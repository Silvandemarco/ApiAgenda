<?php
// If you installed via composer, just use this code to requrie autoloader on the top of your projects.
//require __DIR__ . '/../vendor/autoload.php';
 
// Using Medoo namespace
use Medoo\Medoo;
 
$database = new Medoo([
	// required
	'database_type' => 'mysql',
	'database_name' => 'appagenda',
	'server' => 'localhost',
	'username' => 'root',
	'password' => '',
 
	// [optional]
	'charset' => 'utf8',
	'port' => 3306,
 
	// [optional] Table prefix
	//'prefix' => 'PREFIX_',
 
	// [optional] Enable logging (Logging is disabled by default for better performance)
	//'logging' => true,
 
	// [optional] MySQL socket (shouldn't be used with server and port)
	//'socket' => '/tmp/mysql.sock',
 
	// [optional] driver_option for connection, read more from http://www.php.net/manual/en/pdo.setattribute.php
	'option' => [
		PDO::ATTR_CASE => PDO::CASE_NATURAL
	],
 
	// [optional] Medoo will execute those commands after connected to the database for initialization
	'command' => [
		'SET SQL_MODE=ANSI_QUOTES'
	]
]);