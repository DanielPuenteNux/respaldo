<div class="page-body" id="ventas_cotizacion" style="display: none;">
	<div class="container-fluid">
		<div class="page-header">
			<div class="row">
				<div class="col">
					<div class="page-header-left">
						<h3>Cotización</h3>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a onclick="showContent('optWelcome');"><i data-feather="home"></i></a></li>
							<li class="breadcrumb-item">Cotización</li>							
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
					<div class="card-header b-l-primary border-3 card-has-subheader">
						<div class="row" style="align-items: center;">
							<div class="col-sm-3" style="width: 215px;">
								<h5>Nueva cotización</h5>
							</div>
							<div class="col-sm-2">
								<div class="icon-urgent" style="display:none; height:40px; background-size:100%; width: 30px;"><div class="dotted-animation" style="right:6px; top:39px;"><span class="animate-circle"></span><span class="main-circle"></span></div></div>
							</div>
						</div>
						<h6 class="card-subheader">Card subtitle</h6>
						<div class="card-header-right">
							<ul class="list-unstyled card-option">
								<li><i class="icofont icofont-simple-left"></i></li>                        
								<li><i class="icofont icofont-maximize full-card"></i></li>
								<li><i class="icofont icofont-minus minimize-card"></i></li>
							</ul>
						</div>
					</div>
					<div class="card-body">
					
						<div id="pnlLabels_Cotizacion" class="pnlLabels" style="display:none;">
							<div class="mb-2 field">
								<label class="col-form-label lbl">Fecha:</label>
								<div class="form-control-plaintext fw-bold data fecha"></div>								
							</div>						
							<div class="mb-2 field">
								<label class="col-form-label lbl">Solicitante:</label>											
								<div class="form-control-plaintext fw-bold data sol"></div>								
							</div>
							<div class="mb-2 field">
								<label class="col-form-label lbl">Departamento:</label>										
								<div class="form-control-plaintext fw-bold data depto"></div>								
							</div>
							<div class="mb-2 field">
								<label class="col-form-label lbl">Sub Departamento:</label>										
								<div class="form-control-plaintext fw-bold data subdepto"></div>								
							</div>
							<div class="hdivider"></div>
						</div>

						<?php

							// obtenemos permisos de la opción        
							$user = new Users_User();
							$user->getUser_Opcion($_SESSION['userID'], 'ventas_cotizaciones');
					
							// obtenemos el rol del usuario        
							$userRol = new Users_User_Role();
							$userRol = $user->roles[0];
							
							// checamos si va a ser tipo formas o tipo nüx
							($userRol->hasPermission_CurrentOption($userRol::action_ListFormasInteligentes) ? $intTipoCotizacion = 1 : '');
							($userRol->hasPermission_CurrentOption($userRol::action_ListNÜX) ? $intTipoCotizacion = 2 : '');
						?>

						<div id="pnlInfoCotizacion" class="pnlInfoCotizacion">

							<div class="formElement" data-ParamName="strDescripcion" data-ValueType="str" data-ControlID="vcDescCot" data-Required="1" style="width:100%;">
								<p class="formLabel">Descripción:</p>
								<input id="vcDescCot" class="vcDescCot form-control"></input>																		
							</div>
							<div class="formElement" data-ParamName="intCliente" data-ValueType="int" data-ControlID="vcCliente" data-Required="1" style="width:290px;">
								<p class="formLabel">Cliente:</p>
								<select id="vcCliente" id="vcCliente" class="AjaxSelect form-control vcCliente"></select>
							</div>
							<div class="formElement" data-ParamName="intVendedor" data-ValueType="int" data-ControlID="vcVendedor" data-Required="1" style="width:200px;">
								<p class="formLabel">Vendedor:</p>
								<select id="vcVendedor" id="vcVendedor" class="AjaxSelect form-control vcVendedor"></select>
							</div>
							<!-- Revisamos si es tipo NUX -->
							<?php
								if($intTipoCotizacion == 1){
									echo '
										<div class="formElement" data-ParamName="intTipoServicio" data-ValueType="int" data-ControlID="vcTipoServicio" data-Required="0" style="width:180px;">
											<p class="formLabel">Tipo de servicio:</p>
											<select id="vcTipoServicio" id="vcTipoServicio" class="AjaxSelect form-control vcTipoServicio"></select>
										</div>
									';
								}
							?>
							

							<div id="tipoCot" data-intTipoCotizacion=<?php echo $intTipoCotizacion ?> style="display:none;"></div>
							
							<div class="preciosCotizacion">
								<div class="formElement" style="width:250px;" data-ParamName="intMoneda" data-ValueType="int" data-ControlID="vcMoneda" data-Required="1">
									<p class="formLabel">Moneda:</p>
									<select id="vcMoneda" class="AjaxSelect form-control vcMoneda"></select>						
								</div>
								<div class="formElement" data-ParamName="dblTipoCambio" data-ValueType="dbl" data-ControlID="vcTipoCambio" data-Required="1" style="width:100px;">
									<p class="formLabel">T. cambio:</p>
									<input id="vcTipoCambio" class="vcTipoCambio form-control"></input>																		
								</div>
								<div class="formElement" data-ParamName="dblSubTotal" data-ValueType="dbl" data-ControlID="vcSubTotal" data-Required="1" style="width:100px;">
									<p class="formLabel">Precio:</p>
									<input id="vcSubTotal" class="vcSubTotal form-control"></input>																		
								</div>
								<div class="formElement" data-ParamName="dblTotal" data-ValueType="dbl" data-ControlID="vcTotal" data-Required="1" style="width:100px;">
									<p class="formLabel">Precio + IVA:</p>
									<input id="vcTotal" class="vcTotal form-control" readonly></input>
								</div>
								<div class="formElement" data-ParamName="dblUtilidad" data-ValueType="dbl" data-ControlID="vcUtilidad" data-Required="1" style="width:100px;">
									<p class="formLabel">Utilidad:</p>
									<input id="vcUtilidad" class="vcUtilidad form-control"></input>																		
								</div>
								<div class="formElement" data-ParamName="dblPtjeUtilidad" data-ValueType="dbl" data-ControlID="vcPtjeUtilidad" data-Required="1" style="width:100px;">
									<p class="formLabel">%Utilidad:</p>
									<input id="vcPtjeUtilidad" class="vcPtjeUtilidad form-control"></input>																		
								</div>
								<div class="formElement" data-ParamName="intDiasEntrega" data-ValueType="int" data-ControlID="vcDiasEntrega" data-Required="1" style="width:100px;">
									<p class="formLabel">Días proceso:</p>
									<input id="vcDiasEntrega" class="vcDiasEntrega form-control"></input>																		
								</div>
								<div class="formElement" data-ParamName="datFechaEntrega" data-ValueType="str" data-ControlID="vcFechaEntrega" data-Required="1" style="width:200px;">
									<p class="formLabel">Fecha entrega estimada:</p>
									<input id="vcFechaEntrega" class="vcFechaEntrega form-control IsDate"></input>																		
								</div>
							</div>
							
							<div class="hdivider"></div>
						</div>

						<button type="button" style="float:right;" class="btn btn-primary" onclick="new_Partida()">Agregar partida</button>
			
						<div id="container_ItemsCotizacion" style="width:100%; padding-bottom:15px;">
							
							<div id="wrapper_ItemsCotizacion" style="width: 100%;"></div>

							<div class="itemRow itemCotizacion" id="vc0" style="display:none; margin-top: 20px; border-top:none;">
								<div class="card panel shadow-0 border" style="width:100%;">

									<div class="panel_Header">
										
										<div class="tabbed-card" style="position:absolute; top:0px;">
											<ul class="nav nav-tabs border-tab" id="tabsCotizacion" role="tablist" style="right: unset;">
												<li class="nav-item">
													<div class="nav-link active tabPartida" data-bs-toggle="tab" href="#tabPartidaCotizacion" role="tab" aria-selected="true">
														Partida
													</div>
													<div class="material-border"></div>
												</li>
												<li class="nav-item">
													<div class="nav-link tabPartidaDesarrollo" data-bs-toggle="tab" href="#tabDetalleCotizacion" role="tab" aria-selected="true">
														Desarrollo
													</div>
													<div class="material-border"></div>
												</li>
												<li class="nav-item">
													<div class="nav-link tabPartidaDocs" data-bs-toggle="tab" href="#tabDocumentosCotizacion" role="tab" aria-selected="true">
														Documentos
													</div>
													<div class="material-border"></div>
												</li>
											</ul>
											<br>
										</div>
										<!-- <div class="btn btn-primary btn-action-datatable ms-3" type="button" style="margin-left:auto !important;" id="loadFilesPartidaC" onclick='docsBox($(this).closest(".itemCotizacion").data("data-obj").docs, CargaDocumentoPartida, $(this).closest(".itemCotizacion").attr("id"), saveDocumentoPartidaCotizacion, "docPartidaCotizacion", true);'><span><i data-feather="file" style="width:18px; aspect-ratio:1;"></i>Documentos</span></div> -->

										<div class="clone-delete-buttons">
											<div class="itemRow_Icon" style="cursor:pointer;" onclick="clonePartidaCotizacion(this);">
												<i data-feather="copy" style="width: 19px; height: 18px; margin-top: 3px; color: #3e3e3e;"></i>
											</div>

											<div class="itemRow_Icon" style="cursor:pointer; margin-left:10px;" onclick="removePartidaObj(this);">
												<i data-feather="trash-2" style="width: 19px; height: 18px; margin-top: 3px; color: #3e3e3e;"></i>
											</div>
										</div>

										<div class="buttons-action-cot">

											<div class="itemRow_Icon" style="cursor:pointer;" onclick="moveItem($(this).closest('.itemCotizacion').attr('id'), false);">
												<i data-feather="arrow-down" style="width: 19px; height: 18px; margin-top: 3px; color: #3e3e3e;"></i>
											</div>

											<div class="itemRow_Icon" style="cursor:pointer; margin-left:5px;" onclick="moveItem($(this).closest('.itemCotizacion').attr('id'), true);">
												<i data-feather="arrow-up" style="width: 19px; height: 18px; margin-top: 3px; color: #3e3e3e;"></i>
											</div>

											<div class="accordion accordion-flush" id="filtersPartida">
												<div class="accordion-item accordion-button-cotizacion" style="position: relative;">
													<h2 class="accordion-header" id="headingOne">
														<button class="accordion-button accordion-partida custom-button" onclick="toggleAccordionPartida(this)" style="padding: 4px; border: 0px !important;" type="button" data-bs-target="#filtersPartida_Body" data-bs-toggle="collapse" aria-expanded="true" aria-controls="filtersRemisiones_Body"></button>
													</h2>
												</div>
											</div>
										</div>
										
									</div>
									
									<div class="tab-content" id="tabsPartidaCotizacion">

										<div class="tab-pane fade active show tab-pane-partida" id="tabPartidaCotizacion">
											<div class="panel_Body card-body text-dark">

												<div class="buttons-action-partida">

													<div class="background-icon-bitacora-cot">
														<div class="icon-bitacora icon-bitacora-cot" onclick="showBitacora_CP_Modal(this, 8)"></div>
													</div>
												</div>
												
												<div class="formElement" data-ParamName="intTipoPartida" data-IsClassID="1" data-ValueType="int" data-ControlID="vcTipoPartida" data-Required="0" style="width:100%; display:none;">
													<input type="text" value="<?php echo $intTipoCotizacion ?>" class="vcTipoPartida text form-control" style="width:100%;"></input>																		
												</div>

												<div class="formElement" data-ParamName="strDescripcion" data-IsClassID="1" data-ValueType="str" data-ControlID="vcDescP" data-Required="0" style="width:100%;">
													<p class="formLabel">Descripción:</p>
													<textarea class="vcDescP text form-control" style="width:100%;"></textarea>																		
												</div>

												<div class="priceInfoPartida">
													<div class="formElement" data-ParamName="dblPrecioUnitario" data-IsClassID="1" data-ValueType="dbl" data-ControlID="vcPrecioUnitarioPartida" data-Required="0" data-MinValue="0.001" style="width:130px;">
														<div class="lock-container">
															<p class="formLabel" style="width:80px;">P. Unitario</p>
															<div class="icon-lock-closed precioUnitarioLock" onclick="switchLock(this, 'precioTotalLock')" style="cursor:pointer;"></div>
														</div>
														
														<input type="text" disabled class="form-control readOnlyNux numeric vcPrecioUnitarioPartida">
														
													</div>
													<div class="formElement" data-ParamName="dblPrecioTotal" data-IsClassID="1" data-ValueType="dbl" data-ControlID="vcPrecioTotalPartida" data-Required="0" data-MinValue="0.001" style="width:125px;">
															<div class="lock-container">
																<p class="formLabel">Precio total</p>
																<div class="icon-lock-open icon-lock-connector precioTotalLock" onclick="switchLock(this, 'precioUnitarioLock')" style="cursor:pointer; --width-connector-lock:143px;"></div>
															</div>
														<input type="text" class="form-control readOnlyNux numeric vcPrecioTotalPartida">
													</div>
													<div class="formElement" data-ParamName="dblPtjeUtilidad" data-IsClassID="1" data-ValueType="dbl" data-ControlID="vcPtjeUtilidadPartida" data-Required="0" data-MinValue="0.001" style="width:120px;">
														<div class="lock-container">
															<p class="formLabel">%Utilidad</p>
															<div class="icon-lock-closed ptjeUtilidadLock" onclick="switchLock(this, 'utilidadLock')" style="cursor:pointer;"></div>
														</div>

														<input type="text" disabled class="form-control readOnlyNux numeric vcPtjeUtilidadPartida">
														
													</div>
													<div class="formElement" data-ParamName="dblUtilidad" data-IsClassID="1" data-ValueType="dbl" data-ControlID="vcUtilidad" data-Required="0" data-MinValue="0.001" style="width:120px;">

														<div class="lock-container">
															<p class="formLabel">Utilidad</p>
															<div class="icon-lock-open icon-lock-connector utilidadLock" onclick="switchLock(this, 'ptjeUtilidadLock')" style="cursor:pointer;"></div>
														</div>

														<input type="text" class="form-control readOnlyNux numeric vcUtilidad">
														
													</div>
													<div class="formElement" data-ParamName="dblDiasEntrega" data-IsClassID="1" data-ValueType="dbl" data-ControlID="vcDiasEntregaPartida" data-Required="0" data-MinValue="0.001" style="width:130px;">
														<p class="formLabel">Dias proceso</p>
														<input type="text" class="form-control numeric vcDiasEntregaPartida">
														
													</div>
													<div class="formElement" data-ParamName="datFechaEntrega" data-IsClassID="1" data-ValueType="str" data-ControlID="vcFechaEntregaPartida" data-Required="0" data-MinValue="0.001" style="width:230px;">
														<p class="formLabel">Fecha entrega</p>
														<input type="text" class="form-control text IsDatePartida vcFechaEntregaPartida">
														
													</div>
												</div>

												<div id="filtersPartida_Body" style="border:0px;" class="accordion-collapse accordion-collapse-partida collapse show">
													<div class="accordion-body" style="padding:0px;">
														<div class="basicInfoPartida">
															<div class="formElement" data-ParamName="dblCantidad" data-IsClassID="1" data-ValueType="dbl" data-ControlID="vcCantidad" data-Required="0" data-MinValue="0.001" style="width:80px;">
																<p class="formLabel">Cantidad</p>
																<input type="text" class="form-control numeric vcCantidad">
															</div>
															<div class="formElement" data-ParamName="dblAncho" data-IsClassID="1" data-ValueType="dbl" data-ControlID="vcAncho" data-minValue="0.0001" data-Required="1" style="width:180px;">

																<div class="formLabel" style="display: flex; justify-content: space-between; width: 82%; height: 25px;">
																	<span>Ancho</span>
																	<span>Largo</span>
																	<span>Alto</span>
																</div>
																<div class="input-group">
																	<input type="text" class="vcAncho form-control text" style="flex:0 0 60px; display:inline-block;">
																	<div style="flex:0 0 60px; display:inline-block;"><input type="text" class="form-control text vcLargo" style="flex:0 0 60px; display:inline-block;"></div>
																	<div style="flex:0 0 60px; display:inline-block;"><input type="text"class="form-control text vcAlto" style="flex:0 0 60px; display:inline-block;"></div>
																</div>
															</div>
															<div class="formElement" data-ParamName="dblLargo" data-IsClassID="1" data-ValueType="dbl" data-ControlID="vcLargo" data-Required="0" data-MinValue="0.001" style="width:80px; display:none;"></div>
															<div class="formElement" data-ParamName="dblAlto" data-IsClassID="1" data-ValueType="dbl" data-ControlID="vcAlto" data-Required="0" data-MinValue="0.001" style="width:80px; display:none;"></div>

															<?php 
																
																//Revisamos si es de formas para mostrar estos campos
																if($intTipoCotizacion == 1){
																	echo '
																		<div class="formElement" data-ParamName="intTantos" data-IsClassID="1" data-ValueType="int" data-ControlID="vcTantos" data-Required="0" style="width:80px;">
																			<p class="formLabel">Tantos</p>
																			<input type="text" class="form-control numeric vcTantos">
																		</div>
																		<div class="formElement" data-ParamName="intAcabadoOP" data-IsClassID="1" data-ValueType="int" data-ControlID="vcAcabado" data-Required="0" style="width:180px;">
																			<p class="formLabel">Acabado</p>
																			<select class="AjaxSelect form-control vcAcabado dontInit"></select>
																		</div>
																		<div class="formElement" data-ParamName="intAplicacionTinta" data-IsClassID="1" data-ValueType="int" data-ControlID="vcAplicacionTinta" data-Required="0" style="width:150px;">
																			<p class="formLabel">Aplicación tinta</p>
																			<select class="AjaxSelect form-control vcAplicacionTinta dontInit"></select>
																		</div>
																		<div class="formElement" data-ParamName="intCantidadTintas" data-IsClassID="1" data-ValueType="int" data-ControlID="vcCantTintas" data-Required="0" style="width:100px;">
																			<p class="formLabel">Cant. tintas</p>
																			<input type="text" class="form-control numeric vcCantTintas">
																		</div>
																	';
																}
															
															?>
															
														</div>
													</div>
												</div>

											</div>
										</div>

										<div class="tab-pane fade tab-pane-partida-desarrollo" id="tabDetalleCotizacion" style="background-color: white;">
											<div id="wrapperDetallePartida" style="width: 100%;">

												<div class="buttons-action">
													<button type="button" class="btn btn-additem" onclick="new_Partida_Detalle(this);">
														<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
													</button>

													<div class="background-icon-bitacora-cot">
														<div class="icon-bitacora icon-bitacora-cot" onclick="showBitacora_CP_Modal(this, 8)"></div>
													</div>
												</div>
												
												<div id="wrapper_HeaderItemsRequisicion" style="margin-left: 15px; margin-top:10px; width: 1215px;">
													<div class="itemHeader itemRow" id="rHeader">
														<div class="itemField" style="width: 590px;">
															<div class="itemField_Data">Artículo</div>
														</div>
														<div class="itemField" style="width: 80px;">
															<div class="itemField_Data data-center">Cantidad</div>
														</div>
														<div class="itemField" style="width: 80px;">
															<div class="itemField_Data data-center">Ancho</div>
														</div>
														<div class="itemField" style="width: 80px;">
															<div class="itemField_Data data-center">Largo</div>
														</div>
														<div class="itemField" style="width: 80px;">
															<div class="itemField_Data data-center">Alto</div>
														</div>
														<div class="itemField" style="width: 110px;">
															<div class="itemField_Data data-center">P. unitario</div>
														</div>
														<div class="itemField" style="width: 130px;">
															<div class="itemField_Data data-center">Precio total</div>
														</div>
														<div class="itemField" style="width: 160px;">
															<div class="itemField_Data data-center">Fecha entrega</div>
														</div>
														<div class="itemField" style="width: 110px;"></div>
													</div>
												</div>

												<div class="wrapperDetalleArticulos"></div>
											</div>
										</div>

										<div class="tab-pane fade tab-pane-partida-docs" id="tabDocumentosCotizacion">

											<div class="buttons-action">
												<button type="button" class="btn btn-additem" id="loadFilesPartidaC" onclick='docsBox("", CargaDocumentoPartida, $(this).closest(".itemCotizacion").attr("id"), saveDocumentoPartidaCotizacion, "docLoadedPartida", true);'>
													<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
												</button>

												<div class="background-icon-bitacora-cot" style="top: 69px;right: 15px;">
													<div class="icon-bitacora icon-bitacora-cot" onclick="showBitacora_CP_Modal(this, 8)"></div>
												</div>
											</div>

											<div class="text-content-empty" style="text-align: center; font-size: 16px; margin-bottom: 40px;">No hay documentos cargados</div>

											<div class="container-image-partida" style="padding:40px; display:none;"></div>
											<div class="docstext" style="margin-left: 24px; display:none;">Otros documentos:</div>
											<div class="container-docs-partida" style="display:none;"></div>
										</div>

									</div>
								</div>
								
							</div>

							<div class="itemRow itemCotizacionDetalle" id="vcd0" style="display:none; border-top:none;">
								<div class="formElement" data-ParamName="strDescripcion" data-IsClassID="1" data-ValueType="str" data-ControlID="vcdDescP" data-Required="0" style="width:100%; display:none;">
									<p class="formLabel">Descripción:</p>
									<textarea class="vcdDescP text form-control" style="width:100%;"></textarea>																		
								</div>
								<div class="articulo-wrapper" style="width:520px; display: flex; align-items: center;">
									
									<div class="formElement" data-ParamName="intArticulo" data-IsClassID="1" data-ValueType="int" data-ControlID="vcdArticulo" data-Required="0" style="width: 490px; margin-left:20px;">

										<input type="hidden" class="AjaxSelect form-control vcdArticulo"></input>

										<div class="itemField_Data data-center vcdLabelArticulo" data-Value="" style="display: flex; align-items: center;">
											<div class="grabber"></div>
											<div class="alert-icon-lines" style="opacity:0.5; cursor:pointer;" onclick="view_BitacoraConflictoPartidaDetalle_Cotizacion(this)"></div>
											<div class="articulo-content" style="display: contents;"></div>
										</div>

									</div>
								</div>
								
								<div class="articulo-info-wrapper">
									<div class="formElement" data-ParamName="dblCantidad" data-IsClassID="1" data-ValueType="dbl" data-ControlID="vcdCantidad" data-Required="0" data-MinValue="0.001" style="width:68px; margin-right:0px;">
										
										<input type="text" class="input-transparent form-control numeric vcdCantidad">
									</div>
									<div class="formElement" data-ParamName="dblAncho" data-IsClassID="1" data-ValueType="dbl" data-ControlID="vcdAncho" data-Required="0" data-MinValue="0.001" style="width:68px; margin-right:0px;">
										
										<input type="text" class="input-transparent form-control numeric vcdAncho">
									</div>
									<div class="formElement" data-ParamName="dblLargo" data-IsClassID="1" data-ValueType="dbl" data-ControlID="vcdLargo" data-Required="0" data-MinValue="0.001" style="width:68px; margin-right:0px;">
										
										<input type="text" class="input-transparent form-control numeric vcdLargo">
									</div>
									<div class="formElement" data-ParamName="dblAlto" data-IsClassID="1" data-ValueType="dbl" data-ControlID="vcdAlto" data-Required="0" data-MinValue="0.001" style="width:68px; margin-right:0px;">
										
										<input type="text" class="input-transparent form-control numeric vcdAlto">
									</div>

									<?php
										if($_SESSION['intUserRole'] == 1){
											echo '
												<div class="priceInfoPartida">
													<div class="formElement" data-ParamName="dblPrecioUnitario" data-IsClassID="1" data-ValueType="dbl" data-ControlID="vcdPrecioUnitarioPartida" data-Required="0" data-MinValue="0.001" style="width:95px; margin-right:0px;">
														
														<input type="text" class="input-transparent form-control numeric vcdPrecioUnitarioPartida">
														
													</div>
													<div class="formElement" data-ParamName="dblPrecioTotal" data-IsClassID="1" data-ValueType="dbl" data-ControlID="vcdPrecioTotalPartida" data-Required="0" data-MinValue="0.001" style="width:106px; margin-right:0px;">
														
														<input type="text" class="input-transparent form-control numeric vcdPrecioTotalPartida">
														
													</div>
													
													<!-- <div class="formElement" data-ParamName="dblPtjeUtilidad" data-IsClassID="1" data-ValueType="dbl" data-ControlID="vcdPtjeUtilidadPartida" data-Required="0" data-MinValue="0.001" style="width:120px; margin-right:0px;">
														<p class="formLabel">%Utilidad</p>
														<input type="text" class="input-transparent form-control numeric vcdPtjeUtilidadPartida">
														
													</div>
													<div class="formElement" data-ParamName="dblUtilidad" data-IsClassID="1" data-ValueType="dbl" data-ControlID="vcdUtilidadPartida" data-Required="0" data-MinValue="0.001" style="width:120px;">
														<p class="formLabel">Utilidad</p>
														<input type="text" class="form-control numeric vcdUtilidadPartida">
														
													</div>
													<div class="formElement" data-ParamName="dblDiasEntrega" data-IsClassID="1" data-ValueType="dbl" data-ControlID="vcdDiasEntregaPartida" data-Required="0" data-MinValue="0.001" style="width:130px;">
														
														<input type="text" class="form-control numeric vcdDiasEntregaPartida">
														
													</div> -->
													<div class="formElement" data-ParamName="datFechaEntrega" data-IsClassID="1" data-ValueType="str" data-ControlID="vcdFechaEntregaPartida" style="width:150px; margin-right:0px;">
														
														<input type="text" class="input-transparent form-control IsDateDet vcdFechaEntregaPartida">
														
													</div>
												</div>
											';
										}
									?>

									
								</div>

								<div class="buttons-action-detalle" style="display:flex; align-items:center;">  

									<div class="icon-message-empty" style="cursor:pointer;" onclick="showBitacora_CPD_Modal(this, 8, 0, 'articulo')"></div>

									<div class="itemRow_Icon" style="cursor:pointer; margin-left:10px;" onclick="cloneDetalleCotizacion(this);">
										<i data-feather="copy" style="width: 19px; height: 18px; margin-top: 3px; color: #c94228;"></i>
									</div>

									<div class="itemRow_Icon" style="cursor:pointer; margin-left:10px;" onclick="removePartidaDetalleObj(this);">
										<i data-feather="trash-2" style="width: 19px; height: 18px; margin-top: 3px; color: #c94228;"></i>
									</div>
								</div>
							</div>

							<div class="itemRow" id="docLoaded0" data-notas="" data-tipo="" data-name="" data-size="" data-path="" style="width: 100%; padding-bottom: 2px; height: 40px; border: none; display:none;">
								<div class="itemField formElement" style="width: 40px; height:40px; border-top-right-radius: 0px !important; border-bottom-right-radius: 0px !important; border-right:0 !important;">
									<div class="itemField_Data pdf-icon-svg" style="height:15px;"></div>
								</div>
								<div class="itemField formElement link" onclick="showDocRequiDet(this);" style="width:100%; margin-bottom: 0; border-right:0;">
									<div class="contenido" style="text-align: left; width: 100%;">
										<div class="itemField_Data data-center docName" style="text-align: left; font-size:15px; padding-bottom: 0; word-break: break-all;"></div>
									</div>
								</div>
								<div class="itemField formElement link" id="removeDoc" onclick="removeDocDomRequi(this);" style="padding-left: 0px !important; margin-bottom:0; height:50px;">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x" style="margin-bottom:0; cursor: pointer; color:#c92018;"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
								</div>
							</div>
						</div>

						<div class="formElement" style="width: 100%;" data-ParamName="strNotas" data-ValueType="str" data-ControlID="vcNotas" data-Required="0">
							<label class="formLabel">Notas:</label>
							<textarea name="commentBox" class="form-control" id="vcNotas" cols="10" rows="8"></textarea>
						</div>
						
						<br><br><br>

						<ul class="ulFormBtns">
							<li><button type="button" class="btn btn-primary btn-iconed btnBack" onclick="goBack();"><span class="btn-icon"><i data-feather="arrow-left"></i></span>Regresar</button></li>
							<li><button type="button" class="btn btn-primary btn-iconed btnSave" onclick="saveCotizacion();"><span class="btn-icon"><i data-feather="plus-circle"></i></span>Guardar</button></li>
							<li><button type="button" class="btn btn-primary btn-iconed btnSaveRevision" onclick="newRevCotizacion();"><span class="btn-icon"><i data-feather="file-plus"></i></span>Nueva revisión</button></li>
							<?php

								// obtenemos permisos de la opción        
								$user = new Users_User();
								$user->getUser_Opcion($_SESSION['userID'], 'ventas_cotizaciones');
						
								// obtenemos el rol del usuario        
								$userRol = new Users_User_Role();
								$userRol = $user->roles[0];
								
								if ($userRol->hasPermission_CurrentOption($userRol::action_Aprove)){
									echo '<li><button type="button" class="btn btn-primary btn-iconed btnSave_Aprob" onclick="aprobar_Cotizacion();"><span class="btn-icon"><i data-feather="check"></i></span>Aprobar</button></li>';
									echo '<li><button type="button" class="btn btn-primary btn-iconed btnSave_Reject" onclick="rechazar_Cotizacion();"><span class="btn-icon"><i data-feather="corner-up-left"></i></span>Rechazar</button></li>';
								}
							?>
						</ul>

					</div>
				</div>
			</div>  			
		</div>
	</div>
	<!-- Container-fluid Ends-->
	
