<?php

namespace Sitecare;

function display_sitecare_history()
{

    if (!is_admin()) {
        return;
    }

    echo display_sitecare_header();

    ?>

    <div class="headline history-headline">
        <?php echo file_get_contents(__DIR__ . "/../img/history.svg"); ?>
        <div class="headline-text">
            <?php _e("Score History", "sitecare-score") ?>
        </div>
    </div>

    <?php

    $history = get_sitecare_history();
    $body = json_decode($history['body']);
    echo $body->html;

    echo display_sitecare_footer();

}
