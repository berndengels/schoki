
@extends('layouts.admin')

@section('content')
    @include('components.add')
    <br>
    {{ $data->links() }}
    <table class="table-responsive-sm">
        <tr class="table table-striped table-sm">
            <th scope="col">ID</th>
            <th scope="col" class="col-sm-auto">Name</th>
            <th scope="col" class="d-none d-sm-table-cell">Slug</th>
            <th scope="col" class="d-none d-sm-table-cell">Default EventTime</th>
            <th scope="col" class="d-none d-sm-table-cell">Default EventPrice</th>
            <th scope="col" class="d-none d-sm-table-cell">Enabled</th>
            <th scope="col" colspan="2">#</th>
        </tr>
    @foreach ($data as $item)
        <tr>
            <td>{{ $item->id }} </td>
            <td class="col-sm-auto">{{ $item->name }}</td>
            <td class="d-none d-sm-table-cell">{{ $item->slug }}</td>
            <td class="d-none d-sm-table-cell">{{ $item->default_time }}</td>
            <td class="d-none d-sm-table-cell">{{ $item->default_price }}</td>
            <td class="text-center d-none d-sm-table-cell"><i class="@if($item->is_published) ion-md-checkmark-circle-outline text-success @else ion-md-close-circle-outline text-danger @endif"></i></td>

            @include('admin.templates.action')
        </tr>
    @endforeach
    </table>
    {{ $data->links() }}
@endsection