</div>

<!-- Item para clonar de documento de cotizacion -->
<!-- <div class="itemRow" id="docPartidaCotizacion0" data-notas="" data-tipo="" data-name="" data-size="" data-path="" style="width: 100%; padding-bottom: 2px; height: 40px; border: none; display:none;">
	<div class="itemField formElement" style="width: 50px; height:40px; border-top-right-radius: 0px !important; border-bottom-right-radius: 0px !important; border-right:0 !important;">
		<div id="iconPDF" class="itemField_Data pdf-icon-svg" style="display:none;"></div>
		<div id="iconJPG" class="itemField_Data icon-jpg" style="display:none;"></div>
		<div id="iconPNG" class="itemField_Data icon-png" style="display:none;"></div>
		<div id="iconXLSX" class="itemField_Data icon-xlsx" style="display:none;"></div>
	</div>
	<div class="itemField formElement link" style="width:100%; margin-bottom: 0; border-right:0;justify-content: flex-start;align-items: flex-start;">
		<div class="contenido" onclick='show_Document($(this).parent().parent().data("data-obj").intTipoArchivo, $(this).parent().parent().data("data-obj").strFileName);' style="text-align: left; width: auto;">
			<div class="itemField_Data data-center docName" style="text-align: left; font-size:15px; padding-bottom: 0; word-break: break-all;"></div>
		</div>
		<div class="editDocName"><div class="itemField_Icon" onclick="edit_docName(this);"><i class="icofont icofont-pencil-alt-2"></i></div></div>
	</div>
	<div class="itemField formElement link" style="border-right: 0; height: 50px;" onclick="edit_comentariosDoc(this);">
		<i data-feather="message-square" style="color:#7c7c7c; height:20px;"></i>
	</div>
	<div class="itemField formElement link" id="removeDoc" onclick="removeDocPartidaCotizacion(this);" style="padding-left: 0px !important; margin-bottom:0; height:50px;">
		<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x" style="margin-bottom:0; cursor: pointer; color:#c92018;"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
	</div>
