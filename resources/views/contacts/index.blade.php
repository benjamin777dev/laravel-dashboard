@extends('layouts.master')
@section('title', 'Agent Commander | Contacts')

@section('content')
    @vite(['resources/css/custom.css'])
    <div class="container">
        <div class="commonFlex ppipeDiv">
            <p class="pText">Database</p>
            <a onclick="createContact();">
                <div class="input-group-text text-white justify-content-center ppipeBtn" id="btnGroupAddon"
                    data-bs-toggle="modal" data-bs-target="#newTaskModalId"><i class="fas fa-plus plusicon">
                    </i>
                    New Contact
                </div>
            </a>
        </div>
        {{-- <div class="pfilterDiv">
            <div class="pcommonFilterDiv">
                <input placeholder="Search" class="psearchInput" id="pipelineSearch" />
                <i class="fas fa-search search-icon"></i>
            </div>
            <p class="porText">or</p>
            <div class="pcommonFilterDiv">
                <input placeholder="Sort contacts by..." id="pipelineSort" class="psearchInput" />
                <img src="{{ URL::asset('/images/swap_vert.svg') }}" alt="Swap-invert icon" class="ppipelinesorticon">
            </div>
            <div class="input-group-text pfilterBtn" id="btnGroupAddon"> <i class="fas fa-filter"></i>
                Filter
            </div>
        </div> --}}

        <div class="row g-4">
            <div class="col-md-4">
                <div class="row align-items-center" style="gap:12px">
                    <div class="col-md-10 pcommonFilterDiv">
                        <input placeholder="Search" class="psearchInput" id="contactSearch" />
                        <i class="fas fa-search search-icon"></i>
                    </div>
                    <p class="col-md-1 porText">or</p>
                </div>
            </div>
            <div class="col-md-5">
                @php
                    $abcd = ['A+', 'A', 'B', 'C', 'D'];
                @endphp
                <div class="row" style="gap:24px">
                    <div class="psortFilterDiv col-md-6">
                        <select name="abcd_class" class="psearchInput" id="contactSort">
                            <option selected value="">-None-</option>
                            @foreach ($abcd as $abcdIndex)
                                <option value="{{ $abcdIndex }}">{{ $abcdIndex }}</option>
                            @endforeach
                        </select>
                        {{-- <input placeholder="Sort contacts by..." id="pipelineSort" class="psearchInput" /> --}}
                        <img src="{{ URL::asset('/images/swap_vert.svg') }}" alt="Swap-invert icon"
                            class="ppipelinesorticon">
                    </div>

                    <div class="input-group-text pfilterBtn col-md-6" onclick="fetchContact()" id="btnGroupAddon"> <i
                            class="fas fa-filter"></i>
                        Filter
                    </div>
                </div>
            </div>

            <div class="col-md-3 cardsTab">
                <div class="viewCards">
                    <img src="{{ URL::asset('/images/person_pin.svg') }}" class="viewCardsImg" alt="">

                    <p class="viewCardsP">View as Cards</p>
                </div>
                <div class="viewMap">
                    <img src="{{ URL::asset('/images/universal_local.svg') }}" class="viewMapImg" alt="">
                    <p class="viewMapP">View on Map

                    </p>
                </div>
            </div>
        </div>

        <div class="contactlist">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-3 row-cols-xl-4 g-3 ">

                @foreach ($contacts as $contact)
                    <a href="{{ route('contacts.show', $contact['id']) }}">
                        <div class="col">
                            <div class="card dataCardDiv">
                                <div class="card-body dacBodyDiv">
                                    <div class="d-flex justify-content-between align-items-center dacHeaderDiv">
                                        <h5 class="card-title">{{ $contact['first_name'] ?? 'N/A' }}</h5>
                                        <p class="databaseCardWord"
                                            style="background-color: {{ $contact['abcd'] === 'A'
                                                ? '#9CC230'
                                                : ($contact['abcd'] === 'A+'
                                                    ? '#44CE1B'
                                                    : ($contact['abcd'] === 'B'
                                                        ? // '#FFB800' ||
                                                        '#FFB800'
                                                        : ($contact['abcd'] === 'C'
                                                            ? '#D4B40C'
                                                            : ($contact['abcd'] === 'D'
                                                                ? '#816D03'
                                                                : '#4F6481')))) }};">
                                            {{ $contact['abcd'] ?? '-' }}</p>
                                    </div>
                                    <div class="dataPhoneDiv">
                                        <img src="{{ URL::asset('/images/phone.svg') }}" alt=""
                                            class="dataphoneicon">

                                        <p class="card-text">{{ $contact['mobile'] ?? 'N/A' }}</p>
                                    </div>
                                    <div class="datamailDiv">
                                        <img src="{{ URL::asset('/images/mail.svg') }}" alt=""
                                            class="datamailicon">
                                        <p class="dataEmailtext">{{ $contact['email'] ?? 'N/A' }}</p>
                                    </div>
                                    <div class="datadiversityDiv">
                                        <img src="{{ URL::asset('/images/diversity.svg') }}" alt=""
                                            class="datadiversityicon">
                                        <p class="datadiversitytext">2nd</p>
                                    </div>
                                </div>
                                <div class="card-footer dataCardFooter">
                                    <div class="datafootericondiv">
                                        <img src="{{ URL::asset('/images/Frame 99.svg') }}" alt=""
                                            class="datadiversityicon">
                                        <img src="{{ URL::asset('/images/sticky_note.svg') }}" alt=""
                                            class="datadiversityicon">
                                    </div>
                                    <div>
                                        <img src="{{ URL::asset('/images/noteBtn.svg') }}" alt=""
                                            class="datadiversityicon">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="datapagination">
                <nav aria-label="...">
                    <ul class="pagination ppipelinepage d-flex justify-content-end">
                        <li class="page-item disabled">
                            <a class="page-link">Previous</a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item active" aria-current="page">
                            <a class="page-link" href="#">2</a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        {{-- <h1>Contacts</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Contact Name</th>
                    <th>ABCD</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Mobile</th>
                    <th>Address</th>
                    <th>Imp Date</th>
                    <th>Perfect</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($contacts as $contact)
                    <tr>
                        <td><a
                                href="{{ route('contacts.show', ['contact' => $contact['id']]) }}">{{ $contact['Full_Name'] ?? 'N/A' }}</a>
                        </td>
                        <td class="{{ $contact['abcdBackgroundClass'] ?? '' }}">{{ $contact['ABCD'] ?? '' }}</td>
                        <td>{{ $contact['Email'] ?? '' }}</td>
                        <td>{{ $contact['Phone'] ?? '' }}</td>
                        <td>{{ $contact['Mobile'] ?? '' }}</td>
                        <td>{{ $contact['Mailing_Street'] ?? '' }} {{ $contact['Mailing_City'] ?? '' }}
                            {{ $contact['Mailing_State'] ?? '' }} {{ $contact['Mailing_Zip'] ?? '' }}</td>
                        <td>
                            <input type="checkbox" disabled
                                {{ $contact['HasMissingImportantDate'] ?? false ? '' : 'checked' }}>
                        </td>
                        <td>
                            <input type="checkbox" disabled {{ $contact['perfect'] ?? false ? 'checked' : '' }}>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table> --}}
    </div>
