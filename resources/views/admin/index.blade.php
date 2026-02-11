<h1>Admin - Phones</h1>

<a href="/admin/phones/create">Add Phone</a>

@foreach($phones as $phone)
    <div>
        <h3>{{ $phone->name }}</h3>

        <a href="/admin/phones/{{ $phone->id }}/edit">Edit</a>

        <form method="POST" action="/admin/phones/{{ $phone->id }}">
            @csrf
            @method('DELETE')
            <button type="submit">Delete</button>
        </form>
    </div>
@endforeach
