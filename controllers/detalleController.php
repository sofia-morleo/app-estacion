<?php 

	$tpl = new Primel('views/detalleView.html');

	 $tpl->assign("CHIPID",$_SECTION[1]);

	


	$tpl->printToScreen();
 ?>