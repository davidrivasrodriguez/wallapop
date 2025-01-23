@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Welcome to Our Marketplace
                </div>
                <div class="card-body">
                    <!-- Sales Links -->
                    <div class="mb-4">
                        <h5>Marketplace</h5>
                        <a href="{{ route('sales.index') }}" class="btn btn-primary">View All Products</a>
                        <a href="{{ route('sales.create') }}" class="btn btn-success">List New Product</a>
                    </div>

                    <!-- Existing Links -->
                    @if (Auth::check())
                        <div class="mb-4">
                            <h5>User Options</h5>
                            <a href="{{ route('profile.show') }}" class="btn btn-secondary">Profile Settings</a>
                            
                            @if (Auth::user()->role == 'admin')
                                <div class="mt-3">
                                    <a href="{{ route('admin.users.create') }}" class="btn btn-warning">Create User</a>
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-info">Manage Users</a>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection