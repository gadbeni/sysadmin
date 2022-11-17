<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Models
use App\Models\Donation;
use App\Models\PersonExternal;

class DonationsController extends Controller
{
    public function store(Request $request){
        DB::beginTransaction();
        try {
            $person = PersonExternal::where('ci_nit', $request->ci_nit)->first();
            if(!$person){
                $person = PersonExternal::create([
                    'person_external_type_id' => $request->person_external_type_id ?? null,
                    'full_name' => $request->full_name,
                    'ci_nit' => $request->ci_nit,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'email' => $request->email,
                    'observations' => $request->observations
                ]);
            }
            Donation::create([
                'donations_type_id' => intval($request->donations_type_id) ? $request->donations_type_id : null,
                'person_external_id' => $person->id,
                'description' => $request->description
            ]);
            
            DB::commit();

            return response()->json(['success' => 1]);
        } catch (\Throwable $th) {
            DB::rollback();
            // dd($th);
            return response()->json(['error' => 1]);
        }
    }
}
