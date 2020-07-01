<?php
    $menu_title = isset($item['menu_title']) ? $item['menu_title'] : "";
    $menu_icon  = isset($item['menu_icon']) ? $item['menu_icon'] : "";
    $menu_url   = isset($item['menu_url']) ? $item['menu_url'] : "";

    $status     = isset($status) ? $status : "";
?>
<li class="nav-item <?=$status?>">
    <a class="nav-link" href="<?=$menu_url?>">
        <i class="mdi mdi-<?=$menu_icon?> menu-icon"></i>
        <span class="menu-title"><?=$menu_title?></span>
    </a>
</li>