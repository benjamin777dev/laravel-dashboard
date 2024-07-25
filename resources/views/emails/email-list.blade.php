@php
use Carbon\Carbon;
@endphp
<ul class="message-list">
    @foreach($emails as $email)
        <li onclick = "getEmail('{{json_encode($email)}}')">
            <div class="col-mail col-mail-1">
                <div class="checkbox-wrapper-mail">
                    <input type="checkbox" id="chk19">
                    <label for="chk19" class="toggle"></label>
                </div>
                <a href="javascript: void(0);" class="title">{{$email['toEmail']}}, me (3)</a><span class="star-toggle far fa-star"></span>
            </div>
            <div class="col-mail col-mail-2">
                <a href="javascript: void(0);" class="subject">{{$email['subject']}} <span class="teaser">{{$email['content']}}</span>
                </a>
                <div class="date">{{ Carbon::parse($email['created_at'])->format('Y-m-d') }}</div>
            </div>
        </li>
    @endforeach
    
</ul>

<script>
    window.getEmail=function(email){
        email = JSON.parse(email)
        console.log(email.id);
        $.ajax({
            url: "{{ route('email.detail', ['emailId' => ':id']) }}".replace(':id', email.id),
            method: 'GET', // Change to DELETE method
            success: function(response) {
                $('#emailList').html(response)
            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error(xhr.responseText);
                showToastError(xhr.responseText);
            }
        });
    }
</script>