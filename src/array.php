<?php

if (!function_exists('get')) {
    /**
     * Get an item from an array using "dot" notation.
     *
     * @param  array $array
     * @param  string $key
     * @param  mixed $default
     * @return mixed
     */
    function get($array, $key, $default = null)
    {
        if (is_null($key)) {
            return $array;
        }
        if (isset($array[$key])) {
            return $array[$key];
        }
        foreach (explode('.', $key) as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return value($default);
            }
            $array = $array[$segment];
        }
        return $array;
    }
}

if (!function_exists('set')) {
    /**
     * Set an array item to a given value using "dot" notation.
     *
     * If no key is given to the method, the entire array will be replaced.
     *
     * @param  array $array
     * @param  string $key
     * @param  mixed $value
     * @return array
     */
    function set(&$array, $key, $value)
    {
        if (is_null($key)) {
            return $array = $value;
        }
        $keys = explode('.', $key);
        while (count($keys) > 1) {
            $key = array_shift($keys);
            // If the key doesn't exist at this depth, we will just create an empty array
            // to hold the next value, allowing us to create the arrays to hold final
            // values at the correct depth. Then we'll keep digging into the array.
            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = array();
            }
            $array =& $array[$key];
        }
        $array[array_shift($keys)] = $value;
        return $array;
    }
}

if (!function_exists('head')) {
    /**
     * Get the first element of an array. Useful for method chaining.
     *
     * @param  array $array
     * @return mixed
     */
    function head($array)
    {
        return reset($array);
    }
}

if (!function_exists('last')) {
    /**
     * Get the last element from an array.
     *
     * @param  array $array
     * @return mixed
     */
    function last($array)
    {
        return end($array);
    }
}

if (!function_exists('array_has')) {
    /**
     * Check if an item exists in an array using "dot" notation.
     *
     * @param  array $array
     * @param  string $key
     * @return bool
     */
    function array_has($array, $key)
    {
        if (empty($array) || is_null($key)) {
            return false;
        }
        if (array_key_exists($key, $array)) {
            return true;
        }
        foreach (explode('.', $key) as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return false;
            }
            $array = $array[$segment];
        }
        return true;
    }
}

if (!function_exists('array_get')) {
    /**
     * Get an item from an array using "dot" notation.
     *
     * @param  array $array
     * @param  string $key
     * @param  mixed $default
     * @return mixed
     */
    function array_get($array, $key, $default = null)
    {
        if (is_null($key)) {
            return $array;
        }
        if (isset($array[$key])) {
            return $array[$key];
        }
        foreach (explode('.', $key) as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return value($default);
            }
            $array = $array[$segment];
        }
        return $array;
    }
}

/**
 * Return an array with only integers value contained in the array passed
 * @param array $array
 * @return array
 **/
function CleanUpArrayOfInt($array)
{
    $result = array();
    if (!is_array($array) || count($array) < 1) {
        return $result;
    }
    reset($array);
    while (list($key, $value) = each($array)) {
        if (isInteger($value)) {
            $result[] = $value;
        }
    }
    reset($array);

    return $result;
}

/**
 * Returns an array with two elements.
 *
 * Iterates over each value in the array passing them to the callback function.
 * If the callback function returns true, the current value from array is returned in the first
 * element of result array. If not, it is return in the second element of result array.
 *
 * Array keys are preserved.
 *
 * @param array $array
 * @param callable $callback
 * @return array
 * @see https://github.com/spatie/array-functions/blob/master/src/array_functions.php
 */
function array_split_filter(array $array, callable $callback)
{
    $passesFilter = array_filter($array, $callback);
    $negatedCallback = function ($item) use ($callback) {
        return !$callback($item);
    };
    $doesNotPassFilter = array_filter($array, $negatedCallback);
    return [$passesFilter, $doesNotPassFilter];
}

/**
 * Checks whether specific value exists in array of object.
 * For exampe, following code
 *  $exist = in_array_column([['id' => 1], ['id' => 2], ['id' => 3]], 3, 'id');
 * will produce 2
 * @author wapmorgan
 * @since 2015.05.19
 * @param array $haystack Source array
 * @param mixed $needle Needed value
 * @param string $column Column to perform search
 * @param bool $strict Should search be strict or not.
 * @return bool True if value exists in array, False otherwise.
 * @see modified from https://github.com/wapmorgan/php-functions-repository/blob/master/i/in_array_column.php
 */
function in_array_column($haystack, $needle, $column, $strict = false)
{
    foreach ($haystack as $k => $elem) {
        if ((!$strict && $elem[$column] == $needle) || ($strict && $elem[$column] === $needle)) {
            return true;
        }
    }
    return false;
}
