<section class="container-12" data-controller="ViewModels/SearchViewModel" data-bind="if: visible">
    <article class="grid-12 search">
        <input type="text" value="@{{ search.text }}"
               data-bind="valueUpdate: 'input'" placeholder="Поиск по участникам" />

        @{{#if: search.text() && found().length === 0}}
            @{{#ifnot searchLoading}}
                <h2 class="search-error text-invalid">По вашему запросу ничего не найдено</h2>
            @{{/ifnot}}
        @{{/if}}

        @{{#ifnot: search.text}}
            <h2 class="search-description">Введите имя пользователя</h2>
        @{{/ifnot}}
    </article>


    @{{#if searchLoading}}
        <section class="grid-12 preloader-container">
            @include('partials.preloader')
        </section>
    @{{/if}}


    @{{#if found().length > 0}}
    <section class="items-list users">
        <h3>Результаты поиска по запросу &laquo;@{{ search.text().trim() }}&raquo;</h3>

        @{{#foreach found}}
            @include('partials.user-list-item')
        @{{/foreach}}
    </section>
    @{{/if}}


    <section class="items-list users">
        <h3>Карма: Топ 12</h3>

        @{{#if topLoading}}
        <section class="grid-12 preloader-container">
            @include('partials.preloader')
        </section>
        @{{/if}}


        @{{#foreach top}}
            @include('partials.user-list-item')
        @{{/foreach}}
    </section>

</section>