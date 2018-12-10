<?php
	require_once("session.php");
	require_once("class.user.php");
	require_once("class.ddlcuenta.php");
	require_once("class.cuotas.php");
	require_once("class.proyectos.php");
	require_once("class.analisiscuant.php");
	require_once("class.checkPlaneacion.php");
	require_once("class.prospectos.php");
	
	$ddl = new DDLprospectos();
	$auth_user = new USER();
	
	
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
	
	$ddlproyectos = new DDLProyectos();
	$crudSits = new crudSits();
	
	if(isset($_POST['confirmar']))
	{
		//VARIABLES GLOBALES
		$cliente = intval($_POST['cliente_sits']);
		$year = date("Y");
		$valorajustado = str_replace(",","",$_POST['ajustado']);
		$valorajustado = intval($valorajustado);
		$cuota = str_replace(",","",$_POST['cuota']);
		$cuota = intval($cuota);
		$absoluto = str_replace(",","",$_POST['valor_absoluto']);
		$absoluto = intval($absoluto);
		
		//TOTAL DE ROWS DE LOS ESCENARIOS
		$totalRowsE1 = intval($_POST['totalRowsE1']);
		$totalRowsE2 = intval($_POST['totalRowsE2']);
		$totalRowsE3 = intval($_POST['totalRowsE3']);
		
		//INICIALIZAR LAS VARIABLES DE CUOTAS TOTALES DE LOS ESCENARIOS Y DE ESTACIONARIEDAD
		$cuotadeftotalE1 = 0;
		$cuotadeftotalE2 = 0;
		$cuotadeftotalE3 = 0;
		
		$estacionariedadEjecucion = 0; 
		$estacionariedadFacturacion = 0; 
		$estacionariedadCobranza = 0;
		$estacionariedadTotal = 0;
		
		/*CICLOS PARA COMPROBAR QUE LOS TOTALES DE LOS ESCENARIOS NO SEAN MENORES QUE LA CUOTA GENERAL
		//ESCENARIO 1
		for($x=1; $x<=$totalRowsE1;	$x++)
		{
			$cuotadeftotalE1 = intval($_POST['cuotadef'.$x]) + $cuotadeftotalE1;
		}
		if($cuota > $cuotadeftotalE1)
		{
			header("Location: Situacionform.php?cuotaE1");
		}
		//ESCENARIO 2
		for($x=1; $x<=$totalRowsE2;	$x++)
		{
			$cuotadeftotalE2 = intval($_POST['cuotadef_E2'.$x]) + $cuotadeftotalE2;
		}
		if($cuota > $cuotadeftotalE2)
		{
			header("Location: Situacionform.php?cuotaE2");
		}
		//ESCENARIO 3
		for($x=1; $x<=$totalRowsE3;	$x++)
		{
			$cuotadeftotalE3 = intval($_POST['cuotadef_E3'.$x]) + $cuotadeftotalE3;
		}
		if($cuota > $cuotadeftotalE3)
		{
			header("Location: Situacionform.php?cuotaE3");
		}
		*/
		//CICLO PARA COMPROBAR QUE LA ESTACIONARIEDAD ES IGUAL AL ESCENARIO 2
		/*for($z=1; $z<=$totalRowsE2; $z++)
		{
			$estacionariedadEjecucion = $estacionariedadEjecucion + intval($_POST['enero_E2_Eje' . $z])+intval($_POST['febrero_E2_Eje' . $z])+intval($_POST['marzo_E2_Eje' . $z])+intval($_POST['abril_E2_Eje' . $z])+
				intval($_POST['mayo_E2_Eje' . $z])+intval($_POST['junio_E2_Eje' . $z])+intval($_POST['julio_E2_Eje' . $z])+intval($_POST['agosto_E2_Eje' . $z])+
				intval($_POST['septiembre_E2_Eje' . $z])+intval($_POST['octubre_E2_Eje' . $z])+intval($_POST['noviembre_E2_Eje' . $z])+intval($_POST['diciembre_E2_Eje' . $z]);
			$estacionariedadFacturacion = $estacionariedadFacturacion + intval($_POST['enero_E2_Fact' . $z])+intval($_POST['febrero_E2_Fact' . $z])+intval($_POST['marzo_E2_Fact' . $z])+intval($_POST['abril_E2_Fact' . $z])+
				intval($_POST['mayo_E2_Fact' . $z])+intval($_POST['junio_E2_Fact' . $z])+intval($_POST['julio_E2_Fact' . $z])+intval($_POST['agosto_E2_Fact' . $z])+
				intval($_POST['septiembre_E2_Fact' . $z])+intval($_POST['octubre_E2_Fact' . $z])+intval($_POST['noviembre_E2_Fact' . $z])+intval($_POST['diciembre_E2_Fact' . $z]); 
			$estacionariedadCobranza = $estacionariedadCobranza + intval($_POST['enero_E2_Cobra' . $z])+intval($_POST['febrero_E2_Cobra' . $z])+intval($_POST['marzo_E2_Cobra' . $z])+intval($_POST['abril_E2_Cobra' . $z])+
				intval($_POST['mayo_E2_Cobra' . $z])+intval($_POST['junio_E2_Cobra' . $z])+intval($_POST['julio_E2_Cobra' . $z])+intval($_POST['agosto_E2_Cobra' . $z])+
				intval($_POST['septiembre_E2_Cobra' . $z])+intval($_POST['octubre_E2_Cobra' . $z])+intval($_POST['noviembre_E2_Cobra' . $z])+intval($_POST['diciembre_E2_Cobra' . $z]);
		}
		if($cuotadeftotalE2 > $estacionariedadEjecucion)
		{
			header("Location: Situacionform.php?estacionariedadEjecucion");
		}
		else if($cuotadeftotalE2 > $estacionariedadFacturacion)
		{
			header("Location: Situacionform.php?estacionariedadFacturacion");
		}
		else if($cuotadeftotalE2 > $estacionariedadCobranza)
		{
			header("Location: Situacionform.php?estacionariedadCobranza");
		}
		*/
		//INSERTAR ANALISIS GENERAL DE LOS ESCENARIOS
		$analisisE1 = $crudSits->insertSit($cliente,$year,$valorajustado,$cuota);
		$analisisE2 = $crudSits->insertSit2($cliente,$year,$valorajustado,$cuota);
		$analisisE3 = $crudSits->insertSit3($cliente,$year,$valorajustado,$cuota,$absoluto);
		
		//CICLO PARA INSERTAR DETALLE DE ANALISIS DE LOS ESCENARIOS
		//ESCENARIO 1
		if($analisisE1 != 0)
		{
			for($y=1; $y<=$totalRowsE1; $y++)
			{
				$cuotadef = $_POST['cuotadef'.$y];
				$cuotadef = str_replace(",","",$cuotadef);
				$cuotadef = intval($cuotadef);
				$costo1_1 = $_POST['costo'.$y];
				$costo1_1 = str_replace("%","",$costo1_1);
				$costo1_1 = intval($costo1_1)/100;
				$tipo = $_POST['tipo_ambito'.$y];
				$ambito = intval ($_POST['ambito'.$y]);
				$producto = intval($_POST['producto'.$y]);
				$nombre = $_POST['nombre'.$y];
				$estatus = intval($_POST['estatus'.$y]);
				
				if(!$crudSits->insertSitDetalle($analisisE1,$cuotadef,$tipo,$ambito,$producto,$nombre,$estatus,$costo1_1))
				{
					header("Location: Situacionform.php?error");
				}
			}
		}
		else
		{
			header("Location: Situacionform.php?error");
		}
		//ESCENARIO 2
		if($analisisE2 != 0)
		{
			for($y=1; $y<=$totalRowsE2; $y++)
			{
				$cuotadef = $_POST['cuotadef_E2'.$y];
				$cuotadef = str_replace(",","",$cuotadef);
				$cuotadef = intval($cuotadef);
				$costo1_1 = $_POST['costo_E2'.$y];
				$costo1_1 = str_replace("%","",$costo1_1);
				$costo1_1 = intval($costo1_1)/100;
				$tipo = $_POST['tipo_ambito_E2'.$y];
				$ambito = intval ($_POST['ambito_E2'.$y]);
				$producto = intval($_POST['producto'.$y.'_E2']);
				$nombre = $_POST['nombre_E2'.$y];
				$estatus = intval($_POST['estatus_E2'.$y]);
				//ESTACIONARIEDAD DE EJECUCION
				$anticipo = intval(str_replace(",","",$_POST['anticipo' . $y]));
				$mes1_Ej = intval(str_replace(",","",$_POST['enero_E2_Eje' . $y]));
				$mes2_Ej = intval(str_replace(",","",$_POST['febrero_E2_Eje' . $y]));
				$mes3_Ej = intval(str_replace(",","",$_POST['marzo_E2_Eje' . $y]));
				$mes4_Ej = intval(str_replace(",","",$_POST['abril_E2_Eje' . $y]));
				$mes5_Ej = intval(str_replace(",","",$_POST['mayo_E2_Eje' . $y]));
				$mes6_Ej = intval(str_replace(",","",$_POST['junio_E2_Eje' . $y]));
				$mes7_Ej = intval(str_replace(",","",$_POST['julio_E2_Eje' . $y]));
				$mes8_Ej = intval(str_replace(",","",$_POST['agosto_E2_Eje' . $y]));
				$mes9_Ej = intval(str_replace(",","",$_POST['septiembre_E2_Eje' . $y]));
				$mes10_Ej = intval(str_replace(",","",$_POST['octubre_E2_Eje' . $y]));
				$mes11_Ej = intval(str_replace(",","",$_POST['noviembre_E2_Eje' . $y]));
				$mes12_Ej = intval(str_replace(",","",$_POST['diciembre_E2_Eje' . $y]));
				//ESTACIONARIEDAD DE FACTURACION
				$mes1_Fa = intval(str_replace(",","",$_POST['enero_E2_Fact' . $y]));
				$mes2_Fa = intval(str_replace(",","",$_POST['febrero_E2_Fact' . $y]));
				$mes3_Fa = intval(str_replace(",","",$_POST['marzo_E2_Fact' . $y]));
				$mes4_Fa = intval(str_replace(",","",$_POST['abril_E2_Fact' . $y]));
				$mes5_Fa = intval(str_replace(",","",$_POST['mayo_E2_Fact' . $y]));
				$mes6_Fa = intval(str_replace(",","",$_POST['junio_E2_Fact' . $y]));
				$mes7_Fa = intval(str_replace(",","",$_POST['julio_E2_Fact' . $y]));
				$mes8_Fa = intval(str_replace(",","",$_POST['agosto_E2_Fact' . $y]));
				$mes9_Fa = intval(str_replace(",","",$_POST['septiembre_E2_Fact' . $y]));
				$mes10_Fa = intval(str_replace(",","",$_POST['octubre_E2_Fact' . $y]));
				$mes11_Fa = intval(str_replace(",","",$_POST['noviembre_E2_Fact' . $y]));
				$mes12_Fa = intval(str_replace(",","",$_POST['diciembre_E2_Fact' . $y]));
				//ESTACIONARIEDAD DE COBRANZA
				$mes1_Co = intval(str_replace(",","",$_POST['enero_E2_Cobra' . $y]));
				$mes2_Co = intval(str_replace(",","",$_POST['febrero_E2_Cobra' . $y]));
				$mes3_Co = intval(str_replace(",","",$_POST['marzo_E2_Cobra' . $y]));
				$mes4_Co = intval(str_replace(",","",$_POST['abril_E2_Cobra' . $y]));
				$mes5_Co = intval(str_replace(",","",$_POST['mayo_E2_Cobra' . $y]));
				$mes6_Co = intval(str_replace(",","",$_POST['junio_E2_Cobra' . $y]));
				$mes7_Co = intval(str_replace(",","",$_POST['julio_E2_Cobra' . $y]));
				$mes8_Co = intval(str_replace(",","",$_POST['agosto_E2_Cobra' . $y]));
				$mes9_Co = intval(str_replace(",","",$_POST['septiembre_E2_Cobra' . $y]));
				$mes10_Co = intval(str_replace(",","",$_POST['octubre_E2_Cobra' . $y]));
				$mes11_Co = intval(str_replace(",","",$_POST['noviembre_E2_Cobra' . $y]));
				$mes12_Co = intval(str_replace(",","",$_POST['diciembre_E2_Cobra' . $y]));
				
				if(!$crudSits->insertSitDetalle2($analisisE2,$cuotadef,$tipo,$ambito,$producto,$nombre,$estatus,$costo1_1,$anticipo,
				$mes1_Ej,$mes2_Ej,$mes3_Ej,$mes4_Ej,$mes5_Ej,$mes6_Ej,$mes7_Ej,$mes8_Ej,$mes9_Ej,$mes10_Ej,$mes11_Ej,$mes12_Ej,
				$mes1_Fa,$mes2_Fa,$mes3_Fa,$mes4_Fa,$mes5_Fa,$mes6_Fa,$mes7_Fa,$mes8_Fa,$mes9_Fa,$mes10_Fa,$mes11_Fa,$mes12_Fa,
				$mes1_Co,$mes2_Co,$mes3_Co,$mes4_Co,$mes5_Co,$mes6_Co,$mes7_Co,$mes8_Co,$mes9_Co,$mes10_Co,$mes11_Co,$mes12_Co))
				{
					header("Location: Situacionform.php?error");
				}
			}
		}
		//ESCENARIO 3
		if($analisisE3 != 0)
		{
			for($y=1; $y<=$totalRowsE3; $y++)
			{
				$cuotadef = $_POST['cuotadef_E3'.$y];
				$cuotadef = str_replace(",","",$cuotadef);
				$cuotadef = intval($cuotadef);
				$costo1_1 = $_POST['costo_E3'.$y];
				$costo1_1 = str_replace("%","",$costo1_1);
				$costo1_1 = intval($costo1_1)/100;
				$tipo = $_POST['tipo_ambito_E3'.$y];
				$ambito = intval ($_POST['ambito_E3'.$y]);
				$producto = intval($_POST['producto'.$y.'_E3']);
				$nombre = $_POST['nombre_E3'.$y];
				$estatus = intval($_POST['estatus_E3'.$y]);
				
				if(!$crudSits->insertSitDetalle3($analisisE3,$cuotadef,$tipo,$ambito,$producto,$nombre,$estatus,$costo1_1))
				{
					header("Location: Situacionform.php?error");
				}
			}
		}
		
		header("Location: sits.php?SitInserted");
	}
?>
<?php include_once "header.php"; ?>
<!--INPUT MASKS-->
<script type="text/javascript" src="../../assets/widgets/input-mask/inputmask.js"></script>
<script type="text/javascript">
	/* Input masks */

	$(function() { "use strict";
		$(".input-mask").inputmask();
	});

</script>

<!--BOOTSTRAP WIZARD-->
<!--<link rel="stylesheet" type="text/css" href="../../assets/widgets/wizard/wizard.css">-->
<script type="text/javascript" src="../../assets/widgets/wizard/wizard.js"></script>
<script type="text/javascript" src="../../assets/widgets/wizard/wizard-demo.js"></script>


