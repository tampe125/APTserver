# APTserver
A proof of concept Advanced Persistence Threat written using Python and Laravel.
For more info about this project, please take a look at this blog post: https://www.nc-lp.com/blog/build-a-custom-apt-module

This is the server side, you can find the code for the client part [in this repository](https://github.com/tampe125/APTpy).

## Installation
Simply clone this repository and run `composer install` to install all dependecies.

## Configuration
Copy the file `.env.example` and rename it to `.env`. You have to supply the connection details to your database.  
Then install the database with:  
```
php artisan migrate
```
Finally, install some sample data with:  
```
php artisan db:seed
```

