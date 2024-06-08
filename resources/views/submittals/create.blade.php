<div class="modal fade" id="submittalModal{{ $deal['id'] }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Create Listing Submittal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('contact.spouse.create', ['contactId' => $contact->id]) }}"
                    id="contact_spouse_create_form" method="POST">
                    @csrf
                    {{-- Layout --}}
                    <div class="col-md-6 col-sm-12"
                        style=" padding:16px; border-radius:4px;background: #FFF;box-shadow: 0px 12px 24px 0px rgba(18, 38, 63, 0.03);">
                        <p class="npinfoText">Basic Info</p>
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label for="validationDefault01" class="form-label nplabelText">Transaction Name</label>
                                <input type="text" name="first_name" value="" placeholder="Enter First name"
                                    class="form-control npinputinfo" id="validationDefault01">
                            </div>
                            <div class="col-md-6">
                                <label for="validationDefault02" class="form-label nplabelText">Agent Name on
                                    Material</label>
                                <input type="text" value="" name="last_name" placeholder="Enter Last name"
                                    class="form-control npinputinfo validate" id="last_name">
                            </div>

                            <div class="col-md-6">
                                <label for="validationDefault03" class="form-label nplabelText">Using CHR TM?</label>
                                <input type="text" value="" name="mobile" class="form-control npinputinfo"
                                    placeholder="Enter Mobile Number" id="validationDefault03">
                            </div>
                            <div class="col-md-6">
                                <label for="validationDefault03" class="form-label nplabelText">TM Name</label>
                                <input type="text" value="" name="phone" class="form-control npinputinfo"
                                    placeholder="Enter Phone Number" id="validationDefault03">
                            </div>
                            <div class="col-md-6">
                                <label for="validationDefault03" class="form-label nplabelText">Listing Agreement
                                    Executed?</label>
                                <input type="text" value="" name="email" class="form-control npinputinfo"
                                    placeholder="Enter Email" id="validationDefault03">
                            </div>
                            <div class="col-md-6">
                                <label for="validationDefault03" class="form-label nplabelText">Price</label>
                                <select name="market_area" class="form-select npinputinfo" id="validationDefault03">
                                    <option disabled value="">-None-</option>
                                    <option value="Yes">
                                        Yes</option>
                                    <option value="No">
                                        No</option>
                                </select>

                            </div>
                            <div>
                                <div>
                                    <label for="validationDefault02" class="form-label nplabelText">Beds, Baths, Total
                                        Sq Ft</label>
                                    <select name="abcd_class" class="form-select npinputinfo" id="validationDefault04">
                                        <option selected disabled value="">-None-</option>
                                    </select>
                                </div>
                                <div class="row d-flex justify-content-center mt-100">
                                    <div>
                                        <label for="validationDefault02"
                                            class="form-label nplabelText mt-2">Groups</label>
                                        <select id="choices-multiple-remove-button" placeholder="Select up to 5 Groups"
                                            multiple>
                                            <option selected disabled value="">-None-</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <div>
                            <button type="button" class="btn btn-secondary taskModalSaveBtn" id="spouse_submit_button"
                                {{-- onclick="validateSpouseContactForm()" --}}>
                                <i class="fas fa-save saveIcon"></i> Save Changes
                            </button>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary taskModalSaveBtn" {{--
                    onclick="submitContactRoles('{{ $deal['zoho_deal_id'] }}')" --}}>
                    <i class="fas fa-save saveIcon"></i> Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    var dealContactRoles = [];
    document.addEventListener('DOMContentLoaded', function () {
        var dealContacts = @json($dealContacts);
        console.log("jsdgjasdgfsjdfgsdjfgsdjfgjhg",dealContacts.data);

        dealContacts.data?.forEach(function(contact) {
            var checkbox = document.getElementById('contact_' + contact.contactId);
            if (checkbox) {
                checkbox.checked = true;
            }
            var roleSelect = document.getElementById('role_' + contact.contactId);
            if (roleSelect) {
                roleSelect.value = contact.contactRole;
            }
            // dealContactRoles.push({ contactId: contact.contactId, role: contact.contactRole });
        });
    })
        window.updateContactRoles = function(contact, selectedRole,dealId) {
            var role = selectedRole || document.getElementById('role_' + contact.id).value;
            var checkbox = document.getElementById('contact_' + contact.id);
            var index = dealContactRoles.findIndex(item => item.contactId === contact.zoho_contact_id);

            if (checkbox.checked) {
                if (index === -1) {
                    dealContactRoles.push({ contactId: contact.zoho_contact_id, role: role });
                } else {
                    dealContactRoles[index].role = role;
                }
            } else if(!checkbox.checked){
                removeContactRole(dealId,contact.zoho_contact_id,contact.id)
            }
            else if (!checkbox.checked && index !== -1) {
                dealContactRoles.splice(index, 1);
                
            }

            console.log(dealContactRoles);
        };

        window.submitContactRoles = function(dealId) {

            let formData = {
                data: dealContactRoles,
                skip_mandatory: true
            };

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: `/add/deal/contact/role/`+dealId,
                method: 'POST',
                contentType: 'application/json',
                dataType: 'json',
                data: JSON.stringify(formData),
                success: function (response) {
                    document.getElementById('closemodal').click();
                    fetchContactRole(dealId);
                },
                error: function (xhr) {
                    
                }
            });
        };
</script>