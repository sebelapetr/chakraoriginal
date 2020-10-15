<?php

namespace Skeleton\Model;

/**
 * Class ArticleCategory
 * @package Skeleton\Model
 * @property int $id {primary}
 * @property string $name
 * @property Article[]|NULL $articles {1:m Article::$articleCategory}
 */
use Nextras\Orm\Entity\Entity;

class ArticleCategory extends Entity{
}