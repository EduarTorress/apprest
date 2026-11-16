<?php

use App\View\Components\EmpresaComponent;
use App\View\Components\ModalDetalleDctoComponent;

$this->setLayout('layouts/admin');
?>
<?php
$this->startSection('contenido');
?>
<?php
$mprov = new \App\View\Components\ModalProveedorComponent();
echo $mprov->render();
?>
<div class="content-wrapper">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary card-outline">
                        <div class="card-body">
                            <form class="form-inline" id="form-search">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control form-control-sm" id="txtproveedor" aria-label="" aria-describedby="basic-addon2" placeholder="Proveedor" disabled value="<?php echo isset($datosproveedor['razo']) ?  trim($datosproveedor['razo']) : '' ?>">
                                    <input type="hidden" id="txtidproveedor" value=" ">
                                    <input type="hidden" id="txtrucproveedor" value="">
                                    <input type="hidden" id="txtptopartida" value=""><input type="hidden" id="txtUbigeoproveedor" value="">
                                    <button class="btn btn-outline-light" type="button" role="button" data-bs-toggle="modal" data-bs-target="#modal_proveedor"><i style="color:black" class="fas fa-user-alt"></i></button>
                                </div>
                                <?php
                                $ec = new EmpresaComponent($_SESSION['idalmacen']);
                                echo $ec->render();
                                ?> &nbsp;
                                <div class="input-group mb-3">
                                    <label class="my-1 mr-2" for="txtfechai"> Inicio</label>
                                    <input type="date" class="form-control form-control-sm" id="txtfechai" name="txtfechai" value="<?php echo date('Y-m-d') ?>">
                                </div>
                                <div class="input-group mb-3">
                                    <label class="my-1 mr-2" for="txtfechai">Hasta</label>
                                    <input type="date" class="form-control form-control-sm" id="txtfechaf" name="txtfechaf" value="<?php echo date('Y-m-d') ?>">
                                </div>
                                <div class="input-group mb-3">
                                    <button type="submit" id="btnconsultar" class="btn btn-primary my-1">Consultar</button>
                                </div>
                                <div class="input-group mb-3">
                                    <button type="button" id="btnconsultar" onclick="descargarpdf()" class="btn btn-warning my-1">Descargar PDF</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12" id="searchcomprasxprov">
            </div>
        </div>
    </div>
</div>
<?php
$md = new ModalDetalleDctoComponent();
echo $md->render();
?>
<?php
$this->endSection('contenido');
?>
<?php
$this->startSection('javascript');
?>
<script>
    document.getElementById('form-search').addEventListener('submit', function(evento) {
        evento.preventDefault();
        search();
    });

    window.onload = function() {
        titulo("<?php echo $titulo ?>");
        $("#cmbAlmacen").attr("disabled", false);
        $(".input-group").addClass("mb-3");
    }

    $("#modal_proveedor").on("shown.bs.modal", function() {
        moverCursorFinalTexto("txtbuscarprov");
    });

    function search() {
        txtidproveedor = $("#txtidproveedor").val();
        if (txtidproveedor == 0 || txtidproveedor == '') {
            toastr.error("Seleccione el Proveedor", 'Mensaje del Sistema');
            return;
        }
        var dfechai = document.getElementById("txtfechai").value;
        var dfechaf = document.getElementById("txtfechaf").value;
        $("#btnconsultar").attr('disabled', true);
        axios.get('/compras/listacomprasxprov', {
            "params": {
                "txtidproveedor": txtidproveedor,
                "dfechai": dfechai,
                "dfechaf": dfechaf,
                "cmbAlmacen": $("#cmbAlmacen").val()
            }
        }).then(function(respuesta) {
            $("#btnconsultar").attr('disabled', false);
            const contenido_tabla = respuesta.data;
            $('#searchcomprasxprov').html(contenido_tabla);
        }).catch(function(error) {
            $("#btnconsultar").attr('disabled', false);
            toastr.error('Error al cargar el listado', 'Mensaje del Sistema')
        });
    }

    function descargarpdf() {
        idproveedor = $("#txtidproveedor").val();
        if (idproveedor == "0") {
            toastr.error("Seleccione el proveedor", 'Mensaje del Sistema');
            return;
        }
        proveedor = $("#txtproveedor").val();
        fechai = $("#txtfechai").val();
        fechaf = $("#txtfechaf").val();
        cmbalmacen = $("#cmbAlmacen").val();
        var params = "idproveedor=" + idproveedor + "&txtfechai=" + fechai + "&txtfechaf=" + fechaf +
            "&cmbalmacen=" + cmbalmacen + "&proveedor=" + proveedor;
        var xhr = new XMLHttpRequest();
        var cruta = '/compras/exportarcomprasxproveedor';
        xhr.open('GET', cruta + "?" + params, true);
        xhr.responseType = 'blob';
        xhr.onload = function(e) {
            if (this.status == 200) {
                var blob = new Blob([this.response]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = "Sysven-Compras.pdf";
                link.click();
            }
        };
        xhr.send();
    }

    function consultarDetalle(detalle) {
        // console.log(detalle)
        $("#tbldetalle tbody").empty();
        detalle.forEach(function(d) {
            $("#lblmodaldetalle").text("Consultar Detalle: " + d.ndoc);
            var tr = `<tr> 
                    <td>` + d.descri + `</td>
                    <td>` + d.unid + `</td>
                    <td>` + d.cant + `</td>
                    <td>` + d.prec + `</td>
                    <td>` + d.importe + `</td>
                    </tr>`;
            $('#tbldetalle tbody').append(tr);
            $("#txtimportemodal").val("S/ " + d.impo)
        });
        $("#modaldetalle").modal('show');
    }

    function cerrarModal() {
        $("#modaldetalle").modal('hide');
    }
</script>
<?php
$this->endSection('javascript');
?>