</div> -->

<div class="itemRow itemDocPartida" id="docLoadedPartida0" data-notas="" data-tipo="" data-name="" data-size="" data-path="" style="width: auto; border: none; display:none;">
    <div class="itemField formElement" style="width: 35px; height:40px; border-right:0 !important; margin-bottom: 0;">
        <div class="itemField_Data pdf-icon-svg" style="height:25px;"></div>
    </div>
    <div class="itemField formElement link" style="margin-bottom: 0; border-right:0; padding-left:0;">
        <div class="contenido" style="display:flex;">

            <div class="itemField_Data data-center docName" onclick='show_Document($(this).closest(".itemDocPartida").data("data-obj").intTipoArchivo, $(this).closest(".itemDocPartida").data("data-obj").strFileName);' style="font-size:15px; word-break: break-all;"></div>

        </div>
		<div class="editDocName"><div class="itemField_Icon" onclick="edit_docNamePartida(this);" style="width: 24px;"><i class="icofont icofont-pencil-alt-2" style="font-size: 15px;"></i></div></div>
    </div>
    <div class="itemField formElement link" id="removeDoc" onclick="deleteDocPartidaCotizacion(this);" style="padding-left: 0px !important; margin-bottom:0; height:50px;">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x" style="margin-bottom:0; cursor: pointer; color:#c92018;"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
    </div>
