<?php

namespace Skeleton\FrontModule\Presenters;

use Skeleton\FrontModule\Forms\IAddProductFormFactory;
use Skeleton\FrontModule\Forms\INewQuoteFormFactory;
use Skeleton\Model\Orm;
use Nette\Utils\DateTime;
use Nette\Utils\Paginator;
use Tracy\Debugger;
use LikeFilterFunction;

class ProduktPresenter extends BasePresenter{

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

    public function renderDefault($id)
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
        $this->getTemplate()->categoryPath = array_reverse($this->parentCategories, true);
        $this->getTemplate()->session = $this->getSession()->getSection('products');
    }

    function cesky_den($den) {
        static $nazvy = array('neděle', 'pondělí', 'úterý', 'středa', 'čtvrtek', 'pátek', 'sobota');
        return $nazvy[$den];
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
        $this->redirect('default', $currentProduct);
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

    protected function createComponentNewQuoteForm(){
        return $this->newQuoteFormFactory->create($this->currentProductId);
    }

    protected function createComponentAddProductForm(){
        return $this->addProductFormFactory->create($this->currentProductId);
    }
}