<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Prophecy\Doubler\Generator\Node\ReturnTypeNode;
use App\Models\Inbox;
use App\Models\Outbox;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TcInboxController extends Controller
{
    public function index()
    {
        return Inbox::all();
        return view('correspondencia.inbox.browse');
    }
}
