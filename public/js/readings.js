var reading = 1;                // Shows if this is the first, or second reading
var currentMember = 0;          // current member in the reading
var memberDivs;

$(function(){
    memberDivs = $('.member');  // array with all member divs

    // the page just loaded so show the save/absent buttons next to the first member
    setCurrentMember(0);
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
 * @return false
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
 * @return false
 */
function nextMember(event) {
    // If the next button was pressed, the member voted so change the status attribute
    if (event.data.btn == 'next') {
        $(memberDivs[currentMember]).data('status', 'voted');
    }

    removeCurrentStatus(memberDivs[currentMember]);     // Remove current status from the current member

    if (currentMember < memberDivs.length - 1) {        // If this wasn't the last member in the list
        currentMember++;                                // go to the next member
                                                        //todo: go to the next NOT VOTED member
        addCurrentStatus(memberDivs[currentMember]);    // and add current status to them
    } else {
        // Check if we should switch to second reading
        if (reading == 1) {
            // switch to second reading
            startSecondReading();
        } else {
            // voting ended
            console.log('Voting ended!');
        }
    }

    return false;   // This line prevents the page from scrolling to the top when a button is clicked
}

/**
 * Switches from the first to the second reading
 */
function startSecondReading() {
    reading = 2;                            // Set reading variable

    $('#title').text('Δεύτερη ανάγνωση');   // Change title

    var votes = [];

    // Save the votes of members who voted and remove them from the form
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
    //todo: dont send votes to server yet, wait until the second reading is over and send them all at once

    //todo: memberDivs might be outdated after deleting fields so maybe update it

}