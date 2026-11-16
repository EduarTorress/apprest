<div class="card-body">
    <div class="row">
        <?php foreach ($lista as $item) : ?>
            <div class="col-sm-2">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><b><?php echo 'Mesa N° ' . $item['idmesa']; ?></b></h5>
                        <p class="card-text"><?php echo $item['mesa_refe']; ?></p>
                        <?php $parametro1 = $item['idmesa']; ?>
                        <!-- <?php if ($modo == 'A'): ?> -->
                        <button onclick='modalEdit("<?php echo $parametro1 ?>")' class="btn btn-primary"> <a role="button">Consultar</a></button>
                        <!-- <?php else: ?> -->
                        <!-- <a class="btn btn-success" role="button" href="<?php echo "/pedidos/buscarpedidoxmesa/" . $item['idmesa'] ?>">
                                Consultar Pedido
                            </a>
                        <?php endif; ?> -->
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>