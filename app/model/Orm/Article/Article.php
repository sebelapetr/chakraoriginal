<?php

namespace Skeleton\Model;

use Nextras\Orm\Entity\Entity;

/**
 * Class Article
 * @package Skeleton\Model
 * @property int $id {primary}
 * @property string $title
 * @property string|NULL $description
 * @property User $author {m:1 User::$articles}
 * @property \DateTimeImmutable $createdAt
 * @property string|NULL $image
 * @property ArticleCategory $articleCategory {m:1 ArticleCategory::$articles}
 * @property boolean $deleted {default false}
 */
class Article extends Entity{
}