<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

// Models
use App\Models\Memo;

class MemosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('finance.memos.browse');
    }

    public function list(){
        $paginate = request('paginate') ?? 10;
        $data = Memo::where('deleted_at', NULL)->orderBy('id', 'DESC')->paginate($paginate);
        return view('finance.memos.list', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('finance.memos.edit-add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        try {
            $last_memo = Memo::orderBy('id', 'DESC')->first();
            Memo::create([
                'user_id' => Auth::user()->id,
                'origin_id' => $request->origin_id,
                'origin_alternate_job' => $request->origin_alternate_job,
                'destiny_id' => $request->destiny_id,
                'destiny_alternate_job' => $request->destiny_alternate_job,
                'memos_type_id' => $request->memos_type_id,
                'person_external_id' => $request->person_external_id,
                'type' => $request->type,
                'number' => ($last_memo ? explode('/', $last_memo->number)[0] +1 : 1).'/'.date('Y', strtotime($request->date)),
                'code' => $request->code,
                'da_sigep' => $request->da_sigep,
                'source' => $request->source,
                'amount' => $request->amount,
                'concept' => $request->concept,
                'imputation' => $request->imputation,
                'date' => $request->date
            ]);
            return redirect()->route('memos.index')->with(['message' => 'Memorándum guardado exitosamente.', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            // dd($th);
            return redirect()->route('memos.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
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
        //
    }

    public function print($id)
    {
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
        $memo = Memo::find($id);
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
                'code' => $request->code,
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
            return redirect()->route('memos.index')->with(['message' => 'Ocurrió un error.', 'alert-type' => 'error']);
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
        //
    }
}
