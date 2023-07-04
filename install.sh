#!/bin/sh -e

if [ "$(id -u)" != "0" ]; then
   echo "This script must be run as root, silly!" 1>&2
   exit 1
fi

echo "Thanks for installing Free Netflicks!"
echo "Installing the GOAT dependencies..."
apt update
apt -y install macchanger hostapd dnsmasq apache2 php

echo "Subverting the man..."
cp -f configs/hostapd.conf /etc/hostapd/
cp -f configs/dnsmasq.conf /etc/
cp -rf html /var/www/
chown -R www-data:www-data /var/www/html
chown root:www-data /var/www/html/.htaccess
cp -f configs/rc.local /etc/
cp -f configs/override.conf /etc/apache2/conf-available/
a2enconf override
a2enmod rewrite

echo "Throwing away perfectly good configs..."
sudo rm -rf /etc/wpa_supplicant/wpa_supplicant.conf

echo "Free Netflicks installed. Reboot to start sharing!"
exit 0
