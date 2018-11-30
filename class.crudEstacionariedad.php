<?php
include_once 'dbconfig.php';
class crud
{
	private $conn;
	
	public function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
	}
	//INTERESES//
	public function interesesAnual($zona,$year)
	{
		//BITACORA
		$query = "SELECT 
					IF(DB.id_mesejecucion=1,SUM(DB.costo_bitacora),0) AS EneroC,
					IF(DB.id_mesejecucion=2,SUM(DB.costo_bitacora),0) AS FebreroC,
					IF(DB.id_mesejecucion=3,SUM(DB.costo_bitacora),0) AS MarzoC,
					IF(DB.id_mesejecucion=4,SUM(DB.costo_bitacora),0) AS AbrilC,
					IF(DB.id_mesejecucion=5,SUM(DB.costo_bitacora),0) AS MayoC,
					IF(DB.id_mesejecucion=6,SUM(DB.costo_bitacora),0) AS JunioC,
					IF(DB.id_mesejecucion=7,SUM(DB.costo_bitacora),0) AS JulioC,
					IF(DB.id_mesejecucion=8,SUM(DB.costo_bitacora),0) AS AgostoC,
					IF(DB.id_mesejecucion=9,SUM(DB.costo_bitacora),0) AS SeptiembreC,
					IF(DB.id_mesejecucion=10,SUM(DB.costo_bitacora),0) AS OctubreC,
					IF(DB.id_mesejecucion=11,SUM(DB.costo_bitacora),0) AS NoviembreC,
					IF(DB.id_mesejecucion=12,SUM(DB.costo_bitacora),0) AS DiciembreC,
					SUM(DB.costo_bitacora) AS TOTALC,
					IF(DB.id_mesejecucion=1,SUM(DB.precio_bitacora*DB.gasto_directo),0) AS EneroG,
					IF(DB.id_mesejecucion=2,SUM(DB.precio_bitacora*DB.gasto_directo),0) AS FebreroG,
					IF(DB.id_mesejecucion=3,SUM(DB.precio_bitacora*DB.gasto_directo),0) AS MarzoG,
					IF(DB.id_mesejecucion=4,SUM(DB.precio_bitacora*DB.gasto_directo),0) AS AbrilG,
					IF(DB.id_mesejecucion=5,SUM(DB.precio_bitacora*DB.gasto_directo),0) AS MayoG,
					IF(DB.id_mesejecucion=6,SUM(DB.precio_bitacora*DB.gasto_directo),0) AS JunioG,
					IF(DB.id_mesejecucion=7,SUM(DB.precio_bitacora*DB.gasto_directo),0) AS JulioG,
					IF(DB.id_mesejecucion=8,SUM(DB.precio_bitacora*DB.gasto_directo),0) AS AgostoG,
					IF(DB.id_mesejecucion=9,SUM(DB.precio_bitacora*DB.gasto_directo),0) AS SeptiembreG,
					IF(DB.id_mesejecucion=10,SUM(DB.precio_bitacora*DB.gasto_directo),0) AS OctubreG,
					IF(DB.id_mesejecucion=11,SUM(DB.precio_bitacora*DB.gasto_directo),0) AS NoviembreG,
					IF(DB.id_mesejecucion=12,SUM(DB.precio_bitacora*DB.gasto_directo),0) AS DiciembreG,
					SUM(DB.precio_bitacora*DB.gasto_directo) AS TOTALG
				FROM T_Clientes AS C
				LEFT OUTER JOIN T_Bitacora AS B
				ON B.id_cliente = C.id_cliente
				LEFT OUTER JOIN T_Detalle_Bitacora AS DB
				ON DB.id_bitacora = B.id_bitacora
				LEFT OUTER JOIN T_Subproductos AS S
				ON S.id_subproducto = DB.id_subproducto
				WHERE C.id_zona = :zona AND YEAR(B.fecha_audit) = :year AND DB.id_estatusejecucion=1
				GROUP BY DB.id_mesejecucion";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(":zona",$zona);
		$stmt->bindparam(":year",$year);
		$stmt->execute();
		
		//QUERY GAO REAL
		$query ="SELECT 
				  IF(N.id_mes=1,(SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.intereses)+SUM(otros)),0) AS EneroGAO,
				  IF(N.id_mes=2,(SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.intereses)+SUM(otros)),0) AS FebreroGAO,
				  IF(N.id_mes=3,(SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.intereses)+SUM(otros)),0) AS MarzoGAO,
				  IF(N.id_mes=4,(SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.intereses)+SUM(otros)),0) AS AbrilGAO,
				  IF(N.id_mes=5,(SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.intereses)+SUM(otros)),0) AS MayoGAO,
				  IF(N.id_mes=6,(SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.intereses)+SUM(otros)),0) AS JunioGAO,
				  IF(N.id_mes=7,(SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.intereses)+SUM(otros)),0) AS JulioGAO,
				  IF(N.id_mes=8,(SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.intereses)+SUM(otros)),0) AS AgostoGAO,
				  IF(N.id_mes=9,(SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.intereses)+SUM(otros)),0) AS SeptiembreGAO,
				  IF(N.id_mes=10,(SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.intereses)+SUM(otros)),0) AS OctubreGAO,
				  IF(N.id_mes=11,(SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.intereses)+SUM(otros)),0) AS NoviembreGAO,
				  IF(N.id_mes=12,(SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.intereses)+SUM(otros)),0) AS DiciembreGAO,
				  (SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.intereses)+SUM(otros)) AS TotalGAO
				  FROM T_Nomina_GAO_Real AS N
				  INNER JOIN T_Plantilla AS P
				  ON N.id_plantilla = P.id_plantilla
				  INNER JOIN T_PlantillaDatos as PD
				  ON PD.id_plantilladatos = P.id_plantilladatos
				  WHERE PD.id_zona=:zona AND N.year_nomina = :year";
		$stmt4 = $this->conn->prepare($query);
		$stmt4->bindparam(":zona",$zona);
		$stmt4->bindparam(":year",$year);
		$stmt4->execute();
		//QUERY ANTICIPOS
		$query ="SELECT 
					(CASE WHEN id_mes=1 THEN SUM(anticipo) ELSE 0 END) AS Enero,
					(CASE WHEN id_mes=2 THEN SUM(anticipo) ELSE 0 END) AS Febrero,
					(CASE WHEN id_mes=3 THEN SUM(anticipo) ELSE 0 END) AS Marzo,
					(CASE WHEN id_mes=4 THEN SUM(anticipo) ELSE 0 END) AS Abril,
					(CASE WHEN id_mes=5 THEN SUM(anticipo) ELSE 0 END) AS Mayo,
					(CASE WHEN id_mes=6 THEN SUM(anticipo) ELSE 0 END) AS Junio,
					(CASE WHEN id_mes=7 THEN SUM(anticipo) ELSE 0 END) AS Julio,
					(CASE WHEN id_mes=8 THEN SUM(anticipo) ELSE 0 END) AS Agosto,
					(CASE WHEN id_mes=9 THEN SUM(anticipo) ELSE 0 END) AS Septiembre,
					(CASE WHEN id_mes=10 THEN SUM(anticipo) ELSE 0 END) AS Octubre,
					(CASE WHEN id_mes=11 THEN SUM(anticipo) ELSE 0 END) AS Noviembre,
					(CASE WHEN id_mes=12 THEN SUM(anticipo) ELSE 0 END) AS Diciembre,
					SUM(anticipo) AS TotalAnticipo,
					oferta
				  FROM T_AnticiposLider
				  WHERE id_zona=:zona AND year_anticipo = :year
				  GROUP BY id_mes";
		$stmt5 = $this->conn->prepare($query);
		$stmt5->bindparam(":zona",$zona);
		$stmt5->bindparam(":year",$year);
		$stmt5->execute();
		//QUERY INTERESES
		$query = "SELECT 
					IF(DB.id_mescobranza=1,SUM(DB.precio_bitacora),0) AS Enero,
					IF(DB.id_mescobranza=2,SUM(DB.precio_bitacora),0) AS Febrero,
					IF(DB.id_mescobranza=3,SUM(DB.precio_bitacora),0) AS Marzo,
					IF(DB.id_mescobranza=4,SUM(DB.precio_bitacora),0) AS Abril,
					IF(DB.id_mescobranza=5,SUM(DB.precio_bitacora),0) AS Mayo,
					IF(DB.id_mescobranza=6,SUM(DB.precio_bitacora),0) AS Junio,
					IF(DB.id_mescobranza=7,SUM(DB.precio_bitacora),0) AS Julio,
					IF(DB.id_mescobranza=8,SUM(DB.precio_bitacora),0) AS Agosto,
					IF(DB.id_mescobranza=9,SUM(DB.precio_bitacora),0) AS Septiembre,
					IF(DB.id_mescobranza=10,SUM(DB.precio_bitacora),0) AS Octubre,
					IF(DB.id_mescobranza=11,SUM(DB.precio_bitacora),0) AS Noviembre,
					IF(DB.id_mescobranza=12,SUM(DB.precio_bitacora),0) AS Diciembre,
					SUM(DB.precio_bitacora) AS TOTALP
				FROM T_Clientes AS C
				LEFT OUTER JOIN T_Bitacora AS B
				ON B.id_cliente = C.id_cliente
				LEFT OUTER JOIN T_Detalle_Bitacora AS DB
				ON DB.id_bitacora = B.id_bitacora
				LEFT OUTER JOIN T_Subproductos AS S
				ON S.id_subproducto = DB.id_subproducto
				WHERE C.id_zona = :zona AND YEAR(B.fecha_audit) = :year AND DB.id_estatuscobranza = 1
				GROUP BY id_mescobranza";
		$stmt6 = $this->conn->prepare($query);
		$stmt6->bindparam(":zona",$zona);
		$stmt6->bindparam(":year",$year);
		$stmt6->execute();
		
		
		//if($stmt6->rowCount()>0)
		if(true)
		{
			$enero = 0;
			$eneroC = 0;
			$eneroG = 0;
			$febrero = 0;
			$febreroC = 0;
			$febreroG = 0;
			$marzo = 0;
			$marzoC = 0;
			$marzoG = 0;
			$abril = 0;
			$abrilC = 0;
			$abrilG = 0;
			$mayo = 0;
			$mayoC = 0;
			$mayoG = 0;
			$junio = 0;
			$junioC = 0;
			$junioG = 0;
			$julio = 0;
			$julioC = 0;
			$julioG = 0;
			$agosto = 0;
			$agostoC = 0;
			$agostoG = 0;
			$septiembre = 0;
			$septiembreC = 0;
			$septiembreG = 0;
			$octubre = 0;
			$octubreC = 0;
			$octubreG = 0;
			$noviembre = 0;
			$noviembreC = 0;
			$noviembreG = 0;
			$diciembre = 0;
			$diciembreC = 0;
			$diciembreG = 0;
			$TOTALP = 0;
			$TOTALC = 0;
			$TOTALG = 0;
			
			$eneroA = 0;
			$febreroA = 0;
			$marzoA = 0;
			$abrilA = 0;
			$mayoA = 0;
			$junioA = 0;
			$julioA = 0;
			$agostoA = 0;
			$septiembreA = 0;
			$octubreA = 0;
			$noviembreA = 0;
			$diciembreA = 0;
			$TOTALA = 0;
			
			$eneroGAO = 0;
			$febreroGAO = 0;
			$marzoGAO = 0;
			$abrilGAO = 0;
			$mayoGAO = 0;
			$junioGAO = 0;
			$julioGAO = 0;
			$agostoGAO = 0;
			$septiembreGAO = 0;
			$octubreGAO = 0;
			$noviembreGAO = 0;
			$diciembreGAO = 0;
			$TOTALGAO = 0;
			
			$eneroNomina = 0;
			$febreroNomina = 0;
			$marzoNomina = 0;
			$abrilNomina = 0;
			$mayoNomina = 0;
			$junioNomina = 0;
			$julioNomina = 0;
			$agostoNomina = 0;
			$septiembreNomina = 0;
			$octubreNomina = 0;
			$noviembreNomina = 0;
			$diciembreNomina = 0;
			$TOTALNomina = 0;
			
			$eneroSAC = 0;
			$febreroSAC = 0;
			$marzoSAC = 0;
			$abrilSAC = 0;
			$mayoSAC = 0;
			$junioSAC = 0;
			$julioSAC = 0;
			$agostoSAC = 0;
			$septiembreSAC = 0;
			$octubreSAC = 0;
			$noviembreSAC = 0;
			$diciembreSAC = 0;
			$TOTALSAC = 0;
			
			$eneroOtros = 0;
			$febreroOtros = 0;
			$marzoOtros = 0;
			$abrilOtros = 0;
			$mayoOtros = 0;
			$junioOtros = 0;
			$julioOtros = 0;
			$agostoOtros = 0;
			$septiembreOtros = 0;
			$octubreOtros = 0;
			$noviembreOtros = 0;
			$diciembreOtros = 0;
			$TOTALOtros = 0;
			
			$eneroIntereses = 0;
			$febreroIntereses = 0;
			$marzoIntereses = 0;
			$abrilIntereses = 0;
			$mayoIntereses = 0;
			$junioIntereses = 0;
			$julioIntereses = 0;
			$agostoIntereses = 0;
			$septiembreIntereses = 0;
			$octubreIntereses = 0;
			$noviembreIntereses = 0;
			$diciembreIntereses = 0;
			$TOTALIntereses = 0;
			
			$eneroCobranza = 0;
			$febreroCobranza = 0;
			$marzoCobranza = 0;
			$abrilCobranza = 0;
			$mayoCobranza = 0;
			$junioCobranza = 0;
			$julioCobranza = 0;
			$agostoCobranza = 0;
			$septiembreCobranza = 0;
			$octubreCobranza = 0;
			$noviembreCobranza = 0;
			$diciembreCobranza = 0;
			$TOTALCobranza = 0;
			
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				$eneroC = $eneroC + $row['EneroC'];
				$febreroC = $febreroC + $row['FebreroC'];
				$marzoC = $marzoC + $row['MarzoC'];
				$abrilC = $abrilC + $row['AbrilC'];
				$mayoC = $mayoC + $row['MayoC'];
				$junioC = $junioC + $row['JunioC'];
				$julioC = $julioC + $row['JulioC'];
				$agostoC = $agostoC + $row['AgostoC'];
				$septiembreC = $septiembreC + $row['SeptiembreC'];
				$octubreC = $octubreC + $row['OctubreC'];
				$noviembreC = $noviembreC + $row['NoviembreC'];
				$diciembreC = $diciembreC + $row['DiciembreC'];
				
				$eneroG = $eneroG + $row['EneroG'];
				$febreroG = $febreroG + $row['FebreroG'];
				$marzoG = $marzoG + $row['MarzoG'];
				$abrilG = $abrilG + $row['AbrilG'];
				$mayoG = $mayoG + $row['MayoG'];
				$junioG = $junioG + $row['JunioG'];
				$julioG = $julioG + $row['JulioG'];
				$agostoG = $agostoG + $row['AgostoG'];
				$septiembreG = $septiembreG + $row['SeptiembreG'];
				$octubreG = $octubreG + $row['OctubreG'];
				$noviembreG = $noviembreG + $row['NoviembreG'];
				$diciembreG = $diciembreG + $row['DiciembreG'];
				
				$TOTALC = $TOTALC + $row['TOTALC'];
				$TOTALG = $TOTALG + $row['TOTALG'];
			}
			while($row5=$stmt5->fetch(PDO::FETCH_ASSOC))
			{
				$eneroA = $eneroA + $row5['Enero'];
				$febreroA = $febreroA + $row5['Febrero'];
				$marzoA = $marzoA + $row5['Marzo'];
				$abrilA = $abrilA + $row5['Abril'];
				$mayoA = $mayoA + $row5['Mayo'];
				$junioA = $junioA + $row5['Junio'];
				$julioA = $julioA + $row5['Julio'];
				$agostoA = $agostoA + $row5['Agosto'];
				$septiembreA = $septiembreA + $row5['Septiembre'];
				$octubreA = $octubreA + $row5['Octubre'];
				$noviembreA = $noviembreA + $row5['Noviembre'];
				$diciembreA = $diciembreA + $row5['Diciembre'];
				$TOTALA = $TOTALA + $row5['TotalAnticipo'];
				$oferta = $row5['oferta'];
			}
			while($row4=$stmt4->fetch(PDO::FETCH_ASSOC))
			{
				$eneroGAO = $eneroGAO + $row4['EneroGAO'];
				$febreroGAO = $febreroGAO + $row4['FebreroGAO'];
				$marzoGAO = $marzoGAO + $row4['MarzoGAO'];
				$abrilGAO = $abrilGAO + $row4['AbrilGAO'];
				$mayoGAO = $mayoGAO + $row4['MayoGAO'];
				$junioGAO = $junioGAO + $row4['JunioGAO'];
				$julioGAO = $julioGAO + $row4['JulioGAO'];
				$agostoGAO = $agostoGAO + $row4['AgostoGAO'];
				$septiembreGAO = $septiembreGAO + $row4['SeptiembreGAO'];
				$octubreGAO = $octubreGAO + $row4['OctubreGAO'];
				$noviembreGAO = $noviembreGAO + $row4['NoviembreGAO'];
				$diciembreGAO = $diciembreGAO + $row4['DiciembreGAO'];
				$TOTALGAO = $TOTALGAO + $row4['TotalGAO'];
			}
			while($row6=$stmt6->fetch(PDO::FETCH_ASSOC))
			{
				$eneroCobranza = $eneroCobranza + $row6['Enero'];
				$febreroCobranza = $febreroCobranza + $row6['Febrero'];
				$marzoCobranza = $marzoCobranza + $row6['Marzo'];
				$abrilCobranza = $abrilCobranza + $row6['Abril'];
				$mayoCobranza = $mayoCobranza + $row6['Mayo'];
				$junioCobranza = $junioCobranza + $row6['Junio'];
				$julioCobranza = $julioCobranza + $row6['Julio'];
				$agostoCobranza = $agostoCobranza + $row6['Agosto'];
				$septiembreCobranza = $septiembreCobranza + $row6['Septiembre'];
				$octubreCobranza = $octubreCobranza + $row6['Octubre'];
				$noviembreCobranza = $noviembreCobranza + $row6['Noviembre'];
				$diciembreCobranza = $diciembreCobranza + $row6['Diciembre'];
				$TOTALP = $TOTALP + $row6['TOTALP'];
			}
			$row5['Enero'] = $eneroA;
			$row5['Febrero'] = $febreroA;
			$row5['Marzo'] = $marzoA;
			$row5['Abril'] = $abrilA;
			$row5['Mayo'] = $mayoA;
			$row5['Junio'] = $junioA;
			$row5['Julio'] = $julioA;
			$row5['Agosto'] = $agostoA;
			$row5['Septiembre'] = $septiembreA;
			$row5['Octubre'] = $octubreA;
			$row5['Noviembre'] = $noviembreA;
			$row5['Diciembre'] = $diciembreA;
			$row5['oferta'] = $oferta;
			
			$row['EneroC'] = $eneroC;
			$row['FebreroC'] = $febreroC;
			$row['MarzoC'] = $marzoC;
			$row['AbrilC'] = $abrilC;
			$row['MayoC'] = $mayoC;
			$row['JunioC'] = $junioC;
			$row['JulioC'] = $julioC;
			$row['AgostoC'] = $agostoC;
			$row['SeptiembreC'] = $septiembreC;
			$row['OctubreC'] = $octubreC;
			$row['NoviembreC'] = $noviembreC;
			$row['DiciembreC'] = $diciembreC;
			
			$row['EneroG'] = $eneroG;
			$row['FebreroG'] = $febreroG;
			$row['MarzoG'] = $marzoG;
			$row['AbrilG'] = $abrilG;
			$row['MayoG'] = $mayoG;
			$row['JunioG'] = $junioG;
			$row['JulioG'] = $julioG;
			$row['AgostoG'] = $agostoG;
			$row['SeptiembreG'] = $septiembreG;
			$row['OctubreG'] = $octubreG;
			$row['NoviembreG'] = $noviembreG;
			$row['DiciembreG'] = $diciembreG;
			
			$row4['EneroGAO'] = $eneroGAO;
			$row4['FebreroGAO'] = $febreroGAO;
			$row4['MarzoGAO'] = $marzoGAO;
			$row4['AbrilGAO'] = $abrilGAO;
			$row4['MayoGAO'] = $mayoGAO;
			$row4['JunioGAO'] = $junioGAO;
			$row4['JulioGAO'] = $julioGAO;
			$row4['AgostoGAO'] = $agostoGAO;
			$row4['SeptiembreGAO'] = $septiembreGAO;
			$row4['OctubreGAO'] = $octubreGAO;
			$row4['NoviembreGAO'] = $noviembreGAO;
			$row4['DiciembreGAO'] = $diciembreGAO;
			
			$row4['EneroNomina'] = $eneroNomina;
			$row4['FebreroNomina'] = $febreroNomina;
			$row4['MarzoNomina'] = $marzoNomina;
			$row4['AbrilNomina'] = $abrilNomina;
			$row4['MayoNomina'] = $mayoNomina;
			$row4['JunioNomina'] = $junioNomina;
			$row4['JulioNomina'] = $julioNomina;
			$row4['AgostoNomina'] = $agostoNomina;
			$row4['SeptiembreNomina'] = $septiembreNomina;
			$row4['OctubreNomina'] = $octubreNomina;
			$row4['NoviembreNomina'] = $noviembreNomina;
			$row4['DiciembreNomina'] = $diciembreNomina;
			
			$row4['EneroSAC'] = $eneroSAC;
			$row4['FebreroSAC'] = $febreroSAC;
			$row4['MarzoSAC'] = $marzoSAC;
			$row4['AbrilSAC'] = $abrilSAC;
			$row4['MayoSAC'] = $mayoSAC;
			$row4['JunioSAC'] = $junioSAC;
			$row4['JulioSAC'] = $julioSAC;
			$row4['AgostoSAC'] = $agostoSAC;
			$row4['SeptiembreSAC'] = $septiembreSAC;
			$row4['OctubreSAC'] = $octubreSAC;
			$row4['NoviembreSAC'] = $noviembreSAC;
			$row4['DiciembreSAC'] = $diciembreSAC;
			
			$row4['EneroOtros'] = $eneroOtros;
			$row4['FebreroOtros'] = $febreroOtros;
			$row4['MarzoOtros'] = $marzoOtros;
			$row4['AbrilOtros'] = $abrilOtros;
			$row4['MayoOtros'] = $mayoOtros;
			$row4['JunioOtros'] = $junioOtros;
			$row4['JulioOtros'] = $julioOtros;
			$row4['AgostoOtros'] = $agostoOtros;
			$row4['SeptiembreOtros'] = $septiembreOtros;
			$row4['OctubreOtros'] = $octubreOtros;
			$row4['NoviembreOtros'] = $noviembreOtros;
			$row4['DiciembreOtros'] = $diciembreOtros;
			
			$row4['EneroIntereses'] = $eneroIntereses;
			$row4['FebreroIntereses'] = $febreroIntereses;
			$row4['MarzoIntereses'] = $marzoIntereses;
			$row4['AbrilIntereses'] = $abrilIntereses;
			$row4['MayoIntereses'] = $mayoIntereses;
			$row4['JunioIntereses'] = $junioIntereses;
			$row4['JulioIntereses'] = $julioIntereses;
			$row4['AgostoIntereses'] = $agostoIntereses;
			$row4['SeptiembreIntereses'] = $septiembreIntereses;
			$row4['OctubreIntereses'] = $octubreIntereses;
			$row4['NoviembreIntereses'] = $noviembreIntereses;
			$row4['DiciembreIntereses'] = $diciembreIntereses;
			
			$row6['EneroP'] = $eneroCobranza;
			$row6['FebreroP'] = $febreroCobranza;
			$row6['MarzoP'] = $marzoCobranza;
			$row6['AbrilP'] = $abrilCobranza;
			$row6['MayoP'] = $mayoCobranza;
			$row6['JunioP'] = $junioCobranza;
			$row6['JulioP'] = $julioCobranza;
			$row6['AgostoP'] = $agostoCobranza;
			$row6['SeptiembreP'] = $septiembreCobranza;
			$row6['OctubreP'] = $octubreCobranza;
			$row6['NoviembreP'] = $noviembreCobranza;
			$row6['DiciembreP'] = $diciembreCobranza;
			
			$row6['TOTALP'] = $TOTALP;
			$row['TOTALC'] = $TOTALC;
			$row['TOTALG'] = $TOTALG;
			$row5['TotalAnticipo'] = $TOTALA;
			$row4['TotalGAO'] = $TOTALGAO;
			
			
			
			
			//EBCL
			$ebclRealEnero = $row['EneroC'] + $row4['EneroGAO'];
			$ebclRealFebrero = $row['FebreroC'] + $row4['FebreroGAO'];
			$ebclRealMarzo = $row['MarzoC'] + $row4['MarzoGAO'];
			$ebclRealAbril = $row['AbrilC'] + $row4['AbrilGAO'];
			$ebclRealMayo = $row['MayoC'] + $row4['MayoGAO'];
			$ebclRealJunio = $row['JunioC'] + $row4['JunioGAO'];
			$ebclRealJulio = $row['JulioC'] + $row4['JulioGAO'];
			$ebclRealAgosto = $row['AgostoC'] + $row4['AgostoGAO'];
			$ebclRealSeptiembre = $row['SeptiembreC'] + $row4['SeptiembreGAO'];
			$ebclRealOctubre = $row['OctubreC'] + $row4['OctubreGAO'];
			$ebclRealNoviembre = $row['NoviembreC'] + $row4['NoviembreGAO'];
			$ebclRealDiciembre = $row['DiciembreC'] + $row4['DiciembreGAO'];
			$ebclRealTotal = $row['TOTALC'] + $row4['TotalGAO'];

			//COMISION PROYECTADA
			$comision = $row5['oferta'];
			$costo_real = floatval($row['TOTALC']);
			$ingreso_real = floatval($row6['TOTALP']);
			if($costo_real == 0)
			{
				$costo_realP = 0;
			}
			else
			{
				$costo_realP = $costo_real/$ingreso_real;
			}
			$comision_real = round($comision - $costo_realP,4);
			
			//MARGEN DIRECTO
			$diferenciaMargenDirecto1 = $row6['EneroP']-$row['EneroC']-$row['EneroG'];
			$diferenciaMargenDirecto2 =	$row6['FebreroP']-$row['FebreroC']-$row['FebreroG'];
			$diferenciaMargenDirecto3 =	$row6['MarzoP']-$row['MarzoC']-$row['MarzoG'];
			$diferenciaMargenDirecto4 =	$row6['AbrilP']-$row['AbrilC']-$row['AbrilG'];
			$diferenciaMargenDirecto5 =	$row6['MayoP']-$row['MayoC']-$row['MayoG'];
			$diferenciaMargenDirecto6 =	$row6['JunioP']-$row['JunioC']-$row['JunioG'];
			$diferenciaMargenDirecto7 =	$row6['JulioP']-$row['JulioC']-$row['JulioG'];
			$diferenciaMargenDirecto8 =	$row6['AgostoP']-$row['AgostoC']-$row['AgostoG'];
			$diferenciaMargenDirecto9 =	$row6['SeptiembreP']-$row['SeptiembreC']-$row['SeptiembreG'];
			$diferenciaMargenDirecto10 = $row6['OctubreP']-$row['OctubreC']-$row['OctubreG'];
			$diferenciaMargenDirecto11 = $row6['NoviembreP']-$row['NoviembreC']-$row['NoviembreG'];
			$diferenciaMargenDirecto12 = $row6['DiciembreP']-$row['DiciembreC']-$row['DiciembreG'];
			$diferenciaMargenDirectoTOTAL = $row6['TOTALP']-$row['TOTALC']-$row['TOTALG'];
			
			//COMISION REAL
			if($row4['EneroGAO'] == 0 || $row['EneroC'] == 0 || $row6['EneroP'] == 0)
			{
				$diferenciaComisionRPor1 = ($comision);
			}
			else
			{
				$diferenciaComisionRPor1 = ($comision-(($row['EneroC']/$row6['EneroP'])+($row4['EneroGAO']/$row6['EneroP'])));
			}
			$diferenciaComisionR1 = $row6['EneroP']*$diferenciaComisionRPor1;
			if($row['FebreroC'] == 0 || $row4['FebreroGAO'] == 0 || $row6['FebreroP'] == 0)
			{
				$diferenciaComisionRPor2 = ($comision);
			}
			else
			{
				$diferenciaComisionRPor2 = ($comision-(($row['FebreroC']/$row6['FebreroP'])+($row4['FebreroGAO']/$row6['FebreroP'])));
			}
			$diferenciaComisionR2 = $row6['FebreroP']*$diferenciaComisionRPor2;
			if($row4['MarzoGAO'] == 0 || $row['MarzoC'] == 0 || $row6['MarzoP'] == 0)
			{
				$diferenciaComisionRPor3 = ($comision);
			}
			else
			{	
				$diferenciaComisionRPor3 = ($comision-(($row['MarzoC']/$row6['MarzoP'])+($row4['MarzoGAO']/$row6['MarzoP'])));
			}
			$diferenciaComisionR3 = $row6['MarzoP']*$diferenciaComisionRPor3;
			if($row4['AbrilGAO'] == 0 || $row['AbrilC'] == 0 || $row6['AbrilP'] == 0)
			{
				$diferenciaComisionRPor4 = ($comision);
			}
			else
			{
				$diferenciaComisionRPor4 = ($comision-(($row['AbrilC']/$row6['AbrilP'])+($row4['AbrilGAO']/$row6['AbrilP'])));
			}
			$diferenciaComisionR4 = $row6['AbrilP']*$diferenciaComisionRPor4;
			if($row4['MayoGAO'] == 0 || $row['MayoC'] == 0 || $row6['MayoP'] == 0)
			{
				$diferenciaComisionRPor5 = ($comision);
			}
			else
			{
				$diferenciaComisionRPor5 = ($comision-(($row['MayoC']/$row6['MayoP'])+($row4['MayoGAO']/$row6['MayoP'])));
			}
			$diferenciaComisionR5 = $row6['MayoP']*$diferenciaComisionRPor5;
			if($row4['JunioGAO'] == 0 || $row['JunioC'] == 0 || $row6['JunioP'] == 0)
			{
				$diferenciaComisionRPor6 = ($comision);
			}
			else
			{
				$diferenciaComisionRPor6 = ($comision-(($row['JunioC']/$row6['JunioP'])+($row4['JunioGAO']/$row6['JunioP'])));
			}
			$diferenciaComisionR6 = $row6['JunioP']*$diferenciaComisionRPor6;
			if($row4['JulioGAO'] == 0 || $row['JulioC'] == 0 || $row6['JulioP'] == 0)
			{
				$diferenciaComisionRPor7 = ($comision);
			}
			else
			{	
				$diferenciaComisionRPor7 = ($comision-(($row['JulioC']/$row6['JulioP'])+($row4['JulioGAO']/$row6['JulioP'])));
			}
			$diferenciaComisionR7 = $row6['JulioP']*$diferenciaComisionRPor7;
			if($row4['AgostoGAO'] == 0 || $row['AgostoC'] == 0 || $row6['AgostoP'] == 0)
			{
				$diferenciaComisionRPor8 = ($comision);
			}
			else
			{
				$diferenciaComisionRPor8 = ($comision-(($row['AgostoC']/$row6['AgostoP'])+($row4['AgostoGAO']/$row6['AgostoP'])));
			}
			$diferenciaComisionR8 = $row6['AgostoP']*$diferenciaComisionRPor8;
			if($row4['SeptiembreGAO'] == 0 || $row['SeptiembreC'] == 0 || $row6['SeptiembreP'] == 0)
			{
				$diferenciaComisionRPor9 = ($comision);
			}
			else
			{
				$diferenciaComisionRPor9 = ($comision-(($row['SeptiembreC']/$row6['SeptiembreP'])+($row4['SeptiembreGAO']/$row6['SeptiembreP'])));
			}
			$diferenciaComisionR9 = $row6['SeptiembreP']*$diferenciaComisionRPor9;
			if($row4['OctubreGAO'] == 0 || $row['OctubreC'] == 0 || $row6['OctubreP'] == 0)
			{
				$diferenciaComisionRPor10 = ($comision);
			}
			else
			{
				$diferenciaComisionRPor10 = ($comision-(($row['OctubreC']/$row6['OctubreP'])+($row4['OctubreGAO']/$row6['OctubreP'])));
			}
			$diferenciaComisionR10 = $row6['OctubreP']*$diferenciaComisionRPor10;
			if($row4['NoviembreGAO'] == 0 || $row['NoviembreC'] == 0 || $row6['NoviembreP'] == 0)
			{
				$diferenciaComisionRPor11 = ($comision);
			}
			else
			{
				$diferenciaComisionRPor11 = ($comision-(($row['NoviembreC']/$row6['NoviembreP'])+($row4['NoviembreGAO']/$row6['NoviembreP'])));
			}
			$diferenciaComisionR11 = $row6['NoviembreP']*$diferenciaComisionRPor11;
			if($row4['DiciembreGAO'] == 0 || $row['DiciembreC'] == 0 || $row6['DiciembreP'])
			{
				$diferenciaComisionRPor12 = ($comision);
			}
			else
			{
				$diferenciaComisionRPor12 = ($comision-(($row['DiciembreC']/$row6['DiciembreP'])+($row4['DiciembreGAO']/$row6['DiciembreP'])));
			}
			$diferenciaComisionR12 = $row6['DiciembreP']*$diferenciaComisionRPor12;
			$diferenciaComisionRTOTAL = $diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6+$diferenciaComisionR7+$diferenciaComisionR8+$diferenciaComisionR9+$diferenciaComisionR10+$diferenciaComisionR11+$diferenciaComisionR12;
			$comision1 = $diferenciaComisionR1-$row5['Enero'];
			$comision2 = $diferenciaComisionR2-$row5['Febrero'];
			$comision3 = $diferenciaComisionR3-$row5['Marzo'];
			$comision4 = $diferenciaComisionR4-$row5['Abril'];
			$comision5 = $diferenciaComisionR5-$row5['Mayo'];
			$comision6 = $diferenciaComisionR6-$row5['Junio'];
			$comision7 = $diferenciaComisionR7-$row5['Julio'];
			$comision8 = $diferenciaComisionR8-$row5['Agosto'];
			$comision9 = $diferenciaComisionR9-$row5['Septiembre'];
			$comision10 = $diferenciaComisionR10-$row5['Octubre'];
			$comision11 = $diferenciaComisionR11-$row5['Noviembre'];
			$comision12 = $diferenciaComisionR12-$row5['Diciembre'];
			//$comisionTOTAL = $diferenciaComisionRTOTAL-($row5['anticipo']);
			
			
			//MARGEN AJUSTADO
			/*$margenAjustado1R = $row['EneroP'] - $row['EneroC'] - $row['EneroG'] - $row4['EneroGAO'] - $comision1;
			$margenAjustado2R = $row['FebreroP'] - $row['FebreroC'] - $row['FebreroG'] - $row4['FebreroGAO'] - $comision2;
			$margenAjustado3R = $row['MarzoP'] - $row['MarzoC'] - $row['MarzoG'] - $row4['MarzoGAO'] - $comision3;
			$margenAjustado4R = $row['AbrilP'] - $row['AbrilC'] - $row['AbrilG'] - $row4['AbrilGAO'] - $comision4;
			$margenAjustado5R = $row['MayoP'] - $row['MayoC'] - $row['MayoG'] - $row4['MayoGAO'] - $comision5;
			$margenAjustado6R = $row['JunioP'] - $row['JunioC'] - $row['JunioG'] - $row4['JunioGAO'] - $comision6;
			$margenAjustado7R = $row['JulioP'] - $row['JulioC'] - $row['JulioG'] - $row4['JulioGAO'] - $comision7;
			$margenAjustado8R = $row['AgostoP'] - $row['AgostoC'] - $row['AgostoG'] - $row4['AgostoGAO'] - $comision8;
			$margenAjustado9R = $row['SeptiembreP'] - $row['SeptiembreC'] - $row['SeptiembreG'] - $row4['SeptiembreGAO'] - $comision9;
			$margenAjustado10R = $row['OctubreP'] - $row['OctubreC'] - $row['OctubreG'] - $row4['OctubreGAO'] - $comision10;
			$margenAjustado11R = $row['NoviembreP'] - $row['NoviembreC'] - $row['NoviembreG'] - $row4['NoviembreGAO'] - $comision11;
			$margenAjustado12R = $row['DiciembreP'] - $row['DiciembreC'] - $row['DiciembreG'] - $row4['DiciembreGAO'] - $comision12;
			$margenAjustadoTotalR = $row['TOTALP'] - $row['TOTALC'] - $row['TOTALG'] - $row4['TotalGAO'] - $comisionTOTAL;*/
			
			
			//INTERESES
			$intereses1R = $row6['EneroP'] - $row['EneroC'] - $row['EneroG'] - $row4['EneroGAO'] - $comision1;
			$intereses2R = $row6['FebreroP'] - $row['FebreroC'] - $row['FebreroG'] - $row4['FebreroGAO'] - $comision2;
			$intereses3R = $row6['MarzoP'] - $row['MarzoC'] - $row['MarzoG'] - $row4['MarzoGAO'] - $comision3;
			$intereses4R = $row6['AbrilP'] - $row['AbrilC'] - $row['AbrilG'] - $row4['AbrilGAO'] - $comision4;
			$intereses5R = $row6['MayoP'] - $row['MayoC'] - $row['MayoG'] - $row4['MayoGAO'] - $comision5;
			$intereses6R = $row6['JunioP'] - $row['JunioC'] - $row['JunioG'] - $row4['JunioGAO'] - $comision6;
			$intereses7R = $row6['JulioP'] - $row['JulioC'] - $row['JulioG'] - $row4['JulioGAO'] - $comision7;
			$intereses8R = $row6['AgostoP'] - $row['AgostoC'] - $row['AgostoG'] - $row4['AgostoGAO'] - $comision8;
			$intereses9R = $row6['SeptiembreP'] - $row['SeptiembreC'] - $row['SeptiembreG'] - $row4['SeptiembreGAO'] - $comision9;
			$intereses10R = $row6['OctubreP'] - $row['OctubreC'] - $row['OctubreG'] - $row4['OctubreGAO'] - $comision10;
			$intereses11R = $row6['NoviembreP'] - $row['NoviembreC'] - $row['NoviembreG'] - $row4['NoviembreGAO'] - $comision11;
			$intereses12R = $row6['DiciembreP'] - $row['DiciembreC'] - $row['DiciembreG'] - $row4['DiciembreGAO'] - $comision12;
			
			if($intereses1R > 0)
			{
				$intereses1R = 0;
			}
			else
			{
				$intereses1R = $intereses1R * 0.02 *-1;
			}
			if($intereses2R > 0)
			{
				$intereses2R = 0;
			}
			else
			{
				$intereses2R = $intereses2R * 0.02 *-1;
			}
			if($intereses3R > 0)
			{
				$intereses3R = 0;
			}
			else
			{
				$intereses3R = $intereses3R * 0.02 *-1;
			}
			if($intereses4R > 0)
			{
				$intereses4R = 0;
			}
			else
			{
				$intereses4R = $intereses4R * 0.02 *-1;
			}
			if($intereses5R > 0)
			{
				$intereses5R = 0;
			}
			else
			{
				$intereses5R = $intereses5R * 0.02 *-1;
			}
			if($intereses6R > 0)
			{
				$intereses6R = 0;
			}
			else
			{
				$intereses6R = $intereses6R * 0.02 *-1;
			}
			if($intereses7R > 0)
			{
				$intereses7R = 0;
			}
			else
			{
				$intereses7R = $intereses7R * 0.02 *-1;
			}
			if($intereses8R > 0)
			{
				$intereses8R = 0;
			}
			else
			{
				$intereses8R = $intereses8R * 0.02 *-1;
			}
			if($intereses9R > 0)
			{
				$intereses9R = 0;
			}
			else
			{
				$intereses9R = $intereses9R * 0.02 *-1;
			}
			if($intereses10R > 0)
			{
				$intereses10R = 0;
			}
			else
			{
				$intereses10R = $intereses10R * 0.02 *-1;
			}
			if($intereses11R > 0)
			{
				$intereses11R = 0;
			}
			else
			{
				$intereses11R = $intereses11R * 0.02 *-1;
			}
			if($intereses12R > 0)
			{
				$intereses12R = 0;
			}
			else
			{
				$intereses12R = $intereses12R * 0.02 *-1;
			}
			
			?>
				<!--INGRESOS-->
				<tr>
					<th colspan="2">Cobranza</th>
					<td>$<?php print(number_format($row6['EneroP'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row6['FebreroP'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row6['MarzoP'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row6['AbrilP'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row6['MayoP'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row6['JunioP'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row6['JulioP'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row6['AgostoP'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row6['SeptiembreP'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row6['OctubreP'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row6['NoviembreP'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row6['DiciembreP'],2));?></td>
				</tr>
				<tr>
					<td colspan="135"></td>
				</tr>
				<!--COSTOS-->
				<tr>
					<th colspan="2">Costos</th>
					<td>$<?php print(number_format($row['EneroC'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row['FebreroC'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row['MarzoC'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row['AbrilC'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row['MayoC'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row['JunioC'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row['JulioC'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row['AgostoC'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row['SeptiembreC'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row['OctubreC'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row['NoviembreC'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row['DiciembreC'],2));?></td>
				</tr>
			<?php
				if($zona == 7)
				{
			?>
				<!--GASTO DIRECTO-->
				<tr>
					<th colspan="2">Gasto Directo</th>
					<td>$<?php print(number_format($row['EneroG'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row['FebreroG'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row['MarzoG'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row['AbrilG'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row['MayoG'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row['JunioG'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row['JulioG'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row['AgostoG'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row['SeptiembreG'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row['OctubreG'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row['NoviembreG'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row['DiciembreG'],2));?></td>
				</tr>
			<?php
				}
			?>
				<!--GAO FIJO-->
				<tr>
					<th colspan="2">GAO Fijo Total</th>
					<td>$<?php print(number_format($row4['EneroGAO'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row4['FebreroGAO'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row4['MarzoGAO'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row4['AbrilGAO'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row4['MayoGAO'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row4['JunioGAO'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row4['JulioGAO'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row4['AgostoGAO'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row4['SeptiembreGAO'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row4['OctubreGAO'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row4['NoviembreGAO'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row4['DiciembreGAO'],2));?></td>
				</tr>
				<!--GAO VARIABLE--
				<tr>
					<th colspan="2">Comisión</th>
					<td>$<?php/* print(number_format($diferenciaComisionR1,2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComisionR2,2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComisionR3,2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComisionR4,2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComisionR5,2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComisionR6,2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComisionR7,2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComisionR8,2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComisionR9,2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComisionR10,2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComisionR11,2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComisionR12,2));*/?></td>
				</tr>-->
				<!--ANTICIPO-->
				<tr>
					<th colspan="2">GAO Variable</th>
					<td>$<?php print(number_format($row5['Enero'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Febrero'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Marzo'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Abril'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Mayo'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Junio'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Julio'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Agosto'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Septiembre'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Octubre'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Noviembre'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Diciembre'],2)); ?></td>
				</tr>
				<tr>
					<td colspan="135"></td>
				</tr>
				<tr>
					<th colspan="2">Suma</th>
					<td>$<?php print(number_format($row5['Enero']+$row['EneroC']+$row['EneroG']+$row4['EneroGAO'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Febrero']+$row['FebreroC']+$row['FebreroG']+$row4['FebreroGAO'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Marzo']+$row['MarzoC']+$row['MarzoG']+$row4['MarzoGAO'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Abril']+$row['AbrilC']+$row['AbrilG']+$row4['AbrilGAO'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Mayo']+$row['MayoC']+$row['MayoG']+$row4['MayoGAO'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Junio']+$row['JunioC']+$row['JunioG']+$row4['JunioGAO'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Julio']+$row['JulioC']+$row['JulioG']+$row4['JulioGAO'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Agosto']+$row['AgostoC']+$row['AgostoG']+$row4['AgostoGAO'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Septiembre']+$row['SeptiembreC']+$row['SeptiembreG']+$row4['SeptiembreGAO'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Octubre']+$row['OctubreC']+$row['OctubreG']+$row4['OctubreGAO'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Noviembre']+$row['NoviembreC']+$row['NoviembreG']+$row4['NoviembreGAO'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Diciembre']+$row['DiciembreC']+$row['DiciembreG']+$row4['DiciembreGAO'],2)); ?></td>
				</tr>
				<tr>
					<td colspan="135"></td>
				</tr>
				<tr>
					<th colspan="2">Diferencia</th>
					<td>$<?php print(number_format($row6['EneroP']-($row5['Enero']+$row['EneroC']+$row['EneroG']+$row4['EneroGAO']),2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row6['FebreroP']-($row5['Febrero']+$row['FebreroC']+$row['FebreroG']+$row4['FebreroGAO']),2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row6['MarzoP']-($row5['Marzo']+$row['MarzoC']+$row['MarzoG']+$row4['MarzoGAO']),2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row6['AbrilP']-($row5['Abril']+$row['AbrilC']+$row['AbrilG']+$row4['AbrilGAO']),2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row6['MayoP']-($row5['Mayo']+$row['MayoC']+$row['MayoG']+$row4['MayoGAO']),2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row6['JunioP']-($row5['Junio']+$row['JunioC']+$row['JunioG']+$row4['JunioGAO']),2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row6['JulioP']-($row5['Julio']+$row['JulioC']+$row['JulioG']+$row4['JulioGAO']),2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row6['AgostoP']-($row5['Agosto']+$row['AgostoC']+$row['AgostoG']+$row4['AgostoGAO']),2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row6['SeptiembreP']-($row5['Septiembre']+$row['SeptiembreC']+$row['SeptiembreG']+$row4['SeptiembreGAO']),2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row6['OctubreP']-($row5['Octubre']+$row['OctubreC']+$row['OctubreG']+$row4['OctubreGAO']),2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row6['NoviembreP']-($row5['Noviembre']+$row['NoviembreC']+$row['NoviembreG']+$row4['NoviembreGAO']),2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row6['DiciembreP']-($row5['Diciembre']+$row['DiciembreC']+$row['DiciembreG']+$row4['DiciembreGAO']),2)); ?></td>
				</tr>
				<tr>
					<td colspan="135"></td>
				</tr>
				<!--INTERESES-->
				<tr>
					<th colspan="2">Intereses Totales</th>
					<td>$0.00</td>
					<td></td>
					<td>$0.00</td>
					<td></td>
					<td>$0.00</td>
					<td></td>
					<td>$<?php print(number_format($intereses4R,2));?></td>
					<td></td>
					<td>$<?php print(number_format($intereses5R,2));?></td>
					<td></td>
					<td>$<?php print(number_format($intereses6R,2));?></td>
					<td></td>
					<td>$<?php print(number_format($intereses7R,2));?></td>
					<td></td>
					<td>$<?php print(number_format($intereses8R,2));?></td>
					<td></td>
					<td>$<?php print(number_format($intereses9R,2));?></td>
					<td></td>
					<td>$<?php print(number_format($intereses10R,2));?></td>
					<td></td>
					<td>$<?php print(number_format($intereses11R,2));?></td>
					<td></td>
					<td>$<?php print(number_format($intereses12R,2));?></td>
				</tr>
			<?php
		}
		else
		{
			?>
            <tr>
				<td colspan="28"></td>
            </tr>
            <?php
		}
	}
	
	//AVEFO//
	public function estacionariedadAVEFOAnual($zona,$year)
	{
		//BITACORA
		$query = "SELECT 
					IF(DB.id_mesejecucion=1,SUM(DB.precio_bitacora),0) AS EneroP,
					IF(DB.id_mesejecucion=2,SUM(DB.precio_bitacora),0) AS FebreroP,
					IF(DB.id_mesejecucion=3,SUM(DB.precio_bitacora),0) AS MarzoP,
					IF(DB.id_mesejecucion=4,SUM(DB.precio_bitacora),0) AS AbrilP,
					IF(DB.id_mesejecucion=5,SUM(DB.precio_bitacora),0) AS MayoP,
					IF(DB.id_mesejecucion=6,SUM(DB.precio_bitacora),0) AS JunioP,
					IF(DB.id_mesejecucion=7,SUM(DB.precio_bitacora),0) AS JulioP,
					IF(DB.id_mesejecucion=8,SUM(DB.precio_bitacora),0) AS AgostoP,
					IF(DB.id_mesejecucion=9,SUM(DB.precio_bitacora),0) AS SeptiembreP,
					IF(DB.id_mesejecucion=10,SUM(DB.precio_bitacora),0) AS OctubreP,
					IF(DB.id_mesejecucion=11,SUM(DB.precio_bitacora),0) AS NoviembreP,
					IF(DB.id_mesejecucion=12,SUM(DB.precio_bitacora),0) AS DiciembreP,
					SUM(DB.precio_bitacora) AS TOTALP,
					IF(DB.id_mesejecucion=1,SUM(DB.costo_bitacora),0) AS EneroC,
					IF(DB.id_mesejecucion=2,SUM(DB.costo_bitacora),0) AS FebreroC,
					IF(DB.id_mesejecucion=3,SUM(DB.costo_bitacora),0) AS MarzoC,
					IF(DB.id_mesejecucion=4,SUM(DB.costo_bitacora),0) AS AbrilC,
					IF(DB.id_mesejecucion=5,SUM(DB.costo_bitacora),0) AS MayoC,
					IF(DB.id_mesejecucion=6,SUM(DB.costo_bitacora),0) AS JunioC,
					IF(DB.id_mesejecucion=7,SUM(DB.costo_bitacora),0) AS JulioC,
					IF(DB.id_mesejecucion=8,SUM(DB.costo_bitacora),0) AS AgostoC,
					IF(DB.id_mesejecucion=9,SUM(DB.costo_bitacora),0) AS SeptiembreC,
					IF(DB.id_mesejecucion=10,SUM(DB.costo_bitacora),0) AS OctubreC,
					IF(DB.id_mesejecucion=11,SUM(DB.costo_bitacora),0) AS NoviembreC,
					IF(DB.id_mesejecucion=12,SUM(DB.costo_bitacora),0) AS DiciembreC,
					SUM(DB.costo_bitacora) AS TOTALC,
					IF(DB.id_mesejecucion=1,SUM(DB.precio_bitacora*DB.gasto_directo),0) AS EneroG,
					IF(DB.id_mesejecucion=2,SUM(DB.precio_bitacora*DB.gasto_directo),0) AS FebreroG,
					IF(DB.id_mesejecucion=3,SUM(DB.precio_bitacora*DB.gasto_directo),0) AS MarzoG,
					IF(DB.id_mesejecucion=4,SUM(DB.precio_bitacora*DB.gasto_directo),0) AS AbrilG,
					IF(DB.id_mesejecucion=5,SUM(DB.precio_bitacora*DB.gasto_directo),0) AS MayoG,
					IF(DB.id_mesejecucion=6,SUM(DB.precio_bitacora*DB.gasto_directo),0) AS JunioG,
					IF(DB.id_mesejecucion=7,SUM(DB.precio_bitacora*DB.gasto_directo),0) AS JulioG,
					IF(DB.id_mesejecucion=8,SUM(DB.precio_bitacora*DB.gasto_directo),0) AS AgostoG,
					IF(DB.id_mesejecucion=9,SUM(DB.precio_bitacora*DB.gasto_directo),0) AS SeptiembreG,
					IF(DB.id_mesejecucion=10,SUM(DB.precio_bitacora*DB.gasto_directo),0) AS OctubreG,
					IF(DB.id_mesejecucion=11,SUM(DB.precio_bitacora*DB.gasto_directo),0) AS NoviembreG,
					IF(DB.id_mesejecucion=12,SUM(DB.precio_bitacora*DB.gasto_directo),0) AS DiciembreG,
					SUM(DB.precio_bitacora*DB.gasto_directo) AS TOTALG
				FROM T_Clientes AS C
				LEFT OUTER JOIN T_Bitacora AS B
				ON B.id_cliente = C.id_cliente
				LEFT OUTER JOIN T_Detalle_Bitacora AS DB
				ON DB.id_bitacora = B.id_bitacora
				LEFT OUTER JOIN T_Subproductos AS S
				ON S.id_subproducto = DB.id_subproducto
				WHERE C.id_zona = :zona AND YEAR(B.fecha_audit) = :year AND DB.id_estatusejecucion=1
				GROUP BY DB.id_mesejecucion";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(":zona",$zona);
		$stmt->bindparam(":year",$year);
		$stmt->execute();
		//ESTACIONARIEDAD ESCENARIO 2
		$query = "SELECT 
				SUM(anticipo),
				SUM(mes1_Ej) AS Enero,
				SUM(mes2_Ej) AS Febrero,
				SUM(mes3_Ej) AS Marzo,
				SUM(mes4_Ej) AS Abril,
				SUM(mes5_Ej) AS Mayo,
				SUM(mes6_Ej) AS Junio,
				SUM(mes7_Ej) AS Julio,
				SUM(mes8_Ej) AS Agosto,
				SUM(mes9_Ej) AS Septiembre,
				SUM(mes10_Ej) AS Octubre,
				SUM(mes11_Ej) AS Noviembre,
				SUM(mes12_Ej) AS Diciembre,
				SUM(mes1_Ej+mes2_Ej+mes3_Ej+mes4_Ej+mes5_Ej+mes6_Ej+mes7_Ej+mes8_Ej+mes9_Ej+mes10_Ej+mes11_Ej+mes12_Ej) AS TOTAL,
				SUM(mes1_Ej*costo_ac) AS EneroC,
				SUM(mes2_Ej*costo_ac) AS FebreroC,
				SUM(mes3_Ej*costo_ac) AS MarzoC,
				SUM(mes4_Ej*costo_ac) AS AbrilC,
				SUM(mes5_Ej*costo_ac) AS MayoC,
				SUM(mes6_Ej*costo_ac) AS JunioC,
				SUM(mes7_Ej*costo_ac) AS JulioC,
				SUM(mes8_Ej*costo_ac) AS AgostoC,
				SUM(mes9_Ej*costo_ac) AS SeptiembreC,
				SUM(mes10_Ej*costo_ac) AS OctubreC,
				SUM(mes11_Ej*costo_ac) AS NoviembreC,
				SUM(mes12_Ej*costo_ac) AS DiciembreC,
				SUM(cuotadef_ac*costo_ac) AS TOTALC,
				(costo_ac) AS costo,
				SUM(mes1_Ej*gasto_ac) AS EneroG,
				SUM(mes2_Ej*gasto_ac) AS FebreroG,
				SUM(mes3_Ej*gasto_ac) AS MarzoG,
				SUM(mes4_Ej*gasto_ac) AS AbrilG,
				SUM(mes5_Ej*gasto_ac) AS MayoG,
				SUM(mes6_Ej*gasto_ac) AS JunioG,
				SUM(mes7_Ej*gasto_ac) AS JulioG,
				SUM(mes8_Ej*gasto_ac) AS AgostoG,
				SUM(mes9_Ej*gasto_ac) AS SeptiembreG,
				SUM(mes10_Ej*gasto_ac) AS OctubreG,
				SUM(mes11_Ej*gasto_ac) AS NoviembreG,
				SUM(mes12_Ej*gasto_ac) AS DiciembreG,
				(gasto_ac) AS gasto,
				SUM(cuotadef_ac*gasto_ac) AS TOTALG
			FROM `T_Detalle_AnalisisCuantitativo_Escenario2` AS DAC
			INNER JOIN `T_AnalisisCuantitativo_Escenario2` AS AC
		    ON DAC.id_analisiscuantitativo = AC.id_analisiscuantitativo
			INNER JOIN T_Clientes AS C 
		    ON C.id_cliente = AC.id_cliente 
		    WHERE C.id_zona = :zona AND AC.year_acuant = :year";
		$stmt2 = $this->conn->prepare($query);
		$stmt2->bindparam(":zona",$zona);
		$stmt2->bindparam(":year",$year);
		$stmt2->execute();
		//QUERY GAO PROYECTADO
		$query ="SELECT 
				  N.year_nomina,
				  (SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.intereses)) AS TotalGAO,
				  (SUM(N.monto)) AS TotalNomina,
				  (SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)) AS TotalSAC,
				  0 AS TotalIntereses,
				  0 AS TotalOtros
				  FROM T_Nomina_GAO_Escenario2 AS N
				  INNER JOIN T_Plantilla AS P
				  ON N.id_plantilla = P.id_plantilla
				  INNER JOIN T_PlantillaDatos as PD
				  ON PD.id_plantilladatos = P.id_plantilladatos
				  WHERE PD.id_zona=:zona AND year_nomina = :year";
		$stmt3 = $this->conn->prepare($query);
		$stmt3->bindparam(":zona",$zona);
		$stmt3->bindparam(":year",$year);
		$stmt3->execute();
		//QUERY GAO REAL
		$query ="SELECT 
				  IF(N.id_mes=1,(SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.intereses)+SUM(otros)),0) AS EneroGAO,
				  IF(N.id_mes=2,(SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.intereses)+SUM(otros)),0) AS FebreroGAO,
				  IF(N.id_mes=3,(SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.intereses)+SUM(otros)),0) AS MarzoGAO,
				  IF(N.id_mes=4,(SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.intereses)+SUM(otros)),0) AS AbrilGAO,
				  IF(N.id_mes=5,(SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.intereses)+SUM(otros)),0) AS MayoGAO,
				  IF(N.id_mes=6,(SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.intereses)+SUM(otros)),0) AS JunioGAO,
				  IF(N.id_mes=7,(SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.intereses)+SUM(otros)),0) AS JulioGAO,
				  IF(N.id_mes=8,(SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.intereses)+SUM(otros)),0) AS AgostoGAO,
				  IF(N.id_mes=9,(SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.intereses)+SUM(otros)),0) AS SeptiembreGAO,
				  IF(N.id_mes=10,(SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.intereses)+SUM(otros)),0) AS OctubreGAO,
				  IF(N.id_mes=11,(SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.intereses)+SUM(otros)),0) AS NoviembreGAO,
				  IF(N.id_mes=12,(SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.intereses)+SUM(otros)),0) AS DiciembreGAO,
				  (SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.intereses)+SUM(otros)) AS TotalGAO,
				  IF(N.id_mes=1,(SUM(N.monto)),0) AS EneroNomina,
				  IF(N.id_mes=2,(SUM(N.monto)),0) AS FebreroNomina,
				  IF(N.id_mes=3,(SUM(N.monto)),0) AS MarzoNomina,
				  IF(N.id_mes=4,(SUM(N.monto)),0) AS AbrilNomina,
				  IF(N.id_mes=5,(SUM(N.monto)),0) AS MayoNomina,
				  IF(N.id_mes=6,(SUM(N.monto)),0) AS JunioNomina,
				  IF(N.id_mes=7,(SUM(N.monto)),0) AS JulioNomina,
				  IF(N.id_mes=8,(SUM(N.monto)),0) AS AgostoNomina,
				  IF(N.id_mes=9,(SUM(N.monto)),0) AS SeptiembreNomina,
				  IF(N.id_mes=10,(SUM(N.monto)),0) AS OctubreNomina,
				  IF(N.id_mes=11,(SUM(N.monto)),0) AS NoviembreNomina,
				  IF(N.id_mes=12,(SUM(N.monto)),0) AS DiciembreNomina,
				  (SUM(N.monto)) AS TotalNomina,
				  IF(N.id_mes=1,(SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)),0) AS EneroSAC,
				  IF(N.id_mes=2,(SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)),0) AS FebreroSAC,
				  IF(N.id_mes=3,(SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)),0) AS MarzoSAC,
				  IF(N.id_mes=4,(SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)),0) AS AbrilSAC,
				  IF(N.id_mes=5,(SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)),0) AS MayoSAC,
				  IF(N.id_mes=6,(SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)),0) AS JunioSAC,
				  IF(N.id_mes=7,(SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)),0) AS JulioSAC,
				  IF(N.id_mes=8,(SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)),0) AS AgostoSAC,
				  IF(N.id_mes=9,(SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)),0) AS SeptiembreSAC,
				  IF(N.id_mes=10,(SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)),0) AS OctubreSAC,
				  IF(N.id_mes=11,(SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)),0) AS NoviembreSAC,
				  IF(N.id_mes=12,(SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)),0) AS DiciembreSAC,
				  (SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)) AS TotalSAC,
				  IF(N.id_mes=1,(SUM(otros)),0) AS EneroOtros,
				  IF(N.id_mes=2,(SUM(otros)),0) AS FebreroOtros,
				  IF(N.id_mes=3,(SUM(otros)),0) AS MarzoOtros,
				  IF(N.id_mes=4,(SUM(otros)),0) AS AbrilOtros,
				  IF(N.id_mes=5,(SUM(otros)),0) AS MayoOtros,
				  IF(N.id_mes=6,(SUM(otros)),0) AS JunioOtros,
				  IF(N.id_mes=7,(SUM(otros)),0) AS JulioOtros,
				  IF(N.id_mes=8,(SUM(otros)),0) AS AgostoOtros,
				  IF(N.id_mes=9,(SUM(otros)),0) AS SeptiembreOtros,
				  IF(N.id_mes=10,(SUM(otros)),0) AS OctubreOtros,
				  IF(N.id_mes=11,(SUM(otros)),0) AS NoviembreOtros,
				  IF(N.id_mes=12,(SUM(otros)),0) AS DiciembreOtros,
				  (SUM(otros)) AS TotalOtros,
				  IF(N.id_mes=1,(SUM(N.intereses)),0) AS EneroIntereses,
				  IF(N.id_mes=2,(SUM(N.intereses)),0) AS FebreroIntereses,
				  IF(N.id_mes=3,(SUM(N.intereses)),0) AS MarzoIntereses,
				  IF(N.id_mes=4,(SUM(N.intereses)),0) AS AbrilIntereses,
				  IF(N.id_mes=5,(SUM(N.intereses)),0) AS MayoIntereses,
				  IF(N.id_mes=6,(SUM(N.intereses)),0) AS JunioIntereses,
				  IF(N.id_mes=7,(SUM(N.intereses)),0) AS JulioIntereses,
				  IF(N.id_mes=8,(SUM(N.intereses)),0) AS AgostoIntereses,
				  IF(N.id_mes=9,(SUM(N.intereses)),0) AS SeptiembreIntereses,
				  IF(N.id_mes=10,(SUM(N.intereses)),0) AS OctubreIntereses,
				  IF(N.id_mes=11,(SUM(N.intereses)),0) AS NoviembreIntereses,
				  IF(N.id_mes=12,(SUM(N.intereses)),0) AS DiciembreIntereses,
				  (SUM(N.intereses)) AS TotalIntereses
				  FROM T_Nomina_GAO_Real AS N
				  INNER JOIN T_Plantilla AS P
				  ON N.id_plantilla = P.id_plantilla
				  INNER JOIN T_PlantillaDatos as PD
				  ON PD.id_plantilladatos = P.id_plantilladatos
				  WHERE PD.id_zona=:zona AND N.year_nomina = :year
				  GROUP BY id_mes";
		$stmt4 = $this->conn->prepare($query);
		$stmt4->bindparam(":zona",$zona);
		$stmt4->bindparam(":year",$year);
		$stmt4->execute();
		//QUERY ANTICIPOS
		$query ="SELECT 
					(CASE WHEN id_mes=1 THEN SUM(anticipo) ELSE 0 END) AS Enero,
					(CASE WHEN id_mes=2 THEN SUM(anticipo) ELSE 0 END) AS Febrero,
					(CASE WHEN id_mes=3 THEN SUM(anticipo) ELSE 0 END) AS Marzo,
					(CASE WHEN id_mes=4 THEN SUM(anticipo) ELSE 0 END) AS Abril,
					(CASE WHEN id_mes=5 THEN SUM(anticipo) ELSE 0 END) AS Mayo,
					(CASE WHEN id_mes=6 THEN SUM(anticipo) ELSE 0 END) AS Junio,
					(CASE WHEN id_mes=7 THEN SUM(anticipo) ELSE 0 END) AS Julio,
					(CASE WHEN id_mes=8 THEN SUM(anticipo) ELSE 0 END) AS Agosto,
					(CASE WHEN id_mes=9 THEN SUM(anticipo) ELSE 0 END) AS Septiembre,
					(CASE WHEN id_mes=10 THEN SUM(anticipo) ELSE 0 END) AS Octubre,
					(CASE WHEN id_mes=11 THEN SUM(anticipo) ELSE 0 END) AS Noviembre,
					(CASE WHEN id_mes=12 THEN SUM(anticipo) ELSE 0 END) AS Diciembre,
					SUM(anticipo) AS TotalAnticipo,
					oferta
				  FROM T_AnticiposLider
				  WHERE id_zona=:zona AND year_anticipo = :year
				  GROUP BY id_mes";
		$stmt5 = $this->conn->prepare($query);
		$stmt5->bindparam(":zona",$zona);
		$stmt5->bindparam(":year",$year);
		$stmt5->execute();
		//QUERY INTERESES
		$query = "SELECT 
					IF(DB.id_mescobranza=1,SUM(DB.precio_bitacora),0) AS Enero,
					IF(DB.id_mescobranza=2,SUM(DB.precio_bitacora),0) AS Febrero,
					IF(DB.id_mescobranza=3,SUM(DB.precio_bitacora),0) AS Marzo,
					IF(DB.id_mescobranza=4,SUM(DB.precio_bitacora),0) AS Abril,
					IF(DB.id_mescobranza=5,SUM(DB.precio_bitacora),0) AS Mayo,
					IF(DB.id_mescobranza=6,SUM(DB.precio_bitacora),0) AS Junio,
					IF(DB.id_mescobranza=7,SUM(DB.precio_bitacora),0) AS Julio,
					IF(DB.id_mescobranza=8,SUM(DB.precio_bitacora),0) AS Agosto,
					IF(DB.id_mescobranza=9,SUM(DB.precio_bitacora),0) AS Septiembre,
					IF(DB.id_mescobranza=10,SUM(DB.precio_bitacora),0) AS Octubre,
					IF(DB.id_mescobranza=11,SUM(DB.precio_bitacora),0) AS Noviembre,
					IF(DB.id_mescobranza=12,SUM(DB.precio_bitacora),0) AS Diciembre
				FROM T_Clientes AS C
				LEFT OUTER JOIN T_Bitacora AS B
				ON B.id_cliente = C.id_cliente
				LEFT OUTER JOIN T_Detalle_Bitacora AS DB
				ON DB.id_bitacora = B.id_bitacora
				LEFT OUTER JOIN T_Subproductos AS S
				ON S.id_subproducto = DB.id_subproducto
				WHERE C.id_zona = :zona AND YEAR(B.fecha_audit) = :year AND DB.id_estatuscobranza=1
				GROUP BY id_mescobranza";
		$stmt6 = $this->conn->prepare($query);
		$stmt6->bindparam(":zona",$zona);
		$stmt6->bindparam(":year",$year);
		$stmt6->execute();
		
		
		if($stmt2->rowCount()>0)
		{
			$enero = 0;
			$eneroC = 0;
			$eneroG = 0;
			$febrero = 0;
			$febreroC = 0;
			$febreroG = 0;
			$marzo = 0;
			$marzoC = 0;
			$marzoG = 0;
			$abril = 0;
			$abrilC = 0;
			$abrilG = 0;
			$mayo = 0;
			$mayoC = 0;
			$mayoG = 0;
			$junio = 0;
			$junioC = 0;
			$junioG = 0;
			$julio = 0;
			$julioC = 0;
			$julioG = 0;
			$agosto = 0;
			$agostoC = 0;
			$agostoG = 0;
			$septiembre = 0;
			$septiembreC = 0;
			$septiembreG = 0;
			$octubre = 0;
			$octubreC = 0;
			$octubreG = 0;
			$noviembre = 0;
			$noviembreC = 0;
			$noviembreG = 0;
			$diciembre = 0;
			$diciembreC = 0;
			$diciembreG = 0;
			$TOTALP = 0;
			$TOTALC = 0;
			$TOTALG = 0;
			
			$eneroA = 0;
			$febreroA = 0;
			$marzoA = 0;
			$abrilA = 0;
			$mayoA = 0;
			$junioA = 0;
			$julioA = 0;
			$agostoA = 0;
			$septiembreA = 0;
			$octubreA = 0;
			$noviembreA = 0;
			$diciembreA = 0;
			$TOTALA = 0;
			
			$eneroGAO = 0;
			$febreroGAO = 0;
			$marzoGAO = 0;
			$abrilGAO = 0;
			$mayoGAO = 0;
			$junioGAO = 0;
			$julioGAO = 0;
			$agostoGAO = 0;
			$septiembreGAO = 0;
			$octubreGAO = 0;
			$noviembreGAO = 0;
			$diciembreGAO = 0;
			$TOTALGAO = 0;
			
			$eneroNomina = 0;
			$febreroNomina = 0;
			$marzoNomina = 0;
			$abrilNomina = 0;
			$mayoNomina = 0;
			$junioNomina = 0;
			$julioNomina = 0;
			$agostoNomina = 0;
			$septiembreNomina = 0;
			$octubreNomina = 0;
			$noviembreNomina = 0;
			$diciembreNomina = 0;
			$TOTALNomina = 0;
			
			$eneroSAC = 0;
			$febreroSAC = 0;
			$marzoSAC = 0;
			$abrilSAC = 0;
			$mayoSAC = 0;
			$junioSAC = 0;
			$julioSAC = 0;
			$agostoSAC = 0;
			$septiembreSAC = 0;
			$octubreSAC = 0;
			$noviembreSAC = 0;
			$diciembreSAC = 0;
			$TOTALSAC = 0;
			
			$eneroOtros = 0;
			$febreroOtros = 0;
			$marzoOtros = 0;
			$abrilOtros = 0;
			$mayoOtros = 0;
			$junioOtros = 0;
			$julioOtros = 0;
			$agostoOtros = 0;
			$septiembreOtros = 0;
			$octubreOtros = 0;
			$noviembreOtros = 0;
			$diciembreOtros = 0;
			$TOTALOtros = 0;
			
			$eneroIntereses = 0;
			$febreroIntereses = 0;
			$marzoIntereses = 0;
			$abrilIntereses = 0;
			$mayoIntereses = 0;
			$junioIntereses = 0;
			$julioIntereses = 0;
			$agostoIntereses = 0;
			$septiembreIntereses = 0;
			$octubreIntereses = 0;
			$noviembreIntereses = 0;
			$diciembreIntereses = 0;
			$TOTALIntereses = 0;
			
			$eneroCobranza = 0;
			$febreroCobranza = 0;
			$marzoCobranza = 0;
			$abrilCobranza = 0;
			$mayoCobranza = 0;
			$junioCobranza = 0;
			$julioCobranza = 0;
			$agostoCobranza = 0;
			$septiembreCobranza = 0;
			$octubreCobranza = 0;
			$noviembreCobranza = 0;
			$diciembreCobranza = 0;
			$TOTALCobranza = 0;
			
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				$enero = $enero + $row['EneroP'];
				$febrero = $febrero + $row['FebreroP'];
				$marzo = $marzo + $row['MarzoP'];
				$abril = $abril + $row['AbrilP'];
				$mayo = $mayo + $row['MayoP'];
				$junio = $junio + $row['JunioP'];
				$julio = $julio + $row['JulioP'];
				$agosto = $agosto + $row['AgostoP'];
				$septiembre = $septiembre + $row['SeptiembreP'];
				$octubre = $octubre + $row['OctubreP'];
				$noviembre = $noviembre + $row['NoviembreP'];
				$diciembre = $diciembre + $row['DiciembreP'];
				
				$eneroHonorarios = $eneroHonorarios + $row['EneroHonorarios'];
				$febreroHonorarios = $febreroHonorarios + $row['FebreroHonorarios'];
				$marzoHonorarios = $marzoHonorarios + $row['MarzoHonorarios'];
				$abrilHonorarios = $abrilHonorarios + $row['AbrilHonorarios'];
				$mayoHonorarios = $mayoHonorarios + $row['MayoHonorarios'];
				$junioHonorarios = $junioHonorarios + $row['JunioHonorarios'];
				$julioHonorarios = $julioHonorarios + $row['JulioHonorarios'];
				$agostoHonorarios = $agostoHonorarios + $row['AgostoHonorarios'];
				$septiembreHonorarios = $septiembreHonorarios + $row['SeptiembreHonorarios'];
				$octubreHonorarios = $octubreHonorarios + $row['OctubreHonorarios'];
				$noviembreHonorarios = $noviembreHonorarios + $row['NoviembreHonorarios'];
				$diciembreHonorarios = $diciembreHonorarios + $row['DiciembreHonorarios'];
				
				$eneroMateriales = $eneroMateriales + $row['EneroMateriales'];
				$febreroMateriales = $febreroMateriales + $row['FebreroMateriales'];
				$marzoMateriales = $marzoMateriales + $row['MarzoMateriales'];
				$abrilMateriales = $abrilMateriales + $row['AbrilMateriales'];
				$mayoMateriales = $mayoMateriales + $row['MayoMateriales'];
				$junioMateriales = $junioMateriales + $row['JunioMateriales'];
				$julioMateriales = $julioMateriales + $row['JulioMateriales'];
				$agostoMateriales = $agostoMateriales + $row['AgostoMateriales'];
				$septiembreMateriales = $septiembreMateriales + $row['SeptiembreMateriales'];
				$octubreMateriales = $octubreMateriales + $row['OctubreMateriales'];
				$noviembreMateriales = $noviembreMateriales + $row['NoviembreMateriales'];
				$diciembreMateriales = $diciembreMateriales + $row['DiciembreMateriales'];
				
				$eneroC = $eneroC + $row['EneroC'];
				$febreroC = $febreroC + $row['FebreroC'];
				$marzoC = $marzoC + $row['MarzoC'];
				$abrilC = $abrilC + $row['AbrilC'];
				$mayoC = $mayoC + $row['MayoC'];
				$junioC = $junioC + $row['JunioC'];
				$julioC = $julioC + $row['JulioC'];
				$agostoC = $agostoC + $row['AgostoC'];
				$septiembreC = $septiembreC + $row['SeptiembreC'];
				$octubreC = $octubreC + $row['OctubreC'];
				$noviembreC = $noviembreC + $row['NoviembreC'];
				$diciembreC = $diciembreC + $row['DiciembreC'];
				
				$eneroG = $eneroG + $row['EneroG'];
				$febreroG = $febreroG + $row['FebreroG'];
				$marzoG = $marzoG + $row['MarzoG'];
				$abrilG = $abrilG + $row['AbrilG'];
				$mayoG = $mayoG + $row['MayoG'];
				$junioG = $junioG + $row['JunioG'];
				$julioG = $julioG + $row['JulioG'];
				$agostoG = $agostoG + $row['AgostoG'];
				$septiembreG = $septiembreG + $row['SeptiembreG'];
				$octubreG = $octubreG + $row['OctubreG'];
				$noviembreG = $noviembreG + $row['NoviembreG'];
				$diciembreG = $diciembreG + $row['DiciembreG'];
				
				$TOTALP = $TOTALP + $row['TOTALP'];
				$TOTALC = $TOTALC + $row['TOTALC'];
				$TOTALG = $TOTALG + $row['TOTALG'];
			}
			while($row5=$stmt5->fetch(PDO::FETCH_ASSOC))
			{
				$eneroA = $eneroA + $row5['Enero'];
				$febreroA = $febreroA + $row5['Febrero'];
				$marzoA = $marzoA + $row5['Marzo'];
				$abrilA = $abrilA + $row5['Abril'];
				$mayoA = $mayoA + $row5['Mayo'];
				$junioA = $junioA + $row5['Junio'];
				$julioA = $julioA + $row5['Julio'];
				$agostoA = $agostoA + $row5['Agosto'];
				$septiembreA = $septiembreA + $row5['Septiembre'];
				$octubreA = $octubreA + $row5['Octubre'];
				$noviembreA = $noviembreA + $row5['Noviembre'];
				$diciembreA = $diciembreA + $row5['Diciembre'];
				$TOTALA = $TOTALA + $row5['TotalAnticipo'];
				$oferta = $row5['oferta'];
			}
			while($row4=$stmt4->fetch(PDO::FETCH_ASSOC))
			{
				$eneroGAO = $eneroGAO + $row4['EneroGAO'];
				$febreroGAO = $febreroGAO + $row4['FebreroGAO'];
				$marzoGAO = $marzoGAO + $row4['MarzoGAO'];
				$abrilGAO = $abrilGAO + $row4['AbrilGAO'];
				$mayoGAO = $mayoGAO + $row4['MayoGAO'];
				$junioGAO = $junioGAO + $row4['JunioGAO'];
				$julioGAO = $julioGAO + $row4['JulioGAO'];
				$agostoGAO = $agostoGAO + $row4['AgostoGAO'];
				$septiembreGAO = $septiembreGAO + $row4['SeptiembreGAO'];
				$octubreGAO = $octubreGAO + $row4['OctubreGAO'];
				$noviembreGAO = $noviembreGAO + $row4['NoviembreGAO'];
				$diciembreGAO = $diciembreGAO + $row4['DiciembreGAO'];
				$TOTALGAO = $TOTALGAO + $row4['TotalGAO'];
				
				$eneroNomina = $eneroNomina + $row4['EneroNomina'];
				$febreroNomina = $febreroNomina + $row4['FebreroNomina'];
				$marzoNomina = $marzoNomina + $row4['MarzoNomina'];
				$abrilNomina = $abrilNomina + $row4['AbrilNomina'];
				$mayoNomina = $mayoNomina + $row4['MayoNomina'];
				$junioNomina = $junioNomina + $row4['JunioNomina'];
				$julioNomina = $julioNomina + $row4['JulioNomina'];
				$agostoNomina = $agostoNomina + $row4['AgostoNomina'];
				$septiembreNomina = $septiembreNomina + $row4['SeptiembreNomina'];
				$octubreNomina = $octubreNomina + $row4['OctubreNomina'];
				$noviembreNomina = $noviembreNomina + $row4['NoviembreNomina'];
				$diciembreNomina = $diciembreNomina + $row4['DiciembreNomina'];
				$TOTALNomina = $TOTALNomina + $row4['TotalNomina'];
				
				$eneroSAC = $eneroSAC + $row4['EneroSAC'];
				$febreroSAC = $febreroSAC + $row4['FebreroSAC'];
				$marzoSAC = $marzoSAC + $row4['MarzoSAC'];
				$abrilSAC = $abrilSAC + $row4['AbrilSAC'];
				$mayoSAC = $mayoSAC + $row4['MayoSAC'];
				$junioSAC = $junioSAC + $row4['JunioSAC'];
				$julioSAC = $julioSAC + $row4['JulioSAC'];
				$agostoSAC = $agostoSAC + $row4['AgostoSAC'];
				$septiembreSAC = $septiembreSAC + $row4['SeptiembreSAC'];
				$octubreSAC = $octubreSAC + $row4['OctubreSAC'];
				$noviembreSAC = $noviembreSAC + $row4['NoviembreSAC'];
				$diciembreSAC = $diciembreSAC + $row4['DiciembreSAC'];
				$TOTALSAC = $TOTALSAC + $row4['TotalSAC'];
				
				$eneroOtros = $eneroOtros + $row4['EneroOtros'];
				$febreroOtros = $febreroOtros + $row4['FebreroOtros'];
				$marzoOtros = $marzoOtros + $row4['MarzoOtros'];
				$abrilOtros = $abrilOtros + $row4['AbrilOtros'];
				$mayoOtros = $mayoOtros + $row4['MayoOtros'];
				$junioOtros = $junioOtros + $row4['JunioOtros'];
				$julioOtros = $julioOtros + $row4['JulioOtros'];
				$agostoOtros = $agostoOtros + $row4['AgostoOtros'];
				$septiembreOtros = $septiembreOtros + $row4['SeptiembreOtros'];
				$octubreOtros = $octubreOtros + $row4['OctubreOtros'];
				$noviembreOtros = $noviembreOtros + $row4['NoviembreOtros'];
				$diciembreOtros = $diciembreOtros + $row4['DiciembreOtros'];
				$TOTALOtros = $TOTALOtros + $row4['TotalOtros'];
				
				$eneroIntereses = $eneroIntereses + $row4['EneroIntereses'];
				$febreroIntereses = $febreroIntereses + $row4['FebreroIntereses'];
				$marzoIntereses = $marzoIntereses + $row4['MarzoIntereses'];
				$abrilIntereses = $abrilIntereses + $row4['AbrilIntereses'];
				$mayoIntereses = $mayoIntereses + $row4['MayoIntereses'];
				$junioIntereses = $junioIntereses + $row4['JunioIntereses'];
				$julioIntereses = $julioIntereses + $row4['JulioIntereses'];
				$agostoIntereses = $agostoIntereses + $row4['AgostoIntereses'];
				$septiembreIntereses = $septiembreIntereses + $row4['SeptiembreIntereses'];
				$octubreIntereses = $octubreIntereses + $row4['OctubreIntereses'];
				$noviembreIntereses = $noviembreIntereses + $row4['NoviembreIntereses'];
				$diciembreIntereses = $diciembreIntereses + $row4['DiciembreIntereses'];
				$TOTALIntereses = $TOTALIntereses + $row4['TotalIntereses'];
			}
			while($row6=$stmt6->fetch(PDO::FETCH_ASSOC))
			{
				$eneroCobranza = $eneroCobranza + $row6['Enero'];
				$febreroCobranza = $febreroCobranza + $row6['Febrero'];
				$marzoCobranza = $marzoCobranza + $row6['Marzo'];
				$abrilCobranza = $abrilCobranza + $row6['Abril'];
				$mayoCobranza = $mayoCobranza + $row6['Mayo'];
				$junioCobranza = $junioCobranza + $row6['Junio'];
				$julioCobranza = $julioCobranza + $row6['Julio'];
				$agostoCobranza = $agostoCobranza + $row6['Agosto'];
				$septiembreCobranza = $septiembreCobranza + $row6['Septiembre'];
				$octubreCobranza = $octubreCobranza + $row6['Octubre'];
				$noviembreCobranza = $noviembreCobranza + $row6['Noviembre'];
				$diciembreCobranza = $diciembreCobranza + $row6['Diciembre'];
			}
			$row5['Enero'] = $eneroA;
			$row5['Febrero'] = $febreroA;
			$row5['Marzo'] = $marzoA;
			$row5['Abril'] = $abrilA;
			$row5['Mayo'] = $mayoA;
			$row5['Junio'] = $junioA;
			$row5['Julio'] = $julioA;
			$row5['Agosto'] = $agostoA;
			$row5['Septiembre'] = $septiembreA;
			$row5['Octubre'] = $octubreA;
			$row5['Noviembre'] = $noviembreA;
			$row5['Diciembre'] = $diciembreA;
			$row5['oferta'] = $oferta;
			
			$row['EneroP'] = $enero;
			$row['FebreroP'] = $febrero;
			$row['MarzoP'] = $marzo;
			$row['AbrilP'] = $abril;
			$row['MayoP'] = $mayo;
			$row['JunioP'] = $junio;
			$row['JulioP'] = $julio;
			$row['AgostoP'] = $agosto;
			$row['SeptiembreP'] = $septiembre;
			$row['OctubreP'] = $octubre;
			$row['NoviembreP'] = $noviembre;
			$row['DiciembreP'] = $diciembre;
			
			$row['EneroHonorarios'] = $eneroHonorarios;
			$row['FebreroHonorarios'] = $febreroHonorarios;
			$row['MarzoHonorarios'] = $marzoHonorarios;
			$row['AbrilHonorarios'] = $abrilHonorarios;
			$row['MayoHonorarios'] = $mayoHonorarios;
			$row['JunioHonorarios'] = $junioHonorarios;
			$row['JulioHonorarios'] = $julioHonorarios;
			$row['AgostoHonorarios'] = $agostoHonorarios;
			$row['SeptiembreHonorarios'] = $septiembreHonorarios;
			$row['OctubreHonorarios'] = $octubreHonorarios;
			$row['NoviembreHonorarios'] = $noviembreHonorarios;
			$row['DiciembreHonorarios'] = $diciembreHonorarios;
			
			$row['EneroMateriales'] = $eneroMateriales;
			$row['FebreroMateriales'] = $febreroMateriales;
			$row['MarzoMateriales'] = $marzoMateriales;
			$row['AbrilMateriales'] = $abrilMateriales;
			$row['MayoMateriales'] = $mayoMateriales;
			$row['JunioMateriales'] = $junioMateriales;
			$row['JulioMateriales'] = $julioMateriales;
			$row['AgostoMateriales'] = $agostoMateriales;
			$row['SeptiembreMateriales'] = $septiembreMateriales;
			$row['OctubreMateriales'] = $octubreMateriales;
			$row['NoviembreMateriales'] = $noviembreMateriales;
			$row['DiciembreMateriales'] = $diciembreMateriales;
			
			$row['EneroC'] = $eneroC;
			$row['FebreroC'] = $febreroC;
			$row['MarzoC'] = $marzoC;
			$row['AbrilC'] = $abrilC;
			$row['MayoC'] = $mayoC;
			$row['JunioC'] = $junioC;
			$row['JulioC'] = $julioC;
			$row['AgostoC'] = $agostoC;
			$row['SeptiembreC'] = $septiembreC;
			$row['OctubreC'] = $octubreC;
			$row['NoviembreC'] = $noviembreC;
			$row['DiciembreC'] = $diciembreC;
			
			$row['EneroG'] = $eneroG;
			$row['FebreroG'] = $febreroG;
			$row['MarzoG'] = $marzoG;
			$row['AbrilG'] = $abrilG;
			$row['MayoG'] = $mayoG;
			$row['JunioG'] = $junioG;
			$row['JulioG'] = $julioG;
			$row['AgostoG'] = $agostoG;
			$row['SeptiembreG'] = $septiembreG;
			$row['OctubreG'] = $octubreG;
			$row['NoviembreG'] = $noviembreG;
			$row['DiciembreG'] = $diciembreG;
			
			$row4['EneroGAO'] = $eneroGAO;
			$row4['FebreroGAO'] = $febreroGAO;
			$row4['MarzoGAO'] = $marzoGAO;
			$row4['AbrilGAO'] = $abrilGAO;
			$row4['MayoGAO'] = $mayoGAO;
			$row4['JunioGAO'] = $junioGAO;
			$row4['JulioGAO'] = $julioGAO;
			$row4['AgostoGAO'] = $agostoGAO;
			$row4['SeptiembreGAO'] = $septiembreGAO;
			$row4['OctubreGAO'] = $octubreGAO;
			$row4['NoviembreGAO'] = $noviembreGAO;
			$row4['DiciembreGAO'] = $diciembreGAO;
			
			$row4['EneroNomina'] = $eneroNomina;
			$row4['FebreroNomina'] = $febreroNomina;
			$row4['MarzoNomina'] = $marzoNomina;
			$row4['AbrilNomina'] = $abrilNomina;
			$row4['MayoNomina'] = $mayoNomina;
			$row4['JunioNomina'] = $junioNomina;
			$row4['JulioNomina'] = $julioNomina;
			$row4['AgostoNomina'] = $agostoNomina;
			$row4['SeptiembreNomina'] = $septiembreNomina;
			$row4['OctubreNomina'] = $octubreNomina;
			$row4['NoviembreNomina'] = $noviembreNomina;
			$row4['DiciembreNomina'] = $diciembreNomina;
			
			$row4['EneroSAC'] = $eneroSAC;
			$row4['FebreroSAC'] = $febreroSAC;
			$row4['MarzoSAC'] = $marzoSAC;
			$row4['AbrilSAC'] = $abrilSAC;
			$row4['MayoSAC'] = $mayoSAC;
			$row4['JunioSAC'] = $junioSAC;
			$row4['JulioSAC'] = $julioSAC;
			$row4['AgostoSAC'] = $agostoSAC;
			$row4['SeptiembreSAC'] = $septiembreSAC;
			$row4['OctubreSAC'] = $octubreSAC;
			$row4['NoviembreSAC'] = $noviembreSAC;
			$row4['DiciembreSAC'] = $diciembreSAC;
			
			$row4['EneroOtros'] = $eneroOtros;
			$row4['FebreroOtros'] = $febreroOtros;
			$row4['MarzoOtros'] = $marzoOtros;
			$row4['AbrilOtros'] = $abrilOtros;
			$row4['MayoOtros'] = $mayoOtros;
			$row4['JunioOtros'] = $junioOtros;
			$row4['JulioOtros'] = $julioOtros;
			$row4['AgostoOtros'] = $agostoOtros;
			$row4['SeptiembreOtros'] = $septiembreOtros;
			$row4['OctubreOtros'] = $octubreOtros;
			$row4['NoviembreOtros'] = $noviembreOtros;
			$row4['DiciembreOtros'] = $diciembreOtros;
			
			$row4['EneroIntereses'] = $eneroIntereses;
			$row4['FebreroIntereses'] = $febreroIntereses;
			$row4['MarzoIntereses'] = $marzoIntereses;
			$row4['AbrilIntereses'] = $abrilIntereses;
			$row4['MayoIntereses'] = $mayoIntereses;
			$row4['JunioIntereses'] = $junioIntereses;
			$row4['JulioIntereses'] = $julioIntereses;
			$row4['AgostoIntereses'] = $agostoIntereses;
			$row4['SeptiembreIntereses'] = $septiembreIntereses;
			$row4['OctubreIntereses'] = $octubreIntereses;
			$row4['NoviembreIntereses'] = $noviembreIntereses;
			$row4['DiciembreIntereses'] = $diciembreIntereses;
			
			$row6['Enero'] = $eneroCobranza;
			$row6['Febrero'] = $febreroCobranza;
			$row6['Marzo'] = $marzoCobranza;
			$row6['Abril'] = $abrilCobranza;
			$row6['Mayo'] = $mayoCobranza;
			$row6['Junio'] = $junioCobranza;
			$row6['Julio'] = $julioCobranza;
			$row6['Agosto'] = $agostoCobranza;
			$row6['Septiembre'] = $septiembreCobranza;
			$row6['Octubre'] = $octubreCobranza;
			$row6['Noviembre'] = $noviembreCobranza;
			$row6['Diciembre'] = $diciembreCobranza;
			
			$row['TOTALP'] = $TOTALP;
			$row['TOTALC'] = $TOTALC;
			$row['TOTALG'] = $TOTALG;
			$row5['TotalAnticipo'] = $TOTALA;
			$row4['TotalGAO'] = $TOTALGAO;
			$row4['TotalNomina'] = $TOTALNomina;
			$row4['TotalSAC'] = $TOTALSAC;
			$row4['TotalOtros'] = $TOTALOtros;
			$row4['TotalIntereses'] = $TOTALIntereses;
			
			$row2=$stmt2->fetch(PDO::FETCH_ASSOC);
			$row3=$stmt3->fetch(PDO::FETCH_ASSOC);
			
			//INGRESOS
			$diferencia1 = $row2['Enero']-$row['EneroP'];
			$diferencia2 = $row2['Febrero']-$row['FebreroP'];
			$diferencia3 = $row2['Marzo']-$row['MarzoP'];
			$diferencia4 = $row2['Abril']-$row['AbrilP'];
			$diferencia5 = $row2['Mayo']-$row['MayoP'];
			$diferencia6 = $row2['Junio']-$row['JunioP'];
			$diferencia7 = $row2['Julio']-$row['JulioP'];
			$diferencia8 = $row2['Agosto']-$row['AgostoP'];
			$diferencia9 = $row2['Septiembre']-$row['SeptiembreP'];
			$diferencia10 = $row2['Octubre']-$row['OctubreP'];
			$diferencia11 = $row2['Noviembre']-$row['NoviembreP'];
			$diferencia12 = $row2['Diciembre']-$row['DiciembreP'];
			$enero = $row2['Enero']-$row['EneroP'];
			$febrero = ($enero+$row2['Febrero'])-$row['FebreroP'];
			$marzo = ($febrero+$row2['Marzo'])-$row['MarzoP'];
			$abril = ($marzo+$row2['Abril'])-$row['AbrilP'];
			$mayo = ($abril+$row2['Mayo'])-$row['MayoP'];
			$junio = ($mayo+$row2['Junio'])-$row['JunioP'];
			$julio = ($junio+$row2['Julio'])-$row['JulioP'];
			$agosto = ($julio+$row2['Agosto'])-$row['AgostoP'];
			$septiembre = ($agosto+$row2['Septiembre'])-$row['SeptiembreP'];
			$octubre = ($septiembre+$row2['Octubre'])-$row['OctubreP'];
			$noviembre = ($octubre+$row2['Noviembre'])-$row['NoviembreP'];
			$diciembre = ($noviembre+$row2['Diciembre'])-$row['DiciembreP'];
			
			//COSTOS HONORARIOS
			$diferencia1Honorarios = $row2['EneroC']-$row['EneroHonorarios'];
			$diferencia2Honorarios = $row2['FebreroC']-$row['FebreroHonorarios'];
			$diferencia3Honorarios = $row2['MarzoC']-$row['MarzoHonorarios'];
			$diferencia4Honorarios = $row2['AbrilC']-$row['AbrilHonorarios'];
			$diferencia5Honorarios = $row2['MayoC']-$row['MayoHonorarios'];
			$diferencia6Honorarios = $row2['JunioC']-$row['JunioHonorarios'];
			$diferencia7Honorarios = $row2['JulioC']-$row['JulioHonorarios'];
			$diferencia8Honorarios = $row2['AgostoC']-$row['AgostoHonorarios'];
			$diferencia9Honorarios = $row2['SeptiembreC']-$row['SeptiembreHonorarios'];
			$diferencia10Honorarios = $row2['OctubreC']-$row['OctubreHonorarios'];
			$diferencia11Honorarios = $row2['NoviembreC']-$row['NoviembreHonorarios'];
			$diferencia12Honorarios = $row2['DiciembreC']-$row['DiciembreHonorarios'];
			$eneroHonorarios = $row2['EneroC']-$row['EneroHonorarios'];
			$febreroHonorarios = $eneroHonorarios+($row2['FebreroC']-$row['FebreroHonorarios']);
			$marzoHonorarios = $febreroHonorarios+($row2['MarzoC']-$row['MarzoHonorarios']);
			$abrilHonorarios = $marzoHonorarios+($row2['AbrilC']-$row['AbrilHonorarios']);
			$mayoHonorarios = $abrilHonorarios+($row2['MayoC']-$row['MayoHonorarios']);
			$junioHonorarios = $mayoHonorarios+($row2['JunioC']-$row['JunioHonorarios']);
			$julioHonorarios = $junioHonorarios+($row2['JulioC']-$row['JulioHonorarios']);
			$agostoHonorarios = $julioHonorarios+($row2['AgostoC']-$row['AgostoHonorarios']);
			$septiembreHonorarios = $agostoHonorarios+($row2['SeptiembreC']-$row['SeptiembreHonorarios']);
			$octubreHonorarios = $septiembreHonorarios+($row2['OctubreC']-$row['OctubreHonorarios']);
			$noviembreHonorarios = $octubreHonorarios+($row2['NoviembreC']-$row['NoviembreHonorarios']);
			$diciembreHonorarios = $noviembreHonorarios+($row2['DiciembreC']-$row['DiciembreHonorarios']);
			
			//COSTOS MATERIALES
			$diferencia1Materiales = 0-$row['EneroMateriales'];
			$diferencia2Materiales = 0-$row['FebreroMateriales'];
			$diferencia3Materiales = 0-$row['MarzoMateriales'];
			$diferencia4Materiales = 0-$row['AbrilMateriales'];
			$diferencia5Materiales = 0-$row['MayoMateriales'];
			$diferencia6Materiales = 0-$row['JunioMateriales'];
			$diferencia7Materiales = 0-$row['JulioMateriales'];
			$diferencia8Materiales = 0-$row['AgostoMateriales'];
			$diferencia9Materiales = 0-$row['SeptiembreMateriales'];
			$diferencia10Materiales = 0-$row['OctubreMateriales'];
			$diferencia11Materiales = 0-$row['NoviembreMateriales'];
			$diferencia12Materiales = 0-$row['DiciembreMateriales'];
			$eneroMateriales = 0-$row['EneroMateriales'];
			$febreroMateriales = $eneroMateriales+(0-$row['FebreroMateriales']);
			$marzoMateriales = $febreroMateriales+(0-$row['MarzoMateriales']);
			$abrilMateriales = $marzoMateriales+(0-$row['AbrilMateriales']);
			$mayoMateriales = $abrilMateriales+(0-$row['MayoMateriales']);
			$junioMateriales = $mayoMateriales+(0-$row['JunioMateriales']);
			$julioMateriales = $junioMateriales+(0-$row['JulioMateriales']);
			$agostoMateriales = $julioMateriales+(0-$row['AgostoMateriales']);
			$septiembreMateriales = $agostoMateriales+(0-$row['SeptiembreMateriales']);
			$octubreMateriales = $septiembreMateriales+(0-$row['OctubreMateriales']);
			$noviembreMateriales = $octubreMateriales+(0-$row['NoviembreMateriales']);
			$diciembreMateriales = $noviembreMateriales+(0-$row['DiciembreMateriales']);
			
			//COSTOS
			$diferencia1C = $row2['EneroC']-$row['EneroC'];
			$diferencia2C = $row2['FebreroC']-$row['FebreroC'];
			$diferencia3C = $row2['MarzoC']-$row['MarzoC'];
			$diferencia4C = $row2['AbrilC']-$row['AbrilC'];
			$diferencia5C = $row2['MayoC']-$row['MayoC'];
			$diferencia6C = $row2['JunioC']-$row['JunioC'];
			$diferencia7C = $row2['JulioC']-$row['JulioC'];
			$diferencia8C = $row2['AgostoC']-$row['AgostoC'];
			$diferencia9C = $row2['SeptiembreC']-$row['SeptiembreC'];
			$diferencia10C = $row2['OctubreC']-$row['OctubreC'];
			$diferencia11C = $row2['NoviembreC']-$row['NoviembreC'];
			$diferencia12C = $row2['DiciembreC']-$row['DiciembreC'];
			$eneroC = $row2['EneroC']-$row['EneroC'];
			$febreroC = $eneroC+($row2['FebreroC']-$row['FebreroC']);
			$marzoC = $febreroC+($row2['MarzoC']-$row['MarzoC']);
			$abrilC = $marzoC+($row2['AbrilC']-$row['AbrilC']);
			$mayoC = $abrilC+($row2['MayoC']-$row['MayoC']);
			$junioC = $mayoC+($row2['JunioC']-$row['JunioC']);
			$julioC = $junioC+($row2['JulioC']-$row['JulioC']);
			$agostoC = $julioC+($row2['AgostoC']-$row['AgostoC']);
			$septiembreC = $agostoC+($row2['SeptiembreC']-$row['SeptiembreC']);
			$octubreC = $septiembreC+($row2['OctubreC']-$row['OctubreC']);
			$noviembreC = $octubreC+($row2['NoviembreC']-$row['NoviembreC']);
			$diciembreC = $noviembreC+($row2['DiciembreC']-$row['DiciembreC']);
			
			//GASTO
			$diferencia1G = $row2['EneroG']-$row['EneroG'];
			$diferencia2G = $row2['FebreroG']-$row['FebreroG'];
			$diferencia3G = $row2['MarzoG']-$row['MarzoG'];
			$diferencia4G = $row2['AbrilG']-$row['AbrilG'];
			$diferencia5G = $row2['MayoG']-$row['MayoG'];
			$diferencia6G = $row2['JunioG']-$row['JunioG'];
			$diferencia7G = $row2['JulioG']-$row['JulioG'];
			$diferencia8G = $row2['AgostoG']-$row['AgostoG'];
			$diferencia9G = $row2['SeptiembreG']-$row['SeptiembreG'];
			$diferencia10G = $row2['OctubreG']-$row['OctubreG'];
			$diferencia11G = $row2['NoviembreG']-$row['NoviembreG'];
			$diferencia12G = $row2['DiciembreG']-$row['DiciembreG'];
			$eneroG = $row2['EneroG']-$row['EneroG'];
			$febreroG = $eneroG+($row2['FebreroG']-$row['FebreroG']);
			$marzoG = $febreroG+($row2['MarzoG']-$row['MarzoG']);
			$abrilG = $marzoG+($row2['AbrilG']-$row['AbrilG']);
			$mayoG = $abrilG+($row2['MayoG']-$row['MayoG']);
			$junioG = $mayoG+($row2['JunioG']-$row['JunioG']);
			$julioG = $junioG+($row2['JulioG']-$row['JulioG']);
			$agostoG = $julioG+($row2['AgostoG']-$row['AgostoG']);
			$septiembreG = $agostoG+($row2['SeptiembreG']-$row['SeptiembreG']);
			$octubreG = $septiembreG+($row2['OctubreG']-$row['OctubreG']);
			$noviembreG = $octubreG+($row2['NoviembreG']-$row['NoviembreG']);
			$diciembreG = $noviembreG+($row2['DiciembreG']-$row['DiciembreG']);
			
			//GAOFIJO
			$gaoMensual = $row3['TotalGAO']/12;
			$nominaMensual = $row3['TotalNomina']/12;
			$sacMensual = $row3['TotalSAC']/12;
			$interesesMensual = $row3['TotalIntereses']/12;
			$otrosMensual = 0;
			
			//NOMINA
			$diferencia1Nom = $row4['EneroNomina']-$nominaMensual;
			$diferencia2Nom = $row4['FebreroNomina']-$nominaMensual;
			$diferencia3Nom = $row4['MarzoNomina']-$nominaMensual;
			$diferencia4Nom = $row4['AbrilNomina']-$nominaMensual;
			$diferencia5Nom = $row4['MayoNomina']-$nominaMensual;
			$diferencia6Nom = $row4['JunioNomina']-$nominaMensual;
			$diferencia7Nom = $row4['JulioNomina']-$nominaMensual;
			$diferencia8Nom = $row4['AgostoNomina']-$nominaMensual;
			$diferencia9Nom = $row4['SeptiembreNomina']-$nominaMensual;
			$diferencia10Nom = $row4['OctubreNomina']-$nominaMensual;
			$diferencia11Nom = $row4['NoviembreNomina']-$nominaMensual;
			$diferencia12Nom = $row4['DiciembreNomina']-$nominaMensual;
			$eneroNom = $row4['EneroNomina']-$nominaMensual;
			$febreroNom = $eneroNom+($row4['FebreroNomina']-$nominaMensual);
			$marzoNom = $febreroNom+($row4['MarzoNomina']-$nominaMensual);
			$abrilNom = $marzoNom+($row4['AbrilNomina']-$nominaMensual);
			$mayoNom = $abrilNom+($row4['MayoNomina']-$nominaMensual);
			$junioNom = $mayoNom+($row4['JunioNomina']-$nominaMensual);
			$julioNom = $junioNom+($row4['JulioNomina']-$nominaMensual);
			$agostoNom = $julioNom+($row4['AgostoNomina']-$nominaMensual);
			$septiembreNom = $agostoNom+($row4['SeptiembreNomina']-$nominaMensual);
			$octubreNom = $septiembreNom+($row4['OctubreNomina']-$nominaMensual);
			$noviembreNom = $octubreNom+($row4['NoviembreNomina']-$nominaMensual);
			$diciembreNom = $noviembreNom+($row4['DiciembreNomina']-$nominaMensual);
			
			//SAC
			$diferencia1SAC = $row4['EneroSAC']-$sacMensual;
			$diferencia2SAC = $row4['FebreroSAC']-$sacMensual;
			$diferencia3SAC = $row4['MarzoSAC']-$sacMensual;
			$diferencia4SAC = $row4['AbrilSAC']-$sacMensual;
			$diferencia5SAC = $row4['MayoSAC']-$sacMensual;
			$diferencia6SAC = $row4['JunioSAC']-$sacMensual;
			$diferencia7SAC = $row4['JulioSAC']-$sacMensual;
			$diferencia8SAC = $row4['AgostoSAC']-$sacMensual;
			$diferencia9SAC = $row4['SeptiembreSAC']-$sacMensual;
			$diferencia10SAC = $row4['OctubreSAC']-$sacMensual;
			$diferencia11SAC = $row4['NoviembreSAC']-$sacMensual;
			$diferencia12SAC = $row4['DiciembreSAC']-$sacMensual;
			$eneroSAC = $row4['EneroSAC']-$sacMensual;
			$febreroSAC = $eneroSAC+($row4['FebreroSAC']-$sacMensual);
			$marzoSAC = $febreroSAC+($row4['MarzoSAC']-$sacMensual);
			$abrilSAC = $marzoSAC+($row4['AbrilSAC']-$sacMensual);
			$mayoSAC = $abrilSAC+($row4['MayoSAC']-$sacMensual);
			$junioSAC = $mayoSAC+($row4['JunioSAC']-$sacMensual);
			$julioSAC = $junioSAC+($row4['JulioSAC']-$sacMensual);
			$agostoSAC = $julioSAC+($row4['AgostoSAC']-$sacMensual);
			$septiembreSAC = $agostoSAC+($row4['SeptiembreSAC']-$sacMensual);
			$octubreSAC = $septiembreSAC+($row4['OctubreSAC']-$sacMensual);
			$noviembreSAC = $octubreSAC+($row4['NoviembreSAC']-$sacMensual);
			$diciembreSAC = $noviembreSAC+($row4['DiciembreSAC']-$sacMensual);
			
			//OTROS
			$diferencia1Otros = $row4['EneroOtros']-$otrosMensual;
			$diferencia2Otros = $row4['FebreroOtros']-$otrosMensual;
			$diferencia3Otros = $row4['MarzoOtros']-$otrosMensual;
			$diferencia4Otros = $row4['AbrilOtros']-$otrosMensual;
			$diferencia5Otros = $row4['MayoOtros']-$otrosMensual;
			$diferencia6Otros = $row4['JunioOtros']-$otrosMensual;
			$diferencia7Otros = $row4['JulioOtros']-$otrosMensual;
			$diferencia8Otros = $row4['AgostoOtros']-$otrosMensual;
			$diferencia9Otros = $row4['SeptiembreOtros']-$otrosMensual;
			$diferencia10Otros = $row4['OctubreOtros']-$otrosMensual;
			$diferencia11Otros = $row4['NoviembreOtros']-$otrosMensual;
			$diferencia12Otros = $row4['DiciembreOtros']-$otrosMensual;
			$eneroOtros = $row4['EneroOtros']-$otrosMensual;
			$febreroOtros = $eneroOtros+($row4['FebreroOtros']-$otrosMensual);
			$marzoOtros = $febreroOtros+($row4['MarzoOtros']-$otrosMensual);
			$abrilOtros = $marzoOtros+($row4['AbrilOtros']-$otrosMensual);
			$mayoOtros = $abrilOtros+($row4['MayoOtros']-$otrosMensual);
			$junioOtros = $mayoOtros+($row4['JunioOtros']-$otrosMensual);
			$julioOtros = $junioOtros+($row4['JulioOtros']-$otrosMensual);
			$agostoOtros = $julioOtros+($row4['AgostoOtros']-$otrosMensual);
			$septiembreOtros = $agostoOtros+($row4['SeptiembreOtros']-$otrosMensual);
			$octubreOtros = $septiembreOtros+($row4['OctubreOtros']-$otrosMensual);
			$noviembreOtros = $octubreOtros+($row4['NoviembreOtros']-$otrosMensual);
			$diciembreOtros = $noviembreOtros+($row4['DiciembreOtros']-$otrosMensual);
			
			//GAO
			$diferencia1GAO = $row4['EneroGAO']-$gaoMensual;
			$diferencia2GAO = $row4['FebreroGAO']-$gaoMensual;
			$diferencia3GAO = $row4['MarzoGAO']-$gaoMensual;
			$diferencia4GAO = $row4['AbrilGAO']-$gaoMensual;
			$diferencia5GAO = $row4['MayoGAO']-$gaoMensual;
			$diferencia6GAO = $row4['JunioGAO']-$gaoMensual;
			$diferencia7GAO = $row4['JulioGAO']-$gaoMensual;
			$diferencia8GAO = $row4['AgostoGAO']-$gaoMensual;
			$diferencia9GAO = $row4['SeptiembreGAO']-$gaoMensual;
			$diferencia10GAO = $row4['OctubreGAO']-$gaoMensual;
			$diferencia11GAO = $row4['NoviembreGAO']-$gaoMensual;
			$diferencia12GAO = $row4['DiciembreGAO']-$gaoMensual;
			$eneroGAO = $row4['EneroGAO']-$gaoMensual;
			$febreroGAO = $eneroGAO+($row4['FebreroGAO']-$gaoMensual);
			$marzoGAO = $febreroGAO+($row4['MarzoGAO']-$gaoMensual);
			$abrilGAO = $marzoGAO+($row4['AbrilGAO']-$gaoMensual);
			$mayoGAO = $abrilGAO+($row4['MayoGAO']-$gaoMensual);
			$junioGAO = $mayoGAO+($row4['JunioGAO']-$gaoMensual);
			$julioGAO = $junioGAO+($row4['JulioGAO']-$gaoMensual);
			$agostoGAO = $julioGAO+($row4['AgostoGAO']-$gaoMensual);
			$septiembreGAO = $agostoGAO+($row4['SeptiembreGAO']-$gaoMensual);
			$octubreGAO = $septiembreGAO+($row4['OctubreGAO']-$gaoMensual);
			$noviembreGAO = $octubreGAO+($row4['NoviembreGAO']-$gaoMensual);
			$diciembreGAO = $noviembreGAO+($row4['DiciembreGAO']-$gaoMensual);
			
			//EBCL
			$ebclEstaEnero = $row2['EneroC'] + $gaoMensual;
			$ebclEstaFebrero = $row2['FebreroC'] + $gaoMensual;
			$ebclEstaMarzo = $row2['MarzoC'] + $gaoMensual;
			$ebclEstaAbril = $row2['AbrilC'] + $gaoMensual;
			$ebclEstaMayo = $row2['MayoC'] + $gaoMensual;
			$ebclEstaJunio = $row2['JunioC'] + $gaoMensual;
			$ebclEstaJulio = $row2['JulioC'] + $gaoMensual;
			$ebclEstaAgosto = $row2['AgostoC'] + $gaoMensual;
			$ebclEstaSeptiembre = $row2['SeptiembreC'] + $gaoMensual;
			$ebclEstaOctubre = $row2['OctubreC'] + $gaoMensual;
			$ebclEstaNoviembre = $row2['NoviembreC'] + $gaoMensual;
			$ebclEstaDiciembre = $row2['DiciembreC'] + $gaoMensual;
			$ebclEstaTotal = $row2['TOTALC'] + $row3['TotalGAO'];
			$ebclEstaTotalPor = $ebclEstaTotal / $row2['TOTAL'];
			
			$ebclRealEnero = $row['EneroC'] + $row4['EneroGAO'];
			$ebclRealFebrero = $row['FebreroC'] + $row4['FebreroGAO'];
			$ebclRealMarzo = $row['MarzoC'] + $row4['MarzoGAO'];
			$ebclRealAbril = $row['AbrilC'] + $row4['AbrilGAO'];
			$ebclRealMayo = $row['MayoC'] + $row4['MayoGAO'];
			$ebclRealJunio = $row['JunioC'] + $row4['JunioGAO'];
			$ebclRealJulio = $row['JulioC'] + $row4['JulioGAO'];
			$ebclRealAgosto = $row['AgostoC'] + $row4['AgostoGAO'];
			$ebclRealSeptiembre = $row['SeptiembreC'] + $row4['SeptiembreGAO'];
			$ebclRealOctubre = $row['OctubreC'] + $row4['OctubreGAO'];
			$ebclRealNoviembre = $row['NoviembreC'] + $row4['NoviembreGAO'];
			$ebclRealDiciembre = $row['DiciembreC'] + $row4['DiciembreGAO'];
			$ebclRealTotal = $row['TOTALC'] + $row4['TotalGAO'];

			
			$ebclRealEneroAcum = $ebclRealEnero;
			$ebclRealFebreroAcum = $ebclRealEneroAcum + $ebclRealFebrero;
			$ebclRealMarzoAcum = $ebclRealFebreroAcum + $row['MarzoC'] + $row4['MarzoGAO'];
			$ebclRealAbrilAcum = $ebclRealMarzoAcum + $row['AbrilC'] + $row4['AbrilGAO'];
			$ebclRealMayoAcum = $ebclRealAbrilAcum + $row['MayoC'] + $row4['MayoGAO'];
			$ebclRealJunioAcum = $ebclRealMayoAcum +$row['JunioC'] + $row4['JunioGAO'];
			$ebclRealJulioAcum = $ebclRealJunioAcum + $row['JulioC'] + $row4['JulioGAO'];
			$ebclRealAgostoAcum = $ebclRealJulioAcum + $row['AgostoC'] + $row4['AgostoGAO'];
			$ebclRealSeptiembreAcum = $ebclRealAgostoAcum + $row['SeptiembreC'] + $row4['SeptiembreGAO'];
			$ebclRealOctubreAcum = $ebclRealSeptiembreAcum + $row['OctubreC'] + $row4['OctubreGAO'];
			$ebclRealNoviembreAcum = $ebclRealOctubreAcum + $row['NoviembreC'] + $row4['NoviembreGAO'];
			$ebclRealDiciembreAcum = $ebclRealNoviembreAcum + $row['DiciembreC'] + $row4['DiciembreGAO'];
			
			$ebclEstaEneroAcum = $ebclEstaEnero;
			$ebclEstaFebreroAcum = $ebclEstaEneroAcum + $ebclEstaFebrero;
			$ebclEstaMarzoAcum = $ebclEstaFebreroAcum + $row2['MarzoC'] + $row4['MarzoGAO'];
			$ebclEstaAbrilAcum = $ebclEstaMarzoAcum + $row2['AbrilC'] + $row4['AbrilGAO'];
			$ebclEstaMayoAcum = $ebclEstaAbrilAcum + $row2['MayoC'] + $row4['MayoGAO'];
			$ebclEstaJunioAcum = $ebclEstaMayoAcum +$row2['JunioC'] + $row4['JunioGAO'];
			$ebclEstaJulioAcum = $ebclEstaJunioAcum + $row2['JulioC'] + $row4['JulioGAO'];
			$ebclEstaAgostoAcum = $ebclEstaJulioAcum + $row2['AgostoC'] + $row4['AgostoGAO'];
			$ebclEstaSeptiembreAcum = $ebclEstaAgostoAcum + $row2['SeptiembreC'] + $row4['SeptiembreGAO'];
			$ebclEstaOctubreAcum = $ebclEstaSeptiembreAcum + $row2['OctubreC'] + $row4['OctubreGAO'];
			$ebclEstaNoviembreAcum = $ebclEstaOctubreAcum + $row2['NoviembreC'] + $row4['NoviembreGAO'];
			$ebclEstaDiciembreAcum = $ebclEstaNoviembreAcum + $row2['DiciembreC'] + $row4['DiciembreGAO'];
			
			$ebclEneroAcumT = $ebclRealEneroAcum-$ebclEstaEneroAcum;
			$ebclFebreroAcumT = $ebclRealFebreroAcum - $ebclEstaFebreroAcum;
			$ebclMarzoAcumT = $ebclRealMarzoAcum - $ebclEstaMarzoAcum;
			$ebclAbrilAcumT = $ebclRealAbrilAcum - $ebclEstaAbrilAcum;
			$ebclMayoAcumT = $ebclRealMayoAcum - $ebclEstaMayoAcum;
			$ebclJunioAcumT = $ebclRealJunioAcum - $ebclEstaJunioAcum;
			$ebclJulioAcumT = $ebclRealJulioAcum - $ebclEstaJulioAcum;
			$ebclAgostoAcumT = $ebclRealAgostoAcum - $ebclEstaAgostoAcum;
			$ebclSeptiembreAcumT = $ebclRealSeptiembreAcum - $ebclEstaSeptiembreAcum;
			$ebclOctubreAcumT = $ebclRealOctubreAcum - $ebclEstaOctubreAcum;
			$ebclNoviembreAcumT = $ebclRealNoviembreAcum - $ebclEstaNoviembreAcum;
			$ebclDiciembreAcumT = $ebclRealDiciembreAcum - $ebclEstaDiciembreAcum;
			
			
			//COMISION PROYECTADA
			$ingresos_p = floatval($row2['TOTAL']);
			$ingresos_por_p = 100;
			$costos_p = floatval($row2['TOTALC']);
			$costos_por_p = floatval($costos_p/$ingresos_p);
			$gao_p = floatval($row3['TotalGAO']);
			$gao_por_p = floatval($gao_p/$ingresos_p);
			$comision = $row5['oferta'];
			
			$comision_por_p = round($comision - $costos_por_p - $gao_por_p,4);
			
			$costo_real = floatval($row['TOTALC']);
			$ingreso_real = floatval($row['TOTALP']);
			if($costo_real == 0)
			{
				$costo_realP = 0;
			}
			else
			{
				$costo_realP = $costo_real/$ingreso_real;
			}
			$comision_real = round($comision - $costo_realP,4);
			
			//MARGEN DIRECTO
			$diferenciaMargenDirecto1 = $row['EneroP']-$row['EneroC']-$row['EneroG'];
			
			if($row['EneroP'] == 0)
			{
				$diferenciaMargenDirectoPor1 = 0;
				$ebclRealEneroPor = 0;
				$ebclRealEneroAcumPor = $ebclRealEneroPor;
			}
			else
			{
				$diferenciaMargenDirectoPor1 = ($row['EneroP']-$row['EneroC']-$row['EneroG'])/$row['EneroP']*100;
				$ebclRealEneroPor = $ebclRealEnero/$row['EneroP'];
				$ebclRealEneroAcumPor = $ebclRealEneroPor;
			}
			$diferenciaMargenDirecto2 =	$row['FebreroP']-$row['FebreroC']-$row['FebreroG'];
			if($row['FebreroP'] == 0)
			{
				$diferenciaMargenDirectoPor2 = 0;
				$ebclRealFebreroPor = 0;
				$ebclRealFebreroAcumPor = 0;
			}
			else
			{
				$diferenciaMargenDirectoPor2 = ($row['FebreroP']-$row['FebreroC']-$row['FebreroG'])/$row['FebreroP'];
				$ebclRealFebreroPor = $ebclRealFebrero/$row['FebreroP'];
				$ebclRealFebreroAcumPor = $ebclRealFebreroAcum/($row['EneroP']+$row['FebreroP']);	
			}
			$diferenciaMargenDirecto3 =	$row['MarzoP']-$row['MarzoC']-$row['MarzoG'];
			if($row['MarzoP'] == 0)
			{
				$diferenciaMargenDirectoPor3 = 0;
				$ebclRealMarzoPor = 0;
				$ebclRealMarzoAcumPor = 0;
			}
			else
			{
				$diferenciaMargenDirectoPor3 = ($row['MarzoP']-$row['MarzoC']-$row['MarzoG'])/$row['MarzoP']*100;
				$ebclRealMarzoPor = $ebclRealMarzo/$row['MarzoP'];
				$ebclRealMarzoAcumPor = $ebclRealMarzoAcum/($row['EneroP']+$row['FebreroP']+$row['MarzoP']);				
			}
			$diferenciaMargenDirecto4 =	$row['AbrilP']-$row['AbrilC']-$row['AbrilG'];
			if($row['AbrilP'] == 0)
			{
				$diferenciaMargenDirectoPor4 = 0;
				$ebclRealAbrilPor = 0;
				$ebclRealAbrilAcumPor = 0;
			}
			else
			{
				$diferenciaMargenDirectoPor4 = ($row['AbrilP']-$row['AbrilC']-$row['AbrilG'])/$row['AbrilP']*100;
				$ebclRealAbrilPor = $ebclRealAbril/$row['AbrilP'];
				$ebclRealAbrilAcumPor = $ebclRealAbrilAcum/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']);				
			}
			$diferenciaMargenDirecto5 =	$row['MayoP']-$row['MayoC']-$row['MayoG'];
			if($row['MayoP'] == 0)
			{
				$diferenciaMargenDirectoPor5 = 0;
				$ebclRealMayoPor = 0;
				$ebclRealMayoAcumPor = 0;
			}
			else
			{
				$diferenciaMargenDirectoPor5 = ($row['MayoP']-$row['MayoC']-$row['MayoG'])/$row['MayoP']*100;
				$ebclRealMayoPor = $ebclRealMayo/$row['MayoP'];
				$ebclRealMayoAcumPor = $ebclRealMayoAcum/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']);
			}
			$diferenciaMargenDirecto6 =	$row['JunioP']-$row['JunioC']-$row['JunioG'];
			if($row['JunioP'] == 0)
			{
				$diferenciaMargenDirectoPor6 = 0;
				$ebclRealJunioPor = 0;
				$ebclRealJunioAcumPor = 0;
			}
			else
			{
				$diferenciaMargenDirectoPor6 = ($row['JunioP']-$row['JunioC']-$row['JunioG'])/$row['JunioP']*100;
				$ebclRealJunioPor = $ebclRealJunio/$row['JunioP'];
				$ebclRealJunioAcumPor = $ebclRealJunioAcum/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']);
			}
			$diferenciaMargenDirecto7 =	$row['JulioP']-$row['JulioC']-$row['JulioG'];
			if($row['JulioP'] == 0)
			{
				$diferenciaMargenDirectoPor7 = 0;
				$ebclRealJulioPor = 0;
				$ebclRealJulioAcumPor = 0;
			}
			else
			{
				$diferenciaMargenDirectoPor7 = ($row['JulioP']-$row['JulioC']-$row['JulioG'])/$row['JulioP']*100;
				$ebclRealJulioPor = $ebclRealJulio/$row['JulioP'];
				$ebclRealJulioAcumPor = $ebclRealJulioAcum/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']);
			}
			$diferenciaMargenDirecto8 =	$row['AgostoP']-$row['AgostoC']-$row['AgostoG'];
			if($row['AgostoP'] == 0)
			{
				$diferenciaMargenDirectoPor8 = 0;
				$ebclRealAgostoPor = 0;
				$ebclRealAgostoAcumPor = 0;
			}
			else
			{
				$diferenciaMargenDirectoPor8 = ($row['AgostoP']-$row['AgostoC']-$row['AgostoG'])/$row['AgostoP']*100;
				$ebclRealAgostoPor = $ebclRealAgosto/$row['AgostoP'];
				$ebclRealAgostoAcumPor = $ebclRealAgostoAcum/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']);
			}
			$diferenciaMargenDirecto9 =	$row['SeptiembreP']-$row['SeptiembreC']-$row['SeptiembreG'];
			if($row['SeptiembreP'] == 0)
			{
				$diferenciaMargenDirectoPor9 = 0;
				$ebclRealSeptiembrePor = 0;
				$ebclRealSeptiembreAcumPor = 0;
			}
			else
			{
				$diferenciaMargenDirectoPor9 = ($row['SeptiembreP']-$row['SeptiembreC']-$row['SeptiembreG'])/$row['SeptiembreP'];
				$ebclRealSeptiembrePor = $ebclRealSeptiembre/$row['SeptiembreP'];
				$ebclRealSeptiembreAcumPor = $ebclRealSeptiembreAcum/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']);
			}
			$diferenciaMargenDirecto10 = $row['OctubreP']-$row['OctubreC']-$row['OctubreG'];
			if($row['OctubreP'] == 0)
			{
				$diferenciaMargenDirectoPor10 =	0;
				$ebclRealOctubrePor = 0;
				$ebclRealOctubreAcumPor = 0;
			}
			else
			{
				$diferenciaMargenDirectoPor10 =	($row['OctubreP']-$row['OctubreC']-$row['OctubreG'])/$row['OctubreP']*100;
				$ebclRealSeptiembrePor = $ebclRealOctubre/$row['OctubreP'];
				$ebclRealOctubreAcumPor = $ebclRealOctubreAcum/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP']);
			}
			$diferenciaMargenDirecto11 = $row['NoviembreP']-$row['NoviembreC']-$row['NoviembreG'];
			if($row['NoviembreP'] == 0)
			{
				$diferenciaMargenDirectoPor11 =	0;
				$ebclRealNoviembrePor = 0;
				$ebclRealNoviembreAcumPor = 0;
			}
			else
			{
				$diferenciaMargenDirectoPor11 =	($row['NoviembreP']-$row['NoviembreC']-$row['NoviembreG'])/$row['NoviembreP']*100;
				$ebclRealNoviembrePor = $ebclRealNoviembre/$row['NoviembreP'];
				$ebclRealNoviembreAcumPor = $ebclRealNoviembreAcum/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP']+$row['NoviembreP']);
			}
			$diferenciaMargenDirecto12 = $row['DiciembreP']-$row['DiciembreC']-$row['DiciembreG'];
			if($row['DiciembreP'] == 0)
			{
				$diferenciaMargenDirectoPor12 =	0;
				$ebclRealDiciembrePor = 0;
				$ebclRealDiciembreAcumPor = 0;
			}
			else
			{
				$diferenciaMargenDirectoPor12 =	($row['DiciembreP']-$row['DiciembreC']-$row['DiciembreG'])/$row['DiciembreP']*100;
				$ebclRealDiciembrePor = $ebclRealDiciembre/$row['DiciembreP'];
				$ebclRealDiciembreAcumPor = $ebclRealDiciembreAcum/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP']+$row['NoviembreP']+$row['DiciembreP']);
			}
			$diferenciaMargenDirectoTOTAL = $row['TOTALP']-$row['TOTALC']-$row['TOTALG'];
			if($row['TOTALP'] == 0)
			{
				$diferenciaMargenDirectoPorTOTAL =	0;
				$ebclRealTotalPor = 0;
			}
			else
			{
				$diferenciaMargenDirectoPorTOTAL =	($row['TOTALP']-$row['TOTALC']-$row['TOTALG'])/$row['TOTALP']*100;
				$ebclRealTotalPor = $ebclRealTotal / $row['TOTALP'];
			}
			
			//COMISION PROYECTADA
			if($gaoMensual == 0)
			{
				$gaoMensual = 1;
			}
			

			if($row2['EneroC'] == 0 && $row2['Enero'] == 0)
			{
				$diferenciaComision1 = $comision;
				$ebclEstaEneroPor = 0;
				$ebclEstaEneroAcumPor = $ebclEstaEneroPor;
			}
			else
			{
				$diferenciaComision1 = $row2['Enero']*($comision-(($row2['EneroC']/$row2['Enero'])+($gaoMensual/$row2['Enero'])));
				$ebclEstaEneroPor = $ebclEstaEnero/$row2['Enero'];
				$ebclEstaEneroAcumPor = $ebclEstaEneroPor;
			}
			if($row2['FebreroC'] == 0 && $row2['Febrero'] == 0)
			{
				$diferenciaComision2 = $comision;
				$ebclEstaFebreroPor = 0;
				$ebclEstaFebreroAcumPor = 0;
			}
			else
			{
				$diferenciaComision2 = $row2['Febrero']*($comision-(($row2['FebreroC']/$row2['Febrero'])+($gaoMensual/$row2['Febrero'])));
				$ebclEstaFebreroPor = $ebclEstaFebrero/$row2['Febrero'];
				$ebclEstaFebreroAcumPor = $ebclEstaFebreroAcum/($row2['Enero']+$row2['Febrero']);
			}
			if($row2['MarzoC'] == 0 && $row2['Marzo'] == 0)
			{
				$diferenciaComision3 = $comision;
				$ebclEstaMarzoPor = 0;
				$ebclEstaMarzoAcumPor = 0;
			}
			else
			{
				$diferenciaComision3 = $row2['Marzo']*($comision-(($row2['MarzoC']/$row2['Marzo'])+($gaoMensual/$row2['Marzo'])));
				$ebclEstaMarzoPor = $ebclEstaMarzo/$row2['Marzo'];
				$ebclEstaMarzoAcumPor = $ebclEstaMarzoAcum/($row2['Enero']+$row2['Febrero']+$row2['Marzo']);
			}
			if($row2['AbrilC'] == 0 && $row2['Abril'] == 0)
			{
				$diferenciaComision4 = $comision;
				$ebclEstaAbrilPor = 0;
				$ebclEstaAbrilAcumPor = 0;
			}
			else
			{
				$diferenciaComision4 = $row2['Abril']*($comision-(($row2['AbrilC']/$row2['Abril'])+($gaoMensual/$row2['Abril'])));
				$ebclEstaAbrilPor = $ebclEstaAbril/$row2['Abril'];
				$ebclEstaAbrilAcumPor = $ebclEstaAbrilAcum/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']);
			}
			if($row2['MayoC'] == 0 && $row2['Mayo'] == 0)
			{
				$diferenciaComision5 = $comision;
				$ebclEstaMayoPor = 0;
				$ebclEstaMayoAcumPor = 0;
			}
			else
			{
				$diferenciaComision5 = $row2['Mayo']*($comision-(($row2['MayoC']/$row2['Mayo'])+($gaoMensual/$row2['Mayo'])));
				$ebclEstaMayoPor = $ebclEstaMayo/$row2['Mayo'];
				$ebclEstaMayoAcumPor = $ebclEstaMayoAcum/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']);
			}
			if($row2['JunioC'] == 0 && $row2['Junio'] == 0)
			{
				$diferenciaComision6 = $comision;
				$ebclEstaJunioPor = 0;
				$ebclEstaJunioAcumPor = 0;
			}
			else
			{
				$diferenciaComision6 = $row2['Junio']*($comision-(($row2['JunioC']/$row2['Junio'])+($gaoMensual/$row2['Junio'])));
				$ebclEstaJunioPor = $ebclEstaJunio/$row2['Junio'];
				$ebclEstaJunioAcumPor = $ebclEstaJunioAcum/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']);
			}
			if($row2['JulioC'] == 0 && $row2['Julio'] == 0)
			{
				$diferenciaComision7 = $comision;
				$ebclEstaJulioPor = 0;
				$ebclEstaJulioAcumPor = 0;
			}
			else
			{
				$diferenciaComision7 = $row2['Julio']*($comision-(($row2['JulioC']/$row2['Julio'])+($gaoMensual/$row2['Julio'])));
				$ebclEstaJulioPor = $ebclEstaJulio/$row2['Julio'];
				$ebclEstaJulioAcumPor = $ebclEstaJulioAcum/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']);
			}
			if($row2['AgostoC'] == 0 && $row2['Agosto'] == 0)
			{
				$diferenciaComision8 = $comision;
				$ebclEstaAgostoPor = 0;
				$ebclEstaAgostoAcumPor = 0;
			}
			else
			{
				$diferenciaComision8 = $row2['Agosto']*($comision-(($row2['AgostoC']/$row2['Agosto'])+($gaoMensual/$row2['Agosto'])));
				$ebclEstaAgostoPor = $ebclEstaAgosto/$row2['Agosto'];
				$ebclEstaAgostoAcumPor = $ebclEstaAgostoAcum/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']);
			}
			if($row2['SeptiembreC'] == 0 && $row2['Septiembre'] == 0)
			{
				$diferenciaComision9 = $comision;
				$ebclEstaSeptiembrePor = 0;
				$ebclEstaSeptiembreAcumPor = 0;
			}
			else
			{
				$diferenciaComision9 = $row2['Septiembre']*($comision-(($row2['SeptiembreC']/$row2['Septiembre'])+($gaoMensual/$row2['Septiembre'])));
				$ebclEstaSeptiembrePor = $ebclEstaSeptiembre/$row2['Septiembre'];
				$ebclEstaSeptiembreAcumPor = $ebclEstaSeptiembreAcum/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre']);
			}
			if($row2['OctubreC'] == 0 && $row2['Octubre'] == 0)
			{
				$diferenciaComision10 = $comision;
				$ebclEstaOctubrePor = 0;
				$ebclEstaOctubreAcumPor = 0;
			}
			else
			{
				$diferenciaComision10 = $row2['Octubre']*($comision-(($row2['OctubreC']/$row2['Octubre'])+($gaoMensual/$row2['Octubre'])));
				$ebclEstaOctubrePor = $ebclEstaOctubre/$row2['Octubre'];
				$ebclEstaOctubreAcumPor = $ebclEstaOctubreAcum/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre']+$row2['Octubre']);
			}
			if($row2['NoviembreC'] == 0 && $row2['Noviembre'] == 0)
			{
				$diferenciaComision11 = $comision;
				$ebclEstaNoviembrePor = 0;
				$ebclEstaNoviembreAcumPor = 0;
			}
			else
			{
				$diferenciaComision11 = $row2['Noviembre']*($comision-(($row2['NoviembreC']/$row2['Noviembre'])+($gaoMensual/$row2['Noviembre'])));
				$ebclEstaNoviembrePor = $ebclEstaNoviembre/$row2['Noviembre'];
				$ebclEstaNoviembreAcumPor = $ebclEstaNoviembreAcum/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre']+$row2['Octubre']+$row2['Noviembre']);
			}
			if($row2['DiciembreC'] == 0 && $row2['Diciembre'] == 0)
			{
				$diferenciaComision12 = $comision;
				$ebclEstaDiciembrePor = 0;
				$ebclEstaDiciembreAcumPor = 0;
			}
			else
			{
				$diferenciaComision12 = $row2['Diciembre']*($comision-(($row2['DiciembreC']/$row2['Diciembre'])+($gaoMensual/$row2['Diciembre'])));
				$ebclEstaDiciembrePor = $ebclEstaDiciembre/$row2['Diciembre'];
				$ebclEstaDiciembreAcumPor = $ebclEstaDiciembreAcum/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre']+$row2['Octubre']+$row2['Noviembre']+$row2['Diciembre']);
			}
			$diferenciaComisionTOTAL = $diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6+$diferenciaComision7+$diferenciaComision8+$diferenciaComision9+$diferenciaComision10+$diferenciaComision11+$diferenciaComision12;
			
			//COMISION REAL
			if($row4['EneroGAO'] == 0 || $row['EneroC'] == 0 || $row['EneroP'] == 0)
			{
				$diferenciaComisionRPor1 = ($comision);
			}
			else
			{
				$diferenciaComisionRPor1 = ($comision-(($row['EneroC']/$row['EneroP'])+($row4['EneroGAO']/$row['EneroP'])));
			}
			$diferenciaComisionR1 = $row['EneroP']*$diferenciaComisionRPor1;
			if($row['FebreroC'] == 0 || $row4['FebreroGAO'] == 0 || $row['FebreroP'] == 0)
			{
				$diferenciaComisionRPor2 = ($comision);
			}
			else
			{
				$diferenciaComisionRPor2 = ($comision-(($row['FebreroC']/$row['FebreroP'])+($row4['FebreroGAO']/$row['FebreroP'])));
			}
			$diferenciaComisionR2 = $row['FebreroP']*$diferenciaComisionRPor2;
			if($row4['MarzoGAO'] == 0 || $row['MarzoC'] == 0 || $row['MarzoP'] == 0)
			{
				$diferenciaComisionRPor3 = ($comision);
			}
			else
			{	
				$diferenciaComisionRPor3 = ($comision-(($row['MarzoC']/$row['MarzoP'])+($row4['MarzoGAO']/$row['MarzoP'])));
			}
			$diferenciaComisionR3 = $row['MarzoP']*$diferenciaComisionRPor3;
			if($row4['AbrilGAO'] == 0 || $row['AbrilC'] == 0 || $row['AbrilP'] == 0)
			{
				$diferenciaComisionRPor4 = ($comision);
			}
			else
			{
				$diferenciaComisionRPor4 = ($comision-(($row['AbrilC']/$row['AbrilP'])+($row4['AbrilGAO']/$row['AbrilP'])));
			}
			$diferenciaComisionR4 = $row['AbrilP']*$diferenciaComisionRPor4;
			if($row4['MayoGAO'] == 0 || $row['MayoC'] == 0 || $row['MayoP'] == 0)
			{
				$diferenciaComisionRPor5 = ($comision);
			}
			else
			{
				$diferenciaComisionRPor5 = ($comision-(($row['MayoC']/$row['MayoP'])+($row4['MayoGAO']/$row['MayoP'])));
			}
			$diferenciaComisionR5 = $row['MayoP']*$diferenciaComisionRPor5;
			if($row4['JunioGAO'] == 0 || $row['JunioC'] == 0 || $row['JunioP'] == 0)
			{
				$diferenciaComisionRPor6 = ($comision);
			}
			else
			{
				$diferenciaComisionRPor6 = ($comision-(($row['JunioC']/$row['JunioP'])+($row4['JunioGAO']/$row['JunioP'])));
			}
			$diferenciaComisionR6 = $row['JunioP']*$diferenciaComisionRPor6;
			if($row4['JulioGAO'] == 0 || $row['JulioC'] == 0 || $row['JulioP'] == 0)
			{
				$diferenciaComisionRPor7 = ($comision);
			}
			else
			{	
				$diferenciaComisionRPor7 = ($comision-(($row['JulioC']/$row['JulioP'])+($row4['JulioGAO']/$row['JulioP'])));
			}
			$diferenciaComisionR7 = $row['JulioP']*$diferenciaComisionRPor7;
			if($row4['AgostoGAO'] == 0 || $row['AgostoC'] == 0 || $row['AgostoP'] == 0)
			{
				$diferenciaComisionRPor8 = ($comision);
			}
			else
			{
				$diferenciaComisionRPor8 = ($comision-(($row['AgostoC']/$row['AgostoP'])+($row4['AgostoGAO']/$row['AgostoP'])));
			}
			$diferenciaComisionR8 = $row['AgostoP']*$diferenciaComisionRPor8;
			if($row4['SeptiembreGAO'] == 0 || $row['SeptiembreC'] == 0 || $row['SeptiembreP'] == 0)
			{
				$diferenciaComisionRPor9 = ($comision);
			}
			else
			{
				$diferenciaComisionRPor9 = ($comision-(($row['SeptiembreC']/$row['SeptiembreP'])+($row4['SeptiembreGAO']/$row['SeptiembreP'])));
			}
			$diferenciaComisionR9 = $row['SeptiembreP']*$diferenciaComisionRPor9;
			if($row4['OctubreGAO'] == 0 || $row['OctubreC'] == 0 || $row['OctubreP'] == 0)
			{
				$diferenciaComisionRPor10 = ($comision);
			}
			else
			{
				$diferenciaComisionRPor10 = ($comision-(($row['OctubreC']/$row['OctubreP'])+($row4['OctubreGAO']/$row['OctubreP'])));
			}
			$diferenciaComisionR10 = $row['OctubreP']*$diferenciaComisionRPor10;
			if($row4['NoviembreGAO'] == 0 || $row['NoviembreC'] == 0 || $row['NoviembreP'] == 0)
			{
				$diferenciaComisionRPor11 = ($comision);
			}
			else
			{
				$diferenciaComisionRPor11 = ($comision-(($row['NoviembreC']/$row['NoviembreP'])+($row4['NoviembreGAO']/$row['NoviembreP'])));
			}
			$diferenciaComisionR11 = $row['NoviembreP']*$diferenciaComisionRPor11;
			if($row4['DiciembreGAO'] == 0 || $row['DiciembreC'] == 0 || $row['DiciembreP'])
			{
				$diferenciaComisionRPor12 = ($comision);
			}
			else
			{
				$diferenciaComisionRPor12 = ($comision-(($row['DiciembreC']/$row['DiciembreP'])+($row4['DiciembreGAO']/$row['DiciembreP'])));
			}
			$diferenciaComisionR12 = $row['DiciembreP']*$diferenciaComisionRPor12;
			$diferenciaComisionRTOTAL = $diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6+$diferenciaComisionR7+$diferenciaComisionR8+$diferenciaComisionR9+$diferenciaComisionR10+$diferenciaComisionR11+$diferenciaComisionR12;
			$comision1 = $diferenciaComisionR1-$row5['Enero'];
			$comision2 = $diferenciaComisionR2-$row5['Febrero'];
			$comision3 = $diferenciaComisionR3-$row5['Marzo'];
			$comision4 = $diferenciaComisionR4-$row5['Abril'];
			$comision5 = $diferenciaComisionR5-$row5['Mayo'];
			$comision6 = $diferenciaComisionR6-$row5['Junio'];
			$comision7 = $diferenciaComisionR7-$row5['Julio'];
			$comision8 = $diferenciaComisionR8-$row5['Agosto'];
			$comision9 = $diferenciaComisionR9-$row5['Septiembre'];
			$comision10 = $diferenciaComisionR10-$row5['Octubre'];
			$comision11 = $diferenciaComisionR11-$row5['Noviembre'];
			$comision12 = $diferenciaComisionR12-$row5['Diciembre'];
			$comisionTOTAL = $diferenciaComisionRTOTAL-($row5['TotalAnticipo']);
			if($row['EneroP'] == 0)
			{
				$comisionPor1 = 0;
			}
			else
			{
				$comisionPor1 = $comision1/$row['EneroP'];
			}
			if($row['FebreroP'] == 0)
			{
				$comisionPor2 = 0;
			}
			else
			{
				$comisionPor2 = $comision2/$row['FebreroP'];
			}
			if($row['MarzoP'] == 0)
			{
				$comisionPor3 = 0;
			}
			else
			{				
				$comisionPor3 = $comision3/$row['MarzoP'];
			}
			if($row['AbrilP'] == 0)
			{
				$comisionPor4 = 0;
			}
			else
			{
				$comisionPor4 = $comision4/$row['AbrilP'];
			}
			if($row['MayoP'] == 0)
			{
				$comisionPor5 = 0;
			}
			else
			{
				$comisionPor5 = $comision5/$row['MayoP'];
			}
			if($row['JunioP'] == 0)
			{
				$comisionPor6 = 0;
			}
			else
			{
				$comisionPor6 = $comision6/$row['JunioP'];
			}
			if($row['JulioP'] == 0)
			{
				$comisionPor7 = 0;
			}
			else
			{
				$comisionPor7 = $comision7/$row['JulioP'];
			}
			if($row['AgostoP'] == 0)
			{
				$comisionPor8 = 0;
			}
			else
			{
				$comisionPor8 = $comision8/$row['AgostoP'];
			}
			if($row['SeptiembreP'] == 0)
			{
				$comisionPor9 = 0;
			}
			else
			{
				$comisionPor9 = $comision9/$row['SeptiembreP'];
			}
			if($row['OctubreP'] == 0)
			{
				$comisionPor10 = 0;
			}
			else
			{
				$comisionPor10 = $comision10/$row['OctubreP'];
			}
			if($row['NoviembreP'] == 0)
			{
				$comisionPor11 = 0;
			}
			else
			{
				$comisionPor11 = $comision11/$row['NoviembreP'];
			}
			if($row['DiciembreP'] == 0)
			{
				$comisionPor12 = 0;
			}
			else
			{
				$comisionPor12 = $comision12/$row['DiciembreP'];
			}
			if($row['TOTALP'] == 0)
			{
				$comisionPorTOTAL = 0;
			}
			else
			{
				$comisionPorTOTAL = $comisionTOTAL/($row['TOTALP']);
			}
			
			//MARGEN AJUSTADO
			//PROYECTADO
			$margenAjustado1P = $row2['Enero'] - $row2['EneroC'] - $gaoMensual - $diferenciaComision1;
			$margenAjustado2P = $row2['Febrero'] - $row2['FebreroC'] - $gaoMensual - $diferenciaComision2;
			$margenAjustado3P = $row2['Marzo'] - $row2['MarzoC'] - $gaoMensual - $diferenciaComision3;
			$margenAjustado4P = $row2['Abril'] - $row2['AbrilC'] - $gaoMensual - $diferenciaComision4;
			$margenAjustado5P = $row2['Mayo'] - $row2['MayoC'] - $gaoMensual - $diferenciaComision5;
			$margenAjustado6P = $row2['Junio'] - $row2['JunioC'] - $gaoMensual - $diferenciaComision6;
			$margenAjustado7P = $row2['Julio'] - $row2['JulioC'] - $gaoMensual - $diferenciaComision7;
			$margenAjustado8P = $row2['Agosto'] - $row2['AgostoC'] - $gaoMensual - $diferenciaComision8;
			$margenAjustado9P = $row2['Septiembre'] - $row2['SeptiembreC'] - $gaoMensual - $diferenciaComision9;
			$margenAjustado10P = $row2['Octubre'] - $row2['OctubreC'] - $gaoMensual - $diferenciaComision10;
			$margenAjustado11P = $row2['Noviembre'] - $row2['NoviembreC'] - $gaoMensual - $diferenciaComision11;
			$margenAjustado12P = $row2['Diciembre'] - $row2['DiciembreC'] - $gaoMensual - $diferenciaComision12;
			$margenAjustadoTotalP = $row2['TOTAL'] - $row2['TOTALC'] - $row3['TotalGAO'] - ($row2['TOTAL']*$comision_por_p);
			//ACUMULADA
			$margenAjustado1PA = $row2['Enero'] - $row2['EneroC'] - $gaoMensual - $diferenciaComision1;
			$margenAjustado2PA = $margenAjustado1PA + $row2['Febrero'] - $row2['FebreroC'] - $gaoMensual - $diferenciaComision2;
			$margenAjustado3PA = $margenAjustado2PA + $row2['Marzo'] - $row2['MarzoC'] - $gaoMensual - $diferenciaComision3;
			$margenAjustado4PA = $margenAjustado3PA + $row2['Abril'] - $row2['AbrilC'] - $gaoMensual - $diferenciaComision4;
			$margenAjustado5PA = $margenAjustado4PA + $row2['Mayo'] - $row2['MayoC'] - $gaoMensual - $diferenciaComision5;
			$margenAjustado6PA = $margenAjustado5PA + $row2['Junio'] - $row2['JunioC'] - $gaoMensual - $diferenciaComision6;
			$margenAjustado7PA = $margenAjustado6PA + $row2['Julio'] - $row2['JulioC'] - $gaoMensual - $diferenciaComision7;
			$margenAjustado8PA = $margenAjustado7PA + $row2['Agosto'] - $row2['AgostoC'] - $gaoMensual - $diferenciaComision8;
			$margenAjustado9PA = $margenAjustado8PA + $row2['Septiembre'] - $row2['SeptiembreC'] - $gaoMensual - $diferenciaComision9;
			$margenAjustado10PA = $margenAjustado9PA + $row2['Octubre'] - $row2['OctubreC'] - $gaoMensual - $diferenciaComision10;
			$margenAjustado11PA = $margenAjustado10PA + $row2['Noviembre'] - $row2['NoviembreC'] - $gaoMensual - $diferenciaComision11;
			$margenAjustado12PA = $margenAjustado11PA + $row2['Diciembre'] - $row2['DiciembreC'] - $gaoMensual - $diferenciaComision12;
			//REAL
			$margenAjustado1R = $row['EneroP'] - $row['EneroC'] - $row['EneroG'] - $row4['EneroGAO'] - $comision1;
			$margenAjustado2R = $row['FebreroP'] - $row['FebreroC'] - $row['FebreroG'] - $row4['FebreroGAO'] - $comision2;
			$margenAjustado3R = $row['MarzoP'] - $row['MarzoC'] - $row['MarzoG'] - $row4['MarzoGAO'] - $comision3;
			$margenAjustado4R = $row['AbrilP'] - $row['AbrilC'] - $row['AbrilG'] - $row4['AbrilGAO'] - $comision4;
			$margenAjustado5R = $row['MayoP'] - $row['MayoC'] - $row['MayoG'] - $row4['MayoGAO'] - $comision5;
			$margenAjustado6R = $row['JunioP'] - $row['JunioC'] - $row['JunioG'] - $row4['JunioGAO'] - $comision6;
			$margenAjustado7R = $row['JulioP'] - $row['JulioC'] - $row['JulioG'] - $row4['JulioGAO'] - $comision7;
			$margenAjustado8R = $row['AgostoP'] - $row['AgostoC'] - $row['AgostoG'] - $row4['AgostoGAO'] - $comision8;
			$margenAjustado9R = $row['SeptiembreP'] - $row['SeptiembreC'] - $row['SeptiembreG'] - $row4['SeptiembreGAO'] - $comision9;
			$margenAjustado10R = $row['OctubreP'] - $row['OctubreC'] - $row['OctubreG'] - $row4['OctubreGAO'] - $comision10;
			$margenAjustado11R = $row['NoviembreP'] - $row['NoviembreC'] - $row['NoviembreG'] - $row4['NoviembreGAO'] - $comision11;
			$margenAjustado12R = $row['DiciembreP'] - $row['DiciembreC'] - $row['DiciembreG'] - $row4['DiciembreGAO'] - $comision12;
			$margenAjustadoTotalR = $row['TOTALP'] - $row['TOTALC'] - $row['TOTALG'] - $row4['TotalGAO'] - $comisionTOTAL;
			//ACUMULADA
			$margenAjustado1RA = $row['EneroP'] - $row['EneroC'] - $row['EneroG'] - $row4['EneroGAO'] - $comision1;
			$margenAjustado2RA = $margenAjustado1RA + $row['FebreroP'] - $row['FebreroC'] - $row['FebreroG'] - $row4['FebreroGAO'] - $comision2;
			$margenAjustado3RA = $margenAjustado2RA + $row['MarzoP'] - $row['MarzoC'] - $row['MarzoG'] - $row4['MarzoGAO'] - $comision3;
			$margenAjustado4RA = $margenAjustado3RA + $row['AbrilP'] - $row['AbrilC'] - $row['AbrilG'] - $row4['AbrilGAO'] - $comision4;
			$margenAjustado5RA = $margenAjustado4RA + $row['MayoP'] - $row['MayoC'] - $row['MayoG'] - $row4['MayoGAO'] - $comision5;
			$margenAjustado6RA = $margenAjustado5RA + $row['JunioP'] - $row['JunioC'] - $row['JunioG'] - $row4['JunioGAO'] - $comision6;
			$margenAjustado7RA = $margenAjustado6RA + $row['JulioP'] - $row['JulioC'] - $row['JulioG'] - $row4['JulioGAO'] - $comision7;
			$margenAjustado8RA = $margenAjustado7RA + $row['AgostoP'] - $row['AgostoC'] - $row['AgostoG'] - $row4['AgostoGAO'] - $comision8;
			$margenAjustado9RA = $margenAjustado8RA + $row['SeptiembreP'] - $row['SeptiembreC'] - $row['SeptiembreG'] - $row4['SeptiembreGAO'] - $comision9;
			$margenAjustado10RA = $margenAjustado9RA + $row['OctubreP'] - $row['OctubreC'] - $row['OctubreG'] - $row4['OctubreGAO'] - $comision10;
			$margenAjustado11RA = $margenAjustado10RA + $row['NoviembreP'] - $row['NoviembreC'] - $row['NoviembreG'] - $row4['NoviembreGAO'] - $comision11;
			$margenAjustado12RA = $margenAjustado11RA + $row['DiciembreP'] - $row['DiciembreC'] - $row['DiciembreG'] - $row4['DiciembreGAO'] - $comision12;
			if($row['EneroP'] == 0)
			{
				$margenAjustadoPor1R = 0;
			}
			else
			{
				$margenAjustadoPor1R = $margenAjustado1R/$row['EneroP'];
			}
			if($row['FebreroP'] == 0)
			{
				$margenAjustadoPor2R = 0;
			}
			else
			{
				$margenAjustadoPor2R = $margenAjustado2R/$row['FebreroP'];
			}
			if($row['MarzoP'] == 0)
			{
				$margenAjustadoPor3R = 0;
			}
			else
			{
				$margenAjustadoPor3R = $margenAjustado3R/$row['MarzoP'];
			}
			if($row['AbrilP'] == 0)
			{
				$margenAjustadoPor4R = 0;
			}
			else
			{
				$margenAjustadoPor4R = $margenAjustado4R/$row['AbrilP'];
			}
			if($row['MayoP'] == 0)
			{
				$margenAjustadoPor5R = 0;
			}
			else
			{
				$margenAjustadoPor5R = $margenAjustado5R/$row['MayoP'];
			}
			if($row['JunioP'] == 0)
			{
				$margenAjustadoPor6R = 0;
			}
			else
			{
				$margenAjustadoPor6R = $margenAjustado6R/$row['JunioP'];
			}
			if($row['JulioP'] == 0)
			{
				$margenAjustadoPor7R = 0;
			}
			else
			{
				$margenAjustadoPor7R = $margenAjustado7R/$row['JulioP'];
			}
			if($row['AgostoP'] == 0)
			{
				$margenAjustadoPor8R = 0;
			}
			else
			{
				$margenAjustadoPor8R = $margenAjustado8R/$row['AgostoP'];
			}
			if($row['SeptiembreP'] == 0)
			{
				$margenAjustadoPor9R = 0;
			}
			else
			{
				$margenAjustadoPor9R = $margenAjustado9R/$row['SeptiembreP'];
			}
			if($row['OctubreP'] == 0)
			{
				$margenAjustadoPor10R = 0;
			}
			else
			{
				$margenAjustadoPor10R = $margenAjustado10R/$row['OctubreP'];
			}
			if($row['NoviembreP'] == 0)
			{
				$margenAjustadoPor11R = 0;
			}
			else
			{
				$margenAjustadoPor11R = $margenAjustado11R/$row['NoviembreP'];
			}
			if($row['DiciembreP'] == 0)
			{
				$margenAjustadoPor12R = 0;
			}
			else
			{
				$margenAjustadoPor12R = $margenAjustado12R/$row['DiciembreP'];
			}
			if($row['TOTALP'] == 0)
			{
				$margenAjustadoTotalPorR = 0;
			}
			else
			{
				$margenAjustadoTotalPorR = $margenAjustadoTotalR / $row['TOTALP'];
			}
			//DIFERENCIA
			$margenAjustado1D = $margenAjustado1P - $margenAjustado1R;
			$margenAjustado2D = $margenAjustado2P - $margenAjustado2R;
			$margenAjustado3D = $margenAjustado3P - $margenAjustado3R;
			$margenAjustado4D = $margenAjustado4P - $margenAjustado4R;
			$margenAjustado5D = $margenAjustado5P - $margenAjustado5R;
			$margenAjustado6D = $margenAjustado6P - $margenAjustado6R;
			$margenAjustado7D = $margenAjustado7P - $margenAjustado7R;
			$margenAjustado8D = $margenAjustado8P - $margenAjustado8R;
			$margenAjustado9D = $margenAjustado9P - $margenAjustado9R;
			$margenAjustado10D = $margenAjustado10P - $margenAjustado10R;
			$margenAjustado11D = $margenAjustado11P - $margenAjustado11R;
			$margenAjustado12D = $margenAjustado12P - $margenAjustado12R;
			$margenAjustadoTotalD = $margenAjustadoTotalP - $margenAjustadoTotalR;
			//AJUSTE - REAL
			$margenAjustado1A = $margenAjustado1P - $margenAjustado1R;
			$margenAjustado2A = ($margenAjustado2P + $margenAjustado1A) - $margenAjustado2R;
			$margenAjustado3A = ($margenAjustado3P + $margenAjustado2A) - $margenAjustado3R;
			$margenAjustado4A = ($margenAjustado4P + $margenAjustado3A) - $margenAjustado4R;
			$margenAjustado5A = ($margenAjustado5P + $margenAjustado4A) - $margenAjustado5R;
			$margenAjustado6A = ($margenAjustado6P + $margenAjustado5A) - $margenAjustado6R;
			$margenAjustado7A = ($margenAjustado7P + $margenAjustado6A) - $margenAjustado7R;
			$margenAjustado8A = ($margenAjustado8P + $margenAjustado7A) - $margenAjustado8R;
			$margenAjustado9A = ($margenAjustado9P + $margenAjustado8A) - $margenAjustado9R;
			$margenAjustado10A = ($margenAjustado10P + $margenAjustado9A) - $margenAjustado10R;
			$margenAjustado11A = ($margenAjustado11P + $margenAjustado10A) - $margenAjustado11R;
			$margenAjustado12A = ($margenAjustado12P + $margenAjustado11A) - $margenAjustado12R;
			//AJUSTE - REAL
			$margenAjustado1B = $margenAjustado1P - $margenAjustado1R;
			$margenAjustado2B = ($margenAjustado2P + $margenAjustado1A) - $margenAjustado2R;
			$margenAjustado3B = ($margenAjustado3P + $margenAjustado2A) - $margenAjustado3R;
			$margenAjustado4B = ($margenAjustado4P + $margenAjustado3A) - $margenAjustado4R;
			$margenAjustado5B = ($margenAjustado5P + $margenAjustado4A) - $margenAjustado5R;
			$margenAjustado6B = ($margenAjustado6P + $margenAjustado5A) - $margenAjustado6R;
			$margenAjustado7B = ($margenAjustado7P + $margenAjustado6A) - $margenAjustado7R;
			$margenAjustado8B = ($margenAjustado8P + $margenAjustado7A) - $margenAjustado8R;
			$margenAjustado9B = ($margenAjustado9P + $margenAjustado8A) - $margenAjustado9R;
			$margenAjustado10B = ($margenAjustado10P + $margenAjustado9A) - $margenAjustado10R;
			$margenAjustado11B = ($margenAjustado11P + $margenAjustado10A) - $margenAjustado11R;
			$margenAjustado12B = ($margenAjustado12P + $margenAjustado11A) - $margenAjustado12R;
			
			
			//INTERESES
			$intereses1R = $row6['Enero'] - $row['EneroC'] - $row['EneroG'] - $row4['EneroGAO'] - $comision1;
			$intereses2R = $row6['Febrero'] - $row['FebreroC'] - $row['FebreroG'] - $row4['FebreroGAO'] - $comision2;
			$intereses3R = $row6['Marzo'] - $row['MarzoC'] - $row['MarzoG'] - $row4['MarzoGAO'] - $comision3;
			$intereses4R = $row6['Abril'] - $row['AbrilC'] - $row['AbrilG'] - $row4['AbrilGAO'] - $comision4;
			$intereses5R = $row6['Mayo'] - $row['MayoC'] - $row['MayoG'] - $row4['MayoGAO'] - $comision5;
			$intereses6R = $row6['Junio'] - $row['JunioC'] - $row['JunioG'] - $row4['JunioGAO'] - $comision6;
			$intereses7R = $row6['Julio'] - $row['JulioC'] - $row['JulioG'] - $row4['JulioGAO'] - $comision7;
			$intereses8R = $row6['Agosto'] - $row['AgostoC'] - $row['AgostoG'] - $row4['AgostoGAO'] - $comision8;
			$intereses9R = $row6['Septiembre'] - $row['SeptiembreC'] - $row['SeptiembreG'] - $row4['SeptiembreGAO'] - $comision9;
			$intereses10R = $row6['Octubre'] - $row['OctubreC'] - $row['OctubreG'] - $row4['OctubreGAO'] - $comision10;
			$intereses11R = $row6['Noviembre'] - $row['NoviembreC'] - $row['NoviembreG'] - $row4['NoviembreGAO'] - $comision11;
			$intereses12R = $row6['Diciembre'] - $row['DiciembreC'] - $row['DiciembreG'] - $row4['DiciembreGAO'] - $comision12;
			
			$intereses1A = $intereses1R;
			$intereses2A = $intereses1A + $row6['Febrero'] - $row['FebreroC'] - $row['FebreroG'] - $row4['FebreroGAO'] - $comision2;
			$intereses3A = $intereses2A + $row6['Marzo'] - $row['MarzoC'] - $row['MarzoG'] - $row4['MarzoGAO'] - $comision3;
			$intereses4A = $intereses3A + $row6['Abril'] - $row['AbrilC'] - $row['AbrilG'] - $row4['AbrilGAO'] - $comision4;
			$intereses5A = $intereses4A + $row6['Mayo'] - $row['MayoC'] - $row['MayoG'] - $row4['MayoGAO'] - $comision5;
			$intereses6A = $intereses5A + $row6['Junio'] - $row['JunioC'] - $row['JunioG'] - $row4['JunioGAO'] - $comision6;
			$intereses7A = $intereses6A + $row6['Julio'] - $row['JulioC'] - $row['JulioG'] - $row4['JulioGAO'] - $comision7;
			$intereses8A = $intereses7A + $row6['Agosto'] - $row['AgostoC'] - $row['AgostoG'] - $row4['AgostoGAO'] - $comision8;
			$intereses9A = $intereses8A + $row6['Septiembre'] - $row['SeptiembreC'] - $row['SeptiembreG'] - $row4['SeptiembreGAO'] - $comision9;
			$intereses10A = $intereses9A + $row6['Octubre'] - $row['OctubreC'] - $row['OctubreG'] - $row4['OctubreGAO'] - $comision10;
			$intereses11A = $intereses10A + $row6['Noviembre'] - $row['NoviembreC'] - $row['NoviembreG'] - $row4['NoviembreGAO'] - $comision11;
			$intereses12A = $intereses11A + $row6['Diciembre'] - $row['DiciembreC'] - $row['DiciembreG'] - $row4['DiciembreGAO'] - $comision12;
			
			if($intereses1R > 0)
			{
				$intereses1R = 0;
			}
			else
			{
				$intereses1R = $intereses1R * 0.02 *-1;
			}
			if($intereses2R > 0)
			{
				$intereses2R = 0;
			}
			else
			{
				$intereses2R = $intereses2R * 0.02 *-1;
			}
			if($intereses3R > 0)
			{
				$intereses3R = 0;
			}
			else
			{
				$intereses3R = $intereses3R * 0.02 *-1;
			}
			if($intereses4R > 0)
			{
				$intereses4R = 0;
			}
			else
			{
				$intereses4R = $intereses4R * 0.02 *-1;
			}
			if($intereses5R > 0)
			{
				$intereses5R = 0;
			}
			else
			{
				$intereses5R = $intereses5R * 0.02 *-1;
			}
			if($intereses6R > 0)
			{
				$intereses6R = 0;
			}
			else
			{
				$intereses6R = $intereses6R * 0.02 *-1;
			}
			if($intereses7R > 0)
			{
				$intereses7R = 0;
			}
			else
			{
				$intereses7R = $intereses7R * 0.02 *-1;
			}
			if($intereses8R > 0)
			{
				$intereses8R = 0;
			}
			else
			{
				$intereses8R = $intereses8R * 0.02 *-1;
			}
			if($intereses9R > 0)
			{
				$intereses9R = 0;
			}
			else
			{
				$intereses9R = $intereses9R * 0.02 *-1;
			}
			if($intereses10R > 0)
			{
				$intereses10R = 0;
			}
			else
			{
				$intereses10R = $intereses10R * 0.02 *-1;
			}
			if($intereses11R > 0)
			{
				$intereses11R = 0;
			}
			else
			{
				$intereses11R = $intereses11R * 0.02 *-1;
			}
			if($intereses12R > 0)
			{
				$intereses12R = 0;
			}
			else
			{
				$intereses12R = $intereses12R * 0.02 *-1;
			}
			
			$diferencia1Intereses = $intereses1R-$interesesMensual;
			$diferencia2Intereses = $intereses2R-$interesesMensual;
			$diferencia3Intereses = $intereses3R-$interesesMensual;
			$diferencia4Intereses = $intereses4R-$interesesMensual;
			$diferencia5Intereses = $intereses5R-$interesesMensual;
			$diferencia6Intereses = $intereses6R-$interesesMensual;
			$diferencia7Intereses = $intereses7R-$interesesMensual;
			$diferencia8Intereses = $intereses8R-$interesesMensual;
			$diferencia9Intereses = $intereses9R-$interesesMensual;
			$diferencia10Intereses = $intereses10R-$interesesMensual;
			$diferencia11Intereses = $intereses11R-$interesesMensual;
			$diferencia12Intereses = $intereses12R-$interesesMensual;
			$eneroIntereses = $intereses1R-$interesesMensual;
			$febreroIntereses = $eneroIntereses+$diferencia2Intereses;
			$marzoIntereses = $febreroIntereses+$diferencia3Intereses;
			$abrilIntereses = $marzoIntereses+$diferencia4Intereses;
			$mayoIntereses = $abrilIntereses+$diferencia5Intereses;
			$junioIntereses = $mayoIntereses+$diferencia6Intereses;
			$julioIntereses = $junioIntereses+$diferencia7Intereses;
			$agostoIntereses = $julioIntereses+$diferencia8Intereses;
			$septiembreIntereses = $agostoIntereses+$diferencia9Intereses;
			$octubreIntereses = $septiembreIntereses+$diferencia10Intereses;
			$noviembreIntereses = $octubreIntereses+$diferencia11Intereses;
			$diciembreIntereses = $noviembreIntereses+$diferencia12Intereses;
			
			//GAO ACUMULADO MAS INTERESES
			$eneroGAO = $row4['EneroGAO']-$gaoMensual;
			$febreroGAO = $eneroGAO+($row4['FebreroGAO']-$gaoMensual);
			$marzoGAO = $febreroGAO+($row4['MarzoGAO']-$gaoMensual);
			$abrilGAO = $marzoGAO+($row4['AbrilGAO']-$gaoMensual+$intereses4R);
			$mayoGAO = $abrilGAO+($row4['MayoGAO']-$gaoMensual+$intereses5R);
			$junioGAO = $mayoGAO+($row4['JunioGAO']-$gaoMensual+$intereses6R);
			$julioGAO = $junioGAO+($row4['JulioGAO']-$gaoMensual+$intereses7R);
			$agostoGAO = $julioGAO+($row4['AgostoGAO']-$gaoMensual+$intereses8R);
			$septiembreGAO = $agostoGAO+($row4['SeptiembreGAO']-$gaoMensual+$intereses9R);
			$octubreGAO = $septiembreGAO+($row4['OctubreGAO']-$gaoMensual+$intereses10R);
			$noviembreGAO = $octubreGAO+($row4['NoviembreGAO']-$gaoMensual+$intereses11R);
			$diciembreGAO = $noviembreGAO+($row4['DiciembreGAO']-$gaoMensual+$intereses12R);
			
			//EBCL REAL MAS INTERESES
			$ebclRealEnero = $row['EneroC'] + $row4['EneroGAO'];
			$ebclRealFebrero = $row['FebreroC'] + $row4['FebreroGAO'];
			$ebclRealMarzo = $row['MarzoC'] + $row4['MarzoGAO'];
			$ebclRealAbril = $row['AbrilC'] + $row4['AbrilGAO'] + $intereses4R;
			$ebclRealMayo = $row['MayoC'] + $row4['MayoGAO'] + $intereses5R;
			$ebclRealJunio = $row['JunioC'] + $row4['JunioGAO'] + $intereses6R;
			$ebclRealJulio = $row['JulioC'] + $row4['JulioGAO'] + $intereses7R;
			$ebclRealAgosto = $row['AgostoC'] + $row4['AgostoGAO'] + $intereses8R;
			$ebclRealSeptiembre = $row['SeptiembreC'] + $row4['SeptiembreGAO'] + $intereses9R;
			$ebclRealOctubre = $row['OctubreC'] + $row4['OctubreGAO'] + $intereses10R;
			$ebclRealNoviembre = $row['NoviembreC'] + $row4['NoviembreGAO'] + $intereses11R;
			$ebclRealDiciembre = $row['DiciembreC'] + $row4['DiciembreGAO'] + $intereses12R;
			$ebclRealTotal = $row['TOTALC'] + $row4['TotalGAO'];
			
			$ebclRealEneroAcum = $ebclRealEnero;
			$ebclRealFebreroAcum = $ebclRealEneroAcum + $ebclRealFebrero;
			$ebclRealMarzoAcum = $ebclRealFebreroAcum + $row['MarzoC'] + $row4['MarzoGAO'];
			$ebclRealAbrilAcum = $ebclRealMarzoAcum + $row['AbrilC'] + $row4['AbrilGAO'] + $intereses4R;
			$ebclRealMayoAcum = $ebclRealAbrilAcum + $row['MayoC'] + $row4['MayoGAO'] + $intereses5R;
			$ebclRealJunioAcum = $ebclRealMayoAcum +$row['JunioC'] + $row4['JunioGAO'] + $intereses6R;
			$ebclRealJulioAcum = $ebclRealJunioAcum + $row['JulioC'] + $row4['JulioGAO'] + $intereses7R;
			$ebclRealAgostoAcum = $ebclRealJulioAcum + $row['AgostoC'] + $row4['AgostoGAO'] + $intereses8R;
			$ebclRealSeptiembreAcum = $ebclRealAgostoAcum + $row['SeptiembreC'] + $row4['SeptiembreGAO'] + $intereses9R;
			$ebclRealOctubreAcum = $ebclRealSeptiembreAcum + $row['OctubreC'] + $row4['OctubreGAO'] + $intereses10R;
			$ebclRealNoviembreAcum = $ebclRealOctubreAcum + $row['NoviembreC'] + $row4['NoviembreGAO'] + $intereses11R;
			$ebclRealDiciembreAcum = $ebclRealNoviembreAcum + $row['DiciembreC'] + $row4['DiciembreGAO'] + $intereses12R;
			
			$ebclEneroAcumT = $ebclRealEneroAcum-$ebclEstaEneroAcum;
			$ebclFebreroAcumT = $ebclRealFebreroAcum - $ebclEstaFebreroAcum;
			$ebclMarzoAcumT = $ebclRealMarzoAcum - $ebclEstaMarzoAcum;
			$ebclAbrilAcumT = $ebclRealAbrilAcum - $ebclEstaAbrilAcum;
			$ebclMayoAcumT = $ebclRealMayoAcum - $ebclEstaMayoAcum;
			$ebclJunioAcumT = $ebclRealJunioAcum - $ebclEstaJunioAcum;
			$ebclJulioAcumT = $ebclRealJulioAcum - $ebclEstaJulioAcum;
			$ebclAgostoAcumT = $ebclRealAgostoAcum - $ebclEstaAgostoAcum;
			$ebclSeptiembreAcumT = $ebclRealSeptiembreAcum - $ebclEstaSeptiembreAcum;
			$ebclOctubreAcumT = $ebclRealOctubreAcum - $ebclEstaOctubreAcum;
			$ebclNoviembreAcumT = $ebclRealNoviembreAcum - $ebclEstaNoviembreAcum;
			$ebclDiciembreAcumT = $ebclRealDiciembreAcum - $ebclEstaDiciembreAcum;
			
			//MARGEN AJUSTADO MAS INTERESES
			$margenAjustado1R = $row['EneroP'] - $row['EneroC'] - $row['EneroG'] - $row4['EneroGAO'] - $comision1;
			$margenAjustado2R = $row['FebreroP'] - $row['FebreroC'] - $row['FebreroG'] - $row4['FebreroGAO'] - $comision2;
			$margenAjustado3R = $row['MarzoP'] - $row['MarzoC'] - $row['MarzoG'] - $row4['MarzoGAO'] - $comision3;
			$margenAjustado4R = $row['AbrilP'] - $row['AbrilC'] - $row['AbrilG'] - $row4['AbrilGAO'] - $comision4 - $intereses4R;
			$margenAjustado5R = $row['MayoP'] - $row['MayoC'] - $row['MayoG'] - $row4['MayoGAO'] - $comision5 - $intereses5R;
			$margenAjustado6R = $row['JunioP'] - $row['JunioC'] - $row['JunioG'] - $row4['JunioGAO'] - $comision6 - $intereses6R;
			$margenAjustado7R = $row['JulioP'] - $row['JulioC'] - $row['JulioG'] - $row4['JulioGAO'] - $comision7 - $intereses7R;
			$margenAjustado8R = $row['AgostoP'] - $row['AgostoC'] - $row['AgostoG'] - $row4['AgostoGAO'] - $comision8 - $intereses8R;
			$margenAjustado9R = $row['SeptiembreP'] - $row['SeptiembreC'] - $row['SeptiembreG'] - $row4['SeptiembreGAO'] - $comision9 - $intereses9R;
			$margenAjustado10R = $row['OctubreP'] - $row['OctubreC'] - $row['OctubreG'] - $row4['OctubreGAO'] - $comision10 - $intereses10R;
			$margenAjustado11R = $row['NoviembreP'] - $row['NoviembreC'] - $row['NoviembreG'] - $row4['NoviembreGAO'] - $comision11 - $intereses11R;
			$margenAjustado12R = $row['DiciembreP'] - $row['DiciembreC'] - $row['DiciembreG'] - $row4['DiciembreGAO'] - $comision12 - $intereses12R;
			$margenAjustadoTotalR = $row['TOTALP'] - $row['TOTALC'] - $row['TOTALG'] - $row4['TotalGAO'] - $comisionTOTAL;
			//ACUMULADA
			$margenAjustado1RA = $row['EneroP'] - $row['EneroC'] - $row['EneroG'] - $row4['EneroGAO'] - $comision1;
			$margenAjustado2RA = $margenAjustado1RA + $row['FebreroP'] - $row['FebreroC'] - $row['FebreroG'] - $row4['FebreroGAO'] - $comision2;
			$margenAjustado3RA = $margenAjustado2RA + $row['MarzoP'] - $row['MarzoC'] - $row['MarzoG'] - $row4['MarzoGAO'] - $comision3;
			$margenAjustado4RA = $margenAjustado3RA + $row['AbrilP'] - $row['AbrilC'] - $row['AbrilG'] - $row4['AbrilGAO'] - $comision4 - $intereses4R;
			$margenAjustado5RA = $margenAjustado4RA + $row['MayoP'] - $row['MayoC'] - $row['MayoG'] - $row4['MayoGAO'] - $comision5 - $intereses5R;
			$margenAjustado6RA = $margenAjustado5RA + $row['JunioP'] - $row['JunioC'] - $row['JunioG'] - $row4['JunioGAO'] - $comision6 - $intereses6R;
			$margenAjustado7RA = $margenAjustado6RA + $row['JulioP'] - $row['JulioC'] - $row['JulioG'] - $row4['JulioGAO'] - $comision7 - $intereses7R;
			$margenAjustado8RA = $margenAjustado7RA + $row['AgostoP'] - $row['AgostoC'] - $row['AgostoG'] - $row4['AgostoGAO'] - $comision8 - $intereses8R;
			$margenAjustado9RA = $margenAjustado8RA + $row['SeptiembreP'] - $row['SeptiembreC'] - $row['SeptiembreG'] - $row4['SeptiembreGAO'] - $comision9 - $intereses9R;
			$margenAjustado10RA = $margenAjustado9RA + $row['OctubreP'] - $row['OctubreC'] - $row['OctubreG'] - $row4['OctubreGAO'] - $comision10 - $intereses10R;
			$margenAjustado11RA = $margenAjustado10RA + $row['NoviembreP'] - $row['NoviembreC'] - $row['NoviembreG'] - $row4['NoviembreGAO'] - $comision11 - $intereses11R;
			$margenAjustado12RA = $margenAjustado11RA + $row['DiciembreP'] - $row['DiciembreC'] - $row['DiciembreG'] - $row4['DiciembreGAO'] - $comision12 - $intereses12R;
			?>
				<!--INGRESOS-->
				<tr>
					<th >Ingresos</th>
					<td>$<?php print(number_format($row2['TOTAL'],2));?></td>
					<td>100%</td>
					<td></td>
					<td>$<?php print(number_format($row2['Enero'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['EneroP'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['EneroP']-$row2['Enero'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Febrero'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['FebreroP'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['FebreroP']-$row2['Febrero'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Enero']+$row2['Febrero'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row['EneroP']+$row['FebreroP'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($febrero*-1,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Marzo'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['MarzoP'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['MarzoP']-$row2['Marzo'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Enero']+$row2['Febrero']+$row2['Marzo'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row['EneroP']+$row['FebreroP']+$row['MarzoP'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($marzo*-1,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Abril'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['AbrilP'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['AbrilP']-$row2['Abril'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($abril,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Mayo'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['MayoP'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['MayoP']-$row2['Mayo'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($mayo,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Junio'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['JunioP'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['JunioP']-$row2['Junio'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($junio,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Julio'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['JulioP'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['JulioP']-$row2['Julio'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($julio,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Agosto'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['AgostoP'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['AgostoP']-$row2['Agosto'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($agosto,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Septiembre'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['SeptiembreP'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['SeptiembreP']-$row2['Septiembre'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($septiembre,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Octubre'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['OctubreP'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['OctubreP']-$row2['Octubre'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre']+$row2['Octubre'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($octubre,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Noviembre'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['NoviembreP'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['NoviembreP']-$row2['Noviembre'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre']+$row2['Octubre']+$row2['Noviembre'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP']+$row['NoviembreP'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($noviembre,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Diciembre'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['DiciembreP'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['DiciembreP']-$row2['Diciembre'],2));?></td>
				</tr>
				<!--COSTOS HONORARIOS-->
				<tr>
					<th>Costos Honorarios</th>
					<td>$<?php print(number_format($row2['TOTALC'],2));?></td>
					<td><?php print(round($row2['costo']*100,2));?>%</td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroC'],2));?></td>
					<td><?php print(round($row2['costo']*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroHonorarios'],2));?></td>
					<td><?php if($row['EneroHonorarios'] == 0 || $row['EneroP'] == 0) {print(0);}else{print(round($row['EneroHonorarios']/$row['EneroP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['EneroHonorarios']-$row2['EneroC'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['FebreroC'],2));?></td>
					<td><?php print(round($row2['costo']*100,2));?>%</td>
					<td>$<?php print(number_format($row['FebreroHonorarios'],2));?></td>
					<td><?php if($row['FebreroHonorarios'] == 0 || $row['FebreroP'] == 0) {print(0);}else{print(round($row['FebreroHonorarios']/$row['FebreroP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['FebreroHonorarios']-$row2['FebreroC'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroC']+$row2['FebreroC'],2));?></td>
					<td><?php print(round(($row2['costo'])*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroHonorarios']+$row['FebreroHonorarios'],2));?></td>
					<td><?php if($row['EneroHonorarios'] == 0 || $row['EneroP'] == 0 || $row['FebreroHonorarios'] == 0 || $row['FebreroP'] == 0) {print(0);}else{print(round(($row['EneroHonorarios']+$row['FebreroHonorarios'])/($row['EneroP']+$row['FebreroP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($febreroC*-1,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['MarzoC'],2));?></td>
					<td><?php print(round(($row2['costo'])*100,2));?>%</td>
					<td>$<?php print(number_format($row['MarzoHonorarios'],2));?></td>
					<td><?php if($row['MarzoHonorarios'] == 0 || $row['MarzoP'] == 0) {print(0);}else{print(round($row['MarzoHonorarios']/$row['MarzoP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['MarzoHonorarios']-$row2['MarzoC'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroC']+$row2['FebreroC']+$row2['MarzoC'],2));?></td>
					<td><?php print(round(($row2['costo'])*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroHonorarios']+$row['FebreroHonorarios']+$row['MarzoHonorarios'],2));?></td>
					<td><?php if($row['EneroHonorarios'] == 0 || $row['EneroP'] == 0 || $row['FebreroHonorarios'] == 0 || $row['FebreroP'] == 0 || $row['MarzoHonorarios'] == 0 || $row['MarzoP'] == 0) {print(0);}else{print(round(($row['EneroHonorarios']+$row['FebreroHonorarios']+$row['MarzoHonorarios'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($marzoC*-1,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['AbrilC'],2));?></td>
					<td><?php print(round($row2['costo']*100,2));?>%</td>
					<td>$<?php print(number_format($row['AbrilHonorarios'],2));?></td>
					<td><?php if($row['AbrilHonorarios'] == 0 || $row['AbrilP'] == 0) {print(0);}else{print(round($row['AbrilHonorarios']/$row['AbrilP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['AbrilHonorarios']-$row2['AbrilC'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroC']+$row2['FebreroC']+$row2['MarzoC']+$row2['AbrilC'],2));?></td>
					<td><?php print(round(($row2['costo'])*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroHonorarios']+$row['FebreroHonorarios']+$row['MarzoHonorarios']+$row['AbrilHonorarios'],2));?></td>
					<td><?php if($row['EneroHonorarios'] == 0 || $row['EneroP'] == 0 || $row['FebreroHonorarios'] == 0 || $row['FebreroP'] == 0 || $row['MarzoHonorarios'] == 0 || $row['MarzoP'] == 0 || $row['AbrilHonorarios'] == 0 || $row['AbrilP'] == 0) {print(0);}else{print(round(($row['EneroHonorarios']+$row['FebreroHonorarios']+$row['MarzoHonorarios']+$row['AbrilHonorarios'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($abrilC,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['MayoC'],2));?></td>
					<td><?php print(round($row2['costo']*100,2));?>%</td>
					<td>$<?php print(number_format($row['MayoC'],2));?></td>
					<td><?php if($row['MayoC'] == 0 || $row['MayoP'] == 0) {print(0);}else{print(round($row['MayoC']/$row['MayoP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['MayoC']-$row2['MayoC'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroC']+$row2['FebreroC']+$row2['MarzoC']+$row2['AbrilC']+$row2['MayoC'],2));?></td>
					<td><?php print(round(($row2['costo'])*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroHonorarios']+$row['FebreroHonorarios']+$row['MarzoHonorarios']+$row['AbrilHonorarios']+$row['MayoC'],2));?></td>
					<td><?php if($row['EneroHonorarios'] == 0 || $row['EneroP'] == 0 || $row['FebreroHonorarios'] == 0 || $row['FebreroP'] == 0 || $row['MarzoHonorarios'] == 0 || $row['MarzoP'] == 0 || $row['AbrilHonorarios'] == 0 || $row['AbrilP'] == 0 || $row['MayoC'] == 0 || $row['MayoP'] == 0) {print(0);}else{print(round(($row['EneroHonorarios']+$row['FebreroHonorarios']+$row['MarzoHonorarios']+$row['AbrilHonorarios']+$row['MayoC'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($mayoC,2));?></td>					
					<td></td>
					<td>$<?php print(number_format($row2['JunioC'],2));?></td>
					<td><?php print(round($row2['costo']*100,2));?>%</td>
					<td>$<?php print(number_format($row['JunioHonorarios'],2));?></td>
					<td><?php if($row['JunioHonorarios'] == 0 || $row['JunioP'] == 0) {print(0);}else{print(round($row['JunioHonorarios']/$row['JunioP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['JunioHonorarios']-$row2['JunioC'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroC']+$row2['FebreroC']+$row2['MarzoC']+$row2['AbrilC']+$row2['MayoC']+$row2['JunioC'],2));?></td>
					<td><?php print(round(($row2['costo'])*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroHonorarios']+$row['FebreroHonorarios']+$row['MarzoHonorarios']+$row['AbrilHonorarios']+$row['MayoC']+$row['JunioHonorarios'],2));?></td>
					<td><?php if($row['EneroHonorarios'] == 0 || $row['EneroP'] == 0 || $row['FebreroHonorarios'] == 0 || $row['FebreroP'] == 0 || $row['MarzoHonorarios'] == 0 || $row['MarzoP'] == 0 || $row['AbrilHonorarios'] == 0 || $row['AbrilP'] == 0 || $row['MayoC'] == 0 || $row['MayoP'] == 0 || $row['JunioHonorarios'] == 0 || $row['JunioP'] == 0) {print(0);}else{print(round(($row['EneroHonorarios']+$row['FebreroHonorarios']+$row['MarzoHonorarios']+$row['AbrilHonorarios']+$row['MayoC']+$row['JunioHonorarios'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($junioC,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['JulioC'],2));?></td>
					<td><?php print(round($row2['costo']*100,2));?>%</td>
					<td>$<?php print(number_format($row['JulioHonorarios'],2));?></td>
					<td><?php if($row['JulioHonorarios'] == 0 || $row['JulioP'] == 0) {print(0);}else{print(round($row['JulioHonorarios']/$row['JulioP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['JulioHonorarios']-$row2['JulioC'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroC']+$row2['FebreroC']+$row2['MarzoC']+$row2['AbrilC']+$row2['MayoC']+$row2['JunioC']+$row2['JulioC'],2));?></td>
					<td><?php print(round(($row2['costo'])*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroHonorarios']+$row['FebreroHonorarios']+$row['MarzoHonorarios']+$row['AbrilHonorarios']+$row['MayoC']+$row['JunioHonorarios']+$row['JulioHonorarios'],2));?></td>
					<td><?php if($row['EneroHonorarios'] == 0 || $row['EneroP'] == 0 || $row['FebreroHonorarios'] == 0 || $row['FebreroP'] == 0 || $row['MarzoHonorarios'] == 0 || $row['MarzoP'] == 0 || $row['AbrilHonorarios'] == 0 || $row['AbrilP'] == 0 || $row['MayoC'] == 0 || $row['MayoP'] == 0 || $row['JunioHonorarios'] == 0 || $row['JunioP'] == 0 || $row['JulioHonorarios'] == 0 || $row['JulioP'] == 0) {print(0);}else{print(round(($row['EneroHonorarios']+$row['FebreroHonorarios']+$row['MarzoHonorarios']+$row['AbrilHonorarios']+$row['MayoC']+$row['JunioHonorarios']+$row['JulioHonorarios'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($julioC,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['AgostoC'],2));?></td>
					<td><?php print(round($row2['costo']*100,2));?>%</td>
					<td>$<?php print(number_format($row['AgostoHonorarios'],2));?></td>
					<td><?php if($row['AgostoHonorarios'] == 0 || $row['AgostoP'] == 0) {print(0);}else{print(round($row['AgostoHonorarios']/$row['AgostoP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['AgostoHonorarios']-$row2['AgostoC'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroC']+$row2['FebreroC']+$row2['MarzoC']+$row2['AbrilC']+$row2['MayoC']+$row2['JunioC']+$row2['JulioC']+$row2['AgostoC'],2));?></td>
					<td><?php print(round(($row2['costo'])*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroHonorarios']+$row['FebreroHonorarios']+$row['MarzoHonorarios']+$row['AbrilHonorarios']+$row['MayoC']+$row['JunioHonorarios']+$row['JulioHonorarios']+$row['AgostoHonorarios'],2));?></td>
					<td><?php if($row['EneroHonorarios'] == 0 || $row['EneroP'] == 0 || $row['FebreroHonorarios'] == 0 || $row['FebreroP'] == 0 || $row['MarzoHonorarios'] == 0 || $row['MarzoP'] == 0 || $row['AbrilHonorarios'] == 0 || $row['AbrilP'] == 0 || $row['MayoC'] == 0 || $row['MayoP'] == 0 || $row['JunioHonorarios'] == 0 || $row['JunioP'] == 0 || $row['JulioHonorarios'] == 0 || $row['JulioP'] == 0 || $row['AgostoHonorarios'] == 0 || $row['AgostoP'] == 0) {print(0);}else{print(round(($row['EneroHonorarios']+$row['FebreroHonorarios']+$row['MarzoHonorarios']+$row['AbrilHonorarios']+$row['MayoC']+$row['JunioHonorarios']+$row['JulioHonorarios']+$row['AgostoHonorarios'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($agostoC,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['SeptiembreC'],2));?></td>
					<td><?php print(round($row2['costo']*100,2));?>%</td>
					<td>$<?php print(number_format($row['SeptiembreHonorarios'],2));?></td>
					<td><?php if($row['SeptiembreHonorarios'] == 0 || $row['SeptiembreP'] == 0) {print(0);}else{print(round($row['SeptiembreHonorarios']/$row['SeptiembreP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['SeptiembreHonorarios']-$row2['SeptiembreC'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroC']+$row2['FebreroC']+$row2['MarzoC']+$row2['AbrilC']+$row2['MayoC']+$row2['JunioC']+$row2['JulioC']+$row2['AgostoC']+$row2['SeptiembreC'],2));?></td>
					<td><?php print(round(($row2['costo'])*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroHonorarios']+$row['FebreroHonorarios']+$row['MarzoHonorarios']+$row['AbrilHonorarios']+$row['MayoC']+$row['JunioHonorarios']+$row['JulioHonorarios']+$row['AgostoHonorarios']+$row['SeptiembreHonorarios'],2));?></td>
					<td><?php if($row['EneroHonorarios'] == 0 || $row['EneroP'] == 0 || $row['FebreroHonorarios'] == 0 || $row['FebreroP'] == 0 || $row['MarzoHonorarios'] == 0 || $row['MarzoP'] == 0 || $row['AbrilHonorarios'] == 0 || $row['AbrilP'] == 0 || $row['MayoC'] == 0 || $row['MayoP'] == 0 || $row['JunioHonorarios'] == 0 || $row['JunioP'] == 0 || $row['JulioHonorarios'] == 0 || $row['JulioP'] == 0 || $row['AgostoHonorarios'] == 0 || $row['AgostoP'] == 0 || $row['SeptiembreHonorarios'] == 0 || $row['SeptiembreP'] == 0) {print(0);}else{print(round(($row['EneroHonorarios']+$row['FebreroHonorarios']+$row['MarzoHonorarios']+$row['AbrilHonorarios']+$row['MayoC']+$row['JunioHonorarios']+$row['JulioHonorarios']+$row['AgostoHonorarios']+$row['SeptiembreHonorarios'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($septiembreC,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['OctubreC'],2));?></td>
					<td><?php print(round($row2['costo']*100,2));?>%</td>
					<td>$<?php print(number_format($row['OctubreHonorarios'],2));?></td>
					<td><?php if($row['OctubreHonorarios'] == 0 || $row['OctubreP'] == 0) {print(0);}else{print(round($row['OctubreHonorarios']/$row['OctubreP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['OctubreHonorarios']-$row2['OctubreC'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroC']+$row2['FebreroC']+$row2['MarzoC']+$row2['AbrilC']+$row2['MayoC']+$row2['JunioC']+$row2['JulioC']+$row2['AgostoC']+$row2['SeptiembreC']+$row2['OctubreC'],2));?></td>
					<td><?php print(round(($row2['costo'])*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroHonorarios']+$row['FebreroHonorarios']+$row['MarzoHonorarios']+$row['AbrilHonorarios']+$row['MayoC']+$row['JunioHonorarios']+$row['JulioHonorarios']+$row['AgostoHonorarios']+$row['SeptiembreHonorarios']+$row['OctubreHonorarios'],2));?></td>
					<td><?php if($row['EneroHonorarios'] == 0 || $row['EneroP'] == 0 || $row['FebreroHonorarios'] == 0 || $row['FebreroP'] == 0 || $row['MarzoHonorarios'] == 0 || $row['MarzoP'] == 0 || $row['AbrilHonorarios'] == 0 || $row['AbrilP'] == 0 || $row['MayoC'] == 0 || $row['MayoP'] == 0 || $row['JunioHonorarios'] == 0 || $row['JunioP'] == 0 || $row['JulioHonorarios'] == 0 || $row['JulioP'] == 0 || $row['AgostoHonorarios'] == 0 || $row['AgostoP'] == 0 || $row['SeptiembreHonorarios'] == 0 || $row['SeptiembreP'] == 0 || $row['OctubreHonorarios'] == 0 || $row['OctubreP'] == 0) {print(0);}else{print(round(($row['EneroHonorarios']+$row['FebreroHonorarios']+$row['MarzoHonorarios']+$row['AbrilHonorarios']+$row['MayoC']+$row['JunioHonorarios']+$row['JulioHonorarios']+$row['AgostoHonorarios']+$row['SeptiembreHonorarios']+$row['OctubreHonorarios'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($octubreC,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['NoviembreC'],2));?></td>
					<td><?php print(round($row2['costo']*100,2));?>%</td>
					<td>$<?php print(number_format($row['NoviembreHonorarios'],2));?></td>
					<td><?php if($row['NoviembreHonorarios'] == 0 || $row['NoviembreP'] == 0) {print(0);}else{print(round($row['NoviembreHonorarios']/$row['NoviembreP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['NoviembreHonorarios']-$row2['NoviembreC'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroC']+$row2['FebreroC']+$row2['MarzoC']+$row2['AbrilC']+$row2['MayoC']+$row2['JunioC']+$row2['JulioC']+$row2['AgostoC']+$row2['SeptiembreC']+$row2['OctubreC']+$row2['NoviembreC'],2));?></td>
					<td><?php print(round(($row2['costo'])*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroHonorarios']+$row['FebreroHonorarios']+$row['MarzoHonorarios']+$row['AbrilHonorarios']+$row['MayoC']+$row['JunioHonorarios']+$row['JulioHonorarios']+$row['AgostoHonorarios']+$row['SeptiembreHonorarios']+$row['OctubreHonorarios']+$row['NoviembreHonorarios'],2));?></td>
					<td><?php if($row['EneroHonorarios'] == 0 || $row['EneroP'] == 0 || $row['FebreroHonorarios'] == 0 || $row['FebreroP'] == 0 || $row['MarzoHonorarios'] == 0 || $row['MarzoP'] == 0 || $row['AbrilHonorarios'] == 0 || $row['AbrilP'] == 0 || $row['MayoC'] == 0 || $row['MayoP'] == 0 || $row['JunioHonorarios'] == 0 || $row['JunioP'] == 0 || $row['JulioHonorarios'] == 0 || $row['JulioP'] == 0 || $row['AgostoHonorarios'] == 0 || $row['AgostoP'] == 0 || $row['SeptiembreHonorarios'] == 0 || $row['SeptiembreP'] == 0 || $row['OctubreHonorarios'] == 0 || $row['OctubreP'] == 0 || $row['NoviembreHonorarios'] == 0 || $row['NoviembreP'] == 0) {print(0);}else{print(round(($row['EneroHonorarios']+$row['FebreroHonorarios']+$row['MarzoHonorarios']+$row['AbrilHonorarios']+$row['MayoC']+$row['JunioHonorarios']+$row['JulioHonorarios']+$row['AgostoHonorarios']+$row['SeptiembreHonorarios']+$row['OctubreHonorarios']+$row['NoviembreHonorarios'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP']+$row['NoviembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($noviembreC,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['DiciembreC'],2));?></td>
					<td><?php print(round($row2['costo']*100,2));?>%</td>
					<td>$<?php print(number_format($row['DiciembreHonorarios'],2));?></td>
					<td><?php if($row['DiciembreHonorarios'] == 0 || $row['DiciembreP'] == 0) {print(0);}else{print(round($row['DiciembreHonorarios']/$row['DiciembreP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['DiciembreHonorarios']-$row2['DiciembreC'],2));?></td>
				</tr>
				<!--COSTOS HONORARIOS-->
				<tr>
					<th>Costos Materiales</th>
					<td>$<?php print(number_format(0,2));?></td>
					<td><?php print(round(0,2));?>%</td>
					<td></td>
					<td>$<?php print(number_format(0,2));?></td>
					<td><?php print(round(0*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroMateriales'],2));?></td>
					<td><?php if($row['EneroMateriales'] == 0 || $row['EneroP'] == 0) {print(0);}else{print(round($row['EneroMateriales']/$row['EneroP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['EneroMateriales']-0,2));?></td>
					<td></td>
					<td>$<?php print(number_format(0,2));?></td>
					<td><?php print(round(0*100,2));?>%</td>
					<td>$<?php print(number_format($row['FebreroMateriales'],2));?></td>
					<td><?php if($row['FebreroMateriales'] == 0 || $row['FebreroP'] == 0) {print(0);}else{print(round($row['FebreroMateriales']/$row['FebreroP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['FebreroMateriales']-0,2));?></td>
					<td></td>
					<td>$<?php print(number_format(0+0,2));?></td>
					<td><?php print(round((0)*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroMateriales']+$row['FebreroMateriales'],2));?></td>
					<td><?php if($row['EneroMateriales'] == 0 || $row['EneroP'] == 0 || $row['FebreroMateriales'] == 0 || $row['FebreroP'] == 0) {print(0);}else{print(round(($row['EneroMateriales']+$row['FebreroMateriales'])/($row['EneroP']+$row['FebreroP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($febreroC*-1,2));?></td>
					<td></td>
					<td>$<?php print(number_format(0,2));?></td>
					<td><?php print(round((0)*100,2));?>%</td>
					<td>$<?php print(number_format($row['MarzoMateriales'],2));?></td>
					<td><?php if($row['MarzoMateriales'] == 0 || $row['MarzoP'] == 0) {print(0);}else{print(round($row['MarzoMateriales']/$row['MarzoP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['MarzoMateriales']-0,2));?></td>
					<td></td>
					<td>$<?php print(number_format(0+0+0,2));?></td>
					<td><?php print(round((0)*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroMateriales']+$row['FebreroMateriales']+$row['MarzoMateriales'],2));?></td>
					<td><?php if($row['EneroMateriales'] == 0 || $row['EneroP'] == 0 || $row['FebreroMateriales'] == 0 || $row['FebreroP'] == 0 || $row['MarzoMateriales'] == 0 || $row['MarzoP'] == 0) {print(0);}else{print(round(($row['EneroMateriales']+$row['FebreroMateriales']+$row['MarzoMateriales'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($marzoC*-1,2));?></td>
					<td></td>
					<td>$<?php print(number_format(0,2));?></td>
					<td><?php print(round(0*100,2));?>%</td>
					<td>$<?php print(number_format($row['AbrilMateriales'],2));?></td>
					<td><?php if($row['AbrilMateriales'] == 0 || $row['AbrilP'] == 0) {print(0);}else{print(round($row['AbrilMateriales']/$row['AbrilP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['AbrilMateriales']-0,2));?></td>
					<td></td>
					<td>$<?php print(number_format(0+0+0+0,2));?></td>
					<td><?php print(round((0)*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroMateriales']+$row['FebreroMateriales']+$row['MarzoMateriales']+$row['AbrilMateriales'],2));?></td>
					<td><?php if($row['EneroMateriales'] == 0 || $row['EneroP'] == 0 || $row['FebreroMateriales'] == 0 || $row['FebreroP'] == 0 || $row['MarzoMateriales'] == 0 || $row['MarzoP'] == 0 || $row['AbrilMateriales'] == 0 || $row['AbrilP'] == 0) {print(0);}else{print(round(($row['EneroMateriales']+$row['FebreroMateriales']+$row['MarzoMateriales']+$row['AbrilMateriales'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($abrilC,2));?></td>
					<td></td>
					<td>$<?php print(number_format(0,2));?></td>
					<td><?php print(round(0*100,2));?>%</td>
					<td>$<?php print(number_format($row['MayoC'],2));?></td>
					<td><?php if($row['MayoC'] == 0 || $row['MayoP'] == 0) {print(0);}else{print(round($row['MayoC']/$row['MayoP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['MayoC']-0,2));?></td>
					<td></td>
					<td>$<?php print(number_format(0+0+0+0+0,2));?></td>
					<td><?php print(round((0)*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroMateriales']+$row['FebreroMateriales']+$row['MarzoMateriales']+$row['AbrilMateriales']+$row['MayoC'],2));?></td>
					<td><?php if($row['EneroMateriales'] == 0 || $row['EneroP'] == 0 || $row['FebreroMateriales'] == 0 || $row['FebreroP'] == 0 || $row['MarzoMateriales'] == 0 || $row['MarzoP'] == 0 || $row['AbrilMateriales'] == 0 || $row['AbrilP'] == 0 || $row['MayoC'] == 0 || $row['MayoP'] == 0) {print(0);}else{print(round(($row['EneroMateriales']+$row['FebreroMateriales']+$row['MarzoMateriales']+$row['AbrilMateriales']+$row['MayoC'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($mayoC,2));?></td>					
					<td></td>
					<td>$<?php print(number_format(0,2));?></td>
					<td><?php print(round(0*100,2));?>%</td>
					<td>$<?php print(number_format($row['JunioMateriales'],2));?></td>
					<td><?php if($row['JunioMateriales'] == 0 || $row['JunioP'] == 0) {print(0);}else{print(round($row['JunioMateriales']/$row['JunioP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['JunioMateriales']-0,2));?></td>
					<td></td>
					<td>$<?php print(number_format(0+0+0+0+0+0,2));?></td>
					<td><?php print(round((0)*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroMateriales']+$row['FebreroMateriales']+$row['MarzoMateriales']+$row['AbrilMateriales']+$row['MayoC']+$row['JunioMateriales'],2));?></td>
					<td><?php if($row['EneroMateriales'] == 0 || $row['EneroP'] == 0 || $row['FebreroMateriales'] == 0 || $row['FebreroP'] == 0 || $row['MarzoMateriales'] == 0 || $row['MarzoP'] == 0 || $row['AbrilMateriales'] == 0 || $row['AbrilP'] == 0 || $row['MayoC'] == 0 || $row['MayoP'] == 0 || $row['JunioMateriales'] == 0 || $row['JunioP'] == 0) {print(0);}else{print(round(($row['EneroMateriales']+$row['FebreroMateriales']+$row['MarzoMateriales']+$row['AbrilMateriales']+$row['MayoC']+$row['JunioMateriales'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($junioC,2));?></td>
					<td></td>
					<td>$<?php print(number_format(0,2));?></td>
					<td><?php print(round(0*100,2));?>%</td>
					<td>$<?php print(number_format($row['JulioMateriales'],2));?></td>
					<td><?php if($row['JulioMateriales'] == 0 || $row['JulioP'] == 0) {print(0);}else{print(round($row['JulioMateriales']/$row['JulioP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['JulioMateriales']-0,2));?></td>
					<td></td>
					<td>$<?php print(number_format(0+0+0+0+0+0+0,2));?></td>
					<td><?php print(round((0)*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroMateriales']+$row['FebreroMateriales']+$row['MarzoMateriales']+$row['AbrilMateriales']+$row['MayoC']+$row['JunioMateriales']+$row['JulioMateriales'],2));?></td>
					<td><?php if($row['EneroMateriales'] == 0 || $row['EneroP'] == 0 || $row['FebreroMateriales'] == 0 || $row['FebreroP'] == 0 || $row['MarzoMateriales'] == 0 || $row['MarzoP'] == 0 || $row['AbrilMateriales'] == 0 || $row['AbrilP'] == 0 || $row['MayoC'] == 0 || $row['MayoP'] == 0 || $row['JunioMateriales'] == 0 || $row['JunioP'] == 0 || $row['JulioMateriales'] == 0 || $row['JulioP'] == 0) {print(0);}else{print(round(($row['EneroMateriales']+$row['FebreroMateriales']+$row['MarzoMateriales']+$row['AbrilMateriales']+$row['MayoC']+$row['JunioMateriales']+$row['JulioMateriales'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($julioC,2));?></td>
					<td></td>
					<td>$<?php print(number_format(0,2));?></td>
					<td><?php print(round(0*100,2));?>%</td>
					<td>$<?php print(number_format($row['AgostoMateriales'],2));?></td>
					<td><?php if($row['AgostoMateriales'] == 0 || $row['AgostoP'] == 0) {print(0);}else{print(round($row['AgostoMateriales']/$row['AgostoP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['AgostoMateriales']-0,2));?></td>
					<td></td>
					<td>$<?php print(number_format(0+0+0+0+0+0+0+0,2));?></td>
					<td><?php print(round((0)*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroMateriales']+$row['FebreroMateriales']+$row['MarzoMateriales']+$row['AbrilMateriales']+$row['MayoC']+$row['JunioMateriales']+$row['JulioMateriales']+$row['AgostoMateriales'],2));?></td>
					<td><?php if($row['EneroMateriales'] == 0 || $row['EneroP'] == 0 || $row['FebreroMateriales'] == 0 || $row['FebreroP'] == 0 || $row['MarzoMateriales'] == 0 || $row['MarzoP'] == 0 || $row['AbrilMateriales'] == 0 || $row['AbrilP'] == 0 || $row['MayoC'] == 0 || $row['MayoP'] == 0 || $row['JunioMateriales'] == 0 || $row['JunioP'] == 0 || $row['JulioMateriales'] == 0 || $row['JulioP'] == 0 || $row['AgostoMateriales'] == 0 || $row['AgostoP'] == 0) {print(0);}else{print(round(($row['EneroMateriales']+$row['FebreroMateriales']+$row['MarzoMateriales']+$row['AbrilMateriales']+$row['MayoC']+$row['JunioMateriales']+$row['JulioMateriales']+$row['AgostoMateriales'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($agostoC,2));?></td>
					<td></td>
					<td>$<?php print(number_format(0,2));?></td>
					<td><?php print(round(0*100,2));?>%</td>
					<td>$<?php print(number_format($row['SeptiembreMateriales'],2));?></td>
					<td><?php if($row['SeptiembreMateriales'] == 0 || $row['SeptiembreP'] == 0) {print(0);}else{print(round($row['SeptiembreMateriales']/$row['SeptiembreP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['SeptiembreMateriales']-0,2));?></td>
					<td></td>
					<td>$<?php print(number_format(0+0+0+0+0+0+0+0+0,2));?></td>
					<td><?php print(round((0)*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroMateriales']+$row['FebreroMateriales']+$row['MarzoMateriales']+$row['AbrilMateriales']+$row['MayoC']+$row['JunioMateriales']+$row['JulioMateriales']+$row['AgostoMateriales']+$row['SeptiembreMateriales'],2));?></td>
					<td><?php if($row['EneroMateriales'] == 0 || $row['EneroP'] == 0 || $row['FebreroMateriales'] == 0 || $row['FebreroP'] == 0 || $row['MarzoMateriales'] == 0 || $row['MarzoP'] == 0 || $row['AbrilMateriales'] == 0 || $row['AbrilP'] == 0 || $row['MayoC'] == 0 || $row['MayoP'] == 0 || $row['JunioMateriales'] == 0 || $row['JunioP'] == 0 || $row['JulioMateriales'] == 0 || $row['JulioP'] == 0 || $row['AgostoMateriales'] == 0 || $row['AgostoP'] == 0 || $row['SeptiembreMateriales'] == 0 || $row['SeptiembreP'] == 0) {print(0);}else{print(round(($row['EneroMateriales']+$row['FebreroMateriales']+$row['MarzoMateriales']+$row['AbrilMateriales']+$row['MayoC']+$row['JunioMateriales']+$row['JulioMateriales']+$row['AgostoMateriales']+$row['SeptiembreMateriales'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($septiembreC,2));?></td>
					<td></td>
					<td>$<?php print(number_format(0,2));?></td>
					<td><?php print(round(0*100,2));?>%</td>
					<td>$<?php print(number_format($row['OctubreMateriales'],2));?></td>
					<td><?php if($row['OctubreMateriales'] == 0 || $row['OctubreP'] == 0) {print(0);}else{print(round($row['OctubreMateriales']/$row['OctubreP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['OctubreMateriales']-0,2));?></td>
					<td></td>
					<td>$<?php print(number_format(0+0+0+0+0+0+0+0+0+0,2));?></td>
					<td><?php print(round((0)*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroMateriales']+$row['FebreroMateriales']+$row['MarzoMateriales']+$row['AbrilMateriales']+$row['MayoC']+$row['JunioMateriales']+$row['JulioMateriales']+$row['AgostoMateriales']+$row['SeptiembreMateriales']+$row['OctubreMateriales'],2));?></td>
					<td><?php if($row['EneroMateriales'] == 0 || $row['EneroP'] == 0 || $row['FebreroMateriales'] == 0 || $row['FebreroP'] == 0 || $row['MarzoMateriales'] == 0 || $row['MarzoP'] == 0 || $row['AbrilMateriales'] == 0 || $row['AbrilP'] == 0 || $row['MayoC'] == 0 || $row['MayoP'] == 0 || $row['JunioMateriales'] == 0 || $row['JunioP'] == 0 || $row['JulioMateriales'] == 0 || $row['JulioP'] == 0 || $row['AgostoMateriales'] == 0 || $row['AgostoP'] == 0 || $row['SeptiembreMateriales'] == 0 || $row['SeptiembreP'] == 0 || $row['OctubreMateriales'] == 0 || $row['OctubreP'] == 0) {print(0);}else{print(round(($row['EneroMateriales']+$row['FebreroMateriales']+$row['MarzoMateriales']+$row['AbrilMateriales']+$row['MayoC']+$row['JunioMateriales']+$row['JulioMateriales']+$row['AgostoMateriales']+$row['SeptiembreMateriales']+$row['OctubreMateriales'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($octubreC,2));?></td>
					<td></td>
					<td>$<?php print(number_format(0,2));?></td>
					<td><?php print(round(0*100,2));?>%</td>
					<td>$<?php print(number_format($row['NoviembreMateriales'],2));?></td>
					<td><?php if($row['NoviembreMateriales'] == 0 || $row['NoviembreP'] == 0) {print(0);}else{print(round($row['NoviembreMateriales']/$row['NoviembreP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['NoviembreMateriales']-0,2));?></td>
					<td></td>
					<td>$<?php print(number_format(0+0+0+0+0+0+0+0+0+0+0,2));?></td>
					<td><?php print(round((0)*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroMateriales']+$row['FebreroMateriales']+$row['MarzoMateriales']+$row['AbrilMateriales']+$row['MayoC']+$row['JunioMateriales']+$row['JulioMateriales']+$row['AgostoMateriales']+$row['SeptiembreMateriales']+$row['OctubreMateriales']+$row['NoviembreMateriales'],2));?></td>
					<td><?php if($row['EneroMateriales'] == 0 || $row['EneroP'] == 0 || $row['FebreroMateriales'] == 0 || $row['FebreroP'] == 0 || $row['MarzoMateriales'] == 0 || $row['MarzoP'] == 0 || $row['AbrilMateriales'] == 0 || $row['AbrilP'] == 0 || $row['MayoC'] == 0 || $row['MayoP'] == 0 || $row['JunioMateriales'] == 0 || $row['JunioP'] == 0 || $row['JulioMateriales'] == 0 || $row['JulioP'] == 0 || $row['AgostoMateriales'] == 0 || $row['AgostoP'] == 0 || $row['SeptiembreMateriales'] == 0 || $row['SeptiembreP'] == 0 || $row['OctubreMateriales'] == 0 || $row['OctubreP'] == 0 || $row['NoviembreMateriales'] == 0 || $row['NoviembreP'] == 0) {print(0);}else{print(round(($row['EneroMateriales']+$row['FebreroMateriales']+$row['MarzoMateriales']+$row['AbrilMateriales']+$row['MayoC']+$row['JunioMateriales']+$row['JulioMateriales']+$row['AgostoMateriales']+$row['SeptiembreMateriales']+$row['OctubreMateriales']+$row['NoviembreMateriales'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP']+$row['NoviembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($noviembreC,2));?></td>
					<td></td>
					<td>$<?php print(number_format(0,2));?></td>
					<td><?php print(round(0*100,2));?>%</td>
					<td>$<?php print(number_format($row['DiciembreMateriales'],2));?></td>
					<td><?php if($row['DiciembreMateriales'] == 0 || $row['DiciembreP'] == 0) {print(0);}else{print(round($row['DiciembreMateriales']/$row['DiciembreP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['DiciembreMateriales']-0,2));?></td>
				</tr>
				
				<!--COSTOS-->
				<tr>
					<th >Costos</th>
					<td>$<?php print(number_format($row2['TOTALC'],2));?></td>
					<td><?php print(round(0*100,2));?>%</td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroC'],2));?></td>
					<td><?php print(round($row2['costo']*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroC'],2));?></td>
					<td><?php if($row['EneroC'] == 0 || $row['EneroP'] == 0) {print(0);}else{print(round($row['EneroC']/$row['EneroP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['EneroC']-$row2['EneroC'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['FebreroC'],2));?></td>
					<td><?php print(round($row2['costo']*100,2));?>%</td>
					<td>$<?php print(number_format($row['FebreroC'],2));?></td>
					<td><?php if($row['FebreroC'] == 0 || $row['FebreroP'] == 0) {print(0);}else{print(round($row['FebreroC']/$row['FebreroP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['FebreroC']-$row2['FebreroC'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroC']+$row2['FebreroC'],2));?></td>
					<td><?php print(round(($row2['costo'])*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroC']+$row['FebreroC'],2));?></td>
					<td><?php if($row['EneroC'] == 0 || $row['EneroP'] == 0 || $row['FebreroC'] == 0 || $row['FebreroP'] == 0) {print(0);}else{print(round(($row['EneroC']+$row['FebreroC'])/($row['EneroP']+$row['FebreroP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($febreroC*-1,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['MarzoC'],2));?></td>
					<td><?php print(round(($row2['costo'])*100,2));?>%</td>
					<td>$<?php print(number_format($row['MarzoC'],2));?></td>
					<td><?php if($row['MarzoC'] == 0 || $row['MarzoP'] == 0) {print(0);}else{print(round($row['MarzoC']/$row['MarzoP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['MarzoC']-$row2['MarzoC'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroC']+$row2['FebreroC']+$row2['MarzoC'],2));?></td>
					<td><?php print(round(($row2['costo'])*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroC']+$row['FebreroC']+$row['MarzoC'],2));?></td>
					<td><?php if($row['EneroC'] == 0 || $row['EneroP'] == 0 || $row['FebreroC'] == 0 || $row['FebreroP'] == 0 || $row['MarzoC'] == 0 || $row['MarzoP'] == 0) {print(0);}else{print(round(($row['EneroC']+$row['FebreroC']+$row['MarzoC'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($marzoC*-1,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['AbrilC'],2));?></td>
					<td><?php print(round($row2['costo']*100,2));?>%</td>
					<td>$<?php print(number_format($row['AbrilC'],2));?></td>
					<td><?php if($row['AbrilC'] == 0 || $row['AbrilP'] == 0) {print(0);}else{print(round($row['AbrilC']/$row['AbrilP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['AbrilC']-$row2['AbrilC'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroC']+$row2['FebreroC']+$row2['MarzoC']+$row2['AbrilC'],2));?></td>
					<td><?php print(round(($row2['costo'])*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroC']+$row['FebreroC']+$row['MarzoC']+$row['AbrilC'],2));?></td>
					<td><?php if($row['EneroC'] == 0 || $row['EneroP'] == 0 || $row['FebreroC'] == 0 || $row['FebreroP'] == 0 || $row['MarzoC'] == 0 || $row['MarzoP'] == 0 || $row['AbrilC'] == 0 || $row['AbrilP'] == 0) {print(0);}else{print(round(($row['EneroC']+$row['FebreroC']+$row['MarzoC']+$row['AbrilC'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($abrilC,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['MayoC'],2));?></td>
					<td><?php print(round($row2['costo']*100,2));?>%</td>
					<td>$<?php print(number_format($row['MayoC'],2));?></td>
					<td><?php if($row['MayoC'] == 0 || $row['MayoP'] == 0) {print(0);}else{print(round($row['MayoC']/$row['MayoP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['MayoC']-$row2['MayoC'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroC']+$row2['FebreroC']+$row2['MarzoC']+$row2['AbrilC']+$row2['MayoC'],2));?></td>
					<td><?php print(round(($row2['costo'])*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroC']+$row['FebreroC']+$row['MarzoC']+$row['AbrilC']+$row['MayoC'],2));?></td>
					<td><?php if($row['EneroC'] == 0 || $row['EneroP'] == 0 || $row['FebreroC'] == 0 || $row['FebreroP'] == 0 || $row['MarzoC'] == 0 || $row['MarzoP'] == 0 || $row['AbrilC'] == 0 || $row['AbrilP'] == 0 || $row['MayoC'] == 0 || $row['MayoP'] == 0) {print(0);}else{print(round(($row['EneroC']+$row['FebreroC']+$row['MarzoC']+$row['AbrilC']+$row['MayoC'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($mayoC,2));?></td>					
					<td></td>
					<td>$<?php print(number_format($row2['JunioC'],2));?></td>
					<td><?php print(round($row2['costo']*100,2));?>%</td>
					<td>$<?php print(number_format($row['JunioC'],2));?></td>
					<td><?php if($row['JunioC'] == 0 || $row['JunioP'] == 0) {print(0);}else{print(round($row['JunioC']/$row['JunioP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['JunioC']-$row2['JunioC'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroC']+$row2['FebreroC']+$row2['MarzoC']+$row2['AbrilC']+$row2['MayoC']+$row2['JunioC'],2));?></td>
					<td><?php print(round(($row2['costo'])*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroC']+$row['FebreroC']+$row['MarzoC']+$row['AbrilC']+$row['MayoC']+$row['JunioC'],2));?></td>
					<td><?php if($row['EneroC'] == 0 || $row['EneroP'] == 0 || $row['FebreroC'] == 0 || $row['FebreroP'] == 0 || $row['MarzoC'] == 0 || $row['MarzoP'] == 0 || $row['AbrilC'] == 0 || $row['AbrilP'] == 0 || $row['MayoC'] == 0 || $row['MayoP'] == 0 || $row['JunioC'] == 0 || $row['JunioP'] == 0) {print(0);}else{print(round(($row['EneroC']+$row['FebreroC']+$row['MarzoC']+$row['AbrilC']+$row['MayoC']+$row['JunioC'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($junioC,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['JulioC'],2));?></td>
					<td><?php print(round($row2['costo']*100,2));?>%</td>
					<td>$<?php print(number_format($row['JulioC'],2));?></td>
					<td><?php if($row['JulioC'] == 0 || $row['JulioP'] == 0) {print(0);}else{print(round($row['JulioC']/$row['JulioP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['JulioC']-$row2['JulioC'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroC']+$row2['FebreroC']+$row2['MarzoC']+$row2['AbrilC']+$row2['MayoC']+$row2['JunioC']+$row2['JulioC'],2));?></td>
					<td><?php print(round(($row2['costo'])*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroC']+$row['FebreroC']+$row['MarzoC']+$row['AbrilC']+$row['MayoC']+$row['JunioC']+$row['JulioC'],2));?></td>
					<td><?php if($row['EneroC'] == 0 || $row['EneroP'] == 0 || $row['FebreroC'] == 0 || $row['FebreroP'] == 0 || $row['MarzoC'] == 0 || $row['MarzoP'] == 0 || $row['AbrilC'] == 0 || $row['AbrilP'] == 0 || $row['MayoC'] == 0 || $row['MayoP'] == 0 || $row['JunioC'] == 0 || $row['JunioP'] == 0 || $row['JulioC'] == 0 || $row['JulioP'] == 0) {print(0);}else{print(round(($row['EneroC']+$row['FebreroC']+$row['MarzoC']+$row['AbrilC']+$row['MayoC']+$row['JunioC']+$row['JulioC'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($julioC,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['AgostoC'],2));?></td>
					<td><?php print(round($row2['costo']*100,2));?>%</td>
					<td>$<?php print(number_format($row['AgostoC'],2));?></td>
					<td><?php if($row['AgostoC'] == 0 || $row['AgostoP'] == 0) {print(0);}else{print(round($row['AgostoC']/$row['AgostoP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['AgostoC']-$row2['AgostoC'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroC']+$row2['FebreroC']+$row2['MarzoC']+$row2['AbrilC']+$row2['MayoC']+$row2['JunioC']+$row2['JulioC']+$row2['AgostoC'],2));?></td>
					<td><?php print(round(($row2['costo'])*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroC']+$row['FebreroC']+$row['MarzoC']+$row['AbrilC']+$row['MayoC']+$row['JunioC']+$row['JulioC']+$row['AgostoC'],2));?></td>
					<td><?php if($row['EneroC'] == 0 || $row['EneroP'] == 0 || $row['FebreroC'] == 0 || $row['FebreroP'] == 0 || $row['MarzoC'] == 0 || $row['MarzoP'] == 0 || $row['AbrilC'] == 0 || $row['AbrilP'] == 0 || $row['MayoC'] == 0 || $row['MayoP'] == 0 || $row['JunioC'] == 0 || $row['JunioP'] == 0 || $row['JulioC'] == 0 || $row['JulioP'] == 0 || $row['AgostoC'] == 0 || $row['AgostoP'] == 0) {print(0);}else{print(round(($row['EneroC']+$row['FebreroC']+$row['MarzoC']+$row['AbrilC']+$row['MayoC']+$row['JunioC']+$row['JulioC']+$row['AgostoC'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($agostoC,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['SeptiembreC'],2));?></td>
					<td><?php print(round($row2['costo']*100,2));?>%</td>
					<td>$<?php print(number_format($row['SeptiembreC'],2));?></td>
					<td><?php if($row['SeptiembreC'] == 0 || $row['SeptiembreP'] == 0) {print(0);}else{print(round($row['SeptiembreC']/$row['SeptiembreP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['SeptiembreC']-$row2['SeptiembreC'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroC']+$row2['FebreroC']+$row2['MarzoC']+$row2['AbrilC']+$row2['MayoC']+$row2['JunioC']+$row2['JulioC']+$row2['AgostoC']+$row2['SeptiembreC'],2));?></td>
					<td><?php print(round(($row2['costo'])*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroC']+$row['FebreroC']+$row['MarzoC']+$row['AbrilC']+$row['MayoC']+$row['JunioC']+$row['JulioC']+$row['AgostoC']+$row['SeptiembreC'],2));?></td>
					<td><?php if($row['EneroC'] == 0 || $row['EneroP'] == 0 || $row['FebreroC'] == 0 || $row['FebreroP'] == 0 || $row['MarzoC'] == 0 || $row['MarzoP'] == 0 || $row['AbrilC'] == 0 || $row['AbrilP'] == 0 || $row['MayoC'] == 0 || $row['MayoP'] == 0 || $row['JunioC'] == 0 || $row['JunioP'] == 0 || $row['JulioC'] == 0 || $row['JulioP'] == 0 || $row['AgostoC'] == 0 || $row['AgostoP'] == 0 || $row['SeptiembreC'] == 0 || $row['SeptiembreP'] == 0) {print(0);}else{print(round(($row['EneroC']+$row['FebreroC']+$row['MarzoC']+$row['AbrilC']+$row['MayoC']+$row['JunioC']+$row['JulioC']+$row['AgostoC']+$row['SeptiembreC'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($septiembreC,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['OctubreC'],2));?></td>
					<td><?php print(round($row2['costo']*100,2));?>%</td>
					<td>$<?php print(number_format($row['OctubreHonorarios'],2));?></td>
					<td><?php if($row['OctubreC'] == 0 || $row['OctubreP'] == 0) {print(0);}else{print(round($row['OctubreC']/$row['OctubreP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['OctubreC']-$row2['OctubreC'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroC']+$row2['FebreroC']+$row2['MarzoC']+$row2['AbrilC']+$row2['MayoC']+$row2['JunioC']+$row2['JulioC']+$row2['AgostoC']+$row2['SeptiembreC']+$row2['OctubreC'],2));?></td>
					<td><?php print(round(($row2['costo'])*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroC']+$row['FebreroC']+$row['MarzoC']+$row['AbrilC']+$row['MayoC']+$row['JunioC']+$row['JulioC']+$row['AgostoC']+$row['SeptiembreC']+$row['OctubreC'],2));?></td>
					<td><?php if($row['EneroC'] == 0 || $row['EneroP'] == 0 || $row['FebreroC'] == 0 || $row['FebreroP'] == 0 || $row['MarzoC'] == 0 || $row['MarzoP'] == 0 || $row['AbrilC'] == 0 || $row['AbrilP'] == 0 || $row['MayoC'] == 0 || $row['MayoP'] == 0 || $row['JunioC'] == 0 || $row['JunioP'] == 0 || $row['JulioC'] == 0 || $row['JulioP'] == 0 || $row['AgostoC'] == 0 || $row['AgostoP'] == 0 || $row['SeptiembreC'] == 0 || $row['SeptiembreP'] == 0 || $row['OctubreC'] == 0 || $row['OctubreP'] == 0) {print(0);}else{print(round(($row['EneroC']+$row['FebreroC']+$row['MarzoC']+$row['AbrilC']+$row['MayoC']+$row['JunioC']+$row['JulioC']+$row['AgostoC']+$row['SeptiembreC']+$row['OctubreC'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($octubreC,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['NoviembreC'],2));?></td>
					<td><?php print(round($row2['costo']*100,2));?>%</td>
					<td>$<?php print(number_format($row['NoviembreC'],2));?></td>
					<td><?php if($row['NoviembreC'] == 0 || $row['NoviembreP'] == 0) {print(0);}else{print(round($row['NoviembreC']/$row['NoviembreP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['NoviembreC']-$row2['NoviembreC'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroC']+$row2['FebreroC']+$row2['MarzoC']+$row2['AbrilC']+$row2['MayoC']+$row2['JunioC']+$row2['JulioC']+$row2['AgostoC']+$row2['SeptiembreC']+$row2['OctubreC']+$row2['NoviembreC'],2));?></td>
					<td><?php print(round(($row2['costo'])*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroC']+$row['FebreroC']+$row['MarzoC']+$row['AbrilC']+$row['MayoC']+$row['JunioC']+$row['JulioC']+$row['AgostoC']+$row['SeptiembreC']+$row['OctubreC']+$row['NoviembreC'],2));?></td>
					<td><?php if($row['EneroC'] == 0 || $row['EneroP'] == 0 || $row['FebreroC'] == 0 || $row['FebreroP'] == 0 || $row['MarzoC'] == 0 || $row['MarzoP'] == 0 || $row['AbrilC'] == 0 || $row['AbrilP'] == 0 || $row['MayoC'] == 0 || $row['MayoP'] == 0 || $row['JunioC'] == 0 || $row['JunioP'] == 0 || $row['JulioC'] == 0 || $row['JulioP'] == 0 || $row['AgostoC'] == 0 || $row['AgostoP'] == 0 || $row['SeptiembreC'] == 0 || $row['SeptiembreP'] == 0 || $row['OctubreC'] == 0 || $row['OctubreP'] == 0 || $row['NoviembreC'] == 0 || $row['NoviembreP'] == 0) {print(0);}else{print(round(($row['EneroC']+$row['FebreroC']+$row['MarzoC']+$row['AbrilC']+$row['MayoC']+$row['JunioC']+$row['JulioC']+$row['AgostoC']+$row['SeptiembreC']+$row['OctubreC']+$row['NoviembreC'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP']+$row['NoviembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($noviembreC,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['DiciembreC'],2));?></td>
					<td><?php print(round($row2['costo']*100,2));?>%</td>
					<td>$<?php print(number_format($row['DiciembreC'],2));?></td>
					<td><?php if($row['DiciembreC'] == 0 || $row['DiciembreP'] == 0) {print(0);}else{print(round($row['DiciembreC']/$row['DiciembreP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['DiciembreC']-$row2['DiciembreC'],2));?></td>
				</tr>
			<?php
				if($zona == 7)
				{
					if($row['EneroP'] == 0)
					{
						$row['EneroP'] = 1;
					}
					if($row2['Enero'] == 0)
					{
						$row2['Enero'] = 1;
					}
					if($row['FebreroP'] == 0)
					{
						$row['FebreroP'] = 1;
					}
					if($row['MarzoP'] == 0)
					{
						$row['MarzoP'] = 1;
					}
					if($row['AbrilP'] == 0)
					{
						$row['AbrilP'] = 1;
					}
					if($row['MayoP'] == 0)
					{
						$row['MayoP'] = 1;
					}
					if($row['JunioP'] == 0)
					{
						$row['JunioP'] = 1;
					}
					if($row['JulioP'] == 0)
					{
						$row['JulioP'] = 1;
					}
					if($row['AgostoP'] == 0)
					{
						$row['AgostoP'] = 1;
					}
					if($row['SeptiembreP'] == 0)
					{
						$row['SeptiembreP'] = 1;
					}
					if($row['OctubreP'] == 0)
					{
						$row['OctubreP'] = 1;
					}
					if($row['NoviembreP'] == 0)
					{
						$row['NoviembreP'] = 1;
					}
					if($row['DiciembreP'] == 0)
					{
						$row['DiciembreP'] = 1;
					}
					if($row['TOTALP'] == 0)
					{
						$row['TOTALP'] = 1;
					}
			?>
				<!--GASTO DIRECTO-->
				<tr>
					<th >Gasto Directo</th>
					<td>$<?php print(number_format($row2['TOTALG'],2));?></td>
					<td><?php print(round($row2['gasto']*100,2));?>%</td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroG'],2));?></td>
					<td><?php print(round($row2['EneroG']/$row2['Enero']*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroG'],2));?></td>
					<td><?php print(round($row['EneroG']/$row['EneroP']*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroG']-$row2['EneroG'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['FebreroG'],2));?></td>
					<td><?php print(round($row2['FebreroG']/$row2['Febrero']*100,2));?>%</td>
					<td>$<?php print(number_format($row['FebreroG'],2));?></td>
					<td><?php print(round($row['FebreroG']/$row['FebreroP']*100,2));?>%</td>
					<td>$<?php print(number_format($row['FebreroG']-$row2['FebreroG'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroG']+$row2['FebreroG'],2));?></td>
					<td><?php print(round(($row2['gasto']*2)*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroG']+$row['FebreroG'],2));?></td>
					<td><?php if($row['EneroG'] == 0 || $row['EneroP'] == 0 || $row['FebreroG'] == 0 || $row['FebreroP'] == 0) {print(0);}else{print(round(($row['EneroG']+$row['FebreroG'])/($row['EneroP']+$row['FebreroP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($febreroG*-1,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['MarzoG'],2));?></td>
					<td><?php print(round(($row2['gasto'])*100,2));?>%</td>
					<td>$<?php print(number_format($row['MarzoG'],2));?></td>
					<td><?php if($row['MarzoG'] == 0 || $row['MarzoP'] == 0) {print(0);}else{print(round($row['MarzoG']/$row['MarzoP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['MarzoG']-$row2['MarzoG'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroG']+$row2['FebreroG']+$row2['MarzoG'],2));?></td>
					<td><?php print(round(($row2['gasto']*3)*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroG']+$row['FebreroG']+$row['MarzoG'],2));?></td>
					<td><?php if($row['EneroG'] == 0 || $row['EneroP'] == 0 || $row['FebreroG'] == 0 || $row['FebreroP'] == 0 || $row['MarzoG'] == 0 || $row['MarzoP'] == 0) {print(0);}else{print(round(($row['EneroG']+$row['FebreroG']+$row['MarzoG'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($marzoG*-1,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['AbrilG'],2));?></td>
					<td><?php print(round($row2['gasto']*100,2));?>%</td>
					<td>$<?php print(number_format($row['AbrilG'],2));?></td>
					<td><?php if($row['AbrilG'] == 0 || $row['AbrilP'] == 0) {print(0);}else{print(round($row['AbrilG']/$row['AbrilP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['AbrilG']-$row2['AbrilG'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroG']+$row2['FebreroG']+$row2['MarzoG']+$row2['AbrilG'],2));?></td>
					<td><?php print(round(($row2['gasto']*4)*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroG']+$row['FebreroG']+$row['MarzoG']+$row['AbrilG'],2));?></td>
					<td><?php if($row['EneroG'] == 0 || $row['EneroP'] == 0 || $row['FebreroG'] == 0 || $row['FebreroP'] == 0 || $row['MarzoG'] == 0 || $row['MarzoP'] == 0 || $row['AbrilG'] == 0 || $row['AbrilP'] == 0) {print(0);}else{print(round(($row['EneroG']+$row['FebreroG']+$row['MarzoG']+$row['AbrilG'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($abrilG,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['MayoG'],2));?></td>
					<td><?php print(round($row2['gasto']*100,2));?>%</td>
					<td>$<?php print(number_format($row['MayoG'],2));?></td>
					<td><?php if($row['MayoG'] == 0 || $row['MayoP'] == 0) {print(0);}else{print(round($row['MayoG']/$row['MayoP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['MayoG']-$row2['MayoG'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroG']+$row2['FebreroG']+$row2['MarzoG']+$row2['AbrilG']+$row2['MayoG'],2));?></td>
					<td><?php print(round(($row2['gasto']*5)*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroG']+$row['FebreroG']+$row['MarzoG']+$row['AbrilG']+$row['MayoG'],2));?></td>
					<td><?php if($row['EneroG'] == 0 || $row['EneroP'] == 0 || $row['FebreroG'] == 0 || $row['FebreroP'] == 0 || $row['MarzoG'] == 0 || $row['MarzoP'] == 0 || $row['AbrilG'] == 0 || $row['AbrilP'] == 0 || $row['MayoG'] == 0 || $row['MayoP'] == 0) {print(0);}else{print(round(($row['EneroG']+$row['FebreroG']+$row['MarzoG']+$row['AbrilG']+$row['MayoG'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($mayoG,2));?></td>					
					<td></td>
					<td>$<?php print(number_format($row2['JunioG'],2));?></td>
					<td><?php print(round($row2['gasto']*100,2));?>%</td>
					<td>$<?php print(number_format($row['JunioG'],2));?></td>
					<td><?php if($row['JunioG'] == 0 || $row['JunioP'] == 0) {print(0);}else{print(round($row['JunioG']/$row['JunioP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['JunioG']-$row2['JunioG'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroG']+$row2['FebreroG']+$row2['MarzoG']+$row2['AbrilG']+$row2['MayoG']+$row2['JunioG'],2));?></td>
					<td><?php print(round(($row2['gasto']*6)*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroG']+$row['FebreroG']+$row['MarzoG']+$row['AbrilG']+$row['MayoG']+$row['JunioG'],2));?></td>
					<td><?php if($row['EneroG'] == 0 || $row['EneroP'] == 0 || $row['FebreroG'] == 0 || $row['FebreroP'] == 0 || $row['MarzoG'] == 0 || $row['MarzoP'] == 0 || $row['AbrilG'] == 0 || $row['AbrilP'] == 0 || $row['MayoG'] == 0 || $row['MayoP'] == 0 || $row['JunioG'] == 0 || $row['JunioP'] == 0) {print(0);}else{print(round(($row['EneroG']+$row['FebreroG']+$row['MarzoG']+$row['AbrilG']+$row['MayoG']+$row['JunioG'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($junioG,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['JulioG'],2));?></td>
					<td><?php print(round($row2['gasto']*100,2));?>%</td>
					<td>$<?php print(number_format($row['JulioG'],2));?></td>
					<td><?php if($row['JulioG'] == 0 || $row['JulioP'] == 0) {print(0);}else{print(round($row['JulioG']/$row['JulioP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['JulioG']-$row2['JulioG'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroG']+$row2['FebreroG']+$row2['MarzoG']+$row2['AbrilG']+$row2['MayoG']+$row2['JunioG']+$row2['JulioG'],2));?></td>
					<td><?php print(round(($row2['gasto']*7)*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroG']+$row['FebreroG']+$row['MarzoG']+$row['AbrilG']+$row['MayoG']+$row['JunioG']+$row['JulioG'],2));?></td>
					<td><?php if($row['EneroG'] == 0 || $row['EneroP'] == 0 || $row['FebreroG'] == 0 || $row['FebreroP'] == 0 || $row['MarzoG'] == 0 || $row['MarzoP'] == 0 || $row['AbrilG'] == 0 || $row['AbrilP'] == 0 || $row['MayoG'] == 0 || $row['MayoP'] == 0 || $row['JunioG'] == 0 || $row['JunioP'] == 0 || $row['JulioG'] == 0 || $row['JulioP'] == 0) {print(0);}else{print(round(($row['EneroG']+$row['FebreroG']+$row['MarzoG']+$row['AbrilG']+$row['MayoG']+$row['JunioG']+$row['JulioG'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($julioG,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['AgostoG'],2));?></td>
					<td><?php print(round($row2['gasto']*100,2));?>%</td>
					<td>$<?php print(number_format($row['AgostoG'],2));?></td>
					<td><?php if($row['AgostoG'] == 0 || $row['AgostoP'] == 0) {print(0);}else{print(round($row['AgostoG']/$row['AgostoP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['AgostoG']-$row2['AgostoG'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroG']+$row2['FebreroG']+$row2['MarzoG']+$row2['AbrilG']+$row2['MayoG']+$row2['JunioG']+$row2['JulioG']+$row2['AgostoG'],2));?></td>
					<td><?php print(round(($row2['gasto']*8)*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroG']+$row['FebreroG']+$row['MarzoG']+$row['AbrilG']+$row['MayoG']+$row['JunioG']+$row['JulioG']+$row['AgostoG'],2));?></td>
					<td><?php if($row['EneroG'] == 0 || $row['EneroP'] == 0 || $row['FebreroG'] == 0 || $row['FebreroP'] == 0 || $row['MarzoG'] == 0 || $row['MarzoP'] == 0 || $row['AbrilG'] == 0 || $row['AbrilP'] == 0 || $row['MayoG'] == 0 || $row['MayoP'] == 0 || $row['JunioG'] == 0 || $row['JunioP'] == 0 || $row['JulioG'] == 0 || $row['JulioP'] == 0 || $row['AgostoG'] == 0 || $row['AgostoP'] == 0) {print(0);}else{print(round(($row['EneroG']+$row['FebreroG']+$row['MarzoG']+$row['AbrilG']+$row['MayoG']+$row['JunioG']+$row['JulioG']+$row['AgostoG'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($agostoG,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['SeptiembreG'],2));?></td>
					<td><?php print(round($row2['gasto']*100,2));?>%</td>
					<td>$<?php print(number_format($row['SeptiembreG'],2));?></td>
					<td><?php if($row['SeptiembreG'] == 0 || $row['SeptiembreP'] == 0) {print(0);}else{print(round($row['SeptiembreG']/$row['SeptiembreP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['SeptiembreG']-$row2['SeptiembreG'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroG']+$row2['FebreroG']+$row2['MarzoG']+$row2['AbrilG']+$row2['MayoG']+$row2['JunioG']+$row2['JulioG']+$row2['AgostoG']+$row2['SeptiembreG'],2));?></td>
					<td><?php print(round(($row2['gasto']*9)*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroG']+$row['FebreroG']+$row['MarzoG']+$row['AbrilG']+$row['MayoG']+$row['JunioG']+$row['JulioG']+$row['AgostoG']+$row['SeptiembreG'],2));?></td>
					<td><?php if($row['EneroG'] == 0 || $row['EneroP'] == 0 || $row['FebreroG'] == 0 || $row['FebreroP'] == 0 || $row['MarzoG'] == 0 || $row['MarzoP'] == 0 || $row['AbrilG'] == 0 || $row['AbrilP'] == 0 || $row['MayoG'] == 0 || $row['MayoP'] == 0 || $row['JunioG'] == 0 || $row['JunioP'] == 0 || $row['JulioG'] == 0 || $row['JulioP'] == 0 || $row['AgostoG'] == 0 || $row['AgostoP'] == 0 || $row['SeptiembreG'] == 0 || $row['SeptiembreP'] == 0) {print(0);}else{print(round(($row['EneroG']+$row['FebreroG']+$row['MarzoG']+$row['AbrilG']+$row['MayoG']+$row['JunioG']+$row['JulioG']+$row['AgostoG']+$row['SeptiembreG'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($septiembreG,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['OctubreG'],2));?></td>
					<td><?php print(round($row2['gasto']*100,2));?>%</td>
					<td>$<?php print(number_format($row['OctubreG'],2));?></td>
					<td><?php if($row['OctubreG'] == 0 || $row['OctubreP'] == 0) {print(0);}else{print(round($row['OctubreG']/$row['OctubreP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['OctubreG']-$row2['OctubreG'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroG']+$row2['FebreroG']+$row2['MarzoG']+$row2['AbrilG']+$row2['MayoG']+$row2['JunioG']+$row2['JulioG']+$row2['AgostoG']+$row2['SeptiembreG']+$row2['OctubreG'],2));?></td>
					<td><?php print(round(($row2['gasto']*10)*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroG']+$row['FebreroG']+$row['MarzoG']+$row['AbrilG']+$row['MayoG']+$row['JunioG']+$row['JulioG']+$row['AgostoG']+$row['SeptiembreG']+$row['OctubreG'],2));?></td>
					<td><?php if($row['EneroG'] == 0 || $row['EneroP'] == 0 || $row['FebreroG'] == 0 || $row['FebreroP'] == 0 || $row['MarzoG'] == 0 || $row['MarzoP'] == 0 || $row['AbrilG'] == 0 || $row['AbrilP'] == 0 || $row['MayoG'] == 0 || $row['MayoP'] == 0 || $row['JunioG'] == 0 || $row['JunioP'] == 0 || $row['JulioG'] == 0 || $row['JulioP'] == 0 || $row['AgostoG'] == 0 || $row['AgostoP'] == 0 || $row['SeptiembreG'] == 0 || $row['SeptiembreP'] == 0 || $row['OctubreG'] == 0 || $row['OctubreP'] == 0) {print(0);}else{print(round(($row['EneroG']+$row['FebreroG']+$row['MarzoG']+$row['AbrilG']+$row['MayoG']+$row['JunioG']+$row['JulioG']+$row['AgostoG']+$row['SeptiembreG']+$row['OctubreG'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($octubreG,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['NoviembreG'],2));?></td>
					<td><?php print(round($row2['gasto']*100,2));?>%</td>
					<td>$<?php print(number_format($row['NoviembreG'],2));?></td>
					<td><?php if($row['NoviembreG'] == 0 || $row['NoviembreP'] == 0) {print(0);}else{print(round($row['NoviembreG']/$row['NoviembreP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['NoviembreG']-$row2['NoviembreG'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroG']+$row2['FebreroG']+$row2['MarzoG']+$row2['AbrilG']+$row2['MayoG']+$row2['JunioG']+$row2['JulioG']+$row2['AgostoG']+$row2['SeptiembreG']+$row2['OctubreG']+$row2['NoviembreG'],2));?></td>
					<td><?php print(round(($row2['gasto']*11)*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroG']+$row['FebreroG']+$row['MarzoG']+$row['AbrilG']+$row['MayoG']+$row['JunioG']+$row['JulioG']+$row['AgostoG']+$row['SeptiembreG']+$row['OctubreG']+$row['NoviembreG'],2));?></td>
					<td><?php if($row['EneroG'] == 0 || $row['EneroP'] == 0 || $row['FebreroG'] == 0 || $row['FebreroP'] == 0 || $row['MarzoG'] == 0 || $row['MarzoP'] == 0 || $row['AbrilG'] == 0 || $row['AbrilP'] == 0 || $row['MayoG'] == 0 || $row['MayoP'] == 0 || $row['JunioG'] == 0 || $row['JunioP'] == 0 || $row['JulioG'] == 0 || $row['JulioP'] == 0 || $row['AgostoG'] == 0 || $row['AgostoP'] == 0 || $row['SeptiembreG'] == 0 || $row['SeptiembreP'] == 0 || $row['OctubreG'] == 0 || $row['OctubreP'] == 0 || $row['NoviembreG'] == 0 || $row['NoviembreP'] == 0) {print(0);}else{print(round(($row['EneroG']+$row['FebreroG']+$row['MarzoG']+$row['AbrilG']+$row['MayoG']+$row['JunioG']+$row['JulioG']+$row['AgostoG']+$row['SeptiembreG']+$row['OctubreG']+$row['NoviembreG'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP']+$row['NoviembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($noviembreG,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['DiciembreG'],2));?></td>
					<td><?php print(round($row2['gasto']*100,2));?>%</td>
					<td>$<?php print(number_format($row['DiciembreG'],2));?></td>
					<td><?php if($row['DiciembreG'] == 0 || $row['DiciembreP'] == 0) {print(0);}else{print(round($row['DiciembreG']/$row['DiciembreP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['DiciembreG']-$row2['DiciembreG'],2));?></td>
				</tr>
			<?php
				}
			?>
				<!--MARGEN DIRECTO-->
				<tr>
					<th >Margen Directo</th>
					<td>$<?php print(number_format($row2['TOTAL']-$row2['TOTALC']-$row2['TOTALG'],2));?></td>
					<td><?php print(round(100-($row2['costo']*100),2));?>%</td>
					<td></td>
					<td>$<?php print(number_format($row2['Enero']-$row2['EneroC']-$row2['EneroG'],2));?></td>
					<td><?php print(round(100-(($row2['costo']+$row2['gasto'])*100),2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto1,2));?></td>
					<td><?php print(round($diferenciaMargenDirectoPor1,2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto1-($row2['Enero']-$row2['EneroC']-$row2['EneroG']),2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Febrero']-$row2['FebreroC']-$row2['FebreroG'],2));?></td>
					<td><?php print(round(100-(($row2['costo']+$row2['gasto'])*100),2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto2,2));?></td>
					<td><?php print(round($diferenciaMargenDirectoPor2,2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto2-($row2['Febrero']-$row2['FebreroC']-$row2['FebreroG']),2));?></td>
					<td></td>
					<td>$<?php print(number_format(($row2['Enero']-$row2['EneroC']-$row2['EneroG'])+($row2['Febrero']-$row2['FebreroC']-$row2['FebreroG']),2));?></td>
					<td><?php print(round(100-(($row2['costo']+$row2['gasto'])*100),2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto1+$diferenciaMargenDirecto2,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0){print(0);} else{print(round(($diferenciaMargenDirecto1+$diferenciaMargenDirecto2)/($row['EneroP']+$row['FebreroP'])*100,2));}?>%</td>
					<td>$<?php print(number_format(($febrero-$febreroC-$febreroG)*-1,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Marzo']-$row2['MarzoC']-$row2['MarzoG'],2));?></td>
					<td><?php print(round(100-(($row2['costo']+$row2['gasto'])*100),2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto3,2));?></td>
					<td><?php print(round($diferenciaMargenDirectoPor3,2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto3-($row2['Marzo']-$row2['MarzoC']-$row2['MarzoG']),2));?></td>
					<td></td>
					<td>$<?php print(number_format(($row2['Enero']-$row2['EneroC']-$row2['EneroG'])+($row2['Febrero']-$row2['FebreroC']-$row2['FebreroG'])+($row2['Marzo']-$row2['MarzoC']-$row2['MarzoG']),2));?></td>
					<td><?php print(round(100-(($row2['costo']+$row2['gasto'])*100),2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto1+$diferenciaMargenDirecto2+$diferenciaMargenDirecto3,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0){print(0);} else{print(round(($diferenciaMargenDirecto1+$diferenciaMargenDirecto2+$diferenciaMargenDirecto3)/($row['EneroP']+$row['FebreroP']+$row['MarzoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format(($marzo-$marzoC-$marzoG)*-1,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Abril']-$row2['AbrilC']-$row2['AbrilG'],2));?></td>
					<td><?php print(round(100-(($row2['costo']+$row2['gasto'])*100),2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto4,2));?></td>
					<td><?php print(round($diferenciaMargenDirectoPor4,2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto4-($row2['Abril']-$row2['AbrilC']-$row2['AbrilG']),2));?></td>
					<td></td>
					<td>$<?php print(number_format(($row2['Enero']-$row2['EneroC']-$row2['EneroG'])+($row2['Febrero']-$row2['FebreroC']-$row2['FebreroG'])+($row2['Marzo']-$row2['MarzoC']-$row2['MarzoG'])+($row2['Abril']-$row2['AbrilC']-$row2['AbrilG']),2));?></td>
					<td><?php print(round(100-(($row2['costo']+$row2['gasto'])*100),2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto1+$diferenciaMargenDirecto2+$diferenciaMargenDirecto3+$diferenciaMargenDirecto4,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0){print(0);} else{print(round(($diferenciaMargenDirecto1+$diferenciaMargenDirecto2+$diferenciaMargenDirecto3+$diferenciaMargenDirecto4)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP'])*100,2));}?>%</td>
					<td>$<?php print(number_format(($abril-$abrilC-$abrilG)*-1,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Mayo']-$row2['MayoC']-$row2['MayoG'],2));?></td>
					<td><?php print(round(100-(($row2['costo']+$row2['gasto'])*100),2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto5,2));?></td>
					<td><?php print(round($diferenciaMargenDirectoPor5,2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto5-($row2['Mayo']-$row2['MayoC']-$row2['MayoG']),2));?></td>
					<td></td>
					<td>$<?php print(number_format(($row2['Enero']-$row2['EneroC']-$row2['EneroG'])+($row2['Febrero']-$row2['FebreroC']-$row2['FebreroG'])+($row2['Marzo']-$row2['MarzoC']-$row2['MarzoG'])+($row2['Abril']-$row2['AbrilC']-$row2['AbrilG'])+($row2['Mayo']-$row2['MayoC']-$row2['MayoG']),2));?></td>
					<td><?php print(round(100-(($row2['costo']+$row2['gasto'])*100),2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto1+$diferenciaMargenDirecto2+$diferenciaMargenDirecto3+$diferenciaMargenDirecto4+$diferenciaMargenDirecto5,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0){print(0);} else{print(round(($diferenciaMargenDirecto1+$diferenciaMargenDirecto2+$diferenciaMargenDirecto3+$diferenciaMargenDirecto4+$diferenciaMargenDirecto5)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format(($mayo-$mayoC-$mayoG)*-1,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Junio']-$row2['JunioC']-$row2['JunioG'],2));?></td>
					<td><?php print(round(100-(($row2['costo']+$row2['gasto'])*100),2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto6,2));?></td>
					<td><?php print(round($diferenciaMargenDirectoPor6,2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto6-($row2['Junio']-$row2['JunioC']-$row2['JunioG']),2));?></td>
					<td></td>
					<td>$<?php print(number_format(($row2['Enero']-$row2['EneroC']-$row2['EneroG'])+($row2['Febrero']-$row2['FebreroC']-$row2['FebreroG'])+($row2['Marzo']-$row2['MarzoC']-$row2['MarzoG'])+($row2['Abril']-$row2['AbrilC']-$row2['AbrilG'])+($row2['Mayo']-$row2['MayoC']-$row2['MayoG'])+($row2['Junio']-$row2['JunioC']-$row2['JunioG']),2));?></td>
					<td><?php print(round(100-(($row2['costo']+$row2['gasto'])*100),2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto1+$diferenciaMargenDirecto2+$diferenciaMargenDirecto3+$diferenciaMargenDirecto4+$diferenciaMargenDirecto5+$diferenciaMargenDirecto6,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0){print(0);} else{print(round(($diferenciaMargenDirecto1+$diferenciaMargenDirecto2+$diferenciaMargenDirecto3+$diferenciaMargenDirecto4+$diferenciaMargenDirecto5+$diferenciaMargenDirecto6)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format(($junio-$junioC-$junioG)*-1,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Julio']-$row2['JulioC']-$row2['JulioG'],2));?></td>
					<td><?php print(round(100-(($row2['costo']+$row2['gasto'])*100),2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto7,2));?></td>
					<td><?php print(round($diferenciaMargenDirectoPor7,2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto7-($row2['Julio']-$row2['JulioC']-$row2['JulioG']),2));?></td>
					<td></td>
					<td>$<?php print(number_format(($row2['Enero']-$row2['EneroC']-$row2['EneroG'])+($row2['Febrero']-$row2['FebreroC']-$row2['FebreroG'])+($row2['Marzo']-$row2['MarzoC']-$row2['MarzoG'])+($row2['Abril']-$row2['AbrilC']-$row2['AbrilG'])+($row2['Mayo']-$row2['MayoC']-$row2['MayoG'])+($row2['Junio']-$row2['JunioC']-$row2['JunioG'])+($row2['Julio']-$row2['JulioC']-$row2['JulioG']),2));?></td>
					<td><?php print(round(100-(($row2['costo']+$row2['gasto'])*100),2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto1+$diferenciaMargenDirecto2+$diferenciaMargenDirecto3+$diferenciaMargenDirecto4+$diferenciaMargenDirecto5+$diferenciaMargenDirecto6+$diferenciaMargenDirecto7,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0 || $row['JulioP'] == 0){print(0);} else{print(round(($diferenciaMargenDirecto1+$diferenciaMargenDirecto2+$diferenciaMargenDirecto3+$diferenciaMargenDirecto4+$diferenciaMargenDirecto5+$diferenciaMargenDirecto6+$diferenciaMargenDirecto7)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format(($julio-$julioC-$julioG)*-1,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Agosto']-$row2['AgostoC']-$row2['AgostoG'],2));?></td>
					<td><?php print(round(100-(($row2['costo']+$row2['gasto'])*100),2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto8,2));?></td>
					<td><?php print(round($diferenciaMargenDirectoPor8,2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto8-($row2['Agosto']-$row2['AgostoC']-$row2['AgostoG']),2));?></td>
					<td></td>
					<td>$<?php print(number_format(($row2['Enero']-$row2['EneroC']-$row2['EneroG'])+($row2['Febrero']-$row2['FebreroC']-$row2['FebreroG'])+($row2['Marzo']-$row2['MarzoC']-$row2['MarzoG'])+($row2['Abril']-$row2['AbrilC']-$row2['AbrilG'])+($row2['Mayo']-$row2['MayoC']-$row2['MayoG'])+($row2['Junio']-$row2['JunioC']-$row2['JunioG'])+($row2['Julio']-$row2['JulioC']-$row2['JulioG'])+($row2['Agosto']-$row2['AgostoC']-$row2['AgostoG']),2));?></td>
					<td><?php print(round(100-(($row2['costo']+$row2['gasto'])*100),2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto1+$diferenciaMargenDirecto2+$diferenciaMargenDirecto3+$diferenciaMargenDirecto4+$diferenciaMargenDirecto5+$diferenciaMargenDirecto6+$diferenciaMargenDirecto7+$diferenciaMargenDirecto8,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0 || $row['JulioP'] == 0 || $row['AgostoP'] == 0){print(0);} else{print(round(($diferenciaMargenDirecto1+$diferenciaMargenDirecto2+$diferenciaMargenDirecto3+$diferenciaMargenDirecto4+$diferenciaMargenDirecto5+$diferenciaMargenDirecto6+$diferenciaMargenDirecto7+$diferenciaMargenDirecto8)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format(($agosto-$agostoC-$agostoG)*-1,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Septiembre']-$row2['SeptiembreC']-$row2['SeptiembreG'],2));?></td>
					<td><?php print(round(100-(($row2['costo']+$row2['gasto'])*100),2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto9,2));?></td>
					<td><?php print(round($diferenciaMargenDirectoPor9,2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto9-($row2['Septiembre']-$row2['SeptiembreC']-$row2['SeptiembreG']),2));?></td>
					<td></td>
					<td>$<?php print(number_format(($row2['Enero']-$row2['EneroC']-$row2['EneroG'])+($row2['Febrero']-$row2['FebreroC']-$row2['FebreroG'])+($row2['Marzo']-$row2['MarzoC']-$row2['MarzoG'])+($row2['Abril']-$row2['AbrilC']-$row2['AbrilG'])+($row2['Mayo']-$row2['MayoC']-$row2['MayoG'])+($row2['Junio']-$row2['JunioC']-$row2['JunioG'])+($row2['Julio']-$row2['JulioC']-$row2['JulioG'])+($row2['Agosto']-$row2['AgostoC']-$row2['AgostoG'])+($row2['Septiembre']-$row2['SeptiembreC']-$row2['SeptiembreG']),2));?></td>
					<td><?php print(round(100-(($row2['costo']+$row2['gasto'])*100),2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto1+$diferenciaMargenDirecto2+$diferenciaMargenDirecto3+$diferenciaMargenDirecto4+$diferenciaMargenDirecto5+$diferenciaMargenDirecto6+$diferenciaMargenDirecto7+$diferenciaMargenDirecto8+$diferenciaMargenDirecto9,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0 || $row['JulioP'] == 0 || $row['AgostoP'] == 0 || $row['SeptiembreP'] == 0){print(0);} else{print(round(($diferenciaMargenDirecto1+$diferenciaMargenDirecto2+$diferenciaMargenDirecto3+$diferenciaMargenDirecto4+$diferenciaMargenDirecto5+$diferenciaMargenDirecto6+$diferenciaMargenDirecto7+$diferenciaMargenDirecto8+$diferenciaMargenDirecto9)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format(($septiembre-$septiembreC-$septiembreG)*-1,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Octubre']-$row2['OctubreC']-$row2['OctubreG'],2));?></td>
					<td><?php print(round(100-(($row2['costo']+$row2['gasto'])*100),2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto10,2));?></td>
					<td><?php print(round($diferenciaMargenDirectoPor10,2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto10-($row2['Octubre']-$row2['OctubreC']-$row2['OctubreG']),2));?></td>
					<td></td>
					<td>$<?php print(number_format(($row2['Enero']-$row2['EneroC']-$row2['EneroG'])+($row2['Febrero']-$row2['FebreroC']-$row2['FebreroG'])+($row2['Marzo']-$row2['MarzoC']-$row2['MarzoG'])+($row2['Abril']-$row2['AbrilC']-$row2['AbrilG'])+($row2['Mayo']-$row2['MayoC']-$row2['MayoG'])+($row2['Junio']-$row2['JunioC']-$row2['JunioG'])+($row2['Julio']-$row2['JulioC']-$row2['JulioG'])+($row2['Agosto']-$row2['AgostoC']-$row2['AgostoG'])+($row2['Septiembre']-$row2['SeptiembreC']-$row2['SeptiembreG'])+($row2['Octubre']-$row2['OctubreC']-$row2['OctubreG']),2));?></td>
					<td><?php print(round(100-(($row2['costo']+$row2['gasto'])*100),2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto1+$diferenciaMargenDirecto2+$diferenciaMargenDirecto3+$diferenciaMargenDirecto4+$diferenciaMargenDirecto5+$diferenciaMargenDirecto6+$diferenciaMargenDirecto7+$diferenciaMargenDirecto8+$diferenciaMargenDirecto9+$diferenciaMargenDirecto10,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0 || $row['JulioP'] == 0 || $row['AgostoP'] == 0 || $row['SeptiembreP'] == 0 || $row['OctubreP'] == 0){print(0);} else{print(round(($diferenciaMargenDirecto1+$diferenciaMargenDirecto2+$diferenciaMargenDirecto3+$diferenciaMargenDirecto4+$diferenciaMargenDirecto5+$diferenciaMargenDirecto6+$diferenciaMargenDirecto7+$diferenciaMargenDirecto8+$diferenciaMargenDirecto9+$diferenciaMargenDirecto10)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format(($octubre-$octubreC-$octubreG)*-1,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Noviembre']-$row2['NoviembreC']-$row2['NoviembreG'],2));?></td>
					<td><?php print(round(100-(($row2['costo']+$row2['gasto'])*100),2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto11,2));?></td>
					<td><?php print(round($diferenciaMargenDirectoPor11,2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto11-($row2['Noviembre']-$row2['NoviembreC']-$row2['NoviembreG']),2));?></td>
					<td></td>
					<td>$<?php print(number_format(($row2['Enero']-$row2['EneroC']-$row2['EneroG'])+($row2['Febrero']-$row2['FebreroC']-$row2['FebreroG'])+($row2['Marzo']-$row2['MarzoC']-$row2['MarzoG'])+($row2['Abril']-$row2['AbrilC']-$row2['AbrilG'])+($row2['Mayo']-$row2['MayoC']-$row2['MayoG'])+($row2['Junio']-$row2['JunioC']-$row2['JunioG'])+($row2['Julio']-$row2['JulioC']-$row2['JulioG'])+($row2['Agosto']-$row2['AgostoC']-$row2['AgostoG'])+($row2['Septiembre']-$row2['SeptiembreC']-$row2['SeptiembreG'])+($row2['Octubre']-$row2['OctubreC']-$row2['OctubreG'])+($row2['Noviembre']-$row2['NoviembreC']-$row2['NoviembreG']),2));?></td>
					<td><?php print(round(100-(($row2['costo']+$row2['gasto'])*100),2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto1+$diferenciaMargenDirecto2+$diferenciaMargenDirecto3+$diferenciaMargenDirecto4+$diferenciaMargenDirecto5+$diferenciaMargenDirecto6+$diferenciaMargenDirecto7+$diferenciaMargenDirecto8+$diferenciaMargenDirecto9+$diferenciaMargenDirecto10+$diferenciaMargenDirecto11,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0 || $row['JulioP'] == 0 || $row['AgostoP'] == 0 || $row['SeptiembreP'] == 0 || $row['OctubreP'] == 0 || $row['NoviembreP'] == 0){print(0);} else{print(round(($diferenciaMargenDirecto1+$diferenciaMargenDirecto2+$diferenciaMargenDirecto3+$diferenciaMargenDirecto4+$diferenciaMargenDirecto5+$diferenciaMargenDirecto6+$diferenciaMargenDirecto7+$diferenciaMargenDirecto8+$diferenciaMargenDirecto9+$diferenciaMargenDirecto10+$diferenciaMargenDirecto11)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP']+$row['NoviembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format(($noviembre-$noviembreC-$noviembreG)*-1,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Diciembre']-$row2['DiciembreC'],2));?></td>
					<td><?php print(round(100-($row2['costo']*100),2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto12,2));?></td>
					<td><?php print(round($diferenciaMargenDirectoPor12,2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto12-($row2['Diciembre']-$row2['DiciembreC']-$row2['DiciembreG']),2));?></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
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
				<!--NOMINA-->
				<tr>
					<th>Nomina Total</th>
					<td>$<?php print(number_format(($row3['TotalNomina']),2));?></td>
					<td><?php if($row2['TOTAL'] == 0){print(0);}else{print(round($row3['TotalNomina']/$row2['TOTAL']*100,2));}?>%</td>
					<td></td>
					<td>$<?php print(number_format($nominaMensual,2));?></td>
					<td><?php if($row2['Enero'] == 0){print(0);}else{print(round($nominaMensual/$row2['Enero']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroNomina'],2));?></td>
					<td><?php if($row4['EneroNomina'] == 0 || $row['EneroP'] == 0){print(0);}else{print(round(($row4['EneroNomina']/$row['EneroP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroNomina']-$nominaMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($nominaMensual,2));?></td>
					<td><?php if($row2['Febrero'] == 0){print(0);}else{print(round($nominaMensual/$row2['Febrero']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['FebreroNomina'],2));?></td>
					<td><?php if($row4['FebreroNomina'] == 0 || $row['FebreroP'] == 0){print(0);}else{print(round(($row4['FebreroNomina']/$row['FebreroP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['FebreroNomina']-$nominaMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($nominaMensual*2,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0){print(0);}else{print(round(($nominaMensual*2)/($row2['Enero']+$row2['Febrero'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroNomina']+$row4['FebreroNomina'],2));?></td>
					<td><?php if($row4['EneroNomina'] == 0 || $row['EneroP'] == 0 || $row4['FebreroNomina'] == 0 || $row['FebreroP'] == 0){print(0);}else{print(round(($row4['EneroNomina']+$row4['FebreroNomina'])/($row['EneroP']+$row['FebreroP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($febreroNom,2));?></td>
					<td></td>
					<td>$<?php print(number_format($nominaMensual,2));?></td>
					<td><?php if($row2['Marzo'] == 0){print(0);}else{print(round($nominaMensual/$row2['Marzo']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['MarzoNomina'],2));?></td>
					<td><?php if($row4['MarzoNomina'] == 0 || $row['MarzoP'] == 0){print(0);}else{print(round(($row4['MarzoNomina']/$row['MarzoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['MarzoNomina']-$nominaMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($nominaMensual*3,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0){print(0);}else{print(round(($nominaMensual*3)/($row2['Enero']+$row2['Febrero']+$row2['Marzo'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroNomina']+$row4['FebreroNomina']+$row4['MarzoNomina'],2));?></td>
					<td><?php if($row4['EneroNomina'] == 0 || $row['EneroP'] == 0 || $row4['FebreroNomina'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoNomina'] == 0 || $row['MarzoP'] == 0){print(0);}else{print(round(($row4['EneroNomina']+$row4['FebreroNomina']+$row4['MarzoNomina'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($marzoNom,2));?></td>
					<td></td>
					<td>$<?php print(number_format($nominaMensual,2));?></td>
					<td><?php if($row2['Abril'] == 0){print(0);}else{print(round($nominaMensual/$row2['Abril']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['AbrilNomina'],2));?></td>
					<td><?php if($row4['AbrilNomina'] == 0 || $row['AbrilP'] == 0){print(0);}else{print(round(($row4['AbrilNomina']/$row['AbrilP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['AbrilNomina']-$nominaMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($nominaMensual*4,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0){print(0);}else{print(round(($nominaMensual*4)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroNomina']+$row4['FebreroNomina']+$row4['MarzoNomina']+$row4['AbrilNomina'],2));?></td>
					<td><?php if($row4['EneroNomina'] == 0 || $row['EneroP'] == 0 || $row4['FebreroNomina'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoNomina'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilNomina'] == 0 || $row['AbrilP'] == 0){print(0);}else{print(round(($row4['EneroNomina']+$row4['FebreroNomina']+$row4['MarzoNomina']+$row4['AbrilNomina'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($abrilNom,2));?></td>
					<td></td>
					<td>$<?php print(number_format($nominaMensual,2));?></td>
					<td><?php if($row2['Mayo'] == 0){print(0);}else{print(round($nominaMensual/$row2['Mayo']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['MayoNomina'],2));?></td>
					<td><?php if($row4['MayoNomina'] == 0 || $row['MayoP'] == 0){print(0);}else{print(round(($row4['MayoNomina']/$row['MayoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['MayoNomina']-$nominaMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($nominaMensual*5,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0){print(0);}else{print(round(($nominaMensual*5)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroNomina']+$row4['FebreroNomina']+$row4['MarzoNomina']+$row4['AbrilNomina']+$row4['MayoNomina'],2));?></td>
					<td><?php if($row4['EneroNomina'] == 0 || $row['EneroP'] == 0 || $row4['FebreroNomina'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoNomina'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilNomina'] == 0 || $row['AbrilP'] == 0 || $row4['MayoNomina'] == 0 || $row['MayoP'] == 0){print(0);}else{print(round(($row4['EneroNomina']+$row4['FebreroNomina']+$row4['MarzoNomina']+$row4['AbrilNomina']+$row4['MayoNomina'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($mayoNom,2));?></td>
					<td></td>
					<td>$<?php print(number_format($nominaMensual,2));?></td>
					<td><?php if($row2['Junio'] == 0){print(0);}else{print(round($nominaMensual/$row2['Junio']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['JunioNomina'],2));?></td>
					<td><?php if($row4['JunioNomina'] == 0 || $row['JunioP'] == 0){print(0);}else{print(round(($row4['JunioNomina']/$row['JunioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['JunioNomina']-$nominaMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($nominaMensual*6,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0){print(0);}else{print(round(($nominaMensual*6)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroNomina']+$row4['FebreroNomina']+$row4['MarzoNomina']+$row4['AbrilNomina']+$row4['MayoNomina']+$row4['JunioNomina'],2));?></td>
					<td><?php if($row4['EneroNomina'] == 0 || $row['EneroP'] == 0 || $row4['FebreroNomina'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoNomina'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilNomina'] == 0 || $row['AbrilP'] == 0 || $row4['MayoNomina'] == 0 || $row['MayoP'] == 0 || $row4['JunioNomina'] == 0 || $row['JunioP'] == 0){print(0);}else{print(round(($row4['EneroNomina']+$row4['FebreroNomina']+$row4['MarzoNomina']+$row4['AbrilNomina']+$row4['MayoNomina']+$row4['JunioNomina'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($junioNom,2));?></td>
					<td></td>
					<td>$<?php print(number_format($nominaMensual,2));?></td>
					<td><?php if($row2['Julio'] == 0){print(0);}else{print(round($nominaMensual/$row2['Julio']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['JulioNomina'],2));?></td>
					<td><?php if($row4['JulioNomina'] == 0 || $row['JulioP'] == 0){print(0);}else{print(round(($row4['JulioNomina']/$row['JulioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['JulioNomina']-$nominaMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($nominaMensual*7,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0){print(0);}else{print(round(($nominaMensual*7)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroNomina']+$row4['FebreroNomina']+$row4['MarzoNomina']+$row4['AbrilNomina']+$row4['MayoNomina']+$row4['JunioNomina']+$row4['JulioNomina'],2));?></td>
					<td><?php if($row4['EneroNomina'] == 0 || $row['EneroP'] == 0 || $row4['FebreroNomina'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoNomina'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilNomina'] == 0 || $row['AbrilP'] == 0 || $row4['MayoNomina'] == 0 || $row['MayoP'] == 0 || $row4['JunioNomina'] == 0 || $row['JunioP'] == 0 || $row4['JulioNomina'] == 0 || $row['JulioP'] == 0){print(0);}else{print(round(($row4['EneroNomina']+$row4['FebreroNomina']+$row4['MarzoNomina']+$row4['AbrilNomina']+$row4['MayoNomina']+$row4['JunioNomina']+$row4['JulioNomina'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($julioNom,2));?></td>
					<td></td>
					<td>$<?php print(number_format($nominaMensual,2));?></td>
					<td><?php if($row2['Agosto'] == 0){print(0);}else{print(round($nominaMensual/$row2['Agosto']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['AgostoNomina'],2));?></td>
					<td><?php if($row4['AgostoNomina'] == 0 || $row['AgostoP'] == 0){print(0);}else{print(round(($row4['AgostoNomina']/$row['AgostoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['AgostoNomina']-$nominaMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($nominaMensual*8,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0 || $row2['Agosto'] == 0){print(0);}else{print(round(($nominaMensual*8)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroNomina']+$row4['FebreroNomina']+$row4['MarzoNomina']+$row4['AbrilNomina']+$row4['MayoNomina']+$row4['JunioNomina']+$row4['JulioNomina']+$row4['AgostoNomina'],2));?></td>
					<td><?php if($row4['EneroNomina'] == 0 || $row['EneroP'] == 0 || $row4['FebreroNomina'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoNomina'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilNomina'] == 0 || $row['AbrilP'] == 0 || $row4['MayoNomina'] == 0 || $row['MayoP'] == 0 || $row4['JunioNomina'] == 0 || $row['JunioP'] == 0 || $row4['JulioNomina'] == 0 || $row['JulioP'] == 0 || $row4['AgostoNomina'] == 0 || $row['AgostoP'] == 0){print(0);}else{print(round(($row4['EneroNomina']+$row4['FebreroNomina']+$row4['MarzoNomina']+$row4['AbrilNomina']+$row4['MayoNomina']+$row4['JunioNomina']+$row4['JulioNomina']+$row4['AgostoNomina'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($agostoNom,2));?></td>
					<td></td>
					<td>$<?php print(number_format($nominaMensual,2));?></td>
					<td><?php if($row2['Septiembre'] == 0){print(0);}else{print(round($nominaMensual/$row2['Septiembre']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['SeptiembreNomina'],2));?></td>
					<td><?php if($row4['SeptiembreNomina'] == 0 || $row['SeptiembreP'] == 0){print(0);}else{print(round(($row4['SeptiembreNomina']/$row['SeptiembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['SeptiembreNomina']-$nominaMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($nominaMensual*9,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0 || $row2['Agosto'] == 0 || $row2['Septiembre'] == 0){print(0);}else{print(round(($nominaMensual*9)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroNomina']+$row4['FebreroNomina']+$row4['MarzoNomina']+$row4['AbrilNomina']+$row4['MayoNomina']+$row4['JunioNomina']+$row4['JulioNomina']+$row4['AgostoNomina']+$row4['SeptiembreNomina'],2));?></td>
					<td><?php if($row4['EneroNomina'] == 0 || $row['EneroP'] == 0 || $row4['FebreroNomina'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoNomina'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilNomina'] == 0 || $row['AbrilP'] == 0 || $row4['MayoNomina'] == 0 || $row['MayoP'] == 0 || $row4['JunioNomina'] == 0 || $row['JunioP'] == 0 || $row4['JulioNomina'] == 0 || $row['JulioP'] == 0 || $row4['AgostoNomina'] == 0 || $row['AgostoP'] == 0 || $row4['SeptiembreNomina'] == 0 || $row['SeptiembreP'] == 0){print(0);}else{print(round(($row4['EneroNomina']+$row4['FebreroNomina']+$row4['MarzoNomina']+$row4['AbrilNomina']+$row4['MayoNomina']+$row4['JunioNomina']+$row4['JulioNomina']+$row4['AgostoNomina']+$row4['SeptiembreNomina'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($septiembreNom,2));?></td>
					<td></td>
					<td>$<?php print(number_format($nominaMensual,2));?></td>
					<td><?php if($row2['Octubre'] == 0){print(0);}else{print(round($nominaMensual/$row2['Octubre']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['OctubreNomina'],2));?></td>
					<td><?php if($row4['OctubreNomina'] == 0 || $row['OctubreP'] == 0){print(0);}else{print(round(($row4['OctubreNomina']/$row['OctubreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['OctubreNomina']-$nominaMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($nominaMensual*10,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0 || $row2['Agosto'] == 0 || $row2['Septiembre'] == 0 || $row2['Octubre'] == 0){print(0);}else{print(round(($nominaMensual*10)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre']+$row2['Octubre'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroNomina']+$row4['FebreroNomina']+$row4['MarzoNomina']+$row4['AbrilNomina']+$row4['MayoNomina']+$row4['JunioNomina']+$row4['JulioNomina']+$row4['AgostoNomina']+$row4['SeptiembreNomina']+$row4['OctubreNomina'],2));?></td>
					<td><?php if($row4['EneroNomina'] == 0 || $row['EneroP'] == 0 || $row4['FebreroNomina'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoNomina'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilNomina'] == 0 || $row['AbrilP'] == 0 || $row4['MayoNomina'] == 0 || $row['MayoP'] == 0 || $row4['JunioNomina'] == 0 || $row['JunioP'] == 0 || $row4['JulioNomina'] == 0 || $row['JulioP'] == 0 || $row4['AgostoNomina'] == 0 || $row['AgostoP'] == 0 || $row4['SeptiembreNomina'] == 0 || $row['SeptiembreP'] == 0 || $row4['OctubreNomina'] == 0 || $row['OctubreP'] == 0){print(0);}else{print(round(($row4['EneroNomina']+$row4['FebreroNomina']+$row4['MarzoNomina']+$row4['AbrilNomina']+$row4['MayoNomina']+$row4['JunioNomina']+$row4['JulioNomina']+$row4['AgostoNomina']+$row4['SeptiembreNomina']+$row4['OctubreNomina'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($octubreNom,2));?></td>
					<td></td>
					<td>$<?php print(number_format($nominaMensual,2));?></td>
					<td><?php if($row2['Noviembre'] == 0){print(0);}else{print(round($nominaMensual/$row2['Noviembre']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['NoviembreNomina'],2));?></td>
					<td><?php if($row4['NoviembreNomina'] == 0 || $row['NoviembreP'] == 0){print(0);}else{print(round(($row4['NoviembreNomina']/$row['NoviembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['NoviembreNomina']-$nominaMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($nominaMensual*11,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0 || $row2['Agosto'] == 0 || $row2['Septiembre'] == 0 || $row2['Octubre'] == 0 || $row2['Noviembre'] == 0){print(0);}else{print(round(($nominaMensual*11)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre']+$row2['Octubre']+$row2['Noviembre'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroNomina']+$row4['FebreroNomina']+$row4['MarzoNomina']+$row4['AbrilNomina']+$row4['MayoNomina']+$row4['JunioNomina']+$row4['JulioNomina']+$row4['AgostoNomina']+$row4['SeptiembreNomina']+$row4['OctubreNomina']+$row4['NoviembreNomina'],2));?></td>
					<td><?php if($row4['EneroNomina'] == 0 || $row['EneroP'] == 0 || $row4['FebreroNomina'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoNomina'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilNomina'] == 0 || $row['AbrilP'] == 0 || $row4['MayoNomina'] == 0 || $row['MayoP'] == 0 || $row4['JunioNomina'] == 0 || $row['JunioP'] == 0 || $row4['JulioNomina'] == 0 || $row['JulioP'] == 0 || $row4['AgostoNomina'] == 0 || $row['AgostoP'] == 0 || $row4['SeptiembreNomina'] == 0 || $row['SeptiembreP'] == 0 || $row4['OctubreNomina'] == 0 || $row['OctubreP'] == 0 || $row4['NoviembreNomina'] == 0 || $row['NoviembreP'] == 0){print(0);}else{print(round(($row4['EneroNomina']+$row4['FebreroNomina']+$row4['MarzoNomina']+$row4['AbrilNomina']+$row4['MayoNomina']+$row4['JunioNomina']+$row4['JulioNomina']+$row4['AgostoNomina']+$row4['SeptiembreNomina']+$row4['OctubreNomina']+$row4['NoviembreNomina'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP']+$row['NoviembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($noviembreNom,2));?></td>
					<td></td>
					<td>$<?php print(number_format($nominaMensual,2));?></td>
					<td><?php if($row2['Diciembre'] == 0){print(0);}else{print(round($nominaMensual/$row2['Diciembre']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['DiciembreNomina'],2));?></td>
					<td><?php if($row4['DiciembreNomina'] == 0 || $row['DiciembreP'] == 0){print(0);}else{print(round(($row4['DiciembreNomina']/$row['DiciembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['DiciembreNomina']-$nominaMensual,2));?></td>
				</tr>
				<!--SAC-->
				<tr>
					<th>SAC Total</th>
					<td>$<?php print(number_format(($row3['TotalSAC']),2));?></td>
					<td><?php if($row2['TOTAL'] == 0){print(0);}else{print(round($row3['TotalSAC']/$row2['TOTAL']*100,2));}?>%</td>
					<td></td>
					<td>$<?php print(number_format($sacMensual,2));?></td>
					<td><?php if($row2['Enero'] == 0){print(0);}else{print(round($sacMensual/$row2['Enero']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroSAC'],2));?></td>
					<td><?php if($row4['EneroSAC'] == 0 || $row['EneroP'] == 0){print(0);}else{print(round(($row4['EneroSAC']/$row['EneroP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroSAC']-$sacMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($sacMensual,2));?></td>
					<td><?php if($row2['Febrero'] == 0){print(0);}else{print(round($sacMensual/$row2['Febrero']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['FebreroSAC'],2));?></td>
					<td><?php if($row4['FebreroSAC'] == 0 || $row['FebreroP'] == 0){print(0);}else{print(round(($row4['FebreroSAC']/$row['FebreroP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['FebreroSAC']-$sacMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($sacMensual*2,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0){print(0);}else{print(round(($sacMensual*2)/($row2['Enero']+$row2['Febrero'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroSAC']+$row4['FebreroSAC'],2));?></td>
					<td><?php if($row4['EneroSAC'] == 0 || $row['EneroP'] == 0 || $row4['FebreroSAC'] == 0 || $row['FebreroP'] == 0){print(0);}else{print(round(($row4['EneroSAC']+$row4['FebreroSAC'])/($row['EneroP']+$row['FebreroP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($febreroSAC,2));?></td>
					<td></td>
					<td>$<?php print(number_format($sacMensual,2));?></td>
					<td><?php if($row2['Marzo'] == 0){print(0);}else{print(round($sacMensual/$row2['Marzo']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['MarzoSAC'],2));?></td>
					<td><?php if($row4['MarzoSAC'] == 0 || $row['MarzoP'] == 0){print(0);}else{print(round(($row4['MarzoSAC']/$row['MarzoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['MarzoSAC']-$sacMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($sacMensual*3,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0){print(0);}else{print(round(($sacMensual*3)/($row2['Enero']+$row2['Febrero']+$row2['Marzo'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroSAC']+$row4['FebreroSAC']+$row4['MarzoSAC'],2));?></td>
					<td><?php if($row4['EneroSAC'] == 0 || $row['EneroP'] == 0 || $row4['FebreroSAC'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoSAC'] == 0 || $row['MarzoP'] == 0){print(0);}else{print(round(($row4['EneroSAC']+$row4['FebreroSAC']+$row4['MarzoSAC'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($marzoSAC,2));?></td>
					<td></td>
					<td>$<?php print(number_format($sacMensual,2));?></td>
					<td><?php if($row2['Abril'] == 0){print(0);}else{print(round($sacMensual/$row2['Abril']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['AbrilSAC'],2));?></td>
					<td><?php if($row4['AbrilSAC'] == 0 || $row['AbrilP'] == 0){print(0);}else{print(round(($row4['AbrilSAC']/$row['AbrilP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['AbrilSAC']-$sacMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($sacMensual*4,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0){print(0);}else{print(round(($sacMensual*4)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroSAC']+$row4['FebreroSAC']+$row4['MarzoSAC']+$row4['AbrilSAC'],2));?></td>
					<td><?php if($row4['EneroSAC'] == 0 || $row['EneroP'] == 0 || $row4['FebreroSAC'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoSAC'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilSAC'] == 0 || $row['AbrilP'] == 0){print(0);}else{print(round(($row4['EneroSAC']+$row4['FebreroSAC']+$row4['MarzoSAC']+$row4['AbrilSAC'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($abrilSAC,2));?></td>
					<td></td>
					<td>$<?php print(number_format($sacMensual,2));?></td>
					<td><?php if($row2['Mayo'] == 0){print(0);}else{print(round($sacMensual/$row2['Mayo']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['MayoSAC'],2));?></td>
					<td><?php if($row4['MayoSAC'] == 0 || $row['MayoP'] == 0){print(0);}else{print(round(($row4['MayoSAC']/$row['MayoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['MayoSAC']-$sacMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($sacMensual*5,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0){print(0);}else{print(round(($sacMensual*5)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroSAC']+$row4['FebreroSAC']+$row4['MarzoSAC']+$row4['AbrilSAC']+$row4['MayoSAC'],2));?></td>
					<td><?php if($row4['EneroSAC'] == 0 || $row['EneroP'] == 0 || $row4['FebreroSAC'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoSAC'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilSAC'] == 0 || $row['AbrilP'] == 0 || $row4['MayoSAC'] == 0 || $row['MayoP'] == 0){print(0);}else{print(round(($row4['EneroSAC']+$row4['FebreroSAC']+$row4['MarzoSAC']+$row4['AbrilSAC']+$row4['MayoSAC'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($mayoSAC,2));?></td>
					<td></td>
					<td>$<?php print(number_format($sacMensual,2));?></td>
					<td><?php if($row2['Junio'] == 0){print(0);}else{print(round($sacMensual/$row2['Junio']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['JunioSAC'],2));?></td>
					<td><?php if($row4['JunioSAC'] == 0 || $row['JunioP'] == 0){print(0);}else{print(round(($row4['JunioSAC']/$row['JunioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['JunioSAC']-$sacMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($sacMensual*6,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0){print(0);}else{print(round(($sacMensual*6)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroSAC']+$row4['FebreroSAC']+$row4['MarzoSAC']+$row4['AbrilSAC']+$row4['MayoSAC']+$row4['JunioSAC'],2));?></td>
					<td><?php if($row4['EneroSAC'] == 0 || $row['EneroP'] == 0 || $row4['FebreroSAC'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoSAC'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilSAC'] == 0 || $row['AbrilP'] == 0 || $row4['MayoSAC'] == 0 || $row['MayoP'] == 0 || $row4['JunioSAC'] == 0 || $row['JunioP'] == 0){print(0);}else{print(round(($row4['EneroSAC']+$row4['FebreroSAC']+$row4['MarzoSAC']+$row4['AbrilSAC']+$row4['MayoSAC']+$row4['JunioSAC'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($junioSAC,2));?></td>
					<td></td>
					<td>$<?php print(number_format($sacMensual,2));?></td>
					<td><?php if($row2['Julio'] == 0){print(0);}else{print(round($sacMensual/$row2['Julio']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['JulioSAC'],2));?></td>
					<td><?php if($row4['JulioSAC'] == 0 || $row['JulioP'] == 0){print(0);}else{print(round(($row4['JulioSAC']/$row['JulioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['JulioSAC']-$sacMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($sacMensual*7,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0){print(0);}else{print(round(($sacMensual*7)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroSAC']+$row4['FebreroSAC']+$row4['MarzoSAC']+$row4['AbrilSAC']+$row4['MayoSAC']+$row4['JunioSAC']+$row4['JulioSAC'],2));?></td>
					<td><?php if($row4['EneroSAC'] == 0 || $row['EneroP'] == 0 || $row4['FebreroSAC'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoSAC'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilSAC'] == 0 || $row['AbrilP'] == 0 || $row4['MayoSAC'] == 0 || $row['MayoP'] == 0 || $row4['JunioSAC'] == 0 || $row['JunioP'] == 0 || $row4['JulioSAC'] == 0 || $row['JulioP'] == 0){print(0);}else{print(round(($row4['EneroSAC']+$row4['FebreroSAC']+$row4['MarzoSAC']+$row4['AbrilSAC']+$row4['MayoSAC']+$row4['JunioSAC']+$row4['JulioSAC'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($julioSAC,2));?></td>
					<td></td>
					<td>$<?php print(number_format($sacMensual,2));?></td>
					<td><?php if($row2['Agosto'] == 0){print(0);}else{print(round($sacMensual/$row2['Agosto']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['AgostoSAC'],2));?></td>
					<td><?php if($row4['AgostoSAC'] == 0 || $row['AgostoP'] == 0){print(0);}else{print(round(($row4['AgostoSAC']/$row['AgostoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['AgostoSAC']-$sacMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($sacMensual*8,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0 || $row2['Agosto'] == 0){print(0);}else{print(round(($sacMensual*8)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroSAC']+$row4['FebreroSAC']+$row4['MarzoSAC']+$row4['AbrilSAC']+$row4['MayoSAC']+$row4['JunioSAC']+$row4['JulioSAC']+$row4['AgostoSAC'],2));?></td>
					<td><?php if($row4['EneroSAC'] == 0 || $row['EneroP'] == 0 || $row4['FebreroSAC'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoSAC'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilSAC'] == 0 || $row['AbrilP'] == 0 || $row4['MayoSAC'] == 0 || $row['MayoP'] == 0 || $row4['JunioSAC'] == 0 || $row['JunioP'] == 0 || $row4['JulioSAC'] == 0 || $row['JulioP'] == 0 || $row4['AgostoSAC'] == 0 || $row['AgostoP'] == 0){print(0);}else{print(round(($row4['EneroSAC']+$row4['FebreroSAC']+$row4['MarzoSAC']+$row4['AbrilSAC']+$row4['MayoSAC']+$row4['JunioSAC']+$row4['JulioSAC']+$row4['AgostoSAC'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($agostoSAC,2));?></td>
					<td></td>
					<td>$<?php print(number_format($sacMensual,2));?></td>
					<td><?php if($row2['Septiembre'] == 0){print(0);}else{print(round($sacMensual/$row2['Septiembre']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['SeptiembreSAC'],2));?></td>
					<td><?php if($row4['SeptiembreSAC'] == 0 || $row['SeptiembreP'] == 0){print(0);}else{print(round(($row4['SeptiembreSAC']/$row['SeptiembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['SeptiembreSAC']-$sacMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($sacMensual*9,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0 || $row2['Agosto'] == 0 || $row2['Septiembre'] == 0){print(0);}else{print(round(($sacMensual*9)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroSAC']+$row4['FebreroSAC']+$row4['MarzoSAC']+$row4['AbrilSAC']+$row4['MayoSAC']+$row4['JunioSAC']+$row4['JulioSAC']+$row4['AgostoSAC']+$row4['SeptiembreSAC'],2));?></td>
					<td><?php if($row4['EneroSAC'] == 0 || $row['EneroP'] == 0 || $row4['FebreroSAC'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoSAC'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilSAC'] == 0 || $row['AbrilP'] == 0 || $row4['MayoSAC'] == 0 || $row['MayoP'] == 0 || $row4['JunioSAC'] == 0 || $row['JunioP'] == 0 || $row4['JulioSAC'] == 0 || $row['JulioP'] == 0 || $row4['AgostoSAC'] == 0 || $row['AgostoP'] == 0 || $row4['SeptiembreSAC'] == 0 || $row['SeptiembreP'] == 0){print(0);}else{print(round(($row4['EneroSAC']+$row4['FebreroSAC']+$row4['MarzoSAC']+$row4['AbrilSAC']+$row4['MayoSAC']+$row4['JunioSAC']+$row4['JulioSAC']+$row4['AgostoSAC']+$row4['SeptiembreSAC'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($septiembreSAC,2));?></td>
					<td></td>
					<td>$<?php print(number_format($sacMensual,2));?></td>
					<td><?php if($row2['Octubre'] == 0){print(0);}else{print(round($sacMensual/$row2['Octubre']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['OctubreSAC'],2));?></td>
					<td><?php if($row4['OctubreSAC'] == 0 || $row['OctubreP'] == 0){print(0);}else{print(round(($row4['OctubreSAC']/$row['OctubreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['OctubreSAC']-$sacMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($sacMensual*10,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0 || $row2['Agosto'] == 0 || $row2['Septiembre'] == 0 || $row2['Octubre'] == 0){print(0);}else{print(round(($sacMensual*10)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre']+$row2['Octubre'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroSAC']+$row4['FebreroSAC']+$row4['MarzoSAC']+$row4['AbrilSAC']+$row4['MayoSAC']+$row4['JunioSAC']+$row4['JulioSAC']+$row4['AgostoSAC']+$row4['SeptiembreSAC']+$row4['OctubreSAC'],2));?></td>
					<td><?php if($row4['EneroSAC'] == 0 || $row['EneroP'] == 0 || $row4['FebreroSAC'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoSAC'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilSAC'] == 0 || $row['AbrilP'] == 0 || $row4['MayoSAC'] == 0 || $row['MayoP'] == 0 || $row4['JunioSAC'] == 0 || $row['JunioP'] == 0 || $row4['JulioSAC'] == 0 || $row['JulioP'] == 0 || $row4['AgostoSAC'] == 0 || $row['AgostoP'] == 0 || $row4['SeptiembreSAC'] == 0 || $row['SeptiembreP'] == 0 || $row4['OctubreSAC'] == 0 || $row['OctubreP'] == 0){print(0);}else{print(round(($row4['EneroSAC']+$row4['FebreroSAC']+$row4['MarzoSAC']+$row4['AbrilSAC']+$row4['MayoSAC']+$row4['JunioSAC']+$row4['JulioSAC']+$row4['AgostoSAC']+$row4['SeptiembreSAC']+$row4['OctubreSAC'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($octubreSAC,2));?></td>
					<td></td>
					<td>$<?php print(number_format($sacMensual,2));?></td>
					<td><?php if($row2['Noviembre'] == 0){print(0);}else{print(round($sacMensual/$row2['Noviembre']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['NoviembreSAC'],2));?></td>
					<td><?php if($row4['NoviembreSAC'] == 0 || $row['NoviembreP'] == 0){print(0);}else{print(round(($row4['NoviembreSAC']/$row['NoviembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['NoviembreSAC']-$sacMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($sacMensual*11,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0 || $row2['Agosto'] == 0 || $row2['Septiembre'] == 0 || $row2['Octubre'] == 0 || $row2['Noviembre'] == 0){print(0);}else{print(round(($sacMensual*11)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre']+$row2['Octubre']+$row2['Noviembre'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroSAC']+$row4['FebreroSAC']+$row4['MarzoSAC']+$row4['AbrilSAC']+$row4['MayoSAC']+$row4['JunioSAC']+$row4['JulioSAC']+$row4['AgostoSAC']+$row4['SeptiembreSAC']+$row4['OctubreSAC']+$row4['NoviembreSAC'],2));?></td>
					<td><?php if($row4['EneroSAC'] == 0 || $row['EneroP'] == 0 || $row4['FebreroSAC'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoSAC'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilSAC'] == 0 || $row['AbrilP'] == 0 || $row4['MayoSAC'] == 0 || $row['MayoP'] == 0 || $row4['JunioSAC'] == 0 || $row['JunioP'] == 0 || $row4['JulioSAC'] == 0 || $row['JulioP'] == 0 || $row4['AgostoSAC'] == 0 || $row['AgostoP'] == 0 || $row4['SeptiembreSAC'] == 0 || $row['SeptiembreP'] == 0 || $row4['OctubreSAC'] == 0 || $row['OctubreP'] == 0 || $row4['NoviembreSAC'] == 0 || $row['NoviembreP'] == 0){print(0);}else{print(round(($row4['EneroSAC']+$row4['FebreroSAC']+$row4['MarzoSAC']+$row4['AbrilSAC']+$row4['MayoSAC']+$row4['JunioSAC']+$row4['JulioSAC']+$row4['AgostoSAC']+$row4['SeptiembreSAC']+$row4['OctubreSAC']+$row4['NoviembreSAC'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP']+$row['NoviembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($noviembreSAC,2));?></td>
					<td></td>
					<td>$<?php print(number_format($sacMensual,2));?></td>
					<td><?php if($row2['Diciembre'] == 0){print(0);}else{print(round($sacMensual/$row2['Diciembre']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['DiciembreSAC'],2));?></td>
					<td><?php if($row4['DiciembreSAC'] == 0 || $row['DiciembreP'] == 0){print(0);}else{print(round(($row4['DiciembreSAC']/$row['DiciembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['DiciembreSAC']-$sacMensual,2));?></td>
				</tr>
				<!--INTERESES-->
				<tr>
					<th>Intereses Totales</th>
					<td>$<?php print(number_format(($row3['TotalIntereses']),2));?></td>
					<td><?php if($row2['TOTAL'] == 0){print(0);}else{print(round($row3['TotalIntereses']/$row2['TOTAL']*100,2));}?>%</td>
					<td></td>
					<td>$<?php print(number_format($interesesMensual,2));?></td>
					<td>0%</td>
					<td>$0.00</td>
					<td>0%</td>
					<td>$0.00</td>
					<td></td>
					<td>$<?php print(number_format($interesesMensual,2));?></td>
					<td>0%</td>
					<td>$0.00</td>
					<td>0%</td>
					<td>$0.00</td>
					<td></td>
					<td>$<?php print(number_format($interesesMensual*2,2));?></td>
					<td>0%</td>
					<td>$0.00</td>
					<td>0%</td>
					<td>$0.00</td>
					<td></td>
					<td>$<?php print(number_format($interesesMensual,2));?></td>
					<td>0%</td>
					<td>$0.00</td>
					<td>0%</td>
					<td>$0.00</td>
					<td></td>
					<td>$<?php print(number_format($interesesMensual*3,2));?></td>
					<td>0%</td>
					<td>$0.00</td>
					<td>0%</td>
					<td>$0.00</td>
					<td></td>
					<td>$<?php print(number_format($interesesMensual,2));?></td>
					<td>0%</td>
					<td>$<?php print(number_format($intereses4R,2));?></td>
					<td><?php if($row['AbrilP'] == 0){print(0);}else{print(round(($intereses4R/$row['AbrilP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($diferencia4Intereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($interesesMensual*4,2));?></td>
					<td>0%</td>
					<td>$<?php print(number_format($intereses4A,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0){print(0);}else{print(round(($intereses4A)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($abrilIntereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($interesesMensual,2));?></td>
					<td>0%</td>
					<td>$<?php print(number_format($intereses5R,2));?></td>
					<td><?php if($row['MayoP'] == 0){print(0);}else{print(round(($intereses5R/$row['MayoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($diferencia5Intereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($interesesMensual*5,2));?></td>
					<td>0%</td>
					<td>$<?php print(number_format($intereses5A,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0){print(0);}else{print(round(($intereses5A)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($mayoIntereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($interesesMensual,2));?></td>
					<td>0%</td>
					<td>$<?php print(number_format($intereses6R,2));?></td>
					<td><?php if($row['JunioP'] == 0){print(0);}else{print(round(($intereses6R/$row['JunioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($diferencia6Intereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($interesesMensual*6,2));?></td>
					<td>0%</td>
					<td>$<?php print(number_format($intereses6A,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0){print(0);}else{print(round(($intereses6A)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($junioIntereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($interesesMensual,2));?></td>
					<td>0%</td>
					<td>$<?php print(number_format($intereses7R,2));?></td>
					<td><?php if($row['JulioP'] == 0){print(0);}else{print(round(($intereses7R/$row['JulioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($diferencia7Intereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($interesesMensual*7,2));?></td>
					<td>0%</td>
					<td>$<?php print(number_format($intereses7A,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0 || $row['JulioP'] == 0){print(0);}else{print(round(($intereses7A)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($julioIntereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($interesesMensual,2));?></td>
					<td>0%</td>
					<td>$<?php print(number_format($intereses8R,2));?></td>
					<td><?php if($row['AgostoP'] == 0){print(0);}else{print(round(($intereses8R/$row['AgostoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($diferencia8Intereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($interesesMensual*8,2));?></td>
					<td>0%</td>
					<td>$<?php print(number_format($intereses8A,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0 || $row['JulioP'] == 0 || $row['AgostoP'] == 0){print(0);}else{print(round(($intereses8A)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($agostoIntereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($interesesMensual,2));?></td>
					<td>0%</td>
					<td>$<?php print(number_format($intereses9R,2));?></td>
					<td><?php if($row['SeptiembreP'] == 0){print(0);}else{print(round(($intereses9R/$row['SeptiembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($diferencia9Intereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($interesesMensual*9,2));?></td>
					<td>0%</td>
					<td>$<?php print(number_format($intereses9A,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0 || $row['JulioP'] == 0  || $row['AgostoP'] == 0 ||  $row['SeptiembreP'] == 0){print(0);}else{print(round(($intereses9A)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($septiembreIntereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($interesesMensual,2));?></td>
					<td>0%</td>
					<td>$<?php print(number_format($intereses10R,2));?></td>
					<td><?php if($row['OctubreP'] == 0){print(0);}else{print(round(($intereses10R/$row['OctubreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($diferencia10Intereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($interesesMensual*10,2));?></td>
					<td>0%</td>
					<td>$<?php print(number_format($intereses10A,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0  || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0 || $row['JulioP'] == 0 || $row['AgostoP'] == 0 || $row['SeptiembreP'] == 0 || $row['OctubreP'] == 0){print(0);}else{print(round(($intereses10A)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($octubreIntereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($interesesMensual,2));?></td>
					<td>0%</td>
					<td>$<?php print(number_format($intereses11R,2));?></td>
					<td><?php if($row['NoviembreP'] == 0){print(0);}else{print(round(($intereses11R/$row['NoviembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($diferencia11Intereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($interesesMensual*11,2));?></td>
					<td>0%</td>
					<td>$<?php print(number_format($intereses11A,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0  || $row['JulioP'] == 0  || $row['AgostoP'] == 0  || $row['SeptiembreP'] == 0  || $row['OctubreP'] == 0 || $row['NoviembreP'] == 0){print(0);}else{print(round(($intereses11A)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP']+$row['NoviembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($noviembreIntereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($interesesMensual,2));?></td>
					<td>0%</td>
					<td>$<?php print(number_format($intereses12R,2));?></td>
					<td><?php if($row['DiciembreP'] == 0){print(0);}else{print(round(($intereses12R/$row['DiciembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($diferencia12Intereses,2));?></td>
				</tr>
				<!--OTROS-->
				<tr>
					<th>Otros</th>
					<td>$<?php print(number_format(($row3['TotalOtros']),2));?></td>
					<td><?php if($row2['TOTAL'] == 0){print(0);}else{print(round($row3['TotalOtros']/$row2['TOTAL']*100,2));}?>%</td>
					<td></td>
					<td>$<?php print(number_format($otrosMensual,2));?></td>
					<td><?php if($row2['Enero'] == 0){print(0);}else{print(round($otrosMensual/$row2['Enero']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroOtros'],2));?></td>
					<td><?php if($row4['EneroOtros'] == 0 || $row['EneroP'] == 0){print(0);}else{print(round(($row4['EneroOtros']/$row['EneroP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroOtros']-$otrosMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($otrosMensual,2));?></td>
					<td><?php if($row2['Febrero'] == 0){print(0);}else{print(round($otrosMensual/$row2['Febrero']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['FebreroOtros'],2));?></td>
					<td><?php if($row4['FebreroOtros'] == 0 || $row['FebreroP'] == 0){print(0);}else{print(round(($row4['FebreroOtros']/$row['FebreroP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['FebreroOtros']-$otrosMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($otrosMensual*2,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0){print(0);}else{print(round(($otrosMensual*2)/($row2['Enero']+$row2['Febrero'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroOtros']+$row4['FebreroOtros'],2));?></td>
					<td><?php if($row4['EneroOtros'] == 0 || $row['EneroP'] == 0 || $row4['FebreroOtros'] == 0 || $row['FebreroP'] == 0){print(0);}else{print(round(($row4['EneroOtros']+$row4['FebreroOtros'])/($row['EneroP']+$row['FebreroP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($febreroOtros,2));?></td>
					<td></td>
					<td>$<?php print(number_format($otrosMensual,2));?></td>
					<td><?php if($row2['Marzo'] == 0){print(0);}else{print(round($otrosMensual/$row2['Marzo']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['MarzoOtros'],2));?></td>
					<td><?php if($row4['MarzoOtros'] == 0 || $row['MarzoP'] == 0){print(0);}else{print(round(($row4['MarzoOtros']/$row['MarzoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['MarzoOtros']-$otrosMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($otrosMensual*3,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0){print(0);}else{print(round(($otrosMensual*3)/($row2['Enero']+$row2['Febrero']+$row2['Marzo'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroOtros']+$row4['FebreroOtros']+$row4['MarzoOtros'],2));?></td>
					<td><?php if($row4['EneroOtros'] == 0 || $row['EneroP'] == 0 || $row4['FebreroOtros'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoOtros'] == 0 || $row['MarzoP'] == 0){print(0);}else{print(round(($row4['EneroOtros']+$row4['FebreroOtros']+$row4['MarzoOtros'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($marzoOtros,2));?></td>
					<td></td>
					<td>$<?php print(number_format($otrosMensual,2));?></td>
					<td><?php if($row2['Abril'] == 0){print(0);}else{print(round($otrosMensual/$row2['Abril']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['AbrilOtros'],2));?></td>
					<td><?php if($row4['AbrilOtros'] == 0 || $row['AbrilP'] == 0){print(0);}else{print(round(($row4['AbrilOtros']/$row['AbrilP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['AbrilOtros']-$otrosMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($otrosMensual*4,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0){print(0);}else{print(round(($otrosMensual*4)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroOtros']+$row4['FebreroOtros']+$row4['MarzoOtros']+$row4['AbrilOtros'],2));?></td>
					<td><?php if($row4['EneroOtros'] == 0 || $row['EneroP'] == 0 || $row4['FebreroOtros'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoOtros'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilOtros'] == 0 || $row['AbrilP'] == 0){print(0);}else{print(round(($row4['EneroOtros']+$row4['FebreroOtros']+$row4['MarzoOtros']+$row4['AbrilOtros'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($abrilOtros,2));?></td>
					<td></td>
					<td>$<?php print(number_format($otrosMensual,2));?></td>
					<td><?php if($row2['Mayo'] == 0){print(0);}else{print(round($otrosMensual/$row2['Mayo']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['MayoOtros'],2));?></td>
					<td><?php if($row4['MayoOtros'] == 0 || $row['MayoP'] == 0){print(0);}else{print(round(($row4['MayoOtros']/$row['MayoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['MayoOtros']-$otrosMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($otrosMensual*5,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0){print(0);}else{print(round(($otrosMensual*5)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroOtros']+$row4['FebreroOtros']+$row4['MarzoOtros']+$row4['AbrilOtros']+$row4['MayoOtros'],2));?></td>
					<td><?php if($row4['EneroOtros'] == 0 || $row['EneroP'] == 0 || $row4['FebreroOtros'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoOtros'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilOtros'] == 0 || $row['AbrilP'] == 0 || $row4['MayoOtros'] == 0 || $row['MayoP'] == 0){print(0);}else{print(round(($row4['EneroOtros']+$row4['FebreroOtros']+$row4['MarzoOtros']+$row4['AbrilOtros']+$row4['MayoOtros'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($mayoOtros,2));?></td>
					<td></td>
					<td>$<?php print(number_format($otrosMensual,2));?></td>
					<td><?php if($row2['Junio'] == 0){print(0);}else{print(round($otrosMensual/$row2['Junio']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['JunioOtros'],2));?></td>
					<td><?php if($row4['JunioOtros'] == 0 || $row['JunioP'] == 0){print(0);}else{print(round(($row4['JunioOtros']/$row['JunioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['JunioOtros']-$otrosMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($otrosMensual*6,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0){print(0);}else{print(round(($otrosMensual*6)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroOtros']+$row4['FebreroOtros']+$row4['MarzoOtros']+$row4['AbrilOtros']+$row4['MayoOtros']+$row4['JunioOtros'],2));?></td>
					<td><?php if($row4['EneroOtros'] == 0 || $row['EneroP'] == 0 || $row4['FebreroOtros'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoOtros'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilOtros'] == 0 || $row['AbrilP'] == 0 || $row4['MayoOtros'] == 0 || $row['MayoP'] == 0 || $row4['JunioOtros'] == 0 || $row['JunioP'] == 0){print(0);}else{print(round(($row4['EneroOtros']+$row4['FebreroOtros']+$row4['MarzoOtros']+$row4['AbrilOtros']+$row4['MayoOtros']+$row4['JunioOtros'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($junioOtros,2));?></td>
					<td></td>
					<td>$<?php print(number_format($otrosMensual,2));?></td>
					<td><?php if($row2['Julio'] == 0){print(0);}else{print(round($otrosMensual/$row2['Julio']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['JulioOtros'],2));?></td>
					<td><?php if($row4['JulioOtros'] == 0 || $row['JulioP'] == 0){print(0);}else{print(round(($row4['JulioOtros']/$row['JulioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['JulioOtros']-$otrosMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($otrosMensual*7,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0){print(0);}else{print(round(($otrosMensual*7)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroOtros']+$row4['FebreroOtros']+$row4['MarzoOtros']+$row4['AbrilOtros']+$row4['MayoOtros']+$row4['JunioOtros']+$row4['JulioOtros'],2));?></td>
					<td><?php if($row4['EneroOtros'] == 0 || $row['EneroP'] == 0 || $row4['FebreroOtros'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoOtros'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilOtros'] == 0 || $row['AbrilP'] == 0 || $row4['MayoOtros'] == 0 || $row['MayoP'] == 0 || $row4['JunioOtros'] == 0 || $row['JunioP'] == 0 || $row4['JulioOtros'] == 0 || $row['JulioP'] == 0){print(0);}else{print(round(($row4['EneroOtros']+$row4['FebreroOtros']+$row4['MarzoOtros']+$row4['AbrilOtros']+$row4['MayoOtros']+$row4['JunioOtros']+$row4['JulioOtros'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($julioOtros,2));?></td>
					<td></td>
					<td>$<?php print(number_format($otrosMensual,2));?></td>
					<td><?php if($row2['Agosto'] == 0){print(0);}else{print(round($otrosMensual/$row2['Agosto']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['AgostoOtros'],2));?></td>
					<td><?php if($row4['AgostoOtros'] == 0 || $row['AgostoP'] == 0){print(0);}else{print(round(($row4['AgostoOtros']/$row['AgostoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['AgostoOtros']-$otrosMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($otrosMensual*8,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0 || $row2['Agosto'] == 0){print(0);}else{print(round(($otrosMensual*8)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroOtros']+$row4['FebreroOtros']+$row4['MarzoOtros']+$row4['AbrilOtros']+$row4['MayoOtros']+$row4['JunioOtros']+$row4['JulioOtros']+$row4['AgostoOtros'],2));?></td>
					<td><?php if($row4['EneroOtros'] == 0 || $row['EneroP'] == 0 || $row4['FebreroOtros'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoOtros'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilOtros'] == 0 || $row['AbrilP'] == 0 || $row4['MayoOtros'] == 0 || $row['MayoP'] == 0 || $row4['JunioOtros'] == 0 || $row['JunioP'] == 0 || $row4['JulioOtros'] == 0 || $row['JulioP'] == 0 || $row4['AgostoOtros'] == 0 || $row['AgostoP'] == 0){print(0);}else{print(round(($row4['EneroOtros']+$row4['FebreroOtros']+$row4['MarzoOtros']+$row4['AbrilOtros']+$row4['MayoOtros']+$row4['JunioOtros']+$row4['JulioOtros']+$row4['AgostoOtros'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($agostoOtros,2));?></td>
					<td></td>
					<td>$<?php print(number_format($otrosMensual,2));?></td>
					<td><?php if($row2['Septiembre'] == 0){print(0);}else{print(round($otrosMensual/$row2['Septiembre']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['SeptiembreOtros'],2));?></td>
					<td><?php if($row4['SeptiembreOtros'] == 0 || $row['SeptiembreP'] == 0){print(0);}else{print(round(($row4['SeptiembreOtros']/$row['SeptiembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['SeptiembreOtros']-$otrosMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($otrosMensual*9,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0 || $row2['Agosto'] == 0 || $row2['Septiembre'] == 0){print(0);}else{print(round(($otrosMensual*9)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroOtros']+$row4['FebreroOtros']+$row4['MarzoOtros']+$row4['AbrilOtros']+$row4['MayoOtros']+$row4['JunioOtros']+$row4['JulioOtros']+$row4['AgostoOtros']+$row4['SeptiembreOtros'],2));?></td>
					<td><?php if($row4['EneroOtros'] == 0 || $row['EneroP'] == 0 || $row4['FebreroOtros'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoOtros'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilOtros'] == 0 || $row['AbrilP'] == 0 || $row4['MayoOtros'] == 0 || $row['MayoP'] == 0 || $row4['JunioOtros'] == 0 || $row['JunioP'] == 0 || $row4['JulioOtros'] == 0 || $row['JulioP'] == 0 || $row4['AgostoOtros'] == 0 || $row['AgostoP'] == 0 || $row4['SeptiembreOtros'] == 0 || $row['SeptiembreP'] == 0){print(0);}else{print(round(($row4['EneroOtros']+$row4['FebreroOtros']+$row4['MarzoOtros']+$row4['AbrilOtros']+$row4['MayoOtros']+$row4['JunioOtros']+$row4['JulioOtros']+$row4['AgostoOtros']+$row4['SeptiembreOtros'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($septiembreOtros,2));?></td>
					<td></td>
					<td>$<?php print(number_format($otrosMensual,2));?></td>
					<td><?php if($row2['Octubre'] == 0){print(0);}else{print(round($otrosMensual/$row2['Octubre']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['OctubreOtros'],2));?></td>
					<td><?php if($row4['OctubreOtros'] == 0 || $row['OctubreP'] == 0){print(0);}else{print(round(($row4['OctubreOtros']/$row['OctubreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['OctubreOtros']-$otrosMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($otrosMensual*10,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0 || $row2['Agosto'] == 0 || $row2['Septiembre'] == 0 || $row2['Octubre'] == 0){print(0);}else{print(round(($otrosMensual*10)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre']+$row2['Octubre'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroOtros']+$row4['FebreroOtros']+$row4['MarzoOtros']+$row4['AbrilOtros']+$row4['MayoOtros']+$row4['JunioOtros']+$row4['JulioOtros']+$row4['AgostoOtros']+$row4['SeptiembreOtros']+$row4['OctubreOtros'],2));?></td>
					<td><?php if($row4['EneroOtros'] == 0 || $row['EneroP'] == 0 || $row4['FebreroOtros'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoOtros'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilOtros'] == 0 || $row['AbrilP'] == 0 || $row4['MayoOtros'] == 0 || $row['MayoP'] == 0 || $row4['JunioOtros'] == 0 || $row['JunioP'] == 0 || $row4['JulioOtros'] == 0 || $row['JulioP'] == 0 || $row4['AgostoOtros'] == 0 || $row['AgostoP'] == 0 || $row4['SeptiembreOtros'] == 0 || $row['SeptiembreP'] == 0 || $row4['OctubreOtros'] == 0 || $row['OctubreP'] == 0){print(0);}else{print(round(($row4['EneroOtros']+$row4['FebreroOtros']+$row4['MarzoOtros']+$row4['AbrilOtros']+$row4['MayoOtros']+$row4['JunioOtros']+$row4['JulioOtros']+$row4['AgostoOtros']+$row4['SeptiembreOtros']+$row4['OctubreOtros'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($octubreOtros,2));?></td>
					<td></td>
					<td>$<?php print(number_format($otrosMensual,2));?></td>
					<td><?php if($row2['Noviembre'] == 0){print(0);}else{print(round($otrosMensual/$row2['Noviembre']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['NoviembreOtros'],2));?></td>
					<td><?php if($row4['NoviembreOtros'] == 0 || $row['NoviembreP'] == 0){print(0);}else{print(round(($row4['NoviembreOtros']/$row['NoviembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['NoviembreOtros']-$otrosMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($otrosMensual*11,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0 || $row2['Agosto'] == 0 || $row2['Septiembre'] == 0 || $row2['Octubre'] == 0 || $row2['Noviembre'] == 0){print(0);}else{print(round(($otrosMensual*11)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre']+$row2['Octubre']+$row2['Noviembre'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroOtros']+$row4['FebreroOtros']+$row4['MarzoOtros']+$row4['AbrilOtros']+$row4['MayoOtros']+$row4['JunioOtros']+$row4['JulioOtros']+$row4['AgostoOtros']+$row4['SeptiembreOtros']+$row4['OctubreOtros']+$row4['NoviembreOtros'],2));?></td>
					<td><?php if($row4['EneroOtros'] == 0 || $row['EneroP'] == 0 || $row4['FebreroOtros'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoOtros'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilOtros'] == 0 || $row['AbrilP'] == 0 || $row4['MayoOtros'] == 0 || $row['MayoP'] == 0 || $row4['JunioOtros'] == 0 || $row['JunioP'] == 0 || $row4['JulioOtros'] == 0 || $row['JulioP'] == 0 || $row4['AgostoOtros'] == 0 || $row['AgostoP'] == 0 || $row4['SeptiembreOtros'] == 0 || $row['SeptiembreP'] == 0 || $row4['OctubreOtros'] == 0 || $row['OctubreP'] == 0 || $row4['NoviembreOtros'] == 0 || $row['NoviembreP'] == 0){print(0);}else{print(round(($row4['EneroOtros']+$row4['FebreroOtros']+$row4['MarzoOtros']+$row4['AbrilOtros']+$row4['MayoOtros']+$row4['JunioOtros']+$row4['JulioOtros']+$row4['AgostoOtros']+$row4['SeptiembreOtros']+$row4['OctubreOtros']+$row4['NoviembreOtros'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP']+$row['NoviembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($noviembreOtros,2));?></td>
					<td></td>
					<td>$<?php print(number_format($otrosMensual,2));?></td>
					<td><?php if($row2['Diciembre'] == 0){print(0);}else{print(round($otrosMensual/$row2['Diciembre']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['DiciembreOtros'],2));?></td>
					<td><?php if($row4['DiciembreOtros'] == 0 || $row['DiciembreP'] == 0){print(0);}else{print(round(($row4['DiciembreOtros']/$row['DiciembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['DiciembreOtros']-$otrosMensual,2));?></td>
				</tr>
				<!--GAO FIJO-->
				<tr>
					<th >GAO Fijo Total</th>
					<td>$<?php print(number_format(($row3['TotalGAO']),2));?></td>
					<td><?php if($row2['TOTAL'] == 0){print(0);}else{print(round($row3['TotalGAO']/$row2['TOTAL']*100,2));}?>%</td>
					<td></td>
					<td>$<?php print(number_format($gaoMensual,2));?></td>
					<td><?php if($row2['Enero'] == 0){print(0);}else{print(round($gaoMensual/$row2['Enero']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroGAO'],2));?></td>
					<td><?php if($row4['EneroGAO'] == 0 || $row['EneroP'] == 0){print(0);}else{print(round((($row4['EneroGAO'])/$row['EneroP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroGAO']-$gaoMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($gaoMensual,2));?></td>
					<td><?php if($row2['Febrero'] == 0){print(0);}else{print(round($gaoMensual/$row2['Febrero']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['FebreroGAO'],2));?></td>
					<td><?php if($row4['FebreroGAO'] == 0 || $row['FebreroP'] == 0){print(0);}else{print(round((($row4['FebreroGAO'])/$row['FebreroP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['FebreroGAO']-$gaoMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($gaoMensual*2,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0){print(0);}else{print(round(($gaoMensual*2)/($row2['Enero']+$row2['Febrero'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroGAO']+$row4['FebreroGAO'],2));?></td>
					<td><?php if($row4['EneroGAO'] == 0 || $row['EneroP'] == 0 || $row4['FebreroGAO'] == 0 || $row['FebreroP'] == 0){print(0);}else{print(round(($row4['EneroGAO']+$row4['FebreroGAO'])/($row['EneroP']+$row['FebreroP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($febreroGAO,2));?></td>
					<td></td>
					<td>$<?php print(number_format($gaoMensual,2));?></td>
					<td><?php if($row2['Marzo'] == 0){print(0);}else{print(round($gaoMensual/$row2['Marzo']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['MarzoGAO'],2));?></td>
					<td><?php if($row4['MarzoGAO'] == 0 || $row['MarzoP'] == 0){print(0);}else{print(round((($row4['MarzoGAO'])/$row['MarzoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['MarzoGAO']-$gaoMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($gaoMensual*3,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0){print(0);}else{print(round(($gaoMensual*3)/($row2['Enero']+$row2['Febrero']+$row2['Marzo'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroGAO']+$row4['FebreroGAO']+$row4['MarzoGAO'],2));?></td>
					<td><?php if($row4['EneroGAO'] == 0 || $row['EneroP'] == 0 || $row4['FebreroGAO'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoGAO'] == 0 || $row['MarzoP'] == 0){print(0);}else{print(round(($row4['EneroGAO']+$row4['FebreroGAO']+$row4['MarzoGAO'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($marzoGAO,2));?></td>
					<td></td>
					<td>$<?php print(number_format($gaoMensual,2));?></td>
					<td><?php if($row2['Abril'] == 0){print(0);}else{print(round($gaoMensual/$row2['Abril']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['AbrilGAO']+$intereses4R,2));?></td>
					<td><?php if($row4['AbrilGAO'] == 0 || $row['AbrilP'] == 0){print(0);}else{print(round((($row4['AbrilGAO']+$intereses4R)/$row['AbrilP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['AbrilGAO']-$gaoMensual+$diferencia4Intereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($gaoMensual*4,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0){print(0);}else{print(round(($gaoMensual*4)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroGAO']+$row4['FebreroGAO']+$row4['MarzoGAO']+$row4['AbrilGAO']+$intereses4A,2));?></td>
					<td><?php if($row4['EneroGAO'] == 0 || $row['EneroP'] == 0 || $row4['FebreroGAO'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoGAO'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilGAO'] == 0 || $row['AbrilP'] == 0){print(0);}else{print(round(($intereses4A+$row4['EneroGAO']+$row4['FebreroGAO']+$row4['MarzoGAO']+$row4['AbrilGAO'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($abrilGAO,2));?></td>
					<td></td>
					<td>$<?php print(number_format($gaoMensual,2));?></td>
					<td><?php if($row2['Mayo'] == 0){print(0);}else{print(round($gaoMensual/$row2['Mayo']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['MayoGAO']+$intereses5R,2));?></td>
					<td><?php if($row4['MayoGAO'] == 0 || $row['MayoP'] == 0){print(0);}else{print(round((($row4['MayoGAO']+$intereses5R)/$row['MayoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['MayoGAO']-$gaoMensual+$diferencia5Intereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($gaoMensual*5,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0){print(0);}else{print(round(($gaoMensual*5)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroGAO']+$row4['FebreroGAO']+$row4['MarzoGAO']+$row4['AbrilGAO']+$row4['MayoGAO']+$intereses5A,2));?></td>
					<td><?php if($row4['EneroGAO'] == 0 || $row['EneroP'] == 0 || $row4['FebreroGAO'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoGAO'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilGAO'] == 0 || $row['AbrilP'] == 0 || $row4['MayoGAO'] == 0 || $row['MayoP'] == 0){print(0);}else{print(round(($intereses5A+$row4['EneroGAO']+$row4['FebreroGAO']+$row4['MarzoGAO']+$row4['AbrilGAO']+$row4['MayoGAO'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($mayoGAO,2));?></td>
					<td></td>
					<td>$<?php print(number_format($gaoMensual,2));?></td>
					<td><?php if($row2['Junio'] == 0){print(0);}else{print(round($gaoMensual/$row2['Junio']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['JunioGAO']+$intereses6R,2));?></td>
					<td><?php if($row4['JunioGAO'] == 0 || $row['JunioP'] == 0){print(0);}else{print(round((($row4['JunioGAO']+$intereses6R)/$row['JunioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['JunioGAO']-$gaoMensual+$diferencia6Intereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($gaoMensual*6,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0){print(0);}else{print(round(($gaoMensual*6)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroGAO']+$row4['FebreroGAO']+$row4['MarzoGAO']+$row4['AbrilGAO']+$row4['MayoGAO']+$row4['JunioGAO']+$intereses6A,2));?></td>
					<td><?php if($row4['EneroGAO'] == 0 || $row['EneroP'] == 0 || $row4['FebreroGAO'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoGAO'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilGAO'] == 0 || $row['AbrilP'] == 0 || $row4['MayoGAO'] == 0 || $row['MayoP'] == 0 || $row4['JunioGAO'] == 0 || $row['JunioP'] == 0){print(0);}else{print(round(($intereses6A+$row4['EneroGAO']+$row4['FebreroGAO']+$row4['MarzoGAO']+$row4['AbrilGAO']+$row4['MayoGAO']+$row4['JunioGAO'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($junioGAO,2));?></td>
					<td></td>
					<td>$<?php print(number_format($gaoMensual,2));?></td>
					<td><?php if($row2['Julio'] == 0){print(0);}else{print(round($gaoMensual/$row2['Julio']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['JulioGAO']+$intereses7R,2));?></td>
					<td><?php if($row4['JulioGAO'] == 0 || $row['JulioP'] == 0){print(0);}else{print(round((($row4['JulioGAO']+$intereses7R)/$row['JulioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['JulioGAO']-$gaoMensual+$diferencia7Intereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($gaoMensual*7,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0){print(0);}else{print(round(($gaoMensual*7)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroGAO']+$row4['FebreroGAO']+$row4['MarzoGAO']+$row4['AbrilGAO']+$row4['MayoGAO']+$row4['JunioGAO']+$row4['JulioGAO']+$intereses7A,2));?></td>
					<td><?php if($row4['EneroGAO'] == 0 || $row['EneroP'] == 0 || $row4['FebreroGAO'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoGAO'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilGAO'] == 0 || $row['AbrilP'] == 0 || $row4['MayoGAO'] == 0 || $row['MayoP'] == 0 || $row4['JunioGAO'] == 0 || $row['JunioP'] == 0 || $row4['JulioGAO'] == 0 || $row['JulioP'] == 0){print(0);}else{print(round(($intereses7A+$row4['EneroGAO']+$row4['FebreroGAO']+$row4['MarzoGAO']+$row4['AbrilGAO']+$row4['MayoGAO']+$row4['JunioGAO']+$row4['JulioGAO'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($julioGAO,2));?></td>
					<td></td>
					<td>$<?php print(number_format($gaoMensual,2));?></td>
					<td><?php if($row2['Agosto'] == 0){print(0);}else{print(round($gaoMensual/$row2['Agosto']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['AgostoGAO']+$intereses8R,2));?></td>
					<td><?php if($row4['AgostoGAO'] == 0 || $row['AgostoP'] == 0){print(0);}else{print(round((($row4['AgostoGAO']+$intereses8R)/$row['AgostoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['AgostoGAO']-$gaoMensual+$diferencia8Intereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($gaoMensual*8,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0 || $row2['Agosto'] == 0){print(0);}else{print(round(($gaoMensual*8)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroGAO']+$row4['FebreroGAO']+$row4['MarzoGAO']+$row4['AbrilGAO']+$row4['MayoGAO']+$row4['JunioGAO']+$row4['JulioGAO']+$row4['AgostoGAO']+$intereses8A,2));?></td>
					<td><?php if($row4['EneroGAO'] == 0 || $row['EneroP'] == 0 || $row4['FebreroGAO'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoGAO'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilGAO'] == 0 || $row['AbrilP'] == 0 || $row4['MayoGAO'] == 0 || $row['MayoP'] == 0 || $row4['JunioGAO'] == 0 || $row['JunioP'] == 0 || $row4['JulioGAO'] == 0 || $row['JulioP'] == 0 || $row4['AgostoGAO'] == 0 || $row['AgostoP'] == 0){print(0);}else{print(round(($intereses8A+$row4['EneroGAO']+$row4['FebreroGAO']+$row4['MarzoGAO']+$row4['AbrilGAO']+$row4['MayoGAO']+$row4['JunioGAO']+$row4['JulioGAO']+$row4['AgostoGAO'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($agostoGAO,2));?></td>
					<td></td>
					<td>$<?php print(number_format($gaoMensual,2));?></td>
					<td><?php if($row2['Septiembre'] == 0){print(0);}else{print(round($gaoMensual/$row2['Septiembre']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['SeptiembreGAO']+$intereses9R,2));?></td>
					<td><?php if($row4['SeptiembreGAO'] == 0 || $row['SeptiembreP'] == 0){print(0);}else{print(round((($row4['SeptiembreGAO']+$intereses9R)/$row['SeptiembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['SeptiembreGAO']-$gaoMensual+$diferencia9Intereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($gaoMensual*9,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0 || $row2['Agosto'] == 0 || $row2['Septiembre'] == 0){print(0);}else{print(round(($gaoMensual*9)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroGAO']+$row4['FebreroGAO']+$row4['MarzoGAO']+$row4['AbrilGAO']+$row4['MayoGAO']+$row4['JunioGAO']+$row4['JulioGAO']+$row4['AgostoGAO']+$row4['SeptiembreGAO']+$intereses9A,2));?></td>
					<td><?php if($row4['EneroGAO'] == 0 || $row['EneroP'] == 0 || $row4['FebreroGAO'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoGAO'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilGAO'] == 0 || $row['AbrilP'] == 0 || $row4['MayoGAO'] == 0 || $row['MayoP'] == 0 || $row4['JunioGAO'] == 0 || $row['JunioP'] == 0 || $row4['JulioGAO'] == 0 || $row['JulioP'] == 0 || $row4['AgostoGAO'] == 0 || $row['AgostoP'] == 0 || $row4['SeptiembreGAO'] == 0 || $row['SeptiembreP'] == 0){print(0);}else{print(round(($intereses9A+$row4['EneroGAO']+$row4['FebreroGAO']+$row4['MarzoGAO']+$row4['AbrilGAO']+$row4['MayoGAO']+$row4['JunioGAO']+$row4['JulioGAO']+$row4['AgostoGAO']+$row4['SeptiembreGAO'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($septiembreGAO,2));?></td>
					<td></td>
					<td>$<?php print(number_format($gaoMensual,2));?></td>
					<td><?php if($row2['Octubre'] == 0){print(0);}else{print(round($gaoMensual/$row2['Octubre']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['OctubreGAO']+$intereses10R,2));?></td>
					<td><?php if($row4['OctubreGAO'] == 0 || $row['OctubreP'] == 0){print(0);}else{print(round((($row4['OctubreGAO']+$intereses10R)/$row['OctubreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['OctubreGAO']-$gaoMensual+$diferencia10Intereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($gaoMensual*10,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0 || $row2['Agosto'] == 0 || $row2['Septiembre'] == 0 || $row2['Octubre'] == 0){print(0);}else{print(round(($gaoMensual*10)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre']+$row2['Octubre'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroGAO']+$row4['FebreroGAO']+$row4['MarzoGAO']+$row4['AbrilGAO']+$row4['MayoGAO']+$row4['JunioGAO']+$row4['JulioGAO']+$row4['AgostoGAO']+$row4['SeptiembreGAO']+$row4['OctubreGAO']+$intereses10A,2));?></td>
					<td><?php if($row4['EneroGAO'] == 0 || $row['EneroP'] == 0 || $row4['FebreroGAO'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoGAO'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilGAO'] == 0 || $row['AbrilP'] == 0 || $row4['MayoGAO'] == 0 || $row['MayoP'] == 0 || $row4['JunioGAO'] == 0 || $row['JunioP'] == 0 || $row4['JulioGAO'] == 0 || $row['JulioP'] == 0 || $row4['AgostoGAO'] == 0 || $row['AgostoP'] == 0 || $row4['SeptiembreGAO'] == 0 || $row['SeptiembreP'] == 0 || $row4['OctubreGAO'] == 0 || $row['OctubreP'] == 0){print(0);}else{print(round(($intereses10A+$row4['EneroGAO']+$row4['FebreroGAO']+$row4['MarzoGAO']+$row4['AbrilGAO']+$row4['MayoGAO']+$row4['JunioGAO']+$row4['JulioGAO']+$row4['AgostoGAO']+$row4['SeptiembreGAO']+$row4['OctubreGAO'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($octubreGAO,2));?></td>
					<td></td>
					<td>$<?php print(number_format($gaoMensual,2));?></td>
					<td><?php if($row2['Noviembre'] == 0){print(0);}else{print(round($gaoMensual/$row2['Noviembre']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['NoviembreGAO']+$intereses11R,2));?></td>
					<td><?php if($row4['NoviembreGAO'] == 0 || $row['NoviembreP'] == 0){print(0);}else{print(round((($row4['NoviembreGAO']+$intereses11R)/$row['NoviembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['NoviembreGAO']-$gaoMensual+$diferencia11Intereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($gaoMensual*11,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0 || $row2['Agosto'] == 0 || $row2['Septiembre'] == 0 || $row2['Octubre'] == 0 || $row2['Noviembre'] == 0){print(0);}else{print(round(($gaoMensual*11)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre']+$row2['Octubre']+$row2['Noviembre'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroGAO']+$row4['FebreroGAO']+$row4['MarzoGAO']+$row4['AbrilGAO']+$row4['MayoGAO']+$row4['JunioGAO']+$row4['JulioGAO']+$row4['AgostoGAO']+$row4['SeptiembreGAO']+$row4['OctubreGAO']+$row4['NoviembreGAO']+$intereses11A,2));?></td>
					<td><?php if($row4['EneroGAO'] == 0 || $row['EneroP'] == 0 || $row4['FebreroGAO'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoGAO'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilGAO'] == 0 || $row['AbrilP'] == 0 || $row4['MayoGAO'] == 0 || $row['MayoP'] == 0 || $row4['JunioGAO'] == 0 || $row['JunioP'] == 0 || $row4['JulioGAO'] == 0 || $row['JulioP'] == 0 || $row4['AgostoGAO'] == 0 || $row['AgostoP'] == 0 || $row4['SeptiembreGAO'] == 0 || $row['SeptiembreP'] == 0 || $row4['OctubreGAO'] == 0 || $row['OctubreP'] == 0 || $row4['NoviembreGAO'] == 0 || $row['NoviembreP'] == 0){print(0);}else{print(round(($intereses11A+$row4['EneroGAO']+$row4['FebreroGAO']+$row4['MarzoGAO']+$row4['AbrilGAO']+$row4['MayoGAO']+$row4['JunioGAO']+$row4['JulioGAO']+$row4['AgostoGAO']+$row4['SeptiembreGAO']+$row4['OctubreGAO']+$row4['NoviembreGAO'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP']+$row['NoviembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($noviembreGAO,2));?></td>
					<td></td>
					<td>$<?php print(number_format($gaoMensual,2));?></td>
					<td><?php if($row2['Diciembre'] == 0){print(0);}else{print(round($gaoMensual/$row2['Diciembre']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['DiciembreGAO']+$intereses12R,2));?></td>
					<td><?php if($row4['DiciembreGAO'] == 0 || $row['DiciembreP'] == 0){print(0);}else{print(round((($row4['DiciembreGAO']+$intereses12R)/$row['DiciembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['DiciembreGAO']-$gaoMensual+$diferencia12Intereses,2));?></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
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
				<!--EBCL-->
				<tr>
					<th>EBCL</th>
					<td>$<?php print(number_format($ebclEstaTotal,2));?></td>
					<td><?php print(round($ebclEstaTotalPor*100,2));?>%</td>
					<td></td>
					<td>$<?php print(number_format($ebclEstaEnero,2));?></td>
					<td><?php print(round($ebclEstaEneroPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealEnero,2));?></td>
					<td><?php print(round($ebclRealEneroPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealEnero-$ebclEstaEnero,2));?></td>
					<td></td>
					<td>$<?php print(number_format($ebclEstaFebrero,2));?></td>
					<td><?php print(round($ebclEstaFebreroPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealFebrero,2));?></td>
					<td><?php print(round($ebclRealFebreroPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealFebrero-$ebclEstaFebrero,2));?></td>
					<td></td>
					<td>$<?php print(number_format($ebclEstaFebreroAcum,2));?></td>
					<td><?php print(round($ebclEstaFebreroAcumPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealFebreroAcum,2));?></td>
					<td><?php print(round($ebclRealFebreroAcumPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclFebreroAcumT,2));?></td>
					<td></td>
					<td>$<?php print(number_format($ebclEstaMarzo,2));?></td>
					<td><?php print(round($ebclEstaMarzoPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealMarzo,2));?></td>
					<td><?php print(round($ebclRealMarzoPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealMarzo-$ebclEstaMarzo,2));?></td>
					<td></td>
					<td>$<?php print(number_format($ebclEstaMarzoAcum,2));?></td>
					<td><?php print(round($ebclEstaMarzoAcumPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealMarzoAcum,2));?></td>
					<td><?php print(round($ebclRealMarzoAcumPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclMarzoAcumT,2));?></td>
					<td></td>
					<td>$<?php print(number_format($ebclEstaAbril,2));?></td>
					<td><?php print(round($ebclEstaAbrilPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealAbril,2));?></td>
					<td><?php print(round($ebclRealAbrilPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealAbril-$ebclEstaAbril,2));?></td>
					<td></td>
					<td>$<?php print(number_format($ebclEstaAbrilAcum,2));?></td>
					<td><?php print(round($ebclEstaAbrilAcumPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealAbrilAcum,2));?></td>
					<td><?php print(round($ebclRealAbrilAcumPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclAbrilAcumT,2));?></td>
					<td></td>
					<td>$<?php print(number_format($ebclEstaMayo,2));?></td>
					<td><?php print(round($ebclEstaMayoPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealMayo,2));?></td>
					<td><?php print(round($ebclRealMayoPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealMayo-$ebclEstaMayo,2));?></td>
					<td></td>
					<td>$<?php print(number_format($ebclEstaMayoAcum,2));?></td>
					<td><?php print(round($ebclEstaMayoAcumPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealMayoAcum,2));?></td>
					<td><?php print(round($ebclRealMayoAcumPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclMayoAcumT,2));?></td>
					<td></td>
					<td>$<?php print(number_format($ebclEstaJunio,2));?></td>
					<td><?php print(round($ebclEstaJunioPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealJunio,2));?></td>
					<td><?php print(round($ebclRealJunioPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealJunio-$ebclEstaJunio,2));?></td>
					<td></td>
					<td>$<?php print(number_format($ebclEstaJunioAcum,2));?></td>
					<td><?php print(round($ebclEstaJunioAcumPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealJunioAcum,2));?></td>
					<td><?php print(round($ebclRealJunioAcumPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclJunioAcumT,2));?></td>
					<td></td>
					<td>$<?php print(number_format($ebclEstaJulio,2));?></td>
					<td><?php print(round($ebclEstaJulioPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealJulio,2));?></td>
					<td><?php print(round($ebclRealJulioPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealJulio-$ebclEstaJulio,2));?></td>
					<td></td>
					<td>$<?php print(number_format($ebclEstaJulioAcum,2));?></td>
					<td><?php print(round($ebclEstaJulioAcumPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealJulioAcum,2));?></td>
					<td><?php print(round($ebclRealJulioAcumPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclJulioAcumT,2));?></td>
					<td></td>
					<td>$<?php print(number_format($ebclEstaAgosto,2));?></td>
					<td><?php print(round($ebclEstaAgostoPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealAgosto,2));?></td>
					<td><?php print(round($ebclRealAgostoPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealAgosto-$ebclEstaAgosto,2));?></td>
					<td></td>
					<td>$<?php print(number_format($ebclEstaAgostoAcum,2));?></td>
					<td><?php print(round($ebclEstaAgostoAcumPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealAgostoAcum,2));?></td>
					<td><?php print(round($ebclRealAgostoAcumPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclAgostoAcumT,2));?></td>
					<td></td>
					<td>$<?php print(number_format($ebclEstaSeptiembre,2));?></td>
					<td><?php print(round($ebclEstaSeptiembrePor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealSeptiembre,2));?></td>
					<td><?php print(round($ebclRealSeptiembrePor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealSeptiembre-$ebclEstaSeptiembre,2));?></td>
					<td></td>
					<td>$<?php print(number_format($ebclEstaSeptiembreAcum,2));?></td>
					<td><?php print(round($ebclEstaSeptiembreAcumPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealSeptiembreAcum,2));?></td>
					<td><?php print(round($ebclRealSeptiembreAcumPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclSeptiembreAcumT,2));?></td>
					<td></td>
					<td>$<?php print(number_format($ebclEstaOctubre,2));?></td>
					<td><?php print(round($ebclEstaOctubrePor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealOctubre,2));?></td>
					<td><?php print(round($ebclRealOctubrePor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealOctubre-$ebclEstaOctubre,2));?></td>
					<td></td>
					<td>$<?php print(number_format($ebclEstaOctubreAcum,2));?></td>
					<td><?php print(round($ebclEstaOctubreAcumPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealOctubreAcum,2));?></td>
					<td><?php print(round($ebclRealOctubreAcumPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclOctubreAcumT,2));?></td>
					<td></td>
					<td>$<?php print(number_format($ebclEstaNoviembre,2));?></td>
					<td><?php print(round($ebclEstaNoviembrePor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealNoviembre,2));?></td>
					<td><?php print(round($ebclRealNoviembrePor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealNoviembre-$ebclEstaNoviembre,2));?></td>
					<td></td>
					<td>$<?php print(number_format($ebclEstaNoviembreAcum,2));?></td>
					<td><?php print(round($ebclEstaNoviembreAcumPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealNoviembreAcum,2));?></td>
					<td><?php print(round($ebclRealNoviembreAcumPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclNoviembreAcumT,2));?></td>
					<td></td>
					<td>$<?php print(number_format($ebclEstaDiciembre,2));?></td>
					<td><?php print(round($ebclEstaDiciembrePor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealDiciembre,2));?></td>
					<td><?php print(round($ebclRealDiciembrePor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealDiciembre-$ebclEstaDiciembre,2));?></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
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
				<!--GAO VARIABLE-->
				<tr>
					<th>GAO Variable</th>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
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
				$oferta = ($row5['oferta']*100).'%';
			?>
				<!--OFERTA-->
				<tr>
					<th>Oferta</th>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
				</tr>
				<!--COMISIÓN-->
				<tr>
					<th>Comisión</th>
					<td>$<?php print(number_format($row2['TOTAL']*$comision_por_p,2));?></td>
					<td><?php if($row2['TOTAL'] == 0){print(0);} else{print(round(($row2['TOTAL']*$comision_por_p)/$row2['TOTAL']*100,2));}?>%</td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision1,2));?></td>
					<td><?php if($row2['Enero'] == 0){print(0);} else{print(round($diferenciaComision1/$row2['Enero'],2)*100);}?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR1,2));?></td>
					<td><?php print(round($diferenciaComisionRPor1*100,2));?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR1-$diferenciaComision1,2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision2,2));?></td>
					<td><?php if($row2['Febrero'] == 0){print(0);} else{print(round($diferenciaComision2/$row2['Febrero'],2)*100);}?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR2,2));?></td>
					<td><?php print(round($diferenciaComisionRPor2*100,2));?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR2-$diferenciaComision2,2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision1+$diferenciaComision2,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0){print(0);} else{print(round(($diferenciaComision1+$diferenciaComision2)/($row2['Enero']+$row2['Febrero'])*100,2));}?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR1+$diferenciaComisionR2,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0){print(0);}else{print(round(($diferenciaComisionR1+$diferenciaComisionR2)/($row['EneroP']+$row['FebreroP'])*100,2));}?>%</td>
					<td>$<?php print(number_format(($diferenciaComisionR1+$diferenciaComisionR2)-($diferenciaComision1+$diferenciaComision2),2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision3,2));?></td>
					<td><?php if($row2['Marzo'] == 0){print(0);} else{print(round($diferenciaComision3/$row2['Marzo'],2)*100);}?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR3,2));?></td>
					<td><?php print(round($diferenciaComisionRPor3*100,2));?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR3-$diferenciaComision3,2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision1+$diferenciaComision2+$diferenciaComision3,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0){print(0);} else{print(round(($diferenciaComision1+$diferenciaComision2+$diferenciaComision3)/($row2['Enero']+$row2['Febrero']+$row2['Marzo'])*100,2));}?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0){print(0);}else{print(round(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3)/($row['EneroP']+$row['FebreroP']+$row['MarzoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3)-($diferenciaComision1+$diferenciaComision2+$diferenciaComision3),2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision4,2));?></td>
					<td><?php if($row2['Abril'] == 0){print(0);} else{print(round($diferenciaComision4/$row2['Abril'],2)*100);}?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR4,2));?></td>
					<td><?php print(round($diferenciaComisionRPor4*100,2));?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR4-$diferenciaComision4,2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0){print(0);} else{print(round(($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril'])*100,2));}?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0){print(0);}else{print(round(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP'])*100,2));}?>%</td>
					<td>$<?php print(number_format(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4)-($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4),2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision5,2));?></td>
					<td><?php if($row2['Mayo'] == 0){print(0);} else{print(round($diferenciaComision5/$row2['Mayo'],2)*100);}?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR5,2));?></td>
					<td><?php print(round($diferenciaComisionRPor5*100,2));?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR5-$diferenciaComision5,2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0){print(0);} else{print(round(($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo'])*100,2));}?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0){print(0);}else{print(round(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5)-($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5),2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision6,2));?></td>
					<td><?php if($row2['Junio'] == 0){print(0);} else{print(round($diferenciaComision6/$row2['Junio'],2)*100);}?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR6,2));?></td>
					<td><?php print(round($diferenciaComisionRPor6*100,2));?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR6-$diferenciaComision6,2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0){print(0);} else{print(round(($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio'])*100,2));}?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0){print(0);}else{print(round(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6)-($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6),2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision7,2));?></td>
					<td><?php if($row2['Julio'] == 0){print(0);} else{print(round($diferenciaComision7/$row2['Julio'],2)*100);}?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR7,2));?></td>
					<td><?php print(round($diferenciaComisionRPor7*100,2));?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR7-$diferenciaComision7,2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6+$diferenciaComision7,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0){print(0);} else{print(round(($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6+$diferenciaComision7)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio'])*100,2));}?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6+$diferenciaComisionR7,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0 || $row['JulioP'] == 0){print(0);}else{print(round(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6+$diferenciaComisionR7)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6+$diferenciaComisionR7)-($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6+$diferenciaComision7),2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision8,2));?></td>
					<td><?php if($row2['Agosto'] == 0){print(0);} else{print(round($diferenciaComision8/$row2['Agosto'],2)*100);}?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR8,2));?></td>
					<td><?php print(round($diferenciaComisionRPor8*100,2));?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR8-$diferenciaComision8,2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6+$diferenciaComision7+$diferenciaComision8,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0 || $row2['Agosto'] == 0){print(0);} else{print(round(($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6+$diferenciaComision7+$diferenciaComision8)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto'])*100,2));}?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6+$diferenciaComisionR7+$diferenciaComisionR8,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0 || $row['JulioP'] == 0 || $row['AgostoP'] == 0){print(0);}else{print(round(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6+$diferenciaComisionR7+$diferenciaComisionR8)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6+$diferenciaComisionR7+$diferenciaComisionR8)-($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6+$diferenciaComision7+$diferenciaComision8),2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision9,2));?></td>
					<td><?php if($row2['Septiembre'] == 0){print(0);} else{print(round($diferenciaComision9/$row2['Septiembre'],2)*100);}?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR9,2));?></td>
					<td><?php print(round($diferenciaComisionRPor9*100,2));?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR9-$diferenciaComision9,2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6+$diferenciaComision7+$diferenciaComision8+$diferenciaComision9,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0 || $row2['Agosto'] == 0 || $row2['Septiembre'] == 0){print(0);} else{print(round(($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6+$diferenciaComision7+$diferenciaComision8+$diferenciaComision9)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre'])*100,2));}?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6+$diferenciaComisionR7+$diferenciaComisionR8+$diferenciaComisionR9,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0 || $row['JulioP'] == 0 || $row['AgostoP'] == 0 || $row['SeptiembreP'] == 0){print(0);}else{print(round(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6+$diferenciaComisionR7+$diferenciaComisionR8+$diferenciaComisionR9)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6+$diferenciaComisionR7+$diferenciaComisionR8+$diferenciaComisionR9)-($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6+$diferenciaComision7+$diferenciaComision8+$diferenciaComision9),2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision10,2));?></td>
					<td><?php if($row2['Octubre'] == 0){print(0);} else{print(round($diferenciaComision10/$row2['Octubre'],2)*100);}?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR10,2));?></td>
					<td><?php print(round($diferenciaComisionRPor10*100,2));?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR10-$diferenciaComision10,2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6+$diferenciaComision7+$diferenciaComision8+$diferenciaComision9+$diferenciaComision10,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0 || $row2['Agosto'] == 0 || $row2['Septiembre'] == 0 || $row2['Octubre'] == 0){print(0);} else{print(round(($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6+$diferenciaComision7+$diferenciaComision8+$diferenciaComision9+$diferenciaComision10)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre']+$row2['Octubre'])*100,2));}?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6+$diferenciaComisionR7+$diferenciaComisionR8+$diferenciaComisionR9+$diferenciaComisionR10,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0 || $row['JulioP'] == 0 || $row['AgostoP'] == 0 || $row['SeptiembreP'] == 0 || $row['OctubreP'] == 0){print(0);}else{print(round(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6+$diferenciaComisionR7+$diferenciaComisionR8+$diferenciaComisionR9+$diferenciaComisionR10)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6+$diferenciaComisionR7+$diferenciaComisionR8+$diferenciaComisionR9+$diferenciaComisionR10)-($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6+$diferenciaComision7+$diferenciaComision8+$diferenciaComision9+$diferenciaComision10),2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision11,2));?></td>
					<td><?php if($row2['Noviembre'] == 0){print(0);} else{print(round($diferenciaComision11/$row2['Noviembre'],2)*100);}?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR11,2));?></td>
					<td><?php print(round($diferenciaComisionRPor11*100,2));?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR11-$diferenciaComision11,2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6+$diferenciaComision7+$diferenciaComision8+$diferenciaComision9+$diferenciaComision10+$diferenciaComision11,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0 || $row2['Agosto'] == 0 || $row2['Septiembre'] == 0 || $row2['Octubre'] == 0 || $row2['Noviembre'] == 0){print(0);} else{print(round(($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6+$diferenciaComision7+$diferenciaComision8+$diferenciaComision9+$diferenciaComision10+$diferenciaComision11)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre']+$row2['Octubre']+$row2['Noviembre'])*100,2));}?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6+$diferenciaComisionR7+$diferenciaComisionR8+$diferenciaComisionR9+$diferenciaComisionR10+$diferenciaComisionR11,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0 || $row['JulioP'] == 0 || $row['AgostoP'] == 0 || $row['SeptiembreP'] == 0 || $row['OctubreP'] == 0 || $row['NoviembreP'] == 0){print(0);}else{print(round(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6+$diferenciaComisionR7+$diferenciaComisionR8+$diferenciaComisionR9+$diferenciaComisionR10+$diferenciaComisionR11)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP']+$row['NoviembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6+$diferenciaComisionR7+$diferenciaComisionR8+$diferenciaComisionR9+$diferenciaComisionR10+$diferenciaComisionR11)-($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6+$diferenciaComision7+$diferenciaComision8+$diferenciaComision9+$diferenciaComision10+$diferenciaComision11),2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision12,2));?></td>
					<td><?php if($row2['Diciembre'] == 0){print(0);} else{print(round($diferenciaComision12/$row2['Diciembre'],2)*100);}?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR12,2));?></td>
					<td><?php print(round($diferenciaComisionRPor12*100,2));?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR12-$diferenciaComision12,2));?></td>
				</tr>
				<!--ANTICIPO-->
				<tr>
					<th>Anticipo</th>
					<td>$<?php print(number_format($row5['TotalAnticipo'],2)); ?></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['Enero'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Enero'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['Febrero'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Febrero'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['Enero']+$row5['Febrero'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Enero']+$row5['Febrero'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['Marzo'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Marzo'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['Enero']+$row5['Febrero']+$row5['Marzo'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Enero']+$row5['Febrero']+$row5['Marzo'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['Abril'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Abril'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['Mayo'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Mayo'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['Junio'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Junio'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['Julio'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Julio'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio']+$row5['Julio'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio']+$row5['Julio'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['Agosto'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Agosto'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio']+$row5['Julio']+$row5['Agosto'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio']+$row5['Julio']+$row5['Agosto'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['Septiembre'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Septiembre'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio']+$row5['Julio']+$row5['Agosto']+$row5['Septiembre'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio']+$row5['Julio']+$row5['Agosto']+$row5['Septiembre'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['Octubre'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Octubre'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio']+$row5['Julio']+$row5['Agosto']+$row5['Septiembre']+$row5['Octubre'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio']+$row5['Julio']+$row5['Agosto']+$row5['Septiembre']+$row5['Octubre'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['Noviembre'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Noviembre'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio']+$row5['Julio']+$row5['Agosto']+$row5['Septiembre']+$row5['Octubre']+$row5['Noviembre'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio']+$row5['Julio']+$row5['Agosto']+$row5['Septiembre']+$row5['Octubre']+$row5['Noviembre'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['Diciembre'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Diciembre'],2)); ?></td>
					<td></td>
					<td></td>
				</tr>
				<!--COMPLEMENTARIO-->
				<tr>
					<th>Complementario</th>
					<td>$<?php print(number_format($row5['TotalComplementario'],2)); ?></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['EneroComplementario'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['EneroComplementario'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['FebreroComplementario'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['FebreroComplementario'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['EneroComplementario']+$row5['FebreroComplementario'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['EneroComplementario']+$row5['FebreroComplementario'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['MarzoComplementario'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['MarzoComplementario'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['EneroComplementario']+$row5['FebreroComplementario']+$row5['MarzoComplementario'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['EneroComplementario']+$row5['FebreroComplementario']+$row5['MarzoComplementario'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['AbrilComplementario'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['AbrilComplementario'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['EneroComplementario']+$row5['FebreroComplementario']+$row5['MarzoComplementario']+$row5['AbrilComplementario'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['EneroComplementario']+$row5['FebreroComplementario']+$row5['MarzoComplementario']+$row5['AbrilComplementario'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['MayoComplementario'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['MayoComplementario'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['EneroComplementario']+$row5['FebreroComplementario']+$row5['MarzoComplementario']+$row5['AbrilComplementario']+$row5['MayoComplementario'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['EneroComplementario']+$row5['FebreroComplementario']+$row5['MarzoComplementario']+$row5['AbrilComplementario']+$row5['MayoComplementario'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['JunioComplementario'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['JunioComplementario'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['EneroComplementario']+$row5['FebreroComplementario']+$row5['MarzoComplementario']+$row5['AbrilComplementario']+$row5['MayoComplementario']+$row5['JunioComplementario'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['EneroComplementario']+$row5['FebreroComplementario']+$row5['MarzoComplementario']+$row5['AbrilComplementario']+$row5['MayoComplementario']+$row5['JunioComplementario'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['JulioComplementario'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['JulioComplementario'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['EneroComplementario']+$row5['FebreroComplementario']+$row5['MarzoComplementario']+$row5['AbrilComplementario']+$row5['MayoComplementario']+$row5['JunioComplementario']+$row5['JulioComplementario'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['EneroComplementario']+$row5['FebreroComplementario']+$row5['MarzoComplementario']+$row5['AbrilComplementario']+$row5['MayoComplementario']+$row5['JunioComplementario']+$row5['JulioComplementario'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['AgostoComplementario'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['AgostoComplementario'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['EneroComplementario']+$row5['FebreroComplementario']+$row5['MarzoComplementario']+$row5['AbrilComplementario']+$row5['MayoComplementario']+$row5['JunioComplementario']+$row5['JulioComplementario']+$row5['AgostoComplementario'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['EneroComplementario']+$row5['FebreroComplementario']+$row5['MarzoComplementario']+$row5['AbrilComplementario']+$row5['MayoComplementario']+$row5['JunioComplementario']+$row5['JulioComplementario']+$row5['AgostoComplementario'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['SeptiembreComplementario'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['SeptiembreComplementario'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['EneroComplementario']+$row5['FebreroComplementario']+$row5['MarzoComplementario']+$row5['AbrilComplementario']+$row5['MayoComplementario']+$row5['JunioComplementario']+$row5['JulioComplementario']+$row5['AgostoComplementario']+$row5['SeptiembreComplementario'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['EneroComplementario']+$row5['FebreroComplementario']+$row5['MarzoComplementario']+$row5['AbrilComplementario']+$row5['MayoComplementario']+$row5['JunioComplementario']+$row5['JulioComplementario']+$row5['AgostoComplementario']+$row5['SeptiembreComplementario'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['OctubreComplementario'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['OctubreComplementario'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['EneroComplementario']+$row5['FebreroComplementario']+$row5['MarzoComplementario']+$row5['AbrilComplementario']+$row5['MayoComplementario']+$row5['JunioComplementario']+$row5['JulioComplementario']+$row5['AgostoComplementario']+$row5['SeptiembreComplementario']+$row5['OctubreComplementario'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['EneroComplementario']+$row5['FebreroComplementario']+$row5['MarzoComplementario']+$row5['AbrilComplementario']+$row5['MayoComplementario']+$row5['JunioComplementario']+$row5['JulioComplementario']+$row5['AgostoComplementario']+$row5['SeptiembreComplementario']+$row5['OctubreComplementario'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['NoviembreComplementario'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['NoviembreComplementario'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['EneroComplementario']+$row5['FebreroComplementario']+$row5['MarzoComplementario']+$row5['AbrilComplementario']+$row5['MayoComplementario']+$row5['JunioComplementario']+$row5['JulioComplementario']+$row5['AgostoComplementario']+$row5['SeptiembreComplementario']+$row5['OctubreComplementario']+$row5['NoviembreComplementario'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['EneroComplementario']+$row5['FebreroComplementario']+$row5['MarzoComplementario']+$row5['AbrilComplementario']+$row5['MayoComplementario']+$row5['JunioComplementario']+$row5['JulioComplementario']+$row5['AgostoComplementario']+$row5['SeptiembreComplementario']+$row5['OctubreComplementario']+$row5['NoviembreComplementario'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['DiciembreComplementario'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['DiciembreComplementario'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['EneroComplementario']+$row5['FebreroComplementario']+$row5['MarzoComplementario']+$row5['AbrilComplementario']+$row5['MayoComplementario']+$row5['JunioComplementario']+$row5['JulioComplementario']+$row5['AgostoComplementario']+$row5['SeptiembreComplementario']+$row5['OctubreComplementario']+$row5['NoviembreComplementario']+$row5['DiciembreComplementario'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['EneroComplementario']+$row5['FebreroComplementario']+$row5['MarzoComplementario']+$row5['AbrilComplementario']+$row5['MayoComplementario']+$row5['JunioComplementario']+$row5['JulioComplementario']+$row5['AgostoComplementario']+$row5['SeptiembreComplementario']+$row5['OctubreComplementario']+$row5['NoviembreComplementario']+$row5['DiciembreComplementario'],2)); ?></td>
					<td></td>
					<td></td>
				</tr>
				<!--SALDO -->
				<tr>
					<th>Saldo</th>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision1-$row5['Enero']-$row5['EneroComplementario'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComisionR1-$row5['Enero']-$row5['EneroComplementario'],2));?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision2-$row5['Febrero']-$row5['FebreroComplementario'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComisionR2-$row5['Febrero']-$row5['FebreroComplementario'],2));?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format(($diferenciaComision1+$diferenciaComision2)-($row5['Enero']+$row5['Febrero']+$row5['EneroComplementario']+$row5['FebreroComplementario']),2));?></td>
					<td></td>
					<td>$<?php print(number_format(($diferenciaComisionR1+$diferenciaComisionR2)-($row5['Enero']+$row5['Febrero']+$row5['EneroComplementario']+$row5['FebreroComplementario']),2));?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision3-$row5['Marzo']-$row5['MarzoComplementario'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComisionR3-$row5['Marzo']-$row5['MarzoComplementario'],2));?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format(($diferenciaComision1+$diferenciaComision2+$diferenciaComision3)-($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['EneroComplementario']+$row5['FebreroComplementario']+$row5['MarzoComplementario']),2));?></td>
					<td></td>
					<td>$<?php print(number_format(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3)-($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['EneroComplementario']+$row5['FebreroComplementario']+$row5['MarzoComplementario']),2));?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision4-$row5['Abril']-$row5['AbrilComplementario'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComisionR4-$row5['Abril']-$row5['AbrilComplementario'],2));?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format(($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4)-($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['EneroComplementario']+$row5['FebreroComplementario']+$row5['MarzoComplementario']+$row5['AbrilComplementario']),2));?></td>
					<td></td>
					<td>$<?php print(number_format(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4)-($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['EneroComplementario']+$row5['FebreroComplementario']+$row5['MarzoComplementario']+$row5['AbrilComplementario']),2));?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision5-$row5['Mayo']-$row5['MayoComplementario'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComisionR5-$row5['Mayo']-$row5['MayoComplementario'],2));?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format(($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5)-($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['EneroComplementario']+$row5['FebreroComplementario']+$row5['MarzoComplementario']+$row5['AbrilComplementario']+$row5['MayoComplementario']),2));?></td>
					<td></td>
					<td>$<?php print(number_format(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5)-($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['EneroComplementario']+$row5['FebreroComplementario']+$row5['MarzoComplementario']+$row5['AbrilComplementario']+$row5['MayoComplementario']),2));?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision6-$row5['Junio']-$row5['JunioComplementario'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComisionR6-$row5['Junio']-$row5['JunioComplementario'],2));?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format(($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6)-($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio']+$row5['EneroComplementario']+$row5['FebreroComplementario']+$row5['MarzoComplementario']+$row5['AbrilComplementario']+$row5['MayoComplementario']+$row5['JunioComplementario']),2));?></td>
					<td></td>
					<td>$<?php print(number_format(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6)-($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio']+$row5['EneroComplementario']+$row5['FebreroComplementario']+$row5['MarzoComplementario']+$row5['AbrilComplementario']+$row5['MayoComplementario']+$row5['JunioComplementario']),2));?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision7-$row5['Julio']-$row5['JulioComplementario'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComisionR7-$row5['Julio']-$row5['JulioComplementario'],2));?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format(($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6+$diferenciaComision7)-($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio']+$row5['Julio']+$row5['EneroComplementario']+$row5['FebreroComplementario']+$row5['MarzoComplementario']+$row5['AbrilComplementario']+$row5['MayoComplementario']+$row5['JunioComplementario']+$row5['JulioComplementario']),2));?></td>
					<td></td>
					<td>$<?php print(number_format(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6+$diferenciaComisionR7)-($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio']+$row5['Julio']++$row5['EneroComplementario']+$row5['FebreroComplementario']+$row5['MarzoComplementario']+$row5['AbrilComplementario']+$row5['MayoComplementario']+$row5['JunioComplementario']+$row5['JulioComplementario']),2));?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision8-$row5['Agosto']-$row5['AgostoComplementario'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComisionR8-$row5['Agosto']-$row5['AgostoComplementario'],2));?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format(($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6+$diferenciaComision7+$diferenciaComision8)-($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio']+$row5['Julio']+$row5['Agosto']+$row5['EneroComplementario']+$row5['FebreroComplementario']+$row5['MarzoComplementario']+$row5['AbrilComplementario']+$row5['MayoComplementario']+$row5['JunioComplementario']+$row5['JulioComplementario']+$row5['AgostoComplementario']),2));?></td>
					<td></td>
					<td>$<?php print(number_format(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6+$diferenciaComisionR7+$diferenciaComisionR8)-($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio']+$row5['Julio']+$row5['Agosto']++$row5['EneroComplementario']+$row5['FebreroComplementario']+$row5['MarzoComplementario']+$row5['AbrilComplementario']+$row5['MayoComplementario']+$row5['JunioComplementario']+$row5['JulioComplementario']+$row5['AgostoComplementario']),2));?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision9-$row5['Septiembre']-$row5['SeptiembreComplementario'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComisionR9-$row5['Septiembre']-$row5['SeptiembreComplementario'],2));?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format(($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6+$diferenciaComision7+$diferenciaComision8+$diferenciaComision9)-($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio']+$row5['Julio']+$row5['Agosto']+$row5['Septiembre']+$row5['EneroComplementario']+$row5['FebreroComplementario']+$row5['MarzoComplementario']+$row5['AbrilComplementario']+$row5['MayoComplementario']+$row5['JunioComplementario']+$row5['JulioComplementario']+$row5['AgostoComplementario']+$row5['SeptiembreComplementario']),2));?></td>
					<td></td>
					<td>$<?php print(number_format(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6+$diferenciaComisionR7+$diferenciaComisionR8+$diferenciaComisionR9)-($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio']+$row5['Julio']+$row5['Agosto']+$row5['Septiembre']+$row5['EneroComplementario']+$row5['FebreroComplementario']+$row5['MarzoComplementario']+$row5['AbrilComplementario']+$row5['MayoComplementario']+$row5['JunioComplementario']+$row5['JulioComplementario']+$row5['AgostoComplementario']+$row5['SeptiembreComplementario']),2));?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision10-$row5['Octubre']-$row5['OctubreComplementario'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComisionR10-$row5['Octubre']-$row5['OctubreComplementario'],2));?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format(($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6+$diferenciaComision7+$diferenciaComision8+$diferenciaComision9+$diferenciaComision10)-($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio']+$row5['Julio']+$row5['Agosto']+$row5['Septiembre']+$row5['Octubre']+$row5['EneroComplementario']+$row5['FebreroComplementario']+$row5['MarzoComplementario']+$row5['AbrilComplementario']+$row5['MayoComplementario']+$row5['JunioComplementario']+$row5['JulioComplementario']+$row5['AgostoComplementario']+$row5['SeptiembreComplementario']+$row5['OctubreComplementario']),2));?></td>
					<td></td>
					<td>$<?php print(number_format(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6+$diferenciaComisionR7+$diferenciaComisionR8+$diferenciaComisionR9+$diferenciaComisionR10)-($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio']+$row5['Julio']+$row5['Agosto']+$row5['Septiembre']+$row5['Octubre']+$row5['EneroComplementario']+$row5['FebreroComplementario']+$row5['MarzoComplementario']+$row5['AbrilComplementario']+$row5['MayoComplementario']+$row5['JunioComplementario']+$row5['JulioComplementario']+$row5['AgostoComplementario']+$row5['SeptiembreComplementario']+$row5['OctubreComplementario']),2));?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision11-$row5['Noviembre']-$row5['NoviembreComplementario'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComisionR11-$row5['Noviembre']-$row5['NoviembreComplementario'],2));?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format(($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6+$diferenciaComision7+$diferenciaComision8+$diferenciaComision9+$diferenciaComision10+$diferenciaComision11)-($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio']+row5['Julio']+$row5['Agosto']+$row5['Septiembre']+$row5['Octubre']+$row5['Noviembre']+$row5['EneroComplementario']+$row5['FebreroComplementario']+$row5['MarzoComplementario']+$row5['AbrilComplementario']+$row5['MayoComplementario']+$row5['JunioComplementario']+row5['JulioComplementario']+$row5['AgostoComplementario']+$row5['SeptiembreComplementario']+$row5['OctubreComplementario']+$row5['NoviembreComplementario']),2));?></td>
					<td></td>
					<td>$<?php print(number_format(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6+$diferenciaComisionR7+$diferenciaComisionR8+$diferenciaComisionR9+$diferenciaComisionR10+$diferenciaComisionR11)-($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio']+$row5['Julio']+$row5['Agosto']+$row5['Septiembre']+$row5['Octubre']+$row5['Noviembre']+$row5['EneroComplementario']+$row5['FebreroComplementario']+$row5['MarzoComplementario']+$row5['AbrilComplementario']+$row5['MayoComplementario']+$row5['JunioComplementario']+row5['JulioComplementario']+$row5['AgostoComplementario']+$row5['SeptiembreComplementario']+$row5['OctubreComplementario']+$row5['NoviembreComplementario']),2));?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision12-$row5['Diciembre']-$row5['DiciembreComplementario'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComisionR12-$row5['Diciembre']-$row5['DiciembreComplementario'],2));?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format(($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6+$diferenciaComision7+$diferenciaComision8+$diferenciaComision9+$diferenciaComision10+$diferenciaComision11+$diferenciaComision12)-($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio']+row5['Julio']+$row5['Agosto']+$row5['Septiembre']+$row5['Octubre']+$row5['Noviembre']+$row5['Diciembre']+$row5['EneroComplementario']+$row5['FebreroComplementario']+$row5['MarzoComplementario']+$row5['AbrilComplementario']+$row5['MayoComplementario']+$row5['JunioComplementario']+row5['JulioComplementario']+$row5['AgostoComplementario']+$row5['SeptiembreComplementario']+$row5['OctubreComplementario']+$row5['NoviembreComplementario']+$row5['DiciembreComplementario']),2));?></td>
					<td></td>
					<td>$<?php print(number_format(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6+$diferenciaComisionR7+$diferenciaComisionR8+$diferenciaComisionR9+$diferenciaComisionR10+$diferenciaComisionR11+$diferenciaComisionR12)-($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio']+$row5['Julio']+$row5['Agosto']+$row5['Septiembre']+$row5['Octubre']+$row5['Noviembre']+$row5['Diciembre']+$row5['EneroComplementario']+$row5['FebreroComplementario']+$row5['MarzoComplementario']+$row5['AbrilComplementario']+$row5['MayoComplementario']+$row5['JunioComplementario']+row5['JulioComplementario']+$row5['AgostoComplementario']+$row5['SeptiembreComplementario']+$row5['OctubreComplementario']+$row5['NoviembreComplementario']+$row5['DiciembreComplementario']),2));?></td>
					<td></td>
					<td></td>
				</tr>
				<!--MARGEN AJUSTADO-->
				<tr>
					<th >Margen Ajustado</th>
					<td>$<?php print(number_format($margenAjustadoTotalP,2));?></td>
					<td><?php if($row2['TOTAL'] == 0){print(0);} else{print(round($margenAjustadoTotalP/$row2['TOTAL']*100,2));}?>%</td>
					<td></td>
					<td>$<?php print(number_format($margenAjustado1P,2));?></td>
					<td><?php if($row2['Enero'] == 0){print(0);} else{print(round($margenAjustado1P/$row2['Enero']*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado1R,2));?></td>
					<td><?php print(round($margenAjustadoPor1R*100,2));?>%</td>
					<td>$<?php print(number_format($margenAjustado1R-$margenAjustado1P,2));?></td>
					<td></td>
					<td>$<?php print(number_format($margenAjustado2P,2));?></td>
					<td><?php if($row2['Febrero'] == 0){print(0);} else{print(round($margenAjustado2P/$row2['Febrero']*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado2R,2));?></td>
					<td><?php print(round($margenAjustadoPor2R*100,2));?>%</td>
					<td>$<?php print(number_format($margenAjustado2R-$margenAjustado2P,2));?></td>
					<td></td>
					<td>$<?php print(number_format($margenAjustado2PA,2));?></td>
					<td><?php if($row2['Enero']==0||$row2['Febrero']==0){print(0);} else{print(round(($margenAjustado1P+$margenAjustado2P)/($row2['Enero']+$row2['Febrero'])*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado2RA,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0){print(0);}else{print(round(($margenAjustado1R+$margenAjustado2R)/($row['EneroP']+$row['FebreroP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado2RA-$margenAjustado2PA,2));?></td>
					<td></td>
					<td>$<?php print(number_format($margenAjustado3P,2));?></td>
					<td><?php if($row2['Marzo'] == 0){print(0);} else{print(round($margenAjustado3P/$row2['Marzo']*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado3R,2));?></td>
					<td><?php print(round($margenAjustadoPor3R*100,2));?>%</td>
					<td>$<?php print(number_format($margenAjustado3R-$margenAjustado3P,2));?></td>
					<td></td>
					<td>$<?php print(number_format($margenAjustado1P+$margenAjustado2P+$margenAjustado3P,2));?></td>
					<td><?php if($row2['Enero']==0||$row2['Febrero']==0||$row2['Marzo']==0){print(0);} else{print(round(($margenAjustado1P+$margenAjustado2P+$margenAjustado3P)/($row2['Enero']+$row2['Febrero']+$row2['Marzo'])*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado1R+$margenAjustado2R+$margenAjustado3R,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0){print(0);}else{print(round(($margenAjustado1R+$margenAjustado2R+$margenAjustado3R)/($row['EneroP']+$row['FebreroP']+$row['MarzoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado3RA-$margenAjustado3PA,2));?></td>
					<td></td>
					<td>$<?php print(number_format($margenAjustado4P,2));?></td>
					<td><?php if($row2['Abril'] == 0){print(0);} else{print(round($margenAjustado4P/$row2['Abril']*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado4R,2));?></td>
					<td><?php print(round($margenAjustadoPor4R*100,2));?>%</td>
					<td>$<?php print(number_format($margenAjustado4R-$margenAjustado4P,2));?></td>
					<td></td>
					<td>$<?php print(number_format($margenAjustado1P+$margenAjustado2P+$margenAjustado3P+$margenAjustado4P,2));?></td>
					<td><?php if($row2['Enero']==0||$row2['Febrero']==0||$row2['Marzo']==0||$row2['Abril']==0){print(0);} else{print(round(($margenAjustado1P+$margenAjustado2P+$margenAjustado3P+$margenAjustado4P)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril'])*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado1R+$margenAjustado2R+$margenAjustado3R+$margenAjustado4R,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0){print(0);}else{print(round(($margenAjustado1R+$margenAjustado2R+$margenAjustado3R+$margenAjustado4R)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado4RA-$margenAjustado4PA,2));?></td>
					<td></td>
					<td>$<?php print(number_format($margenAjustado5P,2));?></td>
					<td><?php if($row2['Mayo'] == 0){print(0);} else{print(round($margenAjustado5P/$row2['Mayo']*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado5R,2));?></td>
					<td><?php print(round($margenAjustadoPor5R*100,2));?>%</td>
					<td>$<?php print(number_format($margenAjustado5R-$margenAjustado5P,2));?></td>
					<td></td>
					<td>$<?php print(number_format($margenAjustado1P+$margenAjustado2P+$margenAjustado3P+$margenAjustado4P+$margenAjustado5P,2));?></td>
					<td><?php if($row2['Enero']==0||$row2['Febrero']==0||$row2['Marzo']==0||$row2['Abril']==0||$row2['Mayo']==0){print(0);} else{print(round(($margenAjustado1P+$margenAjustado2P+$margenAjustado3P+$margenAjustado4P+$margenAjustado5P)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo'])*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado1R+$margenAjustado2R+$margenAjustado3R+$margenAjustado4R+$margenAjustado5R,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0){print(0);}else{print(round(($margenAjustado1R+$margenAjustado2R+$margenAjustado3R+$margenAjustado4R+$margenAjustado5R)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado5RA-$margenAjustado5PA,2));?></td>
					<td></td>
					<td>$<?php print(number_format($margenAjustado6P,2));?></td>
					<td><?php if($row2['Junio'] == 0){print(0);} else{print(round($margenAjustado6P/$row2['Junio']*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado6R,2));?></td>
					<td><?php print(round($margenAjustadoPor6R*100,2));?>%</td>
					<td>$<?php print(number_format($margenAjustado6R-$margenAjustado6P,2));?></td>
					<td></td>
					<td>$<?php print(number_format($margenAjustado1P+$margenAjustado2P+$margenAjustado3P+$margenAjustado4P+$margenAjustado5P+$margenAjustado6P,2));?></td>
					<td><?php if($row2['Enero']==0||$row2['Febrero']==0||$row2['Marzo']==0||$row2['Abril']==0||$row2['Mayo']==0||$row2['Junio']==0){print(0);} else{print(round(($margenAjustado1P+$margenAjustado2P+$margenAjustado3P+$margenAjustado4P+$margenAjustado5P+$margenAjustado6P)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio'])*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado1R+$margenAjustado2R+$margenAjustado3R+$margenAjustado4R+$margenAjustado5R+$margenAjustado6R,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0){print(0);}else{print(round(($margenAjustado1R+$margenAjustado2R+$margenAjustado3R+$margenAjustado4R+$margenAjustado5R+$margenAjustado6R)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado6RA-$margenAjustado6PA,2));?></td>
					<td></td>
					<td>$<?php print(number_format($margenAjustado7P,2));?></td>
					<td><?php if($row2['Julio'] == 0){print(0);} else{print(round($margenAjustado7P/$row2['Julio']*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado7R,2));?></td>
					<td><?php print(round($margenAjustadoPor7R*100,2));?>%</td>
					<td>$<?php print(number_format($margenAjustado7R-$margenAjustado7P,2));?></td>
					<td></td>
					<td>$<?php print(number_format($margenAjustado1P+$margenAjustado2P+$margenAjustado3P+$margenAjustado4P+$margenAjustado5P+$margenAjustado6P+$margenAjustado7P,2));?></td>
					<td><?php if($row2['Enero']==0||$row2['Febrero']==0||$row2['Marzo']==0||$row2['Abril']==0||$row2['Mayo']==0||$row2['Junio']==0||$row2['Julio']==0){print(0);} else{print(round(($margenAjustado1P+$margenAjustado2P+$margenAjustado3P+$margenAjustado4P+$margenAjustado5P+$margenAjustado6P+$margenAjustado7P)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio'])*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado1R+$margenAjustado2R+$margenAjustado3R+$margenAjustado4R+$margenAjustado5R+$margenAjustado6R+$margenAjustado7R,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0 || $row['JulioP'] == 0){print(0);}else{print(round(($margenAjustado1R+$margenAjustado2R+$margenAjustado3R+$margenAjustado4R+$margenAjustado5R+$margenAjustado6R+$margenAjustado7R)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado7RA-$margenAjustado7PA,2));?></td>
					<td></td>
					<td>$<?php print(number_format($margenAjustado8P,2));?></td>
					<td><?php if($row2['Agosto'] == 0){print(0);} else{print(round($margenAjustado8P/$row2['Agosto']*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado8R,2));?></td>
					<td><?php print(round($margenAjustadoPor8R*100,2));?>%</td>
					<td>$<?php print(number_format($margenAjustado8R-$margenAjustado8P,2));?></td>
					<td></td>
					<td>$<?php print(number_format($margenAjustado1P+$margenAjustado2P+$margenAjustado3P+$margenAjustado4P+$margenAjustado5P+$margenAjustado6P+$margenAjustado7P+$margenAjustado8P,2));?></td>
					<td><?php if($row2['Enero']==0||$row2['Febrero']==0||$row2['Marzo']==0||$row2['Abril']==0||$row2['Mayo']==0||$row2['Junio']==0||$row2['Julio']==0||$row2['Agosto']==0){print(0);} else{print(round(($margenAjustado1P+$margenAjustado2P+$margenAjustado3P+$margenAjustado4P+$margenAjustado5P+$margenAjustado6P+$margenAjustado7P+$margenAjustado8P)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto'])*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado1R+$margenAjustado2R+$margenAjustado3R+$margenAjustado4R+$margenAjustado5R+$margenAjustado6R+$margenAjustado7R+$margenAjustado8R,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0 || $row['JulioP'] == 0 || $row['AgostoP'] == 0){print(0);}else{print(round(($margenAjustado1R+$margenAjustado2R+$margenAjustado3R+$margenAjustado4R+$margenAjustado5R+$margenAjustado6R+$margenAjustado7R+$margenAjustado8R)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado8RA-$margenAjustado8PA,2));?></td>
					<td></td>
					<td>$<?php print(number_format($margenAjustado9P,2));?></td>
					<td><?php if($row2['Septiembre'] == 0){print(0);} else{print(round($margenAjustado9P/$row2['Septiembre']*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado9R,2));?></td>
					<td><?php print(round($margenAjustadoPor9R*100,2));?>%</td>
					<td>$<?php print(number_format($margenAjustado9R-$margenAjustado9P,2));?></td>
					<td></td>
					<td>$<?php print(number_format($margenAjustado1P+$margenAjustado2P+$margenAjustado3P+$margenAjustado4P+$margenAjustado5P+$margenAjustado6P+$margenAjustado7P+$margenAjustado8P+$margenAjustado9P,2));?></td>
					<td><?php if($row2['Enero']==0||$row2['Febrero']==0||$row2['Marzo']==0||$row2['Abril']==0||$row2['Mayo']==0||$row2['Junio']==0||$row2['Julio']==0||$row2['Agosto']==0||$row2['Septiembre']==0){print(0);} else{print(round(($margenAjustado1P+$margenAjustado2P+$margenAjustado3P+$margenAjustado4P+$margenAjustado5P+$margenAjustado6P+$margenAjustado7P+$margenAjustado8P+$margenAjustado9P)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre'])*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado1R+$margenAjustado2R+$margenAjustado3R+$margenAjustado4R+$margenAjustado5R+$margenAjustado6R+$margenAjustado7R+$margenAjustado8R+$margenAjustado9R,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0 || $row['JulioP'] == 0 || $row['AgostoP'] == 0 || $row['SeptiembreP'] == 0){print(0);}else{print(round(($margenAjustado1R+$margenAjustado2R+$margenAjustado3R+$margenAjustado4R+$margenAjustado5R+$margenAjustado6R+$margenAjustado7R+$margenAjustado8R+$margenAjustado9R)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado9RA-$margenAjustado9PA,2));?></td>
					<td></td>
					<td>$<?php print(number_format($margenAjustado10P,2));?></td>
					<td><?php if($row2['Octubre'] == 0){print(0);} else{print(round($margenAjustado10P/$row2['Octubre']*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado10R,2));?></td>
					<td><?php print(round($margenAjustadoPor10R*100,2));?>%</td>
					<td>$<?php print(number_format($margenAjustado10R-$margenAjustado10P,2));?></td>
					<td></td>
					<td>$<?php print(number_format($margenAjustado1P+$margenAjustado2P+$margenAjustado3P+$margenAjustado4P+$margenAjustado5P+$margenAjustado6P+$margenAjustado7P+$margenAjustado8P+$margenAjustado9P+$margenAjustado10P,2));?></td>
					<td><?php if($row2['Enero']==0||$row2['Febrero']==0||$row2['Marzo']==0||$row2['Abril']==0||$row2['Mayo']==0||$row2['Junio']==0||$row2['Julio']==0||$row2['Agosto']==0||$row2['Septiembre']==0||$row2['Octubre']==0){print(0);} else{print(round(($margenAjustado1P+$margenAjustado2P+$margenAjustado3P+$margenAjustado4P+$margenAjustado5P+$margenAjustado6P+$margenAjustado7P+$margenAjustado8P+$margenAjustado9P+$margenAjustado10P)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre']+$row2['Octubre'])*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado1R+$margenAjustado2R+$margenAjustado3R+$margenAjustado4R+$margenAjustado5R+$margenAjustado6R+$margenAjustado7R+$margenAjustado8R+$margenAjustado9R+$margenAjustado10R,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0 || $row['JulioP'] == 0 || $row['AgostoP'] == 0 || $row['SeptiembreP'] == 0 || $row['OctubreP'] == 0){print(0);}else{print(round(($margenAjustado1R+$margenAjustado2R+$margenAjustado3R+$margenAjustado4R+$margenAjustado5R+$margenAjustado6R+$margenAjustado7R+$margenAjustado8R+$margenAjustado9R+$margenAjustado10R)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado10RA-$margenAjustado10PA,2));?></td>
					<td></td>
					<td>$<?php print(number_format($margenAjustado11P,2));?></td>
					<td><?php if($row2['Noviembre'] == 0){print(0);} else{print(round($margenAjustado11P/$row2['Noviembre']*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado11R,2));?></td>
					<td><?php print(round($margenAjustadoPor11R*100,2));?>%</td>
					<td>$<?php print(number_format($margenAjustado11R-$margenAjustado11P,2));?></td>
					<td></td>
					<td>$<?php print(number_format($margenAjustado1P+$margenAjustado2P+$margenAjustado3P+$margenAjustado4P+$margenAjustado5P+$margenAjustado6P+$margenAjustado7P+$margenAjustado8P+$margenAjustado9P+$margenAjustado10P+$margenAjustado11P,2));?></td>
					<td><?php if($row2['Enero']==0||$row2['Febrero']==0||$row2['Marzo']==0||$row2['Abril']==0||$row2['Mayo']==0||$row2['Junio']==0||$row2['Julio']==0||$row2['Agosto']==0||$row2['Septiembre']==0||$row2['Octubre']==0||$row2['Noviembre']==0){print(0);} else{print(round(($margenAjustado1P+$margenAjustado2P+$margenAjustado3P+$margenAjustado4P+$margenAjustado5P+$margenAjustado6P+$margenAjustado7P+$margenAjustado8P+$margenAjustado9P+$margenAjustado10P+$margenAjustado10P)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre']+$row2['Octubre']+$row2['Noviembre'])*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado1R+$margenAjustado2R+$margenAjustado3R+$margenAjustado4R+$margenAjustado5R+$margenAjustado6R+$margenAjustado7R+$margenAjustado8R+$margenAjustado9R+$margenAjustado10R+$margenAjustado11R,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0 || $row['JulioP'] == 0 || $row['AgostoP'] == 0 || $row['SeptiembreP'] == 0 || $row['OctubreP'] == 0 || $row['NoviembreP'] == 0){print(0);}else{print(round(($margenAjustado1R+$margenAjustado2R+$margenAjustado3R+$margenAjustado4R+$margenAjustado5R+$margenAjustado6R+$margenAjustado7R+$margenAjustado8R+$margenAjustado9R+$margenAjustado10R+$margenAjustado11R)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP']+$row['NoviembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado11RA-$margenAjustado11PA,2));?></td>
					<td></td>
					<td>$<?php print(number_format($margenAjustado12P,2));?></td>
					<td><?php if($row2['Diciembre'] == 0){print(0);} else{print(round($margenAjustado12P/$row2['Diciembre']*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado12R,2));?></td>
					<td><?php print(round($margenAjustadoPor12R*100,2));?>%</td>
					<td>$<?php print(number_format($margenAjustado12R-$margenAjustado12P,2));?></td>
				</tr>
			<?php
			
		}
		else
		{
			?>
            <tr>
				<td colspan="136"></td>
            </tr>
            <?php
		}
	}
	public function estacionariedadAVEFOMensual($zona,$year,$mes_numero)
	{
		
		$query = "SELECT 
					SUM(DB.precio_bitacora) AS MesP,
					SUM(DB.costo_bitacora) AS MesC,
					SUM(DB.gasto_directo) AS MesG
				FROM T_Clientes AS C
				LEFT OUTER JOIN T_Bitacora AS B
				ON B.id_cliente = C.id_cliente
				LEFT OUTER JOIN T_Detalle_Bitacora AS DB
				ON DB.id_bitacora = B.id_bitacora
				LEFT OUTER JOIN T_Subproductos AS S
				ON S.id_subproducto = DB.id_subproducto
				WHERE C.id_zona = :zona AND YEAR(B.fecha_audit) = :year AND DB.id_mesejecucion = :mes_numero AND DB.id_estatusejecucion = 1";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(":zona",$zona);
		$stmt->bindparam(":year",$year);
		$stmt->bindparam(":mes_numero",$mes_numero);
		$stmt->execute();
		
		$query = "SELECT 
				SUM(anticipo),
				SUM(mes".$mes_numero."_Ej) AS Mes,
				SUM(mes".$mes_numero."_Ej*costo_ac) AS MesC
			FROM `T_Detalle_AnalisisCuantitativo_Escenario2` AS DAC
			INNER JOIN `T_AnalisisCuantitativo_Escenario2` AS AC
		    ON DAC.id_analisiscuantitativo = AC.id_analisiscuantitativo
			INNER JOIN T_Clientes AS C 
		    ON C.id_cliente = AC.id_cliente 
		    WHERE C.id_zona = :zona AND AC.year_acuant = :year";
		$stmt2 = $this->conn->prepare($query);
		$stmt2->bindparam(":zona",$zona);
		$stmt2->bindparam(":year",$year);
		$stmt2->execute();
		
		$query ="SELECT 
				  N.year_nomina,
				  (SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.intereses)) AS TotalGAO
				  FROM T_Nomina_GAO_Escenario2 AS N
				  INNER JOIN T_Plantilla AS P
				  ON N.id_plantilla = P.id_plantilla
				  INNER JOIN T_PlantillaDatos as PD
				  ON PD.id_plantilladatos = P.id_plantilladatos
				  WHERE PD.id_zona=:zona AND year_nomina = :year";
		$stmt3 = $this->conn->prepare($query);
		$stmt3->bindparam(":zona",$zona);
		$stmt3->bindparam(":year",$year);
		$stmt3->execute();
		
		$query ="SELECT 
				  (SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.intereses)+SUM(otros)) AS TotalGAO
				  FROM T_Nomina_GAO_Real AS N
				  INNER JOIN T_Plantilla AS P
				  ON N.id_plantilla = P.id_plantilla
				  INNER JOIN T_PlantillaDatos as PD
				  ON PD.id_plantilladatos = P.id_plantilladatos
				  WHERE PD.id_zona=:zona AND N.year_nomina = :year AND N.id_mes = :mes_numero";
		$stmt4 = $this->conn->prepare($query);
		$stmt4->bindparam(":zona",$zona);
		$stmt4->bindparam(":year",$year);
		$stmt4->bindparam(":mes_numero",$mes_numero);
		$stmt4->execute();
		
		$query ="SELECT 
					anticipo
				  FROM T_AnticiposLider
				  WHERE id_zona=:zona AND year_anticipo = :year";
		$stmt5 = $this->conn->prepare($query);
		$stmt5->bindparam(":zona",$zona);
		$stmt5->bindparam(":year",$year);
		$stmt5->execute();
		
		if($stmt2->rowCount()>0)
		{
			$row=$stmt->fetch(PDO::FETCH_ASSOC);
			$row2=$stmt2->fetch(PDO::FETCH_ASSOC);
			$row3=$stmt3->fetch(PDO::FETCH_ASSOC);
			$row4=$stmt4->fetch(PDO::FETCH_ASSOC);
			$row5=$stmt5->fetch(PDO::FETCH_ASSOC);
			
			//INGRESOS
			$diferencia = $row2['Mes']-$row['MesP'];
			
			//COSTOS
			$diferenciaC = $row2['MesC']-$row['MesC'];
			
			//GAOFIJO
			$gaoMensual = $row3['TotalGAO']/12;
			
			if($zona == 7 || $zona == 3)
			{
				$comision = 0.43;
			}
			else
			{
				$comision = 0.38;
			}
			
			//COMISION PROYECTADA
			$diferenciaComision = $row2['Mes']*($comision-(($row2['MesC']/$row2['Mes'])+($gaoMensual/$row2['Mes'])));
			
			//COMISION REAL
			$comisionReal = 1; //$row['MesP']*($comision-((($row['MesC']+$row['MesG'])/$row['MesP'])+($row4['TotalGAO']/$row['MesP'])));
			
			//MARGEN AJUSTADO
			//PROYECTADO
			$margenAjustadoP = $row2['Mes'] - $row2['MesC'] - $gaoMensual - $diferenciaComision;
			//REAL
			$margenAjustadoR = $row['MesP'] - $row['MesC'] - $row['MesG'] - $row4['TotalGAO'] - ($comisionReal - $row5['anticipo']);
			//DIFERENCIA
			$margenAjustadoD = $margenAjustadoP - $margenAjustadoR;
			if($row['MesP'] == 0)
			{
				$row['MesP'] = 1;
			}
			
			?>
				<!--REAL-->
				<tr>
					<td colspan="3">REAL</td>
				</tr>
				<tr>
					<th>Ingresos</th>
					<td>$<?php print(number_format($row['MesP'],2));?></td>
					<td>100%</td>
				</tr>
				<tr>
					<th>Costos</th>
					<td>$<?php print(number_format($row['MesC'],2));?></td>
					<td><?php print(round($row['MesC']/$row['MesP']*100,2));?>%</td>
				</tr>
			<?php
				if($zona == 7)
				{
			?>	
				<tr>
					<th>Gasto Directo</th>
					<td>$<?php print(number_format($row['MesG'],2));?></td>
					<td><?php print(round($row['MesG']/$row['MesP']*100,2));?>%</td>
				</tr>
			<?php
				}
			?>
				<tr>
					<th>Margen Directo</th>
					<td>$<?php print(number_format($row['MesP']-$row['MesC']-$row['MesG'],2));?></td>
					<td><?php print(round(($row['MesP']-$row['MesC']-$row['MesG'])/$row['MesP']*100,2));?>%</td>
				</tr>
				<tr>
					<th>GAO Fijo</th>
					<td>$<?php print(number_format($row4['TotalGAO'],2));?></td>
					<td><?php print(round($row4['TotalGAO']/$row['MesP']*100,2));?>%</td>
				</tr>
				<tr>
					<th>Oferta</th>
					<td></td>
			<?php
				if($zona == 3 || $zona == 7)
				{
			?>
					<td>43%</td>
			<?php
				}
				else
				{
			?>
					<td>38%</td>
			<?php
				}
			?>
				</tr>
				<tr>
					<th>Comisión</th>
					<td>$<?php print(number_format($comisionReal,2));?></td>
					<td><?php print(round($comisionReal/$row['MesP']*100,2));?>%</td>
				</tr>
				<tr>
					<th>Anticipos</th>
					<td>$<?php print(number_format($row5['anticipo'],2));?></td>
					<td><?php print(round($row5['anticipo']/$row['MesP']*100,2));?>%</td>
				</tr>
				<tr>
					<th>Saldo</th>
					<td>$<?php print(number_format($comisionReal-$row5['anticipo'],2));?></td>
					<td><?php print(round(($comisionReal-$row5['anticipo'])/$row['MesP']*100,2));?>%</td>
				</tr>
				<tr>
					<th>Margen Ajustado</th>
					<td>$<?php print(number_format($margenAjustadoR,2));?></td>
					<td><?php print(round($margenAjustadoR/$row['MesP']*100,2));?>%</td>
				</tr>
				
				<tr>
					<td colspan="3">PROYECTADO</td>
				</tr>
				<tr>
					<th>Ingresos</th>
					<td>$<?php print(number_format($row2['Mes'],2));?></td>
					<td>100%</td>
				</tr>
				<tr>
					<th>Costos</th>
					<td>$<?php print(number_format($row2['MesC'],2));?></td>
					<td><?php print(round($row2['MesC']/$row2['Mes']*100,2));?>%</td>
				</tr>
				<tr>
					<th>Margen Directo</th>
					<td>$<?php print(number_format($row2['Mes']-$row2['MesC'],2));?></td>
					<td><?php print(round(($row2['Mes']-$row2['MesC'])/$row2['Mes']*100,2));?>%</td>
				</tr>
				<tr>
					<th>GAO Fijo Total</th>
					<td>$<?php print(number_format($gaoMensual,2));?></td>
					<td><?php print(round($gaoMensual/$row2['Mes']*100,2));?>%</td>
				</tr>
				<tr>
					<th>GAO Variable</th>
					<td>$<?php print(number_format($diferenciaComision,2));?></td>
					<td><?php print(round($diferenciaComision/$row2['Mes']*100,2));?>%</td>
				</tr>
				<tr>
					<th>Margen Ajustado</th>
					<td>$<?php print(number_format($margenAjustadoP,2));?></td>
					<td><?php print(round($margenAjustadoP/$row2['Mes']*100,2));?>%</td>
				</tr>
			<?php
		}
		else
		{
			?>
            <tr>
				<td colspan="4"></td>
            </tr>
            <?php
		}
	}
	public function estacionariedadAVEFOTrimestre($zona,$year,$trimestre)
	{
		if($trimestre == 1)
		{
			$query = "SELECT 
					IF(DB.id_mesejecucion=1,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora),0),0) AS Mes1P,
					IF(DB.id_mesejecucion=2,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora),0),0) AS Mes2P,
					IF(DB.id_mesejecucion=3,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora),0),0) AS Mes3P,
					IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora),0) AS TOTALP,
					IF(DB.id_mesejecucion=1,IF(DB.id_estatusejecucion=1,SUM(DB.costo_bitacora),0),0) AS Mes1,
					IF(DB.id_mesejecucion=2,IF(DB.id_estatusejecucion=1,SUM(DB.costo_bitacora),0),0) AS Mes2C,
					IF(DB.id_mesejecucion=3,IF(DB.id_estatusejecucion=1,SUM(DB.costo_bitacora),0),0) AS Mes3C,
					IF(DB.id_estatusejecucion=1,SUM(DB.costo_bitacora),0) AS TOTALC,
					IF(DB.id_mesejecucion=1,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora*DB.gasto_directo),0),0) AS Mes1G,
					IF(DB.id_mesejecucion=2,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora*DB.gasto_directo),0),0) AS Mes2G,
					IF(DB.id_mesejecucion=3,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora*DB.gasto_directo),0),0) AS Mes3G,
					IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora*DB.gasto_directo),0) AS TOTALG
				FROM T_Clientes AS C
				LEFT OUTER JOIN T_Bitacora AS B
				ON B.id_cliente = C.id_cliente
				LEFT OUTER JOIN T_Detalle_Bitacora AS DB
				ON DB.id_bitacora = B.id_bitacora
				LEFT OUTER JOIN T_Subproductos AS S
				ON S.id_subproducto = DB.id_subproducto
				WHERE C.id_zona = :zona AND YEAR(B.fecha_audit) = :year";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":zona",$zona);
			$stmt->bindparam(":year",$year);
			$stmt->execute();
			
			$query = "SELECT 
				SUM(anticipo),
				SUM(mes1_Ej) AS Mes1,
				SUM(mes2_Ej) AS Mes2,
				SUM(mes3_Ej) AS Mes3,
				SUM(mes1_Ej+mes2_Ej+mes3_Ej+mes4_Ej+mes5_Ej+mes6_Ej+mes7_Ej+mes8_Ej+mes9_Ej+mes10_Ej+mes11_Ej+mes12_Ej) AS TOTAL,
				SUM(mes1_Ej*costo_ac) AS Mes1C,
				SUM(mes2_Ej*costo_ac) AS Mes2C,
				SUM(mes3_Ej*costo_ac) AS Mes3C,
				SUM(cuotadef_ac*costo_ac) AS TOTALC
			FROM `T_Detalle_AnalisisCuantitativo_Escenario2` AS DAC
			INNER JOIN `T_AnalisisCuantitativo_Escenario2` AS AC
		    ON DAC.id_analisiscuantitativo = AC.id_analisiscuantitativo
			INNER JOIN T_Clientes AS C 
		    ON C.id_cliente = AC.id_cliente 
		    WHERE C.id_zona = :zona AND AC.year_acuant = :year";
			$stmt2 = $this->conn->prepare($query);
			$stmt2->bindparam(":zona",$zona);
			$stmt2->bindparam(":year",$year);
			$stmt2->execute();
		}
		else if($trimestre == 2)
		{
			$query = "SELECT 
					IF(DB.id_mesejecucion=4,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora),0),0) AS Mes1P,
					IF(DB.id_mesejecucion=5,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora),0),0) AS Mes2P,
					IF(DB.id_mesejecucion=6,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora),0),0) AS Mes3P,
					IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora),0) AS TOTALP,
					IF(DB.id_mesejecucion=4,IF(DB.id_estatusejecucion=1,SUM(DB.costo_bitacora),0),0) AS Mes1C,
					IF(DB.id_mesejecucion=5,IF(DB.id_estatusejecucion=1,SUM(DB.costo_bitacora),0),0) AS Mes2C,
					IF(DB.id_mesejecucion=6,IF(DB.id_estatusejecucion=1,SUM(DB.costo_bitacora),0),0) AS Mes3C,
					IF(DB.id_estatusejecucion=1,SUM(DB.costo_bitacora),0) AS TOTALC,
					IF(DB.id_mesejecucion=1,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora*DB.gasto_directo),0),0) AS Mes1G,
					IF(DB.id_mesejecucion=2,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora*DB.gasto_directo),0),0) AS Mes2G,
					IF(DB.id_mesejecucion=3,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora*DB.gasto_directo),0),0) AS Mes3G,
					IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora*DB.gasto_directo),0) AS TOTALG
				FROM T_Clientes AS C
				LEFT OUTER JOIN T_Bitacora AS B
				ON B.id_cliente = C.id_cliente
				LEFT OUTER JOIN T_Detalle_Bitacora AS DB
				ON DB.id_bitacora = B.id_bitacora
				LEFT OUTER JOIN T_Subproductos AS S
				ON S.id_subproducto = DB.id_subproducto
				WHERE C.id_zona = :zona AND YEAR(B.fecha_audit) = :year";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":zona",$zona);
			$stmt->bindparam(":year",$year);
			$stmt->execute();
			
			$query = "SELECT 
				SUM(anticipo),
				SUM(mes4_Ej) AS Mes1,
				SUM(mes5_Ej) AS Mes2,
				SUM(mes6_Ej) AS Mes3,
				SUM(mes1_Ej+mes2_Ej+mes3_Ej+mes4_Ej+mes5_Ej+mes6_Ej+mes7_Ej+mes8_Ej+mes9_Ej+mes10_Ej+mes11_Ej+mes12_Ej) AS TOTAL,
				SUM(mes4_Ej*costo_ac) AS Mes1C,
				SUM(mes5_Ej*costo_ac) AS Mes2C,
				SUM(mes6_Ej*costo_ac) AS Mes3C,
				SUM(cuotadef_ac*costo_ac) AS TOTALC
			FROM `T_Detalle_AnalisisCuantitativo_Escenario2` AS DAC
			INNER JOIN `T_AnalisisCuantitativo_Escenario2` AS AC
		    ON DAC.id_analisiscuantitativo = AC.id_analisiscuantitativo
			INNER JOIN T_Clientes AS C 
		    ON C.id_cliente = AC.id_cliente 
		    WHERE C.id_zona = :zona AND AC.year_acuant = :year";
			$stmt2 = $this->conn->prepare($query);
			$stmt2->bindparam(":zona",$zona);
			$stmt2->bindparam(":year",$year);
			$stmt2->execute();
		}
		else if($trimestre == 3)
		{
			$query = "SELECT 
					IF(DB.id_mesejecucion=7,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora),0),0) AS Mes1P,
					IF(DB.id_mesejecucion=8,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora),0),0) AS Mes2P,
					IF(DB.id_mesejecucion=9,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora),0),0) AS Mes3P,
					IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora),0) AS TOTALP,
					IF(DB.id_mesejecucion=7,IF(DB.id_estatusejecucion=1,SUM(DB.costo_bitacora),0),0) AS Mes1C,
					IF(DB.id_mesejecucion=8,IF(DB.id_estatusejecucion=1,SUM(DB.costo_bitacora),0),0) AS Mes2C,
					IF(DB.id_mesejecucion=9,IF(DB.id_estatusejecucion=1,SUM(DB.costo_bitacora),0),0) AS Mes3C,
					IF(DB.id_estatusejecucion=1,SUM(DB.costo_bitacora),0) AS TOTALC,
					IF(DB.id_mesejecucion=7,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora*DB.gasto_directo),0),0) AS Mes1G,
					IF(DB.id_mesejecucion=8,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora*DB.gasto_directo),0),0) AS Mes2G,
					IF(DB.id_mesejecucion=9,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora*DB.gasto_directo),0),0) AS Mes3G,
					IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora*DB.gasto_directo),0) AS TOTALG
				FROM T_Clientes AS C
				LEFT OUTER JOIN T_Bitacora AS B
				ON B.id_cliente = C.id_cliente
				LEFT OUTER JOIN T_Detalle_Bitacora AS DB
				ON DB.id_bitacora = B.id_bitacora
				LEFT OUTER JOIN T_Subproductos AS S
				ON S.id_subproducto = DB.id_subproducto
				WHERE C.id_zona = :zona AND YEAR(B.fecha_audit) = :year";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":zona",$zona);
			$stmt->bindparam(":year",$year);
			$stmt->execute();
		
			$query = "SELECT 
				SUM(anticipo),
				SUM(mes7_Ej) AS Mes1,
				SUM(mes8_Ej) AS Mes2,
				SUM(mes9_Ej) AS Mes3,
				SUM(mes1_Ej+mes2_Ej+mes3_Ej+mes4_Ej+mes5_Ej+mes6_Ej+mes7_Ej+mes8_Ej+mes9_Ej+mes10_Ej+mes11_Ej+mes12_Ej) AS TOTAL,
				SUM(mes7_Ej*costo_ac) AS Mes1C,
				SUM(mes8_Ej*costo_ac) AS Mes2C,
				SUM(mes9_Ej*costo_ac) AS Mes3C,
				SUM(cuotadef_ac*costo_ac) AS TOTALC
			FROM `T_Detalle_AnalisisCuantitativo_Escenario2` AS DAC
			INNER JOIN `T_AnalisisCuantitativo_Escenario2` AS AC
		    ON DAC.id_analisiscuantitativo = AC.id_analisiscuantitativo
			INNER JOIN T_Clientes AS C 
		    ON C.id_cliente = AC.id_cliente 
		    WHERE C.id_zona = :zona AND AC.year_acuant = :year";
			$stmt2 = $this->conn->prepare($query);
			$stmt2->bindparam(":zona",$zona);
			$stmt2->bindparam(":year",$year);
			$stmt2->execute();
		}
		else
		{
			$query = "SELECT 
					IF(DB.id_mesejecucion=10,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora),0),0) AS Mes1P,
					IF(DB.id_mesejecucion=11,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora),0),0) AS Mes2P,
					IF(DB.id_mesejecucion=12,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora),0),0) AS Mes3P,
					IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora),0) AS TOTALP,
					IF(DB.id_mesejecucion=10,IF(DB.id_estatusejecucion=1,SUM(DB.costo_bitacora),0),0) AS Mes1C,
					IF(DB.id_mesejecucion=11,IF(DB.id_estatusejecucion=1,SUM(DB.costo_bitacora),0),0) AS Mes2C,
					IF(DB.id_mesejecucion=12,IF(DB.id_estatusejecucion=1,SUM(DB.costo_bitacora),0),0) AS Mes3C,
					IF(DB.id_estatusejecucion=1,SUM(DB.costo_bitacora),0) AS TOTALC,
					IF(DB.id_mesejecucion=10,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora*DB.gasto_directo),0),0) AS Mes1G,
					IF(DB.id_mesejecucion=11,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora*DB.gasto_directo),0),0) AS Mes2G,
					IF(DB.id_mesejecucion=12,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora*DB.gasto_directo),0),0) AS Mes3G,
					IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora*DB.gasto_directo),0) AS TOTALG
				FROM T_Clientes AS C
				LEFT OUTER JOIN T_Bitacora AS B
				ON B.id_cliente = C.id_cliente
				LEFT OUTER JOIN T_Detalle_Bitacora AS DB
				ON DB.id_bitacora = B.id_bitacora
				LEFT OUTER JOIN T_Subproductos AS S
				ON S.id_subproducto = DB.id_subproducto
				WHERE C.id_zona = :zona AND YEAR(B.fecha_audit) = :year";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":zona",$zona);
			$stmt->bindparam(":year",$year);
			$stmt->execute();
		
			$query = "SELECT 
				SUM(anticipo),
				SUM(mes10_Ej) AS Mes1,
				SUM(mes11_Ej) AS Mes2,
				SUM(mes12_Ej) AS Mes3,
				SUM(mes1_Ej+mes2_Ej+mes3_Ej+mes4_Ej+mes5_Ej+mes6_Ej+mes7_Ej+mes8_Ej+mes9_Ej+mes10_Ej+mes11_Ej+mes12_Ej) AS TOTAL,
				SUM(mes10_Ej*costo_ac) AS Mes1C,
				SUM(mes11_Ej*costo_ac) AS Mes2C,
				SUM(mes12_Ej*costo_ac) AS Mes3C,
				SUM(cuotadef_ac*costo_ac) AS TOTALC
			FROM `T_Detalle_AnalisisCuantitativo_Escenario2` AS DAC
			INNER JOIN `T_AnalisisCuantitativo_Escenario2` AS AC
		    ON DAC.id_analisiscuantitativo = AC.id_analisiscuantitativo
			INNER JOIN T_Clientes AS C 
		    ON C.id_cliente = AC.id_cliente 
		    WHERE C.id_zona = :zona AND AC.year_acuant = :year";
			$stmt2 = $this->conn->prepare($query);
			$stmt2->bindparam(":zona",$zona);
			$stmt2->bindparam(":year",$year);
			$stmt2->execute();
		}
		
		$query ="SELECT 
				  N.year_nomina,
				  (SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.intereses)) AS TotalGAO
				  FROM T_Nomina_GAO_Escenario2 AS N
				  INNER JOIN T_Plantilla AS P
				  ON N.id_plantilla = P.id_plantilla
				  INNER JOIN T_PlantillaDatos as PD
				  ON PD.id_plantilladatos = P.id_plantilladatos
				  WHERE PD.id_zona=:zona AND year_nomina = :year";
		$stmt3 = $this->conn->prepare($query);
		$stmt3->bindparam(":zona",$zona);
		$stmt3->bindparam(":year",$year);
		$stmt3->execute();
		
		if($stmt2->rowCount()>0)
		{
			$row=$stmt->fetch(PDO::FETCH_ASSOC);
			$row2=$stmt2->fetch(PDO::FETCH_ASSOC);
			$row3=$stmt3->fetch(PDO::FETCH_ASSOC);
			
			//INGRESOS
			$diferencia1 = $row2['Mes1']-$row['Mes1P'];
			$diferencia2 = $row2['Mes2']-$row['Mes2P'];
			$diferencia3 = $row2['Mes3']-$row['Mes3P'];
			$mes1 = $diferencia1;
			$mes2 = ($mes1+$row2['Mes2'])-$row['Mes2P'];
			$mes3 = ($mes2+$row2['Mes3'])-$row['Mes3P'];
			
			//COSTOS
			$diferencia1C = $row2['Mes1C']-$row['Mes1C'];
			$diferencia2C = $row2['Mes2C']-$row['Mes2C'];
			$diferencia3C = $row2['Mes3C']-$row['Mes3C'];
			$mes1C = $diferencia1C;
			$mes2C = ($mes1C+$row2['Mes2C'])-$row['Mes3C'];
			$mes3C = ($mes2C+$row2['Mes2C'])-$row['Mes3C'];
			
			//GAOFIJO
			$gaoMensual = $row3['TotalGAO']/12;
			
			//COMISION PROYECTADA
			if($zona == 7 || $zona == 3)
			{
				$comision = 0.43;
			}
			else
			{
				$comision = 0.38;
			}
			
			//COMISION PROYECTADA
			$diferenciaComision1 = $row2['Mes1']*($comision-(($row2['Mes1C']/$row2['Mes1'])+($gaoMensual/$row2['Mes1'])));
			$diferenciaComision2 = $row2['Mes2']*($comision-(($row2['Mes2C']/$row2['Mes2'])+($gaoMensual/$row2['Mes2'])));
			$diferenciaComision3 = $row2['Mes3']*($comision-(($row2['Mes3C']/$row2['Mes3'])+($gaoMensual/$row2['Mes3'])));
			$comision1 = $diferenciaComision1;
			$comision2 = $diferenciaComision2+$comision1;
			$comision3 = $diferenciaComision3+$comision2;
			//COMISION REAL
			
			
			//MARGEN AJUSTADO
			//PROYECTADO
			$margenAjustado1P = $row2['Mes1'] - $row2['Mes1C'] - $gaoMensual - $diferenciaComision1;
			$margenAjustado2P = $row2['Mes2'] - $row2['Mes2C'] - $gaoMensual - $diferenciaComision2;
			$margenAjustado3P = $row2['Mes3'] - $row2['Mes3C'] - $gaoMensual - $diferenciaComision3;
			//REAL
			$margenAjustado1R = $row['Mes1P'] - $row['Mes1C'] - $row['Mes1G'];
			$margenAjustado2R = $row['Mes2P'] - $row['Mes2C'] - $row['Mes2G'];
			$margenAjustado3R = $row['Mes3P'] - $row['Mes3C'] - $row['Mes3G'];
			//DIFERENCIA
			$margenAjustado1D = $margenAjustado1P - $margenAjustado1R;
			$margenAjustado2D = $margenAjustado2P - $margenAjustado2R;
			$margenAjustado3D = $margenAjustado3P - $margenAjustado3R;
			//AJUSTE - REAL
			$margenAjustado1A = $margenAjustado1P - $margenAjustado1R;
			$margenAjustado2A = ($margenAjustado2P + $margenAjustado1A) - $margenAjustado2R;
			$margenAjustado3A = ($margenAjustado3P + $margenAjustado2A) - $margenAjustado3R;
			//AJUSTE - REAL
			$margenAjustado1B = $margenAjustado1P - $margenAjustado1R;
			$margenAjustado2B = ($margenAjustado2P + $margenAjustado1A) - $margenAjustado2R;
			$margenAjustado3B = ($margenAjustado3P + $margenAjustado2A) - $margenAjustado3R;
			
			?>
				<!--INGRESOS-->
				<tr>
					<th rowspan="4">Ingresos</th>
					<th>Proyectado</th>
					<td>$<?php print(number_format($row2['Mes1'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($row2['Mes2'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($row2['Mes3'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($row2['Mes1']+$row2['Mes2']+$row2['Mes3'],2));?></td>
					<td>100%</td>
				</tr>
				<tr>
					<th>Ajuste</th>
					<td>$0.00</td>
					<td>10%</td>
					<td>$<?php print(number_format($mes1,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($mes2,2));?></td>
					<td>10%</td>
					<td>$0.00</td>
					<td>100%</td>
				</tr>
				<tr>
					<th>Real</th>
					<td>$<?php print(number_format($row['Mes1P'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($row['Mes2P'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($row['Mes3P'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($row['Mes1P']+$row['Mes2P']+$row['Mes3P'],2));?></td>
					<td>100%</td>
				</tr>
				<tr>
					<th>Diferencia</th>
					<td>$<?php print(number_format($mes1,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($mes2,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($mes3,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format(($row2['Mes1']+$row2['Mes2']+$row2['Mes3'])-($row['Mes1P']+$row['Mes2P']+$row['Mes3P']),2));?></td>
					<td>100%</td>
				</tr>
				<tr>
					<td colspan="9"></td>
				</tr>
				<!--COSTOS-->
				<tr>
					<th rowspan="4">Costos</th>
					<th>Proyectado</th>
					<td>$<?php print(number_format($row2['Mes1C'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($row2['Mes2C'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($row2['Mes3C'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($row2['Mes1C']+$row2['Mes2C']+$row2['Mes3C'],2));?></td>
					<td>100%</td>
				</tr>
				<tr>
					<th>Ajuste</th>
					<td>$0.00</td>
					<td>10%</td>
					<td>$<?php print(number_format($mes1C,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($mes2C,2));?></td>
					<td>10%</td>
					<td>$0.00</td>
					<td>100%</td>
				</tr>
				<tr>
					<th>Real</th>
					<td>$<?php print(number_format($row['Mes1C'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($row['Mes2C'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($row['Mes3C'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($row['Mes1C']+$row['Mes2C']+$row['Mes3C'],2));?></td>
					<td>100%</td>
				</tr>
				<tr>
					<th>Diferencia</th>
					<td>$<?php print(number_format($mes1C,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($mes2C,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($mes3C,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format(($row2['Mes1C']+$row2['Mes2C']+$row2['Mes3C'])-($row['Mes1C']+$row['Mes2C']+$row['Mes3C']),2));?></td>
					<td>100%</td>
				</tr>
				<tr>
					<td colspan="9"></td>
				</tr>
			<?php
				if($zona == 7)
				{
			?>
				<!--GASTO DIRECTO-->
				<tr>
					<th rowspan="4">Gasto Directo</th>
					<th>Proyectado</th>
					<td>$<?php print(number_format(/*$row2['EneroC']*/0,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format(/*$row2['FebreroC']*/0,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format(/*$row2['MarzoC']*/0,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format(/*$row2['TOTALC']*/0,2));?></td>
					<td>100%</td>
				</tr>
				<tr>
					<th>Ajuste</th>
					<td>$0.00</td>
					<td>10%</td>
					<td>$<?php print(number_format(/*$eneroC*/0,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format(/*$febreroC*/0,2));?></td>
					<td>10%</td>
					<td>$0.00</td>
					<td>100%</td>
				</tr>
				<tr>
					<th>Real</th>
					<td>$<?php print(number_format($row['Mes1G'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($row['Mes2G'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($row['Mes3G'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($row['Mes1G']+$row['Mes2G']+$row['Mes3G'],2));?></td>
					<td>100%</td>
				</tr>
				<tr>
					<th>Diferencia</th>
					<td>$<?php print(number_format($row['Mes1G'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($row['Mes2G'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($row['Mes3G'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($row['Mes1G']+$row['Mes2G']+$row['Mes3G'],2));?></td>
					<td>100%</td>
				</tr>
				<tr>
					<td colspan="9"></td>
				</tr>
			<?php
				}
			?>
				<!--MARGEN DIRECTO-->
				<tr>
					<th rowspan="4">Margen Directo</th>
					<th>Proyectado</th>
					<td>$<?php print(number_format($row2['Mes1']-$row2['Mes1C'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($row2['Mes2']-$row2['Mes2C'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($row2['Mes3']-$row2['Mes3C'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format(($row2['Mes1']-$row2['Mes1C'])-($row2['Mes2']-$row2['Mes2C'])-($row2['Mes3']-$row2['Mes3C']),2));?></td>
					<td>100%</td>
				</tr>
				<tr>
					<th>Ajuste</th>
					<td>$0.00</td>
					<td>10%</td>
					<td>$<?php print(number_format(($mes1)-($mes1C),2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format(($mes2)-($mes2C),2));?></td>
					<td>10%</td>
					<td>$0.00</td>
					<td>100%</td>
				</tr>
				<tr>
					<th>Real</th>
					<td>$<?php print(number_format($row['Mes1P']-$row['Mes1C']-$row['Mes1G'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($row['Mes2P']-$row['Mes2C']-$row['Mes2G'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($row['Mes3P']-$row['Mes3C']-$row['Mes3G'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format(($row['Mes1P']-$row['Mes1C']-$row['Mes1G'])-($row['Mes2P']-$row['Mes2C']-$row['Mes2G'])-($row['Mes3P']-$row['Mes3C']-$row['Mes3G']),2));?></td>
					<td>100%</td>
				</tr>
				<tr>
					<th>Diferencia</th>
					<td>$<?php print(number_format($mes1-$mes1C,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($mes2-$mes2C,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($mes3-$mes3C,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format((($row2['Mes1']-$row2['Mes1C'])-($row2['Mes2']-$row2['Mes2C'])-($row2['Mes3']-$row2['Mes3C']))-(($row['Mes1P']-$row['Mes1C']-$row['Mes1G'])-($row['Mes2P']-$row['Mes2C']-$row['Mes2G'])-($row['Mes3P']-$row['Mes3C']-$row['Mes3G'])),2));?></td>
					<td>100%</td>
				</tr>
				<tr>
					<td colspan="9"></td>
				</tr>
				<!--GAO FIJO-->
				<tr>
					<th rowspan="4">GAO Fijo Total</th>
					<th>Proyectado</th>
					<td>$<?php print(number_format($gaoMensual,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($gaoMensual,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($gaoMensual,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format(($gaoMensual*3),2));?></td>
					<td>100%</td>
				</tr>
				<tr>
					<th>Ajuste</th>
					<td>$0.00</td>
					<td>10%</td>
					<td>$<?php print(number_format($gaoMensual,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($gaoMensual*2,2));?></td>
					<td>10%</td>
					<td>$0.00</td>
					<td>100%</td>
				</tr>
				<tr>
					<th>Real</th>
					<td>$<?php print(number_format(0,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format(0,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format(0,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format(0,2));?></td>
					<td>100%</td>
				</tr>
				<tr>
					<th>Diferencia</th>
					<td>$<?php print(number_format($gaoMensual,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format((($gaoMensual*2))-0,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format((($gaoMensual*3))-0,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format(($gaoMensual*3),2));?></td>
					<td>100%</td>
				</tr>
				<tr>
					<td colspan="9"></td>
				</tr>
				<!--GAO VARIABLE-->
				<tr>
					<th rowspan="4">GAO Variable</th>
					<th>Proyectado</th>
					<td>$<?php print(number_format($diferenciaComision1,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($diferenciaComision2,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($diferenciaComision3,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($diferenciaComision1+$diferenciaComision2+$diferenciaComision3,2));?></td>
					<td>100%</td>
				</tr>
				<tr>
					<th>Ajuste</th>
					<td>$0.00</td>
					<td>10%</td>
					<td>$<?php print(number_format($comision1,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($diferenciaComision2+$comision1,2));?></td>
					<td>10%</td>
					<td>$0.00</td>
					<td>100%</td>
				</tr>
				<tr>
					<th>Real</th>
					<td>$<?php print(number_format(0,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format(0,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format(0,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format(0,2));?></td>
					<td>100%</td>
				</tr>
				<tr>
					<th>Diferencia</th>
					<td>$<?php print(number_format($comision1,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($comision2,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($comision3,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($comision1+$comision2+$comision3,2));?></td>
					<td>100%</td>
				</tr>
				<tr>
					<td colspan="9"></td>
				</tr>
				<!--MARGEN AJUSTADO-->
				<tr>
					<th rowspan="4">Margen Ajustado</th>
					<th>Proyectado</th>
					<td>$<?php print(number_format($margenAjustado1P,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($margenAjustado2P,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($margenAjustado3P,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($margenAjustado1P+$margenAjustado2P+$margenAjustado3P,2));?></td>
					<td>100%</td>
				</tr>
				<tr>
					<th>Ajuste</th>
					<td>$0.00</td>
					<td>10%</td>
					<td>$<?php print(number_format($margenAjustado1A,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($margenAjustado2A,2));?></td>
					<td>10%</td>
					<td>$0.00</td>
					<td>100%</td>
				</tr>
				<tr>
					<th>Real</th>
					<td>$<?php print(number_format($margenAjustado1R,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($margenAjustado2R,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($margenAjustado3R,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($margenAjustado1R+$margenAjustado2R+$margenAjustado3R,2));?></td>
					<td>100%</td>
				</tr>
				<tr>
					<th>Diferencia</th>
					<td>$<?php print(number_format($margenAjustado1A,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($margenAjustado2A,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($margenAjustado3A,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($margenAjustado1A-$margenAjustado2A-$margenAjustado3A,2));?></td>
					<td>100%</td>
				</tr>
				<tr>
					<td colspan="9"></td>
				</tr>
			<?php
		}
		else
		{
			?>
            <tr>
				<td colspan="9"></td>
            </tr>
            <?php
		}
	}
	public function estacionariedadAVEFOAnualC($year)
	{
		//BITACORA
		$query = "SELECT 
					IF(DB.id_mesejecucion=1,SUM(DB.precio_bitacora),0) AS EneroP,
					IF(DB.id_mesejecucion=2,SUM(DB.precio_bitacora),0) AS FebreroP,
					IF(DB.id_mesejecucion=3,SUM(DB.precio_bitacora),0) AS MarzoP,
					IF(DB.id_mesejecucion=4,SUM(DB.precio_bitacora),0) AS AbrilP,
					IF(DB.id_mesejecucion=5,SUM(DB.precio_bitacora),0) AS MayoP,
					IF(DB.id_mesejecucion=6,SUM(DB.precio_bitacora),0) AS JunioP,
					IF(DB.id_mesejecucion=7,SUM(DB.precio_bitacora),0) AS JulioP,
					IF(DB.id_mesejecucion=8,SUM(DB.precio_bitacora),0) AS AgostoP,
					IF(DB.id_mesejecucion=9,SUM(DB.precio_bitacora),0) AS SeptiembreP,
					IF(DB.id_mesejecucion=10,SUM(DB.precio_bitacora),0) AS OctubreP,
					IF(DB.id_mesejecucion=11,SUM(DB.precio_bitacora),0) AS NoviembreP,
					IF(DB.id_mesejecucion=12,SUM(DB.precio_bitacora),0) AS DiciembreP,
					SUM(DB.precio_bitacora) AS TOTALP,
					IF(DB.id_mesejecucion=1,SUM(DB.costo_bitacora),0) AS EneroC,
					IF(DB.id_mesejecucion=2,SUM(DB.costo_bitacora),0) AS FebreroC,
					IF(DB.id_mesejecucion=3,SUM(DB.costo_bitacora),0) AS MarzoC,
					IF(DB.id_mesejecucion=4,SUM(DB.costo_bitacora),0) AS AbrilC,
					IF(DB.id_mesejecucion=5,SUM(DB.costo_bitacora),0) AS MayoC,
					IF(DB.id_mesejecucion=6,SUM(DB.costo_bitacora),0) AS JunioC,
					IF(DB.id_mesejecucion=7,SUM(DB.costo_bitacora),0) AS JulioC,
					IF(DB.id_mesejecucion=8,SUM(DB.costo_bitacora),0) AS AgostoC,
					IF(DB.id_mesejecucion=9,SUM(DB.costo_bitacora),0) AS SeptiembreC,
					IF(DB.id_mesejecucion=10,SUM(DB.costo_bitacora),0) AS OctubreC,
					IF(DB.id_mesejecucion=11,SUM(DB.costo_bitacora),0) AS NoviembreC,
					IF(DB.id_mesejecucion=12,SUM(DB.costo_bitacora),0) AS DiciembreC,
					SUM(DB.costo_bitacora) AS TOTALC,
					IF(DB.id_mesejecucion=1,SUM(DB.precio_bitacora*DB.gasto_directo),0) AS EneroG,
					IF(DB.id_mesejecucion=2,SUM(DB.precio_bitacora*DB.gasto_directo),0) AS FebreroG,
					IF(DB.id_mesejecucion=3,SUM(DB.precio_bitacora*DB.gasto_directo),0) AS MarzoG,
					IF(DB.id_mesejecucion=4,SUM(DB.precio_bitacora*DB.gasto_directo),0) AS AbrilG,
					IF(DB.id_mesejecucion=5,SUM(DB.precio_bitacora*DB.gasto_directo),0) AS MayoG,
					IF(DB.id_mesejecucion=6,SUM(DB.precio_bitacora*DB.gasto_directo),0) AS JunioG,
					IF(DB.id_mesejecucion=7,SUM(DB.precio_bitacora*DB.gasto_directo),0) AS JulioG,
					IF(DB.id_mesejecucion=8,SUM(DB.precio_bitacora*DB.gasto_directo),0) AS AgostoG,
					IF(DB.id_mesejecucion=9,SUM(DB.precio_bitacora*DB.gasto_directo),0) AS SeptiembreG,
					IF(DB.id_mesejecucion=10,SUM(DB.precio_bitacora*DB.gasto_directo),0) AS OctubreG,
					IF(DB.id_mesejecucion=11,SUM(DB.precio_bitacora*DB.gasto_directo),0) AS NoviembreG,
					IF(DB.id_mesejecucion=12,SUM(DB.precio_bitacora*DB.gasto_directo),0) AS DiciembreG,
					SUM(DB.precio_bitacora*DB.gasto_directo) AS TOTALG
				FROM T_Clientes AS C
				LEFT OUTER JOIN T_Bitacora AS B
				ON B.id_cliente = C.id_cliente
				LEFT OUTER JOIN T_Detalle_Bitacora AS DB
				ON DB.id_bitacora = B.id_bitacora
				LEFT OUTER JOIN T_Subproductos AS S
				ON S.id_subproducto = DB.id_subproducto
				WHERE YEAR(B.fecha_audit) = :year AND DB.id_estatusejecucion=1
				GROUP BY DB.id_mesejecucion";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(":year",$year);
		$stmt->execute();
		//ESTACIONARIEDAD ESCENARIO 2
		$query = "SELECT 
				SUM(anticipo),
				SUM(mes1_Ej) AS Enero,
				SUM(mes2_Ej) AS Febrero,
				SUM(mes3_Ej) AS Marzo,
				SUM(mes4_Ej) AS Abril,
				SUM(mes5_Ej) AS Mayo,
				SUM(mes6_Ej) AS Junio,
				SUM(mes7_Ej) AS Julio,
				SUM(mes8_Ej) AS Agosto,
				SUM(mes9_Ej) AS Septiembre,
				SUM(mes10_Ej) AS Octubre,
				SUM(mes11_Ej) AS Noviembre,
				SUM(mes12_Ej) AS Diciembre,
				SUM(mes1_Ej+mes2_Ej+mes3_Ej+mes4_Ej+mes5_Ej+mes6_Ej+mes7_Ej+mes8_Ej+mes9_Ej+mes10_Ej+mes11_Ej+mes12_Ej) AS TOTAL,
				SUM(mes1_Ej*costo_ac) AS EneroC,
				SUM(mes2_Ej*costo_ac) AS FebreroC,
				SUM(mes3_Ej*costo_ac) AS MarzoC,
				SUM(mes4_Ej*costo_ac) AS AbrilC,
				SUM(mes5_Ej*costo_ac) AS MayoC,
				SUM(mes6_Ej*costo_ac) AS JunioC,
				SUM(mes7_Ej*costo_ac) AS JulioC,
				SUM(mes8_Ej*costo_ac) AS AgostoC,
				SUM(mes9_Ej*costo_ac) AS SeptiembreC,
				SUM(mes10_Ej*costo_ac) AS OctubreC,
				SUM(mes11_Ej*costo_ac) AS NoviembreC,
				SUM(mes12_Ej*costo_ac) AS DiciembreC,
				SUM(cuotadef_ac*costo_ac) AS TOTALC,
				(costo_ac) AS costo,
				SUM(mes1_Ej*gasto_ac) AS EneroG,
				SUM(mes2_Ej*gasto_ac) AS FebreroG,
				SUM(mes3_Ej*gasto_ac) AS MarzoG,
				SUM(mes4_Ej*gasto_ac) AS AbrilG,
				SUM(mes5_Ej*gasto_ac) AS MayoG,
				SUM(mes6_Ej*gasto_ac) AS JunioG,
				SUM(mes7_Ej*gasto_ac) AS JulioG,
				SUM(mes8_Ej*gasto_ac) AS AgostoG,
				SUM(mes9_Ej*gasto_ac) AS SeptiembreG,
				SUM(mes10_Ej*gasto_ac) AS OctubreG,
				SUM(mes11_Ej*gasto_ac) AS NoviembreG,
				SUM(mes12_Ej*gasto_ac) AS DiciembreG,
				(gasto_ac) AS gasto,
				SUM(cuotadef_ac*gasto_ac) AS TOTALG
			FROM `T_Detalle_AnalisisCuantitativo_Escenario2` AS DAC
			INNER JOIN `T_AnalisisCuantitativo_Escenario2` AS AC
		    ON DAC.id_analisiscuantitativo = AC.id_analisiscuantitativo
			INNER JOIN T_Clientes AS C 
		    ON C.id_cliente = AC.id_cliente 
		    WHERE AC.year_acuant = :year";
		$stmt2 = $this->conn->prepare($query);
		$stmt2->bindparam(":year",$year);
		$stmt2->execute();
		//QUERY GAO PROYECTADO
		$query ="SELECT 
				  N.year_nomina,
				  (SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.intereses)) AS TotalGAO,
				  (SUM(N.monto)) AS TotalNomina,
				  (SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)) AS TotalSAC,
				  0 AS TotalIntereses,
				  0 AS TotalOtros
				  FROM T_Nomina_GAO_Escenario2 AS N
				  INNER JOIN T_Plantilla AS P
				  ON N.id_plantilla = P.id_plantilla
				  INNER JOIN T_PlantillaDatos as PD
				  ON PD.id_plantilladatos = P.id_plantilladatos
				  WHERE year_nomina = :year";
		$stmt3 = $this->conn->prepare($query);
		$stmt3->bindparam(":year",$year);
		$stmt3->execute();
		//QUERY GAO REAL
		$query ="SELECT 
				  IF(N.id_mes=1,(SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.intereses)+SUM(otros)),0) AS EneroGAO,
				  IF(N.id_mes=2,(SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.intereses)+SUM(otros)),0) AS FebreroGAO,
				  IF(N.id_mes=3,(SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.intereses)+SUM(otros)),0) AS MarzoGAO,
				  IF(N.id_mes=4,(SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.intereses)+SUM(otros)),0) AS AbrilGAO,
				  IF(N.id_mes=5,(SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.intereses)+SUM(otros)),0) AS MayoGAO,
				  IF(N.id_mes=6,(SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.intereses)+SUM(otros)),0) AS JunioGAO,
				  IF(N.id_mes=7,(SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.intereses)+SUM(otros)),0) AS JulioGAO,
				  IF(N.id_mes=8,(SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.intereses)+SUM(otros)),0) AS AgostoGAO,
				  IF(N.id_mes=9,(SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.intereses)+SUM(otros)),0) AS SeptiembreGAO,
				  IF(N.id_mes=10,(SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.intereses)+SUM(otros)),0) AS OctubreGAO,
				  IF(N.id_mes=11,(SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.intereses)+SUM(otros)),0) AS NoviembreGAO,
				  IF(N.id_mes=12,(SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.intereses)+SUM(otros)),0) AS DiciembreGAO,
				  (SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.intereses)+SUM(otros)) AS TotalGAO,
				  IF(N.id_mes=1,(SUM(N.monto)),0) AS EneroNomina,
				  IF(N.id_mes=2,(SUM(N.monto)),0) AS FebreroNomina,
				  IF(N.id_mes=3,(SUM(N.monto)),0) AS MarzoNomina,
				  IF(N.id_mes=4,(SUM(N.monto)),0) AS AbrilNomina,
				  IF(N.id_mes=5,(SUM(N.monto)),0) AS MayoNomina,
				  IF(N.id_mes=6,(SUM(N.monto)),0) AS JunioNomina,
				  IF(N.id_mes=7,(SUM(N.monto)),0) AS JulioNomina,
				  IF(N.id_mes=8,(SUM(N.monto)),0) AS AgostoNomina,
				  IF(N.id_mes=9,(SUM(N.monto)),0) AS SeptiembreNomina,
				  IF(N.id_mes=10,(SUM(N.monto)),0) AS OctubreNomina,
				  IF(N.id_mes=11,(SUM(N.monto)),0) AS NoviembreNomina,
				  IF(N.id_mes=12,(SUM(N.monto)),0) AS DiciembreNomina,
				  (SUM(N.monto)) AS TotalNomina,
				  IF(N.id_mes=1,(SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)),0) AS EneroSAC,
				  IF(N.id_mes=2,(SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)),0) AS FebreroSAC,
				  IF(N.id_mes=3,(SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)),0) AS MarzoSAC,
				  IF(N.id_mes=4,(SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)),0) AS AbrilSAC,
				  IF(N.id_mes=5,(SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)),0) AS MayoSAC,
				  IF(N.id_mes=6,(SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)),0) AS JunioSAC,
				  IF(N.id_mes=7,(SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)),0) AS JulioSAC,
				  IF(N.id_mes=8,(SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)),0) AS AgostoSAC,
				  IF(N.id_mes=9,(SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)),0) AS SeptiembreSAC,
				  IF(N.id_mes=10,(SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)),0) AS OctubreSAC,
				  IF(N.id_mes=11,(SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)),0) AS NoviembreSAC,
				  IF(N.id_mes=12,(SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)),0) AS DiciembreSAC,
				  (SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)) AS TotalSAC,
				  IF(N.id_mes=1,(SUM(otros)),0) AS EneroOtros,
				  IF(N.id_mes=2,(SUM(otros)),0) AS FebreroOtros,
				  IF(N.id_mes=3,(SUM(otros)),0) AS MarzoOtros,
				  IF(N.id_mes=4,(SUM(otros)),0) AS AbrilOtros,
				  IF(N.id_mes=5,(SUM(otros)),0) AS MayoOtros,
				  IF(N.id_mes=6,(SUM(otros)),0) AS JunioOtros,
				  IF(N.id_mes=7,(SUM(otros)),0) AS JulioOtros,
				  IF(N.id_mes=8,(SUM(otros)),0) AS AgostoOtros,
				  IF(N.id_mes=9,(SUM(otros)),0) AS SeptiembreOtros,
				  IF(N.id_mes=10,(SUM(otros)),0) AS OctubreOtros,
				  IF(N.id_mes=11,(SUM(otros)),0) AS NoviembreOtros,
				  IF(N.id_mes=12,(SUM(otros)),0) AS DiciembreOtros,
				  (SUM(otros)) AS TotalOtros,
				  IF(N.id_mes=1,(SUM(N.intereses)),0) AS EneroIntereses,
				  IF(N.id_mes=2,(SUM(N.intereses)),0) AS FebreroIntereses,
				  IF(N.id_mes=3,(SUM(N.intereses)),0) AS MarzoIntereses,
				  IF(N.id_mes=4,(SUM(N.intereses)),0) AS AbrilIntereses,
				  IF(N.id_mes=5,(SUM(N.intereses)),0) AS MayoIntereses,
				  IF(N.id_mes=6,(SUM(N.intereses)),0) AS JunioIntereses,
				  IF(N.id_mes=7,(SUM(N.intereses)),0) AS JulioIntereses,
				  IF(N.id_mes=8,(SUM(N.intereses)),0) AS AgostoIntereses,
				  IF(N.id_mes=9,(SUM(N.intereses)),0) AS SeptiembreIntereses,
				  IF(N.id_mes=10,(SUM(N.intereses)),0) AS OctubreIntereses,
				  IF(N.id_mes=11,(SUM(N.intereses)),0) AS NoviembreIntereses,
				  IF(N.id_mes=12,(SUM(N.intereses)),0) AS DiciembreIntereses,
				  (SUM(N.intereses)) AS TotalIntereses
				  FROM T_Nomina_GAO_Real AS N
				  INNER JOIN T_Plantilla AS P
				  ON N.id_plantilla = P.id_plantilla
				  INNER JOIN T_PlantillaDatos as PD
				  ON PD.id_plantilladatos = P.id_plantilladatos
				  WHERE N.year_nomina = :year";
		$stmt4 = $this->conn->prepare($query);
		$stmt4->bindparam(":year",$year);
		$stmt4->execute();
		//QUERY ANTICIPOS
		$query ="SELECT 
					(CASE WHEN id_mes=1 THEN SUM(anticipo) ELSE 0 END) AS Enero,
					(CASE WHEN id_mes=2 THEN SUM(anticipo) ELSE 0 END) AS Febrero,
					(CASE WHEN id_mes=3 THEN SUM(anticipo) ELSE 0 END) AS Marzo,
					(CASE WHEN id_mes=4 THEN SUM(anticipo) ELSE 0 END) AS Abril,
					(CASE WHEN id_mes=5 THEN SUM(anticipo) ELSE 0 END) AS Mayo,
					(CASE WHEN id_mes=6 THEN SUM(anticipo) ELSE 0 END) AS Junio,
					(CASE WHEN id_mes=7 THEN SUM(anticipo) ELSE 0 END) AS Julio,
					(CASE WHEN id_mes=8 THEN SUM(anticipo) ELSE 0 END) AS Agosto,
					(CASE WHEN id_mes=9 THEN SUM(anticipo) ELSE 0 END) AS Septiembre,
					(CASE WHEN id_mes=10 THEN SUM(anticipo) ELSE 0 END) AS Octubre,
					(CASE WHEN id_mes=11 THEN SUM(anticipo) ELSE 0 END) AS Noviembre,
					(CASE WHEN id_mes=12 THEN SUM(anticipo) ELSE 0 END) AS Diciembre,
					SUM(anticipo) AS TotalAnticipo,
					oferta
				  FROM T_AnticiposLider
				  WHERE year_anticipo = :year
				  GROUP BY id_mes";
		$stmt5 = $this->conn->prepare($query);
		$stmt5->bindparam(":year",$year);
		$stmt5->execute();
		//QUERY INTERESES
		$query = "SELECT 
					IF(DB.id_mescobranza=1,SUM(DB.precio_bitacora),0) AS Enero,
					IF(DB.id_mescobranza=2,SUM(DB.precio_bitacora),0) AS Febrero,
					IF(DB.id_mescobranza=3,SUM(DB.precio_bitacora),0) AS Marzo,
					IF(DB.id_mescobranza=4,SUM(DB.precio_bitacora),0) AS Abril,
					IF(DB.id_mescobranza=5,SUM(DB.precio_bitacora),0) AS Mayo,
					IF(DB.id_mescobranza=6,SUM(DB.precio_bitacora),0) AS Junio,
					IF(DB.id_mescobranza=7,SUM(DB.precio_bitacora),0) AS Julio,
					IF(DB.id_mescobranza=8,SUM(DB.precio_bitacora),0) AS Agosto,
					IF(DB.id_mescobranza=9,SUM(DB.precio_bitacora),0) AS Septiembre,
					IF(DB.id_mescobranza=10,SUM(DB.precio_bitacora),0) AS Octubre,
					IF(DB.id_mescobranza=11,SUM(DB.precio_bitacora),0) AS Noviembre,
					IF(DB.id_mescobranza=12,SUM(DB.precio_bitacora),0) AS Diciembre
				FROM T_Clientes AS C
				LEFT OUTER JOIN T_Bitacora AS B
				ON B.id_cliente = C.id_cliente
				LEFT OUTER JOIN T_Detalle_Bitacora AS DB
				ON DB.id_bitacora = B.id_bitacora
				LEFT OUTER JOIN T_Subproductos AS S
				ON S.id_subproducto = DB.id_subproducto
				WHERE YEAR(B.fecha_audit) = :year AND DB.id_estatuscobranza=1";
		$stmt6 = $this->conn->prepare($query);
		$stmt6->bindparam(":year",$year);
		$stmt6->execute();
		
		
		if($stmt2->rowCount()>0)
		{
			$enero = 0;
			$eneroC = 0;
			$eneroG = 0;
			$febrero = 0;
			$febreroC = 0;
			$febreroG = 0;
			$marzo = 0;
			$marzoC = 0;
			$marzoG = 0;
			$abril = 0;
			$abrilC = 0;
			$abrilG = 0;
			$mayo = 0;
			$mayoC = 0;
			$mayoG = 0;
			$junio = 0;
			$junioC = 0;
			$junioG = 0;
			$julio = 0;
			$julioC = 0;
			$julioG = 0;
			$agosto = 0;
			$agostoC = 0;
			$agostoG = 0;
			$septiembre = 0;
			$septiembreC = 0;
			$septiembreG = 0;
			$octubre = 0;
			$octubreC = 0;
			$octubreG = 0;
			$noviembre = 0;
			$noviembreC = 0;
			$noviembreG = 0;
			$diciembre = 0;
			$diciembreC = 0;
			$diciembreG = 0;
			$TOTALP = 0;
			$TOTALC = 0;
			$TOTALG = 0;
			$eneroA = 0;
			$febreroA = 0;
			$marzoA = 0;
			$abrilA = 0;
			$mayoA = 0;
			$junioA = 0;
			$julioA = 0;
			$agostoA = 0;
			$septiembreA = 0;
			$octubreA = 0;
			$noviembreA = 0;
			$diciembreA = 0;
			$TOTALA = 0;
			while($row=$stmt->fetch(PDO::FETCH_ASSOC))
			{
				$enero = $enero + $row['EneroP'];
				$febrero = $febrero + $row['FebreroP'];
				$marzo = $marzo + $row['MarzoP'];
				$abril = $abril + $row['AbrilP'];
				$mayo = $mayo + $row['MayoP'];
				$junio = $junio + $row['JunioP'];
				$julio = $julio + $row['JulioP'];
				$agosto = $agosto + $row['AgostoP'];
				$septiembre = $septiembre + $row['SeptiembreP'];
				$octubre = $octubre + $row['OctubreP'];
				$noviembre = $noviembre + $row['NoviembreP'];
				$diciembre = $diciembre + $row['DiciembreP'];
				
				$eneroC = $eneroC + $row['EneroC'];
				$febreroC = $febreroC + $row['FebreroC'];
				$marzoC = $marzoC + $row['MarzoC'];
				$abrilC = $abrilC + $row['AbrilC'];
				$mayoC = $mayoC + $row['MayoC'];
				$junioC = $junioC + $row['JunioC'];
				$julioC = $julioC + $row['JulioC'];
				$agostoC = $agostoC + $row['AgostoC'];
				$septiembreC = $septiembreC + $row['SeptiembreC'];
				$octubreC = $octubreC + $row['OctubreC'];
				$noviembreC = $noviembreC + $row['NoviembreC'];
				$diciembreC = $diciembreC + $row['DiciembreC'];
				
				$eneroG = $eneroG + $row['EneroG'];
				$febreroG = $febreroG + $row['FebreroG'];
				$marzoG = $marzoG + $row['MarzoG'];
				$abrilG = $abrilG + $row['AbrilG'];
				$mayoG = $mayoG + $row['MayoG'];
				$junioG = $junioG + $row['JunioG'];
				$julioG = $julioG + $row['JulioG'];
				$agostoG = $agostoG + $row['AgostoG'];
				$septiembreG = $septiembreG + $row['SeptiembreG'];
				$octubreG = $octubreG + $row['OctubreG'];
				$noviembreG = $noviembreG + $row['NoviembreG'];
				$diciembreG = $diciembreG + $row['DiciembreG'];
				
				$TOTALP = $TOTALP + $row['TOTALP'];
				$TOTALC = $TOTALC + $row['TOTALC'];
				$TOTALG = $TOTALG + $row['TOTALG'];
			}
			while($row5=$stmt5->fetch(PDO::FETCH_ASSOC))
			{
				$eneroA = $eneroA + $row5['Enero'];
				$febreroA = $febreroA + $row5['Febrero'];
				$marzoA = $marzoA + $row5['Marzo'];
				$abrilA = $abrilA + $row5['Abril'];
				$mayoA = $mayoA + $row5['Mayo'];
				$junioA = $junioA + $row5['Junio'];
				$julioA = $julioA + $row5['Julio'];
				$agostoA = $agostoA + $row5['Agosto'];
				$septiembreA = $septiembreA + $row5['Septiembre'];
				$octubreA = $octubreA + $row5['Octubre'];
				$noviembreA = $noviembreA + $row5['Noviembre'];
				$diciembreA = $diciembreA + $row5['Diciembre'];
				$TOTALA = $TOTALA + $row5['TotalAnticipo'];
				$oferta = $row5['oferta'];
			}
			$row5['Enero'] = $eneroA;
			$row5['Febrero'] = $febreroA;
			$row5['Marzo'] = $marzoA;
			$row5['Abril'] = $abrilA;
			$row5['Mayo'] = $mayoA;
			$row5['Junio'] = $junioA;
			$row5['Julio'] = $julioA;
			$row5['Agosto'] = $agostoA;
			$row5['Septiembre'] = $septiembreA;
			$row5['Octubre'] = $octubreA;
			$row5['Noviembre'] = $noviembreA;
			$row5['Diciembre'] = $diciembreA;
			$row5['oferta'] = $oferta;
			$row['EneroP'] = $enero;
			$row['FebreroP'] = $febrero;
			$row['MarzoP'] = $marzo;
			$row['AbrilP'] = $abril;
			$row['MayoP'] = $mayo;
			$row['JunioP'] = $junio;
			$row['JulioP'] = $julio;
			$row['AgostoP'] = $agosto;
			$row['SeptiembreP'] = $septiembre;
			$row['OctubreP'] = $octubre;
			$row['NoviembreP'] = $noviembre;
			$row['DiciembreP'] = $diciembre;
			
			$row['EneroC'] = $eneroC;
			$row['FebreroC'] = $febreroC;
			$row['MarzoC'] = $marzoC;
			$row['AbrilC'] = $abrilC;
			$row['MayoC'] = $mayoC;
			$row['JunioC'] = $junioC;
			$row['JulioC'] = $julioC;
			$row['AgostoC'] = $agostoC;
			$row['SeptiembreC'] = $septiembreC;
			$row['OctubreC'] = $octubreC;
			$row['NoviembreC'] = $noviembreC;
			$row['DiciembreC'] = $diciembreC;
			
			$row['EneroG'] = $eneroG;
			$row['FebreroG'] = $febreroG;
			$row['MarzoG'] = $marzoG;
			$row['AbrilG'] = $abrilG;
			$row['MayoG'] = $mayoG;
			$row['JunioG'] = $junioG;
			$row['JulioG'] = $julioG;
			$row['AgostoG'] = $agostoG;
			$row['SeptiembreG'] = $septiembreG;
			$row['OctubreG'] = $octubreG;
			$row['NoviembreG'] = $noviembreG;
			$row['DiciembreG'] = $diciembreG;
			
			$row['TOTALP'] = $TOTALP;
			$row['TOTALC'] = $TOTALC;
			$row['TOTALG'] = $TOTALG;
			$row5['TotalAnticipo'] = $TOTALA;
			
			$row2=$stmt2->fetch(PDO::FETCH_ASSOC);
			$row3=$stmt3->fetch(PDO::FETCH_ASSOC);
			$row4=$stmt4->fetch(PDO::FETCH_ASSOC);
			$row6=$stmt6->fetch(PDO::FETCH_ASSOC);
			
			//INGRESOS
			$diferencia1 = $row2['Enero']-$row['EneroP'];
			$diferencia2 = $row2['Febrero']-$row['FebreroP'];
			$diferencia3 = $row2['Marzo']-$row['MarzoP'];
			$diferencia4 = $row2['Abril']-$row['AbrilP'];
			$diferencia5 = $row2['Mayo']-$row['MayoP'];
			$diferencia6 = $row2['Junio']-$row['JunioP'];
			$diferencia7 = $row2['Julio']-$row['JulioP'];
			$diferencia8 = $row2['Agosto']-$row['AgostoP'];
			$diferencia9 = $row2['Septiembre']-$row['SeptiembreP'];
			$diferencia10 = $row2['Octubre']-$row['OctubreP'];
			$diferencia11 = $row2['Noviembre']-$row['NoviembreP'];
			$diferencia12 = $row2['Diciembre']-$row['DiciembreP'];
			$enero = $row2['Enero']-$row['EneroP'];
			$febrero = ($enero+$row2['Febrero'])-$row['FebreroP'];
			$marzo = ($febrero+$row2['Marzo'])-$row['MarzoP'];
			$abril = ($marzo+$row2['Abril'])-$row['AbrilP'];
			$mayo = ($abril+$row2['Mayo'])-$row['MayoP'];
			$junio = ($mayo+$row2['Junio'])-$row['JunioP'];
			$julio = ($junio+$row2['Julio'])-$row['JulioP'];
			$agosto = ($julio+$row2['Agosto'])-$row['AgostoP'];
			$septiembre = ($agosto+$row2['Septiembre'])-$row['SeptiembreP'];
			$octubre = ($septiembre+$row2['Octubre'])-$row['OctubreP'];
			$noviembre = ($octubre+$row2['Noviembre'])-$row['NoviembreP'];
			$diciembre = ($noviembre+$row2['Diciembre'])-$row['DiciembreP'];
			
			//COSTOS
			$diferencia1C = $row2['EneroC']-$row['EneroC'];
			$diferencia2C = $row2['FebreroC']-$row['FebreroC'];
			$diferencia3C = $row2['MarzoC']-$row['MarzoC'];
			$diferencia4C = $row2['AbrilC']-$row['AbrilC'];
			$diferencia5C = $row2['MayoC']-$row['MayoC'];
			$diferencia6C = $row2['JunioC']-$row['JunioC'];
			$diferencia7C = $row2['JulioC']-$row['JulioC'];
			$diferencia8C = $row2['AgostoC']-$row['AgostoC'];
			$diferencia9C = $row2['SeptiembreC']-$row['SeptiembreC'];
			$diferencia10C = $row2['OctubreC']-$row['OctubreC'];
			$diferencia11C = $row2['NoviembreC']-$row['NoviembreC'];
			$diferencia12C = $row2['DiciembreC']-$row['DiciembreC'];
			$eneroC = $row2['EneroC']-$row['EneroC'];
			$febreroC = $eneroC+($row2['FebreroC']-$row['FebreroC']);
			$marzoC = $febreroC+($row2['MarzoC']-$row['MarzoC']);
			$abrilC = $marzoC+($row2['AbrilC']-$row['AbrilC']);
			$mayoC = $abrilC+($row2['MayoC']-$row['MayoC']);
			$junioC = $mayoC+($row2['JunioC']-$row['JunioC']);
			$julioC = $junioC+($row2['JulioC']-$row['JulioC']);
			$agostoC = $julioC+($row2['AgostoC']-$row['AgostoC']);
			$septiembreC = $agostoC+($row2['SeptiembreC']-$row['SeptiembreC']);
			$octubreC = $septiembreC+($row2['OctubreC']-$row['OctubreC']);
			$noviembreC = $octubreC+($row2['NoviembreC']-$row['NoviembreC']);
			$diciembreC = $noviembreC+($row2['DiciembreC']-$row['DiciembreC']);
			
			//GASTO
			$diferencia1G = $row2['EneroG']-$row['EneroG'];
			$diferencia2G = $row2['FebreroG']-$row['FebreroG'];
			$diferencia3G = $row2['MarzoG']-$row['MarzoG'];
			$diferencia4G = $row2['AbrilG']-$row['AbrilG'];
			$diferencia5G = $row2['MayoG']-$row['MayoG'];
			$diferencia6G = $row2['JunioG']-$row['JunioG'];
			$diferencia7G = $row2['JulioG']-$row['JulioG'];
			$diferencia8G = $row2['AgostoG']-$row['AgostoG'];
			$diferencia9G = $row2['SeptiembreG']-$row['SeptiembreG'];
			$diferencia10G = $row2['OctubreG']-$row['OctubreG'];
			$diferencia11G = $row2['NoviembreG']-$row['NoviembreG'];
			$diferencia12G = $row2['DiciembreG']-$row['DiciembreG'];
			$eneroG = $row2['EneroG']-$row['EneroG'];
			$febreroG = $eneroG+($row2['FebreroG']-$row['FebreroG']);
			$marzoG = $febreroG+($row2['MarzoG']-$row['MarzoG']);
			$abrilG = $marzoG+($row2['AbrilG']-$row['AbrilG']);
			$mayoG = $abrilG+($row2['MayoG']-$row['MayoG']);
			$junioG = $mayoG+($row2['JunioG']-$row['JunioG']);
			$julioG = $junioG+($row2['JulioG']-$row['JulioG']);
			$agostoG = $julioG+($row2['AgostoG']-$row['AgostoG']);
			$septiembreG = $agostoG+($row2['SeptiembreG']-$row['SeptiembreG']);
			$octubreG = $septiembreG+($row2['OctubreG']-$row['OctubreG']);
			$noviembreG = $octubreG+($row2['NoviembreG']-$row['NoviembreG']);
			$diciembreG = $noviembreG+($row2['DiciembreG']-$row['DiciembreG']);
			
			//GAOFIJO
			$gaoMensual = $row3['TotalGAO']/12;
			$nominaMensual = $row3['TotalNomina']/12;
			$sacMensual = $row3['TotalSAC']/12;
			$interesesMensual = $row3['TotalIntereses']/12;
			$otrosMensual = 0;
			
			//NOMINA
			$diferencia1Nom = $row4['EneroNomina']-$nominaMensual;
			$diferencia2Nom = $row4['FebreroNomina']-$nominaMensual;
			$diferencia3Nom = $row4['MarzoNomina']-$nominaMensual;
			$diferencia4Nom = $row4['AbrilNomina']-$nominaMensual;
			$diferencia5Nom = $row4['MayoNomina']-$nominaMensual;
			$diferencia6Nom = $row4['JunioNomina']-$nominaMensual;
			$diferencia7Nom = $row4['JulioNomina']-$nominaMensual;
			$diferencia8Nom = $row4['AgostoNomina']-$nominaMensual;
			$diferencia9Nom = $row4['SeptiembreNomina']-$nominaMensual;
			$diferencia10Nom = $row4['OctubreNomina']-$nominaMensual;
			$diferencia11Nom = $row4['NoviembreNomina']-$nominaMensual;
			$diferencia12Nom = $row4['DiciembreNomina']-$nominaMensual;
			$eneroNom = $row4['EneroNomina']-$nominaMensual;
			$febreroNom = $eneroNom+($row4['FebreroNomina']-$nominaMensual);
			$marzoNom = $febreroNom+($row4['MarzoNomina']-$nominaMensual);
			$abrilNom = $marzoNom+($row4['AbrilNomina']-$nominaMensual);
			$mayoNom = $abrilNom+($row4['MayoNomina']-$nominaMensual);
			$junioNom = $mayoNom+($row4['JunioNomina']-$nominaMensual);
			$julioNom = $junioNom+($row4['JulioNomina']-$nominaMensual);
			$agostoNom = $julioNom+($row4['AgostoNomina']-$nominaMensual);
			$septiembreNom = $agostoNom+($row4['SeptiembreNomina']-$nominaMensual);
			$octubreNom = $septiembreNom+($row4['OctubreNomina']-$nominaMensual);
			$noviembreNom = $octubreNom+($row4['NoviembreNomina']-$nominaMensual);
			$diciembreNom = $noviembreNom+($row4['DiciembreNomina']-$nominaMensual);
			
			//SAC
			$diferencia1SAC = $row4['EneroSAC']-$sacMensual;
			$diferencia2SAC = $row4['FebreroSAC']-$sacMensual;
			$diferencia3SAC = $row4['MarzoSAC']-$sacMensual;
			$diferencia4SAC = $row4['AbrilSAC']-$sacMensual;
			$diferencia5SAC = $row4['MayoSAC']-$sacMensual;
			$diferencia6SAC = $row4['JunioSAC']-$sacMensual;
			$diferencia7SAC = $row4['JulioSAC']-$sacMensual;
			$diferencia8SAC = $row4['AgostoSAC']-$sacMensual;
			$diferencia9SAC = $row4['SeptiembreSAC']-$sacMensual;
			$diferencia10SAC = $row4['OctubreSAC']-$sacMensual;
			$diferencia11SAC = $row4['NoviembreSAC']-$sacMensual;
			$diferencia12SAC = $row4['DiciembreSAC']-$sacMensual;
			$eneroSAC = $row4['EneroSAC']-$sacMensual;
			$febreroSAC = $eneroSAC+($row4['FebreroSAC']-$sacMensual);
			$marzoSAC = $febreroSAC+($row4['MarzoSAC']-$sacMensual);
			$abrilSAC = $marzoSAC+($row4['AbrilSAC']-$sacMensual);
			$mayoSAC = $abrilSAC+($row4['MayoSAC']-$sacMensual);
			$junioSAC = $mayoSAC+($row4['JunioSAC']-$sacMensual);
			$julioSAC = $junioSAC+($row4['JulioSAC']-$sacMensual);
			$agostoSAC = $julioSAC+($row4['AgostoSAC']-$sacMensual);
			$septiembreSAC = $agostoSAC+($row4['SeptiembreSAC']-$sacMensual);
			$octubreSAC = $septiembreSAC+($row4['OctubreSAC']-$sacMensual);
			$noviembreSAC = $octubreSAC+($row4['NoviembreSAC']-$sacMensual);
			$diciembreSAC = $noviembreSAC+($row4['DiciembreSAC']-$sacMensual);
			
			//OTROS
			$diferencia1Otros = $row4['EneroOtros']-$otrosMensual;
			$diferencia2Otros = $row4['FebreroOtros']-$otrosMensual;
			$diferencia3Otros = $row4['MarzoOtros']-$otrosMensual;
			$diferencia4Otros = $row4['AbrilOtros']-$otrosMensual;
			$diferencia5Otros = $row4['MayoOtros']-$otrosMensual;
			$diferencia6Otros = $row4['JunioOtros']-$otrosMensual;
			$diferencia7Otros = $row4['JulioOtros']-$otrosMensual;
			$diferencia8Otros = $row4['AgostoOtros']-$otrosMensual;
			$diferencia9Otros = $row4['SeptiembreOtros']-$otrosMensual;
			$diferencia10Otros = $row4['OctubreOtros']-$otrosMensual;
			$diferencia11Otros = $row4['NoviembreOtros']-$otrosMensual;
			$diferencia12Otros = $row4['DiciembreOtros']-$otrosMensual;
			$eneroOtros = $row4['EneroOtros']-$otrosMensual;
			$febreroOtros = $eneroOtros+($row4['FebreroOtros']-$otrosMensual);
			$marzoOtros = $febreroOtros+($row4['MarzoOtros']-$otrosMensual);
			$abrilOtros = $marzoOtros+($row4['AbrilOtros']-$otrosMensual);
			$mayoOtros = $abrilOtros+($row4['MayoOtros']-$otrosMensual);
			$junioOtros = $mayoOtros+($row4['JunioOtros']-$otrosMensual);
			$julioOtros = $junioOtros+($row4['JulioOtros']-$otrosMensual);
			$agostoOtros = $julioOtros+($row4['AgostoOtros']-$otrosMensual);
			$septiembreOtros = $agostoOtros+($row4['SeptiembreOtros']-$otrosMensual);
			$octubreOtros = $septiembreOtros+($row4['OctubreOtros']-$otrosMensual);
			$noviembreOtros = $octubreOtros+($row4['NoviembreOtros']-$otrosMensual);
			$diciembreOtros = $noviembreOtros+($row4['DiciembreOtros']-$otrosMensual);
			
			//GAO
			$diferencia1GAO = $row4['EneroGAO']-$gaoMensual;
			$diferencia2GAO = $row4['FebreroGAO']-$gaoMensual;
			$diferencia3GAO = $row4['MarzoGAO']-$gaoMensual;
			$diferencia4GAO = $row4['AbrilGAO']-$gaoMensual;
			$diferencia5GAO = $row4['MayoGAO']-$gaoMensual;
			$diferencia6GAO = $row4['JunioGAO']-$gaoMensual;
			$diferencia7GAO = $row4['JulioGAO']-$gaoMensual;
			$diferencia8GAO = $row4['AgostoGAO']-$gaoMensual;
			$diferencia9GAO = $row4['SeptiembreGAO']-$gaoMensual;
			$diferencia10GAO = $row4['OctubreGAO']-$gaoMensual;
			$diferencia11GAO = $row4['NoviembreGAO']-$gaoMensual;
			$diferencia12GAO = $row4['DiciembreGAO']-$gaoMensual;
			$eneroGAO = $row4['EneroGAO']-$gaoMensual;
			$febreroGAO = $eneroGAO+($row4['FebreroGAO']-$gaoMensual);
			$marzoGAO = $febreroGAO+($row4['MarzoGAO']-$gaoMensual);
			$abrilGAO = $marzoGAO+($row4['AbrilGAO']-$gaoMensual);
			$mayoGAO = $abrilGAO+($row4['MayoGAO']-$gaoMensual);
			$junioGAO = $mayoGAO+($row4['JunioGAO']-$gaoMensual);
			$julioGAO = $junioGAO+($row4['JulioGAO']-$gaoMensual);
			$agostoGAO = $julioGAO+($row4['AgostoGAO']-$gaoMensual);
			$septiembreGAO = $agostoGAO+($row4['SeptiembreGAO']-$gaoMensual);
			$octubreGAO = $septiembreGAO+($row4['OctubreGAO']-$gaoMensual);
			$noviembreGAO = $octubreGAO+($row4['NoviembreGAO']-$gaoMensual);
			$diciembreGAO = $noviembreGAO+($row4['DiciembreGAO']-$gaoMensual);
			
			//EBCL
			$ebclEstaEnero = $row2['EneroC'] + $gaoMensual;
			$ebclEstaFebrero = $row2['FebreroC'] + $gaoMensual;
			$ebclEstaMarzo = $row2['MarzoC'] + $gaoMensual;
			$ebclEstaAbril = $row2['AbrilC'] + $gaoMensual;
			$ebclEstaMayo = $row2['MayoC'] + $gaoMensual;
			$ebclEstaJunio = $row2['JunioC'] + $gaoMensual;
			$ebclEstaJulio = $row2['JulioC'] + $gaoMensual;
			$ebclEstaAgosto = $row2['AgostoC'] + $gaoMensual;
			$ebclEstaSeptiembre = $row2['SeptiembreC'] + $gaoMensual;
			$ebclEstaOctubre = $row2['OctubreC'] + $gaoMensual;
			$ebclEstaNoviembre = $row2['NoviembreC'] + $gaoMensual;
			$ebclEstaDiciembre = $row2['DiciembreC'] + $gaoMensual;
			$ebclEstaTotal = $row2['TOTALC'] + $row3['TotalGAO'];
			$ebclEstaTotalPor = $ebclEstaTotal / $row2['TOTAL'];
			
			$ebclRealEnero = $row['EneroC'] + $row4['EneroGAO'];
			$ebclRealFebrero = $row['FebreroC'] + $row4['FebreroGAO'];
			$ebclRealMarzo = $row['MarzoC'] + $row4['MarzoGAO'];
			$ebclRealAbril = $row['AbrilC'] + $row4['AbrilGAO'];
			$ebclRealMayo = $row['MayoC'] + $row4['MayoGAO'];
			$ebclRealJunio = $row['JunioC'] + $row4['JunioGAO'];
			$ebclRealJulio = $row['JulioC'] + $row4['JulioGAO'];
			$ebclRealAgosto = $row['AgostoC'] + $row4['AgostoGAO'];
			$ebclRealSeptiembre = $row['SeptiembreC'] + $row4['SeptiembreGAO'];
			$ebclRealOctubre = $row['OctubreC'] + $row4['OctubreGAO'];
			$ebclRealNoviembre = $row['NoviembreC'] + $row4['NoviembreGAO'];
			$ebclRealDiciembre = $row['DiciembreC'] + $row4['DiciembreGAO'];
			$ebclRealTotal = $row['TOTALC'] + $row4['TotalGAO'];

			
			$ebclRealEneroAcum = $ebclRealEnero;
			$ebclRealFebreroAcum = $ebclRealEneroAcum + $ebclRealFebrero;
			$ebclRealMarzoAcum = $ebclRealFebreroAcum + $row['MarzoC'] + $row4['MarzoGAO'];
			$ebclRealAbrilAcum = $ebclRealMarzoAcum + $row['AbrilC'] + $row4['AbrilGAO'];
			$ebclRealMayoAcum = $ebclRealAbrilAcum + $row['MayoC'] + $row4['MayoGAO'];
			$ebclRealJunioAcum = $ebclRealMayoAcum +$row['JunioC'] + $row4['JunioGAO'];
			$ebclRealJulioAcum = $ebclRealJunioAcum + $row['JulioC'] + $row4['JulioGAO'];
			$ebclRealAgostoAcum = $ebclRealJulioAcum + $row['AgostoC'] + $row4['AgostoGAO'];
			$ebclRealSeptiembreAcum = $ebclRealAgostoAcum + $row['SeptiembreC'] + $row4['SeptiembreGAO'];
			$ebclRealOctubreAcum = $ebclRealSeptiembreAcum + $row['OctubreC'] + $row4['OctubreGAO'];
			$ebclRealNoviembreAcum = $ebclRealOctubreAcum + $row['NoviembreC'] + $row4['NoviembreGAO'];
			$ebclRealDiciembreAcum = $ebclRealNoviembreAcum + $row['DiciembreC'] + $row4['DiciembreGAO'];
			
			$ebclEstaEneroAcum = $ebclEstaEnero;
			$ebclEstaFebreroAcum = $ebclEstaEneroAcum + $ebclEstaFebrero;
			$ebclEstaMarzoAcum = $ebclEstaFebreroAcum + $row2['MarzoC'] + $row4['MarzoGAO'];
			$ebclEstaAbrilAcum = $ebclEstaMarzoAcum + $row2['AbrilC'] + $row4['AbrilGAO'];
			$ebclEstaMayoAcum = $ebclEstaAbrilAcum + $row2['MayoC'] + $row4['MayoGAO'];
			$ebclEstaJunioAcum = $ebclEstaMayoAcum +$row2['JunioC'] + $row4['JunioGAO'];
			$ebclEstaJulioAcum = $ebclEstaJunioAcum + $row2['JulioC'] + $row4['JulioGAO'];
			$ebclEstaAgostoAcum = $ebclEstaJulioAcum + $row2['AgostoC'] + $row4['AgostoGAO'];
			$ebclEstaSeptiembreAcum = $ebclEstaAgostoAcum + $row2['SeptiembreC'] + $row4['SeptiembreGAO'];
			$ebclEstaOctubreAcum = $ebclEstaSeptiembreAcum + $row2['OctubreC'] + $row4['OctubreGAO'];
			$ebclEstaNoviembreAcum = $ebclEstaOctubreAcum + $row2['NoviembreC'] + $row4['NoviembreGAO'];
			$ebclEstaDiciembreAcum = $ebclEstaNoviembreAcum + $row2['DiciembreC'] + $row4['DiciembreGAO'];
			
			$ebclEneroAcumT = $ebclRealEneroAcum-$ebclEstaEneroAcum;
			$ebclFebreroAcumT = $ebclRealFebreroAcum - $ebclEstaFebreroAcum;
			$ebclMarzoAcumT = $ebclRealMarzoAcum - $ebclEstaMarzoAcum;
			$ebclAbrilAcumT = $ebclRealAbrilAcum - $ebclEstaAbrilAcum;
			$ebclMayoAcumT = $ebclRealMayoAcum - $ebclEstaMayoAcum;
			$ebclJunioAcumT = $ebclRealJunioAcum - $ebclEstaJunioAcum;
			$ebclJulioAcumT = $ebclRealJulioAcum - $ebclEstaJulioAcum;
			$ebclAgostoAcumT = $ebclRealAgostoAcum - $ebclEstaAgostoAcum;
			$ebclSeptiembreAcumT = $ebclRealSeptiembreAcum - $ebclEstaSeptiembreAcum;
			$ebclOctubreAcumT = $ebclRealOctubreAcum - $ebclEstaOctubreAcum;
			$ebclNoviembreAcumT = $ebclRealNoviembreAcum - $ebclEstaNoviembreAcum;
			$ebclDiciembreAcumT = $ebclRealDiciembreAcum - $ebclEstaDiciembreAcum;
			
			
			//COMISION PROYECTADA
			$ingresos_p = floatval($row2['TOTAL']);
			$ingresos_por_p = 100;
			$costos_p = floatval($row2['TOTALC']);
			$costos_por_p = floatval($costos_p/$ingresos_p);
			$gao_p = floatval($row3['TotalGAO']);
			$gao_por_p = floatval($gao_p/$ingresos_p);
			
			$comision = 0.38;
			
			$comision_por_p = round($comision - $costos_por_p - $gao_por_p,4);
			
			$costo_real = floatval($row['TOTALC']);
			$ingreso_real = floatval($row['TOTALP']);
			if($costo_real == 0)
			{
				$costo_realP = 0;
			}
			else
			{
				$costo_realP = $costo_real/$ingreso_real;
			}
			$comision_real = round($comision - $costo_realP,4);
			
			//MARGEN DIRECTO
			$diferenciaMargenDirecto1 = $row['EneroP']-$row['EneroC']-$row['EneroG'];
			
			if($row['EneroP'] == 0)
			{
				$diferenciaMargenDirectoPor1 = 0;
				$ebclRealEneroPor = 0;
				$ebclRealEneroAcumPor = $ebclRealEneroPor;
			}
			else
			{
				$diferenciaMargenDirectoPor1 = ($row['EneroP']-$row['EneroC']-$row['EneroG'])/$row['EneroP']*100;
				$ebclRealEneroPor = $ebclRealEnero/$row['EneroP'];
				$ebclRealEneroAcumPor = $ebclRealEneroPor;
			}
			$diferenciaMargenDirecto2 =	$row['FebreroP']-$row['FebreroC']-$row['FebreroG'];
			if($row['FebreroP'] == 0)
			{
				$diferenciaMargenDirectoPor2 = 0;
				$ebclRealFebreroPor = 0;
				$ebclRealFebreroAcumPor = 0;
			}
			else
			{
				$diferenciaMargenDirectoPor2 = ($row['FebreroP']-$row['FebreroC']-$row['FebreroG'])/$row['FebreroP'];
				$ebclRealFebreroPor = $ebclRealFebrero/$row['FebreroP'];
				$ebclRealFebreroAcumPor = $ebclRealFebreroAcum/($row['EneroP']+$row['FebreroP']);	
			}
			$diferenciaMargenDirecto3 =	$row['MarzoP']-$row['MarzoC']-$row['MarzoG'];
			if($row['MarzoP'] == 0)
			{
				$diferenciaMargenDirectoPor3 = 0;
				$ebclRealMarzoPor = 0;
				$ebclRealMarzoAcumPor = 0;
			}
			else
			{
				$diferenciaMargenDirectoPor3 = ($row['MarzoP']-$row['MarzoC']-$row['MarzoG'])/$row['MarzoP']*100;
				$ebclRealMarzoPor = $ebclRealMarzo/$row['MarzoP'];
				$ebclRealMarzoAcumPor = $ebclRealMarzoAcum/($row['EneroP']+$row['FebreroP']+$row['MarzoP']);				
			}
			$diferenciaMargenDirecto4 =	$row['AbrilP']-$row['AbrilC']-$row['AbrilG'];
			if($row['AbrilP'] == 0)
			{
				$diferenciaMargenDirectoPor4 = 0;
				$ebclRealAbrilPor = 0;
				$ebclRealAbrilAcumPor = 0;
			}
			else
			{
				$diferenciaMargenDirectoPor4 = ($row['AbrilP']-$row['AbrilC']-$row['AbrilG'])/$row['AbrilP']*100;
				$ebclRealAbrilPor = $ebclRealAbril/$row['AbrilP'];
				$ebclRealAbrilAcumPor = $ebclRealAbrilAcum/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']);				
			}
			$diferenciaMargenDirecto5 =	$row['MayoP']-$row['MayoC']-$row['MayoG'];
			if($row['MayoP'] == 0)
			{
				$diferenciaMargenDirectoPor5 = 0;
				$ebclRealMayoPor = 0;
				$ebclRealMayoAcumPor = 0;
			}
			else
			{
				$diferenciaMargenDirectoPor5 = ($row['MayoP']-$row['MayoC']-$row['MayoG'])/$row['MayoP']*100;
				$ebclRealMayoPor = $ebclRealMayo/$row['MayoP'];
				$ebclRealMayoAcumPor = $ebclRealMayoAcum/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']);
			}
			$diferenciaMargenDirecto6 =	$row['JunioP']-$row['JunioC']-$row['JunioG'];
			if($row['JunioP'] == 0)
			{
				$diferenciaMargenDirectoPor6 = 0;
				$ebclRealJunioPor = 0;
				$ebclRealJunioAcumPor = 0;
			}
			else
			{
				$diferenciaMargenDirectoPor6 = ($row['JunioP']-$row['JunioC']-$row['JunioG'])/$row['JunioP']*100;
				$ebclRealJunioPor = $ebclRealJunio/$row['JunioP'];
				$ebclRealJunioAcumPor = $ebclRealJunioAcum/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']);
			}
			$diferenciaMargenDirecto7 =	$row['JulioP']-$row['JulioC']-$row['JulioG'];
			if($row['JulioP'] == 0)
			{
				$diferenciaMargenDirectoPor7 = 0;
				$ebclRealJulioPor = 0;
				$ebclRealJulioAcumPor = 0;
			}
			else
			{
				$diferenciaMargenDirectoPor7 = ($row['JulioP']-$row['JulioC']-$row['JulioG'])/$row['JulioP']*100;
				$ebclRealJulioPor = $ebclRealJulio/$row['JulioP'];
				$ebclRealJulioAcumPor = $ebclRealJulioAcum/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']);
			}
			$diferenciaMargenDirecto8 =	$row['AgostoP']-$row['AgostoC']-$row['AgostoG'];
			if($row['AgostoP'] == 0)
			{
				$diferenciaMargenDirectoPor8 = 0;
				$ebclRealAgostoPor = 0;
				$ebclRealAgostoAcumPor = 0;
			}
			else
			{
				$diferenciaMargenDirectoPor8 = ($row['AgostoP']-$row['AgostoC']-$row['AgostoG'])/$row['AgostoP']*100;
				$ebclRealAgostoPor = $ebclRealAgosto/$row['AgostoP'];
				$ebclRealAgostoAcumPor = $ebclRealAgostoAcum/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']);
			}
			$diferenciaMargenDirecto9 =	$row['SeptiembreP']-$row['SeptiembreC']-$row['SeptiembreG'];
			if($row['SeptiembreP'] == 0)
			{
				$diferenciaMargenDirectoPor9 = 0;
				$ebclRealSeptiembrePor = 0;
				$ebclRealSeptiembreAcumPor = 0;
			}
			else
			{
				$diferenciaMargenDirectoPor9 = ($row['SeptiembreP']-$row['SeptiembreC']-$row['SeptiembreG'])/$row['SeptiembreP'];
				$ebclRealSeptiembrePor = $ebclRealSeptiembre/$row['SeptiembreP'];
				$ebclRealSeptiembreAcumPor = $ebclRealSeptiembreAcum/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']);
			}
			$diferenciaMargenDirecto10 = $row['OctubreP']-$row['OctubreC']-$row['OctubreG'];
			if($row['OctubreP'] == 0)
			{
				$diferenciaMargenDirectoPor10 =	0;
				$ebclRealOctubrePor = 0;
				$ebclRealOctubreAcumPor = 0;
			}
			else
			{
				$diferenciaMargenDirectoPor10 =	($row['OctubreP']-$row['OctubreC']-$row['OctubreG'])/$row['OctubreP']*100;
				$ebclRealSeptiembrePor = $ebclRealOctubre/$row['OctubreP'];
				$ebclRealOctubreAcumPor = $ebclRealOctubreAcum/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP']);
			}
			$diferenciaMargenDirecto11 = $row['NoviembreP']-$row['NoviembreC']-$row['NoviembreG'];
			if($row['NoviembreP'] == 0)
			{
				$diferenciaMargenDirectoPor11 =	0;
				$ebclRealNoviembrePor = 0;
				$ebclRealNoviembreAcumPor = 0;
			}
			else
			{
				$diferenciaMargenDirectoPor11 =	($row['NoviembreP']-$row['NoviembreC']-$row['NoviembreG'])/$row['NoviembreP']*100;
				$ebclRealNoviembrePor = $ebclRealNoviembre/$row['NoviembreP'];
				$ebclRealNoviembreAcumPor = $ebclRealNoviembreAcum/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP']+$row['NoviembreP']);
			}
			$diferenciaMargenDirecto12 = $row['DiciembreP']-$row['DiciembreC']-$row['DiciembreG'];
			if($row['DiciembreP'] == 0)
			{
				$diferenciaMargenDirectoPor12 =	0;
				$ebclRealDiciembrePor = 0;
				$ebclRealDiciembreAcumPor = 0;
			}
			else
			{
				$diferenciaMargenDirectoPor12 =	($row['DiciembreP']-$row['DiciembreC']-$row['DiciembreG'])/$row['DiciembreP']*100;
				$ebclRealDiciembrePor = $ebclRealDiciembre/$row['DiciembreP'];
				$ebclRealDiciembreAcumPor = $ebclRealDiciembreAcum/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP']+$row['NoviembreP']+$row['DiciembreP']);
			}
			$diferenciaMargenDirectoTOTAL = $row['TOTALP']-$row['TOTALC']-$row['TOTALG'];
			if($row['TOTALP'] == 0)
			{
				$diferenciaMargenDirectoPorTOTAL =	0;
				$ebclRealTotalPor = 0;
			}
			else
			{
				$diferenciaMargenDirectoPorTOTAL =	($row['TOTALP']-$row['TOTALC']-$row['TOTALG'])/$row['TOTALP']*100;
				$ebclRealTotalPor = $ebclRealTotal / $row['TOTALP'];
			}
			
			//COMISION PROYECTADA
			if($gaoMensual == 0)
			{
				$gaoMensual = 1;
			}
			

			if($row2['EneroC'] == 0 && $row2['Enero'] == 0)
			{
				$diferenciaComision1 = $comision;
				$ebclEstaEneroPor = 0;
				$ebclEstaEneroAcumPor = $ebclEstaEneroPor;
			}
			else
			{
				$diferenciaComision1 = $row2['Enero']*($comision-(($row2['EneroC']/$row2['Enero'])+($gaoMensual/$row2['Enero'])));
				$ebclEstaEneroPor = $ebclEstaEnero/$row2['Enero'];
				$ebclEstaEneroAcumPor = $ebclEstaEneroPor;
			}
			if($row2['FebreroC'] == 0 && $row2['Febrero'] == 0)
			{
				$diferenciaComision2 = $comision;
				$ebclEstaFebreroPor = 0;
				$ebclEstaFebreroAcumPor = 0;
			}
			else
			{
				$diferenciaComision2 = $row2['Febrero']*($comision-(($row2['FebreroC']/$row2['Febrero'])+($gaoMensual/$row2['Febrero'])));
				$ebclEstaFebreroPor = $ebclEstaFebrero/$row2['Febrero'];
				$ebclEstaFebreroAcumPor = $ebclEstaFebreroAcum/($row2['Enero']+$row2['Febrero']);
			}
			if($row2['MarzoC'] == 0)
			{
				$diferenciaComision3 = $row2['Marzo']*($comision-(($gaoMensual/$row2['Marzo'])));
				$ebclEstaMarzoPor = 0;
				$ebclEstaMarzoAcumPor = 0;
			}
			else
			{
				$diferenciaComision3 = $row2['Marzo']*($comision-(($row2['MarzoC']/$row2['Marzo'])+($gaoMensual/$row2['Marzo'])));
				$ebclEstaMarzoPor = $ebclEstaMarzo/$row2['Marzo'];
				$ebclEstaMarzoAcumPor = $ebclEstaMarzoAcum/($row2['Enero']+$row2['Febrero']+$row2['Marzo']);
			}
			if($row2['AbrilC'] == 0)
			{
				$diferenciaComision4 = $row2['Abril']*($comision-(($gaoMensual/$row2['Abril'])));
				$ebclEstaAbrilPor = 0;
				$ebclEstaAbrilAcumPor = 0;
			}
			else
			{
				$diferenciaComision4 = $row2['Abril']*($comision-(($row2['AbrilC']/$row2['Abril'])+($gaoMensual/$row2['Abril'])));
				$ebclEstaAbrilPor = $ebclEstaAbril/$row2['Abril'];
				$ebclEstaAbrilAcumPor = $ebclEstaAbrilAcum/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']);
			}
			if($row2['MayoC'] == 0)
			{
				$diferenciaComision5 = $row2['Mayo']*($comision-(($gaoMensual/$row2['Mayo'])));
				$ebclEstaMayoPor = $ebclEstaMayo/$row2['Mayo'];
				$ebclEstaMayoAcumPor = 0;
			}
			else
			{
				$diferenciaComision5 = $row2['Mayo']*($comision-(($row2['MayoC']/$row2['Mayo'])+($gaoMensual/$row2['Mayo'])));
				$ebclEstaMayoPor = $ebclEstaMayo/$row2['Mayo'];
				$ebclEstaMayoAcumPor = $ebclEstaMayoAcum/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']);
			}
			if($row2['JunioC'] == 0)
			{
				$diferenciaComision6 = $row2['Junio']*($comision-(($gaoMensual/$row2['Junio'])));
				$ebclEstaJunioPor = $ebclEstaJunio/$row2['Junio'];
				$ebclEstaJunioAcumPor = 0;
			}
			else
			{
				$diferenciaComision6 = $row2['Junio']*($comision-(($row2['JunioC']/$row2['Junio'])+($gaoMensual/$row2['Junio'])));
				$ebclEstaJunioPor = $ebclEstaJunio/$row2['Junio'];
				$ebclEstaJunioAcumPor = $ebclEstaJunioAcum/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']);
			}
			if($row2['JulioC'] == 0)
			{
				$diferenciaComision7 = $row2['Julio']*($comision-(($gaoMensual/$row2['Julio'])));
				$ebclEstaJulioPor = $ebclEstaJulio/$row2['Julio'];
				$ebclEstaJulioAcumPor = 0;
			}
			else
			{
				$diferenciaComision7 = $row2['Julio']*($comision-(($row2['JulioC']/$row2['Julio'])+($gaoMensual/$row2['Julio'])));
				$ebclEstaJulioPor = $ebclEstaJulio/$row2['Julio'];
				$ebclEstaJulioAcumPor = $ebclEstaJulioAcum/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']);
			}
			if($row2['AgostoC'] == 0 && $row2['Agosto'] == 0)
			{
				$diferenciaComision8 = $comision;
				$ebclEstaAgostoPor = 0;
				$ebclEstaAgostoAcumPor = 0;
			}
			else
			{
				$diferenciaComision8 = $row2['Agosto']*($comision-(($row2['AgostoC']/$row2['Agosto'])+($gaoMensual/$row2['Agosto'])));
				$ebclEstaAgostoPor = $ebclEstaAgosto/$row2['Agosto'];
				$ebclEstaAgostoAcumPor = $ebclEstaAgostoAcum/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']);
			}
			if($row2['SeptiembreC'] == 0 && $row2['Septiembre'] == 0)
			{
				$diferenciaComision9 = $comision;
				$ebclEstaSeptiembrePor = 0;
				$ebclEstaSeptiembreAcumPor = 0;
			}
			else
			{
				$diferenciaComision9 = $row2['Septiembre']*($comision-(($row2['SeptiembreC']/$row2['Septiembre'])+($gaoMensual/$row2['Septiembre'])));
				$ebclEstaSeptiembrePor = $ebclEstaSeptiembre/$row2['Septiembre'];
				$ebclEstaSeptiembreAcumPor = $ebclEstaSeptiembreAcum/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre']);
			}
			if($row2['OctubreC'] == 0 && $row2['Octubre'] == 0)
			{
				$diferenciaComision10 = $comision;
				$ebclEstaOctubrePor = 0;
				$ebclEstaOctubreAcumPor = 0;
			}
			else
			{
				$diferenciaComision10 = $row2['Octubre']*($comision-(($row2['OctubreC']/$row2['Octubre'])+($gaoMensual/$row2['Octubre'])));
				$ebclEstaOctubrePor = $ebclEstaOctubre/$row2['Octubre'];
				$ebclEstaOctubreAcumPor = $ebclEstaOctubreAcum/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre']+$row2['Octubre']);
			}
			if($row2['NoviembreC'] == 0 && $row2['Noviembre'] == 0)
			{
				$diferenciaComision11 = $comision;
				$ebclEstaNoviembrePor = 0;
				$ebclEstaNoviembreAcumPor = 0;
			}
			else
			{
				$diferenciaComision11 = $row2['Noviembre']*($comision-(($row2['NoviembreC']/$row2['Noviembre'])+($gaoMensual/$row2['Noviembre'])));
				$ebclEstaNoviembrePor = $ebclEstaNoviembre/$row2['Noviembre'];
				$ebclEstaNoviembreAcumPor = $ebclEstaNoviembreAcum/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre']+$row2['Octubre']+$row2['Noviembre']);
			}
			if($row2['DiciembreC'] == 0 && $row2['Diciembre'] == 0)
			{
				$diferenciaComision12 = $comision;
				$ebclEstaDiciembrePor = 0;
				$ebclEstaDiciembreAcumPor = 0;
			}
			else
			{
				$diferenciaComision12 = $row2['Diciembre']*($comision-(($row2['DiciembreC']/$row2['Diciembre'])+($gaoMensual/$row2['Diciembre'])));
				$ebclEstaDiciembrePor = $ebclEstaDiciembre/$row2['Diciembre'];
				$ebclEstaDiciembreAcumPor = $ebclEstaDiciembreAcum/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre']+$row2['Octubre']+$row2['Noviembre']+$row2['Diciembre']);
			}
			$diferenciaComisionTOTAL = $diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6+$diferenciaComision7+$diferenciaComision8+$diferenciaComision9+$diferenciaComision10+$diferenciaComision11+$diferenciaComision12;
			
			//COMISION REAL
			if($row4['EneroGAO'] == 0 || $row['EneroC'] == 0 || $row['EneroP'] == 0)
			{
				$diferenciaComisionRPor1 = ($comision);
			}
			else
			{
				$diferenciaComisionRPor1 = ($comision-(($row['EneroC']/$row['EneroP'])+($row4['EneroGAO']/$row['EneroP'])));
			}
			$diferenciaComisionR1 = $row['EneroP']*$diferenciaComisionRPor1;
			if($row['FebreroC'] == 0 || $row4['FebreroGAO'] == 0 || $row['FebreroP'] == 0)
			{
				$diferenciaComisionRPor2 = ($comision);
			}
			else
			{
				$diferenciaComisionRPor2 = ($comision-(($row['FebreroC']/$row['FebreroP'])+($row4['FebreroGAO']/$row['FebreroP'])));
			}
			$diferenciaComisionR2 = $row['FebreroP']*$diferenciaComisionRPor2;
			if($row4['MarzoGAO'] == 0 || $row['MarzoC'] == 0 || $row['MarzoP'] == 0)
			{
				$diferenciaComisionRPor3 = ($comision);
			}
			else
			{	
				$diferenciaComisionRPor3 = ($comision-(($row['MarzoC']/$row['MarzoP'])+($row4['MarzoGAO']/$row['MarzoP'])));
			}
			$diferenciaComisionR3 = $row['MarzoP']*$diferenciaComisionRPor3;
			if($row4['AbrilGAO'] == 0 || $row['AbrilC'] == 0 || $row['AbrilP'] == 0)
			{
				$diferenciaComisionRPor4 = ($comision);
			}
			else
			{
				$diferenciaComisionRPor4 = ($comision-(($row['AbrilC']/$row['AbrilP'])+($row4['AbrilGAO']/$row['AbrilP'])));
			}
			$diferenciaComisionR4 = $row['AbrilP']*$diferenciaComisionRPor4;
			if($row4['MayoGAO'] == 0 || $row['MayoC'] == 0 || $row['MayoP'] == 0)
			{
				$diferenciaComisionRPor5 = ($comision);
			}
			else
			{
				$diferenciaComisionRPor5 = ($comision-(($row['MayoC']/$row['MayoP'])+($row4['MayoGAO']/$row['MayoP'])));
			}
			$diferenciaComisionR5 = $row['MayoP']*$diferenciaComisionRPor5;
			if($row4['JunioGAO'] == 0 || $row['JunioC'] == 0 || $row['JunioP'] == 0)
			{
				$diferenciaComisionRPor6 = ($comision);
			}
			else
			{
				$diferenciaComisionRPor6 = ($comision-(($row['JunioC']/$row['JunioP'])+($row4['JunioGAO']/$row['JunioP'])));
			}
			$diferenciaComisionR6 = $row['JunioP']*$diferenciaComisionRPor6;
			if($row4['JulioGAO'] == 0 || $row['JulioC'] == 0 || $row['JulioP'] == 0)
			{
				$diferenciaComisionRPor7 = ($comision);
			}
			else
			{	
				$diferenciaComisionRPor7 = ($comision-(($row['JulioC']/$row['JulioP'])+($row4['JulioGAO']/$row['JulioP'])));
			}
			$diferenciaComisionR7 = $row['JulioP']*$diferenciaComisionRPor7;
			if($row4['AgostoGAO'] == 0 || $row['AgostoC'] == 0 || $row['AgostoP'] == 0)
			{
				$diferenciaComisionRPor8 = ($comision);
			}
			else
			{
				$diferenciaComisionRPor8 = ($comision-(($row['AgostoC']/$row['AgostoP'])+($row4['AgostoGAO']/$row['AgostoP'])));
			}
			$diferenciaComisionR8 = $row['AgostoP']*$diferenciaComisionRPor8;
			if($row4['SeptiembreGAO'] == 0 || $row['SeptiembreC'] == 0 || $row['SeptiembreP'] == 0)
			{
				$diferenciaComisionRPor9 = ($comision);
			}
			else
			{
				$diferenciaComisionRPor9 = ($comision-(($row['SeptiembreC']/$row['SeptiembreP'])+($row4['SeptiembreGAO']/$row['SeptiembreP'])));
			}
			$diferenciaComisionR9 = $row['SeptiembreP']*$diferenciaComisionRPor9;
			if($row4['OctubreGAO'] == 0 || $row['OctubreC'] == 0 || $row['OctubreP'] == 0)
			{
				$diferenciaComisionRPor10 = ($comision);
			}
			else
			{
				$diferenciaComisionRPor10 = ($comision-(($row['OctubreC']/$row['OctubreP'])+($row4['OctubreGAO']/$row['OctubreP'])));
			}
			$diferenciaComisionR10 = $row['OctubreP']*$diferenciaComisionRPor10;
			if($row4['NoviembreGAO'] == 0 || $row['NoviembreC'] == 0 || $row['NoviembreP'] == 0)
			{
				$diferenciaComisionRPor11 = ($comision);
			}
			else
			{
				$diferenciaComisionRPor11 = ($comision-(($row['NoviembreC']/$row['NoviembreP'])+($row4['NoviembreGAO']/$row['NoviembreP'])));
			}
			$diferenciaComisionR11 = $row['NoviembreP']*$diferenciaComisionRPor11;
			if($row4['DiciembreGAO'] == 0 || $row['DiciembreC'] == 0 || $row['DiciembreP'])
			{
				$diferenciaComisionRPor12 = ($comision);
			}
			else
			{
				$diferenciaComisionRPor12 = ($comision-(($row['DiciembreC']/$row['DiciembreP'])+($row4['DiciembreGAO']/$row['DiciembreP'])));
			}
			$diferenciaComisionR12 = $row['DiciembreP']*$diferenciaComisionRPor12;
			$diferenciaComisionRTOTAL = $diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6+$diferenciaComisionR7+$diferenciaComisionR8+$diferenciaComisionR9+$diferenciaComisionR10+$diferenciaComisionR11+$diferenciaComisionR12;
			$comision1 = $diferenciaComisionR1-$row5['Enero'];
			$comision2 = $diferenciaComisionR2-$row5['Febrero'];
			$comision3 = $diferenciaComisionR3-$row5['Marzo'];
			$comision4 = $diferenciaComisionR4-$row5['Abril'];
			$comision5 = $diferenciaComisionR5-$row5['Mayo'];
			$comision6 = $diferenciaComisionR6-$row5['Junio'];
			$comision7 = $diferenciaComisionR7-$row5['Julio'];
			$comision8 = $diferenciaComisionR8-$row5['Agosto'];
			$comision9 = $diferenciaComisionR9-$row5['Septiembre'];
			$comision10 = $diferenciaComisionR10-$row5['Octubre'];
			$comision11 = $diferenciaComisionR11-$row5['Noviembre'];
			$comision12 = $diferenciaComisionR12-$row5['Diciembre'];
			$comisionTOTAL = $diferenciaComisionRTOTAL-($row5['TotalAnticipo']);
			if($row['EneroP'] == 0)
			{
				$comisionPor1 = 0;
			}
			else
			{
				$comisionPor1 = $comision1/$row['EneroP'];
			}
			if($row['FebreroP'] == 0)
			{
				$comisionPor2 = 0;
			}
			else
			{
				$comisionPor2 = $comision2/$row['FebreroP'];
			}
			if($row['MarzoP'] == 0)
			{
				$comisionPor3 = 0;
			}
			else
			{				
				$comisionPor3 = $comision3/$row['MarzoP'];
			}
			if($row['AbrilP'] == 0)
			{
				$comisionPor4 = 0;
			}
			else
			{
				$comisionPor4 = $comision4/$row['AbrilP'];
			}
			if($row['MayoP'] == 0)
			{
				$comisionPor5 = 0;
			}
			else
			{
				$comisionPor5 = $comision5/$row['MayoP'];
			}
			if($row['JunioP'] == 0)
			{
				$comisionPor6 = 0;
			}
			else
			{
				$comisionPor6 = $comision6/$row['JunioP'];
			}
			if($row['JulioP'] == 0)
			{
				$comisionPor7 = 0;
			}
			else
			{
				$comisionPor7 = $comision7/$row['JulioP'];
			}
			if($row['AgostoP'] == 0)
			{
				$comisionPor8 = 0;
			}
			else
			{
				$comisionPor8 = $comision8/$row['AgostoP'];
			}
			if($row['SeptiembreP'] == 0)
			{
				$comisionPor9 = 0;
			}
			else
			{
				$comisionPor9 = $comision9/$row['SeptiembreP'];
			}
			if($row['OctubreP'] == 0)
			{
				$comisionPor10 = 0;
			}
			else
			{
				$comisionPor10 = $comision10/$row['OctubreP'];
			}
			if($row['NoviembreP'] == 0)
			{
				$comisionPor11 = 0;
			}
			else
			{
				$comisionPor11 = $comision11/$row['NoviembreP'];
			}
			if($row['DiciembreP'] == 0)
			{
				$comisionPor12 = 0;
			}
			else
			{
				$comisionPor12 = $comision12/$row['DiciembreP'];
			}
			if($row['TOTALP'] == 0)
			{
				$comisionPorTOTAL = 0;
			}
			else
			{
				$comisionPorTOTAL = $comisionTOTAL/($row['TOTALP']);
			}
			
			//MARGEN AJUSTADO
			//PROYECTADO
			$margenAjustado1P = $row2['Enero'] - $row2['EneroC'] - $gaoMensual - $diferenciaComision1;
			$margenAjustado2P = $row2['Febrero'] - $row2['FebreroC'] - $gaoMensual - $diferenciaComision2;
			$margenAjustado3P = $row2['Marzo'] - $row2['MarzoC'] - $gaoMensual - $diferenciaComision3;
			$margenAjustado4P = $row2['Abril'] - $row2['AbrilC'] - $gaoMensual - $diferenciaComision4;
			$margenAjustado5P = $row2['Mayo'] - $row2['MayoC'] - $gaoMensual - $diferenciaComision5;
			$margenAjustado6P = $row2['Junio'] - $row2['JunioC'] - $gaoMensual - $diferenciaComision6;
			$margenAjustado7P = $row2['Julio'] - $row2['JulioC'] - $gaoMensual - $diferenciaComision7;
			$margenAjustado8P = $row2['Agosto'] - $row2['AgostoC'] - $gaoMensual - $diferenciaComision8;
			$margenAjustado9P = $row2['Septiembre'] - $row2['SeptiembreC'] - $gaoMensual - $diferenciaComision9;
			$margenAjustado10P = $row2['Octubre'] - $row2['OctubreC'] - $gaoMensual - $diferenciaComision10;
			$margenAjustado11P = $row2['Noviembre'] - $row2['NoviembreC'] - $gaoMensual - $diferenciaComision11;
			$margenAjustado12P = $row2['Diciembre'] - $row2['DiciembreC'] - $gaoMensual - $diferenciaComision12;
			$margenAjustadoTotalP = $row2['TOTAL'] - $row2['TOTALC'] - $row3['TotalGAO'] - ($row2['TOTAL']*$comision_por_p);
			//ACUMULADA
			$margenAjustado1PA = $row2['Enero'] - $row2['EneroC'] - $gaoMensual - $diferenciaComision1;
			$margenAjustado2PA = $margenAjustado1PA + $row2['Febrero'] - $row2['FebreroC'] - $gaoMensual - $diferenciaComision2;
			$margenAjustado3PA = $margenAjustado2PA + $row2['Marzo'] - $row2['MarzoC'] - $gaoMensual - $diferenciaComision3;
			$margenAjustado4PA = $margenAjustado3PA + $row2['Abril'] - $row2['AbrilC'] - $gaoMensual - $diferenciaComision4;
			$margenAjustado5PA = $margenAjustado4PA + $row2['Mayo'] - $row2['MayoC'] - $gaoMensual - $diferenciaComision5;
			$margenAjustado6PA = $margenAjustado5PA + $row2['Junio'] - $row2['JunioC'] - $gaoMensual - $diferenciaComision6;
			$margenAjustado7PA = $margenAjustado6PA + $row2['Julio'] - $row2['JulioC'] - $gaoMensual - $diferenciaComision7;
			$margenAjustado8PA = $margenAjustado7PA + $row2['Agosto'] - $row2['AgostoC'] - $gaoMensual - $diferenciaComision8;
			$margenAjustado9PA = $margenAjustado8PA + $row2['Septiembre'] - $row2['SeptiembreC'] - $gaoMensual - $diferenciaComision9;
			$margenAjustado10PA = $margenAjustado9PA + $row2['Octubre'] - $row2['OctubreC'] - $gaoMensual - $diferenciaComision10;
			$margenAjustado11PA = $margenAjustado10PA + $row2['Noviembre'] - $row2['NoviembreC'] - $gaoMensual - $diferenciaComision11;
			$margenAjustado12PA = $margenAjustado11PA + $row2['Diciembre'] - $row2['DiciembreC'] - $gaoMensual - $diferenciaComision12;
			//REAL
			$margenAjustado1R = $row['EneroP'] - $row['EneroC'] - $row['EneroG'] - $row4['EneroGAO'] - $comision1;
			$margenAjustado2R = $row['FebreroP'] - $row['FebreroC'] - $row['FebreroG'] - $row4['FebreroGAO'] - $comision2;
			$margenAjustado3R = $row['MarzoP'] - $row['MarzoC'] - $row['MarzoG'] - $row4['MarzoGAO'] - $comision3;
			$margenAjustado4R = $row['AbrilP'] - $row['AbrilC'] - $row['AbrilG'] - $row4['AbrilGAO'] - $comision4;
			$margenAjustado5R = $row['MayoP'] - $row['MayoC'] - $row['MayoG'] - $row4['MayoGAO'] - $comision5;
			$margenAjustado6R = $row['JunioP'] - $row['JunioC'] - $row['JunioG'] - $row4['JunioGAO'] - $comision6;
			$margenAjustado7R = $row['JulioP'] - $row['JulioC'] - $row['JulioG'] - $row4['JulioGAO'] - $comision7;
			$margenAjustado8R = $row['AgostoP'] - $row['AgostoC'] - $row['AgostoG'] - $row4['AgostoGAO'] - $comision8;
			$margenAjustado9R = $row['SeptiembreP'] - $row['SeptiembreC'] - $row['SeptiembreG'] - $row4['SeptiembreGAO'] - $comision9;
			$margenAjustado10R = $row['OctubreP'] - $row['OctubreC'] - $row['OctubreG'] - $row4['OctubreGAO'] - $comision10;
			$margenAjustado11R = $row['NoviembreP'] - $row['NoviembreC'] - $row['NoviembreG'] - $row4['NoviembreGAO'] - $comision11;
			$margenAjustado12R = $row['DiciembreP'] - $row['DiciembreC'] - $row['DiciembreG'] - $row4['DiciembreGAO'] - $comision12;
			$margenAjustadoTotalR = $row['TOTALP'] - $row['TOTALC'] - $row['TOTALG'] - $row4['TotalGAO'] - $comisionTOTAL;
			//ACUMULADA
			$margenAjustado1RA = $row['EneroP'] - $row['EneroC'] - $row['EneroG'] - $row4['EneroGAO'] - $comision1;
			$margenAjustado2RA = $margenAjustado1RA + $row['FebreroP'] - $row['FebreroC'] - $row['FebreroG'] - $row4['FebreroGAO'] - $comision2;
			$margenAjustado3RA = $margenAjustado2RA + $row['MarzoP'] - $row['MarzoC'] - $row['MarzoG'] - $row4['MarzoGAO'] - $comision3;
			$margenAjustado4RA = $margenAjustado3RA + $row['AbrilP'] - $row['AbrilC'] - $row['AbrilG'] - $row4['AbrilGAO'] - $comision4;
			$margenAjustado5RA = $margenAjustado4RA + $row['MayoP'] - $row['MayoC'] - $row['MayoG'] - $row4['MayoGAO'] - $comision5;
			$margenAjustado6RA = $margenAjustado5RA + $row['JunioP'] - $row['JunioC'] - $row['JunioG'] - $row4['JunioGAO'] - $comision6;
			$margenAjustado7RA = $margenAjustado6RA + $row['JulioP'] - $row['JulioC'] - $row['JulioG'] - $row4['JulioGAO'] - $comision7;
			$margenAjustado8RA = $margenAjustado7RA + $row['AgostoP'] - $row['AgostoC'] - $row['AgostoG'] - $row4['AgostoGAO'] - $comision8;
			$margenAjustado9RA = $margenAjustado8RA + $row['SeptiembreP'] - $row['SeptiembreC'] - $row['SeptiembreG'] - $row4['SeptiembreGAO'] - $comision9;
			$margenAjustado10RA = $margenAjustado9RA + $row['OctubreP'] - $row['OctubreC'] - $row['OctubreG'] - $row4['OctubreGAO'] - $comision10;
			$margenAjustado11RA = $margenAjustado10RA + $row['NoviembreP'] - $row['NoviembreC'] - $row['NoviembreG'] - $row4['NoviembreGAO'] - $comision11;
			$margenAjustado12RA = $margenAjustado11RA + $row['DiciembreP'] - $row['DiciembreC'] - $row['DiciembreG'] - $row4['DiciembreGAO'] - $comision12;
			if($row['EneroP'] == 0)
			{
				$margenAjustadoPor1R = 0;
			}
			else
			{
				$margenAjustadoPor1R = $margenAjustado1R/$row['EneroP'];
			}
			if($row['FebreroP'] == 0)
			{
				$margenAjustadoPor2R = 0;
			}
			else
			{
				$margenAjustadoPor2R = $margenAjustado2R/$row['FebreroP'];
			}
			if($row['MarzoP'] == 0)
			{
				$margenAjustadoPor3R = 0;
			}
			else
			{
				$margenAjustadoPor3R = $margenAjustado3R/$row['MarzoP'];
			}
			if($row['AbrilP'] == 0)
			{
				$margenAjustadoPor4R = 0;
			}
			else
			{
				$margenAjustadoPor4R = $margenAjustado4R/$row['AbrilP'];
			}
			if($row['MayoP'] == 0)
			{
				$margenAjustadoPor5R = 0;
			}
			else
			{
				$margenAjustadoPor5R = $margenAjustado5R/$row['MayoP'];
			}
			if($row['JunioP'] == 0)
			{
				$margenAjustadoPor6R = 0;
			}
			else
			{
				$margenAjustadoPor6R = $margenAjustado6R/$row['JunioP'];
			}
			if($row['JulioP'] == 0)
			{
				$margenAjustadoPor7R = 0;
			}
			else
			{
				$margenAjustadoPor7R = $margenAjustado7R/$row['JulioP'];
			}
			if($row['AgostoP'] == 0)
			{
				$margenAjustadoPor8R = 0;
			}
			else
			{
				$margenAjustadoPor8R = $margenAjustado8R/$row['AgostoP'];
			}
			if($row['SeptiembreP'] == 0)
			{
				$margenAjustadoPor9R = 0;
			}
			else
			{
				$margenAjustadoPor9R = $margenAjustado9R/$row['SeptiembreP'];
			}
			if($row['OctubreP'] == 0)
			{
				$margenAjustadoPor10R = 0;
			}
			else
			{
				$margenAjustadoPor10R = $margenAjustado10R/$row['OctubreP'];
			}
			if($row['NoviembreP'] == 0)
			{
				$margenAjustadoPor11R = 0;
			}
			else
			{
				$margenAjustadoPor11R = $margenAjustado11R/$row['NoviembreP'];
			}
			if($row['DiciembreP'] == 0)
			{
				$margenAjustadoPor12R = 0;
			}
			else
			{
				$margenAjustadoPor12R = $margenAjustado12R/$row['DiciembreP'];
			}
			if($row['TOTALP'] == 0)
			{
				$margenAjustadoTotalPorR = 0;
			}
			else
			{
				$margenAjustadoTotalPorR = $margenAjustadoTotalR / $row['TOTALP'];
			}
			//DIFERENCIA
			$margenAjustado1D = $margenAjustado1P - $margenAjustado1R;
			$margenAjustado2D = $margenAjustado2P - $margenAjustado2R;
			$margenAjustado3D = $margenAjustado3P - $margenAjustado3R;
			$margenAjustado4D = $margenAjustado4P - $margenAjustado4R;
			$margenAjustado5D = $margenAjustado5P - $margenAjustado5R;
			$margenAjustado6D = $margenAjustado6P - $margenAjustado6R;
			$margenAjustado7D = $margenAjustado7P - $margenAjustado7R;
			$margenAjustado8D = $margenAjustado8P - $margenAjustado8R;
			$margenAjustado9D = $margenAjustado9P - $margenAjustado9R;
			$margenAjustado10D = $margenAjustado10P - $margenAjustado10R;
			$margenAjustado11D = $margenAjustado11P - $margenAjustado11R;
			$margenAjustado12D = $margenAjustado12P - $margenAjustado12R;
			$margenAjustadoTotalD = $margenAjustadoTotalP - $margenAjustadoTotalR;
			//AJUSTE - REAL
			$margenAjustado1A = $margenAjustado1P - $margenAjustado1R;
			$margenAjustado2A = ($margenAjustado2P + $margenAjustado1A) - $margenAjustado2R;
			$margenAjustado3A = ($margenAjustado3P + $margenAjustado2A) - $margenAjustado3R;
			$margenAjustado4A = ($margenAjustado4P + $margenAjustado3A) - $margenAjustado4R;
			$margenAjustado5A = ($margenAjustado5P + $margenAjustado4A) - $margenAjustado5R;
			$margenAjustado6A = ($margenAjustado6P + $margenAjustado5A) - $margenAjustado6R;
			$margenAjustado7A = ($margenAjustado7P + $margenAjustado6A) - $margenAjustado7R;
			$margenAjustado8A = ($margenAjustado8P + $margenAjustado7A) - $margenAjustado8R;
			$margenAjustado9A = ($margenAjustado9P + $margenAjustado8A) - $margenAjustado9R;
			$margenAjustado10A = ($margenAjustado10P + $margenAjustado9A) - $margenAjustado10R;
			$margenAjustado11A = ($margenAjustado11P + $margenAjustado10A) - $margenAjustado11R;
			$margenAjustado12A = ($margenAjustado12P + $margenAjustado11A) - $margenAjustado12R;
			//AJUSTE - REAL
			$margenAjustado1B = $margenAjustado1P - $margenAjustado1R;
			$margenAjustado2B = ($margenAjustado2P + $margenAjustado1A) - $margenAjustado2R;
			$margenAjustado3B = ($margenAjustado3P + $margenAjustado2A) - $margenAjustado3R;
			$margenAjustado4B = ($margenAjustado4P + $margenAjustado3A) - $margenAjustado4R;
			$margenAjustado5B = ($margenAjustado5P + $margenAjustado4A) - $margenAjustado5R;
			$margenAjustado6B = ($margenAjustado6P + $margenAjustado5A) - $margenAjustado6R;
			$margenAjustado7B = ($margenAjustado7P + $margenAjustado6A) - $margenAjustado7R;
			$margenAjustado8B = ($margenAjustado8P + $margenAjustado7A) - $margenAjustado8R;
			$margenAjustado9B = ($margenAjustado9P + $margenAjustado8A) - $margenAjustado9R;
			$margenAjustado10B = ($margenAjustado10P + $margenAjustado9A) - $margenAjustado10R;
			$margenAjustado11B = ($margenAjustado11P + $margenAjustado10A) - $margenAjustado11R;
			$margenAjustado12B = ($margenAjustado12P + $margenAjustado11A) - $margenAjustado12R;
			
			
			//INTERESES
			$intereses1R = $row6['Enero'] - $row['EneroC'] - $row['EneroG'] - $row4['EneroGAO'] - $comision1;
			$intereses2R = $row6['Febrero'] - $row['FebreroC'] - $row['FebreroG'] - $row4['FebreroGAO'] - $comision2;
			$intereses3R = $row6['Marzo'] - $row['MarzoC'] - $row['MarzoG'] - $row4['MarzoGAO'] - $comision3;
			$intereses4R = $row6['Abril'] - $row['AbrilC'] - $row['AbrilG'] - $row4['AbrilGAO'] - $comision4;
			$intereses5R = $row6['Mayo'] - $row['MayoC'] - $row['MayoG'] - $row4['MayoGAO'] - $comision5;
			$intereses6R = $row6['Junio'] - $row['JunioC'] - $row['JunioG'] - $row4['JunioGAO'] - $comision6;
			$intereses7R = $row6['Julio'] - $row['JulioC'] - $row['JulioG'] - $row4['JulioGAO'] - $comision7;
			$intereses8R = $row6['Agosto'] - $row['AgostoC'] - $row['AgostoG'] - $row4['AgostoGAO'] - $comision8;
			$intereses9R = $row6['Septiembre'] - $row['SeptiembreC'] - $row['SeptiembreG'] - $row4['SeptiembreGAO'] - $comision9;
			$intereses10R = $row6['Octubre'] - $row['OctubreC'] - $row['OctubreG'] - $row4['OctubreGAO'] - $comision10;
			$intereses11R = $row6['Noviembre'] - $row['NoviembreC'] - $row['NoviembreG'] - $row4['NoviembreGAO'] - $comision11;
			$intereses12R = $row6['Diciembre'] - $row['DiciembreC'] - $row['DiciembreG'] - $row4['DiciembreGAO'] - $comision12;
			
			$intereses1A = $intereses1R;
			$intereses2A = $intereses1A + $row6['Febrero'] - $row['FebreroC'] - $row['FebreroG'] - $row4['FebreroGAO'] - $comision2;
			$intereses3A = $intereses2A + $row6['Marzo'] - $row['MarzoC'] - $row['MarzoG'] - $row4['MarzoGAO'] - $comision3;
			$intereses4A = $intereses3A + $row6['Abril'] - $row['AbrilC'] - $row['AbrilG'] - $row4['AbrilGAO'] - $comision4;
			$intereses5A = $intereses4A + $row6['Mayo'] - $row['MayoC'] - $row['MayoG'] - $row4['MayoGAO'] - $comision5;
			$intereses6A = $intereses5A + $row6['Junio'] - $row['JunioC'] - $row['JunioG'] - $row4['JunioGAO'] - $comision6;
			$intereses7A = $intereses6A + $row6['Julio'] - $row['JulioC'] - $row['JulioG'] - $row4['JulioGAO'] - $comision7;
			$intereses8A = $intereses7A + $row6['Agosto'] - $row['AgostoC'] - $row['AgostoG'] - $row4['AgostoGAO'] - $comision8;
			$intereses9A = $intereses8A + $row6['Septiembre'] - $row['SeptiembreC'] - $row['SeptiembreG'] - $row4['SeptiembreGAO'] - $comision9;
			$intereses10A = $intereses9A + $row6['Octubre'] - $row['OctubreC'] - $row['OctubreG'] - $row4['OctubreGAO'] - $comision10;
			$intereses11A = $intereses10A + $row6['Noviembre'] - $row['NoviembreC'] - $row['NoviembreG'] - $row4['NoviembreGAO'] - $comision11;
			$intereses12A = $intereses11A + $row6['Diciembre'] - $row['DiciembreC'] - $row['DiciembreG'] - $row4['DiciembreGAO'] - $comision12;
			
			if($intereses1R > 0)
			{
				$intereses1R = 0;
			}
			else
			{
				$intereses1R = $intereses1R * 0.02 *-1;
			}
			if($intereses2R > 0)
			{
				$intereses2R = 0;
			}
			else
			{
				$intereses2R = $intereses2R * 0.02 *-1;
			}
			if($intereses3R > 0)
			{
				$intereses3R = 0;
			}
			else
			{
				$intereses3R = $intereses3R * 0.02 *-1;
			}
			if($intereses4R > 0)
			{
				$intereses4R = 0;
			}
			else
			{
				$intereses4R = $intereses4R * 0.02 *-1;
			}
			if($intereses5R > 0)
			{
				$intereses5R = 0;
			}
			else
			{
				$intereses5R = $intereses5R * 0.02 *-1;
			}
			if($intereses6R > 0)
			{
				$intereses6R = 0;
			}
			else
			{
				$intereses6R = $intereses6R * 0.02 *-1;
			}
			if($intereses7R > 0)
			{
				$intereses7R = 0;
			}
			else
			{
				$intereses7R = $intereses7R * 0.02 *-1;
			}
			if($intereses8R > 0)
			{
				$intereses8R = 0;
			}
			else
			{
				$intereses8R = $intereses8R * 0.02 *-1;
			}
			if($intereses9R > 0)
			{
				$intereses9R = 0;
			}
			else
			{
				$intereses9R = $intereses9R * 0.02 *-1;
			}
			if($intereses10R > 0)
			{
				$intereses10R = 0;
			}
			else
			{
				$intereses10R = $intereses10R * 0.02 *-1;
			}
			if($intereses11R > 0)
			{
				$intereses11R = 0;
			}
			else
			{
				$intereses11R = $intereses11R * 0.02 *-1;
			}
			if($intereses12R > 0)
			{
				$intereses12R = 0;
			}
			else
			{
				$intereses12R = $intereses12R * 0.02 *-1;
			}
			
			$diferencia1Intereses = $intereses1R-$interesesMensual;
			$diferencia2Intereses = $intereses2R-$interesesMensual;
			$diferencia3Intereses = $intereses3R-$interesesMensual;
			$diferencia4Intereses = $intereses4R-$interesesMensual;
			$diferencia5Intereses = $intereses5R-$interesesMensual;
			$diferencia6Intereses = $intereses6R-$interesesMensual;
			$diferencia7Intereses = $intereses7R-$interesesMensual;
			$diferencia8Intereses = $intereses8R-$interesesMensual;
			$diferencia9Intereses = $intereses9R-$interesesMensual;
			$diferencia10Intereses = $intereses10R-$interesesMensual;
			$diferencia11Intereses = $intereses11R-$interesesMensual;
			$diferencia12Intereses = $intereses12R-$interesesMensual;
			$eneroIntereses = $intereses1R-$interesesMensual;
			$febreroIntereses = $eneroIntereses+$diferencia2Intereses;
			$marzoIntereses = $febreroIntereses+$diferencia3Intereses;
			$abrilIntereses = $marzoIntereses+$diferencia4Intereses;
			$mayoIntereses = $abrilIntereses+$diferencia5Intereses;
			$junioIntereses = $mayoIntereses+$diferencia6Intereses;
			$julioIntereses = $junioIntereses+$diferencia7Intereses;
			$agostoIntereses = $julioIntereses+$diferencia8Intereses;
			$septiembreIntereses = $agostoIntereses+$diferencia9Intereses;
			$octubreIntereses = $septiembreIntereses+$diferencia10Intereses;
			$noviembreIntereses = $octubreIntereses+$diferencia11Intereses;
			$diciembreIntereses = $noviembreIntereses+$diferencia12Intereses;
			
			//GAO ACUMULADO MAS INTERESES
			$eneroGAO = $row4['EneroGAO']-$gaoMensual;
			$febreroGAO = $eneroGAO+($row4['FebreroGAO']-$gaoMensual);
			$marzoGAO = $febreroGAO+($row4['MarzoGAO']-$gaoMensual);
			$abrilGAO = $marzoGAO+($row4['AbrilGAO']-$gaoMensual+$intereses4R);
			$mayoGAO = $abrilGAO+($row4['MayoGAO']-$gaoMensual+$intereses5R);
			$junioGAO = $mayoGAO+($row4['JunioGAO']-$gaoMensual+$intereses6R);
			$julioGAO = $junioGAO+($row4['JulioGAO']-$gaoMensual+$intereses7R);
			$agostoGAO = $julioGAO+($row4['AgostoGAO']-$gaoMensual+$intereses8R);
			$septiembreGAO = $agostoGAO+($row4['SeptiembreGAO']-$gaoMensual+$intereses9R);
			$octubreGAO = $septiembreGAO+($row4['OctubreGAO']-$gaoMensual+$intereses10R);
			$noviembreGAO = $octubreGAO+($row4['NoviembreGAO']-$gaoMensual+$intereses11R);
			$diciembreGAO = $noviembreGAO+($row4['DiciembreGAO']-$gaoMensual+$intereses12R);
			
			//EBCL REAL MAS INTERESES
			$ebclRealEnero = $row['EneroC'] + $row4['EneroGAO'];
			$ebclRealFebrero = $row['FebreroC'] + $row4['FebreroGAO'];
			$ebclRealMarzo = $row['MarzoC'] + $row4['MarzoGAO'];
			$ebclRealAbril = $row['AbrilC'] + $row4['AbrilGAO'] + $intereses4R;
			$ebclRealMayo = $row['MayoC'] + $row4['MayoGAO'] + $intereses5R;
			$ebclRealJunio = $row['JunioC'] + $row4['JunioGAO'] + $intereses6R;
			$ebclRealJulio = $row['JulioC'] + $row4['JulioGAO'] + $intereses7R;
			$ebclRealAgosto = $row['AgostoC'] + $row4['AgostoGAO'] + $intereses8R;
			$ebclRealSeptiembre = $row['SeptiembreC'] + $row4['SeptiembreGAO'] + $intereses9R;
			$ebclRealOctubre = $row['OctubreC'] + $row4['OctubreGAO'] + $intereses10R;
			$ebclRealNoviembre = $row['NoviembreC'] + $row4['NoviembreGAO'] + $intereses11R;
			$ebclRealDiciembre = $row['DiciembreC'] + $row4['DiciembreGAO'] + $intereses12R;
			$ebclRealTotal = $row['TOTALC'] + $row4['TotalGAO'];
			
			$ebclRealEneroAcum = $ebclRealEnero;
			$ebclRealFebreroAcum = $ebclRealEneroAcum + $ebclRealFebrero;
			$ebclRealMarzoAcum = $ebclRealFebreroAcum + $row['MarzoC'] + $row4['MarzoGAO'];
			$ebclRealAbrilAcum = $ebclRealMarzoAcum + $row['AbrilC'] + $row4['AbrilGAO'] + $intereses4R;
			$ebclRealMayoAcum = $ebclRealAbrilAcum + $row['MayoC'] + $row4['MayoGAO'] + $intereses5R;
			$ebclRealJunioAcum = $ebclRealMayoAcum +$row['JunioC'] + $row4['JunioGAO'] + $intereses6R;
			$ebclRealJulioAcum = $ebclRealJunioAcum + $row['JulioC'] + $row4['JulioGAO'] + $intereses7R;
			$ebclRealAgostoAcum = $ebclRealJulioAcum + $row['AgostoC'] + $row4['AgostoGAO'] + $intereses8R;
			$ebclRealSeptiembreAcum = $ebclRealAgostoAcum + $row['SeptiembreC'] + $row4['SeptiembreGAO'] + $intereses9R;
			$ebclRealOctubreAcum = $ebclRealSeptiembreAcum + $row['OctubreC'] + $row4['OctubreGAO'] + $intereses10R;
			$ebclRealNoviembreAcum = $ebclRealOctubreAcum + $row['NoviembreC'] + $row4['NoviembreGAO'] + $intereses11R;
			$ebclRealDiciembreAcum = $ebclRealNoviembreAcum + $row['DiciembreC'] + $row4['DiciembreGAO'] + $intereses12R;
			
			$ebclEneroAcumT = $ebclRealEneroAcum-$ebclEstaEneroAcum;
			$ebclFebreroAcumT = $ebclRealFebreroAcum - $ebclEstaFebreroAcum;
			$ebclMarzoAcumT = $ebclRealMarzoAcum - $ebclEstaMarzoAcum;
			$ebclAbrilAcumT = $ebclRealAbrilAcum - $ebclEstaAbrilAcum;
			$ebclMayoAcumT = $ebclRealMayoAcum - $ebclEstaMayoAcum;
			$ebclJunioAcumT = $ebclRealJunioAcum - $ebclEstaJunioAcum;
			$ebclJulioAcumT = $ebclRealJulioAcum - $ebclEstaJulioAcum;
			$ebclAgostoAcumT = $ebclRealAgostoAcum - $ebclEstaAgostoAcum;
			$ebclSeptiembreAcumT = $ebclRealSeptiembreAcum - $ebclEstaSeptiembreAcum;
			$ebclOctubreAcumT = $ebclRealOctubreAcum - $ebclEstaOctubreAcum;
			$ebclNoviembreAcumT = $ebclRealNoviembreAcum - $ebclEstaNoviembreAcum;
			$ebclDiciembreAcumT = $ebclRealDiciembreAcum - $ebclEstaDiciembreAcum;
			
			//MARGEN AJUSTADO MAS INTERESES
			$margenAjustado1R = $row['EneroP'] - $row['EneroC'] - $row['EneroG'] - $row4['EneroGAO'] - $comision1;
			$margenAjustado2R = $row['FebreroP'] - $row['FebreroC'] - $row['FebreroG'] - $row4['FebreroGAO'] - $comision2;
			$margenAjustado3R = $row['MarzoP'] - $row['MarzoC'] - $row['MarzoG'] - $row4['MarzoGAO'] - $comision3;
			$margenAjustado4R = $row['AbrilP'] - $row['AbrilC'] - $row['AbrilG'] - $row4['AbrilGAO'] - $comision4 - $intereses4R;
			$margenAjustado5R = $row['MayoP'] - $row['MayoC'] - $row['MayoG'] - $row4['MayoGAO'] - $comision5 - $intereses5R;
			$margenAjustado6R = $row['JunioP'] - $row['JunioC'] - $row['JunioG'] - $row4['JunioGAO'] - $comision6 - $intereses6R;
			$margenAjustado7R = $row['JulioP'] - $row['JulioC'] - $row['JulioG'] - $row4['JulioGAO'] - $comision7 - $intereses7R;
			$margenAjustado8R = $row['AgostoP'] - $row['AgostoC'] - $row['AgostoG'] - $row4['AgostoGAO'] - $comision8 - $intereses8R;
			$margenAjustado9R = $row['SeptiembreP'] - $row['SeptiembreC'] - $row['SeptiembreG'] - $row4['SeptiembreGAO'] - $comision9 - $intereses9R;
			$margenAjustado10R = $row['OctubreP'] - $row['OctubreC'] - $row['OctubreG'] - $row4['OctubreGAO'] - $comision10 - $intereses10R;
			$margenAjustado11R = $row['NoviembreP'] - $row['NoviembreC'] - $row['NoviembreG'] - $row4['NoviembreGAO'] - $comision11 - $intereses11R;
			$margenAjustado12R = $row['DiciembreP'] - $row['DiciembreC'] - $row['DiciembreG'] - $row4['DiciembreGAO'] - $comision12 - $intereses12R;
			$margenAjustadoTotalR = $row['TOTALP'] - $row['TOTALC'] - $row['TOTALG'] - $row4['TotalGAO'] - $comisionTOTAL;
			//ACUMULADA
			$margenAjustado1RA = $row['EneroP'] - $row['EneroC'] - $row['EneroG'] - $row4['EneroGAO'] - $comision1;
			$margenAjustado2RA = $margenAjustado1RA + $row['FebreroP'] - $row['FebreroC'] - $row['FebreroG'] - $row4['FebreroGAO'] - $comision2;
			$margenAjustado3RA = $margenAjustado2RA + $row['MarzoP'] - $row['MarzoC'] - $row['MarzoG'] - $row4['MarzoGAO'] - $comision3;
			$margenAjustado4RA = $margenAjustado3RA + $row['AbrilP'] - $row['AbrilC'] - $row['AbrilG'] - $row4['AbrilGAO'] - $comision4 - $intereses4R;
			$margenAjustado5RA = $margenAjustado4RA + $row['MayoP'] - $row['MayoC'] - $row['MayoG'] - $row4['MayoGAO'] - $comision5 - $intereses5R;
			$margenAjustado6RA = $margenAjustado5RA + $row['JunioP'] - $row['JunioC'] - $row['JunioG'] - $row4['JunioGAO'] - $comision6 - $intereses6R;
			$margenAjustado7RA = $margenAjustado6RA + $row['JulioP'] - $row['JulioC'] - $row['JulioG'] - $row4['JulioGAO'] - $comision7 - $intereses7R;
			$margenAjustado8RA = $margenAjustado7RA + $row['AgostoP'] - $row['AgostoC'] - $row['AgostoG'] - $row4['AgostoGAO'] - $comision8 - $intereses8R;
			$margenAjustado9RA = $margenAjustado8RA + $row['SeptiembreP'] - $row['SeptiembreC'] - $row['SeptiembreG'] - $row4['SeptiembreGAO'] - $comision9 - $intereses9R;
			$margenAjustado10RA = $margenAjustado9RA + $row['OctubreP'] - $row['OctubreC'] - $row['OctubreG'] - $row4['OctubreGAO'] - $comision10 - $intereses10R;
			$margenAjustado11RA = $margenAjustado10RA + $row['NoviembreP'] - $row['NoviembreC'] - $row['NoviembreG'] - $row4['NoviembreGAO'] - $comision11 - $intereses11R;
			$margenAjustado12RA = $margenAjustado11RA + $row['DiciembreP'] - $row['DiciembreC'] - $row['DiciembreG'] - $row4['DiciembreGAO'] - $comision12 - $intereses12R;
			?>
				<!--INGRESOS-->
				<tr>
					<th colspan="2">Ingresos</th>
					<td>$<?php print(number_format($row2['TOTAL'],2));?></td>
					<td>100%</td>
					<td></td>
					<td>$<?php print(number_format($row2['Enero'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['EneroP'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['EneroP']-$row2['Enero'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Febrero'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['FebreroP'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['FebreroP']-$row2['Febrero'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Enero']+$row2['Febrero'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row['EneroP']+$row['FebreroP'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($febrero*-1,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Marzo'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['MarzoP'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['MarzoP']-$row2['Marzo'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Enero']+$row2['Febrero']+$row2['Marzo'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row['EneroP']+$row['FebreroP']+$row['MarzoP'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($marzo*-1,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Abril'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['AbrilP'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['AbrilP']-$row2['Abril'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($abril,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Mayo'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['MayoP'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['MayoP']-$row2['Mayo'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($mayo,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Junio'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['JunioP'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['JunioP']-$row2['Junio'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($junio,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Julio'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['JulioP'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['JulioP']-$row2['Julio'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($julio,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Agosto'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['AgostoP'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['AgostoP']-$row2['Agosto'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($agosto,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Septiembre'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['SeptiembreP'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['SeptiembreP']-$row2['Septiembre'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($septiembre,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Octubre'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['OctubreP'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['OctubreP']-$row2['Octubre'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre']+$row2['Octubre'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($octubre,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Noviembre'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['NoviembreP'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['NoviembreP']-$row2['Noviembre'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre']+$row2['Octubre']+$row2['Noviembre'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP']+$row['NoviembreP'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($noviembre,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Diciembre'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['DiciembreP'],2));?></td>
					<td>100%</td>
					<td>$<?php print(number_format($row['DiciembreP']-$row2['Diciembre'],2));?></td>
				</tr>
				<!--COSTOS-->
				<tr>
					<th colspan="2">Costos</th>
					<td>$<?php print(number_format($row2['TOTALC'],2));?></td>
					<td><?php print(round($row2['costo']*100,2));?>%</td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroC'],2));?></td>
					<td><?php print(round($row2['costo']*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroC'],2));?></td>
					<td><?php if($row['EneroC'] == 0 || $row['EneroP'] == 0) {print(0);}else{print(round($row['EneroC']/$row['EneroP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['EneroC']-$row2['EneroC'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['FebreroC'],2));?></td>
					<td><?php print(round($row2['costo']*100,2));?>%</td>
					<td>$<?php print(number_format($row['FebreroC'],2));?></td>
					<td><?php if($row['FebreroC'] == 0 || $row['FebreroP'] == 0) {print(0);}else{print(round($row['FebreroC']/$row['FebreroP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['FebreroC']-$row2['FebreroC'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroC']+$row2['FebreroC'],2));?></td>
					<td><?php print(round(($row2['costo'])*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroC']+$row['FebreroC'],2));?></td>
					<td><?php if($row['EneroC'] == 0 || $row['EneroP'] == 0 || $row['FebreroC'] == 0 || $row['FebreroP'] == 0) {print(0);}else{print(round(($row['EneroC']+$row['FebreroC'])/($row['EneroP']+$row['FebreroP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($febreroC*-1,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['MarzoC'],2));?></td>
					<td><?php print(round(($row2['costo'])*100,2));?>%</td>
					<td>$<?php print(number_format($row['MarzoC'],2));?></td>
					<td><?php if($row['MarzoC'] == 0 || $row['MarzoP'] == 0) {print(0);}else{print(round($row['MarzoC']/$row['MarzoP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['MarzoC']-$row2['MarzoC'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroC']+$row2['FebreroC']+$row2['MarzoC'],2));?></td>
					<td><?php print(round(($row2['costo'])*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroC']+$row['FebreroC']+$row['MarzoC'],2));?></td>
					<td><?php if($row['EneroC'] == 0 || $row['EneroP'] == 0 || $row['FebreroC'] == 0 || $row['FebreroP'] == 0 || $row['MarzoC'] == 0 || $row['MarzoP'] == 0) {print(0);}else{print(round(($row['EneroC']+$row['FebreroC']+$row['MarzoC'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($marzoC*-1,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['AbrilC'],2));?></td>
					<td><?php print(round($row2['costo']*100,2));?>%</td>
					<td>$<?php print(number_format($row['AbrilC'],2));?></td>
					<td><?php if($row['AbrilC'] == 0 || $row['AbrilP'] == 0) {print(0);}else{print(round($row['AbrilC']/$row['AbrilP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['AbrilC']-$row2['AbrilC'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroC']+$row2['FebreroC']+$row2['MarzoC']+$row2['AbrilC'],2));?></td>
					<td><?php print(round(($row2['costo'])*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroC']+$row['FebreroC']+$row['MarzoC']+$row['AbrilC'],2));?></td>
					<td><?php if($row['EneroC'] == 0 || $row['EneroP'] == 0 || $row['FebreroC'] == 0 || $row['FebreroP'] == 0 || $row['MarzoC'] == 0 || $row['MarzoP'] == 0 || $row['AbrilC'] == 0 || $row['AbrilP'] == 0) {print(0);}else{print(round(($row['EneroC']+$row['FebreroC']+$row['MarzoC']+$row['AbrilC'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($abrilC,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['MayoC'],2));?></td>
					<td><?php print(round($row2['costo']*100,2));?>%</td>
					<td>$<?php print(number_format($row['MayoC'],2));?></td>
					<td><?php if($row['MayoC'] == 0 || $row['MayoP'] == 0) {print(0);}else{print(round($row['MayoC']/$row['MayoP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['MayoC']-$row2['MayoC'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroC']+$row2['FebreroC']+$row2['MarzoC']+$row2['AbrilC']+$row2['MayoC'],2));?></td>
					<td><?php print(round(($row2['costo'])*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroC']+$row['FebreroC']+$row['MarzoC']+$row['AbrilC']+$row['MayoC'],2));?></td>
					<td><?php if($row['EneroC'] == 0 || $row['EneroP'] == 0 || $row['FebreroC'] == 0 || $row['FebreroP'] == 0 || $row['MarzoC'] == 0 || $row['MarzoP'] == 0 || $row['AbrilC'] == 0 || $row['AbrilP'] == 0 || $row['MayoC'] == 0 || $row['MayoP'] == 0) {print(0);}else{print(round(($row['EneroC']+$row['FebreroC']+$row['MarzoC']+$row['AbrilC']+$row['MayoC'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($mayoC,2));?></td>					
					<td></td>
					<td>$<?php print(number_format($row2['JunioC'],2));?></td>
					<td><?php print(round($row2['costo']*100,2));?>%</td>
					<td>$<?php print(number_format($row['JunioC'],2));?></td>
					<td><?php if($row['JunioC'] == 0 || $row['JunioP'] == 0) {print(0);}else{print(round($row['JunioC']/$row['JunioP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['JunioC']-$row2['JunioC'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroC']+$row2['FebreroC']+$row2['MarzoC']+$row2['AbrilC']+$row2['MayoC']+$row2['JunioC'],2));?></td>
					<td><?php print(round(($row2['costo'])*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroC']+$row['FebreroC']+$row['MarzoC']+$row['AbrilC']+$row['MayoC']+$row['JunioC'],2));?></td>
					<td><?php if($row['EneroC'] == 0 || $row['EneroP'] == 0 || $row['FebreroC'] == 0 || $row['FebreroP'] == 0 || $row['MarzoC'] == 0 || $row['MarzoP'] == 0 || $row['AbrilC'] == 0 || $row['AbrilP'] == 0 || $row['MayoC'] == 0 || $row['MayoP'] == 0 || $row['JunioC'] == 0 || $row['JunioP'] == 0) {print(0);}else{print(round(($row['EneroC']+$row['FebreroC']+$row['MarzoC']+$row['AbrilC']+$row['MayoC']+$row['JunioC'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($junioC,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['JulioC'],2));?></td>
					<td><?php print(round($row2['costo']*100,2));?>%</td>
					<td>$<?php print(number_format($row['JulioC'],2));?></td>
					<td><?php if($row['JulioC'] == 0 || $row['JulioP'] == 0) {print(0);}else{print(round($row['JulioC']/$row['JulioP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['JulioC']-$row2['JulioC'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroC']+$row2['FebreroC']+$row2['MarzoC']+$row2['AbrilC']+$row2['MayoC']+$row2['JunioC']+$row2['JulioC'],2));?></td>
					<td><?php print(round(($row2['costo'])*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroC']+$row['FebreroC']+$row['MarzoC']+$row['AbrilC']+$row['MayoC']+$row['JunioC']+$row['JulioC'],2));?></td>
					<td><?php if($row['EneroC'] == 0 || $row['EneroP'] == 0 || $row['FebreroC'] == 0 || $row['FebreroP'] == 0 || $row['MarzoC'] == 0 || $row['MarzoP'] == 0 || $row['AbrilC'] == 0 || $row['AbrilP'] == 0 || $row['MayoC'] == 0 || $row['MayoP'] == 0 || $row['JunioC'] == 0 || $row['JunioP'] == 0 || $row['JulioC'] == 0 || $row['JulioP'] == 0) {print(0);}else{print(round(($row['EneroC']+$row['FebreroC']+$row['MarzoC']+$row['AbrilC']+$row['MayoC']+$row['JunioC']+$row['JulioC'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($julioC,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['AgostoC'],2));?></td>
					<td><?php print(round($row2['costo']*100,2));?>%</td>
					<td>$<?php print(number_format($row['AgostoC'],2));?></td>
					<td><?php if($row['AgostoC'] == 0 || $row['AgostoP'] == 0) {print(0);}else{print(round($row['AgostoC']/$row['AgostoP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['AgostoC']-$row2['AgostoC'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroC']+$row2['FebreroC']+$row2['MarzoC']+$row2['AbrilC']+$row2['MayoC']+$row2['JunioC']+$row2['JulioC']+$row2['AgostoC'],2));?></td>
					<td><?php print(round(($row2['costo'])*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroC']+$row['FebreroC']+$row['MarzoC']+$row['AbrilC']+$row['MayoC']+$row['JunioC']+$row['JulioC']+$row['AgostoC'],2));?></td>
					<td><?php if($row['EneroC'] == 0 || $row['EneroP'] == 0 || $row['FebreroC'] == 0 || $row['FebreroP'] == 0 || $row['MarzoC'] == 0 || $row['MarzoP'] == 0 || $row['AbrilC'] == 0 || $row['AbrilP'] == 0 || $row['MayoC'] == 0 || $row['MayoP'] == 0 || $row['JunioC'] == 0 || $row['JunioP'] == 0 || $row['JulioC'] == 0 || $row['JulioP'] == 0 || $row['AgostoC'] == 0 || $row['AgostoP'] == 0) {print(0);}else{print(round(($row['EneroC']+$row['FebreroC']+$row['MarzoC']+$row['AbrilC']+$row['MayoC']+$row['JunioC']+$row['JulioC']+$row['AgostoC'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($agostoC,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['SeptiembreC'],2));?></td>
					<td><?php print(round($row2['costo']*100,2));?>%</td>
					<td>$<?php print(number_format($row['SeptiembreC'],2));?></td>
					<td><?php if($row['SeptiembreC'] == 0 || $row['SeptiembreP'] == 0) {print(0);}else{print(round($row['SeptiembreC']/$row['SeptiembreP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['SeptiembreC']-$row2['SeptiembreC'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroC']+$row2['FebreroC']+$row2['MarzoC']+$row2['AbrilC']+$row2['MayoC']+$row2['JunioC']+$row2['JulioC']+$row2['AgostoC']+$row2['SeptiembreC'],2));?></td>
					<td><?php print(round(($row2['costo'])*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroC']+$row['FebreroC']+$row['MarzoC']+$row['AbrilC']+$row['MayoC']+$row['JunioC']+$row['JulioC']+$row['AgostoC']+$row['SeptiembreC'],2));?></td>
					<td><?php if($row['EneroC'] == 0 || $row['EneroP'] == 0 || $row['FebreroC'] == 0 || $row['FebreroP'] == 0 || $row['MarzoC'] == 0 || $row['MarzoP'] == 0 || $row['AbrilC'] == 0 || $row['AbrilP'] == 0 || $row['MayoC'] == 0 || $row['MayoP'] == 0 || $row['JunioC'] == 0 || $row['JunioP'] == 0 || $row['JulioC'] == 0 || $row['JulioP'] == 0 || $row['AgostoC'] == 0 || $row['AgostoP'] == 0 || $row['SeptiembreC'] == 0 || $row['SeptiembreP'] == 0) {print(0);}else{print(round(($row['EneroC']+$row['FebreroC']+$row['MarzoC']+$row['AbrilC']+$row['MayoC']+$row['JunioC']+$row['JulioC']+$row['AgostoC']+$row['SeptiembreC'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($septiembreC,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['OctubreC'],2));?></td>
					<td><?php print(round($row2['costo']*100,2));?>%</td>
					<td>$<?php print(number_format($row['OctubreC'],2));?></td>
					<td><?php if($row['OctubreC'] == 0 || $row['OctubreP'] == 0) {print(0);}else{print(round($row['OctubreC']/$row['OctubreP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['OctubreC']-$row2['OctubreC'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroC']+$row2['FebreroC']+$row2['MarzoC']+$row2['AbrilC']+$row2['MayoC']+$row2['JunioC']+$row2['JulioC']+$row2['AgostoC']+$row2['SeptiembreC']+$row2['OctubreC'],2));?></td>
					<td><?php print(round(($row2['costo'])*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroC']+$row['FebreroC']+$row['MarzoC']+$row['AbrilC']+$row['MayoC']+$row['JunioC']+$row['JulioC']+$row['AgostoC']+$row['SeptiembreC']+$row['OctubreC'],2));?></td>
					<td><?php if($row['EneroC'] == 0 || $row['EneroP'] == 0 || $row['FebreroC'] == 0 || $row['FebreroP'] == 0 || $row['MarzoC'] == 0 || $row['MarzoP'] == 0 || $row['AbrilC'] == 0 || $row['AbrilP'] == 0 || $row['MayoC'] == 0 || $row['MayoP'] == 0 || $row['JunioC'] == 0 || $row['JunioP'] == 0 || $row['JulioC'] == 0 || $row['JulioP'] == 0 || $row['AgostoC'] == 0 || $row['AgostoP'] == 0 || $row['SeptiembreC'] == 0 || $row['SeptiembreP'] == 0 || $row['OctubreC'] == 0 || $row['OctubreP'] == 0) {print(0);}else{print(round(($row['EneroC']+$row['FebreroC']+$row['MarzoC']+$row['AbrilC']+$row['MayoC']+$row['JunioC']+$row['JulioC']+$row['AgostoC']+$row['SeptiembreC']+$row['OctubreC'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($octubreC,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['NoviembreC'],2));?></td>
					<td><?php print(round($row2['costo']*100,2));?>%</td>
					<td>$<?php print(number_format($row['NoviembreC'],2));?></td>
					<td><?php if($row['NoviembreC'] == 0 || $row['NoviembreP'] == 0) {print(0);}else{print(round($row['NoviembreC']/$row['NoviembreP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['NoviembreC']-$row2['NoviembreC'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroC']+$row2['FebreroC']+$row2['MarzoC']+$row2['AbrilC']+$row2['MayoC']+$row2['JunioC']+$row2['JulioC']+$row2['AgostoC']+$row2['SeptiembreC']+$row2['OctubreC']+$row2['NoviembreC'],2));?></td>
					<td><?php print(round(($row2['costo'])*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroC']+$row['FebreroC']+$row['MarzoC']+$row['AbrilC']+$row['MayoC']+$row['JunioC']+$row['JulioC']+$row['AgostoC']+$row['SeptiembreC']+$row['OctubreC']+$row['NoviembreC'],2));?></td>
					<td><?php if($row['EneroC'] == 0 || $row['EneroP'] == 0 || $row['FebreroC'] == 0 || $row['FebreroP'] == 0 || $row['MarzoC'] == 0 || $row['MarzoP'] == 0 || $row['AbrilC'] == 0 || $row['AbrilP'] == 0 || $row['MayoC'] == 0 || $row['MayoP'] == 0 || $row['JunioC'] == 0 || $row['JunioP'] == 0 || $row['JulioC'] == 0 || $row['JulioP'] == 0 || $row['AgostoC'] == 0 || $row['AgostoP'] == 0 || $row['SeptiembreC'] == 0 || $row['SeptiembreP'] == 0 || $row['OctubreC'] == 0 || $row['OctubreP'] == 0 || $row['NoviembreC'] == 0 || $row['NoviembreP'] == 0) {print(0);}else{print(round(($row['EneroC']+$row['FebreroC']+$row['MarzoC']+$row['AbrilC']+$row['MayoC']+$row['JunioC']+$row['JulioC']+$row['AgostoC']+$row['SeptiembreC']+$row['OctubreC']+$row['NoviembreC'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP']+$row['NoviembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($noviembreC,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['DiciembreC'],2));?></td>
					<td><?php print(round($row2['costo']*100,2));?>%</td>
					<td>$<?php print(number_format($row['DiciembreC'],2));?></td>
					<td><?php if($row['DiciembreC'] == 0 || $row['DiciembreP'] == 0) {print(0);}else{print(round($row['DiciembreC']/$row['DiciembreP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['DiciembreC']-$row2['DiciembreC'],2));?></td>
				</tr>
			<?php
				if($row['EneroP'] == 0)
				{
					$row['EneroP'] = 1;
				}
				if($row2['Enero'] == 0)
				{
					$row2['Enero'] = 1;
				}
				if($row['FebreroP'] == 0)
				{
					$row['FebreroP'] = 1;
				}
				if($row['MarzoP'] == 0)
				{
					$row['MarzoP'] = 1;
				}
				if($row['AbrilP'] == 0)
				{
					$row['AbrilP'] = 1;
				}
				if($row['MayoP'] == 0)
				{
					$row['MayoP'] = 1;
				}
				if($row['JunioP'] == 0)
				{
					$row['JunioP'] = 1;
				}
				if($row['JulioP'] == 0)
				{
					$row['JulioP'] = 1;
				}
				if($row['AgostoP'] == 0)
				{
					$row['AgostoP'] = 1;
				}
				if($row['SeptiembreP'] == 0)
				{
					$row['SeptiembreP'] = 1;
				}
				if($row['OctubreP'] == 0)
				{
					$row['OctubreP'] = 1;
				}
				if($row['NoviembreP'] == 0)
				{
					$row['NoviembreP'] = 1;
				}
				if($row['DiciembreP'] == 0)
				{
					$row['DiciembreP'] = 1;
				}
				if($row['TOTALP'] == 0)
				{
					$row['TOTALP'] = 1;
				}
			?>
				<!--GASTO DIRECTO-->
				<tr>
					<th colspan="2">Gasto Directo</th>
					<td>$<?php print(number_format($row2['TOTALG'],2));?></td>
					<td><?php print(round($row2['gasto']*100,2));?>%</td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroG'],2));?></td>
					<td><?php print(round($row2['EneroG']/$row2['Enero']*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroG'],2));?></td>
					<td><?php print(round($row['EneroG']/$row['EneroP']*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroG']-$row2['EneroG'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['FebreroG'],2));?></td>
					<td><?php print(round($row2['FebreroG']/$row2['Febrero']*100,2));?>%</td>
					<td>$<?php print(number_format($row['FebreroG'],2));?></td>
					<td><?php print(round($row['FebreroG']/$row['FebreroP']*100,2));?>%</td>
					<td>$<?php print(number_format($row['FebreroG']-$row2['FebreroG'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroG']+$row2['FebreroG'],2));?></td>
					<td><?php print(round(($row2['gasto']*2)*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroG']+$row['FebreroG'],2));?></td>
					<td><?php if($row['EneroG'] == 0 || $row['EneroP'] == 0 || $row['FebreroG'] == 0 || $row['FebreroP'] == 0) {print(0);}else{print(round(($row['EneroG']+$row['FebreroG'])/($row['EneroP']+$row['FebreroP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($febreroG*-1,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['MarzoG'],2));?></td>
					<td><?php print(round(($row2['gasto'])*100,2));?>%</td>
					<td>$<?php print(number_format($row['MarzoG'],2));?></td>
					<td><?php if($row['MarzoG'] == 0 || $row['MarzoP'] == 0) {print(0);}else{print(round($row['MarzoG']/$row['MarzoP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['MarzoG']-$row2['MarzoG'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroG']+$row2['FebreroG']+$row2['MarzoG'],2));?></td>
					<td><?php print(round(($row2['gasto']*3)*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroG']+$row['FebreroG']+$row['MarzoG'],2));?></td>
					<td><?php if($row['EneroG'] == 0 || $row['EneroP'] == 0 || $row['FebreroG'] == 0 || $row['FebreroP'] == 0 || $row['MarzoG'] == 0 || $row['MarzoP'] == 0) {print(0);}else{print(round(($row['EneroG']+$row['FebreroG']+$row['MarzoG'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($marzoG*-1,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['AbrilG'],2));?></td>
					<td><?php print(round($row2['gasto']*100,2));?>%</td>
					<td>$<?php print(number_format($row['AbrilG'],2));?></td>
					<td><?php if($row['AbrilG'] == 0 || $row['AbrilP'] == 0) {print(0);}else{print(round($row['AbrilG']/$row['AbrilP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['AbrilG']-$row2['AbrilG'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroG']+$row2['FebreroG']+$row2['MarzoG']+$row2['AbrilG'],2));?></td>
					<td><?php print(round(($row2['gasto']*4)*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroG']+$row['FebreroG']+$row['MarzoG']+$row['AbrilG'],2));?></td>
					<td><?php if($row['EneroG'] == 0 || $row['EneroP'] == 0 || $row['FebreroG'] == 0 || $row['FebreroP'] == 0 || $row['MarzoG'] == 0 || $row['MarzoP'] == 0 || $row['AbrilG'] == 0 || $row['AbrilP'] == 0) {print(0);}else{print(round(($row['EneroG']+$row['FebreroG']+$row['MarzoG']+$row['AbrilG'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($abrilG,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['MayoG'],2));?></td>
					<td><?php print(round($row2['gasto']*100,2));?>%</td>
					<td>$<?php print(number_format($row['MayoG'],2));?></td>
					<td><?php if($row['MayoG'] == 0 || $row['MayoP'] == 0) {print(0);}else{print(round($row['MayoG']/$row['MayoP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['MayoG']-$row2['MayoG'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroG']+$row2['FebreroG']+$row2['MarzoG']+$row2['AbrilG']+$row2['MayoG'],2));?></td>
					<td><?php print(round(($row2['gasto']*5)*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroG']+$row['FebreroG']+$row['MarzoG']+$row['AbrilG']+$row['MayoG'],2));?></td>
					<td><?php if($row['EneroG'] == 0 || $row['EneroP'] == 0 || $row['FebreroG'] == 0 || $row['FebreroP'] == 0 || $row['MarzoG'] == 0 || $row['MarzoP'] == 0 || $row['AbrilG'] == 0 || $row['AbrilP'] == 0 || $row['MayoG'] == 0 || $row['MayoP'] == 0) {print(0);}else{print(round(($row['EneroG']+$row['FebreroG']+$row['MarzoG']+$row['AbrilG']+$row['MayoG'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($mayoG,2));?></td>					
					<td></td>
					<td>$<?php print(number_format($row2['JunioG'],2));?></td>
					<td><?php print(round($row2['gasto']*100,2));?>%</td>
					<td>$<?php print(number_format($row['JunioG'],2));?></td>
					<td><?php if($row['JunioG'] == 0 || $row['JunioP'] == 0) {print(0);}else{print(round($row['JunioG']/$row['JunioP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['JunioG']-$row2['JunioG'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroG']+$row2['FebreroG']+$row2['MarzoG']+$row2['AbrilG']+$row2['MayoG']+$row2['JunioG'],2));?></td>
					<td><?php print(round(($row2['gasto']*6)*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroG']+$row['FebreroG']+$row['MarzoG']+$row['AbrilG']+$row['MayoG']+$row['JunioG'],2));?></td>
					<td><?php if($row['EneroG'] == 0 || $row['EneroP'] == 0 || $row['FebreroG'] == 0 || $row['FebreroP'] == 0 || $row['MarzoG'] == 0 || $row['MarzoP'] == 0 || $row['AbrilG'] == 0 || $row['AbrilP'] == 0 || $row['MayoG'] == 0 || $row['MayoP'] == 0 || $row['JunioG'] == 0 || $row['JunioP'] == 0) {print(0);}else{print(round(($row['EneroG']+$row['FebreroG']+$row['MarzoG']+$row['AbrilG']+$row['MayoG']+$row['JunioG'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($junioG,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['JulioG'],2));?></td>
					<td><?php print(round($row2['gasto']*100,2));?>%</td>
					<td>$<?php print(number_format($row['JulioG'],2));?></td>
					<td><?php if($row['JulioG'] == 0 || $row['JulioP'] == 0) {print(0);}else{print(round($row['JulioG']/$row['JulioP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['JulioG']-$row2['JulioG'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroG']+$row2['FebreroG']+$row2['MarzoG']+$row2['AbrilG']+$row2['MayoG']+$row2['JunioG']+$row2['JulioG'],2));?></td>
					<td><?php print(round(($row2['gasto']*7)*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroG']+$row['FebreroG']+$row['MarzoG']+$row['AbrilG']+$row['MayoG']+$row['JunioG']+$row['JulioG'],2));?></td>
					<td><?php if($row['EneroG'] == 0 || $row['EneroP'] == 0 || $row['FebreroG'] == 0 || $row['FebreroP'] == 0 || $row['MarzoG'] == 0 || $row['MarzoP'] == 0 || $row['AbrilG'] == 0 || $row['AbrilP'] == 0 || $row['MayoG'] == 0 || $row['MayoP'] == 0 || $row['JunioG'] == 0 || $row['JunioP'] == 0 || $row['JulioG'] == 0 || $row['JulioP'] == 0) {print(0);}else{print(round(($row['EneroG']+$row['FebreroG']+$row['MarzoG']+$row['AbrilG']+$row['MayoG']+$row['JunioG']+$row['JulioG'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($julioG,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['AgostoG'],2));?></td>
					<td><?php print(round($row2['gasto']*100,2));?>%</td>
					<td>$<?php print(number_format($row['AgostoG'],2));?></td>
					<td><?php if($row['AgostoG'] == 0 || $row['AgostoP'] == 0) {print(0);}else{print(round($row['AgostoG']/$row['AgostoP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['AgostoG']-$row2['AgostoG'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroG']+$row2['FebreroG']+$row2['MarzoG']+$row2['AbrilG']+$row2['MayoG']+$row2['JunioG']+$row2['JulioG']+$row2['AgostoG'],2));?></td>
					<td><?php print(round(($row2['gasto']*8)*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroG']+$row['FebreroG']+$row['MarzoG']+$row['AbrilG']+$row['MayoG']+$row['JunioG']+$row['JulioG']+$row['AgostoG'],2));?></td>
					<td><?php if($row['EneroG'] == 0 || $row['EneroP'] == 0 || $row['FebreroG'] == 0 || $row['FebreroP'] == 0 || $row['MarzoG'] == 0 || $row['MarzoP'] == 0 || $row['AbrilG'] == 0 || $row['AbrilP'] == 0 || $row['MayoG'] == 0 || $row['MayoP'] == 0 || $row['JunioG'] == 0 || $row['JunioP'] == 0 || $row['JulioG'] == 0 || $row['JulioP'] == 0 || $row['AgostoG'] == 0 || $row['AgostoP'] == 0) {print(0);}else{print(round(($row['EneroG']+$row['FebreroG']+$row['MarzoG']+$row['AbrilG']+$row['MayoG']+$row['JunioG']+$row['JulioG']+$row['AgostoG'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($agostoG,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['SeptiembreG'],2));?></td>
					<td><?php print(round($row2['gasto']*100,2));?>%</td>
					<td>$<?php print(number_format($row['SeptiembreG'],2));?></td>
					<td><?php if($row['SeptiembreG'] == 0 || $row['SeptiembreP'] == 0) {print(0);}else{print(round($row['SeptiembreG']/$row['SeptiembreP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['SeptiembreG']-$row2['SeptiembreG'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroG']+$row2['FebreroG']+$row2['MarzoG']+$row2['AbrilG']+$row2['MayoG']+$row2['JunioG']+$row2['JulioG']+$row2['AgostoG']+$row2['SeptiembreG'],2));?></td>
					<td><?php print(round(($row2['gasto']*9)*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroG']+$row['FebreroG']+$row['MarzoG']+$row['AbrilG']+$row['MayoG']+$row['JunioG']+$row['JulioG']+$row['AgostoG']+$row['SeptiembreG'],2));?></td>
					<td><?php if($row['EneroG'] == 0 || $row['EneroP'] == 0 || $row['FebreroG'] == 0 || $row['FebreroP'] == 0 || $row['MarzoG'] == 0 || $row['MarzoP'] == 0 || $row['AbrilG'] == 0 || $row['AbrilP'] == 0 || $row['MayoG'] == 0 || $row['MayoP'] == 0 || $row['JunioG'] == 0 || $row['JunioP'] == 0 || $row['JulioG'] == 0 || $row['JulioP'] == 0 || $row['AgostoG'] == 0 || $row['AgostoP'] == 0 || $row['SeptiembreG'] == 0 || $row['SeptiembreP'] == 0) {print(0);}else{print(round(($row['EneroG']+$row['FebreroG']+$row['MarzoG']+$row['AbrilG']+$row['MayoG']+$row['JunioG']+$row['JulioG']+$row['AgostoG']+$row['SeptiembreG'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($septiembreG,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['OctubreG'],2));?></td>
					<td><?php print(round($row2['gasto']*100,2));?>%</td>
					<td>$<?php print(number_format($row['OctubreG'],2));?></td>
					<td><?php if($row['OctubreG'] == 0 || $row['OctubreP'] == 0) {print(0);}else{print(round($row['OctubreG']/$row['OctubreP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['OctubreG']-$row2['OctubreG'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroG']+$row2['FebreroG']+$row2['MarzoG']+$row2['AbrilG']+$row2['MayoG']+$row2['JunioG']+$row2['JulioG']+$row2['AgostoG']+$row2['SeptiembreG']+$row2['OctubreG'],2));?></td>
					<td><?php print(round(($row2['gasto']*10)*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroG']+$row['FebreroG']+$row['MarzoG']+$row['AbrilG']+$row['MayoG']+$row['JunioG']+$row['JulioG']+$row['AgostoG']+$row['SeptiembreG']+$row['OctubreG'],2));?></td>
					<td><?php if($row['EneroG'] == 0 || $row['EneroP'] == 0 || $row['FebreroG'] == 0 || $row['FebreroP'] == 0 || $row['MarzoG'] == 0 || $row['MarzoP'] == 0 || $row['AbrilG'] == 0 || $row['AbrilP'] == 0 || $row['MayoG'] == 0 || $row['MayoP'] == 0 || $row['JunioG'] == 0 || $row['JunioP'] == 0 || $row['JulioG'] == 0 || $row['JulioP'] == 0 || $row['AgostoG'] == 0 || $row['AgostoP'] == 0 || $row['SeptiembreG'] == 0 || $row['SeptiembreP'] == 0 || $row['OctubreG'] == 0 || $row['OctubreP'] == 0) {print(0);}else{print(round(($row['EneroG']+$row['FebreroG']+$row['MarzoG']+$row['AbrilG']+$row['MayoG']+$row['JunioG']+$row['JulioG']+$row['AgostoG']+$row['SeptiembreG']+$row['OctubreG'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($octubreG,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['NoviembreG'],2));?></td>
					<td><?php print(round($row2['gasto']*100,2));?>%</td>
					<td>$<?php print(number_format($row['NoviembreG'],2));?></td>
					<td><?php if($row['NoviembreG'] == 0 || $row['NoviembreP'] == 0) {print(0);}else{print(round($row['NoviembreG']/$row['NoviembreP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['NoviembreG']-$row2['NoviembreG'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['EneroG']+$row2['FebreroG']+$row2['MarzoG']+$row2['AbrilG']+$row2['MayoG']+$row2['JunioG']+$row2['JulioG']+$row2['AgostoG']+$row2['SeptiembreG']+$row2['OctubreG']+$row2['NoviembreG'],2));?></td>
					<td><?php print(round(($row2['gasto']*11)*100,2));?>%</td>
					<td>$<?php print(number_format($row['EneroG']+$row['FebreroG']+$row['MarzoG']+$row['AbrilG']+$row['MayoG']+$row['JunioG']+$row['JulioG']+$row['AgostoG']+$row['SeptiembreG']+$row['OctubreG']+$row['NoviembreG'],2));?></td>
					<td><?php if($row['EneroG'] == 0 || $row['EneroP'] == 0 || $row['FebreroG'] == 0 || $row['FebreroP'] == 0 || $row['MarzoG'] == 0 || $row['MarzoP'] == 0 || $row['AbrilG'] == 0 || $row['AbrilP'] == 0 || $row['MayoG'] == 0 || $row['MayoP'] == 0 || $row['JunioG'] == 0 || $row['JunioP'] == 0 || $row['JulioG'] == 0 || $row['JulioP'] == 0 || $row['AgostoG'] == 0 || $row['AgostoP'] == 0 || $row['SeptiembreG'] == 0 || $row['SeptiembreP'] == 0 || $row['OctubreG'] == 0 || $row['OctubreP'] == 0 || $row['NoviembreG'] == 0 || $row['NoviembreP'] == 0) {print(0);}else{print(round(($row['EneroG']+$row['FebreroG']+$row['MarzoG']+$row['AbrilG']+$row['MayoG']+$row['JunioG']+$row['JulioG']+$row['AgostoG']+$row['SeptiembreG']+$row['OctubreG']+$row['NoviembreG'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP']+$row['NoviembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($noviembreG,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['DiciembreG'],2));?></td>
					<td><?php print(round($row2['gasto']*100,2));?>%</td>
					<td>$<?php print(number_format($row['DiciembreG'],2));?></td>
					<td><?php if($row['DiciembreG'] == 0 || $row['DiciembreP'] == 0) {print(0);}else{print(round($row['DiciembreG']/$row['DiciembreP']*100,2));}?>%</td>
					<td>$<?php print(number_format($row['DiciembreG']-$row2['DiciembreG'],2));?></td>
				</tr>
				<!--MARGEN DIRECTO-->
				<tr>
					<th colspan="2">Margen Directo</th>
					<td>$<?php print(number_format($row2['TOTAL']-$row2['TOTALC']-$row2['TOTALG'],2));?></td>
					<td><?php print(round(100-($row2['costo']*100),2));?>%</td>
					<td></td>
					<td>$<?php print(number_format($row2['Enero']-$row2['EneroC']-$row2['EneroG'],2));?></td>
					<td><?php print(round(100-(($row2['costo']+$row2['gasto'])*100),2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto1,2));?></td>
					<td><?php print(round($diferenciaMargenDirectoPor1,2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto1-($row2['Enero']-$row2['EneroC']-$row2['EneroG']),2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Febrero']-$row2['FebreroC']-$row2['FebreroG'],2));?></td>
					<td><?php print(round(100-(($row2['costo']+$row2['gasto'])*100),2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto2,2));?></td>
					<td><?php print(round($diferenciaMargenDirectoPor2,2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto2-($row2['Febrero']-$row2['FebreroC']-$row2['FebreroG']),2));?></td>
					<td></td>
					<td>$<?php print(number_format(($row2['Enero']-$row2['EneroC']-$row2['EneroG'])+($row2['Febrero']-$row2['FebreroC']-$row2['FebreroG']),2));?></td>
					<td><?php print(round(100-(($row2['costo']+$row2['gasto'])*100),2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto1+$diferenciaMargenDirecto2,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0){print(0);} else{print(round(($diferenciaMargenDirecto1+$diferenciaMargenDirecto2)/($row['EneroP']+$row['FebreroP'])*100,2));}?>%</td>
					<td>$<?php print(number_format(($febrero-$febreroC-$febreroG)*-1,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Marzo']-$row2['MarzoC']-$row2['MarzoG'],2));?></td>
					<td><?php print(round(100-(($row2['costo']+$row2['gasto'])*100),2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto3,2));?></td>
					<td><?php print(round($diferenciaMargenDirectoPor3,2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto3-($row2['Marzo']-$row2['MarzoC']-$row2['MarzoG']),2));?></td>
					<td></td>
					<td>$<?php print(number_format(($row2['Enero']-$row2['EneroC']-$row2['EneroG'])+($row2['Febrero']-$row2['FebreroC']-$row2['FebreroG'])+($row2['Marzo']-$row2['MarzoC']-$row2['MarzoG']),2));?></td>
					<td><?php print(round(100-(($row2['costo']+$row2['gasto'])*100),2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto1+$diferenciaMargenDirecto2+$diferenciaMargenDirecto3,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0){print(0);} else{print(round(($diferenciaMargenDirecto1+$diferenciaMargenDirecto2+$diferenciaMargenDirecto3)/($row['EneroP']+$row['FebreroP']+$row['MarzoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format(($marzo-$marzoC-$marzoG)*-1,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Abril']-$row2['AbrilC']-$row2['AbrilG'],2));?></td>
					<td><?php print(round(100-(($row2['costo']+$row2['gasto'])*100),2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto4,2));?></td>
					<td><?php print(round($diferenciaMargenDirectoPor4,2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto4-($row2['Abril']-$row2['AbrilC']-$row2['AbrilG']),2));?></td>
					<td></td>
					<td>$<?php print(number_format(($row2['Enero']-$row2['EneroC']-$row2['EneroG'])+($row2['Febrero']-$row2['FebreroC']-$row2['FebreroG'])+($row2['Marzo']-$row2['MarzoC']-$row2['MarzoG'])+($row2['Abril']-$row2['AbrilC']-$row2['AbrilG']),2));?></td>
					<td><?php print(round(100-(($row2['costo']+$row2['gasto'])*100),2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto1+$diferenciaMargenDirecto2+$diferenciaMargenDirecto3+$diferenciaMargenDirecto4,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0){print(0);} else{print(round(($diferenciaMargenDirecto1+$diferenciaMargenDirecto2+$diferenciaMargenDirecto3+$diferenciaMargenDirecto4)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP'])*100,2));}?>%</td>
					<td>$<?php print(number_format(($abril-$abrilC-$abrilG)*-1,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Mayo']-$row2['MayoC']-$row2['MayoG'],2));?></td>
					<td><?php print(round(100-(($row2['costo']+$row2['gasto'])*100),2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto5,2));?></td>
					<td><?php print(round($diferenciaMargenDirectoPor5,2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto5-($row2['Mayo']-$row2['MayoC']-$row2['MayoG']),2));?></td>
					<td></td>
					<td>$<?php print(number_format(($row2['Enero']-$row2['EneroC']-$row2['EneroG'])+($row2['Febrero']-$row2['FebreroC']-$row2['FebreroG'])+($row2['Marzo']-$row2['MarzoC']-$row2['MarzoG'])+($row2['Abril']-$row2['AbrilC']-$row2['AbrilG'])+($row2['Mayo']-$row2['MayoC']-$row2['MayoG']),2));?></td>
					<td><?php print(round(100-(($row2['costo']+$row2['gasto'])*100),2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto1+$diferenciaMargenDirecto2+$diferenciaMargenDirecto3+$diferenciaMargenDirecto4+$diferenciaMargenDirecto5,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0){print(0);} else{print(round(($diferenciaMargenDirecto1+$diferenciaMargenDirecto2+$diferenciaMargenDirecto3+$diferenciaMargenDirecto4+$diferenciaMargenDirecto5)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format(($mayo-$mayoC-$mayoG)*-1,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Junio']-$row2['JunioC']-$row2['JunioG'],2));?></td>
					<td><?php print(round(100-(($row2['costo']+$row2['gasto'])*100),2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto6,2));?></td>
					<td><?php print(round($diferenciaMargenDirectoPor6,2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto6-($row2['Junio']-$row2['JunioC']-$row2['JunioG']),2));?></td>
					<td></td>
					<td>$<?php print(number_format(($row2['Enero']-$row2['EneroC']-$row2['EneroG'])+($row2['Febrero']-$row2['FebreroC']-$row2['FebreroG'])+($row2['Marzo']-$row2['MarzoC']-$row2['MarzoG'])+($row2['Abril']-$row2['AbrilC']-$row2['AbrilG'])+($row2['Mayo']-$row2['MayoC']-$row2['MayoG'])+($row2['Junio']-$row2['JunioC']-$row2['JunioG']),2));?></td>
					<td><?php print(round(100-(($row2['costo']+$row2['gasto'])*100),2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto1+$diferenciaMargenDirecto2+$diferenciaMargenDirecto3+$diferenciaMargenDirecto4+$diferenciaMargenDirecto5+$diferenciaMargenDirecto6,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0){print(0);} else{print(round(($diferenciaMargenDirecto1+$diferenciaMargenDirecto2+$diferenciaMargenDirecto3+$diferenciaMargenDirecto4+$diferenciaMargenDirecto5+$diferenciaMargenDirecto6)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format(($junio-$junioC-$junioG)*-1,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Julio']-$row2['JulioC']-$row2['JulioG'],2));?></td>
					<td><?php print(round(100-(($row2['costo']+$row2['gasto'])*100),2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto7,2));?></td>
					<td><?php print(round($diferenciaMargenDirectoPor7,2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto7-($row2['Julio']-$row2['JulioC']-$row2['JulioG']),2));?></td>
					<td></td>
					<td>$<?php print(number_format(($row2['Enero']-$row2['EneroC']-$row2['EneroG'])+($row2['Febrero']-$row2['FebreroC']-$row2['FebreroG'])+($row2['Marzo']-$row2['MarzoC']-$row2['MarzoG'])+($row2['Abril']-$row2['AbrilC']-$row2['AbrilG'])+($row2['Mayo']-$row2['MayoC']-$row2['MayoG'])+($row2['Junio']-$row2['JunioC']-$row2['JunioG'])+($row2['Julio']-$row2['JulioC']-$row2['JulioG']),2));?></td>
					<td><?php print(round(100-(($row2['costo']+$row2['gasto'])*100),2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto1+$diferenciaMargenDirecto2+$diferenciaMargenDirecto3+$diferenciaMargenDirecto4+$diferenciaMargenDirecto5+$diferenciaMargenDirecto6+$diferenciaMargenDirecto7,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0 || $row['JulioP'] == 0){print(0);} else{print(round(($diferenciaMargenDirecto1+$diferenciaMargenDirecto2+$diferenciaMargenDirecto3+$diferenciaMargenDirecto4+$diferenciaMargenDirecto5+$diferenciaMargenDirecto6+$diferenciaMargenDirecto7)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format(($julio-$julioC-$julioG)*-1,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Agosto']-$row2['AgostoC']-$row2['AgostoG'],2));?></td>
					<td><?php print(round(100-(($row2['costo']+$row2['gasto'])*100),2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto8,2));?></td>
					<td><?php print(round($diferenciaMargenDirectoPor8,2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto8-($row2['Agosto']-$row2['AgostoC']-$row2['AgostoG']),2));?></td>
					<td></td>
					<td>$<?php print(number_format(($row2['Enero']-$row2['EneroC']-$row2['EneroG'])+($row2['Febrero']-$row2['FebreroC']-$row2['FebreroG'])+($row2['Marzo']-$row2['MarzoC']-$row2['MarzoG'])+($row2['Abril']-$row2['AbrilC']-$row2['AbrilG'])+($row2['Mayo']-$row2['MayoC']-$row2['MayoG'])+($row2['Junio']-$row2['JunioC']-$row2['JunioG'])+($row2['Julio']-$row2['JulioC']-$row2['JulioG'])+($row2['Agosto']-$row2['AgostoC']-$row2['AgostoG']),2));?></td>
					<td><?php print(round(100-(($row2['costo']+$row2['gasto'])*100),2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto1+$diferenciaMargenDirecto2+$diferenciaMargenDirecto3+$diferenciaMargenDirecto4+$diferenciaMargenDirecto5+$diferenciaMargenDirecto6+$diferenciaMargenDirecto7+$diferenciaMargenDirecto8,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0 || $row['JulioP'] == 0 || $row['AgostoP'] == 0){print(0);} else{print(round(($diferenciaMargenDirecto1+$diferenciaMargenDirecto2+$diferenciaMargenDirecto3+$diferenciaMargenDirecto4+$diferenciaMargenDirecto5+$diferenciaMargenDirecto6+$diferenciaMargenDirecto7+$diferenciaMargenDirecto8)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format(($agosto-$agostoC-$agostoG)*-1,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Septiembre']-$row2['SeptiembreC']-$row2['SeptiembreG'],2));?></td>
					<td><?php print(round(100-(($row2['costo']+$row2['gasto'])*100),2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto9,2));?></td>
					<td><?php print(round($diferenciaMargenDirectoPor9,2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto9-($row2['Septiembre']-$row2['SeptiembreC']-$row2['SeptiembreG']),2));?></td>
					<td></td>
					<td>$<?php print(number_format(($row2['Enero']-$row2['EneroC']-$row2['EneroG'])+($row2['Febrero']-$row2['FebreroC']-$row2['FebreroG'])+($row2['Marzo']-$row2['MarzoC']-$row2['MarzoG'])+($row2['Abril']-$row2['AbrilC']-$row2['AbrilG'])+($row2['Mayo']-$row2['MayoC']-$row2['MayoG'])+($row2['Junio']-$row2['JunioC']-$row2['JunioG'])+($row2['Julio']-$row2['JulioC']-$row2['JulioG'])+($row2['Agosto']-$row2['AgostoC']-$row2['AgostoG'])+($row2['Septiembre']-$row2['SeptiembreC']-$row2['SeptiembreG']),2));?></td>
					<td><?php print(round(100-(($row2['costo']+$row2['gasto'])*100),2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto1+$diferenciaMargenDirecto2+$diferenciaMargenDirecto3+$diferenciaMargenDirecto4+$diferenciaMargenDirecto5+$diferenciaMargenDirecto6+$diferenciaMargenDirecto7+$diferenciaMargenDirecto8+$diferenciaMargenDirecto9,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0 || $row['JulioP'] == 0 || $row['AgostoP'] == 0 || $row['SeptiembreP'] == 0){print(0);} else{print(round(($diferenciaMargenDirecto1+$diferenciaMargenDirecto2+$diferenciaMargenDirecto3+$diferenciaMargenDirecto4+$diferenciaMargenDirecto5+$diferenciaMargenDirecto6+$diferenciaMargenDirecto7+$diferenciaMargenDirecto8+$diferenciaMargenDirecto9)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format(($septiembre-$septiembreC-$septiembreG)*-1,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Octubre']-$row2['OctubreC']-$row2['OctubreG'],2));?></td>
					<td><?php print(round(100-(($row2['costo']+$row2['gasto'])*100),2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto10,2));?></td>
					<td><?php print(round($diferenciaMargenDirectoPor10,2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto10-($row2['Octubre']-$row2['OctubreC']-$row2['OctubreG']),2));?></td>
					<td></td>
					<td>$<?php print(number_format(($row2['Enero']-$row2['EneroC']-$row2['EneroG'])+($row2['Febrero']-$row2['FebreroC']-$row2['FebreroG'])+($row2['Marzo']-$row2['MarzoC']-$row2['MarzoG'])+($row2['Abril']-$row2['AbrilC']-$row2['AbrilG'])+($row2['Mayo']-$row2['MayoC']-$row2['MayoG'])+($row2['Junio']-$row2['JunioC']-$row2['JunioG'])+($row2['Julio']-$row2['JulioC']-$row2['JulioG'])+($row2['Agosto']-$row2['AgostoC']-$row2['AgostoG'])+($row2['Septiembre']-$row2['SeptiembreC']-$row2['SeptiembreG'])+($row2['Octubre']-$row2['OctubreC']-$row2['OctubreG']),2));?></td>
					<td><?php print(round(100-(($row2['costo']+$row2['gasto'])*100),2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto1+$diferenciaMargenDirecto2+$diferenciaMargenDirecto3+$diferenciaMargenDirecto4+$diferenciaMargenDirecto5+$diferenciaMargenDirecto6+$diferenciaMargenDirecto7+$diferenciaMargenDirecto8+$diferenciaMargenDirecto9+$diferenciaMargenDirecto10,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0 || $row['JulioP'] == 0 || $row['AgostoP'] == 0 || $row['SeptiembreP'] == 0 || $row['OctubreP'] == 0){print(0);} else{print(round(($diferenciaMargenDirecto1+$diferenciaMargenDirecto2+$diferenciaMargenDirecto3+$diferenciaMargenDirecto4+$diferenciaMargenDirecto5+$diferenciaMargenDirecto6+$diferenciaMargenDirecto7+$diferenciaMargenDirecto8+$diferenciaMargenDirecto9+$diferenciaMargenDirecto10)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format(($octubre-$octubreC-$octubreG)*-1,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Noviembre']-$row2['NoviembreC']-$row2['NoviembreG'],2));?></td>
					<td><?php print(round(100-(($row2['costo']+$row2['gasto'])*100),2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto11,2));?></td>
					<td><?php print(round($diferenciaMargenDirectoPor11,2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto11-($row2['Noviembre']-$row2['NoviembreC']-$row2['NoviembreG']),2));?></td>
					<td></td>
					<td>$<?php print(number_format(($row2['Enero']-$row2['EneroC']-$row2['EneroG'])+($row2['Febrero']-$row2['FebreroC']-$row2['FebreroG'])+($row2['Marzo']-$row2['MarzoC']-$row2['MarzoG'])+($row2['Abril']-$row2['AbrilC']-$row2['AbrilG'])+($row2['Mayo']-$row2['MayoC']-$row2['MayoG'])+($row2['Junio']-$row2['JunioC']-$row2['JunioG'])+($row2['Julio']-$row2['JulioC']-$row2['JulioG'])+($row2['Agosto']-$row2['AgostoC']-$row2['AgostoG'])+($row2['Septiembre']-$row2['SeptiembreC']-$row2['SeptiembreG'])+($row2['Octubre']-$row2['OctubreC']-$row2['OctubreG'])+($row2['Noviembre']-$row2['NoviembreC']-$row2['NoviembreG']),2));?></td>
					<td><?php print(round(100-(($row2['costo']+$row2['gasto'])*100),2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto1+$diferenciaMargenDirecto2+$diferenciaMargenDirecto3+$diferenciaMargenDirecto4+$diferenciaMargenDirecto5+$diferenciaMargenDirecto6+$diferenciaMargenDirecto7+$diferenciaMargenDirecto8+$diferenciaMargenDirecto9+$diferenciaMargenDirecto10+$diferenciaMargenDirecto11,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0 || $row['JulioP'] == 0 || $row['AgostoP'] == 0 || $row['SeptiembreP'] == 0 || $row['OctubreP'] == 0 || $row['NoviembreP'] == 0){print(0);} else{print(round(($diferenciaMargenDirecto1+$diferenciaMargenDirecto2+$diferenciaMargenDirecto3+$diferenciaMargenDirecto4+$diferenciaMargenDirecto5+$diferenciaMargenDirecto6+$diferenciaMargenDirecto7+$diferenciaMargenDirecto8+$diferenciaMargenDirecto9+$diferenciaMargenDirecto10+$diferenciaMargenDirecto11)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP']+$row['NoviembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format(($noviembre-$noviembreC-$noviembreG)*-1,2));?></td>
					<td></td>
					<td>$<?php print(number_format($row2['Diciembre']-$row2['DiciembreC'],2));?></td>
					<td><?php print(round(100-($row2['costo']*100),2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto12,2));?></td>
					<td><?php print(round($diferenciaMargenDirectoPor12,2));?>%</td>
					<td>$<?php print(number_format($diferenciaMargenDirecto12-($row2['Diciembre']-$row2['DiciembreC']-$row2['DiciembreG']),2));?></td>
				</tr>
				<tr>
					<td colspan="136"></td>
				</tr>
				<!--NOMINA-->
				<tr>
					<th></th>
					<th>Nomina Total</th>
					<td>$<?php print(number_format(($row3['TotalNomina']),2));?></td>
					<td><?php if($row2['TOTAL'] == 0){print(0);}else{print(round($row3['TotalNomina']/$row2['TOTAL']*100,2));}?>%</td>
					<td></td>
					<td>$<?php print(number_format($nominaMensual,2));?></td>
					<td><?php if($row2['Enero'] == 0){print(0);}else{print(round($nominaMensual/$row2['Enero']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroNomina'],2));?></td>
					<td><?php if($row4['EneroNomina'] == 0 || $row['EneroP'] == 0){print(0);}else{print(round(($row4['EneroNomina']/$row['EneroP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroNomina']-$nominaMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($nominaMensual,2));?></td>
					<td><?php if($row2['Febrero'] == 0){print(0);}else{print(round($nominaMensual/$row2['Febrero']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['FebreroNomina'],2));?></td>
					<td><?php if($row4['FebreroNomina'] == 0 || $row['FebreroP'] == 0){print(0);}else{print(round(($row4['FebreroNomina']/$row['FebreroP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['FebreroNomina']-$nominaMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($nominaMensual*2,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0){print(0);}else{print(round(($nominaMensual*2)/($row2['Enero']+$row2['Febrero'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroNomina']+$row4['FebreroNomina'],2));?></td>
					<td><?php if($row4['EneroNomina'] == 0 || $row['EneroP'] == 0 || $row4['FebreroNomina'] == 0 || $row['FebreroP'] == 0){print(0);}else{print(round(($row4['EneroNomina']+$row4['FebreroNomina'])/($row['EneroP']+$row['FebreroP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($febreroNom,2));?></td>
					<td></td>
					<td>$<?php print(number_format($nominaMensual,2));?></td>
					<td><?php if($row2['Marzo'] == 0){print(0);}else{print(round($nominaMensual/$row2['Marzo']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['MarzoNomina'],2));?></td>
					<td><?php if($row4['MarzoNomina'] == 0 || $row['MarzoP'] == 0){print(0);}else{print(round(($row4['MarzoNomina']/$row['MarzoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['MarzoNomina']-$nominaMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($nominaMensual*3,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0){print(0);}else{print(round(($nominaMensual*3)/($row2['Enero']+$row2['Febrero']+$row2['Marzo'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroNomina']+$row4['FebreroNomina']+$row4['MarzoNomina'],2));?></td>
					<td><?php if($row4['EneroNomina'] == 0 || $row['EneroP'] == 0 || $row4['FebreroNomina'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoNomina'] == 0 || $row['MarzoP'] == 0){print(0);}else{print(round(($row4['EneroNomina']+$row4['FebreroNomina']+$row4['MarzoNomina'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($marzoNom,2));?></td>
					<td></td>
					<td>$<?php print(number_format($nominaMensual,2));?></td>
					<td><?php if($row2['Abril'] == 0){print(0);}else{print(round($nominaMensual/$row2['Abril']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['AbrilNomina'],2));?></td>
					<td><?php if($row4['AbrilNomina'] == 0 || $row['AbrilP'] == 0){print(0);}else{print(round(($row4['AbrilNomina']/$row['AbrilP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['AbrilNomina']-$nominaMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($nominaMensual*4,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0){print(0);}else{print(round(($nominaMensual*4)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroNomina']+$row4['FebreroNomina']+$row4['MarzoNomina']+$row4['AbrilNomina'],2));?></td>
					<td><?php if($row4['EneroNomina'] == 0 || $row['EneroP'] == 0 || $row4['FebreroNomina'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoNomina'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilNomina'] == 0 || $row['AbrilP'] == 0){print(0);}else{print(round(($row4['EneroNomina']+$row4['FebreroNomina']+$row4['MarzoNomina']+$row4['AbrilNomina'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($abrilNom,2));?></td>
					<td></td>
					<td>$<?php print(number_format($nominaMensual,2));?></td>
					<td><?php if($row2['Mayo'] == 0){print(0);}else{print(round($nominaMensual/$row2['Mayo']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['MayoNomina'],2));?></td>
					<td><?php if($row4['MayoNomina'] == 0 || $row['MayoP'] == 0){print(0);}else{print(round(($row4['MayoNomina']/$row['MayoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['MayoNomina']-$nominaMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($nominaMensual*5,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0){print(0);}else{print(round(($nominaMensual*5)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroNomina']+$row4['FebreroNomina']+$row4['MarzoNomina']+$row4['AbrilNomina']+$row4['MayoNomina'],2));?></td>
					<td><?php if($row4['EneroNomina'] == 0 || $row['EneroP'] == 0 || $row4['FebreroNomina'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoNomina'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilNomina'] == 0 || $row['AbrilP'] == 0 || $row4['MayoNomina'] == 0 || $row['MayoP'] == 0){print(0);}else{print(round(($row4['EneroNomina']+$row4['FebreroNomina']+$row4['MarzoNomina']+$row4['AbrilNomina']+$row4['MayoNomina'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($mayoNom,2));?></td>
					<td></td>
					<td>$<?php print(number_format($nominaMensual,2));?></td>
					<td><?php if($row2['Junio'] == 0){print(0);}else{print(round($nominaMensual/$row2['Junio']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['JunioNomina'],2));?></td>
					<td><?php if($row4['JunioNomina'] == 0 || $row['JunioP'] == 0){print(0);}else{print(round(($row4['JunioNomina']/$row['JunioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['JunioNomina']-$nominaMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($nominaMensual*6,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0){print(0);}else{print(round(($nominaMensual*6)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroNomina']+$row4['FebreroNomina']+$row4['MarzoNomina']+$row4['AbrilNomina']+$row4['MayoNomina']+$row4['JunioNomina'],2));?></td>
					<td><?php if($row4['EneroNomina'] == 0 || $row['EneroP'] == 0 || $row4['FebreroNomina'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoNomina'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilNomina'] == 0 || $row['AbrilP'] == 0 || $row4['MayoNomina'] == 0 || $row['MayoP'] == 0 || $row4['JunioNomina'] == 0 || $row['JunioP'] == 0){print(0);}else{print(round(($row4['EneroNomina']+$row4['FebreroNomina']+$row4['MarzoNomina']+$row4['AbrilNomina']+$row4['MayoNomina']+$row4['JunioNomina'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($junioNom,2));?></td>
					<td></td>
					<td>$<?php print(number_format($nominaMensual,2));?></td>
					<td><?php if($row2['Julio'] == 0){print(0);}else{print(round($nominaMensual/$row2['Julio']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['JulioNomina'],2));?></td>
					<td><?php if($row4['JulioNomina'] == 0 || $row['JulioP'] == 0){print(0);}else{print(round(($row4['JulioNomina']/$row['JulioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['JulioNomina']-$nominaMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($nominaMensual*7,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0){print(0);}else{print(round(($nominaMensual*7)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroNomina']+$row4['FebreroNomina']+$row4['MarzoNomina']+$row4['AbrilNomina']+$row4['MayoNomina']+$row4['JunioNomina']+$row4['JulioNomina'],2));?></td>
					<td><?php if($row4['EneroNomina'] == 0 || $row['EneroP'] == 0 || $row4['FebreroNomina'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoNomina'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilNomina'] == 0 || $row['AbrilP'] == 0 || $row4['MayoNomina'] == 0 || $row['MayoP'] == 0 || $row4['JunioNomina'] == 0 || $row['JunioP'] == 0 || $row4['JulioNomina'] == 0 || $row['JulioP'] == 0){print(0);}else{print(round(($row4['EneroNomina']+$row4['FebreroNomina']+$row4['MarzoNomina']+$row4['AbrilNomina']+$row4['MayoNomina']+$row4['JunioNomina']+$row4['JulioNomina'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($julioNom,2));?></td>
					<td></td>
					<td>$<?php print(number_format($nominaMensual,2));?></td>
					<td><?php if($row2['Agosto'] == 0){print(0);}else{print(round($nominaMensual/$row2['Agosto']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['AgostoNomina'],2));?></td>
					<td><?php if($row4['AgostoNomina'] == 0 || $row['AgostoP'] == 0){print(0);}else{print(round(($row4['AgostoNomina']/$row['AgostoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['AgostoNomina']-$nominaMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($nominaMensual*8,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0 || $row2['Agosto'] == 0){print(0);}else{print(round(($nominaMensual*8)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroNomina']+$row4['FebreroNomina']+$row4['MarzoNomina']+$row4['AbrilNomina']+$row4['MayoNomina']+$row4['JunioNomina']+$row4['JulioNomina']+$row4['AgostoNomina'],2));?></td>
					<td><?php if($row4['EneroNomina'] == 0 || $row['EneroP'] == 0 || $row4['FebreroNomina'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoNomina'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilNomina'] == 0 || $row['AbrilP'] == 0 || $row4['MayoNomina'] == 0 || $row['MayoP'] == 0 || $row4['JunioNomina'] == 0 || $row['JunioP'] == 0 || $row4['JulioNomina'] == 0 || $row['JulioP'] == 0 || $row4['AgostoNomina'] == 0 || $row['AgostoP'] == 0){print(0);}else{print(round(($row4['EneroNomina']+$row4['FebreroNomina']+$row4['MarzoNomina']+$row4['AbrilNomina']+$row4['MayoNomina']+$row4['JunioNomina']+$row4['JulioNomina']+$row4['AgostoNomina'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($agostoNom,2));?></td>
					<td></td>
					<td>$<?php print(number_format($nominaMensual,2));?></td>
					<td><?php if($row2['Septiembre'] == 0){print(0);}else{print(round($nominaMensual/$row2['Septiembre']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['SeptiembreNomina'],2));?></td>
					<td><?php if($row4['SeptiembreNomina'] == 0 || $row['SeptiembreP'] == 0){print(0);}else{print(round(($row4['SeptiembreNomina']/$row['SeptiembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['SeptiembreNomina']-$nominaMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($nominaMensual*9,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0 || $row2['Agosto'] == 0 || $row2['Septiembre'] == 0){print(0);}else{print(round(($nominaMensual*9)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroNomina']+$row4['FebreroNomina']+$row4['MarzoNomina']+$row4['AbrilNomina']+$row4['MayoNomina']+$row4['JunioNomina']+$row4['JulioNomina']+$row4['AgostoNomina']+$row4['SeptiembreNomina'],2));?></td>
					<td><?php if($row4['EneroNomina'] == 0 || $row['EneroP'] == 0 || $row4['FebreroNomina'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoNomina'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilNomina'] == 0 || $row['AbrilP'] == 0 || $row4['MayoNomina'] == 0 || $row['MayoP'] == 0 || $row4['JunioNomina'] == 0 || $row['JunioP'] == 0 || $row4['JulioNomina'] == 0 || $row['JulioP'] == 0 || $row4['AgostoNomina'] == 0 || $row['AgostoP'] == 0 || $row4['SeptiembreNomina'] == 0 || $row['SeptiembreP'] == 0){print(0);}else{print(round(($row4['EneroNomina']+$row4['FebreroNomina']+$row4['MarzoNomina']+$row4['AbrilNomina']+$row4['MayoNomina']+$row4['JunioNomina']+$row4['JulioNomina']+$row4['AgostoNomina']+$row4['SeptiembreNomina'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($septiembreNom,2));?></td>
					<td></td>
					<td>$<?php print(number_format($nominaMensual,2));?></td>
					<td><?php if($row2['Octubre'] == 0){print(0);}else{print(round($nominaMensual/$row2['Octubre']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['OctubreNomina'],2));?></td>
					<td><?php if($row4['OctubreNomina'] == 0 || $row['OctubreP'] == 0){print(0);}else{print(round(($row4['OctubreNomina']/$row['OctubreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['OctubreNomina']-$nominaMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($nominaMensual*10,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0 || $row2['Agosto'] == 0 || $row2['Septiembre'] == 0 || $row2['Octubre'] == 0){print(0);}else{print(round(($nominaMensual*10)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre']+$row2['Octubre'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroNomina']+$row4['FebreroNomina']+$row4['MarzoNomina']+$row4['AbrilNomina']+$row4['MayoNomina']+$row4['JunioNomina']+$row4['JulioNomina']+$row4['AgostoNomina']+$row4['SeptiembreNomina']+$row4['OctubreNomina'],2));?></td>
					<td><?php if($row4['EneroNomina'] == 0 || $row['EneroP'] == 0 || $row4['FebreroNomina'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoNomina'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilNomina'] == 0 || $row['AbrilP'] == 0 || $row4['MayoNomina'] == 0 || $row['MayoP'] == 0 || $row4['JunioNomina'] == 0 || $row['JunioP'] == 0 || $row4['JulioNomina'] == 0 || $row['JulioP'] == 0 || $row4['AgostoNomina'] == 0 || $row['AgostoP'] == 0 || $row4['SeptiembreNomina'] == 0 || $row['SeptiembreP'] == 0 || $row4['OctubreNomina'] == 0 || $row['OctubreP'] == 0){print(0);}else{print(round(($row4['EneroNomina']+$row4['FebreroNomina']+$row4['MarzoNomina']+$row4['AbrilNomina']+$row4['MayoNomina']+$row4['JunioNomina']+$row4['JulioNomina']+$row4['AgostoNomina']+$row4['SeptiembreNomina']+$row4['OctubreNomina'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($octubreNom,2));?></td>
					<td></td>
					<td>$<?php print(number_format($nominaMensual,2));?></td>
					<td><?php if($row2['Noviembre'] == 0){print(0);}else{print(round($nominaMensual/$row2['Noviembre']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['NoviembreNomina'],2));?></td>
					<td><?php if($row4['NoviembreNomina'] == 0 || $row['NoviembreP'] == 0){print(0);}else{print(round(($row4['NoviembreNomina']/$row['NoviembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['NoviembreNomina']-$nominaMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($nominaMensual*11,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0 || $row2['Agosto'] == 0 || $row2['Septiembre'] == 0 || $row2['Octubre'] == 0 || $row2['Noviembre'] == 0){print(0);}else{print(round(($nominaMensual*11)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre']+$row2['Octubre']+$row2['Noviembre'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroNomina']+$row4['FebreroNomina']+$row4['MarzoNomina']+$row4['AbrilNomina']+$row4['MayoNomina']+$row4['JunioNomina']+$row4['JulioNomina']+$row4['AgostoNomina']+$row4['SeptiembreNomina']+$row4['OctubreNomina']+$row4['NoviembreNomina'],2));?></td>
					<td><?php if($row4['EneroNomina'] == 0 || $row['EneroP'] == 0 || $row4['FebreroNomina'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoNomina'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilNomina'] == 0 || $row['AbrilP'] == 0 || $row4['MayoNomina'] == 0 || $row['MayoP'] == 0 || $row4['JunioNomina'] == 0 || $row['JunioP'] == 0 || $row4['JulioNomina'] == 0 || $row['JulioP'] == 0 || $row4['AgostoNomina'] == 0 || $row['AgostoP'] == 0 || $row4['SeptiembreNomina'] == 0 || $row['SeptiembreP'] == 0 || $row4['OctubreNomina'] == 0 || $row['OctubreP'] == 0 || $row4['NoviembreNomina'] == 0 || $row['NoviembreP'] == 0){print(0);}else{print(round(($row4['EneroNomina']+$row4['FebreroNomina']+$row4['MarzoNomina']+$row4['AbrilNomina']+$row4['MayoNomina']+$row4['JunioNomina']+$row4['JulioNomina']+$row4['AgostoNomina']+$row4['SeptiembreNomina']+$row4['OctubreNomina']+$row4['NoviembreNomina'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP']+$row['NoviembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($noviembreNom,2));?></td>
					<td></td>
					<td>$<?php print(number_format($nominaMensual,2));?></td>
					<td><?php if($row2['Diciembre'] == 0){print(0);}else{print(round($nominaMensual/$row2['Diciembre']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['DiciembreNomina'],2));?></td>
					<td><?php if($row4['DiciembreNomina'] == 0 || $row['DiciembreP'] == 0){print(0);}else{print(round(($row4['DiciembreNomina']/$row['DiciembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['DiciembreNomina']-$nominaMensual,2));?></td>
				</tr>
				<!--SAC-->
				<tr>
					<th></th>
					<th>SAC Total</th>
					<td>$<?php print(number_format(($row3['TotalSAC']),2));?></td>
					<td><?php if($row2['TOTAL'] == 0){print(0);}else{print(round($row3['TotalSAC']/$row2['TOTAL']*100,2));}?>%</td>
					<td></td>
					<td>$<?php print(number_format($sacMensual,2));?></td>
					<td><?php if($row2['Enero'] == 0){print(0);}else{print(round($sacMensual/$row2['Enero']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroSAC'],2));?></td>
					<td><?php if($row4['EneroSAC'] == 0 || $row['EneroP'] == 0){print(0);}else{print(round(($row4['EneroSAC']/$row['EneroP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroSAC']-$sacMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($sacMensual,2));?></td>
					<td><?php if($row2['Febrero'] == 0){print(0);}else{print(round($sacMensual/$row2['Febrero']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['FebreroSAC'],2));?></td>
					<td><?php if($row4['FebreroSAC'] == 0 || $row['FebreroP'] == 0){print(0);}else{print(round(($row4['FebreroSAC']/$row['FebreroP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['FebreroSAC']-$sacMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($sacMensual*2,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0){print(0);}else{print(round(($sacMensual*2)/($row2['Enero']+$row2['Febrero'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroSAC']+$row4['FebreroSAC'],2));?></td>
					<td><?php if($row4['EneroSAC'] == 0 || $row['EneroP'] == 0 || $row4['FebreroSAC'] == 0 || $row['FebreroP'] == 0){print(0);}else{print(round(($row4['EneroSAC']+$row4['FebreroSAC'])/($row['EneroP']+$row['FebreroP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($febreroSAC,2));?></td>
					<td></td>
					<td>$<?php print(number_format($sacMensual,2));?></td>
					<td><?php if($row2['Marzo'] == 0){print(0);}else{print(round($sacMensual/$row2['Marzo']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['MarzoSAC'],2));?></td>
					<td><?php if($row4['MarzoSAC'] == 0 || $row['MarzoP'] == 0){print(0);}else{print(round(($row4['MarzoSAC']/$row['MarzoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['MarzoSAC']-$sacMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($sacMensual*3,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0){print(0);}else{print(round(($sacMensual*3)/($row2['Enero']+$row2['Febrero']+$row2['Marzo'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroSAC']+$row4['FebreroSAC']+$row4['MarzoSAC'],2));?></td>
					<td><?php if($row4['EneroSAC'] == 0 || $row['EneroP'] == 0 || $row4['FebreroSAC'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoSAC'] == 0 || $row['MarzoP'] == 0){print(0);}else{print(round(($row4['EneroSAC']+$row4['FebreroSAC']+$row4['MarzoSAC'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($marzoSAC,2));?></td>
					<td></td>
					<td>$<?php print(number_format($sacMensual,2));?></td>
					<td><?php if($row2['Abril'] == 0){print(0);}else{print(round($sacMensual/$row2['Abril']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['AbrilSAC'],2));?></td>
					<td><?php if($row4['AbrilSAC'] == 0 || $row['AbrilP'] == 0){print(0);}else{print(round(($row4['AbrilSAC']/$row['AbrilP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['AbrilSAC']-$sacMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($sacMensual*4,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0){print(0);}else{print(round(($sacMensual*4)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroSAC']+$row4['FebreroSAC']+$row4['MarzoSAC']+$row4['AbrilSAC'],2));?></td>
					<td><?php if($row4['EneroSAC'] == 0 || $row['EneroP'] == 0 || $row4['FebreroSAC'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoSAC'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilSAC'] == 0 || $row['AbrilP'] == 0){print(0);}else{print(round(($row4['EneroSAC']+$row4['FebreroSAC']+$row4['MarzoSAC']+$row4['AbrilSAC'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($abrilSAC,2));?></td>
					<td></td>
					<td>$<?php print(number_format($sacMensual,2));?></td>
					<td><?php if($row2['Mayo'] == 0){print(0);}else{print(round($sacMensual/$row2['Mayo']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['MayoSAC'],2));?></td>
					<td><?php if($row4['MayoSAC'] == 0 || $row['MayoP'] == 0){print(0);}else{print(round(($row4['MayoSAC']/$row['MayoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['MayoSAC']-$sacMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($sacMensual*5,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0){print(0);}else{print(round(($sacMensual*5)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroSAC']+$row4['FebreroSAC']+$row4['MarzoSAC']+$row4['AbrilSAC']+$row4['MayoSAC'],2));?></td>
					<td><?php if($row4['EneroSAC'] == 0 || $row['EneroP'] == 0 || $row4['FebreroSAC'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoSAC'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilSAC'] == 0 || $row['AbrilP'] == 0 || $row4['MayoSAC'] == 0 || $row['MayoP'] == 0){print(0);}else{print(round(($row4['EneroSAC']+$row4['FebreroSAC']+$row4['MarzoSAC']+$row4['AbrilSAC']+$row4['MayoSAC'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($mayoSAC,2));?></td>
					<td></td>
					<td>$<?php print(number_format($sacMensual,2));?></td>
					<td><?php if($row2['Junio'] == 0){print(0);}else{print(round($sacMensual/$row2['Junio']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['JunioSAC'],2));?></td>
					<td><?php if($row4['JunioSAC'] == 0 || $row['JunioP'] == 0){print(0);}else{print(round(($row4['JunioSAC']/$row['JunioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['JunioSAC']-$sacMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($sacMensual*6,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0){print(0);}else{print(round(($sacMensual*6)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroSAC']+$row4['FebreroSAC']+$row4['MarzoSAC']+$row4['AbrilSAC']+$row4['MayoSAC']+$row4['JunioSAC'],2));?></td>
					<td><?php if($row4['EneroSAC'] == 0 || $row['EneroP'] == 0 || $row4['FebreroSAC'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoSAC'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilSAC'] == 0 || $row['AbrilP'] == 0 || $row4['MayoSAC'] == 0 || $row['MayoP'] == 0 || $row4['JunioSAC'] == 0 || $row['JunioP'] == 0){print(0);}else{print(round(($row4['EneroSAC']+$row4['FebreroSAC']+$row4['MarzoSAC']+$row4['AbrilSAC']+$row4['MayoSAC']+$row4['JunioSAC'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($junioSAC,2));?></td>
					<td></td>
					<td>$<?php print(number_format($sacMensual,2));?></td>
					<td><?php if($row2['Julio'] == 0){print(0);}else{print(round($sacMensual/$row2['Julio']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['JulioSAC'],2));?></td>
					<td><?php if($row4['JulioSAC'] == 0 || $row['JulioP'] == 0){print(0);}else{print(round(($row4['JulioSAC']/$row['JulioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['JulioSAC']-$sacMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($sacMensual*7,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0){print(0);}else{print(round(($sacMensual*7)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroSAC']+$row4['FebreroSAC']+$row4['MarzoSAC']+$row4['AbrilSAC']+$row4['MayoSAC']+$row4['JunioSAC']+$row4['JulioSAC'],2));?></td>
					<td><?php if($row4['EneroSAC'] == 0 || $row['EneroP'] == 0 || $row4['FebreroSAC'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoSAC'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilSAC'] == 0 || $row['AbrilP'] == 0 || $row4['MayoSAC'] == 0 || $row['MayoP'] == 0 || $row4['JunioSAC'] == 0 || $row['JunioP'] == 0 || $row4['JulioSAC'] == 0 || $row['JulioP'] == 0){print(0);}else{print(round(($row4['EneroSAC']+$row4['FebreroSAC']+$row4['MarzoSAC']+$row4['AbrilSAC']+$row4['MayoSAC']+$row4['JunioSAC']+$row4['JulioSAC'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($julioSAC,2));?></td>
					<td></td>
					<td>$<?php print(number_format($sacMensual,2));?></td>
					<td><?php if($row2['Agosto'] == 0){print(0);}else{print(round($sacMensual/$row2['Agosto']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['AgostoSAC'],2));?></td>
					<td><?php if($row4['AgostoSAC'] == 0 || $row['AgostoP'] == 0){print(0);}else{print(round(($row4['AgostoSAC']/$row['AgostoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['AgostoSAC']-$sacMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($sacMensual*8,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0 || $row2['Agosto'] == 0){print(0);}else{print(round(($sacMensual*8)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroSAC']+$row4['FebreroSAC']+$row4['MarzoSAC']+$row4['AbrilSAC']+$row4['MayoSAC']+$row4['JunioSAC']+$row4['JulioSAC']+$row4['AgostoSAC'],2));?></td>
					<td><?php if($row4['EneroSAC'] == 0 || $row['EneroP'] == 0 || $row4['FebreroSAC'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoSAC'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilSAC'] == 0 || $row['AbrilP'] == 0 || $row4['MayoSAC'] == 0 || $row['MayoP'] == 0 || $row4['JunioSAC'] == 0 || $row['JunioP'] == 0 || $row4['JulioSAC'] == 0 || $row['JulioP'] == 0 || $row4['AgostoSAC'] == 0 || $row['AgostoP'] == 0){print(0);}else{print(round(($row4['EneroSAC']+$row4['FebreroSAC']+$row4['MarzoSAC']+$row4['AbrilSAC']+$row4['MayoSAC']+$row4['JunioSAC']+$row4['JulioSAC']+$row4['AgostoSAC'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($agostoSAC,2));?></td>
					<td></td>
					<td>$<?php print(number_format($sacMensual,2));?></td>
					<td><?php if($row2['Septiembre'] == 0){print(0);}else{print(round($sacMensual/$row2['Septiembre']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['SeptiembreSAC'],2));?></td>
					<td><?php if($row4['SeptiembreSAC'] == 0 || $row['SeptiembreP'] == 0){print(0);}else{print(round(($row4['SeptiembreSAC']/$row['SeptiembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['SeptiembreSAC']-$sacMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($sacMensual*9,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0 || $row2['Agosto'] == 0 || $row2['Septiembre'] == 0){print(0);}else{print(round(($sacMensual*9)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroSAC']+$row4['FebreroSAC']+$row4['MarzoSAC']+$row4['AbrilSAC']+$row4['MayoSAC']+$row4['JunioSAC']+$row4['JulioSAC']+$row4['AgostoSAC']+$row4['SeptiembreSAC'],2));?></td>
					<td><?php if($row4['EneroSAC'] == 0 || $row['EneroP'] == 0 || $row4['FebreroSAC'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoSAC'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilSAC'] == 0 || $row['AbrilP'] == 0 || $row4['MayoSAC'] == 0 || $row['MayoP'] == 0 || $row4['JunioSAC'] == 0 || $row['JunioP'] == 0 || $row4['JulioSAC'] == 0 || $row['JulioP'] == 0 || $row4['AgostoSAC'] == 0 || $row['AgostoP'] == 0 || $row4['SeptiembreSAC'] == 0 || $row['SeptiembreP'] == 0){print(0);}else{print(round(($row4['EneroSAC']+$row4['FebreroSAC']+$row4['MarzoSAC']+$row4['AbrilSAC']+$row4['MayoSAC']+$row4['JunioSAC']+$row4['JulioSAC']+$row4['AgostoSAC']+$row4['SeptiembreSAC'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($septiembreSAC,2));?></td>
					<td></td>
					<td>$<?php print(number_format($sacMensual,2));?></td>
					<td><?php if($row2['Octubre'] == 0){print(0);}else{print(round($sacMensual/$row2['Octubre']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['OctubreSAC'],2));?></td>
					<td><?php if($row4['OctubreSAC'] == 0 || $row['OctubreP'] == 0){print(0);}else{print(round(($row4['OctubreSAC']/$row['OctubreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['OctubreSAC']-$sacMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($sacMensual*10,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0 || $row2['Agosto'] == 0 || $row2['Septiembre'] == 0 || $row2['Octubre'] == 0){print(0);}else{print(round(($sacMensual*10)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre']+$row2['Octubre'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroSAC']+$row4['FebreroSAC']+$row4['MarzoSAC']+$row4['AbrilSAC']+$row4['MayoSAC']+$row4['JunioSAC']+$row4['JulioSAC']+$row4['AgostoSAC']+$row4['SeptiembreSAC']+$row4['OctubreSAC'],2));?></td>
					<td><?php if($row4['EneroSAC'] == 0 || $row['EneroP'] == 0 || $row4['FebreroSAC'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoSAC'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilSAC'] == 0 || $row['AbrilP'] == 0 || $row4['MayoSAC'] == 0 || $row['MayoP'] == 0 || $row4['JunioSAC'] == 0 || $row['JunioP'] == 0 || $row4['JulioSAC'] == 0 || $row['JulioP'] == 0 || $row4['AgostoSAC'] == 0 || $row['AgostoP'] == 0 || $row4['SeptiembreSAC'] == 0 || $row['SeptiembreP'] == 0 || $row4['OctubreSAC'] == 0 || $row['OctubreP'] == 0){print(0);}else{print(round(($row4['EneroSAC']+$row4['FebreroSAC']+$row4['MarzoSAC']+$row4['AbrilSAC']+$row4['MayoSAC']+$row4['JunioSAC']+$row4['JulioSAC']+$row4['AgostoSAC']+$row4['SeptiembreSAC']+$row4['OctubreSAC'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($octubreSAC,2));?></td>
					<td></td>
					<td>$<?php print(number_format($sacMensual,2));?></td>
					<td><?php if($row2['Noviembre'] == 0){print(0);}else{print(round($sacMensual/$row2['Noviembre']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['NoviembreSAC'],2));?></td>
					<td><?php if($row4['NoviembreSAC'] == 0 || $row['NoviembreP'] == 0){print(0);}else{print(round(($row4['NoviembreSAC']/$row['NoviembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['NoviembreSAC']-$sacMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($sacMensual*11,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0 || $row2['Agosto'] == 0 || $row2['Septiembre'] == 0 || $row2['Octubre'] == 0 || $row2['Noviembre'] == 0){print(0);}else{print(round(($sacMensual*11)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre']+$row2['Octubre']+$row2['Noviembre'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroSAC']+$row4['FebreroSAC']+$row4['MarzoSAC']+$row4['AbrilSAC']+$row4['MayoSAC']+$row4['JunioSAC']+$row4['JulioSAC']+$row4['AgostoSAC']+$row4['SeptiembreSAC']+$row4['OctubreSAC']+$row4['NoviembreSAC'],2));?></td>
					<td><?php if($row4['EneroSAC'] == 0 || $row['EneroP'] == 0 || $row4['FebreroSAC'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoSAC'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilSAC'] == 0 || $row['AbrilP'] == 0 || $row4['MayoSAC'] == 0 || $row['MayoP'] == 0 || $row4['JunioSAC'] == 0 || $row['JunioP'] == 0 || $row4['JulioSAC'] == 0 || $row['JulioP'] == 0 || $row4['AgostoSAC'] == 0 || $row['AgostoP'] == 0 || $row4['SeptiembreSAC'] == 0 || $row['SeptiembreP'] == 0 || $row4['OctubreSAC'] == 0 || $row['OctubreP'] == 0 || $row4['NoviembreSAC'] == 0 || $row['NoviembreP'] == 0){print(0);}else{print(round(($row4['EneroSAC']+$row4['FebreroSAC']+$row4['MarzoSAC']+$row4['AbrilSAC']+$row4['MayoSAC']+$row4['JunioSAC']+$row4['JulioSAC']+$row4['AgostoSAC']+$row4['SeptiembreSAC']+$row4['OctubreSAC']+$row4['NoviembreSAC'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP']+$row['NoviembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($noviembreSAC,2));?></td>
					<td></td>
					<td>$<?php print(number_format($sacMensual,2));?></td>
					<td><?php if($row2['Diciembre'] == 0){print(0);}else{print(round($sacMensual/$row2['Diciembre']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['DiciembreSAC'],2));?></td>
					<td><?php if($row4['DiciembreSAC'] == 0 || $row['DiciembreP'] == 0){print(0);}else{print(round(($row4['DiciembreSAC']/$row['DiciembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['DiciembreSAC']-$sacMensual,2));?></td>
				</tr>
				<!--INTERESES-->
				<tr>
					<th></th>
					<th>Intereses Totales</th>
					<td>$<?php print(number_format(($row3['TotalIntereses']),2));?></td>
					<td><?php if($row2['TOTAL'] == 0){print(0);}else{print(round($row3['TotalIntereses']/$row2['TOTAL']*100,2));}?>%</td>
					<td></td>
					<td>$<?php print(number_format($interesesMensual,2));?></td>
					<td>0%</td>
					<td>$0.00</td>
					<td>0%</td>
					<td>$0.00</td>
					<td></td>
					<td>$<?php print(number_format($interesesMensual,2));?></td>
					<td>0%</td>
					<td>$0.00</td>
					<td>0%</td>
					<td>$0.00</td>
					<td></td>
					<td>$<?php print(number_format($interesesMensual*2,2));?></td>
					<td>0%</td>
					<td>$0.00</td>
					<td>0%</td>
					<td>$0.00</td>
					<td></td>
					<td>$<?php print(number_format($interesesMensual,2));?></td>
					<td>0%</td>
					<td>$0.00</td>
					<td>0%</td>
					<td>$0.00</td>
					<td></td>
					<td>$<?php print(number_format($interesesMensual*3,2));?></td>
					<td>0%</td>
					<td>$0.00</td>
					<td>0%</td>
					<td>$0.00</td>
					<td></td>
					<td>$<?php print(number_format($interesesMensual,2));?></td>
					<td>0%</td>
					<td>$<?php print(number_format($intereses4R,2));?></td>
					<td><?php if($row['AbrilP'] == 0){print(0);}else{print(round(($intereses4R/$row['AbrilP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($diferencia4Intereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($interesesMensual*4,2));?></td>
					<td>0%</td>
					<td>$<?php print(number_format($intereses4A,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0){print(0);}else{print(round(($intereses4A)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($abrilIntereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($interesesMensual,2));?></td>
					<td>0%</td>
					<td>$<?php print(number_format($intereses5R,2));?></td>
					<td><?php if($row['MayoP'] == 0){print(0);}else{print(round(($intereses5R/$row['MayoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($diferencia5Intereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($interesesMensual*5,2));?></td>
					<td>0%</td>
					<td>$<?php print(number_format($intereses5A,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0){print(0);}else{print(round(($intereses5A)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($mayoIntereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($interesesMensual,2));?></td>
					<td>0%</td>
					<td>$<?php print(number_format($intereses6R,2));?></td>
					<td><?php if($row['JunioP'] == 0){print(0);}else{print(round(($intereses6R/$row['JunioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($diferencia6Intereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($interesesMensual*6,2));?></td>
					<td>0%</td>
					<td>$<?php print(number_format($intereses6A,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0){print(0);}else{print(round(($intereses6A)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($junioIntereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($interesesMensual,2));?></td>
					<td>0%</td>
					<td>$<?php print(number_format($intereses7R,2));?></td>
					<td><?php if($row['JulioP'] == 0){print(0);}else{print(round(($intereses7R/$row['JulioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($diferencia7Intereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($interesesMensual*7,2));?></td>
					<td>0%</td>
					<td>$<?php print(number_format($intereses7A,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0 || $row['JulioP'] == 0){print(0);}else{print(round(($intereses7A)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($julioIntereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($interesesMensual,2));?></td>
					<td>0%</td>
					<td>$<?php print(number_format($intereses8R,2));?></td>
					<td><?php if($row['AgostoP'] == 0){print(0);}else{print(round(($intereses8R/$row['AgostoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($diferencia8Intereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($interesesMensual*8,2));?></td>
					<td>0%</td>
					<td>$<?php print(number_format($intereses8A,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0 || $row['JulioP'] == 0 || $row['AgostoP'] == 0){print(0);}else{print(round(($intereses8A)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($agostoIntereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($interesesMensual,2));?></td>
					<td>0%</td>
					<td>$<?php print(number_format($intereses9R,2));?></td>
					<td><?php if($row['SeptiembreP'] == 0){print(0);}else{print(round(($intereses9R/$row['SeptiembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($diferencia9Intereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($interesesMensual*9,2));?></td>
					<td>0%</td>
					<td>$<?php print(number_format($intereses9A,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0 || $row['JulioP'] == 0  || $row['AgostoP'] == 0 ||  $row['SeptiembreP'] == 0){print(0);}else{print(round(($intereses9A)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($septiembreIntereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($interesesMensual,2));?></td>
					<td>0%</td>
					<td>$<?php print(number_format($intereses10R,2));?></td>
					<td><?php if($row['OctubreP'] == 0){print(0);}else{print(round(($intereses10R/$row['OctubreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($diferencia10Intereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($interesesMensual*10,2));?></td>
					<td>0%</td>
					<td>$<?php print(number_format($intereses10A,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0  || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0 || $row['JulioP'] == 0 || $row['AgostoP'] == 0 || $row['SeptiembreP'] == 0 || $row['OctubreP'] == 0){print(0);}else{print(round(($intereses10A)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($octubreIntereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($interesesMensual,2));?></td>
					<td>0%</td>
					<td>$<?php print(number_format($intereses11R,2));?></td>
					<td><?php if($row['NoviembreP'] == 0){print(0);}else{print(round(($intereses11R/$row['NoviembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($diferencia11Intereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($interesesMensual*11,2));?></td>
					<td>0%</td>
					<td>$<?php print(number_format($intereses11A,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0  || $row['JulioP'] == 0  || $row['AgostoP'] == 0  || $row['SeptiembreP'] == 0  || $row['OctubreP'] == 0 || $row['NoviembreP'] == 0){print(0);}else{print(round(($intereses11A)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP']+$row['NoviembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($noviembreIntereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($interesesMensual,2));?></td>
					<td>0%</td>
					<td>$<?php print(number_format($intereses12R,2));?></td>
					<td><?php if($row['DiciembreP'] == 0){print(0);}else{print(round(($intereses12R/$row['DiciembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($diferencia12Intereses,2));?></td>
				</tr>
				<!--OTROS-->
				<tr>
					<th></th>
					<th>Otros</th>
					<td>$<?php print(number_format(($row3['TotalOtros']),2));?></td>
					<td><?php if($row2['TOTAL'] == 0){print(0);}else{print(round($row3['TotalOtros']/$row2['TOTAL']*100,2));}?>%</td>
					<td></td>
					<td>$<?php print(number_format($otrosMensual,2));?></td>
					<td><?php if($row2['Enero'] == 0){print(0);}else{print(round($otrosMensual/$row2['Enero']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroOtros'],2));?></td>
					<td><?php if($row4['EneroOtros'] == 0 || $row['EneroP'] == 0){print(0);}else{print(round(($row4['EneroOtros']/$row['EneroP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroOtros']-$otrosMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($otrosMensual,2));?></td>
					<td><?php if($row2['Febrero'] == 0){print(0);}else{print(round($otrosMensual/$row2['Febrero']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['FebreroOtros'],2));?></td>
					<td><?php if($row4['FebreroOtros'] == 0 || $row['FebreroP'] == 0){print(0);}else{print(round(($row4['FebreroOtros']/$row['FebreroP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['FebreroOtros']-$otrosMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($otrosMensual*2,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0){print(0);}else{print(round(($otrosMensual*2)/($row2['Enero']+$row2['Febrero'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroOtros']+$row4['FebreroOtros'],2));?></td>
					<td><?php if($row4['EneroOtros'] == 0 || $row['EneroP'] == 0 || $row4['FebreroOtros'] == 0 || $row['FebreroP'] == 0){print(0);}else{print(round(($row4['EneroOtros']+$row4['FebreroOtros'])/($row['EneroP']+$row['FebreroP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($febreroOtros,2));?></td>
					<td></td>
					<td>$<?php print(number_format($otrosMensual,2));?></td>
					<td><?php if($row2['Marzo'] == 0){print(0);}else{print(round($otrosMensual/$row2['Marzo']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['MarzoOtros'],2));?></td>
					<td><?php if($row4['MarzoOtros'] == 0 || $row['MarzoP'] == 0){print(0);}else{print(round(($row4['MarzoOtros']/$row['MarzoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['MarzoOtros']-$otrosMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($otrosMensual*3,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0){print(0);}else{print(round(($otrosMensual*3)/($row2['Enero']+$row2['Febrero']+$row2['Marzo'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroOtros']+$row4['FebreroOtros']+$row4['MarzoOtros'],2));?></td>
					<td><?php if($row4['EneroOtros'] == 0 || $row['EneroP'] == 0 || $row4['FebreroOtros'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoOtros'] == 0 || $row['MarzoP'] == 0){print(0);}else{print(round(($row4['EneroOtros']+$row4['FebreroOtros']+$row4['MarzoOtros'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($marzoOtros,2));?></td>
					<td></td>
					<td>$<?php print(number_format($otrosMensual,2));?></td>
					<td><?php if($row2['Abril'] == 0){print(0);}else{print(round($otrosMensual/$row2['Abril']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['AbrilOtros'],2));?></td>
					<td><?php if($row4['AbrilOtros'] == 0 || $row['AbrilP'] == 0){print(0);}else{print(round(($row4['AbrilOtros']/$row['AbrilP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['AbrilOtros']-$otrosMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($otrosMensual*4,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0){print(0);}else{print(round(($otrosMensual*4)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroOtros']+$row4['FebreroOtros']+$row4['MarzoOtros']+$row4['AbrilOtros'],2));?></td>
					<td><?php if($row4['EneroOtros'] == 0 || $row['EneroP'] == 0 || $row4['FebreroOtros'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoOtros'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilOtros'] == 0 || $row['AbrilP'] == 0){print(0);}else{print(round(($row4['EneroOtros']+$row4['FebreroOtros']+$row4['MarzoOtros']+$row4['AbrilOtros'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($abrilOtros,2));?></td>
					<td></td>
					<td>$<?php print(number_format($otrosMensual,2));?></td>
					<td><?php if($row2['Mayo'] == 0){print(0);}else{print(round($otrosMensual/$row2['Mayo']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['MayoOtros'],2));?></td>
					<td><?php if($row4['MayoOtros'] == 0 || $row['MayoP'] == 0){print(0);}else{print(round(($row4['MayoOtros']/$row['MayoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['MayoOtros']-$otrosMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($otrosMensual*5,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0){print(0);}else{print(round(($otrosMensual*5)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroOtros']+$row4['FebreroOtros']+$row4['MarzoOtros']+$row4['AbrilOtros']+$row4['MayoOtros'],2));?></td>
					<td><?php if($row4['EneroOtros'] == 0 || $row['EneroP'] == 0 || $row4['FebreroOtros'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoOtros'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilOtros'] == 0 || $row['AbrilP'] == 0 || $row4['MayoOtros'] == 0 || $row['MayoP'] == 0){print(0);}else{print(round(($row4['EneroOtros']+$row4['FebreroOtros']+$row4['MarzoOtros']+$row4['AbrilOtros']+$row4['MayoOtros'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($mayoOtros,2));?></td>
					<td></td>
					<td>$<?php print(number_format($otrosMensual,2));?></td>
					<td><?php if($row2['Junio'] == 0){print(0);}else{print(round($otrosMensual/$row2['Junio']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['JunioOtros'],2));?></td>
					<td><?php if($row4['JunioOtros'] == 0 || $row['JunioP'] == 0){print(0);}else{print(round(($row4['JunioOtros']/$row['JunioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['JunioOtros']-$otrosMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($otrosMensual*6,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0){print(0);}else{print(round(($otrosMensual*6)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroOtros']+$row4['FebreroOtros']+$row4['MarzoOtros']+$row4['AbrilOtros']+$row4['MayoOtros']+$row4['JunioOtros'],2));?></td>
					<td><?php if($row4['EneroOtros'] == 0 || $row['EneroP'] == 0 || $row4['FebreroOtros'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoOtros'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilOtros'] == 0 || $row['AbrilP'] == 0 || $row4['MayoOtros'] == 0 || $row['MayoP'] == 0 || $row4['JunioOtros'] == 0 || $row['JunioP'] == 0){print(0);}else{print(round(($row4['EneroOtros']+$row4['FebreroOtros']+$row4['MarzoOtros']+$row4['AbrilOtros']+$row4['MayoOtros']+$row4['JunioOtros'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($junioOtros,2));?></td>
					<td></td>
					<td>$<?php print(number_format($otrosMensual,2));?></td>
					<td><?php if($row2['Julio'] == 0){print(0);}else{print(round($otrosMensual/$row2['Julio']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['JulioOtros'],2));?></td>
					<td><?php if($row4['JulioOtros'] == 0 || $row['JulioP'] == 0){print(0);}else{print(round(($row4['JulioOtros']/$row['JulioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['JulioOtros']-$otrosMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($otrosMensual*7,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0){print(0);}else{print(round(($otrosMensual*7)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroOtros']+$row4['FebreroOtros']+$row4['MarzoOtros']+$row4['AbrilOtros']+$row4['MayoOtros']+$row4['JunioOtros']+$row4['JulioOtros'],2));?></td>
					<td><?php if($row4['EneroOtros'] == 0 || $row['EneroP'] == 0 || $row4['FebreroOtros'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoOtros'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilOtros'] == 0 || $row['AbrilP'] == 0 || $row4['MayoOtros'] == 0 || $row['MayoP'] == 0 || $row4['JunioOtros'] == 0 || $row['JunioP'] == 0 || $row4['JulioOtros'] == 0 || $row['JulioP'] == 0){print(0);}else{print(round(($row4['EneroOtros']+$row4['FebreroOtros']+$row4['MarzoOtros']+$row4['AbrilOtros']+$row4['MayoOtros']+$row4['JunioOtros']+$row4['JulioOtros'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($julioOtros,2));?></td>
					<td></td>
					<td>$<?php print(number_format($otrosMensual,2));?></td>
					<td><?php if($row2['Agosto'] == 0){print(0);}else{print(round($otrosMensual/$row2['Agosto']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['AgostoOtros'],2));?></td>
					<td><?php if($row4['AgostoOtros'] == 0 || $row['AgostoP'] == 0){print(0);}else{print(round(($row4['AgostoOtros']/$row['AgostoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['AgostoOtros']-$otrosMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($otrosMensual*8,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0 || $row2['Agosto'] == 0){print(0);}else{print(round(($otrosMensual*8)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroOtros']+$row4['FebreroOtros']+$row4['MarzoOtros']+$row4['AbrilOtros']+$row4['MayoOtros']+$row4['JunioOtros']+$row4['JulioOtros']+$row4['AgostoOtros'],2));?></td>
					<td><?php if($row4['EneroOtros'] == 0 || $row['EneroP'] == 0 || $row4['FebreroOtros'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoOtros'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilOtros'] == 0 || $row['AbrilP'] == 0 || $row4['MayoOtros'] == 0 || $row['MayoP'] == 0 || $row4['JunioOtros'] == 0 || $row['JunioP'] == 0 || $row4['JulioOtros'] == 0 || $row['JulioP'] == 0 || $row4['AgostoOtros'] == 0 || $row['AgostoP'] == 0){print(0);}else{print(round(($row4['EneroOtros']+$row4['FebreroOtros']+$row4['MarzoOtros']+$row4['AbrilOtros']+$row4['MayoOtros']+$row4['JunioOtros']+$row4['JulioOtros']+$row4['AgostoOtros'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($agostoOtros,2));?></td>
					<td></td>
					<td>$<?php print(number_format($otrosMensual,2));?></td>
					<td><?php if($row2['Septiembre'] == 0){print(0);}else{print(round($otrosMensual/$row2['Septiembre']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['SeptiembreOtros'],2));?></td>
					<td><?php if($row4['SeptiembreOtros'] == 0 || $row['SeptiembreP'] == 0){print(0);}else{print(round(($row4['SeptiembreOtros']/$row['SeptiembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['SeptiembreOtros']-$otrosMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($otrosMensual*9,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0 || $row2['Agosto'] == 0 || $row2['Septiembre'] == 0){print(0);}else{print(round(($otrosMensual*9)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroOtros']+$row4['FebreroOtros']+$row4['MarzoOtros']+$row4['AbrilOtros']+$row4['MayoOtros']+$row4['JunioOtros']+$row4['JulioOtros']+$row4['AgostoOtros']+$row4['SeptiembreOtros'],2));?></td>
					<td><?php if($row4['EneroOtros'] == 0 || $row['EneroP'] == 0 || $row4['FebreroOtros'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoOtros'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilOtros'] == 0 || $row['AbrilP'] == 0 || $row4['MayoOtros'] == 0 || $row['MayoP'] == 0 || $row4['JunioOtros'] == 0 || $row['JunioP'] == 0 || $row4['JulioOtros'] == 0 || $row['JulioP'] == 0 || $row4['AgostoOtros'] == 0 || $row['AgostoP'] == 0 || $row4['SeptiembreOtros'] == 0 || $row['SeptiembreP'] == 0){print(0);}else{print(round(($row4['EneroOtros']+$row4['FebreroOtros']+$row4['MarzoOtros']+$row4['AbrilOtros']+$row4['MayoOtros']+$row4['JunioOtros']+$row4['JulioOtros']+$row4['AgostoOtros']+$row4['SeptiembreOtros'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($septiembreOtros,2));?></td>
					<td></td>
					<td>$<?php print(number_format($otrosMensual,2));?></td>
					<td><?php if($row2['Octubre'] == 0){print(0);}else{print(round($otrosMensual/$row2['Octubre']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['OctubreOtros'],2));?></td>
					<td><?php if($row4['OctubreOtros'] == 0 || $row['OctubreP'] == 0){print(0);}else{print(round(($row4['OctubreOtros']/$row['OctubreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['OctubreOtros']-$otrosMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($otrosMensual*10,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0 || $row2['Agosto'] == 0 || $row2['Septiembre'] == 0 || $row2['Octubre'] == 0){print(0);}else{print(round(($otrosMensual*10)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre']+$row2['Octubre'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroOtros']+$row4['FebreroOtros']+$row4['MarzoOtros']+$row4['AbrilOtros']+$row4['MayoOtros']+$row4['JunioOtros']+$row4['JulioOtros']+$row4['AgostoOtros']+$row4['SeptiembreOtros']+$row4['OctubreOtros'],2));?></td>
					<td><?php if($row4['EneroOtros'] == 0 || $row['EneroP'] == 0 || $row4['FebreroOtros'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoOtros'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilOtros'] == 0 || $row['AbrilP'] == 0 || $row4['MayoOtros'] == 0 || $row['MayoP'] == 0 || $row4['JunioOtros'] == 0 || $row['JunioP'] == 0 || $row4['JulioOtros'] == 0 || $row['JulioP'] == 0 || $row4['AgostoOtros'] == 0 || $row['AgostoP'] == 0 || $row4['SeptiembreOtros'] == 0 || $row['SeptiembreP'] == 0 || $row4['OctubreOtros'] == 0 || $row['OctubreP'] == 0){print(0);}else{print(round(($row4['EneroOtros']+$row4['FebreroOtros']+$row4['MarzoOtros']+$row4['AbrilOtros']+$row4['MayoOtros']+$row4['JunioOtros']+$row4['JulioOtros']+$row4['AgostoOtros']+$row4['SeptiembreOtros']+$row4['OctubreOtros'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($octubreOtros,2));?></td>
					<td></td>
					<td>$<?php print(number_format($otrosMensual,2));?></td>
					<td><?php if($row2['Noviembre'] == 0){print(0);}else{print(round($otrosMensual/$row2['Noviembre']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['NoviembreOtros'],2));?></td>
					<td><?php if($row4['NoviembreOtros'] == 0 || $row['NoviembreP'] == 0){print(0);}else{print(round(($row4['NoviembreOtros']/$row['NoviembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['NoviembreOtros']-$otrosMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($otrosMensual*11,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0 || $row2['Agosto'] == 0 || $row2['Septiembre'] == 0 || $row2['Octubre'] == 0 || $row2['Noviembre'] == 0){print(0);}else{print(round(($otrosMensual*11)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre']+$row2['Octubre']+$row2['Noviembre'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroOtros']+$row4['FebreroOtros']+$row4['MarzoOtros']+$row4['AbrilOtros']+$row4['MayoOtros']+$row4['JunioOtros']+$row4['JulioOtros']+$row4['AgostoOtros']+$row4['SeptiembreOtros']+$row4['OctubreOtros']+$row4['NoviembreOtros'],2));?></td>
					<td><?php if($row4['EneroOtros'] == 0 || $row['EneroP'] == 0 || $row4['FebreroOtros'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoOtros'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilOtros'] == 0 || $row['AbrilP'] == 0 || $row4['MayoOtros'] == 0 || $row['MayoP'] == 0 || $row4['JunioOtros'] == 0 || $row['JunioP'] == 0 || $row4['JulioOtros'] == 0 || $row['JulioP'] == 0 || $row4['AgostoOtros'] == 0 || $row['AgostoP'] == 0 || $row4['SeptiembreOtros'] == 0 || $row['SeptiembreP'] == 0 || $row4['OctubreOtros'] == 0 || $row['OctubreP'] == 0 || $row4['NoviembreOtros'] == 0 || $row['NoviembreP'] == 0){print(0);}else{print(round(($row4['EneroOtros']+$row4['FebreroOtros']+$row4['MarzoOtros']+$row4['AbrilOtros']+$row4['MayoOtros']+$row4['JunioOtros']+$row4['JulioOtros']+$row4['AgostoOtros']+$row4['SeptiembreOtros']+$row4['OctubreOtros']+$row4['NoviembreOtros'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP']+$row['NoviembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($noviembreOtros,2));?></td>
					<td></td>
					<td>$<?php print(number_format($otrosMensual,2));?></td>
					<td><?php if($row2['Diciembre'] == 0){print(0);}else{print(round($otrosMensual/$row2['Diciembre']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['DiciembreOtros'],2));?></td>
					<td><?php if($row4['DiciembreOtros'] == 0 || $row['DiciembreP'] == 0){print(0);}else{print(round(($row4['DiciembreOtros']/$row['DiciembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['DiciembreOtros']-$otrosMensual,2));?></td>
				</tr>
				<!--GAO FIJO-->
				<tr>
					<th colspan="2">GAO Fijo Total</th>
					<td>$<?php print(number_format(($row3['TotalGAO']),2));?></td>
					<td><?php if($row2['TOTAL'] == 0){print(0);}else{print(round($row3['TotalGAO']/$row2['TOTAL']*100,2));}?>%</td>
					<td></td>
					<td>$<?php print(number_format($gaoMensual,2));?></td>
					<td><?php if($row2['Enero'] == 0){print(0);}else{print(round($gaoMensual/$row2['Enero']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroGAO'],2));?></td>
					<td><?php if($row4['EneroGAO'] == 0 || $row['EneroP'] == 0){print(0);}else{print(round((($row4['EneroGAO'])/$row['EneroP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroGAO']-$gaoMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($gaoMensual,2));?></td>
					<td><?php if($row2['Febrero'] == 0){print(0);}else{print(round($gaoMensual/$row2['Febrero']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['FebreroGAO'],2));?></td>
					<td><?php if($row4['FebreroGAO'] == 0 || $row['FebreroP'] == 0){print(0);}else{print(round((($row4['FebreroGAO'])/$row['FebreroP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['FebreroGAO']-$gaoMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($gaoMensual*2,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0){print(0);}else{print(round(($gaoMensual*2)/($row2['Enero']+$row2['Febrero'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroGAO']+$row4['FebreroGAO'],2));?></td>
					<td><?php if($row4['EneroGAO'] == 0 || $row['EneroP'] == 0 || $row4['FebreroGAO'] == 0 || $row['FebreroP'] == 0){print(0);}else{print(round(($row4['EneroGAO']+$row4['FebreroGAO'])/($row['EneroP']+$row['FebreroP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($febreroGAO,2));?></td>
					<td></td>
					<td>$<?php print(number_format($gaoMensual,2));?></td>
					<td><?php if($row2['Marzo'] == 0){print(0);}else{print(round($gaoMensual/$row2['Marzo']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['MarzoGAO'],2));?></td>
					<td><?php if($row4['MarzoGAO'] == 0 || $row['MarzoP'] == 0){print(0);}else{print(round((($row4['MarzoGAO'])/$row['MarzoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['MarzoGAO']-$gaoMensual,2));?></td>
					<td></td>
					<td>$<?php print(number_format($gaoMensual*3,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0){print(0);}else{print(round(($gaoMensual*3)/($row2['Enero']+$row2['Febrero']+$row2['Marzo'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroGAO']+$row4['FebreroGAO']+$row4['MarzoGAO'],2));?></td>
					<td><?php if($row4['EneroGAO'] == 0 || $row['EneroP'] == 0 || $row4['FebreroGAO'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoGAO'] == 0 || $row['MarzoP'] == 0){print(0);}else{print(round(($row4['EneroGAO']+$row4['FebreroGAO']+$row4['MarzoGAO'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($marzoGAO,2));?></td>
					<td></td>
					<td>$<?php print(number_format($gaoMensual,2));?></td>
					<td><?php if($row2['Abril'] == 0){print(0);}else{print(round($gaoMensual/$row2['Abril']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['AbrilGAO']+$intereses4R,2));?></td>
					<td><?php if($row4['AbrilGAO'] == 0 || $row['AbrilP'] == 0){print(0);}else{print(round((($row4['AbrilGAO']+$intereses4R)/$row['AbrilP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['AbrilGAO']-$gaoMensual+$diferencia4Intereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($gaoMensual*4,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0){print(0);}else{print(round(($gaoMensual*4)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroGAO']+$row4['FebreroGAO']+$row4['MarzoGAO']+$row4['AbrilGAO']+$intereses4A,2));?></td>
					<td><?php if($row4['EneroGAO'] == 0 || $row['EneroP'] == 0 || $row4['FebreroGAO'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoGAO'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilGAO'] == 0 || $row['AbrilP'] == 0){print(0);}else{print(round(($intereses4A+$row4['EneroGAO']+$row4['FebreroGAO']+$row4['MarzoGAO']+$row4['AbrilGAO'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($abrilGAO,2));?></td>
					<td></td>
					<td>$<?php print(number_format($gaoMensual,2));?></td>
					<td><?php if($row2['Mayo'] == 0){print(0);}else{print(round($gaoMensual/$row2['Mayo']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['MayoGAO']+$intereses5R,2));?></td>
					<td><?php if($row4['MayoGAO'] == 0 || $row['MayoP'] == 0){print(0);}else{print(round((($row4['MayoGAO']+$intereses5R)/$row['MayoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['MayoGAO']-$gaoMensual+$diferencia5Intereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($gaoMensual*5,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0){print(0);}else{print(round(($gaoMensual*5)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroGAO']+$row4['FebreroGAO']+$row4['MarzoGAO']+$row4['AbrilGAO']+$row4['MayoGAO']+$intereses5A,2));?></td>
					<td><?php if($row4['EneroGAO'] == 0 || $row['EneroP'] == 0 || $row4['FebreroGAO'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoGAO'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilGAO'] == 0 || $row['AbrilP'] == 0 || $row4['MayoGAO'] == 0 || $row['MayoP'] == 0){print(0);}else{print(round(($intereses5A+$row4['EneroGAO']+$row4['FebreroGAO']+$row4['MarzoGAO']+$row4['AbrilGAO']+$row4['MayoGAO'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($mayoGAO,2));?></td>
					<td></td>
					<td>$<?php print(number_format($gaoMensual,2));?></td>
					<td><?php if($row2['Junio'] == 0){print(0);}else{print(round($gaoMensual/$row2['Junio']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['JunioGAO']+$intereses6R,2));?></td>
					<td><?php if($row4['JunioGAO'] == 0 || $row['JunioP'] == 0){print(0);}else{print(round((($row4['JunioGAO']+$intereses6R)/$row['JunioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['JunioGAO']-$gaoMensual+$diferencia6Intereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($gaoMensual*6,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0){print(0);}else{print(round(($gaoMensual*6)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroGAO']+$row4['FebreroGAO']+$row4['MarzoGAO']+$row4['AbrilGAO']+$row4['MayoGAO']+$row4['JunioGAO']+$intereses6A,2));?></td>
					<td><?php if($row4['EneroGAO'] == 0 || $row['EneroP'] == 0 || $row4['FebreroGAO'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoGAO'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilGAO'] == 0 || $row['AbrilP'] == 0 || $row4['MayoGAO'] == 0 || $row['MayoP'] == 0 || $row4['JunioGAO'] == 0 || $row['JunioP'] == 0){print(0);}else{print(round(($intereses6A+$row4['EneroGAO']+$row4['FebreroGAO']+$row4['MarzoGAO']+$row4['AbrilGAO']+$row4['MayoGAO']+$row4['JunioGAO'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($junioGAO,2));?></td>
					<td></td>
					<td>$<?php print(number_format($gaoMensual,2));?></td>
					<td><?php if($row2['Julio'] == 0){print(0);}else{print(round($gaoMensual/$row2['Julio']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['JulioGAO']+$intereses7R,2));?></td>
					<td><?php if($row4['JulioGAO'] == 0 || $row['JulioP'] == 0){print(0);}else{print(round((($row4['JulioGAO']+$intereses7R)/$row['JulioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['JulioGAO']-$gaoMensual+$diferencia7Intereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($gaoMensual*7,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0){print(0);}else{print(round(($gaoMensual*7)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroGAO']+$row4['FebreroGAO']+$row4['MarzoGAO']+$row4['AbrilGAO']+$row4['MayoGAO']+$row4['JunioGAO']+$row4['JulioGAO']+$intereses7A,2));?></td>
					<td><?php if($row4['EneroGAO'] == 0 || $row['EneroP'] == 0 || $row4['FebreroGAO'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoGAO'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilGAO'] == 0 || $row['AbrilP'] == 0 || $row4['MayoGAO'] == 0 || $row['MayoP'] == 0 || $row4['JunioGAO'] == 0 || $row['JunioP'] == 0 || $row4['JulioGAO'] == 0 || $row['JulioP'] == 0){print(0);}else{print(round(($intereses7A+$row4['EneroGAO']+$row4['FebreroGAO']+$row4['MarzoGAO']+$row4['AbrilGAO']+$row4['MayoGAO']+$row4['JunioGAO']+$row4['JulioGAO'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($julioGAO,2));?></td>
					<td></td>
					<td>$<?php print(number_format($gaoMensual,2));?></td>
					<td><?php if($row2['Agosto'] == 0){print(0);}else{print(round($gaoMensual/$row2['Agosto']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['AgostoGAO']+$intereses8R,2));?></td>
					<td><?php if($row4['AgostoGAO'] == 0 || $row['AgostoP'] == 0){print(0);}else{print(round((($row4['AgostoGAO']+$intereses8R)/$row['AgostoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['AgostoGAO']-$gaoMensual+$diferencia8Intereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($gaoMensual*8,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0 || $row2['Agosto'] == 0){print(0);}else{print(round(($gaoMensual*8)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroGAO']+$row4['FebreroGAO']+$row4['MarzoGAO']+$row4['AbrilGAO']+$row4['MayoGAO']+$row4['JunioGAO']+$row4['JulioGAO']+$row4['AgostoGAO']+$intereses8A,2));?></td>
					<td><?php if($row4['EneroGAO'] == 0 || $row['EneroP'] == 0 || $row4['FebreroGAO'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoGAO'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilGAO'] == 0 || $row['AbrilP'] == 0 || $row4['MayoGAO'] == 0 || $row['MayoP'] == 0 || $row4['JunioGAO'] == 0 || $row['JunioP'] == 0 || $row4['JulioGAO'] == 0 || $row['JulioP'] == 0 || $row4['AgostoGAO'] == 0 || $row['AgostoP'] == 0){print(0);}else{print(round(($intereses8A+$row4['EneroGAO']+$row4['FebreroGAO']+$row4['MarzoGAO']+$row4['AbrilGAO']+$row4['MayoGAO']+$row4['JunioGAO']+$row4['JulioGAO']+$row4['AgostoGAO'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($agostoGAO,2));?></td>
					<td></td>
					<td>$<?php print(number_format($gaoMensual,2));?></td>
					<td><?php if($row2['Septiembre'] == 0){print(0);}else{print(round($gaoMensual/$row2['Septiembre']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['SeptiembreGAO']+$intereses9R,2));?></td>
					<td><?php if($row4['SeptiembreGAO'] == 0 || $row['SeptiembreP'] == 0){print(0);}else{print(round((($row4['SeptiembreGAO']+$intereses9R)/$row['SeptiembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['SeptiembreGAO']-$gaoMensual+$diferencia9Intereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($gaoMensual*9,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0 || $row2['Agosto'] == 0 || $row2['Septiembre'] == 0){print(0);}else{print(round(($gaoMensual*9)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroGAO']+$row4['FebreroGAO']+$row4['MarzoGAO']+$row4['AbrilGAO']+$row4['MayoGAO']+$row4['JunioGAO']+$row4['JulioGAO']+$row4['AgostoGAO']+$row4['SeptiembreGAO']+$intereses9A,2));?></td>
					<td><?php if($row4['EneroGAO'] == 0 || $row['EneroP'] == 0 || $row4['FebreroGAO'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoGAO'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilGAO'] == 0 || $row['AbrilP'] == 0 || $row4['MayoGAO'] == 0 || $row['MayoP'] == 0 || $row4['JunioGAO'] == 0 || $row['JunioP'] == 0 || $row4['JulioGAO'] == 0 || $row['JulioP'] == 0 || $row4['AgostoGAO'] == 0 || $row['AgostoP'] == 0 || $row4['SeptiembreGAO'] == 0 || $row['SeptiembreP'] == 0){print(0);}else{print(round(($intereses9A+$row4['EneroGAO']+$row4['FebreroGAO']+$row4['MarzoGAO']+$row4['AbrilGAO']+$row4['MayoGAO']+$row4['JunioGAO']+$row4['JulioGAO']+$row4['AgostoGAO']+$row4['SeptiembreGAO'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($septiembreGAO,2));?></td>
					<td></td>
					<td>$<?php print(number_format($gaoMensual,2));?></td>
					<td><?php if($row2['Octubre'] == 0){print(0);}else{print(round($gaoMensual/$row2['Octubre']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['OctubreGAO']+$intereses10R,2));?></td>
					<td><?php if($row4['OctubreGAO'] == 0 || $row['OctubreP'] == 0){print(0);}else{print(round((($row4['OctubreGAO']+$intereses10R)/$row['OctubreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['OctubreGAO']-$gaoMensual+$diferencia10Intereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($gaoMensual*10,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0 || $row2['Agosto'] == 0 || $row2['Septiembre'] == 0 || $row2['Octubre'] == 0){print(0);}else{print(round(($gaoMensual*10)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre']+$row2['Octubre'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroGAO']+$row4['FebreroGAO']+$row4['MarzoGAO']+$row4['AbrilGAO']+$row4['MayoGAO']+$row4['JunioGAO']+$row4['JulioGAO']+$row4['AgostoGAO']+$row4['SeptiembreGAO']+$row4['OctubreGAO']+$intereses10A,2));?></td>
					<td><?php if($row4['EneroGAO'] == 0 || $row['EneroP'] == 0 || $row4['FebreroGAO'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoGAO'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilGAO'] == 0 || $row['AbrilP'] == 0 || $row4['MayoGAO'] == 0 || $row['MayoP'] == 0 || $row4['JunioGAO'] == 0 || $row['JunioP'] == 0 || $row4['JulioGAO'] == 0 || $row['JulioP'] == 0 || $row4['AgostoGAO'] == 0 || $row['AgostoP'] == 0 || $row4['SeptiembreGAO'] == 0 || $row['SeptiembreP'] == 0 || $row4['OctubreGAO'] == 0 || $row['OctubreP'] == 0){print(0);}else{print(round(($intereses10A+$row4['EneroGAO']+$row4['FebreroGAO']+$row4['MarzoGAO']+$row4['AbrilGAO']+$row4['MayoGAO']+$row4['JunioGAO']+$row4['JulioGAO']+$row4['AgostoGAO']+$row4['SeptiembreGAO']+$row4['OctubreGAO'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($octubreGAO,2));?></td>
					<td></td>
					<td>$<?php print(number_format($gaoMensual,2));?></td>
					<td><?php if($row2['Noviembre'] == 0){print(0);}else{print(round($gaoMensual/$row2['Noviembre']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['NoviembreGAO']+$intereses11R,2));?></td>
					<td><?php if($row4['NoviembreGAO'] == 0 || $row['NoviembreP'] == 0){print(0);}else{print(round((($row4['NoviembreGAO']+$intereses11R)/$row['NoviembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['NoviembreGAO']-$gaoMensual+$diferencia11Intereses,2));?></td>
					<td></td>
					<td>$<?php print(number_format($gaoMensual*11,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0 || $row2['Agosto'] == 0 || $row2['Septiembre'] == 0 || $row2['Octubre'] == 0 || $row2['Noviembre'] == 0){print(0);}else{print(round(($gaoMensual*11)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre']+$row2['Octubre']+$row2['Noviembre'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['EneroGAO']+$row4['FebreroGAO']+$row4['MarzoGAO']+$row4['AbrilGAO']+$row4['MayoGAO']+$row4['JunioGAO']+$row4['JulioGAO']+$row4['AgostoGAO']+$row4['SeptiembreGAO']+$row4['OctubreGAO']+$row4['NoviembreGAO']+$intereses11A,2));?></td>
					<td><?php if($row4['EneroGAO'] == 0 || $row['EneroP'] == 0 || $row4['FebreroGAO'] == 0 || $row['FebreroP'] == 0 || $row4['MarzoGAO'] == 0 || $row['MarzoP'] == 0 || $row4['AbrilGAO'] == 0 || $row['AbrilP'] == 0 || $row4['MayoGAO'] == 0 || $row['MayoP'] == 0 || $row4['JunioGAO'] == 0 || $row['JunioP'] == 0 || $row4['JulioGAO'] == 0 || $row['JulioP'] == 0 || $row4['AgostoGAO'] == 0 || $row['AgostoP'] == 0 || $row4['SeptiembreGAO'] == 0 || $row['SeptiembreP'] == 0 || $row4['OctubreGAO'] == 0 || $row['OctubreP'] == 0 || $row4['NoviembreGAO'] == 0 || $row['NoviembreP'] == 0){print(0);}else{print(round(($intereses11A+$row4['EneroGAO']+$row4['FebreroGAO']+$row4['MarzoGAO']+$row4['AbrilGAO']+$row4['MayoGAO']+$row4['JunioGAO']+$row4['JulioGAO']+$row4['AgostoGAO']+$row4['SeptiembreGAO']+$row4['OctubreGAO']+$row4['NoviembreGAO'])/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP']+$row['NoviembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($noviembreGAO,2));?></td>
					<td></td>
					<td>$<?php print(number_format($gaoMensual,2));?></td>
					<td><?php if($row2['Diciembre'] == 0){print(0);}else{print(round($gaoMensual/$row2['Diciembre']*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['DiciembreGAO']+$intereses12R,2));?></td>
					<td><?php if($row4['DiciembreGAO'] == 0 || $row['DiciembreP'] == 0){print(0);}else{print(round((($row4['DiciembreGAO']+$intereses12R)/$row['DiciembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($row4['DiciembreGAO']-$gaoMensual+$diferencia12Intereses,2));?></td>
				</tr>
				<tr>
					<td colspan="136"></td>
				</tr>
				<!--EBCL-->
				<tr>
					<th colspan="2">EBCL</th>
					<td>$<?php print(number_format($ebclEstaTotal,2));?></td>
					<td><?php print(round($ebclEstaTotalPor*100,2));?>%</td>
					<td></td>
					<td>$<?php print(number_format($ebclEstaEnero,2));?></td>
					<td><?php print(round($ebclEstaEneroPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealEnero,2));?></td>
					<td><?php print(round($ebclRealEneroPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealEnero-$ebclEstaEnero,2));?></td>
					<td></td>
					<td>$<?php print(number_format($ebclEstaFebrero,2));?></td>
					<td><?php print(round($ebclEstaFebreroPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealFebrero,2));?></td>
					<td><?php print(round($ebclRealFebreroPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealFebrero-$ebclEstaFebrero,2));?></td>
					<td></td>
					<td>$<?php print(number_format($ebclEstaFebreroAcum,2));?></td>
					<td><?php print(round($ebclEstaFebreroAcumPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealFebreroAcum,2));?></td>
					<td><?php print(round($ebclRealFebreroAcumPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclFebreroAcumT,2));?></td>
					<td></td>
					<td>$<?php print(number_format($ebclEstaMarzo,2));?></td>
					<td><?php print(round($ebclEstaMarzoPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealMarzo,2));?></td>
					<td><?php print(round($ebclRealMarzoPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealMarzo-$ebclEstaMarzo,2));?></td>
					<td></td>
					<td>$<?php print(number_format($ebclEstaMarzoAcum,2));?></td>
					<td><?php print(round($ebclEstaMarzoAcumPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealMarzoAcum,2));?></td>
					<td><?php print(round($ebclRealMarzoAcumPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclMarzoAcumT,2));?></td>
					<td></td>
					<td>$<?php print(number_format($ebclEstaAbril,2));?></td>
					<td><?php print(round($ebclEstaAbrilPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealAbril,2));?></td>
					<td><?php print(round($ebclRealAbrilPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealAbril-$ebclEstaAbril,2));?></td>
					<td></td>
					<td>$<?php print(number_format($ebclEstaAbrilAcum,2));?></td>
					<td><?php print(round($ebclEstaAbrilAcumPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealAbrilAcum,2));?></td>
					<td><?php print(round($ebclRealAbrilAcumPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclAbrilAcumT,2));?></td>
					<td></td>
					<td>$<?php print(number_format($ebclEstaMayo,2));?></td>
					<td><?php print(round($ebclEstaMayoPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealMayo,2));?></td>
					<td><?php print(round($ebclRealMayoPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealMayo-$ebclEstaMayo,2));?></td>
					<td></td>
					<td>$<?php print(number_format($ebclEstaMayoAcum,2));?></td>
					<td><?php print(round($ebclEstaMayoAcumPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealMayoAcum,2));?></td>
					<td><?php print(round($ebclRealMayoAcumPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclMayoAcumT,2));?></td>
					<td></td>
					<td>$<?php print(number_format($ebclEstaJunio,2));?></td>
					<td><?php print(round($ebclEstaJunioPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealJunio,2));?></td>
					<td><?php print(round($ebclRealJunioPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealJunio-$ebclEstaJunio,2));?></td>
					<td></td>
					<td>$<?php print(number_format($ebclEstaJunioAcum,2));?></td>
					<td><?php print(round($ebclEstaJunioAcumPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealJunioAcum,2));?></td>
					<td><?php print(round($ebclRealJunioAcumPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclJunioAcumT,2));?></td>
					<td></td>
					<td>$<?php print(number_format($ebclEstaJulio,2));?></td>
					<td><?php print(round($ebclEstaJulioPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealJulio,2));?></td>
					<td><?php print(round($ebclRealJulioPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealJulio-$ebclEstaJulio,2));?></td>
					<td></td>
					<td>$<?php print(number_format($ebclEstaJulioAcum,2));?></td>
					<td><?php print(round($ebclEstaJulioAcumPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealJulioAcum,2));?></td>
					<td><?php print(round($ebclRealJulioAcumPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclJulioAcumT,2));?></td>
					<td></td>
					<td>$<?php print(number_format($ebclEstaAgosto,2));?></td>
					<td><?php print(round($ebclEstaAgostoPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealAgosto,2));?></td>
					<td><?php print(round($ebclRealAgostoPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealAgosto-$ebclEstaAgosto,2));?></td>
					<td></td>
					<td>$<?php print(number_format($ebclEstaAgostoAcum,2));?></td>
					<td><?php print(round($ebclEstaAgostoAcumPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealAgostoAcum,2));?></td>
					<td><?php print(round($ebclRealAgostoAcumPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclAgostoAcumT,2));?></td>
					<td></td>
					<td>$<?php print(number_format($ebclEstaSeptiembre,2));?></td>
					<td><?php print(round($ebclEstaSeptiembrePor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealSeptiembre,2));?></td>
					<td><?php print(round($ebclRealSeptiembrePor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealSeptiembre-$ebclEstaSeptiembre,2));?></td>
					<td></td>
					<td>$<?php print(number_format($ebclEstaSeptiembreAcum,2));?></td>
					<td><?php print(round($ebclEstaSeptiembreAcumPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealSeptiembreAcum,2));?></td>
					<td><?php print(round($ebclRealSeptiembreAcumPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclSeptiembreAcumT,2));?></td>
					<td></td>
					<td>$<?php print(number_format($ebclEstaOctubre,2));?></td>
					<td><?php print(round($ebclEstaOctubrePor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealOctubre,2));?></td>
					<td><?php print(round($ebclRealOctubrePor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealOctubre-$ebclEstaOctubre,2));?></td>
					<td></td>
					<td>$<?php print(number_format($ebclEstaOctubreAcum,2));?></td>
					<td><?php print(round($ebclEstaOctubreAcumPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealOctubreAcum,2));?></td>
					<td><?php print(round($ebclRealOctubreAcumPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclOctubreAcumT,2));?></td>
					<td></td>
					<td>$<?php print(number_format($ebclEstaNoviembre,2));?></td>
					<td><?php print(round($ebclEstaNoviembrePor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealNoviembre,2));?></td>
					<td><?php print(round($ebclRealNoviembrePor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealNoviembre-$ebclEstaNoviembre,2));?></td>
					<td></td>
					<td>$<?php print(number_format($ebclEstaNoviembreAcum,2));?></td>
					<td><?php print(round($ebclEstaNoviembreAcumPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealNoviembreAcum,2));?></td>
					<td><?php print(round($ebclRealNoviembreAcumPor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclNoviembreAcumT,2));?></td>
					<td></td>
					<td>$<?php print(number_format($ebclEstaDiciembre,2));?></td>
					<td><?php print(round($ebclEstaDiciembrePor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealDiciembre,2));?></td>
					<td><?php print(round($ebclRealDiciembrePor*100,2));?>%</td>
					<td>$<?php print(number_format($ebclRealDiciembre-$ebclEstaDiciembre,2));?></td>
				</tr>
				<tr>
					<td colspan="136"></td>
				</tr>
				<!--GAO VARIABLE-->
				<tr>
					<th colspan="2">GAO Variable</th>
					<td colspan="134"></td>
				</tr>
			<?php 
				$oferta = '38%';
			?>
				<!--OFERTA-->
				<tr>
					<th></th>
					<th>Oferta</th>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
					<td><?php print($oferta);?></td>
					<td></td>
				</tr>
				<!--COMISIÓN-->
				<tr>
					<th></th>
					<th>Comisión</th>
					<td>$<?php print(number_format($row2['TOTAL']*$comision_por_p,2));?></td>
					<td><?php if($row2['TOTAL'] == 0){print(0);} else{print(round(($row2['TOTAL']*$comision_por_p)/$row2['TOTAL']*100,2));}?>%</td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision1,2));?></td>
					<td><?php if($row2['Enero'] == 0){print(0);} else{print(round($diferenciaComision1/$row2['Enero'],2)*100);}?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR1,2));?></td>
					<td><?php print(round($diferenciaComisionRPor1*100,2));?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR1-$diferenciaComision1,2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision2,2));?></td>
					<td><?php if($row2['Febrero'] == 0){print(0);} else{print(round($diferenciaComision2/$row2['Febrero'],2)*100);}?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR2,2));?></td>
					<td><?php print(round($diferenciaComisionRPor2*100,2));?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR2-$diferenciaComision2,2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision1+$diferenciaComision2,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0){print(0);} else{print(round(($diferenciaComision1+$diferenciaComision2)/($row2['Enero']+$row2['Febrero'])*100,2));}?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR1+$diferenciaComisionR2,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0){print(0);}else{print(round(($diferenciaComisionR1+$diferenciaComisionR2)/($row['EneroP']+$row['FebreroP'])*100,2));}?>%</td>
					<td>$<?php print(number_format(($diferenciaComisionR1+$diferenciaComisionR2)-($diferenciaComision1+$diferenciaComision2),2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision3,2));?></td>
					<td><?php if($row2['Marzo'] == 0){print(0);} else{print(round($diferenciaComision3/$row2['Marzo'],2)*100);}?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR3,2));?></td>
					<td><?php print(round($diferenciaComisionRPor3*100,2));?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR3-$diferenciaComision3,2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision1+$diferenciaComision2+$diferenciaComision3,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0){print(0);} else{print(round(($diferenciaComision1+$diferenciaComision2+$diferenciaComision3)/($row2['Enero']+$row2['Febrero']+$row2['Marzo'])*100,2));}?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0){print(0);}else{print(round(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3)/($row['EneroP']+$row['FebreroP']+$row['MarzoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3)-($diferenciaComision1+$diferenciaComision2+$diferenciaComision3),2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision4,2));?></td>
					<td><?php if($row2['Abril'] == 0){print(0);} else{print(round($diferenciaComision4/$row2['Abril'],2)*100);}?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR4,2));?></td>
					<td><?php print(round($diferenciaComisionRPor4*100,2));?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR4-$diferenciaComision4,2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0){print(0);} else{print(round(($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril'])*100,2));}?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0){print(0);}else{print(round(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP'])*100,2));}?>%</td>
					<td>$<?php print(number_format(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4)-($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4),2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision5,2));?></td>
					<td><?php if($row2['Mayo'] == 0){print(0);} else{print(round($diferenciaComision5/$row2['Mayo'],2)*100);}?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR5,2));?></td>
					<td><?php print(round($diferenciaComisionRPor5*100,2));?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR5-$diferenciaComision5,2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0){print(0);} else{print(round(($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo'])*100,2));}?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0){print(0);}else{print(round(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5)-($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5),2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision6,2));?></td>
					<td><?php if($row2['Junio'] == 0){print(0);} else{print(round($diferenciaComision6/$row2['Junio'],2)*100);}?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR6,2));?></td>
					<td><?php print(round($diferenciaComisionRPor6*100,2));?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR6-$diferenciaComision6,2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0){print(0);} else{print(round(($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio'])*100,2));}?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0){print(0);}else{print(round(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6)-($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6),2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision7,2));?></td>
					<td><?php if($row2['Julio'] == 0){print(0);} else{print(round($diferenciaComision7/$row2['Julio'],2)*100);}?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR7,2));?></td>
					<td><?php print(round($diferenciaComisionRPor7*100,2));?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR7-$diferenciaComision7,2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6+$diferenciaComision7,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0){print(0);} else{print(round(($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6+$diferenciaComision7)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio'])*100,2));}?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6+$diferenciaComisionR7,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0 || $row['JulioP'] == 0){print(0);}else{print(round(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6+$diferenciaComisionR7)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6+$diferenciaComisionR7)-($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6+$diferenciaComision7),2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision8,2));?></td>
					<td><?php if($row2['Agosto'] == 0){print(0);} else{print(round($diferenciaComision8/$row2['Agosto'],2)*100);}?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR8,2));?></td>
					<td><?php print(round($diferenciaComisionRPor8*100,2));?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR8-$diferenciaComision8,2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6+$diferenciaComision7+$diferenciaComision8,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0 || $row2['Agosto'] == 0){print(0);} else{print(round(($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6+$diferenciaComision7+$diferenciaComision8)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto'])*100,2));}?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6+$diferenciaComisionR7+$diferenciaComisionR8,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0 || $row['JulioP'] == 0 || $row['AgostoP'] == 0){print(0);}else{print(round(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6+$diferenciaComisionR7+$diferenciaComisionR8)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6+$diferenciaComisionR7+$diferenciaComisionR8)-($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6+$diferenciaComision7+$diferenciaComision8),2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision9,2));?></td>
					<td><?php if($row2['Septiembre'] == 0){print(0);} else{print(round($diferenciaComision9/$row2['Septiembre'],2)*100);}?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR9,2));?></td>
					<td><?php print(round($diferenciaComisionRPor9*100,2));?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR9-$diferenciaComision9,2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6+$diferenciaComision7+$diferenciaComision8+$diferenciaComision9,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0 || $row2['Agosto'] == 0 || $row2['Septiembre'] == 0){print(0);} else{print(round(($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6+$diferenciaComision7+$diferenciaComision8+$diferenciaComision9)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre'])*100,2));}?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6+$diferenciaComisionR7+$diferenciaComisionR8+$diferenciaComisionR9,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0 || $row['JulioP'] == 0 || $row['AgostoP'] == 0 || $row['SeptiembreP'] == 0){print(0);}else{print(round(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6+$diferenciaComisionR7+$diferenciaComisionR8+$diferenciaComisionR9)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6+$diferenciaComisionR7+$diferenciaComisionR8+$diferenciaComisionR9)-($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6+$diferenciaComision7+$diferenciaComision8+$diferenciaComision9),2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision10,2));?></td>
					<td><?php if($row2['Octubre'] == 0){print(0);} else{print(round($diferenciaComision10/$row2['Octubre'],2)*100);}?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR10,2));?></td>
					<td><?php print(round($diferenciaComisionRPor10*100,2));?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR10-$diferenciaComision10,2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6+$diferenciaComision7+$diferenciaComision8+$diferenciaComision9+$diferenciaComision10,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0 || $row2['Agosto'] == 0 || $row2['Septiembre'] == 0 || $row2['Octubre'] == 0){print(0);} else{print(round(($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6+$diferenciaComision7+$diferenciaComision8+$diferenciaComision9+$diferenciaComision10)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre']+$row2['Octubre'])*100,2));}?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6+$diferenciaComisionR7+$diferenciaComisionR8+$diferenciaComisionR9+$diferenciaComisionR10,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0 || $row['JulioP'] == 0 || $row['AgostoP'] == 0 || $row['SeptiembreP'] == 0 || $row['OctubreP'] == 0){print(0);}else{print(round(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6+$diferenciaComisionR7+$diferenciaComisionR8+$diferenciaComisionR9+$diferenciaComisionR10)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6+$diferenciaComisionR7+$diferenciaComisionR8+$diferenciaComisionR9+$diferenciaComisionR10)-($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6+$diferenciaComision7+$diferenciaComision8+$diferenciaComision9+$diferenciaComision10),2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision11,2));?></td>
					<td><?php if($row2['Noviembre'] == 0){print(0);} else{print(round($diferenciaComision11/$row2['Noviembre'],2)*100);}?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR11,2));?></td>
					<td><?php print(round($diferenciaComisionRPor11*100,2));?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR11-$diferenciaComision11,2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6+$diferenciaComision7+$diferenciaComision8+$diferenciaComision9+$diferenciaComision10+$diferenciaComision11,2));?></td>
					<td><?php if($row2['Enero'] == 0 || $row2['Febrero'] == 0 || $row2['Marzo'] == 0 || $row2['Abril'] == 0 || $row2['Mayo'] == 0 || $row2['Junio'] == 0 || $row2['Julio'] == 0 || $row2['Agosto'] == 0 || $row2['Septiembre'] == 0 || $row2['Octubre'] == 0 || $row2['Noviembre'] == 0){print(0);} else{print(round(($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6+$diferenciaComision7+$diferenciaComision8+$diferenciaComision9+$diferenciaComision10+$diferenciaComision11)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre']+$row2['Octubre']+$row2['Noviembre'])*100,2));}?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6+$diferenciaComisionR7+$diferenciaComisionR8+$diferenciaComisionR9+$diferenciaComisionR10+$diferenciaComisionR11,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0 || $row['JulioP'] == 0 || $row['AgostoP'] == 0 || $row['SeptiembreP'] == 0 || $row['OctubreP'] == 0 || $row['NoviembreP'] == 0){print(0);}else{print(round(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6+$diferenciaComisionR7+$diferenciaComisionR8+$diferenciaComisionR9+$diferenciaComisionR10+$diferenciaComisionR11)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP']+$row['NoviembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6+$diferenciaComisionR7+$diferenciaComisionR8+$diferenciaComisionR9+$diferenciaComisionR10+$diferenciaComisionR11)-($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6+$diferenciaComision7+$diferenciaComision8+$diferenciaComision9+$diferenciaComision10+$diferenciaComision11),2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision12,2));?></td>
					<td><?php if($row2['Diciembre'] == 0){print(0);} else{print(round($diferenciaComision12/$row2['Diciembre'],2)*100);}?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR12,2));?></td>
					<td><?php print(round($diferenciaComisionRPor12*100,2));?>%</td>
					<td>$<?php print(number_format($diferenciaComisionR12-$diferenciaComision12,2));?></td>
				</tr>
				<!--ANTICIPO-->
				<tr>
					<th></th>
					<th>Anticipo</th>
					<td>$<?php print(number_format($row5['TotalAnticipo'],2)); ?></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['Enero'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Enero'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['Febrero'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Febrero'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['Enero']+$row5['Febrero'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Enero']+$row5['Febrero'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['Marzo'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Marzo'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['Enero']+$row5['Febrero']+$row5['Marzo'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Enero']+$row5['Febrero']+$row5['Marzo'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['Abril'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Abril'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['Mayo'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Mayo'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['Junio'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Junio'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['Julio'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Julio'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio']+$row5['Julio'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio']+$row5['Julio'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['Agosto'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Agosto'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio']+$row5['Julio']+$row5['Agosto'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio']+$row5['Julio']+$row5['Agosto'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['Septiembre'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Septiembre'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio']+$row5['Julio']+$row5['Agosto']+$row5['Septiembre'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio']+$row5['Julio']+$row5['Agosto']+$row5['Septiembre'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['Octubre'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Octubre'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio']+$row5['Julio']+$row5['Agosto']+$row5['Septiembre']+$row5['Octubre'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio']+$row5['Julio']+$row5['Agosto']+$row5['Septiembre']+$row5['Octubre'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['Noviembre'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Noviembre'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio']+$row5['Julio']+$row5['Agosto']+$row5['Septiembre']+$row5['Octubre']+$row5['Noviembre'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio']+$row5['Julio']+$row5['Agosto']+$row5['Septiembre']+$row5['Octubre']+$row5['Noviembre'],2)); ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($row5['Diciembre'],2)); ?></td>
					<td></td>
					<td>$<?php print(number_format($row5['Diciembre'],2)); ?></td>
					<td></td>
					<td></td>
				</tr>
				<!--SALDO -->
				<tr>
					<th></th>
					<th>Saldo</th>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision1-$row5['Enero'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComisionR1-$row5['Enero'],2));?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision2-$row5['Febrero'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComisionR2-$row5['Febrero'],2));?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format(($diferenciaComision1+$diferenciaComision2)-($row5['Enero']+$row5['Febrero']),2));?></td>
					<td></td>
					<td>$<?php print(number_format(($diferenciaComisionR1+$diferenciaComisionR2)-($row5['Enero']+$row5['Febrero']),2));?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision3-$row5['Marzo'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComisionR3-$row5['Marzo'],2));?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format(($diferenciaComision1+$diferenciaComision2+$diferenciaComision3)-($row5['Enero']+$row5['Febrero']+$row5['Marzo']),2));?></td>
					<td></td>
					<td>$<?php print(number_format(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3)-($row5['Enero']+$row5['Febrero']+$row5['Marzo']),2));?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision4-$row5['Abril'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComisionR4-$row5['Abril'],2));?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format(($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4)-($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']),2));?></td>
					<td></td>
					<td>$<?php print(number_format(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4)-($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']),2));?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision5-$row5['Mayo'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComisionR5-$row5['Mayo'],2));?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format(($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5)-($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']),2));?></td>
					<td></td>
					<td>$<?php print(number_format(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5)-($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']),2));?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision6-$row5['Junio'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComisionR6-$row5['Junio'],2));?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format(($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6)-($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio']),2));?></td>
					<td></td>
					<td>$<?php print(number_format(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6)-($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio']),2));?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision7-$row5['Julio'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComisionR7-$row5['Julio'],2));?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format(($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6+$diferenciaComision7)-($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio']-$row5['Julio']),2));?></td>
					<td></td>
					<td>$<?php print(number_format(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6+$diferenciaComisionR7)-($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio']-$row5['Julio']),2));?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision8-$row5['Agosto'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComisionR8-$row5['Agosto'],2));?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format(($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6+$diferenciaComision7+$diferenciaComision8)-($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio']-$row5['Julio']-$row5['Agosto']),2));?></td>
					<td></td>
					<td>$<?php print(number_format(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6+$diferenciaComisionR7+$diferenciaComisionR8)-($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio']-$row5['Julio']-$row5['Agosto']),2));?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision9-$row5['Septiembre'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComisionR9-$row5['Septiembre'],2));?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format(($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6+$diferenciaComision7+$diferenciaComision8+$diferenciaComision9)-($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio']-$row5['Julio']-$row5['Agosto']-$row5['Septiembre']),2));?></td>
					<td></td>
					<td>$<?php print(number_format(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6+$diferenciaComisionR7+$diferenciaComisionR8+$diferenciaComisionR9)-($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio']-$row5['Julio']-$row5['Agosto']-$row5['Septiembre']),2));?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision10-$row5['Octubre'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComisionR10-$row5['Octubre'],2));?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format(($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6+$diferenciaComision7+$diferenciaComision8+$diferenciaComision9+$diferenciaComision10)-($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio']-$row5['Julio']-$row5['Agosto']-$row5['Septiembre']-$row5['Octubre']),2));?></td>
					<td></td>
					<td>$<?php print(number_format(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6+$diferenciaComisionR7+$diferenciaComisionR8+$diferenciaComisionR9+$diferenciaComisionR10)-($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio']-$row5['Julio']-$row5['Agosto']-$row5['Septiembre']-$row5['Octubre']),2));?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision11-$row5['Noviembre'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComisionR11-$row5['Noviembre'],2));?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format(($diferenciaComision1+$diferenciaComision2+$diferenciaComision3+$diferenciaComision4+$diferenciaComision5+$diferenciaComision6+$diferenciaComision7+$diferenciaComision8+$diferenciaComision9+$diferenciaComision10+$diferenciaComision11)-($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio']-$row5['Julio']-$row5['Agosto']-$row5['Septiembre']-$row5['Octubre']-$row5['Noviembre']),2));?></td>
					<td></td>
					<td>$<?php print(number_format(($diferenciaComisionR1+$diferenciaComisionR2+$diferenciaComisionR3+$diferenciaComisionR4+$diferenciaComisionR5+$diferenciaComisionR6+$diferenciaComisionR7+$diferenciaComisionR8+$diferenciaComisionR9+$diferenciaComisionR10+$diferenciaComisionR11)-($row5['Enero']+$row5['Febrero']+$row5['Marzo']+$row5['Abril']+$row5['Mayo']+$row5['Junio']-$row5['Julio']-$row5['Agosto']-$row5['Septiembre']-$row5['Octubre']-$row5['Noviembre']),2));?></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComision12-$row5['Diciembre'],2));?></td>
					<td></td>
					<td>$<?php print(number_format($diferenciaComisionR12-$row5['Diciembre'],2));?></td>
					<td></td>
				</tr>
				<!--MARGEN AJUSTADO-->
				<tr>
					<th colspan="2">Margen Ajustado</th>
					<td>$<?php print(number_format($margenAjustadoTotalP,2));?></td>
					<td><?php if($row2['TOTAL'] == 0){print(0);} else{print(round($margenAjustadoTotalP/$row2['TOTAL']*100,2));}?>%</td>
					<td></td>
					<td>$<?php print(number_format($margenAjustado1P,2));?></td>
					<td><?php if($row2['Enero'] == 0){print(0);} else{print(round($margenAjustado1P/$row2['Enero']*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado1R,2));?></td>
					<td><?php print(round($margenAjustadoPor1R*100,2));?>%</td>
					<td>$<?php print(number_format($margenAjustado1R-$margenAjustado1P,2));?></td>
					<td></td>
					<td>$<?php print(number_format($margenAjustado2P,2));?></td>
					<td><?php if($row2['Febrero'] == 0){print(0);} else{print(round($margenAjustado2P/$row2['Febrero']*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado2R,2));?></td>
					<td><?php print(round($margenAjustadoPor2R*100,2));?>%</td>
					<td>$<?php print(number_format($margenAjustado2R-$margenAjustado2P,2));?></td>
					<td></td>
					<td>$<?php print(number_format($margenAjustado2PA,2));?></td>
					<td><?php if($row2['Enero']==0||$row2['Febrero']==0){print(0);} else{print(round(($margenAjustado1P+$margenAjustado2P)/($row2['Enero']+$row2['Febrero'])*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado2RA,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0){print(0);}else{print(round(($margenAjustado1R+$margenAjustado2R)/($row['EneroP']+$row['FebreroP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado2RA-$margenAjustado2PA,2));?></td>
					<td></td>
					<td>$<?php print(number_format($margenAjustado3P,2));?></td>
					<td><?php if($row2['Marzo'] == 0){print(0);} else{print(round($margenAjustado3P/$row2['Marzo']*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado3R,2));?></td>
					<td><?php print(round($margenAjustadoPor3R*100,2));?>%</td>
					<td>$<?php print(number_format($margenAjustado3R-$margenAjustado3P,2));?></td>
					<td></td>
					<td>$<?php print(number_format($margenAjustado1P+$margenAjustado2P+$margenAjustado3P,2));?></td>
					<td><?php if($row2['Enero']==0||$row2['Febrero']==0||$row2['Marzo']==0){print(0);} else{print(round(($margenAjustado1P+$margenAjustado2P+$margenAjustado3P)/($row2['Enero']+$row2['Febrero']+$row2['Marzo'])*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado1R+$margenAjustado2R+$margenAjustado3R,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0){print(0);}else{print(round(($margenAjustado1R+$margenAjustado2R+$margenAjustado3R)/($row['EneroP']+$row['FebreroP']+$row['MarzoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado3RA-$margenAjustado3PA,2));?></td>
					<td></td>
					<td>$<?php print(number_format($margenAjustado4P,2));?></td>
					<td><?php if($row2['Abril'] == 0){print(0);} else{print(round($margenAjustado4P/$row2['Abril']*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado4R,2));?></td>
					<td><?php print(round($margenAjustadoPor4R*100,2));?>%</td>
					<td>$<?php print(number_format($margenAjustado4R-$margenAjustado4P,2));?></td>
					<td></td>
					<td>$<?php print(number_format($margenAjustado1P+$margenAjustado2P+$margenAjustado3P+$margenAjustado4P,2));?></td>
					<td><?php if($row2['Enero']==0||$row2['Febrero']==0||$row2['Marzo']==0||$row2['Abril']==0){print(0);} else{print(round(($margenAjustado1P+$margenAjustado2P+$margenAjustado3P+$margenAjustado4P)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril'])*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado1R+$margenAjustado2R+$margenAjustado3R+$margenAjustado4R,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0){print(0);}else{print(round(($margenAjustado1R+$margenAjustado2R+$margenAjustado3R+$margenAjustado4R)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado4RA-$margenAjustado4PA,2));?></td>
					<td></td>
					<td>$<?php print(number_format($margenAjustado5P,2));?></td>
					<td><?php if($row2['Mayo'] == 0){print(0);} else{print(round($margenAjustado5P/$row2['Mayo']*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado5R,2));?></td>
					<td><?php print(round($margenAjustadoPor5R*100,2));?>%</td>
					<td>$<?php print(number_format($margenAjustado5R-$margenAjustado5P,2));?></td>
					<td></td>
					<td>$<?php print(number_format($margenAjustado1P+$margenAjustado2P+$margenAjustado3P+$margenAjustado4P+$margenAjustado5P,2));?></td>
					<td><?php if($row2['Enero']==0||$row2['Febrero']==0||$row2['Marzo']==0||$row2['Abril']==0||$row2['Mayo']==0){print(0);} else{print(round(($margenAjustado1P+$margenAjustado2P+$margenAjustado3P+$margenAjustado4P+$margenAjustado5P)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo'])*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado1R+$margenAjustado2R+$margenAjustado3R+$margenAjustado4R+$margenAjustado5R,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0){print(0);}else{print(round(($margenAjustado1R+$margenAjustado2R+$margenAjustado3R+$margenAjustado4R+$margenAjustado5R)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado5RA-$margenAjustado5PA,2));?></td>
					<td></td>
					<td>$<?php print(number_format($margenAjustado6P,2));?></td>
					<td><?php if($row2['Junio'] == 0){print(0);} else{print(round($margenAjustado6P/$row2['Junio']*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado6R,2));?></td>
					<td><?php print(round($margenAjustadoPor6R*100,2));?>%</td>
					<td>$<?php print(number_format($margenAjustado6R-$margenAjustado6P,2));?></td>
					<td></td>
					<td>$<?php print(number_format($margenAjustado1P+$margenAjustado2P+$margenAjustado3P+$margenAjustado4P+$margenAjustado5P+$margenAjustado6P,2));?></td>
					<td><?php if($row2['Enero']==0||$row2['Febrero']==0||$row2['Marzo']==0||$row2['Abril']==0||$row2['Mayo']==0||$row2['Junio']==0){print(0);} else{print(round(($margenAjustado1P+$margenAjustado2P+$margenAjustado3P+$margenAjustado4P+$margenAjustado5P+$margenAjustado6P)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio'])*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado1R+$margenAjustado2R+$margenAjustado3R+$margenAjustado4R+$margenAjustado5R+$margenAjustado6R,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0){print(0);}else{print(round(($margenAjustado1R+$margenAjustado2R+$margenAjustado3R+$margenAjustado4R+$margenAjustado5R+$margenAjustado6R)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado6RA-$margenAjustado6PA,2));?></td>
					<td></td>
					<td>$<?php print(number_format($margenAjustado7P,2));?></td>
					<td><?php if($row2['Julio'] == 0){print(0);} else{print(round($margenAjustado7P/$row2['Julio']*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado7R,2));?></td>
					<td><?php print(round($margenAjustadoPor7R*100,2));?>%</td>
					<td>$<?php print(number_format($margenAjustado7R-$margenAjustado7P,2));?></td>
					<td></td>
					<td>$<?php print(number_format($margenAjustado1P+$margenAjustado2P+$margenAjustado3P+$margenAjustado4P+$margenAjustado5P+$margenAjustado6P+$margenAjustado7P,2));?></td>
					<td><?php if($row2['Enero']==0||$row2['Febrero']==0||$row2['Marzo']==0||$row2['Abril']==0||$row2['Mayo']==0||$row2['Junio']==0||$row2['Julio']==0){print(0);} else{print(round(($margenAjustado1P+$margenAjustado2P+$margenAjustado3P+$margenAjustado4P+$margenAjustado5P+$margenAjustado6P+$margenAjustado7P)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio'])*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado1R+$margenAjustado2R+$margenAjustado3R+$margenAjustado4R+$margenAjustado5R+$margenAjustado6R+$margenAjustado7R,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0 || $row['JulioP'] == 0){print(0);}else{print(round(($margenAjustado1R+$margenAjustado2R+$margenAjustado3R+$margenAjustado4R+$margenAjustado5R+$margenAjustado6R+$margenAjustado7R)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado7RA-$margenAjustado7PA,2));?></td>
					<td></td>
					<td>$<?php print(number_format($margenAjustado8P,2));?></td>
					<td><?php if($row2['Agosto'] == 0){print(0);} else{print(round($margenAjustado8P/$row2['Agosto']*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado8R,2));?></td>
					<td><?php print(round($margenAjustadoPor8R*100,2));?>%</td>
					<td>$<?php print(number_format($margenAjustado8R-$margenAjustado8P,2));?></td>
					<td></td>
					<td>$<?php print(number_format($margenAjustado1P+$margenAjustado2P+$margenAjustado3P+$margenAjustado4P+$margenAjustado5P+$margenAjustado6P+$margenAjustado7P+$margenAjustado8P,2));?></td>
					<td><?php if($row2['Enero']==0||$row2['Febrero']==0||$row2['Marzo']==0||$row2['Abril']==0||$row2['Mayo']==0||$row2['Junio']==0||$row2['Julio']==0||$row2['Agosto']==0){print(0);} else{print(round(($margenAjustado1P+$margenAjustado2P+$margenAjustado3P+$margenAjustado4P+$margenAjustado5P+$margenAjustado6P+$margenAjustado7P+$margenAjustado8P)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto'])*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado1R+$margenAjustado2R+$margenAjustado3R+$margenAjustado4R+$margenAjustado5R+$margenAjustado6R+$margenAjustado7R+$margenAjustado8R,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0 || $row['JulioP'] == 0 || $row['AgostoP'] == 0){print(0);}else{print(round(($margenAjustado1R+$margenAjustado2R+$margenAjustado3R+$margenAjustado4R+$margenAjustado5R+$margenAjustado6R+$margenAjustado7R+$margenAjustado8R)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado8RA-$margenAjustado8PA,2));?></td>
					<td></td>
					<td>$<?php print(number_format($margenAjustado9P,2));?></td>
					<td><?php if($row2['Septiembre'] == 0){print(0);} else{print(round($margenAjustado9P/$row2['Septiembre']*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado9R,2));?></td>
					<td><?php print(round($margenAjustadoPor9R*100,2));?>%</td>
					<td>$<?php print(number_format($margenAjustado9R-$margenAjustado9P,2));?></td>
					<td></td>
					<td>$<?php print(number_format($margenAjustado1P+$margenAjustado2P+$margenAjustado3P+$margenAjustado4P+$margenAjustado5P+$margenAjustado6P+$margenAjustado7P+$margenAjustado8P+$margenAjustado9P,2));?></td>
					<td><?php if($row2['Enero']==0||$row2['Febrero']==0||$row2['Marzo']==0||$row2['Abril']==0||$row2['Mayo']==0||$row2['Junio']==0||$row2['Julio']==0||$row2['Agosto']==0||$row2['Septiembre']==0){print(0);} else{print(round(($margenAjustado1P+$margenAjustado2P+$margenAjustado3P+$margenAjustado4P+$margenAjustado5P+$margenAjustado6P+$margenAjustado7P+$margenAjustado8P+$margenAjustado9P)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre'])*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado1R+$margenAjustado2R+$margenAjustado3R+$margenAjustado4R+$margenAjustado5R+$margenAjustado6R+$margenAjustado7R+$margenAjustado8R+$margenAjustado9R,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0 || $row['JulioP'] == 0 || $row['AgostoP'] == 0 || $row['SeptiembreP'] == 0){print(0);}else{print(round(($margenAjustado1R+$margenAjustado2R+$margenAjustado3R+$margenAjustado4R+$margenAjustado5R+$margenAjustado6R+$margenAjustado7R+$margenAjustado8R+$margenAjustado9R)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado9RA-$margenAjustado9PA,2));?></td>
					<td></td>
					<td>$<?php print(number_format($margenAjustado10P,2));?></td>
					<td><?php if($row2['Octubre'] == 0){print(0);} else{print(round($margenAjustado10P/$row2['Octubre']*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado10R,2));?></td>
					<td><?php print(round($margenAjustadoPor10R*100,2));?>%</td>
					<td>$<?php print(number_format($margenAjustado10R-$margenAjustado10P,2));?></td>
					<td></td>
					<td>$<?php print(number_format($margenAjustado1P+$margenAjustado2P+$margenAjustado3P+$margenAjustado4P+$margenAjustado5P+$margenAjustado6P+$margenAjustado7P+$margenAjustado8P+$margenAjustado9P+$margenAjustado10P,2));?></td>
					<td><?php if($row2['Enero']==0||$row2['Febrero']==0||$row2['Marzo']==0||$row2['Abril']==0||$row2['Mayo']==0||$row2['Junio']==0||$row2['Julio']==0||$row2['Agosto']==0||$row2['Septiembre']==0||$row2['Octubre']==0){print(0);} else{print(round(($margenAjustado1P+$margenAjustado2P+$margenAjustado3P+$margenAjustado4P+$margenAjustado5P+$margenAjustado6P+$margenAjustado7P+$margenAjustado8P+$margenAjustado9P+$margenAjustado10P)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre']+$row2['Octubre'])*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado1R+$margenAjustado2R+$margenAjustado3R+$margenAjustado4R+$margenAjustado5R+$margenAjustado6R+$margenAjustado7R+$margenAjustado8R+$margenAjustado9R+$margenAjustado10R,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0 || $row['JulioP'] == 0 || $row['AgostoP'] == 0 || $row['SeptiembreP'] == 0 || $row['OctubreP'] == 0){print(0);}else{print(round(($margenAjustado1R+$margenAjustado2R+$margenAjustado3R+$margenAjustado4R+$margenAjustado5R+$margenAjustado6R+$margenAjustado7R+$margenAjustado8R+$margenAjustado9R+$margenAjustado10R)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado10RA-$margenAjustado10PA,2));?></td>
					<td></td>
					<td>$<?php print(number_format($margenAjustado11P,2));?></td>
					<td><?php if($row2['Noviembre'] == 0){print(0);} else{print(round($margenAjustado11P/$row2['Noviembre']*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado11R,2));?></td>
					<td><?php print(round($margenAjustadoPor11R*100,2));?>%</td>
					<td>$<?php print(number_format($margenAjustado11R-$margenAjustado11P,2));?></td>
					<td></td>
					<td>$<?php print(number_format($margenAjustado1P+$margenAjustado2P+$margenAjustado3P+$margenAjustado4P+$margenAjustado5P+$margenAjustado6P+$margenAjustado7P+$margenAjustado8P+$margenAjustado9P+$margenAjustado10P+$margenAjustado11P,2));?></td>
					<td><?php if($row2['Enero']==0||$row2['Febrero']==0||$row2['Marzo']==0||$row2['Abril']==0||$row2['Mayo']==0||$row2['Junio']==0||$row2['Julio']==0||$row2['Agosto']==0||$row2['Septiembre']==0||$row2['Octubre']==0||$row2['Noviembre']==0){print(0);} else{print(round(($margenAjustado1P+$margenAjustado2P+$margenAjustado3P+$margenAjustado4P+$margenAjustado5P+$margenAjustado6P+$margenAjustado7P+$margenAjustado8P+$margenAjustado9P+$margenAjustado10P+$margenAjustado10P)/($row2['Enero']+$row2['Febrero']+$row2['Marzo']+$row2['Abril']+$row2['Mayo']+$row2['Junio']+$row2['Julio']+$row2['Agosto']+$row2['Septiembre']+$row2['Octubre']+$row2['Noviembre'])*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado1R+$margenAjustado2R+$margenAjustado3R+$margenAjustado4R+$margenAjustado5R+$margenAjustado6R+$margenAjustado7R+$margenAjustado8R+$margenAjustado9R+$margenAjustado10R+$margenAjustado11R,2));?></td>
					<td><?php if($row['EneroP'] == 0 || $row['FebreroP'] == 0 || $row['MarzoP'] == 0 || $row['AbrilP'] == 0 || $row['MayoP'] == 0 || $row['JunioP'] == 0 || $row['JulioP'] == 0 || $row['AgostoP'] == 0 || $row['SeptiembreP'] == 0 || $row['OctubreP'] == 0 || $row['NoviembreP'] == 0){print(0);}else{print(round(($margenAjustado1R+$margenAjustado2R+$margenAjustado3R+$margenAjustado4R+$margenAjustado5R+$margenAjustado6R+$margenAjustado7R+$margenAjustado8R+$margenAjustado9R+$margenAjustado10R+$margenAjustado11R)/($row['EneroP']+$row['FebreroP']+$row['MarzoP']+$row['AbrilP']+$row['MayoP']+$row['JunioP']+$row['JulioP']+$row['AgostoP']+$row['SeptiembreP']+$row['OctubreP']+$row['NoviembreP'])*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado11RA-$margenAjustado11PA,2));?></td>
					<td></td>
					<td>$<?php print(number_format($margenAjustado12P,2));?></td>
					<td><?php if($row2['Diciembre'] == 0){print(0);} else{print(round($margenAjustado12P/$row2['Diciembre']*100,2));}?>%</td>
					<td>$<?php print(number_format($margenAjustado12R,2));?></td>
					<td><?php print(round($margenAjustadoPor12R*100,2));?>%</td>
					<td>$<?php print(number_format($margenAjustado12R-$margenAjustado12P,2));?></td>
				</tr>
			<?php
		}
		else
		{
			?>
            <tr>
				<td colspan="28"></td>
            </tr>
            <?php
		}
	}
	public function estacionariedadAVEFOMensualC($year,$mes_numero)
	{
		$query = "SELECT 
					IF(DB.id_mesejecucion=".$mes_numero.",IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora),0),0) AS MesP,
					IF(DB.id_mesejecucion=".$mes_numero.",IF(DB.id_estatusejecucion=1,SUM(DB.costo_bitacora),0),0) AS MesC,
					IF(DB.id_mesejecucion=".$mes_numero.",IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora*DB.gasto_directo),0),0) AS MesG
				FROM T_Clientes AS C
				LEFT OUTER JOIN T_Bitacora AS B
				ON B.id_cliente = C.id_cliente
				LEFT OUTER JOIN T_Detalle_Bitacora AS DB
				ON DB.id_bitacora = B.id_bitacora
				LEFT OUTER JOIN T_Subproductos AS S
				ON S.id_subproducto = DB.id_subproducto
				WHERE NOT C.id_zona = 4 AND YEAR(B.fecha_audit) = :year";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(":year",$year);
		$stmt->execute();
		
		$query = "SELECT 
				SUM(anticipo),
				SUM(mes".$mes_numero."_Ej) AS Mes,
				SUM(mes".$mes_numero."_Ej*costo_ac) AS MesC
			FROM `T_Detalle_AnalisisCuantitativo_Escenario2` AS DAC
			INNER JOIN `T_AnalisisCuantitativo_Escenario2` AS AC
		    ON DAC.id_analisiscuantitativo = AC.id_analisiscuantitativo
			INNER JOIN T_Clientes AS C 
		    ON C.id_cliente = AC.id_cliente 
		    WHERE NOT C.id_zona = 4 AND AC.year_acuant = :year";
		$stmt2 = $this->conn->prepare($query);
		$stmt2->bindparam(":year",$year);
		$stmt2->execute();
		
		$query ="SELECT 
				  N.year_nomina,
				  (SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.intereses)) AS TotalGAO
				  FROM T_Nomina_GAO_Escenario2 AS N
				  INNER JOIN T_Plantilla AS P
				  ON N.id_plantilla = P.id_plantilla
				  INNER JOIN T_PlantillaDatos as PD
				  ON PD.id_plantilladatos = P.id_plantilladatos
				  WHERE NOT PD.id_zona=4 AND year_nomina = :year";
		$stmt3 = $this->conn->prepare($query);
		$stmt3->bindparam(":year",$year);
		$stmt3->execute();
		
		if($stmt2->rowCount()>0)
		{
			$row=$stmt->fetch(PDO::FETCH_ASSOC);
			$row2=$stmt2->fetch(PDO::FETCH_ASSOC);
			$row3=$stmt3->fetch(PDO::FETCH_ASSOC);
			
			//INGRESOS
			$diferencia = $row2['Mes']-$row['MesP'];
			
			//COSTOS
			$diferenciaC = $row2['MesC']-$row['MesC'];
			
			//GAOFIJO
			$gaoMensual = $row3['TotalGAO']/12;
			
			$comision = 0.38;
			
			//COMISION PROYECTADA
			$diferenciaComision = $row2['Mes']*($comision-(($row2['MesC']/$row2['Mes'])+($gaoMensual/$row2['Mes'])));
			
			//COMISION REAL
			
			
			//MARGEN AJUSTADO
			//PROYECTADO
			$margenAjustadoP = $row2['Mes'] - $row2['MesC'] - $gaoMensual - $diferenciaComision;
			//REAL
			$margenAjustadoR = $row['MesP'] - $row['MesC'] - $row['MesG'];
			//DIFERENCIA
			$margenAjustadoD = $margenAjustadoP - $margenAjustadoR;
			
			?>
				<!--INGRESOS-->
				<tr>
					<th rowspan="3">Ingresos</th>
					<th>Proyectado</th>
					<td>$<?php print(number_format($row2['Mes'],2));?></td>
					<td>%</td>
				</tr>
				<tr>
					<th>Real</th>
					<td>$<?php print(number_format($row['MesP'],2));?></td>
					<td>%</td>
				</tr>
				<tr>
					<th>Diferencia</th>
					<td>$<?php print(number_format($row2['Mes']-$row['MesP'],2));?></td>
					<td>%</td>
				</tr>
				<tr>
					<td colspan="4"></td>
				</tr>
				<!--COSTOS-->
				<tr>
					<th rowspan="3">Costos</th>
					<th>Proyectado</th>
					<td>$<?php print(number_format($row2['MesC'],2));?></td>
					<td>%</td>
				</tr>
				<tr>
					<th>Real</th>
					<td>$<?php print(number_format($row['MesC'],2));?></td>
					<td>%</td>
				</tr>
				<tr>
					<th>Diferencia</th>
					<td>$<?php print(number_format($row2['MesC']-$row['MesC'],2));?></td>
					<td>%</td>
				</tr>
				<tr>
					<td colspan="4"></td>
				</tr>
				<!--GASTO DIRECTO-->
				<tr>
					<th rowspan="3">Gasto Directo</th>
					<th>Proyectado</th>
					<td>$<?php print(number_format(/*$row2['EneroC']*/0,2));?></td>
					<td>%</td>
				</tr>
				<tr>
					<th>Real</th>
					<td>$<?php print(number_format($row['MesG'],2));?></td>
					<td>%</td>
				</tr>
				<tr>
					<th>Diferencia</th>
					<td>$<?php print(number_format($row['MesG'],2));?></td>
					<td>%</td>
				</tr>
				<tr>
					<td colspan="4"></td>
				</tr>
				<!--MARGEN DIRECTO-->
				<tr>
					<th rowspan="3">Margen Directo</th>
					<th>Proyectado</th>
					<td>$<?php print(number_format($row2['Mes']-$row2['MesC'],2));?></td>
					<td>%</td>
				</tr>
				<tr>
					<th>Real</th>
					<td>$<?php print(number_format($row['MesP']-$row['MesC'],2));?></td>
					<td>%</td>
				</tr>
				<tr>
					<th>Diferencia</th>
					<td>$<?php print(number_format($diferencia-$diferenciaC,2));?></td>
					<td>%</td>
				</tr>
				<tr>
					<td colspan="4"></td>
				</tr>
				<!--GAO FIJO-->
				<tr>
					<th rowspan="3">GAO Fijo Total</th>
					<th>Proyectado</th>
					<td>$<?php print(number_format($gaoMensual,2));?></td>
					<td>%</td>
				</tr>
				<tr>
					<th>Real</th>
					<td>$<?php print(number_format($gaoMensual,2));?></td>
					<td>%</td>
				</tr>
				<tr>
					<th>Diferencia</th>
					<td>$<?php print(number_format($gaoMensual,2));?></td>
					<td>%</td>
				</tr>
				<tr>
					<td colspan="4"></td>
				</tr>
				<!--GAO VARIABLE-->
				<tr>
					<th rowspan="3">GAO Variable</th>
					<th>Proyectado</th>
					<td>$<?php print(number_format($diferenciaComision,2));?></td>
					<td>%</td>
				</tr>
				<tr>
					<th>Real</th>
					<td>$<?php print(number_format(0,2));?></td>
					<td>%</td>
				</tr>
				<tr>
					<th>Diferencia</th>
					<td>$<?php print(number_format($diferenciaComision,2));?></td>
					<td>%</td>
				</tr>
				<tr>
					<td colspan="4"></td>
				</tr>
				<!--MARGEN AJUSTADO-->
				<tr>
					<th rowspan="3">Margen Ajustado</th>
					<th>Proyectado</th>
					<td>$<?php print(number_format($margenAjustadoP,2));?></td>
					<td>%</td>
				</tr>
				<tr>
					<th>Real</th>
					<td>$<?php print(number_format($margenAjustadoR,2));?></td>
					<td>%</td>
				</tr>
				<tr>
					<th>Diferencia</th>
					<td>$<?php print(number_format($margenAjustadoD,2));?></td>
					<td>%</td>
				</tr>
				<tr>
					<td colspan="4"></td>
				</tr>
			<?php
		}
		else
		{
			?>
            <tr>
				<td colspan="4"></td>
            </tr>
            <?php
		}
	}
	public function estacionariedadAVEFOTrimestreC($year,$trimestre)
	{
		if($trimestre == 1 || $trimestre == 2 || $trimestre == 3)
		{
			$query = "SELECT 
					IF(DB.id_mesejecucion=1,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora),0),0) AS Mes1P,
					IF(DB.id_mesejecucion=2,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora),0),0) AS Mes2P,
					IF(DB.id_mesejecucion=3,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora),0),0) AS Mes3P,
					IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora),0) AS TOTALP,
					IF(DB.id_mesejecucion=1,IF(DB.id_estatusejecucion=1,SUM(DB.costo_bitacora),0),0) AS Mes1C,
					IF(DB.id_mesejecucion=2,IF(DB.id_estatusejecucion=1,SUM(DB.costo_bitacora),0),0) AS Mes2C,
					IF(DB.id_mesejecucion=3,IF(DB.id_estatusejecucion=1,SUM(DB.costo_bitacora),0),0) AS Mes3C,
					IF(DB.id_estatusejecucion=1,SUM(DB.costo_bitacora),0) AS TOTALC,
					IF(DB.id_mesejecucion=1,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora*DB.gasto_directo),0),0) AS Mes1G,
					IF(DB.id_mesejecucion=2,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora*DB.gasto_directo),0),0) AS Mes2G,
					IF(DB.id_mesejecucion=3,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora*DB.gasto_directo),0),0) AS Mes3G,
					IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora*DB.gasto_directo),0) AS TOTALG
				FROM T_Clientes AS C
				LEFT OUTER JOIN T_Bitacora AS B
				ON B.id_cliente = C.id_cliente
				LEFT OUTER JOIN T_Detalle_Bitacora AS DB
				ON DB.id_bitacora = B.id_bitacora
				LEFT OUTER JOIN T_Subproductos AS S
				ON S.id_subproducto = DB.id_subproducto
				WHERE NOT C.id_zona = 4 AND YEAR(B.fecha_audit) = :year";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":year",$year);
			$stmt->execute();
			
			$query = "SELECT 
				SUM(anticipo),
				SUM(mes1_Ej) AS Mes1,
				SUM(mes2_Ej) AS Mes2,
				SUM(mes3_Ej) AS Mes3,
				SUM(mes1_Ej+mes2_Ej+mes3_Ej+mes4_Ej+mes5_Ej+mes6_Ej+mes7_Ej+mes8_Ej+mes9_Ej+mes10_Ej+mes11_Ej+mes12_Ej) AS TOTAL,
				SUM(mes1_Ej*costo_ac) AS Mes1C,
				SUM(mes2_Ej*costo_ac) AS Mes2C,
				SUM(mes3_Ej*costo_ac) AS Mes3C,
				SUM(cuotadef_ac*costo_ac) AS TOTALC
			FROM `T_Detalle_AnalisisCuantitativo_Escenario2` AS DAC
			INNER JOIN `T_AnalisisCuantitativo_Escenario2` AS AC
		    ON DAC.id_analisiscuantitativo = AC.id_analisiscuantitativo
			INNER JOIN T_Clientes AS C 
		    ON C.id_cliente = AC.id_cliente 
		    WHERE NOT C.id_zona = 4 AND AC.year_acuant = :year";
			$stmt2 = $this->conn->prepare($query);
			$stmt2->bindparam(":year",$year);
			$stmt2->execute();
		}
		else if($trimestre == 4 || $trimestre == 5 || $trimestre == 6)
		{
			$query = "SELECT 
					IF(DB.id_mesejecucion=4,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora),0),0) AS Mes1P,
					IF(DB.id_mesejecucion=5,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora),0),0) AS Mes2P,
					IF(DB.id_mesejecucion=6,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora),0),0) AS Mes3P,
					IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora),0) AS TOTALP,
					IF(DB.id_mesejecucion=4,IF(DB.id_estatusejecucion=1,SUM(DB.costo_bitacora),0),0) AS Mes1C,
					IF(DB.id_mesejecucion=5,IF(DB.id_estatusejecucion=1,SUM(DB.costo_bitacora),0),0) AS Mes2C,
					IF(DB.id_mesejecucion=6,IF(DB.id_estatusejecucion=1,SUM(DB.costo_bitacora),0),0) AS Mes3C,
					IF(DB.id_estatusejecucion=1,SUM(DB.costo_bitacora),0) AS TOTALC,
					IF(DB.id_mesejecucion=1,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora*DB.gasto_directo),0),0) AS Mes1G,
					IF(DB.id_mesejecucion=2,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora*DB.gasto_directo),0),0) AS Mes2G,
					IF(DB.id_mesejecucion=3,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora*DB.gasto_directo),0),0) AS Mes3G,
					IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora*DB.gasto_directo),0) AS TOTALG
				FROM T_Clientes AS C
				LEFT OUTER JOIN T_Bitacora AS B
				ON B.id_cliente = C.id_cliente
				LEFT OUTER JOIN T_Detalle_Bitacora AS DB
				ON DB.id_bitacora = B.id_bitacora
				LEFT OUTER JOIN T_Subproductos AS S
				ON S.id_subproducto = DB.id_subproducto
				WHERE NOT C.id_zona = 4 AND YEAR(B.fecha_audit) = :year";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":year",$year);
			$stmt->execute();
			
			$query = "SELECT 
				SUM(anticipo),
				SUM(mes4_Ej) AS Mes1,
				SUM(mes5_Ej) AS Mes2,
				SUM(mes6_Ej) AS Mes3,
				SUM(mes1_Ej+mes2_Ej+mes3_Ej+mes4_Ej+mes5_Ej+mes6_Ej+mes7_Ej+mes8_Ej+mes9_Ej+mes10_Ej+mes11_Ej+mes12_Ej) AS TOTAL,
				SUM(mes4_Ej*costo_ac) AS Mes1C,
				SUM(mes5_Ej*costo_ac) AS Mes2C,
				SUM(mes6_Ej*costo_ac) AS Mes3C,
				SUM(cuotadef_ac*costo_ac) AS TOTALC
			FROM `T_Detalle_AnalisisCuantitativo_Escenario2` AS DAC
			INNER JOIN `T_AnalisisCuantitativo_Escenario2` AS AC
		    ON DAC.id_analisiscuantitativo = AC.id_analisiscuantitativo
			INNER JOIN T_Clientes AS C 
		    ON C.id_cliente = AC.id_cliente 
		    WHERE NOT C.id_zona = 4 AND AC.year_acuant = :year";
			$stmt2 = $this->conn->prepare($query);
			$stmt2->bindparam(":year",$year);
			$stmt2->execute();
		}
		else if($trimestre == 7 || $trimestre == 8 || $trimestre == 9)
		{
			$query = "SELECT 
					IF(DB.id_mesejecucion=7,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora),0),0) AS Mes1P,
					IF(DB.id_mesejecucion=8,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora),0),0) AS Mes2P,
					IF(DB.id_mesejecucion=9,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora),0),0) AS Mes3P,
					IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora),0) AS TOTALP,
					IF(DB.id_mesejecucion=7,IF(DB.id_estatusejecucion=1,SUM(DB.costo_bitacora),0),0) AS Mes1C,
					IF(DB.id_mesejecucion=8,IF(DB.id_estatusejecucion=1,SUM(DB.costo_bitacora),0),0) AS Mes2C,
					IF(DB.id_mesejecucion=9,IF(DB.id_estatusejecucion=1,SUM(DB.costo_bitacora),0),0) AS Mes3C,
					IF(DB.id_estatusejecucion=1,SUM(DB.costo_bitacora),0) AS TOTALC,
					IF(DB.id_mesejecucion=7,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora*DB.gasto_directo),0),0) AS Mes1G,
					IF(DB.id_mesejecucion=8,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora*DB.gasto_directo),0),0) AS Mes2G,
					IF(DB.id_mesejecucion=9,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora*DB.gasto_directo),0),0) AS Mes3G,
					IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora*DB.gasto_directo),0) AS TOTALG
				FROM T_Clientes AS C
				LEFT OUTER JOIN T_Bitacora AS B
				ON B.id_cliente = C.id_cliente
				LEFT OUTER JOIN T_Detalle_Bitacora AS DB
				ON DB.id_bitacora = B.id_bitacora
				LEFT OUTER JOIN T_Subproductos AS S
				ON S.id_subproducto = DB.id_subproducto
				WHERE NOT C.id_zona = 4 AND YEAR(B.fecha_audit) = :year";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":year",$year);
			$stmt->execute();
		
			$query = "SELECT 
				SUM(anticipo),
				SUM(mes7_Ej) AS Mes1,
				SUM(mes8_Ej) AS Mes2,
				SUM(mes9_Ej) AS Mes3,
				SUM(mes1_Ej+mes2_Ej+mes3_Ej+mes4_Ej+mes5_Ej+mes6_Ej+mes7_Ej+mes8_Ej+mes9_Ej+mes10_Ej+mes11_Ej+mes12_Ej) AS TOTAL,
				SUM(mes7_Ej*costo_ac) AS Mes1C,
				SUM(mes8_Ej*costo_ac) AS Mes2C,
				SUM(mes9_Ej*costo_ac) AS Mes3C,
				SUM(cuotadef_ac*costo_ac) AS TOTALC
			FROM `T_Detalle_AnalisisCuantitativo_Escenario2` AS DAC
			INNER JOIN `T_AnalisisCuantitativo_Escenario2` AS AC
		    ON DAC.id_analisiscuantitativo = AC.id_analisiscuantitativo
			INNER JOIN T_Clientes AS C 
		    ON C.id_cliente = AC.id_cliente 
		    WHERE NOT C.id_zona = 4 AND AC.year_acuant = :year";
			$stmt2 = $this->conn->prepare($query);
			$stmt2->bindparam(":year",$year);
			$stmt2->execute();
		}
		else
		{
			$query = "SELECT 
					IF(DB.id_mesejecucion=10,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora),0),0) AS Mes1P,
					IF(DB.id_mesejecucion=11,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora),0),0) AS Mes2P,
					IF(DB.id_mesejecucion=12,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora),0),0) AS Mes3P,
					IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora),0) AS TOTALP,
					IF(DB.id_mesejecucion=10,IF(DB.id_estatusejecucion=1,SUM(DB.costo_bitacora),0),0) AS Mes1C,
					IF(DB.id_mesejecucion=11,IF(DB.id_estatusejecucion=1,SUM(DB.costo_bitacora),0),0) AS Mes2C,
					IF(DB.id_mesejecucion=12,IF(DB.id_estatusejecucion=1,SUM(DB.costo_bitacora),0),0) AS Mes3C,
					IF(DB.id_estatusejecucion=1,SUM(DB.costo_bitacora),0) AS TOTALC,
					IF(DB.id_mesejecucion=10,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora*DB.gasto_directo),0),0) AS Mes1G,
					IF(DB.id_mesejecucion=11,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora*DB.gasto_directo),0),0) AS Mes2G,
					IF(DB.id_mesejecucion=12,IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora*DB.gasto_directo),0),0) AS Mes3G,
					IF(DB.id_estatusejecucion=1,SUM(DB.precio_bitacora*DB.gasto_directo),0) AS TOTALG
				FROM T_Clientes AS C
				LEFT OUTER JOIN T_Bitacora AS B
				ON B.id_cliente = C.id_cliente
				LEFT OUTER JOIN T_Detalle_Bitacora AS DB
				ON DB.id_bitacora = B.id_bitacora
				LEFT OUTER JOIN T_Subproductos AS S
				ON S.id_subproducto = DB.id_subproducto
				WHERE NOT C.id_zona = 4 AND YEAR(B.fecha_audit) = :year";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":year",$year);
			$stmt->execute();
		
			$query = "SELECT 
				SUM(anticipo),
				SUM(mes10_Ej) AS Mes1,
				SUM(mes11_Ej) AS Mes2,
				SUM(mes12_Ej) AS Mes3,
				SUM(mes1_Ej+mes2_Ej+mes3_Ej+mes4_Ej+mes5_Ej+mes6_Ej+mes7_Ej+mes8_Ej+mes9_Ej+mes10_Ej+mes11_Ej+mes12_Ej) AS TOTAL,
				SUM(mes10_Ej*costo_ac) AS Mes1C,
				SUM(mes11_Ej*costo_ac) AS Mes2C,
				SUM(mes12_Ej*costo_ac) AS Mes3C,
				SUM(cuotadef_ac*costo_ac) AS TOTALC
			FROM `T_Detalle_AnalisisCuantitativo_Escenario2` AS DAC
			INNER JOIN `T_AnalisisCuantitativo_Escenario2` AS AC
		    ON DAC.id_analisiscuantitativo = AC.id_analisiscuantitativo
			INNER JOIN T_Clientes AS C 
		    ON C.id_cliente = AC.id_cliente 
		    WHERE NOT C.id_zona = 4 AND AC.year_acuant = :year";
			$stmt2 = $this->conn->prepare($query);
			$stmt2->bindparam(":year",$year);
			$stmt2->execute();
		}
		
		$query ="SELECT 
				  N.year_nomina,
				  (SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.intereses)) AS TotalGAO
				  FROM T_Nomina_GAO_Escenario2 AS N
				  INNER JOIN T_Plantilla AS P
				  ON N.id_plantilla = P.id_plantilla
				  INNER JOIN T_PlantillaDatos as PD
				  ON PD.id_plantilladatos = P.id_plantilladatos
				  WHERE NOT PD.id_zona=4 AND year_nomina = :year";
		$stmt3 = $this->conn->prepare($query);
		$stmt3->bindparam(":year",$year);
		$stmt3->execute();
		
		if($stmt2->rowCount()>0)
		{
			$row=$stmt->fetch(PDO::FETCH_ASSOC);
			$row2=$stmt2->fetch(PDO::FETCH_ASSOC);
			$row3=$stmt3->fetch(PDO::FETCH_ASSOC);
			
			//INGRESOS
			$diferencia1 = $row2['Mes1']-$row['Mes1P'];
			$diferencia2 = $row2['Mes2']-$row['Mes2P'];
			$diferencia3 = $row2['Mes3']-$row['Mes3P'];
			$mes1 = $diferencia1;
			$mes2 = ($mes1+$row2['Mes2'])-$row['Mes2P'];
			$mes3 = ($mes2+$row2['Mes3'])-$row['Mes3P'];
			
			//COSTOS
			$diferencia1C = $row2['Mes1C']-$row['Mes1C'];
			$diferencia2C = $row2['Mes2C']-$row['Mes2C'];
			$diferencia3C = $row2['Mes3C']-$row['Mes3C'];
			$mes1C = $diferencia1C;
			$mes2C = ($mes1C+$row2['Mes2C'])-$row['Mes3C'];
			$mes3C = ($mes2C+$row2['Mes2C'])-$row['Mes3C'];
			
			//GAOFIJO
			$gaoMensual = $row3['TotalGAO']/12;
			
			//COMISION PROYECTADA
			$comision = 0.38;
			
			//COMISION PROYECTADA
			$diferenciaComision1 = $row2['Mes1']*($comision-(($row2['Mes1C']/$row2['Mes1'])+($gaoMensual/$row2['Mes1'])));
			$diferenciaComision2 = $row2['Mes2']*($comision-(($row2['Mes2C']/$row2['Mes2'])+($gaoMensual/$row2['Mes2'])));
			$diferenciaComision3 = $row2['Mes3']*($comision-(($row2['Mes3C']/$row2['Mes3'])+($gaoMensual/$row2['Mes3'])));
			$comision1 = $diferenciaComision1;
			$comision2 = $diferenciaComision2+$comision1;
			$comision3 = $diferenciaComision3+$comision2;
			//COMISION REAL
			
			
			//MARGEN AJUSTADO
			//PROYECTADO
			$margenAjustado1P = $row2['Mes1'] - $row2['Mes1C'] - $gaoMensual - $diferenciaComision1;
			$margenAjustado2P = $row2['Mes2'] - $row2['Mes2C'] - $gaoMensual - $diferenciaComision2;
			$margenAjustado3P = $row2['Mes3'] - $row2['Mes3C'] - $gaoMensual - $diferenciaComision3;
			//REAL
			$margenAjustado1R = $row['Mes1P'] - $row['Mes1C'] - $row['Mes1G'];
			$margenAjustado2R = $row['Mes2P'] - $row['Mes2C'] - $row['Mes2G'];
			$margenAjustado3R = $row['Mes3P'] - $row['Mes3C'] - $row['Mes3G'];
			//DIFERENCIA
			$margenAjustado1D = $margenAjustado1P - $margenAjustado1R;
			$margenAjustado2D = $margenAjustado2P - $margenAjustado2R;
			$margenAjustado3D = $margenAjustado3P - $margenAjustado3R;
			//AJUSTE - REAL
			$margenAjustado1A = $margenAjustado1P - $margenAjustado1R;
			$margenAjustado2A = ($margenAjustado2P + $margenAjustado1A) - $margenAjustado2R;
			$margenAjustado3A = ($margenAjustado3P + $margenAjustado2A) - $margenAjustado3R;
			//AJUSTE - REAL
			$margenAjustado1B = $margenAjustado1P - $margenAjustado1R;
			$margenAjustado2B = ($margenAjustado2P + $margenAjustado1A) - $margenAjustado2R;
			$margenAjustado3B = ($margenAjustado3P + $margenAjustado2A) - $margenAjustado3R;
			
			?>
				<!--INGRESOS-->
				<tr>
					<th rowspan="4">Ingresos</th>
					<th>Proyectado</th>
					<td>$<?php print(number_format($row2['Mes1'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($row2['Mes2'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($row2['Mes3'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($row2['Mes1']+$row2['Mes2']+$row2['Mes3'],2));?></td>
					<td>100%</td>
				</tr>
				<tr>
					<th>Ajuste</th>
					<td>$0.00</td>
					<td>10%</td>
					<td>$<?php print(number_format($mes1,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($mes2,2));?></td>
					<td>10%</td>
					<td>$0.00</td>
					<td>100%</td>
				</tr>
				<tr>
					<th>Real</th>
					<td>$<?php print(number_format($row['Mes1P'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($row['Mes2P'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($row['Mes3P'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($row['Mes1P']+$row['Mes2P']+$row['Mes3P'],2));?></td>
					<td>100%</td>
				</tr>
				<tr>
					<th>Diferencia</th>
					<td>$<?php print(number_format($mes1,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($mes2,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($mes3,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format(($row2['Mes1']+$row2['Mes2']+$row2['Mes3'])-($row['Mes1P']+$row['Mes2P']+$row['Mes3P']),2));?></td>
					<td>100%</td>
				</tr>
				<tr>
					<td colspan="9"></td>
				</tr>
				<!--COSTOS-->
				<tr>
					<th rowspan="4">Costos</th>
					<th>Proyectado</th>
					<td>$<?php print(number_format($row2['Mes1C'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($row2['Mes2C'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($row2['Mes3C'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($row2['Mes1C']+$row2['Mes2C']+$row2['Mes3C'],2));?></td>
					<td>100%</td>
				</tr>
				<tr>
					<th>Ajuste</th>
					<td>$0.00</td>
					<td>10%</td>
					<td>$<?php print(number_format($mes1C,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($mes2C,2));?></td>
					<td>10%</td>
					<td>$0.00</td>
					<td>100%</td>
				</tr>
				<tr>
					<th>Real</th>
					<td>$<?php print(number_format($row['Mes1C'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($row['Mes2C'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($row['Mes3C'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($row['Mes1C']+$row['Mes2C']+$row['Mes3C'],2));?></td>
					<td>100%</td>
				</tr>
				<tr>
					<th>Diferencia</th>
					<td>$<?php print(number_format($mes1C,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($mes2C,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($mes3C,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format(($row2['Mes1C']+$row2['Mes2C']+$row2['Mes3C'])-($row['Mes1C']+$row['Mes2C']+$row['Mes3C']),2));?></td>
					<td>100%</td>
				</tr>
				<tr>
					<td colspan="9"></td>
				</tr>
				<!--GASTO DIRECTO-->
				<tr>
					<th rowspan="4">Gasto Directo</th>
					<th>Proyectado</th>
					<td>$<?php print(number_format(/*$row2['EneroC']*/0,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format(/*$row2['FebreroC']*/0,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format(/*$row2['MarzoC']*/0,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format(/*$row2['TOTALC']*/0,2));?></td>
					<td>100%</td>
				</tr>
				<tr>
					<th>Ajuste</th>
					<td>$0.00</td>
					<td>10%</td>
					<td>$<?php print(number_format(/*$eneroC*/0,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format(/*$febreroC*/0,2));?></td>
					<td>10%</td>
					<td>$0.00</td>
					<td>100%</td>
				</tr>
				<tr>
					<th>Real</th>
					<td>$<?php print(number_format($row['Mes1G'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($row['Mes2G'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($row['Mes3G'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($row['Mes1G']+$row['Mes2G']+$row['Mes3G'],2));?></td>
					<td>100%</td>
				</tr>
				<tr>
					<th>Diferencia</th>
					<td>$<?php print(number_format($row['Mes1G'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($row['Mes2G'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($row['Mes3G'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($row['Mes1G']+$row['Mes2G']+$row['Mes3G'],2));?></td>
					<td>100%</td>
				</tr>
				<tr>
					<td colspan="9"></td>
				</tr>
				<!--MARGEN DIRECTO-->
				<tr>
					<th rowspan="4">Margen Directo</th>
					<th>Proyectado</th>
					<td>$<?php print(number_format($row2['Mes1']-$row2['Mes1C'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($row2['Mes2']-$row2['Mes2C'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($row2['Mes3']-$row2['Mes3C'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format(($row2['Mes1']-$row2['Mes1C'])-($row2['Mes2']-$row2['Mes2C'])-($row2['Mes3']-$row2['Mes3C']),2));?></td>
					<td>100%</td>
				</tr>
				<tr>
					<th>Ajuste</th>
					<td>$0.00</td>
					<td>10%</td>
					<td>$<?php print(number_format(($mes1)-($mes1C),2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format(($mes2)-($mes2C),2));?></td>
					<td>10%</td>
					<td>$0.00</td>
					<td>100%</td>
				</tr>
				<tr>
					<th>Real</th>
					<td>$<?php print(number_format($row['Mes1P']-$row['Mes1C']-$row['Mes1G'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($row['Mes2P']-$row['Mes2C']-$row['Mes2G'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($row['Mes3P']-$row['Mes3C']-$row['Mes3G'],2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format(($row['Mes1P']-$row['Mes1C']-$row['Mes1G'])-($row['Mes2P']-$row['Mes2C']-$row['Mes2G'])-($row['Mes3P']-$row['Mes3C']-$row['Mes3G']),2));?></td>
					<td>100%</td>
				</tr>
				<tr>
					<th>Diferencia</th>
					<td>$<?php print(number_format($mes1-$mes1C,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($mes2-$mes2C,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($mes3-$mes3C,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format((($row2['Mes1']-$row2['Mes1C'])-($row2['Mes2']-$row2['Mes2C'])-($row2['Mes3']-$row2['Mes3C']))-(($row['Mes1P']-$row['Mes1C']-$row['Mes1G'])-($row['Mes2P']-$row['Mes2C']-$row['Mes2G'])-($row['Mes3P']-$row['Mes3C']-$row['Mes3G'])),2));?></td>
					<td>100%</td>
				</tr>
				<tr>
					<td colspan="9"></td>
				</tr>
				<!--GAO FIJO-->
				<tr>
					<th rowspan="4">GAO Fijo Total</th>
					<th>Proyectado</th>
					<td>$<?php print(number_format($gaoMensual,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($gaoMensual,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($gaoMensual,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format(($gaoMensual*3),2));?></td>
					<td>100%</td>
				</tr>
				<tr>
					<th>Ajuste</th>
					<td>$0.00</td>
					<td>10%</td>
					<td>$<?php print(number_format($gaoMensual,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($gaoMensual*2,2));?></td>
					<td>10%</td>
					<td>$0.00</td>
					<td>100%</td>
				</tr>
				<tr>
					<th>Real</th>
					<td>$<?php print(number_format(0,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format(0,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format(0,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format(0,2));?></td>
					<td>100%</td>
				</tr>
				<tr>
					<th>Diferencia</th>
					<td>$<?php print(number_format($gaoMensual,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format((($gaoMensual*2))-0,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format((($gaoMensual*3))-0,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format(($gaoMensual*3),2));?></td>
					<td>100%</td>
				</tr>
				<tr>
					<td colspan="9"></td>
				</tr>
				<!--GAO VARIABLE-->
				<tr>
					<th rowspan="4">GAO Variable</th>
					<th>Proyectado</th>
					<td>$<?php print(number_format($diferenciaComision1,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($diferenciaComision2,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($diferenciaComision3,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($diferenciaComision1+$diferenciaComision2+$diferenciaComision3,2));?></td>
					<td>100%</td>
				</tr>
				<tr>
					<th>Ajuste</th>
					<td>$0.00</td>
					<td>10%</td>
					<td>$<?php print(number_format($comision1,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($diferenciaComision2+$comision1,2));?></td>
					<td>10%</td>
					<td>$0.00</td>
					<td>100%</td>
				</tr>
				<tr>
					<th>Real</th>
					<td>$<?php print(number_format(0,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format(0,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format(0,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format(0,2));?></td>
					<td>100%</td>
				</tr>
				<tr>
					<th>Diferencia</th>
					<td>$<?php print(number_format($comision1,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($comision2,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($comision3,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($comision1+$comision2+$comision3,2));?></td>
					<td>100%</td>
				</tr>
				<tr>
					<td colspan="9"></td>
				</tr>
				<!--MARGEN AJUSTADO-->
				<tr>
					<th rowspan="4">Margen Ajustado</th>
					<th>Proyectado</th>
					<td>$<?php print(number_format($margenAjustado1P,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($margenAjustado2P,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($margenAjustado3P,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($margenAjustado1P+$margenAjustado2P+$margenAjustado3P,2));?></td>
					<td>100%</td>
				</tr>
				<tr>
					<th>Ajuste</th>
					<td>$0.00</td>
					<td>10%</td>
					<td>$<?php print(number_format($margenAjustado1A,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($margenAjustado2A,2));?></td>
					<td>10%</td>
					<td>$0.00</td>
					<td>100%</td>
				</tr>
				<tr>
					<th>Real</th>
					<td>$<?php print(number_format($margenAjustado1R,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($margenAjustado2R,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($margenAjustado3R,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($margenAjustado1R+$margenAjustado2R+$margenAjustado3R,2));?></td>
					<td>100%</td>
				</tr>
				<tr>
					<th>Diferencia</th>
					<td>$<?php print(number_format($margenAjustado1A,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($margenAjustado2A,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($margenAjustado3A,2));?></td>
					<td>10%</td>
					<td>$<?php print(number_format($margenAjustado1A-$margenAjustado2A-$margenAjustado3A,2));?></td>
					<td>100%</td>
				</tr>
				<tr>
					<td colspan="9"></td>
				</tr>
			<?php
		}
		else
		{
			?>
            <tr>
				<td colspan="9"></td>
            </tr>
            <?php
		}
	}
	
	
	//NUEVA ESTACIONARIEDAD
	//LIDER
	public function estacionariedadNew($zona,$year)
	{
	    $this->conn->beginTransaction();
		try
		{
		    //ESCENARIO 1
		    $query = "SELECT 
				nombre_proyecto,
				anticipo,
				cuotadef_ac,
				mes1_Ej,mes2_Ej,mes3_Ej,mes4_Ej,mes5_Ej,mes6_Ej,mes7_Ej,mes8_Ej,mes9_Ej,mes10_Ej,mes11_Ej,mes12_Ej,
				mes1_Fa,mes2_Fa,mes3_Fa,mes4_Fa,mes5_Fa,mes6_Fa,mes7_Fa,mes8_Fa,mes9_Fa,mes10_Fa,mes11_Fa,mes12_Fa,
				mes1_Co,mes2_Co,mes3_Co,mes4_Co,mes5_Co,mes6_Co,mes7_Co,mes8_Co,mes9_Co,mes10_Co,mes11_Co,mes12_Co
		    FROM `T_Detalle_AnalisisCuantitativo_Escenario2` AS DAC
			INNER JOIN `T_AnalisisCuantitativo_Escenario2` AS AC
		    ON DAC.id_analisiscuantitativo = AC.id_analisiscuantitativo
			INNER JOIN T_Clientes AS C 
		    ON C.id_cliente = AC.id_cliente 
		    WHERE C.id_zona = :zona AND AC.year_acuant = :year";
		    
		    $stmt = $this->conn->prepare($query);
			$stmt->bindparam(":zona",$zona);
			$stmt->bindparam(":year",$year);
			$stmt->execute();
			
			while($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
			?>
				<tr class="">
					<td rowspan="3"><?php print($row['nombre_proyecto']);?></td>
						<td>Ejecución</td>
						<td></td>
						<td><?php print(number_format($row['mes1_Ej'],2));?></td> 
						<td><?php print(number_format($row['mes2_Ej'],2));?></td> 
						<td><?php print(number_format($row['mes3_Ej'],2));?></td> 
						<td><?php print(number_format($row['mes4_Ej'],2));?></td> 
						<td><?php print(number_format($row['mes5_Ej'],2));?></td> 
						<td><?php print(number_format($row['mes6_Ej'],2));?></td> 
						<td><?php print(number_format($row['mes7_Ej'],2));?></td> 
						<td><?php print(number_format($row['mes8_Ej'],2));?></td> 
						<td><?php print(number_format($row['mes9_Ej'],2));?></td> 
						<td><?php print(number_format($row['mes10_Ej'],2));?></td> 
						<td><?php print(number_format($row['mes11_Ej'],2));?></td> 
						<td><?php print(number_format($row['mes12_Ej'],2));?></td> 
						<td><?php print(number_format($row['cuotadef_ac'],2));?></td> 
					</tr>
				<tr class="">
					<td>Facturación</td>
					<td><?php print(number_format($row['anticipo'],2));?></td>
					<td><?php print(number_format($row['mes1_Fa'],2));?></td> 
					<td><?php print(number_format($row['mes2_Fa'],2));?></td> 
					<td><?php print(number_format($row['mes3_Fa'],2));?></td> 
					<td><?php print(number_format($row['mes4_Fa'],2));?></td> 
					<td><?php print(number_format($row['mes5_Fa'],2));?></td> 
					<td><?php print(number_format($row['mes6_Fa'],2));?></td> 
					<td><?php print(number_format($row['mes7_Fa'],2));?></td> 
					<td><?php print(number_format($row['mes8_Fa'],2));?></td> 
					<td><?php print(number_format($row['mes9_Fa'],2));?></td> 
					<td><?php print(number_format($row['mes10_Fa'],2));?></td> 
					<td><?php print(number_format($row['mes11_Fa'],2));?></td> 
					<td><?php print(number_format($row['mes12_Fa'],2));?></td> 
					<td><?php print(number_format($row['cuotadef_ac'],2));?></td> 
				</tr>
				<tr class="">
					<td>Cobranza</td>
					<td><?php print(number_format($row['anticipo'],2));?></td>
					<td><?php print(number_format($row['mes1_Co'],2));?></td> 
					<td><?php print(number_format($row['mes2_Co'],2));?></td> 
					<td><?php print(number_format($row['mes3_Co'],2));?></td> 
					<td><?php print(number_format($row['mes4_Co'],2));?></td> 
					<td><?php print(number_format($row['mes5_Co'],2));?></td> 
					<td><?php print(number_format($row['mes6_Co'],2));?></td> 
					<td><?php print(number_format($row['mes7_Co'],2));?></td> 
					<td><?php print(number_format($row['mes8_Co'],2));?></td> 
					<td><?php print(number_format($row['mes9_Co'],2));?></td> 
					<td><?php print(number_format($row['mes10_Co'],2));?></td> 
					<td><?php print(number_format($row['mes11_Co'],2));?></td> 
					<td><?php print(number_format($row['mes12_Co'],2));?></td>
					<td><?php print(number_format($row['cuotadef_ac'],2));?></td>
				</tr>
			<?php
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
	//TERRITORIO
	public function estacionariedadNew_Territorio($territorio,$year)
	{
	    $this->conn->beginTransaction();
		try
		{
		    //ESCENARIO 1
		    $query = "SELECT 
				nombre_proyecto,
				anticipo,
				cuotadef_ac,
				mes1_Ej,mes2_Ej,mes3_Ej,mes4_Ej,mes5_Ej,mes6_Ej,mes7_Ej,mes8_Ej,mes9_Ej,mes10_Ej,mes11_Ej,mes12_Ej,
				mes1_Fa,mes2_Fa,mes3_Fa,mes4_Fa,mes5_Fa,mes6_Fa,mes7_Fa,mes8_Fa,mes9_Fa,mes10_Fa,mes11_Fa,mes12_Fa,
				mes1_Co,mes2_Co,mes3_Co,mes4_Co,mes5_Co,mes6_Co,mes7_Co,mes8_Co,mes9_Co,mes10_Co,mes11_Co,mes12_Co
		    FROM `T_Detalle_AnalisisCuantitativo_Escenario2` AS DAC
			INNER JOIN `T_AnalisisCuantitativo_Escenario2` AS AC
		    ON DAC.id_analisiscuantitativo = AC.id_analisiscuantitativo
			INNER JOIN T_Clientes AS C 
		    ON C.id_cliente = AC.id_cliente 
		    INNER JOIN T_TerritorioZona AS TZ
			ON TZ.id_zona = C.id_zona
		    WHERE TZ.id_territorio = :territorio AND AC.year_acuant = :year";
		    
		    $stmt = $this->conn->prepare($query);
			$stmt->bindparam(":territorio",$territorio);
			$stmt->bindparam(":year",$year);
			$stmt->execute();
			
			while($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
			?>
				<tr class="">
					<td rowspan="3"><?php print($row['nombre_proyecto']);?></td>
						<td>Ejecución</td>
						<td></td>
						<td><?php print(number_format($row['mes1_Ej'],2));?></td> 
						<td><?php print(number_format($row['mes2_Ej'],2));?></td> 
						<td><?php print(number_format($row['mes3_Ej'],2));?></td> 
						<td><?php print(number_format($row['mes4_Ej'],2));?></td> 
						<td><?php print(number_format($row['mes5_Ej'],2));?></td> 
						<td><?php print(number_format($row['mes6_Ej'],2));?></td> 
						<td><?php print(number_format($row['mes7_Ej'],2));?></td> 
						<td><?php print(number_format($row['mes8_Ej'],2));?></td> 
						<td><?php print(number_format($row['mes9_Ej'],2));?></td> 
						<td><?php print(number_format($row['mes10_Ej'],2));?></td> 
						<td><?php print(number_format($row['mes11_Ej'],2));?></td> 
						<td><?php print(number_format($row['mes12_Ej'],2));?></td> 
						<td><?php print(number_format($row['cuotadef_ac'],2));?></td> 
					</tr>
				<tr class="">
					<td>Facturación</td>
					<td><?php print(number_format($row['anticipo'],2));?></td>
					<td><?php print(number_format($row['mes1_Fa'],2));?></td> 
					<td><?php print(number_format($row['mes2_Fa'],2));?></td> 
					<td><?php print(number_format($row['mes3_Fa'],2));?></td> 
					<td><?php print(number_format($row['mes4_Fa'],2));?></td> 
					<td><?php print(number_format($row['mes5_Fa'],2));?></td> 
					<td><?php print(number_format($row['mes6_Fa'],2));?></td> 
					<td><?php print(number_format($row['mes7_Fa'],2));?></td> 
					<td><?php print(number_format($row['mes8_Fa'],2));?></td> 
					<td><?php print(number_format($row['mes9_Fa'],2));?></td> 
					<td><?php print(number_format($row['mes10_Fa'],2));?></td> 
					<td><?php print(number_format($row['mes11_Fa'],2));?></td> 
					<td><?php print(number_format($row['mes12_Fa'],2));?></td> 
					<td><?php print(number_format($row['cuotadef_ac'],2));?></td> 
				</tr>
				<tr class="">
					<td>Cobranza</td>
					<td><?php print(number_format($row['anticipo'],2));?></td>
					<td><?php print(number_format($row['mes1_Co'],2));?></td> 
					<td><?php print(number_format($row['mes2_Co'],2));?></td> 
					<td><?php print(number_format($row['mes3_Co'],2));?></td> 
					<td><?php print(number_format($row['mes4_Co'],2));?></td> 
					<td><?php print(number_format($row['mes5_Co'],2));?></td> 
					<td><?php print(number_format($row['mes6_Co'],2));?></td> 
					<td><?php print(number_format($row['mes7_Co'],2));?></td> 
					<td><?php print(number_format($row['mes8_Co'],2));?></td> 
					<td><?php print(number_format($row['mes9_Co'],2));?></td> 
					<td><?php print(number_format($row['mes10_Co'],2));?></td> 
					<td><?php print(number_format($row['mes11_Co'],2));?></td> 
					<td><?php print(number_format($row['mes12_Co'],2));?></td>
					<td><?php print(number_format($row['cuotadef_ac'],2));?></td>
				</tr>
			<?php
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
	
	
	//ESTACIONARIEDAD - PLANEACIÓN TERRITORIO
	public function estacionariedad1View_Territorio($zona,$year)
	{
	    $this->conn->beginTransaction();
		try
		{
		    //ESCENARIO 1
		    $query = "SELECT IF(SUM(mes1) IS NULL,0,SUM(mes1))AS one, 
    		    IF(SUM(mes2) IS NULL,0,SUM(mes2)) as two, 
    		    IF(SUM(mes3) IS NULL,0,SUM(mes3)) as three, 
    		    IF(SUM(mes4) IS NULL,0,SUM(mes4)) as four, 
    		    IF(SUM(mes5) IS NULL,0,SUM(mes5)) as five, 
    		    IF(SUM(mes6) IS NULL,0,SUM(mes6)) as six, 
    		    IF(SUM(mes7) IS NULL,0,SUM(mes7)) as seven, 
    		    IF(SUM(mes8) IS NULL,0,SUM(mes8)) as eight, 
    		    IF(SUM(mes9) IS NULL,0,SUM(mes9)) as nine, 
    		    IF(SUM(mes10) IS NULL,0,SUM(mes10)) as ten, 
    		    IF(SUM(mes11) IS NULL,0,SUM(mes11)) as eleven, 
    		    IF(SUM(mes12) IS NULL,0,SUM(mes12)) as twelve,
    		    IF(SUM(mes1)+SUM(mes2)+SUM(mes3)+SUM(mes4)+SUM(mes5)+SUM(mes6)+SUM(mes7)+SUM(mes8)+SUM(mes9)+SUM(mes10)+SUM(mes11)+SUM(mes12) IS NULL,0,
    		    SUM(mes1)+SUM(mes2)+SUM(mes3)+SUM(mes4)+SUM(mes5)+SUM(mes6)+SUM(mes7)+SUM(mes8)+SUM(mes9)+SUM(mes10)+SUM(mes11)+SUM(mes12)) as total
		    FROM `T_AnalisisCuantitativo_Escenario1` AS AC 
		    INNER JOIN T_Clientes AS C 
		    ON C.id_cliente = AC.id_cliente 
		    WHERE C.id_zona = :zona AND AC.year_acuant = :year";
		    
		    $stmt = $this->conn->prepare($query);
			$stmt->bindparam(":zona",$zona);
			$stmt->bindparam(":year",$year);
			$stmt->execute();
			
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			?>
			    <div class="col-md-12">
                    <div class="dashboard-box dashboard-box-chart bg-white content-box">
                        <div class="content-wrapper">
                            <div class="header">
                                $ <?php print(number_format($row['total'],2));?>
                                <span><b>Estacionariedad - Escenario 1</b></span>
                            </div>
                            <div class="bs-label bg-default">+0%</div>
                            <div class="center-div sparkline-big-alt"><?php print $row['one'];?>,<?php print $row['two'];?>,<?php print $row['three'];?>,<?php print $row['four'];?>,
                            <?php print $row['five'];?>,<?php print $row['six'];?>,<?php print $row['seven'];?>,<?php print $row['eight'];?>,<?php print $row['nine'];?>,
                            <?php print $row['ten'];?>,<?php print $row['eleven'];?>,<?php print $row['twelve'];?></div>
                            <div class="row list-grade">
                                <div class="col-md-1">Enero</div>
                                <div class="col-md-1">Febrero</div>
                                <div class="col-md-1">Marzo</div>
                                <div class="col-md-1">Abril</div>
                                <div class="col-md-1">Mayo</div>
                                <div class="col-md-1">Junio</div>
            					<div class="col-md-1">Julio</div>
                                <div class="col-md-1">Agosto</div>
                                <div class="col-md-1">Septiembre</div>
                                <div class="col-md-1">Octubre</div>
                                <div class="col-md-1">Noviembre</div>
                                <div class="col-md-1">Diciembre</div>
                            </div>
                        </div>
                        <div class="button-pane">
                            <div class="size-md float-left">
                                <a href="#" title="">
                                    Ver detalles
                                </a>
                            </div>
                            <a href="#" class="btn btn-info float-right tooltip-button" data-placement="top" title="View details">
                                <i class="glyph-icon icon-angle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
			<?php
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
	public function estacionariedad2View_Territorio($zona,$year)
	{
	    $this->conn->beginTransaction();
		try
		{
		    //ESCENARIO 2
		    $query = "SELECT IF(SUM(mes1) IS NULL,0,SUM(mes1))AS one, 
    		    IF(SUM(mes2) IS NULL,0,SUM(mes2)) as two, 
    		    IF(SUM(mes3) IS NULL,0,SUM(mes3)) as three, 
    		    IF(SUM(mes4) IS NULL,0,SUM(mes4)) as four, 
    		    IF(SUM(mes5) IS NULL,0,SUM(mes5)) as five, 
    		    IF(SUM(mes6) IS NULL,0,SUM(mes6)) as six, 
    		    IF(SUM(mes7) IS NULL,0,SUM(mes7)) as seven, 
    		    IF(SUM(mes8) IS NULL,0,SUM(mes8)) as eight, 
    		    IF(SUM(mes9) IS NULL,0,SUM(mes9)) as nine, 
    		    IF(SUM(mes10) IS NULL,0,SUM(mes10)) as ten, 
    		    IF(SUM(mes11) IS NULL,0,SUM(mes11)) as eleven, 
    		    IF(SUM(mes12) IS NULL,0,SUM(mes12)) as twelve,
    		    IF(SUM(mes1)+SUM(mes2)+SUM(mes3)+SUM(mes4)+SUM(mes5)+SUM(mes6)+SUM(mes7)+SUM(mes8)+SUM(mes9)+SUM(mes10)+SUM(mes11)+SUM(mes12) IS NULL,0,
    		    SUM(mes1)+SUM(mes2)+SUM(mes3)+SUM(mes4)+SUM(mes5)+SUM(mes6)+SUM(mes7)+SUM(mes8)+SUM(mes9)+SUM(mes10)+SUM(mes11)+SUM(mes12)) as total
		    FROM `T_AnalisisCuantitativo_Escenario2` AS AC 
		    INNER JOIN T_Clientes AS C 
		    ON C.id_cliente = AC.id_cliente
			INNER JOIN T_TerritorioZona AS TZ
			ON TZ.id_zona = C.id_zona
		    WHERE TZ.id_territorio = :zona AND AC.year_acuant = :year";
		    
		    $stmt = $this->conn->prepare($query);
			$stmt->bindparam(":zona",$zona);
			$stmt->bindparam(":year",$year);
			$stmt->execute();
			
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			?>
			    <div class="col-md-12">
                    <div class="dashboard-box dashboard-box-chart bg-white content-box">
                        <div class="content-wrapper">
                            <div class="header">
                                $ <?php print(number_format($row['total'],2));?>
                                <span><b>Estacionariedad - Escenario 2</b></span>
                            </div>
                            <div class="bs-label bg-default">+0%</div>
                            <div class="center-div sparkline-big-alt"><?php print $row['one'];?>,<?php print $row['two'];?>,<?php print $row['three'];?>,<?php print $row['four'];?>,
                            <?php print $row['five'];?>,<?php print $row['six'];?>,<?php print $row['seven'];?>,<?php print $row['eight'];?>,<?php print $row['nine'];?>,
                            <?php print $row['ten'];?>,<?php print $row['eleven'];?>,<?php print $row['twelve'];?></div>
                            <div class="row list-grade">
                                <div class="col-md-1">Enero</div>
                                <div class="col-md-1">Febrero</div>
                                <div class="col-md-1">Marzo</div>
                                <div class="col-md-1">Abril</div>
                                <div class="col-md-1">Mayo</div>
                                <div class="col-md-1">Junio</div>
            					<div class="col-md-1">Julio</div>
                                <div class="col-md-1">Agosto</div>
                                <div class="col-md-1">Septiembre</div>
                                <div class="col-md-1">Octubre</div>
                                <div class="col-md-1">Noviembre</div>
                                <div class="col-md-1">Diciembre</div>
                            </div>
                        </div>
                        <div class="button-pane">
                            <div class="size-md float-left">
                                <a href="#" title="">
                                    Ver detalles
                                </a>
                            </div>
                            <a href="#" class="btn btn-info float-right tooltip-button" data-placement="top" title="View details">
                                <i class="glyph-icon icon-angle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
			<?php
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
	public function estacionariedad3View_Territorio($zona,$year)
	{
	    $this->conn->beginTransaction();
		try
		{
		    //ESCENARIO 2
		    $query = "SELECT IF(SUM(mes1) IS NULL,0,SUM(mes1))AS one, 
    		    IF(SUM(mes2) IS NULL,0,SUM(mes2)) as two, 
    		    IF(SUM(mes3) IS NULL,0,SUM(mes3)) as three, 
    		    IF(SUM(mes4) IS NULL,0,SUM(mes4)) as four, 
    		    IF(SUM(mes5) IS NULL,0,SUM(mes5)) as five, 
    		    IF(SUM(mes6) IS NULL,0,SUM(mes6)) as six, 
    		    IF(SUM(mes7) IS NULL,0,SUM(mes7)) as seven, 
    		    IF(SUM(mes8) IS NULL,0,SUM(mes8)) as eight, 
    		    IF(SUM(mes9) IS NULL,0,SUM(mes9)) as nine, 
    		    IF(SUM(mes10) IS NULL,0,SUM(mes10)) as ten, 
    		    IF(SUM(mes11) IS NULL,0,SUM(mes11)) as eleven, 
    		    IF(SUM(mes12) IS NULL,0,SUM(mes12)) as twelve,
    		    IF(SUM(mes1)+SUM(mes2)+SUM(mes3)+SUM(mes4)+SUM(mes5)+SUM(mes6)+SUM(mes7)+SUM(mes8)+SUM(mes9)+SUM(mes10)+SUM(mes11)+SUM(mes12) IS NULL,0,
    		    SUM(mes1)+SUM(mes2)+SUM(mes3)+SUM(mes4)+SUM(mes5)+SUM(mes6)+SUM(mes7)+SUM(mes8)+SUM(mes9)+SUM(mes10)+SUM(mes11)+SUM(mes12)) as total
		    FROM `T_AnalisisCuantitativo_Escenario3` AS AC 
		    INNER JOIN T_Clientes AS C 
		    ON C.id_cliente = AC.id_cliente 
		    WHERE C.id_zona = :zona AND AC.year_acuant = :year";
		    
		    $stmt = $this->conn->prepare($query);
			$stmt->bindparam(":zona",$zona);
			$stmt->bindparam(":year",$year);
			$stmt->execute();
			
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			?>
			    <div class="col-md-12">
                    <div class="dashboard-box dashboard-box-chart bg-white content-box">
                        <div class="content-wrapper">
                            <div class="header">
                                $ <?php print(number_format($row['total'],2));?>
                                <span><b>Estacionariedad - Escenario 3</b></span>
                            </div>
                            <div class="bs-label bg-default">+0%</div>
                            <div class="center-div sparkline-big-alt"><?php print $row['one'];?>,<?php print $row['two'];?>,<?php print $row['three'];?>,<?php print $row['four'];?>,
                            <?php print $row['five'];?>,<?php print $row['six'];?>,<?php print $row['seven'];?>,<?php print $row['eight'];?>,<?php print $row['nine'];?>,
                            <?php print $row['ten'];?>,<?php print $row['eleven'];?>,<?php print $row['twelve'];?></div>
                            <div class="row list-grade">
                                <div class="col-md-1">Enero</div>
                                <div class="col-md-1">Febrero</div>
                                <div class="col-md-1">Marzo</div>
                                <div class="col-md-1">Abril</div>
                                <div class="col-md-1">Mayo</div>
                                <div class="col-md-1">Junio</div>
            					<div class="col-md-1">Julio</div>
                                <div class="col-md-1">Agosto</div>
                                <div class="col-md-1">Septiembre</div>
                                <div class="col-md-1">Octubre</div>
                                <div class="col-md-1">Noviembre</div>
                                <div class="col-md-1">Diciembre</div>
                            </div>
                        </div>
                        <div class="button-pane">
                            <div class="size-md float-left">
                                <a href="#" title="">
                                    Ver detalles
                                </a>
                            </div>
                            <a href="#" class="btn btn-info float-right tooltip-button" data-placement="top" title="View details">
                                <i class="glyph-icon icon-angle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
			<?php
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
	
	
	//ESTACIONARIEDAD - PLANEACIÓN ZONAS
	public function estacionariedad1View($zona,$year)
	{
	    $this->conn->beginTransaction();
		try
		{
		    //ESCENARIO 1
		    $query = "SELECT IF(SUM(mes1) IS NULL,0,SUM(mes1))AS one, 
    		    IF(SUM(mes2) IS NULL,0,SUM(mes2)) as two, 
    		    IF(SUM(mes3) IS NULL,0,SUM(mes3)) as three, 
    		    IF(SUM(mes4) IS NULL,0,SUM(mes4)) as four, 
    		    IF(SUM(mes5) IS NULL,0,SUM(mes5)) as five, 
    		    IF(SUM(mes6) IS NULL,0,SUM(mes6)) as six, 
    		    IF(SUM(mes7) IS NULL,0,SUM(mes7)) as seven, 
    		    IF(SUM(mes8) IS NULL,0,SUM(mes8)) as eight, 
    		    IF(SUM(mes9) IS NULL,0,SUM(mes9)) as nine, 
    		    IF(SUM(mes10) IS NULL,0,SUM(mes10)) as ten, 
    		    IF(SUM(mes11) IS NULL,0,SUM(mes11)) as eleven, 
    		    IF(SUM(mes12) IS NULL,0,SUM(mes12)) as twelve,
    		    IF(SUM(mes1)+SUM(mes2)+SUM(mes3)+SUM(mes4)+SUM(mes5)+SUM(mes6)+SUM(mes7)+SUM(mes8)+SUM(mes9)+SUM(mes10)+SUM(mes11)+SUM(mes12) IS NULL,0,
    		    SUM(mes1)+SUM(mes2)+SUM(mes3)+SUM(mes4)+SUM(mes5)+SUM(mes6)+SUM(mes7)+SUM(mes8)+SUM(mes9)+SUM(mes10)+SUM(mes11)+SUM(mes12)) as total
		    FROM `T_AnalisisCuantitativo_Escenario1` AS AC 
		    INNER JOIN T_Clientes AS C 
		    ON C.id_cliente = AC.id_cliente 
		    WHERE C.id_zona = :zona AND AC.year_acuant = :year";
		    
		    $stmt = $this->conn->prepare($query);
			$stmt->bindparam(":zona",$zona);
			$stmt->bindparam(":year",$year);
			$stmt->execute();
			
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			?>
			    <div class="col-md-12">
                    <div class="dashboard-box dashboard-box-chart bg-white content-box">
                        <div class="content-wrapper">
                            <div class="header">
                                $ <?php print(number_format($row['total'],2));?>
                                <span><b>Estacionariedad - Escenario 1</b></span>
                            </div>
                            <div class="bs-label bg-default">+0%</div>
                            <div class="center-div sparkline-big-alt"><?php print $row['one'];?>,<?php print $row['two'];?>,<?php print $row['three'];?>,<?php print $row['four'];?>,
                            <?php print $row['five'];?>,<?php print $row['six'];?>,<?php print $row['seven'];?>,<?php print $row['eight'];?>,<?php print $row['nine'];?>,
                            <?php print $row['ten'];?>,<?php print $row['eleven'];?>,<?php print $row['twelve'];?></div>
                            <div class="row list-grade">
                                <div class="col-md-1">Enero</div>
                                <div class="col-md-1">Febrero</div>
                                <div class="col-md-1">Marzo</div>
                                <div class="col-md-1">Abril</div>
                                <div class="col-md-1">Mayo</div>
                                <div class="col-md-1">Junio</div>
            					<div class="col-md-1">Julio</div>
                                <div class="col-md-1">Agosto</div>
                                <div class="col-md-1">Septiembre</div>
                                <div class="col-md-1">Octubre</div>
                                <div class="col-md-1">Noviembre</div>
                                <div class="col-md-1">Diciembre</div>
                            </div>
                        </div>
                        <div class="button-pane">
                            <div class="size-md float-left">
                                <a href="#" title="">
                                    Ver detalles
                                </a>
                            </div>
                            <a href="#" class="btn btn-info float-right tooltip-button" data-placement="top" title="View details">
                                <i class="glyph-icon icon-angle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
			<?php
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
	public function estacionariedad2View($zona,$year)
	{
	    $this->conn->beginTransaction();
		try
		{
		    //ESCENARIO 2
		    $query = "SELECT IF(SUM(mes1) IS NULL,0,SUM(mes1))AS one, 
    		    IF(SUM(mes2) IS NULL,0,SUM(mes2)) as two, 
    		    IF(SUM(mes3) IS NULL,0,SUM(mes3)) as three, 
    		    IF(SUM(mes4) IS NULL,0,SUM(mes4)) as four, 
    		    IF(SUM(mes5) IS NULL,0,SUM(mes5)) as five, 
    		    IF(SUM(mes6) IS NULL,0,SUM(mes6)) as six, 
    		    IF(SUM(mes7) IS NULL,0,SUM(mes7)) as seven, 
    		    IF(SUM(mes8) IS NULL,0,SUM(mes8)) as eight, 
    		    IF(SUM(mes9) IS NULL,0,SUM(mes9)) as nine, 
    		    IF(SUM(mes10) IS NULL,0,SUM(mes10)) as ten, 
    		    IF(SUM(mes11) IS NULL,0,SUM(mes11)) as eleven, 
    		    IF(SUM(mes12) IS NULL,0,SUM(mes12)) as twelve,
    		    IF(SUM(mes1)+SUM(mes2)+SUM(mes3)+SUM(mes4)+SUM(mes5)+SUM(mes6)+SUM(mes7)+SUM(mes8)+SUM(mes9)+SUM(mes10)+SUM(mes11)+SUM(mes12) IS NULL,0,
    		    SUM(mes1)+SUM(mes2)+SUM(mes3)+SUM(mes4)+SUM(mes5)+SUM(mes6)+SUM(mes7)+SUM(mes8)+SUM(mes9)+SUM(mes10)+SUM(mes11)+SUM(mes12)) as total
		    FROM `T_AnalisisCuantitativo_Escenario2` AS AC 
		    INNER JOIN T_Clientes AS C 
		    ON C.id_cliente = AC.id_cliente 
		    WHERE C.id_zona = :zona AND AC.year_acuant = :year";
		    
		    $stmt = $this->conn->prepare($query);
			$stmt->bindparam(":zona",$zona);
			$stmt->bindparam(":year",$year);
			$stmt->execute();
			
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			?>
			<div class="col-md-12">
				<div class="dashboard-box dashboard-box-chart bg-white content-box">
					<div class="content-wrapper">
						<div class="header">
							$ <?php print(number_format($row['total'],2));?>
							<span><b>Estacionariedad - Escenario 2</b></span>
						</div>
						<div class="bs-label bg-default">+0%</div>
						<div class="center-div sparkline-big-alt"><?php print $row['one'];?>,<?php print $row['two'];?>,<?php print $row['three'];?>,<?php print $row['four'];?>,
						<?php print $row['five'];?>,<?php print $row['six'];?>,<?php print $row['seven'];?>,<?php print $row['eight'];?>,<?php print $row['nine'];?>,
						<?php print $row['ten'];?>,<?php print $row['eleven'];?>,<?php print $row['twelve'];?></div>
						<div class="row list-grade">
							<div class="col-md-1">Enero</div>
							<div class="col-md-1">Febrero</div>
							<div class="col-md-1">Marzo</div>
							<div class="col-md-1">Abril</div>
							<div class="col-md-1">Mayo</div>
							<div class="col-md-1">Junio</div>
							<div class="col-md-1">Julio</div>
							<div class="col-md-1">Agosto</div>
							<div class="col-md-1">Septiembre</div>
							<div class="col-md-1">Octubre</div>
							<div class="col-md-1">Noviembre</div>
							<div class="col-md-1">Diciembre</div>
						</div>
					</div>
					<div class="button-pane">
						<div class="size-md float-left">
							<a href="#" title="">
								Ver detalles
							</a>
						</div>
						<a href="#" class="btn btn-info float-right tooltip-button" data-placement="top" title="Ver detalles">
							<i class="glyph-icon icon-angle-right"></i>
						</a>
					</div>
				</div>
			</div>
								
			<?php
			$this->conn->commit();
			return $row;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
			$this->conn->rollBack();
			return false;
		}
	}
	public function estacionariedad3View($zona,$year)
	{
	    $this->conn->beginTransaction();
		try
		{
		    //ESCENARIO 2
		    $query = "SELECT IF(SUM(mes1) IS NULL,0,SUM(mes1))AS one, 
    		    IF(SUM(mes2) IS NULL,0,SUM(mes2)) as two, 
    		    IF(SUM(mes3) IS NULL,0,SUM(mes3)) as three, 
    		    IF(SUM(mes4) IS NULL,0,SUM(mes4)) as four, 
    		    IF(SUM(mes5) IS NULL,0,SUM(mes5)) as five, 
    		    IF(SUM(mes6) IS NULL,0,SUM(mes6)) as six, 
    		    IF(SUM(mes7) IS NULL,0,SUM(mes7)) as seven, 
    		    IF(SUM(mes8) IS NULL,0,SUM(mes8)) as eight, 
    		    IF(SUM(mes9) IS NULL,0,SUM(mes9)) as nine, 
    		    IF(SUM(mes10) IS NULL,0,SUM(mes10)) as ten, 
    		    IF(SUM(mes11) IS NULL,0,SUM(mes11)) as eleven, 
    		    IF(SUM(mes12) IS NULL,0,SUM(mes12)) as twelve,
    		    IF(SUM(mes1)+SUM(mes2)+SUM(mes3)+SUM(mes4)+SUM(mes5)+SUM(mes6)+SUM(mes7)+SUM(mes8)+SUM(mes9)+SUM(mes10)+SUM(mes11)+SUM(mes12) IS NULL,0,
    		    SUM(mes1)+SUM(mes2)+SUM(mes3)+SUM(mes4)+SUM(mes5)+SUM(mes6)+SUM(mes7)+SUM(mes8)+SUM(mes9)+SUM(mes10)+SUM(mes11)+SUM(mes12)) as total
		    FROM `T_AnalisisCuantitativo_Escenario3` AS AC 
		    INNER JOIN T_Clientes AS C 
		    ON C.id_cliente = AC.id_cliente 
		    WHERE C.id_zona = :zona AND AC.year_acuant = :year";
		    
		    $stmt = $this->conn->prepare($query);
			$stmt->bindparam(":zona",$zona);
			$stmt->bindparam(":year",$year);
			$stmt->execute();
			
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			?>
			    <div class="col-md-12">
                    <div class="dashboard-box dashboard-box-chart bg-white content-box">
                        <div class="content-wrapper">
                            <div class="header">
                                $ <?php print(number_format($row['total'],2));?>
                                <span><b>Estacionariedad - Escenario 3</b></span>
                            </div>
                            <div class="bs-label bg-default">+0%</div>
                            <div class="center-div sparkline-big-alt"><?php print $row['one'];?>,<?php print $row['two'];?>,<?php print $row['three'];?>,<?php print $row['four'];?>,
                            <?php print $row['five'];?>,<?php print $row['six'];?>,<?php print $row['seven'];?>,<?php print $row['eight'];?>,<?php print $row['nine'];?>,
                            <?php print $row['ten'];?>,<?php print $row['eleven'];?>,<?php print $row['twelve'];?></div>
                            <div class="row list-grade">
                                <div class="col-md-1">Enero</div>
                                <div class="col-md-1">Febrero</div>
                                <div class="col-md-1">Marzo</div>
                                <div class="col-md-1">Abril</div>
                                <div class="col-md-1">Mayo</div>
                                <div class="col-md-1">Junio</div>
            					<div class="col-md-1">Julio</div>
                                <div class="col-md-1">Agosto</div>
                                <div class="col-md-1">Septiembre</div>
                                <div class="col-md-1">Octubre</div>
                                <div class="col-md-1">Noviembre</div>
                                <div class="col-md-1">Diciembre</div>
                            </div>
                        </div>
                        <div class="button-pane">
                            <div class="size-md float-left">
                                <a href="#" title="">
                                    Ver detalles
                                </a>
                            </div>
                            <a href="#" class="btn btn-info float-right tooltip-button" data-placement="top" title="View details">
                                <i class="glyph-icon icon-angle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
			<?php
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
	
	
	public function viewEstacionariedad($zona,$year)
	{
		$this->conn->beginTransaction();
		try
		{
			$query = "SELECT 
					 (SUM(DAC.trimestre1_ac)+SUM(DAC.trimestre2_ac)+SUM(DAC.trimestre3_ac)+SUM(DAC.trimestre4_ac)) AS subtotal,
					 (SUM(DAC.trimestre1_ac)+SUM(DAC.trimestre2_ac)+SUM(DAC.trimestre3_ac)+SUM(DAC.trimestre4_ac)) * 0.3 AS costototal,
					(SUM(DAC.trimestre1_ac)+SUM(DAC.trimestre2_ac)+SUM(DAC.trimestre3_ac)+SUM(DAC.trimestre4_ac)) - ((SUM(DAC.trimestre1_ac)+SUM(DAC.trimestre2_ac)+SUM(DAC.trimestre3_ac)+SUM(DAC.trimestre4_ac)) * 0.3) AS margentotal
					 FROM T_Detalle_AnalisisCuantitativo AS DAC
					 INNER JOIN T_AnalisisCuantitativo AS AC
					  ON AC.id_analisiscuantitativo = DAC.id_analisiscuantitativo
					INNER JOIN T_Clientes AS C
					  ON AC.id_cliente = C.id_cliente
					WHERE C.id_zona = :zona AND AC.year_acuant = :year";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":zona",$zona);
			$stmt->bindparam(":year",$year);
			$stmt->execute();
			$validacion1 = $stmt->rowCount();
			
			$query ="SELECT 
					  N.year_nomina,
					  (SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.comisiones)+SUM(N.apoyos)+SUM(N.intereses)) AS TotalGAO
					  FROM T_Nomina_GAO AS N
					  INNER JOIN T_Plantilla AS P
					  ON N.id_plantilla = P.id_plantilla
					  INNER JOIN T_PlantillaDatos as PD
					  ON PD.id_plantilladatos = P.id_plantilladatos
					  WHERE PD.id_zona=:zona AND year_nomina = :year
					  GROUP BY N.year_nomina";
			$stmt2 = $this->conn->prepare($query);
			$stmt2->bindparam(":zona",$zona);
			$stmt2->bindparam(":year",$year);
			$stmt2->execute();
			$validacion2 = $stmt2->rowCount();
			
			$query ="					SELECT 
					  SUM(Sub.precio_subproducto) AS precio_total,
					  SUM(Sub.costo_subproducto) AS costo_total,
					  (SUM(Sub.precio_subproducto) - SUM(Sub.costo_subproducto)) AS margen_total2
					  FROM T_Detalle_Bitacora as DB
					  INNER JOIN T_Bitacora AS B
					  ON B.id_bitacora = DB.id_bitacora
					  INNER JOIN T_Clientes AS C
					  ON C.id_cliente = B.id_cliente
					  INNER JOIN T_Subproductos AS Sub
					  ON DB.id_subproducto = Sub.id_subproducto
					  WHERE C.id_zona = :zona AND YEAR(B.ultima_modificacion) = :year AND DB.id_estatusejecucion = 1";
			$stmt3 = $this->conn->prepare($query);
			$stmt3->bindparam(":zona",$zona);
			$stmt3->bindparam(":year",$year);
			$stmt3->execute();
			$validacion3 = $stmt3->rowCount();
			
			if($validacion1>0 AND $validacion2>0 AND $validacion3>0)
			{
				$row=$stmt->fetch(PDO::FETCH_ASSOC);
				$row2=$stmt2->fetch(PDO::FETCH_ASSOC);
				$row3=$stmt3->fetch(PDO::FETCH_ASSOC);
					?>
						<tr>	
							<th>Escenario 1</th>
							<td>$<?php print(number_format(0,2)); ?></td>
							<td>$<?php print(number_format(0,2)); ?></td>
							<td>$<?php print(number_format(0,2)); ?></td>
							<td>$<?php print(number_format(0,2)); ?></td>
							<td>$<?php print(number_format(0,2)); ?></td>
							<td>$<?php print(number_format(0,2)); ?></td>
							<td>$<?php print(number_format(0,2)); ?></td>
							<td>$<?php print(number_format(0,2)); ?></td>
							<td>$<?php print(number_format(0,2)); ?></td>
							<td>$<?php print(number_format(0,2)); ?></td>
							<td>$<?php print(number_format(0,2)); ?></td>
							<td>$<?php print(number_format(0,2)); ?></td>
						</tr>
						<tr>
							<th>Escenario 2</th>
							<td>$<?php print(number_format(0,2)); ?></td>
							<td>$<?php print(number_format(0,2)); ?></td>
							<td>$<?php print(number_format(0,2)); ?></td>
							<td>$<?php print(number_format(0,2)); ?></td>
							<td>$<?php print(number_format(0,2)); ?></td>
							<td>$<?php print(number_format(0,2)); ?></td>
							<td>$<?php print(number_format(0,2)); ?></td>
							<td>$<?php print(number_format(0,2)); ?></td>
							<td>$<?php print(number_format(0,2)); ?></td>
							<td>$<?php print(number_format(0,2)); ?></td>
							<td>$<?php print(number_format(0,2)); ?></td>
							<td>$<?php print(number_format(0,2)); ?></td>
						</tr>
						<tr>	
							<th>Escenario 3</th>
							<td>$<?php print(number_format(0,2)); ?></td>
							<td>$<?php print(number_format(0,2)); ?></td>
							<td>$<?php print(number_format(0,2)); ?></td>
							<td>$<?php print(number_format(0,2)); ?></td>
							<td>$<?php print(number_format(0,2)); ?></td>
							<td>$<?php print(number_format(0,2)); ?></td>
							<td>$<?php print(number_format(0,2)); ?></td>
							<td>$<?php print(number_format(0,2)); ?></td>
							<td>$<?php print(number_format(0,2)); ?></td>
							<td>$<?php print(number_format(0,2)); ?></td>
							<td>$<?php print(number_format(0,2)); ?></td>
							<td>$<?php print(number_format(0,2)); ?></td>
						</tr>
					<?php
			
			}
			else
			{
				?>
				<tr>
					<td>No hay información disponible</td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<?php
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
	//Dashboard	
	public function viewEstacionariedad2($zona,$year)
	{
		$this->conn->beginTransaction();
		try
		{
			$query = "SELECT 
					 (SUM(DAC.trimestre1_ac)+SUM(DAC.trimestre2_ac)+SUM(DAC.trimestre3_ac)+SUM(DAC.trimestre4_ac)) AS subtotal,
					 (SUM(DAC.trimestre1_ac)+SUM(DAC.trimestre2_ac)+SUM(DAC.trimestre3_ac)+SUM(DAC.trimestre4_ac)) * 0.3 AS costototal,
					(SUM(DAC.trimestre1_ac)+SUM(DAC.trimestre2_ac)+SUM(DAC.trimestre3_ac)+SUM(DAC.trimestre4_ac)) - ((SUM(DAC.trimestre1_ac)+SUM(DAC.trimestre2_ac)+SUM(DAC.trimestre3_ac)+SUM(DAC.trimestre4_ac)) * 0.3) AS margentotal
					 FROM T_Detalle_AnalisisCuantitativo AS DAC
					 INNER JOIN T_AnalisisCuantitativo AS AC
					  ON AC.id_analisiscuantitativo = DAC.id_analisiscuantitativo
					INNER JOIN T_Clientes AS C
					  ON AC.id_cliente = C.id_cliente
					WHERE C.id_zona = :zona AND AC.year_acuant = :year";
			$stmt = $this->conn->prepare($query);
			$stmt->bindparam(":zona",$zona);
			$stmt->bindparam(":year",$year);
			$stmt->execute();
			$validacion1 = $stmt->rowCount();
			
			$query ="SELECT 
					  N.year_nomina,
					  (SUM(N.monto)+SUM(N.comida)+SUM(N.obsequios)+SUM(N.estacionamiento)+SUM(N.visitas)+SUM(N.comisiones)+SUM(N.apoyos)+SUM(N.intereses)) AS TotalGAO
					  FROM T_Nomina_GAO AS N
					  INNER JOIN T_Plantilla AS P
					  ON N.id_plantilla = P.id_plantilla
					  INNER JOIN T_PlantillaDatos as PD
					  ON PD.id_plantilladatos = P.id_plantilladatos
					  WHERE PD.id_zona=:zona AND year_nomina = :year
					  GROUP BY N.year_nomina";
			$stmt2 = $this->conn->prepare($query);
			$stmt2->bindparam(":zona",$zona);
			$stmt2->bindparam(":year",$year);
			$stmt2->execute();
			$validacion2 = $stmt2->rowCount();
			
			$query ="					SELECT 
					  SUM(Sub.precio_subproducto) AS precio_total,
					  SUM(Sub.costo_subproducto) AS costo_total,
					  (SUM(Sub.precio_subproducto) - SUM(Sub.costo_subproducto)) AS margen_total2
					  FROM T_Detalle_Bitacora as DB
					  INNER JOIN T_Bitacora AS B
					  ON B.id_bitacora = DB.id_bitacora
					  INNER JOIN T_Clientes AS C
					  ON C.id_cliente = B.id_cliente
					  INNER JOIN T_Subproductos AS Sub
					  ON DB.id_subproducto = Sub.id_subproducto
					  WHERE C.id_zona = :zona AND YEAR(B.ultima_modificacion) = :year AND DB.id_estatusejecucion = 1";
			$stmt3 = $this->conn->prepare($query);
			$stmt3->bindparam(":zona",$zona);
			$stmt3->bindparam(":year",$year);
			$stmt3->execute();
			$validacion3 = $stmt3->rowCount();
			
			if($validacion1>0 AND $validacion2>0 AND $validacion3>0)
			{
				$row=$stmt->fetch(PDO::FETCH_ASSOC);
				$row2=$stmt2->fetch(PDO::FETCH_ASSOC);
				$row3=$stmt3->fetch(PDO::FETCH_ASSOC);
					?>
						<tr>	
							<th>Subtotal</th>
							<td>$<?php print(number_format($row['subtotal'],2)); ?></td>
							<td>$<?php print(number_format($row3['precio_total'],2)); ?></td>
							<td>$<?php print(number_format($row['subtotal']-$row3['precio_total'],2)); ?></td>
						</tr>
						<tr>
							<th>% Subtotal</th>
							<td>100%</td>
							<td><?php print(round((float)($row3['precio_total']/$row['subtotal'])*100). '%');  ?></td>
							<td><?php print(round((float)(1-($row3['precio_total']/$row['subtotal']))*100) . '%');  ?></td>
						</tr>
						<tr>	
							<th>Costo</th>
							<td>$<?php print(number_format($row['costototal'],2)); ?></td>
							<td>$<?php print(number_format($row3['costo_total'],2)); ?></td>
							<td>$<?php print(number_format($row['costototal']-$row3['costo_total'],2)); ?></td>
						</tr>
						<tr>
							<th>% Costo</th>
							<td><?php print(round((float)($row['costototal']/$row['subtotal'])*100). '%');  ?></td>
							<td><?php print(round((float)($row3['costo_total']/$row3['precio_total'])*100). '%');  ?></td>
							<td><?php print(round((float)(($row['costototal']/$row['subtotal'])-($row3['costo_total']/$row3['precio_total']))*100). '%');  ?></td>
						</tr>
						<tr>	
							<th>Margen Directo</th>
							<td>$<?php print(number_format($row['margentotal'],2)); ?></td>
							<td>$<?php print(number_format($row3['margen_total2'],2)); ?></td>
							<td>$<?php print(number_format($row['margentotal']-$row3['margen_total2'],2)); ?></td>
						</tr>
						<tr>
							<th>GAO</th>
							<td>$<?php print(number_format($row2['TotalGAO'],2));  ?></td>
							<td>$<?php print(number_format($row2['TotalGAO'],2));  ?></td>
							<td>$<?php print(number_format($row2['TotalGAO'],2));  ?></td>
						</tr>
						<tr>
							<th>% GAO</th>
							<td><?php print(round((float)($row2['TotalGAO']/$row['subtotal'])*100). '%');  ?></td>
							<td><?php print(round((float)($row2['TotalGAO']/$row['precio_total'])*100). '%');  ?></td>
							<td><?php print(round((float)(($row2['TotalGAO']/$row['subtotal'])-($row2['TotalGAO']/$row['precio_total']))*100). '%');  ?></td>
						</tr>
						<tr>
							<th>Margen Ajustado</th>
							<td>$<?php print(number_format($row['margentotal']-$row2['TotalGAO'],2));  ?></td>
							<td>$<?php print(number_format($row3['margen_total2']-$row2['TotalGAO'],2));  ?></td>
							<td>$<?php print(number_format(($row['margentotal']-$row2['TotalGAO'])-($row3['margen_total2']-$row2['TotalGAO']),2));  ?></td>
						</tr>
						<tr>
							<th>% Margen Ajustado</th>
							<td><?php print(round((float)(($row['margentotal']-$row2['TotalGAO'])/$row['subtotal'])*100). '%');  ?></td>
							<td><?php print(round((float)(($row3['margen_total2']-$row2['TotalGAO'])/$row3['precio_total'])*100). '%');  ?></td>
							<td><?php print(round((float)((($row['margentotal']-$row2['TotalGAO'])/$row['subtotal'])-(($row3['margen_total2']-$row2['TotalGAO'])/$row3['precio_total']))*100). '%');  ?></td>
						</tr>
					<?php
			
			}
			else
			{
				?>
				<tr>
					<td>No hay información disponible</td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<?php
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
	
	
	//Estacionariedad
	public function dataviewEstacionariedad($zona)
	{
		$query = "SELECT 
				 SUM(DAC.trimestre1_ac),
				 SUM(DAC.trimestre2_ac),
				 SUM(DAC.trimestre3_ac),
				 SUM(DAC.trimestre4_ac),
				 (SUM(DAC.trimestre1_ac)+SUM(DAC.trimestre2_ac)+SUM(DAC.trimestre3_ac)+SUM(DAC.trimestre4_ac))
				FROM T_Detalle_AnalisisCuantitativo AS DAC
				INNER JOIN T_AnalisisCuantitativo AS AC
				  ON AC.id_analisiscuantitativo = DAC.id_analisiscuantitativo
				INNER JOIN T_Clientes AS C
				  ON AC.id_cliente = C.id_cliente
				WHERE C.id_zona = :zona AND C.id_mercado=1";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(":zona",$zona);
		$stmt->execute();
	
		if($stmt->rowCount()>0)
		{
			$row=$stmt->fetch(PDO::FETCH_ASSOC)
			
				?>
                
					<th>Ingresos M-1</th>
					<td>$<?php print(number_format($row['SUM(DAC.trimestre1_ac)'],2)); ?></td>
					<td>$<?php print(number_format($row['SUM(DAC.trimestre2_ac)'],2)); ?></td>
					<td>$<?php print(number_format($row['SUM(DAC.trimestre3_ac)'],2)); ?></td>
					<td>$<?php print(number_format($row['SUM(DAC.trimestre4_ac)'],2)); ?></td>
					<td>$<?php print(number_format($row['(SUM(DAC.trimestre1_ac)+SUM(DAC.trimestre2_ac)+SUM(DAC.trimestre3_ac)+SUM(DAC.trimestre4_ac))'],2)); ?></td>
                
                <?php
			
		}
		else
		{
			?>
            <tr>
				<td>Nothing here...</td>
            </tr>
            <?php
		}
		
	}
	public function dataviewEstacionariedad2($zona)
	{
		$query = "SELECT 
				 SUM(DAC.trimestre1_ac),
				 SUM(DAC.trimestre2_ac),
				 SUM(DAC.trimestre3_ac),
				 SUM(DAC.trimestre4_ac),
				 (SUM(DAC.trimestre1_ac)+SUM(DAC.trimestre2_ac)+SUM(DAC.trimestre3_ac)+SUM(DAC.trimestre4_ac))
				FROM T_Detalle_AnalisisCuantitativo AS DAC
				INNER JOIN T_AnalisisCuantitativo AS AC
				  ON AC.id_analisiscuantitativo = DAC.id_analisiscuantitativo
				INNER JOIN T_Clientes AS C
				  ON AC.id_cliente = C.id_cliente
				WHERE C.id_zona = :zona AND C.id_mercado=2";
					
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(":zona",$zona);
		$stmt->execute();
	
		if($stmt->rowCount()>0)
		{
			$row=$stmt->fetch(PDO::FETCH_ASSOC)
			
				?>
                
					<th>Ingresos M-2</th>
					<td><?php print($row['SUM(DAC.trimestre1_ac)']); ?></td>
					<td><?php print($row['SUM(DAC.trimestre2_ac)']); ?></td>
					<td><?php print($row['SUM(DAC.trimestre3_ac)']); ?></td>
					<td><?php print($row['SUM(DAC.trimestre4_ac)']); ?></td>
					<td><?php print($row['(SUM(DAC.trimestre1_ac)+SUM(DAC.trimestre2_ac)+SUM(DAC.trimestre3_ac)+SUM(DAC.trimestre4_ac))']); ?></td>
                
                <?php
			
		}
		else
		{
			?>
            <tr>
            <td>Nothing here...</td>
            </tr>
            <?php
		}
		
	}
	public function dataviewEstacionariedad3($zona)
	{
		$query = "SELECT 
				 SUM(DAC.trimestre1_ac),
				 SUM(DAC.trimestre2_ac),
				 SUM(DAC.trimestre3_ac),
				 SUM(DAC.trimestre4_ac),
				 (SUM(DAC.trimestre1_ac)+SUM(DAC.trimestre2_ac)+SUM(DAC.trimestre3_ac)+SUM(DAC.trimestre4_ac))
				FROM T_Detalle_AnalisisCuantitativo AS DAC
				INNER JOIN T_AnalisisCuantitativo AS AC
				  ON AC.id_analisiscuantitativo = DAC.id_analisiscuantitativo
				INNER JOIN T_Clientes AS C
				  ON AC.id_cliente = C.id_cliente
				WHERE C.id_zona = :zona AND C.id_mercado=3";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(":zona",$zona);
		$stmt->execute();
	
		if($stmt->rowCount()>0)
		{
			$row=$stmt->fetch(PDO::FETCH_ASSOC)
			
				?>
                
					<th>Ingresos M-3</th>
					<td><?php print($row['SUM(DAC.trimestre1_ac)']); ?></td>
					<td><?php print($row['SUM(DAC.trimestre2_ac)']); ?></td>
					<td><?php print($row['SUM(DAC.trimestre3_ac)']); ?></td>
					<td><?php print($row['SUM(DAC.trimestre4_ac)']); ?></td>
					<td><?php print($row['(SUM(DAC.trimestre1_ac)+SUM(DAC.trimestre2_ac)+SUM(DAC.trimestre3_ac)+SUM(DAC.trimestre4_ac))']); ?></td>
                
                <?php
			
		}
		else
		{
			?>
            <tr>
            <td>Nothing here...</td>
            </tr>
            <?php
		}
		
	}
	public function dataviewEstacionariedad4($zona)
	{
		$query = "SELECT 
				SUM(DAC.trimestre1_ac) AS sub1,
				 SUM(DAC.trimestre2_ac) AS sub2,
				 SUM(DAC.trimestre3_ac) AS sub3,
				 SUM(DAC.trimestre4_ac) AS sub4,
				 (SUM(DAC.trimestre1_ac)+SUM(DAC.trimestre2_ac)+SUM(DAC.trimestre3_ac)+SUM(DAC.trimestre4_ac)) AS subtotal,
				 SUM(DAC.trimestre1_ac)/(SUM(DAC.trimestre1_ac)+SUM(DAC.trimestre2_ac)+SUM(DAC.trimestre3_ac)+SUM(DAC.trimestre4_ac)) AS subpor1,
				 SUM(DAC.trimestre2_ac)/(SUM(DAC.trimestre1_ac)+SUM(DAC.trimestre2_ac)+SUM(DAC.trimestre3_ac)+SUM(DAC.trimestre4_ac)) AS subpor2,
				 SUM(DAC.trimestre3_ac)/(SUM(DAC.trimestre1_ac)+SUM(DAC.trimestre2_ac)+SUM(DAC.trimestre3_ac)+SUM(DAC.trimestre4_ac)) AS subpor3,
				 SUM(DAC.trimestre4_ac)/(SUM(DAC.trimestre1_ac)+SUM(DAC.trimestre2_ac)+SUM(DAC.trimestre3_ac)+SUM(DAC.trimestre4_ac)) AS subpor4,
				SUM(DAC.trimestre1_ac)*0.3 AS costo1,
				 SUM(DAC.trimestre2_ac)*0.3 AS costo2,
				 SUM(DAC.trimestre3_ac)*0.3 AS costo3,
				 SUM(DAC.trimestre4_ac)*0.3 AS costo4,
				 (SUM(DAC.trimestre1_ac)+SUM(DAC.trimestre2_ac)+SUM(DAC.trimestre3_ac)+SUM(DAC.trimestre4_ac)) * 0.3 AS costototal,
				(SUM(DAC.trimestre1_ac)-(SUM(DAC.trimestre1_ac)) * 0.3) AS margen1,
				(SUM(DAC.trimestre2_ac)-(SUM(DAC.trimestre2_ac)) * 0.3) AS margen2,
				(SUM(DAC.trimestre3_ac)-(SUM(DAC.trimestre3_ac)) * 0.3) AS margen3,
				(SUM(DAC.trimestre4_ac)-(SUM(DAC.trimestre4_ac)) * 0.3) AS margen4,
				(SUM(DAC.trimestre1_ac)+SUM(DAC.trimestre2_ac)+SUM(DAC.trimestre3_ac)+SUM(DAC.trimestre4_ac)) - ((SUM(DAC.trimestre1_ac)+SUM(DAC.trimestre2_ac)+SUM(DAC.trimestre3_ac)+SUM(DAC.trimestre4_ac)) * 0.3) AS margentotal
				 FROM T_Detalle_AnalisisCuantitativo AS DAC
				 INNER JOIN T_AnalisisCuantitativo AS AC
				  ON AC.id_analisiscuantitativo = DAC.id_analisiscuantitativo
				INNER JOIN T_Clientes AS C
				  ON AC.id_cliente = C.id_cliente
				WHERE C.id_zona = :zona";
		$stmt = $this->conn->prepare($query);
		$stmt->bindparam(":zona",$zona);
		$stmt->execute();
		if($stmt->rowCount()>0)
		{
			$row=$stmt->fetch(PDO::FETCH_ASSOC)
				?>
        			<tr>	
        				<th>Subtotal</th>
        				<td><?php print($row['sub1']); ?></td>
        				<td><?php print($row['sub2']); ?></td>
        				<td><?php print($row['sub3']); ?></td>
        				<td><?php print($row['sub4']); ?></td>
        				<td><?php print($row['subtotal']); ?></td>
        			</tr>
        			<tr>
                            	    <th>% Subtotal</th>
	                	    <td><?php print(round((float)$row['subpor1']*100). '%'); ?></td>
				    <td><?php print(round((float)$row['subpor2']*100). '%'); ?></td>
				    <td><?php print(round((float)$row['subpor3']*100). '%'); ?></td>
				    <td><?php print(round((float)$row['subpor4']*100). '%'); ?></td>
				    <td> 100% </td>
	                        </tr>
        			<tr>	
        				<th>Costo</th>
        				<td><?php print($row['costo1']); ?></td>
        				<td><?php print($row['costo2']); ?></td>
        				<td><?php print($row['costo3']); ?></td>
        				<td><?php print($row['costo4']); ?></td>
        				<td><?php print($row['costototal']); ?></td>
        			</tr>
        			<tr>
	                            <th>% Costo</th>
				    <td> 30% </td>
				    <td> 30% </td>
				    <td> 30% </td>
				    <td> 30% </td>
				    <td> 30% </td>
	                        </tr>
        			<tr>	
        				<th>Margen Directo</th>
        				<td><?php print($row['margen1']); ?></td>
        				<td><?php print($row['margen2']); ?></td>
        				<td><?php print($row['margen3']); ?></td>
        				<td><?php print($row['margen4']); ?></td>
        				<td><?php print($row['margentotal']); ?></td>
        			</tr>
		        <?php
		}
		else
		{
			?>
            <tr>
            <td>Nothing here...</td>
            </tr>
            <?php
		}
		
	}
	public function dataviewEstacionariedad5($zona)
	{	
		
				$query = "SELECT 
					          (
					            SELECT
					              (SUM(comida)+SUM(obsequios)+SUM(estacionamiento)+SUM(visitas)+SUM(comisiones)+SUM(apoyos)+SUM(intereses))
					            FROM T_GAO
					            WHERE id_zona = :zona AND year_gao = 2017
					          ) + 
					          (
					            SELECT 
					              SUM(N.monto)
					                      FROM T_Nomina AS N
					                      INNER JOIN T_Plantilla AS P
					                      ON N.id_plantilla = P.id_plantilla
					                      INNER JOIN T_PlantillaDatos AS PD
					                      ON P.id_plantilladatos = PD.id_plantilladatos
					                      WHERE PD.id_zona = :zona AND year_nomina = 2017
					          )TotalGAO";
		
						
			$stmt = $this->conn->prepare($query);
			$stmt->bindvalue(":zona",$zona);
			$stmt->execute();
					
		
		
		if($stmt->rowCount()>0)
		{
			
				$row=$stmt->fetch(PDO::FETCH_ASSOC);
			
				?>
						<th>GAO</th>
						<td><?php print($row['TotalGAO']/4);  ?></td>
						<td><?php print($row['TotalGAO']/4);  ?></td>
						<td><?php print($row['TotalGAO']/4);  ?></td>
						<td><?php print($row['TotalGAO']/4);  ?></td>
						<td><?php print($row['TotalGAO']);  ?></td>
					
                <?php
			
		}
		else
		{
			?>
            <tr>
            <td>Error</td>
            </tr>
            <?php
		}
		
	}
	public function dataviewEstacionariedad6($zona)
	{	
		$query = "SELECT 
					((SELECT 
								(
								  SELECT
									(SUM(comida)+SUM(obsequios)+SUM(estacionamiento)+SUM(visitas)+SUM(comisiones)+SUM(apoyos)+SUM(intereses))
								  FROM T_GAO
								  WHERE id_zona = :zona AND year_gao = 2015
								) + 
								(
								  SELECT 
									SUM(N.monto)
											FROM T_Nomina AS N
											INNER JOIN T_Plantilla AS P
											ON N.id_plantilla = P.id_plantilla
											INNER JOIN T_PlantillaDatos AS PD
											ON P.id_plantilladatos = PD.id_plantilladatos
											WHERE PD.id_zona = :zona AND year_nomina = 2015
								)TotalGAO)/4)/SUM(DAC.trimestre1_ac) AS sub1,
					 ((SELECT 
								(
								  SELECT
									(SUM(comida)+SUM(obsequios)+SUM(estacionamiento)+SUM(visitas)+SUM(comisiones)+SUM(apoyos)+SUM(intereses))
								  FROM T_GAO
								  WHERE id_zona = :zona AND year_gao = 2015
								) + 
								(
								  SELECT 
									SUM(N.monto)
											FROM T_Nomina AS N
											INNER JOIN T_Plantilla AS P
											ON N.id_plantilla = P.id_plantilla
											INNER JOIN T_PlantillaDatos AS PD
											ON P.id_plantilladatos = PD.id_plantilladatos
											WHERE PD.id_zona = :zona AND year_nomina = 2015
								)TotalGAO)/4) / SUM(DAC.trimestre2_ac) AS sub2,
					 ((SELECT 
								(
								  SELECT
									(SUM(comida)+SUM(obsequios)+SUM(estacionamiento)+SUM(visitas)+SUM(comisiones)+SUM(apoyos)+SUM(intereses))
								  FROM T_GAO
								  WHERE id_zona = :zona AND year_gao = 2015
								) + 
								(
								  SELECT 
									SUM(N.monto)
											FROM T_Nomina AS N
											INNER JOIN T_Plantilla AS P
											ON N.id_plantilla = P.id_plantilla
											INNER JOIN T_PlantillaDatos AS PD
											ON P.id_plantilladatos = PD.id_plantilladatos
											WHERE PD.id_zona = :zona AND year_nomina = 2015
								)TotalGAO)/4) / SUM(DAC.trimestre3_ac) AS sub3,
					 ((SELECT 
								(
								  SELECT
									(SUM(comida)+SUM(obsequios)+SUM(estacionamiento)+SUM(visitas)+SUM(comisiones)+SUM(apoyos)+SUM(intereses))
								  FROM T_GAO
								  WHERE id_zona = :zona AND year_gao = 2015
								) + 
								(
								  SELECT 
									SUM(N.monto)
											FROM T_Nomina AS N
											INNER JOIN T_Plantilla AS P
											ON N.id_plantilla = P.id_plantilla
											INNER JOIN T_PlantillaDatos AS PD
											ON P.id_plantilladatos = PD.id_plantilladatos
											WHERE PD.id_zona = :zona AND year_nomina = 2015
								)TotalGAO)/4) / SUM(DAC.trimestre4_ac) AS sub4,
					 (SELECT 
								(
								  SELECT
									(SUM(comida)+SUM(obsequios)+SUM(estacionamiento)+SUM(visitas)+SUM(comisiones)+SUM(apoyos)+SUM(intereses))
								  FROM T_GAO
								  WHERE id_zona = :zona AND year_gao = 2015
								) + 
								(
								  SELECT 
									SUM(N.monto)
											FROM T_Nomina AS N
											INNER JOIN T_Plantilla AS P
											ON N.id_plantilla = P.id_plantilla
											INNER JOIN T_PlantillaDatos AS PD
											ON P.id_plantilladatos = PD.id_plantilladatos
											WHERE PD.id_zona = :zona AND year_nomina = 2015
								)TotalGAO) / (SUM(DAC.trimestre1_ac)+SUM(DAC.trimestre2_ac)+SUM(DAC.trimestre3_ac)+SUM(DAC.trimestre4_ac))AS subtotal
					 FROM T_Detalle_AnalisisCuantitativo AS DAC
					 INNER JOIN T_AnalisisCuantitativo AS AC
					  ON AC.id_analisiscuantitativo = DAC.id_analisiscuantitativo
					INNER JOIN T_Clientes AS C
					  ON AC.id_cliente = C.id_cliente
					WHERE C.id_zona = :zona";
				
		$stmt = $this->conn->prepare($query);
		$stmt->bindvalue(":zona",$zona);
		$stmt->execute();
		
		if($stmt->rowCount()>0)
		{		
			$row=$stmt->fetch(PDO::FETCH_ASSOC);
			?>
				<tr>
					<th>% GAO</th>
					<td><?php print(round((float)$row['sub1']*100). '%'); ?></td>
					<td><?php print(round((float)$row['sub2']*100). '%'); ?></td>
					<td><?php print(round((float)$row['sub3']*100). '%'); ?></td>
					<td><?php print(round((float)$row['sub4']*100). '%'); ?></td>
					<td><?php print(round((float)$row['subtotal']*100). '%');  ?></td>
				</tr>
			<?php	
		}
		else
		{
			?>
            <tr>
				<td>Error</td>
            </tr>
            <?php
		}
	}			
	public function dataviewEstacionariedad7($zona)
	{	
		$query = "SELECT 
					(SUM(DAC.trimestre1_ac)-(SUM(DAC.trimestre1_ac)) * 0.3) -
					((SELECT 
								(
								  SELECT
									(SUM(comida)+SUM(obsequios)+SUM(estacionamiento)+SUM(visitas)+SUM(comisiones)+SUM(apoyos)+SUM(intereses))
								  FROM T_GAO
								  WHERE id_zona = :zona AND year_gao = 2015
								) + 
								(
								  SELECT 
									SUM(N.monto)
											FROM T_Nomina AS N
											INNER JOIN T_Plantilla AS P
											ON N.id_plantilla = P.id_plantilla
											INNER JOIN T_PlantillaDatos AS PD
											ON P.id_plantilladatos = PD.id_plantilladatos
											WHERE PD.id_zona = :zona AND year_nomina = 2015
								)TotalGAO)/4) AS sub1,
					 (SUM(DAC.trimestre2_ac)-(SUM(DAC.trimestre2_ac)) * 0.3) - 
					 ((SELECT 
								(
								  SELECT
									(SUM(comida)+SUM(obsequios)+SUM(estacionamiento)+SUM(visitas)+SUM(comisiones)+SUM(apoyos)+SUM(intereses))
								  FROM T_GAO
								  WHERE id_zona = :zona AND year_gao = 2015
								) + 
								(
								  SELECT 
									SUM(N.monto)
											FROM T_Nomina AS N
											INNER JOIN T_Plantilla AS P
											ON N.id_plantilla = P.id_plantilla
											INNER JOIN T_PlantillaDatos AS PD
											ON P.id_plantilladatos = PD.id_plantilladatos
											WHERE PD.id_zona = :zona AND year_nomina = 2015
								)TotalGAO)/4) AS sub2,
					 (SUM(DAC.trimestre3_ac)-(SUM(DAC.trimestre3_ac)) * 0.3) -
					 ((SELECT 
								(
								  SELECT
									(SUM(comida)+SUM(obsequios)+SUM(estacionamiento)+SUM(visitas)+SUM(comisiones)+SUM(apoyos)+SUM(intereses))
								  FROM T_GAO
								  WHERE id_zona = :zona AND year_gao = 2015
								) + 
								(
								  SELECT 
									SUM(N.monto)
											FROM T_Nomina AS N
											INNER JOIN T_Plantilla AS P
											ON N.id_plantilla = P.id_plantilla
											INNER JOIN T_PlantillaDatos AS PD
											ON P.id_plantilladatos = PD.id_plantilladatos
											WHERE PD.id_zona = :zona AND year_nomina = 2015
								)TotalGAO)/4) AS sub3,
						(SUM(DAC.trimestre4_ac)-(SUM(DAC.trimestre4_ac)) * 0.3) -
					((SELECT 
								(
								  SELECT
									(SUM(comida)+SUM(obsequios)+SUM(estacionamiento)+SUM(visitas)+SUM(comisiones)+SUM(apoyos)+SUM(intereses))
								  FROM T_GAO
								  WHERE id_zona = :zona AND year_gao = 2015
								) + 
								(
								  SELECT 
									SUM(N.monto)
											FROM T_Nomina AS N
											INNER JOIN T_Plantilla AS P
											ON N.id_plantilla = P.id_plantilla
											INNER JOIN T_PlantillaDatos AS PD
											ON P.id_plantilladatos = PD.id_plantilladatos
											WHERE PD.id_zona = :zona AND year_nomina = 2015
								)TotalGAO)/4) AS sub4,
						(SUM(DAC.trimestre1_ac)+SUM(DAC.trimestre2_ac)+SUM(DAC.trimestre3_ac)+SUM(DAC.trimestre4_ac)) - ((SUM(DAC.trimestre1_ac)+SUM(DAC.trimestre2_ac)+SUM(DAC.trimestre3_ac)+SUM(DAC.trimestre4_ac)) * 0.3) -
						(SELECT 
								(
								  SELECT
									(SUM(comida)+SUM(obsequios)+SUM(estacionamiento)+SUM(visitas)+SUM(comisiones)+SUM(apoyos)+SUM(intereses))
								  FROM T_GAO
								  WHERE id_zona = :zona AND year_gao = 2015
								) + 
								(
								  SELECT 
									SUM(N.monto)
											FROM T_Nomina AS N
											INNER JOIN T_Plantilla AS P
											ON N.id_plantilla = P.id_plantilla
											INNER JOIN T_PlantillaDatos AS PD
											ON P.id_plantilladatos = PD.id_plantilladatos
											WHERE PD.id_zona = :zona AND year_nomina = 2015
								)TotalGAO) AS subtotal
					 FROM T_Detalle_AnalisisCuantitativo AS DAC
					 INNER JOIN T_AnalisisCuantitativo AS AC
					  ON AC.id_analisiscuantitativo = DAC.id_analisiscuantitativo
					INNER JOIN T_Clientes AS C
					  ON AC.id_cliente = C.id_cliente
					WHERE C.id_zona = :zona";
		$stmt = $this->conn->prepare($query);
		$stmt->bindvalue(":zona",$zona);
		$stmt->execute();
		
		if($stmt->rowCount()>=0)
		{			
			$row=$stmt->fetch(PDO::FETCH_ASSOC);
			?>
				<tr>
					<th>Margen Ajustado</th>
					<td><?php print($row['sub1']); ?></td>
					<td><?php print($row['sub2']); ?></td>
					<td><?php print($row['sub3']); ?></td>
					<td><?php print($row['sub4']); ?></td>
					<td><?php print($row['subtotal']);  ?></td>
				</tr>
			<?php			
		}
		else
		{
			?>
            <tr>
				<td>Error</td>
            </tr>
            <?php
		}
	}
	public function dataviewEstacionariedad8($zona)
	{	
		$query = "SELECT 
						  ((SUM(DAC.trimestre1_ac)-(SUM(DAC.trimestre1_ac)) * 0.3) - ((SELECT 
									  (
										SELECT
										  (SUM(comida)+SUM(obsequios)+SUM(estacionamiento)+SUM(visitas)+SUM(comisiones)+SUM(apoyos)+SUM(intereses))
										FROM T_GAO
										WHERE id_zona = :zona AND year_gao = 2015
									  ) + 
									  (
										SELECT 
										  SUM(N.monto)
												  FROM T_Nomina AS N
												  INNER JOIN T_Plantilla AS P
												  ON N.id_plantilla = P.id_plantilla
												  INNER JOIN T_PlantillaDatos AS PD
												  ON P.id_plantilladatos = PD.id_plantilladatos
												  WHERE PD.id_zona = :zona AND year_nomina = 2015
									  )TotalGAO)/4))/SUM(DAC.trimestre1_ac) AS sub1,
						   ((SUM(DAC.trimestre2_ac)-(SUM(DAC.trimestre2_ac)) * 0.3) - 
						   ((SELECT 
									  (
										SELECT
										  (SUM(comida)+SUM(obsequios)+SUM(estacionamiento)+SUM(visitas)+SUM(comisiones)+SUM(apoyos)+SUM(intereses))
										FROM T_GAO
										WHERE id_zona = :zona AND year_gao = 2015
									  ) + 
									  (
										SELECT 
										  SUM(N.monto)
												  FROM T_Nomina AS N
												  INNER JOIN T_Plantilla AS P
												  ON N.id_plantilla = P.id_plantilla
												  INNER JOIN T_PlantillaDatos AS PD
												  ON P.id_plantilladatos = PD.id_plantilladatos
												  WHERE PD.id_zona = :zona AND year_nomina = 2015
									  )TotalGAO)/4))/SUM(DAC.trimestre2_ac) AS sub2,
						   ((SUM(DAC.trimestre3_ac)-(SUM(DAC.trimestre3_ac)) * 0.3) -
						   ((SELECT 
									  (
										SELECT
										  (SUM(comida)+SUM(obsequios)+SUM(estacionamiento)+SUM(visitas)+SUM(comisiones)+SUM(apoyos)+SUM(intereses))
										FROM T_GAO
										WHERE id_zona = :zona AND year_gao = 2015
									  ) + 
									  (
										SELECT 
										  SUM(N.monto)
												  FROM T_Nomina AS N
												  INNER JOIN T_Plantilla AS P
												  ON N.id_plantilla = P.id_plantilla
												  INNER JOIN T_PlantillaDatos AS PD
												  ON P.id_plantilladatos = PD.id_plantilladatos
												  WHERE PD.id_zona = :zona AND year_nomina = 2015
									  )TotalGAO)/4))/SUM(DAC.trimestre3_ac) AS sub3,
							  ((SUM(DAC.trimestre4_ac)-(SUM(DAC.trimestre4_ac) * 0.3) -
					  ((SELECT 
									  (
										SELECT
										  (SUM(comida)+SUM(obsequios)+SUM(estacionamiento)+SUM(visitas)+SUM(comisiones)+SUM(apoyos)+SUM(intereses))
										FROM T_GAO
										WHERE id_zona = :zona AND year_gao = 2015
									  ) + 
									  (
										SELECT 
										  SUM(N.monto)
												  FROM T_Nomina AS N
												  INNER JOIN T_Plantilla AS P
												  ON N.id_plantilla = P.id_plantilla
												  INNER JOIN T_PlantillaDatos AS PD
												  ON P.id_plantilladatos = PD.id_plantilladatos
												  WHERE PD.id_zona = :zona AND year_nomina = 2015
									  )TotalGAO)/4))) / SUM(DAC.trimestre4_ac) AS sub4,
							 ((SUM(DAC.trimestre1_ac)+SUM(DAC.trimestre2_ac)+SUM(DAC.trimestre3_ac)+SUM(DAC.trimestre4_ac)) - ((SUM(DAC.trimestre1_ac)+SUM(DAC.trimestre2_ac)+SUM(DAC.trimestre3_ac)+SUM(DAC.trimestre4_ac)) * 0.3) -
							 ((SELECT 
									  (
										SELECT
										  (SUM(comida)+SUM(obsequios)+SUM(estacionamiento)+SUM(visitas)+SUM(comisiones)+SUM(apoyos)+SUM(intereses))
										FROM T_GAO
										WHERE id_zona = :zona AND year_gao = 2015
									  ) + 
									  (
										SELECT 
										  SUM(N.monto)
												  FROM T_Nomina AS N
												  INNER JOIN T_Plantilla AS P
												  ON N.id_plantilla = P.id_plantilla
												  INNER JOIN T_PlantillaDatos AS PD
												  ON P.id_plantilladatos = PD.id_plantilladatos
												  WHERE PD.id_zona = :zona AND year_nomina = 2015
									  )TotalGAO)))/(SUM(DAC.trimestre1_ac)+SUM(DAC.trimestre2_ac)+SUM(DAC.trimestre3_ac)+SUM(DAC.trimestre4_ac)) AS subtotal
						   FROM T_Detalle_AnalisisCuantitativo AS DAC
						   INNER JOIN T_AnalisisCuantitativo AS AC
							ON AC.id_analisiscuantitativo = DAC.id_analisiscuantitativo
						  INNER JOIN T_Clientes AS C
							ON AC.id_cliente = C.id_cliente
						  WHERE C.id_zona = :zona";
						  
		$stmt = $this->conn->prepare($query);
		$stmt->bindvalue(":zona",$zona);
		$stmt->execute();		
		if($stmt->rowCount()>=0)
		{
			$row=$stmt->fetch(PDO::FETCH_ASSOC);
			?>
				<tr>
					<th>% Margen Ajustado</th>
					<td><?php print(round((float)$row['sub1']*100). '%'); ?></td>
					<td><?php print(round((float)$row['sub2']*100). '%'); ?></td>
					<td><?php print(round((float)$row['sub3']*100). '%'); ?></td>
					<td><?php print(round((float)$row['sub4']*100). '%'); ?></td>
					<td><?php print(round((float)$row['subtotal']*100). '%');  ?></td>
				</tr>
			</thead>
			<?php			
		}
		else
		{
			?>
            <tr>
				<td>Error</td>
            </tr>
            <?php
		}
	}
}
