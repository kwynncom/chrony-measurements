<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>chrony readings</title>
<link rel="stylesheet" href="front/chinfo.css?v240310=1">
<script src='/opt/kwynn/js/utils.js'></script>
<script src='front/js/load.js?v240310=1'></script>
</head>
<body>
<?php  require_once('histTable.php');
		$KW_G_TDS_ORDER = ['np', 'np_span_min', 'lpmin', 'lpoll', 'laoffnist', 'estoff', 'rdi', 'rde', 'maxe', 'rfr']; ?>
<script>
<?php	echo("\t" . 'var KW_G_CHM_INIT  = ' . json_encode($KW_G_TIMEA    ) . ';' . "\n" ); // still need GV for logs way below
		echo("\t" . 'var KW_G_CHM_ORDER = ' . json_encode($KW_G_TDS_ORDER) . ';' . "\n" ); unset($KW_G_TDS_ORDER); ?>
</script>

<div> <!-- dat - tables and such -->
	<div id='worstP'>
		
	</div>
	<div id='ordp'> <!-- ordered tds -->
<table>
	<tr><td class='n30'></td><td></td><td>active polls</td></tr>
	<tr><td class='n30'></td><td>minutes</td><td>poll span</td></tr>
	<tr><td class='n30'></td><td>minutes</td><td>since last ref time</td></tr>
</table>
<table>
	<tr><td class='n30'></td><td>ms</td><td>offset - last poll</td></tr>
	<tr><td class='n30'></td><td>ms</td><td>offset - ext</td></tr>
	<tr><td class='n30'></td><td>ms</td><td>offset - current est</td></tr>
</table>

	<div class='btngp20'>
	<div class='b20'>
<table class='b20'>
	<tr><td class='n30 b10'></td><td>ms</td><td>root disp</td></tr>
	<tr><td class='n30'></td><td>ms</td><td>root delay</td></tr>
	<tr><td class='n30'></td><td>ms</td><td>max error</td></tr>
</table>
	</div>
	<div class='b20 btnp20'>
		<button class='btn10' onclick='reload_btn_onclick();'>&#8635;</button>
	</div>
	</div>
		
<table>
	<tr><td class='n30'><td>ppm</td><td>residual frequency</td></tr>
</table>
	
	<div class='asofP'>at <span id='asof' ></span></div>
	</div> <!-- ordered tds -->
	
<div class='histP'> <!-- hist tables -->
<table class='mono htab10 hist'>
	<caption>NTP server</caption>
	<thead>
		<tr><th>min</th><th class='poll'>off</th><th class='frcor'>frcor</th></tr>
	</thead>
	<tbody id='histb10'><?php echo(kwChmHistRows($KW_G_TIMEA['logs']['logs'])); ; ?></tbody>
</table>

<table class='mono htab10 hist' id='nisthist'>
	<caption>ext</caption>
	<thead>
		<tr><th>min</th><th class='poll'>off</th><th>s</th></tr>
	</thead>
	<tbody id='histNIST'><?php echo(kwChmNISTRows($KW_G_TIMEA['nistall']));?></tbody>
</table>
	
</div> <!-- hist tables -->
</div>  <!-- dat - tables and such -->

<div class='foot10'>
	<div><a href='https://kwynn.com/t/9/12/sync/'>clock</a>
		<span id='sourcerefD'><a href='/t/9/12/sync/more.html'>more info</a></span></div>
</div> <!-- foot -->

</body>
</html>
