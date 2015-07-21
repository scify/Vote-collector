var reading = 1;        // Shows if this is the first, or second reading
var currentMember = 0;  // current member in the reading
var memberDivs;         // Keeps divs of members
var votes = [];         // Keeps member's votes
var voting_id;

$(function(){
    memberDivs = $('.member');  // Get all member divs

    // the page just loaded so show the save/absent buttons next to the first member
    setCurrentMember(0);

    // set the voting_id variable (needed for when the form is saved)
    voting_id = $('#votesform').data('votingid');
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
 * @return false    To prevent the page from scrolling to the top when a button is clicked
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
 * @return false    To prevent the page from scrolling to the top when a button is clicked
 */
function nextMember(event) {
    // If the next button was pressed, the member voted so change the status attribute
    if (event.data.btn == 'next') {
        $(memberDivs[currentMember]).data('status', 'voted');
    }

    removeCurrentStatus(memberDivs[currentMember]);     // Remove current status from the current member

    if (currentMember < memberDivs.length - 1) {        // If this wasn't the last member in the list
        currentMember++;                                // go to the next member

        addCurrentStatus(memberDivs[currentMember]);    // and add current status to them
    } else {
        // Check if we should switch to second reading or the voting ended
        if (reading == 1) {
            // switch to second reading
            startSecondReading();
        } else {
            console.log("VOTING ENDEDDDDDDDD");
            // Voting ended, save the votes
            saveVotes(memberDivs, votes);

            // And submit them to the server
            submitVotes(votes);
        }
    }

    return false;
}

/**
 * Switches from the first to the second reading
 */
function startSecondReading() {
    reading = 2;                            // Set reading variable

    $('#title').text('Δεύτερη ανάγνωση');   // Change title

    saveVotes(memberDivs, votes);           // Save the votes of members who voted

    memberDivs = $('.member');              // Update memberDivs

    // If all members voted, no need for second reading
    if (memberDivs.length == 0) {
        submitVotes(votes);

        return;
    }

    // Add current status to the first member on the list
    currentMember = 0;
    addCurrentStatus(memberDivs[currentMember]);
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
            //todo: go to votings page
            console.log("Server returned SUCCESS");
        },
        error: function(data) {
            console.log("Server returned ERROR!!!");
        }
    });
}