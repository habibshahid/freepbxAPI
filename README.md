## Setup
$ composer install  

## Endpoints
### api/v1
**Check if user is logged in, if so returns username and token**  
GET: http://localhost/app/api/v1  

**Attempt to login, currently simply checks if username is drum**  
POST: http://localhost/app/api/v1
```json
{
  "username": "drum",
  "password": "password"
}
```

**Logout**  
DELETE: http://localhost/app/api/v1

### Get all Extension (Requires auth)
**Return all**  
GET: http://localhost/app/api/v1/sipExtensions
POST: http://localhost/app/api/v1/sipExtension
```json
{
  "extension": "1000",
  "secret": "{strong password}",
  "displayname": "John Doe",
  "devicetype": "sip"
}
```

## Configuration
**Changes you may want to make**  
Session name: config/bootstrap.php  
CORS Origins: config/middleware.php  
DB Connection: config/settings.php

