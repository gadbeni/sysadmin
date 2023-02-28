@extends('voyager::master')

@section('page_title', 'Añadir Beneficiarios')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-people"></i>
        Añadir Beneficiarios
    </h1>
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <form id="form-submit" action="{{ route('voyager.person-externals.store') }}" method="post">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-bordered">
                        <div class="panel-body">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="person_external_type_id ">Tipo</label>
                                    <select name="person_external_type_id " class="form-control select2" required>
                                        <option value="">-- Seleccione el tipo --</option>
                                        @foreach (App\Models\PersonExternalType::where('status', 1)->where('deleted_at', NULL)->get() as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="person_id">Funcionario</label>
                                    <select name="person_id" id="select-person_id" class="form-control select2">
                                        <option value="">-- Seleccione funcionario --</option>
                                        @foreach (App\Models\Person::where('deleted_at', NULL)->orderBy('last_name')->get() as $item)
                                        <option value="{{ $item->id }}" data-item='@json($item)'>{{ $item->last_name }} {{ $item->first_name }} - CI: {{ $item->ci }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="full_name">Nombre completo</label>
                                    <input type="text" name="full_name" class="form-control" placeholder="Nombre completo" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="ci_nit">CI/NIT</label>
                                    <input type="text" name="ci_nit" class="form-control" placeholder="CI/NIT" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="number_acount">N&deg; de cuenta</label>
                                    <input type="text" name="number_acount" class="form-control" placeholder="N&deg; de cuenta">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="phone">Celular</label>
                                    <input type="text" name="phone" class="form-control" placeholder="Celular">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" class="form-control" placeholder="Email">
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="address">Dirección</label>
                                    <textarea name="address" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer text-right">
                            <button type="submit" class="btn btn-primary save">Guardar <i class="voyager-check"></i> </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop

@section('css')
    
@stop

@section('javascript')
    <script>
        $(document).ready(function(){
            $('#select-person_id').change(function(){
                let item = $('#select-person_id option:selected').data('item');
                if(item){
                    $('#form-submit input[name="full_name"]').val(`${item.first_name} ${item.last_name}`);
                    $('#form-submit input[name="ci_nit"]').val(item.ci);
                    $('#form-submit input[name="phone"]').val(item.phone ? item.phone : '');
                    $('#form-submit input[name="email"]').val(item.email ? item.email : '');
                    $('#form-submit textarea[name="address"]').val(item.address ? item.address : '');
                }else{
                    $('#form-submit input[name="full_name"]').val('');
                    $('#form-submit input[name="ci_nit"]').val('');
                    $('#form-submit input[name="phone"]').val('');
                    $('#form-submit input[name="email"]').val('');
                    $('#form-submit textarea[name="address"]').val('');
                }
            });
        });
    </script>
@stop
