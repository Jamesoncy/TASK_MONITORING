@extends('layouts.master')

@section('title', 'Testing')

@section('sidebar')
    @parent

    <p>This is appended to the master sidebar.</p>
@endsection

@section('content')

@if($day == "Fr2ay")
	<h1>test2<h1>

	<h1>test2<h1>
@else
	<h1>test4<h1>
	<h1>test4<h1>
@endif
@foreach($drinks as $drink)
{{$drink}} <br>

@endforeach
<p>The date is {{date(' D M, Y')}}</p>
    <p>This is my body {{$name}}.</p>
@endsection
