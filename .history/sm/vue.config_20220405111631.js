module.exports = {
    pages: {
      index: {
        entry: 'src/main.js',
        title: 'Spam Manager'
      }
    },
  
    publicPath: ".",
    assetsDir: process.env.NODE_ENV === 'production' ? '../modules/addons/SpamManager/lib/app/Views/' : '',
    indexPath: 'home@index.tpl',
    devServer: {
      // open: process.platform === 'darwin',
      // host: '0.0.0.0',
      // port: 8080, // CHANGE YOUR PORT HERE!
      https: true,
      // hotOnly: false,
      //public: 'https://localhost:8080/',
      public: 'https://localhost:8080/',
      proxy: {
        "addonmodules.php": {
         target: "https://ticketing.stage.tmdhosting.com/admin/",
        // target: "https://my.tmdhosting.com/admin/",
          logLevel: "debug",
          // changeOrigin: true,
          secure: false,
          withCredentials: true,
        // cookieDomainRewrite: { "localhost": "my.tmdhosting.com" },
       cookieDomainRewrite: { "localhost": "ticketing.stage.tmdhosting.com" },
          headers: { Cookie: 'WHMCSBaCqM4Y33YVw=acb35266e9fee3ec53e7086cec02b335'},
        }
      }
    }
  }