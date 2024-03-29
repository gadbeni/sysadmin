<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

// Models
use App\Models\Memo;
use App\Models\Contract;
use App\Models\MemosAdditionalPerson;

class MemosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->custom_authorize('browse_memos');
        return view('finance.memos.browse');
    }

    public function list(){
        $paginate = request('paginate') ?? 10;
        $search = request('search') ?? null;
        $data = Memo::where('deleted_at', NULL)
                    ->where(function($query) use ($search){
                        if($search){
                            $query->OrwhereHas('person_external', function($query) use($search){
                                $query->whereRaw("(full_name like '%$search%' or ci_nit like '%$search%')");
                            })
                            ->OrWhereRaw("id = '$search'")
                            ->OrWhereRaw("code like '%$search%'")
                            ->OrWhereRaw("number like '%$search%'")
                            ->OrWhereRaw("amount like '%$search%'")
                            ->OrWhereRaw("concept like '%$search%'")
                            ->OrWhereRaw("imputation like '%$search%'")
                            ->OrWhereRaw("date like '%$search%'");
                        }
                    })
                    ->orderBy('id', 'DESC')->paginate($paginate);
        return view('finance.memos.list', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->custom_authorize('add_memos');
        $contracts = Contract::with(['alternate_job' => function($q){
                            $q->where('status', 1)->where('deleted_at', NULL);
                        }])->where('status', 'firmado')->where('deleted_at', NULL)->get();
        return view('finance.memos.edit-add', compact('contracts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        // dd($request->all());
        try {
            $last_memo = Memo::orderBy('id', 'DESC')->whereRaw(Auth::user()->direccion_administrativa_id ? "direccion_administrativa_id = ".Auth::user()->direccion_administrativa_id : 1)->whereYear('date', $request->date)->first();
            $memo = Memo::create([
                'user_id' => Auth::user()->id,
                'direccion_administrativa_id' => Auth::user()->direccion_administrativa_id,
                'origin_id' => $request->origin_id,
                'origin_alternate_job' => $request->origin_alternate_job,
                'destiny_id' => $request->destiny_id,
                'destiny_alternate_job' => $request->destiny_alternate_job,
                'memos_type_id' => $request->memos_type_id,
                'person_external_id' => $request->person_external_id,
                'type' => $request->type,
                'code' => ($last_memo ? explode('/', $last_memo->code)[0] +1 : '1').'/'.date('Y', strtotime($request->date)),
                'number' => $request->number,
                'da_sigep' => $request->da_sigep,
                'source' => $request->source,
                'amount' => $request->amount,
                'concept' => $request->concept,
                'imputation' => $request->imputation,
                'date' => $request->date
            ]);

            if($request->memos_additional_people_id){
                for ($i=0; $i < count($request->memos_additional_people_id); $i++) { 
                    MemosAdditionalPerson::create([
                        'memo_id' => $memo->id,
                        'person_external_id' => $request->memos_additional_people_id[$i]
                    ]);
                }
            }

            return redirect()->route('memos.index')->with(['message' => 'Memorándum guardado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            dd($th);
            return redirect()->route('memos.index')->with(['message' => 'Ocurrió un error', 'alert-type' => 'error']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->custom_authorize('read_memos');
        $memo = Memo::find($id);
        return view('finance.memos.read', compact('memo'));
    }

    public function print($id)
    {
        $this->custom_authorize('read_memos');
        $memo = Memo::find($id);
        return view('finance.memos.print', compact('memo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->custom_authorize('edit_memos');
        $memo = Memo::with(['additional_person.person_external'])->where('id', $id)->first();
        return view('finance.memos.edit-add', compact('memo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            Memo::where('id', $id)->update([
                'origin_id' => $request->origin_id,
                'origin_alternate_job' => $request->origin_alternate_job,
                'destiny_id' => $request->destiny_id,
                'destiny_alternate_job' => $request->destiny_alternate_job,
                'memos_type_id' => $request->memos_type_id,
                'person_external_id' => $request->person_external_id,
                'type' => $request->type,
                'number' => $request->number,
                'da_sigep' => $request->da_sigep,
                'source' => $request->source,
                'amount' => $request->amount,
                'concept' => $request->concept,
                'imputation' => $request->imputation,
                'date' => $request->date
            ]);
            return redirect()->route('memos.index')->with(['message' => 'Memorándum actualizado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            // dd($th);
            return redirect()->route('memos.index')->with(['message' => 'Ocurrió un error', 'alert-type' => 'error']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->custom_authorize('delete_memos');
    }
}
