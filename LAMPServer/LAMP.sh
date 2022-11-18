#Get Latest Packages 
sudo apt update && sudo apt upgrade

#Install Apache
sudo apt-get install apache2 -y
#Install PHP
sudo apt install libapache2-mod-php
#Change Dir to Public Html
cd /var/www
#Grant ownership to pi user
sudo chown pi: html
#Restart the Apache service
sudo /etc/init.d/apache2 restart
#Install PHP
sudo apt install php
#Install MySQL
sudo apt install mariadb-server php-mysql -y
#Start the DB monitor
sudo mysql
#Create User Query 
CREATE USER ‘admin’@’localhost’ IDENTIFIED BY ‘password’;
#Grant User Permissions 
GRANT ALL PRIVILEGES ON *.* to ‘admin’@’localhost’ WITH GRANT OPTION;
#Exit the DB monitor
exit
#Install PHPMyAdmin
sudo apt-get install phpmyadmin
#Choose apache2 as web server
#Edit Apache Config 
sudo nano /etc/apache2/apache2.conf
#Go all the way to the bottom of the file 
#Add the line 

#Include PHPMyAdmin
Include /etc/phpmyadmin/apache.conf
#Hit ctrl+X and then answer with 'y' to save
#Restart the Apache service
sudo /etc/init.d/apache2 restart
#Reboot Raspberry Pi 
sudo reboot

#If everything works, open a web browser and try to hit your
#hostname proceded by http://, for example, http://localhost
# You can see the Apace2 Debian Default Page
#The next step is try to acces http://localhost/phpmyadmin
#You can use the user created to access