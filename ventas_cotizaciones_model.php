<?php
require_once(dirname(__FILE__) ."/../../config/Database.php");
require_once(dirname(__FILE__) . "/../model_Generales.php");

class ventas_cotizaciones extends modelBase{
	protected array $_childClasses = array("partidas"=>"ventas_cotizaciones_partida");

	protected int $intCotizacion;
	protected int $bytRevision;
	protected int $intCliente;
	protected string $strCliente;
	protected string $datFecha;
	protected int $intVendedor;
	protected string $strVendedor;
	protected int $intEstatusCot;
	protected string $strEstatusCot;
	protected int $intTipoCotizacion;
	protected string $strTipoCotizacion;
	protected int $intTipoServicio;
	protected string $strTipoServicio;
	protected string $strDescripcion;
	protected int $intMoneda;
	protected string $strMoneda;
	protected int $intDiasEntrega;
	protected string $datFechaEntrega;
	protected string $strFechaEntrega;
	protected float $dblUtilidad;
	protected float $dblPtjeUtilidad;
	protected float $dblSubTotal;
	protected float $dblIVA;
	protected float $dblPtjeIVA;
	protected float $dblTotal;
	protected float $dblTipoCambio;
	protected float $dblSubTotal_Moneda;
	protected float $dblIVA_Moneda;
	protected float $dblTotal_Moneda;
	protected string $strNotas;
	protected int $bytEstatus;
	protected string $strUsuarioAlta;
	protected string $datFechaAlta;
	protected string $strUsuarioMod;
	protected string $datFechaMod;
	public array $partidas;

	public function __construct(){
		$this->clear_object();
	}
	protected function clear_object(){
		$this->intCotizacion = 0;
		$this->bytRevision = 0;
		$this->intCliente = 0;
        $this->strCliente = '';
		$this->datFecha = '';
		$this->intVendedor = 0;
		$this->strVendedor = '';
		$this->intEstatusCot = 0;
        $this->strEstatusCot = '';
		$this->intTipoCotizacion = 0;
        $this->strTipoCotizacion = '';
        $this->intTipoServicio = 0;
        $this->strTipoServicio = '';
		$this->strDescripcion = '';
		$this->intMoneda = 0;
        $this->strMoneda = '';
		$this->intDiasEntrega = 0;
		$this->datFechaEntrega = '';
		$this->strFechaEntrega = '';
		$this->dblUtilidad = 0;
		$this->dblPtjeUtilidad = 0;
		$this->dblSubTotal = 0;
		$this->dblIVA = 0;
		$this->dblPtjeIVA = 0;
		$this->dblTotal = 0;
		$this->dblTipoCambio = 0;
		$this->dblSubTotal_Moneda = 0;
		$this->dblIVA_Moneda = 0;
		$this->dblTotal_Moneda = 0;
		$this->strNotas = '';
		$this->bytEstatus = 0;
		$this->strUsuarioAlta = '';
		$this->datFechaAlta = '';
		$this->strUsuarioMod = '';
		$this->datFechaMod = '';
		$this->partidas = array();

	}

	public function save($userName){
		
		// hacemos la coneccion a la base de datos
		$db = new Database;
        $db->connectDB();

		// cargamos user
        if ($this->intCotizacion == 0) $this->strUsuarioAlta = $userName;
        else $this->strUsuarioMod = $userName;

		$json = $this->toJSON();
		$json = $db->sanitize($json);

        
        // guardamos
		if ($this->intCotizacion == 0) $query = "call p_newCotizacion('$json', '$userName')";
		else $query = "call p_editCotizacion('$json', '$userName')";
		
		$results = mysqli_query($db->conn, $query);
		//var_dump(mysqli_error($db->conn));
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
			$arr['intCotizacion'] = $row["intCotizacion"];
			$arr['strUsuarioAlta'] = $row["strUsuarioAlta"];
            $arr['datFechaAlta'] = $row["datFechaAlta"];

            if($row["Result"] == 1){
				$this->intCotizacion = $row["intCotizacion"];
			}
        }

		return $arr;
	}

	public function newRevCotizacion($userName){
		
		// hacemos la coneccion a la base de datos
		$db = new Database;
        $db->connectDB();

		// cargamos user
        if ($this->intCotizacion == 0) $this->strUsuarioAlta = $userName;
        else $this->strUsuarioMod = $userName;

		$json = $this->toJSON();
		$json = $db->sanitize($json);
        
        // guardamos
		$query = "call p_newRevision_Cotizacion('$json', '$userName')";
		
		$results = mysqli_query($db->conn, $query);
		
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
			$arr['intCotizacion'] = $row["intCotizacion"];
			$arr['strUsuarioAlta'] = $row["strUsuarioAlta"];
            $arr['datFechaAlta'] = $row["datFechaAlta"];

            if($row["Result"] == 1){
				$this->intCotizacion = $row["intCotizacion"];
			}
        }

		return $arr;
	}

	public function getCotizacionByID($id, $bytRevision){

		// hacemos la coneccion a la base de datos
		$db = new Database;
        $db->connectDB();
			        
        // obtenemos info
        $query = "call p_getCotizacion_Ventas($id, $bytRevision)";
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

			// cargamos info
			$this->loadJSON($row["strJSON"]);

            // preparamos array
            $arr = array();
            $arr['success'] = $row["Result"];
            $arr['msg'] = $row["Msg"];
			$arr['json'] = $row["strJSON"];
        }

		return $arr;

	}

	public function aprobarCotizacion($intCotizacion, $bytRevision, $userName){

		// hacemos la coneccion a la base de datos
		$db = new Database;
        $db->connectDB();
			        
        // obtenemos info
        $query = "call p_aprobar_Cotizacion($intCotizacion, $bytRevision, '$userName')";
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

	public function rechazarCotizacion($intCotizacion, $bytRevision, $intEstatusCot, $userName){

		// hacemos la coneccion a la base de datos
		$db = new Database;
        $db->connectDB();
			        
        // obtenemos info
        $query = "call p_rechazar_Cotizacion($intCotizacion, $bytRevision, $intEstatusCot, '$userName')";
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

	public function cancelarCotizacion($intCotizacion, $bytRevision, $userName){

		// Establecemos la conexión a la base de datos
		$db = new Database;
        $db->connectDB();
			        
        // Ejecutamos la consulta para cancelar la cotización
        $query = "call p_cancelar_Cotizacion($intCotizacion, $bytRevision, '$userName')";
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
