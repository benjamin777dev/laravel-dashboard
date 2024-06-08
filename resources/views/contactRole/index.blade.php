{{-- contact roles --}}
<div class="d-flex justify-content-between align-items-center npNom-TMRoles">
    <p class="nproletext">Contact Roles</p>
    <div class="input-group-text npcontactbtn" id="btnGroupAddon" data-bs-toggle="modal"
        data-bs-target="#contactRoleModal{{$deal['id']}}">
        <i class="fas fa-plus plusicon">
        </i>
        Add Contact Role
    </div>
</div>
<div class="table-responsive">
    <div class="container-fluid">
        <div class="col-md-12">
            <table class="table bg-grey table-bordered testest nowrap" cellspacing="0" width="100%">
                <thead class="thead_con_design">
                    <tr>
                        <th>
                            <div class="commonFlex">
                                <p class="mb-0"></p>
                            </div>
                        </th>
                        <th>
                            <div class="commonFlex">
                                <p class="mb-0">Role</p>
                            </div>
                        </th>
                        <th>
                            <div class="commonFlex">
                                <p class="mb-0">Role Name</p>
                            </div>
                        </th>
                        <th>
                            <div class="commonFlex">
                                <p class="mb-0">Phone</p>
                            </div>
                        </th>
                        <th>
                            <div class="commonFlex">
                                <p class="mb-0">Email</p>
                            </div>
                        </th>
                    </tr>
                </thead>
                @if ($dealContacts->isEmpty())
                <div>
                    <p class="text-center notesAsignedText">No contacts assigned</p>
                </div>
                @else
                <tbody class="contact_role_table_pipeline">
                    @include('contactRole.contact',['dealContacts'=>$dealContacts,'deal'=>$deal])
                </tbody>
                @endif
            </table>
        </div>
    </div>
</div>
<div class="ptableCardDiv">
    @foreach ($dealContacts as $dealContact)
    <div class="npRoleCard vprolecard">
        <div>
            <p class="npcommonheaderText">Role</p>
            <p class="npcommontableBodytext">
                {{ $dealContact->contactData ? $dealContact->contactData->first_name . ' ' .
                $dealContact->contactData->last_name : ($dealContact->userData ? $dealContact->userData->name :
                'N/A')
                }}
            </p>
        </div>
        <div class="d-flex justify-content-between align-items-center npCardPhoneDiv">
            <div>
                <p class="npcommonheaderText">Role Name</p>
                <p class="npcommontableBodyDatetext">{{ $dealContact['contactRole'] }}</p>
            </div>
            <div>
                <p class="npcommonheaderText">Phone</p>
                <p class="npcommontableBodyDatetext">
                    {{ $dealContact->contactData ? ($dealContact->contactData->phone ?
                    $dealContact->contactData->phone
                    : 'N/A') : 'N/A' }}
                </p>
            </div>
        </div>
        <div>
            <p class="npcommonheaderText">Email</p>
            <p class="npcommontableBodyDatetext">
                {{ $dealContact->contactData ? $dealContact->contactData->email : ($dealContact->userData ?
                $dealContact->userData->email : 'N/A') }}
            </p>
        </div>
    </div>
    @endforeach
</div>
<div class="dpagination">
    <nav aria-label="..." class="dpaginationNav">
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
@include('contactRole.create',['dealContacts'=>$dealContacts,'deal'=>$deal])


<script>
    window.fetchContactRole=function() {
        console.log("fetchControlRole");
        var dealId = window.location.pathname.split('/').pop();
        $.ajax({
            url: `{{ url('/get/deal/contact/role/${dealId}')}}`,
            method: 'GET',
            success: function(data) {
            
                const card = $('.contact_role_table_pipeline').html(data);
            
            },
            error: function(xhr, status, error) {
            
                console.error('Error:', error);
            }
        });
    }

    window.removeContactRole = function(dealId,contactId,contact_id){
        console.log("removeContactRole",dealId,contactId);
        let formData = {
            dealId: dealId,
            zohocontactId: contactId,
            contact_id:contact_id
            
        };
        $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        $.ajax({
            url: `/remove/deal/contact/role`,
            method: 'POST',
            contentType: 'application/json',
            dataType: 'json',
            data: JSON.stringify(formData),
            success: function (response) {
                document.getElementById('closemodal').click();
                fetchContactRole(dealId);
            },
            error: function (xhr) {
                document.getElementById("loaderOverlay").style.display = "none";
                document.getElementById('loaderfor').style.display = "none";
                console.error(xhr.responseText);
            }
        });
    }
</script>