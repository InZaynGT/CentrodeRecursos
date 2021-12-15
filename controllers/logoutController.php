<?php
session_destroy();
	echo '
		<script>
			window.location ="'.BASE_DIR.'";
		</script>
	';
?>