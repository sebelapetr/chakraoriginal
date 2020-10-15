<?php

namespace Skeleton\Model;

use Latte\Engine;
use Nette\Caching\Storages\FileStorage;
use Nette\Mail\Message;
use Nette\Mail\SendmailMailer;
use Nette\Security\Passwords;
use ondrs\Hi\Hi;
use Tracy\Debugger;

class QuoteService
{

    /** @var Orm */
    private $orm;

    public function __construct(Orm $orm)
    {
        $this->orm = $orm;
    }

    public function newQuote($values)
    {
        if (!isset($values->product)) {
            $values->product = null;
        }
        else{
            $product = $this->orm->products->getById($values->product);
        }
        $quote = new Quote();
        $quote->name = '';//$values->name;
        $quote->surname = '';//$values->surname;
        $quote->email = $values->email;
        $quote->phone = $values->phone;
        $quote->text = $values->message;
        $quote->product = $product;
        $quote->createdAt = date("Y-m-d h:i:sa");
        $this->orm->persistAndFlush($quote);

        $this->sendMails($quote);
    }

    public function sendMails(Quote $quote){

        $latte = new Engine();

        $mail = new Message();

        $mail->setFrom('Chakra.cz <noreply@chakra.cz>')
            ->addTo('anita.bodyfitness@gmail.com')
            ->addTo('chakra.original@gmail.com')
            ->setSubject('Nová zpráva v kontaktním formuláři!')
            ->setHtmlBody($latte->renderToString(__DIR__ . '/../../BackModule/templates/Emails/newQuote.latte', ['quote' => $quote->id]));

        $mailer = new SendmailMailer();
        $mailer->send($mail);
    }
}