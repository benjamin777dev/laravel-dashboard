@extends('layouts.master')
@section('title', 'Agent Commander | Contacts')

@section('content')
    @vite(['resources/css/custom.css'])
    <div class="container">
        <div class="commonFlex ppipeDiv">
            <p class="pText">Database</p>
            <a href = "{{ route('contacts.create') }}"><div class="input-group-text text-white justify-content-center ppipeBtn" id="btnGroupAddon" data-bs-toggle="modal"
                data-bs-target="#newTaskModalId"><i class="fas fa-plus plusicon">
                </i>
                New Contact
            </div>
            </a>
        </div>
        <div class="pfilterDiv">
            <div class="pcommonFilterDiv">
                <input placeholder="Search" class="psearchInput" id="pipelineSearch" />
                <i class="fas fa-search search-icon"></i>
            </div>
            <p class="porText">or</p>
            <div class="pcommonFilterDiv">
                <input placeholder="Sort contacts by..." id="pipelineSort" class="psearchInput" />
                <img src="{{ URL::asset('/images/swap_vert.svg') }}" alt="Swap-invert icon" class="ppipelinesorticon">
            </div>
            <div class="input-group-text pfilterBtn" id="btnGroupAddon"> <i class="fas fa-filter"></i>
                Filter
            </div>
        </div>

        <div>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-3 row-cols-xl-4 g-3 ">
                
                @foreach ($contacts as $contact)
                <a href="{{ route('contacts.show', $contact['id']) }}">
                    <div class="col">
                        <div class="card dataCardDiv">
                            <div class="card-body dacBodyDiv">
                                <div class="d-flex justify-content-between align-items-center dacHeaderDiv">
                                    <h5 class="card-title">{{ $contact['Full_Name'] ?? 'N/A' }}</h5>
                                    <p class="databaseCardWord"
                                        style="background-color: {{ $contact['ABCD'] === 'A'
                                            ? '#9CC230'
                                            : ($contact['ABCD'] === 'A+'
                                                ? '#44CE1B'
                                                : ($contact['ABCD'] === 'B'
                                                    ? // '#FFB800' ||
                                                    '#FFB800'
                                                    : ($contact['ABCD'] === 'C'
                                                        ? '#D4B40C'
                                                        : ($contact['ABCD'] === 'D'
                                                            ? '#816D03'
                                                            : '#4F6481')))) }};">
                                        {{ $contact['ABCD'] ?? '-' }}</p>
                                </div>
                                <div class="dataPhoneDiv">
                                    <img src="{{ URL::asset('/images/phone.svg') }}" alt="" class="dataphoneicon">

                                    <p class="card-text">{{ $contact['Mobile'] ?? 'N/A' }}</p>
                                </div>
                                <div class="datamailDiv">
                                    <img src="{{ URL::asset('/images/mail.svg') }}" alt="" class="datamailicon">
                                    <p class="dataEmailtext">{{ $contact['Email'] ?? 'N/A' }}</p>
                                </div>
                                <div class="datadiversityDiv">
                                    <img src="{{ URL::asset('/images/diversity.svg') }}" alt=""
                                        class="datadiversityicon">
                                    <p class="datadiversitytext">2nd</p>
                                </div>
                            </div>
                            <div class="card-footer dataCardFooter">
                                <div class="datafootericondiv">
                                    <img src="{{ URL::asset('/images/Frame 99.svg') }}" alt=""
                                        class="datadiversityicon">
                                    <img src="{{ URL::asset('/images/sticky_note.svg') }}" alt=""
                                        class="datadiversityicon">
                                </div>
                                <div>
                                    <img src="{{ URL::asset('/images/noteBtn.svg') }}" alt=""
                                        class="datadiversityicon">
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            <div class="datapagination">
                <nav aria-label="...">
                    <ul class="pagination ppipelinepage d-flex justify-content-end">
                        <li class="page-item disabled">
                            <a class="page-link">Previous</a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item active" aria-current="page">
                            <a class="page-link" href="#">2</a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        {{-- <h1>Contacts</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Contact Name</th>
                    <th>ABCD</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Mobile</th>
                    <th>Address</th>
                    <th>Imp Date</th>
                    <th>Perfect</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($contacts as $contact)
                    <tr>
                        <td><a
                                href="{{ route('contacts.show', ['contact' => $contact['id']]) }}">{{ $contact['Full_Name'] ?? 'N/A' }}</a>
                        </td>
                        <td class="{{ $contact['abcdBackgroundClass'] ?? '' }}">{{ $contact['ABCD'] ?? '' }}</td>
                        <td>{{ $contact['Email'] ?? '' }}</td>
                        <td>{{ $contact['Phone'] ?? '' }}</td>
                        <td>{{ $contact['Mobile'] ?? '' }}</td>
                        <td>{{ $contact['Mailing_Street'] ?? '' }} {{ $contact['Mailing_City'] ?? '' }}
                            {{ $contact['Mailing_State'] ?? '' }} {{ $contact['Mailing_Zip'] ?? '' }}</td>
                        <td>
                            <input type="checkbox" disabled
                                {{ $contact['HasMissingImportantDate'] ?? false ? '' : 'checked' }}>
                        </td>
                        <td>
                            <input type="checkbox" disabled {{ $contact['perfect'] ?? false ? 'checked' : '' }}>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table> --}}
    </div>
@endsection
