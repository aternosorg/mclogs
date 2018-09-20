#!/bin/bash

sudo sed -i '1 s/^.*$/mclogs.local/' /etc/hostname
sudo hostname mclogs.local

sudo apt-get update
sudo apt-get upgrade -y

sudo apt-get install php-fpm php-mongodb php-xml php-redis nginx mongodb composer redis-server -y

cp /vagrant/nginx/* /etc/nginx/sites-enabled/
sudo service nginx restart
sudo service php7.2-fpm restart
