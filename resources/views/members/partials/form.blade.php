<!-- Name -->
<div class="form-group">
    {!! Form::label('first_name', 'Όνομα') !!}
    {!! Form::text('first_name', Input::old('first_name'), array('class' => 'form-control')) !!}
</div>

<!-- Surname -->
<div class="form-group">
    {!! Form::label('last_name', 'Επώνυμο') !!}
    {!! Form::text('last_name', Input::old('last_name'), array('class' => 'form-control')) !!}
</div>

<!-- Group selection -->
<div class="form-group">
    {!! Form::label('group_list', 'Κοινοβουλευτικές Ομάδες') !!}
    {!! Form::select('group_list[]', $groups, null, ['class' => 'form-control', 'multiple']) !!}
</div>

<!-- District selection -->
<div class="form-group">
    {!! Form::label('district', 'Περιφέρεια') !!}
    {{-- If user is editing a member, the select must be disabled (perifereia does not change) --}}
    @if(isset($edit))
        {!! Form::select('district', $districts, $member->district->id, ['class' => 'form-control', 'disabled']) !!}
    @else
        {!! Form::select('district', $districts, null, ['class' => 'form-control']) !!}
    @endif
</div>


{!! Form::submit($submitButtonText, array('class' => 'btn btn-primary')) !!}
<a href="/members" class="btn btn-default">Άκυρο</a>