<?php

$ppid = posix_getppid();
file_put_contents('/tmp/ppid', $ppid . "\n");
echo($ppid . "\n");
sleep(120);

// $ pstree -s ppid
