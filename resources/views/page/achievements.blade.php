<section class="container-12" data-controller="ViewModels/AchievementsViewModel" data-bind="if: visible">


    <section class="achievements">
        <h1>Все достижения</h1>

        <!--ko if: achievements().length == 0-->
            <article class="preloader-section"></article>
        <!--/ko-->

        <!--ko foreach: achievements-->
        <article class="achieve" data-bind="attr: {
            class: 'achieve ' + (users > 0 ? '' : 'achieve-disabled')
        }">
            <img src="#" data-bind="attr: {
                src: image + '?v=2',
                alt: title
            }"/>
            <h2 data-bind="text: title">Achieve</h2>
            <div class="achieve-description" data-bind="text: description">description</div>
            <span class="achieve-users">
                <!--ko if: users > 0 -->
                    Получили <!--ko text: users--><!--/ko--> человек
                <!--/ko-->
                <!--ko ifnot: users > 0 -->
                    Пока никто не получил
                <!--/ko-->
            </span>
        </article>
        <!--/ko-->
    </section>


</section>
