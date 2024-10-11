@forelse($products as $product)
    <h2>{{ $product->name }}</h2>
    <h3>{{ $product->price }}</h3>
    <h3>{{ $product->getPriceEuroAttribute() }}</h3>
@empty
    <h2>
        No products found
    </h2>
@endforelse
