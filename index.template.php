<?php
/**
 * Simple Machines Forum (SMF)
 *
 * @package SMF
 * @author Simple Machines
 * @copyright 2011 Simple Machines
 * @license http://www.simplemachines.org/about/smf/license.php BSD
 *
 * @version 2.0.14
 */

/*  This template is, perhaps, the most important template in the theme. It
  contains the main template layer that displays the header and footer of
  the forum, namely with main_above and main_below. It also contains the
  menu sub template, which appropriately displays the menu; the init sub
  template, which is there to set the theme up; (init can be missing.) and
  the linktree sub template, which sorts out the link tree.

  The init sub template should load any data and set any hardcoded options.

  The main_above sub template is what is shown above the main content, and
  should contain anything that should be shown up there.

  The main_below sub template, conversely, is shown after the main content.
  It should probably contain the copyright statement and some other things.

  The linktree sub template should display the link tree, using the data
  in the $context['linktree'] variable.

  The menu sub template should display all the relevant buttons the user
  wants and or needs.

  For more information on the templating system, please see the site at:
  http://www.simplemachines.org/
*/

// Initialize the template... mainly little settings.
function template_init()
{
  global $context, $settings, $options, $txt, $user_info, $scripturl;

  /* Use images from default theme when using templates from the default theme?
    if this is 'always', images from the default theme will be used.
    if this is 'defaults', images from the default theme will only be used with default templates.
    if this is 'never' or isn't set at all, images from the default theme will not be used. */
  $settings['use_default_images'] = 'never';

  /* What document type definition is being used? (for font size and other issues.)
    'xhtml' for an XHTML 1.0 document type definition.
    'html' for an HTML 4.01 document type definition. */
  $settings['doctype'] = 'xhtml';

  /* The version this template/theme is for.
    This should probably be the version of SMF it was created for. */
  $settings['theme_version'] = '2.0';

  /* Set a setting that tells the theme that it can render the tabs. */
  $settings['use_tabs'] = true;

  /* Use plain buttons - as opposed to text buttons? */
  $settings['use_buttons'] = true;

  /* Show sticky and lock status separate from topic icons? */
  $settings['separate_sticky_lock'] = true;

  /* Does this theme use the strict doctype? */
  $settings['strict_doctype'] = false;

  /* Does this theme use post previews on the message index? */
  $settings['message_index_preview'] = false;

  /* Set the following variable to true if this theme requires the optional theme strings file to be loaded. */
  $settings['require_theme_strings'] = true;

  /* Define theme variants. */
  $settings['theme_variants'] = array('default', 'omen', 'gold');

  // SUBACCOUNTS
  $context['subaccount_dropdown'] = '';
  if (!empty($user_info['subaccounts']))
  {
     $context['subaccount_dropdown'] = '
      <form action="' . $scripturl . '?action=switchsubaccount" method="post" name="subaccount_drop" id="subaccount_drop" enctype="multipart/form-data">
        <div class="select">
          <select name="subaccount" size="1" onchange="document.subaccount_drop.submit()">
            <option selected="selected">' . $txt['change_subaccount'] . '</option>';
      foreach($user_info['subaccounts'] as $id => $subaccount)
        $context['subaccount_dropdown'] .= '
            <option value="' . $id . '">' . $subaccount['name'] . '</option>';
      $context['subaccount_dropdown'] .= '
          </select>
        </div>
        <input type="hidden" name="' . $context['session_var'] . '" value="' . $context['session_id'] . '" />
      </form>';
  }
}

