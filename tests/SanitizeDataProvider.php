<?php

namespace Padosoft\Support\Test;


trait SanitizeDataProvider
{
    /**
     * @return array
     */
    public function normalizeUtf8StringProvider()
    {
        return [
            'null' => [null, 'TypeError'],
            '\'\'' => ['', ''],
            '\' \'' => [' ', ' '],
            '\'  \'' => ['  ', '  '],
            'ľščťžýáíéČÁŽÝ' => ['ľščťžýáíéČÁŽÝ', 'lsctzyaieCAZY'],
            'ľ š č ť ž ý á í é Č Á Ž Ý' => ['ľ š č ť ž ý á í é Č Á Ž Ý', 'l s c t z y a i e C A Z Y'],
            'èéùòìù' => ['èéùòìù', 'eeuoiu'],
        ];
    }

    /**
     * @return array
     */
    public function sanitize_filenameProvider()
    {
        return [
            'null' => [null, 'TypeError'],
            '\'\'' => ['', ''],
            '\' \'' => [' ', ''],
            '\'  \'' => ['  ', ''],
            'ľščťžýáíéČÁŽÝ.txt' => ['ľščťžýáíéČÁŽÝ.txt', 'lsctzyaieCAZY.txt'],
            'ľ š č ť ž ý á í é Č Á Ž Ý.txt' => ['ľ š č ť ž ý á í é Č Á Ž Ý.txt', 'l s c t z y a i e C A Z Y.txt'],
            'èéùòìù.txt' => ['èéùòìù.txt', 'eeuoiu.txt'],
            'èéùò?ìù.txt' => ['èéùò?ìù.txt', 'eeuoiu.txt'],
            '.htaccess' => ['.htaccess', '.htaccess'],
            'dummy..txt' => ['dummy..txt', 'dummy..txt'],
            'du..mmy..txt' => ['du..mmy..txt', 'du..mmy..txt'],
            'du..txt..' => ['du..txt..', 'du..txt'],
            'du..txt.' => ['du..txt.', 'du..txt'],
            '.' => ['.', ''],
            '..' => ['..', ''],
            'dummy[tab]dummy.txt' => ['dummy'."\t".'dummy.txt', 'dummydummy.txt'],
            'dummy[\r]dummy.txt' => ['dummy'."\r".'dummy.txt', 'dummydummy.txt'],
            'dummy[\n]dummy.txt' => ['dummy'."\n".'dummy.txt', 'dummydummy.txt'],
            // avoid direcory traversal https://www.owasp.org/index.php/Path_Traversal
            '..\.htaccess' => ['..\.htaccess', '.htaccess'],
            '..\..\.htaccess' => ['..\..\.htaccess', '.htaccess'],
            '../.htaccess' => ['../.htaccess', '.htaccess'],
            '../../.htaccess' => ['../../.htaccess', '.htaccess'],
            './.htaccess' => ['./.htaccess', '.htaccess'],
            './..htaccess' => ['./..htaccess', 'htaccess'],
            '..%2fhtaccess' => ['..%2fhtaccess', 'htaccess'],
            '%2e%2e/htaccess' => ['%2e%2e/htaccess', 'htaccess'],
            '%2e%2e%2fhtaccess' => ['%2e%2e%2fhtaccess', 'htaccess'],
            '..%5chtaccess' => ['..%5chtaccess', 'htaccess'],
            '%2e%2e%5chtaccess' => ['%2e%2e%5chtaccess', 'htaccess'],
            '%252e%252e%255chtaccess' => ['%252e%252e%255chtaccess', 'htaccess'],
            '..%255chtaccess' => ['..%255chtaccess', 'htaccess'],
            '..%c0%afhtaccess' => ['..%c0%afhtaccess', 'htaccess'],
            '..%c1%9chtaccess' => ['..%c1%9chtaccess', 'htaccess'],
        ];
    }

