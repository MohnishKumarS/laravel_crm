<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{

    public function index()
    {

        $results = DB::table('yuukke_dashbaord.visitors')
            ->join(
                'marketplace_new.sma_products',
                'yuukke_dashbaord.visitors.visitor_id',
                '=',
                'marketplace_new.sma_products.details'
            )
            ->select(
                'yuukke_dashbaord.visitors.*',
                'marketplace_new.sma_products.name'
            )
            ->get();

        // $products = DB::connection('marketplace')
        //     ->table('products')
        //     ->get()
        //     ->keyBy('details');

        // $results = DB::table('visitors')
        //     ->get()
        //     ->map(function ($visitor) use ($products) {

        //         $product = $products->get($visitor->visitor_id);

        //         return [
        //             'visitor_id' => $visitor->visitor_id,
        //             'country' => $visitor->country,
        //             'product_name' => $product?->name,
        //         ];
        //     });


        $create = DB::connection('marketplace')->table('visitors')
        ->insert([
            'ip_address' => '123:345:45',
            'page' => 'http://127.0.0.1:8000/upload-test'
        ]);

        return 'successfully inserted';
        return view('upload-test');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $file = $request->file('image');

        $fileName = 'prod_' . $file->getClientOriginalName();


        // Change this path to your CodeIgniter uploads folder
        $destination = '/var/www/sttyyl/assets/uploads';

        if (!file_exists($destination)) {
            mkdir($destination, 0775, true);
        }

        $file->move($destination, $fileName);

        return back()->with([
            'success' => 'Image uploaded successfully!',
            'filename' => $fileName,
        ]);
    }


    public function sellerIndex(){
        return "hello Seller";
    }
    public function editorIndex(){
        return "hello Seller";
    }
}
