<?php

namespace Skeleton\FrontModule\Presenters;

use Skeleton\FrontModule\Forms\INewQuoteFormFactory;
use Skeleton\Model\Orm;

Class KontaktPresenter extends BasePresenter{
    /**
     * @var INewQuoteFormFactory
     */
    public $newQuoteFormFactory;

    public function __construct(Orm $orm, INewQuoteFormFactory $newQuoteFormFactory)
    {
        parent::__construct($orm);
        $this->newQuoteFormFactory = $newQuoteFormFactory;
    }

    public function renderDefault(){
        $this->getTemplate()->setFile(__DIR__ . "/../templates/Kontakt/default.latte");
    }


    protected function createComponentNewQuoteForm(){
        return $this->newQuoteFormFactory->create(0);
    }
}