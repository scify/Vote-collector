<!-- Name -->
<div class="form-group">
    {!! Form::label('name', 'Όνομα') !!}
    {!! Form::text('name', Input::old('name'), array('class' => 'form-control')) !!}
</div>

<!-- Members selection -->
<div class="form-group">
    {!! Form::label('member_list', 'Μέλη:') !!}
    {!! Form::select('member_list[]', $members, null, ['class' => 'form-control', 'multiple']) !!}
</div>

{!! Form::submit($submitButtonText, array('class' => 'btn btn-primary')) !!}
<a href="{{ url('groups') }}" class="btn btn-default">Άκυρο</a>