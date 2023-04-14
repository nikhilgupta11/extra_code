<?php

namespace Modules\Accommodation\Http\Middleware;

use Closure;

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
        /*
         *
         * Module Menu for Admin Backend
         *
         * *********************************************************************
         */
        \Menu::make('admin_sidebar', function ($menu) {

            // Accommodations
            $menu->add('<i class="nav-icon far fa-building"></i> '.__('Accommodations'), [
                'route' => 'backend.accommodations.index',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 86,
                'activematches' => ['admin/accommodations*'],
            ])
            ->link->attr([
                'class' => 'nav-link',
            ]);
        })->sortBy('order');

        return $next($request);
    }
}
