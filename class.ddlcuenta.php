<?php
	include_once 'dbconfig.php';
	class DDL{
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
		
		
		public function selectClienteCualitativoUpdate($cliente)
		{
			$stmt = $this->conn->prepare("SELECT id_cliente, nombre_cliente FROM T_Clientes WHERE id_cliente =:cliente");
			$stmt->execute(array(':cliente'=>$cliente));
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
			?>
			<option selected="selected" value="<?php echo $row['id_cliente']; ?>"><?php echo $row['nombre_cliente']; ?></option>
			<?php
			}
		}
		public function selectClienteCualitativo($zona)
		{
			$stmt = $this->conn->prepare("SELECT * FROM T_Clientes WHERE id_zona =:zona AND finished_analisiscual = 0");
			$stmt->execute(array(':zona'=>$zona));
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
			?>
			<option value="<?php echo $row['id_cliente']; ?>"><?php echo $row['nombre_cliente']; ?></option>
			<?php
			}
		}
		public function selectClienteCuantitativo($zona)
		{
			$stmt = $this->conn->prepare("SELECT * FROM T_Clientes WHERE id_zona =:zona AND finished_analisiscuant = 0");
			$stmt->execute(array(':zona'=>$zona));
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
			?>
			<option value="<?php echo $row['id_cliente']; ?>"><?php echo $row['nombre_cliente']; ?></option>
			<?php
			}
		}
		public function selectClienteCuantitativoUpdate($cliente)
		{
			$stmt = $this->conn->prepare("SELECT id_cliente, nombre_cliente FROM T_Clientes WHERE id_cliente =:cliente");
			$stmt->execute(array(':cliente'=>$cliente));
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
			?>
			<option selected="selected" value="<?php echo $row['id_cliente']; ?>"><?php echo $row['nombre_cliente']; ?></option>
			<?php
			}
		}
		
		public function selectCliente($zona)
		{
			$stmt = $this->conn->prepare("SELECT * FROM T_Clientes WHERE id_zona =:zona");
			$stmt->execute(array(':zona'=>$zona));
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
			?>
			<option value="<?php echo $row['id_cliente']; ?>"><?php echo $row['nombre_cliente']; ?></option>
			<?php
			}
			
		}
		public function selectYearCuenta()
		{
			$stmt = $this->conn->prepare("SELECT * FROM T_YearCuenta");
			$stmt->execute();
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
			?>
			<option value="<?php echo $row['id_yearcuenta']; ?>"><?php echo $row['year_cuenta']; ?></option>
			<?php
			}
			
		}
		public function selectYearCuentaUpdate($year)
		{
			$stmt = $this->conn->prepare("SELECT * FROM T_YearCuenta WHERE id_yearcuenta = :year");
			$stmt->execute(array(':year'=>$year));
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
			?>
			<option value="<?php echo $row['id_yearcuenta']; ?>"><?php echo $row['year_cuenta']; ?></option>
			<?php
			}
			
		}
		public function selectHistoricoVAS($cliente, $year)
		{
			$stmt = $this->conn->prepare("SELECT 
				C.id_cliente,
				(SELECT SUM(AC.valor_ajustado) FROM T_AnalisisCuantitativo_Escenario2 AS AC WHERE AC.id_cliente = C.id_cliente AND AC.year_acuant = :year) AS valor_ajustado,
				(SELECT SUM(DAC.cuotadef_ac) FROM T_AnalisisCuantitativo_Escenario2 AS AC INNER JOIN T_Detalle_AnalisisCuantitativo_Escenario2 AS DAC ON DAC.id_analisiscuantitativo = AC.id_analisiscuantitativo WHERE AC.id_cliente = C.id_cliente AND AC.year_acuant = :year) AS escenario2Ant,
				(SELECT SUM(DB.precio_bitacora) FROM T_Detalle_Bitacora AS DB INNER JOIN T_Bitacora AS B ON B.id_bitacora = DB.id_bitacora WHERE B.id_cliente = C.id_cliente AND ((LENGTH(DB.fecha_ejecucion)>20 AND SUBSTR(DB.fecha_ejecucion,7,4) = :year) OR (LENGTH(DB.fecha_ejecucion)<=20 AND YEAR(DB.fecha_ejecucion) = :year))) AS ventas,
				(SELECT year_ac FROM T_AnalisisCualitativo AS AC WHERE AC.id_cliente = C.id_cliente AND AC.year_acuant = :year) AS anios
			FROM T_Clientes AS C
			WHERE C.id_cliente=:cliente");
			$stmt->execute(array(':year'=>$year,':cliente'=>$cliente));
			$row=$stmt->fetch(PDO::FETCH_ASSOC);						
			echo json_encode($row);
			exit;
		}
		public function selectHistoricoAC($cliente, $year)
		{
			$stmt = $this->conn->prepare("SELECT * FROM T_AnalisisCualitativo WHERE id_cliente=:cliente AND year_ac=:year");
			$stmt->execute(array(':cliente'=>$cliente, ':year'=>$year));
			$row=$stmt->fetch(PDO::FETCH_ASSOC);						
			echo json_encode($row);
			exit;
		}
		public function selectHistoricoACuant($cliente, $year)
		{
			$stmt = $this->conn->prepare("SELECT C.nombre_cliente,
				  OI.year_oi, 
				  AC.valor_ajustado,
				  AC.cuota,
				  ROUND(SUM(DISTINCT(AC.cuota/AC.valor_ajustado))*100) AS VajCuota,
				  SUM(DOI.cantidad_oi * S.precio_subproducto) AS RealAprovechado,
				  ROUND((SUM(DOI.cantidad_oi * S.precio_subproducto) / SUM(DISTINCT(AC.cuota)))*100) AS RealApCuota,
				  ROUND((SUM(DOI.cantidad_oi * S.precio_subproducto) / SUM(DISTINCT(AC.valor_ajustado)))*100) AS RealApVAj
				  FROM T_DetalleOI AS DOI
				  INNER JOIN T_Subproductos as S
				  ON. S.id_subproducto = DOI.id_subproducto
				  INNER JOIN T_Ordenes_Intervencion as OI
				  ON OI.id_oi = DOI.id_oi
				  INNER JOIN T_Clientes AS C
				  ON C.id_cliente = OI.id_cliente
				  INNER JOIN T_AnalisisCuantitativo AS AC
				  ON AC.id_cliente = OI.id_cliente AND OI.year_oi = AC.year_acuant
				  WHERE OI.id_cliente=:cliente AND OI.year_oi = :year AND AC.year_acuant=:year
				  GROUP BY OI.year_oi, AC.year_acuant");
			$stmt->execute(array(':cliente'=>$cliente, ':year'=>$year));
			$row=$stmt->fetch(PDO::FETCH_ASSOC);						
			echo json_encode($row);
			exit;
		}
		
		public function selectHistoricoACuant2($cliente, $year)
		{
			$stmt = $this->conn->prepare("SELECT C.nombre_cliente,
				  OI.year_oi, 
				  AC.valor_ajustado,
				  AC.cuota,
				  ROUND(SUM(DISTINCT(AC.cuota/AC.valor_ajustado))*100) AS VajCuota,
				  SUM(DOI.cantidad_oi * S.precio_subproducto) AS RealAprovechado,
				  ROUND((SUM(DOI.cantidad_oi * S.precio_subproducto) / SUM(DISTINCT(AC.cuota)))*100) AS RealApCuota,
				  ROUND((SUM(DOI.cantidad_oi * S.precio_subproducto) / SUM(DISTINCT(AC.valor_ajustado)))*100) AS RealApVAj
				  FROM T_DetalleOI AS DOI
				  INNER JOIN T_Subproductos as S
				  ON. S.id_subproducto = DOI.id_subproducto
				  INNER JOIN T_Ordenes_Intervencion as OI
				  ON OI.id_oi = DOI.id_oi
				  INNER JOIN T_Clientes AS C
				  ON C.id_cliente = OI.id_cliente
				  INNER JOIN T_AnalisisCuantitativo AS AC
				  ON AC.id_cliente = OI.id_cliente AND OI.year_oi = AC.year_acuant
				  WHERE OI.id_cliente=:cliente AND OI.year_oi = :year AND AC.year_acuant=:year
				  GROUP BY OI.year_oi, AC.year_acuant");
			$stmt->execute(array(':cliente'=>$cliente, ':year'=>$year));
			$row=$stmt->fetch(PDO::FETCH_ASSOC);						
			echo json_encode($row);
			exit;
		}
		
		public function selectHistoricoACuant3($cliente, $year)
		{
			$stmt = $this->conn->prepare("SELECT C.nombre_cliente,
				  OI.year_oi, 
				  AC.valor_ajustado,
				  AC.cuota,
				  ROUND(SUM(DISTINCT(AC.cuota/AC.valor_ajustado))*100) AS VajCuota,
				  SUM(DOI.cantidad_oi * S.precio_subproducto) AS RealAprovechado,
				  ROUND((SUM(DOI.cantidad_oi * S.precio_subproducto) / SUM(DISTINCT(AC.cuota)))*100) AS RealApCuota,
				  ROUND((SUM(DOI.cantidad_oi * S.precio_subproducto) / SUM(DISTINCT(AC.valor_ajustado)))*100) AS RealApVAj
				  FROM T_DetalleOI AS DOI
				  INNER JOIN T_Subproductos as S
				  ON. S.id_subproducto = DOI.id_subproducto
				  INNER JOIN T_Ordenes_Intervencion as OI
				  ON OI.id_oi = DOI.id_oi
				  INNER JOIN T_Clientes AS C
				  ON C.id_cliente = OI.id_cliente
				  INNER JOIN T_AnalisisCuantitativo AS AC
				  ON AC.id_cliente = OI.id_cliente AND OI.year_oi = AC.year_acuant
				  WHERE OI.id_cliente=:cliente AND OI.year_oi = :year AND AC.year_acuant=:year
				  GROUP BY OI.year_oi, AC.year_acuant");
			$stmt->execute(array(':cliente'=>$cliente,':year'=>$year));
			$row=$stmt->fetch(PDO::FETCH_ASSOC);						
			echo json_encode($row);
			exit;
		}
		
	}
	
	
	
	


?>
