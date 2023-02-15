
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
                                        <textarea class="form-control richTextBox" name="detalles">{{old('detalles') ? : $outbox->detalles}}</textarea>
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


    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.8.0/sweetalert2.min.js"></script>

   
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
        
            var okletra = true;
            var oknumero = true;
            var auxl=0;
            var auxn=0;
            $(document).ready(function(){

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
                    // alert(1);
                    tipo = "<?php echo $outbox->tipo; ?>";
                    if(tipo == 'I')
                    {
                        $('#input1').fadeIn();
                        $('#input1').attr('required', 'required');
                        $('#input1').attr('name', 'cite');
                        auxn =0
                        ;
                        auxl=0
                        ;
                    }
                }
                else
                {
                    $('#input1').fadeIn();
                }

                tinymce.init(window.voyagerTinyMCE.getConfig(additionalConfig));
                

            });
            
            function check(e) {   
                tecla = (document.all) ? e.keyCode : e.which;

                if (tecla == 8) {
            
                    return true;
                }

                var numero =0;
                var letra =0;
                patron = /[A-Za-z0-9-/-]/;
                tecla_final = String.fromCharCode(tecla);
                // alert(patron.test(tecla_final))
                if(patron.test(tecla_final))
                {
                    var contenido =document.getElementsByClassName("input1")[0].value;
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

                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "El Campo Nro. CITE tiene que tener minimo 2 letras y 5 numeros.\nEjemplo: DF-1/2022",
                        // footer: '<a href="">Why do I have this issue?</a>'
                    })
                    div = document.getElementById('flotante');
                    div.style.display = '';
                    return;
                }
                
            }



            $(document).ready(function(){
                $("#input1").on('paste', function(e){
                    e.preventDefault();
                    // alert('Esta acción está prohibida');
                })                
                $("#input1").on('copy', function(e){
                    e.preventDefault();
                    // alert('Esta acción está prohibida');
                })
            })



            // Para validar los archivos Extenciones y Tamaño
            $(document).on('change','.imageLength',function(){
                var fileName = this.files[0].name;
                var fileSize = this.files[0].size;
                    var ext = fileName.split('.').pop();
                    
                    ext = ext.toLowerCase();

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
                            this.value = ''; 
                            this.files[0].name = '';
                    }
            });

        </script>
    @stop
    
