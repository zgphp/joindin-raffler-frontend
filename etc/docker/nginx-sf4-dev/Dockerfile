FROM msvrtan/sf4-nginx-dev:latest

RUN sed -i.bak "s#DEV_DOMAIN_NAME#dev.frontend-raffler.loc#g" /etc/nginx/sites-available/symfony-dev.conf
RUN sed -i.bak "s#WEBROOT_PATH#/var/www/public/#g" /etc/nginx/sites-available/symfony-dev.conf
RUN sed -i.bak "s#TEST_DOMAIN_NAME#test.frontend-raffler.loc#g" /etc/nginx/sites-available/symfony-test.conf
RUN sed -i.bak "s#WEBROOT_PATH#/var/www/public/#g" /etc/nginx/sites-available/symfony-test.conf

RUN rm /etc/nginx/sites-available/symfony-dev.conf.bak
RUN rm /etc/nginx/sites-available/symfony-test.conf.bak

CMD ["nginx"]

EXPOSE 80 443
