<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

use App\Models\Person;

class PeopleController extends Controller
{
    public function index(){
        return view('management.people.browse');
    }

    public function list($search = null){
        $paginate = request('paginate') ?? 10;
        $data = Person::with(['city'])
                    ->where(function($query) use ($search){
                        if($search){
                            $query->OrwhereHas('city', function($query) use($search){
                                $query->whereRaw("name like '%$search%'");
                            })
                            ->OrWhereRaw($search ? "id = '$search'" : 1)
                            ->OrWhereRaw($search ? "first_name like '%$search%'" : 1)
                            ->OrWhereRaw($search ? "last_name like '%$search%'" : 1)
                            ->OrWhereRaw($search ? "ci like '%$search%'" : 1)
                            ->OrWhereRaw($search ? "phone like '%$search%'" : 1);
                        }
                    })
                    ->where('deleted_at', NULL)->orderBy('id', 'DESC')->paginate($paginate);
        return view('management.people.list', compact('data'));
    }
}
