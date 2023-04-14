<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Request;

class GenerateMenus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        \Menu::make('admin_sidebar', function ($menu) {
            // Dashboard
            $menu->add('<i class="nav-icon cil-speedometer"></i> '.__('Dashboard'), [
                'route' => 'backend.dashboard',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 1,
                'activematches' => 'admin/dashboard*',
            ])
            ->link->attr([
                'class' => 'nav-link',
            ]);

            $menu->add('<i class="nav-icon fa fa-hammer"></i> Form Builder', [
                'route' => 'backend.formbuilder.index',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 80,
                'activematches' => 'admin/formbuilder*',
            ])
            ->link->attr([
                'class' => 'nav-link',
            ]);

            // Trips
            $menu->add('<i class="nav-icon fa fa-road"></i> Trips', [
                'route' => 'backend.trips.index',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 85,
                'activematches' => 'admin/trips*',
                'permission'    => [],
            ])
            ->link->attr([
                'class' => 'nav-link',
            ]);

            // Trip Calculations
            $menu->add('<i class="nav-icon fa fa-percent"></i> Calculations', [
                'route' => 'backend.calculations.index',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 86,
                'activematches' => 'admin/calculations*',
                'permission'    => [],
            ])
            ->link->attr([
                'class' => 'nav-link',
            ]);

            $menu->add('<i class="nav-icon fa fa-tag"></i> Coupons', [
                'route' => 'backend.coupons',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 89,
                'activematches' => 'admin/coupons*',
                'permission'    => [],
            ])
            ->link->attr([
                'class' => 'nav-link',
            ]);

            // Donations
            $menu->add('<i class="nav-icon fa fa-hand-holding-dollar"></i> Donations', [
                'route' => 'backend.donations.index',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 89,
                'activematches' => 'admin/donations*',
                'permission'    => [],
            ])
            ->link->attr([
                'class' => 'nav-link',
            ]);

            // Settings
            $menu->add('<i class="nav-icon fa fa-cogs"></i> Settings', [
                'route' => 'backend.settings',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 106,
                'activematches' => 'admin/settings*',
            ])
            ->link->attr([
                'class' => 'nav-link',
            ]);

            // Access Control Dropdown
            $menu->add('<i class="nav-icon cil-people"></i> Users', [
                'route' => 'backend.users.index',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 105,
                'activematches' => 'admin/users*',
            ])
            ->link->attr([
                'class' => 'nav-link',
            ]);

            // Contact Enquiries
            $menu->add('<i class="nav-icon cil-clipboard"></i> Contact Enquiries', [
                'route' => 'backend.enquiries.index',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 105,
                'activematches' => 'admin/enquiries*',
            ])
            ->link->attr([
                'class' => 'nav-link',
            ]);

            // Set Active Menu
            $menu->filter(function ($item) {
                if ($item->activematches) {
                    $activematches = (is_string($item->activematches)) ? [$item->activematches] : $item->activematches;
                    foreach ($activematches as $pattern) {
                        if (request()->is($pattern)) {
                            $item->active();
                            $item->link->active();
                            if ($item->hasParent()) {
                                $item->parent()->active();
                            }
                        }
                    }
                }

                return true;
            });
        })->sortBy('order');

        return $next($request);
    }
}
