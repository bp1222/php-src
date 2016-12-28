--TEST--
list() with assigning by reference - array
--FILE--
<?php
$a = [1, 2];
list($one, $two) = $a;
$one++;
var_dump($a);
unset($one, $two);

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
list('one' => $one, 'two' => $two) = $a;
$one++;
var_dump($a);
unset($one, $two);

list('one' => &$one, 'two' => $two) = $a;
$one++;
var_dump($a);
unset($one, $two);

list('one' => $one, 'two' => $two, 'three' => &$three) = $a;
var_dump($a);
$three = 3;
var_dump($a);
unset($one, $two, $three);

?>
--EXPECT--
array(2) {
  [0]=>
  int(1)
  [1]=>
  int(2)
}
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
  int(1)
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
