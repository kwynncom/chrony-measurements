<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>chrony readings</title>
<link rel="stylesheet" href="front/chinfo.css">
</head>
<body>
<?php $d = $KW_G_TIMEA; ?>

<div>
<table>
	<tr><td class='n30'><?php echo($d['np']			); ?></td><td>(count)</td><td>active polls</td></tr>
	<tr><td class='n30'><?php echo($d['np_span_min']); ?></td><td>minutes</td><td>poll span</td></tr>
	<tr><td class='n30'><?php echo($d['lpmin']); ?>		 </td><td>minutes</td><td>last poll</td></tr>
</table>

<table>
	<tr><td class='n30'><?php echo($d['laoff']);  ?></td><td>ms</td><td>offset - last poll</td></tr>
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
	
</div>

<div><a href='https://kwynn.com/t/9/12/sync/'>clock</a></div>
	
<div id='sourcerefD'><a href='https://github.com/kwynncom/chrony-measurements'>source code</a></div>
	
<?php unset($d, $KW_G_TIMEA); ?>
</body>
</html>

