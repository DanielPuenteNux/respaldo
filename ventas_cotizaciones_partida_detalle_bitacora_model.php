<?php
require_once(dirname(__FILE__) ."/../../config/Database.php");
require_once(dirname(__FILE__) . "/../model_Generales.php");

class ventas_cotizaciones_partida_detalle_bitacora extends modelBase{
	protected array $_childClasses = array();

	protected int $intConsec;
	protected int $intCotizacion;
	protected int $bytRevision;
	protected int $intPartida;
	protected int $intDetalle;
	protected int $intTipoRegistro;
	protected string $strDescripcion;
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
		$this->intTipoRegistro = 0;
		$this->strDescripcion = '';
		$this->bytEstatus = 0;
		$this->strUsuarioAlta = '';
		$this->datFechaAlta = '';
		$this->strUsuarioMod = '';
		$this->datFechaMod = '';
	}

    public function save($userName){

		// hacemos la coneccion a la base de datos
		$db = new Database;
        $db->connectDB();
        
        // guardamos
        $query = "call p_registraBitacora_Cotizacion_PartidaDetalle($this->intTipoRegistro, $this->intCotizacion, $this->bytRevision, $this->intPartida, $this->intDetalle, '$this->strDescripcion', '$userName', @result)";

        $results = $db->get_Rows($query); 
		
		if(!$results) {
			$db->closeDB();
			$arr = array('success' => 98, 'msg' => "Se encontraron problemas al procesar la información.", 'sql' => $query);
			return $arr;
		}

		$query = "select @result AS Result";

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

	public function getBitacoraCotizacion_PartidaDetalle($intCotizacion, $bytRevision, $intPartida, $intDetalle, $intTipoRegistro){

		// hacemos la coneccion a la base de datos
        global $db;
        $db->connectDB();

        $query = "SELECT 
                    intTipoRegistro,
                    strTipoRegistro,
                    strDescripcion,
                    strNombreCompleto_UsuarioAlta AS strUsuarioAlta,
                    f_Fecha_Formato(datFechaAlta, 'longdatetime') AS strFechaAlta,
                    F_DETERMINA_TIEMPOTRANSCURRIDO(now(), datFechaAlta) as strTiempoTranscurrido
                 FROM view_cotizaciones_p_detalle_bitacora 
                 WHERE intCotizacion = $intCotizacion
				 AND bytRevision = $bytRevision
				 AND intPartida = $intPartida
				 AND intDetalle = $intDetalle
                 ".($intTipoRegistro == 0 ? '' : 'AND intTipoRegistro = '.$intTipoRegistro.'')."
                 ".($intTipoRegistro == 9 ? 'AND bytActivo = 1' : '')."
                 ORDER BY datFechaAlta DESC";
                 
        // obtenemos registros
        $results = $db->get_Rows($query); 
       
        // cerramos conexión
        $db->closeDB();
        
        // validamos
        if(!$results){  
            // error
            $data = array('success' => 98, 'msg' => "Se encontraron problemas al procesar la información.", 'sql' => $query);     
        }
        else {
            // Return JSON data
            $data = array();
            $dataResults = array();

            $data['success'] = 1;
            $data['msg'] = "";
            $data['results'] = array();
                        
            while ($row = mysqli_fetch_assoc($results)) {
                $dataResults[] = array(
                'intTipoRegistro' => $row['intTipoRegistro'],
                'strTipoRegistro' => $row['strTipoRegistro'],
                'strDescripcion' => $row['strDescripcion'],
                'strUsuarioAlta' => $row['strUsuarioAlta'],
                'strFechaAlta' => $row['strFechaAlta'],
                'strTiempoTranscurrido' => $row['strTiempoTranscurrido']
                );
            }

            $data['results'] = $dataResults;
            
        }

        echo json_encode($data);

	}

    public function getBitacoraConflictosCotizacion_PartidaDetalle($intCotizacion, $bytRevision, $intPartida, $intDetalle){

		// hacemos la coneccion a la base de datos
        global $db;
        $db->connectDB();

        $query = "SELECT 
                    intTipoRegistro,
                    strTipoRegistro,
                    strDescripcion,
                    strNombreCompleto_UsuarioAlta AS strUsuarioAlta,
                    f_Fecha_Formato(datFechaAlta, 'longdatetime') AS strFechaAlta,
                    F_DETERMINA_TIEMPOTRANSCURRIDO(now(), datFechaAlta) as strTiempoTranscurrido
                 FROM view_cotizaciones_p_detalle_bitacora 
                 WHERE intCotizacion = $intCotizacion
				 AND bytRevision = $bytRevision
				 AND intPartida = $intPartida
				 AND intDetalle = $intDetalle
                 AND intTipoRegistro = 9
                 AND bytActivo = 1
                 ORDER BY datFechaAlta DESC";
                 
        // obtenemos registros
        $results = $db->get_Rows($query); 
       
        // cerramos conexión
        $db->closeDB();
        
        // validamos
        if(!$results){  
            // error
            $data = array('success' => 98, 'msg' => "Se encontraron problemas al procesar la información.", 'sql' => $query);     
        }
        else {
            // Return JSON data
            $data = array();
            $dataResults = array();

            $data['success'] = 1;
            $data['msg'] = "";
            $data['results'] = array();
                        
            while ($row = mysqli_fetch_assoc($results)) {
                $dataResults[] = array(
                'intTipoRegistro' => $row['intTipoRegistro'],
                'strTipoRegistro' => $row['strTipoRegistro'],
                'strDescripcion' => $row['strDescripcion'],
                'strUsuarioAlta' => $row['strUsuarioAlta'],
                'strFechaAlta' => $row['strFechaAlta'],
                'strTiempoTranscurrido' => $row['strTiempoTranscurrido']
                );
            }

            $data['results'] = $dataResults;
            
        }

        echo json_encode($data);

	}

}

?>
