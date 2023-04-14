<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function index(){
        return view("welcome");
    }
    public function store(Request $request){
        $request->validate([
            'email' => 'required|unique:contact|max:255',
        ]);
        $contact = new Contact();
        $contact->email = $request->email;
        $contact->save();
        return back()->with('success',  __('Thanks for Subscribing! We\'ll Notify You Soon.') );
    }
}
