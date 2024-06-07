<div class="modal fade" id="contactRoleModal{{ $deal['id'] }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">Contact Roles Mapping</h5>
                <button type="button" class="btn-close" id="closemodal" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="contactForm{{ $deal['id'] }}">
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Contacts</th>
                                    <th>Account Name</th>
                                    <th>Contact Roles</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($contacts as $contact)
                                <tr>
                                    <td>

                                        <input type="checkbox" name="contact_{{ $contact['id'] }}"
                                            id="contact_{{ $contact['id'] }}"
                                            onclick="updateContactRoles({{ json_encode($contact)}}, '','{{$deal['zoho_deal_id']}}')"
                                            {{ $dealContacts->contains('id', $contact['id']) ? 'checked' : '' }}>
                                        <label for="contact_{{ $contact['id'] }}">{{ $contact['first_name'] }} {{
                                            $contact['last_name'] }}</label>
                                    </td>
                                    <td>{{ $contact['userData']['name'] ?? "" }}</td>
                                    <td>
                                        <select name="role_{{ $contact['id'] }}" id="role_{{ $contact['id'] }}"
                                            onchange="updateContactRoles({{ json_encode($contact) }}, this.value,'{{$deal['zoho_deal_id']}}')">
                                            @foreach($contactRoles as $contactRole)
                                            <option value="{{$contactRole['name']}}">{{$contactRole['name']}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary taskModalSaveBtn"
                    onclick="submitContactRoles('{{ $deal['zoho_deal_id'] }}')">
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