
<x-layout>

    <!--
    tutorial credits for search functionality: https://www.youtube.com/watch?v=R58XZ8pAXoE
    tutorial credits for product display and pagination: https://www.youtube.com/watch?v=Yi1NfLkflyU
    tutorial credits for displaying product images: https://www.youtube.com/watch?v=IHrlw_5DGtk
    tutorial credits for retrieving information from a form: https://www.youtube.com/watch?v=Yi1NfLkflyU
    -->

    <h1>Our Products</h1>

    <!-- product search functionality -->
    <!-- search bar -->

    <h5>Search</h5>
    <form action="{{route('products.index')}}" method="GET">
        @csrf
        <input name = "search" placeholder="..." type="text" id="search">
        <button> Search </button>


    </form>

    <!-- search results-->
    <div id="searchresult"></div>


    <!-- product sort functionality -->
    <form method="GET" action="{{route('products.index')}}">
        @csrf
        <!-- <input type="hidden" name="url" id="url" value="{$url}}"> -->
        <select name="sort" id="sort">  <!-- select element defines drop down list -->
            <option selected>Sort By: Newest Items</option>
            <option value = "lowest_price">Sort By: Price, low to high</option>
            <option value = "highest_price">Sort By: Price, high to low</option>
            <option value = "recently_added">Sort By: Recently added</option>
            <option value = "least_recently_added">Sort By: Least recently added</option>
            <option value = "top_sustainability">Top sustainability picks</option>
        </select>
        <button> Sort </button>
    </form>







    <!-- go through every product, displaying name (with a link to the individual page) and price -->
    @foreach ($products as $product)
        <h2> <a href=" {{route('products.show', $product->id)}}">{{ $product->name }}</a></h2>
        <p> {{ $product ->photo}}</p>
        <p> £{{ $product -> price }}</p>
        <img src="{{asset('images/shoes/'.$product->photo_ref)}}" width = "150px" height="150px" alt = "Image">

    @endforeach



    <!-- page navigation links -->
    {{$products ->links()}}









</x-layout>

