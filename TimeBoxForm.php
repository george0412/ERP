<?php
	require_once("class.user.php");
	require_once("session.php");
	require_once ("class.ddlseguimiento.php");
	require_once ("class.seguimiento.php");
	require_once("class.proyectos.php");
	require_once("class.auxiliar.php");
	
	$auth_user = new USER();
	$ddl = new DDLSeguimiento();
	$crud = new crud();
	$ddlproyectos = new DDLProyectos();
	$auxiliar = new crudAux();
	
	$user_id = $_SESSION['user_session'];
	
	$stmt = $auth_user->runQuery("SELECT
				  U.id_usuario,
				  U.nombre_usuario,
				  U.apellidos_usuario,
				  U.correo_usuario,
				  U.contrasena_usuario,
				  U.id_tipousuario,
				  TU.tipo_usuario,
				  Z.id_zona,
				  Z.zona
				FROM T_ZonaUsuario AS ZU
				INNER JOIN T_Usuarios AS U
				ON  ZU.id_usuario = U.id_usuario
				INNER JOIN T_Zona AS Z
				ON ZU.id_zona = Z.id_zona
				INNER JOIN T_TipoUsuario AS TU
				ON U.id_tipousuario = TU.id_tipousuario
				WHERE U.id_usuario=:user_id");
	$stmt->execute(array(":user_id"=>$user_id));
	
	$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
	$zona = intval($userRow['id_zona']);
	

	
	if(isset($_POST['confirmar']))
	{
		header("Location: ClienteProyectosTab-DigitalCenter.php");
	}
?>

<?php 
	include_once('header13.php');
?>
<div id="page-title">
    <h2>Creación de TimeBox</h2>
    <p>Aprovechar</p>
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
				<p>La información no pudo ser procesada adecuadamente. Intenta de nuevo</p>
			</div>
		</div>
	</div>
<?php
	}
?>
<form id="prospecto" method="post" class="form-horizontal bordered-row" role="form">
	<div class="row">
		<div class="col-md-20">
			<div class="panel">
				<div class="panel-body">
					<h3 class="title-hero">
						Datos generales
					</h3>
					<div class="example-box-wrapper">
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Nombre del cliente</label>
							<div class="col-sm-4">
								<div class="input-prepend input-group">
									<select id="cliente" name="cliente" class="form-control" required>
										<option selected value="0">Selecciona el cliente deseado</option>
										<?php
											$ddl->viewClientes($zona);
										?>
									</select>
								</div>
							</div>
							<label for="" class="col-sm-2 control-label">Nombre TimeBox</label>
							<div class="col-sm-4">
								<div class="input-prepend input-group">
									<input id="nombre" name="nombre" type="text" class="form-control" value="">
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-2 control-label">Monto</label>
							<div class="col-sm-4">
								<div class="input-prepend input-group">
									<input id="monto" name="monto" type="text" class="form-control" value="0.00" onkeyup="getCommas(this);">
								</div>
							</div>
							<label for="" class="col-sm-2 control-label">Horas</label>
							<div class="col-sm-4">
								<div class="input-prepend input-group">
									<input id="monto" name="monto" type="text" class="form-control" value="0.00" onkeyup="getCommas(this);">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--BOTÓN PARA GENERAR-->	
			<div class="example-box-wrapper">
				<div class="panel">
					<div class="panel-body">
						<div class="bg-default content-box text-center pad20A mrg25T">
							<button id="generar" name="generar" type="button" class="btn btn-lg btn-primary" onclick="getModals();">Ingresar TimeBox</button>
						</div>
						<!--MODAL PRINCIPAL - GENERAR PROYECTO-->
						<div class="modal fade modalgenerar" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
							<div class="modal-dialog modal-lg">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
										<h4 class="modal-title">Dialogo de confirmacion</h4>
									</div>
									<div class="modal-body">
											La informacion será procesada ¿te gustaría confirmar el ingreso de este TimeBox? Una vez que confirmes, no podras cambiar la información.
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
										<button form="prospecto" id="confirmar" name="confirmar" type="submit" class="btn btn-success">Confirmar</button>
									</div>
								</div>
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
	const commaSeparateNumber = (x) => {
	  return x.toString().replace(/\D/g, "")
			.replace(/([0-9])([0-9]{2})$/, '$1.$2')  
			.replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",")
			;
	}
	const commaUnseparateNumber = (x) => {
	  return x.toString().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, "");
	}
	/*function (val)
	{
		if(event.which >= 37 && event.which <= 40)
		{
			event.preventDefault();
		}

		  return val
		  .replace(/\D/g, "")
		  .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",")
		;
	}*/
</script>
<!--SCRIPT PARA CALCULAR PORCENTAJES-->
<script type="text/javascript">
	function getCommas(e)
	{
		var aux = $(e).val();
		$(e).val(commaSeparateNumber(aux));
	}
</script>
<!--ATRIBUTOS PARA QUE APAREZCAN VARIOS MODALS-->
<script type="text/javascript">
	$('.modal').on('hidden.bs.modal', function (e) {
		if($('.modal').hasClass('in')) {
			$('body').addClass('modal-open');
		}    
	});
</script>
<!--SCRIPT PARA CONSEGUIR GRAND TOTAL-->
<script type="text/javascript">
	function getModals()
	{
		$('.modalgenerar').modal('show');
	}
</script>
<?php 
	include_once('footer.php');
?>
