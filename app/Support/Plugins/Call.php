<?php

namespace Vanguard\Support\Plugins;

use Vanguard\Plugins\Plugin;
use Vanguard\Support\Sidebar\Item;

class Call extends Plugin
{
    public function sidebar(): Item
    {
        return Item::create(__('Call'))
            ->route('calendarCall.index')
            ->icon('fas fa-phone')
            ->active('calendarCall*')
            ->permissions('call');
    }
}
