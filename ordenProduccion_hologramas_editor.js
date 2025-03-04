var currInfoHoloOrdenProduccion = new ordenProduccion_InfoHologramas;

$(document).ready(function(){
	togglesAdicionalesHolo();

	//funcion que se activa al presionar el boton de + que se ubica alado del select Forma
	$('#ordenProduccion_hologramas_editor .btn-add-forma').click(function() {
		//limpiamos select de cliente del modal agregar formato
		$('#intClienteFormatoAgregar').val('').trigger('change');

		//invocamos el modal para agregar formato 
		accionVentasCatFormatosC(1, {});

		//obtenemos el valor del select de cliente
		let cliente = $('#ophgCliente').val();
		
		//Checamos que el select de Cliente tenga algun valo
		if(cliente !== null) {
			//obtenemos objeto con la data del select de cliente
			let optCliente = $('#ophgCliente').select2('data')[0];
			optCliente.id = parseInt(optCliente.id);
			//seteamos el select del modal de agregar formato
			setOption_AjaxSelect('intClienteFormatoAgregar', optCliente)
		}
	});
});

function showHologramasOPEditor(){
	
	//Limpiamos
	limpiarHologramasOP();

	// mostramos
	showContent('ordenProduccion_hologramas_editor');
}

function showEditHologramasOPEditor(){

	$("#headerOP h5").text(currOrdenProduccion.strOrdenProd);
	$("#headerOP .card-subheader").text(" Estatus:  " + currOrdenProduccion.strEstatusOP);

	//Inicializamos selects
	initSelectsHologramasOP();

	//Seteamos valores en tabs generales
	setValuesParameters("pnlInfohologramasOP", currOrdenProduccion);
	setValuesParameters("tabHoloOPGeneral", currOrdenProduccion);
	setValuesParameters("tabHoloOPGeneral", currInfoHoloOrdenProduccion);

	//Limpiamos materiales
	$("#tabHoloOPMateriales .formElement").each(function(index){
		// checamos si tiene el atributo definido
		if ($(this).attr("data-ControlID"))
		{
			var strID = "#" + $(this).attr('data-ControlID');
			$(strID).val("").trigger("change");
		}
		
	});
	
	//Seteamos valores en tab materiales despues de haber limpiado la tab
	setValuesParameters("tabHoloOPMateriales", currInfoHoloOrdenProduccion.materiales[0]);

	//Seteamos valores con los dos objetos en la pestaña acabados
	setValuesParameters("tabHoloOPAcabado", currInfoHoloOrdenProduccion);
	setValuesParameters("tabHoloOPAcabado", currOrdenProduccion);
	setValuesParameters("tabHoloOPDiseño", currInfoHoloOrdenProduccion);

	setOption_AjaxSelect("ophgDireccionMaterial", {id: currInfoHoloOrdenProduccion.intDireccionMaterial, text:currInfoHoloOrdenProduccion.strDireccionMaterial});

	//Revisamos si estas cantidades vienen vacias para limpiar los campos y pueda pasar el validate
	(currInfoHoloOrdenProduccion.dblCantPzasPieCuad == 0 ? $("#ophgCantPiezasPieCuad").val("") : $("#ophgCantPiezasPieCuad").val(currInfoHoloOrdenProduccion.dblCantPzasPieCuad));
	(currInfoHoloOrdenProduccion.dblOtraCantidad == 0 ? $("#ophgOtraCantidad").val("") : $("#ophgOtraCantidad").val(currInfoHoloOrdenProduccion.dblOtraCantidad));

	//Botones
	(currOrdenProduccion.intEstatusOP == 1 ? $(".buttonAproveOP").show() : $(".buttonAproveOP").hide());
	(currOrdenProduccion.intEstatusOP == 1 ? $(".buttonRejectOP").hide() : $(".buttonRejectOP").show());
	(currOrdenProduccion.intEstatusOP == 2 ? $(".buttonAuthorizeOP").show() : $(".buttonAuthorizeOP").hide());

	//Valores adicionales
	$("#ophgSalidaCon").val(currInfoHoloOrdenProduccion.strSalidaCon);
	$("#ophInstrucciones").val(currOrdenProduccion.strInstruccionesEsp);
	$(".opNotas").val(currOrdenProduccion.strNotas);
	$(".estatusOPText").text((currOrdenProduccion.intEstatusOP == 3 ? 'Aprobada' : currOrdenProduccion.intEstatusOP == 2 ? 'XAprobar' : ''));
	currOrdenProduccion.strNotas.length > 0 ? $(".pnlNotasOP_Rechazo").show() : $(".pnlNotasOP_Rechazo").hide();

	setTimeout(function(){ autosize.update($("#ophInstrucciones")); },400);
	setTimeout(function(){ autosize.update($(".opNotas")); },400);
	setTimeout(function(){ autosize.update($("#ophgDesc")); },400);

	//Deshabilitamos tipo de orden
	$("#ophgTipoProdOP").prop('disabled', true);
	$("#ophProv").prop('disabled', true);

	setOption_AjaxSelect("ophgTipoProdOP", {id: currOrdenProduccion.intTipoProduccion, text:currOrdenProduccion.strTipoProduccion});

	//Revisamos si no viene de una réplica para asignarle el valor de la orden que esta seleccionando
	if(currOrdenProduccion.intOrdenProd != 0){
		setOption_AjaxSelect("ophOperacion", {id: currOrdenProduccion.intOperacionOP, text:currOrdenProduccion.strOperacionOP});
	}

	//Revisamos si es réplica exacta
	if(currOrdenProduccion.intTipoOperacionOP == 2){

		// Agregamos un delay para que no se deshabiliten otros inputs
		setTimeout(function() {				
			
			$("#ordenProduccion_hologramas_editor").find("input, select").prop('disabled', true);

			//Ocultamos
			$("#ordenProduccion_hologramas_editor .itemRow_Icon").hide();
			$("#ordenProduccion_hologramas_editor .itemField_Icon").hide();

			//Prendemos inputs editables
			$("#ophgCantidad").prop("disabled", false);
			$("#ophUMedidaOP").prop("disabled", false);
			$("#ophFechaEntrega").prop("disabled", false);

			//Hablitamos el input de tipo de orden si viene una de réplica
			if(currOrdenProduccion.intOrdenProd == 0) $("#ophgTipoProdOP").prop('disabled', false); else $("#ophgTipoProdOP").prop('disabled', true);
		}, 300);
		

	}else{
		$("#ordenProduccion_hologramas_editor").find("input, select").prop('disabled', false);

		(currOrdenProduccion.infoHologramas.bytAcabadoEtiqueta == 1 ? $("#ophBytAcabadoEtiqueta").prop("checked", true).trigger('change') : $("#ophBytAcabadoEtiqueta").prop("checked", false).trigger('change'));
		(currOrdenProduccion.infoHologramas.bytSuajes == 1 ? $("#ophbytSuajesHolo").prop("checked", true).trigger('change') : $("#ophbytSuajesHolo").prop("checked", false).trigger('change'));
		(currOrdenProduccion.infoHologramas.bytCodigoBarras == 1 ? $("#ophbytCodigoBarras").prop("checked", true).trigger('change') : $("#ophbytCodigoBarras").prop("checked", false).trigger('change'));
		(currOrdenProduccion.bytFolios == 1 ? $("#ophbytFoliosHolo").prop("checked", true).trigger('change') : $("#ophbytFoliosHolo").prop("checked", false).trigger('change'));
		(currOrdenProduccion.infoHologramas.bytPleca == 1 ? $("#ophbytPleca").prop("checked", true).trigger('change') : $("#ophbytPleca").prop("checked", false).trigger('change'));
		(currOrdenProduccion.infoHologramas.bytPlecaHorizontal == 1 ? $("#ophPlecaFolios").val("1").trigger('change') : $("#ophPlecaFolios").val("0").trigger('change'));

		//Hablitamos el input de tipo de orden si viene una de réplica
		if(currOrdenProduccion.intOrdenProd == 0) $("#ophgTipoProdOP").prop('disabled', false); else $("#ophgTipoProdOP").prop('disabled', true);
	}

	//Revisamos si es filial
	if(currOrdenProduccion.intTipoProduccion == 3){

		//Revisamos si encontramos el tipo de proceso filial para setear el select con el proveedor correspondiente
		currOrdenProduccion.secuencia.forEach(function(proceso) {
			if(proceso.intTipoProcesoProd == 5) setOption_AjaxSelect("ophProv", {id: currOrdenProduccion.secuencia[0].intProveedor, text:currOrdenProduccion.secuencia[0].strProveedor});
		});
		
	}

	// mostramos
	showContent('ordenProduccion_hologramas_editor');
}

