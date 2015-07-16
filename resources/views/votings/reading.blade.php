@extends('app')

@section('content')
    <h1>Πρώτη ανάγνωση</h1>

    {!! Form::open(['action' => 'VotingsController@saveAnswers', 'class' => 'form-horizontal']) !!}

        @foreach($members as $member)
            <fieldset>
                <div class="form-group">
                    {!! Form::label('answer_' . $member->id, $member->first_name . ' ' . $member->last_name, ['class' => 'control-label col-sm-2']) !!}
                    <div class="col-sm-3">
                        {!! Form::select('answer_' . $member->id, $voting->type->answers->lists('answer', 'id'), $member->groupAnswer($voting->id), ['class' => 'form-control selectpicker']) !!}
                    </div>
                </div>
            </fieldset>
        @endforeach

        {!! Form::submit('Αποθήκευση', ['class' => 'btn btn-primary']) !!}
        <a href="/votings" class="btn btn-default">Άκυρο</a>
    {!! Form::close() !!}
@stop