<?php

namespace App;

use Nette;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;


final class RouterFactory
{
	use Nette\StaticClass;

	/**
	 * @return Nette\Application\IRouter
	 */
	public static function createRouter()
	{
		$router = new RouteList;

        $router[] = $routerBack = new RouteList("Back");
        $routerBack[] = new Route('admin/<presenter>/<action>[/<id>]', 'Login:default');

        $router[] = $routerCron = new RouteList("Cron");
        $routerCron[] = new Route('cron/<presenter>/<action>[/<id>]', 'Feeder:default');

        $router[] = $routerFront = new RouteList("Front");

        $routerFront[] = new Route('/veterinarni-vybaveni/<action>[/<id>]', [
            'presenter' => 'VeterinarniVybaveni',
            'action' => 'default'
        ]);

        /*
        $routerFront[] = new Route('/produkt/<action>[/<id>]', [
            'presenter' => 'Product',
            'action' => 'default'
        ]);
        */

        $routerFront[] = new Route('<presenter>/<action>[/<id>]', 'UvodniStrana:default');

		$router[] = new Route('<presenter>/<action>[/<id>]', 'UvodniStrana:default');

		return $router;
	}
}
