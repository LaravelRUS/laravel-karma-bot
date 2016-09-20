<article class="grid-4 item @{{ karma_count > 0 ? '' : 'empty' }}"
         title="@{{ 'Карма: ' + (karma_count || '0') + '\nБлагодарностей: ' + (thanks_count || '0') }}"
         data-bind="on.click: $root.route('user', {user: login})">

    <aside class="avatar">
        <div class="counter">
            @{{ karma_count || '0' }}
        </div>

        <figure>
            <img alt="@{{ name }}" data-bind="attr: { src: avatar }" />
            @include('partials.preloader')
        </figure>
    </aside>


    <section class="content">
        <h4 class="login">@{{ login }}</h4>
        <h5 class="name">@{{ name }}</h5>

        <div class="user-achievements" data-bind="foreach: achievements">
            <div class="achieve">
                <img data-bind="attr: { src: image }" alt="@{{ title }}" />
            </div>
        </div>
    </section>
</article>