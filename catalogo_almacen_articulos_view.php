<div class="page-body" id="catalogo_almacen_articulos" style="display:none;">
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col">
                    <div class="page-header-left">
                        <h3>Artículos</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.html"><i data-feather="home"></i></a></li>
                            <li class="breadcrumb-item">Almacen</li>
                            <li class="breadcrumb-item active">Artículos</li>
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
    <div class="col-md-12" id="consultaEntWrapper">
        <div class="card">
            <div class="card-header b-l-danger border-3">
                <h5 class="pull-left">Consulta<span></span></h5>
            </div>
            <div class="card-body">
                
                <br>

                <?php
                    // obtenemos permisos de la opción        
                    $user = new Users_User();
                    $user->getUser_Opcion($_SESSION['userID'], 'catalogo_almacen_articulos');

                    // obtenemos el rol del usuario        
                    $userRol = new Users_User_Role();
                    $userRol = $user->roles[0];

                    //Inicializamos el estilo del toggle como visible hasta que se encuentre lo opuesto
                    $showToggleArtNux = "display:inline-block;";
                    $checkDefault = "";

                    //Revisamos que no sea admin
                    if(!$userRol->hasPermission_CurrentOption($userRol::action_FullControl)){
                        
                        //Revisamos si es almacenista de nux o gerente de almacen de NUX
                        if ($userRol->hasPermission_CurrentOption($userRol::action_ListNÜX) && !$userRol->hasPermission_CurrentOption($userRol::action_ListFormasInteligentes)){


                            $showToggleArtNux = "display:none;";
                            $checkDefault = "checked";
                        }

                        //Revisamos si es almacenista de formas
                        if ($userRol->hasPermission_CurrentOption($userRol::action_ListFormasInteligentes) && !$userRol->hasPermission_CurrentOption($userRol::action_ListNÜX)){


                            $showToggleArtNux = "display:none;";
                        }

                        //Revisamos si es almacenista de formas
                        if ($userRol->hasPermission_CurrentOption($userRol::action_ListFormasInteligentes) && $userRol->hasPermission_CurrentOption($userRol::action_ListNÜX)){


                            $showToggleArtNux = "display:inline-block;";
                        }
                    }
                ?>

                <div class="tab-content" id="gridCatalagoArticulos">
                    <div class="accordion accordion-flush" id="filtrosCatalogoArticulos">
                        <div class="accordion-item" style="position: relative;">								
                            <h2 class="accordion-header" id="headingOne"><button class="accordion-button custom-button" type="button" data-bs-target="#filtrosCatalogoArticulos_Body" data-bs-toggle="collapse" aria-expanded="true" aria-controls="filtrosCatalogoArticulos_Body">Filtros</button></h2>
                            <div style="border-bottom: 0px;" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#filtrosCatalogoArticulos">
                                <div id="filtrosCatalogoArticulos_Body" class="accordion-body">
                                    <div class="formElement" data-ParamName="p1" data-ValueType="str" data-ControlID="strDescripcionCatArticulos" data-Required="0">
                                        <p class="formLabel">Descripción del artículo:</p>
                                        <input type="text" class="form-control" id="strDescripcionCatArticulos"/>					
                                    </div>
                                    <div class="formElement" data-ParamName="p2" data-ValueType="str" data-ControlID="strCodigoCatArticulos" data-Required="0">
                                        <p class="formLabel">Código del artículo:</p>
                                        <input type="text" class="form-control" id="strCodigoCatArticulos"/>					
                                    </div>
                                    <div class="formElement" data-ParamName="p3" data-ValueType="mul" data-ControlID="bytSobrePedidoCatArticulos" data-Required="0">
                                        <p class="formLabel">Sobre Pedido:</p>
                                        <select class="form-control bytSobrePedidoCatArticulos dontInit" id="bytSobrePedidoCatArticulos" >
                                            <option value="0">No Sobre Pedido</option>
                                            <option value="1">Sobre Pedido</option>
                                        </select>
                                    </div>
                                    <div class="formElement" data-ParamName="p4" data-ValueType="mul" data-ControlID="bytArtNux" data-Required="0">
                                        <p class="formLabel">Articulo NÜX:</p>
                                        <select class="form-control bytArtNux dontInit" id="bytArtNux" >
                                            <option value="1">Si</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>
                                    <div class="formElement" data-ParamName="p5" data-ValueType="mul" data-ControlID="intCategoriaArticuloCatArticulos" data-Required="0">
                                        <p class="formLabel">Categoría del Artículo:</p>
                                        <select class="form-control intCategoriaArticuloCatArticulos dontInit" id="intCategoriaArticuloCatArticulos" >
                                            <?php 
                                                $query = "SELECT intCategoriaArticulo, strNombre FROM tblcategorias_articulo WHERE bytEstatus <> 9 AND intCategoriaArticulo <> 0;";
                                                $db = new Database();
                                                echo $db->get_SelectOptions($query); 
                                            ?>
                                        </select>					
                                    </div>
                                    <div class="formElement" data-ParamName="p6" data-ValueType="str" data-ControlID="strComentariosCatArticulos" data-Required="0">
                                        <p class="formLabel">Comentarios:</p>
                                        <input type="text" class="form-control" id="strComentariosCatArticulos"/>					
                                    </div>
                                    <!-- <div class="formElement" data-ParamName="p5" data-ValueType="mul" data-ControlID="intTipoArticuloCatArticulos" data-Required="0">
                                        <p class="formLabel">Tipo de Artículo:</p>
                                        <select class="form-control intTipoArticuloCatArticulos dontInit" id="intTipoArticuloCatArticulos" >
                                            <?php 
                                                $query = "SELECT intTipoArticulo, strNombre FROM tbltipos_articulo WHERE bytEstatus <> 9 AND intTipoArticulo <> 0;";
                                                $db = new Database();
                                                echo $db->get_SelectOptions($query); 
                                            ?>
                                        </select>					
                                    </div> -->
                                    <div class="formElement" data-ParamName="p7" data-ValueType="mul" data-ControlID="intUMedidaCatArticulos" data-Required="1">
                                        <p class="formLabel">Unidad de Medida:</p>
                                        <select class="form-control intUMedidaCatArticulos dontInit" id="intUMedidaCatArticulos" >
                                        <?php 
                                                $query = "SELECT intUMedida, strNombre FROM tblumedida WHERE bytEstatus <> 9 AND intUMedida <> 0;";
                                                $db = new Database();
                                                echo $db->get_SelectOptions($query); 
                                            ?>
                                        </select>					
                                    </div> 
                                    <br>
                                    <br>
                                    <div class="ulFormBtns">
                                        <li><button type="button" class="btn btn-primary" onclick="dataTableCatArticulos()">Buscar</button></li>
                                        <li><button type="button" class="btn btn-secondary" onclick="clearFilters('filtrosCatalogoArticulos_Body')">Limpiar</button></li>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <br>

                    <div id="scrollCatAlmacenArticulos"></div>

                    <table id="tablaCatAlmacenArticulos" class="display table" style="width:100%">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Descripción</th>
                                <th>Sobre Pedido</th>
                                <th>Minimo</th>
                                <th>Máximo</th>
                                <th>Nivel Reorden</th>
                                <!-- <th>Tipo</th> -->
                                <th>Categoría </th>
                                <th>Unidad de Medida </th>
                                <th>Comentarios</th>
                                <th>Usuario Alta</th>
                                <th>Fecha Alta</th>
                                <th>Usuario Mod</th>
                                <th>Fecha Mod</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <br>
