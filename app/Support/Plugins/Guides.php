<?php

namespace Vanguard\Support\Plugins;

use Vanguard\Plugins\Plugin;
use Vanguard\Support\Sidebar\Item;
use Vanguard\User;

class Guides extends Plugin
{
    public function sidebar(): Item
    {
        /*
        return Item::create(__('Guides'))
            ->route('guideTransport.index')
            ->icon('fas fa-users-cog')
            ->active('guideTransport*')
            ->permissions('guide');
        */

        $guideTransport = Item::create(__('GuideTransports'))
            ->route('guideTransport.index')
            ->active('guideTransport')
            ->permissions('GuideTransport');

        return Item::create(__('Guides'))
            ->href('#guideTransport-dropdown')
            ->icon('fas fa-users-cog')
            ->permissions(function (User $user) {
                return $user->hasPermission(
                    ['guide', 'GuideTransport'],
                    allRequired: false
                );
            })
            ->addChildren([
                $guideTransport,
            ]);
    }
}
