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
    /* Data table fixed columns 
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
	*/
    $(document).ready(function() {
        $('.dataTables_filter input').attr("placeholder", "Buscar...");
    });
</script>
<div id="page-title">
	<h2>Ranking - Méritos</h2>
</div>
<div class="panel">
	<div class="panel-body">
		<h3 class="title-hero">
			Tabla de Méritos
		</h3>
		<div style="float:right" class="col-md-3">
			<select id="vista" class="form-control" onchange="getTipoVista(this);">
				<option value="mensual">Vista mensual</option>
				<option value="acumulada">Vista acumulada</option>
				<option value="anualizada">Vista anualizada</option>
			</select>
		</div>
		<div style="float:right" class="col-md-3">
			<select id="tipoAgente" class="form-control" onchange="getTipoAgente(this);">
				<option value="todos">Todos los Agentes</option>
				<option value="invos">Agentes Invos</option>
				<option value="evos">Agentes Evos</option>
			</select>
		</div>
		<div class="mensual-todos example-box-wrapper ">
			<table name="reqs" id="datatable-tabletools" class="table table-bordered" width="100%">
				<thead>
					<tr>
						<th>Número</th>
						<th>Agente</th>
						<th>Desarrollo</th>
						<th>Desempeño</th>
						<th>Total</th>
						<th>Conversión</th>
					</tr>	
				</thead>
				<tbody>
					<?php $ara->meritosMensual(date("Y"),date('n'),0); ?>
				</tbody>
			</table>
		</div>
		<div class="mensual-invos example-box-wrapper ">
			<table name="reqs" id="datatable-tabletools" class="table table-bordered" width="100%">
				<thead>
					<tr>
						<th>Número</th>
						<th>Agente</th>
						<th>Desarrollo</th>
						<th>Desempeño</th>
						<th>Total</th>
						<th>Conversión</th>
					</tr>	
				</thead>
				<tbody>
					<tr bgcolor="#FFC700" style="color:white">
						<td>1</td>
						<td>Agente 1</td>
						<td>24</td>
						<td>53</td>
						<td>77</td>
						<td>7.7</td>
					</tr>
					<tr bgcolor="#e2e2e0" style="color:white">
						<td>2</td>
						<td>Agente 2</td>
						<td>30</td>
						<td>43</td>
						<td>73</td>
						<td>7.3</td>
					</tr>
					<tr bgcolor="#e06133" style="color:white">
						<td>3</td>
						<td>Agente 3</td>
						<td>28</td>
						<td>44</td>
						<td>72</td>
						<td>7.2</td>
					</tr>
					<tr>
						<td>4</td>
						<td>Agente 4</td>
						<td>28</td>
						<td>42</td>
						<td>70</td>
						<td>7.0</td>
					</tr>
					<tr>
						<td>5</td>
						<td>Agente 5</td>
						<td>35</td>
						<td>34</td>
						<td>69</td>
						<td>6.9</td>
					</tr>
					<tr>
						<td>6</td>
						<td>Agente 6</td>
						<td>35</td>
						<td>34</td>
						<td>69</td>
						<td>6.9</td>
					</tr>
					<tr>
						<td>7</td>
						<td>Agente 7</td>
						<td>33</td>
						<td>36</td>
						<td>69</td>
						<td>6.9</td>
					</tr>
					<tr>
						<td>8</td>
						<td>Agente 8</td>
						<td>31</td>
						<td>37</td>
						<td>68</td>
						<td>6.8</td>
					</tr>
					<tr>
						<td>9</td>
						<td>Agente 9</td>
						<td>31</td>
						<td>35</td>
						<td>66</td>
						<td>6.6</td>
					</tr>
					<tr>
						<td>10</td>
						<td>Agente 10</td>
						<td>31</td>
						<td>35</td>
						<td>66</td>
						<td>6.6</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="mensual-evos example-box-wrapper ">
			<table name="reqs" id="datatable-tabletools" class="table table-bordered" width="100%">
				<thead>
					<tr>
						<th>Número</th>
						<th>Agente</th>
						<th>Desarrollo</th>
						<th>Desempeño</th>
						<th>Total</th>
						<th>Conversión</th>
					</tr>	
				</thead>
				<tbody>
					<tr bgcolor="#FFC700" style="color:white">
						<td>1</td>
						<td>Agente 1</td>
						<td>24</td>
						<td>53</td>
						<td>77</td>
						<td>7.7</td>
					</tr>
					<tr bgcolor="#e2e2e0" style="color:white">
						<td>2</td>
						<td>Agente 2</td>
						<td>30</td>
						<td>43</td>
						<td>73</td>
						<td>7.3</td>
					</tr>
					<tr bgcolor="#e06133" style="color:white">
						<td>3</td>
						<td>Agente 3</td>
						<td>28</td>
						<td>44</td>
						<td>72</td>
						<td>7.2</td>
					</tr>
					<tr>
						<td>4</td>
						<td>Agente 4</td>
						<td>28</td>
						<td>42</td>
						<td>70</td>
						<td>7.0</td>
					</tr>
					<tr>
						<td>5</td>
						<td>Agente 5</td>
						<td>35</td>
						<td>34</td>
						<td>69</td>
						<td>6.9</td>
					</tr>
					<tr>
						<td>6</td>
						<td>Agente 6</td>
						<td>35</td>
						<td>34</td>
						<td>69</td>
						<td>6.9</td>
					</tr>
					<tr>
						<td>7</td>
						<td>Agente 7</td>
						<td>33</td>
						<td>36</td>
						<td>69</td>
						<td>6.9</td>
					</tr>
					<tr>
						<td>8</td>
						<td>Agente 8</td>
						<td>31</td>
						<td>37</td>
						<td>68</td>
						<td>6.8</td>
					</tr>
					<tr>
						<td>9</td>
						<td>Agente 9</td>
						<td>31</td>
						<td>35</td>
						<td>66</td>
						<td>6.6</td>
					</tr>
					<tr>
						<td>10</td>
						<td>Agente 10</td>
						<td>31</td>
						<td>35</td>
						<td>66</td>
						<td>6.6</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="acumulada-todos example-box-wrapper ">
			<table name="reqs" id="datatable-tabletools" class="table table-bordered" width="100%">
				<thead>
					<tr>
						<th>Número</th>
						<th>Agente</th>
						<th>Desarrollo</th>
						<th>Desempeño</th>
						<th>Total</th>
						<th>Conversión</th>
					</tr>	
				</thead>
				<tbody>
					<tr bgcolor="#FFC700" style="color:white">
						<td>1</td>
						<td>Agente 1</td>
						<td>24</td>
						<td>53</td>
						<td>77</td>
						<td>7.7</td>
					</tr>
					<tr bgcolor="#e2e2e0" style="color:white">
						<td>2</td>
						<td>Agente 2</td>
						<td>30</td>
						<td>43</td>
						<td>73</td>
						<td>7.3</td>
					</tr>
					<tr bgcolor="#e06133" style="color:white">
						<td>3</td>
						<td>Agente 3</td>
						<td>28</td>
						<td>44</td>
						<td>72</td>
						<td>7.2</td>
					</tr>
					<tr>
						<td>4</td>
						<td>Agente 4</td>
						<td>28</td>
						<td>42</td>
						<td>70</td>
						<td>7.0</td>
					</tr>
					<tr>
						<td>5</td>
						<td>Agente 5</td>
						<td>35</td>
						<td>34</td>
						<td>69</td>
						<td>6.9</td>
					</tr>
					<tr>
						<td>6</td>
						<td>Agente 6</td>
						<td>35</td>
						<td>34</td>
						<td>69</td>
						<td>6.9</td>
					</tr>
					<tr>
						<td>7</td>
						<td>Agente 7</td>
						<td>33</td>
						<td>36</td>
						<td>69</td>
						<td>6.9</td>
					</tr>
					<tr>
						<td>8</td>
						<td>Agente 8</td>
						<td>31</td>
						<td>37</td>
						<td>68</td>
						<td>6.8</td>
					</tr>
					<tr>
						<td>9</td>
						<td>Agente 9</td>
						<td>31</td>
						<td>35</td>
						<td>66</td>
						<td>6.6</td>
					</tr>
					<tr>
						<td>10</td>
						<td>Agente 10</td>
						<td>31</td>
						<td>35</td>
						<td>66</td>
						<td>6.6</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="acumulada-invos example-box-wrapper ">
			<table name="reqs" id="datatable-tabletools" class="table table-bordered" width="100%">
				<thead>
					<tr>
						<th>Número</th>
						<th>Agente</th>
						<th>Desarrollo</th>
						<th>Desempeño</th>
						<th>Total</th>
						<th>Conversión</th>
					</tr>	
				</thead>
				<tbody>
					<tr bgcolor="#FFC700" style="color:white">
						<td>1</td>
						<td>Agente 1</td>
						<td>24</td>
						<td>53</td>
						<td>77</td>
						<td>7.7</td>
					</tr>
					<tr bgcolor="#e2e2e0" style="color:white">
						<td>2</td>
						<td>Agente 2</td>
						<td>30</td>
						<td>43</td>
						<td>73</td>
						<td>7.3</td>
					</tr>
					<tr bgcolor="#e06133" style="color:white">
						<td>3</td>
						<td>Agente 3</td>
						<td>28</td>
						<td>44</td>
						<td>72</td>
						<td>7.2</td>
					</tr>
					<tr>
						<td>4</td>
						<td>Agente 4</td>
						<td>28</td>
						<td>42</td>
						<td>70</td>
						<td>7.0</td>
					</tr>
					<tr>
						<td>5</td>
						<td>Agente 5</td>
						<td>35</td>
						<td>34</td>
						<td>69</td>
						<td>6.9</td>
					</tr>
					<tr>
						<td>6</td>
						<td>Agente 6</td>
						<td>35</td>
						<td>34</td>
						<td>69</td>
						<td>6.9</td>
					</tr>
					<tr>
						<td>7</td>
						<td>Agente 7</td>
						<td>33</td>
						<td>36</td>
						<td>69</td>
						<td>6.9</td>
					</tr>
					<tr>
						<td>8</td>
						<td>Agente 8</td>
						<td>31</td>
						<td>37</td>
						<td>68</td>
						<td>6.8</td>
					</tr>
					<tr>
						<td>9</td>
						<td>Agente 9</td>
						<td>31</td>
						<td>35</td>
						<td>66</td>
						<td>6.6</td>
					</tr>
					<tr>
						<td>10</td>
						<td>Agente 10</td>
						<td>31</td>
						<td>35</td>
						<td>66</td>
						<td>6.6</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="acumulada-evos example-box-wrapper ">
			<table name="reqs" id="datatable-tabletools" class="table table-bordered" width="100%">
				<thead>
					<tr>
						<th>Número</th>
						<th>Agente</th>
						<th>Desarrollo</th>
						<th>Desempeño</th>
						<th>Total</th>
						<th>Conversión</th>
					</tr>	
				</thead>
				<tbody>
					<tr bgcolor="#FFC700" style="color:white">
						<td>1</td>
						<td>Agente 1</td>
						<td>24</td>
						<td>53</td>
						<td>77</td>
						<td>7.7</td>
					</tr>
					<tr bgcolor="#e2e2e0" style="color:white">
						<td>2</td>
						<td>Agente 2</td>
						<td>30</td>
						<td>43</td>
						<td>73</td>
						<td>7.3</td>
					</tr>
					<tr bgcolor="#e06133" style="color:white">
						<td>3</td>
						<td>Agente 3</td>
						<td>28</td>
						<td>44</td>
						<td>72</td>
						<td>7.2</td>
					</tr>
					<tr>
						<td>4</td>
						<td>Agente 4</td>
						<td>28</td>
						<td>42</td>
						<td>70</td>
						<td>7.0</td>
					</tr>
					<tr>
						<td>5</td>
						<td>Agente 5</td>
						<td>35</td>
						<td>34</td>
						<td>69</td>
						<td>6.9</td>
					</tr>
					<tr>
						<td>6</td>
						<td>Agente 6</td>
						<td>35</td>
						<td>34</td>
						<td>69</td>
						<td>6.9</td>
					</tr>
					<tr>
						<td>7</td>
						<td>Agente 7</td>
						<td>33</td>
						<td>36</td>
						<td>69</td>
						<td>6.9</td>
					</tr>
					<tr>
						<td>8</td>
						<td>Agente 8</td>
						<td>31</td>
						<td>37</td>
						<td>68</td>
						<td>6.8</td>
					</tr>
					<tr>
						<td>9</td>
						<td>Agente 9</td>
						<td>31</td>
						<td>35</td>
						<td>66</td>
						<td>6.6</td>
					</tr>
					<tr>
						<td>10</td>
						<td>Agente 10</td>
						<td>31</td>
						<td>35</td>
						<td>66</td>
						<td>6.6</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="anualizada-todos example-box-wrapper ">
			<table name="reqs" id="datatable-tabletools" class="table table-bordered" width="100%">
				<thead>
					<tr>
						<th>Número</th>
						<th>Agente</th>
						<th>Desarrollo</th>
						<th>Desempeño</th>
						<th>Total</th>
						<th>Conversión</th>
					</tr>	
				</thead>
				<tbody>
					<tr bgcolor="#FFC700" style="color:white">
						<td>1</td>
						<td>Agente 1</td>
						<td>24</td>
						<td>53</td>
						<td>77</td>
						<td>7.7</td>
					</tr>
					<tr bgcolor="#e2e2e0" style="color:white">
						<td>2</td>
						<td>Agente 2</td>
						<td>30</td>
						<td>43</td>
						<td>73</td>
						<td>7.3</td>
					</tr>
					<tr bgcolor="#e06133" style="color:white">
						<td>3</td>
						<td>Agente 3</td>
						<td>28</td>
						<td>44</td>
						<td>72</td>
						<td>7.2</td>
					</tr>
					<tr>
						<td>4</td>
						<td>Agente 4</td>
						<td>28</td>
						<td>42</td>
						<td>70</td>
						<td>7.0</td>
					</tr>
					<tr>
						<td>5</td>
						<td>Agente 5</td>
						<td>35</td>
						<td>34</td>
						<td>69</td>
						<td>6.9</td>
					</tr>
					<tr>
						<td>6</td>
						<td>Agente 6</td>
						<td>35</td>
						<td>34</td>
						<td>69</td>
						<td>6.9</td>
					</tr>
					<tr>
						<td>7</td>
						<td>Agente 7</td>
						<td>33</td>
						<td>36</td>
						<td>69</td>
						<td>6.9</td>
					</tr>
					<tr>
						<td>8</td>
						<td>Agente 8</td>
						<td>31</td>
						<td>37</td>
						<td>68</td>
						<td>6.8</td>
					</tr>
					<tr>
						<td>9</td>
						<td>Agente 9</td>
						<td>31</td>
						<td>35</td>
						<td>66</td>
						<td>6.6</td>
					</tr>
					<tr>
						<td>10</td>
						<td>Agente 10</td>
						<td>31</td>
						<td>35</td>
						<td>66</td>
						<td>6.6</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="anualizada-invos example-box-wrapper ">
			<table name="reqs" id="datatable-tabletools" class="table table-bordered" width="100%">
				<thead>
					<tr>
						<th>Número</th>
						<th>Agente</th>
						<th>Desarrollo</th>
						<th>Desempeño</th>
						<th>Total</th>
						<th>Conversión</th>
					</tr>	
				</thead>
				<tbody>
					<tr bgcolor="#FFC700" style="color:white">
						<td>1</td>
						<td>Agente 1</td>
						<td>24</td>
						<td>53</td>
						<td>77</td>
						<td>7.7</td>
					</tr>
					<tr bgcolor="#e2e2e0" style="color:white">
						<td>2</td>
						<td>Agente 2</td>
						<td>30</td>
						<td>43</td>
						<td>73</td>
						<td>7.3</td>
					</tr>
					<tr bgcolor="#e06133" style="color:white">
						<td>3</td>
						<td>Agente 3</td>
						<td>28</td>
						<td>44</td>
						<td>72</td>
						<td>7.2</td>
					</tr>
					<tr>
						<td>4</td>
						<td>Agente 4</td>
						<td>28</td>
						<td>42</td>
						<td>70</td>
						<td>7.0</td>
					</tr>
					<tr>
						<td>5</td>
						<td>Agente 5</td>
						<td>35</td>
						<td>34</td>
						<td>69</td>
						<td>6.9</td>
					</tr>
					<tr>
						<td>6</td>
						<td>Agente 6</td>
						<td>35</td>
						<td>34</td>
						<td>69</td>
						<td>6.9</td>
					</tr>
					<tr>
						<td>7</td>
						<td>Agente 7</td>
						<td>33</td>
						<td>36</td>
						<td>69</td>
						<td>6.9</td>
					</tr>
					<tr>
						<td>8</td>
						<td>Agente 8</td>
						<td>31</td>
						<td>37</td>
						<td>68</td>
						<td>6.8</td>
					</tr>
					<tr>
						<td>9</td>
						<td>Agente 9</td>
						<td>31</td>
						<td>35</td>
						<td>66</td>
						<td>6.6</td>
					</tr>
					<tr>
						<td>10</td>
						<td>Agente 10</td>
						<td>31</td>
						<td>35</td>
						<td>66</td>
						<td>6.6</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="anualizada-evos example-box-wrapper ">
			<table name="reqs" id="datatable-tabletools" class="table table-bordered" width="100%">
				<thead>
					<tr>
						<th>Número</th>
						<th>Agente</th>
						<th>Desarrollo</th>
						<th>Desempeño</th>
						<th>Total</th>
						<th>Conversión</th>
					</tr>	
				</thead>
				<tbody>
					<tr bgcolor="#FFC700" style="color:white">
						<td>1</td>
						<td>Agente 1</td>
						<td>24</td>
						<td>53</td>
						<td>77</td>
						<td>7.7</td>
					</tr>
					<tr bgcolor="#e2e2e0" style="color:white">
						<td>2</td>
						<td>Agente 2</td>
						<td>30</td>
						<td>43</td>
						<td>73</td>
						<td>7.3</td>
					</tr>
					<tr bgcolor="#e06133" style="color:white">
						<td>3</td>
						<td>Agente 3</td>
						<td>28</td>
						<td>44</td>
						<td>72</td>
						<td>7.2</td>
					</tr>
					<tr>
						<td>4</td>
						<td>Agente 4</td>
						<td>28</td>
						<td>42</td>
						<td>70</td>
						<td>7.0</td>
					</tr>
					<tr>
						<td>5</td>
						<td>Agente 5</td>
						<td>35</td>
						<td>34</td>
						<td>69</td>
						<td>6.9</td>
					</tr>
					<tr>
						<td>6</td>
						<td>Agente 6</td>
						<td>35</td>
						<td>34</td>
						<td>69</td>
						<td>6.9</td>
					</tr>
					<tr>
						<td>7</td>
						<td>Agente 7</td>
						<td>33</td>
						<td>36</td>
						<td>69</td>
						<td>6.9</td>
					</tr>
					<tr>
						<td>8</td>
						<td>Agente 8</td>
						<td>31</td>
						<td>37</td>
						<td>68</td>
						<td>6.8</td>
					</tr>
					<tr>
						<td>9</td>
						<td>Agente 9</td>
						<td>31</td>
						<td>35</td>
						<td>66</td>
						<td>6.6</td>
					</tr>
					<tr>
						<td>10</td>
						<td>Agente 10</td>
						<td>31</td>
						<td>35</td>
						<td>66</td>
						<td>6.6</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script type="text/javascript">
	function getTipoVista(e)
	{
		var tipoVista = $(e).val();
		var tipoAgente = $('#tipoAgente').val();
		
		$('.mensual-todos').hide();
		$('.mensual-invos').hide();
		$('.mensual-evos').hide();
		$('.acumulada-todos').hide();
		$('.acumulada-invos').hide();
		$('.acumulada-evos').hide();
		$('.anualizada-todos').hide();
		$('.anualizada-invos').hide();
		$('.anualizada-evos').hide();
		
		$('.'+tipoVista+'-'+tipoAgente).show();
	}
</script>
<script type="text/javascript">
	$(function()
	{
		$('.mensual-todos').show();
		$('.mensual-invos').hide();
		$('.mensual-evos').hide();
		$('.acumulada-todos').hide();
		$('.acumulada-invos').hide();
		$('.acumulada-evos').hide();
		$('.anualizada-todos').hide();
		$('.anualizada-invos').hide();
		$('.anualizada-evos').hide();
	});
</script>
<?php include_once 'footer.php';?>
