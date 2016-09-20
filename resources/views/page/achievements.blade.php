<section class="container-12" data-controller="ViewModels/AchievementsViewModel" data-bind="if: visible">

    @{{#if: loading}}
    <section class="container-12 preloader-container">
        @include('partials.preloader')
    </section>
    @{{/if}}

    <section class="items-list achievements">
        <h3>Все достижения</h3>

        @{{#foreach: achievements}}
            @include('partials.achieve-list-items')
        @{{/foreach}}
    </section>


</section>
