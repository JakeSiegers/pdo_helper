# pdo_mysql_helper
A PHP pdo_mysql helper class to make it easier to use.

The goal of this library is to keep PDO mysql simple and easy to use, while making it easier to port your older mysql code. You'll find this libary has a very similar api, so if you are used to old-school mysql programming in php, learning this new library will be a breeze.

This library also supports parameterized queries (something not supported by the class php mysql library), which you **should always use**  to prevent SQL Injection when accepting data from the user.

# Usage
```
$dbc = new pdo_helper();
```
[More Documentation coming very shortly]
