@extends('app')

@section('content')
    <h1>Πρώτη ανάγνωση</h1>

    {!! Form::open(['action' => 'VotingsController@saveAnswers']) !!}

        @foreach($members as $member)
            <div class="form-group">
                {!! Form::label('answer_' . $member->id, $member->first_name . ' ' . $member->last_name) !!}
                <!--todo: to apo katw anti gia null na exei thn default apanthsh me vasi to prwto group pou einai o vouleutis-->
                {!! Form::select('answer_' . $member->id, $voting->type->answers->lists('answer', 'id'), null, ['class' => 'form-control selectpicker']) !!}
            </div>
        @endforeach

        {!! Form::submit('Αποθήκευση', ['class' => 'btn btn-primary']) !!}
        <a href="/votings" class="btn btn-default">Άκυρο</a>
    {!! Form::close() !!}
@stop