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

function template_main()
{
  global $context, $settings, $options, $txt, $scripturl, $modSettings;

  // BOARD INDEX
  echo'
  <div class="container">
  ';

  // NEWS
  if ($settings['show_newsfader'] && !empty($context['fader_news_lines']))
  {
    echo '
    <div class="notification">
      <h2 class="title is-5">', $txt['news'], '</h2>
      <ul', empty($options['collapse_news_fader']) ? '' : ' style="display: none;"', '>
      ';

      foreach ($context['news_lines'] as $news)
        echo '
        <li class="mb-3">', $news, '</li>
        ';
      echo '
      </ul>
    </div>
    ';
  } 

  foreach ($context['categories'] as $category)
  {
    if (empty($category['boards']) && !$category['is_collapsed'])
      continue;

    /*
    // Show a collapse link if collapsing is allowed
    if ($category['can_collapse'])
      echo '
      <a class="collapse" href="', $category['collapse_href'], '">', $category['collapse_image'], '</a>';
    */

    // Build the category
    echo '
    <div id="category_', $category['id'], '" class="card mb-4">
      <div class="card-header">
        <h2 class="card-header-title title is-4">
          <span class="is-size-5-mobile href="">', $category['name'], '</span>
        </h2>
      </div>
      ';

    // Assuming the category hasn't been collapsed...
    if (!$category['is_collapsed'])
    {

      // Build each board
      foreach ($category['boards'] as $board)
      {
        echo '
        <div class="card-content " id="category_', $category['id'], '_boards">
          <div class="columns is-mobile">
        ';

        // First column
        echo '
        <div class="column is-narrow">
        ';

          // New
          if ($board['new'] || $board['children_new'])
          echo '
          <span class="icon has-new" title="New Posts">
            <i class="fa fa-star"></i>
            <span class="sr-only">This board has new posts</span>
          </span>
          ';

          // Redirects
          elseif ($board['is_redirect'])
          echo '
          <span class="icon has-redirect" title="Redirects">
            <i class="fa fa-link"></i>
            <span class="sr-only">This is a redirect</span>
          </span>
          ';

          // No New
          else
          echo '
          <span class="icon has-no-new" title="No New Posts">
            <i class="fa fa-star-o"></i>
            <span class="sr-only">This board has no new posts</span>
          </span>
          ';

        echo '
        </div>
        ';

        // Board info column
        echo '
        <div class="column is-6-desktop is-10-narrow">
          <h3 class="title is-5 mb-1">
            <a class="is-size-6-mobile" href="', $board['href'], '" name="b', $board['id'], '">', $board['name'], '</a>
          </h3>
          ';

          if (!empty($board['description']))
            echo'
            <p class="is-muted mb-0">', $board['description'] , '</p>
            ';

          // Board Moderators
          if (!empty($board['moderators']))
            echo '
            <p class="mb-0">
              <span class="is-size-7 is-muted is-uppercase">', count($board['moderators']) == 1 ? $txt['moderator'] : $txt['moderators'], ': </span>
              ', implode(', ', $board['link_moderators']), '
            </p>
            ';
            
          // Child Boards
          if (!empty($board['children']))
          {
            // Build the list of child boards
            $children = array();
            foreach ($board['children'] as $child)
            {
              if (!$child['is_redirect'])
                $child['link'] = '<a href="' . $child['href'] . '" ' . ($child['new'] ? 'class="" ' : '') . 'title="' . ($child['new'] ? $txt['new_posts'] : $txt['old_posts']) . ' (' . $txt['board_topics'] . ': ' . comma_format($child['topics']) . ', ' . $txt['posts'] . ': ' . comma_format($child['posts']) . ')">' . $child['name'] . ($child['new'] ? '</a> <a href="' . $scripturl . '?action=unread;board=' . $child['id'] . '" title="' . $txt['new_posts'] . ' (' . $txt['board_topics'] . ': ' . comma_format($child['topics']) . ', ' . $txt['posts'] . ': ' . comma_format($child['posts']) . ')">' : '') . '</a>';
              else
                $child['link'] = '<a href="' . $child['href'] . '" title="' . comma_format($child['posts']) . ' ' . $txt['redirects'] . '">' . $child['name'] . '</a>';

              // Has it posts awaiting approval?
              if ($child['can_approve_posts'] && ($child['unapproved_posts'] || $child['unapproved_topics']))
                $child['link'] .= ' <a href="' . $scripturl . '?action=moderate;area=postmod;sa=' . ($child['unapproved_topics'] > 0 ? 'topics' : 'posts') . ';brd=' . $child['id'] . ';' . $context['session_var'] . '=' . $context['session_id'] . '" title="' . sprintf($txt['unapproved_posts'], $child['unapproved_topics'], $child['unapproved_posts']) . '" class="">(!)</a>';

              $children[] = $child['new'] ? '<strong>' . $child['link'] . '</strong>' : $child['link'];
            }
            // Show the list of child boards
            echo '
            <div id="board_', $board['id'], '_children">
              <span class="is-size-7 is-muted is-uppercase">', $txt['parent_boards'], ': </span>', implode(', ', $children), '</div>
            ';
          } 
          
        echo '
        </div>
        ';

        // Post count column
        echo '
        <div class="column is-2 is-hidden-mobile">
          <p class="is-uppercase is-size-7">
            <span>', comma_format($board['posts']), ' ', $board['is_redirect'] ? $txt['redirects'] : $txt['posts'], '
          </p>
          <p class="is-uppercase is-size-7">', $board['is_redirect'] ? '' : comma_format($board['topics']) . ' ' . $txt['board_topics'], '
          </p>
        </div>
        ';

        // Last post column
        echo '
        <div class="column is-hidden-mobile">';
        if (!empty($board['last_post']['id']))
          echo '
          <p class="is-size-6-5">
            <span class="is-uppercase is-size-7">', $txt['last_post'], '</span>
            <span class="is-uppercase is-size-7 is-muted"> ', $txt['by'], '</span> ', 
            $board['last_post']['member']['link'] , ' 
            <br>
            <span class="is-muted is-uppercase is-size-7">', $txt['in'], '</span> ', 
            $board['last_post']['link'], ' 
            <br>
            <span class="is-muted is-uppercase is-size-7">', $txt['on'], '</span> ', 
            $board['last_post']['time'],'
          </p>
          ';
        echo'       
        </div>
        ';

        // Close main board columns
        echo '
        </div>
        ';

        // Close each board
        echo '
        </div>
        ';
      }

      echo'
      </div>';
    }

  }

  if ($context['user']['is_logged'])
  {
    echo '
    <div>';

    // Mark read button.
    $mark_read_button = array(
      'markread' => array(
        'text' => 'mark_as_read', 
        'image' => 'markread.gif', 
        'class' => 'is-primary is-small',
        'lang' => true, 
        'icon' => 'fa-check',
        'url' => $scripturl . '?action=markasread;sa=all;' . $context['session_var'] . '=' . $context['session_id']
        ),
    );

    // Icons key
    /* 
    echo '
    <ul>
      <li>', $txt['new_posts'], '</li>
      <li>', $txt['old_posts'], '</li>
      <li>', $txt['redirect_board'], '</li>
    </ul>
    '; */

    // Show the mark all as read button?
    if ($settings['show_mark_read'] && !empty($context['categories']))
      echo ' ', template_button_strip($mark_read_button, 'right'), ' ';

    echo'
    </div>
    ';
  }
  else // Not logged in...
  {
    // Icons key
    /*
    echo '
    <div>
      <ul>
        <li>', $txt['old_posts'], '</li>
        <li>', $txt['redirect_board'], '</li>
      </ul>
    </div>
    ';*/
  }

  template_info_center();
  // Close the forum index
  echo '
    </div>
  ';
}

