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
    $('#nextBtn').click({btn: 'next'}, nextMember);
    $('#absentBtn').click({btn: 'absent'}, nextMember);
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
    var buttons =   '<div id="currentMemberButtons" class="btn-toolbar col-sm-4">' +
                        '<a id="nextBtn" class="btn btn-primary" href="#"><span class="glyphicon glyphicon-chevron-down"></span> Επόμενος</a>' +
                        '<a id="absentBtn" class="btn btn-warning" href="#"><span class="glyphicon glyphicon-question-sign"></span> Απουσιάζει</a>' +
                    '</div>';

    return buttons;
}

/**
 * todo: write comment
 */
function nextMember(event) {
    // Get which button was pressed
    var btn = event.data.btn;

    //console.log($(memberDivs[currentMember]).data('status')); <- this prints the status

    // If it was the next button, the member voted so change the status attribute
    if (btn == 'next') {
        $(memberDivs[currentMember]).data('status', 'voted');
    }

    removeCurrentStatus(memberDivs[currentMember]);     // Remove current status from the current member

    if (currentMember < memberDivs.length - 1) {        // If this wasn't the last member in the list
        currentMember++;                                // go to the next member

        addCurrentStatus(memberDivs[currentMember]);    // and add current status to them
    } else {
        // switch to second reading???????
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
 * Switches from the first to the second readingΠροσθ
 */
function startSecondReading() {

}