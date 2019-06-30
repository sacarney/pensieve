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

  echo'<div class="container">';

  // Let them know, if their report was a success!
  if ($context['report_sent'])
  {
    echo '
      <div class="message is-info">
        <div class="message-body">', $txt['report_sent'], '</div>
      </div>';
  }

  // Show the page index... "Pages: [1]".
  echo '
    <div class="level mt-2">
      <div class="level-left">';

        // Show the anchor for the top and for the first message. If the first message is new, say so.
        echo '
          <a id="top"></a>';

        // Skip to first post
        echo'
        <a href="#msg', $context['first_message'], '" class="invisible button is-small is-dark mr-1">Skip to first post</a>';

        echo'
        <span class="is-muted is-size-6-5 is-uppercase">', $txt['pages'], ':&nbsp;</span>
        <span class="is-size-6-5 mr-3"> ', $context['page_index'], !empty($modSettings['topbottomEnable']) ? $context['menu_separator'] . '</span> 
        
        <a class="button is-small" href="#lastPost">
          <span class="icon">
            <span class="fa fa-arrow-down"></span>
          </span>
          <span>' . $txt['go_down'] . '</span>
        </a>' : '', '
      </div>';

      // Show reply button strip
      // Build the normal button array.
      $normal_buttons = array(
        'reply' => array(
          'test' => 'can_reply', 
          'text' => 'reply', 
          'class' => 'is-primary',
          'image' => 'reply.gif', 
          'icon' => 'fa-reply',
          'lang' => true, 
          'url' => $scripturl . '?action=post;topic=' . $context['current_topic'] . '.' . $context['start'] . ';last_msg=' . $context['topic_last_message'], 
          'active' => true),

        'add_poll' => array(
          'test' => 'can_add_poll', 
          'text' => 'add_poll', 
          'image' => 'add_poll.gif', 
          'icon' => 'fa-bar-chart-o',
          'lang' => true, 
          'url' => $scripturl . '?action=editpoll;add;topic=' . $context['current_topic'] . '.' . $context['start']),

        'notify' => array(
          'test' => 'can_mark_notify', 
          'text' => $context['is_marked_notify'] ? 'unnotify' : 'notify', 
          'image' => ($context['is_marked_notify'] ? 'un' : '') . 'notify.gif', 
          'icon' => 'fa-bell',
          'lang' => true, 
          'custom' => 'onclick="return confirm(\'' . ($context['is_marked_notify'] ? $txt['notification_disable_topic'] : $txt['notification_enable_topic']) . '\');"', 
          'url' => $scripturl . '?action=notify;sa=' . ($context['is_marked_notify'] ? 'off' : 'on') . ';topic=' . $context['current_topic'] . '.' . $context['start'] . ';' . $context['session_var'] . '=' . $context['session_id']),

        'mark_unread' => array(
          'test' => 'can_mark_unread', 
          'text' => 'mark_unread', 
          'image' => 'markunread.gif', 
          'icon' => 'fa-eye',
          'lang' => true, 
          'url' => $scripturl . '?action=markasread;sa=topic;t=' . $context['mark_unread_time'] . ';topic=' . $context['current_topic'] . '.' . $context['start'] . ';' . $context['session_var'] . '=' . $context['session_id']),

        'send' => array(
          'test' => 'can_send_topic', 
          'text' => 'send_topic', 
          'image' => 'sendtopic.gif', 
          'icon' => 'fa-paper-plane',
          'lang' => true, 
          'url' => $scripturl . '?action=emailuser;sa=sendtopic;topic=' . $context['current_topic'] . '.0'
          ),

        'print' => array(
          'text' => 'print', 
          'image' => 'print.gif',
          'icon' => 'fa-print', 
          'lang' => true, 
          'custom' => 'rel="new_win nofollow"', 
          'url' => $scripturl . '?action=printpage;topic=' . $context['current_topic'] . '.0'
          ),
      );

      echo'
      <div class="level-right" role="toolbar" aria-label="Topic Toolbar">', template_button_strip($normal_buttons, 'right'), '</div>';

      echo'
    </div>';

  /* 
  I'm hiding the prev-next buttons because I can't figure out where to put them.
  <div class="level-right mt-0 has-text-right previous-next-buttons">', $context['previous_next'], ' </div>
  */

  // Is this topic also a poll?
  if ($context['is_poll'])
  {
    echo '
      <div id="poll" class="card card-poll mb-4">
        <div class="card-header">
          <h2 class="card-header-title" id="pollquestion">
            <span class="icon"><span class="fa fa-bar-chart"></span></span>
            <span>', $txt['poll'] ,': ', $context['poll']['question'] ,'</span>
          </h2>
        </div>

        <div class="card-content">';

          // Are they not allowed to vote but allowed to view the options?
          if ($context['poll']['show_results'] || !$context['allow_vote']) {

            foreach ($context['poll']['options'] as $option) {
              echo '
              <div class="columns is-mobile pl-1 pr-1">
                <div class="column is-one-quarter-tablet is-half-mobile"><div class="', $option['voted_this'] ? ' voted' : '', '">' , $option['option'] , '</div></div>';

                if ($context['allow_poll_view'])
                  echo '
                  <div class="column is-hidden-mobile"><div class="bar-wrapper">', $option['bar_ndt'], '</div></div>
                  <div class="column is-one-quarter-tablet is-half-mobile"><div class="tag is-small">', $option['votes'], ' (', $option['percent'], '%)</div></div>
              </div>';
            }
          }

          // Are they allowed to vote? Do it!
          else {
            echo '
              <form action="', $scripturl, '?action=vote;topic=', $context['current_topic'], '.', $context['start'], ';poll=', $context['poll']['id'], '" method="post" accept-charset="', $context['character_set'], '">
            ';

            // Show a warning if they are allowed more than one option
            if ($context['poll']['allowed_warning']) {
              echo '
                <p class="notification is-size-6-5 p-3">', $context['poll']['allowed_warning'] ,'</p>
              ';

              
            }

            echo '
                <ul class="mb-4">
              ';
              // Show each option with its button - a radio likely.
              foreach ($context['poll']['options'] as $option)
                echo '
                  <li>
                    <label class="checkbox" for="', $option['id'], '">
                      ', $option['vote_button'], ' ', $option['option'], '
                    </label>
                  </li>
                  ';

                echo '
                </ul>

                <input type="submit" value="', $txt['poll_vote'], '" class="button is-small is-primary" />
                <input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
            </form>';
          }

          // Total Voters
          if ($context['allow_poll_view'])
          echo '
            <p class="notification is-size-6-5 p-3 mt-2 mb-1">
              <span class="is-uppercase">', $txt['poll_total_voters'], ':</span> ', $context['poll']['total_votes'], '
            </p>';

          // Is the clock ticking?
          if (!empty($context['poll']['expire_time']))
            echo '
              <p class="notification is-size-6-5 p-3 mt-2 mb-1">
                <span class="is-uppercase is-muted">', ($context['poll']['is_expired'] ? $txt['poll_expired_on'] : $txt['poll_expires_on']), ':</span>  ', $context['poll']['expire_time'], '
              </p>
            ';

          // Build the poll moderation button array.
          $poll_buttons = array(
            'vote' => array(
              'test' => 'allow_return_vote', 
              'text' => 'poll_return_vote', 
              'image' => 'poll_options.gif', 
              'icon' => 'fa-check-square-o',
              'lang' => true, 
              'url' => $scripturl . '?topic=' . $context['current_topic'] . '.' . $context['start']
              ),
            'results' => array(
              'test' => 'show_view_results_button', 
              'text' => 'poll_results', 
              'image' => 'poll_results.gif', 
              'icon' => 'fa-bar-chart',
              'lang' => true, 
              'url' => $scripturl . '?topic=' . $context['current_topic'] . '.' . $context['start'] . ';viewresults'
              ),
            'change_vote' => array(
              'test' => 'allow_change_vote', 
              'text' => 'poll_change_vote', 
              'image' => 'poll_change_vote.gif', 
              'icon' => 'fa-check-square-o',
              'lang' => true, 
              'url' => $scripturl . '?action=vote;topic=' . $context['current_topic'] . '.' . $context['start'] . ';poll=' . $context['poll']['id'] . ';' . $context['session_var'] . '=' . $context['session_id']
              ),
            'lock' => array(
              'test' => 'allow_lock_poll', 
              'text' => (!$context['poll']['is_locked'] ? 'poll_lock' : 'poll_unlock'), 
              'image' => 'poll_lock.gif', 
              'icon' => 'fa-lock',
              'lang' => true, 
              'url' => $scripturl . '?action=lockvoting;topic=' . $context['current_topic'] . '.' . $context['start'] . ';' . $context['session_var'] . '=' . $context['session_id']
              ),
            'edit' => array(
              'test' => 'allow_edit_poll', 
              'text' => 'poll_edit', 
              'image' => 'poll_edit.gif', 
              'icon' => 'fa-pencil',
              'lang' => true, 
              'url' => $scripturl . '?action=editpoll;topic=' . $context['current_topic'] . '.' . $context['start']
              ),
            'remove_poll' => array(
              'test' => 'can_remove_poll', 
              'text' => 'poll_remove', 
              'image' => 'admin_remove_poll.gif', 
              'icon' => 'fa-times',
              'lang' => true, 
              'custom' => 'onclick="return confirm(\'' . $txt['poll_remove_warn'] . '\');"', 
              'url' => $scripturl . '?action=removepoll;topic=' . $context['current_topic'] . '.' . $context['start'] . ';' . $context['session_var'] . '=' . $context['session_id']
              ),
          );
          echo'
          <div class="mt-2">';
            template_button_strip($poll_buttons);
          echo '
          </div>';
        
        echo '
        </div>
      </div>';
  }

  // Does this topic have some events linked to it? @TODO
  if (!empty($context['linked_calendar_events']))
  {
    echo '
      <div class="linked_events">
        <div class="title_bar">
          <h3 class="titlebg headerpadding">', $txt['calendar_linked_events'], '</h3>
        </div>
        <div class="windowbg">
          <span class="topslice"><span></span></span>
          <div class="content">
            <ul class="reset">';

    foreach ($context['linked_calendar_events'] as $event)
      echo '
              <li>
                ', ($event['can_edit'] ? '<a href="' . $event['modify_href'] . '"> <img src="' . $settings['images_url'] . '/icons/modify_small.gif" alt="" title="' . $txt['modify'] . '" class="edit_event" /></a> ' : ''), '<strong>', $event['title'], '</strong>: ', $event['start_date'], ($event['start_date'] != $event['end_date'] ? ' - ' . $event['end_date'] : ''), '
              </li>';

    echo '
            </ul>
          </div>
          <span class="botslice"><span></span></span>
        </div>
      </div>';
  }

  // Allow adding new buttons easily.
  call_integration_hook('integrate_display_buttons', array(&$normal_buttons));

  echo '<article id="forumposts">';

  // Show the topic information - icon, subject, etc.
  echo '
    <header>
      <div class="cat_bar">
        <h1 class="title is-5 mb-1"><img class="mr-1" src="', $settings['images_url'], '/topic/', $context['class'], '.gif" alt="" />', $context['subject'], '</h1> 
      </div>';

      // Tagging System
      echo '
      <div class="container is-flex flex-wrap align-items-center mt-2 mb-2">

        <h2 class="title is-6 mb-0 mr-2">', $txt['smftags_topic'], '</h2>';

        echo'<ul class="tags mb-0">';

        foreach ($context['topic_tags'] as $i => $tag)
        {
          echo '<li>
                  <div class="tags has-addons mr-2">
                    <a class="tag" href="' . $scripturl . '?action=tags;tagid=' . $tag['ID_TAG']  . '">' . $tag['tag'] . '</a>';

                    if(!$context['user']['is_guest'] && allowedTo('smftags_del'))
                    echo'
                      <a class="tag is-delete" href="' . $scripturl . '?action=tags;sa=deletetag;tagid=' . $tag['ID']  . '"></a>
                      ';
             echo' </div>
             </li>';
        }

        echo'</ul>';

        global $topic;
        if(!$context['user']['is_guest'] && allowedTo('smftags_add'))
        echo '
        <a class="button is-small" href="' . $scripturl . '?action=tags;sa=addtag;topic=',$topic, '">' . $txt['smftags_addtag'] . '</a>';

      echo '
        </div>';
    
    // End Tagging System
    
    echo'
      <div class="mb-4">
        <span class="is-muted is-size-6-5">', $txt['read'], ' ', $context['num_views'], ' ', $txt['times'], '</span>';

          // Who is viewing
          if (!empty($settings['display_who_viewing']))
          {
            echo '<span id="whoisviewing" class="is-muted is-size-6-5">&nbsp;/ ';

            // Show just numbers...?
            if ($settings['display_who_viewing'] == 1)
              echo count($context['view_members']), ' ', count($context['view_members']) == 1 ? $txt['who_member'] : $txt['members'];
            // Or the actual members
            else
              echo empty($context['view_members_list']) ? '0 ' . $txt['members'] : implode(', ', $context['view_members_list']) . ((empty($context['view_num_hidden']) || $context['can_moderate_forum']) ? '' : ' (+ ' . $context['view_num_hidden'] . ' ' . $txt['hidden'] . ')');
            // Now show how many guests are here too.
            echo $txt['who_and'], $context['view_num_guests'], ' ', $context['view_num_guests'] == 1 ? $txt['guest'] : $txt['guests'], $txt['who_viewing_topic'], '
          
            </span>';
          }
          echo'
        </div>
      </header>';

  echo '
    <form action="', $scripturl, '?action=quickmod2;topic=', $context['current_topic'], '.', $context['start'], '" method="post" accept-charset="', $context['character_set'], '" name="quickModForm" id="quickModForm" style="margin: 0;" onsubmit="return oQuickModify.bInEditMode ? oQuickModify.modifySave(\'' . $context['session_id'] . '\', \'' . $context['session_var'] . '\') : false">';

    $ignoredMsgs = array();
    $removableMessageIDs = array();
    $alternate = false;

    $thispost = 0;   

    // Get all the messages...
    while ($message = $context['get_message']())
    {
      $ignoring = false;
      $alternate = !$alternate;
      if ($message['can_remove'])
        $removableMessageIDs[] = $message['id'];

      // Are we ignoring this message?
      if (!empty($message['is_ignored']))
      {
        $ignoring = true;
        $ignoredMsgs[] = $message['id'];
      }

      $thispost = $thispost + 1;
      $nextpost = $thispost + 1;

      // The Post
      echo '
        <article class="mb-4 the-post-wrapper">
          <div class="columns m-0">
          ';

            // Show information about the poster of this message.
            echo '
            <aside class="column is-one-fifth-tablet p-0">
              <div class="card ">
                <div class="card-header ">
                  <div class="card-header-title">';
                    // Avatar
                    if (!empty($settings['show_user_images']) && empty($options['show_no_avatars']) && !empty($message['member']['avatar']['image']))
                      echo '
                      <div class="card-image post-profile-image-mobile is-hidden-tablet">
                        <figure class="image">
                          ', $message['member']['avatar']['image'], '
                        </figure>
                      </div>';
                    echo' 
                    <div>
                      <span class="sr-only">Post from ', $message['member']['name'], '</span>';
                      // Link to member profile and whether they are online
                        echo '<span class="icon"><span class="fa fa-circle '; 
                          if($message['member']['online']['text'] == "Online")
                          echo 'has-text-success ,';
                          else echo 'has-text-grey-light';
                          echo '"></span></span>';
                        
                        echo '<span>', $message['member']['link'], '</span>';
                        
                        // Mobile titles

                        echo'<div class="is-flex flex-wrap has-text-weight-normal pl-2 is-size-7-mobile is-hidden-tablet">';
                        // Show primary group, if they have one.
                          if (!empty($message['member']['group']))
                            echo'<p class="is-uppercase is-muted mr-2">', $message['member']['group'], '</p>';

                          // Show the member's custom title, if they have one.
                          if (!empty($message['member']['title']))
                            echo '<p class="mr-2">', $message['member']['title'], '</p>';

                          // Show the post group if and only if they have no other group or the option is on, and they are in a post group.
                          if ((empty($settings['hide_post_group']) || $message['member']['group'] == '') && $message['member']['post_group'] != '' && !$message['member']['is_guest'])
                            echo '
                            <p>', $message['member']['post_group'], '</p>
                            <div aria-hidden class="is-hidden-mobile">', $message['member']['group_stars'], '</div>
                            ';
                        echo'</div>
                    </div>'; 

                    // Are they online? (For screenreaders)
                    if (!empty($modSettings['onlineEnable']) && !$message['member']['is_guest']) 
                    echo '<span class="sr-only">', $message['member']['online']['text'], '</span>';
                    echo'
                  </div>
                </div>';

                echo'
                <div class="card-content is-size-6-5 flex-wrap is-hidden-mobile">';

                  // Show primary group, if they have one.
                  if (!empty($message['member']['group']))
                    echo'<p class="is-uppercase is-muted mr-2">', $message['member']['group'], '</p>';

                  // Show the member's custom title, if they have one.
                  if (!empty($message['member']['title']))
                    echo '<p class="mr-2">', $message['member']['title'], '</p>';

                  // Show the post group if and only if they have no other group or the option is on, and they are in a post group.
                  if ((empty($settings['hide_post_group']) || $message['member']['group'] == '') && $message['member']['post_group'] != '' && !$message['member']['is_guest'])
                    echo '
                    <p>', $message['member']['post_group'], '</p>
                    <div aria-hidden class="is-hidden-mobile">', $message['member']['group_stars'], '</div>
                    ';

                    echo'
                  </div>';

                  // Don't show these things for guests.
                  if (!$message['member']['is_guest']) {

                    // Avatar
                    if (!empty($settings['show_user_images']) && empty($options['show_no_avatars']) && !empty($message['member']['avatar']['image']))
                      echo '
                      <div class="card-image post-profile-image is-hidden-mobile">
                        <figure class="image">
                          ', $message['member']['avatar']['image'], '
                        </figure>
                      </div>';

                    echo'
                    <div class="card-content is-size-6-5 is-hidden-mobile">';
                    
                      // Show how many posts they have made.
                      if (!isset($context['disabled_fields']['posts']))
                        echo '<p><span class="is-uppercase is-muted">', $txt['member_postcount'], ':</span> ', $message['member']['posts'], '</p>';

                      // Is karma display enabled?  Total or +/-?
                      if ($modSettings['karmaMode'] == '1')
                        echo '
                          <p>
                            <span class="is-uppercase is-muted">', $modSettings['karmaLabel'], '</span>  ', $message['member']['karma']['good'] - $message['member']['karma']['bad'], '
                          </p>';

                      elseif ($modSettings['karmaMode'] == '2')
                        echo '
                          <p>
                            <span class="is-uppercase is-muted">', $modSettings['karmaLabel'], '</span> 
                            <span class="tags has-addons is-inline-flex">
                              <span class="tag is-success mb-0">+', $message['member']['karma']['good'], '</span>
                              <span class="tag is-danger mb-0">-', $message['member']['karma']['bad'], '</span>
                            </span>
                          </p>';

                      // Is this user allowed to modify this member's karma?
                      if ($message['member']['karma']['allow'])
                        echo '
                            <div class="field has-addons mt-2">
                              <div class="control">
                                <a class="button is-small is-success is-outlined" href="', $scripturl, '?action=modifykarma;sa=applaud;uid=', $message['member']['id'], ';topic=', $context['current_topic'], '.' . $context['start'], ';m=', $message['id'], ';', $context['session_var'], '=', $context['session_id'], '">', $modSettings['karmaApplaudLabel'], '</a>
                              </div>
                              <div class="control">
                                <a class="button is-small is-danger is-outlined" href="', $scripturl, '?action=modifykarma;sa=smite;uid=', $message['member']['id'], ';topic=', $context['current_topic'], '.', $context['start'], ';m=', $message['id'], ';', $context['session_var'], '=', $context['session_id'], '">', $modSettings['karmaSmiteLabel'], '</a>
                              </div>
                            </div>
                          ';
                    echo'
                    </div>';
                  }

                  // Show their personal text?
                  if (!empty($settings['show_blurb']) && $message['member']['blurb'] != '')
                  echo '
                    <div class="card-content is-size-6-5 is-hidden-mobile">
                      <p class="is-muted">', $message['member']['blurb'], '</p>
                    </div>';

                  // Any custom fields for standard placement?
                  if (!empty($message['member']['custom_fields']))
                  {
                    echo'<div class="card-content is-size-6-5 is-hidden-mobile">';

                    foreach ($message['member']['custom_fields'] as $custom)
                      if (empty($custom['placement']) || empty($custom['value']))
                        echo '<div class="mb-2">', $custom['value'], '</div>';
                    echo '</div>';
                  }

                  // Any custom fields to show as icons?
                  if (!empty($message['member']['custom_fields']))
                  {
                    $shown = false;
                    foreach ($message['member']['custom_fields'] as $custom)
                    {
                      if ($custom['placement'] != 1 || empty($custom['value']))
                        continue;
                      if (empty($shown))
                      {
                        $shown = true;
                        echo '
                          <div class="card-content is-size-6-5 is-hidden-mobile">
                            <ul class="is-flex">';
                      }
                      echo '
                            <li class="mr-2">', $custom['value'], '</li>';
                    }
                    if ($shown)
                      echo '
                            </ul>
                          </div>
                      ';
                  }

                  // Are we showing the warning status?
                  if ($message['member']['can_see_warning'])
                  echo '
                    <div class="card-content is-size-6-5 is-hidden-mobile">
                      <div class="message is-warning mb-0">
                        <div class="message-body is-size-7 p-2"><span class="icon has-text-warning"><span class="fa fa-exclamation-triangle"></span></span>', $context['can_issue_warning'] ? '<a href="' . $scripturl . '?action=profile;area=issuewarning;u=' . $message['member']['id'] . '">' : '', '', $context['can_issue_warning'] ? '</a>' : '', '<span class="warn_', $message['member']['warning_status'], '">', $txt['warn_' . $message['member']['warning_status']], '</span>
                        </div>
                      </div>
                    </div>';

                  // Show the profile, website, email address, and personal message buttons.
                  if ($settings['show_profile_buttons']) {
                  
                    echo'
                    <div class="card-footer post-profile-footer is-hidden-mobile">';

                    // Don't show the profile button if you're not allowed to view the profile.
                    if ($message['member']['can_view_profile'])
                    echo '
                      <a class="card-footer-item" href="', $message['member']['href'], '">
                        <span class="fa fa-user-circle-o"></span>
                        <span class="sr-only">' . $txt['view_profile'] . '</span>
                      </a>';

                    // Don't show an icon if they haven't specified a website.
                    if ($message['member']['website']['url'] != '' && !isset($context['disabled_fields']['website']))
                    echo '
                      <a class="card-footer-item" href="', $message['member']['website']['url'], '">
                        <span class="fa fa-globe"></span>
                        <span class="sr-only">' . $txt['www'] . '</span>
                      </a>';

                     // Don't show the email address if they want it hidden.
                    if (in_array($message['member']['show_email'], array('yes', 'yes_permission_override', 'no_through_forum')))
                      echo '
                        <a class="card-footer-item" href="', $scripturl, '?action=emailuser;sa=email;msg=', $message['id'], '">
                          <span class="fa fa-envelope-o"></span>
                          <span class="sr-only">' . $txt['email'] . '</span>
                        </a>';  

                    // Since we know this person isn't a guest, you *can* message them.
                    if ($context['can_send_pm'])
                    echo '
                      <a class="card-footer-item" href="', $scripturl, '?action=pm;sa=send;u=', $message['member']['id'], '">
                          <span class="fa fa-comment-o"></span>
                          <span class="sr-only">'  . ($message['member']['online']['is_online'] ? $txt['pm_online'] : $txt['pm_offline']) . '</span>
                        </a>
                            ';

                            echo'

                  </div>'; 
                }
                  echo'
                </div>
            </aside>';

            // Show the post
            echo'
            <div class="column is-four-fifths-tablet p-0 the-post">
              
              <header class="columns">
                <div class="column pb-0">
                  <div>
                    <h1 class="title is-6 mb-0 is-hidden-mobile" id="subject_', $message['id'], '">
                      <a href="', $message['href'], '" rel="nofollow">', $message['subject'], '</a>
                    </h1>
                    <p class="is-muted is-size-6-5"><span class="is-uppercase">', !empty($message['counter']) ? $txt['reply_noun'] . ' #' . $message['counter'] : '', ' </span>', $txt['on'], '<span class="is-uppercase"> ', $message['time'], '</span></p>
                  </div>
                </div>

                <div class="column is-narrow" role="toolbar" aria-label="Post Toolbar">';

                  // Post buttons

                  // Maybe we can approve it, maybe we should?
                  if ($message['can_approve'])
                  echo '
                    <a class="button is-small mr-1" href="', $scripturl, '?action=moderate;area=postmod;sa=approve;topic=', $context['current_topic'], '.', $context['start'], ';msg=', $message['id'], ';', $context['session_var'], '=', $context['session_id'], '" title="', $txt['approve'], '"><span class="icon m-0"><span class="fa fa-check"></span></span><span class="is-hidden-touch ml-1">', $txt['approve'], '</span></a>';

                  // Can they reply? Have they turned on quick reply?
                  if ($context['can_quote'] && !empty($options['display_quick_reply']))
                  echo '
                    <a class="button is-small mr-1" href="', $scripturl, '?action=post;quote=', $message['id'], ';topic=', $context['current_topic'], '.', $context['start'], ';last_msg=', $context['topic_last_message'], '" onclick="return oQuickReply.quote(', $message['id'], ');" title="', $txt['quote'], '"><span class="icon m-0"><span class="fa fa-quote-left"></span></span><span class="is-hidden-touch ml-1">', $txt['quote'], '</span></a>';

                    // So... quick reply is off, but they *can* reply?
                    elseif ($context['can_quote'])
                      echo '
                      <a class="button is-small mr-1" href="', $scripturl, '?action=post;quote=', $message['id'], ';topic=', $context['current_topic'], '.', $context['start'], ';last_msg=', $context['topic_last_message'], '" title="', $txt['quote'], '"><span class="icon m-0"><span class="fa fa-quote-left"></span></span><span class="is-hidden-touch ml-1">', $txt['quote'], '</span></a>';

                    // Can the user modify the contents of this post?
                    if ($message['can_modify'])
                      echo '
                        <a class="button is-small mr-1" href="', $scripturl, '?action=post;msg=', $message['id'], ';topic=', $context['current_topic'], '.', $context['start'], '" title="', $txt['modify'], '"><span class="icon m-0"><span class="fa fa-pencil"></span></span><span class="is-hidden-touch ml-1">', $txt['modify'], '</span></a>';

                    // How about... even... remove it entirely?!
                    if ($message['can_remove'])
                      echo '
                        <a class="button is-small mr-1" href="', $scripturl, '?action=deletemsg;topic=', $context['current_topic'], '.', $context['start'], ';msg=', $message['id'], ';', $context['session_var'], '=', $context['session_id'], '" onclick="return confirm(\'', $txt['remove_message'], '?\');" title="', $txt['remove'], '"><span class="icon m-0"><span class="fa fa-times"></span></span><span class="is-hidden-touch ml-1">', $txt['remove'], '</span></a>';

                    // What about splitting it off the rest of the topic?
                    if ($context['can_split'] && !empty($context['real_num_replies']))
                      echo '
                        <a class="button is-small mr-1" href="', $scripturl, '?action=splittopics;topic=', $context['current_topic'], '.0;at=', $message['id'], '" title="', $txt['split'], '"><span class="icon m-0"><span class="fa fa-code-fork"></span></span><span class="is-hidden-touch ml-1">', $txt['split'], '</span></a>';

                    // Can we restore topics?
                    if ($context['can_restore_msg'])
                      echo '
                        <a class="button is-small mr-1" href="', $scripturl, '?action=restoretopic;msgs=', $message['id'], ';', $context['session_var'], '=', $context['session_id'], '" title="', $txt['restore_message'], '"><span class="icon m-0"><span class="fa fa-refresh"></span></span><span class="is-hidden-touch ml-1">', $txt['restore_message'], '</span></a>';

                    // Show a checkbox for quick moderation?
                    if (!empty($options['display_quick_mod']) && $options['display_quick_mod'] == 1 && $message['can_remove'])
                      echo '
                        <div class="inline_mod_check is-inline-flex" style="display: none;" id="in_topic_mod_check_', $message['id'], '"></div>';

                  echo'
                </div>
              </header>

              <div id="msg_', $message['id'], '_quick_mod"></div>';

              // Show the message anchor and a "new" anchor if this message is new.
              if ($message['id'] /* != $context['first_message']*/)
              echo '
                <a id="msg', $message['id'], '"></a>', $message['first_new'] ? '<a id="new"></a>' : '';

              // Skipping posts anchor
                echo '<a id="postid_', $thispost ,'"></a>';

              // Ignoring this user? Hide the post.
              if ($ignoring)
              echo '
                <div class="notification is-size-6-5 is-danger"> id="msg_', $message['id'], '_ignored_prompt">', $txt['ignoring_user'], '<a href="#" id="msg_', $message['id'], '_ignored_link" style="display: none;">', $txt['show_ignore_user_post'], '</a>
                </div>';

              echo'

              <div class="content mt-4 the-post-content">';

              if (!$message['approved'] && $message['member']['id'] != 0 && $message['member']['id'] == $context['user']['id'])
                echo '
                  <div class="notification is-size-6-5 is-warning">', $txt['post_awaiting_approval'], '</div>';
                
                echo '
                  <div class="inner" id="msg_', $message['id'], '"', '>', $message['body'], '</div>
              </div>';

              // Assuming there are attachments...
              if (!empty($message['attachment']))
              {
                echo '
                        <div id="msg_', $message['id'], '_footer" class="box">
                          <div style="overflow: ', $context['browser']['is_firefox'] ? 'visible' : 'auto', ';">';

                $last_approved_state = 1;
                foreach ($message['attachment'] as $attachment)
                {
                  // Show a special box for unapproved attachments...
                  if ($attachment['is_approved'] != $last_approved_state)
                  {
                    $last_approved_state = 0;
                    echo '
                            <fieldset>
                              <legend>', $txt['attach_awaiting_approve'];

                    if ($context['can_approve'])
                      echo '&nbsp;[<a href="', $scripturl, '?action=attachapprove;sa=all;mid=', $message['id'], ';', $context['session_var'], '=', $context['session_id'], '">', $txt['approve_all'], '</a>]';

                    echo '</legend>';
                  }

                  if ($attachment['is_image'])
                  {
                    if ($attachment['thumbnail']['has_thumb'])
                      echo '
                              <a href="', $attachment['href'], ';image" id="link_', $attachment['id'], '" onclick="', $attachment['thumbnail']['javascript'], '"><img src="', $attachment['thumbnail']['href'], '" alt="" id="thumb_', $attachment['id'], '" /></a><br />';
                    else
                      echo '
                              <img src="' . $attachment['href'] . ';image" alt="" width="' . $attachment['width'] . '" height="' . $attachment['height'] . '"/><br />';
                  }
                  echo '
                              <a href="' . $attachment['href'] . '"><img src="' . $settings['images_url'] . '/icons/clip.gif" align="middle" alt="*" />&nbsp;' . $attachment['name'] . '</a> ';

                  if (!$attachment['is_approved'] && $context['can_approve'])
                    echo '
                              [<a href="', $scripturl, '?action=attachapprove;sa=approve;aid=', $attachment['id'], ';', $context['session_var'], '=', $context['session_id'], '">', $txt['approve'], '</a>]&nbsp;|&nbsp;[<a href="', $scripturl, '?action=attachapprove;sa=reject;aid=', $attachment['id'], ';', $context['session_var'], '=', $context['session_id'], '">', $txt['delete'], '</a>] ';
                  echo '
                              (', $attachment['size'], ($attachment['is_image'] ? ', ' . $attachment['real_width'] . 'x' . $attachment['real_height'] . ' - ' . $txt['attach_viewed'] : ' - ' . $txt['attach_downloaded']) . ' ' . $attachment['downloads'] . ' ' . $txt['attach_times'] . '.)<br />';
                }

                // If we had unapproved attachments clean up.
                if ($last_approved_state == 0)
                  echo '
                            </fieldset>';

                echo '
                          </div>
                        </div>';
              }

              // Skip to next post
              echo '<div><a href="#postid_', $nextpost ,'" class="invisible button is-small is-dark mr-1">Skip to next post</a></div>';

              // Moderator Bar

              echo '
                <footer class="level">
                  <div class="level-left">
                    ';

                    // Show last edited
                    if ($settings['show_modify'] && !empty($message['modified']['name']))
                    echo'
                      <div class="is-muted is-size-7" id="modified_', $message['id'], '"> ', $txt['last_edit'], ': ', $message['modified']['time'], ' ', $txt['by'], ' ', $message['modified']['name'], '</div>
                      ';

                    echo'

                  </div>

                  <div class="level-right mr-2">';
                  // Report to moderator
                  if ($context['can_report_moderator'])
                    echo'<a class="button is-small" href="', $scripturl, '?action=reporttm;topic=', $context['current_topic'], '.', $message['counter'], ';msg=', $message['id'], '">', $txt['report_to_mod'], '</a> &nbsp';

                  // Issue a warning because of this post?
                  if ($context['can_issue_warning'] && !$message['is_message_author'] && !$message['member']['is_guest'])
                    echo'
                      <a class="button is-small mr-1" href="', $scripturl, '?action=reporttm;topic=', $context['current_topic'], '.', $message['counter'], ';msg=', $message['id'], '">
                        <span class="icon mr-1"><span class="fa fa-exclamation-triangle"></span></span> Warn
                        </a>
                    ';

                  // Show the IP to this user for this post - because you can moderate?
                  if ($context['can_moderate_forum'] && !empty($message['member']['ip']))
                    echo '
                        <a style="font-family: monospace;" href="', $scripturl, '?action=', !empty($message['member']['is_guest']) ? 'trackip' : 'profile;area=tracking;sa=ip;u=' . $message['member']['id'], ';searchip=', $message['member']['ip'], '">', $message['member']['ip'], '</a> <a class="tag is-rounded" href="', $scripturl, '?action=helpadmin;help=see_admin_ip" onclick="return reqWin(this.href);">?</a>';
                  // Or, should we show it because this is you?
                  elseif ($message['can_see_ip'])
                    echo '
                        <a href="', $scripturl, '?action=helpadmin;help=see_member_ip" onclick="return reqWin(this.href);" style="font-family: monospace;">', $message['member']['ip'], '</a>';
                  // Okay, are you at least logged in?  Then we can show something about why IPs are logged...
                  elseif (!$context['user']['is_guest'])
                    echo '
                        <a class="is-size-7" href="', $scripturl, '?action=helpadmin;help=see_member_ip" onclick="return reqWin(this.href);">', $txt['logged'], '</a>';
                  // Otherwise, you see NOTHING!
                  else
                    echo '';

                  echo'
                  </div>
                </footer>
            </div>
            ';

          echo'
          </div>';

          echo'
          <aside class="columns m-0 the-post-footer">
            <div class="column is-one-fifth-tablet p-0"></div>
            <div class="column is-four-fifths-tablet pl-0">';
            // Are there any custom profile fields for above the signature?
              if (!empty($message['member']['custom_fields']))
              {
                $shown = false;
                foreach ($message['member']['custom_fields'] as $custom)
                {
                  if ($custom['placement'] != 2 || empty($custom['value']))
                    continue;
                  if (empty($shown))
                  {
                    $shown = true;
                    echo '
                        <div class="custom_fields_above_signature">
                          <ul class="reset nolist">';
                  }
                  echo '
                            <li>', $custom['value'], '</li>';
                }
                if ($shown)
                  echo '
                          </ul>
                        </div>';
              }

              // Show the member's signature?
              if (!empty($message['member']['signature']) && empty($options['show_no_signatures']) && $context['signature_enabled'])
                echo '
                  <div class="is-hidden-mobile content post-signature pt-4 is-size-6-5" id="msg_', $message['id'], '_signature">', $message['member']['signature'], '</div>';
              echo'
            </div>
          </aside>';
        
        echo'
        </article>
      ';

  }

  echo '
        </form>
      </article>

      <div class="">
        <a id="lastPost"></a>';

      $mod_buttons = array(
        'move' => array('test' => 'can_move', 
         'text' => 'move_topic', 
         'image' => 'admin_move.gif',
         'icon' => 'fa-arrows', 
         'lang' => true, 
         'url' => $scripturl . '?action=movetopic;topic=' . $context['current_topic'] . '.0'), 
        
        'delete' => array('test' => 'can_delete', 
         'text' => 'remove_topic', 
         'image' => 'admin_rem.gif', 
         'class' => 'is-danger',
         'icon' => 'fa-times',
         'lang' => true, 
         'custom' => 'onclick="return confirm(\'' . $txt['are_sure_remove_topic'] . '\');"', 
         'url' => $scripturl . '?action=removetopic2;topic=' . $context['current_topic'] . '.0;' . $context['session_var'] . '=' . $context['session_id']), 
        
        'lock' => array('test' => 'can_lock', 
         'text' => empty($context['is_locked']) ? 'set_lock' : 'set_unlock', 
         'image' => 'admin_lock.gif', 
         'icon' => 'fa-lock',
         'lang' => true, 
         'url' => $scripturl . '?action=lock;topic=' . $context['current_topic'] . '.' . $context['start'] . ';' . $context['session_var'] . '=' . $context['session_id']), 
        
        'sticky' => array('test' => 'can_sticky', 
         'text' => empty($context['is_sticky']) ? 'set_sticky' : 'set_nonsticky', 
         'image' => 'admin_sticky.gif', 
         'icon' => 'fa-thumb-tack',
         'lang' => true, 
         'url' => $scripturl . '?action=sticky;topic=' . $context['current_topic'] . '.' . $context['start'] . ';' . $context['session_var'] . '=' . $context['session_id']), 
        
        'merge' => array('test' => 'can_merge', 
         'text' => 'merge', 
         'image' => 'merge.gif', 
         'icon' => 'fa-compress',
         'lang' => true, 
         'url' => $scripturl . '?action=mergetopics;board=' . $context['current_board'] . '.0;from=' . $context['current_topic']), 
        
        'calendar' => array('test' => 'calendar_post', 
         'text' => 'calendar_link', 
         'image' => 'linktocal.gif', 
         'icon' => 'fa-calendar-check-o',
         'lang' => true, 
         'url' => $scripturl . '?action=post;calendar;msg=' . $context['topic_first_message'] . ';topic=' . $context['current_topic'] . '.0'),    
      );

  // Restore topic. eh?  No monkey business.
  if ($context['can_restore_topic'])
    $mod_buttons[] = array('text' => 'restore_topic', 'image' => '', 'lang' => true, 'url' => $scripturl . '?action=restoretopic;topics=' . $context['current_topic'] . ';' . $context['session_var'] . '=' . $context['session_id']);  


  // Allow adding new mod buttons easily.
  call_integration_hook('integrate_mod_buttons', array(&$mod_buttons));

  // Show the page index... "Pages: [1]".
  echo '
    <div class="level mt-2 mb-2">
      <div class="level-left">
        <span class="is-muted is-size-6-5 is-uppercase">', $txt['pages'], ':&nbsp;</span>
        <span class="is-size-6-5 mr-3"> ', $context['page_index'], !empty($modSettings['topbottomEnable']) ? $context['menu_separator'] . '</span> 
        <a class="button is-small" href="#top">
          <span class="icon">
            <span class="fa fa-arrow-up"></span>
          </span>
          <span>' . $txt['go_up'] . '</span>
        </a>' : '', '
      </div>';

      // Show to button strip again
      echo '<div class="level-right" role="toolbar" aria-label="Topic toolbar">', template_button_strip($normal_buttons, 'right'), '</div>';
    echo'
    </div>';

    echo '
    <div id="moderationbuttons" class="mt-2 has-text-right-tablet" role="toolbar" aria-label="Moderator Toolbar">', template_button_strip($mod_buttons, 'bottom', array('id' => 'moderationbuttons_strip')), '</div>';

    // Tagging System
      echo '
      <div class="is-flex flex-wrap align-items-center mt-2 mb-2">
        <h2 class="title is-6 mb-0 mr-2">', $txt['smftags_topic'], '</h2>';
        echo'<ul class="tags mb-0">';
        foreach ($context['topic_tags'] as $i => $tag)
        {
          echo '
            <li>
              <div class="tags has-addons mr-2">
                <a class="tag" href="' . $scripturl . '?action=tags;tagid=' . $tag['ID_TAG']  . '">' . $tag['tag'] . '</a>';

                if(!$context['user']['is_guest'] && allowedTo('smftags_del'))
                  echo'
                    <a class="tag is-delete" href="' . $scripturl . '?action=tags;sa=deletetag;tagid=' . $tag['ID']  . '"></a>
                      ';
             echo'</div>
             </li>';
        }

        echo'</ul>';

        global $topic;
        if(!$context['user']['is_guest'] && allowedTo('smftags_add'))
        echo '
        <a class="button is-small" href="' . $scripturl . '?action=tags;sa=addtag;topic=',$topic, '">' . $txt['smftags_addtag'] . '</a>';

      echo '
        </div>';
    
    // End Tagging System

  if ($context['can_reply'] && !empty($options['display_quick_reply']))
  {
    echo '
      <a id="quickreply"></a>

      <div class="mt-4" id="quickreplybox">
        <div class="cat_bar">
          <h2 class="title is-5">
            <a href="javascript:oQuickReply.swap();">
              <span class="icon">
                <img src="', $settings['images_url'], '/', $options['display_quick_reply'] == 2 ? 'collapse' : 'expand', '.gif" alt="+" id="quickReplyExpand" style="display: none;" />
                <span class="fa fa-pencil"></span>
              </span>
            </a>
            <a href="javascript:oQuickReply.swap();">', $txt['quick_reply'], '</a>
          </h2>
        </div>
      </div>
      <div id="quickReplyOptions"', $options['display_quick_reply'] == 2 ? '' : ' style="display: none"', '>
        <div>
            <p class="is-muted is-size-6-5 mt-2 mb-2">', $txt['quick_reply_desc'], '</p>
            ', $context['is_locked'] ? '<p class="alert smalltext">' . $txt['quick_reply_warning'] . '</p>' : '',
            $context['oldTopicError'] ? '<p class="alert smalltext">' . sprintf($txt['error_old_topic'], $modSettings['oldTopicDays']) . '</p>' : '', '
            ', $context['can_reply_approved'] ? '' : '<em>' . $txt['wait_for_approval'] . '</em>', '
            ', !$context['can_reply_approved'] && $context['require_verification'] ? '<br />' : '', '
            <form action="', $scripturl, '?board=', $context['current_board'], ';action=post2" method="post" accept-charset="', $context['character_set'], '" name="postmodify" id="postmodify" onsubmit="submitonce(this);" style="margin: 0;">
              <input type="hidden" name="topic" value="', $context['current_topic'], '" />
              <input type="hidden" name="subject" value="', $context['response_prefix'], $context['subject'], '" />
              <input type="hidden" name="icon" value="xx" />
              <input type="hidden" name="from_qr" value="1" />
              <input type="hidden" name="notify" value="', $context['is_marked_notify'] || !empty($options['auto_notify']) ? '1' : '0', '" />
              <input type="hidden" name="not_approved" value="', !$context['can_reply_approved'], '" />
              <input type="hidden" name="goback" value="', empty($options['return_to_post']) ? '0' : '1', '" />
              <input type="hidden" name="last_msg" value="', $context['topic_last_message'], '" />
              <input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
              <input type="hidden" name="seqnum" value="', $context['form_sequence_number'], '" />';

      // Guests just need more.
      if ($context['user']['is_guest'])
        echo '
              <strong>', $txt['name'], ':</strong> <input type="text" name="guestname" value="', $context['name'], '" size="25" class="input" tabindex="', $context['tabindex']++, '" />
              <strong>', $txt['email'], ':</strong> <input type="text" name="email" value="', $context['email'], '" size="25" class="input" tabindex="', $context['tabindex']++, '" /><br />';

      // Is visual verification enabled?
      if ($context['require_verification'])
        echo '
              <strong>', $txt['verification'], ':</strong>', template_control_verification($context['visual_verification_id'], 'quick_reply'), '<br />';

      echo '
        <div class="quickReplyContent">
          <textarea class="textarea" cols="600" rows="7" name="message" tabindex="', $context['tabindex']++, '"></textarea>
        </div>
        <div class="mt-2">
          <input class="button is-primary" type="submit" name="post" value="', $txt['post'], '" onclick="return submitThisOnce(this);" accesskey="s" tabindex="', $context['tabindex']++, '" class="button is-primary" />
          <input class="button" type="submit" name="preview" value="', $txt['preview'], '" onclick="return submitThisOnce(this);" accesskey="p" tabindex="', $context['tabindex']++, '" class="button is-primary" />';

      if ($context['show_spellchecking'])
        echo '
            <input class="button" type="button" value="', $txt['spell_check'], '" onclick="spellCheck(\'postmodify\', \'message\');" tabindex="', $context['tabindex']++, '" class="button is-primary" />';

      echo '

          </div>
        </form>
      </div>
    </div>
    </div>';
  }

  else
    echo '';

  if ($context['show_spellchecking'])
    echo '
      <form action="', $scripturl, '?action=spellcheck" method="post" accept-charset="', $context['character_set'], '" name="spell_form" id="spell_form" target="spellWindow"><input type="hidden" name="spellstring" value="" /></form>
        <script type="text/javascript" src="' . $settings['default_theme_url'] . '/scripts/spellcheck.js"></script>';

  // Show the jumpto box, or actually...let Javascript do it.
  echo '
      <div class="plainbox mt-4 mb-2" id="display_jump_to">&nbsp;</div>';

  echo '
        <script type="text/javascript" src="' . $settings['default_theme_url'] . '/scripts/topic.js"></script>
        <script type="text/javascript"><!-- // --><![CDATA[';

  if (!empty($options['display_quick_reply']))
    echo '
          var oQuickReply = new QuickReply({
            bDefaultCollapsed: ', !empty($options['display_quick_reply']) && $options['display_quick_reply'] == 2 ? 'false' : 'true', ',
            iTopicId: ', $context['current_topic'], ',
            iStart: ', $context['start'], ',
            sScriptUrl: smf_scripturl,
            sImagesUrl: "', $settings['images_url'], '",
            sContainerId: "quickReplyOptions",
            sImageId: "quickReplyExpand",
            sImageCollapsed: "collapse.gif",
            sImageExpanded: "expand.gif",
            sJumpAnchor: "quickreply"
          });';

  if (!empty($options['display_quick_mod']) && $options['display_quick_mod'] == 1 && $context['can_remove_post'])
    echo '
          var oInTopicModeration = new InTopicModeration({
            sSelf: \'oInTopicModeration\',
            sCheckboxContainerMask: \'in_topic_mod_check_\',
            aMessageIds: [\'', implode('\', \'', $removableMessageIDs), '\'],
            sSessionId: \'', $context['session_id'], '\',
            sSessionVar: \'', $context['session_var'], '\',
            sButtonStrip: \'moderationbuttons\',
            sButtonStripDisplay: \'moderationbuttons_strip\',
            bUseImageButton: false,
            bCanRemove: ', $context['can_remove_post'] ? 'true' : 'false', ',
            sRemoveButtonLabel: \'', $txt['quickmod_delete_selected'], '\',
            sRemoveButtonImage: \'delete_selected.gif\',
            sRemoveButtonConfirm: \'', $txt['quickmod_confirm'], '\',
            bCanRestore: ', $context['can_restore_msg'] ? 'true' : 'false', ',
            sRestoreButtonLabel: \'', $txt['quick_mod_restore'], '\',
            sRestoreButtonImage: \'restore_selected.gif\',
            sRestoreButtonConfirm: \'', $txt['quickmod_confirm'], '\',
            sFormId: \'quickModForm\'
          });';

  echo '
          if (\'XMLHttpRequest\' in window)
          {
            var oQuickModify = new QuickModify({
              sScriptUrl: smf_scripturl,
              bShowModify: ', $settings['show_modify'] ? 'true' : 'false', ',
              iTopicId: ', $context['current_topic'], ',
              sTemplateBodyEdit: ', JavaScriptEscape('
                <div id="quick_edit_body_container" style="width: 90%">
                  <div id="error_box" style="padding: 4px;" class="error"></div>
                  <textarea class="editor" name="message" rows="12" style="' . ($context['browser']['is_ie8'] ? 'width: 635px; max-width: 100%; min-width: 100%' : 'width: 100%') . '; margin-bottom: 10px;" tabindex="' . $context['tabindex']++ . '">%body%</textarea><br />
                  <input type="hidden" name="' . $context['session_var'] . '" value="' . $context['session_id'] . '" />
                  <input type="hidden" name="topic" value="' . $context['current_topic'] . '" />
                  <input type="hidden" name="msg" value="%msg_id%" />
                  <div class="righttext">
                    <input type="submit" name="post" value="' . $txt['save'] . '" tabindex="' . $context['tabindex']++ . '" onclick="return oQuickModify.modifySave(\'' . $context['session_id'] . '\', \'' . $context['session_var'] . '\');" accesskey="s" class="button is-primary" />&nbsp;&nbsp;' . ($context['show_spellchecking'] ? '<input type="button" value="' . $txt['spell_check'] . '" tabindex="' . $context['tabindex']++ . '" onclick="spellCheck(\'quickModForm\', \'message\');" class="button is-primary" />&nbsp;&nbsp;' : '') . '<input type="submit" name="cancel" value="' . $txt['modify_cancel'] . '" tabindex="' . $context['tabindex']++ . '" onclick="return oQuickModify.modifyCancel();" class="button is-primary" />
                  </div>
                </div>'), ',
              sTemplateSubjectEdit: ', JavaScriptEscape('<input type="text" style="width: 90%;" name="subject" value="%subject%" size="80" maxlength="80" tabindex="' . $context['tabindex']++ . '" class="input" />'), ',
              sTemplateBodyNormal: ', JavaScriptEscape('%body%'), ',
              sTemplateSubjectNormal: ', JavaScriptEscape('<a href="' . $scripturl . '?topic=' . $context['current_topic'] . '.msg%msg_id%#msg%msg_id%" rel="nofollow">%subject%</a>'), ',
              sTemplateTopSubject: ', JavaScriptEscape($txt['topic'] . ': %subject% &nbsp;(' . $txt['read'] . ' ' . $context['num_views'] . ' ' . $txt['times'] . ')'), ',
              sErrorBorderStyle: ', JavaScriptEscape('1px solid red'), '
            });

            aJumpTo[aJumpTo.length] = new JumpTo({
              sContainerId: "display_jump_to",
              sJumpToTemplate: "<div class=\"is-flex align-items-center\"><label class=\"label is-small mr-2\" for=\"%select_id%\">', $context['jump_to']['label'], '</label><span class=\"select is-small is-flex\">%dropdown_list%</span></div></div></div>",
              iCurBoardId: ', $context['current_board'], ',
              iCurBoardChildLevel: ', $context['jump_to']['child_level'], ',
              sCurBoardName: "', $context['jump_to']['board_name'], '",
              sBoardChildLevelIndicator: "==",
              sBoardPrefix: "=> ",
              sCatSeparator: "-----------------------------",
              sCatPrefix: "",
              sGoButtonLabel: "', $txt['go'], '"
            });

            aIconLists[aIconLists.length] = new IconList({
              sBackReference: "aIconLists[" + aIconLists.length + "]",
              sIconIdPrefix: "msg_icon_",
              sScriptUrl: smf_scripturl,
              bShowModify: ', $settings['show_modify'] ? 'true' : 'false', ',
              iBoardId: ', $context['current_board'], ',
              iTopicId: ', $context['current_topic'], ',
              sSessionId: "', $context['session_id'], '",
              sSessionVar: "', $context['session_var'], '",
              sLabelIconList: "', $txt['message_icon'], '",
              sBoxBackground: "transparent",
              sBoxBackgroundHover: "#ffffff",
              iBoxBorderWidthHover: 1,
              sBoxBorderColorHover: "#adadad" ,
              sContainerBackground: "#ffffff",
              sContainerBorder: "1px solid #adadad",
              sItemBorder: "1px solid #ffffff",
              sItemBorderHover: "1px dotted gray",
              sItemBackground: "transparent",
              sItemBackgroundHover: "#e0e0f0"
            });
          }';

  if (!empty($ignoredMsgs))
  {
    echo '
          var aIgnoreToggles = new Array();';

    foreach ($ignoredMsgs as $msgid)
    {
      echo '
          aIgnoreToggles[', $msgid, '] = new smc_Toggle({
            bToggleEnabled: true,
            bCurrentlyCollapsed: true,
            aSwappableContainers: [
              \'msg_', $msgid, '_extra_info\',
              \'msg_', $msgid, '\',
              \'msg_', $msgid, '_footer\',
              \'msg_', $msgid, '_quick_mod\',
              \'modify_button_', $msgid, '\',
              \'msg_', $msgid, '_signature\'

            ],
            aSwapLinks: [
              {
                sId: \'msg_', $msgid, '_ignored_link\',
                msgExpanded: \'\',
                msgCollapsed: ', JavaScriptEscape($txt['show_ignore_user_post']), '
              }
            ]
          });';
    }
  }

  echo '
        // ]]></script>';

    

      echo'</div>';
    // Show the lower breadcrumbs.
    
    
  theme_linktree();
}

?>