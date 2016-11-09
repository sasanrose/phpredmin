#!/bin/bash

# Run gearman job server
gearmand -d
# Run cron daemon
cron
# Run gearman work
php index.php gearman/index &
# Start web server
apache2-foreground &

trap "pkill gearmand && pkill -WINCH apache2" TERM

wait
