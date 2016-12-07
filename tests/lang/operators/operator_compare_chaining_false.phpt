--TEST--
Test operator chaining
--FILE--
<?php

$a = 1;
$b = 2;
$c = true;

// False Tests
var_dump(1 < 1 < 3);
var_dump(1 < 2 < 1);
var_dump(1 <= 2 <= 1);
var_dump(3 > 4 > 1);
var_dump(3 >= 4 >= 1);

// Short Circuit Test
var_dump(1 < 1 < $a++);
var_dump($a);

// Equality Test
var_dump($a == 1 === $b);
var_dump($a == 1 === $c);
var_dump($a <= 1 === $c);
var_dump(1 < 2 == 3 == 4);

// Combined Test
var_dump(1 > 2 == 3 < 4);
?>
===DONE===
--EXPECT--
bool(false)
bool(false)
bool(false)
bool(false)
bool(false)
bool(false)
int(1)
bool(false)
bool(false)
bool(false)
bool(false)
bool(false)
===DONE===
