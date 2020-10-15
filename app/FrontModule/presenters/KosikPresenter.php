<?php

namespace Skeleton\FrontModule\Presenters;

use Skeleton\FrontModule\Forms\INewQuoteFormFactory;
use Skeleton\FrontModule\Forms\IPersonalDataFormFactory;
use Skeleton\Model\Orm;
use Nette\Caching\Storages\FileStorage;
use Nette\Utils\ArrayHash;
use Nette\Utils\DateTime;
use ondrs\Hi\Hi;
use Tracy\Debugger;
use Skeleton\FrontModule\Forms\IShippingAndPaymentFormFactory;

Class KosikPresenter extends BasePresenter{

    /**
     * @var IShippingAndPaymentFormFactory
     */
    public  $shippingAndPaymentFormFactory;

    /**
     * @var IPersonalDataFormFactory
     */
    public  $personalDataFormFactory;


    public function __construct(Orm $orm, IShippingAndPaymentFormFactory $shippingAndPaymentFormFactory, IPersonalDataFormFactory $personalDataFormFactory)
    {
        parent::__construct($orm);
        $this->shippingAndPaymentFormFactory = $shippingAndPaymentFormFactory;
        $this->personalDataFormFactory = $personalDataFormFactory;
    }

    public function renderDefault(){
        $this->redirect('dopravaAPlatba');
    }

    public function renderDopravaAPlatba(){
        $this->getTemplate()->setFile(__DIR__ . "/../templates/Kosik/dopravaAPlatba.latte");
        $this->getTemplate()->cartCheck = 1;
        $this->getTemplate()->productsInCart = $this->getProductsInCart();
        $this->getTemplate()->totalPrice = $this->getTotalPrice();

    }

    public function renderOsobniUdaje(){
        $this->getTemplate()->setFile(__DIR__ . "/../templates/Kosik/osobniUdaje.latte");
        $this->getTemplate()->cartCheck = 1;
        $this->getTemplate()->productsInCart = $this->getProductsInCart();
        $this->getTemplate()->totalPrice = $this->getTotalPrice();
        $this->getTemplate()->shipping = $this->getShipping();
        $this->getTemplate()->payment = $this->getPayment();
    }

    public function renderDokoncenaObjednavka($id = null){
        $id = str_replace('8452', '', $id);
        $order = $this->orm->orders->getById(base64_decode($id));
        $deliveryDate = DateTime::from($order->createdAt);
        if ($deliveryDate->format("N") == 5) {
            $days = 6;
        } elseif ($deliveryDate->format("N") == 6) {
            $days = 5;
        } elseif ($deliveryDate->format("N") == 7) {
            $days = 4;
        }
        else {
            $days = 4;
        }
        $deliveryDate->modify('+'.$days.' days');
        $this->getTemplate()->setFile(__DIR__ . "/../templates/Kosik/dokoncenaObjednavka.latte");
        $this->getTemplate()->order = $order;

        $total = 0;
        foreach ($order->ordersItems as $ordersItem) {
            $total += $ordersItem->priceVat;
        }
        $this->template->total = $total;
        $this->getTemplate()->deliveryDate = $deliveryDate;

    }

    public function getProductsInCart(){
        $productsSession = $this->getSession()->getSection('products');
        return $productsSession;
    }

    public function getShipping(){
        $productsSession = $this->getSession()->getSection('shipping');
        return $productsSession;
    }

    public function getPayment(){
        $productsSession = $this->getSession()->getSection('payment');
        return $productsSession;
    }

    public function getTotalPrice(){
        $productsSession = $this->getSession()->getSection('products');
        $shippingSession = $this->getSession()->getSection('shipping');
        $paymentSession = $this->getSession()->getSection('payment');
        $totalPrice = 0;
        foreach ($productsSession as $product){
            $totalPrice += $product['catalogPriceVat']*$product['quantity'];
        }
        $shipping = ($shippingSession->shipping==1?'120':'0');
        $payment = ($paymentSession->payment==1?'30':'0');
        $totalPrice += $shipping;
        $totalPrice += $payment;
        return $totalPrice;
    }


    public function handleAddProductNumber($id){
        $productsSession = $this->getSession()->getSection('products');
        $data = $productsSession[$id];
        $data['quantity']++;
        $productsSession[$id] = $data;
        $this->redirect('this');
    }

    public function handleRemoveProductNumber($id){
        $productsSession = $this->getSession()->getSection('products');
        $data = $productsSession[$id];
        if ($data['quantity']>1) {
            $data['quantity']--;
            $productsSession[$id] = $data;
        }
        else{
            $this->handleRemoveProduct($id);
        }
        $this->redirect('this');
    }

    public function handleClear(){
    }

    public function handleRemoveProduct($id){
        $productsSession = $this->getSession()->getSection('products');
        unset($productsSession[$id]);
    }

    public function createComponentShippingAndPaymentForm()
    {
        return $this->shippingAndPaymentFormFactory->create($this->campaigns);
    }

    public function createComponentPersonalDataForm()
    {
        return $this->personalDataFormFactory->create($this->campaigns);
    }
}