$(function(){
    $('#addVotingItemButton').click(addVotingItemButtonHandler);
});

function addVotingItemButtonHandler() {
    var newVItem = $('.votingItem:first').clone();

    $(newVItem).children('.form-group').each(function(index, group) {
        console.log('oh a child');
        console.log($(group).children('select'));
        $(group).children('select').val(0);
        $(group).children('select').show();
        $(group).children('.bootstrap-select').remove();
    });

    $(newVItem).insertAfter('.votingItem:last');

    $(".selectpicker").selectpicker('refresh');
}
//todo:button to remove a voting item