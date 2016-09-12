<section class="container-12" data-controller="ViewModels/AchievementsViewModel" data-bind="if: visible">

    @{{#if: loading}}
    <section class="container-12 preloader-container">
        @include('partials.preloader')
    </section>
    @{{/if}}

    <section class="achievements">
        <h3>Все достижения</h3>

        @{{#foreach: achievements}}
        <article class="achieve grid-6"
                 title="@{{ user_count ? 'Получили ' + user_count + ' пользователей' : 'Пока никто не заработал это достижение' }}">
            <aside class="achieve-icon @{{ user_count || 'no-users' }}">
                <figure>
                    <span class="achieve-users">@{{ user_count }}</span>
                    <img src="#" alt="@{{ title }}" data-bind="attr: { src: image }" />
                    @include('partials.preloader')
                </figure>
            </aside>

            <div class="achieve-content">
                <h4 data-bind="text: title">Achieve</h4>
                <div class="achieve-description">@{{ description }}</div>
            </div>
        </article>
        @{{/foreach}}
    </section>


</section>
