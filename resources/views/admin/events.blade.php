
@extends('layouts.admin')

@section('content')
    @include('components.add')

    @if(isset($templateSelectForm) && null !== $templateSelectForm)
    {!! form($templateSelectForm) !!}
    @endif

    <br>
    {{ $data->links() }}
    <table class="table-responsive-sm mbs">
        <tr class="table table-striped table-sm">
            <th scope="col">ID</th>
            <th scope="col">@sortablelink('event_date','Datum')</th>
            <th scope="col" class="col-sm-auto">@sortablelink('title','Titel')</th>
            <th scope="col" class="d-none d-sm-table-cell">CreatedBy</th>
            <th scope="col" class="d-none d-sm-table-cell">UpdatedBy</th>
            <th scope="col" class="d-none d-sm-table-cell">Publiziert</th>
            <th scope="col" colspan="2">#</th>
        </tr>
    @foreach ($data as $item)
        <tr>
            <td>{{ $item->id }} </td>
            <td>{!! $item->event_date->format('d.m.Y') !!} </td>
            <td class="col-sm-auto">{{ $item->title }}</td>
            <td class="text-center d-none d-sm-table-cell">@if($item->createdBy) {{ $item->createdBy->username }} @else <br> @endif </td>
            <td class="text-center d-none d-sm-table-cell">@if($item->updatedBy) {{ $item->updatedBy->username }} @else <br> @endif </td>
            <td class="text-center d-none d-sm-table-cell"><i class="@if($item->is_published) ion-md-checkmark-circle-outline text-success @else ion-md-close-circle-outline text-danger @endif"></i></td>

            @include('admin.templates.action')
        </tr>
    @endforeach
    </table>
    {{ $data->links() }}
@endsection

