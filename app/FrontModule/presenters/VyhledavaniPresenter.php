<?php

namespace Skeleton\FrontModule\Presenters;

use Skeleton\FrontModule\Forms\IAddProductFormFactory;
use Skeleton\FrontModule\Forms\INewQuoteFormFactory;
use Skeleton\Model\Orm;
use Nette\Utils\DateTime;
use Nextras\Dbal\Connection;
use Tracy\Debugger;

Class VyhledavaniPresenter extends BasePresenter{

    /** @var int */
    public $currentProductId;

    /** @var array */
    public $parentCategories = [];

    /**
     * @var IAddProductFormFactory  */
    public $addProductFormFactory;

    /**
     * @var INewQuoteFormFactory
     */
    public $newQuoteFormFactory;

    public function __construct(Orm $orm, IAddProductFormFactory $addProductFormFactory, INewQuoteFormFactory $newQuoteFormFactory)
    {
        parent::__construct($orm);
        $this->addProductFormFactory = $addProductFormFactory;
        $this->newQuoteFormFactory = $newQuoteFormFactory;
    }

    public function renderDefault($p, $page=1){
        Debugger::barDump(htmlentities($p));
        $this->getTemplate()->products = $this->findProduct(htmlentities($p));
        $this->getTemplate()->phrase = $p;
    }

    public function findProduct($p){
        return $this->orm->products->findProducts($p);

    }

    public function renderProdukt($id)
    {
        $this->currentProductId = $id;
        $product = $this->orm->products->getById($id);

        $currentTime = new DateTime();
        $deliveryTime = DateTime::from($currentTime);
        if ($deliveryTime->format("N") == 5) {
            $days = 6;
        } elseif ($deliveryTime->format("N") == 6) {
            $days = 5;
        } elseif ($deliveryTime->format("N") == 7) {
            $days = 4;
        }
        else {
            $days = 4;
        }
        $deliveryTime->modify('+'.$days.' days');
        $this->getTemplate()->product = $product;
        $this->getTemplate()->relatedProducts = $this->orm->products->findBy(['category'=>$product->category, 'active' => 1])->limitBy(4,0);
        $this->getTemplate()->categories = $this->orm->categories->getById($id);
        $this->getTemplate()->deliveryDate = $deliveryTime;
        $this->getTemplate()->productInCart = $this->productInCart($id);
        $this->getTemplate()->actualId = $id;
        $this->getTemplate()->den = $this->cesky_den(1);
        $this->getCategoryPath($product->category); /* -SETNUTÍ NÁZVŮ KATEGORIÍ CESTY KD AKTUÁLNÍ KATEGORIE- */
        $this->getTemplate()->categoryPath = array_reverse($this->parentCategories, true);
        $this->getTemplate()->session = $this->getSession()->getSection('products');
    }

    public function productInCart($id){
        $sessionProduct = $this->getSession()->getSection('products');
        if($sessionProduct->$id)
        {
            return true;
        }
        else{
            return false;
        }
    }

    function cesky_den($den) {
        static $nazvy = array('neděle', 'pondělí', 'úterý', 'středa', 'čtvrtek', 'pátek', 'sobota');
        return $nazvy[$den];
    }

    public function getCategoryPath($id){
        $category = $this->orm->categoryParents->getBy(['category'=>$id]);
        if($category) {
            if ($category->parent !== null) {
                $this->parentCategories[$category->category->id] = $category->category->categorySingleName;
                $this->getCategoryPath($category->parent->id);
            } else {
                $this->parentCategories[$category->category->id] = $category->category->categorySingleName;
            }
        }
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

    public function handleAddProductToCartFromProduct($id, $currentProduct){
        $product = $this->orm->products->getById($id);
        //$array = ['id'=>$id, 'productName'=>$product->productName,'catalogPriceVat'=>$product->catalogPriceVat,'quantity'=>1, 'photo'=>$product->image];
        $productsSession = $this->getSession()->getSection('products');
        $productsSession->$id = array();
        $productsSession->$id['id'] = $id;
        $productsSession->$id['productName'] = $product->productName;
        $productsSession->$id['catalogPriceVat'] = $product->catalogPriceVat;
        $productsSession->$id['quantity'] = 1;
        $productsSession->$id['photo'] = $product->image;
        $this->redirect('produkt', $currentProduct);
    }

    protected function createComponentAddProductForm(){
        return $this->addProductFormFactory->create($this->currentProductId);
    }

    protected function createComponentNewQuoteForm(){
        return $this->newQuoteFormFactory->create($this->currentProductId);
    }
}