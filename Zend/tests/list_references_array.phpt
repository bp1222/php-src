--TEST--
list() with assigning by reference - array
--FILE--
<?php
$a = [1, 2];
list(&$one, $two) = $a;
$one++;
var_dump($a);
unset($one, $two);

list($one, $two, &$three) = $a;
var_dump($a);
$three = 3;
var_dump($a);
unset($one, $two, $three);

$a = ['two' => 2, 'one' => 1];
list('one' => &$one, 'two' => $two) = $a;
$one++;
var_dump($a);
unset($one, $two);

list('one' => $one, 'two' => $two, 'three' => &$three) = $a;
var_dump($a);
$three = 3;
var_dump($a);
unset($one, $two, $three);

$a = [[1, 2]];
list(list(&$one, $two)) = $a;
$one++;
var_dump($a);
unset($one, $two);

$a = [[1, 2]];
[[$one, &$two]] = $a;
$one++;
var_dump($a);
unset($one, $two);

$a = [1, 2];
list(,, list($var)) = $a;
var_dump($a);
var_dump($var);
unset($var);
list(,, list(&$var)) = $a;
var_dump($a);
var_dump($var);
unset($var, $a);

$a = [1, 2, [3]];
list(,, list(, $var)) = $a;
var_dump($a);
var_dump($var);
unset($var);
list(,, list(, &$var)) = $a;
var_dump($a);
var_dump($var);
unset($var, $a);
?>
--EXPECTF--
array(2) {
  [0]=>
  &int(2)
  [1]=>
  int(2)
}
array(3) {
  [0]=>
  int(2)
  [1]=>
  int(2)
  [2]=>
  &NULL
}
array(3) {
  [0]=>
  int(2)
  [1]=>
  int(2)
  [2]=>
  &int(3)
}
array(2) {
  ["two"]=>
  int(2)
  ["one"]=>
  &int(2)
}
array(3) {
  ["two"]=>
  int(2)
  ["one"]=>
  int(2)
  ["three"]=>
  &NULL
}
array(3) {
  ["two"]=>
  int(2)
  ["one"]=>
  int(2)
  ["three"]=>
  &int(3)
}
array(1) {
  [0]=>
  array(2) {
    [0]=>
    &int(2)
    [1]=>
    int(2)
  }
}
array(1) {
  [0]=>
  array(2) {
    [0]=>
    int(1)
    [1]=>
    &int(2)
  }
}

Notice: Undefined offset: 2 in %s on line %d
array(2) {
  [0]=>
  int(1)
  [1]=>
  int(2)
}
NULL
array(3) {
  [0]=>
  int(1)
  [1]=>
  int(2)
  [2]=>
  array(1) {
    [0]=>
    &NULL
  }
}
NULL

Notice: Undefined offset: 1 in %s on line %d
array(3) {
  [0]=>
  int(1)
  [1]=>
  int(2)
  [2]=>
  array(1) {
    [0]=>
    int(3)
  }
}
NULL
array(3) {
  [0]=>
  int(1)
  [1]=>
  int(2)
  [2]=>
  array(2) {
    [0]=>
    int(3)
    [1]=>
    &NULL
  }
}
NULL