</div>

<div class="itemImagePartida itemDocPartida" id="imgPreviewPartida0" data-notas="" data-tipo="" data-name="" data-size="" data-path="" style="display:none;">
	<div class="image-wrapper-cot">

		<img class="image-partida-cotizacion" style="cursor:pointer;">

		<div class="toolbar-image">
			<div class="itemRow_Icon" style="cursor:pointer;" onclick="checkImagePartida(this);">
				<div class="icon-toggle-check icon-check-sofi" style="width:24px;"></div>
			</div>

			<div class="itemRow_Icon" style="cursor:pointer;" onclick="checkFavoriteImagePartida(this);">
				<div class="icon-toggle-star icon-star-sofi" style="width:24px;"></div>
			</div>

			<div class="itemRow_Icon" style="cursor:pointer;" onclick="addComentsImagePartida_Cotizacion(this);">
				<div class="icon-message-toggle icon-message-empty" style="width:21px;"></div>
			</div>

			<div style="flex: 0 0 10px; width: 100%;">
				<hr style="margin: 0;margin-top: 3px; background-color: #7b7b7b;">
			</div>

			<div class="itemRow_Icon" style="cursor:pointer;" onclick="deleteDocPartidaCotizacion(this);">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2" style="width: 18px; height: 18px; color: #c94228;"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
			</div>
		</div>
	</div>
	<div class="docNameImage">

		<div class="itemField_Data data-center docName" style="font-size:15px; word-break: break-all; margin: auto; padding: 5px;"></div>

		<div class="editDocName" style="flex: 0 0 25px; text-align: center;"><div class="itemField_Icon" onclick="edit_docNamePartida(this);"><i class="icofont icofont-pencil-alt-2" style="color:#7c7c7c"></i></div></div>
	</div>
