<div id="lockedwrapper">
    <div id='main_content'>
        <?php
        echo $sdmassembler_themeContentObject->main_content;
        ?>
        <h3>Menus:</h3>
        <?php
        $menus = $sdmcore->sdmCoreLoadDataObject()->menus;
        // display menus who bleong to the main_content wrapper
        foreach ($menus as $menu) {
            if (isset($menu->wrapper) && $menu->wrapper === 'main_content') {
                foreach ($menu->menuItems as $menuItem) {
                    switch ($menuItem->destinationType) {
                        case 'internal':
                            echo '<p><a href="' . $sdmcore->getRootDirectoryUrl() . '/index.php?page=' . $menuItem->destination . '">' . $menuItem->menuItemDisplayName . '</a></p>';
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