function limpiarHologramasOP(){

	//Limpiamos objetos
	currOrdenProduccion.clear();
	currInfoHoloOrdenProduccion.clear();

	//Definimos el estatus incial de la orden
	currOrdenProduccion.intEstatusOP = 1;
	currOrdenProduccion.intTipoOrden = 2;

	$("#ordenProduccion_hologramas_editor #headerOP h5").text("Nueva OP");
	$("#ordenProduccion_hologramas_editor .card-subheader").text(getCurrentDate_Long());

	//Ocultamos notas de rechazo
	$(".pnlNotasOP_Rechazo").hide();
	$(".opNotas").val("");
	$("#ophgTipoProdOP").val("").trigger("change");

	//Limpiamos campos
	clearFilters("ordenProduccion_hologramas_editor");
	$("#ophInstrucciones").val("");

	initSelectsHologramasOP();

	//Prendemos inputs
	$("#ordenProduccion_hologramas_editor input, select").each(function(index){
		$(this).prop( "disabled", false );
	});

	//Mostramos
	$(".buttonAproveOP").show();
	$(".buttonAuthorizeOP").show();
	$(".buttonRejectOP").hide();
	
	//Apagamos toggles
	$("#ordenProduccion_hologramas_editor input[type=checkbox]").each(function(index){
		$(this).prop( "checked", false ).trigger("change");
	});

	//Deshabilitamos el input operación
	// setOption_AjaxSelect("ophOperacion", {id: 1, text:'Diseño'});
	// $("#ophOperacion").attr('disabled', 'disabled');
	// setOption_AjaxSelect("ophTipoOperacion", {id: 1, text:'Nueva'});
}

