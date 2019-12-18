@extends('layouts.admin')

@section('content')
@include('components.newsletter-back')
<br>
<pre>
{!! $html !!}
</pre>

@include('components.newsletter-back')
@endsection
