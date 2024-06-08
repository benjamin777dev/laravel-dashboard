window.fetchData = function(tab = null) {
    $('#spinner').show();
    loading = true;
    $.ajax({
        url: '/dashboard',
        method: 'GET',
        data: { tab: tab },
        dataType: 'html',
        success: function(data) {
            $('#spinner').hide();
            loading = false;
            $('.task-container').html(data);
        },
        error: function(xhr, status, error) {
            loading = false;
            console.error('Error:', error);
        }
    });
};

document.addEventListener('DOMContentLoaded', function() {
    var defaultTab = "{{ $tab }}";
    localStorage.setItem('status', defaultTab);
    var status = localStorage.getItem('status');

    var statusInfo = { 'In Progress': false, 'Overdue': false, 'Not Started': false };
    statusInfo[status] = true;

    for (var key in statusInfo) {
        if (key !== status) {
            statusInfo[key] = false;
        }
    }

    var tabs = document.querySelectorAll('.nav-link');
    tabs.forEach(function(tab) {
        tab.classList.remove('active');
    });

    var activeTab = document.querySelector('.nav-link[data-tab="' + status + '"]');
    if (activeTab) {
        activeTab.classList.add('active');
        activeTab.style.backgroundColor = "#253C5B";
        activeTab.style.color = "#fff";
        activeTab.style.borderRadius = "4px";
    }

    var ctx = document.getElementById('chart').getContext('2d');
    window.myGauge = new Chart(ctx, config);
});

window.updateDeal = function(zohoDealId, dealId, parentElement) {
    event.preventDefault();

    let dealData = {};
    parentElement.querySelectorAll('[data-type]').forEach(element => {
        let type = element.getAttribute('data-type');
        let value = element.getAttribute('data-value');
        dealData[type] = value;
    });

    let closingDateInput = document.getElementById(`closing_date${zohoDealId}`);
    let closingDate = closingDateInput ? closingDateInput.value : null;

    if (closingDate === null) {
        console.log("Closing date not found");
        return;
    }

    dealData['closing_date'] = closingDate;

    let formData = {
        "data": [{
            "Deal_Name": dealData.deal_name,
            "Client_Name_Primary": dealData.client_name_primary,
            "Stage": dealData.stage,
            "Representing": dealData.representing,
            "Sale_Price": dealData.sale_price,
            "Closing_Date": dealData.closing_date,
            "Commission": dealData.commission,
            "Pipeline_Probability": dealData.pipeline_probability,
        }],
        "skip_mandatory": true
    };

    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    $.ajax({
        url: `/pipeline/update/${dealId}`,
        method: 'PUT',
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify(formData),
        success: function(response) {
            document.getElementById("loaderOverlay").style.display = "none";
            document.getElementById('loaderfor').style.display = "none";
            if (response?.data[0]?.status == "success") {
                const upperCaseMessage = response.data[0].message.toUpperCase();
                showToast(upperCaseMessage);
                window.location.reload();
            }
        },
        error: function(xhr, status, error) {
            document.getElementById("loaderOverlay").style.display = "none";
            document.getElementById('loaderfor').style.display = "none";
            console.error(xhr.responseText, 'error');
        }
    });
};

window.moduleSelected = function(selectedModule, id = "") {
    var selectedOption = selectedModule.options[selectedModule.selectedIndex];
    var selectedText = selectedOption.text;
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
    $.ajax({
        url: '/task/get-' + selectedText,
        method: "GET",
        dataType: "json",
        success: function(response) {
            var notes = response;
            var noteSelect = $('#noteSelect');
            noteSelect.empty();
            $.each(notes, function(index, note) {
                if (selectedText === "Tasks") {
                    noteSelect.append($('<option>', { value: note?.zoho_task_id, text: note?.subject }));
                }
                if (selectedText === "Deals") {
                    noteSelect.append($('<option>', { value: note?.zoho_deal_id, text: note?.deal_name }));
                }
                if (selectedText === "Contacts") {
                    noteSelect.append($('<option>', { value: note?.zoho_contact_id, text: (note?.first_name ?? '') + ' ' + (note?.last_name ?? '') }));
                }
            });
            noteSelect.show();
        },
        error: function(xhr, status, error) {
            console.error("Ajax Error:", error);
        }
    });
};

var randomScalingFactor = function(progressCount = "") {
    return Math.round(Math.random() * 100);
};

var randomData = function() {
    return [15, 45, 100];
};

var randomValue = function(data) {
    if (data) {
        return data;
    }
    return progress;
};

var data = randomData();
var value = randomValue();
var config = {
    type: 'gauge',
    data: {
        datasets: [{
            data: data,
            value: value,
            backgroundColor: ['#FE5243', '#FADA05', '#21AC25'],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        title: {
            display: true,
        },
        layout: {
            padding: {
                bottom: 30
            }
        },
        needle: {
            radiusPercentage: 2,
            widthPercentage: 3.2,
            lengthPercentage: 80,
        },
        valueLabel: {
            fontSize: "24px",
            formatter: function(value) {
                return Math.round(value) + "%";
            }
        },
        chartArea: {
            width: '80%',
            height: '80%'
        },
        plugins: {
            afterDraw: function(chart, easing) {
                var ctx = chart.ctx;
                ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, 'normal', Chart.defaults.global.defaultFontFamily);
                ctx.textAlign = 'center';
                ctx.textBaseline = 'bottom';
                chart.data.datasets.forEach(function(dataset) {
                    for (var i = 0; i < dataset.data.length; i++) {
                        var model = dataset._meta[Object.keys(dataset._meta)[0]].data[i]._model;
                        var labelText = Math.round(dataset.data[i]) + "%";
                        ctx.fillStyle = '#000';
                        ctx.fillText(labelText, model.x, model.y - 5);
                    }
                });
            }
        }
    }
};
