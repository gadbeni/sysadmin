@extends('layouts.template-print')

@section('page_title', 'Declaración jurada')

@section('content')
    <div class="content">
        <div class="page-head">
            <h2 style="text-align: center">DECLARACIÓN JURADA DE NO INCOMPATIBILIDAD LEGAL</h2>
            <br>
            <p>
                Santísima Trinidad, 20 de Septiembre de 2021
            </p>
            <br>
            <p style="text-align: left">
                Señor: <br>
                Lic. Geisel Marcelo Oliva Ruiz <br>
                <b>RESPONSABLE DEL PROCESO DE CONTRATACIÓN DE APOYO NACIONAL A LA PRODUCCIÓN Y EMPLEO - RPA</b> <br>
                Presente. –
            </p>
        </div>
        <div class="page-title">
            <h3><u>REF. DECLARACIÓN JURADA</u></h3>
        </div>
        <div class="page-body">
            <p>
                De mi mayor consideración: <br> <br>
                Mediante la presente, declaro bajo juramento que mi persona no se encuentra dentro de las incompatibilidades legales para la Prestación de Servicios de Consultoría Individual de Línea, en el Gobierno Autónomo Departamental del Beni, asumiendo la responsabilidad de la veracidad de la presente declaración para los fines legales que correspondan. <br> <br>
                Fraternalmente,
            </p>

            <div style="margin-top: 70px">
                <p style="text-align: center; width: 100%">
                    Lourdes Nicol Muñoz Humalla <br>
                    C.I. 12815663-BN <br>
                    <b>POSTULANTE</b>
                </p>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <style>
        .page-head {
            padding: 0px 34px;
            text-align: right;
            padding-top: 50px;
        }
        .page-title {
            padding: 0px 50px;
            text-align: center;
            padding-top: 10px;
        }
        .page-body{
            padding: 0px 30px;
            padding-top: 10px;
        }
        .page-body p{
            text-align: justify;
        }
        .content {
            padding: 0px 34px;
            font-size: 14px;
        }
    </style>
@endsection

@section('script')
    <script>

    </script>
@endsection