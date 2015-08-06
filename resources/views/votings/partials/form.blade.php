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

<!--todo: make ids unique in this page-->

<div class="col-sm12">
    {{-- Submit and cancel buttons --}}
    <div class="pull-left">
        {!! Form::submit($submitButtonText, array('class' => 'btn btn-primary')) !!}
        <a href="{{ url('votings') }}" class="btn btn-default">Άκυρο</a>
    </div>

    {{-- Create & delete voting item buttons --}}
    <div class="pull-right">
        <a href="#" id="addVotingItemButton" class="btn btn-default"><span class="glyphicon glyphicon-plus"></span> Προσθήκη αντικειμένου</a>
        <a href="#" id="remVotingItemButton" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span> Αφαίρεση</a>
    </div>
</div>
