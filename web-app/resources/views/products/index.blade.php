
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

    <div class="card">
        <div class="card-header pb-0 border-0">
            <h5 class="">Search</h5>
        </div>
        <div class="card-body">
            <form action="{{route('products.index')}}" method="GET">
                @csrf
                <input name = "search" placeholder="..." class="form-control w-100" type="text" id="search">
                <button class="btn btn-dark mt-2"> Search </button>


            </form>
        </div>
    </div>

    <!-- search results-->
    <div id="searchresult"></div>


    <!-- product sort functionality -->




    <!-- go through every product, displaying name (with a link to the individual page) and price -->
    @foreach ($products as $product)
        <h2> <a href=" {{route('products.show', $product->id)}}">{{ $product->name }}</a></h2>
        <p> {{ $product ->photo}}</p>
        <p>£{{ $product -> price }}</p>
        <img src="{{asset('images/shoes/'.$product->photo_ref)}}" width = "150px" height="150px" alt = "Image">

    @endforeach



    <!-- page navigation links -->
    {{$products ->links()}}









</x-layout>

