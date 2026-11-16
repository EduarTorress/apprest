<table id="tabla_marcas" class="table table-bordered table-hover table-sm small">
    <thead>
        <tr>
            <th class="text-center">Código</th>
            <th>Nombre de Marca</th>
            <th class="text-end">Productos Enlazados</th>
            <th class="text-center">Seleccionar</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($lista['lista']['items'] as $item) : ?>
            <tr>
                <td><?php echo $item['idmar'] ?></td>
                <td><?php echo $item['dmar'] ?></td>
                <td><?php echo $item['totalproductos'] ?></td>
                <td id="iniciar">
                    <?php
                    $parametro1 = $item['idmar'];
                    $parametro2 = $item['dmar'];
                    $parametro3 = $item['totalproductos'];
                    $parametros = compact('parametro1', 'parametro2', 'parametro3');
                    $cadena_json = json_encode($parametros);
                    ?>
                    <button onclick='modalEdit(<?php echo $parametro1 ?>)' class="btn btn-warning">Editar</button>
                    <button onclick='darBaja(<?php echo $parametro1 ?>)' class="btn btn-danger">Eliminar</button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    $(document).ready(function() {
        reportetablebt('#tabla_marcas')
    });
</script>