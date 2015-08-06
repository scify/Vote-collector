$(function(){
    $('#addVotingItemButton').click(addVotingItemButtonHandler);
    $('#remVotingItemButton').click(removeVotingItemButtonHandler);
});

function addVotingItemButtonHandler() {
    var newVItem = $('.votingItem:first').clone();          // Make a copy of the first voting item

    $(newVItem).children('.form-group').each(function(index, group) {
        $(group).children('select').val(0);                 // Select the first choice on both select fields
        $(group).children('select').show();                 // Show both hidden select fields
        $(group).children('.bootstrap-select').remove();    // Remove old bootstrap-select button thingy
    });

    $(newVItem).insertAfter('.votingItem:last');            // Add to page

    $(".selectpicker").selectpicker('refresh');             // refresh all select picker elements
}

function removeVotingItemButtonHandler() {
    if ($('.votingItem').length > 1) {
        $('.votingItem:last').remove();
    }
}