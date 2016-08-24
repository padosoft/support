<?php

/**
 * Fetch URL with fopen() && returns a well formed array like the structure of the xml-document.
 * If somethins goes wrong return an empty array.
 * @param string $url
 * @param int $get_attributes
 * @param string $priority
 * @return array
 */
function xmlUrl2array(string $url, int $get_attributes = 1, string $priority = 'tag') : array
{
    if (!function_exists('xml_parser_create')) {
        return array();
    }

    $contents = file_get_contents($url);

    return xml2array($contents, $get_attributes, $priority);
}

/**
 * Returns a well formed array like the structure of the xml-document
 * If somethins goes wrong return an empty array.
 * @param string $xml
 * @param int $get_attributes
 * @param string $priority
 * @return array
 */
function xml2array(string $xml, int $get_attributes = 1, string $priority = 'tag') : array
{
    if (!function_exists('xml_parser_create')) {
        return array();
    }
    if (!$xml) {
        return array();
    }
    $contents = $xml;
    $parser = xml_parser_create('');
    xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8");
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
    xml_parse_into_struct($parser, trim($contents), $xml_values);
    xml_parser_free($parser);
    if (empty($xml_values)) {
        return array();
    }
    $xml_array = array();
    $current = &$xml_array;
    $repeated_tag_index = array();
    foreach ($xml_values as $data) {
        unset ($attributes, $value);
        extract($data);
        $result = array();
        $attributes_data = array();
        if (isset ($value)) {
            if ($priority == 'tag') {
                $result = $value;
            } else {
                $result['value'] = $value;
            }
        }
        if (isset ($attributes) && $get_attributes) {
            foreach ($attributes as $attr => $val) {
                if ($priority == 'tag') {
                    $attributes_data[$attr] = $val;
                } else {
                    $result['attr'][$attr] = $val;
                } //Set all the attributes in a array called 'attr'
            }
        }
        if ($type == "open") {
            $parent[$level - 1] = &$current;
            if (!is_array($current) || (!in_array($tag, array_keys($current)))) {
                $current[$tag] = $result;
                if (!empty($attributes_data)) {
                    $current[$tag . '_attr'] = $attributes_data;
                }
                $repeated_tag_index[$tag . '_' . $level] = 1;
                $current = &$current[$tag];
            } else {
                if (isset ($current[$tag][0])) {
                    $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
                    $repeated_tag_index[$tag . '_' . $level]++;
                } else {
                    $current[$tag] = array(
                        $current[$tag],
                        $result
                    );
                    $repeated_tag_index[$tag . '_' . $level] = 2;
                    if (isset ($current[$tag . '_attr'])) {
                        $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                        unset ($current[$tag . '_attr']);
                    }
                }
                $last_item_index = $repeated_tag_index[$tag . '_' . $level] - 1;
                $current = &$current[$tag][$last_item_index];
            }
        } elseif ($type == "complete") {
            if (!isset ($current[$tag])) {
                $current[$tag] = $result;
                $repeated_tag_index[$tag . '_' . $level] = 1;
                if ($priority == 'tag' && !empty($attributes_data)) {
                    $current[$tag . '_attr'] = $attributes_data;
                }
            } else {
                if (is_array($current[$tag]) && isset ($current[$tag][0])) {
                    $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
                    if ($priority == 'tag' && $get_attributes && !empty($attributes_data)) {
                        $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                    }
                    $repeated_tag_index[$tag . '_' . $level]++;
                } else {
                    $current[$tag] = array(
                        $current[$tag],
                        $result
                    );
                    $repeated_tag_index[$tag . '_' . $level] = 1;
                    if ($priority == 'tag' && $get_attributes) {
                        if (isset ($current[$tag . '_attr'])) {
                            $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                            unset ($current[$tag . '_attr']);
                        }
                        if (!empty($attributes_data)) {
                            $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                        }
                    }
                    $repeated_tag_index[$tag . '_' . $level]++; //0 && 1 index is already taken
                }
            }
        } elseif ($type == 'close') {
            $current = &$parent[$level - 1];
        }
    }
    return $xml_array;
}

/**
 * @param array $arrData
 * @param string $rootXml
 * @return string
 */
function array2xml(array $arrData, string $rootXml = '<root></root>') : string
{
    // creating object of SimpleXMLElement
    $objXml = new SimpleXMLElement('<?xml version="1.0"?>' . ($rootXml ? $rootXml : '<root></root>'));
    array2SimpleXMLElement($arrData, $objXml);
    return trim($objXml->asXML());
}

/**
 * @param array $arrData
 * @param SimpleXMLElement $objXml
 */
function array2SimpleXMLElement(array $arrData, SimpleXMLElement $objXml)
{
    foreach ($arrData as $key => $value) {
        if (is_array($value)) {
            if (!is_numeric($key)) {
                $subnode = $objXml->addChild("$key");
                array2SimpleXMLElement($value, $subnode);
            } else {
                array2SimpleXMLElement($value, $objXml);
            }
        } else {
            $objXml->addChild("$key", "$value");
        }
    }
}
