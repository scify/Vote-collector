@extends('app')

@section('content')
    <h1>{{ $voting->title }}</h1>

    <!--todo: errors list might never be needed here-->
    @include('errors.list')

    <h2>Προεπιλεγμένες απαντήσεις</h2>
    {!! Form::open(['action' => 'VotingsController@saveDefaultAnswers']) !!}
        {!! Form::hidden('voting_id', $voting->id) !!}
        @foreach($groups as $group)
            <div class="form-group">
                {!! Form::label('answer_' . $group->id, $group->name) !!}
                {!! Form::select('answer_' . $group->id, $voting->type->answers->lists('answer', 'id'), null, ['class' => 'form-control selectpicker']) !!}
            </div>
        @endforeach

        {!! Form::submit('Αποθήκευση', ['class' => 'btn btn-primary']) !!}
        <a href="/votings" class="btn btn-default">Άκυρο</a>
    {!! Form::close() !!}
@stop