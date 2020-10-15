<?php

namespace Skeleton\Model;

use Nextras\Orm\Entity\Entity;

/**
 * Class Product
 * @package Skeleton\Model
 * @property int $id {primary}
 * @property string|NULL $productName
 * @property float|NULL $catalogPrice
 * @property float|NULL $catalogPriceVat
 * @property int|NULL $vat
 * @property int|NULL $stockLevel
 * @property string|NULL $image
 * @property string|NULL $description
 * @property int|NULL $active
 * @property Category|NULL $category {m:1 Category::$products}
 * @property Quote|NULL $quote {1:m Quote::$product}
 * @property OrdersItem|NULL $orderItem {1:m OrdersItem::$product}
 * @property string|NULL $code
 * @property int|NULL $orderValue
 */
class Product extends Entity{

    public function productInCart($id, $sessionProduct){
        if($sessionProduct->$id)
        {
            return true;
        }
        else{
            return false;
        }
    }
}