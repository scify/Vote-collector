var reading = 1;        // Shows if this is the first, or second reading
var currentMember = 0;  // current member in the reading
var memberRows;         // Keeps divs of members
var savedVotes = {};    // Keeps the votes of the saved members, to check for changes
var votingItemIds = []; // Keeps the voting item ids
var voting_id;

$(function(){
    memberRows = $('.member');  // Get all member divs

    // For each voting item, add an object to savedVotes (to keep votes in) and its id in the votingItemIds array
    $(memberRows[0]).children('.votingItem').each(function(index, vi) {
        savedVotes[$(vi).data('id')] = {};      // Each object will be used to keep votes in
        votingItemIds.push($(vi).data('id'));   // votingItemIds has all the ids of the voting items in this voting
    });

    // The page just loaded so make the first member in the list the current one
    addCurrentStatus(memberRows[0]);

    // In case the page loaded because the user changed from 2nd -> 1st reading, put votes of saved members in the savedVotes variable
    $(memberRows).each(function(index, member) {
        if (isSaved(member)) {
            if ($(member).data('wassavedasabsent') == true) {
                makeAbsent(member);
                $(votingItemIds).each(function(index, id) {
                    savedVotes[id][getMemberId(member)] = null;
                });
                //savedVotes[getMemberId(member)] = null;
            } else {
                $(votingItemIds).each(function(index, id) {
                    savedVotes[id][getMemberId(member)] = getSelectedAnswer(member, id);
                });
                //savedVotes[getMemberId(member)] = getSelectedAnswer(member);
            }
        }
    });

    // Set the voting_id variable (needed for when the form is saved)
    voting_id = $('#votesDiv').data('votingid');

    // Setup CSRF token for middleware
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Keyboard shortcuts
    $(document).keydown(keyboardHandler);

    // Add confirmation before leaving the page so no data is lost by a misclick
    //$(window).bind('beforeunload', function() {
    //    return 'Σίγουρα θέλετε να φύγετε από τη σελίδα;';
    //});

    $('body').click(clickHandler);                  // Used to change between members by clicking on their names

    $('#nextPhaseBtn').click(nextPhaseBtnHandler);  // Second voting/end voting button

    // Add event listener to remove the shortcuts div from the page
    $('#closeShortcutsLink').click(function() {
        $('.shortcutsDiv').remove();
        return false;
    });
});

/**
 * Handles keypresses and calls the appropriate functions
 * @param e
 */
function keyboardHandler(e) {
    var btn = e.which;

    switch(btn) {
        case 83:    // S (next member)
            var member = memberRows[currentMember];
            if (!isAbsent(member)) {
                saveMember(memberRows[currentMember], false);
            }
            nextMember();
            break;
        case 87:    // W (previous member)
            if (currentMember - 1 >= 0) {
                changeToMember(currentMember - 1);
            }
            break;
        case 65:    // A (mark as absent)
            absentButtonHandler();
            break;
    }
}

/**
 * Checks if the user clicked a member name, and makes that
 * member the current member
 *
 * @param e
 */
function clickHandler(e) {
    var target = e.target;

    if (target.nodeName == 'TD') {
        if ($(target).is('.memberName') && !$(target).is('.currentMember')) {
            var newMember = $(memberRows).index($(target).parent());    // Get index of clicked member

            changeToMember(newMember);
        }
    }
}

/**
 * Changes the current member to another member and saves the current member's
 * value if it should
 *
 * @param memberIndex   The index (in the memberRows array) of the member you want to switch to
 */
function changeToMember(memberIndex) {
    // Save old member's vote
    var currMember = memberRows[currentMember];
    if (!isAbsent(currMember)) {
        var m_id = getMemberId(currMember);
        if (!isSaved(currMember) || answerChanged(currMember)) { // (getSelectedAnswer(currMember) != savedVotes[m_id])
            console.log('is not saved, or answer changed');
            saveMember(currMember, false);
        } else {
            console.log('has the answer changed??');
            console.log(answerChanged(currMember));
        }
    }

    // Remove current status from old div
    removeCurrentStatus(memberRows[currentMember]);

    // Change currentMember to new member index
    currentMember = memberIndex;

    // Add current status to new div
    addCurrentStatus(memberRows[currentMember]);
}

