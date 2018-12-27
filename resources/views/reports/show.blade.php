@extends('layouts.master')

@section('title','Reports')

@section('content')

    <div class="container">
        @if ($reports == "N/A")
            <div class="row d-flex justify-content-center">
                <h4>Create a Schedule to begin reporting</h4>
            </div>
        @else
            <div class="row d-flex justify-content-center mb-4">
                <a href="{{ route('report.generate') }}" class="btn btn-primary">Generate Report</a>
            </div>
            <div class="row">
                <div class="col-sm-6">
                        @if (! empty($reports))
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
            </div>
        @endif
    </div>

@endsection

@section('script')
<script>
    $(document).ready(function(){
        $(".clickable-row").click(function(){
            window.location = $(this).data("href");
        });
    });
</script>
@endsection