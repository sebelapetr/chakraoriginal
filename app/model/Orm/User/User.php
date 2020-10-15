<?php

namespace Skeleton\Model;



use Nextras\Orm\Entity\Entity;

/**
 * Class User
 * @package Skeleton\Model
 * @property int $id {primary}
 * @property string $password
 * @property string $email
 * @property Article[]|NULL $articles {1:m Article::$author}
 */
class User extends Entity {

}