# Vote-collector

### Starting the project

For installing Laravel, please refer to [Official Laravel installation
guide](http://laravel.com/docs/5.0).

### Installing dependencies (assuming apache as web server and mysql as db):

In a nutchell (assuming debian-based OS), first install the dependencies needed:

Note: php5 package installs apache2 as a dependency so we have no need to add
it manually.

`% sudo aptitude install php5 php5-cli mcrypt php5-mcrypt mysql-server php5-mysql`

Install composer according to official instructions (link above) and move binary to ~/bin:

`% curl -sS https://getcomposer.org/installer | php5 && mv composer.phar ~/bin`

Download Laravel installer via composer:

`% composer global require "laravel/installer=~1.1"`

And add ~/.composer/vendor/bin to your $PATH. Example:

```
% cat ~/.profile
[..snip..]
LARAVEL=/home/username/.composer/vendor
PATH=$PATH:$LARAVEL/bin
```

And source your .profile with `% source ~/.profile`

After cloning the project with a simple `git clone https://github.com/scify/Vote-collector.git`, type `cd Vote-collector && composer install` to install all dependencies.

### Apache configuration:

```
% cat /etc/apache2/sites-available/mysite.conf
<VirtualHost *:80>
	ServerName myapp.localhost.com
	DocumentRoot "/path/to/Vote-collector/public"
	<Directory "/path/to/Vote-collector/public">
		AllowOverride all
	</Directory>
</VirtualHost>
```

Make the symbolic link:

`% cd /etc/apache2/sites-enabled && sudo ln -s ../sites-available/mysite.conf`

Enable mod_rewrite and restart apache:

`% sudo a2enmod rewrite && sudo service apache2 restart`

Fix permissions for storage directory (web server must have write permissions. DO NOT use 777):

`% chown -R :www-data /path/to/Vote-collector/storage && chmod -R 775 /path/to/Vote-collector/storage`

Test your setup with:

`% php artisan serve`

and navigate to localhost:8000.


### Nginx configuration:

Add additional the additional dependencies needed:

`% sudo aptitude install nginx php5-fpm`

Disable cgi.fix_pathinfo at /etc/php5/fpm/php.ini: `cgi.fix_pathinfo=0`

`% sudo php5enmod mcrypt && sudo service php5-fpm restart`

Nginx server block:

```
server {
    listen 80 default_server;
    listen [::]:80 default_server ipv6only=on;

    root /path/to/Vote-collector/public;
    index index.php index.html index.htm;

    server_name server_domain_or_IP;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        try_files $uri /index.php =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass unix:/var/run/php5-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

`% sudo service nginx restart && sudo chmod -R 755 path/to/Vote-collector/storage`

And finally, set the group appropriately:

`% sudo chown -R www-data:www-data storage`

### Initialize database:

Initialize the database with `php artisan migrate` and test the installation with `php artisan serve` and hit `localhost:8000/auth/register` at your browser of choice.

After running migrations, it's time to seed the database.

Navigate to the root directory and run `php artisan db:seed`. This seeds the database tables (code in /path/to/Vote-collector/database/seeds/).

Verify login credentials by navigating at http://localhost/auth/login

### Mail setup

Manual intervention required in this. You'll have to edit Vote-collector/config/mail.php and set the global "From" Address from:

```
'from' => ['address' => null, 'name' => null],
```

to

```
'from' => ['address' => 'myaddress@example.com', 'name' => 'My Name'],
```

Configure your .env file appropriately:

```
MAIL_DRIVER=smtp
MAIL_HOST=your.address
MAIL_PORT=port_num
MAIL_USERNAME=username
MAIL_PASSWORD=password
```

Example for relay mail through Gmail:

```
# inside config/mail.php
'from' => ['address' => 'myname@gmail.com', 'name' => 'My Name'],
```

```
# inside .env
MAIL_DRIVER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=myname@gmail.com
MAIL_PASSWORD=mypass
```

### Command scheduling

If you need to make a background job, you need to put the following in your crontab (edit with `crontab -e`.
Something like
```
% crontab -l
0 1 * * * php /path/to/artisan schedule:run 1>> /dev/null 2>&1
```
will suffice.
