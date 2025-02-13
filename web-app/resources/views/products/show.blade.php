<x-layout>

    <!-- This page displays details for individual products -->

    <!--
    tutorial credits for product display and pagination: https://www.youtube.com/watch?v=Yi1NfLkflyU
    tutorial credits for displaying product images: https://www.youtube.com/watch?v=IHrlw_5DGtk
    tutorial credits for retrieving information from a form: https://www.youtube.com/watch?v=Yi1NfLkflyU
    -->

    <!-- details for this product are displayed here -->
    <h1> {{$product->name}}</h1>
    <img src="{{asset('images/shoes/'.$product->photo_ref)}}" width = "400px" height="400px">
    <p>Description: {{$product->description}}</p>
    <p>Price: £{{$product->price}} </p>
    <p>Available colours:{{$product->colours}}</p>
    <p>Available sizes: {{$product->sizes}}</p>

    <!-- reviews for this product are displayed here -->
    <br><h4>Product reviews</h4>
    <p>There are {{count($product_reviews)}} reviews for this product.</p>
    @foreach ($product_reviews as $product_review)
        <b>{{$product_review['title']}}</b><br>
        {{$product_review['review_body']}}<br>
        {{$product_review['created_at']}}<br>
    @endforeach




    <!-- Form to review product -->

    <br><h4>Review product</h4>
    <form method = "post" action ="{{route('products.save_review', $product->id)}}">
        @csrf

        <!-- field to enter review title -->
        <label for="review_title">Review Title</label>
        <input type="text" name="review_title" id="review_title">


        <!--Drop down box to select rating value -->
        <label for="product_rating">Rating</label>

        <select name="product_rating">
            <option value="1">1</option>
            <option value="1.5">1.5</option>
            <option value="2">2</option>
            <option value="2.5">2.5</option>
            <option value="3">3</option>
            <option value="3.5">3.5</option>
            <option value="4">4</option>
            <option value="4.5">4.5</option>
            <option value="5">5</option>
        </select>

        <!-- field to enter review body -->
        <br><label for="review">Review Text</label>
        <textarea name ="review_body" id="review_body"></textarea>
        <br>

        <!-- this field is to enable passing of the product id to the form - may need to be refined -->
        <select name ="product_id">
            <option value="{{$product->id}}"></option>
        </select>


        <br><button>Submit Review</button>
    </form>

    <br><br>

    <!-- form to add product to favourites -->
    <h4>Add product to favourites</h4>
    <form method="post" action="{{route('products.favourite', $product->id)}}">
        @csrf
        <select name ="product_id">
            <option value="{{$product->id}}"></option>
        </select>
        <button>Add Product to Favourites</button>
    </form>
    <br>

    <!-- form to add product to shopping cart, selecting colour and size -->
    <h4>Add to shopping cart</h4>
    <form method ="post" action="{{route('products.add_to_cart', $product->id)}}">
        @csrf
        <!-- this field is to enable passing of the product id to the form - may need to be refined -->
        <select name ="product_id">
            <option value="{{$product->id}}"></option>
        </select>

        <!-- size to add to cart -->
        <select name = "size">
            <label for ="size">Select Size</label>
            @foreach(explode(',', $product['sizes']) as $size) <!-- an array is created from the sizes available for the given product -->
                <option>{{$size}}</option> <!-- array elements output as options -->
            @endforeach

        </select>

        <!-- colour to add to cart -->
        <select name = "colour">
            <label for="colour">Select colour</label>
            @foreach(explode(',', $product['colours']) as $colour) <!-- an array is created from the colour options specific to the product -->
                <option>{{$colour}}</option> <!-- array elements output as options -->
            @endforeach
        </select>
        <button>Add to cart</button>
    </form>



    <!-- Back button - currently goes back to first page of all listings -->
    <br>
    <a href="{{route('products.index')}}">Back</a>
    <br>
    <br>
    <br>

</x-layout>
