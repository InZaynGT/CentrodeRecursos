<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<style>
 .select-editable {
    background-color: white;
    border: solid grey 1px;
    width: 120px;
    height: 28px;
}

.select-editable select,
.select-editable input {
    font-size: 16px;
    border: none;
    width: 100%;
    margin: 0;
    padding: 3px;
    outline: none;
}

.ui-autocomplete {
    position: absolute;
    z-index: 1000;
    background-color: white;
    border: 1px solid #ccc;
    max-height: 200px;
    overflow-y: auto;
}

.ui-menu-item {
    font-size: 16px; /* Aumenta el tamaño de fuente de los elementos de la lista */
    list-style: none; /* Elimina la viñeta de los elementos de la lista */
}
.ui-helper-hidden-accessible {
    display: none;
}


</style>

<script>
    $(document).ready(function () {
        var availableTags2 = [
             <?php echo $tags2; ?>
        ];
         $("#nombreCompleto").autocomplete({
            source: availableTags2,
            messages: {
                noResults: ''
            }
        });


    })

</script>

<aside class="right-side">
    <section class="content-header">
        <h1>FICHA DE SEGUIMIENTO A USUARIO</h1>
    </section>
    <section class="container pb-5">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <form role="form" id="frmConsulta" method="POST">
                        <div class="box box-solid">
                            <br>
                            <div id="dataNav" class="container">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="nombreCompleto" class="col-form-label">Nombre Completo:</label>
                                            <input type="text" class="form-control" id="nombreCompleto" name="nombreCompleto"
                                                placeholder="Nombre Completo" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="fecha" class="col-form-label">Fecha:</label>
                                            <input type="date" class="form-control" id="fecha" name="fecha" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="observaciones">Observaciones:</label>
                                    <textarea class="form-control" name="observaciones" id="observaciones" rows="15"
                                        style="width: 91.5%;" required></textarea>
                                </div>

                                <div class="form-group mt-3">
                                    <button type="submit" id="Guardar" class="btn btn-primary">Guardar</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</aside>