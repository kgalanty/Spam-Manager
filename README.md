# SpamManager

## Project setup

1. 
```
cd sm
npm install
npm run build
```

2. Copy `SpamManager` folder to `/modules/addons/` in WHMCS
3. Copy `sm/modules` to `MAIN WHMCS dir` to integrate with its `modules` dir
4. Copy `sm/dist/home@index.tpl` to `/modules/addons/SpamManager/lib/app/Views`

5. Setup crontab entry
```
*/5 * * * * php -q /var/www/projects/my.tmdhosting.com/modules/addons/SpamManager/mailingcron.php`
```