<link rel="stylesheet" href="<?php echo BASE_DIR; ?>css/jquery-ui.css">
<script src="<?php echo BASE_DIR; ?>js/jquery-ui.js"></script>


<script>
  $(function() {
    $("#datepicker").datepicker({
      dateFormat: 'dd-mm-yy'
    });
    $("#example2").dataTable();
  });


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
    // document.getElementById("tipo_movimiento").enabled = true;

    // $('#myModal').on('shown.bs.modal', function() {
    //   document.getElementById("buscarDetalle").focus();
    // })

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

    // $("#selprov").click(function() {
    //   document.getElementById("descarga").innerHTML = "";

    // });


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
        //   Row = document.getElementById("prods");
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
            // Row = document.getElementById("prods");
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
<div class="row">
  <div class="col-sm-12">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" id="closemodal">&times;</button>
      <h3 class="modal-title">
        MOVIMIENTOS DE PRODUCTOS <small>Entradas y Salidas</small></h3>
      
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
    </div>
  </div>
</div>
</aside>
