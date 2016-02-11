<!-- Name -->
<div class="form-group">
    {!! Form::label('first_name', 'Όνομα') !!}
    {!! Form::text('first_name', Input::old('first_name'), ['class' => 'form-control']) !!}
</div>

<!-- Surname -->
<div class="form-group">
    {!! Form::label('last_name', 'Επώνυμο') !!}
    {!! Form::text('last_name', Input::old('last_name'), ['class' => 'form-control']) !!}
</div>

<!-- Group selection -->
<div class="form-group">
    {!! Form::label('group_list', 'Κοινοβουλευτικές Ομάδες') !!}
    {!! Form::select('group_list[]', $groups, null, ['class' => 'form-control', 'multiple']) !!}
</div>

<!-- District selection -->
<div class="form-group">
    {!! Form::label('district', 'Περιφέρεια') !!}
    {!! Form::select('district', $districts, null, ['class' => 'form-control']) !!}
</div>


{!! Form::submit($submitButtonText, array('class' => 'btn btn-primary')) !!}
<a href="{{ url('members') }}" class="btn btn-default">Άκυρο</a>
