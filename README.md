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

### Register the clients
```  
Table: clients
+----+-----------------+---------------+---------------+----------------------------------+
| id | client_id       | priv_key      | pub_key       | aes_key                          |
+----+-----------------+---------------+---------------+----------------------------------+
|  1 | WD-WCC3F7XK9Y9P | -----BEGIN... | ssh-rsa AA... |                                  |
|  2 | S1D8VCGV        | -----BEGIN... | ssh-rsa AA... | Y2YyMWNmZmQ0OWUwMGVkMTJhZThiYmFm |
+----+-----------------+---------------+---------------+----------------------------------+
```
For each client you have to store the client id (currently it's HDD serial number, but you can hardcode it) and their public/private key. Remember to update the client with the correct values!

### Issue commands
```
Table: commands
+----+-----------------+---------+-----------------+---------------------+---------------------+
| id | client_id       | command | module          | created_at          | sent_at             |
+----+-----------------+---------+-----------------+---------------------+---------------------+
|  1 | S1D8VCGV        | ls -la  | ShellModule     | 2017-03-09 11:28:59 | 0000-00-00 00:00:00 |
|  2 | WD-WCC3F7XK9Y9P | dir     | ShellModule     | 2017-03-09 11:28:59 | 0000-00-00 00:00:00 |
|  3 | WD-WCC3F7XK9Y9P | start   | KeyloggerModule | 2017-03-09 11:28:59 | 0000-00-00 00:00:00 |
+----+-----------------+---------+-----------------+---------------------+---------------------+
```
Issue commands to each client by entering a record in the `commands` table.  
Available modules:

1. **ShellModule** Issue shell commands on the remote host.
2. **KeyloggerModule** Only the `start` command is available, which starts the module until the computer is powered off.

### Read the reply
```
Table: responses
*************************** 1. row ***************************
        id: 3
 client_id: S1D8VCGV
    module: ShellModule
command_id: 1
  response: total 224
drwxr-xr-x  12 tampe125  staff    408  9 Mar 12:36 .
drwxr-xr-x  83 tampe125  staff   2822  6 Mar 11:07 ..
drwxr-xr-x  17 tampe125  staff    578  9 Mar 12:36 .git
-rw-r--r--   1 tampe125  staff     47 30 Gen 09:33 .gitignore
drwxr-xr-x   8 tampe125  staff    272  9 Mar 12:36 .idea
-rw-r--r--   1 tampe125  staff  35141 13 Gen 12:04 LICENSE
-rw-r--r--   1 tampe125  staff      7 13 Gen 12:04 README.md
-rw-r--r--   1 tampe125  staff  52124  9 Mar 12:36 aptpy.log
-rw-r--r--   1 tampe125  staff   5229 22 Feb 10:14 aptpy.py
-rw-r--r--   1 tampe125  staff   4096  9 Mar 12:36 aptpy.queue
drwxr-xr-x  10 tampe125  staff    340  9 Mar 12:12 lib
-rw-r--r--   1 tampe125  staff    319 21 Feb 11:58 settings.json
created_at: 2017-03-09 11:37:42
```
The raw reply is stored inside the `responses` table. You can get it by directly querying the database.

At the moment there's no application logic for database interaction, you have to issue your queries manually directly on the database.

## Required changes
Most of the values are inside the configuration file, however some files need to be changed before getting the server ready.

### Change public and private keys
Open [apt.php](https://github.com/tampe125/APTserver/blob/master/config/apt.php) file and change the public and private server. **Remember to update the hardcoded value in the client, too!**
