<!-- Title -->
<div class="form-group">
    {!! Form::label('title', 'Τίτλος') !!}
    {!! Form::text('title', Input::old('title'), array('class' => 'form-control')) !!}
</div>

<!-- Voting type selection -->
<div class="form-group">
    {!! Form::label('voting_type', 'Τύπος ψηφοφορίας:') !!}
    {!! Form::select('voting_type', $types, null, ['class' => 'form-control selectpicker']) !!}
</div>

<!-- Vote objective selection -->
<div class="form-group">
    {!! Form::label('objective', 'Αντικείμενο ψηφοφορίας:') !!}
    {!! Form::select('objective', $objectives, null, ['class' => 'form-control selectpicker']) !!}
</div>

<!-- Submit and cancel buttons -->
{!! Form::submit($submitButtonText, array('class' => 'btn btn-primary')) !!}
<a href="{{ url('votings') }}" class="btn btn-default">Άκυρο</a>