<!--BOOTSTRAP TABS-->
<script type="text/javascript" src="../../assets/widgets/tabs/tabs.js"></script>
<div id="page-title">
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
					<p>Ocurrió un error al intentar ingresar los datos. Por favor vuelve a intentarlo.</p>
				</div>
			</div>
		</div>
	<?php
		}
		if(isset($_GET['cuotaE1']))
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
					<p>La suma entre las cuotas definidas en el <b>Escenario 1</b> debe ser igual o mayor a la cuota total.</p>
				</div>
			</div>
		</div>
	<?php
		}
		if(isset($_GET['cuotaE2']))
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
					<p>La suma entre las cuotas definidas en el <b>Escenario 2</b> debe ser igual o mayor a la cuota total.</p>
				</div>
			</div>
		</div>
	<?php
		}
		if(isset($_GET['cuotaE3']))
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
					<p>La suma entre las cuotas definidas en el <b>Escenario 3</b> debe ser igual o mayor a la cuota total.</p>
				</div>
			</div>
		</div>
	<?php
		}
		if(isset($_GET['estacionariedadEjecucion']))
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
					<p>La suma de la estacionariedad debe ser igual o mayor a la cuota total del escenario 2.</p>
				</div>
			</div>
		</div>
	<?php
		}
		if(isset($_GET['estacionariedadFacturacion']))
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
					<p>La suma entre los meses debe ser igual o mayor a la cuota total.</p>
				</div>
			</div>
		</div>
	<?php
		}
		if(isset($_GET['estacionariedadCobranza']))
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
					<p>La suma entre los meses debe ser igual o mayor a la cuota total.</p>
				</div>
			</div>
		</div>
	<?php
		}
	?>
	<h2>Análisis Cuantitativo</h2>
