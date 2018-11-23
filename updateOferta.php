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
	if(isset($_GET['agente']))
	{
		$agente = intval($_GET['agente']);
	}
	else
	{
		header("Location: ReplantearOferta.php?noAgente");
	}
	
	if(isset($_POST['confirmar']))
	{
		$monto = isset($_POST['prox_oferta']) ? $_POST['prox_oferta'] : 0;
		if($ara->updateOferta($agente,$monto))
		{	
			header("Location: ReplantearOferta.php?updated");
		}
		else
		{
			header("Location: updateOferta.php?error=update");
		}
	}
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
	<h2>Replantear oferta</h2>
	<p>Valorar</p>
</div>
<?php
	if(isset($_GET['error']))
	{
?>
	<div class="example-box-wrapper">
		<div class="alert alert-close alert-danger">
			<a href="#" title="Close" class="glyph-icon alert-close-btn icon-remove"></a>
			<div class="bg-red alert-icon">
				<i class="glyph-icon icon-times"></i>
			</div>
			<div class="alert-content">
				<h4 class="alert-title">Error al ingresar los datos</h4>
				<p>La información no pudo ser procesada adecuadamente.</p>
			</div>
		</div>
	</div>
<?php
	}
?>
<form id="agente" method="post" class="form-horizontal bordered-row" role="form">
	<div class="panel">
		<div class="panel-body">
			<h3 class="title-hero">
				Tabla de agentes para replantear oferta
			</h3>
			<div class="example-box-wrapper">
				<table name="resumen" id="resumen" class="table table-striped table-bordered" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>Agente</th>
							<th>Oferta anterior</th>
							<th>Oferta actual</th>
							<th>Próxima oferta</th>
						</tr>	
					</thead>
					<tbody>
						<?php $ara->viewReplantearOfertaUpdate($agente); ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="example-box-wrapper">
		<div class="panel">
			<div class="panel-body">
				<div class="bg-default content-box text-center pad20A mrg25T">
					<button id="generar" name="generar" type="button" class="btn btn-lg btn-primary" data-toggle="modal" data-target=".bs-example-modalgenerar-lg">Actualizar oferta</button>
				</div>
				<div class="modal fade bs-example-modalgenerar-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h4 class="modal-title">Diálogo de confirmación</h4>
							</div>
							<div class="modal-body">
									La información será procesada, ¿te gustaria confirmar la actualización de la oferta del agente? Una vez que confirmes, no podras cambiar la información.
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
								<button form="agente" id="confirmar" name="confirmar" type="submit" class="btn btn-success">Confirmar</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
<!--SCRIPT PARA COMAS-->
<script type="text/javascript">
	function getCommas(e)
	{
		if(event.which >= 37 && event.which <= 40)
		{
			event.preventDefault();
		}

		$(e).val(function(index, value) 
		{
			return value
			.replace(/\D/g, "")
			.replace(/([0-9])([0-9]{2})$/, '$1.$2')  
			.replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",")
			;
		});
	}
</script>
<?php include_once 'footer.php';?>
