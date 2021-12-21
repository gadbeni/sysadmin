<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PluginsController extends Controller
{
    public function cashiers_tickets(){
        return view('vendor.voyager.cashiers.tickets-browse');
    }
}
