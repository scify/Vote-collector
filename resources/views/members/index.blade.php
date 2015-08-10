@extends('app')

@section('content')
    {{-- Title and export to json button --}}
    <h1>Βουλευτές<a href="{{ url('membersexport') }}" class="btn btn-default pull-right"><span class="glyphicon glyphicon-export"></span> Εξαγωγή</a></h1>

    {{-- Panels for each district --}}
    @foreach($districts as $district)
        @if($district->members->count() > 0)
            <div class="panel panel-default">
                <div class="panel-heading">{{ $district->name }}</div>

                <div class="table-responsive">
                    <table id="members_list" class="table table-condensed table-striped">
                        <thead>
                            <tr>
                                <th>Σειρά</th>
                                <th>Όνομα</th>
                                <th>Επώνυμο</th>
                                <th colspan="3">Ενέργειες</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($district->members()->orderBy('order')->get() as $member)
                                <tr class="member" data-id="{{ $member->id }}">
                                    <td class="priority col-sm-1">
                                        {{ $member->order }}
                                    </td>
                                    <td class="col-sm-3">
                                        {{ $member->first_name }}
                                    </td>
                                    <td class="col-sm-3">
                                        {{ $member->last_name }}
                                    </td>
                                    <td class="col-sm-1"> {{-- Info button --}}
                                        @include('partials.infoBtn', ['url' => 'members/' . $member->id])
                                    </td>
                                    <td class="col-sm-1"> {{-- Edit button --}}
                                        @include('partials.editBtn', ['url' => 'members/' . $member->id . '/edit'])
                                    </td>
                                    <td class="col-sm-2"> {{-- Delete button --}}
                                        @include('partials.deleteBtn', ['url' => 'members', 'id' => $member->id])
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @endforeach

    <a href="members/create" class="btn btn-default"><span class="glyphicon glyphicon-plus"></span> Νέος βουλευτής</a>
@stop

@section('footer')
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <script>
        var submitOrderUrl = '{{ url('membersorder') }}';    {{-- Url for submitting votes, using url() --}}
    </script>
    <script src="{{ URL::asset('js/members.js') }}"></script>
@stop