<?php

require_once 'models/medicamentoModel.php';
$p = new Medicamento();
$deactivate = $p->eliminarMedicamento($idItem);
if($deactivate){
    echo '
				<script>
					window.location.href = "'.BASE_DIR.'medicamentos";
				</script>
			';

}
