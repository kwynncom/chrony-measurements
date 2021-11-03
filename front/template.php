<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>chrony readings</title>
<link rel="stylesheet" href="front/chinfo.css">
</head>
<body>
<?php 
	require_once('histTable.php');
	$d = $KW_G_TIMEA; unset($KW_G_TIMEA); 
?>

<div> <!-- dat - tables and such -->
<table>
	<tr><td class='n30'><?php echo($d['np']			); ?></td><td>(count)</td><td>active polls</td></tr>
	<tr><td class='n30'><?php echo($d['np_span_min']); ?></td><td>minutes</td><td>poll span</td></tr>
	<tr><td class='n30'><?php echo($d['lpmin']); ?>		 </td><td>minutes</td><td>since last poll</td></tr>
</table>

<table>
	<tr><td class='n30'><?php echo($d['lpoll']);  ?></td><td>ms</td><td>offset - last poll</td></tr>
	<tr><td class='n30'><?php echo($d['laoffnist']);  ?></td><td>ms</td><td>offset - NIST</td></tr>
	<tr><td class='n30'><?php echo($d['estoff']); ?></td><td>ms</td><td>offset - current est, running <?php echo($d['estoffa']['direction']); ?></td></tr>
</table>
	
<table>
	<tr><td class='n30 b10'><?php echo($d['rdi' ]); ?></td><td>ms</td><td>root dispersion</td></tr>
	<tr><td class='n30'><?php echo($d['rde' ]); ?></td><td>ms</td><td>root delay</td></tr>
	<tr><td class='n30'><?php echo($d['maxe']); ?></td><td>ms</td><td>max error</td></tr>
</table>

<table>
	<tr><td class='n30'><?php echo($d['rfr']); ?></td><td>ppm</td><td>residual frequency</td></tr>
</table>
	
<div>at <?php echo($d['asof']); ?></div>

<table class='mono htab10'>
	<thead>
		<tr><th>min<br/>ago</th><th class='poll'>poll<br/>off</th><th class='frcor'>frcor</th></tr>
	</thead
	<tbody id='histb10'>
		<?php echo(kwChmHistRows($d['logs']['logs'])); ?>
	</tbody>
</table>
	
	
</div>  <!-- dat - tables and such -->
<div class='foot10'>
<div><a href='https://kwynn.com/t/9/12/sync/'>clock</a></div>
	
<div id='sourcerefD'><a href='https://github.com/kwynncom/chrony-measurements'>source code</a></div>
	</div>
<?php unset($d); ?>
</body>
</html>

