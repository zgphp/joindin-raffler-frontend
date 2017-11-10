#!/bin/sh

set -e -x

echo "127.0.0.1 www.raffler.loc" >> /etc/hosts
echo "127.0.0.1 test.raffler.loc" >> /etc/hosts

#Setup Nginx
cp etc/circleci2/symfony-test.conf /etc/nginx/sites-enabled/symfony-test.conf

service php7.1-fpm restart
service nginx restart

cp etc/circleci2/.env.dist .env