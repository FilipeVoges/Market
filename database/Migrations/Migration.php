<?php

namespace Database\Migrations;

use App\Modules\Configuration\Configuration;
use App\Modules\Connection\Database;

class Migration
{

    /**
     * Run the Migrations.
     *
     * @return void
     * @throws Exception
     */
    public static function up() {
        Configuration::load();

        $db = Database::getInstance();

        self::down();

        $sql = "CREATE TABLE products (";
        $sql .= "id INT(11) AUTO_INCREMENT PRIMARY KEY,";
        $sql .= "name varchar(60) NOT NULL,";
        $sql .= "type INT(11) NOT NULL";
        $sql .= ");";
        $db->getQuery($sql);

        $sql = "CREATE TABLE types (";
        $sql .= "id INT(11) AUTO_INCREMENT PRIMARY KEY,";
        $sql .= "name varchar(60) NOT NULL";
        $sql .= ");";
        $db->getQuery($sql);

        $sql = "CREATE TABLE taxes (";
        $sql .= "id INT(11) AUTO_INCREMENT PRIMARY KEY,";
        $sql .= "name varchar(60) NOT NULL";
        $sql .= ");";
        $db->getQuery($sql);

        $sql = "CREATE TABLE sales (";
        $sql .= "id INT(11) AUTO_INCREMENT PRIMARY KEY,";
        $sql .= "productId INT(11) NOT NULL,";
        $sql .= "amount INT(11) NOT NULL";
        $sql .= ");";
        $db->getQuery($sql);
    }

    /**
     * Reverse the Migrations.
     *
     * @return void
     * @throws Exception
     */
    public static function down() {
        Configuration::load();

        $db = Database::getInstance();

        $sql = "DROP TABLE IF EXISTS products";
        $db->getQuery($sql);

        $sql = "DROP TABLE IF EXISTS types";
        $db->getQuery($sql);

        $sql = "DROP TABLE IF EXISTS taxes";
        $db->getQuery($sql);

        $sql = "DROP TABLE IF EXISTS sales";
        $db->getQuery($sql);
    }
}