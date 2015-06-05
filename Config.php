<?php

/**
 * @author Rolando Arriaza <rolignu90@gmail.com>
 * @copyright (c) 2015, Rolignu
 * @version 1.5
 * @access public
 * 
 * SCRIPT DE CONFIGURACION DEL SISTEMA 
 * 
 * -CONFIGURACION PARA LAS BASES DEE DATOS:
 * 
 *          -MYSQL
 *          -SQLITE
 *          -ORACLE
 *          
 * -DIRECTORIOS
 * -ENMASCARAMIENTO .htaccess
 */




$CONFIG__ = array(
    
    "DB_CONFIG" => [
        "driver"                    => "mysql",
        "database"                  => "testdb",
        "user"                      => "root",
        "password"                  => "",
        "host"                      => "localhost",
        "port"                      => "3306",
        "prefix"                    => NULL,
        "cacheMetadata"             => FALSE,
        'encoding'                  => 'utf8',                         
	'timezone'                  => 'UTC',
        "sqlite_db"                 => ''
    ]


);