function validaInfoHologramasOrdenProduccion(){

	// validamos info general superior
	var paramsOpHologramasInfo = getParametersArray("pnlInfohologramasOP", true);
		
	// checamos si NO podemos continuar
	if (!paramsOpHologramasInfo[0]){

		// mensaje de error
		msgBox_Warning("Falta información.", 'Por favor capture los campos en rojo.');
		return false;
	}

	currOrdenProduccion.loadJSON(JSON.stringify(paramsArray_ToJson(paramsOpHologramasInfo[1])));

	// validamos primer tab de la info de Hologramas de la orden
	var paramsOpHoloGeneral = getParametersArray("tabHoloOPGeneral", true);
		
	// checamos si NO podemos continuar
	if (!paramsOpHoloGeneral[0]){
		
		// mensaje de error
		msgBox_Warning("Falta información.", 'Por favor capture los campos en rojo en la pestaña "General".');
		return false;
	}

	//Cargamos valores de la primer tab en nuestros objetos
	currOrdenProduccion.loadJSON(JSON.stringify(paramsArray_ToJson(paramsOpHoloGeneral[1])));
	currInfoHoloOrdenProduccion.loadJSON(JSON.stringify(paramsArray_ToJson(paramsOpHoloGeneral[1])));

	//Validamos materiales
	if ($("#ophgMaterialHolo").val() == null){

		// mensaje de error
		msgBox_Warning("Falta información.", 'Por favor seleccione un material en la pestaña "Materiales".');
		return false;
	}

	// validamos tab de materiales
	var paramsOpHoloMateriales = getParametersArray("wrapperMaterialHolo", true);
		
	// checamos si NO podemos continuar
	if (!paramsOpHoloMateriales[0]){

		// mensaje de error
		msgBox_Warning("Falta información.", 'Por favor capture los campos en rojo en la pestaña "Materiales".');
		return false;
	}

	if(currInfoHoloOrdenProduccion.bytAcabadoEtiqueta == 1){
		

		if(currInfoHoloOrdenProduccion.bytSuajes == 1){
			if($("#ophFormaSuaje").val() == null){
				// mensaje de error
				msgBox_Warning("Falta información.", 'Por favor seleccione un suaje.');
				return false;
			}
		}

		if(currOrdenProduccion.bytFolios == 1){

			// validamos tab de materiales
			var paramsOpHoloFolios = getParametersArray("wrapperFoliosHolo", true);
				
			// checamos si NO podemos continuar
			if (!paramsOpHoloFolios[0]){

				// mensaje de error
				msgBox_Warning("Falta información.", 'Por favor capture los campos en rojo en la pestaña "Acabado" en la sección "Folios".');
				return false;
			}
		}

		if(currOrdenProduccion.bytPleca == 1){
			if($("#ophPlecaFolios").val() == null){

				// mensaje de error
				msgBox_Warning("Falta información.", 'Por favor seleccione un tipo de pleca en la pestaña "Acabado".');
				return false;
			}
		}
	}

	return true;
}

