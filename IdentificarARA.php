<?php
	require_once("session.php");
	require_once("class.user.php");
	require_once("class.ARA.php");
	$auth_user = new USER();
	$ara = new ara();
	$user_id = $_SESSION['user_session'];
	
	$stmt = $auth_user->runQuery("SELECT
				  U.id_usuario,
				  U.nombre_usuario,
				  U.apellidos_usuario,
				  U.correo_usuario,
				  U.contrasena_usuario,
				  U.id_tipousuario,
				  TU.tipo_usuario
				FROM T_Usuarios AS U
				INNER JOIN T_TipoUsuario AS TU
				  ON U.id_tipousuario = TU.id_tipousuario
				WHERE U.id_usuario=:user_id");
	$stmt->execute(array(":user_id"=>$user_id));
	$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
?>
<?php include_once 'header9.php';?>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.2.6/js/dataTables.fixedColumns.min.js"></script>
<script type="text/javascript">
    /* Data table fixed columns */
	 $(document).ready(function() {
        var table = $('#resumen').DataTable( {
			scrollY:        "600px",
			scrollX:        true,
			scrollCollapse: true,
			paging:         false,
			"order":		false
        } );
        //new $.fn.dataTable.FixedColumns(table);
    } );

    $(document).ready(function() {
        $('.dataTables_filter input').attr("placeholder", "Buscar...");
    });
</script>
<div id="page-title">
	<h2>Identificar</h2>
	<p>Planear</p>
</div>
<div class="panel">
	<div class="panel-body">
		<h3 class="title-hero">
			Información obtenida por la proyección de las Unidades de Negocio del <?php print(date("Y")+1);?>.
		</h3>
		<div class="example-box-wrapper">
			<table id="resumen" name="resumen" id="datatable-tabletools" class="table table-striped table-bordered" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th>Tipo de Producto</th>
						<th>Número de proyectos</th>
						<th>Tipo de Agente</th>
						<th>Número de Agentes requeridos</th>
						<th>Número de Agentes disponibles</th>
						<th>Número de Agentes faltantes</th>
						<th>Sugerencias</th>
					</tr>	
				</thead>
				<tbody>
					<?php $ara->planeacionARA(date("Y")+1);?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php include_once 'footer.php';?>
