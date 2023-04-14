<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UsersIndex extends Component
{
    use WithPagination;

    public $searchTerm;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $searchTerm = '%'.$this->searchTerm.'%';
        // $active = "active";
        // $inactive = "inactive";
        // $pattern = "/$searchTerm/i";
        // if(preg_match($pattern, $active)){
        //     $searchTerm = 1;
        // }else if(preg_match($pattern, $inactive)){
        //     $searchTerm = 0;
        // }else{
        //     $searchTerm = $searchTerm;
        // }
        // echo $searchTerm;
        $users = User::where('type',1)->where(function($query)use($searchTerm){
            $query->where('name', 'like', $searchTerm)->orWhere('email', 'like', $searchTerm)->orWhere('status', 'LIKE', $searchTerm);
        })->orderBy('id', 'desc')->with(['providers'])->paginate();

        return view('livewire.users-index', compact('users'));
    }
}
