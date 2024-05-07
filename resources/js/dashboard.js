window.moduleSelected = function (selectedModule, id = "") {
    // console.log(accessToken,'accessToken')
    var selectedOption = selectedModule.options[selectedModule.selectedIndex];
    var selectedText = selectedOption.text;
    console.log(selectedText, "selectedText");
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: '/task/get-' + selectedText,
        method: "GET",
        dataType: "json",
        success: function (response) {
            // Handle successful response
            var notes = response;
            // Assuming you have another select element with id 'taskSelect'
            var noteSelect = $('#noteSelect');
            // Clear existing options
            noteSelect.empty();
            // Populate select options with tasks
            $.each(notes, function (index, note) {
                if (selectedText === "Tasks") {
                    noteSelect.append($('<option>', {
                        value: note?.zoho_task_id,
                        text: note?.subject
                    }));
                }
                if (selectedText === "Deals") {
                    noteSelect.append($('<option>', {
                        value: note?.zoho_deal_id,
                        text: note?.deal_name
                    }));
                }
                if (selectedText === "Contacts") {
                    noteSelect.append($('<option>', {
                        value: note?.zoho_contact_id,
                        text: (note?.first_name ?? '') + ' ' + (note?.last_name ?? '')
                    }));
                }
            });
            noteSelect.show();
            // Do whatever you want with the response data here
        },
        error: function (xhr, status, error) {
            // Handle error
            console.error("Ajax Error:", error);
        }
    });

}