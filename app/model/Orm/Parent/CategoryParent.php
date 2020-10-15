<?php

namespace Skeleton\Model;

/**
 * Class CategoryParent
 * @package Skeleton\Model
 * @property int $id {primary}
 * @property Category $category {m:1 Category::$category}
 * @property Category|NULL $parent {m:1 Category::$parent}
 */
use Nextras\Orm\Entity\Entity;

class CategoryParent extends Entity{

}