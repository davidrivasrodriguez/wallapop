@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="position-relative">
                    @if($sale->isSold)
                        <div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center" 
                            style="background-color: rgba(0,0,0,0.5); z-index: 1;">
                            <h3 class="text-white fw-bold">RESERVED!</h3>
                        </div>
                    @endif
                    <img src="data:image/jpeg;base64,{{ base64_encode($sale->thumbnail) }}" 
                        class="card-img-top" alt="{{ $sale->product }}">
                </div>
                
                <div class="card-body">
                    <h2 class="card-title">{{ $sale->product }}</h2>
                    
                    <p class="card-text">
                        <strong>Price:</strong> ${{ number_format($sale->price, 2) }}
                    </p>
                    
                    <p class="card-text">
                        <strong>Category:</strong> {{ $sale->category->name }}
                    </p>
                    
                    <p class="card-text">
                        <strong>Seller:</strong> {{ $sale->user->name }}
                    </p>
                    
                    <p class="card-text">
                        <strong>Description:</strong><br>
                        {{ $sale->description }}
                    </p>

                    @if($sale->images->count() > 0)
                        <h4 class="mt-4">Additional Images</h4>
                        <div class="row">
                            @foreach($sale->images as $image)
                                <div class="col-md-4 mb-3">
                                    <div style="aspect-ratio: 1; overflow: hidden">
                                        <img src="{{ route('private.storage', str_replace('products/', '', $image->path)) }}" 
                                            class="img-fluid rounded w-100 h-100 object-fit-cover" 
                                            alt="Additional image">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if(Auth::id() === $sale->user_id)
                        <div class="mt-4">
                            <form action="{{ route('sales.update', $sale) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="isSold" value="{{ $sale->isSold ? '0' : '1' }}">
                                <button type="submit" class="btn btn-{{ $sale->isSold ? 'success' : 'warning' }}">
                                    Mark as {{ $sale->isSold ? 'Available' : 'Sold' }}
                                </button>
                            </form>

                            <a href="{{ route('sales.edit', $sale) }}" class="btn btn-primary">Edit</a>
        
                            <form action="{{ route('sales.destroy', $sale) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" 
                                        onclick="return confirm('Are you sure you want to delete this listing?')">
                                    Delete
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection