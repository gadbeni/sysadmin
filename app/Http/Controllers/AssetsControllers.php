<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

// Models
use App\Models\AssetsSubcategory;
use App\Models\Asset;

class AssetsControllers extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->custom_authorize('browse_assets');
        return view('assets.browse');
    }

    public function list(){
        $this->custom_authorize('browse_assets');
        $search = request('search') ?? null;
        $status = request('status') ?? null;
        $user_id = request('user_id') ?? null;
        $direccion_administrativa_id = request('direccion_administrativa_id') ?? null;
        $paginate = request('paginate') ?? 10;
        $data = Asset::with(['user', 'subcategory.category', 'assignments.person_asset.person', 'assignments' => function($q){
                        $q->where('active', 1);
                    }])
                    ->whereRaw($user_id ? "user_id = ".$user_id : 1)
                    ->where(function($query) use ($search){
                        if($search){
                            $query->OrwhereHas('subcategory', function($query) use($search){
                                $query->whereRaw("name like '%$search%'");
                            })
                            ->OrwhereHas('subcategory.category', function($query) use($search){
                                $query->whereRaw("name like '%$search%'");
                            })
                            ->OrwhereHas('assignments.person_asset.person', function($query) use($search){
                                $query->whereRaw("(first_name like '%$search%' or last_name like '%$search%' or ci like '%$search%' or phone like '%$search%' or CONCAT(first_name, ' ', last_name) like '%$search%')");
                            })
                            ->OrWhereRaw("id = '$search'")
                            ->orWhereRaw("code like '%$search%'")
                            ->orWhereRaw("code_siaf like '%$search%'")
                            ->orWhereRaw("code_internal like '%$search%'")
                            ->orWhereRaw("tags like '%$search%'")
                            ->orWhereRaw("description like '%$search%'")
                            ->orWhereRaw("observations like '%$search%'");
                        }
                    })
                    // ->where(function($query) use ($addendums){
                    //     if($addendums){
                    //         $query->OrwhereHas('addendums', function($query){
                    //             $query->whereRaw("1");
                    //         });
                    //     }
                    // })
                    // ->where('deleted_at', NULL)
                    ->orderBy('id', 'DESC')->paginate($paginate);
        return view('assets.list', compact('data', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->custom_authorize('add_assets');
        return view('assets.edit-add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validar que el PB no exista
        $code = Str::lower($request->code);
        if (Asset::where('code', $code)->first()) {
            return redirect()->route('asset.index')->with(['message' => 'El código ingresado ya existe', 'alert-type' => 'error']);
        }

        DB::beginTransaction();
        $images = [];
        if($request->images){
            foreach ($request->images as $item) {
                $image = $this->store_image($item, 'assets', 1000);
                if ($image) {
                    array_push($images, $image);
                }
            }
        }
        try {
            $asset = Asset::create([
                // Diferenciar si la categoría existía o la crearon
                'assets_subcategory_id' => is_numeric($request->assets_subcategory_id) ? $request->assets_subcategory_id : AssetsSubcategory::create(['assets_category_id' => $request->assets_category_id, 'name' => Str::upper($request->assets_subcategory_id)])->id,
                'city_id' => $request->city_id,
                'user_id' => Auth::user()->id,
                'code' => Str::upper($code),
                'code_siaf' => $request->code_siaf,
                'code_internal' => $request->code_internal,
                'tags' => $request->tags,
                'description' => $request->description,
                'initial_price' => $request->initial_price,
                'current_price' => $request->current_price,
                'address' => $request->address,
                'location' => $request->location,
                'observations' => $request->observations,
                'images' => json_encode($images),
                'status' => $request->status
            ]);
            DB::commit();
            return redirect()->route('assets.index')->with(['message' => 'Activo guardado exitosamente', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            return redirect()->route('assets.index')->with(['message' => 'Ocurrió un error', 'alert-type' => 'error']);
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
        $this->custom_authorize('read_assets');
        $asset = Asset::findOrFail($id);
        return view('assets.read', compact('asset'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->custom_authorize('edit_assets');
        $asset = Asset::findOrFail($id);
        return view('assets.edit-add', compact('asset'));
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
        DB::beginTransaction();
        try {
            $asset = Asset::findOrFail($id);
            
            $images = json_decode($asset->images);
            if($request->images){
                foreach ($request->images as $item) {
                    $image = $this->store_image($item, 'assets', 1000);
                    if ($image) {
                        array_push($images, $image);
                    }
                }
            }
            
            // Diferenciar si la categoría existía o la crearon
            $asset->assets_subcategory_id = is_numeric($request->assets_subcategory_id) ? $request->assets_subcategory_id : AssetsSubcategory::create(['assets_category_id' => $request->assets_category_id, 'name' => Str::upper($request->assets_subcategory_id)])->id;
            $asset->city_id = $request->city_id;
            $asset->code_siaf = $request->code_siaf;
            $asset->code_internal = $request->code_internal;
            $asset->tags = $request->tags;
            $asset->description = $request->description;
            $asset->initial_price = $request->initial_price;
            $asset->current_price = $request->current_price;
            $asset->address = $request->address;
            $asset->location = $request->location;
            $asset->observations = $request->observations;
            $asset->images = json_encode($images);
            $asset->status = $request->status;
            $asset->update();

            DB::commit();

            return redirect()->route('assets.index')->with(['message' => 'Activo actualizado exitosamente', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            // throw $th;
            return redirect()->route('assets.index')->with(['message' => 'Ocurrió un error', 'alert-type' => 'error']);
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
        try{
            $asset = Asset::findOrFail($id);
            // Verificar si el activo ya ha sido asignado a un funcionario antes
            if($asset->assignments->count()){
                return redirect()->route('assets.index')->with(['message' => 'El activo tiene historial de asiganción', 'alert-type' => 'error']);
            }
            $asset->code = $asset->code.'-'.Carbon::now();
            // Primero actualizamos el campo code para podes reusar el PB que tenía
            $asset->update();
            
            $asset->delete();
            return redirect()->route('assets.index')->with(['message' => 'Activo eliminado exitosamente', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            DB::rollback();
            // throw $th;
            return redirect()->route('assets.index')->with(['message' => 'Ocurrió un error', 'alert-type' => 'error']);
        }
    }

    public function image_delete(Request $request){
        try {
            $asset = Asset::find($request->id);
            $images = json_decode($asset->images);
            $new_images = [];
            foreach ($images as $item) {
                if($item != $request->image){
                    array_push($new_images, $item);
                }
            }
            $asset->images = $new_images;
            $asset->update();

            return redirect()->route('assets.edit', $request->id)->with(['message' => 'Imagen eliminada exitosamente', 'alert-type' => 'success']);
        } catch (\Throwable $th) {
            // throw $th;
            return redirect()->route('assets.edit', $request->id)->with(['message' => 'Ocurrió un error', 'alert-type' => 'error']);
        }
    }

    // Métodos funcionales
    
    public function search(){
        $search = request('search');
        $assets = Asset::with(['user', 'subcategory.category'])
                    ->whereHas('assignments',function($q) {
                        $q->where('active', 1);
                    }, '<', 1)
                    ->where(function($query) use ($search){
                        $query->OrwhereHas('subcategory', function($query) use($search){
                            $query->whereRaw("name like '%$search%'");
                        })
                        ->OrwhereHas('subcategory.category', function($query) use($search){
                            $query->whereRaw("name like '%$search%'");
                        })
                        ->orWhereRaw("code like '%$search%'")
                        ->orWhereRaw("code_siaf like '%$search%'")
                        ->orWhereRaw("code_internal like '%$search%'")
                        ->orWhereRaw("tags like '%$search%'")
                        ->orWhereRaw("description like '%$search%'")
                        ->orWhereRaw("observations like '%$search%'");
                    })
                    ->where('deleted_at', NULL)->get();
        return response()->json($assets);
    }

    public function search_by_code(){
        $code = request('code');
        return response()->json(Asset::where('code', Str::lower($code))->withTrashed()->first());
    }
}