</div>

<div class="modal draggable fade" id="confirmBoxRechazarCotizacion" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true" style="z-index:999999999;">
    <div class="modal-dialog modal-dialog-centered modal-sm modal-dialog-scrollable">
        <div class="modal-content shadow" style="width: 240px;">
        <div class="modal-header">
            <h5 class="modal-title"></h5>
        </div>
        <div class="modal-body">
            <div class="formElement">
				<p class="formLabel">Seleccionar estatus:</p>
				<select class="AjaxSelect cotEstatus" id="cotEstatus"></select>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Si</button>
        </div>
        </div>
    </div>
</div>

<div class="modal draggable fade" id="bitacoraCPBox" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content shadow" style="height:60vh;">
			<div class="modal-header">
				<h5 class="modal-title">Bitácora</h5>
				<div class="updatedTag" style="margin-left: 30px;"><span class="loading"></span><span class="title" style="display:none;"></span></div>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body" style="overflow-y: auto; background: #f9f8f8;">

				<div id="wrapperBitacoraCP" class="bitacoraWrapper"></div>

				<div id="itemBitacoraCP01" class="bitacoraRow" style="display: none;">
					<div class="icon"><i></i></div>
					<div class="content">
						<div class="header">
							<div class="action"></div>
							<div class="time"></div>
						</div>
						<div class="comments"></div>
						<div class="footer">
							<div class="user"></div>
							<div class="date"></div>
						</div>
					</div>
				</div>
				<div id="itemBitacora02" class="bitacoraRow rowType2" style="display: none;">
					<div class="header">
						<div class="icon"><i></i></div>
						<div class="header">
							<div class="action"></div>
							<div class="title"></div>
							<div class="time"></div>
						</div>
					</div>
				</div>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-primary add-registro">Agregar Registro</button>
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>

