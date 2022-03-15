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



if (! function_exists('trait_uses_recursive')) {
    /**
     * Returns all traits used by a trait and its traits.
     * see: Illuminate/Support/helpers.php
     *
     * @param  string  $trait
     * @return array
     */
    function trait_uses_recursive($trait)
    {
        $traits = class_uses($trait) ?: [];

        foreach ($traits as $trait2) {
            $traits += trait_uses_recursive($trait2);
        }

        return $traits;
    }
}

if (! function_exists('class_uses_recursive')) {
    /**
     * Returns all traits used by a class, its parent classes and trait of their traits.
     * see: Illuminate/Support/helpers.php
     *
     * @param  object|string  $class
     * @return array
     */
    function class_uses_recursive($class)
    {
        if (is_object($class)) {
            $class = get_class($class);
        }

        $results = [];

        foreach (array_reverse(class_parents($class)) + [$class => $class] as $class) {
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
    $iMax = count($tokens);
    for ($i = 0; $i < $iMax; $i++) {
        if ($tokens[$i][0] === T_TRAIT || $tokens[$i][0] === T_INTERFACE) {
            return;
        }
        if ($tokens[$i][0] === T_CLASS) {
            for ($j = $i + 1; $j < $iMax; $j++) {
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
    $iMax = count($tokens);
    for ($i = 0; $i < $iMax; $i++) {
        if ($tokens[$i][0] === T_NAMESPACE) {
            for ($j = $i + 1; $j < $iMax; $j++) {
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
    $class = $namespace = $buffer = '';
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
        $iMax = count($tokens);
        for (; $i < $iMax; $i++) {
            if ($tokens[$i][0] === T_NAMESPACE) {
                for ($j = $i + 1; $j < $iMax; $j++) {
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
                for ($j = $i + 1; $j < $iMax; $j++) {
                    if ($tokens[$j] === '{') {
                        $class = $tokens[$i + 2][1];
                        $classes[] = $class;
                    }
                }
            }
            if ($tokens[$i][0] === T_INTERFACE) {
                for ($j = $i + 1; $j < $iMax; $j++) {
                    if ($tokens[$j] === '{') {
                        $interface = $tokens[$i + 2][1];
                        $interfaces[] = $interface;
                    }
                }
            }
            if ($tokens[$i][0] === T_TRAIT) {
                for ($j = $i + 1; $j < $iMax; $j++) {
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
