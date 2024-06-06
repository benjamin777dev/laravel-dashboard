{{-- contact roles --}}
<div class="table-responsive dtranstiontable mt-3">
    <div class="d-flex justify-content-between align-items-center npNom-TMRoles">
        <p class="nproletext">Contact Roles</p>
        <div class="input-group-text npcontactbtn" id="btnGroupAddon" data-bs-toggle="modal" data-bs-target="#contactRoleModal{{$deal['id']}}">
            <i class="fas fa-plus plusicon">
            </i>
            Add Contact Role
        </div>
    </div>

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
        @foreach ($dealContacts as $dealContact)
        <tbody class="contact_role_table_pipeline">
            <tr>
            <td>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"  @if(isset($dealContact['contactData']))
            onclick="removeContactRole('{{ $dealContact['zoho_deal_id']}}','{{ $dealContact['contactData']['zoho_contact_id'] }}','{{ $dealContact['contactId']}}')"
        @endif></button>
            </td>
            <td>
                {{ $dealContact->contactData ? $dealContact->contactData->first_name . ' ' . $dealContact->contactData->last_name : ($dealContact->userData ? $dealContact->userData->name : 'N/A') }}
            </td>
            <td>
                {{ $dealContact['contactRole'] }}
            </td>
            <td>
                {{ $dealContact->contactData ? ($dealContact->contactData->phone ? $dealContact->contactData->phone : 'N/A') : 'N/A' }}
            </td>
            <td>
                {{ $dealContact->contactData ? $dealContact->contactData->email : ($dealContact->userData ? $dealContact->userData->email : 'N/A') }}
            </td>
            </tr>
        </tbody>
        @endforeach
        @endif
    </table>

     @foreach ($dealContacts as $dealContact)
        <div class="npRoleCard vprolecard">
            <div>
                <p class="npcommonheaderText">Role</p>
                <p class="npcommontableBodytext">
                    {{ $dealContact->contactData ? $dealContact->contactData->first_name . ' ' . $dealContact->contactData->last_name : ($dealContact->userData ? $dealContact->userData->name : 'N/A') }}
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
                        {{ $dealContact->contactData ? ($dealContact->contactData->phone ? $dealContact->contactData->phone : 'N/A') : 'N/A' }}
                    </p>
                </div>
            </div>
            <div>
                <p class="npcommonheaderText">Email</p>
                <p class="npcommontableBodyDatetext">
                    {{ $dealContact->contactData ? $dealContact->contactData->email : ($dealContact->userData ? $dealContact->userData->email : 'N/A') }}
                </p>
            </div>
        </div>
    @endforeach
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
              
</div>

<script>
    function removeContactRole(dealId,contactId,contact_id){
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
                document.getElementById("loaderOverlay").style.display = "none";
                document.getElementById('loaderfor').style.display = "none";
                if (response?.data[0]?.status == "success") {
                    var modalTarget = document.getElementById('savemakeModalId' + dealId);
                    var updateMessage = document.getElementById('updated_message_make');
                    updateMessage.textContent = response?.data[0]?.message;
                    $(modalTarget).modal('show');
                    window.location.reload();
                }
            },
            error: function (xhr) {
                document.getElementById("loaderOverlay").style.display = "none";
                document.getElementById('loaderfor').style.display = "none";
                console.error(xhr.responseText);
            }
        });
    }
    </script>