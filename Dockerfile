FROM llitllie/swoole-zookeeper:php72-cli-alpine

WORKDIR /usr/src/app
COPY . ./
ARG COMPOSER_ARGS="install"
RUN composer ${COMPOSER_ARGS} --prefer-dist --ignore-platform-reqs --no-progress --no-suggest --no-scripts --ansi
ENV ZOOKEEPER_CONNECTION "192.168.33.1:2181,192.168.33.1:2182,192.168.33.1:2183"
EXPOSE 9501
CMD ["php", "bin/server.php"]