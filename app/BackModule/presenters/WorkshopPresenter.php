<?php

namespace Skeleton\BackModule\Presenters;

use Nette\Application\UI\Form;
use Skeleton\BackModule\Forms\IWorkshopFormFactory;
use Skeleton\FrontModule\Forms\INewQuoteFormFactory;
use Skeleton\Model\Event;
use Skeleton\Model\Orm;
use Tracy\Debugger;

Class WorkshopPresenter extends BasePresenter{
    /**
     * @var IWorkshopFormFactory @inject
     */
    public $workshopFormFactory;

    /** @var Event */
    public $workshop;

    public function __construct(Orm $orm)
    {
        parent::__construct($orm);
    }

    public function renderDefault(){
        $this->getTemplate()->setFile(__DIR__ . "/../templates/Workshop/default.latte");
        $this->template->workshops = $this->orm->events->findBy(['deleted' => false]);
    }


    protected function createComponentNewQuoteForm(){
        return $this->newQuoteFormFactory->create(0);
    }

    public function handleDelete($id)
    {
        $event = $this->orm->events->getById($id);
        $event->deleted = true;
        $this->orm->persistAndFlush($event);
        $this->redirect('this');
    }

    public function actionEdit($id = NULL)
    {
        $this->workshop = $id ? $this->orm->events->getById($id) : NULL;
        $this->template->workshop = $this->workshop;
    }

    public function createComponentWorkshopForm()
    {
        return $this->workshopFormFactory->create($this->orm, $this->workshop);
    }


}