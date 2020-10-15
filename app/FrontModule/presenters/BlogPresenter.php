<?php

namespace Skeleton\FrontModule\Presenters;

use Skeleton\FrontModule\Forms\INewQuoteFormFactory;
use Skeleton\Model\Orm;

Class BlogPresenter extends BasePresenter{
    /**
     * @var INewQuoteFormFactory
     */
    public $newQuoteFormFactory;

    public function __construct(Orm $orm, INewQuoteFormFactory $newQuoteFormFactory)
    {
        parent::__construct($orm);
        $this->newQuoteFormFactory = $newQuoteFormFactory;
    }

    public function actionDefault($id = NULL)
    {
        $this->template->articles = $this->orm->articles->findBy(['deleted' => false])->orderBy('createdAt', 'DESC');
        $this->template->articleCategories = $this->orm->articleCategories->findAll();
    }

    public function renderDefault(){
        $this->getTemplate()->setFile(__DIR__ . "/../templates/Blog/default.latte");
    }


    public function actionClanek($id)
    {
        $article = $this->orm->articles->getById($id);
        $this->template->article = $article;
        $this->template->articleCategories = $this->orm->articleCategories->findAll();
    }

    protected function createComponentNewQuoteForm(){
        return $this->newQuoteFormFactory->create(0);
    }
}