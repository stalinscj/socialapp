@if ($errors->any())
    <div dusk="validation-errors" class="alert alert-danger">
        @foreach ($errors->all() as $error)
            {{ $error }} <br>
        @endforeach
    </div>
@endif