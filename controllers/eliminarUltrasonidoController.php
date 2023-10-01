<?php

require_once 'models/producto_servicioModel.php';
$p = new Producto();
$deactivate = $p->desactivarUltrasonido($idItem);
if($deactivate){
    echo '
				<script>
					window.location.href = "'.BASE_DIR.'ultrasonidos";
				</script>
			';

}
