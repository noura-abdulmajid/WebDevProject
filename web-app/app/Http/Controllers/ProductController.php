<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\ProductReview;
use App\Models\Favourite;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Support\Facades\Route;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

/*
 * tutorial credits for search functionality: https://www.youtube.com/watch?v=R58XZ8pAXoE
 * tutorial credits for product display and pagination: https://www.youtube.com/watch?v=Yi1NfLkflyU
 * tutorial credits for displaying product images: https://www.youtube.com/watch?v=IHrlw_5DGtk
 * tutorial credits for retrieving information from a form: https://www.youtube.com/watch?v=Yi1NfLkflyU
 */

class ProductController extends Controller {

    //Displays product index page (main product page)
    public function index() {

        //Products are displayed ordered by lowest price by default
        //$products = Product::orderBy('price', 'ASC');
        $products = Product::all()->first();

        //SEARCH FUNCTIONALITY
        //check if there is a search request: if there is, query database using search value
        if(request()->has('search')) {
            $products = $products->where('name', 'like', '%' . request()->get('search','') . '%');
        }

        //SORT FUNCTIONALITY
        //check if there is a sort request, if there is, order items depending on selected option
        if(request()->has('sort')) {

            switch (request()->get('sort')) {
                case 'recently_added':
                    $products = Product::orderBy('created_at', 'DESC');
                    break;
                case 'least_recently_added':
                    $products = Product::orderBy('created_at', 'ASC');
                    break;
                case 'lowest_price':
                    $products = Product::orderBy('price', 'ASC');
                    break;
                case 'highest_price':
                    $products = Product::orderBy('price', 'DESC');
                    break;
                case 'top_sustainability':
                    $products = Product::orderBy('sustainability', 'DESC');
                    break;
                default:
                    $products = Product::orderBy('price', 'DESC');
                    break;
            }

        }

        //return view with X products per page
        return response()->json($products);

    }

    //Displays page showing details for an individual item
    public function show(string $id) {

        $product = Product::find($id);
        $product_reviews = ProductReview::where('product_id', $id)->get();

        $data = [
            'product' => $product,
            'product_reviews' => $product_reviews,
        ];

        return response()->json($data);
    }

    //Functionality to save individual product reviews
    public function save_review (Request $request)  {

        $productReview = new ProductReview;

        $productReview->title = $request->input('review_title');
        $productReview->review_body = $request->input('review_body');
        $productReview->rating = $request->input('product_rating');
        $productReview->product_id = $request -> input('product_id'); //needs to be extracted from page url
        $productReview->customer_id = 1; //needs to be extracted from customer when customer table is used in full application

        $productReview->save(); //New review gets saved in the database.

        //After saving, redirects to individual product detail page for given product
        return redirect()->route('products.show', $productReview->product_id);

    }

    //Functionality to add item to favourites
    public function favourite (Request $request) {

        $favourite = new Favourite;

        $favourite -> product_id = $request -> input('product_id');
        $favourite-> customer_id = 1; //this will need to be replaced with actual customer ID when customer table is added

        //need to perform more checks to only add favourite, if customer and product combination not included there at same time
        //also need to change html element (empty dropdown boxes)
        $favourite->save(); //New favourite gets saved in the database.
        return redirect()->route('products.show', $favourite->product_id);

    }

    //Functionality to add purchased product to Cart (via adding it into cart table)
    public function add_to_cart (Request $request) {
        $cart = new Cart;

        $product = Product::find($request -> input('product_id'));


        $cart -> product_id = $request -> input('product_id');
        $cart -> size = $request -> input('size');
        $cart -> colour = $request -> input('colour');
        $cart -> quantity = 1; //will have to cause this to increment
        $cart -> price = $product -> price;

        //add to cart
        $cart -> save();
        return redirect()->route('products.show', $cart -> product_id);
    }

}