/**
 * Checks with the savedVotes array if any of this member's answers in the voting's items
 * have changed, and returns true if they changed and false if they didn't
 *
 * @param member        Member to check for changed answers
 * @returns {boolean}   Shows if any answers have changed or not
 */
function answerChanged(member) {
    var changed = false;
    $(votingItemIds).each(function(index, id) {
        if (getSelectedAnswer(member, id) != savedVotes[id][getMemberId(member)]) {
            changed = true;
            return;
        }
    });

    return changed;
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
        $(memberRows).each(function (index, member) {
            if (isSaved(member)) {
                var memberId = getMemberId(member);            // Get id
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
    var member = memberRows[currentMember];
    if (!isAbsent(member)) {
        saveMember(member, true);
    } else {
        nextMember();
    }

    return false;
}

/**
 * Goes to the next member in the list
 * (if the current member is the last in the list, it adds current status again to update absent buttons)
 */
function nextMember() {
    removeCurrentStatus(memberRows[currentMember]);
    if (currentMember < memberRows.length - 1) {
        currentMember++;
    }
    addCurrentStatus(memberRows[currentMember]);
}

/**
 * Marks a member as absent and goes to the next member in the list
 *
 * @return {boolean}    To prevent the page from scrolling to the top when a button is clicked
 */
function absentButtonHandler() {
    var member = memberRows[currentMember];

    if (!isAbsent(member)) {
        if (isSaved(member)) {
            $(member).data('saved', false);   // Mark member as not saved
            deleteVote(member);
        }

        makeAbsent(member);

        nextMember();   // Go to next member
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
        $(member).children('.btnCell').append(getNotAbsentButton());
    } else {
        $(member).children('.btnCell').append(getAbsentButton());
    }

    $('#absentBtn').click(absentButtonHandler);     // Event listener for the absent button

    // Remove the next button if it's the last member in the list, or add event listener to it if it's not
    if ($(memberRows).index(member) == memberRows.length - 1) {
        $(member).find('#nextBtn').remove();
    } else {
        $('#nextBtn').click(nextButtonHandler);
    }

    // Apply css class
    $(member).find('.memberName').addClass('currentMember');

    // Unhide radio buttons
    $(member).find('.radios').removeClass('hidden');

    // Hide the selected answer labels
    var m_id = getMemberId(member);
    $(votingItemIds).each(function(index, vi_id) {
        $(member).find('#selAnswerLabel' + m_id + '' + vi_id).text('');
    });

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

    // Show the selected answer labels
    if (!isAbsent(member)) {
        var m_id = getMemberId(member);
        $(votingItemIds).each(function(index, vi_id) {
            var answerText = $(member).find('label[for=rd' + m_id + '' + vi_id + '' + getSelectedAnswer(member, vi_id) + ']').text();
            $('#selAnswerLabel' + m_id + '' + vi_id).text(answerText);
        });
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
function getMemberId(member) {
    return $(member).data('id');
}

/**
 * Checks if a member is marked as saved (using the data-saved attribute)
 *
 * @param member        Member to check
 * @returns {boolean}
 */
function isSaved(member) {
    return ( $(member).data('saved') == true );
}

/**
 * Makes a member div absent
 *
 * @param member
 */
function makeAbsent(member) {
    $(member).addClass('text-muted');
    $(member).children('.btnCell').append(absentLabel());
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
    var votes = getVotes(memberRows, false);
    submitVotes(votes, false);              // Submit the votes

    // Save votes of absent members
    votes = getAbsentMemberVotes();
    submitVotes(votes, false);

    votingComplete(true);                   // Clear page and show voting complete message
}

/**
 * Switches from the first to the second reading
 */
function startSecondReading() {
    removeCurrentStatus(memberRows[currentMember]);     // Remove current status from current member

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

    memberRows = $('.member');                          // Update memberRows

    // If all members voted, no need for second reading
    if (memberRows.length == 0) {
        votingComplete(true);
    } else {
        // Make all remaining members not absent
        $(memberRows).each(function(index, div) {
            makeNotAbsent(div);
        });

        reading = 2;                                                    // Set reading variable
        currentMember = 0;                                              // Current member is the first one again
        $('#title').text('Δεύτερη ανάγνωση');                           // Change title
        addCurrentStatus(memberRows[currentMember]);                    // Add curr. status to current member

        $('#nextPhaseBtn').text('Τέλος ψηφοφορίας');                    // Change the next phase button to say "end voting"
        $('#readingsButtonGroup').prepend(getPrevReadingButton());      // Add button to go to the previous reading
        $('#prevReadingBtn').click(function() {
            if (confirm('Θέλετε να επιστρέψετε στην 1η ανάγνωση;')) {
                $(window).off('beforeunload');                          // Turn off the message about leaving the page
                window.location.href = currentPageUrl;
            }
        });
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
        if (!isAbsent(member) && (forceUpdate || !isSaved(member))) {
            $(member).data('saved', true);      // Mark as saved
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
        member_id: getMemberId(member),
    };

    // For each voting id add an answer for this member
    $(votingItemIds).each(function(index, id) {
        vote['answer_for_' + id] = isAbsent(member) ? null : getSelectedAnswer(member, id);
    });

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
            absentVotes.push(getMemberVote(member));
        }
    });

    return absentVotes;
}

/**
 * Returns the id of the selected answer of a member
 *
 * @param member        The div of the member to get answer from
 * @param votingItemId  The id of the voting item to get answer from
 * @returns {*|jQuery}  The id of the selected answer of the member
 */
function getSelectedAnswer(member, votingItemId) {
    return $(member).find('input[type="radio"][name="answer_' + getMemberId(member) + '' + votingItemId + '"]:checked').val();
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

        $(votingItemIds).each(function(index, id) {
            savedVotes[id][memberId] = vote['answer_for_' + id];
        });
    });

    console.log('[submitVotes] submitting votes to server (kai kala)');
    // Send ajax request to server
    $.ajax({
        url: submitVotesUrl,
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
            $('#votingCompleteAlert').remove();

            var errorDiv =  getAlertDiv(false, 'couldNotSaveAlert', '<strong>Σφάλμα!</strong> Δεν ήταν δυνατό να αποθηκευτούν οι φήφοι!');
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

    // Remove entire table
    $('#votesDiv').remove();

    // Remove the next phase button and previous reading button
    $('#readingsButtonGroup').remove();

    var alertDiv;
    var msg;
    if (success) {
        msg =   '<strong>Η ψηφοφορία ολοκληρώθηκε με επιτυχία!</strong>' +
                (reading == 1 ? ' Όλοι οι βουλευτές ψήφισαν στην πρώτη ανάγνωση.' : '') +
                ' Δείτε τα αποτελέσματα <a href="' + votingUrl + '" class="alert-link">εδώ</a>.';
    } else {
        msg =   '<strong>Σφάλμα!</strong> Δεν ήταν δυνατό να αποθηκευτούν οι ψήφοι.';
    }
    alertDiv = getAlertDiv(success, 'votingCompleteAlert', msg);

    $('.container').prepend(alertDiv);

    // Mark voting as complete in the database
    $.ajax({
        url: markCompleteUrl,
        type: 'POST',
        data: {
            v_id: voting_id
        },
        dataType: 'json',
        error: function(data) {
            // Show error
            var msg = '<strong>Σφάλμα!</strong> Δεν ήταν δυνατό να αποθηκευτεί η ψηφοφορία ως "ολοκληρωμένη"!';
            $('.container').prepend(getAlertDiv(false, 'memberDeleteFailAlert', msg));
        }
    });
}

/**
 * Deletes the vote of the given member from the database
 *
 * @param member    The member to delete the vote of
 */
function deleteVote(member) {
    var m_id = getMemberId(member);    // member id
    console.log('=> would normally send request to delete member');
    // Send ajax request to server
    $.ajax({
        url: deleteVoteUrl,
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