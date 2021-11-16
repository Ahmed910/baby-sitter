
@extends('dashboard.layout.layout')

@section('content')
<div class="card border-info bg-transparent">

    <div class="card-body">
        <div class="d-flex justify-content-center">
            {!! $car_types->links() !!}
        </div>
        <div class="card-datatable table-responsive">
            <table class="datatables-custom table table-hover-animation" data-title="{{ trans('dashboard.car_type.car_types') }}" data-create_title="{{ trans('dashboard.car_type.add_car_type') }}" data-create_link="{{ route('dashboard.car_type.create') }}">
                <thead>
                    <tr class="text-center">
                        <th>#</th>
                        <th>{!! trans('dashboard.car_type.car_types') !!}</th>

                        <th>{!! trans('dashboard.general.added_date') !!}</th>
                        <th>{!! trans('dashboard.general.control') !!}</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach ($car_types as $car_type)
                    <tr class="{{ $car_type->id }}">
                        <td>{{ $loop->iteration }}</td>


                        <td>{{ $car_type->name }}</td>
                        <td>
                            <div class="badge badge-primary badge-md mr-1 mb-1">{{ $car_type->created_at->format("Y-m-d") }}</div>
                        </td>
                        <td class="justify-content-center">
                            <a onclick="deleteItem('{{ $car_type->id }}' , '{{ route('dashboard.car_type.destroy',$car_type->id) }}')" class="text-danger" title="{!! trans('dashboard.general.delete') !!}">
                                <i class="fas fa-trash-alt font-medium-3"></i>
                            </a>
                            <a href="{!! route('dashboard.car_type.edit',$car_type->id) !!}" class="text-primary mr-2" title="{!! trans('dashboard.general.edit') !!}">
                                <i class="fas fa-edit font-medium-3"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {!! $car_types->links() !!}
        </div>
    </div>
</div>
@include('dashboard.layout.delete_modal')
@endsection


@include('dashboard.car_type.scripts')

