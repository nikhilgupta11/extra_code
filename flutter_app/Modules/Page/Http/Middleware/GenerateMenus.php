<?php

namespace Modules\Page\Http\Middleware;

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

            // Pages
            $menu->add('<i class="nav-icon far fa-file"></i> '.__('Pages'), [
                'route' => 'backend.pages.index',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 90,
                'activematches' => ['admin/pages*'],
            ])
            ->link->attr([
                'class' => 'nav-link',
            ]);
        })->sortBy('order');

        return $next($request);
    }
}
