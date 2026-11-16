<?php
$this->setLayout('layouts/admin');
?>
<?php
$this->startSection('contenido');
?>
<div class="content-wrapper">
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <form class="form-inline">
                        <?php
                        $lu = new \App\View\Components\ListasusuarioscomboComponent(session()->get("usuario_id"));
                        echo $lu->render();
                        ?>
                    </form>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#divoi" role="tab" aria-selected="true">Otros Ingresos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#dive" role="tab" aria-selected="false">Egresos</a>
                        </li>
                        <!-- <li class="nav-item">
                            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#divt" role="tab" aria-selected="false">Transferencia</a>
                        </li> -->
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="divoi" role="tabpanel"><br>
                            <?php
                            $tipo = 'I';
                            $ie = new \App\View\Components\IngresosEgresosComponent($tipo);
                            echo $ie->render();
                            ?>
                        </div>
                        <div class="tab-pane fade" id="dive" role="tabpanel"><br>
                            <?php
                            $tipo = 'E';
                            $ie = new \App\View\Components\IngresosEgresosComponent($tipo);
                            echo $ie->render();
                            ?>
                        </div>
                        <!-- <div class="tab-pane fade" id="divt" role="tabpanel"><br>
                            <div class="mb-3 row">
                                <label for="" class="col-sm-2 col-form-label">Fecha</label>
                                <div class="col-sm-3">
                                    <input type="date" class="form-control" id="txtfechaf" value="<?php echo date('Y-m-d') ?>" readonly>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="" class="col-sm-2 col-form-label">Saldo</label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="txtsaldo" readonly placeholder="0.00">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="" class="col-sm-2 col-form-label">Monto a Transferir</label>
                                <div class="col-sm-3">
                                    <input type="number" class="form-control" id="txtmontoatransferir">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="" class="col-sm-2 col-form-label">Detalle</label>
                                <div class="col">
                                    <input type="text" class="form-control" id="txtdetallef">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-sm">
                                    <button type="button" onclick="limpiar();" class="btn btn-danger float-right"><i class="fas fa-refresh"></i> Limpiar</button>
                                    <button type="button" onclick="registrarTransferencia();" class="btn btn-success float-right"><i class="fas fa-plus-circle"></i> Registrar</button>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$this->endSection('contenido');
