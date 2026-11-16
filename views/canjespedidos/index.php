<?php

use App\View\Components\DocumentoComponent;
use App\View\Components\FechaComponent;
use App\View\Components\FechavtoComponent;
use App\View\Components\FormadepagoComponent;
use App\View\Components\VendedorComponent;
use App\View\Components\TipoMonedaComponent;
use App\View\Components\ModalClienteComponent;
use App\View\Components\ModalProductoComponent;
use App\View\Components\ModalPedidosComponent;
use App\View\Components\ModalImprimir;
use App\View\Components\IGVComponent;

$this->setLayout('layouts/admin');
?>
<?php
$this->startSection('contenido');
?>
<?php
$clie = new ModalClienteComponent();
echo $clie->render();
$mp = new ModalPedidosComponent();
echo $mp->render();
$prod = new ModalProductoComponent();
echo $prod->render();
?>
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-sm-4">
                    <div class="input-group ">
                        <button class="btn btn-light" role="button" data-bs-toggle="modal" data-bs-target="#modal_pedidos"><i class="fa fa-file-text-o" aria-hidden="true"></i></button> &nbsp;&nbsp;
                        <input type="text" class="form-control form-control-sm" id="txtcliente" placeholder="Cliente" disabled value="">
                        <input type="hidden" id="txtidcliente" value="">
                        <input type="hidden" id="txtruccliente" value="">
                        <input type="hidden" id="txtdireccion" value="">
                        <input type="hidden" id="txtclienteretencion" value="N">
                        <input type="hidden" id="txtdnicliente" value="">
                        <input type="hidden" id="txtidautop" value="0">
                        <input type="hidden" id="idusua" value="0">
                        <input type="hidden" id="usuario" value="">
                    </div>
                </div>
                <div class="col-sm-2">
                    <?php
                    $dctos = new DocumentoComponent('');
                    echo $dctos->render();
                    ?>
                </div>
                <div class="col-sm-2">
                    <div class="input-group">
                        <label class="col-sm-0 col-form-label col-form-label-sm">Guía R. :</label>
                        <input type="text" onkeyup="mayusculas(this);" class="form-control form-control-sm" id="ndo2" style="width: 100px;" value="" placeholder="T001-00001">
                    </div>
                </div>
                <div class="col-sm-2">
                    <?php
                    $empresa = new \App\View\Components\EmpresaComponent($_SESSION['idalmacen']);
                    echo $empresa->render();
                    ?>
                </div>
                <div class="col-sm-2">
                    <?php
                    $fecha = new FechaComponent();
                    echo $fecha->render();
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">
                    <?php
                    $tpmoneda = new TipoMonedaComponent('S');
                    echo $tpmoneda->render();
                    ?>
                </div>
                <div class="col-sm-2">
                    <?php
                    $formapago = new FormadepagoComponent('E');
                    echo $formapago->render();
                    ?>
                </div>
                <div class="col-sm-3">
                    <?php
                    $dfechavto = new FechavtoComponent();
                    echo $dfechavto->render();
                    ?>
                </div>
                <div class="col-sm-2">
                    <?php
                    $vendedor = new VendedorComponent(0);
                    echo $vendedor->render();
                    ?>
                </div>
                <?php $multiigv = (empty($_SESSION['config']['multiigv'])) ? 'N' : $_SESSION['config']['multiigv']; ?>
                <div class="col-sm-3" style="<?php echo ($multiigv == 'S' ? '' : 'display:none') ?>">
                    <?php
                    $igv = new IGVComponent('');
                    echo $igv->render();
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <input type="text" class="form-control form-control-sm" name="txtreferencia" placeholder="Referencia" id="txtreferencia" value="">
                </div>
            </div>
        </div>
    </div>
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
        buscarpedidos();
        $(".tipodocumentos option[value='07']").remove();
        $(".tipodocumentos option[value='08']").remove();
        $(".tipodocumentos option[value='GI']").remove();
    }

    $("#modal_pedidos").on("shown.bs.modal", function() {
        buscarpedidos();
    });

    $('#divfecha').click(function() {
        $("#txtfecha").prop("readonly", false);
    });

    $("#modal_productos").on("shown.bs.modal", function() {
        moverCursorFinalTexto("txtbuscarProducto");
    });

    function grabarCabecera() {}

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
        if (ctdoc === '01' && ruc.trim() === '') {
            toastr.info("Se necesita que el Cliente tenga RUC para hacer una Factura", 'Mensaje del Sistema');
            return false;
        }
        if (ctdoc === '01' && ruc === 0) {
            toastr.info("Se necesita que el Cliente tenga RUC para hacer una Factura", 'Mensaje del Sistema');
            return false;
        }
        return true;
    }

    function grabarVenta() {
        if (!validarVenta()) {
            return;
        }
        var cmensaje = "";
        if (document.querySelector('#txtidautop').value != '0') {
            cmensaje = '¿Registrar Venta?';
            grabar(cmensaje);
        }
    }

    function changedetaildolar() {
        $("#cmbmoneda").attr('disabled', true);
        $('#griddetalle tbody tr').each(function() {
            precio = $(this).find(".precio").html();
            preciocondolar = precio / Number("<?php echo $_SESSION['gene_dola']; ?>");
            $(this).find(".precio").html(Number(preciocondolar).toFixed(2));
        });
    }

    function limpiardatos() {
        $("#idusua").val("0")
        $("#cmbmoneda").attr('disabled', false);
        $("#txtcliente").val("");
        $("#titulo").html("Facturar Cotizaciones");
        $("#txtidcliente").val("0");
        $("#txtruccliente").val("0");
        $("#ndo2").val("");
        $("#cmbforma").val("E");
        // $("#cmbAlmacen").val("1");
        $("#cmbmoneda").val("S");
        // $("#cmbvendedor").val("");
        $("#total").val("0.00");
        $("#txtdias").val();
        $("#igv").val("0.00");
        $("#subtotal").val("0.00");
        $("#totalitems").val("0.00");
        $("#txtreferencia").val("");
        document.getElementById("grabar").innerHTML = "Grabar";
        $("#griddetalle tbody tr").remove();
    }

    function quitaritem(i) {
        row = $(i).parent().parent().html();
        document.getElementById("carritoventas").deleteRow(row);
    }

    // function grabar(cmensaje) {
    //     Swal.fire({
    //         title: cmensaje,
    //         text: "Se grabará en el sistema.",
    //         icon: 'question',
    //         showCancelButton: true,
    //         confirmButtonColor: '#3085d6',
    //         cancelButtonColor: '#d33',
    //         confirmButtonText: 'Si'
    //     }).then(function(respuesta) {
    //         if (respuesta.isConfirmed) {
    //             const detalle = []
    //             $("#griddetalle tbody tr").each(function() {
    //                 json = "";
    //                 $(this).find("td").each(function() {
    //                     $this = $(this);
    //                     key = $this.attr("class");
    //                     key = key.replace(/dtr-control/g, '')
    //                     if (key == 'eliminar') {
    //                         val = '';
    //                     } else {
    //                         val = $this.text();
    //                         val = val.replace(/"/g, '\\"');
    //                     }
    //                     json += ',"' + key.trim() + '":"' + val + '"'
    //                 })
    //                 obj = JSON.parse('{' + json.substr(1) + ',"activo":"A"}');
    //                 detalle.push(obj)
    //             });
    //             data = new FormData();
    //             data.append("idcliev", $("#txtidcliente").val());
    //             data.append("idautop", $("#txtidautop").val());
    //             data.append("idusua", $("#idusua").val());
    //             data.append("usuario", $("#usuario").val());
    //             data.append("razov", $("#txtcliente").val());
    //             data.append("tdocv", $("#cmbdcto").val());
    //             data.append("txtdireccion", $("#txtdireccion").val());
    //             data.append("txtruccliente", $("#txtruccliente").val());
    //             data.append("txtdnicliente", $("#txtdnicliente").val());
    //             data.append("txtclienteretencion", $("#txtclienteretencion").val());
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
    //             data.append("detalle", JSON.stringify(detalle));
    //             data.append("txtreferencia", $("#txtreferencia").val());
    //             data.append("detraccion", $("#detraccion").val());
    //             axios.post("/vtas/registrarpedido", data)
    //                 .then(function(respuesta) {
    //                     toastr.success(respuesta.data.mensaje.trimEnd() + ' ' + respuesta.data.ndoc, 'Mensaje del Sistema');
    //                     $('#griddetalle tbody tr').remove();
    //                     var cruta = '/vtas/imprimirdirecto/';
    //                     var xhr = new XMLHttpRequest();
    //                     xhr.open('GET', cruta, true);
    //                     xhr.responseType = 'blob';
    //                     xhr.onload = function(e) {
    //                         if (this.status == 200) {
    //                             var w = screen.width;
    //                             url = location.protocol + '//' + document.domain + '/descargas/' + respuesta.data.ndoc + ".pdf"
    //                             // console.log(w)
    //                             if (w <= 768) {
    //                                 var req = new XMLHttpRequest();
    //                                 req.open("GET", url, true);
    //                                 req.responseType = "blob";
    //                                 req.onload = function(event) {
    //                                     var blob = req.response;
    //                                     // console.log(blob.size);
    //                                     var link = document.createElement('a');
    //                                     link.href = window.URL.createObjectURL(blob);
    //                                     link.download = respuesta.data.ndoc + ".pdf"
    //                                     link.click();
    //                                 };
    //                                 req.send();
    //                             } else {
    //                                 $("#pdfguia").attr("src", url)
    //                                 $("#abrirguia").click();
    //                             }
    //                         }
    //                     };
    //                     xhr.send();
    //                     limpiardatos();
    //                     <?php $_SESSION['carritov'] = []; ?>
    //                 }).catch(function(error) {
    //                     console.log(error)
    //                     mostrarerroresvalidacion(error);
    //                 });
    //         }
    //     });
    // }


    function grabar(cmensaje) {
        Swal.fire({
            title: '¿Cómo desea generar el comprobante?',
            text: 'Seleccione el tipo de comprobante.',
            icon: 'question',
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            denyButtonColor: '#16a34a',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Por detallado',
            denyButtonText: 'Por consumo',
            cancelButtonText: 'Cancelar'
        }).then(function(respuesta) {
            if (!respuesta.isConfirmed && !respuesta.isDenied) {
                return;
            }
            const porConsumo = respuesta.isDenied;
            const detalle = [];
            $("#griddetalle tbody tr").each(function() {
                let obj = {
                    coda: $(this).find(".coda").text().trim(),
                    descripcion: $(this).find(".descripcion").text().trim(),
                    unidad: $(this).find(".unidad").text().trim(),
                    cantidad: $(this).find(".cantidad").text().trim(),
                    precio: $(this).find(".precio").text().trim(),
                    preciosgv: $(this).find(".preciosgv").text().trim(),
                    total: $(this).find(".total").text().trim(),
                    tipoproducto: $(this).find(".tipoproducto").text().trim(),
                    costo: $(this).find(".costo").text().trim(),
                    prod_detr: $(this).find(".prod_detr").text().trim(),
                    activo: "A"
                };
                detalle.push(obj);
            });

            // Convertir a un solo artículo POR CONSUMO
            if (porConsumo) {
                const total = Number($("#total").val()) || 0;
                const itemConsumo = {
                    coda: "1",
                    descripcion: "POR CONSUMO",
                    unidad: detalle.length > 0 ? detalle[0].unidad : "NIU",
                    cantidad: "1",
                    precio: total.toFixed(2),
                    preciosgv: "",
                    total: total.toFixed(2),
                    tipoproducto: detalle.length > 0 ? detalle[0].tipoproducto : "",
                    costo: detalle.length > 0 ? detalle[0].costo : "0",
                    prod_detr: detalle.length > 0 ? detalle[0].prod_detr : "0",
                    activo: "A"
                };
                detalle.length = 0;
                detalle.push(itemConsumo);
            }
            let data = new FormData();
            data.append("idcliev", $("#txtidcliente").val());
            data.append("idautop", $("#txtidautop").val());
            data.append("idusua", $("#idusua").val());
            data.append("usuario", $("#usuario").val());
            data.append("razov", $("#txtcliente").val());
            data.append("tdocv", $("#cmbdcto").val());
            data.append("txtdireccion", $("#txtdireccion").val());
            data.append("txtruccliente", $("#txtruccliente").val());
            data.append("txtdnicliente", $("#txtdnicliente").val());
            data.append("txtclienteretencion", $("#txtclienteretencion").val());
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
            data.append("detalle", JSON.stringify(detalle));
            data.append("txtreferencia", $("#txtreferencia").val());
            data.append("detraccion", $("#detraccion").val());
            axios.post("/vtas/registrarpedido", data)
                .then(function(respuesta) {
                    toastr.success(respuesta.data.mensaje.trimEnd() + ' ' + respuesta.data.ndoc,'Mensaje del Sistema');
                    $('#griddetalle tbody tr').remove();
                    var cruta = '/vtas/imprimirdirecto/';
                    var xhr = new XMLHttpRequest();
                    xhr.open('GET', cruta, true);
                    xhr.responseType = 'blob';
                    xhr.onload = function(e) {
                        if (this.status == 200) {
                            var w = screen.width;
                            url = location.protocol + '//' + document.domain +'/descargas/' + respuesta.data.ndoc + ".pdf";
                            if (w <= 768) {
                                var req = new XMLHttpRequest();
                                req.open("GET", url, true);
                                req.responseType = "blob";
                                req.onload = function(event) {
                                    var blob = req.response;
                                    var link = document.createElement('a');
                                    link.href = window.URL.createObjectURL(blob);
                                    link.download = respuesta.data.ndoc + ".pdf";
                                    link.click();
                                };
                                req.send();
                            } else {
                                $("#pdfguia").attr("src", url);
                              $("#abrirguia").click();
                            }
                        }
                    };
                    xhr.send();
                    limpiardatos();
                    <?php $_SESSION['carritov'] = []; ?>
                }).catch(function(error) {
                    console.log(error);
                    mostrarerroresvalidacion(error);
                });
        });
    }

    function cancelarVenta() {
        limpiardatos();
        <?php $_SESSION['carritov'] = []; ?>
    }
</script>
<?php
$this->endSection("javascript");
?>