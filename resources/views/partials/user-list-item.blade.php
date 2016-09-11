<article class="grid-4 user" data-bind="attr: {
            title: 'Карма: ' + (karma_count || '0') +
                '\nБлагодарностей: ' + (thanks_count || '0')
        }, on.click: $root.route('user', {user: login})">

    <div class="user-avatar">
        <div class="user-karma @{{ karma_count > 0 ? '' : 'no-karma' }}">
            @{{ karma_count || '0' }}
        </div>

        <figure>
            <img alt="@{{ name }}" data-bind="attr: { src: avatar }" />
            @include('partials.preloader')
        </figure>
    </div>

    <div class="user-info">
        <div class="login">@{{ login }}</div>
        <div class="name">@{{ name }}</div>
        <div class="achievements" data-bind="foreach: achievements">
            <div class="achieve">
                <img data-bind="attr: { src: image }" alt="@{{ title }}" />
            </div>
        </div>
    </div>
</article>