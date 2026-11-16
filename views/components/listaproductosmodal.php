<table id="tabla_productos" class="table table-bordered table-hover table-sm small">
    <thead>
        <tr>
            <th style="font-size:10px;" id="headersysven">Producto</th>
            <th style="font-size:10px;" id="headersysven">Und.</th>
            <?php $sucursales = cargarsucursales(); ?>
            <?php foreach ($sucursales as $s) : ?>
                <th style="font-size:10px;" id="headersysven"><?php echo $s['nomb']; ?></th>
            <?php endforeach; ?>
            <!-- <th>Costo sin IGV</th> -->
            <th style="font-size:10px;" id="headersysven">Pre. May</th>
            <th style="font-size:10px;" id="headersysven">Pre. Pub</th>
            <?php if (!empty($_SESSION['config']['precioespecial'])) : ?>
                <th style="font-size:10px;" id="headersysven">Pre. Esp</th>
            <?php endif; ?>
            <?php if (!empty($_SESSION['config']['vercostos'])) : ?>
                <th style="font-size:10px;" id="headersysven">Costo</th>
            <?php endif; ?>
            <th class="text-center" id="headersysven">Elegir</th>
        </tr>
    </thead>
    <tbody>
        <?php $tds = cargarsucursalestbody(); ?>
        <?php foreach ($lista['lista']['items'] as $item) : ?>
            <tr style="font-size: 10px;">
                <td><?php echo $item['descri'] . ' - ' . $item['marca'] ?></td>
                <td><?php echo $item['unid'] ?></td>
                <?php foreach ($tds as $t) : ?>
                    <th class="text-end"><?php echo $item["$t"]; ?></th>
                <?php endforeach; ?>
                <!-- <td><?php echo $item['uno'] ?></td>
                <td><?php echo $item['dos'] ?></td>
                <td><?php echo $item['tre'] ?></td> -->
                <!-- <td><?php echo $item['prec'] ?></td> -->
                <td class="text-end"><?php echo $item['pre1'] ?></td>
                <td class="text-end"><?php echo $item['pre3'] ?></td>
                <?php if (!empty($_SESSION['config']['precioespecial'])) : ?>
                    <td class="text-end"><?php echo $item['pre2'] ?></td>
                <?php endif; ?>
                <?php if (!empty($_SESSION['config']['vercostos'])) : ?>
                    <td class="text-end"><?php echo $item['costo'] ?></td>
                <?php endif; ?>
                <td class="text-center" id="iniciarp" style="font-size:10px;">
                    <?php
                    // $parametro1 = $item['descri'];
                    $parametro1 = str_replace("'", '"', $item['descri'] . ' - ' . $item['marca']);
                    $parametro2 = $item['idart'];
                    $parametro3 = $item['unid'];
                    $multiempresa = (empty($_SESSION['config']['multiempresa']) ? 'N' : $_SESSION['config']['multiempresa']);
                    // if ($multiempresa == 'S') {
                    switch ($_SESSION['idalmacen']) {
                        case '1':
                            $parametro4 = floatval($item['uno']);
                            break;
                        case '2':
                            $parametro4 = floatval($item['dos']);
                            break;
                        case '3':
                            $parametro4 = floatval($item['tre']);
                            break;
                        case '4':
                            $parametro = floatval($item['cua']);
                            break;
                        case '5':
                            $parametro4 = floatval($item['cin']);
                            break;
                        case '6':
                            $parametro4 = floatval($item['sei']);
                            break;
                    }
                    // } else {
                    //     $parametro4 = $item['uno'] + $item['dos'] + $item['tre'] + $item['cua'] + $item['cin'] + $item['sei'];
                    // }
                    if ($item['tipro'] == 'K') {
                        $parametro5 = $item['pre1'];
                    } else {
                        $parametro5 = $item['costo'];
                    }
                    $parametro6 = $item['pre2'];
                    if (empty($_SESSION['config']['precioespecial'])) {
                        $parametro7 = $item['prec'];
                    } else {
                        $parametro7 = $item['pre3'];
                    }
                    $parametro8 = $item['costo'];
                    $parametro9 = $item['peso'];
                    $parametro10 = $item['tipro'];
                    $stockuno = $item['uno'];
                    $stockdos = $item['dos'];
                    $stocktre = $item['tre'];
                    $stockcua = $item['cua'];
                    $stockcin = $item['cin'];
                    $stocksei = $item['sei'];
                    $prod_detr = (empty($item['prod_detr']) ? 0 : $item['prod_detr']);
                    $parametros = compact('parametro1', 'parametro2', 'parametro3', 'parametro4', 'parametro5', 'parametro6', 'parametro7', 'parametro8', 'parametro9', 'parametro10', 'stockuno', 'stockdos', 'stocktre', 'prod_detr', 'stockcua', 'stockcin', 'stocksei');
                    $cadena_json = json_encode($parametros);
                    ?>
                    <button class="btn <?php echo ((intval($parametro4) < 0) ?  'btn-danger' : 'btn-success') ?> btn-sm" data-target="#agregar_cantidad" id="<?php echo 'agregar' . $parametro2 ?>" onclick='agregarunitemVenta(<?php echo $cadena_json ?>)' style="font-size: 5px;"><i href="" style="color:white;" class="fas fa-plus-circle"></i></button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    $(document).ready(function() {
        focustablaproducto('#tabla_productos')
    });
</script>