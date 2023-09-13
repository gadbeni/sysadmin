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
        {t_planilla: $('#select-t_planilla').val(), periodo: $('#input-periodo').val(), afp: $('#select-afp').val(), centralize_code: $('#select-centralize_code').val()},
        function(res){
            // console.log(res)
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
                    total_ganado += parseFloat(item.partial_salary) + parseFloat(item.seniority_bonus_amount);
                    total_aportes_afp += parseFloat(item.labor_total);
                    total_riesgo_comun += parseFloat(item.common_risk);
                    people++;
                    periodo = item.paymentschedule.period.name;
                    $('#form input[name="afp_alt"]').val(item.afp);
                });

                $('#select-centralize_code').html('<option value="">Todos</option>');
                Object.keys(res.paymentschedule_details_group).map(item => {
                    $('#select-centralize_code').append(`<option ${res.centralize_code == [item] ? 'selected' : ''} value="${[item]}">${[item]}</option>`);
                });
            }
            
            planillaSelect = {
                total_ganado, total_aportes_afp, total_riesgo_comun
            }

            $('#alert-details').fadeIn();
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

function customSelect(select, url, templateResult, templateSelection, dropdownParent){
    $(select).select2({
        dropdownParent: dropdownParent ? dropdownParent : null,
        maximumSelectionLength: 20,
        ajax: { 
            allowClear: true,
            url,
            type: "get",
            dataType: 'json',
            delay: 500,
            data:  (params) =>  {
                var query = {
                    search: params.term
                }
                return query;
            },
            processResults: function (response) {
                return {
                    results: response
                };
            }
        },
        minimumInputLength: 4,
        templateResult,
        templateSelection
    });
}

function formatResultContracts(data) {
    if (data.loading) {
        return 'Buscando...';
    }
    let image = "/images/default.jpg";
    if(data.person.image){
        image = "/storage/"+data.person.image.replace('.', '-cropped.');
    }
    var $container = $(
        `<div class="option-select2-custom">
            <div style="display:flex; flex-direction: row">
                <div>
                    <img src="${image}" style="width: 60px; height: 60px; border-radius: 30px; margin-right: 10px" />
                </div>
                <div>
                    <h5>
                        ${data.person.first_name.toUpperCase()} ${data.person.last_name.toUpperCase()} <br>
                        <p style="font-size: 13px; margin-top: 5px">
                            ${data.person.ci} <br>
                            ${(data.cargo_id ? data.cargo.Descripcion : data.job.name).toUpperCase()} ${data.procedure_type_id != 1 ? ' - '+(data.unidad_administrativa ? data.unidad_administrativa.nombre : data.direccion_administrativa.nombre).toUpperCase() : ''}
                        </p>
                    </h5>
                </div>
            </div>
            
        </div>`
    );

    return $container;
}

function formatResultAssets(data) {
    if (data.loading) {
        return 'Buscando...';
    }
    let image = "/images/default.jpg";
    if(JSON.parse(data.images).length){
        image = "/storage/"+JSON.parse(data.images)[0].replace('.', '-cropped.');
    }

    let labelType = 'default';
    switch (data.status) {
        case 'bueno':
            labelType = 'success';
            break;
        case 'regular':
            labelType = 'warning';
            break;
        case 'malo':
            labelType = 'danger';
            break;
    }

    var $container = $(
        `<div class="option-select2-custom">
            <div style="display:flex; flex-direction: row">
                <div>
                    <img src="${image}" style="width: 60px; height: 60px; border-radius: 30px; margin-right: 10px" />
                </div>
                <div>
                    <h5>
                        ${data.code} <label class="label label-${labelType}">${data.status.charAt(0).toUpperCase() + data.status.slice(1)}</label>
                        <p style="font-size: 12px; margin-top: 5px">
                            ${data.description.length > 100 ? data.description.substring(0, 100)+'...' : data.description} <br>
                            ${data.subcategory.name}
                        </p>
                    </h5>
                </div>
            </div>
            
        </div>`
    );
    return $container;
}