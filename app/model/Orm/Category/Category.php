<?php

namespace Skeleton\Model;

/**
 * Class Category
 * @package Skeleton\Model
 * @property int $id {primary}
 * @property string $categoryName
 * @property string $categoryLabel
 * @property string|NULL $description
 * @property Product[] $products {1:m Product::$category}
 * @property CategoryParent[] $category {1:m CategoryParent::$category}
 * @property CategoryParent[]|NULL $parent {1:m CategoryParent::$parent}
 * @property string|NULL $image
 * @property boolean|NULL $visible
 */
use Nextras\Orm\Entity\Entity;

class Category extends Entity{
}