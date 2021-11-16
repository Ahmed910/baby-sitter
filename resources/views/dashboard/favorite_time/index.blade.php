@extends('dashboard.layout.layout')

@section('content')
<div class="card border-info bg-transparent">

    <div class="card-body">
        <div class="d-flex justify-content-center">
            {!! $favorite_times->links() !!}
        </div>
        <div class="card-datatable table-responsive">
            <table class="datatables-custom table table-hover-animation" data-title="{{ trans('dashboard.favorite_time.favorite_times') }}" data-create_title="{{ trans('dashboard.favorite_time.add_favorite_time') }}" data-create_link="{{ route('dashboard.favorite_time.create') }}">
                <thead>
                    <tr class="text-center">
                        <th>#</th>

                        <th>{!! trans('dashboard.district.district') !!}</th>
                        <th>{!! trans('dashboard.available_day.available_day') !!}</th>
                        <th>{!! trans('dashboard.favorite_time.favorite_time') !!}</th>
                        <th>{!! trans('dashboard.general.added_date') !!}</th>
                        <th>{!! trans('dashboard.general.control') !!}</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach ($favorite_times as $favorite_time)
                    <tr class="{{ $favorite_time->id }}">
                        <td>{{ $loop->iteration }}</td>


                        <td>{{ $favorite_time->district->name }}</td>
                        <td>
                           {{ trans('dashboard.day_keys.'.$favorite_time->availableDay->day) }}
                        </td>
                        <td>{{ $favorite_time->time }}</td>
                        <td>
                            <div class="badge badge-primary badge-md mr-1 mb-1">{{ $favorite_time->created_at->format("Y-m-d") }}</div>
                        </td>
                        <td class="justify-content-center">
                            <a onclick="deleteItem('{{ $favorite_time->id }}' , '{{ route('dashboard.favorite_time.destroy',$favorite_time->id) }}')" class="text-danger" title="{!! trans('dashboard.general.delete') !!}">
                                <i class="fas fa-trash-alt font-medium-3"></i>
                            </a>
                            <a href="{!! route('dashboard.favorite_time.edit',$favorite_time->id) !!}" class="text-primary mr-2" title="{!! trans('dashboard.general.edit') !!}">
                                <i class="fas fa-edit font-medium-3"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {!! $favorite_times->links() !!}
        </div>
    </div>
</div>
@include('dashboard.layout.delete_modal')
@endsection


@include('dashboard.favorite_time.scripts')
