## Install Composer (Cent OS)
$ sudo curl -sS https://getcomposer.org/installer | php

$ mv composer.phar /usr/local/bin/composer

## Setup
$ cd /var/www/html/

$ git clone https://github.com/habibshahid/freepbxAPI.git

$ cd freepbxAPI

$ composer install  

## Endpoints
### api/v1
**Check if user is logged in, if so returns username and token**  
GET: http://localhost/freepbxAPI/auth

**Attempt to login, currently simply checks if username is drum**  
POST: http://localhost/freepbxAPI/auth
```json
{
  "username": "drum",
  "password": "password"
}
```

**Logout**  
DELETE: http://localhost/freepbxAPI/auth

### SIP Extensions (Requires auth)
GET: http://localhost/freepbxAPI/api/v1/sipExtensions
```Returns all extensions```  

POST: http://localhost/freepbxAPI/api/v1/sipExtension
```json
{
  "extension": "1000",
  "secret": "{strong password}",
  "displayname": "John Doe",
  "devicetype": "sip",
  "create_user": "1"
}
```

## Configuration
**Changes you may want to make**  
Session name: config/bootstrap.php  
CORS Origins: config/middleware.php  
DB Connection: config/settings.php
SQL Lite Connection: config/settings.php

