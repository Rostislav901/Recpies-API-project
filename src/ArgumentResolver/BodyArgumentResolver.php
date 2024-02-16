<?php

namespace App\ArgumentResolver;

use App\Exceptions\UserDataNotConvertException;
use App\Exceptions\ValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Attribute\Body;
class BodyArgumentResolver implements ValueResolverInterface
{

    public function __construct(private SerializerInterface $serializer,private ValidatorInterface $validator){}
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (!$argument->getAttributesOfType(Body::class, ArgumentMetadata::IS_INSTANCEOF)) {
            return [];
        }

        try {
            $model = $this->serializer->deserialize(
                $request->getContent(),
                $argument->getType(),
                JsonEncoder::FORMAT
            );
        } catch (\Throwable $throwable) {
            throw new UserDataNotConvertException();
        }

        $errors = $this->validator->validate($model);
        if (count($errors) > 0)
        {
            throw new ValidationException();
        }

        return [$model];
    }
    }

