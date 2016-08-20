<?php

/**
 * @param $object
 * @return string
 */
function short_class_name($object): string
{
    $objectProperties = new \ReflectionClass($object);

    return $objectProperties->getShortName();
}

/**
 * Get all class constants that starts with $startsWithFilter
 * or if empty get all class constants.
 * @param $object
 * @param string $startsWithFilter
 * @return array
 * @see https://github.com/spatie-custom/blender/blob/master/app/Foundation/helpers.php
 */
function class_constants($object, string $startsWithFilter = ''): array
{
    $objectProperties = new \ReflectionClass($object);

    $constants = $objectProperties->getConstants();

    if ($startsWithFilter == '') {
        return $constants;
    }

    return array_filter($constants, function ($key) use ($startsWithFilter) {
        return starts_with(strtolower($key), strtolower($startsWithFilter));
    }, ARRAY_FILTER_USE_KEY);
}

if (!function_exists('class_uses_recursive')) {
    /**
     * Returns all traits used by a class, it's subclasses and trait of their traits
     *
     * @param  string $class
     * @return array
     */
    function class_uses_recursive($class)
    {
        $results = [];
        foreach (array_merge([$class => $class], class_parents($class)) as $class) {
            $results += trait_uses_recursive($class);
        }
        return array_unique($results);
    }
}

if (!function_exists('class_basename')) {
    /**
     * Get the class "basename" of the given object / class.
     *
     * @param  string|object $class
     * @return string
     */
    function class_basename($class)
    {
        $class = is_object($class) ? get_class($class) : $class;
        return basename(str_replace('\\', '/', $class));
    }
}
