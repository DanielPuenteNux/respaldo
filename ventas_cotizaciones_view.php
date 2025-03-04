<div class="page-body" id="ventas_cotizaciones" style="display:none;">
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <div class="page-header-left">
                        <h3>Cotizaciones</h3>
                        <ol class="breadcrumb">
							<li class="breadcrumb-item"><a onclick="showContent('optWelcome');"><i data-feather="home"></i></a></li>
							<li class="breadcrumb-item">Cotizaciones</li>							
						</ol>
                    </div>
                </div>
                <!-- Bookmark Start-->
                <div class="col">
                    <div class="bookmark pull-right">
                        <ul>
                            <li><a href="#" data-bs-toggle="tooltip" title="Regresar" data-bs-original-title="Regresar"><i data-feather="arrow-left-circle"></i></a></li>                      
                        </ul>
                    </div>
                </div>
                <!-- Bookmark Ends-->
            </div>
        </div>
    </div>
    <div class="col-md-12" id="consultaCotizacionesWrapper">
        <div class="card">
            <div class="card-header b-l-danger border-3">
                <h5 class="pull-left">Consulta</span></h5>
            </div>
            <div class="card-body">
                
                <br>
                <div class="tab-content" id="gridCotizaciones">
                    <div class="tab-pane fade active show">

                        <div class="accordion accordion-flush" id="filtersCotizaciones">
                            <div class="accordion-item" style="position: relative;">
                                <h2 class="accordion-header" id="headingOne"><button class="accordion-button custom-button" type="button" data-bs-target="#filtersCotizaciones_Body" data-bs-toggle="collapse" aria-expanded="true" aria-controls="filtersCotizaciones_Body">Filtros</button></h2>
                                <div id="filtersCotizaciones_Body" style="border-bottom: 0px;" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#filtersCotizaciones">
                                    <div class="accordion-body">

                                        <br>

                                        <div class="formElement" data-ParamName="p1" data-ValueType="str" data-ControlID="vcFolio" data-Required="0">
											<p class="formLabel">Folio: <span style="font-size:9px">(separado por comas)</span></p>
											<input type="text" id="vcFolio" class="form-control text" >
										</div>

                                        <div class="formElement" data-ParamName="p2" data-ValueType="mul" data-ControlID="vcClienteC" data-Required="0">
											<p class="formLabel">Cliente:</p>
											<select multiple id="vcClienteC" class="AjaxSelect form-control vcCliente"></select>
										</div>

                                        <div class="formElement" data-ParamName="p3" data-ValueType="mul" data-ControlID="vcEstatusCotizacion">
                                            <p class="formLabel">Estatus cotización:</p>
                                            <select multiple id="vcEstatusCotizacion" id="vcEstatusCotizacion" class="AjaxSelect form-control vcEstatusCotizacion">
                                                <?php 
                                                    $query = "SELECT intEstatusCotizacion, strNombre FROM tblestatus_cotizaciones WHERE bytEstatus <> 9 AND intEstatusCotizacion > 0;";
                                                    $db = new Database();
                                                    echo $db->get_SelectOptions($query, 1); 
                                                ?>
                                            </select>
                                        </div>

                                        <div class="formElement" data-ParamName="p4" data-ValueType="dat" data-ControlID="vcFechaInitCotizacion">
                                            <label class="formLabel" for="recipient-name">Fecha de alta inicial:</label>
                                            <input type="text" class="form-control text IsDate" id="vcFechaInitCotizacion" data-multiple-dates-separator>
                                        </div>

                                        <div class="formElement" data-ParamName="p5" data-ValueType="dat" data-ControlID="vcFechaFinCotizacion">
                                            <label class="formLabel" for="recipient-name">Fecha de alta final:</label>
                                            <input type="text" class="form-control text IsDate" id="vcFechaFinCotizacion" data-multiple-dates-separator>
                                        </div>

                                        <div class="formElement" id="vcOrdenProdElement" data-ParamName="p6" data-ValueType="mul" data-ControlID="vcOrdenProd" style="width:100%;">
                                            <p class="formLabel">Orden de producción:</p>	
											<select class="text" id="vcOrdenProd" multiple style="width:100%;" data-placeholder="" data-allow-clear="true"></select>
                                        </div>

                                        <div class="ulFormBtns">
                                            <li><button type="button"  class="btn btn-primary" onclick="datatableCotizaciones()">Buscar</button></li>
                                            <li><button type="button" class="btn btn-secondary" onclick="clearFilters('filtersCotizaciones_Body')">Limpiar campos</button></li>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="scrollVC"></div>

                        <br><br>

                        <div class="hdivider"></div>

                        <div class="row">
                            <div class="col-12 text-end">
                                <button type="button" style="padding-bottom:0px; padding-top:0px;" class="btn btn-primary" onclick='showCotizacion()'>
                                    <span class="btn-icon"><i data-feather="plus"></i></span>
                                    Nueva
                                </button>
                            </div>
                        </div>

                        <br>
                        <br>

                        <table id="tablaCotizaciones" class="display table" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#Cotización</th>
                                    <th>Revisión</th>
                                    <th>Cliente</th>
                                    <th>Estatus Cotización</th>
                                    <th>Fecha de Cotización</th>
                                    <th>Fecha de alta</th>
                                    <th>Usuario alta</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    <br>
</div>