@endsection
<script>
    function createContact() {
        console.log("Onclick");
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let name = "CHR";
        var formData = {
            "data": [{
                "Relationship_Type": "Primary",
                "Missing_ABCD": true,
                "Owner": {
                    "id": "{{ auth()->user()->root_user_id }}",
                    "full_name": "{{ auth()->user()->name }}",
                },
                "Unsubscribe_From_Reviews": false,
                "Currency": "USD",
                "Market_Area": "-None-",
                "Lead_Source": "-None-",
                "ABCD": "-None-",
                "Last_Name": name,
                "zia_suggested_users": {}
            }],
            "_token": '{{ csrf_token() }}'
        };
        $.ajax({
            url: '{{ url('/contact/create') }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: JSON.stringify(formData),
            dataType: 'json',
            success: function(data) {
                console.log(data);
                // Handle success response, such as redirecting to a new page
                window.location.href = `{{ url('/contacts-create/${data.id}') }}`;
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }


    function getColorByAbcd(abcd) {
        switch (abcd) {
            case 'A':
                return '#9CC230';
            case 'A+':
                return '#44CE1B';
            case 'B':
                return '#FFB800';
            case 'C':
                return '#D4B40C';
            case 'D':
                return '#816D03';
            default:
                return '#4F6481';
        }
    }

    function filterContactData(sortField, sortDirection, searchInput, filterVal) {
        const searchValue = searchInput.val().trim();
        $.ajax({
            url: '{{ url('/contacts/fetch-contact') }}',
            method: 'GET',
            data: {
                search: encodeURIComponent(searchValue),
                filter: filterVal,

            },
            dataType: 'json',
            success: function(data) {
                // Select the contact list container
                const contactList = $('.contactlist .row');

                // Clear existing contact cards
                contactList.empty();
                if (data.length === 0) {
        contactList.append('<p class="text-center">No records found.</p>');
           }else{
                // Iterate over each contact
                data.forEach(function(contact) {
                    // Generate HTML for the contact card using the template
                    const cardHtml = `
            <a href="{{ route('contacts.show', $contact['id']) }}">
                <div class="col">
                    <div class="card dataCardDiv">
                        <div class="card-body dacBodyDiv">
                        <div class="d-flex justify-content-between align-items-center dacHeaderDiv">
                            <h5 class="card-title">${contact.first_name ?? 'N/A'}</h5>
                            <p class="databaseCardWord" style="background-color: ${getColorByAbcd(contact.abcd)};">${contact.abcd ?? '-'}</p>
                        </div>
                        <div class="dataPhoneDiv">
                            <img src="{{ URL::asset('/images/phone.svg') }}" alt="" class="dataphoneicon">
                            <p class="card-text">${contact.mobile ?? 'N/A'}</p>
                        </div>
                        <div class="datamailDiv">
                            <img src="{{ URL::asset('/images/mail.svg') }}" alt="" class="datamailicon">
                            <p class="dataEmailtext">${contact.email ?? 'N/A'}</p>
                        </div>
                        <div class="datadiversityDiv">
                            <img src="{{ URL::asset('/images/diversity.svg') }}" alt="" class="datadiversityicon">
                            <p class="datadiversitytext">2nd</p>
                        </div>
                    </div>
                    <div class="card-footer dataCardFooter">
                        <div class="datafootericondiv">
                            <img src="{{ URL::asset('/images/Frame 99.svg') }}" alt="" class="datadiversityicon">
                            <img src="{{ URL::asset('/images/sticky_note.svg') }}" alt="" class="datadiversityicon">
                        </div>
                        <div>
                            <img src="{{ URL::asset('/images/noteBtn.svg') }}" alt="" class="datadiversityicon">
                        </div>
                </div>
            </div>
        </div>
            </a>
        `;
                        // Append the contact card HTML to the contact list container
                    contactList.append(cardHtml);
                });
            }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    function fetchContact(sortField, sortDirection) {
        const searchInput = $('#contactSearch');
        var csearch = $('#contactSort');
        var filterVal = csearch.val();
        // var filterVal = selectedModule.val();
        // Call fetchData with the updated parameters
        filterContactData(sortField, sortDirection, searchInput, filterVal);
    }
</script>
