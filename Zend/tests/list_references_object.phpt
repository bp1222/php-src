--TEST--
list() with assigning by reference - object
--FILE--
<?php
class StorageNoRef implements ArrayAccess {
    private $s = [];
    function __construct(array $a) { $this->s = $a; }
    function offsetSet ($k, $v) { $this->s[$k] = $v; }
    function offsetGet ($k) { return $this->s[$k]; }
    function offsetExists ($k) { return isset($this->s[$k]); }
    function offsetUnset ($k) { unset($this->s[$k]); }
}

class StorageRef implements ArrayAccess {
    private $s = [];
    function __construct(array $a) { $this->s = $a; }
    function offsetSet ($k, $v) { $this->s[$k] = $v; }
    function &offsetGet ($k) { return $this->s[$k]; }
    function offsetExists ($k) { return isset($this->s[$k]); }
    function offsetUnset ($k) { unset($this->s[$k]); }
}

$a = new StorageNoRef([1, 2]);
list($one, $two) = $a;
$one++;
var_dump($a);
unset($one, $two);

list(&$one, $two) = $a;
$one++;
var_dump($a);
unset($one, $two);

$a = new StorageRef([1, 2]);
list($one, $two) = $a;
$one++;
var_dump($a);
unset($one, $two);

list(&$one, $two) = $a;
$one++;
var_dump($a);
unset($one, $two);

$a = new StorageRef(['two' => 2, 'one' => 1]);
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
--EXPECTF--
object(StorageNoRef)#%d (%d) {
  [%s]=>
  array(2) {
    [0]=>
    int(1)
    [1]=>
    int(2)
  }
}

Notice: Indirect modification of overloaded element of StorageNoRef has no effect in %s on line %d
object(StorageNoRef)#%d (%d) {
  [%s]=>
  array(2) {
    [0]=>
    int(1)
    [1]=>
    int(2)
  }
}
object(StorageRef)#%d (%d) {
  [%s]=>
  array(2) {
    [0]=>
    int(1)
    [1]=>
    int(2)
  }
}
object(StorageRef)#%d (%d) {
  [%s]=>
  array(2) {
    [0]=>
    &int(2)
    [1]=>
    int(2)
  }
}
object(StorageRef)#%d (%d) {
  [%s]=>
  array(2) {
    ["two"]=>
    int(2)
    ["one"]=>
    int(1)
  }
}
object(StorageRef)#%d (%d) {
  [%s]=>
  array(2) {
    ["two"]=>
    int(2)
    ["one"]=>
    &int(2)
  }
}
object(StorageRef)#%d (%d) {
  [%s]=>
  array(3) {
    ["two"]=>
    int(2)
    ["one"]=>
    int(2)
    ["three"]=>
    &NULL
  }
}
object(StorageRef)#%d (%d) {
  [%s]=>
  array(3) {
    ["two"]=>
    int(2)
    ["one"]=>
    int(2)
    ["three"]=>
    &int(3)
  }
}
