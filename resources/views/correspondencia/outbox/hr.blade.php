{{-- <html mmoznomarginboxes="" mozdisallowselectionprint=""> --}}
<style>
        body{
            margin: 0px auto;
            font-family: Arial, sans-serif;
            font-weight: 100;
        }
        .btn-print{
            padding: 5px 10px
        }
        #watermark {
            width: 100%;
            position: fixed;
            top: 300px;
            opacity: 0.1;
            z-index:  -1;
            text-align: center
        }
        #watermark img{
            position: relative;
            width: 350px;
        }
        #label-location{
            display: none;
        }
        #label-locations{
            display: none;
        }
        #label-locatio{
            display: none;
        }
        @media print{
            .hide-print{
                display: none
            }
            .content{
                padding: 0px 0px
            }
            #location-id{
                display: none;
            }
            #label-location{
                display: inline;
            }

            #location-ids{
                display: none;
            }
            #label-locations{
                display: inline;
            }

            #location-i{
                display: none;
            }
            #label-locatio{
                display: inline;
            }
        }
        @media print and (min-width: 700px) and (orientation: landscape) {
            #watermark {
                top: 200px;
            }
        }
    </style>
    <head>
        <title>HR</title>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="{{ asset('css/siscor/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/siscor/print.style.css') }}" media="print">
        <style type="text/css">
            html
            {
                background-color: #FFFFFF; 
                margin: 0px;  /* this affects the margin on the html before sending to printer */
            }
            @media print{
            .hide-print{
                display: none
            }
            .content{
                padding: 0px 0px
            }
            #location-id{
                display: none;
            }
            #label-location{
                display: inline;
            }
        }
            body {
                font-size: 14px !important;
            }
            table {
                font-size: 14px !important;
            }
            .centrar{
                width: 240mm;
                margin-left: auto;
                margin-right: auto;
                /*border: 1px solid #777;*/
                display: grid;
                padding: 10mm !important;
                -webkit-box-shadow: inset 2px 2px 46px 1px rgba(209,209,209,1);
                -moz-box-shadow: inset 2px 2px 46px 1px rgba(209,209,209,1);
                box-shadow: inset 2px 2px 46px 1px rgba(209,209,209,1);
            }
            /*For each sections*/
            .box-section {
                margin-top: 1mm;
                border: 1px solid #000;
                padding: 8px;
            }
            .alltables {
                width: 100%;
            }
            .alltables td{
                padding: 2px;
            }
            .box-margin {
                border: 1px solid #000;
                width: 120px;
            }
            .caja {
                border: 1px solid #000;
            }
        </style>
    </head>
    <body>
        <div class="noImprimir text-center">
            <button onclick="javascript:window.print()" class="btn btn-link">
                IMPRIMIR
            </button>
        </div>
        




        <div class="centrar">
            <div id="marcaAgua">
                <div id="encabezado" style="padding:1">
                    {{-- ENCABEZADO --}}
                    <table class="alltables text-center">
                        <tbody>
                            <tr>
                                <td><img src="{{ asset('images/icon.png') }}" width="100px"></td>
                                <td>
                                    <table class="alltables">
                                        <tr>
                                            <td colspan="3" class="text-center">
                                                <h4 style="font-size: 27px;"><strong>GOBIERNO AUTONOMO DEPARTAMENTAL DEL BENI</strong></h4>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center" style="width: 45%">
                                                <span style="line-height: 8px; font-size: 25px;"><strong>NOTA DE COMUNICACION INTERNA</strong></span>
                                            </td>
                                            <td class="text-left" style="width: 20%; border: 1px solid #000; padding: 5px; font-weight: bold;">
                                                {{$entrada->tipo .'-' . $entrada->cite }}
                                            </td>                                            
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    {{-- Primera seccion --}}
                    <div class="box-section">
                        <table class="alltables">
                            <tr>
                                <td  WIDTH="17%">FECHA</td>
                                <td class="caja" WIDTH="12%">
                                    @if($entrada)
                                        {{ date('d/m/Y', strtotime($entrada->created_at)) }}
                                    @else
                                     2021
                                    @endif
                                </td>
                                <td WIDTH="8%">&nbsp;HORA</td>
                                <td class="caja" WIDTH="5%">
                                    {{ date('H:i', strtotime($entrada->created_at)) }}
                                </td>   
                                <td WIDTH="10%">&nbsp;Nº HOJAS</td>
                                <td class="caja"  WIDTH="33%">{{ $entrada->nro_hojas != null? $entrada->nro_hojas:'S/N' }}</td>
                            </tr>
                        </table>
                        @php
                           
                        @endphp
                        <table class="alltables" style="margin-top: 5px;">
                            <tr>
                                <td style="width: 20%">A</td>
                                <td class="box-margin">
                                    @php
                                        $name = \DB::table('people')->where('id', $entrada->people_id_para)->first();                                        
                                    @endphp
                                    {{$name->first_name}} {{$name->last_name}} 
                                    <select id="location-id" style="text-transform: uppercase;">
                                        <option value=" - {{$entrada->job_para}}"> - {{$entrada->job_para}}</option>
                                     
                                            @if(count($additionals)>0)
                                                @foreach($additionals as $item)
                                                    <option value=" - {{$item->cargo}}"> - {{$item->cargo}}</option>
                                                @endforeach
                                            @endif
                            
                                    </select>
                                    <span id="label-location" style="text-transform: uppercase;"> - {{$entrada->job_para}}</span>
                                </td>
                            </tr>
                        </table>
                            @if($entrada->tipo == 'I')
                                
                                @forelse($entrada->vias as $der)
                                    @php
                                        $viaJob = \app\Models\TcAdditionalJob::where('person_id',$der->people_id)
                                                        ->where('status', 1)
                                                        ->select('*')
                                                        ->get();
                                        // dd($viaJob);
                                    @endphp
                                    <table class="alltables" style="margin-top: 5px;">
                                        <tr style="text-transform: uppercase;">
                                            <td style="width: 20%">VIA</td>
                                            <td class="box-margin">{{ $der->nombre }} 

                                                <select id="location-i" style="text-transform: uppercase;">
                                                    <option value=""> - {{$der->cargo}}</option>
                                                    @if(count($viaJob)>0)
                                                        @foreach($viaJob as $item)
                                                            <option value=" - {{$item->cargo}}"> - {{$item->cargo}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <span id="label-locatio" style="text-transform: uppercase;"> - {{$der->cargo}}</span>
                                            </td>
                                        </tr>
                                    </table>
                                @empty
                                @endforelse
                            @endif
                    
                        <table class="alltables" style="margin-top: 5px;">
                            <tr>
                                <td style="width: 20%">DE</td>
                                <td class="box-margin">{{ $entrada->remitente}}

                                        <select id="location-ids" style="text-transform: uppercase;">
                                            <option value=""> - {{$entrada->job_de}}</option>
                                            @if(count($additional)>0)
                                                @foreach($additional as $item)
                                                    <option value=" - {{$item->cargo}}"> - {{$item->cargo}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <span id="label-locations" style="text-transform: uppercase;"> - {{$entrada->job_de}}</span>
                               
                                </td>
                            </tr>
                        </table>
                        
                        <table class="alltables" style="margin-top: 5px;">
                            <tr>
                                <td style="width: 20%">REF</td>
                                <td class="box-margin">{{ $entrada->referencia }}</td>
                            </tr>
                            @if($entrada->tipo == 'E')
                                <tr>
                                    <td ></td>
                                    @if($entrada->urgent)
                                    <td style="text-align: right;">
                                        <img src="{{ asset('images/urgente.jpg')}}" height="90px" width="210px" style="opacity: 0.4">
                                    </td>
                                    @else
                                    <td style="height: 70px" colspan="6">
                                        <p style="padding: 20px"></p>
                                    </td>
                                    @endif
                                </tr>
                            @endif
                        </table>
                        {{-- @if($entrada->tipo == 'E')
                            <table class="alltables">
                                <tr>
                                    <td style="width: 15%;">Fecha de Salida</td>
                                    <td>{{ date('d/m/Y', strtotime($entrada->created_at)) }}</td>
                                    <td>Plazo</td>
                                    <td>{{ $entrada->deadline ? date('d/m/Y', strtotime($entrada->deadline)) : '....../....../............' }}</td>
                                    <td class="text-right" style="width: 30%">Firma y sello</td>
                                    <td style="width: 25%"></td>
                                </tr>
                            </table>
                        @endif --}}
                    </div>
                    {{-- Body seccion --}}
                    @if($entrada->tipo == 'I')
                    <div class="box-section">
                        <table class="alltables">
                            <tr>
                                <td style="height: 350px" colspan="6">
                                    <p style="padding: 20px">{!! $entrada->detalles !!}</p>
                                    <br>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 15%;">Fecha de Salida</td>
                                <td>{{ date('d/m/Y', strtotime($entrada->created_at)) }}</td>
                                <td>Plazo</td>
                                <td>{{ $entrada->deadline ? date('d/m/Y', strtotime($entrada->deadline)) : '....../....../............' }}</td>
                                <td class="text-right" style="width: 30%">Firma y sello</td>
                                <td style="width: 25%"></td>
                            </tr>
                            <tr>
                                <td colspan="6">
                                <p></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    @endif
                    {{-- end section body --}}

                    {{-- Segunda seccion --}}
                    @if($entrada->tipo == 'E')
                    <div class="box-section">
                        <table width="100%" cellspacing="5">
                            <tr>
                                <td>A</td>
                                <td class="box-margin" colspan="5" height="20px"></td>
                            </tr>
                            <tr>
                                <td>DE</td>
                                <td class="box-margin" colspan="5" height="20px"></td>
                            </tr>
                            <tr>
                                <td>REF</td>
                                <td class="box-margin" colspan="5" height="20px"></td>
                            </tr>
                        </table>
                        <p>Instrucción</p>
                        <table width="100%" style="margin-top: 5px;">
                            <tr>
                                <td height="20px" class="box-margin" style="width: 5%;"></td>
                                <td style="width: 20%;">Urgente</td>
                                <td height="20px" class="box-margin" style="width: 5%;"></td>
                                <td style="width: 20%;">Para su conocimiento</td>
                                <td height="20px" class="box-margin" style="width: 5%;"></td>
                                <td style="width: 25%;">Analizar e Informar</td>
                                <td height="20px" class="box-margin" style="width: 5%;"></td>
                                <td>Para su Difusión</td>
                            </tr>
                        </table>
                        <table class="alltables" style="margin-top: 5px;">
                            <tr>
                                <td class="box-margin" style="width: 5%;"></td>
                                <td style="width: 20%;">Preparar respuesta</td>
                                <td class="box-margin" style="width: 5%;"></td>
                                <td style="width: 20%;">asistir a Evento</td>
                                <td class="box-margin" style="width: 5%;"></td>
                                <td style="width: 25%;">Proceder segun normas</td>
                                <td class="box-margin" style="width: 5%;"></td>
                                <td>Archivo</td>
                            </tr>
                        </table>
                        <table class="alltables" style="margin-top: 5px; margin-bottom: 5px;">
                            <tr>
                                <td class="box-margin" style="width: 5%;"></td>
                                <td style="width: 20%;">Reserva de Firma</td>
                                <td class="box-margin" style="width: 5%;"></td>
                                <td style="width: 20%;">Efectuara Inspección</td>
                                <td class="box-margin" style="width: 5%;"></td>
                                <td>Coordinar con</td>
                            </tr>
                        </table>
                        <p>NOTA:</p>
                        <table class="alltables">
                            <tr>
                                <td class="box-margin" style="height: 50;"></td>
                            </tr>
                        </table>
                        <table class="alltables" style="margin-top: 3px;">
                            <tr>
                                <td width="120px" height="30px">Fecha de salidad</td>
                                <td width="30px">............/............/..............</td>
                                <td width="50px">Hora</td>
                                <td width="100px">.......... : ..........</td>
                                <td class="text-right" style="width: 10%;">Firma</td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                    @endif
                    {{-- Tercera seccion --}}
                    <div class="box-section">
                        <table width="100%" cellspacing="5">
                            <tr>
                                <td>A</td>
                                <td class="box-margin" colspan="5" height="20px"></td>
                            </tr>
                            <tr>
                                <td>DE</td>
                                <td class="box-margin" colspan="5" height="20px"></td>
                            </tr>
                            <tr>
                                <td>REF</td>
                                <td class="box-margin" colspan="5" height="20px"></td>
                            </tr>
                        </table>
                        <p>Instrucción</p>
                        <table class="alltables" style="margin-top: 5px;">
                            <tr>
                                <td class="box-margin" style="width: 5%;"></td>
                                <td style="width: 20%;">Urgente</td>
                                <td class="box-margin" style="width: 5%;"></td>
                                <td style="width: 20%;">Para su conocimiento</td>
                                <td class="box-margin" style="width: 5%;"></td>
                                <td style="width: 25%;">Analizar e Informar</td>
                                <td class="box-margin" style="width: 5%;"></td>
                                <td>Para su Difusión</td>
                            </tr>
                        </table>
                        <table class="alltables" style="margin-top: 5px;">
                            <tr>
                                <td class="box-margin" style="width: 5%;"></td>
                                <td style="width: 20%;">Preparar respuesta</td>
                                <td class="box-margin" style="width: 5%;"></td>
                                <td style="width: 20%;">Asistir a Evento</td>
                                <td class="box-margin" style="width: 5%;"></td>
                                <td style="width: 25%;">Proceder segun normas</td>
                                <td class="box-margin" style="width: 5%;"></td>
                                <td>Archivo</td>
                            </tr>
                        </table>
                        <table class="alltables" style="margin-top: 5px; margin-bottom: 5px;">
                            <tr>
                                <td class="box-margin" style="width: 5%;"></td>
                                <td style="width: 20%;">Para Firma</td>
                                <td class="box-margin" style="width: 5%;"></td>
                                <td style="width: 20%;">Efectuara Inspección</td>
                                <td class="box-margin" style="width: 5%;"></td>
                                <td>Coordinar con</td>
                            </tr>
                        </table>
                        <p>NOTA:</p>
                        <table class="alltables">
                            <tr>
                                <td class="box-margin" style="height: 50;"></td>
                            </tr>
                        </table>
                        <table class="alltables" style="margin-top: 3px;">
                            <tr>
                                <td width="120px" height="30px">Fecha de salidad</td>
                                <td width="30px">............/............/..............</td>
                                <td width="50px">Hora</td>
                                <td width="100px">.......... : ..........</td>
                                <td class="text-right" style="width: 10%;">Firma</td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                    <div class="text-center">
                        <p style="font-size: 13px;"><b>NOTA:</b> Esta Papeleta no debera ser separada ni extraviada del documento al cual se encuentra adherida por constituir parte del mismo</p>
                    </div>
                    <div>
                        <table style="width: 100%;">
                            <tr>
                                <td class="text-left" style="font-size: 10px;">{{ 'Registrado por: ' . $entrada->registrado_por }}</td>
                                <td class="text-right" style="font-size: 10px;">{{ 'Fecha y hora de impresión: ' . date('d/m/Y H:i:s') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script type="text/javascript" src="{{ voyager_asset('js/app.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#location-id').change(function () {
                $('#label-location').html($(this).val());
            });
            $('#location-ids').change(function () {
                $('#label-locations').html($(this).val());
            });
            $('#location-i').change(function () {
                $('#label-locatio').html($(this).val());
            });
        });
    </script>
</html>