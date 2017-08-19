# Dropbox Public Folder Replacement (DPFR)

Dropbox are [removing support for the Public folder functionality](https://www.dropbox.com/help/files-folders/public-folder).

This is a drop-in replacement for your Dropbox public folder links.

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

### Requirements

This script is tailored to run on most conventional web hosts that support a modern version of PHP.

* PHP 5.5
* mbstring

### Setup

Download the latest release version [here](). If you don't download the release version you will need to install dependencies via Composer.

#### Setting up your Dropbox app

DPFR does **not** support getting files directly from your Dropbox public folder. This is for security reasons. Instead, 
we will create a special "App folder", and move our files from the Public folder to the new App folder.

* Go to ["My apps"](https://www.dropbox.com/developers/apps/create) in your Dropbox control panel.
* In step 1, create a "Dropbox API" app.
* In step 2, choose "App folder"
* In step 3, name your app to "Public folder replacement"
* You will be redirected to the app info page. Write down the `App key` and `App secret`
* In the Oauth2 section, click the "Generate" button and write down the `Generated access token`

#### Configuring DPFR

* Start by uploading the script to your web host.
* Copy the file `config.sample.php` to `config.php`.
* Edit `config.php` with the value obtained in the previous step.
    * `appKey` = App key
    * `appSecret` = App secret
    * `accessToken` = Generated access token

#### Web server: Apache

If you have put the script in a subfolder, please modify the `.htaccess` file. For example:

If your script URL is: 

```
http://mysite.com/dropbox/
```

Your `.htaccess` file should read:

```
ErrorDocument /dropbox/index.php
```

#### Web server: Nginx

Make sure to rewrite all your requests to `index.php`. For example this can be done using the following location block:

```
location / {
    try_files $uri $uri/ /index.php;
}
```

#### Generating links

Links are generated in the same manner as 

### To-do

* Add file cache
* Nicer error page

Pull requests are welcome.