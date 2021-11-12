# chrony-measurements
quick assessment of chrony's accuracy

https://kwynn.com/t/21/10/chm/ - live

My purpose is to add details to my accurate clock widget: 

https://kwynn.com/t/9/12/sync/
https://github.com/kwynncom/javascript-synchronized-clock

I have found that Chrony's own assessment of accuracy, which is all that is displayed now, is not useful in itself.  I plan to add details to that 
widget that give a far more complete assessment.

INSTALLATION / REQUIREMENTS

This needs my nanotime PHP extension: https://github.com/kwynncom/nano-php-extension
And parts of my nanoseond web server: https://github.com/kwynncom/web-timeserver-nanosecond-precision
    It's expecting chronyParsed.php from that project in a specific path relationship, in this project's main.php
Needs read permission for the files in /var/log/chrony
Needs sntp and wrap.php from https://github.com/kwynncom/sntp-client  in the PATH (see that project's README for installation notes)


IMPLEMENTATION NOTES

* /var/log/chrony does not necessarily have world-read and passthrough (directory) permission.  I changed that where needed.  I don't see any harm in it, although
perhaps I'm missing something.  I should say I don't see any harm given that I'm already publishing this information.

* Log rotation can cause /var/log/chrony/measurements.log and the other 2 to not exist for a time.  I tried to turn logration off for chrony.  I'll see how 
that works in about 23 hours.


HISTORY

I took the first files from: https://github.com/kwynncom/web-timeserver-nanosecond-precision
