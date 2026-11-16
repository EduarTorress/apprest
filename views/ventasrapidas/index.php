<?php

use App\View\Components\DocumentoComponent;
use App\View\Components\FechaComponent;
use App\View\Components\FechavtoComponent;
use App\View\Components\FormadepagoComponent;
use App\View\Components\VendedorComponent;
use App\View\Components\TipoMonedaComponent;
use App\View\Components\ModalClienteComponent;
use App\View\Components\ModalProductoComponent;
use App\View\Components\ModalImprimir;
use App\View\Components\IGVComponent;
use App\View\Components\ModalConfirmarLoginComponent;

$this->setLayout('layouts/admin');
?>
<?php
$this->startSection('contenido');
?>
<?php
$clie = new ModalClienteComponent();
echo $clie->render();
$prod = new ModalProductoComponent();
echo $prod->render();
$login = new ModalConfirmarLoginComponent();
echo $login->render();
?>
<div class="modal fade" id="mdpreregistro" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="">Pre Registro</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <?php
                        $ctdoc = isset($datosclientev['tdocv']) ? $datosclientev['tdocv'] : '';
                        $dctos = new DocumentoComponent($ctdoc);
                        echo $dctos->render();
                        ?> <br>
                    </div>
                    <div class="col-sm-12">
                        <div class="input-group">
                            <input type="text" class="form-control form-control-sm" id="txtcliente" placeholder="Cliente" disabled value="VENTAS DEL DIA">
                            <input type="hidden" id="txtidcliente" value="2">
                            <input type="hidden" id="txtruccliente" value="">
                            <input type="hidden" id="txtdireccion" value="">
                            <input type="hidden" id="txtdnicliente" value="">
                            <input type="hidden" id="txtidauto" value="">
                            <input type="hidden" id="txtclienteretencion" value="">
                            <button class="btn btn-outline-light" role="button" data-bs-toggle="modal" data-bs-target="#modal_clientes"><i style="color:black" class="fas fa-user-alt"></i></button>
                            <button class="btn btn-outline-primary" role="button" onclick="mostrardatoscliente()"><i style="color:black" class="fa fa-address-card-o"></i></button>
                        </div><br>
                    </div>
                    <div class="col-sm-2" style="display:none">
                        <div class="input-group">
                            <label class="col-sm-0 col-form-label col-form-label-sm">Guía R. :</label>
                            <input type="text" onkeyup="mayusculas(this);" class="form-control form-control-sm" id="ndo2" style="width: 100px;" value="<?php echo isset($datosclientev['ndo2v']) ?  $datosclientev['ndo2v'] : '' ?>" placeholder="T001-00001">
                        </div>
                    </div>
                    <div class="col-sm-2" style="display:none">
                        <?php
                        $cempresa = isset($datosclientev['almv']) ? $datosclientev['almv'] : $_SESSION['idalmacen'];
                        $empresa = new \App\View\Components\EmpresaComponent($cempresa);
                        echo $empresa->render();
                        ?>
                    </div>
                    <div class="col-sm-6">
                        <?php
                        $fecha = new FechaComponent();
                        echo $fecha->render();
                        ?>
                    </div>
                    <div class="col-sm-6" style="display:none">
                        <?php
                        $cmon = isset($datosclientev['monev']) ? $datosclientev['monev'] : '';
                        $tpmoneda = new TipoMonedaComponent($cmon);
                        echo $tpmoneda->render();
                        ?>
                    </div>
                    <div class="col-sm-6">
                        <?php
                        $cforma = isset($datosclientev['formv']) ? $datosclientev['formv'] : '';
                        $formapago = new FormadepagoComponent($cforma);
                        echo $formapago->render();
                        ?>
                    </div>
                    <div class="col-sm-6">
                        <?php
                        $dfechavto = new FechavtoComponent();
                        echo $dfechavto->render();
                        ?>
                    </div>
                    <div class="col-sm-6">
                        <?php
                        $nidv = isset($datosclientev['idvenv']) ? $datosclientev['idvenv'] : 0;
                        $vendedor = new VendedorComponent($nidv);
                        echo $vendedor->render();
                        ?>
                        <div class="form-group row" style="display: none">&nbsp;&nbsp;&nbsp;
                            <label class="col-sm-0 col-form-label col-form-label-sm">Fecha Vcto. :</label>
                            <input type="date" class="form-control form-control-sm" value="<?php echo empty($datosclientev['fechvV']) ?  date("Y-m-d") :  $datosclientev['fechvV'] ?>" style="width:140px;" id="txtfechav" name="txtfechav">
                        </div>
                    </div>
                    <?php $multiigv = (empty($_SESSION['config']['multiigv'])) ? 'N' : $_SESSION['config']['multiigv']; ?>
                    <div class="col-sm-6" style="<?php echo ($multiigv == 'S' ? '' : 'display:none') ?>">
                        <?php
                        $optigv = isset($datosclientev['optigv']) ? $datosclientev['optigv'] : '';
                        $igv = new IGVComponent($optigv);
                        echo $igv->render();
                        ?>
                    </div>
                    <div class="col-sm-12">
                        <input type="text" class="form-control form-control-sm" onkeypress="entergrabar(event);" name="txtreferencia" placeholder="Referencia" id="txtreferencia" value="<?php echo (isset($datosclientev['txtreferencia']) ? $datosclientev['txtreferencia'] : '') ?>">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary text-white" id="btngrabar" onclick="registrarvta();">Guardar</button>
                <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div class="content-wrapper">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-success card-outline" style="width:max-content; width:auto;">
                        <div class="col-12" id="detalle">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$oimp = new ModalImprimir();
