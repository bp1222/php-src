--TEST--
Test operator chaining
--FILE--
<?php

$a = 1;
$b = 1;
$c = true;

var_dump(1 < 2 < 3);
var_dump(1 <= 2 <= 3);
var_dump(3 > 2 > 1);
var_dump(3 >= 2 >= 1);
var_dump(($a - 4) < 0 < 3);

// Short Circuit Test
var_dump(0 < 1 <= $a++);
var_dump($a);

// Equality Test
$a = 1;
var_dump($a == 1 == $b);
var_dump($a == 1 == $c);
var_dump($a <= 1 == $c);

// Combined Test
var_dump(1 < 2 == 3 < 4);
var_dump((1 < 2) == (3 < 4));
?>
===DONE===
--EXPECT--
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
int(2)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
===DONE===
