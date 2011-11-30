<?php
/**
 * Draw weather-plot
 * Call:   include 'Plot.Average.php'
 */

$Months       = array();
$Temperatures = array();

for ($m = 1; $m <= 12; $m++) {
	$Months[] = array($m-1, Helper::Month($m, true));

	for ($y = START_YEAR, $n = date('Y'); $y <= $n; $y++)
		$Temperatures[$y] = array(null,null,null,null,null,null,null,null,null,null,null,null);
}

$Data = Mysql::getInstance()->fetchAsArray('SELECT YEAR(FROM_UNIXTIME(`time`)) as `y`, MONTH(FROM_UNIXTIME(`time`)) as `m`, AVG(`temperature`) as `temp` FROM `'.PREFIX.'training` WHERE !ISNULL(`temperature`) GROUP BY `y`, `m` ORDER BY `y` ASC, `m` ASC');
foreach ($Data as $dat)
	$Temperatures[$dat['y']][$dat['m'] - 1] = $dat['temp'];

$Plot = new Plot("average", 780, 240);

for ($y = START_YEAR, $n = date('Y'); $y <= $n; $y++) {
	if (min($Temperatures[$y]) != null || max($Temperatures[$y]) != null)
		$Plot->Data[] = array('label' => $y, 'data' => $Temperatures[$y]);
}

$Plot->setMarginForGrid(5);
$Plot->setXLabels($Months);
$Plot->addYAxis(1, 'left');
$Plot->addYUnit(1, '�C');
$Plot->setYTicks(1, 5, 0);

$Plot->addThreshold('y', 0);
$Plot->addMarkingArea('y', -99, 0);
$Plot->lineWithPoints();


$Plot->outputJavaScript();
?>