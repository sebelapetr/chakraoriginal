<?php

namespace Skeleton\Model;

use Nextras\Orm\Repository\Repository;

class CategoryParentsRepository extends Repository{

    /**
     * Returns possible entity class names for current repository.
     * @return string[]
     */
    public static function getEntityClassNames(): array
    {
        return [CategoryParent::class];
    }
}