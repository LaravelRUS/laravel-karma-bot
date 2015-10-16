<header data-controller="Controllers/Partials/HeaderController">
    <section class="content">
        <a class="logo" href="{{ URL::to('/') }}" data-bind="click: home">
            Laravel<span>\Karma</span>
        </a>

        <nav class="breadcumbs">
            <!--ko if: title-->
            <span class="separator">&raquo;</span>
            <span class="label" data-bind="text: title">info</span>
            <!--/ko-->
        </nav>

        <nav>
            <a href="/achievements" data-bind="click: achievements">Достижения</a>


            <span class="dropdown" data-bind="attr: {
                class: 'dropdown ' + (dropdown()?'active':'')
            }, click: toggle">
                Ресурсы
                <nav>
                    <a href="https://gitter.im/LaravelRUS/chat">Чат</a>
                    <a href="http://laravel.su/docs">Документация</a>
                    <a href="http://vk.com/laravel_rus">Сообщество</a>
                </nav>
            </span>
        </nav>
    </section>
</header>