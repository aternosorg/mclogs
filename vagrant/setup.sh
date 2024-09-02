#!/bin/bash

sudo sed -i '1 s/^.*$/mclogs.local/' /etc/hostname
sudo hostname mclogs.local

sudo apt-get update
sudo apt-get upgrade -y

sudo add-apt-repository -y ppa:ondrej/php
sudo apt-get update

sudo apt-get install php8.1-fpm php8.1-mongodb php8.1-xml php8.1-redis php8.1-curl php8.1-mbstring nginx mongodb redis-server -y

bash /web/mclogs/vagrant/setup-composer.sh

cp /web/mclogs/vagrant/nginx/* /etc/nginx/sites-enabled/
sudo service nginx restart
sudo service php8.1-fpm restart
