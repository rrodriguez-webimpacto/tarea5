<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class Productmodule extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'productmodule';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Rafa Rodríguez';
        $this->need_instance = 1;

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Product Module');
        $this->description = $this->l('Este es un módulo para configurar la ficha de un producto');

        $this->confirmUninstall = $this->l('');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        Configuration::updateValue('PRODUCTMODULE_LIVE_MODE', false);

        include(dirname(__FILE__).'/sql/install.php');

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('displayAdminProductsMainStepLeftColumnBottom') &&
            $this->registerHook('displayProductAdditionalInfo');
    }

    public function uninstall()
    {
        Configuration::deleteByName('PRODUCTMODULE_LIVE_MODE');

        include(dirname(__FILE__).'/sql/uninstall.php');

        return parent::uninstall();
    }

    public function getContent()
    {
        $product = new Product(10);

        if(Tools::isSubmit('boton'))
        {
            $product->texto_personalizado = Tools::getValue('text');
            $product->update();
            $this->context->smarty->assign('texto', $product->texto_personalizado);
        }elseif($product->texto_personalizado != null || $product->texto_personalizado != ''){
            $this->context->smarty->assign('texto', $product->texto_personalizado);
        }else{
            $this->context->smarty->assign('texto', 'Introduce tu texto personalizado');
        }

        return $this->display(__FILE__ , 'configure.tpl');
    }

    public function hookDisplayAdminProductsMainStepLeftColumnBottom(){
        
        global $kernel;
        $requestStack = $kernel->getContainer()->get('request_stack');
        $request = $requestStack->getCurrentRequest();
        $idProduct = $request->get('id');
        $product = new Product((int)$idProduct);


        if(Tools::isSubmit('boton'))
        {
            $product->texto_personalizado = Tools::getValue('text');
            $product->update();
            $this->context->smarty->assign('texto', $product->texto_personalizado);
            var_dump($produc->texto_personalizado);
            die();
        }elseif($product->texto_personalizado != null || $product->texto_personalizado != ''){
            $this->context->smarty->assign('texto', $product->texto_personalizado);
        }else{
            $this->context->smarty->assign('texto', 'Introduce tu texto personalizado');
        }

        $this->processTextPublish();     
        return $this->display(__FILE__, 'displayAdminProductsMainStepLeftColumnBottom.tpl');
    }

    private function processTextPublish(){
        global $kernel;
        $requestStack = $kernel->getContainer()->get('request_stack');
        $request = $requestStack->getCurrentRequest();
        $idProduct = $request->get('id');
        $product = new Product((int)$idProduct);

        
        if(Tools::isSubmit('boton'))
        {
            $product->texto_personalizado = Tools::getValue('text');
            $product->update();
            $this->context->smarty->assign('texto', $product->texto_personalizado);
            var_dump($produc->texto_personalizado);
            die();
        }elseif($product->texto_personalizado != null || $product->texto_personalizado != ''){
            $this->context->smarty->assign('texto', $product->texto_personalizado);
        }else{
            $this->context->smarty->assign('texto', 'Introduce tu texto personalizado');
        }
    }

    public function hookDisplayProductAdditionalInfo(){

        if(Configuration::get('miTexto') != ''){
            $texto = Configuration::get('miTexto');
            $this->context->smarty->assign('texto', $texto);
        }
        return $this->display(__FILE__, 'displayProductAdditionalInfo.tpl');
    }

}
