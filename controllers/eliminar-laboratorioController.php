<?php

require_once 'models/producto_servicioModel.php';
$p = new Producto();
$deactivate = $p->desactivarLaboratorio($idItem);
if($deactivate){
    echo '
				<script>
					window.location.href = "'.BASE_DIR.'laboratorios";
				</script>
			';

}
