<?php
permiso(1);
require_once 'models/pacientesModel.php';
$p = new Patient();
$deactivate = $p->desactivarPaciente($idItem);
if($deactivate){
    echo '
				<script>
					window.location.href = "'.BASE_DIR.'pacientes";
				</script>
			';

}

