<form action="{{ route('products.update', $product) }}" method="post">
    @csrf
    @method('PUT')

    <input type="text" name="name" id="name" value="{{ $product->name }}"><br>
    <input type="number" name="price" id="price" value="{{ $product->price }}"><br>

    <input type="submit" value="Submit">
</form>
