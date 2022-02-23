

@extends('dashboard.layout.layout')

@section('content')
<div class="card border-info bg-transparent">

    <div class="card-body">
        <div class="d-flex justify-content-center">
            {!! $centers->links() !!}
        </div>
        <div class="card-datatable table-responsive">
            <table class="datatables-custom table table-hover-animation" data-title="{{ trans('dashboard.center.centers') }}" data-create_title="{{ trans('dashboard.center.add_center') }}" data-create_link="{{ route('dashboard.center.create') }}">
                <thead>
                    <tr>
                        <th>
                            <div class="vs-checkbox-con vs-checkbox-primary justify-content-right">
                                <input type="checkbox" class="select_all_rows" value="${data.id}" onclick="toggle(this)"/>
                                <span class="vs-checkbox">
                                    <span class="vs-checkbox--check">
                                        <i class="vs-icon feather icon-check"></i>
                                    </span>
                                </span>
                            </div>
                        </th>
                        <th>{!! trans('dashboard.general.image') !!}</th>
                        <th>{!! trans('dashboard.general.name') !!}</th>

                        <th>{!! trans('dashboard.general.phone') !!}</th>
                        {{--  <th>{!! trans('dashboard.order.finished_order_count') !!}</th>  --}}
                        <th>{!! trans('dashboard.general.added_date') !!}</th>
                        <th><i data-feather='list'></i></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($centers as $center)

                   <tr class="{{ $center->id }} text-center">
                       <td>
                        <div class="vs-checkbox-con vs-checkbox-primary justify-content-center"><input type="checkbox" class="check_list" value="{{ $center->id}}" name="center_list[]"/><span class="vs-checkbox">
                            <span class="vs-checkbox--check">
                                <i class="vs-icon feather icon-check"></i>
                            </span>
                        </span></div>
                       </td>
                   <td><a href="{{ $center->avatar }}" data-fancybox="gallery">
                    <div class="avatar">
                    <img src="{{ $center->avatar }}" alt="" style="width:60px; height:60px;" class="img-thumbnail rounded">
                    <span class="avatar-status-busy avatar-status-md" id="online_{{ $center->id }}"></span>
                    </div>
                    </a></td>
                    <td>{{ $center->name }}</td>
                    <td>{{ $center->phone }}</td>
                    <td>
                        <div class="badge badge-primary badge-md mr-1 mb-1">{{ $center->created_at->format("Y-m-d") }}</div>
                    </td>
                    <td class="justify-content-center">
                        <a onclick="deleteItem('{{ $center->id }}' , '{{ route('dashboard.center.destroy',$center->id) }}')" class="text-danger" title="{!! trans('dashboard.general.delete') !!}">
                            <i class="fas fa-trash-alt font-medium-3"></i>
                        </a>

                        <a href="{!! route('dashboard.center.edit',$center->id) !!}" class="text-primary" title="{!! trans('dashboard.general.edit') !!}">
                            <i class="fas fa-edit font-medium-3"></i>
                        </a>
                        {{--  <a href="{!!  route('dashboard.notification.store') !!}" class="text-success" title="{!! trans('dashboard.general.notify') !!}">
                            <i  class="fa fa-bell font-medium-3"></i>
                        </a>  --}}
                    </td>
                </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {!! $centers->links() !!}
        </div>
    </div>
</div>
@include('dashboard.layout.delete_modal')
@include('dashboard.layout.notify_modal')
@endsection


@include('dashboard.center.scripts')
