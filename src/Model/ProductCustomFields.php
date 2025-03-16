<?php

namespace PrestaShop\Module\Democustomfields17\Model;

use ObjectModel;
use DbQuery;
use Db;

class ProductCustomFields extends ObjectModel {
    /** @var int ID */
    public $id;

    /** @var int product ID */
    public $id_product;

    /** @var string  */
    public $custom_label;

    /** @var int Custom quantity */
    public $custom_quantity;

    /** @var string  */
    public $custom_unit;

    /** @var string */
    public $custom_code;

    /** @var bool  */
    public $enabled;

    /** @var string Object creation date */
    public $date_add;

    /** @var string Object last modification date */
    public $date_upd;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = [
        'table' => 'ts_distproducts',
        'primary' => 'id_distproducts',
        'multilang' => true,
        'multilang_shop' => true,
        'fields' => [
            'id_product' => [
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedId'
            ],
            'enabled' => [
                'type' => self::TYPE_BOOL,
            ],
            'custom_label' => [
                'type' => self::TYPE_STRING,
                'lang' => true,
                'shop' => true,
            ],
            'custom_quantity' => [
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt'
            ],
            'custom_unit' => [
                'type' => self::TYPE_STRING,
            ],
            'custom_code' => [
                'type' => self::TYPE_STRING,
            ],
            'date_add' => [
                'type' => self::TYPE_DATE,
                'validate' => 'isDate'
            ],
            'date_upd' => [
                'type' => self::TYPE_DATE,
                'validate' => 'isDate'
            ],
        ],
    ];

    public static function getInstanceByProductId(
        int $idProduct,
        ?int $idLang = null,
        ?int $idShop = null
    ) : self
    {
        $sql = new DbQuery();
        $sql->select(self::$definition['primary']);
        $sql->from(self::$definition['table'], 'b');
        $sql->where('b.id_product = '.(int)$idProduct);

        $idObject = (int)Db::getInstance()->getValue($sql);

        return (new self($idObject, $idLang, $idShop));
    }
}