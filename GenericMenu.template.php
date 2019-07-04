
<?php
/**
 * Simple Machines Forum (SMF)
 *
 * @package SMF
 * @author Simple Machines
 * @copyright 2011 Simple Machines
 * @license http://www.simplemachines.org/about/smf/license.php BSD
 *
 * @version 2.0
 */

// This contains the html for the side bar of the admin center, which is used for all admin pages.
function template_generic_menu_sidebar_above()
{
  global $context, $settings, $options, $scripturl, $txt, $modSettings;

  // This is the main table - we need it so we can keep the content to the right of it.
  echo '
  <div class="container">
    <div id="main_container" class="columns">
      <div id="left_admsection" class="menu column is-3 pt-0">
        <span id="admin_menu"></span>';

  // What one are we rendering?
  $context['cur_menu_id'] = isset($context['cur_menu_id']) ? $context['cur_menu_id'] + 1 : 1;
  $menu_context = &$context['menu_data_' . $context['cur_menu_id']];

  // For every section that appears on the sidebar...
  $firstSection = true;
  foreach ($menu_context['sections'] as $section)
  {
    // Show the section header - and pump up the line spacing for readability.
    echo '<p class="menu-label is-flex">';

    if ($firstSection && !empty($menu_context['can_toggle_drop_down']))
    {
      echo '
      <span class="flex-grow-1">', $section['title'],'</span>
      <a href="', $menu_context['toggle_url'], '">
        <span class="icon button is-secondary">
          <span class="fa fa-angle-double-right"></span>
        </span>
      </a>';
    }
    else
    {
      echo $section['title'];
    }

    echo '
    </p>
    <ul class="menu-list">';

    // For every area of this section show a link to that area (bold if it's currently selected.)
    foreach ($section['areas'] as $i => $area)
    {
      // Not supposed to be printed?
      if (empty($area['label']))
        continue;

      echo '
      <li>';

      // Is this the current area, or just some area?
      if ($i == $menu_context['current_area'])
      {
        echo '
            <a class="is-active" href="', isset($area['url']) ? $area['url'] : $menu_context['base_url'] . ';area=' . $i, $menu_context['extra_parameters'], '">', $area['label'], '</a>';

        if (empty($context['tabs']))
          $context['tabs'] = isset($area['subsections']) ? $area['subsections'] : array();
      }
      else
        echo '
            <a href="', isset($area['url']) ? $area['url'] : $menu_context['base_url'] . ';area=' . $i, $menu_context['extra_parameters'], '">', $area['label'], '</a>';

      echo '
          </li>';
    }

    echo '
        </ul>
      ';

    $firstSection = false;
  }

  // This is where the actual "main content" area for the admin section starts.
  echo '
    </div>
    <div id="main_admsection" class="column is-9">';

  // If there are any "tabs" setup, this is the place to shown them.
  if (!empty($context['tabs']) && empty($context['force_disable_tabs']))
    template_generic_menu_tabs($menu_context);
}

// Part of the sidebar layer - closes off the main bit.
function template_generic_menu_sidebar_below()
{
  global $context, $settings, $options;

  echo '
    </div>
    </div>
  </div>';
}