?>
<?php
$this->startSection('javascript');
?>
<script>
    window.onload = function() {
        titulo("<?php echo $titulo ?>");
        $("#cmbusuarios").attr('disabled', true);
        idserie = 0;
    }

    $(document).ready(function() {
        obtenernumeracion();
    });

    function obtenernumeracion() {
        axios.get('/cajas/obtenernumerodocumento').then(function(respuesta) {
            numerodocumento = respuesta.data.data;
            if (numerodocumento != '0') {
                llenarnumeracion(numerodocumento);
                idserie = respuesta.data.message;
            }
        }).catch(function(error) {
            toastr.error('Error al cargar el listado ' + error, 'Mensaje del sistema')
        });
    }

    function llenarnumeracion(documento) {
        $("#txtnumerodocumentoi").val('RI/' + documento);
        $("#txtnumerodocumentoi").attr("readonly", true);
        $("#txtnumerodocumentoe").val('VALE/' + documento);
        $("#txtnumerodocumentoe").attr("readonly", true);
    }

    function registrar(tipo) {
        if (tipo == 'I') {
            dtipo = 'Ingreso';
            registrarIngreso();
        } else {
            dtipo = 'Egreso'
            registrarEgreso();
        }
    }

    function registrarIngreso() {
        validacion = validarIngreso();
        if (validacion == true) {
            Swal.fire({
                title: "¿Grabar Ingreso?",
                text: "Se registrará en la base de datos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, proceder',
                cancelButtonText: 'No, cancelar'
            }).then(function(respuesta) {
                if (respuesta.isConfirmed) {
                    data = new FormData();
                    data.append("dfecha", $("#txtfechai").val());
                    data.append("cndoc", $("#txtnumerodocumentoi").val());
                    data.append("cdeta", $("#txtdetallei").val());
                    data.append("sdeudor", $("#txtimportei").val());
                    data.append("sacreedor", 0);
                    formadepago = $("#cmbformapago").val();
                    <?php $proyecto = (empty($_SESSION['config']['proyecto']) ? '' : $_SESSION['config']['proyecto']);
                    if ($proyecto == 'psysm'): ?>
                        formadepago = 'E';
                    <?php endif; ?>
                    data.append("cmbformapago", formadepago);
                    data.append("tipo", 'I');
                    data.append("idserie", idserie);
                    axios.post("/cajas/registrarIngresoEgreso", data)
                        .then(function(respuesta) {
                            Swal.fire({
                                icon: "success",
                                title: respuesta.data.message,
                                text: "El " + dtipo + " con el número " + $("#txtnumerodocumentoi").val() + ".",
                                showConfirmButton: false,
                                timer: 4750
                            });
                            limpiar();
                        }).catch(function(error) {
                            mostrarerroresvalidacion(error);
                        });
                }
            });
        } else {
            toastr.error("Complete los datos correctamente", 'Mensaje del Sistema');
        }
    }

    function registrarEgreso() {
        validacion = validarEgreso();
        if (validacion == true) {
            Swal.fire({
                title: "¿Grabar Egreso?",
                text: "Se registrará en la base de datos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, proceder',
                cancelButtonText: 'No, cancelar'
            }).then(function(respuesta) {
                if (respuesta.isConfirmed) {
                    data = new FormData();
                    data.append("dfecha", $("#txtfechae").val());
                    data.append("cndoc", $("#txtnumerodocumentoe").val());
                    data.append("cdeta", $("#txtdetallee").val());
                    data.append("sdeudor", 0);
                    data.append("sacreedor", $("#txtimportee").val());
                    formadepago = $("#cmbformapago").val();
                    <?php $proyecto = (empty($_SESSION['config']['proyecto']) ? '' : $_SESSION['config']['proyecto']);
                    if ($proyecto == 'psysm'): ?>
                        formadepago = 'E';
                    <?php endif; ?>
                    data.append("cmbformapago", formadepago);
                    data.append("tipo", 'E');
                    data.append("idserie", idserie);
                    axios.post("/cajas/registrarIngresoEgreso", data)
                        .then(function(respuesta) {
                            Swal.fire({
                                icon: "success",
                                title: respuesta.data.message,
                                text: "El " + dtipo + " con el número " + $("#txtnumerodocumentoe").val() + ".",
                                showConfirmButton: false,
                                timer: 4750
                            });
                            limpiar();
                        }).catch(function(error) {
                            mostrarerroresvalidacion(error);
                        });
                }
            });
        } else {
            toastr.error("Complete los datos correctamente", 'Mensaje del Sistema');
        }
    }

    function validarIngreso() {
        cndoc = $("#txtnumerodocumentoi").val();
        if (cndoc == '') {
            return false;
        }
        cdeta = $("#txtdetallei").val();
        if (cdeta == '') {
            return false;
        }
        importe = $("#txtimportei").val();
        if (importe == '' || importe == 0 || importe == '0') {
            return false;
        }
        return true;
    }

    function validarEgreso() {
        cndoc = $("#txtnumerodocumentoe").val();
        if (cndoc == '') {
            return false;
        }
        cdeta = $("#txtdetallee").val();
        if (cdeta == '') {
            return false;
        }
        importe = $("#txtimportee").val();
        if (importe == '' || importe == 0 || importe == '0') {
            return false;
        }
        return true;
    }

    function validarTransferencia() {
        cndoc = $("#txtmontoatransferir").val();
        if (cndoc == '') {
            return false;
        }
        cdeta = $("#txtdetallef").val();
        if (cdeta == '') {
            return false;
        }
        return true;
    }

    function registrarTransferencia() {
        validacion = validarTransferencia();
        if (validacion == true) {
            Swal.fire({
                title: "¿Grabar Transferencia?",
                text: "Se registrará en la base de datos.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, proceder',
                cancelButtonText: 'No, cancelar'
            }).then(function(respuesta) {
                if (respuesta.isConfirmed) {
                    data = new FormData();
                    data.append("dfecha", $("#txtfechaf").val());
                    data.append("fsaldo", $("#txtsaldo").val());
                    data.append("fmontoatransferir", $("#txtmontoatransferir").val());
                    data.append("cdeta", $("#txtdetallef").val());
                    axios.post("/cajas/registrarTransferencia", data)
                        .then(function(respuesta) {
                            Swal.fire({
                                icon: "success",
                                title: respuesta.data.message,
                                text: "La transferencia se realizó correctamente",
                                showConfirmButton: false,
                                timer: 4750
                            });
                            limpiar();
                        }).catch(function(error) {
                            mostrarerroresvalidacion(error);
                        });
                }
            });
        } else {
            toastr.error("Complete los datos correctamente", 'Mensaje del Sistema');
        }
    }

    function limpiar() {
        var elements = document.getElementsByTagName("input");
        for (var ii = 0; ii < elements.length; ii++) {
            if (elements[ii].type == "text") {
                elements[ii].value = "";
            }
        }
        $("#txtimportei").val('');
        $("#txtimportee").val('');
        $("#txtmontoatransferir").val('');
        obtenernumeracion();
    }
</script>
<?php
$this->endSection('javascript');
?>