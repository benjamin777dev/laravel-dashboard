/*
Template Name: zPortal - Admin & Dashboard Template
Author: Themesbrand
Website: https://themesbrand.com/
Contact: themesbrand@gmail.com
File: contact user list Js File
*/

var url = "build/json/";
var userListData = '';
var editList = false;

//contact user list by json
var getJSON = function (jsonurl, callback) {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", url + jsonurl, true);
    xhr.responseType = "json";
    xhr.onload = function () {
        var status = xhr.status;
        if (status === 200) {
            callback(null, xhr.response);
        } else {
            callback(status, xhr.response);
        }
    };
    xhr.send();
};

// get json
getJSON("contact-user-list.json", function (err, data) {
    if (err !== null) {
        console.log("Something went wrong: " + err);
    } else {
        userListData = data;
        loadUserList(userListData)
    }
});

// load table list data
function loadUserList() {
    $('#userList-table').DataTable({
        "bLengthChange": false,
        order: [[0, 'desc']],
        language: {
            oPaginate: {
                sNext: '<i class="mdi mdi-chevron-right"></i>',
                sPrevious: '<i class="mdi mdi-chevron-left"></i>',
            }
        },
        columns: [
            { data: "name" }, // Assuming name is available
            { data: "abcd" }, // Assuming abcd is available
            { data: "relationship_type" }, // Assuming relationship_type is available
            {
                data: "email",
                render: function (data, type, row) {
                    return '<span style="display:inline-block;white-space:normal; word-wrap: break-word; overflow-wrap: break-word; max-width:200px;">' + data + '</span>';
                }
            },
            { data: "mobile" }, // Assuming mobile is available
            { data: "phone" }, // Assuming phone is available
            {
                data: "address",
                render: function (data, type, row) {
                    return '<span style="display:inline-block;white-space:normal; word-wrap: break-word; overflow-wrap: break-word; max-width:300px;">' + data + '</span>';
                }
            },
{
                data: "Salutation_s",
                render: function (data, type, row) {
                    return '<span style="display:inline-block;white-space:normal; word-wrap: break-word; overflow-wrap: break-word; max-width:200px;">' + data + '</span>';
                }
            },
            {
                data: null,
                'bSortable': false,
                render: function (data, type, row) {
                    return '<div class="tooltip-wrapper">' +
                           '<img src="/images/splitscreen.svg" alt="Split screen icon" class="ppiplinecommonIcon" data-bs-toggle="modal" data-bs-target="#newTaskModalId' + row.id + '"  title="Add Task">' +
                           '<span class="tooltiptext">Add Task</span>' +
                           '</div>' +
                           '<div class="tooltip-wrapper">' +
                           '<img src="/images/sticky_note.svg" alt="Sticky note icon" class="ppiplinecommonIcon" data-bs-toggle="modal" data-bs-target="#" onclick="fetchNotesForContact(\'' + row.id + '\',\'' + row.zoho_contact_id + '\')">' +
                           '<span class="tooltiptext">View Notes</span>' +
                           '</div>' +
                           '<div class="tooltip-wrapper">' +
                           '<img src="/images/noteBtn.svg" alt="Note icon" class="ppiplinecommonIcon" data-bs-toggle="modal" data-bs-target="#staticBackdropforNote_' + row.id + '">' +
                           '<span class="tooltiptext">Add Note</span>' +
                           '</div>';
                }
            }
        ],
        drawCallback: function (oSettings) {
            editContactList();
            removeItem();
        },
    });

    $('#searchTableList').keyup(function () {
        $('#userList-table').DataTable().search($(this).val()).draw();
    });
    $(".dataTables_length select").addClass('form-select form-select-sm');
    $('.dataTables_paginate').addClass('pagination-rounded');
    $(".dataTables_filter").hide();
};

// Select2
$("#tag-input").select2();

