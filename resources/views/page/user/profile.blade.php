<section class="profile">
    <nav class="breadcumbs">
        <a href="#" class="label orange" data-bind="click: $root.home">К поиску</a>
        <span class="separator">&laquo;</span>
        <span class="label white" data-bind="text: login">User Login</span>
    </nav>

    <aside class="profile-badge">
        <figure>
            <img src="https://github.com/identicons/jasonlong.png" alt="User" data-bind="attr: {
                src: avatar,
                alt: name
            }" />
        </figure>

        <h1 data-bind="text: login">User Login</h1>
        <h2 data-bind="text: name">User Name</h2>

        <!--ko if: achievements.items().length-->
        <section class="achievements" data-bind="foreach: achievements.items">
            <article class="achieve" data-bind="attr: {
                title: description + '\nПолучено ' + created_at.toLocaleString()
            }">
                <img data-bind="attr: {src: image, alt: title}" />
                <!--ko text: title--><!--/ko-->
            </article>
        </section>
        <!--/ko-->
    </aside>

    <section class="profile-content">
        <h2>Карма: <!--ko text: karma.length--><!--/ko--></h2>
        <article class="segment">
            <!--ko if: karma.length-->
                <h3>Последние поблагодарившие</h3>
                <section class="profile-list" data-bind="foreach: karma.items">
                    <!--ko with: user-->
                    <article class="profile-list-item" data-bind="click: profile">
                        <img src="https://github.com/identicons/jasonlong.png" data-bind="attr: {
                            src: avatar,
                            alt: login
                        }" />
                        <h3 data-bind="text: login"></h3>
                        <span class="description">
                            Сказал спасибо:
                            <time data-bind="text: $parent.created_at.toLocaleString()"></time>
                        </span>
                    </article>
                    <!--/ko-->
                </section>
            <!--/ko-->
            <!--ko ifnot: karma.length-->
                <h3>Этого пользователя ещё никто не благодарил</h3>
            <!--/ko-->
        </article>

        <h2>Благодарностей: <!--ko text: thanks.length--><!--/ko--></h2>
        <article class="segment">
            <!--ko if: thanks.length-->
                <h3>
                    <!--ko text: login--><!--/ko--> сказал "спасибо":
                </h3>
                <section class="profile-list" data-bind="foreach: thanks.items">
                    <!--ko with: user-->
                    <article class="profile-list-item" data-bind="click: profile">
                        <img src="https://github.com/identicons/jasonlong.png" data-bind="attr: {
                                src: avatar,
                                alt: login
                            }" />
                        <h3 data-bind="text: login"></h3>
                            <span class="description">
                                Поблагодарил:
                                <time data-bind="text: $parent.created_at.toLocaleString()"></time>
                            </span>
                    </article>
                    <!--/ko-->
                </section>
            <!--/ko-->
            <!--ko ifnot: thanks.length-->
                <h3>Этот пользователь ещё не говорил "спасибо"</h3>
            <!--/ko-->
        </article>
    </section>
</section>