<?php

namespace Skeleton\FrontModule\Presenters;

use Skeleton\FrontModule\Forms\IAddProductFormFactory;
use Skeleton\FrontModule\Forms\INewQuoteFormFactory;
use Skeleton\Model\Category;
use Skeleton\Model\CategoryParent;
use Skeleton\Model\Orm;
use Nette\Utils\DateTime;
use Nette\Utils\Paginator;
use Tracy\Debugger;
use LikeFilterFunction;

class ProduktyPresenter extends BasePresenter{

    /** @var int */
    public $limit;

    /** @var array  */
    public $pages = [];

    /** @var int */
    public $pagesNumber;

    /** @var  int */
    public $productsNumber;

    /** @var array */
    public $categories = [];

    /** @var int */
    public $currentCategoryId;

    /** @var array */
    public $productCategories = [];

    /** @var array */
    public $parentCategories = [];

    /** @var array  */
    public $actualCategoryChilds = [];

    /** @var int */
    public $lastPage;

    /** @var int */
    public $currentProductId;

    /**
     * @var INewQuoteFormFactory
     */
    public $newQuoteFormFactory;

    /**
     * @var IAddProductFormFactory  */
    public $addProductFormFactory;

    public function __construct(Orm $orm, INewQuoteFormFactory $newQuoteFormFactory, IAddProductFormFactory $addProductFormFactory)
    {
        parent::__construct($orm);
        $this->newQuoteFormFactory = $newQuoteFormFactory;
        $this->addProductFormFactory = $addProductFormFactory;
    }

    public function actionDefault($categoryId = NULL, $page = 1)
    {
        $this->limit = 30;
        $offset = ($page-1)*$this->limit;
        $criteria = [];

        if ($categoryId) {
            $this->template->category = $this->orm->categories->getById($categoryId);
            $products = $this->orm->products->findBy(['category' => $categoryId, 'active' => 1])->orderBy('orderValue', 'DESC');
            $criteria['category'] = $categoryId;
            $criteria['active'] = 1;
        } else {
            $products = $this->orm->products->findBy(['active' => 1])->orderBy('orderValue', 'DESC');
            $criteria['active'] = 1;
        }

        $paginator = $this->getPaginator($page, $products->countStored());

        $products = $this->orm->products->findBy($criteria)->limitBy($paginator->getLength(), $paginator->getOffset())->orderBy('orderValue', 'DESC');

        $this->template->categories = $this->orm->categoryParents->findBy(['parent' => NULL]);

        $productsArray = [];
        foreach ($products as $product) {
            Debugger::barDump($product->productName);
            $productsArray[] = $product;
        }

        $this->template->products = $productsArray;

        if ($products->countStored() > 0 && $products->countStored() < 7) {
            $this->template->productsLayout = __DIR__ . '/../templates/Produkty/products-layout/' . $products->countStored() . '-product.latte';
        }

        if ($products->countStored() > 6) {
            Debugger::barDump('a');
            $this->template->productsLayout = __DIR__ . '/../templates/Produkty/products-layout/all-product.latte';
        }

        $this->template->paginator = $paginator;
    }

    public function renderStary($id, $page=1, $orderBy = null, $direction = null)
    {
        if ($id === null) {
            $this->redirect('vsechnyProdukty');
        }

        /* -NASTAVENÍ PROMĚNNÝCH- */
        $this->currentCategoryId = $id; /* -AKTUÁLNÍ KATEGORIE- */
        $this->limit = 2; /* -LIMIT PRODUKTŮ- */
        $offset = $page>0?($page-1)*$this->limit:$page; /* -OFFSET PRODUKTŮ- */
        /* ---------------------- */

        $category = $this->orm->categories->getById($id);

        $this->getTemplate()->actualId = $this->currentCategoryId;

        $this->getTemplate()->setFile(__DIR__ . "/../templates/Kategorie/default.latte");

        $this->getCategoryChilds($category);

        $categoryChilds = $this->orm->categories->findById($this->actualCategoryChilds);

        $this->getTemplate()->category = $category;


        $this->actualCategoryChilds[] = intval($this->currentCategoryId);

        $products = $this->orm->products->findBy(['category'=>$this->actualCategoryChilds, 'active' => 1])->orderBy('orderValue', 'DESC')->limitBy($this->limit, $offset);

        $this->getTemplate()->products = $products;

        $this->lastPage = ceil($this->orm->products->findBy(['category'=>$this->categories, 'active' => 1])->countStored()/$this->limit); /* -- */


        $this->getTemplate()->actualPage = $page;
        $this->getTemplate()->pages = $this->getPages($page, $products->countStored());
        $this->getTemplate()->lastPage = ceil($products->countStored()/15);
        $session = $this->getSession()->getSection('products');
    }


    public function handleAddProductToCart($id, $currentCategory){
        $product = $this->orm->products->getById($id);
        //$array = ['id'=>$id, 'productName'=>$product->productName,'catalogPriceVat'=>$product->catalogPriceVat,'quantity'=>1, 'photo'=>$product->image];
        $productsSession = $this->getSession()->getSection('products');
        $productsSession->$id = array();
        $productsSession->$id['id'] = $id;
        $productsSession->$id['productName'] = $product->productName;
        $productsSession->$id['catalogPriceVat'] = $product->catalogPriceVat;
        $productsSession->$id['quantity'] = 1;
        $productsSession->$id['photo'] = $product->image;
        $this->flashMessage('Produkt byl přidán do košíku');
        $this->redirect('kategorie', $currentCategory);
    }

    public function getPages($actualPage, $count){
        $paginator = new Paginator();
        $paginator->setItemCount($count);
        $paginator->setItemsPerPage($this->limit);
        $paginator->setPage($actualPage);

        return $paginator;
    }

    public function getPaginator($actualPage, $count){
        $paginator = new Paginator();
        $paginator->setItemCount($count);
        $paginator->setItemsPerPage($this->limit);
        $paginator->setPage($actualPage);

        return $paginator;
    }

    public function isProductInCart($id){
        $sessionProduct = $this->getSession()->getSection('products');
        if($sessionProduct->$id)
        {
            return true;
        }
        else{
            return false;
        }
    }

    public function getCategoryChilds(Category $category) {

        $childs = $this->orm->categoryParents->findBy(['parent' => $category]);

        if ($childs->countStored() > 0) {
            /** @var CategoryParent $child */
            foreach ($childs as $child) {
                if (!in_array($child->category->id, $this->actualCategoryChilds)) {
                    $this->actualCategoryChilds[] = $child->category->id;
                }
                $this->getCategoryChilds($child->category);
            }
        }

        return $this->actualCategoryChilds;

    }
}