<div class="form-group">
    {!! Form::label('title', 'Title') !!}
    {!! Form::text('title', Input::old('title'), array('class' => 'form-control')) !!}
</div>

<div class="form-group">
    <h4>Answers:</h4>

    <div id="answers">
        <input type="text" class="form-control" id="answer" name="answers[]" value="" />
    </div>

    <a href="#" id="addAnswer" class="btn btn-default"><span class="glyphicon glyphicon-plus"></span> Add answer</a>
</div>

{!! Form::submit($submitButtonText, array('class' => 'btn btn-primary')) !!}
<a href="/votetypes" class="btn btn-default">Cancel</a>