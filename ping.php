<?php
/**
 * Created by PhpStorm.
 * User: stuarteske
 * Date: 10/11/2013
 * Time: 18:33
 *
 * @copyright    Copyright (C) 2013 CFA Group. All rights reserved.
 * @license      GNU General Public License Version 2 or later.
 * @author       Stuart Eske, <stuart.eske@cfa-group.com>
 */

header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json; charset=utf-8');

if( !function_exists('apache_request_headers') ) {
    function apache_request_headers() {
       $arh = array();
       $rx_http = '/\AHTTP_/';

       foreach($_SERVER as $key => $val) {
           if( preg_match($rx_http, $key) ) {
               $arh_key = preg_replace($rx_http, '', $key);
               $rx_matches = array();
               // do some nasty string manipulations to restore the original letter case
               // this should work in most cases
               $rx_matches = explode('_', $arh_key);

               if( count($rx_matches) > 0 and strlen($arh_key) > 2 ) {
                    foreach($rx_matches as $ak_key => $ak_val) $rx_matches[$ak_key] = ucfirst($ak_val);
                    $arh_key = implode('-', $rx_matches);
               }
               $arh[$arh_key] = $val;
            }
        }

        return( $arh );
    }
}

$headersArray = apache_request_headers();

if (!isset($headersArray['AUTHKEY']) || $headersArray['AUTHKEY'] != 'uhfiuh83988dtdijdwhf46gfomv298')
    die();

function pingDomain($domain){
    $starttime = microtime(true);
    // supress error messages with @
    $file      = @fsockopen($domain, 80, $errno, $errstr, 10);
    $stoptime  = microtime(true);
    $status    = 0;

    if (!$file){
        $status = -1;  // Site is down
    }
    else{
        fclose($file);
        $status = ($stoptime - $starttime) * 1000;
        $status = floor($status);
    }
    return $status;
}

function stripText($text = "") {
    return str_replace(array("http://","https://"), "", $text);
}

$data = $_GET['domains'];

$returnArray = array();

foreach ($data as $key => $domain) {
    $domain = stripText($domain);
    $returnArray[$domain]['latency'] = pingDomain($domain);
}


echo json_encode($returnArray);
