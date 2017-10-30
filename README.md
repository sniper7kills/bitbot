# BitBot
This is under development...
Do not expect updates any time soon.

## Install
```
sudo apt-get update
sudo apt-get upgrade -y

sudo /bin/dd if=/dev/zero of=/var/swap.1 bs=1M count=1024
sudo /sbin/mkswap /var/swap.1
sudo /sbin/swapon /var/swap.1

sudo apt-get install apache2 mysql php php-dev php-curl php-xml php-mbstring php-mcrypt php-json mysql-server php-zip php-mysql libapache2-mod-php

wget https://getcomposer.org/installer
php ./installer
rm -rf installer
sudo mv composer.phar /usr/bin/composer

git clone https://github.com/sniper7kills/bitbot.git
```
