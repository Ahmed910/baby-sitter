@extends('dashboard.layout.layout')

@section('content')
<div class="card border-info bg-transparent">
    <div class="card-header header-elements-inline">
        <h5 class="card-title">{{ trans('dashboard.notification.notifications') }}</h5>

    </div>

    <div class="card-body">
        <div class="d-flex justify-content-center">
            {!! $notifications->links() !!}
        </div>
        <div class="table-responsive">
            <table class="table table-bordered dataex-html5-selectors">
            <thead>
                <tr class="text-center">
					<th>#</th>
					<th>{{ trans('dashboard.notification.notification') }}</th>
					<th>{{ trans('dashboard.notification.sender') }}</th>
					<th>{{ trans('dashboard.general.read_at') }}</th>
					<th>{{ trans('dashboard.general.added_date') }}</th>
                    <th><i class="feather icon-zap"></i></th>
                </tr>
            </thead>
            <tbody>
				@foreach ($notifications as $notification)
                    @php
                    $title = "";
                    if (isset($notification->data['title'])) {
                        if (!is_array($notification->data['title'])) {
                            $title = trans($notification->data['title']);
                        }elseif (isset($notification->data['title'][1])) {
                            $title = trans($notification->data['title'][0],$notification->data['title'][1]);
                        }elseif (!isset($notification->data['title'][1])) {
                            $title = trans($notification->data['title'][0]);
                        }else{
                            $title = "";
                        }
                    }
                    $body = "";
                    if (isset($notification->data['body'])) {
                        if (!is_array($notification->data['body'])) {
                            $body = trans($notification->data['body']);
                        }elseif (isset($notification->data['body'][1])) {
                            $body = trans($notification->data['body'][0],$notification->data['body'][1]);
                        }elseif (!isset($notification->data['body'][1])) {
                            $body = trans($notification->data['body'][0]);
                        }else{
                            $body = "";
                        }
                    }
                    @endphp
                <tr class="{{ $notification->id }} text-center">
                    <td>{{ $loop->iteration }}</td>
                    <td class="product-img sorting_1">
						<div class="font-medium-1"><a href="{{ route('dashboard.notification.show',$notification->id) }}">{{ $title }}</a></div>
						<div class="text-muted">{{ str_limit($body,100," ...") . $notification->data['sender_data']['fullname'] }}</div>
                    </td>
                    <td>
						<div class="d-flex justify-content-center">
							{!! isset($notification->data['sender_data']) ? $notification->data['sender_data']['fullname'] : trans('dashboard.notification.not_found')  !!}
						</div>
                    </td>

                    <td>
						@if ($notification->read_at)
						<div class="badge badge-success badge-md mr-1 mb-1">
							{{ $notification->read_at->isoFormat("D MMMM , Y ( h:mm a )") }}
						</div>
						@else
							<div class="badge badge-warning badge-md mr-1 mb-1">
                                <i class="fas fa-eye"></i>
				            </div>
						@endif
                    </td>
					<td>
						<div class="badge badge-violet badge-md mr-1 mb-1">{{ $notification->created_at->format("Y-m-d") }}</div>
					</td>
                    <td class="text-center font-medium-1">
                        <a href="{{ route('dashboard.notification.show',$notification->id) }}" class="text-success mr-2 font-medium-3">

                            <i class="fas fa-desktop" title="{!! trans('dashboard.general.show') !!}"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
        <div class="d-flex justify-content-center">
            {!! $notifications->links() !!}
        </div>
    </div>
</div>
@include('dashboard.layout.delete_modal')
@endsection

@section('vendor_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets') }}/vendors/css/tables/datatable/datatables.min.css">
@endsection

@include('dashboard.notification.scripts')
