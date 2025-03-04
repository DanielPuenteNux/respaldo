<?php

session_name('_erp');
session_start();

require_once("../controllerHelper.php");

function getCotizacionesJSON(){

	if (isset($_POST['filters'])){

        $objFilters = $_POST['filters'];
        
		// asociamos filtros
		$filters = new filters();        
		array_push($filters->filters, new filter("p1", "intCotizacion","in"));
		array_push($filters->filters, new filter("p2", "intCliente","in"));
		array_push($filters->filters, new filter("p3", "intEstatusCot","in"));
		array_push($filters->filters, new filter("p4", "date(datFechaAlta)",">="));
		array_push($filters->filters, new filter("p5", "date(datFechaAlta)","<="));
		array_push($filters->filters, new filter("p6", "strOrdenProd","in"));
			
        //Construyo las ordenes delimitadas por comas en strings separados para poder filtrarlas en el IN
        if(strlen($objFilters['p6']) > 0){
            $splitedOPS = explode(",", $objFilters['p6']);
            $ops = "'" . implode("', '", $splitedOPS) ."'";
            $objFilters['p6'] = $ops;
        }

		// obtenemos sql
		$strFilters = $filters->getSQL($objFilters);

		if (strlen($strFilters) > 0){

			$strFilters = "WHERE " . $strFilters . " AND bytEstatus <> 9 ";

		}
		else
		{
			$strFilters = "WHERE bytEstatus <> 9 ";
		}

		// preparamos estatuto
		$query = "SELECT count(distinct intCotizacion) as Tot FROM tblcotizaciones 
					$strFilters
						AND intCotizacion <> 0";

		// hacemos la coneccion a la base de datos
		global $db;
		$db->connectDB();

		// obtenemos registros
		$row = $db->getValuesDB($query); 

		// cerramos conexión
		$db->closeDB();
				
		// obtenemso total
		$total = $row["Tot"];

		// preparamos paginación
		if (number_format($_POST['length']) > 0) $limits = "LIMIT " . $_POST['start'] . "," . $_POST['length'];

		// preparamos ORDER
		if(isset($_POST["order"])){

			// obtenemos columnas
			$arrCols = $_POST['columns'];

			// inicializamos
			$colName = "";
			$orderDir = $_POST['order']['0']['dir'];

			// evaluamos
			switch ($arrCols[$_POST['order']['0']['column']]['data'])
			{
				case 'strFecha':
					$colName = 'datFecha';
					break;

				default:
					$colName = $arrCols[$_POST['order']['0']['column']]['data'];                    
			}

			$orderby = 'ORDER BY ' . $colName . ' ' . $orderDir;
		}
		else{
			$orderby = 'ORDER BY intCotizacion DESC';
		}

		$query = "SELECT DISTINCT
                `intCotizacion`,
                `bytRevision`,
                `strCliente`,
                `intTipoCotizacion`,
                `intEstatusCot`,
                `strEstatusCot`,
                `strFecha`,
                `bytEstatus`,
                `strUsuarioAlta`,
                `datFechaAlta`,
                `strFechaAlta`
            FROM view_consulta_cotizaciones  
                $strFilters
                AND intCotizacion <> 0 
                AND bytEstatus <> 9
            $orderby $limits";
            
		// hacemos conexión
		$db->connectDB();

		// Ejeuctar el SQL
		$results = $db->get_Rows($query);

		//cerramos coneccion
		$db->closeDB();

		// validamos
		if(!$results){ 
			
			// error        
			$arr = array('success' => 98, 'msg' => "Se encontraron problemas al procesar la información.", 'sql' => $query);
			echo json_encode($arr);
		}
			

			// obtenemos total de renglones
			$totRows = mysqli_num_rows($results);
			
			// Return JSON data
			$data = array();		
			$data['success'] = 1;
			$data['draw'] = intval($_POST['draw']);
			$data['recordsFiltered'] =  intval($total);
			$data['recordsTotal'] = intval($total);
			$data['data'] = mysqli_fetch_all($results, MYSQLI_ASSOC);
			$data['sql'] = $query;
			
			// regresamos rows
			echo json_encode($data);
	}
	else{

		// error
		$arr = array('success' => 98, 'msg' => "Parámetros incorrectos.");
		echo json_encode($arr);
	}
}

