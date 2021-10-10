<?php

/**
 * 8  dP w      888            8          8            
 * 8wdP  w       8  8d8b. .d8b 8 8   8 .d88 .d88b 8d8b 
 * 88Yb  8 wwww  8  8P Y8 8    8 8b d8 8  8 8.dP' 8P   
 * 8  Yb 8      888 8   8 `Y8P 8 `Y8P8 `Y88 `Y88P 8      
 * 
 * @interface
 *      __ki_minify_content
 *      __ki__dump_content
 *      __ki_js_array_list_join
 *      __ki_read_keys
 * @required interface
 *      __ki__includer_core
 *      __ki__includer_scheme
 *      ___ki_include_snippet 
 *
 * @more
 *      KI-Includer script adapted for 5.4 php version
 *
 */

define('OPENING_TOKEN', '$$');
define('CLOSING_TOKEN', '$$');

define('MINIFY_SCRIPT', true);


require_once __DIR__ . '/FileStreamer.php';
require_once __DIR__ . '/ki-scheme.php';

class ki_includer
{
    public static $SchemeBaseDir = '';
    private static $ki_KeyMaps   = [];
    private static $schemes = [];

    public static function Prepare()
    {
        self::setKeyMap('MODULE_NAME', 'ki');
        self::setKeyMap('KI_BASEDIR_ASSETS', 'http://127.0.0.1/assets/ki-assets');
    }

    public static function jsArrayListJoin($arr)
    {
        $str = '';
        for ( $i = 0; $i < count($arr); ++$i )
            $str .= "'" . $arr[$i] . "', ";

        return '[' . substr($str, 0, strlen($str) - 2) . ']';
    }

    public static function minifyContent(&$content)
    {
        if ( !method_exists('JSMin', 'minify') )
            return $content;
            
        return JSMin::minify($content);
    }

    public static function setKeyMap($key, $value)
    {
        self::$ki_KeyMaps[$key] = $value;
    }

    public static function getKeyMap($key)
    {
        return self::$ki_KeyMaps[$key];
    }
    
    public static function load($scheme_name, ...$args)
    {
        $scheme = self::getScheme($scheme_name);
        if ( !$scheme )
            return;

        $scheme->Invoke(array_slice( func_get_args(), 1 ));
        
        self::DumpContent(self::path_join(self::$SchemeBaseDir, $scheme->getSchemeFileName()));
    }

    public static function getScheme($sch) 
    {
        for ( $i = 0; $i < count(self::$schemes); ++$i )
        {
            if ( self::$schemes[$i]->getSchemeName() === $sch )
                return self::$schemes[$i];
        }

        return NULL;
    }

    public static function addScheme($scheme_name, $scheme_filename, $callback = null)
    {
        array_push(self::$schemes, new ki_scheme($scheme_name, $scheme_filename, $callback));
    }

    public static function DumpContent($scheme_src)
    {
        $keys = self::readKeys($scheme_src);
        $content = @file_get_contents($scheme_src);

        for ( $i = 0; $i < count($keys); ++$i )
            $content = str_replace(OPENING_TOKEN . $keys[$i] . CLOSING_TOKEN, self::$ki_KeyMaps[$keys[$i]], $content);

        ob_start();
        echo defined('MINIFY_SCRIPT') && MINIFY_SCRIPT ? self::minifyContent($content) : $content;
        ob_end_flush(); 
    }
 
    private static function readKeys($scheme_src)
    {
        $keys = [];
        $fs = new FileStreamer($scheme_src);

        while ( !$fs->eof() )
        {

            $fs->skip_until(OPENING_TOKEN);
            $key = $fs->read_until(CLOSING_TOKEN);
            if ( !$key )
                continue;

           array_push($keys, $key);    
        }

        return $keys;
    }

    private static function path_join($p1, $p2)
    {
        if ( empty($p1) )
            return $p2;

        if ( empty($p2) )
            return $p1;

        if ( $p1[strlen($p1) - 1] !== '/' )
            $p1 .= '/';
        
        return $p1 . $p2;
    }
    
} // class ki_includer


require_once __DIR__ . '/ki-helpers.php';


?>