<link rel="stylesheet" href="<?php echo BASE_DIR; ?>css/jquery-ui.css">
<script src="<?php echo BASE_DIR; ?>js/jquery-ui.js"></script>
<script>
    //Esta funcion sirve para eliminar los productos de la lista
    function deactive_tiptip(subtotalString, clase) {
        var total = parseFloat($("#totalP1").val());
        var subtotal = parseFloat(subtotalString);
        total -= subtotal;
        $("#totalP1").val(total.toFixed(2));
        $("." + clase).html("");
        $(".inpt" + clase).remove();
    }

    $("document").ready(function() {

        //Envia los request a la base de datos para obtener lo que el ciente busca
        $("#buscarProd").autocomplete({
            minLength: 4,
            delay:300,
            source: function(request, response){
                $.ajax({
                    url:"<?php echo BASE_DIR ?>autocomplete",
                    type:"POST",
                    dataType:"json",
                    data : {
                        search: request.term,
                        buscarProducto: 1
                    },
                    success:function(data){
                        if(data.length > 0){
                            $("#empty-message").text("")
                            response(data)
                        }
                        else {
                            $("#empty-message").text("No se encontraron resultados")
                        }
                        
                    }
                    
                })
            }
        });


        $("#agregarProdLista").hide()
        $().ajaxStart(function() {
            $("#resultCompra").hide();
        }).ajaxStop(function() {
            $("#resultCompra").fadeIn("slow");
        });
        $("#frmAjax").submit(function() {
            $.ajax({
                type: "POST",
                url: $("#frmAjax").attr('action'),
                data: $("#frmAjax").serialize(),
                beforeSend: function() {
                    $("#resultCompra").html('<div class="loading"></div>');
                },
                success: function(data) {
                    $("#resultCompra").html(data);
                }
            });
            return false;
        });
        $().ajaxStart(function() {
            $("#ajxProveedor").hide();
        }).ajaxStop(function() {
            $("#ajxProveedor").fadeIn("slow");
        });
        $("#frmAjaxProv").submit(function() {
            $.ajax({
                type: "POST",
                url: $("#frmAjaxProv").attr('action'),
                data: $("#frmAjaxProv").serialize(),
                beforeSend: function() {
                    $("#ajxProveedor").html('<div class="loading"></div>');
                },
                success: function(data) {
                    $("#ajxProveedor").html(data);
                }
            });
            return false;
        });

        $("#agregarProdLista").click(function() {
            $("#ajxProducto").html("");
        });

        $().ajaxStart(function() {
            $("#ajxProducto").hide();
        }).ajaxStop(function() {
            $("#ajxProducto").fadeIn("slow");
        });
        $("#frmAjaxProd").submit(function() {
            $.ajax({
                type: "POST",
                url: $("#frmAjaxProd").attr('action'),
                data: $("#frmAjaxProd").serialize(),
                beforeSend: function() {
                    $("#ajxProducto").html('<div class="loading"></div>');
                },
                success: function(data) {
                    $("#ajxProducto").html(data);
                }
            });
            return false;
        });

        $("#btnSelProd").click(function() {

            if ($("#frmAjaxProd input[name='radio_prod']:radio").is(':checked')) {
                var cantidad = $("#cantidadp").val().trim();
                //codigo de producto viene de agregarOrdenController ln107
                var codigo = $("#codprod").val().trim();
                var precio = $("#precio").val().trim();
                
                if (cantidad == '' || cantidad < 1 || precio == '' || precio < 1) {
                    alert("Ingrese una cantidad y un precio validos");
                } else {

                    //verifica duplicados
                    if (window.Row) {
                        Row = []
                        Row = document.getElementById("prods");
                        var Cells = Row.getElementsByTagName("td");
                        var Codigos = [];
                        for (var i = 0; i < Cells.length; i++) {
                            Codigos.push($(Cells[i]).parent("tr").find("td:first").text())
                            //Codigos.push(Cells[i].firstChild.data);
                        }
                        
                        // if (inputs.length>0){
                        //   for (var i=0; i<inputs.length; i++) {
                        //     var valinputs = document.getElementsByClassName(inputs)[i].value;
                        //     console.log(valinputs);
                        //   }
                        //
                        // alert(inputs.toString());
                        if (Codigos.indexOf(codigo) !== -1) {
                            alert("Este producto ya ha sido agregado");
                            $("#buscarProd").val("");
                        } else {
                            var subtotal = cantidad * precio;
                            var total = parseFloat($("#totalP1").val());
                            total += subtotal;
                            // alert(cookie);
                            $("#totalP1").val(total.toFixed(2));
                            var idEliminar = $("#frmAjaxProd input[type='radio']:checked").data("id");
                            $(".cesta").append("<tr class='" + idEliminar + "' >" +
                                "<td align='center'>" + $("#frmAjaxProd input[type='radio']:checked").data("codigoprod") + "</td>" +
                                "<td align='center'>" + cantidad + "</td>" +
                                "<td>" + $("#frmAjaxProd input[type='radio']:checked").data("nombreprod") + "</td>" +
                                "<td align='right'>Q. " + precio + "</td><td align='right'>Q. " + subtotal.toFixed(2) + "</td>" +
                                "<td class='text-center'>" +

                                "<a href='javascript:deactive_tiptip(" + subtotal.toFixed(2) + ", " + idEliminar + ")'>" +
                                "<i class='fa fa-close' style='color:#EC0000;font-size:20px;'></i>" +
                                "</a>" +
                                "</td></tr>");
                            $("#listProductos").append('<input type="hidden" name="producto[]" class="inpt' + idEliminar + '" value="' + $("#frmAjaxProd input[type='radio']:checked").val() + '"/>');
                            $("#listProductos").append('<input type="hidden" name="cantidad[]" class="inpt' + idEliminar + '" value="' + cantidad + '"/>');
                            $("#listProductos").append('<input type="hidden" name="subtotal[]" class="inpt' + idEliminar + '" value="' + precio + '"/>');
                            $("#btnMinimizarAdd").trigger("click");
                            $("#ajxProducto").html("");
                            $("#buscarProd").val("");
                            $("#myModal").modal("hide");
                        }
                    } else {
                        var subtotal = cantidad * precio;
                        var total = parseFloat($("#totalP1").val());
                        total += subtotal;
                        $("#totalP1").val(total.toFixed(2));
                        var idEliminar = $("#frmAjaxProd input[type='radio']:checked").data("id");
                        $(".cesta").append("<tr class='" + idEliminar + "' >" +
                            "<td align='center'>" + $("#frmAjaxProd input[type='radio']:checked").data("codigoprod") + "</td>" +
                            "<td align='center'>" + cantidad + "</td>" +
                            "<td>" + $("#frmAjaxProd input[type='radio']:checked").data("nombreprod") + "</td>" +
                            "<td align='right'>Q. " + precio + "</td><td align='right'>Q. " + subtotal.toFixed(2) + "</td>" +
                            "<td class='text-center'>" +
                            "<a href='javascript:deactive_tiptip(" + subtotal.toFixed(2) + ", " + idEliminar + ")'>" +
                            "<i class='fa fa-close' style='color:#EC0000;font-size:20px;'></i>" +
                            "</a>" +
                            "</td></tr>");
                        $("#listProductos").append('<input type="hidden" name="producto[]" class="inpt' + idEliminar + '" value="' + $("#frmAjaxProd input[type='radio']:checked").val() + '"/>');
                        $("#listProductos").append('<input type="hidden" name="cantidad[]" class="inpt' + idEliminar + '" value="' + cantidad + '"/>');
                        $("#listProductos").append('<input type="hidden" name="subtotal[]" class="inpt' + idEliminar + '" value="' + precio + '"/>');
                        $("#btnMinimizarAdd").trigger("click");
                        $("#ajxProducto").html("");
                        $("#buscarProd").val("");
                        $("#myModal").modal("hide");
                        Row = document.getElementById("prods");
                    }
                }
            } else {
                alert("Debe seleccionar un insumo");
            }
        });

    });
