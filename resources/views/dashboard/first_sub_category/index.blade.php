@extends('dashboard.layout.layout')

@section('content')
<div class="card border-info bg-transparent">

    <div class="card-body">
        <div class="d-flex justify-content-center">
            {!! $first_sub_categories->links() !!}
        </div>
        <div class="card-datatable table-responsive">
            <table class="datatables-custom table table-hover-animation" data-title="{{ trans('dashboard.first_sub_category.first_sub_categories') }}" data-create_title="{{ trans('dashboard.first_sub_category.add_first_sub_category') }}" data-create_link="{{ route('dashboard.first_sub_category.create') }}">
                <thead>
                    <tr class="text-center">
                        <th>#</th>
                        <th>{!! trans('dashboard.first_sub_category.first_sub_categories') !!}</th>
                        <th>{!! trans('dashboard.first_sub_category.price') !!}</th>
                        <th>{!! trans('dashboard.main_category.main_category') !!}</th>
                        <th>{!! trans('dashboard.general.added_date') !!}</th>
                        <th>{!! trans('dashboard.general.control') !!}</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach ($first_sub_categories as $first_sub_category)
                    <tr class="{{ $first_sub_category->id }}">
                        <td>{{ $loop->iteration }}</td>


                        <td>{{ $first_sub_category->name }}</td>
                        <td>{{ $first_sub_category->price??trans('dashboard.first_sub_category.price_in_sub_category') }}</td>
                        <td>{{ $first_sub_category->mainCategory->name }}</td>
                        <td>
                            <div class="badge badge-primary badge-md mr-1 mb-1">{{ $first_sub_category->created_at->format("Y-m-d") }}</div>
                        </td>
                        <td class="justify-content-center">
                            <a onclick="deleteItem('{{ $first_sub_category->id }}' , '{{ route('dashboard.first_sub_category.destroy',$first_sub_category->id) }}')" class="text-danger" title="{!! trans('dashboard.general.delete') !!}">
                                <i class="fas fa-trash-alt font-medium-3"></i>
                            </a>
                            <a href="{!! route('dashboard.first_sub_category.edit',$first_sub_category->id) !!}" class="text-primary mr-2" title="{!! trans('dashboard.general.edit') !!}">
                                <i class="fas fa-edit font-medium-3"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {!! $first_sub_categories->links() !!}
        </div>
    </div>
</div>
@include('dashboard.layout.delete_modal')
@endsection


@include('dashboard.first_sub_category.scripts')
