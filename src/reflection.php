<?php

if (!function_exists('short_class_name')) {

    /**
     * @param $object
     * @return string
     */
    function short_class_name($object): string
    {
        $objectProperties = new \ReflectionClass($object);

        return $objectProperties->getShortName();
    }
}

if (!function_exists('class_constants')) {

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

/**
 * Get the class name from a php file
 *
 * @param string $filePath
 *
 * @return string|null
 * @see https://github.com/laradic/support/blob/master/src/Util.php
 */
function getClassNameFromFile($filePath)
{
    $tokens = token_get_all(file_get_contents($filePath));
    for ($i = 0; $i < count($tokens); $i++) {
        if ($tokens[$i][0] === T_TRAIT || $tokens[$i][0] === T_INTERFACE) {
            return;
        }
        if ($tokens[$i][0] === T_CLASS) {
            for ($j = $i + 1; $j < count($tokens); $j++) {
                if ($tokens[$j] === '{') {
                    return $tokens[$i + 2][1];
                }
            }
        }
    }
    return null;
}

/**
 * Get the namespace of the php file
 *
 * @param $filePath
 *
 * @return string|null
 * @see https://github.com/laradic/support/blob/master/src/Util.php
 */
function getNamespaceFromFile($filePath)
{
    $namespace = '';
    $tokens = token_get_all(file_get_contents($filePath));
    for ($i = 0; $i < count($tokens); $i++) {
        if ($tokens[$i][0] === T_NAMESPACE) {
            for ($j = $i + 1; $j < count($tokens); $j++) {
                if ($tokens[$j][0] === T_STRING) {
                    $namespace .= '\\' . $tokens[$j][1];
                } else {
                    if ($tokens[$j] === '{' || $tokens[$j] === ';') {
                        return $namespace;
                    }
                }
            }
        }
    }
    return null;
}

/**
 * Get the namespace, classes, interfaces and traits of the php file
 *
 * @param $filePath
 *
 * @return array
 * @see https://github.com/laradic/support/blob/master/src/Util.php
 */
function getPhpDefinitionsFromFile($filePath)
{
    $classes = [];
    $traits = [];
    $interfaces = [];
    $fp = fopen($filePath, 'r');
    $trait = $interface = $class = $namespace = $buffer = '';
    $i = 0;
    while (!$class) {
        if (feof($fp)) {
            break;
        }
        $buffer .= fread($fp, 512);
        $tokens = token_get_all($buffer);
        if (strpos($buffer, '{') === false) {
            continue;
        }
        for (; $i < count($tokens); $i++) {
            if ($tokens[$i][0] === T_NAMESPACE) {
                for ($j = $i + 1; $j < count($tokens); $j++) {
                    if ($tokens[$j][0] === T_STRING) {
                        $namespace .= '\\' . $tokens[$j][1];
                    } else {
                        if ($tokens[$j] === '{' || $tokens[$j] === ';') {
                            break;
                        }
                    }
                }
            }
            if ($tokens[$i][0] === T_CLASS) {
                for ($j = $i + 1; $j < count($tokens); $j++) {
                    if ($tokens[$j] === '{') {
                        $class = $tokens[$i + 2][1];
                        $classes[] = $class;
                    }
                }
            }
            if ($tokens[$i][0] === T_INTERFACE) {
                for ($j = $i + 1; $j < count($tokens); $j++) {
                    if ($tokens[$j] === '{') {
                        $interface = $tokens[$i + 2][1];
                        $interfaces[] = $interface;
                    }
                }
            }
            if ($tokens[$i][0] === T_TRAIT) {
                for ($j = $i + 1; $j < count($tokens); $j++) {
                    if ($tokens[$j] === '{') {
                        $trait = $tokens[$i + 2][1];
                        $traits[] = $trait;
                    }
                }
            }
        }
    }
    return compact('namespace', 'classes', 'traits', 'interfaces');
}
