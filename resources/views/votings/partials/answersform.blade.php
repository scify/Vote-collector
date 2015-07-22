{!! Form::hidden('voting_id', $voting->id) !!}
@foreach($groups as $group)
    <div class="form-group">
        {!! Form::label('answer_' . $group->id, $group->name) !!}
        {!! Form::select('answer_' . $group->id, $voting->type->answers->lists('answer', 'id'), isset($edit)?$group->defaultAnswer($voting->id):null, ['class' => 'form-control selectpicker']) !!}
    </div>
@endforeach

{!! Form::submit('Αποθήκευση', ['class' => 'btn btn-primary']) !!}
<a href="/votings" class="btn btn-default">Άκυρο</a>