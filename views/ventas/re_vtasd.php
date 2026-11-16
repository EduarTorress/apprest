<?php

use App\View\Components\Modaload;

$this->setLayout('layouts/admin');
?>
<?php
$this->startSection('contenido');
?>
<div class="content-wrapper">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary card-outline">
                        <div class="card-body">
                            <form class="form-inline" id="form-search">
                                <?php
                                $empresa = new \App\View\Components\EmpresaComponent("");
                                echo $empresa->render();
                                ?>
                                <div id="fechas">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12" id="search">
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
    document.getElementById('form-search').addEventListener('submit', function(evento) {
        evento.preventDefault();
        search();
    });
    window.onload = function() {

        $('#titulo').html("<?php echo $titulo ?>")
    }
    $(document).ready(function() {
        obtenerFechas();
    });

    function search() {
        const empresasel = empresaseleccionada();
        var dfechai = document.getElementById("txtfechai").value;
        var dfechaf = document.getElementById("txtfechaf").value;
        $("#btnconsultar").attr("disabled", "disabled");
        axios.get('/vtas/listarvtasdetalladas', {
                "params": {
                    "empresa": empresasel,
                    "dfechai": dfechai,
                    "dfechaf": dfechaf

                }
            }).then(function(respuesta) {
                $("#btnconsultar").removeAttr("disabled");
                const contenido_tabla = respuesta.data;
                $('#search').html(contenido_tabla);

            }) // representa cuando todo va bien en peticion
            .catch(function(error) {
                $("#btnconsultar").removeAttr("disabled");

                toastr.error('Error al cargar el listado')
            });
    }
</script>
<?php
$this->endSection('javascript');
?>