</script>
<style>
    .select-editable {
        position: relative;
        background-color: white;
        border: solid grey 1px;
        width: 120px;
        height: 18px;
    }

    .select-editable select {
        position: absolute;
        top: 0px;
        left: 0px;
        font-size: 14px;
        border: none;
        width: 120px;
        margin: 0;
    }

    .select-editable input {
        position: absolute;
        top: 0px;
        left: 0px;
        width: 100px;
        padding: 1px;
        font-size: 12px;
        border: none;
    }

    .select-editable select:focus,
    .select-editable input:focus {
        outline: none;
    }

    .bg-light-blue,
    .bg-green {
        margin: 1px;
    }

    .form-group.col-md-12,
    .form-group.col-md-6 {
        display: none;
        padding-right: 15px;
        padding-left: 15px;
    }

    #sesion-referencias {
        padding: 10px;
        margin: 0px 0px 8px;
    }

    #sesion-referencias h3 {
        font-size: 20px;
    }
</style>

<aside class="right-side">
    <section class="content-header">
        <h1>
            Cobro de consulta
            <small>Datos de la consulta</small>
           <button type="button" id="agregarProdLista" class="btn btn-success btn-sm" style="display:none"><i class="fa fa-save"></i> </button>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-primary padd">
                    <form role="form" id="frmAjax" action="">
                        <div class="col-sm-6">

                            <table class="table table-condensed miFormulario">
                                <tbody>
                                    <tr>
                                        <th>Fecha: </th>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input name="fecha" type="text" readonly value="<?php echo date("d-m-Y"); ?>" class="form-control" placeholder="Fecha">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="110px">Nro. Consulta: </th>
                                        <td>
                                            <input class="form-control" name="nroConsulta" id="nroConsulta" placeholder="Número de consulta" disabled type="text" value="<?php echo $consulta['idconsulta'] ?>" style="color:red">
                                            <input type="hidden" name="idConsulta" id="idConsulta" value="<?php echo $consulta['idconsulta'] ?>">
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Paciente: </th>
                                        <td>
                                            <input class="form-control" name="nombrePaciente" id="nombrecliente" placeholder="Nombre del cliente" disabled type="text" value="<?php echo $consulta['nombrepaciente'] ?>">
                                            <input class="form-control" name="idPaciente" type="hidden" value="<?php echo $consulta['idpaciente'] ?>">
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Total: </th>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon">Q.</span>
                                                <input class="form-control"  name="totalPago" value="0.00" id="totalP1" type="number" style="font-weight:bold" readonly>
                                            </div>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>

                        </div>


                        <div class="col-sm-6">
                            <table class="table table-condensed miFormulario">
                                <tbody>
                                    <tr>
                                        <th width="110px">Documento: </th>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-sticky-note-o"></i></span>
                                                <select class="form-control" name="documento" required>
                                                    <option value="" selected disabled>Seleccione un documento</option>
                                                    <?php foreach ($documentos as $doctos) { ?>
                                                        <option value="<?php echo $doctos['iddocumento'] ?>"><?php echo $doctos['documento']  ?></option>

                                                    <?php } ?>
                                                </select>
                                            </div>

                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Forma de Pago: </th>
                                        <td>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                                <select name="formaPago" class="form-control" id="select-tipo" required>
                                                    <option value="" selected disabled>Seleccione una forma de pago</option>
                                                    <?php foreach ($formasPago as $formaPago) { ?>
                                                        <option value="<?php echo $formaPago['idformapago'] ?>"> <?php echo $formaPago['nombre'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Observaciones: </th>
                                        <td>
                                            <textarea class="form-control" name="observaciones" placeholder="Escriba una observación" rows="3" style="overflow:auto;resize:none"></textarea>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                            </div>
                            <div id="result2">
                            </div>

                            <div id="listProductos"></div>
                            <div class="box-footer" style="text-align: right;">

                                
                                <button type="submit" class="btn btn-primary"><i class="fa  fa-shopping-cart"></i> COBRAR</button>
                </div>
                <div id="resultCompra"></div>
            </div>

            </form>


            <div class="col-xs-12">
                <div class="box box-primary" style="padding:20px 30px;">
                    <div class="box-header">
                        <h3 class="box-title">Productos/servicios asignados</h3>
                        <div class="box-tools" style="text-align:right;">
                            <button type="button" id="agregarProdLista" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal">Agregar servicio</button>
                        </div>
                    </div>

                    <!-- Modal -->
                    <style>
                        .modal-dialog {
                            width: 50%;
                            margin: 0 auto;
                        }
                    </style>
                    <div id="myModal" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" id="closemodal">&times;</button>
                                    <h4 class="modal-title">Agregar producto o servicio</h4>
                                </div>
                                <div class="modal-body">

                                    <form role="form" id="frmAjaxProd" action="">
                                        <div class="box-body">

                                            <div class="input-group input-group-sm">
                                                <input class="form-control" name="buscarProd" id="buscarProd" placeholder="Nombre del producto o servicio" type="text" required>
                                                <input type="hidden" id="idProductoBuscar" name="idProductoBuscar">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-info btn-flat" type="submit"><i class="fa fa-search"></i> SELECCIONAR</button>
                                                </span>
                                            </div>
                                            <p id="empty-message" style="color:red"></p>
                                        </div>
                                        <div id="ajxProducto"></div>
                                    </form>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" id="btnSelProd" class="btn btn-success btn-sm"><i class="fa fa-save"></i> AGREGAR</button>
                                </div>
                            </div>

                        </div>
                    </div>


                    <table aria-describedby="example1_info" class="table table-bordered table-striped dataTable miTabla">
                        <thead>
                            <tr role="row">
                                <th width="65px"><b>C&oacute;digo</b></th>
                                <th width="130px"><b>Cantidad</b></th>
                                <th><b>Producto o servicio</b></th>
                                <th width="130px"><b>Precio</b></th>
                                <th width="130px"><b>Subtotal</b></th>
                                <th width="130px"><b>Acciones</b></th>
                            </tr>

                        </thead>
                        <tbody id="prods" aria-relevant="all" aria-live="polite" role="alert" class="cesta">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php

        //Muestra los productos/servicios que tenga asignada la consulta
        if (!empty($ultrasonidosConsulta)) {
            for ($i = 0; $i < count($ultrasonidosConsulta); $i++) {
                $select = 'checked';
                $precioUlt = $ultrasonidosConsulta[$i]['valor'];

                //se crea una tabla "temporal" en la que se colocan los datos
                echo '
				<form role="form" id="formProductos" action="">
				<table class="table table-hover" id="carga" style="display: none">
					<tbody>
						<tr>
						<td><input type="radio" id="rad" value="' . $ultrasonidosConsulta[$i]['codigo'] . '" data-id="' . $i . '" data-nombreprod="' . $ultrasonidosConsulta[$i]['nombre'] . '" 
						data-codigoprod="' . $ultrasonidosConsulta[$i]['codigo'] . '" data-precio="' . number_format($precioUlt, 2, '.', '') . '"  ' . $select . '></td>
						<td align="right"><input type="number" name="cantidad" id="cantidad" value="1" min="1" required/></td>
						<td align="right"><input type="number" id="precio" value="' . number_format($precioUlt, 2, '.', '') . '"></td>
						</tr>
					</tbody>
				</table>
				</form>';
        ?>
                <!--en esta parte se crea la tabla que se muestra en la vista, los datos que se muestran son los que se insertaron anteriormente-->
                <script>
                    var cantidad = $("#cantidad").val().trim();
                    var costo = $("#precio").val().trim();
                    var subtotal = cantidad * costo
                    var total = parseFloat($("#totalP1").val())
                    total += subtotal
                    $("#totalP1").val(total.toFixed(2))
                   
                    var idEliminar = $("#formProductos input[type='radio']:checked").data("id");
                    $(".cesta").append("<tr class='" + idEliminar + "' >" +
                        "<td align='center'>" + $("#formProductos input[type='radio']:checked").data("codigoprod") + "</td>" +
                        "<td align='center'>" + cantidad + "</td>" +
                        "<td>" + $("#formProductos input[type='radio']:checked").data("nombreprod") + "</td>" +
                        "<td align='right'>Q. " + costo + "</td><td align='right'>Q. " + subtotal.toFixed(2) + "</td>" +
                        "<td class='text-center'>" +
                        "<a href='javascript:deactive_tiptip(" + subtotal.toFixed(2) + ", " + idEliminar + ")'>" +
                        "<i class='fa fa-close' style='color:#EC0000;font-size:20px;'></i>" +
                        "</a>" +
                        "</td></tr>");

                    $("#listProductos").append('<input type="hidden" name="producto[]" class="inpt' + idEliminar + '" value="' + $("#formProductos input[type='radio']:checked").val() + '"/>');
                    $("#listProductos").append('<input type="hidden" name="cantidad[]" class="inpt' + idEliminar + '" value="' + cantidad + '"/>');
                    $("#listProductos").append('<input type="hidden" name="subtotal[]" class="inpt' + idEliminar + '" value="' + costo + '"/>');
                    // document.getElementById('rad').checked = false;
                    // document.getElementById("carga").innerHTML = "";
                    Row = document.getElementById("prods");
                    var Cells = Row.getElementsByTagName("td");
                    var Codigos = [];
                    for (var i = 0; i < Cells.length; i++) {
                        Codigos.push($(Cells[i]).parent("tr").find("td:first").text())
                        //Codigos.push(Cells[i].firstChild.data);
                        
                    }
                    $("#carga").remove();
                </script>
        <?php        }
        } ?>

    </section>
</aside>