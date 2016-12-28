--TEST--
list with by-reference assignment should fail
--FILE--
<?php

$a = [1, 5];
[&$foo] = $a;
$foo = 2;

var_dump($a);

?>
--EXPECTF--
array(2) {
  [0]=>
  &int(2)
  [1]=>
  int(5)
}
