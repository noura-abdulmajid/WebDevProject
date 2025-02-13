<x-layout>

    <!--
    tutorial credits for product display and pagination: https://www.youtube.com/watch?v=Yi1NfLkflyU
    tutorial credits for displaying product images: https://www.youtube.com/watch?v=IHrlw_5DGtk
    tutorial credits for retrieving information from a form: https://www.youtube.com/watch?v=Yi1NfLkflyU
    -->

    <!-- details for this product are displayed here -->
    <h1> {{$product->name}}</h1>
    <img src="{{asset('images/shoes/'.$product->photo_ref)}}" width = "400px" height="400px" alt = "Image">
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
        <label for="review_title">Review Title</label>
        <input type="text" name="review_title" id="review_title">
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

        <br><label for="review">Review Text</label>
        <textarea name ="review_body" id="review_body"></textarea>
        <br>

        <select name ="product_id">
            <option value="{{$product->id}}"></option>
        </select>


        <br><button>Submit</button>
    </form>

    <br><br>

    <!-- form to add product to favourites -->
    <form method="post" action="{{route('products.favourite', $product->id)}}">
        @csrf
        <select name ="product_id">
            <option value="{{$product->id}}"></option>
        </select>
        <button>Add to favourites</button>
    </form>
    <br>

    <!-- Back button - currently goes back to first page of all listings -->
    <a href="{{route('products.index')}}">Back</a>
    <br>

</x-layout>
