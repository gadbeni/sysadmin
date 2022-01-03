<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PluginsController extends Controller
{
    public function cashiers_tickets(){
        $videos = Storage::files('pantalla/videos');
        return view('vendor.voyager.cashiers.tickets-browse', compact('videos'));
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
