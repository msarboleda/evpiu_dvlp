#!/usr/bin/env bash

Update () {
  echo "Actualizando Linux..."
  sudo apt-get update >/dev/null 2>&1
  sudo apt-get upgrade >/dev/null 2>&1
}

restartApache() {
  echo "Reiniciando Apache..."
  sudo /etc/init.d/apache2 restart >/dev/null 2>&1
}

echo ""
echo "======================================="
echo "|             Inicializando           |"
echo "======================================="
echo ""
Update

echo "Instalando programas esenciales..."
sudo apt-get install -y vim htop curl build-essential python-software-properties git >/dev/null 2>&1

echo "Instalando Apache..."
sudo apt-get install -y apache2 >/dev/null 2>&1

echo "Instalando MariaDB..."
sudo debconf-set-selections <<< "maria-db-server-10.1 mysql-server/root_password password root" >/dev/null 2>&1
sudo debconf-set-selections <<< "maria-db-server-10.1 mysql-server/root_password_again password root" >/dev/null 2>&1
sudo apt-get install -y mariadb-server >/dev/null 2>&1

echo "Configurando y asegurando MariaDB ..."
sudo systemctl enable mysql >/dev/null 2>&1
sudo systemctl start mysql >/dev/null 2>&1
echo "DELETE FROM mysql.user WHERE User='';" | mysql -uroot -proot
echo "DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1');" | mysql -uroot -proot
echo "DROP DATABASE IF EXISTS test;" | mysql -uroot -proot
echo "DELETE FROM mysql.db WHERE Db='test' OR Db='test\\_%';" | mysql -uroot -proot
echo "FLUSH PRIVILEGES;" | mysql -uroot -proot
sudo systemctl reload mysql >/dev/null 2>&1

echo "Installing PHP 7.0..."
sudo apt-get install -y php7.0-common php7.0-dev php7.0-json php7.0-opcache php7.0-cli libapache2-mod-php7.0 php7.0 php7.0-mysql php7.0-fpm php7.0-curl php7.0-gd php7.0-mcrypt mcrypt php-mbstring php7.0-mbstring php7.0-bcmath php7.0-zip php7.0-xmlrpc php-pear php-memcached >/dev/null 2>&1
Update

echo "Configurando PHP & Apache..."
sed -i "s/error_reporting = .*/error_reporting = E_ALL/" /etc/php/7.0/apache2/php.ini
sed -i "s/display_errors = .*/display_errors = On/" /etc/php/7.0/apache2/php.ini
sudo a2enmod rewrite >/dev/null 2>&1

echo "Creando virtual hosts..."
echo ""
sudo ln -fs /vagrant/webroot/ /var/www/evpiudvlp >/dev/null 2>&1
cat << EOF | sudo tee -a /etc/apache2/sites-available/default.conf
<Directory "/var/www/">
    AllowOverride All
</Directory>
<VirtualHost *:80>
    DocumentRoot /var/www/evpiudvlp/
    ServerName evpiudvlp.local
    ServerAlias www.evpiudvlp.local
</VirtualHost>
<VirtualHost *:80>
    DocumentRoot /var/www/phpmyadmin
    ServerName phpmyadmin.local
    ServerAlias www.phpmyadmin.local
</VirtualHost>
EOF
sudo a2dissite 000-default.conf >/dev/null 2>&1
sudo a2ensite default.conf >/dev/null 2>&1
restartApache

echo "Instalando Composer..."
curl -s https://getcomposer.org/installer | php >/dev/null 2>&1
sudo mv composer.phar /usr/local/bin/composer >/dev/null 2>&1
sudo chmod +x /usr/local/bin/composer >/dev/null 2>&1

echo "Instalando PHPUnit ..."
sudo wget https://phar.phpunit.de/phpunit.phar >/dev/null 2>&1
sudo chmod +x phpunit.phar >/dev/null 2>&1
sudo mv phpunit.phar /usr/local/bin/phpunit >/dev/null 2>&1

