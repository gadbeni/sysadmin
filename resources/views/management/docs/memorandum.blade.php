@extends('layouts.template-print')

@section('page_title', 'Memoramdum')

@section('content')
    <div class="content">
        <div class="page-title">
            <h2>
                <span style="color: #009A2F">MEMORANDUM</span> <br>
                S.D.A.F./R.E.C. N° 190/2021
            </h2>
        </div>
        <div class="page-body">
            <div class="page-head" style="width: 100%">
                <div class="border-right">
                    <p style="position:absolute; bottom: 10px">Santísima Trinidad, 21 de septiembre de 2021</p>
                </div>
                <div class="border-left">
                    <b>DE:</b> Lic. Geisel Marcelo Oliva Ruiz <br>
                    <b>RESP. DE PROCESO DE CONTRATACION APOYO NACIONAL A LA PRODUCCION Y EMPLEO – RPA</b> <br> <br>
                    <b>A:</b> Oscar Lola Saavedra <br>
                    <b>ASESOR TÉCNICO V SDAF - GAD BENI</b> <br> <br>
                    <b>A:</b> Ing. Juan Carlos Añez Ybañez <br>
                    <b>DIRECTOR DE INTERACCIÓN SOCIAL</b> <br> <br>
                    <b>REF.:</b> Designación Responsable de Evaluación
                </div>
            </div>
            <br>
            <p>
                De mi consideración: <br> <br>
                En uso de las atribuciones que me confiere el Decreto Supremo 0181 y la Resolución Administrativa de Gobernación N° 074/2021, de fecha 30 de agosto del 2021, bajo la Modalidad de Contratación Menor, transfiero a ustedes como <b>RESPONSABLE DE EVALUACIÓN</b>, el siguiente proceso de contratación: <br>

                <table border="1" cellspacing="0" cellpadding="10">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Modalidad</th>
                            <th>Objeto</th>
                            <th>programa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>GAD - BENI / MC N° 190/2021</td>
                            <td>CONTRATACIÓN MENOR</td>
                            <td>CONTRATACION DE UN CONSULTOR INDIVIDUAL DE LÍNEA PARA EL CARGO TÉCNICO IV PARA LA DIRECCIÓN DE INTERACCIÓN SOCIAL</td>
                            <td>IMPLEMENTACIÓN DEL SISTEMA DE INTERACCIÓN SOCIAL</td>
                        </tr>
                    </tbody>
                </table>

                La recepción de documentos se realizará en oficinas de la Dirección Administrativa dependiente de la Secretaría Departamental de Administración y Finanzas. <br> <br>
                Como Responsable de Evaluación, deberá cumplir las funciones establecidas en el artículo 38 del D.S. 0181, con dedicación exclusiva y no podrá delegar sus funciones ni excusarse, salvo en los casos de conflictos de intereses, impedimento físico o por las causales de excusa establecidas en el artículo 41 del mencionado decreto. <br> <br>
                Atentamente,
            </p>

            <div style="margin-top: 80px">
                <p style="text-align: center; width: 100%; font-size: 12px">
                    Lic. Geisel Marcelo Oliva Ruiz <br>
                    <b>RESPONSABLE DEL PROCESO DE CONTRATACIÓN <br>
                        DE APOYO NACIONAL A LA PRODUCCIÓN Y EMPLEO – RPA <br>
                        GAD - BENI
                    </b>
                </p>
            </div>

        </div>
    </div>
@endsection

@section('css')
    <style>
        .page-title {
            padding: 0px 34px;
            text-align: center;
            padding-top: 100px;
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
            font-size: 13px;
        }
        .page-head{
            display: flex;
            flex-direction: row;
            width: 100%;
            /* height: 100px; */
            border-bottom: 2px solid #000;
        }
        .border-right{
            position: relative;
            padding: 10px;
            width: 50%;
            border-right: 1px solid black
        }
        .border-left{
            padding: 10px;
            width: 50%;
            border-left: 1px solid black
        }
        .page-body th{
            background-color: #d7d7d7
        }
        .page-body table{
            /* text-align: center; */
            margin: 30px 0px;
            width: 100%;
            font-size: 12px;
        }
    </style>
@endsection

@section('script')
    <script>

    </script>
@endsection