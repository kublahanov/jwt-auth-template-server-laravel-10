<?php

namespace App\Helpers;

use ReflectionClass;
use ReflectionException;

class TestHelper
{
    /**
     * @throws ReflectionException
     */
    public static function invokeProtectedMethod($object, $methodName, array $parameters = [])
    {
        $reflection = new ReflectionClass($object);
        $method = $reflection->getMethod($methodName);

        /** @var mixed $method */
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
