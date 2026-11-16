<table class="table table-sm small table table-hover" id="griddetalle">
    <thead>
        <tr>
            <th scope="col" data-sortable="true">Detalle</th>
            <th scope="col" data-sortable="true">N° Documento</th>
            <th scope="col" data-sortable="true" class="text-end" data-footer-formatter="formatTotal">Efectivo</th>
            <th scope="col" data-sortable="true" class="text-end" data-footer-formatter="formatTotal">Crédito</th>
            <th scope="col" data-sortable="true" class="text-end" data-footer-formatter="formatTotal">Depósito</th>
            <th scope="col" data-sortable="true" class="text-end" data-footer-formatter="formatTotal">Tarjeta</th>
            <th scope="col" data-sortable="true" class="text-end" data-footer-formatter="formatTotal">YAPE / PLIN</th>
            <th scope="col" data-sortable="true" class="text-end" data-footer-formatter="formatTotal">Anticipo</th>
            <th scope="col" data-sortable="true" class="text-end" data-footer-formatter="formatTotal">Reparto</th>
            <th scope="col" data-sortable="true" class="text-end" data-footer-formatter="formatTotal">Egresos</th>
            <th scope="col" data-sortable="true" class="text-center">Usuario</th>
            <th scope="col" data-sortable="true" class="text-center">Moneda</th>
            <th scope="col" data-sortable="true" class="text-center">Fecha Hora</th>
        </tr>
    </thead>
    <tbody id="">
        <?php foreach ($lista as $l) : ?>
            <tr>
                <td><b><?php echo $l['deta']; ?></b></td>
                <td><b><?php echo $l['ndoc']; ?></b></td>
                <td><?php echo $l['efectivo']; ?></td>
                <td><?php echo $l['credito']; ?></td>
                <td><?php echo $l['deposito']; ?></td>
                <td><?php echo $l['tarjeta']; ?></td>
                <td><?php echo $l['yape']; ?></td>
                <td><?php echo $l['anticipo']; ?></td>
                <td><?php echo $l['Centrega']; ?></td>
                <td><?php echo $l['egresos']; ?></td>
                <td><b><?php echo $l['usua']; ?></b></td>
                <td><?php echo ($l['mone'] == 'S' ? 'SOLES' : 'DOLARES'); ?></td>
                <td><b><?php echo $l['fechao']; ?></b></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<div class="card ">
    <div class="card-body">
        <div class="mb-3 input-group ">
            <label for="" class="col-sm-1 col-form-label col-form-label-sm">Total Caja: </label>
            <div class="col-sm-1">
                <input readonly type="text" class="form-control form-control-sm text-end" id="txttotalcaja" value="<?php echo number_format($totalc, 2, '.', ''); ?>">
            </div>
            <label for="" class="col-sm-1 col-form-label col-form-label-sm">Total de Ventas: </label>
            <div class="col-sm-1">
                <input type="text" readonly class="form-control form-control-sm text-end" id="txttotalvtas" value="<?php echo ($totalv); ?>">
            </div>
            <label for="" class="col-sm-1 col-form-label col-form-label-sm">Efect. de Ventas: </label>
            <div class="col-sm-1">
                <input type="text" readonly class="form-control form-control-sm text-end" id="txtefectivosolovtas" value="<?php echo ($efectivosolovtas); ?>">
            </div>
            <label for="" class="col-sm-1 col-form-label col-form-label-sm">Créd. de Ventas: </label>
            <div class="col-sm-1">
                <input type="text" readonly class="form-control form-control-sm text-end" id="txtcreditosolovtas" value="<?php echo ($creditosolovtas); ?>">
            </div>
            <label for="" class="col-sm-1 col-form-label col-form-label-sm">Dep. de Ventas: </label>
            <div class="col-sm-1">
                <input type="text" readonly class="form-control form-control-sm text-end" id="txtdepositosolovtas" value="<?php echo ($depositosolovtas); ?>">
            </div>
            <label for="" class="col-sm-1 col-form-label col-form-label-sm">Tarje. de Ventas: </label>
            <div class="col-sm-1">
                <input type="text" readonly class="form-control form-control-sm text-end" id="txttarjetasolovtas" value="<?php echo ($tarjetasolovtas); ?>">
            </div>
            <label for="" class="col-sm-1 col-form-label col-form-label-sm">Anticipos: </label>
            <div class="col-sm-1">
                <input type="text" readonly class="form-control form-control-sm text-end" id="txtanticipos" value="<?php echo ($anticipos); ?>">
            </div>
            <label for="" class="col-sm-1 col-form-label col-form-label-sm">Otros Ingresos: </label>
            <div class="col-sm-1">
                <input type="text" readonly class="form-control form-control-sm text-end" id="txtotrosingresos" value="<?php echo ($otrosingresos); ?>">
            </div>
            <label for="" class="col-sm-1 col-form-label col-form-label-sm">Pagos a Cuenta: </label>
            <div class="col-sm-1">
                <input type="text" readonly class="form-control form-control-sm text-end" id="txtpagosacta" value="<?php echo ($pagosacta); ?>">
            </div>
            <!-- <label for="" class="col-sm-1 col-form-label col-form-label-sm">Total Efectivo: </label>
            <div class="col-sm-1">
                <input type="text" readonly class="form-control form-control-sm text-end" id="" value="<?php echo number_format($efectivo, 2, '.', ''); ?>">
            </div> -->
            <label for="" class="col-sm-1 col-form-label col-form-label-sm">Egresos: </label>
            <div class="col-sm-1">
                <input readonly type="text" class="form-control form-control-sm text-end" id="txtegresoscaja" value="<?php echo number_format($egresos, 2, '.', ''); ?>">
            </div>
            <label for="" class="col-sm-1 col-form-label col-form-label-sm">Ganancia Total: </label>
            <div class="col-sm-1">
                <input readonly type="text" class="form-control form-control-sm text-end" id="txtgananciatotal" value="<?php echo number_format(floatval($totalv) - floatval($egresos), 2, '.', ''); ?>">
            </div>
            <!--  <label for="txtpagosact" class="col-sm-0.5 col-form-label col-form-label-sm">Pagos Act: </label>
             <div class="col-sm-1">
                 <input readonly type="text" class="form-control form-control-sm" id="txtpagosact" value="">
             </div>
             <label for="txtingsincaja" class="col-sm-0.5 col-form-label col-form-label-sm">Ing sin Caj: </label>
             <div class="col-sm-1">
                 <input readonly type="text" class="form-control form-control-sm" id="txtingsincaja" value="">
             </div>
             <label for="txtegresos" class="col-sm-0.5 col-form-label col-form-label-sm">Egresos: </label>
             <div class="col-sm-1">
                 <input readonly type="text" class="form-control form-control-sm" id="txtegresos" value="">
             </div> -->
            <label for="" class="col-sm-1 col-form-label col-form-label-sm">Liquidación: </label>
            <div class="col-sm-1">
                <input readonly type="text" class="form-control form-control-sm text-end" id="txtliquidacion" value="<?php echo number_format(((floatval($efectivosolovtas) + floatval($otrosingresos) + floatval($pagosacta)) - floatval($egresos)), 2, '.', ''); ?>">
            </div>
        </div>
        <div class="mb-3 d-flex justify-content-end align-items-center gap-1">
            <div style="width: 300px;" class="me-2">
                <input type="text"
                    placeholder="Ingrese una glosa (referencia)"
                    id="txtreferencia"
                    class="form-control form-control-sm">
            </div>
            <button class="btn btn-primary btn-sm"
                onclick='generarticketcaja(<?php echo json_encode($lista) ?>)'>
                Imprimir Ticket
            </button>
        </div>
    </div>
