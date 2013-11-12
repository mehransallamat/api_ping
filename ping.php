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

$data = $_GET['domains'];

$returnArray = array();

foreach ($data as $key => $domain) {
    $returnArray[$domain]['latency'] = pingDomain($domain);
}


echo json_encode($returnArray);