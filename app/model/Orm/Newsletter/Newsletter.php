<?php

namespace Skeleton\Model;

use Nette\Utils\DateTime;
use Nextras\Dbal\Utils\DateTimeImmutable;
use Nextras\Orm\Entity\Entity;

/**
 * Class Newsletter
 * @package Skeleton\Model
 * @property int $id {primary}
 * @property string $email
 * @property DateTimeImmutable $date
 * @property int $allowed
 */
Class Newsletter extends Entity{

}