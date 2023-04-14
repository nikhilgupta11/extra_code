<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Models\Trip;
use App\Models\User;
use Modules\Page\Models\Page;
use Modules\Project\Models\Project;
use Modules\Article\Entities\Post;
class BackendController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $totalUsers = User::where('type',1)->count();
        $totalTrips = Trip::count();
        $totalPages = Page::count();
        $totalProjects = Project::count();
        $totalBlogs = Post::count();
        $totalQueries = Enquiry::count();
        return view('backend.index',compact('totalUsers','totalTrips','totalPages','totalProjects','totalBlogs','totalQueries'));
    }
}
