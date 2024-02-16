<?php

namespace App\Tests\ArgumentResolver;

use App\ArgumentResolver\BodyArgumentResolver;
use App\Attribute\Body;
use App\Exceptions\UserDataNotConvertException;
use App\Exceptions\ValidationException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BodyArgumentResolverTest extends TestCase
{
    private SerializerInterface $serializer;
    private ValidatorInterface $validator;
    protected function setUp(): void
    {
        parent::setUp();
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->validator = $this->createMock(ValidatorInterface::class);
    }

    public function testResolveReturnEmptyArray()
    {
        $actual = $this->runResolver(request: new Request());
        $this->assertEquals([],$actual);
    }

    public function testResolveUserDataNotConvertException()
    {
        $request = (new Request());
        $request->initialize(content: "test");

        $this->serializer->expects($this->once())
                    ->method("deserialize")
                    ->with("test","object",JsonEncoder::FORMAT)
                    ->willThrowException(new \LogicException("test"));

        $this->expectException(UserDataNotConvertException::class);

        $this->runResolver($request,new Body());
    }


    public function testResolveValidationException()
    {
        $request = new Request();
        $request->initialize(content: "test");

        $this->serializer->expects($this->once())
            ->method("deserialize")
            ->with("test","object",JsonEncoder::FORMAT)
            ->willReturn("testModel");

        $this->validator->expects($this->once())
            ->method("validate")
            ->with("testModel")
            ->willReturn(new ConstraintViolationList(
                        [new ConstraintViolation(message: "test", messageTemplate: "test", parameters: ["test"],
                                                 root: "test", propertyPath: "/test", invalidValue: "test")]));

        $this->expectException(ValidationException::class);

        $this->runResolver($request,new Body());


    }

    public function testResolve()
    {
        $request = new Request();
        $request->initialize(content: "test");

        $this->serializer->expects($this->once())
            ->method("deserialize")
            ->with("test","object",JsonEncoder::FORMAT)
            ->willReturn("testModel");

        $this->validator->expects($this->once())
            ->method("validate")
            ->with("testModel")
            ->willReturn(new ConstraintViolationList());


        $this->assertEquals(["testModel"],$this->runResolver($request,new Body()));
    }


    private function createArgumentMetadata(?object $attribute = null) : ArgumentMetadata
    {
       $attribute =  $attribute === null ? new class extends \stdClass{} : $attribute;
       return new  ArgumentMetadata(name: "test", type: "object", isVariadic: false, hasDefaultValue: false,
            defaultValue: "", attributes: [$attribute]);
    }

    private function runResolver(Request $request,?object $attribute = null) : iterable
    {
        $argument = $this->createArgumentMetadata($attribute);

        return (new BodyArgumentResolver($this->serializer,$this->validator))->resolve($request,$argument);
    }

}
