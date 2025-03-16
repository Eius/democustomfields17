<?php

namespace PrestaShop\Module\Democustomfields17\Form\Product\Hooks;

use Context;
use PrestaShop\PrestaShop\Adapter\Entity\Combination;
use PrestaShop\PrestaShop\Adapter\Entity\PrestaShopLogger;
use Product;
use Symfony\Component\Debug\Debug;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use PrestaShopBundle\Form\Admin\Type\TranslatableType;
use PrestaShopBundle\Form\Admin\Type\SwitchType;
use Module;
use Tools;

class HookDisplayAdminProductsExtraFieldsBuilder implements HookFieldsBuilderInterface
{
    public function addFields(FormBuilderInterface $adminFormBuilder, Module $module): FormBuilderInterface
    {
        $langId = Context::getContext()->language->id;
        $requestUri = $_SERVER['REQUEST_URI'];
        $uriParts = explode('/', rtrim($requestUri, '/'));
        $productId = (int)end($uriParts);
        $product = new Product($productId);

        $adminFormBuilder
            ->add('custom_label', TranslatableType::class, [
                'label' => $module->l('Názov'),
                'type' => TextType::class,
                'locales' => $module->getLocales()
            ])
            ->add('custom_quantity', IntegerType::class, [
                'label' => $module->l('Množstvo'),
                'attr' => [
                    'class' => 'my-custom-class',
                    'data-hex' => 'true'
                ]
            ])
            ->add('custom_unit', TextType::class, [
                'label' => $module->l('Merná jednotka'),
                'attr' => [
                    'class' => 'my-custom-class',
                    'data-hex' => 'true'
                ]
            ])
            ->add('enabled', SwitchType::class, [
                'label' => $module->l('Aktívne')
            ]);

        if ($product->hasCombinations()) {
            $combinations = $product->getAttributesResume($langId);
            foreach ($combinations as $combination) {
                $adminFormBuilder->add("id_product_attribute_" . $combination['id_product_attribute'],
                    TextType::class, [
                    'label' => $combination['attribute_designation'],
                    'attr' => [
                        'class' => 'my-custom-class',
                        'data-hex' => 'true'
                    ]
                ]);
            }
        } else {
            $adminFormBuilder->add('custom_code', TextType::class, [
                'label' => $module->l('Kód'),
                'attr' => [
                    'class' => 'my-custom-class',
                    'data-hex' => 'true'
                ]
            ]);
        }

        return $adminFormBuilder;
    }
}
