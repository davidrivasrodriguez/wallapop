@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <img src="data:image/jpeg;base64,{{ base64_encode($sale->thumbnail) }}" 
                     class="card-img-top" alt="{{ $sale->product }}">
                
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
                                    <img src="{{ asset('storage/' . $image->path) }}" 
                                         class="img-fluid rounded" 
                                         alt="Additional image">
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
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this listing?')">
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