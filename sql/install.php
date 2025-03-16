<?php

$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'ts_distproducts` (
    `id_distproducts` int(11) NOT NULL AUTO_INCREMENT,
    `id_product` int(11) unsigned NOT NULL,
    `custom_quantity` int(11) unsigned NOT NULL DEFAULT "1",
    `custom_unit` text NOT NULL DEFAULT "kg",
    `custom_code` text DEFAULT NULL,
    `enabled` tinyint(1) unsigned NOT NULL DEFAULT "0",
    `date_add` datetime NOT NULL,
    `date_upd` datetime NOT NULL,
    PRIMARY KEY  (`id_distproducts`),
    KEY `id_product` (`id_product`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'ts_distproducts_lang` (
    `id_distproducts` int(11) unsigned NOT NULL,
    `id_shop` INT(11) UNSIGNED NOT NULL DEFAULT "1",
    `id_lang` int(11) unsigned NOT NULL ,
    `custom_label` text NOT NULL DEFAULT "Merná jednotka",
    PRIMARY KEY  (`id_distproducts`, `id_shop`, `id_lang`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
