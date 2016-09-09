<section class="container-12" data-controller="Controllers/SearchController" data-bind="if: visible">
    <article class="grid-12 search">
        <input type="text" data-bind="value: query, valueUpdate: 'input'" placeholder="Поиск по участникам" />

        <!--ko ifnot: users().length-->
            <!--ko if: query().trim().length > 0 -->
                <h2 class="search-error text-invalid">По вашему запросу ничего не найдено</h2>
            <!--/ko-->

            <!--ko ifnot: query().trim().length > 0 -->
                <h2 class="search-description">Введите имя пользователя</h2>
            <!--/ko-->
        <!--/ko-->
    </article>


    <section class="grid-12 search-users" data-bind="foreach: users">

        <article class="user" data-bind="click: $parent.load">
            <img src="https://github.com/identicons/jasonlong.png" alt="User"
                 data-bind="attr: { src: avatar, alt: name }" />

            <span class="login" data-bind="html: highlight.login">User Login</span>

            <span class="name" data-bind="html: highlight.name">User Name</span>


            <div class="user-karma" data-bind="text: (karma_count || '0'), attr: {
                title: 'Карма: ' + (karma_count || '0') +
                        '\nБлагодарностей: ' + (thanks_count || '0')
            }"></div>
        </article>

    </section>
</section>