<?php

namespace Core\Clases;

// use chillerlan\QRCode\QRCode as QRCodeQRCode;
use Core\Foundation\Application;
use Fpdf\Fpdf;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use tFPDF;

class maquinsu extends Imprimir
{
    public function generapdf($rutapdf, $estilo = '')
    {
        require('tfpdf.php');

        $pdf = new tFPDF();
        $pdf->AddPage('P', 'A4');
        $pdf->AddFont('DejaVu', '', 'DejaVuSansCondensed.ttf', true);

        $pdf->AddFont('Tahoma', '', 'tahoma.php');
        $pdf->AddFont('Tahomab', '', 'tahomab.php');

        $i = 1;

        if ($_SERVER['SERVER_NAME'] == 'app26.test') {
            $logo = 'logos/' . trim($this->rucempresa) . '/logo.jpg';
            $logosdemarca = 'logos/' . trim($this->rucempresa) . '/logomarcas.jpg';
        } else {
            $logo = $_SERVER['DOCUMENT_ROOT'] . '/../logos/' . trim($this->rucempresa) . '/logo.jpg';
            $logosdemarca = $_SERVER['DOCUMENT_ROOT'] . '/../logos/' . trim($this->rucempresa) . '/logomarcas.jpg';
        }
        if (\file_exists($logo)) {
            $pdf->Image($logo, 12, 10, -280);
        }
        $pdf->SetFont('Tahomab', '', 10);
        $pdf->setx(68);
        $pdf->Image($logosdemarca, 61, 9, 80);
        // $pdf->cell(40, 5, (session()->get("gene_empresa")));
        $pdf->SetFont('Tahomab', '', 12);
        $pdf->setx(150);

        $ruc = "RUC   " . session()->get("gene_nruc");
        $pdf->cell(50, 6, $ruc, 'LRT', 1, 'C', 0);
        $pdf->SetFont('Tahoma', '', 6.5);

        $pdf->setx(55);
        $current_x = $pdf->GetX();
        $current_y = $pdf->GetY();
        $cell_width = 106;
        // $pdf->Multicell(90, 3, trim(session()->get("gene_ptop")), '', 'C', false);
        // $pdf->SetXY($current_x + $cell_width, $current_y);

        // $pdf->SetY($current_y);
        $pdf->setx(150);
        $pdf->SetFont('Tahomab', '', 8);
        $pdf->cell(50, 6, trim($this->tipocomprobante), 'LR', 1, 'C', 0);
        $pdf->cell(100);

        $pdf->setx(50);
        $pdf->SetFont('Tahoma', '', 6.5);
        $current_y = $pdf->GetY();
        $current_x = $pdf->GetX();
        $cell_width = 106;
        // $pdf->Multicell(100, 4,  'CORREO: ' . trim(session()->get("gene_correo")), '', 'C', false);
        // $pdf->SetXY($current_x + $cell_width, $current_y);

        $pdf->SetFont('Tahomab', '', 10);
        $pdf->setx(150);
        $pdf->cell(50, 6, $this->numero, 'BLR', 0, 'C', 0);
        $pdf->ln(4);
        $pdf->SetAutoPageBreak('auto', 2);
        $pdf->SetDisplayMode(75);

        $pdf->SetFont('Tahomab', '', 8);
        // $pdf->setx(50);
        $pdf->ln();
        $current_y = $pdf->GetY();
        $current_x = $pdf->GetX();
        $cell_width = 106;
        $pdf->cell(40, 5, (session()->get("gene_empresa")));
        $pdf->ln();
        $pdf->SetFont('Tahoma', '', 7);
        $pdf->cell(40, 5, trim(session()->get("gene_ptop")) . '. CELULAR: ' . trim(session()->get("gene_fono")) . ' CORREO: ' . trim(session()->get("gene_correo")));
        // $pdf->ln(2);
        // $current_x = $pdf->GetX();
        // $current_y = $pdf->GetY();
        // $cell_width = 106;
        // $pdf->Multicell(90, 3, trim(session()->get("gene_ptop")), '', 'C', false);
        // $pdf->SetXY($current_x + $cell_width, $current_y);

        // $pdf->Multicell(100, 4, 'CELULAR: ' . trim(session()->get("gene_fono")), '', 'C', false);
        // $pdf->SetXY($current_x + $cell_width, $current_y);

        // $pdf->Ln();
        $pdf->setx(51);
        $current_y = $pdf->GetY();
        $current_x = $pdf->GetX();
        $cell_width = 106;
        // $pdf->Multicell(100, 4, 'CELULAR: ' . trim(session()->get("gene_fono")), '', 'C', false);
        $pdf->SetXY($current_x + $cell_width, $current_y);

        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetFont('DejaVu', '', 8);
        if ($this->tdoc == '01') {
            $pdf->cell(100, 5, 'RUC: ' . $this->ruccliente);
            $pdf->setx(75);
        } else {
            $pdf->cell(100, 5, 'DNI: ' . $this->dnicliente);
            $pdf->setx(75);
        }
        $pdf->cell(100, 5, 'FECHA: ' . $this->fecha);
        $pdf->setx(150);
        $pdf->cell(50, 5, 'GUIA-REMISION: ' . $this->guiaremision);
        $pdf->Ln();
        $pdf->cell(100, 5, 'CLIENTE: ' . $this->cliente);
        $pdf->setx(150);
        $pdf->cell(50, 5, 'VENDEDOR: ' . $this->vendedor);
        $pdf->Ln();
        $pdf->cell(100, 5, 'DIRECCION: ' . $this->direccioncliente);

        $pdf->ln();
        if (\substr($this->formadepago, 0, 2) == 'CR') {
            $pdf->cell(80, 5, 'FORMA DE PAGO: ' . $this->formadepago . ' a ' . $this->dias . ' Dias');
        } else {
            $pdf->cell(80, 5, 'FORMA DE PAGO: ' . $this->formadepago);
        }
        $pdf->setx(150);
        $pdf->cell(80, 5, 'FECHA VTO: ' . $this->fechavto);
        $pdf->ln();
        $pdf->cell(100, 5, 'REFERENCIA: ' . $this->referencia);
        $pdf->setx(150);
        $pdf->cell(100, 5, 'MONEDA: ' . $this->moneda);
        $pdf->ln();
        $pdf->SetFont('Tahomab', '', 7);

        $pdf->SetFillColor(240, 240, 240);
        $pdf->SetTextColor(0);

        $pdf->cell(26, 6, 'CANTIDAD', 1, 0, 'C', true);
        $pdf->cell(18, 6, 'U.M.', 1, 0, 'C', true);
        $pdf->cell(106, 6, 'DESCRIPCION', 1, 0, 'C', true);
        $pdf->cell(18, 6, 'V.U.', 1, 0, 'C', true);
        $pdf->cell(20, 6, 'SUBTOTAL', 1, 1, 'C', true);
        $pdf->SetFillColor(224, 235, 255);
        $pdf->SetTextColor(0);
        $pdf->SetFont('Tahoma', '', 7);

        $logo = 'logos/' . trim($this->rucempresa) . '/logofondo.jpg';
        if (\file_exists($logo)) {
            $pdf->Image($logo, 32, 100, 150);
        }

        $i = 1;
        foreach ($this->items as $fila) {
            // $pdf->cell(8, 6, $i, 'L', 0, 'C', 0);
            $cant = (floatval($fila['cant']) > 0) ?  number_format($fila['cant'], 2, '.', ',') : '';
            $pdf->cell(26, 6, $cant, 0, 0, 'C', 0);
            if ($fila['subtotal'] > 0) {
                $pdf->cell(18, 6, trim($fila['unid']), 0, 0, 'C', 0);
            } else {
                $pdf->cell(18, 6, '', 0, 0, 'C', 0);
            }
            $current_y = $pdf->GetY();
            $current_x = $pdf->GetX();
            $pdf->SetY($current_y + 1);
            $pdf->SetX($current_x);
            $cell_width = 106;
            $pdf->SetFont('DejaVu', '', 7);
            $pdf->Multicell(106, 4,  $fila['descri'], 0, 'L', false);
            $pdf->SetFont('Tahoma', '', 7);
            $pdf->SetXY($current_x + $cell_width, $current_y);
            // $app = Application::getInstance();
            if ($this->optigv == 'N') {
                $precio = $fila['prec'] / $_SESSION['gene_igv'];
            } else {
                $precio = $fila['prec'];
            }
            $prec = (floatval($precio) > 0) ?  number_format($precio, 2, '.', ',') : '';
            $pdf->cell(18, 6, $prec, 0, 0, 'C', 0);
            if ($fila['subtotal'] > 0) {
                $pdf->cell(20, 6, number_format($fila['subtotal'], 2, '.', ','), 0, 1, 'C', 0);
            } else {
                $pdf->cell(20, 6, '', 0, 1, 'C', 0);
            }
            $i++;
        }
        $tl = 30 - $i;

        // while ($i <= $tl) :
        //     $pdf->cell(26, 6, '', 0, 0, 'C', 0);
        //     $pdf->cell(18, 6, '', 0, 0, 'C', 0);
        //     $pdf->cell(106, 6, '', 0, 0, 'L', 0);
        //     $pdf->cell(18, 6, '', 0, 0, 'C', 0);
        //     $pdf->cell(20, 6, '', 0, 1, 'C', 0);
        //     $i++;
        // endwhile;

        $pdf->cell(188, 0, '', 'B', 1, 'C', 0);
        $pdf->cell(188, 2, '', 0, 1, 'C', 0);
        $pdf->ln();

        $pdf->SetFont('Tahomab', '', 7);
        $pdf->cell(120, 5, "SON: " . $this->importeletras);
        $pdf->ln();

        $dctocliente = $this->tdoc == '03' ? $this->dnicliente : $this->ruccliente;
        $tdctocliente = $this->tdoc == '03' ? '0' : '6';
        //CODIGO QR
        //RUC|TIPO DOC|SERIE|CORRELATIVO|IGV|TOTAL|FECHA EMISION|TIPO DOC CLIENTE|NUMERO DOC CLI|
        $ruta_qr = 'codigoqr' . '.png';
        $texto_qr = $this->rucempresa . '|' . $this->tdoc . '|' . $this->serie . '|' . $this->ndoc . '|' . $this->igv . '|' . $this->total . '|' . $this->fecha . '|' . $tdctocliente . '|' . $dctocliente . '|';
        $qr = QrCode::create($texto_qr);
        $writer = new PngWriter();
        $writer->write($qr)->saveToFile($ruta_qr);
        $pdf->Image($ruta_qr, 10, $pdf->gety(), 20, 20);
        //QRcode::png($texto_qr, $ruta_qr, 'Q', 15, 0);

        // $pdf->Image($ruta_qr, 10, $pdf->gety(), 20, 20);
        // if ($this->detraccion != '0') {
        //     $pdf->SetFont('Tahomab', '', 6);
        //     $pdf->setx(35);
        //     $pdf->cell(105, 6, "OPERACION SUJETA AL SISTEMA DE OBLIGACIONES TRIBUTARIAS CON EL GOBIERNO CENTRAL", 1, 0, 'R', 0);
        // }

        $y = $pdf->GetY();
        // $pdf->SetY($y + 2);
        $pdf->setx(37);

        $pdf->SetFont('Tahoma', '', 5);
        $cuentasbanco = obtenercuentasbanco();
        if (count($cuentasbanco) > 0) {
            $pdf->cell(50, 4, 'CUENTAS BANCO', 1, 0, 'C', true);
            $pdf->cell(50, 4, 'NUMERO', 1, 0, 'C', true);
            $pdf->SetY($y + 4);
            foreach ($cuentasbanco as $bc) {
                $pdf->SetX(37);
                $pdf->cell(50, 3, $bc['mame'], 1, 0, 'C', 0);
                $pdf->cell(50, 3, $bc['number'], 1, 1, 'C', 0);
            }
            $pdf->ln(2);
            $pdf->SetFont('DejaVu', '', 6);
            $pdf->cell(50, 6, 'Representación Impresa de ' . trim($this->tipocomprobante));
            $pdf->SetX(80);
            $pdf->cell(80, 6, 'Este comprobante podrá ser consultado vía web mediante este link https://info.compacompaniasysvennysysven.com/consulta');
        }

        $pdf->SetY($y);
        $pdf->SetFont('Tahomab', '', 7);
        $pdf->setx(144);
        $pdf->cell(25, 6, 'VALOR GRAVADO', 1, 0, 'R', 0);
        $pdf->cell(29, 6, number_format($this->valorgravado, 2, '.', ','), 1, 0, 'R', 0);
        $pdf->ln();
        $pdf->SetFont('Tahomab', '', 7);
        $pdf->setx(144);
        $pdf->cell(25, 6, 'I.G.V. ' . number_format(($this->vigv - 1) * 100, 2, '.', ',') . '%', 1, 0, 'R', 0);
        $pdf->cell(29, 6, number_format($this->igv, 2, '.', ','), 1, 0, 'R', 0);
        $pdf->ln();
        // if ($this->detraccion != '0') {
        //     $pdf->SetFont('DejaVu', '', 7);
        //     $pdf->setx(35);
        //     $pdf->cell(39.5, 6, "IMPORTE A DETRAER:  " . number_format($this->total, 2, '.', ',') . "   ", 1, 0, 'R', 0);
        //     $pdf->setx(74.5);
        //     $pdf->cell(32, 6, "DETRACCIÓN:  " . number_format($this->detraccion, 2, '.', ',') . "    ", 1, 0, 'R', 0);
        //     $pdf->setx(106.5);
        //     $totalPagar = floatval($this->total) - floatval($this->detraccion);
        //     $pdf->cell(33.5, 6, "TOTAL A PAGAR:  " . number_format($totalPagar, 2, '.', ',') . "  ", 1, 0, 'R', 0);
        // }
        $ventasanticipado = (empty($_SESSION['config']['ventasanticipado']) ? 'N' : $_SESSION['config']['ventasanticipado']);
        if ($ventasanticipado == 'S') {
            if ($this->totalanticipo != '0') {
                $pdf->setx(144);
                $pdf->cell(25, 6, 'ANTICIPO ', 1, 0, 'R', 0);
                $pdf->cell(29, 6, number_format($this->totalanticipo, 2, '.', ','), 1, 0, 'R', 0);
                $pdf->ln();
                $pdf->setx(144);
                $pdf->cell(25, 6, 'TOTAL ', 1, 0, 'R', 0);
                $pdf->cell(29, 6, number_format($this->total, 2, '.', ','), 1, 0, 'R', 0);
                $pdf->ln();
                $pdf->ln();
            } else {
                $pdf->setx(144);
                $pdf->cell(25, 6, 'TOTAL ', 1, 0, 'R', 0);
                $pdf->cell(29, 6, number_format($this->total, 2, '.', ','), 1, 0, 'R', 0);
                $pdf->ln();
                $pdf->ln();
            }
        } else {
            $pdf->setx(144);
            $pdf->cell(25, 6, 'TOTAL ', 1, 0, 'R', 0);
            $pdf->cell(29, 6, number_format($this->total, 2, '.', ','), 1, 0, 'R', 0);
            $pdf->ln();
            $pdf->ln();
        }
        if (count($cuentasbanco) < 1) {
            $pdf->SetFont('DejaVu', '', 6);
            $pdf->cell(50, 6, 'Representación Impresa de ' . trim($this->tipocomprobante));
            $pdf->SetX(80);
            $pdf->cell(80, 6, 'Este comprobante podrá ser consultado vía web mediante este link https://info.companiasysven.com/consulta');
        } else {
            $pdf->SetFont('DejaVu', '', 6);
            if ($this->clienteretencion == 'S') {
                $yy = $pdf->GetY();
                if (floatval($_SESSION['gene_montoretencion']) <= floatval($this->total)) {
                    $pdf->ln(2);
                    $pdf->SetX(144);
                    $pdf->cell(15, 4, 'CUOTA', 1, 0, 'C', true);
                    $pdf->cell(15, 4, 'RETENCION', 1, 0, 'C', true);
                    $pdf->cell(17, 4, 'IMPORTE CUOTA', 1, 0, 'C', true);
                    $pdf->cell(17, 4, 'FECHA VENC.', 1, 0, 'C', true);
                    $pdf->ln(2);
                    $pdf->SetY($yy + 4);
                    $pdf->SetX(144);
                    $pdf->cell(15, 3, 'CUOTA 01', 1, 0, 'C', 0);
                    $pdf->cell(15, 3, round(floatval($_SESSION['gene_retencion'] / 100) * floatval($this->total), 2), 1, 0, 'C', 0);
                    $pdf->cell(17, 3, $this->total, 1, 0, 'C', 0);
                    $pdf->cell(17, 3, $this->fechavto, 1, 1, 'C', 0);
                }
            }
        }

        if ($estilo == 'I') {
            // $pdf->Output('I', $rutapdf);
            #GUARDAR EN SERVIDOR
            $pdf->Output($rutapdf, 'F');
        } else {
            // $pdf->Output('D', $rutapdf);
            $pdf->Output($rutapdf, 'D');
        }
    }
    //   var $rucempresa;
    // var $empresa;
    // var $direccionempresa;
    // var $tipocomprobante;
    // var $numero;
    // var $serie;
    // var $ndoc;
    // var $tdoc;
    // var $moneda;
    // var $fecha;
    // var $fechavto;
    // var $dias;
    // var $ruccliente;
    // var $dnicliente;
    // var $guiaremision;
    // var $cliente;
    // var $vendedor;
    // var $direccioncliente;
    // var $formadepago;
    // var $importeletras;
    // var $valorgravado;
    // var $exonerado;
    // var $gratuita;
    // var $dscto;
    // var $igv;
    // var $total;
    // var $vigv;
    // var $rucremitente;
    // var $rucdestinatario;
    // var $ptopartida;
    // var $ptollegada;
    // var $remitente;
    // var $destinatario;
    // var $fechat;
    // var $placa;
    // var $placa1;
    // var $marca;
    // var $conductor;
    // var $constancia;
    // var $brevete;
    // var $totalpeso;
    // var $tipotransporte;
    // var $ructransportista;
    // var $nombretransportista;
    // var $referencia;
    // var $detraccion;
    // var $tref;
    // var $fechareferencia;
    // var $optigv;
    // var $forma;
    // var $plazo;
    // var $validez;
    // var $entrega;
    // var $clienteretencion;
    // var $descuentogeneral;
    // var $fusua;
    // var $totalanticipo;
    // var $items = array();
    // var $urlguiasunat = "https://e-factura.sunat.gob.pe/v1/contribuyente/gre/comprobantes/descargaqr?";
    // var $qrsunat;
}
