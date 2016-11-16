#! /bin/sh

# Copy PHPREDMIN env variables to .profile for cron jobs
printenv | grep PHPREDMIN | xargs -rl echo "export$1" >> $HOME/.profile

# Run gearman job server
gearmand -d
# Run cron daemon
cron

# Trap sigkill
trap "pkill gearmand && pkill -WINCH apache2" TERM

# Run gearman work & Start web server
php index.php gearman/index & apache2-foreground
