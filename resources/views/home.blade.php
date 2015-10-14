@extends('layout.master')

@section('content')
    <section data-controller="Controllers/RequestNotifiesController" data-bind="foreach: messages">
        <article class="preloader" data-bind="attr: {class: 'preloader ' + (visible()?'visible':'')}">
            <!--ko text: text--><!--/ko-->
            <span class="circle"></span>
        </article>
    </section>

    <header>
        <a class="logo" href="{{ URL::to('/') }}">Laravel<span>\Karma</span></a>

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