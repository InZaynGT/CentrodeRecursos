<link rel="stylesheet" href="<?php echo BASE_DIR; ?>css/jquery-ui.css">
<script src="<?php echo BASE_DIR; ?>js/jquery-ui.js"></script>


<script>
  $(function() {
    $("#datepicker").datepicker({
      dateFormat: 'dd-mm-yy'
    });
    $("#example2").dataTable();
  });

  function selectMovimiento(event) {

    var idMov = parseFloat($("#tipo_movimiento").val());
    $("#movimiento").val(idMov);
    $("#mv").val(idMov);
    document.getElementById("tipo_movimiento").disabled = true;
    $("#btnAgregarPr").show();
    var mov = parseFloat($("#tipo_movimiento").val());
    if (mov == 4) $(".compra").show();
    if (mov == 3) $(".venta").show();
    if (mov == 2) $(".salida").show();
  }

  function selectFormaC(event) {
    var pfC = parseFloat($("#formaPagoC").val());
    if (pfC == 1) $(".cheque").show();
    if (pfC != 1) $(".cheque").hide();

  }

  function selectFormaV(event) {
    var pfV = parseFloat($("#formaPagoV").val());
    if (pfV == 2) $(".cheque").show();
    if (pfV != 2) $(".cheque").hide();
    if (pfV == 6) $(".dep").show();
    if (pfV != 6) $(".dep").hide();

  }

  function enterSelProd(e) {
    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla == 13) document.getElementById('btnSelProd').click();

  }

  function enterbtnAgregarPr(e) {
    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla == 9) document.getElementById('btnAgregarPr').click();
  }

  function deactive_tiptip(subtotalString, clase) {
    var total = parseFloat($("#totalP").val());
    var subtotal = parseFloat(subtotalString);

    total -= subtotal;
    $("#totalP").val(total.toFixed(2));
    $("." + clase).html("");
    $(".inpt" + clase).remove();

  }

  function loadInsumos() {
    //Gets the name of the sport entered.
    var insumosPorFinca = "";
    $.ajax({
      url: '<?php echo BASE_DIR ?>busquedaFiltro',
      type: "POST",
      async: false,
    }).done(function(insumos) {
      insumosPorFinca = insumos.split(',');
    });
    //Returns the javascript array of sports teams for the selected sport.
    return insumosPorFinca;
  }

  function autocompleteInsumos(event) {
    var insumos = loadInsumos();
    $("#buscarDetalle").autocomplete({
      source: insumos
    });
  }
</script>

