@extends('layouts.app')

@section('content')
<div class="container py-4">
  <a href="{{ route('products.index') }}" class="btn btn-link mb-3">&larr; Back</a>

  <div class="card mb-3">
    <div class="card-body">
      <h2>{{ $productCore->name }}</h2>
      <p>{{ $productCore->description }}</p>
      <p><strong>Price:</strong> {{ $productCore->price }}</p>

      <!-- Real-time stocks -->
      <p><strong>Stocks (real-time):</strong></p>
      <ul>
        @forelse($stocks as $stock)
          <li>{{ $stock->city }}: {{ $stock->quantity }}</li>
        @empty
          <li>Out of stock</li>
        @endforelse
      </ul>

      <!-- Tags -->
      <p>
        @foreach($productCore->tags as $t)
          <span class="badge bg-secondary">{{ $t['name'] }}</span>
        @endforeach
      </p>
    </div>
  </div>

  <h4>Related products</h4>
  <div class="row">
    @foreach($related as $r)
    <div class="col-md-3">
      <div class="card mb-3">
        <div class="card-body">
          <h5>{{ $r->description }}</h5>
          <p><img src="{{ $r->photo }}" /></p>
          <a href="{{ route('products.show', ['product' => $r]) }}" class="btn btn-sm btn-outline-primary">View</a>
        </div>
      </div>
    </div>
    @endforeach
  </div>
</div>
@endsection