</div>
<script>
    $("#txtingresos").val('<?php echo $saldo['ingresoss']; ?>');
    $("#txtegresos").val('<?php echo $saldo['egresoss']; ?>');
    $("#txtsaldoanterior").val('<?php echo $saldo['saldoanterior']; ?>');
    reportetablebt("#griddetalle");
    graficar();

    function graficar() {
        const ctx = document.getElementById('myChart');
        myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                title: 'Gráfico de Liquidación de Caja',
                labels: ['Efectivo', 'Crédito', 'Depósito', 'Tarjeta', 'YAPE', 'PLIN', 'Contra Entrega', 'Egresos'],
                datasets: [{
                    label: 'Monto',
                    data: [<?php echo array_sum(array_column($lista, 'efectivo'))  ?>,
                        <?php echo array_sum(array_column($lista, 'credito'))  ?>,
                        <?php echo array_sum(array_column($lista, 'deposito'))  ?>,
                        <?php echo array_sum(array_column($lista, 'tarjeta'))  ?>,
                        <?php echo array_sum(array_column($lista, 'yape'))  ?>,
                        <?php echo array_sum(array_column($lista, 'plin'))  ?>,
                        <?php echo array_sum(array_column($lista, 'Centrega'))  ?>,
                        <?php echo array_sum(array_column($lista, 'egresos'))  ?>
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Grafico de Liquidación de Caja',
                        padding: {
                            top: 10,
                            bottom: 30
                        }
                    }
                }
            }
        });
    }
</script>