function saveCotizacion(){

    // validamos
    if (isset($_SESSION['userID']) && isset($_POST['json']) && isset($_SESSION['userName'])){

        $json = $_POST['json'];
        $userName = $_SESSION['userName'];
        $userID = $_SESSION['userID'];

        // creamos objeto
        $obj = new ventas_cotizaciones();
        $obj->loadJSON($json);

        // guardamos cambios
        if ($obj->intCotizacion > 0) $newCotizacion = false; 
        else $newCotizacion = true;  

        $arr = $obj->save($userName);

        // if($arr['success'] == 1){

        //     // checamos si era nueva requisicion
        //     if ($newRequisicion){
                
        //         // enviamos notificación
        //         $ws = new WS_Client();
        //         $ws->sendRequisicion_Update($userID, $obj->intRequisicion, $obj->intFolio, $ws::event_Requisicion_Nueva);
        //     }
        // }

        echo json_encode($arr);
    }
    else{

        // error        
        $arr = array('success' => 0, 'msg' => "Parámetros incorrectos.");
        echo json_encode($arr);
    }
}

function newRevCotizacion(){

    // validamos
    if (isset($_SESSION['userID']) && isset($_POST['json']) && isset($_SESSION['userName'])){

        $json = $_POST['json'];
        $userName = $_SESSION['userName'];
        $userID = $_SESSION['userID'];

        // creamos objeto
        $obj = new ventas_cotizaciones();
        $obj->loadJSON($json);
        
        // guardamos cambios
        $arr = $obj->newRevCotizacion($userName);

        // if($arr['success'] == 1){

        //     // checamos si era nueva requisicion
        //     if ($newRequisicion){
                
        //         // enviamos notificación
        //         $ws = new WS_Client();
        //         $ws->sendRequisicion_Update($userID, $obj->intRequisicion, $obj->intFolio, $ws::event_Requisicion_Nueva);
        //     }
        // }

        echo json_encode($arr);
    }
    else{

        // error        
        $arr = array('success' => 0, 'msg' => "Parámetros incorrectos.");
        echo json_encode($arr);
    }
}

function getCotizacionByID()
{
    // validamos
    if (isset($_POST['p1']) && isset($_POST['p2'])){
    
        $id = $_POST['p1'];
        $bytRevision = $_POST['p2'];
                
        // creamos objeto
        $obj = new ventas_cotizaciones();
        $arr = $obj->getCotizacionByID($id, $bytRevision);

        // regresamos info
        echo json_encode($arr);

    }
    else{

        // error        
        $arr = array('success' => 0, 'msg' => "Parámetros incorrectos.");
        echo json_encode($arr);
    }
}

