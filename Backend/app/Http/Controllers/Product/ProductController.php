<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\ProductReview;
use App\Models\Favourite;
use Illuminate\Http\Request;
use App\Models\Products;
use Illuminate\Support\Facades\Log;
use App\Models\Category;


/*
 * tutorial credits for search functionality: https://www.youtube.com/watch?v=R58XZ8pAXoE
 * tutorial credits for product display and pagination: https://www.youtube.com/watch?v=Yi1NfLkflyU
 * tutorial credits for displaying product images: https://www.youtube.com/watch?v=IHrlw_5DGtk
 * tutorial credits for retrieving information from a form: https://www.youtube.com/watch?v=Yi1NfLkflyU
 */

class ProductController extends Controller {

    //Displays product index page (main product page)
    public function index() {
        Log::channel('stderr')->info('Get product...');

        //Products are displayed ordered by lowest price by default
        //$products = Product::orderBy('price', 'ASC');
        $products = Products::all();
        Log::channel('stderr')->info('Product: ', $products ? $products->toArray() : []);

        //SEARCH FUNCTIONALITY
        //check if there is a search request: if there is, query database using search value
        if(request()->has('search')) {
            $searchTerm = request()->get('search', '');
            $products = $products->filter(function($product) use ($searchTerm) {
                return str_contains(strtolower($product->p_name), strtolower($searchTerm));
            });
        }

        //SORT FUNCTIONALITY
        //check if there is a sort request, if there is, order items depending on selected option
        if(request()->has('sort')) {
            $products = $products->sortBy(function($product) {
                switch (request()->get('sort')) {
                    case 'recently_added':
                        return -strtotime($product->created_at);
                    case 'least_recently_added':
                        return strtotime($product->created_at);
                    case 'lowest_price':
                        return $product->price;
                    case 'highest_price':
                        return -$product->price;
                    case 'top_sustainability':
                        return -$product->sustainability;
                    default:
                        return $product->price;
                }
            });
        }

        // Handle gender filter if provided
        if(request()->has('gender')) {
            $gender = request()->get('gender');
            $products = $products->filter(function($product) use ($gender) {
                if($gender === 'men') {
                    return $product->gender_target === 'male' || $product->gender_target === 'unisex';
                } elseif($gender === 'women') {
                    return $product->gender_target === 'female' || $product->gender_target === 'unisex';
                } elseif($gender === 'kids') {
                    return $product->gender_target === 'kids';
                }
                return true;
            });
        }

        // Format response
        $formattedProducts = $products->map(function($product) {
            return [
                'P_ID' => $product->P_ID,
                'p_name' => $product->p_name,
                'gender_target' => $product->gender_target,
                'description' => $product->description,
                'categories' => $product->categories,
                'colours' => $product->colours,
                'sizes' => $product->sizes,
                'photo' => $product->photo,
                'price' => (float)$product->price,
                'sustainability' => $product->sustainability,
                'overall_stock_status' => $product->overall_stock_status,
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at
            ];
        });

        //return view with X products per page
        return response()->json($formattedProducts);
    }

    //Displays page showing details for an individual item
    public function show(string $id) {
        $product = Products::find($id);
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
        $product = Products::find($request -> input('product_id'));

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
