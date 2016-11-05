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
            'null, false' => [null, false, 'TypeError'],
            '\'\', false' => ['', false, ''],
            '\' \', false' => [' ', false, ' '],
            '\'  \', false' => ['  ', false, ' '],
            '\'   \', false' => ['   ', false, ' '],
            '\'I am   Lorenzo.\', false' => ['I am   Lorenzo.', false, 'I am Lorenzo.'],
            '\'I   am   Lorenzo.\', false' => ['I   am   Lorenzo.', false, 'I am Lorenzo.'],
            '\'I &nbsp;am&nbsp;&nbsp;Lorenzo.\', true' => ['I &nbsp;am&nbsp;&nbsp;Lorenzo.', true, 'I am Lorenzo.'],
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

    /**
     * @return array
     */
    public function slugfyProvider()
    {
        return [
            'null, null' => [null, null, 'TypeError'],
            '\'\', null' => ['', null, 'TypeError'],
            '\'\', \'\'' => ['', '', ''],
            '0, \'-\'' => [0, '-', '0'],
            '\' foo  bar \', \'-\'' => [' foo  bar ', '-', 'foo-bar'],
            '\'foo -.-"-...bar\', \'-\'' => ['foo -.-"-...bar', '-', 'foo-bar'],
            '\'another..& foo -.-"-...bar\', \'-\'' => ['another..& foo -.-"-...bar', '-', 'another-foo-bar'],
            '\' Foo d\'Bar \', \'-\'' => [' Foo d\'Bar ', '-', 'foo-dbar'],
            '\'A string-with-dashes\', \'-\'' => ['A string-with-dashes', '-', 'a-string-with-dashes'],
            '\'Using strings like fòô bàř\', \'-\'' => ['Using strings like fòô bàř', '-', 'using-strings-like-foo-bar'],
            '\'numbers 1234\', \'-\'' => ['numbers 1234', '-', 'numbers-1234'],
            '\'śšşсσșςစſს\', \'-\'' => ['śšşсσșςစſს', '-', 'ssssssssss'],
            '\'°₀۰\', \'-\'' => ['°₀۰', '-', '000'],
            '\'Foo bar baz\', \'_\'' => ['Foo bar baz', '_', 'foo_bar_baz'],
            '\'A_string with_underscores\', \'_\'' => ['A_string with_underscores', '_', 'a_string_with_underscores'],
            '\'--   An odd__   string-_\', \'-\'' => ['--   An odd__   string-_', '-', 'an-odd-string'],
            '\'A string / strong\', \'-\'' => ['A string / strong', '-', 'a-string-strong'],
            '\'A string \ strong\', \'-\'' => ['A string \ strong', '-', 'a-string-strong'],
            '\'foo           Bar\' (spaces U+2000 to U+200A), \'-\'' => ['foo           Bar', '-', 'foo-bar'],
            '\'foo Bar\' (no-break space (U+00A0)), \'-\'' => ['foo Bar', '-', 'foo-bar'],
            '\'foo𐍉Bar\' (some uncommon, unsupported character (U+10349)), \'-\'' => ['foo𐍉Bar', '-', 'foobar'],
        ];
    }
}
