<link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.8.0/sweetalert2.min.css" rel="stylesheet" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.8.0/sweetalert2.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">




@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', 'Añadir Ingreso')


    @section('page_header')
        <h1 class="page-title">
            <i class="voyager-credit-cards"></i>
            Crear NCI
        </h1>
    @stop

    @section('content')
        <div class="page-content edit-add container-fluid">
            <div class="row">
                <div class="col-md-12 div-phone">
                    <form 
                        action="{{ ! $outbox->id ? route('outbox.store') : route('outbox.update',$outbox->id) }}" 
                        method="POST" 
                        enctype="multipart/form-data"
                        id="formulario"
                    >
                        @if($outbox->id)
                            @method('PUT')
                        @endif
                        @csrf
                        <div class="panel panel-bordered">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label class="control-label">Tipo</label>
                                        <select name="tipo" class="form-control select2" id="select-tipo" required>
                                            <option value="" selected>Seleccione el tipo</option>
                                            <option {{old('tipo') === 'I' || $outbox->tipo === 'I' ? 'selected' : ''}} 
                                                value="I">
                                                Interno
                                            </option>
                                        </select>
                                    </div>
                                    <div id="div_category" @if(auth()->user()->hasRole(['ventanilla'])) class="form-group col-md-4" @else class="form-group col-md-6" @endif>
                                        <label class="control-label">Tipo Trámite</label>
                                        <select name="category_id" class="form-control select2" id="select-category" required>
                                            <option value="" selected>Seleccione el tipo</option>
                                            @foreach (\App\Models\TcCategory::with(['organization' => function($q){
                                                $q->where('tipo','tptramites');
                                            }])->get() as $item)
                                            <option {{(int)old('category_id') === $item->id ||$outbox->category_id === $item->id ? 'selected' : ''}} value="{{ $item->id }}">{{ ($item->organization->count() > 0) ? substr($item->organization->nombre,0,4).' -' : '' }} {{ $item->nombre }}</option> 
                                            @endforeach                                        
                                        </select>
                                    </div>

                                    <div id="divcite" class="form-group tip col-md-6">
                                        <label class="control-label">Nro de cite</label>
                                        <input type="text" id="input1" maxlength="50" class="form-control input1" onkeypress="return check(event)" style="text-transform:uppercase" placeholder="DF-1/2022">
                                        <input type="text" id="input2" maxlength="50" class="form-control input2" style="text-transform:uppercase" placeholder="1/2022">
                                        <span id="icon"  style="display: none; color:red">
                                            <b>El cite  ya se encuentra registrado</b>
                                        </span>
                                    </div>
                                    




                                    
                                    <div class="form-group col-md-6">
                                        <label class="control-label">Fecha Registro</label>
                                        <span class="voyager-question text-info pull-left" data-toggle="tooltip" data-placement="left" title="Si seleccionona una fecha anterior estara registrando un tramite con fecha atrasada."></span>
                                        @php 
                                        $dt = new DateTime(); // Date object using current date and time
                                        $dt= $dt->format('Y-m-d\TH:i:s'); 
                                        $fechaeregistro = $outbox->fecha_registro ? $outbox->fecha_registro : $dt;
                                        @endphp
                                        <input type="datetime-local" name="fecha_registro" class="form-control"  value="{{old('fecha_registro') ? : date('Y-m-d\TH:i', strtotime($fechaeregistro))}}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="control-label">Urgente</label><br>
                                        <span class="voyager-question text-info pull-left" data-toggle="tooltip" data-placement="left" title="Este campo es obligatorio."></span>
                                        <input 
                                            type="checkbox" 
                                            name="urgent" 
                                            data-toggle="toggle" 
                                            data-on="Sí" 
                                            data-off="No"
                                            @if($outbox->urgent) checked @endif
                                            >
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="control-label">Remitente</label>
                                        <div id="div-remitente" style="{{$outbox->tipo == null ||$outbox->tipo === 'I' ? 'display: block' : 'display: none' }}">
                                            
                                            <select name="funcionario_id_remitente" class="form-control select2">
                                                <option value="{{ $funcionario->id_funcionario ? $funcionario->id_funcionario : $funcionario->funcionario_id }}">{{ $funcionario ? $funcionario->nombre.' '.$funcionario->cargo: 'Admin' }}</option>
                                            </select>
                                        </div>
                                        <!-- <input type="hidden" name="remitent_interno" value="{{ $funcionario ? $funcionario->nombre.' '.$funcionario->cargo : null}}"> -->
                                        <input type="hidden" name="remitent_interno" value="{{ $funcionario ? $funcionario->nombre : null}}">

                                        
                                        {{-- <input 
                                            type="text" 
                                            name="remitente" 
                                            id="input-remitente" 
                                            maxlength="150" 
                                            style="{{$outbox->tipo === 'E' ? 'display: block' : 'display: none' }}" 
                                            class="form-control"
                                            value="{{old('remitente') ? :$outbox->remitente}}"> --}}
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="control-label">Nro. de Hojas/Anexas</label>
                                        <input type="text" name="nro_hojas" class="form-control" value="{{old('nro_hojas') ? : $outbox->nro_hojas}}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="control-label">Plazo</label>
                                        @php 
                                        $fechaentrada = $outbox->deadline ? $outbox->deadline->format('Y-m-d') : '';
                                        @endphp
                                        <input type="date" name="deadline" class="form-control"  value="{{old('deadline') ? : $fechaentrada}}">
                                    </div>
                                    <div class="form-group col-md-6" id="div-destinatario" >
                                        <label class="control-label">Destinatario</label>
                                        <input 
                                        type="checkbox" 
                                        
                                        id="toggleswitch" 
                                        data-toggle="toggle" 
                                        data-on="Interno" 
                                        data-off="Externo"
                                        checked 
                                        >
                                        <select name="funcionario_id_destino" class="form-control" id="select-funcionario_id_destino" style="text-transform: uppercase;" required></select>
                                        
                                    </div>
                                    
                                    <div class="form-group col-md-6">
                                        <label class="control-label">Archivos</label>
                                        <input type="file" name="archivos[]" multiple class="form-control imageLength" accept="image/jpeg,image/jpg,image/png,application/pdf">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label class="control-label">Referencia</label>
                                        <textarea name="referencia" class="form-control" rows="3" required>{{old('referencia') ? : $outbox->referencia}}</textarea>
                                    </div>
                                    
                                    <div class="form-group col-md-12" id="div-detalle">
                                        <label class="control-label">Cuerpo</label> 
                                        <textarea class="form-control richTextBox" rows="20" id="bloquear" name="detalles">{{old('detalles') ? : $outbox->detalles}}</textarea>
                                    </div>
                                   
                                </div>
                            </div>

                            <div class="panel-footer text-right">
                                <button type="submit" id="btn_save" class="btn btn-primary save">{{ __('voyager::generic.save') }} <i class="voyager-check"></i> </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>





    @stop


    @section('javascript')
   
        {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> Para bloquear el text-area --}}
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            entrada_id = "<?php echo $outbox->id; ?>"; 
            input1.oninput = function() {
                // result.innerHTML = input1.value;
                var cite ="";
                var aux = '';
                var i =0;
                cite = input1.value;

                while(i < cite.length){
                    if(cite.charAt(i) == '/'){
                        aux = aux + '&';   
                    }
                    else
                    {
                        aux = aux + cite.charAt(i);
                    }
                    i++;
                }
                if(!entrada_id)
                {
                    entrada_id=1;
                }
                $.get('{{route('cite.get')}}/'+entrada_id+'/'+aux, function(data){ 
                    if(data == 1)
                    {
                        $('#icon').fadeIn();         
                        $('#btn_save').attr('disabled', true);           
                    }      
                    else
                    {
                        $('#icon').fadeOut();
                        $('#btn_save').attr('disabled', false);
                    }
                });
            };
            input2.oninput = function() {
                // result.innerHTML = input1.value;
                var cite ="";
                var aux = '';
                var i =0;
                cite = input2.value;

                while(i < cite.length){
                    if(cite.charAt(i) == '/'){
                        aux = aux + '&';   
                    }
                    else
                    {
                        aux = aux + cite.charAt(i);
                    }
                    i++;
                }
                if(!entrada_id)
                {
                    entrada_id=1;
                }
                $.get('{{route('cite.get')}}/'+entrada_id+'/'+aux, function(data){ 
                    if(data == 1)
                    {
                        $('#icon').fadeIn();         
                        $('#btn_save').attr('disabled', true);           
                    }      
                    else
                    {
                        $('#icon').fadeOut();
                        $('#btn_save').attr('disabled', false);
                    }
                });
            };

            var okletra = true;
            var oknumero = true;
            var auxl=0;
            var auxn=0;
            $(document).ready(function(){

                $('#form-create-customer').submit(function(e){
                    e.preventDefault();
                    $('.btn-save-customer').attr('disabled', true);
                    $('.btn-save-customer').val('Guardando...');
                    $.post($(this).attr('action'), $(this).serialize(), function(data){
                        if(data.category.id){
                            // $('#select-category').trigger('reset');
                            // $('#div_category').load()
                            toastr.success('Categoria Registrada..', 'Éxito');
                            $(this).trigger('reset');
                        }else{
                            toastr.error(data.error, 'Error');
                        }
                    })
                    .always(function(){
                        $('.btn-save-customer').attr('disabled', false);
                        $('.btn-save-customer').val('Guardar');
                        $('#nombre').val('');
                        $('#organization_id').val('').trigger('change');

                        $('#modal-create-customer').modal('hide');
                    });
                });





               // $('#divcite').fadeOut();
                ruta = "{{ route('getpeoplederivacion') }}";
                intern_externo=1;
                $("#select-funcionario_id_destino").select2({
                    ajax: { 
                        allowClear: true,
                        url: ruta,
                        type: "get",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                search: params.term, // search term
                                externo: intern_externo
                            };
                        },
                        processResults: function (response) {
                            return {
                                results: response
                            };
                        }
                    }
                });
                $('#toggleswitch').on('change', function() {
                    if (this.checked) {
                        intern_externo = 1;
                    } else {
                        intern_externo = 0;
                    }
                });




                var additionalConfig = {
                    selector: 'textarea.richTextBox[name="detalles"]',
                }

                


                edit = "<?php echo $outbox->id; ?>";
                if(edit)
                {
                    tipo = "<?php echo $outbox->tipo; ?>";
                    if(tipo == 'I')
                    {
                        $('#input2').fadeOut();
                        $('#input2').removeAttr('required');
                        $('#input2').removeAttr('name', 'cite');
                        $('#input1').fadeIn();
                        $('#input1').attr('required', 'required');
                        $('#input1').attr('name', 'cite');
                        auxn =0
                        ;
                        auxl=0
                        ;
                    }
                    else
                    {
                        $('#input1').fadeOut();
                        $('#input1').removeAttr('required');
                        $('#input1').removeAttr('name', 'cite');
                        $('#input2').fadeIn();
                        $('#input2').attr('required', 'required');
                        $('#input2').attr('name', 'cite');
                        auxn =5;
                        auxl=5;
                    }
                    // alert(edit)
                }
                else
                {
                    $('#input1').fadeIn();
                    $('#input2').fadeOut();
                }

                tinymce.init(window.voyagerTinyMCE.getConfig(additionalConfig));
                
                $('#select-tipo').change(function(){
                    let tipo = $('#select-tipo option:selected').val();
                    if(tipo == 'E'){
                        $('#div-remitente').fadeOut();
                        $('#input-remitente').fadeIn();
                        $('#div-detalle').fadeOut();
                        $('#div-entity_id').fadeIn();
                        $('#input2').fadeIn();
                        $('#input2').attr('required', 'required');
                        $('#input2').attr('name', 'cite');
                        $('#input1').fadeOut();
                        $('#input1').removeAttr('required');
                        $('#input1').removeAttr('name', 'cite');
                        auxn =5;
                        auxl=5;
                        
                    }else{
                        $('#div-remitente').fadeIn();
                        $('#input-remitente').fadeOut();
                        $('#div-detalle').fadeIn();
                        $('#div-entity_id').fadeOut();
                        $('#input1').fadeIn();
                        $('#input1').attr('required', 'required');
                        $('#input1').attr('name', 'cite');

                        $('#input2').fadeOut();
                        $('#input2').removeAttr('required');
                        $('#input2').removeAttr('name', 'cite');
                        auxn =0;
                        auxl=0;
                    }
                });
            });
            
            function check(e) {   
                tecla = (document.all) ? e.keyCode : e.which;

                //Tecla de retroceso para borrar, siempre la permite
                if (tecla == 8) {
            
                    return true;
                }

                var numero =0;
                var letra =0;
                // Patron de entrada, en este caso solo acepta numeros y letras
                patron = /[A-Za-z0-9-/-]/;
                tecla_final = String.fromCharCode(tecla);
                // alert(patron.test(tecla_final))
                if(patron.test(tecla_final))
                {
                    var contenido =document.getElementsByClassName("input1")[0].value;
                    // var contenido =document.getElementsByClassName("input2")[0].value;
                    var cadena =  contenido+tecla_final;
                    // alert(cadena)

                    for(var i = 0; i < (contenido+tecla_final).length; i++)
                    {
                        if((cadena[i]>="a" && cadena[i]<="z")||(cadena[i]>="A" && cadena[i]<="Z"))  
                        {
                            letra= letra+1;
                        } 
                        if(cadena[i] >= 0 && cadena[i]<= 9)  
                        {
                            numero= numero+1;
                        }   
                    }
                    auxl=letra;
                    auxn=numero;
                    // alert(letra)
                    // alert(numero)
                    if(letra <= 12)
                    {
                        okletra = true;
                    }

                    var num ="0123456789";
                    if (!(num.indexOf(tecla_final.charAt(0),0)!=-1))
                    {
                        // alert(4
                        
                        if(letra <= 12 && okletra == true)
                        {
                            if(letra == 12)
                            {
                                okletra = false;
                            }
                            else
                            {
                                okletra = true;
                            }
                            
                            return true;
                        }
                        else
                        {
                            okletra = false;
                            return false;
                        }
                    }
                    

                    if(numero <= 7)
                    {
                        oknumero = true;
                    }

                    if(numero <= 7 && oknumero == true)
                    {
                        if(numero == 7)
                        {
                            oknumero = false;
                        }
                        else
                        {
                            oknumero = true;
                        }
                        
                        return true;
                    }
                    else
                    {
                        return false;
                    }
                
                }
                else{
                    return false;
                }
                                
            }
        

            document.addEventListener("DOMContentLoaded", function() {
                document.getElementById("formulario").addEventListener('submit', validarFormulario); 
            });

            function validarFormulario(evento) {
                evento.preventDefault();
                
                if (auxl>=2 && auxn>=5) {
                    this.submit();
                }
                else
                {
                    // swal({
                    //     title: "Error",
                    //     text: "El Campo Nro. CITE tiene que tener minimo 2 letras y 5 numeros.\nEjemplo: DF-1/2022",
                    //     // text: "Esta acción ya no se podrá deshacer, Así que piénsalo bien.",
                    //     type: "error",
                    //     showCancelButton: false,
                    //     // confirmButtonColor: '#3085d6',
                    //     // cancelButtonColor: '#d33',
                    //     // confirmButtonText: 'Si, estoy seguro',
                    //     // cancelButtonText: "Cancelar"
                    //     });

                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "El Campo Nro. CITE tiene que tener minimo 2 letras y 5 numeros.\nEjemplo: DF-1/2022",
                        // footer: '<a href="">Why do I have this issue?</a>'
                    })
                    // alertify.confirm("This is a confirm dialog.",
                    // function() {
                    //     alertify.success('Ok');
                    // },
                    // function() {
                    //     alertify.error('Cancel');
                    // }
                    // );
                    div = document.getElementById('flotante');
                    div.style.display = '';
                    return;
                }
                
            }



            $(document).ready(function(){
                $("#bloquear").on('paste', function(e){
                    e.preventDefault();
                    // alert('Esta acción está prohibida');
                })                
                $("#bloquear").on('copy', function(e){
                    e.preventDefault();
                    // alert('Esta acción está prohibida');
                })
            })



            // Para validar los archivos Extenciones y Tamaño
            $(document).on('change','.imageLength',function(){
                var fileName = this.files[0].name;
                var fileSize = this.files[0].size;

                // if(fileSize > 10000000){
                //     Swal.fire({
                //         icon: 'error',
                //         title: 'Oops...',
                //         text: 'El archivo no debe superar los 10 MB!'
                //     })
                //     this.value = '';
                //     this.files[0].name = '';
                // }
                
                    // recuperamos la extensión del archivo
                    var ext = fileName.split('.').pop();
                    
                    // Convertimos en minúscula porque 
                    // la extensión del archivo puede estar en mayúscula
                    ext = ext.toLowerCase();
                    // console.log(ext);
                    switch (ext) {
                        case 'jpg':
                        case 'jpeg':
                        case 'png': 
                        case 'pdf': break;
                        default:
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'El archivo no tiene la extensión adecuada!'
                            })
                            this.value = ''; // reset del valor
                            this.files[0].name = '';
                    }
            });

        </script>
    @stop
    
