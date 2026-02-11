<h1>Phone Shop</h1>

@foreach($phones as $phone)
    <div>
        <img src="{{ asset('images/'.$phone->image) }}" width="150">
        <h3>{{ $phone->name }}</h3>
        <p>{{ $phone->description }}</p>
        <p>Rs {{ $phone->price }}</p>
    </div>
@endforeach
