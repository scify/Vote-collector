<!-- Javascript for adding and removing answer fields -->
<script type="text/javascript">
    // Event listener for adding an answer
    document.getElementById('addAnswer').addEventListener('click', addField);

    // Event listener for removing answer
    document.querySelector('body').addEventListener('click', function (event) {
        var target = event.target;

        // Check if the button was clicked (or the span inside it)
        if (target.id == 'removeBtn') {
            removeField(target);
        } else if (target.tagName.toLowerCase() == 'span' && target.parentNode.id == 'removeBtn') {
            removeField(target.parentNode);
        }
    });

    // Removes an answer field
    function removeField(target) {
        var igroup = target.parentNode.parentNode;  // target is the button, get input group
        igroup.parentNode.removeChild(igroup);
    }

    // Adds an answer field
    function addField() {
        var answer_div = document.getElementById('answers');    // div to append p's to

        // Create div to put field and remove button in
        var div = document.createElement('div');
        div.className = 'input-group';

        // Create text field
        var field = document.createElement('input');
        field.type = 'text';
        field.className = 'form-control';
        field.id = 'answer';
        field.name = 'answers[]';
        field.value = '';

        // Create span to put button in (needed so button is on the right side of text field)
        var span = document.createElement('span');
        span.className = 'input-group-btn';

        // Create remove button
        var btn = document.createElement('a');
        btn.href = '#';
        btn.id = 'removeBtn';
        btn.innerHTML = '<span class=\'glyphicon glyphicon-remove\'></span>';
        btn.className = 'btn btn-danger';

        span.appendChild(btn);          // append button to span
        div.appendChild(field);         // append text field to p
        div.appendChild(span);          // append span to p
        answer_div.appendChild(div);    // append p to answers div!!!
    }
</script>