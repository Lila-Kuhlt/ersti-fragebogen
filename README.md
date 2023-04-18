# Lila Pause Erstifragebogen

## Basic Information

### Scope and features

Scope:

* PHP standalone application (no framework)
* SQLite database (no database server required)
* initially created by a former Lila Pause tutor in 2014 or 2015

Features:

* Registration form for "Erstis"
* Admin view with statistics about given information
* Edit and delete function for admins
* Export of all data including who has to pay how much as HTML table
* Export of all email addresses to be added to the "Kuhlt" mailing list as plain text

## How to setup

### Server requirements

* Apache >= 2.4, nginx >= 1.16 (older versions may also work but have not been tested)
* PHP >= 8.1
* PDO driver for SQLite3

### Steps to setup

The quick and dirty way to make this web application available online is to simply copy all the files and directories inside this project folder and paste it to the root directory of your web server. Then simply setup your server to use the `public` folder as web root.

This web application features page slugs for readable URLs. To make them work, you have to get your web server to respond to every request with the index.php. Everything else then happens by itself in an ugly way.

If you are using Apache as your least distrust web server, the .htaccess file included in this project should apply all necessary configuration. For completeness, you can find its content here:

```
DirectoryIndex index.php

RewriteEngine on
RewriteBase /

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d

RewriteRule ^(.*)$ index.php [QSA]
```

If you prefer nginx as your web server, you should add the following to your /etc/nginx/sites-available/whatever.conf file:

```
location / {
    try_files $uri $uri/ /index.php?$args;
}
```
