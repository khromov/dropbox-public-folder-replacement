# Dropbox Public Folder Replacement (DPFR)

Dropbox are [removing support for the Public folder functionality](https://www.dropbox.com/help/files-folders/public-folder).

This is a drop-in script replacement for your Dropbox public folder links.

As Dropbox no longer provides the service, DPFR runs on your web host instead. Files are still fetched in real-time from Dropbox
using their [API](https://www.dropbox.com/developers/documentation/http/overview).

For example, while your old links might have looked like this:

```
https://dl.dropboxusercontent.com/u/123456/myfile.txt
```

Your new links will be sent via your web host and will look like this:

```
https://mysite.com/dropbox/myfile.txt
```

Instead of manually generating new Dropbox share links, this enables you to simply change the domain name for your links and be done with it.

### Requirements

This script is tailored to run on most conventional web hosts that support a modern version of PHP.

* PHP 5.5
* mbstring

### Setup

Download the latest release version [here](https://github.com/khromov/dropbox-public-folder-replacement/releases/download/1.0/dropbox-public-folder.zip). If you don't download the release version you will need to install dependencies via Composer.

#### Setting up your Dropbox app

DPFR does **not** support getting files directly from your Dropbox public folder. This is for security reasons. Instead, 
we will create a special "App folder", and move our files from the Public folder to the new App folder.

* Go to ["My apps"](https://www.dropbox.com/developers/apps/create) in your Dropbox control panel.
* In step 1, create a "Dropbox API" app.
* In step 2, choose "App folder"
* In step 3, name your app to "Public folder replacement"
* You will be redirected to the app info page. Write down the `App key` and `App secret`
* In the Oauth2 section, click the "Generate" button and write down the `Generated access token`

![2017-08-19 02_21_44-developers - dropbox](https://user-images.githubusercontent.com/1207507/29482018-4c7952a0-8489-11e7-82da-25d49e30fe34.png)

App settings

![2017-08-19 02_22_20-my public folder replacement - dropbox](https://user-images.githubusercontent.com/1207507/29482019-4fba6e54-8489-11e7-88c3-55f3e39c762a.png)

Generating OAuth token

#### Configuring DPFR

* Start by uploading the script to your web host.
* Copy the file `config.sample.php` to `config.php`.
* Edit `config.php` with the value obtained in the previous step.
    * `appKey` = App key
    * `appSecret` = App secret
    * `accessToken` = Generated access token
* Move or copy your files from the `Public` folder to the `Apps/Public folder replacement` folder. 

#### Web server: Apache

You will need the mod_rewrite apache module enabled for this to work.

Add the following to your `.htaccess file`:

```
<IfModule mod_rewrite.c>
  RewriteEngine on
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteCond %{REQUEST_URI} !=/favicon.ico
  RewriteRule ^(.*)$ index.php?q=$1 [L,QSA]
</IfModule>
```

Remove or comment out the ErrorDocument line.

If you have put the script in a subfolder you will need to modify the `$urlPath` variable by adding an additional line  immediately below the line that defines this variable in index.php, eg

```
$urlPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$urlPath = preg_replace( "/^\/my_subfolder/", '', $urlPath);
```

replace 'my_subfolder' with the name of your folder. 


#### Web server: Nginx

Make sure to rewrite all your requests to `index.php`. For example this can be done using the following location block:

```
location / {
    try_files $uri $uri/ /index.php;
}
```

### To-do

* Add file cache
* Nicer error page

Pull requests/issues are welcome.
