<?php

namespace Padosoft\Support\Test;


trait XmlDataProvider
{
    /**
     * @return array
     */
    public function xmlProvider()
    {
        return [
            'null, null, null' => [null, null, null, 'TypeError'],
            '\'\', \'\', \'\'' => ['', '', '', 'TypeError'],
            '\' \', \' \', \' \' ' => [' ', ' ', ' ', 'TypeError'],
            '\'sdsada\', 1, \'tag\' ' => ['sdsada', 1, '', []],
            '12345678, 1, \'tag\' ' => [12345678, 1, 'tag', []],
        ];
    }

    /**
     * @return array
     */
    public function xmlOkProvider()
    {
        return [
            '<root><child1><child1child1/></child1></root>, 1, \'tag\'' => ['<root><child1><child1child1/></child1></root>', 1, 'tag', true],
            '<root><child1><child1child1>ciaone</child1child1></child1></root>, 1, \'tag\'' => ['<root><child1><child1child1>ciaone</child1child1></child1></root>', 1, 'tag', true],
        ];
    }

    /**
     * @return array
     */
    public function array2xmlProvider()
    {
        $arrStr = trim("
                    array(1) {
                      'root' =>
                      array(1) {
                        'child1' =>
                        array(1) {
                          'child1child1' =>
                          string(6) \"ciaone\"
                        }
                      }
                    }
        ");
        $arr = [
                'root'
                => ['child1'
                    => ['child1&child1' => 'cia&o<ne']
                    ]
                ];
        return [
            'arr with ciaone and master tag, \'<master></master>\'' => [$arr, '<master></master>', '<?xml version="1.0"?>
<master><root><child1><child1&amp;child1>cia&amp;o&lt;ne</child1&amp;child1></child1></root></master>'],
            'arr with ciaone and no master tag, \'\'' => [$arr, '', '<?xml version="1.0"?>
<root><root><child1><child1&amp;child1>cia&amp;o&lt;ne</child1&amp;child1></child1></root></root>'],
        ];
    }
}
