#
# Nginx Dockerfile
#
# Written by:
#   Baptiste MOINE <bap.moine.86@gmail.com>
#

# Pull base image.
FROM nginx:latest

MAINTAINER Baptiste MOINE <bap.moine.86@gmail.com>

# Create Nginx file structure.
RUN rm -rf /usr/share/nginx/html/ \
&& mkdir -p /usr/share/nginx/static \
&& mkdir -p /usr/share/nginx/webroot

# Create volumes.
VOLUME ["/etc/nginx/", "/usr/share/nginx/"]

# Set workdir.
WORKDIR /usr/share/nginx/webroot/

# TCP port that container will listen for connections.
# HTTP and HTTPS.
EXPOSE 80/tcp 443/tcp

CMD ["sh", "-c", "nginx -g \"daemon on;\" && tail -F /usr/share/nginx/log/*.log"]

