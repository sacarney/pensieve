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
      <div class="who-is-viewing">';
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

  // Child board nonsense here

  // DESCRIPTION
  if (!empty($options['show_board_desc']) && $context['description'] != '')
    echo '
  <p class="notification">', $context['description'], '</p>';

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
            <div class="column is-1"></div>
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

      /*if ($topic['is_sticky'])
        echo'
        <div class="sticky-tab"><i class="fa fa-thumb-tack"></i></div>
        ';*/

      echo'
        <div class="columns is-mobile">
          <div class="column is-narrow">
            <span class="icon">
              <i class="fa ', $topic_icon, '"></i>
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
            <p>
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