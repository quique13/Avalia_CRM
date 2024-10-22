function verAdjuntos(idCliente,fase) {
    if(idCliente==undefined){
        var idCliente = $("#idOcaInfo").val();  
    }else{
        $("#idOcaInfo").val(idCliente);
    }
        $("#idClienteFha").val(idCliente);
    // var nombreCLiente = $("#nombreClienteInfo").val();
    var id_tipo_documento = $("#filtro-adjuntos_0 option:selected").val();
    var formData = new FormData;

    formData.append("idOcaCliente", idCliente);
    formData.append("id_tipo_documento", id_tipo_documento);
    formData.append("id_fase", fase);

    $("#modalVerAdjuntos").modal({
        backdrop: 'static',
        keyboard: false,
        show: true
    });
    $.ajax({
        url: "./cliente.php?get_adjuntos_listado_fha=true",
        type: "post",
        dataType: "json",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function () {
            $("#loadingConfirmacion").html('<img src="./dist/img/loading.gif" width="25px" alt="">');
        },
        success: function (response) {
            var output = ''
            $.each(response.listado_adjuntos, function (i, e) {
                output += `	<tr>
                                    <td style="width:90%;">
                                        <label class="nodpitext">${e.nombre}</label>
                                    </td>
                                    <td style="width:10%;">
                                        <button
                                            onclick="eliminarAdjuntos(${e.id_archivo})"
                                            class="btn btn-sm btn-danger"
                                            type="button"
                                        >
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </td>
                                </tr>`;
            });
            var tb = document.getElementById('resultadoAdjuntos');
            while (tb.rows.length >= 1) {
                tb.deleteRow(0);
            }
            $('#resultadoAdjuntos').append(output);
        },
        error: function () {
            $("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
        }
    });
    $("#divVerAdjuntos").html(`	</iframe>
                                        <iframe
                                            frameborder='0'
                                            type='application/pdf'
                                            style='width:100%; height:100%' align='right'
                                            src='./adjuntosFha.php/${$("#nombreClienteInfo").val()}?idCliente=${idCliente}&nombreCliente=${$("#nombreClienteInfo").val()}&id_tipo_documento=${id_tipo_documento}&fase=${fase}#page=1&zoom=80'
                                        >
                                    </iframe>`);
    verAdjuntosCarpetas(idCliente);
}
function verAdjuntosCarpetas(idCliente) {
    //alert(idCliente);
    var formData = new FormData;
    formData.append("idOcaCliente", idCliente);
    $.ajax({
        url: "./cliente.php?get_adjuntos_listado_fha_json=true",
        type: "post",
        dataType: "json",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function () {
            $("#loadingConfirmacion").html('<img src="./dist/img/loading.gif" width="25px" alt="">');
        },
        success: function (response) {
            var output = ''
            $.each(response.listado_adjuntos, function (i, e) {
               
            });
            $('#evts')
                .on("changed.jstree", function (e, data) {
                if(data.selected.length) {
                    alert('The selected node is: ' + data.instance.get_node(data.selected[0]).id);
                }
                })
                .jstree({
                'core' : {
                    'multiple' : false,
                    
                    
                    'data' : [
                        response.listado_adjuntos
                    ]
                }
            });
        },
        error: function () {
            $("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
        }
    });
}

function getFiltroAdjuntos(id_cliente,fase) {

    var idCliente = id_cliente!=''?id_cliente:$("#idClienteFha").val();
    const option = document.getElementById("filtro-adjuntos_"+fase);

    let formData = new FormData();
    formData.append("idOcaCliente", idCliente);
    formData.append("fase", fase);

    $.ajax({
        url: "./cliente.php?get_filtros_adjuntos_fha=true",
        type: "post",
        dataType: "json",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function () {
            $("#loadingConfirmacion").html('<img src="./dist/img/loading.gif" width="25px" alt="">');
        },
        success: function (response) {
            var output;
            output += ' <option value="0">Mostrar todos</option>';
            $.each(response.filtros_adjuntos, function (i, e) {
                output += `<option value="${e.id_tipo_documento}">${e.nombre}</option>`;
            });
            for (let i = option.options.length; i >= 0; i--) {
                option.remove(i);
            }
            $('#filtro-adjuntos_'+fase).append(output);
        },
        error: function () {
            $("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
        }
    });
}

function eliminarAdjuntos(id_archivo) {
    var formData = new FormData;
    formData.append("id_archivo", id_archivo);
    $.ajax({
        url: "./cliente.php?deleteAdjunto_fha=true",
        type: "post",
        dataType: "json",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function () {
            $("#loadingConfirmacion").html('<img src="./dist/img/loading.gif" width="25px" alt="">');
        },
        success: function (response) {
            $("#resultadoAdjuntos").html('');
            $("#divVerAdjuntos").html('');
            verAdjuntos();
        },
        error: function () {
            $("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
        }
    });
}

function guardarAdjuntos(idCliente) {
    var idCliente = $("#idOcaInfo").val();
    var id_tipo_documento = $("#filtro-adjuntos_0 option:selected").val();
    var formData = new FormData(document.getElementById("frmListaAdjunto"));
    // var adjuntos = $("#fliesAdjuntos").val();

    if (!id_tipo_documento || id_tipo_documento == 0 ) {
        $("#divAlertAdjuntos").html('<div class="alert alert-danger">Por favor seleccione un tipo de documento...</div>');
        setTimeout(function(){
            $("#divAlertAdjuntos").html('');
        },5000)
        return true;
    }

    formData.append("idOcaCliente", idCliente);
    formData.append("id_tipo_documento", id_tipo_documento);

    $.ajax({
        url: "./cliente.php?guardarAdjunto_fha=true",
        type: "post",
        dataType: "json",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function () {
            $("#loadingConfirmacion").html('<img src="./dist/img/loading.gif" width="25px" alt="">');
        },
        success: function (response) {
            $("#resultadoAdjuntos").html('');
            $("#divVerAdjuntos").html('');
            verAdjuntos();
        },
        error: function () {
            $("#loadingConfirmacion").html('<div class="alert alert-danger"><b>Error </b> Intente nuevamente</div>');
        }
    });
}
