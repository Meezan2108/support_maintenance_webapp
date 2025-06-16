<?php

namespace App\Helpers;

class EtlHelper
{
    public static function transformNull($value)
    {
        $value = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $value);
        return strtoupper($value) != "NULL" ? trim($value) : null;
    }

    public static function mimeToExtension($mime)
    {
        $mimeMap = array(
            'image/jpg' => '.jpg',
            'image/jpeg' => '.jpg',
            'image/png' => '.png',
            'image/gif' => '.gif',
            'application/pdf' => '.pdf',
            'application/msword' => '.docx',
            'application/x-msexcel' => '.xlsx'
        );

        if (isset($mimeMap[$mime])) {
            return $mimeMap[$mime];
        } else {
            return '';
        }
    }
}
