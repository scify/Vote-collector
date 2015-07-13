@extends('app')

@section('content')
    <h2>Τύπος ψηφοφορίας: {{ $vt->title }}</h2>

    @if (count($vt->answers) > 0)
        <h3>Απαντήσεις:</h3>

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
            Αυτός ο τύπος ψηφοφορίας δεν έχει απαντήσεις!
        </p>
    @endif
@stop