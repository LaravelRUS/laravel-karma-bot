@extends('layout.master')

@section('content')
    <section data-controller="Controllers/RequestNotifiesController" data-bind="foreach: messages">
        <article class="preloader" data-bind="attr: {class: 'preloader ' + (visible()?'visible':'')}">
            <!--ko text: text--><!--/ko-->
            <span class="circle"></span>
        </article>
    </section>

    <header data-controller="Controllers/HeaderController">
        <a class="logo" href="{{ URL::to('/') }}" data-bind="click: home">
            Laravel<span>\Karma</span>
        </a>

        <nav class="breadcumbs">
            <!--ko if: url-->
            <span class="separator">&raquo;</span>
            <span class="label" data-bind="text: '@' + url()">@user</span>
            <!--/ko-->
        </nav>

        <nav>
            <a href="http://laravel.su/docs">Документация</a>
            <a href="https://gitter.im/LaravelRUS/chat">Чат</a>
            <a href="http://vk.com/laravel_rus">Сообщество</a>
            <a href="http://laravel.su/users">Пользователи</a>
        </nav>
    </header>

    @include('page.search')
    @include('page.user')
@stop