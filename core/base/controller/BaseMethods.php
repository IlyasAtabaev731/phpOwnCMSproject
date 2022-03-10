<?php

namespace core\base\controller;

trait BaseMethods
{
    protected function toClearStr($dirtyStr) {
        if(is_array($dirtyStr)) {
            foreach ($dirtyStr as $key => $item) {
                $clearStr[$key] = trim(strip_tags($item));
            }
        }else{
            $clearStr = trim(strip_tags($dirtyStr));
        }
        return $clearStr;
    }

    protected function toClearNum($dirtyNum): int {
        return $dirtyNum * 1;
    }

    protected function isPost(): bool {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function isAjax(): bool {
        return $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

    protected function redirect(?string $http = null, ?int $resCode = null): void {
        if($resCode) {
            $codes = ['301' => 'HTTP/1.1 301 Move Permanently'];
            if($codes[''.$resCode]) header($codes[$resCode]);
        }
        if($http) {
            $redirectUrl = $http;
        }else {
            $redirectUrl = $_SERVER['HTTP_REFERER'] ?? PATH;
        }
        header('Location: ' . $redirectUrl);
    }

    protected function writeLog(string $errorMessage, string $file = 'log.txt', string $event = 'Fault'): void {
        $dateTime = new \DateTime();
        $log = $event . ' : ' . $dateTime->format('d-m-Y G:i:s') . ' - ' . $errorMessage . "\r\n";

        file_put_contents('log/' . $file, $log, FILE_APPEND);
    }
}