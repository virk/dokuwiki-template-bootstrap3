<?php
/**
 * DokuWiki Bootstrap3 Template: Navbar
 *
 * @link     http://dokuwiki.org/template:bootstrap3
 * @author   Giuseppe Di Terlizzi <giuseppe.diterlizzi@gmail.com>
 * @license  GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */

// must be run from within DokuWiki
if (!defined('DOKU_INC')) die();

global $lang;
global $TEMPLATE;

$navbar_labels    = $TEMPLATE->getConf('navbarLabels');
$navbar_classes   = array();
$navbar_classes[] = ($TEMPLATE->getConf('fixedTopNavbar') ? 'navbar-fixed-top' : null);
$navbar_classes[] = ($TEMPLATE->getConf('inverseNavbar')  ? 'navbar-inverse'   : 'navbar-default');
$home_link        = ($TEMPLATE->getConf('homePageURL') ? $TEMPLATE->getConf('homePageURL') : wl());

?>
<!-- navbar -->
<nav id="dw__navbar" class="navbar <?php echo trim(implode(' ', $navbar_classes)) ?>" role="navigation">

    <div class="dw-container container<?php echo ($TEMPLATE->isFluidNavbar() ? '-fluid mx-5' : '') ?>">

        <div class="navbar-header">

            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <?php

                // get logo either out of the template images folder or data/media folder
                $logoSize  = array();
                $logo      = tpl_getMediaFile(array(':wiki:logo.png', ':logo.png', 'images/logo.png'), false, $logoSize);
                $title     = $conf['title'];
                $tagline   = ($conf['tagline']) ? '<span id="dw__tagline">'.$conf['tagline'].'</span>' : '';
                $logo_size = 'height="20"';

                if ($tagline) {
                $logo_size = 'height="32" style="margin-top:-5px"';
                }

                // display logo and wiki title in a link to the home page
                tpl_link(
                    $home_link,
                    '<img src="'.$logo.'" alt="'.$title.'" class="pull-left'.(($tagline) ? ' dw-logo-tagline' : '').'" id="dw__logo" '.$logo_size.' /> <span id="dw__title" '.($tagline ? 'style="margin-top:-5px"': '').'>'. $title . $tagline .'</span>',
                    'accesskey="h" title="[H]" class="navbar-brand"'
                );

            ?>

        </div>

        <div class="collapse navbar-collapse">

            <?php if ($TEMPLATE->getConf('showHomePageLink')) :?>
            <ul class="nav navbar-nav">
                <li<?php echo ((wl($ID) == $home_link) ? ' class="active"' : ''); ?>>
                    <?php tpl_link($home_link, '<i class="icon icon-home"></i> Home') ?>
                </li>
            </ul>
            <?php endif; ?>

            <?php
                echo $TEMPLATE->getNavbar(); // Include the navbar for different namespaces
                echo $TEMPLATE->getDropDownPage('dropdownpage');
            ?>

            <?php if(file_exists(dirname(__FILE__) . '/../navbar.html') && $TEMPLATE->getConf('useLegacyNavbar')): ?>
            <ul class="nav navbar-nav">
                <?php tpl_includeFile('navbar.html') ?>
            </ul>
            <?php endif; ?>

            <div class="navbar-right" id="dw__navbar_items">

                <?php
                    // Search form
                    include_once(template('tpl/navbar-searchform.php'));

                    // Admin Menu
                    include_once(template('tpl/menu-admin.php'));

                    // Tools Menu
                    include_once(template('tpl/menu-tools.php'));

                    // Theme Switcher Menu
                    include_once(template('tpl/theme-switcher.php'));

                    // Translation Menu
                    include_once(template('tpl/translation.php'));

                    // Add New Page
                    include_once(template('tpl/new-page.php'));
                ?>

                <ul class="nav navbar-nav">

                    <?php

                        if ($TEMPLATE->getConf('showEditBtn')) {

                            $action = null;

                            global $ACT;

                            if ($ACT == 'edit') {
                                $action = 'show';
                            }
                            if ($ACT == 'show') {
                                $action = 'edit';
                            }

                            if ($action && $edit_action = $TEMPLATE->getToolMenuItem('page', $action)) {

                                $edit_attr = $edit_action->getLinkAttributes();

                                $edit_html  = '<li class="hidden-xs"><a '. buildAttributes($edit_attr) . '>';
                                $edit_html .= \inlineSVG($edit_action->getSvg());
                                #$edit_html .= hsc($edit_action->getLabel());
                                $edit_html .= "</a></li>";

                                echo $edit_html;

                            }

                        }

                    ?>

                    <?php if ($TEMPLATE->getConf('fluidContainerBtn')): ?>
                    <li class="hidden-xs <?php echo ($TEMPLATE->getFluidContainerStatus() ? 'active' : '')?>">
                        <a href="#" class="btn-fluid-container" title="<?php echo tpl_getLang('expand_container') ?>">
                            <i class="icon icon-arrow-expand-all"></i><span class="<?php echo (in_array('expand', $TEMPLATE->getConf('navbarLabels')) ? '' : 'hidden-lg hidden-md hidden-sm') ?>"> <?php echo tpl_getLang('expand_container') ?></span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php if (empty($_SERVER['REMOTE_USER'])): ?>
                    <li>
                        <span class="dw__actions dw-action-icon">
                        <?php

                            $register_action = $TEMPLATE->getToolMenuItem('user', 'register');
                            $login_action    = $TEMPLATE->getToolMenuItem('user', 'login');

                            if ($register_action) {

                                $register_attr = $register_action->getLinkAttributes();
                                $register_attr['class'] .= ' btn btn-success navbar-btn';

                                $register_html  = '<a '. buildAttributes($register_attr) . '>';
                                $register_html .= \inlineSVG($register_action->getSvg());
                                $register_html .= '<span class="'. (in_array('register', $navbar_labels) ? null : 'sr-only') .'"> ' . hsc($register_action->getLabel()) . '</span>';
                                $register_html .= "</a>";

                                echo $register_html;

                            }

                            if (! $TEMPLATE->getConf('hideLoginLink') && $login_action) {

                                $login_attr = $login_action->getLinkAttributes();
                                $login_attr['class'] .= ' btn btn-default navbar-btn';

                                $login_html  = '<a '. buildAttributes($login_attr) . '>';
                                $login_html .= \inlineSVG($login_action->getSvg());
                                $login_html .= '<span class="'. (in_array('login', $navbar_labels) ? null : 'sr-only') .'"> ' . hsc($login_action->getLabel()) . '</span>';
                                $login_html .= "</a>";

                                echo $login_html;

                            }

                        ?>
                        </span>
                    </li>
                    <?php endif; ?>

                </ul>

                <?php if ($TEMPLATE->getConf('tocLayout') == 'navbar'): ?>
                <ul class="nav navbar-nav hide" id="dw__toc_menu">
                    <li class="dropdown">
                        <a href="<?php wl($ID) ?>" class="dropdown-toggle" data-target="#" data-toggle="dropdown" title="<?php echo $lang['toc'] ?>" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="icon icon-th-list"></i> <span class="hidden-lg hidden-md hidden-sm"><?php echo $lang['toc'] ?></span><span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu" style="max-height: 400px; overflow-y: auto">
                            <li class="dropdown-header"><i class="icon icon-th-list"></i> <?php echo $lang['toc'] ?></li>
                        </ul>
                    </li>
                </ul>
                <?php endif; ?>

                <?php include_once(template('tpl/menu-user.php')); ?>


            </div>

        </div>
    </div>
</nav>
<!-- navbar -->
