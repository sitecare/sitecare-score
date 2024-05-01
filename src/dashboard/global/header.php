<?php

namespace Sitecare;

function display_sitecare_header()
{

    ob_start();

    ?>

    <div class="sitecare-dashboard">

        <div class="wrap">

            <div class="sitecare-dashboard-wrapper">

    <?php

    return ob_get_clean();

}
