<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>chrony readings</title>
</head>
<body>
	<?php $dat = $KW_G_TIMEA; ?>
	<div>poll span (minutes): <span><?php echo($dat['np_span_min']); ?></span></div>
	
	<?php unset($dat, $KW_G_TIMEA); ?>
</body>
</html>

