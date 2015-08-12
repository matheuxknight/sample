<?php

//$t1 = microtime(true);
require_once('sansspace/ui/app.php');

if(SANSSPACE_DEBUG && !strstr($_SERVER['REQUEST_URI'], 'ping'))
	debuglog("{$_SERVER['REMOTE_ADDR']}: {$_SERVER['REQUEST_URI']} - {$_SERVER['HTTP_REFERER']}");

try { $app->run(); }
catch(CException $e)
{
	debuglog($e, 3);
	mydump($e, 3);
}

// $t2 = microtime(true);
// $d1 = precision($t2 - $t1, 2);

// if($d1 > 1)
// {
// 	$message = username()." - {$_SERVER['REQUEST_URI']}";
// 	debuglog("$d1 - $message");
// }




