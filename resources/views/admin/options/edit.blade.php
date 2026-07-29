@extends('layouts.admin')

@section('title', 'Ubah Durasi')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.options.update', $option) }}">
                        @csrf
                        @method('PUT')
                        @include('admin.options._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
