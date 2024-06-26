<footer class="footer position-sticky" >
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <script>document.write(new Date().getFullYear())</script> © zPortal.
            </div>
            <div class="col-sm-6">
                <div class="text-sm-end d-none d-sm-block">
                    Colorado Home Realty, 2024
                </div>
            </div>
        </div>
    </div>
</footer>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    var button = document.querySelector('.navbar-toggler');
        var sidebar = document.querySelector('.vertical-menu');

        button.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });

        const modalSelectMap = [{
            modalID: 'global-search',
            selectElementId: 'global-search'
        }, ];

        modalSelectMap.forEach(({
            modalID,
            selectElementId
        }) => {
            const selectElement = $(`#${selectElementId}`);
            showDropdown(modalID, selectElement);
        });
    });
    function createTransaction(userContactData,contactData=null) {
        let contact =  contactData ? JSON.parse(JSON.stringify(contactData)) : null;
        
       let userContact = JSON.parse(JSON.stringify(userContactData));
        var formData = {
            "data": [{
                "Deal_Name": "{{ config('variables.dealName') }}",
                "Owner": {
                    "id": "{{ auth()->user()->root_user_id }}"
                },
                "Stage": "Potential",
                "Contact_Name": {
                    "Name": userContact.first_name + " " + userContact.last_name,
                    "id": userContact.zoho_contact_id
                },
            }],
            "_token": '{{ csrf_token() }}'
        };

        if (contactData) {
            formData.data[0]["Client_Name_Primary"] = contact.first_name + " " + contact.last_name;
            formData.data[0]["Client_Name_Only"] = contact.first_name + " " + contact.last_name + " || " + contact.zoho_contact_id;
            formData.data[0]["Contact"] = {
                "Name": contact.first_name + " " + contact.last_name,
                "id": contact.zoho_contact_id
            };
        }
        console.log("formData",formData);
        $.ajax({
            url: '{{ url('/pipeline/create') }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: JSON.stringify(formData),
            dataType: 'json',
            success: function(data) {
                console.log(data);
                // Handle success response, such as redirecting to a new page
                window.location.href = `{{ url('/pipeline-create/${data.id}') }}`;
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }
</script>
