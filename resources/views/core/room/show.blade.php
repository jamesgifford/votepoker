@extends('spark::layouts.app')

@section('content')

<vote-room :room="{{ $room }}" :current-user="{{ $currentUser }}"></vote-room>

@endsection