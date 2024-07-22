<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

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
        $token = $request->bearerToken();
        $decodedToken = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
        if ($decodedToken->user->role != 'admin') {
            abort(403, 'Unauthorized action.');
        }
        $token = $request->bearerToken();
        $decodedToken = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
        if ($decodedToken->user->role == "admin") {
            // Check if the ref is already used
            $existingProduct = Product::where('ref', $request->input('ref'))->first();
            if ($existingProduct) {
                return response()->json([
                    'result' => "error",
                    'message' => 'Ref telah digunakan'
                ]);
            }

            $product = new Product();
            $product->ref = $request->input('ref');
            $product->description = $request->input('description');
            $product->price = $request->input('price');
            $product->bu = $request->input('bu');
            $product->activity = $request->input('activity');
            $product->standard_discounts_mpg = $request->input('standard_discounts_mpg');
            $product->type = $request->input('type');
            $product->sub_type = $request->input('sub_type');
            $product->time = $request->input('time');
            $product->activity_detail = $request->input('activity_detail');
            $product->stock = $request->input('stock');
            $product->save();
            return response()->json([
                'result' => "success",
                'message' => 'Product berhasil ditambahkan',
                'data' => $product
            ]);
        } else {
            return response()->json([
                'result' => "error",
                'message' => 'Hanya admin yang dapat menambah product'
            ]);
        }
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
        if ($by == "ref") {
            $data = Product::where('ref', 'like', "$query%")->get();
        } else if ($by == "desc") {
            $data = Product::where('description', 'like', "$query%")->get();
        }
        return $data;
    }

    public function upload(Request $request)
    {
        $token = $request->bearerToken();
        $decodedToken = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
        if ($decodedToken->user->role != 'admin') {
            abort(403, 'Unauthorized action.');
        }
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

        // Initialize array to store product data
        // $products = [];

        // Process each row
        foreach ($rows as $key => $row) {
            // Skip header row
            if ($key === 0) {
                continue;
            }

            $productData = [
                'ref' => $row[0],
                'description' => $row[1],
                'price' => $row[2],
                'bu' => $row[3],
                'activity' => $row[4],
                'standard_discounts_mpg' => $row[5],
                'type' => $row[6],
                'sub_type' => $row[7],
                'time' => $row[8],
                'activity_detail' => $row[9],
                'stock' => 0,
            ];

            // Create new product object
            $product = new Product();
            $product->ref = $productData['ref'];
            $product->description = $productData['description'];
            $product->price = $productData['price'];
            $product->bu = $productData['bu'];
            $product->activity = $productData['activity'];
            $product->standard_discounts_mpg = $productData['standard_discounts_mpg'];
            $product->type = $productData['type'];
            $product->sub_type = $productData['sub_type'];
            $product->time = $productData['time'];
            $product->activity_detail = $productData['activity_detail'];
            $product->stock = $productData['stock'];
            $product->save();

            // Add product to array
            // $products[] = $product;
        }

        return response()->json([
            'result' => "success",
            'message' => 'products berhasil diupload',
            // 'data' => $products
        ]);
    }

    public function update_stock(Request $request)
    {
        $token = $request->bearerToken();
        $decodedToken = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
        if ($decodedToken->user->role != 'admin') {
            abort(403, 'Unauthorized action.');
        }
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

        // Initialize array to store product data
        $products = [];

        // Process each row
        foreach ($rows as $key => $row) {
            // Skip header row
            if ($key === 0) {
                continue;
            }

            $productData = [
                'ref' => $row[0],
                'stock' => $row[1],
            ];
            // Create new product object
            $product = Product::where("ref", "=", $productData['ref'])->first();
            $product->stock = $productData['stock'];
            $product->save();
            // Add product to array
            $products[] = $product;
        }

        return response()->json([
            'result' => "success",
            'message' => 'stock products berhasil diupdate',
            'data' => $products
        ]);
    }
}
