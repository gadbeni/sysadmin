<select
    class="form-control select2"
    name="{{ $row->field }}"
    data-name="{{ $row->display_name }}"
    @if($row->required == 1) required @endif
>
    @foreach(\App\Models\DireccionAdministrativa::all() as $direccion)
    <option @if($dataTypeContent->{$row->field} == $direccion->ID) selected @endif value="{{ $direccion->ID }}">{{ $direccion->NOMBRE }}</option>
    @endforeach
</select>