// The main sub template above the content.
function template_html_above()
{
  global $context, $settings, $options, $scripturl, $txt, $modSettings;

  // Show right to left and the character set for ease of translating.
  echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml"', $context['right_to_left'] ? ' dir="rtl"' : '', '>
    <head>';

  // The ?fin20 part of this link is just here to make sure browsers don't cache it wrongly.
  /*echo '
  <link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/index', $context['theme_variant'], '.css?fin20" />';*/

  // Some browsers need an extra stylesheet due to bugs/compatibility issues.
  foreach (array('ie7', 'ie6', 'webkit') as $cssfix)
    if ($context['browser']['is_' . $cssfix])
      echo '
  <link rel="stylesheet" type="text/css" href="', $settings['default_theme_url'], '/css/', $cssfix, '.css" />';

  // RTL languages require an additional stylesheet.
  if ($context['right_to_left'])
    echo '
  <link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/rtl.css" />';

  // The ?fin20 part of this link is just here to make sure browsers don't cache it wrongly.

  echo '
  <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">';
  echo'
  <link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/bulma.css">';
  echo'
  <link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/style.css">';
  
  echo '
  <link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/style', $context['theme_variant'], '.css?fin20" />'; 

  // SUBACCOUNTS - this theme as a custom CSS file to re-style SubAccounts mod
  echo '
  <link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/subaccounts.css">';


  // Here comes the JavaScript bits!
  echo '
  <script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/script.js?fin20"></script>
  <script type="text/javascript" src="', $settings['theme_url'], '/scripts/theme.js?fin20"></script>
  <script type="text/javascript"><!-- // --><![CDATA[
    var smf_theme_url = "', $settings['theme_url'], '";
    var smf_default_theme_url = "', $settings['default_theme_url'], '";
    var smf_images_url = "', $settings['images_url'], '";
    var smf_scripturl = "', $scripturl, '";
    var smf_iso_case_folding = ', $context['server']['iso_case_folding'] ? 'true' : 'false', ';
    var smf_charset = "', $context['character_set'], '";', $context['show_pm_popup'] ? '
    var fPmPopup = function ()
    {
      if (confirm("' . $txt['show_personal_messages'] . '"))
        window.open(smf_prepareScriptUrl(smf_scripturl) + "action=pm");
    }
    addLoadEvent(fPmPopup);' : '', '
    var ajax_notification_text = "', $txt['ajax_in_progress'], '";
    var ajax_notification_cancel_text = "', $txt['modify_cancel'], '";
  // ]]></script>';

  // RESPONSIVE TABLES
    /*
  echo'
    <script type="text/javascript" language="Javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="', $settings['theme_url'], '/scripts/responsive-tables.js"></script>';
*/
  echo '
  <meta http-equiv="Content-Type" content="text/html; charset=', $context['character_set'], '" />
  <meta name="description" content="', $context['page_title_html_safe'], '" />', !empty($context['meta_keywords']) ? '
  <meta name="keywords" content="' . $context['meta_keywords'] . '" />' : '', '
  <title>', $context['page_title_html_safe'], '</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">';

  // Please don't index these Mr Robot.
  if (!empty($context['robot_no_index']))
    echo '
  <meta name="robots" content="noindex" />';

  // Present a canonical url for search engines to prevent duplicate content in their indices.
  if (!empty($context['canonical_url']))
    echo '
  <link rel="canonical" href="', $context['canonical_url'], '" />';

  // Show all the relative links, such as help, search, contents, and the like.
  echo '
  <link rel="help" href="', $scripturl, '?action=help" />
  <link rel="search" href="', $scripturl, '?action=search" />
  <link rel="contents" href="', $scripturl, '" />';

  // If RSS feeds are enabled, advertise the presence of one.
  if (!empty($modSettings['xmlnews_enable']) && (!empty($modSettings['allow_guestAccess']) || $context['user']['is_logged']))
    echo '
  <link rel="alternate" type="application/rss+xml" title="', $context['forum_name_html_safe'], ' - ', $txt['rss'], '" href="', $scripturl, '?type=rss;action=.xml" />';

  // If we're viewing a topic, these should be the previous and next topics, respectively.
  if (!empty($context['current_topic']))
    echo '
  <link rel="prev" href="', $scripturl, '?topic=', $context['current_topic'], '.0;prev_next=prev" />
  <link rel="next" href="', $scripturl, '?topic=', $context['current_topic'], '.0;prev_next=next" />';

  // If we're in a board, or a topic for that matter, the index will be the board's index.
  if (!empty($context['current_board']))
    echo '
  <link rel="index" href="', $scripturl, '?board=', $context['current_board'], '.0" />';

  // Output any remaining HTML headers. (from mods, maybe?)
  echo $context['html_headers'];

  echo '
  </head>
  <body>';
}

