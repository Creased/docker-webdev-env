#
# Nginx Dockerfile
#
# Written by:
#   Baptiste MOINE <contact@bmoine.fr>
#

# Pull base image.
FROM nginx:latest

MAINTAINER Baptiste MOINE <contact@bmoine.fr>

# Create Nginx file structure.
RUN rm -rf /usr/share/nginx/html/ \
&& mkdir -p /usr/share/nginx/static \
&& mkdir -p /usr/share/nginx/webroot

# Optional proxy configuration
# COPY /etc/apt/apt.conf.d/70debconf /etc/apt/apt.conf.d/70debconf

# Create volumes.
VOLUME ["/etc/nginx/", "/usr/share/nginx/"]

# Set workdir.
WORKDIR /usr/share/nginx/webroot/

# TCP port that container will listen for connections.
# HTTP and HTTPS.
EXPOSE 80/tcp 443/tcp

CMD ["sh", "-c", "nginx -g \"daemon on;\" && tail -F /usr/share/nginx/log/*.log"]