function template_info_center()
{
  global $context, $settings, $options, $txt, $scripturl, $modSettings;

  // Here's where the "Info Center" starts...
  echo '
    <div class="container mt-3">
      <div class="columns">
  ';

  // RECENT POSTS
  if (!empty($settings['number_recent_posts']) && (!empty($context['latest_posts']) || !empty($context['latest_post'])))
  {
    echo'
      <div class="column">
      <div class="card h-100">
        <div class="card-header">
          <h2 class="card-header-title title is-5">
            <span class="icon is-medium">
              <span class="fa fa-clock-o"></span>
            </span>
            <a href="', $scripturl, '?action=recent">', $txt['recent_posts'], '</a>
          </h2>
        </div>
        <div class="card-content">';

        // Only show one post.
        if ($settings['number_recent_posts'] == 1) {
          // latest_post has link, href, time, subject, short_subject, and topic
          echo'
            <strong>
              <a href="">', $txt['recent_posts'] ,'</a>
            </strong>
            <p id="infocenter_onepost" class="smalltext">
                ', $txt['recent_view'], ' &quot;', $context['latest_post']['link'], '&quot; ', $txt['recent_updated'], ' (', $context['latest_post']['time'], ')<br />
            </p>';
        }
        // Show lots of posts
        elseif (!empty($context['latest_post'])) {
            echo '
              <ul>
            ';
            foreach ($context['latest_posts'] as $post)
              echo '
              <li class="mb-2">
                <div>', $post['link'], '</div>
                <div>
                  <div class="is-size-7">
                    <span class="is-uppercase is-muted"> ', $txt['by'], ' </span> ', $post['poster']['link'], '
                    <span class="is-uppercase is-muted"> on </span>', $post['time'], '
                  </div>
                </div>
              </li>
              ';
            echo '
            <ul>
            ';
        }

    echo'
        </div>
      </div> 
    </div>
    '; // end box, end column
  }
  
  // USERS ONLINE
  echo '
    <div class="column">
      <div class="card h-100">
        <div class="card-header">
          <h2 class="card-header-title title is-5">
            <span class="icon is-medium">
              <span class="fa fa-user-circle-o"></span>
            </span>
            <a href="' . $scripturl . '?action=who' . '">', $txt['online_users'], '</a>
          </h2>
        </div>
      <div class="card-content">
    ';

      // # Users & # Guests Online
      echo'
      <p>
        <span class="is-size-5">', comma_format($context['num_users_online']), ' </span>',
        $context['num_users_online'] == 1 ? $txt['user'] : $txt['users'], '
        <span class="is-cyan">&</span>
        <span class="is-size-5">', comma_format($context['num_guests']), ' </span>',
        $context['num_guests'] == 1 ? $txt['guest'] : $txt['guests'], '
      </p>
      ';

      // Online users
      if (!empty($context['users_online']))
      {
        echo'
        <hr class="mt-2 mb-2">

        <p class="is-uppercase is-muted is-size-7">', sprintf($txt['users_active'], $modSettings['lastActive']), '</p>
        
        <p>', implode(', ', $context['list_users_online']), '</p>';

          // Showing membergroups?
          if (!empty($settings['show_group_key']) && !empty($context['membergroups']))
          echo '
          <div class="is-size-6-5">[' . implode(']&nbsp;&nbsp;[', $context['membergroups']) . ']</div>
        ';
      }

      // Most users online
      echo'
      <p>
        <span class="is-uppercase is-size-7 is-muted">', $txt['most_online_today'], ':</span>
        ', comma_format($modSettings['mostOnlineToday']), '
        <br>
        <span class="is-uppercase is-size-7 is-muted">', $txt['most_online_ever'], ':</span>
        ', comma_format($modSettings['mostOnline']), '<span class="is-muted"> on </span>
        ', timeformat($modSettings['mostDate']), '
      </p>
      ';

    echo '
    </div>


      </div> 
    </div>
    '; // end box, end column

  // FORUM STATS
  if ($settings['show_stats_index'])
  {
    echo '
      <div class="column">
        <div class="card h-100">
          <div class="card-header">
            <h2 class="card-header-title title is-5">
              <span class="icon is-medium">
                <span class="fa fa-clock-o"></span>
              </span>
              <a href="', $scripturl, '?action=stats">', $txt['forum_stats'], '</a>
            </h2>
          </div>
          <div class="card-content">
            <p>', $context['common_stats']['total_posts'] ,' <span class="is-uppercase is-size-7 is-muted">', $txt['posts_made'], ' ', $txt['in'], '</span> ', $context['common_stats']['total_topics'] ,' <span class="is-uppercase is-size-7 is-muted">', $txt['topics'], ' ', $txt['by'], '</span> ', $context['common_stats']['total_members'] ,' <span class="is-uppercase is-size-7 is-muted">', $txt['members'], '</span>.</p>',

            !empty($settings['show_latest_member']) ? '<p><span class="is-uppercase is-size-7 is-muted">' . $txt['latest_member']. ':</span> ' . $context['common_stats']['latest_member']['link'] . '</p>' : '',

            !empty($context['latest_post']) ? '<p><span class="is-uppercase is-size-7 is-muted">' . $txt['latest_post'] . ':</span> ' . $context['latest_post']['link'] . ' (' . $context['latest_post']['time'] .')</p>' : '';
    }
  echo'
  </div>
  </div>
  '; // end columns, end container, end section
}