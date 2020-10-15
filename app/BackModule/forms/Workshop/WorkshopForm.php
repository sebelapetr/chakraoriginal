<?php

namespace Skeleton\BackModule\Forms;

use Skeleton\Model\Event;
use Skeleton\Model\Orm;
use Skeleton\Model\ProductService;
use Skeleton\Model\EditParentCategoryService;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Tracy\Debugger;

interface IWorkshopFormFactory{

    /**
     * @var Orm $orm
     * @var Event $event
     * @return WorkshopForm
     */
    function create(Orm $orm, Event $event = NULL);
}

class WorkshopForm extends Control{

    /** @var Event */
    public $event;

    /** @var Orm  */
    public $orm;

    public function __construct(Orm $orm, Event $event = NULL)
    {
        $this->event = $event;
        $this->orm = $orm;
    }

    protected function createComponentEditProductForm(){
        $form = new Form();
        $form->addText("title");
        $form->addTextArea("description");
        $form->addUpload("image");

        if ($this->event) {
            $form->setDefaults(['title' => $this->event->title, 'description' => $this->event->description, 'image' => $this->event->image]);
        }

        $form->addSubmit("submit");
        $form->onSuccess[] = [$this, 'workshopFormSucceeded'];
        return $form;
    }
    public function workshopFormSucceeded(Form $form, $values){

        $event = $this->event ? $this->event : new Event();

        if (!$this->event) {
            $event->author = $this->orm->users->getById($this->getPresenter()->user->id);
            $event->createdAt = new \DateTime();
            $event->type = Event::TYPE_WORKSHOP;
        }

        $event->title = $values->title;
        $event->description = $values->description;

        if ((filesize ($values->image) > 0) and $values->image->isImage()) {
            $soubor = $values->image;
            $soubor->move("img/workshopy/" . $values->image->name);
            $event->image = $values->image->name;
        }

        $this->orm->persistAndFlush($event);

        $this->getPresenter()->flashMessage("Workshop byl úspěšně upraven.");
        $this->getPresenter()->redirect("Workshop:default");
    }
    public function render(){
        $this->getTemplate()->setFile(__DIR__  .  "/../../forms/Workshop/WorkshopForm.latte");
        $this->template->workshop = $this->event;
        $this->getTemplate()->render();
    }

}