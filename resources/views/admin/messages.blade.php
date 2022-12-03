
@extends('layouts.admin')

@section('extra-headers')
    <script type="text/javascript" src="{{ asset('js/admin.js') }}" charset="UTF-8"></script>
@endsection

@section('content')

    @include('components.add')
    @include('admin.form.filter.messages')
    <br>
    {{ $data->links() }}
    <table class="table-responsive-sm">
        <tr class="table table-striped table-sm">
            <th scope="col">ID</th>
            <th scope="col" class="col-sm-auto">@sortablelink('musicStyle.name', 'Musikrichtung')</th>
            <th scope="col" class="d-none d-sm-table-cell">@sortablelink('email')</th>
            <th scope="col" class="d-none d-sm-table-cell">@sortablelink('name')</th>
            <th scope="col" class="d-none d-sm-table-cell">Message</th>
            <th scope="col" class="d-none d-sm-table-cell">@sortablelink('created_at','Erstellt')</th>
            <th scope="col" colspan="2">#</th>
        </tr>
    @foreach ($data as $item)
        <tr>
            <td>{{ $item->id }} </td>
            <td class="col-sm-auto">@if($item->musicStyle){{ $item->musicStyle->name }}@endif</td>
            <td class="d-none d-sm-table-cell">{{ $item->email }}</td>
            <td class="d-none d-sm-table-cell">{{ $item->name }}</td>
            <td class="d-none d-sm-table-cell">{{ \Illuminate\Support\Str::limit($item->msg, 30) }}</td>
            <td class="d-none d-sm-table-cell">{{ $item->created_at->format('d.m.Y H:i') }}</td>
            @include('admin.templates.action.show')

            @include('admin.templates.action.delete')
        </tr>
    @endforeach
    </table>
    {{ $data->links() }}
@endsection
