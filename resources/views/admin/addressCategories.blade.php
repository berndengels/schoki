
@extends('layouts.admin')

@section('content')
    @include('components.add')
    <br>
    {{ $data->links() }}

    <table class="table-responsive-sm">
        <tr class="table table-striped table-sm">
            <th scope="col">ID</th>
            <th scope="col" class="d-none d-sm-table-cell">Name</th>
            <th scope="col" colspan="2">#</th>
        </tr>
    @foreach ($data as $item)
        <tr>
            <td>{{ $item->id }} </td>
            <td class="col-sm-auto">{{ $item->name }}</td>

            @include('admin.templates.action')
        </tr>
    @endforeach
    </table>
    <div>
        {{ $data->links() }}
    </div>
@endsection
