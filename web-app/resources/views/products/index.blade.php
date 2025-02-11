
<x-layout>

    <h1>Our Products</h1>

    <!-- go through every product, displaying name (with a link to the individual page) and price -->
    @foreach ($products as $product)
        <h2> <a href=" {{route('products.show', $product->id)}}">{{ $product->name }}</a></h2>
        <p> {{ $product ->photo}}</p>
        <p>{{ $product -> price }}</p>
        <img src="{{asset('images/shoes/'.$product->photo_ref)}}" width = "150px" height="150px" alt = "Image">

    @endforeach

    <!-- page navigation links -->
    {{$products ->links()}}





</x-layout>

