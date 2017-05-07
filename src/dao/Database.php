<?php

/**
 * Created by kaidi
 * Date: 17-4-5
 * Desc:
 */

namespace app\dao;

use \PDO;
use \PDOException;

require __DIR__ . '/../config/db.php';

class Database extends PDO
{
    /**
     * @var array Array of saved databases for reusing
     */
    protected static $instances = array();

    /**
     * Static method get
     *
     * @return database
     */
    public static function get ()
    {

        // Group information
        $type = DB_TYPE;
        $host = DB_HOST;
        $port = DB_PORT;
        $name = DB_NAME;
        $user = DB_USER;
        $pass = DB_PASS;

        // ID for database based on the group information
        $id = "$type.$host.$name.$user.$pass";

        // Checking if the same
        if (isset(self::$instances[$id])) {
            return self::$instances[$id];
        }

        try {
            $instance = new Database("$type:host=$host;port=$port;dbname=$name;charset=utf8", $user, $pass);
            $instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Setting Database into $instances to avoid duplication
            self::$instances[$id] = $instance;

            return $instance;
        } catch (PDOException $e) {
            //in the event of an error record the error to errorlog.html
            var_dump($e);
        }
    }

    public function select($sql,$array = array(), $fetchMode = PDO::FETCH_OBJ){
        $stmt = $this->prepare($sql);
        foreach($array as $key => $value){
            if(is_int($value)){
                $stmt->bindValue("$key", $value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue("$key", $value);
            }
        }
        $stmt->execute();
        return $stmt->fetchAll($fetchMode);
    }

    public function insert($table, $data, $ignore = false){
        ksort($data);
        $fieldNames = implode(',', array_keys($data));
        $fieldValues = ':'.implode(', :', array_keys($data));
        if ($ignore) {
            $stmt = $this->prepare("INSERT ignore INTO $table($fieldNames) VALUES($fieldValues)");
        } else {
            $stmt = $this->prepare("INSERT INTO $table($fieldNames) VALUES($fieldValues)");
        }
        foreach($data as $key => $value){
            $stmt->bindValue(":$key", $value);
        }
        $stmt->execute();
    }

    public function insertOnDuplicate($table, $data, $updateData){
        ksort($data);
        $fieldNames = implode(',', array_keys($data));
        $fieldValues = ':'.implode(', :', array_keys($data));

        $updateValues = array();
        foreach($updateValues as $key => $value) {
            $updateValues[] = $key . 
        }
        //$stmt = $conn->prepare('INSERT INTO customer_info (user_id, fname, lname)
        // VALUES(:user_id, :fname, :lname)
    //ON DUPLICATE KEY UPDATE fname= :fname2, lname= :lname2');
        $sql = "INSERT INTO $table($fieldNames) VALUES($fieldValues) on duplicate key update " .
            "a";
        $stmt = $this->prepare("INSERT INTO $table($fieldNames) VALUES($fieldValues)");
        foreach($data as $key => $value){
            $stmt->bindValue(":$key", $value);
        }
        $stmt->execute();
    }


    public function update($table, $data, $where){

        $this->insertOnDuplicate("aaa", array(
            "openid" => "ddd",
            "dtTime" => "dddddddd"
        ), array(
            "dtTime" => "kkkkk"
        ));

        ksort($data);
        $fieldDetails = NULL;
        foreach($data as $key => $value){
            $fieldDetails .= "$key = :$key,";
        }
        $fieldDetails = rtrim($fieldDetails, ',');
        $whereDetails = NULL;
        $i = 0;
        foreach($where as $key => $value){
            if($i == 0){
                $whereDetails .= "$key = :$key";
            } else {
                $whereDetails .= " AND $key = :$key";
            }

            $i++;}
        $whereDetails = ltrim($whereDetails, ' AND ');
        $stmt = $this->prepare("UPDATE $table SET $fieldDetails WHERE $whereDetails");
        foreach($data as $key => $value){
            $stmt->bindValue(":$key", $value);
        }
        foreach($where as $key => $value){
            $stmt->bindValue(":$key", $value);
        }
        $stmt->execute();
    }

    public function delete($table, $where, $limit = 1){
        ksort($where);
        $whereDetails = NULL;
        $i = 0;
        foreach($where as $key => $value){
            if($i == 0){
                $whereDetails .= "$key = :$key";
            } else {
                $whereDetails .= " AND $key = :$key";
            }

            $i++;
        }
        $whereDetails = ltrim($whereDetails, ' AND ');
        //if limit is a number use a limit on the query
        if(is_numeric($limit)){
            $uselimit = "LIMIT $limit";
        }
        $stmt = $this->prepare("DELETE FROM $table WHERE $whereDetails $uselimit");
        foreach($where as $key => $value){
            $stmt->bindValue(":$key", $value);
        }
        $stmt->execute();
    }
}