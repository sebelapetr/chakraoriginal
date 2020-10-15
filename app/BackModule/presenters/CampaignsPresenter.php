<?php

namespace Skeleton\BackModule\Presenters;

use Skeleton\BackModule\Forms\IArticleFormFactory;
use Skeleton\FrontModule\Forms\INewQuoteFormFactory;
use Skeleton\Model\Article;
use Skeleton\Model\Orm;

Class CampaignsPresenter extends BasePresenter{
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
        $this->getTemplate()->setFile(__DIR__ . "/../templates/Campaigns/default.latte");
        $this->template->campaigns = $this->orm->campaigns->findAll();
    }

    public function handleSwitchCampaign($id) {
        $campaign = $this->orm->campaigns->getById($id);

        $campaign->active = $campaign->active ? FALSE : TRUE;

        $this->orm->persistAndFlush($campaign);

        $this->redirect('this');
    }

}