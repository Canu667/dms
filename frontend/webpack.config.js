const path = require('path')
const webpack = require('webpack');

module.exports = {
    context: __dirname,
    entry: [
        './src/index.jsx'
    ],
    devtool: 'cheap-eval-source-map',
    output: {
        path: path.join(__dirname, 'public'),
        filename: 'bundle.js',
        publicPath: '/public/'
    },
    resolve: {
        extensions: ['.js', '.jsx', '.json']
    },
    devServer: {
        port: 9009,
        host: '0.0.0.0',
        disableHostCheck: true,
        inline: true,
        publicPath: '/public/',
    },
    module: {
        /* it uses some kind of rule on your code */
        rules: [
            {
                enforce: 'pre', /* ensures that it runs before babel */
                test: /\.jsx?$/,
                loader: 'eslint-loader',
                exclude: /node_modules/
            },
            {
                test: /\.jsx?$/,         // Match both .js and .jsx files
                exclude: /node_modules/,
                loader: "babel-loader",
                query:
                    {
                        presets: ['react']
                    }
            }
        ]
    }
}
