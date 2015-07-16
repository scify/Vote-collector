@extends('app')

@section('content')
    <h1>Επεξεργασία: {{ $group->name }}</h1>

    @include('errors.list')

    {!! Form::model($group, ['route' => ['groups.update', $group->id], 'method' => 'PUT']) !!}
        @include('groups._form', ['submitButtonText' => 'Αποθήκευση'])
    {!! Form::close() !!}
@stop

@section('footer')
    <script type="text/javascript">
        $('select').select2();
    </script>
@stop