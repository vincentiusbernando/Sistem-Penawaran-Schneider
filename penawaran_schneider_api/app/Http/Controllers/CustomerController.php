<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Termwind\Components\Raw;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function search(Request $request)
    {
        $query = $request->input('search');
        $items = Customer::join('perusahaans', 'customers.perusahaans_id', '=', 'perusahaans.id')
            ->where('customers.nama', 'like', $query . '%') // Specify the table name
            ->select(DB::raw('CONCAT(customers.nama, " - ", perusahaans.nama) AS customer_perusahaan'), 'perusahaans.standard_discount', "perusahaans.additional_discount", "customers.id as customer_id")->get();
        return response()->json($items);
    }
}
