<?php

namespace Skeleton\BackModule\Forms;

use Skeleton\Model\EditParentCategoryService;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Skeleton\Model\AddSerialService;
use Tracy\Debugger;
interface IEditParentCategoryFormFactory{
    /** @return EditParentCategoryForm */
    function create($id);
}

class EditParentCategoryForm extends Control{

    /** @var EditParentCategoryService */
    public $editParentCategoryService;

    /** @var int */
    public $categoryId;

    public function __construct(EditParentCategoryService $editParentCategoryService, $id)
    {

        $this->editParentCategoryService = $editParentCategoryService;
        $this->categoryId = $id;
    }

    protected function createComponentEditParentCategoryForm(){
        $options = $this->editParentCategoryService->getCategories();
        $options[null] = 'Rodičovská kategorie';
        $form = new Form();
        $form->addHidden('id', $this->categoryId);
        $form->addSelect("parent", '', $options);
        $form->addSubmit("submit");
        $form->onSuccess[] = [$this, 'editParentCategoryFormSucceeded'];
        return $form;
    }
    public function editParentCategoryFormSucceeded(Form $form, $values){
        $this->editParentCategoryService->editParentCategory($values);
        $this->getPresenter()->flashMessage("Rodičovská kategorie úspěšně aktualizována.");

        //$this->getPresenter()->redirect("Homepage:default");
    }
    public function render(){
        $this->getTemplate()->setFile(__DIR__  .  "/../../forms/EditParentCategory/EditParentCategory.latte");
        $this->getTemplate()->render();
    }

}