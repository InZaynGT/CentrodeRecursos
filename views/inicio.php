<script src="<?php echo BASE_DIR; ?>js/Chart.js"></script>
<aside class="right-side">

    <section class="content-header">
        <h1 align="center">
            I N I C I O
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col lg-3 col-xs-4">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><?php echo $totalPacientes ?></h3>
                        <p>Pacientes ingresados</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users">
                        </i>
                    </div>
                    <a href="<?php echo BASE_DIR . 'pacientes' ?>" class="small-box-footer">
                        Más información
                        <i class="fa fa-arrow-circle-right">

                        </i>
                    </a>
                </div>
            </div>

            <div class="col lg-3 col-xs-4">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3><?php echo $totalConsultas ?></h3>
                        <p>Consultas Ingresadas</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-paperclip">
                        </i>
                    </div>
                    <a href="<?php echo BASE_DIR . 'consultas' ?>" class="small-box-footer">
                        Más información
                        <i class="fa fa-arrow-circle-right">

                        </i>
                    </a>
                </div>
            </div>

            <div class="col lg-3 col-xs-4">
                <div class="small-box bg-olive">
                    <div class="inner">
                        <h3><?php echo $totalCitas ?></h3>
                        <p>Citas para hoy</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-calendar">
                        </i>
                    </div>
                    <a href="<?php echo BASE_DIR . 'citas' ?>" class="small-box-footer">
                        Más información
                        <i class="fa fa-arrow-circle-right">

                        </i>
                    </a>
                </div>
            </div>

        </div>
        <!-- <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3>Resumen mensual</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-1"></div>
                            <div class="col-md-10">
                                <p class="text-center">
                                    <strong>Consultas Creadas en los últimos 12 Meses</strong>
                                </p>
                                <div class="chart">
                                    <canvas id="salesChart" style="height: 180px; width: 816px;" height="255" width="800"></canvas>
                                    <?php 
                                    $listaCantidad = array_map(function($totalConsultasM) {
                                        return $totalConsultasM['cantidad'];
                                      }, $totalConsultasM);
                                    $listaMes = array_map(function($totalConsultasM) {
                                        return $totalConsultasM['mes'];
                                      }, $totalConsultasM);

                                    //HACER ARRAY DE MESES
                                    $arrayValoresMes = array_values($listaMes);
                                    $jsonMes = json_encode($arrayValoresMes);
                                    $jsonFormateadoMes = str_replace(array('(', ')', '=>'), array('[', ']', ','), $jsonMes);
                                    
                                    //HACER ARRAY DE ENTEROS
                                    $arrayValores = array_values($listaCantidad);
                                    $arrayEnteros = array_map('intval', $arrayValores);
                                    $jsonFormateado = json_encode($arrayEnteros);
                                    ?>
                                    <script>
                                        var ctx = document.getElementById("salesChart").getContext('2d');
                                        var myChart = new Chart(ctx, {
                                            type: 'bar',
                                            data: {
                                                labels: <?php echo $jsonFormateadoMes ?> ,
                                                datasets: [{
                                                    label: "Datos Proporcionados",
                                                    data: <?php echo $jsonFormateado ?>,
                                                    // data: $totalConsultas
                                                    backgroundColor: [
                                                        'rgba(255, 99, 132, 0.2)',
                                                        'rgba(54, 162, 235, 0.2)',
                                                        'rgba(255, 206, 86, 0.2)',
                                                        'rgba(75, 192, 192, 0.2)',
                                                        'rgba(153, 102, 255, 0.2)',
                                                        'rgba(255, 159, 64, 0.2)',
                                                        'rgba(255, 99, 132, 0.2)',
                                                        'rgba(54, 162, 235, 0.2)',
                                                        'rgba(255, 206, 86, 0.2)',
                                                        'rgba(75, 192, 192, 0.2)'
                                                    ],
                                                    borderColor: [
                                                        'rgba(255,99,132,1)',
                                                        'rgba(54, 162, 235, 1)',
                                                        'rgba(255, 206, 86, 1)',
                                                        'rgba(75, 192, 192, 1)',
                                                        'rgba(153, 102, 255, 1)',
                                                        'rgba(255, 159, 64, 1)'
                                                    ],
                                                    borderWidth: 1
                                                }]
                                            },
                                            options: {
                                                scales: {
                                                    yAxes: [{
                                                        ticks: {
                                                            beginAtZero: true
                                                        }
                                                    }]
                                                }
                                            }
                                        });
                                    </script>
                                </div>
                            </div>
                            <!-- <div class="col-md-4">
                                <p class="text-center">
                                    <strong>Metas a completar</strong>
                                </p>
                                <div class="progress-group">
                                    <span class="progress-text"> Add Products to Cart</span>
                                    <span class="progress-number">
                                        <b>160</b>
                                        200
                                    </span>
                                    <div class="progress sm">
                                        <div class="progress-bar progress-bar-aqua" style="width:80%"></div>
                                    </div>
                                </div>

                                <div class="progress-group">
                                    <span class="progress-text"> Complete purchase</span>
                                    <span class="progress-number">
                                        <b>310</b>
                                        400
                                    </span>
                                    <div class="progress sm">
                                        <div class="progress-bar progress-bar-red" style="width:80%"></div>
                                    </div>
                                </div>

                                <div class="progress-group">
                                    <span class="progress-text"> Visit Premiun Page</span>
                                    <span class="progress-number">
                                        <b>480</b>
                                        800
                                    </span>
                                    <div class="progress sm">
                                        <div class="progress-bar progress-bar-green" style="width:80%"></div>
                                    </div>
                                </div>
                                <div class="progress-group">
                                    <span class="progress-text"> Send Inquires</span>
                                    <span class="progress-number">
                                        <b>250</b>
                                        500
                                    </span>
                                    <div class="progress sm">
                                        <div class="progress-bar progress-bar-yellow" style="width:80%"></div>
                                    </div>
                                </div>

                            </div> -->

                        </div>
                        <div class="box-footer">
                            <!-- <div class="row">
                                <div class="col-sm-3 col-xs-6">
                                    <div class="description-block border-right">
                                        <span class="description-percentage text-green">
                                            <i class="fa fa-caret-up"></i>
                                            17%
                                        </span>
                                        <h5 class="description-header">Q35,212.43</h5>
                                        <span class="description-text">Total de Ingresos</span>
                                    </div>
                                </div>

                                <div class="col-sm-3 col-xs-6">
                                    <div class="description-block border-right">
                                        <span class="description-percentage text-yellow">
                                            <i class="fa fa-caret-left"></i>
                                            0%
                                        </span>
                                        <h5 class="description-header">Q10,212.43</h5>
                                        <span class="description-text">Costo Total</span>
                                    </div>
                                </div>

                                <div class="col-sm-3 col-xs-6">
                                    <div class="description-block border-right">
                                        <span class="description-percentage text-green">
                                            <i class="fa fa-caret-up"></i>
                                            20%
                                        </span>
                                        <h5 class="description-header">Q25,000.00</h5>
                                        <span class="description-text">Total de Ganacias</span>
                                    </div>
                                </div>

                                <div class="col-sm-3 col-xs-6">
                                    <div class="description-block">
                                        <span class="description-percentage text-red">
                                            <i class="fa fa-caret-down"></i>
                                            17%
                                        </span>
                                        <h5 class="description-header">1200</h5>
                                        <span class="description-text">Metas completadas</span>
                                    </div>
                                </div>

                            </div> -->
                        </div>

                    </div>

                </div>

            </div>


        </div> -->
    </section>
</aside>