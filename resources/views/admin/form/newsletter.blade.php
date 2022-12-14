@extends('layouts.admin')

@section('content')
    {!! form($form) !!}
@endsection

@section('inline-scripts')
<script>
    var ajaxCalls = ['check','test','send'];
    $("button[type=submit]").click(function(evt){
        var action = evt.target.value;
        if(-1 !== $.inArray(action, ajaxCalls) ) {
//                evt.preventDefault();
//                alert(action);
//                return true;
        }
    });
</script>
@endsection
