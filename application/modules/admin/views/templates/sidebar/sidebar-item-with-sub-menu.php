<?php
    $menu_title = isset($item['menu_title']) ? $item['menu_title'] : "";
    $menu_icon  = isset($item['menu_icon']) ? $item['menu_icon'] : "";
    $menu_id    = isset($item['menu_id']) ? $item['menu_id'] : "";
    
    $sub_menu   = isset($sub_menu) ? $sub_menu : "";
    $status     = isset($status) ? $status : "";
?>
<li class="nav-item <?=$status?>">
    <a class="nav-link" data-toggle="collapse" href="#<?=$menu_id?>" aria-expanded="false" aria-controls="<?=$menu_id?>">
        <i class="mdi mdi-<?=$menu_icon?> menu-icon"></i>
        <span class="menu-title"><?=$menu_title?></span>
        <i class="menu-arrow"></i>
    </a>
    <?=$sub_menu?>
</li>