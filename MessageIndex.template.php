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
  global $context, $settings, $options, $scripturl, $modSettings, $txt;

  echo '
  <a id="top"></a>';

  // WHO IS VIEWING
  if (!empty($settings['display_who_viewing']))
    {
      echo'
      <div class="who-is-viewing container mb-3 is-size-7">';
        if ($settings['display_who_viewing'] == 1)
          echo count($context['view_members']), ' ', count($context['view_members']) === 1 ? $txt['who_member'] : $txt['members'];
        else
          echo empty($context['view_members_list']) ? '0 ' . $txt['members'] : implode(', ', $context['view_members_list']) . ((empty($context['view_num_hidden']) or $context['can_moderate_forum']) ? '' : ' (+ ' . $context['view_num_hidden'] . ' ' . $txt['hidden'] . ')');
        echo $txt['who_and'], $context['view_num_guests'], ' ', $context['view_num_guests'] == 1 ? $txt['guest'] : $txt['guests'], $txt['who_viewing_board'], '
      </div>
      ';
    }

  echo'
    <div class="container">
  '; 

  // DESCRIPTION
  if (!empty($options['show_board_desc']) && $context['description'] != '')
    echo '
  <p class="notification">', $context['description'], '</p>';

  // CHILD BOARDS
  if (!empty($context['boards']) && (!empty($options['show_children']) || $context['start'] == 0))
  {

    echo'
      <div board_', $context['current_board'], '_childboards" class="card is-narrow mb-4">
        <div class="card-header">
          <h2 class="card-header-title title is-6">
            <span class="is-size-5-mobile href="">', $txt['parent_boards'], '</span>
          </h2>
        </div>
      ';

      // Build each board
      foreach ($context['boards'] as $board) {
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
              <h3 class="title is-6 mb-1">
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
                <p>
                  <span class="is-size-7 is-muted is-uppercase">', count($board['moderators']) == 1 ? $txt['moderator'] : $txt['moderators'], ': </span>
                  ', implode(', ', $board['link_moderators']), '
                </p>
                ';
                
              // Child Boards (of child boards...)
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

      echo'</div>';
  }

  // BUTTONS
  $normal_buttons = array(
    'new_topic' => array(
      'test' => 'can_post_new', 
      'text' => 'new_topic', 
      'hidden' => 'is-hidden-mobile',
      'image' => 'new_topic.gif', 
      'class' => 'is-primary is-small',
      'icon' => 'fa-plus',
      'lang' => true, 
      'url' => $scripturl . '?action=post;board=' . $context['current_board'] . '.0', 
      'active' => true
      ),
    'post_poll' => array(
      'test' => 'can_post_poll', 
      'text' => 'new_poll', 
      'hidden' => 'is-hidden-mobile',
      'image' => 'new_poll.gif',
      'class' => 'is-primary is-small', 
      'icon' => 'fa-bar-chart',
      'lang' => true, 
      'url' => $scripturl . '?action=post;board=' . $context['current_board'] . '.0;poll'
      ),
    'notify' => array(
      'test' => 'can_mark_notify', 
      'class' => 'is-primary is-small',
      'hidden' => 'is-hidden-mobile',
      'text' => $context['is_marked_notify'] ? 'unnotify' : 'notify', 
      'image' => ($context['is_marked_notify'] ? 'un' : ''). 'notify.gif', 
      'icon' => 'fa-flag',
      'lang' => true, 
      'custom' => 'onclick="return confirm(\'' . ($context['is_marked_notify'] ? $txt['notification_disable_board'] : $txt['notification_enable_board']) . '\');"', 
      'url' => $scripturl . '?action=notifyboard;sa=' . ($context['is_marked_notify'] ? 'off' : 'on') . ';board=' . $context['current_board'] . '.' . $context['start'] . ';' . $context['session_var'] . '=' . $context['session_id']
      ),
    'markread' => array(
      'text' => 'mark_read_short', 
      'image' => 'markread.gif', 
      'class' => 'is-primary is-small',
      'hidden' => 'is-hidden-mobile',
      'icon' => 'fa-check',
      'lang' => true, 
      'url' => $scripturl . '?action=markasread;sa=board;board=' . $context['current_board'] . '.0;' . $context['session_var'] . '=' . $context['session_id']
      ),
  );

  // They can only mark read if they are logged in and it's enabled!
  if (!$context['user']['is_logged'] || !$settings['show_mark_read'])
    unset($normal_buttons['markread']);

  // Allow adding new buttons easily.
  call_integration_hook('integrate_messageindex_buttons', array(&$normal_buttons));

  // BEGIN TOPIC LIST

  if (!$context['no_topic_listing'])

    // Show Buttons
    echo '
    <div class="thread-tools-menu mb-4">
    ', template_button_strip($normal_buttons, 'right'), '
    </div>
    ';

  {
    // If Quick Moderation is enabled start the form.
    if (!empty($context['can_quick_mod']) && $options['display_quick_mod'] > 0 && !empty($context['topics']))
      echo '<form action="', $scripturl, '?action=quickmod;board=', $context['current_board'], '.', $context['start'], '" method="post" accept-charset="', $context['character_set'], '" class="clear" name="quickModForm" id="quickModForm">';

    // Begin the topic list
    if (!empty($context['topics']))
    {
      echo'
      <div class="card">
      ';
      // Show the list header
      echo'
        <div class="card-header is-hidden-mobile">
          <div class="columns card-header-title">
            <div class="column is-narrow"><span class="icon"><span class="fa"></span></span></div>
            <div class="column', $options['display_quick_mod'] == 1 ? ' is-5' : ' is-6', '">
              <span class="board-header-title">Subject/Started By</span>
            </div>
            <div class="column is-2">
              <span class="board-header-title">Replies/Views</span>
            </div>
            <div class="column is-3">
              <span class="board-header-title">Last post</span>
            </div>
          ';

          if (!empty($context['can_quick_mod']) && $options['display_quick_mod'] == 1)
          echo '
            <div class="column is-1">
              <p class="control">
                <label class="checkbox">
                  <input type="checkbox" onclick="invertAll(this, this.form, \'topics[]\');"/>
                  <span class="is-hidden-tablet">Select all threads</span>
                </label>
              </p>
            </div>
          ';
             
            echo' 
        </div>
        </div>
      ';
    }

    else // There are no topics...
    echo '
      <div class="notification is-danger">', $txt['msg_alert_none'], '</div>
    
    ';

    echo'
    <div class="card-content">
    ';

    // FOR EACH TOPIC
    foreach ($context['topics'] as $topic)
    {
      // Is this topic pending approval, or does it have any posts pending approval?
      if ($context['can_approve_posts'] && $topic['unapproved_posts'])
        $topic_class = !$topic['approved'] ? 'topic-is-pending' : 'topic-is-approved';
      // Is locked
      elseif ($topic['is_locked'])
        $topic_class = 'topic-is-locked';
      elseif ($topic['new'])
        $topic_class = 'topic-is-new';
      else
        $topic_class = 'topic-is-basic';

      // Icon
      if ($topic['is_poll'])
        $topic_icon = 'fa-bar-chart';
      elseif ($topic['is_locked'])
        $topic_icon = 'fa-lock';
      elseif ($topic['new'])
        $topic_icon = 'fa-star';
      else
        $topic_icon ='fa-star-o';

      // Tooltip
      if ($topic['is_poll'])
        $topic_tooltip = 'Poll';
      elseif ($topic['is_locked'])
        $topic_tooltip = 'Locked';
      elseif ($topic['new'])
        $topic_tooltip = 'New Posts';
      else
        $topic_tooltip ='No New Posts';

      /*if ($topic['is_sticky'])
        echo'
        <div class="sticky-tab"><i class="fa fa-thumb-tack"></i></div>
        ';*/

      echo'
        <div class="columns is-mobile ', $topic_class ,'">';
          if ($topic['is_sticky'])
          echo'
          <div class="sticky-tab"><i class="fa fa-thumb-tack"></i></div>
          ';
          echo'
          <div class="column is-narrow">
            <span class="icon">
              <i class="fa ', $topic_icon, '" title="', $topic_tooltip ,'"></i>
            </span>
          </div>

          <div class="column is-10-mobile ', $options['display_quick_mod'] == 1 ? ' is-5-tablet' : ' is-6-tablet', '">
            <div>
              <h2 class="title is-6 mb-2">', $topic['first_post']['link'],'</h2>
            </div>
            <p>
              <span class="is-size-7 is-muted is-uppercase">', $txt['started_by'], '</span> ', $topic['first_post']['member']['link'], '
              </p>
          </div>

          <div class="column is-hidden-mobile is-2">
            <p class="is-uppercase is-size-7">', $topic['replies'], ' ', $txt['replies'], '</p>
            <p class="is-uppercase is-size-7">', $topic['views'], ' ', $txt['views'], '</p>
          </div>

          <div class="column is-hidden-mobile">
            <p class="is-size-6-5">
              <span class="is-uppercase is-size-7">', $txt['last_post'], '</span>
              <span class="is-uppercase is-size-7 is-muted"> ', $txt['by'], '</span> ', 
              $topic['last_post']['member']['link'] , '  
              <br>
              <span class="is-muted is-uppercase is-size-7">', $txt['on'], '</span> ', 
              $topic['last_post']['time'],'
              <a href="', $topic['last_post']['href'], '" class="view-last-post">
                <span class="icon has-text-primary">
                  <i class="fa fa-angle-double-right"></i>
                </span>
                <span class="sr-only"> View last post</span>
              </a>
            </p>
          </div>';

          // Show the quick moderation options?
          if (!empty($context['can_quick_mod']))
          {
            if ($options['display_quick_mod'] == 1)
              echo '
              <div class="column is-hidden-mobile is-narrow">
                <p class="control">
                  <label class="checkbox">
                    <input type="checkbox" name="topics[]" value="', $topic['id'], '" class="input_check" />
                    <span class="is-hidden-tablet">Select thread</span>
                  </label>
                </p>
              </div>
              ';
            }
            echo '
        </div>
      ';
    }
  }
  echo '
  </div>
    </div>
  ';

}

?>