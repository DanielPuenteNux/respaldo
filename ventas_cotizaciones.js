$(document).ready(function()
{
	// Clientes
	$("#vcClienteC").select2({
		theme: "bootstrap-5",
		language: "es",
		minimumInputLength: 3,
		multiple:true,		
		delay: 250,
		escapeMarkup: function (text) { return text; },
		ajax: {
			url: "../controllers/controllerGeneral",
			dataType: 'json',
			data: function (params) {
				return {
					action: "getClientes",					
					[strTokenID]: strTokenValue,
					strTerm: $.trim(params.term) === '' ? '' : params.term
				};
			}
		}
	});

	// Orden
	$("#vcOrdenProd").select2({
		theme: "bootstrap-5",
		language: "es",
		minimumInputLength: 3,
		multiple: true,		
		delay: 250,
		escapeMarkup: function (text) { return text; },
		ajax: {
			url: "../controllers/controllerGeneral",
			dataType: 'json',
			data: function (params) {
				return {
					action: "getOrdenesProduccionText",					
					[strTokenID]: strTokenValue,
					strTerm: $.trim(params.term) === '' ? '' : params.term
				};
			}
		}
	});

	$('#vcOrdenProd').on('select2:unselect', function (e) {
        var data = e.params.data;  
		
		$("#vcOrdenProd option[value='"+data.id+"']").remove();
    });

	$('#vcOrdenProd').on('select2:open', function (e) {
		$("#vcOrdenProdElement").find(".select2-search__field").addClass("formatOP");
		aplicaFormatoOP();
	});

	$("#vcEstatusCotizacion").select2({
		theme: "bootstrap-5",
		language: "es",
		minimumInputLength: -1,
		multiple:true,
		delay: 250,
		type: "GET",
		ajax: {
			url: "../controllers/ventas/ventas_cotizaciones_controller",
			dataType: 'json',
			data: function (params) {
				return {
					strTerm: params.term,
					[strTokenID]: strTokenValue,
					action: "getEstatusCotizacion_JSON"
					};
			}
		}	
	});
});

function datatableCotizaciones(){

	//Obtenemos los parámetros dados
	var results = getParametersArray("filtersCotizaciones_Body", false);
	var filters = paramsArray_ToJson(results[1]);
	var params = new Array();

	params["filters"] = filters;
	params[strTokenID] = strTokenValue;
	params["action"] = "getCotizacionesJSON";

	// loading
	showLoading();

	//Inicializamos datatable del respectivo componente
	dataTableCotizaciones = $('#tablaCotizaciones').DataTable({
        
		ajax: {
			url: '../controllers/ventas/ventas_cotizaciones_controller',
			type: 'POST', 
			data: params,
			deferLoading:600,
		},
		rowId: 'intCotizacion',		
		select: { 
			items: 'row', 
			style:'single', // 
			// selector: ':not(:last-child)'  excluir el último row u útlima columna
			//Este parametro es un ejemplo de como podemos excluir columnas de la selección de rows
			//selector: 'tr>td:nth-child(2), tr>td:nth-child(3), tr>td:nth-child(4), tr>td:nth-child(5), tr>td:nth-child(6), tr>td:nth-child(7), tr>td:nth-child(8), tr>td:nth-child(9)'			
		},
		columns: [
			{data: "intCotizacion"},
            {data: "bytRevision"},
            {data: "strCliente"},
			{data: "strEstatusCot"},
			{data: "strFecha"},
			{data: "strFechaAlta"},
			{data: "strUsuarioAlta"},
		],
        buttons: [			
			{
				text: '<i data-feather="search" style="width:18px; aspect-ratio:1;"></i>Ver',
				className: 'btn-action-datatable',
				action: function ( e, dt, node, config ) { doAction_ConsCotizaciones(1); }
			},
			{
				text: '<i data-feather="x" style="width:20px; aspect-ratio:1;"></i>Cancelar',
				className: 'btn-action-datatable',
				action: function ( e, dt, node, config ) { doAction_ConsCotizaciones(2); }
			},	
			{
				text: '<i data-feather="printer" style="width:16px; aspect-ratio:1;"></i>Imprimir',
				className: 'btn-action-datatable',
				action: function ( e, dt, node, config ) { doAction_ConsCotizaciones(3); }
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
						text:'<i class="fa fa-file-excel-o"></i> Excel',
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
		columnDefs: [
			{targets: '_all', className: 'dt-head-center'},	
			{targets: '_all', className: 'dt-body-center'},
		],
		"preDrawCallback": function( settings ) {
      		$("#tablaCotizaciones" + ' tbody').fadeOut(200);			
		},
		"drawCallback": function(settings) {						
			$("#tablaCotizaciones" + ' tbody').fadeIn(200);
			hideLoading();
			feather.replace();
			scrollTo_Mobile('scrollVC');
			if(settings.json.success != 1) msgBox_Error("Error", settings.json.msg);
		},
    });

	
	$('#tablaCotizaciones').on('order.dt', function () {
		showLoading();
		
		// This will show: "Ordering on column 1 (asc)", for example
		//var order = dataTableCVA.order();
		//$('#orderInfo').html( 'Ordering on column '+order[0][0]+' ('+order[0][1]+')' );		
	});

	//Inicializamos evento dblclick para cargar el row
	$('#tablaCotizaciones' + ' tbody').off("dblclick").on('dblclick', 'tr', function () { 

		let row = dataTableCotizaciones.row(this).data(); 

		if (row.intCotizacion > 0){
			get_CotizacionByID(row.intCotizacion, row.bytRevision);
		} 
		
		dataTableCotizaciones.row(this).select();

	});
}

function doAction_ConsCotizaciones(optn){

	// obtenemos row seleccionado
	let row = dataTableCotizaciones.row('.selected').data();
	
	// validamos selección
	if (!row){
		msgBox_Warning("Aviso", "Debes seleccionar una cotización.");	
		return;
	}

	switch (optn){

		case 1:

			// validamos ID
			if (row["intCotizacion"] > 0) get_CotizacionByID(row["intCotizacion"], row["bytRevision"]);
			break;

		case 2:

			// validamos ID
			if (row["intCotizacion"] > 0) cancela_Cotizacion(row);
			break;
		case 3:

			// validamos ID
			if (row["intCotizacion"] > 0) printCotizacion(row);
			break;
	}

}

async function cancela_Cotizacion(row){


	// preguntamos si está seguro
	if (! await confirmBox("Aviso", "¿Está seguro que desea cancelar la cotización ?")) return;
	
	// loading
	showLoading();

	currCotizacion.get(row.intCotizacion, row.bytRevision).then( (objResponse) => { 

		if (objResponse.success != 1) {
			
			hideLoading().sleep(200).then(() =>{ 
				msgBox_Error("", objResponse.msg);				
			});
			return;
		}
		else{
			
			//Validamos si hay bug al generar
			if(currCotizacion.intCotizacion == 0){
				msgBox_Error("", "Error al obtener cotización, favor de avisar al administrador");
				return;
			}else{

				// guardamos
				fetch_Post("../controllers/ventas/ventas_cotizaciones_controller", {action: 'cancelar_Cotizacion', json: JSON.stringify(currCotizacion)})
				.then(function (objResponse){
					if(objResponse.success == 1){			
						hideLoading().sleep(200).then(async () => {

							msgBox_Success('', 'La cotización fue cancelada con éxito.');
							datatableCotizaciones();
							
						});
					}else hideLoading();
				});
			}

			
		}
		
	});

}
