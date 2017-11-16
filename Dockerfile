FROM php:7.1-cli-alpine

RUN apk update && apk add wget

ADD ./src /opt/src
RUN cd /opt/src && ./composer-install.sh && ./composer.phar -vvv install \
   && php ./vendor/bin/phpunit --verbose  -c ./phpunit.xml

ENTRYPOINT ["php", "/opt/src/app.php", "movie:recommend"]
CMD ["animation"]