<h1>Edit Phone</h1>

<form method="POST" action="/admin/phones/{{ $phone->id }}">
    @csrf
    @method('PUT')

    <input type="text" name="name" value="{{ $phone->name }}"><br>
    <textarea name="description">{{ $phone->description }}</textarea><br>
    <input type="number" name="price" value="{{ $phone->price }}"><br>

    <button>Update</button>
</form>
