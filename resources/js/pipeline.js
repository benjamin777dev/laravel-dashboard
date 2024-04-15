const pipelineData = (searchValue = '') => {
    fetch(`{{ url('/pipeline/deals') }}?search=${encodeURIComponent(searchValue) ? encodeURIComponent(searchValue) : ""}`)
        .then(response => response.json())
        .then(data => {
            // Clear previous results
            ppipelineTableBody.innerHTML = '';
            ptableCardDiv.innerHTML = ''
            // Render new results
            const isMobile = window.innerWidth < 767; // Check if viewport width is less than 767 pixels
            console.log("ISMOBILE", isMobile);
            data.forEach(item => {
                if (isMobile) {
                    // Render data in card format
                    const card = document.createElement('div');
                    card.classList.add('pTableCard');
                    card.innerHTML = `
                                            <div class="pTableCard">
                                            <p class="pTableTransText">Transaction</p>
                                            <p class="pTableNameText">${item.deal_name || 'N/A'}</p>
                                            <div class="d-flex justify-content-between">
                                                <div class="pTableSelect pipelinestatusdiv">
                                                    <p style="background-color: ${item.stage === 'Potential'
                            ? '#85A69C'
                            : (item.stage === 'Active'
                                ? '#70BCA5'
                                : (item.stage === 'Pre-Active'
                                    ? '#4B8170'
                                    : (item.stage === 'Under Contract'
                                        ? '#477ABB'
                                        : (item.stage === 'Dead-Lost To Competition'
                                            ? '#575B58'
                                            : '#F18F01'))))}"
                                                        class="pstatusText">${item.stage || 'N/A'}</p>
                                                    <i class="fas fa-angle-down"></i>
                                                </div>
                                                ${item.closing_date || 'N/A'}
                                            </div>
                                            <div class="d-flex justify-content-between psellDiv">
                                                <div><img src="{{ URL::asset('/images/account_box.svg') }}" alt="A"> {{ $deal->contactName->first_name ?? 'N/A' }} {{ $deal->contactName->last_name ?? '' }}
                                                </div>
                                                <div>
                                                    <img src="{{ URL::asset('/images/sell.svg') }}" alt="A">
                                                    ${item.sale_price || 'N/A'}
                                                </div>
                                            </div>
                                            <div class="pCardFooter">
                                                <div class="pfootericondiv">
                                                    <img src="{{ URL::asset('/images/Frame 99.svg') }}" alt=""
                                                        class="pdiversityicon">
                                                    <img src="{{ URL::asset('/images/sticky_note.svg') }}" alt=""
                                                        class="pdiversityicon">
                                                </div>
                                                <div>
                                                    <img src="{{ URL::asset('/images/noteBtn.svg') }}" alt=""
                                                        class="pdiversityicon">
                                                </div>
                                            </div>
                                        </div>
                                        `;
                    ptableCardDiv.appendChild(card);
                } else {
                    // Render data in table format
                    const row = document.createElement('tr');
                    row.innerHTML = `
                                            <td><input type="checkbox" /></td>
                                            <td>${item.deal_name || 'N/A'}</td>
                                            <td>${item.contactName ? (item.contactName.first_name + ' ' + item.contactName.last_name) : 'N/A'}</td>
                                            <td>
                                                <div class="commonFlex pipelinestatusdiv">
                                                    <p style="background-color: ${item.stage === 'Potential'
                            ? '#85A69C'
                            : (item.stage === 'Active'
                                ? '#70BCA5'
                                : (item.stage === 'Pre-Active'
                                    ? '#4B8170'
                                    : (item.stage === 'Under Contract'
                                        ? '#477ABB'
                                        : (item.stage === 'Dead-Lost To Competition'
                                            ? '#575B58'
                                            : '#F18F01'))))}"
                                            class="pstatusText">${item.stage || 'N/A'} </p>
                                                    <i class="fas fa-angle-down"></i>
                                                </div>
                                            </td>
                                            <td>${item.representing || 'N/A'}</td>
                                            <td>${item.sale_price || 'N/A'}</td>
                                            <td>${item.closing_date || 'N/A'}</td>
                                            <td>
                                                <img src="{{ URL::asset('/images/open.svg') }}" alt="Open icon" class="ppiplinecommonIcon">
                                                <img src="{{ URL::asset('/images/splitscreen.svg') }}" alt="Open icon" class="ppiplinecommonIcon">
                                                <img src="{{ URL::asset('/images/sticky_note.svg') }}" alt="Open icon" class="ppiplinecommonIcon">
                                                <img src="{{ URL::asset('/images/noteBtn.svg') }}" alt="Open icon" class="ppiplinecommonIcon">
                                            </td>
                                        `;
                    ppipelineTableBody.appendChild(row);
                }
            });
        })
        .catch(error => {
            console.error('Error:', error);
        });
};

console.log("yes working")
function addTask() {

    console.log('deal is ehdjhdjkfh');
    // var subject = document.getElementsByName("subject")[0].value;
    // if (subject.trim() === "") {
    //     document.getElementById("subject_error").innerHTML = "please enter details";
    // }
    // var whoSelectoneid = document.getElementsByName("who_id")[0].value;
    // var whoId = window.selectedTransation
    // if (whoId === undefined) {
    //     whoId = whoSelectoneid
    // }
    // var dueDate = document.getElementsByName("due_date")[0].value;
    // console.log("dueDate", dueDate);
    // console.log("dealId", deal -> zoho_deal_id);
    // var formData = {
    //     "data": [{
    //         "Subject": subject,
    //         "Who_Id": {
    //             "id": whoId
    //         },
    //         "Status": "In Progress",
    //         "Due_Date": dueDate,
    //         "Priority": "High",
    //         "Transaction": {
    //             "id": $deal -> zoho_deal_id
    //         }
    //     }],
    //     "_token": '{{ csrf_token() }}'
    // };

    // $.ajax({
    //     url: '{{ route('create.task') }}',
    //     type: 'POST',
    //     headers: {
    //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //     },
    //     contentType: 'application/json',
    //     dataType: 'json',
    //     data: JSON.stringify(formData),
    //     success: function (response) {
    //         if (response?.data && response.data[0]?.message) {
    //             // Convert message to uppercase and then display
    //             const upperCaseMessage = response.data[0].message.toUpperCase();
    //             alert(upperCaseMessage);
    //             window.location.reload();
    //         } else {
    //             alert("Response or message not found");
    //         }
    //     },
    //     error: function (xhr, status, error) {
    //         // Handle error response
    //         console.error(xhr.responseText);
    //     }
    // })
}