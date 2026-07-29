@extends('layouts.admin')

@section('title', 'Tambah Jenis')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.products.variants.store', $product) }}">
                        @csrf
                        @include('admin.variants._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
