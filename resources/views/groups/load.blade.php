@foreach ($contacts as $contact)
<tr class="table-data">
    <td class="text-start">
        @if($contact->relationship_type == 'Secondary')
            <i class="fa fa-caret-right"></i>
        @endif
        {{$contact->first_name ?? ''}} {{$contact->last_name ?? ''}}
    </td>

    @foreach ($shownGroups as $index => $shownGroup)
                @php
                $group = optional($contact->groups)->firstWhere('groupId', $shownGroup['id']);
                @endphp
                <td>
                    <input type="checkbox" data-id="{{$contact['zoho_contact_id']}}" data-group-id="{{$group}}"
                        onclick="contactGroupUpdate('{{ $contact ? $contact : 'null' }}', '{{ $shownGroup }}', this.checked,'{{$group}}')"
                        class="groupCheckbox" {{ $group ? 'checked' : '' }} data-index="{{ $index }}" />
                </td>
    @endforeach
</tr>
@endforeach
<tr class="spinner" style="display: none;">
    <td colspan="10">
        <!-- Add your spinner HTML here -->
        <!-- For example, you can use Font Awesome spinner -->
        <i class="fas fa-spinner fa-spin"></i> Loading...
    </td>
</tr>