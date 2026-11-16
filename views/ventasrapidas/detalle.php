<div class="table-responsive">
    <table class="table table-sm small table table-hover" id="griddetalle">
        <thead>
            <tr>
                <th scope="col" style="width:2%" id="headersysven">Opciones</th>
                <th scope="col" style="width:3%" class="codigo" id="headersysven">Código</th>
                <th scope="col" style="width:28%" id="headersysven">Producto</th>
                <th scope="col" style="width:5%" id="headersysven">U.M.</th>
                <th scope="col" style="width:5%" class="text-center" id="headersysven">Cantidad</th>
                <th scope="col" style="width:5%" class="text-center" id="headersysven">Precio</th>
                <th scope="col" style="width:5%" class="text-center preciosgv" id="headersysven">Valor Unit.</th>
                <th scope="col" style="width:5%" class="text-center descuento" id="headersysven">Descuento (%)</th>
                <th scope="col" style="width:5%" id="headersysven">Importe</th>
            </tr>
        </thead>
        <tbody id="carritoventas">
            <?php

            use Core\Foundation\Application;

            $i = 0; ?>
            <?php foreach ($carritov as $indice => $item) : ?>
                <?php if ($item['activo'] == 'A') { ?>
                    <tr onkeyup="verificarValores(this); actualizarProducto(this,<?php echo $indice ?>,event);" onblur="actualizarProducto(this,<?php echo $indice ?>,event);">
                        <?php
                        $parametro1 = $item['descripcion'];
                        $parametro2 = $item['coda'];
                        $parametro3 = $item['unidad'];
                        $parametro4 = $item['stock'];
                        $parametro5 = $item['precio1'];
                        $parametro6 = $item['precio2'];
                        $parametro7 = $item['precio3'];
                        $parametro8 = $item['costo'];
                        $parametro9 = $item['cantidad'];
                        $parametro10 = $item['precio'];
                        $parametro11 = $indice;
                        $parametros = compact('parametro1', 'parametro2', 'parametro3', 'parametro4', 'parametro5', 'parametro6', 'parametro8', 'parametro9', 'parametro10', 'parametro11');
                        $cadena_json = json_encode($parametros);
                        ?>
                        <td>
                            <button class="btn btn-warning" onclick="quitaritem(<?php echo $indice ?>,<?php echo $item['coda']; ?>)"><a style="color:white" class="fas fa-trash-alt"></a></button>
                        </td>
                        <td class="codigo"><?php echo $item['coda'] ?></td>
                        <?php $app = Application::getInstance(); ?>
                        <td <?php echo (($app->empresa == 'bustamante') ? 'contenteditable="true"' : ''); ?>><?php echo $item['descripcion'] ?></td>
                        <td><?php echo $item['unidad'] ?></td>
                        <td class="text-center cantidad" onclick="funcionEnterCant(this,<?php echo $indice ?>)" contenteditable="false" name="cantidad"><input type="text" class="inputright" onkeyup="abrirmodalvtas(event)" onkeypress="return isNumber(event);" value="<?php echo number_format($item['cantidad'], 2, '.', '') ?>"></td>
                        <td class="precio text-center" id="precio" contenteditable="false" name="precio">
                            <input
                                onkeypress="return isNumber(event);"
                                autocomplete="off"
                                onkeydown="manejarTecladoPrecio(event, this)"
                                type="text"
                                onkeyup="abrirmodalvtas(event)"
                                class="inputright"
                                value="<?php echo number_format($item['precio'], 2, '.', '') ?>">
                        </td>
                        <td class="preciosgv text-center"></td>
                        <td class="descuento text-end"><input onkeypress="return isNumber(event);" type="text" onclick="this.select();" class="inputright" onblur="editardescuentoxproducto(<?php echo $item['coda'] . ',' . $indice . ', this' ?>)" value="<?php echo number_format($item['descuento'], 2, '.', '') ?>"></td>
                        <td class="text-center" class="total"><?php echo number_format(round($item['cantidad'] * $item['precio'], 2), 2, '.', '') ?></td>
                        <?php $i++; ?>
                    </tr>
                <?php } ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div><br>
