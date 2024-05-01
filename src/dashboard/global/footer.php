<?php

namespace Sitecare;

function display_sitecare_footer()
{

    ob_start();

    ?>

            </div>

        </div>

    </div>

    <?php

    return ob_get_clean();

}
