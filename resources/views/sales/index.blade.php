@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12 mb-4">
            <a href="{{ route('sales.create') }}" class="btn btn-primary">List New Product</a>
        </div>
        
        @foreach ($sales as $sale)
        <div class="col-md-4 mb-4">
            <div class="card h-100"> <!-- Añadido h-100 para igual altura -->
                <div class="position-relative" style="height: 200px"> <!-- Altura fija para contenedor de imagen -->
                    @if($sale->isSold)
                        <div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center" 
                            style="background-color: rgba(0,0,0,0.5); z-index: 1;">
                            <h3 class="text-white fw-bold">RESERVED!</h3>
                        </div>
                    @endif
                    <img src="data:image/jpeg;base64,{{ base64_encode($sale->thumbnail) }}" 
                        class="card-img-top h-100 w-100 object-fit-cover" 
                        alt="{{ $sale->product }}">
                </div>
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title text-truncate">{{ $sale->product }}</h5>
                    <p class="card-text" style="height: 4.5em; overflow: hidden">{{ Str::limit($sale->description, 100) }}</p>
                    <p class="card-text">
                        <strong>Price:</strong> ${{ number_format($sale->price, 2) }}
                    </p>
                    <p class="card-text">
                        <small class="text-muted">
                            Category: {{ $sale->category->name }}
                        </small>
                    </p>
                    <a href="{{ route('sales.show', $sale) }}" class="btn btn-primary mt-auto">
                        View Details
                    </a>
                </div>
            </div>
        </div>
        @endforeach
        
        <div class="col-12">
            {{ $sales->links() }}
        </div>
    </div>
</div>
@endsection