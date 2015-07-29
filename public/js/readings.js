var reading = 1;        // Shows if this is the first, or second reading
var currentMember = 0;  // current member in the reading
var memberDivs;         // Keeps divs of members
var savedVotes = {};    // Keeps the votes of the saved members, to check for changes
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

    // Setup CSRF token for middleware
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('body').click(clickHandler);                  // Used to change between members by clicking on their names

    $('#nextPhaseBtn').click(nextPhaseBtnHandler);  // Second voting/end voting button
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
            // Save old member's vote
            var currMember = memberDivs[currentMember];
            if (!isAbsent(currMember)) {
                var m_id = $(currMember).data('id');
                if (!isSaved(currMember) || (getSelectedAnswer(currMember) != savedVotes[m_id])) {
                    saveMember(currMember, false);
                }
            }

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
    // Confirm the action before doing anything
    var msg = 'Σίγουρα θέλετε να ολοκληρώσετε την ψηφοφορία;';
    if (reading == 1) {
        msg = 'Σίγουρα θέλετε να προχωρήσετε στη 2η ανάγνωση;';
    }

    if (confirm(msg)) {
        // Check if any saved member's answer is different (so it needs updating)
        var changedMembers = [];
        $(memberDivs).each(function (index, member) {
            if (isSaved(member)) {
                var memberId = $(member).data('id');        // Get id
                var answerId = getSelectedAnswer(member);   // Get selected answer

                if (savedVotes[memberId] != answerId) {     // If selected answer is different from saved one, add it to the array
                    changedMembers.push(member);
                }
            }
        });

        // Update any members that have selected different answers from the saved ones
        if (changedMembers.length > 0) {
            var votes = getVotes(changedMembers, true);
            submitVotes(votes);
        }

        // Go to next phase of voting
        if (reading == 1) {
            startSecondReading();
        } else {
            endVoting();
        }
    }

    return false;
}

/**
 * Goes to the next member in the list
 *
 * @return {boolean}    To prevent page from scrolling to the top
 */
function nextButtonHandler() {
    // Save current member's vote if they are not absent
    var member = memberDivs[currentMember];
    if (!isAbsent(member)) {
        saveMember(member, true);
    } else {
        nextMember();
    }

    return false;
}

/**
 * Goes to the next member in the list
 */
