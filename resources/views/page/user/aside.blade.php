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
    <section class="profile-achievements" data-bind="
        click: $root.achievements,
        foreach: achievements.items
    ">
        <article class="achieve" data-bind="attr: {
            title: title + '\nПолучено ' + created_at.toLocaleString()
        }">
            <img data-bind="attr: {src: image, alt: title}" />
        </article>
    </section>
    <!--/ko-->
</aside>