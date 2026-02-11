<h1>Add Phone</h1>

<form method="POST" action="/admin/phones" enctype="multipart/form-data">
    @csrf
    <input type="text" name="name" placeholder="Phone name"><br>
    <textarea name="description"></textarea><br>
    <input type="number" name="price"><br>
    <input type="file" name="image"><br>
    <button>Add</button>
</form>
