<?php

namespace Vanguard\Support\Plugins;

use Vanguard\Plugins\Plugin;
use Vanguard\Support\Sidebar\Item;

class Delivery extends Plugin
{
    public function sidebar(): Item
    {
        return Item::create(__('Delivery'))
            ->route('calendarDelivery.index')
            ->icon('fas fa-calendar')
            ->active('calendarDelivery*')
            ->permissions('delivery');
    }
}
