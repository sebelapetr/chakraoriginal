<?php

namespace Skeleton\FrontModule\Presenters;

use Skeleton\FrontModule\Forms\INewQuoteFormFactory;
use Skeleton\Model\Orm;
use Nette\Caching\Storages\FileStorage;
use ondrs\Hi\Hi;
use Tracy\Debugger;

Class UvodniStranaPresenter extends BasePresenter{
    public function __construct(Orm $orm)
    {
        parent::__construct($orm);
    }

    public function renderDefault(){
        $this->getTemplate()->setFile(__DIR__ . "/../templates/UvodniStrana/default.latte");
        $this->template->categories = $this->orm->categories->findBy(['visible' => true]);
    }
}