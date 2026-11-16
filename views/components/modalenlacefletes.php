<!-- Modal: Seleccionar compras -->
<div class="modal fade" id="modalCompras" tabindex="-1" aria-labelledby="tituloModalCompras" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloModalCompras">
                    <i class="bi bi-cart-check"></i> Seleccionar compras
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <!-- Selector de compras -->
                <div class="form-group row mb-4">
                    <label class="col-sm-3 col-form-label" for="cmbCompras">
                        Compra:
                    </label>
                    <div class="col-sm-9">
                        <select name="cmbCompras" id="cmbCompras"
                            data-width="100%"
                            data-live-search="true"
                            data-size="8"
                            title="Seleccione una compra..."
                            class="selectpicker form-control">
                            <option value="0">Seleccione</option>
                            <?php foreach ($compras as $row): ?>
                                <option
                                    value="<?= (int)$row['idauto'] ?>"
                                    data-documento="<?= htmlspecialchars(
                                                        $row['ndoc'],
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>"
                                    data-proveedor="<?= htmlspecialchars(
                                                        $row['razo'],
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>">
                                    <?= htmlspecialchars(
                                        $row['ndoc'] . ' - ' .
                                            $row['razo'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <!-- Tabla de productos -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle"
                        id="tablaCompras">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">ID</th>
                                <th>Documento</th>
                                <th>Producto</th>
                                <th class="text-end">Cantidad</th>
                                <th class="text-end">Precio</th>
                                <th class="text-end">Flete</th>
                                <th class="text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody id="detalleCompras">
                            <?php if (!empty($detallefletes)): ?>
                                <?php foreach ($detallefletes as $index => $item): ?>
                                    <tr
                                        data-id-compra="<?= (int)$item['idauto'] ?>"
                                        data-idkar="<?= (int)$item['idkar'] ?>">
                                        <td class="text-center numero-fila">
                                            <?= $index + 1 ?>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars(
                                                $item['ndoc'],
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                            <input
                                                type="hidden"
                                                name="compras[]"
                                                value="<?= (int)$item['idauto'] ?>">
                                        </td>
                                        <td>
                                            <?= htmlspecialchars(
                                                $item['producto'],
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </td>
                                        <td class="text-end">
                                            <?= htmlspecialchars(
                                                (string)$item['cantidad'],
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </td>
                                        <td class="text-end">
                                            <?= number_format(
                                                (float)$item['precio'],
                                                2,
                                                '.',
                                                ''
                                            ) ?>
                                        </td>
                                        <td>
                                            <input
                                                type="number"
                                                name="fletes[<?= (int)$item['idauto'] ?>][]"
                                                class="form-control text-end input-flete"
                                                value="<?= number_format(
                                                            (float)$item['gast'],
                                                            2,
                                                            '.',
                                                            ''
                                                        ) ?>"
                                                min="0"
                                                step="0.01"
                                                required>
                                        </td>
                                        <td class="text-center">
                                            <button
                                                type="button"
                                                class="btn btn-danger btn-sm btnEliminarCompra"
                                                title="Eliminar producto">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr id="filaVacia">
                                    <td colspan="7" class="text-center text-muted">
                                        Seleccione una compra para mostrar sus productos.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="5" class="text-end">
                                    Flete acumulado:
                                </th>
                                <th class="text-end">
                                    <input type="number" id="totalFlete" class="form-control text-end" value="0.00" readonly>
                                </th>
                                <th>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="text-end">
                    <strong>Total de productos:</strong>
                    <span id="totalCompras" class="badge bg-primary">0</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnConfirmarCompras">
                    Confirmar selección
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#cmbCompras').selectpicker();
        const comprasAgregadas = new Set();

        // Seleccionar compra y cargar productos
        $('#cmbCompras').on('changed.bs.select', function() {
            const select = $(this);
            const idCompra = String(select.val());
            if (!idCompra || idCompra === '0') {
                return;
            }
            if (comprasAgregadas.has(idCompra)) {
                alert('Esta compra ya fue agregada.');
                select.selectpicker('val', '0');
                return;
            }
            const opcion = select.find('option:selected');
            const numeroCompra = opcion.attr('data-documento');
            select.prop('disabled', true).selectpicker('refresh');
            $.ajax({
                url: '/compras/listardetalleparafletes',
                type: 'GET',
                dataType: 'json',
                data: {
                    idauto: idCompra
                },
                success: function(respuesta) {
                    if (!respuesta.lista || respuesta.lista.length === 0) {
                        alert('La compra no tiene productos.');
                        return;
                    }
                    comprasAgregadas.add(idCompra);
                    $('#filaVacia').remove();
                    respuesta.lista.forEach(function(producto) {

                        const fila = $('<tr>')
                            .attr('data-id-compra', idCompra)
                            .attr('data-idkar', producto.idkar);

                        $('<td>')
                            .addClass('text-center numero-fila')
                            .appendTo(fila);

                        const celdaCompra = $('<td>')
                            .text(numeroCompra);

                        $('<input>', {
                            type: 'hidden',
                            name: 'compras[]',
                            value: idCompra
                        }).appendTo(celdaCompra);

                        celdaCompra.appendTo(fila);

                        $('<td>')
                            .text(producto.producto)
                            .appendTo(fila);

                        $('<td>')
                            .addClass('text-end')
                            .text(producto.cantidad)
                            .appendTo(fila);

                        $('<td>')
                            .addClass('text-end')
                            .text(Number(producto.precio).toFixed(2))
                            .appendTo(fila);

                        const inputFlete = $('<input>', {
                                type: 'number',
                                name: 'fletes[' + idCompra + '][]',
                                class: 'form-control text-end input-flete',
                                value: '0.00',
                                min: '0',
                                step: '0.01',
                                required: true
                            })
                            .on('focus', function() {
                                this.select();
                            })
                            .on('keydown', function(e) {
                                if (e.key === 'Enter') {
                                    e.preventDefault();

                                    const siguienteInput = $(this)
                                        .closest('tr')
                                        .next('tr')
                                        .find('.input-flete');

                                    if (siguienteInput.length) {
                                        siguienteInput.focus().select();
                                    }
                                }
                            });

                        $('<td>').append(inputFlete).appendTo(fila);

                        const boton = $('<button>', {
                            type: 'button',
                            class: 'btn btn-danger btn-sm btnEliminarCompra',
                            title: 'Eliminar compra'
                        }).append(
                            $('<i>').addClass('fas fa-trash-alt')
                        );
                        $('<td>').addClass('text-center').append(boton).appendTo(fila);
                        $('#detalleCompras').append(fila);
                    });
                    actualizarDetalleCompras();
                },
                error: function(xhr) {
                    console.error('Error al consultar detalle:', xhr.responseText);

                    alert(
                        xhr.responseJSON?.message ||
                        'Error al consultar los productos de la compra.'
                    );
                },
                complete: function() {
                    select.prop('disabled', false).selectpicker('refresh');
                    select.selectpicker('val', '0');
                }
            });
        });

        // Sumar automáticamente los fletes
        $(document).on('input change', '.input-flete', function() {
            actualizarTotalFlete();
        });

        function actualizarTotalFlete() {
            let total = 0;
            $('.input-flete').each(function() {
                const valor = parseFloat($(this).val());
                if (Number.isFinite(valor) && valor > 0) {
                    total += valor;
                }
            });
            $('#totalFlete').val(total.toFixed(2));
        }

        // Eliminar una compra y todos sus productos
        $(document).on('click', '.btnEliminarCompra', function() {
            const idCompra = String(
                $(this).closest('tr').attr('data-id-compra')
            );
            $('#detalleCompras tr[data-id-compra]').filter(function() {
                return String($(this).attr('data-id-compra')) === idCompra;
            }).remove();
            comprasAgregadas.delete(idCompra);
            actualizarDetalleCompras();
        });

        // Actualizar numeración y totales
        function actualizarDetalleCompras() {
            const filas = $('#detalleCompras tr[data-id-compra]');
            filas.each(function(index) {
                $(this).find('.numero-fila').text(index + 1);
            });
            $('#totalCompras').text(filas.length);
            if (filas.length === 0) {
                $('#detalleCompras').html(`
                <tr id="filaVacia">
                    <td colspan="7" class="text-center text-muted">
                        Seleccione una compra para mostrar sus productos.
                    </td>
                </tr>
            `);
            }

            actualizarTotalFlete();
        }

        // Confirmar selección
        $('#btnConfirmarCompras').on('click', function() {
            const total = $('#detalleCompras tr[data-id-compra]').length;
            if (total === 0) {
                alert('Debe seleccionar al menos una compra.');
                return;
            }
            let fleteInvalido = false;

            $('.input-flete').each(function() {
                const valor = Number($(this).val());
                if (!Number.isFinite(valor) || valor < 0) {
                    fleteInvalido = true;
                    return false;
                }
            });
            if (fleteInvalido) {
                alert('Verifique que todos los fletes sean válidos.');
                return;
            }
            $('#modalCompras').modal('hide');
        });

        actualizarDetalleCompras();
    });
</script>