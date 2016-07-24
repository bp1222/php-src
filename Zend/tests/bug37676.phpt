--TEST--
Bug #67169: E_NOTICE for invalid container
--FILE--
<?php
declare (ticks=1);
$fn = 'var_dump';

const FOO = null;
const BAR = FOO[0][1][2];

$a = null;
$a[0];
$a[0][1];
@$a[0][1];
var_dump($a[0][0]);
$fn($a[0][0]);

$a = [null];
$a[0];
$a[0][1];
@$a[0][1];

$a = 123;
$a[0];
$a[0][1];
@$a[0][1];
var_dump($a[0][0]);
$fn($a[0][0]);

$a = array(123);
$a[0];
$a[0][1];
@$a[0][1];

$a = false;
$a[0];
$a[0][1];
@$a[0][1];
var_dump($a[0][0]);
$fn($a[0][0]);

$a = [false];
$a[0];
$a[0][1];
@$a[0][1];

$a = array('foo', 'bar');
while (list($key, $val) = each($a)) {
    echo "$key\n";
    echo "$val\n";
}

$a = null;
list($key, $val) = $a;

$a = null;
list('key' => $val) = $a;

function foo() {
    return null;
}
foo()[0];

function fooa() {
    return [null];
}
fooa()[0];
fooa()[0][1];

$a = [null];
$b = null;
var_dump($a[0][0] + $b[0]);

$a = [0 => null];
$b = [1 => 0];
$c = [2 => 1];
$d = [3 => $b];

$a[$b[$c[2]]][0];
$a[$d[3][1]][$b[1]];
echo "Done";
?>
--EXPECTF--
Warning: Variable of type null does not accept array offsets in %sbug37676.php on line %d

Warning: Variable of type null does not accept array offsets in %sbug37676.php on line %d

Warning: Variable of type null does not accept array offsets in %sbug37676.php on line %d

Warning: Variable of type null does not accept array offsets in %sbug37676.php on line %d
NULL

Warning: Variable of type null does not accept array offsets in %sbug37676.php on line %d
NULL

Warning: Variable of type null does not accept array offsets in %sbug37676.php on line %d

Warning: Variable of type integer does not accept array offsets in %sbug37676.php on line %d

Warning: Variable of type integer does not accept array offsets in %sbug37676.php on line %d

Warning: Variable of type integer does not accept array offsets in %sbug37676.php on line %d
NULL

Warning: Variable of type integer does not accept array offsets in %sbug37676.php on line %d
NULL

Warning: Variable of type integer does not accept array offsets in %sbug37676.php on line %d

Warning: Variable of type boolean does not accept array offsets in %sbug37676.php on line %d

Warning: Variable of type boolean does not accept array offsets in %sbug37676.php on line %d

Warning: Variable of type boolean does not accept array offsets in %sbug37676.php on line %d
NULL

Warning: Variable of type boolean does not accept array offsets in %sbug37676.php on line %d
NULL

Warning: Variable of type boolean does not accept array offsets in %sbug37676.php on line %d
0
foo
1
bar

Warning: Variable of type null does not accept array offsets in %sbug37676.php on line %d

Warning: Variable of type null does not accept array offsets in %sbug37676.php on line %d

Warning: Variable of type null does not accept array offsets in %sbug37676.php on line %d

Warning: Variable of type null does not accept array offsets in %sbug37676.php on line %d
int(0)

Warning: Variable of type null does not accept array offsets in %sbug37676.php on line %d

Warning: Variable of type null does not accept array offsets in %sbug37676.php on line %d
Done
