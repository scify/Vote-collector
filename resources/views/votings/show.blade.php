@extends('app')

@section('head')
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>

    <script type="text/javascript">
        google.load("visualization", "1", {packages:["corechart"]});
        google.setOnLoadCallback(drawChart);
            function drawChart() {

            // Gather data from table
            var answerTds = [];
            $('.table').find('tr').each(function(index, tr) {
                answerTds.push($(tr).children('td')[1]);
            });

            //todo: write comments
            var answerLabels = [];
            var answerCounts = [];
            $(answerTds).each(function(index, td) {
                var answerText = $(td).text().replace(/ /g,'');

                if (answerText.length > 0) {
                    var exists = false;
                    $(answerLabels).each(function(index, label) {
                        if (label == answerText) {
                            exists = true;
                        }
                    });

                    if (exists) {
                        var index = answerLabels.indexOf(answerText);
                        answerCounts[index] += 1;
                    } else {
                        answerLabels.push(answerText);
                        answerCounts.push(1);
                    }
                }
            });

            var mydata = [];
            mydata.push(['Απάντηση', 'Ψήφοι']);
            $(answerLabels).each(function(index, label) {
                mydata.push([label, answerCounts[index]]);
            });

            var data = google.visualization.arrayToDataTable(mydata);

            var options = {
                title: 'Ψήφοι'
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart'));

            chart.draw(data, options);
        }
    </script>

@stop

@section('content')
    <h1>Προβολή ψηφοφορίας</h1>

    <div class="jumbotron">
        <h1>{{ $voting->title }}</h1>

        <p>
            Τύπος: {{ $type->title }}
        </p>
        <p>
            Αντικείμενο: {{ $objective->title }}
        </p>
        @if(count($memberVotes) > 0)
            <p>
                <a class="btn btn-primary btn-lg" href="/votings/{{ $voting->id }}/download" role="button"><span class="glyphicon glyphicon-cloud-download"></span> Κατέβασμα αποτελεσμάτων</a>
            </p>
        @endif
    </div>

    <!-- google chart test -->
    <div id="piechart" style="width: 900px; height: 500px;"></div>

    {{-- Check if the voting has any votes, and show them --}}
    @if(count($memberVotes) > 0)
        <div class="panel panel-default">
            <div class="panel-heading">Αποτελέσματα</div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <th>Ονοματεπώνυμο</th>
                        <th>Ψήφος</th>
                    </thead>
                    <tbody>
                        @foreach($memberVotes as $mv)
                            <tr>
                                <td>
                                    {{ $mv['member'] }}
                                </td>
                                <td>
                                    {{ $mv['answer'] }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@stop