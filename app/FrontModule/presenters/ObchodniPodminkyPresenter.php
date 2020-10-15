<?php

namespace Skeleton\FrontModule\Presenters;

use Skeleton\Model\Orm;
use Tracy\Debugger;

class ObchodniPodminkyPresenter extends BasePresenter{

    public function __construct(Orm $orm)
    {
        parent::__construct($orm);
    }

    public function renderDefault(){

    }
}

