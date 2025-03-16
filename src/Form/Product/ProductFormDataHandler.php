<?php

namespace PrestaShop\Module\Democustomfields17\Form\Product;

use PrestaShop\Module\Democustomfields17\Form\FormDataHandlerInterface;
use PrestaShop\PrestaShop\Core\Module\Exception\ModuleErrorException;
use PrestaShop\Module\Democustomfields17\Factory\ProductCustomFieldsFactory;
use Exception;

final class ProductFormDataHandler implements FormDataHandlerInterface
{
    public function save(array $data): bool{

        $idProduct = (int) $data['id_product'];
        $productCustomFields = ProductCustomFieldsFactory::create($idProduct);
        $productCustomFields->id_product = $idProduct;
        $productCustomFields->custom_label = $data['custom_label'];
        $productCustomFields->custom_quantity = $data['custom_quantity'];
        $productCustomFields->custom_unit = $data['custom_unit'];
        $productCustomFields->custom_code = $data['custom_code'];
        $productCustomFields->enabled = (bool) $data['enabled'];

        try {
            if($productCustomFields->save()){
                return true;
            }
        } catch(Exception $e){
            throw new ModuleErrorException($e->getMessage());
        }

        return true;
    }

    public function getData(array $params): array{
        $productCustomFields = ProductCustomFieldsFactory::create(
            (int)$params['id_product'],
            $params['id_lang'] ?? null,
            $params['id_shop'] ?? null
        );

        return [
            'id' => $productCustomFields->id,
            'id_product' => $productCustomFields->id_product,
            'valid' => !empty($productCustomFields->id),
            'custom_label' => $productCustomFields->custom_label,
            'custom_quantity' => $productCustomFields->custom_quantity,
            'custom_unit' => $productCustomFields->custom_unit,
            'custom_code' => $productCustomFields->custom_code,
            'enabled' => $productCustomFields->enabled,
        ];
    }
}
