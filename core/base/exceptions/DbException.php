<?php

namespace core\base\exceptions;

use core\base\controller\BaseMethods;

class DbException extends \PDOException
{
    use BaseMethods;

    protected $messages;

    public function __construct($message = '', $code = 0)
    {
        parent::__construct($message, $code);

        $this->messages = include 'messages.php';

        $error = $this->getMessage();
        $error .= "\r\n" . 'file ' . $this->getFile() . "\r\n" . 'In line ' . $this->getLine() . "\r\n";

        $this->writeLog($error, 'db_log.txt');
    }
}