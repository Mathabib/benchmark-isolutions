@extends('layouts.app')

@section('content')
<h1>Projects</h1>
<ul>
    @foreach($projects as $project)
    <li><a href="{{ route('projects.show', $project->id) }}">{{ $project->nama }}</a></li>
    @endforeach
</ul>
@endsection
