<article class="grid-6 item @{{ users_count > 0 ? '' : 'empty' }}"
     title="@{{ users_count ? 'Получили ' + users_count + ' пользователей' : 'Пока никто не заработал это достижение' }}">


    <aside class="avatar">
        <div class="counter">
            @{{ users_count || '0' }}
        </div>

        <figure>
            <img alt="@{{ title }}" data-bind="attr: { src: image }" />
            @include('partials.preloader')
        </figure>
    </aside>


    <section class="content">
        <h4 class="title">@{{ title }}</h4>
        <h5 class="description">@{{ description }}</h5>
    </section>
</article>