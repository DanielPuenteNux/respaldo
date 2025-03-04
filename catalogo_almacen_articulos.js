$(document).ready(function(){

	// inicializamos dataTable
	inicializa_DataTable("tablaCatAlmacenArticulos");

	$('.intCategoriaArticuloCatArticulos').val(0);
	$('.bytSobrePedidoCatArticulos').val('');
	$('.bytArtNux').val('');
	$('.intTipoArticuloCatArticulos').val(0);
	$('.intTipoArticuloAgregar').val(0);
	$('.intCategoriaArticuloAgregar').val(0);
	$('.intUnidadMedidadArticuloAgregar').val(0);
	$('.intUMedidaCatArticulos').val(0);

	//Se inializa el select de Almacen con select2
	$(".intCategoriaArticuloCatArticulos").select2({
		theme: "bootstrap-5",
		language: "es",
		minimumInputLength: -1,
		multiple:true,
		dropdownAutoWidth : false,
		delay: 250,
		allowClear:true
	});

	//Se inializa el select de Almacen con select2
	$(".intUMedidaCatArticulos").select2({
		theme: "bootstrap-5",
		language: "es",
		minimumInputLength: -1,
		multiple:true,
		dropdownAutoWidth : false,
		delay: 250,
		allowClear:true
	});

	//Se inializa el select de Almacen con select2
	$(".bytSobrePedidoCatArticulos").select2({
		theme: "bootstrap-5",
		language: "es",
		minimumInputLength: -1,
		multiple:true,
		dropdownAutoWidth : false,
		delay: 250,
		allowClear:true 
	});

	$(".bytArtNux").select2({
		theme: "bootstrap-5",
		language: "es",
		minimumInputLength: -1,
		multiple:true,
		dropdownAutoWidth : false,
		delay: 250,
		allowClear:true 
	});

	//Se inializa el select de Almacen con select2
	$(".intTipoArticuloCatArticulos").select2({
		theme: "bootstrap-5",
		language: "es",
		minimumInputLength: -1,
		multiple:true,
		dropdownAutoWidth : false,
		delay: 250,
		allowClear:true 
	});

	//Se inializa el select de Almacen con select2
	$(".intTipoArticuloAgregar").select2({
		theme: "bootstrap-5",
		language: "es",
		minimumInputLength: -1,
		multiple:false,
		dropdownAutoWidth : false,
		delay: 250,
		dropdownParent: $("#AgregarEditarArticulo"),
	});

	//Se inializa el select de Almacen con select2
	$(".intCategoriaArticuloAgregar").select2({
		theme: "bootstrap-5",
		language: "es",
		minimumInputLength: -1,
		multiple:false,
		dropdownAutoWidth : false,
		delay: 250,
		dropdownParent: $("#AgregarEditarArticulo"),
	});

	//Se inializa el select de Almacen con select2
	$(".intUnidadMedidadArticuloAgregar").select2({
		theme: "bootstrap-5",
		language: "es",
		minimumInputLength: -1,
		multiple:false,
		dropdownAutoWidth : false,
		delay: 250,
		dropdownParent: $("#AgregarEditarArticulo"),
	});

	$("#strCodigoArticuloAgregar").keydown(function( event ) {
		if ( event.which == 13 ) {
			event.preventDefault();
			$('#intTipoArticuloAgregar').focus(); 
		}
	});

	//Se asigna la siglas al artículo seleccionado
	$('#intTipoArticuloAgregar').on('select2:close', function (e) {

		setTimeout(function() { $('#intCategoriaArticuloAgregar').focus() }, 50);
	});

	//Se asigna la siglas al artículo seleccionado
	$('#intCategoriaArticuloAgregar').on('select2:close', function (e) {

		setTimeout(function() { $('#dblReordenArticuloAgregar').focus() }, 50);
	});

	$("#dblReordenArticuloAgregar").keydown(function( event ) {
		if ( event.which == 13 ) {
			event.preventDefault();
			$('#dblMinimoArticuloAgregar').focus(); 
		}
	});

	$("#dblMinimoArticuloAgregar").keydown(function( event ) {
		if ( event.which == 13 ) {
			event.preventDefault();
			$('#dblMaximoArticuloAgregar').focus(); 
		}
	});

	$("#dblMinimoArticuloAgregar").keydown(function( event ) {
		if ( event.which == 13 ) {
			event.preventDefault();
			$('#dblMaxmimoArticuloAgregar').focus(); 
		}
	});

	$("#dblMaxmimoArticuloAgregar").keydown(function( event ) {
		if ( event.which == 13 ) {
			event.preventDefault();
			$('#strDescripcionArticuloAgregar').focus(); 
		}
	});

	$("#bytSobrePedidoArticuloAgregar").on("change", function(){

		if ($('#bytSobrePedidoArticuloAgregar').is(":checked"))
		{
			$('#dblReordenArticulo').hide(500);
			$('#dblReordenArticulo #dblReordenArticuloAgregar').val('');
			$('#dblMinimoArticulo').hide(500);
			$('#dblMinimoArticulo #dblMinimoArticuloAgregar').val('');
			$('#dblMaximoArticulo').hide(500);
			$('#dblMaximoArticulo #dblMaximoArticuloAgregar').val('');
		}
		else
		{
			$('#dblReordenArticulo').show(500);
			$('#dblMinimoArticulo').show(500);
			$('#dblMaximoArticulo').show(500);
		}

	});

	$('#strCodigoArticuloAgregar').keyup(function() {
		var raw_text =  $(this).val();
		var return_text = raw_text.replace(/[^a-zA-Z0-9]/g,'');
		$(this).val(return_text.toUpperCase());
	});
});

