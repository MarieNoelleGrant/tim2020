<?php
/* Template d'article avec le format Par défaut */ ?>
<header class="article">
    <h1> <?php the_title() ?></h1>
</header>
<div class="contenu">
    <div class="extrait">
        <?php if ( has_excerpt() ) {
            the_excerpt();
        }?>
    </div>
    <?php the_content () ?>
</div>
<footer class="article">
    <?php the_category() ?>  -  <?php the_tags()   ?>  -  <?php the_date()   ?>  -  <?php the_author_posts_link() ?>  -  <?php edit_post_link() ?>
</footer>