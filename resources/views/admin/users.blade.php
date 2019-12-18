
@extends('layouts.admin')

@section('content')
    @if( auth()->user() && auth()->user()->is_super_admin )
        @include('components.add')
    @endif

    <br>
    {{ $data->links() }}
    <div class="table-responsive-sm">
        <table class="table table-striped table-sm">
            <tr>
                <th scope="col">ID</th>
                <th scope="col" class="col-sm-auto">@sortablelink('username')</th>
                <th scope="col" class="d-none d-sm-table-cell">@sortablelink('email')</th>
                <th scope="col" class="d-none d-sm-table-cell">@sortablelink('last_login','Last Login')</th>
                <th scope="col" class="d-none d-sm-table-cell">@sortablelink('enabled','Aktiv')</th>
                <th scope="col" colspan="2">#</th>
            </tr>
        @foreach ($data as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td class="col-sm-auto">{{ $item->username }}</td>
                <td class="d-none d-sm-table-cell">{{ $item->email }}</td>
                <td class="d-none d-sm-table-cell">@if($item->last_login) {{ $item->last_login->format('d.m.Y H:i') }} @endif</td>
                <td class="d-none d-sm-table-cell"><i class="@if($item->enabled) ion-md-checkmark-circle-outline text-success @else ion-md-close-circle-outline text-danger @endif"></i></td>

                @include('admin.templates.action')
            </tr>
        @endforeach
        </table>
    </div>
    {{ $data->links() }}
@endsection
