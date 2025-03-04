<?php
require_once(dirname(__FILE__) ."/../../config/Database.php");
require_once(dirname(__FILE__) . "/../model_Generales.php");

class ventas_cotizaciones_partida_detalle_docs extends modelBase{
	protected array $_childClasses = array();

	protected int $intConsec;
	protected int $intCotizacion;
	protected int $bytRevision;
	protected int $intPartida;
	protected int $intDetalle;
	protected string $strNombreDoc;
	protected string $strNotas;
	protected int $intTipoArchivo;
	protected int $intTipoDocumentoCot;
	protected string $strTipoDocumentoCot;
	protected string $strFilePath;
	protected string $strFileName;
	protected int $intFileSizeKB;
	protected string $strContentText;
	protected int $bytEstatus;
	protected string $strUsuarioAlta;
	protected string $datFechaAlta;
	protected string $strUsuarioMod;
	protected string $datFechaMod;

	public function __construct(){
		$this->clear_object();
	}
	protected function clear_object(){
		$this->intConsec = 0;
		$this->intCotizacion = 0;
		$this->bytRevision = 0;
		$this->intPartida = 0;
		$this->intDetalle = 0;
		$this->strNombreDoc = '';
		$this->strNotas = '';
		$this->intTipoArchivo = 0;
		$this->intTipoDocumentoCot = 0;
		$this->strTipoDocumentoCot = '';
		$this->strFilePath = '';
		$this->strFileName = '';
		$this->intFileSizeKB = 0;
		$this->strContentText = '';
		$this->bytEstatus = 0;
		$this->strUsuarioAlta = '';
		$this->datFechaAlta = '';
		$this->strUsuarioMod = '';
		$this->datFechaMod = '';
	}
}

?>
