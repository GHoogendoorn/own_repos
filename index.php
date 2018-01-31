<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title>OO Toets Cesuur bepalen</title>
        <link rel="stylesheet" type="text/css" href="style.css"/>

    </head>
<?php
        include_once 'class/resultaat.php';
        include_once 'class/cesuurtabel.php';
        include_once 'class/cesuur.php';
?>
<body>
<h1>Cesuur bepaling toets</h1>
<?php
echo "<hr /> Test Resultaat";
$test = new TestResultaat();

echo "<hr /> Test CesuurTabel<br />";
$cesuur_tbl = new TestCesuurTabel();

echo "<hr /> Test Cesuur<hr /><br />";
$cesuur = new TestCesuur();

var_dump( $cesuur );
?>
</body>
</html>