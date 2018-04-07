<?php
namespace Lusiweb\Botman\Test\Unit;

/**
 * Class AbstractTestUnit
 * @package Lusiweb\Botman\Test\Unit
 */
abstract class AbstractTestUnit extends \PHPUnit_Framework_TestCase
{
    /**
     * Call protected/private method of a class.
     *
     * @param   $object
     * @param   $methodName
     * @param   array $parameters
     * @return  mixed
     * @throws  \ReflectionException
     */
    public function invokeMethod(&$object, $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /**
     * Calls class properties.
     *
     * @param   $object
     * @param   $propertyName
     * @return  mixed
     * @throws  \ReflectionException
     */
    public function invokeProperties(&$object, $propertyName)
    {
        $reflection = new \ReflectionClass(get_class($object));
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        return $property
            ->getValue($object);
    }
}
