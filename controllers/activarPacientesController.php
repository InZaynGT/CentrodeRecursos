<?php
permiso(1);
require_once 'models/pacientesModel.php';
$p = new Patient();
$activate = $p->activarPaciente($idItem);
if($activate){
    echo '
				<script>
					window.location.href = "'.BASE_DIR.'pacientes";
				</script>
			';

}
