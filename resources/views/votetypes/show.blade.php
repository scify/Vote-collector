@extends('app')

@section('content')
    <h2>Vote type: {{ $vt->title }}</h2>

    @if (count($vt->answers) > 0)
        <h3>Answers:</h3>

        <div class="table-responsive">
            <table class="table table-condensed">
                @foreach($vt->answers as $answer)
                    <tr>
                        <td>
                            {{ $answer->answer }}
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    @else
        <p>
            This vote type has no answers!
        </p>
    @endif

@stop