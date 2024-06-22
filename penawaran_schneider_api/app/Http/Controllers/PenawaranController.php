<?php

namespace App\Http\Controllers;

use App\Models\Internal;
use App\Models\Penawaran;
use App\Models\Product;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenawaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data = Penawaran::join('internals', 'penawarans.internals_id', '=', 'internals.id')
            ->join('teams as t', 'penawarans.teams_id', '=', 't.id')
            ->join('customers as c', 'penawarans.customers_id', '=', 'c.id')
            ->join("perusahaans", "perusahaans.id", '=', 'c.perusahaans_id')
            ->select(DB::raw("DATE_FORMAT(tgl, '%W, %e %M %Y, %H:%i:%s') tgl"), "t.nama as Team", "internals.nama as Internal", "c.nama as Customer", "penawarans.uri as uri", "perusahaans.nama as Perusahaan")
            ->orderBy('penawarans.tgl', 'desc')
            ->get();
        return $data;
    }
    public function customer()
    {
        //
        // $data = Penawaran::join('internals', 'penawarans.internals_id', '=', 'internals.id')
        //     ->join('teams as t', 'penawarans.teams_id', '=', 't.id')
        //     ->join('customers as c', 'penawarans.customers_id', '=', 'c.id')
        //     ->select("penawarans.tgl", "t.nama as Team", "internals.nama as Internal", "c.nama as Customer", "penawarans.uri as uri")
        //     ->where("c.id", "=", Auth::guard('customer')->user()->id)
        //     ->get();
        // return inertia::render('customer', ['data' => $data]);
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
        $products = $request->input('products');
        $customerId = $request->input('customer_id');
        // Process and save penawaran data
        try {
            // Begin database transaction
            DB::beginTransaction();

            $token = $request->bearerToken();
            $decodedToken = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));

            $penawaran = new Penawaran();
            $penawaran->customers_id = $customerId;
            $penawaran->internals_id = $decodedToken->user->id;
            $penawaran->teams_id = $decodedToken->user->teams_id;
            $currentDateTime = date('Y-m-d H:i:s');
            $penawaran->tgl = $currentDateTime;
            $penawaran->uri = hash('sha256', $currentDateTime . $customerId);
            $penawaran->save();

            $values = [];
            foreach ($request->input('products') as $itemData) {
                $data = json_decode($itemData);
                $values[] = sprintf(
                    "(%d, %d, %d, %f, %f, '%s', '%s', %f, %f, %f, %f, %f, %f)",
                    $data->dbID,
                    $penawaran->id,
                    $data->quantity,
                    $data->unitPrice,
                    $data->totalPrice,
                    $data->deliveryTime,
                    $data->remarks,
                    $data->standardDiscount,
                    $data->additionalDiscount,
                    $data->cashDiscount,
                    $data->coef,
                    $data->plAfterCoef,
                    $data->plExcel
                );
            }

            // Execute the raw SQL query to insert data into products_has_penawarans table
            $query = sprintf(
                "INSERT INTO products_has_penawarans (products_id, penawarans_id, quantity, unit_price, total_price, delivery_time, remarks, standard_discount, additional_discount, cash_discount, coef, pl_aft_coef, price_list_excl) VALUES %s",
                implode(',', $values)
            );

            DB::statement($query);

            // Commit transaction
            DB::commit();

            // Return success response
            return response()->json(['message' => 'Penawaran successfully stored'], 201);
        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollback();
            // Return error response
            return response()->json(['message' => 'Failed to store penawaran', 'error' => $e->getMessage()]);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(string $uri)
    {
        //
        $data = DB::table('penawarans as p')
            ->join('products_has_penawarans as pp', 'pp.penawarans_id', '=', 'p.id')
            ->join('products', 'pp.products_id', '=', 'products.id')
            ->select('*')->where('p.uri', '=', $uri)
            ->get();
        $head = Penawaran::join('internals', 'penawarans.internals_id', '=', 'internals.id')
            ->join('teams as t', 'penawarans.teams_id', '=', 't.id')
            ->join('customers as c', 'penawarans.customers_id', '=', 'c.id')
            ->join('perusahaans as p', 'p.id', '=', 'c.perusahaans_id')
            ->select(DB::raw("DATE_FORMAT(tgl, '%W, %e %M %Y, %H:%i:%s') tgl"), "t.nama as Team", "internals.nama as Internal", "c.nama as Customer", "t.id as teams_id", "penawarans.id", "p.nama as Perusahaan")
            ->where('penawarans.uri', '=', $uri)
            ->get();
        return json_encode(["data" => $data, "head" => $head]);
    }
    public function edit_show()
    {
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
    public function update(Request $request)
    {
        //
        $jsonData = $request->input('jsonData');
        $id_penawaran = $request->input('id_penawaran');
        $data = json_decode($jsonData, true);
        DB::beginTransaction();
        // try {
        foreach ($data as $item) {
            $products_id = $item['row'];
            $fields = $item['fields'];

            $updateData = [];
            foreach ($fields as $field) {
                foreach ($field as $columnName => $value) {
                    $updateData[$columnName] = $value;
                }
            }

            DB::table('products_has_penawarans')
                ->where('penawarans_id', $id_penawaran)
                ->where('products_id', $products_id)
                ->update($updateData);
        }

        DB::commit();

        return response()->json(['message' => 'Data updated successfully']);
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return response()->json(['message' => $fields], 500);
        // }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function dashboard()
    {
        $currentWeekTotalPrice = DB::table('penawarans')
            ->join('products_has_penawarans', 'products_has_penawarans.penawarans_id', '=', 'penawarans.id')
            ->join('customers', 'penawarans.customers_id', '=', 'customers.id')
            ->join('perusahaans', 'customers.perusahaans_id', '=', 'perusahaans.id')
            ->select(DB::raw("CONCAT(customers.nama, ' - ', perusahaans.nama) as customer"), DB::raw("sum(products_has_penawarans.total_price) as total"))
            ->whereRaw("tgl >= DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY)")
            ->whereRaw("tgl <= NOW()")
            ->groupBy('customers.nama', 'perusahaans.nama')
            ->get();



        $lastWeekTotalPrice = DB::table('penawarans')
            ->join('products_has_penawarans', 'products_has_penawarans.penawarans_id', '=', 'penawarans.id')
            ->join('customers', 'penawarans.customers_id', '=', 'customers.id')
            ->join('perusahaans', 'customers.perusahaans_id', '=', 'perusahaans.id')
            ->select(DB::raw("CONCAT(customers.nama, ' - ', perusahaans.nama) as customer"), DB::raw("sum(products_has_penawarans.total_price) as total"))
            ->whereRaw("WEEK(tgl, 1) = WEEK(NOW(), 1) - 1")
            ->whereRaw("tgl >= DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) + 7 DAY)")
            ->whereRaw("tgl < DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY)")
            ->groupBy('customers.nama', 'perusahaans.nama')
            ->get();

        $lastTwoWeeksTotalPrice = DB::table('penawarans')
            ->join('products_has_penawarans', 'products_has_penawarans.penawarans_id', '=', 'penawarans.id')
            ->join('customers', 'penawarans.customers_id', '=', 'customers.id')
            ->join('perusahaans', 'customers.perusahaans_id', '=', 'perusahaans.id')
            ->select(DB::raw("CONCAT(customers.nama, ' - ', perusahaans.nama) as customer"), DB::raw("sum(products_has_penawarans.total_price) as total"))
            ->whereRaw("WEEK(tgl, 1) = WEEK(NOW(), 1) - 2")
            ->whereRaw("tgl >= DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) + 14 DAY)")
            ->whereRaw("tgl < DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) + 7 DAY)")
            ->groupBy('customers.nama', 'perusahaans.nama')
            ->get();

        $lastThreeWeeksTotalPrice = DB::table('penawarans')
            ->join('products_has_penawarans', 'products_has_penawarans.penawarans_id', '=', 'penawarans.id')
            ->join('customers', 'penawarans.customers_id', '=', 'customers.id')
            ->join('perusahaans', 'customers.perusahaans_id', '=', 'perusahaans.id')
            ->select(DB::raw("CONCAT(customers.nama, ' - ', perusahaans.nama) as customer"), DB::raw("sum(products_has_penawarans.total_price) as total"))
            ->whereRaw("WEEK(tgl, 1) = WEEK(NOW(), 1) - 3")
            ->whereRaw("tgl >= DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) + 21 DAY)")
            ->whereRaw("tgl < DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) + 14 DAY)")
            ->groupBy('customers.nama', 'perusahaans.nama')
            ->get();

        $currentWeekQuantity = DB::table('penawarans')
            ->join('products_has_penawarans', 'products_has_penawarans.penawarans_id', '=', 'penawarans.id')
            ->join('customers', 'penawarans.customers_id', '=', 'customers.id')
            ->join('perusahaans', 'customers.perusahaans_id', '=', 'perusahaans.id')
            ->select(DB::raw("CONCAT(customers.nama, ' - ', perusahaans.nama) as customer"), DB::raw("sum(products_has_penawarans.quantity) as total"))
            ->whereRaw("tgl >= DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY)")
            ->whereRaw("tgl <= NOW()")
            ->groupBy('customers.nama', 'perusahaans.nama')
            ->get();

        $lastWeekQuantity = DB::table('penawarans')
            ->join('products_has_penawarans', 'products_has_penawarans.penawarans_id', '=', 'penawarans.id')
            ->join('customers', 'penawarans.customers_id', '=', 'customers.id')
            ->join('perusahaans', 'customers.perusahaans_id', '=', 'perusahaans.id')
            ->select(DB::raw("CONCAT(customers.nama, ' - ', perusahaans.nama) as customer"), DB::raw("sum(products_has_penawarans.quantity) as total"))
            ->whereRaw("WEEK(tgl, 1) = WEEK(NOW(), 1) - 1")
            ->whereRaw("tgl >= DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) + 7 DAY)")
            ->whereRaw("tgl < DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY)")
            ->groupBy('customers.nama', 'perusahaans.nama')
            ->get();

        $lastTwoWeeksQuantity = DB::table('penawarans')
            ->join('products_has_penawarans', 'products_has_penawarans.penawarans_id', '=', 'penawarans.id')
            ->join('customers', 'penawarans.customers_id', '=', 'customers.id')
            ->join('perusahaans', 'customers.perusahaans_id', '=', 'perusahaans.id')
            ->select(DB::raw("CONCAT(customers.nama, ' - ', perusahaans.nama) as customer"), DB::raw("sum(products_has_penawarans.quantity) as total"))
            ->whereRaw("WEEK(tgl, 1) = WEEK(NOW(), 1) - 2")
            ->whereRaw("tgl >= DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) + 14 DAY)")
            ->whereRaw("tgl < DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) + 7 DAY)")
            ->groupBy('customers.nama', 'perusahaans.nama')
            ->get();

        $lastThreeWeeksQuantity = DB::table('penawarans')
            ->join('products_has_penawarans', 'products_has_penawarans.penawarans_id', '=', 'penawarans.id')
            ->join('customers', 'penawarans.customers_id', '=', 'customers.id')
            ->join('perusahaans', 'customers.perusahaans_id', '=', 'perusahaans.id')
            ->select(DB::raw("CONCAT(customers.nama, ' - ', perusahaans.nama) as customer"), DB::raw("sum(products_has_penawarans.quantity) as total"))
            ->whereRaw("WEEK(tgl, 1) = WEEK(NOW(), 1) - 3")
            ->whereRaw("tgl >= DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) + 21 DAY)")
            ->whereRaw("tgl < DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) + 14 DAY)")
            ->groupBy('customers.nama', 'perusahaans.nama')
            ->get();
        $lastMonthTotalPrice = DB::table('penawarans')
            ->join('products_has_penawarans', 'products_has_penawarans.penawarans_id', '=', 'penawarans.id')
            ->join('customers', 'penawarans.customers_id', '=', 'customers.id')
            ->join('perusahaans', 'customers.perusahaans_id', '=', 'perusahaans.id')
            ->select(DB::raw("CONCAT(customers.nama, ' - ', perusahaans.nama) as customer"), DB::raw("sum(products_has_penawarans.total_price) as total"))
            ->whereRaw("tgl >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)")
            ->whereRaw("tgl <= NOW()")
            ->groupBy('customers.nama', 'perusahaans.nama')
            ->get();
        $lastMonthQuantity = DB::table('penawarans')
            ->join('products_has_penawarans', 'products_has_penawarans.penawarans_id', '=', 'penawarans.id')
            ->join('customers', 'penawarans.customers_id', '=', 'customers.id')
            ->join('perusahaans', 'customers.perusahaans_id', '=', 'perusahaans.id')
            ->select(DB::raw("CONCAT(customers.nama, ' - ', perusahaans.nama) as customer"), DB::raw("sum(products_has_penawarans.quantity) as total"))
            ->whereRaw("tgl >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)")
            ->whereRaw("tgl <= NOW()")
            ->groupBy('customers.nama', 'perusahaans.nama')
            ->get();


        $internals = Internal::select("*")->get();

        $data = [
            'currentWeekTotalPrice' => $currentWeekTotalPrice,
            'lastWeekTotalPrice' => $lastWeekTotalPrice,
            'lastTwoWeeksTotalPrice' => $lastTwoWeeksTotalPrice,
            'lastThreeWeeksTotalPrice' => $lastThreeWeeksTotalPrice,
            'currentWeekQuantity' => $currentWeekQuantity,
            'lastWeekQuantity' => $lastWeekQuantity,
            'lastTwoWeeksQuantity' => $lastTwoWeeksQuantity,
            'lastThreeWeeksQuantity' => $lastThreeWeeksQuantity,
            'lastMonthTotalPrice' => $lastMonthTotalPrice,
            'lastMonthQuantity' => $lastMonthQuantity,
            'internals' => $internals,
        ];
        // Mengonversi array menjadi format JSON
        return $data;
    }
}
