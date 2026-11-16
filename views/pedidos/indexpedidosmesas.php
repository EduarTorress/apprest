<?php


$this->setLayout('layouts/admin');
?>
<?php
$this->startSection('contenido');
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-12">
                    <h4>
                        <i class="fas fa-utensils"></i>
                        Atención de mesas
                    </h4>
                    <small class="text-muted">
                        Selecciona una mesa para registrar o consultar un pedido.
                    </small>
                </div>
            </div>
            <!-- Leyenda -->
            <div class="d-flex gap-3 mb-3">
                <span>
                    <i class="fas fa-circle text-success"></i>
                    Disponible
                </span>
                <span>
                    <i class="fas fa-circle text-danger"></i>
                    Ocupada
                </span>
            </div>
            <!-- Mesas -->
            <div class="row" id="contenedor-mesas">
                <?php if (!empty($mesas)): ?>
                    <?php foreach ($mesas as $mesa): ?>
                        <?php
                        $idmesa = (int)$mesa['idmesa'];
                        $nombre = htmlspecialchars(
                            $mesa['mesa_refe'],
                            ENT_QUOTES,
                            'UTF-8'
                        );
                        $estado = $mesa['estado'];
                        $ocupada = ($estado == 'O');
                        $clase = $ocupada ? 'btn-outline-danger' : 'btn-outline-success';
                        $color = $ocupada ? 'text-danger' : 'text-success';
                        $texto = $ocupada ? 'Ocupada' : 'Disponible';
                        $idpedido = (int)($mesa['idautop'] ?? 0);
                        ?>
                        <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-3">
                            <button type="button"
                                class="btn <?php echo $clase; ?> w-100 p-3 mesa-card"
                                data-idmesa="<?php echo $idmesa; ?>"
                                data-nombre="<?php echo $nombre; ?>"
                                data-estado="<?php echo $estado; ?>"
                                data-idpedido="<?php echo $idpedido; ?>">
                                <i class="fas fa-chair fa-2x mb-2 <?php echo $color; ?>"></i>
                                <h4 class="mb-1">
                                    <?php echo 'MESA #' . $idmesa; ?>
                                </h4>
                                <h5>
                                    <?php echo $nombre; ?>
                                </h5>
                                <small>
                                    <?php echo $texto; ?>
                                </small>
                            </button>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            No hay mesas registradas.
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<!-- MODAL PARA REGISTRAR O CONSULTAR PEDIDOS -->
<div class="modal fade" id="modalPedido" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-fullscreen-lg-down">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-utensils"></i>
                    <span id="tituloModalPedido">Atención de mesa</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"> </button>
            </div>
            <div class="modal-body p-0">
                <iframe
                    id="iframePedido"
                    src="about:blank"
                    style="width:100%; height:78vh; border:0;"
                    title="Registro de pedido">
                </iframe>
            </div>
        </div>
    </div>
</div>
<style>
    .mesa-card:hover i {
        color: #fff !important;
    }
</style>
<?php
$this->endSection('contenido');
?>
<?php
$this->startSection('javascript');
?>
<script>
    titulo("<?php echo $titulo ?>");

    // const onFocus = () => {
    //      window.location.reload();
    // }
    // window.addEventListener("focus", onFocus)

    $(document).on('click', '.mesa-card', function() {
        const idmesa = $(this).data('idmesa');
        const nombre = $(this).data('nombre');
        const estado = $(this).data('estado');
        const idpedido = $(this).data('idpedido');
        let url = '';
        // Si la mesa está disponible
        if (estado === 'D') {
            url = '/pedidos/indexespecial?idmesa=' + idmesa;
            $('#tituloModalPedido').text('Nuevo pedido - ' + nombre);
        }
        // Si la mesa está ocupada
        else {
            url = '/pedidos/buscarpedido/' + idpedido;
            $('#tituloModalPedido').text('Pedido en atención - ' + nombre);
        }
        // Cargar la ruta dentro del iframe
        $('#iframePedido').attr('src', url);
        // Abrir modal
        const modal = bootstrap.Modal.getOrCreateInstance(
            document.getElementById('modalPedido')
        );
        modal.show();
    });

    // Cuando se cierre la modal
    $('#modalPedido').on('hidden.bs.modal', function() {
        // Limpiar iframe
        $('#iframePedido').attr('src', 'about:blank');
        // Actualizar listado de mesas
        window.location.reload();
    });

    window.addEventListener('message', function(event) {
        // Validar que el mensaje venga del mismo dominio
        if (event.origin !== window.location.origin) return;
        if (event.data.accion === 'guardar_correcto' || event.data.accion === 'cerrar_iframe') {
            const modalElement = document.getElementById('modalPedido');
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) {
                modal.hide();
            }
        }
    });
</script>
<?php
$this->endSection('javascript');
?>