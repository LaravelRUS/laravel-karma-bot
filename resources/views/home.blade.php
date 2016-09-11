@extends('layout.master')

@section('content')
    {{-- PARTIALS --}}
    @include('partials.notifies')
    @include('partials.header')

    {{-- PAGES --}}
    @include('page.search')
    {{--@include('page.user')--}}
    @include('page.achievements')
@stop