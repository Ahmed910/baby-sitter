{{--  @extends('dashboard.layout.layout')

@section('content')
    <!-- Basic table -->
    <section id="basic-datatable">
        <div class="row">
            <div class="d-flex justify-content-center">
                {!! $sitters->links() !!}
            </div>
            <div class="col-12">

                <div class="card border-info bg-transparent">
                    <table class="datatables-custom table table-hover-animation" data-title="{{ trans('dashboard.sitter.sitters') }}" data-create_title="{{ trans('dashboard.sitter.add_sitter') }}" data-create_link="{{ route('dashboard.sitter.create') }}">
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
                                 <th>{!! trans('dashboard.order.finished_order_count') !!}</th>
                                <th>{!! trans('dashboard.general.added_date') !!}</th>
                                <th><i data-feather='list'></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sitters as $sitter)

                           <tr>
                               <td>
                                <div class="vs-checkbox-con vs-checkbox-primary justify-content-center"><input type="checkbox" class="check_list" value="{{ $sitter->id}}" name="sitter_list[]"/><span class="vs-checkbox">
                                    <span class="vs-checkbox--check">
                                        <i class="vs-icon feather icon-check"></i>
                                    </span>
                                </span></div>
                               </td>
                           <td><a href="{{ $sitter->avatar }}" data-fancybox="gallery">
                            <div class="avatar">
                            <img src="{{ $sitter->avatar }}" alt="" style="width:60px; height:60px;" class="img-thumbnail rounded">
                            <span class="avatar-status-busy avatar-status-md" id="online_{{ $sitter->id }}"></span>
                            </div>
                            </a></td>
                            <td>{{ $sitter->name }}</td>
                            <td>{{ $sitter->phone }}</td>
                            <td>
                                <div class="badge badge-primary badge-md mr-1 mb-1">{{ $sitter->created_at->format("Y-m-d") }}</div>
                            </td>
                            <td class="justify-content-center">
                                <a onclick="deleteItem('{{ $sitter->id }}' , '{{ route('dashboard.sitter.destroy',$sitter->id) }}')" class="text-danger" title="{!! trans('dashboard.general.delete') !!}">
                                    <i class="fas fa-trash-alt font-medium-3"></i>
                                </a>

                                <a href="{!! route('dashboard.sitter.edit',$sitter->id) !!}" class="text-primary mr-2" title="{!! trans('dashboard.general.edit') !!}">
                                    <i class="fas fa-edit font-medium-3"></i>
                                </a>
                            </td>
                        </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    {!! $sitters->links() !!}
                </div>
            </div>
        </div>
    </section>
    <!--/ Basic table -->
    @include('dashboard.layout.delete_modal')
    @include('dashboard.layout.notify_modal')
@endsection
@include('dashboard.sitter.styles')
@include('dashboard.sitter.scripts')  --}}




@extends('dashboard.layout.layout')

@section('content')
<div class="card border-info bg-transparent">

    <div class="card-body">
        <div class="d-flex justify-content-center">
            {!! $sitters->links() !!}
        </div>
        <div class="card-datatable table-responsive">
            <table class="datatables-custom table table-hover-animation" data-title="{{ trans('dashboard.sitter.sitters') }}" data-create_title="{{ trans('dashboard.sitter.add_sitter') }}" data-create_link="{{ route('dashboard.sitter.create') }}">
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
                    @foreach ($sitters as $sitter)

                   <tr class="{{ $sitter->id }} text-center">
                       <td>
                        <div class="vs-checkbox-con vs-checkbox-primary justify-content-center"><input type="checkbox" class="check_list" value="{{ $sitter->id}}" name="sitter_list[]"/><span class="vs-checkbox">
                            <span class="vs-checkbox--check">
                                <i class="vs-icon feather icon-check"></i>
                            </span>
                        </span></div>
                       </td>
                   <td><a href="{{ $sitter->avatar }}" data-fancybox="gallery">
                    <div class="avatar">
                    <img src="{{ $sitter->avatar }}" alt="" style="width:60px; height:60px;" class="img-thumbnail rounded">
                    <span class="avatar-status-busy avatar-status-md" id="online_{{ $sitter->id }}"></span>
                    </div>
                    </a></td>
                    <td>{{ $sitter->name }}</td>
                    <td>{{ $sitter->phone }}</td>
                    <td>
                        <div class="badge badge-primary badge-md mr-1 mb-1">{{ $sitter->created_at->format("Y-m-d") }}</div>
                    </td>
                    <td class="justify-content-center">
                        <a onclick="deleteItem('{{ $sitter->id }}' , '{{ route('dashboard.sitter.destroy',$sitter->id) }}')" class="text-danger" title="{!! trans('dashboard.general.delete') !!}">
                            <i class="fas fa-trash-alt font-medium-3"></i>
                        </a>

                        <a href="{!! route('dashboard.sitter.edit',$sitter->id) !!}" class="text-primary mr-2" title="{!! trans('dashboard.general.edit') !!}">
                            <i class="fas fa-edit font-medium-3"></i>
                        </a>
                    </td>
                </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {!! $sitters->links() !!}
        </div>
    </div>
</div>
@include('dashboard.layout.delete_modal')
@include('dashboard.layout.notify_modal')
@endsection


@include('dashboard.sitter.scripts')
