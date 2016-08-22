<?php

namespace Padosoft\Support\Test;


trait IpDataProvider
{
    /**
     * @return array
     */
    public function ipProvider()
    {
        return [
            'null, null' => [null, null, 'TypeError'],
            '\'\', \'\'' => ['', '', 'TypeError'],
            '\' \', \' \' ' => [' ', ' ', 'TypeError'],
            '\'sdsada\', \' \' ' => ['sdsada', ' ', 'TypeError'],
            '12345678, \' \' ' => [12345678, ' ', 'TypeError'],
            'array(), \' \' ' => [array(), ' ', 'TypeError'],
            'array(), array()' => [array(), array(), ''],
            '[\'REMOTE_ADDR\' => \'192.168.0.18\'], array()' => [['REMOTE_ADDR' => '192.168.0.18'], array(), '192.168.0.18'],
            '[\'REMOTE_ADDR\' => \'192.168.0.18\'], [\'192.168.0.18\']' => [['REMOTE_ADDR' => '192.168.0.18'], ['192.168.0.18'], '192.168.0.18'],
            '[\'REMOTE_ADDR\' => \'192.168.0.18\', \'FORWARDED\' => \'192.168.0.80\'], [\'192.168.0.18\']' => [['REMOTE_ADDR' => '192.168.0.18', 'FORWARDED' => '192.168.0.80'], ['192.168.0.18'], '192.168.0.18'],
            '[\'REMOTE_ADDR\' => \'192.168.0.18\', \'X_FORWARDED_FOR\' => \'\'], [\'192.168.0.18\']' => [['REMOTE_ADDR' => '192.168.0.18', 'X_FORWARDED_FOR' => ''], ['192.168.0.18'], '192.168.0.18'],
            '[\'REMOTE_ADDR\' => \'192.168.0.18\', \'X_FORWARDED_FOR\' => \'192.168.0.80\']' => [['REMOTE_ADDR' => '192.168.0.18', 'X_FORWARDED_FOR' => '192.168.0.80'], ['192.168.0.18'], '192.168.0.80'],
            '11' => [['REMOTE_ADDR' => '192.168.0.18', 'X_FORWARDED_FOR' => '192.168.0.81, 192.168.0.80'], ['192.168.0.18'], '192.168.0.80'],
            '12' => [['REMOTE_ADDR' => '192.168.0.18', 'X_FORWARDED_FOR' => '192.168.0.82, 192.168.0.81, 192.168.0.80'], ['192.168.0.18', '192.168.0.81', '192.168.0.82'], '192.168.0.80'],
        ];
    }
}
