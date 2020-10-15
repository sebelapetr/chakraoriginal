<?php

namespace Skeleton\Model;

use Nextras\Orm\Entity\Entity;

/**
 * Class Campaign
 * @package Skeleton\Model
 * @property int $id {primary}
 * @property boolean $active {default false}
 * @property string $code {enum self::CODE_*}
 */
class Campaign extends Entity {

    const CODE_FREE_DELIVERY_WEEK = "FREE_DELIVERY_WEEK";
    const CODE_CHECK_FB = "CHECK_FB";

}