function nextMember() {
    if (currentMember < memberDivs.length - 1) {
        // Go to next member
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
        if (isSaved(member)) {
            $(member).data('saved', 'false');   // Mark member as not saved
            deleteVote(member);
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
 * Deletes the vote of the given member from the database
 *
 * @param member    The member to delete the vote of
 */
function deleteVote(member) {
    var m_id = $(member).data('id');    // member id

    // Send ajax request to server
    $.ajax({
        url: '/votings/reading/dv',
        type: 'POST',
        data: {
            m_id: m_id,
            v_id: voting_id
        },
        dataType: 'json',
        error: function(data) {
            // Show error
            $('#memberDeleteFailAlert').remove();
            var msg = '<strong>Σφάλμα!</strong> Δεν ήταν δυνατό να διαγραφεί η απάντηση του/ης βουλευτή αφού επισημάνθηκε ως απών!';
            $('.container').prepend(getAlertDiv(false, 'memberDeleteFailAlert', msg));
        }
    });
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

    // Hide the selected answer label
    $('#selAnswerLabel' + memberId(member)).text('');

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
    $('#sideButtons').remove();

    // Remove applied css class
    $(member).find('.memberName').removeClass('currentMember');

    // Hide buttons with css
    $(member).find('.radios').addClass('hidden');

    // Show the selected answer label
    if (!isAbsent(member)) {
        var answerText = $(member).find('label[for=rd' + memberId(member) + '' + getSelectedAnswer(member) + ']').text();
        $('#selAnswerLabel' + memberId(member)).text(answerText);
    }

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
 * Returns the id of a member using the data-id property
 *
 * @param member        The div of the member
 * @returns {*|jQuery}  The id of the given member
 */
function memberId(member) {
    return $(member).data('id');
}

/**
 * Checks if a member is marked as saved (using the data-saved attribute)
 *
 * @param member        Member to check
 * @returns {boolean}
 */
function isSaved(member) {
    return ( $(member).data('saved') == 'true' );
}

/**
 * Makes a member div absent
 *
 * @param member
 */
function makeAbsent(member) {
    $(member).addClass('text-muted');
    $(member).append(absentLabel());
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
    // Save votes of not absent members
    var votes = getVotes(memberDivs, false);
    submitVotes(votes, false);                  // Submit the votes

    // Save votes of absent members
    votes = getAbsentMemberVotes();
    submitVotes(votes, false);

    votingComplete(true);                   // Clear page and show voting complete message
}

/**
 * Switches from the first to the second reading
 */
function startSecondReading() {
    removeCurrentStatus(memberDivs[currentMember]);     // Remove current status from current member

    // Save the votes of members who voted
    var membersToSave = [];
    $('.member').each(function(index, div) {
        if (!isAbsent(div) && !isSaved(div)) {
            membersToSave.push(div);
        }
    });
    var votes = getVotes(membersToSave, false);
    submitVotes(votes, false);

    // Remove members that are not absent and members that have been saved from the form
    $('.member').each(function(index, div) {
        if (!isAbsent(div) || isSaved(div)) {
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
 * Puts the votes of members who voted to an array, ready to
 * be saved to the database by the submitVotes() function
 *
 * @param members       The divs of the members
 * @param forceUpdate   Set true if updating members, so it will ignore the data-saved attribute.
 * @return {Array}      Array with votes in the format that submitVotes expects
 */
function getVotes(members, forceUpdate) {
    var votes = [];

    $(members).each(function(index, member) {
        if (isSaved(member)) {
            console.log('IGNORING SAVED MEMBER BRUH! ' + $(member).data('id'));
        }

        if (!isAbsent(member) && (forceUpdate || !isSaved(member))) {
            console.log('troloolololol');
            $(member).data('saved', 'true');    // Mark as saved
            votes.push(getMemberVote(member));  // Add member's vote to votes array
        }
    });

    return votes;
}

/**
 * Returns a single member's vote in the format needed by the other functions
 * that save votes
 *
 * @param member    The member who's vote to return
 * @returns Object  Object with member_id and answer_id properties
 */
function getMemberVote(member) {
    var vote = {
        member_id: $(member).data('id'),
        answer_id: isAbsent(member) ? null : getSelectedAnswer(member)
    };

    return vote;
}

/**
 * Returns an array with the votes (null) of all absent members in the form
 *
 * @returns {Array}
 */
function getAbsentMemberVotes() {
    var absentVotes = [];

    $('.member').each(function(index, member) {
        if (isAbsent(member)) {
            console.log(index + ' is absent!!!');
            absentVotes.push(getMemberVote(member));
        }
    });

    return absentVotes;
}

/**
 * Returns the id of the selected answer of a member
 *
 * @param member        The div of the member to get answer from
 * @returns {*|jQuery}
 */
function getSelectedAnswer(member) {
    return $(member).find('input[type="radio"][name="answer_' + $(member).data('id') + '"]:checked').val();
}

/**
 * Saves the given member to the database using the existing getVotes() and submitVotes() functions
 *
 * @param memberDiv     Div of member with vote to save
 * @param goToNext      Set to true if you want to go to next member after saving this one
 */
function saveMember(memberDiv, goToNext) {
    var tmpvote = getVotes([memberDiv], true);
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

    // Before submitting votes, save them to the array used to check for changes
    $(votes).each(function(index, vote) {
        var memberId = vote['member_id'];
        var answerId = vote['answer_id'];

        savedVotes[memberId] = answerId;
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
            $('#couldNotSaveAlert').remove();
            var errorDiv =  getAlertDiv(false, 'couldNotSaveAlert', '<strong>Σφάλμα!</strong> Δεν ήταν δυνατό να αποθηκευτούν οι φήφοι!');

            $('#votingCompleteAlert').remove();
            $('.container').prepend(errorDiv);
        }
    });
}

/**
 * Changes the page to show a success or error message
 *
 * @param success
 */
function votingComplete(success) {
    $('#title').remove();           // Remove title

    $(window).off('beforeunload');  // Turn off message that warns before leaving page

    // Remove any fields remaining
    $(memberDivs).each(function(index, div) {
        $(div).remove();
    });

    // Remove the next phase button
    $('#nextPhaseBtn').remove();

    var alertDiv;
    var msg;
    if (success) {
        msg =   '<strong>Η ψηφοφορία ολοκληρώθηκε με επιτυχία!</strong>' +
                (reading == 1 ? ' Όλοι οι βουλευτές ψήφισαν στην πρώτη ανάγνωση.' : '') +
                ' Δείτε τα αποτελέσματα <a href="/votings/' + voting_id + '" class="alert-link">εδώ</a>.';
    } else {
        msg =   '<strong>Σφάλμα!</strong> Δεν ήταν δυνατό να αποθηκευτούν οι ψήφοι.';
    }
    alertDiv = getAlertDiv(success, 'votingCompleteAlert', msg);

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

/**
 * Returns a bootstrap alert div, with the danger or success class
 * and the specified message
 *
 * @param success       Set true if you want the message to be green, false for red
 * @param id            The id that the div should have
 * @param msg           The message that should be displayed in the div
 * @returns {string}    The div with bootstrap classes etc.
 */
function getAlertDiv(success, id, msg) {
    var alert = '<div class="alert alert-' + (success?'success':'danger') + '" ' + ((id.length > 0) ? 'id="' + id + '"' : '') + '>' +
                msg +
                '</div>';

    return alert;
}