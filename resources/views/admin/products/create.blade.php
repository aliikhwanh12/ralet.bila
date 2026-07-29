@extends('layouts.admin')

@section('title', 'Tambah Produk')

@section('content')
    <div class="card">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                @csrf
                @include('admin.products._form')
            </form>
        </div>
    </div>
@endsection
