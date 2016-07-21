#
# StackEdit Dockerfile
#
# Written by:
#   Baptiste MOINE <bap.moine.86@gmail.com>
#

# Pull base image.
FROM debian:8.5

MAINTAINER Baptiste MOINE <bap.moine.86@gmail.com>

# Install nodeJS and StackEdit.
RUN apt-get update && apt-get install -y \
		git \
		nodejs-legacy \
		npm \
    && git clone https://github.com/benweet/stackedit.git

WORKDIR stackedit

RUN npm install \
    && node_modules/bower/bin/bower install --allow-root --production --config.interactive=false

CMD nodejs server.js

EXPOSE 3000

