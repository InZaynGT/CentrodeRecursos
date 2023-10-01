<link rel="stylesheet" href="<?php echo BASE_DIR; ?>css/jquery-ui.css">
<script src="<?php echo BASE_DIR; ?>js/jquery-ui.js"></script>
<link rel="stylesheet" href="<?php echo BASE_DIR; ?>css/fullcalendar/fullcalendar.css" />
<script src="<?php echo BASE_DIR; ?>js/fullcalendar/moment.min.js"></script>
<script src="<?php echo BASE_DIR; ?>js/fullcalendar/fullcalendar.min.js"></script>
<script src="<?php echo BASE_DIR; ?>js/fullcalendar/es.js"></script>
<script>
  $(document).ready(function () {
    var eventsList = <?php echo $eventsList ?>;
    var calendar = $('#calendar').fullCalendar({
      language: 'es',
      eventLimit: true,
      editable: true,
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay,listMonth'
      },
      events: eventsList,
      selectable: true,
      selectHelper: true,
      //Evento para abrir el modal para crear una nueva cita
      select: function (start, end, allDay) {
        $('#ModalAdd #start').val(moment(start).format('Y-MM-DD HH:mm:ss'));
        $('#ModalAdd #end').val(moment(end).format('Y-MM-DD HH:mm:ss'));
        $('#ModalAdd').modal('show');

      },
      editable: true,

      //Evento para cuando se aumenta o disminuye el tiempo de una cita
      eventResize: function (event) {
        var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
        var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
        var id = event.id;
        let update = true;
        $.ajax({
          type: "POST",
          data: { start: start, end: end, id: id, update: update },
          success: function () {
            calendar.fullCalendar('refetchEvents');
          }
        });


      },

      //Evento para cuando se mueve una cita de una fecha/hora a otra
      eventDrop: function (event) {
        var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
        var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
        var id = event.id;
        let update = true;
        $.ajax({
          type: "POST",
          data: { start: start, end: end, id: id, update: update },
          success: function () {
            calendar.fullCalendar('refetchEvents');
          }
        });
      },


      eventRender: function (event, element) {
        element.bind('click', function () {
          $('#ModalEdit #id').val(event.id);
          $('#ModalEdit #title').val(event.title);
          $('#ModalEdit #color').val(event.color);
          $('#ModalEdit').modal('show');
        });
      },

    });
  });



  $("#ModalAdd").submit(function () {
    $.ajax({
      type: "POST",
      url: $("#ModalAdd").attr("action"),
      data: $("#ModalAdd").serialize(),
      beforeSend: function () {
        $("#result").html('<div class="loading"></div>');
      },
      success: function (data) {
        $("#result").html(data);
      }

    })
    return false;
  });

</script>
<style type="text/css">
  .userinfo {
    font-family: 'pt_sansregular';
    color: white;
    background: #3C78BC;
    border-radius: 3px;
    border: 1px solid transparent;
    border-color: #4a8e7f;
    width: 85px;
    height: 27px;
    border-radius: 3px;
    -webkit-box-shadow: inset 0px -2px 0px 0px rgba(0, 0, 0, 0.09);
    -moz-box-shadow: inset 0px -2px 0px 0px rgba(0, 0, 0, 0.09);
    box-shadow: inset 0px -1px 0px 0px rgba(0, 0, 0, 0.09);
  }

  .userinfo:hover {
    background-color: #1462ba;
  }
</style>
<?php $min = 'Citas';
$may = 'INVENTARIO';
$minS = 'Inventario'; ?>
<aside class="right-side">
  <section class="content">
    <div id="calendar" class="col-md-12">
      <!-- Modal para agregar una nueva cita-->
      <div class="modal fade" id="ModalAdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog">
          <div class="modal-content">
             <form class="form-horizontal" method="POST">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Agregar Cita</h4>
              </div>
              <form id="ModalAdd" action="" method="post">
                <div class="modal-body">
                  <div class="form-group">
                    <label for="title" class="col-sm-2 control-label">Titulo</label>
                    <div class="col-sm-10">
                      <input required type="text" name="title" class="form-control" id="title" placeholder="Titulo">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="color" class="col-sm-2 control-label">Motivo</label>
                    <div class="col-sm-10">
                      <select required name="color" class="form-control" id="color">
                        <option value="">Seleccionar</option>
                        <option style="color:#FF0000;" value="#FF0000">&#9724; Primera Revisión de Historial Clínico
                        </option>
                        <option style="color:#000;" value="#000">&#9724; Consulta de Seguimiento</option>
                        <option style="color:#A020F0" value="#A020F0">&#9724; Descanso o Personal</option>
                        <!-- <option style="color:#FFD700;" value="#FFD700">&#9724; Colposcopía</option>
                    <option style="color:#FF8C00;" value="#FF8C00">&#9724; Radiofrecuencia</option>
                    <option style="color:#FF0000;" value="#FF0000">&#9724; Turnos</option>
                    <option style="color:#000;" value="#000">&#9724; Depilación láser</option>
                    <option style="color:#A020F0" value="#A020F0">&#9724; Reducción de Medidas</option> -->
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="start" class="col-sm-2 control-label">Fecha y Hora Inicial</label>
                    <div class="col-sm-10">
                      <input required type="datetime-local" name="start" class="form-control" id="start">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="end" class="col-sm-2 control-label">Fecha y Hora Final</label>
                    <div class="col-sm-10">
                      <input required type="datetime-local" name="end" class="form-control" id="end">
                    </div>
                  </div>

                </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-info" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary">Guardar</button>
          </div>
          </form>
        </div>
      </div>
    </div>

    <!--Modal para editar una cita-->
    <div class="modal fade" id="ModalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form class="form-horizontal" method="POST">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                  aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Modificar Cita</h4>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label for="title" class="col-sm-2 control-label">Titulo</label>
                <div class="col-sm-10">
                  <input type="text" name="title" class="form-control" id="title" placeholder="Titulo">
                </div>
              </div>
              <div class="form-group">
                <label for="color" class="col-sm-2 control-label">Color</label>
                <div class="col-sm-10">
                  <select required name="color" class="form-control" id="color">
                    <option value="">Seleccionar</option>
                    <option style="color:#FF0000;" value="#FF0000">&#9724; Primera Revisión de Historial Clínico
                    </option>
                    <option style="color:#000;" value="#000">&#9724; Consulta de Seguimiento</option>
                    <option style="color:#A020F0" value="#A020F0">&#9724; Descanso o Personal</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <div class="checkbox">
                    <label class="text-danger"><input type="checkbox" name="delete">Eliminar Evento</label>
                  </div>
                </div>
              </div>
              <input type="hidden" name="id" class="form-control" id="id">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    </div>


    </div>

  </section>


</aside>