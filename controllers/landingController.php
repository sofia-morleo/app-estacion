<?php 

	$tpl = new Primel('views/landingView.html');

	// proteccion para averiguar si existe un parametro adicional dentro de lo que es la url de la seccion
	if(isset($_SECTION[1])){
		var_dump($_SECTION[1]);
	}
	
	// $tpl->assign("usuarios", 6666);
	// $tpl->assign("ACTUAL_YEAR",date('Y'));

	// $tpl->assign("author", "mattprofe@mb");

	$tpl->printToScreen();
 ?>