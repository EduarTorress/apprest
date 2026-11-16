<?php

namespace App\View\Components;

use App\Models\Compra;
use Core\View\Component;

class ModalEnlaceFletes extends Component
{
    function render()
    {
        $compras = new Compra();
        $listadocumentos = $compras->listarcomprasparafletes();
        $cvista = \retornavista('components', 'modalenlacefletes');
        return view($cvista, ['compras' => $listadocumentos]);
    }
}
