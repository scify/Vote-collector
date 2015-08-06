{{-- Title --}}
<div class="form-group">
    {!! Form::label('title', 'Τίτλος') !!}
    {!! Form::text('title', Input::old('title'), array('class' => 'form-control')) !!}
</div>

{{-- Voting starts with one voting item, that can be cloned by javascript to add more than one voting items --}}
<div class="votingItem">
    {{-- Vote objective selection --}}
    <div class="form-group col-sm-6">
        {!! Form::label('objectives[]', 'Αντικείμενο:') !!}
        {!! Form::select('objectives[]', $objectives, null, ['class' => 'form-control selectpicker']) !!}
    </div>

    {{-- Voting type selection --}}
    <div class="form-group col-sm-6">
        {!! Form::label('voting_types[]', 'Τύπος απάντησης:') !!}
        {!! Form::select('voting_types[]', $types, null, ['class' => 'form-control selectpicker']) !!}
    </div>
</div>


{{-- Submit and cancel buttons --}}
{!! Form::submit($submitButtonText, array('class' => 'btn btn-primary')) !!}
<a href="{{ url('votings') }}" class="btn btn-default">Άκυρο</a>

{{-- New voting item button --}}
<a href="#" id="addVotingItemButton" class="btn btn-default pull-right"><span class="glyphicon glyphicon-plus"></span> Προσθήκη αντικειμένου</a>
