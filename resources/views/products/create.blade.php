<form action="{{route('products.store')}}" method="post">
    @csrf

    <input type="text" name="name" id="name"><br>
    <input type="number" name="price" id="price"><br>

    <input type="submit" value="Submit">
</form>
