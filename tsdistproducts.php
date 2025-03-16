<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

use PrestaShop\Module\Democustomfields17\Form\Product\Hooks\HookFieldsBuilderInterface;
use PrestaShop\Module\Democustomfields17\Form\Product\Hooks\HookFieldsBuilderFinder;
use PrestaShop\Module\Democustomfields17\Form\Product\Democustomfields17AdminForm;
use PrestaShop\Module\Democustomfields17\Form\Product\ProductFormDataHandler;
use PrestaShop\Module\Democustomfields17\Form\Helpers\TsLogger;

class TsDistProducts extends Module
{
    private $productFormDataHandler;

    public function __construct()
    {
        $this->name = 'tsdistproducts';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Marek Žáčik';
        $this->need_instance = 1;

        parent::__construct();

        $this->displayName = $this->l('Produkty pre distribútorov');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall my module?');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->productFormDataHandler = new ProductFormDataHandler();
    }

    public function isUsingNewTranslationSystem()
    {
        return true;
    }

    public function install()
    {
        include(dirname(__FILE__) . '/sql/install.php');

        return parent::install() && $this->registerHook($this->getHooks());
    }

    public function uninstall()
    {
        include(dirname(__FILE__) . '/sql/uninstall.php');

        return parent::uninstall();
    }

    public function __call($hookName, $params)
    {
        $hookFieldsBuilder = (new HookFieldsBuilderFinder())->find($hookName);

        if (null != $hookFieldsBuilder) {
            return $this->displayProductAdminHookFields($hookFieldsBuilder, $params);
        }
    }

    public function hookActionGetProductPropertiesAfter($params)
    {
        $productCustomsData = $this->productFormDataHandler->getData(
            [
                'id_product' => (int)$params['product']['id_product'],
                'id_lang' => $this->context->language->id,
                'id_shop' => $this->context->shop->id
            ]
        );

        $params['product'][$this->name] = $productCustomsData;
    }

    public function hookActionAdminProductsControllerSaveAfter($params)
    {
        $data = Tools::getValue($this->name);

        if (!is_array($data) || !isset($data[$this->getModuleFormDatasID()])) { // Make sure data come from this form
            return;
        }

        if (!isset($data['id_product'])) {
            $data['id_product'] = (int)Tools::getValue('id_product');
        }

        $this->productFormDataHandler->save($data);
    }

    private function getProductAdminHookFieldsDefinition(HookFieldsBuilderInterface $hookFieldsBuilder, array $data)
    {
        $formFactory = $this->get('form.factory');
        $options = [
            'csrf_protection' => false,
            'hookFieldsBuilder' => $hookFieldsBuilder,
            'module' => $this,
        ];

        return $formFactory->createNamed($this->name, Democustomfields17AdminForm::class, $data, $options);
    }

    private function displayProductAdminHookFields(HookFieldsBuilderInterface $hookFieldsBuilder, array $params)
    {
        if (!isset($params['id_product'])) {
            $requestStack = $this->get('request_stack');
            $request = $requestStack->getCurrentRequest();
            $params['id_product'] = (int)$request->attributes->get('id');
        }

        $productFieldsData = $this->productFormDataHandler->getData($params);
        $form = $this->getProductAdminHookFieldsDefinition($hookFieldsBuilder, $productFieldsData);

        return $this->get('twig')
            ->render('@PrestaShop/' . $this->name . '/admin/product/customfields.html.twig', [
                'form' => $form->createView(),
            ]);
    }

    public function hookDisplayAdminOrderMain($params)
    {
        $id_order = (int)$params['id_order'];

        $this->context->smarty->assign([
            'customData' => "TEST!!!!!!!!!!!!!!!!!!!!!!!!!!!",
        ]);

        return $this->display(__FILE__, 'views/templates/admin/order_custom_data.tpl');
    }

    public function hookActionEmailAddAfterContent($params) {
       TsLogger::log($params);
    }

    public function hookActionEmailAddBeforeContent($params) {
        TsLogger::log($params);
    }

    public function getHooks()
    {
        // @see https://devdocs.prestashop.com/1.7/modules/concepts/hooks/list-of-hooks/#full-list
        return [
            'displayAdminProductsExtra',
            'displayAdminProductsMainStepLeftColumnMiddle',
            'displayAdminProductsMainStepLeftColumnBottom',
            'displayAdminProductsMainStepRightColumnBottom',
            'displayAdminProductsQuantitiesStepBottom',
            'displayAdminProductsPriceStepBottom',
            'displayAdminProductsOptionsStepTop',
            'displayAdminProductsOptionsStepBottom',
            'displayAdminProductsSeoStepBottom',
            'displayAdminOrderMain',
            'actionAdminProductsControllerSaveAfter',
            'actionObjectProductDeleteAfter',
            'actionGetProductPropertiesAfter',
            'actionEmailAddAfterContent',
            'actionEmailAddBeforeContent',
        ];
    }

    public function getLocales()
    {
        return $this->get('prestashop.adapter.data_provider.language')->getLanguages();
    }

    public function getModuleFormDatasID()
    {
        return 'fields_from_' . $this->name . '_' . $this->id;
    }
}
