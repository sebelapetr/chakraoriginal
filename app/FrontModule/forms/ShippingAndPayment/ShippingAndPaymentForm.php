<?php

namespace Skeleton\FrontModule\Forms;

use Skeleton\Model\QuoteService;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Nette\Utils\ArrayHash;
use Tracy\Debugger;

interface IShippingAndPaymentFormFactory{
    /** @return ShippingAndPaymentForm */
    function create($freeDeliveryWeek = false);
}

class ShippingAndPaymentForm extends Control{

    public $freeDeliveryWeek;

    public function __construct($freeDeliveryWeek = false)
    {
        $this->freeDeliveryWeek = $freeDeliveryWeek;
    }

    protected function createComponentShippingAndPaymentForm(){
        $form = new Form();
        $form->addRadioList('shipping', 'Způsob doručení', [
            2=>'Osobní odběr na adrese Komenského 46/4, 678 01 Blansko',
            1=>'Doručení na adresu přepravcem DPD'
        ])
        ->setDefaultValue(2);
        $form->addRadioList('payment', 'Způsob platby', [2=>'Platba převodem na účet', 1=>'Dobírka'])
        ->setDefaultValue(2);
        $form->addSubmit('submit', 'Pokračovat v objednávce')
            ->setDefaultValue('aa');
        $form->onSuccess[] = [$this, 'shippingAndPaymentFormSucceeded'];
        return $form;
    }

    public function shippingAndPaymentFormSucceeded(Form $form, ArrayHash $values){
        $shippingSection = $this->getPresenter()->getSession()->getSection('shipping');
        $paymentSection = $this->getPresenter()->getSession()->getSection('payment');
        $shippingSection->shipping = $values->shipping;
        $paymentSection->payment = $values->payment;
        $this->getPresenter()->redirect('osobniUdaje');

        //$this->getPresenter()->redirect("Homepage:default");
    }
    public function render(){
        $this->getTemplate()->setFile(__DIR__  .  "/../../forms/ShippingAndPayment/ShippingAndPayment.latte");
        $this->getTemplate()->freeDeliveryWeek = $this->freeDeliveryWeek;
        $this->getTemplate()->render();
    }

}