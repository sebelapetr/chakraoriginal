<?php

namespace Skeleton\Model;

use Nette\Security\Passwords;
use Nette\Utils\FileSystem;
use Tracy\Debugger;
use Nette\SmartObject;

class ProductService{

    /** @var Orm */
    private $orm;

    public function __construct(Orm $orm)
    {
        $this->orm = $orm;
    }

    public function getCategories(){
        if ($this->categoriesExists()) {
            $categories = $this->orm->categories->findAll();
            foreach ($categories as $category) {
                $categorie[$category->id] = $category->categoryName;
            }
        }
        else{
            $categorie[] = 'Nebyla nalezena žádná kategorie';
        }
        return $categorie;
    }

    public function productsExists() {
        $number = $this->orm->products->findAll()->countStored();
        $number = $number>0?true:false;
        return $number;
    }

    public function categoriesExists() {
        $number = $this->orm->categories->findAll()->countStored();
        $number = $number>0?true:false;
        return $number;
    }

    public function getProducts(){
        if ($this->productsExists()){
            $products = $this->orm->products->findAll()->orderBy('productName', 'ASC');
            foreach ($products as $product){
                $productsList[$product->id] = $product->productName;
            }
        }
        else{
            $productsList[] = 'Nebyl nalezen žádný produkt';
        }
        return $productsList;
    }

    public function getDefaultValues($id, $value){
        if($id) {
            $product = $this->orm->products->getById($id)->$value;
            if (isset($product)) {
                return $product;
            }
        }
    }

    public function addProduct($values){
        try {
        $product = new Product();
        $product->productName = $values->name;
        $product->description = $values->description;
        $product->image = $values->image->name;
        $product->catalogPriceVat = $values->price;
        $product->catalogPrice = $values->price;
        $product->vat = 0;
        $product->active = $values->visible;
        $product->code = $values->code;
        $product->stockLevel = intval($values->stock);
        $product->orderValue = $values->order;
        $this->orm->persistAndFlush($product);
        } catch (\Exception $exception) {
            Debugger::log($exception, Debugger::ERROR);
        }

        if ($this->categoriesExists()) {
            $product = $this->orm->products->getBy(["id" => $product->id]);
            $product->category = $values->category;
            $this->orm->persistAndFlush($product);
        }
    }

    public function editProduct($values){
        $product = $this->orm->products->getBy(["id"=>$values->id]);
        $product->productName = $values->name;
        $product->description = $values->description;
        if ($values->image->name) {$product->image = $values->image->name;}
        $product->catalogPriceVat = $values->price;
        $product->catalogPrice = $values->price;
        $product->vat = 0;
        $product->active = $values->visible;
        $product->code = $values->code;
        $product->stockLevel = intval($values->stock);
        $product->orderValue = $values->order;
        $this->orm->persistAndFlush($product);
    }

    public function redirectTo($values){
        return $values->product;
    }
}