function getObjectOrdenProduccionHologramasForDB(){

	var objMaterialHolo = new ordenProduccion_MaterialesHolo;

	var paramsOpHoloMateriales = getParametersArray("tabHoloOPMateriales", false);
	objMaterialHolo.loadJSON(JSON.stringify(paramsArray_ToJson(paramsOpHoloMateriales[1])))

	currOrdenProduccion.strInstruccionesEsp = $("#ophInstrucciones").val();
	currOrdenProduccion.strDescripcion = $("#ophgDesc").val();
	
	
	if(currInfoHoloOrdenProduccion.bytAcabadoEtiqueta == 1){

		// validamos info general superior
		var paramsAcabadoEtiqueta = getParametersArray("tabHoloOPAcabado", false);
		currInfoHoloOrdenProduccion.loadJSON(JSON.stringify(paramsArray_ToJson(paramsAcabadoEtiqueta[1])));
		currOrdenProduccion.loadJSON(JSON.stringify(paramsArray_ToJson(paramsAcabadoEtiqueta[1])));
	} 

	var paramsDiseno = getParametersArray("tabHoloOPDiseño", false);
	currInfoHoloOrdenProduccion.loadJSON(JSON.stringify(paramsArray_ToJson(paramsDiseno[1])));

	currInfoHoloOrdenProduccion.materiales[0] = objMaterialHolo;

	//Asignamos tipo de producción
	currOrdenProduccion.intTipoProduccion = ($("#ophgTipoProdOP").val() == null ? 0 : $("#ophgTipoProdOP").val());

	//Asignamos toda la info de la orden
	currOrdenProduccion.infoHologramas = currInfoHoloOrdenProduccion;

	return currOrdenProduccion;
}

function togglesAdicionalesHolo(){
	
	$("#ophBytAcabadoEtiqueta").change(function () {
		
		//Revisamos si esta checkeado
		if(!$(this).is(":checked")) {
			
			//Desactivamos checks
		   $("#ophbytSuajesHolo").prop( "checked", false );
		   $("#ophbytFoliosHolo").prop( "checked", false );
		   $("#ophbytPleca").prop( "checked", false );

		   //Limpiamos valores
		   clearFilters('wrapperMica');
		   clearFilters('wrapperSticker');

		   $("#wrapperFoliosHolo").find('select,input').not(this).attr('disabled', 'disabled');
		   $("#wrapperSuajesHolo").find('select,input').not(this).attr('disabled', 'disabled');
		   $("#wrapperPlecaHolo").find('select,input').not(this).attr('disabled', 'disabled');
		   $("#ophProductoFinal").attr('disabled', 'disabled');
		   $("#ophCantProductoFinal").attr('disabled', 'disabled');
			
		   //Seteamos valor
		   currInfoHoloOrdenProduccion.bytAcabadoEtiqueta = 0;
		}
		else{

			//Activamos suajes porque debe ir obligatoriamente si se activa el acabado de etiqueta
		   $("#ophbytSuajesHolo").attr('disabled', 'disabled');
		   $("#ophbytSuajesHolo").prop( "checked", true ).trigger("change");

		   $("#ophProductoFinal").removeAttr('disabled', 'disabled');
		   $("#ophCantProductoFinal").removeAttr('disabled', 'disabled');
		   $("#ophbytFoliosHolo").removeAttr('disabled');
		   $("#ophbytCodigoBarras").removeAttr('disabled');
		   $("#ophbytPleca").removeAttr('disabled');

		   //Seteamos valor
		   currInfoHoloOrdenProduccion.bytAcabadoEtiqueta = 1;
		   
		}
	});

	$("#ophbytSuajesHolo").change(function () {
		
		//Revisamos si esta checkeado
		if(!$(this).is(":checked")) {
			
		   $("#wrapperSuajesHolo").find('select,input').not(this).attr('disabled', 'disabled');

		    //Limpiamos valores
			clearFilters('wrapperSuajesHolo');

			currInfoHoloOrdenProduccion.bytSuajes= 0;
		}
		else{

		   $("#wrapperSuajesHolo").find('select,input').not(this).removeAttr('disabled');
		   currInfoHoloOrdenProduccion.bytSuajes = 1;
		}
	});

	$("#ophbytFoliosHolo").change(function () {
		
		//Revisamos si esta checkeado
		if(!$(this).is(":checked")) {
			
		   $("#wrapperFoliosHolo").find('select,input').not(this).attr('disabled', 'disabled');

		   //Limpiamos valores
			clearFilters('wrapperFoliosHolo');
			currOrdenProduccion.bytFolios = 0;

		}
		else{

		   $("#wrapperFoliosHolo").find('select,input').not(this).removeAttr('disabled');
		   currOrdenProduccion.bytFolios = 1;
		}
	});

	$("#ophbytPleca").change(function () {
		
		//Revisamos si esta checkeado
		if(!$(this).is(":checked")) {
			
		   $("#wrapperPlecaHolo").find('select,input').not(this).attr('disabled', 'disabled');

		   //Limpiamos valores
			clearFilters('wrapperPlecaHolo');
			currInfoHoloOrdenProduccion.bytPleca = 0;
		}
		else{

		   $("#wrapperPlecaHolo").find('select,input').not(this).removeAttr('disabled');
		   currInfoHoloOrdenProduccion.bytPleca = 1;
		}
	});
}