// This contains the html for the side bar of the admin center, which is used for all admin pages.
function template_generic_menu_dropdown_above()
{
  global $context, $settings, $options, $scripturl, $txt, $modSettings;

  // Which menu are we rendering?
  $context['cur_menu_id'] = isset($context['cur_menu_id']) ? $context['cur_menu_id'] + 1 : 1;
  $menu_context = &$context['menu_data_' . $context['cur_menu_id']];

  echo '
  <div id="admin_menu">
    <div class="container">
      <nav class="navbar is-light is-pensieve" role="navigation" aria-label="admin navigation">
        <div class="burger navbar-burger" data-target="adminMenu">
          <span></span>
          <span></span>
          <span></span>
        </div>
        <div class="navbar-menu" id="adminMenu">
          ';

  // Main areas first.
  foreach ($menu_context['sections'] as $section)
  {
    if ($section['id'] == $menu_context['current_section'])
    {
      // Open the dropdown
      echo '
      <div class="navbar-item has-dropdown is-hoverable">
        <a class="navbar-link is-active" href="#">', $section['title'] , '</a>
      ';
    }
    else
      echo '
      <div class="navbar-item has-dropdown is-hoverable">
        <a class="navbar-link" href="#">', $section['title'] , '</a>
      ';

    echo '<div class="navbar-dropdown is-boxed">';

      // For every area of this section show a link to that area (bold if it's currently selected.)
      $additional_items = 0;
      foreach ($section['areas'] as $i => $area)
      {
        // Not supposed to be printed?
        if (empty($area['label']))
          continue;

        /* echo  '
          <div', (++$additional_items > 6) ? ' class="navbar-dropdown is-boxed"' : ' class="navbar-dropdown is-boxed"' ,'>'; */

        // Is this the current area, or just some area?
          if ($i == $menu_context['current_area'])
          {
            echo '
              <a class="navbar-item is-active" href="', (isset($area['url']) ? $area['url'] : $menu_context['base_url'] . ';area=' . $i), $menu_context['extra_parameters'], '">', $area['icon'], $area['label'], !empty($area['subsections']) ? '...' : '', '</a>';

            if (empty($context['tabs']))
              $context['tabs'] = isset($area['subsections']) ? $area['subsections'] : array();
            }
            else
              echo '
                <a class="navbar-item" href="', (isset($area['url']) ? $area['url'] : $menu_context['base_url'] . ';area=' . $i), $menu_context['extra_parameters'], '">', $area['icon'], $area['label'], !empty($area['subsections']) ? '...' : '', '</a>';
      }
      echo '</div><!-- end navbar-dropdown -->'; // end navbar-dropdown
      echo '</div><!-- end navbar-item -->'; // end navbar-item
  }
  echo '</div><!-- end navbar-menu -->'; // end navbar-menu

  if (!empty($menu_context['can_toggle_drop_down']))
    echo '
    <div class="navbar-end is-hidden-touch m-3">
      <a href="', $menu_context['toggle_url'], '">
        <span class="icon button is-secondary">
          <span class="fa fa-angle-double-left"></span>
        </span>
      </a>
    </div>
    ';

  echo '</nav><!-- end navbar -->'; // end navbar
  echo '</div><!-- end container -->'; // end container
  echo '</div><!-- end #admin_menu-->'; // end #admin_menu

  // This is the main table - we need it so we can keep the content to the right of it.
  echo '
<div id="admin_content" class="container mt-4 mb-4 pb-4">';

  // It's possible that some pages have their own tabs they wanna force...
  if (!empty($context['tabs']))
    template_generic_menu_tabs($menu_context);
}

// Part of the admin layer - used with admin_above to close the table started in it.
function template_generic_menu_dropdown_below()
{
  global $context, $settings, $options;

  echo '
</div>';
}

