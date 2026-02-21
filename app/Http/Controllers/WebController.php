<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class WebController extends Controller
{
    public function home()
    {
        return view('web.home');
    }

    public function catalogue(Request $request)
    {
        $productos = Producto::active()
            ->when($request->category, fn($q, $c) => $q->where('categoria_id', $c))
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('web.catalogue', compact('productos'));
    }

    public function cart()
    {
        return view('web.cart');
    }
}
