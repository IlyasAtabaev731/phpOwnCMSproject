<?php

namespace core\base\model;

use core\base\controller\Singleton;
use core\base\exceptions\DbException;
use PDO;
use PDOException;

class BaseModel
{
    use Singleton;

    protected PDO $database;

    private function __construct() {
        $dsn = 'mysql:host='. HOST .';dbname='. DB_NAME .';charset=utf8';
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_STRINGIFY_FETCHES => false,
            PDO::ATTR_EMULATE_PREPARES => false
        ];
        try {
            $this->database = new PDO($dsn, USER, PASS, $options);
        }catch (PDOException $e) {
            throw new DbException('Ошибка подключения к базе данных: ' . $e->getCode() . ' ' . $e->getMessage());
        }
    }

    final public function query($query, $crud = 'r', $return_id = false) {
        try {
            $statement = $this->database->query($query);
        }catch (PDOException $e) {
            throw new DbException('Ошибка в SQL запросе :' . $query . '. Ответ от базы данных ' . $e->getMessage());
        }

        switch($crud) {
            case 'r':
                if($statement->rowCount()) {
                    $res = [];
                    for ($i = 0; $i < $statement->rowCount(); $i++) {
                        $res[] = $statement->fetch(PDO::FETCH_ASSOC);
                    }
                    return $res;
                }
                return false;
                break;
            case 'c':
                if ($return_id) return $this->database->lastInsertId();
                return true;
                break;
            default:
                return true;
                break;
        }
    }
}