function aprobar_Cotizacion(){

    // validamos
    if (isset($_SESSION['userID']) && isset($_SESSION['userName']) && isset($_POST['json'])){
        
        $json = $_POST['json'];
        $userName = $_SESSION['userName'];
        $userID = $_SESSION['userID'];

        // validamos
        if (!isset($_SESSION['userID']) || $userID == 0){
			$arr = array('success' => 98, 'msg' => "Su usuario ha expirado por favor reinicie el sistema.");
			return $arr;
		}
        

        // creamos objeto
        $obj = new ventas_cotizaciones();
        $obj->loadJSON($json);

        // guardamos cambios        
        $arr = $obj->save($userName);
        
        // validamos
        if($arr['success'] == 1){
            
            // enviamos remision
            $arr = $obj->aprobarCotizacion($obj->intCotizacion, $obj->bytRevision, $userName);
            
            // validamos
            // if($arr['success'] == 1){
                
            //     // enviamos notificación
            //     $msgClient = new Messages_Client();
            //     $msgClient->sendRemision_Update($userID, $obj->intRemision, $msgClient::event_Remision_Enviada);

            //     // obtenemos objeto
            //     global $generales;

            //     // recorremos OPs
            //     foreach($obj->detalle as $item){

            //         // checamos si es una OP
            //         if ($item->intOrdenProd > 0){

            //             // creamos objeto
            //             $op = new OrdenProduccion();
            //             $op->intOrdenProd = $item->intOrdenProd;
            //             $arrCarga = $op->get_Carga_SubProceso(20, 1);
                        
            //             //Revisamos si hay carga en el proceso de logistica (preparación) para que pueda embarcar
            //             if($arrCarga['success'] == 1 && $arrCarga['dblCantidad'] > 0){

            //                 // guardamos cambios 
            //                 $arr = $op->embarcar($item->dblCantidadOP, $userName);
                    
            //                 // validamos
            //                 if($arr['success'] == 1){
                    
            //                     // enviamos notificación					 
            //                     $msgClient->sendOP_Update($userID, $arr['intOrdenProd'], $msgClient::event_OP_MovimientoCarga, $arr['strOrdenProd'], $arr['strSubject'], $arr['stMessage'], $arr['strJSON']);
            //                 }
            //                 else{

            //                     // preparamos parámetros
            //                     $subject = "Error al enviar remisión: " . $obj->intRemision;
            //                     $body = "No fue posible embarcar la OP:" . $op->intOrdenProd . "\n" . $op->errorsMsg_List("\n");
                                
            //                     // notificamos
            //                     $generales->sendMail_Text_Admin($subject, $body);
            //                 }
            //             }
                        
            //         }
            //     }
            // }
        }
        
        echo json_encode($arr);

    }
    else{

        // error        
        $arr = array('success' => 0, 'msg' => "Parámetros incorrectos.");
        echo json_encode($arr);
    }
}

function rechazar_Cotizacion(){

    // validamos
    if (isset($_SESSION['userID']) && isset($_SESSION['userName']) && isset($_POST['json']) && isset($_POST['p1'])){
        
        $json = $_POST['json'];
        $userName = $_SESSION['userName'];
        $userID = $_SESSION['userID'];
        $intEstatusCot = $_POST['p1'];

        // validamos
        if (!isset($_SESSION['userID']) || $userID == 0){
			$arr = array('success' => 98, 'msg' => "Su usuario ha expirado por favor reinicie el sistema.");
			return $arr;
		}
        

         // creamos objeto
         $obj = new ventas_cotizaciones();
         $obj->loadJSON($json);

         // guardamos cambios        
         $arr = $obj->save($userName);
         
         // validamos
         if($arr['success'] == 1){
             
            // enviamos remision
            $arr = $obj->rechazarCotizacion($obj->intCotizacion, $obj->bytRevision, $intEstatusCot, $userName);
             
             // validamos
            //  if($arr['success'] == 1){
 
            //      // enviamos notificación
            //      $ws = new WS_Client();
            //      $ws->sendRequisicion_Update($userID, $obj->intRequisicion, $obj->intFolio, $ws::event_Requisicion_Rechazada,'','', $intUserAprobador);
            //  }
         }
        
        echo json_encode($arr);

    }
    else{

        // error        
        $arr = array('success' => 0, 'msg' => "Parámetros incorrectos.");
        echo json_encode($arr);
    }
}

function cancelar_Cotizacion(){

    // validamos
    if (isset($_SESSION['userID']) && isset($_SESSION['userName']) && isset($_POST['json'])){
        
        $json = $_POST['json'];
        $userName = $_SESSION['userName'];
        $userID = $_SESSION['userID'];
        
        // creamos objeto
        $obj = new ventas_cotizaciones();
        $obj->loadJSON($json);
        
        // cancelamos requisición
        $arr = $obj->cancelarCotizacion($obj->intCotizacion, $obj->bytRevision, $userName);
        
        echo json_encode($arr);

    }
    else{

        // error        
        $arr = array('success' => 0, 'msg' => "Parámetros incorrectos.");
        echo json_encode($arr);
    }
}


function cargaDocsPartidaCotizacion(){

    //validamos
    if (isset($_SESSION['userID']) && isset($_SESSION['userName']) && isset($_POST['json'])){

        $json = $_POST['json'];
        $userName = $_SESSION['userName'];
        
        // creamos objeto
        $obj = new ventas_cotizaciones_partida();
        $obj->loadJSON($_POST['json']);
        
        $arr = $obj->saveArchivoDocPartida($userName);

    }else{

        //error        
        $arr = array('success' => 0, 'msg' => "Parámetros incorrectos.");
    }

    echo json_encode($arr);
}

