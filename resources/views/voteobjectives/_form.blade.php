<!-- Title field -->
<div class="form-group">
    {!! Form::label('title', 'Title') !!}
    {!! Form::text('title', Input::old('title'), array('class' => 'form-control')) !!}
</div>

<!-- Description field -->
<div class="form-group">
    {!! Form::label('description', 'Description') !!}
    {!! Form::textarea('description', null, array('class' => 'form-control')) !!}
</div>

<!-- Submit and cancel buttons -->
{!! Form::submit($submitButtonText, array('class' => 'btn btn-primary')) !!}
<a href="/voteobjectives" class="btn btn-default">Cancel</a>