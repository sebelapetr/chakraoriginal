<?php

namespace Skeleton\Model;

use Nextras\Orm\Repository\Repository;

class SettingsRepository extends Repository{

    /**
     * Returns possible entity class names for current repository.
     * @return string[]
     */
    public static function getEntityClassNames(): array
    {
        return [Setting::class];
    }

}