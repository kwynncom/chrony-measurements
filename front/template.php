<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>chrony readings</title>
<link rel="stylesheet" href="chinfo.css">
</head>
<body>
<?php $dat = $KW_G_TIMEA; ?>
<div>
	<div><?php echo($dat['np']); ?> polls in use spanning <?php echo($dat['np_span_min']); ?> minutes; 
		last poll was <?php echo($dat['lpmin']); ?> minutes ago
	</div>
	<div>root dispersion:   <?php echo($dat['rdi']); ?> ; 
		root delay:   <?php echo($dat['rde']); ?> ; 
		residual frequency: <?php echo($dat['rfr']  ); ?>  ;
		estimated offset:   <?php echo($dat['estoff']); ?> ;
		last offset:		<?php echo($dat['laoff' ]); ?>
	</div>
	<div>max possible (but very unlikely) error: <?php echo($dat['maxe' ]); ?>
		
	</div>
	
	
	
</div>
<?php unset($dat, $KW_G_TIMEA); ?>
</body>
</html>

