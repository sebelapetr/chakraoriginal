<?php

namespace Skeleton\BackModule\Presenters;

use Skeleton\BackModule\Forms\IEditProductFormFactory;
use Skeleton\BackModule\Forms\IProductsListFormFactory;
use Skeleton\BackModule\Forms\IAddProductFormFactory;
use Skeleton\Model\Orm;
use Skeleton\Model\Product;
use Tracy\Debugger;

Class ProductPresenter extends BasePresenter{


    /** @var IEditProductFormFactory */
    public  $editProductFormFactory;

    /** @var IProductsListFormFactory */
    public  $productsListFormFactory;

    /** @var IAddProductFormFactory */
    public  $addProductFormFactory;

    /** @var int */
    public $productId;


    public function __construct(Orm $orm, IEditProductFormFactory $editProductFormFactory, IProductsListFormFactory $productsListFormFactory, IAddProductFormFactory $addProductFormFactory)
    {
        parent::__construct($orm);
        $this->editProductFormFactory = $editProductFormFactory;
        $this->productsListFormFactory = $productsListFormFactory;
        $this->addProductFormFactory = $addProductFormFactory;
    }

    public function renderDefault()
    {
        $this->getTemplate()->setFile(__DIR__ . "/../templates/Product/default.latte");
        $this->getTemplate()->products = $this->orm->products->findAll();
    }

    public function renderEdit($id){
        $this->getTemplate()->product = $this->orm->products->getBy(["id"=>$id]);
        $this->productId = $id;
    }

    public function renderList()
    {
        $this->getTemplate()->setFile(__DIR__ . "/../templates/Product/list.latte");
        $this->getTemplate()->products = $this->orm->products->findAll();
    }

    public function renderAdd(){
        $this->getTemplate()->setFile(__DIR__ . "/../templates/Product/add.latte");
    }

    public function createComponentEditProductForm()
    {
        return $this->editProductFormFactory->create($this->productId);
    }

    public function createComponentProductsListForm()
    {
        return $this->productsListFormFactory->create();
    }

    public function createComponentAddProductForm()
    {
        return $this->addProductFormFactory->create();
    }

    public function handleDelete($id){
        $product = $this->orm->products->getBy(['id'=>$id]);
        $this->orm->remove($product);
        $this->orm->flush();
        $this->flashMessage("Produkt byl úspěšně smazán");
    }

    public function actionUploadProducts() {
        $first = true;
        if (($handle = fopen(__DIR__."/../../../www/catalog_products.csv", "r")) !== FALSE) {

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if (!$first) {
                    $product = new Product();
                    $product->productName = $data[2];
                    $product->catalogPrice = $data[8];
                    $product->vat = 0;
                    $product->catalogPriceVat = $data[8];
                    $product->stockLevel = $data[13];
                    $product->description = $data[34]. ' <br> '. $data[36] . ' <br> ' . $data[38];
                    $product->active = ($data[10] === 'true' ? 1 : 0);
                    $product->code = $data[6];
                    $this->orm->persistAndFlush($product);
                    Debugger::barDump($data);
                } else {
                    $first = false;
                }
            }
            fclose($handle);
        }
    }

}