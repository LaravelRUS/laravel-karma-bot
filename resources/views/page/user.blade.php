<section class="content" data-controller="Controllers/UserController" data-bind="if: visible">
    <!--ko if: error-->
        <h1 data-bind="text: error"></h1>
    <!--/ko-->

    <!--ko if: user()-->
        <!--ko ifnot: user().loaded-->
            <article class="preloader-section"></article>
        <!--/ko-->
        <!--ko if: user().loaded-->
            <!--ko with: user()-->
                @include('page.user.profile')
            <!--/ko-->
        <!--/ko-->
    <!--/ko-->
</section>