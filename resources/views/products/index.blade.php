@if(auth()->user()->is_admin)
    <a href="{{ route('products.create') }}">
        Add new product
    </a>
@endif


@forelse($products as $product)
    <h2>{{ $product->name }}</h2>
    <h3>{{ $product->price }} USD</h3>
    <h3>{{ $product->getPriceEuroAttribute() }} EUR</h3>
@empty
    <h2>
        No products found
    </h2>
@endforelse
