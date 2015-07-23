var reading = 1;        // Shows if this is the first, or second reading
var currentMember = 0;  // current member in the reading
var memberDivs;         // Keeps divs of members
var votes = [];         // Keeps member's votes
var voting_id;

$(function(){
    memberDivs = $('.member');  // Get all member divs

    $(memberDivs).each(function(index, div) {
        removeCurrentStatus(div);
    });

    // The page just loaded so show the save/absent buttons next to the first member
    addCurrentStatus(memberDivs[0]);

    // Set the voting_id variable (needed for when the form is saved)
    voting_id = $('#votesform').data('votingid');

    // Add confirmation before leaving the page so no data is lost by a misclick
    $(window).bind('beforeunload', function() {
        return 'Σίγουρα θέλετε να φύγετε από τη σελίδα;';
    });

    $('body').click(clickHandler);                  // Used to change between members by clicking on their names

    $('#nextPhaseBtn').click(nextPhaseBtnHandler);   // Second voting/end voting button
});

/**
 * Checks if the user clicked a member name, and makes that
 * member the current member
 *
 * @param e
 */
function clickHandler(e) {
    var target = e.target;

    if (target.nodeName == 'SPAN') {
        if ($(target).is('.memberName') && !$(target).is('.currentMember')) {
            // Remove current status from old div
            removeCurrentStatus(memberDivs[currentMember]);

            // Get new div and change currentMember variable
            var memberDiv = $(target).parent();
            currentMember = $(memberDivs).index(memberDiv);

            // Add current status to new div
            addCurrentStatus(memberDiv);
        }
    }
}

/**
 * Handles clicks of the next phase button.
 * If it's the first reading, it switches to the second reading.
 * If it's the second reading, it ends the voting
 */
function nextPhaseBtnHandler() {
    if (reading == 1) {
        startSecondReading();
    } else {
        endVoting();
    }
}

/**
 * Adds current status to a member (currently shown by the buttons next to them only)
 *
 * @param member    The form-control div of the member
 */
function addCurrentStatus(member) {
    // Put the appropriate button next to the member
    if (isAbsent(member)) {
        $(member).append(getNotAbsentButton());
    } else {
        $(member).append(getAbsentButton());
    }

    $('#absentBtn').click(nextMember);      // Event listener for the button

    // Apply css class
    $(member).find('.memberName').addClass('currentMember');

    // Unhide radio buttons
    $(member).find('.radios').removeClass('hidden');

    // If member is marked as absent, hide the absent label temporarily
    $(member).find('.absentLabel').addClass('hidden');
}

/**
 * Removes the current status from a member
 *
 * @param member    The form-control div of the member
 */
function removeCurrentStatus(member) {
    // Check if member has the absent button as a child and remove it
    $(member).children('#absentBtn').remove();

    // Remove applied css class
    $(member).find('.memberName').removeClass('currentMember');

    // Hide buttons with css
    $(member).find('.radios').addClass('hidden');

    // If member is marked as absent, unhide the absent label
    $(member).find('.absentLabel').removeClass('hidden');
}

/**
 * Creates and returns the absent member button
 *
 * @returns {string}
 */
function getAbsentButton() {
    return '<a id="absentBtn" class="btn btn-warning" href="#"><span class="glyphicon glyphicon-question-sign"></span> Απουσιάζει</a>';
}

/**
 * Creates and returns the NOT absent member button
 *
 * @returns {string}
 */
function getNotAbsentButton() {
    return '<a id="absentBtn" class="btn btn-success" href="#"><span class="glyphicon glyphicon-ok-sign"></span> Δεν απουσιάζει</a>';
}

/**
 * Creates and returns the absent member label
 *
 * @returns {string}
 */
function absentLabel() {
    return '<span class="label label-default absentLabel "><span class="glyphicon glyphicon-question-sign"></span> Απουσιάζει</span>';
}

/**
 * Checks if a member is marked as absent (using the text-muted class)
 *
 * @param member        The div of the member to check
 * @returns {boolean}
 */
function isAbsent(member) {
    return $(member).hasClass('text-muted');
}

/**
 * Makes a member div absent
 *
 * @param member
 */
function makeAbsent(member) {
    $(member).addClass('text-muted');
    $(member).data('status', 'not_voted');
    $(member).prepend(absentLabel());
}

/**
 * Makes a member div NOT absent
 *
 * @param member
 */
function makeNotAbsent(member) {
    $(member).removeClass('text-muted');
    $(member).data('status', 'voted');
    $(member).find('.absentLabel').remove();
}

/**
 * Marks a member as absent and goes to the next member in the list
 *
 * @return boolean  To prevent the page from scrolling to the top when a button is clicked
 */
function nextMember() {
    var member = memberDivs[currentMember];

    if (!isAbsent(member)) {
        makeAbsent(member);
    } else {
        makeNotAbsent(member);
    }

    removeCurrentStatus(member);            // Remove current status from the current member

    if (currentMember < memberDivs.length - 1) {        // If this wasn't the last member in the list
        currentMember++;                                // go to the next member

        addCurrentStatus(memberDivs[currentMember]);    // and add current status to them
    } else {
        addCurrentStatus(member);           // Add current status to the last member again so the button updates and label hides
    }

    return false;
}

/**
 * Calls the functions needed to end the voting
 * (to save the votes, and submit them to the server)
 */
function endVoting() {
    saveVotes(memberDivs, votes);

    submitVotes(votes);
}

/**
 * Switches from the first to the second reading
 */
function startSecondReading() {
    removeCurrentStatus(memberDivs[currentMember]);     // Remove current status from current member

    saveVotes(memberDivs, votes);   // Save the votes of members who voted
    memberDivs = $('.member');      // Update memberDivs

    // If all members voted, no need for second reading
    if (memberDivs.length == 0) {
        submitVotes(votes);
    } else {
        // Make all remaining members not absent
        $(memberDivs).each(function(index, div) {
            makeNotAbsent(div);
        });

        reading = 2;                                    // Set reading variable
        currentMember = 0;                              // Current member is the first one again
        $('#title').text('Δεύτερη ανάγνωση');           // Change title
        addCurrentStatus(memberDivs[currentMember]);    // Add curr. status to current member

        $('#nextPhaseBtn').text('Τέλος ψηφοφορίας');    // Change the next phase button to say "end voting"
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
        if ($(memberDiv).data('status') == 'voted') {
            var id = $(memberDiv).data('id');   // Get member id

            var vote = {
                member_id: id,
                answer_id: $(memberDiv).find('input[type="radio"][name="answer_' + id + '"]:checked').val()
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

    // Remove the next phase button
    $('#nextPhaseBtn').remove();

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