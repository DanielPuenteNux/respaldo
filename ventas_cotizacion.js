var currCotizacion = new Ventas_Cotizaciones;
var countItemsCotizacion = 0;
var countItemsCotizacionDetalle = 0;
var countItemsDocPartidaCotizacion = 0;
var countItemsImagePartidaCotizacion = 0;
var count_ItemsBitacora_CP = 0;
var currDraggable_ID_Detalle = '';

$(document).ready(function()
{
	$('#vcSubTotal').on('input', function() {
        var subtotal = parseFloat($(this).val()) || 0;
        var precioConIVA = subtotal * 1.16;  // Aplica el IVA del 16%
        $('#vcTotal').val(precioConIVA.toFixed(2)); // Muestra el resultado con 2 decimales
    });

	initSelectsCotizacion();

	//funcion que se activa al presionar el boton de + que se ubica alado del select Forma
	$('#selectBoxArticuloCot .btn-add-articulo').click(function() {

		//invocamos el modal para agregar formato 
		accionAlmacenCatArticulos(1, {});

		$("#AgregarEditarArticulo").css('z-index', 1600);
	});

});

function showCotizacion(){

    limpiar_Cotizacion();

    // mostramos
	showContent('ventas_cotizacion');
}

function showEditCotizacion(){

	// Header	
	$("#ventas_cotizacion .card-header h5").text("Cotización #" + currCotizacion.intCotizacion + ' - ' + currCotizacion.bytRevision);
	$("#ventas_cotizacion .card-subheader").text("Estatus: "+currCotizacion.strEstatusCot);

	//Cargamos valores generales
	setValuesParameters("pnlInfoCotizacion", currCotizacion);
	$("#ventas_cotizacion #lrObservaciones").val(currCotizacion.strNotas);

	//Mostramos botón de rechazar
	$("#ventas_cotizacion .btnSave_Reject").show();

	//Si esta en el estatus inicial ocultamos el botón de rechazar
	if(currCotizacion.intEstatusCot == 1){
		$("#ventas_cotizacion .btnSave_Reject").hide();
	}

	//Ocultamos botones de estatuses y botones adicionales hasta que procese la remisión
	$("#buttonEnviarCotizacion").hide();

	//Mostramos botón de replicar cotización
	$("#ventas_cotizacion .btnSave").show();
	$("#ventas_cotizacion .btnReplicateCotizacion").show();
	$("#ventas_cotizacion .btnSaveRevision").show();
	$("#ventas_cotizacion .btnSave_Aprob").show();
	
	//Limpiamos partidas y luego cargamos partidas
	$("#wrapper_ItemsCotizacion").html("");

	currCotizacion.partidas.forEach(function(partida) {	

		var itemID = add_PartidaCotizacion(partida, 'wrapper_ItemsCotizacion', false);

		//Revisamos si tiene detalle
		if(partida.detalle.length > 0){

			partida.detalle.forEach(function(detalle) {
				add_PartidaDetalleCotizacion(detalle, itemID+" .wrapperDetalleArticulos", false);  
			});
		}
		
	});

	//$('.IsDate').datepicker();

	// mostramos
	showContent('ventas_cotizacion');
}

function showReadCotizacion(){
	
	// Header	
	$("#ventas_cotizacion .card-header h5").text("Remisión #" + currCotizacion.intRemision);
	$("#ventas_cotizacion .card-subheader").text("Estatus: "+ currCotizacion.strEstatusRemision);

	//Ocultamos elementos
	$("#pnlInfoRemision").hide();
	$("#pnlAddItem_Cotizacion").hide();
	$("#ventas_cotizacion .itemRow_Icon").hide();
	$("#ventas_cotizacion .itemField_Icon").hide();

	//Mostramos botón de ver seguimiento OP
	$("#ventas_cotizacion .showSOPRemision").show();
	$("#ventas_cotizacion .removeOPRemisionPartida").show();

	//Seteamos valores
	setValuesParameters("pnlInfoRemision", currCotizacion);
	$("#ventas_cotizacion #lrObservaciones").val(currCotizacion.strNotas);

	//Mostramos labels de read
	$("#pnlLabels_Cotizacion").show();
	$("#ventas_cotizacion #buttonsActionRemision").show();

	//Mostramos/ocultamos botones
	$("#ventas_cotizacion .btnReplicateRemision").hide();
	$("#ventas_cotizacion .btnSave").hide();

	//Revisamos si ya fue entregada y ocultamos todos
	if(currCotizacion.intEstatusCotizacion == 3){
		$("#buttonEntregarRemision").hide();
		$("#buttonEnviarRemision").hide();
		$("#buttonRejectRemision").hide();
		$("#ventas_Cotizacion .removeOPRemisionPartida").hide();
	}

	//Cargamos labels
	$("#pnlLabels_Cotizacion .cliente").text(currCotizacion.strCliente);
	$("#pnlLabels_Cotizacion .fechaCotizacion").text(currCotizacion.strFecha);

	//Limpiamos partidas y luego cargamos partidas
	$("#wrapper_ItemsRemision").html("");

	currCotizacion.detalle.forEach(function(item) {	
		add_itemCotizacion(item, 'wrapper_ItemsCotizacion', false);
	});

	// mostramos
	showContent('ventas_cotizacion');

}

function limpiar_Cotizacion(){

	currCotizacion.clear();

	$("#vcTipoCambio").prop( "disabled", false )

    //Inicializamos el estatus en "En proceso"
    currCotizacion.intEstatusCot = 1;
	currCotizacion.datFecha = getCurrentDateMX();
	currCotizacion.intTipoCotizacion = $("#tipoCot").attr('data-intTipoCotizacion');

	countItemsCotizacion = 0;
	countItemsCotizacionDetalle = 0;

	// Header
	$("#ventas_cotizacion h3").text('Cotización');
	$("#ventas_cotizacion .card-header h5").text("Nueva cotización");
	$("#ventas_cotizacion .card-subheader").text(getCurrentDate_Long());

	//Ocultamos boton de regresar Cotizacion y botones adicionales
	$("#ventas_cotizacion .btnSave_Reject").hide();
	$("#ventas_cotizacion .btnSave_Aprob").hide();
	$("#ventas_cotizacion .btnReplicateCotizacion").hide();
	$("#ventas_cotizacion .btnSaveRevision").hide();

	//Ocultamos botones de estatuses y botones adicionales hasta que procese la remisión
	$("#buttonEnviarCotizacion").hide();
	$("#buttonEntregarCotizacion").hide();

	//Mostramos elementos
	$("#pnlInfoCotizacion").show();
	$("#pnlAddItem_Cotizacion").show();
	$("#ventas_cotizacion .itemRow_Icon").show();
	$("#ventas_cotizacion .itemField_Icon").show();
	$("#ventas_cotizacion .btnSave").show();

	//Limpiamos campos
	clearFilters("ventas_cotizacion");
	$('#vcMoneda').val(null).trigger('change');
	$('#vcTipoCambio').val("").trigger("change");
	$('#vcCliente').val("").trigger("change");
	$("#vcNotas").val("");
	

	//Limpiamos partidas y luego cargamos partidas
	$("#wrapper_ItemsCotizacion").html("");

}

async function saveCotizacion()
{
	var valida = valida_infoCotizacion();

	if(!valida){	
		return;
	}

	getObjectCotizacion_ForDB();
	
	//Ejecutamos loader
	showLoading();
	
	fetch_Post("../controllers/ventas/ventas_cotizaciones_controller",
	{action: 'saveCotizacion',json: currCotizacion.getJSON_ForDB()}).then(function (objResponse){
		if(objResponse != false){
			hideLoading().sleep(200).then(() =>{ 

				//Actualizamos id
				currCotizacion.intCotizacion = objResponse.intCotizacion;

				// Header	
				// $("#ventas_cotizacion .card-header h5").text("Cotización #" + currCotizacion.intCotizacion);
				// $("#ventas_cotizacion .card-subheader").text("Estatus: En proceso");
				
				goBack();
				msgBox_Success('', 'La cotización fue generada con éxito.');
				datatableCotizaciones();
			});
		}
	});
	
}

async function newRevCotizacion(){
	var valida = valida_infoCotizacion();

	if(!valida){	
		return;
	}

	getObjectCotizacion_ForDB();
	
	//Ejecutamos loader
	showLoading();
	
	fetch_Post("../controllers/ventas/ventas_cotizaciones_controller",
	{action: 'newRevCotizacion',json: currCotizacion.getJSON_ForDB()}).then(function (objResponse){
		if(objResponse != false){
			hideLoading().sleep(200).then(() =>{ 

				//Actualizamos id
				currCotizacion.intCotizacion = objResponse.intCotizacion;

				// Header	
				// $("#ventas_cotizacion .card-header h5").text("Cotización #" + currCotizacion.intCotizacion);
				// $("#ventas_cotizacion .card-subheader").text("Estatus: En proceso");
				
				goBack();
				msgBox_Success('', 'La revisión fue generada con éxito.');
				datatableCotizaciones();
			});
		}
	});
}

function getObjectCotizacion_ForDB(){

	var resultsCotizacion = getParametersArray("pnlInfoCotizacion", false);
	
	//Cargamos valores generales
	currCotizacion.loadJSON(JSON.stringify(paramsArray_ToJson(resultsCotizacion[1])));
	
	//Limpiamos partidas
	currCotizacion.partidas = [];

	// recorremos partidas
	$("#wrapper_ItemsCotizacion .itemCotizacion").each(function( index ) {

		//Obtenemos id del item en turno
		var itemID = $(this).attr('id');

		//Deshabilitamos los valores del detalle para que no colisione con los atributos de la partida
		$('#'+itemID+' .wrapperDetalleArticulos').find('.formElement').attr('data-inactive', '');

		//Obtenemos valores de la partida en turno y cargamos al objeto
		var resultsPartida = getParametersArray($(this).attr('id'), false);
		var objPartida = $(this).data('data-obj');
		objPartida.loadJSON(JSON.stringify(paramsArray_ToJson(resultsPartida[1])))

		//Insertamos objeto al arreglo
		currCotizacion.partidas.push(objPartida);
		
		//Limpiamos detalle de la partida
		objPartida.detalle = [];

		//Revisamos si hay items detalle en la partida
		if($(this).find(".wrapperDetalleArticulos").children(".itemCotizacionDetalle").length > 0){

			//Habilitamos los valores del detalle para poder cargar los items al detalle de la partida
			$('#'+itemID+' .wrapperDetalleArticulos').find('.formElement').removeAttr('data-inactive');
		
			//Recorremos los items detalle de la partida para cargarlo a la partida
			$(this).find(".itemCotizacionDetalle").each(function( index ) {

				//Obtenemos valores del detalle de la partida en turno y cargamos al objeto
				var resultsDetalle = getParametersArray($(this).attr('id'), false);
				var objPartidaDetalle = $(this).data('data-obj');
				objPartidaDetalle.loadJSON(JSON.stringify(paramsArray_ToJson(resultsDetalle[1])));

				//Insertamos objeto al arreglo
				objPartida.detalle.push(objPartidaDetalle);	  		
			});
		}
	});

	currCotizacion.strNotas = $("#vcNotas").val();
	
	return true;
}

function valida_infoCotizacion(){

	var infoCotizacion = getParametersArray("pnlInfoCotizacion", true);

	var bolContinue = infoCotizacion[0];
	var bolValid = true;
	
	//Validamos campos vacíos
	if(!bolContinue){

		// mensaje de error
		msgBox_Warning("Falta información.", "Por favor rellene los campos en rojo.");
		bolValid = false;
	}

	//Validamos que haya al menos una partida
	if($("#wrapper_ItemsCotizacion").children(".itemRow").length === 0){
		
		// mensaje de error
		msgBox_Warning("Falta información.", "Por favor agregue al menos una partida a la cotización.");
		bolValid = false;
	}

	return bolValid;
}

