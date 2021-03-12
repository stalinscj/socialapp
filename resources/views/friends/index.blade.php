@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            @foreach ($friends as $friend)
                <div class="col-md-3">
                    @include('partials.user', ['user' => $friend])
                </div>
            @endforeach
        </div>
    </div>

@endsection