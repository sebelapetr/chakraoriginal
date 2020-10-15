<?php

namespace Skeleton\BackModule\Forms;

use Skeleton\Model\Article;
use Skeleton\Model\Event;
use Skeleton\Model\Orm;
use Skeleton\Model\ProductService;
use Skeleton\Model\EditParentCategoryService;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Tracy\Debugger;

interface IArticleFormFactory{

    /**
     * @var Orm $orm
     * @var Article $article
     * @return ArticleForm
     */
    function create(Orm $orm, Article $article = NULL);
}

class ArticleForm extends Control{

    /** @var Article */
    public $article;

    /** @var Orm  */
    public $orm;

    public function __construct(Orm $orm, Article $article = NULL)
    {
        $this->article = $article;
        $this->orm = $orm;
    }

    protected function createComponentEditArticleForm(){
        $form = new Form();
        $form->addText("title");
        $form->addTextArea("description");
        $form->addUpload("image");

        if ($this->article) {
            $form->setDefaults(['title' => $this->article->title, 'description' => $this->article->description, 'image' => $this->article->image]);
        }

        $form->addSubmit("submit");
        $form->onSuccess[] = [$this, 'articleFormSucceeded'];
        return $form;
    }
    public function articleFormSucceeded(Form $form, $values){

        $article = $this->article ? $this->article : new Article();

        if (!$this->article) {
            $article->author = $this->orm->users->getById($this->getPresenter()->user->id);
            $article->createdAt = new \DateTime();
            $article->articleCategory = $this->orm->articleCategories->getById(1);
        }

        $article->title = $values->title;
        $article->description = $values->description;

        if ((filesize ($values->image) > 0) and $values->image->isImage()) {
            $soubor = $values->image;
            $soubor->move("img/clanky/" . $values->image->name);
            $article->image = $values->image->name;
        }

        $this->orm->persistAndFlush($article);

        $this->getPresenter()->flashMessage("Článek byl úspěšně upraven.");
        $this->getPresenter()->redirect("Blog:default");
    }
    public function render(){
        $this->getTemplate()->setFile(__DIR__  .  "/../../forms/Article/ArtcileForm.latte");
        $this->template->article = $this->article;
        $this->getTemplate()->render();
    }

}