#!/bin/bash

FILE=install.lock
FILE2=install.ready

if [ -f "$FILE2" ]; then
    echo "Installation of Laravel finished. You can click the RUN->Run button to test your app. If this is a resintallation please remove the install.ready or install.lock and try again."
else 
    if [ -f "$FILE" ]; then
        echo "Installation of Laravel environnement in progress, when finished, a file named 'install.ready' will appear on the left sidebar then, you can click the RUN button->Run. If this is a resintallation please remove the install.ready or install.lock and try again."
    else 
        while sudo fuser /var/lib/dpkg/lock /var/lib/apt/lists/lock /var/cache/apt/archives/lock >/dev/null 2>&1; do echo 'Preparing installation...'; sleep 5; done;

        touch install.lock > /dev/null 2>&1 

        echo "Installation of Laravel environnement - This can take up to 5 minutes"

        echo "1% completed - Prepare server..."

        apt-get -y purge 'php*' > /dev/null 2>&1 
        apt-get -y install software-properties-common > /dev/null 2>&1
        add-apt-repository ppa:ondrej/php -y > /dev/null 2>&1
        apt update > /dev/null 2>&1

        echo "10% completed - Installing php modules..."

        apt-get install -y -f > /dev/null 2>&1
        apt-get -y install openssl php7.4 php7.4-common php7.4-cli php7.4-xml php7.4-zip php7.4-mysql php7.4-gd php7.4-imagick php7.4-tidy php7.4-xmlrpc php7.4-mbstring php7.4-curl php7.4-json php7.4-bcmath > /dev/null 2>&1

        echo "30% completed - Installing dependencies (this can take a while)..."

        composer global require hirak/prestissimo > /dev/null 2>&1 
        composer install > /dev/null 2>&1

        npm set progress=false > /dev/null 2>&1 
        npm install > /dev/null 2>&1 

        echo "60% completed - Prepare MySql Server..."

        mkdir storage/logs > /dev/null 2>&1

        npm run dev > /dev/null 2>&1 

        echo "Canada/Toronto" > /etc/timezone
        dpkg-reconfigure -f noninteractive tzdata > /dev/null 2>&1

        debconf-set-selections <<< 'mysql-server mysql-server/root_password password root' 
        debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password root'
        apt-get -y install mysql-server > /dev/null 2>&1

        echo "90% completed - Finishing..."

        service mysql restart > /dev/null 2>&1

        touch install.sql > /dev/null 2>&1 

        echo 'CREATE DATABASE hackerrank;' >> install.sql

        mysql -u root -proot < install.sql
        
        rm install.sql > /dev/null 2>&1 

        php artisan migrate > /dev/null 2>&1 

        touch install.ready > /dev/null 2>&1 

        rm install.lock > /dev/null 2>&1 

        chmod -R 777 . > /dev/null 2>&1

        echo "100% completed - Installationg finished"

        echo "Environnement ready, you can now execute your app clicking the Run button."


    fi
fi