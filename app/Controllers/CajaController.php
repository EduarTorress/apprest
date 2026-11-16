<?php

namespace App\Controllers;

use App\Models\Caja;
use Core\Clases\Imprimir;
use Core\Http\Request;
use Core\Routing\Controller;

class CajaController extends Controller
{
    function index()
    {
        return \view('liquidaciones/index', ['titulo' => 'Liquidar Caja']);
    }
    function buscar(Request $request)
    {
        $fech = $request->get('txtfech');
        $fechaAnterior = date('Y-m-d', strtotime($fech . ' -1 day'));
        $nidusua = $request->get('cmbusuarios');
        $codt = $request->get('cmbAlmacen');
        $caja = new Caja();
        if (!empty($_SESSION['config']['cajaxtienda'])) {
            $data = $caja->buscarmulti($fech, $nidusua, $codt);
            $saldo = $caja->versaldoxtienda($fechaAnterior, $fech, $codt);
        } else {
            $data = $caja->buscar($fech, $nidusua, $codt);
            $saldo = $caja->verSaldo($fechaAnterior, $fech, $nidusua);
        }
        $lista = $data['lista'];
        $totalv = 0;
        $totalc = 0;
        $egresos = 0;
        $efectivo = 0;
        $efectivosolovtas = 0;
        $creditosolovtas = 0;
        $depositosolovtas = 0;
        $tarjetasolovtas = 0;
        $anticipos = 0;
        $pagosacta = 0;
        $otrosingresos = 0;
        foreach ($lista as $l) {
            if (floatval($l['egresos']) >= 0) {
                $egresos = $egresos + floatval($l['egresos']);
            }
            if ((floatval($l['egresos']) <= 0) && ($l['tipo'] == 'I')) {
                if ($l['idauto'] != '0') {
                    $totalv = $totalv + floatval($l['nimpo']);
                }
                $totalc = $totalc + floatval($l['nimpo']);
                if ($l['forma'] == 'E') {
                    $efectivo = $efectivo + floatval($l['nimpo']);
                    if (intval($l['idauto']) > 0) {
                        $efectivosolovtas = $efectivosolovtas + floatval($l['nimpo']);
                    }
                }
                if ($l['forma'] == 'C') {
                    if (intval($l['idauto']) > 0) {
                        $creditosolovtas = $creditosolovtas + floatval($l['nimpo']);
                    }
                }
                if ($l['forma'] == 'D') {
                    if (intval($l['idauto']) > 0) {
                        $depositosolovtas = $depositosolovtas + floatval($l['nimpo']);
                    }
                }
                if ($l['forma'] == 'T') {
                    if (intval($l['idauto']) > 0) {
                        $tarjetasolovtas = $tarjetasolovtas + floatval($l['nimpo']);
                    }
                }
                if ($l['forma'] == 'A') {
                    // if (intval($l['idauto']) > 0) {
                    $anticipos = $anticipos + floatval($l['nimpo']);
                    // }
                }
                if ($l['idauto'] == '0') {
                    $otrosingresos = $otrosingresos + floatval($l['nimpo']);
                }
            }
            if (intval($l['idcredito']) > 0) {
                $pagosacta = $pagosacta + floatval($l['pagosacta']);
            }
        }
        $totalv = number_format($totalv, 2, ".", "");

        return \view('liquidaciones/listamovimientos', [
            'lista' => $lista,
            'saldo' => $saldo['lista'][0],
            'otrosingresos' => $otrosingresos,
            'totalc' => $totalc,
            'totalv' => $totalv,
            'efectivo' => $efectivo,
            'efectivosolovtas' => $efectivosolovtas,
            'creditosolovtas' => $creditosolovtas,
            'depositosolovtas' => $depositosolovtas,
            'tarjetasolovtas' => $tarjetasolovtas,
            'egresos' => $egresos,
            'anticipos' => $anticipos,
            'pagosacta' => $pagosacta
        ]);
    }
    public function generarticketcaja(Request $request)
    {
        $fecha = $request->get('fecha');
        $time = strtotime($fecha);
        $datos = [
            'fecha' => $time ? date('Y-m-d', $time) : date('Y-m-d'),
            'usuario' => $request->get('usuario'),
            'totalcaja' => (float) $request->get('totalcaja'),
            'totalv' => (float) $request->get('totalv'),
            'efectivosolovtas' => (float) $request->get('efectivosolovtas'),
            'creditosolovtas' => (float) $request->get('creditosolovtas'),
            'depositosolovtas' => (float) $request->get('depositosolovtas'),
            'tarjetasolovtas' => (float) $request->get('tarjetasolovtas'),
            'yape' => (float) $request->get('yape'),
            'anticipos' => (float) $request->get('anticipos'),
            'otrosingresos' => (float) $request->get('otrosingresos'),
            'pagosacta' => (float) $request->get('pagosacta'),
            'egresos' => (float) $request->get('egresos'),
            'gananciatotal' => (float) $request->get('gananciatotal'),
            'liquidacion' => (float) $request->get('liquidacion'),
            'referencia' => $request->get('txtreferencia'),
            'cmbalmacen' => $request->get('cmbalmacen')
        ];

        $oimp = new Imprimir();
        $rutapdf = 'ticketcaja.pdf';
        $oimp->generarticketcaja($rutapdf, $datos, 'I');
        echo json_encode([
            'ndoc' => 'ticketcaja'
        ]);
    }
    function indexIngresosEgresos()
    {
        return \view('liquidaciones/indexIngresosEgresos', ['titulo' => 'Registrar Ingresos - Egresos']);
    }
    function obtenernumerodocumento(Request $request)
    {
        $correlativo = SerieController::correlativo($_SESSION['nserie'], 'RC');
        if ($correlativo[0]['estado'] == 0) {
            $documento = '0';
            $idserie = 0;
        } else {
            $documento = $correlativo[0]['correlativo'];
            $idserie =  $correlativo[0]['idserie'];
        }
        return response()->json(['message' => $idserie, 'data' => $documento], 200);
    }
    function registrarIngresoEgreso(Request $request)
    {
        $caja = new Caja();
        $caja->dfecha = $request->get('dfecha');
        $caja->cndoc = $request->get('cndoc');
        $caja->cdeta = $request->get('cdeta');
        $caja->sdeudor = $request->get('sdeudor');
        $caja->sacreedor = $request->get('sacreedor');
        $caja->cmone = 'S';
        $caja->ndolar = session()->get("gene_dola");
        $caja->nidus = session()->get("usuario_id");
        $caja->cmbformapago = $request->get('cmbformapago');
        $caja->nidt = $_SESSION['almacen'];
        $rpta = $caja->registrarIngresoEgreso($request->get('idserie'));
        if ($rpta['estado'] == '1') {
            return response()->json(['message' => $rpta['mensaje'], 'data' => $rpta['data']], 200);
        } else {
            return response()->json(['message' => $rpta['mensaje'], 'data' => $rpta['data']], 422);
        }
    }
    function registrarTransferencia(Request $request) {}
    // function indexregistrocajaybancos()
    // {
    //     $cuentasbanco = new NumerosCuenta();
    //     $listacuentasbanco = $cuentasbanco->listar('%%');
    //     $planescontables = new PlanesContables();
    //     $listarplanescontables = $planescontables->listar('10');
    //     $caja = new Caja();
    //     $rptampagos = $caja->listarmpagos();
    //     return \view('cajaybancos/index', [
    //         'titulo' => 'Registrar Datos a Libro Caja y Bancos',
    //         'listacuentasbanco' => $listacuentasbanco,
    //         'listarplanescontables' => $listarplanescontables,
    //         'listampagos' => $rptampagos['lista']
    //     ]);
    // }
    // function listaringresos()
    // {
    //     $caja = new Caja();
    //     $data = $caja->listaringresos();
    //     return \view('components/listaingresosxcancelar', [
    //         'ingresosxcancelar' => $data['lista']
    //     ]);
    // }
    // function listaregresos()
    // {
    //     $caja = new Caja();
    //     $data = $caja->listaregresos();
    //     return \view('components/listaegresosxcancelar', [
    //         'egresosxcancelar' => $data['lista']
    //     ]);
    // }
    // function registraringresolibro(Request $request)
    // {
    //     // data.append("cmbnrocuentas", $("#cmbnrocuentas").val());
    //     // data.append("cmbctas", $("#cmbctas").val());
    //     // data.append("cmbintereses", $("#cmbintereses").val());
    //     // data.append("txtintereses", $("#txtintereses").val());
    //     // data.append("cmbcomisiones", $("#cmbcomisiones").val());
    //     // data.append("txtcomisiones", $("#txtcomisiones").val());
    //     // data.append("txtnrooperacion", $("#txtnrooperacion").val());
    //     // data.append("txttipocambio", $("#txttipocambio").val());
    //     // data.append("txtinteres", $("#txtinteres").val());
    //     // data.append("txtcomision", $("#txtcomision").val());
    //     // data.append("txttotal", $("#txttotal").val());
    //     // data.append("cuentasxpagar", JSON.stringify(detalle));
    //     $cabecera = [
    //         'cmbmediopago' => $request->get('cmbmediopago'),
    //         'txtfechai' => $request->get('txtfechai'),
    //         'cmbnrocuentas' => $request->get('cmbnrocuentas'),
    //         'cmbctas' => $request->get('cmbctas'),
    //         'cmbintereses' => $request->get('cmbintereses'),
    //         'txtintereses' => $request->get('txtintereses'),
    //         'cmbcomisiones' => $request->get('cmbcomisiones'),
    //         'txtcomisiones' => $request->get('txtcomisiones'),
    //         'txtnrooperacion' => $request->get('txtnrooperacion'),
    //         'txttipocambio' => $request->get('txttipocambio'),
    //         'txtinteres' => $request->get('txtinteres'),
    //         'txtcomision' => $request->get('txtcomision'),
    //         'txttotal' => $request->get('txttotal'),
    //         'txtreferencia' => $request->get('txtreferencia')
    //     ];
    //     $detalle = json_decode($request->get("cuentasxpagar"));
    //     $detalle = json_decode(json_encode($detalle), true);
    //     $caja = new Caja();
    //     $rptacaja = $caja->grabaringresolibro($cabecera, $detalle);
    //     if ($rptacaja['estado'] == '1') {
    //         return response()->json(['message' => 'Se registro correctamente ' . $rptacaja['ndoc'], 'ndoc' => $rptacaja['ndoc']], 200);
    //     } else {
    //         return response()->json(['message' => $rptacaja['mensaje'], 'ndoc' => ''], 422);
    //     }
    // }
    // function registraregresolibro(Request $request)
    // {
    //     // data.append("cmbnrocuentas", $("#cmbnrocuentas").val());
    //     // data.append("cmbctas", $("#cmbctas").val());
    //     // data.append("cmbintereses", $("#cmbintereses").val());
    //     // data.append("txtintereses", $("#txtintereses").val());
    //     // data.append("cmbcomisiones", $("#cmbcomisiones").val());
    //     // data.append("txtcomisiones", $("#txtcomisiones").val());
    //     // data.append("txtnrooperacion", $("#txtnrooperacion").val());
    //     // data.append("txttipocambio", $("#txttipocambio").val());
    //     // data.append("txtinteres", $("#txtinteres").val());
    //     // data.append("txtcomision", $("#txtcomision").val());
    //     // data.append("txttotal", $("#txttotal").val());
    //     // data.append("cuentasxpagar", JSON.stringify(detalle));
    //     $cabecera = [
    //         'cmbmediopago' => $request->get('cmbmediopago'),
    //         'txtfechai' => $request->get('txtfechai'),
    //         'cmbnrocuentas' => $request->get('cmbnrocuentas'),
    //         'cmbctas' => $request->get('cmbctas'),
    //         'cmbintereses' => $request->get('cmbintereses'),
    //         'txtintereses' => $request->get('txtintereses'),
    //         'cmbcomisiones' => $request->get('cmbcomisiones'),
    //         'txtcomisiones' => $request->get('txtcomisiones'),
    //         'txtnrooperacion' => $request->get('txtnrooperacion'),
    //         'txttipocambio' => $request->get('txttipocambio'),
    //         'txtinteres' => $request->get('txtinteres'),
    //         'txtcomision' => $request->get('txtcomision'),
    //         'txttotal' => $request->get('txttotal'),
    //         'txtreferencia' => $request->get('txtreferencia')
    //     ];
    //     $detalle = json_decode($request->get("cuentasxpagar"));
    //     $detalle = json_decode(json_encode($detalle), true);
    //     $caja = new Caja();
    //     $rptacaja = $caja->grabaregresolibro($cabecera, $detalle);
    //     if ($rptacaja['estado'] == '1') {
    //         return response()->json(['message' => 'Se registro correctamente ' . $rptacaja['ndoc'], 'ndoc' => $rptacaja['ndoc']], 200);
    //     } else {
    //         return response()->json(['message' => $rptacaja['mensaje'], 'ndoc' => ''], 422);
    //     }
    // }
    // function indexregistrocajayefectivo()
    // {
    //     return \view('cajayefectivo/index', [
    //         'titulo' => 'Registrar Datos a Libro Caja Efectivo'
    //     ]);
    // }
    // function registraringresolibroefectivo(Request $request)
    // {
    //     $cabecera = [
    //         'txtfechai' => $request->get('txtfechai'),
    //         'cmbcuentas' => $request->get('cmbcuentas'),
    //         'txtcuentas' => $request->get('txtcuentas'),
    //         'cmbmoneda' => $request->get('cmbmoneda'),
    //         'txtvalor' => $request->get('txtvalor'),
    //         'txttotal' => $request->get('txttotal'),
    //         'txtreferencia' => $request->get('txtreferencia'),
    //         'txttipocambio' => $request->get('txttipocambio')
    //     ];
    //     $caja = new Caja();
    //     $rptacaja = $caja->registraringresolibroefectivo($cabecera);
    //     if ($rptacaja['estado'] == '1') {
    //         return response()->json(['message' => 'Se registro correctamente ' . $rptacaja['ndoc'], 'ndoc' => $rptacaja['ndoc']], 200);
    //     } else {
    //         return response()->json(['message' => $rptacaja['mensaje'], 'ndoc' => ''], 422);
    //     }
    // }
    // function registraregresolibroefectivo(Request $request)
    // {
    //     $cabecera = [
    //         'txtfechai' => $request->get('txtfechai'),
    //         'cmbcuentas' => $request->get('cmbcuentas'),
    //         'txtcuentas' => $request->get('txtcuentas'),
    //         'cmbmoneda' => $request->get('cmbmoneda'),
    //         'txtvalor' => $request->get('txtvalor'),
    //         'txttotal' => $request->get('txttotal'),
    //         'txtreferencia' => $request->get('txtreferencia'),
    //         'txttipocambio' => $request->get('txttipocambio')
    //     ];
    //     $caja = new Caja();
    //     $rptacaja = $caja->registraregresolibroefectivo($cabecera);
    //     if ($rptacaja['estado'] == '1') {
    //         return response()->json(['message' => 'Se registro correctamente ' . $rptacaja['ndoc'], 'ndoc' => $rptacaja['ndoc']], 200);
    //     } else {
    //         return response()->json(['message' => $rptacaja['mensaje'], 'ndoc' => ''], 422);
    //     }
    // }
    // function cambiarfecha(Request $request)
    // {
    //     $usua = $request->get("txtUsuario");
    //     $pass = $request->get("txtPassword");
    //     $ousuario = new Usuario();
    //     $valor = $ousuario->verificarusuarioadministador(trim($usua), $pass);
    //     if (!empty($valor[0]['idusua'])) {
    //         return response()->json(['message' => 'ok', 'estado' => '1'], 200);
    //     } else {
    //         return response()->json(['message' => 'Las credenciales no son correctas', 'estado' => '0'], 422);
    //     }
    // }
    // function indexlistarcajabanco()
    // {
    //     $nc = new NumerosCuenta();
    //     $listabancos = $nc->listar('%%');
    //     return \view('cajaybancos/informes/indexlistar', [
    //         'titulo' => 'Reporte de Libro Caja y Bancos',
    //         'listabancos' => $listabancos
    //     ]);
    // }
    // function listarinformescajaybancos(Request $request)
    // {
    //     $txtfechai = $request->get('txtfechai');
    //     $txtfechaf = $request->get('txtfechaf');
    //     $banco = $request->get('cmbbancos');
    //     $caja = new Caja();
    //     $data = $caja->listarinformescajaybancos($txtfechai, $txtfechaf, $banco);
    //     $listarsaldoinicial = $caja->listarsaldoinicial($txtfechai, $banco);
    //     return \view('cajaybancos/informes/listarinformescajaybancos', [
    //         'listado' => $data['lista'],
    //         'listarsaldoinicial' => $listarsaldoinicial['lista']
    //     ]);
    // }
}
