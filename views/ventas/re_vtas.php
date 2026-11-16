<?php

use App\View\Components\Modaload;

$this->setLayout('layouts/admin');
?>
<?php
$this->startSection('contenido');
?>
<?php
$omodal = new Modaload();
echo $omodal->render();
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
        titulo("<?php echo $titulo ?>");
    }

    $(document).ready(function() {
        obtenerFechas();
    });

    function search() {
        const empresasel = empresaseleccionada();
        var dfechai = document.getElementById("txtfechai").value;
        var dfechaf = document.getElementById("txtfechaf").value;
        $('#loading').modal('show');
        axios.get('/vtas/listavtasr', {
            "params": {
                "empresa": empresasel,
                "dfechai": dfechai,
                "dfechaf": dfechaf
            }
        }).then(function(respuesta) {
            // 100, 200, 300
            const contenido_tabla = respuesta.data;
            $('#loading').modal('hide');
            $('#search').html(contenido_tabla);
            // console.log(respuesta.data.message)
        }).catch(function(error) {
            // 400, 500
            $('#loading').modal('hide');
            toastr.error('Error al cargar el listado', 'Mensaje del Sistema');
        });
    }

    function descargarxml(nidauto, nombrexml) {
        axios.get('/cpe/descargarxml', {
            "params": {
                "empresa": empresasel,
                "nidauto": nidauto
            }
        }).then(function(respuesta) {
            var fileURL = window.URL.createObjectURL(new Blob([respuesta.data]));
            var fileLink = document.createElement('a');
            fileLink.href = fileURL;
            fileLink.setAttribute('download', nombrexml);
            document.body.appendChild(fileLink);
            fileLink.click();
        }).catch(function(error) {
            console.log(error);
            toastr.error("Error al Descargar XML", 'Mensaje del Sistema');
        });
    }

    function descargarpdf10(nidauto, tipo, nombrepdf) {
        var params = "nidauto=" + nidauto + '&tipo=' + tipo + '&nombrepdf=' + nombrepdf;
        var xhr = new XMLHttpRequest();
        var cruta = '/cpe/descargarpdf';
        xhr.open('GET', cruta + "?" + params, true);
        xhr.responseType = 'blob';
        xhr.onload = function(e) {
            if (this.status == 200) {
                var blob = new Blob([this.response]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = nombrepdf;
                link.click();
            }
        };
        xhr.send();
    }

    function descargarpdf(nidauto, tipo, nombrepdf) {
        axios.get('/cpe/descargarpdf', {
            "params": {
                "empresa": empresasel,
                "nidauto": nidauto,
                "tipo": tipo
            },
            contentType: 'application/pdf',
        }).then(function(respuesta) {
            var fileURL = window.URL.createObjectURL(new Blob([respuesta.data], {
                type: 'application/octet-stream'
            }));
            var fileLink = document.createElement('a');
            fileLink.href = fileURL;
            fileLink.setAttribute('download', nombrepdf);
            document.body.appendChild(fileLink);
            fileLink.click();
        }).catch(function(error) {
            toastr.error("Error al Descargar PDF", 'Mensaje del Sistema');
            console.log(error);
        });
    }
</script>
<?php
$this->endSection('javascript');
?>