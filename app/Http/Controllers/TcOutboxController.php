<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Outbox;
use Illuminate\Support\Facades\DB;

class TcOutboxController extends Controller
{
    public function index()
    {
        return view('correspondencia.outbox.browse');
    }

    public function create()
    {

        return view('correspondencia.outbox.add-edit');        
    }
}
