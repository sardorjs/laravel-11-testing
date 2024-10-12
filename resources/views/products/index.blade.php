@if(auth()->user()->is_admin)
    <a href="{{ route('products.create') }}">
        Add new product
    </a>
@endif


@forelse($products as $product)
    <h2>{{ $product->name }} </h2>
    <h3>{{ $product->price }} USD</h3>
    <h3>{{ $product->getPriceEuroAttribute() }} EUR</h3>

    @if(auth()->user()->is_admin)
        <a href="{{ route('products.edit', $product) }}">Edit</a> <br>
        <form action="{{ route('products.destroy', $product) }}" method="post">
            @csrf
            @method('DELETE')

            <button type="submit">Delete</button>
        </form>
    @endif
@empty
    <h2>
        No products found
    </h2>
@endforelse
