<?php

namespace Skeleton\Model;

/**
 * Class Category
 * @package Skeleton\Model
 * @property int $id {primary}
 * @property string $nameClean
 * @proerty string $name
 * @property string $value
 * @property string $type {enum self::TYPE_*}
 */
use Nextras\Orm\Entity\Entity;

class Setting extends Entity {

    const TYPE_INTEGER = 'INTEGER';
    const TYPE_STRING = 'STRING';
    const TYPE_BOOLEAN = 'BOOLEAN';

}