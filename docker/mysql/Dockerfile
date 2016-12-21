FROM mysql:5.7

ADD startup /etc/mysql/startup
ADD my.cnf /etc/mysql/my.cnf

RUN chown -R mysql:root /var/lib/mysql/

ARG DB_DATABASE=homestead
ARG DB_USERNAME=homestead
ARG DB_PASSWORD=secret

ENV DB_DATABASE=$DB_DATABASE
ENV DB_USERNAME=$DB_USERNAME
ENV DB_PASSWORD=$DB_PASSWORD

RUN sed -i 's/MYSQL_DATABASE/'$DB_DATABASE'/g' /etc/mysql/startup && \
    sed -i 's/MYSQL_USER/'$DB_USERNAME'/g' /etc/mysql/startup && \
    sed -i 's/MYSQL_PASSWORD/'$DB_PASSWORD'/g' /etc/mysql/startup && \
    sed -i 's/MYSQL_PASSWORD/'$DB_PASSWORD'/g' /etc/mysql/my.cnf


CMD ["mysqld", "--init-file=/etc/mysql/startup"]

EXPOSE 3306
