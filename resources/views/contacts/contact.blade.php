<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-3 row-cols-xl-4 g-3 ">
    <div class="col col-contact">
        <div class="webResponsiveDiv">
            <table id="example" class="table bg-grey table-bordered nowrap" cellspacing="0" width="100%">
              
                @if ($apend === false)
                    <thead class="thead_con_design">
                        <tr>
                            <th></th>
                            <th data-sort-type="text">
                                <div class="commonFlex" onclick="sortContact('first_name,last_name',this)">
                                    <p class="mb-0">Full name</p>
                                    <div class="d-flex flex-column">
                                        <i class="bx bx-caret-up up-arrow"></i>
                                        <i class="bx bx-caret-down down-arrow"></i>
                                    </div>
                                </div>
                            </th>
                            <th>
                                <div onclick="sortContact('abcd',this)" class="commonFlex">
                                    <p class="mb-0">ABCD</p>
                                        <div class="d-flex flex-column">
                                            <i class="bx bx-caret-up up-arrow"></i>
                                            <i class="bx bx-caret-down down-arrow"></i>
                                        </div>
                                </div>
                            </th>
                            <th scope="col">
                                <div onclick="sortContact('relationship_type',this)" class="commonFlex">
                                    <p class="mb-0">Relationship Type</p>
                                    <div class="d-flex flex-column">
                                        <i class="bx bx-caret-up up-arrow"></i>
                                        <i class="bx bx-caret-down down-arrow"></i>
                                    </div>
                                </div>
                            </th>
                            <th scope="col">
                                <div onclick="sortContact('email',this)" class="commonFlex">
                                    <p class="mb-0">Email</p>
                                    <div class="d-flex flex-column">
                                    <i class="bx bx-caret-up up-arrow"></i>
                                    <i class="bx bx-caret-down down-arrow"></i>
                                </div>
                                </div>
                            </th>
                           
                            <th scope="col">
                                <div onclick="sortContact('mobile',this)" class="commonFlex">
                                    <p class="mb-0">Mobile</p>
                                        <div class="d-flex flex-column">
                                            <i class="bx bx-caret-up up-arrow"></i>
                                            <i class="bx bx-caret-down down-arrow"></i>
                                        </div>
                                </div>
                            </th>
                            <th scope="col">
                                <div onclick="sortContact('phone',this)" class="commonFlex">
                                    <p class="mb-0">Phone</p>
                                        <div class="d-flex flex-column">
                                            <i class="bx bx-caret-up up-arrow"></i>
                                            <i class="bx bx-caret-down down-arrow"></i>
                                        </div>
                                </div>
                            </th>
                            
                            <th scope="col">
                                <div onclick="sortContact('mailing_address',this)" class="commonFlex">
                                    <p class="mb-0">Address</p>
                                    <div class="d-flex flex-column">
                                    <i class="bx bx-caret-up up-arrow"></i>
                                    <i class="bx bx-caret-down down-arrow"></i>
                                </div>
                                </div>
                            </th>    
                            <th scope="col">
                                <div onclick="sortContact('Salutation_s',this)" class="commonFlex">
                                    <p class="mb-0">Envelope Salutation</p>
                                    <div class="d-flex flex-column">
                                    <i class="bx bx-caret-up up-arrow"></i>
                                    <i class="bx bx-caret-down down-arrow"></i>
                                </div>
                                </div>
                            </th>                         
                        </tr>
                    </thead>
                @endif
                <tbody class="table_apeend">
                    @include('contacts.load', ['contacts' => $contacts])
                    <tr class="spinner" style="display: none;">
                        <td colspan="5" class="text-center">
                            <!-- Add your spinner HTML here -->
                            <!-- For example, you can use Font Awesome spinner -->
                            <i class="fas fa-spinner fa-spin"></i> Loading...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        @foreach ($contacts as $contact)
            <a id="taskRoute" href="{{ route('contacts.show', $contact['id']) }}">
                <div class="card dataCardDiv">
                    <div class="card-body dacBodyDiv">
                        <div class="d-flex justify-content-between align-items-center dacHeaderDiv">
                            <div class="d-flex gap-2">
                                <h5 class="card-title" id="first_name{{ $contact['zoho_contact_id'] }}">
                                    {{ $contact['first_name']}} {{$contact['last_name'] ?? 'N/A' }}</h5>
                            </div>

                            <p class="databaseCardWord">
                                {{ $contact['abcd'] ?? '-' }}</p>
                        </div>
                        <div class="dataPhoneDiv">
                            <img src="{{ URL::asset('/images/phoneb.svg') }}" alt="" class="dataphoneicon">

                            <div class="d-flex gap-2"
                                onclick="editText('{{ $contact['zoho_contact_id'] }}','mobile','{{ $contact['mobile'] ?? 'N/A' }}')">
                                <p id="mobile{{ $contact['zoho_contact_id'] }}" class="card-text">
                                    {{ $contact['mobile'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="datamailDiv">
                            <img src="{{ URL::asset('/images/mail.svg') }}" alt="" class="datamailicon">
                            <div class="d-flex gap-2 overflow-hidden"
                                onclick="editText('{{ $contact['zoho_contact_id'] }}','email','{{ $contact['email'] ?? 'N/A' }}')">
                                <p id="email{{ $contact['zoho_contact_id'] }}" class="dataEmailtext">
                                    {{ $contact['email'] ?? 'N/A' }}</p>

                            </div>
                        </div>
                        <div class="datamailDiv">
                            <i class="fas fa-map-marker-alt"></i>
                            <div class="d-flex gap-2 overflow-hidden">
                                <p id="address{{ $contact['zoho_contact_id'] }}" class="dataEmailtext">
                                    @if ($contact['mailing_address'])
                                        {{ $contact['mailing_address'] }}
                                    @endif
                                    @if ($contact['mailing_city'])
                                        {{ $contact['mailing_address'] ? ', ' : '' }}
                                        {{ $contact['mailing_city'] }}
                                    @endif
                                    @if ($contact['mailing_state'])
                                        {{ $contact['mailing_city'] || $contact['mailing_address'] ? ', ' : '' }}
                                        {{ $contact['mailing_state'] }}
                                    @endif
                                    @if ($contact['mailing_zip'])
                                        {{ $contact['mailing_city'] || $contact['mailing_address'] || $contact['mailing_state'] ? ', ' : '' }}
                                        {{ $contact['mailing_zip'] }}
                                    @endif
                                    @if (empty($contact['mailing_address']) &&
                                            empty($contact['mailing_city']) &&
                                            empty($contact['mailing_state']) &&
                                            empty($contact['mailing_zip']))
                                        N/A
                                    @endif

                                </p>
                            </div>
                        </div>


                        <div class="datadiversityDiv">
                            <img src="{{ URL::asset('/images/diversity.svg') }}" alt=""
                                class="datadiversityicon">
                            <p class="datadiversitytext"> {{ $contact['relationship_type'] ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="card-footer dataCardFooter">
                        <div class="datafootericondiv">
                            <div class="tooltip-wrapper" onclick="event.preventDefault();">
                                <img src="{{ URL::asset('/images/Frame 99.svg') }}" alt=""
                                    class="datadiversityicon" data-bs-toggle="modal"
                                    data-bs-target="#newTaskModalId{{ $contact['id'] }}">
                                <span class="tooltiptext">Add Task</span>
                            </div>
                            <div class="tooltip-wrapper">
                                <img src="{{ URL::asset('/images/sticky_note.svg') }}" alt=""
                                    class="datadiversityicon"
                                    onclick="fetchNotesForContact('{{ $contact['id'] }}','{{ $contact['zoho_contact_id'] }}')">
                                <span class="tooltiptext">Fetch Notes</span>
                            </div>
                        </div>
                        <div class="tooltip-wrapper" onclick="event.preventDefault();" data-bs-toggle="modal"
                            data-bs-target="#staticBackdropforNote_{{ $contact['id'] }}">
                            <img src="{{ URL::asset('/images/noteBtn.svg') }}" alt=""
                                class="datadiversityicon">
                            <span class="tooltiptext">Add Note</span>
                        </div>
                    </div>
                </div>
            </a>


        
            <div class="modal fade" id="savemakeModalId{{ $contact['zoho_contact_id'] }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered deleteModal">
                    <div class="modal-content">
                        <div class="modal-header saveModalHeaderDiv border-0">
                            {{-- <h5 class="modal-title">Modal title</h5> --}}
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body saveModalBodyDiv">
                            <p class="saveModalBodyText" id="updated_message_make">
                                Changes have been saved</p>
                        </div>
                        <div class="modal-footer saveModalFooterDiv justify-content-evenly border-0">
                            <div class="d-grid col-12">
                                <button type="button" class="btn btn-secondary saveModalBtn"
                                    data-bs-dismiss="modal">
                                    <i class="fas fa-check trashIcon"></i>
                                    Understood
                                </button>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
            </a>
        @endforeach
    </div>
</div>
<script>
    window.onload = function() {
        let nextPageUrl = '{{ $contacts->nextPageUrl() }}';
        let searchInput = $('#contactSearch');
        console.log(searchInput.value,'searchInput.value')
      
        $(window).scroll(function() {
            if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
                if (nextPageUrl) {
                    loadMorePosts();
                }
            }
        });

        function loadMorePosts() {
            let searchInput = $('#contactSearch');
            console.log(searchInput, 'searchInput')
            let csearch = $('#contactSort');
            let filterVal = csearch.val();
            let email = document.getElementById('filterEmail').checked;
            let mobile = document.getElementById('filterMobile').checked;
            let abcd = document.getElementById('filterABCD').checked;
            let count = 1;
            if (!email && !mobile && !abcd && count > 2) return;

            let missingFeild = {
                email: email,
                mobile: mobile,
                abcd: abcd
            }
            if (!missingFeild.email && !missingFeild.mobile && !missingFeild.abcd) {
                missingFeild = "";
            }
            $('.spinner').show();
            let search = searchInput.val(); // Update search with input value
            $.ajax({
                url: nextPageUrl, // Send only the base URL, append parameters later
                type: 'get',
                data: {
                    search: encodeURIComponent(search), // Pass correct parameters
                    filter: csearch.val(), // Pass filter value
                    missingField: missingFeild, // Adjust as per your requirement
                },
                beforeSend: function() {
                    nextPageUrl = ''; // Reset nextPageUrl to prevent multiple requests
                },
                success: function(data) {
                    $('.spinner').hide();
                    nextPageUrl = data.nextPageUrl; // Update nextPageUrl from response
                    $('.table_apeend').append(data.view);
                },
                error: function(xhr, status, error) {
                    console.error("Error loading more posts:", error);
                    $('.spinner').hide();
                }
            });
        }
        
    }


    let sortDirectionContact = 'desc';
    window.sortContact = function(sort,clickColumn) {
        sortDirectionContact = (sortDirectionContact === 'desc') ? 'asc' : 'desc';
        // Call fetchDeal with the sortField parameter
        fetchContact("", sort, sortDirectionContact,clickColumn);
    }



    function editText(zohoID, name, value) {
        event.preventDefault();
        let elementId = name + zohoID;
        let element = document.getElementById(elementId);
        let text = element.textContent.trim();
        text = text === "" ? value : text;

        let newInput = document.createElement('input');
        newInput.type = 'text';
        newInput.id = elementId;
        newInput.value = value;
        element.parentNode.replaceChild(newInput, element);
        newInput.setAttribute("onclick", "event.preventDefault()");
        newInput.focus();

        // Prevent clicks inside the input from triggering the container's click event
        newInput.addEventListener('click', function(event) {
            event.stopPropagation();
        });

        function replaceWithParagraph() {
            let newParagraph = document.createElement('p');
            newParagraph.id = elementId;
            newParagraph.textContent = newInput.value;
            newInput.parentNode.replaceChild(newParagraph, newInput);
        }

        newInput.addEventListener('blur', replaceWithParagraph);

        newInput.addEventListener('change', function() {
            replaceWithParagraph();
            updateContact(zohoID, name);
        });

        // Prevent default action when clicking on container
        let container = document.getElementById("contactlist");
        container?.addEventListener("click", function(event) {
            event.preventDefault();
        });
    }


    function fetchNotesForContact(id, conId) {
        event.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{ route('notes.fetch', ['contactId' => ':contactId']) }}".replace(':contactId', id),
            method: "GET",
            success: function(response) {
                // $('#notesContainer').append('<p>New Note Content</p>');
                let noteContainer = $("#notesContainer" + conId);
                console.log(noteContainer, 'noteContainer')
                // Clear previous contents of note containe
                noteContainer.empty();
                const card = noteContainer.html(response);
                // Loop through each note in the response array
                // response?.forEach(function(note) {
                //     // console.log(note, 'note')
                //     // Create HTML to display note content and creation time
                //     let data = `<div class="noteCardForContact">
                //                 <p>Note Content: ${note?.contact_data?.first_name} ${note?.contact_data?.last_name}</p>
                //                 <p>Note Content: ${note?.note_content}</p>
                //             </div>`;
                //     // Append the HTML to noteContainer
                //     noteContainer.append(data);
                //     console.log("testing", noteContainer)
                // });
                // // Show the modal after appending notes
                $("#notefetchrelatedContact" + conId).modal('show');
            },
            error: function(xhr, status, error) {
                // Handle error
                showToastError(error);
                console.error("Ajax Error:", error);
            }
        });

    }

    function updateContact(zohoID, name, selectedValue=null) {
        console.log(name, zohoID, 'tresdkjfklshdlfhsldf');
        let elementId = document.getElementById(name + zohoID);
        document.getElementById("loaderOverlay").style.display = "block";
        document.getElementById('loaderfor').style.display = "block";
        let formData = {
            "data": [{
                "Missing_ABCD": selectedValue?false:true,
                "ABCD":selectedValue??undefined,
                "Owner": {
                    "id": '{{ auth()->user()->root_user_id }}',
                    "full_name": '{{ auth()->user()->name }}'
                },
                "Unsubscribe_From_Reviews": false,
                "Currency": "USD",
                "First_Name": name == "first_name" || name == "first_name_web" ? elementId?.textContent :
                    undefined,
                "Mobile": name == "mobile" || name == "mobile_web" ? elementId?.textContent : undefined,
                // "ABCD": "",
                "Email": name == "email" || name == "email_web" ? elementId?.textContent : undefined,
                "Salutation": name == "envelope_salutation" || name == "salutation_s" ? elementId?.textContent : undefined,
                "zia_suggested_users": []
            }],
            "skip_mandatory": true
        }
        // Iterate through the data array
        formData?.data?.forEach(obj => {
            // Iterate through the keys of each object
            Object.keys(obj).forEach(key => {
                // Check if the value is undefined and delete the key
                if (obj[key] === undefined) {
                    delete obj[key];
                }
            });
        });
        //ajax call hitting here
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "{{ route('update.contact', ['id' => ':id']) }}".replace(':id', zohoID),
            method: 'PUT',
            contentType: 'application/json',
            dataType: 'json',
            data: JSON.stringify(formData),
            success: function(response) {
                console.log(response)
                // Handle success response
                document.getElementById("loaderOverlay").style.display = "none";
                document.getElementById('loaderfor').style.display = "none";
                if (response?.data[0]?.status == "success") {
                    if (!document.getElementById('savemakeModalId' + zohoID).classList.contains('show')) {
                        var modalTarget = document.getElementById('savemakeModalId' + zohoID);
                        var update_message = document.getElementById('updated_message_make');
                        update_message.textContent = formatSentence(response?.data[0]?.message);
                        // Show the modal
                        $(modalTarget).modal('show');
                        window.location.reload();
                    }

                }
            },
            error: function(xhr, status, error) {
                document.getElementById("loaderOverlay").style.display = "none";
                document.getElementById('loaderfor').style.display = "none";
                if (xhr?.responseJSON?.status === 401) {
                    showToastError(xhr?.responseJSON?.error);

                }
                // Handle error response


            }
        })



    }
</script>