function new_Partida(){

	// obtenemos objeto
	var objItem = new Ventas_Cotizaciones_Partida;
	
	// agregamos partida
	add_PartidaCotizacion(objItem, 'wrapper_ItemsCotizacion', true);
}

function add_PartidaCotizacion(obj, wrapperID, animate){
	
	// preparamos ID
	wrapperID = "#" + wrapperID;

	// clonamos primer articulo
	let newItem = $('#vc0').clone();

	// incrementamos contador
	countItemsCotizacion ++;

	// cambiamos el ID
	newItem.attr("id", "vc" + countItemsCotizacion);

	// obtenemos nueva ID
	var newItemID = newItem.attr("id");

	// mostramos
	$(wrapperID).show();
	
	if (animate) {

		// agregamos al final
		newItem.appendTo(wrapperID).slideDown(function() {


			// cargamos valores
			$("#" + newItemID + " .vcDescP").val(obj.strDescripcion);
			$("#" + newItemID + " .vcPrecioUnitarioPartida").val(obj.dblPrecioUnitario);
			$("#" + newItemID + " .vcPrecioTotalPartida").val(obj.dblPrecioTotal);
			$("#" + newItemID + " .vcPtjeUtilidadPartida").val(obj.dblPtjeUtilidad);
			$("#" + newItemID + " .vcUtilidad").val(obj.dblUtilidad);
			$("#" + newItemID + " .vcDiasEntregaPartida").val(obj.dblDiasEntrega);
			$("#" + newItemID + " .vcFechaEntregaPartida").val(obj.datFechaEntrega);
			$("#" + newItemID + " .vcFechaEntregaPartida").datepicker();
			$("#" + newItemID + " .vcCantidad").val(obj.dblCantidad);
			$("#" + newItemID + " .vcAncho").val(obj.dblAncho);
			$("#" + newItemID + " .vcLargo").val(obj.dblLargo);
			$("#" + newItemID + " .vcAlto").val(obj.dblAlto);
			$("#" + newItemID + " .vcTantos").val(obj.intTantos);
			$("#" + newItemID + " .vcCantTintas").val(obj.intCantidadTintas);

			//Validamos si es una partida nueva para que no deje cargar documentos hasta que se guarde en base de datos
			$("#" + newItemID + " #loadFilesPartidaC").attr("onclick", 'msgBox_Warning("","Debe guardar la partida para poder cargar documentos.")');
		});
	}else{

		//Aqui se cargan los valores que vienen del edit
		newItem.appendTo(wrapperID).show();

		// cargamos valores
		$("#" + newItemID + " .vcDescP").val(obj.strDescripcion);
		$("#" + newItemID + " .vcPrecioUnitarioPartida").val(obj.dblPrecioUnitario);
		$("#" + newItemID + " .vcPrecioTotalPartida").val(obj.dblPrecioTotal);
		$("#" + newItemID + " .vcPtjeUtilidadPartida").val(obj.dblPtjeUtilidad);
		$("#" + newItemID + " .vcUtilidad").val(obj.dblUtilidad);
		$("#" + newItemID + " .vcDiasEntregaPartida").val(obj.dblDiasEntrega);
		$("#" + newItemID + " .vcCantidad").val(obj.dblCantidad);
		$("#" + newItemID + " .vcAncho").val(obj.dblAncho);
		$("#" + newItemID + " .vcLargo").val(obj.dblLargo);
		$("#" + newItemID + " .vcAlto").val(obj.dblAlto);
		$("#" + newItemID + " .vcTantos").val(obj.intTantos);
		$("#" + newItemID + " .vcCantTintas").val(obj.intCantidadTintas);
		$("#" + newItemID + " .vcFechaEntregaPartida").val(obj.strFechaEntrega);
		$("#" + newItemID + " .vcFechaEntregaPartida").datepicker();

	}

	setOption_AjaxSelect(newItemID + " .vcAcabado", {id: obj.intAcabadoOP, text: obj.strAcabadoOP});
	setOption_AjaxSelect(newItemID + " .vcAplicacionTinta", {id: obj.intAplicacionTinta, text: obj.strAplicacionTinta});

	// Delay para setear controles
	setTimeout(function() {

		//Seteamos el id del acordion
		$("#" + newItemID + " .accordion-partida").attr('data-bs-target', '#filtersPartida_Body'+(animate ? countItemsCotizacion : obj.intPartida));
		$("#" + newItemID + " .accordion-collapse-partida").attr('id', 'filtersPartida_Body'+(animate ? countItemsCotizacion : obj.intPartida));

		//Seteamos el id de las tabs
		$("#" + newItemID + " .tabPartida").attr('href', '#tabPartidaCotizacion'+(animate ? countItemsCotizacion : obj.intPartida));
		$("#" + newItemID + " .tabPartidaDesarrollo").attr('href', '#tabDetalleCotizacion'+(animate ? countItemsCotizacion : obj.intPartida));
		$("#" + newItemID + " .tabPartidaDocs").attr('href', '#tabDocumentosCotizacion'+(animate ? countItemsCotizacion : obj.intPartida));

		//Seteamos el id de las tabs
		$("#" + newItemID + " .tab-pane-partida").attr('id', 'tabPartidaCotizacion'+(animate ? countItemsCotizacion : obj.intPartida));
		$("#" + newItemID + " .tab-pane-partida-desarrollo").attr('id', 'tabDetalleCotizacion'+(animate ? countItemsCotizacion : obj.intPartida));
		$("#" + newItemID + " .tab-pane-partida-docs").attr('id', 'tabDocumentosCotizacion'+(animate ? countItemsCotizacion : obj.intPartida));

		initSelectsPartidaCotizacion(newItemID);

		autosize($(".vcDescP"));

		//autosize.update($(".vcDescP"));

	}, 500);

	//Guardamos objeto en la partida
	obj.intPartida = (animate ? countItemsCotizacion : obj.intPartida);
	obj.bytSecuencia = (animate ? countItemsCotizacion : obj.bytSecuencia);
	$("#" + newItemID).data("data-obj", obj);

	//Revisamos si tiene documentos
	if (obj.docs.length > 0) {

		for(doc of obj.docs){
			add_DocPartida(doc, newItemID);
		}
		
	}

	$("#" + newItemID+' .container-docs-partida').children(".itemDocPartida").length > 0;
	$("#" + newItemID+' .container-image-partida').children(".itemImagePartida").length > 0;

	// checamos si debemos hacer animación
	if (animate) {

		// preparamos color			
		$("#" + newItemID).addClass("itemRow_blue");

		// quitamos color
		setTimeout(function() {				
			$("#" + newItemID).removeClass("itemRow_blue");
		}, 500);
	}

	return newItemID;
}

function new_Partida_Detalle(item){

	// obtenemos objeto
	var objItem = new Ventas_Cotizaciones_Partida_Detalle;
	var itemID = $(item).closest('.itemCotizacion').attr('id');
	var wrapperID = itemID+" .wrapperDetalleArticulos";

	//Se cambia el valor en el label
	$("#selectBoxArticuloCot .btn-primary").on("click", function(){

		$("#selectBoxArticuloCot").modal('hide');

		objItem.intArticulo = $("#SelectOptionsArticuloCot").val();
		objItem.strArticulo = $("#SelectOptionsArticuloCot").select2('data')[0].text;
		
		// agregamos partida
		add_PartidaDetalleCotizacion(objItem, wrapperID, true);

		$('#selectBoxArticuloCot .btn-primary').off('click');
	});

	//Se cierra el modal
	$("#selectBoxArticuloCot .btn-secondary").on("click", function(){
		$("#selectBoxArticuloCot").modal('hide');

		$('#selectBoxArticuloCot .btn-primary').off('click');
	});

	//Se actualiza el titulo del modal
	$("#selectBoxArticuloCot .formLabel").html("Agregar articulo");

	//Se muesta el modal
	let myModal = new bootstrap.Modal(document.getElementById('selectBoxArticuloCot'), {keyboard: false, backdrop: 'static', focus: true});
	myModal.show();
	
}

function add_PartidaDetalleCotizacion(obj, wrapperID, animate){
	
	// preparamos ID
	wrapperID = "#" + wrapperID;

	// clonamos primer articulo
	let newItem = $('#vcd0').clone();

	// incrementamos contador
	countItemsCotizacionDetalle ++;
	
	// cambiamos el ID
	newItem.attr("id", "vcd" + countItemsCotizacionDetalle);

	// obtenemos nueva ID
	let newItemID = newItem.attr("id");

	// mostramos
	$(wrapperID).show();
	
	// checamos si debemos mostrar la animación
	if (animate) {

		// agregamos al final
		newItem.appendTo(wrapperID).slideDown(function() {


			// cargamos valores
			$("#" + newItemID + " .articulo-content").html(obj.strArticulo+'<div class="itemField_Icon" onclick="edit_articuloDetalle_Cotizacion(this)" style="margin-left:auto;"><i class="icofont icofont-pencil-alt-2" style="color:#7c7c7c"></i></div>');
			$("#" + newItemID + " .vcdArticulo").val(obj.intArticulo);
			$("#" + newItemID + " .vcdPrecioUnitarioPartida").val(obj.dblPrecioUnitario);
			$("#" + newItemID + " .vcdPrecioTotalPartida").val(obj.dblPrecioTotal);
			$("#" + newItemID + " .vcdPtjeUtilidadPartida").val(obj.dblPtjeUtilidad);
			$("#" + newItemID + " .vcdUtilidadPartida").val(obj.dblUtilidad);
			$("#" + newItemID + " .vcdDiasEntregaPartida").val(obj.dblDiasEntrega);
			$("#" + newItemID + " .vcdCantidad").val(obj.dblCantidad);
			$("#" + newItemID + " .vcdAncho").val(obj.dblAncho);
			$("#" + newItemID + " .vcdAlto").val(obj.dblAlto);
			$("#" + newItemID + " .vcdLargo").val(obj.dblLargo);
			$("#" + newItemID + " .vcdFechaEntregaPartida").val(obj.datFechaEntrega);
			$("#" + newItemID + " .vcdFechaEntregaPartida").datepicker();

		});
	}else{

		//Aqui se cargan los valores que vienen del edit
		newItem.appendTo(wrapperID).show();

		$("#" + newItemID + " .articulo-content").html(obj.strArticulo+'<div class="itemField_Icon" onclick="edit_articuloDetalle_Cotizacion(this)" style="margin-left:auto;"><i class="icofont icofont-pencil-alt-2" style="color:#7c7c7c"></i></div>');
		$("#" + newItemID + " .vcdArticulo").val(obj.intArticulo);
		$("#" + newItemID + " .vcdPrecioUnitarioPartida").val(obj.dblPrecioUnitario);
		$("#" + newItemID + " .vcdPrecioTotalPartida").val(obj.dblPrecioTotal);
		$("#" + newItemID + " .vcdPtjeUtilidadPartida").val(obj.dblPtjeUtilidad);
		$("#" + newItemID + " .vcdUtilidadPartida").val(obj.dblUtilidad);
		$("#" + newItemID + " .vcdDiasEntregaPartida").val(obj.dblDiasEntrega);
		$("#" + newItemID + " .vcdCantidad").val(obj.dblCantidad);
		$("#" + newItemID + " .vcdAncho").val(obj.dblAncho);
		$("#" + newItemID + " .vcdAlto").val(obj.dblAlto);
		$("#" + newItemID + " .vcdLargo").val(obj.dblLargo);
		$("#" + newItemID + " .vcdFechaEntregaPartida").val(obj.datFechaEntrega);
		$("#" + newItemID + " .vcdFechaEntregaPartida").datepicker();
		
		//Revisamos si hay observaciones para cambiar el icono de mensaje vacio por el icono de mensaje con relleno
		if(obj.bytBitacora == 1) $("#" + newItemID + " .icon-message-empty").removeClass('icon-message-empty').addClass('icon-message-filled');

		//Revisamos si tiene conflicto el articulo para remarcarlo
		if(obj.bytConflicto == 1) $("#" + newItemID + " .alert-icon-lines").removeClass('alert-icon-lines').addClass('alert-icon');
		if(obj.bytConflicto == 1) $("#" + newItemID).css({
			'background-color': '#FFF3CD',
			'opacity': '1'
		});
	}

	// Delay para setear controles
	setTimeout(function() {
		partidaDetalle_setDragHandlers_Secuencia();
		detallePartida_setGrabbers();
		initSelectsPartidaDetalleCotizacion(newItemID);
	}, 500);

	//Guardamos objeto en la partida
	obj.intDetalle = (animate ? countItemsCotizacionDetalle : obj.intDetalle);
	obj.bytSecuencia = (animate ? countItemsCotizacionDetalle : obj.bytSecuencia);
	$("#" + newItemID).data("data-obj", obj);
	$("#" + newItemID).attr("draggable", true);


	// checamos si debemos hacer animación
	if (animate) {

		// preparamos color			
		$("#" + newItemID).addClass("itemRow_blue");

		// quitamos color
		setTimeout(function() {				
			$("#" + newItemID).removeClass("itemRow_blue");
		}, 500);
	}

	return true;
}