function cargaDocsPartidaDetalleCotizacion(){

    //validamos
    if (isset($_SESSION['userID']) && isset($_SESSION['userName']) && isset($_POST['json'])){

        $json = $_POST['json'];
        $userName = $_SESSION['userName'];
        
        // creamos objeto
        $obj = new ventas_cotizaciones_partida_detalle();
        $obj->loadJSON($_POST['json']);
        
        $arr = $obj->saveArchivoDocPartidaDetalle($userName);

    }else{

        //error        
        $arr = array('success' => 0, 'msg' => "Parámetros incorrectos.");
    }

    echo json_encode($arr);
}

function deleteDocCotizacion(){

    // validamos
    if (isset($_SESSION['userID']) && isset($_SESSION['userName']) && isset($_POST['json'])){

        $json = $_POST['json'];
        $userName = $_SESSION['userName'];

        // creamos objeto
        $obj = new ventas_cotizaciones_partida_docs();
        $obj->loadJSON($json);

        if($obj){

            $pathFile = dirname(__DIR__, 2) . "/files/" . $obj->strFileName;

            // guardamos cambios        
            $arr = $obj->deleteDocPartida($userName);
            
            if($arr['success'] == 1){
                unlink($pathFile);
            }
        }
        else
        {
            // error        
            $arr = array('success' => 0, 'msg' => "formato incorrecto.");
        }
    }
    else{

        // error        
        $arr = array('success' => 0, 'msg' => "Parámetros incorrectos.");
    }
    echo json_encode($arr);
}

function editDocPartidaCotizacion(){

    // validamos
    if (isset($_SESSION['userID']) && isset($_SESSION['userName']) && isset($_POST['json'])){

        $json = $_POST['json'];
        $userName = $_SESSION['userName'];

        // creamos objeto
        $obj = new ventas_cotizaciones_partida_docs();
        $obj->loadJSON($json);

        // guardamos cambios        
        $arr = $obj->editDocPartida($userName);
    }
    else{

        // error        
        $arr = array('success' => 0, 'msg' => "Parámetros incorrectos.");
    }
    echo json_encode($arr);
}

function addItemPartida_Bitacora() {
    // validamos
    if (isset($_SESSION['userName']) && isset($_SESSION['userID']) && isset($_POST['p1']) && isset($_POST['p2']) && isset($_POST['p3']) && isset($_POST['p4'])){

        $userName = $_SESSION['userName'];
        $userID = $_SESSION['userID'];
        
        $bitacoraCP = new ventas_cotizaciones_partida_bitacora();
        $bitacoraCP->intCotizacion = $_POST['p1'];
        $bitacoraCP->bytRevision = $_POST['p2'];
        $bitacoraCP->intPartida = $_POST['p3'];
        $bitacoraCP->intTipoRegistro = $_POST['p4'];
        $bitacoraCP->strDescripcion = $_POST['p5'];

        $arr = $bitacoraCP->save($userName);

        // if($arr['success'] == 1) {
            
        //     // enviamos notificación
        //     $msgClient = new Messages_Client();
        //     $msgClient->sendOP_Update($userID, $intOrdenProd, $msgClient::event_OP_Bitacora, $strOrdenProd); 
        // }
        
        // regresamos info
        echo json_encode($arr);

    }
    else{

        // error        
        $arr = array('success' => 0, 'msg' => "Parámetros incorrectos.");
        echo json_encode($arr);
    }
}

