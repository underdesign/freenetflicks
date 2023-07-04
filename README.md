# Free Netflicks - deploy a wifi captive file sharing site on a Raspberry Pi

With the outrageous costs of streaming services quietly replacing your old all-in-one cable bill, it's time to subvert the Hollywood machine and offer your own selections of copyright-free material.
 
---In order to save the usual disk thrashing one might expect with hosting a file server, all the hosted files should be on an external USB stick named 'free_netflicks' and inserted into an available USB port on the running Raspberry Pi.  This might limit your choices for models to host this service to models that have a full-sized USB port.  This allows for massive sharing libraries and quick changes when upkeep happens.

Easy installation after a fresh install of Raspbian Buster Lite:
```
sudo apt update
sudo apt install git
git clone https://github.com/underdesign/freenetflicks
cd freenetflicks
chmod +x install.sh
sudo ./install.sh
sudo reboot
```
During installation, macchanger will ask whether or not MAC addresses should be changed automatically - choose "No". The startup script in rc.local will perform this task more reliably.

After reboot, look for an access point named "Free Netflicks". Connecting to it from any device should automatically bring up a list of files hosted on the USB stick.  There's a place to download a PDF sign to hang in your front window, as well as collect suggestions from guests in your little hidden file sharing world.

Suggestions are logged in `/var/www/html/suggestions.txt`.


