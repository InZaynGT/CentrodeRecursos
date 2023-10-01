<?php

require_once 'models/producto_servicioModel.php';
$p = new Producto();
$deactivate = $p->desactivarServicio($idItem);
if($deactivate){
    echo '
				<script>
					window.location.href = "'.BASE_DIR.'servicios";
				</script>
			';

}
