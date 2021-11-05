<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>chrony readings</title>
<link rel="stylesheet" href="front/chinfo.css">
<script src='front/js/utils.js'></script>
<script src='front/js/load.js'></script>
<script src='front/js/win_onload.js'></script>
</head>
<body> <!-- for future reference: refresh, reload &#128472; -->
<?php  require_once('histTable.php');
		$KW_G_TDS_ORDER = ['np', 'np_span_min', 'lpmin', 'lpoll', 'laoffnist', 'estoff', 'rdi', 'rde', 'maxe', 'rfr']; ?>
<script>
<?php	echo("\t" . 'var KW_G_CHM_INIT  = ' . json_encode($KW_G_TIMEA    ) . ';' . "\n" ); // still need GV for logs way below
		echo("\t" . 'var KW_G_CHM_ORDER = ' . json_encode($KW_G_TDS_ORDER) . ';' . "\n" ); unset($KW_G_TDS_ORDER); ?>
</script>



<div> <!-- dat - tables and such -->
<table>
	<tr><td class='n30'></td><td></td><td>active polls</td></tr>
	<tr><td class='n30'></td><td>minutes</td><td>poll span</td></tr>
	<tr><td class='n30'></td><td>minutes</td><td>since last poll</td></tr>
</table>
<table>
	<tr><td class='n30'></td><td>ms</td><td>offset - last poll</td></tr>
	<tr><td class='n30'></td><td>ms</td><td>offset - NIST</td></tr>
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
		<button class='btn10' onclick='reload_btn_onclick();'>&#128472;</button>
	</div>
	</div>
		
<table>
	<tr><td class='n30'><td>ppm</td><td>residual frequency</td></tr>
</table>
	
	<div>at <span id='asof' /></div>

<table class='mono htab10'>
	<thead>
		<tr><th>min<br/>ago</th><th class='poll'>poll<br/>off</th><th class='frcor'>frcor</th></tr>
	</thead>
	<tbody id='histb10'><?php echo(kwChmHistRows($KW_G_TIMEA['logs']['logs'])); unset($KW_G_TIMEA); ?></tbody>
</table>
</div>  <!-- dat - tables and such -->

<div class='foot10'>
	<div><a href='https://kwynn.com/t/9/12/sync/'>clock</a></div>
	<div id='sourcerefD'><a href='https://github.com/kwynncom/chrony-measurements'>source code</a></div>
</div> <!-- foot -->

</body>
</html>

