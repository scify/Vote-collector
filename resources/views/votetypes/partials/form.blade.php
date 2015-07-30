<div class="form-group">
    {!! Form::label('title', 'Τίτλος') !!}
    {!! Form::text('title', Input::old('title'), array('class' => 'form-control')) !!}
</div>

<h4>Απαντήσεις:</h4>

<div id="answers">
    <div class="form-group">
        <input type="text" class="form-control" id="answer" name="answers[]" value="{{ $answers[0]['answer'] or '' }}" />
    </div>

    <!-- If $answers is set, this is an edit form, so if there is more than one answer, show all of them in extra fields  -->
    @if (isset($answers) && count($answers) > 1)
        @for($i = 1; $i < count($answers); $i++)
            <div class="form-group">
                <div class="input-group">
                    <input name="answers[]" id="answer" class="form-control" type="text" value="{{ $answers[$i]['answer'] }}">
                    <span class="input-group-btn">
                        <a class="btn btn-danger" id="removeBtn" href="#">
                            <span class="glyphicon glyphicon-remove"></span>
                        </a>
                    </span>
                </div>
            </div>
        @endfor
    @endif
</div>

<a href="#" id="addAnswer" class="btn btn-default pull-right"><span class="glyphicon glyphicon-plus"></span> Προσθήκη απάντησης</a>

{!! Form::submit($submitButtonText, array('class' => 'btn btn-primary')) !!}
<a href="{{ url('votetypes') }}" class="btn btn-default">Άκυρο</a>