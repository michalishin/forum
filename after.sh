#!/bin/sh

# If you would like to do some extra provisioning you may
# add any commands you wish to this file and they will
# be run after the Homestead machine is provisioned.

#php-dev
sudo apt install php7.2-gd -y

#laravel
php artisan migrate --seed

#node
npm run watch &>/dev/null &