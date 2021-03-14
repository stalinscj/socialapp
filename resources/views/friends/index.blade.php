@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            @forelse ($friends as $friend)
                <div class="col-md-3">
                    @include('partials.user', ['user' => $friend])
                </div>
            @empty
                <div class="bg-light p-3 rounded mx-3 shadow-sm w-100 text-secondary">
                    Aún no tienes amigos, envía o acepta solicitudes de amistad para tener amigos.
                </div>
            @endforelse
        </div>
    </div>

@endsection