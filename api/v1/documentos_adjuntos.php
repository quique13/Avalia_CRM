<div class="modal fade" id="modalVerAdjuntos">
    <div class="modal-dialog mw-100">
        <div class="modal-content">
            <div class="modal-header" id="headerVerAdjuntos" style="padding:5px 15px;">
                <h5 class="tittle">Documentos Adjuntos</h5>
                <button type="button" class="close" aria-label="Close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="divVerAdjunt" style="padding:5px 15px;">
                
                <div class="row" id="rows">
                    <div class="col-lg-4 col-md-4 col-xs-10" id="divListadoAdjuntos" style="padding:5px 15px;">
                        <form autocomplete="off" enctype="multipart/form-data" id="frmListaAdjunto"
                            name="frmListaAdjunto" method="POST">
                            <Label class="results">Nombre</label>

                            <div class="col-lg-12 col-md-12 col-xs-10">
                                <label class="nodpitext">Filtro:</label>
                                <select class="form-control" name="filtro-adjuntos" id="filtro-adjuntos"
                                    placeholder="Seleccione un filtro" onchange="verAdjuntos()" readonly>
                                </select>
                            </div>
                            <hr />

                            <div class="table-responsive">
                                <table id="resultadoAdjuntos" class="table table-sm table-hover" style="width:100%;">
                                </table>
                            </div>
                            <div id="divAlertAdjuntos" class="col-lg-12 col-md-12 col-xs-10" style="margin-bottom:10px">
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-10">
                                <label class="draganddroptexttitle" for="mail">Subir archivos aquí:</label>
                                <input class="draganddrop" type="file" id="fliesAdjuntos[]" name="fliesAdjuntos[]"
                                    placeholder="Arrastra y suelta aquí "
                                    accept="image/jpeg,image/gif,image/png,application/pdf,image/x-eps" multiple>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-10" style="text-align:center;padding:5px">
                                <button onclick="guardarAdjuntos()" class="guardar" type="button" <?php echo $disabledGuardar ?>>Adjuntar</button>
                                
                            </div>
                        </form>

                    </div>
                    <div class="col-lg-8 col-md-8 col-xs-10" id="divVerAdjuntos" style="padding:5px 15px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
