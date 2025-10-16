@extends('layouts.public')

@section('title', 'Concert - LaSala')

@section('content')
    <livewire:public.event-detail :eventId="$eventId" />
@endsection