@extends('layouts.admin')

@section('title', 'Edit Produk')

@section('content')
    <div class="card">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('admin.products._form')
            </form>
        </div>
    </div>
@endsection