function doAction_CatArticulos(optn){

	// obtenemos row seleccionado
	let row = dataCatAlmacenArticulos.row('.selected').data();

	// validamos selección
	if (!row && optn != 1){
		msgBox_Warning("Aviso", "Debes seleccionar un registro.");	
		return;
	}

	if(optn != 3){accionAlmacenCatArticulos(optn,row);}
	else{cancelaArticuloCat(optn,row);}
}

function accionAlmacenCatArticulos(optn,row = {})
{

	//Limpiamos campos
	$('#AgregarEditarArticulo .intTipoArticuloAgregar').val('').trigger("change");
	$('#AgregarEditarArticulo .intCategoriaArticuloAgregar').val('').trigger("change");
	$('#AgregarEditarArticulo .intUnidadMedidadArticuloAgregar').val('').trigger("change");
	$('#AgregarEditarArticulo #bytSobrePedidoArticuloAgregar').prop('checked', false);
	$('#AgregarEditarArticulo #bytNUXArticuloAgregar').prop('checked', false);
	$('#AgregarEditarArticulo .strDescripcionArticuloAgregar').val('');
	$('#AgregarEditarArticulo .strComentariosArticuloAgregar').val('');
	$('#AgregarEditarArticulo .strCodigoArticuloAgregar').val('');
	$('#AgregarEditarArticulo .titleModalHeader').text("Agregar");

	clearFilters("AgregarEditarArticulo");

	if(optn == 2)
	{	
		$('#AgregarEditarArticulo .titleModalHeader').text("Editar");
		$('#AgregarEditarArticulo .intTipoArticuloAgregar').val(row.intTipoArticulo).trigger("change");
		$('#AgregarEditarArticulo .intUnidadMedidadArticuloAgregar').val(row.intUMedidaArticulo).trigger("change");
		$('#AgregarEditarArticulo .intCategoriaArticuloAgregar').val(row.intCategoriaArticulo).trigger("change");
		$('#AgregarEditarArticulo .strCodigoArticuloAgregar').val(row.strCodigo);
		$('#AgregarEditarArticulo .dblReordenArticuloAgregar').val(row.dblReorden);
		$('#AgregarEditarArticulo .dblMinimoArticuloAgregar').val(row.dblMinimo);
		$('#AgregarEditarArticulo .dblMaximoArticuloAgregar').val(row.dblMaximo);
		$('#AgregarEditarArticulo .dblAncho').val(row.dblAncho);
		$('#AgregarEditarArticulo .dblLargo').val(row.dblLargo);
		$('#AgregarEditarArticulo .dblAlto').val(row.dblAlto);

		if(row.bytSobrePedido == 1){$('#AgregarEditarArticulo #bytSobrePedidoArticuloAgregar').prop('checked', true);}
		if(row.bytNUX == 1){$('#AgregarEditarArticulo #bytNUXArticuloAgregar').prop('checked', true);}
		$('#AgregarEditarArticulo #bytNUXArticuloAgregar').val(row.bytNUX);
		$('#AgregarEditarArticulo #bytSobrePedidoArticuloAgregar').val(row.bytSobrePedido);
		$('#AgregarEditarArticulo .strDescripcionArticuloAgregar').val(row.strDescripcion);
		$('#AgregarEditarArticulo .strComentariosArticuloAgregar').val(row.strComentarios);
		
		if(row.bytSobrePedido == 1){
			$('#dblReordenArticulo').hide(500);
			$('#dblReordenArticulo #dblReordenArticuloAgregar').val('');
			$('#dblMinimoArticulo').hide(500);
			$('#dblMinimoArticulo #dblMinimoArticuloAgregar').val('');
			$('#dblMaximoArticulo').hide(500);
			$('#dblMaximoArticulo #dblMaximoArticuloAgregar').val('');
		}
		else{
			$('#dblReordenArticulo').show(500);
			$('#dblMinimoArticulo').show(500);
			$('#dblMaximoArticulo').show(500);
		}
	}
	
	$('#AgregarEditarArticulo').modal('show');
	$("#AgregarEditarArticulo .btn-primary").off("click").on("click", function(){

		// obtenemos datos y validamos
		var results = getParametersArray("AgregarEditarArticulo", true);
		var bolContinue = results[0];
		var params = results[1];

		if(!bolContinue){msgBox_Warning("Aviso", "Favor de llenar los campos obligatorios."); return;}

		if(!row.intArticulo){row.intArticulo = ''}

		params.push({name: 'accionDB', value: optn});
		params.push({name: 'intArticulo', value: row.intArticulo});

		// obtenemos objeto
		var json = paramsArray_ToJson(params);
		
		fetch_Post("../controllers/catalogos/catalogos_almacen_controller",{
			action: 'accionArticuloCat',
			json: JSON.stringify(json)
		}).then(function (objResponse){
			if(objResponse != false){
				hideLoading().sleep(200).then(() =>{ 
					
					$('#AgregarEditarArticulo').modal('toggle');

					msgBox_Success('', 'Transacción realizada con éxito.');
	
					dataTableCatArticulos();

					clearFilters("AgregarEditarArticulo");

				 });
			 }
		});

	});
	$("#AgregarEditarArticulo .btn-secondary").on("click", function(){
		$("#AgregarEditarArticulo").modal('hide');
	});
}

