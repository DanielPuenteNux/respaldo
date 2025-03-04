class Ventas_Cotizaciones extends modelBase{
	_childClasses = {'partidas': 'Ventas_Cotizaciones_Partida'};
	_controller_cotizaciones = "../controllers/ventas/ventas_cotizaciones_controller";

	constructor() {
		super();
		this.clear();
	}
	clear(){
		this.intCotizacion = 0;
		this.bytRevision = 0;
		this.intCliente = 0;
		this.strCliente = '';
		this.datFecha = '';
		this.intVendedor = 0;
		this.strVendedor = '';
		this.intEstatusCot = 0;
		this.strEstatusCot = '';
		this.intTipoCotizacion = 0;
		this.strTipoCotizacion = '';
		this.intTipoServicio = 0;
		this.strTipoServicio = '';
		this.strDescripcion = '';
		this.intMoneda = 0;
		this.strMoneda = '';
		this.intDiasEntrega = 0;
		this.datFechaEntrega = '';
		this.strFechaEntrega = '';
		this.dblUtilidad = 0;
		this.dblPtjeUtilidad = 0;
		this.dblSubTotal = 0;
		this.dblIVA = 0;
		this.dblPtjeIVA = 0;
		this.dblTotal = 0;
		this.dblTipoCambio = 0;
		this.dblSubTotal_Moneda = 0;
		this.dblIVA_Moneda = 0;
		this.dblTotal_Moneda = 0;
		this.strNotas = '';
		this.bytEstatus = 0;
		this.strUsuarioAlta = '';
		this.datFechaAlta = '';
		this.strUsuarioMod = '';
		this.datFechaMod = '';
        this.partidas = [];
	}

	async get(intCotizacion, bytRevision){
				
		let self = this;
		return await fetch_Post_Promise(this._controller_cotizaciones, {action: 'getCotizacionByID', p1: intCotizacion, p2: bytRevision}).then(
			function(objResponse) { 		
				if (objResponse.success == 1){
					self.loadJSON(objResponse.json);
				}
				return objResponse;				
			});
	}
}

class Ventas_Cotizaciones_Partida extends modelBase{
	_childClasses = {'detalle': 'Ventas_Cotizaciones_Partida_Detalle', 'docs': 'Ventas_Cotizaciones_Partida_Docs'};

	constructor() {
		super();
		this.clear();
	}
	clear(){
		this.intCotizacion = 0;
		this.bytRevision = 0;
		this.intOldPartida = 0;
		this.intPartida = 0;
		this.bytSecuencia = 0;
		this.bytDelete = 0;
		this.intTipoPartida = 0;
		this.strTipoPartida = '';
		this.strDescripcion = '';
		this.dblCantidad = 0;
		this.dblLargo = 0;
		this.dblAncho = 0;
		this.dblAlto = 0;
		this.strObservaciones = '';
		this.intTantos = 0;
		this.intAcabadoOP = 0;
		this.strAcabadoOP = '';
		this.intAplicacionTinta = 0;
		this.strAplicacionTinta = '';
		this.intCantidadTintas = 0;
		this.dblDiasEntrega = 0;
		this.datFechaEntrega = '';
		this.strFechaEntrega = '';
		this.dblPrecioUnitario = 0;
		this.dblPrecioTotal = 0;
		this.dblPtjeUtilidad = 0;
		this.dblUtilidad = 0;
		this.strUsuarioAlta = '';
		this.datFechaAlta = '';
		this.strUsuarioMod = '';
		this.datFechaMod = '';
        this.detalle = [];
		this.docs = [];
	}
}

class Ventas_Cotizaciones_Partida_Docs extends modelBase{
	_childClasses = {};

	constructor() {
		super();
		this.clear();
	}
	clear(){
		this.intConsec = 0;
		this.intCotizacion = 0;
		this.bytRevision = 0;
		this.intPartida = 0;
		this.strNombreDoc = '';
		this.strNotas = '';
		this.intTipoArchivo = 0;
		this.intTipoDocumentoCot = 0;
		this.strTipoDocumentoCot = '';
		this.strFilePath = '';
		this.strFileName = '';
		this.intFileSizeKB = 0;
		this.strContentText = '';
		this.bytEstatus = 0;
		this.strUsuarioAlta = '';
		this.datFechaAlta = '';
		this.strUsuarioMod = '';
		this.datFechaMod = '';
	}
}

class Ventas_Cotizaciones_Partida_Detalle extends modelBase{
	_childClasses = {'docs': 'Ventas_Cotizaciones_Partida_Detalle_Docs'};

	constructor() {
		super();
		this.clear();
	}
	clear(){
		this.intCotizacion = 0;
		this.bytRevision = 0;
		this.intPartida = 0;
		this.intOldDetalle = 0;
		this.intDetalle = 0;
		this.bytSecuencia = 0;
		this.bytDelete = 0;
		this.intArticulo = 0;
		this.strArticulo = '';
		this.strDescripcion = '';
		this.dblCantidad = 0;
		this.dblLargo = 0;
		this.dblAncho = 0;
		this.dblAlto = 0;
		this.strObservaciones = '';
		this.dblDiasEntrega = 0;
		this.datFechaEntrega = '';
		this.strFechaEntrega = '';
		this.dblPrecioUnitario = 0;
		this.dblPrecioTotal = 0;
		this.dblPtjeUtilidad = 0;
		this.dblUtilidad = 0;
		this.bytConflicto = 0;
		this.bytBitacora = 0;
		this.strUsuarioAlta = '';
		this.datFechaAlta = '';
		this.strUsuarioMod = '';
		this.datFechaMod = '';
		this.docs = [];
	}
}

class Ventas_Cotizaciones_Partida_Detalle_Docs extends modelBase{
	_childClasses = {};

	constructor() {
		super();
		this.clear();
	}
	clear(){
		this.intConsec = 0;
		this.intCotizacion = 0;
		this.bytRevision = 0;
		this.intPartida = 0;
		this.intDetalle = 0;
		this.strNombreDoc = '';
		this.strNotas = '';
		this.intTipoArchivo = 0;
		this.intTipoDocumentoCot = 0;
		this.strTipoDocumentoCot = '';
		this.strFilePath = '';
		this.strFileName = '';
		this.intFileSizeKB = 0;
		this.strContentText = '';
		this.bytEstatus = 0;
		this.strUsuarioAlta = '';
		this.datFechaAlta = '';
		this.strUsuarioMod = '';
		this.datFechaMod = '';
	}
}