function initSelectsHologramasOP(){

	/*********************** OP GENERAL ***********************/
	$(".opOperacion").select2({
		theme: "bootstrap-5",
		language: "es",
		minimumInputLength: -1,
		multiple:false,
		delay: 250,
		escapeMarkup: function (text) { return text; },
		ajax: {
			url: "../controllers/ordenProduccion/ordenProduccion_editor_controller",
			dataType: 'json',
			data: function (params) {
				return {
					action: "getOperacionesOP_JSON",				
					[strTokenID]: strTokenValue,
					strTerm: $.trim(params.term) === '' ? '' : params.term
				};
			}
		}
	});

	// $('#ophOperacion').on('change', function (e) {

		

	// });


	$(".opTipoOperacion").select2({
		theme: "bootstrap-5",
		language: "es",
		minimumInputLength: -1,
		multiple:false,
		delay: 250,
		escapeMarkup: function (text) { return text; },
		ajax: {
			url: "../controllers/ordenProduccion/ordenProduccion_editor_controller",
			dataType: 'json',
			data: function (params) {
				return {
					action: "getTiposOperacionOP_JSON",				
					[strTokenID]: strTokenValue,
					strTerm: $.trim(params.term) === '' ? '' : params.term
				};
			}
		}
	});

	$(".opgTipoMuestra").select2({
		theme: "bootstrap-5",
		language: "es",
		minimumInputLength: -1,
		multiple:false,
		delay: 250,
		escapeMarkup: function (text) { return text; },
		ajax: {
			url: "../controllers/ordenProduccion/ordenProduccion_editor_controller",
			dataType: 'json',
			data: function (params) {
				return {
					action: "getTiposMuestraOP_JSON",				
					[strTokenID]: strTokenValue,
					strTerm: $.trim(params.term) === '' ? '' : params.term
				};
			}
		}
	});

	$(".opgCliente").select2({
		theme: "bootstrap-5",
		language: "es",
		minimumInputLength: 3,
		multiple:false,
		delay: 250,
		escapeMarkup: function (text) { return text; },
		ajax: {
			url: "../controllers/ordenProduccion/ordenProduccion_controller",
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

	$('.opgCliente').on('change', function (e) {

		$("#ophgFormatoCliente").val("").trigger("change");
		
	});

	$("#ophgFormatoCliente").select2({
		theme: "bootstrap-5",
		language: "es",
		minimumInputLength: -1,
		multiple:false,
		delay: 250,
		escapeMarkup: function (text) { return text; },
		ajax: {
			url: "../controllers/ordenProduccion/ordenProduccion_editor_controller",
			dataType: 'json',
			data: function (params) {
				return {
					action: "getFormatosCliente_JSON",
					intCliente: $("#ophgCliente").val(),
					[strTokenID]: strTokenValue,
					strTerm: $.trim(params.term) === '' ? '' : params.term
				};
			}
		}
	});

	$(".opgEjecutivo").select2({
		theme: "bootstrap-5",
		language: "es",
		minimumInputLength: -1,
		multiple:false,
		delay: 250,
		escapeMarkup: function (text) { return text; },
		ajax: {
			url: "../controllers/ordenProduccion/ordenProduccion_editor_controller",
			dataType: 'json',
			data: function (params) {
				return {
					action: "getVendedores_JSON",				
					[strTokenID]: strTokenValue,
					strTerm: $.trim(params.term) === '' ? '' : params.term
				};
			}
		}
	});

	$(".ophgTipoProdOP").select2({
		theme: "bootstrap-5",
		language: "es",
		minimumInputLength: -1,
		multiple:false,
		delay: 250,
		escapeMarkup: function (text) { return text; },
		ajax: {
			url: "../controllers/ordenProduccion/ordenProduccion_editor_controller",
			dataType: 'json',
			data: function (params) {
				return {
					action: "getTiposProduccion",				
					[strTokenID]: strTokenValue,
					strTerm: $.trim(params.term) === '' ? '' : params.term
				};
			}
		}
	});

	$('#ophgTipoProdOP').on('change', function (e) {

		//Ocultamos todas las descripciones
		$(".tiposProdHOrdenesContainer .clientOrden").hide();
		$(".tiposProdHOrdenesContainer .internaOrden").hide();
		$(".tiposProdHOrdenesContainer .filialOrden").hide();
		$(".tiposProdHOrdenesContainer .infoTypeOrden").hide();

		//Revisamos si es filial
		if($(this).val() == 3){

			//Limpiamos select
			$('#ophOperacion').val("").trigger("change");

			//Definimos la operación en producción
			setOption_AjaxSelect("ophOperacion", {id: 2, text:'Producción'});
			$(".tiposProdHOrdenesContainer #proveedorFilialOp").show("fast");

			//Mostramos descripción
			$(".filialOrden").show("fast");

			//Mostramos container 
			$(".infoTypeOrden").show("fast");
			
		}else{

			//Revisamos si es tipo cliente y revisamos que la operación no sea pruebas
			//Si es tipo cliente y es de pruebas, solamente muestra las leyendas
			if($(this).val() == 1 && $('#ophOperacion').val() != 3){

				//Mostramos descripción
				$(".clientOrden").show("fast");

				//Mostramos container 
				$(".infoTypeOrden").show("fast");

				//Limpiamos select
				$('#ophOperacion').val("").trigger("change");

				//Definimos la operación como diseño
				setOption_AjaxSelect("ophOperacion", {id: 1, text:'Diseño'});

			}else if($(this).val() == 1 && $('#ophOperacion').val() == 3){
				//Mostramos descripción
				$(".clientOrden").show("fast");

				//Mostramos container 
				$(".infoTypeOrden").show("fast");
			}

			//Revisamos si es interna
			if($(this).val() == 2){

				//Limpiamos select
				$('#ophOperacion').val("").trigger("change");

				//Definimos la operación como producción
				setOption_AjaxSelect("ophOperacion", {id: 2, text:'Producción'});

				//Mostramos descripción
				$(".internaOrden").show("fast");

				//Mostramos container 
				$(".infoTypeOrden").show("fast");
			}

			//Ocultamos el input de proveedor de filial
			$("#proveedorFilialOp").hide("fast");

			
			//Limpiamos proveedor
			currOrdenProduccion.payload.intProveedor = 0;
			$("#ophProv").val("").trigger("change");

		}

	});

	$("#ophProv").select2({
		theme: "bootstrap-5",
		language: "es",
		minimumInputLength: -1,
		multiple: false,
		placeholder: "",
		delay: 250,
		escapeMarkup: function (text) { return text; },
		ajax: {
			url: "../controllers/controllerGeneral",
			dataType: 'json',
			data: function (params) {
				return {
					action: "getProveedoresFiliales",					
					[strTokenID]: strTokenValue,
					strTerm: $.trim(params.term) === '' ? '' : params.term
				};
			}
		}
	});

	$('#ophProv').on('change', function (e) {
		if($('#ophTipoProdOP').val() == 3 ) currOrdenProduccion.payload = {"intProveedor": $('#ophProv').val()};
	});

	$("#ophUMedidaMedidas").select2({
		theme: "bootstrap-5",
		language: "es",
		minimumInputLength: -1,
		multiple:false,
		delay: 250,
		escapeMarkup: function (text) { return text; },
		ajax: {
			url: "../controllers/controllerGeneral",
			dataType: 'json',
			data: function (params) {
				return {
					action: "getUMedidas",
					[strTokenID]: strTokenValue,
					strTerm: $.trim(params.term) === '' ? '' : params.term
				};
			}
		}
	});

	$(".ophgImagen").select2({
		theme: "bootstrap-5",
		language: "es",
		minimumInputLength: -1,
		multiple:false,
		delay: 250,
		escapeMarkup: function (text) { return text; },
		ajax: {
			url: "../controllers/ordenProduccion/ordenProduccion_editor_controller",
			dataType: 'json',
			data: function (params) {
				return {
					action: "getImagenesHolograma_JSON",				
					[strTokenID]: strTokenValue,
					strTerm: $.trim(params.term) === '' ? '' : params.term
				};
			}
		}
	});

	$(".ophgDireccionMaterial").select2({
		theme: "bootstrap-5",
		language: "es",
		minimumInputLength: -1,
		multiple:false,
		delay: 250,
		escapeMarkup: function (text) { return text; },
		ajax: {
			url: "../controllers/ordenProduccion/ordenProduccion_editor_controller",
			dataType: 'json',
			data: function (params) {
				return {
					action: "getDireccionMaterial_JSON",				
					[strTokenID]: strTokenValue,
					strTerm: $.trim(params.term) === '' ? '' : params.term
				};
			}
		}
	});

	$('.ophgDireccionMaterial').on('change', function (e) {

		if($(this).select2("data").length > 0){
			if($(this).select2("data")[0].id == "3"){
				
				$(".salidaConToggle").show("fast");
			}else{
				$(".salidaConToggle").hide("fast");

			}
		}
		
	});

	$(".ophgMaterialHolo").select2({
		theme: "bootstrap-5",
		language: "es",
		minimumInputLength: -1,
		multiple:false,
		delay: 250,
		escapeMarkup: function (text) { return text; },
		ajax: {
			url: "../controllers/ordenProduccion/ordenProduccion_editor_controller",
			dataType: 'json',
			data: function (params) {
				return {
					action: "getMaterialesHologramas_JSON",				
					[strTokenID]: strTokenValue,
					strTerm: $.trim(params.term) === '' ? '' : params.term
				};
			}
		}
	});

	$('.ophgMaterialHolo').on('change', function (e) {

		$("#wrapperMaterialHolo .formElement").each(function(index){
		
			// checamos si tiene el atributo definido
			if ($(this).attr("data-ControlID"))
			{
				var strID = "#" + $(this).attr('data-ControlID');
				$(strID).val("").trigger("change");
			}
		});
	});

	$(".ophMaterialColor").select2({
		theme: "bootstrap-5",
		language: "es",
		minimumInputLength: -1,
		multiple:false,
		delay: 250,
		escapeMarkup: function (text) { return text; },
		ajax: {
			url: "../controllers/ordenProduccion/ordenProduccion_editor_controller",
			dataType: 'json',
			data: function (params) {
				return {
					action: "getMaterialesHologramasColores_JSON",				
					intMaterial: $(".ophgMaterialHolo").val(),				
					[strTokenID]: strTokenValue,
					strTerm: $.trim(params.term) === '' ? '' : params.term
				};
			}
		}
	});

	$(".ophMaterialCalidad").select2({
		theme: "bootstrap-5",
		language: "es",
		minimumInputLength: -1,
		multiple:false,
		delay: 250,
		escapeMarkup: function (text) { return text; },
		ajax: {
			url: "../controllers/ordenProduccion/ordenProduccion_editor_controller",
			dataType: 'json',
			data: function (params) {
				return {
					action: "getMaterialesHologramasCalidad_JSON",			
					intMaterial: $(".ophgMaterialHolo").val(),		
					[strTokenID]: strTokenValue,
					strTerm: $.trim(params.term) === '' ? '' : params.term
				};
			}
		}
	});

	$(".ophMaterialEspesor").select2({
		theme: "bootstrap-5",
		language: "es",
		minimumInputLength: -1,
		multiple:false,
		delay: 250,
		escapeMarkup: function (text) { return text; },
		ajax: {
			url: "../controllers/ordenProduccion/ordenProduccion_editor_controller",
			dataType: 'json',
			data: function (params) {
				return {
					action: "getMaterialesHologramasEspesor_JSON",		
					intMaterial: $(".ophgMaterialHolo").val(),			
					[strTokenID]: strTokenValue,
					strTerm: $.trim(params.term) === '' ? '' : params.term
				};
			}
		}
	});

	$(".ophMedidaCentro").select2({
		theme: "bootstrap-5",
		language: "es",
		minimumInputLength: -1,
		multiple:false,
		delay: 250,
		escapeMarkup: function (text) { return text; },
		ajax: {
			url: "../controllers/ordenProduccion/ordenProduccion_editor_controller",
			dataType: 'json',
			data: function (params) {
				return {
					action: "getMedidasdelCentro_JSON",				
					[strTokenID]: strTokenValue,
					strTerm: $.trim(params.term) === '' ? '' : params.term
				};
			}
		}
	});

	$('.ophgMaterialHolo').on('change', function (e) {

		if($(this).select2("data").length > 0){
			if($(this).select2("data")[0].text == "Otro"){
				
				$(".toggleOtroMedidaCentro").show("fast");
			}else{
				$(".toggleOtroMedidaCentro").hide("fast");
	
			}
		}
		
	});

	$(".ophFormaSuaje").select2({
		theme: "bootstrap-5",
		language: "es",
		minimumInputLength: -1,
		multiple:false,
		delay: 250,
		escapeMarkup: function (text) { return text; },
		ajax: {
			url: "../controllers/ordenProduccion/ordenProduccion_editor_controller",
			dataType: 'json',
			data: function (params) {
				return {
					action: "getFormasSuaje_JSON",				
					[strTokenID]: strTokenValue,
					strTerm: $.trim(params.term) === '' ? '' : params.term
				};
			}
		}
	});

	$(".ophTipoFolio").select2({
		theme: "bootstrap-5",
		language: "es",
		minimumInputLength: -1,
		multiple:false,
		delay: 250,
		escapeMarkup: function (text) { return text; },
		ajax: {
			url: "../controllers/ordenProduccion/ordenProduccion_editor_controller",
			dataType: 'json',
			data: function (params) {
				return {
					action: "getTiposFolioOP_JSON",				
					[strTokenID]: strTokenValue,
					strTerm: $.trim(params.term) === '' ? '' : params.term
				};
			}
		}
	});

	$(".ophProductoFinal").select2({
		theme: "bootstrap-5",
		language: "es",
		minimumInputLength: -1,
		multiple:false,
		delay: 250,
		escapeMarkup: function (text) { return text; },
		ajax: {
			url: "../controllers/ordenProduccion/ordenProduccion_editor_controller",
			dataType: 'json',
			data: function (params) {
				return {
					action: "getTiposProductoFinal_JSON",				
					[strTokenID]: strTokenValue,
					strTerm: $.trim(params.term) === '' ? '' : params.term
				};
			}
		}
	});

	$(".ophDisenoHolo").select2({
		theme: "bootstrap-5",
		language: "es",
		minimumInputLength: -1,
		multiple:false,
		delay: 250,
		escapeMarkup: function (text) { return text; },
		ajax: {
			url: "../controllers/ordenProduccion/ordenProduccion_editor_controller",
			dataType: 'json',
			data: function (params) {
				return {
					action: "getDisenoHolograma_JSON",				
					[strTokenID]: strTokenValue,
					strTerm: $.trim(params.term) === '' ? '' : params.term
				};
			}
		}
	});

	$(".ophTipoHolograma").select2({
		theme: "bootstrap-5",
		language: "es",
		minimumInputLength: -1,
		multiple:false,
		delay: 250,
		escapeMarkup: function (text) { return text; },
		ajax: {
			url: "../controllers/ordenProduccion/ordenProduccion_editor_controller",
			dataType: 'json',
			data: function (params) {
				return {
					action: "getTiposHolograma_JSON",				
					[strTokenID]: strTokenValue,
					strTerm: $.trim(params.term) === '' ? '' : params.term
				};
			}
		}
	});

	$(".opgUMedida").select2({
		theme: "bootstrap-5",
		language: "es",
		minimumInputLength: -1,
		multiple:false,
		delay: 250,
		escapeMarkup: function (text) { return text; },
		ajax: {
			url: "../controllers/controllerGeneral",
			dataType: 'json',
			data: function (params) {
				return {
					action: "getUMedidasOP",				
					[strTokenID]: strTokenValue,
					strTerm: $.trim(params.term) === '' ? '' : params.term
				};
			}
		}
	});
}
