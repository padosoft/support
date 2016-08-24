<?php

namespace Padosoft\Support\Test;


trait StringDataProvider
{
    /**
     * @return array
     */
    public function str_replace_multiple_spaceProvider()
    {
        return [
            'null' => [null, 'TypeError'],
            '\'\'' => ['', ''],
            '\' \'' => [' ', ' '],
            '\'  \'' => ['  ', ' '],
            '\'   \'' => ['   ', ' '],
            '\'I am   Lorenzo.\'' => ['I am   Lorenzo.', 'I am Lorenzo.'],
            '\'I   am   Lorenzo.\'' => ['I   am   Lorenzo.', 'I am Lorenzo.'],
        ];
    }

    /**
     * @return array
     */
    public function str_replace_lastProvider()
    {
        return [
            'null, null, null' => [null, null, null, 'TypeError'],
            '\'\', null, null' => ['', null, null, 'TypeError'],
            '\'\', \'\', null' => ['', '', null, 'TypeError'],
            '\'\', \'\', \'\'' => ['', '', '', ''],
            '/, -, /' => ['/', '-', '/', '-'],
            '/, -, /var/www/' => ['/', '-', '/var/www/', '/var/www-'],
        ];
    }

    /**
     * @return array
     */
    public function generateRandomPasswordProvider()
    {
        return [
            'null, null' => [null, null, 'TypeError'],
            '\'\', null' => ['', null, 'TypeError'],
            '\'\', \'\'' => ['', '', 'TypeError'],
            '0, 0' => [0, 0, true],
            '1, 0' => [1, 0, true],
            '10, 0' => [10, 0, true],
            '10, 1' => [10, 1, true],
            '10, 2' => [10, 2, true],
            '10, 3' => [10, 3, true],
            '10, 4' => [10, 4, true],
        ];
    }
}
