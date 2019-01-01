@extends('layouts.master')

@section('title','Reports')

@section('content')

    <div class="container">
        <div class="row d-flex justify-content-center mb-4">
            <a href="{{ route('report.generate') }}" class="btn btn-primary">Generate Report</a>
        </div>
        <div class="row">
            <div class="col-sm-8">
                @if (!($reports == "N/A"))
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Previous Reports</h5>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Time</th>
                                        <th scope="col">Progress</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reports as $key => $report)
                                        <tr class="clickable-row" data-href='{{ url("reports/view/{$report->id}") }}'>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $report->created_at }}</td>
                                            <td>{{ $report->progress }}%</td>
                                        </tr> 
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-sm-4">
                @if (! empty($archived))
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Completed Modules</h5>
                        <p>Please update these modules if your results are available</p>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Module Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($archived as $key => $archive)
                                    <tr>
                                        <td>{{ $archive->name }}</td>
                                        <td>
                                            <select class="form-control module-rating" data-module="{{$archive->id}}">
                                                <option value="null" @if ($archive->grade === null) selected="selected" @endif>None</option>
                                                <option value="A+" @if ($archive->grade == "A+") selected="selected" @endif>A+</option>
                                                <option value="A" @if ($archive->grade == "A") selected="selected" @endif>A</option>
                                                <option value="A-" @if ($archive->grade == "A-") selected="selected" @endif>A-</option>
                                                <option value="B+" @if ($archive->grade == "B+") selected="selected" @endif>B+</option>
                                                <option value="B" @if ($archive->grade == "B") selected="selected" @endif>B</option>
                                                <option value="B-" @if ($archive->grade == "B-") selected="selected" @endif>B-</option>
                                                <option value="C+" @if ($archive->grade == "C+") selected="selected" @endif>C+</option>
                                                <option value="C" @if ($archive->grade == "C") selected="selected" @endif>C</option>
                                                <option value="C-" @if ($archive->grade == "C-") selected="selected" @endif>C-</option>
                                                <option value="D" @if ($archive->grade == "D") selected="selected" @endif>D</option>
                                                <option value="F" @if ($archive->grade == "F") selected="selected" @endif>F</option>
                                                <option value="O" @if ($archive->grade == "O") selected="selected" @endif>Other</option>
                                            </select>
                                        </td>
                                    </tr> 
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

@endsection

@section('script')
<script>
    $(document).ready(function(){

        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            
        $(".clickable-row").click(function(){
            window.location = $(this).data("href");
        });

        $(".module-rating").on('change', function(){
            var grade = this.value;
            var moduleId = $(this).attr('data-module');
    
            updateGrade(grade, moduleId);
        });

        function updateGrade(grade, moduleId)
        {
            $.ajax({
                type: 'POST',
                url: '{{ route('schedules.archive.update') }}',
                data: {module: moduleId, grade : grade},
                success: function(data){
                    console.log(data);
                },
                error: function(message){
                    console.log(message);
                }
            });
        }
    });
</script>
@endsection