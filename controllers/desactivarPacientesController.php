<?php

require_once 'models/pacienteModel.php';
$p = new Patient();
$deactivate = $p->desactivarPaciente($idItem);
if($deactivate){
    echo '
				<script>
					window.location.href = "'.BASE_DIR.'pacientes";
				</script>
			';

}