<script>
  $("document").ready(function() {
    $(".venta").hide();
    $(".compra").hide();
    $(".cheque").hide();
    $(".salida").hide();
    $(".doc").hide();
    $(".dep").hide();
    document.getElementById("tipo_movimiento").enabled = true;

    $('#myModal').on('shown.bs.modal', function() {
      document.getElementById("buscarDetalle").focus();
    })

    var availableTags2 = [
      <?php echo $tags2; ?>
    ];
    $("#cliente").autocomplete({
      source: availableTags2
    });

    var availableTags3 = [
      <?php echo $tags3; ?>
    ];
    $("#proveedor").autocomplete({
      source: availableTags3
    });

    var availableTags4 = [
      <?php echo $tags4; ?>
    ];
    $("#buscarDetalle").autocomplete({
      source: availableTags4
    });

    $("#btnAgregarPr").hide()
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

    $("#btnAgregarPr").click(function() {
      $("#ajxProducto").html("");
    });
    $("#btnSelProv").click(function() {

      if ($("#frmAjaxProv input[name='radio_prov']:radio").is(':checked')) {

        $("#nombreproveedor").val($("#frmAjaxProv input[type='radio']:checked").data("prov"));
        $("#idproveedorBus").val($("#frmAjaxProv input[type='radio']:checked").val());

        $("#idproveedor").val($("#frmAjaxProv input[type='radio']:checked").val());

        $("#ajxProveedor").html("");


      } else {
        alert("Debe seleccionar un proveedor");
      }
    });

    $("#selprov").click(function() {
      document.getElementById("descarga").innerHTML = "";

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

    // btnSelProd PARA los INSUMOS del VEHICULO

    $("#btnSelProd").click(function() {
      if ($("#frmAjaxProd input[name='radio_prod']:radio").is(':checked')) {
        var cantidad = parseFloat($("#cantidadp").val());
        var precio = parseFloat($("#precio").val());
        var precioV = parseFloat($("#precioV").val());
        var tipo_movimiento = $("#tipo_movimiento").val().trim();
        var insumo = $("#insumoNombre").val().trim();
        var existencia = $("#existenciaInsumo").val().trim();
        var codigo = $("#codprod").val().trim();
        // alert (cantidad);alert(existencia);
        if (cantidad == '' || cantidad < 1 || precio == '' || precio < 1) {
          alert("Ingrese cantidad y precios correctos");
          return;
        }
        if ((tipo_movimiento == 2 || tipo_movimiento == 3) && (cantidad > existencia)) {
          alert("La existencia no es suficiente");
          return;
        } else {

          Row = [];
          Row = document.getElementById("prods");
          var Cells = Row.getElementsByTagName("td");
          var Codigos = [];
          for (var i = 0; i < Cells.length; i++) {
            Codigos.push($(Cells[i]).parent("tr").find("td:first").text())
            //Codigos.push(Cells[i].firstChild.data);
          }
          console.log(Codigos);
          if (Codigos.indexOf(codigo) !== -1) {
            alert("Este insumo ya ha sido agregado");
            $("#buscarDetalle").val("");
          } else {
            var subtotal = cantidad * precio;
            var total = parseFloat($("#totalP").val());
            total += subtotal;
            $("#totalP").val(total.toFixed(2));
            var idEliminar = $("#frmAjaxProd input[type='radio']:checked").data("iddiv");
            $(".cesta").append("<tr class='" + idEliminar + "' >" +
              "<td >" + $("#frmAjaxProd input[type='radio']:checked").data("codigo") + "</td>" +
              "<td align='center'>" + cantidad + "</td>" +
              "<td >" + insumo + "</td>" +
              "<td align='center'>" + precio + "</td>" +
              "<td align='center'>" + cantidad * precio + "</td>" +
              "<td align='center'>" + precioV + "</td>" +
              "<td class='text-center'>" +
              "<a href='javascript:deactive_tiptip(" + subtotal.toFixed(2) + ", " + idEliminar + ")'>" +
              "<i class='fa fa-close' style='color:#EC0000;font-size:20px;'></i>" +
              "</a>" +
              "</td></tr>");
            $("#listProductos").append('<input type="hidden" name="producto[]" class="inpt' + idEliminar + '"  value="' + $("#frmAjaxProd input[type='radio']:checked").val() + '"/>');
            $("#listProductos").append('<input type="hidden" name="cantidad[]" class="inpt' + idEliminar + '" value="' + cantidad + '"/>');
            $("#listProductos").append('<input type="hidden" name="precio[]" class="inpt' + idEliminar + '" value="' + precio + '"/>');
            $("#listProductos").append('<input type="hidden" name="precioV[]" class="inpt' + idEliminar + '" value="' + precioV + '"/>');

            $("#ajxProducto").html("");
            $("#buscarDetalle").val("");
            Row = document.getElementById("prods");
            $("#myModal").modal("hide");

          }

        }
      } else {
        alert("Debe seleccionar un producto");
      }
    });
  });
</script>


<aside class="right-side">
  <section class="content-header">
    <h1>
      OPERACIONES DE INVENTARIO
      <small>Entrada y Salida</small>
      <button type="button" id="btnSelProdI" class="btn btn-success btn-sm" style="display:none"><i class="fa fa-save"></i> </button>
    </h1>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-sm-12 bodega">
        <!--		<div class="box box-solid box-primary">
					<div class="box-header">
						<h3 class="box-title">Seleccione Bodega a operar</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-primary btn-sm" data-widget="collapse" id="btnMinimizar"><i class="fa fa-chevron-down"></i></button>
						</div>
					</div>
					<div style="display: block;" class="box-body">
						<form role="form" id="frmAjaxProv" action="">
							<div class="box-body">
								<div class="input-group input-group-sm">
									<input class="form-control" id="buscarItem" name="buscarItem" autofocus onkeypress="enterbtnAgregarPr(event)" placeholder="Nombre de la Bodega" type="text">
									<span class="input-group-btn">
										<button class="btn btn-info btn-flat" id="selprov"   type="submit"><i class="fa fa-search"></i> SELECCIONAR</button>
									</span>
								</div>
							</div>
							<div id="ajxProveedor"></div>
						</form>
						<div id="result"></div>
						<div class="box-footer">
							<button type="button" id="btnSelProv" class="btn btn-success btn-sm"><i class="fa fa-save"></i> SELECCIONAR</button>
						</div>
					</div>
				</div>-->
      </div>



      <div class="row">
        <div class="col-sm-12">
          <div class="box box-primary padd">
            <form role="form" id="frmAjax" action="">
              <div class="col-sm-4">
                <table class="table table-condensed miFormulario">
                  <tbody>
                    <td>
                      <!-- <tr>
                      <th width="110px">Bodega: </th>
                      <td>
                        <input class="form-control" name="trabajo" id="nombreItem" placeholder="Seleccione una bodega" disabled="" type="text">
                        <input type="hidden" name="idItem" id="idItem" value="0">
                      </td>
                    </tr> -->
                      <tr>
                        <th width="110px">Operaci&oacute;n: </th>
                        <td>
                          <select class="form-control" name="tipo_movimiento" id="tipo_movimiento" onchange="selectMovimiento(event)">
                            <option value="0">Seleccione tipo de operaci&oacute;n</option>
                            <option value="4">Compra</option>
                            <option value="3">Venta</option>
                            <option value="1">Entrada</option>
                            <option value="2">Salida</option>
                          </select>
                          <input type="hidden" name="movimiento" id="movimiento" value="0">
                        </td>
                      </tr>
                    </td>
                  </tbody>
                </table>
              </div>
              <div class="col-sm-4">
                <table class="table table-condensed miFormulario">
                  <tbody>
                    <td>
                      <tr>
                        <th width="110px">Fecha: </th>
                        <td>
                          <input class="form-control " name="fecha" id="datepicker" readonly="readonly" value="<?php echo date("d-m-Y"); ?>" type="text">
                        </td>
                      </tr>

                      <tr>
                        <th width="110px">Nota: </th>
                        <td>
                          <input class="form-control" name="nota" placeholder="Nota" type="text">

                        </td>
                      </tr>

                      <tr class="compra" id="compra">
                        <th width="110px">Proveedor: </th>
                        <td>
                          <input name="proveedor" id="proveedor" autofocus type="text" class="form-control" placeholder="Proveedor (*)">
                        </td>
                      </tr>

                      <tr class="venta" id="venta">
                        <th width="110px">Paciente: </th>
                        <td>
                          <input name="cliente" id="cliente" autofocus type="text" class="form-control" placeholder="Paciente (*)">
                        </td>
                      </tr>
                    </td>
                  </tbody>
                </table>
              </div>
              <div class="col-sm-4">
                <table class="table table-condensed miFormulario">
                  <tbody>
                    <td>
                      <!-- <tr class="venta" id="venta">
                        <th width="110px">Documento: </th>
                        <td>
                          <select class="form-control" name="docfel">
                            <option value="0">Seleccione documento</option>
                            <?php foreach ($documentos as $r) {
                            ?>
                              <option value="<?php echo $r['ID_DOCUMENTO'] ?>"><?php echo $r['TIPO']; ?> <?php echo $r['SERIE']; ?> :: <?php echo $r['NUMERO_ACTUAL']; ?></option>
                            <?php
                            }
                            ?>
                          </select>
                        </td>
                      </tr> -->
                      <tr>
                        <th width="110px">Comprobante: </th>
                        <td>
                          <input name="comprobante" autofocus type="text" class="form-control" placeholder="Comprobante">
                        </td>
                      </tr>
                      <tr class="compra" id="compra">
                        <th width="110px">Forma de pago: </th>
                        <td>
                          <select class="form-control" name="formaPagoC" id="formaPagoC" onchange="selectFormaC(event)">
                            <option value="0">Seleccione Forma de Pago</option>
                            <option value="1">Efectivo</option>
                          </select>
                        </td>
                      </tr>
                      <tr class="venta" id="venta">
                        <th width="110px">Forma de pago: </th>
                        <td>
                          <select class="form-control" name="formaPagoV" id="formaPagoV" onchange="selectFormaV(event)">
                            <option value="0">Seleccione Forma de Pago</option>
                            <option value="1">Efectivo</option>
                          </select>
                        </td>
                      </tr>
                      <!-- <tr class="dep">
                      <th width="110px">Cuenta: </th>
                      <td>
                        <select class="form-control" name="cuentaBancaria">
                          <option value="0">Seleccione una cuenta</option>
                            <?php foreach ($clienteBancos as $r) {
                            ?>
                                  <option value="<?php echo $r['ID_BANCO_CLIENTE'] ?>"><?php echo $r['NUMERO_CUENTA']; ?> <?php echo $r['DESCRIPCION']; ?></option>
                                    <?php
                                  }
                                    ?>
                          </select>
                      </td>
                    </tr> -->

                      <!-- <tr class="cheque">
                      <th width="110px">Banco: </th>
                      <td>
                        <select class="form-control" name="banco">
                          <option value="0">Seleccione un banco</option>
                            <?php foreach ($bancos as $r) {
                            ?>
                                  <option value="<?php echo $r['ID_BANCO'] ?>"><?php echo $r['DESCRIPCION']; ?></option>
                                    <?php
                                  }
                                    ?>
                          </select>
                      </td>
                    </tr> -->

                      <!-- <tr class="cheque">
                      <th width="110px">Cuenta Bancaria: </th>
                      <td>
                        	<input name="cuenta" type="text" value=""  class="form-control"  placeholder="Cuenta">
                      </td>
                    </tr> -->

                      <!-- <tr class="venta" id="venta">
                        <th width="110px">Documento: </th>
                        <td>
                          <input name="documento" type="text" value="" class="form-control" placeholder="Documento">
                        </td>
                      </tr> -->

                      <!-- <tr class="compra" id="venta">
                        <th width="110px">Factura: </th>
                        <td>
                          <input name="facturaVenta" type="text" value="" class="form-control" placeholder="Factura">
                        </td>
                      </tr> -->


                      <tr>
                        <th width="110px"> Total </th>
                        <td>
                          <input name="totalMovimiento" readonly id="totalP" autofocus type="text" class="form-control" value="0" placeholder="Total">
                        </td>
                      </tr>

                      <!-- <tr class="salida">
                      <th width="110px"> Gasto en mensualidades </th>
                      <td>
                        <input class="form-control" type="number" pattern=" 0+\.[0-9]*[1-9][0-9]*$" name="mensualidades" min="0" value="0"  placeholder="Cantidad pagos" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                      </td>
                    </tr> -->



                    </td>
                  </tbody>
                </table>
              </div>




              <div id="listProductos"></div>
              <div class="box-footer" style="text-align: right;">
                <div id="resultCompra" class="in-block"></div> &nbsp;
                <button type="submit" class="btn btn-primary" id="BotonGuardar"><i class="fa fa-save"></i> GUARDAR</button>
              </div>
            </form>
          </div>
        </div>
      </div>






      <div class="col-xs-12">
        <div class="box box-primary" style="padding:20px 30px;">
          <div class="box-header">
            <h3 class="box-title">Listado de productos</h3>
            <div class="box-tools" style="text-align:right;">
              <button type="button" id="btnAgregarPr" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal">Agregar producto</button>
            </div>
          </div>

          <!-- Modal -->
          <style>
            .modal-dialog {
              width: 90%;
              margin: 0 auto;
            }
          </style>
          <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">


              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" id="closemodal">&times;</button>
                  <h4 class="modal-title">Agregar producto</h4>
                </div>
                <div class="modal-body">

                  <form role="form" id="frmAjaxProd" action="">
                    <div class="box-body">
                      <div class="input-group input-group-sm">
                        <input class="form-control" name="buscarDetalle" id="buscarDetalle" placeholder="Producto" type="text">
                        <input type="hidden" id="idproveedorBus" name="idproveedorBus">
                        <input type="hidden" id="idFinca" name="idFinca">
                        <input type="hidden" id="mv" name="mv">
                        <span class="input-group-btn">
                          <button class="btn btn-info btn-flat" type="submit"><i class="fa fa-search"></i></button>
                        </span>
                      </div>
                    </div>
                    <div id="ajxProducto"></div>
                  </form>

                </div>
                <div class="modal-footer">
                  <button type="button" id="btnSelProd" class="btn btn-success btn-sm"><i class="fa fa-save"></i> SELECCIONAR</button>
                </div>
              </div>

            </div>
          </div>


          <table id="prods" aria-describedby="example1_info" class="table table-bordered table-striped dataTable miTabla">
            <thead>
              <tr role="row">
                <th width="50px"><b>C&oacute;digo</b></th>
                <th width="130px"><b>Cantidad</b></th>
                <th><b>Producto</b></th>
                <th width="130px"><b>P.Unidad</b></th>
                <th width="130px"><b>Subtotal</b></th>
                <th width="130px"><b>Precio Venta</b></th>
                <th width="130px"><b>Acciones</b></th>
              </tr>

            </thead>
            <tbody aria-relevant="all" aria-live="polite" role="alert" class="cesta" id="descarga">
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</aside>