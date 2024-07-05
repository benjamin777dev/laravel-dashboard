$(document).ready(function() {
    console.log("es sjdfsd ussss")
const urlPartss = window.location.pathname.split('/'); // Split the URL by '/'
        const dealId = urlPartss.pop();
         $('#datatable_submittal').DataTable({
    paging: true,
    searching: true,
    "processing": true,
    serverSide: true,
    columns: [
        {
            data: 'submittalName',
            title: 'Submittal Name',
            render: function(data, type, row) {
                return `<span class="editable" data-name="submittalName" data-id="${row.id}">${data}</span>`;
            }
        },
        {
            data: 'submittalType',
            title: 'Submittal Type',
            render: function(data, type, row) {
                return `<span class="editable" data-name="submittalType" data-id="${row.id}">${data}</span>`;
            }
},
        {
            data: 'userData.name',
            title: 'Owner',
            render: function(data, type, row) {
                console.log(data,'shdfhsdhf')
                return  `<span class="editable" data-name="phone" data-id="${row.id}">${formateDate(data) || "N/A"}</span>`;
            }
        },
        {
            data: 'created_at',
            title: 'Created Time',
            render: function(data, type, row) {
                console.log(data,'shdfhsdhf')
                return  `<span class="editable" data-name="created_at" data-id="${row.id}">${formateDate(data) || "N/A"}</span>`;
            }
        },
      
    ],
    
    ajax: {
        url: '/submittal/'+dealId, // Ensure this URL is correct
        type: 'GET', // or 'POST' depending on your server setup
        "data": function(request) {
            request._token = "{{ csrf_token() }}";
            request.perPage = request.length;
            request.stage = $('#related_to_stage').val(),
            request.tab = "In Progress";
            request.page = (request.start / request.length) + 1;
            request.search = request.search.value;
            console.log(request,'skdhfkshdfkhsdkfskddfjhsk')

        },
        dataSrc: function(data) {
            console.log(data,'data is hreeeee')
            return data?.data; // Return the data array or object from your response
        }
    },
});

});
