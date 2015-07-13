<!-- Title field -->
<div class="form-group">
    {!! Form::label('title', 'Τίτλος') !!}
    {!! Form::text('title', Input::old('title'), array('class' => 'form-control')) !!}
</div>

<!-- Description field -->
<div class="form-group">
    {!! Form::label('description', 'Περιγραφή') !!}
    {!! Form::textarea('description', null, array('class' => 'form-control')) !!}
</div>

<!-- Submit and cancel buttons -->
{!! Form::submit($submitButtonText, array('class' => 'btn btn-primary')) !!}
<a href="/voteobjectives" class="btn btn-default">Άκυρο</a>