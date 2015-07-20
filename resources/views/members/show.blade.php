@extends('app')

@section('content')
    <h1>Προβολή βουλευτή</h1>

    <ul class="nav nav-tabs">
      <li role="presentation" class="active"><a href="#info" data-toggle="tab">Πληροφορίες</a></li>
      <li role="presentation"><a href="#groups" data-toggle="tab">Κοιν. Ομάδες</a></li>
    </ul>

    <div class="tab-content">
        <div id="info" class="tab-pane fade in active">
            <div class="tab-content">
                <h2>{{ $member->first_name }} {{ $member->last_name }}</h2>

                <p>
                    Μέλος σε {{ $groups->count() }} κοιν. ομάδ{{ ($groups->count() == 1)?'α':'ες' }}.
                    Περιφέρεια: {{ $member->district->name }}
                </p>
            </div>
        </div>
        <div id="groups" class="tab-pane fade">
            @if(count($groups) > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Όνομα</th>
                                <th>Ενέργειες</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($groups as $group)
                                <tr>
                                    <td>
                                        {{ $group->name }}
                                    </td>
                                    <td>
                                        <!-- Info button -->
                                        <a href="/groups/{{ $group->id }}" class="btn btn-default btn-xs">
                                            <span class="glyphicon glyphicon-info-sign"></span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p>
                    Αυτός ο βουλευτής δεν συμμετέχει σε καμία κοινοβουλευτική ομάδα.
                </p>
            @endif
        </div>
    </div>
@stop