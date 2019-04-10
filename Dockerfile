FROM harvardinformatics/wheezy-php55

RUN apt-get update -o Acquire::Check-Valid-Until=false -y

RUN a2enmod php5 && \
    sed -i -e 's?ErrorLog.*?ErrorLog /dev/stderr?' /etc/apache2/apache2.conf && \
    sed -i -e 's?ErrorLog.*?ErrorLog /dev/stderr?' /etc/apache2/sites-enabled/000-default && \
    printf "\nAddHandler php5-script .html\n" >> /etc/apache2/sites-enabled/000-default && \
    printf "display_error = stderr\nerror_log = /dev/stderr\n" > /etc/php5/apache2/conf.d/20-logging.ini && \
    rm -f /var/www/index.html

ADD . /var/www/

EXPOSE 80

CMD ["apachectl", "-DFOREGROUND"]
