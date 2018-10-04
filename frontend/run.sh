#!/bin/bash
sudo sed -i.bak "s/%SERVER%/$(curl http://169.254.169.254/latest/meta-data/public-hostname)/g" /var/app/current/frontend/public/index.html
cd /home/node/app && ./node_modules/webpack-dev-server/bin/webpack-dev-server.js --config webpack.config.js --progress --content-base ./public --output-public-path /build/
