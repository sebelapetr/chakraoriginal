<?php
namespace Skeleton\Model;
use Nextras\Orm\Model\Model;
use Skeleton\BackModule\Presenters\CampaignsPresenter;

/**
 * Model
 * @property-read CategoriesRepository $categories
 * @property-read ProductsRepository $products
 * @property-read CategoryParentsRepository $categoryParents
 * @property-read UsersRepository $users
 * @property-read QuotesRepository $quotes
 * @property-read OrdersRepository $orders
 * @property-read OrdersItemsRepository $ordersItems
 * @property-read NewslettersRepository $newsletters
 * @property-read ArticleCategoriesRepository $articleCategories
 * @property-read ArticlesRepository $articles
 * @property-read EventsRepository $events
 * @property-read CampaignsRepository $campaigns
 */
class Orm extends Model
{
}