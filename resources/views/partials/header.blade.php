<header data-controller="ViewModels/HeaderViewModel">
    <h1>Laravel.Karma</h1>

    <a class="logo" href="{{ URL::to('/') }}" data-bind="on.click: route('home')">
        Laravel<span>\Karma</span>
    </a>

    <nav class="breadcumbs">
        <!--ko if: title-->
            <span class="label" data-bind="text: title">page</span>
        <!--/ko-->
    </nav>

    <nav>
        <a href="{{ route('achievements') }}"
           data-bind="on.click: route('achievements')">Достижения</a>

        {{--
        <span class="dropdown" data-bind="click: toggle, attr: {
            class: 'dropdown ' + (dropdown() ? 'active':'')
        }">
            Ресурсы
            <nav>
                <a href="https://gitter.im/LaravelRUS/chat">Чат</a>
                <a href="http://laravel.su/docs">Документация</a>
                <a href="http://vk.com/laravel_rus">Сообщество</a>
            </nav>
        </span>
        --}}
    </nav>
</header>