</div>

<!-- Modal CRUD  -->
<div class="modal fade" id="AgregarEditarArticulo" style="overflow:hidden;"  data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2"><span class="titleModalHeader">Agregar</span> articulo</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close" data-bs-original-title="" title=""></button>
            </div>
            <div class="modal-body" style="margin-left:20px;">
                <div class="accordion accordion-flush" id="accordionAgregarArticulos">
                    <div class="accordion-item" style="position: relative;">								
                        <h2 class="accordion-header" id="headingOne"><button class="accordion-button custom-button" type="button" data-bs-target="#accordionAgregarArticulos_Body" data-bs-toggle="collapse" aria-expanded="true" aria-controls="accordionAgregarArticulos_Body">Información del articulo</button></h2>
                        <div style="border-bottom: 0px;" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionAgregarArticulos">
                            <div id="accordionAgregarArticulos_Body" class="accordion-body">
                                <div class="formElement" data-ParamName="strCodigo" data-IsClassID="1" data-ControlID="strCodigoArticuloAgregar" data-ValueType="str" data-Required="1">
                                    <p class="formLabel">Código</p>
                                    <input type="text" class="form-control strCodigoArticuloAgregar" id="strCodigoArticuloAgregar"/>
                                </div>
                                <!-- <div class="formElement" data-ParamName="intTipoArticulo" data-ValueType="int" data-Required="1" data-ControlID="intTipoArticuloAgregar">
                                    <p class="formLabel">Tipo</p>
                                    <select class="form-control intTipoArticuloAgregar dontInit" id="intTipoArticuloAgregar" >
                                        <?php 
                                            $query = "SELECT intTipoArticulo, strNombre FROM tbltipos_articulo WHERE bytEstatus <> 9 AND intTipoArticulo <> 0;";
                                            $db = new Database();
                                            echo $db->get_SelectOptions($query);
                                        ?>
                                    </select>
                                </div> -->
                                <div class="formElement" data-ParamName="intUnidadMedidaArticulo" data-ValueType="int" data-ControlID="intUnidadMedidadArticuloAgregar" data-Required="1">
                                    <p class="formLabel">Unidad de Medida:</p>
                                    <select class="form-control intUnidadMedidadArticuloAgregar dontInit" id="intUnidadMedidadArticuloAgregar" >
                                    <?php 
                                            $query = "SELECT intUMedida, strNombre FROM tblumedida WHERE bytEstatus <> 9 AND intUMedida <> 0;";
                                            $db = new Database();
                                            echo $db->get_SelectOptions($query); 
                                        ?>
                                    </select>	
                                </div>
                                <div class="formElement" data-ParamName="intCategoriaArticulo" data-ValueType="int" data-Required="1" data-ControlID="intCategoriaArticuloAgregar">
                                    <p class="formLabel">Categoría</p>
                                    <select class="form-control intCategoriaArticuloAgregar dontInit" id="intCategoriaArticuloAgregar" >
                                        <?php 
                                            $query = "SELECT intCategoriaArticulo, strNombre FROM tblcategorias_articulo WHERE bytEstatus <> 9 AND intCategoriaArticulo <> 0;";
                                            $db = new Database();
                                            echo $db->get_SelectOptions($query); 
                                        ?>
                                    </select>
                                </div>
                                <br>
                                <div class="formElement" id="dblReordenArticulo" data-ParamName="dblReorden" data-ValueType="dbl" data-Required="0" data-MinValue="0" data-ControlID="dblReordenArticuloAgregar">
                                    <p class="formLabel">Nivel Reorden</p>
                                    <input type="text" id="dblReordenArticuloAgregar" class="form-control numeric-positive dblReordenArticuloAgregar" data-bs-original-title="" title="">
                                </div>
                                <div class="formElement" id="dblMinimoArticulo" data-ParamName="dblMinimo" data-ValueType="dbl" data-Required="0" data-MinValue="0" data-ControlID="dblMinimoArticuloAgregar">
                                    <p class="formLabel">Mínimo</p>
                                    <input type="text" id="dblMinimoArticuloAgregar" class="form-control numeric-positive dblMinimoArticuloAgregar" data-bs-original-title="" title="">
                                </div>
                                <div class="formElement" id="dblMaximoArticulo" data-ParamName="dblMaximo" data-ValueType="dbl" data-Required="0" data-MinValue="0" data-ControlID="dblMaximoArticuloAgregar">
                                    <p class="formLabel">Máximo</p>
                                    <input type="text" id="dblMaximoArticuloAgregar" class="form-control numeric-positive dblMaximoArticuloAgregar" data-bs-original-title="" title="">
                                </div>
                                <div class="formElement" data-ParamName="dblAncho" data-ValueType="dbl" data-Required="0" data-MinValue="0.001" data-ControlID="dblAncho">
                                    <p class="formLabel">Ancho</p>
                                    <input type="text" id="dblAncho" class="form-control numeric-positive dblAncho" data-bs-original-title="" title="">
                                </div>
                                <div class="formElement" data-ParamName="dblLargo" data-ValueType="dbl" data-Required="0" data-MinValue="0.001" data-ControlID="dblLargo">
                                    <p class="formLabel">Largo</p>
                                    <input type="text" id="dblLargo" class="form-control numeric-positive dblLargo" data-bs-original-title="" title="">
                                </div>
                                <div class="formElement" data-ParamName="dblAlto" data-ValueType="dbl" data-Required="0" data-MinValue="0.001" data-ControlID="dblAlto">
                                    <p class="formLabel">Alto</p>
                                    <input type="text" id="dblAlto" class="form-control numeric-positive dblAlto" data-bs-original-title="" title="">
                                </div>
                                <br>
                                <div class="formElement" style="width: 120px;" data-ParamName="bytSobrePedido" data-ValueType="chk" data-ControlID="bytSobrePedidoArticuloAgregar" data-Required="1">
                                    <p class="formLabel">Sobre Pedido</p>
                                    <div class="icon-state">
                                        <label class="switch">
                                            <input type="checkbox" id="bytSobrePedidoArticuloAgregar"><span class="switch-state"></span>
                                        </label>
                                    </div>								
                                </div>
                                <div class="formElement" style="<?php echo $showToggleArtNux ?>" data-ParamName="bytNUX" data-ValueType="chk" data-ControlID="bytNUXArticuloAgregar" data-Required="0">
                                    <p class="formLabel">Articulo NÜX</p>
                                    <div class="icon-state">
                                        <label class="switch">
                                            <input type="checkbox" <?php echo $checkDefault ?> id="bytNUXArticuloAgregar"><span class="switch-state"></span>
                                        </label>
                                    </div>								
                                </div>
                                <div class="formElement" style="width:89%;" data-ParamName="strDescripcion" data-ValueType="str" data-Required="1" data-ControlID="strDescripcionArticuloAgregar">
                                    <p class="formLabel">Descripción</p>
                                    <textarea class="form-control strDescripcionArticuloAgregar" id="strDescripcionArticuloAgregar"></textarea>
                                </div>
                                <div class="formElement" style="width:89%;" data-ParamName="strComentarios" data-ValueType="str" data-Required="0" data-ControlID="strComentariosArticuloAgregar">
                                    <p class="formLabel">Comentarios</p>
                                    <textarea class="form-control strComentariosArticuloAgregar" id="strComentariosArticuloAgregar"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <br>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary">Cancelar</button>
                <button type="button" class="btn btn-primary">Aceptar</button>
            </div>
        </div>
    </div>
</div> 