// Before BoardIndex.template.php begins...
function template_body_above()
{
  global $context, $settings, $options, $scripturl, $txt, $modSettings;

  echo'
  <header>
    <div class="skiplink">
      <a class="invisible button is-small is-dark" href="#maincontent">Skip to main content</a>
    </div>
  ';

  // Show the main menu
  template_menu();

  // BANNER, SITENAME, & NEWS
  echo'
  <div class="hero is-small is-primary is-pensieve is-bg">
    <div class="hero-body">
      <div class="container">
        <div class="columns align-items-center">
          <div class="column">
            <h1 class="title">
              <a href="', $scripturl, '">', empty($context['header_logo_url_html_safe']) ? $context['forum_name'] : '<img src="' . $context['header_logo_url_html_safe'] . '" alt="' . $context['forum_name'] . '" />', '</a>
            </h1>';
            if (!empty($settings['site_slogan']))
              echo' <p class="subtitle">', $settings['site_slogan'] ,'</p>';

            echo'
          </div>
          <div class="column">';

          // Show a random news item? (or you could pick one from news_lines...)
          if (!empty($settings['enable_news']))
            echo '
              <div>
                <p>', $context['random_news_line'], '</p>
              </div>';

          echo'
          </div>
        </div>
      </div>
    </div>
  </div>


  ';

  // PROFILE

  // If the user is logged in, display stuff like their name, new messages, etc.
    if ($context['user']['is_logged'])
    {

      // Maintenance mode?
      if ($context['in_maintenance'] && $context['user']['is_admin'])
        echo '
          <div class="container">
            <div class="notification is-warning mt-3 mb-3 p-2 has-text-centered">', $txt['maintain_mode_on'], '</div>
          </div>
        ';

      echo'
      <div class="navbar is-light is-pensieve">
        <div class="container">
          <div class="navbar-brand">
      ';

            // NAME AND AVATAR
            echo'
              <a class="navbar-item" href="', $scripturl ,'?action=profile">';
              // Avatar
              if (!empty($context['user']['avatar']))
                echo '', $context['user']['avatar']['image'],'';
              echo'
                <span class="ml-2 is-hidden-mobile">', $context['user']['name'] ,'</span>
              </a>
            ';

            // SUBACCOUNTS
            echo'
            <div class="navbar-item">
              
            ', $context['subaccount_dropdown'], '
            </div>
            '; // end subaccounts

        echo'
            <div class="navbar-item">
              <div class="field is-grouped">

                <p class="control">
                  <a href="', $scripturl, '?action=pm" class="buttons is-inline-flex">
                  <span class="button is-small is-primary">
                    <span class="fa fa-envelope"></span>
                    <span>';
                    // Unread messages?
                    if($context['user']['unread_messages'])
                      echo'
                      <span class="ml-2">', $context['user']['unread_messages'],'</span>
                      <span class="sr-only">unread messages</span></span>
                      '; // endif unread messages
                    echo'                   
                  </span>
                  
                  </a>
                </p>

                <p class="control">
                  <a href="', $scripturl, '?action=unread" class="button is-pensieve is-primary is-small" href="#">Unread</a>
                  <a href="', $scripturl, '?action=unreadreplies" class="button is-pensieve is-primary is-small" href="#">Replies</a>
                </p>

              </div>
            </div>
    </div>
      ';

      

    } // endif logged in

  echo'
      </div>
    </div>
  </header>
  '; // End Profile

  // BREADCRUMB
  theme_linktree();

  // BEGIN MAIN
  echo'
  <main class="section is-small" id="maincontent">
  ';
}

// THIS IS WHERE THE BOARD INDEX IS!

// After BoardIndex.template.php ends...
function template_body_below()
{
  global $context, $settings, $options, $scripturl, $txt, $modSettings;

  echo'
  </main>
  '; // End main


  // Show the "Powered by" and "Valid" logos, as well as the copyright. Remember, the copyright must be somewhere!
  echo '
  <footer class="footer">
    <div class="container">
      <div class="has-text-centered is-size-7 is-uppercase">', theme_copyright(), ' | <a href="https://github.com/sacarney/pensieve">Pensieve Theme</a> by Sarah Carney</div>
    </div>
  </footer>
  ';
}

function template_html_below()
{
  global $context, $settings, $options, $scripturl, $txt, $modSettings;

  echo '
  </body></html>';
}

