<?php

defined('JSON_ERROR_NONE') || define('JSON_ERROR_NONE', 0);
defined('JSON_ERROR_DEPTH') || define('JSON_ERROR_DEPTH', 1);
defined('JSON_ERROR_STATE_MISMATCH') || define('JSON_ERROR_STATE_MISMATCH', 2);
defined('JSON_ERROR_CTRL_CHAR') || define('JSON_ERROR_CTRL_CHAR', 3);
defined('JSON_ERROR_SYNTAX') || define('JSON_ERROR_SYNTAX', 4);
defined('JSON_ERROR_UTF8') || define('JSON_ERROR_UTF8', 5);
defined('JSON_ERROR_RECURSION') || define('JSON_ERROR_RECURSION', 6);
defined('JSON_ERROR_INF_OR_NAN') || define('JSON_ERROR_INF_OR_NAN', 7);
defined('JSON_ERROR_UNSUPPORTED_TYPE') || define('JSON_ERROR_UNSUPPORTED_TYPE', 8);

defined('JSON_HEX_TAG') || define('JSON_HEX_TAG', 1);
defined('JSON_HEX_AMP') || define('JSON_HEX_AMP', 2);
defined('JSON_HEX_APOS') || define('JSON_HEX_APOS', 4);
defined('JSON_HEX_QUOT') || define('JSON_HEX_QUOT', 8);
defined('JSON_FORCE_OBJECT') || define('JSON_FORDE_OBJECT', 16);
defined('JSON_NUMERIC_CHECK') || define('JSON_NUMERIC_CHECK', 32);
defined('JSON_BIGINT_AS_STRING') || define('JSON_BIGINT_AS_STRING', 2);
defined('JSON_PRETTY_PRINT') || define('JSON_PRETTY_PRINT', 128);
defined('JSON_UNESCAPED_SLASHES') || define('JSON_UNESCAPED_SLASHES', 64);
defined('JSON_UNESCAPED_UNICODE') || define('JSON_UNESCAPED_UNICODE', 256);


$_lastJsonError_ = JSON_ERROR_NONE;

if (!function_exists('json_encode')) {
    function json_encode($value, $options = 0)
    {
        global $_lastJsonError_;
        $_lastJsonError_ = JSON_ERROR_NONE;
        $usePhpJsonFunctions = CJSON::$usePhpJsonFunctions;

        CJSON::$usePhpJsonFunctions = false;
        $result = CJSON::encode($value);
        if ($result === false) {
            $_lastJsonError_ = JSON_ERROR_UTF8;
        }

        CJSON::$usePhpJsonFunctions = $usePhpJsonFunctions;
        return $result;
    }
}

if (!function_exists('json_decode')) {
    function json_decode($json, $assoc = false, $depth = 512, $options = 0)
    {
        global $_lastJsonError_;
        $_lastJsonError_ = JSON_ERROR_NONE;
        $usePhpJsonFunctions = CJSON::$usePhpJsonFunctions;

        CJSON::$usePhpJsonFunctions = false;
        $result = CJSON::decode($json, $assoc);

        CJSON::$usePhpJsonFunctions = $usePhpJsonFunctions;
        if ($result === null && strcasecmp($json, 'null') !== 0) {
            $_lastJsonError_ = JSON_ERROR_SYNTAX;
        }
        return $result;
    }
}

if (!function_exists('json_last_error')) {
    function json_last_error()
    {
        global $_lastJsonError_;
        return $_lastJsonError_;
    }
}
