<?php

namespace Modules\Project\Http\Middleware;

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

            // Projects
            $menu->add('<i class="nav-icon fa fa-list"></i> '.__('Projects'), [
                'route' => 'backend.projects.index',
                'class' => 'nav-item',
            ])
            ->data([
                'order'         => 89,
                'activematches' => ['admin/projects*'],
            ])
            ->link->attr([
                'class' => 'nav-link',
            ]);
        })->sortBy('order');

        return $next($request);
    }
}
