#!/bin/bash

sudo sed -i '1 s/^.*$/mclogs.local/' /etc/hostname
sudo hostname mclogs.local

sudo apt-get update
sudo apt-get upgrade -y

sudo add-apt-repository -y ppa:ondrej/php
sudo apt-get update

sudo apt-get install php8.0-fpm php8.0-mongodb php8.0-xml php8.0-redis nginx mongodb redis-server -y

bash /web/mclogs/vagrant/setup-composer.sh

cp /web/mclogs/vagrant/nginx/* /etc/nginx/sites-enabled/
sudo service nginx restart
sudo service php8.0-fpm restart
