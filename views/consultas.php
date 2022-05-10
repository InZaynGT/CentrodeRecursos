<link rel="stylesheet" href="<?php echo BASE_DIR; ?>css/jquery-ui.css">
<script src="<?php echo BASE_DIR; ?>js/jquery-ui.js"></script>

<style type="text/css">

#container{position:relative;}

</style>
<style type="text/css">
    .userinfo2{
      font-family: 'pt_sansregular';
        color:white;
        background:#2E8B57;
        border-radius: 3px;
        border: 1px solid transparent;
        border-color: #556B2F;
        width:40px;
        height:40px;
        border-radius:3px;
        -webkit-box-shadow: inset 0px -2px 0px 0px rgba(0, 0, 0, 0.09);
        -moz-box-shadow: inset 0px -2px 0px 0px rgba(0, 0, 0, 0.09);
        box-shadow: inset 0px -1px 0px 0px rgba(0, 0, 0, 0.09);
     }
    .userinfo:hover {
background-color: #1462ba;
}
</style>
<?php $min='Consultas'; $may='CONSULTAS'; $minS='Consultas'; ?>
<aside class="right-side">
    <section class="content-header">
        <h1>
            <?php echo $min; ?>
            <small>Mantenimiento de <?php echo $min; ?></small>
        </h1>
		<ol class="breadcrumb">
			<li>
				<a href="<?php echo BASE_DIR; ?>agregar-consulta" class="btn btn-primary btn-sm">  AGREGAR CONSULTA</a>
			</li>
		</ol>
    </section>
    <section class="content">
		

	<div class="row">
		<div class="col-xs-12">
			<div class="box">
				<div class="box-header">
					<h3 class="box-title">Listado de <?php echo $min; ?></h3>
				</div>
				<div class="box-body table-responsive">
					<?php
								//si el id es example1, cambiarlo a otro
								//$columnasConSumas = array(9);
							//mostrarFiltroFecha(id talba, no. columna fecha, mostrar filtro fecha?)
							//mostrarFiltroFecha("example1234", 3, true,"Listado de Ordenes", $columnasConSumas);
					?>

					<table id="example1234" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>Nro. Consulta</th>
								<th>Fecha</th>
								<th>Paciente</th>
								<th>Estado</th>
								<th>Acciones</th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach($consultas as $columna){
							?>
								<tr>
									<td><?php echo $columna['idconsulta']; ?></td>
									<td><?php echo $columna['fecha']; ?></td>
									<td><?php echo $columna['paciente']; ?> </td>
									<td style="text-align:center;">
										<span class="label label-<?php echo ($columna['estado']==1) ? 'warning' : 'danger'; ?>">
											<?php echo ($columna['estado']==1) ? 'Activa' : 'Terminada'; ?>
										</span>
									</td>

                                    <!--ESTADOS DE LAS CONSULTAS-->
                                    <!--1 ACTIVA, 2 TERMINADA, 3 ANULADA, 4 FACTURADA-->

									<td class="acciones">
										<?php if($columna['estado']==1) {?>
										<a title="Editar <?php echo $minS; ?>" href="<?php echo BASE_DIR; ?>editar-consulta/<?php echo slug($columna['idconsulta']); ?>-<?php echo slug($columna['idpaciente']); ?>"><i class="fa fa-edit"></i></a>
										<a target="_blank"  href="<?php echo BASE_DIR; ?>imprimir-consulta/<?php echo $columna['idconsulta']; ?>-consulta" title="Imprimir Orden">
											<i class="fa fa-print"></i>
										</a>
										
										<?php }?>

										<?php if($columna['estado']==2) {?>
											<a href="<?php echo BASE_DIR; ?>consultaAdmin/<?php echo $columna['idconsulta']; ?>-<?php echo $columna['idpaciente']; ?>" class="eliminar fa fa-reply" title="Regresar a Activa" onclick="return confirm('&iquest;Esta seguro que desea cambiar el estado de la consulta a Activa?')" title="Regresar a Activa"></a>
											
										<?php }?>
										<a title="Agregar Imágenes" href="<?php echo BASE_DIR; ?>agregar-imagenes/<?php echo slug($columna['idconsulta']); ?>-<?php echo slug($columna['idpaciente']); ?>"><i class="fa fa-camera"></i></a>
										<a href="<?php echo BASE_DIR; ?>consultaEliminar/<?php echo $columna['idconsulta']; ?>-<?php echo $columna['idpaciente']; ?>" class="eliminar fa fa-remove" title="Eliminar Consulta" onclick="return confirm('&iquest;Esta seguro que desea eliminar la consulta?')" title="Eliminar Consulta" style="color:red;"></a>

									</td>
								</tr>
							<?php } ?>
						</tbody>
						<tfoot>
							<tr>
								<th>Nro. Consulta</th>
								<th>Fecha</th>
								<th>Paciente</th>
								<th>Estado</th>
								<th>Acciones</th>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
    </section>
</aside>