async function cancelaArticuloCat(optn,row)
{
	if (! await confirmBox("Aviso", "¿Está seguro que desea eliminar el Artículo " + row.strCodigo + " - " + row.strDescripcion + "?")) return;

	// loading
	showLoading();

	var params = [];
	params.push({name: 'accionDB', value: optn});
	params.push({name: 'intArticulo', value: row.intArticulo});

	// obtenemos objeto
	var json = paramsArray_ToJson(params);

	fetch_Post("../controllers/catalogos/catalogos_almacen_controller",{
		action: 'accionArticuloCat',
		json: JSON.stringify(json)
	}).then(function (objResponse){
		if(objResponse != false){
			hideLoading().sleep(200).then(() =>{ 

				msgBox_Success('', 'Transacción realizada con éxito.');

				dataTableCatArticulos();

				});
			}
			else hideLoading();
	});
}

function dataTableCatArticulos()
{
	//Obtenemos los parámetros dados
	var results = getParametersArray("filtrosCatalogoArticulos_Body", false);
	var filters = paramsArray_ToJson(results[1]);
	var params = new Array();

	params["filters"] = filters;
	params[strTokenID] = strTokenValue;
	params["action"] = "getCatArticulosJSON";

	// loading
	showLoading();

	//Inicializamos datatable del respectivo componente
	dataCatAlmacenArticulos = $('#tablaCatAlmacenArticulos').DataTable({
		ajax: {
			url: '../controllers/catalogos/catalogos_almacen_controller',
			type: 'POST', 
			data: params,
			deferLoading:600,
		},
		rowId: 'intArticulo',
		select: { 
			items: 'row', 
			style:'single'
			//Este parametro es un ejemplo de como podemos excluir columnas de la selección de rows
			//selector: 'tr>td:nth-child(2), tr>td:nth-child(3), tr>td:nth-child(4), tr>td:nth-child(5), tr>td:nth-child(6), tr>td:nth-child(7), tr>td:nth-child(8), tr>td:nth-child(9)'
		},
		buttons :[
			{
				text: '<i data-feather="plus" style="width:16px; aspect-ratio:1;"></i>Agregar',
				className: 'btn-action-datatable',
				action: function ( e, dt, node, config ) { doAction_CatArticulos(1); }
			},
			{
				text: '<i data-feather="edit" style="width:16px; aspect-ratio:1;"></i>Editar',
				className: 'btn-action-datatable',
				action: function ( e, dt, node, config ) { doAction_CatArticulos(2); }
			},
			{
				text: '<i data-feather="x-circle" style="width:16px; aspect-ratio:1;"></i>Eliminar',
				className: 'btn-action-datatable',
				action: function ( e, dt, node, config ) { doAction_CatArticulos(3); }
			},
			{
				extend: 'collection',
				text: 'Exportar',
				buttons: [
					{
						extend:    'copy',
						text:      '<i class="fa fa-files-o"></i> Copy',
						titleAttr: 'Copy'
					},
					{
						extend:'excelHtml5',
						text:'<i class="fa fa-files-o"></i> Excel',
						exportOptions: {
							columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
						}
					},
					{
						extend: 'pdfHtml5',
						text: '<i class="fa fa-file-pdf-o"></i> PDF',
						exportOptions: {
							columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
						},
						orientation:'landscape',
						pageSize:'LEGAL'
					},
				]
			},
			'pageLength'
		],
		columns: [
			{ data: "strIconArticulo", width: 300},
			{ data: "strDescripcion"},
			{ data: "strSobrePedido_HTML"},
			{ data: "dblMinimo"},
			{ data: "dblMaximo"},
			{ data: "dblReorden"},
			{ data: "strNombreCategoria"},
			{ data: "strNombreMedidaArticulo"},
			{ data: "strComentarios"},
			{ data: "strUsuarioAlta"},
			{ data: "datFechaAlta"},
			{ data: "strUsuarioMod"},
			{ data: "datFechaMod"}
		],
		bDestroy: true,
		searching:false,
		bFilter: false,
		scrollX: true,
		"preDrawCallback": function( settings ) {
      		$('#tablaCatAlmacenArticulos tbody').fadeOut(200);			  
		},
		"drawCallback": function() {			
			$('#tablaCatAlmacenArticulos tbody').fadeIn(200);			
			hideLoading();
			scrollTo_Mobile("scrollCatAlmacenArticulos");
			feather.replace();
		}
    });


	//Revisamos el botón a validar 
	$('#tablaCatAlmacenArticulos .validate-button').on( 'click', function () {

		//false - nothing selected. true - 1 or more are selected.
		var anyRowSelected = dataCatAlmacenArticulos.rows('.selected').indexes().length === 0 ? false : true;
		
		//Si no hay nada seleccionado mostramos mensaje de error
		if (!anyRowSelected){
			msgBox_Warning("Falta información.", "Por favor seleccione al menos un registro.");;
		}
	});

	$('#tablaCatAlmacenArticulos').on('order.dt', function () {
		showLoading();
	});
}
