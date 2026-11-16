<?php

use App\View\Components\EmpresaComponent;
use App\View\Components\ModalImprimir;

$this->setLayout('layouts/admin');
?>
<?php
$this->startSection('contenido');
?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <div class="input-group">
                        <label for="txtfecha" class="col-sm-0.5 col-form-label col-form-label-sm">Fecha: </label>
                        <div class="col-sm-1.5">
                            <input type="date" class="form-control form-control-sm" id="txtfecha" value="<?php echo date("Y-m-d") ?>">
                        </div>
                        <?php
                        $lu = new \App\View\Components\ListasusuarioscomboComponent($_SESSION['usuario_id']);
                        echo $lu->render();
                        ?>
                        <div class="col-sm-2">
                            <?php
                            $ec = new EmpresaComponent($_SESSION['idalmacen']);
                            echo $ec->render();
                            ?> &nbsp;
                        </div>
                        <div class="col-sm-1">
                            <button type="button" class="btn btn-success btn-sm" onclick="buscar()">Consultar</button>
                        </div>
                        <?php $proyecto = (empty($_SESSION['config']['proyecto']) ? '' : $_SESSION['config']['proyecto']); ?>
                        <label for="" class="" <?php echo ($proyecto == 'psysm' ? 'style="display:none;"' : ' ') ?>>Ingresos: </label>
                        <div class="col-sm-1" <?php echo ($proyecto == 'psysm' ? 'style="display:none;"' : ' ') ?>>
                            <input type="text" class="form-control form-control-sm text-end" id="txtingresos" value="" readonly>
                        </div>
                        <label for="" class="" <?php echo ($proyecto == 'psysm' ? 'style="display:none;"' : ' ') ?>>Egresos: </label>
                        <div class="col-sm-1" <?php echo ($proyecto == 'psysm' ? 'style="display:none;"' : ' ') ?>>
                            <input type="text" class="form-control form-control-sm text-end" id="txtegresos" value="" readonly>
                        </div>
                        <label for="" class="">Saldo Anterior: </label>
                        <div class="col-sm-1">
                            <input type="text" class="form-control form-control-sm text-end" id="txtsaldoanterior" value="" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div id="resultado"></div>
        </div>
        <div id="canvas" class="card border-light">
        </div>
    </div>
</div>
<!-- 
<div class="modal fade" id="modalversaldo" role="dialog" data-keyboard="true" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Saldos</h5>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label" for="">Saldo Anterior:</label>
                    <div class="col-sm-8">
                        <div class="input-group-append">
                            <input type="text" class="form-control form-control" name="txtsaldoanterior" id="txtsaldoanterior" readonly>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label" for="">Ingresos:</label>
                    <div class="col-sm-8">
                        <div class="input-group-append">
                            <input type="text" class="form-control form-control" name="txtingresos" id="txtingresos" readonly>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label" for="">Egresos:</label>
                    <div class="col-sm-8">
                        <div class="input-group-append">
                            <input type="text" class="form-control form-control" name="txtegresos" id="txtegresos" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
<!-- </div> -->
<?php
$oimp = new ModalImprimir();
echo $oimp->render();
?>
<?php
$this->endSection('contenido');
?>
<?php
$this->startSection('javascript');
?>
<script>
    window.onload = function() {
        titulo("<?php echo $titulo ?>");
        $('#griddetalle').DataTable({
            "responsive": true,
            "autoWidth": true,
            "searching": true,
            "paging": false
        });

        $("#cmbAlmacen option[value='0']").remove();
        tipocaja = "<?php echo (!empty($_SESSION['config']['cajaxtienda']) ? 'A' : 'U') ?>";
        tipousuario = "<?php echo $_SESSION['tipousuario'] ?>";

        if (tipocaja == 'A') {
            $("#cmbAlmacen").removeAttr("disabled");
            $("#cmbusuarios").attr("disabled", true);
            if (tipousuario != 'A') {
                $("#cmbAlmacen").attr("disabled", "disabled");
            }
        } else {
            $("#cmbusuarios").removeAttr("disabled");
            $("#cmbAlmacen").attr("disabled", true);
            if (tipousuario != 'A') {
                $("#cmbusuarios").attr("disabled", "disabled");
            }
        }
    }

    function buscar() {
        axios.get('/cajas/buscar', {
            "params": {
                "txtfech": $("#txtfecha").val(),
                "cmbusuarios": $("#cmbusuarios").val(),
                "cmbAlmacen": $("#cmbAlmacen").val()
            }
        }).then(function(respuesta) {
            $("#canvas").empty();
            $("#canvas").append(' <canvas id="myChart" width="400" height="100"></canvas>');
            const contenido_tabla = respuesta.data;
            $('#resultado').html(contenido_tabla);
        }).catch(function(error) {
            toastr.error('Error al cargar el listado' + error, 'Mensaje del sistema')
        });
    }

    function generarticketcaja(listamovimientos) {
        const numero = (id) => {
            const valor = document.getElementById(id)?.value ?? 0;
            return Number.parseFloat(valor) || 0;
        };

        let fecha = "";
        let usuario = "";

        if (listamovimientos && listamovimientos.length > 0) {
            fecha = listamovimientos[0].fechao || "";
            usuario = $("#cmbusuarios option:selected").text() || "";
        }
        const datos = {
            totalcaja: numero("txttotalcaja"),
            totalv: numero("txttotalvtas"),
            efectivosolovtas: numero("txtefectivosolovtas"),
            creditosolovtas: numero("txtcreditosolovtas"),
            depositosolovtas: numero("txtdepositosolovtas"),
            tarjetasolovtas: numero("txttarjetasolovtas"),
            anticipos: numero("txtanticipos"),
            otrosingresos: numero("txtotrosingresos"),
            pagosacta: numero("txtpagosacta"),
            egresos: numero("txtegresoscaja"),
            gananciatotal: numero("txtgananciatotal"),
            liquidacion: numero("txtliquidacion"),
            yape: numero("txtyape"),
            fecha: fecha,
            usuario: usuario,
            txtreferencia: $("#txtreferencia").val() || "",
            cmbalmacen: $("#cmbAlmacen option:selected").text() || "",
            sobrante: $("#txtsobrante").val() || 0
        };
        axios.get('/cajas/generarticketcaja', {
            params: datos
        }).then(function(respuesta) {
            const url = "ticketcaja.pdf";
            if (screen.width <= 768) {
                const req = new XMLHttpRequest();
                req.open("GET", url, true);
                req.responseType = "blob";
                req.onload = function() {
                    if (req.status === 200) {
                        const blob = req.response;
                        const link = document.createElement("a");
                        link.href = window.URL.createObjectURL(blob);
                        link.download = (respuesta.data.ndoc || "ticketcaja") + ".pdf";
                        link.click();
                        window.URL.revokeObjectURL(link.href);
                    } else {
                        toastr.error("No se pudo descargar el ticket", 'Mensaje del Sistema');
                    }
                };
                req.onerror = function() {
                    toastr.error("Error al descargar el ticket", 'Mensaje del Sistema');
                };
                req.send();
            } else {
                $("#pdfguia").attr("src", url);
                $("#abrirguia").click();
            }
        }).catch(function(error) {
            toastr.error("Error al generar ticket: " + (error.response?.data?.message || error.message), "Mensaje del sistema");
        });
    }
</script>
<?php
$this->endSection("javascript");
?>