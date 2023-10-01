<!DOCTYPE html>
<html>
<link rel="shortcut icon" href="<?php echo BASE_DIR ?>clinic.ico" type="image/x-icon">
<head>
    <title>Receta</title>
    <style>
        @media print {
            body {
                -webkit-print-color-adjust: exact;
            }
        }

        @import url('<?php echo BASE_DIR; ?>css/fonts/ptsans_regular_macroman/stylesheet.css');

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'pt_sansregular', Arial, Tahoma, Verdana;
            font-size: 13px;
        }

        #contenido {
            /*border: solid 3px #000;*/
            width: 760px;
            height: 990px;
            position: relative;
        }
        
        h3,
        h4 {
            text-align: center;
            margin: 8px;
        }

        table {
            border-collapse: collapse;
            border-spacing: 0px;
        }

        th,
        td {
            padding: 2px 3px;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        footer {
            margin: auto;
        }

        #divfooter {
            position: absolute;
            bottom: 0;
            width: 100%;
        }

        .datos th {
            padding-right: 10px;
            text-align: left;
        }

        #logo,
        #texto {
            float: left;
        }

        #logo img {
            max-height: 70px;
        }

        #datosdoctor {
            float: right;
            width: 600px;
            font-size: 16px;
            font-family: 'Segoe UI', 'Open Sans', 'Helvetica Neue', sans-serif
        }

        #datosdoctor .bordered {
            border: solid 1px #000;
        }

        .clear {
            clear: both;
        }

        #tblmar {
            float: right;
            width: 320px;
        }
    </style>
    <script>
        window.print();
    </script>
</head>

<body>
    <div id="contenido">
        <div id="logo">

            <table>
                <tr>
                    <td><img src="<?php echo BASE_DIR ?>/img/clinicalogo.jpg" /></td>
                </tr>
            </table>

        </div>
        <div id="datosdoctor">
            <table>
                <tr>
                    <th>Dra. Elisama Matías Alfaro </th>
                </tr>
                <tr>
                    <td class="center">Ginecóloga y Obstetra</td>
                </tr>
                <tr>
                    <td>Ginecología, Maternidad, Ultrasonofragía, Colposcopía</td>
                </tr>
            </table>
        </div>
        <div class="clear"></div>
        <div class="center">
            <h2 style="margin: 0px 5px 0px;"></h2>
        </div>

        <table border="0" class="datos" width="100%">
            <tr>
                <td><b>Clinica:</b> &nbsp; Interior Hospital Privado López 3er. Nivel </td>
                <td width="40%"><b>Horario:</b>Lunes a Viernes de 10:00 a 13:00 horas</td>
            </tr>
            <tr>
                <td width="50%">1a. Calle 00-67 Zona 1, frente a Centro de Salud El Calvario</td>
                <td width="35%" >y de 15:00 a 19:00 horas</td>
                <td><b>Teléfono:</b></td>
            </tr>
            <tr>
                <td><b>PBX: 7955 8600 extensión 108</b></td>
                <td><b>Sábados</b> &nbsp; 10:00 a 13 horas </td>
                <td><b>7768-1157</b></td>
            </tr>
        </table>
        <hr style="width: 100%; color:blue; border-width:2px">
        <table>
            <tbody>
                <tr>
                <td><b>Paciente:</b></td>
                <td style="border-bottom:1px solid black"><?php echo $recetaEnc['nombre']?></td>
                </tr>
                <tr>
                    <td>
                    </td>
                </tr>
                <tr>
                    <td><b>Fecha:</b></td>
                    <td style="border-bottom:1px solid black"><?php echo $recetaEnc['fecha']?></td>
                </tr>
            </tbody>
        </table>
        <br>
        <table border="0" width="100%" class="left">
            
            <tbody>
                <?php foreach ($recetaDet as $key=>$det) { ?>
                    <tr>
                    <td class="right"><b><?php echo $key+1?>)</b></td>
                        <td width='40%' ><b><?php echo $det['nombre'] ?></b></td>
                    </tr>
                    <tr>
                        <td></td>
                    <td width='50%' ><?php echo $det['dosificacion'] ?></td>
                    </tr>
                    <tr>
                        <td></td>
                    <td width='90%' ><?php echo 'Uso: '.$det['uso'] ?></td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                
            </tfoot>
        </table>
        <br />
        <br>
        <br>
        <table border="0" width="100%">
            <tr>
                <td>
                    <strong>Próxima Cita:</strong> ______________________
                </td>
            </tr>
        </table>

        <table border="0" width="100%">
            <tr>
                <td class="center" width=100%">
                    
                    <strong><p style="color:blue">Por favor no cambiar el medicamento recetado.</p></strong>
                </td> 
            </tr>
        </table>
    </div>
</body>

</html>