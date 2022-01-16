{{--  @extends('dashboard.layout.layout')

@section('content')
    <!-- Basic table -->
    <section id="basic-datatable">
        <div class="row">
            <div class="d-flex justify-content-center">
                {!! $sitter_workers->links() !!}
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
            {!! $sitter_workers->links() !!}
        </div>
        <div class="card-datatable table-responsive">
            <table class="datatables-custom table table-hover-animation" data-title="{{ trans('dashboard.sitter_worker.sitter_workers') }}" data-create_title="{{ trans('dashboard.sitter_worker.add_sitter_worker') }}" data-create_link="{{ route('dashboard.sitter_worker.create') }}">
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

                        <th>{!! trans('dashboard.sitter_worker.max_num_of_child_care') !!}</th>
                        <th>{!! trans('dashboard.sitter_worker.total_num_of_student') !!}</th>
                        <th>{!! trans('dashboard.sitter_worker.level_experience') !!}</th>
                        <th>{!! trans('dashboard.sitter_worker.level_percentage') !!}</th>
                        {{--  <th>{!! trans('dashboard.order.finished_order_count') !!}</th>  --}}
                        <th>{!! trans('dashboard.general.added_date') !!}</th>
                        <th><i data-feather='list'></i></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sitter_workers as $sitter_worker)

                   <tr class="{{ $sitter_worker->id }} text-center">
                       <td>
                        <div class="vs-checkbox-con vs-checkbox-primary justify-content-center"><input type="checkbox" class="check_list" value="{{ $sitter_worker->id}}" name="sitter_worker_list[]"/><span class="vs-checkbox">
                            <span class="vs-checkbox--check">
                                <i class="vs-icon feather icon-check"></i>
                            </span>
                        </span></div>
                       </td>
                   <td><a href="{{ $sitter_worker->image }}" data-fancybox="gallery">
                    <div class="avatar">
                    <img src="{{ $sitter_worker->image }}" alt="" style="width:60px; height:60px;" class="img-thumbnail rounded">
                    <span class="avatar-status-busy avatar-status-md" id="online_{{ $sitter_worker->id }}"></span>
                    </div>
                    </a></td>
                    <td>{{ $sitter_worker->name }}</td>
                    <td>{{ $sitter_worker->max_num_of_child_care }}</td>
                    <td>{{ $sitter_worker->total_num_of_student }}</td>
                    <td>{{ $sitter_worker->level_experience }}</td>
                    <td>{{ $sitter_worker->level_percentage }}</td>
                    <td>
                        <div class="badge badge-primary badge-md mr-1 mb-1">{{ $sitter_worker->created_at->format("Y-m-d") }}</div>
                    </td>
                    <td class="justify-content-center">
                        <a onclick="deleteItem('{{ $sitter_worker->id }}' , '{{ route('dashboard.sitter_worker.destroy',$sitter_worker->id) }}')" class="text-danger" title="{!! trans('dashboard.general.delete') !!}">
                            <i class="fas fa-trash-alt font-medium-3"></i>
                        </a>

                        <a href="{!! route('dashboard.sitter_worker.edit',$sitter_worker->id) !!}" class="text-primary mr-2" title="{!! trans('dashboard.general.edit') !!}">
                            <i class="fas fa-edit font-medium-3"></i>
                        </a>
                    </td>
                </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {!! $sitter_workers->links() !!}
        </div>
    </div>
</div>
@include('dashboard.layout.delete_modal')
@include('dashboard.layout.notify_modal')
@endsection


@include('dashboard.sitter_worker.scripts')
