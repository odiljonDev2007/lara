<?php

namespace Vanguard\Support\Plugins;

use Vanguard\Plugins\Plugin;
use Vanguard\Support\Sidebar\Item;

class FinanceDelivery extends Plugin
{
    public function sidebar(): Item
    {
        return Item::create(__('FinanceDelivery'))
            ->route('financeDelivery.index')
            ->icon('fas fa-ruble-sign')
            ->active('financeDelivery*')
            ->permissions('financeDelivery');
    }
}
