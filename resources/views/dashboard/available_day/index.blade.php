@extends('dashboard.layout.layout')

@section('content')
<div class="card border-info bg-transparent">

    <div class="card-body">
        <div class="d-flex justify-content-center">
            {!! $available_days->links() !!}
        </div>
        <div class="card-datatable table-responsive">
            <table class="datatables-custom table table-hover-animation" data-title="{{ trans('dashboard.available_day.available_days') }}" data-create_title="{{ trans('dashboard.available_day.add_available_day') }}" data-create_link="{{ route('dashboard.available_day.create') }}">
                <thead>
                    <tr class="text-center">
                        <th>#</th>
                        <th>{!! trans('dashboard.available_day.available_days') !!}</th>
                        <th>{!! trans('dashboard.district.district') !!}</th>
                        <th>{!! trans('dashboard.general.added_date') !!}</th>
                        <th>{!! trans('dashboard.general.control') !!}</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach ($available_days as $available_day)
                    <tr class="{{ $available_day->id }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ trans('dashboard.day_keys.'.$available_day->day) }}</td>

                        <td>{{ $available_day->district->name }}</td>
                        <td>
                            <div class="badge badge-primary badge-md mr-1 mb-1">{{ $available_day->created_at->format("Y-m-d") }}</div>
                        </td>
                        <td class="justify-content-center">
                            <a onclick="deleteItem('{{ $available_day->id }}' , '{{ route('dashboard.available_day.destroy',$available_day->id) }}')" class="text-danger" title="{!! trans('dashboard.general.delete') !!}">
                                <i class="fas fa-trash-alt font-medium-3"></i>
                            </a>
                            <a href="{!! route('dashboard.available_day.edit',$available_day->id) !!}" class="text-primary mr-2" title="{!! trans('dashboard.general.edit') !!}">
                                <i class="fas fa-edit font-medium-3"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {!! $available_days->links() !!}
        </div>
    </div>
</div>
@include('dashboard.layout.delete_modal')
@endsection


@include('dashboard.available_day.scripts')
