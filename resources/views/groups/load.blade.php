@foreach ($contacts as $contact)
<tr class="table-data">
    <td class="text-start sticky-col first-col">
        @if($contact->relationship_type == 'Secondary')
            <i class="fa fa-caret-right"></i>
        @endif
       <a href="{{ url('/contacts-view/' . $contact->id) }}" target="_blank" style="color:black;">{{ $contact->first_name ?? '' }} {{ $contact->last_name ?? '' }}</a>

    </td>

    @foreach ($shownGroups as $index => $shownGroup)
                @php
                $group = optional($contact->groups)->firstWhere('groupId', $shownGroup['id']);
                @endphp
                <td>
                    <input type="checkbox" data-id="{{$contact->zoho_contact_id}}" data-group-id="{{$group ? $group->zoho_contact_group_id : ''}}"
                        onclick="contactGroupUpdate(this, '{{ $contact->zoho_contact_id }}', '{{ $shownGroup->zoho_group_id }}', this.checked, '{{$group ? $group->zoho_contact_group_id : ''}}')"
                        class="groupCheckbox" {{ $group ? 'checked' : '' }} data-index="{{ $index }}" />
                </td>
    @endforeach
</tr>
@endforeach
