FROM nubs/phpunit

USER root

ADD provisioning/sockets.ini /etc/php/conf.d/sockets.ini

USER build

CMD ["./build.php"]
