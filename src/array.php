<?php

if (!function_exists('getEx')) {
    /**
     * Get an item from an array using "dot" notation.
     *
     * @param array $array
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function getEx($array, $key, $default = null)
    {
        if (is_null($key)) {
            return $array;
        }
        if (isset($array[$key])) {
            return $array[$key];
        }
        foreach (explode('.', $key) as $segment) {
            if (!array_key_exists_safe($segment, $array)) {
                return valueEx($default);
            }
            $array = $array[$segment];
        }
        return $array;
    }
}

if (!function_exists('headEx')) {
    /**
     * Get the first element of an array. Useful for method chaining.
     *
     * @param array $array
     * @return mixed
     */
    function headEx($array)
    {
        return reset($array);
    }
}

if (!function_exists('lastEx')) {
    /**
     * Get the last element from an array.
     *
     * @param array $array
     * @return mixed
     */
    function lastEx($array)
    {
        return end($array);
    }
}

if (!function_exists('insert_at_topEx')) {

    /**
     * Insert element in top of array and return $count element.
     * @param $my_arr
     * @param $elem
     * @param int $count
     * @return bool
     * @see https://github.com/ifsnop/lpsf/blob/master/src/Ifsnop/functions.inc.php
     */
    function insert_at_topEx(&$my_arr, $elem, int $count = 10): bool
    {
        if (!is_array($my_arr) || is_null($elem)) {
            return false;
        }
        array_splice($my_arr, $count - 1);
        array_unshift($my_arr, $elem);    // Shift every element down one, and insert new at top
        return true;
    }
}

if (!function_exists('array_hasEx')) {
    /**
     * Check if an item exists in an array using "dot" notation.
     *
     * @param array $array
     * @param string $key
     * @return bool
     */
    function array_hasEx($array, $key)
    {
        if (empty($array) || is_null($key)) {
            return false;
        }
        if (array_key_exists($key, $array)) {
            return true;
        }
        foreach (explode('.', $key) as $segment) {
            if (!array_key_exists_safe($segment, $array)) {
                return false;
            }
            $array = $array[$segment];
        }
        return true;
    }
}

if (!function_exists('array_accessibleEx')) {
    /**
     * Determine whether the given value is array accessible.
     * See: https://github.com/illuminate/support/blob/master/Arr.php
     *
     * @param mixed $value
     * @return bool
     */
    function array_accessibleEx($value)
    {
        return is_array($value) || $value instanceof ArrayAccess;
    }
}

if (!function_exists('array_getEx')) {
    /**
     * Get an item from an array using "dot" notation.
     *
     * @param  array $array
     * @param  string $key
     * @param  mixed $default
     * @return mixed
     */
    function array_getEx($array, $key, $default = null)
    {
        if (!array_accessibleEx($array)) {
            return valueEx($default);
        }

        if (is_null($key)) {
            return $array;
        }

        if (array_key_exists_safe($array, $key)) {
            return $array[$key];
        }

        if (strpos($key, '.') === false) {
            return $array[$key] ?? valueEx($default);
        }

        foreach (explode('.', $key) as $segment) {
            if (!array_accessibleEx($array) || !array_key_exists_safe($array, $segment)) {
                return valueEx($default);
            }
            $array = $array[$segment];
        }
        return $array;
    }
}

if (!function_exists('array_setEx')) {
    /**
     * Set an array item to a given value using "dot" notation.
     * If no key is given to the method, the entire array will be replaced.
     *
     * @see: https://github.com/illuminate/support/blob/master/Arr.php
     *
     * @param array $array
     * @param string|null $key
     * @param mixed $value
     * @return array
     */
    function array_setEx(&$array, $key, $value)
    {
        if (is_null($key)) {
            return $array = $value;
        }

        $keys = explode('.', $key);

        foreach ($keys as $i => $key) {
            if (count($keys) === 1) {
                break;
            }

            unset($keys[$i]);

            // If the key doesn't exist at this depth, we will just create an empty array
            // to hold the next value, allowing us to create the arrays to hold final
            // values at the correct depth. Then we'll keep digging into the array.
            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = [];
            }

            $array = &$array[$key];
        }

        $array[array_shift($keys)] = $value;

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
    if (isNullOrEmptyArray($array)) {
        return $result;
    }
    reset($array);
    foreach ($array as $key => $value) {
        if (isInteger($value)) {
            $result[] = $value;
        }
    }
    reset($array);

    return $result;
}

if (!function_exists('array_split_filter')) {
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
}

if (!function_exists('in_array_column')) {
    /**
     * Checks whether specific value exists in array of object.
     * For exampe, following code
     *  $exist = in_array_column([['id' => 1], ['id' => 2], ['id' => 3]], 3, 'id');
     * will produce 2
     * @param array $haystack Source array
     * @param mixed $needle Needed value
     * @param string $column Column to perform search
     * @param bool $strict Should search be strict or not.
     * @return bool True if value exists in array, False otherwise.
     * @since 2015.05.19
     * @author wapmorgan
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
}

if (!function_exists('objectToArray')) {

    /**
     * Convert objecte to the array.
     *
     * @param $object
     *
     * @return array
     * @see https://github.com/ngfw/Recipe/blob/master/src/ngfw/Recipe.php
     */
    function objectToArray($object): array
    {
        if (!is_object($object) && !is_array($object)) {
            return [];
        }
        if (is_object($object)) {
            $object = get_object_vars($object);
        }
        return array_map('objectToArray', $object);
    }
}

