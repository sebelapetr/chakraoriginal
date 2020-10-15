<?php

namespace Skeleton\Model;

use Nextras\Orm\Entity\Entity;

/**
 * Class Event
 * @package Skeleton\Model
 * @property int $id {primary}
 * @property string $title
 * @property string|NULL $description
 * @property User $author {m:1 User::$articles}
 * @property \DateTimeImmutable $createdAt
 * @property string|NULL $image
 * @property string $type {enum self::TYPE_*}
 * @property \DateTimeImmutable|NULL $date
 * @property boolean $deleted {default false}
 */
class Event extends Entity{
    const TYPE_WORKSHOP = 'WORKSHOP';
}