<?php

namespace corpsepk\yml\helpers;

class XmlWriterHelper
{
    public static function convertBoolToString($value)
    {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        } else {
            return $value;
        }
    }
}