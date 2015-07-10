@if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> {{ $error }}<br/>
        @endforeach
    </div>
@endif