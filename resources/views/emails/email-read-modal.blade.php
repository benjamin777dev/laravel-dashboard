<div class="card-body">
    <div class="d-flex mb-4">
        <div class="flex-shrink-0 me-3">
            <img class="rounded-circle avatar-sm" src="{{ URL::asset('build/images/users/avatar-2.jpg') }}" alt="Generic placeholder image">
        </div>
        <div class="flex-grow-1">
            <h5 class="font-size-14 mt-1">Humberto D. Champion</h5>
            @php
                $contacts = $email->toUserData;
                $firstContact = $contacts->first();
                $remainingCount = $contacts->count() - 1;
            @endphp
            @foreach($contacts as $contact)
            <small class="text-muted">{{$contact['email']}},</small>
            @endforeach
        </div>
    </div>
    <div>

        <h4 class="font-size-16">{{$email['subject']}}</h4>
    
        {!! $email['content'] !!}
        <hr />
    </div>

    {{-- <div class="row">
        <div class="col-xl-2 col-6">
            <div class="card">
                <img class="card-img-top img-fluid" src="{{ URL::asset('build/images/small/img-3.jpg') }}" alt="Card image cap">
                <div class="py-2 text-center">
                    <a href="javascript: void(0);" class="fw-medium">Download</a>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-6">
            <div class="card">
                <img class="card-img-top img-fluid" src="{{ URL::asset('build/images/small/img-4.jpg') }}" alt="Card image cap">
                <div class="py-2 text-center">
                    <a href="javascript: void(0);" class="fw-medium">Download</a>
                </div>
            </div>
        </div>
    </div>

    <a href="javascript: void(0);" class="btn btn-secondary waves-effect mt-4"><i class="mdi mdi-reply"></i> Reply</a> --}}
</div>