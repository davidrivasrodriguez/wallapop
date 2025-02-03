@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Manage Products</div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Thumbnail</th>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Seller</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sales as $sale)
                                <tr>
                                    <td>{{ $sale->id }}</td>
                                    <td><img src="data:image/jpeg;base64,{{ base64_encode($sale->thumbnail) }}" alt="Thumbnail" style="max-width: 50px"></td>
                                    <td>{{ $sale->product }}</td>
                                    <td>{{ $sale->category->name }}</td>
                                    <td>${{ number_format($sale->price, 2) }}</td>
                                    <td>{{ $sale->user->name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $sale->isSold ? 'warning' : 'success' }}">
                                            {{ $sale->isSold ? 'Reserved' : 'Available' }}
                                        </span>
                                    </td>
                                    <td>{{ $sale->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <a href="{{ route('sales.show', $sale) }}" class="btn btn-sm btn-info">View</a>
                                        <a href="{{ route('sales.edit', $sale) }}" class="btn btn-sm btn-primary">Edit</a>
                                        <form action="{{ route('sales.destroy', $sale) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $sales->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection