@extends('layouts.app')
@section('content')
<div class="container py-4">
  <h1>Products</h1>
  <div class="row">
    @foreach($products as $p)
      <div class="col-md-4 mb-3">
        <div class="card h-100">
          <div class="card-body">
            <h5 class="card-title">{{ $p->name }}</h5>
            <p class="card-text">{{ Str::limit($p->description, 100) }}</p>
            <p><img src="{{ $p->photo }}" /></p>
            @if(count($p->stocks) > 0)
            <p>Stocks:</p>
            @endif
            <ul>
            @foreach($p->stocks as $stock)
                <li>{{ $stock->city }}: {{ $stock->quantity }}</li>
            @endforeach
            </ul>
            <p>Total stock: {{ $p->stocks->sum('quantity') }}</p>
            <a href="{{ route('products.show', ['product' => $p]) }}" class="btn btn-primary">View</a>
          </div>
        </div>
      </div>
    @endforeach
  </div>
  {{ $products->links() }}
</div>
@endsection