<div class="modal draggable fade" id="bitacoraCPDBox" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content shadow" style="height:60vh;">
			<div class="modal-header">
				<h5 class="modal-title">Bitácora</h5>
				<div class="updatedTag" style="margin-left: 30px;"><span class="loading"></span><span class="title" style="display:none;"></span></div>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body" style="overflow-y: auto; background: #f9f8f8;">

				<div id="wrapperBitacoraCPD" class="bitacoraWrapper"></div>

				<div id="itemBitacoraCPD01" class="bitacoraRow" style="display: none;">
					<div class="icon"><i></i></div>
					<div class="content">
						<div class="header">
							<div class="action"></div>
							<div class="time"></div>
						</div>
						<div class="comments"></div>
						<div class="footer">
							<div class="user"></div>
							<div class="date"></div>
						</div>
					</div>
				</div>
				<div id="itemBitacora02" class="bitacoraRow rowType2" style="display: none;">
					<div class="header">
						<div class="icon"><i></i></div>
						<div class="header">
							<div class="action"></div>
							<div class="title"></div>
							<div class="time"></div>
						</div>
					</div>
				</div>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-primary add-registro">Agregar Registro</button>
				<button type="button" class="btn btn-primary toggle-conflicto" style="display:none; padding-bottom: 0px; padding-top: 0px;">
					<span class="btn-icon" style="padding-top: 4px !important; padding-bottom: 4px !important; padding: 0px 12px; top: 0px;"><i data-feather="trash-2" style="width: 18px;vertical-align: bottom;"></i></span>
					Remover conflicto
				</button>
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>

<div class="modal draggable fade" id="selectBoxArticuloCot" style="overflow:hidden;" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content shadow">
			<div class="modal-body">
				<div class="input-group">
					<div class="formElement" data-ParamName="p1" data-ValueType="int" data-ControlID="SelectOptionsArticuloCot" data-Required="1" style="width:90%; margin-right: 0;">
						<p class="formLabel"></p>
						<select class="AjaxSelect form-control SelectOptionsArticuloCot" id="SelectOptionsArticuloCot"></select>
					</div>
					<button type="button" class="btn btn-outline-secondary btn-add-articulo" style="padding: 2px; width: 33px; max-height: 45px; margin-top: 23.81px"><i class="icofont icofont-plus"></i></button>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary">Cancelar</button>
				<button type="button" class="btn btn-primary">Aceptar</button>
			</div>
		</div>
	</div>
</div>
