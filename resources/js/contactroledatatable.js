 $('#datatable_contact_ros').DataTable({
    paging: true,
    searching: true,
    "processing": true,
    serverSide: true,
    columns: [
        {
            data: 'role',
            title: 'Role',
            render: function(data, type, row) {
                return `<span class="editable" data-name="role" data-id="${row.id}">${data}</span>`;
            }
        },
        {
            data: 'name',
            title: 'Name',
            render: function(data, type, row) {
                return `<span class="editable" data-name="name" data-id="${row.id}">${data}</span>`;
            }
},
        {
            data: 'phone',
            title: 'Phone',
            render: function(data, type, row) {
                console.log(data,'shdfhsdhf')
                return  `<span class="editable" data-name="phone" data-id="${row.id}">${formateDate(data) || "N/A"}</span>`;
            }
        },
        {
            data: 'email',
            title: 'Email',
            render: function(data, type, row) {
                console.log(data,'shdfhsdhf')
                return  `<span class="editable" data-name="email" data-id="${row.id}">${formateDate(data) || "N/A"}</span>`;
            }
        },
      
    ],
    
    ajax: {
        url: '/contact/roles', // Ensure this URL is correct
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