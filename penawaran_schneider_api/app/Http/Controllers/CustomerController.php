<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

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
        $customer = new Customer();
        $customer->nama = $request->input('nama');
        $customer->email = $request->input('email');
        $customer->password = bcrypt($request->input('password'));
        $customer->handphone = $request->input('handphone');
        $customer->perusahaans_id = $request->input('perusahaans_id');
        $customer->save();
        return response()->json([
            'result' => "success",
            'message' => 'Customer berhasil ditambahkan',
            'data' => $customer
        ]);
    }
    public function upload(Request $request)
    {
        // Validate file input
        $request->validate([
            'file' => 'required|mimes:xlsx'
        ]);

        // Get file from request
        $file = $request->file('file');
        $filePath = $file->getRealPath();

        // Load the spreadsheet
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        // Initialize array to store customer data
        $customers = [];

        // Process each row
        foreach ($rows as $key => $row) {
            // Skip header row
            if ($key === 0) {
                continue;
            }

            $customerData = [
                'nama' => $row[0],
                'email' => $row[1],
                'handphone' => $row[2],
                'nama_perusahaan' => $row[3]
            ];

            // Find company by name
            $perusahaan = Perusahaan::where("nama", $customerData['nama_perusahaan'])->first();

            if ($perusahaan) {
                // Create new customer object
                $customer = new Customer();
                $customer->nama = $customerData['nama'];
                $customer->email = $customerData['email'];
                $customer->password = bcrypt("123");
                $customer->handphone = $customerData['handphone'];
                $customer->perusahaans_id = $perusahaan->id;
                $customer->save();

                // Add customer to array
                $customers[] = $customer;
            }
        }

        return response()->json([
            'result' => "success",
            'message' => 'Customers berhasil diupload',
            'data' => $customers
        ]);
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
