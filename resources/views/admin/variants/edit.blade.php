@extends('layouts.admin')

@section('title', 'Ubah Jenis')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.variants.update', $variant) }}">
                        @csrf
                        @method('PUT')
                        @include('admin.variants._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
