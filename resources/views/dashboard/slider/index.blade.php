@extends('dashboard.layout.layout')

@section('content')
<div class="card border-info bg-transparent">

    <div class="card-body">
        <div class="d-flex justify-content-center">
            {!! $sliders->links() !!}
        </div>
        <div class="card-datatable table-responsive">
            <table class="table table-hover-animation" data-title="{{ trans('dashboard.slider.sliders') }}" data-create_title="{{ trans('dashboard.slider.add_slider') }}" data-create_link="{{ route('dashboard.slider.create') }}">
                <thead>
                    <tr class="text-center">
                        <th>#</th>

                        <th>{!! trans('dashboard.slider.slider') !!}</th>

                        <th>{!! trans('dashboard.general.added_date') !!}</th>
                        <th>{!! trans('dashboard.general.control') !!}</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach ($sliders as $slider)
                    <tr class="{{ $slider->id }}">
                        <td>{{ $loop->iteration }}</td>

                        <td>
                            {{--  <a href="{{ $slider->image }}" data-fancybox="gallery">
                                <img src="{{ $slider->image }}" alt="" style="width:60px; height:60px;" class="img-preview rounded">
                            </a>  --}}
                            @if($slider->media->media_type == 'image')
                              <a href="{{ $slider->file }}" data-fancybox="gallery">
                                <img src="{{ $slider->file }}" alt="" style="width:60px; height:60px;" class="img-preview rounded">
                            </a>
                            @else
                                 <video width="200" height="150" controls>
                                <source src="{{ $slider->file }}">
                                <source src="movie.ogg" type="video/ogg">
                                Your browser does not support the video tag.
                              </video>
                            @endif

                        </td>

                        <td>
                            <div class="badge badge-primary badge-md mr-1 mb-1">{{ $slider->created_at->format("Y-m-d") }}</div>
                        </td>
                        <td class="justify-content-center">
                            <a onclick="deleteItem('{{ $slider->id }}' , '{{ route('dashboard.slider.destroy',$slider->id) }}')" class="text-danger" title="{!! trans('dashboard.general.delete') !!}">
                                <i class="fas fa-trash-alt font-medium-3"></i>
                            </a>
                            <a href="{!! route('dashboard.slider.edit',$slider->id) !!}" class="text-primary mr-2" title="{!! trans('dashboard.general.edit') !!}">
                                <i class="fas fa-edit font-medium-3"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {!! $sliders->links() !!}
        </div>
    </div>
</div>
@include('dashboard.layout.delete_modal')
@endsection


@include('dashboard.slider.scripts')
