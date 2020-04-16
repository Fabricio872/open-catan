#!/bin/bash
root="/var/www/html"
rootBackend="/var/www/html/backend"
if [ ! -d "${root}/.git" ]; then
  git clone https://github.com/Fabricio872/open-catan.git $root
  git checkout origin/develop
  echo -e $"RewriteEngine On\nRewriteRule (.*) backend/$1 [L]" > "${root}/.htaccess"
  composer install -d $rootBackend
  chown -R www-data:www-data $root
  cp -R /jwt ${rootBackend}/var/
  ${rootBackend}/bin/console cache:warmup

  if [ ! -f "${root}/var/data.db" ]; then
    ${rootBackend}/bin/console doctrine:database:create
    ${rootBackend}/bin/console doctrine:schema:update --force
  fi
else
  git reset --hard origin/master
  composer install -d $rootBackend
  chown -R www-data:www-data $root
  ${rootBackend}/bin/console cache:warmup
fi

chmod 777 -R $root
/usr/sbin/apache2ctl -D FOREGROUND
