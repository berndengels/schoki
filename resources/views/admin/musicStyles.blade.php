
@extends('layouts.admin')

@section('content')
    @include('components.add')
    <br>
    {{ $data->links() }}
    <table class="table-responsive-sm">
        <tr class="table table-striped table-sm">
            <th scope="col">ID</th>
            <th scope="col" class="col-sm-auto">@sortablelink('name')</th>
            <th scope="col" class="d-none d-sm-table-cell">Benutzt von</th>
            <th scope="col" class="d-none d-sm-table-cell">Slug</th>
            <th scope="col" colspan="2">#</th>
        </tr>
        @foreach ($data as $item)
            <tr>
                <td>{{ $item->id }} </td>
                <td class="col-sm-auto">{{ $item->name }}</td>
                <td class="col-sm-auto">{{ $item->users->count() }}</td>
                <td class="d-none d-sm-table-cell">{{ $item->slug }}</td>

                @include('admin.templates.action')
            </tr>
        @endforeach
    </table>
    {{ $data->links() }}
@endsection
