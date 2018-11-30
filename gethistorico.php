<?php
		
	header('Content-type: application/json; charset=UTF-8');
		 
	require_once 'class.ddlcuenta.php';
	require_once("class.proyectos.php");
	
	$ddl = new DDL();
	$ddlproyectos = new DDLProyectos();
	
	if (isset($_POST['clienteVAS']) && !empty($_POST['clienteVAS'])) && isset($_POST['yearVAS']) && !empty($_POST['yearVAS']) 
	{  
	   $cliente = $_POST['cliente'];
	   $year = $_POST['year'];
	   $ddl->selectHistoricoVAS($cliente, $year);	
	}
	
	if (isset($_POST['cliente']) && !empty($_POST['cliente'])) {
	 if (isset($_POST['year']) && !empty($_POST['year'])) {  
	   $cliente = $_POST['cliente'];
	   $year = $_POST['year'];
	   $ddl->selectHistoricoAC($cliente, $year);
	 }	
	}
	
	if (isset($_POST['cliente_cuant']) && !empty($_POST['cliente_cuant'])) {
	 if (isset($_POST['year_cuant']) && !empty($_POST['year_cuant'])) {  
	   $cliente = $_POST['cliente_cuant'];
	   $year = $_POST['year_cuant'];
	   $ddl->selectHistoricoACuant($cliente, $year);
	 }	
	}
	if (isset($_POST['cliente_cuant2']) && !empty($_POST['cliente_cuant2'])) {
	 if (isset($_POST['year_cuant2']) && !empty($_POST['year_cuant2'])) {  
	   $cliente = $_POST['cliente_cuant2'];
	   $year = $_POST['year_cuant2'];
	   $ddl->selectHistoricoACuant($cliente, $year);
	 }	
	}
	if (isset($_POST['cliente_cuant3']) && !empty($_POST['cliente_cuant3'])) {
	 if (isset($_POST['year_cuant3']) && !empty($_POST['year_cuant3'])) {  
	   $cliente = $_POST['cliente_cuant3'];
	   $year = $_POST['year_cuant3'];
	   $ddl->selectHistoricoACuant($cliente, $year);
	 }	
	}
	if (isset($_POST['cliente_proyecto']) && !empty($_POST['cliente_proyecto'])) {
	 if (isset($_POST['year_proyecto']) && !empty($_POST['year_proyecto'])) {  
	   $cliente = $_POST['cliente_proyecto'];
	   $year = $_POST['year_proyecto'];
	   $ddlproyectos->selectProyectos($cliente, $year);
	 }	
	}
?>
