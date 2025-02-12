<?php

namespace App\Http\Controllers;

use App\Models\ProductReview;
use App\Models\Favourite;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\View\View;

class ProductController extends Controller
{
    //displays all products
    public function index() {

        return view('products.index', ['products' => Product::paginate(10)]);
    }

    //displays page for an individual item
    public function show(string $id) {

        $product = Product::find($id);
        $product_reviews = ProductReview::where('product_id', $id)->get();

        $data = [
            'product' => $product,
            'product_reviews' => $product_reviews,
        ];

        return view('products.show', $data);
    }

    public function save_review (Request $request)  {

        $productReview = new ProductReview;

        $productReview->title = $request->input('review_title');
        $productReview->review_body = $request->input('review_body');
        $productReview->rating = $request->input('product_rating');
        $productReview->product_id = $request -> input('product_id'); //needs to be extracted from page url
        $productReview->customer_id = 1; //needs to be extracted from customer when customer table is used in full application

        $productReview->save(); //New review gets saved in the database.
        return redirect()->route('products.show', $productReview->product_id);

    }

    public function favourite (Request $request) {

        $favourite = new Favourite;

        $favourite -> product_id = $request -> input('product_id');
        $favourite-> customer_id = 1; //this will need to be replaced with actual customer ID when customer table is added

        //need to perform more checks to only add favourite, if customer and product combination not included there at same time
        //also need to change html element (empty dropdown boxes)
        $favourite->save(); //New favourite gets saved in the database.
        return redirect()->route('products.show', $favourite->product_id);

    }
}
