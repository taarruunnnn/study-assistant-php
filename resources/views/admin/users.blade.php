@extends('layouts.master')

@section('title','Users')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <table class="table table-hover">
                    <thead class="thead-light">
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Schedule Status</th>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr class="clickable-row" data-id="{{ $user->id }}">
                                <th scope="row">{{ $user->id }}</th>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if ($user->schedule()->exists())
                                        Ongoing Schedule
                                    @else
                                        No Schedule
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">User Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>ID :</td>
                                <td id="id"></td>
                            </tr>
                            <tr>
                                <td>Name :</td>
                                <td id="name"></td>
                            </tr>
                            <tr class="schedule-row">
                                <td>Schedule Start :</td>
                                <td id="scheduleStart"></td>
                            </tr>
                            <tr class="schedule-row">
                                <td>Schedule End :</td>
                                <td id="scheduleEnd"></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="my-4">
                        <p id="log-title" style="display:none;">User Logs</p>
                        <ul id="logs" class="list-group">

                        </ul>
                    </div>
                    <button type="button" class="btn btn-danger" id="userDelete">Delete User</button>
                    <p id="confirmDelete">Are you sure you want to <strong>Delete this User?</strong>
                        <br/>
                        <a class="btn btn-outline-primary btn-sm" id="btnYes">Yes</a>
                        <a class="btn btn-outline-secondary btn-sm" id="canceluserDelete">No</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@stop

@section('script')
    <script>
        $(document).ready(function(){

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(".clickable-row").click(function(){
                $('#userModal').modal('show');
                var id = $(this).data('id');
                showUser(id);
            });

            $( "#userDelete" ).click(function(e) {
                e.preventDefault();
                $("#confirmDelete").toggle("slow");
            });

            $('#canceluserDelete').click(function(e) {
                e.preventDefault();
                $("#confirmDelete").hide("slow");
            });

            $('#btnYes').click(function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                deleteUser(id);
            });

            function showUser(id)
            {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('admin.users.details') }}',
                    data: {id : id},
                    success: function(data){
                        console.log(data);
                        displayData(data, id);
                    },
                    error: function(message){
                        console.log('Failed '.message);
                    }
                });
            }

            function displayData(data, id)
            {
                $('#id').text(id);
                $('#btnYes').data('id', id);

                if (data['name'])
                {
                    $('#name').text(data['name']);
                }

                if (data['schedule_start'] && data['schedule_end'])
                {
                    $('#scheduleStart').text(data['schedule_start']);
                    $('#scheduleEnd').text(data['schedule_end']);
                    $('.schedule-row').show();
                } else {
                    $('.schedule-row').hide();
                }

                if (data['logs'])
                {
                    $('#log-title').show();
                    var logs = data['logs'];
                    console.log(logs);
                    logs.forEach(function(log){
                        $('#logs').append('<li class="list-group-item">' + log.description + ' on ' + log.created_at + '</li>');
                    })
                }
            }

            function deleteUser(id)
            {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('admin.users.destroy') }}',
                    data: {id : id},
                    success: function(data){
                        console.log(data);
                        if (data == "Success")
                        {
                            window.location.reload(true)
                        }
                    },
                    error: function(message){
                        console.log('Failed '.message);
                    }
                });
            }

            $('#userModal').on('hidden.bs.modal', function(e){
                $('#log-title').hide();
                $('#logs li').remove();
            });
        });
    </script>
@stop