echo $oimp->render();
?>
<style>
    #cliente {
        color: black
    }

    #txtbuscar {
        background-color: white;
        color: black;
    }

    #tabla_clientes {
        color: black;
    }
</style>
<?php
$this->endSection('contenido');
?>
<?php
$this->startSection('javascript');
?>
<script>
    window.onload = function() {
        idcliente = 0;
        titulo("<?php echo $titulo ?>");
        listardetalle();
        $("#txtfecha").val("<?php echo (empty($datosclientev['fechv']) ? date("Y-m-d") : $datosclientev['fechv']) ?>")
        $("#txtdias").val("<?php echo (empty($datosclientev['dias']) ?  '' :  $datosclientev['dias']) ?>")
        $("#txtfechavto").val("<?php echo (empty($datosclientev['fvto']) ?  date("Y-m-d") :  $datosclientev['fvto']) ?>")
        $("#modal_productos").modal('show');
        $("#cmbdcto").val("03");
        provieneopcion = "carrito";
    }

    function listardetalle() {
        axios.get('/ventasrapidas/listardetalle').then(function(respuesta) {
            // 100, 200, 300
            const contenido_tabla = respuesta.data;
            $('#detalle').html(contenido_tabla);
            $(".codigo").css("display", "none");
            <?php if ($multiigv != 'S') : ?>
                $(".preciosgv").css("display", "none");
            <?php endif; ?>
            ventascondescuento = "<?php echo (empty($_SESSION['config']['ventascondescuento']) ? 'N' : $_SESSION['config']['ventascondescuento']); ?>";
            if (ventascondescuento == 'N') {
                $(".descuento").css("display", "none");
            }
            // $("#txtfecha").attr("disabled", true)
            $("#cndoc1").val("<?php echo (isset($serie) ?  $serie : '') ?>");
            calcularIGV();
            <?php if ($_SESSION['moneda'] == 'SI') : ?>
                $("#cmbmoneda").attr('disabled', true);
            <?php endif; ?>
            <?php if ($_SESSION['opigv'] == 'N') : ?>
                $(".igv").attr('disabled', true);
            <?php endif; ?>
            facturardolares = "<?php echo (!empty($_SESSION['config']['facturardolares']) ? $_SESSION['config']['facturardolares'] : 'N'); ?>";
            if (facturardolares == 'N') {
                $(".cmbmoneda option[value='D']").remove();
            }
            $(".tipodocumentos option[value='07']").remove();
            $(".tipodocumentos option[value='08']").remove();
            $(".tipodocumentos option[value='GI']").remove();
        }).catch(function(error) {
            // 400, 500
            toastr.error('Error al cargar el listado' + error, 'Mensaje del sistema')
        });
    }

    function entertipodocumento(u) {
        u.onkeypress = function(e) {
            var keyCode = (e.keyCode || e.which);
            if (keyCode === 13) {
                if (enterPressed == 0) {} else if (enterPressed >= 1) {
                    e.preventDefault();
                    tipodocumento = $(u).val();
                    txtidcliente = $("#txtidcliente").val();
                    if ((tipodocumento == '03' || tipodocumento == '20') && (txtidcliente == '2')) {
                        registrarvta();
                    } else {
                        $("#mdpreregistro").modal('hide');
                        $("#modal_clientes").modal('show');
                    }
                }
                enterPressed++;
                return;
            }
        };
    }

    // var code = e.keyCode || e.which;
    // if (code == 46) {
    //     $("#searchP").empty();
    //     $("#txtbuscarProducto").select();
    // }

    // $("input[type='search']").val("Ventas x Producto");

    $('#modal_clientes').on('hidden.bs.modal', function() {
        grabarCabecera();
        $("#mdpreregistro").modal('show');
        provieneopcion = 'clientes';
    });

    $('#mdpreregistro').on('shown.bs.modal', function() {
        if (provieneopcion == 'carrito') {
            $("#cmbdcto").focus();
            $("#cmbdcto").click();
            enterPressed = 0;
        } else {
            $("#txtreferencia").focus();
            $("#txtreferencia").click();
        }
    });

    function entergrabar(e) {
        if (e.keyCode === 13 && !e.shiftKey) {
            registrarvta();
        }
    }

    // $('body').on('keyup', function(e) {
    //     if (e.shiftKey) {
    //         $("#modal_productos").modal('show');
    //     }
    // });

    $('#divfecha').click(function() {
        $("#txtfecha").prop("readonly", false);
    });

    function verutilidad() {
        $("#modalConfirmarLogin").modal("show");
    }

    function cerrarModal() {
        $("#modalConfirmarLogin").modal("hide");
    }

    function consultarlogin() {
        data = new FormData();
        data.append("txtUsuario", document.getElementById("txtUsuario").value);
        data.append("txtPassword", document.getElementById("txtPassword").value);
        axios.post("/vtas/verutilidad", data)
            .then(function(respuesta) {
                Swal.fire({
                    title: "La ganancia calculada correctamente",
                    text: "La ganancia es: " + respuesta.data.message,
                    icon: "success"
                });
                $("#divutilidad").css("display", "");
                $("#txtutilidad").val(respuesta.data.message);
                $("#modalConfirmarLogin").modal("hide");
            }).catch(function(error) {
                if (error.hasOwnProperty("response")) {
                    if (error.response.status == 422) {
                        toastr.error(error.response.data.message, 'Mensaje del sistema');
                    }
                } else {
                    toastr.error("Ocurrió un error" + error, 'Mensaje del sistema')
                }
            });
    }

    $(document).on('keydown', function(event) {
        if (event.key == "Escape") {
            $("#modal_productos").modal('hide');
        }
    });

    function quitaritem(pos, idart) {
        btnagregar = "#agregar" + idart;
        const data = new FormData();
        data.append("indice", pos)
        axios.post('/ventasrapidas/quitaritem', data)
            .then(function(respuesta) {
                const contenido_tabla = respuesta.data;
                $('#detalle').html(contenido_tabla);
                calcularIGV();
                $(btnagregar).removeAttr('disabled');
                // $(btnagregar).attr('disabled', false);
                // $('#totalpedido').html(document.querySelector("#total").value);
            }).catch(function(error) {
                toastr.error(error, 'Mensaje del sistema');
            });
    }

    function grabarCabecera() {
        // data = new FormData();
        // data.append("idcliev", $("#txtidcliente").val());
        // data.append("razov", $("#txtcliente").val());
        // data.append("ruccliev", $("#txtruccliente").val());
        // data.append("tdocv", $("#cmbdcto").val());
        // data.append("cndocv", "");
        // data.append("numv", "");
        // var optigv = obtenerTipoIGV();
        // data.append("optigv", optigv);
        // data.append("ndo2v", $("#ndo2").val());
        // data.append("almv", $("#cmbAlmacen").val());
        // data.append("fechv", $("#txtfecha").val());
        // data.append("monev", $("#cmbmoneda").val());
        // data.append("formv", $("#cmbforma").val());
        // data.append("fechvv", $("#txtfechav").val());
        // data.append("idvenv", $("#cmbvendedor").val());
        // data.append("txtreferencia", $("#txtreferencia").val());
        // axios.post("/vtas/sesion", data)
        //     .then(function(respuesta) {
        //         // console.log("Se registro la cabecera en la sesión")
        //     }).catch(function(error) {
        //         toastr.error("Error al guardar sesión", "Mensaje del Sistema");
        //     });
    }

    $("#modal_productos").on("shown.bs.modal", function() {
        cerrarTooltipPrecios();
        filastbl = document.getElementById("griddetalle").rows.length;
        if (filastbl <= 1) {
            moverCursorFinalTexto("txtbuscarProducto");
        }
        if (document.getElementById('codigo').checked) {
            moverCursorFinalTexto("txtbuscarProducto");
            $("#txtbuscarProducto").select();
        }
    });

    $("#modal_clientes").on("shown.bs.modal", function() {
        moverCursorFinalTexto("txtbuscar");
    });

    document.addEventListener('keyup', function(event) {
        if (event.ctrlKey && event.keyCode === 13) {
            $("#cmdbuscarP").click();
        }
    });

    // document.addEventListener("keydown", (e) => {
    //     switch (e.key) {
    //         case "Delete":
    //             $("#txtbuscarProducto").focus();
    //             break;
    //         default:
    //             break;
    //     }
    // });

    function agregarunitemVenta(datos) {
        precioventa = 0;
        <?php $preciodefault = (empty($_SESSION['gene_precdefault']) ? '' : trim($_SESSION['gene_precdefault'])); ?>
        <?php switch ($preciodefault):
            case 'menor': ?>
                precioventa = datos.parametro7;
            <?php break;
            case 'especial': ?>
                precioventa = datos.parametro6;
            <?php break;
            case 'mayor': ?>
                precioventa = datos.parametro5;
            <?php break;
            default: ?>
                precioventa = datos.parametro5;
        <?php endswitch; ?>
        const data = new FormData();
        data.append('txtcodigo', datos.parametro2);
        data.append("txtdescripcion", datos.parametro1);
        data.append("txtunidad", datos.parametro3);
        data.append("txtprecio", precioventa);
        data.append("txtcantidad", 1);
        data.append("precio1", datos.parametro5);
        data.append("precio2", datos.parametro6);
        data.append("precio3", datos.parametro7);
        data.append("costo", datos.parametro8);
        data.append("tipoproducto", datos.parametro10);
        data.append("cmbmoneda", $("#cmbmoneda").val());
        data.append("stock", parseFloat(datos.parametro4.toFixed(2)));
        data.append("opt", 0)
        data.append("prod_detr", datos.prod_detr);
        axios.post('/ventasrapidas/agregaritem', data)
            .then(function(respuesta) {
                $('#modal_productos').modal('hide')
                const contenido_tabla = respuesta.data;
                $('#detalle').html(contenido_tabla);
                calcularIGV();
                idart = "#agregar" + datos.parametro2;
                // console.log(idart);
                $(idart).attr('disabled', 'disabled');
            }).catch(function(error) {
                if (error.hasOwnProperty("response")) {
                    if (error.response.status == 422) {
                        if (error.response.data.errors) {
                            e = error['response']['data']['errors']
                            result = []
                            for (var i in e) {
                                result.push([i, e[i]]);
                            }
                            result.forEach(function(numero) {
                                toastr.error(numero[1], 'Mensaje del sistema')
                            });
                        } else {
                            errors = error.response.data.message;
                            toastr.error(errors, "Mensaje del Sistema")
                        }
                    }
                }
            });
    }

    $('#modal_productos').on('hidden.bs.modal', function() {
        var a = $("#griddetalle tr:last td:eq(4)");
        $(a).find("input").click();
        $(a).find("input").focus();
    });

    function calcularIGV() {
        columnatotal = ($("#griddetalle").find("thead tr:first th").length) - 1;
        igv = obtenerTipoIGV();
        var total_col = 0;
        valorigv = Number("<?php echo $_SESSION['gene_igv']; ?>");
        $('#griddetalle tbody').find('tr').each(function(i, el) {
            t = $(this).find('td').eq(columnatotal).text();
            // t = t.replace(",", "")
            total_col += parseFloat(t);
        });
        if (igv == 'I') {
            $('#griddetalle tbody tr').each(function() {
                $(this).find(".preciosgv").html("");
            });
        } else {
            $('#griddetalle tbody tr').each(function() {
                precio = $(this).find(".precio input").val();
                preciosgv = precio / Number(valorigv);
                $(this).find(".preciosgv").html(Number(preciosgv).toFixed(2));
            });
        }
        let impo = (Number(total_col)).toFixed(2);
        let valor = (impo / valorigv).toFixed(2);
        let nigv = (impo - valor).toFixed(2);
        $("#igv").val(nigv);
        $("#subtotal").val(valor);
        $("#total").val(impo);
        let impor = document.querySelector("#total").value;
        if (isNaN(impor)) {
            $("#subtotal").val("0.00");
            $("#igv").val("0.00");
            $("#total").val("0.00");
        }
    }

    function mostrardatoscliente() {
        txtruccliente = $("#txtruccliente").val();
        txtdireccion = $("#txtdireccion").val();
        txtdnicliente = $("#txtdnicliente").val();
        Swal.fire("RUC: " + txtruccliente + ". <br> DNI: " + txtdnicliente + ". <br>DIRECCIÓN: " + txtdireccion + ".");
    }

    function validarVenta() {
        idcliente = document.querySelector('#txtidcliente').value;
        total = document.querySelector('#total').value;
        ctdoc = $('#cmbdcto option:selected').val();
        ruc = document.querySelector('#txtruccliente').value;
        if (idcliente == 0) {
            toastr.info("Seleccione un Cliente", 'Mensaje del Sistema');
            return false;
        }
        if (total == 0) {
            toastr.info("Ingrese Importes Válidos", 'Mensaje del Sistema');
            return false;
        }
        if (ctdoc == '01' && ruc.trim() == '') {
            toastr.info("Se necesita que el Cliente tenga RUC para hacer una Factura", 'Mensaje del Sistema');
            return false;
        }
        if (ctdoc == '01' && ruc == 0) {
            toastr.info("Se necesita que el Cliente tenga RUC para hacer una Factura", 'Mensaje del Sistema');
            return false;
        }
        return true;
    }

    function preregistro() {
        importe = $("#total").val();
        if (importe == "0.00") {
            toastr.info("Agregue productos a la venta", 'Mensaje del Sistema');
            return;
        }
        $("#mdpreregistro").modal('show');
    }

    function registrarvta() {
        $("#btngrabar").attr("disabled", true);
        if (!validarVenta()) {
            $("#btngrabar").removeAttr("disabled");
            return;
        }
        cmensaje = '¿Registrar Venta?';
        grabar(cmensaje);
    }

    function changedetaildolar() {
        moneda = $("#cmbmoneda").val();
        const data = new FormData();
        data.append('moneda', moneda);
        axios.post('/detail/changedolar', data)
            .then(function(respuesta) {
                const contenido_tabla = respuesta.data;
                $('#detalle').html(contenido_tabla);
                calcularIGV();
                $("#cmbmoneda").attr('disabled', true);
            }).catch(function(error) {
                if (error.hasOwnProperty("response")) {
                    if (error.response.status == 422) {
                        toastr.error(error.response.data.errors, "Mensaje del Sistema");
                    }
                } else {
                    toastr.error("Ocurrió un error" + error, 'Mensaje del sistema')
                }
            });
    }

    function changeoptigv() {
        $(".igv").attr('disabled', true);
    }

    function limpiardatos() {
        $("#cmbmoneda").attr('disabled', false);
        <?php $_SESSION['moneda'] = 'NO'; ?>
        <?php $_SESSION['opigv'] = 'I'; ?>
        $("#txtcliente").val("VENTAS DEL DÍA");
        $("#txtdias").val("");
        $("#titulo").val("Venta Rápida");
        $("#txtidcliente").val("2");
        $("#txtruccliente").val("");
        $("#ndo2").val("");
        $("#cmbforma").val("E");
        // $("#cmbAlmacen").val("1");
        $("#cmbmoneda").val("S");
        $("#optigv").val("I");
        // $("#cmbvendedor").val("1");
        $("#total").val("0.00");
        $("#igv").val("0.00");
        $("#subtotal").val("0.00");
        $("#totalitems").val("0.00");
        $("#txtreferencia").val("");
        $("#searchP").empty();
        $("#btngrabar").removeAttr("disabled");
        provieneopcion = "carrito";
        document.getElementById("grabar").innerHTML = "Grabar";
    }

    function grabar(cmensaje) {
        data = new FormData();
        data.append("idcliev", $("#txtidcliente").val());
        data.append("razov", $("#txtcliente").val());
        data.append("tdocv", $("#cmbdcto").val());
        data.append("txtdireccion", $("#txtdireccion").val());
        data.append("txtruccliente", $("#txtruccliente").val());
        data.append("txtdnicliente", $("#txtdnicliente").val());
        data.append("ndo2v", $("#ndo2").val());
        data.append("almv", $("#cmbAlmacen").val());
        data.append("fechv", $("#txtfecha").val());
        data.append("monev", $("#cmbmoneda").val());
        data.append("formv", $("#cmbforma").val());
        data.append("fechvv", $("#txtfechavto").val());
        let tigv = obtenerTipoIGV();
        data.append("optigv", tigv);
        data.append("idvenv", $("#cmbvendedor").val());
        data.append("subtotal", $("#subtotal").val());
        data.append("igv", $("#igv").val());
        data.append("total", $("#total").val());
        data.append("txtreferencia", $("#txtreferencia").val());
        <?php $ventascondescuento = (empty($_SESSION['config']['ventascondescuento']) ? 'N' : $_SESSION['config']['ventascondescuento']);
        if ($ventascondescuento == 'S') : ?>
            data.append("descuentogeneral", $("#descuentogeneral").val());
        <?php endif; ?>
        axios.post("/vtas/registrar", data)
            .then(function(respuesta) {
                $("#mdpreregistro").modal('hide');
                $("#btngrabar").removeAttr("disabled");
                toastr.success(respuesta.data.mensaje.trimEnd() + ' ' + respuesta.data.ndoc, 'Mensaje del Sistema');
                $('#griddetalle tbody tr').remove();
                var cruta = '/vtas/imprimirdirecto/';
                var xhr = new XMLHttpRequest();
                xhr.open('GET', cruta, true);
                xhr.responseType = 'blob';
                xhr.onload = function(e) {
                    if (this.status == 200) {
                        var w = screen.width;
                        url = location.protocol + '//' + document.domain + '/descargas/' + respuesta.data.ndoc + ".pdf"
                        if (w <= 800) {
                            var req = new XMLHttpRequest();
                            req.open("GET", url, true);
                            req.responseType = "blob";
                            req.onload = function(event) {
                                var blob = req.response;
                                // console.log(blob.size);
                                var link = document.createElement('a');
                                link.href = window.URL.createObjectURL(blob);
                                link.download = respuesta.data.ndoc + ".pdf"
                                link.click();
                            };
                            req.send();
                        } else {
                            $("#pdfguia").attr("src", url)
                            $("#abrirguia").click();
                            // $("#exampleModal").modal('hide');
                        }
                    }
                };
                xhr.send();
                limpiardatos();
            }).catch(function(error) {
                $("#btngrabar").removeAttr("disabled");
                mostrarerroresvalidacion(error);
            });
    }

    // function actualizar(cmensaje) {
    //     Swal.fire({
    //         title: cmensaje,
    //         text: "Se modificará en la Base de datos ",
    //         icon: 'question',
    //         showCancelButton: true,
    //         confirmButtonColor: '#3085d6',
    //         cancelButtonColor: '#d33',
    //         confirmButtonText: 'Si'
    //     }).then(function(respuesta) {
    //         if (respuesta.isConfirmed) {
    //             data = new FormData();
    //             data.append("idautov", $("#txtidauto").val());
    //             data.append("idcliev", $("#txtidcliente").val());
    //             data.append("razov", $("#txtcliente").val());
    //             data.append("tdocv", $("#cmbdcto").val());
    //             data.append("ndo2v", $("#ndo2").val());
    //             data.append("almv", $("#cmbAlmacen").val());
    //             data.append("fechv", $("#txtfecha").val());
    //             data.append("monev", $("#cmbmoneda").val());
    //             data.append("formv", $("#cmbforma").val());
    //             data.append("fechvv", $("#txtfechavto").val());
    //             let tigv = obtenerTipoIGV();
    //             data.append("optigv", tigv);
    //             data.append("idvenv", $("#cmbvendedor").val());
    //             data.append("subtotal", $("#subtotal").val());
    //             data.append("igv", $("#igv").val());
    //             data.append("total", $("#total").val());
    //             data.append("txtreferencia", $("#txtreferencia").val());
    //             axios.post("/vtas/actualizar", data)
    //                 .then(function(respuesta) {
    //                     toastr.success(' Se actualizo correctamente ');
    //                     const tabla = respuesta.data;
    //                     $('#detalle').html(tabla);
    //                     window.location.href = '/vtas/vtasresumidas';
    //                     limpiardatos();
    //                 }).catch(function(error) {
    //                     mostrarerroresvalidacion(error);
    //                 });
    //         }
    //     });
    // }

    const onFocus = () => {
        $("#exampleModal").modal('hide');
        $('#modal_productos').modal('show');
        $("#mdpreregistro").modal('hide');
        listardetalle();
    }
    window.addEventListener("focus", onFocus)

    function cancelarVenta() {
        axios.post('/ventasrapidas/limpiar').then(function(respuesta) {
            const tabla = respuesta.data;
            $('#detalle').html(tabla);
            window.location.href = '/ventasrapidas/index';
        }).catch(function(error) {
            toastr.error(error, 'Mensaje del sistema');
        });
    }

    //Eventos
    var txtfecha = document.getElementById("txtfecha");
    txtfecha.addEventListener("blur", function(event) {
        fech = $("#txtfecha").val();
        grabarCabecera();
    }, true);

    var txtfechav = document.getElementById("txtfechav");
    txtfechav.addEventListener("blur", function(event) {
        grabarCabecera();
    }, true);

    var ndo2 = document.getElementById("ndo2");
    ndo2.addEventListener("blur", function(event) {
        grabarCabecera();
    }, true);

    var txtreferencia = document.getElementById("txtreferencia");
    txtreferencia.addEventListener("blur", function(event) {
        grabarCabecera();
    }, true);

    $("#cmbvendedor").on("change", function() {
        grabarCabecera();
    });
</script>
<?php
$this->endSection("javascript");
?>