function partidaDetalle_setDragHandlers_Secuencia(){

	// configuramos listeners
	let items = document.querySelectorAll('.wrapperDetalleArticulos .itemRow');
	items.forEach(function(item) {		
		item.addEventListener('dragstart', partidaDetalle_handleDragStart);
		item.addEventListener('dragover', partidaDetalle_handleDragOver);
		item.addEventListener('dragenter', partidaDetalle_handleDragEnter);
		item.addEventListener('dragleave', partidaDetalle_handleDragLeave);
		item.addEventListener('dragend', partidaDetalle_handleDragEnd);
		item.addEventListener('drop', partidaDetalle_handleDrop);
	});

	// incluimos el parent
	// let item = document.querySelector('.mspe_WrapperSecuencia ol');
	// item.addEventListener('dragover', mspe_handleDragOver);
	// item.addEventListener('dragleave', mspe_handleDragLeave);
	// item.addEventListener('dragend', mspe_handleDragEnd);
	// item.addEventListener('drop', mspe_handleDrop);
}

function partidaDetalle_handleDragStart(e) {
	

	$("#" + e.target.id).css('opacity','0.4');
	e.dataTransfer.effectAllowed = 'move';
	e.dataTransfer.setData('text', e.target.id);
	currDraggable_ID_Detalle = e.target.id;
	

}

function partidaDetalle_handleDragOver(e) {
	e.preventDefault();	
	e.stopPropagation();
	
	// validamos que no sea el mismo ID
	if ($(this).attr("id") != currDraggable_ID_Detalle) {
		
		// drop permitido
		e.dataTransfer.dropEffect = "move";
		$(this).addClass('over');
		
	}
	
	return false;
}

function partidaDetalle_handleDragEnter(e) {
	
}

function partidaDetalle_handleDragLeave(e) {
	$(".wrapperDetalleArticulos .itemRow").removeClass('over');
}

function partidaDetalle_handleDragEnd(e) {
	this.style.opacity = '1';
	$(".wrapperDetalleArticulos .itemRow").removeClass('over');
}

function partidaDetalle_handleDrop(e) {
	
	// stops the browser from redirecting.
	e.stopPropagation(); 

	// obtenemos ID
	let itemID = e.dataTransfer.getData("text");

	// validamos que no sea el mismo ID
	if ($(this).attr("id") != itemID) {
		
		// obtenemos id del objeto actual
		let currID = $(this).attr("id");
		
		// checamos si es movimiento hacia arriba (el index actual es menor al del item en movimiento)
		if ($(this).index() < $("#" + itemID).index()){

			// movemos el item
			$("#" + itemID).insertBefore("#" + currID);
		}
		else{
			// movemos el item
			$("#" + itemID).insertAfter("#" + currID);
		}

		// actualizamos grabber
		detallePartida_setGrabbers();

		//Actualizamos secuencia
		$(".wrapperDetalleArticulos .itemCotizacionDetalle").each(function( index ) {

			$(this).data('data-obj').bytSecuencia = index + 1;
		});
	}
	
	return false;
}

function add_DocPartida(obj, wrapperID){

	//Revisamos si es una imagen para mostrar el preview
	if(obj.intTipoArchivo == 4 || obj.intTipoArchivo == 2){

		// id del elemento a clonar
		var newItem = $("#imgPreviewPartida0").clone();

		// incrementamos contador
		countItemsImagePartidaCotizacion ++;

		// cambiamos el ID
		newItem.attr("id", "imgPreviewPartida" + countItemsImagePartidaCotizacion);

		// obtenemos nueva ID
		var newItemID = newItem.attr("id");

		//Cargamos la imagen para obtener proporciones y ajustar el width del globo
		var imageFullScreen = new Image();

		imageFullScreen.src="../files/"+obj.strFileName;

		$(imageFullScreen).on('load',function(){

			var originalWidth = imageFullScreen.width;
			var originalHeight = imageFullScreen.height;
			var urlimg = '../files/'+obj.strFileName;

			$("#" + newItemID + " .image-partida-cotizacion").removeAttr('onclick');

			$("#" + newItemID + " .image-partida-cotizacion").off('click').click(function(){
				zoomImage(urlimg,originalWidth,originalHeight);
			});
			
		});

		const img = new Image();
		img.src="src=../../../thumbnail?f=files/"+obj.strFileName+"&w=300&h=200";

		$(img).on('load',function(){

			var newWidth = Number(img.width) + 2;

			// ajustamos el width de la imagen al label
			$("#" + newItemID + " .docNameImage").css('width', newWidth+'px');
		});

		// mostramos
		$("#" + wrapperID + " .container-image-partida").show();

		newItem.appendTo("#" + wrapperID + " .container-image-partida").show();

		// cargamos imagen
		$("#" + newItemID + " .image-partida-cotizacion").attr("src", "src=../../../thumbnail?f=files/"+obj.strFileName+"&w=300&h=200");

		// cargamos descripción
		$("#" + newItemID + " .docName").html(obj.strNombreDoc);

		//Revisamos que tipo de documento es para marcar el check o la estrella
		if(obj.intTipoDocumentoCot == 3) $("#" + newItemID + " .icon-toggle-check").removeClass("icon-check-sofi").addClass("icon-check-green");
		if(obj.intTipoDocumentoCot == 4) $("#" + newItemID + " .icon-toggle-star").removeClass('icon-star-sofi').addClass('icon-star-gold');
		

		//Cargamos objeto en el elemento 
		$("#" + newItemID).data("data-obj", obj);

	}else{

		// id del elemento a clonar
		var newItem = $("#docLoadedPartida0").clone();

		// incrementamos contador
		countItemsDocPartidaCotizacion ++;

		// cambiamos el ID
		newItem.attr("id", "docLoadedPartida" + countItemsDocPartidaCotizacion);

		// obtenemos nueva ID
		var newItemID = newItem.attr("id");

		// conversión de bytes a MB
		fileSize = (obj.intFileSizeKB / (1024)).toFixed(2);

		if(fileSize >= 1000){fileSize = 1 + " MB";}else{fileSize = fileSize + " KB";}

		// mostramos
		$("#" + wrapperID + " .container-docs-partida").show();
		$("#" + wrapperID + " .docstext").show();
		
		// agregamos al final
		newItem.appendTo("#" + wrapperID + " .container-docs-partida").slideDown(function() {

			// cargamos descripción
			$("#" + newItemID + " .docName").html(obj.strNombreDoc);


			// cargamos datos
			$("#" + newItemID).attr("data-Name", obj.strNombreDoc);
			$("#" + newItemID).data('data-obj', obj);

			switch (obj.intTipoArchivo) {
				case 1:
					$("#" + newItemID + " #iconPDF").show();
					break;
				case 6:
					
					$("#" + newItemID + " #iconXLSX").show();

					break;
			
				default:
					break;
			}
			
		});

		//Cargamos objeto en el elemento 
		$("#" + newItemID).data("data-obj", obj);
	}

	//Ocultamos texto de documentos cargados
	$("#" + wrapperID + " .text-content-empty").hide();
	
}

