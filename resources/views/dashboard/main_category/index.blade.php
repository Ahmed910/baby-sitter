@extends('dashboard.layout.layout')

@section('content')
<div class="card border-info bg-transparent">

    <div class="card-body">
        <div class="d-flex justify-content-center">
            {!! $main_categories->links() !!}
        </div>
        <div class="card-datatable table-responsive">
            <table class="datatables-custom table table-hover-animation" data-title="{{ trans('dashboard.main_category.main_categories') }}" data-create_title="{{ trans('dashboard.main_category.add_main_category') }}" data-create_link="{{ route('dashboard.main_category.create') }}">
                <thead>
                    <tr class="text-center">
                        <th>#</th>
                        <th>{!! trans('dashboard.main_category.main_categories') !!}</th>
                        <th>{!! trans('dashboard.main_category.price') !!}</th>
                        <th>{!! trans('dashboard.general.added_date') !!}</th>
                        <th>{!! trans('dashboard.general.control') !!}</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach ($main_categories as $main_category)
                    <tr class="{{ $main_category->id }}">
                        <td>{{ $loop->iteration }}</td>


                        <td>{{ $main_category->name }}</td>
                        <td>@if(is_null($main_category->price) && $main_category->is_free)
                              {{ trans('dashboard.main_category.service_is_free') }}
                              @elseif($main_category->has_sub_category == false && $main_category->is_free ==false)
                                {{ $main_category->price }}
                              @else
                               {{  trans('dashboard.main_category.price_in_sub_category') }}
                            @endif
                         </td>
                        <td>
                            <div class="badge badge-primary badge-md mr-1 mb-1">{{ $main_category->created_at->format("Y-m-d") }}</div>
                        </td>
                        <td class="justify-content-center">
                            <a onclick="deleteItem('{{ $main_category->id }}' , '{{ route('dashboard.main_category.destroy',$main_category->id) }}')" class="text-danger" title="{!! trans('dashboard.general.delete') !!}">
                                <i class="fas fa-trash-alt font-medium-3"></i>
                            </a>
                            <a href="{!! route('dashboard.main_category.edit',$main_category->id) !!}" class="text-primary mr-2" title="{!! trans('dashboard.general.edit') !!}">
                                <i class="fas fa-edit font-medium-3"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {!! $main_categories->links() !!}
        </div>
    </div>
</div>
@include('dashboard.layout.delete_modal')
@endsection


@include('dashboard.main_category.scripts')
