<?php
    $menu_id    = isset($menu_id) ? $menu_id : "";
    $items      = isset($items) ? $items : "";
?>
<div class="collapse" id="<?=$menu_id?>">
    <ul class="nav flex-column sub-menu">
        <?=isset($items) ? $items : ""?>
    </ul>
</div>
