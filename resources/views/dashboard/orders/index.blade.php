@extends('dashboard.layout.layout')

@section('content')
<div class="card border-info bg-transparent">

    <div class="card-body">
        <div class="d-flex justify-content-center">
            {!! $orders->links() !!}
        </div>
        <div class="card-datatable table-responsive">
            <table class="table table-hover-animation" data-title="{{ trans('dashboard.order.orders') }}">
                <thead>
                    <tr class="text-center">
                        <th>#</th>
                        <th>{!! trans('dashboard.order.type') !!}</th>
                        <th>{!! trans('dashboard.order.from') !!}</th>
                        <th>{!! trans('dashboard.order.to') !!}</th>
                        <th>{!! trans('dashboard.order.status.status') !!}</th>
                        <th>{!! trans('dashboard.order.service_type') !!}</th>
                        <th>{!! trans('dashboard.general.added_date') !!}</th>
                        <th>{!! trans('dashboard.general.control') !!}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                    <tr class="{{ $order->id }} text-center">
                        <td>{{ $loop->iteration }}</td>

                        <td>{{ trans('dashboard.order.'.$order->to) }}</td>
                        <td>{{ optional($order->client)->name }}</td>
                        <td>{{ $order->to == 'sitter' ? optional($order->sitter)->name : optional($order->center)->name }}</td>
                        <td>{{ $order->to == 'sitter' ? trans('dashboard.order.status.'.optional($order->sitter_order)->status) : trans('dashboard.order.status.'.optional($order->center_order)->status) }}</td>
                        <td>{{ $order->to == 'sitter' ? optional(@$order->sitter_order->service)->service_type : optional(@$order->center_order->service)->service_type}}</td>


                        <td>
                            <div class="badge badge-primary badge-md mr-1 mb-1">{{ $order->created_at->format("Y-m-d") }}</div>
                        </td>
                        <td class="justify-content-center">

                            <a href="{!! route('dashboard.orders.show',$order->id) !!}" class="text-primary mr-2" title="{!! trans('dashboard.general.show') !!}">
                                <i class="fas fa-desktop font-medium-3"></i>
                            </a>
                            <a onclick="deleteItem('{{ $order->id }}' , '{{ route('dashboard.orders.destroy',$order->id) }}')" class="text-danger" title="{!! trans('dashboard.general.delete') !!}">
                                <i class="fas fa-trash-alt font-medium-3"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {!! $orders->links() !!}
        </div>
    </div>
</div>
@include('dashboard.layout.delete_modal')
@endsection


@include('dashboard.orders.scripts')
