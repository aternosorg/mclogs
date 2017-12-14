#!/bin/bash

sudo sed -i '1 s/^.*$/mclogs.local/' /etc/hostname
sudo hostname aternos.dev

sudo apt-get update
sudo apt-get upgrade -y

sudo apt-get install screen curl python-software-properties zip unzip htop rsync php php-fpm php-mongodb php-xml git nginx mongodb composer redis-server php-redis -y

cp /vagrant/nginx/* /etc/nginx/sites-enabled/

sudo service nginx restart
sudo service php7.0-fpm restart

#cd /web/mclogs && composer install