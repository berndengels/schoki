
@extends('layouts.admin')

@section('content')
    @include('components.back')
    <div class="clearfix">
        <table>
            <tr><th>ID</th><td>{{ $data->id }}</td></tr>
            <tr><th>Typ</th><td>{{ $data->addressCategory->name }}</td></tr>
            <tr><th>Name</th><td>{{ $data->name }}</td></tr>
            <tr><th>Email</th><td>{{ $data->email }}</td></tr>
            <tr><th>Token</th><td>{{ $data->token }}</td></tr>
            <tr><th>Erstellt</th><td>{{ $data->created_at }}</td></tr>
        </table>
    </div>
@endsection
