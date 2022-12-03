
@extends('layouts.admin')

@section('content')
    @include('components.add')
    <br>
    {{ $data->links() }}
    <div class="d-inline-block mt-2 float-right">{!! form($addressCategoryForm) !!}</div>

    <table class="table-responsive-sm">
        <tr class="table table-striped table-sm">
            <th scope="col">ID</th>
            <th scope="col" class="col-sm-auto">@sortablelink('address_category_id','Type')</th>
            <th scope="col" class="col-sm-auto">@sortablelink('email')</th>
            <th scope="col" class="d-none d-sm-table-cell">TagID</th>
            <th scope="col" class="d-none d-sm-table-cell">@sortablelink('created_at','Erstellt')</th>
            <th scope="col" colspan="2">#</th>
        </tr>
    @foreach ($data as $item)
        <tr>
            <td>{{ $item->id }} </td>
            <td class="col-sm-auto">@if($item->addressCategory){{ $item->addressCategory->name }}@endif</td>
            <td class="col-sm-auto">{{ $item->email }}</td>
            <td class="d-none d-sm-table-cell">{{ $item->tag_id }}</td>
            <td class="d-none d-sm-table-cell">{{ $item->created_at->format('d.m.Y H:i') }}</td>

            @include('admin.templates.action')
        </tr>
    @endforeach
    </table>
    <div>
        {{ $data->links() }}
    </div>
@endsection
