#!/bin/bash
cd /home/node/app && ./node_modules/webpack-dev-server/bin/webpack-dev-server.js --config webpack.config.js --progress --content-base ./public --output-public-path /build/