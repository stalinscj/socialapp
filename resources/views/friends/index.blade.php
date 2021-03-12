@extends('layouts.app')

@section('content')

    <div class="container">

        @foreach ($friends as $friend)
            <p>{{ $friend->name }}</p>
        @endforeach

    </div>

@endsection