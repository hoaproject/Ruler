<?php

require "vendor/autoload.php";

//$str = '("\"a \" toto" babidou 1 AND "b" = true AND ("c" = false OR "c" = false)) AND "c" = false';

//$str = '("a.toto" = "toto" AND "b" = true AND "c" != NULL) OR "d" = "pouet"';
$str = '("a.toto" IS "toto" AND "b" = true AND "c" IS NOT NULL) OR "d" = "pouet"';


//$str = 'key = key AND pouet = "1"';
$str = 'key = "key" XOR pouet = "4" XOR key = key';

$str = 'DATE("Y-m-d", now) > "2013-02-25"';

//$str = 'key = key';

$ruler = new \Rulez\Ruler();
$ruler->addFunction('DATE', function(array $arguments) {
    if (count($arguments) > 2) {
        throw new \LogicException('Date function accepts 2 arguments');
    }

    return date($arguments[0], $arguments[1]);
});
$result = $ruler->decode($str);

$str    = $ruler->encode($result);

$context = new \Rulez\Asserter\Context();
$context['key'] = 'douda';
$context['pouet'] = '4';
$context['now'] = time();

print "<pre>";
var_dump($ruler->assert($str, $context));
print "</pre>";
exit('ici');
