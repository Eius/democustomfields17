<?php

$sql = array();

$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'ts_distproducts`';
$sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'ts_distproducts_lang`';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