// create user modal form
var createContactForms = document.querySelectorAll('.createContact-form')
Array.prototype.slice.call(createContactForms).forEach(function (form) {
    form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        } else {
            event.preventDefault();
            var memberImg = document.getElementById("member-img").src;
            var memberImgValue = memberImg.substring(
                memberImg.indexOf("/as") + 1
            );

            var memberImageValue
            if (memberImgValue == "build/images/users/user-dummy-img.jpg") {
                memberImageValue = ""
            } else {
                memberImageValue = memberImg
            }

            var userName = document.getElementById('username-input').value;
            var str = userName;
            var matches = str.match(/\b(\w)/g);
            var acronym = matches.join(''); // JSON
            var nicknameValue = acronym.substring(0, 2)

            var designationInput = document.getElementById('designation-input').value;
            var emailInput = document.getElementById('email-input').value;
            var tagInputFieldValue = $("#tag-input").val();

            if (userName !== "" && designationInput !== "" && emailInput !== "" && !editList) {
                var newUserId = findNextId();
                
                var newList = {
                    "id": newUserId,
                    "memberImg": memberImageValue,
                    "nickname": nicknameValue,
                    "userName": userName,
                    "designation": designationInput,
                    "email": emailInput,
                    "tags": tagInputFieldValue,
                    "projects": "--"
                };

                userListData.push(newList)
            }else if(userName !== "" && designationInput !== "" && emailInput !== "" && editList){
                var getEditid = 0;
                getEditid = document.getElementById("userid-input").value;
                userListData = userListData.map(function (item) {
                    if (item.id == getEditid) {
                        var editObj = {
                            'id': getEditid,
                            "memberImg": memberImageValue,
                            "nickname": nicknameValue,
                            "userName": userName,
                            "designation": designationInput,
                            "email": emailInput,
                            'tags': tagInputFieldValue,
                            "projects": item.projects
                        }
                        
                        return editObj;
                    }
                    return item;
                });
                editList = false;
            }

            if ($.fn.DataTable.isDataTable('#userList-table')) {
                $('#userList-table').DataTable().destroy();
            }
            loadUserList(userListData)
            $("#newContactModal").modal("hide");
        }
        form.classList.add('was-validated');
    }, false)
});


function fetchIdFromObj(member) {
    return parseInt(member.id);
}

function findNextId() {
    if (userListData.length === 0) {
        return 0;
    }
    var lastElementId = fetchIdFromObj(userListData[userListData.length - 1]),
        firstElementId = fetchIdFromObj(userListData[0]);
    return (firstElementId >= lastElementId) ? (firstElementId + 1) : (lastElementId + 1);
}

// member image
document.querySelector("#member-image-input").addEventListener("change", function () {
    var preview = document.querySelector("#member-img");
    var file = document.querySelector("#member-image-input").files[0];
    var reader = new FileReader();
    reader.addEventListener("load",function () {
        preview.src = reader.result;
    },false);
    if (file) {
        reader.readAsDataURL(file);
    }
});


// edit list event
function editContactList() {
    var getEditid = 0;
    Array.from(document.querySelectorAll(".edit-list")).forEach(function (elem) {
        elem.addEventListener('click', function (event) {
            getEditid = elem.getAttribute('data-edit-id');
            editList = true;
            document.getElementById("createContact-form").classList.remove("was-validated")
            userListData = userListData.map(function (item) {
                if (item.id == getEditid) {
                    document.getElementById("newContactModalLabel").innerHTML = "Edit Profile";
                    document.getElementById("addContact-btn").innerHTML = "Update";
                    document.getElementById("userid-input").value = item.id;
                    if(item.memberImg == ""){
                        document.getElementById("member-img").src = "build/images/users/user-dummy-img.jpg";
                    }else{
                        document.getElementById("member-img").src = item.memberImg;
                    }
                    document.getElementById("username-input").value = item.userName;
                    document.getElementById("designation-input").value = item.designation;
                    document.getElementById("email-input").value = item.email;

                    $("#tag-input").select2({
                        multiple: true,
                    });
                    $('#tag-input').val(item.tags).trigger('change');
                }
                return item;
            });
        });
    });
}


// add list event
Array.from(document.querySelectorAll(".addContact-modal")).forEach(function(elem) {
    elem.addEventListener('click', function (event) {
        editList = false;
        document.getElementById("createContact-form").classList.remove("was-validated");
        document.getElementById("newContactModalLabel").innerHTML = "Add Contact";
        document.getElementById("addContact-btn").innerHTML = "add";
        document.getElementById("userid-input").value = "";
        document.getElementById("username-input").value = "";
        document.getElementById("email-input").value = "";
        document.getElementById("designation-input").value = "";
        document.getElementById("member-img").src = "build/images/users/user-dummy-img.jpg";
        $("#tag-input").select2({
            multiple: true,
        });
        $('#tag-input').val("").trigger('change');
    });
});

// remove item
function removeItem() {
    var getid = 0;
    Array.from(document.querySelectorAll(".remove-list")).forEach(function (item) {
        item.addEventListener('click', function (event) {
            getid = item.getAttribute('data-remove-id');
            document.getElementById("remove-item").addEventListener("click", function () {
                function arrayRemove(arr, value) {
                    return arr.filter(function (ele) {
                        return ele.id != value;
                    });
                }
                var filtered = arrayRemove(userListData, getid);

                userListData = filtered;
                if ( $.fn.DataTable.isDataTable('#userList-table') ) {
                    $('#userList-table').DataTable().destroy();
                }
                loadUserList(userListData);
                $("#removeItemModal").modal("hide");
            });
        });
    });
}