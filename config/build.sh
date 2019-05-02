#!/usr/bin/env bash

echo "Actualizando Ubuntu..."
sudo apt-get update >/dev/null 2>&1

echo "Instalando programas esenciales..."
sudo apt-get install -y curl build-essential python-software-properties git >/dev/null 2>&1

echo "Instalando Apache..."
sudo apt-get install -y apache2 >/dev/null 2>&1

echo "Instalando MariaDB..."
sudo debconf-set-selections <<< "maria-db-server-10.1 mysql-server/root_password password root" >/dev/null 2>&1
sudo debconf-set-selections <<< "maria-db-server-10.1 mysql-server/root_password_again password root" >/dev/null 2>&1
sudo apt-get install -y mariadb-server >/dev/null 2>&1

echo "Configurando y asegurando MariaDB..."
sudo systemctl enable mysql >/dev/null 2>&1
sudo systemctl start mysql >/dev/null 2>&1
echo "DELETE FROM mysql.user WHERE User='';" | mysql -uroot -proot
echo "DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1');" | mysql -uroot -proot
echo "DROP DATABASE IF EXISTS test;" | mysql -uroot -proot
echo "DELETE FROM mysql.db WHERE Db='test' OR Db='test\\_%';" | mysql -uroot -proot
echo "FLUSH PRIVILEGES;" | mysql -uroot -proot
sudo systemctl reload mysql >/dev/null 2>&1

echo "Instalando PHP 7.1..."
sudo add-apt-repository ppa:ondrej/php -y >/dev/null 2>&1
sudo apt-get update >/dev/null 2>&1
sudo apt-get install php7.1 php7.1-dev php7.1-xml -y --allow-unauthenticated >/dev/null 2>&1

echo "Configurando PHP & Apache..."
sudo sed -i "s/error_reporting = .*/error_reporting = E_ALL/" /etc/php/7.1/apache2/php.ini
sudo sed -i "s/display_errors = .*/display_errors = On/" /etc/php/7.1/apache2/php.ini
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
EOF
sudo a2dissite 000-default.conf >/dev/null 2>&1
sudo a2ensite default.conf >/dev/null 2>&1

echo "Instalando Composer..."
curl -s https://getcomposer.org/installer | php >/dev/null 2>&1
sudo mv composer.phar /usr/local/bin/composer >/dev/null 2>&1
sudo chmod +x /usr/local/bin/composer >/dev/null 2>&1

echo "Instalando listas de repositorios para SQL Drivers..."
sudo su -c "curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add -" >/dev/null 2>&1
sudo su -c "curl https://packages.microsoft.com/config/ubuntu/16.04/prod.list > /etc/apt/sources.list.d/mssql-release.list" >/dev/null 2>&1

echo "Refrescando lista de repositorios..."
sudo apt-get update >/dev/null 2>&1

echo "Instalando pre-requisitos para SQL Drivers..."
sudo ACCEPT_EULA=Y apt-get install -y msodbcsql17 >/dev/null 2>&1
sudo ACCEPT_EULA=Y apt-get install -y mssql-tools >/dev/null 2>&1
echo 'export PATH="$PATH:/opt/mssql-tools/bin"' >> ~/.bash_profile
echo 'export PATH="$PATH:/opt/mssql-tools/bin"' >> ~/.bashrc
source ~/.bashrc
sudo apt-get install -y unixodbc-dev >/dev/null 2>&1

echo "Instalando SQL Drivers..."
sudo pecl install sqlsrv >/dev/null 2>&1
sudo pecl install pdo_sqlsrv >/dev/null 2>&1

echo "Configurando SQL Drivers..."
echo extension=pdo_sqlsrv.so >> `php --ini | grep "Scan for additional .ini files" | sed -e "s|.*:\s*||"`/30-pdo_sqlsrv.ini
echo extension=sqlsrv.so >> `php --ini | grep "Scan for additional .ini files" | sed -e "s|.*:\s*||"`/20-sqlsrv.ini
echo "extension=pdo_sqlsrv.so" >> /etc/php/7.1/apache2/conf.d/30-pdo_sqlsrv.ini
echo "extension=sqlsrv.so" >> /etc/php/7.1/apache2/conf.d/20-sqlsrv.ini

echo "Reiniciando Apache..."
sudo service apache2 restart

echo ""
echo "======================================="
echo "|         Instalación Completa        |"
echo "======================================="
echo ""
echo "Plataforma:"
echo "http://evpiudvlp.local (192.168.100.100)"
echo "======================================="
echo ""