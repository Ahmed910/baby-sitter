@extends('dashboard.layout.layout')

@section('content')
<div class="card border-info bg-transparent">

    <div class="card-body">
        <div class="d-flex justify-content-center">
            {!! $features->links() !!}
        </div>
        <div class="card-datatable table-responsive">
            <table class="datatables-custom table table-hover-animation" data-title="{{ trans('dashboard.feature.features') }}" data-create_title="{{ trans('dashboard.feature.add_feature') }}" data-create_link="{{ route('dashboard.feature.create') }}">
                <thead>
                    <tr class="text-center">
                                <th>#</th>
                                <th>{!! trans('dashboard.feature.feature') !!}</th>

                                <th>{!! trans('dashboard.general.added_date') !!}</th>
                                <th>{!! trans('dashboard.general.control') !!}</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach ($features as $feature)
                    <tr class="{{ $feature->id }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $feature->name }}</td>

                        <td>
                            <div class="badge badge-primary badge-md mr-1 mb-1">{{ $feature->created_at->format("Y-m-d") }}</div>
                        </td>
                        <td class="justify-content-center">
                            <a onclick="deleteItem('{{ $feature->id }}' , '{{ route('dashboard.feature.destroy',$feature->id) }}')" class="text-danger" title="{!! trans('dashboard.general.delete') !!}">
                                <i class="fas fa-trash-alt font-medium-3"></i>
                            </a>
                            <a href="{!! route('dashboard.feature.edit',$feature->id) !!}" class="text-primary mr-2" title="{!! trans('dashboard.general.edit') !!}">
                                <i class="fas fa-edit font-medium-3"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {!! $features->links() !!}
        </div>
    </div>
</div>
@include('dashboard.layout.delete_modal')
@endsection


@include('dashboard.feature.scripts')

