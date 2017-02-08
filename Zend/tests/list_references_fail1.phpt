--TEST--
list() with assigning by reference failure - string assignment
--FILE--
<?php
$s = "abc";
[$a, $b, &$c] = $s;
?>
--EXPECTF--
Fatal error: Uncaught Error: Cannot create references to/from string offsets in %s:%d
Stack trace:
#0 {main}
  thrown in %s on line %d
