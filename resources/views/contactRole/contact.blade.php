@foreach ($dealContacts as $dealContact)
<tr>
    <td>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
            @if(isset($dealContact['contactData']))
            onclick="removeContactRole('{{ $dealContact['zoho_deal_id']}}','{{ $dealContact['contactData']['zoho_contact_id'] }}','{{ $dealContact['contactId']}}')"
            @endif></button>
    </td>
    <td>
        {{ $dealContact->contactData ? $dealContact->contactData->first_name . ' ' .
        $dealContact->contactData->last_name : ($dealContact->userData ? $dealContact->userData->name :
        'N/A') }}
    </td>
    <td>
        {{ $dealContact['contactRole'] }}
    </td>
    <td>
        {{ $dealContact->contactData ? ($dealContact->contactData->phone ? $dealContact->contactData->phone
        : 'N/A') : 'N/A' }}
    </td>
    <td>
        {{ $dealContact->contactData ? $dealContact->contactData->email : ($dealContact->userData ?
        $dealContact->userData->email : 'N/A') }}
    </td>
</tr>
@endforeach