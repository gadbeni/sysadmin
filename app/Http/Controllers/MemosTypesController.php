<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MemosTypesController extends Controller
{
    public function create(){
        return view('finance.person_externals.edit-add');
    }
}
