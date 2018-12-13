<?php
include_once 'dbconfig.php';
class DDLProyectos{
	private $conn;
	public function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
	}
	public function runQuery($sql)
	{
		$stmt = $this->conn->prepare($sql);
		return $stmt;
	}
	
	//SELECTS//
	public function selectCuestionario($cuestionario)
	{
		$stmt = $this->conn->prepare("SELECT * FROM `T_Cuestionario` 
		WHERE id_cuestionario = :cuestionario");
		$stmt->bindparam(":cuestionario",$cuestionario);
		$stmt->execute();
		$row=$stmt->fetch(PDO::FETCH_ASSOC);
		return $row;
	}
	
	public function getClaveProyecto($cuenta)
	{
		$stmt = $this->conn->prepare("SELECT id_proyecto FROM `T_Proyectos` 
		WHERE id_proyecto = (SELECT MAX(id_proyecto) FROM `T_Proyectos`)");
		$stmt->execute();
		
		$stmt2 = $this->conn->prepare("SELECT clave_prospecto FROM T_Prospectos
		WHERE id_prospecto = :cuenta");
		$stmt2->bindparam(":cuenta",$cuenta);
		$stmt2->execute();
		
		if($stmt->rowCount()>0)
		{
			$row=$stmt->fetch(PDO::FETCH_ASSOC);
			if($stmt2->rowCount()>0)
			{
				$row2=$stmt2->fetch(PDO::FETCH_ASSOC);
				?>
				<input type="hidden" class="form-control" id="clave_aux" name="clave_aux" placeholder="" value="<?php echo($row2['clave_prospecto']); ?>" readonly>
				<input id="clave" name="clave" type="text" class="form-control" value="<?php echo ($row2['clave_prospecto']); ?>" readonly>
				<?php
			}
			else
			{
				?>
				<input type="hidden" class="form-control" id="clave_aux" name="clave_aux" placeholder="" value="<?php echo intval($row['id_proyecto'])+1; ?>" readonly>
				<input id="clave" name="clave" type="text" class="form-control" value="<?php echo intval($row['id_proyecto'])+1; ?>" readonly>
				<?php
			}
		}
		else
		{
			?>
			<input type="hidden" class="form-control" id="clave_aux" name="clave_aux" placeholder="" value="1" readonly>
			<input id="clave" name="clave" type="text" class="form-control" value="" readonly>
			<?php
		}
	}
	public function getClaveProyectoCliente($cuenta)
	{
		$stmt = $this->conn->prepare("SELECT id_cliente, id_proyecto FROM `T_Proyectos` 
		WHERE id_proyecto = (SELECT MAX(id_proyecto) FROM `T_Proyectos`)");
		$stmt->execute();
		
		$stmt2 = $this->conn->prepare("SELECT clave_cliente FROM T_Clientes
		WHERE id_cliente = :cuenta");
		$stmt2->bindparam(":cuenta",$cuenta);
		$stmt2->execute();
		
		if($stmt->rowCount()>0)
		{
			$row=$stmt->fetch(PDO::FETCH_ASSOC);
			if($stmt2->rowCount()>0)
			{
				$row2=$stmt2->fetch(PDO::FETCH_ASSOC);
				?>
				<input type="hidden" class="form-control" id="clave_aux" name="clave_aux" placeholder="" value="<?php echo($row2['clave_cliente']); ?>" readonly>
				<input id="clave" name="clave" type="text" class="form-control" value="<?php echo ($row2['clave_cliente']); ?>" readonly>
				<?php
			}
			else
			{
				?>
				<input type="hidden" class="form-control" id="clave_aux" name="clave_aux" placeholder="" value="<?php echo intval($row['id_proyecto'])+1; ?>" readonly>
				<input id="clave" name="clave" type="text" class="form-control" value="<?php echo intval($row['id_proyecto'])+1; ?>" readonly>
				<?php
			}
		}
		else
		{
			?>
			<input type="hidden" class="form-control" id="clave_aux" name="clave_aux" placeholder="" value="1" readonly>
			<input id="clave" name="clave" type="text" class="form-control" value="" readonly>
			<?php
		}
	}
	public function selectTipoAmbito()
	{
		$stmt = $this->conn->prepare("SELECT * FROM T_TipoAmbitos");
		$stmt->execute();
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))
		{
			?>
			<option value="<?php echo $row['id_tipoambito']; ?>"><?php echo $row['tipoambito']; ?></option>
			<?php
		}
	}
	public function selectAmbito()
	{
		$stmt = $this->conn->prepare("SELECT * FROM T_Ambitos");
		$stmt->execute();
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))
		{
			?>
			<option value="<?php echo $row['id_ambito']; ?>"><?php echo $row['ambito']; ?></option>
			<?php
		}
	}
	public function selectAmbitoUpdate($ambito)
	{
		$stmt = $this->conn->prepare("SELECT * FROM T_Ambitos WHERE id_ambito = :ambito");
		$stmt->bindparam(":ambito",$ambito);
		$stmt->execute();
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))
		{
			?>
			<option value="<?php echo $row['id_ambito']; ?>"><?php echo $row['ambito']; ?></option>
			<?php
		}
	}
	public function selectClientes($zona)
	{
		$stmt = $this->conn->prepare("SELECT * FROM T_Clientes WHERE id_zona = :zona");
		$stmt->bindparam(":zona",$zona);
		$stmt->execute();
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))
		{
			?>
			<option value="<?php echo $row['id_cliente']; ?>"><?php echo $row['nombre_cliente']; ?></option>
			<?php
		}
	}
	public function selectAlcance()
	{
		$stmt = $this->conn->prepare("SELECT * FROM T_Alcances");
		$stmt->execute();
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))
		{
			?>
			<option value="<?php echo $row['id_alcance']; ?>"><?php echo $row['alcance']; ?></option>
			<?php
		}
	}
	public function selectFamiliaProyectosAntiguos($id)
	{
		$stmt = $this->conn->prepare("SELECT * FROM T_FamiliaProductos WHERE id_familiaproducto = :id");
		$stmt->bindparam(":id",$id);
		$stmt->execute();
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))
		{
			?>
			<option value="<?php echo $row['id_familiaproducto']; ?>"><?php echo $row['familia_producto']; ?></option>
			<?php
		}
	}
	public function selectFamilia()
	{
		$stmt = $this->conn->prepare("SELECT * FROM T_FamiliaProductos");
		$stmt->execute();
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))
		{
			?>
			<option value="<?php echo $row['id_familiaproducto']; ?>"><?php echo $row['familia_producto']; ?></option>
			<?php
		}
	}
	public function selectFamiliaUpdate($familia)
	{
		$stmt = $this->conn->prepare("SELECT * FROM T_FamiliaProductos WHERE id_familiaproducto = :familia");
		$stmt->bindparam(":familia",$familia);
		$stmt->execute();
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))
		{
			?>
			<option value="<?php echo $row['id_familiaproducto']; ?>"><?php echo $row['familia_producto']; ?></option>
			<?php
		}
	}
	public function selectProductos($familia)
	{
		$stmt = $this->conn->prepare("SELECT * FROM T_Productos WHERE id_familiaproducto = :familia");
		$stmt->bindparam(":familia",$familia);
		$stmt->execute();
		?>
		<option value="" selected>Selecciona un programa</option>
		<?php
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))
		{
			?>
			<option value="<?php echo $row['id_producto']; ?>"><?php echo $row['nombre_producto']; ?></option>
			<?php
		}
	}
	public function selectProductosUpdate($familia)
	{
		$stmt = $this->conn->prepare("SELECT * FROM T_Productos WHERE id_producto = :familia");
		$stmt->bindparam(":familia",$familia);
		$stmt->execute();
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))
		{
			?>
			<option selected value="<?php echo $row['id_producto']; ?>"><?php echo $row['nombre_producto']; ?></option>
			<?php
		}
	}
	public function selectNombreProducto($producto)
	{
		$stmt = $this->conn->prepare("SELECT nombre_producto FROM T_Productos WHERE id_producto = :producto");
		$stmt->bindparam(":producto",$producto);
		$stmt->execute();
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))
		{
			echo $row['nombre_producto']; 
		}
	}
	public function selectSubproductos($producto)
	{
		$stmt = $this->conn->prepare("SELECT * FROM T_Subproductos WHERE id_producto = :producto");
		$stmt->bindparam(":producto",$producto);
		$stmt->execute();
		?>
		<option value="" selected>Selecciona un producto</option>
		<?php
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))
		{
		?>
			<option value="<?php echo $row['id_subproducto']; ?>"><?php echo $row['nombre_subproducto']; ?></option>
		<?php
		}
	}
	public function getSubPartes($subproducto)
	{
		$stmt = $this->conn->prepare("SELECT 
				SP.id_subparte,
				SPDC.subparte,
				SPDC.costo_hora,
				PDC.parte,
				SP.id_subproducto_subparte,
				SP.horas,
				(SP.horas*SPDC.costo_hora) AS totalcosto
			FROM T_Subproductos_Subpartes AS SP
			INNER JOIN T_Subpartes_DC AS SPDC
			ON SPDC.id_subparte = SP.id_subparte
			INNER JOIN T_Partes_DC AS PDC
			ON PDC.id_parte = SPDC.id_parte
			WHERE SP.id_subproducto = :subproducto");
		$stmt->bindparam(":subproducto",$subproducto);
		$stmt->execute();
		//
		$array = array();
		$x = 0;
		if($stmt->rowCount()>0)
		{
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				$array[$x]['horas'] = utf8_encode($row['horas']);
				$array[$x]['id_subproducto_subparte'] = utf8_encode($row['id_subproducto_subparte']);
				$array[$x]['id_subparte'] = utf8_encode($row['id_subparte']);
				$array[$x]['subparte'] = utf8_encode($row['subparte']);
				$array[$x]['costo_hora'] = utf8_encode(number_format($row['costo_hora'],2));
				$array[$x]['totalcosto'] = utf8_encode(number_format($row['totalcosto'],2));
				$array[$x]['parte'] = utf8_encode($row['parte']);
				
				$x++;
			}
			print_r(json_encode($array));
		}
		else
		{
			header('Content-Type: application/json');
			print(json_encode("Error"));
		}
		//echo json_encode("hola");
	}
	public function selectSubproductosPrecio($precio)
	{
		$stmt = $this->conn->prepare("SELECT * FROM T_Subproductos WHERE id_subproducto = :precio");
		$stmt->bindparam(":precio",$precio);
		$stmt->execute();
		$row=$stmt->fetch(PDO::FETCH_ASSOC);
		$resultado = number_format($row['precio_subproducto'],2);
		echo $resultado;
	}
	public function selectSubproductosCosto($costo)
	{
		$stmt = $this->conn->prepare("SELECT costo_porcentaje FROM T_Subproductos WHERE id_subproducto = :costo");
		$stmt->bindparam(":costo",$costo);
		$stmt->execute();
		$row=$stmt->fetch(PDO::FETCH_ASSOC);
		$resultado = round($row['costo_porcentaje']*100,2).'%';
		echo $resultado;
	}
	public function selectSubproductosCostoImporte($costo)
	{
		$stmt = $this->conn->prepare("SELECT costo_subproducto FROM T_Subproductos WHERE id_subproducto = :costo");
		$stmt->bindparam(":costo",$costo);
		$stmt->execute();
		$row=$stmt->fetch(PDO::FETCH_ASSOC);
		$resultado = number_format($row['costo_subproducto'],2);
		echo $resultado;
	}
	public function selectEstatusProyectosAntiguos($estatus)
	{
		$stmt = $this->conn->prepare("SELECT * FROM T_EstatusProyecto WHERE id_estatusproyecto = :estatus");
		$stmt->bindparam(":estatus",$estatus);
		$stmt->execute();
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))
		{
		?>
			<option value="<?php echo $row['id_estatusproyecto']; ?>"><?php echo $row['estatus']; ?></option>
		<?php
		}
	}
	public function selectEstatus()
	{
		$stmt = $this->conn->prepare("SELECT * FROM T_EstatusProyecto WHERE NOT (id_estatusproyecto = 3 OR id_estatusproyecto = 6)");
		$stmt->execute();
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))
		{
			?>
			<option value="<?php echo $row['id_estatusproyecto']; ?>"><?php echo $row['estatus']; ?></option>
			<?php
		}
	}
	public function selectEstatusUpdate($estatus)
	{
		$stmt = $this->conn->prepare("SELECT * FROM T_EstatusProyecto WHERE id_estatusproyecto = :estatus");
		$stmt->bindparam(":estatus",$estatus);
		$stmt->execute();
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))
		{
			?>
			<option value="<?php echo $row['id_estatusproyecto']; ?>"><?php echo $row['estatus']; ?></option>
			<?php
		}
	}
	public function viewProyectosCambios($cuenta)
	{
		$stmt = $this->conn->prepare("SELECT * FROM T_Proyectos AS P 
			INNER JOIN T_Detalle_Proyectos AS DP
			ON P.id_proyecto = DP.id_proyecto
			WHERE P.id_prospecto = :cuenta");
		$stmt->bindparam(":cuenta",$cuenta);	
		$stmt->execute();
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))
		{
			?>
			<option value="<?php echo $row['id_proyecto']; ?>"><?php echo $row['nombre_proyecto']; ?></option>
			<?php
		}
	}
	public function viewProyectosCambios1($cuenta)
	{
		$stmt = $this->conn->prepare("SELECT * FROM T_Proyectos AS P
			WHERE P.id_prospecto = :cuenta");
		$stmt->bindparam(":cuenta",$cuenta);	
		$stmt->execute();
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))
		{
			?>
			<option value="<?php echo $row['id_proyecto']; ?>"><?php echo $row['nombre_proyecto']; ?></option>
			<?php
		}
	}
	public function selectProyectos($cuenta, $year_consulta)
	{
		$stmt = $this->conn->prepare("SELECT *
			  FROM T_Proyectos AS P
			  WHERE P.id_prospecto=:cuenta AND P.year_proyecto=:year_consulta AND id_estatusproyecto<>5");
		$stmt->execute(array(':cuenta'=>$cuenta,':year_consulta'=>$year_consulta));
		$contador = 0;
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))
		{
			if(($contador%2)===0)
			{
				?>
				<div class="form-group">
				</div>
				<?php
			}
			?>
				<label class="col-sm-2 control-label">Proyecto: <?php print($row['nombre_proyecto']);?></label>
				<div class="col-sm-4">
					<div class="input-prepend input-group">
						<input id="proyecto" name="proyecto" type="radio" class="form-control" value="<?php print($row['id_proyecto']);?>">
					</div>
				</div>
			<?php
			$contador++;
		}
	}
	public function viewFundamentacionC($fundamentacion)
	{
		$query = "SELECT F.id_fundamentacion,
					F.necesidad_comentarios,
					F.expectativa,
					P.poblacion,
					N.necesidad,
					A.alcance,
					C.nombre_cliente
				FROM T_FundamentacionCliente AS F
				INNER JOIN T_Poblacion AS P
				ON F.id_poblacion = P.id_poblacion
				INNER JOIN T_Necesidades AS N
				ON N.id_necesidad = F.id_necesidad
				INNER JOIN T_Alcances AS A
				ON F.id_alcance = A.id_alcance
				INNER JOIN T_Clientes AS C
				ON C.id_cliente = F.id_cliente
				WHERE id_fundamentacion = :fundamentacion";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(":fundamentacion",$fundamentacion);
		$stmt->execute();
		
		if($stmt->rowCount()>0)
		{
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
			?>
			<tr>
				<td><?php print($row['nombre_cliente']);?></td>
				<td><?php print($row['poblacion']);?></td>
				<td><?php print($row['necesidad']);?></td>
				<td><?php print($row['alcance']);?></td>
				<td><?php print($row['necesidad_comentarios']);?></td>
				<td><?php print($row['expectativa']);?></td>
			</tr>
			<?php
			}
		}
	}
	
	//VER PROYECTOS ANTERIORES
	public function proyectosAnterioresSits($cliente)
	{
		$query="SELECT P.clave_proyecto,
		P.nombre_proyecto,
		Prod.id_familiaproducto,
		P.id_estatusproyecto,
		(SUM(Subprod.precio_subproducto)*DP.cantidad_proyecto) AS cuota,
		(SUM(Subprod.costo_subproducto)*DP.cantidad_proyecto) AS costo,
		(((SUM(Subprod.precio_subproducto-Subprod.costo_subproducto)*DP.cantidad_proyecto)*DP.descuento)*PN.porcentaje_negociacion) AS margen
		FROM T_Detalle_Proyectos AS DP
		INNER JOIN T_Proyectos AS P
		ON DP.id_proyecto = P.id_proyecto
		INNER JOIN T_Subproductos AS Subprod
		 ON DP.id_subproducto = Subprod.id_subproducto
		INNER JOIN T_Productos AS Prod
		 ON Subprod.id_producto = Prod.id_producto
		INNER JOIN T_Precio_Negociacion AS PN
				ON P.id_precio_negociacion = PN.id_precio_negociacion
		WHERE P.id_cliente = :cliente AND year_proyecto = YEAR(CURDATE()) AND (P.id_estatusproyecto = 1 OR P.id_estatusproyecto = 2)";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(":cliente",$cliente);
		$stmt->execute();
		$x = 1;
		if($stmt->rowCount()>0)
		{
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				?>
				<tr id="tableProyectos<?php print($x);?>" class="clonedProyecto">
					<td id="select_tipoambito">
						<input readonly type="text" class="form-control" value="<?php print($row[clave_proyecto]);?>" name="tipo_ambito<?php print($x);?>" id="tipo_ambito<?php print($x);?>">
					</td>
					<td id="select_ambito">
						<select id="ambito<?php print($x);?>" name="ambito<?php print($x);?>" class="form-control">
							<option value="" selected>Selecciona un ámbito</option>
							<?php
								$this->selectAmbito();
							?>
						</select>
					</td>
					<td id="select_producto">
						<select disabled id="producto<?php print($x);?>" name="producto<?php print($x);?>" class="form-control">
							<?php
								$this->selectFamiliaProyectosAntiguos($row[id_familiaproducto]);
							?>
						</select>
					</td>
					<td id="txt_nombre">
						<input readonly type="text" class="form-control" name="nombre<?php print($x);?>" id="nombre<?php print($x);?>" value="<?php print($row[nombre_producto]) ?>">
					</td>
					<td id="select_estatus">
						<select id="estatus<?php print($x);?>" name="estatus<?php print($x);?>" class="form-control">
							<?php
								$this->selectEstatusProyectosAntiguos($row[id_estatusproyecto]);
							?>
						</select>
					</td>
					<td id="txt_cuotadef">
						<input type="text" class="form-control" data-parsley-type="digits" name="cuotadef<?php print($x);?>" id="cuotadef<?php print($x);?>" value="<?php print($row[cuota]);?>">
					</td>
					<td id="txt_porcuotadef">
						<input type="text" disabled="" class="form-control" name="por_cuotadef<?php print($x);?>" id="por_cuotadef<?php print($x);?>" value="">
					</td>
					<td id="txt_costo">
						<input value="<?php print($row[costo]);?>" type="text" class="form-control input-mask" name="costo<?php print($x);?>" id="costo<?php print($x);?>" data-inputmask="&apos;mask&apos;:&apos;99%&apos;">
					</td>
					<td id="txt_margen">
						<input value="<?php print($row[margen]);?>" type="text" disabled="" class="form-control" name="margen<?php print($x);?>" id="margen<?php print($x);?>">
					</td>
				</tr>
				<?php
				$x++;
			}
		}
		return $x;
	}
	//APROBACIÓN DE DESCUENTO O CORTESÍA
	public function AprobacionDescuento($proyecto)
	{
		$this->conn->beginTransaction();
		try
		{
			$query="UPDATE T_Detalle_Proyectos SET id_autorizacion = 1 WHERE id_proyecto = :proyecto";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":proyecto",$proyecto);
			$stmt->execute();
			$this->conn->commit();
			return true;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
			$this->conn->rollBack();
			return false;
		}
	}
	public function viewAprobacionDescuentosClientes($territorio)
	{
		$query = "SELECT
		P.id_proyecto,
		P.nombre_proyecto,
		C.nombre_cliente,
		SUM(DP.cantidad_proyecto*Prod.precio_subproducto) AS Monto,
		SUM(DP.descuento)/COUNT(DP.descuento) AS Descuento
		FROM T_Proyectos AS P
		INNER JOIN T_Clientes AS C
		ON C.id_cliente = C.id_cliente
		INNER JOIN T_Detalle_Proyectos AS DP
		ON P.id_proyecto = DP.id_proyecto
		INNER JOIN T_Subproductos AS Prod
		ON DP.id_subproducto = Prod.id_subproducto
		INNER JOIN T_TerritorioZona AS TZ
		ON C.id_zona = TZ.id_zona
		WHERE TZ.id_territorio = :territorio AND DP.id_autorizacion = 3";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(":territorio",$territorio);
		$stmt->execute();

		if($stmt->rowCount()>0)
		{
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				?>
				<tr>
					<td style="text-align: center"><a href="viewProyectos-Director.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
					<td style="text-align: center"><a href="get_aprobacion.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>&aprobacion=3"><i class="glyph-icon icon-check"></i></a></td>
					<td style="text-align: center"><a href="#" id="" name="" onclick="showModal('<?php print($row['id_proyecto']); ?>');" ><i class="glyph-icon icon-close"></i></a></td>
					<td><?php print($row['nombre_proyecto']); ?></td>
					<td><?php print($row['nombre_cliente']); ?></td>
					<td><?php print(number_format($row['Monto'],2)); ?></td>
					<td><?php print(round($row['Descuento']*100)); ?>%</td>
				</tr>		                                              
				<?php
			}
		}
		else
		{
			?>
			<script type="text/javascript">
				window.location = "home.php";
			</script>
			<?php
		}
	}
	public function viewAprobacionDescuentosProspectos($territorio)
	{
		$query = "SELECT 
				P.id_proyecto,
				P.nombre_proyecto,
				Pr.nombre_prospecto,
				SUM(DP.cantidad_proyecto*Prod.precio_subproducto) AS Monto,
				SUM(DP.descuento)/COUNT(DP.descuento) AS Descuento
			FROM T_Proyectos AS P
			INNER JOIN T_Prospectos AS Pr
			ON P.id_prospecto = Pr.id_prospecto
			INNER JOIN T_Detalle_Proyectos AS DP
			ON P.id_proyecto = DP.id_proyecto
			INNER JOIN T_Subproductos AS Prod
			ON DP.id_subproducto = Prod.id_subproducto
			INNER JOIN T_TerritorioZona AS TZ
			ON Pr.id_zona = TZ.id_zona
			WHERE TZ.id_territorio = :territorio AND DP.id_autorizacion = 3";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(":territorio",$territorio);
		$stmt->execute();
		
		
		if($stmt->rowCount()>0)
		{
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				?>
				<tr>
					<td style="text-align: center"><a href="viewProyectos.php?proyecto_prospecto=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
					<td style="text-align: center"><a href="get_aprobacion.php?proyecto_prospecto=<?php print($row['id_proyecto']); ?>&aprobacion=3"><i class="glyph-icon icon-check"></i></a></td>
					<td style="text-align: center"><a href="#" id="" name="" onclick="showModal('<?php print($row['id_proyecto']); ?>');" ><i class="glyph-icon icon-close"></i></a></td>
					<td><?php print($row['nombre_proyecto']); ?></td>
					<td><?php print($row['nombre_prospecto']); ?></td>
					<td><?php print(number_format($row['Monto'],2)); ?></td>
					<td><?php print(round($row['Descuento']*100)); ?>%</td>
				</tr>		                                              
				<?php
			}
		}
		else
		{
			?>
			<script type="text/javascript">
				window.location = "home.php";
			</script>
			<?php
		}
	}
	//VIEW PROYECTOS DETALLE
	public function viewAllDetalleProyectos($proyecto)
	{
		$query = "SELECT 
			PN.descr_precio_negociacion,
			S.nombre_subproducto,
			DP.precio_proyecto,
			(S.costo_subproducto/DP.precio_proyecto) AS costo_porcentaje,
			DP.gasto_directo,
			DP.descuento - 1 as descuento_final,
			Prod.nombre_producto,
			F.familia_producto,
			DP.cantidad_proyecto,
			DP.precio_proyecto*DP.cantidad_proyecto AS TOTAL,
			((DP.precio_proyecto)-(((S.costo_subproducto/DP.precio_proyecto)+DP.gasto_directo)*DP.precio_proyecto))*DP.cantidad_proyecto AS Margen
			FROM T_Detalle_Proyectos AS DP
			INNER JOIN T_Proyectos AS P
			ON P.id_proyecto = DP.id_proyecto
			INNER JOIN T_Precio_Negociacion AS PN
			ON PN.id_precio_negociacion = P.id_precio_negociacion
			INNER JOIN T_Subproductos AS S
			ON S.id_subproducto = DP.id_subproducto
			INNER JOIN T_Productos AS Prod
			ON S.id_producto = Prod.id_producto
			INNER JOIN T_FamiliaProductos AS F
			ON F.id_familiaproducto = Prod.id_familiaproducto
			WHERE DP.id_proyecto = :proyecto
			GROUP BY DP.id_detalleproyecto";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(":proyecto",$proyecto);
		$stmt->execute();
		if($stmt->rowCount()>0)
		{
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				?>
				<tr>
					<td><?php print($row['descr_precio_negociacion']); ?></td>
					<td><?php print($row['familia_producto']); ?></td>
					<td><?php print($row['nombre_producto']); ?></td>
					<td><?php print($row['nombre_subproducto']); ?></td>
					<td><?php print(number_format($row['precio_proyecto'],2)); ?></td>
					<td><?php print(round($row['costo_porcentaje']*100,2).'%'); ?></td>
					<td class="gasto" style="display:none"><?php print(round($row['gasto_directo'],2).'%'); ?></td>
					<td><?php print($row['cantidad_proyecto']); ?></td>
					<td><?php print($row['descuento_final'].'%'); ?></td>
					<td><?php print(number_format($row['TOTAL'],2)); ?></td>
					<td><?php print(number_format($row['Margen'],2)); ?></td>
				</tr>
				<?php
			}
		}
		else
		{
				?>
				<tr>
					<td>No hay informacion disponible</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<?php
		}
	}
	public function viewAllDetalleProyectosPresentacion($proyecto)
	{
		//DP.descuento - 1 as descuento_final,
		$query = "SELECT 
			Prod.nombre_producto,
			S.nombre_subproducto,
			DP.precio_proyecto,
			DP.cantidad_proyecto,
			DP.precio_proyecto*DP.cantidad_proyecto AS TOTAL
			FROM T_Detalle_Proyectos AS DP
			INNER JOIN T_Proyectos AS P
			ON P.id_proyecto = DP.id_proyecto
			INNER JOIN T_Subproductos AS S
			ON S.id_subproducto = DP.id_subproducto
			INNER JOIN T_Productos AS Prod
			ON S.id_producto = Prod.id_producto
			WHERE DP.id_proyecto = :proyecto
			GROUP BY DP.id_detalleproyecto";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(":proyecto",$proyecto);
		$stmt->execute();
		$row=$stmt->fetchAll(PDO::FETCH_ASSOC);
		print(json_encode($row));
	}
	public function viewAllDetalleProyectosPresentacionDatosGenrales($proyecto)
	{	
		$query = "SELECT 
				C.nombre_cliente,
				P.clave_proyecto,
				P.nombre_proyecto,
				Cuest.pregunta4 AS antecedentes,
				CONCAT(FC.expectativa, ' - ',Cuest.pregunta10) AS expectativa,
				CONCAT(Cuest.pregunta1,' - ', Cuest.pregunta2) AS situacion,
				CONCAT(Pob.poblacion,' - ',Cuest.pregunta3) AS poblacion,
				CONCAT(FC.necesidad_comentarios,' - ',Cuest.pregunta6) AS necesidad
			FROM T_Proyectos AS P
			INNER JOIN T_Fundamentacion_Proyecto_Cliente AS FPC
			ON FPC.id_proyecto = P.id_proyecto
			INNER JOIN T_FundamentacionCliente AS FC
			ON FC.id_fundamentacion = FPC.id_fundamentacion
			INNER JOIN T_Cuestionario_Fundamentacion AS CF
			ON CF.id_fundamentacion = FC.id_fundamentacion
			INNER JOIN T_Cuestionario AS Cuest
			ON Cuest.id_cuestionario = CF.id_cuestionario
			INNER JOIN T_Poblacion AS Pob
			ON Pob.id_poblacion = FC.id_poblacion
			INNER JOIN T_Necesidades AS N
			ON N.id_necesidad = FC.id_necesidad
			INNER JOIN T_Clientes as C
			ON P.id_cliente = C.id_cliente
			WHERE P.id_proyecto = :proyecto";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(":proyecto",$proyecto);
		$stmt->execute();
		if($stmt->rowCount()>0)
		{
			$row=$stmt->fetchAll(PDO::FETCH_ASSOC);
			print(json_encode($row));
		}
		else
		{
			print(("Error"));
		}
	}
	
	//PROYECTOS CLIENTE
	//DIRECTOR
	public function viewAllProyectosClienteTerritorio($zona)
	{
		$query = "SELECT P.id_proyecto,
			C.nombre_cliente,
			FPC.id_fundamentacion,
			C.id_zona,
			P.id_estatusproyecto,
			E.estatus,
			P.clave_proyecto,
			P.nombre_proyecto,
			P.fecha_creacion,
			P.fecha_realizacion,
			P.year_proyecto,
			P.otro_sub,
			P.cambio_precio,
			IF(P.otro_sub = 1,'Por autorizar cambio subproducto OTRO',IF(P.otro_sub = 2,'Cambio subproducto OTRO Autorizado','No hay cambio')) AS estatus_cambio_otro,
			IF(P.cambio_precio = 1,'Por autorizar cambio de precio',IF(P.cambio_precio = 2,'Cambio de precio Autorizado','No hay cambio')) AS estatus_cambio_precio,
			SUM(DP.precio_proyecto) AS Ingresos,
			SUM(DP.costo_proyecto) AS Costos,
			SUM(DP.cantidad_proyecto) AS Cantidad,
			SUM(DP.precio_proyecto*DP.cantidad_proyecto) AS TOTAL,
			SUM((DP.precio_proyecto-(((DP.costo_proyecto/DP.precio_proyecto)+DP.gasto_directo)*DP.precio_proyecto))*DP.cantidad_proyecto) AS Margen
			FROM T_Proyectos AS P
			INNER JOIN T_Detalle_Proyectos AS DP
			ON DP.id_proyecto = P.id_proyecto
			INNER JOIN T_Fundamentacion_Proyecto_Cliente AS FPC
			ON FPC.id_proyecto = P.id_proyecto
			INNER JOIN T_Subproductos AS S
			ON S.id_subproducto = DP.id_subproducto
			INNER JOIN T_EstatusProyecto AS E
			ON P.id_estatusproyecto = E.id_estatusproyecto
			INNER JOIN T_Clientes as C
			ON P.id_cliente = C.id_cliente
			INNER JOIN T_TerritorioZona AS TZ
			ON TZ.id_zona = C.id_zona
			INNER JOIN T_Zona AS Z
			ON Z.id_zona = C.id_zona
			WHERE TZ.id_territorio = :zona
			GROUP BY P.id_cliente, P.id_proyecto";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(":zona",$zona);
		$stmt->execute();
		if($stmt->rowCount()>0)
		{
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				$estatus = intval($row['id_estatusproyecto']);
				$cambioPrecio = intval($row['cambio_precio']);
				$otroSub = intval($row['otro_sub']);
				if($estatus == 6)
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-Director.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td></td>
						<td style="text-align: center"><a href="viewFundamentacion-Director.php?fundamentacion=<?php print($row['id_fundamentacion']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td></td>
						<td><?php print($row['id_zona']); ?></td>
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td></td>
					</tr>    
					<?php
				}
				elseif($estatus == 4 && ($cambioPrecio == 1 || $otroSub == 1))
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-Director.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm-Director.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<td style="text-align: center"><a href="viewFundamentacion-Director.php?fundamentacion=<?php print($row['id_fundamentacion']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td>Solicitó cambio</td>
						<td><?php print($row['id_zona']); ?></td>
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td></td>
					</tr>    
					<?php
				}
				elseif($estatus == 4 && ($cambioPrecio == 2 || $otroSub == 2))
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-Director.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm-Director.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<td style="text-align: center"><a href="viewFundamentacion-Director.php?fundamentacion=<?php print($row['id_fundamentacion']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td>Cambio realizado</td>
						<td><?php print($row['id_zona']); ?></td>
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td style="text-align: center"><a href="OIForm.php?OI=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-check"></i></a></td>
					</tr>
					<?php
				}
				elseif($estatus == 4 && ($cambioPrecio == 0 || $otroSub == 0))
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-Director.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm-Director.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<td style="text-align: center"><a href="viewFundamentacion-Director.php?fundamentacion=<?php print($row['id_fundamentacion']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td></td>
						<td><?php print($row['id_zona']); ?></td>
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td style="text-align: center"><a href="OIForm.php?OI=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-check"></i></a></td>
					</tr>
					<?php
				}
				elseif($cambioPrecio == 1 || $otroSub == 1)
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-Director.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm-Director.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<td style="text-align: center"><a href="viewFundamentacion-Director.php?fundamentacion=<?php print($row['id_fundamentacion']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td>Solicitó cambio</td>
						<td><?php print($row['id_zona']); ?></td>
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td></td>
					</tr>    
					<?php
				}
				elseif($cambioPrecio == 2 || $otroSub == 2)
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-Director.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm-Director.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<td style="text-align: center"><a href="viewFundamentacion-Director.php?fundamentacion=<?php print($row['id_fundamentacion']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td>Cambio realizado</td>
						<td><?php print($row['id_zona']); ?></td>
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td></td>
					</tr>
					<?php
				}
				else
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-Director.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm-Director.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<td style="text-align: center"><a href="viewFundamentacion-Director.php?fundamentacion=<?php print($row['id_fundamentacion']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td></td>
						<td><?php print($row['id_zona']); ?></td>
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td></td>
					</tr>
					<?php
				}
			}
		}
		else
		{
			?>
			<tr>
				<td>No hay información disponible</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<?php
		}
	}
	public function viewAllProyectosClienteDirector($zona)
	{
		$query = "SELECT P.id_proyecto,
			C.nombre_cliente,
			FPC.id_fundamentacion,
			P.id_estatusproyecto,
			E.estatus,
			P.clave_proyecto,
			P.nombre_proyecto,
			P.fecha_creacion,
			P.fecha_realizacion,
			P.year_proyecto,
			P.otro_sub,
			P.cambio_precio,
			IF(P.otro_sub = 1,'Por autorizar cambio subproducto OTRO',IF(P.otro_sub = 2,'Cambio subproducto OTRO Autorizado','No hay cambio')) AS estatus_cambio_otro,
			IF(P.cambio_precio = 1,'Por autorizar cambio de precio',IF(P.cambio_precio = 2,'Cambio de precio Autorizado','No hay cambio')) AS estatus_cambio_precio,
			SUM(DP.precio_proyecto) AS Ingresos,
			SUM(DP.costo_proyecto) AS Costos,
			SUM(DP.cantidad_proyecto) AS Cantidad,
			SUM(DP.precio_proyecto*DP.cantidad_proyecto) AS TOTAL,
			SUM((DP.precio_proyecto-(((DP.costo_proyecto/DP.precio_proyecto)+DP.gasto_directo)*DP.precio_proyecto))*DP.cantidad_proyecto) AS Margen
			FROM T_Proyectos AS P
			INNER JOIN T_Detalle_Proyectos AS DP
			ON DP.id_proyecto = P.id_proyecto
			INNER JOIN T_Fundamentacion_Proyecto_Cliente AS FPC
			ON FPC.id_proyecto = P.id_proyecto
			INNER JOIN T_Subproductos AS S
			ON S.id_subproducto = DP.id_subproducto
			INNER JOIN T_Precio_Negociacion AS PN
			ON P.id_precio_negociacion = PN.id_precio_negociacion
			INNER JOIN T_EstatusProyecto AS E
			ON P.id_estatusproyecto = E.id_estatusproyecto
			INNER JOIN T_Clientes as C
			ON P.id_cliente = C.id_cliente
			WHERE C.id_zona = :zona
			GROUP BY P.id_cliente, P.id_proyecto";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(":zona",$zona);
		$stmt->execute();
		if($stmt->rowCount()>0)
		{
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				$estatus = intval($row['id_estatusproyecto']);
				$cambioPrecio = intval($row['cambio_precio']);
				$otroSub = intval($row['otro_sub']);
				if($estatus == 6)
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-Director.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td></td>
						<td style="text-align: center"><a href="viewFundamentacion-Director.php?fundamentacion=<?php print($row['id_fundamentacion']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td></td>
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td></td>
					</tr>    
					<?php
				}
				elseif($estatus == 4 && ($cambioPrecio == 1 || $otroSub == 1))
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-Director.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm-Director.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<td style="text-align: center"><a href="viewFundamentacion-Director.php?fundamentacion=<?php print($row['id_fundamentacion']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td>Solicitó cambio</td>
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td></td>
					</tr>    
					<?php
				}
				elseif($estatus == 4 && ($cambioPrecio == 2 || $otroSub == 2))
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-Director.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm-Director.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<td style="text-align: center"><a href="viewFundamentacion-Director.php?fundamentacion=<?php print($row['id_fundamentacion']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td>Cambio realizado</td>
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td style="text-align: center"><a href="OIForm.php?OI=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-check"></i></a></td>
					</tr>
					<?php
				}
				elseif($estatus == 4 && ($cambioPrecio == 0 || $otroSub == 0))
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-Director.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm-Director.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<td style="text-align: center"><a href="viewFundamentacion-Director.php?fundamentacion=<?php print($row['id_fundamentacion']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td></td>
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td style="text-align: center"><a href="OIForm.php?OI=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-check"></i></a></td>
					</tr>
					<?php
				}
				elseif($cambioPrecio == 1 || $otroSub == 1)
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-Director.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm-Director.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<td style="text-align: center"><a href="viewFundamentacion-Director.php?fundamentacion=<?php print($row['id_fundamentacion']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td>Solicitó cambio</td>
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td></td>
					</tr>    
					<?php
				}
				elseif($cambioPrecio == 2 || $otroSub == 2)
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-Director.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm-Director.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<td style="text-align: center"><a href="viewFundamentacion-Director.php?fundamentacion=<?php print($row['id_fundamentacion']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td>Cambio realizado</td>
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td></td>
					</tr>
					<?php
				}
				else
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-Director.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm-Director.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<td style="text-align: center"><a href="viewFundamentacion-Director.php?fundamentacion=<?php print($row['id_fundamentacion']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td></td>
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td></td>
					</tr>
					<?php
				}
			}
		}
		else
		{
			?>
			<tr>
				<td>No hay información disponible</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<?php
		}
	}
	//SUBDIRECTOR
	public function viewAllProyectosClienteTerritorioSubdirector($zona)
	{
		$query = "SELECT P.id_proyecto,
			C.nombre_cliente,
			C.id_zona,
			FPC.id_fundamentacion,
			P.id_estatusproyecto,
			E.estatus,
			P.clave_proyecto,
			P.nombre_proyecto,
			P.fecha_creacion,
			P.fecha_realizacion,
			P.year_proyecto,
			P.otro_sub,
			P.cambio_precio,
			IF(P.otro_sub = 1,'Por autorizar cambio subproducto OTRO',IF(P.otro_sub = 2,'Cambio subproducto OTRO Autorizado','No hay cambio')) AS estatus_cambio_otro,
			IF(P.cambio_precio = 1,'Por autorizar cambio de precio',IF(P.cambio_precio = 2,'Cambio de precio Autorizado','No hay cambio')) AS estatus_cambio_precio,
			SUM(DP.precio_proyecto) AS Ingresos,
			SUM(DP.costo_proyecto) AS Costos,
			SUM(DP.cantidad_proyecto) AS Cantidad,
			SUM(DP.precio_proyecto*DP.cantidad_proyecto) AS TOTAL,
			SUM((DP.precio_proyecto-((DP.costo_proyecto+DP.gasto_directo)*DP.precio_proyecto))*DP.cantidad_proyecto) AS Margen
			FROM T_Proyectos AS P
			INNER JOIN T_Detalle_Proyectos AS DP
			ON DP.id_proyecto = P.id_proyecto
			INNER JOIN T_Fundamentacion_Proyecto_Cliente AS FPC
			ON FPC.id_proyecto = P.id_proyecto
			INNER JOIN T_Subproductos AS S
			ON S.id_subproducto = DP.id_subproducto
			INNER JOIN T_EstatusProyecto AS E
			ON P.id_estatusproyecto = E.id_estatusproyecto
			INNER JOIN T_Clientes as C
			ON P.id_cliente = C.id_cliente
			INNER JOIN T_TerritorioZona AS TZ
			ON TZ.id_zona = C.id_zona
			INNER JOIN T_Zona AS Z
			ON Z.id_zona = C.id_zona
			WHERE TZ.id_territorio = :zona
			GROUP BY P.id_cliente, P.id_proyecto";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(":zona",$zona);
		$stmt->execute();
		if($stmt->rowCount()>0)
		{
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				$estatus = intval($row['id_estatusproyecto']);
				$cambioPrecio = intval($row['cambio_precio']);
				$otroSub = intval($row['otro_sub']);
				if($estatus == 6)
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-Subdirector.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td></td>
						<td style="text-align: center"><a href="viewFundamentacion-Subdirector.php?fundamentacion=<?php print($row['id_fundamentacion']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td></td>
						<td><?php print($row['id_zona']); ?></td>
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td></td>
					</tr>    
					<?php
				}
				elseif($estatus == 4 && ($cambioPrecio == 1 || $otroSub == 1))
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-Subdirector.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm-Subdirector.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<td style="text-align: center"><a href="viewFundamentacion-Subdirector.php?fundamentacion=<?php print($row['id_fundamentacion']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td>Solicitó cambio</td>
						<td><?php print($row['id_zona']); ?></td>
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td></td>
					</tr>    
					<?php
				}
				elseif($estatus == 4 && ($cambioPrecio == 2 || $otroSub == 2))
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-Subdirector.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm-Subdirector.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<td style="text-align: center"><a href="viewFundamentacion-Subdirector.php?fundamentacion=<?php print($row['id_fundamentacion']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td>Cambio realizado</td>
						<td><?php print($row['id_zona']); ?></td>
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td style="text-align: center"><a href="OIForm-Subdirector.php?OI=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-check"></i></a></td>
					</tr>
					<?php
				}
				elseif($estatus == 4 && ($cambioPrecio == 0 || $otroSub == 0))
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-Subdirector.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm-Subdirector.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<td style="text-align: center"><a href="viewFundamentacion-Subdirector.php?fundamentacion=<?php print($row['id_fundamentacion']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td></td>
						<td><?php print($row['id_zona']); ?></td>
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td style="text-align: center"><a href="OIForm-Subdirector.php?OI=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-check"></i></a></td>
					</tr>
					<?php
				}
				elseif($cambioPrecio == 1 || $otroSub == 1)
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-Subdirector.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm-Subdirector.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<td style="text-align: center"><a href="viewFundamentacion-Subdirector.php?fundamentacion=<?php print($row['id_fundamentacion']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td>Solicitó cambio</td>
						<td><?php print($row['id_zona']); ?></td>
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td></td>
					</tr>    
					<?php
				}
				elseif($cambioPrecio == 2 || $otroSub == 2)
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-Subdirector.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm-Subdirector.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<td style="text-align: center"><a href="viewFundamentacion-Subdirector.php?fundamentacion=<?php print($row['id_fundamentacion']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td>Cambio realizado</td>
						<td><?php print($row['id_zona']); ?></td>
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td></td>
					</tr>
					<?php
				}
				else
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-Subdirector.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm-Subdirector.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<td style="text-align: center"><a href="viewFundamentacion-Subdirector.php?fundamentacion=<?php print($row['id_fundamentacion']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td></td>
						<td><?php print($row['id_zona']); ?></td>
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td></td>
					</tr>
					<?php
				}
			}
		}
		else
		{
			?>
			<tr>
				<td>No hay información disponible</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<?php
		}
	}
	public function viewAllProyectosClienteSubdirector($zona)
	{
		$query = "SELECT P.id_proyecto,
			C.nombre_cliente,
			FPC.id_fundamentacion,
			P.id_estatusproyecto,
			E.estatus,
			P.clave_proyecto,
			P.nombre_proyecto,
			P.fecha_creacion,
			P.fecha_realizacion,
			P.year_proyecto,
			P.otro_sub,
			P.cambio_precio,
			IF(P.otro_sub = 1,'Por autorizar cambio subproducto OTRO',IF(P.otro_sub = 2,'Cambio subproducto OTRO Autorizado','No hay cambio')) AS estatus_cambio_otro,
			IF(P.cambio_precio = 1,'Por autorizar cambio de precio',IF(P.cambio_precio = 2,'Cambio de precio Autorizado','No hay cambio')) AS estatus_cambio_precio,
			SUM(DP.precio_proyecto) AS Ingresos,
			SUM(DP.costo_proyecto) AS Costos,
			SUM(DP.cantidad_proyecto) AS Cantidad,
			SUM(DP.precio_proyecto*DP.cantidad_proyecto) AS TOTAL,
			SUM((DP.precio_proyecto-((DP.costo_proyecto+DP.gasto_directo)*DP.precio_proyecto))*DP.cantidad_proyecto) AS Margen
			FROM T_Proyectos AS P
			INNER JOIN T_Detalle_Proyectos AS DP
			ON DP.id_proyecto = P.id_proyecto
			INNER JOIN T_Fundamentacion_Proyecto_Cliente AS FPC
			ON FPC.id_proyecto = P.id_proyecto
			INNER JOIN T_Subproductos AS S
			ON S.id_subproducto = DP.id_subproducto
			INNER JOIN T_Precio_Negociacion AS PN
			ON P.id_precio_negociacion = PN.id_precio_negociacion
			INNER JOIN T_EstatusProyecto AS E
			ON P.id_estatusproyecto = E.id_estatusproyecto
			INNER JOIN T_Clientes as C
			ON P.id_cliente = C.id_cliente
			WHERE C.id_zona = :zona
			GROUP BY P.id_cliente, P.id_proyecto";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(":zona",$zona);
		$stmt->execute();
		if($stmt->rowCount()>0)
		{
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				$estatus = intval($row['id_estatusproyecto']);
				$cambioPrecio = intval($row['cambio_precio']);
				$otroSub = intval($row['otro_sub']);
				if($estatus == 6)
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-Subdirector.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td></td>
						<td style="text-align: center"><a href="viewFundamentacion-Subdirector.php?fundamentacion=<?php print($row['id_fundamentacion']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td></td>
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td></td>
					</tr>    
					<?php
				}
				elseif($estatus == 4 && ($cambioPrecio == 1 || $otroSub == 1))
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-Subdirector.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm-Subdirector.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<td style="text-align: center"><a href="viewFundamentacion-Subdirector.php?fundamentacion=<?php print($row['id_fundamentacion']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td>Solicitó cambio</td>
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td></td>
					</tr>    
					<?php
				}
				elseif($estatus == 4 && ($cambioPrecio == 2 || $otroSub == 2))
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-Subdirector.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm-Subdirector.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<td style="text-align: center"><a href="viewFundamentacion-Subdirector.php?fundamentacion=<?php print($row['id_fundamentacion']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td>Cambio realizado</td>
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td style="text-align: center"><a href="OIForm-Subdirector.php?OI=<?php print($row['id_proyecto']); ?>&zona=<?php print($zona); ?>"><i class="glyph-icon icon-check"></i></a></td>
					</tr>
					<?php
				}
				elseif($estatus == 4 && ($cambioPrecio == 0 || $otroSub == 0))
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-Subdirector.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm-Subdirector.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<td style="text-align: center"><a href="viewFundamentacion-Subdirector.php?fundamentacion=<?php print($row['id_fundamentacion']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td></td>
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td style="text-align: center"><a href="OIForm-Subdirector.php?OI=<?php print($row['id_proyecto']); ?>&zona=<?php print($zona); ?>"><i class="glyph-icon icon-check"></i></a></td>
					</tr>
					<?php
				}
				elseif($cambioPrecio == 1 || $otroSub == 1)
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-Subdirector.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm-Subdirector.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<td style="text-align: center"><a href="viewFundamentacion-Subdirector.php?fundamentacion=<?php print($row['id_fundamentacion']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td>Solicitó cambio</td>
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td></td>
					</tr>    
					<?php
				}
				elseif($cambioPrecio == 2 || $otroSub == 2)
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-Subdirector.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm-Subdirector.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<td style="text-align: center"><a href="viewFundamentacion-Subdirector.php?fundamentacion=<?php print($row['id_fundamentacion']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td>Cambio realizado</td>
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td></td>
					</tr>
					<?php
				}
				else
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-Subdirector.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm-Subdirector.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<td style="text-align: center"><a href="viewFundamentacion-Subdirector.php?fundamentacion=<?php print($row['id_fundamentacion']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td></td>
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td></td>
					</tr>
					<?php
				}
			}
		}
		else
		{
			?>
			<tr>
				<td>No hay información disponible</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<?php
		}
	}
	//COORDINADOR
	public function viewAllProyectosClienteCoordinador($zona)
	{
		$query = "SELECT P.id_proyecto,
			C.nombre_cliente,
			P.id_estatusproyecto,
			FPC.id_fundamentacion,
			E.estatus,
			P.clave_proyecto,
			P.nombre_proyecto,
			P.fecha_creacion,
			P.fecha_realizacion,
			P.year_proyecto,
			P.otro_sub,
			P.cambio_precio,
			IF(P.otro_sub = 1,'Por autorizar cambio subproducto OTRO',IF(P.otro_sub = 2,'Cambio subproducto OTRO Autorizado','No hay cambio')) AS estatus_cambio_otro,
			IF(P.cambio_precio = 1,'Por autorizar cambio de precio',IF(P.cambio_precio = 2,'Cambio de precio Autorizado','No hay cambio')) AS estatus_cambio_precio,
			SUM(DP.precio_proyecto) AS Ingresos,
			SUM(DP.costo_proyecto) AS Costos,
			SUM(DP.cantidad_proyecto) AS Cantidad,
			SUM(DP.precio_proyecto*DP.cantidad_proyecto) AS TOTAL,
			SUM((DP.precio_proyecto-((DP.costo_proyecto+DP.gasto_directo)*DP.precio_proyecto))*DP.cantidad_proyecto) AS Margen
			FROM T_Proyectos AS P
			INNER JOIN T_Detalle_Proyectos AS DP
			ON DP.id_proyecto = P.id_proyecto
			INNER JOIN T_Fundamentacion_Proyecto_Cliente AS FPC
			ON FPC.id_proyecto = P.id_proyecto
			INNER JOIN T_Subproductos AS S
			ON S.id_subproducto = DP.id_subproducto
			INNER JOIN T_Precio_Negociacion AS PN
			ON P.id_precio_negociacion = PN.id_precio_negociacion
			INNER JOIN T_EstatusProyecto AS E
			ON P.id_estatusproyecto = E.id_estatusproyecto
			INNER JOIN T_Clientes as C
			ON P.id_cliente = C.id_cliente
			WHERE C.id_zona = :zona
			GROUP BY P.id_cliente, P.id_proyecto";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(":zona",$zona);
		$stmt->execute();
		if($stmt->rowCount()>0)
		{
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				$estatus = intval($row['id_estatusproyecto']);
				$cambioPrecio = intval($row['cambio_precio']);
				$otroSub = intval($row['otro_sub']);
				if($estatus == 6)
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-Coordinador.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td></td>
						<td style="text-align: center"><a href="#" onclick="getPPTX(<?php print(intval($row['id_proyecto'])); ?>);"><i class="glyph-icon icon-file-powerpoint-o"></i></a></td>
						<td style="text-align: center"><a href="viewFundamentacion-Coordinador.php?fundamentacion=<?php print($row['id_fundamentacion']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td style="text-align: center"><a href="ProyectoClienteFormExt-Coordinador.php?fundamentacion=<?php print($row['id_fundamentacion']);?>&proyecto=<?php print($row['id_proyecto']);?>"><i class="glyph-icon icon-plus"></i></a></td>
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td></td>
					</tr>    
					<?php
				}
				elseif($estatus == 4 && ($cambioPrecio == 1 || $otroSub == 1))
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-Coordinador.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td>Solicitud de cambio enviada</td>
						<td style="text-align: center"><a href="#" onclick="getPPTX(<?php print(intval($row['id_proyecto'])); ?>);"><i class="glyph-icon icon-file-powerpoint-o"></i></a></td>
						<td style="text-align: center"><a href="viewFundamentacion-Coordinador.php?fundamentacion=<?php print($row['id_fundamentacion']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td style="text-align: center"><a href="ProyectoClienteFormExt-Coordinador.php?fundamentacion=<?php print($row['id_fundamentacion']);?>&proyecto=<?php print($row['id_proyecto']);?>"><i class="glyph-icon icon-plus"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td></td>
					</tr>    
					<?php
				}
				elseif($estatus == 4 && ($cambioPrecio == 2 || $otroSub == 2))
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-Coordinador.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm-Coordinador.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<td style="text-align: center"><a href="#" onclick="getPPTX(<?php print(intval($row['id_proyecto'])); ?>);"><i class="glyph-icon icon-file-powerpoint-o"></i></a></td>
						<td style="text-align: center"><a href="viewFundamentacion-Coordinador.php?fundamentacion=<?php print($row['id_fundamentacion']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td style="text-align: center"><a href="ProyectoClienteFormExt-Coordinador.php?fundamentacion=<?php print($row['id_fundamentacion']);?>&proyecto=<?php print($row['id_proyecto']);?>"><i class="glyph-icon icon-plus"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td style="text-align: center"><a href="OIForm-Coordinador.php?OI=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-check"></i></a></td>
					</tr>
					<?php
				}
				elseif($estatus == 4 && ($cambioPrecio == 0 || $otroSub == 0))
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-Coordinador.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm-Coordinador.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<td style="text-align: center"><a href="#" onclick="getPPTX(<?php print(intval($row['id_proyecto'])); ?>);"><i class="glyph-icon icon-file-powerpoint-o"></i></a></td>
						<td style="text-align: center"><a href="viewFundamentacion-Coordinador.php?fundamentacion=<?php print($row['id_fundamentacion']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td style="text-align: center"><a href="ProyectoClienteFormExt-Coordinador.php?fundamentacion=<?php print($row['id_fundamentacion']);?>&proyecto=<?php print($row['id_proyecto']);?>"><i class="glyph-icon icon-plus"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td style="text-align: center"><a href="OIForm-Coordinador.php?OI=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-check"></i></a></td>
					</tr>
					<?php
				}
				elseif($cambioPrecio == 1 || $otroSub == 1)
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-Coordinador.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm-Coordinador.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<td style="text-align: center"><a href="#" onclick="getPPTX(<?php print(intval($row['id_proyecto'])); ?>);"><i class="glyph-icon icon-file-powerpoint-o"></i></a></td>
						<td style="text-align: center"><a href="viewFundamentacion-Coordinador.php?fundamentacion=<?php print($row['id_fundamentacion']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td style="text-align: center"><a href="ProyectoClienteFormExt-Coordinador.php?fundamentacion=<?php print($row['id_fundamentacion']);?>&proyecto=<?php print($row['id_proyecto']);?>"><i class="glyph-icon icon-plus"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td></td>
					</tr>    
					<?php
				}
				elseif($cambioPrecio == 2 || $otroSub == 2)
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-Coordinador.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm-Coordinador.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<td style="text-align: center"><a href="#" onclick="getPPTX(<?php print(intval($row['id_proyecto'])); ?>);"><i class="glyph-icon icon-file-powerpoint-o"></i></a></td>
						<td style="text-align: center"><a href="viewFundamentacion-Coordinador.php?fundamentacion=<?php print($row['id_fundamentacion']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td style="text-align: center"><a href="ProyectoClienteFormExt-Coordinador.php?fundamentacion=<?php print($row['id_fundamentacion']);?>&proyecto=<?php print($row['id_proyecto']);?>"><i class="glyph-icon icon-plus"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td></td>
					</tr>
					<?php
				}
				else
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-Coordinador.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm-Coordinador.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<td style="text-align: center"><a href="#" onclick="getPPTX(<?php print(intval($row['id_proyecto'])); ?>);"><i class="glyph-icon icon-file-powerpoint-o"></i></a></td>
						<td style="text-align: center"><a href="viewFundamentacion-Coordinador.php?fundamentacion=<?php print($row['id_fundamentacion']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td style="text-align: center"><a href="ProyectoClienteFormExt-Coordinador.php?fundamentacion=<?php print($row['id_fundamentacion']);?>&proyecto=<?php print($row['id_proyecto']);?>"><i class="glyph-icon icon-plus"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td></td>
					</tr>
					<?php
				}
			}
		}
		else
		{
			?>
			<tr>
				<td>No hay información disponible</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<?php
		}
	}
	//LIDER
	public function viewAllProyectosCliente($zona)
	{
		$query = "SELECT P.id_proyecto,
			C.nombre_cliente,
			P.id_estatusproyecto,
			FPC.id_fundamentacion,
			E.estatus,
			P.clave_proyecto,
			P.nombre_proyecto,
			P.fecha_creacion,
			P.fecha_realizacion,
			P.year_proyecto,
			P.otro_sub,
			P.cambio_precio,
			IF(P.otro_sub = 1,'Por autorizar cambio subproducto OTRO',IF(P.otro_sub = 2,'Cambio subproducto OTRO Autorizado','No hay cambio')) AS estatus_cambio_otro,
			IF(P.cambio_precio = 1,'Por autorizar cambio de precio',IF(P.cambio_precio = 2,'Cambio de precio Autorizado','No hay cambio')) AS estatus_cambio_precio,
			SUM(DP.precio_proyecto) AS Ingresos,
			SUM(DP.costo_proyecto) AS Costos,
			SUM(DP.cantidad_proyecto) AS Cantidad,
			SUM(DP.precio_proyecto*DP.cantidad_proyecto) AS TOTAL,
			SUM((DP.precio_proyecto-((DP.costo_proyecto+DP.gasto_directo)*DP.precio_proyecto))*DP.cantidad_proyecto) AS Margen
			FROM T_Proyectos AS P
			INNER JOIN T_Detalle_Proyectos AS DP
			ON DP.id_proyecto = P.id_proyecto
			INNER JOIN T_Fundamentacion_Proyecto_Cliente AS FPC
			ON FPC.id_proyecto = P.id_proyecto
			INNER JOIN T_Subproductos AS S
			ON S.id_subproducto = DP.id_subproducto
			INNER JOIN T_Precio_Negociacion AS PN
			ON P.id_precio_negociacion = PN.id_precio_negociacion
			INNER JOIN T_EstatusProyecto AS E
			ON P.id_estatusproyecto = E.id_estatusproyecto
			INNER JOIN T_Clientes as C
			ON P.id_cliente = C.id_cliente
			WHERE C.id_zona = :zona
			GROUP BY P.id_cliente, P.id_proyecto";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(":zona",$zona);
		$stmt->execute();
		if($stmt->rowCount()>0)
		{
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				$estatus = intval($row['id_estatusproyecto']);
				$cambioPrecio = intval($row['cambio_precio']);
				$otroSub = intval($row['otro_sub']);
				if($estatus == 6)
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td></td>
						<td style="text-align: center"><a href="#" onclick="getPPTX(<?php print(intval($row['id_proyecto'])); ?>);"><i class="glyph-icon icon-file-powerpoint-o"></i></a></td>
						<td style="text-align: center"><a href="ClientesProyectosTab.php?download=true&proyecto=<?php print($row['id_proyecto']); ?>" id="download" name="download"/><i class="glyph-icon icon-download"></i></a></td>
						<td style="text-align: center"><a href="viewFundamentacion.php?fundamentacion=<?php print($row['id_fundamentacion']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td style="text-align: center"><a href="ProyectoClienteFormExt.php?fundamentacion=<?php print($row['id_fundamentacion']);?>&proyecto=<?php print($row['id_proyecto']);?>"><i class="glyph-icon icon-plus"></i></a></td>
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td></td>
					</tr>    
					<?php
				}
				elseif($estatus == 4 && ($cambioPrecio == 1 || $otroSub == 1))
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td>Solicitud de cambio enviada</td>
						<td style="text-align: center"><a href="#" onclick="getPPTX(<?php print(intval($row['id_proyecto'])); ?>);"><i class="glyph-icon icon-file-powerpoint-o"></i></a></td>
						<td></td>
						<td style="text-align: center"><a href="viewFundamentacion.php?fundamentacion=<?php print($row['id_fundamentacion']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td style="text-align: center"><a href="ProyectoClienteFormExt.php?fundamentacion=<?php print($row['id_fundamentacion']);?>&proyecto=<?php print($row['id_proyecto']);?>"><i class="glyph-icon icon-plus"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td></td>
					</tr>    
					<?php
				}
				elseif($estatus == 4 && ($cambioPrecio == 2 || $otroSub == 2) && $zona != 7)
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<td style="text-align: center"><a href="#" onclick="getPPTX(<?php print(intval($row['id_proyecto'])); ?>);"><i class="glyph-icon icon-file-powerpoint-o"></i></a></td>
						<td></td>
						<td style="text-align: center"><a href="viewFundamentacion.php?fundamentacion=<?php print($row['id_fundamentacion']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td style="text-align: center"><a href="ProyectoClienteFormExt.php?fundamentacion=<?php print($row['id_fundamentacion']);?>&proyecto=<?php print($row['id_proyecto']);?>"><i class="glyph-icon icon-plus"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td style="text-align: center"><a href="OIForm.php?OI=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-check"></i></a></td>
					</tr>
					<?php
				}
				elseif($estatus == 8)
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<td style="text-align: center"><a href="#" onclick="getPPTX(<?php print(intval($row['id_proyecto'])); ?>);"><i class="glyph-icon icon-file-powerpoint-o"></i></a></td>
						<td></td>
						<td style="text-align: center"><a href="viewFundamentacion.php?fundamentacion=<?php print($row['id_fundamentacion']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td style="text-align: center"><a href="ProyectoClienteFormExt.php?fundamentacion=<?php print($row['id_fundamentacion']);?>&proyecto=<?php print($row['id_proyecto']);?>"><i class="glyph-icon icon-plus"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td style="text-align: center"><a href="OIForm.php?OI=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-check"></i></a></td>
					</tr>
					<?php
				}
				elseif($estatus == 4 && ($cambioPrecio == 2 || $otroSub == 2) && $zona == 7)
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<td style="text-align: center"><a href="#" onclick="getPPTX(<?php print(intval($row['id_proyecto'])); ?>);"><i class="glyph-icon icon-file-powerpoint-o"></i></a></td>
						<td></td>
						<td style="text-align: center"><a href="viewFundamentacion.php?fundamentacion=<?php print($row['id_fundamentacion']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td style="text-align: center"><a href="ProyectoClienteFormExt.php?fundamentacion=<?php print($row['id_fundamentacion']);?>&proyecto=<?php print($row['id_proyecto']);?>"><i class="glyph-icon icon-plus"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td style="text-align: center"><a href="TimeBoxProyecto.php?idProyecto=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-check"></i></a></td>
					</tr>
					<?php
				}
				elseif($estatus == 4 && ($cambioPrecio == 0 || $otroSub == 0))
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<td style="text-align: center"><a href="#" onclick="getPPTX(<?php print(intval($row['id_proyecto'])); ?>);"><i class="glyph-icon icon-file-powerpoint-o"></i></a></td>
						<td></td>
						<td style="text-align: center"><a href="viewFundamentacion.php?fundamentacion=<?php print($row['id_fundamentacion']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td style="text-align: center"><a href="ProyectoClienteFormExt.php?fundamentacion=<?php print($row['id_fundamentacion']);?>&proyecto=<?php print($row['id_proyecto']);?>"><i class="glyph-icon icon-plus"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td style="text-align: center"><a href="OIForm.php?OI=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-check"></i></a></td>
					</tr>
					<?php
				}
				elseif($cambioPrecio == 1 || $otroSub == 1)
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<td style="text-align: center"><a href="#" onclick="getPPTX(<?php print(intval($row['id_proyecto'])); ?>);"><i class="glyph-icon icon-file-powerpoint-o"></i></a></td>
						<td></td>
						<td style="text-align: center"><a href="viewFundamentacion.php?fundamentacion=<?php print($row['id_fundamentacion']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td style="text-align: center"><a href="ProyectoClienteFormExt.php?fundamentacion=<?php print($row['id_fundamentacion']);?>&proyecto=<?php print($row['id_proyecto']);?>"><i class="glyph-icon icon-plus"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td></td>
					</tr>    
					<?php
				}
				elseif($cambioPrecio == 2 || $otroSub == 2)
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<td style="text-align: center"><a href="#" onclick="getPPTX(<?php print(intval($row['id_proyecto'])); ?>);"><i class="glyph-icon icon-file-powerpoint-o"></i></a></td>
						<td></td>
						<td style="text-align: center"><a href="viewFundamentacion.php?fundamentacion=<?php print($row['id_fundamentacion']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td style="text-align: center"><a href="ProyectoClienteFormExt.php?fundamentacion=<?php print($row['id_fundamentacion']);?>&proyecto=<?php print($row['id_proyecto']);?>"><i class="glyph-icon icon-plus"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td></td>
					</tr>
					<?php
				}
				else
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<td style="text-align: center"><a href="#" onclick="getPPTX(<?php print(intval($row['id_proyecto'])); ?>);"><i class="glyph-icon icon-file-powerpoint-o"></i></a></td>
						<td></td>
						<td style="text-align: center"><a href="viewFundamentacion.php?fundamentacion=<?php print($row['id_fundamentacion']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td style="text-align: center"><a href="ProyectoClienteFormExt.php?fundamentacion=<?php print($row['id_fundamentacion']);?>&proyecto=<?php print($row['id_proyecto']);?>"><i class="glyph-icon icon-plus"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td></td>
					</tr>
					<?php
				}
			}
		}
		else
		{
			?>
			<tr>
				<td>No hay información disponible</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<?php
		}
	}
	//AVEFO
	public function viewAllProyectosClienteAVEFO($zona)
	{
		$query = "SELECT P.id_proyecto,
			C.nombre_cliente,
			P.id_estatusproyecto,
			E.estatus,
			P.clave_proyecto,
			P.nombre_proyecto,
			P.fecha_creacion,
			P.fecha_realizacion,
			P.year_proyecto,
			P.otro_sub,
			P.cambio_precio,
			IF(P.otro_sub = 1,'Por autorizar cambio subproducto OTRO',IF(P.otro_sub = 2,'Cambio subproducto OTRO Autorizado','No hay cambio')) AS estatus_cambio_otro,
			IF(P.cambio_precio = 1,'Por autorizar cambio de precio',IF(P.cambio_precio = 2,'Cambio de precio Autorizado','No hay cambio')) AS estatus_cambio_precio,
			SUM(DP.precio_proyecto) AS Ingresos,
			SUM(DP.costo_proyecto) AS Costos,
			SUM(DP.cantidad_proyecto) AS Cantidad,
			SUM(DP.precio_proyecto*DP.cantidad_proyecto) AS TOTAL,
			SUM((DP.precio_proyecto-((DP.costo_proyecto+DP.gasto_directo)*DP.precio_proyecto))*DP.cantidad_proyecto) AS Margen
			FROM T_Proyectos AS P
			INNER JOIN T_Detalle_Proyectos AS DP
			ON DP.id_proyecto = P.id_proyecto
			INNER JOIN T_Subproductos AS S
			ON S.id_subproducto = DP.id_subproducto
			INNER JOIN T_Precio_Negociacion AS PN
			ON P.id_precio_negociacion = PN.id_precio_negociacion
			INNER JOIN T_EstatusProyecto AS E
			ON P.id_estatusproyecto = E.id_estatusproyecto
			INNER JOIN T_Clientes as C
			ON P.id_cliente = C.id_cliente
			WHERE C.id_zona = :zona
			GROUP BY P.id_cliente, P.id_proyecto";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(":zona",$zona);
		$stmt->execute();
		if($stmt->rowCount()>0)
		{
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				$estatus = intval($row['id_estatusproyecto']);
				$cambioPrecio = intval($row['cambio_precio']);
				$otroSub = intval($row['otro_sub']);
				if($estatus == 6)
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-AVEFO.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td></td>
						<td></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
					</tr>    
					<?php
				}
				elseif($estatus == 4 && ($cambioPrecio == 1 || $otroSub == 1))
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-AVEFO.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm-AVEFO.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td>Solicitó cambio</td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
					</tr>
					<?php
				}
				elseif($estatus == 4 && ($cambioPrecio == 2 || $otroSub == 2))
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-AVEFO.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm-AVEFO.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td>Cambio realizado</td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
					</tr>
					<?php
				}
				elseif($estatus == 4 && ($cambioPrecio == 0 || $otroSub == 0))
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-AVEFO.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm-AVEFO.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
					</tr>
					<?php
				}
				elseif($cambioPrecio == 1 || $otroSub == 1)
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-AVEFO.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm-AVEFO.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td>Solicitó cambio</td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
					</tr>    
					<?php
				}
				elseif($cambioPrecio == 2 || $otroSub == 2)
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-AVEFO.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm-AVEFO.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td>Cambio realizado</td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
					</tr>
					<?php
				}
				else
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-AVEFO.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm-AVEFO.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
					</tr>
					<?php
				}
			}
		}
		else
		{
			?>
			<tr>
				<td>No hay información disponible</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<?php
		}
	}
	public function viewAllProyectosClienteTotalesAVEFO($zona)
	{
		$query = "SELECT 
			SUM(DP.precio_proyecto) as precio,
			AVG((DP.costo_proyecto/DP.precio_proyecto))*100 as costo,
			AVG(DP.gasto_directo) as gasto,
			SUM(((DP.precio_proyecto*PN.porcentaje_negociacion*DP.descuento)-(((DP.costo_proyecto/DP.precio_proyecto)+DP.gasto_directo)*DP.precio_proyecto))*DP.cantidad_proyecto) AS TOTAL
			FROM T_Proyectos AS P
			INNER JOIN T_Detalle_Proyectos AS DP
			ON DP.id_proyecto = P.id_proyecto
			INNER JOIN T_Subproductos AS S
			ON S.id_subproducto = DP.id_subproducto
			INNER JOIN T_Precio_Negociacion AS PN
			ON P.id_precio_negociacion = PN.id_precio_negociacion
			INNER JOIN T_Clientes AS C
			ON C.id_cliente = P.id_cliente
			WHERE C.id_zona = :zona";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(":zona",$zona);
		$stmt->execute();
		if($stmt->rowCount()>0)
		{
			$row=$stmt->fetch(PDO::FETCH_ASSOC);
			if($zona == 7)
			{
			?>
			<tr>
				<th>Totales</th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th><?php print(number_format($row['precio'],2));?></th>
				<th><?php print(round($row['costo'],2).'%');?></th>
				<th><?php print(round($row['gasto'],2).'%');?></th>
				<th><?php print(number_format($row['TOTAL'],2));?></th>
			</tr>
			<?php
			}
			else
			{
			?>
			<tr>
				<th>Totales</th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th><?php print(number_format($row['precio'],2));?></th>
				<th><?php print(round($row['costo'],2).'%');?></th>
				<th><?php print(number_format($row['TOTAL'],2));?></th>
			</tr>
			<?php
			}
		}
		else
		{
			?>
			<tr>
				<td>No hay informacion disponible</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<?php
		}
	}
	public function viewAllProyectosProspectoAVEFO($zona)
	{
		$query = "SELECT P.id_proyecto,
			C.nombre_prospecto,
			P.id_estatusproyecto,
			E.estatus,
			P.clave_proyecto,
			P.nombre_proyecto,
			P.fecha_creacion,
			P.fecha_realizacion,
			P.year_proyecto,
			P.otro_sub,
			P.cambio_precio,
			IF(P.otro_sub = 1,'Por autorizar cambio subproducto OTRO',IF(P.otro_sub = 2,'Cambio subproducto OTRO Autorizado','No hay cambio')) AS estatus_cambio_otro,
			IF(P.cambio_precio = 1,'Por autorizar cambio de precio',IF(P.cambio_precio = 2,'Cambio de precio Autorizado','No hay cambio')) AS estatus_cambio_precio,
			SUM(DP.precio_proyecto) AS Ingresos,
			SUM(DP.costo_proyecto) AS Costos,
			SUM(DP.cantidad_proyecto) AS Cantidad,
			SUM(DP.precio_proyecto*DP.cantidad_proyecto) AS TOTAL,
			SUM((DP.precio_proyecto-((DP.costo_proyecto+DP.gasto_directo)*DP.precio_proyecto))*DP.cantidad_proyecto) AS Margen
			FROM T_Proyectos AS P
			INNER JOIN T_Detalle_Proyectos AS DP
			ON DP.id_proyecto = P.id_proyecto
			INNER JOIN T_Subproductos AS S
			ON S.id_subproducto = DP.id_subproducto
			INNER JOIN T_Precio_Negociacion AS PN
			ON P.id_precio_negociacion = PN.id_precio_negociacion
			INNER JOIN T_EstatusProyecto AS E
			ON P.id_estatusproyecto = E.id_estatusproyecto
			INNER JOIN T_Prospectos as C
			ON P.id_prospecto = C.id_prospecto
			WHERE C.id_zona = :zona
			GROUP BY P.id_cliente, P.id_proyecto";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(":zona",$zona);
		$stmt->execute();
		if($stmt->rowCount()>0)
		{
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				$estatus = intval($row['id_estatusproyecto']);
				$cambioPrecio = intval($row['cambio_precio']);
				$otroSub = intval($row['otro_sub']);
				if($estatus == 6)
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-AVEFO.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td></td>
						<td></td>
						<td><?php print($row['nombre_prospecto']); ?></td>
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
					</tr>    
					<?php
				}
				elseif($estatus == 4 && ($cambioPrecio == 1 || $otroSub == 1))
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-AVEFO.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm-AVEFO.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td>Solicitó cambio</td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
					</tr>
					<?php
				}
				elseif($estatus == 4 && ($cambioPrecio == 2 || $otroSub == 2))
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-AVEFO.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm-AVEFO.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td>Cambio realizado</td>
						<td><?php print($row['nombre_prospecto']); ?></td>
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
					</tr>
					<?php
				}
				elseif($estatus == 4 && ($cambioPrecio == 0 || $otroSub == 0))
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-AVEFO.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm-AVEFO.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td></td>
						<td><?php print($row['nombre_prospecto']); ?></td>
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
					</tr>
					<?php
				}
				elseif($cambioPrecio == 1 || $otroSub == 1)
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-AVEFO.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm-AVEFO.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td>Solicitó cambio</td>
						<td><?php print($row['nombre_prospecto']); ?></td>
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
					</tr>    
					<?php
				}
				elseif($cambioPrecio == 2 || $otroSub == 2)
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-AVEFO.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm-AVEFO.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td>Cambio realizado</td>
						<td><?php print($row['nombre_prospecto']); ?></td>
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
					</tr>
					<?php
				}
				else
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewProyectos-AVEFO.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td style="text-align: center"><a href="updateProyectoClienteForm-AVEFO.php?zona=<?php print($zona); ?>&proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td></td>
						<td><?php print($row['nombre_prospecto']); ?></td>
						<td><?php print($row['estatus']); ?></td>
						<td><?php print($row['clave_proyecto']); ?></td>
						<td><?php print($row['nombre_proyecto']); ?></td>
						<td><?php print($row['fecha_creacion']); ?></td>
						<td><?php print($row['fecha_realizacion']); ?></td>
						<td><?php print($row['year_proyecto']); ?></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td><?php print($row['Cantidad']); ?></td>
						<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
					</tr>
					<?php
				}
			}
		}
		else
		{
			?>
			<tr>
				<td>No hay información disponible</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<?php
		}
	}
	public function viewAllProyectosProspectoTotalesAVEFO($zona)
	{
		$query = "SELECT 
			SUM(DP.precio_proyecto) as precio,
			AVG((DP.costo_proyecto/DP.precio_proyecto))*100 as costo,
			AVG(DP.gasto_directo) as gasto,
			SUM(((DP.precio_proyecto*PN.porcentaje_negociacion*DP.descuento)-(((DP.costo_proyecto/DP.precio_proyecto)+DP.gasto_directo)*DP.precio_proyecto))*DP.cantidad_proyecto) AS TOTAL
			FROM T_Proyectos AS P
			INNER JOIN T_Detalle_Proyectos AS DP
			ON DP.id_proyecto = P.id_proyecto
			INNER JOIN T_Subproductos AS S
			ON S.id_subproducto = DP.id_subproducto
			INNER JOIN T_Precio_Negociacion AS PN
			ON P.id_precio_negociacion = PN.id_precio_negociacion
			INNER JOIN T_Prospectos AS C
			ON C.id_prospecto = P.id_prospecto
			WHERE C.id_zona = :zona";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(":zona",$zona);
		$stmt->execute();
		if($stmt->rowCount()>0)
		{
			$row=$stmt->fetch(PDO::FETCH_ASSOC);
			if($zona == 7)
			{
			?>
			<tr>
				<th>Totales</th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th><?php print(number_format($row['precio'],2));?></th>
				<th><?php print(round($row['costo'],2).'%');?></th>
				<th><?php print(round($row['gasto'],2).'%');?></th>
				<th><?php print(number_format($row['TOTAL'],2));?></th>
			</tr>
			<?php
			}
			else
			{
			?>
			<tr>
				<th>Totales</th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th><?php print(number_format($row['precio'],2));?></th>
				<th><?php print(round($row['costo'],2).'%');?></th>
				<th><?php print(number_format($row['TOTAL'],2));?></th>
			</tr>
			<?php
			}
		}
		else
		{
			?>
			<tr>
				<td>No hay informacion disponible</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<?php
		}
	}
	
	//VISTA CORPORATIVA
	public function viewAllProyectosClienteAVEFOC()
	{
		$query = "SELECT P.id_proyecto,
			C.nombre_cliente,
			C.id_zona,
			P.id_estatusproyecto,
			E.estatus,
			P.clave_proyecto,
			P.nombre_proyecto,
			P.fecha_creacion,
			P.fecha_realizacion,
			P.year_proyecto,
			P.otro_sub,
			P.cambio_precio,
			IF(P.otro_sub = 1,'Por autorizar cambio subproducto OTRO',IF(P.otro_sub = 2,'Cambio subproducto OTRO Autorizado','No hay cambio')) AS estatus_cambio_otro,
			IF(P.cambio_precio = 1,'Por autorizar cambio de precio',IF(P.cambio_precio = 2,'Cambio de precio Autorizado','No hay cambio')) AS estatus_cambio_precio,
			SUM(DP.precio_proyecto) AS Ingresos,
			SUM(DP.costo_proyecto) AS Costos,
			SUM(DP.gasto_directo) AS Gasto,
			SUM(DP.cantidad_proyecto) AS Cantidad,
			SUM(DP.precio_proyecto*DP.cantidad_proyecto) AS TOTAL,
			SUM((DP.precio_proyecto-((DP.costo_proyecto+DP.gasto_directo)*DP.precio_proyecto))*DP.cantidad_proyecto) AS Margen
			FROM T_Proyectos AS P
			INNER JOIN T_Detalle_Proyectos AS DP
			ON DP.id_proyecto = P.id_proyecto
			INNER JOIN T_Subproductos AS S
			ON S.id_subproducto = DP.id_subproducto
			INNER JOIN T_Precio_Negociacion AS PN
			ON P.id_precio_negociacion = PN.id_precio_negociacion
			INNER JOIN T_EstatusProyecto AS E
			ON P.id_estatusproyecto = E.id_estatusproyecto
			INNER JOIN T_Clientes as C
			ON P.id_cliente = C.id_cliente
			WHERE NOT C.id_zona = 4
			GROUP BY P.id_cliente, P.id_proyecto";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		if($stmt->rowCount()>0)
		{
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				?>
				<tr>
					<td style="text-align: center"><a href="viewProyectos.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
					<td></td>
					<td><?php print($row['id_zona']); ?></td>
					<td><?php print($row['nombre_cliente']); ?></td>
					<td><?php print($row['estatus']); ?></td>
					<td><?php print($row['clave_proyecto']); ?></td>
					<td><?php print($row['nombre_proyecto']); ?></td>
					<td><?php print($row['fecha_creacion']); ?></td>
					<td><?php print($row['fecha_realizacion']); ?></td>
					<td><?php print($row['year_proyecto']); ?></td>
					<td><?php print(number_format($row['Ingresos'],2));?></td>
					<td><?php print(round($row['Costos'],2).'%');?></td>
					<td><?php print(round($row['Gasto'],2).'%');?></td>
					<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
					<td>$<?php print(number_format($row['Margen'],2)); ?></td>
				</tr>    
				<?php
			}
		}
		else
		{
			?>
			<tr>
				<td>No hay informacion disponible</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<?php
		}
	}
	public function viewAllProyectosClienteTotalesAVEFOC()
	{
		$query = "SELECT 
			SUM(DP.precio_proyecto) as precio,
			AVG((DP.costo_proyecto/DP.precio_proyecto)) as costo,
			AVG(DP.gasto_directo) as gasto,
			SUM(((DP.precio_proyecto*PN.porcentaje_negociacion*DP.descuento)-(((DP.costo_proyecto/DP.precio_proyecto)+DP.gasto_directo)*DP.precio_proyecto))*DP.cantidad_proyecto) AS TOTAL
			FROM T_Proyectos AS P
			INNER JOIN T_Detalle_Proyectos AS DP
			ON DP.id_proyecto = P.id_proyecto
			INNER JOIN T_Subproductos AS S
			ON S.id_subproducto = DP.id_subproducto
			INNER JOIN T_Precio_Negociacion AS PN
			ON P.id_precio_negociacion = PN.id_precio_negociacion
			INNER JOIN T_Clientes AS C
			ON C.id_cliente = P.id_cliente
			WHERE NOT C.id_zona = 4";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		if($stmt->rowCount()>0)
		{
			$row=$stmt->fetch(PDO::FETCH_ASSOC);
			?>
			<tr>
				<th>Totales</th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th><?php print(number_format($row['precio'],2));?></th>
				<th><?php print(round($row['costo'],2).'%');?></th>
				<th><?php print(round($row['gasto'],2).'%');?></th>
				<th><?php print(number_format($row['TOTAL'],2));?></th>
			</tr>
			<?php
		}
		else
		{
			?>
			<tr>
				<td>No hay informacion disponible</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<?php
		}
	}
	public function viewAllProyectosProspectoAVEFOC()
	{
		$query = "SELECT P.id_proyecto,
			C.nombre_prospecto,
			C.id_zona,
			P.id_estatusproyecto,
			E.estatus,
			P.clave_proyecto,
			P.nombre_proyecto,
			P.fecha_creacion,
			P.fecha_realizacion,
			P.year_proyecto,
			P.otro_sub,
			P.cambio_precio,
			IF(P.otro_sub = 1,'Por autorizar cambio subproducto OTRO',IF(P.otro_sub = 2,'Cambio subproducto OTRO Autorizado','No hay cambio')) AS estatus_cambio_otro,
			IF(P.cambio_precio = 1,'Por autorizar cambio de precio',IF(P.cambio_precio = 2,'Cambio de precio Autorizado','No hay cambio')) AS estatus_cambio_precio,
			SUM(DP.precio_proyecto) AS Ingresos,
			SUM(DP.costo_proyecto) AS Costos,
			SUM(DP.gasto_directo) AS Gasto,
			SUM(DP.cantidad_proyecto) AS Cantidad,
			SUM(DP.precio_proyecto*DP.cantidad_proyecto) AS TOTAL,
			SUM((DP.precio_proyecto-((DP.costo_proyecto+DP.gasto_directo)*DP.precio_proyecto))*DP.cantidad_proyecto) AS Margen
			FROM T_Proyectos AS P
			INNER JOIN T_Detalle_Proyectos AS DP
			ON DP.id_proyecto = P.id_proyecto
			INNER JOIN T_Subproductos AS S
			ON S.id_subproducto = DP.id_subproducto
			INNER JOIN T_Precio_Negociacion AS PN
			ON P.id_precio_negociacion = PN.id_precio_negociacion
			INNER JOIN T_EstatusProyecto AS E
			ON P.id_estatusproyecto = E.id_estatusproyecto
			INNER JOIN T_Prospectos as C
			ON P.id_prospecto = C.id_prospecto
			WHERE NOT C.id_zona = 4
			GROUP BY P.id_prospecto, P.id_proyecto";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		if($stmt->rowCount()>0)
		{
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				?>
				<tr>
					<td style="text-align: center"><a href="viewProyectos.php?proyecto_cliente=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
					<td></td>
					<td><?php print($row['id_zona']); ?></td>
					<td><?php print($row['nombre_prospecto']); ?></td>
					<td><?php print($row['estatus']); ?></td>
					<td><?php print($row['clave_proyecto']); ?></td>
					<td><?php print($row['nombre_proyecto']); ?></td>
					<td><?php print($row['fecha_creacion']); ?></td>
					<td><?php print($row['fecha_realizacion']); ?></td>
					<td><?php print($row['year_proyecto']); ?></td>
					<td><?php print(number_format($row['Ingresos'],2));?></td>
					<td><?php print(round($row['Costos'],2).'%');?></td>
					<td><?php print(round($row['Gasto'],2).'%');?></td>
					<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
					<td>$<?php print(number_format($row['Margen'],2)); ?></td>
				</tr>    
				<?php
			}
		}
		else
		{
			?>
			<tr>
				<td>No hay informacion disponible</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<?php
		}
	}
	public function viewAllProyectosProspectoTotalesAVEFOC()
	{
		$query = "SELECT 
			SUM(DP.precio_proyecto) as precio,
			AVG((DP.costo_proyecto/DP.precio_proyecto)) as costo,
			AVG(DP.gasto_directo) as gasto,
			SUM(((DP.precio_proyecto*PN.porcentaje_negociacion*DP.descuento)-(((DP.costo_proyecto/DP.precio_proyecto)+DP.gasto_directo)*DP.precio_proyecto))*DP.cantidad_proyecto) AS TOTAL
			FROM T_Proyectos AS P
			INNER JOIN T_Detalle_Proyectos AS DP
			ON DP.id_proyecto = P.id_proyecto
			INNER JOIN T_Subproductos AS S
			ON S.id_subproducto = DP.id_subproducto
			INNER JOIN T_Precio_Negociacion AS PN
			ON P.id_precio_negociacion = PN.id_precio_negociacion
			INNER JOIN T_Prospectos AS C
			ON C.id_prospecto = P.id_prospecto
			WHERE NOT C.id_zona = 4";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		if($stmt->rowCount()>0)
		{
			$row=$stmt->fetch(PDO::FETCH_ASSOC);
			?>
			<tr>
				<th>Totales</th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th><?php print(number_format($row['precio'],2));?></th>
				<th><?php print(round($row['costo'],2).'%');?></th>
				<th><?php print(round($row['gasto'],2).'%');?></th>
				<th><?php print(number_format($row['TOTAL'],2));?></th>
			</tr>
			<?php
		}
		else
		{
			?>
			<tr>
				<td>No hay informacion disponible</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<?php
		}
	}
	
	
	
	//VIEW PROYECTOS
	//TERRITORIO
	public function viewAllProyectosDirector($territorio)
	{
		$query = "SELECT P.id_proyecto,
			Pr.nombre_prospecto,
			Z.zona,
			E.estatus,
			P.clave_proyecto,
			P.nombre_proyecto,
			P.fecha_creacion,
			P.fecha_realizacion,
			P.version_proyecto,
			P.year_proyecto,
			PN.descr_precio_negociacion,
			SUM(((DP.cantidad_proyecto * (S.precio_subproducto * PN.porcentaje_negociacion)) * DP.descuento)) AS TOTAL
			FROM T_Proyectos AS P
			INNER JOIN T_Detalle_Proyectos AS DP
			ON DP.id_proyecto = P.id_proyecto
			INNER JOIN T_Subproductos AS S
			ON S.id_subproducto = DP.id_subproducto
			INNER JOIN T_EstatusProyecto AS E
			ON P.id_estatusproyecto = E.id_estatusproyecto
			INNER JOIN T_Prospectos as Pr
			ON P.id_prospecto = Pr.id_prospecto
			INNER JOIN T_Precio_Negociacion AS PN
			ON P.id_precio_negociacion = PN.id_precio_negociacion
			INNER JOIN T_TerritorioZona AS TZ
			ON TZ.id_zona = Pr.id_zona
			INNER JOIN T_Zona AS Z
			ON Z.id_zona = Pr.id_zona
			WHERE TZ.id_territorio = :territorio
			GROUP BY P.id_prospecto, P.id_proyecto";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(":territorio",$territorio);
		$stmt->execute();
		if($stmt->rowCount()>0)
		{
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				?>
				<tr>
					<td style="text-align: center"><a href="viewProyectos-Director.php?proyecto_prospecto=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
					<td><?php print($row['nombre_prospecto']); ?></td>
					<td><?php print($row['zona']); ?></td>
					<td><?php print($row['estatus']); ?></td>
					<td><?php print($row['clave_proyecto']); ?></td>
					<td><?php print($row['nombre_proyecto']); ?></td>
					<td><?php print($row['version_proyecto']); ?></td>
					<td><?php print($row['fecha_creacion']); ?></td>
					<td><?php print($row['fecha_realizacion']); ?></td>
					<td><?php print($row['year_proyecto']); ?></td>
					<td><?php print($row['descr_precio_negociacion']); ?></td>
					<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
				</tr>
				<?php
			}
		}
		else
		{
			?>
			<tr>
				<td>No hay información disponible</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<?php
		}
	}
	//ZONA
	public function viewAllProyectos($zona)
	{
		$query = "SELECT P.id_proyecto,
			Pr.nombre_prospecto,
			E.estatus,
			P.clave_proyecto,
			P.nombre_proyecto,
			P.fecha_creacion,
			P.fecha_realizacion,
			P.version_proyecto,
			P.year_proyecto,
			PN.descr_precio_negociacion,
			SUM(((DP.cantidad_proyecto * (S.precio_subproducto * PN.porcentaje_negociacion)) * DP.descuento)) AS TOTAL
			FROM T_Proyectos AS P
			INNER JOIN T_Detalle_Proyectos AS DP
			ON DP.id_proyecto = P.id_proyecto
			INNER JOIN T_Subproductos AS S
			ON S.id_subproducto = DP.id_subproducto
			INNER JOIN T_EstatusProyecto AS E
			ON P.id_estatusproyecto = E.id_estatusproyecto
			INNER JOIN T_Prospectos as Pr
			ON P.id_prospecto = Pr.id_prospecto
			INNER JOIN T_Precio_Negociacion AS PN
			ON P.id_precio_negociacion = PN.id_precio_negociacion
			WHERE Pr.id_zona = :zona
			GROUP BY P.id_prospecto, P.id_proyecto";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(":zona",$zona);
		$stmt->execute();
		if($stmt->rowCount()>0)
		{
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				?>
				<tr>
					<td style="text-align: center"><a href="viewProyectos.php?proyecto_prospecto=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
					<td><?php print($row['nombre_prospecto']); ?></td>
					<td><?php print($row['estatus']); ?></td>
					<td><?php print($row['clave_proyecto']); ?></td>
					<td><?php print($row['nombre_proyecto']); ?></td>
					<td><?php print($row['version_proyecto']); ?></td>
					<td><?php print($row['fecha_creacion']); ?></td>
					<td><?php print($row['fecha_realizacion']); ?></td>
					<td><?php print($row['year_proyecto']); ?></td>
					<td><?php print($row['descr_precio_negociacion']); ?></td>
					<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
				</tr>
				<?php
			}
		}
		else
		{
			?>
			<tr>
				<td>No hay información disponible</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<?php
		}
	}
	//SUBDIRECTOR
	public function viewAllProyectosSubdirector($zona)
	{
		$query = "SELECT P.id_proyecto,
			Pr.nombre_prospecto,
			E.estatus,
			P.clave_proyecto,
			P.nombre_proyecto,
			P.fecha_creacion,
			P.fecha_realizacion,
			P.version_proyecto,
			P.year_proyecto,
			PN.descr_precio_negociacion,
			SUM(((DP.cantidad_proyecto * (S.precio_subproducto * PN.porcentaje_negociacion)) * DP.descuento)) AS TOTAL
			FROM T_Proyectos AS P
			INNER JOIN T_Detalle_Proyectos AS DP
			ON DP.id_proyecto = P.id_proyecto
			INNER JOIN T_Subproductos AS S
			ON S.id_subproducto = DP.id_subproducto
			INNER JOIN T_EstatusProyecto AS E
			ON P.id_estatusproyecto = E.id_estatusproyecto
			INNER JOIN T_Prospectos as Pr
			ON P.id_prospecto = Pr.id_prospecto
			INNER JOIN T_Precio_Negociacion AS PN
			ON P.id_precio_negociacion = PN.id_precio_negociacion
			WHERE Pr.id_zona = :zona
			GROUP BY P.id_prospecto, P.id_proyecto";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(":zona",$zona);
		$stmt->execute();
		if($stmt->rowCount()>0)
		{
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				?>
				<tr>
					<td style="text-align: center"><a href="viewProyectos-Subdirector.php?proyecto_prospecto=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
					<td><?php print($row['nombre_prospecto']); ?></td>
					<td><?php print($row['estatus']); ?></td>
					<td><?php print($row['clave_proyecto']); ?></td>
					<td><?php print($row['nombre_proyecto']); ?></td>
					<td><?php print($row['version_proyecto']); ?></td>
					<td><?php print($row['fecha_creacion']); ?></td>
					<td><?php print($row['fecha_realizacion']); ?></td>
					<td><?php print($row['year_proyecto']); ?></td>
					<td><?php print($row['descr_precio_negociacion']); ?></td>
					<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
				</tr>
				<?php
			}
		}
		else
		{
			?>
			<tr>
				<td>No hay información disponible</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<?php
		}
	}
	public function viewAllProyectosTerritorioSubdirector($territorio)
	{
		$query = "SELECT P.id_proyecto,
			Pr.nombre_prospecto,
			Z.zona,
			E.estatus,
			P.clave_proyecto,
			P.nombre_proyecto,
			P.fecha_creacion,
			P.fecha_realizacion,
			P.version_proyecto,
			P.year_proyecto,
			PN.descr_precio_negociacion,
			SUM(((DP.cantidad_proyecto * (S.precio_subproducto * PN.porcentaje_negociacion)) * DP.descuento)) AS TOTAL
			FROM T_Proyectos AS P
			INNER JOIN T_Detalle_Proyectos AS DP
			ON DP.id_proyecto = P.id_proyecto
			INNER JOIN T_Subproductos AS S
			ON S.id_subproducto = DP.id_subproducto
			INNER JOIN T_EstatusProyecto AS E
			ON P.id_estatusproyecto = E.id_estatusproyecto
			INNER JOIN T_Prospectos as Pr
			ON P.id_prospecto = Pr.id_prospecto
			INNER JOIN T_Precio_Negociacion AS PN
			ON P.id_precio_negociacion = PN.id_precio_negociacion
			INNER JOIN T_TerritorioZona AS TZ
			ON TZ.id_zona = Pr.id_zona
			INNER JOIN T_Zona AS Z
			ON Z.id_zona = Pr.id_zona
			WHERE TZ.id_territorio = :territorio
			GROUP BY P.id_prospecto, P.id_proyecto";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(":territorio",$territorio);
		$stmt->execute();
		if($stmt->rowCount()>0)
		{
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				?>
				<tr>
					<td style="text-align: center"><a href="viewProyectos-Subdirector.php?proyecto_prospecto=<?php print($row['id_proyecto']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
					<td><?php print($row['nombre_prospecto']); ?></td>
					<td><?php print($row['zona']); ?></td>
					<td><?php print($row['estatus']); ?></td>
					<td><?php print($row['clave_proyecto']); ?></td>
					<td><?php print($row['nombre_proyecto']); ?></td>
					<td><?php print($row['version_proyecto']); ?></td>
					<td><?php print($row['fecha_creacion']); ?></td>
					<td><?php print($row['fecha_realizacion']); ?></td>
					<td><?php print($row['year_proyecto']); ?></td>
					<td><?php print($row['descr_precio_negociacion']); ?></td>
					<td>$<?php print(number_format($row['TOTAL'],2)); ?></td>
				</tr>
				<?php
			}
		}
		else
		{
			?>
			<tr>
				<td>No hay información disponible</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<?php
		}
	}
	
	
	//ABC - ALTAS, BAJAS Y CAMBIOS//
	//INSERT CUESTIONARIO
	public function insertCuestionario($cuenta,$fecha,$pregunta1,$pregunta2,$pregunta3,$pregunta4,$pregunta5,$pregunta6,$pregunta7,$pregunta8,$pregunta9,$pregunta10,
			$pregunta11,$pregunta12,$pregunta13,$pregunta14,$pregunta15,$pregunta16,$pregunta17,$pregunta18,$pregunta19,$pregunta20,$pregunta21)
	{
		$this->conn->beginTransaction();
		try
		{			
			$query="INSERT INTO T_Cuestionario (id_cliente,fecha_ingreso,pregunta1,pregunta2,pregunta3,pregunta4,pregunta5,pregunta6,pregunta7,pregunta8,pregunta9,pregunta10,pregunta11,pregunta12,pregunta13,pregunta14,pregunta15,pregunta16,pregunta17,pregunta18,pregunta19,pregunta20,pregunta21)
			VALUES(:cuenta,:fecha,:pregunta1,:pregunta2,:pregunta3,:pregunta4,:pregunta5,:pregunta6,:pregunta7,:pregunta8,:pregunta9,:pregunta10,:pregunta11,:pregunta12,:pregunta13,:pregunta14,:pregunta15,:pregunta16,:pregunta17,:pregunta18,:pregunta19,:pregunta20,:pregunta21)";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":cuenta",$cuenta);
			$stmt->bindparam(":fecha",$fecha);
			$stmt->bindparam(":pregunta1",$pregunta1);
			$stmt->bindparam(":pregunta2",$pregunta2);
			$stmt->bindparam(":pregunta3",$pregunta3);
			$stmt->bindparam(":pregunta4",$pregunta4);
			$stmt->bindparam(":pregunta5",$pregunta5);
			$stmt->bindparam(":pregunta6",$pregunta6);
			$stmt->bindparam(":pregunta7",$pregunta7);
			$stmt->bindparam(":pregunta8",$pregunta8);
			$stmt->bindparam(":pregunta9",$pregunta9);
			$stmt->bindparam(":pregunta10",$pregunta10);
			$stmt->bindparam(":pregunta11",$pregunta11);
			$stmt->bindparam(":pregunta12",$pregunta12);
			$stmt->bindparam(":pregunta13",$pregunta13);
			$stmt->bindparam(":pregunta14",$pregunta14);
			$stmt->bindparam(":pregunta15",$pregunta15);
			$stmt->bindparam(":pregunta16",$pregunta16);
			$stmt->bindparam(":pregunta17",$pregunta17);
			$stmt->bindparam(":pregunta18",$pregunta18);
			$stmt->bindparam(":pregunta19",$pregunta19);
			$stmt->bindparam(":pregunta20",$pregunta20);
			$stmt->bindparam(":pregunta21",$pregunta21);
			$stmt->execute();
			$this->conn->commit();
			$last_id = $this->conn->lastInsertId();
			return $last_id;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
			$this->conn->rollBack();
			return false;
		}
	}
	//UPDATE CUESTIONARIO
	public function updateCuestionario($cuestionario,$pregunta1,$pregunta2,$pregunta3,$pregunta4,$pregunta5,$pregunta6,$pregunta7,$pregunta8,$pregunta9,$pregunta10,
			$pregunta11,$pregunta12,$pregunta13,$pregunta14,$pregunta15,$pregunta16,$pregunta17,$pregunta18,$pregunta19,$pregunta20,$pregunta21)
	{
		$this->conn->beginTransaction();
		try
		{			
			$query="UPDATE T_Cuestionario SET pregunta1=:pregunta1, pregunta2=:pregunta2, pregunta3=:pregunta3, pregunta4=:pregunta4,
			pregunta5 = :pregunta5, pregunta6 = :pregunta6, pregunta7 = :pregunta7, pregunta8 = :pregunta8, pregunta9 = :pregunta9, pregunta10 = :pregunta10,
			pregunta11 = :pregunta11, pregunta12 = :pregunta12, pregunta13 = :pregunta13, pregunta14 = :pregunta14, pregunta15 = :pregunta15, pregunta16 = :pregunta16,
			pregunta17 = :pregunta17, pregunta18 = :pregunta18, pregunta19 = :pregunta19, pregunta20 = :pregunta20, pregunta21 = :pregunta21 WHERE id_cuestionario = :cuestionario";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":pregunta1",$pregunta1);
			$stmt->bindparam(":pregunta2",$pregunta2);
			$stmt->bindparam(":pregunta3",$pregunta3);
			$stmt->bindparam(":pregunta4",$pregunta4);
			$stmt->bindparam(":pregunta5",$pregunta5);
			$stmt->bindparam(":pregunta6",$pregunta6);
			$stmt->bindparam(":pregunta7",$pregunta7);
			$stmt->bindparam(":pregunta8",$pregunta8);
			$stmt->bindparam(":pregunta9",$pregunta9);
			$stmt->bindparam(":pregunta10",$pregunta10);
			$stmt->bindparam(":pregunta11",$pregunta11);
			$stmt->bindparam(":pregunta12",$pregunta12);
			$stmt->bindparam(":pregunta13",$pregunta13);
			$stmt->bindparam(":pregunta14",$pregunta14);
			$stmt->bindparam(":pregunta15",$pregunta15);
			$stmt->bindparam(":pregunta16",$pregunta16);
			$stmt->bindparam(":pregunta17",$pregunta17);
			$stmt->bindparam(":pregunta18",$pregunta18);
			$stmt->bindparam(":pregunta19",$pregunta19);
			$stmt->bindparam(":pregunta20",$pregunta20);
			$stmt->bindparam(":pregunta21",$pregunta21);
			$stmt->bindparam(":cuestionario",$cuestionario);
			$stmt->execute();
			$this->conn->commit();
			return true;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
			$this->conn->rollBack();
			return false;
		}
	}
	
	//ELIMINAR DETALLE PROYECTO
	public function deleteDetalleProyecto($detalle_eliminar)
	{
		$this->conn->beginTransaction();
		try
		{
			$query="DELETE FROM `T_Detalle_Proyectos` WHERE id_detalleproyecto = :detalle_eliminar";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":detalle_eliminar",$detalle_eliminar);
			$stmt->execute();
			$this->conn->commit();
			return true;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
			$this->conn->rollBack();
			return false;
		}
	}
	//ACTUALIZAR PROYECTO GENERAL
	//LIDER
	public function updateProyecto($proyecto,$update_realizacion,$estatus,$cambioPrecio,$otroSub)
	{
		$this->conn->beginTransaction();
		try
		{
			$query="UPDATE T_Proyectos SET fecha_realizacion = :update_realizacion, id_estatusproyecto = :estatus, cambio_precio = :cambioPrecio, otro_sub = :otroSub WHERE id_proyecto = :proyecto";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":update_realizacion",$update_realizacion);
			$stmt->bindparam(":estatus",$estatus);
			$stmt->bindparam(":cambioPrecio",$cambioPrecio);
			$stmt->bindparam(":otroSub",$otroSub);
			$stmt->bindparam(":proyecto",$proyecto);
			$stmt->execute();
			$this->conn->commit();
			return true;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
			$this->conn->rollBack();
			return false;
		}
	}
	//ACTUALIZAR PROYECTO DETALLE
	public function updateDetalleProyecto($update_tema,$update_subproducto,$update_precio,$update_precio_cambiar,$update_costo,$update_gasto,$update_cantidad,$update_descuento,$autorizacion,$detalle_aux)
	{
		$this->conn->beginTransaction();
		try
		{
			$query= "UPDATE T_Detalle_Proyectos SET 
				tema_proyecto = :update_tema, id_subproducto = :update_subproducto, precio_proyecto = :update_precio, precio_cambiar = :update_precio_cambiar, costo_proyecto = :update_costo, gasto_directo = :update_gasto, cantidad_proyecto = :update_cantidad, descuento = :update_descuento, id_autorizacion = :autorizacion
				WHERE id_detalleproyecto = :detalle_aux";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":update_tema",$update_tema);
			$stmt->bindparam(":update_subproducto",$update_subproducto);
			$stmt->bindparam(":update_precio",$update_precio);
			$stmt->bindparam(":update_precio_cambiar",$update_precio_cambiar);
			$stmt->bindparam(":update_costo",$update_costo);
			$stmt->bindparam(":update_gasto",$update_gasto);
			$stmt->bindparam(":update_cantidad",$update_cantidad);
			$stmt->bindparam(":update_descuento",$update_descuento);
			$stmt->bindparam(":autorizacion",$autorizacion);
			$stmt->bindparam(":detalle_aux",$detalle_aux);
			$stmt->execute();
			$this->conn->commit();
			return true;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
			$this->conn->rollBack();
			return false;
		}
	}
	//INSERTAR NUEVO DETALLE PROYECTO EXISTENTE
	public function insertExistingDetalleProyecto($proyecto,$subproducto,$cantidad)
	{
		$this->conn->beginTransaction();
		try
		{			
			if(empty($subproducto))
			{
				$this->conn->commit();
				return true;
			}
			$query="INSERT INTO T_Detalle_Proyectos (id_proyecto,id_subproducto,cantidad_proyecto)
			VALUES(:proyecto,:subproducto,:cantidad)";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":subproducto",$subproducto);
			$stmt->bindparam(":cantidad",$cantidad);
			$stmt->bindparam(":proyecto",$proyecto);
			$stmt->execute();
			$this->conn->commit();
			return true;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
			$this->conn->rollBack();
			return false;
		}
	}
	//INSERTAR NUEVO PROYECYTO CLIENTE
	public function insertProyectoCliente($cuenta,$estatus,$clave,$nombre,$fecha_creacion,$year,$version,$precio_negociacion,$inicio,$fundamentacion_actual_auxiliar,$cambio_precio,$otro_sub)
	{
		$this->conn->beginTransaction();
		try
		{
			$stmt = $this->conn->prepare("INSERT INTO T_Proyectos (id_cliente,id_estatusproyecto,clave_proyecto,nombre_proyecto,fecha_creacion,
				year_proyecto,version_proyecto,id_precio_negociacion,fecha_realizacion,cambio_precio,otro_sub) 
				VALUES (:cuenta,:estatus,:clave,:nombre,:fecha_creacion,:year,:version,:precio_negociacion,:inicio,:cambio_precio,:otro_sub)");
			$stmt->bindparam(":cuenta",$cuenta);
			$stmt->bindparam(":estatus",$estatus);
			$stmt->bindparam(":clave",$clave);
			$stmt->bindparam(":nombre",$nombre);
			$stmt->bindparam(":fecha_creacion",$fecha_creacion);
			$stmt->bindparam(":year",$year);
			$stmt->bindparam(":version",$version);
			$stmt->bindparam(":precio_negociacion",$precio_negociacion);
			$stmt->bindparam(":inicio",$inicio);
			$stmt->bindparam(":cambio_precio",$cambio_precio);
			$stmt->bindparam(":otro_sub",$otro_sub);
			$stmt->execute();
			$last_id = $this->conn->lastInsertId();
			
			$stmt = $this->conn->prepare("INSERT INTO T_Fundamentacion_Proyecto_Cliente (id_fundamentacion,id_proyecto) 
			VALUES (:fundamentacion_actual_auxiliar,:last_id)");
			$stmt->bindparam(":fundamentacion_actual_auxiliar",$fundamentacion_actual_auxiliar);
			$stmt->bindparam(":last_id",$last_id);
			$stmt->execute();
			
			$stmt2 = $this->conn->prepare("UPDATE T_FundamentacionCliente SET estatus_fundamentacion = 1 WHERE id_fundamentacion = :fundamentacion_actual_auxiliar");
			$stmt2->bindparam(":fundamentacion_actual_auxiliar",$fundamentacion_actual_auxiliar);
			$stmt2->execute();
			
			$this->conn->commit();
			return $last_id;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
			$this->conn->rollBack();
			return false;
		}
	}
	//INSERTAR NUEVO DETALLE PROYECTO CLIENTE
	public function insertProyectoClienteDetalle($last_id,$tema,$subproducto,$precio,$precio_cambiar,$costo,$gasto,$cantidad,$porcentaje,$autorizacion)
	{
		$this->conn->beginTransaction();
		try
		{
			$stmt = $this->conn->prepare("INSERT INTO T_Detalle_Proyectos (id_proyecto,tema_proyecto,id_subproducto,precio_proyecto,precio_cambiar,costo_proyecto,gasto_directo,cantidad_proyecto,descuento,id_autorizacion) 
				VALUES (:last_id,:tema,:subproducto,:precio,:precio_cambiar,:costo,:gasto,:cantidad,:porcentaje,:autorizacion)");
			$stmt->bindparam(":last_id",$last_id);
			$stmt->bindparam(":tema",$tema);
			$stmt->bindparam(":subproducto",$subproducto);
			$stmt->bindparam(":precio",$precio);
			$stmt->bindparam(":precio_cambiar",$precio_cambiar);
			$stmt->bindparam(":costo",$costo);
			$stmt->bindparam(":gasto",$gasto);
			$stmt->bindparam(":cantidad",$cantidad);
			$stmt->bindparam(":porcentaje",$porcentaje);
			$stmt->bindparam(":autorizacion",$autorizacion);
			$stmt->execute();
			
			$this->conn->commit();
			return true;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
			$this->conn->rollBack();
			return false;
		}
	}
	//INSERTAR NUEVO DETALLE PROYECTO CLIENTE SUBPARTES
	public function insertProyectoClienteDetalleSubparte($detalleProyecto,$subparte,$costoHora,$horas)
	{
		$this->conn->beginTransaction();
		try
		{
			$stmt = $this->conn->prepare("INSERT INTO T_Detalle_Proyectos_Subpartes (id_detalleproyecto,id_subproducto_subparte,horas,costo_hora) 
				VALUES (:detalleProyecto,:subparte,:costoHora,:horas)");
			$stmt->bindparam(":detalleProyecto",$detalleProyecto);
			$stmt->bindparam(":subparte",$subparte);
			$stmt->bindparam(":costoHora",$costoHora);
			$stmt->bindparam(":horas",$horas);
			$stmt->execute();
			
			$this->conn->commit();
			return true;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
			$this->conn->rollBack();
			return false;
		}
	}
	
	//INSERTAR NUEVO PROYECTO PROSPECTO
	public function insertProyectoProspecto($cuenta,$estatus,$clave,$nombre,$fecha_creacion,$year,$version,$precio_negociacion,$inicio,$fundamentacion_actual_auxiliar,$cambio_precio,$otro_sub)
	{
		$this->conn->beginTransaction();
		try
		{
			$stmt = $this->conn->prepare("INSERT INTO T_Proyectos (id_prospecto,id_estatusproyecto,clave_proyecto,nombre_proyecto,fecha_creacion,
				year_proyecto,version_proyecto,id_precio_negociacion,fecha_realizacion,cambio_precio,otro_sub) 
				VALUES (:cuenta,:estatus,:clave,:nombre,:fecha_creacion,:year,:version,:precio_negociacion,:inicio,:cambio_precio,:otro_sub)");
			$stmt->bindparam(":cuenta",$cuenta);
			$stmt->bindparam(":estatus",$estatus);
			$stmt->bindparam(":clave",$clave);
			$stmt->bindparam(":nombre",$nombre);
			$stmt->bindparam(":fecha_creacion",$fecha_creacion);
			$stmt->bindparam(":year",$year);
			$stmt->bindparam(":version",$version);
			$stmt->bindparam(":precio_negociacion",$precio_negociacion);
			$stmt->bindparam(":inicio",$inicio);
			$stmt->bindparam(":cambio_precio",$cambio_precio);
			$stmt->bindparam(":otro_sub",$otro_sub);
			$stmt->execute();
			$last_id = $this->conn->lastInsertId();
			
			$stmt = $this->conn->prepare("INSERT INTO T_Fundamentacion_Proyecto (id_fundamentacion,id_proyecto) 
			VALUES (:fundamentacion_actual_auxiliar,:last_id)");
			$stmt->bindparam(":fundamentacion_actual_auxiliar",$fundamentacion_actual_auxiliar);
			$stmt->bindparam(":last_id",$last_id);
			$stmt->execute();
			
			$stmt = $this->conn->prepare("UPDATE T_Seguimiento SET id_accion_resultado = 13, fecha_seguimiento=:fecha_creacion WHERE id_prospecto = :cuenta");
			$stmt->bindparam(":fecha_creacion",$fecha_creacion);
			$stmt->bindparam(":cuenta",$cuenta);
			$stmt->execute();
			
			$this->conn->commit();
			return $last_id;
			
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
			$this->conn->rollBack();
			return false;
		}
	}
	public function insertProyectoProspectoDetalle($last_id,$tema,$subproducto,$precio,$precio_cambiar,$costo,$gasto,$cantidad,$porcentaje,$autorizacion)
	{
		$this->conn->beginTransaction();
		try
		{
			$stmt = $this->conn->prepare("INSERT INTO T_Detalle_Proyectos (id_proyecto,tema_proyecto,id_subproducto,precio_proyecto,precio_cambiar,costo_proyecto,gasto_directo,cantidad_proyecto,descuento,id_autorizacion) 
				VALUES (:last_id,:tema,:subproducto,:precio,:precio_cambiar,:costo,:gasto,:cantidad,:porcentaje,:autorizacion)");
			$stmt->bindparam(":last_id",$last_id);
			$stmt->bindparam(":tema",$tema);
			$stmt->bindparam(":subproducto",$subproducto);
			$stmt->bindparam(":precio",$precio);
			$stmt->bindparam(":precio_cambiar",$precio_cambiar);
			$stmt->bindparam(":costo",$costo);
			$stmt->bindparam(":gasto",$gasto);
			$stmt->bindparam(":cantidad",$cantidad);
			$stmt->bindparam(":porcentaje",$porcentaje);
			$stmt->bindparam(":autorizacion",$autorizacion);
			$stmt->execute();
			
			$this->conn->commit();
			return true;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
			$this->conn->rollBack();
			return false;
		}
	}
	
	public function showProyectos($cuenta, $rango, $proyecto)
	{
		$stmt = $this->conn->prepare("SELECT * FROM  T_Detalle_Proyectos AS DP
			INNER JOIN T_Subproductos AS Sub
			ON DP.id_subproducto = Sub.id_subproducto
			INNER JOIN T_Productos AS Prod
			ON Sub.id_producto = Prod.id_producto
			INNER JOIN T_FamiliaProductos AS Fam
			ON Prod.id_familiaproducto = Fam.id_familiaproducto
			WHERE DP.id_proyecto = :proyecto");
		$stmt->execute(array(':proyecto'=>$proyecto));
		
		$stmt2 = $this->conn->prepare("SELECT * FROM T_Proyectos AS P
			INNER JOIN T_Prospectos AS Pr
			ON P.id_prospecto = Pr.id_prospecto
			INNER JOIN T_EstatusProyecto AS EP
			ON EP.id_estatusproyecto = P.id_estatusproyecto
			WHERE P.id_proyecto = :proyecto");
		$stmt2->execute(array(':proyecto'=>$proyecto));
		
		$contador = 1;
		$row2=$stmt2->fetch(PDO::FETCH_ASSOC);
		?>
		<div class="example-box-wrapper">
			<div class="form-group">
				<label for="" class="col-sm-2 control-label">Nombre del prospecto</label>
				<div class="col-sm-4">
					<div class="input-prepend input-group">
						<select id="prospecto" name="prospecto" class="form-control" readonly>
							<option selected value="<?php print($row2['id_prospecto']);?>"><?php print($row2['nombre_prospecto']);?></option>
						</select>
					</div>
				</div>
				<label for="" class="col-sm-2 control-label">Fecha de creacion</label>
				<div class="col-sm-4">
					<div class="input-prepend input-group">
						<span class="add-on input-group-addon">
							<i class="glyph-icon icon-calendar"></i>
						</span>
						<input id="fecha_creacion" name="fecha_creacion" type="text" class="form-control" value="<?php echo date("d-m-Y");?>" readonly>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label for="" class="col-sm-2 control-label">Estatus del proyecto</label>
				<div class="col-sm-4">
					<div class="input-prepend input-group">
						<select id="estatus" name="estatus" class="form-control" readonly>
							<option value="<?php print($row2['id_estatusproyecto']);?>" selected><?php print($row2['estatus']);?></option>
						</select>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label for="" class="col-sm-2 control-label">Clave del proyecto</label>
				<div class="col-sm-4">
					<div class="input-prepend input-group">
						<input id="clave" name="clave" type="text" class="form-control" readonly value="<?php print($row2['clave_proyecto']);?>">
					</div>
				</div>
				<label for="" class="col-sm-2 control-label">Nombre del proyecto</label>
				<div class="col-sm-4">
					<div class="input-prepend input-group">
						<input id="nombre" name="nombre" type="text" class="form-control" readonly value="<?php print($row2['nombre_proyecto']);?>" placeholder="">
					</div>
				</div>
			</div>

			<div class="form-group">
				<label for="" class="col-sm-2 control-label">Fecha de presentacion</label>
				<div class="col-sm-4">
					<div class="input-prepend input-group">
						<input type="text" class="form-control" readonly value="<?php print($rango);?>"id="rango_fechas" name="rango_fechas" placeholder="">
					</div>
				</div>
				<label for="" class="col-sm-2 control-label">Fecha de realizacion</label>
				<div class="col-sm-4">
					<div class="input-prepend input-group">
						<input type="text" class="form-control" id="update_fecha_realizacion" name="update_fecha_realizacion" placeholder="" value="<?php print($row2['fecha_realizacion']);?>">
					</div>
				</div>
			</div>

		</div>
	</div>
	</div>
	<div class="panel">
		<div class="panel-body">
			<h3 class="title-hero">
				Datos especificos del proyecto
			</h3>
			<div class="example-box-wrapper">
				<table form="prospecto" id="update_table" name="update_table" class="table">
					<thead>
						<tr>
							<th align="center"><span><i rowspan="2"  class="glyphicon glyphicon-remove-circle"></i></span></th>
							<th>Familia de productos*</th>
							<th>Productos*</th>
							<th>Subproductos*</th>
							<th>Precio unitario</th>
							<th>Costo unitario</th>
							<th>Cantidad*</th>
							<th>Total</th>
						</tr>
					</thead>
					<tbody>
		<?php
		while($row=$stmt->fetch(PDO::FETCH_ASSOC))
		{
			$id_detalles = [];
			$id_detalles[$contador] = $row['id_detalleproyecto'];
			$variable_detalle = 'modificar'.$contador;
			$variable_eliminar = 'eliminar'.$contador;
			$variable_familia = 'update_familia'.$contador;
			$variable_producto = 'update_producto'.$contador;
			$variable_subproducto = 'update_subproducto'.$contador;
			$variable_precio = 'update_precio'.$contador;
			$variable_costo = 'update_costo'.$contador;
			$variable_cantidad = 'update_cantidad'.$contador;
			$variable_total = 'update_total'.$contador;
			$variable_calculos = 'getUpdateCalculos'.$contador.'();'
			
		?>
			<tr form="prospecto" id="update_tr" name="update_tr" class="">
				<td id="action_update_delete">
					<input type="text" id="<?php print($variable_detalle);?>" name="<?php print($variable_detalle);?>" value="<?php print($id_detalles[$contador]);?>" hidden />
					<input type="checkbox" class="" id="<?php print($variable_eliminar);?>" name="<?php print($variable_eliminar);?>" value="<?php print($id_detalles[$contador]);?>"></input>
				</td>
				<td id="update_select_familia">
					<select readonly id="<?php print($variable_familia);?>" name="<?php print($variable_familia);?>" class="form-control">
						<option value="<?php print($row['id_familiaproducto']);?>" selected><?php print($row['familia_producto']);?></option>
					</select>
				</td>
				<td id="update_select_producto">
					<select readonly id="<?php print($variable_producto);?>" name="<?php print($variable_producto);?>" class="form-control">
						<option value="<?php print($row['id_producto']);?>" selected><?php print($row['nombre_producto']);?></option>
					</select>
				</td>
				<td id="update_select_subproducto">
					<select readonly id="<?php print($variable_subproducto);?>" name="<?php print($variable_subproducto);?>" class="form-control">
						<option value="<?php print($row['id_subproducto']);?>" selected><?php print($row['nombre_subproducto']);?></option>
					</select>
				</td>
				<td id="update_txt_precio">
					<input type="text" class="form-control" name="<?php print($variable_precio);?>" id="<?php print($variable_precio);?>" value="<?php print($row['precio_subproducto']);?>">
				</td>
				<td id="update_txt_costo">
					<input type="text" class="form-control" name="<?php print($variable_costo);?>" id="<?php print($variable_costo);?>" value="<?php print($row['costo_subproducto']);?>" readonly>
				</td>
				<td id="update_txt_cantidad">
					<input onkeyup="<?php print($variable_calculos); ?>" type="text" class="form-control" data-parsley-type="digits"  name="<?php print($variable_cantidad);?>" value="<?php print($row['cantidad_proyecto']);?>" id="<?php print($variable_cantidad);?>">
				</td>
				<td id="update_txt_total">
					<input type="text" class="form-control" name="<?php print($variable_total);?>" id="<?php print($variable_total);?>" value="<?php print(($row['precio_subproducto']-$row['costo_subproducto'])*$row['cantidad_proyecto']);?>" readonly>
				</td>
			</tr>
		<?php
		$contador++;
		}
		?>
			<input id="contador" name="contador" value="<?php print($contador);?>" type="text" hidden />
		<?php			
		json_encode($row);
		json_encode($row2);
		return true;	
	}
	//PROYECTO PROSPECTO
	public function showProyectosProspecto($proyecto)
	{
		$stmt = $this->conn->prepare("SELECT * FROM T_Proyectos AS P
			INNER JOIN T_Precio_Negociacion AS PN
			ON P.id_precio_negociacion = PN.id_precio_negociacion
			INNER JOIN T_Prospectos AS Pros
			ON P.id_prospecto = Pros.id_prospecto
			INNER JOIN T_EstatusProyecto AS EP
			ON EP.id_estatusproyecto = P.id_estatusproyecto
			WHERE P.id_proyecto = :proyecto");
		$stmt->execute(array(':proyecto'=>$proyecto));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row;
	}
	//PROYECTO CLIENTE
	public function showProyectosCliente($proyecto)
	{
		$stmt = $this->conn->prepare("SELECT * FROM T_Proyectos AS P
			INNER JOIN T_Precio_Negociacion AS PN
			ON P.id_precio_negociacion = PN.id_precio_negociacion
			INNER JOIN T_Clientes AS C
			ON P.id_cliente = C.id_cliente
			INNER JOIN T_EstatusProyecto AS EP
			ON EP.id_estatusproyecto = P.id_estatusproyecto
			WHERE P.id_proyecto = :proyecto");
		$stmt->execute(array(':proyecto'=>$proyecto));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row;
	}
	public function ShowTotales($proyecto)
	{
		$stmt = $this->conn->prepare("SELECT 
					SUM(DP.precio_proyecto*DP.cantidad_proyecto) AS TOTAL,
					SUM((DP.precio_proyecto-(((Sub.costo_subproducto/DP.precio_proyecto)-DP.gasto_directo)*DP.precio_proyecto))*DP.cantidad_proyecto) AS Margen
				FROM  T_Detalle_Proyectos AS DP
				INNER JOIN T_Proyectos AS P
				ON P.id_proyecto = DP.id_proyecto
				INNER JOIN T_Precio_Negociacion AS PN
				ON P.id_precio_negociacion = PN.id_precio_negociacion
				INNER JOIN T_Subproductos AS Sub
				ON DP.id_subproducto = Sub.id_subproducto
				INNER JOIN T_Productos AS Prod
				ON Sub.id_producto = Prod.id_producto
				INNER JOIN T_FamiliaProductos AS Fam
				ON Prod.id_familiaproducto = Fam.id_familiaproducto
				WHERE DP.id_proyecto = :proyecto");
		$stmt->execute(array(':proyecto'=>$proyecto));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row;
	}
	public function showProyectosDetalleCliente($proyecto)
	{
		$stmt2 = $this->conn->prepare("SELECT 
					DP.id_detalleproyecto,
					Fam.id_familiaproducto,
					Fam.familia_producto,
					Prod.id_producto,
					Prod.nombre_producto,
					DP.id_subproducto,
					Sub.nombre_subproducto,
					DP.precio_proyecto,
					DP.tema_proyecto,
					DP.precio_cambiar,
					(DP.costo_proyecto/DP.precio_proyecto) AS costo_porcentaje,
					DP.costo_proyecto,
					DP.gasto_directo,
					DP.cantidad_proyecto,
					1-DP.descuento AS descuento_final,
					(DP.precio_proyecto*DP.cantidad_proyecto) AS TOTAL,
					((DP.precio_proyecto-(DP.costo_proyecto-DP.gasto_directo))*DP.cantidad_proyecto) AS Margen
				FROM  T_Detalle_Proyectos AS DP
				INNER JOIN T_Proyectos AS P
				ON P.id_proyecto = DP.id_proyecto
				INNER JOIN T_Precio_Negociacion AS PN
				ON P.id_precio_negociacion = PN.id_precio_negociacion
				INNER JOIN T_Subproductos AS Sub
				ON DP.id_subproducto = Sub.id_subproducto
				INNER JOIN T_Productos AS Prod
				ON Sub.id_producto = Prod.id_producto
				INNER JOIN T_FamiliaProductos AS Fam
				ON Prod.id_familiaproducto = Fam.id_familiaproducto
				WHERE DP.id_proyecto = :proyecto");
		$stmt2->execute(array(':proyecto'=>$proyecto));
		$contador = 1;
		while($row=$stmt2->fetch(PDO::FETCH_ASSOC))
		{
			$id_detalles = [];
			$id_detalles[$contador] = $row['id_detalleproyecto'];
			$variable_detalle = 'modificar'.$contador;
			$variable_eliminar = 'eliminar'.$contador;
			$variable_familia = 'update_familia'.$contador;
			$variable_tema = 'update_tema'.$contador;
			$variable_producto = 'update_producto'.$contador;
			$variable_subproducto = 'update_subproducto'.$contador;
			$variable_precio = 'update_precio'.$contador;
			$variable_precio_cambiar = 'update_precio_cambiar'.$contador;
			$variable_costo = 'update_costo'.$contador;
			$variable_costo_importe = 'update_costo_importe'.$contador;
			$variable_gasto = 'update_gasto'.$contador;
			$variable_cantidad = 'update_cantidad'.$contador;
			$variable_descuento = 'update_descuento'.$contador;
			$variable_total = 'update_total'.$contador;
			$variable_margen = 'update_margen'.$contador;
			$variable_margen_class = 'margen';
			$variable_total_class = 'total';
			$variable_calculos = 'getUpdateCalculos(this);';
			
			if($row['id_producto'] == 23)
			{
				$readonly = "";
			}
			else
			{
				$readonly = "readonly";
			}
		?>
		<tr id="update_tr" name="update_tr" class="clonedProyecto">
			<td id="action_update_delete">
				<input type="text" id="<?php print($variable_detalle);?>" name="<?php print($variable_detalle);?>" value="<?php print($row['id_detalleproyecto']);?>" hidden />
				<input type="checkbox" class="" id="<?php print($variable_eliminar);?>" name="<?php print($variable_eliminar);?>" value="<?php print($id_detalles[$contador]);?>"></input>
			</td>
			<td id="update_select_familia">
				<select readonly id="<?php print($variable_familia);?>" name="<?php print($variable_familia);?>" class="form-control">
					<option value="<?php print($row['id_familiaproducto']);?>" selected><?php print($row['familia_producto']);?></option>
				</select>
			</td>
			<td id="update_select_producto">
				<select readonly id="<?php print($variable_producto);?>" name="<?php print($variable_producto);?>" class="form-control">
					<option value="<?php print($row['id_producto']);?>" selected><?php print($row['nombre_producto']);?></option>
				</select>
			</td>
			<td id="update_tema">
				<?php
					if($row['id_subproducto'] == 7)
					{
				?>
				<select id="<?php print($variable_tema);?>" name="<?php print($variable_tema);?>" class="form-control">
					<option selected value="<?php print($row['tema_proyecto']); ?>"><?php print($row['tema_proyecto']); ?></option>
					<option selected value="Primer Cluster">Primer Cluster</option>
					<option value="Segundo Cluster">Segundo Cluster</option>
					<option value="Tercer Cluster">Tercer Cluster</option>
					<option value="Cuarto Cluster">Cuarto Cluster</option>
				</select>
				<?php
					}
					elseif($row['id_producto'] == 40 || $row['id_producto'] == 41 || $row['id_producto'] == 42 || $row['id_producto'] == 3)
					{
				?>
				<select id="<?php print($variable_tema);?>" name="<?php print($variable_tema);?>" class="form-control">
					<option selected value="<?php print($row['tema_proyecto']); ?>"><?php print($row['tema_proyecto']); ?></option>
					<option value="Visión de negocio">Visión de negocio</option>
					<option value="Orientación a resultados">Orientación a resultados</option>
					<option value="Comunicación interpersonal">Comunicación interpersonal</option>
					<option value="Liderazgo">Liderazgo</option>
					<option value="Sinergia Organizacional">Sinergia Organizacional</option>
					<option value="Análisis de problemas y toma de decisiones">Análisis de problemas y toma de decisiones</option>
					<option value="Administración de microproyectos">Administración de microproyectos</option>
					<option value="Inteligencia emocional">Inteligencia emocional</option>
					<option value="Inteligencia social">Inteligencia social</option>
				</select>
				<?php
					}
					else
					{
				?>
				<input type="text" id="<?php print($variable_tema);?>" name="<?php print($variable_tema);?>" value="<?php print($row['tema_proyecto']);?>"/>
				<?php
					}
				?>
				<select readonly id="<?php print($variable_familia);?>" name="<?php print($variable_familia);?>" class="form-control">
					<option value="<?php print($row['id_familiaproducto']);?>" selected><?php print($row['familia_producto']);?></option>
				</select>
			</td>
			<td id="update_select_subproducto">
				<select readonly id="<?php print($variable_subproducto);?>" name="<?php print($variable_subproducto);?>" class="form-control">
					<option value="<?php print($row['id_subproducto']);?>" selected><?php print($row['nombre_subproducto']);?></option>
				</select>
			</td>
			<td id="update_txt_precio">
				<input onkeyup="<?php print($variable_calculos); ?>" type="text" class="form-control" name="<?php print($variable_precio);?>" id="<?php print($variable_precio);?>" value="<?php print(number_format($row['precio_proyecto'],2));?>" readonly>
			</td>
			<td id="update_txt_precio_cambiar">
				<input onkeyup="getCommas(this);" type="text" class="preciocambiar form-control" name="<?php print($variable_precio_cambiar);?>" id="<?php print($variable_precio_cambiar);?>" value="<?php print(number_format($row['precio_cambiar'],2));?>" readonly>
			</td>
			<td id="update_txt_costo">
				<input type="text" class="form-control" name="<?php print($variable_costo);?>" id="<?php print($variable_costo);?>" value="<?php print(round($row['costo_porcentaje']*100,2).'%');?>" <?php print($readonly); ?> >
			</td>
			<td id="update_txt_costo_importe">
				<input onkeyup="getCommas(this);" type="text" class="form-control" name="<?php print($variable_costo_importe);?>" id="<?php print($variable_costo_importe);?>" value="<?php print(number_format($row['costo_proyecto'],2));?>" <?php print($readonly); ?> >
			</td>
			<td id="update_txt_gasto" style="display:none;" class="gasto">
				<input type="text" class="form-control" name="<?php print($variable_gasto);?>" id="<?php print($variable_gasto);?>" value="<?php print(round($row['gasto_directo']*100,2).'%');?>">
			</td>
			<td id="update_txt_cantidad">
				<input onkeyup="<?php print($variable_calculos); ?>" type="text" class="form-control" data-parsley-type="digits"  name="<?php print($variable_cantidad);?>" value="<?php print($row['cantidad_proyecto']);?>" id="<?php print($variable_cantidad);?>">
			</td>
			<!--<td id="update_txt_descuento" colspan="2">
				<input onkeyup="<?php //print($variable_calculos); ?>" type="text" class="form-control" id="<?php //print($variable_descuento);?>" name="<?php //print($variable_descuento);?>" value="<?php //print($row['descuento_final']);?>%">
			</td>-->
			<td id="update_txt_total">
				<input type="text" class="form-control <?php print($variable_total_class); ?>" name="<?php print($variable_total);?>" id="<?php print($variable_total);?>" value="<?php print(number_format($row['TOTAL'],2));?>" readonly>
			</td>
			<td id="update_txt_margen">
				<input type="text" class="form-control <?php print($variable_margen_class); ?>" name="<?php print($variable_margen);?>" id="<?php print($variable_margen);?>" value="<?php print(number_format($row['Margen'],2));?>" readonly>
			</td>
		</tr>
		<?php
			$contador++;
		}
		?>
		<input id="contador" name="contador" value="<?php print($contador);?>" type="text" hidden />				
		<?php			
	}
	public function showProyectosDetalleClienteDirector($proyecto)
	{
		$stmt2 = $this->conn->prepare("SELECT 
					DP.id_detalleproyecto,
					Fam.id_familiaproducto,
					Fam.familia_producto,
					Prod.id_producto,
					Prod.nombre_producto,
					DP.id_subproducto,
					DP.tema_proyecto,
					Sub.nombre_subproducto,
					DP.precio_proyecto,
					DP.precio_cambiar,
					DP.costo_proyecto,
					(DP.costo_proyecto/DP.precio_proyecto) AS costo_porcentaje,
					DP.gasto_directo,
					DP.cantidad_proyecto,
					1-DP.descuento AS descuento_final,
					(DP.precio_proyecto*DP.cantidad_proyecto) AS TOTAL,
					((DP.precio_proyecto-((DP.costo_proyecto-DP.gasto_directo)))*DP.cantidad_proyecto) AS Margen
				FROM  T_Detalle_Proyectos AS DP
				INNER JOIN T_Proyectos AS P
				ON P.id_proyecto = DP.id_proyecto
				INNER JOIN T_Precio_Negociacion AS PN
				ON P.id_precio_negociacion = PN.id_precio_negociacion
				INNER JOIN T_Subproductos AS Sub
				ON DP.id_subproducto = Sub.id_subproducto
				INNER JOIN T_Productos AS Prod
				ON Sub.id_producto = Prod.id_producto
				INNER JOIN T_FamiliaProductos AS Fam
				ON Prod.id_familiaproducto = Fam.id_familiaproducto
				WHERE DP.id_proyecto = :proyecto");
		$stmt2->execute(array(':proyecto'=>$proyecto));
		$contador = 1;
		while($row=$stmt2->fetch(PDO::FETCH_ASSOC))
		{
			$id_detalles = [];
			$id_detalles[$contador] = $row['id_detalleproyecto'];
			$variable_detalle = 'modificar'.$contador;
			$variable_eliminar = 'eliminar'.$contador;
			$variable_tema = 'update_tema'.$contador;
			$variable_familia = 'update_familia'.$contador;
			$variable_producto = 'update_producto'.$contador;
			$variable_subproducto = 'update_subproducto'.$contador;
			$variable_precio = 'update_precio'.$contador;
			$variable_precio_cambiar = 'update_precio_cambiar'.$contador;
			$variable_costo = 'update_costo'.$contador;
			$variable_costo_importe = 'update_costo_importe'.$contador;
			$variable_costo_aux = 'update_costo_aux'.$contador;
			$variable_gasto = 'update_gasto'.$contador;
			$variable_cantidad = 'update_cantidad'.$contador;
			$variable_descuento = 'update_descuento'.$contador;
			$variable_total = 'update_total'.$contador;
			$variable_margen = 'update_margen'.$contador;
			$variable_margen_class = 'margen';
			$variable_total_class = 'total';
			$variable_calculos = 'getUpdateCalculos(this);';
			if($row['precio_cambiar'] != 0)
			{
			?>
			<tr id="update_tr" name="update_tr" class="clonedProyecto" bgcolor="#FFDB00">
				<td id="action_update_delete">
					<input type="text" id="<?php print($variable_detalle);?>" name="<?php print($variable_detalle);?>" value="<?php print($row['id_detalleproyecto']);?>" hidden />
					<input type="checkbox" class="" id="<?php print($variable_eliminar);?>" name="<?php print($variable_eliminar);?>" value="<?php print($id_detalles[$contador]);?>"></input>
				</td>
				<td id="update_select_familia">
					<select readonly id="<?php print($variable_familia);?>" name="<?php print($variable_familia);?>" class="form-control">
						<option value="<?php print($row['id_familiaproducto']);?>" selected><?php print($row['familia_producto']);?></option>
					</select>
				</td>
				<td id="update_select_producto">
					<select readonly id="<?php print($variable_producto);?>" name="<?php print($variable_producto);?>" class="form-control">
						<option value="<?php print($row['id_producto']);?>" selected><?php print($row['nombre_producto']);?></option>
					</select>
				</td>
				<td id="update_tema">
				   <input class="form-control" readonly type="text" id="<?php print($variable_tema);?>" name="<?php print($variable_tema);?>" value="<?php print($row['tema_proyecto']);?>"/>
				</td>
				<td id="update_select_subproducto">
					<select readonly id="<?php print($variable_subproducto);?>" name="<?php print($variable_subproducto);?>" class="form-control">
						<option value="<?php print($row['id_subproducto']);?>" selected><?php print($row['nombre_subproducto']);?></option>
					</select>
				</td>
				<td id="update_txt_precio" bgcolor="#C30000">
					<input onkeyup="getCostosActuales(this); <?php print($variable_calculos); ?>" type="text" class="form-control" name="<?php print($variable_precio);?>" id="<?php print($variable_precio);?>" value="<?php print(number_format($row['precio_proyecto'],2));?>">
				</td>
				<td id="update_txt_precio_cambiar" bgcolor="#C30000">
					<input type="text" class="form-control" name="<?php print($variable_precio_cambiar);?>" id="<?php print($variable_precio_cambiar);?>" value="<?php print(number_format($row['precio_cambiar'],2));?>" readonly>
				</td>
				<td id="update_txt_costo">
					<input type="text" class="form-control" name="<?php print($variable_costo);?>" id="<?php print($variable_costo);?>" value="<?php print(round($row['costo_porcentaje']*100,2).'%');?>" readonly>
					<input type="hidden" class="form-control" name="<?php print($variable_costo_aux);?>" id="<?php print($variable_costo_aux);?>" value="<?php print($row['costo_proyecto']);?>" readonly>
				</td>
				<td id="update_txt_costo_importe">
					<input type="text" class="form-control" name="<?php print($variable_costo_importe);?>" id="<?php print($variable_costo_importe);?>" value="<?php print(number_format($row['costo_proyecto'],2));?>" readonly>
				</td>
				<td id="update_txt_gasto" style="display:none;" class="gasto">
					<input type="text" class="form-control" name="<?php print($variable_gasto);?>" id="<?php print($variable_gasto);?>" value="<?php print(round($row['gasto_directo']*100,2).'%');?>" readonly>
				</td>
				<td id="update_txt_cantidad">
					<input onkeyup="<?php print($variable_calculos); ?>" type="text" class="form-control" data-parsley-type="digits"  name="<?php print($variable_cantidad);?>" value="<?php print($row['cantidad_proyecto']);?>" id="<?php print($variable_cantidad);?>" readonly>
				</td>
				<!--<td id="update_txt_descuento" colspan="2">
					<input onkeyup="<?php //print($variable_calculos); ?>" type="text" class="form-control" id="<?php //print($variable_descuento);?>" name="<?php //print($variable_descuento);?>" value="<?php //print($row['descuento_final']);?>%">
				</td>-->
				<td id="update_txt_total">
					<input type="text" class="form-control <?php print($variable_total_class); ?>" name="<?php print($variable_total);?>" id="<?php print($variable_total);?>" value="<?php print(number_format($row['TOTAL'],2));?>" readonly>
				</td>
				<td id="update_txt_margen">
					<input type="text" class="form-control <?php print($variable_margen_class); ?>" name="<?php print($variable_margen);?>" id="<?php print($variable_margen);?>" value="<?php print(number_format($row['Margen'],2));?>" readonly>
				</td>
			</tr>
			<?php	
			}
			else
			{
			?>
			<tr id="update_tr" name="update_tr" class="clonedProyecto" bgcolor="">
				<td id="action_update_delete">
					<input type="text" id="<?php print($variable_detalle);?>" name="<?php print($variable_detalle);?>" value="<?php print($row['id_detalleproyecto']);?>" hidden />
					<input type="checkbox" class="" id="<?php print($variable_eliminar);?>" name="<?php print($variable_eliminar);?>" value="<?php print($id_detalles[$contador]);?>"></input>
				</td>
				<td id="update_select_familia">
					<select readonly id="<?php print($variable_familia);?>" name="<?php print($variable_familia);?>" class="form-control">
						<option value="<?php print($row['id_familiaproducto']);?>" selected><?php print($row['familia_producto']);?></option>
					</select>
				</td>
				<td id="update_select_producto">
					<select readonly id="<?php print($variable_producto);?>" name="<?php print($variable_producto);?>" class="form-control">
						<option value="<?php print($row['id_producto']);?>" selected><?php print($row['nombre_producto']);?></option>
					</select>
				</td>
				<td id="update_tema">
				   <input class="form-control" readonly type="text" id="<?php print($variable_tema);?>" name="<?php print($variable_tema);?>" value="<?php print($row['tema_proyecto']);?>"/>
				</td>
				<td id="update_select_subproducto">
					<select readonly id="<?php print($variable_subproducto);?>" name="<?php print($variable_subproducto);?>" class="form-control">
						<option value="<?php print($row['id_subproducto']);?>" selected><?php print($row['nombre_subproducto']);?></option>
					</select>
				</td>
				<td id="update_txt_precio">
					<input onkeyup="getCostosActuales(this); <?php print($variable_calculos); ?>" type="text" class="form-control" name="<?php print($variable_precio);?>" id="<?php print($variable_precio);?>" value="<?php print(number_format($row['precio_proyecto'],2));?>">
				</td>
				<td id="update_txt_precio_cambiar">
					<input type="text" class="form-control" name="<?php print($variable_precio_cambiar);?>" id="<?php print($variable_precio_cambiar);?>" value="0" readonly>
				</td>
				<td id="update_txt_costo">
					<input type="text" class="form-control" name="<?php print($variable_costo);?>" id="<?php print($variable_costo);?>" value="<?php print(round($row['costo_porcentaje']*100,2).'%');?>" readonly>
					<input type="hidden" class="form-control" name="<?php print($variable_costo_aux);?>" id="<?php print($variable_costo_aux);?>" value="<?php print($row['costo_proyecto']);?>" readonly>
				</td>
				<td id="update_txt_costo_importe">
					<input type="text" class="form-control" name="<?php print($variable_costo_importe);?>" id="<?php print($variable_costo_importe);?>" value="<?php print(number_format($row['costo_proyecto'],2));?>" readonly>
				</td>
				<td id="update_txt_gasto" style="display:none;" class="gasto">
					<input type="text" class="form-control" name="<?php print($variable_gasto);?>" id="<?php print($variable_gasto);?>" value="<?php print(round($row['gasto_directo']*100,2).'%');?>" readonly>
				</td>
				<td id="update_txt_cantidad">
					<input onkeyup="<?php print($variable_calculos); ?>" type="text" class="form-control" data-parsley-type="digits"  name="<?php print($variable_cantidad);?>" value="<?php print($row['cantidad_proyecto']);?>" id="<?php print($variable_cantidad);?>" readonly>
				</td>
				<!--<td id="update_txt_descuento" colspan="2">
					<input onkeyup="<?php //print($variable_calculos); ?>" type="text" class="form-control" id="<?php //print($variable_descuento);?>" name="<?php //print($variable_descuento);?>" value="<?php //print($row['descuento_final']);?>%">
				</td>-->
				<td id="update_txt_total">
					<input type="text" class="form-control <?php print($variable_total_class); ?>" name="<?php print($variable_total);?>" id="<?php print($variable_total);?>" value="<?php print(number_format($row['TOTAL'],2));?>" readonly>
				</td>
				<td id="update_txt_margen">
					<input type="text" class="form-control <?php print($variable_margen_class); ?>" name="<?php print($variable_margen);?>" id="<?php print($variable_margen);?>" value="<?php print(number_format($row['Margen'],2));?>" readonly>
				</td>
			</tr>
			<?php
			}
			$contador++;
		}
		?>
		<input id="contador" name="contador" value="<?php print($contador);?>" type="text" hidden />				
		<?php
	}
	public function showProyectosDetalleClienteAVEFO($proyecto)
	{
		$stmt2 = $this->conn->prepare("SELECT 
					DP.id_detalleproyecto,
					Fam.id_familiaproducto,
					Fam.familia_producto,
					Prod.id_producto,
					Prod.nombre_producto,
					DP.id_subproducto,
					Sub.nombre_subproducto,
					DP.precio_proyecto,
					DP.precio_cambiar,
					DP.tema_proyecto,
					DP.costo_proyecto,
					(DP.costo_proyecto/DP.precio_proyecto) AS costo_porcentaje,
					DP.gasto_directo,
					DP.cantidad_proyecto,
					1-DP.descuento AS descuento_final,
					(DP.precio_proyecto*DP.cantidad_proyecto) AS TOTAL,
					((DP.precio_proyecto-(DP.costo_proyecto-DP.gasto_directo))*DP.cantidad_proyecto) AS Margen
				FROM  T_Detalle_Proyectos AS DP
				INNER JOIN T_Proyectos AS P
				ON P.id_proyecto = DP.id_proyecto
				INNER JOIN T_Precio_Negociacion AS PN
				ON P.id_precio_negociacion = PN.id_precio_negociacion
				INNER JOIN T_Subproductos AS Sub
				ON DP.id_subproducto = Sub.id_subproducto
				INNER JOIN T_Productos AS Prod
				ON Sub.id_producto = Prod.id_producto
				INNER JOIN T_FamiliaProductos AS Fam
				ON Prod.id_familiaproducto = Fam.id_familiaproducto
				WHERE DP.id_proyecto = :proyecto");
		$stmt2->execute(array(':proyecto'=>$proyecto));
		$contador = 1;
		while($row=$stmt2->fetch(PDO::FETCH_ASSOC))
		{
			$id_detalles = [];
			$id_detalles[$contador] = $row['id_detalleproyecto'];
			$variable_detalle = 'modificar'.$contador;
			$variable_eliminar = 'eliminar'.$contador;
			$variable_familia = 'update_familia'.$contador;
			$variable_producto = 'update_producto'.$contador;
			$variable_subproducto = 'update_subproducto'.$contador;
			$variable_tema = 'update_tema'.$contador;
			$variable_precio = 'update_precio'.$contador;
			$variable_precio_cambiar = 'update_precio_cambiar'.$contador;
			$variable_costo = 'update_costo'.$contador;
			$variable_costo_importe = 'update_costo_importe'.$contador;
			$variable_costo_aux = 'update_costo_aux'.$contador;
			$variable_gasto = 'update_gasto'.$contador;
			$variable_cantidad = 'update_cantidad'.$contador;
			$variable_descuento = 'update_descuento'.$contador;
			$variable_total = 'update_total'.$contador;
			$variable_margen = 'update_margen'.$contador;
			$variable_margen_class = 'margen';
			$variable_total_class = 'total';
			$variable_calculos = 'getUpdateCalculos(this);';
			
			if($row['precio_cambiar'] != 0)
			{
			?>
			<tr id="update_tr" name="update_tr" class="clonedProyecto" bgcolor="#FFDB00">
				<td id="action_update_delete">
					<input type="text" id="<?php print($variable_detalle);?>" name="<?php print($variable_detalle);?>" value="<?php print($row['id_detalleproyecto']);?>" hidden />
					<input type="checkbox" class="" id="<?php print($variable_eliminar);?>" name="<?php print($variable_eliminar);?>" value="<?php print($id_detalles[$contador]);?>"></input>
				</td>
				<td id="update_select_familia">
					<select readonly id="<?php print($variable_familia);?>" name="<?php print($variable_familia);?>" class="form-control">
						<option value="<?php print($row['id_familiaproducto']);?>" selected><?php print($row['familia_producto']);?></option>
					</select>
				</td>
				<td id="update_select_producto">
					<select readonly id="<?php print($variable_producto);?>" name="<?php print($variable_producto);?>" class="form-control">
						<option value="<?php print($row['id_producto']);?>" selected><?php print($row['nombre_producto']);?></option>
					</select>
				</td>
				<td id="update_tema">
					<input readonly class="form-control" type="text" id="<?php print($variable_tema);?>" name="<?php print($variable_tema);?>" value="<?php print($row['tema_proyecto']);?>"/>
				</td>
				<td id="update_select_subproducto">
					<select readonly id="<?php print($variable_subproducto);?>" name="<?php print($variable_subproducto);?>" class="form-control">
						<option value="<?php print($row['id_subproducto']);?>" selected><?php print($row['nombre_subproducto']);?></option>
					</select>
				</td>
				<td id="update_txt_precio" bgcolor="#C30000">
					<input onkeyup="getCommas(this); getCostosActuales(this); <?php print($variable_calculos); ?>" type="text" class="form-control" name="<?php print($variable_precio);?>" id="<?php print($variable_precio);?>" value="<?php print(number_format($row['precio_proyecto'],2));?>">
				</td>
				<td id="update_txt_precio_cambiar" bgcolor="#C30000">
					<input readonly type="text" class="form-control" name="<?php print($variable_precio_cambiar);?>" id="<?php print($variable_precio_cambiar);?>" value="<?php print(number_format($row['precio_cambiar'],2));?>" >
				</td>
				<td id="update_txt_costo">
					<input type="text" class="form-control" name="<?php print($variable_costo);?>" id="<?php print($variable_costo);?>" value="<?php print(round($row['costo_porcentaje']*100,2).'%');?>" readonly>
					<input type="hidden" class="form-control" name="<?php print($variable_costo_aux);?>" id="<?php print($variable_costo_aux);?>" value="<?php print($row['costo_proyecto']);?>" readonly>
				</td>
				<td id="update_txt_costo_importe">
					<input type="text" class="form-control" name="<?php print($variable_costo_importe);?>" id="<?php print($variable_costo_importe);?>" value="<?php print(number_format($row['costo_proyecto'],2));?>" readonly>
				</td>
				<td id="update_txt_gasto" style="display:none;" class="gasto">
					<input type="text" class="form-control" name="<?php print($variable_gasto);?>" id="<?php print($variable_gasto);?>" value="<?php print(round($row['gasto_directo']*100,2).'%');?>" readonly>
				</td>
				<td id="update_txt_cantidad">
					<input onkeyup="<?php print($variable_calculos); ?>" type="text" class="form-control" data-parsley-type="digits"  name="<?php print($variable_cantidad);?>" value="<?php print($row['cantidad_proyecto']);?>" id="<?php print($variable_cantidad);?>" readonly>
				</td>
				<!--<td id="update_txt_descuento" colspan="2">
					<input onkeyup="<?php //print($variable_calculos); ?>" type="text" class="form-control" id="<?php //print($variable_descuento);?>" name="<?php //print($variable_descuento);?>" value="<?php //print($row['descuento_final']);?>%">
				</td>-->
				<td id="update_txt_total">
					<input type="text" class="form-control <?php print($variable_total_class); ?>" name="<?php print($variable_total);?>" id="<?php print($variable_total);?>" value="<?php print(number_format($row['TOTAL'],2));?>" readonly>
				</td>
				<td id="update_txt_margen">
					<input type="text" class="form-control <?php print($variable_margen_class); ?>" name="<?php print($variable_margen);?>" id="<?php print($variable_margen);?>" value="<?php print(number_format($row['Margen'],2));?>" readonly>
				</td>
			</tr>
			<?php	
			}
			else
			{
			?>
			<tr id="update_tr" name="update_tr" class="clonedProyecto" bgcolor="">
				<td id="action_update_delete">
					<input type="text" id="<?php print($variable_detalle);?>" name="<?php print($variable_detalle);?>" value="<?php print($row['id_detalleproyecto']);?>" hidden />
					<input type="checkbox" class="" id="<?php print($variable_eliminar);?>" name="<?php print($variable_eliminar);?>" value="<?php print($id_detalles[$contador]);?>"></input>
				</td>
				<td id="update_select_familia">
					<select readonly id="<?php print($variable_familia);?>" name="<?php print($variable_familia);?>" class="form-control">
						<option value="<?php print($row['id_familiaproducto']);?>" selected><?php print($row['familia_producto']);?></option>
					</select>
				</td>
				<td id="update_select_producto">
					<select readonly id="<?php print($variable_producto);?>" name="<?php print($variable_producto);?>" class="form-control">
						<option value="<?php print($row['id_producto']);?>" selected><?php print($row['nombre_producto']);?></option>
					</select>
				</td>
				<td id="update_tema">
					<input readonly class="form-control" type="text" id="<?php print($variable_tema);?>" name="<?php print($variable_tema);?>" value="<?php print($row['tema_proyecto']);?>"/>
				</td>
				<td id="update_select_subproducto">
					<select readonly id="<?php print($variable_subproducto);?>" name="<?php print($variable_subproducto);?>" class="form-control">
						<option value="<?php print($row['id_subproducto']);?>" selected><?php print($row['nombre_subproducto']);?></option>
					</select>
				</td>
				<td id="update_txt_precio">
					<input onkeyup="getCommas(this); getCostosActuales(this); <?php print($variable_calculos); ?>" type="text" class="form-control" name="<?php print($variable_precio);?>" id="<?php print($variable_precio);?>" value="<?php print(number_format($row['precio_proyecto'],2));?>">
				</td>
				<td id="update_txt_precio_cambiar">
					<input readonly type="text" class="form-control" name="<?php print($variable_precio_cambiar);?>" id="<?php print($variable_precio_cambiar);?>" value="0" readonly>
				</td>
				<td id="update_txt_costo">
					<input type="text" class="form-control" name="<?php print($variable_costo);?>" id="<?php print($variable_costo);?>" value="<?php print(round($row['costo_porcentaje']*100,2).'%');?>" readonly>
					<input type="hidden" class="form-control" name="<?php print($variable_costo_aux);?>" id="<?php print($variable_costo_aux);?>" value="<?php print($row['costo_proyecto']);?>" readonly>
				</td>
				<td id="update_txt_costo_importe">
					<input type="text" class="form-control" name="<?php print($variable_costo_importe);?>" id="<?php print($variable_costo_importe);?>" value="<?php print(number_format($row['costo_proyecto'],2));?>" readonly>
				</td>
				<td id="update_txt_gasto" style="display:none;" class="gasto">
					<input type="text" class="form-control" name="<?php print($variable_gasto);?>" id="<?php print($variable_gasto);?>" value="<?php print(round($row['gasto_directo']*100,2).'%');?>" readonly>
				</td>
				<td id="update_txt_cantidad">
					<input onkeyup="<?php print($variable_calculos); ?>" type="text" class="form-control" data-parsley-type="digits"  name="<?php print($variable_cantidad);?>" value="<?php print($row['cantidad_proyecto']);?>" id="<?php print($variable_cantidad);?>" readonly>
				</td>
				<!--<td id="update_txt_descuento" colspan="2">
					<input onkeyup="<?php //print($variable_calculos); ?>" type="text" class="form-control" id="<?php //print($variable_descuento);?>" name="<?php //print($variable_descuento);?>" value="<?php //print($row['descuento_final']);?>%">
				</td>-->
				<td id="update_txt_total">
					<input type="text" class="form-control <?php print($variable_total_class); ?>" name="<?php print($variable_total);?>" id="<?php print($variable_total);?>" value="<?php print(number_format($row['TOTAL'],2));?>" readonly>
				</td>
				<td id="update_txt_margen">
					<input type="text" class="form-control <?php print($variable_margen_class); ?>" name="<?php print($variable_margen);?>" id="<?php print($variable_margen);?>" value="<?php print(number_format($row['Margen'],2));?>" readonly>
				</td>
			</tr>
			<?php
			}
			$contador++;
		}
		?>
		<input id="contador" name="contador" value="<?php print($contador);?>" type="text" hidden />				
		<?php
	}
}
?>
