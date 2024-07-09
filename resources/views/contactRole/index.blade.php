
<div class="row table-responsive dtranstiontable mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title nproletext">Contact Roles</h4>
                <div class="table-rep-plugin">
                    <div class="table-responsive mb-0" data-pattern="priority-columns">
                        @if ($contactRoles->isEmpty())
                            <p class="text-center notesAsignedText">No Contact Role assigned</p>
                        @else
                            <table id="tech-companies-1" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th data-priority="1">Role</th>
                                        <th data-priority="3">Name</th>
                                        <th data-priority="1">Phone</th>
                                        <th data-priority="3">Email</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($contactRoles as $role)
                                        <tr>
                                            <td>{{ $role['role'] }}</td>
                                            <td>{{ $role['name'] }}</td>
                                            <td>{{ $role['phone'] ?? 'N/A' }}</td>
                                            <td>{{ $role['email'] ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->
    <!-- Responsive Table js -->
    <script src="{{ URL::asset('build/libs/admin-resources/rwd-table/rwd-table.min.js') }}"></script>