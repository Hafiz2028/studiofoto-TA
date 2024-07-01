@extends('front.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page Title')
@section('content')
    {{-- @include('front.layout.inc.categories') --}}
    @include('front.layout.inc.featured')


@endsection