<div class="col-lg-12">
    <div class="card card-success card-outline" style="width:auto;">
        <div class="row">
            <div class="col-6 align-items-start">
                <br>
                <div class="input-group">
                    <!-- "/productos/index/3" para index de productos de ventas  -->
                    <button class="btn btn-primary btn-sm" role="button" data-bs-toggle="modal" data-bs-target="#modal_productos">Agregar</button>
                    <button class="btn btn-danger btn-sm" id="cancelar" role="button" onclick="cancelarVenta()">Limpiar</button>
                    <button class="btn btn-success btn-sm" id="grabar" role="button" onclick="preregistro();"><?php echo (isset($btn) ? $btn : 'Grabar') ?></button>
                    <?php if ($_SESSION['tipousuario'] == 'A') : ?>
                        <button class="btn btn-warning btn-sm" style="display:none" onclick="verutilidad();" hidden>Ver Utilidad</button>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-2 align-items-start">
                <div class="input-group mb-3" style="width: 85%; display:none" id="divutilidad">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-sm" id=""><strong>Ganancia:</strong></span>
                    </div>
                    <input type="text" class="form-control text-right text-sm" id="txtutilidad" aria-label="Small" aria-describedby="inputGroup-sizing-sm" disabled>
                </div>
            </div>
            <div class="col-2 align-items-start">
                <div class="input-group mb-3" style="width: 85%;">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-sm" id=""><strong>Items:</strong></span>
                    </div>
                    <input type="text" class="form-control text-right text-sm" id="totalitems" aria-label="Small" value="<?php echo  $items ?>" aria-describedby="inputGroup-sizing-sm" disabled>
                </div>
            </div>
            <div class="col-2 align-items-start">
                <div class="input-group" style="width: 90%;">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-sm"><strong>SubTotal</strong></span>
                    </div>
                    <input type="text" class="form-control text-right text-sm" id="subtotal" aria-label="Small" value="" aria-describedby="inputGroup-sizing-sm" disabled>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-10"></div>
            <div class="col-2">
                <div class="input-group " style="width: 90%;">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-sm" id=""><strong>IGV&emsp;&emsp;&nbsp;&nbsp;</strong></span>
                    </div>
                    <input type="text" class="form-control text-right text-sm" id="igv" aria-label="Small" value="" aria-describedby="inputGroup-sizing-sm" disabled>
                </div>
            </div>
        </div>
        <?php $ventascondescuento = (empty($_SESSION['config']['ventascondescuento']) ? 'N' : $_SESSION['config']['ventascondescuento']);
        if ($ventascondescuento == 'S') : ?>
            <div class="row">
                <div class="col-8"></div>
                <div class="col-2">
                    <div class="input-group" style="width: 85%; color:red;">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-sm" id=""><strong style="color:red">Descuen.:</strong></span>
                        </div>
                        <input type="text" class="form-control text-right text-sm" id="descuentogeneral" onblur="aplicardescuentogeneral($(this).val());" style="color:red" aria-label="Small" value="0" aria-describedby="inputGroup-sizing-sm" readonly onclick="$(this).removeAttr('readonly')">
                    </div>
                </div>
                <div class="col-2 align-items-start">
                    <div class="input-group mb-3" style="width: 90%;">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-sm" id=""><strong>Total S/D</strong></span>
                        </div>
                        <input type="text" class="form-control text-right text-sm" id="totalsindescuento" aria-label="Small" value="<?php echo 0 ?>" readonly>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div class="row">
            <div class="col-10"></div>
            <div class="col-2 align-items-start">
                <div class="input-group mb-3" style="width: 90%;">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-sm" id=""><strong>TOTAL&emsp;</strong></span>
                    </div>
                    <input type="text" class="form-control text-right text-sm" id="total" aria-label="Small" value="<?php echo $total ?>" disabled>
                    <input type="text" style="display:none" class="form-control text-right text-sm" id="numeroDocumento" aria-label="Small" value="<?php echo isset($numeroDocumento) ?  $numeroDocumento : '' ?>" disabled>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .tooltip-precios {
        z-index: 99999;
        background: white;
        border: 1px solid #ccc;
        border-radius: 6px;
        padding: 8px;
        min-width: 160px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.20);
    }

    .tooltip-precios-titulo {
        font-size: 13px;
        font-weight: bold;
        margin-bottom: 6px;
        color: #333;
    }

    .tooltip-precios .precio-opcion {
        display: block;
        width: 100%;
        box-sizing: border-box;
        padding: 5px 8px;
        margin-bottom: 3px;
        border: 1px solid transparent;
        border-radius: 4px;
        background: white;
        text-align: right;
        cursor: pointer;
    }

    .tooltip-precios .precio-opcion:focus {
        outline: none;
        border-color: #007bff;
        background: #e9f2ff;
    }

    .tooltip-precios .precio-opcion:last-child {
        margin-bottom: 0;
    }