    /**
     * @return array
     */
    public function sanitize_filenameNoWiteSpacesProvider()
    {
        return [
            'null' => [null, 'TypeError'],
            '\'\'' => ['', ''],
            '\' \'' => [' ', '_'],
            '\'  \'' => ['  ', '__'],
            'ľščťžýáíéČÁŽÝ.txt' => ['ľščťžýáíéČÁŽÝ.txt', 'lsctzyaieCAZY.txt'],
            'ľ š č ť ž ý á í é Č Á Ž Ý.txt' => ['ľ š č ť ž ý á í é Č Á Ž Ý.txt', 'l_s_c_t_z_y_a_i_e_C_A_Z_Y.txt'],
            'èéùòìù.txt' => ['èéùòìù.txt', 'eeuoiu.txt'],
            'èéùò?ìù.txt' => ['èéùò?ìù.txt', 'eeuoiu.txt'],
            '.htaccess' => ['.htaccess', '.htaccess'],
            'dummy..txt' => ['dummy..txt', 'dummy..txt'],
            'du..mmy..txt' => ['du..mmy..txt', 'du..mmy..txt'],
            'du..txt..' => ['du..txt..', 'du..txt'],
            'du..txt.' => ['du..txt.', 'du..txt'],
            '.' => ['.', ''],
            '..' => ['..', ''],
            // avoid direcory traversal https://www.owasp.org/index.php/Path_Traversal
            '..\.htaccess' => ['..\.htaccess', '.htaccess'],
            '..\..\.htaccess' => ['..\..\.htaccess', '.htaccess'],
            '../.htaccess' => ['../.htaccess', '.htaccess'],
            '../../.htaccess' => ['../../.htaccess', '.htaccess'],
            './.htaccess' => ['./.htaccess', '.htaccess'],
            './..htaccess' => ['./..htaccess', 'htaccess'],
            '..%2fhtaccess' => ['..%2fhtaccess', 'htaccess'],
            '%2e%2e/htaccess' => ['%2e%2e/htaccess', 'htaccess'],
            '%2e%2e%2fhtaccess' => ['%2e%2e%2fhtaccess', 'htaccess'],
            '..%5chtaccess' => ['..%5chtaccess', 'htaccess'],
            '%2e%2e%5chtaccess' => ['%2e%2e%5chtaccess', 'htaccess'],
            '%252e%252e%255chtaccess' => ['%252e%252e%255chtaccess', 'htaccess'],
            '..%255chtaccess' => ['..%255chtaccess', 'htaccess'],
            '..%c0%afhtaccess' => ['..%c0%afhtaccess', 'htaccess'],
            '..%c1%9chtaccess' => ['..%c1%9chtaccess', 'htaccess'],
        ];
    }

    /**
     * @return array
     */
    public function sanitize_pathnameProvider()
    {
        return [
            'null' => [null, 'TypeError'],
            '\'\'' => ['', ''],
            '\' \'' => [' ', '_'],
            '\'  \'' => ['  ', '__'],
            'ľščťžýáíéČÁŽÝ.txt' => ['ľščťžýáíéČÁŽÝ.txt', 'lsctzyaieCAZY.txt'],
            'ľ š č ť ž ý á í é Č Á Ž Ý.txt' => ['ľ š č ť ž ý á í é Č Á Ž Ý.txt', 'l_s_c_t_z_y_a_i_e_C_A_Z_Y.txt'],
            'èéùòìù.txt' => ['èéùòìù.txt', 'eeuoiu.txt'],
            'èéùò?ìù.txt' => ['èéùò?ìù.txt', 'eeuoiu.txt'],
            '.htaccess' => ['.htaccess', '.htaccess'],
            'dummy..txt' => ['dummy..txt', 'dummy..txt'],
            'du..mmy..txt' => ['du..mmy..txt', 'du..mmy..txt'],
            'du..txt..' => ['du..txt..', 'du..txt'],
            'du..txt.' => ['du..txt.', 'du..txt'],
            '.' => ['.', ''],
            '..' => ['..', ''],
            // avoid direcory traversal https://www.owasp.org/index.php/Path_Traversal
            '..\.htaccess' => ['..\.htaccess', '.htaccess'],
            '..\..\.htaccess' => ['..\..\.htaccess', '.htaccess'],
            '../.htaccess' => ['../.htaccess', '.htaccess'],
            '../../.htaccess' => ['../../.htaccess', '.htaccess'],
            './.htaccess' => ['./.htaccess', '.htaccess'],
            './..htaccess' => ['./..htaccess', 'htaccess'],
            '..%2fhtaccess' => ['..%2fhtaccess', 'htaccess'],
            '%2e%2e/htaccess' => ['%2e%2e/htaccess', 'htaccess'],
            '%2e%2e%2fhtaccess' => ['%2e%2e%2fhtaccess', 'htaccess'],
            '..%5chtaccess' => ['..%5chtaccess', 'htaccess'],
            '%2e%2e%5chtaccess' => ['%2e%2e%5chtaccess', 'htaccess'],
            '%252e%252e%255chtaccess' => ['%252e%252e%255chtaccess', 'htaccess'],
            '..%255chtaccess' => ['..%255chtaccess', 'htaccess'],
            '..%c0%afhtaccess' => ['..%c0%afhtaccess', 'htaccess'],
            '..%c1%9chtaccess' => ['..%c1%9chtaccess', 'htaccess'],
        ];
    }

