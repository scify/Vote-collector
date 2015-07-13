<div class="form-group">
    {!! Form::label('first_name', 'First name') !!}
    {!! Form::text('first_name', Input::old('first_name'), array('class' => 'form-control')) !!}

    {!! Form::label('last_name', 'Last name') !!}
    {!! Form::text('last_name', Input::old('last_name'), array('class' => 'form-control')) !!}
</div>

<!-- Group selection -->
<div class="form-group">
    {!! Form::label('group_list', 'Groups:') !!}
    {!! Form::select('group_list[]', $groups, null, ['class' => 'form-control selectpicker', 'multiple', 'data-selected-text-format' => 'count']) !!}
</div>

{!! Form::submit($submitButtonText, array('class' => 'btn btn-primary')) !!}
<a href="/members" class="btn btn-default">Cancel</a>