@extends('layouts.admin')

@section('title', 'Tambah Durasi')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.variants.options.store', $variant) }}">
                        @csrf
                        @include('admin.options._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
