<?php

namespace App\Tests\Service\ExceptionHandler;

use App\Service\ExceptionHandler\ExceptionMapping;
use App\Service\ExceptionHandler\ExceptionMappingResolver;
use InvalidArgumentException;
use LogicException;
use PHPUnit\Framework\TestCase;

class ExceptionMappingResolverTest extends TestCase
{

    public function testInvalidArgumentException()
    {

        $this->expectException(InvalidArgumentException::class);

        new ExceptionMappingResolver(["ClassExceptions"=>[]]);

    }

    public function testResolveReturnNull()
    {


        $actual =  (new ExceptionMappingResolver([]))->resolve(InvalidArgumentException::class);

        $this->assertNull($actual);
    }

    public function testResolve()
    {

        $actual =  (new ExceptionMappingResolver([InvalidArgumentException::class=>["code"=>403,"hidden"=>false,"loggable"=>true]]))
                                                ->resolve(InvalidArgumentException::class);

        $expected = new ExceptionMapping(403,false,true);
        $this->assertEquals($expected,$actual);
    }
    public function testResolveSubClass()
    {
        $actual =  (new ExceptionMappingResolver([LogicException::class=>["code"=>403]]))
            ->resolve(InvalidArgumentException::class);

        $expected = new ExceptionMapping(403,true,false);
        $this->assertEquals($expected,$actual);

    }
}
