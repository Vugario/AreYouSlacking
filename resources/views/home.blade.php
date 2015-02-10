@extends('app')

@section('content')
	<div class="text-center">
		@if (Auth::guest())
			<a class="btn btn-default" href="{{ URL::to('slack/auth') }}">Connect with Slack</a>
		@else
			<p><img src="{{ Auth::user()->image }}" alt="{{ Auth::user()->name }}" style="border-radius: 50%"></p>
			<br>
			<h2>Hi, {{ Auth::user()->firstname }}</h2>
			<p>Would you like me to ask what you are doing?</p>
			<br>
			<br>
			<p><a href="{{ URL::to('slack/ask') }}" class="btn btn-primary">Yes please</a> <a href="#" class="btn btn-default">Nah im good</a></p>
		@endif
	</div>
@endsection
