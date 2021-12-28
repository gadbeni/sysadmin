<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PluginsController extends Controller
{
    public function cashiers_tickets(){
        return view('vendor.voyager.cashiers.tickets-browse');
    }

    public function cashiers_tickets_generate(){
        return view('vendor.voyager.cashiers.tickets-generate');
    }

    public function cashiers_tickets_print(Request $request){
        $start = $request->start;
        $finish = $request->finish;
        return view('vendor.voyager.cashiers.tickets-print', compact('start', 'finish'));
    }
}
