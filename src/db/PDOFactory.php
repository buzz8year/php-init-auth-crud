<?php

namespace db;

use PDO;

class PDOFactory
{
    protected static $pdo_written_instance;
    protected static $pdo_red_instance;

    public static function readInstance(): PDO
    {
        return self::writeInstance();
    }

    public static function writeInstance(): PDO
    {
        // NOTE: Condition is required for unit testing
        if (!isset(self::$pdo_written_instance)) 
            self::createPDO();

        return self::$pdo_written_instance;
    }

    public static function createPDO(): void
    {
        $dsn = sprintf("mysql:host=%s;port=%u;dbname=%s;charset=UTF8", MYSQL_HOST, MYSQL_PORT, MYSQL_DB);
        
        self::$pdo_written_instance = new PDO($dsn, MYSQL_USER, MYSQL_PASS, [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'set names utf8',
            PDO::ATTR_EMULATE_PREPARES => false
        ]);
        
        self::$pdo_written_instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
}