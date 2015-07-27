var reading = 1;        // Shows if this is the first, or second reading
var currentMember = 0;  // current member in the reading
var memberDivs;         // Keeps divs of members
var votes = [];         // Keeps member's votes
var voting_id;
var success;            // Used for checking whether a save operation was successful todo: does it have to be a global variable?

$(function(){
    memberDivs = $('.member');  // Get all member divs

    // The page just loaded so make the first member in the list the current one
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
 * Goes to the next member in the list
 *
 * @return {boolean}    To prevent page from scrolling to the top
 */
function nextButtonHandler() {
    //todo: if member is absent, do not save vote
    //todo: if member has voted already, change the vote to the new one

    // Save current member's vote
    console.log('saving the vote!');
    saveMember(memberDivs[currentMember], true);

    return false;
}

/**
 * Saves the current member's vote, and goes to the next member in the list
 */
function nextMember() {
    if (currentMember < memberDivs.length - 1) {
        // Mark member as voted
        $(memberDivs[currentMember]).data('saved', 'true');

        // Go to next member
        console.log("Going to next member");
        removeCurrentStatus(memberDivs[currentMember]);
        currentMember++;
        addCurrentStatus(memberDivs[currentMember]);
    }
}

/**
 * Marks a member as absent and goes to the next member in the list
 *
 * @return {boolean}    To prevent the page from scrolling to the top when a button is clicked
 */
function absentButtonHandler() {
    //todo: (not absent) -> (absent): if member has voted, delete his vote
    //todo: (absent) -> (not absent): save the member's vote because it means they voted (CONFIRM??)

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

    $('#absentBtn').click(absentButtonHandler);     // Event listener for the absent button

    // Remove the next button if it's the last member in the list, or add event listener to it if it's not
    if ($(memberDivs).index(member) == memberDivs.length - 1) {
        $(member).find('#nextBtn').remove();
    } else {
        $('#nextBtn').click(nextButtonHandler);
    }

    // Apply css class
    $(member).find('.memberName').addClass('currentMember');

    // Unhide radio buttons
    $(member).find('.radios').removeClass('hidden');

    // If member is marked as absent, hide the absent label temporarily
    $(member).find('.absentLabel').addClass('hidden');

    // Add background class
    $(member).addClass('currentMemberDiv');
}

/**
 * Removes the current status from a member
 *
 * @param member    The form-control div of the member
 */
function removeCurrentStatus(member) {
    // Check if member has the absent button as a child and remove it
    $(member).children('#sideButtons').remove();

    // Remove applied css class
    $(member).find('.memberName').removeClass('currentMember');

    // Hide buttons with css
    $(member).find('.radios').addClass('hidden');

    // If member is marked as absent, unhide the absent label
    $(member).find('.absentLabel').removeClass('hidden');

    // Remove background class
    $(member).removeClass('currentMemberDiv');
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
 * Does the actions needed to end the voting completely
 * (to save the votes, and submit them to the server)
 */
function endVoting() {
    votes = [];                     // Reset votes array
    saveVotes(memberDivs, votes);   // Saves votes from remaining members to the array
    console.log(votes);
    submitVotes(votes, false);      // Submit the votes
    votingComplete(true);           // Complete the voting
}

/**
 * Switches from the first to the second reading
 */
function startSecondReading() {
    removeCurrentStatus(memberDivs[currentMember]);     // Remove current status from current member

    // Save the votes of members who voted
    var membersToSave = [];
    $('.member').each(function(index, div) {
        if (!isAbsent(div) && $(div).data('saved') == false) {
            membersToSave.push(div);
        }
    });
    saveVotes(membersToSave, votes);
    submitVotes(votes, false);

    // Remove members that are not absent and members that have been saved from the form
    $('.member').each(function(index, div) {
        if (!isAbsent(div) || $(div).data('saved') == true) {
            div.remove();
        }
    });

    memberDivs = $('.member');          // Update memberDivs

    // If all members voted, no need for second reading
    if (memberDivs.length == 0) {
        votingComplete(true);
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
 * Saves the votes of members who voted to the votes array and removes them from the form
 *
 * @param memberDivs    The divs of the members
 * @param votes         The array to save the votes to
 */
function saveVotes(memberDivs, votes) {
    $(memberDivs).each(function(index, memberDiv) {
        if ($(memberDiv).data('status') == 'voted' && $(memberDiv).data('saved') == false) {
            var id = $(memberDiv).data('id');   // Get member id

            var vote = {
                member_id: id,
                answer_id: $(memberDiv).find('input[type="radio"][name="answer_' + id + '"]:checked').val()
            };

            votes.push(vote);       // Add member's vote to votes array
        }
    });
}

/**
 * Saves the given member to the database using the existing saveVotes() and submitVotes() functions
 *
 * @param memberDiv     Div of member with vote to save
 * @param goToNext      Set to true if you want to go to next member after saving this one
 */
function saveMember(memberDiv, goToNext) {
    var tmpvote = [];
    saveVotes([memberDiv], tmpvote);
    submitVotes(tmpvote, goToNext);
}

/**
 * Submits the votes to the server which will save
 * them to the database
 *
 * @param votes     Array with the votes to save
 * @param goToNext  Set to true if you want to go to the next member after saving or false if you want to do nothing
 */
function submitVotes(votes, goToNext) {
    var oldCurrMember = currentMember;

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
            if (goToNext) {
                nextMember();
            }
        },
        error: function(data) {
            // Show error
            // todo: show error to user
            console.log("Error saving! Switched to member with index " + currentMember);
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

/**
 * Creates and returns the absent member button
 * along with a next member button
 *
 * @returns {string}
 */
function getAbsentButton() {
    return  '<div id="sideButtons" class="btn-group">' +
                getNextButton() +
                '<a id="absentBtn" class="btn btn-warning" href="#"><span class="glyphicon glyphicon-question-sign"></span> Απουσιάζει</a>' +
            '</div>';
}

/**
 * Creates and returns the NOT absent member button
 * along with a next member button
 *
 * @returns {string}
 */
function getNotAbsentButton() {
    return  '<div id="sideButtons" class="btn-group">' +
                getNextButton() +
                '<a id="absentBtn" class="btn btn-success" href="#"><span class="glyphicon glyphicon-ok-sign"></span> Δεν απουσιάζει</a>' +
            '</div>';
}

/**
 * Creates and returns a next button
 *
 * @returns {string}
 */
function getNextButton() {
    return '<a id="nextBtn" class="btn btn-primary" href="#"><span class="glyphicon glyphicon-chevron-down"></span> Επόμενος</a>'
}

/**
 * Creates and returns the absent member label
 *
 * @returns {string}
 */
function absentLabel() {
    return '<span class="label label-default absentLabel "><span class="glyphicon glyphicon-question-sign"></span> Απουσιάζει</span>';
}