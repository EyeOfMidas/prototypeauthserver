prototypeauthserver
=======
Here is a quick, drop-in authenticated PHP server with CRUD/REST communication for quick prototyping. The only setup you must do aside from putting the code on a PHP-enabled webserver is to create a **data** directory with write-access for the webserver user (typically *www-data*). The script will autogenerate a default authentication user (*admin* / *password*) with access to the *data.json* file.

There is a help.php file that includes the following information:
 * **HELP** - This help file: [/?help](/?help)
 * **LOG IN** - [/?username=[some username]&password=[some password]](/?username=admin&password=password")
 * **LOG OUT** - [/?logout](/?logout)
 * **GET** - [/?method=GET](/?method=get)
 * **GET** - [/?method=GET&key=[some key]](/?method=get&key=key)
 * **POST** - [/?method=POST&key=[some key]&value=[some value]](/?method=post&key=key&value=value)
 * **DELETE** - [/?method=DELETE&key=[some key]](/?method=delete&key=key)

#### NOTE: This is by no means a secure implementation. It is *only* for prototyping or private use.
