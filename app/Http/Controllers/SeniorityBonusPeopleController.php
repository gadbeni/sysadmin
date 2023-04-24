<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Models
use App\Models\SeniorityBonusPerson;

class SeniorityBonusPeopleController extends Controller
{
    public function index(){
        $data = SeniorityBonusPerson::with(['type', 'user', 'person.contracts.type', 'person.contracts' => function($q){
            $q->where('status', 'firmado');
        }])->whereHas('person', function($q){
            $q->where('deleted_at', NULL);
        })->where('deleted_at', NULL)->get();
        return view('management.seniority-bonus.browse', compact('data'));
    }
}