function addItemPartidaDetalle_Bitacora() {
    // validamos
    if (isset($_SESSION['userName']) && isset($_SESSION['userID']) && isset($_POST['p1']) && isset($_POST['p2']) && isset($_POST['p3']) && isset($_POST['p4'])){

        $userName = $_SESSION['userName'];
        $userID = $_SESSION['userID'];
        
        $bitacoraCP = new ventas_cotizaciones_partida_detalle_bitacora();
        $bitacoraCP->intCotizacion = $_POST['p1'];
        $bitacoraCP->bytRevision = $_POST['p2'];
        $bitacoraCP->intPartida = $_POST['p3'];
        $bitacoraCP->intDetalle = $_POST['p4'];
        $bitacoraCP->intTipoRegistro = $_POST['p5'];
        $bitacoraCP->strDescripcion = $_POST['p6'];

        $arr = $bitacoraCP->save($userName);

        // if($arr['success'] == 1) {
            
        //     // enviamos notificación
        //     $msgClient = new Messages_Client();
        //     $msgClient->sendOP_Update($userID, $intOrdenProd, $msgClient::event_OP_Bitacora, $strOrdenProd); 
        // }
        
        // regresamos info
        echo json_encode($arr);

    }
    else{

        // error        
        $arr = array('success' => 0, 'msg' => "Parámetros incorrectos.");
        echo json_encode($arr);
    }
}

function getBitacoraCotizacion_Partida()
{
    // validamos
    if (isset($_POST['p1']) && isset($_POST['p2']) && isset($_POST['p3'])){
    
        $intCotizacion = $_POST['p1'];
        $bytRevision = $_POST['p2'];
        $intPartida = $_POST['p3'];
                
        // creamos objeto
        $obj = new ventas_cotizaciones_partida_bitacora();
        $arr = $obj->getBitacoraCotizacion_Partida($intCotizacion, $bytRevision, $intPartida);

        // regresamos info
        echo $arr;

    }
    else{

        // error        
        $arr = array('success' => 0, 'msg' => "Parámetros incorrectos.");
        echo json_encode($arr);
    }
}

function setConflictoPartidaDetalle()
{
    // validamos
    if (isset($_POST['p1']) && isset($_POST['p2']) && isset($_POST['p3']) && isset($_POST['p4']) && isset($_POST['p5'])){
                
        $userName = $_SESSION['userName'];
        $userID = $_SESSION['userID'];

        // creamos objeto
        $obj = new ventas_cotizaciones_partida_detalle();
        $obj->intCotizacion = $_POST['p1'];
        $obj->bytRevision = $_POST['p2'];
        $obj->intPartida = $_POST['p3'];
        $obj->intDetalle = $_POST['p4'];
        $obj->bytConflicto = $_POST['p5'];
        $arr = $obj->setConflictoPartidaDetalle($userName);

        // regresamos info
        echo json_encode($arr);

    }
    else{

        // error        
        $arr = array('success' => 0, 'msg' => "Parámetros incorrectos.");
        echo json_encode($arr);
    }
}

function getBitacoraCotizacion_PartidaDetalle()
{
    // validamos
    if (isset($_POST['p1']) && isset($_POST['p2']) && isset($_POST['p3']) && isset($_POST['p4'])){
    
        $intCotizacion = $_POST['p1'];
        $bytRevision = $_POST['p2'];
        $intPartida = $_POST['p3'];
        $intDetalle = $_POST['p4'];
        $intTipoRegistro = $_POST['p5'];
                
        // creamos objeto
        $obj = new ventas_cotizaciones_partida_detalle_bitacora();
        $arr = $obj->getBitacoraCotizacion_PartidaDetalle($intCotizacion, $bytRevision, $intPartida, $intDetalle, $intTipoRegistro);

        // regresamos info
        echo $arr;

    }
    else{

        // error        
        $arr = array('success' => 0, 'msg' => "Parámetros incorrectos.");
        echo json_encode($arr);
    }
}

function getTiposCotizacion_JSON()
{

    $term = (isset($_GET['strTerm']) ? $_GET['strTerm'] : "");

    // hacemos la conexión a la base de datos
    $db = new Database();
    $db->connectDB();

    // obtenemos registros
    $query = "SELECT intTipoCotizacion, strNombre FROM tbltipos_cotizacion WHERE strNombre LIKE '%" . $term . "%' AND bytEstatus != 9 ORDER BY intTipoCotizacion;";

    // obtenemos registros
    $results = $db->get_Rows($query);

    // cerramos conexión
    $db->closeDB();

    // inicializamos
    $data = array();
    $data['results'] = array();

    // validamos
    if (!$results) {

        // error
        $data['results'][] = array('id' => -1, 'text' => 'Error !');
    } else {

        //
        while ($row = mysqli_fetch_assoc($results)) {
            $data['results'][] = array(
                'id' => $row['intTipoCotizacion'],
                'text' => $row['strNombre']
            );
        }
    }

    echo json_encode($data);
}

