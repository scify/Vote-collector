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
 * Creates and returns the button that is used to go back to the first reading
 *
 * @returns {string}
 */
function getPrevReadingButton() {
    return '<a id="prevReadingBtn" class="btn btn-default" href="#"><span class="glyphicon glyphicon-backward"></span></a>'
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