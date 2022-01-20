@extends('dashboard.layout.layout')

@section('content')
<div class="card border-info bg-transparent">

    <div class="card-body">
        <div class="d-flex justify-content-center">
            {!! $transfer_requests->links() !!}
        </div>
        <div class="card-datatable table-responsive">
            <table class="datatables-custom table table-hover-animation" data-title="{{ trans('dashboard.transfer_request.transfer_requests') }}">
                <thead>
                    <tr class="text-center">
                        <th>#</th>
                        <th>{!! trans('dashboard.transfer_request.amount') !!}</th>
                        <th>{!! trans('dashboard.transfer_request.status.status') !!}</th>
                        <th>{!! trans('dashboard.general.added_date') !!}</th>
                        <th>{!! trans('dashboard.general.control') !!}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transfer_requests as $transfer_request)
                    <tr class="{{ $transfer_request->id }} text-center">
                        <td>{{ $loop->iteration }}</td>

                        <td>{{ $transfer_request->amount }}</td>
                        <td>{{ trans('dashboard.transfer_request.status.'.$transfer_request->status) }}</td>



                        <td>
                            <div class="badge badge-primary badge-md mr-1 mb-1">{{ $transfer_request->created_at->format("Y-m-d") }}</div>
                        </td>
                        <td class="justify-content-center">

                            <a href="{!! route('dashboard.transfer_request.show',$transfer_request->id) !!}" class="text-primary mr-2" title="{!! trans('dashboard.general.edit') !!}">
                                <i class="fas fa-desktop font-medium-3"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {!! $transfer_requests->links() !!}
        </div>
    </div>
</div>
@include('dashboard.layout.delete_modal')
@endsection


@include('dashboard.transfer_request.scripts')
