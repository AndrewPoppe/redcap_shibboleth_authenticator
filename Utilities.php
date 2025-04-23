<?php

namespace YaleREDCap\EntraIdAuthenticator;

class Utilities
{
    private $module;
    public function __construct(EntraIdAuthenticator $module)
    {
        $this->module = $module;
    }

    public static function getEdocFileContents($edocId)
    {
        if ( empty($edocId) ) {
            return;
        }
        $file     = \REDCap::getFile($edocId);
        $contents = $file[2];

        return 'data:' . $file[0] . ';base64,' . base64_encode($contents);
    }

    public static function curPageURL()
    {
        $pageURL = 'http';
        if ( isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on" ) {
            $pageURL .= "s";
        }
        $pageURL .= "://";
        if ( $_SERVER["SERVER_PORT"] != "80" ) {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        return filter_var($pageURL, FILTER_SANITIZE_URL);
    }

    public static function stripQueryParameter($url, $param)
    {
        $parsed  = parse_url($url);
        $baseUrl = strtok($url, '?');
        if ( isset($parsed['query']) ) {
            parse_str($parsed['query'], $params);
            unset($params[$param]);
            $parsed = empty($params) ? '' : http_build_query($params);
            return $baseUrl . (empty($parsed) ? '' : '?') . $parsed;
        } else {
            return $url;
        }
    }

    public static function addQueryParameter(string $url, string $param, string $value = '')
    {
        $parsed  = parse_url($url);
        $baseUrl = strtok($url, '?');
        if ( isset($parsed['query']) ) {
            parse_str($parsed['query'], $params);
            $params[$param] = $value;
            $parsed         = http_build_query($params);
        } else {
            $parsed = http_build_query([ $param => $value ]);
        }
        return $baseUrl . (empty($parsed) ? '' : '?') . $parsed;
    }

    public static function toLowerCase(string $string) : string {
        if (extension_loaded('mbstring')) {
            return mb_strtolower($string);
        }
        return strtolower($string);
    }
}