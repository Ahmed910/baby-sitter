@extends('dashboard.layout.layout')

@section('content')
<div class="card border-info bg-transparent">

    <div class="card-body">
        <div class="d-flex justify-content-center">
            {!! $offer_requests->links() !!}
        </div>
        <div class="card-datatable table-responsive">
            <table class="table table-hover-animation" data-title="{{ trans('dashboard.offer_request.offer_requests') }}">
                <thead>
                    <tr class="text-center">
                        <th>#</th>
                        <th>{!! trans('dashboard.offer_request.start_date') !!}</th>
                        <th>{!! trans('dashboard.offer_request.end_date') !!}</th>
                        <th>{!! trans('dashboard.offer_request.promo_code') !!}</th>
                        <th>{!! trans('dashboard.offer_request.status.status') !!}</th>
                        <th>{!! trans('dashboard.general.added_date') !!}</th>
                        <th>{!! trans('dashboard.general.control') !!}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($offer_requests as $offer_request)
                    <tr class="{{ $offer_request->id }} text-center">
                        <td>{{ $loop->iteration }}</td>

                        <td>{{ $offer_request->start_date->format("Y-m-d") }}</td>
                        <td>{{ $offer_request->end_date->format("Y-m-d") }}</td>
                        <td>{{ $offer_request->promo_code }}</td>
                        <td>{{ trans('dashboard.offer.offer_statuses.'.$offer_request->status) }}</td>



                        <td>
                            <div class="badge badge-primary badge-md mr-1 mb-1">{{ $offer_request->created_at->format("Y-m-d") }}</div>
                        </td>
                        <td class="justify-content-center">
                           @if($offer_request->status =='pending')
                            <a href="{!! route('dashboard.offer_request.accept',$offer_request->id) !!}" class="text-success mr-2" title="{!! trans('dashboard.general.accept') !!}">
                                <i class="fas fa-check font-medium-3"></i>
                            </a>
                            <a href="{!! route('dashboard.offer_request.reject',$offer_request->id) !!}" class="text-danger mr-2" title="{!! trans('dashboard.general.reject') !!}">
                                {{--  <i class="fas fa-check font-medium-3"></i>  --}}
                                <i class="fa fa-ban font-medium-3" aria-hidden="true"></i>
                            </a>
                            @else
                            {{ trans('dashboard.offer.offer_statuses.'.$offer_request->status) }}
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {!! $offer_requests->links() !!}
        </div>
    </div>
</div>
@include('dashboard.layout.delete_modal')
@endsection


@include('dashboard.offer_request.scripts')
