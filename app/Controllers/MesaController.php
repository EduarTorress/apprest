<?php

namespace App\Controllers;

use App\Models\Mesas;
use App\Models\Vendedor;
use Core\Http\Request;
use Core\Routing\Controller;
use Valitron\Validator;

class MesaController extends Controller
{
    function index()
    {
        $titulo = 'Mesas';
        return view('admin/mesas/index', ['titulo' => $titulo, 'modo' => 'A']);
    }
    function lista(Request $request)
    {
        $cbuscar =  $request->get('cmbalmacen');
        $mesa = new Mesas();
        $mesas = $mesa->listar($cbuscar);
        $modo = 'A';
        return view('admin/mesas/listamesas', ['lista' => $mesas, 'modo' => $modo]);
    }
    function create()
    {
        $titulo = 'Registrar Mesa';
        return view('admin/mesas/create', ['titulo' => $titulo, 'modo' => 'N', 'id' => 0]);
    }
    function edit($id)
    {
        $mesas = new Mesas();
        $mesa = $mesas->buscarid($id);
        $titulo = 'Editar Mesa ' . $mesa['idmesa'];
        return view('admin/mesas/create', ['titulo' => $titulo, 'modo' => 'A', 'lista' => $mesa, 'id' => $id]);
    }
    function store(Request $request)
    {
        try {
            $mesas = new Mesas();
            $mesas->txtnombre = $request->get('txtnombre');
            $mesas->cmbalmacen = $request->get('cmbalmacen');
            if ($mesas->save()) {
                return response()->json(['message' => 'Registrado correctamente'], 200); // Created
            } else {
                return response()->json(['message' => 'Error al registrar'], 400);
            }
        } catch (\Exception $error) {
            return response()->json(['message' => 'Hubo un error' . $error->getMessage()], 500);
        }
    }
    public function update($id, Request $request)
    {
        $validator = new Validator($request->getBody());
        $validator->rule('required', 'txtnombre')->message('El nombre es obligatorio');
        $validator->labels([
            'nombre' => 'txtnombre'
        ]);
        if (!$validator->validate()) {
            $data = [
                'message' => 'Errores de validacion',
                'errors' => $validator->errors()
            ];
            return response()->json($data, 422);
        }
        try {
            $mesa = new Mesas();
            $mesa->txtnombre = $request->get('txtnombre');
            $mesa->cmbalmacen = $request->get('cmbalmacen');
            if ($mesa->update($id)) {
                return response()->json(['message' => 'Actualizado correctamente'], 200);
            } else {
                return response()->json(['message' => 'Error al actualizar'], 400);
            }
        } catch (\Exception $error) {
            return response()->json(['message' => 'Hubo un error' . $error->getMessage()], 500);
        }
    }
}
