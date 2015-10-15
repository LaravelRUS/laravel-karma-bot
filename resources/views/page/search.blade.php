<section class="content" data-controller="Controllers/SearchController" data-bind="if: visible">
    <article class="search">
        <input type="text" data-bind="value: query, valueUpdate: 'input'" placeholder="Поиск по участникам" />

        <!--ko ifnot: users().length-->
            <!--ko if: query().trim().length > 0 -->
                <h1 class="search-error">По вашему запросу ничего не найдено</h1>
            <!--/ko-->
            <!--ko ifnot: query().trim().length > 0 -->
                <h1 class="search-description">Введите имя пользователя</h1>
            <!--/ko-->
        <!--/ko-->
    </article>


    <section class="users" data-bind="foreach: users">

        <article class="user" data-bind="click: $parent.load">
            <img src="https://github.com/identicons/jasonlong.png" alt="User" data-bind="attr: {
                src: avatar,
                alt: name
            }" />

            <span class="login" data-bind="html: highlight.login">User Login</span>

            <span class="name" data-bind="html: highlight.name">User Name</span>


            <div class="user-karma" data-bind="text: (karma_count || '0'), attr: {
                title: 'Карма: ' + (karma_count || '0') +
                        '\nБлагодарностей: ' + (thanks_count || '0')
            }"></div>

            <nav>
                <a href="http://laravel.su/users" target="_blank"
                   data-bind="attr: {href: 'http://laravel.su/users?query=' + login}" >
                    На Laravel.su
                </a>

                <span class="separator">&nbsp;</span>

                <a href="https://gitter.im" target="_blank" data-bind="attr: {href: 'https://gitter.im' + url }">
                    Личное сообщение
                </a>
            </nav>
        </article>

    </section>
</section>