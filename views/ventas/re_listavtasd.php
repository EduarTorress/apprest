<div class="mb-3">
    <button type="button" id="download-excel" class="btn btn-success btn-sm">
        <i class="fas fa-file-excel"></i> Exportar Excel
    </button>
    <button type="button" id="download-pdf" class="btn btn-danger btn-sm">
        <i class="fas fa-file-pdf"></i> Exportar PDF
    </button>
</div>
<div id="table" style="width: 100%; height: 600px;"></div>
</table>
<script>
    var tableData = <?php echo json_encode($listado['lista']['items'], JSON_UNESCAPED_UNICODE); ?>;
    var table = new Tabulator("#table", {
        data: tableData,
        layout: "fitColumns",
        height: "500px", // Altura obligatoria para que el DOM virtual pinte las filas
        pagination: "local",
        paginationSize: 25,
        columns: [{
                title: "Fecha",
                field: "fech"
            },
            {
                title: "Documento",
                field: "ndoc"
            },
            {
                title: "Cliente",
                field: "cliente"
            },
            {
                title: "Producto",
                field: "producto"
            },
            {
                title: "Código",
                field: "codigofabrica"
            },
            {
                title: "Unid.",
                field: "unid",
                width: 60
            },
            {
                title: "Cantidad",
                field: "cant",
                hozAlign: "right",
                decimal: ".",
                precision: 2
            },
            {
                title: "Precio",
                field: "prec",
                hozAlign: "right",
                formatter: "money",
                formatterParams: {
                    thousand: ",",
                    decimal: ".",
                    precision: 2
                }
            },
            {
                title: "Mone.",
                field: "mone",
                hozAlign: "center",
                width: "3%"
            },
            {
                title: "Usuario",
                field: "usuario"
            },
            {
                title: "Hora",
                field: "hora",
                hozAlign: "center"
            }
        ],
    });

    // Funciones para los botones de exportación (equivalentes a DataTables Buttons: 'excel', 'pdf')
    // Puedes enlazar estos triggers a botones HTML personalizados en tu vista.
    // Evento para el botón de Excel
    document.getElementById("download-excel").addEventListener("click", function() {
        table.download("xlsx", "ventas_detalladas.xlsx", {
            sheetName: "Ventas"
        });
    });

    // Evento para el botón de PDF
    document.getElementById("download-pdf").addEventListener("click", function() {
        table.download("pdf", "ventas_detalladas.pdf", {
            orientation: "landscape", // Formato horizontal ideal para tablas con muchas columnas
            title: "Reporte de Ventas Detalladas", // Título que aparecerá en el PDF
        });
    });
    // function exportExcel() {
    //     table.download("xlsx", "reporte.xlsx", {
    //         sheetName: "Reporte"
    //     });
    // }

    // function exportPDF() {
    //     table.download("pdf", "reporte.pdf", {
    //         orientation: "landscape",
    //         title: "Reporte de Ventas",
    //     });
    // }
</script>