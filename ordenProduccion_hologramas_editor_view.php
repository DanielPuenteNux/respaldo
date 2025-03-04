<div class="page-body" id="ordenProduccion_hologramas_editor" style="display: none;">
	<div class="container-fluid">
		<div class="page-header">
			<div class="row">
				<div class="col">
					<div class="page-header-left">
						<h3>OP Hologramas</h3>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a onclick="showContent('optWelcome');"><i data-feather="home"></i></a></li>
							<li class="breadcrumb-item">OP Hologramas</li>
						</ol>
					</div>
				</div>
				<!-- Bookmark Start-->
				<div class="col">
					<div class="bookmark pull-right">
						<ul>
							<li data-bs-toggle="tooltip" title="Regresar" data-bs-original-title="Regresar"><a onclick="goBack();"><i data-feather="arrow-left-circle"></i></a></li>
						</ul>
					</div>
				</div>
				<!-- Bookmark Ends-->
			</div>
		</div>
	</div>

	<!-- Container-fluid starts-->
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
					<div id="headerOP" class="card-header b-l-primary border-3 card-has-subheader">
						<div class="row" style="align-items: center;">
							<div class="col-sm-12">
								<h5>Nueva OP</h5>
							</div>
						</div>
						<h6 class="card-subheader"></h6>
					</div>
					<div class="card-body">

						<div id="pnlInfohologramasOP" class="pnlInfohologramasOP">
							<div class="formElement" data-ParamName="intOperacionOP" data-getSelect2Text="strOperacionOP" data-ValueType="int" data-ControlID="ophOperacion" data-Required="1">
								<p class="formLabel">Operación:</p>
								<select id="ophOperacion" class="AjaxSelect form-control opOperacion"></select>
							</div>
							<div class="formElement" data-ParamName="intTipoOperacionOP" data-getSelect2Text="strTipoOperacionOP" data-ValueType="int" data-IsSelect="1" data-ControlID="ophTipoOperacion" data-Required="1">
								<p class="formLabel">Tipo de operación:</p>
								<select class="AjaxSelect opTipoOperacion" id="ophTipoOperacion"></select>
							</div>
							<div class="formElement" data-ParamName="intTipoMuestraOP" data-getSelect2Text="strTipoMuestraOP" data-ValueType="int" data-ControlID="ophgTipoMuestra" data-Required="1">
								<p class="formLabel">Tipo de muestra:</p>
								<select class="AjaxSelect opgTipoMuestra" id="ophgTipoMuestra"></select>
							</div>
							<div class="formElement" data-ParamName="intCliente" data-getSelect2Text="strCliente" data-ValueType="int" data-ControlID="ophgCliente" data-Required="1" style="width: 350px;">
								<p class="formLabel">Cliente:</p>
								<select class="AjaxSelect opgCliente" id="ophgCliente"></select>
							</div>
							<div style="position:relative; display:inline-flex;">
								<div class="input-group" style="margin-right: 10px;">
									<div class="formElement" data-ParamName="intFormatoCliente" data-getSelect2Text="strFormatoCliente" data-ValueType="int" data-ControlID="ophgFormatoCliente" data-Required="1" style="width: 350px; margin-right: 0px !important;">
										<p class="formLabel">Forma:</p>
										<select class="AjaxSelect opgFormatoCliente" id="ophgFormatoCliente"></select>
									</div>
									<button type="button" class="btn btn-outline-secondary btn-add-forma" style="padding: 2px; width: 33px; max-height: 45px; margin-top: 23.81px"><i class="icofont icofont-plus"></i></button>
								</div>
							</div>
							<div class="formElement" data-ParamName="intVendedor" data-getSelect2Text="strVendedor" data-ValueType="int" data-ControlID="ophgEjecutivo" data-Required="1">
								<p class="formLabel">Vendedor:</p>
								<select class="AjaxSelect opgEjecutivo" id="ophgEjecutivo"></select>
							</div>
							<div class="formElement" data-ParamName="datFechaEntrega" data-ValueType="str" data-ControlID="ophFechaEntrega">
								<p class="formLabel">Fecha de entrega:</p>
								<input type="text" id="ophFechaEntrega" class="form-control text IsDate">
							</div>

							<div class="tiposProdHOrdenesContainer" style="display: flex; align-items: flex-start; justify-content: flex-start;">
								<div class="formElement" data-ParamName="intTipoProduccion" data-getSelect2Text="strTipoProduccion" data-ValueType="int" data-ControlID="ophgTipoProdOP" data-Required="1" style="flex: 0 0 150px;">
									<p class="formLabel">Tipo de producción:</p>
									<select class="AjaxSelect ophgTipoProdOP" id="ophgTipoProdOP"></select>
								</div>
								<div class="formElement" id="proveedorFilialOp" style="display:none; width:250px; flex: 0 0 250px;" data-ParamName="intProveedor" data-ValueType="int" data-ControlID="ophProv" data-Required="0">
									<p class="formLabel">Proveedor:</p>
									<select class="AjaxSelect ophProv" id="ophProv"></select>
								</div>
								<div class="infoTypeOrden" style="display:none;">
									<i class="fa fa-info-circle" style="color:#0077d7;"></i>
									<div class="clientOrden descriptionOrdenes" style="display:none;">Corresponde con un pedido de cliente, la materia prima se procesa en nuestras instalaciones y el producto terminado se entrega en almacén para posteriormente ser embarcado por el área de logística.</div>
									<div class="internaOrden descriptionOrdenes" style="display:none;">Se utiliza para controlar la producción interna, no se embarca.</div>
									<div class="filialOrden descriptionOrdenes" style="display:none;">Corresponde con un pedido de cliente, la materia prima es procesada por nuestros filiales y el producto terminado se recibe en almacén para posteriormente ser embarcado por el área de logística.</div>
								</div>
							</div>

							<br><br>

							<div class="dt-buttons btn-group flex-wrap" style="margin-bottom:20px; float:none !important;">
								<button class="btn btn-primary btn-action-datatable" type="button" id="loadFilesOC" onclick='docsBox(currOrdenProduccion.docs, CargaDocumentoOP, $("#pnlInfoOP").attr("id"), saveDocumentoOrdenProduccion, "docOP", true)'><span><i data-feather="file" style="width:18px; aspect-ratio:1;"></i>Documentos</span></button>

							</div>
						</div>

						<div class="tabbed-card" style="margin-left:15px; margin-top:20px;">
							<ul class="pull-right nav nav-tabs border-tab" id="tabsInventario" role="tablist" style="position:relative; float:none; margin-bottom: 0px; ">
								<li class="nav-item">
									<div style="font-size:14px; padding: 6px 15px; cursor: pointer;" data-bs-toggle="tab" href="#tabHoloOPGeneral" class="nav-link active" role="tab" aria-selected="true">
										General
									</div>
									<div class="material-border"></div>
								</li>
								<li class="nav-item">
									<div style="font-size:14px; padding: 6px 15px; cursor: pointer;" data-bs-toggle="tab" href="#tabHoloOPMateriales" class="nav-link" role="tab" aria-selected="true">
										Materiales
									</div>
									<div class="material-border"></div>
								</li>
								<li class="nav-item">
									<div style="font-size:14px; padding: 6px 15px; cursor: pointer;" data-bs-toggle="tab" href="#tabHoloOPAcabado" class="nav-link" role="tab" aria-selected="true">
										Acabado
									</div>
									<div class="material-border"></div>
								</li>
								<li class="nav-item">
									<div style="font-size:14px; padding: 6px 15px; cursor: pointer;" data-bs-toggle="tab" href="#tabHoloOPDiseño" class="nav-link" role="tab" aria-selected="true">
										Diseño
									</div>
									<div class="material-border"></div>
								</li>
							</ul>
							<br>
						</div>

						<div class="tab-content" id="tabsInfoOrdenProdhologramas" style="padding-left:30px;">

							<div class="tab-pane fade active show" id="tabHoloOPGeneral">

								<div class="formElement" data-ParamName="strDescripcion" data-ValueType="str" data-ControlID="ophgDesc" data-Required="1" style="width:100%; padding-right:25px;">
									<p class="formLabel">Breve descripción del producto:</p>
									<textarea type="text" id="ophgDesc" class="form-control text"></textarea>
								</div>

								<div class="formElement" data-ParamName="dblCantidad" data-ValueType="dbl" data-ControlID="ophgCantidad" data-minValue="0.0001" data-Required="1" style="width:195px;">
									<p class="formLabel">Cantidad:</p>
									<div class="input-group">
										<input type="text" id="ophgCantidad" class="form-control text" style="flex:0 0 80px; display:inline-block;">
										<div style="flex:0 0 110px; display:inline-block;"><select class="AjaxSelect opgUMedida" id="ophUMedidaOP"></select></div>
									</div>
								</div>
								<div class="formElement" data-ParamName="intUMedidaOP" data-getSelect2Text="strUMedidaOP" data-ValueType="int" data-ControlID="ophUMedidaOP" data-Required="1" style="display:none;">
									<!-- NO ELIMINAR, NECESARIO PARA TOMAR EL VALOR DE UMedidaOP -->
								</div>

								<div class="formElement" data-ParamName="dblAncho" data-ValueType="dbl" data-ControlID="ophgAncho" data-minValue="0.0001" data-Required="1" style="width:100px;">
									<p class="formLabel">Ancho:</p>
									<input type="text" id="ophgAncho" class="form-control text">
								</div>

								<div class="formElement" data-ParamName="dblLargo" data-ValueType="dbl" data-ControlID="ophgLargo" data-minValue="0.0001" data-Required="1" style="width:100px;">
									<p class="formLabel">Largo:</p>
									<input type="text" id="ophgLargo" class="form-control text">
								</div>

								<div class="formElement" data-ParamName="intUMedida" data-getSelect2Text="strUMedida" data-ValueType="int" data-ControlID="ophUMedidaMedidas" data-Required="1" style="width:120px;">
									<p class="formLabel">U.Medida:</p>
									<select class="AjaxSelect" id="ophUMedidaMedidas"></select>
								</div>

								<div class="formElement" data-ParamName="dblOtraCantidad" data-ValueType="dbl" data-ControlID="ophgOtraCantidad" data-minValue="0.0001" data-Required="0" style="width:195px;">
									<p class="formLabel">Otra cantidad:</p>
									<div class="input-group">
										<input type="text" id="ophgOtraCantidad" class="form-control text" style="flex:0 0 80px; display:inline-block;">
										<div style="flex:0 0 110px; display:inline-block;"><select class="AjaxSelect opUMedida" id="ophUMedidaOtraCant"></select></div>
									</div>
								</div>
								<div class="formElement" data-ParamName="intUMedidaOtraCant" data-getSelect2Text="strUMedidaOtraCant" data-ValueType="int" data-ControlID="ophUMedidaOtraCant" data-minValue="0" data-Required="0" style="display:none;">
									<!-- NO ELIMINAR, NECESARIO PARA TOMAR EL VALOR DE ophUMedidaOtraCant -->
								</div>

								<div class="formElement" data-ParamName="dblCantPzasPieCuad" data-ValueType="dbl" data-ControlID="ophgCantPiezasPieCuad" data-minValue="0.0001" data-Required="0" style="width:210px;">
									<p class="formLabel">Cant. piezas x pie<sup>2</sup> :</p>
									<input type="text" id="ophgCantPiezasPieCuad" class="form-control text">
								</div>

								<div class="formElement" data-ParamName="strImagenHolograma" data-ValueType="str" data-ControlID="ophgImagen" data-Required="1" style="width: 181px;">
									<p class="formLabel">Imagen a producir:</p>
									<input type="text" id="ophgImagen" class="form-control text">
								</div>

								<div class="formElement" data-ParamName="intDireccionMaterial" data-getSelect2Text="strDireccionMaterial" data-ValueType="int" data-ControlID="ophgDireccionMaterial" data-Required="1" style="width: 215px;">
									<p class="formLabel">Dirección material:</p>
									<select class="AjaxSelect ophgDireccionMaterial" id="ophgDireccionMaterial"></select>
								</div>

								<div class="formElement salidaConToggle" data-ParamName="strSalidaCon" data-ValueType="str" data-ControlID="ophgSalidaCon" data-Required="0" style="display:none;">
									<p class="formLabel">Salida con:</p>
									<input type="text" id="ophgSalidaCon" class="form-control text">
								</div>

							</div>

							<div class="tab-pane fade" id="tabHoloOPMateriales">

								<div class="formElement" data-ParamName="intMaterialHolo" data-getSelect2Text="strMaterialHolo" data-ValueType="int" data-ControlID="ophgMaterialHolo" data-Required="1">
									<p class="formLabel">Material:</p>
									<select class="AjaxSelect ophgMaterialHolo" id="ophgMaterialHolo"></select>
								</div>

								<div class="hdivider"></div>

								<div id="wrapperMaterialHolo" style="margin-left:30px; margin-right:30px;">
									<div class="formElement" data-ParamName="intMaterialHolo_Color" data-getSelect2Text="strMaterialHolo_Color" data-ValueType="int" data-ControlID="ophMaterialColor" data-minValue="0" data-Required="1">
										<p class="formLabel">Color:</p>
										<select class="AjaxSelect ophMaterialColor" id="ophMaterialColor"></select>
									</div>
									<div class="formElement" data-ParamName="intMaterialHolo_Calidad" data-getSelect2Text="strMaterialHolo_Calidad" data-ValueType="int" data-ControlID="ophMaterialCalidad" data-minValue="0" data-Required="1">
										<p class="formLabel">Calidad:</p>
										<select class="AjaxSelect ophMaterialCalidad" id="ophMaterialCalidad"></select>
									</div>
									<div class="formElement" data-ParamName="intMaterialHolo_Espesor" data-getSelect2Text="strMaterialHolo_Espesor" data-ValueType="int" data-ControlID="ophMaterialEspesor" data-minValue="0" data-Required="1">
										<p class="formLabel">Espesor:</p>
										<select class="AjaxSelect ophMaterialEspesor" id="ophMaterialEspesor"></select>
									</div>
									<div class="formElement" data-ParamName="strMedidasCortes" data-ValueType="str" data-ControlID="ophMedidasCortesMaterial" data-minValue="0" data-Required="0">
										<p class="formLabel">Medidas de los cortes:</p>
										<input type="text" class="form-control" id="ophMedidasCortesMaterial">
									</div>
									<div class="formElement" data-ParamName="intMedidaDelCentro" data-getSelect2Text="strMedidaDelCentro" data-ValueType="int" data-ControlID="ophMedidaCentro" data-minValue="0" data-Required="0">
										<p class="formLabel">Medida del centro:</p>
										<select class="AjaxSelect ophMedidaCentro" id="ophMedidaCentro"></select>
									</div>
									<div class="formElement toggleOtroMedidaCentro" data-ParamName="strOtraMedidaDelCentro" data-ValueType="str" data-ControlID="ophOtraMedidaCentro" data-minValue="0" data-Required="0" style="display:none;">
										<p class="formLabel">Valor medida del centro:</p>
										<input type="text" class="form-control" id="ophOtraMedidaCentro">
									</div>
								</div>
							</div>

							<div class="tab-pane fade" id="tabHoloOPAcabado">
								<div class="card-header" style="padding:20px;">
									<div class="row" style="align-items: center; margin-bottom:10px;">
										<div class="col-sm-3">
											<h5>Acabado etiqueta</h5>
										</div>
										<div class="col-sm-1">
											<div class="formElement" data-ParamName="bytAcabadoEtiqueta" data-ValueType="chk" data-ControlID="ophBytAcabadoEtiqueta" data-Required="0" style="width:100%; margin-bottom:0px;">
												<div class="d-inline-flex flex-row justify-content-start align-items-center" style="gap:10px">
													<div class="icon-state">
														<label class="switch">
															<input type="checkbox" id="ophBytAcabadoEtiqueta" unchecked><span class="switch-state"></span>
														</label>
													</div>
													<span></span>
												</div>
											</div>
										</div>
									</div>
									<div class="formElement" data-ParamName="intTipoProductoFinal" data-getSelect2Text="strTipoProductoFinal" data-ValueType="int" data-ControlID="ophProductoFinal" data-Required="1">
										<p class="formLabel">Producto final:</p>
										<select class="AjaxSelect ophProductoFinal" id="ophProductoFinal"></select>
									</div>
									<div class="formElement" data-ParamName="intCantTipoProductoFinal" data-ValueType="int" data-ControlID="ophCantProductoFinal" data-Required="1">
										<p class="formLabel">Cantidad por planilla o rollo:</p>
										<input type="text" class="form-control" id="ophCantProductoFinal">
									</div>
								</div>

								<div id="wrapperAcabadoHolo" style="margin-top:30px;">
									<div id="wrapperSuajesHolo">
										<div class="formElement" data-ParamName="bytSuajes" data-ValueType="chk" data-ControlID="ophbytSuajesHolo" data-Required="1" style="width:160px; padding-top:28px; margin-bottom:0px;">
											<div class="d-inline-flex flex-row justify-content-start align-items-center" style="gap:10px">
												<div class="icon-state">
													<label class="switch">
														<input disabled type="checkbox" id="ophbytSuajesHolo" unchecked><span class="switch-state"></span>
													</label>
												</div>
												<span>Suajes</span>
											</div>
										</div>
										<div class="formElement" data-ParamName="intFormaSuaje" data-getSelect2Text="strFormaSuaje" data-ValueType="int" data-ControlID="ophFormaSuaje" data-Required="1">
											<p class="formLabel">Forma de suaje:</p>
											<select class="AjaxSelect ophFormaSuaje" id="ophFormaSuaje"></select>
										</div>
										<div class="formElement" data-ParamName="bytSuajeSeguridad" data-ValueType="chk" data-ControlID="ophbytSuajeSeguridadHolo" data-Required="1" style="padding-top:28px; margin-bottom:0px;">
											<div class="d-inline-flex flex-row justify-content-start align-items-center" style="gap:10px">
												<div class="icon-state">
													<label class="switch">
														<input disabled type="checkbox" id="ophbytSuajeSeguridadHolo" unchecked><span class="switch-state"></span>
													</label>
												</div>
												<span>Suaje de seguridad</span>
											</div>
										</div>
									</div>
									<div id="wrapperFoliosHolo">
										<div class="formElement" data-ParamName="bytFolios" data-ValueType="chk" data-ControlID="ophbytFoliosHolo" data-Required="0" style="width:160px; padding-top:28px; margin-bottom:0px;">
											<div class="d-inline-flex flex-row justify-content-start align-items-center" style="gap:10px">
												<div class="icon-state">
													<label class="switch">
														<input disabled type="checkbox" id="ophbytFoliosHolo" unchecked><span class="switch-state"></span>
													</label>
												</div>
												<span>Folios</span>
											</div>
										</div>
										<div class="formElement" data-ParamName="strFolioIni" data-ValueType="str" data-ControlID="ophFolioIni" data-Required="0" style="width:150px;">
											<p class="formLabel">Del:</p>
											<input type="text" class="form-control" id="ophFolioIni">
										</div>
										<div class="formElement" data-ParamName="strFolioFin" data-ValueType="str" data-ControlID="ophFolioFin" data-Required="0" style="width:150px;">
											<p class="formLabel">Al:</p>
											<input type="text" class="form-control" id="ophFolioFin">
										</div>
										<div class="formElement" data-ParamName="intTipoFolioOP" data-getSelect2Text="strTipoFolioOP" data-ValueType="int" data-ControlID="ophTipoFolio" data-minValue="0" data-Required="0">
											<p class="formLabel">Tipo de folio:</p>
											<select class="AjaxSelect ophTipoFolio" id="ophTipoFolio"></select>
										</div>

										<div class="formElement" data-ParamName="bytCodigoBarras" data-ValueType="chk" data-ControlID="ophbytCodigoBarras" data-Required="0" style="padding-top:28px; margin-bottom:0px;">
											<div class="d-inline-flex flex-row justify-content-start align-items-center" style="gap:10px">
												<div class="icon-state">
													<label class="switch">
														<input disabled type="checkbox" id="ophbytCodigoBarras" unchecked><span class="switch-state"></span>
													</label>
												</div>
												<span>Codigo de barras</span>
											</div>
										</div>
									</div>
									<div id="wrapperPlecaHolo">
										<div class="formElement" data-ParamName="bytPleca" data-ValueType="chk" data-ControlID="ophbytPleca" data-Required="1" style="width:160px; padding-top:28px; margin-bottom:0px;">
											<div class="d-inline-flex flex-row justify-content-start align-items-center" style="gap:10px">
												<div class="icon-state">
													<label class="switch">
														<input disabled type="checkbox" id="ophbytPleca" unchecked><span class="switch-state"></span>
													</label>
												</div>
												<span>Pleca</span>
											</div>
										</div>
										<div class="formElement" data-ParamName="bytPlecaHorizontal" data-ValueType="int" data-ControlID="ophPlecaFolios" data-minValue="0" data-Required="1">
											<p class="formLabel">Pleca:</p>
											<select disabled class="ophPlecaFolios" id="ophPlecaFolios">
												<option value="1">Pleca horizontal</option>
												<option value="0">Pleca vertical</option>
											</select>
										</div>
									</div>
								</div>

							</div>

							<div class="tab-pane fade" id="tabHoloOPDiseño">

								<div class="formElement" data-ParamName="intDisenoHolograma" data-getSelect2Text="strDisenoHolograma" data-ValueType="int" data-ControlID="ophDisenoHolo" data-minValue="0" data-Required="0">
									<p class="formLabel">Diseño de holograma:</p>
									<select class="AjaxSelect ophDisenoHolo" id="ophDisenoHolo"></select>
								</div>
								<div class="formElement" data-ParamName="intTipoHolograma" data-getSelect2Text="strTipoHolograma" data-ValueType="int" data-ControlID="ophTipoHolograma" data-minValue="0" data-Required="0">
									<p class="formLabel">Tipo de holograma:</p>
									<select class="AjaxSelect ophTipoHolograma" id="ophTipoHolograma"></select>
								</div>
							</div>
						</div>

						<br>

						<div id="pnlInstrucciones_OP" class="formElement pnlInstrucciones_OP" style="width:100%; height:auto; margin-top:20px; padding-right:25px; padding-left:25px;">
							<p class="formLabel">Instrucciones especiales:</p>
							<textarea id="ophInstrucciones" class="form-control"></textarea>
						</div>

						<div class="formElement pnlNotasOP_Rechazo" style="width:100%; height:auto; margin-top:20px; padding-right:25px; padding-left:25px; display:none;">
							<p class="formLabel">Notas:</p>
							<textarea class="form-control opNotas" readonly></textarea>
						</div>

						<br><br><br><br>

						<ul class="ulFormBtns">
							<li><button type="button" class="btn btn-primary btn-iconed btnBack" onclick="goBack();"><span class="btn-icon"><i data-feather="arrow-left"></i></span>Regresar</button></li>
							<li><button type="button" class="btn btn-primary btn-iconed btnSave" onclick="validateOrdenProduccion();"><span class="btn-icon"><i data-feather="plus-circle"></i></span>Guardar</button></li>
							<?php

							// obtenemos permisos de la opción        
							$user = new Users_User();
							$user->getUser_Opcion($_SESSION['userID'], 'consulta_ordenProduccion');

							// obtenemos el rol del usuario        
							$userRol = new Users_User_Role();
							$userRol = $user->roles[0];

							// checamos si es rechazar
							if ($userRol->hasPermission_CurrentOption($userRol::action_Edit)) {
								echo '<li><button type="button" class="btn btn-primary btn-iconed buttonRejectOP" onclick="rechazar_OP();"><span class="btn-icon"><i data-feather="corner-up-left"></i></span>Marcar <span class="estatusOPText"></span></button></li>';
							}

							// checamos si es Aprobación
							if ($userRol->hasPermission_CurrentOption($userRol::action_Aprove)) {
								echo '<li><button type="button" class="btn btn-primary btn-iconed buttonAproveOP" onclick="aprobar_OP();"><span class="btn-icon"><i data-feather="check"></i></span>Aprobar</button></li>';
							}

							// checamos si es Liberar
							if ($userRol->hasPermission_CurrentOption($userRol::action_Authorize)) {
								echo '<li><button type="button" class="btn btn-primary btn-iconed buttonAuthorizeOP" onclick="liberar_OP();"><span class="btn-icon"><i data-feather="check"></i></span>Liberar</button></li>';
							}

							// checamos si es admin
							/*if ($userRol->hasPermission_CurrentOption($userRol::action_FullControl)){
									echo '<li><button type="button" class="btn btn-primary btn-iconed btnSave_Aprob" onclick="save_OP(1);"><span class="btn-icon"><i data-feather="check"></i></span>Aprobar</button></li>';
									echo '<li><button type="button" class="btn btn-primary btn-iconed btnSave_Aprob" onclick="liberar_OP(1);"><span class="btn-icon"><i data-feather="check"></i></span>Liberar</button></li>';
								}*/
							?>
						</ul>

					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Container-fluid Ends-->

</div>
