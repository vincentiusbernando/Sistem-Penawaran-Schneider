<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data = Product::all();
        return $data;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function search_ref(Request $request)
    {
        $query = $request->input('search');
        $items = Product::where('ref', 'like', "$query%")->get();
        return $items;
    }
    public function search_product(string $by, string $query)
    {
        if ($by=="ref"){
            $data = Product::where('ref', 'like', "$query%")->get();
        }
        else if ($by=="desc"){
            $data = Product::where('description', 'like', "$query%")->get();
        }
        return $data;
    }
}
