@extends('dashboard.layout.layout')

@section('content')
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
<div class="card border-info bg-transparent">
	<div class="card-content">
		<div class="card-body">
			<div class="media align-items-center align-items-lg-start text-center text-lg-left flex-column flex-lg-row">
				<div class="mr-lg-3 mb-3 mb-lg-0">
					<a href="{{ isset($notification->data['sender_data']) && isset($notification->data['sender_data']['image']) ? $notification->data['sender_data']['image'] : asset('storage/images/defaults/notification.png') }}"
						data-popup="lightbox">
						<img src="{{ asset('storage/images/defaults/notification.png') }}" width="60" alt="">
					</a>
				</div>
				<div class="media-body">
					<h6 class="media-title font-weight-semibold">
						<a href="#">{{ $title }}</a>
					</h6>
					<ul class="list-inline list-inline-dotted mb-3 mb-lg-2">
						<li class="list-inline-item"><a class="text-muted">{{ $notification->created_at->diffForHumans() }}</a></li>

					</ul>
					<p class="mb-3">{{ $body . $notification->data['sender_data']['fullname'] }}</p>
					<ul class="list-inline list-inline-dotted mb-0">
						<li class="list-inline-item">{{ trans('dashboard.notification.sender') }}
							@if (isset($notification->data['sender_data']))
							<a
								href="{{ $notification->data['sender_data']['user_type'] == 'client' ? route('dashboard.client.show',$notification->data['sender_data']['id']) : '#' }}">{{ $notification->data['sender_data']['fullname'] }}</a>
							@endif
						</li>
						@if (isset($notification->data['route']) && $notification->data['route'])
						<li class="list-inline-item"><a href="{{ $notification->data['route'] }}">{{ trans('dashboard.general.show_more') }}</a></li>
						@endif
					</ul>
				</div>
				<div class="mt-3 mt-lg-0 ml-lg-3 text-center">
					<a href="{{ isset($notification->data['sender_data']) && isset($notification->data['sender_data']['image']) ? $notification->data['sender_data']['image'] : asset('storage/images/defaults/default.jpg') }}"
						data-popup="lightbox">
						<img src="{{ isset($notification->data['sender_data']) && isset($notification->data['sender_data']['image']) ? $notification->data['sender_data']['image'] : asset('storage/images/defaults/default.jpg') }}" width="96"
							alt="">
					</a>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