    /**
     * @return array
     */
    public function sheProvider()
    {
        if(windows_os()){
            return $this->sheProviderWindows();
        }else{
            return $this->sheProviderLinux();
        }
    }

    /**
     * @return array
     */
    public function sheProviderWindows()
    {
        return [
            'null' => [null, 'TypeError'],
            '\'\'' => ['', '""'],
            '\' \'' => [' ', '" "'],
            '\'  \'' => ['  ', '"  "'],
            '.htaccess' => ['.htaccess', '".htaccess"'],
            'dummy..txt' => ['dummy..txt', '"dummy..txt"'],
            'du..mmy..txt' => ['du..mmy..txt', '"du..mmy..txt"'],
            'du..txt..' => ['du..txt..', '"du..txt.."'],
            'du..txt.' => ['du..txt.', '"du..txt."'],
            '.' => ['.', '"."'],
            '..' => ['..', '".."'],
            '..\.htaccess' => ['..\.htaccess', '"..\\\\.htaccess"'],
            '%2e%2e%5chtaccess' => ['%2e%2e%5chtaccess', '"%2e%2e%5chtaccess"'],
            'he\'s mine' => ['he\'s mine', '"he\'s mine"'],
        ];
    }

    /**
     * @return array
     */
    public function sheProviderLinux()
    {
        return [
            'null' => [null, 'TypeError'],
            '\'\'' => ['', '\'\''],
            '\' \'' => [' ', '\' \''],
            '\'  \'' => ['  ', '\'  \''],
            '.htaccess' => ['.htaccess', '\'.htaccess\''],
            'dummy..txt' => ['dummy..txt', '\'dummy..txt\''],
            'du..mmy..txt' => ['du..mmy..txt', '\'du..mmy..txt\''],
            'du..txt..' => ['du..txt..', '\'du..txt..\''],
            'du..txt.' => ['du..txt.', '\'du..txt.\''],
            '.' => ['.', '\'.\''],
            '..' => ['..', '\'..\''],
            '..\.htaccess' => ['..\.htaccess', '\'..\\.htaccess\''],
            '%2e%2e%5chtaccess' => ['%2e%2e%5chtaccess', '\'%2e%2e%5chtaccess\''],
            'he\'s mine' => ['he\'s mine', '\'he\'\\\'\'s mine\''],
        ];
    }
    /**
     * @return array
     */
    public function sanitize_phoneProvider()
    {
        return [
            'null, false' => [null, false, ''],
            '\'\', false' => ['', false, ''],
            '\' \', false' => [' ', false, ''],
            '\'  \', false' => ['  ', false, ''],
            'aaaaaa, false' => ['aaaaaa', false, ''],
            '123sdfsf456, false' => ['123sdfsf456', false, '123456'],
            '123 456, false' => ['123 456', false, '123456'],
            '123 456, true' => ['123 456', true, '123 456'],
            '123456, false' => ['123456', false, '123456'],
            '+123456, false' => ['+123456', false, '+123456'],
            '+39 123a456, false' => ['+39 123a456', false, '+39123456'],
            '+39 123a456, true' => ['+39 123a456', true, '+39 123 456'],
            '+39 (123a)456, false' => ['+39 (123a)456', false, '+39(123)456'],
            '+39 (123a)456, true' => ['+39 (123a)456', true, '+39(123)456'],
            '+39 (123a 456, false' => ['+39 (123a 456', false, '+39123456'],
            '+39 (123a 456, true' => ['+39 (123a 456', true, '+39 123 456'],
            '+39 123)a 456, false' => ['+39 123)a 456', false, '+39123456'],
            '+39 123)a 456, true' => ['+39 123)a 456', true, '+39 123 456'],
        ];
    }
}
