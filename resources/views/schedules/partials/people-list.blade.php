
<div class="col-md-9"></div>
<div class="col-md-3">
    <input type="text" id="myInput" class="form-control" onkeyup="myFunction()" placeholder="Buscar...">
</div>
<div class="col-md-12" style="margin-top: 20px">
    <table id="myTable" class="table table-bordered">
        <thead>
            <tr>
                <th>N&deg;</th>
                <th>Funcionario</th>
                <th>Inicio de contrato</th>
                <th class="text-center"><input type="checkbox" id="checkbox-select-all" style="scale: 1.2"></th>
            </tr>
        </thead>
        <tbody>
            @php
                $cont = 1;
            @endphp
            @forelse ($contracts as $item)
                <tr>
                    <td>{{ $cont }}</td>
                    <td>{{ $item->person->first_name }} {{ $item->person->last_name }}</td>
                    <td>{{ $item->start }}</td>
                    <td class="text-center">
                        {{-- <div style="position: absolute; z-index: 0"> --}}
                            <input type="checkbox" name="contract_id[]" value="{{ $item->id }}" style="scale: 1.2">
                        {{-- </div> --}}
                    </td>
                </tr>
                @php
                    $cont++;
                @endphp
            @empty
                <tr>
                    <td colspan="4" class="text-center">No hay resultados</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<style>
    th {
        position: sticky;
        top: 0;
        z-index: 1;
    }
</style>

<script>
    $(document).ready(function(){
        $('#checkbox-select-all').click(function(){
            if($(this).is(':checked')){
                $('.form-submit input[name="contract_id[]"]').prop('checked', true);
            }else{
                $('.form-submit input[name="contract_id[]"]').prop('checked', false);
            }
        });
    });

    function myFunction() {
        // Declare variables 
        var input, filter, table, tr, td, i, j, visible;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("myTable");
        tr = table.getElementsByTagName("tr");

        // Loop through all table rows, and hide those who don't match the search query
        for (i = 0; i < tr.length; i++) {
            if (tr[i].parentNode.tagName != 'THEAD') {
                visible = false;
                /* Obtenemos todas las celdas de la fila, no sólo la primera */
                td = tr[i].getElementsByTagName("td");
                for (j = 0; j < td.length; j++) {
                    if (td[j] && td[j].innerHTML.toUpperCase().indexOf(filter) > -1) {
                        visible = true;
                    }
                }
                if (visible === true) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }   
            }
        }
    }
</script>