// Show a linktree. This is that thing that shows "My Community | General Category | General Discussion"..
function theme_linktree($force_show = false)
{
  global $context, $settings, $options, $shown_linktree;

  // If linktree is empty, just return - also allow an override.
  if (empty($context['linktree']) || (!empty($context['dont_default_linktree']) && !$force_show))
    return;

  echo '
  <div class="section is-small">
    <div class="container">
      <nav class="breadcrumb is-small" aria-label="breadcrumbs">
        <ul>';

  // Each tree item has a URL and name. Some may have extra_before and extra_after.
  foreach ($context['linktree'] as $link_num => $tree)
  {
    echo '
      <li', ($link_num == count($context['linktree']) - 1) ? ' class=" "' : '', '>';

    // Show something before the link?
    if (isset($tree['extra_before']))
      echo $tree['extra_before'];

    // Show the link, including a URL if it should have one.
    echo $settings['linktree_link'] && isset($tree['url']) ? '
        <a href="' . $tree['url'] . '"><span>' . $tree['name'] . '</span></a>' : '<span>' . $tree['name'] . '</span>';

    // Show something after the link...?
    if (isset($tree['extra_after']))
      echo $tree['extra_after'];

    // Don't show a separator for the last one.
    if ($link_num != count($context['linktree']) - 1)
      echo '';

    echo '
      </li>';
  }
  echo '
      </ul>
    </nav>
  </div>
  </div>
  ';

  $shown_linktree = true;
}

// Show the menu up top. Something like [home] [help] [profile] [logout]...
function template_menu()
{
  global $context, $settings, $options, $scripturl, $txt;

  echo'
  <nav class="navbar pensieve-main-navbar">
    <div class="container">
      <div class="navbar-brand">
        <a href="', $scripturl, '" class="navbar-item is-hidden-desktop">
          <span class="icon is-medium">
            <span class="fa fa-home"></span>
          </span>
        </a>
        <div class="burger navbar-burger" data-target="mainMenu">
          <span></span>
          <span></span>
          <span></span>
        </div>
      </div>
      <div class="navbar-menu" id="mainMenu">
  ';
      foreach ($context['menu_buttons'] as $act => $button) {
        if (!empty($button['sub_buttons'])) {
          echo'
          <div class="navbar-item has-dropdown is-hoverable">
             <a class="navbar-link" href="', $button['href'], '">', $button['title'], '</a>
             <div class="navbar-dropdown is-boxed">
          ';
            foreach ($button['sub_buttons'] as $childbutton) {
              echo'
              <a class="navbar-item" href="', $childbutton['href'], '">', $childbutton['title'], '</a>
              ';
            }
          echo'
            </div>
          </div>
          ';
        }
        else {
          echo'
          <a class="navbar-item" href="', $button['href'], '">', $button['title'], '</a>
          ';
        }
      }

        echo'
      </div>
    </div>
  </nav>
  ';
}

// Generate a strip of buttons.
function template_button_strip($button_strip, $direction = 'top', $strip_options = array())
{
  global $settings, $context, $txt, $scripturl;

  if (!is_array($strip_options))
    $strip_options = array();

  // List the buttons in reverse order for RTL languages.
  if ($context['right_to_left'])
    $button_strip = array_reverse($button_strip, true);

  // Create the buttons...
  $buttons = array();
  foreach ($button_strip as $key => $value)
  {
    if (!isset($value['test']) || !empty($context[$value['test']]))

      $buttons[] = '
      <a' . (isset($value['id']) ? ' id="button_strip_' . $value['id'] . '"' : '') . ' class="button is-small ' . $value['class'] .' button_' . $key . (isset($value['active']) ? ' ' : ' ') . '" href="' . $value['url'] . '"' . (isset($value['custom']) ? ' ' . $value['custom'] : '') . ' title="' . $txt[$value['text']] . '">
        <span class="icon is-small m-0">
          <i class="fa '. $value['icon'] .'"></i>
        </span>
        <span class="' . $value['hidden'] . ' is-hidden-touch ml-1">' . $txt[$value['text']] . '</span>
      </a>
      ';
  }

  // No buttons? No button strip either.
  if (empty($buttons))
    return;

  // Make the last one, as easy as possible.
  $buttons[count($buttons) - 1] = str_replace('<span>', '<span class="last">', $buttons[count($buttons) - 1]);

  echo '
    <div class="thread-tools-menu"', (empty($buttons) ? ' style="display: none;"' : ''), (!empty($strip_options['id']) ? ' id="' . $strip_options['id'] . '"': ''), '>',
        implode('', $buttons), '
    </div>';
}

?>