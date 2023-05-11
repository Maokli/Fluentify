FROM ubuntu
RUN apt-get update -y
RUN apt-get upgrade -y

# install php independant packages
ENV TZ=Europe/Minsk
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone
RUN apt-get install software-properties-common curl unzip -y

# add php repo
RUN LC_ALL=C.UTF-8 add-apt-repository ppa:ondrej/php 

# install php packages

RUN apt install libsodium-dev libsodium23 php libapache2-mod-php php-pgsql php8.1-pgsql php8.1-mbstring php-cli php-xml -y 
RUN curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php
RUN  php -r "if (hash_file('SHA384', '/tmp/composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer

# install symfony cli
RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

# copy the project into the container
COPY . /usr/fluentify

#CD into the project
WORKDIR /usr/fluentify

# update and install packages 
RUN composer require nelmio/cors-bundle
RUN composer update
RUN composer i
RUN php bin/console lexik:jwt:generate-keypair


EXPOSE 8000

# run the server
CMD symfony server:start