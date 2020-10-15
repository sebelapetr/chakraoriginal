<?php

namespace Skeleton\Model;

use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Repository\Repository;

/**
 * Class CampaignsRepository
 * @package Skeleton\Model
 *
 */

class CampaignsRepository extends Repository{

    /**
     * Returns possible entity class names for current repository.
     * @return string[]
     */
    public static function getEntityClassNames(): array
    {
        return [Campaign::class];
    }
}