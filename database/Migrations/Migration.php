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
        $sql .= "price float(10,2) NOT NULL,";
        $sql .= "type INT(11) NOT NULL";
        $sql .= ");";
        $db->getQuery($sql);

        $sql = "CREATE TABLE types (";
        $sql .= "id INT(11) AUTO_INCREMENT PRIMARY KEY,";
        $sql .= "name varchar(60) NOT NULL";
        $sql .= ");";
        $db->getQuery($sql);

        $sql = "CREATE TABLE rates (";
        $sql .= "id INT(11) AUTO_INCREMENT PRIMARY KEY,";
        $sql .= "name varchar(60) NOT NULL,";
        $sql .= "rate int(11) NOT NULL";
        $sql .= ");";
        $db->getQuery($sql);

        $sql = "CREATE TABLE type_rate (";
        $sql .= "id INT(11) AUTO_INCREMENT PRIMARY KEY,";
        $sql .= "type int(11) NOT NULL,";
        $sql .= "rate int(11) NOT NULL";
        $sql .= ");";
        $db->getQuery($sql);

        $sql = "CREATE TABLE sales (";
        $sql .= "id INT(11) AUTO_INCREMENT PRIMARY KEY,";
        $sql .= "status INT(11) NOT NULL";
        $sql .= ");";
        $db->getQuery($sql);

        $sql = "CREATE TABLE product_sale (";
        $sql .= "id INT(11) AUTO_INCREMENT PRIMARY KEY,";
        $sql .= "product int(11) NOT NULL,";
        $sql .= "sale int(11) NOT NULL,";
        $sql .= "amount int(11) NOT NULL";
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

        $sql = "DROP TABLE IF EXISTS rates";
        $db->getQuery($sql);

        $sql = "DROP TABLE IF EXISTS sales";
        $db->getQuery($sql);

        $sql = "DROP TABLE IF EXISTS type_rate";
        $db->getQuery($sql);

        $sql = "DROP TABLE IF EXISTS product_sale";
        $db->getQuery($sql);
    }
}