<?php
require_once(dirname(__FILE__) ."/../../config/Database.php");
require_once(dirname(__FILE__) . "/../model_Generales.php");

class ventas_cotizaciones_partida_detalle extends modelBase{
	protected array $_childClasses = array("docs"=>"ventas_cotizaciones_partida_detalle_docs");

	protected int $intCotizacion;
	protected int $bytRevision;
	protected int $intPartida;
	protected int $intOldDetalle;
	protected int $intDetalle;
	protected int $bytSecuencia;
	protected int $bytDelete;
	protected int $intArticulo;
	protected string $strArticulo;
	protected string $strDescripcion;
	protected float $dblCantidad;
	protected float $dblLargo;
	protected float $dblAncho;
	protected float $dblAlto;
	protected string $strObservaciones;
	protected float $dblDiasEntrega;
	protected string $datFechaEntrega;
	protected string $strFechaEntrega;
	protected float $dblPrecioUnitario;
	protected float $dblPrecioTotal;
	protected float $dblPtjeUtilidad;
	protected float $dblUtilidad;
	protected int $bytConflicto;
	protected int $bytBitacora;
	protected string $strUsuarioAlta;
	protected string $datFechaAlta;
	protected string $strUsuarioMod;
	protected string $datFechaMod;
	public array $docs;

	public function __construct(){
		$this->clear_object();
	}
	protected function clear_object(){
		$this->intCotizacion = 0;
		$this->bytRevision = 0;
		$this->intPartida = 0;
		$this->intOldDetalle = 0;
		$this->intDetalle = 0;
		$this->bytSecuencia = 0;
		$this->bytDelete = 0;
		$this->intArticulo = 0;
		$this->strArticulo = '';
		$this->strDescripcion = '';
		$this->dblCantidad = 0;
		$this->dblLargo = 0;
		$this->dblAncho = 0;
		$this->dblAlto = 0;
		$this->strObservaciones = '';
		$this->dblDiasEntrega = 0;
		$this->datFechaEntrega = '';
		$this->strFechaEntrega = '';
		$this->dblPrecioUnitario = 0;
		$this->dblPrecioTotal = 0;
		$this->dblPtjeUtilidad = 0;
		$this->dblUtilidad = 0;
		$this->bytConflicto = 0;
		$this->bytBitacora = 0;
		$this->strUsuarioAlta = '';
		$this->datFechaAlta = '';
		$this->strUsuarioMod = '';
		$this->datFechaMod = '';
		$this->docs = array();
	}

	public function saveArchivoDocPartidaDetalle($userName){
		// hacemos la coneccion a la base de datos
		$db = new Database;
        $db->connectDB();

        // obtenemos json
		$json = json_encode($this->docs);
        $json = $db->sanitize($json);
		
        // guardamos
        $query = "call p_addDocumentoPartidaCotDetalle('$json', '$userName')";
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

	public function setConflictoPartidaDetalle($userName){

		// hacemos la coneccion a la base de datos
		$db = new Database;
        $db->connectDB();
        
        // guardamos
        $query = "call p_setConflictoDetallePartida_Cotizacion($this->intCotizacion, $this->bytRevision, $this->intPartida, $this->intDetalle, $this->bytConflicto, '$userName')";

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
			$arr['msg'] = "";
			
        }

		return $arr;
	}
	
}

?>