echo "Instalando phpMyAdmin ..."
sudo debconf-set-selections <<< "maria-db-server-10.1 mysql-server/root_password password root" >/dev/null 2>&1
sudo debconf-set-selections <<< "maria-db-server-10.1 mysql-server/root_password_again password root" >/dev/null 2>&1
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/dbconfig-install boolean true" >/dev/null 2>&1
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/app-password-confirm password root" >/dev/null 2>&1
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/mysql/admin-pass password root" >/dev/null 2>&1
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/mysql/app-pass password root" >/dev/null 2>&1
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/reconfigure-webserver multiselect none" >/dev/null 2>&1
sudo apt-get install -y phpmyadmin >/dev/null 2>&1

echo "Configurando phpMyAdmin..."
sudo ln -sf /usr/share/phpmyadmin /var/www/phpmyadmin >/dev/null 2>&1
echo "GRANT ALL PRIVILEGES ON *.* TO 'phpmyadmin'@'localhost' WITH GRANT OPTION;" | mysql -uroot -proot
echo "FLUSH PRIVILEGES;" | mysql -uroot -proot
sudo systemctl reload mysql >/dev/null 2>&1

echo "Instalando pre-requisitos para SQL Drivers..."
curl -sS https://packages.microsoft.com/keys/microsoft.asc | apt-key add - | php
curl -sS https://packages.microsoft.com/config/ubuntu/16.04/prod.list > /etc/apt/sources.list.d/mssql-release.list | php
sudo apt-get update >/dev/null 2>&1
sudo ACCEPT_EULA=Y apt-get install -y msodbcsql mssql-tools >/dev/null 2>&1
sudo apt-get install -y unixodbc-dev >/dev/null 2>&1
echo 'export PATH="$PATH:/opt/mssql-tools/bin"' >> ~/.bash_profile
echo 'export PATH="$PATH:/opt/mssql-tools/bin"' >> ~/.bashrc
source ~/.bashrc

echo "Instalando SQL Drivers..."
sudo pear config-set php_ini `php --ini | grep "Loaded Configuration" | sed -e "s|.*:\s*||"` system >/dev/null 2>&1
sudo pecl install sqlsrv >/dev/null 2>&1
sudo pecl install pdo_sqlsrv >/dev/null 2>&1

echo "Asignando permisos para archivos de configuración..."
sudo chmod 0436 /etc/php/7.0/apache2/php.ini
sudo chmod 0436 /etc/php/7.0/fpm/php.ini
sudo chmod 0436 /etc/php/7.0/cli/php.ini

echo "Configurando SQL Drivers..."
sudo a2dismod mpm_event >/dev/null 2>&1
sudo a2enmod mpm_prefork >/dev/null 2>&1
sudo a2enmod php7.0 >/dev/null 2>&1
sudo echo "extension=/usr/lib/php/20151012/sqlsrv.so" >> /etc/php/7.0/apache2/php.ini
sudo echo "extension=/usr/lib/php/20151012/pdo_sqlsrv.so" >> /etc/php/7.0/apache2/php.ini
sudo echo "extension=/usr/lib/php/20151012/sqlsrv.so" >> /etc/php/7.0/fpm/php.ini
sudo echo "extension=/usr/lib/php/20151012/pdo_sqlsrv.so" >> /etc/php/7.0/fpm/php.ini
sudo echo "extension=/usr/lib/php/20151012/sqlsrv.so" >> /etc/php/7.0/cli/php.ini
sudo echo "extension=/usr/lib/php/20151012/pdo_sqlsrv.so" >> /etc/php/7.0/cli/php.ini

echo "Restaurando permisos..."
sudo chmod 0644 /etc/php/7.0/apache2/php.ini
sudo chmod 0644 /etc/php/7.0/fpm/php.ini
sudo chmod 0644 /etc/php/7.0/cli/php.ini
sudo systemctl reload php7.0-fpm >/dev/null 2>&1
restartApache

echo ""
echo "======================================="
echo "|         Instalación Completa        |"
echo "======================================="
echo ""
echo "Plataforma:"
echo "http://evpiudvlp.local (192.168.100.100)"
echo ""
echo "phpMyAdmin:"
echo "http://evpiudvlp.local/phpmyadmin"
echo "Usuario: phpmyadmin"
echo "Contraseña: root"
echo ""
echo "======================================="
echo ""