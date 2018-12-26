<?php
	include_once 'dbconfig.php';
	class Bitacora
	{
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
		public function getEstadosNew()
		{
			$query = "SELECT 
						id_estado,
						estado
					FROM T_Estado";
			$stmt = $this->conn->prepare($query);
			$stmt->execute();
			$auxiliar = "";
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				$auxiliar = $auxiliar."<option value='".$row['id_estado']."'>".$row['estado']."</option>";
			}
			return $auxiliar;
		}
		//SOLICITUD VIATICOS
		public function getDatosGeneralesSolicitud($id)
		{
			$query = "SELECT
						C.nombre_cliente,
						B.nombre_bitacora,
						B.id_cliente
					FROM T_Bitacora AS B
					INNER JOIN T_Clientes AS C
					ON C.id_cliente = B.id_cliente
					WHERE B.id_bitacora = :id";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":id",$id);
			$stmt->execute();
			$row=$stmt->fetch(PDO::FETCH_ASSOC);
			return $row;
		}
		public function getEstatusViaticos($id)
		{
			$query = "SELECT
						DB.id_detallebitacora,
						DB.id_del_evento
					FROM T_Detalle_Bitacora AS DB
					WHERE DB.id_bitacora = :id AND DB.viaticos = 1";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":id",$id);
			$stmt->execute();
			$x = 1;
			if($stmt->rowCount()>0)
			{
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				?>
				<tr>
					<td><?php print($row['id_del_evento']); ?></td>
					<td>
						<select id="ejecutar<?php print($x);?>" name="ejecutar<?php print($x);?>" class="form-control">
							<option value="1">Ejecutado</option>
							<option value="2" selected>Por Ejecutar</option>
						</select>
					</td>
					<td>
						<select id="facturar<?php print($x);?>" name="facturar<?php print($x);?>" class="form-control">
							<option value="1">Facturado</option>
							<option value="2" selected>Por Facturar</option>
						</select>
					</td>
					<td>
						<select id="cobrar<?php print($x);?>" name="cobrar<?php print($x);?>" class="form-control">
							<option value="1">Cobrado</option>
							<option value="2" selected>Por Cobrar</option>
						</select>
					</td>
					<td>
						<select id="pagar<?php print($x);?>" name="pagar<?php print($x);?>" class="form-control">
							<option value="1">Pagado</option>
							<option value="2" selected>Por Pagar</option>
						</select>
					</td>
				</tr>
				<?php
				$x++;
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
			   </tr>
			   <?php
			}
		}
		public function getForaneo($id)
		{
			$query = "SELECT
						DB.id_detallebitacora,
						DB.id_del_evento,
						CONCAT(A.nombre_agente,' ',A.apellidos_agente) AS agente
					FROM T_Detalle_Bitacora AS DB
					INNER JOIN T_Detalle_Bitacora_Agente AS DBA
					ON DBA.id_detallebitacora = DB.id_detallebitacora
					INNER JOIN T_Agentes AS A
					ON A.id_agente = DBA.id_agente
					WHERE DB.id_bitacora = :id AND DB.viaticos = 1";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":id",$id);
			$stmt->execute();
			$i = 1;
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				?>
				<tr>
					<td><?php print($row['id_del_evento']); ?></td>
					<td><?php print($row['agente']); ?></td>
					<td><input id="foraneo_concepto_<?php print($i); ?>" name="concepto_<?php print($i); ?>" class="form-control" value=""></td>
					<td><input id="foraneo_importe_<?php print($i); ?>" name="importe_<?php print($i); ?>" class="form-control" value="0.00" onkeyup="getCommas(this); getSubtotal(this);"></td>
					<td><input id="foraneo_ish_<?php print($i); ?>" name="ish_<?php print($i); ?>" class="form-control" value="0.00" onkeyup="getCommas(this); getSubtotal(this);"></td>
					<td><input id="foraneo_otro_<?php print($i); ?>" name="otro_<?php print($i); ?>" class="form-control" value="0.00" onkeyup="getCommas(this); getSubtotal(this);"></td>
					<td><input id="foraneo_subtotal_<?php print($i); ?>" name="subtotal_<?php print($i); ?>" class="form-control" value="0.00" onkeyup="getCommas(this); getTotal(this);" readonly></td>
					<td><input id="foraneo_iva_<?php print($i); ?>" name="iva_<?php print($i); ?>" class="form-control" value="0.00" onkeyup="getCommas(this); getTotal(this);"></td>
					<td><input id="foraneo_total_<?php print($i); ?>" name="total_<?php print($i); ?>" class="form-control" value="0.00"readonly></td>
					<td></td>
				</tr>
				<?php
				$i++;
			}
		}
		public function getTerminales($id)
		{
			$query = "SELECT
						DB.id_detallebitacora,
						DB.id_del_evento,
						CONCAT(A.nombre_agente,' ',A.apellidos_agente) AS agente
					FROM T_Detalle_Bitacora AS DB
					INNER JOIN T_Detalle_Bitacora_Agente AS DBA
					ON DBA.id_detallebitacora = DB.id_detallebitacora
					INNER JOIN T_Agentes AS A
					ON A.id_agente = DBA.id_agente
					WHERE DB.id_bitacora = :id AND DB.viaticos = 1";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":id",$id);
			$stmt->execute();
			$i = 1;
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				?>
				<tr>
					<td><?php print($row['id_del_evento']); ?></td>
					<td><?php print($row['agente']); ?></td>
					<td><input id="terminales_concepto_<?php print($i); ?>" name="concepto_<?php print($i); ?>" class="form-control" value=""></td>
					<td><input id="terminales_importe_<?php print($i); ?>" name="importe_<?php print($i); ?>" class="form-control" value="0.00" onkeyup="getCommas(this); getSubtotal(this);"></td>
					<td><input id="terminales_ish_<?php print($i); ?>" name="ish_<?php print($i); ?>" class="form-control" value="0.00" onkeyup="getCommas(this); getSubtotal(this);"></td>
					<td><input id="terminales_otro_<?php print($i); ?>" name="otro_<?php print($i); ?>" class="form-control" value="0.00" onkeyup="getCommas(this); getSubtotal(this);"></td>
					<td><input id="terminales_subtotal_<?php print($i); ?>" name="subtotal_<?php print($i); ?>" class="form-control" value="0.00" onkeyup="getCommas(this); getTotal(this);" readonly></td>
					<td><input id="terminales_iva_<?php print($i); ?>" name="iva_<?php print($i); ?>" class="form-control" value="0.00" onkeyup="getCommas(this); getTotal(this);"></td>
					<td><input id="terminales_total_<?php print($i); ?>" name="total_<?php print($i); ?>" class="form-control" value="0.00"readonly></td>
					<td></td>
				</tr>
				<?php
				$i++;
			}
		}
		public function getHospedaje($id)
		{
			$query = "SELECT
						DB.id_detallebitacora,
						DB.id_del_evento,
						CONCAT(A.nombre_agente,' ',A.apellidos_agente) AS agente
					FROM T_Detalle_Bitacora AS DB
					INNER JOIN T_Detalle_Bitacora_Agente AS DBA
					ON DBA.id_detallebitacora = DB.id_detallebitacora
					INNER JOIN T_Agentes AS A
					ON A.id_agente = DBA.id_agente
					WHERE DB.id_bitacora = :id AND DB.viaticos = 1";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":id",$id);
			$stmt->execute();
			$i = 1;
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				?>
				<tr>
					<td><?php print($row['id_del_evento']); ?></td>
					<td><?php print($row['agente']); ?></td>
					<td><input id="hospedaje_concepto_<?php print($i); ?>" name="concepto_<?php print($i); ?>" class="form-control" value=""></td>
					<td><input id="hospedaje_importe_<?php print($i); ?>" name="importe_<?php print($i); ?>" class="form-control" value="0.00" onkeyup="getCommas(this); getSubtotal(this);"></td>
					<td><input id="hospedaje_ish_<?php print($i); ?>" name="ish_<?php print($i); ?>" class="form-control" value="0.00" onkeyup="getCommas(this); getSubtotal(this);"></td>
					<td><input id="hospedaje_otro_<?php print($i); ?>" name="otro_<?php print($i); ?>" class="form-control" value="0.00" onkeyup="getCommas(this); getSubtotal(this);"></td>
					<td><input id="hospedaje_subtotal_<?php print($i); ?>" name="subtotal_<?php print($i); ?>" class="form-control" value="0.00" onkeyup="getCommas(this); getTotal(this);" readonly></td>
					<td><input id="hospedaje_iva_<?php print($i); ?>" name="iva_<?php print($i); ?>" class="form-control" value="0.00" onkeyup="getCommas(this); getTotal(this);"></td>
					<td><input id="hospedaje_total_<?php print($i); ?>" name="total_<?php print($i); ?>" class="form-control" value="0.00"readonly></td>
					<td></td>
				</tr>
				<?php
				$i++;
			}
		}
		public function getLocal($id)
		{
			$query = "SELECT
						DB.id_detallebitacora,
						DB.id_del_evento,
						CONCAT(A.nombre_agente,' ',A.apellidos_agente) AS agente
					FROM T_Detalle_Bitacora AS DB
					INNER JOIN T_Detalle_Bitacora_Agente AS DBA
					ON DBA.id_detallebitacora = DB.id_detallebitacora
					INNER JOIN T_Agentes AS A
					ON A.id_agente = DBA.id_agente
					WHERE DB.id_bitacora = :id AND DB.viaticos = 1";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":id",$id);
			$stmt->execute();
			$i = 1;
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				?>
				<tr>
					<td><?php print($row['id_del_evento']); ?></td>
					<td><?php print($row['agente']); ?></td>
					<td><input id="local_concepto_<?php print($i); ?>" name="concepto_<?php print($i); ?>" class="form-control" value=""></td>
					<td><input id="local_importe_<?php print($i); ?>" name="importe_<?php print($i); ?>" class="form-control" value="0.00" onkeyup="getCommas(this); getSubtotal(this);"></td>
					<td><input id="local_ish_<?php print($i); ?>" name="ish_<?php print($i); ?>" class="form-control" value="0.00" onkeyup="getCommas(this); getSubtotal(this);"></td>
					<td><input id="local_otro_<?php print($i); ?>" name="otro_<?php print($i); ?>" class="form-control" value="0.00" onkeyup="getCommas(this); getSubtotal(this);"></td>
					<td><input id="local_subtotal_<?php print($i); ?>" name="subtotal_<?php print($i); ?>" class="form-control" value="0.00" onkeyup="getCommas(this); getTotal(this);" readonly></td>
					<td><input id="local_iva_<?php print($i); ?>" name="iva_<?php print($i); ?>" class="form-control" value="0.00" onkeyup="getCommas(this); getTotal(this);"></td>
					<td><input id="local_total_<?php print($i); ?>" name="total_<?php print($i); ?>" class="form-control" value="0.00"readonly></td>
					<td></td>
				</tr>
				<?php
				$i++;
			}
		}
		public function getAlimentos($id)
		{
			$query = "SELECT
						DB.id_detallebitacora,
						DB.id_del_evento,
						CONCAT(A.nombre_agente,' ',A.apellidos_agente) AS agente
					FROM T_Detalle_Bitacora AS DB
					INNER JOIN T_Detalle_Bitacora_Agente AS DBA
					ON DBA.id_detallebitacora = DB.id_detallebitacora
					INNER JOIN T_Agentes AS A
					ON A.id_agente = DBA.id_agente
					WHERE DB.id_bitacora = :id AND DB.viaticos = 1";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":id",$id);
			$stmt->execute();
			$i = 1;
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				?>
				<tr>
					<td><?php print($row['id_del_evento']); ?></td>
					<td><?php print($row['agente']); ?></td>
					<td><input id="alimentos_concepto_<?php print($i); ?>" name="concepto_<?php print($i); ?>" class="form-control" value=""></td>
					<td><input id="alimentos_importe_<?php print($i); ?>" name="importe_<?php print($i); ?>" class="form-control" value="0.00" onkeyup="getCommas(this); getSubtotal(this);"></td>
					<td><input id="alimentos_ish_<?php print($i); ?>" name="ish_<?php print($i); ?>" class="form-control" value="0.00" onkeyup="getCommas(this); getSubtotal(this);"></td>
					<td><input id="alimentos_otro_<?php print($i); ?>" name="otro_<?php print($i); ?>" class="form-control" value="0.00" onkeyup="getCommas(this); getSubtotal(this);"></td>
					<td><input id="alimentos_subtotal_<?php print($i); ?>" name="subtotal_<?php print($i); ?>" class="form-control" value="0.00" onkeyup="getCommas(this); getTotal(this);" readonly></td>
					<td><input id="alimentos_iva_<?php print($i); ?>" name="iva_<?php print($i); ?>" class="form-control" value="0.00" onkeyup="getCommas(this); getTotal(this);"></td>
					<td><input id="alimentos_total_<?php print($i); ?>" name="total_<?php print($i); ?>" class="form-control" value="0.00"readonly></td>
					<td></td>
				</tr>
				<?php
				$i++;
			}
		}
		public function getMateriales($id)
		{
			$query = "SELECT
						DB.id_detallebitacora,
						DB.id_del_evento,
						CONCAT(A.nombre_agente,' ',A.apellidos_agente) AS agente
					FROM T_Detalle_Bitacora AS DB
					INNER JOIN T_Detalle_Bitacora_Agente AS DBA
					ON DBA.id_detallebitacora = DB.id_detallebitacora
					INNER JOIN T_Agentes AS A
					ON A.id_agente = DBA.id_agente
					WHERE DB.id_bitacora = :id AND DB.viaticos = 1";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":id",$id);
			$stmt->execute();
			$i = 1;
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				?>
				<tr>
					<td><?php print($row['id_del_evento']); ?></td>
					<td><?php print($row['agente']); ?></td>
					<td><input id="materiales_concepto_<?php print($i); ?>" name="concepto_<?php print($i); ?>" class="form-control" value=""></td>
					<td><input id="materiales_importe_<?php print($i); ?>" name="importe_<?php print($i); ?>" class="form-control" value="0.00" onkeyup="getCommas(this); getSubtotal(this);"></td>
					<td><input id="materiales_ish_<?php print($i); ?>" name="ish_<?php print($i); ?>" class="form-control" value="0.00" onkeyup="getCommas(this); getSubtotal(this);"></td>
					<td><input id="materiales_otro_<?php print($i); ?>" name="otro_<?php print($i); ?>" class="form-control" value="0.00" onkeyup="getCommas(this); getSubtotal(this);"></td>
					<td><input id="materiales_subtotal_<?php print($i); ?>" name="subtotal_<?php print($i); ?>" class="form-control" value="0.00" onkeyup="getCommas(this); getTotal(this);" readonly></td>
					<td><input id="materiales_iva_<?php print($i); ?>" name="iva_<?php print($i); ?>" class="form-control" value="0.00" onkeyup="getCommas(this); getTotal(this);"></td>
					<td><input id="materiales_total_<?php print($i); ?>" name="total_<?php print($i); ?>" class="form-control" value="0.00"readonly></td>
					<td></td>
				</tr>
				<?php
				$i++;
			}
			?>
			<input id="auxiliar" name="auxiliar" class="form-control" type="hidden" value="<?php print($i);?>">
			<?php
		}
		
		
		public function selectAgenteIDDetalle($id)
		{
			$query = "SELECT 
						A.id_agente,
						CONCAT(A.nombre_agente,' ',A.apellidos_agente) AS nombreAgente
					FROM T_Agentes AS A
					INNER JOIN T_Detalle_Bitacora_Agente AS DBA
					ON DBA.id_agente = A.id_agente
					WHERE DBA.id_detallebitacora = :id";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":id",$id);
			$stmt->execute();
			if($stmt->rowCount()>0)
			{
				while($row=$stmt->fetch(PDO::FETCH_ASSOC))
				{
					?>
					<option value="<?php print($row['id_agente']); ?>"><?php print($row['nombreAgente']); ?></option>	
					<?php
				}
			}
			else
			{
				?>
				<option value="">No hay agentes del tipo solicitado</option>
				<?php
			}
		}
		public function insertSolicitudViaticos($monto_depositar,$fecha_entrega)
		{
			$this->conn->beginTransaction();
			try
			{
				$query="INSERT INTO  T_Solicitud_Viaticos(monto_importe,fecha_entrega) 
				VALUES (:monto_depositar,:fecha_entrega)";
				$stmt = $this->conn->prepare($query);
				$stmt->bindparam(":monto_depositar",$monto_depositar);
				$stmt->bindparam(":fecha_entrega",$fecha_entrega);
				$stmt->execute();
				$last_id = $this->conn->lastInsertId();
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
		public function insertSolicitudViaticosDetalle($last_id,$iddetallebitacora,$foraneoconcepto,$foraneoimporte,$foraneoish,$foraneootro,$foraneosubtotal,$foraneoiva
				,$terminalesconcepto,$terminalesimporte,$terminalesish,$terminalesotro,$terminalessubtotal,$terminalesiva
				,$hospedajeconcepto,$hospedajeimporte,$hospedajeish,$hospedajeotro,$hospedajesubtotal,$hospedajeiva
				,$localconcepto,$localimporte,$localish,$localotro,$localsubtotal,$localiva
				,$alimentosconcepto,$alimentosimporte,$alimentosish,$alimentosotro,$alimentossubtotal,$alimentosiva
				,$materialesconcepto,$materialesimporte,$materialesish,$materialesotro,$materialessubtotal,$materialesiva)
		{
			$this->conn->beginTransaction();
			try
			{
				$query="INSERT INTO `T_Detallesolicitud_Viaticos`(`id_solicitudviaticos`, `foraneo_importe`, `foraneo_ish`, `foraneo_otro`, `foraneo_subtotal`, `foraneo_iva`, `foraneo_concepto`, `id_detallebitacora`
				, `terminales_importe`, `terminales_ish`, `terminales_otro`, `terminales_subtotal`, `terminales_iva`, `terminales_concepto`
				, `hospedaje_importe`, `hospedaje_ish`, `hospedaje_otro`, `hospedaje_subtotal`, `hospedaje_iva`, `hospedaje_concepto`
				, `local_importe`, `local_ish`, `local_otro`, `local_subtotal`, `local_iva`, `local_concepto`
				, `alimentos_importe`, `alimentos_ish`, `alimentos_otro`, `alimentos_subtotal`, `alimentos_iva`, `alimentos_concepto`
				, `materiales_importe`, `materiales_ish`, `materiales_otro`, `materiales_subtotal`, `materiales_iva`, `materiales_concepto`) 
				VALUES (:last_id,:foraneoimporte,:foraneoish,:foraneootro,:foraneosubtotal,:foraneoiva,:foraneoconcepto,:iddetallebitacora
				,:terminalesimporte,:terminalesish,:terminalesotro,:terminalessubtotal,:terminalesiva,:terminalesconcepto
				,:hospedajeimporte,:hospedajeish,:hospedajeotro,:hospedajesubtotal,:hospedajeiva,:hospedajeconcepto
				,:localimporte,:localish,:localotro,:localsubtotal,:localiva,:localconcepto
				,:alimentosimporte,:alimentosish,:alimentosotro,:alimentossubtotal,:alimentosiva,:alimentosconcepto
				,:materialesimporte,:materialesish,:materialesotro,:materialessubtotal,:materialesiva,:materialesconcepto)";
				$stmt = $this->conn->prepare($query);
				$stmt->bindparam(":last_id",$last_id);
				$stmt->bindparam(":foraneoimporte",$foraneoimporte);
				$stmt->bindparam(":foraneoish",$foraneoish);
				$stmt->bindparam(":foraneootro",$foraneootro);
				$stmt->bindparam(":foraneosubtotal",$foraneosubtotal);
				$stmt->bindparam(":foraneoiva",$foraneoiva);
				$stmt->bindparam(":foraneoconcepto",$foraneoconcepto);
				$stmt->bindparam(":iddetallebitacora",$iddetallebitacora);
				$stmt->bindparam(":terminalesimporte",$terminalesimporte);
				$stmt->bindparam(":terminalesish",$terminalesish);
				$stmt->bindparam(":terminalesotro",$terminalesotro);
				$stmt->bindparam(":terminalessubtotal",$terminalessubtotal);
				$stmt->bindparam(":terminalesiva",$terminalesiva);
				$stmt->bindparam(":terminalesconcepto",$terminalesconcepto);
				$stmt->bindparam(":hospedajeimporte",$hospedajeimporte);
				$stmt->bindparam(":hospedajeish",$hospedajeish);
				$stmt->bindparam(":hospedajeotro",$hospedajeotro);
				$stmt->bindparam(":hospedajesubtotal",$hospedajesubtotal);
				$stmt->bindparam(":hospedajeiva",$hospedajeiva);
				$stmt->bindparam(":hospedajeconcepto",$hospedajeconcepto);
				$stmt->bindparam(":localimporte",$localimporte);
				$stmt->bindparam(":localish",$localish);
				$stmt->bindparam(":localotro",$localotro);
				$stmt->bindparam(":localsubtotal",$localsubtotal);
				$stmt->bindparam(":localiva",$localiva);
				$stmt->bindparam(":localconcepto",$localconcepto);
				$stmt->bindparam(":alimentosimporte",$alimentosimporte);
				$stmt->bindparam(":alimentosish",$alimentosish);
				$stmt->bindparam(":alimentosotro",$alimentosotro);
				$stmt->bindparam(":alimentossubtotal",$alimentossubtotal);
				$stmt->bindparam(":alimentosiva",$alimentosiva);
				$stmt->bindparam(":alimentosconcepto",$alimentosconcepto);
				$stmt->bindparam(":materialesimporte",$materialesimporte);
				$stmt->bindparam(":materialesish",$materialesish);
				$stmt->bindparam(":materialesotro",$materialesotro);
				$stmt->bindparam(":materialessubtotal",$materialessubtotal);
				$stmt->bindparam(":materialesiva",$materialesiva);
				$stmt->bindparam(":materialesconcepto",$materialesconcepto);
				$stmt->execute();
				$last_id = $this->conn->lastInsertId();
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
		//VIEW EVENTOS
		public function showEventos($id)
		{
			$query = "SELECT 
						DB.id_detallebitacora,
						DB.evento,
						DB.id_del_evento
					FROM T_Detalle_Bitacora AS DB
					WHERE DB.id_bitacora = :id";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":id",$id);
			$stmt->execute();
			if($stmt->rowCount()>0)
			{
				while($row=$stmt->fetch(PDO::FETCH_ASSOC))
				{
					?>
					<div class="content-box-wrapper">
						<div class="example-box-wrapper">
							<div class="form-group">
								<div class="col-sm-3">
								</div>
								<label for="" class="col-sm-4 control-label"><?php print($row['evento'].' - '.$row['id_del_evento']);?></label>
								<div class="col-sm-2">
									<div class="input-prepend input-group">
										<input type="checkbox" id="<?php print($row['id_detallebitacora']); ?>" name="<?php print($row['id_detallebitacora']); ?>" value="1">
									</div>
								</div>
								<div class="col-sm-3">
								</div>
							</div>
						</div>
					</div>
					<?php
				}
			}
		}
		//VIEW AGENTES
		public function selectAgentes($tipo)
		{
			$query = "SELECT 
						A.id_agente,
						A.nombre_agente,
						A.apellidos_agente
					FROM T_Agentes AS A
					INNER JOIN T_TipoAgente_Agente AS TAA
					ON TAA.id_agente = A.id_agente
					WHERE TAA.id_tipoagente = :tipo";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":tipo",$tipo);
			$stmt->execute();
			if($stmt->rowCount()>0)
			{
				while($row=$stmt->fetch(PDO::FETCH_ASSOC))
				{
					?>
					<option value="<?php print($row['id_agente']); ?>"><?php print($row['nombre_agente'].' '.$row['apellidos_agente']); ?></option>	
					<?php
				}
			}
			else
			{
				?>
				<option value="">No hay agentes del tipo solicitado</option>
				<?php
			}
		}
		public function selectTipoAgente($tipo)
		{
			$query = "SELECT 
						T.id_tipoagente,
						T.tipo_agente
					FROM T_TipoAgente AS T
					WHERE T.id_tipoagente = :tipo";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":tipo",$tipo);
			$stmt->execute();
			if($stmt->rowCount()>0)
			{
				while($row=$stmt->fetch(PDO::FETCH_ASSOC))
				{
					?>
					<option value="<?php print($row['id_tipoagente']); ?>"><?php print($row['tipo_agente']); ?></option>	
					<?php
				}
			}
			else
			{
				?>
				<option value="">No hay agentes del tipo solicitado</option>
				<?php
			}
		}
		public function selectTipoAgenteUpdate($tipo)
		{
			$query = "SELECT 
						T.id_tipoagente,
						T.tipo_agente
					FROM T_TipoAgente AS T
					INNER JOIN T_Subproducto_TipoAgente AS STA
					ON STA.id_tipoagente = T.id_tipoagente
					WHERE STA.id_subproducto = :tipo";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":tipo",$tipo);
			$stmt->execute();
			if($stmt->rowCount()>0)
			{
				while($row=$stmt->fetch(PDO::FETCH_ASSOC))
				{
					?>
					<option value="<?php print($row['id_tipoagente']); ?>"><?php print($row['tipo_agente']); ?></option>	
					<?php
				}
			}
			else
			{
				?>
				<option value="">No hay agentes del tipo solicitado</option>
				<?php
			}
		}
		public function selectAgentesUpdate($tipo)
		{
			$query = "SELECT 
						A.id_agente,
						A.nombre_agente,
						A.apellidos_agente
					FROM T_Agentes AS A
					INNER JOIN T_TipoAgente_Agente AS TAA
					ON TAA.id_agente = A.id_agente
					INNER JOIN T_Subproducto_TipoAgente AS STA
					ON STA.id_tipoagente = TAA.id_tipoagente
					WHERE STA.id_subproducto = :tipo";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":tipo",$tipo);
			$stmt->execute();
			if($stmt->rowCount()>0)
			{
				while($row=$stmt->fetch(PDO::FETCH_ASSOC))
				{
					?>
					<option value="<?php print($row['id_agente']); ?>"><?php print($row['nombre_agente'].' '.$row['apellidos_agente']); ?></option>	
					<?php
				}
			}
			else
			{
				?>
				<option value="">No hay agentes del tipo solicitado</option>
				<?php
			}
		}
		public function selectAgentesUpdateOtro($tipo)
		{
			$query = "SELECT 
						A.id_agente,
						A.nombre_agente,
						A.apellidos_agente
					FROM T_Agentes AS A";
			$stmt = $this->conn->prepare($query);
			$stmt->execute();
			if($stmt->rowCount()>0)
			{
				while($row=$stmt->fetch(PDO::FETCH_ASSOC))
				{
					?>
					<option value="<?php print($row['id_agente']); ?>"><?php print($row['nombre_agente'].' '.$row['apellidos_agente']); ?></option>	
					<?php
				}
			}
			else
			{
				?>
				<option value="">No hay agentes del tipo solicitado</option>
				<?php
			}
		}
		public function selectTipoAgenteUpdateOtro($tipo)
		{
			$query = "SELECT 
						T.id_tipoagente,
						T.tipo_agente
					FROM T_TipoAgente AS T";
			$stmt = $this->conn->prepare($query);
			$stmt->execute();
			if($stmt->rowCount()>0)
			{
				while($row=$stmt->fetch(PDO::FETCH_ASSOC))
				{
					?>
					<option value="<?php print($row['id_tipoagente']); ?>"><?php print($row['tipo_agente']); ?></option>	
					<?php
				}
			}
			else
			{
				?>
				<option value="">No hay agentes del tipo solicitado</option>
				<?php
			}
		}
		
		//VIEW VIATICOS
		public function viewViaticos($bitacoras)
		{
			$query = "SELECT
					id_del_evento,
					fecha_entrega_viaticos,
					transportacion_local,
					alimentos,
					hospedaje,
					no_deducibles,
					SUM(transportacion_local+alimentos+hospedaje+no_deducibles) AS subtotal1_sin,
					SUM(transportacion_local+alimentos+hospedaje)*0.16 AS iva1,
					SUM(transportacion_local+alimentos+hospedaje+no_deducibles)+(SUM(transportacion_local+alimentos+hospedaje)*0.16) AS total1_sin,
					desglose,
					transportacion_foranea,
					transportacion_foranea*0.16 AS iva2,
					transportacion_foranea+(transportacion_foranea*0.16) AS total2,
					anticipo_entregado,
					rembolso,
					gasto_admin,
					observaciones_viaticos
				FROM T_Detalle_Bitacora
				WHERE id_bitacora = :bitacoras
				GROUP BY id_detallebitacora";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":bitacoras",$bitacoras);
			$stmt->execute();
			if($stmt->rowCount()>0)
			{
				while($row=$stmt->fetch(PDO::FETCH_ASSOC))
				{
					if($row['gasto_admin'] == 1)
					{
						$total_cobrar = ($row['total1_sin']*0.02)+$row['transportacion_foranea']+$row['subtotal1_sin'];
					}
					else
					{
						$total_cobrar = $row['transportacion_foranea']+$row['subtotal1_sin'];
					}
					?>
					<tr>
						<td><?php print($row['id_del_evento']); ?></td>
						<td></td>
						<td><?php print($row['fecha_entrega_viaticos']); ?></td>
						<td>$<?php print(number_format($row['transportacion_local'],2)); ?></td>
						<td>$<?php print(number_format($row['alimentos'],2)); ?></td>
						<td>$<?php print(number_format($row['hospedaje'],2)); ?></td>
						<td>$<?php print(number_format($row['no_deducibles'],2)); ?></td>
						<td>$<?php print(number_format($row['subtotal1_sin'],2)); ?></td>
						<td>$<?php print(number_format($row['iva1'],2)); ?></td>
						<td>$<?php print(number_format($row['total1_sin'],2)); ?></td>
						<td>$<?php print(number_format($row['desglose'],2)); ?></td>
						<td>$<?php print(number_format($row['transportacion_foranea'],2)); ?></td>
						<td>$<?php print(number_format($row['iva2'],2)); ?></td>
						<td>$<?php print(number_format($row['total2'],2)); ?></td>
						<td>$<?php print(number_format($row['anticipo_entregado'],2)); ?></td>
						<td>$<?php print(number_format($row['rembolso'],2)); ?></td>
						<td>$<?php print(number_format($total_cobrar,2)); ?></td>
						<td><?php print($row['observaciones_viaticos']); ?></td>
					</tr>
	                <?php
				}
			}
			else
			{
			    ?>
		           <tr>
						<td>No hay información</td>
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
		//OTROS
		public function selectsTipoCobro()
		{
			$query = "SELECT * FROM T_TipoAnticipos";
			$stmt = $this->conn->prepare($query);
			$stmt->execute();
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				?>
				<option value="<?php print($row['id_adminanticipos']);?>"><?php print($row['tipo_anticipo']);?></option>
				<?php
			}
		}
		//VIEW ANTICIPOS
		public function selectAdminAnticipos($bitacoras)
		{
			$query = "SELECT 
				TA.tipo_anticipo,
				B.id_adminanticipos,
				SUM(DB.precio_bitacora) AS TOTAL,
				B.monto_anticipo
			FROM T_Bitacora AS B
			INNER JOIN T_Detalle_Bitacora AS DB
			ON DB.id_bitacora = B.id_bitacora
			INNER JOIN T_TipoAnticipos AS TA
			ON TA.id_adminanticipos = B.id_adminanticipos
			WHERE B.id_bitacora = :bitacoras";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":bitacoras",$bitacoras);
			$stmt->execute();
			$row=$stmt->fetch(PDO::FETCH_ASSOC);
			return $row;
		}
		public function selectAdminAnticiposMeses($bitacoras)
		{
			$query = "SELECT 
				IF(DB.id_mesejecucion=1,SUM(DB.precio_bitacora),0) AS Enero,
				IF(DB.id_mesejecucion=2,SUM(DB.precio_bitacora),0) AS Febrero,
				IF(DB.id_mesejecucion=3,SUM(DB.precio_bitacora),0) AS Marzo,
				IF(DB.id_mesejecucion=4,SUM(DB.precio_bitacora),0) AS Abril,
				IF(DB.id_mesejecucion=5,SUM(DB.precio_bitacora),0) AS Mayo,
				IF(DB.id_mesejecucion=6,SUM(DB.precio_bitacora),0) AS Junio,
				IF(DB.id_mesejecucion=7,SUM(DB.precio_bitacora),0) AS Julio,
				IF(DB.id_mesejecucion=8,SUM(DB.precio_bitacora),0) AS Agosto,
				IF(DB.id_mesejecucion=9,SUM(DB.precio_bitacora),0) AS Septiembre,
				IF(DB.id_mesejecucion=10,SUM(DB.precio_bitacora),0) AS Octubre,
				IF(DB.id_mesejecucion=11,SUM(DB.precio_bitacora),0) AS Noviembre,
				IF(DB.id_mesejecucion=12,SUM(DB.precio_bitacora),0) AS Diciembre
			FROM T_Bitacora AS B
			INNER JOIN T_Detalle_Bitacora AS DB
			ON DB.id_bitacora = B.id_bitacora
			WHERE B.id_bitacora = :bitacoras";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":bitacoras",$bitacoras);
			$stmt->execute();
			$row=$stmt->fetch(PDO::FETCH_ASSOC);
			return $row;
		}
		//GETS
		public function getEstados()
		{
			$query = "SELECT 
						id_estado,
						estado
					FROM T_Estado";
			$stmt = $this->conn->prepare($query);
			$stmt->execute();
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				?>
				<option value="<?php print($row['id_estado']);?>"><?php print($row['estado']);?></option>
				<?php
			}
		}
		//VIEW BITACORA
		//TERRITORIO
		public function viewOIDirector($zona)
		{
			$query = "SELECT 
				B.id_bitacora,
				C.id_zona,
				B.nombre_bitacora,
				C.nombre_cliente,
				B.ultima_modificacion,
				SUM(DB.precio_bitacora) AS Ingresos,
				SUM(DB.costo_bitacora) AS Costos,
				SUM(DB.precio_bitacora*DB.gasto_directo) AS Gasto,
				SUM(DB.precio_bitacora-(DB.costo_bitacora+(DB.precio_bitacora*DB.gasto_directo))) AS Margen,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatusejecucion = 1) AS Ejecutado,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatusejecucion = 2) AS PorEjecutar,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatusfacturacion = 1) AS Facturado,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatusfacturacion = 2) AS PorFacturar,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatuscobranza = 1) AS Cobrado,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatuscobranza = 2) AS PorCobrar,
				(SELECT SUM(DB1.costo_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatuscosto = 1) AS Pagado,
				(SELECT SUM(DB1.costo_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatuscosto = 2) AS PorPagar
				FROM T_Bitacora AS B
				INNER JOIN T_Detalle_Bitacora AS DB
				ON DB.id_bitacora = B.id_bitacora
				INNER JOIN T_Subproductos AS S
				ON S.id_subproducto = DB.id_subproducto
				INNER JOIN T_Clientes as C
				ON B.id_cliente = C.id_cliente
				INNER JOIN T_TerritorioZona AS TZ
				ON TZ.id_zona = C.id_zona
				WHERE TZ.id_territorio = :zona
				GROUP BY B.id_cliente, B.id_bitacora";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":zona",$zona);
			$stmt->execute();
			if($stmt->rowCount()>0)
			{
				while($row=$stmt->fetch(PDO::FETCH_ASSOC))
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewBitacora.php?oi=<?php print($row['id_bitacora']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td style="text-align: center"><a href="updateBitacoraForm.php?OI=<?php print($row['id_bitacora']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<td style="text-align: center"><a href="adminAnticipos.php?bitacora=<?php print($row['id_bitacora']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario3.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td style="text-align: center"><a href="Solicitud-Facturas.php?bitacora=<?php print($row['id_bitacora']); ?>"><i class="glyph-icon icon-file"></i></a></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td>Unidad <?php print($row['id_zona']); ?></td>
						<td><?php print($row['ultima_modificacion']); ?></td>
						<td><?php print($row['nombre_bitacora']); ?></td>
						<td></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td></td>
						<td>$<?php print(number_format($row['Ejecutado'],2)); ?></td>
						<td>$<?php print(number_format($row['PorEjecutar'],2)); ?></td>
						<td>$<?php print(number_format($row['Ejecutado']-$row['PorEjecutar'],2)); ?></td>
						<td></td>
						<td>$<?php print(number_format($row['Facturado'],2)); ?></td>
						<td>$<?php print(number_format($row['PorFacturar'],2)); ?></td>
						<td>$<?php print(number_format($row['Facturado']-$row['PorFacturar'],2)); ?></td>
						<td></td>
						<td>$<?php print(number_format($row['Cobrado'],2)); ?></td>
						<td>$<?php print(number_format($row['PorCobrar'],2)); ?></td>
						<td>$<?php print(number_format($row['Cobrado']-$row['PorCobrar'],2)); ?></td>
						<td></td>
						<td>$<?php print(number_format($row['Pagado'],2)); ?></td>
						<td>$<?php print(number_format($row['PorPagar'],2)); ?></td>
						<td>$<?php print(number_format($row['Pagado']-$row['PorPagar'],2)); ?></td>
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
		//COORDINADOR
		public function viewOICoordinador($zona)
		{
			$query = "SELECT 
				B.id_bitacora,
				B.nombre_bitacora,
				C.nombre_cliente,
				B.ultima_modificacion,
				SUM(DB.precio_bitacora) AS Ingresos,
				SUM(DB.costo_bitacora) AS Costos,
				SUM(DB.precio_bitacora*DB.gasto_directo) AS Gasto,
				SUM(DB.precio_bitacora-(DB.costo_bitacora+(DB.precio_bitacora*DB.gasto_directo))) AS Margen,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatusejecucion = 1) AS Ejecutado,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatusejecucion = 2) AS PorEjecutar,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatusfacturacion = 1) AS Facturado,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatusfacturacion = 2) AS PorFacturar,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatuscobranza = 1) AS Cobrado,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatuscobranza = 2) AS PorCobrar,
				(SELECT SUM(DB1.costo_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatuscosto = 1) AS Pagado,
				(SELECT SUM(DB1.costo_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatuscosto = 2) AS PorPagar
				FROM T_Bitacora AS B
				INNER JOIN T_Detalle_Bitacora AS DB
				ON DB.id_bitacora = B.id_bitacora
				INNER JOIN T_Subproductos AS S
				ON S.id_subproducto = DB.id_subproducto
				INNER JOIN T_Clientes as C
				ON B.id_cliente = C.id_cliente
				WHERE C.id_zona = :zona
				GROUP BY B.id_cliente, B.id_bitacora";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":zona",$zona);
			$stmt->execute();
			if($stmt->rowCount()>0)
			{
				while($row=$stmt->fetch(PDO::FETCH_ASSOC))
				{
					?>
					<tr>
					       <td><?php print($row['nombre_cliente']); ?></td>
						<td style="text-align: center"><a href="viewBitacora-Coordinador.php?oi=<?php print($row['id_bitacora']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td style="text-align: center"><a href="updateBitacoraForm-Coordinador.php?OI=<?php print($row['id_bitacora']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<td style="text-align: center"><a href="SolicitudViaticos-Coordinador.php?idBitacora=<?php print($row['id_bitacora']); ?>"><i class="glyph-icon icon-file"></i></a></td>
						<td style="text-align: center"><a href="adminAnticipos-Coordinador.php?bitacora=<?php print($row['id_bitacora']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td style="text-align: center"><a href="adminViaticos-Coordinador.php?bitacora=<?php print($row['id_bitacora']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td style="text-align: center"><a href="Solicitud-Facturas-Coordinador.php?bitacora=<?php print($row['id_bitacora']); ?>&zona=<?php print($zona);?>"><i class="glyph-icon icon-file"></i></a></td>
						<td><?php print($row['ultima_modificacion']); ?></td>
						<td><?php print($row['nombre_bitacora']); ?></td>
						<td></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td></td>
						<td>$<?php print(number_format($row['Ejecutado'],2)); ?></td>
						<td>$<?php print(number_format($row['PorEjecutar'],2)); ?></td>
						<td>$<?php print(number_format($row['Ejecutado']-$row['PorEjecutar'],2)); ?></td>
						<td></td>
						<td>$<?php print(number_format($row['Facturado'],2)); ?></td>
						<td>$<?php print(number_format($row['PorFacturar'],2)); ?></td>
						<td>$<?php print(number_format($row['Facturado']-$row['PorFacturar'],2)); ?></td>
						<td></td>
						<td>$<?php print(number_format($row['Cobrado'],2)); ?></td>
						<td>$<?php print(number_format($row['PorCobrar'],2)); ?></td>
						<td>$<?php print(number_format($row['Cobrado']-$row['PorCobrar'],2)); ?></td>
						<td></td>
						<td>$<?php print(number_format($row['Pagado'],2)); ?></td>
						<td>$<?php print(number_format($row['PorPagar'],2)); ?></td>
						<td>$<?php print(number_format($row['Pagado']-$row['PorPagar'],2)); ?></td>
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
		public function viewOIAVEFO($zona)
		{
			$query = "SELECT 
				B.id_bitacora,
				C.nombre_cliente,
				B.ultima_modificacion,
				SUM(DB.precio_bitacora) AS Ingresos,
				SUM(DB.costo_bitacora) AS Costos,
				SUM(DB.precio_bitacora*DB.gasto_directo) AS Gasto,
				SUM(DB.precio_bitacora-(DB.costo_bitacora+(DB.precio_bitacora*DB.gasto_directo))) AS Margen,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatusejecucion = 1) AS Ejecutado,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatusejecucion = 2) AS PorEjecutar,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatusfacturacion = 1) AS Facturado,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatusfacturacion = 2) AS PorFacturar,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatuscobranza = 1) AS Cobrado,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatuscobranza = 2) AS PorCobrar,
				(SELECT SUM(DB1.costo_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatuscosto = 1) AS Pagado,
				(SELECT SUM(DB1.costo_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatuscosto = 2) AS PorPagar
				FROM T_Bitacora AS B
				INNER JOIN T_Detalle_Bitacora AS DB
				ON DB.id_bitacora = B.id_bitacora
				INNER JOIN T_Subproductos AS S
				ON S.id_subproducto = DB.id_subproducto
				INNER JOIN T_Clientes as C
				ON B.id_cliente = C.id_cliente
				WHERE C.id_zona = :zona
				GROUP BY B.id_cliente, B.id_bitacora";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":zona",$zona);
			$stmt->execute();
			if($stmt->rowCount()>0)
			{
				if($zona == 7)
				{
					while($row=$stmt->fetch(PDO::FETCH_ASSOC))
					{
						?>
						<tr>
							<td style="text-align: center"><a href="viewBitacora-AVEFO.php?oi=<?php print($row['id_bitacora']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
							<td><?php print($row['nombre_cliente']); ?></td>
							<td><?php print($row['ultima_modificacion']); ?></td>
							<td></td>
							<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
							<td>$<?php print(number_format($row['Costos'],2)); ?></td>
							<td>$<?php print(number_format($row['Costos'],2)); ?></td>
							<td>$<?php print(number_format($row['Margen'],2)); ?></td>
							<td></td>
							<td>$<?php print(number_format($row['Ejecutado'],2)); ?></td>
							<td>$<?php print(number_format($row['PorEjecutar'],2)); ?></td>
							<td>$<?php print(number_format($row['Ejecutado']-$row['PorEjecutar'],2)); ?></td>
							<td></td>
							<td>$<?php print(number_format($row['Facturado'],2)); ?></td>
							<td>$<?php print(number_format($row['PorFacturar'],2)); ?></td>
							<td>$<?php print(number_format($row['Facturado']-$row['PorFacturar'],2)); ?></td>
							<td></td>
							<td>$<?php print(number_format($row['Cobrado'],2)); ?></td>
							<td>$<?php print(number_format($row['PorCobrar'],2)); ?></td>
							<td>$<?php print(number_format($row['Cobrado']-$row['PorCobrar'],2)); ?></td>
							<td></td>
							<td>$<?php print(number_format($row['Pagado'],2)); ?></td>
							<td>$<?php print(number_format($row['PorPagar'],2)); ?></td>
							<td>$<?php print(number_format($row['Pagado']-$row['PorPagar'],2)); ?></td>
						</tr>
						<?php
					}
				}
				else
				{
					while($row=$stmt->fetch(PDO::FETCH_ASSOC))
					{
						?>
						<tr>
							<td style="text-align: center"><a href="viewBitacora-AVEFO.php?oi=<?php print($row['id_bitacora']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
							<td><?php print($row['nombre_cliente']); ?></td>
							<td><?php print($row['ultima_modificacion']); ?></td>
							<td></td>
							<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
							<td>$<?php print(number_format($row['Costos'],2)); ?></td>
							<td>$<?php print(number_format($row['Margen'],2)); ?></td>
							<td></td>
							<td>$<?php print(number_format($row['Ejecutado'],2)); ?></td>
							<td>$<?php print(number_format($row['PorEjecutar'],2)); ?></td>
							<td>$<?php print(number_format($row['Ejecutado']-$row['PorEjecutar'],2)); ?></td>
							<td></td>
							<td>$<?php print(number_format($row['Facturado'],2)); ?></td>
							<td>$<?php print(number_format($row['PorFacturar'],2)); ?></td>
							<td>$<?php print(number_format($row['Facturado']-$row['PorFacturar'],2)); ?></td>
							<td></td>
							<td>$<?php print(number_format($row['Cobrado'],2)); ?></td>
							<td>$<?php print(number_format($row['PorCobrar'],2)); ?></td>
							<td>$<?php print(number_format($row['Cobrado']-$row['PorCobrar'],2)); ?></td>
							<td></td>
							<td>$<?php print(number_format($row['Pagado'],2)); ?></td>
							<td>$<?php print(number_format($row['PorPagar'],2)); ?></td>
							<td>$<?php print(number_format($row['Pagado']-$row['PorPagar'],2)); ?></td>
						</tr>
						<?php
					}
				}
				
			}
			else
			{
			    if($zona == 7)
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
		}
		public function viewOIAVEFOTotales($zona)
		{
			$query = "SELECT 
				B.id_bitacora,
				C.nombre_cliente,
				B.ultima_modificacion,
				SUM(DB.precio_bitacora) AS Ingresos,
				SUM(DB.costo_bitacora) AS Costos,
				SUM(DB.precio_bitacora*DB.gasto_directo) AS Gasto,
				SUM(DB.precio_bitacora-(DB.costo_bitacora+(DB.precio_bitacora*DB.gasto_directo))) AS Margen,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 INNER JOIN T_Bitacora AS B1 ON B1.id_bitacora = DB1. id_bitacora INNER JOIN T_Clientes AS C1 ON C1.id_cliente = B1.id_cliente WHERE C1.id_zona = C.id_zona AND DB1.id_estatusejecucion = 1) AS Ejecutado,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 INNER JOIN T_Bitacora AS B1 ON B1.id_bitacora = DB1. id_bitacora INNER JOIN T_Clientes AS C1 ON C1.id_cliente = B1.id_cliente WHERE C1.id_zona = C.id_zona  AND DB1.id_estatusejecucion = 2) AS PorEjecutar,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 INNER JOIN T_Bitacora AS B1 ON B1.id_bitacora = DB1. id_bitacora INNER JOIN T_Clientes AS C1 ON C1.id_cliente = B1.id_cliente WHERE C1.id_zona = C.id_zona  AND DB1.id_estatusfacturacion = 1) AS Facturado,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 INNER JOIN T_Bitacora AS B1 ON B1.id_bitacora = DB1. id_bitacora INNER JOIN T_Clientes AS C1 ON C1.id_cliente = B1.id_cliente WHERE C1.id_zona = C.id_zona  AND DB1.id_estatusfacturacion = 2) AS PorFacturar,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 INNER JOIN T_Bitacora AS B1 ON B1.id_bitacora = DB1. id_bitacora INNER JOIN T_Clientes AS C1 ON C1.id_cliente = B1.id_cliente WHERE C1.id_zona = C.id_zona  AND DB1.id_estatuscobranza = 1) AS Cobrado,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 INNER JOIN T_Bitacora AS B1 ON B1.id_bitacora = DB1. id_bitacora INNER JOIN T_Clientes AS C1 ON C1.id_cliente = B1.id_cliente WHERE C1.id_zona = C.id_zona  AND DB1.id_estatuscobranza = 2) AS PorCobrar,
				(SELECT SUM(DB1.costo_bitacora) FROM T_Detalle_Bitacora AS DB1 INNER JOIN T_Bitacora AS B1 ON B1.id_bitacora = DB1. id_bitacora INNER JOIN T_Clientes AS C1 ON C1.id_cliente = B1.id_cliente WHERE C1.id_zona = C.id_zona  AND DB1.id_estatuscosto = 1) AS Pagado,
				(SELECT SUM(DB1.costo_bitacora) FROM T_Detalle_Bitacora AS DB1 INNER JOIN T_Bitacora AS B1 ON B1.id_bitacora = DB1. id_bitacora INNER JOIN T_Clientes AS C1 ON C1.id_cliente = B1.id_cliente WHERE C1.id_zona = C.id_zona  AND DB1.id_estatuscosto = 2) AS PorPagar
				FROM T_Bitacora AS B
				INNER JOIN T_Detalle_Bitacora AS DB
				ON DB.id_bitacora = B.id_bitacora
				INNER JOIN T_Subproductos AS S
				ON S.id_subproducto = DB.id_subproducto
				INNER JOIN T_Clientes as C
				ON B.id_cliente = C.id_cliente
				WHERE C.id_zona = :zona
				GROUP BY C.id_zona";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":zona",$zona);
			$stmt->execute();
			if($stmt->rowCount()>0)
			{
				if($zona == 7)
				{
					while($row=$stmt->fetch(PDO::FETCH_ASSOC))
					{
						?>
						<tr>
							<th>Totales</th>
							<th></th>
							<th></th>
							<th></th>
							<th>$<?php print(number_format($row['Ingresos'],2)); ?></th>
							<th>$<?php print(number_format($row['Costos'],2)); ?></th>
							<th>$<?php print(number_format($row['Gasto'],2)); ?></th>
							<th>$<?php print(number_format($row['Margen'],2)); ?></th>
							<th></th>
							<th>$<?php print(number_format($row['Ejecutado'],2)); ?></th>
							<th>$<?php print(number_format($row['PorEjecutar'],2)); ?></th>
							<th>$<?php print(number_format($row['Ejecutado']-$row['PorEjecutar'],2)); ?></th>
							<th></th>
							<th>$<?php print(number_format($row['Facturado'],2)); ?></th>
							<th>$<?php print(number_format($row['PorFacturar'],2)); ?></th>
							<th>$<?php print(number_format($row['Facturado']-$row['PorFacturar'],2)); ?></th>
							<th></th>
							<th>$<?php print(number_format($row['Cobrado'],2)); ?></th>
							<th>$<?php print(number_format($row['PorCobrar'],2)); ?></th>
							<th>$<?php print(number_format($row['Cobrado']-$row['PorCobrar'],2)); ?></th>
							<th></th>
							<th>$<?php print(number_format($row['Pagado'],2)); ?></th>
							<th>$<?php print(number_format($row['PorPagar'],2)); ?></th>
							<th>$<?php print(number_format($row['Pagado']-$row['PorPagar'],2)); ?></th>
						</tr>
						<?php
					}
				}
				else
				{
					while($row=$stmt->fetch(PDO::FETCH_ASSOC))
					{
						?>
						<tr>
							<th>Totales</th>
							<th></th>
							<th></th>
							<th></th>
							<th>$<?php print(number_format($row['Ingresos'],2)); ?></th>
							<th>$<?php print(number_format($row['Costos'],2)); ?></th>
							<th>$<?php print(number_format($row['Margen'],2)); ?></th>
							<th></th>
							<th>$<?php print(number_format($row['Ejecutado'],2)); ?></th>
							<th>$<?php print(number_format($row['PorEjecutar'],2)); ?></th>
							<th>$<?php print(number_format($row['Ejecutado']-$row['PorEjecutar'],2)); ?></th>
							<th></th>
							<th>$<?php print(number_format($row['Facturado'],2)); ?></th>
							<th>$<?php print(number_format($row['PorFacturar'],2)); ?></th>
							<th>$<?php print(number_format($row['Facturado']-$row['PorFacturar'],2)); ?></th>
							<th></th>
							<th>$<?php print(number_format($row['Cobrado'],2)); ?></th>
							<th>$<?php print(number_format($row['PorCobrar'],2)); ?></th>
							<th>$<?php print(number_format($row['Cobrado']-$row['PorCobrar'],2)); ?></th>
							<th></th>
							<th>$<?php print(number_format($row['Pagado'],2)); ?></th>
							<th>$<?php print(number_format($row['PorPagar'],2)); ?></th>
							<th>$<?php print(number_format($row['Pagado']-$row['PorPagar'],2)); ?></th>
						</tr>
						<?php
					}
				}
				
			}
			else
			{
			     if($zona == 7)
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
		}
		//VISTA CORPORATIVA
		public function viewOIAVEFOC()
		{
			$query = "SELECT 
				B.id_bitacora,
				C.nombre_cliente,
				B.ultima_modificacion,
				SUM(DB.precio_bitacora) AS Ingresos,
				SUM(DB.costo_bitacora) AS Costos,
				SUM(DB.precio_bitacora*DB.gasto_directo) AS Gasto,
				SUM(DB.precio_bitacora-(DB.costo_bitacora+(DB.precio_bitacora*DB.gasto_directo))) AS Margen,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatusejecucion = 1) AS Ejecutado,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatusejecucion = 2) AS PorEjecutar,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatusfacturacion = 1) AS Facturado,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatusfacturacion = 2) AS PorFacturar,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatuscobranza = 1) AS Cobrado,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatuscobranza = 2) AS PorCobrar,
				(SELECT SUM(DB1.costo_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatuscosto = 1) AS Pagado,
				(SELECT SUM(DB1.costo_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatuscosto = 2) AS PorPagar
				FROM T_Bitacora AS B
				INNER JOIN T_Detalle_Bitacora AS DB
				ON DB.id_bitacora = B.id_bitacora
				INNER JOIN T_Subproductos AS S
				ON S.id_subproducto = DB.id_subproducto
				INNER JOIN T_Clientes as C
				ON B.id_cliente = C.id_cliente
				WHERE NOT C.id_zona = 4
				GROUP BY B.id_cliente";
			$stmt = $this->conn->prepare($query);
			$stmt->execute();
			if($stmt->rowCount()>0)
			{
				while($row=$stmt->fetch(PDO::FETCH_ASSOC))
				{
						?>
						<tr>
							<td style="text-align: center"><a href="viewBitacora-AVEFO.php?oi=<?php print($row['id_bitacora']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
							<td><?php print($row['nombre_cliente']); ?></td>
							<td><?php print($row['ultima_modificacion']); ?></td>
							<td></td>
							<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
							<td>$<?php print(number_format($row['Costos'],2)); ?></td>
							<td>$<?php print(number_format($row['Gasto'],2)); ?></td>
							<td>$<?php print(number_format($row['Margen'],2)); ?></td>
							<td></td>
							<td>$<?php print(number_format($row['Ejecutado'],2)); ?></td>
							<td>$<?php print(number_format($row['PorEjecutar'],2)); ?></td>
							<td>$<?php print(number_format($row['Ejecutado']-$row['PorEjecutar'],2)); ?></td>
							<td></td>
							<td>$<?php print(number_format($row['Facturado'],2)); ?></td>
							<td>$<?php print(number_format($row['PorFacturar'],2)); ?></td>
							<td>$<?php print(number_format($row['Facturado']-$row['PorFacturar'],2)); ?></td>
							<td></td>
							<td>$<?php print(number_format($row['Cobrado'],2)); ?></td>
							<td>$<?php print(number_format($row['PorCobrar'],2)); ?></td>
							<td>$<?php print(number_format($row['Cobrado']-$row['PorCobrar'],2)); ?></td>
							<td></td>
							<td>$<?php print(number_format($row['Pagado'],2)); ?></td>
							<td>$<?php print(number_format($row['PorPagar'],2)); ?></td>
							<td>$<?php print(number_format($row['Pagado']-$row['PorPagar'],2)); ?></td>
						</tr>
						<?php
				}
			}
			else
			{
			    ?>
		            <tr>
			            <td colspan="23">No hay informacion disponible</td>
		            </tr>
				<?php
			}
		}
		public function viewOIAVEFOTotalesC()
		{
			$query = "SELECT 
				B.id_bitacora,
				C.nombre_cliente,
				B.ultima_modificacion,
				SUM(DB.precio_bitacora) AS Ingresos,
				SUM(DB.costo_bitacora) AS Costos,
				SUM(DB.precio_bitacora*DB.gasto_directo) AS Gasto,
				SUM(DB.precio_bitacora-(DB.costo_bitacora+(DB.precio_bitacora*DB.gasto_directo))) AS Margen,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 INNER JOIN T_Bitacora AS B1 ON B1.id_bitacora = DB1. id_bitacora INNER JOIN T_Clientes AS C1 ON C1.id_cliente = B1.id_cliente WHERE C1.id_zona != 4  AND DB1.id_estatusejecucion = 1) AS Ejecutado,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 INNER JOIN T_Bitacora AS B1 ON B1.id_bitacora = DB1. id_bitacora INNER JOIN T_Clientes AS C1 ON C1.id_cliente = B1.id_cliente WHERE C1.id_zona != 4 AND DB1.id_estatusejecucion = 2) AS PorEjecutar,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 INNER JOIN T_Bitacora AS B1 ON B1.id_bitacora = DB1. id_bitacora INNER JOIN T_Clientes AS C1 ON C1.id_cliente = B1.id_cliente WHERE C1.id_zona != 4  AND DB1.id_estatusfacturacion = 1) AS Facturado,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 INNER JOIN T_Bitacora AS B1 ON B1.id_bitacora = DB1. id_bitacora INNER JOIN T_Clientes AS C1 ON C1.id_cliente = B1.id_cliente WHERE C1.id_zona != 4  AND DB1.id_estatusfacturacion = 2) AS PorFacturar,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 INNER JOIN T_Bitacora AS B1 ON B1.id_bitacora = DB1. id_bitacora INNER JOIN T_Clientes AS C1 ON C1.id_cliente = B1.id_cliente WHERE C1.id_zona != 4 AND DB1.id_estatuscobranza = 1) AS Cobrado,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 INNER JOIN T_Bitacora AS B1 ON B1.id_bitacora = DB1. id_bitacora INNER JOIN T_Clientes AS C1 ON C1.id_cliente = B1.id_cliente WHERE C1.id_zona !=4 AND DB1.id_estatuscobranza = 2) AS PorCobrar,
				(SELECT SUM(DB1.costo_bitacora) FROM T_Detalle_Bitacora AS DB1 INNER JOIN T_Bitacora AS B1 ON B1.id_bitacora = DB1. id_bitacora INNER JOIN T_Clientes AS C1 ON C1.id_cliente = B1.id_cliente WHERE C1.id_zona != 4 AND DB1.id_estatuscosto = 1) AS Pagado,
				(SELECT SUM(DB1.costo_bitacora) FROM T_Detalle_Bitacora AS DB1 INNER JOIN T_Bitacora AS B1 ON B1.id_bitacora = DB1. id_bitacora INNER JOIN T_Clientes AS C1 ON C1.id_cliente = B1.id_cliente WHERE C1.id_zona != 4 AND DB1.id_estatuscosto = 2) AS PorPagar
				FROM T_Bitacora AS B
				INNER JOIN T_Detalle_Bitacora AS DB
				ON DB.id_bitacora = B.id_bitacora
				INNER JOIN T_Subproductos AS S
				ON S.id_subproducto = DB.id_subproducto
				INNER JOIN T_Clientes as C
				ON B.id_cliente = C.id_cliente
				WHERE NOT C.id_zona = 4";
			$stmt = $this->conn->prepare($query);
			$stmt->execute();
			if($stmt->rowCount()>0)
			{
				while($row=$stmt->fetch(PDO::FETCH_ASSOC))
				{
					?>
					<tr>
						<th>Totales</th>
						<th></th>
						<th></th>
						<th></th>
						<th>$<?php print(number_format($row['Ingresos'],2)); ?></th>
						<th>$<?php print(number_format($row['Costos'],2)); ?></th>
						<th>$<?php print(number_format($row['Gasto'],2)); ?></th>
						<th>$<?php print(number_format($row['Margen'],2)); ?></th>
						<th></th>
						<th>$<?php print(number_format($row['Ejecutado'],2)); ?></th>
						<th>$<?php print(number_format($row['PorEjecutar'],2)); ?></th>
						<th>$<?php print(number_format($row['Ejecutado']-$row['PorEjecutar'],2)); ?></th>
						<th></th>
						<th>$<?php print(number_format($row['Facturado'],2)); ?></th>
						<th>$<?php print(number_format($row['PorFacturar'],2)); ?></th>
						<th>$<?php print(number_format($row['Facturado']-$row['PorFacturar'],2)); ?></th>
						<th></th>
						<th>$<?php print(number_format($row['Cobrado'],2)); ?></th>
						<th>$<?php print(number_format($row['PorCobrar'],2)); ?></th>
						<th>$<?php print(number_format($row['Cobrado']-$row['PorCobrar'],2)); ?></th>
						<th></th>
						<th>$<?php print(number_format($row['Pagado'],2)); ?></th>
						<th>$<?php print(number_format($row['PorPagar'],2)); ?></th>
						<th>$<?php print(number_format($row['Pagado']-$row['PorPagar'],2)); ?></th>
					</tr>
					<?php
				}
			}
			else
			{
			    ?>
		            <tr>
			            <td colspan="23">No hay informacion disponible</td>
		            </tr>
				<?php
			}
		}
		//ZONA LIDER
		public function viewOI($zona)
		{
			$query = "SELECT 
				B.id_bitacora,
				B.nombre_bitacora,
				C.nombre_cliente,
				B.ultima_modificacion,
				SUM(DB.precio_bitacora) AS Ingresos,
				SUM(DB.costo_bitacora) AS Costos,
				SUM(DB.precio_bitacora*DB.gasto_directo) AS Gasto,
				SUM(DB.precio_bitacora-(DB.costo_bitacora+(DB.precio_bitacora*DB.gasto_directo))) AS Margen,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatusejecucion = 1) AS Ejecutado,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatusejecucion = 2) AS PorEjecutar,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatusfacturacion = 1) AS Facturado,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatusfacturacion = 2) AS PorFacturar,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatuscobranza = 1) AS Cobrado,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatuscobranza = 2) AS PorCobrar,
				(SELECT SUM(DB1.costo_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatuscosto = 1) AS Pagado,
				(SELECT SUM(DB1.costo_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatuscosto = 2) AS PorPagar
				FROM T_Bitacora AS B
				INNER JOIN T_Detalle_Bitacora AS DB
				ON DB.id_bitacora = B.id_bitacora
				INNER JOIN T_Subproductos AS S
				ON S.id_subproducto = DB.id_subproducto
				INNER JOIN T_Clientes as C
				ON B.id_cliente = C.id_cliente
				WHERE C.id_zona = :zona
				GROUP BY B.id_cliente, B.id_bitacora";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":zona",$zona);
			$stmt->execute();
			if($stmt->rowCount()>0)
			{
				while($row=$stmt->fetch(PDO::FETCH_ASSOC))
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewBitacora.php?oi=<?php print($row['id_bitacora']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td style="text-align: center"><a href="updateBitacoraForm.php?OI=<?php print($row['id_bitacora']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<td style="text-align: center"><a href="adminAnticipos.php?bitacora=<?php print($row['id_bitacora']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td style="text-align: center"><a href="SolicitudViaticos.php?idBitacora=<?php print($row['id_bitacora']); ?>"><i class="glyph-icon icon-file"></i></a></td>
						<td style="text-align: center"><a href="adminViaticos.php?bitacora=<?php print($row['id_bitacora']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario3.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td style="text-align: center"><a href="Solicitud-Facturas.php?bitacora=<?php print($row['id_bitacora']); ?>"><i class="glyph-icon icon-file"></i></a></td>
						<!--<td style="text-align: center"><a href="Prefactura.php?bitacora=<?php print($row['id_bitacora']); ?>"><i class="glyph-icon icon-file"></i></a></td>-->
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['ultima_modificacion']); ?></td>
						<td><?php print($row['nombre_bitacora']); ?></td>
						<td></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td></td>
						<td>$<?php print(number_format($row['Ejecutado'],2)); ?></td>
						<td>$<?php print(number_format($row['PorEjecutar'],2)); ?></td>
						<td>$<?php print(number_format($row['Ejecutado']-$row['PorEjecutar'],2)); ?></td>
						<td></td>
						<td>$<?php print(number_format($row['Facturado'],2)); ?></td>
						<td>$<?php print(number_format($row['PorFacturar'],2)); ?></td>
						<td>$<?php print(number_format($row['Facturado']-$row['PorFacturar'],2)); ?></td>
						<td></td>
						<td>$<?php print(number_format($row['Cobrado'],2)); ?></td>
						<td>$<?php print(number_format($row['PorCobrar'],2)); ?></td>
						<td>$<?php print(number_format($row['Cobrado']-$row['PorCobrar'],2)); ?></td>
						<td></td>
						<td>$<?php print(number_format($row['Pagado'],2)); ?></td>
						<td>$<?php print(number_format($row['PorPagar'],2)); ?></td>
						<td>$<?php print(number_format($row['Pagado']-$row['PorPagar'],2)); ?></td>
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
		public function viewOITotales($zona)
		{
			$query = "SELECT 
				B.id_bitacora,
				B.ultima_modificacion,
				SUM(DB.precio_bitacora) AS Ingresos,
				SUM(DB.costo_bitacora) AS Costos,
				SUM(DB.precio_bitacora*DB.gasto_directo) AS Gasto,
				SUM(DB.precio_bitacora-(DB.costo_bitacora+(DB.precio_bitacora*DB.gasto_directo))) AS Margen,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatusejecucion = 1) AS Ejecutado,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatusejecucion = 2) AS PorEjecutar,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatusfacturacion = 1) AS Facturado,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatusfacturacion = 2) AS PorFacturar,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatuscobranza = 1) AS Cobrado,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatuscobranza = 2) AS PorCobrar,
				(SELECT SUM(DB1.costo_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatuscosto = 1) AS Pagado,
				(SELECT SUM(DB1.costo_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatuscosto = 2) AS PorPagar
				FROM T_Bitacora AS B
				INNER JOIN T_Detalle_Bitacora AS DB
				ON DB.id_bitacora = B.id_bitacora
				INNER JOIN T_Subproductos AS S
				ON S.id_subproducto = DB.id_subproducto
				INNER JOIN T_Clientes as C
				ON B.id_cliente = C.id_cliente
				WHERE C.id_zona = :zona
				GROUP BY C.id_zona";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":zona",$zona);
			$stmt->execute();
			if($stmt->rowCount()>0)
			{
				if($zona == 7)
				{
					while($row=$stmt->fetch(PDO::FETCH_ASSOC))
					{
						?>
						<tr>
							<th>Totales</th>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<th>$<?php print(number_format($row['Ingresos'],2)); ?></th>
							<th>$<?php print(number_format($row['Costos'],2)); ?></th>
							<th>$<?php print(number_format($row['Gasto'],2)); ?></th>
							<th>$<?php print(number_format($row['Margen'],2)); ?></th>
							<th></th>
							<th>$<?php print(number_format($row['Ejecutado'],2)); ?></th>
							<th>$<?php print(number_format($row['PorEjecutar'],2)); ?></th>
							<th>$<?php print(number_format($row['Ejecutado']-$row['PorEjecutar'],2)); ?></th>
							<th></th>
							<th>$<?php print(number_format($row['Facturado'],2)); ?></th>
							<th>$<?php print(number_format($row['PorFacturar'],2)); ?></th>
							<th>$<?php print(number_format($row['Facturado']-$row['PorFacturar'],2)); ?></th>
							<th></th>
							<th>$<?php print(number_format($row['Cobrado'],2)); ?></th>
							<th>$<?php print(number_format($row['PorCobrar'],2)); ?></th>
							<th>$<?php print(number_format($row['Cobrado']-$row['PorCobrar'],2)); ?></th>
							<th></th>
							<th>$<?php print(number_format($row['Pagado'],2)); ?></th>
							<th>$<?php print(number_format($row['PorPagar'],2)); ?></th>
							<th>$<?php print(number_format($row['Pagado']-$row['PorPagar'],2)); ?></th>
						</tr>
						<?php
					}
				}
				else
				{
					while($row=$stmt->fetch(PDO::FETCH_ASSOC))
					{
						?>
						<tr>
							<th>Totales</th>
							<th></th>
							<th></th>
							<th></th>
							<th>$<?php print(number_format($row['Ingresos'],2)); ?></th>
							<th>$<?php print(number_format($row['Costos'],2)); ?></th>
							<th>$<?php print(number_format($row['Margen'],2)); ?></th>
							<th></th>
							<th>$<?php print(number_format($row['Ejecutado'],2)); ?></th>
							<th>$<?php print(number_format($row['PorEjecutar'],2)); ?></th>
							<th>$<?php print(number_format($row['Ejecutado']-$row['PorEjecutar'],2)); ?></th>
							<th></th>
							<th>$<?php print(number_format($row['Facturado'],2)); ?></th>
							<th>$<?php print(number_format($row['PorFacturar'],2)); ?></th>
							<th>$<?php print(number_format($row['Facturado']-$row['PorFacturar'],2)); ?></th>
							<th></th>
							<th>$<?php print(number_format($row['Cobrado'],2)); ?></th>
							<th>$<?php print(number_format($row['PorCobrar'],2)); ?></th>
							<th>$<?php print(number_format($row['Cobrado']-$row['PorCobrar'],2)); ?></th>
							<th></th>
							<th>$<?php print(number_format($row['Pagado'],2)); ?></th>
							<th>$<?php print(number_format($row['PorPagar'],2)); ?></th>
							<th>$<?php print(number_format($row['Pagado']-$row['PorPagar'],2)); ?></th>
						</tr>
						<?php
					}
				}
				
			}
			else
			{
			    ?>
		            <tr>
			            <td colspan="23">No hay informacion disponible</td>
		            </tr>
				<?php
			}
		}
		public function viewAllDetalleOI($oi)
		{
			$resultsAux = array();
			$stmt = $this->conn->prepare("SELECT 
				DB.id_detallebitacora,
				DB.version,
				DB.id_del_evento,
				DB.evento,
				DB.id_subproducto, 
				DB.tema,  
				DB.no_personas, 
				DB.id_nivel, 
				DB.id_tipoagente, 
				DB.id_agente, 
				DB.id_tipoequipo, 
				DB.id_tipomaterial, 
				DB.precio_bitacora, 
				DB.descuento, 
				DB.gasto_directo,
				(SELECT GROUP_CONCAT(A.nombre_agente,' ',A.apellidos_agente,',') FROM T_Detalle_Bitacora_Agente AS DBA INNER JOIN T_Agentes AS A ON A.id_agente = DBA.id_agente WHERE id_detallebitacora = DB.id_detallebitacora) AS agente,
				(SELECT GROUP_CONCAT(A.tipo_agente,',') FROM T_Detalle_Bitacora_Agente AS DBA INNER JOIN T_TipoAgente AS A ON A.id_tipoagente = DBA.id_tipoagente WHERE id_detallebitacora = DB.id_detallebitacora) AS tipoagente,
				(CASE WHEN DB.precio_bitacora = 0 THEN 0 ELSE DB.costo_bitacora/DB.precio_bitacora END) AS porcentajeCosto,
				(CASE WHEN DB.precio_bitacora = 0 THEN 0 ELSE DB.costo_honorarios/DB.precio_bitacora END) AS porcentajeHonorarios,
				(CASE WHEN DB.precio_bitacora = 0 THEN 0 ELSE DB.costo_materiales/DB.precio_bitacora END) AS porcentajeMateriales,
				(CASE WHEN DB.precio_bitacora = 0 THEN 0 ELSE (DB.precio_bitacora-DB.costo_bitacora)/DB.precio_bitacora END) AS porcentajeMargen,
				DB.precio_bitacora-DB.costo_bitacora AS margen,
				Sub.nombre_subproducto, 
				DB.costo_honorarios, 
				DB.costo_materiales, 
				DB.costo_bitacora, 
				Prod.nombre_producto, 
				Prod.id_producto,
				Fam.familia_producto, 
				Fam.id_familiaproducto,
				N.nivel, 
				TE.tipo_equipo, 
				TM.tipo_material
			FROM T_Detalle_Bitacora AS DB USE INDEX(id_detallebitacora,id_subproducto,id_nivel,id_tipoagente,id_agente,id_tipoequipo,id_tipomaterial,id_mesejecucion,id_estatusejecucion,id_mescosto,id_estatuscosto,id_estatusfacturacion,id_mesfacturacion,id_estatuscobranza,id_mescobranza)
			INNER JOIN T_Subproductos AS Sub ON DB.id_subproducto = Sub.id_subproducto
			INNER JOIN T_Productos AS Prod ON Sub.id_producto = Prod.id_producto
			INNER JOIN T_FamiliaProductos AS Fam ON Prod.id_familiaproducto = Fam.id_familiaproducto
			INNER JOIN T_Niveles AS N ON N.id_nivel = DB.id_nivel
			INNER JOIN T_TipoEquipo AS TE ON TE.id_tipoequipo = DB.id_tipoequipo
			INNER JOIN T_TipoMaterial AS TM ON TM.id_tipomaterial = DB.id_tipomaterial
			WHERE DB.id_bitacora = :oi
			ORDER BY DB.id_detallebitacora");
			$stmt->execute(array(':oi'=>$oi));
			$count1 = $stmt->rowCount();
			
			$stmt2 = $this->conn->prepare("SELECT 
				DB.id_detallebitacora,
				DB.id_mesejecucion, 
				DB.fecha_ejecucion, 
				DB.anomalia, 
				DB.id_estatusejecucion, 
				DB.id_mescosto, 
				DB.id_estatuscosto, 
				DB.id_estatusfacturacion, 
				DB.id_mesfacturacion,
				DB.numero_factura,
				DB.id_estatuscobranza, 
				DB.id_mescobranza,
				DB.fecha_cobranza,
				DB.sede_particular,
				DB.id_estado,
				E.estado,
				ME.mes_ejecucion, 
				EE.estatus_ejecucion, 
				MCS.mes_costo, 
				ECS.estatus_costo, 
				EF.estatus_facturacion, 
				MF.mes_facturacion, 
				EC.estatus_cobranza, 
				MC.mes_cobranza
			FROM T_Detalle_Bitacora AS DB
			INNER JOIN T_Mes_Ejecucion AS ME ON ME.id_mesejecucion = DB.id_mesejecucion
			INNER JOIN T_Estatus_Ejecucion AS EE ON EE.id_estatusejecucion = DB.id_estatusejecucion
			INNER JOIN T_Mes_Costo	AS MCS ON MCS.id_mescosto = DB.id_mescosto
			INNER JOIN T_Estatus_Costos AS ECS ON ECS.id_estatuscosto = DB.id_estatuscosto
			INNER JOIN T_Estatus_Facturacion AS EF ON EF.id_estatusfacturacion = DB.id_estatusfacturacion
			INNER JOIN T_Mes_Facturacion AS MF ON MF.id_mesfacturacion = DB.id_mesfacturacion
			INNER JOIN T_Estatus_Cobranza AS EC ON EC.id_estatuscobranza = DB.id_estatuscobranza
			INNER JOIN T_Mes_Cobranza AS MC USE INDEX(id_mescobranza) ON MC.id_mescobranza = DB.id_mescobranza
			INNER JOIN T_Estado AS E ON E.id_estado = DB.id_estado
			WHERE DB.id_bitacora = :oi
			ORDER BY DB.id_detallebitacora");
			$stmt2->execute(array(':oi'=>$oi));
			$count2 = $stmt2->rowCount();
			for($i = 0; $i <= $count1; $i++)
			{
				$rowAux=$stmt->fetch(PDO::FETCH_ASSOC);
				$rowAux2=$stmt2->fetch(PDO::FETCH_ASSOC);
				$resultsAux[$i] = $rowAux + $rowAux2; 
				//$resultsAux[$i] = $row2; 
			}
			$contador = 1;
			if($count1 == $count2)
			{
				foreach($resultsAux as $row)
				{
					if($row['id_subproducto'] != '' || $row['id_subproducto'] != 0)
					{
					?>
					<tr>
						<td><?php print($row['id_del_evento']); ?></td>
						<td><?php print($row['evento']); ?></td>
						<td><?php print($row['familia_producto']); ?></td>
						<td><?php print($row['nombre_producto']); ?></td>
						<td><?php print($row['nombre_subproducto']); ?></td>
						<td><?php print($row['tema']); ?></td>
						<td><?php print($row['no_personas']); ?></td>
						<td><?php print($row['nivel']); ?></td>
						<td><?php print($row['tipoagente']); ?></td>
						<td><?php print($row['agente']); ?></td>
						<td><?php print($row['tipo_equipo']); ?></td>
						<td><?php print($row['tipo_material']); ?></td>
						<td>$<?php print(number_format($row['precio_bitacora'],2)); ?></td>
						<td>$<?php print(number_format($row['costo_honorarios'],2)); ?></td>
						<td><?php print(round($row['porcentajeHonorarios']*100,2)); ?>%</td>
						<td>$<?php print(number_format($row['costo_materiales'],2)); ?></td>
						<td><?php print(round($row['porcentajeMateriales']*100,2)); ?>%</td>
						<td>$<?php print(number_format($row['costo_bitacora'],2)); ?></td>
						<td><?php print(round($row['porcentajeCosto']*100,2)); ?>%</td>
						<td>$<?php print(number_format($row['margen'],2)); ?></td>
						<td><?php print(round($row['porcentajeMargen']*100,2)); ?>%</td>
						<td><?php print($row['estado']); ?></td>
						<td><?php print($row['sede_particular']); ?></td>
						<td><?php print($row['fecha_ejecucion']); ?></td>
						<td><?php print($row['mes_ejecucion']); ?></td>
						<td><?php print($row['estatus_ejecucion']); ?></td>
						<td><?php print($row['estatus_costo']); ?></td>
						<td><?php print($row['mes_costo']); ?></td>
						<td><?php print($row['estatus_facturacion']); ?></td>
						<td><?php print($row['mes_facturacion']); ?></td>
						<td><?php print($row['numero_factura']); ?></td>
						<td><?php print($row['estatus_cobranza']); ?></td>
						<td><?php print($row['mes_cobranza']); ?></td>
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
		
		//SUBDIRECTOR
		public function viewOISubdirector($zona)
		{
			$query = "SELECT 
				B.id_bitacora,
				B.nombre_bitacora,
				C.nombre_cliente,
				B.ultima_modificacion,
				SUM(DB.precio_bitacora) AS Ingresos,
				SUM(DB.costo_bitacora) AS Costos,
				SUM(DB.precio_bitacora*DB.gasto_directo) AS Gasto,
				SUM(DB.precio_bitacora-(DB.costo_bitacora+(DB.precio_bitacora*DB.gasto_directo))) AS Margen,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatusejecucion = 1) AS Ejecutado,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatusejecucion = 2) AS PorEjecutar,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatusfacturacion = 1) AS Facturado,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatusfacturacion = 2) AS PorFacturar,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatuscobranza = 1) AS Cobrado,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatuscobranza = 2) AS PorCobrar,
				(SELECT SUM(DB1.costo_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatuscosto = 1) AS Pagado,
				(SELECT SUM(DB1.costo_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatuscosto = 2) AS PorPagar
				FROM T_Bitacora AS B
				INNER JOIN T_Detalle_Bitacora AS DB
				ON DB.id_bitacora = B.id_bitacora
				INNER JOIN T_Subproductos AS S
				ON S.id_subproducto = DB.id_subproducto
				INNER JOIN T_Clientes as C
				ON B.id_cliente = C.id_cliente
				WHERE C.id_zona = :zona
				GROUP BY B.id_cliente, B.id_bitacora";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":zona",$zona);
			$stmt->execute();
			if($stmt->rowCount()>0)
			{
				while($row=$stmt->fetch(PDO::FETCH_ASSOC))
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewBitacora-Subdirector.php?oi=<?php print($row['id_bitacora']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td style="text-align: center"><a href="updateBitacoraForm-Subdirector.php?OI=<?php print($row['id_bitacora']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<td style="text-align: center"><a href="adminAnticipos-Subdirector.php?bitacora=<?php print($row['id_bitacora']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td style="text-align: center"><a href="adminViaticos-Subdirector.php?bitacora=<?php print($row['id_bitacora']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario3.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td style="text-align: center"><a href="Solicitud-Facturas-Subdirector.php?bitacora=<?php print($row['id_bitacora']); ?>"><i class="glyph-icon icon-file"></i></a></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td><?php print($row['ultima_modificacion']); ?></td>
						<td><?php print($row['nombre_bitacora']); ?></td>
						<td></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td></td>
						<td>$<?php print(number_format($row['Ejecutado'],2)); ?></td>
						<td>$<?php print(number_format($row['PorEjecutar'],2)); ?></td>
						<td>$<?php print(number_format($row['Ejecutado']-$row['PorEjecutar'],2)); ?></td>
						<td></td>
						<td>$<?php print(number_format($row['Facturado'],2)); ?></td>
						<td>$<?php print(number_format($row['PorFacturar'],2)); ?></td>
						<td>$<?php print(number_format($row['Facturado']-$row['PorFacturar'],2)); ?></td>
						<td></td>
						<td>$<?php print(number_format($row['Cobrado'],2)); ?></td>
						<td>$<?php print(number_format($row['PorCobrar'],2)); ?></td>
						<td>$<?php print(number_format($row['Cobrado']-$row['PorCobrar'],2)); ?></td>
						<td></td>
						<td>$<?php print(number_format($row['Pagado'],2)); ?></td>
						<td>$<?php print(number_format($row['PorPagar'],2)); ?></td>
						<td>$<?php print(number_format($row['Pagado']-$row['PorPagar'],2)); ?></td>
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
		public function viewOISubdirectorTerritorio($zona)
		{
			$query = "SELECT 
				B.id_bitacora,
				C.id_zona,
				B.nombre_bitacora,
				C.nombre_cliente,
				B.ultima_modificacion,
				SUM(DB.precio_bitacora) AS Ingresos,
				SUM(DB.costo_bitacora) AS Costos,
				SUM(DB.precio_bitacora*DB.gasto_directo) AS Gasto,
				SUM(DB.precio_bitacora-(DB.costo_bitacora+(DB.precio_bitacora*DB.gasto_directo))) AS Margen,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatusejecucion = 1) AS Ejecutado,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatusejecucion = 2) AS PorEjecutar,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatusfacturacion = 1) AS Facturado,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatusfacturacion = 2) AS PorFacturar,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatuscobranza = 1) AS Cobrado,
				(SELECT SUM(DB1.precio_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatuscobranza = 2) AS PorCobrar,
				(SELECT SUM(DB1.costo_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatuscosto = 1) AS Pagado,
				(SELECT SUM(DB1.costo_bitacora) FROM T_Detalle_Bitacora AS DB1 WHERE DB1.id_bitacora = B.id_bitacora AND DB1.id_estatuscosto = 2) AS PorPagar
				FROM T_Bitacora AS B
				INNER JOIN T_Detalle_Bitacora AS DB
				ON DB.id_bitacora = B.id_bitacora
				INNER JOIN T_Subproductos AS S
				ON S.id_subproducto = DB.id_subproducto
				INNER JOIN T_Clientes as C
				ON B.id_cliente = C.id_cliente
				INNER JOIN T_TerritorioZona AS TZ
				ON TZ.id_zona = C.id_zona
				WHERE TZ.id_territorio = :zona
				GROUP BY B.id_cliente, B.id_bitacora";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":zona",$zona);
			$stmt->execute();
			if($stmt->rowCount()>0)
			{
				while($row=$stmt->fetch(PDO::FETCH_ASSOC))
				{
					?>
					<tr>
						<td style="text-align: center"><a href="viewBitacora-Subdirector.php?oi=<?php print($row['id_bitacora']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<td style="text-align: center"><a href="updateBitacoraForm-Subdirector.php?OI=<?php print($row['id_bitacora']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<td style="text-align: center"><a href="adminAnticipos-Subdirector.php?bitacora=<?php print($row['id_bitacora']); ?>"><i class="glyph-icon icon-eye"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCalendario3.php"><i class="glyph-icon icon-calendar"></i></a></td>-->
						<td style="text-align: center"><a href="Solicitud-Facturas-Subdirector.php?bitacora=<?php print($row['id_bitacora']); ?>"><i class="glyph-icon icon-file"></i></a></td>
						<td><?php print($row['nombre_cliente']); ?></td>
						<td>Unidad <?php print($row['id_zona']); ?></td>
						<td><?php print($row['ultima_modificacion']); ?></td>
						<td><?php print($row['nombre_bitacora']); ?></td>
						<td></td>
						<td>$<?php print(number_format($row['Ingresos'],2)); ?></td>
						<td>$<?php print(number_format($row['Costos'],2)); ?></td>
						<td>$<?php print(number_format($row['Margen'],2)); ?></td>
						<td></td>
						<td>$<?php print(number_format($row['Ejecutado'],2)); ?></td>
						<td>$<?php print(number_format($row['PorEjecutar'],2)); ?></td>
						<td>$<?php print(number_format($row['Ejecutado']-$row['PorEjecutar'],2)); ?></td>
						<td></td>
						<td>$<?php print(number_format($row['Facturado'],2)); ?></td>
						<td>$<?php print(number_format($row['PorFacturar'],2)); ?></td>
						<td>$<?php print(number_format($row['Facturado']-$row['PorFacturar'],2)); ?></td>
						<td></td>
						<td>$<?php print(number_format($row['Cobrado'],2)); ?></td>
						<td>$<?php print(number_format($row['PorCobrar'],2)); ?></td>
						<td>$<?php print(number_format($row['Cobrado']-$row['PorCobrar'],2)); ?></td>
						<td></td>
						<td>$<?php print(number_format($row['Pagado'],2)); ?></td>
						<td>$<?php print(number_format($row['PorPagar'],2)); ?></td>
						<td>$<?php print(number_format($row['Pagado']-$row['PorPagar'],2)); ?></td>
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
		
		
		//INSERT BITACORA
		public function insertOI($ordenes,$cliente,$fecha_modificacion,$version,$clave,$nombre,$tipo_anticipo,$monto_anticipo)
		{
			$this->conn->beginTransaction();
			try
			{
				$query="INSERT INTO `T_Bitacora`(`id_oi`, `id_cliente`, `ultima_modificacion`, `version_bitacora`, `clave_bitacora`, `nombre_bitacora`, `id_adminanticipos`, `monto_anticipo`) 
				VALUES (:ordenes,:cliente,:fecha_modificacion,:version,:clave,:nombre,:tipo_anticipo,:monto_anticipo)";
				$stmt = $this->conn->prepare($query);
				$stmt->bindparam(":ordenes",$ordenes);
				$stmt->bindparam(":cliente",$cliente);
				$stmt->bindparam(":fecha_modificacion",$fecha_modificacion);
				$stmt->bindparam(":version",$version);
				$stmt->bindparam(":clave",$clave);
				$stmt->bindparam(":nombre",$nombre);
				$stmt->bindparam(":tipo_anticipo",$tipo_anticipo);
				$stmt->bindparam(":monto_anticipo",$monto_anticipo);
				$stmt->execute();
				$last_id = $this->conn->lastInsertId();
				$this->conn->commit();
				return $last_id;
			}
			catch(PDOException $e)
			{
				echo $e->getMessage();
				$error = $e->getMessage();
				$fichero = 'logs.txt';
				$actual = file_get_contents($fichero);
				$actual .= $error."\n";
				file_put_contents($fichero, $actual);
				$this->conn->rollBack();
				return false;
			}
		}
		public function insertDetalleOI($ordenes,$nueva_bitacora,$subproducto,$precio,$costo,$honorarios,$costomateriales,$costosadicionales,$descuento,$tema,$evento,$id_evento,$personas,$nivel,$equipo,$material,
					$fecha_cobranza,$sede,$sede_particular,$fecha_realizacion,$mes_ejecucion,$estatus_ejecucion,$estatus_costo,$mes_costo,$estatus_factura,$mes_factura,$factura,$estatus_cobranza,$mes_cobranza,$anomalia,$viaticos,
					$transportacion_local,$alimentos,$hospedaje,$nodeducible,$desglose,$transportacion_foranea,$anticipo_viaticos,$rembolso,$observaciones_viaticos,$gasto_admin,$fecha_entrega_viaticos,$estatusdetalleBitacora,$estatus_ejecucion_viaticos,$estatus_facturacion_viaticos,$estatus_cobranza_viaticos,$estatus_costo_viaticos,$detalleoi)
		{
			$this->conn->beginTransaction();
			try
			{
				$query="INSERT INTO `T_Detalle_Bitacora`(`id_bitacora`, `id_subproducto`, `precio_bitacora`, `costo_bitacora`, `costo_honorarios`, `costo_materiales`, `costo_adicionales`, `gasto_directo`, `descuento`, `tema`, `evento`, `id_del_evento`, `no_personas`, `id_nivel`, `id_tipoequipo`, `id_tipomaterial`, `fecha_ejecucion`, `id_mesejecucion`, `id_estatusejecucion`, `id_estatuscosto`, `id_mescosto`, `id_estatusfacturacion`, `id_mesfacturacion`, `numero_factura`, `id_estatuscobranza`, `id_mescobranza`, `anomalia`, viaticos, `transportacion_local`, `alimentos`, `hospedaje`, `no_deducibles`, `desglose`, `transportacion_foranea`, `anticipo_entregado`, `rembolso`, `observaciones_viaticos`, `gasto_admin`, `fecha_entrega_viaticos`, `fecha_cobranza`, `id_estado`, `sede_particular`, `id_estatusdetallebitacora`, `id_estatusejecucion_viaticos`, `id_estatusfacturacion_viaticos`, `id_estatuscobranza_viaticos`, `id_estatuscosto_viaticos`, id_detalleoi) 
				VALUES (:nueva_bitacora,:subproducto,:precio,:costo,:honorarios,:costomateriales,:costosadicionales,0,:descuento,:tema,:evento,:id_evento,:personas,:nivel,:equipo,:material,:fecha_realizacion,:mes_ejecucion,:estatus_ejecucion,:estatus_costo,:mes_costo,:estatus_factura,:mes_factura,:factura,:estatus_cobranza,:mes_cobranza,:anomalia,:viaticos,:transportacion_local,:alimentos,:hospedaje,:nodeducible,:desglose,:transportacion_foranea,:anticipo_viaticos,:rembolso,:observaciones_viaticos,:gasto_admin,:fecha_entrega_viaticos,:fecha_cobranza,:sede,:sede_particular,:estatusdetalleBitacora,:estatus_ejecucion_viaticos,:estatus_facturacion_viaticos,:estatus_cobranza_viaticos,:estatus_costo_viaticos,:detalleoi)";
				$stmt = $this->conn->prepare($query);
				$stmt->bindparam(":nueva_bitacora",$nueva_bitacora);
				$stmt->bindparam(":subproducto",$subproducto);
				$stmt->bindparam(":precio",$precio);
				$stmt->bindparam(":costo",$costo);
				$stmt->bindparam(":honorarios",$honorarios);
				$stmt->bindparam(":costomateriales",$costomateriales);
				$stmt->bindparam(":costosadicionales",$costosadicionales);
				$stmt->bindparam(":descuento",$descuento);
				$stmt->bindparam(":tema",$tema);
				$stmt->bindparam(":evento",$evento);
				$stmt->bindparam(":id_evento",$id_evento);
				$stmt->bindparam(":personas",$personas);
				$stmt->bindparam(":nivel",$nivel);
				$stmt->bindparam(":equipo",$equipo);
				$stmt->bindparam(":material",$material);
				$stmt->bindparam(":fecha_realizacion",$fecha_realizacion);
				$stmt->bindparam(":mes_ejecucion",$mes_ejecucion);
				$stmt->bindparam(":estatus_ejecucion",$estatus_ejecucion);
				$stmt->bindparam(":estatus_costo",$estatus_costo);
				$stmt->bindparam(":mes_costo",$mes_costo);
				$stmt->bindparam(":estatus_factura",$estatus_factura);
				$stmt->bindparam(":mes_factura",$mes_factura);
				$stmt->bindparam(":factura",$factura);
				$stmt->bindparam(":estatus_cobranza",$estatus_cobranza);
				$stmt->bindparam(":mes_cobranza",$mes_cobranza);
				$stmt->bindparam(":anomalia",$anomalia);
				$stmt->bindparam(":viaticos",$viaticos);
				$stmt->bindparam(":transportacion_local",$transportacion_local);
				$stmt->bindparam(":alimentos",$alimentos);
				$stmt->bindparam(":hospedaje",$hospedaje);
				$stmt->bindparam(":nodeducible",$nodeducible);
				$stmt->bindparam(":desglose",$desglose);
				$stmt->bindparam(":transportacion_foranea",$transportacion_foranea);
				$stmt->bindparam(":anticipo_viaticos",$anticipo_viaticos);
				$stmt->bindparam(":rembolso",$rembolso);
				$stmt->bindparam(":observaciones_viaticos",$observaciones_viaticos);
				$stmt->bindparam(":gasto_admin",$gasto_admin);
				$stmt->bindparam(":fecha_entrega_viaticos",$fecha_entrega_viaticos);
				$stmt->bindparam(":fecha_cobranza",$fecha_cobranza);
				$stmt->bindparam(":sede",$sede);
				$stmt->bindparam(":sede_particular",$sede_particular);
				$stmt->bindparam(":estatusdetalleBitacora",$estatusdetalleBitacora);
				$stmt->bindparam(":estatus_ejecucion_viaticos",$estatus_ejecucion_viaticos);
				$stmt->bindparam(":estatus_facturacion_viaticos",$estatus_facturacion_viaticos);
				$stmt->bindparam(":estatus_cobranza_viaticos",$estatus_cobranza_viaticos);
				$stmt->bindparam(":estatus_costo_viaticos",$estatus_costo_viaticos);
				$stmt->bindparam(":detalleoi",$detalleoi);
				$stmt->execute();
				$last_id = $this->conn->lastInsertId();
								
				/*$query="UPDATE T_Ordenes_Intervencion SET id_estatusoi =  2 WHERE id_oi = :ordenes";
				$stmt = $this->conn->prepare($query);
				$stmt->bindparam(":ordenes",$ordenes);
				$stmt->execute();*/
				$this->conn->commit();
				return $last_id;
				
			}
			catch(PDOException $e)
			{
				echo $e->getMessage();
				//exit;
				$error = $e->getMessage();
				$fichero = fopen("logs.txt","w") or die("Unable to open file");
				fwrite($fichero, $error);
				fclose($fichero);
				$this->conn->rollBack();
				return false;
			}
		}
		public function insertAgenteDetalleOI($detalleBitacora,$tipo_agente,$costo,$subproducto,$month,$fecha_realizacion)
		{
			//$this->conn->beginTransaction();
			try
			{
				
				$query="SELECT 
						id_detallebitacora_agente
					FROM T_Detalle_Bitacora_Agente AS DBA
					WHERE id_detallebitacora = :detalleBitacora";
				$stmt10 = $this->conn->prepare($query);
				$stmt10->bindparam(":detalleBitacora",$detalleBitacora);
				$stmt10->execute();
				if($stmt10->rowCount()>0)
				{
					return true;
				}
				else
				{
					if($fecha_realizacion != '' AND $fecha_realizacion != 0 AND $fecha_realizacion != '0000-00-00')
					{
						//CONSULTA PARA OBTENER EL TIPO DE AGENTE DEL SUBPRODUCTO
						/*$tiposAgentes_agentes = array();
						$j = 0;
						$query="SELECT 
								id_tipoagente
							FROM T_Subproducto_TipoAgente
							WHERE id_subproducto = :subproducto";
						$stmt = $this->conn->prepare($query);
						$stmt->bindparam(":subproducto",$subproducto);
						$stmt->execute();
						while($row=$stmt->fetch(PDO::FETCH_ASSOC))
						{
							$tiposAgentes_agentes[$j] = $row['id_tipoagente'];
							$j++;
						}*/
						
						//CONSULTA PARA OBTENER LOS AGENTES CON EL TIPO DE AGENTE REQUERIDO
						if($tipo_agente > 0)
						{
							$x = 0;
							$agentesArray = array();
							$query="SELECT
								A.id_agente
							FROM T_Agentes AS A
							INNER JOIN T_TipoAgente_Agente AS TAA
							ON TAA.id_agente = A.id_agente
							WHERE TAA.id_tipoagente = :tipo_agente";
							$stmt2 = $this->conn->prepare($query);
							$stmt2->bindparam(":tipo_agente",$tipo_agente);
							$stmt2->execute();
							$i = 0;
							while($row2=$stmt2->fetch(PDO::FETCH_ASSOC))
							{
								$agentesArray[$i] = $row2['id_agente'];
								$i++;
							}
							$x++;
						}
						else
						{
							$agente = 0;
						}

						if($stmt2->rowCount() == 0)
						{
							$agente = 0;
						}
						else
						{
							//CONSULTA PARA OBTENER A LOS AGENTES OCUPADOS EN LA FECHA REQUERIDA
							$agentesOcupadosArray = array();
							$query="SELECT 
									DBA.id_agente 
								FROM T_Detalle_Bitacora_Agente AS DBA 
								INNER JOIN T_Detalle_Bitacora AS DB 
								ON DB.id_detallebitacora = DBA.id_detallebitacora 
								WHERE DB.fecha_ejecucion = :fecha_realizacion AND DBA.id_tipoagente = :tipo_agente";
							$stmt3 = $this->conn->prepare($query);
							$stmt3->bindparam(":fecha_realizacion",$fecha_realizacion);
							$stmt3->bindparam(":tipo_agente",$tipo_agente);
							$stmt3->execute();
							$i = 0;
							while($row3=$stmt3->fetch(PDO::FETCH_ASSOC))
							{
								$agentesOcupadosArray[$i]['id_agente'] = $row3['id_agente'];
								$i++;
							}
							
							//RECORRER EL ARRAY DE LOS AGENTES CALIFICADOS PARA EXCLUIR LOS QUE NO ESTÁN DISPONIBLES
							$agentesDisponiblesArray = array();
							$x = 0;
							if($stmt3->rowCount() == 0)
							{
								foreach($agentesArray as $aux1)
								{
									$agentesDisponiblesArray[$x] = $aux1;
									$x++;
								}
							}
							else
							{
								foreach($agentesArray as $aux1)
								{
									foreach($agentesOcupadosArray as $aux2)
									{
										if($aux1 != $aux2)
										{
											$agentesDisponiblesArray[$x] = $aux1;
										}
									}
									$x++;
								}
							}
							$contarAgentes = sizeof($agentesDisponiblesArray);
							if($contarAgentes == 1)
							{
								$agente = $agentesDisponiblesArray[0];
							}
							elseif($contarAgentes == 0)
							{
								$agente = 0;
							}
							else
							{
								$y = 0;
								$agentesOferta = array();
								foreach($agentesDisponiblesArray as $aux)
								{
									if($aux != 0)
									{
										//CONSULTA PARA SABER A QUIÉN LE CONVIENE EN CUESTION DE OFERTA
										$query="SELECT 
											A.id_agente, 
											CONCAT(A.nombre_agente, ' ', A.apellidos_agente) AS nombre_agente, 
											A.oferta_actual, 
											(
												CASE WHEN (
													SELECT SUM( S.costo_honorarios )
													FROM T_Detalle_Bitacora AS DB
													INNER JOIN T_Subproductos AS S
													ON S.id_subproducto = DB.id_subproducto
													WHERE DB.id_agente = :aux
												) = NULL
												THEN 0 
												ELSE (
													SELECT SUM( S.costo_honorarios )
													FROM T_Detalle_Bitacora AS DB
													INNER JOIN T_Subproductos AS S
													ON S.id_subproducto = DB.id_subproducto
													WHERE DB.id_agente = :aux
												)
												END
											)AS realidad, 
											(
												CASE WHEN (
													SELECT 
														COUNT( AUXDB.id_detallebitacora ) 
													FROM T_Detalle_Bitacora AS AUXDB
													WHERE AUXDB.id_mesejecucion = :month AND AUXDB.id_agente = A.id_agente
												) = NULL 
												THEN 0 
												ELSE (
													SELECT 
														COUNT( AUXDB.id_detallebitacora ) 
													FROM T_Detalle_Bitacora AS AUXDB
													WHERE AUXDB.id_mesejecucion = :month AND AUXDB.id_agente = A.id_agente
												)
												END
											) AS eventos_delmes
										FROM  `T_Agentes` AS A
										WHERE A.id_agente = :aux
										GROUP BY A.id_agente";
										$stmt4 = $this->conn->prepare($query);
										$stmt4->bindparam(":aux",$aux);
										$stmt4->bindparam(":month",$month);
										$stmt4->execute();
										while($row4=$stmt4->fetch(PDO::FETCH_ASSOC))
										{
											$agentesOferta[$y]['id_agente'] = $row4['id_agente'];
											$agentesOferta[$y]['nombre_agente'] = $row4['nombre_agente'];
											$agentesOferta[$y]['oferta_actual'] = floatval($row4['oferta_actual']);
											$agentesOferta[$y]['realidad'] = floatval($row4['realidad']);
											$agentesOferta[$y]['diferencia'] = floatval($row4['oferta_actual']-$row4['realidad']);
											$agentesOferta[$y]['eventos_delmes'] = $row4['eventos_delmes'];
										}
									}
									$y++;
								}
								$eventos = 100;
								$diferencia = 0;
								$id = '';
								foreach($agentesOferta as $aux)
								{
									if($aux['eventos_delmes'] == $eventos)
									{
										$eventos = $aux['eventos_delmes'];
										$id = $id.'+'.$aux['id_agente'];
									}
									elseif($aux['eventos_delmes'] < $eventos)
									{
										$eventos = $aux['eventos_delmes'];
										$id = $aux['id_agente'];
									}
								}
								if(strpos($id, '+') !== false)
								{
									foreach($agentesOferta as $aux)
									{
										if($aux['diferencia'] == $diferencia)
										{
											$diferencia = $aux['diferencia'];
											$id = $id.'+'.$aux['id_agente'];
										}
										elseif($aux['diferencia'] > $diferencia)
										{
											$diferencia = $aux['diferencia'];
											$id = $aux['id_agente'];
										}
									}
									if(strpos($id, '+') !== false)
									{
										$contadorAux = $y;
										$randomNum = rand(0,$contadorAux);
										$agente = $agentesOferta[$randomNum]['id_agente'];
									}
									else
									{
										$agente = $id;
									}
								}
								else
								{
									$agente = $id;
									
								}
							}
						}
					}
					else
					{
						$agente = 0;
					}
					
					if($subproducto == 112)
					{
						$detalleBitacora2 = $detalleBitacora+1;
						$detalleBitacora3 = $detalleBitacora+2;
						$detalleBitacora4 = $detalleBitacora+3;
						$query="INSERT INTO `T_Detalle_Bitacora_Agente`(`id_detallebitacora`, `id_agente`, `id_tipoagente`, `costo`, id_estatusasignacion, fecha_creacion) 
						VALUES (:detalleBitacora,:agente,:tipo_agente,:costo,1,NOW()),(:detalleBitacora2,:agente,:tipo_agente,:costo,1,NOW()),(:detalleBitacora3,:agente,:tipo_agente,:costo,1,NOW()),(:detalleBitacora4,:agente,:tipo_agente,:costo,1,NOW())";
						$stmt5 = $this->conn->prepare($query);
						$stmt5->bindparam(":detalleBitacora",$detalleBitacora);
						$stmt5->bindparam(":agente",$agente);
						$stmt5->bindparam(":tipo_agente",$tipo_agente);
						$stmt5->bindparam(":costo",$costo);
						$stmt5->bindparam(":detalleBitacora2",$detalleBitacora2);
						$stmt5->bindparam(":detalleBitacora3",$detalleBitacora3);
						$stmt5->bindparam(":detalleBitacora4",$detalleBitacora4);
						$stmt5->execute();
						$last_id = $this->conn->lastInsertId();
						
						if($agente != 0)
						{
							$len = strlen($fecha_realizacion);
							if($len == 41)
							{
								$inicio = substr($fecha_realizacion,0,19);
								$fin = substr($fecha_realizacion,22,41);
							}
							else
							{
								$inicio = $fecha_realizacion;
								$fin = date($fecha_realizacion, strtotime('+4 hours'));
							}
							
							$query="SELECT
									B.nombre_bitacora AS titulo
								FROM T_Bitacora AS B
								INNER JOIN T_Detalle_Bitacora AS DB
								ON DB.id_bitacora = B.id_bitacora
								WHERE DB.id_detallebitacora = :detalleBitacora";
							$stmt7 = $this->conn->prepare($query);
							$stmt7->bindparam(":detalleBitacora",$detalleBitacora);
							$stmt7->execute();
							$row7=$stmt7->fetch(PDO::FETCH_ASSOC);
							
							$query="INSERT INTO `T_Calendario_Agentes`(`titulo_calendario`, `inicio_calendario`, `fin_calendario`, `id_agente`, `id_detallebitacora`) 
							VALUES (:titulo,:inicio,:fin,:agente,:detalleBitacora)";
							$stmt6 = $this->conn->prepare($query);
							$stmt6->bindparam(":titulo",$row7['titulo']);
							$stmt6->bindparam(":inicio",$inicio);
							$stmt6->bindparam(":fin",$fin);
							$stmt6->bindparam(":agente",$agente);
							$stmt6->bindparam(":detalleBitacora",$detalleBitacora);
							$stmt6->execute();
						}
					}
					elseif($subproducto == 113)
					{
						$detalleBitacora2 = $detalleBitacora+1;
						$detalleBitacora3 = $detalleBitacora+2;
						$query="INSERT INTO `T_Detalle_Bitacora_Agente`(`id_detallebitacora`, `id_agente`, `id_tipoagente`, `costo`, id_estatusasignacion, fecha_creacion) 
						VALUES (:detalleBitacora,:agente,:tipo_agente,:costo,1,NOW()),(:detalleBitacora2,:agente,:tipo_agente,:costo,1,NOW()),(:detalleBitacora3,:agente,:tipo_agente,:costo,1,NOW())";
						$stmt5 = $this->conn->prepare($query);
						$stmt5->bindparam(":detalleBitacora",$detalleBitacora);
						$stmt5->bindparam(":agente",$agente);
						$stmt5->bindparam(":tipo_agente",$tipo_agente);
						$stmt5->bindparam(":costo",$costo);
						$stmt5->bindparam(":detalleBitacora2",$detalleBitacora2);
						$stmt5->bindparam(":detalleBitacora3",$detalleBitacora3);
						$stmt5->execute();
						$last_id = $this->conn->lastInsertId();
						
						if($agente != 0)
						{
							$len = strlen($fecha_realizacion);
							if($len == 41)
							{
								$inicio = substr($fecha_realizacion,0,19);
								$fin = substr($fecha_realizacion,22,41);
							}
							else
							{
								$inicio = $fecha_realizacion;
								$fin = date($fecha_realizacion, strtotime('+4 hours'));
							}
							
							$query="SELECT
									B.nombre_bitacora AS titulo
								FROM T_Bitacora AS B
								INNER JOIN T_Detalle_Bitacora AS DB
								ON DB.id_bitacora = B.id_bitacora
								WHERE DB.id_detallebitacora = :detalleBitacora";
							$stmt7 = $this->conn->prepare($query);
							$stmt7->bindparam(":detalleBitacora",$detalleBitacora);
							$stmt7->execute();
							$row7=$stmt7->fetch(PDO::FETCH_ASSOC);
							
							$query="INSERT INTO `T_Calendario_Agentes`(`titulo_calendario`, `inicio_calendario`, `fin_calendario`, `id_agente`, `id_detallebitacora`) 
							VALUES (:titulo,:inicio,:fin,:agente,:detalleBitacora)";
							$stmt6 = $this->conn->prepare($query);
							$stmt6->bindparam(":titulo",$row7['titulo']);
							$stmt6->bindparam(":inicio",$inicio);
							$stmt6->bindparam(":fin",$fin);
							$stmt6->bindparam(":agente",$agente);
							$stmt6->bindparam(":detalleBitacora",$detalleBitacora);
							$stmt6->execute();
						}
					}
					elseif($subproducto == 114)
					{
						$detalleBitacora2 = $detalleBitacora+1;
						$query="INSERT INTO `T_Detalle_Bitacora_Agente`(`id_detallebitacora`, `id_agente`, `id_tipoagente`, `costo`, id_estatusasignacion, fecha_creacion) 
						VALUES (:detalleBitacora,:agente,:tipo_agente,:costo,1,NOW()),(:detalleBitacora2,:agente,:tipo_agente,:costo,1,NOW())";
						$stmt5 = $this->conn->prepare($query);
						$stmt5->bindparam(":detalleBitacora",$detalleBitacora);
						$stmt5->bindparam(":agente",$agente);
						$stmt5->bindparam(":tipo_agente",$tipo_agente);
						$stmt5->bindparam(":costo",$costo);
						$stmt5->bindparam(":detalleBitacora2",$detalleBitacora2);
						$stmt5->execute();
						$last_id = $this->conn->lastInsertId();
						
						if($agente != 0)
						{
							$len = strlen($fecha_realizacion);
							if($len == 41)
							{
								$inicio = substr($fecha_realizacion,0,19);
								$fin = substr($fecha_realizacion,22,41);
							}
							else
							{
								$inicio = $fecha_realizacion;
								$fin = date($fecha_realizacion, strtotime('+4 hours'));
							}
							
							$query="SELECT
									B.nombre_bitacora AS titulo
								FROM T_Bitacora AS B
								INNER JOIN T_Detalle_Bitacora AS DB
								ON DB.id_bitacora = B.id_bitacora
								WHERE DB.id_detallebitacora = :detalleBitacora";
							$stmt7 = $this->conn->prepare($query);
							$stmt7->bindparam(":detalleBitacora",$detalleBitacora);
							$stmt7->execute();
							$row7=$stmt7->fetch(PDO::FETCH_ASSOC);
							
							$query="INSERT INTO `T_Calendario_Agentes`(`titulo_calendario`, `inicio_calendario`, `fin_calendario`, `id_agente`, `id_detallebitacora`) 
							VALUES (:titulo,:inicio,:fin,:agente,:detalleBitacora)";
							$stmt6 = $this->conn->prepare($query);
							$stmt6->bindparam(":titulo",$row7['titulo']);
							$stmt6->bindparam(":inicio",$inicio);
							$stmt6->bindparam(":fin",$fin);
							$stmt6->bindparam(":agente",$agente);
							$stmt6->bindparam(":detalleBitacora",$detalleBitacora);
							$stmt6->execute();
						}
					}
					else
					{
						$query="INSERT INTO `T_Detalle_Bitacora_Agente`(`id_detallebitacora`, `id_agente`, `id_tipoagente`, `costo`, id_estatusasignacion, fecha_creacion) 
						VALUES (:detalleBitacora,:agente,:tipo_agente,:costo,1,NOW())";
						$stmt5 = $this->conn->prepare($query);
						$stmt5->bindparam(":detalleBitacora",$detalleBitacora);
						$stmt5->bindparam(":agente",$agente);
						$stmt5->bindparam(":tipo_agente",$tipo_agente);
						$stmt5->bindparam(":costo",$costo);
						$stmt5->execute();
						$last_id = $this->conn->lastInsertId();
						
						if($agente != 0)
						{
							$len = strlen($fecha_realizacion);
							if($len == 41)
							{
								$inicio = substr($fecha_realizacion,0,19);
								$fin = substr($fecha_realizacion,22,41);
							}
							else
							{
								$inicio = $fecha_realizacion;
								$fin = date($fecha_realizacion, strtotime('+4 hours'));
							}
							
							$query="SELECT
									B.nombre_bitacora AS titulo
								FROM T_Bitacora AS B
								INNER JOIN T_Detalle_Bitacora AS DB
								ON DB.id_bitacora = B.id_bitacora
								WHERE DB.id_detallebitacora = :detalleBitacora";
							$stmt7 = $this->conn->prepare($query);
							$stmt7->bindparam(":detalleBitacora",$detalleBitacora);
							$stmt7->execute();
							$row7=$stmt7->fetch(PDO::FETCH_ASSOC);
							
							$query="INSERT INTO `T_Calendario_Agentes`(`titulo_calendario`, `inicio_calendario`, `fin_calendario`, `id_agente`, `id_detallebitacora`) 
							VALUES (:titulo,:inicio,:fin,:agente,:detalleBitacora)";
							$stmt6 = $this->conn->prepare($query);
							$stmt6->bindparam(":titulo",$row7['titulo']);
							$stmt6->bindparam(":inicio",$inicio);
							$stmt6->bindparam(":fin",$fin);
							$stmt6->bindparam(":agente",$agente);
							$stmt6->bindparam(":detalleBitacora",$detalleBitacora);
							$stmt6->execute();
						}
					}
					//$this->conn->commit();
					return true;
				}
			}
			catch(PDOException $e)
			{
				echo $e->getMessage();
				//$this->conn->rollBack();
				return false;
			}
		}
		/*public function($detalleBitacora,$agente,$tipo_agente)
		{
			$this->conn->beginTransaction();
			try
			{
				$query="UPDATE `T_Detalle_Bitacora_Agente` SET `id_agente` = :agente,  id_tipoagente = :tipo_agente WHERE id_detallebitacora_agente = :detalleBitacora";
				$stmt = $this->conn->prepare($query);
				$stmt->bindparam(":agente",$agente);
				$stmt->bindparam(":tipo_agente",$tipo_agente);
				$stmt->bindparam(":detalleBitacora",$detalleBitacora);
				$stmt->execute();
				$last_id = $this->conn->lastInsertId();
				
				$this->conn->commit();
				return true;
				
			}
			catch(PDOException $e)
			{
				echo $e->getMessage();
				$this->conn->rollBack();
				$error = $e->getMessage();
				$fichero = fopen("logs.txt","w") or die("Unable to open file");
				fwrite($fichero, $error);
				fclose($fichero);
				return false;
			}
		}*/
		public function updateAgenteDetalleOI ($idDetalle,$tipo_agente,$monto,$subproducto,$mes_ejecucion,$fecha_realizacion)
		{
			//$this->conn->beginTransaction();
			try
			{
				$query="SELECT 
							DBA.id_agente
						FROM T_Detalle_Bitacora_Agente AS DBA
						INNER JOIN T_Detalle_Bitacora AS DB
						ON DB.id_detallebitacora = DBA.id_detallebitacora
						WHERE DBA.id_detallebitacora_agente = :idDetalle AND DBA.id_tipoagente = :tipo_agente AND DBA.costo = :monto";
				$stmt = $this->conn->prepare($query);
				$stmt->bindparam(":idDetalle",$idDetalle);
				$stmt->bindparam(":tipo_agente",$tipo_agente);
				$stmt->bindparam(":monto",$monto);
				$stmt->execute();
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				$agente = $row['id_agente'];
					
				
				
				if($fecha_realizacion != '' AND $fecha_realizacion != 0 AND $fecha_realizacion != '0000-00-00')
				{
					//CONSULTA PARA OBTENER LOS AGENTES CON EL TIPO DE AGENTE REQUERIDO
					if($tipo_agente > 0)
					{
						$x = 0;
						$agentesArray = array();
						$query="SELECT
							A.id_agente
						FROM T_Agentes AS A
						INNER JOIN T_TipoAgente_Agente AS TAA
						ON TAA.id_agente = A.id_agente
						WHERE TAA.id_tipoagente = :tipo_agente AND NOT A.id_agente = :agente";
						$stmt2 = $this->conn->prepare($query);
						$stmt2->bindparam(":tipo_agente",$tipo_agente);
						$stmt2->bindparam(":agente",$agente);
						$stmt2->execute();
						$i = 0;
						while($row2=$stmt2->fetch(PDO::FETCH_ASSOC))
						{
							$agentesArray[$i] = $row2['id_agente'];
							$i++;
						}
						$x++;
					}
					else
					{
						$agente = 0;
					}

					if($stmt2->rowCount() == 0)
					{
						$agente = 0;
					}
					else
					{
						//CONSULTA PARA OBTENER A LOS AGENTES OCUPADOS EN LA FECHA REQUERIDA
						$agentesOcupadosArray = array();
						$query="SELECT 
								DBA.id_agente 
							FROM T_Detalle_Bitacora_Agente AS DBA 
							INNER JOIN T_Detalle_Bitacora AS DB 
							ON DB.id_detallebitacora = DBA.id_detallebitacora 
							WHERE DB.fecha_ejecucion = :fecha_realizacion AND DBA.id_tipoagente = :tipo_agente AND NOT DBA.id_agente = :agente";
						$stmt3 = $this->conn->prepare($query);
						$stmt3->bindparam(":fecha_realizacion",$fecha_realizacion);
						$stmt3->bindparam(":tipo_agente",$tipo_agente);
						$stmt3->bindparam(":agente",$agente);
						$stmt3->execute();
						$i = 0;
						while($row3=$stmt3->fetch(PDO::FETCH_ASSOC))
						{
							$agentesOcupadosArray[$i]['id_agente'] = $row3['id_agente'];
							$i++;
						}
						
						//RECORRER EL ARRAY DE LOS AGENTES CALIFICADOS PARA EXCLUIR LOS QUE NO ESTÁN DISPONIBLES
						$agentesDisponiblesArray = array();
						$x = 0;
						if($stmt3->rowCount() == 0)
						{
							foreach($agentesArray as $aux1)
							{
								$agentesDisponiblesArray[$x] = $aux1;
								$x++;
							}
						}
						else
						{
							foreach($agentesArray as $aux1)
							{
								foreach($agentesOcupadosArray as $aux2)
								{
									if($aux1 != $aux2)
									{
										$agentesDisponiblesArray[$x] = $aux1;
									}
								}
								$x++;
							}
						}
						$contarAgentes = sizeof($agentesDisponiblesArray);
						if($contarAgentes == 1)
						{
							$agente = $agentesDisponiblesArray[0];
						}
						elseif($contarAgentes == 0)
						{
							$agente = 0;
						}
						else
						{
							$y = 0;
							$agentesOferta = array();
							foreach($agentesDisponiblesArray as $aux)
							{
								if($aux != 0)
								{
									//CONSULTA PARA SABER A QUIÉN LE CONVIENE EN CUESTION DE OFERTA
									$query="SELECT 
										A.id_agente, 
										CONCAT(A.nombre_agente, ' ', A.apellidos_agente) AS nombre_agente, 
										A.oferta_actual, 
										(
											CASE WHEN (
												SELECT SUM( S.costo_honorarios )
												FROM T_Detalle_Bitacora AS DB
												INNER JOIN T_Subproductos AS S
												ON S.id_subproducto = DB.id_subproducto
												WHERE DB.id_agente = :aux
											) = NULL
											THEN 0 
											ELSE (
												SELECT SUM( S.costo_honorarios )
												FROM T_Detalle_Bitacora AS DB
												INNER JOIN T_Subproductos AS S
												ON S.id_subproducto = DB.id_subproducto
												WHERE DB.id_agente = :aux
											)
											END
										)AS realidad, 
										(
											CASE WHEN (
												SELECT 
													COUNT( AUXDB.id_detallebitacora ) 
												FROM T_Detalle_Bitacora AS AUXDB
												WHERE AUXDB.id_mesejecucion = :month AND AUXDB.id_agente = A.id_agente
											) = NULL 
											THEN 0 
											ELSE (
												SELECT 
													COUNT( AUXDB.id_detallebitacora ) 
												FROM T_Detalle_Bitacora AS AUXDB
												WHERE AUXDB.id_mesejecucion = :month AND AUXDB.id_agente = A.id_agente
											)
											END
										) AS eventos_delmes
									FROM  `T_Agentes` AS A
									WHERE A.id_agente = :aux
									GROUP BY A.id_agente";
									$stmt4 = $this->conn->prepare($query);
									$stmt4->bindparam(":aux",$aux);
									$stmt4->bindparam(":month",$month);
									$stmt4->execute();
									while($row4=$stmt4->fetch(PDO::FETCH_ASSOC))
									{
										$agentesOferta[$y]['id_agente'] = $row4['id_agente'];
										$agentesOferta[$y]['nombre_agente'] = $row4['nombre_agente'];
										$agentesOferta[$y]['oferta_actual'] = floatval($row4['oferta_actual']);
										$agentesOferta[$y]['realidad'] = floatval($row4['realidad']);
										$agentesOferta[$y]['diferencia'] = floatval($row4['oferta_actual']-$row4['realidad']);
										$agentesOferta[$y]['eventos_delmes'] = $row4['eventos_delmes'];
									}
								}
								$y++;
							}
							$eventos = 100;
							$diferencia = 0;
							$id = '';
							foreach($agentesOferta as $aux)
							{
								if($aux['eventos_delmes'] == $eventos)
								{
									$eventos = $aux['eventos_delmes'];
									$id = $id.'+'.$aux['id_agente'];
								}
								elseif($aux['eventos_delmes'] < $eventos)
								{
									$eventos = $aux['eventos_delmes'];
									$id = $aux['id_agente'];
								}
							}
							if(strpos($id, '+') !== false)
							{
								foreach($agentesOferta as $aux)
								{
									if($aux['diferencia'] == $diferencia)
									{
										$diferencia = $aux['diferencia'];
										$id = $id.'+'.$aux['id_agente'];
									}
									elseif($aux['diferencia'] > $diferencia)
									{
										$diferencia = $aux['diferencia'];
										$id = $aux['id_agente'];
									}
								}
								if(strpos($id, '+') !== false)
								{
									$contadorAux = $y;
									$randomNum = rand(0,$contadorAux);
									$agente = $agentesOferta[$randomNum]['id_agente'];
								}
								else
								{
									$agente = $id;
								}
							}
							else
							{
								$agente = $id;
								
							}
						}
					}
				}
				else
				{
					$agente = 0;
				}
				
				if($subproducto == 112)
				{
					$detalleBitacora2 = $idDetalle+1;
					$detalleBitacora3 = $idDetalle+2;
					$detalleBitacora4 = $idDetalle+3;
					$query="UPDATE T_Detalle_Bitacora_Agente SET id_agente = :agente
						WHERE id_detallebitacora_agente = :idDetalle OR id_detallebitacora_agente = :detalleBitacora2 OR id_detallebitacora_agente = :detalleBitacora3 OR id_detallebitacora_agente = :detalleBitacora4";
					$stmt7 = $this->conn->prepare($query);
					$stmt7->bindparam(":agente",$agente);
					$stmt7->bindparam(":idDetalle",$idDetalle);
					$stmt7->bindparam(":detalleBitacora2",$detalleBitacora2);
					$stmt7->bindparam(":detalleBitacora3",$detalleBitacora3);
					$stmt7->bindparam(":detalleBitacora4",$detalleBitacora4);
					$stmt7->execute();
				}
				elseif($subproducto == 113)
				{
					$detalleBitacora2 = $idDetalle+1;
					$detalleBitacora3 = $idDetalle+2;
					$query="UPDATE T_Detalle_Bitacora_Agente SET id_agente = :agente
						WHERE id_detallebitacora_agente = :idDetalle OR id_detallebitacora_agente = :detalleBitacora2 OR id_detallebitacora_agente = :detalleBitacora3";
					$stmt7 = $this->conn->prepare($query);
					$stmt7->bindparam(":agente",$agente);
					$stmt7->bindparam(":idDetalle",$idDetalle);
					$stmt7->bindparam(":detalleBitacora2",$detalleBitacora2);
					$stmt7->bindparam(":detalleBitacora3",$detalleBitacora3);
					$stmt7->execute();
				}
				elseif($subproducto == 114)
				{
					$detalleBitacora2 = $idDetalle+1;
					$query="UPDATE T_Detalle_Bitacora_Agente SET id_agente = :agente
						WHERE id_detallebitacora_agente = :idDetalle OR id_detallebitacora_agente = :detalleBitacora2";
					$stmt7 = $this->conn->prepare($query);
					$stmt7->bindparam(":agente",$agente);
					$stmt7->bindparam(":idDetalle",$idDetalle);
					$stmt7->bindparam(":detalleBitacora2",$detalleBitacora2);
					$stmt7->execute();
				}
				else
				{
					$query="UPDATE T_Detalle_Bitacora_Agente SET id_agente = :agente
						WHERE id_detallebitacora_agente = :idDetalle";
					$stmt7 = $this->conn->prepare($query);
					$stmt7->bindparam(":agente",$agente);
					$stmt7->bindparam(":idDetalle",$idDetalle);
					$stmt7->execute();
				}
				
				$query="UPDATE T_Calendario_Agentes AS CA,  T_Detalle_Bitacora_Agente AS DBA SET CA.id_agente = :agente 
						WHERE id_detallebitacora_agente = :idDetalle";
				$stmt8 = $this->conn->prepare($query);
				$stmt8->bindparam(":agente",$agente);
				$stmt8->bindparam(":idDetalle",$idDetalle);
				$stmt8->execute();
				
				//$this->conn->commit();
				return true;
			}
			catch(PDOException $e)
			{
				echo $e->getMessage();
				exit;
				//$this->conn->rollBack();
				return false;
			}
		}
		public function updateAgenteDetalleOISens($detalleBitacora,$tipo_agente)
		{
			$this->conn->beginTransaction();
			try
			{
				$query="UPDATE `T_Detalle_Bitacora_Agente` SET id_detallebitacora = 0 WHERE `id_tipoagente` = :tipo_agente AND id_detallebitacora = :detalleBitacora";
				$stmt = $this->conn->prepare($query);
				$stmt->bindparam(":tipo_agente",$tipo_agente);
				$stmt->bindparam(":detalleBitacora",$detalleBitacora);
				$stmt->execute();
				$last_id = $this->conn->lastInsertId();
				$this->conn->commit();
				return true;
				
			}
			catch(PDOException $e)
			{
				echo $e->getMessage();
				$this->conn->rollBack();
				$error = $e->getMessage();
				$fichero = fopen("logs.txt","w") or die("Unable to open file");
				fwrite($fichero, $error);
				fclose($fichero);
				return false;
			}
		}
		public function insertDetalleOICambios($ordenes,$nueva_bitacora,$subproducto,$precio,$descuento,$tema,$evento,$id_evento,$personas,$nivel,$tipoagente,$agente,$equipo,$material,
					$fecha_cobranza,$sede,$sede_particular,$fecha_realizacion,$mes_ejecucion,$estatus_ejecucion,$estatus_costo,$mes_costo,$estatus_factura,$mes_factura,$factura,$estatus_cobranza,$mes_cobranza,$anomalia,$viaticos,
					$transportacion_local,$alimentos,$hospedaje,$nodeducible,$desglose,$transportacion_foranea,$anticipo_viaticos,$rembolso,$observaciones_viaticos,$gasto_admin,$fecha_entrega_viaticos)
		{
			$this->conn->beginTransaction();
			try
			{
				$month = date('m');
				if($fecha_realizacion != '' OR $fecha_realizacion != 0)
				{
					//CONSULTA PARA OBTENER EL TIPO DE AGENTE DEL SUBPRODUCTO
					$tiposAgentes_agentes = array();
					$j = 0;
					$query="SELECT 
							id_tipoagente
						FROM T_Subproducto_TipoAgente
						WHERE id_subproducto = :subproducto";
					$stmt = $this->conn->prepare($query);
					$stmt->bindparam(":subproducto",$subproducto);
					$stmt->execute();
					while($row=$stmt->fetch(PDO::FETCH_ASSOC))
					{
						$tiposAgentes_agentes[$j] = $row['id_tipoagente'];
						$j++;
					}
					
					//CONSULTA PARA OBTENER LOS AGENTES CON EL TIPO DE AGENTE REQUERIDO
					if($stmt->rowCount() > 1)
					{
						$x = 0;
						$agentesArray = array();
						foreach($tiposAgentes_agentes as $aux)
						{
							$query="SELECT
								A.id_agente
							FROM T_Agentes AS A
							INNER JOIN T_TipoAgente_Agente AS TAA
							ON TAA.id_agente = A.id_agente
							WHERE TAA.id_tipoagente = :aux";
							$stmt2 = $this->conn->prepare($query);
							$stmt2->bindparam(":aux",$aux);
							$stmt2->execute();
							$i = 0;
							while($row2=$stmt2->fetch(PDO::FETCH_ASSOC))
							{
								$agentesArray[$i] = $row2['id_agente'];
								$i++;
							}
							$x++;
						}
					}
					else
					{
						$agentesArray = array();
						$query="SELECT
							A.id_agente
						FROM T_Agentes AS A
						INNER JOIN T_TipoAgente_Agente AS TAA
						ON TAA.id_agente = A.id_agente
						WHERE TAA.id_tipoagente = :tiposAgentes_agentes
						GROUP BY A.id_agente";
						$stmt2 = $this->conn->prepare($query);
						$stmt2->bindparam(":tiposAgentes_agentes",$tiposAgentes_agentes[0]);
						$stmt2->execute();
						$i = 0;
						while($row2=$stmt2->fetch(PDO::FETCH_ASSOC))
						{
							$agentesArray[$i] = $row2['id_agente'];
							$i++;
						}
					}
					
					if($stmt2->rowCount() == 0)
					{
						print("No hay agentes preparados");
					}
					else
					{
						$len = strlen($fecha_realizacion);
						$agentesOcupadosArray = array();
						if($len == 41)
						{
							$inicio = substr($fecha_realizacion,0,19);
							$fin = substr($fecha_realizacion,22,19);
							//CONSULTA PARA OBTENER A LOS AGENTES OCUPADOS EN LA FECHA REQUERIDA
							$query="SELECT 
								A.id_agente
							FROM `T_Calendario_Agentes` AS CA
							INNER JOIN T_Agentes AS A
							ON A.id_agente = CA.id_agente
							WHERE CA.inicio_calendario = :inicio AND CA.fin_calendario = :fin
							GROUP BY A.id_agente";
							$stmt3 = $this->conn->prepare($query);
							$stmt3->bindparam(":inicio",$inicio);
							$stmt3->bindparam(":fin",$fin);
							$stmt3->execute();
							$i = 0;
							while($row3=$stmt3->fetch(PDO::FETCH_ASSOC))
							{
								$agentesOcupadosArray[$i] = $row3['id_agente'];
								$i++;
							}
						}
						else
						{
							$fecha_completa = $fecha_realizacion.' 00:00:00';
							$query="SELECT 
								A.id_agente
							FROM `T_Calendario_Agentes` AS CA
							INNER JOIN T_Agentes AS A
							ON A.id_agente = CA.id_agente
							WHERE CA.inicio_calendario = :fecha_completa AND CA.fin_calendario = :fecha_completa
							GROUP BY A.id_agente";
							$stmt3 = $this->conn->prepare($query);
							$stmt3->bindparam(":fecha_completa",$fecha_completa);
							$stmt3->execute();
							$i = 0;
							while($row3=$stmt3->fetch(PDO::FETCH_ASSOC))
							{
								$agentesOcupadosArray[$i] = $row3['id_agente'];
								$i++;
							}
							print($fecha_completa);
						}
						
						//RECORRER EL ARRAY DE LOS AGENTES CALIFICADOS PARA EXCLUIR LOS QUE NO ESTÁN DISPONIBLES
						$agentesDisponiblesArray = array();
						$x = 0;
						if($stmt3->rowCount() == 0)
						{
							foreach($agentesArray as $aux1)
							{
								$agentesDisponiblesArray[$x] = $aux1;
								$x++;
							}
						}
						else
						{
							foreach($agentesArray as $aux1)
							{
								foreach($agentesOcupadosArray as $aux2)
								{
									if($aux1 != $aux2)
									{
										$agentesDisponiblesArray[$x] = $aux1;
									}
								}
								$x++;
							}
						}
						$contarAgentes = sizeof($agentesDisponiblesArray);
						if($contarAgentes == 1)
						{
							print("Agente asignado con el ID ".$agentesDisponiblesArray[0]);
						}
						elseif($contarAgentes == 0)
						{
							print("No hay agentes disponibles");
						}
						else
						{
							$y = 0;
							$agentesOferta = array();
							foreach($agentesDisponiblesArray as $aux)
							{
								if($aux != 0)
								{
									//CONSULTA PARA SABER A QUIÉN LE CONVIENE EN CUESTION DE OFERTA
									$query="SELECT 
										A.id_agente, 
										A.nombre_agente, 
										A.oferta_actual, 
										(
											CASE WHEN (
												SELECT SUM( S.costo_honorarios )
												FROM T_Detalle_Bitacora AS DB
												INNER JOIN T_Subproductos AS S
												ON S.id_subproducto = DB.id_subproducto
												WHERE DB.id_agente = :aux
											) = NULL
											THEN 0 
											ELSE (
												SELECT SUM( S.costo_honorarios )
												FROM T_Detalle_Bitacora AS DB
												INNER JOIN T_Subproductos AS S
												ON S.id_subproducto = DB.id_subproducto
												WHERE DB.id_agente = :aux
											)
											END
										)AS realidad, 
										(
											CASE WHEN (
												SELECT 
													COUNT( AUXDB.id_detallebitacora ) 
												FROM T_Detalle_Bitacora AS AUXDB
												WHERE AUXDB.id_mesejecucion = :month AND AUXDB.id_agente = A.id_agente
											) = NULL 
											THEN 0 
											ELSE (
												SELECT 
													COUNT( AUXDB.id_detallebitacora ) 
												FROM T_Detalle_Bitacora AS AUXDB
												WHERE AUXDB.id_mesejecucion = :month AND AUXDB.id_agente = A.id_agente
											)
											END
										) AS eventos_delmes
									FROM  `T_Agentes` AS A
									WHERE A.id_agente = :aux
									GROUP BY A.id_agente";
									$stmt4 = $this->conn->prepare($query);
									$stmt4->bindparam(":aux",$aux);
									$stmt4->bindparam(":month",$month);
									$stmt4->execute();
									while($row4=$stmt4->fetch(PDO::FETCH_ASSOC))
									{
										$agentesOferta[$y]['id_agente'] = $row4['id_agente'];
										$agentesOferta[$y]['nombre_agente'] = $row4['nombre_agente'];
										$agentesOferta[$y]['oferta_actual'] = floatval($row4['oferta_actual']);
										$agentesOferta[$y]['realidad'] = floatval($row4['realidad']);
										$agentesOferta[$y]['diferencia'] = floatval($row4['oferta_actual']-$row4['realidad']);
										$agentesOferta[$y]['eventos_delmes'] = $row4['eventos_delmes'];
									}
								}
								$y++;
							}
							$eventos = 100;
							$diferencia = 0;
							$id = '';
							foreach($agentesOferta as $aux)
							{
								if($aux['eventos_delmes'] == $eventos)
								{
									$eventos = $aux['eventos_delmes'];
									$id = $id.'+'.$aux['id_agente'];
								}
								elseif($aux['eventos_delmes'] < $eventos)
								{
									$eventos = $aux['eventos_delmes'];
									$id = $aux['id_agente'];
									$nombre = $aux['nombre_agente'];
								}
							}
							if(strpos($id, '+') !== false)
							{
								foreach($agentesOferta as $aux)
								{
									if($aux['diferencia'] == $diferencia)
									{
										$diferencia = $aux['diferencia'];
										$id = $id.'+'.$aux['id_agente'];
									}
									elseif($aux['diferencia'] > $diferencia)
									{
										$diferencia = $aux['diferencia'];
										$id = $aux['id_agente'];
										$nombre = $aux['nombre_agente'];
									}
								}
								if(strpos($id, '+') !== false)
								{
									print("Hay más de dos agentes disponibles y con la misma diferencia en su oferta");
								}
								else
								{
									print("Agente asignado ".$nombre);
								}
							}
							else
							{
								print("Agente asignado ".$nombre);
							}
						}
					}
					exit;
				}
				
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
		
		public function updateBitacora($ordenes,$tipo_anticipo,$monto_anticipo)
		{
			$this->conn->beginTransaction();
			try
			{
				$query="UPDATE `T_Bitacora` SET `id_adminanticipos`=:tipo_anticipo,`monto_anticipo`=:monto_anticipo  WHERE `id_bitacora`= :ordenes";
				$stmt = $this->conn->prepare($query);
				$stmt->bindparam(":tipo_anticipo",$tipo_anticipo);
				$stmt->bindparam(":monto_anticipo",$monto_anticipo);
				$stmt->bindparam(":ordenes",$ordenes);
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
		public function updateDetalleOI($ordenes,$version,$subproducto,$costo,$costomateriales,$costosadicionales,$descuento,$tema_real,$evento,$id_evento,$personas,$nivel,$equipo,$material,
						$fecha_cobranza,$sede,$sede_particular,$fecha_realizacion,$mes_ejecucion,$estatus_ejecucion,$estatus_costo,$mes_costo,$estatus_factura,$mes_factura,$factura,$estatus_cobranza,$mes_cobranza,$anomalia,
						$viaticos,$transportacion_local,$alimentos,$hospedaje,$nodeducible,$desglose,$transportacion_foranea,$anticipo_viaticos,$rembolso,$observaciones_viaticos,$gasto_admin,$fecha_entrega_viaticos,$estatusdetalleBitacora,$estatus_ejecucion_viaticos,$estatus_facturacion_viaticos,$estatus_cobranza_viaticos,$estatus_costo_viaticos)
		{
			$this->conn->beginTransaction();
			try
			{
				$query="UPDATE `T_Detalle_Bitacora` SET `version`=:version, `ultima_modificacion`=NOW(), `id_subproducto`=:subproducto, costo_bitacora = :costo, 
				`costo_adicionales`=:costosadicionales, `costo_materiales`=:costomateriales, `tema`=:tema_real,`evento`=:evento,`id_del_evento`=:id_evento,
				`no_personas`=:personas,`id_nivel`=:nivel,`id_tipoequipo`=:equipo,`id_tipomaterial`=:material,
				`fecha_ejecucion`=:fecha_realizacion,`id_mesejecucion`=:mes_ejecucion,`id_estatusejecucion`=:estatus_ejecucion,`id_estatuscosto`=:estatus_costo,
				`id_mescosto`=:mes_costo,`id_estatusfacturacion`=:estatus_factura,`id_mesfacturacion`=:mes_factura,`numero_factura`=:factura,
				`id_estatuscobranza`=:estatus_cobranza,`id_mescobranza`=:mes_cobranza,`descuento`=:descuento,`anomalia`=:anomalia,  
				`viaticos`=:viaticos,`transportacion_local`=:transportacion_local,`alimentos`=:alimentos,`hospedaje`=:hospedaje,`no_deducibles`=:nodeducible,
				`desglose`=:desglose,`transportacion_foranea`=:transportacion_foranea,`anticipo_entregado`=:anticipo_viaticos,`rembolso`=:rembolso,`observaciones_viaticos`=:observaciones_viaticos,
				`gasto_admin`=:gasto_admin,`fecha_entrega_viaticos`=:fecha_entrega_viaticos, `fecha_cobranza`=:fecha_cobranza, `id_estado`=:sede, `sede_particular`=:sede_particular, `id_estatusdetallebitacora`=:estatusdetalleBitacora, `id_estatusejecucion_viaticos`=:estatus_ejecucion_viaticos, `id_estatuscobranza_viaticos`=:estatus_cobranza_viaticos, `id_estatusfacturacion_viaticos`=:estatus_facturacion_viaticos, `id_estatuscosto_viaticos`=:estatus_costo_viaticos
				WHERE `id_detallebitacora`= :ordenes";
				$stmt = $this->conn->prepare($query);
				$stmt->bindparam(":version",$version);
				$stmt->bindparam(":subproducto",$subproducto);
				$stmt->bindparam(":costo",$costo);
				$stmt->bindparam(":costosadicionales",$costosadicionales);
				$stmt->bindparam(":costomateriales",$costomateriales);
				$stmt->bindparam(":tema_real",$tema_real);
				$stmt->bindparam(":evento",$evento);
				$stmt->bindparam(":id_evento",$id_evento);
				$stmt->bindparam(":personas",$personas);
				$stmt->bindparam(":nivel",$nivel);
				$stmt->bindparam(":equipo",$equipo);
				$stmt->bindparam(":material",$material);
				$stmt->bindparam(":fecha_realizacion",$fecha_realizacion);
				$stmt->bindparam(":mes_ejecucion",$mes_ejecucion);
				$stmt->bindparam(":estatus_ejecucion",$estatus_ejecucion);
				$stmt->bindparam(":estatus_costo",$estatus_costo);
				$stmt->bindparam(":mes_costo",$mes_costo);
				$stmt->bindparam(":estatus_factura",$estatus_factura);
				$stmt->bindparam(":mes_factura",$mes_factura);
				$stmt->bindparam(":factura",$factura);
				$stmt->bindparam(":estatus_cobranza",$estatus_cobranza);
				$stmt->bindparam(":mes_cobranza",$mes_cobranza);
				$stmt->bindparam(":descuento",$descuento);
				$stmt->bindparam(":anomalia",$anomalia);
				$stmt->bindparam(":viaticos",$viaticos);
				$stmt->bindparam(":transportacion_local",$transportacion_local);
				$stmt->bindparam(":alimentos",$alimentos);
				$stmt->bindparam(":hospedaje",$hospedaje);
				$stmt->bindparam(":nodeducible",$nodeducible);
				$stmt->bindparam(":desglose",$desglose);
				$stmt->bindparam(":transportacion_foranea",$transportacion_foranea);
				$stmt->bindparam(":anticipo_viaticos",$anticipo_viaticos);
				$stmt->bindparam(":rembolso",$rembolso);
				$stmt->bindparam(":observaciones_viaticos",$observaciones_viaticos);
				$stmt->bindparam(":gasto_admin",$gasto_admin);
				$stmt->bindparam(":fecha_entrega_viaticos",$fecha_entrega_viaticos);
				$stmt->bindparam(":fecha_cobranza",$fecha_cobranza);
				$stmt->bindparam(":sede",$sede);
				$stmt->bindparam(":sede_particular",$sede_particular);
				$stmt->bindparam(":estatusdetalleBitacora",$estatusdetalleBitacora);
				$stmt->bindparam(":estatus_ejecucion_viaticos",$estatus_ejecucion_viaticos);
				$stmt->bindparam(":estatus_cobranza_viaticos",$estatus_cobranza_viaticos);
				$stmt->bindparam(":estatus_facturacion_viaticos",$estatus_facturacion_viaticos);
				$stmt->bindparam(":estatus_costo_viaticos",$estatus_costo_viaticos);
				$stmt->bindparam(":ordenes",$ordenes);
				$stmt->execute();
				$this->conn->commit();
				return true;
			}
			catch(PDOException $e)
			{
				echo $e->getMessage();
				$this->conn->rollBack();
				exit;
				return false;
			}
		}
		public function deleteDetalleOI($ordenes)
		{
			$this->conn->beginTransaction();
			try
			{
				$query="DELETE FROM `T_Detalle_Bitacora` WHERE `id_detallebitacora`= :ordenes";
				$stmt = $this->conn->prepare($query);
				$stmt->bindparam(":ordenes",$ordenes);
				$stmt->execute();
				$this->conn->commit();
				return true;
			}
			catch(PDOException $e)
			{
				echo $e->getMessage();
				$this->conn->rollBack();
				$error = $e->getMessage();
				$fichero = fopen("logs.txt","w") or die("Unable to open file");
				fwrite($fichero, $error);
				fclose($fichero);
				return false;
			}
		}
		
		
		//INSERT SOLICITUD
		public function insertSolicitud($cliente,$bitacoraOI,$concepto,$rfc_destinatario,$notas,$monto_factura)
		{
			$this->conn->beginTransaction();
			try
			{
				$query="INSERT INTO `T_Solicitudes_Factura`(`id_cliente`, `id_bitacora`, `concepto`, `rfc_destinatario`, `notas`, `monto_factura`, `id_estatus_solicitud`)
				VALUES (:cliente,:bitacoraOI,:concepto,:rfc_destinatario,:notas,:monto_factura,1)";
				$stmt = $this->conn->prepare($query);
				$stmt->bindparam(":cliente",$cliente);
				$stmt->bindparam(":bitacoraOI",$bitacoraOI);
				$stmt->bindparam(":concepto",$concepto);
				$stmt->bindparam(":rfc_destinatario",$rfc_destinatario);
				$stmt->bindparam(":notas",$notas);
				$stmt->bindparam(":monto_factura",$monto_factura);
				$stmt->execute();
				$last_id = $this->conn->lastInsertId();
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
		public function insertSolicitudDetalle($detalle_bitacora,$detalle,$evento,$precio)
		{
			$this->conn->beginTransaction();
			try
			{
				$query="INSERT INTO `T_Detalle_SolicitudFacturas`(`id_solicitud_factura`, `id_detallebitacora`, `id_facturacion_completa`, `monto_facturado`)
				VALUES (:detalle_bitacora,:detalle,:evento,:precio)";
				$stmt = $this->conn->prepare($query);
				$stmt->bindparam(":detalle_bitacora",$detalle_bitacora);
				$stmt->bindparam(":detalle",$detalle);
				$stmt->bindparam(":evento",$evento);
				$stmt->bindparam(":precio",$precio);
				$stmt->execute();
				$last_id = $this->conn->lastInsertId();
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
		
		//CONVERTIR A BITACORA
		public function showOI($ordenes)
		{
			$stmt2 = $this->conn->prepare("SELECT * FROM T_Ordenes_Intervencion AS O
				INNER JOIN T_Clientes AS C
				ON O.id_cliente = C.id_cliente
				WHERE O.id_oi = :ordenes");
			$stmt2->execute(array(':ordenes'=>$ordenes));
			$row2=$stmt2->fetch(PDO::FETCH_ASSOC);
			return $row2;
		}
		public function showOIDetalle($ordenes)
		{
			$query = "SELECT 
					DO.id_detalleoi,
					Fam.familia_producto,
					Fam.id_familiaproducto,
					Prod.id_producto,
					Prod.nombre_producto,
					DO.id_subproducto,
					Sub.nombre_subproducto,
					DO.tema,
					DO.personas,
					DO.id_nivel,
					N.nivel,
					DO.precio_oi,
					DO.descuento,
					DO.costo_oi,
					(CASE WHEN DO.precio_oi = 0 THEN 0 ELSE DO.costo_oi/DO.precio_oi END) AS porcentajeCosto,
					(CASE WHEN DO.precio_oi = 0 THEN 0 ELSE (DO.precio_oi-DO.costo_oi)/DO.precio_oi END) AS porcentajeMargen,
					Sub.costo_honorarios,
					(CASE WHEN DO.precio_oi = 0 THEN 0 ELSE Sub.costo_honorarios/DO.precio_oi END) AS porcentajeHonorarios,
					(CASE WHEN DO.precio_oi = 0 THEN 0 ELSE Sub.costo_materiales/DO.precio_oi END) AS porcentajeMateriales,
					Sub.costo_materiales,
					DO.cantidad_oi
				FROM  T_DetalleOI AS DO
				INNER JOIN T_Subproductos AS Sub
				ON DO.id_subproducto = Sub.id_subproducto
				INNER JOIN T_Productos AS Prod
				ON Sub.id_producto = Prod.id_producto
				INNER JOIN T_FamiliaProductos AS Fam
				ON Prod.id_familiaproducto = Fam.id_familiaproducto
				INNER JOIN T_Niveles AS N
				ON N.id_nivel = DO.id_nivel
				WHERE DO.id_oi = :ordenes AND DO.id_estatusoi = 1
				ORDER BY DO.id_detalleoi, DO.id_subproducto ASC";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":ordenes",$ordenes);
			$stmt->execute();
			
			
			$query = "SELECT SUM(DO.cantidad_oi) AS CantidadTotal
				FROM  T_DetalleOI AS DO
				INNER JOIN T_Subproductos AS Sub
				ON DO.id_subproducto = Sub.id_subproducto
				INNER JOIN T_Productos AS Prod
				ON Sub.id_producto = Prod.id_producto
				INNER JOIN T_FamiliaProductos AS Fam
				ON Prod.id_familiaproducto = Fam.id_familiaproducto
				INNER JOIN T_Niveles AS N
				ON N.id_nivel = DO.id_nivel
				WHERE DO.id_oi = :ordenes AND DO.id_estatusoi = 1";
			$stmt2 = $this->conn->prepare($query);
			$stmt2->bindparam(":ordenes",$ordenes);
			$stmt2->execute();
			$row2=$stmt2->fetch(PDO::FETCH_ASSOC);
			
			$contador = 1;
			$auxiliar = 1;
			$cantidad = 0;
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				$subproducto = $row['id_subproducto'];
				$query = "SELECT 
							id_tipoagente,
							costo_tipoagente_subproducto
						FROM T_Subproducto_TipoAgente
						WHERE id_subproducto = :subproducto";
				$stmt3 = $this->conn->prepare($query);
				$stmt3->bindparam(":subproducto",$subproducto);
				$stmt3->execute();
				if($stmt3->rowCount() == 0)
				{
					$row3 = array();
					$row3['id_tipoagente'] = 0;
					$row3['costo_tipoagente_subproducto'] = 0;
					$styleHidden = "display:none;";
					$comprobacion = FALSE;
					$totalAgentes = 0;
				}
				elseif($stmt3->rowCount() == 1)
				{
					$row3=$stmt3->fetch(PDO::FETCH_ASSOC);
					$styleHidden = "display:none;";
					$comprobacion = FALSE;
					$totalAgentes = 1;
				}
				else
				{
					$arrayAux = array();
					$arrayAux2 = array();
					$x=0;
					while($row3=$stmt3->fetch(PDO::FETCH_ASSOC))
					{
						$arrayAux[$x] = $row3['id_tipoagente'];
						$arrayAux2[$x] = $row3['costo_tipoagente_subproducto'];
						$x++;
					}
					$row3['id_tipoagente'] = $arrayAux[0];
					$row3['costo_tipoagente_subproducto'] = $arrayAux2[0];
					$styleHidden = "";
					$comprobacion = TRUE;
					$totalAgentes = $x;
				}
				//SELECT SUBTEMAS
				$subtema = array();
				$temaAux = $row['tema'];
				if($row['tema'] == "Visión de negocio")
				{
					$subtema[1] = "La gestión y el rol del mando";
					$subtema[2] = "Responsabilidad";
					$subtema[3] = "Pensamiento estratégico";
					$subtema[4] = "Oferta de valor y ventaja competitiva";
				}
				elseif($row['tema'] == "Orientación a resultados")
				{
					$subtema[1] = "Definición de actividades";
					$subtema[2] = "Supervisión y control";
					$subtema[3] = "Definición de objetivos, indicadores y metas";
					$subtema[4] = "Distribución de actividades y cargas de trabajo";
				}
				elseif($row['tema'] == "Comunicación interpersonal")
				{
					$subtema[1] = "Escucha";
					$subtema[2] = "Generación de acuerdos";
					$subtema[3] = "Seguimiento a acuerdos y retroalimentación";
					$subtema[4] = "Elaboración de preguntas";
				}
				elseif($row['tema'] == "Liderazgo")
				{
					$subtema[1] = "Adaptación al colaborador";
					$subtema[2] = "Desarrollo del colaborador";
					$subtema[3] = "Visión compartida";
					$subtema[4] = "Facultamiento";
				}
				elseif($row['tema'] == "Sinergia Organizacional")
				{
					$subtema[1] = "Trabajo en equipo";
					$subtema[2] = "Formación de equipos de alto desempeño";
					$subtema[3] = "Interequipos";
					$subtema[4] = "Sociedad estratégica de líderes";
				}
				elseif($row['tema'] == "Análisis de problemas y toma de decisiones")
				{
					$subtema[1] = "Análisis de causas";
					$subtema[2] = "Aseguramiento de la resolución";
					$subtema[3] = "Establecimiento de brechas";
					$subtema[4] = "Aseguramiento de la resolución";
				}
				elseif($row['tema'] == "Administración de microproyectos")
				{
					$subtema[1] = "Planeación de un proyecto";
					$subtema[2] = "Ejecución y cierre";
					$subtema[3] = "Definición e inicio de un proyecto";
					$subtema[4] = "Control y cierre";
				}
				elseif($row['tema'] == "Inteligencia emocional")
				{
					$subtema[1] = "Consciencia de si";
					$subtema[2] = "Autoconocimiento";
					$subtema[3] = "Autorregulación";
					$subtema[4] = "Sinergía emocional";
				}
				elseif($row['tema'] == "Inteligencia social")
				{
					$subtema[1] = "Detener un conflicto en crecimiento";
					$subtema[2] = "Cerrar un conflicto";
					$subtema[3] = "Empatía y asertividad";
					$subtema[4] = "Aprovechamiento del conflicto";
				}
				else
				{
					$subtema[1] = "";
					$subtema[2] = "";
					$subtema[3] = "";
					$subtema[4] = "";
				}
				
				//PRODUCTO FDP
				if($row['id_producto'] == 40)
				{
					$cantidad = $row['cantidad_oi']*4;
					$row['precio_oi'] = $row['precio_oi']/4;
					$row['costo_honorarios'] = $row['costo_honorarios']/4;
					$row3['costo_tipoagente_subproducto'] = $row3['costo_tipoagente_subproducto']/4;
					$row['costo_materiales'] = $row['costo_materiales']/4;
					$row['costo_oi'] = $row['costo_oi']/4;
				}
				elseif($row['id_subproducto'] == 129)
				{
					$cantidad = $row['cantidad_oi']*4;
					$row['precio_oi'] = $row['precio_oi'];
					$row['costo_honorarios'] = $row['costo_honorarios'];
					$row3['costo_tipoagente_subproducto'] = $row3['costo_tipoagente_subproducto'];
					$row['costo_materiales'] = $row['costo_materiales'];
					$row['costo_oi'] = $row['costo_oi'];
				}
				elseif($row['id_producto'] == 41)
				{
				$cantidad = $row['cantidad_oi']*3;
					$row['precio_oi'] = $row['precio_oi']/3;
					$row['costo_honorarios'] = $row['costo_honorarios']/3;
					$row3['costo_tipoagente_subproducto'] = $row3['costo_tipoagente_subproducto']/3;
					$row['costo_materiales'] = $row['costo_materiales']/3;
					$row['costo_oi'] = $row['costo_oi']/3;
				}
				elseif($row['id_producto'] == 42)
				{
					$cantidad = $row['cantidad_oi']*2;
					$row['precio_oi'] = $row['precio_oi']/2;
					$row['costo_honorarios'] = $row['costo_honorarios']/2;
					$row3['costo_tipoagente_subproducto'] = $row3['costo_tipoagente_subproducto']/2;
					$row['costo_materiales'] = $row['costo_materiales']/2;
					$row['costo_oi'] = $row['costo_oi']/2;
				}
				else
				{
					$cantidad = $row['cantidad_oi'];
				}
				for($i=1;$i<=$cantidad;$i++)
				{
					if($row['id_producto'] == 40)
					{
						$cantidad_aux = $cantidad / 4;
						$cantidad_aux2 = $i % 4;
						if($cantidad_aux2 == 3)
						{
							$row['tema'] = $temaAux.' - '.$subtema[3];
						}
						elseif($cantidad_aux2 == 0)
						{
							$row['tema'] = $temaAux.' - '.$subtema[4];
						}
						elseif($cantidad_aux2 == 1)
						{
							$row['tema'] = $temaAux.' - '.$subtema[1];
						}
						elseif($cantidad_aux2 == 2)
						{
							$row['tema'] = $temaAux.' - '.$subtema[2];
						}
					}
					elseif($row['id_subproducto'] == 129)
					{
						$cantidad_aux = $cantidad / 4;
						$cantidad_aux2 = $i % 4;
						if($cantidad_aux2 == 1)
						{
							$row['precio_oi'] = $row['precio_oi'];
						}
						else
						{
							$row['precio_oi'] = 0;
							$row['porcentajeCosto'] = 0;
							$row['costo_materiales'] = 0;
							$row['porcentajeMargen'] = 0;
						}
					}
					elseif($row['id_producto'] == 41)
					{
						$cantidad_aux = $cantidad / 3;
						$cantidad_aux2 = $i % 3;
						if($cantidad_aux2 == 0)
						{
							$row['tema'] = $temaAux.' - '.$subtema[3];
						}
						elseif($cantidad_aux2 == 1)
						{
							$row['tema'] = $temaAux.' - '.$subtema[1];
						}
						elseif($cantidad_aux2 == 2)
						{
							$row['tema'] = $temaAux.' - '.$subtema[2];
						}
						
					}
					elseif($row['id_producto'] == 42)
					{
						$cantidad_aux = $cantidad / 2;
						$cantidad_aux2 = $i % 2;
						if($cantidad_aux2 == 1)
						{
							$row['tema'] = $temaAux.' - '.$subtema[1];
						}
						elseif($cantidad_aux2 == 0)
						{
							$row['tema'] = $temaAux.' - '.$subtema[2];
						}
					}
						
					$variable_familia = 'familia'.$auxiliar;
					$variable_producto = 'producto'.$auxiliar;
					$variable_subproducto = 'subproducto'.$auxiliar;
					$variable_tema = 'tema'.$auxiliar;
					$variable_evento = 'evento'.$auxiliar;
					$variable_id_evento = 'id_evento'.$auxiliar;
					$variable_personas = 'personas'.$auxiliar;
					$variable_nivel = 'nivel'.$auxiliar;
					$evento = $i.$row['tema'].'-';
					$variable_tipoagente = 'tipoagente0_'.$auxiliar;
					$variable_agente = 'agente0_'.$auxiliar;
					$variable_equipo = 'equipo'.$auxiliar;
					$variable_material = 'material'.$auxiliar;
					$variable_precio = 'precio'.$auxiliar;
					$variable_descuento = 'descuento'.$auxiliar;
					$variable_costo = 'costo'.$auxiliar;
					$variable_porcentajecosto = 'porcentajecosto'.$auxiliar;
					$variable_honorarios = 'honorarios'.$auxiliar;
					$variable_honorarios_aux = 'honorarios0_'.$auxiliar;
					$variable_porcentajehonorarios_aux = 'porcentajehonorarios0_'.$auxiliar;
					$variable_costomateriales = 'costomateriales'.$auxiliar;
					$variable_costosadicionales = 'costosadicionales'.$auxiliar;
					$variable_porcentajecostosadicionales = 'porcentajecostosadicionales'.$auxiliar;
					$variable_margen = 'margen'.$auxiliar;
					$variable_porcentajemargen = 'porcentajemargen'.$auxiliar;
					$variable_sede = 'sede'.$auxiliar;
					$variable_sede_particular = 'sede_particular'.$auxiliar;
					$variable_fecha_cobranza = 'fecha_cobranza_'.$auxiliar;
					$variable_fecha_realizacion1 = 'fecha_realizacion_1_'.$auxiliar;
					$variable_fecha_realizacion2 = 'fecha_realizacion_2_'.$auxiliar;
					$variable_fecha_realizacion_horario2 = 'fecha_realizacion_2_horario'.$auxiliar;
					$variable_fecha_realizacion3 = 'fecha_realizacion_3_'.$auxiliar;
					$variable_class_fecha_realizacion1 = 'fecha_realizacion_1_'.$auxiliar;
					$variable_class_fecha_realizacion2 = 'fecha_realizacion_2_'.$auxiliar;
					$variable_class_fecha_realizacion3 = 'fecha_realizacion_3_'.$auxiliar;
					$variable_name_radio_button_costomaterial = 'radio-costomaterial'.$auxiliar;
					$variable_name_radio_button_costomaterial1 = 'radio-costomaterial_1_'.$auxiliar;
					$variable_name_radio_button_costomaterial2 = 'radio-costomaterial_2_'.$auxiliar;
					$variable_name_radio_button = 'radio-fecharealizacion'.$auxiliar;
					$variable_radio_button1 = 'radio-fecharealizacion_1_'.$auxiliar;
					$variable_radio_button2 = 'radio-fecharealizacion_2_'.$auxiliar;
					$variable_radio_button3 = 'radio-fecharealizacion_3_'.$auxiliar;
					$variable_mes_ejecucion = 'mes_ejecucion'.$auxiliar;
					$variable_estatus_ejecucion = 'estatus_ejecucion'.$auxiliar;
					$variable_anomalia = 'anomalia'.$auxiliar;
					$variable_estatus_costo = 'estatus_costo'.$auxiliar;
					$variable_mes_costo = 'mes_costo'.$auxiliar;
					$variable_estatus_factura = 'estatus_factura'.$auxiliar;
					$variable_mes_factura = 'mes_factura'.$auxiliar;
					$variable_factura = 'factura'.$auxiliar;
					$variable_estatus_cobranza = 'estatus_cobranza'.$auxiliar;
					$variable_mes_cobranza = 'mes_cobranza'.$auxiliar;
					$variable_ejecutar_class = 'ejecutar'.$auxiliar;
					$variable_costear_class = 'costear'.$auxiliar;
					$variable_facturar_class = 'facturar'.$auxiliar;
					$variable_cobrar_class = 'cobrar'.$auxiliar;
					$variable_viaticos = 'viaticos'.$auxiliar;
					$variable_transportacion = 'transportacion'.$auxiliar;
					$variable_alimentos = 'alimentos'.$auxiliar;
					$variable_hospedaje = 'hospedaje'.$auxiliar;
					$variable_nodeducible = 'nodeducible'.$auxiliar;
					$variable_suma_viaticos = 'suma_viaticos'.$auxiliar;
					$variable_iva = 'iva'.$auxiliar;
					$variable_total_sinavion = 'total_sinavion'.$auxiliar;
					$variable_desglose = 'desglose'.$auxiliar;
					$variable_avionsiniva = 'avionsiniva'.$auxiliar;
					$variable_ivaavion = 'ivaavion'.$auxiliar;
					$variable_total_avion = 'total_avion'.$auxiliar;
					$variable_anticipo_viaticos = 'anticipo_viaticos'.$auxiliar;
					$variable_rembloso = 'rembolso'.$auxiliar;
					$variable_total_cobrar = 'total_cobrar'.$auxiliar;
					$variable_observaciones_viaticos = 'observaciones_viaticos'.$auxiliar;
					$variable_gasto_admin = 'gasto_admin'.$auxiliar;
					$variable_fecha_entrega_viaticos = 'fecha_entrega_viaticos'.$auxiliar;
					$variable_total_agentes = 'total_agentes'.$auxiliar;
					$variable_auxiliar_tipoFDP = 'auxiliar_tipoFDP'.$auxiliar;
					$variable_auxiliar_tipoFDP_cantidad = 'auxiliar_tipoFDP_cantidad'.$auxiliar;
					$variable_estatus_ejecucion_viaticos = 'estatus_ejecucion_viaticos'.$auxiliar;
					$variable_estatus_factura_vitaticos = 'estatus_factura_viaticos'.$auxiliar;
					$variable_estatus_cobranza_viaticos = 'estatus_cobranza_viaticos'.$auxiliar;
					$variable_estatus_costo_viaticos = 'estatus_costo_viaticos'.$auxiliar;
					$variable_detalleoi = 'detalleoi'.$auxiliar;
					if($row['id_producto'] == 40 || $row['id_producto'] == 41 || $row['id_producto'] == 42)
					{
						$variable_auxiliar_tipoFDP_valor = 1;
					}
					else
					{
						$variable_auxiliar_tipoFDP_valor = 0;
					}
			?>
			<!--SCRIPT INICIALIZA DATEPICKERS-->
			<script type="text/javascript">
				$(function() {
					$('input[type=radio][name=<?php print($variable_name_radio_button);?>]').change(function() {
						$('.<?php print($variable_class_fecha_realizacion1);?>').val('');
						$('.<?php print($variable_class_fecha_realizacion2);?>').val('');
						$('.<?php print($variable_class_fecha_realizacion3);?>').val('');
						if (this.value == 1) 
						{
							//PERIODO
							$('.<?php print($variable_class_fecha_realizacion1);?>').show();
							$('.<?php print($variable_class_fecha_realizacion2);?>').hide();
							$('.<?php print($variable_class_fecha_realizacion3);?>').hide();
							/*$('.<?php print($variable_class_fecha_realizacion1);?>').prop('required','true');
									$('.<?php print($variable_class_fecha_realizacion2);?>').prop('required','false');
									$('.<?php print($variable_class_fecha_realizacion3);?>').prop('required','false');*/
						}
						else if (this.value == 2) 
						{
							//UNICA FECHA
							$('.<?php print($variable_class_fecha_realizacion1);?>').hide();
							$('.<?php print($variable_class_fecha_realizacion2);?>').show();
							$('.<?php print($variable_class_fecha_realizacion3);?>').hide();
							/*$('.<?php print($variable_class_fecha_realizacion1);?>').prop('required','false');
									$('.<?php print($variable_class_fecha_realizacion2);?>').prop('required','true');
									$('.<?php print($variable_class_fecha_realizacion3);?>').prop('required','false');*/
						}
						else
						{
							//VARIAS FECHAS
							$('.<?php print($variable_class_fecha_realizacion1);?>').hide();
							$('.<?php print($variable_class_fecha_realizacion2);?>').hide();
							$('.<?php print($variable_class_fecha_realizacion3);?>').show();
							/*$('.<?php print($variable_class_fecha_realizacion1);?>').prop('required','false');
									$('.<?php print($variable_class_fecha_realizacion2);?>').prop('required','false');
									$('.<?php print($variable_class_fecha_realizacion3);?>').prop('required','true');*/
						}
					});
					$('.<?php print($variable_class_fecha_realizacion1);?>').daterangepicker({
							startDate: moment(),
							endDate: moment(),
							minDate: '01/01/2010',
							maxDate: '12/31/2999',
							showDropdowns: true,
							showWeekNumbers: true,
							timePicker: true,
							timePickerIncrement: 1,
							timePicker24Hour: true,
							opens: 'left',
							buttonClasses: ['btn btn-default'],
							applyClass: 'small bg-green',
							cancelClass: 'small ui-state-default',
							format: 'DD-MM-YYYY HH:mm:ss',
							separator: ' a ',
							locale: {
								applyLabel: 'Aplicar',
								fromLabel: 'Desde',
								toLabel: 'Hasta',
								daysOfWeek: ['Do', 'Lu', 'Mar', 'Mie', 'Jue', 'Vi', 'Sa'],
								monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
								firstDay: 1
							}
						},
						function(start, end) 
						{
							console.log("Callback has been called!");
							$('.<?php print($variable_class_fecha_realizacion1);?>').val(start.format('YYYY-MM-DD HH:mm:ss') + ' - ' + end.format('YYYY-MM-DD HH:mm:ss'));
							var date = end.format('DD-MM-YYYY');
							var nextDate = new Date(start);
							nextDate.setDate(nextDate.getDate() - 7);
							var dd = nextDate.getDate();
							var mm = nextDate.getMonth()+1;
							var y = nextDate.getFullYear();
							var someFormattedDate = y + '-' + mm + '-' + dd;
							$('#<?php print($variable_fecha_entrega_viaticos); ?>').val(someFormattedDate);
							var day = date.substring(0,2);
							day = parseInt(day);
							var month = date.substring(3,5);
							month = parseInt(month);
							$('.<?php print($variable_ejecutar_class);?>').val(month);
							if(month == 12)
							{
							   month=0;
							}
							$('.<?php print($variable_costear_class);?>').val(month+1);
							$('.<?php print($variable_facturar_class);?>').val(month+1);
							
							//COPIAR TODAS LAS SEDES
							var id = $('#<?php print($variable_fecha_realizacion1);?>').attr('id');
							id = id.replace("fecha_realizacion_1_","");
							var sede = $('#sede'+id).val();
							var fecha = $('#fecha_realizacion_1_'+id).val();
							var producto = $('#producto'+id).val();
							if(producto == 40 || producto == 41 || producto == 42)
							{
								var cantidad = $('#auxiliar_tipoFDP_cantidad'+id).val();
								var tema = $('#tema'+id).val();
								var idEvento = parseInt($('#evento'+id).val());
								var cantidad_aux = 0;
								var cantidad_aux2 = 0;
								var aux1 = 0;
								var aux2 = 0;
								if(producto == 40)
								{
									cantidad_aux = cantidad / 4;
									cantidad_aux2 = idEvento % 4;
									if(cantidad_aux2 == 1)
									{
										aux1 = parseInt(id);
										aux2 = parseInt(id)+3;
									}
									else if(cantidad_aux2 == 2)
									{
										aux1 = parseInt(id)-1;
										aux2 = parseInt(id)+2;
									}
									else if(cantidad_aux2 == 3)
									{
										aux1 = parseInt(id)-2;
										aux2 = parseInt(id)+1;
									}
									else
									{
										aux1 = parseInt(id)-3;
										aux2 = parseInt(id);
									}
								}
								else if(producto == 41)
								{
									cantidad_aux = cantidad / 3;
									cantidad_aux2 = idEvento % 3;
									if(cantidad_aux2 == 1)
									{
										aux1 = parseInt(id);
										aux2 = parseInt(id)+2;
									}
									else if(cantidad_aux2 == 2)
									{
										aux1 = parseInt(id)-1;
										aux2 = parseInt(id)+1;
									}
									else
									{
										aux1 = parseInt(id)-2;
										aux2 = parseInt(id);
									}
								}
								else if(producto == 42)
								{
									cantidad_aux = cantidad / 2;
									cantidad_aux2 = idEvento % 2;
									if(cantidad_aux2 == 1)
									{
										aux1 = parseInt(id);
										aux2 = parseInt(id)+1;
									}
									else
									{
										aux1 = parseInt(id)-1;
										aux2 = parseInt(id);
									}
								}
								for(i = aux1; i <= aux2; i++)
								{
									/*if(tema == $('#tema'+i).val())
									{*/
										$('#sede'+i).val(sede);
										$('#fecha_realizacion_1_'+i).show();
										$('#fecha_realizacion_1_'+i).val(fecha);
										$('#fecha_realizacion_2_'+i).hide();
										$('#fecha_realizacion_2_'+i).val('');
										$('#fecha_realizacion_3_'+i).hide();
										$('#fecha_realizacion_3_'+i).val('');
										$('#radio-fecharealizacion_1_'+i).attr("checked",true);
										
										$('.ejecutar'+i).val(month);
										$('.costear'+i).val(month+1);
										$('.facturar'+i).val(month+1);
									//}
								}
							}
						}
					);
					
					$('#<?php print($variable_fecha_realizacion2);?>').bsdatepicker({format: 'yyyy-mm-dd'})
						.on('changeDate', function(ev){
							var date = $('#<?php print($variable_fecha_realizacion2);?>').val();
							var nextDate = new Date(date);
							nextDate.setDate(nextDate.getDate() - 7);
							var dd = nextDate.getDate()+1;
							var mm = nextDate.getMonth()+1;
							var y = nextDate.getFullYear();
							var someFormattedDate = y + '-' + mm + '-' + dd;
							$('#<?php print($variable_fecha_entrega_viaticos); ?>').val(someFormattedDate);
							var month = date.substring(5,7);
							month = parseInt(month);
							$('.<?php print($variable_ejecutar_class);?>').val(month);
							if(month == 12)
							{
							   month=0;
							}
							$('.<?php print($variable_costear_class);?>').val(month+1);
							$('.<?php print($variable_facturar_class);?>').val(month+1);
							
							//COPIAR TODAS LAS SEDES
							var id = $('#<?php print($variable_fecha_realizacion2);?>').attr('id');
							id = id.replace("fecha_realizacion_2_","");
							var sede = $('#sede'+id).val();
							var fecha = $('#fecha_realizacion_2_'+id).val();
							var producto = $('#producto'+id).val();
							if(producto == 40 || producto == 41 || producto == 42)
							{
								var cantidad = $('#auxiliar_tipoFDP_cantidad'+id).val();
								var tema = $('#tema'+id).val();
								var idEvento = parseInt($('#evento'+id).val());
								var cantidad_aux = 0;
								var cantidad_aux2 = 0;
								var aux1 = 0;
								var aux2 = 0;
								if(producto == 40)
								{
									cantidad_aux = cantidad / 4;
									cantidad_aux2 = idEvento % 4;
									if(cantidad_aux2 == 1)
									{
										aux1 = parseInt(id);
										aux2 = parseInt(id)+3;
									}
									else if(cantidad_aux2 == 2)
									{
										aux1 = parseInt(id)-1;
										aux2 = parseInt(id)+2;
									}
									else if(cantidad_aux2 == 3)
									{
										aux1 = parseInt(id)-2;
										aux2 = parseInt(id)+1;
									}
									else
									{
										aux1 = parseInt(id)-3;
										aux2 = parseInt(id);
									}
								}
								else if(producto == 41)
								{
									cantidad_aux = cantidad / 3;
									cantidad_aux2 = idEvento % 3;
									if(cantidad_aux2 == 1)
									{
										aux1 = parseInt(id);
										aux2 = parseInt(id)+2;
									}
									else if(cantidad_aux2 == 2)
									{
										aux1 = parseInt(id)-1;
										aux2 = parseInt(id)+1;
									}
									else
									{
										aux1 = parseInt(id)-2;
										aux2 = parseInt(id);
									}
								}
								else if(producto == 42)
								{
									cantidad_aux = cantidad / 2;
									cantidad_aux2 = idEvento % 2;
									if(cantidad_aux2 == 1)
									{
										aux1 = parseInt(id);
										aux2 = parseInt(id)+1;
									}
									else
									{
										aux1 = parseInt(id)-1;
										aux2 = parseInt(id);
									}
								}
								for(i = aux1; i <= aux2; i++)
								{
									/*if(tema == $('#tema'+i).val())
									{*/
										$('#sede'+i).val(sede);
										$('#fecha_realizacion_1_'+i).hide();
										$('#fecha_realizacion_1_'+i).val('');
										$('#fecha_realizacion_2_'+i).show();
										$('#fecha_realizacion_2_'+i).val(fecha);
										$('#fecha_realizacion_3_'+i).hide();
										$('#fecha_realizacion_3_'+i).val('');
										$('#radio-fecharealizacion_2_'+i).attr("checked",true);
										
										$('.ejecutar'+i).val(month);
										$('.costear'+i).val(month+1);
										$('.facturar'+i).val(month+1);
									//}
								}
							}
						});
					
					$('#<?php print($variable_fecha_realizacion_horario2);?>').timepicker({
						minuteStep: 5,
						showSeconds: true,
						showMeridian: false
					});
					
					$('#<?php print($variable_fecha_cobranza);?>').bsdatepicker({
							//startDate: moment(),
							format: 'yyyy-mm-dd'
						})
						.on('changeDate', function(ev){
							var date = $('#<?php print($variable_fecha_cobranza);?>').val();
							var month = date.substring(5,7);
							month = parseInt(month);
							$('.<?php print($variable_cobrar_class);?>').val(month);
						});
						
					//SCRIPT PARA COPIAR AGENTES
					$( "#<?php print($variable_agente); ?>" ).change(function() 
					{
						var id = $('#<?php print($variable_agente);?>').attr('id');
						id = id.replace("agente0_","");
						var agente = $('#<?php print($variable_agente);?>').val();
						var producto = $('#producto'+id).val();
						if(producto == 40 || producto == 41 || producto == 42)
						{
							var cantidad = $('#auxiliar_tipoFDP_cantidad'+id).val();
							var idEvento = parseInt($('#evento'+id).val());
							var cantidad_aux = 0;
							var cantidad_aux2 = 0;
							var aux1 = 0;
							var aux2 = 0;
							if(producto == 40)
							{
								cantidad_aux = cantidad / 4;
								cantidad_aux2 = idEvento % 4;
								if(cantidad_aux2 == 1)
								{
									aux1 = parseInt(id);
									aux2 = parseInt(id)+3;
								}
								else if(cantidad_aux2 == 2)
								{
									aux1 = parseInt(id)-1;
									aux2 = parseInt(id)+2;
								}
								else if(cantidad_aux2 == 3)
								{
									aux1 = parseInt(id)-2;
									aux2 = parseInt(id)+1;
								}
								else
								{
									aux1 = parseInt(id)-3;
									aux2 = parseInt(id);
								}
							}
							else if(producto == 41)
							{
								cantidad_aux = cantidad / 3;
								cantidad_aux2 = idEvento % 3;
								if(cantidad_aux2 == 1)
								{
									aux1 = parseInt(id);
									aux2 = parseInt(id)+2;
								}
								else if(cantidad_aux2 == 2)
								{
									aux1 = parseInt(id)-1;
									aux2 = parseInt(id)+1;
								}
								else
								{
									aux1 = parseInt(id)-2;
									aux2 = parseInt(id);
								}
							}
							else if(producto == 42)
							{
								cantidad_aux = cantidad / 2;
								cantidad_aux2 = idEvento % 2;
								if(cantidad_aux2 == 1)
								{
									aux1 = parseInt(id);
									aux2 = parseInt(id)+1;
								}
								else
								{
									aux1 = parseInt(id)-1;
									aux2 = parseInt(id);
								}
							}
							for(i = aux1; i <= aux2; i++)
							{
								$('#agente0_'+i).val(agente);
							}
						}
					});
				});
			</script>
			<?php 
				if($row['id_subproducto'] == 57)
				{
					$readOnlyCostos = "";
				}
				else
				{
					$readOnlyCostos = "readonly";
				}
			?>
			<tr class="">
			       <td id="txt_evento">
					<input type="text" class="form-control" name="<?php print($variable_evento);?>" id="<?php print($variable_evento);?>" value="<?php print($i);?>" readonly>
				</td>
			       <td id="txt_id_evento">
					<input type="hidden" class="form-control" name="<?php print($variable_detalleoi);?>" id="<?php print($variable_detalleoi);?>" value="<?php print($row['id_detalleoi']);?>">
					<input type="hidden" class="form-control" name="<?php print($variable_total_agentes);?>" id="<?php print($variable_total_agentes);?>" value="<?php print($totalAgentes);?>">
					<input type="text" class="form-control" name="<?php print($variable_id_evento);?>" id="<?php print($variable_id_evento);?>" value="<?php print($evento); ?>" readonly>
				</td>
				<td id="txt_tema">
					<input readonly onkeyup="getIDEvento(this);" type="text" class="form-control" name="<?php print($variable_tema);?>" id="<?php print($variable_tema);?>" value="<?php print($row['tema']);?>" >
				</td>
				<td id="txt_personas">
					<input type="text" class="form-control" name="<?php print($variable_personas);?>" id="<?php print($variable_personas);?>" value="<?php print($row['personas']);?>">
				</td>
				<td id="txt_nivel">
					<select name="<?php print($variable_nivel);?>" id="<?php print($variable_nivel);?>" class="form-control">
						<option value="<?php print($row['id_nivel']);?>" selected><?php print($row['nivel']);?></option>
						<option value="1">Operativo</option>
						<option value="2">Mando medio</option>
						<option value="3">Directivo</option>
						<option value="4">Otro</option>
					</select>
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
				<td id="txt_subproducto">
					<select id="<?php print($variable_subproducto); ?>" name="<?php print($variable_subproducto); ?>" class="form-control" readonly>
						<option value="<?php print($row['id_subproducto']);?>" selected><?php print($row['nombre_subproducto']);?></option>
					</select>
					<input type="hidden" class="form-control" name="<?php print($variable_auxiliar_tipoFDP);?>" id="<?php print($variable_auxiliar_tipoFDP);?>" value="<?php print($variable_auxiliar_tipoFDP_valor);?>" readonly>
					<input type="hidden" class="form-control" name="<?php print($variable_auxiliar_tipoFDP_cantidad);?>" id="<?php print($variable_auxiliar_tipoFDP_cantidad);?>" value="<?php print($cantidad);?>" readonly>
				</td>
				<?php 
					if($row['id_subproducto'] == 57)
					{
				?>
				<td id="txt_tipoagente">
					<select id="<?php print($variable_tipoagente);?>" name="<?php print($variable_tipoagente);?>" class="form-control">
						<?php $this->selectTipoAgenteUpdateOtro($row['id_subproducto']);?>
					</select>
				</td>
				<td id="txt_agente">
					<select id="<?php print($variable_agente);?>" name="<?php print($variable_agente);?>" class="form-control">
						<option value="" selected>Selecciona un agente</option>
					</select>
				</td>
				<?php
					}
					else
					{
					    if($row['id_subproducto'] == 129)
					    {
					         $sensibilizacion = 'inline';
					    }
					    else
					    {
					        $sensibilizacion = 'none';
					    }
				?>
				<td id="txt_tipoagente">
					<select readonly id="<?php print($variable_tipoagente);?>" name="<?php print($variable_tipoagente);?>" class="form-control">
						<?php $this->selectTipoAgente($row3['id_tipoagente']);?>
					</select>
					<div class='col-sm-12 sensibilizacion' style='display:<?php print($sensibilizacion); ?>'><label class='radio-inline'><input onclick='getSensibilizacion(this); getPercentage(this);' type='radio' id='sensibilizacion<?php print($auxiliar); ?>' name='sensibilizacion<?php print($auxiliar); ?>' value='1' checked>Apoyo</label><input onclick='getSensibilizacion(this); getPercentage(this);' type='radio' id='sensibilizacion<?php print($auxiliar); ?>' name='sensibilizacion<?php print($auxiliar); ?>' value='0'>Coordinador</label><label class='radio-inline'><input onclick='getSensibilizacion(this); getPercentage(this);' type='radio' id='sensibilizacion<?php print($auxiliar); ?>' name='sensibilizacion<?php print($auxiliar); ?>' value='2'>Ambos</label></div>
				</td>
				<td id="txt_agente">
					<select id="<?php print($variable_agente);?>" name="<?php print($variable_agente);?>" class="form-control">
						<option value="" selected>Selecciona un agente</option>
					</select>
				</td>
				<?php
					}
				?>
				<td id="txt_equipo">
					<select name="<?php print($variable_equipo);?>" id="<?php print($variable_equipo);?>" class="form-control">
						<option value="1" selected>Estándar</option>
						<option value="2">Adicional</option>
					</select>
				</td>
				<td id="txt_material">
					<select name="<?php print($variable_material);?>" id="<?php print($variable_material);?>" class="form-control">
						<option value="1" selected>Estándar</option>
						<option value="2">Adicional</option>
					</select>
				</td>
				<td id="txt_precio">
					<input type="text" class="form-control" name="<?php print($variable_precio);?>" id="<?php print($variable_precio);?>" value="<?php print(number_format($row['precio_oi'],2));?>" readonly>
				</td>
				<td id="txt_descuento">	
					<input id="<?php print($variable_descuento);?>" name="<?php print($variable_descuento);?>" type="text" class="form-control" value="<?php print(round(1-$row['descuento'],2)*100) ;?>%" readonly>
				</td>
				<?php
				    if($row['id_subproducto'] == 57)
				    {
				 ?>
				 <td id="txt_honorarios">
					<input type="hidden" class="form-control" name="<?php print($variable_honorarios);?>" id="<?php print($variable_honorarios);?>" value="<?php print(number_format($row['costo_oi'],2));?>" readonly>
					<input <?php print($readOnlyCostos); ?> type="text" class="form-control" name="<?php print($variable_honorarios_aux);?>" id="<?php print($variable_honorarios_aux);?>" value="<?php print(number_format($row['costo_oi'],2));?>" readonly>
				</td>
				<td id="txt_porcentajehonorarios">
					<input type="text" class="form-control" name="<?php print($variable_porcentajehonorarios_aux);?>" id="<?php print($variable_porcentajehonorarios_aux);?>" value="<?php print(round($row['porcentajeHonorarios'],2)*100);?>%" readonly>
				</td>
				 <?php
				    }
				    else
				    {
				?>
				<td id="txt_honorarios">
					<input type="hidden" class="form-control" name="<?php print($variable_honorarios);?>" id="<?php print($variable_honorarios);?>" value="<?php print(number_format($row['costo_honorarios'],2));?>" readonly>
					<input <?php print($readOnlyCostos); ?> type="text" class="form-control" name="<?php print($variable_honorarios_aux);?>" id="<?php print($variable_honorarios_aux);?>" value="<?php print(number_format($row3['costo_tipoagente_subproducto'],2));?>" readonly>
				</td>
				<td id="txt_porcentajehonorarios">
					<input type="text" class="form-control" name="<?php print($variable_porcentajehonorarios_aux);?>" id="<?php print($variable_porcentajehonorarios_aux);?>" value="<?php print(round($row['porcentajeHonorarios'],2)*100);?>%" readonly>
				</td>
				<?php
				}
				?>
				<td id="txt_costomateriales">
					<input <?php print($readOnlyCostos); ?> type="text" class="form-control" name="<?php print($variable_costomateriales);?>" id="<?php print($variable_costomateriales);?>" value="<?php print(number_format($row['costo_materiales'],2));?>" readonly>
					<div class="col-sm-6">
						<label class="radio-inline">
							<input onclick="getPercentage(this);" type="radio" id="<?php print($variable_name_radio_button_costomaterial1);?>" name="<?php print($variable_name_radio_button_costomaterial);?>" value="1" checked onclick="">
							Aplicar costo
						</label>
					</div>
					<div class="col-sm-6">
						<label class="radio-inline">
							<input onclick="getPercentage(this);" type="radio" id="<?php print($variable_name_radio_button_costomaterial2);?>" name="<?php print($variable_name_radio_button_costomaterial);?>" value="0" onclick="">
							No aplicar costo
						</label>
					</div>
				</td>
				<td id="txt_porcentajemateriales">
					<input type="text" class="form-control" name="" id="" value="<?php print(round($row['porcentajeMateriales'],2)*100);?>%" readonly>
				</td>
				<td id="txt_costoadicionales">
					<input type="text" class="form-control" name="<?php print($variable_costosadicionales);?>" id="<?php print($variable_costosadicionales);?>" value="0.00" onkeyup="getCommas(this); getPercentage(this);">
				</td>
				<td id="txt_porcentajecostoadicionales">
					<input type="text" class="form-control" name="<?php print($variable_porcentajecostosadicionales);?>" id="<?php print($variable_porcentajecostosadicionales);?>" value="0%" readonly>
				</td>
				<td id="update_txt_costo">
					<input type="text" class="form-control" name="<?php print($variable_costo);?>" id="<?php print($variable_costo);?>" value="<?php print(number_format($row['costo_oi'],2));?>" readonly>
				</td>
				<td id="txt_porcentajecosto">
					<input type="text" class="form-control" name="<?php print($variable_porcentajecosto);?>" id="<?php print($variable_porcentajecosto);?>" value="<?php print(round($row['porcentajeCosto'],4)*100);?>%" readonly>
				</td>
				<td id="txt_margen">
					<input type="text" class="form-control" name="<?php print($variable_margen);?>" id="<?php print($variable_margen);?>" value="<?php print(number_format($row['precio_oi']-$row['costo_oi'],2));?>" readonly>
				</td>
				<td id="txt_porcentajemargen">
					<input type="text" class="form-control" name="<?php print($variable_porcentajemargen);?>" id="<?php print($variable_porcentajemargen);?>" value="<?php print(round(($row['porcentajeMargen'])*100,2));?>%" readonly>
				</td>
				<td id="txt_sede">
					<select name="<?php print($variable_sede);?>" id="<?php print($variable_sede);?>" class="form-control" onchange="getCopySede(this);">
						<?php $this->getEstados(); ?>
					</select>
				</td>
				<td id="txt_sede_particular">
					<input id="<?php print($variable_sede_particular);?>" name="<?php print($variable_sede_particular);?>" value="" type="text" class="form-control">
				</td>
				<td id="txt_fecharealizacion">
					<input id="<?php print($variable_fecha_realizacion1);?>" name="<?php print($variable_fecha_realizacion1);?>" value="" type="text" class="form-control <?php print($variable_class_fecha_realizacion1);?>" placeholder="dd-mm-yyyy">
					<input id="<?php print($variable_fecha_realizacion2);?>" name="<?php print($variable_fecha_realizacion2);?>" value="" type="text" class="form-control <?php print($variable_class_fecha_realizacion2);?>" placeholder="dd-mm-yyyy" style="display:none;">
					<input id="<?php print($variable_fecha_realizacion_horario2);?>" name="<?php print($variable_fecha_realizacion_horario2);?>" value="" type="text" class="form-control <?php print($variable_class_fecha_realizacion2);?>" placeholder="hh:mm" style="display:none;">
					<input id="<?php print($variable_fecha_realizacion3);?>" name="<?php print($variable_fecha_realizacion3);?>" value="" type="text" class="form-control <?php print($variable_class_fecha_realizacion3);?>" placeholder="dd-mm-yyyy" style="display:none;">
					<div class="col-sm-6">
						<label class="radio-inline">
							<input type="radio" id="<?php print($variable_radio_button1);?>" name="<?php print($variable_name_radio_button);?>" value="1" checked="checked" onclick="">
							Juntas
						</label>
						<label class="radio-inline">
							<input type="radio" id="<?php print($variable_radio_button2);?>" name="<?php print($variable_name_radio_button);?>" value="2" onclick="">
							Ordenadas
						</label>
					</div>
					<div class="col-sm-6">
						<label class="radio-inline">
							<input type="radio" id="<?php print($variable_radio_button3);?>" name="<?php print($variable_name_radio_button);?>" value="3">
							Separadas
						</label>
					</div>
				</td>
				<td id="txt_mes_ejecucion">
					<select name="<?php print($variable_mes_ejecucion);?>" id="<?php print($variable_mes_ejecucion);?>" class="form-control <?php print($variable_ejecutar_class);?>">
						<option value="1">Enero</option>
						<option value="2">Febrero</option>
						<option value="3">Marzo</option>
						<option value="4">Abril</option>
						<option value="5">Mayo</option>
						<option value="6">Junio</option>
						<option value="7">Julio</option>
						<option value="8">Agosto</option>
						<option value="9">Septiembre</option>
						<option value="10">Octubre</option>
						<option value="11">Noviembre</option>
						<option value="12">Diciembre</option>
					</select>
				</td>
				<td id="txt_estatus_ejecucion">
					<select onchange="" id="<?php print($variable_estatus_ejecucion);?>" name="<?php print($variable_estatus_ejecucion);?>" class="form-control">
						<option value="1">Ejecutado</option>
						<option value="2" selected>Por ejecutar</option>
					</select>
				</td>													
				<td id="txt_anomalias">
					<textarea rows="3" class="form-control textarea-autosize" id="<?php print($variable_anomalia);?>" name="<?php print($variable_anomalia);?>"></textarea>
				</td>
				<td id="txt_mes_costo">
					<select name="<?php print($variable_mes_costo);?>" id="<?php print($variable_mes_costo);?>" class="form-control <?php print($variable_costear_class);?>">
						<option value="1">Enero</option>
						<option value="2">Febrero</option>
						<option value="3">Marzo</option>
						<option value="4">Abril</option>
						<option value="5">Mayo</option>
						<option value="6">Junio</option>
						<option value="7">Julio</option>
						<option value="8">Agosto</option>
						<option value="9">Septiembre</option>
						<option value="10">Octubre</option>
						<option value="11">Noviembre</option>
						<option value="12">Diciembre</option>
					</select>
				</td>
				<td id="txt_estatus_costo">
					<select id="<?php print($variable_estatus_costo);?>" name="<?php print($variable_estatus_costo);?>" class="form-control">
						<option value="1">Pagado</option>
						<option value="2" selected>Por pagar</option>
					</select>
				</td>
				<td id="txt_mes_factura">
					<select name="<?php print($variable_mes_factura);?>" id="<?php print($variable_mes_factura);?>" class="form-control <?php print($variable_facturar_class);?>">
						<option value="1">Enero</option>
						<option value="2">Febrero</option>
						<option value="3">Marzo</option>
						<option value="4">Abril</option>
						<option value="5">Mayo</option>
						<option value="6">Junio</option>
						<option value="7">Julio</option>
						<option value="8">Agosto</option>
						<option value="9">Septiembre</option>
						<option value="10">Octubre</option>
						<option value="11">Noviembre</option>
						<option value="12">Diciembre</option>
					</select>
				</td>
				<td id="txt_estatus_factura">
					<select id="<?php print($variable_estatus_factura);?>" name="<?php print($variable_estatus_factura);?>" class="form-control">
						<option value="1">Facturado</option>
						<option value="2" selected>Por facturar</option>
					</select>
				</td>
				<td id="txt_factura">
					<input type="text" class="form-control" name="<?php print($variable_factura);?>" value="" id="<?php print($variable_factura);?>" >
				</td>
				<td id="txt_estatus_cobranza">
					<select id="<?php print($variable_estatus_cobranza);?>" name="<?php print($variable_estatus_cobranza);?>" class="form-control">
						<option value="1">Cobrado</option>
						<option value="2" selected>Por cobrar</option>
						<option value="3">Ingresada a plataforma</option>
						<option value="4">Monitoreo</option>
					</select>
				</td>
				<td id="txt_fecha_cobranza">
					<input id="<?php print($variable_fecha_cobranza);?>" name="<?php print($variable_fecha_cobranza);?>" value="" type="text" class="form-control" placeholder="dd-mm-yyyy" readonly>
				</td>
				<td id="txt_mes_cobranza">
					<select name="<?php print($variable_mes_cobranza);?>" id="<?php print($variable_mes_cobranza);?>" class="form-control <?php print($variable_cobrar_class);?>" readonly>
						<option value="1">Enero</option>
						<option value="2">Febrero</option>
						<option value="3">Marzo</option>
						<option value="4">Abril</option>
						<option value="5">Mayo</option>
						<option value="6">Junio</option>
						<option value="7">Julio</option>
						<option value="8">Agosto</option>
						<option value="9">Septiembre</option>
						<option value="10">Octubre</option>
						<option value="11">Noviembre</option>
						<option value="12">Diciembre</option>
					</select>
				</td>
				<td id="txt_viaticos">
					<input type="checkbox" class="form-control" name="<?php print($variable_viaticos);?>" id="<?php print($variable_viaticos);?>" value="0" onclick="getReadonly(this);">
				</td>
			</tr>
			<?php
					if($comprobacion)
					{
						$elementos = count($arrayAux);
						for($j = 1; $j < $elementos; $j++)
						{
						if($row['precio_oi'] == 0)
						{
						    $porcentajeHon = 0;
						 }
						 else
						 {
						    $porcentajeHon = $arrayAux2[$j]/$row['precio_oi'];
						 }
			?>
			<tr class="" style="<?php print($styleHidden);?>">
				<td id=""><input type="hidden" class="form-control" name="" id="" value="<?php print($i);?>" readonly></td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id="txt_tipoagente_v2">
					<select id="<?php print('tipoagente'.$j.'_'.$auxiliar);?>" name="<?php print('tipoagente'.$j.'_'.$auxiliar);?>" class="form-control">
						<?php $this->selectTipoAgente($arrayAux[$j]);?>
					</select>
				</td>
				<td id="txt_agente_v2">
					<select id="<?php print('agente'.$j.'_'.$auxiliar);?>" name="<?php print('agente'.$j.'_'.$auxiliar);?>" class="form-control agente">
						<option value="" selected>Selecciona un agente</option>
					</select>
				</td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id="">
					<input <?php print($readOnlyCostos); ?> type="text" class="form-control" name="<?php print('honorarios'.$j.'_'.$auxiliar);?>" id="<?php print('honorarios'.$j.'_'.$auxiliar);?>" value="<?php print(number_format($arrayAux2[$j],2));?>" readonly>
				</td>
				<td id="">
					<input type="text" class="form-control" name="<?php print('porcentajehonorarios'.$j.'_'.$auxiliar);?>" id="<?php print('porcentajehonorarios'.$j.'_'.$auxiliar);?>" value="<?php print(round($porcentajeHon,4)*100);?>%" readonly>
				</td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>													
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
			</tr>
			<?php
						}
					}
					$auxiliar++;
				}
				$contador++;
			}
			$aux = $auxiliar;
			?>
			<input id="contador" name="contador" value="<?php print($aux); ?>" type="text" hidden />
			<?php
		}
		
		
		public function showOIDetalleDigitalCenter($ordenes)
		{
			$query = "SELECT 
			              DO.aux,
					DO.id_detalleoi,
					Fam.familia_producto,
					Fam.id_familiaproducto,
					Prod.id_producto,
					Prod.nombre_producto,
					DO.id_subproducto,
					Sub.nombre_subproducto,
					DO.tema,
					DO.personas,
					DO.id_nivel,
					N.nivel,
					DO.precio_oi,
					DO.descuento,
					DO.costo_oi,
					(CASE WHEN DO.precio_oi = 0 THEN 0 ELSE DO.costo_oi/DO.precio_oi END) AS porcentajeCosto,
					(CASE WHEN DO.precio_oi = 0 THEN 0 ELSE (DO.precio_oi-DO.costo_oi)/DO.precio_oi END) AS porcentajeMargen,
					Sub.costo_honorarios,
					(CASE WHEN DO.precio_oi = 0 THEN 0 ELSE Sub.costo_honorarios/DO.precio_oi END) AS porcentajeHonorarios,
					(CASE WHEN DO.precio_oi = 0 THEN 0 ELSE Sub.costo_materiales/DO.precio_oi END) AS porcentajeMateriales,
					Sub.costo_materiales,
					DO.cantidad_oi
				FROM  T_DetalleOI AS DO
				INNER JOIN T_Subproductos AS Sub
				ON DO.id_subproducto = Sub.id_subproducto
				INNER JOIN T_Productos AS Prod
				ON Sub.id_producto = Prod.id_producto
				INNER JOIN T_FamiliaProductos AS Fam
				ON Prod.id_familiaproducto = Fam.id_familiaproducto
				INNER JOIN T_Niveles AS N
				ON N.id_nivel = DO.id_nivel
				WHERE DO.id_oi = :ordenes AND DO.id_estatusoi = 1
				UNION
				SELECT
				DO.aux,
DO.id_detalleoi,
					'',
					'',
					Prod.id_parte,
					Prod.parte,
					DO.id_subproducto_subparte,
					SS.subparte,
					DO.tema,
					DO.personas,
					DO.id_dificultad,
					N.dificultad,
					DO.precio_oi,
					DO.descuento,
					DO.costo_oi,
					0 AS porcentajeCosto,
					0 AS porcentajeMargen,
					0,
					0 AS porcentajeHonorarios,
					0 AS porcentajeMateriales,
					0,
					DO.cantidad_oi
FROM  T_DetalleOI AS DO
				INNER JOIN T_Subproductos_Subpartes AS S
				ON DO.id_subproducto_subparte = S.id_subproducto_subparte
                          INNER JOIN T_Subpartes_DC AS SS
				ON SS.id_subparte = S.id_subparte
				INNER JOIN T_Partes_DC AS Prod
				ON SS.id_parte = Prod.id_parte
				INNER JOIN T_Dificultad AS N
				ON N.id_dificultad = DO.id_dificultad
				WHERE DO.id_oi = :ordenes AND DO.id_estatusoi = 1";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":ordenes",$ordenes);
			
			$stmt->execute();
			
			
			
			$query = "SELECT SUM(DO.cantidad_oi) AS CantidadTotal
				FROM  T_DetalleOI AS DO
				INNER JOIN T_Subproductos AS Sub
				ON DO.id_subproducto = Sub.id_subproducto
				INNER JOIN T_Productos AS Prod
				ON Sub.id_producto = Prod.id_producto
				INNER JOIN T_FamiliaProductos AS Fam
				ON Prod.id_familiaproducto = Fam.id_familiaproducto
				INNER JOIN T_Niveles AS N
				ON N.id_nivel = DO.id_nivel
				WHERE DO.id_oi = :ordenes AND DO.id_estatusoi = 1";
			$stmt2 = $this->conn->prepare($query);
			$stmt2->bindparam(":ordenes",$ordenes);
			$stmt2->execute();
			$row2=$stmt2->fetch(PDO::FETCH_ASSOC);
			
			$contador = 1;
			$auxiliar = 1;
			$cantidad = 0;
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				$subproducto = $row['id_subproducto'];
				$query = "SELECT 
							id_tipoagente,
							costo_tipoagente_subproducto
						FROM T_Subproducto_TipoAgente
						WHERE id_subproducto = :subproducto";
				$stmt3 = $this->conn->prepare($query);
				$stmt3->bindparam(":subproducto",$subproducto);
				$stmt3->execute();
				if($stmt3->rowCount() == 0)
				{
					$row3 = array();
					$row3['id_tipoagente'] = 0;
					$row3['costo_tipoagente_subproducto'] = 0;
					$styleHidden = "display:none;";
					$comprobacion = FALSE;
					$totalAgentes = 0;
				}
				elseif($stmt3->rowCount() == 1)
				{
					$row3=$stmt3->fetch(PDO::FETCH_ASSOC);
					$styleHidden = "display:none;";
					$comprobacion = FALSE;
					$totalAgentes = 1;
				}
				else
				{
					$arrayAux = array();
					$arrayAux2 = array();
					$x=0;
					while($row3=$stmt3->fetch(PDO::FETCH_ASSOC))
					{
						$arrayAux[$x] = $row3['id_tipoagente'];
						$arrayAux2[$x] = $row3['costo_tipoagente_subproducto'];
						$x++;
					}
					$row3['id_tipoagente'] = $arrayAux[0];
					$row3['costo_tipoagente_subproducto'] = $arrayAux2[0];
					$styleHidden = "";
					$comprobacion = TRUE;
					$totalAgentes = $x;
				}
				//SELECT SUBTEMAS
				$subtema = array();
				$temaAux = $row['tema'];
				if($row['tema'] == "Visión de negocio")
				{
					$subtema[1] = "La gestión y el rol del mando";
					$subtema[2] = "Responsabilidad";
					$subtema[3] = "Pensamiento estratégico";
					$subtema[4] = "Oferta de valor y ventaja competitiva";
				}
				elseif($row['tema'] == "Orientación a resultados")
				{
					$subtema[1] = "Definición de actividades";
					$subtema[2] = "Supervisión y control";
					$subtema[3] = "Definición de objetivos, indicadores y metas";
					$subtema[4] = "Distribución de actividades y cargas de trabajo";
				}
				elseif($row['tema'] == "Comunicación interpersonal")
				{
					$subtema[1] = "Escucha";
					$subtema[2] = "Generación de acuerdos";
					$subtema[3] = "Seguimiento a acuerdos y retroalimentación";
					$subtema[4] = "Elaboración de preguntas";
				}
				elseif($row['tema'] == "Liderazgo")
				{
					$subtema[1] = "Adaptación al colaborador";
					$subtema[2] = "Desarrollo del colaborador";
					$subtema[3] = "Visión compartida";
					$subtema[4] = "Facultamiento";
				}
				elseif($row['tema'] == "Sinergia Organizacional")
				{
					$subtema[1] = "Trabajo en equipo";
					$subtema[2] = "Formación de equipos de alto desempeño";
					$subtema[3] = "Interequipos";
					$subtema[4] = "Sociedad estratégica de líderes";
				}
				elseif($row['tema'] == "Análisis de problemas y toma de decisiones")
				{
					$subtema[1] = "Análisis de causas";
					$subtema[2] = "Aseguramiento de la resolución";
					$subtema[3] = "Establecimiento de brechas";
					$subtema[4] = "Aseguramiento de la resolución";
				}
				elseif($row['tema'] == "Administración de microproyectos")
				{
					$subtema[1] = "Planeación de un proyecto";
					$subtema[2] = "Ejecución y cierre";
					$subtema[3] = "Definición e inicio de un proyecto";
					$subtema[4] = "Control y cierre";
				}
				elseif($row['tema'] == "Inteligencia emocional")
				{
					$subtema[1] = "Consciencia de si";
					$subtema[2] = "Autoconocimiento";
					$subtema[3] = "Autorregulación";
					$subtema[4] = "Sinergía emocional";
				}
				elseif($row['tema'] == "Inteligencia social")
				{
					$subtema[1] = "Detener un conflicto en crecimiento";
					$subtema[2] = "Cerrar un conflicto";
					$subtema[3] = "Empatía y asertividad";
					$subtema[4] = "Aprovechamiento del conflicto";
				}
				else
				{
					$subtema[1] = "";
					$subtema[2] = "";
					$subtema[3] = "";
					$subtema[4] = "";
				}
				
				//PRODUCTO FDP
				if($row['id_producto'] == 40)
				{
					$cantidad = $row['cantidad_oi']*4;
					$row['precio_oi'] = $row['precio_oi']/4;
					$row['costo_honorarios'] = $row['costo_honorarios']/4;
					$row3['costo_tipoagente_subproducto'] = $row3['costo_tipoagente_subproducto']/4;
					$row['costo_materiales'] = $row['costo_materiales']/4;
					$row['costo_oi'] = $row['costo_oi']/4;
				}
				elseif($row['id_subproducto'] == 129)
				{
					$cantidad = $row['cantidad_oi']*4;
					$row['precio_oi'] = $row['precio_oi'];
					$row['costo_honorarios'] = $row['costo_honorarios'];
					$row3['costo_tipoagente_subproducto'] = $row3['costo_tipoagente_subproducto'];
					$row['costo_materiales'] = $row['costo_materiales'];
					$row['costo_oi'] = $row['costo_oi'];
				}
				elseif($row['id_producto'] == 41)
				{
				
					$cantidad = $row['cantidad_oi']*3;
					
					$row['precio_oi'] = $row['precio_oi']/3;
					$row['costo_honorarios'] = $row['costo_honorarios']/3;
					$row3['costo_tipoagente_subproducto'] = $row3['costo_tipoagente_subproducto']/3;
					$row['costo_materiales'] = $row['costo_materiales']/3;
					$row['costo_oi'] = $row['costo_oi']/3;
				}
				elseif($row['id_producto'] == 42)
				{
					$cantidad = $row['cantidad_oi']*2;
					$row['precio_oi'] = $row['precio_oi']/2;
					$row['costo_honorarios'] = $row['costo_honorarios']/2;
					$row3['costo_tipoagente_subproducto'] = $row3['costo_tipoagente_subproducto']/2;
					$row['costo_materiales'] = $row['costo_materiales']/2;
					$row['costo_oi'] = $row['costo_oi']/2;
				}
				elseif($row['aux'] == 'parte')
				{
					$cantidad = 1;
				}
				elseif($row['id_familiaproducto']==4)
				{
				       $cantidad = 1;
				}
				else
				{
					$cantidad = $row['cantidad_oi'];
				}
				for($i=1;$i<=$cantidad;$i++)
				{
					if($row['id_producto'] == 40)
					{
						$cantidad_aux = $cantidad / 4;
						$cantidad_aux2 = $i % 4;
						if($cantidad_aux2 == 3)
						{
							$row['tema'] = $temaAux.' - '.$subtema[3];
						}
						elseif($cantidad_aux2 == 0)
						{
							$row['tema'] = $temaAux.' - '.$subtema[4];
						}
						elseif($cantidad_aux2 == 1)
						{
							$row['tema'] = $temaAux.' - '.$subtema[1];
						}
						elseif($cantidad_aux2 == 2)
						{
							$row['tema'] = $temaAux.' - '.$subtema[2];
						}
					}
					elseif($row['id_subproducto'] == 129)
					{
						$cantidad_aux = $cantidad / 4;
						$cantidad_aux2 = $i % 4;
						if($cantidad_aux2 == 1)
						{
							$row['precio_oi'] = $row['precio_oi'];
						}
						else
						{
							$row['precio_oi'] = 0;
							$row['porcentajeCosto'] = 0;
							$row['costo_materiales'] = 0;
							$row['porcentajeMargen'] = 0;
						}
					}
					elseif($row['id_producto'] == 41)
					{
						$cantidad_aux = $cantidad / 3;
						$cantidad_aux2 = $i % 3;
						if($cantidad_aux2 == 0)
						{
							$row['tema'] = $temaAux.' - '.$subtema[3];
						}
						elseif($cantidad_aux2 == 1)
						{
							$row['tema'] = $temaAux.' - '.$subtema[1];
						}
						elseif($cantidad_aux2 == 2)
						{
							$row['tema'] = $temaAux.' - '.$subtema[2];
						}
						
					}
					elseif($row['id_producto'] == 42)
					{
						$cantidad_aux = $cantidad / 2;
						$cantidad_aux2 = $i % 2;
						if($cantidad_aux2 == 1)
						{
							$row['tema'] = $temaAux.' - '.$subtema[1];
						}
						elseif($cantidad_aux2 == 0)
						{
							$row['tema'] = $temaAux.' - '.$subtema[2];
						}
					}
						
					$variable_familia = 'familia'.$auxiliar;
					$variable_producto = 'producto'.$auxiliar;
					$variable_subproducto = 'subproducto'.$auxiliar;
					$variable_tema = 'tema'.$auxiliar;
					$variable_evento = 'evento'.$auxiliar;
					$variable_id_evento = 'id_evento'.$auxiliar;
					$variable_personas = 'personas'.$auxiliar;
					$variable_nivel = 'nivel'.$auxiliar;
					$evento = $i.$row['tema'].'-';
					$variable_tipoagente = 'tipoagente0_'.$auxiliar;
					$variable_agente = 'agente0_'.$auxiliar;
					$variable_equipo = 'equipo'.$auxiliar;
					$variable_material = 'material'.$auxiliar;
					$variable_precio = 'precio'.$auxiliar;
					$variable_descuento = 'descuento'.$auxiliar;
					$variable_costo = 'costo'.$auxiliar;
					$variable_porcentajecosto = 'porcentajecosto'.$auxiliar;
					$variable_honorarios = 'honorarios'.$auxiliar;
					$variable_honorarios_aux = 'honorarios0_'.$auxiliar;
					$variable_porcentajehonorarios_aux = 'porcentajehonorarios0_'.$auxiliar;
					$variable_costomateriales = 'costomateriales'.$auxiliar;
					$variable_costosadicionales = 'costosadicionales'.$auxiliar;
					$variable_porcentajecostosadicionales = 'porcentajecostosadicionales'.$auxiliar;
					$variable_margen = 'margen'.$auxiliar;
					$variable_porcentajemargen = 'porcentajemargen'.$auxiliar;
					$variable_sede = 'sede'.$auxiliar;
					$variable_sede_particular = 'sede_particular'.$auxiliar;
					$variable_fecha_cobranza = 'fecha_cobranza_'.$auxiliar;
					$variable_fecha_realizacion1 = 'fecha_realizacion_1_'.$auxiliar;
					$variable_fecha_realizacion2 = 'fecha_realizacion_2_'.$auxiliar;
					$variable_fecha_realizacion_horario2 = 'fecha_realizacion_2_horario'.$auxiliar;
					$variable_fecha_realizacion3 = 'fecha_realizacion_3_'.$auxiliar;
					$variable_class_fecha_realizacion1 = 'fecha_realizacion_1_'.$auxiliar;
					$variable_class_fecha_realizacion2 = 'fecha_realizacion_2_'.$auxiliar;
					$variable_class_fecha_realizacion3 = 'fecha_realizacion_3_'.$auxiliar;
					$variable_name_radio_button_costomaterial = 'radio-costomaterial'.$auxiliar;
					$variable_name_radio_button_costomaterial1 = 'radio-costomaterial_1_'.$auxiliar;
					$variable_name_radio_button_costomaterial2 = 'radio-costomaterial_2_'.$auxiliar;
					$variable_name_radio_button = 'radio-fecharealizacion'.$auxiliar;
					$variable_radio_button1 = 'radio-fecharealizacion_1_'.$auxiliar;
					$variable_radio_button2 = 'radio-fecharealizacion_2_'.$auxiliar;
					$variable_radio_button3 = 'radio-fecharealizacion_3_'.$auxiliar;
					$variable_mes_ejecucion = 'mes_ejecucion'.$auxiliar;
					$variable_estatus_ejecucion = 'estatus_ejecucion'.$auxiliar;
					$variable_anomalia = 'anomalia'.$auxiliar;
					$variable_estatus_costo = 'estatus_costo'.$auxiliar;
					$variable_mes_costo = 'mes_costo'.$auxiliar;
					$variable_estatus_factura = 'estatus_factura'.$auxiliar;
					$variable_mes_factura = 'mes_factura'.$auxiliar;
					$variable_factura = 'factura'.$auxiliar;
					$variable_estatus_cobranza = 'estatus_cobranza'.$auxiliar;
					$variable_mes_cobranza = 'mes_cobranza'.$auxiliar;
					$variable_ejecutar_class = 'ejecutar'.$auxiliar;
					$variable_costear_class = 'costear'.$auxiliar;
					$variable_facturar_class = 'facturar'.$auxiliar;
					$variable_cobrar_class = 'cobrar'.$auxiliar;
					$variable_viaticos = 'viaticos'.$auxiliar;
					$variable_transportacion = 'transportacion'.$auxiliar;
					$variable_alimentos = 'alimentos'.$auxiliar;
					$variable_hospedaje = 'hospedaje'.$auxiliar;
					$variable_nodeducible = 'nodeducible'.$auxiliar;
					$variable_suma_viaticos = 'suma_viaticos'.$auxiliar;
					$variable_iva = 'iva'.$auxiliar;
					$variable_total_sinavion = 'total_sinavion'.$auxiliar;
					$variable_desglose = 'desglose'.$auxiliar;
					$variable_avionsiniva = 'avionsiniva'.$auxiliar;
					$variable_ivaavion = 'ivaavion'.$auxiliar;
					$variable_total_avion = 'total_avion'.$auxiliar;
					$variable_anticipo_viaticos = 'anticipo_viaticos'.$auxiliar;
					$variable_rembloso = 'rembolso'.$auxiliar;
					$variable_total_cobrar = 'total_cobrar'.$auxiliar;
					$variable_observaciones_viaticos = 'observaciones_viaticos'.$auxiliar;
					$variable_gasto_admin = 'gasto_admin'.$auxiliar;
					$variable_fecha_entrega_viaticos = 'fecha_entrega_viaticos'.$auxiliar;
					$variable_total_agentes = 'total_agentes'.$auxiliar;
					$variable_auxiliar_tipoFDP = 'auxiliar_tipoFDP'.$auxiliar;
					$variable_auxiliar_tipoFDP_cantidad = 'auxiliar_tipoFDP_cantidad'.$auxiliar;
					$variable_estatus_ejecucion_viaticos = 'estatus_ejecucion_viaticos'.$auxiliar;
					$variable_estatus_factura_vitaticos = 'estatus_factura_viaticos'.$auxiliar;
					$variable_estatus_cobranza_viaticos = 'estatus_cobranza_viaticos'.$auxiliar;
					$variable_estatus_costo_viaticos = 'estatus_costo_viaticos'.$auxiliar;
					$variable_detalleoi = 'detalleoi'.$auxiliar;
					if($row['id_producto'] == 40 || $row['id_producto'] == 41 || $row['id_producto'] == 42)
					{
						$variable_auxiliar_tipoFDP_valor = 1;
					}
					else
					{
						$variable_auxiliar_tipoFDP_valor = 0;
					}
			?>
			<!--SCRIPT INICIALIZA DATEPICKERS-->
			<script type="text/javascript">
				$(function() {
					$('input[type=radio][name=<?php print($variable_name_radio_button);?>]').change(function() {
						$('.<?php print($variable_class_fecha_realizacion1);?>').val('');
						$('.<?php print($variable_class_fecha_realizacion2);?>').val('');
						$('.<?php print($variable_class_fecha_realizacion3);?>').val('');
						if (this.value == 1) 
						{
							//PERIODO
							$('.<?php print($variable_class_fecha_realizacion1);?>').show();
							$('.<?php print($variable_class_fecha_realizacion2);?>').hide();
							$('.<?php print($variable_class_fecha_realizacion3);?>').hide();
							/*$('.<?php print($variable_class_fecha_realizacion1);?>').prop('required','true');
									$('.<?php print($variable_class_fecha_realizacion2);?>').prop('required','false');
									$('.<?php print($variable_class_fecha_realizacion3);?>').prop('required','false');*/
						}
						else if (this.value == 2) 
						{
							//UNICA FECHA
							$('.<?php print($variable_class_fecha_realizacion1);?>').hide();
							$('.<?php print($variable_class_fecha_realizacion2);?>').show();
							$('.<?php print($variable_class_fecha_realizacion3);?>').hide();
							/*$('.<?php print($variable_class_fecha_realizacion1);?>').prop('required','false');
									$('.<?php print($variable_class_fecha_realizacion2);?>').prop('required','true');
									$('.<?php print($variable_class_fecha_realizacion3);?>').prop('required','false');*/
						}
						else
						{
							//VARIAS FECHAS
							$('.<?php print($variable_class_fecha_realizacion1);?>').hide();
							$('.<?php print($variable_class_fecha_realizacion2);?>').hide();
							$('.<?php print($variable_class_fecha_realizacion3);?>').show();
							/*$('.<?php print($variable_class_fecha_realizacion1);?>').prop('required','false');
									$('.<?php print($variable_class_fecha_realizacion2);?>').prop('required','false');
									$('.<?php print($variable_class_fecha_realizacion3);?>').prop('required','true');*/
						}
					});
					$('.<?php print($variable_class_fecha_realizacion1);?>').daterangepicker({
							startDate: moment(),
							endDate: moment(),
							minDate: '01/01/2010',
							maxDate: '12/31/2999',
							showDropdowns: true,
							showWeekNumbers: true,
							timePicker: true,
							timePickerIncrement: 1,
							timePicker24Hour: true,
							opens: 'left',
							buttonClasses: ['btn btn-default'],
							applyClass: 'small bg-green',
							cancelClass: 'small ui-state-default',
							format: 'DD-MM-YYYY HH:mm:ss',
							separator: ' a ',
							locale: {
								applyLabel: 'Aplicar',
								fromLabel: 'Desde',
								toLabel: 'Hasta',
								daysOfWeek: ['Do', 'Lu', 'Mar', 'Mie', 'Jue', 'Vi', 'Sa'],
								monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
								firstDay: 1
							}
						},
						function(start, end) 
						{
							console.log("Callback has been called!");
							$('.<?php print($variable_class_fecha_realizacion1);?>').val(start.format('YYYY-MM-DD HH:mm:ss') + ' - ' + end.format('YYYY-MM-DD HH:mm:ss'));
							var date = end.format('DD-MM-YYYY');
							var nextDate = new Date(start);
							nextDate.setDate(nextDate.getDate() - 7);
							var dd = nextDate.getDate();
							var mm = nextDate.getMonth()+1;
							var y = nextDate.getFullYear();
							var someFormattedDate = y + '-' + mm + '-' + dd;
							$('#<?php print($variable_fecha_entrega_viaticos); ?>').val(someFormattedDate);
							var day = date.substring(0,2);
							day = parseInt(day);
							var month = date.substring(3,5);
							month = parseInt(month);
							$('.<?php print($variable_ejecutar_class);?>').val(month);
							$('.<?php print($variable_costear_class);?>').val(month+1);
							$('.<?php print($variable_facturar_class);?>').val(month+1);
							
							//COPIAR TODAS LAS SEDES
							var id = $('#<?php print($variable_fecha_realizacion1);?>').attr('id');
							id = id.replace("fecha_realizacion_1_","");
							var sede = $('#sede'+id).val();
							var fecha = $('#fecha_realizacion_1_'+id).val();
							var producto = $('#producto'+id).val();
							if(producto == 40 || producto == 41 || producto == 42)
							{
								var cantidad = $('#auxiliar_tipoFDP_cantidad'+id).val();
								var tema = $('#tema'+id).val();
								var idEvento = parseInt($('#evento'+id).val());
								var cantidad_aux = 0;
								var cantidad_aux2 = 0;
								var aux1 = 0;
								var aux2 = 0;
								if(producto == 40)
								{
									cantidad_aux = cantidad / 4;
									cantidad_aux2 = idEvento % 4;
									if(cantidad_aux2 == 1)
									{
										aux1 = parseInt(id);
										aux2 = parseInt(id)+3;
									}
									else if(cantidad_aux2 == 2)
									{
										aux1 = parseInt(id)-1;
										aux2 = parseInt(id)+2;
									}
									else if(cantidad_aux2 == 3)
									{
										aux1 = parseInt(id)-2;
										aux2 = parseInt(id)+1;
									}
									else
									{
										aux1 = parseInt(id)-3;
										aux2 = parseInt(id);
									}
								}
								else if(producto == 41)
								{
									cantidad_aux = cantidad / 3;
									cantidad_aux2 = idEvento % 3;
									if(cantidad_aux2 == 1)
									{
										aux1 = parseInt(id);
										aux2 = parseInt(id)+2;
									}
									else if(cantidad_aux2 == 2)
									{
										aux1 = parseInt(id)-1;
										aux2 = parseInt(id)+1;
									}
									else
									{
										aux1 = parseInt(id)-2;
										aux2 = parseInt(id);
									}
								}
								else if(producto == 42)
								{
									cantidad_aux = cantidad / 2;
									cantidad_aux2 = idEvento % 2;
									if(cantidad_aux2 == 1)
									{
										aux1 = parseInt(id);
										aux2 = parseInt(id)+1;
									}
									else
									{
										aux1 = parseInt(id)-1;
										aux2 = parseInt(id);
									}
								}
								for(i = aux1; i <= aux2; i++)
								{
									/*if(tema == $('#tema'+i).val())
									{*/
										$('#sede'+i).val(sede);
										$('#fecha_realizacion_1_'+i).show();
										$('#fecha_realizacion_1_'+i).val(fecha);
										$('#fecha_realizacion_2_'+i).hide();
										$('#fecha_realizacion_2_'+i).val('');
										$('#fecha_realizacion_3_'+i).hide();
										$('#fecha_realizacion_3_'+i).val('');
										$('#radio-fecharealizacion_1_'+i).attr("checked",true);
										
										$('.ejecutar'+i).val(month);
										$('.costear'+i).val(month+1);
										$('.facturar'+i).val(month+1);
									//}
								}
							}
						}
					);
					
					$('#<?php print($variable_fecha_realizacion2);?>').bsdatepicker({format: 'yyyy-mm-dd'})
						.on('changeDate', function(ev){
							var date = $('#<?php print($variable_fecha_realizacion2);?>').val();
							var nextDate = new Date(date);
							nextDate.setDate(nextDate.getDate() - 7);
							var dd = nextDate.getDate()+1;
							var mm = nextDate.getMonth()+1;
							var y = nextDate.getFullYear();
							var someFormattedDate = y + '-' + mm + '-' + dd;
							$('#<?php print($variable_fecha_entrega_viaticos); ?>').val(someFormattedDate);
							var month = date.substring(5,7);
							month = parseInt(month);
							$('.<?php print($variable_ejecutar_class);?>').val(month);
							$('.<?php print($variable_costear_class);?>').val(month+1);
							$('.<?php print($variable_facturar_class);?>').val(month+1);
							
							//COPIAR TODAS LAS SEDES
							var id = $('#<?php print($variable_fecha_realizacion2);?>').attr('id');
							id = id.replace("fecha_realizacion_2_","");
							var sede = $('#sede'+id).val();
							var fecha = $('#fecha_realizacion_2_'+id).val();
							var producto = $('#producto'+id).val();
							if(producto == 40 || producto == 41 || producto == 42)
							{
								var cantidad = $('#auxiliar_tipoFDP_cantidad'+id).val();
								var tema = $('#tema'+id).val();
								var idEvento = parseInt($('#evento'+id).val());
								var cantidad_aux = 0;
								var cantidad_aux2 = 0;
								var aux1 = 0;
								var aux2 = 0;
								if(producto == 40)
								{
									cantidad_aux = cantidad / 4;
									cantidad_aux2 = idEvento % 4;
									if(cantidad_aux2 == 1)
									{
										aux1 = parseInt(id);
										aux2 = parseInt(id)+3;
									}
									else if(cantidad_aux2 == 2)
									{
										aux1 = parseInt(id)-1;
										aux2 = parseInt(id)+2;
									}
									else if(cantidad_aux2 == 3)
									{
										aux1 = parseInt(id)-2;
										aux2 = parseInt(id)+1;
									}
									else
									{
										aux1 = parseInt(id)-3;
										aux2 = parseInt(id);
									}
								}
								else if(producto == 41)
								{
									cantidad_aux = cantidad / 3;
									cantidad_aux2 = idEvento % 3;
									if(cantidad_aux2 == 1)
									{
										aux1 = parseInt(id);
										aux2 = parseInt(id)+2;
									}
									else if(cantidad_aux2 == 2)
									{
										aux1 = parseInt(id)-1;
										aux2 = parseInt(id)+1;
									}
									else
									{
										aux1 = parseInt(id)-2;
										aux2 = parseInt(id);
									}
								}
								else if(producto == 42)
								{
									cantidad_aux = cantidad / 2;
									cantidad_aux2 = idEvento % 2;
									if(cantidad_aux2 == 1)
									{
										aux1 = parseInt(id);
										aux2 = parseInt(id)+1;
									}
									else
									{
										aux1 = parseInt(id)-1;
										aux2 = parseInt(id);
									}
								}
								for(i = aux1; i <= aux2; i++)
								{
									/*if(tema == $('#tema'+i).val())
									{*/
										$('#sede'+i).val(sede);
										$('#fecha_realizacion_1_'+i).hide();
										$('#fecha_realizacion_1_'+i).val('');
										$('#fecha_realizacion_2_'+i).show();
										$('#fecha_realizacion_2_'+i).val(fecha);
										$('#fecha_realizacion_3_'+i).hide();
										$('#fecha_realizacion_3_'+i).val('');
										$('#radio-fecharealizacion_2_'+i).attr("checked",true);
										
										$('.ejecutar'+i).val(month);
										$('.costear'+i).val(month+1);
										$('.facturar'+i).val(month+1);
									//}
								}
							}
						});
					
					$('#<?php print($variable_fecha_realizacion_horario2);?>').timepicker({
						minuteStep: 5,
						showSeconds: true,
						showMeridian: false
					});
					
					$('#<?php print($variable_fecha_cobranza);?>').bsdatepicker({
							//startDate: moment(),
							format: 'yyyy-mm-dd'
						})
						.on('changeDate', function(ev){
							var date = $('#<?php print($variable_fecha_cobranza);?>').val();
							var month = date.substring(5,7);
							month = parseInt(month);
							$('.<?php print($variable_cobrar_class);?>').val(month);
						});
						
					//SCRIPT PARA COPIAR AGENTES
					$( "#<?php print($variable_agente); ?>" ).change(function() 
					{
						var id = $('#<?php print($variable_agente);?>').attr('id');
						id = id.replace("agente0_","");
						var agente = $('#<?php print($variable_agente);?>').val();
						var producto = $('#producto'+id).val();
						if(producto == 40 || producto == 41 || producto == 42)
						{
							var cantidad = $('#auxiliar_tipoFDP_cantidad'+id).val();
							var idEvento = parseInt($('#evento'+id).val());
							var cantidad_aux = 0;
							var cantidad_aux2 = 0;
							var aux1 = 0;
							var aux2 = 0;
							if(producto == 40)
							{
								cantidad_aux = cantidad / 4;
								cantidad_aux2 = idEvento % 4;
								if(cantidad_aux2 == 1)
								{
									aux1 = parseInt(id);
									aux2 = parseInt(id)+3;
								}
								else if(cantidad_aux2 == 2)
								{
									aux1 = parseInt(id)-1;
									aux2 = parseInt(id)+2;
								}
								else if(cantidad_aux2 == 3)
								{
									aux1 = parseInt(id)-2;
									aux2 = parseInt(id)+1;
								}
								else
								{
									aux1 = parseInt(id)-3;
									aux2 = parseInt(id);
								}
							}
							else if(producto == 41)
							{
								cantidad_aux = cantidad / 3;
								cantidad_aux2 = idEvento % 3;
								if(cantidad_aux2 == 1)
								{
									aux1 = parseInt(id);
									aux2 = parseInt(id)+2;
								}
								else if(cantidad_aux2 == 2)
								{
									aux1 = parseInt(id)-1;
									aux2 = parseInt(id)+1;
								}
								else
								{
									aux1 = parseInt(id)-2;
									aux2 = parseInt(id);
								}
							}
							else if(producto == 42)
							{
								cantidad_aux = cantidad / 2;
								cantidad_aux2 = idEvento % 2;
								if(cantidad_aux2 == 1)
								{
									aux1 = parseInt(id);
									aux2 = parseInt(id)+1;
								}
								else
								{
									aux1 = parseInt(id)-1;
									aux2 = parseInt(id);
								}
							}
							for(i = aux1; i <= aux2; i++)
							{
								$('#agente0_'+i).val(agente);
							}
						}
					});
				});
			</script>
			<?php 
				if($row['id_subproducto'] == 57)
				{
					$readOnlyCostos = "";
				}
				else
				{
					$readOnlyCostos = "readonly";
				}
			?>
			<tr class="">
			       <td id="txt_evento">
					<input type="text" class="form-control" name="<?php print($variable_evento);?>" id="<?php print($variable_evento);?>" value="<?php print($i);?>" readonly>
				</td>
			       <td id="txt_id_evento">
					<input type="hidden" class="form-control" name="<?php print($variable_detalleoi);?>" id="<?php print($variable_detalleoi);?>" value="<?php print($row['id_detalleoi']);?>">
					<input type="hidden" class="form-control" name="<?php print($variable_total_agentes);?>" id="<?php print($variable_total_agentes);?>" value="<?php print($totalAgentes);?>">
					<input type="text" class="form-control" name="<?php print($variable_id_evento);?>" id="<?php print($variable_id_evento);?>" value="<?php print($evento); ?>" readonly>
				</td>
				<td id="txt_tema">
					<input readonly onkeyup="getIDEvento(this);" type="text" class="form-control" name="<?php print($variable_tema);?>" id="<?php print($variable_tema);?>" value="<?php print($row['tema']);?>" >
				</td>
				<td id="txt_personas">
					<input type="text" class="form-control" name="<?php print($variable_personas);?>" id="<?php print($variable_personas);?>" value="<?php print($row['personas']);?>">
				</td>
				<td id="txt_nivel">
					<select name="<?php print($variable_nivel);?>" id="<?php print($variable_nivel);?>" class="form-control">
						<option value="<?php print($row['id_nivel']);?>" selected><?php print($row['nivel']);?></option>
						<option value="1">Operativo</option>
						<option value="2">Mando medio</option>
						<option value="3">Directivo</option>
						<option value="4">Otro</option>
					</select>
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
				<td id="txt_subproducto">
					<select id="<?php print($variable_subproducto); ?>" name="<?php print($variable_subproducto); ?>" class="form-control" readonly>
						<option value="<?php print($row['id_subproducto']);?>" selected><?php print($row['nombre_subproducto']);?></option>
					</select>
					<input type="hidden" class="form-control" name="<?php print($variable_auxiliar_tipoFDP);?>" id="<?php print($variable_auxiliar_tipoFDP);?>" value="<?php print($variable_auxiliar_tipoFDP_valor);?>" readonly>
					<input type="hidden" class="form-control" name="<?php print($variable_auxiliar_tipoFDP_cantidad);?>" id="<?php print($variable_auxiliar_tipoFDP_cantidad);?>" value="<?php print($cantidad);?>" readonly>
				</td>
				<?php 
					if($row['id_subproducto'] == 57)
					{
				?>
				<td id="txt_tipoagente">
					<select id="<?php print($variable_tipoagente);?>" name="<?php print($variable_tipoagente);?>" class="form-control">
						<?php $this->selectTipoAgenteUpdateOtro($row['id_subproducto']);?>
					</select>
				</td>
				<td id="txt_agente">
					<select id="<?php print($variable_agente);?>" name="<?php print($variable_agente);?>" class="form-control">
						<option value="" selected>Selecciona un agente</option>
					</select>
				</td>
				<?php
					}
					else
					{
					    if($row['id_subproducto'] == 129)
					    {
					         $sensibilizacion = 'inline';
					    }
					    else
					    {
					        $sensibilizacion = 'none';
					    }
				?>
				<td id="txt_tipoagente">
					<select disabled id="<?php print($variable_tipoagente);?>" name="<?php print($variable_tipoagente);?>" class="form-control">
						<?php $this->selectTipoAgente($row3['id_tipoagente']);?>
					</select>
					<div class='col-sm-12 sensibilizacion' style='display:<?php print($sensibilizacion); ?>'><label class='radio-inline'><input onclick='getSensibilizacion(this); getPercentage(this);' type='radio' id='sensibilizacion<?php print($auxiliar); ?>' name='sensibilizacion<?php print($auxiliar); ?>' value='1' checked>Apoyo</label><input onclick='getSensibilizacion(this); getPercentage(this);' type='radio' id='sensibilizacion<?php print($auxiliar); ?>' name='sensibilizacion<?php print($auxiliar); ?>' value='0'>Coordinador</label><label class='radio-inline'><input onclick='getSensibilizacion(this); getPercentage(this);' type='radio' id='sensibilizacion<?php print($auxiliar); ?>' name='sensibilizacion<?php print($auxiliar); ?>' value='2'>Ambos</label></div>
				</td>
				<td id="txt_agente">
					<select id="<?php print($variable_agente);?>" name="<?php print($variable_agente);?>" class="form-control">
						<option value="" selected>Selecciona un agente</option>
					</select>
				</td>
				<?php
					}
				?>
				<td id="txt_equipo">
					<select name="<?php print($variable_equipo);?>" id="<?php print($variable_equipo);?>" class="form-control">
						<option value="1" selected>Estándar</option>
						<option value="2">Adicional</option>
					</select>
				</td>
				<td id="txt_material">
					<select name="<?php print($variable_material);?>" id="<?php print($variable_material);?>" class="form-control">
						<option value="1" selected>Estándar</option>
						<option value="2">Adicional</option>
					</select>
				</td>
				<td id="txt_precio">
					<input type="text" class="form-control" name="<?php print($variable_precio);?>" id="<?php print($variable_precio);?>" value="<?php print(number_format($row['precio_oi'],2));?>" readonly>
				</td>
				<td id="txt_descuento">	
					<input id="<?php print($variable_descuento);?>" name="<?php print($variable_descuento);?>" type="text" class="form-control" value="<?php print(round(1-$row['descuento'],2)*100) ;?>%" readonly>
				</td>
				<?php
				    if($row['id_subproducto'] == 57)
				    {
				 ?>
				 <td id="txt_honorarios">
					<input type="hidden" class="form-control" name="<?php print($variable_honorarios);?>" id="<?php print($variable_honorarios);?>" value="<?php print(number_format($row['costo_oi'],2));?>" readonly>
					<input <?php print($readOnlyCostos); ?> type="text" class="form-control" name="<?php print($variable_honorarios_aux);?>" id="<?php print($variable_honorarios_aux);?>" value="<?php print(number_format($row['costo_oi'],2));?>" readonly>
				</td>
				<td id="txt_porcentajehonorarios">
					<input type="text" class="form-control" name="<?php print($variable_porcentajehonorarios_aux);?>" id="<?php print($variable_porcentajehonorarios_aux);?>" value="<?php print(round($row['porcentajeHonorarios'],2)*100);?>%" readonly>
				</td>
				 <?php
				    }
				    else
				    {
				?>
				<td id="txt_honorarios">
					<input type="hidden" class="form-control" name="<?php print($variable_honorarios);?>" id="<?php print($variable_honorarios);?>" value="<?php print(number_format($row['costo_honorarios'],2));?>" readonly>
					<input <?php print($readOnlyCostos); ?> type="text" class="form-control" name="<?php print($variable_honorarios_aux);?>" id="<?php print($variable_honorarios_aux);?>" value="<?php print(number_format($row3['costo_tipoagente_subproducto'],2));?>" readonly>
				</td>
				<td id="txt_porcentajehonorarios">
					<input type="text" class="form-control" name="<?php print($variable_porcentajehonorarios_aux);?>" id="<?php print($variable_porcentajehonorarios_aux);?>" value="<?php print(round($row['porcentajeHonorarios'],2)*100);?>%" readonly>
				</td>
				<?php
				}
				?>
				<td id="txt_costomateriales">
					<input <?php print($readOnlyCostos); ?> type="text" class="form-control" name="<?php print($variable_costomateriales);?>" id="<?php print($variable_costomateriales);?>" value="<?php print(number_format($row['costo_materiales'],2));?>" readonly>
					<div class="col-sm-6">
						<label class="radio-inline">
							<input onclick="getPercentage(this);" type="radio" id="<?php print($variable_name_radio_button_costomaterial1);?>" name="<?php print($variable_name_radio_button_costomaterial);?>" value="1" checked onclick="">
							Aplicar costo
						</label>
					</div>
					<div class="col-sm-6">
						<label class="radio-inline">
							<input onclick="getPercentage(this);" type="radio" id="<?php print($variable_name_radio_button_costomaterial2);?>" name="<?php print($variable_name_radio_button_costomaterial);?>" value="0" onclick="">
							No aplicar costo
						</label>
					</div>
				</td>
				<td id="txt_porcentajemateriales">
					<input type="text" class="form-control" name="" id="" value="<?php print(round($row['porcentajeMateriales'],2)*100);?>%" readonly>
				</td>
				<td id="txt_costoadicionales">
					<input type="text" class="form-control" name="<?php print($variable_costosadicionales);?>" id="<?php print($variable_costosadicionales);?>" value="0.00" onkeyup="getCommas(this); getPercentage(this);">
				</td>
				<td id="txt_porcentajecostoadicionales">
					<input type="text" class="form-control" name="<?php print($variable_porcentajecostosadicionales);?>" id="<?php print($variable_porcentajecostosadicionales);?>" value="0%" readonly>
				</td>
				<td id="update_txt_costo">
					<input type="text" class="form-control" name="<?php print($variable_costo);?>" id="<?php print($variable_costo);?>" value="<?php print(number_format($row['costo_oi'],2));?>" readonly>
				</td>
				<td id="txt_porcentajecosto">
					<input type="text" class="form-control" name="<?php print($variable_porcentajecosto);?>" id="<?php print($variable_porcentajecosto);?>" value="<?php print(round($row['porcentajeCosto'],4)*100);?>%" readonly>
				</td>
				<td id="txt_margen">
					<input type="text" class="form-control" name="<?php print($variable_margen);?>" id="<?php print($variable_margen);?>" value="<?php print(number_format($row['precio_oi']-$row['costo_oi'],2));?>" readonly>
				</td>
				<td id="txt_porcentajemargen">
					<input type="text" class="form-control" name="<?php print($variable_porcentajemargen);?>" id="<?php print($variable_porcentajemargen);?>" value="<?php print(round(($row['porcentajeMargen'])*100,2));?>%" readonly>
				</td>
				<td id="txt_sede">
					<select name="<?php print($variable_sede);?>" id="<?php print($variable_sede);?>" class="form-control" onchange="getCopySede(this);">
						<?php $this->getEstados(); ?>
					</select>
				</td>
				<td id="txt_sede_particular">
					<input id="<?php print($variable_sede_particular);?>" name="<?php print($variable_sede_particular);?>" value="" type="text" class="form-control">
				</td>
				<td id="txt_fecharealizacion">
					<input id="<?php print($variable_fecha_realizacion1);?>" name="<?php print($variable_fecha_realizacion1);?>" value="" type="text" class="form-control <?php print($variable_class_fecha_realizacion1);?>" placeholder="dd-mm-yyyy">
					<input id="<?php print($variable_fecha_realizacion2);?>" name="<?php print($variable_fecha_realizacion2);?>" value="" type="text" class="form-control <?php print($variable_class_fecha_realizacion2);?>" placeholder="dd-mm-yyyy" style="display:none;">
					<input id="<?php print($variable_fecha_realizacion_horario2);?>" name="<?php print($variable_fecha_realizacion_horario2);?>" value="" type="text" class="form-control <?php print($variable_class_fecha_realizacion2);?>" placeholder="hh:mm" style="display:none;">
					<input id="<?php print($variable_fecha_realizacion3);?>" name="<?php print($variable_fecha_realizacion3);?>" value="" type="text" class="form-control <?php print($variable_class_fecha_realizacion3);?>" placeholder="dd-mm-yyyy" style="display:none;">
					<div class="col-sm-6">
						<label class="radio-inline">
							<input type="radio" id="<?php print($variable_radio_button1);?>" name="<?php print($variable_name_radio_button);?>" value="1" checked="checked" onclick="">
							Juntas
						</label>
						<label class="radio-inline">
							<input type="radio" id="<?php print($variable_radio_button2);?>" name="<?php print($variable_name_radio_button);?>" value="2" onclick="">
							Ordenadas
						</label>
					</div>
					<div class="col-sm-6">
						<label class="radio-inline">
							<input type="radio" id="<?php print($variable_radio_button3);?>" name="<?php print($variable_name_radio_button);?>" value="3">
							Separadas
						</label>
					</div>
				</td>
				<td id="txt_mes_ejecucion">
					<select name="<?php print($variable_mes_ejecucion);?>" id="<?php print($variable_mes_ejecucion);?>" class="form-control <?php print($variable_ejecutar_class);?>">
						<option value="1">Enero</option>
						<option value="2">Febrero</option>
						<option value="3">Marzo</option>
						<option value="4">Abril</option>
						<option value="5">Mayo</option>
						<option value="6">Junio</option>
						<option value="7">Julio</option>
						<option value="8">Agosto</option>
						<option value="9">Septiembre</option>
						<option value="10">Octubre</option>
						<option value="11">Noviembre</option>
						<option value="12">Diciembre</option>
					</select>
				</td>
				<td id="txt_estatus_ejecucion">
					<select onchange="" id="<?php print($variable_estatus_ejecucion);?>" name="<?php print($variable_estatus_ejecucion);?>" class="form-control">
						<option value="1">Ejecutado</option>
						<option value="2" selected>Por ejecutar</option>
					</select>
				</td>													
				<td id="txt_anomalias">
					<textarea rows="3" class="form-control textarea-autosize" id="<?php print($variable_anomalia);?>" name="<?php print($variable_anomalia);?>"></textarea>
				</td>
				<td id="txt_mes_costo">
					<select name="<?php print($variable_mes_costo);?>" id="<?php print($variable_mes_costo);?>" class="form-control <?php print($variable_costear_class);?>">
						<option value="1">Enero</option>
						<option value="2">Febrero</option>
						<option value="3">Marzo</option>
						<option value="4">Abril</option>
						<option value="5">Mayo</option>
						<option value="6">Junio</option>
						<option value="7">Julio</option>
						<option value="8">Agosto</option>
						<option value="9">Septiembre</option>
						<option value="10">Octubre</option>
						<option value="11">Noviembre</option>
						<option value="12">Diciembre</option>
					</select>
				</td>
				<td id="txt_estatus_costo">
					<select id="<?php print($variable_estatus_costo);?>" name="<?php print($variable_estatus_costo);?>" class="form-control">
						<option value="1">Pagado</option>
						<option value="2" selected>Por pagar</option>
					</select>
				</td>
				<td id="txt_mes_factura">
					<select name="<?php print($variable_mes_factura);?>" id="<?php print($variable_mes_factura);?>" class="form-control <?php print($variable_facturar_class);?>">
						<option value="1">Enero</option>
						<option value="2">Febrero</option>
						<option value="3">Marzo</option>
						<option value="4">Abril</option>
						<option value="5">Mayo</option>
						<option value="6">Junio</option>
						<option value="7">Julio</option>
						<option value="8">Agosto</option>
						<option value="9">Septiembre</option>
						<option value="10">Octubre</option>
						<option value="11">Noviembre</option>
						<option value="12">Diciembre</option>
					</select>
				</td>
				<td id="txt_estatus_factura">
					<select id="<?php print($variable_estatus_factura);?>" name="<?php print($variable_estatus_factura);?>" class="form-control">
						<option value="1">Facturado</option>
						<option value="2" selected>Por facturar</option>
					</select>
				</td>
				<td id="txt_factura">
					<input type="text" class="form-control" name="<?php print($variable_factura);?>" value="" id="<?php print($variable_factura);?>" >
				</td>
				<td id="txt_estatus_cobranza">
					<select id="<?php print($variable_estatus_cobranza);?>" name="<?php print($variable_estatus_cobranza);?>" class="form-control">
						<option value="1">Cobrado</option>
						<option value="2" selected>Por cobrar</option>
						<option value="3">Ingresada a plataforma</option>
						<option value="4">Monitoreo</option>
					</select>
				</td>
				<td id="txt_fecha_cobranza">
					<input id="<?php print($variable_fecha_cobranza);?>" name="<?php print($variable_fecha_cobranza);?>" value="" type="text" class="form-control" placeholder="dd-mm-yyyy" readonly>
				</td>
				<td id="txt_mes_cobranza">
					<select name="<?php print($variable_mes_cobranza);?>" id="<?php print($variable_mes_cobranza);?>" class="form-control <?php print($variable_cobrar_class);?>" readonly>
						<option value="1">Enero</option>
						<option value="2">Febrero</option>
						<option value="3">Marzo</option>
						<option value="4">Abril</option>
						<option value="5">Mayo</option>
						<option value="6">Junio</option>
						<option value="7">Julio</option>
						<option value="8">Agosto</option>
						<option value="9">Septiembre</option>
						<option value="10">Octubre</option>
						<option value="11">Noviembre</option>
						<option value="12">Diciembre</option>
					</select>
				</td>
				<td id="txt_viaticos">
					<input type="checkbox" class="form-control" name="<?php print($variable_viaticos);?>" id="<?php print($variable_viaticos);?>" value="0" onclick="getReadonly(this);">
				</td>
			</tr>
			<?php
					if($comprobacion)
					{
						$elementos = count($arrayAux);
						for($j = 1; $j < $elementos; $j++)
						{
						if($row['precio_oi'] == 0)
						{
						    $porcentajeHon = 0;
						 }
						 else
						 {
						    $porcentajeHon = $arrayAux2[$j]/$row['precio_oi'];
						 }
			?>
			<tr class="" style="<?php print($styleHidden);?>">
				<td id=""><input type="hidden" class="form-control" name="" id="" value="<?php print($i);?>" readonly></td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id="txt_tipoagente_v2">
					<select disabled id="<?php print('tipoagente'.$j.'_'.$auxiliar);?>" name="<?php print('tipoagente'.$j.'_'.$auxiliar);?>" class="form-control">
						<?php $this->selectTipoAgente($arrayAux[$j]);?>
					</select>
				</td>
				<td id="txt_agente_v2">
					<select id="<?php print('agente'.$j.'_'.$auxiliar);?>" name="<?php print('agente'.$j.'_'.$auxiliar);?>" class="form-control agente">
						<option value="" selected>Selecciona un agente</option>
					</select>
				</td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id="">
					<input <?php print($readOnlyCostos); ?> type="text" class="form-control" name="<?php print('honorarios'.$j.'_'.$auxiliar);?>" id="<?php print('honorarios'.$j.'_'.$auxiliar);?>" value="<?php print(number_format($arrayAux2[$j],2));?>" readonly>
				</td>
				<td id="">
					<input type="text" class="form-control" name="<?php print('porcentajehonorarios'.$j.'_'.$auxiliar);?>" id="<?php print('porcentajehonorarios'.$j.'_'.$auxiliar);?>" value="<?php print(round($porcentajeHon,4)*100);?>%" readonly>
				</td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>													
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
				<td id=""></td>
			</tr>
			<?php
						}
					}
					$auxiliar++;
				}
				$contador++;
			}
			$aux = $auxiliar;
			?>
			<input id="contador" name="contador" value="<?php print($aux); ?>" type="text" hidden />
			<?php
		}
		public function showOIDetalleDigitalCentr($ordenes)
		{
			$query = "SELECT 
					1 AS ordenador,
					DO.id_detalleoi,
					Fam.familia_producto,
					Fam.id_familiaproducto,
					Prod.id_producto,
					Prod.nombre_producto,
					DO.id_subproducto,
					Sub.nombre_subproducto,
					DO.tema,
					DO.personas,
					DO.id_nivel,
					N.nivel,
					(CASE WHEN Prod.id_producto = 40 THEN DO.precio_oi/4 WHEN Prod.id_producto = 41 THEN DO.precio_oi/3 WHEN Prod.id_producto = 42 THEN DO.precio_oi/2 ELSE DO.precio_oi END) AS precio_oi,
					DO.descuento,
					(CASE WHEN Prod.id_producto = 40 THEN DO.gasto_directo/4 WHEN Prod.id_producto = 41 THEN DO.gasto_directo/3 WHEN Prod.id_producto = 42 THEN DO.gasto_directo/2 ELSE DO.gasto_directo END) AS gasto_directo,
					(CASE WHEN Prod.id_producto = 40 THEN DO.costo_oi/4 WHEN Prod.id_producto = 41 THEN DO.costo_oi/3 WHEN Prod.id_producto = 42 THEN DO.costo_oi/2 ELSE DO.costo_oi END) AS costo_oi,
					(CASE WHEN Prod.id_producto = 40 THEN Sub.costo_honorarios/4 WHEN Prod.id_producto = 41 THEN Sub.costo_honorarios/3 WHEN Prod.id_producto = 42 THEN Sub.costo_honorarios/2 ELSE Sub.costo_honorarios END) AS costo_honorarios,
					(CASE WHEN Prod.id_producto = 40 THEN Sub.costo_materiales/4 WHEN Prod.id_producto = 41 THEN Sub.costo_materiales/3 WHEN Prod.id_producto = 42 THEN Sub.costo_materiales/2 ELSE Sub.costo_materiales END) AS costo_materiales,
					(CASE WHEN Prod.id_producto = 40 THEN DO.cantidad_oi*4 WHEN Prod.id_producto = 41 THEN DO.cantidad_oi*3 WHEN Prod.id_producto = 42 THEN DO.cantidad_oi*2 ELSE DO.cantidad_oi END) AS cantidad_oi,
					'tipo agente' AS idTipoAgente,
					'tipo agente' AS tipoAgente,
					'costo_agente'AS costoAgente
				FROM  T_DetalleOI AS DO
				INNER JOIN T_Subproductos AS Sub
				ON DO.id_subproducto = Sub.id_subproducto
				INNER JOIN T_Productos AS Prod
				ON Sub.id_producto = Prod.id_producto
				INNER JOIN T_FamiliaProductos AS Fam
				ON Prod.id_familiaproducto = Fam.id_familiaproducto
				INNER JOIN T_Niveles AS N
				ON N.id_nivel = DO.id_nivel
				WHERE DO.id_oi = :ordenes  AND DO.id_estatusoi = 1 
				UNION
				SELECT 
				       1 AS ordenador,
					DOS.id_detalleoi,
					'',
					'',
					Prod.id_parte,
					Prod.parte,
					DOS.id_subproducto_subparte,
					SS.subparte,
					DOS.tema,
					DOS.personas,
					DOS.id_dificultad,
					N.dificultad,
					DOS.precio_oi,
					DOS.descuento,
					DOS.gasto_directo,
					DOS.costo_oi,
					0,
					0,
					DO
				FROM  T_DetalleOI AS DOS
				INNER JOIN T_Subproductos_Subpartes AS S
				ON DOS.id_subproducto_subparte = S.id_subproducto_subparte
                          INNER JOIN T_Subpartes_DC AS SS
				ON SS.id_subparte = S.id_subparte
				INNER JOIN T_Partes_DC AS Prod
				ON SS.id_parte = Prod.id_parte
				INNER JOIN T_Dificultad AS N
				ON N.id_dificultad = DOS.id_dificultad
				WHERE DOS.id_oi = :ordenes AND DOS.id_estatusoi = 1";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":ordenes",$ordenes);
			$stmt->execute();
			
			$contador = 1;
			$auxiliar = 1;
			$auxiliar2 = 0;
			$cantidad = 0;
			$rowAux = array();
			$result=$stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result as $row)
			{
				$subtema[1] = "";
				$subtema[2] = "";
				$subtema[3] = "";
				$subtema[4] = "";
				$cantidad = $row['cantidad_oi'];
				$auxiliar = 1;
				for($i=1;$i<=$cantidad;$i++)
				{
					$rowAux[$auxiliar2][$auxiliar]['totalAgentes'] = $auxiliar2;
					$rowAux[$auxiliar2][$auxiliar]['ordenador'] = $auxiliar;
					$rowAux[$auxiliar2][$auxiliar]['id_detalleoi'] = $row['id_detalleoi'];
					$rowAux[$auxiliar2][$auxiliar]['familia_producto'] = $row['familia_producto'];
					$rowAux[$auxiliar2][$auxiliar]['id_familiaproducto'] = $row['id_familiaproducto'];
					$rowAux[$auxiliar2][$auxiliar]['id_producto'] = $row['id_producto'];
					$rowAux[$auxiliar2][$auxiliar]['nombre_producto'] = $row['nombre_producto'];
					$rowAux[$auxiliar2][$auxiliar]['id_subproducto'] = $row['id_subproducto'];
					$rowAux[$auxiliar2][$auxiliar]['nombre_subproducto'] = $row['nombre_subproducto'];
					$rowAux[$auxiliar2][$auxiliar]['tema'] = $row['tema'];
					$rowAux[$auxiliar2][$auxiliar]['personas'] = $row['personas'];
					$rowAux[$auxiliar2][$auxiliar]['id_nivel'] = $row['id_nivel'];
					$rowAux[$auxiliar2][$auxiliar]['nivel'] = $row['nivel'];
					$rowAux[$auxiliar2][$auxiliar]['precio_oi'] = $row['precio_oi'];
					$rowAux[$auxiliar2][$auxiliar]['descuento'] = $row['descuento'];
					$rowAux[$auxiliar2][$auxiliar]['costo_oi'] = $row['costo_oi'];
					$rowAux[$auxiliar2][$auxiliar]['gasto_directo'] = $row['gasto_directo'];
					$rowAux[$auxiliar2][$auxiliar]['costo_honorarios'] = $row['costo_honorarios'];
					$rowAux[$auxiliar2][$auxiliar]['costo_materiales'] = $row['costo_materiales'];
					$rowAux[$auxiliar2][$auxiliar]['cantidad_oi'] = $row['cantidad_oi'];
					$rowAux[$auxiliar2][$auxiliar]['idTipoAgente'] = $row['idTipoAgente'];
					$rowAux[$auxiliar2][$auxiliar]['tipoAgente'] = $row['tipoAgente'];
					$rowAux[$auxiliar2][$auxiliar]['costoAgente'] = $row['costoAgente'];
					
					$auxiliar++;
				}
				$auxiliar2++;
				$contador = $auxiliar;
			}
			$estados = $this->getEstadosNew();
			$i = 0;
			foreach($rowAux as $rows)
			{
				foreach($rows as $row)
				{
					$auxiliar = $row['ordenador'];
					echo "<tr>
					<td><input id='evento".$auxiliar."' name='evento".$auxiliar."' type='text' class='form-control' value='".$row['ordenador']."' readonly='readonly'></td>
					<td><input id='id_evento".$auxiliar."' name='id_evento".$auxiliar."' type='text' class='form-control' value='".$auxiliar.$row['tema']."' readonly='readonly'></td>
					<td><input id='tema".$auxiliar."' name='tema".$auxiliar."' type='text' class='form-control' value='".$row['tema']."' readonly='readonly'></td>
					<td><select id='equipo".$auxiliar."' name='equipo".$auxiliar."' class='form-control' onchange='copiarEquipo(this);'><option selected value='1' selected>Estándar</option><option value='2'>Adicional</option></select></td>
					<td><select id='material".$auxiliar."' name='material".$auxiliar."' class='form-control' onchange='copiarMat(this);'><option selected value='1' selected>Estándar</option><option value='2'>Adicional</option></select></td>
					<td><input id='personas".$auxiliar."' name='personas".$auxiliar."' type='text' class='form-control' value=".$row['personas']." readonly='readonly'></td>
					<td><select id='familia".$auxiliar."' name='familia".$auxiliar."' class='form-control' readonly='readonly'><option selected value='".$row['id_familiaproducto']."'>".$row['familia_producto']."</option></select></td>
					<td><select id='producto".$auxiliar."' name='producto".$auxiliar."' class='form-control' readonly='readonly'><option selected value='".$row['id_producto']."'>".$row['nombre_producto']."</option></select></td>
					<td><select id='subproducto".$auxiliar."' name='subproducto".$auxiliar."' class='form-control' readonly='readonly'><option selected value='".$row['id_subproducto']."'>".$row['nombre_subproducto']."</option></select></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><input id='precio".$auxiliar."' name='precio".$auxiliar."' type='text' class='form-control' value='".number_format($row['precio_oi'],2)."' readonly='readonly'></td>
					<td><input id='descuento".$auxiliar."' name='descuento".$auxiliar."' type='text' class='form-control' value='".(number_format($row['gasto_directo'],2))."' readonly='readonly'></td>
					<td><input id='' name='' type='text' class='form-control' value='".(round($row['gasto_directo']/$row['precio_oi'],2)*100)."%' readonly='readonly'></td>
					<td><input id='honorarios".$auxiliar."' name='honorarios".$auxiliar."' type='text' class='form-control' value=".number_format($row['costo_honorarios'],2)." readonly='readonly'></td>
					<td><input id='' name='' type='text' class='form-control' value='".(round($row['costo_honorarios']/$row['precio_oi'],2)*100)."%' readonly='readonly'></td>
					<td><input id='costomateriales".$auxiliar."' name='costomateriales".$auxiliar."' type='text' class='form-control' value=".number_format($row['costo_materiales'],2)." readonly='readonly'><div class='col-sm-6'><label class='radio-inline'><input onclick='getPercentage(this); copiarMateriales(this);' type='radio' id='radio-costomaterial_1_".$auxiliar."' name='radio-costomaterial_1_".$auxiliar."' value='1' checked>Aplicar costo</label></div><div class='col-sm-6'><label class='radio-inline'><input onclick='getPercentage(this); copiarMateriales(this);' type='radio' id='radio-costomaterial_1_".$auxiliar."' name='radio-costomaterial_1_".$auxiliar."' value='0'>No aplicar costo</label></div></td>
					<td><input id='' name='' type='text' class='form-control' value='".(round($row['costo_materiales']/$row['precio_oi'],2)*100)."%' readonly='readonly'></td>
					<td><input id='costosadicionales".$auxiliar."' name='costosadicionales".$auxiliar."' value='0.00' type='text' class='form-control' onkeyup='getCommas(this); getPercentage(this);'></td>
					<td><input type='text' class='form-control' name='porcentajecostosadicionales".$auxiliar."' id='porcentajecostosadicionales".$auxiliar."' value='0%' readonly='readonly'></td>
					<td><input id='costo".$auxiliar."' name='costo".$auxiliar."' type='text' class='form-control' value=".number_format($row['costo_oi'],2)." readonly='readonly'></td>
					<td><input type='text' class='form-control' name='porcentajecosto".$auxiliar."' id='porcentajecosto".$auxiliar."' value='".(round(($row['costo_oi']/$row['precio_oi'])*100,2))."%' readonly='readonly'></td>
					<td><input type='text' class='form-control' name='margen".$auxiliar."' id='margen".$auxiliar."' value='".number_format($row['precio_oi']-$row['costo_oi'],2)."' readonly='readonly'></td>
					<td><input type='text' class='form-control' name='' id='' value='".(round((($row['precio_oi']-$row['costo_oi'])/$row['precio_oi'])*100,2))."%' readonly='readonly'></td>
					<td><select id='sede".$auxiliar."' name='sede".$auxiliar."' class='form-control' onchange='getCopySede(this);'>".$estados."</select></td>
					<td><input onkeyup='copiarSedePart(this);' id='sede_particular".$auxiliar."' name='sede_particular".$auxiliar."' type='text' class='form-control'></td>
					<td><input id='fecha_realizacion_3_".$auxiliar."' name='fecha_realizacion_3_".$auxiliar."' type='text' class='form-control fecha_realizacion_3_ fecha_realizacion_3_".$auxiliar."' readonly=''></td>
					<td><select id='mes_ejecucion".$auxiliar."' name='mes_ejecucion".$auxiliar."' class='form-control ejecutar".$auxiliar."'><option value='1'>Enero</option><option value='2'>Febrero</option><option value='3'>Marzo</option><option value='4'>Abril</option><option value='5'>Mayo</option><option value='6'>Junio</option><option value='7'>Julio</option><option value='8'>Agosto</option><option value='9'>Septiembre</option><option value='10'>Octubre</option><option value='11'>Noviembre</option><option value='12'>Diciembre</option></select></td>
					<td><select id='estatus_ejecucion".$auxiliar."' name='estatus_ejecucion".$auxiliar."' class='form-control' onchange='getCloneEjecucion(this);'><option value='1'>Ejecutado</option><option selected value='2'>Por Ejecutar</option></select></td>
					<td><textarea rows='3' class='form-control textarea-autosize' id='anomalia' name='anomalia'></textarea></td>
					<td><select id='mes_costo".$auxiliar."' name='mes_costo".$auxiliar."' class='form-control costear".$auxiliar."'><option value='1'>Enero</option><option value='2'>Febrero</option><option value='3'>Marzo</option><option value='4'>Abril</option><option value='5'>Mayo</option><option value='6'>Junio</option><option value='7'>Julio</option><option value='8'>Agosto</option><option value='9'>Septiembre</option><option value='10'>Octubre</option><option value='11'>Noviembre</option><option value='12'>Diciembre</option></select></td>
					<td><select id='estatus_costo".$auxiliar."' name='estatus_costo".$auxiliar."' class='form-control' onchange='getCloneCosto(this);'><option value='1'>Pagado</option><option selected value='2'>Por Pagar</option></select></td>
					<td><select id='mes_factura".$auxiliar."' name='mes_factura".$auxiliar."' class='form-control facturar".$auxiliar."'><option value='1'>Enero</option><option value='2'>Febrero</option><option value='3'>Marzo</option><option value='4'>Abril</option><option value='5'>Mayo</option><option value='6'>Junio</option><option value='7'>Julio</option><option value='8'>Agosto</option><option value='9'>Septiembre</option><option value='10'>Octubre</option><option value='11'>Noviembre</option><option value='12'>Diciembre</option></select></td>
					<td><select id='estatus_factura".$auxiliar."' name='estatus_factura".$auxiliar."' class='form-control' onchange='getCloneFacturacion(this);'><option value='1'>Facturado</option><option selected value='2'>Por Facturar</option></select></td>
					<td><input id='factura".$auxiliar."' name='factura".$auxiliar."' type='text' class='form-control' reaonly></td>
					<td><select id='estatus_cobranza".$auxiliar."' name='estatus_cobranza".$auxiliar."' class='form-control' onchange='getCloneCobranza(this);'><option value='1'>Cobrado</option><option selected value='2'>Por Cobrar</option><option value='3'>Ingresada a plataforma</option><option value='4'>Monitoreo</option></select></td>
					<td><input id='fecha_cobranza_".$auxiliar."' name='fecha_cobranza_".$auxiliar."' type='text' class='form-control'></td>
					<td><select id='mes_cobranza".$auxiliar."' name='mes_cobranza".$auxiliar."' class='form-control cobrar".$auxiliar."'><option value='1'>Enero</option><option value='2'>Febrero</option><option value='3'>Marzo</option><option value='4'>Abril</option><option value='5'>Mayo</option><option value='6'>Junio</option><option value='7'>Julio</option><option value='8'>Agosto</option><option value='9'>Septiembre</option><option value='10'>Octubre</option><option value='11'>Noviembre</option><option value='12'>Diciembre</option></select></td>
					<td><input type='checkbox' class='form-control' name='viaticos".$auxiliar."' id='".$auxiliar."' value='0' onclick='getReadonly(this);'></td>
					</tr>
					<tr>
					<td><input id='evento".$auxiliar."' name='evento".$auxiliar."' type='text' class='form-control' value='".$row['ordenador']."' readonly='readonly'></td>
					<td><input id='id_evento".$auxiliar."' name='id_evento".$auxiliar."' type='text' class='form-control' value='".$auxiliar.$row['tema']."' readonly='readonly'></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><select id='subproducto".$auxiliar."' name='subproducto".$auxiliar."' class='form-control' readonly='readonly'><option selected value='Análisis de contenidos'>Contenidos</option></select></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><input id='honorarios".$auxiliar."' name='honorarios".$auxiliar."' type='text' class='form-control' value='10,500.00' readonly='readonly'></td>
					<td><input id='' name='' type='text' class='form-control' value='".(round(10500/$row['precio_oi'],2)*100)."%' readonly='readonly'></td>
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
					<td><input id='fecha_realizacion_3_".$auxiliar."' name='fecha_realizacion_3_".$auxiliar."' type='text' class='form-control fecha_realizacion_3_ fecha_realizacion_3_".$auxiliar."' readonly=''></td>
					<td><select id='mes_ejecucion".$auxiliar."' name='mes_ejecucion".$auxiliar."' class='form-control ejecutar".$auxiliar."'><option value='1'>Enero</option><option value='2'>Febrero</option><option value='3'>Marzo</option><option value='4'>Abril</option><option value='5'>Mayo</option><option value='6'>Junio</option><option value='7'>Julio</option><option value='8'>Agosto</option><option value='9'>Septiembre</option><option value='10'>Octubre</option><option value='11'>Noviembre</option><option value='12'>Diciembre</option></select></td>
					<td><select id='estatus_ejecucion".$auxiliar."' name='estatus_ejecucion".$auxiliar."' class='form-control' onchange='getCloneEjecucion(this);'><option value='1'>Ejecutado</option><option selected value='2'>Por Ejecutar</option></select></td>
					<td><textarea rows='3' class='form-control textarea-autosize' id='anomalia' name='anomalia'></textarea></td>
					<td><select id='mes_costo".$auxiliar."' name='mes_costo".$auxiliar."' class='form-control costear".$auxiliar."'><option value='1'>Enero</option><option value='2'>Febrero</option><option value='3'>Marzo</option><option value='4'>Abril</option><option value='5'>Mayo</option><option value='6'>Junio</option><option value='7'>Julio</option><option value='8'>Agosto</option><option value='9'>Septiembre</option><option value='10'>Octubre</option><option value='11'>Noviembre</option><option value='12'>Diciembre</option></select></td>
					<td><select id='estatus_costo".$auxiliar."' name='estatus_costo".$auxiliar."' class='form-control' onchange='getCloneCosto(this);'><option value='1'>Pagado</option><option selected value='2'>Por Pagar</option></select></td>
					<td><select id='mes_factura".$auxiliar."' name='mes_factura".$auxiliar."' class='form-control facturar".$auxiliar."'><option value='1'>Enero</option><option value='2'>Febrero</option><option value='3'>Marzo</option><option value='4'>Abril</option><option value='5'>Mayo</option><option value='6'>Junio</option><option value='7'>Julio</option><option value='8'>Agosto</option><option value='9'>Septiembre</option><option value='10'>Octubre</option><option value='11'>Noviembre</option><option value='12'>Diciembre</option></select></td>
					<td><select id='estatus_factura".$auxiliar."' name='estatus_factura".$auxiliar."' class='form-control' onchange='getCloneFacturacion(this);'><option value='1'>Facturado</option><option selected value='2'>Por Facturar</option></select></td>
					<td><input id='factura".$auxiliar."' name='factura".$auxiliar."' type='text' class='form-control' reaonly></td>
					<td><select id='estatus_cobranza".$auxiliar."' name='estatus_cobranza".$auxiliar."' class='form-control' onchange='getCloneCobranza(this);'><option value='1'>Cobrado</option><option selected value='2'>Por Cobrar</option><option value='3'>Ingresada a plataforma</option><option value='4'>Monitoreo</option></select></td>
					<td><input id='fecha_cobranza_".$auxiliar."' name='fecha_cobranza_".$auxiliar."' type='text' class='form-control'></td>
					<td><select id='mes_cobranza".$auxiliar."' name='mes_cobranza".$auxiliar."' class='form-control cobrar".$auxiliar."'><option value='1'>Enero</option><option value='2'>Febrero</option><option value='3'>Marzo</option><option value='4'>Abril</option><option value='5'>Mayo</option><option value='6'>Junio</option><option value='7'>Julio</option><option value='8'>Agosto</option><option value='9'>Septiembre</option><option value='10'>Octubre</option><option value='11'>Noviembre</option><option value='12'>Diciembre</option></select></td>
					<td><input type='checkbox' class='form-control' name='viaticos".$auxiliar."' id='".$auxiliar."' value='0' onclick='getReadonly(this);'></td>
					</tr>
					<tr>
					<td><input id='evento".$auxiliar."' name='evento".$auxiliar."' type='text' class='form-control' value='".$row['ordenador']."' readonly='readonly'></td>
					<td><input id='id_evento".$auxiliar."' name='id_evento".$auxiliar."' type='text' class='form-control' value='".$auxiliar.$row['tema']."' readonly='readonly'></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><select id='subproducto".$auxiliar."' name='subproducto".$auxiliar."' class='form-control' readonly='readonly'><option selected value='Análisis de contenidos'>Análisis de contenidos</option></select></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><input id='honorarios".$auxiliar."' name='honorarios".$auxiliar."' type='text' class='form-control' value='2,625.00' readonly='readonly'></td>
					<td><input id='' name='' type='text' class='form-control' value='".(round(2625/$row['precio_oi'],2)*100)."%' readonly='readonly'></td>
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
					<td><input id='fecha_realizacion_3_".$auxiliar."' name='fecha_realizacion_3_".$auxiliar."' type='text' class='form-control fecha_realizacion_3_ fecha_realizacion_3_".$auxiliar."' readonly=''></td>
					<td><select id='mes_ejecucion".$auxiliar."' name='mes_ejecucion".$auxiliar."' class='form-control ejecutar".$auxiliar."'><option value='1'>Enero</option><option value='2'>Febrero</option><option value='3'>Marzo</option><option value='4'>Abril</option><option value='5'>Mayo</option><option value='6'>Junio</option><option value='7'>Julio</option><option value='8'>Agosto</option><option value='9'>Septiembre</option><option value='10'>Octubre</option><option value='11'>Noviembre</option><option value='12'>Diciembre</option></select></td>
					<td><select id='estatus_ejecucion".$auxiliar."' name='estatus_ejecucion".$auxiliar."' class='form-control' onchange='getCloneEjecucion(this);'><option value='1'>Ejecutado</option><option selected value='2'>Por Ejecutar</option></select></td>
					<td><textarea rows='3' class='form-control textarea-autosize' id='anomalia' name='anomalia'></textarea></td>
					<td><select id='mes_costo".$auxiliar."' name='mes_costo".$auxiliar."' class='form-control costear".$auxiliar."'><option value='1'>Enero</option><option value='2'>Febrero</option><option value='3'>Marzo</option><option value='4'>Abril</option><option value='5'>Mayo</option><option value='6'>Junio</option><option value='7'>Julio</option><option value='8'>Agosto</option><option value='9'>Septiembre</option><option value='10'>Octubre</option><option value='11'>Noviembre</option><option value='12'>Diciembre</option></select></td>
					<td><select id='estatus_costo".$auxiliar."' name='estatus_costo".$auxiliar."' class='form-control' onchange='getCloneCosto(this);'><option value='1'>Pagado</option><option selected value='2'>Por Pagar</option></select></td>
					<td><select id='mes_factura".$auxiliar."' name='mes_factura".$auxiliar."' class='form-control facturar".$auxiliar."'><option value='1'>Enero</option><option value='2'>Febrero</option><option value='3'>Marzo</option><option value='4'>Abril</option><option value='5'>Mayo</option><option value='6'>Junio</option><option value='7'>Julio</option><option value='8'>Agosto</option><option value='9'>Septiembre</option><option value='10'>Octubre</option><option value='11'>Noviembre</option><option value='12'>Diciembre</option></select></td>
					<td><select id='estatus_factura".$auxiliar."' name='estatus_factura".$auxiliar."' class='form-control' onchange='getCloneFacturacion(this);'><option value='1'>Facturado</option><option selected value='2'>Por Facturar</option></select></td>
					<td><input id='factura".$auxiliar."' name='factura".$auxiliar."' type='text' class='form-control' reaonly></td>
					<td><select id='estatus_cobranza".$auxiliar."' name='estatus_cobranza".$auxiliar."' class='form-control' onchange='getCloneCobranza(this);'><option value='1'>Cobrado</option><option selected value='2'>Por Cobrar</option><option value='3'>Ingresada a plataforma</option><option value='4'>Monitoreo</option></select></td>
					<td><input id='fecha_cobranza_".$auxiliar."' name='fecha_cobranza_".$auxiliar."' type='text' class='form-control'></td>
					<td><select id='mes_cobranza".$auxiliar."' name='mes_cobranza".$auxiliar."' class='form-control cobrar".$auxiliar."'><option value='1'>Enero</option><option value='2'>Febrero</option><option value='3'>Marzo</option><option value='4'>Abril</option><option value='5'>Mayo</option><option value='6'>Junio</option><option value='7'>Julio</option><option value='8'>Agosto</option><option value='9'>Septiembre</option><option value='10'>Octubre</option><option value='11'>Noviembre</option><option value='12'>Diciembre</option></select></td>
					<td><input type='checkbox' class='form-control' name='viaticos".$auxiliar."' id='".$auxiliar."' value='0' onclick='getReadonly(this);'></td>
					</tr>
					<tr>
					<td><input id='evento".$auxiliar."' name='evento".$auxiliar."' type='text' class='form-control' value='".$row['ordenador']."' readonly='readonly'></td>
					<td><input id='id_evento".$auxiliar."' name='id_evento".$auxiliar."' type='text' class='form-control' value='".$auxiliar.$row['tema']."' readonly='readonly'></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><select id='nivel".$auxiliar."' name='nivel".$auxiliar."' class='form-control' readonly='readonly'><option selected value='Junior'>Junior</option></select></td>
					<td><select id='subproducto".$auxiliar."' name='subproducto".$auxiliar."' class='form-control' readonly='readonly'><option selected value='Análisis de contenidos'>Asistencia a curso</option></select></td>
					<td><select id='subproducto".$auxiliar."' name='subproducto".$auxiliar."' class='form-control' readonly='readonly'><option selected value='Análisis de contenidos'>Diseñador Instruccional</option></select></td>
					<td><select id='subproducto".$auxiliar."' name='subproducto".$auxiliar."' class='form-control' readonly='readonly'><option selected value='Análisis de contenidos'>Diseñador 1</option></select></td>
					<td></td>
					<td><input id='honorarios".$auxiliar."' name='honorarios".$auxiliar."' type='text' class='form-control' value='875.00' readonly='readonly'></td>
					<td><input id='' name='' type='text' class='form-control' value='".(round(875/$row['precio_oi'],2)*100)."%' readonly='readonly'></td>
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
					<td><input id='fecha_realizacion_3_".$auxiliar."' name='fecha_realizacion_3_".$auxiliar."' type='text' class='form-control fecha_realizacion_3_ fecha_realizacion_3_".$auxiliar."' readonly=''></td>
					<td><select id='mes_ejecucion".$auxiliar."' name='mes_ejecucion".$auxiliar."' class='form-control ejecutar".$auxiliar."'><option value='1'>Enero</option><option value='2'>Febrero</option><option value='3'>Marzo</option><option value='4'>Abril</option><option value='5'>Mayo</option><option value='6'>Junio</option><option value='7'>Julio</option><option value='8'>Agosto</option><option value='9'>Septiembre</option><option value='10'>Octubre</option><option value='11'>Noviembre</option><option value='12'>Diciembre</option></select></td>
					<td><select id='estatus_ejecucion".$auxiliar."' name='estatus_ejecucion".$auxiliar."' class='form-control' onchange='getCloneEjecucion(this);'><option value='1'>Ejecutado</option><option selected value='2'>Por Ejecutar</option></select></td>
					<td><textarea rows='3' class='form-control textarea-autosize' id='anomalia' name='anomalia'></textarea></td>
					<td><select id='mes_costo".$auxiliar."' name='mes_costo".$auxiliar."' class='form-control costear".$auxiliar."'><option value='1'>Enero</option><option value='2'>Febrero</option><option value='3'>Marzo</option><option value='4'>Abril</option><option value='5'>Mayo</option><option value='6'>Junio</option><option value='7'>Julio</option><option value='8'>Agosto</option><option value='9'>Septiembre</option><option value='10'>Octubre</option><option value='11'>Noviembre</option><option value='12'>Diciembre</option></select></td>
					<td><select id='estatus_costo".$auxiliar."' name='estatus_costo".$auxiliar."' class='form-control' onchange='getCloneCosto(this);'><option value='1'>Pagado</option><option selected value='2'>Por Pagar</option></select></td>
					<td><select id='mes_factura".$auxiliar."' name='mes_factura".$auxiliar."' class='form-control facturar".$auxiliar."'><option value='1'>Enero</option><option value='2'>Febrero</option><option value='3'>Marzo</option><option value='4'>Abril</option><option value='5'>Mayo</option><option value='6'>Junio</option><option value='7'>Julio</option><option value='8'>Agosto</option><option value='9'>Septiembre</option><option value='10'>Octubre</option><option value='11'>Noviembre</option><option value='12'>Diciembre</option></select></td>
					<td><select id='estatus_factura".$auxiliar."' name='estatus_factura".$auxiliar."' class='form-control' onchange='getCloneFacturacion(this);'><option value='1'>Facturado</option><option selected value='2'>Por Facturar</option></select></td>
					<td><input id='factura".$auxiliar."' name='factura".$auxiliar."' type='text' class='form-control' reaonly></td>
					<td><select id='estatus_cobranza".$auxiliar."' name='estatus_cobranza".$auxiliar."' class='form-control' onchange='getCloneCobranza(this);'><option value='1'>Cobrado</option><option selected value='2'>Por Cobrar</option><option value='3'>Ingresada a plataforma</option><option value='4'>Monitoreo</option></select></td>
					<td><input id='fecha_cobranza_".$auxiliar."' name='fecha_cobranza_".$auxiliar."' type='text' class='form-control'></td>
					<td><select id='mes_cobranza".$auxiliar."' name='mes_cobranza".$auxiliar."' class='form-control cobrar".$auxiliar."'><option value='1'>Enero</option><option value='2'>Febrero</option><option value='3'>Marzo</option><option value='4'>Abril</option><option value='5'>Mayo</option><option value='6'>Junio</option><option value='7'>Julio</option><option value='8'>Agosto</option><option value='9'>Septiembre</option><option value='10'>Octubre</option><option value='11'>Noviembre</option><option value='12'>Diciembre</option></select></td>
					<td><input type='checkbox' class='form-control' name='viaticos".$auxiliar."' id='".$auxiliar."' value='0' onclick='getReadonly(this);'></td>
					</tr>
					<tr>
					<td><input id='evento".$auxiliar."' name='evento".$auxiliar."' type='text' class='form-control' value='".$row['ordenador']."' readonly='readonly'></td>
					<td><input id='id_evento".$auxiliar."' name='id_evento".$auxiliar."' type='text' class='form-control' value='".$auxiliar.$row['tema']."' readonly='readonly'></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><select id='nivel".$auxiliar."' name='nivel".$auxiliar."' class='form-control' readonly='readonly'><option selected value='Junior'>Junior</option></select></td>
					<td><select id='subproducto".$auxiliar."' name='subproducto".$auxiliar."' class='form-control' readonly='readonly'><option selected value='Análisis de contenidos'>Tratamiento y ajuste MF por cuartilla</option></select></td>
					<td><select id='subproducto".$auxiliar."' name='subproducto".$auxiliar."' class='form-control' readonly='readonly'><option selected value='Análisis de contenidos'>Diseñador Instruccional</option></select></td>
					<td><select id='subproducto".$auxiliar."' name='subproducto".$auxiliar."' class='form-control' readonly='readonly'><option selected value='Análisis de contenidos'>Diseñador 1</option></select></td>
					<td></td>
					<td><input id='honorarios".$auxiliar."' name='honorarios".$auxiliar."' type='text' class='form-control' value='875.00' readonly='readonly'></td>
					<td><input id='' name='' type='text' class='form-control' value='".(round(875/$row['precio_oi'],2)*100)."%' readonly='readonly'></td>
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
					<td><input id='fecha_realizacion_3_".$auxiliar."' name='fecha_realizacion_3_".$auxiliar."' type='text' class='form-control fecha_realizacion_3_ fecha_realizacion_3_".$auxiliar."' readonly=''></td>
					<td><select id='mes_ejecucion".$auxiliar."' name='mes_ejecucion".$auxiliar."' class='form-control ejecutar".$auxiliar."'><option value='1'>Enero</option><option value='2'>Febrero</option><option value='3'>Marzo</option><option value='4'>Abril</option><option value='5'>Mayo</option><option value='6'>Junio</option><option value='7'>Julio</option><option value='8'>Agosto</option><option value='9'>Septiembre</option><option value='10'>Octubre</option><option value='11'>Noviembre</option><option value='12'>Diciembre</option></select></td>
					<td><select id='estatus_ejecucion".$auxiliar."' name='estatus_ejecucion".$auxiliar."' class='form-control' onchange='getCloneEjecucion(this);'><option value='1'>Ejecutado</option><option selected value='2'>Por Ejecutar</option></select></td>
					<td><textarea rows='3' class='form-control textarea-autosize' id='anomalia' name='anomalia'></textarea></td>
					<td><select id='mes_costo".$auxiliar."' name='mes_costo".$auxiliar."' class='form-control costear".$auxiliar."'><option value='1'>Enero</option><option value='2'>Febrero</option><option value='3'>Marzo</option><option value='4'>Abril</option><option value='5'>Mayo</option><option value='6'>Junio</option><option value='7'>Julio</option><option value='8'>Agosto</option><option value='9'>Septiembre</option><option value='10'>Octubre</option><option value='11'>Noviembre</option><option value='12'>Diciembre</option></select></td>
					<td><select id='estatus_costo".$auxiliar."' name='estatus_costo".$auxiliar."' class='form-control' onchange='getCloneCosto(this);'><option value='1'>Pagado</option><option selected value='2'>Por Pagar</option></select></td>
					<td><select id='mes_factura".$auxiliar."' name='mes_factura".$auxiliar."' class='form-control facturar".$auxiliar."'><option value='1'>Enero</option><option value='2'>Febrero</option><option value='3'>Marzo</option><option value='4'>Abril</option><option value='5'>Mayo</option><option value='6'>Junio</option><option value='7'>Julio</option><option value='8'>Agosto</option><option value='9'>Septiembre</option><option value='10'>Octubre</option><option value='11'>Noviembre</option><option value='12'>Diciembre</option></select></td>
					<td><select id='estatus_factura".$auxiliar."' name='estatus_factura".$auxiliar."' class='form-control' onchange='getCloneFacturacion(this);'><option value='1'>Facturado</option><option selected value='2'>Por Facturar</option></select></td>
					<td><input id='factura".$auxiliar."' name='factura".$auxiliar."' type='text' class='form-control' reaonly></td>
					<td><select id='estatus_cobranza".$auxiliar."' name='estatus_cobranza".$auxiliar."' class='form-control' onchange='getCloneCobranza(this);'><option value='1'>Cobrado</option><option selected value='2'>Por Cobrar</option><option value='3'>Ingresada a plataforma</option><option value='4'>Monitoreo</option></select></td>
					<td><input id='fecha_cobranza_".$auxiliar."' name='fecha_cobranza_".$auxiliar."' type='text' class='form-control'></td>
					<td><select id='mes_cobranza".$auxiliar."' name='mes_cobranza".$auxiliar."' class='form-control cobrar".$auxiliar."'><option value='1'>Enero</option><option value='2'>Febrero</option><option value='3'>Marzo</option><option value='4'>Abril</option><option value='5'>Mayo</option><option value='6'>Junio</option><option value='7'>Julio</option><option value='8'>Agosto</option><option value='9'>Septiembre</option><option value='10'>Octubre</option><option value='11'>Noviembre</option><option value='12'>Diciembre</option></select></td>
					<td><input type='checkbox' class='form-control' name='viaticos".$auxiliar."' id='".$auxiliar."' value='0' onclick='getReadonly(this);'></td>
					</tr>
					<tr>
					<td><input id='evento".$auxiliar."' name='evento".$auxiliar."' type='text' class='form-control' value='".$row['ordenador']."' readonly='readonly'></td>
					<td><input id='id_evento".$auxiliar."' name='id_evento".$auxiliar."' type='text' class='form-control' value='".$auxiliar.$row['tema']."' readonly='readonly'></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><select id='nivel".$auxiliar."' name='nivel".$auxiliar."' class='form-control' readonly='readonly'><option selected value='Junior'>Junior</option></select></td>
					<td><select id='subproducto".$auxiliar."' name='subproducto".$auxiliar."' class='form-control' readonly='readonly'><option selected value='Análisis de contenidos'>Mapa de contenido</option></select></td>
					<td><select id='subproducto".$auxiliar."' name='subproducto".$auxiliar."' class='form-control' readonly='readonly'><option selected value='Análisis de contenidos'>Diseñador Instruccional</option></select></td>
					<td><select id='subproducto".$auxiliar."' name='subproducto".$auxiliar."' class='form-control' readonly='readonly'><option selected value='Análisis de contenidos'>Diseñador 1</option></select></td>
					<td></td>
					<td><input id='honorarios".$auxiliar."' name='honorarios".$auxiliar."' type='text' class='form-control' value='875.00' readonly='readonly'></td>
					<td><input id='' name='' type='text' class='form-control' value='".(round(875/$row['precio_oi'],2)*100)."%' readonly='readonly'></td>
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
					<td><input id='fecha_realizacion_3_".$auxiliar."' name='fecha_realizacion_3_".$auxiliar."' type='text' class='form-control fecha_realizacion_3_ fecha_realizacion_3_".$auxiliar."' readonly=''></td>
					<td><select id='mes_ejecucion".$auxiliar."' name='mes_ejecucion".$auxiliar."' class='form-control ejecutar".$auxiliar."'><option value='1'>Enero</option><option value='2'>Febrero</option><option value='3'>Marzo</option><option value='4'>Abril</option><option value='5'>Mayo</option><option value='6'>Junio</option><option value='7'>Julio</option><option value='8'>Agosto</option><option value='9'>Septiembre</option><option value='10'>Octubre</option><option value='11'>Noviembre</option><option value='12'>Diciembre</option></select></td>
					<td><select id='estatus_ejecucion".$auxiliar."' name='estatus_ejecucion".$auxiliar."' class='form-control' onchange='getCloneEjecucion(this);'><option value='1'>Ejecutado</option><option selected value='2'>Por Ejecutar</option></select></td>
					<td><textarea rows='3' class='form-control textarea-autosize' id='anomalia' name='anomalia'></textarea></td>
					<td><select id='mes_costo".$auxiliar."' name='mes_costo".$auxiliar."' class='form-control costear".$auxiliar."'><option value='1'>Enero</option><option value='2'>Febrero</option><option value='3'>Marzo</option><option value='4'>Abril</option><option value='5'>Mayo</option><option value='6'>Junio</option><option value='7'>Julio</option><option value='8'>Agosto</option><option value='9'>Septiembre</option><option value='10'>Octubre</option><option value='11'>Noviembre</option><option value='12'>Diciembre</option></select></td>
					<td><select id='estatus_costo".$auxiliar."' name='estatus_costo".$auxiliar."' class='form-control' onchange='getCloneCosto(this);'><option value='1'>Pagado</option><option selected value='2'>Por Pagar</option></select></td>
					<td><select id='mes_factura".$auxiliar."' name='mes_factura".$auxiliar."' class='form-control facturar".$auxiliar."'><option value='1'>Enero</option><option value='2'>Febrero</option><option value='3'>Marzo</option><option value='4'>Abril</option><option value='5'>Mayo</option><option value='6'>Junio</option><option value='7'>Julio</option><option value='8'>Agosto</option><option value='9'>Septiembre</option><option value='10'>Octubre</option><option value='11'>Noviembre</option><option value='12'>Diciembre</option></select></td>
					<td><select id='estatus_factura".$auxiliar."' name='estatus_factura".$auxiliar."' class='form-control' onchange='getCloneFacturacion(this);'><option value='1'>Facturado</option><option selected value='2'>Por Facturar</option></select></td>
					<td><input id='factura".$auxiliar."' name='factura".$auxiliar."' type='text' class='form-control' reaonly></td>
					<td><select id='estatus_cobranza".$auxiliar."' name='estatus_cobranza".$auxiliar."' class='form-control' onchange='getCloneCobranza(this);'><option value='1'>Cobrado</option><option selected value='2'>Por Cobrar</option><option value='3'>Ingresada a plataforma</option><option value='4'>Monitoreo</option></select></td>
					<td><input id='fecha_cobranza_".$auxiliar."' name='fecha_cobranza_".$auxiliar."' type='text' class='form-control'></td>
					<td><select id='mes_cobranza".$auxiliar."' name='mes_cobranza".$auxiliar."' class='form-control cobrar".$auxiliar."'><option value='1'>Enero</option><option value='2'>Febrero</option><option value='3'>Marzo</option><option value='4'>Abril</option><option value='5'>Mayo</option><option value='6'>Junio</option><option value='7'>Julio</option><option value='8'>Agosto</option><option value='9'>Septiembre</option><option value='10'>Octubre</option><option value='11'>Noviembre</option><option value='12'>Diciembre</option></select></td>
					<td><input type='checkbox' class='form-control' name='viaticos".$auxiliar."' id='".$auxiliar."' value='0' onclick='getReadonly(this);'></td>
					</tr>";
				}
				$i++;
			}
			?>
			<input id="contador" name="contador" value="<?php print($contador); ?>" type="text" hidden/>
			<input id="total_agentes" name="total_agentes" value="<?php print($i); ?>" type="text" hidden/>
			<?php
		}
		//YA NO SE SE UTILIZA ESTA DIIVISIÓN DE LA TABLA
		/*public function showOIDetalle1($ordenes)
		{
			$stmt = $this->conn->prepare("SELECT * FROM  T_DetalleOI AS DO
				INNER JOIN T_Subproductos AS Sub
				ON DO.id_subproducto = Sub.id_subproducto
				INNER JOIN T_Productos AS Prod
				ON Sub.id_producto = Prod.id_producto
				INNER JOIN T_FamiliaProductos AS Fam
				ON Prod.id_familiaproducto = Fam.id_familiaproducto
				WHERE DO.id_oi = :ordenes");
			$stmt->execute(array(':ordenes'=>$ordenes));
			
			$contador = 1;
			
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				$cantidad = $row['cantidad_oi'];
				for($i=1;$i<=$cantidad;$i++)
				{
					$id_detalles = [];
					$id_detalles[$i] = $row['id_detalleoi'];
					$variable_detalle = 'modificar'.$i;
					$variable_eliminar = 'eliminar'.$i;
					$variable_familia = 'update_familia'.$i;
					$variable_producto = 'update_producto'.$i;
					$variable_subproducto = 'subproducto'.$i;
					$variable_tema = 'tema'.$i;
					$evento = $i.$row['tema'].'-';
			?>
			<tr id="tr" name="tr" class="">
				<td id="txt_evento">
					<input type="text" class="form-control" name="" id="" value="<?php print($evento);?>" readonly>
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
				<td id="txt_tema" colspan="">
					<input onkeyup="getSubproducto(this);" type="text" class="form-control" name="<?php print($variable_tema);?>" id="<?php print($variable_tema);?>" value="<?php print($row['tema']);?>" >
				</td>
			</tr>
			<?php
				$contador++;
				}
			}
		}
		public function showOIDetalle2($ordenes)
		{
			$stmt = $this->conn->prepare("SELECT * FROM  T_DetalleOI AS DO
				INNER JOIN T_Subproductos AS Sub
				ON DO.id_subproducto = Sub.id_subproducto
				INNER JOIN T_Productos AS Prod
				ON Sub.id_producto = Prod.id_producto
				INNER JOIN T_FamiliaProductos AS Fam
				ON Prod.id_familiaproducto = Fam.id_familiaproducto
				INNER JOIN T_Niveles AS N
				ON N.id_nivel = DO.id_nivel
				WHERE DO.id_oi = :ordenes");
			$stmt->execute(array(':ordenes'=>$ordenes));
			
			$contador = 1;
			
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				$cantidad = $row['cantidad_oi'];
				for($i=1;$i<=$cantidad;$i++)
				{
					$variable_evento = 'evento'.$i;
					$variable_id_evento = 'id_evento'.$i;
					$variable_personas = 'personas'.$i;
					$variable_nivel = 'nivel'.$i;
					$evento = $i.$row['tema'].'-';
			?>
			<tr class="">
				<td id="txt_evento">
					<input type="text" class="form-control" name="<?php print($variable_evento);?>" id="<?php print($variable_evento);?>" value="<?php print($i);?>" readonly>
				</td>
				<td id="txt_id_evento" colspan="5">
					<input type="text" class="form-control" name="<?php print($variable_id_evento);?>" id="<?php print($variable_id_evento);?>" value="<?php print($evento); ?>">
				</td>
				<td id="txt_personas">
					<input type="text" class="form-control" name="<?php print($variable_personas);?>" id="<?php print($variable_personas);?>" value="<?php print($row['personas']);?>">
				</td>
				<td id="txt_nivel" colspan="5">
					<select name="<?php print($variable_nivel);?>" id="<?php print($variable_nivel);?>" class="form-control">
						<option value="<?php print($row['id_nivel']);?>" selected><?php print($row['nivel']);?></option>
						<option value="1">Operativo</option>
						<option value="2">Mando medio</option>
						<option value="3">Directivo</option>
						<option value="4">Otro</option>
					</select>
				</td>
			</tr>
			<?php
				}
				$contador++;
			}
		}
		public function showOIDetalle3($ordenes)
		{
			$stmt = $this->conn->prepare("SELECT * FROM  T_DetalleOI AS DO
				INNER JOIN T_Subproductos AS Sub
				ON DO.id_subproducto = Sub.id_subproducto
				INNER JOIN T_Productos AS Prod
				ON Sub.id_producto = Prod.id_producto
				INNER JOIN T_FamiliaProductos AS Fam
				ON Prod.id_familiaproducto = Fam.id_familiaproducto
				WHERE DO.id_oi = :ordenes");
			$stmt->execute(array(':ordenes'=>$ordenes));
			
			$contador = 1;
			
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{												
				$cantidad = $row['cantidad_oi'];
				for($i=1;$i<=$cantidad;$i++)
				{
					$id_detalles = [];
					$id_detalles[$i] = $row['id_detalleoi'];
					$variable_detalle = 'modificar'.$i;
					$variable_eliminar = 'eliminar'.$i;
					$variable_tipoagente = 'tipoagente'.$i;
					$variable_agente = 'agente'.$i;
					$variable_equipo = 'equipo'.$i;
					$variable_material = 'material'.$i;
					$evento = $i.$row['tema'].'-';
			?>
			<tr class="">
				<td id="txt_evento">
					<input type="text" class="form-control" name="" id="" value="<?php print($evento);?>" readonly>
				</td>
				<td id="txt_tipoagente" colspan="5">
					<select id="<?php print($variable_tipoagente);?>" name="<?php print($variable_tipoagente);?>" class="form-control" readonly>
						<option value="1" selected></option>
					</select>
				</td>
				<td id="txt_agente" colspan="5">
					<select id="<?php print($variable_agente);?>" name="<?php print($variable_agente);?>" class="form-control" readonly>
						<option value="1" selected></option>
					</select>
				</td>
				<td id="txt_equipo" colspan="5">
					<select name="<?php print($variable_equipo);?>" id="<?php print($variable_equipo);?>" class="form-control">
						<option value="1" selected>Estándar</option>
						<option value="2">Adicional</option>
					</select>
				</td>
				<td id="txt_material" colspan="5">
					<select name="<?php print($variable_material);?>" id="<?php print($variable_material);?>" class="form-control">
						<option value="1" selected>Estándar</option>
						<option value="2">Adicional</option>
					</select>
				</td>
			</tr>
			<?php
				}
				$contador++;
			}
		}
		public function showOIDetalle4($ordenes)
		{
			$stmt = $this->conn->prepare("SELECT * FROM  T_DetalleOI AS DO
				INNER JOIN T_Subproductos AS Sub
				ON DO.id_subproducto = Sub.id_subproducto
				INNER JOIN T_Productos AS Prod
				ON Sub.id_producto = Prod.id_producto
				INNER JOIN T_FamiliaProductos AS Fam
				ON Prod.id_familiaproducto = Fam.id_familiaproducto
				WHERE DO.id_oi = :ordenes");
			$stmt->execute(array(':ordenes'=>$ordenes));
			
			$contador = 1;
			
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				$cantidad = $row['cantidad_oi'];
				for($i=1;$i<=$cantidad;$i++)
				{
					$id_detalles = [];
					$id_detalles[$i] = $row['id_detalleoi'];
					$variable_detalle = 'modificar'.$i;
					$variable_eliminar = 'eliminar'.$i;
					$variable_precio = 'precio'.$i;
					$variable_descuento = 'descuento'.$i;
					$variable_costo = 'update_costo'.$i;
					$variable_honorarios = 'honorarios'.$i;
					$variable_costomateriales = 'costomateriales'.$i;
					$evento = $i.$row['tema'].'-';
			?>
			<tr class="">
				<td id="txt_evento">
					<input type="text" class="form-control" name="" id="" value="<?php print($evento);?>" readonly>
				</td>
				<td id="txt_precio" colspan="5">
					<input type="text" class="form-control" name="<?php print($variable_precio);?>" id="<?php print($variable_precio);?>" value="<?php print(number_format($row['precio_oi'],2));?>" readonly>
				</td>
				<td id="txt_descuento">	
					<input id="<?php print($variable_descuento);?>" name="<?php print($variable_descuento);?>" type="text" class="form-control" value="<?php print(round(1-$row['descuento'],2)*100) ;?>%" readonly>
				</td>
				<td id="txt_honorarios">
					<input type="text" class="form-control" name="<?php print($variable_honorarios);?>" id="<?php print($variable_honorarios);?>" value="<?php print(number_format($row['costo_honorarios'],2));?>" readonly>
				</td>
				<td id="txt_porcentajehonorarios">
					<input type="text" class="form-control" name="" id="" value="<?php print(round($row['costo_honorarios']/$row['precio_oi'],2)*100);?>%" readonly>
				</td>
				<td id="txt_costomateriales">
					<input type="text" class="form-control" name="<?php print($variable_costomateriales);?>" id="<?php print($variable_costomateriales);?>" value="<?php print(number_format($row['costo_materiales'],2));?>" readonly>
				</td>
				<td id="txt_porcentajemateriales">
					<input type="text" class="form-control" name="" id="" value="<?php print(round($row['costo_materiales']/$row['precio_oi'],2)*100);?>%" readonly>
				</td>
				<td id="update_txt_costo" colspan="5">
					<input type="text" class="form-control" name="<?php print($variable_costo);?>" id="<?php print($variable_costo);?>" value="<?php print(number_format($row['costo_bitacora'],2));?>" readonly>
				</td>
				<td id="txt_porcentajemateriales">
					<input type="text" class="form-control" name="" id="" value="<?php print(round($row['costo_bitacora']/$row['precio_oi'],2)*100);?>%" readonly>
				</td>
				<td id="txt_margen" colspan="5">
					<input type="text" class="form-control" name="" id="" value="<?php print(number_format($row['precio_oi']-$row['costo_bitacora'],2));?>" readonly>
				</td>
				<td id="txt_porcentajemargen">
					<input type="text" class="form-control" name="" id="" value="<?php print(round((($row['precio_oi']-$row['costo_bitacora'])/$row['precio_oi'])*100,2));?>%" readonly>
				</td>
			</tr>
			<?php
				}
				$contador++;
			}
		}
		public function showOIDetalle5($ordenes)
		{
			$stmt = $this->conn->prepare("SELECT * FROM  T_DetalleOI AS DO
				INNER JOIN T_Subproductos AS Sub
				ON DO.id_subproducto = Sub.id_subproducto
				INNER JOIN T_Productos AS Prod
				ON Sub.id_producto = Prod.id_producto
				INNER JOIN T_FamiliaProductos AS Fam
				ON Prod.id_familiaproducto = Fam.id_familiaproducto
				WHERE DO.id_oi = :ordenes");
			$stmt->execute(array(':ordenes'=>$ordenes));
			
			$contador = 1;
			
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				$cantidad = $row['cantidad_oi'];
				for($i=1;$i<=$cantidad;$i++)
				{
					$id_detalles = [];
					$id_detalles[$i] = $row['id_detalleoi'];
					$variable_detalle = 'modificar'.$i;
					$variable_eliminar = 'eliminar'.$i;
					$variable_fecha_realizacion = 'fecha_realizacion'.$i;
					$variable_mes_ejecucion = 'mes_ejecucion'.$i;
					$variable_estatus_ejecucion = 'estatus_ejecucion'.$i;
					$variable_anomalia = 'anomalia'.$i;
					$evento = $i.$row['tema'].'-';
			?>
			<tr class="">
				<td id="txt_evento">
					<input type="text" class="form-control" name="" id="" value="<?php print($evento);?>" readonly>
				</td>
				<td id="txt_fecharealizacion">
					<input placeholder="dd-mm-yyyy" type="text" class="form-control fecha_realizacion" name="<?php print($variable_fecha_realizacion);?>" value="" id="<?php print($variable_fecha_realizacion);?>">
				</td>
				<td id="txt_mes_ejecucion">
					<select name="<?php print($variable_mes_ejecucion);?>" id="<?php print($variable_mes_ejecucion);?>" class="form-control">
						<option value="1" selected>Enero</option>
						<option value="2">Febrero</option>
						<option value="3">Marzo</option>
						<option value="4">Abril</option>
						<option value="5">Mayo</option>
						<option value="6">Junio</option>
						<option value="7">Julio</option>
						<option selected value="8">Agosto</option>
						<option value="9">Septiembre</option>
						<option value="10">Octubre</option>
						<option value="11">Noviembre</option>
						<option value="12">Diciembre</option>
					</select>
				</td>
				<td id="txt_estatus_ejecucion">
					<select onchange="getMessage(this);" id="<?php print($variable_estatus_ejecucion);?>" name="<?php print($variable_estatus_ejecucion);?>" class="form-control">
						<option value="1">Ejecutado</option>
						<option value="2" selected>Por ejecutar</option>
					</select>
				</td>
				<td id="txt_anomalias">
					<textarea rows="3" class="form-control textarea-autosize" id="<?php print($variable_anomalia);?>" name="<?php print($variable_anomalia);?>"></textarea>
				</td>
			</tr>
			<?php
				}
				$contador++;
			}
		}
		public function showOIDetalle6($ordenes)
		{
			$stmt = $this->conn->prepare("SELECT * FROM  T_DetalleOI AS DO
				INNER JOIN T_Subproductos AS Sub
				ON DO.id_subproducto = Sub.id_subproducto
				INNER JOIN T_Productos AS Prod
				ON Sub.id_producto = Prod.id_producto
				INNER JOIN T_FamiliaProductos AS Fam
				ON Prod.id_familiaproducto = Fam.id_familiaproducto
				WHERE DO.id_oi = :ordenes");
			$stmt->execute(array(':ordenes'=>$ordenes));
			
			$contador = 1;
			
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{													
				$cantidad = $row['cantidad_oi'];
				for($i=1;$i<=$cantidad;$i++)
				{
					$id_detalles = [];
					$id_detalles[$i] = $row['id_detalleoi'];
					$variable_detalle = 'modificar'.$i;
					$variable_eliminar = 'eliminar'.$i;
					$variable_estatus_costo = 'estatus_costo'.$i;
					$variable_mes_costo = 'mes_costo'.$i;
					$evento = $i.$row['tema'].'-';
			?>
			<tr class="">
				<td id="txt_evento">
					<input type="text" class="form-control" name="" id="" value="<?php print($evento);?>" readonly>
				</td>
				<td id="txt_estatus_costo">
					<select id="<?php print($variable_estatus_costo);?>" name="<?php print($variable_estatus_costo);?>" class="form-control">
						<option value="1">Pagado</option>
						<option value="2" selected>Por pagar</option>
					</select>
				</td>
				<td id="txt_mes_costo">
					<select name="<?php print($variable_mes_costo);?>" id="<?php print($variable_mes_costo);?>" class="form-control">
						<option value="1">Enero</option>
						<option value="2">Febrero</option>
						<option value="3">Marzo</option>
						<option value="4">Abril</option>
						<option value="5">Mayo</option>
						<option value="6">Junio</option>
						<option value="7">Julio</option>
						<option value="8" selected>Agosto</option>
						<option value="9">Septiembre</option>
						<option value="10">Octubre</option>
						<option value="11">Noviembre</option>
						<option value="12">Diciembre</option>
					</select>
				</td>
			</tr>
			<?php
				}
				$contador++;
			}
		}		
		public function showOIDetalle7($ordenes)
		{
			$stmt = $this->conn->prepare("SELECT * FROM  T_DetalleOI AS DO
				INNER JOIN T_Subproductos AS Sub
				ON DO.id_subproducto = Sub.id_subproducto
				INNER JOIN T_Productos AS Prod
				ON Sub.id_producto = Prod.id_producto
				INNER JOIN T_FamiliaProductos AS Fam
				ON Prod.id_familiaproducto = Fam.id_familiaproducto
				WHERE DO.id_oi = :ordenes");
			$stmt->execute(array(':ordenes'=>$ordenes));
			
			$contador = 1;
			
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{												
				$cantidad = $row['cantidad_oi'];
				for($i=1;$i<=$cantidad;$i++)
				{
					$id_detalles = [];
					$id_detalles[$i] = $row['id_detalleoi'];
					$variable_detalle = 'modificar'.$i;
					$variable_eliminar = 'eliminar'.$i;
					$variable_estatus_factura = 'estatus_factura'.$i;
					$variable_mes_factura = 'mes_factura'.$i;
					$variable_factura = 'factura'.$i;
					$evento = $i.$row['tema'].'-';
			?>
			<tr class="">
				<td id="txt_evento">
					<input type="text" class="form-control" name="" id="" value="<?php print($evento);?>" readonly>
				</td>
				<td id="txt_estatus_factura">
					<select id="<?php print($variable_estatus_factura);?>" name="<?php print($variable_estatus_factura);?>" class="form-control">
						<option value="1">Facturado</option>
						<option value="2" selected>Por facturar</option>
					</select>
				</td>
				<td id="txt_mes_factura">
					<select name="<?php print($variable_mes_factura);?>" id="<?php print($variable_mes_factura);?>" class="form-control">
						<option value="1" selected>Enero</option>
						<option value="2">Febrero</option>
						<option value="3">Marzo</option>
						<option value="4">Abril</option>
						<option value="5">Mayo</option>
						<option value="6">Junio</option>
						<option value="7">Julio</option>
						<option value="8">Agosto</option>
						<option value="9" selected>Septiembre</option>
						<option value="10">Octubre</option>
						<option value="11">Noviembre</option>
						<option value="12">Diciembre</option>
					</select>
				</td>
				<td id="txt_factura">
					<input type="text" class="form-control" name="<?php print($variable_factura);?>" value="" id="<?php print($variable_factura);?>" readonly>
				</td>
			</tr>
			<?php
				}
				$contador++;
			}
		}
		public function showOIDetalle8($ordenes)
		{
			$stmt = $this->conn->prepare("SELECT * FROM  T_DetalleOI AS DO
				INNER JOIN T_Subproductos AS Sub
				ON DO.id_subproducto = Sub.id_subproducto
				INNER JOIN T_Productos AS Prod
				ON Sub.id_producto = Prod.id_producto
				INNER JOIN T_FamiliaProductos AS Fam
				ON Prod.id_familiaproducto = Fam.id_familiaproducto
				WHERE DO.id_oi = :ordenes");
			$stmt->execute(array(':ordenes'=>$ordenes));
			
			$contador = 1;
			
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{													
				$cantidad = $row['cantidad_oi'];
				for($i=1;$i<=$cantidad;$i++)
				{
					$id_detalles = [];
					$id_detalles[$i] = $row['id_detalleoi'];
					$variable_detalle = 'modificar'.$i;
					$variable_eliminar = 'eliminar'.$i;
					$variable_estatus_cobranza = 'estatus_cobranza'.$i;
					$variable_mes_cobranza = 'mes_cobranza'.$i;
					$evento = $i.$row['tema'].'-';
			?>
			<tr class="">
				<td id="txt_evento">
					<input type="text" class="form-control" name="" id="" value="<?php print($evento);?>" readonly>
				</td>
				<td id="txt_estatus_cobranza">
					<select id="<?php print($variable_estatus_cobranza);?>" name="<?php print($variable_estatus_cobranza);?>" class="form-control">
						<option value="1">Cobrado</option>
						<option value="2" selected>Por cobrar</option>
						<option value="3">Ingresada a plataforma</option>
						<option value="4">Monitoreo</option>
					</select>
				</td>
				<td id="txt_mes_cobranza">
					<select name="<?php print($variable_mes_cobranza);?>" id="<?php print($variable_mes_cobranza);?>" class="form-control">
						<option value="1" selected>Enero</option>
						<option value="2">Febrero</option>
						<option value="3">Marzo</option>
						<option value="4">Abril</option>
						<option value="5">Mayo</option>
						<option value="6">Junio</option>
						<option value="7">Julio</option>
						<option value="8">Agosto</option>
						<option value="9" selected>Septiembre</option>
						<option value="10">Octubre</option>
						<option value="11">Noviembre</option>
						<option value="12">Diciembre</option>
					</select>
				</td>
			</tr>
			<?php
				}
				$contador++;
			}
			?>
			<input id="contador" name="contador" value="<?php print($cantidad);?>" type="text" hidden />
			<?php
		}*/
		//COMPLETAR BITACORA
		public function showBCompletar($ordenes)
		{
			$stmt2 = $this->conn->prepare("SELECT * FROM T_Bitacora AS B
				INNER JOIN T_Clientes AS C
				ON B.id_cliente = C.id_cliente
				INNER JOIN T_TipoAnticipos AS TA
				ON TA.id_adminanticipos = B.id_adminanticipos
				WHERE B.id_oi = :ordenes");
			$stmt2->execute(array(':ordenes'=>$ordenes));
			$row2=$stmt2->fetch(PDO::FETCH_ASSOC);
			return $row2;
		}
		public function showOIDetalleCompletar($ordenes,$bitacora)
		{
			$query = "SELECT D.id_detalleoi
					FROM T_DetalleOI AS D
					WHERE D.id_oi = :ordenes
					AND D.id_estatusoi = 1
					AND (D.id_detalleoi NOT 
					IN (
					SELECT B.id_detalleoi
					FROM T_Detalle_Bitacora AS B
					WHERE B.id_bitacora = :bitacora
					))";
			$stmt4 = $this->conn->prepare($query);
			$stmt4->bindparam(":ordenes",$ordenes);
			$stmt4->bindparam(":bitacora",$bitacora);
			$stmt4->execute();
			if($stmt4->rowCount()>0)
			{
				$contador = 1;
				$auxiliar = 1;
				$cantidad = 0;
				while($row4=$stmt4->fetch(PDO::FETCH_ASSOC))
				{
					$id_aux = $row4['id_detalleoi'];
					$query = "SELECT 
					DO.id_detalleoi,
					Fam.familia_producto,
					Fam.id_familiaproducto,
					Prod.id_producto,
					Prod.nombre_producto,
					DO.id_subproducto,
					Sub.nombre_subproducto,
					DO.tema,
					DO.personas,
					DO.id_nivel,
					N.nivel,
					DO.precio_oi,
					DO.descuento,
					DO.costo_oi,
					(CASE WHEN DO.precio_oi = 0 THEN 0 ELSE DO.costo_oi/DO.precio_oi END) AS porcentajeCosto,
					(CASE WHEN DO.precio_oi = 0 THEN 0 ELSE (DO.precio_oi-DO.costo_oi)/DO.precio_oi END) AS porcentajeMargen,
					Sub.costo_honorarios,
					Sub.costo_materiales,
					(CASE WHEN DO.precio_oi = 0 THEN 0 ELSE Sub.costo_honorarios/DO.precio_oi END) AS porcentajeHonorarios,
					(CASE WHEN DO.precio_oi = 0 THEN 0 ELSE Sub.costo_materiales/DO.precio_oi END) AS porcentajeMateriales,
					DO.cantidad_oi
					FROM  T_DetalleOI AS DO
					INNER JOIN T_Subproductos AS Sub
					ON DO.id_subproducto = Sub.id_subproducto
					INNER JOIN T_Productos AS Prod
					ON Sub.id_producto = Prod.id_producto
					INNER JOIN T_FamiliaProductos AS Fam
					ON Prod.id_familiaproducto = Fam.id_familiaproducto
					INNER JOIN T_Niveles AS N
					ON N.id_nivel = DO.id_nivel
					WHERE DO.id_detalleoi = :id_aux AND DO.id_estatusoi = 1
					ORDER BY DO.id_detalleoi ASC";
					$stmt = $this->conn->prepare($query);
					$stmt->bindparam(":id_aux",$id_aux);
					$stmt->execute();
					
					
					$query = "SELECT SUM(DO.cantidad_oi) AS CantidadTotal
						FROM  T_DetalleOI AS DO
						INNER JOIN T_Subproductos AS Sub
						ON DO.id_subproducto = Sub.id_subproducto
						INNER JOIN T_Productos AS Prod
						ON Sub.id_producto = Prod.id_producto
						INNER JOIN T_FamiliaProductos AS Fam
						ON Prod.id_familiaproducto = Fam.id_familiaproducto
						INNER JOIN T_Niveles AS N
						ON N.id_nivel = DO.id_nivel
						WHERE DO.id_detalleoi = :id_aux AND DO.id_estatusoi = 1";
					$stmt2 = $this->conn->prepare($query);
					$stmt2->bindparam(":id_aux",$id_aux);
					$stmt2->execute();
					$row2=$stmt2->fetch(PDO::FETCH_ASSOC);
					
					while($row=$stmt->fetch(PDO::FETCH_ASSOC))
					{
						$subproducto = $row['id_subproducto'];
						$query = "SELECT 
									id_tipoagente,
									costo_tipoagente_subproducto
								FROM T_Subproducto_TipoAgente
								WHERE id_subproducto = :subproducto";
						$stmt3 = $this->conn->prepare($query);
						$stmt3->bindparam(":subproducto",$subproducto);
						$stmt3->execute();
						if($stmt3->rowCount() == 0)
						{
							$row3 = array();
							$row3['id_tipoagente'] = 0;
							$row3['costo_tipoagente_subproducto'] = 0;
							$styleHidden = "display:none;";
							$comprobacion = FALSE;
							$totalAgentes = 0;
						}
						elseif($stmt3->rowCount() == 1)
						{
							$row3=$stmt3->fetch(PDO::FETCH_ASSOC);
							$styleHidden = "display:none;";
							$comprobacion = FALSE;
							$totalAgentes = 1;
						}
						else
						{
							$arrayAux = array();
							$arrayAux2 = array();
							$x=0;
							while($row3=$stmt3->fetch(PDO::FETCH_ASSOC))
							{
								$arrayAux[$x] = $row3['id_tipoagente'];
								$arrayAux2[$x] = $row3['costo_tipoagente_subproducto'];
								$x++;
							}
							$row3['id_tipoagente'] = $arrayAux[0];
							$row3['costo_tipoagente_subproducto'] = $arrayAux2[0];
							$styleHidden = "";
							$comprobacion = TRUE;
							$totalAgentes = $x;
						}
						//SELECT SUBTEMAS
						$subtema = array();
						$temaAux = $row['tema'];
						if($row['tema'] == "Visión de negocio")
						{
							$subtema[1] = "La gestión y el rol del mando";
							$subtema[2] = "Responsabilidad";
							$subtema[3] = "Pensamiento estratégico";
							$subtema[4] = "Oferta de valor y ventaja competitiva";
						}
						elseif($row['tema'] == "Orientación a resultados")
						{
							$subtema[1] = "Definición de actividades";
							$subtema[2] = "Supervisión y control";
							$subtema[3] = "Definición de objetivos, indicadores y metas";
							$subtema[4] = "Distribución de actividades y cargas de trabajo";
						}
						elseif($row['tema'] == "Comunicación interpersonal")
						{
							$subtema[1] = "Escucha";
							$subtema[2] = "Generación de acuerdos";
							$subtema[3] = "Seguimiento a acuerdos y retroalimentación";
							$subtema[4] = "Elaboración de preguntas";
						}
						elseif($row['tema'] == "Liderazgo")
						{
							$subtema[1] = "Adaptación al colaborador";
							$subtema[2] = "Desarrollo del colaborador";
							$subtema[3] = "Visión compartida";
							$subtema[4] = "Facultamiento";
						}
						elseif($row['tema'] == "Sinergia Organizacional")
						{
							$subtema[1] = "Trabajo en equipo";
							$subtema[2] = "Formación de equipos de alto desempeño";
							$subtema[3] = "Interequipos";
							$subtema[4] = "Sociedad estratégica de líderes";
						}
						elseif($row['tema'] == "Análisis de problemas y toma de decisiones")
						{
							$subtema[1] = "Análisis de causas";
							$subtema[2] = "Aseguramiento de la resolución";
							$subtema[3] = "Establecimiento de brechas";
							$subtema[4] = "Aseguramiento de la resolución";
						}
						elseif($row['tema'] == "Administración de microproyectos")
						{
							$subtema[1] = "Planeación de un proyecto";
							$subtema[2] = "Ejecución y cierre";
							$subtema[3] = "Definición e inicio de un proyecto";
							$subtema[4] = "Control y cierre";
						}
						elseif($row['tema'] == "Inteligencia emocional")
						{
							$subtema[1] = "Consciencia de si";
							$subtema[2] = "Autoconocimiento";
							$subtema[3] = "Autorregulación";
							$subtema[4] = "Sinergía emocional";
						}
						elseif($row['tema'] == "Inteligencia social")
						{
							$subtema[1] = "Detener un conflicto en crecimiento";
							$subtema[2] = "Cerrar un conflicto";
							$subtema[3] = "Empatía y asertividad";
							$subtema[4] = "Aprovechamiento del conflicto";
						}
						else
						{
						     $subtema[1] = "";
						     $subtema[2] = "";
						     $subtema[3] = "";
						     $subtema[4] = "";
						}
						
						//PRODUCTO FDP
						if($row['id_producto'] == 40)
						{
							$cantidad = $row['cantidad_oi']*4;
							$row['precio_oi'] = $row['precio_oi']/4;
							$row['costo_honorarios'] = $row['costo_honorarios']/4;
							$row3['costo_tipoagente_subproducto'] = $row3['costo_tipoagente_subproducto']/4;
							$row['costo_materiales'] = $row['costo_materiales']/4;
							$row['costo_oi'] = $row['costo_oi']/4;
						}
						elseif($row['id_subproducto'] == 129)
						{
							$cantidad = $row['cantidad_oi']*4;
							$row['precio_oi'] = $row['precio_oi'];
							$row['costo_honorarios'] = $row['costo_honorarios'];
							$row3['costo_tipoagente_subproducto'] = $row3['costo_tipoagente_subproducto'];
							$row['costo_materiales'] = $row['costo_materiales'];
							$row['costo_oi'] = $row['costo_oi'];
						}
						elseif($row['id_producto'] == 41)
						{
							$cantidad = $row['cantidad_oi']*3;
							$row['precio_oi'] = $row['precio_oi']/3;
							$row['costo_honorarios'] = $row['costo_honorarios']/3;
							$row3['costo_tipoagente_subproducto'] = $row3['costo_tipoagente_subproducto']/3;
							$row['costo_materiales'] = $row['costo_materiales']/3;
							$row['costo_oi'] = $row['costo_oi']/3;
						}
						elseif($row['id_producto'] == 42)
						{
							$cantidad = $row['cantidad_oi']*2;
							$row['precio_oi'] = $row['precio_oi']/2;
							$row['costo_honorarios'] = $row['costo_honorarios']/2;
							$row3['costo_tipoagente_subproducto'] = $row3['costo_tipoagente_subproducto']/2;
							$row['costo_materiales'] = $row['costo_materiales']/2;
							$row['costo_oi'] = $row['costo_oi']/2;
						}
						else
						{
							$cantidad = $row['cantidad_oi'];
						}
						for($i=1;$i<=$cantidad;$i++)
						{	
							if($row['id_producto'] == 40)
							{
								$cantidad_aux = $cantidad / 4;
								$cantidad_aux2 = $i % 4;
								if($cantidad_aux2 == 3)
								{
									$row['tema'] = $temaAux.' - '.$subtema[3];
								}
								elseif($cantidad_aux2 == 0)
								{
									$row['tema'] = $temaAux.' - '.$subtema[4];
								}
								elseif($cantidad_aux2 == 1)
								{
									$row['tema'] = $temaAux.' - '.$subtema[1];
								}
								elseif($cantidad_aux2 == 2)
								{
									$row['tema'] = $temaAux.' - '.$subtema[2];
								}
							}
							elseif($row['id_subproducto'] == 129)
					{
						$cantidad_aux = $cantidad / 4;
						$cantidad_aux2 = $i % 4;
						if($cantidad_aux2 == 1)
						{
							$row['precio_oi'] = $row['precio_oi'];
						}
						else
						{
							$row['precio_oi'] = 0;
							$row['porcentajeCosto'] = 0;
							$row['costo_materiales'] = 0;
							$row['porcentajeMargen'] = 0;
						}
					} 
					elseif($row['id_producto'] == 41)
							{
								$cantidad_aux = $cantidad / 3;
								$cantidad_aux2 = $i % 3;
								if($cantidad_aux2 == 0)
								{
									$row['tema'] = $temaAux.' - '.$subtema[3];
								}
								elseif($cantidad_aux2 == 1)
								{
									$row['tema'] = $temaAux.' - '.$subtema[1];
								}
								elseif($cantidad_aux2 == 2)
								{
									$row['tema'] = $temaAux.' - '.$subtema[2];
								}
								
							}
							elseif($row['id_producto'] == 42)
							{
								$cantidad_aux = $cantidad / 2;
								$cantidad_aux2 = $i % 2;
								if($cantidad_aux2 == 1)
								{
									$row['tema'] = $temaAux.' - '.$subtema[1];
								}
								elseif($cantidad_aux2 == 0)
								{
									$row['tema'] = $temaAux.' - '.$subtema[2];
								}
							}
							
							$variable_familia = 'familia'.$auxiliar;
							$variable_producto = 'producto'.$auxiliar;
							$variable_subproducto = 'subproducto'.$auxiliar;
							$variable_tema = 'tema'.$auxiliar;
							$variable_evento = 'evento'.$auxiliar;
							$variable_id_evento = 'id_evento'.$auxiliar;
							$variable_personas = 'personas'.$auxiliar;
							$variable_nivel = 'nivel'.$auxiliar;
							$evento = $i.$row['tema'].'-';
							$variable_tipoagente = 'tipoagente0_'.$auxiliar;
							$variable_agente = 'agente0_'.$auxiliar;
							$variable_equipo = 'equipo'.$auxiliar;
							$variable_material = 'material'.$auxiliar;
							$variable_precio = 'precio'.$auxiliar;
							$variable_descuento = 'descuento'.$auxiliar;
							$variable_costo = 'costo'.$auxiliar;
							$variable_porcentajecosto = 'porcentajecosto'.$auxiliar;
							$variable_porcentajehonorarios_aux = 'porcentajehonorarios0_'.$auxiliar;
							$variable_honorarios = 'honorarios'.$auxiliar;
							$variable_honorarios_aux = 'honorarios0_'.$auxiliar;
							$variable_costomateriales = 'costomateriales'.$auxiliar;
							$variable_costosadicionales = 'costosadicionales'.$auxiliar;
							$variable_name_radio_button_costomaterial = 'radio-costomaterial'.$auxiliar;
							$variable_name_radio_button_costomaterial1 = 'radio-costomaterial_1_'.$auxiliar;
							$variable_name_radio_button_costomaterial2 = 'radio-costomaterial_2_'.$auxiliar;
							$variable_porcentajecostosadicionales = 'porcentajecostosadicionales'.$auxiliar;
							$variable_sede = 'sede'.$auxiliar;
							$variable_sede_particular = 'sede_particular'.$auxiliar;
							$variable_fecha_cobranza = 'fecha_cobranza_'.$auxiliar;
							$variable_name_radio_button_costomaterial = 'radio-costomaterial'.$auxiliar;
							$variable_name_radio_button_costomaterial1 = 'radio-costomaterial_1_'.$auxiliar;
							$variable_name_radio_button_costomaterial2 = 'radio-costomaterial_2_'.$auxiliar;
							$variable_fecha_realizacion1 = 'fecha_realizacion_1_'.$auxiliar;
							$variable_fecha_realizacion2 = 'fecha_realizacion_2_'.$auxiliar;
							$variable_fecha_realizacion_horario2 = 'fecha_realizacion_2_horario'.$auxiliar;
							$variable_fecha_realizacion3 = 'fecha_realizacion_3_'.$auxiliar;
							$variable_name_radio_button_costomaterial = 'radio-costomaterial'.$auxiliar;
							$variable_name_radio_button_costomaterial1 = 'radio-costomaterial_1_'.$auxiliar;
							$variable_name_radio_button_costomaterial2 = 'radio-costomaterial_2_'.$auxiliar;
							$variable_class_fecha_realizacion1 = 'fecha_realizacion_1_'.$auxiliar;
							$variable_class_fecha_realizacion2 = 'fecha_realizacion_2_'.$auxiliar;
							$variable_class_fecha_realizacion3 = 'fecha_realizacion_3_'.$auxiliar;
							$variable_name_radio_button = 'radio-fecharealizacion'.$auxiliar;
							$variable_radio_button1 = 'radio-fecharealizacion_1_'.$auxiliar;
							$variable_radio_button2 = 'radio-fecharealizacion_2_'.$auxiliar;
							$variable_radio_button3 = 'radio-fecharealizacion_3_'.$auxiliar;
							$variable_mes_ejecucion = 'mes_ejecucion'.$auxiliar;
							$variable_estatus_ejecucion = 'estatus_ejecucion'.$auxiliar;
							$variable_anomalia = 'anomalia'.$auxiliar;
							$variable_estatus_costo = 'estatus_costo'.$auxiliar;
							$variable_mes_costo = 'mes_costo'.$auxiliar;
							$variable_estatus_factura = 'estatus_factura'.$auxiliar;
							$variable_mes_factura = 'mes_factura'.$auxiliar;
							$variable_factura = 'factura'.$auxiliar;
							$variable_estatus_cobranza = 'estatus_cobranza'.$auxiliar;
							$variable_mes_cobranza = 'mes_cobranza'.$auxiliar;
							$variable_ejecutar_class = 'ejecutar'.$auxiliar;
							$variable_costear_class = 'costear'.$auxiliar;
							$variable_facturar_class = 'facturar'.$auxiliar;
							$variable_cobrar_class = 'cobrar'.$auxiliar;
							$variable_viaticos = 'viaticos'.$auxiliar;
							$variable_transportacion = 'transportacion'.$auxiliar;
							$variable_alimentos = 'alimentos'.$auxiliar;
							$variable_hospedaje = 'hospedaje'.$auxiliar;
							$variable_nodeducible = 'nodeducible'.$auxiliar;
							$variable_suma_viaticos = 'suma_viaticos'.$auxiliar;
							$variable_iva = 'iva'.$auxiliar;
							$variable_total_sinavion = 'total_sinavion'.$auxiliar;
							$variable_desglose = 'desglose'.$auxiliar;
							$variable_avionsiniva = 'avionsiniva'.$auxiliar;
							$variable_ivaavion = 'ivaavion'.$auxiliar;
							$variable_total_avion = 'total_avion'.$auxiliar;
							$variable_anticipo_viaticos = 'anticipo_viaticos'.$auxiliar;
							$variable_rembloso = 'rembolso'.$auxiliar;
							$variable_total_cobrar = 'total_cobrar'.$auxiliar;
							$variable_observaciones_viaticos = 'observaciones_viaticos'.$auxiliar;
							$variable_gasto_admin = 'gasto_admin'.$auxiliar;
							$variable_fecha_entrega_viaticos = 'fecha_entrega_viaticos'.$auxiliar;
							$variable_total_agentes = 'total_agentes'.$auxiliar;
							$variable_auxiliar_tipoFDP = 'auxiliar_tipoFDP'.$auxiliar;
							$variable_auxiliar_tipoFDP_cantidad = 'auxiliar_tipoFDP_cantidad'.$auxiliar;
							$variable_estatus_ejecucion_viaticos = 'estatus_ejecucion_viaticos'.$auxiliar;
							$variable_estatus_factura_vitaticos = 'estatus_factura_viaticos'.$auxiliar;
							$variable_estatus_cobranza_viaticos = 'estatus_cobranza_viaticos'.$auxiliar;
							$variable_estatus_costo_viaticos = 'estatus_costo_viaticos'.$auxiliar;
							$variable_detalleoi = 'detalleoi'.$auxiliar;
							if($row['id_producto'] == 40 || $row['id_producto'] == 41 || $row['id_producto'] == 42)
							{
								$variable_auxiliar_tipoFDP_valor = 1;
							}
							else
							{
								$variable_auxiliar_tipoFDP_valor = 0;
							}
					?>
					<!--SCRIPT INICIALIZA DATEPICKERS-->
					<script type="text/javascript">
						$(function() {
							$('input[type=radio][name=<?php print($variable_name_radio_button);?>]').change(function() {
								$('.<?php print($variable_class_fecha_realizacion1);?>').val('');
								$('.<?php print($variable_class_fecha_realizacion2);?>').val('');
								$('.<?php print($variable_class_fecha_realizacion3);?>').val('');
								if (this.value == 1) 
								{
									//PERIODO
									$('.<?php print($variable_class_fecha_realizacion1);?>').show();
									$('.<?php print($variable_class_fecha_realizacion2);?>').hide();
									$('.<?php print($variable_class_fecha_realizacion3);?>').hide();
									/*$('.<?php print($variable_class_fecha_realizacion1);?>').prop('required','true');
											$('.<?php print($variable_class_fecha_realizacion2);?>').prop('required','false');
											$('.<?php print($variable_class_fecha_realizacion3);?>').prop('required','false');*/
								}
								else if (this.value == 2) 
								{
									//UNICA FECHA
									$('.<?php print($variable_class_fecha_realizacion1);?>').hide();
									$('.<?php print($variable_class_fecha_realizacion2);?>').show();
									$('.<?php print($variable_class_fecha_realizacion3);?>').hide();
									/*$('.<?php print($variable_class_fecha_realizacion1);?>').prop('required','false');
											$('.<?php print($variable_class_fecha_realizacion2);?>').prop('required','true');
											$('.<?php print($variable_class_fecha_realizacion3);?>').prop('required','false');*/
								}
								else
								{
									//VARIAS FECHAS
									$('.<?php print($variable_class_fecha_realizacion1);?>').hide();
									$('.<?php print($variable_class_fecha_realizacion2);?>').hide();
									$('.<?php print($variable_class_fecha_realizacion3);?>').show();
									/*$('.<?php print($variable_class_fecha_realizacion1);?>').prop('required','false');
											$('.<?php print($variable_class_fecha_realizacion2);?>').prop('required','false');
											$('.<?php print($variable_class_fecha_realizacion3);?>').prop('required','true');*/
								}
							});
							$('.<?php print($variable_class_fecha_realizacion1);?>').daterangepicker({
									startDate: moment(),
									endDate: moment(),
									minDate: '01/01/2010',
									maxDate: '12/31/2999',
									showDropdowns: true,
									showWeekNumbers: true,
									timePicker: true,
									timePickerIncrement: 1,
									timePicker24Hour: true,
									opens: 'left',
									buttonClasses: ['btn btn-default'],
									applyClass: 'small bg-green',
									cancelClass: 'small ui-state-default',
									format: 'DD-MM-YYYY HH:mm:ss',
									separator: ' a ',
									locale: {
										applyLabel: 'Aplicar',
										fromLabel: 'Desde',
										toLabel: 'Hasta',
										daysOfWeek: ['Do', 'Lu', 'Mar', 'Mie', 'Jue', 'Vi', 'Sa'],
										monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
										firstDay: 1
									}
								},
								function(start, end) 
								{
									console.log("Callback has been called!");
									$('.<?php print($variable_class_fecha_realizacion1);?>').val(start.format('DD-MM-YYYY HH:mm:ss') + ' - ' + end.format('DD-MM-YYYY HH:mm:ss'));
									var date = end.format('DD-MM-YYYY');
									var nextDate = new Date(start);
									nextDate.setDate(nextDate.getDate() - 7);
									var dd = nextDate.getDate();
									var mm = nextDate.getMonth()+1;
									var y = nextDate.getFullYear();
									var someFormattedDate = y + '-' + mm + '-' + dd;
									$('#<?php print($variable_fecha_entrega_viaticos); ?>').val(someFormattedDate);
									var day = date.substring(0,2);
									day = parseInt(day);
									var month = date.substring(3,5);
									month = parseInt(month);
									$('.<?php print($variable_ejecutar_class);?>').val(month);
									if(month == 12)
							{
							   month=0;
							}
									$('.<?php print($variable_costear_class);?>').val(month+1);
									$('.<?php print($variable_facturar_class);?>').val(month+1);
									
									//COPIAR TODAS LAS SEDES
									var id = $('#<?php print($variable_fecha_realizacion1);?>').attr('id');
									id = id.replace("fecha_realizacion_1_","");
									var sede = $('#sede'+id).val();
									var fecha = $('#fecha_realizacion_1_'+id).val();
									var producto = $('#producto'+id).val();
									if(producto == 40 || producto == 41 || producto == 42)
									{
										var cantidad = $('#auxiliar_tipoFDP_cantidad'+id).val();
										var tema = $('#tema'+id).val();
										var idEvento = parseInt($('#evento'+id).val());
										var cantidad_aux = 0;
										var cantidad_aux2 = 0;
										var aux1 = 0;
										var aux2 = 0;
										if(producto == 40)
										{
											cantidad_aux = cantidad / 4;
											cantidad_aux2 = idEvento % 4;
											if(cantidad_aux2 == 1)
											{
												aux1 = parseInt(id);
												aux2 = parseInt(id)+3;
											}
											else if(cantidad_aux2 == 2)
											{
												aux1 = parseInt(id)-1;
												aux2 = parseInt(id)+2;
											}
											else if(cantidad_aux2 == 3)
											{
												aux1 = parseInt(id)-2;
												aux2 = parseInt(id)+1;
											}
											else
											{
												aux1 = parseInt(id)-3;
												aux2 = parseInt(id);
											}
										}
										else if(producto == 41)
										{
											cantidad_aux = cantidad / 3;
											cantidad_aux2 = idEvento % 3;
											if(cantidad_aux2 == 1)
											{
												aux1 = parseInt(id);
												aux2 = parseInt(id)+2;
											}
											else if(cantidad_aux2 == 2)
											{
												aux1 = parseInt(id)-1;
												aux2 = parseInt(id)+1;
											}
											else
											{
												aux1 = parseInt(id)-2;
												aux2 = parseInt(id);
											}
										}
										else if(producto == 42)
										{
											cantidad_aux = cantidad / 2;
											cantidad_aux2 = idEvento % 2;
											if(cantidad_aux2 == 1)
											{
												aux1 = parseInt(id);
												aux2 = parseInt(id)+1;
											}
											else
											{
												aux1 = parseInt(id)-1;
												aux2 = parseInt(id);
											}
										}
										for(i = aux1; i <= aux2; i++)
										{
											/*if(tema == $('#tema'+i).val())
											{*/
												$('#sede'+i).val(sede);
												$('#fecha_realizacion_1_'+i).show();
												$('#fecha_realizacion_1_'+i).val(fecha);
												$('#fecha_realizacion_2_'+i).hide();
												$('#fecha_realizacion_2_'+i).val('');
												$('#fecha_realizacion_3_'+i).hide();
												$('#fecha_realizacion_3_'+i).val('');
												$('#radio-fecharealizacion_1_'+i).attr("checked",true);
												
												$('.ejecutar'+i).val(month);
												$('.costear'+i).val(month+1);
												$('.facturar'+i).val(month+1);
											//}
										}
									}
								}
							);
							
							$('#<?php print($variable_fecha_realizacion2);?>').bsdatepicker({format: 'yyyy-mm-dd'})
								.on('changeDate', function(ev){
									var date = $('#<?php print($variable_fecha_realizacion2);?>').val();
									var nextDate = new Date(date);
									nextDate.setDate(nextDate.getDate() - 7);
									var dd = nextDate.getDate()+1;
									var mm = nextDate.getMonth()+1;
									var y = nextDate.getFullYear();
									var someFormattedDate = y + '-' + mm + '-' + dd;
									$('#<?php print($variable_fecha_entrega_viaticos); ?>').val(someFormattedDate);
									var month = date.substring(5,7);
									month = parseInt(month);
									$('.<?php print($variable_ejecutar_class);?>').val(month);
									if(month == 12)
							{
							   month=0;
							}
									$('.<?php print($variable_costear_class);?>').val(month+1);
									$('.<?php print($variable_facturar_class);?>').val(month+1);
									
									//COPIAR TODAS LAS SEDES
									var id = $('#<?php print($variable_fecha_realizacion2);?>').attr('id');
									id = id.replace("fecha_realizacion_2_","");
									var sede = $('#sede'+id).val();
									var fecha = $('#fecha_realizacion_2_'+id).val();
									var producto = $('#producto'+id).val();
									if(producto == 40 || producto == 41 || producto == 42)
									{
										var cantidad = $('#auxiliar_tipoFDP_cantidad'+id).val();
										var tema = $('#tema'+id).val();
										var idEvento = parseInt($('#evento'+id).val());
										var cantidad_aux = 0;
										var cantidad_aux2 = 0;
										var aux1 = 0;
										var aux2 = 0;
										if(producto == 40)
										{
											cantidad_aux = cantidad / 4;
											cantidad_aux2 = idEvento % 4;
											if(cantidad_aux2 == 1)
											{
												aux1 = parseInt(id);
												aux2 = parseInt(id)+3;
											}
											else if(cantidad_aux2 == 2)
											{
												aux1 = parseInt(id)-1;
												aux2 = parseInt(id)+2;
											}
											else if(cantidad_aux2 == 3)
											{
												aux1 = parseInt(id)-2;
												aux2 = parseInt(id)+1;
											}
											else
											{
												aux1 = parseInt(id)-3;
												aux2 = parseInt(id);
											}
										}
										else if(producto == 41)
										{
											cantidad_aux = cantidad / 3;
											cantidad_aux2 = idEvento % 3;
											if(cantidad_aux2 == 1)
											{
												aux1 = parseInt(id);
												aux2 = parseInt(id)+2;
											}
											else if(cantidad_aux2 == 2)
											{
												aux1 = parseInt(id)-1;
												aux2 = parseInt(id)+1;
											}
											else
											{
												aux1 = parseInt(id)-2;
												aux2 = parseInt(id);
											}
										}
										else if(producto == 42)
										{
											cantidad_aux = cantidad / 2;
											cantidad_aux2 = idEvento % 2;
											if(cantidad_aux2 == 1)
											{
												aux1 = parseInt(id);
												aux2 = parseInt(id)+1;
											}
											else
											{
												aux1 = parseInt(id)-1;
												aux2 = parseInt(id);
											}
										}
										for(i = aux1; i <= aux2; i++)
										{
											/*if(tema == $('#tema'+i).val())
											{*/
												$('#sede'+i).val(sede);
												$('#fecha_realizacion_1_'+i).hide();
												$('#fecha_realizacion_1_'+i).val('');
												$('#fecha_realizacion_2_'+i).show();
												$('#fecha_realizacion_2_'+i).val(fecha);
												$('#fecha_realizacion_3_'+i).hide();
												$('#fecha_realizacion_3_'+i).val('');
												$('#radio-fecharealizacion_2_'+i).attr("checked",true);
												
												$('.ejecutar'+i).val(month);
												$('.costear'+i).val(month+1);
												$('.facturar'+i).val(month+1);
											//}
										}
									}
								});
								
							$('#<?php print($variable_fecha_realizacion_horario2);?>').timepicker({
								minuteStep: 5,
								showSeconds: true,
								showMeridian: false
							});	
								
							$('#<?php print($variable_fecha_cobranza);?>').bsdatepicker({format: 'yyyy-mm-dd'})
								.on('changeDate', function(ev){
									var date = $('#<?php print($variable_fecha_cobranza);?>').val();
									var month = date.substring(5,7);
									month = parseInt(month);
									$('.<?php print($variable_cobrar_class);?>').val(month);
								});
							//SCRIPT PARA COPIAR AGENTES
							$( "#<?php print($variable_agente); ?>" ).change(function() 
							{
								var id = $('#<?php print($variable_agente);?>').attr('id');
								id = id.replace("agente0_","");
								var agente = $('#<?php print($variable_agente);?>').val();
								var producto = $('#producto'+id).val();
								if(producto == 40 || producto == 41 || producto == 42)
								{
									var cantidad = $('#auxiliar_tipoFDP_cantidad'+id).val();
									var idEvento = parseInt($('#evento'+id).val());
									var cantidad_aux = 0;
									var cantidad_aux2 = 0;
									var aux1 = 0;
									var aux2 = 0;
									if(producto == 40)
									{
										cantidad_aux = cantidad / 4;
										cantidad_aux2 = idEvento % 4;
										if(cantidad_aux2 == 1)
										{
											aux1 = parseInt(id);
											aux2 = parseInt(id)+3;
										}
										else if(cantidad_aux2 == 2)
										{
											aux1 = parseInt(id)-1;
											aux2 = parseInt(id)+2;
										}
										else if(cantidad_aux2 == 3)
										{
											aux1 = parseInt(id)-2;
											aux2 = parseInt(id)+1;
										}
										else
										{
											aux1 = parseInt(id)-3;
											aux2 = parseInt(id);
										}
									}
									else if(producto == 41)
									{
										cantidad_aux = cantidad / 3;
										cantidad_aux2 = idEvento % 3;
										if(cantidad_aux2 == 1)
										{
											aux1 = parseInt(id);
											aux2 = parseInt(id)+2;
										}
										else if(cantidad_aux2 == 2)
										{
											aux1 = parseInt(id)-1;
											aux2 = parseInt(id)+1;
										}
										else
										{
											aux1 = parseInt(id)-2;
											aux2 = parseInt(id);
										}
									}
									else if(producto == 42)
									{
										cantidad_aux = cantidad / 2;
										cantidad_aux2 = idEvento % 2;
										if(cantidad_aux2 == 1)
										{
											aux1 = parseInt(id);
											aux2 = parseInt(id)+1;
										}
										else
										{
											aux1 = parseInt(id)-1;
											aux2 = parseInt(id);
										}
									}
									for(i = aux1; i <= aux2; i++)
									{
										$('#agente0_'+i).val(agente);
									}
								}
							});
						});
					</script>
					<?php
						if($row['id_subproducto'] == 57)
						{
							$readOnlyCostos = "";
						}
						else
						{
							$readOnlyCostos = "readonly";
						}
					?>
					<tr class="">
					       <td id="txt_evento">
							<input type="text" class="form-control" name="<?php print($variable_evento);?>" id="<?php print($variable_evento);?>" value="<?php print($i);?>" readonly>
						</td>
					       <td id="txt_id_evento">
							<input type="hidden" class="form-control" name="<?php print($variable_total_agentes);?>" id="<?php print($variable_total_agentes);?>" value="<?php print($totalAgentes);?>">
							<input type="hidden" class="form-control" name="<?php print($variable_detalleoi);?>" id="<?php print($variable_detalleoi);?>" value="<?php print($row['id_detalleoi']);?>">
							<input type="text" class="form-control" name="<?php print($variable_id_evento);?>" id="<?php print($variable_id_evento);?>" value="<?php print($evento); ?>" readonly>
						</td>
						<td id="txt_personas">
							<input type="text" class="form-control" name="<?php print($variable_personas);?>" id="<?php print($variable_personas);?>" value="<?php print($row['personas']);?>">
						</td>
						<td id="txt_nivel">
							<select name="<?php print($variable_nivel);?>" id="<?php print($variable_nivel);?>" class="form-control">
								<option value="<?php print($row['id_nivel']);?>" selected><?php print($row['nivel']);?></option>
								<option value="1">Operativo</option>
								<option value="2">Mando medio</option>
								<option value="3">Directivo</option>
								<option value="4">Otro</option>
							</select>
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
						<td id="txt_subproducto">
							<select id="<?php print($variable_subproducto); ?>" name="<?php print($variable_subproducto); ?>" class="form-control" readonly>
								<option value="<?php print($row['id_subproducto']);?>" selected><?php print($row['nombre_subproducto']);?></option>
							</select>
							<input type="hidden" class="form-control" name="<?php print($variable_auxiliar_tipoFDP);?>" id="<?php print($variable_auxiliar_tipoFDP);?>" value="<?php print($variable_auxiliar_tipoFDP_valor);?>" readonly>
							<input type="hidden" class="form-control" name="<?php print($variable_auxiliar_tipoFDP_cantidad);?>" id="<?php print($variable_auxiliar_tipoFDP_cantidad);?>" value="<?php print($cantidad);?>" readonly>
						</td>
						<td id="txt_tema">
							<input onkeyup="getIDEvento(this);" type="text" class="form-control" name="<?php print($variable_tema);?>" id="<?php print($variable_tema);?>" value="<?php print($row['tema']);?>" >
						</td>
						<?php 
							if($row['id_subproducto'] == 57)
							{
						?>
						<td id="txt_tipoagente">
							<select id="<?php print($variable_tipoagente);?>" name="<?php print($variable_tipoagente);?>" class="form-control">
								<?php $this->selectTipoAgenteUpdateOtro($row['id_subproducto']);?>
							</select>
						</td>
						<td id="txt_agente">
							<select id="<?php print($variable_agente);?>" name="<?php print($variable_agente);?>" class="form-control">
								<option value="" selected>Selecciona un agente</option>
							</select>
						</td>
						<?php
							}
							else
							{
							    if($row['id_subproducto'] == 129)
					    {
					         $sensibilizacion = 'inline';
					    }
					    else
					    {
					        $sensibilizacion = 'none';
					    }
						?>
						<td id="txt_tipoagente">
					<select id="<?php print($variable_tipoagente);?>" name="<?php print($variable_tipoagente);?>" class="form-control">
						<?php $this->selectTipoAgente($row3['id_tipoagente']);?>
					</select>
					<div class='col-sm-12 sensibilizacion' style='display:<?php print($sensibilizacion); ?>'><label class='radio-inline'><input onclick='getSensibilizacion(this); getPercentage(this);' type='radio' id='sensibilizacion<?php print($auxiliar); ?>' name='sensibilizacion<?php print($auxiliar); ?>' value='1' checked>Apoyo</label><input onclick='getSensibilizacion(this); getPercentage(this);' type='radio' id='sensibilizacion<?php print($auxiliar); ?>' name='sensibilizacion<?php print($auxiliar); ?>' value='0'>Coordinador</label><label class='radio-inline'><input onclick='getSensibilizacion(this); getPercentage(this);' type='radio' id='sensibilizacion<?php print($auxiliar); ?>' name='sensibilizacion<?php print($auxiliar); ?>' value='2'>Ambos</label></div>
				</td>
						<td id="txt_agente">
							<select id="<?php print($variable_agente);?>" name="<?php print($variable_agente);?>" class="form-control">
								<option value="" selected>Selecciona un agente</option>
							</select>
						</td>
						<?php
							}
						?>
						<td id="txt_equipo">
							<select name="<?php print($variable_equipo);?>" id="<?php print($variable_equipo);?>" class="form-control">
								<option value="1" selected>Estándar</option>
								<option value="2">Adicional</option>
							</select>
						</td>
						<td id="txt_material">
							<select name="<?php print($variable_material);?>" id="<?php print($variable_material);?>" class="form-control">
								<option value="1" selected>Estándar</option>
								<option value="2">Adicional</option>
							</select>
						</td>
						<td id="txt_precio">
							<input type="text" class="form-control" name="<?php print($variable_precio);?>" id="<?php print($variable_precio);?>" value="<?php print(number_format($row['precio_oi'],2));?>" readonly>
						</td>
						<td id="txt_descuento">	
							<input id="<?php print($variable_descuento);?>" name="<?php print($variable_descuento);?>" type="text" class="form-control" value="<?php print(round(1-$row['descuento'],2)*100) ;?>%" readonly>
						</td>
						<?php
						if($row['id_subproducto'] == 57)
						{
						?>
						<td id="txt_honorarios">
					<input type="hidden" class="form-control" name="<?php print($variable_honorarios);?>" id="<?php print($variable_honorarios);?>" value="<?php print(number_format($row['costo_oi'],2));?>" readonly>
					<input <?php print($readOnlyCostos); ?> type="text" class="form-control" name="<?php print($variable_honorarios_aux);?>" id="<?php print($variable_honorarios_aux);?>" value="<?php print(number_format($row['costo_oi'],2));?>" readonly>
				</td>
				<td id="txt_porcentajehonorarios">
					<input type="text" class="form-control" name="" id="" value="<?php print(round($row['porcentajeHonorarios'],2)*100);?>%" readonly>
				</td>
				<?php
				}
				else
				{
				?>
						<td id="txt_honorarios">
							<input type="hidden" class="form-control" name="<?php print($variable_honorarios);?>" id="<?php print($variable_honorarios);?>" value="<?php print(number_format($row['costo_honorarios'],2));?>" readonly>
							<input <?php print($readOnlyCostos); ?> type="text" class="form-control" name="<?php print($variable_honorarios_aux);?>" id="<?php print($variable_honorarios_aux);?>" value="<?php print(number_format($row3['costo_tipoagente_subproducto'],2));?>" readonly>
						</td>
						<td id="txt_porcentajehonorarios">
							<input type="text" class="form-control" name="<?php print($variable_porcentajehonorarios_aux);?>" id="<?php print($variable_porcentajehonorarios_aux);?>" value="<?php print(round($row['porcentajeHonorarios'],2)*100);?>%" readonly>
						</td>
						<?php
						}
						?>
						<td id="txt_costomateriales">
							<input <?php print($readOnlyCostos); ?> type="text" class="form-control" name="<?php print($variable_costomateriales);?>" id="<?php print($variable_costomateriales);?>" value="<?php print(number_format($row['costo_materiales'],2));?>" readonly>
							<div class="col-sm-6">
								<label class="radio-inline">
									<input onclick="getPercentage(this);" type="radio" id="<?php print($variable_name_radio_button_costomaterial1);?>" name="<?php print($variable_name_radio_button_costomaterial);?>" value="1" checked onclick="">
									Aplicar costo
								</label>
							</div>
							<div class="col-sm-6">
								<label class="radio-inline">
									<input onclick="getPercentage(this);" type="radio" id="<?php print($variable_name_radio_button_costomaterial2);?>" name="<?php print($variable_name_radio_button_costomaterial);?>" value="0" onclick="">
									No aplicar costo
								</label>
							</div>
						</td>
						<td id="txt_porcentajemateriales">
							<input type="text" class="form-control" name="" id="" value="<?php print(round($row['porcentajeMateriales'],2)*100);?>%" readonly>
						</td>
						<td id="txt_costoadicionales">
							<input type="text" class="form-control" name="<?php print($variable_costosadicionales);?>" id="<?php print($variable_costosadicionales);?>" value="0.00" onkeyup="getCommas(this); getPercentage(this);">
						</td>
						<td id="txt_porcentajecostoadicionales">
							<input type="text" class="form-control" name="<?php print($variable_porcentajecostosadicionales);?>" id="<?php print($variable_porcentajecostosadicionales);?>" value="0%" readonly>
						</td>
						<td id="update_txt_costo">
							<input type="text" class="form-control" name="<?php print($variable_costo);?>" id="<?php print($variable_costo);?>" value="<?php print(number_format($row['costo_oi'],2));?>" readonly>
						</td>
						<td id="txt_porcentajemateriales">
							<input type="text" class="form-control" name="<?php print($variable_porcentajecosto);?>" id="<?php print($variable_porcentajecosto);?>" value="<?php print(round($row['porcentajeCosto'],4)*100);?>%" readonly>
						</td>
						<td id="txt_margen">
							<input type="text" class="form-control" name="" id="" value="<?php print(number_format($row['precio_oi']-$row['costo_oi'],2));?>" readonly>
						</td>
						<td id="txt_porcentajemargen">
							<input type="text" class="form-control" name="" id="" value="<?php print(round(($row['porcentajeMargen'])*100,2));?>%" readonly>
						</td>
						<td id="txt_sede">
							<select name="<?php print($variable_sede);?>" id="<?php print($variable_sede);?>" class="form-control">
								<?php $this->getEstados(); ?>
							</select>
						</td>
						<td id="txt_sede_particular">
							<input id="<?php print($variable_sede_particular);?>" name="<?php print($variable_sede_particular);?>" value="" type="text" class="form-control">
						</td>
						<td id="txt_fecharealizacion">
							<input id="<?php print($variable_fecha_realizacion1);?>" name="<?php print($variable_fecha_realizacion1);?>" value="" type="text" class="form-control <?php print($variable_class_fecha_realizacion1);?>" placeholder="dd-mm-yyyy">
							<input id="<?php print($variable_fecha_realizacion2);?>" name="<?php print($variable_fecha_realizacion2);?>" value="" type="text" class="form-control <?php print($variable_class_fecha_realizacion2);?>" placeholder="dd-mm-yyyy" style="display:none;">
							<input id="<?php print($variable_fecha_realizacion_horario2);?>" name="<?php print($variable_fecha_realizacion_horario2);?>" value="" type="text" class="form-control <?php print($variable_class_fecha_realizacion2);?>" placeholder="hh:mm" style="display:none;">
							<input id="<?php print($variable_fecha_realizacion3);?>" name="<?php print($variable_fecha_realizacion3);?>" value="" type="text" class="form-control <?php print($variable_class_fecha_realizacion3);?>" placeholder="dd-mm-yyyy" style="display:none;">
							<div class="col-sm-6">
								<label class="radio-inline">
									<input type="radio" id="<?php print($variable_radio_button1);?>" name="<?php print($variable_name_radio_button);?>" value="1" checked="checked">
									Juntas
								</label>
								<label class="radio-inline">
									<input type="radio" id="<?php print($variable_radio_button2);?>" name="<?php print($variable_name_radio_button);?>" value="2">
									Ordenadas
								</label>
							</div>
							<div class="col-sm-6">
								<label class="radio-inline">
									<input type="radio" id="<?php print($variable_radio_button3);?>" name="<?php print($variable_name_radio_button);?>" value="3">
									Separadas
								</label>
							</div>
						</td>
						<td id="txt_mes_ejecucion">
							<select name="<?php print($variable_mes_ejecucion);?>" id="<?php print($variable_mes_ejecucion);?>" class="form-control <?php print($variable_ejecutar_class);?>">
								<option value="1">Enero</option>
								<option value="2">Febrero</option>
								<option value="3">Marzo</option>
								<option value="4">Abril</option>
								<option value="5">Mayo</option>
								<option value="6">Junio</option>
								<option value="7">Julio</option>
								<option value="8">Agosto</option>
								<option value="9">Septiembre</option>
								<option value="10">Octubre</option>
								<option value="11">Noviembre</option>
								<option value="12">Diciembre</option>
							</select>
						</td>
						<td id="txt_estatus_ejecucion">
							<select onchange="" id="<?php print($variable_estatus_ejecucion);?>" name="<?php print($variable_estatus_ejecucion);?>" class="form-control">
								<option value="1">Ejecutado</option>
								<option value="2" selected>Por ejecutar</option>
							</select>
						</td>													
						<td id="txt_anomalias">
							<textarea rows="3" class="form-control textarea-autosize" id="<?php print($variable_anomalia);?>" name="<?php print($variable_anomalia);?>"></textarea>
						</td>
						<td id="txt_mes_costo">
							<select name="<?php print($variable_mes_costo);?>" id="<?php print($variable_mes_costo);?>" class="form-control <?php print($variable_costear_class);?>">
								<option value="1">Enero</option>
								<option value="2">Febrero</option>
								<option value="3">Marzo</option>
								<option value="4">Abril</option>
								<option value="5">Mayo</option>
								<option value="6">Junio</option>
								<option value="7">Julio</option>
								<option value="8">Agosto</option>
								<option value="9">Septiembre</option>
								<option value="10">Octubre</option>
								<option value="11">Noviembre</option>
								<option value="12">Diciembre</option>
							</select>
						</td>
						<td id="txt_estatus_costo">
							<select id="<?php print($variable_estatus_costo);?>" name="<?php print($variable_estatus_costo);?>" class="form-control">
								<option value="1">Pagado</option>
								<option value="2" selected>Por pagar</option>
							</select>
						</td>
						<td id="txt_mes_factura">
							<select name="<?php print($variable_mes_factura);?>" id="<?php print($variable_mes_factura);?>" class="form-control <?php print($variable_facturar_class);?>">
								<option value="1">Enero</option>
								<option value="2">Febrero</option>
								<option value="3">Marzo</option>
								<option value="4">Abril</option>
								<option value="5">Mayo</option>
								<option value="6">Junio</option>
								<option value="7">Julio</option>
								<option value="8">Agosto</option>
								<option value="9">Septiembre</option>
								<option value="10">Octubre</option>
								<option value="11">Noviembre</option>
								<option value="12">Diciembre</option>
							</select>
						</td>
						<td id="txt_estatus_factura">
							<select id="<?php print($variable_estatus_factura);?>" name="<?php print($variable_estatus_factura);?>" class="form-control">
								<option value="1">Facturado</option>
								<option value="2" selected>Por facturar</option>
							</select>
						</td>
						<td id="txt_factura">
							<input type="text" class="form-control" name="<?php print($variable_factura);?>" value="" id="<?php print($variable_factura);?>" >
						</td>
						<td id="txt_estatus_cobranza">
							<select id="<?php print($variable_estatus_cobranza);?>" name="<?php print($variable_estatus_cobranza);?>" class="form-control">
								<option value="1">Cobrado</option>
								<option value="2" selected>Por cobrar</option>
								<option value="3">Ingresada a plataforma</option>
								<option value="4">Monitoreo</option>
							</select>
						</td>
						<td id="txt_fecha_cobranza">
							<input id="<?php print($variable_fecha_cobranza);?>" name="<?php print($variable_fecha_cobranza);?>" value="" type="text" class="form-control" placeholder="dd-mm-yyyy">
						</td>
						<td id="txt_mes_cobranza">
							<select name="<?php print($variable_mes_cobranza);?>" id="<?php print($variable_mes_cobranza);?>" class="form-control <?php print($variable_cobrar_class);?>">
								<option value="1">Enero</option>
								<option value="2">Febrero</option>
								<option value="3">Marzo</option>
								<option value="4">Abril</option>
								<option value="5">Mayo</option>
								<option value="6">Junio</option>
								<option value="7">Julio</option>
								<option value="8">Agosto</option>
								<option value="9">Septiembre</option>
								<option value="10">Octubre</option>
								<option value="11">Noviembre</option>
								<option value="12">Diciembre</option>
							</select>
						</td>
						<td id="txt_viaticos">
							<input type="checkbox" class="form-control" name="<?php print($variable_viaticos);?>" id="<?php print($variable_viaticos);?>" value="0" onclick="getReadonly(this);">
						</td>
					</tr>
					<?php
							if($comprobacion)
							{
								$elementos = count($arrayAux);
								for($j = 1; $j < $elementos; $j++)
								{
								if($row['precio_oi'] == 0)
								{
								    $porcentajeHon = 0;
								}
								else
								{
								    $porcentajeHon = $arrayAux2[$j]/$row['precio_oi']; 
								}
					?>
					<tr class="" style="<?php print($styleHidden);?>">
						<td id=""><input type="hidden" class="form-control" name="" id="" value="<?php print($i);?>" readonly></td>
						<td id=""></td>
						<td id=""></td>
						<td id=""></td>
						<td id=""></td>
						<td id=""></td>
						<td id=""></td>
						<td id=""></td>
						<td id="txt_tipoagente_v2">
							<select id="<?php print('tipoagente'.$j.'_'.$auxiliar);?>" name="<?php print('tipoagente'.$j.'_'.$auxiliar);?>" class="form-control">
								<?php $this->selectTipoAgente($arrayAux[$j]);?>
							</select>
						</td>
						<td id="txt_agente_v2">
							<select id="<?php print('agente'.$j.'_'.$auxiliar);?>" name="<?php print('agente'.$j.'_'.$auxiliar);?>" class="form-control">
								<option value="" selected>Selecciona un agente</option>
							</select>
						</td>
						<td id=""></td>
						<td id=""></td>
						<td id=""></td>
						<td id=""></td>
						<td id=""><input <?php print($readOnlyCostos); ?> type="text" class="form-control" name="<?php print('honorarios'.$j.'_'.$auxiliar);?>" id="<?php print('honorarios'.$j.'_'.$auxiliar);?>" value="<?php print(number_format($arrayAux2[$j],2));?>" readonly></td>
						<td id=""><input type="text" class="form-control" name="<?php print('porcentajehonorarios'.$j.'_'.$auxiliar);?>" id="<?php print('porcentajehonorarios'.$j.'_'.$auxiliar);?>" value="<?php print(round($porcentajeHon,2)*100);?>%" readonly></td>
						<td id=""></td>
						<td id=""></td>
						<td id=""></td>
						<td id=""></td>
						<td id=""></td>
						<td id=""></td>
						<td id=""></td>
						<td id=""></td>
						<td id=""></td>
						<td id=""></td>
						<td id=""></td>
						<td id=""></td>
						<td id=""></td>
						<td id=""></td>													
						<td id=""></td>													
						<td id=""></td>													
						<td id=""></td>													
						<td id=""></td>													
						<td id=""></td>
						<td id=""></td>
						<td id=""></td>
						<td id=""></td>
						<td id=""></td>
						<td id=""></td>
					</tr>
					<?php
								}
							}	
							$auxiliar++;
						}
						$contador++;
					}
				}
				?>
				<input id="contador" name="contador" value="<?php print($auxiliar);?>" type="text" hidden />
				<?php
			}
			else
			{
				return false;
			}
		}
		public function showOIDetalleBCompletar($ordenes)
		{
			$stmt = $this->conn->prepare("SELECT 
				DB.id_detallebitacora, 
				DB.id_del_evento,
				DB.tema,  
				DB.no_personas, 
				DB.precio_bitacora, 
				DB.descuento, 
				DB.gasto_directo, 
				DB.fecha_ejecucion, 
				DB.anomalia, 
				Sub.nombre_subproducto, 
				Sub.costo_honorarios, 
				Sub.costo_materiales, 
				Sub.costo_bitacora, 
				Prod.nombre_producto, 
				Prod.id_producto,
				Fam.familia_producto, 
				Fam.id_familiaproducto,
				N.nivel, 
				TA.tipo_agente, 
				A.nombre_agente, 
				TE.tipo_equipo, 
				TM.tipo_material, 
				ME.mes_ejecucion, 
				EE.estatus_ejecucion, 
				MCS.mes_costo, 
				ECS.estatus_costo, 
				EF.estatus_facturacion, 
				MF.mes_facturacion, 
				EC.estatus_cobranza, 
				MC.mes_cobranza
			FROM T_Detalle_Bitacora AS DB USE INDEX(id_detallebitacora,id_subproducto,id_nivel,id_tipoagente,id_agente,id_tipoequipo,id_tipomaterial,id_mesejecucion,id_estatusejecucion,id_mescosto,id_estatuscosto,id_estatusfacturacion,id_mesfacturacion,id_estatuscobranza,id_mescobranza)
			INNER JOIN T_Subproductos AS Sub ON DB.id_subproducto = Sub.id_subproducto
			INNER JOIN T_Productos AS Prod ON Sub.id_producto = Prod.id_producto
			INNER JOIN T_FamiliaProductos AS Fam ON Prod.id_familiaproducto = Fam.id_familiaproducto
			INNER JOIN T_Niveles AS N ON N.id_nivel = DB.id_nivel
			INNER JOIN T_TipoAgente AS TA ON TA.id_tipoagente = DB.id_tipoagente
			INNER JOIN T_Agentes AS A ON A.id_agente = DB.id_agente
			INNER JOIN T_TipoEquipo AS TE ON TE.id_tipoequipo = DB.id_tipoequipo
			INNER JOIN T_TipoMaterial AS TM ON TM.id_tipomaterial = DB.id_tipomaterial
			INNER JOIN T_Mes_Ejecucion AS ME ON ME.id_mesejecucion = DB.id_mesejecucion
			INNER JOIN T_Estatus_Ejecucion AS EE ON EE.id_estatusejecucion = DB.id_estatusejecucion
			INNER JOIN T_Mes_Costo	AS MCS ON MCS.id_mescosto = DB.id_mescosto
			INNER JOIN T_Estatus_Costos AS ECS ON ECS.id_estatuscosto = DB.id_estatuscosto
			INNER JOIN T_Estatus_Facturacion AS EF ON EF.id_estatusfacturacion = DB.id_estatusfacturacion
			INNER JOIN T_Mes_Facturacion AS MF ON MF.id_mesfacturacion = DB.id_mesfacturacion
			INNER JOIN T_Estatus_Cobranza AS EC USE INDEX(id_estatuscobranza) ON EC.id_estatuscobranza = DB.id_estatuscobranza
			INNER JOIN T_Mes_Cobranza AS MC USE INDEX(id_mescobranza) ON MC.id_mescobranza = DB.id_mescobranza
			WHERE DB.id_bitacora = :ordenes
			LIMIT 0 , 30");
			$stmt->execute(array(':ordenes'=>$ordenes));
			
			$contador = 1;
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
			?>
			<tr id="tr" name="tr" class="">
				<input type="text" id="" name="" value="<?php print($row['id_detallebitacora']);?>" style="display:none;" />
				<td id="txt_evento">
					<input type="text" class="form-control" name="" id="" value="<?php print($contador);?>" readonly>
				</td>
				<td id="txt_id_evento">
					<input type="text" class="form-control" name="" id="" value="<?php print($row['id_del_evento']);?>" disabled>
				</td>
				<td id="txt_personas">
					<input type="text" class="form-control" name="" id="" value="<?php print($row['no_personas']);?>" disabled>
				</td>
				<td id="txt_nivel">
					<select disabled name="" id="" class="form-control">
						<option value="" selected><?php print($row['nivel']);?></option>
					</select>
				</td>
				<td id="update_select_familia">
					<select disabled id="" name="" class="form-control">
						<option value="" selected><?php print($row['familia_producto']);?></option>
					</select>
				</td>
				<td id="update_select_producto">
					<select disabled id="" name="" class="form-control">
						<option value="" selected><?php print($row['nombre_producto']);?></option>
					</select>
				</td>
				<td id="update_select_subproducto">
					<select disabled id="" name="" class="form-control">
						<option value="" selected><?php print($row['nombre_subproducto']);?></option>
					</select>
				</td>
				<td id="txt_tema">
					<input disabled type="text" onkeyup="" class="form-control" name="" id="" value="<?php print($row['tema']);?>" >
				</td>
				<td id="txt_tipoagente">
					<select id="" name="" class="form-control" disabled>
						<option value="" selected><?php print($row['tipo_agente']);?></option>
					</select>
				</td>
				<td id="txt_agente">
					<select id="" name="" class="form-control" disabled>
						<option value="" selected><?php print($row['nombre_agente']);?></option>
					</select>
				</td>
				<td id="txt_equipo">
					<select name="" id="" class="form-control" disabled>
						<option value="" selected><?php print($row['tipo_equipo']); ?></option>
					</select>
				</td>
				<td id="txt_material">
					<select name="" id="" class="form-control" disabled>
						<option value="" selected><?php print($row['tipo_material']); ?></option>
					</select>
				</td>
				<td id="txt_precio">
					<input type="text" class="form-control" name="" id="" value="<?php print(number_format($row['precio_bitacora'],2));?>" readonly>
				</td>
				<td id="txt_descuento">	
					<input id="" name="" type="text" class="form-control" value="<?php print(round($row['descuento']*100,2)) ;?>" readonly>
				</td>
				<td id="txt_honorarios">
					<input type="text" class="form-control" name="" id="" value="<?php print(number_format($row['costo_honorarios'],2));?>" readonly>
				</td>
				<td id="txt_porcentajehonorarios">
					<input type="text" class="form-control" name="" id="" value="<?php print(round(($row['costo_honorarios']/$row['precio_bitacora'])*100,2));?>%" readonly>
				</td>
				<td id="txt_costomateriales">
					<input type="text" class="form-control" name="" id="" value="<?php print(number_format($row['costo_materiales'],2));?>" readonly>
				</td>
				<td id="txt_porcentajemateriales">
					<input type="text" class="form-control" name="" id="" value="<?php print(round(($row['costo_materiales']/$row['precio_bitacora'])*100,2));?>%" readonly>
				</td>
				<td id="update_txt_costo">
					<input type="text" class="form-control" name="" id="" value="<?php print(number_format($row['costo_bitacora'],2));?>" readonly>
				</td>
				<td id="txt_porcentajemateriales">
					<input type="text" class="form-control" name="" id="" value="<?php print(round(($row['costo_bitacora']/$row['precio_bitacora'])*100,2));?>%" readonly>
				</td>
				<td id="txt_margen">
					<input type="text" class="form-control" name="" id="" value="<?php print(number_format($row['precio_bitacora']-$row['costo_bitacora'],2));?>" readonly>
				</td>
				<td id="txt_porcentajemargen">
					<input type="text" class="form-control" name="" id="" value="<?php print(round((($row['precio_bitacora']-$row['costo_bitacora'])/$row['precio_bitacora'])*100,2));?>%" readonly>
				</td>
				<td id="txt_fecharealizacion">
					<input id="" name="" value="<?php print($row['fecha_ejecucion']); ?>" type="text" class="form-control" placeholder="dd-mm-yyyy" disabled>
					<input id="" name="" value="" type="text" class="form-control" placeholder="dd-mm-yyyy" style="display:none;">
					<input id="" name="" value="" type="text" class="form-control" placeholder="dd-mm-yyyy" style="display:none;">
					<div class="col-sm-6">
                        <label class="radio-inline">
                            <input type="radio" id="" name="" value="1" checked="checked" disabled>
                            Periodo
                        </label>
                        <label class="radio-inline">
                            <input type="radio" id="" name="" value="2" disabled>
                            Única fecha
                        </label>
					</div>
					<div class="col-sm-6">
                        <label class="radio-inline">
                            <input type="radio" id="" name="" value="3" disabled>
                            Fechas independientes
                        </label>
                    </div>
				</td>
				<td id="txt_mes_ejecucion">
					<select name="" id="" class="form-control" disabled>
						<option value="" selected><?php print($row['mes_ejecucion']);?></option>
					</select>
				</td>
				<td id="txt_estatus_ejecucion">
					<select id="" name="" class="form-control" disabled>
						<option value="" selected><?php print($row['estatus_ejecucion']);?></option>
					</select>
				</td>
				<td id="txt_anomalias">
					<textarea rows="3" class="form-control textarea-autosize" id="" name="" readonly><?php print($row['anomalia']);?></textarea>
				</td>
				<td id="txt_estatus_costo">
					<select id="" name="" class="form-control" disabled>
						<option value="" selected><?php print($row['estatus_costo']);?></option>
					</select>
				</td>
				<td id="txt_mes_costo">
					<select name="" id="" class="form-control" disabled>
						<option value="" selected><?php print($row['mes_costo']);?></option>
					</select>
				</td>
				<td id="txt_estatus_factura">
					<select id="" name="" class="form-control" disabled>
						<option value="" selected><?php print($row['estatus_facturacion']);?></option>
					</select>
				</td>
				<td id="txt_mes_factura">
					<select name="" id="" class="form-control" disabled>
						<option value="" selected><?php print($row['mes_facturacion']);?></option>
					</select>
				</td>
				<td id="txt_factura">
					<input type="text" class="form-control" name="<?php print($variable_factura);?>" value="" id="<?php print($variable_factura);?>" >
				</td>
				<td id="txt_estatus_cobranza">
					<select id="<?php print($variable_estatus_cobranza);?>" name="<?php print($variable_estatus_cobranza);?>" class="form-control" disabled>
						<option value="" selected><?php print($row['estatus_cobranza']);?></option>
					</select>
				</td>
				<td id="txt_mes_cobranza">
					<select name="" id="" class="form-control" disabled>
						<option value="" selected><?php print($row['mes_cobranza']);?></option>
					</select>
				</td>
			</tr>
			<?php
				$contador++;
			}
		}
		
		
		//COPIAR LOS SHOWS PERO ADAPTÁNDOLOS PARA UPDATE DE BITACORA
		public function showB($ordenes)
		{
			$stmt2 = $this->conn->prepare("SELECT * FROM T_Bitacora AS B
				INNER JOIN T_Clientes AS C
				ON B.id_cliente = C.id_cliente
				INNER JOIN T_TipoAnticipos AS TA
				ON TA.id_adminanticipos = B.id_adminanticipos
				WHERE B.id_bitacora = :ordenes");
			$stmt2->execute(array(':ordenes'=>$ordenes));
			$row2=$stmt2->fetch(PDO::FETCH_ASSOC);
			return $row2;
		}
		public function showOIDetalleB($ordenes)
		{
			$resultsAux = array();
			$stmt = $this->conn->prepare("SELECT 
				DB.id_detallebitacora,
				DB.version,
				DB.id_del_evento,
				DB.id_subproducto, 
				DB.tema,  
				DB.no_personas, 
				DB.id_nivel, 
				DB.id_tipoequipo, 
				DB.id_tipomaterial, 
				DB.precio_bitacora,
				(CASE WHEN DB. costo_bitacora = 0 THEN 0.01 ELSE DB.costo_bitacora END )AS  costo_bitacora,
				DB.costo_honorarios,
				DB.costo_materiales,
				(CASE WHEN DB.precio_bitacora = 0 THEN 0 ELSE DB.costo_honorarios/DB.precio_bitacora END) AS porcentajeHonorarios,
				(CASE WHEN DB.precio_bitacora = 0 THEN 0 ELSE DB.costo_materiales/DB.precio_bitacora END) AS porcentajeMateriales,
				(CASE WHEN DB.precio_bitacora = 0 THEN 0 ELSE DB.costo_bitacora/DB.precio_bitacora END) AS porcentajeBitacora,
				(CASE WHEN DB.precio_bitacora = 0 THEN 0 ELSE (DB.precio_bitacora-DB.costo_materiales)/DB.precio_bitacora END) AS porcentajeMargen,
				DB.costo_adicionales,
				(CASE WHEN DB.costo_adicionales = 0 THEN 0 ELSE DB.costo_adicionales/DB.precio_bitacora END) AS porcentajecosto_adicionales,
				DB.descuento, 
				DB.gasto_directo, 
				Sub.nombre_subproducto, 
				Prod.nombre_producto, 
				Prod.id_producto,
				Fam.familia_producto, 
				Fam.id_familiaproducto,
				N.nivel, 
				TE.tipo_equipo, 
				TM.tipo_material
			FROM T_Detalle_Bitacora AS DB USE INDEX(id_detallebitacora,id_subproducto,id_nivel,id_tipoagente,id_agente,id_tipoequipo,id_tipomaterial,id_mesejecucion,id_estatusejecucion,id_mescosto,id_estatuscosto,id_estatusfacturacion,id_mesfacturacion,id_estatuscobranza,id_mescobranza)
			INNER JOIN T_Subproductos AS Sub ON DB.id_subproducto = Sub.id_subproducto
			INNER JOIN T_Productos AS Prod ON Sub.id_producto = Prod.id_producto
			INNER JOIN T_FamiliaProductos AS Fam ON Prod.id_familiaproducto = Fam.id_familiaproducto
			INNER JOIN T_Niveles AS N ON N.id_nivel = DB.id_nivel
			INNER JOIN T_TipoEquipo AS TE ON TE.id_tipoequipo = DB.id_tipoequipo
			INNER JOIN T_TipoMaterial AS TM ON TM.id_tipomaterial = DB.id_tipomaterial
			WHERE DB.id_bitacora = :ordenes
			ORDER BY DB.id_detallebitacora");
			$stmt->execute(array(':ordenes'=>$ordenes));
			$count1 = $stmt->rowCount();
			
			$stmt2 = $this->conn->prepare("SELECT 
				DB.id_detallebitacora,
				DB.id_mesejecucion, 
				DB.fecha_ejecucion, 
				DB.anomalia, 
				DB.id_estatusejecucion, 
				DB.id_mescosto, 
				DB.id_estatuscosto, 
				DB.id_estatusfacturacion, 
				DB.id_mesfacturacion, 
				DB.id_estatuscobranza, 
				DB.id_mescobranza,
				DB.numero_factura,
				DB.fecha_cobranza,
				DB.sede_particular,
				DB.id_estado,
				DB.id_estatusejecucion_viaticos,
				DB.id_estatuscosto_viaticos, 
				DB.viaticos,
				DB.transportacion_local,
				DB.alimentos,
				DB.hospedaje,
				DB.no_deducibles,
				DB.desglose,
				DB.transportacion_foranea,
				DB.anticipo_entregado,
				DB.rembolso,
				DB.observaciones_viaticos,
				DB.gasto_admin,
				DB.fecha_entrega_viaticos,
	            DB.id_estatusfacturacion_viaticos,
				DB.id_estatuscobranza_viaticos,
				DB.id_estatusdetallebitacora,
				E.estado,
				ME.mes_ejecucion, 
				EE.estatus_ejecucion, 
				MCS.mes_costo, 
				ECS.estatus_costo, 
				EF.estatus_facturacion, 
				MF.mes_facturacion, 
				EC.estatus_cobranza, 
				MC.mes_cobranza
			FROM T_Detalle_Bitacora AS DB USE INDEX(id_detallebitacora,id_subproducto,id_nivel,id_tipoagente,id_agente,id_tipoequipo,id_tipomaterial,id_mesejecucion,id_estatusejecucion,id_mescosto,id_estatuscosto,id_estatusfacturacion,id_mesfacturacion,id_estatuscobranza,id_mescobranza)
			INNER JOIN T_Mes_Ejecucion AS ME ON ME.id_mesejecucion = DB.id_mesejecucion
			INNER JOIN T_Estatus_Ejecucion AS EE ON EE.id_estatusejecucion = DB.id_estatusejecucion
			INNER JOIN T_Mes_Costo	AS MCS ON MCS.id_mescosto = DB.id_mescosto
			INNER JOIN T_Estatus_Costos AS ECS ON ECS.id_estatuscosto = DB.id_estatuscosto
			INNER JOIN T_Estatus_Facturacion AS EF ON EF.id_estatusfacturacion = DB.id_estatusfacturacion
			INNER JOIN T_Mes_Facturacion AS MF ON MF.id_mesfacturacion = DB.id_mesfacturacion
			INNER JOIN T_Estatus_Cobranza AS EC USE INDEX(id_estatuscobranza) ON EC.id_estatuscobranza = DB.id_estatuscobranza
			INNER JOIN T_Mes_Cobranza AS MC USE INDEX(id_mescobranza) ON MC.id_mescobranza = DB.id_mescobranza
			INNER JOIN T_Estado AS E ON E.id_estado = DB.id_estado
			WHERE DB.id_bitacora = :ordenes
			ORDER BY DB.id_detallebitacora");
			$stmt2->execute(array(':ordenes'=>$ordenes));
			$count2 = $stmt2->rowCount();
			
			$stmt6 = $this->conn->prepare("SELECT 
				DB.id_detallebitacora,
				DB.id_estatusejecucion_viaticos,
				DB.id_estatuscosto_viaticos, 
				DB.viaticos,
				DB.transportacion_local,
				DB.alimentos,
				DB.hospedaje,
				DB.no_deducibles,
				DB.desglose,
				DB.transportacion_foranea,
				DB.anticipo_entregado,
				DB.rembolso,
				DB.observaciones_viaticos,
				DB.gasto_admin,
				DB.fecha_entrega_viaticos,
	            DB.id_estatusfacturacion_viaticos,
				DB.id_estatuscobranza_viaticos,
				DB.id_estatusdetallebitacora,
				EE.estatus_ejecucion AS estatus_ejecucion_viaticos, 
				ECS.estatus_costo AS estatus_costo_viaticos, 
				EF.estatus_facturacion AS estatus_facturacion_viaticos, 
				EC.estatus_cobranza AS estatus_cobranza_viaticos, 
				EDB.estatus_detallebitacora
			FROM T_Detalle_Bitacora AS DB USE INDEX(id_detallebitacora,id_subproducto,id_nivel,id_tipoagente,id_agente,id_tipoequipo,id_tipomaterial,id_mesejecucion,id_estatusejecucion,id_mescosto,id_estatuscosto,id_estatusfacturacion,id_mesfacturacion,id_estatuscobranza,id_mescobranza)
			INNER JOIN T_Estatus_Ejecucion AS EE ON EE.id_estatusejecucion = DB.id_estatusejecucion_viaticos
			INNER JOIN T_Estatus_Costos AS ECS ON ECS.id_estatuscosto = DB.id_estatuscosto_viaticos
			INNER JOIN T_Estatus_Facturacion AS EF ON EF.id_estatusfacturacion = DB.id_estatusfacturacion_viaticos
			INNER JOIN T_Estatus_Cobranza AS EC USE INDEX(id_estatuscobranza) ON EC.id_estatuscobranza = DB.id_estatuscobranza_viaticos
			INNER JOIN T_Estatus_DetalleBitacora AS EDB ON DB.id_estatusdetallebitacora = EDB.id_estatusdetallebitacora
			WHERE DB.id_bitacora = :ordenes
			ORDER BY DB.id_detallebitacora");
			$stmt6->execute(array(':ordenes'=>$ordenes));
			$count6 = $count1;
			for($i = 0; $i <= $count1; $i++)
			{
				$row=$stmt->fetch(PDO::FETCH_ASSOC);
				$row2=$stmt2->fetch(PDO::FETCH_ASSOC);
				$row6=$stmt6->fetch(PDO::FETCH_ASSOC);
				$resultsAux[$i] = $row + $row2 + $row6; 
				//$resultsAux[$i] = $row2; 
			}
			$contador = 1;
			if($count1 == $count2 && $count1 == $count6)
			{
				foreach($resultsAux as $results)
				{
					if($results['id_subproducto'] != '' || $results['id_subproducto'] != 0)
					{
						$detallebitacora = $results['id_detallebitacora'];
						$query = "SELECT 
									DBA.id_tipoagente,
									TA.tipo_agente,
									DBA.id_agente,
									A.nombre_agente,
									A.apellidos_agente,
									DBA.costo,
									DBA.id_detallebitacora_agente
								FROM T_Detalle_Bitacora_Agente AS DBA
								INNER JOIN T_TipoAgente AS TA
								ON TA.id_tipoagente = DBA.id_tipoagente
								INNER JOIN T_Agentes AS A
								ON A.id_agente = DBA.id_agente
								WHERE id_detallebitacora = :detallebitacora";
						$stmt3 = $this->conn->prepare($query);
						$stmt3->bindparam(":detallebitacora",$detallebitacora);
						$stmt3->execute();
						if($stmt3->rowCount() == 0)
						{
							$row3 = array();
							$row3['id_tipoagente'] = 0;
							$row3['tipo_agente'] = '';
							$row3['id_agente'] = 0;
							$row3['nombre_agente'] = '';
							$row3['apellidos_agente'] = '';
							$row3['costo'] = 0;
							$row3['id_detallebitacora_agente'] = 0;
							$styleHidden = "display:none;";
							$comprobacion = FALSE;
							$totalAgentes = 0;
						}
						elseif($stmt3->rowCount() == 1)
						{
							$row3=$stmt3->fetch(PDO::FETCH_ASSOC);
							$styleHidden = "display:none;";
							$comprobacion = FALSE;
							$totalAgentes = 1;
						}
						else
						{
							$arrayAux = array();
							$arrayAux2 = array();
							$arrayAux3 = array();
							$arrayAux4 = array();
							$arrayAux5 = array();
							$arrayAux6 = array();
							$x=0;
							while($row3=$stmt3->fetch(PDO::FETCH_ASSOC))
							{
								$arrayAux[$x] = $row3['id_tipoagente'];
								$arrayAux2[$x] = $row3['tipo_agente'];
								$arrayAux3[$x] = $row3['id_agente'];
								$arrayAux4[$x] = $row3['nombre_agente'];
								$arrayAux5[$x] = $row3['apellidos_agente'];
								$arrayAux6[$x] = $row3['costo'];
								$arrayAux7[$x] = $row3['id_detallebitacora_agente'];
								$x++;
							}
							$row3['id_tipoagente'] = $arrayAux[0];
							$row3['tipo_agente'] = $arrayAux2[0];
							$row3['id_agente'] = $arrayAux3[0];
							$row3['nombre_agente'] = $arrayAux4[0];
							$row3['apellidos_agente'] = $arrayAux5[0];
							$row3['costo'] = $arrayAux6[0];
							$row3['id_detallebitacora_agente'] = $arrayAux7[0];
							$styleHidden = "";
							$comprobacion = TRUE;
							$totalAgentes = $x;
						}
						
						$variable_cancelar = 'cancelar'.$contador;
						$variable_version = 'version'.$contador;
						$variable_familia = 'update_familia'.$contador;
						$variable_producto = 'producto'.$contador;
						$variable_subproducto = 'subproducto'.$contador;
						$variable_tema = 'tema'.$contador;
						$variable_id = 'id_detallebitacora'.$contador;
						$variable_evento = 'evento'.$contador;
						$variable_id_evento = 'id_evento'.$contador;
						$variable_personas = 'personas'.$contador;
						$variable_nivel = 'nivel'.$contador;
						$variable_tipoagente = 'tipoagente0_'.$contador;
						$variable_agente = 'agente0_'.$contador;
						$variable_equipo = 'equipo'.$contador;
						$variable_material = 'material'.$contador;
						$variable_precio = 'precio'.$contador;
						$variable_descuento = 'descuento'.$contador;
						$variable_costo = 'costo'.$contador;
						$variable_porcentajecosto = 'porcentajecosto'.$contador;
						$variable_honorarios = 'honorarios'.$contador;
						$variable_honorarios_aux = 'honorarios0_'.$contador;
						$variable_costomateriales = 'costomateriales'.$contador;
						$variable_costosadicionales = 'costosadicionales'.$contador;
						$variable_porcentajecostosadicionales = 'porcentajecostosadicionales'.$contador;
						$variable_mes_ejecucion = 'mes_ejecucion'.$contador;
						$variable_estatus_ejecucion = 'estatus_ejecucion'.$contador;					
						$variable_anomalia = 'anomalia'.$contador;
						$variable_fecha_cobranza = 'fecha_cobranza_'.$contador;
						$variable_sede = 'sede'.$contador;
						$variable_sede_particular = 'sede_particular'.$contador;
						$variable_fecha_realizacion1 = 'fecha_realizacion_1_'.$contador;
						$variable_fecha_realizacion2 = 'fecha_realizacion_2_'.$contador;
						$variable_fecha_realizacion3 = 'fecha_realizacion_3_'.$contador;
						$variable_name_radio_button_costomaterial = 'radio-costomaterial'.$contador;
						$variable_name_radio_button_costomaterial1 = 'radio-costomaterial_1_'.$contador;
						$variable_name_radio_button_costomaterial2 = 'radio-costomaterial_2_'.$contador;
						$variable_class_fecha_realizacion1 = 'fecha_realizacion_1_'.$contador;
						$variable_class_fecha_realizacion2 = 'fecha_realizacion_2_'.$contador;
						$variable_fecha_realizacion_horario2 = 'fecha_realizacion_2_horario'.$contador;
						$variable_class_fecha_realizacion3 = 'fecha_realizacion_3_'.$contador;
						$variable_name_radio_button = 'radio-fecharealizacion'.$contador;
						$variable_radio_button1 = 'radio-fecharealizacion_1_'.$contador;
						$variable_radio_button2 = 'radio-fecharealizacion_2_'.$contador;
						$variable_radio_button3 = 'radio-fecharealizacion_3_'.$contador;
						$variable_ejecutar_class = 'ejecutar'.$contador;
						$variable_costear_class = 'costear'.$contador;
						$variable_facturar_class = 'facturar'.$contador;
						$variable_cobrar_class = 'cobrar'.$contador;
						$variable_estatus_costo = 'estatus_costo'.$contador;
						$variable_mes_costo = 'mes_costo'.$contador;
						$variable_estatus_factura = 'estatus_factura'.$contador;
						$variable_mes_factura = 'mes_factura'.$contador;
						$variable_factura = 'factura'.$contador;
						$variable_estatus_cobranza = 'estatus_cobranza'.$contador;
						$variable_mes_cobranza = 'mes_cobranza'.$contador;
						$variable_viaticos = 'viaticos'.$contador;
						$variable_transportacion = 'transportacion'.$contador;
						$variable_alimentos = 'alimentos'.$contador;
						$variable_hospedaje = 'hospedaje'.$contador;
						$variable_nodeducible = 'nodeducible'.$contador;
						$variable_suma_viaticos = 'suma_viaticos'.$contador;
						$variable_iva = 'iva'.$contador;
						$variable_total_sinavion = 'total_sinavion'.$contador;
						$variable_desglose = 'desglose'.$contador;
						$variable_avionsiniva = 'avionsiniva'.$contador;
						$variable_ivaavion = 'ivaavion'.$contador;
						$variable_total_avion = 'total_avion'.$contador;
						$variable_anticipo_viaticos = 'anticipo_viaticos'.$contador;
						$variable_rembloso = 'rembolso'.$contador;
						$variable_total_cobrar = 'total_cobrar'.$contador;
						$variable_observaciones_viaticos = 'observaciones_viaticos'.$contador;
						$variable_gasto_admin = 'gasto_admin'.$contador;
						$variable_fecha_entrega_viaticos = 'fecha_entrega_viaticos'.$contador;
						$variable_total_agentes = 'total_agentes'.$contador;
						$variable_auxiliar_tipoFDP = 'auxiliar_tipoFDP'.$contador;
						$variable_auxiliar_tipoFDP_cantidad = 'auxiliar_tipoFDP_cantidad'.$contador;
						$variable_estatus_ejecucion_viaticos = 'estatus_ejecucion_viaticos'.$contador;
						$variable_estatus_factura_vitaticos = 'estatus_factura_viaticos'.$contador;
						$variable_estatus_cobranza_viaticos = 'estatus_cobranza_viaticos'.$contador;
						$variable_estatus_costo_viaticos = 'estatus_costo_viaticos'.$contador;
						if($results['id_producto'] == 40 || $results['id_producto'] == 41 || $results['id_producto'] == 42 || $results['id_producto'] == 43 || $results['id_producto'] == 44 || $results['id_producto'] == 45)
						{
							$variable_auxiliar_tipoFDP_valor = 1;
						}
						else
						{
							$variable_auxiliar_tipoFDP_valor = 0;
						}
						
						$subtotal = $results['transportacion_local']+$results['alimentos']+$results['hospedaje']+$results['no_deducibles'];
						$iva = ($subtotal-$results['no_deducibles'])*0.16;
						$total = $subtotal+$iva;
						$iva_transf = $results['transportacion_foranea']*0.16;
						$total_avion = $results['transportacion_foranea']+$iva_transf;
						$total_cobrar = $results['anticipo_entregado']+$results['rembolso'];
				?>
				<!--SCRIPT INICIALIZA DATEPICKERS-->
				<script type="text/javascript">
					$(function() {
						$('input[type=radio][name=<?php print($variable_name_radio_button);?>]').change(function() {
							$('.<?php print($variable_class_fecha_realizacion1);?>').val('');
							$('.<?php print($variable_class_fecha_realizacion2);?>').val('');
							$('.<?php print($variable_class_fecha_realizacion3);?>').val('');
							if (this.value == 1) 
							{
								//PERIODO
								$('.<?php print($variable_class_fecha_realizacion1);?>').show();
								$('.<?php print($variable_class_fecha_realizacion2);?>').hide();
								$('.<?php print($variable_class_fecha_realizacion3);?>').hide();
							}
							else if (this.value == 2) 
							{
								//UNICA FECHA
								$('.<?php print($variable_class_fecha_realizacion1);?>').hide();
								$('.<?php print($variable_class_fecha_realizacion2);?>').show();
								$('.<?php print($variable_class_fecha_realizacion3);?>').hide();
							}
							else
							{
								//VARIAS FECHAS
								$('.<?php print($variable_class_fecha_realizacion1);?>').hide();
								$('.<?php print($variable_class_fecha_realizacion2);?>').hide();
								$('.<?php print($variable_class_fecha_realizacion3);?>').show();
							}
						});
						
						$('.<?php print($variable_class_fecha_realizacion1);?>').daterangepicker({
							startDate: moment(),
							endDate: moment(),
							minDate: '01/01/2010',
							maxDate: '12/31/2999',
							showDropdowns: true,
							showWeekNumbers: true,
							timePicker: true,
							timePickerIncrement: 1,
							timePicker24Hour: true,
							opens: 'left',
							buttonClasses: ['btn btn-default'],
							applyClass: 'small bg-green',
							cancelClass: 'small ui-state-default',
							format: 'DD-MM-YYYY HH:mm:ss',
							separator: ' a ',
							locale: {
								applyLabel: 'Aplicar',
								fromLabel: 'Desde',
								toLabel: 'Hasta',
								daysOfWeek: ['Do', 'Lu', 'Mar', 'Mie', 'Jue', 'Vi', 'Sa'],
								monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
								firstDay: 1
							}
						},
						function(start, end) 
						{
							console.log("Callback has been called!");
							$('.<?php print($variable_class_fecha_realizacion1);?>').val(start.format('YYYY-MM-DD HH:mm:ss') + ' - ' + end.format('YYYY-MM-DD HH:mm:ss'));
							var date = end.format('DD-MM-YYYY');
							var nextDate = new Date(start);
							nextDate.setDate(nextDate.getDate() - 7);
							var dd = nextDate.getDate();
							var mm = nextDate.getMonth()+1;
							var y = nextDate.getFullYear();
							var someFormattedDate = y + '-' + mm + '-' + dd;
							$('#<?php print($variable_fecha_entrega_viaticos); ?>').val(someFormattedDate);
							var day = date.substring(0,2);
							day = parseInt(day);
							var month = date.substring(3,5);
							month = parseInt(month);
							$('.<?php print($variable_ejecutar_class);?>').val(month);
							if(month == 12)
							{
							   month=0;
							}
							$('.<?php print($variable_costear_class);?>').val(month+1);
							$('.<?php print($variable_facturar_class);?>').val(month+1);
							
							//COPIAR TODAS LAS SEDES
							var id = $('#<?php print($variable_fecha_realizacion1);?>').attr('id');
							id = id.replace("fecha_realizacion_1_","");
							var sede = $('#sede'+id).val();
							var fecha = $('#fecha_realizacion_1_'+id).val();
							var producto = $('#producto'+id).val();
							if(producto == 40 || producto == 41 || producto == 42)
							{
								var cantidad = $('#auxiliar_tipoFDP_cantidad'+id).val();
								var tema = $('#tema'+id).val();
								var idEvento = parseInt($('#evento'+id).val());
								var cantidad_aux = 0;
								var cantidad_aux2 = 0;
								var aux1 = 0;
								var aux2 = 0;
								if(producto == 40)
								{
									cantidad_aux = cantidad / 4;
									cantidad_aux2 = idEvento % 4;
									
									if(cantidad_aux2 == 1)
									{
										aux1 = parseInt(id);
										aux2 = parseInt(id)+3;
									}
									else if(cantidad_aux2 == 2)
									{
										aux1 = parseInt(id)-1;
										aux2 = parseInt(id)+2;
									}
									else if(cantidad_aux2 == 3)
									{
										aux1 = parseInt(id)-2;
										aux2 = parseInt(id)+1;
									}
									else
									{
										aux1 = parseInt(id)-3;
										aux2 = parseInt(id);
									}
								}
								else if(producto == 41)
								{
									cantidad_aux = cantidad / 3;
									cantidad_aux2 = idEvento % 3;
									
									if(cantidad_aux2 == 1)
									{
										aux1 = parseInt(id);
										aux2 = parseInt(id)+2;
									}
									else if(cantidad_aux2 == 2)
									{
										aux1 = parseInt(id)-1;
										aux2 = parseInt(id)+1;
									}
									else
									{
										aux1 = parseInt(id)-2;
										aux2 = parseInt(id);
									}
								}
								else if(producto == 42)
								{
									cantidad_aux = cantidad / 2;
									cantidad_aux2 = idEvento % 2;
									if(cantidad_aux2 == 1)
									{
										aux1 = parseInt(id)-1;
										aux2 = parseInt(id);
									}
									else
									{
										aux1 = parseInt(id);
										aux2 = parseInt(id)+1;
									}
							       }
								 tema = tema.substr(0,5);
								for(i = aux1; i <= aux2; i++)
								{
								    var temaAux = $('#tema'+i).val();
								    temaAux = temaAux.substr(0,5);
									if(tema == temaAux)
									{
										$('#sede'+i).val(sede);
										$('#fecha_realizacion_1_'+i).show();
										$('#fecha_realizacion_1_'+i).val(fecha);
										$('#fecha_realizacion_2_'+i).hide();
										$('#fecha_realizacion_2_'+i).val('');
										$('#fecha_realizacion_3_'+i).hide();
										$('#fecha_realizacion_3_'+i).val('');
										$('#radio-fecharealizacion_1_'+i).attr("checked",true);
										
										$('.ejecutar'+i).val(month);
										$('.costear'+i).val(month+1);
										$('.facturar'+i).val(month+1);
									}
								}
							}
						}
					);
					
					$('#<?php print($variable_fecha_realizacion2);?>').bsdatepicker({format: 'yyyy-mm-dd'})
						.on('changeDate', function(ev){
							var date = $('#<?php print($variable_fecha_realizacion2);?>').val();
							var nextDate = new Date(date);
							nextDate.setDate(nextDate.getDate() - 7);
							var dd = nextDate.getDate()+1;
							var mm = nextDate.getMonth()+1;
							var y = nextDate.getFullYear();
							var someFormattedDate = y + '-' + mm + '-' + dd;
							$('#<?php print($variable_fecha_entrega_viaticos); ?>').val(someFormattedDate);
							var month = date.substring(5,7);
							month = parseInt(month);
							$('.<?php print($variable_ejecutar_class);?>').val(month);
							if(month == 12)
							{
							   month=0;
							}
							$('.<?php print($variable_costear_class);?>').val(month+1);
							$('.<?php print($variable_facturar_class);?>').val(month+1);
							
							//COPIAR TODAS LAS SEDES
							var id = $('#<?php print($variable_fecha_realizacion2);?>').attr('id');
							id = id.replace("fecha_realizacion_2_","");
							var sede = $('#sede'+id).val();
							var fecha = $('#fecha_realizacion_2_'+id).val();
							var producto = $('#producto'+id).val();
							if(producto == 40 || producto == 41 || producto == 42)
							{
								var cantidad = $('#auxiliar_tipoFDP_cantidad'+id).val();
								var tema = $('#tema'+id).val();
								var idEvento = parseInt($('#evento'+id).val());
								var cantidad_aux = 0;
								var cantidad_aux2 = 0;
								var aux1 = 0;
								var aux2 = 0;
								if(producto == 40)
								{
									cantidad_aux = cantidad / 4;
									cantidad_aux2 = idEvento % 4;
									if(cantidad_aux2 == 1)
									{
										aux1 = parseInt(id);
										aux2 = parseInt(id)+3;
									}
									else if(cantidad_aux2 == 2)
									{
										aux1 = parseInt(id)-1;
										aux2 = parseInt(id)+2;
									}
									else if(cantidad_aux2 == 3)
									{
										aux1 = parseInt(id)-2;
										aux2 = parseInt(id)+1;
									}
									else
									{
										aux1 = parseInt(id)-3;
										aux2 = parseInt(id);
									}
								}
								else if(producto == 41)
								{
									cantidad_aux = cantidad / 3;
									cantidad_aux2 = idEvento % 3;
									if(cantidad_aux2 == 1)
									{
										aux1 = parseInt(id);
										aux2 = parseInt(id)+2;
									}
									else if(cantidad_aux2 == 2)
									{
										aux1 = parseInt(id)-1;
										aux2 = parseInt(id)+1;
									}
									else
									{
										aux1 = parseInt(id)-2;
										aux2 = parseInt(id);
									}
								}
								else if(producto == 42)
								{
									cantidad_aux = cantidad / 2;
									cantidad_aux2 = idEvento % 2;
									if(cantidad_aux2 == 1)
									{
										aux1 = parseInt(id)-1;
										aux2 = parseInt(id);
									}
									else
									{
										aux1 = parseInt(id);
										aux2 = parseInt(id)+1;
									}
								}
								tema = tema.substr(0,5);
								for(i = aux1; i <= aux2; i++)
								{
									 var temaAux = $('#tema'+i).val();
								    temaAux = temaAux.substr(0,5);
									if(tema == temaAux)
									{
										$('#sede'+i).val(sede);
										$('#fecha_realizacion_1_'+i).hide();
										$('#fecha_realizacion_1_'+i).val('');
										$('#fecha_realizacion_2_'+i).show();
										$('#fecha_realizacion_2_'+i).val(fecha);
										$('#fecha_realizacion_3_'+i).hide();
										$('#fecha_realizacion_3_'+i).val('');
										$('#radio-fecharealizacion_2_'+i).attr("checked",true);
										
										$('.ejecutar'+i).val(month);
										$('.costear'+i).val(month+1);
										$('.facturar'+i).val(month+1);
									}
								}
							}
						});
					
					$('#<?php print($variable_fecha_realizacion_horario2);?>').timepicker({
						minuteStep: 5,
						showSeconds: true,
						showMeridian: false
					});
					
					$('#<?php print($variable_fecha_cobranza);?>').bsdatepicker({
							//startDate: moment(),
							format: 'yyyy-mm-dd'
						})
						.on('changeDate', function(ev){
							var date = $('#<?php print($variable_fecha_cobranza);?>').val();
							var month = date.substring(5,7);
							month = parseInt(month);
							$('.<?php print($variable_cobrar_class);?>').val(month);
						});
						
					//SCRIPT PARA COPIAR AGENTES
					$( "#<?php print($variable_agente); ?>" ).change(function() 
					{
						var id = $('#<?php print($variable_agente);?>').attr('id');
						id = id.replace("agente0_","");
						var agente = $('#<?php print($variable_agente);?>').val();
						var producto = $('#producto'+id).val();
						if(producto == 40 || producto == 41 || producto == 42)
						{
							var cantidad = $('#auxiliar_tipoFDP_cantidad'+id).val();
							var idEvento = parseInt($('#evento'+id).val());
							var cantidad_aux = 0;
							var cantidad_aux2 = 0;
							var aux1 = 0;
							var aux2 = 0;
							if(producto == 40)
							{
								cantidad_aux = cantidad / 4;
								cantidad_aux2 = idEvento % 4;
								if(cantidad_aux2 == 1)
								{
									aux1 = parseInt(id);
									aux2 = parseInt(id)+3;
								}
								else if(cantidad_aux2 == 2)
								{
									aux1 = parseInt(id)-1;
									aux2 = parseInt(id)+2;
								}
								else if(cantidad_aux2 == 3)
								{
									aux1 = parseInt(id)-2;
									aux2 = parseInt(id)+1;
								}
								else
								{
									aux1 = parseInt(id)-3;
									aux2 = parseInt(id);
								}
							}
							else if(producto == 41)
							{
								cantidad_aux = cantidad / 3;
								cantidad_aux2 = idEvento % 3;
								if(cantidad_aux2 == 1)
								{
									aux1 = parseInt(id);
									aux2 = parseInt(id)+2;
								}
								else if(cantidad_aux2 == 2)
								{
									aux1 = parseInt(id)-1;
									aux2 = parseInt(id)+1;
								}
								else
								{
									aux1 = parseInt(id)-2;
									aux2 = parseInt(id);
								}
							}
							else if(producto == 42)
							{
								cantidad_aux = cantidad / 2;
								cantidad_aux2 = idEvento % 2;
								if(cantidad_aux2 == 1)
								{
									aux1 = parseInt(id)-1;
									aux2 = parseInt(id);
								}
								else
								{
									aux1 = parseInt(id);
									aux2 = parseInt(id)+1;
								}
							}
							for(i = aux1; i <= aux2; i++)
							{
								$('#agente0_'+i).val(agente);
							}
						}
					});
					});
				</script>
				<?php
					if($row['id_subproducto'] == 57)
					{
						$readOnlyCostos = "";
					}
					else
					{
						$readOnlyCostos = "readonly";
					}
				?>
				<tr id="tr" name="tr" class="">
					<input type="text" id="<?php print($variable_id);?>" name="<?php print($variable_id);?>" value="<?php print($results['id_detallebitacora']);?>" style="display:none;" />
					<input type="text" id="<?php print($variable_version);?>" name="<?php print($variable_version);?>" value="<?php print($results['version']);?>" style="display:none;" />
					<td id="txt_cancelar">
						<input type="checkbox" class="form-control" name="<?php print($variable_cancelar);?>" id="<?php print($variable_cancelar);?>" value="<?php print($results['id_detallebitacora']);?>">
					</td>
					<td id="txt_id_evento">
						<input type="hidden" class="form-control" name="<?php print($variable_total_agentes);?>" id="<?php print($variable_total_agentes);?>" value="<?php print($totalAgentes);?>">
						<input type="text" class="form-control" name="<?php print($variable_id_evento);?>" id="<?php print($variable_id_evento);?>" value="<?php print($results['id_del_evento']);?>">
					</td>
					<td id="txt_evento">
						<input type="text" class="form-control" name="<?php print($variable_evento);?>" id="<?php print($variable_evento);?>" value="<?php print($contador);?>" readonly>
					</td>
					<td id="txt_personas">
						<input type="text" class="form-control" name="<?php print($variable_personas);?>" id="<?php print($variable_personas);?>" value="<?php print($results['no_personas']);?>">
					</td>
					<td id="txt_nivel">
						<select name="<?php print($variable_nivel);?>" id="<?php print($variable_nivel);?>" class="form-control">
							<option value="<?php print($results['id_nivel']);?>" selected><?php print($results['nivel']);?></option>
							<option value="1">Operativo</option>
							<option value="2">Mando medio</option>
							<option value="3">Directivo</option>
							<option value="4">Otro</option>
						</select>
					</td>
					<td id="update_select_familia">
						<select readonly id="<?php print($variable_familia);?>" name="<?php print($variable_familia);?>" class="form-control">
							<option value="<?php print($results['id_familiaproducto']);?>" selected><?php print($results['familia_producto']);?></option>
						</select>
					</td>
					<td id="update_select_producto">
						<select readonly id="<?php print($variable_producto);?>" name="<?php print($variable_producto);?>" class="form-control">
							<option value="<?php print($results['id_producto']);?>" selected><?php print($results['nombre_producto']);?></option>
						</select>
					</td>
					<td id="update_select_subproducto">
						<select readonly id="<?php print($variable_subproducto);?>" name="<?php print($variable_subproducto);?>" class="form-control">
							<option value="<?php print($results['id_subproducto']);?>" selected><?php print($results['nombre_subproducto']);?></option>
						</select>
						<input type="hidden" class="form-control" name="<?php print($variable_auxiliar_tipoFDP);?>" id="<?php print($variable_auxiliar_tipoFDP);?>" value="<?php print($variable_auxiliar_tipoFDP_valor);?>" readonly>
						<input type="hidden" class="form-control" name="<?php print($variable_auxiliar_tipoFDP_cantidad);?>" id="<?php print($variable_auxiliar_tipoFDP_cantidad);?>" value="<?php print($contador);?>" readonly>
					</td>
					<td id="txt_tema">
						<input type="text" onkeyup="getIDEvento(this);" class="form-control" name="<?php print($variable_tema);?>" id="<?php print($variable_tema);?>" value="<?php print($results['tema']);?>" >
					</td>
					<?php
					if($results['id_subproducto'] == 129)
					{
					?>
					<td id="txt_tipoagente">
						<select id="<?php print($variable_tipoagente);?>" name="<?php print($variable_tipoagente);?>" class="form-control">
							<?php $this->selectTipoAgenteUpdate($results['id_subproducto']);?>
						</select>
						<input type='hidden' id='detalleAgente0_<?php print($contador); ?>' name='detalleAgente0_<?php print($contador); ?>' value='<?php print($row3['id_detallebitacora_agente']); ?>' />
						<div class='col-sm-12 sensibilizacion'><label class='radio-inline'><input onclick='getSensibilizacion(this); getPercentage(this);' type='radio' id='sensibilizacion<?php print($contador); ?>' name='sensibilizacion<?php print($contador); ?>' value='1' checked>Apoyo</label><input onclick='getSensibilizacion(this); getPercentage(this);' type='radio' id='sensibilizacion<?php print($contador); ?>' name='sensibilizacion<?php print($contador); ?>' value='0'>Coordinador</label><label class='radio-inline'><input onclick='getSensibilizacion(this); getPercentage(this);' type='radio' id='sensibilizacion<?php print($contador); ?>' name='sensibilizacion<?php print($contador); ?>' value='2'>Ambos</label></div>
					</td>
					<td id="txt_agente">
						<select id="<?php print($variable_agente);?>" name="<?php print($variable_agente);?>" class="form-control">
							<option value="" selected>Selecciona un agente</option>
						</select>
					</td>
					<?php
					}
					elseif($results['id_subproducto'] == 128)
					{
					?>
					<td id="txt_tipoagente">
						<select id="<?php print($variable_tipoagente);?>" name="<?php print($variable_tipoagente);?>" class="form-control">
							<?php $this->selectTipoAgenteUpdate($results['id_subproducto']);?>
						</select>
						<input type='hidden' id='detalleAgente0_<?php print($contador); ?>' name='detalleAgente0_<?php print($contador); ?>' value='<?php print($row3['id_detallebitacora_agente']); ?>' />
						<div class='col-sm-12 sensibilizacion'><label class='radio-inline'><input onclick='getSensibilizacionPleno(this); getPercentage(this);' type='radio' id='sensibilizacion<?php print($contador); ?>' name='sensibilizacion<?php print($contador); ?>' value='1' checked>Confer.</label><input onclick='getSensibilizacionPleno(this); getPercentage(this);' type='radio' id='sensibilizacion<?php print($contador); ?>' name='sensibilizacion<?php print($contador); ?>' value='0'>Conduc.</label><label class='radio-inline'><input onclick='getSensibilizacionPleno(this); getPercentage(this);' type='radio' id='sensibilizacion<?php print($contador); ?>' name='sensibilizacion<?php print($contador); ?>' value='2'>Apoyo</label> <label class='radio-inline'><input onclick='getSensibilizacionPleno(this); getPercentage(this);' type='radio' id='sensibilizacion<?php print($contador); ?>' name='sensibilizacion<?php print($contador); ?>' value='3'>Todos</label> </div>
					</td>
					<td id="txt_agente">
						<select id="<?php print($variable_agente);?>" name="<?php print($variable_agente);?>" class="form-control">
							<option value="" selected>Selecciona un agente</option>
						</select>
					</td>
					<?php
					}
					else
					{
					?>
					<td id="txt_tipoagente">
						<select id="<?php print($variable_tipoagente);?>" name="<?php print($variable_tipoagente);?>" class="form-control" readonly>
							<option value="<?php print($row3['id_tipoagente']);?>" selected><?php print($row3['tipo_agente']);?></option>
						</select>
						<input type='hidden' id='detalleAgente0_<?php print($contador); ?>' name='detalleAgente0_<?php print($contador); ?>' value='<?php print($row3['id_detallebitacora_agente']); ?>' />
					</td>
					<td id="txt_agente">
						<select id="<?php print($variable_agente);?>" name="<?php print($variable_agente);?>" class="form-control" readonly>
							<option value="<?php print($row3['id_agente']);?>" selected><?php print($row3['nombre_agente']);?></option>
						</select>
					</td>
					<?php
					}
					?>
					<td id="txt_equipo">
						<select name="<?php print($variable_equipo);?>" id="<?php print($variable_equipo);?>" class="form-control">
							<option value="<?php print($results['id_tipoequipo']); ?>" selected><?php print($results['tipo_equipo']); ?></option>
							<option value="1">Estándar</option>
							<option value="2">Adicional</option>
						</select>
					</td>
					<td id="txt_material">
						<select name="<?php print($variable_material);?>" id="<?php print($variable_material);?>" class="form-control">
							<option value="<?php print($results['id_tipomaterial']); ?>" selected><?php print($results['tipo_material']); ?></option>
							<option value="1">Estándar</option>
							<option value="2">Adicional</option>
						</select>
					</td>
					<td id="txt_precio">
						<input type="text" class="form-control" name="<?php print($variable_precio);?>" id="<?php print($variable_precio);?>" value="<?php print(number_format($results['precio_bitacora'],2));?>" readonly>
					</td>
					<td id="txt_descuento">	
						<input id="<?php print($variable_descuento);?>" name="<?php print($variable_descuento);?>" type="text" class="form-control" value="<?php print(round($results['descuento']*100,2)) ;?>" readonly>
					</td>
					<td id="txt_honorarios">
						<input <?php print($readOnlyCostos); ?> type="text" class="form-control" name="<?php print($variable_honorarios_aux);?>" id="<?php print($variable_honorarios_aux);?>" value="<?php print(number_format($row3['costo'],2));?>" readonly>
						<input type="hidden" class="form-control" name="<?php print($variable_honorarios);?>" id="<?php print($variable_honorarios);?>" value="<?php print(number_format($results['costo_honorarios'],2));?>" readonly>
					</td>
					<td id="txt_porcentajehonorarios">
						<input type="text" class="form-control" name="porcentajehonorarios0_<?php print($contador); ?>" id="porcentajehonorarios0_<?php print($contador); ?>" value="<?php print(round(($results['porcentajeHonorarios'])*100,2));?>%" readonly>
					</td>
					<?php
						if($results['costo_materiales'] == 0)
						{
							$noCostosRadio = "checked";
							$siCostosRadio = "";
						}
						else
						{
							$noCostosRadio = "";
							$siCostosRadio = "checked";
						}    
					?>
					<td id="txt_costomateriales">
						<input <?php print($readOnlyCostos); ?> type="text" class="form-control" name="<?php print($variable_costomateriales);?>" id="<?php print($variable_costomateriales);?>" value="<?php print(number_format($results['costo_materiales'],2));?>" readonly>
						<div class="col-sm-6">
							<label class="radio-inline">
								<input onclick="getPercentage(this);" type="radio" id="<?php print($variable_name_radio_button_costomaterial1);?>" name="<?php print($variable_name_radio_button_costomaterial);?>" value="1" onclick="" <?php print($siCostosRadio); ?> >
								Aplicar costo
							</label>
						</div>
						<div class="col-sm-6">
							<label class="radio-inline">
								<input onclick="getPercentage(this);" type="radio" id="<?php print($variable_name_radio_button_costomaterial2);?>" name="<?php print($variable_name_radio_button_costomaterial);?>" value="0" onclick="" <?php print($noCostosRadio); ?>>
								No aplicar costo
							</label>
						</div>
					</td>
					<td id="txt_porcentajemateriales">
						<input type="text" class="form-control" name="" id="" value="<?php print(round(($results['porcentajeMateriales'])*100,2));?>%" readonly>
					</td>
					<td id="txt_costoadicionales">
						<input type="text" class="form-control" name="<?php print($variable_costosadicionales);?>" id="<?php print($variable_costosadicionales);?>" value="<?php print(number_format($results['costo_adicionales'],2));?>" onkeyup="getCommas(this); getPercentage(this);">
					</td>
					<td id="txt_porcentajecostoadicionales">
						<input type="text" class="form-control" name="<?php print($variable_porcentajecostosadicionales);?>" id="<?php print($variable_porcentajecostosadicionales);?>" value="<?php print(round($results['porcentajecosto_adicionales']*100,2));?>%" readonly>
					</td>
					<td id="update_txt_costo">
						<input type="text" class="form-control" name="<?php print($variable_costo);?>" id="<?php print($variable_costo);?>" value="<?php print(number_format($results['costo_bitacora'],2));?>" readonly>
					</td>
					<td id="txt_porcentajecosto">
						<input type="text" class="form-control" name="" id="" value="<?php print(round(($results['porcentajeBitacora'])*100,2));?>%" readonly>
					</td>
					<td id="txt_margen">
						<input type="text" class="form-control" name="" id="" value="<?php print(number_format($results['precio_bitacora']-$results['costo_bitacora'],2));?>" readonly>
					</td>
					<td id="txt_porcentajemargen">
						<input type="text" class="form-control" name="" id="" value="<?php print(round(($results['porcentajeMargen'])*100,2));?>%" readonly>
					</td>
					<?php
						if($results['id_estatusejecucion'] == 1)
						{
					?>
					<td id="txt_sede">
						<select readonly id="<?php print($variable_sede); ?>" name="<?php print($variable_sede); ?>" class="form-control">
							<option value="<?php print($results['id_estado']);?>" selected><?php print($results['estado']);?></option>
						</select>
					</td>
					<td id="txt_sede_particular">
						<input type="text" class="form-control" name="<?php print($variable_sede_particular);?>" value="<?php print($results['sede_particular']); ?>" id="<?php print($variable_sede_particular);?>" readonly>
					</td>
					<td id="txt_fecharealizacion">
						<input readonly id="<?php print($variable_fecha_realizacion1);?>" name="<?php print($variable_fecha_realizacion1);?>" value="<?php print($results['fecha_ejecucion']);?>" type="text" class="form-control <?php print($variable_class_fecha_realizacion1);?>" placeholder="dd-mm-yyyy">
						<div class="">
							<label class="radio-inline">
								<input type="radio" id="<?php print($variable_radio_button1);?>" name="<?php print($variable_name_radio_button);?>" value="1" checked="checked">
								Fecha(s)
							</label>
						</div>
					</td>
					<td id="txt_mes_ejecucion">
						<select readonly name="<?php print($variable_mes_ejecucion);?>" id="<?php print($variable_mes_ejecucion);?>" class="form-control <?php print($variable_ejecutar_class); ?>">
							<option value="<?php print($results['id_mesejecucion']);?>" selected><?php print($results['mes_ejecucion']);?></option>
						</select>
					</td>
					<td id="txt_estatus_ejecucion">
						<select readonly id="<?php print($variable_estatus_ejecucion);?>" name="<?php print($variable_estatus_ejecucion);?>" class="form-control">
							<option value="<?php print($results['id_estatusejecucion']);?>" selected><?php print($results['estatus_ejecucion']);?></option>
						</select>
					</td>
					<?php
						}
						else
						{
					?>
					<td id="txt_sede">
						<select id="<?php print($variable_sede); ?>" name="<?php print($variable_sede); ?>" class="form-control">
						<option value="<?php print($results['id_estado']);?>" selected><?php print($results['estado']);?></option>
							<?php $this->getEstados(); ?>
						</select>
					</td>
					<td id="txt_sede_particular">
						<input type="text" class="form-control" name="<?php print($variable_sede_particular);?>" value="<?php print($results['sede_particular']); ?>" id="<?php print($variable_sede_particular);?>">
					</td>
					<td id="txt_fecharealizacion">
						<input id="<?php print($variable_fecha_realizacion1);?>" name="<?php print($variable_fecha_realizacion1);?>" value="<?php print($results['fecha_ejecucion']);?>" type="text" class="form-control <?php print($variable_class_fecha_realizacion1);?>" placeholder="dd-mm-yyyy">
						<input id="<?php print($variable_fecha_realizacion2);?>" name="<?php print($variable_fecha_realizacion2);?>" value="<?php print($results['fecha_ejecucion']);?>" type="text" class="form-control <?php print($variable_class_fecha_realizacion2);?>" placeholder="dd-mm-yyyy" style="display:none;">
						<input id="<?php print($variable_fecha_realizacion_horario2);?>" name="<?php print($variable_fecha_realizacion_horario2);?>" value="00:00:00" type="text" class="form-control <?php print($variable_class_fecha_realizacion2);?>" placeholder="hh:mm" style="display:none;">
						<input id="<?php print($variable_fecha_realizacion3);?>" name="<?php print($variable_fecha_realizacion3);?>" value="<?php print($results['fecha_ejecucion']);?>" type="text" class="form-control <?php print($variable_class_fecha_realizacion3);?>" placeholder="dd-mm-yyyy" style="display:none;">
						<div class="">
							<label class="radio-inline">
								<input type="radio" id="<?php print($variable_radio_button1);?>" name="<?php print($variable_name_radio_button);?>" value="1" checked="checked">
								Juntas
							</label>
							<label class="radio-inline">
								<input type="radio" id="<?php print($variable_radio_button2);?>" name="<?php print($variable_name_radio_button);?>" value="2">
								Ordenadas
							</label>
						</div>
						<div class="">
							<label class="radio-inline">
								<input type="radio" id="<?php print($variable_radio_button3);?>" name="<?php print($variable_name_radio_button);?>" value="3">
								Separadas
							</label>
						</div>
					</td>
					<td id="txt_mes_ejecucion">
						<select name="<?php print($variable_mes_ejecucion);?>" id="<?php print($variable_mes_ejecucion);?>" class="form-control <?php print($variable_ejecutar_class); ?>">
							<option value="<?php print($results['id_mesejecucion']);?>" selected><?php print($results['mes_ejecucion']);?></option>
							<option value="1">Enero</option>
							<option value="2">Febrero</option>
							<option value="3">Marzo</option>
							<option value="4">Abril</option>
							<option value="5">Mayo</option>
							<option value="6">Junio</option>
							<option value="7">Julio</option>
							<option value="8">Agosto</option>
							<option value="9">Septiembre</option>
							<option value="10">Octubre</option>
							<option value="11">Noviembre</option>
							<option value="12">Diciembre</option>
						</select>
					</td>
					<td id="txt_estatus_ejecucion">
						<select id="<?php print($variable_estatus_ejecucion);?>" name="<?php print($variable_estatus_ejecucion);?>" class="form-control">
							<option value="<?php print($results['id_estatusejecucion']);?>" selected><?php print($results['estatus_ejecucion']);?></option>
							<option value="1">Ejecutado</option>
							<option value="2">Por ejecutar</option>
						</select>
					</td>
					<?php
						}
						if($results['id_estatuscosto'] == 1)
						{
					?>
					<td id="txt_estatus_costo">
						<select readonly id="<?php print($variable_estatus_costo);?>" name="<?php print($variable_estatus_costo);?>" class="form-control">
							<option value="<?php print($results['id_estatuscosto']);?>" selected><?php print($results['estatus_costo']);?></option>
						</select>
					</td>
					<td id="txt_mes_costo">
						<select readonly name="<?php print($variable_mes_costo);?>" id="<?php print($variable_mes_costo);?>" class="form-control <?php print($variable_costear_class); ?>">
							<option value="<?php print($results['id_mescosto']);?>" selected><?php print($results['mes_costo']);?></option>
						</select>
					</td>			
					<?php
						}
						else
						{
					?>
					<td id="txt_estatus_costo">
						<select id="<?php print($variable_estatus_costo);?>" name="<?php print($variable_estatus_costo);?>" class="form-control">
							<option value="<?php print($results['id_estatuscosto']);?>" selected><?php print($results['estatus_costo']);?></option>
							<option value="1">Pagado</option>
							<option value="2">Por pagar</option>
						</select>
					</td>
					<td id="txt_mes_costo">
						<select name="<?php print($variable_mes_costo);?>" id="<?php print($variable_mes_costo);?>" class="form-control <?php print($variable_costear_class); ?>">
							<option value="<?php print($results['id_mescosto']);?>" selected><?php print($results['mes_costo']);?></option>
							<option value="1">Enero</option>
							<option value="2">Febrero</option>
							<option value="3">Marzo</option>
							<option value="4">Abril</option>
							<option value="5">Mayo</option>
							<option value="6">Junio</option>
							<option value="7">Julio</option>
							<option value="8">Agosto</option>
							<option value="9">Septiembre</option>
							<option value="10">Octubre</option>
							<option value="11">Noviembre</option>
							<option value="12">Diciembre</option>
						</select>
					</td>
					<?php		
						}
					?>
					<td id="txt_anomalias">
						<textarea rows="3" class="form-control textarea-autosize" id="<?php print($variable_anomalia);?>" name="<?php print($variable_anomalia);?>"><?php print($results['anomalia']);?></textarea>
					</td>
					<?php
						if($results['id_estatusfacturacion'] == 1)
						{
					?>
					<td id="txt_estatus_factura">
						<select readonly id="<?php print($variable_estatus_factura);?>" name="<?php print($variable_estatus_factura);?>" class="form-control">
							<option value="<?php print($results['id_estatusfacturacion']);?>" selected><?php print($results['estatus_facturacion']);?></option>
						</select>
					</td>
					<td id="txt_mes_factura">
						<select readonly name="<?php print($variable_mes_factura);?>" id="<?php print($variable_mes_factura);?>" class="form-control <?php print($variable_facturar_class); ?>">
							<option value="<?php print($results['id_mesfacturacion']);?>" selected><?php print($results['mes_facturacion']);?></option>
						</select>
					</td>
					<td id="txt_factura">
						<input type="text" class="form-control" name="<?php print($variable_factura);?>" value="<?php print($results['numero_factura']);?>" id="<?php print($variable_factura);?>">
					</td>
					<?php		
						}
						else
						{
					?>
					<td id="txt_estatus_factura">
						<select id="<?php print($variable_estatus_factura);?>" name="<?php print($variable_estatus_factura);?>" class="form-control">
							<option value="<?php print($results['id_estatusfacturacion']);?>" selected><?php print($results['estatus_facturacion']);?></option>
							<option value="1">Facturado</option>
							<option value="2">Por facturar</option>
						</select>
					</td>
					<td id="txt_mes_factura">
						<select name="<?php print($variable_mes_factura);?>" id="<?php print($variable_mes_factura);?>" class="form-control <?php print($variable_facturar_class); ?>">
							<option value="<?php print($results['id_mesfacturacion']);?>" selected><?php print($results['mes_facturacion']);?></option>
							<option value="1">Enero</option>
							<option value="2">Febrero</option>
							<option value="3">Marzo</option>
							<option value="4">Abril</option>
							<option value="5">Mayo</option>
							<option value="6">Junio</option>
							<option value="7">Julio</option>
							<option value="8">Agosto</option>
							<option value="9">Septiembre</option>
							<option value="10">Octubre</option>
							<option value="11">Noviembre</option>
							<option value="12">Diciembre</option>
						</select>
					</td>
					<td id="txt_factura">
						<input type="text" class="form-control" name="<?php print($variable_factura);?>" value="<?php print($results['numero_factura']);?>" id="<?php print($variable_factura);?>">
					</td>
					<?php		
						}
						if($results['id_estatuscobranza'] == 1)
						{
					?>
					<td id="txt_estatus_cobranza">
						<select readonly id="<?php print($variable_estatus_cobranza);?>" name="<?php print($variable_estatus_cobranza);?>" class="form-control">
							<option value="<?php print($results['id_estatuscobranza']);?>" selected><?php print($results['estatus_cobranza']);?></option>
						</select>
					</td>
					<td id="txt_fecha_cobranza">
						<input id="<?php print($variable_fecha_cobranza);?>" name="<?php print($variable_fecha_cobranza);?>" value="<?php print($results['fecha_cobranza']);?>" type="text" class="form-control" placeholder="dd-mm-yyyy"readonly>
					</td>
					<td id="txt_mes_cobranza">
						<select readonly name="<?php print($variable_mes_cobranza);?>" id="<?php print($variable_mes_cobranza);?>" class="form-control <?php print($variable_cobrar_class); ?>">
							<option value="<?php print($results['id_mescobranza']);?>" selected><?php print($results['mes_cobranza']);?></option>
						</select>
					</td>
					<?php		
						}
						else
						{
							if($results['fecha_cobranza'] == "0000-00-00")	
							{
								$results['fecha_cobranza'] = date("Y")."-01-01";
							}
					?>
					<td id="txt_estatus_cobranza">
						<select id="<?php print($variable_estatus_cobranza);?>" name="<?php print($variable_estatus_cobranza);?>" class="form-control">
							<option value="<?php print($results['id_estatuscobranza']);?>" selected><?php print($results['estatus_cobranza']);?></option>
							<option value="1">Cobrado</option>
							<option value="2">Por cobrar</option>
							<option value="3">Ingresada a plataforma</option>
							<option value="4">Monitoreo</option>
						</select>
					</td>
					<td id="txt_fecha_cobranza">
						<input id="<?php print($variable_fecha_cobranza);?>" name="<?php print($variable_fecha_cobranza);?>" value="<?php print($results['fecha_cobranza']);?>" type="text" class="form-control" placeholder="dd-mm-yyyy">
					</td>
					<td id="txt_mes_cobranza">
						<select name="<?php print($variable_mes_cobranza);?>" id="<?php print($variable_mes_cobranza);?>" class="form-control <?php print($variable_cobrar_class); ?>">
							<option value="<?php print($results['id_mescobranza']);?>" selected><?php print($results['mes_cobranza']);?></option>
							<option value="1">Enero</option>
							<option value="2">Febrero</option>
							<option value="3">Marzo</option>
							<option value="4">Abril</option>
							<option value="5">Mayo</option>
							<option value="6">Junio</option>
							<option value="7">Julio</option>
							<option value="8">Agosto</option>
							<option value="9">Septiembre</option>
							<option value="10">Octubre</option>
							<option value="11">Noviembre</option>
							<option value="12">Diciembre</option>
						</select>
					</td>
					<?php		
						}
						if($results['viaticos'] == 1)
						{
					?>
					<td id="txt_viaticos">
						<input checked type="checkbox" class="form-control" name="<?php print($variable_viaticos);?>" id="<?php print($variable_viaticos);?>" value="1" onclick="getReadonly(this);">
					</td>
					<?php
						}
						else
						{
					?>
					<td id="txt_viaticos">
						<input type="checkbox" class="form-control" name="<?php print($variable_viaticos);?>" id="<?php print($variable_viaticos);?>" value="0" onclick="getReadonly(this);">
					</td>
					<?php
						}
					?>
				</tr>
					<?php	
						if($comprobacion)
						{
							$elementos = count($arrayAux);
							for($j = 1; $j < $elementos; $j++)
							{
							   if($results['precio_bitacora'] == 0)
							   {
							      $porcentajeHon = 0;
							   }
							   else
							   {
							      $porcentajeHon = $arrayAux6[$j]/$results['precio_bitacora'];
							   }
					?>
				<tr class="" style="<?php print($styleHidden);?>">
					<td id=""><input type="checkbox" class="form-control" name="" id="" value="<?php print($results['id_detallebitacora']);?>"></td>
					<td id=""><input type="hidden" class="form-control" name="" id="" value="<?php print($contador);?>" readonly></td>
					<td id=""></td>
					<td id=""></td>
					<td id=""></td>
					<td id=""></td>
					<td id=""></td>
					<td id=""></td>
					<td id=""></td>
					<td id="txt_tipoagente_v2">
						<select id="<?php print('tipoagente'.$j.'_'.$contador);?>" name="<?php print('tipoagente'.$j.'_'.$contador);?>" class="form-control">
							<option value="<?php print($arrayAux[$j]);?>"><?php print($arrayAux2[$j]);?></option>
						</select>
						<input type='hidden' id='detalleAgente<?php print($j.'_'.$contador); ?>' name='detalleAgente<?php print($j.'_'.$contador); ?>' value='<?php print($arrayAux7[$j]); ?>' />
					</td>
					<td disabled id="txt_agente_v2">
						<select id="<?php print('agente'.$j.'_'.$contador);?>" name="<?php print('agente'.$j.'_'.$contador);?>" class="form-control">
							<option selected value="<?php print($arrayAux3[$j]);?>"><?php print($arrayAux4[$j].' '.$arrayAux5[$j]);?></option>
						</select>
					</td>
					<td id=""></td>
					<td id=""></td>
					<td id=""></td>
					<td id=""></td>
					<td id="">
						<input type="text" class="form-control" name="<?php print('honorarios'.$j.'_'.$contador);?>" id="<?php print('honorarios'.$j.'_'.$contador);?>" value="<?php print(number_format($arrayAux6[$j],2));?>" readonly>
					</td>
					<td id="">
						<input type="text" class="form-control" name="porcentajehonorarios<?php print($j.'_'.$contador); ?>" id="porcentajehonorarios<?php print($j.'_'.$contador); ?>" value="<?php print(round($porcentajeHon,4)*100);?>%" readonly>
					</td>
					<td id=""></td>
					<td id=""></td>
					<td id=""></td>
					<td id=""></td>
					<td id=""></td>
					<td id=""></td>
					<td id=""></td>
					<td id=""></td>
					<td id=""></td>
					<td id=""></td>
					<td id=""></td>													
					<td id=""></td>
					<td id=""></td>
					<td id=""></td>
					<td id=""></td>
					<td id=""></td>
					<td id=""></td>
					<td id=""></td>
					<td id=""></td>
					<td id=""></td>
					<td id=""></td>
					<td id=""></td>
					<td id=""></td>
				</tr>
				<?php
							}
						}
						$contador++;
					}
				}
				?>
				<input id="contador" name="contador" value="<?php print($contador-1);?>" type="text" hidden />
				<?php
			}
		}
		//YA NO SE UTILIZA//
		/*
		public function showOIDetalleB1($ordenes)
		{
			$stmt = $this->conn->prepare("SELECT * FROM  T_Detalle_Bitacora AS DB
				INNER JOIN T_Subproductos AS Sub
				ON DB.id_subproducto = Sub.id_subproducto
				INNER JOIN T_Productos AS Prod
				ON Sub.id_producto = Prod.id_producto
				INNER JOIN T_FamiliaProductos AS Fam
				ON Prod.id_familiaproducto = Fam.id_familiaproducto
				WHERE DB.id_bitacora = :ordenes");
			$stmt->execute(array(':ordenes'=>$ordenes));
			
			$contador = 1;
			
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				$id_detalles = [];
				$id_detalles[$contador] = $row['id_detalleoi'];
				$variable_detalle = 'modificar'.$contador;
				$variable_eliminar = 'eliminar'.$contador;
				$variable_familia = 'update_familia'.$contador;
				$variable_producto = 'update_producto'.$contador;
				$variable_subproducto = 'subproducto'.$contador;
				$variable_tema = 'tema'.$contador;
				$variable_id = 'id_detallebitacora'.$contador;
			?>
			<tr id="tr" name="tr" class="">
				<input type="text" id="<?php print($variable_id);?>" name="<?php print($variable_id);?>" value="<?php print($row['id_detallebitacora']);?>" hidden/>
				<td id="txt_evento">
					<input type="text" class="form-control" name="" id="" value="<?php print($row['id_del_evento']);?>" readonly>
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
				<td id="txt_tema">
					<input type="text" class="form-control" name="<?php print($variable_tema);?>" id="<?php print($variable_tema);?>" value="<?php print($row['tema']);?>" >
				</td>
			</tr>
			<?php
				$contador++;
			}
		}
		public function showOIDetalleB2($ordenes)
		{
			$stmt = $this->conn->prepare("SELECT * FROM  T_Detalle_Bitacora AS DB
				INNER JOIN T_Subproductos AS Sub
				ON DB.id_subproducto = Sub.id_subproducto
				INNER JOIN T_Productos AS Prod
				ON Sub.id_producto = Prod.id_producto
				INNER JOIN T_FamiliaProductos AS Fam
				ON Prod.id_familiaproducto = Fam.id_familiaproducto
				INNER JOIN T_Niveles AS N
				ON N.id_nivel = DB.id_nivel
				WHERE DB.id_bitacora = :ordenes");
			$stmt->execute(array(':ordenes'=>$ordenes));
			
			$contador = 1;
			
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				$variable_evento = 'evento'.$contador;
				$variable_id_evento = 'id_evento'.$contador;
				$variable_personas = 'personas'.$contador;
				$variable_nivel = 'nivel'.$contador;
			?>
			<tr class="">
				<td id="txt_evento">
					<input type="text" class="form-control" name="<?php print($variable_evento);?>" id="<?php print($variable_evento);?>" value="<?php print($contador);?>" readonly>
				</td>
				<td id="txt_id_evento" colspan="5">
					<input type="text" class="form-control" name="<?php print($variable_id_evento);?>" id="<?php print($variable_id_evento);?>" value="<?php print($row['id_del_evento']);?>">
				</td>
				<td id="txt_personas">
					<input type="text" class="form-control" name="<?php print($variable_personas);?>" id="<?php print($variable_personas);?>" value="<?php print($row['no_personas']);?>">
				</td>
				<td id="txt_nivel" colspan="5">
					<select name="<?php print($variable_nivel);?>" id="<?php print($variable_nivel);?>" class="form-control">
						<option value="<?php print($row['id_nivel']);?>" selected><?php print($row['nivel']);?></option>
						<option value="1">Operativo</option>
						<option value="2">Mando medio</option>
						<option value="3">Directivo</option>
						<option value="4">Otro</option>
					</select>
				</td>
			</tr>
			<?php
				$contador++;
			}
		}
		public function showOIDetalleB3($ordenes)
		{
			$stmt = $this->conn->prepare("SELECT * FROM  T_Detalle_Bitacora AS DB
				INNER JOIN T_Subproductos AS Sub
				ON DB.id_subproducto = Sub.id_subproducto
				INNER JOIN T_Productos AS Prod
				ON Sub.id_producto = Prod.id_producto
				INNER JOIN T_FamiliaProductos AS Fam
				ON Prod.id_familiaproducto = Fam.id_familiaproducto
				INNER JOIN T_TipoAgente AS TA
				ON TA.id_tipoagente = DB.id_tipoagente
				INNER JOIN T_Agentes AS A
				ON A.id_agente = DB.id_agente
				INNER JOIN T_TipoEquipo AS TE
				ON TE.id_tipoequipo = DB.id_tipoequipo
				INNER JOIN T_TipoMaterial AS TM
				ON TM.id_tipomaterial = DB.id_tipomaterial
				WHERE DB.id_bitacora = :ordenes");
			$stmt->execute(array(':ordenes'=>$ordenes));
			
			$contador = 1;
			
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{	
				$id_detalles = [];
				$id_detalles[$contador] = $row['id_detalleoi'];
				$variable_detalle = 'modificar'.$contador;
				$variable_eliminar = 'eliminar'.$contador;
				$variable_tipoagente = 'tipoagente'.$contador;
				$variable_agente = 'agente'.$contador;
				$variable_equipo = 'equipo'.$contador;
				$variable_material = 'material'.$contador;
			?>
			<tr class="">
				<td id="txt_evento">
					<input type="text" class="form-control" name="" id="" value="<?php print($row['id_del_evento']);?>" readonly>
				</td>
				<td id="txt_tipoagente" colspan="5">
					<select id="<?php print($variable_tipoagente);?>" name="<?php print($variable_tipoagente);?>" class="form-control" readonly>
						<option value="<?php print($row['id_tipoagente']);?>" selected><?php print($row['tipo_agente']);?></option>
					</select>
				</td>
				<td id="txt_agente" colspan="5">
					<select id="<?php print($variable_agente);?>" name="<?php print($variable_agente);?>" class="form-control" readonly>
						<option value="<?php print($row['id_agente']);?>" selected><?php print($row['nombre_agente']);?></option>
					</select>
				</td>
				<td id="txt_equipo" colspan="5">
					<select name="<?php print($variable_equipo);?>" id="<?php print($variable_equipo);?>" class="form-control">
						<option value="<?php print($row['id_tipoequipo']); ?>" selected><?php print($row['tipo_equipo']); ?></option>
						<option value="1">Estándar</option>
						<option value="2">Adicional</option>
					</select>
				</td>
				<td id="txt_material" colspan="5">
					<select name="<?php print($variable_material);?>" id="<?php print($variable_material);?>" class="form-control">
						<option value="<?php print($row['id_tipomaterial']); ?>" selected><?php print($row['tipo_material']); ?></option>
						<option value="1">Estándar</option>
						<option value="2">Adicional</option>
					</select>
				</td>
			</tr>
			<?php
				$contador++;
			}
		}
		public function showOIDetalleB4($ordenes)
		{
			$stmt = $this->conn->prepare("SELECT * FROM  T_Detalle_Bitacora AS DB
				INNER JOIN T_Subproductos AS Sub
				ON DB.id_subproducto = Sub.id_subproducto
				INNER JOIN T_Productos AS Prod
				ON Sub.id_producto = Prod.id_producto
				INNER JOIN T_FamiliaProductos AS Fam
				ON Prod.id_familiaproducto = Fam.id_familiaproducto
				WHERE DB.id_bitacora = :ordenes");
			$stmt->execute(array(':ordenes'=>$ordenes));
			
			$contador = 1;
			
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				$id_detalles = [];
				$id_detalles[$contador] = $row['id_detalleoi'];
				$variable_detalle = 'modificar'.$contador;
				$variable_eliminar = 'eliminar'.$contador;
				$variable_precio = 'precio'.$contador;
				$variable_descuento = 'descuento'.$contador;
				$variable_costo = 'update_costo'.$contador;
				$variable_honorarios = 'honorarios'.$contador;
				$variable_costomateriales = 'costomateriales'.$contador;
			?>
			<tr class="">
				<td id="txt_evento">
					<input type="text" class="form-control" name="" id="" value="<?php print($row['id_del_evento']);?>" readonly>
				</td>
				<td id="txt_precio" colspan="5">
					<input type="text" class="form-control" name="<?php print($variable_precio);?>" id="<?php print($variable_precio);?>" value="<?php print(number_format($row['precio_bitacora'],2));?>" readonly>
				</td>
				<td id="txt_descuento">	
					<input id="<?php print($variable_descuento);?>" name="<?php print($variable_descuento);?>" type="text" class="form-control" value="<?php print(round($row['descuento']*100,2)) ;?>" readonly>
				</td>
				<td id="txt_honorarios">
					<input type="text" class="form-control" name="<?php print($variable_honorarios);?>" id="<?php print($variable_honorarios);?>" value="<?php print(number_format($row['costo_honorarios'],2));?>" readonly>
				</td>
				<td id="txt_porcentajehonorarios">
					<input type="text" class="form-control" name="" id="" value="<?php print(round(($row['costo_honorarios']/$row['precio_bitacora'])*100,2));?>%" readonly>
				</td>
				<td id="txt_costomateriales">
					<input type="text" class="form-control" name="<?php print($variable_costomateriales);?>" id="<?php print($variable_costomateriales);?>" value="<?php print(number_format($row['costo_materiales'],2));?>" readonly>
				</td>
				<td id="txt_porcentajemateriales">
					<input type="text" class="form-control" name="" id="" value="<?php print(round(($row['costo_materiales']/$row['precio_bitacora'])*100,2));?>%" readonly>
				</td>
				<td id="update_txt_costo" colspan="5">
					<input type="text" class="form-control" name="<?php print($variable_costo);?>" id="<?php print($variable_costo);?>" value="<?php print(number_format($row['costo_bitacora'],2));?>" readonly>
				</td>
				<td id="txt_porcentajemateriales">
					<input type="text" class="form-control" name="" id="" value="<?php print(round(($row['costo_bitacora']/$row['precio_bitacora'])*100,2));?>%" readonly>
				</td>
				<td id="txt_margen" colspan="5">
					<input type="text" class="form-control" name="" id="" value="<?php print(number_format($row['precio_bitacora']-$row['costo_bitacora'],2));?>" readonly>
				</td>
				<td id="txt_porcentajemargen">
					<input type="text" class="form-control" name="" id="" value="<?php print(round((($row['precio_bitacora']-$row['costo_bitacora'])/$row['precio_bitacora'])*100,2));?>%" readonly>
				</td>
			</tr>
			<?php
				$contador++;
			}
			?>
			<input id="contador" name="contador" value="<?php print($contador);?>" type="text" hidden />
			<?php
		}
		public function showOIDetalleB5($ordenes)
		{
			$stmt = $this->conn->prepare("SELECT * FROM  T_Detalle_Bitacora AS DB
				INNER JOIN T_Subproductos AS Sub
				ON DB.id_subproducto = Sub.id_subproducto
				INNER JOIN T_Productos AS Prod
				ON Sub.id_producto = Prod.id_producto
				INNER JOIN T_FamiliaProductos AS Fam
				ON Prod.id_familiaproducto = Fam.id_familiaproducto
				INNER JOIN T_Estatus_Ejecucion AS EE
				ON EE.id_estatusejecucion = DB.id_estatusejecucion
				INNER JOIN T_Mes_Ejecucion AS ME
				ON ME.id_mesejecucion = DB.id_mesejecucion
				WHERE DB.id_bitacora = :ordenes");
			$stmt->execute(array(':ordenes'=>$ordenes));
			
			$contador = 1;
			
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				$id_detalles = [];
				$id_detalles[$contador] = $row['id_detalleoi'];
				$variable_detalle = 'modificar'.$contador;
				$variable_eliminar = 'eliminar'.$contador;
				$variable_fecha_realizacion = 'fecha_realizacion'.$contador;
				$variable_mes_ejecucion = 'mes_ejecucion'.$contador;
				$variable_estatus_ejecucion = 'estatus_ejecucion'.$contador;					
				$variable_anomalia = 'anomalia'.$contador;					
			?>
			<tr class="">
				<td id="txt_evento">
					<input type="text" class="form-control" name="" id="" value="<?php print($row['id_del_evento']);?>" readonly>
				</td>
				<td id="txt_fecharealizacion">
					<input placeholder="dd-mm-yyyy" type="text" class="form-control fecha_realizacion" name="<?php print($variable_fecha_realizacion);?>" value="<?php print($row['fecha_ejecucion']);?>" id="<?php print($variable_fecha_realizacion);?>">
				</td>
				<td id="txt_mes_ejecucion">
					<select name="<?php print($variable_mes_ejecucion);?>" id="<?php print($variable_mes_ejecucion);?>" class="form-control">
						<option value="<?php print($row['id_mesejecucion']);?>" selected><?php print($row['mes_ejecucion']);?></option>
						<option value="1">Enero</option>
						<option value="2">Febrero</option>
						<option value="3">Marzo</option>
						<option value="4">Abril</option>
						<option value="5">Mayo</option>
						<option value="6">Junio</option>
						<option value="7">Julio</option>
						<option value="8">Agosto</option>
						<option value="9">Septiembre</option>
						<option value="10">Octubre</option>
						<option value="11">Noviembre</option>
						<option value="12">Diciembre</option>
					</select>
				</td>
				<td id="txt_estatus_ejecucion">
					<select id="<?php print($variable_estatus_ejecucion);?>" name="<?php print($variable_estatus_ejecucion);?>" class="form-control">
						<option value="<?php print($row['id_estatusejecucion']);?>" selected><?php print($row['estatus_ejecucion']);?></option>
						<option value="1">Ejecutado</option>
						<option value="2">Por ejecutar</option>
					</select>
				</td>
				<td id="txt_anomalias">
					<textarea rows="3" class="form-control textarea-autosize" id="<?php print($variable_anomalia);?>" name="<?php print($variable_anomalia);?>"><?php print($row[anomalia]);?></textarea>
				</td>
			</tr>
			<?php
				$contador++;
			}
		}
		public function showOIDetalleB6($ordenes)
		{
			$stmt = $this->conn->prepare("SELECT * FROM  T_Detalle_Bitacora AS DB
				INNER JOIN T_Subproductos AS Sub
				ON DB.id_subproducto = Sub.id_subproducto
				INNER JOIN T_Productos AS Prod
				ON Sub.id_producto = Prod.id_producto
				INNER JOIN T_FamiliaProductos AS Fam
				ON Prod.id_familiaproducto = Fam.id_familiaproducto
				INNER JOIN T_Estatus_Costos AS EE
				ON EE.id_estatuscosto = DB.id_estatuscosto
				INNER JOIN T_Mes_Costo	AS ME
				ON ME.id_mescosto = DB.id_mescosto
				WHERE DB.id_bitacora = :ordenes");
			$stmt->execute(array(':ordenes'=>$ordenes));
			
			$contador = 1;
			
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{	
				$id_detalles = [];
				$id_detalles[$contador] = $row['id_detalleoi'];
				$variable_detalle = 'modificar'.$contador;
				$variable_eliminar = 'eliminar'.$contador;
				$variable_estatus_costo = 'estatus_costo'.$contador;
				$variable_mes_costo = 'mes_costo'.$contador;					
			?>
			<tr class="">
				<td id="txt_evento">
					<input type="text" class="form-control" name="" id="" value="<?php print($row['id_del_evento']);?>" readonly>
				</td>
				<td id="txt_estatus_costo">
					<select id="<?php print($variable_estatus_costo);?>" name="<?php print($variable_estatus_costo);?>" class="form-control">
						<option value="<?php print($row['id_estatuscosto']);?>" selected><?php print($row['estatus_costo']);?></option>
						<option value="1">Pagado</option>
						<option value="2">Por pagar</option>
					</select>
				</td>
				<td id="txt_mes_costo">
					<select name="<?php print($variable_mes_costo);?>" id="<?php print($variable_mes_costo);?>" class="form-control">
						<option value="<?php print($row['id_mescosto']);?>" selected><?php print($row['mes_costo']);?></option>
						<option value="1">Enero</option>
						<option value="2">Febrero</option>
						<option value="3">Marzo</option>
						<option value="4">Abril</option>
						<option value="5">Mayo</option>
						<option value="6">Junio</option>
						<option value="7">Julio</option>
						<option value="8">Agosto</option>
						<option value="9">Septiembre</option>
						<option value="10">Octubre</option>
						<option value="11">Noviembre</option>
						<option value="12">Diciembre</option>
					</select>
				</td>
			</tr>
			<?php
				$contador++;
			}
		}		
		public function showOIDetalleB7($ordenes)
		{
			$stmt = $this->conn->prepare("SELECT * FROM  T_Detalle_Bitacora AS DB
				INNER JOIN T_Subproductos AS Sub
				ON DB.id_subproducto = Sub.id_subproducto
				INNER JOIN T_Productos AS Prod
				ON Sub.id_producto = Prod.id_producto
				INNER JOIN T_FamiliaProductos AS Fam
				ON Prod.id_familiaproducto = Fam.id_familiaproducto
				INNER JOIN T_Estatus_Facturacion AS EE
				ON EE.id_estatusfacturacion = DB.id_estatusfacturacion
				INNER JOIN T_Mes_Facturacion AS ME
				ON ME.id_mesfacturacion = DB.id_mesfacturacion
				WHERE DB.id_bitacora = :ordenes");
			$stmt->execute(array(':ordenes'=>$ordenes));
			
			$contador = 1;
			
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{	
				$id_detalles = [];
				$id_detalles[$contador] = $row['id_detalleoi'];
				$variable_detalle = 'modificar'.$contador;
				$variable_eliminar = 'eliminar'.$contador;
				$variable_estatus_factura = 'estatus_factura'.$contador;
				$variable_mes_factura = 'mes_factura'.$contador;
				$variable_factura = 'factura'.$contador;
			?>
			<tr class="">
				<td id="txt_evento">
					<input type="text" class="form-control" name="" id="" value="<?php print($row['id_del_evento']);?>" readonly>
				</td>
				<td id="txt_estatus_factura">
					<select id="<?php print($variable_estatus_factura);?>" name="<?php print($variable_estatus_factura);?>" class="form-control">
						<option value="<?php print($row['id_estatusfacturacion']);?>" selected><?php print($row['estatus_facturacion']);?></option>
						<option value="1">Facturado</option>
						<option value="2">Por facturar</option>
					</select>
				</td>
				<td id="txt_mes_factura">
					<select name="<?php print($variable_mes_factura);?>" id="<?php print($variable_mes_factura);?>" class="form-control">
						<option value="<?php print($row['id_mesfacturacion']);?>" selected><?php print($row['mes_facturacion']);?></option>
						<option value="1">Enero</option>
						<option value="2">Febrero</option>
						<option value="3">Marzo</option>
						<option value="4">Abril</option>
						<option value="5">Mayo</option>
						<option value="6">Junio</option>
						<option value="7">Julio</option>
						<option value="8">Agosto</option>
						<option value="9">Septiembre</option>
						<option value="10">Octubre</option>
						<option value="11">Noviembre</option>
						<option value="12">Diciembre</option>
					</select>
				</td>
				<td id="txt_factura">
					<input type="text" class="form-control" name="<?php print($variable_factura);?>" value="" id="<?php print($variable_factura);?>" readonly>
				</td>
			</tr>
			<?php
				$contador++;
			}
		}
		public function showOIDetalleB8($ordenes)
		{
			$stmt = $this->conn->prepare("SELECT * FROM  T_Detalle_Bitacora AS DB
				INNER JOIN T_Subproductos AS Sub
				ON DB.id_subproducto = Sub.id_subproducto
				INNER JOIN T_Productos AS Prod
				ON Sub.id_producto = Prod.id_producto
				INNER JOIN T_FamiliaProductos AS Fam
				ON Prod.id_familiaproducto = Fam.id_familiaproducto
				INNER JOIN T_Estatus_Cobranza AS EE
				ON EE.id_estatuscobranza = DB.id_estatuscobranza
				INNER JOIN T_Mes_Cobranza AS ME
				ON ME.id_mescobranza = DB.id_mescobranza
				WHERE DB.id_bitacora = :ordenes");
			$stmt->execute(array(':ordenes'=>$ordenes));
			
			$contador = 1;
			
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				$id_detalles = [];
				$id_detalles[$contador] = $row['id_detalleoi'];
				$variable_detalle = 'modificar'.$contador;
				$variable_eliminar = 'eliminar'.$contador;
				$variable_estatus_cobranza = 'estatus_cobranza'.$contador;
				$variable_mes_cobranza = 'mes_cobranza'.$contador;
			?>
			<tr class="">
				<td id="txt_evento">
					<input type="text" class="form-control" name="" id="" value="<?php print($row['id_del_evento']);?>" readonly>
				</td>
				<td id="txt_estatus_cobranza">
					<select id="<?php print($variable_estatus_cobranza);?>" name="<?php print($variable_estatus_cobranza);?>" class="form-control">
						<option value="<?php print($row['id_estatuscobranza']);?>" selected><?php print($row['estatus_cobranza']);?></option>
						<option value="1">Cobrado</option>
						<option value="2">Por cobrar</option>
						<option value="3">Ingresada a plataforma</option>
						<option value="4">Monitoreo</option>
					</select>
				</td>
				<td id="txt_mes_cobranza">
					<select name="<?php print($variable_mes_cobranza);?>" id="<?php print($variable_mes_cobranza);?>" class="form-control">
						<option value="<?php print($row['id_mescobranza']);?>" selected><?php print($row['mes_cobranza']);?></option>
						<option value="1">Enero</option>
						<option value="2">Febrero</option>
						<option value="3">Marzo</option>
						<option value="4">Abril</option>
						<option value="5">Mayo</option>
						<option value="6">Junio</option>
						<option value="7">Julio</option>
						<option value="8">Agosto</option>
						<option value="9">Septiembre</option>
						<option value="10">Octubre</option>
						<option value="11">Noviembre</option>
						<option value="12">Diciembre</option>
					</select>
				</td>
			</tr>
			<?php
				$contador++;
			}	
		}		
		*/
		public function selectEjecutados($bitacora)
		{
			$stmt = $this->conn->prepare("SELECT 
				B.id_cliente,
				C.nombre_cliente,
				B.nombre_bitacora,
				SUM(DB.precio_bitacora) as monto
				FROM  T_Detalle_Bitacora AS DB
				INNER JOIN T_Bitacora AS B
				ON B.id_bitacora = DB.id_bitacora
				INNER JOIN T_Clientes AS C
				ON C.id_cliente = B.id_cliente
				INNER JOIN T_Subproductos AS Sub
				ON DB.id_subproducto = Sub.id_subproducto
				INNER JOIN T_Productos AS Prod
				ON Sub.id_producto = Prod.id_producto
				INNER JOIN T_FamiliaProductos AS Fam
				ON Prod.id_familiaproducto = Fam.id_familiaproducto
				INNER JOIN T_Estatus_Ejecucion AS EE
				ON EE.id_estatusejecucion = DB.id_estatusejecucion
				INNER JOIN T_Mes_Ejecucion AS ME
				ON ME.id_mesejecucion = DB.id_mesejecucion
				WHERE DB.id_bitacora = :bitacora AND DB.id_estatusejecucion = 1");
			$stmt->execute(array(':bitacora'=>$bitacora));
			$row=$stmt->fetch(PDO::FETCH_ASSOC);
			return $row;
		}
		public function selectEjecutadosDetalle($bitacora)
		{
			$stmt = $this->conn->prepare("SELECT * FROM  T_Detalle_Bitacora AS DB
				INNER JOIN T_Bitacora AS B
				ON B.id_bitacora = DB.id_bitacora
				INNER JOIN T_Subproductos AS Sub
				ON DB.id_subproducto = Sub.id_subproducto
				INNER JOIN T_Productos AS Prod
				ON Sub.id_producto = Prod.id_producto
				INNER JOIN T_FamiliaProductos AS Fam
				ON Prod.id_familiaproducto = Fam.id_familiaproducto
				INNER JOIN T_Estatus_Ejecucion AS EE
				ON EE.id_estatusejecucion = DB.id_estatusejecucion
				INNER JOIN T_Mes_Ejecucion AS ME
				ON ME.id_mesejecucion = DB.id_mesejecucion
				WHERE DB.id_bitacora = :bitacora AND DB.id_estatusejecucion = 1
				GROUP BY DB.id_detallebitacora");
			$stmt->execute(array(':bitacora'=>$bitacora));
			
			$contador = 1;
			
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				$id_detalles = [];
				$id_detalles[$contador] = $row['id_detallebitacora'];
				$variable_detalle = 'detalle'.$contador;
				$variable_eliminar = 'eliminar'.$contador;
				$variable_familia = 'update_familia'.$contador;
				$variable_producto = 'update_producto'.$contador;
				$variable_subproducto = 'subproducto'.$contador;
				$variable_precio = 'precio'.$contador;
				$variable_pregunta = 'pregunta'.$contador;
				$variable_si = 'si'.$contador;
				$variable_si = 'no'.$contador;
				$variable_tema = 'tema'.$contador;				
			?>
			<tr class="">
				<td id="txt_evento">
					<input type="text" class="form-control" name="" id="" value="<?php print($contador+1);?>" readonly>
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
				<td id="txt_tema" colspan="">
					<input type="text" class="form-control" name="<?php print($variable_tema);?>" id="<?php print($variable_tema);?>" value="<?php print($row['tema']);?>" >
				</td>
				<td>
					<div class="radio">
						<input type="radio" name="<?php print($variable_pregunta);?>" id="<?php print($variable_si);?>" value="1">
					</div>
				</td>
				<td>
					<div class="radio">
						<input type="radio" name="<?php print($variable_pregunta);?>" id="<?php print($variable_no);?>" value="2">
					</div>
				</td>
				<input type="hidden" id="<?php print($variable_precio);?>" name="<?php print($variable_precio);?>" value="<?php print($row['precio_bitacora']);?>">
				<input type="hidden" id="<?php print($variable_detalle);?>" name="<?php print($variable_detalle);?>" value="<?php print($row['id_detallebitacora']);?>">
			</tr>
			<?php
				$contador++;
			}
			?>
			<input type="hidden" id="contador" name="contador" value="<?php print($contador);?>">
			<?php
		}
	}
?>
