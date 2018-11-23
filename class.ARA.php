<?php
	include_once 'dbconfig.php';
	class ara
	{
		private $conn;
		
		public function __construct()
		{
			$database = new Database();
			$db = $database->dbConnection();
			$this->conn = $db;
		}
		//SELECTS//
		//Méritos ARA
		public function meritosMensual($year,$month,$tipo)
		{
			if($tipo == 0)
			{
				$query = "SELECT
							CONCAT(A.nombre_agente,' ',A.apellidos_agente) AS agente,
							0 AS desarrollo,
							0 AS desempeño
						FROM T_Agentes AS A
						ORDER BY A.nombre_agente";
				$stmt = $this->conn->prepare($query);
				$stmt->execute();
				$count1 = $stmt->rowCount();
			}
			else
			{
				$query = "SELECT
							CONCAT(A.nombre_agente,' ',A.apellidos_agente) AS agente,
							0 AS desarrollo,
							0 AS desempeño
						FROM T_Agentes AS A
						WHERE A.id_invo_evo = :tipo
						ORDER BY A.nombre_agente";
				$stmt = $this->conn->prepare($query);
				$stmt->bindparam(":tipo",$tipo);
				$stmt->execute();
				$count1 = $stmt->rowCount();
			}
			if($count1 > 0)
			{
				$x = 1;
				while($row=$stmt->fetch(PDO::FETCH_ASSOC))
				{
					if($x == 1)
					{
						$bgcolor = "#FFC700";
						$fontcolor = "white";
					}
					elseif($x == 2)
					{
						$bgcolor = "#e2e2e0";
						$fontcolor = "white";
					}
					elseif($x == 3)
					{
						$bgcolor = "#e06133";
						$fontcolor = "white";
					}
					else
					{
						$bgcolor = "";
						$fontcolor = "black";
					}
					
					?>
						<tr bgcolor="<?php print($bgcolor); ?>" style="color:<?php print($fontcolor); ?>">
							<td><?php print($x);?></td>
							<td><?php print($row['agente']); ?></td>
							<td><?php print($row['desarrollo']); ?></td>
							<td><?php print($row['desempeño']); ?></td>
							<td><?php print($row['desarrollo']+$row['desempeño']); ?></td>
							<td><?php print(round(($row['desarrollo']+$row['desempeño'])/100,2)); ?></td>
						</tr>
					<?php
					$x++;
				}
			}
			else
			{
			?>
				<tr>
					<td>No hay agentes disponibles</td>
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
		//Planeación ARA
		public function planeacionARA($year)
		{
			$query = "SELECT
						P.nombre_producto,
						COUNT(DAC.id_familiaproducto) AS total,
						(SELECT GROUP_CONCAT(T.tipo_agente) FROM T_Subproducto_TipoAgente AS ST INNER JOIN T_TipoAgente AS T ON T.id_tipoagente = ST.id_tipoagente INNER JOIN T_Subproductos AS Sb ON Sb.id_subproducto = ST.id_subproducto INNER JOIN T_Productos AS Prod ON Prod.id_producto = Sb.id_subproducto WHERE Prod.id_producto =  P.id_producto)AS tipoAgente,
						(COUNT(DAC.id_familiaproducto))*(SELECT COUNT(T.id_tipoagente) FROM T_Subproducto_TipoAgente AS ST INNER JOIN T_TipoAgente AS T ON T.id_tipoagente = ST.id_tipoagente INNER JOIN T_Subproductos AS Sb ON Sb.id_subproducto = ST.id_subproducto INNER JOIN T_Productos AS Prod ON Prod.id_producto = Sb.id_subproducto WHERE Prod.id_producto =  P.id_producto)AS agentesRequeridos,
						(SELECT COUNT(A.id_agente) FROM T_Agentes AS A INNER JOIN T_TipoAgente_Agente AS TAA ON TAA.id_agente = A.id_agente INNER JOIN T_Subproducto_TipoAgente AS ST ON TAA.id_tipoagente = ST.id_tipoagente INNER JOIN T_Subproductos AS Sb ON Sb.id_subproducto = ST.id_subproducto INNER JOIN T_Productos AS Prod ON Prod.id_producto = Sb.id_subproducto WHERE Prod.id_producto =  P.id_producto)AS agentesDisponibles
					FROM T_Detalle_AnalisisCuantitativo_Escenario2 AS DAC
					INNER JOIN T_AnalisisCuantitativo_Escenario2 AS AC
					ON DAC.id_analisiscuantitativo = AC.id_analisiscuantitativo
					INNER JOIN T_Productos AS P
					ON DAC.id_familiaproducto = P.id_producto
					WHERE AC.year_acuant = :year
					GROUP BY DAC.id_familiaproducto";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":year",$year);
			$stmt->execute();
			$count1 = $stmt->rowCount();
			if($count1 > 0)
			{
				$x = 1;
				while($row=$stmt->fetch(PDO::FETCH_ASSOC))
				{
					?>
						<tr>
							<td><?php print($row['nombre_producto']); ?></td>
							<td><?php print($row['total']); ?></td>
							<td><?php print($row['tipoAgente']); ?></td>
							<td><?php print($row['agentesRequeridos']); ?></td>
							<td><?php print($row['agentesDisponibles']); ?></td>
							<td><?php print($row['agentesRequeridos']-$row['agentesDisponibles']); ?></td>
						</tr>
					<?php
					$x++;
				}
			}
			else
			{
			?>
				<tr>
					<td>Contextualización</td>
					<td>5</td>
					<td>Tipo de agente</td>
					<td>2</td>
					<td>2</td>
					<td>3</td>
					<td>Capacitar 3 agentes del padrón disponibles</td>
				</tr>
			<?php
			}
		}
		//Encuesta ARA
		public function EncuestaARAGeneral($id)
		{
			$query = "SELECT
						CONCAT(A.nombre_agente,' ',A.apellidos_agente) AS agente,
						TA.tipo_agente,
						DB.id_del_evento
					FROM T_Detalle_Bitacora AS DB
					INNER JOIN T_Detalle_Bitacora_Agente AS DBA 
					ON DB.id_detallebitacora = DBA.id_detallebitacora
					INNER JOIN T_Agentes AS A 
					ON A.id_agente = DBA.id_agente
					INNER JOIN T_TipoAgente AS TA
					ON TA.id_tipoagente = DBA.id_tipoagente
					WHERE DB.id_detallebitacora = :id
					GROUP BY DB.id_detallebitacora
					ORDER BY DB.id_detallebitacora";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":id",$id);
			$stmt->execute();
			$row=$stmt->fetch(PDO::FETCH_ASSOC);
			return $row;
		}
		//Auditar Eventos
		public function AuditarEventos($month, $year)
		{
			$query = "SELECT
						DB.id_detallebitacora,
						DBA.id_detallebitacora_agente,
						DBA.id_agente,
						CONCAT(A.nombre_agente,' ',A.apellidos_agente) AS agente,
						TA.tipo_agente,
						DB.id_del_evento,
						C.nombre_cliente,
						DB.fecha_ejecucion,
						CONCAT(E.estado, ' - ',DB.sede_particular) AS sede,
						S.nombre_subproducto,
						AUD.auditoria
					FROM T_Detalle_Bitacora AS DB
					INNER JOIN T_Bitacora AS B
					ON DB.id_bitacora = B.id_bitacora
					INNER JOIN T_Detalle_Bitacora_Agente AS DBA 
					ON DB.id_detallebitacora = DBA.id_detallebitacora
					INNER JOIN T_Agentes AS A 
					ON A.id_agente = DBA.id_agente
					INNER JOIN T_Clientes AS C
					ON B.id_cliente = C.id_cliente
					INNER JOIN T_Subproductos AS S
					ON S.id_subproducto = DB.id_subproducto
					INNER JOIN T_TipoAgente AS TA
					ON TA.id_tipoagente = DBA.id_tipoagente
					INNER JOIN T_Estado AS E
					ON E.id_estado = DB.id_estado
					INNER JOIN T_AuditoriaAgentes AS AUD
					ON AUD.id_auditoria = DBA.id_auditoria
					WHERE DB.id_mesejecucion = :month AND YEAR(DB.fecha_ejecucion) = :year AND DBA.id_estatusasignacion = 2
					GROUP BY DB.id_detallebitacora
					ORDER BY DB.id_detallebitacora";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":month",$month);
			$stmt->bindparam(":year",$year);
			$stmt->execute();
			$count1 = $stmt->rowCount();
			if($count1 > 0)
			{
				$x = 1;
				while($row=$stmt->fetch(PDO::FETCH_ASSOC))
				{
					?>
						<tr>
							<td style="text-align: center"><a href="EncuestaARA.php?id=<?php print($row['id_detallebitacora']); ?>"><i class="glyph-icon icon-star"></i></a></td>
							<td><?php print($row['auditoria']); ?></td>
							<td><?php print($row['agente']); ?></td>
							<td><?php print($row['tipo_agente']); ?></td>
							<td><?php print($row['id_del_evento']); ?></td>
							<td><?php print($row['nombre_cliente']); ?></td>
							<td><?php print($row['fecha_ejecucion']); ?></td>
							<td><?php print($row['sede']); ?></td>
							<td><?php print($row['nombre_subproducto']); ?></td>
						</tr>
					<?php
					$x++;
				}
			}
			else
			{
			?>
				<tr>
					<td>No hay agentes disponibles</td>
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
		
		//Top Ten
		public function getTopTenResumen($year)
		{
			try
			{
				$stmt = $this->conn->prepare("SELECT 
					SUM(A.oferta_actual) AS oferta_actual,
					((SELECT SUM(DACE.mes1_Ej*DACE.costo_ac) FROM T_Detalle_AnalisisCuantitativo_Escenario2 AS DACE INNER JOIN T_AnalisisCuantitativo_Escenario2 AS ACE ON ACE.id_analisiscuantitativo = DACE.id_analisiscuantitativo WHERE ACE.year_acuant = :year)) AS EneroEstacionariedad,
					((SELECT SUM(DACE.mes2_Ej*DACE.costo_ac) FROM T_Detalle_AnalisisCuantitativo_Escenario2 AS DACE INNER JOIN T_AnalisisCuantitativo_Escenario2 AS ACE ON ACE.id_analisiscuantitativo = DACE.id_analisiscuantitativo WHERE ACE.year_acuant = :year)) AS FebreroEstacionariedad,
					((SELECT SUM(DACE.mes3_Ej*DACE.costo_ac) FROM T_Detalle_AnalisisCuantitativo_Escenario2 AS DACE INNER JOIN T_AnalisisCuantitativo_Escenario2 AS ACE ON ACE.id_analisiscuantitativo = DACE.id_analisiscuantitativo WHERE ACE.year_acuant = :year)) AS MarzoEstacionariedad,
					((SELECT SUM(DACE.mes4_Ej*DACE.costo_ac) FROM T_Detalle_AnalisisCuantitativo_Escenario2 AS DACE INNER JOIN T_AnalisisCuantitativo_Escenario2 AS ACE ON ACE.id_analisiscuantitativo = DACE.id_analisiscuantitativo WHERE ACE.year_acuant = :year)) AS AbrilEstacionariedad,
					((SELECT SUM(DACE.mes5_Ej*DACE.costo_ac) FROM T_Detalle_AnalisisCuantitativo_Escenario2 AS DACE INNER JOIN T_AnalisisCuantitativo_Escenario2 AS ACE ON ACE.id_analisiscuantitativo = DACE.id_analisiscuantitativo WHERE ACE.year_acuant = :year)) AS MayoEstacionariedad,
					((SELECT SUM(DACE.mes6_Ej*DACE.costo_ac) FROM T_Detalle_AnalisisCuantitativo_Escenario2 AS DACE INNER JOIN T_AnalisisCuantitativo_Escenario2 AS ACE ON ACE.id_analisiscuantitativo = DACE.id_analisiscuantitativo WHERE ACE.year_acuant = :year)) AS JunioEstacionariedad,
					((SELECT SUM(DACE.mes7_Ej*DACE.costo_ac) FROM T_Detalle_AnalisisCuantitativo_Escenario2 AS DACE INNER JOIN T_AnalisisCuantitativo_Escenario2 AS ACE ON ACE.id_analisiscuantitativo = DACE.id_analisiscuantitativo WHERE ACE.year_acuant = :year)) AS JulioEstacionariedad,
					((SELECT SUM(DACE.mes8_Ej*DACE.costo_ac) FROM T_Detalle_AnalisisCuantitativo_Escenario2 AS DACE INNER JOIN T_AnalisisCuantitativo_Escenario2 AS ACE ON ACE.id_analisiscuantitativo = DACE.id_analisiscuantitativo WHERE ACE.year_acuant = :year)) AS AgostoEstacionariedad,
					((SELECT SUM(DACE.mes9_Ej*DACE.costo_ac) FROM T_Detalle_AnalisisCuantitativo_Escenario2 AS DACE INNER JOIN T_AnalisisCuantitativo_Escenario2 AS ACE ON ACE.id_analisiscuantitativo = DACE.id_analisiscuantitativo WHERE ACE.year_acuant = :year)) AS SeptiembreEstacionariedad,
					((SELECT SUM(DACE.mes10_Ej*DACE.costo_ac) FROM T_Detalle_AnalisisCuantitativo_Escenario2 AS DACE INNER JOIN T_AnalisisCuantitativo_Escenario2 AS ACE ON ACE.id_analisiscuantitativo = DACE.id_analisiscuantitativo WHERE ACE.year_acuant = :year)) AS OctubreEstacionariedad,
					((SELECT SUM(DACE.mes11_Ej*DACE.costo_ac) FROM T_Detalle_AnalisisCuantitativo_Escenario2 AS DACE INNER JOIN T_AnalisisCuantitativo_Escenario2 AS ACE ON ACE.id_analisiscuantitativo = DACE.id_analisiscuantitativo WHERE ACE.year_acuant = :year)) AS NoviembreEstacionariedad,
					((SELECT SUM(DACE.mes12_Ej*DACE.costo_ac) FROM T_Detalle_AnalisisCuantitativo_Escenario2 AS DACE INNER JOIN T_AnalisisCuantitativo_Escenario2 AS ACE ON ACE.id_analisiscuantitativo = DACE.id_analisiscuantitativo WHERE ACE.year_acuant = :year)) AS DiciembreEstacionariedad,
					((SELECT SUM((DACE.mes1_Ej+DACE.mes2_Ej+DACE.mes3_Ej+DACE.mes4_Ej+DACE.mes5_Ej+DACE.mes6_Ej+DACE.mes7_Ej+DACE.mes8_Ej+DACE.mes9_Ej+DACE.mes10_Ej+DACE.mes11_Ej+DACE.mes12_Ej)*DACE.costo_ac) FROM T_Detalle_AnalisisCuantitativo_Escenario2 AS DACE INNER JOIN T_AnalisisCuantitativo_Escenario2 AS ACE ON ACE.id_analisiscuantitativo = DACE.id_analisiscuantitativo WHERE ACE.year_acuant = :year)) AS TotalEstacionariedad,
					(SELECT SUM(DBA.costo) FROM T_Detalle_Bitacora_Agente AS DBA INNER JOIN T_Detalle_Bitacora AS DB ON DB.id_detallebitacora = DBA.id_detallebitacora WHERE YEAR(DB.ultima_modificacion) = :year  AND DB.id_mesejecucion = 1) AS EneroBitacora,
					(SELECT SUM(DBA.costo) FROM T_Detalle_Bitacora_Agente AS DBA INNER JOIN T_Detalle_Bitacora AS DB ON DB.id_detallebitacora = DBA.id_detallebitacora WHERE YEAR(DB.ultima_modificacion) = :year  AND DB.id_mesejecucion = 2) AS FebreroBitacora,
					(SELECT SUM(DBA.costo) FROM T_Detalle_Bitacora_Agente AS DBA INNER JOIN T_Detalle_Bitacora AS DB ON DB.id_detallebitacora = DBA.id_detallebitacora WHERE YEAR(DB.ultima_modificacion) = :year  AND DB.id_mesejecucion = 3) AS MarzoBitacora,
					(SELECT SUM(DBA.costo) FROM T_Detalle_Bitacora_Agente AS DBA INNER JOIN T_Detalle_Bitacora AS DB ON DB.id_detallebitacora = DBA.id_detallebitacora WHERE YEAR(DB.ultima_modificacion) = :year  AND DB.id_mesejecucion = 4) AS AbrilBitacora,
					(SELECT SUM(DBA.costo) FROM T_Detalle_Bitacora_Agente AS DBA INNER JOIN T_Detalle_Bitacora AS DB ON DB.id_detallebitacora = DBA.id_detallebitacora WHERE YEAR(DB.ultima_modificacion) = :year  AND DB.id_mesejecucion = 5) AS MayoBitacora,
					(SELECT SUM(DBA.costo) FROM T_Detalle_Bitacora_Agente AS DBA INNER JOIN T_Detalle_Bitacora AS DB ON DB.id_detallebitacora = DBA.id_detallebitacora WHERE YEAR(DB.ultima_modificacion) = :year  AND DB.id_mesejecucion = 6) AS JunioBitacora,
					(SELECT SUM(DBA.costo) FROM T_Detalle_Bitacora_Agente AS DBA INNER JOIN T_Detalle_Bitacora AS DB ON DB.id_detallebitacora = DBA.id_detallebitacora WHERE YEAR(DB.ultima_modificacion) = :year  AND DB.id_mesejecucion = 7) AS JulioBitacora,
					(SELECT SUM(DBA.costo) FROM T_Detalle_Bitacora_Agente AS DBA INNER JOIN T_Detalle_Bitacora AS DB ON DB.id_detallebitacora = DBA.id_detallebitacora WHERE YEAR(DB.ultima_modificacion) = :year  AND DB.id_mesejecucion = 8) AS AgostoBitacora,
					(SELECT SUM(DBA.costo) FROM T_Detalle_Bitacora_Agente AS DBA INNER JOIN T_Detalle_Bitacora AS DB ON DB.id_detallebitacora = DBA.id_detallebitacora WHERE YEAR(DB.ultima_modificacion) = :year  AND DB.id_mesejecucion = 9) AS SeptiembreBitacora,
					(SELECT SUM(DBA.costo) FROM T_Detalle_Bitacora_Agente AS DBA INNER JOIN T_Detalle_Bitacora AS DB ON DB.id_detallebitacora = DBA.id_detallebitacora WHERE YEAR(DB.ultima_modificacion) = :year  AND DB.id_mesejecucion = 10) AS OctubreBitacora,
					(SELECT SUM(DBA.costo) FROM T_Detalle_Bitacora_Agente AS DBA INNER JOIN T_Detalle_Bitacora AS DB ON DB.id_detallebitacora = DBA.id_detallebitacora WHERE YEAR(DB.ultima_modificacion) = :year  AND DB.id_mesejecucion = 11) AS NoviembreBitacora,
					(SELECT SUM(DBA.costo) FROM T_Detalle_Bitacora_Agente AS DBA INNER JOIN T_Detalle_Bitacora AS DB ON DB.id_detallebitacora = DBA.id_detallebitacora WHERE YEAR(DB.ultima_modificacion) = :year  AND DB.id_mesejecucion = 12) AS DiciembreBitacora,
					(SELECT SUM(DBA.costo) FROM T_Detalle_Bitacora_Agente AS DBA INNER JOIN T_Detalle_Bitacora AS DB ON DB.id_detallebitacora = DBA.id_detallebitacora WHERE YEAR(DB.ultima_modificacion) = :year) AS TotalBitacora,
					(SELECT SUM(R.monto) FROM T_Rechazos AS R WHERE YEAR(R.fecha_rechazo) = :year  AND MONTH(R.fecha_rechazo) = 1) AS EneroRechazo,
					(SELECT SUM(R.monto) FROM T_Rechazos AS R WHERE YEAR(R.fecha_rechazo) = :year  AND MONTH(R.fecha_rechazo) = 2) AS FebreroRechazo,
					(SELECT SUM(R.monto) FROM T_Rechazos AS R WHERE YEAR(R.fecha_rechazo) = :year  AND MONTH(R.fecha_rechazo) = 3) AS MarzoRechazo,
					(SELECT SUM(R.monto) FROM T_Rechazos AS R WHERE YEAR(R.fecha_rechazo) = :year  AND MONTH(R.fecha_rechazo) = 4) AS AbrilRechazo,
					(SELECT SUM(R.monto) FROM T_Rechazos AS R WHERE YEAR(R.fecha_rechazo) = :year  AND MONTH(R.fecha_rechazo) = 5) AS MayoRechazo,
					(SELECT SUM(R.monto) FROM T_Rechazos AS R WHERE YEAR(R.fecha_rechazo) = :year  AND MONTH(R.fecha_rechazo) = 6) AS JunioRechazo,
					(SELECT SUM(R.monto) FROM T_Rechazos AS R WHERE YEAR(R.fecha_rechazo) = :year  AND MONTH(R.fecha_rechazo) = 7) AS JulioRechazo,
					(SELECT SUM(R.monto) FROM T_Rechazos AS R WHERE YEAR(R.fecha_rechazo) = :year  AND MONTH(R.fecha_rechazo) = 8) AS AgostoRechazo,
					(SELECT SUM(R.monto) FROM T_Rechazos AS R WHERE YEAR(R.fecha_rechazo) = :year  AND MONTH(R.fecha_rechazo) = 9) AS SeptiembreRechazo,
					(SELECT SUM(R.monto) FROM T_Rechazos AS R WHERE YEAR(R.fecha_rechazo) = :year  AND MONTH(R.fecha_rechazo) = 10) AS OctubreRechazo,
					(SELECT SUM(R.monto) FROM T_Rechazos AS R WHERE YEAR(R.fecha_rechazo) = :year  AND MONTH(R.fecha_rechazo) = 11) AS NoviembreRechazo,
					(SELECT SUM(R.monto) FROM T_Rechazos AS R WHERE YEAR(R.fecha_rechazo) = :year  AND MONTH(R.fecha_rechazo) = 12) AS DiciembreRechazo,
					(SELECT SUM(R.monto) FROM T_Rechazos AS R WHERE YEAR(R.fecha_rechazo) = :year) AS TotalRechazo
				FROM T_Agentes AS A
				INNER JOIN T_Circulos AS C
				ON C.id_circulo = A.id_circulo;");
				$stmt->bindparam(":year",$year);
				$stmt->execute();
				$events = array();
				$e = array();
				while($row=$stmt->fetch(PDO::FETCH_ASSOC))
				{
					?>
					<tr>
						<th>Estacionariedad</th>
						<td><?php print(number_format($row['TotalEstacionariedad'],2)); ?></td>
						<td><?php print(number_format($row['EneroEstacionariedad'],2)); ?></td>
						<td><?php print(number_format($row['FebreroEstacionariedad'],2)); ?></td>
						<td><?php print(number_format($row['EneroEstacionariedad']+$row['FebreroEstacionariedad'],2)); ?></td>
						<td><?php print(number_format($row['MarzoEstacionariedad'],2)); ?></td>
						<td><?php print(number_format($row['EneroEstacionariedad']+$row['FebreroEstacionariedad']+$row['MarzoEstacionariedad'],2)); ?></td>
						<td><?php print(number_format($row['AbrilEstacionariedad'],2)); ?></td>
						<td><?php print(number_format($row['EneroEstacionariedad']+$row['FebreroEstacionariedad']+$row['MarzoEstacionariedad']+$row['AbrilEstacionariedad'],2)); ?></td>
						<td><?php print(number_format($row['MayoEstacionariedad'],2)); ?></td>
						<td><?php print(number_format($row['EneroEstacionariedad']+$row['FebreroEstacionariedad']+$row['MarzoEstacionariedad']+$row['AbrilEstacionariedad']+$row['MayoEstacionariedad'],2)); ?></td>
						<td><?php print(number_format($row['JunioEstacionariedad'],2)); ?></td>
						<td><?php print(number_format($row['EneroEstacionariedad']+$row['FebreroEstacionariedad']+$row['MarzoEstacionariedad']+$row['AbrilEstacionariedad']+$row['MayoEstacionariedad']+$row['JunioEstacionariedad'],2)); ?></td>
						<td><?php print(number_format($row['JulioEstacionariedad'],2)); ?></td>
						<td><?php print(number_format($row['EneroEstacionariedad']+$row['FebreroEstacionariedad']+$row['MarzoEstacionariedad']+$row['AbrilEstacionariedad']+$row['MayoEstacionariedad']+$row['JunioEstacionariedad']+$row['JulioEstacionariedad'],2)); ?></td>
						<td><?php print(number_format($row['AgostoEstacionariedad'],2)); ?></td>
						<td><?php print(number_format($row['EneroEstacionariedad']+$row['FebreroEstacionariedad']+$row['MarzoEstacionariedad']+$row['AbrilEstacionariedad']+$row['MayoEstacionariedad']+$row['JunioEstacionariedad']+$row['JulioEstacionariedad']+$row['AgostoEstacionariedad'],2)); ?></td>
						<td><?php print(number_format($row['SeptiembreEstacionariedad'],2)); ?></td>
						<td><?php print(number_format($row['EneroEstacionariedad']+$row['FebreroEstacionariedad']+$row['MarzoEstacionariedad']+$row['AbrilEstacionariedad']+$row['MayoEstacionariedad']+$row['JunioEstacionariedad']+$row['JulioEstacionariedad']+$row['AgostoEstacionariedad']+$row['SeptiembreEstacionariedad'],2)); ?></td>
						<td><?php print(number_format($row['OctubreEstacionariedad'],2)); ?></td>
						<td><?php print(number_format($row['EneroEstacionariedad']+$row['FebreroEstacionariedad']+$row['MarzoEstacionariedad']+$row['AbrilEstacionariedad']+$row['MayoEstacionariedad']+$row['JunioEstacionariedad']+$row['JulioEstacionariedad']+$row['AgostoEstacionariedad']+$row['SeptiembreEstacionariedad']+$row['OctubreEstacionariedad'],2)); ?></td>
						<td><?php print(number_format($row['NoviembreEstacionariedad'],2)); ?></td>
						<td><?php print(number_format($row['EneroEstacionariedad']+$row['FebreroEstacionariedad']+$row['MarzoEstacionariedad']+$row['AbrilEstacionariedad']+$row['MayoEstacionariedad']+$row['JunioEstacionariedad']+$row['JulioEstacionariedad']+$row['AgostoEstacionariedad']+$row['SeptiembreEstacionariedad']+$row['OctubreEstacionariedad']+$row['NoviembreEstacionariedad'],2)); ?></td>
						<td><?php print(number_format($row['DiciembreEstacionariedad'],2)); ?></td>
					</tr>
					<tr>
						<th>Real</th>
						<td><?php print(number_format($row['TotalBitacora'],2)); ?></td>
						<td><?php print(number_format($row['EneroBitacora'],2)); ?></td>
						<td><?php print(number_format($row['FebreroBitacora'],2)); ?></td>
						<td><?php print(number_format($row['EneroBitacora']+$row['FebreroBitacora'],2)); ?></td>
						<td><?php print(number_format($row['MarzoBitacora'],2)); ?></td>
						<td><?php print(number_format($row['EneroBitacora']+$row['FebreroBitacora']+$row['MarzoBitacora'],2)); ?></td>
						<td><?php print(number_format($row['AbrilBitacora'],2)); ?></td>
						<td><?php print(number_format($row['EneroBitacora']+$row['FebreroBitacora']+$row['MarzoBitacora']+$row['AbrilBitacora'],2)); ?></td>
						<td><?php print(number_format($row['MayoBitacora'],2)); ?></td>
						<td><?php print(number_format($row['EneroBitacora']+$row['FebreroBitacora']+$row['MarzoBitacora']+$row['AbrilBitacora']+$row['MayoBitacora'],2)); ?></td>
						<td><?php print(number_format($row['JunioBitacora'],2)); ?></td>
						<td><?php print(number_format($row['EneroBitacora']+$row['FebreroBitacora']+$row['MarzoBitacora']+$row['AbrilBitacora']+$row['MayoBitacora']+$row['JunioBitacora'],2)); ?></td>
						<td><?php print(number_format($row['JulioBitacora'],2)); ?></td>
						<td><?php print(number_format($row['EneroBitacora']+$row['FebreroBitacora']+$row['MarzoBitacora']+$row['AbrilBitacora']+$row['MayoBitacora']+$row['JunioBitacora']+$row['JulioBitacora'],2)); ?></td>
						<td><?php print(number_format($row['AgostoBitacora'],2)); ?></td>
						<td><?php print(number_format($row['EneroBitacora']+$row['FebreroBitacora']+$row['MarzoBitacora']+$row['AbrilBitacora']+$row['MayoBitacora']+$row['JunioBitacora']+$row['JulioBitacora']+$row['AgostoBitacora'],2)); ?></td>
						<td><?php print(number_format($row['SeptiembreBitacora'],2)); ?></td>
						<td><?php print(number_format($row['EneroBitacora']+$row['FebreroBitacora']+$row['MarzoBitacora']+$row['AbrilBitacora']+$row['MayoBitacora']+$row['JunioBitacora']+$row['JulioBitacora']+$row['AgostoBitacora']+$row['SeptiembreBitacora'],2)); ?></td>
						<td><?php print(number_format($row['OctubreBitacora'],2)); ?></td>
						<td><?php print(number_format($row['EneroBitacora']+$row['FebreroBitacora']+$row['MarzoBitacora']+$row['AbrilBitacora']+$row['MayoBitacora']+$row['JunioBitacora']+$row['JulioBitacora']+$row['AgostoBitacora']+$row['SeptiembreBitacora']+$row['OctubreBitacora'],2)); ?></td>
						<td><?php print(number_format($row['NoviembreBitacora'],2)); ?></td>
						<td><?php print(number_format($row['EneroBitacora']+$row['FebreroBitacora']+$row['MarzoBitacora']+$row['AbrilBitacora']+$row['MayoBitacora']+$row['JunioBitacora']+$row['JulioBitacora']+$row['AgostoBitacora']+$row['SeptiembreBitacora']+$row['OctubreBitacora']+$row['NoviembreBitacora'],2)); ?></td>
						<td><?php print(number_format($row['DiciembreBitacora'],2)); ?></td>
					</tr>
					<tr>
						<th>Diferencia</th>
						<td><?php print(number_format($row['TotalBitacora']-$row['oferta_actual'],2)); ?></td>
						<td><?php print(number_format($row['EneroBitacora']-$row['EneroEstacionariedad'],2)); ?></td>
						<td><?php print(number_format($row['FebreroBitacora']-$row['FebreroEstacionariedad'],2)); ?></td>
						<td><?php print(number_format(0,2)); ?></td>
						<td><?php print(number_format($row['MarzoBitacora']-$row['MarzoEstacionariedad'],2)); ?></td>
						<td><?php print(number_format(0,2)); ?></td>
						<td><?php print(number_format($row['AbrilBitacora']-$row['AbrilEstacionariedad'],2)); ?></td>
						<td><?php print(number_format(0,2)); ?></td>
						<td><?php print(number_format($row['MayoBitacora']-$row['MayoEstacionariedad'],2)); ?></td>
						<td><?php print(number_format(0,2)); ?></td>
						<td><?php print(number_format($row['JunioBitacora']-$row['JunioEstacionariedad'],2)); ?></td>
						<td><?php print(number_format(0,2)); ?></td>
						<td><?php print(number_format($row['JulioBitacora']-$row['JulioEstacionariedad'],2)); ?></td>
						<td><?php print(number_format(0,2)); ?></td>
						<td><?php print(number_format($row['AgostoBitacora']-$row['AgostoEstacionariedad'],2)); ?></td>
						<td><?php print(number_format(0,2)); ?></td>
						<td><?php print(number_format($row['SeptiembreBitacora']-$row['SeptiembreEstacionariedad'],2)); ?></td>
						<td><?php print(number_format(0,2)); ?></td>
						<td><?php print(number_format($row['OctubreBitacora']-$row['OctubreEstacionariedad'],2)); ?></td>
						<td><?php print(number_format(0,2)); ?></td>
						<td><?php print(number_format($row['NoviembreBitacora']-$row['NoviembreEstacionariedad'],2)); ?></td>
						<td><?php print(number_format(0,2)); ?></td>
						<td><?php print(number_format($row['DiciembreBitacora']-$row['DiciembreEstacionariedad'],2)); ?></td>
					</tr>
					<tr>
						<th>Rechazado</th>
						<td><?php print(number_format($row['TotalRechazo'],2)); ?></td>
						<td><?php print(number_format($row['EneroRechazo'],2)); ?></td>
						<td><?php print(number_format($row['FebreroRechazo'],2)); ?></td>
						<td><?php print(number_format($row['EneroRechazo']+$row['FebreroRechazo'],2)); ?></td>
						<td><?php print(number_format($row['MarzoRechazo'],2)); ?></td>
						<td><?php print(number_format($row['EneroRechazo']+$row['FebreroRechazo']+$row['MarzoRechazo'],2)); ?></td>
						<td><?php print(number_format($row['AbrilRechazo'],2)); ?></td>
						<td><?php print(number_format($row['EneroRechazo']+$row['FebreroRechazo']+$row['MarzoRechazo']+$row['AbrilRechazo'],2)); ?></td>
						<td><?php print(number_format($row['MayoRechazo'],2)); ?></td>
						<td><?php print(number_format($row['EneroRechazo']+$row['FebreroRechazo']+$row['MarzoRechazo']+$row['AbrilRechazo']+$row['MayoRechazo'],2)); ?></td>
						<td><?php print(number_format($row['JunioRechazo'],2)); ?></td>
						<td><?php print(number_format($row['EneroRechazo']+$row['FebreroRechazo']+$row['MarzoRechazo']+$row['AbrilRechazo']+$row['MayoRechazo']+$row['JunioRechazo'],2)); ?></td>
						<td><?php print(number_format($row['JulioRechazo'],2)); ?></td>
						<td><?php print(number_format($row['EneroRechazo']+$row['FebreroRechazo']+$row['MarzoRechazo']+$row['AbrilRechazo']+$row['MayoRechazo']+$row['JunioRechazo']+$row['JulioRechazo'],2)); ?></td>
						<td><?php print(number_format($row['AgostoRechazo'],2)); ?></td>
						<td><?php print(number_format($row['EneroRechazo']+$row['FebreroRechazo']+$row['MarzoRechazo']+$row['AbrilRechazo']+$row['MayoRechazo']+$row['JunioRechazo']+$row['JulioRechazo']+$row['AgostoRechazo'],2)); ?></td>
						<td><?php print(number_format($row['SeptiembreRechazo'],2)); ?></td>
						<td><?php print(number_format($row['EneroRechazo']+$row['FebreroRechazo']+$row['MarzoRechazo']+$row['AbrilRechazo']+$row['MayoRechazo']+$row['JunioRechazo']+$row['JulioRechazo']+$row['AgostoRechazo']+$row['SeptiembreRechazo'],2)); ?></td>
						<td><?php print(number_format($row['OctubreRechazo'],2)); ?></td>
						<td><?php print(number_format($row['EneroRechazo']+$row['FebreroRechazo']+$row['MarzoRechazo']+$row['AbrilRechazo']+$row['MayoRechazo']+$row['JunioRechazo']+$row['JulioRechazo']+$row['AgostoRechazo']+$row['SeptiembreRechazo']+$row['OctubreRechazo'],2)); ?></td>
						<td><?php print(number_format($row['NoviembreRechazo'],2)); ?></td>
						<td><?php print(number_format($row['EneroRechazo']+$row['FebreroRechazo']+$row['MarzoRechazo']+$row['AbrilRechazo']+$row['MayoRechazo']+$row['JunioRechazo']+$row['JulioRechazo']+$row['AgostoRechazo']+$row['SeptiembreRechazo']+$row['OctubreRechazo']+$row['NoviembreRechazo'],2)); ?></td>
						<td><?php print(number_format($row['DiciembreRechazo'],2)); ?></td>
					</tr>
					<tr>
						<th>Diferencia (Real + Rechazado - Estacionariedad)</th>
						<td><?php print(number_format(($row['TotalRechazo']+$row['TotalBitacora'])-$row['oferta_actual'],2)); ?></td>
						<td><?php print(number_format(($row['EneroRechazo']+$row['EneroBitacora'])-$row['EneroEstacionariedad'],2)); ?></td>
						<td><?php print(number_format(($row['FebreroRechazo']+$row['FebreroBitacora'])-$row['FebreroEstacionariedad'],2)); ?></td>
						<td><?php print(number_format(0,2)); ?></td>
						<td><?php print(number_format(($row['MarzoRechazo']+$row['MarzoBitacora'])-$row['MarzoEstacionariedad'],2)); ?></td>
						<td><?php print(number_format(0,2)); ?></td>
						<td><?php print(number_format(($row['AbrilRechazo']+$row['AbrilBitacora'])-$row['AbrilEstacionariedad'],2)); ?></td>
						<td><?php print(number_format(0,2)); ?></td>
						<td><?php print(number_format(($row['MayoRechazo']+$row['MayoBitacora'])-$row['MayoEstacionariedad'],2)); ?></td>
						<td><?php print(number_format(0,2)); ?></td>
						<td><?php print(number_format(($row['JunioRechazo']+$row['JunioBitacora'])-$row['JunioEstacionariedad'],2)); ?></td>
						<td><?php print(number_format(0,2)); ?></td>
						<td><?php print(number_format(($row['JulioRechazo']+$row['JulioBitacora'])-$row['JulioEstacionariedad'],2)); ?></td>
						<td><?php print(number_format(0,2)); ?></td>
						<td><?php print(number_format(($row['AgostoRechazo']+$row['AgostoBitacora'])-$row['AgostoEstacionariedad'],2)); ?></td>
						<td><?php print(number_format(0,2)); ?></td>
						<td><?php print(number_format(($row['SeptiembreRechazo']+$row['SeptiembreBitacora'])-$row['SeptiembreEstacionariedad'],2)); ?></td>
						<td><?php print(number_format(0,2)); ?></td>
						<td><?php print(number_format(($row['OctubreRechazo']+$row['OctubreBitacora'])-$row['OctubreEstacionariedad'],2)); ?></td>
						<td><?php print(number_format(0,2)); ?></td>
						<td><?php print(number_format(($row['NoviembreRechazo']+$row['NoviembreBitacora'])-$row['NoviembreEstacionariedad'],2)); ?></td>
						<td><?php print(number_format(0,2)); ?></td>
						<td><?php print(number_format(($row['DiciembreRechazo']+$row['DiciembreBitacora'])-$row['DiciembreEstacionariedad'],2)); ?></td>
					</tr>
					<?php
				}
				return true;
			}
			catch(PDOException $e)
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
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<?php
				return false;
			}
		}
		public function getCirculoTopTen($circulo,$year)
		{
			try
			{
				$stmt = $this->conn->prepare("SELECT 
					A.id_agente,
					CONCAT(A.nombre_agente,' ',A.apellidos_agente) AS nombreAgente,
					A.oferta_actual,
					((SELECT SUM(DACE.mes1_Ej*DACE.costo_ac) FROM T_Detalle_AnalisisCuantitativo_Escenario2 AS DACE INNER JOIN T_AnalisisCuantitativo_Escenario2 AS ACE ON ACE.id_analisiscuantitativo = DACE.id_analisiscuantitativo WHERE ACE.year_acuant = :year)*C.porcentaje)/(SELECT COUNT(id_agente) FROM T_Agentes WHERE id_circulo = :circulo) AS EneroEstacionariedad,
					((SELECT SUM(DACE.mes2_Ej*DACE.costo_ac) FROM T_Detalle_AnalisisCuantitativo_Escenario2 AS DACE INNER JOIN T_AnalisisCuantitativo_Escenario2 AS ACE ON ACE.id_analisiscuantitativo = DACE.id_analisiscuantitativo WHERE ACE.year_acuant = :year)*C.porcentaje)/(SELECT COUNT(id_agente) FROM T_Agentes WHERE id_circulo = :circulo) AS FebreroEstacionariedad,
					((SELECT SUM(DACE.mes3_Ej*DACE.costo_ac) FROM T_Detalle_AnalisisCuantitativo_Escenario2 AS DACE INNER JOIN T_AnalisisCuantitativo_Escenario2 AS ACE ON ACE.id_analisiscuantitativo = DACE.id_analisiscuantitativo WHERE ACE.year_acuant = :year)*C.porcentaje)/(SELECT COUNT(id_agente) FROM T_Agentes WHERE id_circulo = :circulo) AS MarzoEstacionariedad,
					((SELECT SUM(DACE.mes4_Ej*DACE.costo_ac) FROM T_Detalle_AnalisisCuantitativo_Escenario2 AS DACE INNER JOIN T_AnalisisCuantitativo_Escenario2 AS ACE ON ACE.id_analisiscuantitativo = DACE.id_analisiscuantitativo WHERE ACE.year_acuant = :year)*C.porcentaje)/(SELECT COUNT(id_agente) FROM T_Agentes WHERE id_circulo = :circulo) AS AbrilEstacionariedad,
					((SELECT SUM(DACE.mes5_Ej*DACE.costo_ac) FROM T_Detalle_AnalisisCuantitativo_Escenario2 AS DACE INNER JOIN T_AnalisisCuantitativo_Escenario2 AS ACE ON ACE.id_analisiscuantitativo = DACE.id_analisiscuantitativo WHERE ACE.year_acuant = :year)*C.porcentaje)/(SELECT COUNT(id_agente) FROM T_Agentes WHERE id_circulo = :circulo) AS MayoEstacionariedad,
					((SELECT SUM(DACE.mes6_Ej*DACE.costo_ac) FROM T_Detalle_AnalisisCuantitativo_Escenario2 AS DACE INNER JOIN T_AnalisisCuantitativo_Escenario2 AS ACE ON ACE.id_analisiscuantitativo = DACE.id_analisiscuantitativo WHERE ACE.year_acuant = :year)*C.porcentaje)/(SELECT COUNT(id_agente) FROM T_Agentes WHERE id_circulo = :circulo) AS JunioEstacionariedad,
					((SELECT SUM(DACE.mes7_Ej*DACE.costo_ac) FROM T_Detalle_AnalisisCuantitativo_Escenario2 AS DACE INNER JOIN T_AnalisisCuantitativo_Escenario2 AS ACE ON ACE.id_analisiscuantitativo = DACE.id_analisiscuantitativo WHERE ACE.year_acuant = :year)*C.porcentaje)/(SELECT COUNT(id_agente) FROM T_Agentes WHERE id_circulo = :circulo) AS JulioEstacionariedad,
					((SELECT SUM(DACE.mes8_Ej*DACE.costo_ac) FROM T_Detalle_AnalisisCuantitativo_Escenario2 AS DACE INNER JOIN T_AnalisisCuantitativo_Escenario2 AS ACE ON ACE.id_analisiscuantitativo = DACE.id_analisiscuantitativo WHERE ACE.year_acuant = :year)*C.porcentaje)/(SELECT COUNT(id_agente) FROM T_Agentes WHERE id_circulo = :circulo) AS AgostoEstacionariedad,
					((SELECT SUM(DACE.mes9_Ej*DACE.costo_ac) FROM T_Detalle_AnalisisCuantitativo_Escenario2 AS DACE INNER JOIN T_AnalisisCuantitativo_Escenario2 AS ACE ON ACE.id_analisiscuantitativo = DACE.id_analisiscuantitativo WHERE ACE.year_acuant = :year)*C.porcentaje)/(SELECT COUNT(id_agente) FROM T_Agentes WHERE id_circulo = :circulo) AS SeptiembreEstacionariedad,
					((SELECT SUM(DACE.mes10_Ej*DACE.costo_ac) FROM T_Detalle_AnalisisCuantitativo_Escenario2 AS DACE INNER JOIN T_AnalisisCuantitativo_Escenario2 AS ACE ON ACE.id_analisiscuantitativo = DACE.id_analisiscuantitativo WHERE ACE.year_acuant = :year)*C.porcentaje)/(SELECT COUNT(id_agente) FROM T_Agentes WHERE id_circulo = :circulo) AS OctubreEstacionariedad,
					((SELECT SUM(DACE.mes11_Ej*DACE.costo_ac) FROM T_Detalle_AnalisisCuantitativo_Escenario2 AS DACE INNER JOIN T_AnalisisCuantitativo_Escenario2 AS ACE ON ACE.id_analisiscuantitativo = DACE.id_analisiscuantitativo WHERE ACE.year_acuant = :year)*C.porcentaje)/(SELECT COUNT(id_agente) FROM T_Agentes WHERE id_circulo = :circulo) AS NoviembreEstacionariedad,
					((SELECT SUM(DACE.mes12_Ej*DACE.costo_ac) FROM T_Detalle_AnalisisCuantitativo_Escenario2 AS DACE INNER JOIN T_AnalisisCuantitativo_Escenario2 AS ACE ON ACE.id_analisiscuantitativo = DACE.id_analisiscuantitativo WHERE ACE.year_acuant = :year)*C.porcentaje)/(SELECT COUNT(id_agente) FROM T_Agentes WHERE id_circulo = :circulo) AS DiciembreEstacionariedad,
					((SELECT SUM((DACE.mes1_Ej+DACE.mes2_Ej+DACE.mes3_Ej+DACE.mes4_Ej+DACE.mes5_Ej+DACE.mes6_Ej+DACE.mes7_Ej+DACE.mes8_Ej+DACE.mes9_Ej+DACE.mes10_Ej+DACE.mes11_Ej+DACE.mes12_Ej)*DACE.costo_ac) FROM T_Detalle_AnalisisCuantitativo_Escenario2 AS DACE INNER JOIN T_AnalisisCuantitativo_Escenario2 AS ACE ON ACE.id_analisiscuantitativo = DACE.id_analisiscuantitativo WHERE ACE.year_acuant = :year)*C.porcentaje)/(SELECT COUNT(id_agente) FROM T_Agentes WHERE id_circulo = 1) AS TotalEstacionariedad,
					(SELECT SUM(DBA.costo) FROM T_Detalle_Bitacora_Agente AS DBA INNER JOIN T_Detalle_Bitacora AS DB ON DB.id_detallebitacora = DBA.id_detallebitacora WHERE YEAR(DB.ultima_modificacion) = :year AND DBA.id_agente = A.id_agente AND DB.id_mesejecucion = 1) AS EneroBitacora,
					(SELECT SUM(DBA.costo) FROM T_Detalle_Bitacora_Agente AS DBA INNER JOIN T_Detalle_Bitacora AS DB ON DB.id_detallebitacora = DBA.id_detallebitacora WHERE YEAR(DB.ultima_modificacion) = :year AND DBA.id_agente = A.id_agente AND DB.id_mesejecucion = 2) AS FebreroBitacora,
					(SELECT SUM(DBA.costo) FROM T_Detalle_Bitacora_Agente AS DBA INNER JOIN T_Detalle_Bitacora AS DB ON DB.id_detallebitacora = DBA.id_detallebitacora WHERE YEAR(DB.ultima_modificacion) = :year AND DBA.id_agente = A.id_agente AND DB.id_mesejecucion = 3) AS MarzoBitacora,
					(SELECT SUM(DBA.costo) FROM T_Detalle_Bitacora_Agente AS DBA INNER JOIN T_Detalle_Bitacora AS DB ON DB.id_detallebitacora = DBA.id_detallebitacora WHERE YEAR(DB.ultima_modificacion) = :year AND DBA.id_agente = A.id_agente AND DB.id_mesejecucion = 4) AS AbrilBitacora,
					(SELECT SUM(DBA.costo) FROM T_Detalle_Bitacora_Agente AS DBA INNER JOIN T_Detalle_Bitacora AS DB ON DB.id_detallebitacora = DBA.id_detallebitacora WHERE YEAR(DB.ultima_modificacion) = :year AND DBA.id_agente = A.id_agente AND DB.id_mesejecucion = 5) AS MayoBitacora,
					(SELECT SUM(DBA.costo) FROM T_Detalle_Bitacora_Agente AS DBA INNER JOIN T_Detalle_Bitacora AS DB ON DB.id_detallebitacora = DBA.id_detallebitacora WHERE YEAR(DB.ultima_modificacion) = :year AND DBA.id_agente = A.id_agente AND DB.id_mesejecucion = 6) AS JunioBitacora,
					(SELECT SUM(DBA.costo) FROM T_Detalle_Bitacora_Agente AS DBA INNER JOIN T_Detalle_Bitacora AS DB ON DB.id_detallebitacora = DBA.id_detallebitacora WHERE YEAR(DB.ultima_modificacion) = :year AND DBA.id_agente = A.id_agente AND DB.id_mesejecucion = 7) AS JulioBitacora,
					(SELECT SUM(DBA.costo) FROM T_Detalle_Bitacora_Agente AS DBA INNER JOIN T_Detalle_Bitacora AS DB ON DB.id_detallebitacora = DBA.id_detallebitacora WHERE YEAR(DB.ultima_modificacion) = :year AND DBA.id_agente = A.id_agente AND DB.id_mesejecucion = 8) AS AgostoBitacora,
					(SELECT SUM(DBA.costo) FROM T_Detalle_Bitacora_Agente AS DBA INNER JOIN T_Detalle_Bitacora AS DB ON DB.id_detallebitacora = DBA.id_detallebitacora WHERE YEAR(DB.ultima_modificacion) = :year AND DBA.id_agente = A.id_agente AND DB.id_mesejecucion = 9) AS SeptiembreBitacora,
					(SELECT SUM(DBA.costo) FROM T_Detalle_Bitacora_Agente AS DBA INNER JOIN T_Detalle_Bitacora AS DB ON DB.id_detallebitacora = DBA.id_detallebitacora WHERE YEAR(DB.ultima_modificacion) = :year AND DBA.id_agente = A.id_agente AND DB.id_mesejecucion = 10) AS OctubreBitacora,
					(SELECT SUM(DBA.costo) FROM T_Detalle_Bitacora_Agente AS DBA INNER JOIN T_Detalle_Bitacora AS DB ON DB.id_detallebitacora = DBA.id_detallebitacora WHERE YEAR(DB.ultima_modificacion) = :year AND DBA.id_agente = A.id_agente AND DB.id_mesejecucion = 11) AS NoviembreBitacora,
					(SELECT SUM(DBA.costo) FROM T_Detalle_Bitacora_Agente AS DBA INNER JOIN T_Detalle_Bitacora AS DB ON DB.id_detallebitacora = DBA.id_detallebitacora WHERE YEAR(DB.ultima_modificacion) = :year AND DBA.id_agente = A.id_agente AND DB.id_mesejecucion = 12) AS DiciembreBitacora,
					(SELECT SUM(DBA.costo) FROM T_Detalle_Bitacora_Agente AS DBA INNER JOIN T_Detalle_Bitacora AS DB ON DB.id_detallebitacora = DBA.id_detallebitacora WHERE YEAR(DB.ultima_modificacion) = :year AND DBA.id_agente = A.id_agente) AS TotalBitacora,
					(SELECT SUM(R.monto) FROM T_Rechazos AS R WHERE YEAR(R.fecha_rechazo) = :year AND R.id_agente = A.id_agente AND MONTH(R.fecha_rechazo) = 1) AS EneroRechazo,
					(SELECT SUM(R.monto) FROM T_Rechazos AS R WHERE YEAR(R.fecha_rechazo) = :year AND R.id_agente = A.id_agente AND MONTH(R.fecha_rechazo) = 2) AS FebreroRechazo,
					(SELECT SUM(R.monto) FROM T_Rechazos AS R WHERE YEAR(R.fecha_rechazo) = :year AND R.id_agente = A.id_agente AND MONTH(R.fecha_rechazo) = 3) AS MarzoRechazo,
					(SELECT SUM(R.monto) FROM T_Rechazos AS R WHERE YEAR(R.fecha_rechazo) = :year AND R.id_agente = A.id_agente AND MONTH(R.fecha_rechazo) = 4) AS AbrilRechazo,
					(SELECT SUM(R.monto) FROM T_Rechazos AS R WHERE YEAR(R.fecha_rechazo) = :year AND R.id_agente = A.id_agente AND MONTH(R.fecha_rechazo) = 5) AS MayoRechazo,
					(SELECT SUM(R.monto) FROM T_Rechazos AS R WHERE YEAR(R.fecha_rechazo) = :year AND R.id_agente = A.id_agente AND MONTH(R.fecha_rechazo) = 6) AS JunioRechazo,
					(SELECT SUM(R.monto) FROM T_Rechazos AS R WHERE YEAR(R.fecha_rechazo) = :year AND R.id_agente = A.id_agente AND MONTH(R.fecha_rechazo) = 7) AS JulioRechazo,
					(SELECT SUM(R.monto) FROM T_Rechazos AS R WHERE YEAR(R.fecha_rechazo) = :year AND R.id_agente = A.id_agente AND MONTH(R.fecha_rechazo) = 8) AS AgostoRechazo,
					(SELECT SUM(R.monto) FROM T_Rechazos AS R WHERE YEAR(R.fecha_rechazo) = :year AND R.id_agente = A.id_agente AND MONTH(R.fecha_rechazo) = 9) AS SeptiembreRechazo,
					(SELECT SUM(R.monto) FROM T_Rechazos AS R WHERE YEAR(R.fecha_rechazo) = :year AND R.id_agente = A.id_agente AND MONTH(R.fecha_rechazo) = 10) AS OctubreRechazo,
					(SELECT SUM(R.monto) FROM T_Rechazos AS R WHERE YEAR(R.fecha_rechazo) = :year AND R.id_agente = A.id_agente AND MONTH(R.fecha_rechazo) = 11) AS NoviembreRechazo,
					(SELECT SUM(R.monto) FROM T_Rechazos AS R WHERE YEAR(R.fecha_rechazo) = :year AND R.id_agente = A.id_agente AND MONTH(R.fecha_rechazo) = 12) AS DiciembreRechazo,
					(SELECT SUM(R.monto) FROM T_Rechazos AS R WHERE YEAR(R.fecha_rechazo) = :year AND R.id_agente = A.id_agente) AS TotalRechazo
				FROM T_Agentes AS A
				INNER JOIN T_Circulos AS C
				ON C.id_circulo = A.id_circulo
				WHERE A.id_circulo = :circulo;");
				$stmt->bindparam(":year",$year);
				$stmt->bindparam(":circulo",$circulo);
				$stmt->execute();
				$events = array();
				$e = array();
				while($row=$stmt->fetch(PDO::FETCH_ASSOC))
				{
					?>
					<tr>
						<th><?php print($row['nombreAgente']); ?></th>
						<td><?php print(number_format($row['oferta_actual'],2)); ?></td>
						<td><?php print(number_format($row['EneroEstacionariedad'],2)); ?></td>
						<td><?php print(number_format($row['FebreroEstacionariedad'],2)); ?></td>
						<td><?php print(number_format($row['EneroEstacionariedad']+$row['FebreroEstacionariedad'],2)); ?></td>
						<td><?php print(number_format($row['MarzoEstacionariedad'],2)); ?></td>
						<td><?php print(number_format($row['EneroEstacionariedad']+$row['FebreroEstacionariedad']+$row['MarzoEstacionariedad'],2)); ?></td>
						<td><?php print(number_format($row['AbrilEstacionariedad'],2)); ?></td>
						<td><?php print(number_format($row['EneroEstacionariedad']+$row['FebreroEstacionariedad']+$row['MarzoEstacionariedad']+$row['AbrilEstacionariedad'],2)); ?></td>
						<td><?php print(number_format($row['MayoEstacionariedad'],2)); ?></td>
						<td><?php print(number_format($row['EneroEstacionariedad']+$row['FebreroEstacionariedad']+$row['MarzoEstacionariedad']+$row['AbrilEstacionariedad']+$row['MayoEstacionariedad'],2)); ?></td>
						<td><?php print(number_format($row['JunioEstacionariedad'],2)); ?></td>
						<td><?php print(number_format($row['EneroEstacionariedad']+$row['FebreroEstacionariedad']+$row['MarzoEstacionariedad']+$row['AbrilEstacionariedad']+$row['MayoEstacionariedad']+$row['JunioEstacionariedad'],2)); ?></td>
						<td><?php print(number_format($row['JulioEstacionariedad'],2)); ?></td>
						<td><?php print(number_format($row['EneroEstacionariedad']+$row['FebreroEstacionariedad']+$row['MarzoEstacionariedad']+$row['AbrilEstacionariedad']+$row['MayoEstacionariedad']+$row['JunioEstacionariedad']+$row['JulioEstacionariedad'],2)); ?></td>
						<td><?php print(number_format($row['AgostoEstacionariedad'],2)); ?></td>
						<td><?php print(number_format($row['EneroEstacionariedad']+$row['FebreroEstacionariedad']+$row['MarzoEstacionariedad']+$row['AbrilEstacionariedad']+$row['MayoEstacionariedad']+$row['JunioEstacionariedad']+$row['JulioEstacionariedad']+$row['AgostoEstacionariedad'],2)); ?></td>
						<td><?php print(number_format($row['SeptiembreEstacionariedad'],2)); ?></td>
						<td><?php print(number_format($row['EneroEstacionariedad']+$row['FebreroEstacionariedad']+$row['MarzoEstacionariedad']+$row['AbrilEstacionariedad']+$row['MayoEstacionariedad']+$row['JunioEstacionariedad']+$row['JulioEstacionariedad']+$row['AgostoEstacionariedad']+$row['SeptiembreEstacionariedad'],2)); ?></td>
						<td><?php print(number_format($row['OctubreEstacionariedad'],2)); ?></td>
						<td><?php print(number_format($row['EneroEstacionariedad']+$row['FebreroEstacionariedad']+$row['MarzoEstacionariedad']+$row['AbrilEstacionariedad']+$row['MayoEstacionariedad']+$row['JunioEstacionariedad']+$row['JulioEstacionariedad']+$row['AgostoEstacionariedad']+$row['SeptiembreEstacionariedad']+$row['OctubreEstacionariedad'],2)); ?></td>
						<td><?php print(number_format($row['NoviembreEstacionariedad'],2)); ?></td>
						<td><?php print(number_format($row['EneroEstacionariedad']+$row['FebreroEstacionariedad']+$row['MarzoEstacionariedad']+$row['AbrilEstacionariedad']+$row['MayoEstacionariedad']+$row['JunioEstacionariedad']+$row['JulioEstacionariedad']+$row['AgostoEstacionariedad']+$row['SeptiembreEstacionariedad']+$row['OctubreEstacionariedad']+$row['NoviembreEstacionariedad'],2)); ?></td>
						<td><?php print(number_format($row['DiciembreEstacionariedad'],2)); ?></td>
					</tr>
					<tr>
						<td>Honorarios programados en bitácora</td>
						<td><?php print(number_format($row['TotalBitacora'],2)); ?></td>
						<td><?php print(number_format($row['EneroBitacora'],2)); ?></td>
						<td><?php print(number_format($row['FebreroBitacora'],2)); ?></td>
						<td><?php print(number_format($row['EneroBitacora']+$row['FebreroBitacora'],2)); ?></td>
						<td><?php print(number_format($row['MarzoBitacora'],2)); ?></td>
						<td><?php print(number_format($row['EneroBitacora']+$row['FebreroBitacora']+$row['MarzoBitacora'],2)); ?></td>
						<td><?php print(number_format($row['AbrilBitacora'],2)); ?></td>
						<td><?php print(number_format($row['EneroBitacora']+$row['FebreroBitacora']+$row['MarzoBitacora']+$row['AbrilBitacora'],2)); ?></td>
						<td><?php print(number_format($row['MayoBitacora'],2)); ?></td>
						<td><?php print(number_format($row['EneroBitacora']+$row['FebreroBitacora']+$row['MarzoBitacora']+$row['AbrilBitacora']+$row['MayoBitacora'],2)); ?></td>
						<td><?php print(number_format($row['JunioBitacora'],2)); ?></td>
						<td><?php print(number_format($row['EneroBitacora']+$row['FebreroBitacora']+$row['MarzoBitacora']+$row['AbrilBitacora']+$row['MayoBitacora']+$row['JunioBitacora'],2)); ?></td>
						<td><?php print(number_format($row['JulioBitacora'],2)); ?></td>
						<td><?php print(number_format($row['EneroBitacora']+$row['FebreroBitacora']+$row['MarzoBitacora']+$row['AbrilBitacora']+$row['MayoBitacora']+$row['JunioBitacora']+$row['JulioBitacora'],2)); ?></td>
						<td><?php print(number_format($row['AgostoBitacora'],2)); ?></td>
						<td><?php print(number_format($row['EneroBitacora']+$row['FebreroBitacora']+$row['MarzoBitacora']+$row['AbrilBitacora']+$row['MayoBitacora']+$row['JunioBitacora']+$row['JulioBitacora']+$row['AgostoBitacora'],2)); ?></td>
						<td><?php print(number_format($row['SeptiembreBitacora'],2)); ?></td>
						<td><?php print(number_format($row['EneroBitacora']+$row['FebreroBitacora']+$row['MarzoBitacora']+$row['AbrilBitacora']+$row['MayoBitacora']+$row['JunioBitacora']+$row['JulioBitacora']+$row['AgostoBitacora']+$row['SeptiembreBitacora'],2)); ?></td>
						<td><?php print(number_format($row['OctubreBitacora'],2)); ?></td>
						<td><?php print(number_format($row['EneroBitacora']+$row['FebreroBitacora']+$row['MarzoBitacora']+$row['AbrilBitacora']+$row['MayoBitacora']+$row['JunioBitacora']+$row['JulioBitacora']+$row['AgostoBitacora']+$row['SeptiembreBitacora']+$row['OctubreBitacora'],2)); ?></td>
						<td><?php print(number_format($row['NoviembreBitacora'],2)); ?></td>
						<td><?php print(number_format($row['EneroBitacora']+$row['FebreroBitacora']+$row['MarzoBitacora']+$row['AbrilBitacora']+$row['MayoBitacora']+$row['JunioBitacora']+$row['JulioBitacora']+$row['AgostoBitacora']+$row['SeptiembreBitacora']+$row['OctubreBitacora']+$row['NoviembreBitacora'],2)); ?></td>
						<td><?php print(number_format($row['DiciembreBitacora'],2)); ?></td>
					</tr>
					<tr>
						<td>Diferencia</td>
						<td><?php print(number_format($row['TotalBitacora']-$row['oferta_actual'],2)); ?></td>
						<td><?php print(number_format($row['EneroBitacora']-$row['EneroEstacionariedad'],2)); ?></td>
						<td><?php print(number_format($row['FebreroBitacora']-$row['FebreroEstacionariedad'],2)); ?></td>
						<td><?php print(number_format(0,2)); ?></td>
						<td><?php print(number_format($row['MarzoBitacora']-$row['MarzoEstacionariedad'],2)); ?></td>
						<td><?php print(number_format(0,2)); ?></td>
						<td><?php print(number_format($row['AbrilBitacora']-$row['AbrilEstacionariedad'],2)); ?></td>
						<td><?php print(number_format(0,2)); ?></td>
						<td><?php print(number_format($row['MayoBitacora']-$row['MayoEstacionariedad'],2)); ?></td>
						<td><?php print(number_format(0,2)); ?></td>
						<td><?php print(number_format($row['JunioBitacora']-$row['JunioEstacionariedad'],2)); ?></td>
						<td><?php print(number_format(0,2)); ?></td>
						<td><?php print(number_format($row['JulioBitacora']-$row['JulioEstacionariedad'],2)); ?></td>
						<td><?php print(number_format(0,2)); ?></td>
						<td><?php print(number_format($row['AgostoBitacora']-$row['AgostoEstacionariedad'],2)); ?></td>
						<td><?php print(number_format(0,2)); ?></td>
						<td><?php print(number_format($row['SeptiembreBitacora']-$row['SeptiembreEstacionariedad'],2)); ?></td>
						<td><?php print(number_format(0,2)); ?></td>
						<td><?php print(number_format($row['OctubreBitacora']-$row['OctubreEstacionariedad'],2)); ?></td>
						<td><?php print(number_format(0,2)); ?></td>
						<td><?php print(number_format($row['NoviembreBitacora']-$row['NoviembreEstacionariedad'],2)); ?></td>
						<td><?php print(number_format(0,2)); ?></td>
						<td><?php print(number_format($row['DiciembreBitacora']-$row['DiciembreEstacionariedad'],2)); ?></td>
					</tr>
					<tr>
						<td>Rechazado</td>
						<td><?php print(number_format($row['TotalRechazo'],2)); ?></td>
						<td><?php print(number_format($row['EneroRechazo'],2)); ?></td>
						<td><?php print(number_format($row['FebreroRechazo'],2)); ?></td>
						<td><?php print(number_format($row['EneroRechazo']+$row['FebreroRechazo'],2)); ?></td>
						<td><?php print(number_format($row['MarzoRechazo'],2)); ?></td>
						<td><?php print(number_format($row['EneroRechazo']+$row['FebreroRechazo']+$row['MarzoRechazo'],2)); ?></td>
						<td><?php print(number_format($row['AbrilRechazo'],2)); ?></td>
						<td><?php print(number_format($row['EneroRechazo']+$row['FebreroRechazo']+$row['MarzoRechazo']+$row['AbrilRechazo'],2)); ?></td>
						<td><?php print(number_format($row['MayoRechazo'],2)); ?></td>
						<td><?php print(number_format($row['EneroRechazo']+$row['FebreroRechazo']+$row['MarzoRechazo']+$row['AbrilRechazo']+$row['MayoRechazo'],2)); ?></td>
						<td><?php print(number_format($row['JunioRechazo'],2)); ?></td>
						<td><?php print(number_format($row['EneroRechazo']+$row['FebreroRechazo']+$row['MarzoRechazo']+$row['AbrilRechazo']+$row['MayoRechazo']+$row['JunioRechazo'],2)); ?></td>
						<td><?php print(number_format($row['JulioRechazo'],2)); ?></td>
						<td><?php print(number_format($row['EneroRechazo']+$row['FebreroRechazo']+$row['MarzoRechazo']+$row['AbrilRechazo']+$row['MayoRechazo']+$row['JunioRechazo']+$row['JulioRechazo'],2)); ?></td>
						<td><?php print(number_format($row['AgostoRechazo'],2)); ?></td>
						<td><?php print(number_format($row['EneroRechazo']+$row['FebreroRechazo']+$row['MarzoRechazo']+$row['AbrilRechazo']+$row['MayoRechazo']+$row['JunioRechazo']+$row['JulioRechazo']+$row['AgostoRechazo'],2)); ?></td>
						<td><?php print(number_format($row['SeptiembreRechazo'],2)); ?></td>
						<td><?php print(number_format($row['EneroRechazo']+$row['FebreroRechazo']+$row['MarzoRechazo']+$row['AbrilRechazo']+$row['MayoRechazo']+$row['JunioRechazo']+$row['JulioRechazo']+$row['AgostoRechazo']+$row['SeptiembreRechazo'],2)); ?></td>
						<td><?php print(number_format($row['OctubreRechazo'],2)); ?></td>
						<td><?php print(number_format($row['EneroRechazo']+$row['FebreroRechazo']+$row['MarzoRechazo']+$row['AbrilRechazo']+$row['MayoRechazo']+$row['JunioRechazo']+$row['JulioRechazo']+$row['AgostoRechazo']+$row['SeptiembreRechazo']+$row['OctubreRechazo'],2)); ?></td>
						<td><?php print(number_format($row['NoviembreRechazo'],2)); ?></td>
						<td><?php print(number_format($row['EneroRechazo']+$row['FebreroRechazo']+$row['MarzoRechazo']+$row['AbrilRechazo']+$row['MayoRechazo']+$row['JunioRechazo']+$row['JulioRechazo']+$row['AgostoRechazo']+$row['SeptiembreRechazo']+$row['OctubreRechazo']+$row['NoviembreRechazo'],2)); ?></td>
						<td><?php print(number_format($row['DiciembreRechazo'],2)); ?></td>
					</tr>
					<tr>
						<td>Diferencia</td>
						<td><?php print(number_format(($row['TotalRechazo']+$row['TotalBitacora'])-$row['oferta_actual'],2)); ?></td>
						<td><?php print(number_format(($row['EneroRechazo']+$row['EneroBitacora'])-$row['EneroEstacionariedad'],2)); ?></td>
						<td><?php print(number_format(($row['FebreroRechazo']+$row['FebreroBitacora'])-$row['FebreroEstacionariedad'],2)); ?></td>
						<td><?php print(number_format(0,2)); ?></td>
						<td><?php print(number_format(($row['MarzoRechazo']+$row['MarzoBitacora'])-$row['MarzoEstacionariedad'],2)); ?></td>
						<td><?php print(number_format(0,2)); ?></td>
						<td><?php print(number_format(($row['AbrilRechazo']+$row['AbrilBitacora'])-$row['AbrilEstacionariedad'],2)); ?></td>
						<td><?php print(number_format(0,2)); ?></td>
						<td><?php print(number_format(($row['MayoRechazo']+$row['MayoBitacora'])-$row['MayoEstacionariedad'],2)); ?></td>
						<td><?php print(number_format(0,2)); ?></td>
						<td><?php print(number_format(($row['JunioRechazo']+$row['JunioBitacora'])-$row['JunioEstacionariedad'],2)); ?></td>
						<td><?php print(number_format(0,2)); ?></td>
						<td><?php print(number_format(($row['JulioRechazo']+$row['JulioBitacora'])-$row['JulioEstacionariedad'],2)); ?></td>
						<td><?php print(number_format(0,2)); ?></td>
						<td><?php print(number_format(($row['AgostoRechazo']+$row['AgostoBitacora'])-$row['AgostoEstacionariedad'],2)); ?></td>
						<td><?php print(number_format(0,2)); ?></td>
						<td><?php print(number_format(($row['SeptiembreRechazo']+$row['SeptiembreBitacora'])-$row['SeptiembreEstacionariedad'],2)); ?></td>
						<td><?php print(number_format(0,2)); ?></td>
						<td><?php print(number_format(($row['OctubreRechazo']+$row['OctubreBitacora'])-$row['OctubreEstacionariedad'],2)); ?></td>
						<td><?php print(number_format(0,2)); ?></td>
						<td><?php print(number_format(($row['NoviembreRechazo']+$row['NoviembreBitacora'])-$row['NoviembreEstacionariedad'],2)); ?></td>
						<td><?php print(number_format(0,2)); ?></td>
						<td><?php print(number_format(($row['DiciembreRechazo']+$row['DiciembreBitacora'])-$row['DiciembreEstacionariedad'],2)); ?></td>
					</tr>
					<?php
				}
				return true;
			}
			catch(PDOException $e)
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
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<?php
				return false;
			}
		}
		public function getCirculoTopTenTotales($circulo,$year)
		{
			try
			{
				$stmt = $this->conn->prepare("SELECT 
					SUM(A.oferta_actual) AS oferta_actual,
					((SELECT SUM(DACE.mes1_Ej*DACE.costo_ac) FROM T_Detalle_AnalisisCuantitativo_Escenario2 AS DACE INNER JOIN T_AnalisisCuantitativo_Escenario2 AS ACE ON ACE.id_analisiscuantitativo = DACE.id_analisiscuantitativo WHERE ACE.year_acuant = :year)*C.porcentaje)/(SELECT COUNT(id_agente) FROM T_Agentes WHERE id_circulo = :circulo) AS EneroEstacionariedad,
					((SELECT SUM(DACE.mes2_Ej*DACE.costo_ac) FROM T_Detalle_AnalisisCuantitativo_Escenario2 AS DACE INNER JOIN T_AnalisisCuantitativo_Escenario2 AS ACE ON ACE.id_analisiscuantitativo = DACE.id_analisiscuantitativo WHERE ACE.year_acuant = :year)*C.porcentaje)/(SELECT COUNT(id_agente) FROM T_Agentes WHERE id_circulo = :circulo) AS FebreroEstacionariedad,
					((SELECT SUM(DACE.mes3_Ej*DACE.costo_ac) FROM T_Detalle_AnalisisCuantitativo_Escenario2 AS DACE INNER JOIN T_AnalisisCuantitativo_Escenario2 AS ACE ON ACE.id_analisiscuantitativo = DACE.id_analisiscuantitativo WHERE ACE.year_acuant = :year)*C.porcentaje)/(SELECT COUNT(id_agente) FROM T_Agentes WHERE id_circulo = :circulo) AS MarzoEstacionariedad,
					((SELECT SUM(DACE.mes4_Ej*DACE.costo_ac) FROM T_Detalle_AnalisisCuantitativo_Escenario2 AS DACE INNER JOIN T_AnalisisCuantitativo_Escenario2 AS ACE ON ACE.id_analisiscuantitativo = DACE.id_analisiscuantitativo WHERE ACE.year_acuant = :year)*C.porcentaje)/(SELECT COUNT(id_agente) FROM T_Agentes WHERE id_circulo = :circulo) AS AbrilEstacionariedad,
					((SELECT SUM(DACE.mes5_Ej*DACE.costo_ac) FROM T_Detalle_AnalisisCuantitativo_Escenario2 AS DACE INNER JOIN T_AnalisisCuantitativo_Escenario2 AS ACE ON ACE.id_analisiscuantitativo = DACE.id_analisiscuantitativo WHERE ACE.year_acuant = :year)*C.porcentaje)/(SELECT COUNT(id_agente) FROM T_Agentes WHERE id_circulo = :circulo) AS MayoEstacionariedad,
					((SELECT SUM(DACE.mes6_Ej*DACE.costo_ac) FROM T_Detalle_AnalisisCuantitativo_Escenario2 AS DACE INNER JOIN T_AnalisisCuantitativo_Escenario2 AS ACE ON ACE.id_analisiscuantitativo = DACE.id_analisiscuantitativo WHERE ACE.year_acuant = :year)*C.porcentaje)/(SELECT COUNT(id_agente) FROM T_Agentes WHERE id_circulo = :circulo) AS JunioEstacionariedad,
					((SELECT SUM(DACE.mes7_Ej*DACE.costo_ac) FROM T_Detalle_AnalisisCuantitativo_Escenario2 AS DACE INNER JOIN T_AnalisisCuantitativo_Escenario2 AS ACE ON ACE.id_analisiscuantitativo = DACE.id_analisiscuantitativo WHERE ACE.year_acuant = :year)*C.porcentaje)/(SELECT COUNT(id_agente) FROM T_Agentes WHERE id_circulo = :circulo) AS JulioEstacionariedad,
					((SELECT SUM(DACE.mes8_Ej*DACE.costo_ac) FROM T_Detalle_AnalisisCuantitativo_Escenario2 AS DACE INNER JOIN T_AnalisisCuantitativo_Escenario2 AS ACE ON ACE.id_analisiscuantitativo = DACE.id_analisiscuantitativo WHERE ACE.year_acuant = :year)*C.porcentaje)/(SELECT COUNT(id_agente) FROM T_Agentes WHERE id_circulo = :circulo) AS AgostoEstacionariedad,
					((SELECT SUM(DACE.mes9_Ej*DACE.costo_ac) FROM T_Detalle_AnalisisCuantitativo_Escenario2 AS DACE INNER JOIN T_AnalisisCuantitativo_Escenario2 AS ACE ON ACE.id_analisiscuantitativo = DACE.id_analisiscuantitativo WHERE ACE.year_acuant = :year)*C.porcentaje)/(SELECT COUNT(id_agente) FROM T_Agentes WHERE id_circulo = :circulo) AS SeptiembreEstacionariedad,
					((SELECT SUM(DACE.mes10_Ej*DACE.costo_ac) FROM T_Detalle_AnalisisCuantitativo_Escenario2 AS DACE INNER JOIN T_AnalisisCuantitativo_Escenario2 AS ACE ON ACE.id_analisiscuantitativo = DACE.id_analisiscuantitativo WHERE ACE.year_acuant = :year)*C.porcentaje)/(SELECT COUNT(id_agente) FROM T_Agentes WHERE id_circulo = :circulo) AS OctubreEstacionariedad,
					((SELECT SUM(DACE.mes11_Ej*DACE.costo_ac) FROM T_Detalle_AnalisisCuantitativo_Escenario2 AS DACE INNER JOIN T_AnalisisCuantitativo_Escenario2 AS ACE ON ACE.id_analisiscuantitativo = DACE.id_analisiscuantitativo WHERE ACE.year_acuant = :year)*C.porcentaje)/(SELECT COUNT(id_agente) FROM T_Agentes WHERE id_circulo = :circulo) AS NoviembreEstacionariedad,
					((SELECT SUM(DACE.mes12_Ej*DACE.costo_ac) FROM T_Detalle_AnalisisCuantitativo_Escenario2 AS DACE INNER JOIN T_AnalisisCuantitativo_Escenario2 AS ACE ON ACE.id_analisiscuantitativo = DACE.id_analisiscuantitativo WHERE ACE.year_acuant = :year)*C.porcentaje)/(SELECT COUNT(id_agente) FROM T_Agentes WHERE id_circulo = :circulo) AS DiciembreEstacionariedad,
					((SELECT SUM((DACE.mes1_Ej+DACE.mes2_Ej+DACE.mes3_Ej+DACE.mes4_Ej+DACE.mes5_Ej+DACE.mes6_Ej+DACE.mes7_Ej+DACE.mes8_Ej+DACE.mes9_Ej+DACE.mes10_Ej+DACE.mes11_Ej+DACE.mes12_Ej)*DACE.costo_ac) FROM T_Detalle_AnalisisCuantitativo_Escenario2 AS DACE INNER JOIN T_AnalisisCuantitativo_Escenario2 AS ACE ON ACE.id_analisiscuantitativo = DACE.id_analisiscuantitativo WHERE ACE.year_acuant = :year)*C.porcentaje)/(SELECT COUNT(id_agente) FROM T_Agentes WHERE id_circulo = 1) AS TotalEstacionariedad,
					(SELECT SUM(DBA.costo) FROM T_Detalle_Bitacora_Agente AS DBA INNER JOIN T_Agentes AS AG ON AG.id_agente = DBA.id_agente INNER JOIN T_Detalle_Bitacora AS DB ON DB.id_detallebitacora = DBA.id_detallebitacora WHERE YEAR(DB.ultima_modificacion) = :year AND AG.id_circulo = :circulo AND DB.id_mesejecucion = 1) AS EneroBitacora,
					(SELECT SUM(DBA.costo) FROM T_Detalle_Bitacora_Agente AS DBA INNER JOIN T_Agentes AS AG ON AG.id_agente = DBA.id_agente INNER JOIN T_Detalle_Bitacora AS DB ON DB.id_detallebitacora = DBA.id_detallebitacora WHERE YEAR(DB.ultima_modificacion) = :year AND AG.id_circulo = :circulo AND DB.id_mesejecucion = 2) AS FebreroBitacora,
					(SELECT SUM(DBA.costo) FROM T_Detalle_Bitacora_Agente AS DBA INNER JOIN T_Agentes AS AG ON AG.id_agente = DBA.id_agente INNER JOIN T_Detalle_Bitacora AS DB ON DB.id_detallebitacora = DBA.id_detallebitacora WHERE YEAR(DB.ultima_modificacion) = :year AND AG.id_circulo = :circulo AND DB.id_mesejecucion = 3) AS MarzoBitacora,
					(SELECT SUM(DBA.costo) FROM T_Detalle_Bitacora_Agente AS DBA INNER JOIN T_Agentes AS AG ON AG.id_agente = DBA.id_agente INNER JOIN T_Detalle_Bitacora AS DB ON DB.id_detallebitacora = DBA.id_detallebitacora WHERE YEAR(DB.ultima_modificacion) = :year AND AG.id_circulo = :circulo AND DB.id_mesejecucion = 4) AS AbrilBitacora,
					(SELECT SUM(DBA.costo) FROM T_Detalle_Bitacora_Agente AS DBA INNER JOIN T_Agentes AS AG ON AG.id_agente = DBA.id_agente INNER JOIN T_Detalle_Bitacora AS DB ON DB.id_detallebitacora = DBA.id_detallebitacora WHERE YEAR(DB.ultima_modificacion) = :year AND AG.id_circulo = :circulo AND DB.id_mesejecucion = 5) AS MayoBitacora,
					(SELECT SUM(DBA.costo) FROM T_Detalle_Bitacora_Agente AS DBA INNER JOIN T_Agentes AS AG ON AG.id_agente = DBA.id_agente INNER JOIN T_Detalle_Bitacora AS DB ON DB.id_detallebitacora = DBA.id_detallebitacora WHERE YEAR(DB.ultima_modificacion) = :year AND AG.id_circulo = :circulo AND DB.id_mesejecucion = 6) AS JunioBitacora,
					(SELECT SUM(DBA.costo) FROM T_Detalle_Bitacora_Agente AS DBA INNER JOIN T_Agentes AS AG ON AG.id_agente = DBA.id_agente INNER JOIN T_Detalle_Bitacora AS DB ON DB.id_detallebitacora = DBA.id_detallebitacora WHERE YEAR(DB.ultima_modificacion) = :year AND AG.id_circulo = :circulo AND DB.id_mesejecucion = 7) AS JulioBitacora,
					(SELECT SUM(DBA.costo) FROM T_Detalle_Bitacora_Agente AS DBA INNER JOIN T_Agentes AS AG ON AG.id_agente = DBA.id_agente INNER JOIN T_Detalle_Bitacora AS DB ON DB.id_detallebitacora = DBA.id_detallebitacora WHERE YEAR(DB.ultima_modificacion) = :year AND AG.id_circulo = :circulo AND DB.id_mesejecucion = 8) AS AgostoBitacora,
					(SELECT SUM(DBA.costo) FROM T_Detalle_Bitacora_Agente AS DBA INNER JOIN T_Agentes AS AG ON AG.id_agente = DBA.id_agente INNER JOIN T_Detalle_Bitacora AS DB ON DB.id_detallebitacora = DBA.id_detallebitacora WHERE YEAR(DB.ultima_modificacion) = :year AND AG.id_circulo = :circulo AND DB.id_mesejecucion = 9) AS SeptiembreBitacora,
					(SELECT SUM(DBA.costo) FROM T_Detalle_Bitacora_Agente AS DBA INNER JOIN T_Agentes AS AG ON AG.id_agente = DBA.id_agente INNER JOIN T_Detalle_Bitacora AS DB ON DB.id_detallebitacora = DBA.id_detallebitacora WHERE YEAR(DB.ultima_modificacion) = :year AND AG.id_circulo = :circulo AND DB.id_mesejecucion = 10) AS OctubreBitacora,
					(SELECT SUM(DBA.costo) FROM T_Detalle_Bitacora_Agente AS DBA INNER JOIN T_Agentes AS AG ON AG.id_agente = DBA.id_agente INNER JOIN T_Detalle_Bitacora AS DB ON DB.id_detallebitacora = DBA.id_detallebitacora WHERE YEAR(DB.ultima_modificacion) = :year AND AG.id_circulo = :circulo AND DB.id_mesejecucion = 11) AS NoviembreBitacora,
					(SELECT SUM(DBA.costo) FROM T_Detalle_Bitacora_Agente AS DBA INNER JOIN T_Agentes AS AG ON AG.id_agente = DBA.id_agente INNER JOIN T_Detalle_Bitacora AS DB ON DB.id_detallebitacora = DBA.id_detallebitacora WHERE YEAR(DB.ultima_modificacion) = :year AND AG.id_circulo = :circulo AND DB.id_mesejecucion = 12) AS DiciembreBitacora,
					(SELECT SUM(DBA.costo) FROM T_Detalle_Bitacora_Agente AS DBA INNER JOIN T_Agentes AS AG ON AG.id_agente = DBA.id_agente INNER JOIN T_Detalle_Bitacora AS DB ON DB.id_detallebitacora = DBA.id_detallebitacora WHERE YEAR(DB.ultima_modificacion) = :year AND AG.id_circulo = :circulo) AS TotalBitacora,
					(SELECT SUM(R.monto) FROM T_Rechazos AS R INNER JOIN T_Agentes AS AG ON AG.id_agente = R.id_agente WHERE YEAR(R.fecha_rechazo) = :year AND AG.id_circulo = :circulo AND MONTH(R.fecha_rechazo) = 1) AS EneroRechazo,
					(SELECT SUM(R.monto) FROM T_Rechazos AS R INNER JOIN T_Agentes AS AG ON AG.id_agente = R.id_agente WHERE YEAR(R.fecha_rechazo) = :year AND AG.id_circulo = :circulo AND MONTH(R.fecha_rechazo) = 2) AS FebreroRechazo,
					(SELECT SUM(R.monto) FROM T_Rechazos AS R INNER JOIN T_Agentes AS AG ON AG.id_agente = R.id_agente WHERE YEAR(R.fecha_rechazo) = :year AND AG.id_circulo = :circulo AND MONTH(R.fecha_rechazo) = 3) AS MarzoRechazo,
					(SELECT SUM(R.monto) FROM T_Rechazos AS R INNER JOIN T_Agentes AS AG ON AG.id_agente = R.id_agente WHERE YEAR(R.fecha_rechazo) = :year AND AG.id_circulo = :circulo AND MONTH(R.fecha_rechazo) = 4) AS AbrilRechazo,
					(SELECT SUM(R.monto) FROM T_Rechazos AS R INNER JOIN T_Agentes AS AG ON AG.id_agente = R.id_agente WHERE YEAR(R.fecha_rechazo) = :year AND AG.id_circulo = :circulo AND MONTH(R.fecha_rechazo) = 5) AS MayoRechazo,
					(SELECT SUM(R.monto) FROM T_Rechazos AS R INNER JOIN T_Agentes AS AG ON AG.id_agente = R.id_agente WHERE YEAR(R.fecha_rechazo) = :year AND AG.id_circulo = :circulo AND MONTH(R.fecha_rechazo) = 6) AS JunioRechazo,
					(SELECT SUM(R.monto) FROM T_Rechazos AS R INNER JOIN T_Agentes AS AG ON AG.id_agente = R.id_agente WHERE YEAR(R.fecha_rechazo) = :year AND AG.id_circulo = :circulo AND MONTH(R.fecha_rechazo) = 7) AS JulioRechazo,
					(SELECT SUM(R.monto) FROM T_Rechazos AS R INNER JOIN T_Agentes AS AG ON AG.id_agente = R.id_agente WHERE YEAR(R.fecha_rechazo) = :year AND AG.id_circulo = :circulo AND MONTH(R.fecha_rechazo) = 8) AS AgostoRechazo,
					(SELECT SUM(R.monto) FROM T_Rechazos AS R INNER JOIN T_Agentes AS AG ON AG.id_agente = R.id_agente WHERE YEAR(R.fecha_rechazo) = :year AND AG.id_circulo = :circulo AND MONTH(R.fecha_rechazo) = 9) AS SeptiembreRechazo,
					(SELECT SUM(R.monto) FROM T_Rechazos AS R INNER JOIN T_Agentes AS AG ON AG.id_agente = R.id_agente WHERE YEAR(R.fecha_rechazo) = :year AND AG.id_circulo = :circulo AND MONTH(R.fecha_rechazo) = 10) AS OctubreRechazo,
					(SELECT SUM(R.monto) FROM T_Rechazos AS R INNER JOIN T_Agentes AS AG ON AG.id_agente = R.id_agente WHERE YEAR(R.fecha_rechazo) = :year AND AG.id_circulo = :circulo AND MONTH(R.fecha_rechazo) = 11) AS NoviembreRechazo,
					(SELECT SUM(R.monto) FROM T_Rechazos AS R INNER JOIN T_Agentes AS AG ON AG.id_agente = R.id_agente WHERE YEAR(R.fecha_rechazo) = :year AND AG.id_circulo = :circulo AND MONTH(R.fecha_rechazo) = 12) AS DiciembreRechazo,
					(SELECT SUM(R.monto) FROM T_Rechazos AS R INNER JOIN T_Agentes AS AG ON AG.id_agente = R.id_agente WHERE YEAR(R.fecha_rechazo) = :year AND AG.id_circulo = :circulo) AS TotalRechazo
				FROM T_Agentes AS A
				INNER JOIN T_Circulos AS C
				ON C.id_circulo = A.id_circulo
				WHERE A.id_circulo = :circulo;");
				$stmt->bindparam(":year",$year);
				$stmt->bindparam(":circulo",$circulo);
				$stmt->execute();
				$events = array();
				$e = array();
				while($row=$stmt->fetch(PDO::FETCH_ASSOC))
				{
					?>
					<tr>
						<th>Subtotal pactado</th>
						<th><?php print(number_format($row['oferta_actual'],2)); ?></th>
						<th><?php print(number_format($row['EneroEstacionariedad'],2)); ?></th>
						<th><?php print(number_format($row['FebreroEstacionariedad'],2)); ?></th>
						<th><?php print(number_format($row['EneroEstacionariedad']+$row['FebreroEstacionariedad'],2)); ?></th>
						<th><?php print(number_format($row['MarzoEstacionariedad'],2)); ?></th>
						<th><?php print(number_format($row['EneroEstacionariedad']+$row['FebreroEstacionariedad']+$row['MarzoEstacionariedad'],2)); ?></th>
						<th><?php print(number_format($row['AbrilEstacionariedad'],2)); ?></th>
						<th><?php print(number_format($row['EneroEstacionariedad']+$row['FebreroEstacionariedad']+$row['MarzoEstacionariedad']+$row['AbrilEstacionariedad'],2)); ?></th>
						<th><?php print(number_format($row['MayoEstacionariedad'],2)); ?></th>
						<th><?php print(number_format($row['EneroEstacionariedad']+$row['FebreroEstacionariedad']+$row['MarzoEstacionariedad']+$row['AbrilEstacionariedad']+$row['MayoEstacionariedad'],2)); ?></th>
						<th><?php print(number_format($row['JunioEstacionariedad'],2)); ?></th>
						<th><?php print(number_format($row['EneroEstacionariedad']+$row['FebreroEstacionariedad']+$row['MarzoEstacionariedad']+$row['AbrilEstacionariedad']+$row['MayoEstacionariedad']+$row['JunioEstacionariedad'],2)); ?></th>
						<th><?php print(number_format($row['JulioEstacionariedad'],2)); ?></th>
						<th><?php print(number_format($row['EneroEstacionariedad']+$row['FebreroEstacionariedad']+$row['MarzoEstacionariedad']+$row['AbrilEstacionariedad']+$row['MayoEstacionariedad']+$row['JunioEstacionariedad']+$row['JulioEstacionariedad'],2)); ?></th>
						<th><?php print(number_format($row['AgostoEstacionariedad'],2)); ?></th>
						<th><?php print(number_format($row['EneroEstacionariedad']+$row['FebreroEstacionariedad']+$row['MarzoEstacionariedad']+$row['AbrilEstacionariedad']+$row['MayoEstacionariedad']+$row['JunioEstacionariedad']+$row['JulioEstacionariedad']+$row['AgostoEstacionariedad'],2)); ?></th>
						<th><?php print(number_format($row['SeptiembreEstacionariedad'],2)); ?></th>
						<th><?php print(number_format($row['EneroEstacionariedad']+$row['FebreroEstacionariedad']+$row['MarzoEstacionariedad']+$row['AbrilEstacionariedad']+$row['MayoEstacionariedad']+$row['JunioEstacionariedad']+$row['JulioEstacionariedad']+$row['AgostoEstacionariedad']+$row['SeptiembreEstacionariedad'],2)); ?></th>
						<th><?php print(number_format($row['OctubreEstacionariedad'],2)); ?></th>
						<th><?php print(number_format($row['EneroEstacionariedad']+$row['FebreroEstacionariedad']+$row['MarzoEstacionariedad']+$row['AbrilEstacionariedad']+$row['MayoEstacionariedad']+$row['JunioEstacionariedad']+$row['JulioEstacionariedad']+$row['AgostoEstacionariedad']+$row['SeptiembreEstacionariedad']+$row['OctubreEstacionariedad'],2)); ?></th>
						<th><?php print(number_format($row['NoviembreEstacionariedad'],2)); ?></th>
						<th><?php print(number_format($row['EneroEstacionariedad']+$row['FebreroEstacionariedad']+$row['MarzoEstacionariedad']+$row['AbrilEstacionariedad']+$row['MayoEstacionariedad']+$row['JunioEstacionariedad']+$row['JulioEstacionariedad']+$row['AgostoEstacionariedad']+$row['SeptiembreEstacionariedad']+$row['OctubreEstacionariedad']+$row['NoviembreEstacionariedad'],2)); ?></th>
						<th><?php print(number_format($row['DiciembreEstacionariedad'],2)); ?></th>
					</tr>
					<tr>
						<th>Subtotal real</th>
						<th><?php print(number_format($row['TotalBitacora'],2)); ?></th>
						<th><?php print(number_format($row['EneroBitacora'],2)); ?></th>
						<th><?php print(number_format($row['FebreroBitacora'],2)); ?></th>
						<th><?php print(number_format($row['EneroBitacora']+$row['FebreroBitacora'],2)); ?></th>
						<th><?php print(number_format($row['MarzoBitacora'],2)); ?></th>
						<th><?php print(number_format($row['EneroBitacora']+$row['FebreroBitacora']+$row['MarzoBitacora'],2)); ?></th>
						<th><?php print(number_format($row['AbrilBitacora'],2)); ?></th>
						<th><?php print(number_format($row['EneroBitacora']+$row['FebreroBitacora']+$row['MarzoBitacora']+$row['AbrilBitacora'],2)); ?></th>
						<th><?php print(number_format($row['MayoBitacora'],2)); ?></th>
						<th><?php print(number_format($row['EneroBitacora']+$row['FebreroBitacora']+$row['MarzoBitacora']+$row['AbrilBitacora']+$row['MayoBitacora'],2)); ?></th>
						<th><?php print(number_format($row['JunioBitacora'],2)); ?></th>
						<th><?php print(number_format($row['EneroBitacora']+$row['FebreroBitacora']+$row['MarzoBitacora']+$row['AbrilBitacora']+$row['MayoBitacora']+$row['JunioBitacora'],2)); ?></th>
						<th><?php print(number_format($row['JulioBitacora'],2)); ?></th>
						<th><?php print(number_format($row['EneroBitacora']+$row['FebreroBitacora']+$row['MarzoBitacora']+$row['AbrilBitacora']+$row['MayoBitacora']+$row['JunioBitacora']+$row['JulioBitacora'],2)); ?></th>
						<th><?php print(number_format($row['AgostoBitacora'],2)); ?></th>
						<th><?php print(number_format($row['EneroBitacora']+$row['FebreroBitacora']+$row['MarzoBitacora']+$row['AbrilBitacora']+$row['MayoBitacora']+$row['JunioBitacora']+$row['JulioBitacora']+$row['AgostoBitacora'],2)); ?></th>
						<th><?php print(number_format($row['SeptiembreBitacora'],2)); ?></th>
						<th><?php print(number_format($row['EneroBitacora']+$row['FebreroBitacora']+$row['MarzoBitacora']+$row['AbrilBitacora']+$row['MayoBitacora']+$row['JunioBitacora']+$row['JulioBitacora']+$row['AgostoBitacora']+$row['SeptiembreBitacora'],2)); ?></th>
						<th><?php print(number_format($row['OctubreBitacora'],2)); ?></th>
						<th><?php print(number_format($row['EneroBitacora']+$row['FebreroBitacora']+$row['MarzoBitacora']+$row['AbrilBitacora']+$row['MayoBitacora']+$row['JunioBitacora']+$row['JulioBitacora']+$row['AgostoBitacora']+$row['SeptiembreBitacora']+$row['OctubreBitacora'],2)); ?></th>
						<th><?php print(number_format($row['NoviembreBitacora'],2)); ?></th>
						<th><?php print(number_format($row['EneroBitacora']+$row['FebreroBitacora']+$row['MarzoBitacora']+$row['AbrilBitacora']+$row['MayoBitacora']+$row['JunioBitacora']+$row['JulioBitacora']+$row['AgostoBitacora']+$row['SeptiembreBitacora']+$row['OctubreBitacora']+$row['NoviembreBitacora'],2)); ?></th>
						<th><?php print(number_format($row['DiciembreBitacora'],2)); ?></th>
					</tr>
					<tr>
						<th>Diferencia</th>
						<th><?php print(number_format($row['TotalBitacora']-$row['oferta_actual'],2)); ?></th>
						<th><?php print(number_format($row['EneroBitacora']-$row['EneroEstacionariedad'],2)); ?></th>
						<th><?php print(number_format($row['FebreroBitacora']-$row['FebreroEstacionariedad'],2)); ?></th>
						<th><?php print(number_format(0,2)); ?></th>
						<th><?php print(number_format($row['MarzoBitacora']-$row['MarzoEstacionariedad'],2)); ?></th>
						<th><?php print(number_format(0,2)); ?></th>
						<th><?php print(number_format($row['AbrilBitacora']-$row['AbrilEstacionariedad'],2)); ?></th>
						<th><?php print(number_format(0,2)); ?></th>
						<th><?php print(number_format($row['MayoBitacora']-$row['MayoEstacionariedad'],2)); ?></th>
						<th><?php print(number_format(0,2)); ?></th>
						<th><?php print(number_format($row['JunioBitacora']-$row['JunioEstacionariedad'],2)); ?></th>
						<th><?php print(number_format(0,2)); ?></th>
						<th><?php print(number_format($row['JulioBitacora']-$row['JulioEstacionariedad'],2)); ?></th>
						<th><?php print(number_format(0,2)); ?></th>
						<th><?php print(number_format($row['AgostoBitacora']-$row['AgostoEstacionariedad'],2)); ?></th>
						<th><?php print(number_format(0,2)); ?></th>
						<th><?php print(number_format($row['SeptiembreBitacora']-$row['SeptiembreEstacionariedad'],2)); ?></th>
						<th><?php print(number_format(0,2)); ?></th>
						<th><?php print(number_format($row['OctubreBitacora']-$row['OctubreEstacionariedad'],2)); ?></th>
						<th><?php print(number_format(0,2)); ?></th>
						<th><?php print(number_format($row['NoviembreBitacora']-$row['NoviembreEstacionariedad'],2)); ?></th>
						<th><?php print(number_format(0,2)); ?></th>
						<th><?php print(number_format($row['DiciembreBitacora']-$row['DiciembreEstacionariedad'],2)); ?></th>
					</tr>
					<tr>
						<th>Rechazado</th>
						<th><?php print(number_format($row['TotalRechazo'],2)); ?></th>
						<th><?php print(number_format($row['EneroRechazo'],2)); ?></th>
						<th><?php print(number_format($row['FebreroRechazo'],2)); ?></th>
						<th><?php print(number_format($row['EneroRechazo']+$row['FebreroRechazo'],2)); ?></th>
						<th><?php print(number_format($row['MarzoRechazo'],2)); ?></th>
						<th><?php print(number_format($row['EneroRechazo']+$row['FebreroRechazo']+$row['MarzoRechazo'],2)); ?></th>
						<th><?php print(number_format($row['AbrilRechazo'],2)); ?></th>
						<th><?php print(number_format($row['EneroRechazo']+$row['FebreroRechazo']+$row['MarzoRechazo']+$row['AbrilRechazo'],2)); ?></th>
						<th><?php print(number_format($row['MayoRechazo'],2)); ?></th>
						<th><?php print(number_format($row['EneroRechazo']+$row['FebreroRechazo']+$row['MarzoRechazo']+$row['AbrilRechazo']+$row['MayoRechazo'],2)); ?></th>
						<th><?php print(number_format($row['JunioRechazo'],2)); ?></th>
						<th><?php print(number_format($row['EneroRechazo']+$row['FebreroRechazo']+$row['MarzoRechazo']+$row['AbrilRechazo']+$row['MayoRechazo']+$row['JunioRechazo'],2)); ?></th>
						<th><?php print(number_format($row['JulioRechazo'],2)); ?></th>
						<th><?php print(number_format($row['EneroRechazo']+$row['FebreroRechazo']+$row['MarzoRechazo']+$row['AbrilRechazo']+$row['MayoRechazo']+$row['JunioRechazo']+$row['JulioRechazo'],2)); ?></th>
						<th><?php print(number_format($row['AgostoRechazo'],2)); ?></th>
						<th><?php print(number_format($row['EneroRechazo']+$row['FebreroRechazo']+$row['MarzoRechazo']+$row['AbrilRechazo']+$row['MayoRechazo']+$row['JunioRechazo']+$row['JulioRechazo']+$row['AgostoRechazo'],2)); ?></th>
						<th><?php print(number_format($row['SeptiembreRechazo'],2)); ?></th>
						<th><?php print(number_format($row['EneroRechazo']+$row['FebreroRechazo']+$row['MarzoRechazo']+$row['AbrilRechazo']+$row['MayoRechazo']+$row['JunioRechazo']+$row['JulioRechazo']+$row['AgostoRechazo']+$row['SeptiembreRechazo'],2)); ?></th>
						<th><?php print(number_format($row['OctubreRechazo'],2)); ?></th>
						<th><?php print(number_format($row['EneroRechazo']+$row['FebreroRechazo']+$row['MarzoRechazo']+$row['AbrilRechazo']+$row['MayoRechazo']+$row['JunioRechazo']+$row['JulioRechazo']+$row['AgostoRechazo']+$row['SeptiembreRechazo']+$row['OctubreRechazo'],2)); ?></th>
						<th><?php print(number_format($row['NoviembreRechazo'],2)); ?></th>
						<th><?php print(number_format($row['EneroRechazo']+$row['FebreroRechazo']+$row['MarzoRechazo']+$row['AbrilRechazo']+$row['MayoRechazo']+$row['JunioRechazo']+$row['JulioRechazo']+$row['AgostoRechazo']+$row['SeptiembreRechazo']+$row['OctubreRechazo']+$row['NoviembreRechazo'],2)); ?></th>
						<th><?php print(number_format($row['DiciembreRechazo'],2)); ?></th>
					</tr>
					</tr>
					<tr>
						<th>Diferencia</th>
						<th><?php print(number_format(($row['TotalRechazo']+$row['TotalBitacora'])-$row['oferta_actual'],2)); ?></th>
						<th><?php print(number_format(($row['EneroRechazo']+$row['EneroBitacora'])-$row['EneroEstacionariedad'],2)); ?></th>
						<th><?php print(number_format(($row['FebreroRechazo']+$row['FebreroBitacora'])-$row['FebreroEstacionariedad'],2)); ?></th>
						<th><?php print(number_format(0,2)); ?></th>
						<th><?php print(number_format(($row['MarzoRechazo']+$row['MarzoBitacora'])-$row['MarzoEstacionariedad'],2)); ?></th>
						<th><?php print(number_format(0,2)); ?></th>
						<th><?php print(number_format(($row['AbrilRechazo']+$row['AbrilBitacora'])-$row['AbrilEstacionariedad'],2)); ?></th>
						<th><?php print(number_format(0,2)); ?></th>
						<th><?php print(number_format(($row['MayoRechazo']+$row['MayoBitacora'])-$row['MayoEstacionariedad'],2)); ?></th>
						<th><?php print(number_format(0,2)); ?></th>
						<th><?php print(number_format(($row['JunioRechazo']+$row['JunioBitacora'])-$row['JunioEstacionariedad'],2)); ?></th>
						<th><?php print(number_format(0,2)); ?></th>
						<th><?php print(number_format(($row['JulioRechazo']+$row['JulioBitacora'])-$row['JulioEstacionariedad'],2)); ?></th>
						<th><?php print(number_format(0,2)); ?></th>
						<th><?php print(number_format(($row['AgostoRechazo']+$row['AgostoBitacora'])-$row['AgostoEstacionariedad'],2)); ?></th>
						<th><?php print(number_format(0,2)); ?></th>
						<th><?php print(number_format(($row['SeptiembreRechazo']+$row['SeptiembreBitacora'])-$row['SeptiembreEstacionariedad'],2)); ?></th>
						<th><?php print(number_format(0,2)); ?></th>
						<th><?php print(number_format(($row['OctubreRechazo']+$row['OctubreBitacora'])-$row['OctubreEstacionariedad'],2)); ?></th>
						<th><?php print(number_format(0,2)); ?></th>
						<th><?php print(number_format(($row['NoviembreRechazo']+$row['NoviembreBitacora'])-$row['NoviembreEstacionariedad'],2)); ?></th>
						<th><?php print(number_format(0,2)); ?></th>
						<th><?php print(number_format(($row['DiciembreRechazo']+$row['DiciembreBitacora'])-$row['DiciembreEstacionariedad'],2)); ?></th>
					</tr>
					<?php
				}
				return true;
			}
			catch(PDOException $e)
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
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<?php
				return false;
			}
		}
		//Get Asignacion
		public function getAsignacion($idDetalleBitacora,$subproducto,$month,$fecha_realizacion)
		{
			$variableImprimir = '';
			$this->conn->beginTransaction();
			try
			{
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
						$variableImprimir = "No hay agentes preparados";
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
								CONCAT(A.nombre_agente, ' ', A.apellidos_agente) AS nombre_agente,
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
								$agentesOcupadosArray[$i]['id_agente'] = $row3['id_agente'];
								$agentesOcupadosArray[$i]['nombre_agente'] = $row3['nombre_agente'];
								$i++;
							}
						}
						else
						{
							$fecha_completa = $fecha_realizacion.' 00:00:00';
							$query="SELECT 
								CONCAT(A.nombre_agente, ' ', A.apellidos_agente) AS nombre_agente, 
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
								$agentesOcupadosArray[$i]['id_agente'] = $row3['id_agente'];
								$agentesOcupadosArray[$i]['nombre_agente'] = $row3['nombre_agente'];
								$i++;
							}
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
							$variableImprimir = "Agente asignado con el ID ".$agentesDisponiblesArray[0];
						}
						elseif($contarAgentes == 0)
						{
							$variableImprimir = "No hay agentes disponibles";
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
									$variableNombres = "";
									foreach($agentesOferta as $aux)
									{
										$diferencia = $aux['diferencia'];
										$id = $aux['id_agente'];
										$nombre = $aux['nombre_agente'];
										$variableNombres = $variableNombres.", ".$nombre." ";
									}
									$variableImprimir = "Hay más de dos agentes disponibles y con la misma diferencia en su oferta".$variableNombres;
								}
								else
								{
									$variableImprimir = "Agente asignado ".$nombre;
								}
							}
							else
							{
								$variableImprimir = "Agente asignado ".$nombre;
								
							}
						}
					}
				}
				$this->conn->commit();
				return $variableImprimir;				
			}
			catch(PDOException $e)
			{
				echo $e->getMessage();
				$this->conn->rollBack();
				return false;
			}
		}
		//Get Calendario Definitivo
		public function getCalendarioDefinitivo($year)
		{
			try
			{
				$stmt = $this->conn->prepare("SELECT 
					id_calendario as id,
					titulo_calendario as title,
					inicio_calendario as start,
					fin_calendario as end,
					A.color
				FROM T_Calendario_Agentes AS C
				INNER JOIN T_Detalle_Bitacora_Agente AS DBA
				ON DBA.id_detallebitacora = C.id_detallebitacora
				INNER JOIN T_Agentes AS A
				ON A.id_agente = DBA.id_agente
				WHERE YEAR(inicio_calendario) = :year AND YEAR(fin_calendario) = :year AND DBA.id_estatusasignacion = 2");
				$stmt->bindparam(":year",$year);
				$stmt->execute();
				$events = array();
				$e = array();
				while($row=$stmt->fetch(PDO::FETCH_ASSOC))
				{
					$e['id'] = $row['id'];
					$e['title'] = $row['title'];
					$e['start'] = date("Y-m-d H:i:s",strtotime($row['start']));
					$e['end'] = date("Y-m-d H:i:s", strtotime($row['end']));
					$e['color'] = $row['color'];
					array_push($events, $e);
				}
				print(json_encode($events));
				exit;
			}
			catch(PDOException $e)
			{
				echo $e->getMessage();
				$this->conn->rollBack();
				return false;
			}
		}
		//Get Calendario Preliminar
		public function getCalendarioPreliminar($year)
		{
			try
			{
				$stmt = $this->conn->prepare("SELECT 
					id_calendario as id,
					titulo_calendario as title,
					inicio_calendario as start,
					fin_calendario as end,
					A.color
				FROM T_Calendario_Agentes AS C
				INNER JOIN T_Detalle_Bitacora_Agente AS DBA
				ON DBA.id_detallebitacora = C.id_detallebitacora
				INNER JOIN T_Agentes AS A
				ON A.id_agente = DBA.id_agente
				WHERE YEAR(inicio_calendario) = :year AND YEAR(fin_calendario) = :year AND DBA.id_estatusasignacion = 1");
				$stmt->bindparam(":year",$year);
				$stmt->execute();
				$events = array();
				$e = array();
				while($row=$stmt->fetch(PDO::FETCH_ASSOC))
				{
					$e['id'] = $row['id'];
					$e['title'] = $row['title'];
					$e['start'] = date("Y-m-d H:i:s",strtotime($row['start']));
					$e['end'] = date("Y-m-d H:i:s", strtotime($row['end']));
					$e['color'] = $row['color'];
					array_push($events, $e);
				}
				print(json_encode($events));
				exit;
			}
			catch(PDOException $e)
			{
				echo $e->getMessage();
				$this->conn->rollBack();
				return false;
			}
		}
		//Get Colors
		public function getColorsAgente()
		{
			$stmt = $this->conn->prepare("SELECT 
				CONCAT(nombre_agente,' ',apellidos_agente) AS nombre,
				color
			FROM T_Agentes AS A");
			$stmt->execute();
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
			?>
				<div class="btn display-block mrg5B external-event" style="background:<?php print($row['color']); ?>" data-class="bg-white">
					<div class="button-content"><?php print($row['nombre']); ?></div>
				</div>
			<?php
			}
		}
		//Get Información para Cambio Manual del Agente
		public function getInfoCambioManualAgente($id)
		{
			$stmt = $this->conn->prepare("SELECT 
				DB.id_del_evento,
				C.id_zona,
				B.nombre_bitacora,
				S.nombre_subproducto,
				DB.fecha_ejecucion,
				E.estado,
				DB.sede_particular,
				DBA.id_tipoagente,
				TA.tipo_agente,
				DBA.id_agente,
				CONCAT(A.nombre_agente,' ',A.apellidos_agente) AS nombre
			FROM T_Detalle_Bitacora_Agente AS DBA
			INNER JOIN T_Detalle_Bitacora AS DB
			ON DBA.id_detallebitacora = DB.id_detallebitacora
			INNER JOIN T_Bitacora AS B
			ON B.id_bitacora = DB.id_bitacora
			INNER JOIN T_Clientes AS C
			ON C.id_cliente = B.id_cliente
			INNER JOIN T_Subproductos AS S
			ON S.id_subproducto = DB.id_subproducto
			INNER JOIN T_Estado AS E
			ON E.id_estado = DB.id_estado
			INNER JOIN T_TipoAgente AS TA
			ON TA.id_tipoagente = DBA.id_tipoagente
			INNER JOIN T_Agentes AS A
			ON A.id_agente = DBA.id_agente
			WHERE DBA.id_detallebitacora_agente = :id");
			$stmt->bindparam(":id",$id);
			$stmt->execute();
			
			$stmt2 = $this->conn->prepare("SELECT 
				TA.id_tipoagente,
				TA.tipo_agente
			FROM T_TipoAgente AS TA");
			$stmt2->execute();
			
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
			?>
				<tr>
					<td><?php print($row['id_del_evento']); ?></td>
					<td><?php print($row['id_zona']); ?></td>
					<td><?php print($row['nombre_bitacora']); ?></td>
					<td><?php print($row['nombre_subproducto']); ?></td>
					<td><?php print($row['fecha_ejecucion']); ?></td>
					<td><?php print($row['estado']); ?></td>
					<td><?php print($row['sede_particular']); ?></td>
					<td>
						<select id="tipo_agente" name="tipo_agente" class="form-control" onchange="getAgente(this);">
							<option value="<?php print($row['id_tipoagente']); ?>"><?php print($row['tipo_agente']); ?></option>
							<?php
							while($row2=$stmt2->fetch(PDO::FETCH_ASSOC))
							{
							?>
							<option value="<?php print($row2['id_tipoagente']); ?>"><?php print($row2['tipo_agente']); ?></option>
							<?php
							}
							?>
						</select>
					</td>
					<td>
						<select id="agente" name="agente" class="form-control">
							<option value="<?php print($row['id_agente']); ?>"><?php print($row['nombre']); ?></option>
						</select>
					</td>
				</tr>
			<?php
			}
		}
		//Get Agente po el Tipo
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
		
		//View Requerimientos
		public function viewRequerimientosAgente($agente,$year)
		{
			$query = "SELECT
						DB.id_detallebitacora,
						DB.id_subproducto,
						DB.id_del_evento,
						C.id_zona,
						C.nombre_cliente,
						S.nombre_subproducto,
						DB.fecha_ejecucion,
						MONTH(DB.fecha_ejecucion) AS mes,
						DBA.id_detallebitacora_agente,
						DBA.fecha_creacion,
						EAA.estatus_asignacion,
						DBA.id_agente,
						DBA.id_estatusasignacion,
						DBA.fecha_confirmacion,
						DBA.id_tipoagente
					FROM T_Detalle_Bitacora AS DB
					INNER JOIN T_Bitacora AS B
					ON DB.id_bitacora = B.id_bitacora
					INNER JOIN T_Detalle_Bitacora_Agente AS DBA 
					ON DB.id_detallebitacora = DBA.id_detallebitacora
					INNER JOIN T_Agentes AS A 
					ON A.id_agente = DBA.id_agente
					INNER JOIN T_Clientes AS C
					ON B.id_cliente = C.id_cliente
					INNER JOIN T_Subproductos AS S
					ON S.id_subproducto = DB.id_subproducto
					INNER JOIN T_EstatusAsignacionAgentes AS EAA
					ON DBA.id_estatusasignacion = EAA.id_estatusasignacion
					WHERE DBA.id_agente = :agente AND YEAR(DB.fecha_ejecucion) = :year
					GROUP BY DB.id_detallebitacora
					ORDER BY DB.id_detallebitacora, C.id_zona";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":agente",$agente);
			$stmt->bindparam(":year",$year);
			$stmt->execute();
			$count1 = $stmt->rowCount();
			if($count1 > 0)
			{
				$x = 1;
				while($row=$stmt->fetch(PDO::FETCH_ASSOC))
				{
					if($row['id_estatusasignacion'] == 1)
					{
					?>
						<tr>
							<td style="text-align: center"><a id="trigger<?php print($row['id_detallebitacora_agente']);?>" onclick="getConfirmacion(this);"><i class="glyph-icon icon-check"></i></a></td>
							<td><?php print($row['id_del_evento']); ?></td>
							<td>Unidad <?php print($row['id_zona']); ?></td>
							<td><?php print($row['nombre_cliente']); ?></td>
							<td><?php print($row['nombre_subproducto']); ?></td>
							<td><?php print($row['fecha_ejecucion']); ?></td>
							<td><?php print($row['fecha_creacion']); ?></td>
							<td><?php print($row['fecha_confirmacion']); ?></td>
							<td id="estatus<?php print($x);?>"><?php print($row['estatus_asignacion']); ?></td>
							<td></td>
							<td></td>
						</tr>
					<?php
					}
					elseif($row['id_estatusasignacion'] == 2)
					{
					?>
						<tr>
							<td></td>
							<td><?php print($row['id_del_evento']); ?></td>
							<td>Unidad <?php print($row['id_zona']); ?></td>
							<td><?php print($row['nombre_cliente']); ?></td>
							<td><?php print($row['nombre_subproducto']); ?></td>
							<td><?php print($row['fecha_ejecucion']); ?></td>
							<td><?php print($row['fecha_creacion']); ?></td>
							<td><?php print($row['fecha_confirmacion']); ?></td>
							<td id="estatus<?php print($x);?>"><?php print($row['estatus_asignacion']); ?></td>
							<td style="text-align: center"><a href="MaterialTrabajo-Agente.php?idAgente=<?php print($row['id_agente']); ?>&idTipo=<?php print($row['id_tipoagente']); ?>&idEvento=<?php print($row['id_detallebitacora']); ?>"><i class="glyph-icon icon-wrench"></i></a></td>
							<td style="text-align: center"><a href="#"><i class="glyph-icon icon-download"></i></a></td>
						</tr>
					<?php
					}
					else
					{
					?>
						<tr>
							<td></td>
							<td><?php print($row['id_del_evento']); ?></td>
							<td>Unidad <?php print($row['id_zona']); ?></td>
							<td><?php print($row['nombre_cliente']); ?></td>
							<td><?php print($row['nombre_subproducto']); ?></td>
							<td><?php print($row['fecha_ejecucion']); ?></td>
							<td><?php print($row['fecha_creacion']); ?></td>
							<td><?php print($row['fecha_confirmacion']); ?></td>
							<td id="estatus<?php print($x);?>"><?php print($row['estatus_asignacion']); ?></td>
							<td></td>
							<td></td>
						</tr>
					<?php
					}
					$x++;
				}
			}
			else
			{
			?>
				<tr>
					<td>No hay eventos disponibles</td>
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
		public function viewRequerimientos($semana,$year)
		{
			$query = "SELECT
						DB.id_detallebitacora,
						DB.id_subproducto,
						DB.id_del_evento,
						C.id_zona,
						S.nombre_subproducto,
						DB.fecha_ejecucion,
						MONTH(DB.fecha_ejecucion) AS mes,
						CONCAT(A.nombre_agente,' ',A.apellidos_agente) AS agente,
						DBA.id_detallebitacora_agente,
						DBA.fecha_creacion,
						EAA.estatus_asignacion,
						DBA.id_agente,
						DBA.id_estatusasignacion,
						DBA.fecha_confirmacion
					FROM T_Detalle_Bitacora AS DB
					INNER JOIN T_Bitacora AS B
					ON DB.id_bitacora = B.id_bitacora
					INNER JOIN T_Detalle_Bitacora_Agente AS DBA 
					ON DB.id_detallebitacora = DBA.id_detallebitacora
					INNER JOIN T_Agentes AS A 
					ON A.id_agente = DBA.id_agente
					INNER JOIN T_Clientes AS C
					ON B.id_cliente = C.id_cliente
					INNER JOIN T_Subproductos AS S
					ON S.id_subproducto = DB.id_subproducto
					INNER JOIN T_EstatusAsignacionAgentes AS EAA
					ON DBA.id_estatusasignacion = EAA.id_estatusasignacion
					WHERE WEEK(DB.fecha_ejecucion)+1 = :semana AND YEAR(DB.fecha_ejecucion) = :year
					GROUP BY DB.id_detallebitacora
					ORDER BY DB.id_detallebitacora, C.id_zona";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":semana",$semana);
			$stmt->bindparam(":year",$year);
			$stmt->execute();
			$count1 = $stmt->rowCount();
			if($count1 > 0)
			{
				$x = 1;
				while($row=$stmt->fetch(PDO::FETCH_ASSOC))
				{
					if($row['id_estatusasignacion'] == 1)
					{
					?>
						<tr>
							<td style="text-align: center"><a id="trigger<?php print($row['id_detallebitacora_agente']);?>" onclick="getConfirmacion(this);"><i class="glyph-icon icon-check"></i></a></td>
							<td><?php print($row['id_del_evento']); ?></td>
							<td>Unidad <?php print($row['id_zona']); ?></td>
							<td><?php print($row['nombre_subproducto']); ?></td>
							<td><?php print($row['fecha_ejecucion']); ?></td>
							<td><?php print($row['agente']); ?></td>
							<td><?php print($row['fecha_creacion']); ?></td>
							<td><?php print($row['fecha_confirmacion']); ?></td>
							<td id="estatus<?php print($x);?>"><?php print($row['estatus_asignacion']); ?></td>
							<td style="text-align: center"><a id="" href="doCambioManual.php?id=<?php print($row['id_detallebitacora_agente']); ?>"><i class="glyph-icon icon-gear"></i></a></td>
						</tr>
					<?php
					}
					elseif($row['id_estatusasignacion'] == 2)
					{
					?>
						<tr>
							<td></td>
							<td><?php print($row['id_del_evento']); ?></td>
							<td>Unidad <?php print($row['id_zona']); ?></td>
							<td><?php print($row['nombre_subproducto']); ?></td>
							<td><?php print($row['fecha_ejecucion']); ?></td>
							<td><?php print($row['agente']); ?></td>
							<td><?php print($row['fecha_creacion']); ?></td>
							<td><?php print($row['fecha_confirmacion']); ?></td>
							<td id="estatus<?php print($x);?>"><?php print($row['estatus_asignacion']); ?></td>
							<td style="text-align: center"><a id="" href="doCambioManual.php?id=<?php print($row['id_detallebitacora_agente']); ?>"><i class="glyph-icon icon-gear"></i></a></td>
						</tr>
					<?php
					}
					elseif($row['id_agente'] == 0)
					{
					?>
						<tr>
							<td></td>
							<td><?php print($row['id_del_evento']); ?></td>
							<td>Unidad <?php print($row['id_zona']); ?></td>
							<td><?php print($row['nombre_subproducto']); ?></td>
							<td><?php print($row['fecha_ejecucion']); ?></td>
							<td><?php print($row['agente']); ?></td>
							<td><?php print($row['fecha_creacion']); ?></td>
							<td><?php print($row['fecha_confirmacion']); ?></td>
							<td id="estatus<?php print($x);?>"><?php print($row['estatus_asignacion']); ?></td>
							<td style="text-align: center"><a id="" href="doCambioManual.php?id=<?php print($row['id_detallebitacora_agente']); ?>"><i class="glyph-icon icon-gear"></i></a></td>
						</tr>
					<?php
					}
					else
					{
					?>
						<tr>
							<td></td>
							<td><?php print($row['id_del_evento']); ?></td>
							<td>Unidad <?php print($row['id_zona']); ?></td>
							<td><?php print($row['nombre_subproducto']); ?></td>
							<td><?php print($row['fecha_ejecucion']); ?></td>
							<td><?php print($row['agente']); ?></td>
							<td><?php print($row['fecha_creacion']); ?></td>
							<td><?php print($row['fecha_confirmacion']); ?></td>
							<td id="estatus<?php print($x);?>"><?php print($row['estatus_asignacion']); ?></td>
							<td style="text-align: center"><a id="" href="doCambioManual.php?id=<?php print($row['id_detallebitacora_agente']); ?>"><i class="glyph-icon icon-gear"></i></a></td>
						</tr>
					<?php
					}
					$x++;
				}
			}
			else
			{
			?>
				<tr>
					<td>No hay agentes disponibles</td>
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
		//View Select Agentes
		public function viewAgentes()
		{
			$query = "SELECT
						A.id_agente,
						A.nombre_agente,
						A.apellidos_agente,
						A.id_circulo,
						A.correo_oexl,
						A.antiguedad,
						GROUP_CONCAT(TA.tipo_agente) AS tipo_agente
					FROM T_Agentes AS A
					INNER JOIN T_TipoAgente_Agente AS TAA
					ON TAA.id_agente = A.id_agente
					INNER JOIN T_TipoAgente AS TA
					ON TA.id_tipoagente = TAA.id_tipoagente
					GROUP BY id_agente
					ORDER BY id_agente";
			$stmt = $this->conn->prepare($query);
			$stmt->execute();
			$count1 = $stmt->rowCount();
			if($count1 > 0)
			{
				while($row=$stmt->fetch(PDO::FETCH_ASSOC))
				{
				?>
					<tr>
						<td><?php print($row['nombre_agente'].' '.$row['apellidos_agente']); ?></td>
						<td style="text-align: center"><a href="updatePadronAgentesForm.php?agente=<?php print($row['id_agente']); ?>"><i class="glyph-icon icon-pencil"></i></a></td>
						<td>Círculo <?php print($row['id_circulo']); ?></td>
						<td><?php print($row['tipo_agente']); ?></td>
						<td><?php print($row['correo_oexl']); ?></td>
						<td><?php print($row['antiguedad']); ?></td>
						<td style="text-align: center"><a href="viewDatosPersonalesARA.php?agente=<?php print($row['id_agente']); ?>"><i class="glyph-icon icon-user"></i></a></td>
						<td style="text-align: center"><a href="viewFormacionAcademicaARA.php?agente=<?php print($row['id_agente']); ?>"><i class="glyph-icon icon-book"></i></a></td>
						<td style="text-align: center"><a href="viewDatosRolARA.php?agente=<?php print($row['id_agente']); ?>"><i class="glyph-icon icon-file"></i></a></td>
						<!--<td style="text-align: center"><a href="viewActualizacionARA.php?agente=<?php print($row['id_agente']); ?>"><i class="glyph-icon icon-file"></i></a></td>-->
						<td style="text-align: center"><a href="viewInteresesARA.php?agente=<?php print($row['id_agente']); ?>"><i class="glyph-icon icon-bookmark"></i></a></td>
						<td style="text-align: center"><a href="viewHerramientasARA.php?agente=<?php print($row['id_agente']); ?>"><i class="glyph-icon icon-wrench"></i></a></td>
						<!--<td style="text-align: center"><a href="viewCompetenciasARA.php?agente=<?php print($row['id_agente']); ?>"><i class="glyph-icon icon-file"></i></a></td>-->
						<!--<td style="text-align: center"><a href="viewExpedienteARA.php?agente=<?php print($row['id_agente']); ?>"><i class="glyph-icon icon-file"></i></a></td>-->
						<td><img width="28" src="../../assets/image-resources/person_icon.png" alt="Profile image"></td>
					</tr>
				<?php
				}
			}
			else
			{
			?>
				<tr>
					<td>No hay agentes disponibles</td>
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
		//View Select DatosPersonales
		public function viewDatosPersonales($agente)
		{
			$query = "SELECT
						A.id_agente,
						A.nombre_agente,
						A.apellidos_agente,
						A.sexo,
						A.fecha_nacimiento,
						FLOOR(DATEDIFF(CURDATE(),STR_TO_DATE(A.fecha_nacimiento,'%Y-%m-%d')) / 365) AS edad,
						A.calle,
						A.no_ext,
						A.no_int,
						C.asentamiento,
						C.cp,
						C.municipio,
						C.ciudad,
						C.estado,
						A.nss,
						A.curp,
						A.rfc,
						A.clabe,
						B.banco,
						A.tel_personal,
						A.celular_personal,
						A.otro_tel_personal,
						A.correo_personal,
						A.estado_civil,
						A.no_hijos,
						A.contacto_emergencia,
						A.tel_contacto,
						A.celular_contacto,
						A.tipo_sangre
					FROM T_Agentes AS A
					INNER JOIN codigo AS C
					ON C.id = A.id
					INNER JOIN T_Bancos AS B
					ON B.id_banco = A.id_banco
					WHERE A.id_agente = :agente
					ORDER BY id_agente";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":agente",$agente);
			$stmt->execute();
			$count1 = $stmt->rowCount();
			if($count1 > 0)
			{
				while($row=$stmt->fetch(PDO::FETCH_ASSOC))
				{
				?>
					<tr>
						<td><?php print($row['nombre_agente'].' '.$row['apellidos_agente']); ?></td>
						<td><?php print($row['sexo']); ?></td>
						<td><?php print($row['fecha_nacimiento']); ?></td>
						<td><?php print($row['edad']); ?></td>
						<td><?php print($row['calle']); ?></td>
						<td><?php print($row['no_ext']); ?></td>
						<td><?php print($row['no_int']); ?></td>
						<td><?php print($row['asentamiento']); ?></td>
						<td><?php print($row['cp']); ?></td>
						<td><?php print($row['municipio']); ?></td>
						<td><?php print($row['ciudad']); ?></td>
						<td><?php print($row['estado']); ?></td>
						<td><?php print($row['nss']); ?></td>
						<td><?php print($row['curp']); ?></td>
						<td><?php print($row['rfc']); ?></td>
						<td><?php print($row['clabe']); ?></td>
						<td><?php print($row['banco']); ?></td>
						<td><?php print($row['tel_personal']); ?></td>
						<td><?php print($row['celular_personal']); ?></td>
						<td><?php print($row['otro_tel_personal']); ?></td>
						<td><?php print($row['correo_personal']); ?></td>
						<td><?php print($row['estado_civil']); ?></td>
						<td><?php print($row['no_hijos']); ?></td>
						<td><?php print($row['contacto_emergencia']); ?></td>
						<td><?php print($row['tel_contacto']); ?></td>
						<td><?php print($row['celular_contacto']); ?></td>
						<td><?php print($row['tipo_sangre']); ?></td>
					</tr>
				<?php
				}
			}
			else
			{
			?>
				<tr>
					<td>No hay agentes disponibles</td>
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
		//View Select FormacionAcademica
		public function viewFormacionAcademica($agente)
		{
			$query = "SELECT
						A.id_agente,
						A.nombre_agente,
						A.apellidos_agente,
						A.id_carrera,
						C.carrera,
						A.grado,
						A.documento_lic,
						A.posgrado,
						A.fecha_termino_posgrado,
						A.documento_posgrado,
						A.diplomado_especialidad,
						A.fecha_termino_diplomado,
						A.documento_diplomado,
						A.certificacion,
						A.fecha_termino_certificacion,
						A.documento_certificado
					FROM T_Agentes AS A
					INNER JOIN T_Carrera AS C
					ON C.id_carrera = A.id_carrera
					WHERE A.id_agente = :agente
					ORDER BY id_agente";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":agente",$agente);
			$stmt->execute();
			$count1 = $stmt->rowCount();
			if($count1 > 0)
			{
				while($row=$stmt->fetch(PDO::FETCH_ASSOC))
				{
				?>
					<tr>
						<td><?php print($row['nombre_agente'].' '.$row['apellidos_agente']); ?></td>
						<td><?php print($row['carrera']); ?></td>
						<td><?php print($row['grado']); ?></td>
						<td><?php print($row['documento_lic']); ?></td>
						<td><?php print($row['posgrado']); ?></td>
						<td><?php print($row['fecha_termino_posgrado']); ?></td>
						<td><?php print($row['documento_posgrado']); ?></td>
						<td><?php print($row['diplomado_especialidad']); ?></td>
						<td><?php print($row['fecha_termino_diplomado']); ?></td>
						<td><?php print($row['documento_diplomado']); ?></td>
						<td><?php print($row['certificacion']); ?></td>
						<td><?php print($row['fecha_termino_certificacion']); ?></td>
						<td><?php print($row['documento_cer']); ?></td>
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
				</tr>
			<?php
			}
		}
		//View Select DatosRol
		public function viewDatosRol($agente)
		{
			$query = "SELECT
						A.id_agente,
						A.nombre_agente,
						A.apellidos_agente,
						GROUP_CONCAT(TA.tipo_agente) AS tipo_agente,
						A.fecha_ingreso,
						A.registro_dc5,
						A.residencia,
						A.tel_empresa,
						A.tel_extension,
						A.correo_empresarial
					FROM T_Agentes AS A
					INNER JOIN T_TipoAgente_Agente AS TAA
					ON TAA.id_agente = A.id_agente
					INNER JOIN T_TipoAgente AS TA
					ON TA.id_tipoagente = TAA.id_tipoagente
					WHERE A.id_agente = :agente
					GROUP BY id_agente
					ORDER BY id_agente";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":agente",$agente);
			$stmt->execute();
			$count1 = $stmt->rowCount();
			if($count1 > 0)
			{
				while($row=$stmt->fetch(PDO::FETCH_ASSOC))
				{
				?>
					<tr>
						<td><?php print($row['nombre_agente'].' '.$row['apellidos_agente']); ?></td>
						<td><?php print($row['tipo_agente']); ?></td>
						<td><?php print($row['fecha_ingreso']); ?></td>
						<td><?php print($row['registro_dc5']); ?></td>
						<td><?php print($row['residencia']); ?></td>
						<td><?php print($row['tel_empresa']); ?></td>
						<td><?php print($row['tel_extension']); ?></td>
						<td><?php print($row['correo_empresarial']); ?></td>
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
				</tr>
			<?php
			}
		}
		//View Select AreasInteres
		public function viewAreasInteres($agente)
		{
			$query = "SELECT
						A.id_agente,
						A.nombre_agente,
						A.apellidos_agente,
						GROUP_CONCAT(TA.area) AS areas
					FROM T_Agentes AS A
					INNER JOIN T_Areas_Agente AS TAA
					ON TAA.id_agente = A.id_agente
					INNER JOIN T_Areas AS TA
					ON TA.id_area = TAA.id_area
					WHERE A.id_agente = :agente
					GROUP BY id_agente
					ORDER BY id_agente";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":agente",$agente);
			$stmt->execute();
			$count1 = $stmt->rowCount();
			if($count1 > 0)
			{
				while($row=$stmt->fetch(PDO::FETCH_ASSOC))
				{
				?>
					<tr>
						<td><?php print($row['nombre_agente'].' '.$row['apellidos_agente']); ?></td>
						<td><?php print($row['areas']); ?></td>
					</tr>
				<?php
				}
			}
			else
			{
			?>
				<tr>
					<td>No hay agentes disponibles</td>
					<td></td>
				</tr>
			<?php
			}
		}
		//View Select AreasHerramientas
		public function viewHerramientas($agente)
		{
			$query = "SELECT
						A.id_agente,
						A.nombre_agente,
						A.apellidos_agente,
						H.herramienta
					FROM T_Agentes AS A
					INNER JOIN T_Herramientas_Agente AS HA
					ON HA.id_agente = HA.id_agente
					INNER JOIN T_HerramientasARA AS H
					ON HA.id_herramienta = H.id_herramienta
					WHERE A.id_agente = :agente
					GROUP BY herramienta
					ORDER BY id_agente";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":agente",$agente);
			$stmt->execute();
			$count1 = $stmt->rowCount();
			if($count1 > 0)
			{
				$i = 1;
				while($row=$stmt->fetch(PDO::FETCH_ASSOC))
				{
					if($i == 1)
					{
					?>
						<tr>
							<td><?php print($row['nombre_agente'].' '.$row['apellidos_agente']); ?></td>
							<td><?php print($row['herramienta']); ?></td>
						</tr>
					<?php
					}
					else
					{
					?>
						<tr>
							<td style="display:none;"><?php print($row['nombre_agente'].' '.$row['apellidos_agente']); ?></td>
							<td></td>
							<td><?php print($row['herramienta']); ?></td>
						</tr>
					<?php	
					}
					$i++;
				}
			}
			else
			{
			?>
				<tr>
					<td>No hay información disponible</td>
					<td></td>
				</tr>
			<?php
			}
		}
		//ShowAgente
		public function showAgente($agente)
		{
			$query = "SELECT
						A.id_agente,
						A.nombre_agente,
						A.apellidos_agente,
						A.id_circulo,
						A.correo_oexl,
						A.antiguedad,
						GROUP_CONCAT(TA.tipo_agente) AS tipo_agente,
						A.sexo,
						A.fecha_nacimiento,
						FLOOR(DATEDIFF(CURDATE(),STR_TO_DATE(A.fecha_nacimiento,'%Y-%m-%d')) / 365) AS edad,
						A.calle,
						A.no_ext,
						A.no_int,
						A.id,
						C.asentamiento,
						C.cp,
						C.municipio,
						C.ciudad,
						C.estado,
						A.nss,
						A.curp,
						A.rfc,
						A.clabe,
						A.id_banco,
						B.banco,
						A.tel_personal,
						A.celular_personal,
						A.otro_tel_personal,
						A.correo_personal,
						A.estado_civil,
						A.no_hijos,
						A.contacto_emergencia,
						A.tel_contacto,
						A.celular_contacto,
						A.tipo_sangre,
						A.id_carrera,
						CR.carrera,
						A.grado,
						A.documento_lic,
						A.posgrado,
						A.fecha_termino_posgrado,
						A.documento_posgrado,
						A.diplomado_especialidad,
						A.fecha_termino_diplomado,
						A.documento_diplomado,
						A.certificacion,
						A.fecha_termino_certificacion,
						A.documento_certificado,
						A.fecha_ingreso,
						A.registro_dc5,
						A.residencia,
						A.tel_empresa,
						A.tel_extension,
						A.correo_empresarial
					FROM T_Agentes AS A
					INNER JOIN T_TipoAgente_Agente AS TAA
					ON TAA.id_agente = A.id_agente
					INNER JOIN T_TipoAgente AS TA
					ON TA.id_tipoagente = TAA.id_tipoagente
					INNER JOIN codigo AS C
					ON C.id = A.id
					INNER JOIN T_Bancos AS B
					ON B.id_banco = A.id_banco
					INNER JOIN T_Carrera AS CR
					ON CR.id_carrera = A.id_carrera
					WHERE A.id_agente = :agente
					GROUP BY id_agente
					ORDER BY id_agente";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":agente",$agente);
			$stmt->execute();
			$count1 = $stmt->rowCount();
			if($count1 > 0)
			{
				$row=$stmt->fetch(PDO::FETCH_ASSOC);
				return $row;
			}
			else
			{
				return 0;
			}
		}
		//ShowAgente Herramientas
		public function showAgenteHerramientas($agente)
		{
			$query = "SELECT
						H.id_herramienta
					FROM T_Herramientas_Agente AS H
					WHERE H.id_agente = :agente";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":agente",$agente);
			$stmt->execute();
			$count1 = $stmt->rowCount();
			if($count1 > 0)
			{
				$row=$stmt->fetch(PDO::FETCH_ASSOC);
				return $row;
			}
			else
			{
				return 0;
			}
		}
		//Circulos
		public function selectCirculos()
		{
			$stmt = $this->conn->prepare("SELECT id_circulo, circulo FROM T_Circulos");
			$stmt->execute();
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
			?>
				<option value="<?php echo $row['id_circulo']; ?>"><?php echo $row['circulo']; ?></option>
			<?php
			}
		}
		//Tipo Agente
		public function selectTipoAgente()
		{
			$stmt = $this->conn->prepare("SELECT id_tipoagente, tipo_agente	FROM T_TipoAgente");
			$stmt->execute();
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
			?>
				<option value="<?php echo $row['id_tipoagente']; ?>"><?php echo $row['tipo_agente']; ?></option>
			<?php
			}
		}
		//Select Dirección
		public function selectPais()
		{
			$stmt = $this->conn->prepare("SELECT * FROM T_Pais");
			$stmt->execute();
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
			?>
			<option value="<?php echo $row['id_pais']; ?>"><?php echo $row['pais']; ?></option>
			<?php
			} 
		}
		public function selectEstado()
		{
			$stmt = $this->conn->prepare("SELECT idEstado,estado FROM codigo GROUP BY idEstado, estado");
			$stmt->execute();
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
			?>
			<option value="<?php echo $row['idEstado']; ?>"><?php echo $row['estado']; ?></option>
			<?php
			} 
		}
		public function selectMunicipio($estado)
		{
			$stmt = $this->conn->prepare("SELECT idMunicipio,municipio FROM codigo WHERE  idEstado = :estado GROUP BY idMunicipio, municipio");
			$stmt->bindparam(":estado",$estado);
			$stmt->execute();
			?>
			<option value="" selected>Selecciona la Delegación o Municipio de la ubicación</option>
			<?php
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
			?>
			<option value="<?php echo $row['idMunicipio']; ?>"><?php echo $row['municipio']; ?></option>
			<?php
			} 
		}
		public function selectCP($delmun)
		{
			$stmt = $this->conn->prepare("SELECT cp FROM codigo WHERE  idMunicipio = :delmun GROUP BY cp");
			$stmt->bindparam(":delmun",$delmun);
			$stmt->execute();
			?>
			<option value="" selected>Selecciona el Código Postal de la ubicación</option>
			<?php
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
			?>
			<option value="<?php echo $row['cp']; ?>"><?php echo $row['cp']; ?></option>
			<?php
			} 
		}
		public function selectColonia($cp)
		{
			$stmt = $this->conn->prepare("SELECT id, asentamiento FROM codigo WHERE  cp = :cp GROUP BY asentamiento");
			$stmt->bindparam(":cp",$cp);
			$stmt->execute();
			?>
			<option value="" selected>Selecciona la colonia de la ubicación</option>
			<?php
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
			?>
			<option value="<?php echo $row['id']; ?>"><?php echo $row['asentamiento']; ?></option>
			<?php
			} 
		}
		//Carrera
		public function selectCarrera()
		{
			$stmt = $this->conn->prepare("SELECT id_carrera, carrera FROM T_Carrera");
			$stmt->execute();
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
			?>
			<option value="<?php echo $row['id_carrera']; ?>"><?php echo $row['carrera']; ?></option>
			<?php
			} 
		}
		//Bancos
		public function selectBanco()
		{
			$stmt = $this->conn->prepare("SELECT id_banco, banco FROM T_Bancos");
			$stmt->execute();
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
			?>
			<option value="<?php echo $row['id_banco']; ?>"><?php echo $row['banco']; ?></option>
			<?php
			} 
		}
		
		
		//INSERTS//
		//Insertar Agente
		public function InsertAgente($nombre,$apellidos,$circulo,$correo,$antiguedad,$sexo,$fecha_nacimiento,$nss,$curp,$rfc,$clabe,$banco,$estado_civil,$tipo_sangre,$no_hijos,$calle,$no_ext,
			$no_int,$colonia,$tel,$celular,$otro_tel,$correo_personal,$residencia,$contacto_emergencia,$tel_contacto,$celular_contacto,$carrera,$grado,$documento_lic,$posgrado,$fecha_termino_posgrado,
			$documento_posgrado,$diplomado_especialidad,$documento_diplomado,$fecha_termino_diplomado,$certificacion,$documento_cer,$fecha_termino_certificacion,$fecha_ingreso,$registro_dc5,
			$tel_empresa,$tel_extension,$mail_empresarial)
		{
			$this->conn->beginTransaction();
			try
			{
				$query="INSERT INTO T_Agentes (nombre_agente,apellidos_agente,id_estatusagente,oferta_anterior,oferta_actual,oferta_proxima,id_circulo,correo_oexl,antiguedad,sexo,fecha_nacimiento,nss,curp,rfc,clabe,id_banco,estado_civil,tipo_sangre,no_hijos,
					calle,no_ext,no_int,id,tel_personal,celular_personal,otro_tel_personal,correo_personal,residencia,contacto_emergencia,tel_contacto,celular_contacto,id_carrera,grado,documento_lic,posgrado,
					fecha_termino_posgrado,documento_posgrado,diplomado_especialidad,documento_diplomado,fecha_termino_diplomado,certificacion,documento_certificado,fecha_termino_certificacion,fecha_ingreso,registro_dc5,
					tel_empresa,tel_extension,correo_empresarial) 
					VALUES(:nombre,:apellidos,6,0,0,0,:circulo,:correo,:antiguedad,:sexo,:fecha_nacimiento,:nss,:curp,:rfc,:clabe,:banco,:estado_civil,:tipo_sangre,:no_hijos,:calle,:no_ext,
					:no_int,:colonia,:tel,:celular,:otro_tel,:correo_personal,:residencia,:contacto_emergencia,:tel_contacto,:celular_contacto,:carrera,:grado,:documento_lic,:posgrado,:fecha_termino_posgrado,
					:documento_posgrado,:diplomado_especialidad,:documento_diplomado,:fecha_termino_diplomado,:certificacion,:documento_cer,:fecha_termino_certificacion,:fecha_ingreso,:registro_dc5,
					:tel_empresa,:tel_extension,:mail_empresarial)";
				$stmt = $this->conn->prepare($query);
				$stmt->bindparam(":nombre",$nombre);
				$stmt->bindparam(":apellidos",$apellidos);
				$stmt->bindparam(":circulo",$circulo);
				$stmt->bindparam(":correo",$correo);
				$stmt->bindparam(":antiguedad",$antiguedad);
				$stmt->bindparam(":sexo",$sexo);
				$stmt->bindparam(":fecha_nacimiento",$fecha_nacimiento);
				$stmt->bindparam(":nss",$nss);
				$stmt->bindparam(":curp",$curp);
				$stmt->bindparam(":rfc",$rfc);
				$stmt->bindparam(":clabe",$clabe);
				$stmt->bindparam(":banco",$banco);
				$stmt->bindparam(":estado_civil",$estado_civil);
				$stmt->bindparam(":tipo_sangre",$tipo_sangre);
				$stmt->bindparam(":no_hijos",$no_hijos);
				$stmt->bindparam(":calle",$calle);
				$stmt->bindparam(":no_ext",$no_ext);
				$stmt->bindparam(":no_int",$no_int);
				$stmt->bindparam(":colonia",$colonia);
				$stmt->bindparam(":tel",$tel);
				$stmt->bindparam(":celular",$celular);
				$stmt->bindparam(":otro_tel",$otro_tel);
				$stmt->bindparam(":correo_personal",$correo_personal);
				$stmt->bindparam(":residencia",$residencia);
				$stmt->bindparam(":contacto_emergencia",$contacto_emergencia);
				$stmt->bindparam(":tel_contacto",$tel_contacto);
				$stmt->bindparam(":celular_contacto",$celular_contacto);
				$stmt->bindparam(":carrera",$carrera);
				$stmt->bindparam(":grado",$grado);
				$stmt->bindparam(":documento_lic",$documento_lic);
				$stmt->bindparam(":posgrado",$posgrado);
				$stmt->bindparam(":fecha_termino_posgrado",$fecha_termino_posgrado);
				$stmt->bindparam(":documento_posgrado",$documento_posgrado);
				$stmt->bindparam(":diplomado_especialidad",$diplomado_especialidad);
				$stmt->bindparam(":documento_diplomado",$documento_diplomado);
				$stmt->bindparam(":fecha_termino_diplomado",$fecha_termino_diplomado);
				$stmt->bindparam(":certificacion",$certificacion);
				$stmt->bindparam(":documento_cer",$documento_cer);
				$stmt->bindparam(":fecha_termino_certificacion",$fecha_termino_certificacion);
				$stmt->bindparam(":fecha_ingreso",$fecha_ingreso);
				$stmt->bindparam(":registro_dc5",$registro_dc5);
				$stmt->bindparam(":tel_empresa",$tel_empresa);
				$stmt->bindparam(":tel_extension",$tel_extension);
				$stmt->bindparam(":mail_empresarial",$mail_empresarial);
				$stmt->execute();
				$last_id = $this->conn->lastInsertId();
				$this->conn->commit();
				return $last_id;
			}
			catch(PDOException $e)
			{
				echo $e->getMessage();
				$this->conn->rollBack();
				return 0;
			}
		}	
		//Insertar Agente-Tipo Agente
		public function InsertRolAgente($rol,$idAgente)
		{
			$this->conn->beginTransaction();
			try
			{
				foreach($rol as $aux)
				{
					$query="INSERT INTO T_TipoAgente_Agente(id_tipoagente,id_agente) 
					VALUES(:aux,:idAgente)";
					$stmt = $this->conn->prepare($query);
					$stmt->bindparam(":aux",$aux);
					$stmt->bindparam(":idAgente",$idAgente);
					$stmt->execute();
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
		//Insertar Agente-Areas
		public function InsertAreasAgente($areas,$idAgente)
		{
			$this->conn->beginTransaction();
			try
			{
				foreach($areas as $aux)
				{
					$query="INSERT INTO T_Areas_Agente(id_area,id_agente) 
					VALUES(:aux,:idAgente)";
					$stmt = $this->conn->prepare($query);
					$stmt->bindparam(":aux",$aux);
					$stmt->bindparam(":idAgente",$idAgente);
					$stmt->execute();
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
		//Insertar Agente-Herramientas
		public function InsertHerramientasAgente($herramientas,$idAgente)
		{
			$this->conn->beginTransaction();
			try
			{
				foreach($herramientas as $aux)
				{
					$query="INSERT INTO T_Herramientas_Agente(id_herramienta,id_agente) 
					VALUES(:aux,:idAgente)";
					$stmt = $this->conn->prepare($query);
					$stmt->bindparam(":aux",$aux);
					$stmt->bindparam(":idAgente",$idAgente);
					$stmt->execute();
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
		//UPDATES//
		//Update Agente
		public function UpdateAgente($agente,$correo,$antiguedad,$sexo,$fecha_nacimiento,$nss,$curp,$rfc,$clabe,$banco,$estado_civil,$tipo_sangre,$no_hijos,$calle,$no_ext,
		$no_int,$colonia,$tel,$celular,$otro_tel,$correo_personal,$residencia,$contacto_emergencia,$tel_contacto,$celular_contacto,$carrera,$grado,$documento_lic,$posgrado,$fecha_termino_posgrado,
		$documento_posgrado,$diplomado_especialidad,$documento_diplomado,$fecha_termino_diplomado,$certificacion,$documento_cer,$fecha_termino_certificacion,$fecha_ingreso,$registro_dc5,
		$tel_empresa,$tel_extension,$mail_empresarial)
		{
			$this->conn->beginTransaction();
			try
			{
				$query="UPDATE T_Agentes SET correo_oexl=:correo,antiguedad = :antiguedad,sexo = :sexo,fecha_nacimiento = :fecha_nacimiento,nss = :nss,curp = :curp,rfc = :rfc,clabe = :clabe,
							id_banco = :banco,estado_civil = :estado_civil,tipo_sangre = :tipo_sangre,no_hijos = :no_hijos,calle = :calle,no_ext = :no_ext,no_int = :no_int,id = :colonia,
							tel_personal = :tel,celular_personal = :celular,otro_tel_personal = :otro_tel,correo_personal = :correo_personal,residencia = :residencia,contacto_emergencia = :contacto_emergencia,
							tel_contacto = :tel_contacto,celular_contacto = :celular_contacto,id_carrera = :carrera,grado = :grado,documento_lic = :documento_lic,posgrado = :posgrado,
							fecha_termino_posgrado = :fecha_termino_posgrado,documento_posgrado = :documento_posgrado,diplomado_especialidad = :diplomado_especialidad,documento_diplomado = :documento_diplomado,
							fecha_termino_diplomado = :fecha_termino_diplomado,certificacion = :certificacion,documento_certificado = :documento_cer,fecha_termino_certificacion = :fecha_termino_certificacion,
							fecha_ingreso = :fecha_ingreso,registro_dc5 = :registro_dc5,tel_empresa = :tel_empresa,tel_extension = :tel_extension,correo_empresarial = :mail_empresarial
						WHERE id_agente = :agente";
				$stmt = $this->conn->prepare($query);
				$stmt->bindparam(":correo",$correo);
				$stmt->bindparam(":antiguedad",$antiguedad);
				$stmt->bindparam(":sexo",$sexo);
				$stmt->bindparam(":fecha_nacimiento",$fecha_nacimiento);
				$stmt->bindparam(":nss",$nss);
				$stmt->bindparam(":curp",$curp);
				$stmt->bindparam(":rfc",$rfc);
				$stmt->bindparam(":clabe",$clabe);
				$stmt->bindparam(":banco",$banco);
				$stmt->bindparam(":estado_civil",$estado_civil);
				$stmt->bindparam(":tipo_sangre",$tipo_sangre);
				$stmt->bindparam(":no_hijos",$no_hijos);
				$stmt->bindparam(":calle",$calle);
				$stmt->bindparam(":no_ext",$no_ext);
				$stmt->bindparam(":no_int",$no_int);
				$stmt->bindparam(":colonia",$colonia);
				$stmt->bindparam(":tel",$tel);
				$stmt->bindparam(":celular",$celular);
				$stmt->bindparam(":otro_tel",$otro_tel);
				$stmt->bindparam(":correo_personal",$correo_personal);
				$stmt->bindparam(":residencia",$residencia);
				$stmt->bindparam(":contacto_emergencia",$contacto_emergencia);
				$stmt->bindparam(":tel_contacto",$tel_contacto);
				$stmt->bindparam(":celular_contacto",$celular_contacto);
				$stmt->bindparam(":carrera",$carrera);
				$stmt->bindparam(":grado",$grado);
				$stmt->bindparam(":documento_lic",$documento_lic);
				$stmt->bindparam(":posgrado",$posgrado);
				$stmt->bindparam(":fecha_termino_posgrado",$fecha_termino_posgrado);
				$stmt->bindparam(":documento_posgrado",$documento_posgrado);
				$stmt->bindparam(":diplomado_especialidad",$diplomado_especialidad);
				$stmt->bindparam(":documento_diplomado",$documento_diplomado);
				$stmt->bindparam(":fecha_termino_diplomado",$fecha_termino_diplomado);
				$stmt->bindparam(":certificacion",$certificacion);
				$stmt->bindparam(":documento_cer",$documento_cer);
				$stmt->bindparam(":fecha_termino_certificacion",$fecha_termino_certificacion);
				$stmt->bindparam(":fecha_ingreso",$fecha_ingreso);
				$stmt->bindparam(":registro_dc5",$registro_dc5);
				$stmt->bindparam(":tel_empresa",$tel_empresa);
				$stmt->bindparam(":tel_extension",$tel_extension);
				$stmt->bindparam(":mail_empresarial",$mail_empresarial);
				$stmt->bindparam(":agente",$agente);
				$stmt->execute();
				$last_id = $this->conn->lastInsertId();
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
		//Update Si Confirmación
		public function confirmarAsignacionAgente($id)
		{
			$this->conn->beginTransaction();
			try
			{
				$query="UPDATE T_Detalle_Bitacora_Agente SET id_estatusasignacion = 2, fecha_confirmacion = NOW()
						WHERE id_detallebitacora_agente = :id";
				$stmt = $this->conn->prepare($query);
				$stmt->bindparam(":id",$id);
				$stmt->execute();
				$last_id = $this->conn->lastInsertId();
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
		//Update Reasignación
		public function reasignacionAgentes($idDetalle,$tipo_rechazo,$comentarios_agente)
		{
			$this->conn->beginTransaction();
			try
			{
				$query="SELECT 
							DBA.id_agente,
							DBA.id_tipoagente,
							DBA.costo,
							DB.fecha_ejecucion,
							DB.id_subproducto
						FROM T_Detalle_Bitacora_Agente AS DBA
						INNER JOIN T_Detalle_Bitacora AS DB
						ON DB.id_detallebitacora = DBA.id_detallebitacora
						WHERE DBA.id_detallebitacora_agente = :idDetalle";
				$stmt = $this->conn->prepare($query);
				$stmt->bindparam(":idDetalle",$idDetalle);
				$stmt->execute();
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				$agente = $row['id_agente'];
				$tipo_agente = $row['id_tipoagente'];
				$monto = $row['costo'];
				$fecha_realizacion = $row['fecha_ejecucion'];
				$subproducto = $row['id_subproducto'];
					
				$query = "INSERT INTO T_Rechazos (id_agente, id_tiporechazo, monto, fecha_rechazo, id_detallebitacora_agente, comentarios) 
				VALUES (:agente,:tipo_rechazo,:monto,NOW(),:idDetalle,:comentarios_agente)";
				$stmt2 = $this->conn->prepare($query);
				$stmt2->bindparam(":agente",$agente);
				$stmt2->bindparam(":tipo_rechazo",$tipo_rechazo);
				$stmt2->bindparam(":monto",$monto);
				$stmt2->bindparam(":idDetalle",$idDetalle);
				$stmt2->bindparam(":comentarios_agente",$comentarios_agente);
				$stmt2->execute();
				
				
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
								if(strpos($id, '+') !== false && !empty($agentesOferta))
								{
									$contadorAux = $y;
									$randomNum = rand(0,$contadorAux);
									$agente = intval($agentesOferta[$randomNum]['id_agente']);
								}
								else
								{
									$agente = intval($id);
								}
							}
							else
							{
								$agente = intval($id);
								
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
		//Update Cambio Manual
		public function updateAgenteManual($idDetalle,$tipo_agente,$agente)
		{
			$this->conn->beginTransaction();
			try
			{
				$query="SELECT 
							DB.id_subproducto
						FROM T_Detalle_Bitacora_Agente AS DBA
						INNER JOIN T_Detalle_Bitacora AS DB
						ON DB.id_detallebitacora = DBA.id_detallebitacora
						WHERE DBA.id_detallebitacora_agente = :idDetalle";
				$stmt = $this->conn->prepare($query);
				$stmt->bindparam(":idDetalle",$idDetalle);
				$stmt->execute();
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				$subproducto = $row['id_subproducto'];
				
				if($subproducto == 112)
				{
					$detalleBitacora2 = $idDetalle+1;
					$detalleBitacora3 = $idDetalle+2;
					$detalleBitacora4 = $idDetalle+3;
					$query="UPDATE T_Detalle_Bitacora_Agente SET id_agente = :agente, id_tipoagente = :tipo_agente
						WHERE id_detallebitacora_agente = :idDetalle OR id_detallebitacora_agente = :detalleBitacora2 OR id_detallebitacora_agente = :detalleBitacora3 OR id_detallebitacora_agente = :detalleBitacora4";
					$stmt7 = $this->conn->prepare($query);
					$stmt7->bindparam(":agente",$agente);
					$stmt7->bindparam(":tipo_agente",$tipo_agente);
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
					$query="UPDATE T_Detalle_Bitacora_Agente SET id_agente = :agente, id_tipoagente = :tipo_agente
						WHERE id_detallebitacora_agente = :idDetalle OR id_detallebitacora_agente = :detalleBitacora2 OR id_detallebitacora_agente = :detalleBitacora3";
					$stmt7 = $this->conn->prepare($query);
					$stmt7->bindparam(":agente",$agente);
					$stmt7->bindparam(":tipo_agente",$tipo_agente);
					$stmt7->bindparam(":idDetalle",$idDetalle);
					$stmt7->bindparam(":detalleBitacora2",$detalleBitacora2);
					$stmt7->bindparam(":detalleBitacora3",$detalleBitacora3);
					$stmt7->execute();
				}
				elseif($subproducto == 114)
				{
					$detalleBitacora2 = $idDetalle+1;
					$query="UPDATE T_Detalle_Bitacora_Agente SET id_agente = :agente, id_tipoagente = :tipo_agente
						WHERE id_detallebitacora_agente = :idDetalle OR id_detallebitacora_agente = :detalleBitacora2";
					$stmt7 = $this->conn->prepare($query);
					$stmt7->bindparam(":agente",$agente);
					$stmt7->bindparam(":tipo_agente",$tipo_agente);
					$stmt7->bindparam(":idDetalle",$idDetalle);
					$stmt7->bindparam(":detalleBitacora2",$detalleBitacora2);
					$stmt7->execute();
				}
				else
				{
					$query="UPDATE T_Detalle_Bitacora_Agente SET id_agente = :agente, id_tipoagente = :tipo_agente
						WHERE id_detallebitacora_agente = :idDetalle";
					$stmt7 = $this->conn->prepare($query);
					$stmt7->bindparam(":agente",$agente);
					$stmt7->bindparam(":tipo_agente",$tipo_agente);
					$stmt7->bindparam(":idDetalle",$idDetalle);
					$stmt7->execute();
				}
				$query="UPDATE T_Calendario_Agentes AS CA,  T_Detalle_Bitacora_Agente AS DBA SET CA.id_agente = :agente 
						WHERE id_detallebitacora_agente = :idDetalle";
				$stmt2 = $this->conn->prepare($query);
				$stmt2->bindparam(":agente",$agente);
				$stmt2->bindparam(":idDetalle",$idDetalle);
				$stmt2->execute();
				
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
		
	}