</style>
<script>
    // $('#griddetalle').DataTable({
    //     "paging": true,
    //     "keys": true,
    //     "lengthChange": false,
    //     "searching": true,
    //     "ordering": false,
    //     "info": false,
    //     "autoWidth": false,
    //     "responsive": true,
    //     "columnDefs": [{
    //             "responsivePriority": 1,
    //             "target": 0
    //         },
    //         {
    //             "responsivePriority": 2,
    //             "target": 2
    //         },
    //         {
    //             "responsivePriority": 3,
    //             "target": -3
    //         },
    //         {
    //             "responsivePriority": 4,
    //             "target": -2
    //         }
    //     ]
    // });

    var tooltipPreciosActual = null;
    var inputPrecioActual = null;

    function mostrarPrecios(input) {
        var tr = $(input).closest('tr');
        // Código del producto
        var id = tr.find("td").eq(1).html().trim();
        // Productos de la sesión
        let productos = <?php echo json_encode(empty($_SESSION["carritov"]) ? [] : $_SESSION["carritov"]) ?>;
        // Buscar producto
        var producto = productos.find(function(elemento) {
            return String(elemento.coda) === String(id);
        });
        if (!producto) {
            return;
        }
        var precio1 = Number(producto.precio1 || 0);
        var precio2 = Number(producto.precio2 || 0);
        var precio3 = Number(producto.precio3 || 0);
        // Crear contenido del tooltip
        var contenido = `
        <div style="text-align:left; font-size:13px;">
            <strong>Precios disponibles</strong><br>
            <span>Precio 1: <b>${precio1.toFixed(2)}</b></span><br>
            <span>Precio 2: <b>${precio2.toFixed(2)}</b></span><br>
            <span>Precio 3: <b>${precio3.toFixed(2)}</b></span>
        </div>
        `;
        // Si ya existe un tooltip, eliminarlo
        var tooltipExistente = bootstrap.Tooltip.getInstance(input);
        if (tooltipExistente) {
            tooltipExistente.dispose();
        }
        // Crear nuevo tooltip
        var tooltip = new bootstrap.Tooltip(input, {
            title: contenido,
            html: true,
            placement: 'top',
            trigger: 'manual'
        });
        tooltip.show();
        // Guardarlo en el elemento
        $(input).data('tooltipInstance', tooltip);
    }

    function ocultarPrecios(input) {
        var tooltip = bootstrap.Tooltip.getInstance(input);
        if (tooltip) {
            tooltip.dispose();
        }
    }


    $(document).ready(function() {
        $(".codigo").css("display", "none");
        <?php
        $multiigv = (empty($_SESSION['config']['multiigv'])) ? 'N' : $_SESSION['config']['multiigv'];
        if ($multiigv != 'S') : ?>
            $(".preciosgv").css("display", "none");
        <?php endif; ?>
        ventascondescuento = "<?php echo (empty($_SESSION['config']['ventascondescuento']) ? 'N' : $_SESSION['config']['ventascondescuento']); ?>";
        if (ventascondescuento == 'N') {
            $(".descuento").css("display", "none");
        }
        validarvaloresporgrupo();
        obtenerdescuento();
    });

    function editardescuentoxproducto(idart, posicion, descuento) {
        const data = new FormData();
        data.append("txtcoda", idart);
        data.append("descuento", $(descuento).val());
        data.append("indice", posicion);
        axios.post('/vtas/editardescuentoxproducto', data)
            .then(function(respuesta) {
                tr = $(descuento).parent().parent();
                $(tr).find(".precio input").val(Number(respuesta.data.preciofinal).toFixed(3));
                calcularsubtotal($(tr));
                $("#descuentogeneral").val("0.00");
                obtenerdescuento();
            }).catch(function(error) {
                console.log(error);
                if (trim(error) == 'Network Error') {
                    const datanew = new FormData();
                    datanew.append("txtcoda", idart);
                    datanew.append("descuento", $(descuento).val());
                    datanew.append("indice", posicion);
                    axios.post('/vtas/editardescuentoxproducto', datanew)
                        .then(function(respuesta) {
                            tr = $(descuento).parent().parent();
                            $(tr).find(".precio input").val(respuesta.data.preciofinal);
                            calcularsubtotal($(tr));
                            $("#descuentogeneral").val("0.00");
                            obtenerdescuento();
                        });
                }
            });
    }

    function aplicardescuentogeneral(descuentogeneral) {
        $('#griddetalle tbody tr').each(function() {
            _tr = $(this);
            var descuento = _tr.find("td").eq(7).find("input");
            $(descuento).val(descuentogeneral);
            $(descuento).blur();
        });
    }

    function obtenerdescuento() {
        var preciosindescuento = 0;
        var descuentoold = $('#griddetalle tbody tr').find('td').eq(7).find("input").val();
        var todosmismodescuento = true;
        $('#griddetalle tbody tr').each(function() {
            _tr = $(this);
            var cant = _tr.find("td").eq(4).find("input").val();
            var precio = _tr.find("td").eq(5).find("input").val();
            var descuento = _tr.find("td").eq(7).find("input").val();
            if (descuento != descuentoold) {
                todosmismodescuento = false;
            }
            if (Number(descuento) > 0) {
                preciosindescuento += (Number(precio) / (1 - (Number(descuento) / 100))) * Number(cant);
            } else {
                preciosindescuento += Number(precio) * Number(cant);
            }
            descuentoold = descuento;
        });
        if (todosmismodescuento == true) {
            $("#descuentogeneral").val(descuentoold);
        }
        $("#totalsindescuento").val(preciosindescuento.toFixed(2));
    }

    function actualizarProducto(o, i) {
        if (event && event.target.closest(".descuento")) {
            return;
        }
        // $(o).each(function() {
        var _tr = $(o);
        const data = new FormData();
        var id = _tr.find("td").eq(1).html();
        data.append("txtdescri", _tr.find("td").eq(2).html());
        data.append("txtprecio", _tr.find("td").eq(5).find("input").val());
        data.append("txtcantidad", _tr.find("td").eq(4).find("input").val());
        data.append("cmbmoneda", $("#cmbmoneda").val());
        // console.log(_tr.find("td").eq(4).html())
        data.append("indice", i);
        axios.post('/ventasrapidas/EditarUno', data)
            .then(function(respuesta) {
                calcularIGV();
                //console.log('correctamente editado')
            }).catch(function(error) {
                console.log(error);
            });
        // });
    }

    function funcionEnterCant(o, i) {
        var id1 = document.getElementById("1");
        $(id1).removeAttr('id', '1');
        var id2 = document.getElementById("2");
        $(id2).removeAttr('id', '2');

        cant = $(o).find("input");
        $(cant).attr('id', '1');
        $("#1").select();

        var tr = $(o).parent();
        tr.find("td").eq(5).find("input").attr('id', '2');

        var cant = document.getElementById("1");
        cant.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                tr.find("td").eq(4).removeClass('focus');
                $("#2").select();
                // $('#1').removeAttr('contenteditable');
                // $('#2').focus().select();
            }
        });

        var prec = document.getElementById("2");
        prec.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                // $('#2').removeClass('focus');
                // $('#2').removeAttr('contenteditable');
                tr.find("td").eq(5).removeClass('focus');
                // $("#2").blur();
                // $('#body').trigger('click');
                tr.next('tr').find("td:nth-child(5) input").click();
            }
        });
    }

    // Evento enter con el precio
    $("table tbody tr td:nth-child(6) input").click(function() {
        var id = document.getElementById("2");
        $(id).removeAttr('id', '2')
        tr = $(this).closest('tr');
        $(this).attr('id', '2')
        $(this).select()
        var prec = document.getElementById("2");
        prec.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                tr.find("td").eq(5).removeClass('focus');
                $("#2").blur();
                // $('#body').trigger('click');
                tr.next('tr').find("td:nth-child(5) input").click();
            }
        });
    });

    // Evento enter con precio
    // $("table tbody tr td:nth-child(7)").click(function() {
    //     var id = document.getElementById("3");
    //     $(id).removeAttr('id', '3')
    //     $(this).attr('id', '3')
    //     var prec = document.getElementById("3");
    //     prec.addEventListener("keypress", function(event) {
    //         if (event.key === "Enter") {
    //             event.preventDefault();
    //             $('#3').removeClass('focus');
    //             $('#3').removeAttr('contenteditable');
    //             $('#body').trigger('click');
    //         }
    //     });
    // });

    //Calculamos en el subtotal y total
    function calcularsubtotal(o) {
        columnatotal = ($("#griddetalle").find("thead tr:first th").length) - 1;
        var _tr = $(o);
        var cant = _tr.find("td").eq(4).find("input").val();
        var prec = _tr.find("td").eq(5).find("input").val();
        var subt = parseFloat(cant) * parseFloat(prec);
        var campo = _tr.find("td").eq(columnatotal);
        if (isNaN(subt)) {
            // toastr.info("Dígite un número correcto")
        } else {
            campo.html(subt.toFixed(2));
            var total_col1 = 0;
            $('table tbody').find('tr').each(function(i, el) {
                //Voy incrementando las variables segun la fila ( .eq(0) representa la columna 1 )
                total_col1 += parseFloat($(this).find('td').eq(columnatotal).text());
                calcularIGV();
            });
        }
    }

    //Validar precios
    function verificarValores(o) {
        calcularsubtotal(o);
        $(o).each(function() {
            var _tr = $(o);
            let premiun = <?php echo json_encode(empty($_SESSION["carritov"]) ? [] : $_SESSION["carritov"]) ?>;
            var id = _tr.find("td").eq(1).html();
            var cant = _tr.find("td").eq(4).find("input").val();
            var precio = _tr.find("td").eq(5).find("input").val();

            const resultado = premiun.find(elemento => elemento.coda == id);
            cmbmoneda = $("#cmbmoneda").val();

            <?php if (empty($_SESSION['config']['precioespecial'])) : ?>
                precios = [Number(resultado.precio1), Number(resultado.precio2), Number(resultado.costo)];
                precios.sort(function(a, b) {
                    return a - b
                });
            <?php else: ?>
                precios = [Number(resultado.precio1), Number(resultado.precio2), Number(resultado.precio3), Number(resultado.costo)];
                precios.sort(function(a, b) {
                    return a - b
                });
            <?php endif; ?>
            if (cmbmoneda == 'D') {
                preciomenor = (Number(precios[0])) / Number("<?php echo $_SESSION["gene_dola"] ?>");
            } else {
                preciomenor = Number(precios[0]);
            }
            preciomenor = Number(preciomenor).toFixed(2);
            <?php $precioedit = (empty($_SESSION['gene_precioedit']) ? 'N' : $_SESSION['gene_precioedit']); ?>
            <?php if ($precioedit == 'N'): ?>
                preciomenor = Number(preciomenor).toFixed(2);
                if (Number(precio) < preciomenor) {
                    _tr.find("td").eq(5).css("backgroundColor", "#F67979");
                    $("#grabar").attr("disabled", true);
                    toastr.warning("Precio no permitido", 'Mensaje del Sistema');
                } else {
                    _tr.find("td").eq(5).css("backgroundColor", "");
                    $("#grabar").attr("disabled", false);
                }
            <?php endif; ?>
        });
        validarvaloresporgrupo();
    }

    $("#griddetalle tr:last td:eq(5) .inputright").on("keypress", function(evt) {
        if (evt.key === "Enter") {
            $("#modal_productos").modal('show');
            // $("#txtbuscarProducto").blur();
            // $("#cmdbuscarP").attr("disabled", "disabled");
        }
    });

    function abrirmodalvtas(e) {
        if (e.keyCode == 27) {
            preregistro();
        }
    }

    function validarvaloresporgrupo() {
        $('#griddetalle tbody tr').each(function() {
            _tr = $(this);
            let premiun = <?php echo json_encode(empty($_SESSION["carritov"]) ? [] : $_SESSION["carritov"]) ?>;
            var id = _tr.find("td").eq(1).html();
            var cant = _tr.find("td").eq(4).find("input").val();
            var precio = _tr.find("td").eq(5).find("input").val();

            const resultado = premiun.find(elemento => elemento.coda == id);
            cmbmoneda = $("#cmbmoneda").val();

            <?php if (empty($_SESSION['config']['precioespecial'])) : ?>
                precios = [Number(resultado.precio1), Number(resultado.precio2), Number(resultado.costo)];
                precios.sort(function(a, b) {
                    return a - b
                });
            <?php else: ?>
                precios = [Number(resultado.precio1), Number(resultado.precio2), Number(resultado.precio3), Number(resultado.costo)];
                precios.sort(function(a, b) {
                    return a - b
                });
            <?php endif; ?>
            if (cmbmoneda == 'D') {
                preciomenor = (Number(precios[0])) / Number("<?php echo $_SESSION["gene_dola"] ?>");
            } else {
                preciomenor = Number(precios[0]);
            }
            preciomenor = Number(preciomenor).toFixed(2);
            <?php $precioedit = (empty($_SESSION['gene_precioedit']) ? 'N' : $_SESSION['gene_precioedit']); ?>
            <?php if ($precioedit == 'N'): ?>
                if (Number(precio) < preciomenor) {
                    _tr.find("td").eq(5).css("backgroundColor", "#F67979");
                    $("#grabar").attr("disabled", true);
                } else {
                    _tr.find("td").eq(5).css("backgroundColor", "");
                }
            <?php endif; ?>
        })
    }
