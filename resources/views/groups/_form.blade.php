<!-- Name -->
<div class="form-group">
    {!! Form::label('name', 'Name') !!}
    {!! Form::text('name', Input::old('name'), array('class' => 'form-control')) !!}
</div>

<!-- Members selection -->
<div class="form-group">
    {!! Form::label('member_list', 'Members:') !!}
    {!! Form::select('member_list[]', $members, null, ['class' => 'form-control', 'multiple']) !!}
</div>

{!! Form::submit($submitButtonText, array('class' => 'btn btn-primary')) !!}
<a href="/groups" class="btn btn-default">Cancel</a>