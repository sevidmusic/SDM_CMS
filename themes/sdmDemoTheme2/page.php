<div id="lockedwrapper">
    <div id="topmenu">
        <?php
        echo (isset($sdmassembler_themeContentObject->topmenu) ? $sdmassembler_themeContentObject->topmenu : '<a href="' . $sdmcore->sdmCoreGetRootDirectoryUrl() . '/index.php?page=homepage">Homepage</a>');
        ?>
    </div>
    <div id='main_content'>
        <?php
        echo (isset($sdmassembler_themeContentObject->main_content) ? $sdmassembler_themeContentObject->main_content : '<!-- No Content -->');
        ?>
        <?php
        $menus = $sdmcore->sdmCoreLoadDataObject()->menus;
        // display menus who bleong to the main_content wrapper
        foreach ($menus as $menu) {
            if (isset($menu->wrapper) && $menu->wrapper === 'main_content') {
                foreach ($menu->menuItems as $menuItem) {
                    switch ($menuItem->destinationType) {
                        case 'internal':
                            echo '<p><a href="' . $sdmcore->sdmCoreGetRootDirectoryUrl() . '/index.php?page=' . $menuItem->destination . '">' . $menuItem->menuItemDisplayName . '</a></p>';
                            break;

                        case 'external':
                            echo '<p><a href="' . $menuItem->destination . '">' . $menuItem->menuItemDisplayName . '</a></p>';
                            break;
                        default:
                            break;
                    }
                }
            }
        }
        ?>
    </div>
</div>