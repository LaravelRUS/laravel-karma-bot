<header data-vm="HeaderViewModel">
    <h1>Laravel.Karma</h1>

    <a class="logo" href="{{ URL::to('/') }}">
        Laravel<span>\Karma</span>
    </a>

    <nav>
        <dropdown params="title: 'Аккаунт', links: accountLinks"></dropdown>
    </nav>
</header>