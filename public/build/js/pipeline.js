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