<?php

namespace Skeleton\BackModule\Presenters;

use Skeleton\BackModule\Forms\IArticleFormFactory;
use Skeleton\FrontModule\Forms\INewQuoteFormFactory;
use Skeleton\Model\Article;
use Skeleton\Model\Orm;

Class BlogPresenter extends BasePresenter{
    /**
     * @var IArticleFormFactory @inject
     */
    public $articleFormFactory;

    /** @var Article */
    public $article;

    public function __construct(Orm $orm)
    {
        parent::__construct($orm);
    }

    public function renderDefault(){
        $this->getTemplate()->setFile(__DIR__ . "/../templates/Blog/default.latte");
        $this->template->articles = $this->orm->articles->findBy(['deleted' => false]);
    }

    public function handleDelete($id)
    {
        $article = $this->orm->articles->getById($id);
        $article->deleted = true;
        $this->orm->persistAndFlush($article);
        $this->redirect('this');
    }

    public function actionEdit($id = NULL)
    {
        $this->article = $id ? $this->orm->articles->getById($id) : NULL;
        $this->template->article = $this->article;
    }

    public function createComponentArticleForm()
    {
        return $this->articleFormFactory->create($this->orm, $this->article);
    }
}