<div class="form-group">
    {!! Form::label('title', 'Title') !!}
    {!! Form::text('title', Input::old('title'), array('class' => 'form-control')) !!}
</div>

<div class="form-group">
    <h4>Answers:</h4>

    <div id="answers">
        <input type="text" class="form-control" id="answer" name="answers[]" value="{{ $answers[0]['answer'] or '' }}" />

        <!-- If $answers is set, this is an edit form, so if there is more than one answer, show all of them in extra fields  -->
        @if (isset($answers) && count($answers) > 1)
            @for($i = 1; $i < count($answers); $i++)
                <div class="input-group">
                    <input name="answers[]" id="answer" class="form-control" type="text" value="{{ $answers[$i]['answer'] }}">
                    <span class="input-group-btn">
                        <a class="btn btn-danger" id="removeBtn" href="#">
                            <span class="glyphicon glyphicon-remove"></span>
                        </a>
                    </span>
                </div>
            @endfor
        @endif
    </div>

    <a href="#" id="addAnswer" class="btn btn-default"><span class="glyphicon glyphicon-plus"></span> Add answer</a>
</div>

{!! Form::submit($submitButtonText, array('class' => 'btn btn-primary')) !!}
<a href="/votetypes" class="btn btn-default">Cancel</a>