function getEstatusCotizacion_JSON()
{

    $term = (isset($_GET['strTerm']) ? $_GET['strTerm'] : "");

    // hacemos la conexión a la base de datos
    $db = new Database();
    $db->connectDB();

    // obtenemos registros
    $query = "SELECT intEstatusCot, strNombre FROM tblestatus_cotizaciones WHERE strNombre LIKE '%" . $term . "%' AND bytEstatus != 9 ORDER BY intEstatusCot;";

    // obtenemos registros
    $results = $db->get_Rows($query);

    // cerramos conexión
    $db->closeDB();

    // inicializamos
    $data = array();
    $data['results'] = array();

    // validamos
    if (!$results) {

        // error
        $data['results'][] = array('id' => -1, 'text' => 'Error !');
    } else {

        //
        while ($row = mysqli_fetch_assoc($results)) {
            $data['results'][] = array(
                'id' => $row['intEstatusCot'],
                'text' => $row['strNombre']
            );
        }
    }

    echo json_encode($data);
}

function getEstatusRechazarCotizacion_JSON()
{

    $term = (isset($_GET['strTerm']) ? $_GET['strTerm'] : "");

    // hacemos la conexión a la base de datos
    $db = new Database();
    $db->connectDB();

    // obtenemos registros
    $query = "SELECT intEstatusCot, strNombre FROM tblestatus_cotizaciones WHERE strNombre LIKE '%" . $term . "%' AND bytEstatus != 9 AND intEstatusCot IN (1,2) ORDER BY intEstatusCot;";

    // obtenemos registros
    $results = $db->get_Rows($query);

    // cerramos conexión
    $db->closeDB();

    // inicializamos
    $data = array();
    $data['results'] = array();

    // validamos
    if (!$results) {

        // error
        $data['results'][] = array('id' => -1, 'text' => 'Error !');
    } else {

        //
        while ($row = mysqli_fetch_assoc($results)) {
            $data['results'][] = array(
                'id' => $row['intEstatusCot'],
                'text' => $row['strNombre']
            );
        }
    }

    echo json_encode($data);
}

function getAplicacionTintaCotizacion_JSON()
{

    $term = (isset($_GET['strTerm']) ? $_GET['strTerm'] : "");

    // hacemos la conexión a la base de datos
    $db = new Database();
    $db->connectDB();

    // obtenemos registros
    $query = "SELECT intAplicacionTinta, strNombre FROM tblaplicacion_tinta WHERE strNombre LIKE '%" . $term . "%' AND bytEstatus != 9 ORDER BY intAplicacionTinta;";

    // obtenemos registros
    $results = $db->get_Rows($query);

    // cerramos conexión
    $db->closeDB();

    // inicializamos
    $data = array();
    $data['results'] = array();

    // validamos
    if (!$results) {

        // error
        $data['results'][] = array('id' => -1, 'text' => 'Error !');
    } else {

        //
        while ($row = mysqli_fetch_assoc($results)) {
            $data['results'][] = array(
                'id' => $row['intAplicacionTinta'],
                'text' => $row['strNombre']
            );
        }
    }

    echo json_encode($data);
}

function getTiposPartidaCotizacion_JSON()
{

    $term = (isset($_GET['strTerm']) ? $_GET['strTerm'] : "");

    // hacemos la conexión a la base de datos
    $db = new Database();
    $db->connectDB();

    // obtenemos registros
    $query = "SELECT intTipoPartida, strNombre FROM tbltipos_partida WHERE strNombre LIKE '%" . $term . "%' AND bytEstatus != 9 ORDER BY intTipoPartida;";

    // obtenemos registros
    $results = $db->get_Rows($query);

    // cerramos conexión
    $db->closeDB();

    // inicializamos
    $data = array();
    $data['results'] = array();

    // validamos
    if (!$results) {

        // error
        $data['results'][] = array('id' => -1, 'text' => 'Error !');
    } else {

        //
        while ($row = mysqli_fetch_assoc($results)) {
            $data['results'][] = array(
                'id' => $row['intTipoPartida'],
                'text' => $row['strNombre']
            );
        }
    }

    echo json_encode($data);
}

