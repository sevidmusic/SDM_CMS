<div id="lockedwrapper">
    <div id="topmenu">
        <?php
        $menus = $sdmcore->sdmCoreLoadDataObject()->menus;
        // display menus who bleong to the main_content wrapper
        foreach ($menus as $menu) {
            if (isset($menu->wrapper) && $menu->wrapper === 'topmenu') {
                echo '<' . $menu->menuWrappingTagType . '>';
                foreach ($menu->menuItems as $menuItem) {
                    switch ($menuItem->destinationType) {
                        case 'internal':
                            echo '<' . $menuItem->menuItemWrappingTagType . '>' . '<a href="' . $sdmcore->getRootDirectoryUrl() . '/index.php?page=' . $menuItem->destination . '">' . $menuItem->menuItemDisplayName . '</a>' . '</' . $menuItem->menuItemWrappingTagType . '>';
                            break;

                        case 'external':
                            echo '<' . $menuItem->menuItemWrappingTagType . '>' . '<a href="' . $menuItem->destination . '">' . $menuItem->menuItemDisplayName . '</a>' . '</' . $menuItem->menuItemWrappingTagType . '>';
                            break;
                        default:
                            break;
                    }
                }
                echo '</' . $menu->menuWrappingTagType . '>';
            }
        }
        echo (isset($sdmassembler_themeContentObject->topmenu) ? $sdmassembler_themeContentObject->topmenu : '<a href="' . $sdmcore->getRootDirectoryUrl() . '/index.php?page=homepage">Homepage</a>');
        ?>
    </div>
    <div id='main_content'>
        <?php
        echo (isset($sdmassembler_themeContentObject->main_content) ? $sdmassembler_themeContentObject->main_content : '<!-- No Content -->');
        ?>
        <?php
        // display menus who bleong to the main_content wrapper
        foreach ($menus as $menu) {
            if (isset($menu->wrapper) && $menu->wrapper === 'topmenu') {
                echo '<' . $menu->menuWrappingTagType . '>';
                foreach ($menu->menuItems as $menuItem) {
                    switch ($menuItem->destinationType) {
                        case 'internal':
                            echo '<' . $menuItem->menuItemWrappingTagType . '>' . '<a href="' . $sdmcore->getRootDirectoryUrl() . '/index.php?page=' . $menuItem->destination . '">' . $menuItem->menuItemDisplayName . '</a>' . '</' . $menuItem->menuItemWrappingTagType . '>';
                            break;

                        case 'external':
                            echo '<' . $menuItem->menuItemWrappingTagType . '>' . '<a href="' . $menuItem->destination . '">' . $menuItem->menuItemDisplayName . '</a>' . '</' . $menuItem->menuItemWrappingTagType . '>';
                            break;
                        default:
                            break;
                    }
                }
                echo '</' . $menu->menuWrappingTagType . '>';
            }
        }
        ?>
    </div>
</div>