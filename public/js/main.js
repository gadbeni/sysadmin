function customDataTable(url, columns = []){
    var data_table = $('#dataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: url,
        columns,
        order: [[ 0, "desc" ]],
        language: {
            sProcessing: "Procesando...",
            sLengthMenu: "Mostrar _MENU_ registros",
            sZeroRecords: "No se encontraron resultados",
            sEmptyTable: "Ningún dato disponible en esta tabla",
            sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
            sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
            sSearch: "Buscar:",
            sInfoThousands: ",",
            sLoadingRecords: "Cargando...",
            oPaginate: {
                sFirst: "Primero",
                sLast: "Último",
                sNext: "Siguiente",
                sPrevious: "Anterior"
            },
            oAria: {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            },
            buttons: {
                copy: "Copiar",
                colvis: "Visibilidad"
            }
        }
    });
}

function deleteItem(url){
    $('#delete_form').attr('action', url);
}

function getPlanillas(url){
    $('#select-checks_beneficiary_id').val('').trigger('change');
    $.post(
        url,
        {t_planilla: $('#select-t_planilla').val(), periodo: $('#input-periodo').val(), afp: $('#select-afp').val()},
        function(res){
            let total = 0;
            let people = 0;
            res.planilla.map(item => {
                total += parseFloat(item.Total_Ganado);
                people++;
            })
            planillaSelect = {
                total_ganado: total
            }

            $('#alert-details').fadeIn();
            $('#alert-details').html(`
                Cantidad de personas: <b>${people}</b> - Monto total: <b>${total.toFixed(2)}</b>
            `);
        }
    );
}