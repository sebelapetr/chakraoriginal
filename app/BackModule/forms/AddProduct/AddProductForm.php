<?php

namespace Skeleton\BackModule\Forms;

use Skeleton\Model\ProductService;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Tracy\Debugger;

interface IAddProductFormFactory{
    /** @return AddProductForm */
    function create();
}

class AddProductForm extends Control{

    /** @var ProductService */
    public $productService;

    public function __construct(ProductService $productService)
    {

        $this->productService = $productService;
    }

    protected function createComponentAddProductForm(){
        $form = new Form();
        $form->addText("name")
            ->setRequired();
        $form->addText('stock');
        $form->addText('order');
        $form->addText("code");
        $form->addText("price");
        $form->addUpload("image")
            ->setRequired();
        $form->addTextArea("description");
        $form->addSelect("visible", '', ['1'=>'Ano', '0'=>'Ne']);
        $form->addSelect("category", '', $this->productService->getCategories())
            ->setDisabled(!$this->productService->categoriesExists());
        $form->addSubmit("submit");
        $form->onSuccess[] = [$this, 'addProductFormSucceeded'];
        return $form;
    }
    public function addProductFormSucceeded(Form $form, $values){
        $this->productService->addProduct($values);

        if ((filesize ($values->image) > 0) and $values->image->isImage()) {
            $soubor = $values->image;
            $soubor->move("img/produkty/" . $values->image->name);
        }
        $this->getPresenter()->flashMessage("Produkt byl úspěšně přidán.");
    }
    public function render(){
        $this->getTemplate()->setFile(__DIR__  .  "/../../forms/AddProduct/AddProduct.latte");
        $this->getTemplate()->render();
    }

}