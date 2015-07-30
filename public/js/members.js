var orderChanged = false;

$(document).ready(function() {
    // Setup CSRF token for middleware
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Helper function to keep table row from collapsing when being sorted
    var fixHelperModified = function(e, tr) {
        var $originals = tr.children();
        var $helper = tr.clone();
        $helper.children().each(function(index) {
            $(this).width($originals.eq(index).width())
        });

        return $helper;
    };

    // Make members table sortable
    $("#members_list tbody").sortable({
        helper: fixHelperModified,
        stop: function(event, ui) { renumber_table("#members_list") }
    }).disableSelection();
});

/**
 * Renumber table rows
 *
 * @param tableID   The id of the table to renumber rows for
 */
function renumber_table(tableID) {
    // Check if it's the first time that the order changes, to show the save button
    if (!orderChanged) {
        showSaveOrderButton();

        orderChanged = true;
    }

    // Renumber rows
    $(tableID + " tr").each(function() {
        var count = $(this).parent().children().index($(this)) + 1;
        $(this).find(".priority").html(count);
    });
}

/**
 * Adds the save order button to the page
 */
function showSaveOrderButton() {
    // Create the button
    var btn = document.createElement('button');
    btn.innerHTML = '<span class="glyphicon glyphicon-floppy-disk"></span> Αποθήκευση σειράς';
    btn.className = 'btn btn-primary pull-right';
    btn.id = 'saveOrderBtn';
    btn.addEventListener('click', saveOrder);

    // Add button to page
    document.getElementsByClassName('container')[0].appendChild(btn);
}

/**
 * Save the current order of members to the database
 */
function saveOrder() {
    var members = collectMembersByOrder();

    // Send the ajax request to server
    $.ajax({
        url: submitOrderUrl,
        type: 'POST',
        data: { data: members },
        dataType: 'json',
        success: function(data) {
            // If success, remove button from page
            orderChanged = false;
            $('#saveOrderBtn').remove();
        },
        error: function(data) {
            // If fail, show error
            $('#orderAlert').remove();
            $('.container').prepend('<div class="alert alert-danger" id="orderAlert">' +
                                        '<strong>Σφάλμα!</strong> Δεν ήταν δυνατό να αποθηκευτεί η σειρά των βουλευτών!' +
                                    '</div>');
        }
    });
}

/**
 * Collects each member's id and new order and returns an array of them
 *
 * @returns {Array} Order of members
 */
function collectMembersByOrder() {
    var newOrdering = [];

    $(".member").each(function(index,member){
        var order = {
            order: $($(member).children('.priority')[0]).html(),
            id: $(member).data("id")
        };

        newOrdering.push(order);
    });

    return newOrdering;
}