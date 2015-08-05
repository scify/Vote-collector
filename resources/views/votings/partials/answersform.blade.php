{{-- Hidden voting id --}}
{!! Form::hidden('voting_id', $voting->id) !!}

{{-- Hidden voting item ids (needed to not make a mistake if something changes before the form is submitted) --}}
@foreach($votingItems as $vItem)
    {!! Form::hidden('votingItems[]', $vItem->id) !!}
@endforeach

{{-- Table with default answer select inputs --}}
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Κοιν. Ομάδα</th>
                @foreach($votingItems as $vItem)
                    <th>{{ $vItem->voteObjective->title }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($groups as $group)
                <tr>
                    <td>
                        <strong>{{ $group->name }}</strong>
                    </td>
                    @foreach($votingItems as $vItem)
                        <td>
                            {!! Form::select('answer_' . $group->id . '[]', $vItem->voteType->answers->lists('answer', 'id'), isset($edit)?$group->defaultAnswer($voting->id, $vItem->id):null, ['class' => 'selectpicker']) !!}
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{!! Form::submit('Αποθήκευση', ['class' => 'btn btn-primary']) !!}
<a href="{{ url('votings') }}" class="btn btn-default">Άκυρο</a>