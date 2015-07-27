google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawChart);
function drawChart() {
    // Get <td>s from the table
    var answerTds = [];
    $('.table').find('tr').each(function(index, tr) {
        answerTds.push($(tr).children('td')[1]);        // Get only the td's from the second row of the table
    });

    var answerLabels = [];  // Array to keep answer text
    var answerCounts = [];  // Array to keep count of how many times each answer has been found
    $(answerTds).each(function(index, td) {
        var answerText = $(td).text().replace(/ /g,'');         // Get text from td and remove whitespace

        if (answerText.length > 0) {
            var exists = false;
            $(answerLabels).each(function(index, label) {       // Check if the answer has been found before
                if (label == answerText) {
                    exists = true;
                }
            });

            if (exists) {
                var index = answerLabels.indexOf(answerText);   // If it already exists, count one more
                answerCounts[index] += 1;
            } else {
                answerLabels.push(answerText);                  // Or add it with a count of 1
                answerCounts.push(1);
            }
        }
    });

    // Put all the data in the mydata array, in the format that Google Charts wants it
    var mydata = [];
    mydata.push(['Απάντηση', 'Ψήφοι']);
    $(answerLabels).each(function(index, label) {
        mydata.push([label, answerCounts[index]]);
    });

    var data = google.visualization.arrayToDataTable(mydata);   // Make it a data table for Google Charts

    var options = {
        title: 'Ψήφοι',
        is3D: true
    };

    var chart = new google.visualization.PieChart(document.getElementById('piechart'));

    chart.draw(data, options);
}