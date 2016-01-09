<?php

namespace Identicon;

use Identicon\Type\Plain\Identicon;

final class IdenticonFactory
{
    /**
     * @param string $name
     * @param array $options
     * @return IdenticonInterface
     */
    public static function createDefault($name, $options = array())
    {
        return new Identicon($name, $options);
    }

    /**
     * @param string $type
     * @param string $name
     * @param array $options
     * @return IdenticonInterface
     */
    public static function create($type, $name, $options = array())
    {
        if (!$type) {
            throw new \InvalidArgumentException('Empty identicon type received');
        }
        $class = sprintf('\Identicon\Type\%s\Identicon', ucfirst($type));
        if (!class_exists($class)) {
            throw new \InvalidArgumentException(
                sprintf('Type "%s" is not a correct Identicon Type', $type)
            );
        }

        return new $class($name, $options);
    }
}