</script>
<script>
    var tooltipPreciosActual = null;
    var inputPrecioActual = null;

    function mostrarPrecios(input) {
        // Si ya existe un tooltip, eliminarlo
        cerrarTooltipPrecios();

        var tr = $(input).closest('tr');

        // Código del producto
        var id = tr.find("td").eq(1).html().trim();

        // Productos de la sesión
        var productos = <?php echo json_encode(empty($_SESSION["carritov"]) ? [] : $_SESSION["carritov"]) ?>;

        // Buscar producto
        var producto = productos.find(function(elemento) {
            return String(elemento.coda) === String(id);
        });

        if (!producto) {
            return;
        }

        var precio1 = Number(producto.precio1 || 0);
        var precio2 = Number(producto.precio2 || 0);
        var precio3 = Number(producto.precio3 || 0);

        inputPrecioActual = input;

        // Crear tooltip
        var tooltip = document.createElement("div");
        tooltip.className = "tooltip-precios";

        tooltip.innerHTML = `
            <div class="tooltip-precios-titulo">
                Precios disponibles
            </div>
            <input type="text"
                class="precio-opcion"
                value="${precio1.toFixed(2)}"
                data-precio="${precio1.toFixed(2)}"
                readonly>
            <input type="text"
                class="precio-opcion"
                value="${precio2.toFixed(2)}"
                data-precio="${precio2.toFixed(2)}"
                readonly>
            <input type="text"
                class="precio-opcion"
                value="${precio3.toFixed(2)}"
                data-precio="${precio3.toFixed(2)}"
                readonly>
        `;
        document.body.appendChild(tooltip);
        tooltipPreciosActual = tooltip;
        // Posicionar debajo del input
        var rect = input.getBoundingClientRect();
        tooltip.style.position = "absolute";
        tooltip.style.top = (rect.bottom + window.scrollY + 5) + "px";
        tooltip.style.left = (rect.left + window.scrollX) + "px";
        // Obtener inputs
        var opciones = tooltip.querySelectorAll(".precio-opcion");
        // Primer precio seleccionado
        opciones[0].focus();
        opciones[0].select();
        // Eventos
        opciones.forEach(function(opcion, indice) {
            opcion.addEventListener("keydown", function(event) {
                if (event.key === "ArrowDown") {
                    event.preventDefault();
                    var siguiente = indice + 1;
                    if (siguiente >= opciones.length) {
                        siguiente = opciones.length - 1;
                    }
                    opciones[siguiente].focus();
                    opciones[siguiente].select();
                    return;
                }
                if (event.key === "ArrowUp") {
                    event.preventDefault();
                    var anterior = indice - 1;
                    if (anterior < 0) {
                        anterior = 0;
                    }
                    opciones[anterior].focus();
                    opciones[anterior].select();
                    return;
                }
                if (event.key === "Enter") {
                    event.preventDefault();
                    seleccionarPrecio(opcion);
                    return;
                }
                if (event.key === "Escape") {
                    event.preventDefault();
                    cerrarTooltipPrecios();
                    inputPrecioActual.focus();
                    inputPrecioActual.select();
                    return;
                }
            });
            opcion.addEventListener("click", function() {
                seleccionarPrecio(opcion);
            });
        });
    }

    function seleccionarPrecio(opcion) {
        if (!inputPrecioActual) {
            return;
        }
        // Guardamos el input original
        var input = inputPrecioActual;
        // Precio seleccionado
        var precioSeleccionado = Number(opcion.dataset.precio);
        // Cambiar el valor
        $(input).val(precioSeleccionado.toFixed(3));
        // Obtener fila
        var tr = $(input).closest('tr');
        // Recalcular
        calcularsubtotal(tr);
        // Validar
        verificarValores(tr);
        // Cerrar tooltip
        if (tooltipPreciosActual) {
            tooltipPreciosActual.remove();
            tooltipPreciosActual = null;
        }
        setTimeout(function() {
            input.focus();
            input.select();
        }, 0);
        inputPrecioActual = null;
    }

    function cerrarTooltipPrecios() {
        if (tooltipPreciosActual) {
            tooltipPreciosActual.remove();
            tooltipPreciosActual = null;
        }
        inputPrecioActual = null;
    }

    function manejarTecladoPrecio(event, input) {
        if (event.key === "ArrowDown") {
            event.preventDefault();
            mostrarPrecios(input);
            return;
        }
        if (event.key === "Escape") {
            event.preventDefault();
            cerrarTooltipPrecios();
            $(input).focus();
            $(input).select();
            return;
        }
    }
</script>