function initSelectsCotizacion(){
	
	$("#vcCliente").select2({
		theme: "bootstrap-5",
		language: "es",
		minimumInputLength: 3,
		multiple:false,
		dropdownAutoWidth : false,
		delay: 250,
		type: "GET",
		ajax: {
			url: "../controllers/ordenProduccion/ordenProduccion_controller",
			dataType: 'json',
			data: function (params) {
				return {
					strTerm: params.term,
					[strTokenID]: strTokenValue,
					action: "getClientes"
					};
			}
		}	
	});

	$("#vcTipoServicio").select2({
		theme: "bootstrap-5",
		language: "es",
		minimumInputLength: -1,
		multiple:false,
		dropdownAutoWidth : false,
		delay: 250,
		type: "GET",
		ajax: {
			url: "../controllers/ventas/ventas_cotizaciones_controller",
			dataType: 'json',
			data: function (params) {
				return {
					strTerm: params.term,
					[strTokenID]: strTokenValue,
					action: "getTiposServicioCotizacion_JSON"
					};
			}
		}	
	});

	$("#vcVendedor").select2({
		theme: "bootstrap-5",
		language: "es",
		minimumInputLength: -1,
		multiple:false,
		dropdownAutoWidth : false,
		delay: 250,
		type: "GET",
		ajax: {
			url: "../controllers/ventas/ventas_cotizaciones_controller",
			dataType: 'json',
			data: function (params) {
				return {
					strTerm: params.term,
					[strTokenID]: strTokenValue,
					action: "getVendedores_JSON"
					};
			}
		}	
	});

	$("#vcEstatusCot").select2({
		theme: "bootstrap-5",
		language: "es",
		minimumInputLength: -1,
		multiple:false,
		dropdownAutoWidth : false,
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

	$(".cotEstatus").select2({
		theme: "bootstrap-5",
		language: "es",
		minimumInputLength: -1,
		multiple:false,
		dropdownParent: $("#confirmBoxRechazarCotizacion"),
		delay: 250,
		type: "GET",
		ajax: {
			url: "../controllers/ventas/ventas_cotizaciones_controller",
			dataType: 'json',
			data: function (params) {
				return {
					strTerm: params.term,
					[strTokenID]: strTokenValue,
					action: "getEstatusRechazarCotizacion_JSON"
					};
			}
		}	
	});

	$(".SelectOptionsArticuloCot").select2({
		theme: "bootstrap-5",
		language: "es",
		minimumInputLength: 3,
		multiple:false,
		dropdownParent: $("#selectBoxArticuloCot"),
		type: "GET",
		escapeMarkup: function (text) { return text; },
		ajax: {
			url: "../controllers/ventas/ventas_cotizaciones_controller",
			dataType: 'json',
			data: function (params) {
				return {
					strTerm: params.term,
					[strTokenID]: strTokenValue,
					action: "getArticulosAlmacen_JSON"
					};
			}
		}
		
	});

	$("#vcMoneda").select2({
		minimumInputLength: -1,
		theme: "bootstrap-5",
		multiple:false,
		allowClear: false,
		placeholder: '',
		delay: 250,
		maximumSelectionLength: 2,
		type: "GET",
		escapeMarkup: function (text) { return text; },
		ajax: {
			url: "../controllers/controllerGeneral",
			dataType: 'json',
			data: function (params) {
				return {
					action: "getMonedas",
					strTerm:  $.trim(params.term) === '' ? '' : params.term,					
					[strTokenID]: strTokenValue					
				};
			}
		}
	});

	// Se asigna el tipo de cambio según la moneda seleccionada
	$('#vcMoneda').on('select2:close', function (e) {

		if($(this).select2("data")[0] != undefined)
		{

			// actualizamos variable
			//$("#monedaFooterOC").text($(this).select2("data")[0].siglas);

			// obtenemos tipo de cambio
			dbltipocambio = $(this).select2("data")[0].dbltipocambio;
			
			$('#vcTipoCambio').attr('data-Value', dbltipocambio);
			$('#vcTipoCambio').val(dbltipocambio).trigger("change");
		}
	});

	// Se valida continuamente que el campo de moneda se bloquee en dado caso de que se seleccione peso, ya que no se puede editar.
	$('#vcMoneda').on('change',function()
	{
		if($(this).val() == 1) $("#vcTipoCambio").prop( "disabled", true ); 
		else $("#vcTipoCambio").prop( "disabled", false );

		//En dado caso de que cambie el tipo de moneda, los campos de cantidad, precio unitario e importe se reinician
		$('#vcSubTotal').val('');
		$('#vcTotal').val('');
		$('#vcUtilidad').val('');		
		$('#vcPtjeUtilidad').val('');		

	});
}

function initSelectsPartidaCotizacion(partidaID){

	$("#"+partidaID+" .vcAcabado").select2({
		theme: "bootstrap-5",
		language: "es",
		minimumInputLength: -1,
		multiple:false,
		dropdownAutoWidth : false,
		delay: 250,
		type: "GET",
		ajax: {
			url: "../controllers/ordenProduccion/ordenProduccion_editor_controller",
			dataType: 'json',
			data: function (params) {
				return {
					strTerm: params.term,
					[strTokenID]: strTokenValue,
					action: "getAcabadosOP_JSON"
					};
			}
		}	
	});

	$("#"+partidaID+" .vcAplicacionTinta").select2({
		theme: "bootstrap-5",
		language: "es",
		minimumInputLength: -1,
		multiple:false,
		dropdownAutoWidth : false,
		delay: 250,
		type: "GET",
		ajax: {
			url: "../controllers/ventas/ventas_cotizaciones_controller",
			dataType: 'json',
			data: function (params) {
				return {
					strTerm: params.term,
					[strTokenID]: strTokenValue,
					action: "getAplicacionTintaCotizacion_JSON"
					};
			}
		}	
	});

	// Evento cuando cambia el precio unitario
	$("#"+partidaID+" .vcPrecioUnitarioPartida").on('input', function() {

		recalculaPrecioUnitarioPartida(partidaID);
	});

    // Recalculamos partida cuando cambia la cantidad
    $("#" + partidaID + " .vcCantidad").on('input', function () {
        recalculaPartida(partidaID);
    });

    // Recalculamos precio unitario y utilidad cuando cambia el precio total
    $("#" + partidaID + " .vcPrecioTotalPartida").on('input', function () {
        if (!$(this).prop('disabled')) {
            recalculaPrecioUnitarioUtilidadPartida(partidaID);
        }
    });

    // Recalculamos porcentaje de utilidad cuando cambia la utilidad
    $("#" + partidaID + " .vcUtilidad").on('input', function () {
        if (!$(this).prop('disabled')) {
            recalculaPtjeUtilidadPartida(partidaID);
        }
    });

    // Recalculamos utilidad cuando cambia el porcentaje de utilidad
    $("#" + partidaID + " .vcPtjeUtilidadPartida").on('input', function () {
        if (!$(this).prop('disabled')) {
            recalculaUtilidadPartida(partidaID);
        }
    });
}

function recalculaPartida(partidaID) {

	//Obtenemos cantidad
    var cantidad = parseFloat($("#" + partidaID + " .vcCantidad").val()) || 0;

	//Obtenemos precio unitario
    var precioUnitario = parseFloat($("#" + partidaID + " .vcPrecioUnitarioPartida").val()) || 0;

   	// Si el precio total está desbloqueado, recalcular precio total con cantidad y precio unitario
	if (!$("#" + partidaID + " .vcPrecioTotalPartida").prop('disabled')) {

		//Obtenemos cálculo actual para el precio total
		var precioTotal = cantidad * precioUnitario;

		$("#" + partidaID + " .vcPrecioTotalPartida").val(precioTotal.toFixed(2));  // Recalcular precio total
	}

	// Si el precio total está bloqueado, recalcular precio unitario con cantidad
	if ($("#" + partidaID + " .vcPrecioTotalPartida").prop('disabled') && !$("#" + partidaID + " .vcPrecioUnitarioPartida").prop('disabled')) {

		//Obtenemos precio total bloqueado
		var precioTotal = parseFloat($("#" + partidaID + " .vcPrecioTotalPartida").val()) || 0;

		//Obtenemos precio unitario calculadoi
		var precioUnitarioCalculado = precioTotal / cantidad;

		$("#" + partidaID + " .vcPrecioUnitarioPartida").val(precioUnitarioCalculado.toFixed(2));  // Actualizar precio unitario
	}

    recalculaUtilidadPartida(partidaID); // Asegura que la utilidad se actualice cuando cambia el precio total
	recalculaPtjeUtilidadPartida(partidaID); // Asegura que el porcentaje de utilidad se actualice cuando cambia el precio total
}

function recalculaPrecioUnitarioPartida(partidaID){
	var precioUnitario = parseFloat($("#"+partidaID+" .vcPrecioUnitarioPartida").val()) || 0;
	var precioTotal = parseFloat($("#"+partidaID+" .vcPrecioTotalPartida").val()) || 0;

	// Si el precio total está bloqueado, ajustamos la cantidad
	if ($("#"+partidaID+" .vcPrecioTotalPartida").prop('disabled')) {
		
		if (precioUnitario > 0) {
			var cantidad = precioTotal / precioUnitario;
			$("#"+partidaID+" .vcCantidad").val(cantidad.toFixed(2));
		}
	}

	// Recalcular utilidad y porcentaje de utilidad si es necesario
	recalculaUtilidadPartida(partidaID);
}

function recalculaPrecioUnitarioUtilidadPartida(partidaID) {
    var precioTotal = parseFloat($("#" + partidaID + " .vcPrecioTotalPartida").val()) || 0;
    var cantidad = parseFloat($("#" + partidaID + " .vcCantidad").val()) || 1;
    
    // Si el precio unitario no está bloqueado, se recalcula
    if (!$("#" + partidaID + " .vcPrecioUnitarioPartida").prop('disabled')) {

        var precioUnitario = precioTotal / cantidad;
        $("#" + partidaID + " .vcPrecioUnitarioPartida").val(precioUnitario.toFixed(2));
    }

    recalculaUtilidadPartida(partidaID); // Se asegura de actualizar la utilidad
}

function recalculaPtjeUtilidadPartida(partidaID) {

    var utilidad = parseFloat($("#" + partidaID + " .vcUtilidad").val()) || 0;
    var precioTotal = parseFloat($("#" + partidaID + " .vcPrecioTotalPartida").val()) || 0;

    if (!$("#" + partidaID + " .vcPtjeUtilidadPartida").prop('disabled')) {
        var porcentajeUtilidad = (utilidad / precioTotal) * 100;
        $("#" + partidaID + " .vcPtjeUtilidadPartida").val(porcentajeUtilidad.toFixed(2));
    }
}

function recalculaUtilidadPartida(partidaID) {
	
    var precioTotal = parseFloat($("#" + partidaID + " .vcPrecioTotalPartida").val()) || 0;
    var porcentajeUtilidad = parseFloat($("#" + partidaID + " .vcPtjeUtilidadPartida").val()) || 0;

    // Si la utilidad no está bloqueada, la recalculamos
    if (!$("#" + partidaID + " .vcUtilidad").prop('disabled')) {
        var utilidad = (porcentajeUtilidad / 100) * precioTotal;
        $("#" + partidaID + " .vcUtilidad").val(utilidad.toFixed(2));
    }
}

function initSelectsPartidaDetalleCotizacion(partidaDetalleID){

	// $("#"+partidaDetalleID+" .vcdArticulo").select2({
	// 	theme: "bootstrap-5",
	// 	language: "es",
	// 	minimumInputLength: 3,
	// 	multiple:false,
	// 	delay: 350,
	// 	placeholder: '',
	// 	escapeMarkup: function (text) { return text; },
	// 	ajax: {
	// 		url: "../controllers/controllerGeneral",
	// 		dataType: 'json',
	// 		data: function (params) {
	// 			return {
	// 				strTerm: params.term,
	// 				[strTokenID]: strTokenValue,
	// 				action: "getArticulos_Almacen"
	// 			};
	// 		}
	// 	},
	// 	tags: true,
	// 	createTag: function (params) {
	// 		var term = $.trim(params.term);
		
	// 		if (term === '') {
	// 		  return null;
	// 		}
		
	// 		if (this.$element.find('option').length === 0) {
	// 		  this.$element.append($('<option data-select2-tag="true">'));
	// 		}
		
	// 		return {
	// 		  id: term,
	// 		  text: term
	// 		}
	// 	},
	// 	insertTag: function (data, tag) {
	// 		// Insert the tag at the end of the results
	// 		tag.isTag = true;			
	// 		data.push(tag);
	// 	},
	// 	templateResult: function (option){			
	// 		if (option.isTag) {
	// 			return $('<div class="addOption">' + option.text + '</div>');
	// 		} else {
	// 			return option.text;
	// 		}
	// 	}	
	// });

	// $("#"+partidaDetalleID+" .vcdArticulo").on('select2:selecting', function (e) {
		
	// 	// obtenemos item
	// 	let item = e.params.args.data;

	// 	// checamos si es un Tag
	// 	if (item.isTag) {
            
	// 		// cancelamos este evento para que no se agregue aquí porque no tendría el ID correcto			
	// 		e.preventDefault();
    //         e.stopPropagation();

	// 		// limpiamos 
	// 		$(this).empty().trigger('change');
	// 		$(this).select2("close");

	// 		// agregamos 	
	// 		var newOption = new Option(item.text, item.text, false, false);
	// 		$(this).append(newOption).trigger('change');
			
			
    //     }

	// 	$("#"+partidaDetalleID+" .vcdDescP").val($(this).select2('data')[0].text);
	// });
	
	// Recalculamos partida cuando cambia la cantidad
    $("#" + partidaDetalleID + " .vcdCantidad").on('input', function () {

        //Obtenemos cantidad
		var cantidad = parseFloat($("#" + partidaDetalleID + " .vcdCantidad").val()) || 0;

		//Obtenemos precio unitario
		var precioUnitario = parseFloat($("#" + partidaDetalleID + " .vcdPrecioUnitarioPartida").val()) || 0;

		//Obtenemos cálculo actual para el precio total
		var precioTotal = cantidad * precioUnitario;

		$("#" + partidaDetalleID + " .vcdPrecioTotalPartida").val(precioTotal.toFixed(2));  // Recalcular precio total
		
    });

	// Recalculamos partida cuando cambia la cantidad
    $("#" + partidaDetalleID + " .vcdPrecioTotalPartida").on('input', function () {
		
        //Obtenemos cantidad
		var cantidad = parseFloat($("#" + partidaDetalleID + " .vcdCantidad").val()) || 0;

		//Obtenemos precio unitario
		var precioTotal = parseFloat($("#" + partidaDetalleID + " .vcdPrecioTotalPartida").val()) || 0;

		//Obtenemos cálculo actual para el precio unitario
		var precioUnitario = precioTotal / cantidad;

		$("#" + partidaDetalleID + " .vcdPrecioUnitarioPartida").val(precioUnitario.toFixed(2));  // Recalcular precio total
		
    });

	// Recalculamos partida cuando cambia la cantidad
    $("#" + partidaDetalleID + " .vcdPrecioUnitarioPartida").on('input', function () {
		
        //Obtenemos cantidad
		var cantidad = parseFloat($("#" + partidaDetalleID + " .vcdCantidad").val()) || 0;

		//Obtenemos precio unitario
		var precioUnitario = parseFloat($("#" + partidaDetalleID + " .vcdPrecioUnitarioPartida").val()) || 0;

		//Obtenemos cálculo actual para el precio unitario
		var precioTotal = cantidad * precioUnitario;

		$("#" + partidaDetalleID + " .vcdPrecioTotalPartida").val(precioTotal.toFixed(2));  // Recalcular precio total
		
    });
}

function get_CotizacionByID(intCotizacion, bytRevision){
	
	// loading
	showLoading();
	
	// guardamos
	fetch_Post("../controllers/ventas/ventas_cotizaciones_controller", {
		action: 'getCotizacionByID', 
		p1: intCotizacion, 
		p2: bytRevision
	})
	.then(function (objResponse){
		if(objResponse.success == 1){

			limpiar_Cotizacion();
			
			// cargamos info
			if (!currCotizacion.loadJSON(objResponse.json)){				
				hideLoading().sleep(200).then(() =>{ 				
					msgBox_Error("Error", "No se pudo encontrar la cotización");	
				});
				return;
			}
			
			showEditCotizacion();
			

			// if(currCotizacion.intEstatusCot == 2 || currCotizacion.intEstatusCot == 3 || currCotizacion.intEstatusCot == 5){
			// 	showReadRemision();
			// }

			// ocultamos loading
			hideLoading();

		}else hideLoading();
	});

}

async function aprobar_Cotizacion(){

	// preguntamos si está seguro
	if (! await confirmBox("Aviso", "¿Está seguro que desea aprobar la cotización ?")) return;

	// validamos info
	if (!valida_infoCotizacion()) return;

	//Cargamos objeto con valores
	getObjectCotizacion_ForDB();
	
	// loading
	showLoading();
	
	// guardamos
	fetch_Post("../controllers/ventas/ventas_cotizaciones_controller", {
		action: 'aprobar_Cotizacion', 
		json: currCotizacion.getJSON_ForDB()
	})
	.then(function (objResponse){
		if(objResponse.success == 1){			
			hideLoading().sleep(200).then(() =>{

				msgBox_Success('', 'La cotización fue aprobada con éxito.');
				
				goBack();

				datatableCotizaciones();
			});
		}else hideLoading();
	});

}

async function rechazar_Cotizacion(){

	//Inicializamos parametro de estatus, si no está en estatus "En cotización", mandamos en 0 el parámetro y determinamos el estatus anterior en base de datos
	var intEstatusCot = 0;

	//Revisamos si está en el estatus "En cotizacion" ya que estando en este estatus puede mover hasta un estatus determinado hacía atrás
	if(currCotizacion.intEstatusCot == 3){
		intEstatusCot = await confirmBoxRechazarCotizacion("Aviso");

		if (!intEstatusCot) return;
	} 

	// validamos info
	if (!valida_infoCotizacion()) return;

	//Cargamos objeto con valores
	getObjectCotizacion_ForDB();
	
	// loading
	showLoading();
	
	// guardamos
	fetch_Post("../controllers/ventas/ventas_cotizaciones_controller", {
		action: 'rechazar_Cotizacion', 
		json: currCotizacion.getJSON_ForDB(),
		p1: intEstatusCot
	})
	.then(function (objResponse){
		if(objResponse.success == 1){
			hideLoading().sleep(200).then(() =>{

				msgBox_Success('', 'La cotización fue rechazada con éxito.');
				
				goBack();

				datatableCotizaciones();
			});
		}else hideLoading();
	});

}

async function CargaDocumentoPartida()
{
	//Obtenemos el objeto actual mediante el modal
	var currElementId = $("#docsBox").data('currElementID');
	var obj = $("#"+currElementId).data('data-obj');

	let typeFile = $("#docsBox").data("buttonValueType");
	
	objDocs = await uploadFiles(arrUFile_FilterType[typeFile.toLowerCase()], obj.intPartida, 'cp', 'files');

	if(!objDocs) return;

	//Convertimos en objeto los documentos
	arrayDocs = getFiles_Collection(objDocs, Ventas_Cotizaciones_Partida_Docs, 2);

	//Si hay documentos agregamos al wrapper
	if(arrayDocs){

		arrayDocs.forEach(doc => {

			//Seteamos ids de la cotizacion
			doc.intCotizacion = obj.intCotizacion;
			doc.bytRevision = obj.bytRevision;
			doc.intPartida = obj.intPartida;

			//Si es una imagen la marcamos con el tipo de documento (imagen de referencia para la partida en sofi)
			if(doc.intTipoArchivo == 4 || 2) doc.intTipoDocumentoCot = 1;

			//Si es un PDF lo marcamos con el tipo de documento (documento generico)
			if(doc.intTipoArchivo == 1) doc.intTipoDocumentoCot = 2;

			//Seteamos nuevos docs al objeto del dom
			obj.docs.push(doc);

			//Agregamos documento
			add_DocPartida(doc, currElementId);

			saveDocumentoPartidaCotizacion();
			$("#docsBox").modal('hide');
		});

	}

}

async function saveDocumentoPartidaCotizacion()
{
	//Obtenemos el objeto actual mediante el modal
	var currElementID = $("#docsBox").data('currElementID');
	var currObj = $("#"+currElementID).data('data-obj');
	
	//Instanciamos objeto para mandar a base de datos
	let objPartida = new Ventas_Cotizaciones_Partida;
	objPartida.intCotizacion = currObj.intCotizacion;
	objPartida.intPartida = currObj.intPartida;
	objPartida.bytRevision = currObj.bytRevision;
	
	//Asignamos nuevos valores a los documentos de la entrada
	currObj.docs.forEach(doc => {
		objPartida.docs.push(doc);
	});

	fetch_Post_Promise("../controllers/ventas/ventas_cotizaciones_controller",
	{action: 'cargaDocsPartidaCotizacion',json: objPartida.getJSON_ForDB()}).then(function (objResponse){
		
		if(objResponse.success != "1"){
			
			msgBox_Error("Error", objResponse.msg)
			
		}
	});
}

async function CargaDocumentoPartidaDetalle()
{
	//Obtenemos el objeto actual mediante el modal
	var currElementId = $("#docsBox").data('currElementID');
	var obj = $("#"+currElementId).data('data-obj');

	let typeFile = $("#docsBox").data("buttonValueType");
	
	objDocs = await uploadFiles(arrUFile_FilterType[typeFile.toLowerCase()], obj.intDetalle, 'cpdet', 'files');

	if(!objDocs) return;

	//Convertimos en objeto los documentos
	arrayDocs = getFiles_Collection(objDocs, Ventas_Cotizaciones_Partida_Detalle_Docs, 2);

	//Si hay documentos agregamos al wrapper
	if(arrayDocs){

		arrayDocs.forEach(doc => {

			//Seteamos ids de la cotizacion
			doc.intCotizacion = obj.intCotizacion;
			doc.bytRevision = obj.bytRevision;
			doc.intPartida = obj.intPartida;
			doc.intDetalle = obj.intDetalle;

			//Seteamos nuevos docs al objeto del dom
			obj.docs.push(doc);

			//Agregamos documento 
			add_DocGenerico(doc, "docLoaded", "wrapperDocsGenerico");
		});

	}

}

async function saveDocumentoPartidaDetalle()
{
	//Obtenemos el objeto actual mediante el modal
	var currElementID = $("#docsBox").data('currElementID');
	var currObj = $("#"+currElementID).data('data-obj');
	
	//Instanciamos objeto para mandar a base de datos
	let objPartidaDetalle = new Ventas_Cotizaciones_Partida_Detalle;
	objPartidaDetalle.intCotizacion = currObj.intCotizacion;
	objPartidaDetalle.intDetalle = currObj.intDetalle;
	objPartidaDetalle.intPartida = currObj.intPartida;
	objPartidaDetalle.bytRevision = currObj.bytRevision;
	
	//Asignamos nuevos valores a los documentos de la entrada
	currObj.docs.forEach(doc => {
		objPartidaDetalle.docs.push(doc);
	});
	
	//Ejecutamos loader
	showLoading();
	
	fetch_Post("../controllers/ventas/ventas_cotizaciones_controller",
	{action: 'cargaDocsPartidaDetalleCotizacion',json: objPartidaDetalle.getJSON_ForDB()}).then(function (objResponse){
		if(objResponse != false){
			hideLoading().sleep(200).then(() =>{ 


			});
		}
	});
}

function removeDocPartidaDetalleCotizacion(el){

	//Se obtiene el objeto del documento
	let objDocument = $(el).parent().data('data-obj');

	// eliminamos con animación
	$(el).parent().animate({height: 'toggle'}, 200, function() {
		$(this).remove();
	});

	//Recorremos el array para limpiar el array de documentos que sean de tipo imagen
	$.each(currCotizacion.partidas, function( key, partida ) {
		$.each(partida.detalle, function( keyDoc, det ) {
		
			$.each(det.docs, function( keyDoc, doc ) {
		
				if(doc == undefined) return;
				if(doc.strNombreDoc == objDocument.strNombreDoc){
		
					det.docs.splice(keyDoc, 1);
				}
				
			});
			
		});
		
	});
}

function removePartidaObj(el){

	// eliminamos con animación
	$(el).closest('.itemRow').animate({height: 'toggle'}, 200, function() {

		$(this).hide();
		$(this).data('data-obj').bytDelete = 1;
		
		//Actualizamos secuencia
		$("#wrapper_ItemsCotizacion .itemCotizacion").each(function( index ) {

			$(this).data('data-obj').bytSecuencia = index + 1;
		});
	});
}

function removePartidaDetalleObj(el){

	// eliminamos con animación
	$(el).closest('.itemRow').animate({height: 'toggle'}, 200, function() {

		$(this).hide();
		$(this).data('data-obj').bytDelete = 1;
		
		//Actualizamos secuencia
		$(".wrapperDetalleArticulos .itemCotizacionDetalle").each(function( index ) {

			$(this).data('data-obj').bytSecuencia = index + 1;
		});
	});
}

function printCotizacion(row)
{	
	//Revisamos si el tipo de cotización es de formas inteligentes
	if(row.intTipoCotizacion == 1){
		window.open('../fpdf/print_Cotizacion?p1=' + row.intCotizacion + '&p2='+row.bytRevision + '&' + strTokenID + '=' + strTokenValue, '_blank');
	}

	if(row.intTipoCotizacion == 2){
		window.open('../fpdf/print_Cotizacion_Nux?p1=' + row.intCotizacion + '&p2='+row.bytRevision + '&' + strTokenID + '=' + strTokenValue, '_blank');
	}
	
}

function printCurrCotizacion()
{	
	window.open('../fpdf/print_Cotizacion?p1=' + currCotizacion.intCotizacion + '&' + strTokenID + '=' + strTokenValue, '_blank');
}

async function createReplicaCotizacion(){

	//Preguntamos si esta seguro de crear réplica
	if (! await confirmBox("Aviso", "¿Está seguro que desea crear una réplica de la remisión: #" + currRemision.intRemision + "?")) return;

	//Lipiamos ID de remisión para que se cree una replica de esta
	currRemision.intRemision = 0;

	//Guardamos replica de la remisión
	saveRemision();

	
}

function toggleAccordionPartida(itemID){

	$(itemID).closest('.itemCotizacion').find(".tabPartida").click();
	
}

function clonePartidaCotizacion(item){

	//Se obtiene el id del elemento
	let itemID = $(item).closest('.itemCotizacion').attr('id');

	//Deshabilitamos los valores del detalle para que no colisione con los atributos de la partida
	$('#'+itemID+' .wrapperDetalleArticulos').find('.formElement').attr('data-inactive', '');

	let resultsPartida = getParametersArray(itemID, false);
	let obj = $(item).closest('.itemCotizacion').data('data-obj');
	obj.loadJSON(JSON.stringify(paramsArray_ToJson(resultsPartida[1])));

	// agregamos partida
	var newPartidaID = add_PartidaCotizacion(obj, 'wrapper_ItemsCotizacion', true);

	//Revisamos si hay detalle
	if($(item).closest('.itemCotizacion').find(".wrapperDetalleArticulos").children(".itemCotizacionDetalle").length > 0){

		//Habilitamos los valores del detalle para que no colisione con los atributos de la partida
		$('#'+itemID+' .wrapperDetalleArticulos').find('.formElement').removeAttr('data-inactive');
		
		//Recorremos detalle para cargarlo a la partida
		$(item).closest('.itemCotizacion').find(".itemCotizacionDetalle").each(function( index ) {
			
			var resultsDetalle = getParametersArray($(this).attr('id'), false);
			var objPartidaDetalle = $(this).data('data-obj');
			objPartidaDetalle.loadJSON(JSON.stringify(paramsArray_ToJson(resultsDetalle[1])))
	
			add_PartidaDetalleCotizacion(objPartidaDetalle, newPartidaID+" .wrapperDetalleArticulos", true);  		
		});
	}
}

function cloneDetalleCotizacion(item){

	//Se obtiene el id de la partida padre
	let partidaID = $(item).closest('.itemCotizacion').attr('id');

	//Se obtiene el id del detalle
	let detalleID = $(item).closest('.itemCotizacionDetalle').attr('id');

	let resultsDetalle = getParametersArray(detalleID, false);

	//Se obtiene el objeto del detalle
	let objDetalle = $(item).closest('.itemCotizacionDetalle').data('data-obj');

	//Cargamos valores
	objDetalle.loadJSON(JSON.stringify(paramsArray_ToJson(resultsDetalle[1])));
	
	// agregamos detalle
	add_PartidaDetalleCotizacion(objDetalle, partidaID+" .wrapperDetalleArticulos", true);
}

async function confirmBoxRechazarCotizacion(title){

	//Limpiamos
	$("#cotEstatus").val("").trigger("change");

	return new Promise(resolve => {

		$("#confirmBoxRechazarCotizacion .btn-primary").on("click", function(){

			var intEstatusCot = $("#cotEstatus").val();

			$("#confirmBoxRechazarCotizacion").modal('hide');

			resolve(intEstatusCot);		
		});
		$("#confirmBoxRechazarCotizacion .btn-secondary").on("click", function(){
			$("#confirmBoxRechazarCotizacion").modal('hide');
			resolve(false);		
		});

		$("#confirmBoxRechazarCotizacion .modal-title").html(title);

		let myModal = new bootstrap.Modal(document.getElementById('confirmBoxRechazarCotizacion'), {keyboard: false, backdrop: 'static'});
		myModal.show();
	});

}

async function edit_tipoDoc_Cot(item){

	// inicializamos
	let obj = $(item).closest('.itemRow').data("data-obj");

	let table = 'tbltipos_documento_cot';
	let keyName = 'intTipoDocumentoCot';
	let displayName = 'strNombre';
	let dataName = 'strNombre';
	let title = 'Selecciona el tipo de documento';
	let condition = 'bytEstatus <> 9 AND intTipoDocumentoCot > 0';
	let defaultValue = obj.strNombre;

	selectBox_Custom(table, keyName, displayName, dataName, title, condition, defaultValue, function(value, data){

		$(item).closest('.itemRow').find(".tipoDocCot").text(data);

		obj.intTipoDocumentoCot = value;
		obj.strNombre = data;

	});
}

async function edit_articuloDetalle_Cotizacion(item){

	// inicializamos
	let obj = $(item).closest('.itemRow').data("data-obj");
	let inputArticulo = $(item).closest('.itemRow').find(".vcdArticulo");

	//Se cambia el valor en el label
	$("#selectBoxArticuloCot .btn-primary").on("click", function(){

		$("#selectBoxArticuloCot").modal('hide');

		$(item).closest('.itemRow').find(".articulo-content").html($("#SelectOptionsArticuloCot").select2('data')[0].text+'<div class="itemField_Icon" onclick="edit_articuloDetalle_Cotizacion(this)" style="width: 20px; display: inline-block; margin-left: 5px;"><i class="icofont icofont-pencil-alt-2" style="color:#7c7c7c"></i></div>');
		inputArticulo.val($("#SelectOptionsArticuloCot").val());

		obj.intArticulo = $("#SelectOptionsArticuloCot").val();
		obj.strArticulo = $("#SelectOptionsArticuloCot").select2('data')[0].text;

		$('#selectBoxArticuloCot .btn-primary').off('click');
	});

	//Se cierra el modal
	$("#selectBoxArticuloCot .btn-secondary").on("click", function(){
		$("#selectBoxArticuloCot").modal('hide');

		$('#selectBoxArticuloCot .btn-primary').off('click');
	});

	//Se actualiza el titulo del modal
	$("#selectBoxArticuloCot .formLabel").html("Editar articulo");

	//Se muesta el modal
	let myModal = new bootstrap.Modal(document.getElementById('selectBoxArticuloCot'), {keyboard: false, backdrop: 'static', focus: true});
	myModal.show();
}

function moveItem(id, bolIsUp)
{
	
	if (bolIsUp){
		
		// checamos si NO hay partida anterior
		if ($('#' + id).prev().length == 0) return;
		
		// obtenemos ID partida anterior
		prevID = $('#' + id).prev().attr("id");

		//Obtenemos objeto de partida anterior
		prevObjPartida = $('#' + id).prev().data('data-obj');
		prevSecuenciaPartida = prevObjPartida.bytSecuencia;

		// obtenemos partida
		objPartida = $("#" + id);

		//Obtenemos obj
		currObjPartida = $("#" + id).data('data-obj');
		currSecuenciaPartida = currObjPartida.bytSecuencia;

		// hacemos detach (remueve el objeto pero preserva los eventos)
		objPartida.hide().detach().fadeIn();
		
		// lo movemos
		objPartida.insertBefore('#' + prevID);		

		//Alternamos numeros de partida para la base de datos
		currObjPartida.bytSecuencia = prevSecuenciaPartida;
		prevObjPartida.bytSecuencia = currSecuenciaPartida;
	}
	else{
		
		// checamos si NO hay partida siguiente
		if ($('#' + id).next().length == 0) return;
		
		// obtenemos ID partida siguiente
		nextID = $('#' + id).next().attr("id");

		//Obtenemos objeto de partida siguiente
		nextObjPartida = $('#' + id).next().data('data-obj');
		nextSecuenciaPartida = nextObjPartida.bytSecuencia;

		// obtenemos partida
		objPartida = $("#" + id);

		//Obtenemos obj
		currObjPartida = $("#" + id).data('data-obj');
		currSecuenciaPartida = currObjPartida.bytSecuencia;

		// hacemos detach (remueve el objeto pero preserva los eventos)
		objPartida.hide().detach().fadeIn();
		
		// lo movemos		
		objPartida.insertAfter('#' + nextID);

		//Alternamos numeros de partida para la base de datos
		currObjPartida.bytSecuencia = nextSecuenciaPartida;
		nextObjPartida.bytSecuencia = currSecuenciaPartida;
	}
	
	// posicionamos
	scrollTo_Mobile(id);
}

function switchLock(item, secondItem){
	
	let partidaID = $(item).closest('.itemCotizacion').attr('id');

	//REVISAMOS SI TIENE EL CANDADO
	if($(item).hasClass("icon-lock-closed")){

		//Removemos el candado y habilitamos el input
		$(item).removeClass("icon-lock-closed").addClass("icon-lock-open");
		$(item).closest(".formElement").find("input").prop('disabled', false);

		//Ponemos el candado al input enlazado con el primer input recibido y lo inhabilitamos
		$("."+secondItem).removeClass("icon-lock-open").addClass("icon-lock-closed");
		$("."+secondItem).closest(".formElement").find("input").prop('disabled', true);

	}else{

		//Si no tiene el candado, candadeamos el input
		$(item).removeClass("icon-lock-open").addClass("icon-lock-closed");
		$(item).closest(".formElement").find("input").prop('disabled', true);

		//Removemos el candado al input enlazado con el primer input recibido
		$("."+secondItem).removeClass("icon-lock-closed").addClass("icon-lock-open");
		$("."+secondItem).closest(".formElement").find("input").prop('disabled', false);
	}

	//RECALCULAMOS
	recalculaPartida(partidaID);
	recalculaPrecioUnitarioPartida(partidaID);
	recalculaPrecioUnitarioUtilidadPartida(partidaID);
	recalculaPtjeUtilidadPartida(partidaID);
	recalculaUtilidadPartida(partidaID);
}

function checkImagePartida(item){

	// inicializamos
	let obj = $(item).closest('.itemImagePartida').data("data-obj");

	//Revisamos si existe alguna partida marcada para preview (porque solo puede haber una marcada) y la removemos (excluimos el item que vamos a togglear)
	if($(item).closest('.container-image-partida').find('.icon-check-green').not($(item).find(".icon-toggle-check"))){

		//Regresamos el objeto que encontramos marcado para preview a tipo 1 (porque solo puede haber una imagen marcada)
		let oldObj = $(item).closest('.container-image-partida').find('.icon-check-green').not($(item).find(".icon-toggle-check")).closest(".itemImagePartida").data('data-obj');

		//Actualizamos objeto de la imagen que encontramos que ya estaba marcada
		oldObj.intTipoDocumentoCot = 1;

		//Actualizamos en base de datos
		editDocPartidaCotizacion(oldObj);

		//Removemos la clase del check de la imagen encontrada
		$(item).closest('.container-image-partida').find('.icon-check-green').not($(item).find(".icon-toggle-check")).removeClass('icon-check-green').addClass('icon-check-sofi');

	}
	

	//Revisamos si esta marcada para el apartado de imagenes en PDF
	if($(item).closest('.itemImagePartida').find('.icon-toggle-star').hasClass('icon-star-gold')){
		$(item).closest('.itemImagePartida').find('.icon-toggle-star').removeClass('icon-star-gold').addClass('icon-star-sofi');
	}

	//Revisamos si no esta marcada para mostrar imagen de partida en PDF
	if($(item).find(".icon-toggle-check").hasClass('icon-check-sofi')){

		$(item).find(".icon-toggle-check").removeClass('icon-check-sofi').addClass('icon-check-green');
		obj.intTipoDocumentoCot = 3;
		
	}else{
		$(item).find(".icon-toggle-check").removeClass('icon-check-green').addClass('icon-check-sofi');
		obj.intTipoDocumentoCot = 1;
	}

	//Actualizamos en base de datos
	editDocPartidaCotizacion(obj);
}

function checkFavoriteImagePartida(item){

	// inicializamos
	let obj = $(item).closest('.itemImagePartida').data("data-obj");

	//Revisamos si esta marcada para mostrarla como preview en partida (porque solo puede ser estrella o check)
	$(item).closest('.itemImagePartida').find('.icon-toggle-check').removeClass('icon-check-green').addClass('icon-check-sofi');

	//Revisamos si no esta marcada como favorita
	if($(item).find(".icon-toggle-star").hasClass('icon-star-sofi')){
		$(item).find(".icon-toggle-star").removeClass('icon-star-sofi').addClass('icon-star-gold');
		obj.intTipoDocumentoCot = 4;

	}else{
		$(item).find(".icon-toggle-star").removeClass('icon-star-gold').addClass('icon-star-sofi');
		obj.intTipoDocumentoCot = 1;
	}

	//Actualizamos en base de datos
	editDocPartidaCotizacion(obj);
}

// async function addComentsDetallePartida_Cotizacion(item){

// 	// inicializamos
// 	let obj = $(item).closest('.itemCotizacionDetalle').data("data-obj");

// 	// obtenemos nueva descripción
// 	strObservaciones = await textAreaBox_Sync("Comentarios:", obj.strObservaciones);
// 	if (!strObservaciones) return;

// 	obj.strObservaciones = strObservaciones;

// 	//Revisamos si cargaron observaciones para cambiar el icono
// 	if(obj.strObservaciones.length > 0){
// 		$(item).removeClass('icon-message-empty').addClass('icon-message-filled');
// 	} else{
// 		$(item).removeClass('icon-message-filled').addClass('icon-message-empty');
// 	}
	
// }

async function addComentsImagePartida_Cotizacion(item){

	// inicializamos
	let obj = $(item).closest('.itemImagePartida').data("data-obj");

	// obtenemos nueva descripción
	strNotas = await textAreaBox_Sync("Comentarios:", obj.strNotas);
	if (!strNotas) return;

	obj.strNotas = strNotas;

	//Revisamos si cargaron observaciones para cambiar el icono
	if(obj.strNotas.length > 0){
		$(item).find('.icon-message-toggle').removeClass('icon-message-empty').addClass('icon-message-filled');
	} else{
		$(item).find('.icon-message-toggle').removeClass('icon-message-filled').addClass('icon-message-empty');
	}

	//Actualizamos en base de datos
	editDocPartidaCotizacion(obj);
}

async function edit_docNamePartida(item){
	
	// inicializamos
	let obj = $(item).closest('.itemDocPartida').data("data-obj");
	
	// obtenemos nueva descripción
	strNombreDoc = await textAreaBox_Sync("Nombre documento:", obj.strNombreDoc);
	if (!strNombreDoc) return;

	obj.strNombreDoc = strNombreDoc;

	$(item).closest('.itemDocPartida').find(".docName").html(strNombreDoc);

	//Actualizamos en base de datos
	editDocPartidaCotizacion(obj);
}

function editDocPartidaCotizacion(objDocument){

	if(objDocument){

		fetch_Post("../controllers/ventas/ventas_cotizaciones_controller",{
			action: 'editDocPartidaCotizacion',
			json: JSON.stringify(objDocument),
		}).then(function (objResponse){
	
			if(objResponse != false){
				hideLoading().sleep(200).then(() =>{});
			}
		});
	}
}

async function deleteDocPartidaCotizacion(item){

	//Se obtiene el objeto del documento
	let objDocument = $(item).closest(".itemDocPartida").data('data-obj');

	if(objDocument){

		if (! await confirmBox("Aviso", "¿Está seguro que desea eliminar el documento?")) return;

		fetch_Post("../controllers/ventas/ventas_cotizaciones_controller",{
			action: 'deleteDocCotizacion',
			json: JSON.stringify(objDocument),
		}).then(function (objResponse){
	
			if(objResponse != false){
				hideLoading().sleep(200).then(() =>{

					//Obtenemos la tab de documentos
					let tabDocs = $(item).closest('.tab-pane-partida-docs');

					// eliminamos con animación
					$(item).closest(".itemDocPartida").animate({height: 'toggle'}, 200, function() {
						$(this).remove();
						checkLengthContainerDocsPartida(tabDocs);
					});

					//Recorremos el array para limpiar el array de documentos que sean de tipo imagen
					$.each(currCotizacion.partidas, function( key, partida ) {
						$.each(partida.docs, function( keyDoc, doc ) {
						
							if(doc == undefined) return;
							if(doc.strNombreDoc == objDocument.strNombreDoc){
					
								partida.docs.splice(keyDoc, 1);
							}
							
						});
					});
				});
			}
		});
	}
}

function checkLengthContainerDocsPartida(tabDocs){
	
	//Obtenemos los documentos
	let docs = $(tabDocs).find('.itemDocPartida');

	//Si ya no hay documentos
	if($(docs).length == 0){
		
		//Mostramos label de sin documentos cargados
		$(tabDocs).find('.text-content-empty').show();

		//Ocultamos contenedores de docs
		$(tabDocs).find(".container-image-partida").hide();
		$(tabDocs).find(".docstext").hide();
		$(tabDocs).find(".container-docs-partida").hide();
	} 
}

async function bitacoraBox_CP_Sync(intCotizacion, bytRevision, intPartida, intTipoRegistro, title = "") {
	return new Promise(async(resolve, reject) => {
		if(intCotizacion === undefined){
			console.log('falta intCotizacion en la funcion bitacoraBox_CP()'); 
			resolve(false);
			return;
		} 

		if(intTipoRegistro === undefined){
			console.log('falta intTipoRegistro en la funcion bitacoraBox_CP()');
			resolve(false);
			return;
		}

		strComentarios = await textAreaBox_Sync((title !== "" ? title : "Bitacora:"));
		
		if(!strComentarios) {
			resolve(false);
			return;
		};

		if(!await additemPartidaBitacora_CP(intCotizacion, bytRevision, intPartida, intTipoRegistro, strComentarios)) {
			resolve(false);
			return;
		}

		resolve(true);

	});
}

async function additemPartidaBitacora_CP(intCotizacion, bytRevision, intPartida, intTipoRegistro, strComentarios = "") {
	return new Promise(resolve => {

		showLoading();

		fetch_Post("../controllers/ventas/ventas_cotizaciones_controller",{
			p1: intCotizacion,
			p2: bytRevision,
			p3: intPartida,
			p4: intTipoRegistro,
			p5: strComentarios,
			action: 'addItemPartida_Bitacora'
		}).then(function (objResponse){
			if(objResponse.success == 1){
				hideLoading().sleep(200).then(() =>{
					resolve(true);
				});
			} else {
				hideLoading();
				console.log(objResponse.msg);
				resolve(false);
				return;
			}
		});

	});
}

function showBitacora_CP_Modal(item, intTipoRegistro = 0) {

	//Se obtiene el objeto
	let obj = $(item).closest(".itemCotizacion").data('data-obj');

	if(obj.intCotizacion == 0){
		msgBox_Warning("", "Debe guardar la partida para poder visualizar la bitacora.");
		return;
	}

	$('#bitacoraCPBox .add-registro').show();

	// actualizamos header
	$('#bitacoraCPBox .modal-title').text("Bitácora partida " + obj.intPartida);

	// Si el footer tiene la clase de justificar al final, lo removemos
	if($('#bitacoraCPBox .modal-footer').hasClass('justify-content-end')) $('#bitacoraCPBox .modal-footer').removeClass('justify-content-end'); 

	// Si el footer no tiene la clase de justificar around, se lo agregamos
	if(!$('#bitacoraCPBox .modal-footer').hasClass('justify-content-between')) $('#bitacoraCPBox .modal-footer').addClass('justify-content-between'); 

	// refrescamos info del modal de bitacora op
	refreshBitacora_CP_Modal(obj.intCotizacion, obj.bytRevision, obj.intPartida);
	
	// Abrimos el modal
	$('#bitacoraCPBox').modal('show');

	$('#bitacoraCPBox .add-registro').off('click').click(async function() {
		// Si no hay un tipo de registro ingresado en la funcion, debe retornar
		if(intTipoRegistro == 0){
			console.log('Inserte un tipo de registro en la función');
			return;
		} 

		if(await bitacoraBox_CP_Sync(obj.intCotizacion, obj.bytRevision, obj.intPartida, intTipoRegistro, "Nuevo registro:")){
			// volvemos a cargar el modal
			refreshBitacora_CP_Modal(obj.intCotizacion, obj.bytRevision, obj.intPartida);
		}
	});

}

function refreshBitacora_CP_Modal(intCotizacion, bytRevision, intPartida) {
	
	// limpiamos wrapper y hacemos reset a la altura del modal
	$('#bitacoraCPBox #wrapperBitacoraCP').html('');
	$("#bitacoraCPBox .modal-content").css('height', '60vh'); 

	// Si el wrapper tiene la clase de text-center se la quitamos
	if($("#bitacoraCPBox #wrapperBitacoraCP").hasClass('text-center')) $("#bitacoraCPBox #wrapperBitacoraCP").removeClass('text-center');

	fetch_Post("../controllers/ventas/ventas_cotizaciones_controller",{
		action: 'getBitacoraCotizacion_Partida',
		p1: intCotizacion,
		p2: bytRevision,
		p3: intPartida
	}).then(function (objResponse){
		if(objResponse.success == 1){
			//mostramos loadin
			show_UpdateTag('bitacoraCPBox');

			setTimeout(function(){hide_UpdateTag('bitacoraCPBox')}, 1000);

			// Checamos que los haya resultados
			if(objResponse.results.length > 0) {

				objResponse.results.forEach(function(bitacora) { 

					// agregamos items de la bitacora
					add_ItemBitacora_Partida_Cotizacion(bitacora, 'wrapperBitacoraCP');
				});
			} else {
				// Si no hay resultados mostramos la sig leyenda y le damos estilo
				$("#bitacoraCPBox .modal-content").css('height', 'auto'); //60vh
				$("#bitacoraCPBox #wrapperBitacoraCP").addClass('text-center');
				$("#bitacoraCPBox #wrapperBitacoraCP").text('No hay información disponible');
			}
				
		}
	});

}

function add_ItemBitacora_Partida_Cotizacion(bitacoraItem, wrapperID){

	// preparamos ID
	wrapperID = "#" + wrapperID;

	// inicializamos
	let strIDToClone = '';

	// determinamos el tipo de registro (Autorización o Liberada)
	if(bitacoraItem.intTipoRegistro == '14' || bitacoraItem.intTipoRegistro == '15') strIDToClone = '#itemBitacoraCP02';		
	else strIDToClone = '#itemBitacoraCP01';

	// clonamos
	let newBitacora = $(strIDToClone).clone();

	// incrementamos contador
	count_ItemsBitacora_CP ++;

	// cambiamos el ID
	newBitacora.attr("id", "itemBitacora" + count_ItemsBitacora_CP);

	// obtenemos nueva ID
	newBitacoraID = "#" + newBitacora.attr("id");

	// mostramos
	$(wrapperID).show();
		
	// agregamos item
	newBitacora.appendTo(wrapperID).show();
		
	// icono según tipo de registro
	switch(Number(bitacoraItem.intTipoRegistro)){

		case 14: //Autorización
			$(newBitacoraID + ' .icon').addClass('color2');
			$(newBitacoraID + ' .icon i').addClass('fa fa-flag');
			$(newBitacoraID + ' .action').html(bitacoraItem.strUsuarioAlta);
			$(newBitacoraID + ' .header .title').html(' marcó esta cotización como Autorizada');
			$(newBitacoraID + ' .header .time').attr('title', bitacoraItem.strFechaAlta);
			break;

		case 15: //Liberada
			$(newBitacoraID + ' .icon').addClass('color2');
			$(newBitacoraID + ' .icon i').addClass('fa fa-paper-plane');
			$(newBitacoraID + ' .action').html(bitacoraItem.strUsuarioAlta);
			$(newBitacoraID + ' .header .title').html(' marcó esta cotización como Liberada');
			$(newBitacoraID + ' .header .time').attr('title', bitacoraItem.strFechaAlta);
			break;

		default:
			$(newBitacoraID + ' .icon').addClass('color1');
			$(newBitacoraID + ' .icon i').addClass('icofont icofont-pencil-alt-2');
			$(newBitacoraID + ' .action').html(bitacoraItem.strTipoRegistro);
			break;
	}
	// se remplazan los caracteres de la base de datos para cambiarlos por etiquetas html
	try {
		strCambios = generarBitacoraItem(bitacoraItem);
	}
	catch (error) {
		strCambios = bitacoraItem.strDescripcion;
	}
	// cargamos labels	
	$(newBitacoraID + ' .time').html('Hace '+ bitacoraItem.strTiempoTranscurrido);
	$(newBitacoraID + ' .comments').html(strCambios);
	$(newBitacoraID + ' .user').html(bitacoraItem.strUsuarioAlta);
	$(newBitacoraID + ' .date').html(bitacoraItem.strFechaAlta);
	
	return true;
}

async function bitacoraBox_CPD_Sync(intCotizacion, bytRevision, intPartida, intDetalle, intTipoRegistro, title = "") {
	return new Promise(async(resolve, reject) => {
		if(intCotizacion === undefined){
			console.log('falta intCotizacion en la funcion bitacoraBox_CP()'); 
			resolve(false);
			return;
		} 

		if(intTipoRegistro === undefined){
			console.log('falta intTipoRegistro en la funcion bitacoraBox_CP()');
			resolve(false);
			return;
		}

		strComentarios = await textAreaBox_Sync((title !== "" ? title : "Bitacora:"));
		
		if(!strComentarios) {
			resolve(false);
			return;
		};

		if(!await additemPartidaBitacora_CPD(intCotizacion, bytRevision, intPartida, intDetalle, intTipoRegistro, strComentarios)) {
			resolve(false);
			return;
		}

		resolve(true);

	});
}

async function additemPartidaBitacora_CPD(intCotizacion, bytRevision, intPartida, intDetalle, intTipoRegistro, strComentarios = "") {
	return new Promise(resolve => {

		showLoading();

		fetch_Post("../controllers/ventas/ventas_cotizaciones_controller",{
			p1: intCotizacion,
			p2: bytRevision,
			p3: intPartida,
			p4: intDetalle,
			p5: intTipoRegistro,
			p6: strComentarios,
			action: 'addItemPartidaDetalle_Bitacora'
		}).then(function (objResponse){
			if(objResponse.success == 1){
				hideLoading().sleep(200).then(() =>{
					resolve(true);
				});
			} else {
				hideLoading();
				console.log(objResponse.msg);
				resolve(false);
				return;
			}
		});

	});
}

function showBitacora_CPD_Modal(item, intTipoRegistro = 0, intTipoRegistroConsulta = 0, title = '') {

	//Se obtiene el objeto
	let obj = $(item).closest(".itemCotizacionDetalle").data('data-obj');
	let row = $(item).closest(".itemCotizacionDetalle");

	if(obj.intCotizacion == 0){
		msgBox_Warning("", "Debe guardar la partida para poder visualizar la bitácora.");
		return;
	}

	//Ocultamos botón de conflicto
	$('#bitacoraCPDBox .toggle-conflicto').hide();

	$('#bitacoraCPDBox .add-registro').show();

	//Si el tipo de registro es 9 (conflicto) mostramos botón para remover conflicto
	if(intTipoRegistroConsulta == 9){
		$('#bitacoraCPDBox .toggle-conflicto').show();

		$('#bitacoraCPDBox .toggle-conflicto').off('click').click(function() {
			
			//Función para remover conflicto
			setConflictoPartidaDetalle_Cotizacion(obj, row);

			// cerramos el modal
			$('#bitacoraCPDBox').modal('hide');

		});
	} 

	// actualizamos header
	$('#bitacoraCPDBox .modal-title').text("Bitácora "+ title + ' ' + obj.intDetalle);

	// Si el footer tiene la clase de justificar al final, lo removemos
	if($('#bitacoraCPDBox .modal-footer').hasClass('justify-content-end')) $('#bitacoraCPDBox .modal-footer').removeClass('justify-content-end'); 

	// Si el footer no tiene la clase de justificar around, se lo agregamos
	if(!$('#bitacoraCPDBox .modal-footer').hasClass('justify-content-between')) $('#bitacoraCPDBox .modal-footer').addClass('justify-content-between'); 

	// refrescamos info del modal de bitacora 
	refreshBitacora_CPD_Modal(obj.intCotizacion, obj.bytRevision, obj.intPartida, obj.intDetalle, intTipoRegistroConsulta);
	
	// Abrimos el modal
	$('#bitacoraCPDBox').modal('show');

	$('#bitacoraCPDBox .add-registro').off('click').click(async function() {
		// Si no hay un tipo de registro ingresado en la funcion, debe retornar
		if(intTipoRegistro == 0){
			console.log('Inserte un tipo de registro en la función');
			return;
		} 

		if(await bitacoraBox_CPD_Sync(obj.intCotizacion, obj.bytRevision, obj.intPartida, obj.intDetalle, intTipoRegistro, "Nuevo registro:")){
			// volvemos a cargar el modal
			refreshBitacora_CPD_Modal(obj.intCotizacion, obj.bytRevision, obj.intPartida, obj.intDetalle, intTipoRegistroConsulta);
		}
	});

}

function refreshBitacora_CPD_Modal(intCotizacion, bytRevision, intPartida, intDetalle, intTipoRegistroConsulta) {
	
	// limpiamos wrapper y hacemos reset a la altura del modal
	$('#bitacoraCPDBox #wrapperBitacoraCPD').html('');
	$("#bitacoraCPDBox .modal-content").css('height', '60vh'); 

	// Si el wrapper tiene la clase de text-center se la quitamos
	if($("#bitacoraCPDBox #wrapperBitacoraCPD").hasClass('text-center')) $("#bitacoraCPDBox #wrapperBitacoraCPD").removeClass('text-center');

	fetch_Post("../controllers/ventas/ventas_cotizaciones_controller",{
		action: 'getBitacoraCotizacion_PartidaDetalle',
		p1: intCotizacion,
		p2: bytRevision,
		p3: intPartida,
		p4: intDetalle,
		p5: intTipoRegistroConsulta
	}).then(function (objResponse){
		if(objResponse.success == 1){
			//mostramos loadin
			show_UpdateTag('bitacoraCPDBox');

			setTimeout(function(){hide_UpdateTag('bitacoraCPDBox')}, 1000);

			// Checamos que los haya resultados
			if(objResponse.results.length > 0) {

				objResponse.results.forEach(function(bitacora) { 

					// agregamos items de la bitacora
					add_ItemBitacora_Partida_Detalle_Cotizacion(bitacora, 'wrapperBitacoraCPD');
				});
			} else {
				// Si no hay resultados mostramos la sig leyenda y le damos estilo
				$("#bitacoraCPDBox .modal-content").css('height', 'auto'); //60vh
				$("#bitacoraCPDBox #wrapperBitacoraCPD").addClass('text-center');
				$("#bitacoraCPDBox #wrapperBitacoraCPD").text('No hay información disponible');
			}
				
		}
	});

}

function add_ItemBitacora_Partida_Detalle_Cotizacion(bitacoraItem, wrapperID){

	// preparamos ID
	wrapperID = "#" + wrapperID;

	// inicializamos
	let strIDToClone = '';

	// determinamos el tipo de registro (Autorización o Liberada)
	if(bitacoraItem.intTipoRegistro == '14' || bitacoraItem.intTipoRegistro == '15') strIDToClone = '#itemBitacoraCPD02';		
	else strIDToClone = '#itemBitacoraCPD01';

	// clonamos
	let newBitacora = $(strIDToClone).clone();

	// incrementamos contador
	count_ItemsBitacora_CP ++;

	// cambiamos el ID
	newBitacora.attr("id", "itemBitacora" + count_ItemsBitacora_CP);

	// obtenemos nueva ID
	newBitacoraID = "#" + newBitacora.attr("id");

	// mostramos
	$(wrapperID).show();
		
	// agregamos item
	newBitacora.appendTo(wrapperID).show();
		
	// icono según tipo de registro
	switch(Number(bitacoraItem.intTipoRegistro)){

		case 14: //Autorización
			$(newBitacoraID + ' .icon').addClass('color2');
			$(newBitacoraID + ' .icon i').addClass('fa fa-flag');
			$(newBitacoraID + ' .action').html(bitacoraItem.strUsuarioAlta);
			$(newBitacoraID + ' .header .title').html(' marcó esta cotización como Autorizada');
			$(newBitacoraID + ' .header .time').attr('title', bitacoraItem.strFechaAlta);
			break;

		case 15: //Liberada
			$(newBitacoraID + ' .icon').addClass('color2');
			$(newBitacoraID + ' .icon i').addClass('fa fa-paper-plane');
			$(newBitacoraID + ' .action').html(bitacoraItem.strUsuarioAlta);
			$(newBitacoraID + ' .header .title').html(' marcó esta cotización como Liberada');
			$(newBitacoraID + ' .header .time').attr('title', bitacoraItem.strFechaAlta);
			break;

		default:
			$(newBitacoraID + ' .icon').addClass('color1');
			$(newBitacoraID + ' .icon i').addClass('icofont icofont-pencil-alt-2');
			$(newBitacoraID + ' .action').html(bitacoraItem.strTipoRegistro);
			break;
	}
	// se remplazan los caracteres de la base de datos para cambiarlos por etiquetas html
	try {
		strCambios = generarBitacoraItem(bitacoraItem);
	}
	catch (error) {
		strCambios = bitacoraItem.strDescripcion;
	}
	// cargamos labels	
	$(newBitacoraID + ' .time').html('Hace '+ bitacoraItem.strTiempoTranscurrido);
	$(newBitacoraID + ' .comments').html(strCambios);
	$(newBitacoraID + ' .user').html(bitacoraItem.strUsuarioAlta);
	$(newBitacoraID + ' .date').html(bitacoraItem.strFechaAlta);
	
	return true;
}

async function view_BitacoraConflictoPartidaDetalle_Cotizacion(item){

	//Se obtiene el objeto
	let obj = $(item).closest(".itemCotizacionDetalle").data('data-obj');
	let row = $(item).closest(".itemCotizacionDetalle");

	if(obj.intCotizacion == 0){
		msgBox_Warning("", "Debe guardar la partida para poder visualizar la bitácora.");
		return;
	}

	//Revisamos si ya tiene conflicto
	if(obj.bytConflicto == 1){
		showBitacora_CPD_Modal(item, 9, 9, 'conflictos articulo');
	}else{
		
		if(await bitacoraBox_CPD_Sync(obj.intCotizacion, obj.bytRevision, obj.intPartida, obj.intDetalle, 9, "Motivo de conflicto:")){
			
			// volvemos a cargar el modal
			refreshBitacora_CPD_Modal(obj.intCotizacion, obj.bytRevision, obj.intPartida, obj.intDetalle, 9);

			//Prendemos conflicto de partida
			setConflictoPartidaDetalle_Cotizacion(obj, row);
		}
	}
	
}

function setConflictoPartidaDetalle_Cotizacion(obj, item){

	if(obj){

		//Toggleamos el valor
		if(obj.bytConflicto == 1){

			//Removemos marca
			$(item).find(".alert-icon").removeClass('alert-icon').addClass('alert-icon-lines');
			$(item).find(".alert-icon-lines").css('opacity', '0.5');

			$(item).css('background-color', '');

			//Actualizamos valor apagando el conflicto
			obj.bytConflicto = 0;
		} else{

			//establecemos marca de conflicto
			$(item).find(".alert-icon-lines").removeClass('alert-icon-lines').addClass('alert-icon');
			$(item).find(".alert-icon").css('opacity', '1');
			$(item).css('background-color', '#FFF3CD');

			//Actualizamos valor prendiendo conflicto
			obj.bytConflicto = 1;
		} 

		showLoading();

		fetch_Post("../controllers/ventas/ventas_cotizaciones_controller",{
			action: 'setConflictoPartidaDetalle',
			p1: obj.intCotizacion,
			p2: obj.bytRevision,
			p3: obj.intPartida,
			p4: obj.intDetalle,
			p5: obj.bytConflicto
		}).then(function (objResponse){
	
			if(objResponse != false){
				hideLoading().sleep(200).then(() =>{});
			}
		});
	}
}

function detallePartida_setGrabbers(){

	$(".wrapperDetalleArticulos .itemCotizacionDetalle .grabber").removeClass("up").removeClass("down");
	if ($(".wrapperDetalleArticulos .itemCotizacionDetalle:first-child").length > 0) $(".wrapperDetalleArticulos .itemCotizacionDetalle:first-child .grabber").addClass("down");
	if ($(".wrapperDetalleArticulos .itemCotizacionDetalle:last-child").length > 0) $(".wrapperDetalleArticulos .itemCotizacionDetalle:last-child .grabber").addClass("up");

}
