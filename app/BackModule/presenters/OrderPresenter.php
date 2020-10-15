<?php

namespace Skeleton\BackModule\Presenters;

use Skeleton\BackModule\Forms\IOrdersFormFactory;
use Skeleton\BackModule\Forms\IQuotesFormFactory;
use Skeleton\Model\Orm;
use Tracy\Debugger;

class OrderPresenter extends BasePresenter{

    /** @var IOrdersFormFactory */
    public  $ordersFormFactory;

    /** @var int */
    public $quoteId;

    public function __construct(Orm $orm, IOrdersFormFactory $ordersFormFactory)
    {
        parent::__construct($orm);
        $this->ordersFormFactory = $ordersFormFactory;
    }

    public function renderDefault()
    {
        $this->getTemplate()->setFile(__DIR__ . "/../templates/Order/default.latte");
        $this->getTemplate()->orders = $this->orm->orders->findBy(['visible' => true])->orderBy('createdAt', 'DESC');
    }

    public function actionDetail($id) {
        $order = $this->orm->orders->getById($id);
        if (!$order->visible) {
            $this->redirect('default');
        }
    }

    public function renderDetail($id){
        $order = $this->orm->orders->getById($id);
        $this->getTemplate()->setFile(__DIR__ . "/../templates/Order/detail.latte");
        $this->getTemplate()->order = $order;
    }

    public function createComponentOrdersForm()
    {
        return $this->ordersFormFactory->create();
    }

    public function handleChangeState($id){
        $order = $this->orm->orders->getById($id);
        $order->state++;
        $order->createdAt = $order->createdAt;
        $this->orm->persistAndFlush($order);
        $this->redirect('this');
    }

    public function handleDelete($id)
    {
        $order = $this->orm->orders->getById($id);
        $order->visible = false;
        $this->orm->persistAndFlush($order);
    }

}