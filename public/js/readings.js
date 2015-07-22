var reading = 1;        // Shows if this is the first, or second reading
var currentMember = 0;  // current member in the reading
var memberDivs;         // Keeps divs of members
var votes = [];         // Keeps member's votes
var voting_id;

$(function(){
    memberDivs = $('.member');  // Get all member divs

    // The page just loaded so show the save/absent buttons next to the first member
    setCurrentMember(0);

    // Set the voting_id variable (needed for when the form is saved)
    voting_id = $('#votesform').data('votingid');

    // Add confirmation before leaving the page so no data is lost by a misclick
    /*$(window).bind('beforeunload', function() {
        return 'Σίγουρα θέλετε να φύγετε από τη σελίδα;';
    });*/   //todo: uncomment this
});

/**
 * Sets the member with the specified index as the current one in the reading,
 * which means their name becomes bigger to show they are the current member
 * and shows buttons next to them
 *
 * @param index
 */
function setCurrentMember(index) {
    // Remove current status from all other members
    $(memberDivs).each(function(index, member) {
        removeCurrentStatus(member);
    });

    // Add current status to the specified member
    addCurrentStatus(memberDivs[index]);
}

/**
 * Adds current status to a member (currently shown by the buttons next to them only)
 *
 * @param member    The form-control div of the member
 */
function addCurrentStatus(member) {
    // Put the buttons next to the member
    $(member).append(getMemberButtons());

    // Make name blue
    $($(member).find('.control-label')[0]).addClass('text-primary');
    $($(member).find('.control-label')[0]).addClass('currentMember');

    // Add event listeners to the buttons
    $('#prevBtn').click(prevMember);
    $('#absentBtn').click({btn: 'absent'}, nextMember);
    $('#nextBtn').click({btn: 'next'}, nextMember);
}

/**
 * Removes the current status from a member
 *
 * @param member    The form-control div of the member
 */
function removeCurrentStatus(member) {
    // Check if member has the buttons div as a child
    if ($(member).children('#currentMemberButtons').length > 0) {
        // And remove it
        $(member).children('#currentMemberButtons').each(function(index, btns) {
            btns.remove();
        });
    }

    // Make name not blue
    $($(member).find('.control-label')[0]).removeClass('text-primary');
    $($(member).find('.control-label')[0]).removeClass('currentMember');
}

/**
 * Creates and returns the "next member" & "absent" buttons
 * used to go to the next member or mark them as absent
 * for this reading
 *
 * @returns string
 */
function getMemberButtons() {
    var buttons =   '<div id="currentMemberButtons" class="btn-group">' +
                        '<a id="prevBtn" class="btn btn-default" href="#"><span class="glyphicon glyphicon-chevron-up"></span> Πίσω</a>' +
                        '<a id="absentBtn" class="btn btn-default" href="#"><span class="glyphicon glyphicon-question-sign"></span> Απουσιάζει</a>' +
                        '<a id="nextBtn" class="btn btn-primary" href="#"><span class="glyphicon glyphicon-chevron-down"></span> Επόμενος</a>' +
                    '</div>';

    return buttons;
}

/**
 * Goes to the previous member in the list
 *
 * @return boolean  To prevent the page from scrolling to the top when a button is clicked
 */
function prevMember() {
    // Check if it is the first member or not
    if (currentMember > 0) {
        removeCurrentStatus(memberDivs[currentMember]);

        // Go to previous member
        currentMember--;
        addCurrentStatus(memberDivs[currentMember]);
    }

    return false;
}

/**
 * Goes to next member in the list.
 *
 * @return boolean  To prevent the page from scrolling to the top when a button is clicked
 */
function nextMember(event) {
    // If the next button was pressed, the member voted so change the status attribute
    if (event.data.btn == 'next') {
        $(memberDivs[currentMember]).data('status', 'voted');
        $(memberDivs[currentMember]).removeClass('text-muted');
    } else {
        $(memberDivs[currentMember]).addClass('text-muted');
        $(memberDivs[currentMember]).data('status', 'not_voted');
    }

    removeCurrentStatus(memberDivs[currentMember]);     // Remove current status from the current member

    if (currentMember < memberDivs.length - 1) {        // If this wasn't the last member in the list
        currentMember++;                                // go to the next member

        addCurrentStatus(memberDivs[currentMember]);    // and add current status to them
    } else {
        // Check if we should switch to second reading or the voting ended
        if (reading == 1) {
            startSecondReading();           // Switch to second reading
        } else {
            saveVotes(memberDivs, votes);   // Voting ended, save the votes

            submitVotes(votes);             // And submit them to the server
        }
    }

    return false;
}

/**
 * Switches from the first to the second reading
 */
function startSecondReading() {
    saveVotes(memberDivs, votes);           // Save the votes of members who voted
    memberDivs = $('.member');              // Update memberDivs

    // If all members voted, no need for second reading
    if (memberDivs.length == 0) {
        submitVotes(votes);
    } else {
        reading = 2;                                    // Set reading variable
        currentMember = 0;                              // Current member is the first one again
        $('#title').text('Δεύτερη ανάγνωση');           // Change title
        addCurrentStatus(memberDivs[currentMember]);    // Add curr. status to current member

        // Remove muted text from remaining members
        $(memberDivs).each(function(index, div) {
            $(div).removeClass('text-muted');
        });
    }
}

/**
 * Saves the votes of members who voted and removes them from the form
 *
 * @param memberDivs    The divs of the members
 * @param votes         The array to save the votes to
 */
function saveVotes(memberDivs, votes) {
    $(memberDivs).each(function(index, memberDiv) {
        if ($(memberDiv).data('status') == 'voted') {   // Member voted
            var vote = {
                member_id: $(memberDiv).data('id'),
                answer_id: $(memberDiv).find('.selectpicker')[0].value
            };

            votes.push(vote);       // Add member's vote to votes array

            $(memberDiv).remove();  // Remove member's form field
        }
    });
}

/**
 * Submits the votes to the server which will save
 * them to the database
 */
function submitVotes(votes) {
    console.log("submit to server");
    // Setup CSRF token for middleware
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Send ajax request to server
    $.ajax({
        url: '/votings/reading',
        type: 'POST',
        data: {
            data: votes,
            voting: voting_id
        },
        dataType: 'json',
        success: function(data) {
            votingComplete(true);
        },
        error: function(data) {
            votingComplete(false);
        }
    });
}

/**
 * Changes the page to show a success or error message
 *
 * @param success
 */
function votingComplete(success) {
    $('#title').remove();   // Remove title

    $(window).off('beforeunload');

    // Remove any fields remaining
    $(memberDivs).each(function(index, div) {
        $(div).remove();
    });

    var alertDiv;

    if (success) {
        alertDiv =  '<div class="alert alert-success">' +
                        '<strong>Η ψηφοφορία ολοκληρώθηκε με επιτυχία!</strong>' +
                        (reading == 1 ? ' Όλοι οι βουλευτές ψήφισαν στην πρώτη ανάγνωση.' : '') +
                        ' Δείτε τα αποτελέσματα <a href="/votings/' + voting_id + '" class="alert-link">εδώ</a>.' +
                    '</div>';
    } else {
        alertDiv =  '<div class="alert alert-danger">' +
                        '<strong>Σφάλμα!</strong> Δεν ήταν δυνατό να αποθηκευτούν οι ψήφοι.' +
                    '</div>';
    }

    $($('.container')[0]).prepend(alertDiv);
}