function getTiposServicioCotizacion_JSON()
{

    $term = (isset($_GET['strTerm']) ? $_GET['strTerm'] : "");

    // hacemos la conexión a la base de datos
    $db = new Database();
    $db->connectDB();

    // obtenemos registros
    $query = "SELECT intTipoServicio, strNombre FROM tbltipos_servicio WHERE strNombre LIKE '%" . $term . "%' AND bytEstatus != 9 ORDER BY intTipoServicio;";

    // obtenemos registros
    $results = $db->get_Rows($query);

    // cerramos conexión
    $db->closeDB();

    // inicializamos
    $data = array();
    $data['results'] = array();

    // validamos
    if (!$results) {

        // error
        $data['results'][] = array('id' => -1, 'text' => 'Error !');
    } else {

        //
        while ($row = mysqli_fetch_assoc($results)) {
            $data['results'][] = array(
                'id' => $row['intTipoServicio'],
                'text' => $row['strNombre']
            );
        }
    }

    echo json_encode($data);
}

function getVendedores_JSON()
{

    $term = (isset($_GET['strTerm']) ? $_GET['strTerm'] : "");

    // hacemos la conexión a la base de datos
    $db = new Database();
    $db->connectDB();

    // obtenemos permisos de la opción
    $user = new Users_User();
    $user->getUser_Opcion($_SESSION['userID'], 'ventas_cotizaciones');

    // obtenemos el rol del usuario
    $userRol = new Users_User_Role();
    $userRol = $user->roles[0];

    if (!$userRol->hasPermission_CurrentOption($userRol::action_ListAll)) {

        // obtenemos registros
        $query = "SELECT intUserID, strNombreCompleto FROM tblusers WHERE strNombreCompleto LIKE '%" . $term . "%' AND intUserID = " . $_SESSION['userID'] . " ORDER BY strNombreCompleto;";
    } else {
        // obtenemos registros
        $query = "SELECT intUserID, strNombreCompleto FROM tblusers WHERE strNombreCompleto LIKE '%" . $term . "%' AND bytEstatus != 9 AND (intUserRole = 35 OR intUserRole = 6 OR intUserRole = 37 OR intUserRole = 12 OR intUserRole = 58 OR intUserRole = 50 OR intUserRole = 36) ORDER BY strNombreCompleto;";
    }

    // obtenemos registros
    $results = $db->get_Rows($query);

    // cerramos conexión
    $db->closeDB();

    // inicializamos
    $data = array();
    $data['results'] = array();

    // validamos
    if (!$results) {

        // error
        $data['results'][] = array('id' => -1, 'text' => 'Error !');
    } else {

        //
        while ($row = mysqli_fetch_assoc($results)) {
            $data['results'][] = array(
                'id' => $row['intUserID'],
                'text' => $row['strNombreCompleto']
            );
        }
    }

    echo json_encode($data);
}

function getArticulosAlmacen_JSON(){

    $term = (isset($_GET['strTerm']) ? $_GET['strTerm'] : "");

    // hacemos la conexión a la base de datos
    $db = new Database();
    $db->connectDB();

    // obtenemos registros
    $query = "SELECT intArticulo, 
                 CONCAT(strIcon_HTML, '<div class=\"text-articulo\" style=\"display:inline-block;\">' , strCodigo, ' - ', strDescripcion, '</div>') 
                 AS strNombre 
          FROM view_articulos_almacen 
          WHERE bytEstatus <> 9 
          AND strArticulo LIKE '%" . $term . "%'";
          
    // obtenemos registros
    $results = $db->get_Rows($query);
    
    // cerramos conexión
    $db->closeDB();

    // inicializamos
    $data = array();
    $data['results'] = array();

    // validamos
    if (!$results) {

        // error
        $data['results'][] = array('id' => -1, 'text' => 'Error !');
    } else {

        //
        while ($row = mysqli_fetch_assoc($results)) {
            $data['results'][] = array(
                'id' => $row['intArticulo'],
                'text' => $row['strNombre']
            );
        }
    }
    
    echo json_encode($data);
}

?>
