<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderedItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Types\Relations\Car;
use Stripe\StripeClient;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // items in cart into array as Product
        foreach (Cart::all() as $cart)
        {
            $cart_products[] = Product::where('product_id', $cart->product_id)->first();
        }

        //items into array as Cart
        foreach (Cart::all() as $cart)
        {
            $cart_contents[] = $cart::first();
        }

    }

    /**
     * Display View/Edit Cart Page
     */

    public function viewCart()
    {
        return view('/shopping_cart/cart');
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateCart(Request $request, string $id)
    {
        // Changes items in cart i.e. quantity/size
    }

    public function checkout(Request $request)
    {
        // check items are still in stock


        // loads checkout
        $stripe = new StripeClient(env('STRIPE_SECRET'));

        $cart_products = [];
        $cart = [];

        foreach (Cart::all() as $cart)
        {
            $cart_products[] = Product::where('product_id', $cart->product_id)->first();
        }

        $cart = Cart::all();

        $line_items = [];
        $total_price = 0;

        for ($i = 0; $i < count($cart_products); $i++) {
            $line_items[] =
                [
                    'price_data' =>
                        [
                            'currency' => 'gbp',
                            'product_data' => ['name' => $cart_products[$i]->p_name,],
                            //'images' => [$cart_products[$i]->photo,],
                            'unit_amount' => ($cart_products[$i]->price) * 100,
                        ],
                    'quantity' => $cart[$i]->quantity,
                ];
            $total_price += ($cart_products[$i]->price) * $cart[$i]->quantity;
        }
//        dd($line_items);

        $checkout_session = $stripe->checkout->sessions->create(
            [
            'line_items' => $line_items,
            'mode' => 'payment',
            'success_url' => route('checkout.success', [], true),//.'?session_id={CHECKOUT_SESSION_ID}"',
            'cancel_url' => route('checkout.failed', [], true),
        ]);


        $order = new Order();
        $order->order_date = date("Y-m-d");
        $order->shipping_address = 'address'; //$request->shipping_address;
        $order->subtotal = $total_price;
        $order->delivery_charge = 0;
        $order->total_payment = $order->subtotal + $order->delivery_charge;
        $order->save();

        for ($i = 0; $i < count($cart_products); $i++)
        {
            $ordered_items = new OrderedItem();
            $ordered_items->order_id = $order->id;
            // $ordered_items->inventory_id = //something
            $ordered_items->quantity = $cart[$i]->quantity;
            $ordered_items->save();
        }

        return redirect($checkout_session->url);
    }

    /**
     * Payment Success or Failure views
     */
    public function success(Request $request)
    {
        // display order confirmation

//        $stripe = new StripeClient(env('STRIPE_SECRET'));
//        //$sessionId = $request->get('session_id');
//
//
//        try {
//            $session = $stripe->checkout->sessions->retrieve($_GET['session_id']);
//            $customer = $stripe->customers->retrieve($session->customer);
//            echo "<h1>Thanks for your order, $customer->name!</h1>";
//        } catch (Error $e) {
//            http_response_code(500);
//            echo json_encode(['error' => $e->getMessage()]);
//        }
        $this->destroy();

        dd('Success');
    }

    public function failed()
    {
        // display order failure message
        dd('failed');
    }



    // empties cart
    public function destroy()
    {
        Cart::truncate();
    }
}
