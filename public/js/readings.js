var reading = 1;        // Shows if this is the first, or second reading
var currentMember = 0;  // current member in the reading
var memberDivs;         // Keeps divs of members
var voting_id;

$(function(){
    memberDivs = $('.member');  // Get all member divs

    // The page just loaded so make the first member in the list the current one
    addCurrentStatus(memberDivs[0]);

    // Set the voting_id variable (needed for when the form is saved)
    voting_id = $('#votesform').data('votingid');

    // Add confirmation before leaving the page so no data is lost by a misclick
    //$(window).bind('beforeunload', function() {
    //    return 'Σίγουρα θέλετε να φύγετε από τη σελίδα;';
    //});

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
    //todo: an exei allaksei epilogh kai pathsei telos psifoforias h paei sto 2o reading de tha apothikeytei h allagh
    //todo: an kapoios psifisei kai apothikeytei to vote alla meta mpei absent, de diagrafetai to vote tou

    // Save current member's vote if they are not absent
    var member = memberDivs[currentMember];
    if (!isAbsent(member)) {
        console.log('saving the vote!');
        saveMember(member, true);
    } else {
        nextMember(false);
    }

    return false;
}

/**
 * Saves the current member's vote, and goes to the next member in the list
 *
 * @param markAsVoted   Set to true if you want to mark the current member as "voted" before going to the next one
 */
function nextMember(markAsVoted) {
    if (currentMember < memberDivs.length - 1) {
        if (markAsVoted) {
            $(memberDivs[currentMember]).data('saved', 'true'); // Mark member as voted
        }

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
    var member = memberDivs[currentMember];

    if (!isAbsent(member)) {
        if ($(member).data('saved') == true) {
            console.log('Gotta delete the vote of this member FAST!');

            //todo: delete the vote
        }

        makeAbsent(member);

        // Go to next member
        removeCurrentStatus(member);            // Remove current status from the current member

        if (currentMember < memberDivs.length - 1) {        // If this wasn't the last member in the list
            currentMember++;                                // go to the next member

            addCurrentStatus(memberDivs[currentMember]);    // and add current status to them
        } else {
            addCurrentStatus(member);           // Add current status to the last member again so the button updates and label hides
        }
    } else {
        removeCurrentStatus(member);
        makeNotAbsent(member);
        addCurrentStatus(member);
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
    $(member).prepend(absentLabel());
}

/**
 * Makes a member div NOT absent
 *
 * @param member
 */
function makeNotAbsent(member) {
    $(member).removeClass('text-muted');
    $(member).find('.absentLabel').remove();
}

/**
 * Does the actions needed to end the voting completely
 * (to save the votes, and submit them to the server)
 */
function endVoting() {
    var votes = [];                         // Reset votes array
    saveVotes(memberDivs, votes, false);    // Saves votes from remaining members to the array
    submitVotes(votes, false);              // Submit the votes
    votingComplete(true);                   // Complete the voting
}

/**
 * Switches from the first to the second reading
 */
function startSecondReading() {
    removeCurrentStatus(memberDivs[currentMember]);     // Remove current status from current member

    // Save the votes of members who voted
    var membersToSave = [];
    var votes = [];
    $('.member').each(function(index, div) {
        if (!isAbsent(div) && $(div).data('saved') == false) {
            membersToSave.push(div);
        }
    });
    saveVotes(membersToSave, votes, false);
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
 * @param updating      Set true if updating members. Then it will ignore the data-saved attribute.
 */
function saveVotes(memberDivs, votes, updating) {
    $(memberDivs).each(function(index, member) {
        var updatingCheck = true;
        if (!updating) {
            updatingCheck = ($(member).data('saved') == false);
        }

        if (!isAbsent(member) && updatingCheck) {
            var id = $(member).data('id');   // Get member id

            var vote = {
                member_id: id,
                answer_id: $(member).find('input[type="radio"][name="answer_' + id + '"]:checked').val()
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
    saveVotes([memberDiv], tmpvote, true);
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
    // If there are no votes, do nothing
    if (votes.length == 0) {
        return;
    }

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
                nextMember(true);
            }
        },
        error: function(data) {
            // Show error
            // todo: show error to user
            /*
             * (Possibly check if the success message from the end of the voting is shown and remove it
             * and then prepend an error)
             */
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

    $('.container').prepend(alertDiv);
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