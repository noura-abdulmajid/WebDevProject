<x-layout>

    <h1> {{$product->name}}</h1>
    <img src="{{asset('images/shoes/'.$product->photo_ref)}}" width = "400px" height="400px" alt = "Image">
    <p>Description: {{$product->description}}</p>
    <p>Price: £{{$product->price}} </p>
    <p>Available colours:{{$product->colours}}</p>
    <p>Available sizes: {{$product->sizes}}</p>

    <!-- need to insert product review form here -->
    Review product <br>

    <!-- Back button - currently goes back to first page of all listings -->
    <a href="{{route('products.index')}}">Back</a>

</x-layout>
