<script src="<?php echo BASE_DIR; ?>js/Chart.js"></script>
<aside class="right-side">

    <section class="content-header">
        <h1 align="center">
            ADMINISTRACIÓN DEL CENTRO DE RECURSOS
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
                        <p>Citas agendadas para hoy</p>
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
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Resumen mensual</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <p class="text-center">
                            <strong>Consultas Creadas en los últimos 10 Meses</strong>
                        </p>
                        <div class="chart">
                            <canvas id="salesChart" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Estilos CSS para el gráfico -->
<style>
    .chart {
        text-align: center;
    }

    #salesChart {
        width: 100%;
        max-width: 800px;
        margin: 0 auto;
    }
</style>

<?php
// Tu código PHP para obtener datos y formatearlos
$listaCantidad = array_map(function($totalConsultasM) {
    return $totalConsultasM['cantidad'];
}, $totalConsultasM);

$listaMes = array_map(function($totalConsultasM) {
    return $totalConsultasM['mes'];
}, $totalConsultasM);

$arrayValoresMes = array_values($listaMes);
$jsonFormateadoMes = json_encode($arrayValoresMes);

$arrayValores = array_values($listaCantidad);
$arrayEnteros = array_map('intval', $arrayValores);
$jsonFormateado = json_encode($arrayEnteros);
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById("salesChart").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo $jsonFormateadoMes ?>,
            datasets: [{
                label: "Consultas Registradas",
                data: <?php echo $jsonFormateado ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(54, 162, 235, 0.5)',
                    'rgba(255, 206, 86, 0.5)',
                    'rgba(75, 192, 192, 0.5)',
                    'rgba(153, 102, 255, 0.5)',
                    'rgba(255, 159, 64, 0.5)',
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(54, 162, 235, 0.5)',
                    'rgba(255, 206, 86, 0.5)',
                    'rgba(75, 192, 192, 0.5)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    gridLines: {
                display: false // Esto desactiva las líneas de la escala en el eje Y
                    },
                    ticks: {
                        beginAtZero: true
                    }
                }]
            },
            legend: {
                display: false
            }
        }
    });
</script>

    </section>
</aside>