</div>
<!--CONTENIDO DE TODA LA PÁGINA-->
<form id="sits_form" method="POST">
	<!--SCRIPT PARA EL CALCULO DE LAS CUOTAS-->
	<script type="text/javascript">
		function getCuentas()
		{
			var zona = "<?php print($zona)?>";
			//$('#modal-loaderCuentas').show();
			$('#modal-contenCuentas').hide(); // show dynamic div
			$('#calculada').empty();
			$('#requerida').empty();
			$('#diferencia').empty();

			$.ajax
			({
				type: "POST",
				url: "getcuotas.php",
				data: {zona: zona},
				dataType: 'json'
			})
			.done(function(data)
			{
				console.log(data);
			   // $('#modal-loaderCuentas').show();
				$('#modal-contenCuentas').hide();
				$('#calculada').html(data.CuotaDefinida.replace(/\B(?=(\d{3})+(?!\d))/g, ","));
				$('#requerida').html(data.CuotaRequerida.replace(/\B(?=(\d{3})+(?!\d))/g, ","));
				var diferencia = data.CuotaRequerida-data.CuotaDefinida;
				$('#diferencia').html(diferencia);
				$('#modal-contenCuentas').show(); // show dynamic div
				//$('#modal-loaderCuentas').hide();    // hide ajax loader
			})
			.fail(function()
			{
				$('#modal-contenCuentas').html('<i class="glyphicon glyphicon-info-sign"></i> Algo salió mal, por favor inténtalo de nuevo.');
			});
		}
	</script>
	<!--MODAL TOTAL DE CUENTAS
	<a onclick="getCuentas();"class="btn btn-default" data-toggle="modal" data-target=".bs-example-modal2-lg">Total de cuotas</a>
	<div class="modal fade bs-example-modal2-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
					<h4 class="modal-title">Control de total de cuotas</h4>
				</div>
				<!--<div id="modal-loaderCuentas"class="spinner glyph-icon remove-border demo-icon tooltip-button icon-spin-6 icon-spin" title="icon-spin-6"></div>
				<div id="modal-contenCuentas" class="modal-body">
					<table class="table">
						<thead>
							<tr>
								<th>Cuota de ingresos calculada</th>
								<th>Cuota de ingresos requerida</th>
								<th>Diferencia</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td id="calculada"></td>
								<td id="requerida"></td>
								<td id="diferencia"></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>-->
	<!--SCRIPT MODAL RESULTADOS CUALITATIVOS-->
	<script type="text/javascript">
		function getHistorico(elem)
		{		
				var dataString = $('#cliente_sits').val();
				var dataString2 = new Date().getFullYear();
				$('#modal-loaderACual').show();
				$('#modal-contentACual').hide(); // show dynamic div
				$('#txt_circanterior').empty();
				$('#txt_cambanterior').empty();			
				$('#txt_rescpanterior').empty();
				$('#txt_pardiranterior').empty();
				$('#txt_ndanterior').empty();
				$('#txt_resrhanterior').empty();
				$('#txt_nfanterior').empty();
				$('#txt_rescapanterior').empty();
				$('#txt_relcapanterior').empty();
				$('#txt_reszianterior').empty();
				$('#txt_relzianterior').empty();
				$('#txt_contianterior').empty();
				$('#txt_conclanterior').empty();
				$('#txt_estvinanterior').empty();
				
				
			$.ajax
			({
				type: "POST",
				url: "gethistorico.php",
				data: {cliente: dataString, year: dataString2},
				dataType: 'json'
				
			})
			.done(function(data){
					console.log(data);
					$('#modal-loaderACual').show();
					$('#modal-contentACual').hide(); // hide dynamic div
					$('#txt_circanterior').html(data.circunstancias_sit);
					$('#txt_cambanterior').html(data.cambios_sit);			
					$('#txt_rescpanterior').html(data.resultados_sit);
					$('#txt_pardiranterior').html(data.paradigma_rh);
					$('#txt_ndanterior').html(data.niveldesarrollo_rh);
					$('#txt_resrhanterior').html(data.resultados_rh);
					$('#txt_nfanterior').html(data.nivelformacion_st);
					$('#txt_rescapanterior').html(data.resultados_st);
					$('#txt_relcapanterior').html(data.relacion_st);
					$('#txt_reszianterior').html(data.resultados_sz);
					$('#txt_relzianterior').html(data.relacion_sz);
					$('#txt_contianterior').html(data.continuidad_sz);
					$('#txt_conclanterior').html(data.conclusion);
					$('#txt_estvinanterior').html(data.estrategia_vinculacion);
					$('#modal-contentACual').show(); // show dynamic div
					$('#modal-loaderACual').hide();    // hide ajax loader
					
				})
				.fail(function(){
					$('#modal-contentACual').html('<i class="glyphicon glyphicon-info-sign"></i> Algo salió mal, por favor inténtalo de nuevo.');
				});
		};
	</script>
	<!--MODAL RESULTADOS CUALITATIVOS-->
	<a class="btn btn-default" data-toggle="modal" data-id="#cliente_sits :selected, #year_sits :selected" data-target=".bs-example-modal-lg">Resultados Cualitativos</a>
	<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Análisis Cualitativo del año anterior</h4>
				</div>
				<div id="modal-loaderACual"class="spinner glyph-icon remove-border demo-icon tooltip-button icon-spin-6 icon-spin" title="icon-spin-6"></div>
				<div id="modal-contentACual" class="modal-body">
					<table class="table">
						<thead>
							<tr>
								<th>Circunstancias</th>
								<th>Cambios</th>
								<th>Resultados</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td id="txt_circanterior"></td>
								<td id="txt_cambanterior"></td>
								<td id="txt_rescpanterior"></td>
							</tr>
						</tbody>
						<thead>
							<tr>
								<th>Paradigma directivo</th>
								<th>Nivel de desarrollo</th>
								<th>Resultados</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td id="txt_pardiranterior"></td>
								<td id="txt_ndanterior"></td>
								<td id="txt_resrhanterior"></td>
							</tr>
						</tbody>
						<thead>
							<tr>
								<th>Nivel de formación</th>
								<th>Resultados</th>
								<th>Relación</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td id="txt_nfanterior"></td>
								<td id="txt_rescapanterior"></td>
								<td id="txt_relcapanterior"></td>
							</tr>
						</tbody>
						<thead>
							<tr>
								<th>Resultados</th>
								<th>Relación</th>
								<th>Continuidad</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td id="txt_reszianterior"></td>
								<td id="txt_relzianterior"></td>
								<td id="txt_contianterior"></td>
							</tr>
						</tbody>
						<thead>
							<tr>
								<th>Conclusión general</th>
								<th>Estrategia de vinculación</th>
								
							</tr>
						</thead>
						<tbody>
							<tr>
								<td id="txt_conclanterior"></td>
								<td id="txt_estvinanterior"></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
							
	<!--SCRIPT MODAL RESULTADOS CUANITATIVOS-->
	<script type="text/javascript">
		function getVAS(e)
		{		
				var clienteVAS = $(e).val();
				var yearVAS = parseInt($('#yearpresente').val())-1;
			$.ajax
			({
				type: "POST",
				url: "gethistorico.php",
				data: {clienteVAS: clienteVAS, yearVAS: yearVAS},
				dataType: 'json'
				
			})
			.done(function(data){
					console.log(data);
					var valorAjustado = parseFloat(data.valor_ajustado),
					escenario2 = parseFloat(data.escenario2Ant),
					totalVentas = parseFloat(data.ventas),
					aprovechamiento = (ventas/valorAjustado)*100,
					valorAjustado = valorAjustado.toLocaleString(undefined, {minimumFractionDigits: 2}),
					escenario2 = escenario2.toLocaleString(undefined, {minimumFractionDigits: 2}),
					ventas = ventas.toLocaleString(undefined, {minimumFractionDigits: 2}),
					aprovechamiento = aprovechamiento.toFixed(2);
					
					$('#ajustado').html(valorAjustado);
					$('#escenario2Ant').html(escenario2);			
					$('#totalVentas').html(totalVentas);
					$('#aprovechamiento').html(aprovechamiento);
					$('#anioAnt').html(data.anios);
				})
		};
	
		function getHistoricoCuant()
		{		var dataString = $('#cliente_sits').val();
				var year = new Date().getFullYear();
				var dataString2 = parseInt(year)-1;
				$('#modal-loaderACuant').show();
				$('#modal-contentACuant').hide(); // show dynamic div
				$('#cuota1').empty();
				$('#vajustado1').empty();
				$('#cuotavaj1').empty();
				$('#realajus1').empty();
				$('#realvscuota1').empty();
				$('#realvsaj1').empty();
				$('#modal-contentACuant').show(); // show dynamic div
				$('#modal-loaderACuant').hide();
			$.ajax
			({
				type: "POST",
				url: "gethistorico.php",
				data: {cliente_cuant: dataString, year_cuant: dataString2},
				dataType: 'json'
				
			})
			.done(function(data){
					console.log(data);
					$('#modal-loaderACuant').show();
					$('#modal-contentACuant').hide(); // hide dynamic div
					$('#cuota1').html(data.cuota.replace(/\B(?=(\d{3})+(?!\d))/g, ","));
					$('#vajustado1').html(data.valor_ajustado.replace(/\B(?=(\d{3})+(?!\d))/g, ","));
					$('#cuotavaj1').html(data.VajCuota+'%');
					$('#realajus1').html(data.RealAprovechado.replace(/\B(?=(\d{3})+(?!\d))/g, ","));
					$('#realvscuota1').html(data.RealApCuota+'%');
					$('#realvsaj1').html(data.RealApVAj+'%');
					$('#modal-contentACuant').show(); // show dynamic div
					$('#modal-loaderACuant').hide();    // hide ajax loader
					
				})
				.fail(function(){
					$('#modal-contentACuant').html('<i class="glyphicon glyphicon-info-sign"></i> Algo salió mal, por favor inténtalo de nuevo.');
				});
		
				var dataString2 = parseInt(year)-2;
				$('#modal-loaderACuant').show();
				$('#modal-contentACuant').hide(); // show dynamic div
				$('#cuota2').empty();
				$('#vajustado2').empty();
				$('#cuotavaj2').empty();
				$('#realajus2').empty();
				$('#realvscuota2').empty();
				$('#realvsaj2').empty();
				$('#modal-contentACuant').show(); // show dynamic div
				$('#modal-loaderACuant').hide();
			$.ajax
			({
				type: "POST",
				url: "gethistorico.php",
				data: {cliente_cuant2: dataString, year_cuant2: dataString2},
				dataType: 'json'
				
			})
			.done(function(data){
					console.log(data);
					$('#modal-loaderACuant').show();
					$('#modal-contentACuant').hide(); // hide dynamic div
					$('#cuota2').html(data.cuota.replace(/\B(?=(\d{3})+(?!\d))/g, ","));
					$('#vajustado2').html(data.valor_ajustado.replace(/\B(?=(\d{3})+(?!\d))/g, ","));
					$('#cuotavaj2').html(data.VajCuota+'%');
					$('#realajus2').html(data.RealAprovechado.replace(/\B(?=(\d{3})+(?!\d))/g, ","));
					$('#realvscuota2').html(data.RealApCuota+'%');
					$('#realvsaj2').html(data.RealApVAj+'%');
					$('#modal-contentACuant').show(); // show dynamic div
					$('#modal-loaderACuant').hide();    // hide ajax loader
					
				})
				.fail(function(){
					$('#modal-contentACuant').html('<i class="glyphicon glyphicon-info-sign"></i> Algo salió mal, por favor inténtalo de nuevo.');
				});
		
		
				var dataString2 = parseInt(year)-3;
				$('#modal-loaderACuant').show();
				$('#modal-contentACuant').hide(); // show dynamic div
				$('#cuota3').empty();
				$('#vajustado3').empty();
				$('#cuotavaj3').empty();
				$('#realajus3').empty();
				$('#realvscuota3').empty();
				$('#realvsaj3').empty();
				$('#modal-contentACuant').show(); // show dynamic div
				$('#modal-loaderACuant').hide();
				
			$.ajax
			({
				type: "POST",
				url: "gethistorico.php",
				data: {cliente_cuant3: dataString, year_cuant3: dataString2},
				dataType: 'json'
				
			})
			.done(function(data){
					console.log(data);
					$('#modal-loaderACuant').show();
					$('#modal-contentACuant').hide(); // hide dynamic div
					$('#cuota3').html(data.cuota.replace(/\B(?=(\d{3})+(?!\d))/g, ","));
					$('#vajustado3').html(data.valor_ajustado.replace(/\B(?=(\d{3})+(?!\d))/g, ","));
					$('#cuotavaj3').html(data.VajCuota+'%');
					$('#realajus3').html(data.RealAprovechado.replace(/\B(?=(\d{3})+(?!\d))/g, ","));
					$('#realvscuota3').html(data.RealApCuota+'%');
					$('#realvsaj3').html(data.RealApVAj+'%');
					$('#modal-contentACuant').show(); // show dynamic div
					$('#modal-loaderACuant').hide();    // hide ajax loader
					
				})
				.fail(function(){
					$('#modal-contentACuant').html('<i class="glyphicon glyphicon-info-sign"></i> Algo salió mal, por favor inténtalo de nuevo.');
				});
		};
	</script>
	
	<!--MODAL RESULTADOS CUANTITATIVOS-->
	<a class="btn btn-default" data-toggle="modal" data-id="#cliente_sits :selected" data-target=".bs-example-modal1-lg">Resultados Cuantitativos</a>
	<div class="modal fade bs-example-modal1-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Resultados Cuantitativos de años anteriores</h4>
				</div>
				<div id="modal-loaderACuant" class="spinner glyph-icon remove-border demo-icon tooltip-button icon-spin-6 icon-spin" title="icon-spin-6"></div>
				<div id="modal-contentACuant" class="modal-body">
					<table class="table">
						<thead>
							<tr>
								<th colspan="6" style="text-align:center;"><?php echo date("Y")-1?></th>
							</tr>
							<tr>
								<th>Cuota</th>
								<th>Valor ajustado</th>
								<th>Cuota vs V. AJ %</th>
								<th>Real Aprovechado</th>
								<th>vs Cuota %</th>
								<th>vs V. AJ %</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td id="cuota1"></td>
								<td id="vajustado1"></td>
								<td id="cuotavaj1"></td>
								<td id="realajus1"></td>
								<td id="realvscuota1"></td>
								<td id="realvsaj1"></td>
							</tr>
						</tbody>
						<thead>
							<tr>
								<th colspan="6" style="text-align:center;"><?php echo date("Y")-2?></th>
							</tr>
							<tr>
								<th>Cuota</th>
								<th>Valor ajustado</th>
								<th>Cuota vs V. AJ</th>
								<th>Real Aprovechado</th>
								<th>vs Cuota</th>
								<th>vs V. AJ</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td id="cuota2"></td>
								<td id="vajustado2"></td>
								<td id="cuotavaj2"></td>
								<td id="realajus2"></td>
								<td id="realvscuota2"></td>
								<td id="realvsaj2"></td>
							</tr>
						</tbody>
						<thead>
							<tr>
								<th colspan="6" style="text-align:center;"><?php echo date("Y")-3?></th>
							</tr>
							<tr>
								<th>Cuota</th>
								<th>Valor ajustado</th>
								<th>Cuota vs V. AJ</th>
								<th>Real Aprovechado</th>
								<th>vs Cuota</th>
								<th>vs V. AJ</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td id="cuota3"></td>
								<td id="vajustado3"></td>
								<td id="cuotavaj3"></td>
								<td id="realajus3"></td>
								<td id="realvscuota3"></td>
								<td id="realvsaj3"></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
	<!--MODAL CALCULAR VALOR AJUSTADO Y ABSOLUTO-->
	<div class="modal fade bs-example-modalValores-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Recalcula el valor absoluto y ajustado del cliente seleccionado</h4>
				</div>
				<div id="modal-content" class="modal-body">
					<?php
					if($zona != 7)
					{
					?>
					<div class="content-box-wrapper">
						<div class="example-box-wrapper">
							<div class="row">
								<div class="form-group">
									<label for="" class="col-sm-2 control-label">Número de empleados</label>
									<div class="col-sm-4">
										<div class="input-prepend input-group">
											<input onkeyup="getAbsoluto();" type="text" class="form-control" id="empleados" name="empleados">
										</div>
									</div>
									<label for="" class="col-sm-2 control-label">Cobertura</label>
									<div class="col-sm-4">
										<div class="input-prepend input-group">
											<select onchange="getAbsoluto();" id="cobertura" name="cobertura" class="form-control">	
												<option value="">Selecciona la cobertura de la cuenta</option>
												<?php
													$ddl->selectCobertura();
												?>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="form-group">
									<label for="" class="col-sm-2 control-label">Dinamismo</label>
									<div class="col-sm-4">
										<div class="input-prepend input-group">
										   <select onchange="getAbsoluto();" name="dinamismo" id="dinamismo" class="form-control">	
											<option value="">Selecciona el dinamismo de la cuenta</option>
											<?php
													$ddl->selectDinamismo();
											?>
										   </select>                                
										</div>
									</div>
									<label for="" class="col-sm-2 control-label">Nivel de desarrollo del área</label>
									<div class="col-sm-4">
										<div class="input-prepend input-group">
											<select id="ndac" name="ndac" class="form-control" onChange="getAbsoluto();">	
												<option value="">Selecciona el nivel de desarrollo del área</option>
												<?php
													$ddl->selectNDAC();
												?>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="form-group">
									<label for="" class="col-sm-1 control-label">Sistemas de cómputo</label>
									<div class="col-sm-2">
										<div class="input-prepend input-group">
											<input type="checkbox" id="sistemas" name="sistemas" onChange="getAbsoluto();">
										</div>
									</div>
									<label for="" class="col-sm-1 control-label">Competencias organizacionales</label>
									<div class="col-sm-2">
										<div class="input-prepend input-group">
											<input type="checkbox" id="competencias" name="competencias" onChange="getAbsoluto();">
										</div>
									</div>
									<label for="" class="col-sm-1 control-label">Idiomas</label>
									<div class="col-sm-2">
										<div class="input-prepend input-group">
											<input type="checkbox" id="idiomas" name="idiomas" onChange="getAbsoluto();">
										</div>
									</div>
									<label for="" class="col-sm-1 control-label">Técnicos no desarrollables en U-Learning</label>
									<div class="col-sm-2">
										<div class="input-prepend input-group">
											<input type="checkbox" id="ulearning" name="ulearning" onChange="getAbsoluto();">
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="form-group">
									<label for="" class="col-sm-2 control-label">Valor Absoluto</label>
									<div class="col-sm-4">
										<div class="input-prepend input-group">
											<input type="text" class="form-control" id="valor_absoluto" name="valor_absoluto" readonly>
										</div>
									</div>
									<label for="" class="col-sm-2 control-label">Valor Ajustado</label>
									<div class="col-sm-4">
										<div class="input-prepend input-group">
											<input type="text" class="form-control" id="valor_ajustado" name="valor_ajustado" readonly>
										</div>
									</div>
								</div>
								<input type="hidden" id="validacion" name="validacion" value="0"/>
							</div>
						</div>
					</div>
					<?php
					}
					else
					{
					?>
					<div class="content-box-wrapper">
						<div class="example-box-wrapper">
							<div class="row">
								<div class="form-group">
									<label for="" class="col-sm-2 control-label">Tamaño de la organización</label>
									<div class="col-sm-4">
										<div class="input-prepend input-group">
											<select onchange="getAbsolutoDC();" id="tamano" name="tamano" class="form-control">	
												<option value="0">Selecciona el tamaño de la organización</option>
												<option value="1">Micro (200-500)</option>
												<option value="3">Chicas (501-1000)</option>
												<option value="5">Medianas (1001-2999)</option>
												<option value="7">Grandes (3000-5000)</option>
												<option value="10">Super grandes (5000- )</option>
											</select>
										</div>
									</div>
									<label for="" class="col-sm-2 control-label">Cursos Nivel 1 (Institucionales)</label>
									<div class="col-sm-4">
										<div class="input-prepend input-group">
											<select onchange="getAbsolutoDC();" id="cursoN1" name="cursoN1" class="form-control">	
												<option value="0">Selecciona la opción correspondiente</option>
												<option value="1">1</option>
												<option value="2">2</option>
												<option value="3">3</option>
												<option value="4">4</option>
												<option value="5">5</option>
												<option value="6">6</option>
												<option value="7">7</option>
												<option value="8">8</option>
												<option value="9">9</option>
												<option value="10">10</option>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="form-group">
									<label for="" class="col-sm-2 control-label">Cursos Nivel 2 (Técnicos)</label>
									<div class="col-sm-4">
										<div class="input-prepend input-group">
										   <select onchange="getAbsolutoDC();" id="cursoN2" name="cursoN2" class="form-control">	
												<option value="0">Selecciona la opción correspondiente</option>
												<option value="2">2</option>
												<option value="4">4</option>
												<option value="6">6</option>
												<option value="8">8</option>
												<option value="10">10</option>
												<option value="12">12</option>
												<option value="14">14</option>
												<option value="16">16</option>
												<option value="18">18</option>
												<option value="20">20</option>
											</select>
										</div>
									</div>
									<label for="" class="col-sm-2 control-label">Nivel desagregación proceso</label>
									<div class="col-sm-4">
										<div class="input-prepend input-group">
										   <input type="text" class="form-control" id="nivelDes" name="nivelDes" value="0" readonly/>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="form-group">
									<label for="" class="col-sm-2 control-label">Cursos Gestión</label>
									<div class="col-sm-4">
										<div class="input-prepend input-group">
											<input type="text" id="cursoGestion" name="cursoGestion" class="form-control" onkeyup="getAbsolutoDC();" value="0" />	
										</div>
									</div>
									<label for="" class="col-sm-2 control-label">Suma</label>
									<div class="col-sm-4">
										<div class="input-prepend input-group">
											<input type="text" id="sumaDC" name="sumaDC" class="form-control" onkeyup="" value="0" readonly/>	
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="form-group">
									<label for="" class="col-sm-2 control-label">Digitalización</label>
									<div class="col-sm-4">
										<div class="input-prepend input-group">
											<select onchange="getAbsolutoDC();" id="digitalizacion" name="digitalizacion" class="form-control">	
												<option value="0">Selecciona la opción correspondiente</option>
												<option value="0.25">Alta</option>
												<option value="0.5">Media</option>
												<option value="0.75">Baja</option>
											</select>
										</div>
									</div>
									<label for="" class="col-sm-2 control-label">Fabricación interna</label>
									<div class="col-sm-4">
										<div class="input-prepend input-group">
											<select onchange="getAbsolutoDC();" id="fabricacion" name="fabricacion" class="form-control">	
												<option value="0">Selecciona la opción correspondiente</option>
												<option value="0.25">Alta</option>
												<option value="0.5">Media</option>
												<option value="0.75">Baja</option>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="form-group">
									<label for="" class="col-sm-2 control-label">Políticas internas (Asignación de proveedores)</label>
									<div class="col-sm-4">
										<div class="input-prepend input-group">
											<select onchange="getAbsolutoDC();" id="politicas" name="politicas" class="form-control">	
												<option value="0">Selecciona la opción correspondiente</option>
												<option value="0.33">1</option>
												<option value="0.50">2</option>
												<option value="1">3</option>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="form-group">
									<label for="" class="col-sm-2 control-label">Valor Absoluto</label>
									<div class="col-sm-4">
										<div class="input-prepend input-group">
											<input type="text" class="form-control" id="valor_absoluto" name="valor_absoluto" readonly>
										</div>
									</div>
									<label for="" class="col-sm-2 control-label">Valor Ajustado</label>
									<div class="col-sm-4">
										<div class="input-prepend input-group">
											<input type="text" class="form-control" id="valor_ajustado" name="valor_ajustado" readonly>
										</div>
									</div>
								</div>
								<input type="hidden" id="validacion" name="validacion" value="0"/>
							</div>
						</div>
					</div>
					<?php
					}
					?>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
	<!--FORM WIZARD-->
	<div class="panel">
		<div class="panel-body">
			<h3 class="title-hero">
				Segmentado
			</h3>
			
			<div class="example-box-wrapper">
				<div id="form-wizard-1">
					<ul>
						<li><a id="tab1-link" href="#tab1" data-toggle="tab">Cliente</a></li>
						<li><a id="tab5-link" href="#tab5" data-toggle="tab">Escenario 3</a></li>
						<li><a id="tab4-link" href="#tab4" data-toggle="tab">Escenario 2</a></li>
						<li><a id="tab3-link" href="#tab3" data-toggle="tab">Escenario 1</a></li>
						<li><a id="tab6-link" href="#tab6" data-toggle="tab">Estacionariedad</a></li>
					</ul>
					<div class="tab-content">
						<!--TABLE PARA SELECCIONAR EL AÑO Y LA CUENTA-->
						<div class="tab-pane" id="tab1">
							<div class="content-box">
								<h3 class="content-box-header bg-default">
									Datos generales
								</h3>
								<div class="content-box-wrapper">
									<table class="table">
										<thead>
											<th>Cliente</th>
											<th>Año del análisis</th>
										</thead>
										<tbody>
											<tr>
												<td>
													<select id="cliente_sits" name="cliente_sits" class="form-control" onChange="getHistorico(this); getVAS(this); cloneSelect(this);">
														<option selected="selected">Selecciona el cliente deseado </option>
														<?php
															$zona = intval($userRow['id_zona']);
															$ddl = new DDL();
															$ddl->selectClienteCuantitativo($zona);
														?>			                
													</select>
												</td>
												<td>
													 <select readonly id="yearpresente" name="yearpresente" class="form-control">
														<option selected value="<?php echo date("Y")+1 ?>"><?php echo date("Y")+1 ?></option>
													</select>
												</td>
											</tr>
										</tbody>
									</table>
									<table class="table">   
										<thead>
											<tr>
												<th>Valor Ajustado del <?php print(date("Y")); ?></th>
												<th>Desafío</th>
												<th>% Desafío/Valor Ajustado</th>
												<th>Escenario 2 del <?php print(date("Y")); ?></th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td><input readonly type="text" class="form-control popover-button-default" data-content="Da clic aqui para actualizar el Valor Ajustado de este cliente" title="Alerta" data-trigger="focus" data-placement="top" data-toggle="modal" data-target=".bs-example-modalValores-lg" data-parsley-type="digits" name="ajustado" id="ajustado" onkeyup="getDivision(); getCalculos();"></td>
												<td><input type="text" class="form-control popover-button-default" data-content="Ingresa el monto que piensas vender el siguiente año" title="Alerta" data-trigger="focus" data-placement="top" data-parsley-type="digits" name="cuota" id="cuota" onkeyup="getDivision(); getCalculos();"></td>
												<td><input type="text" class="form-control" data-parsley-type="digits" name="cuotava" id="cuotava" disabled></td>
												<td><input readonly type="text" class="form-control" data-parsley-type="digits" name="escenario2Ant" id="escenario2Ant"></td>
											</tr>
										</tbody>
									</table>
									<table class="table">   
										<thead>
											<tr>
												<th>% de Aprovechamiento del <?php print(date("Y")); ?></th>
												<th>Total Ventas del <?php print(date("Y")); ?></th>
												<th>Años de la cuenta</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td><input readonly type="text" class="form-control" data-parsley-type="digits" name="aprovechamiento" id="aprovechamiento"></td>
												<td><input readonly type="text" class="form-control" data-parsley-type="digits" name="totalVentas" id="totalVentas"></td>
												<td><input readonly type="text" class="form-control" data-parsley-type="digits" name="anioAnt" id="anioAnt"></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab3">
							<div class="content-box">
								<h3 class="content-box-header bg-default">
									Escenario 1
								</h3>
								<div class="content-box-wrapper">
									<table class="table">   
										<thead>
											<tr>
												<th>Cliente</th>
												<th>Valor Ajustado</th>
												<th>Desafío</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td id="cliente_sits_copy1" name="cliente_sits_copy1"></td>
												<td><input readonly type="text" class="form-control" name="ajustado_copy" id="ajustado_copy"></td>
												<td><input readonly type="text" class="form-control" name="cuota_copy" id="cuota_copy"></td>
											</tr>
										</tbody>
									</table>
								</div>
								<div class="content-box-wrapper">
									<!--SCRIPT PARA CALCULAR EL PORCENTAJE DE VALOR AJUSTADO VS CUOTA-->
									<script type="text/javascript">
										function getDivision()
										{
											var ajustado = $('#ajustado').val();
											var cuota = $('#cuota').val();
											ajustado = commaUnseparateNumber(ajustado);
											cuota = commaUnseparateNumber(cuota);
											var total = Math.round((cuota/ajustado)*100);
											$('#cuotava').val(total+'%');
											$('#cuota').val(commaSeparateNumber(cuota));
											$('#cuota_copy').val(commaSeparateNumber(cuota));
											$('#cuota_copy_2').val(commaSeparateNumber(cuota));
											$('#cuota_copy_3').val(commaSeparateNumber(cuota));
										}
									</script>
									<!--TABLA CON CAMPOS DE VALOR AJUSTADO Y CUOTA-->
								</div>
								<div class="content-box-wrapper">
									<table class="table">
										<thead>
											<tr>
												<th>ID del Proyecto</th>
												<th>Nivel Jerárquico</th>
												<th>Familia de productos</th>
												<th>Productos</th>
												<th>Nombre del proyecto</th>
												<th>Estatus del proyecto</th>
												<th>Contribución</th>
												<th>%</th>
												<th>Costo del proyecto</th>
												<th>____Margen____</th>
											</tr>
										</thead>
										<tbody>
										   <?php  
											  //$x = $ddlproyectos->proyectosAnterioresSits($_REQUEST['cliente_sits']);
											  $x = 0;
											?>
											<tr id="tableProyectos0" class="clonedProyecto0" style="display:none">
												<td id="select_tipoambito">
													<input type="text" class="form-control" placeholder="Ejemplo: 56WAL16FDP" name="tipo_ambito<?php print($x)?>" id="tipo_ambito<?php print($x)?>">
												</td>
												<td id="select_ambito">
													<select id="ambito<?php print($x)?>" name="ambito<?php print($x)?>" class="form-control">
														<option value="" selected>Selecciona un ámbito</option>
														<?php
															$ddlproyectos->selectAmbito();
														?>
													</select>
												</td>
												<td id="select_familia">
													<select id="familia<?php print($x)?>" name="familia<?php print($x)?>" class="form-control">
														<option value="" selected>Selecciona una familia de productos</option>
														<?php
															$ddlproyectos->selectFamilia();
														?>
													</select>
												</td>
												<td id="select_producto">
													<select id="producto<?php print($x)?>" name="producto<?php print($x)?>" class="form-control">
														<option value="" selected>Selecciona un producto</option>
													</select>
												</td>
												<td id="txt_nombre">
													<input type="text" class="form-control" name="nombre<?php print($x)?>" id="nombre<?php print($x)?>">
												</td>
												<td id="select_estatus">
													<select id="estatus<?php print($x)?>" name="estatus<?php print($x)?>" class="form-control">
														<option value="" selected>Selecciona el estatus del proyecto</option>
														<?php
															$ddlproyectos->selectEstatus();
														?>
													</select>
												</td>
												<td id="txt_cuotadef">
													<input onkeyup="getCalculos<?php print($x)?>();" type="text" class="form-control" data-parsley-type="digits" name="cuotadef<?php print($x)?>" id="cuotadef<?php print($x)?>">
												</td>
												<td id="txt_porcuotadef">
													<input type="text" disabled class="form-control" name="por_cuotadef<?php print($x)?>" id="por_cuotadef<?php print($x)?>">
												</td>
												<td id="txt_costo">
													<input onkeyup="getCalculos<?php print($x)?>();" type="text" class="form-control input-mask" name="costo<?php print($x)?>" id="costo<?php print($x)?>" data-inputmask="&apos;mask&apos;:&apos;99%&apos;">
												</td>
												<td id="txt_margen">
													<input type="text" disabled class="form-control" name="margen<?php print($x)?>" id="margen<?php print($x)?>">
												</td>
											</tr>
										</tbody>
										<tfoot>
											<tr>	
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												<th>Totales</th>
												<th>
													<input type="text" name="total_E1" id="total_E1" readonly class="form-control">
												</th>
												<th>
													<input type="text" name="portotal_E1" id="portotal_E1" readonly class="form-control">
												</th>
												<th>
													<input type="text" name="costotal_E1" id="costotal_E1" readonly class="form-control">
												</th>
												<th>
													<input type="text" name="margentotal_E1" id="margentotal_E1" readonly class="form-control">
												</th>
											</tr>
										</tfoot>
									</table>
									<a href="#" id="add-proyecto" class="btn btn-alt btn-hover btn-primary">
										 <span>Agregar otro campo</span>
										 <i class="glyph-icon icon-arrow-right"></i>
									</a>
									<a id="btnDel-proyecto" href="#" class="btn btn-alt btn-hover btn-warning">
											<span>Eliminar un campo</span>
											<i class="glyph-icon icon-arrow-right"></i>
									</a>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab4">
							<div class="content-box">
								<h3 class="content-box-header bg-default">
									Escenario 2
								</h3>
								<div class="content-box-wrapper">
									<table class="table">   
										<thead>
											<tr>
												<th>Cliente</th>
												<th>Valor Ajustado</th>
												<th>Desafío</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td id="cliente_sits_copy2" name="cliente_sits_copy2"></td>
												<td><input readonly type="text" class="form-control" name="ajustado_copy_2" id="ajustado_copy_2"></td>
												<td><input readonly type="text" class="form-control" name="cuota_copy_2" id="cuota_copy_2"></td>
											</tr>
										</tbody>
									</table>
								</div>
								<div class="content-box-wrapper">
									<!--SCRIPT PARA CALCULAR EL PORCENTAJE DE VALOR AJUSTADO VS CUOTA-->
									<script type="text/javascript">
										function getDivision2()
										{
											var ajustado = $('#ajustado').val();
											var cuota = $('#cuota').val();
											var total = ((cuota/ajustado)*100).toFixed(2);
											$('#cuotava2').val(total+'%');
										}
									</script>
									<!--TABLA CON CAMPOS DE VALOR AJUSTADO Y CUOTA-->
								</div>
								<div class="content-box-wrapper">
									<table class="table">
										<thead>
											<tr>
												<th>ID del Proyecto</th>
												<th>Nivel Jerárquico</th>
												<th>Familia de productos</th>
												<th>Productos</th>
												<th>Nombre del proyecto</th>
												<th>Estatus del proyecto</th>
												<th>Contribución</th>
												<th>%</th>
												<th>Costo del proyecto</th>
												<th>____Margen____</th>
											</tr>
										</thead>
										<tbody>
											<tr id="tablaProyectos_E2_0" class="clonedProyecto_E20" style="display:none">
												<td id="select_tipoambito_E20">
													<input type="text" class="form-control" placeholder="Ejemplo: 56WAL16FDP" name="tipo_ambito_E20" id="tipo_ambito_E20">
												</td>
												<td id="select_ambito_E20">
													<select id="ambito_E20" name="ambito_E20" class="form-control">
														<option value="" selected>Selecciona un ámbito</option>
														<?php
															$ddlproyectos->selectAmbito();
														?>
													</select>
												</td>
												<td id="select_familia_E20">
													<select id="familia_E20" name="familia_E20" class="form-control">
														<option value="" selected>Selecciona una familia de productos</option>
														<?php
															$ddlproyectos->selectFamilia();
														?>
													</select>
												</td>
												<td id="select_producto_E20">
													<select id="producto0_E2" name="producto0_E2" class="form-control">
														<option value="" selected>Selecciona un producto</option>
													</select>
												</td>
												<td id="txt_nombre_E20">
													<input type="text" class="form-control" name="nombre_E20" id="nombre_E20">
												</td>
												<td id="select_estatus_E20">
													<select id="estatus_E20" name="estatus_E20" class="form-control">
														<option value="" selected>Selecciona el estatus del proyecto</option>
														<?php
															$ddlproyectos->selectEstatus();
														?>
													</select>
												</td>
												<td id="txt_cuotadef_E20">
													<input type="text" class="form-control" data-parsley-type="digits" name="cuotadef_E20" id="cuotadef_E20">
												</td>
												<td id="txt_porcuotadef_E20">
													<input type="text" disabled class="form-control" name="por_cuotadef_E20" id="por_cuotadef_E20">
												</td>
												<td id="txt_costo_E20">
													<input onkeyup="" type="text" class="form-control input-mask" name="costo_E20" id="costo_E20" data-inputmask="&apos;mask&apos;:&apos;99%&apos;">
												</td>
												<td id="txt_margen_E20">
													<input type="text" disabled class="form-control" name="margen_E20" id="margen_E20">
												</td>
											</tr>
										
										</tbody>
										<tfoot>
											<tr>	
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												<th>Totales</th>
												<th>
													<input id="total_E2" name="total_E2" type="text" readonly class="form-control">
												</th>
												<th>
													<input type="text" id="portotal_E2" name="portotal_E2" type="text" readonly class="form-control">
												</th>
												<th>
													<input type="text" id="costotal_E2" name="costotal_E2" type="text" readonly class="form-control">
												</th>
												<th>
													<input type="text" id="margentotal_E2" name="margentotal_E2" type="text" readonly class="form-control">
												</th>
											</tr>
										</tfoot>
									</table>
									<a href="#" id="add-proyecto2" class="btn btn-alt btn-hover btn-primary">
										 <span>Agregar otro campo</span>
										 <i class="glyph-icon icon-arrow-right"></i>
									</a>
									<a id="btnDel-proyecto2" href="#" class="btn btn-alt btn-hover btn-warning">
											<span>Eliminar un campo</span>
											<i class="glyph-icon icon-arrow-right"></i>
									</a>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab5">
							<div class="content-box">
								<h3 class="content-box-header bg-default">
									Escenario 3
								</h3>
								<!--TABLA CON CAMPOS DE VALOR AJUSTADO Y CUOTA-->
								<div class="content-box-wrapper">
									<table class="table">   
										<thead>
											<tr>
												<th>Cliente</th>
												<th>Valor Ajustado</th>
												<th>Desafío</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td id="cliente_sits_copy3" name="cliente_sits_copy3"></td>
												<td><input readonly type="text" class="form-control" name="ajustado_copy_3" id="ajustado_copy_3"></td>
												<td><input readonly type="text" class="form-control" name="cuota_copy_3" id="cuota_copy_3"></td>
											</tr>
										</tbody>
									</table>
								</div>
								<div class="content-box-wrapper">
									<table class="table">
										<thead>
											<tr>
												<th>__ID del Proyecto__</th>
												<th>Nivel Jerárquico</th>
												<th>Familia de productos</th>
												<th>Productos</th>
												<th>Nombre proyecto</th>
												<th>Estatus del proyecto</th>
												<th>Contribución</th>
												<th>%</th>
												<th>Costo del proyecto</th>
												<th>____Margen____</th>
											</tr>
										</thead>
										<tbody>
											<tr id="tablaProyectos_E3_1" class="clonedProyecto_E3">
												<td id="select_tipoambito_E3" >
													<input type="text" class="form-control" placeholder="Ejemplo: 56WAL16FDP" name="tipo_ambito_E31" id="tipo_ambito_E31">
												</td>
												<td id="select_ambito_E3">
													<select id="ambito_E31" name="ambito_E31" class="form-control">
														<option value="">Selecciona un ámbito</option>
														<?php
															$ddlproyectos->selectAmbito();
														?>
													</select>
												</td>
												<td id="select_familia_E3">
													<select id="familia_E31" name="familia_E31" class="form-control" onchange="getProducto1_E3(this);">
														<option value="" selected>Selecciona una familia de productos</option>
														<?php
															$ddlproyectos->selectFamilia();
														?>
													</select>
												</td>
												<td id="select_producto_E3">
													<select id="producto1_E3" name="producto1_E3" class="form-control">
														<option value="" selected>Selecciona un producto</option>
													</select>
												</td>
												<td id="txt_nombre_E3">
													<input type="text" class="form-control" name="nombre_E31" id="nombre_E31">
												</td>
												<td id="select_estatus_E3">
													<select id="estatus_E31" name="estatus_E31" class="form-control">
														<option value="">Selecciona el estatus del proyecto</option>
														<?php
															$ddlproyectos->selectEstatus();
														?>
													</select>
												</td>
												<td id="txt_cuotadef_E3">
													<input onkeyup="getCalculosE3(this);" type="text" class="form-control" data-parsley-type="digits" name="cuotadef_E31" id="cuotadef_E31">
												</td>
												<td id="txt_porcuotadef_E3">
													<input type="text" disabled class="form-control" name="por_cuotadef_E31" id="por_cuotadef_E31">
												</td>
												<td id="txt_costo_E3">
													<input onkeyup="getCalculosE3(this);" type="text" class="form-control input-mask" name="costo_E31" id="costo_E31" data-inputmask="&apos;mask&apos;:&apos;99%&apos;">
												</td>
												<td id="txt_margen_E3">
													<input type="text" disabled class="form-control" name="margen_E31" id="margen_E31">
												</td>
											</tr>
										</tbody>
										<tfoot>
											<tr>	
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												<th>Totales</th>
												<th>
													<input id="total_E3" name="total_E3" type="text" readonly class="form-control">
												</th>
												<th>
													<input id="portotal_E3" name="portotal_E3" type="text" readonly class="form-control">
												</th>
												<th>
													<input id="costotal_E3" name="costotal_E3" type="text" readonly class="form-control">
												</th>
												<th>
													<input id="margentotal_E3" name="margentotal_E3" type="text" readonly class="form-control">
												</th>
											</tr>
										</tfoot>
									</table>
									<a href="#" id="add-proyecto3" class="btn btn-alt btn-hover btn-primary">
										 <span>Agregar otro campo</span>
										 <i class="glyph-icon icon-arrow-right"></i>
									</a>
									<a id="btnDel-proyecto3" href="#" class="btn btn-alt btn-hover btn-warning">
											<span>Eliminar un campo</span>
											<i class="glyph-icon icon-arrow-right"></i>
									</a>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab6">
							<div class="content-box">
								<h3 class="content-box-header bg-default">
									Estacionariedad
								</h3>
								<div class="content-box-wrapper">
									<table id="" class="table" width="100%">
										<thead>
											<tr>
												<th colspan="3"></th>
												<th>Anticipo</th>
												<th>____Enero____</th>
												<th>___Febrero___</th>
												<th colspan="2">____Marzo____</th>
												<th colspan="2">____Abril____</th>
												<th colspan="2">____Mayo____</th>
												<th colspan="2">____Junio____</th>
												<th colspan="2">____Julio____</th>
												<th>____Agosto____</th>
												<th>_Septiembre_</th>
												<th>___Octubre___</th>
												<th>_Noviembre_</th>
												<th>_Diciembre_</th>
												<th>Total</th>
												<th>Desafío escenario 2</th>
											</tr>
										</thead>
										<tbody id="bodyEstacionariedad">
											
										</tbody>
									</table>
									<!--<table class="table">
										<thead>
											<tr>
												<th>Total Escenario</th>
												<th>Total Estacionariedad</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td><input type="text" class="form-control" id="total_escenario_E2" name="total_escenario_E2" readonly required></td>
												<td><input type="text" class="form-control" id="total_estacionariedad_E2" name="total_estacionariedad_E2" readonly required></td>
											</tr>
										</tbody>
									</table>-->
								</div>
								<!--GENERAR PROYECCIÓN-->
								<div class="example-box-wrapper">
									<div class="panel">
										<div class="panel-body">
											<div class="bg-default content-box text-center pad20A mrg25T">
												<button id="generar" name="generar" type="button" class="btn btn-lg btn-primary" onclick="botonGenerar();">Generar Proyección</button>
											</div>
											<!--MODAL EJECUCIÓN-->
											<div class="modal fade modal-ejecucion" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
												<div class="modal-dialog modal-lg">
													<div class="modal-content">
														<div class="modal-header">
															<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
															<h4 class="modal-title">Error</h4>
														</div>
														<div class="modal-body">
																El total de Ejecución no cuadra con el total del Escenario 2. Revisa la información.
														</div>
														<div class="modal-footer">
															<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
														</div>
													</div>
												</div>
											</div>
											<!--MODAL FACTURACION-->
											<div class="modal fade modal-facturacion" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
												<div class="modal-dialog modal-lg">
													<div class="modal-content">
														<div class="modal-header">
															<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
															<h4 class="modal-title">Error</h4>
														</div>
														<div class="modal-body">
																El total de Facturación no cuadra con el total del Escenario 2. Revisa la información.
														</div>
														<div class="modal-footer">
															<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
														</div>
													</div>
												</div>
											</div>
											<!--MODAL COBRANZA-->
											<div class="modal fade modal-cobranza" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
												<div class="modal-dialog modal-lg">
													<div class="modal-content">
														<div class="modal-header">
															<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
															<h4 class="modal-title">Error</h4>
														</div>
														<div class="modal-body">
																El total de Cobranza no cuadra con el total del Escenario 2. Revisa la información.
														</div>
														<div class="modal-footer">
															<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
														</div>
													</div>
												</div>
											</div>
											<!--MODAL DE CONFIRMACION-->
											<div class="modal fade bs-example-modalgenerar-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
												<div class="modal-dialog modal-lg">
													<div class="modal-content">
														<div class="modal-header">
															<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
															<h4 class="modal-title">Diálogo de confirmación</h4>
														</div>
														<div class="modal-body">
																La información será procesada, ¿te gustaría confirmar el ingreso de este análisis?. Una vez que confirmes, no podrás cambiar la información.
														</div>
														<div class="modal-footer">
															<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
															<button form="sits_form" id="confirmar" name="confirmar" type="submit" class="btn btn-success">Confirmar</button>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!--<ul class="pager wizard">
							<li class="previous first" style="display:none;"><a href="#">Primer página</a></li>
							<li class="previous"><a href="#">Anterior</a></li>
							<li class="next last" style="display:none;"><a href="#">Última pagina</a></li>
							<li class="next"><a href="#">Siguiente</a></li>
						</ul>-->
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--INPUTS AUXILIARES-->
	<input type="hidden" id="totalRowsE1" name="totalRowsE1" class="form-control" value="1">
	<input type="hidden" id="totalRowsE2" name="totalRowsE2" class="form-control" value="1">
	<input type="hidden" id="totalRowsE3" name="totalRowsE3" class="form-control" value="1">
	<!--SCRIPT PARA ABRIR MODAL DE GENERAR-->
	<script type="text/javascript">
		function getAbsolutoDC()
		{
			var tamano = parseInt($('#tamano').val());
			var nivel1 = $('#cursoN1').val();
			var nivel2 = $('#cursoN2').val();
			var nivelDes = (parseInt(nivel1)+parseInt(nivel2))*3;
			var cursoGestion = $('#cursoGestion').val();
			var suma = parseInt(nivelDes)+parseInt(cursoGestion);
			var digitalizacion = parseFloat($('#digitalizacion').val());
			var fabricacion = parseFloat($('#fabricacion').val());
			var politicas = parseFloat($('#politicas').val());
			var absoluto = (40000*suma)/digitalizacion;
			var ajustado = (absoluto*fabricacion)*politicas;
			
			$('#nivelDes').val(nivelDes);
			$('#sumaDC').val(suma);
			$('#valor_absoluto').val(commaSeparateNumber(absoluto));
			$('#valor_ajustado').val(commaSeparateNumber(ajustado));
		}
		function botonGenerar()
		{
			var escenario2 = commaUnseparateNumber($('#total_E2').val());
			var num = $('.clonedProyecto_E2').length;
			var ejecucion = 0;
			var facturacion = 0;
			var cobranza = 0;
			for(i = 1; i <= num; i++)
			{
				ejecucion = ejecucion + parseInt(commaUnseparateNumber($('#total_Eje'+i).html()));
				facturacion = facturacion + parseInt(commaUnseparateNumber($('#total_Fact'+i).html()));
				cobranza = cobranza + parseInt(commaUnseparateNumber($('#total_Cobra'+i).html()));
			}
			var diferenciaEjecucion = escenario2 - ejecucion;
			var diferenciaFacturacion = escenario2 - facturacion;
			var diferenciaCobranza = escenario2 - cobranza;
						
			if(diferenciaEjecucion != 0)
			{
				$('.modal-ejecucion').modal('show');
			}
			else if(diferenciaFacturacion != 0)
			{
				$('.modal-facturacion').modal('show');
			}
			else if(diferenciaCobranza != 0)
			{
				$('.modal-cobranza').modal('show');
			}
			else if(diferenciaEjecucion == 0 && diferenciaFacturacion == 0 && diferenciaCobranza == 0)
			{
				$('.bs-example-modalgenerar-lg').modal('show');
			}
		}
	</script>
	<!--SCRIPT PARA VALOR AJUSTADO Y ABSOLUTO-->
	<script type="text/javascript">
		function cloneSelect(e)
		{
			$('#cliente_sits_copy1').empty();
			$('#cliente_sits_copy2').empty();
			$('#cliente_sits_copy3').empty();
			$('#cliente_sits').clone().attr('disabled', true).attr('id', 'cliente_sits_copy1').attr('name', 'cliente_sits_copy1').attr('onChange','').val($('#cliente_sits').val()).appendTo('#cliente_sits_copy1');
			$('#cliente_sits').clone().attr('disabled', true).attr('id', 'cliente_sits_copy2').attr('name', 'cliente_sits_copy2').attr('onChange','').val($('#cliente_sits').val()).appendTo('#cliente_sits_copy2');
			$('#cliente_sits').clone().attr('disabled', true).attr('id', 'cliente_sits_copy3').attr('name', 'cliente_sits_copy3').attr('onChange','').val($('#cliente_sits').val()).appendTo('#cliente_sits_copy3');
		}
	</script>
	<script type="text/javascript">
		function getAbsoluto()
		{
			var empleados = $('#empleados').val();
			$('#empleados').val(commaSeparateNumber(empleados));
			empleados = commaUnseparateNumber(empleados);
			var cobertura = $('#cobertura').val();
			var dinamismo = $('#dinamismo').val();
			var ndac = $('#ndac').val();
			
			//cobertura
			if(cobertura == 1)
			{
				var cobertura_precio = 0.5;   
			}
			else if(cobertura == 2)
			{
				var cobertura_precio = 0.75;
			}
			else if(cobertura == 3)
			{
				var cobertura_precio = 1;
			}
			else
			{
				var cobertura_precio = 1;
			}
			
			//dinamismo
			if(dinamismo == 1)
			{
				var dinamismo_precio = 0.5;
			}
			else if(dinamismo == 2)
			{
				var dinamismo_precio = 0.75;
			}
			else if(dinamismo == 3)
			{
				var dinamismo_precio = 1;
			}
			else if(dinamismo == 4)
			{
				var dinamismo_precio = 1.25;
			}
			else if(dinamismo == 5)
			{
				var dinamismo_precio = 1.5;
			}
			else
			{
				var dinamismo_precio = 1;
			}
			
			//ndac
			if(ndac == 1)
			{
				var ndac_precio = 0.5;
			}
			else if(ndac == 2)
			{
				var ndac_precio = 0.5;
			}
			else if(ndac == 3)
			{
				var ndac_precio = 0.75;
			}
			else if(ndac == 4)
			{
				var ndac_precio = 1;
			}
			else if(ndac == 5)
			{
				var ndac_precio = 1.25;
			}
			else if(ndac == 6)
			{
				var ndac_precio = 1.5;
			}
			else if(ndac == 7)
			{
				var ndac_precio = 1.75;
			}
			else
			{
				var ndac_precio = 1;
			}
			
			//absoluto
			var absoluto = Math.round(((empleados/20)*cobertura_precio*(2500*12))*(ndac_precio)*(dinamismo_precio));
			$('#valor_absoluto').val(commaSeparateNumber(absoluto));
			
			
			//checkboxes
			if ($('#sistemas').is(':checked')) 
			{
				var sistemas = 0.05;
			}
			else
			{
				var sistemas = 0;
			}
			
			if ($('#competencias').is(':checked')) 
			{
				var competencias = 0.05;
			}
			else
			{
				var competencias = 0;
			}
			
			if ($('#idiomas').is(':checked')) 
			{
				var idiomas = 0.1;
			}
			else
			{
				var idiomas = 0;
			}
			
			if ($('#ulearning').is(':checked')) 
			{
				var ulearning = 0.1;
			}
			else
			{
				var ulearning = 0;
			}
			
			var ajustado = Math.round(absoluto - (absoluto*sistemas)-(absoluto*competencias)-(absoluto*idiomas)-(absoluto*ulearning));
			$('#valor_ajustado').val(commaSeparateNumber(ajustado));
			$('#ajustado').val(commaSeparateNumber(ajustado));
			$('#ajustado_copy').val(commaSeparateNumber(ajustado));
			$('#ajustado_copy_2').val(commaSeparateNumber(ajustado));
			$('#ajustado_copy_3').val(commaSeparateNumber(ajustado));
		}
	</script>
	<!--NO ENTER SCRIPT-->
	<script type="text/javascript">
		$(document).ready(function() {
		  $(window).keydown(function(event){
			if(event.keyCode == 13) {
			  event.preventDefault();
			  return false;
			}
		  });
		});
	</script>
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
	<!--SCRIPT PARA DDL PRODUCTOS-->
	<script type="text/javascript">
		function getProducto1(ele)
		{
			var dataString = $(ele);
			var familia= dataString.val();
			
			$.ajax
			({
				type: "POST",
				url: "get_producto.php",
				data: {familia: familia},
				success: function(html)
				{
					dataString.parent().parent().find("#producto1").html(html);
					
				}
			})
		};
		function getProducto2(ele)
		{
			var dataString = $(ele);
			var familia= dataString.val();
			
			$.ajax
			({
				type: "POST",
				url: "get_producto.php",
				data: {familia: familia},
				success: function(html)
				{
					dataString.parent().parent().find("#producto2").html(html);
					
				}
			})
		};
		function getProducto3(ele)
		{
			var dataString = $(ele);
			var familia= dataString.val();
			
			$.ajax
			({
				type: "POST",
				url: "get_producto.php",
				data: {familia: familia},
				success: function(html)
				{
					dataString.parent().parent().find("#producto3").html(html);
					
				}
			})
		};
		function getProducto4(ele)
		{
			var dataString = $(ele);
			var familia= dataString.val();
			
			$.ajax
			({
				type: "POST",
				url: "get_producto.php",
				data: {familia: familia},
				success: function(html)
				{
					dataString.parent().parent().find("#producto4").html(html);
					
				}
			})
		};
		function getProducto5(ele)
		{
			var dataString = $(ele);
			var familia= dataString.val();
			
			$.ajax
			({
				type: "POST",
				url: "get_producto.php",
				data: {familia: familia},
				success: function(html)
				{
					dataString.parent().parent().find("#producto5").html(html);
					
				}
			})
		};
		function getProducto6(ele)
		{
			var dataString = $(ele);
			var familia= dataString.val();
			
			$.ajax
			({
				type: "POST",
				url: "get_producto.php",
				data: {familia: familia},
				success: function(html)
				{
					dataString.parent().parent().find("#producto6").html(html);
					
				}
			})
		};
		function getProducto7(ele)
		{
			var dataString = $(ele);
			var familia= dataString.val();
			
			$.ajax
			({
				type: "POST",
				url: "get_producto.php",
				data: {familia: familia},
				success: function(html)
				{
					dataString.parent().parent().find("#producto7").html(html);
					
				}
			})
		};
		function getProducto8(ele)
		{
			var dataString = $(ele);
			var familia= dataString.val();
			
			$.ajax
			({
				type: "POST",
				url: "get_producto.php",
				data: {familia: familia},
				success: function(html)
				{
					dataString.parent().parent().find("#producto8").html(html);
					
				}
			})
		};
		function getProducto9(ele)
		{
			var dataString = $(ele);
			var familia= dataString.val();
			
			$.ajax
			({
				type: "POST",
				url: "get_producto.php",
				data: {familia: familia},
				success: function(html)
				{
					dataString.parent().parent().find("#producto9").html(html);
					
				}
			})
		};
		function getProducto10(ele)
		{
			var dataString = $(ele);
			var familia= dataString.val();
			
			$.ajax
			({
				type: "POST",
				url: "get_producto.php",
				data: {familia: familia},
				success: function(html)
				{
					dataString.parent().parent().find("#producto10").html(html);
					
				}
			})
		};
		
		function getProducto1_E2(ele)
		{
			var dataString = $(ele);
			var familia= dataString.val();
			
			$.ajax
			({
				type: "POST",
				url: "get_producto.php",
				data: {familia: familia},
				success: function(html)
				{
					dataString.parent().parent().find("#producto1_E2").html(html);
					
				}
			})
		};
		function getProducto2_E2(ele)
		{
			var dataString = $(ele);
			var familia= dataString.val();
			
			$.ajax
			({
				type: "POST",
				url: "get_producto.php",
				data: {familia: familia},
				success: function(html)
				{
					dataString.parent().parent().find("#producto2_E2").html(html);
					
				}
			})
		};
		function getProducto3_E2(ele)
		{
			var dataString = $(ele);
			var familia= dataString.val();
			
			$.ajax
			({
				type: "POST",
				url: "get_producto.php",
				data: {familia: familia},
				success: function(html)
				{
					dataString.parent().parent().find("#producto3_E2").html(html);
					
				}
			})
		};
		function getProducto4_E2(ele)
		{
			var dataString = $(ele);
			var familia= dataString.val();
			
			$.ajax
			({
				type: "POST",
				url: "get_producto.php",
				data: {familia: familia},
				success: function(html)
				{
					dataString.parent().parent().find("#producto4_E2").html(html);
					
				}
			})
		};
		function getProducto5_E2(ele)
		{
			var dataString = $(ele);
			var familia= dataString.val();
			
			$.ajax
			({
				type: "POST",
				url: "get_producto.php",
				data: {familia: familia},
				success: function(html)
				{
					dataString.parent().parent().find("#producto5_E2").html(html);
					
				}
			})
		};
		function getProducto6_E2(ele)
		{
			var dataString = $(ele);
			var familia= dataString.val();
			
			$.ajax
			({
				type: "POST",
				url: "get_producto.php",
				data: {familia: familia},
				success: function(html)
				{
					dataString.parent().parent().find("#producto6_E2").html(html);
					
				}
			})
		};
		function getProducto7_E2(ele)
		{
			var dataString = $(ele);
			var familia= dataString.val();
			
			$.ajax
			({
				type: "POST",
				url: "get_producto.php",
				data: {familia: familia},
				success: function(html)
				{
					dataString.parent().parent().find("#producto7_E2").html(html);
					
				}
			})
		};
		function getProducto8_E2(ele)
		{
			var dataString = $(ele);
			var familia= dataString.val();
			
			$.ajax
			({
				type: "POST",
				url: "get_producto.php",
				data: {familia: familia},
				success: function(html)
				{
					dataString.parent().parent().find("#producto8_E2").html(html);
					
				}
			})
		};
		function getProducto9_E2(ele)
		{
			var dataString = $(ele);
			var familia= dataString.val();
			
			$.ajax
			({
				type: "POST",
				url: "get_producto.php",
				data: {familia: familia},
				success: function(html)
				{
					dataString.parent().parent().find("#producto9_E2").html(html);
					
				}
			})
		};
		function getProducto10_E2(ele)
		{
			var dataString = $(ele);
			var familia= dataString.val();
			
			$.ajax
			({
				type: "POST",
				url: "get_producto.php",
				data: {familia: familia},
				success: function(html)
				{
					dataString.parent().parent().find("#producto10_E2").html(html);
					
				}
			})
		};
		
		function getProducto1_E3(ele)
		{
			var dataString = $(ele);
			var familia= dataString.val();
			
			$.ajax
			({
				type: "POST",
				url: "get_producto.php",
				data: {familia: familia},
				success: function(html)
				{
					dataString.parent().parent().find("#producto1_E3").html(html);
					
				}
			})
		};
		function getProducto2_E3(ele)
		{
			var dataString = $(ele);
			var familia= dataString.val();
			
			$.ajax
			({
				type: "POST",
				url: "get_producto.php",
				data: {familia: familia},
				success: function(html)
				{
					dataString.parent().parent().find("#producto2_E3").html(html);
					
				}
			})
		};
		function getProducto3_E3(ele)
		{
			var dataString = $(ele);
			var familia= dataString.val();
			
			$.ajax
			({
				type: "POST",
				url: "get_producto.php",
				data: {familia: familia},
				success: function(html)
				{
					dataString.parent().parent().find("#producto3_E3").html(html);
					
				}
			})
		};
		function getProducto4_E3(ele)
		{
			var dataString = $(ele);
			var familia= dataString.val();
			
			$.ajax
			({
				type: "POST",
				url: "get_producto.php",
				data: {familia: familia},
				success: function(html)
				{
					dataString.parent().parent().find("#producto4_E3").html(html);
					
				}
			})
		};
		function getProducto5_E3(ele)
		{
			var dataString = $(ele);
			var familia= dataString.val();
			
			$.ajax
			({
				type: "POST",
				url: "get_producto.php",
				data: {familia: familia},
				success: function(html)
				{
					dataString.parent().parent().find("#producto5_E3").html(html);
					
				}
			})
		};
		function getProducto6_E3(ele)
		{
			var dataString = $(ele);
			var familia= dataString.val();
			
			$.ajax
			({
				type: "POST",
				url: "get_producto.php",
				data: {familia: familia},
				success: function(html)
				{
					dataString.parent().parent().find("#producto6_E3").html(html);
					
				}
			})
		};
		function getProducto7_E3(ele)
		{
			var dataString = $(ele);
			var familia= dataString.val();
			
			$.ajax
			({
				type: "POST",
				url: "get_producto.php",
				data: {familia: familia},
				success: function(html)
				{
					dataString.parent().parent().find("#producto7_E3").html(html);
					
				}
			})
		};
		function getProducto8_E3(ele)
		{
			var dataString = $(ele);
			var familia= dataString.val();
			
			$.ajax
			({
				type: "POST",
				url: "get_producto.php",
				data: {familia: familia},
				success: function(html)
				{
					dataString.parent().parent().find("#producto8_E3").html(html);
					
				}
			})
		};
		function getProducto9_E3(ele)
		{
			var dataString = $(ele);
			var familia= dataString.val();
			
			$.ajax
			({
				type: "POST",
				url: "get_producto.php",
				data: {familia: familia},
				success: function(html)
				{
					dataString.parent().parent().find("#producto9_E3").html(html);
					
				}
			})
		};
		function getProducto10_E3(ele)
		{
			var dataString = $(ele);
			var familia= dataString.val();
			
			$.ajax
			({
				type: "POST",
				url: "get_producto.php",
				data: {familia: familia},
				success: function(html)
				{
					dataString.parent().parent().find("#producto10_E3").html(html);
					
				}
			})
		};
	
	</script>
	<!--SCRIPT PARA CALCULAR PORCENTAJES-->
	<script type="text/javascript">
		function getTotalEstacionariedadEjecucion(e)
		{
			var id = $(e).attr('id');
			id = id.replace("_E2", "");
			id = id.replace(/\D/g, "");
			var total = parseInt(commaUnseparateNumber($('#enero_E2_Eje'+id).val())) + parseInt(commaUnseparateNumber($('#febrero_E2_Eje'+id).val())) + parseInt(commaUnseparateNumber($('#marzo_E2_Eje'+id).val())) + parseInt(commaUnseparateNumber($('#abril_E2_Eje'+id).val())) 
			+ parseInt(commaUnseparateNumber($('#mayo_E2_Eje'+id).val())) + parseInt(commaUnseparateNumber($('#junio_E2_Eje'+id).val())) + parseInt(commaUnseparateNumber($('#julio_E2_Eje'+id).val())) 
			+ parseInt(commaUnseparateNumber($('#agosto_E2_Eje'+id).val())) + parseInt(commaUnseparateNumber($('#septiembre_E2_Eje'+id).val())) + parseInt(commaUnseparateNumber($('#octubre_E2_Eje'+id).val())) 
			+ parseInt(commaUnseparateNumber($('#noviembre_E2_Eje'+id).val())) + parseInt(commaUnseparateNumber($('#diciembre_E2_Eje'+id).val()));
			
			$('#total_Eje'+id).html(commaSeparateNumber(total));
		}
		function getTotalEstacionariedadFacturacion(e)
		{
			var id = $(e).attr('id');
			id = id.replace("_E2", "");
			id = id.replace(/\D/g, "");
			var total = parseInt(commaUnseparateNumber($('#anticipo'+id).val())) + parseInt(commaUnseparateNumber($('#enero_E2_Fact'+id).val())) + parseInt(commaUnseparateNumber($('#febrero_E2_Fact'+id).val())) + parseInt(commaUnseparateNumber($('#marzo_E2_Fact'+id).val())) + parseInt(commaUnseparateNumber($('#abril_E2_Fact'+id).val())) 
			+ parseInt(commaUnseparateNumber($('#mayo_E2_Fact'+id).val())) + parseInt(commaUnseparateNumber($('#junio_E2_Fact'+id).val())) + parseInt(commaUnseparateNumber($('#julio_E2_Fact'+id).val())) 
			+ parseInt(commaUnseparateNumber($('#agosto_E2_Fact'+id).val())) + parseInt(commaUnseparateNumber($('#septiembre_E2_Fact'+id).val())) + parseInt(commaUnseparateNumber($('#octubre_E2_Fact'+id).val())) 
			+ parseInt(commaUnseparateNumber($('#noviembre_E2_Fact'+id).val())) + parseInt(commaUnseparateNumber($('#diciembre_E2_Fact'+id).val()));
			
			$('#total_Fact'+id).html(commaSeparateNumber(total));
		}
		function getTotalEstacionariedadCobranza(e)
		{
			var id = $(e).attr('id');
			id = id.replace("_E2", "");
			id = id.replace(/\D/g, "");
			var total = parseInt(commaUnseparateNumber($('#anticipo'+id).val())) + parseInt(commaUnseparateNumber($('#enero_E2_Cobra'+id).val())) + parseInt(commaUnseparateNumber($('#febrero_E2_Cobra'+id).val())) + parseInt(commaUnseparateNumber($('#marzo_E2_Cobra'+id).val())) + parseInt(commaUnseparateNumber($('#abril_E2_Cobra'+id).val())) 
			+ parseInt(commaUnseparateNumber($('#mayo_E2_Cobra'+id).val())) + parseInt(commaUnseparateNumber($('#junio_E2_Cobra'+id).val())) + parseInt(commaUnseparateNumber($('#julio_E2_Cobra'+id).val())) 
			+ parseInt(commaUnseparateNumber($('#agosto_E2_Cobra'+id).val())) + parseInt(commaUnseparateNumber($('#septiembre_E2_Cobra'+id).val())) + parseInt(commaUnseparateNumber($('#octubre_E2_Cobra'+id).val())) 
			+ parseInt(commaUnseparateNumber($('#noviembre_E2_Cobra'+id).val())) + parseInt(commaUnseparateNumber($('#diciembre_E2_Cobra'+id).val()));
			
			$('#total_Cobra'+id).html(commaSeparateNumber(total));
		}
		
		function getCalculosE1(e)
		{
			var id = $('.clonedProyecto').length;
			var ajustado = commaUnseparateNumber($('#ajustado').val());
			var cuota = commaUnseparateNumber($('#cuota').val());
			var cuotadef = commaUnseparateNumber($('#cuotadef'+id).val());
			$('#cuotadef'+id).val(commaSeparateNumber(cuotadef));
			var cuotadeftotal = Math.round(((cuotadef/cuota)*100));
			$('#por_cuotadef'+id).val(cuotadeftotal+'%');
			var cuotatotal = 0;
			for(i = 1; i <= id; i++)
			{
				cuotatotal = cuotatotal + parseInt(commaUnseparateNumber($('#cuotadef'+i).val()));
			}
			$('#total_E1').val(commaSeparateNumber(cuotatotal));
			$('#total_escenario_E1').val(cuotatotal);
			var portotal = 0;
			for(i = 1; i <= id; i++)
			{
				portotal = portotal + parseInt(commaUnseparateNumber($('#por_cuotadef'+i).val()));
			}
			$('#portotal_E1').val(Math.round(portotal)+'%');
			
			var costoTotal = 0;
			for(i = 1; i <= id; i++)
			{
				costoTotal = costoTotal + parseInt($('#costo'+i).val().replace("%",""));
			}
			costoTotal = costoTotal / id;
			$('#costotal_E1').val(costoTotal+'%');
			
			var aux = $('#costo'+id).val();
			var costo = parseInt(aux.replace("%",""));
			var porcosto = costo / 100;
			var margen = Math.round((1-porcosto) * cuotadef);
			$('#margen'+id).val(commaSeparateNumber(margen));
			var margentotal = 0;
			for(i = 1; i <= id; i++)
			{
				margentotal = margentotal + parseInt(commaUnseparateNumber($('#margen'+i).val()));
			}
			$('#margentotal_E1').val(commaSeparateNumber(margentotal));
		}
		
		function getCalculosE2(e)
		{
			var id = $('.clonedProyecto_E2').length;
			var ajustado = commaUnseparateNumber($('#ajustado').val());
			var cuota = commaUnseparateNumber($('#cuota').val());
			var cuotadef = commaUnseparateNumber($('#cuotadef_E2'+id).val());
			$('#cuotadef_E2'+id).val(commaSeparateNumber(cuotadef));
			var cuotadeftotal = Math.round(((cuotadef/cuota)*100));
			$('#por_cuotadef_E2'+id).val(cuotadeftotal+'%');
			var cuotatotal = 0;
			for(i = 1; i <= id; i++)
			{
				cuotatotal = cuotatotal + parseInt(commaUnseparateNumber($('#cuotadef_E2'+i).val()));
			}
			
			$('#total_E2').val(commaSeparateNumber(cuotatotal));
			$('#total_escenario_E2').val(cuotatotal);
			var portotal = 0;
			for(i = 1; i <= id; i++)
			{
				portotal = portotal + parseInt(commaUnseparateNumber($('#por_cuotadef_E2'+i).val()));
			}
			$('#portotal_E2').val(Math.round(portotal)+'%');
			
			var costoTotal = 0;
			for(i = 1; i <= id; i++)
			{
				costoTotal = costoTotal + parseInt($('#costo_E2'+i).val().replace("%",""));
			}
			costoTotal = costoTotal / id;
			$('#costotal_E2').val(costoTotal+'%');
			
			var aux = $('#costo_E2'+id).val();
			var costo = parseInt(aux.replace("%",""));
			var porcosto = costo / 100;
			var margen = Math.round((1-porcosto) * cuotadef);
			$('#margen_E2'+id).val(commaSeparateNumber(margen));
			var margentotal = 0;
			for(i = 1; i <= id; i++)
			{
				margentotal = margentotal + parseInt(commaUnseparateNumber($('#margen_E2'+i).val()));
			}
			$('#margentotal_E2').val(commaSeparateNumber(margentotal));
		}
				
		function getCalculosE3(e)
		{
			var id = $('.clonedProyecto_E3').length;
			var ajustado = commaUnseparateNumber($('#ajustado').val());
			var cuota = commaUnseparateNumber($('#cuota').val());
			var cuotadef = commaUnseparateNumber($('#cuotadef_E3'+id).val());
			$('#cuotadef_E3'+id).val(commaSeparateNumber(cuotadef));
			var cuotadeftotal = Math.round(((cuotadef/cuota)*100));
			$('#por_cuotadef_E3'+id).val(cuotadeftotal+'%');
			var cuotatotal = 0;
			for(i = 1; i <= id; i++)
			{
				cuotatotal = cuotatotal + parseInt(commaUnseparateNumber($('#cuotadef_E3'+i).val()));
			}
			$('#total_E3').val(commaSeparateNumber(cuotatotal));
			$('#total_escenario_E3').val(cuotatotal);
			var portotal = 0;
			for(i = 1; i <= id; i++)
			{
				portotal = portotal + parseInt(commaUnseparateNumber($('#por_cuotadef_E3'+i).val()));
			}
			$('#portotal_E3').val(Math.round(portotal)+'%');
			
			var costoTotal = 0;
			for(i = 1; i <= id; i++)
			{
				var quitaPorcentajes = $('#costo_E3'+i).val();
				quitaPorcentajes = quitaPorcentajes.replace("%","");
				costoTotal = costoTotal + parseInt(quitaPorcentajes);
			}
			costoTotal = costoTotal / id;
			$('#costotal_E3').val(costoTotal+'%');
			
			var aux = $('#costo_E3'+id).val();
			var costo = parseInt(aux.replace("%",""));
			var porcosto = costo / 100;
			var margen = Math.round((1-porcosto) * cuotadef);
			$('#margen_E3'+id).val(commaSeparateNumber(margen));
			var margentotal = 0;
			for(i = 1; i <= id; i++)
			{
				margentotal = margentotal + parseInt(commaUnseparateNumber($('#margen_E3'+i).val()));
			}
			$('#margentotal_E3').val(commaSeparateNumber(margentotal));
		}
	
		function getCommasEstacionariedad(e)
		{
			var value = $(e).val();
			$(e).val(commaSeparateNumber(value));
		}
	</script>
	<!--SCRIPT PARA CLONAR TABLA-->
	<script type="text/javascript">
		//COPIAR ESCENARIO 2
		$('#tab4-link').click(function () {
			var aux = parseInt($('.clonedProyecto_E3_cloned_E2').length);
			if(aux == 0)
			{
				var num = $('.clonedProyecto_E3').length;
				$('#totalRowsE2').val(num);
				$('#totalRowsE3').val(num);
				for(i = 1; i <= num; i++)
				{
					var x = i - 1;
					var newElem = $('#tablaProyectos_E3_' + i).clone().attr('id','tablaProyectos_E2_' + i).removeClass("clonedProyecto_E3").addClass("clonedProyecto_E2 clonedProyecto_E3_cloned_E2");
					newElem.find('#select_tipoambito_E3 input').attr('id', 'tipo_ambito_E2' + i).attr('name', 'tipo_ambito_E2' + i);
					newElem.find('#select_tipoambito_E3').attr('id' , 'select_tipoambito_E2');
					var selectElem4 = $('#familia_E3' + i).val();
					newElem.find('#select_familia_E3 select').attr('id', 'familia_E2' + i).attr('name', 'familia_E2' + i).val(selectElem4).attr('onchange','getProducto'+i+'_E2(this);');
					newElem.find('#select_familia_E3').attr('id', 'select_familia_E2');
					var selectElem3 = $('#producto'+i+'_E3').val();
					newElem.find('#select_producto_E3 select').attr('id', 'producto'+i+'_E2').attr('name', 'producto'+i+'_E2').val(selectElem3);
					newElem.find('#select_producto_E3').attr('id', 'select_producto_E2');
					var selectElem = $('#ambito_E3' + i).val();
					newElem.find('#select_ambito_E3 select').attr('id', 'ambito_E2' + i).attr('name', 'ambito_E2' + i).val(selectElem);
					newElem.find('#select_ambito_E3').attr('id' , 'select_ambito_E2');
					var selectElem2 = $('#estatus_E3' + i).val();
					newElem.find('#select_estatus_E3 select').attr('id', 'estatus_E2' + i).attr('name', 'estatus_E2' + i).val(selectElem2);
					newElem.find('#select_estatus_E3').attr('id' , 'select_estatus_E2');
					newElem.find('#txt_nombre_E3 input').attr('id', 'nombre_E2' + i).attr('name', 'nombre_E2' + i);
					newElem.find('#txt_nombre_E3').attr('id' , 'txt_nombre_E2');
					newElem.find('#txt_cuotadef_E3 input').attr('id', 'cuotadef_E2' + i).attr('name', 'cuotadef_E2' + i).attr('onkeyup','getCalculosE2(this);');
					newElem.find('#txt_cuotadef_E3').attr('id' , 'txt_cuotadef_E2');
					newElem.find('#txt_porcuotadef_E3 input').attr('id', 'por_cuotadef_E2' + i).attr('name', 'por_cuotadef_E2' + i);
					newElem.find('#txt_porcuotadef_E3').attr('id' , 'txt_porcuotadef_E2');
					newElem.find('#txt_costo_E3 input').attr('id', 'costo_E2' + i).attr('onkeyup','getCalculosE2(this);').attr('name', 'costo_E2' + i).inputmask('99%');
					newElem.find('#txt_costo_E3').attr('id' , 'txt_costo_E2');
					newElem.find('#txt_margen_E3 input').attr('id', 'margen_E2' + i).attr('name', 'margen_E2' + i);
					newElem.find('#txt_margen_E3').attr('id' , 'txt_margen_E2');
					newElem.insertAfter('#tablaProyectos_E2_' + x);
				}
				var total = $('#total_E3').val();
				$('#total_E2').val(total);
				var portotal = $('#portotal_E3').val();
				$('#portotal_E2').val(portotal);
				var costo = $('#costotal_E3').val();
				$('#costotal_E2').val(costo);
				var margen = $('#margentotal_E3').val();
				$('#margentotal_E2').val(margen);			
				if(num > 1)
				{
					$('#btnDel-proyecto2').attr('disabled', false);
				}
				else
				{
					$('#btnDel-proyecto2').attr('disabled', 'disabled');
				}
			}
			
			
		});
		
		//COPIAR ESCENARIO 1
		$('#tab3-link').click(function () {
			var aux = parseInt($('.clonedProyecto_E3_cloned_E1').length);
			if(aux == 0)
			{
				var num = $('.clonedProyecto_E3').length;			
				$('#totalRowsE1').val(num);
				$('#totalRowsE3').val(num);
				for(i = 1; i <= num; i++)
				{
					var x = i - 1;
					var newElem = $('#tablaProyectos_E3_' + i).clone().attr('id','tableProyectos' + i).removeClass("clonedProyecto_E3").addClass("clonedProyecto clonedProyecto_E3_cloned_E1");
					newElem.find('#select_tipoambito_E3 input').attr('id', 'tipo_ambito' + i).attr('name', 'tipo_ambito' + i);
					newElem.find('#select_tipoambito_E3').attr('id' , 'select_tipoambito');
					var selectElem4 = $('#familia_E3' + i).val();
					newElem.find('#select_familia_E3 select').attr('id', 'familia' + i).attr('name', 'familia' + i).val(selectElem4).attr('onchange','getProducto'+i+'(this);');
					newElem.find('#select_familia_E3').attr('id', 'select_familia');
					var selectElem3 = $('#producto'+i+'_E3').val();
					newElem.find('#select_producto_E3 select').attr('id', 'producto'+i).attr('name', 'producto'+i).val(selectElem3);
					newElem.find('#select_producto_E3').attr('id', 'select_producto');
					var selectElem = $('#ambito_E3' + i).val();
					newElem.find('#select_ambito_E3 select').attr('id', 'ambito' + i).attr('name', 'ambito' + i).val(selectElem);
					newElem.find('#select_ambito_E3').attr('id' , 'select_ambito');
					var selectElem2 = $('#estatus_E3' + i).val();
					newElem.find('#select_estatus_E3 select').attr('id', 'estatus' + i).attr('name', 'estatus' + i).val(selectElem2);
					newElem.find('#select_estatus_E3').attr('id' , 'select_estatus');
					newElem.find('#txt_nombre_E3 input').attr('id', 'nombre' + i).attr('name', 'nombre' + i);
					newElem.find('#txt_nombre_E3').attr('id' , 'txt_nombre');
					newElem.find('#txt_cuotadef_E3 input').attr('id', 'cuotadef' + i).attr('name', 'cuotadef' + i).attr('onkeyup','getCalculosE1(this);');
					newElem.find('#txt_cuotadef_E3').attr('id' , 'txt_cuotadef');
					newElem.find('#txt_porcuotadef_E3 input').attr('id', 'por_cuotadef' + i).attr('name', 'por_cuotadef' + i);
					newElem.find('#txt_porcuotadef_E3').attr('id' , 'txt_porcuotadef');
					newElem.find('#txt_costo_E3 input').attr('id', 'costo' + i).attr('name', 'costo' + i).attr('onkeyup','getCalculosE1(this);').inputmask('99%');
					newElem.find('#txt_costo_E3').attr('id' , 'txt_costo');
					newElem.find('#txt_margen_E3 input').attr('id', 'margen' + i).attr('name', 'margen' + i);
					newElem.find('#txt_margen_E3').attr('id' , 'txt_margen');
					newElem.insertAfter('#tableProyectos' + x);
				}
				var total = $('#total_E3').val();
				$('#total_E1').val(total);
				var portotal = $('#portotal_E3').val();
				$('#portotal_E1').val(portotal);
				var costo = $('#costotal_E3').val();
				$('#costotal_E1').val(costo);
				var margen = $('#margentotal_E3').val();
				$('#margentotal_E1').val(margen);			
				if(num > 1)
				{
					$('#btnDel-proyecto').attr('disabled', false);
				}
				else
				{
					$('#btnDel-proyecto').attr('disabled', 'disabled');
				}
			}
		});
		
		//COPIAR ESTACIONARIEDAD
		$('#tab6-link').click(function () {
			$('.clonedEstacionariedad').remove();
			var num = $('.clonedProyecto_E2').length;
			for(i = 1; i <= num; i++)
			{
				var x = i - 1;
				var nombreProyecto = $('#nombre_E2' + i).val();
				var totalProyecto = $('#cuotadef_E2' + i).val();
				
				$('#bodyEstacionariedad').append('<tr class="clonedEstacionariedad"><td rowspan="3">'+nombreProyecto+'</td><td colspan="2">Ejecución</td><td></td><td><input onkeyup="getTotalEstacionariedadEjecucion(this); getCommasEstacionariedad(this);" type="text" class="form-control popover-button-default col-md-8" data-content="Enero" title="Top popover" data-trigger="focus" data-placement="top" id="enero_E2_Eje'+ i +'" name="enero_E2_Eje'+ i +'" value="0" required></td><td><input onkeyup="getTotalEstacionariedadEjecucion(this); getCommasEstacionariedad(this);" type="text" class="form-control popover-button-default col-md-12" data-content="Febrero" title="Top popover" data-trigger="focus" data-placement="top" id="febrero_E2_Eje'+ i +'" name="febrero_E2_Eje'+ i +'" value="0" required></td><td colspan="2"><input onkeyup="getTotalEstacionariedadEjecucion(this); getCommasEstacionariedad(this);" type="text" class="form-control popover-button-default" data-content="Marzo" title="Top popover" data-trigger="focus" data-placement="top" id="marzo_E2_Eje'+ i +'" name="marzo_E2_Eje'+ i +'" value="0" required></td><td colspan="2"><input onkeyup="getTotalEstacionariedadEjecucion(this); getCommasEstacionariedad(this);" type="text" class="form-control popover-button-default" data-content="Abril" title="Top popover" data-trigger="focus" data-placement="top" id="abril_E2_Eje'+ i +'" name="abril_E2_Eje'+ i +'" value="0" required></td><td colspan="2"><input onkeyup="getTotalEstacionariedadEjecucion(this); getCommasEstacionariedad(this);" type="text" class="form-control popover-button-default" data-content="Mayo" title="Top popover" data-trigger="focus" data-placement="top" id="mayo_E2_Eje'+ i +'" name="mayo_E2_Eje'+ i +'" value="0" required></td><td colspan="2"><input onkeyup="getTotalEstacionariedadEjecucion(this); getCommasEstacionariedad(this);" type="text" class="form-control popover-button-default" data-content="Junio" title="Top popover" data-trigger="focus" data-placement="top" id="junio_E2_Eje'+ i +'" name="junio_E2_Eje'+ i +'" value="0" required></td><td colspan="2"><input onkeyup="getTotalEstacionariedadEjecucion(this); getCommasEstacionariedad(this);" type="text" class="form-control popover-button-default" data-content="Julio" title="Top popover" data-trigger="focus" data-placement="top" id="julio_E2_Eje'+ i +'" name="julio_E2_Eje'+ i +'" value="0" required></td><td><input onkeyup="getTotalEstacionariedadEjecucion(this); getCommasEstacionariedad(this);" type="text" class="form-control popover-button-default" data-content="Agosto" title="Top popover" data-trigger="focus" data-placement="top" id="agosto_E2_Eje'+ i +'" name="agosto_E2_Eje'+ i +'" value="0" required></td><td><input onkeyup="getTotalEstacionariedadEjecucion(this); getCommasEstacionariedad(this);" type="text" class="form-control popover-button-default" data-content="Septiembre" title="Top popover" data-trigger="focus" data-placement="top" id="septiembre_E2_Eje'+ i +'" name="septiembre_E2_Eje'+ i +'" value="0" required></td><td><input onkeyup="getTotalEstacionariedadEjecucion(this); getCommasEstacionariedad(this);" type="text" class="form-control popover-button-default" data-content="Octubre" title="Top popover" data-trigger="focus" data-placement="top" id="octubre_E2_Eje'+ i +'" name="octubre_E2_Eje'+ i +'" value="0" required></td><td><input onkeyup="getTotalEstacionariedadEjecucion(this); getCommasEstacionariedad(this);" type="text" class="form-control popover-button-default" data-content="Noviembre" title="Top popover" data-trigger="focus" data-placement="top" id="noviembre_E2_Eje'+ i +'" name="noviembre_E2_Eje'+ i +'" value="0" required></td><td><input onkeyup="getTotalEstacionariedadEjecucion(this); getCommasEstacionariedad(this);" type="text" class="form-control popover-button-default" data-content="Diciembre" title="Top popover" data-trigger="focus" data-placement="top" id="diciembre_E2_Eje'+ i +'" name="diciembre_E2_Eje'+ i +'" value="0" required></td><td id="total_Eje'+ i +'" name="total_Eje'+ i +'">0</td><td>'+totalProyecto+'</td></tr>');
				$('#bodyEstacionariedad').append('<tr class="clonedEstacionariedad"><td colspan="2">Facturación</td><td rowspan="2"><input onkeyup="getTotalEstacionariedadCobranza(this); getTotalEstacionariedadFacturacion(this); getTotalEstacionariedadEjecucion(this); getCommasEstacionariedad(this);" type="text" id="anticipo'+ i +'" name="anticipo'+ i +'" value="0" required></td><td><input onkeyup="getTotalEstacionariedadFacturacion(this); getCommasEstacionariedad(this);" type="text" class="form-control popover-button-default" data-content="Enero" title="Top popover" data-trigger="focus" data-placement="top" id="enero_E2_Fact'+ i +'" name="enero_E2_Fact'+ i +'" value="0" required></td><td><input onkeyup="getTotalEstacionariedadFacturacion(this); getCommasEstacionariedad(this);" type="text" class="form-control popover-button-default" data-content="Febrero" title="Top popover" data-trigger="focus" data-placement="top" id="febrero_E2_Fact'+ i +'" name="febrero_E2_Fact'+ i +'" value="0" required></td><td colspan="2"><input onkeyup="getTotalEstacionariedadFacturacion(this); getCommasEstacionariedad(this);" type="text" class="form-control popover-button-default" data-content="Marzo" title="Top popover" data-trigger="focus" data-placement="top" id="marzo_E2_Fact'+ i +'" name="marzo_E2_Fact'+ i +'" value="0" required></td><td colspan="2"><input onkeyup="getTotalEstacionariedadFacturacion(this); getCommasEstacionariedad(this);" type="text" class="form-control popover-button-default" data-content="Abril" title="Top popover" data-trigger="focus" data-placement="top" id="abril_E2_Fact'+ i +'" name="abril_E2_Fact'+ i +'" value="0" required></td><td colspan="2"><input onkeyup="getTotalEstacionariedadFacturacion(this); getCommasEstacionariedad(this);" type="text" class="form-control popover-button-default" data-content="Mayo" title="Top popover" data-trigger="focus" data-placement="top" id="mayo_E2_Fact'+ i +'" name="mayo_E2_Fact'+ i +'" value="0" required></td><td colspan="2"><input onkeyup="getTotalEstacionariedadFacturacion(this); getCommasEstacionariedad(this);" type="text" class="form-control popover-button-default" data-content="Junio" title="Top popover" data-trigger="focus" data-placement="top" id="junio_E2_Fact'+ i +'" name="junio_E2_Fact'+ i +'" value="0" required></td><td colspan="2"><input onkeyup="getTotalEstacionariedadFacturacion(this); getCommasEstacionariedad(this);" type="text" class="form-control popover-button-default" data-content="Julio" title="Top popover" data-trigger="focus" data-placement="top" id="julio_E2_Fact'+ i +'" name="julio_E2_Fact'+ i +'" value="0" required></td><td><input onkeyup="getTotalEstacionariedadFacturacion(this); getCommasEstacionariedad(this);" type="text" class="form-control popover-button-default" data-content="Agosto" title="Top popover" data-trigger="focus" data-placement="top" id="agosto_E2_Fact'+ i +'" name="agosto_E2_Fact'+ i +'" value="0" required></td><td><input onkeyup="getTotalEstacionariedadFacturacion(this); getCommasEstacionariedad(this);" type="text" class="form-control popover-button-default" data-content="Septiembre" title="Top popover" data-trigger="focus" data-placement="top" id="septiembre_E2_Fact'+ i +'" name="septiembre_E2_Fact'+ i +'" value="0" required></td><td><input onkeyup="getTotalEstacionariedadFacturacion(this); getCommasEstacionariedad(this);" type="text" class="form-control popover-button-default" data-content="Octubre" title="Top popover" data-trigger="focus" data-placement="top" id="octubre_E2_Fact'+ i +'" name="octubre_E2_Fact'+ i +'" value="0" required></td><td><input onkeyup="getTotalEstacionariedadFacturacion(this); getCommasEstacionariedad(this);" type="text" class="form-control popover-button-default" data-content="Noviembre" title="Top popover" data-trigger="focus" data-placement="top" id="noviembre_E2_Fact'+ i +'" name="noviembre_E2_Fact'+ i +'" value="0" required></td><td><input onkeyup="getTotalEstacionariedadFacturacion(this); getCommasEstacionariedad(this);" type="text" class="form-control popover-button-default" data-content="Diciembre" title="Top popover" data-trigger="focus" data-placement="top" id="diciembre_E2_Fact'+ i +'" name="diciembre_E2_Fact'+ i +'" value="0" required></td><td id="total_Fact'+ i +'" name="total_Fact'+ i +'">0</td><td>'+totalProyecto+'</td></tr>');
				$('#bodyEstacionariedad').append('<tr class="clonedEstacionariedad"><td colspan="2">Cobranza</td><td><input onkeyup="getTotalEstacionariedadCobranza(this); getCommasEstacionariedad(this);" type="text" class="form-control popover-button-default" data-content="Enero" title="Top popover" data-trigger="focus" data-placement="top" id="enero_E2_Cobra'+ i +'" name="enero_E2_Cobra'+ i +'" value="0" required></td><td><input onkeyup="getTotalEstacionariedadCobranza(this); getCommasEstacionariedad(this);" type="text" class="form-control popover-button-default" data-content="Febrero" title="Top popover" data-trigger="focus" data-placement="top" id="febrero_E2_Cobra'+ i +'" name="febrero_E2_Cobra'+ i +'" value="0" required></td><td colspan="2"><input onkeyup="getTotalEstacionariedadCobranza(this); getCommasEstacionariedad(this);" type="text" class="form-control popover-button-default" data-content="Marzo" title="Top popover" data-trigger="focus" data-placement="top" id="marzo_E2_Cobra'+ i +'" name="marzo_E2_Cobra'+ i +'" value="0" required></td><td colspan="2"><input onkeyup="getTotalEstacionariedadCobranza(this); getCommasEstacionariedad(this);" type="text" class="form-control popover-button-default" data-content="Abril" title="Top popover" data-trigger="focus" data-placement="top" id="abril_E2_Cobra'+ i +'" name="abril_E2_Cobra'+ i +'" value="0" required></td><td colspan="2"><input onkeyup="getTotalEstacionariedadCobranza(this); getCommasEstacionariedad(this);" type="text" class="form-control popover-button-default" data-content="Mayo" title="Top popover" data-trigger="focus" data-placement="top" id="mayo_E2_Cobra'+ i +'" name="mayo_E2_Cobra'+ i +'" value="0" required></td><td colspan="2"><input onkeyup="getTotalEstacionariedadCobranza(this); getCommasEstacionariedad(this);" type="text" class="form-control popover-button-default" data-content="Junio" title="Top popover" data-trigger="focus" data-placement="top" id="junio_E2_Cobra'+ i +'" name="junio_E2_Cobra'+ i +'" value="0" required></td><td colspan="2"><input onkeyup="getTotalEstacionariedadCobranza(this); getCommasEstacionariedad(this);" type="text" class="form-control popover-button-default" data-content="Julio" title="Top popover" data-trigger="focus" data-placement="top" id="julio_E2_Cobra'+ i +'" name="julio_E2_Cobra'+ i +'" value="0" required></td><td><input onkeyup="getTotalEstacionariedadCobranza(this); getCommasEstacionariedad(this);" type="text" class="form-control popover-button-default" data-content="Agosto" title="Top popover" data-trigger="focus" data-placement="top" id="agosto_E2_Cobra'+ i +'" name="agosto_E2_Cobra'+ i +'" value="0" required></td><td><input onkeyup="getTotalEstacionariedadCobranza(this); getCommasEstacionariedad(this);" type="text" class="form-control popover-button-default" data-content="Septiembre" title="Top popover" data-trigger="focus" data-placement="top" id="septiembre_E2_Cobra'+ i +'" name="septiembre_E2_Cobra'+ i +'" value="0" required></td><td><input onkeyup="getTotalEstacionariedadCobranza(this); getCommasEstacionariedad(this);" type="text" class="form-control popover-button-default" data-content="Octubre" title="Top popover" data-trigger="focus" data-placement="top" id="octubre_E2_Cobra'+ i +'" name="octubre_E2_Cobra'+ i +'" value="0" required></td><td><input onkeyup="getTotalEstacionariedadCobranza(this); getCommasEstacionariedad(this);" type="text" class="form-control popover-button-default" data-content="Noviembre" title="Top popover" data-trigger="focus" data-placement="top" id="noviembre_E2_Cobra'+ i +'" name="noviembre_E2_Cobra'+ i +'" value="0" required></td><td><input onkeyup="getTotalEstacionariedadCobranza(this); getCommasEstacionariedad(this);" type="text" class="form-control popover-button-default" data-content="Diciembre" title="Top popover" data-trigger="focus" data-placement="top" id="diciembre_E2_Cobra'+ i +'" name="diciembre_E2_Cobra'+ i +'" value="0" required></td><td id="total_Cobra'+ i +'" name="total_Cobra'+ i +'">0</td><td>'+totalProyecto+'</td></tr>');
			}
		});
		
	</script>
	<!--SCRIPT PARA CLONAR RENGLONES-->
	<script type="text/javascript">
		$(window).load(function () 
		{
			//CLON ESCENARIO 1
			var numAux = $('.clonedProyecto').length;
			if(numAux > 1)
			{
				$('#btnDel-proyecto').attr('disabled', false);
			}
			else
			{
				$('#btnDel-proyecto').attr('disabled', 'disabled');
			}
			
			$('#add-proyecto').click(function () 
			{
				var num = $('.clonedProyecto').length;
				var newNum = num + 1;
				$('#totalRowsE1').val(newNum);
				var newElem = $('#tableProyectos1').clone().attr('id', 'tableProyectos' + newNum);
				newElem.find('#tablaProyectos_E1_TR a').attr('id','tablaProyectos_E1_' + newNum +'_TR').attr('name','tablaProyectos_E1_' + newNum +'_TR');
				newElem.find('#select_tipoambito select').attr('id', 'tipo_ambito' + newNum).attr('name', 'tipo_ambito' + newNum).val('');
				newElem.find('#select_ambito select').attr('id', 'ambito' + newNum).attr('name', 'ambito' + newNum).prop('required', true);
				newElem.find('#select_familia select').attr('id', 'familia' + newNum).attr('name', 'familia' + newNum).attr('onchange','getProducto'+ newNum +'(this);');
				newElem.find('#select_producto select').attr('id', 'producto' + newNum).attr('name', 'producto' + newNum).prop('required', true);
				newElem.find('#select_estatus select').attr('id', 'estatus' + newNum).attr('name', 'estatus' + newNum).prop('required', true);
				newElem.find('#txt_nombre input').attr('id', 'nombre' + newNum).attr('name', 'nombre' + newNum).val('');
				newElem.find('#txt_cuotadef input').attr('id', 'cuotadef' + newNum).attr('name', 'cuotadef' + newNum).attr('onkeyup','getCalculosE1(this);').prop('required', true).val('');
				newElem.find('#txt_porcuotadef input').attr('id', 'por_cuotadef' + newNum).attr('name', 'por_cuotadef' + newNum).val('');
				newElem.find('#txt_costo input').attr('id', 'costo' + newNum).attr('name', 'costo' + newNum).attr('onkeyup','getCalculosE1(this);').inputmask('99%').prop('required', true).val('');
				newElem.find('#txt_margen input').attr('id', 'margen' + newNum).attr('name', 'margen' + newNum).val('');
				

				$('#tableProyectos' + num).after(newElem);
				$('#btnDel-proyecto').attr('disabled', false);
				if (newNum == 10)
				{
					$('#add-proyecto').attr('disabled', 'disabled');
				}
			});			
			$('#btnDel-proyecto').click(function () 
			{
				var num = $('.clonedProyecto').length;
				$('#tableProyectos' + num).remove();
				$('#add-proyecto').attr('disabled', false);
					
				if (num - 1 == 1)
				{	
					$('#btnDel-proyecto').attr('disabled', 'disabled');
				}
				var cuotatotal = 0;
				for(i = 1; i <= num-1; i++)
				{
					cuotatotal = cuotatotal + parseInt(commaUnseparateNumber($('#cuotadef'+i).val()));
				}
				$('#total_E1').val(commaSeparateNumber(cuotatotal));
				$('#total_escenario_E1').val(cuotatotal);
				var portotal = 0;
				for(i = 1; i <= num-1; i++)
				{
					portotal = portotal + parseInt(commaUnseparateNumber($('#por_cuotadef'+i).val()));
				}
				$('#portotal_E1').val(portotal+'%');
				var margentotal = 0;
				for(i = 1; i <= num-1; i++)
				{
					margentotal = margentotal + parseInt(commaUnseparateNumber($('#margen'+i).val()));
				}
				$('#margentotal_E1').val(commaSeparateNumber(margentotal));
			});
		
		
			//CLON ESCENARIO 2
			$('#btnDel-proyecto2').attr('disabled', 'disabled');
			$('#add-proyecto2').click(function () 
			{
				var num = $('.clonedProyecto_E2').length;
				var newNum = num + 1;
				$('#totalRowsE2').val(newNum);
				var newElem = $('#tablaProyectos_E2_1').clone().attr('id', 'tablaProyectos_E2_' + newNum);
				newElem.find('#tablaProyectos_E2_TR a').attr('id','tablaProyectos_E2_' + newNum +'_TR').attr('name','tablaProyectos_E2_' + newNum +'_TR');
				newElem.find('#select_tipoambito_E2 input').attr('id', 'tipo_ambito_E2' + newNum).attr('name', 'tipo_ambito_E2' + newNum).val('');
				newElem.find('#select_ambito_E2 select').attr('id', 'ambito_E2' + newNum).attr('name', 'ambito_E2' + newNum).prop('required', true);
				newElem.find('#select_familia_E2 select').attr('id', 'familia_E2' + newNum).attr('name', 'familia_E2' + newNum).attr('onchange','getProducto'+ newNum +'_E2(this);');
				newElem.find('#select_producto_E2 select').attr('id', 'producto'+newNum+'_E2').attr('name', 'producto'+newNum+'_E2').prop('required', true);
				newElem.find('#select_estatus_E2 select').attr('id', 'estatus_E2' + newNum).attr('name', 'estatus_E2' + newNum).prop('required', true);
				newElem.find('#txt_nombre_E2 input').attr('id', 'nombre_E2' + newNum).attr('name', 'nombre_E2' + newNum).val('');
				newElem.find('#txt_cuotadef_E2 input').attr('id', 'cuotadef_E2' + newNum).attr('name', 'cuotadef_E2' + newNum).attr('onkeyup','getCalculosE2(this);').prop('required', true).val('');
				newElem.find('#txt_porcuotadef_E2 input').attr('id', 'por_cuotadef_E2' + newNum).attr('name', 'por_cuotadef_E2' + newNum).val('');
				newElem.find('#txt_costo_E2 input').attr('id', 'costo_E2' + newNum).attr('name', 'costo_E2' + newNum).attr('onkeyup','getCalculosE2(this);').prop('required', true).inputmask('99%').val('');
				newElem.find('#txt_margen_E2 input').attr('id', 'margen_E2' + newNum).attr('name', 'margen_E2' + newNum).val('');

				$('#tablaProyectos_E2_' + num).after(newElem);
				$('#btnDel-proyecto2').attr('disabled', false);
				if (newNum == 10)
				{
					$('#add-proyecto2').attr('disabled', 'disabled');
				}
			});			
			$('#btnDel-proyecto2').click(function () 
			{
				var num = $('.clonedProyecto_E2').length;
				$('#tablaProyectos_E2_' + num).remove();
				$('#add-proyecto2').attr('disabled', false);
					
				if (num - 1 == 1)
				{
					$('#btnDel-proyecto2').attr('disabled', 'disabled');
				}
				var cuotatotal = 0;
				for(i = 1; i <= num-1; i++)
				{
					cuotatotal = cuotatotal + parseInt(commaUnseparateNumber($('#cuotadef_E2'+i).val()));
				}
				$('#total_E2').val(commaSeparateNumber(cuotatotal));
				$('#total_escenario_E2').val(cuotatotal);
				var portotal = 0;
				for(i = 1; i <= num-1; i++)
				{
					portotal = portotal + parseInt(commaUnseparateNumber($('#por_cuotadef_E2'+i).val()));
				}
				$('#portotal_E2').val(portotal+'%');
				var margentotal = 0;
				for(i = 1; i <= num-1; i++)
				{
					margentotal = margentotal + parseInt(commaUnseparateNumber($('#margen_E2'+i).val()));
				}
				$('#margentotal_E2').val(commaSeparateNumber(margentotal));
			});
			
			//CLON ESCENARIO 3
			$('#btnDel-proyecto3').attr('disabled', 'disabled');
			$('#add-proyecto3').click(function () 
			{
				var num = $('.clonedProyecto_E3').length;
				var newNum = num + 1;
				$('#totalRowsE3').val(newNum);
				var newElem = $('#tablaProyectos_E3_1').clone().attr('id', 'tablaProyectos_E3_' + newNum);
				newElem.find('#tablaProyectos_E3_TR a').attr('id','tablaProyectos_E3_' + newNum +'_TR').attr('name','tablaProyectos_E3_' + newNum +'_TR');
				newElem.find('#select_tipoambito_E3 input').attr('id', 'tipo_ambito_E3' + newNum).attr('name', 'tipo_ambito_E3' + newNum).val('');
				newElem.find('#select_ambito_E3 select').attr('id', 'ambito_E3' + newNum).attr('name', 'ambito_E3' + newNum).prop('required', true);
				newElem.find('#select_familia_E3 select').attr('id', 'familia_E3' + newNum).attr('name', 'familia_E3' + newNum).attr('onchange','getProducto'+ newNum +'_E3(this);');
				newElem.find('#select_producto_E3 select').attr('id', 'producto'+newNum+'_E3').attr('name', 'producto'+newNum+'_E3').prop('required', true);
				newElem.find('#select_estatus_E3 select').attr('id', 'estatus_E3' + newNum).attr('name', 'estatus_E3' + newNum).prop('required', true);
				newElem.find('#txt_nombre_E3 input').attr('id', 'nombre_E3' + newNum).attr('name', 'nombre_E3' + newNum).val('');
				newElem.find('#txt_cuotadef_E3 input').attr('id', 'cuotadef_E3' + newNum).attr('name', 'cuotadef_E3' + newNum).attr('onkeyup','getCalculosE3(this);').prop('required', true).val('');
				newElem.find('#txt_porcuotadef_E3 input').attr('id', 'por_cuotadef_E3' + newNum).attr('name', 'por_cuotadef_E3' + newNum).val('');
				newElem.find('#txt_costo_E3 input').attr('id', 'costo_E3' + newNum).attr('name', 'costo_E3' + newNum).attr('onkeyup','getCalculosE3(this);').inputmask('99%').prop('required', true).val('');
				newElem.find('#txt_margen_E3 input').attr('id', 'margen_E3' + newNum).attr('name', 'margen_E3' + newNum).val('');

				$('#tablaProyectos_E3_' + num).after(newElem);
				$('#btnDel-proyecto3').attr('disabled', false);
				if (newNum == 10)
				{
					$('#add-proyecto3').attr('disabled', 'disabled');
				}
			});			
			$('#btnDel-proyecto3').click(function () 
			{
				var num = $('.clonedProyecto_E3').length;
				$('#tablaProyectos_E3_' + num).remove();
				$('#add-proyecto3').attr('disabled', false);
					
				if (num - 1 == 1)
				{	
					$('#btnDel-proyecto3').attr('disabled', 'disabled');
				}
				var cuotatotal = 0;
				for(i = 1; i <= num-1; i++)
				{
					cuotatotal = cuotatotal + parseInt(commaUnseparateNumber($('#cuotadef_E3'+i).val()));
				}
				$('#total_E3').val(commaSeparateNumber(cuotatotal));
				$('#total_escenario_E3').val(cuotatotal);
				var portotal = 0;
				for(i = 1; i <= num-1; i++)
				{
					portotal = portotal + parseInt(commaUnseparateNumber($('#por_cuotadef_E3'+i).val()));
				}
				$('#portotal_E3').val(portotal+'%');
				var margentotal = 0;
				for(i = 1; i <= num-1; i++)
				{
					margentotal = margentotal + parseInt(commaUnseparateNumber($('#margen_E3'+i).val()));
				}
				$('#margentotal_E3').val(commaSeparateNumber(margentotal));
			});
		});
	</script>
	<!--SCRIPT PARA OBTENER LOS PROYECTOS DEL AÑO PASADO-->
	<script>
		function getProyectos(elem)
		{
			var dataString = $(elem).val();
			var dataString2 = new Date().getFullYear();
			$.ajax
			({
				type: "POST",
				url: "gethistorico.php",
				data: {cliente_proyecto: dataString, year_proyecto: dataString2},
				dataType: 'json'
				
			})
			.done(function(data){
				console.log(data);
				if(!$.trim(data)){
					$('#monto').html(data.nombre_subproducto);
				}
				else
				{
					alert("goodnombre");
				}
				
			})
			.fail(function(jqXHR, textStatus, errorThrown){
				 if (jqXHR.status === 0) {

					alert('Not connect: Verify Network.');

				  } else if (jqXHR.status == 404) {

					alert('Requested page not found [404]');

				  } else if (jqXHR.status == 500) {

					alert('Internal Server Error [500].');

				  } else if (textStatus === 'parsererror') {

					alert('Requested JSON parse failed.');

				  } else if (textStatus === 'timeout') {

					alert('Time out error.');

				  } else if (textStatus === 'abort') {

					alert('Ajax request aborted.');

				  } else {

					alert('Uncaught Error: ' + jqXHR.responseText);

				  }
			})
		}
	</script>	
</form>
<?php include_once"footer.php";?>
