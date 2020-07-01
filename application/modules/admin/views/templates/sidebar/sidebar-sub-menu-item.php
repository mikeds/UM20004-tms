<?php
    $menu_title = isset($item['menu_title']) ? $item['menu_title'] : "";
    $menu_url   = isset($item['menu_url']) ? $item['menu_url'] : "";
?>
<li class="nav-item"> <a class="nav-link" href="<?=$menu_url?>"> <?=$menu_title?> </a></li>