// Some code for showing a tabbed view.
function template_generic_menu_tabs(&$menu_context)
{
  global $context, $settings, $options, $scripturl, $txt, $modSettings;

  // Handy shortcut.
  $tab_context = &$menu_context['tab_data'];

  echo '
  <div class="cat_bar">
    <h2 class="title is-4 mb-4">';

  // Exactly how many tabs do we have?
  foreach ($context['tabs'] as $id => $tab)
  {
    // Can this not be accessed?
    if (!empty($tab['disabled']))
    {
      $tab_context['tabs'][$id]['disabled'] = true;
      continue;
    }

    // Did this not even exist - or do we not have a label?
    if (!isset($tab_context['tabs'][$id]))
      $tab_context['tabs'][$id] = array('label' => $tab['label']);
    elseif (!isset($tab_context['tabs'][$id]['label']))
      $tab_context['tabs'][$id]['label'] = $tab['label'];

    // Has a custom URL defined in the main admin structure?
    if (isset($tab['url']) && !isset($tab_context['tabs'][$id]['url']))
      $tab_context['tabs'][$id]['url'] = $tab['url'];
    // Any additional paramaters for the url?
    if (isset($tab['add_params']) && !isset($tab_context['tabs'][$id]['add_params']))
      $tab_context['tabs'][$id]['add_params'] = $tab['add_params'];
    // Has it been deemed selected?
    if (!empty($tab['is_selected']))
      $tab_context['tabs'][$id]['is_selected'] = true;
    // Does it have its own help?
    if (!empty($tab['help']))
      $tab_context['tabs'][$id]['help'] = $tab['help'];
    // Is this the last one?
    if (!empty($tab['is_last']) && !isset($tab_context['override_last']))
      $tab_context['tabs'][$id]['is_last'] = true;
  }

  // Find the selected tab
  foreach ($tab_context['tabs'] as $sa => $tab)
  {
    if (!empty($tab['is_selected']) || (isset($menu_context['current_subsection']) && $menu_context['current_subsection'] == $sa))
    {
      $selected_tab = $tab;
      $tab_context['tabs'][$sa]['is_selected'] = true;
    }
  }

  // Show an icon and/or a help item?
  if (!empty($selected_tab['icon']) || !empty($tab_context['icon']) || !empty($selected_tab['help']) || !empty($tab_context['help']))
  {

    if (!empty($selected_tab['icon']) || !empty($tab_context['icon']))
      echo '<img src="', $settings['images_url'], '/icons/', !empty($selected_tab['icon']) ? $selected_tab['icon'] : $tab_context['icon'], '" alt="" class="icon" />';

    if (!empty($selected_tab['help']) || !empty($tab_context['help']))
      echo '
        <a href="', $scripturl, '?action=helpadmin;help=', !empty($selected_tab['help']) ? $selected_tab['help'] : $tab_context['help'], '" onclick="return reqWin(this.href);">
          <span class="icon">
            <span class="fa fa-question-circle is-size-5"></span>
          </span>
        </a>
      ';

    echo $tab_context['title'];
  }
  else
  {
    echo '
      ', $tab_context['title'];
  }

  echo '
    </h2>
  </div>';

  // Shall we use the tabs?
  if (!empty($settings['use_tabs']))
  {
    

    // The admin tabs.
    echo '
  <div class="tabs is-boxed" id="adm_submenus">
    <ul>';

    // Print out all the items in this tab.
    foreach ($tab_context['tabs'] as $sa => $tab)
    {
      if (!empty($tab['disabled']))
        continue;

      if (!empty($tab['is_selected']))
      {
        echo '
      <li class="is-active">
        <a class="active firstlevel" href="', isset($tab['url']) ? $tab['url'] : $menu_context['base_url'] . ';area=' . $menu_context['current_area'] . ';sa=' . $sa, $menu_context['extra_parameters'], isset($tab['add_params']) ? $tab['add_params'] : '', '">
          <span class="firstlevel">', $tab['label'], '</span>
          </a>
      </li>';
      }
      else
        echo '
      <li>
        <a class="firstlevel" href="', isset($tab['url']) ? $tab['url'] : $menu_context['base_url'] . ';area=' . $menu_context['current_area'] . ';sa=' . $sa, $menu_context['extra_parameters'], isset($tab['add_params']) ? $tab['add_params'] : '', '"><span class="firstlevel">', $tab['label'], '</span></a>
      </li>';
    }

    // the end of tabs
    echo '
    </ul>
  </div>';

  echo '
  <div class="notification is-size-6-5 p-2">
    ', !empty($selected_tab['description']) ? $selected_tab['description'] : $tab_context['description'], '
  </div>';
  }
  // ...if not use the old style
  else
  {
    echo '
  <div class="tabs">';

    // Print out all the items in this tab.
    foreach ($tab_context['tabs'] as $sa => $tab)
    {
      if (!empty($tab['disabled']))
        continue;

      if (!empty($tab['is_selected']))
      {
        echo '
    <img src="', $settings['images_url'], '/selected.gif" alt="*" /> <strong><a href="', isset($tab['url']) ? $tab['url'] : $menu_context['base_url'] . ';area=' . $menu_context['current_area'] . ';sa=' . $sa, $menu_context['extra_parameters'], '">', $tab['label'], '</a></strong>';
      }
      else
        echo '
    <a href="', isset($tab['url']) ? $tab['url'] : $menu_context['base_url'] . ';area=' . $menu_context['current_area'] . ';sa=' . $sa, $menu_context['extra_parameters'], '">', $tab['label'], '</a>';

      if (empty($tab['is_last']))
        echo ' | ';
    }

    echo '
  </div>
  <div class="notification is-size-6-5 p-2">', isset($selected_tab['description']) ? $selected_tab['description'] : $tab_context['description'], '</div>';
  }
}

?>