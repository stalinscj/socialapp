@extends('layouts.app')

@section('content')
	<div class="container">

		@forelse ($friendshipRequests as $friendshipRequest)

			<accept-friendship-btn
				:sender="{{ $friendshipRequest->sender }}"
				friendship-status="{{ $friendshipRequest->status }}"
			></accept-friendship-btn>

		@empty
			<div class="bg-light p-3 rounded mb-3 shadow-sm text-secondary">
				No posees solicitudes de amistad.
			</div>
		@endforelse

	</div>
@endsection