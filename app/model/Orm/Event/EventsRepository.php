<?php

namespace Skeleton\Model;

use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Repository\Repository;

/**
 * Class EventsRepository
 * @package Skeleton\Model
 *
 */

class EventsRepository extends Repository{

    /**
     * Returns possible entity class names for current repository.
     * @return string[]
     */
    public static function getEntityClassNames(): array
    {
        return [Event::class];
    }
}