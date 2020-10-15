<?php

namespace Skeleton\FrontModule\Presenters;

use Nette\Application\UI\Form;
use Nette\Mail\Message;
use Nette\Mail\SendmailMailer;
use Skeleton\FrontModule\Forms\INewQuoteFormFactory;
use Skeleton\Model\Event;
use Skeleton\Model\Orm;
use Tracy\Debugger;

Class WorkshopyPresenter extends BasePresenter{
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
        $this->template->workshops = $this->orm->events->findBy(['deleted' => false,'type' => Event::TYPE_WORKSHOP]);
        $this->getTemplate()->setFile(__DIR__ . "/../templates/Workshopy/default.latte");
    }


    protected function createComponentNewQuoteForm(){
        return $this->newQuoteFormFactory->create(0);
    }

    public function actionWorkshop($id)
    {
        $workshop = $this->orm->events->getById($id);
        $this->template->workshop = $workshop;
    }


    public function createComponentRegisterForm()
    {
        $form = new Form();
        $form->addText('name', 'Jméno a příjmení:')
            ->setAttribute('class', 'registerFormInput')
            ->setRequired();
        $form->addEmail('email', 'Email:')
            ->setAttribute('class', 'registerFormInput')
            ->setRequired();
        $form->addText('phone', 'Telefon:')
            ->setAttribute('class', 'registerFormInput')
            ->setRequired();
        $form->addSubmit('submit', 'Registrovat se')
            ->setAttribute('class', 'btn btn-success');

        $form->onSuccess[] = [$this, 'registerFormSucceed'];

        return $form;
    }

    public function registerFormSucceed($form, $values)
    {
        $mailer = new SendmailMailer();

        $mail = new Message();

        $mail->setFrom('Chakra Original <chakra.original@gmail.com>')
            ->addTo('anita.bodyfitness@gmail.com')
            ->setSubject('Potvrzení objednávky workshopu')
            ->setBody("Dobrý den, nová registrace do workshopu.....");

        $mailer->send($mail);

        $mail->setFrom('Chakra Original <chakra.original@gmail.com>')
            ->addTo('chakra.original@gmail.com')
            ->setSubject('Potvrzení objednávky workshopu')
            ->setBody("Dobrý den, nová registrace do workshopu.....");

        $mailer->send($mail);

        $mail = new Message();
        $mail->setFrom('Chakra Original <chakra.original@gmail.com>')
            ->addTo($values->email)
            ->setSubject('Potvrzení objednávky workshopu')
            ->setBody("Dobrý den, nová registrace do workshopu.....");

        $mailer->send($mail);

        $mail = new Message();
        $mail->setFrom('Chakra Original <chakra.original@gmail.com>')
            ->addTo('petrsebel@seznam.cz')
            ->setSubject('Potvrzení objednávky workshopu')
            ->setBody("Dobrý den, nová registrace do workshopu.....");

        $mailer->send($mail);

    }
}