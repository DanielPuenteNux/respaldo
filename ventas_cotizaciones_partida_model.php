<?php
require_once(dirname(__FILE__) ."/../../config/Database.php");
require_once(dirname(__FILE__) . "/../model_Generales.php");

class ventas_cotizaciones_partida extends modelBase{
	protected array $_childClasses = array("detalle" => "ventas_cotizaciones_partida_detalle", "docs"=>"ventas_cotizaciones_partida_docs");

	protected int $intCotizacion;
	protected int $bytRevision;
	protected int $intOldPartida;
	protected int $intPartida;
	protected int $bytSecuencia;
	protected int $bytDelete;
	protected int $intTipoPartida;
	protected string $strTipoPartida;
	protected string $strDescripcion;
	protected float $dblCantidad;
	protected float $dblLargo;
	protected float $dblAncho;
	protected float $dblAlto;
	protected string $strObservaciones;
	protected int $intTantos;
	protected int $intAcabadoOP;
	protected string $strAcabadoOP;
	protected int $intAplicacionTinta;
	protected string $strAplicacionTinta;
	protected int $intCantidadTintas;
	protected float $dblDiasEntrega;
	protected string $datFechaEntrega;
	protected string $strFechaEntrega;
	protected float $dblPrecioUnitario;
	protected float $dblPrecioTotal;
	protected float $dblPtjeUtilidad;
	protected float $dblUtilidad;
	protected string $strUsuarioAlta;
	protected string $datFechaAlta;
	protected string $strUsuarioMod;
	protected string $datFechaMod;
	public array $detalle;
	public array $docs;

	public function __construct(){
		$this->clear_object();
	}
	protected function clear_object(){
		$this->intCotizacion = 0;
		$this->bytRevision = 0;
		$this->intOldPartida = 0;
		$this->intPartida = 0;
		$this->bytSecuencia = 0;
		$this->bytDelete = 0;
		$this->intTipoPartida = 0;
		$this->strDescripcion = '';
		$this->dblCantidad = 0;
		$this->dblLargo = 0;
		$this->dblAncho = 0;
		$this->dblAlto = 0;
		$this->strObservaciones = '';
		$this->intTantos = 0;
		$this->intAcabadoOP = 0;
		$this->strAcabadoOP = '';
		$this->intAplicacionTinta = 0;
		$this->strAplicacionTinta = '';
		$this->intCantidadTintas = 0;
		$this->dblDiasEntrega = 0;
		$this->datFechaEntrega = '';
		$this->strFechaEntrega = '';
		$this->dblPrecioUnitario = 0;
		$this->dblPrecioTotal = 0;
		$this->dblPtjeUtilidad = 0;
		$this->dblUtilidad = 0;
		$this->strUsuarioAlta = '';
		$this->datFechaAlta = '';
		$this->strUsuarioMod = '';
		$this->datFechaMod = '';
		$this->detalle = array();
		$this->docs = array();
	}

	public function saveArchivoDocPartida($userName){
		// hacemos la coneccion a la base de datos
		$db = new Database;
        $db->connectDB();

        // obtenemos json
		$json = json_encode($this->docs);
        $json = $db->sanitize($json);
		
        // guardamos
        $query = "call p_addDocumentoPartidaCot('$json', '$userName')";
		
        $results = $db->get_Rows($query); 

        // cerramos conexión
        $db->closeDB();

        // validamos
        if(!$results){ 
            
            // error        
            $arr = array('success' => 98, 'msg' => "Se encontraron problemas al procesar la información.", 'sql' => $query);
        }
        else {

            // obtenemos datos
            $row = mysqli_fetch_assoc($results); 

            // preparamos array
            $arr = array();
            $arr['success'] = $row["Result"];
            $arr['msg'] = $row["Msg"];
        }

		return $arr;
	}

}


?>
