<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        // Helper function to keep table row from collapsing when being sorted
        var fixHelperModified = function(e, tr) {
            var $originals = tr.children();
            var $helper = tr.clone();
            $helper.children().each(function(index) {
                $(this).width($originals.eq(index).width())
            });

            return $helper;
        };

        // Make members table sortable
        $("#members_list tbody").sortable({
            helper: fixHelperModified,
            stop: function(event, ui) { renumber_table("members_list") }
        }).disableSelection();

        // Delete button in table rows

    });

    // Renumber table rows
    function renumber_table(tableID) {
        $(tableID + " tr").each(function() {
            count = $(this).parent.children().index($(this)) + 1;
            $(this).find(".priority").html(count);
        });
    }
</script>