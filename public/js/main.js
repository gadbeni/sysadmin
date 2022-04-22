const language = {
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

function customDataTable(url, columns = [], order = 0, orderBy = 'desc'){
    $('#dataTable').DataTable({
        processing: true,
        serverSide: true,
        searchDelay : 1000,
        ajax: url,
        columns,
        order: [[ order, orderBy ]],
        language
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
            let total_ganado = 0;
            let total_aportes_afp = 0;
            let total_riesgo_comun = 0;
            let people = 0;
            let periodo = '';
            
            if(res.planilla.length){
                res.planilla.map(item => {
                    total_ganado += parseFloat(item.Total_Ganado);
                    total_aportes_afp += parseFloat(item.Total_Aportes_Afp);
                    total_riesgo_comun += parseFloat(item.Riesgo_Comun);
                    people++;
                    periodo = item.Periodo;
                });
                $('#form input[name="afp_alt"]').val('');
            }else{
                res.paymentschedule_details.map(item => {
                    total_ganado += parseFloat(item.partial_salary + item.seniority_bonus_amount);
                    total_aportes_afp += parseFloat(item.labor_total);
                    total_riesgo_comun += parseFloat(item.common_risk);
                    people++;
                    periodo = item.paymentschedule.period.name;
                    $('#form input[name="afp_alt"]').val(item.contract.person.afp);
                })
            }
            
            planillaSelect = {
                total_ganado, total_aportes_afp, total_riesgo_comun
            }

            $('#alert-details').fadeIn();
            $('#alert-details').html(`
                Cantidad de personas: <b>${people}</b> - Monto total: <b>${total_ganado.toFixed(2)}</b>
            `);
            $('#alert-details').html(`
                Cantidad de personas: <b>${people}</b> <br>
                Periodo: <b>${periodo}</b> <br>
                Monto total: <b>${new Intl.NumberFormat('es-ES').format(total_ganado.toFixed(2))} Bs.</b>
            `);
        }
    );
}

function checkId(){
    let ids = [];
    $('#form-selection-multiple input[name="id[]"]:checked').each(function() {
        ids.push($(this).val());
    });
    if (ids.length > 0) {
        $('.btn-multiple').fadeIn();
    } else {
        $('.btn-multiple').fadeOut();
    }
}