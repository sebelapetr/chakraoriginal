<?php

namespace Skeleton\BackModule\Presenters;

use Skeleton\BackModule\Forms\IEditCategoryFormFactory;
use Skeleton\BackModule\Forms\IEditParentCategoryFormFactory;
use Skeleton\BackModule\Forms\IAddCategoryFormFactory;
use Skeleton\BackModule\Forms\ICategoryListFormFactory;
use Skeleton\Model\Orm;
use Tracy\Debugger;

Class CategoryPresenter extends BasePresenter{

    /** @var IEditParentCategoryFormFactory */
    public  $editParentCategoryFormFactory;

    /** @var IEditCategoryFormFactory */
    public  $editCategoryFormFactory;

    /** @var IAddCategoryFormFactory */
    public  $addCategoryFormFactory;

    /** @var ICategoryListFormFactory */
    public  $categoryListFormFactory;

    /** @var int */
    public $categoryId;


    public function __construct(Orm $orm, IEditParentCategoryFormFactory $editParentCategoryFormFactory, IEditCategoryFormFactory $editCategoryFormFactory, IAddCategoryFormFactory $addCategoryFormFactory, ICategoryListFormFactory $categoryListFormFactory)
    {
        parent::__construct($orm);
        $this->editParentCategoryFormFactory = $editParentCategoryFormFactory;
        $this->editCategoryFormFactory = $editCategoryFormFactory;
        $this->addCategoryFormFactory = $addCategoryFormFactory;
        $this->categoryListFormFactory = $categoryListFormFactory;
    }

    public function renderDefault()
    {
        $this->getTemplate()->setFile(__DIR__ . "/../templates/Category/default.latte");
        $this->getTemplate()->categoriesNumber = $this->orm->categories->findAll()->countStored();
    }

    public function renderEdit($id){
        $this->getTemplate()->category = $this->orm->categories->getBy(["id"=>$id]);
        $this->categoryId = $id;
    }

    public function renderList()
    {
        $this->getTemplate()->setFile(__DIR__ . "/../templates/Category/list.latte");
        $this->getTemplate()->parentCategories = $this->orm->categoryParents->findAll();
    }

    public function createComponentEditParentCategoryForm()
    {
        return $this->editParentCategoryFormFactory->create($this->categoryId);
    }

    public function createComponentEditCategoryForm()
    {
        return $this->editCategoryFormFactory->create($this->categoryId);
    }

    public function createComponentAddCategoryForm()
    {
        return $this->addCategoryFormFactory->create();
    }

    public function createComponentCategoryListForm()
    {
       return $this->categoryListFormFactory->create();
    }

    public function handleDelete($id){
        $categoryParent = $this->orm->categoryParents->getBy(['category'=>$id]);
        $this->orm->remove($categoryParent);
        $this->orm->flush();
        $category = $this->orm->categories->getById($id);
        $this->orm->remove($category);
        $this->orm->flush();
        $this->flashMessage("Kategorie byl úspěšně smazaná");
    }
}