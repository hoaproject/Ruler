<?php

require "vendor/autoload.php";

//$str = '("\"a \" toto" babidou 1 AND "b" = true AND ("c" = false OR "c" = false)) AND "c" = false';

$str = '("a.toto" IS "toto" AND "b" = true AND "c" IS NOT NULL) OR "d" = "pouet"';

$result = \Rulez\Factory::decode($str);
print "<pre>";
var_dump($result);
print "</pre>";
$str    = \Rulez\Factory::encode($result);
print "<pre>";
var_dump($str);
print "</pre>";
exit('ici');
