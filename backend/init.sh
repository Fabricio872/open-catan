#!/bin/bash
root="/var/www/html"
if [ ! -d "${root}/.git" ]; then
  git clone https://github.com/Fabricio872/open-catan.git $root
  git git checkout origin/develop
  composer install -d $root
  chown -R www-data:www-data $root
  ${root}/bin/console cache:warmup

  if [ ! -f "${root}/var/data.db" ]; then
    ${root}/bin/console doctrine:database:create
    ${root}/bin/console doctrine:schema:update --force
  fi
else
  git reset --hard origin/master
  composer install -d $root
  chown -R www-data:www-data $root
  ${root}/bin/console cache:warmup
fi

chmod 777 -R $root
/usr/sbin/apache2ctl -D FOREGROUND