if (!function_exists('arrayToObject')) {

    /**
     * Convert array to the object.
     *
     * @param array $array PHP array
     *
     * @return stdClass (object)
     * @see https://github.com/ngfw/Recipe/blob/master/src/ngfw/Recipe.php
     */
    function arrayToObject(array $array = []): \stdClass
    {
        $object = new \stdClass();

        if (!is_array($array) || count($array) < 1) {
            return $object;
        }

        foreach ($array as $name => $value) {
            if (is_array($value)) {
                $object->$name = arrayToObject($value);
                continue;
            }
            $object->{$name} = $value;
        }
        return $object;
    }
}

if (!function_exists('arrayToString')) {

    /**
     * Convert Array to string
     * expected output: <key1>="value1" <key2>="value2".
     *
     * @param array $array array to convert to string
     *
     * @return string
     * @see https://github.com/ngfw/Recipe/blob/master/src/ngfw/Recipe.php
     */
    function arrayToString(array $array = []): string
    {
        if (isNullOrEmptyArray($array)) {
            return '';
        }

        $string = '';
        foreach ($array as $key => $value) {
            $string .= $key . '="' . $value . '" ';
        }
        return rtrim($string, ' ');
    }
}

if (!function_exists('array_key_exists_safe')) {

    /**
     * Check if a key exists in array
     * @param array $array
     * @param string $key
     * @return bool
     */
    function array_key_exists_safe(array $array, string $key): bool
    {
        if (isNullOrEmptyArray($array) || isNullOrEmpty($key)) {
            return false;
        }

        return array_key_exists($key, $array);
    }
}

if (!function_exists('array_get_key_value_safe')) {

    /**
     * Retrieve a single key from an array.
     * If the key does not exist in the array, or array is null or empty
     * the default value will be returned instead.
     * @param array $array
     * @param string $key
     * @param string $default
     * @return mixed
     */
    function array_get_key_value_safe(array $array, string $key, $default = null)
    {
        if (isNullOrEmptyArray($array) || isNullOrEmpty($key) || !array_key_exists($key, $array)) {
            return $default;
        }

        return $array[$key];
    }
}

if (!function_exists('isNullOrEmptyArray')) {
    /**
     * Check if array is null or empty.
     * @param $array
     * @return bool
     */
    function isNullOrEmptyArray($array): bool
    {
        return $array === null || !is_array($array) || count($array) < 1;
    }
}

if (!function_exists('isNullOrEmptyArrayKey')) {
    /**
     * Check if an array key not exits or exists and is null or empty.
     * @param $array
     * @param string $key
     * @param bool $withTrim if set to true (default) check if trim()!='' too.
     * @return bool
     */
    function isNullOrEmptyArrayKey(array $array, string $key, bool $withTrim = true): bool
    {
        return !array_key_exists_safe($array, $key) || $array[$key] === null || isNullOrEmpty($array[$key], $withTrim);
    }
}

if (!function_exists('isNotNullOrEmptyArray')) {

    /**
     * Check if array is not null and not empty.
     * @param $array
     * @return bool
     */
    function isNotNullOrEmptyArray($array): bool
    {
        return !isNullOrEmptyArray($array);
    }
}

if (!function_exists('isNotNullOrEmptyArrayKey')) {

    /**
     * Check if an array key exists and is not null and not empty.
     * @param $array
     * @param string $key
     * @param bool $withTrim if set to true (default) check if trim()!='' too.
     * @return bool
     */
    function isNotNullOrEmptyArrayKey(array $array, string $key, bool $withTrim = true): bool
    {
        return !isNullOrEmptyArrayKey($array, $key, $withTrim);
    }
}

if (!function_exists('array_remove_columns')) {

    /**
     * Remove given column from the subarrays of a two dimensional array.
     * @param $array
     * @param int $columnToRemove
     * @return array
     */
    function array_remove_columns(array $array, int $columnToRemove): array
    {
        if (count($array) < 1) {
            return [];
        }

        $numCol = count($array[0]);

        if ($columnToRemove == 1 && isInRange($numCol, 0, 1)) {
            return [];
        }

        foreach ($array as &$element) {
            if ($columnToRemove == 1) {
                $element = array_slice($element, 1, $numCol - 1);
            } elseif ($columnToRemove == $numCol) {
                $element = array_slice($element, 0, $numCol - 1);
            } else {
                $element1 = array_slice($element, 0, $columnToRemove - 1);
                $element2 = array_slice($element, $columnToRemove, $numCol - $columnToRemove);
                $element = array_merge($element1, $element2);
            }
        }

        if (!is_array($array)) {
            return [];
        }

        return $array;
    }
}

if (!function_exists('array_remove_first_columns')) {

    /**
     * Remove first column from the subarrays of a two dimensional array.
     * @param $array
     * @return array
     */
    function array_remove_first_columns(array $array): array
    {
        return array_remove_columns($array, 1);
    }
}

if (!function_exists('array_remove_last_columns')) {

    /**
     * Remove last column from the subarrays of a two dimensional array.
     * @param $array
     * @return array
     */
    function array_remove_last_columns(array $array): array
    {
        if (count($array) < 1) {
            return [];
        }

        $numCol = count($array[0]);

        return array_remove_columns($array, $numCol);
    }
}
