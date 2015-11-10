@extends('master')

@section('content')
    <h1>List of Posts</h1>
    <hr/>
    <ul>
        @forelse($posts as $post)
            <li>
                {{ $post->title }}
                <small>
                    by {{ $post->user->name }}
                </small>
            </li>
        @empty
            <p>There is no article!</p>
        @endforelse
    </ul>
@stop