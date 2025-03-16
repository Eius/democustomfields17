<?php

namespace PrestaShop\Module\Democustomfields17\Form\Helpers;

final class TsLogger {
    public static function log(string|array|object $message, $append = true) {
        $logFile = _MODULE_DIR_ . 'tsdistproducts/log.txt';
        if (is_array($message) || is_object($message)) {
            $message = print_r($message, true);
        }
        file_put_contents($logFile, $message.PHP_EOL, $append ? FILE_APPEND : 0);
    }
}