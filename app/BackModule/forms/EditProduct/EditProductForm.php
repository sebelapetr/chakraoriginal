<?php

namespace Skeleton\BackModule\Forms;

use Skeleton\Model\ProductService;
use Skeleton\Model\EditParentCategoryService;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Skeleton\Model\AddSerialService;
use Tracy\Debugger;

interface IEditProductFormFactory{
    /** @return EditProductForm */
    function create($id);
}

class EditProductForm extends Control{

    /** @var ProductService */
    public $productService;

    /** @var int */
    public $productId;


    public function __construct(ProductService $productService, $id)
    {

        $this->productService = $productService;
        $this->productId = $id;
    }

    protected function createComponentEditProductForm(){
        $form = new Form();
        $form->addHidden('id')
            ->setDefaultValue($values = $this->productService->getDefaultValues($this->productId, 'id'));
        $form->addText("name")
            ->setDefaultValue($values = $this->productService->getDefaultValues($this->productId, 'productName'))
            ->setRequired();
        $form->addText('stock')
            ->setDefaultValue($values = $this->productService->getDefaultValues($this->productId, 'stockLevel'));
        $form->addText('order')
            ->setDefaultValue($values = $this->productService->getDefaultValues($this->productId, 'orderValue'));
        $form->addText("code")
            ->setDefaultValue($values = $this->productService->getDefaultValues($this->productId, 'code'));;
        $form->addText("price")
            ->setDefaultValue($values = $this->productService->getDefaultValues($this->productId, 'catalogPriceVat'));
        $form->addUpload("image")
            ->setDefaultValue($values = $this->productService->getDefaultValues($this->productId, 'image'));
        $form->addTextArea("description")
            ->setDefaultValue($values = $this->productService->getDefaultValues($this->productId, 'description'));
        $form->addSelect("visible", '', ['1'=>'Ano', '0'=>'Ne']);
        $form->addSelect("category", '', $this->productService->getCategories())
            ->setDisabled(!$this->productService->categoriesExists());
        $form->addSubmit("submit");
        $form->onSuccess[] = [$this, 'editProductFormSucceeded'];

        return $form;
    }
    public function editProductFormSucceeded(Form $form, $values){
        $this->productService->editProduct($values);

        if ($values->image) {
            if ($values->image != $this->productService->getDefaultValues($this->productId, 'image')) {
                if ((filesize($values->image) > 0) and $values->image->isImage()) {
                    $soubor = $values->image;
                    $soubor->move("img/" . $values->image->name);
                }
            }
        }
        $this->getPresenter()->flashMessage("Produkt byl úspěšně upraven.");
        $this->getPresenter()->redirect("Product:default");
    }
    public function render(){
        $this->getTemplate()->setFile(__DIR__  .  "/../../forms/EditProduct/EditProduct.latte");
        $this->getTemplate()->render();
    }

}