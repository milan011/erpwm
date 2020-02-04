let mix = require('laravel-mix');
let path = require('path');
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

let config = {
  resolve: {
    alias: {
      '@': path.resolve("resources/assets/js"),
      // 'vendor': path.resolve('resources/assets/js/vendor'),
    },
  },
  output: {
    // filename:'js/app.js',
    // chunkFilename: 'js/chunks/[name].js',
    publicPath: "/",
    chunkFilename: 'js/chunks/[name].js'
  },
  /*externals: {//配置不打包的选项
        'element-ui': 'Element',
        'axios': 'axios', 
        'vue': 'Vue', 
        'vuex': 'Vuex', 
        'vue-router': 'VueRouter', 
        'vue-chartjs': 'VueChartJs', 
        'lodash': '_', 
    }, */
  module: {
    /*loaders: [
      {
        test: /\.es6$/,
        exclude: /node_modules/,
        loader: 'babel',
        query: {
          presets: ['es2015']
        }
      }
    ],*/
    rules: [{
      test: /\.jsx?$/,
      include: /(node_modules\/element-ui)/,
      use: [{
        loader: 'babel-loader',
        options: {
          presets: 'vue-app',
          plugins: [
            ["component", [{
              "libraryName": "element-ui",
              "styleLibraryName": "theme-default"
            }]], "transform-vue-jsx"
          ]
        }
      }]
    }, ]
  },
  // chunks:[]
}


// console.log(path.resolve("resources/assets/js"));

mix.webpackConfig(config);

mix.js('resources/assets/js/app.js', 'public/js')
  .sass('resources/assets/sass/app.scss', 'public/css').version();

/*mix.js('resources/assets/js/adminPc.js', 'public/js')
   .sass('resources/assets/sass/adminPc.scss', 'public/css')
   .js('resources/assets/js/adminMobile.js', 'public/js')
   .sass('resources/assets/sass/adminMobile.scss', 'public/css')
   .version();*/
// mix.browserSync('tclvue.net');

/* mix.js('resources/assets/js/admin/pc/appPc.js', 'public/js')
 .sass('resources/assets/sass/appPc.scss', 'public/css')
 .js('resources/assets/js/admin/mobile/appMobile.js', 'public/js')
 .sass('resources/assets/sass/appMobile.scss', 'public/css')
 .version();*/

/**
 * 发布时文件名打上 hash 以强制客户端更新
 */

if (mix.inProduction()) {
  mix.version()
} else {
  mix.disableNotifications()
}