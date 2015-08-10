@extends('app')

@section('content')
    <h2>Τύπος απάντησης: {{ $vt->title }}</h2>

    @if (count($vt->answers) > 0)
        <div class="panel panel-default">
            <div class="panel-heading">Απαντήσεις</div>

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
        </div>
    @else
        <p>
            Δεν υπάρχουν απαντήσεις!
        </p>
    @endif
@stop