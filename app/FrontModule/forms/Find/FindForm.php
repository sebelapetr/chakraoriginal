<?php

namespace Skeleton\FrontModule\Forms;

use Skeleton\Model\AddProductService;
use Skeleton\Model\FindService;
use Skeleton\Model\QuoteService;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Tracy\Debugger;

interface IFindFormFactory{
    /** @return FindForm */
    function create();
}

class FindForm extends Control{

    /** @var FindService */
    public $findService;


    public function __construct(FindService $findService)
    {
        $this->findService = $findService;
    }

    protected function createComponentFindForm(){
        $form = new Form();
        $form->addText('phrase');
        $form->addSubmit("submit");
        $form->onSuccess[] = [$this, 'findFormSucceeded'];
        return $form;
    }
    public function findFormSucceeded(Form $form, $values){
        $this->getPresenter()->redirect('Vyhledavani:default', $values->phrase);
        //{link Vyhledavani:default 'Roboran'}"
    }
    public function render(){
        $this->getTemplate()->setFile(__DIR__  .  "/../../forms/Find/Find.latte");
        $this->getTemplate()->render();
    }

}