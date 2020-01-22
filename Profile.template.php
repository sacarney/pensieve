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

// Template for the profile side bar - goes before any other profile template.
function template_profile_above()
{
  global $context, $settings, $options, $scripturl, $modSettings, $txt;

  echo '
  <script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/profile.js"></script>';

  // Prevent Chrome from auto completing fields when viewing/editing other members profiles
  if ($context['browser']['is_chrome'] && !$context['user']['is_owner'])
    echo '
  <script type="text/javascript"><!-- // --><![CDATA[
    disableAutoComplete();
  // ]]></script>';

  // If an error occurred while trying to save previously, give the user a clue!
  if (!empty($context['post_errors']))
    echo '
          ', template_error_message();

  // If the profile was update successfully, let the user know this.
  if (!empty($context['profile_updated']))
    echo '
    <div class="message is-success mt-2">
      <div class="message-body">
      ', $context['profile_updated'], '
      </div>
    </div>';
}

// Template for closing off table started in profile_above.
function template_profile_below()
{
}

// This template displays users details without any option to edit them.
function template_summary()
{
  global $context, $settings, $options, $scripturl, $modSettings, $txt;

  // Display the basic information about the user
  echo '
  <div class="columns is-multiline mt-2 pensieve-profile-summary">

    <div class="column is-6">';

      // Is this member requiring activation and/or banned?
      if (!empty($context['activate_message']) || !empty($context['member']['bans']))
      {
        // If the person looking at the summary has permission, and the account isn't activated, give the viewer the ability to do it themselves.
        if (!empty($context['activate_message']))
          echo '
          <div class="message is-warning">
            <div class="message-body">
              <span class="alert">', $context['activate_message'], '</span>&nbsp;(<a href="' . $scripturl . '?action=profile;save;area=activateaccount;u=' . $context['id_member'] . ';' . $context['session_var'] . '=' . $context['session_id'] . '"', ($context['activate_type'] == 4 ? ' onclick="return confirm(\'' . $txt['profileConfirm'] . '\');"' : ''), '>', $context['activate_link_text'], '</a>)
            </div>
          </div>
          ';

        // If the current member is banned, show a message and possibly a link to the ban.
        if (!empty($context['member']['bans']))
        {
          echo '
          <div class="message is-danger">
            <div class="message-header">
              <p>', $txt['user_is_banned'], '</p>
              <a class="button is-small" href="', $scripturl ,'?action=admin;area=ban;sa=list">' . $txt['view_ban'] . '</a>
            </div>
            <div class="message-body content">
              <p>', $txt['user_banned_by_following'], '</p>
              <ul>';
                foreach ($context['member']['bans'] as $ban)
                echo '<li>', $ban['explanation'], '</li>';
              echo'
              </ul>
            </div>
          </div>
          ';
        }
      }

      // Can they view/issue a warning?
      if ($context['can_view_warning'] && $context['member']['warning'])
      {
        echo '
        <div class="message is-warning">
          <div class="message-header">
            <p>', $txt['profile_warning_level'], ': ', $context['warning_status'], '</p>
            <a class="button is-small" href="', $scripturl, '?action=profile;u=', $context['id_member'], ';area=', $context['can_issue_warning'] ? 'issuewarning' : 'viewwarning', '">Edit</a>
          </div>
          <div class="message-body">
            <p>', $txt['profile_warning_level'], '</p>

            <progress class="progress is-warning" value="', $context['member']['warning'], '" max="100">', $context['member']['warning'], '%</progress>';
            echo'
          </div>
        </div>
        ';
      }

      echo'
      <div class="card">
        
        <div class="card-content has-text-centered">
        '; 

        // Online/Offline

        if ($context['member']['online']['text'] === 'Online') {
          echo'<div id="userstatus" class="tag is-success online-tag">' . $context['member']['online']['text'] . '</div>';
        } else {
          echo'<div id="userstatus" class="tag is-light online-tag">' . $context['member']['online']['text'] . '</div>';
        }

        echo'
          <div class="mb-3">', $context['member']['avatar']['image'], '</div>
          <h2 class="title is-4 mb-2">', $context['member']['name'], '</h2>
          <p class="is-uppercase is-muted">', (!empty($context['member']['group']) ? $context['member'][
            'group'] : $context['member']['post_group']), '</p>';
          
          if (!empty($modSettings['titlesEnable']) && !empty($context['member']['title']))
          echo '
          <p>', $context['member']['title'], '</p>';
          
        if (!empty($context['member']['blurb']))
        echo '
          <p class="tag mt-2">', $context['member']['blurb'], '</p>
        ';
        
        // Show the users signature.
        /*
        if ($context['signature_enabled'] && !empty($context['member']['signature']))
        echo '
          <hr>
          <div class="content has-text-left">', $context['member']['signature'], '</div>';
        */
        
        // Show the users signature. ADVANCED SIGNATURE
        for ($i=0; $i<count($context['member']['signature']) && $i<$modSettings['max_numberofSignatures']; $i++){
          if ($context['signature_enabled'] && !empty($context['member']['signature']))
          echo '<hr>
            <div class="content has-text-left">', $context['member']['signature'][$i], '
            </div>';
  }
  

      // Are there any custom profile fields for the summary?
    if (!empty($context['custom_fields']))
    { 
      echo'
      <hr>
        <ul class="is-flex justity-content-center">';
      foreach ($context['custom_fields'] as $field)
        if (($field['placement'] == 1 || empty($field['output_html'])) && !empty($field['value']))
        echo '
        <li class="custom_field mr-2">', $field['output_html'], '</li>';
      echo'
      </ul>';
    }

    echo '<ul class="is-flex justity-content-center mt-3">
      ', !isset($context['disabled_fields']['icq']) && !empty($context['member']['icq']['link']) ? '<li>' . $context['member']['icq']['link'] . '</li>' : '', '
      ', !isset($context['disabled_fields']['msn']) && !empty($context['member']['msn']['link']) ? '<li>' . $context['member']['msn']['link'] . '</li>' : '', '
      ', !isset($context['disabled_fields']['aim']) && !empty($context['member']['aim']['link']) ? '<li>' . $context['member']['aim']['link'] . '</li>' : '', '
      ', !isset($context['disabled_fields']['yim']) && !empty($context['member']['yim']['link']) ? '<li>' . $context['member']['yim']['link'] . '</li>' : '', '';
      
      echo'
      </ul>
        </div>
        <div class="card-footer">
          <div class="card-footer-item">
            <div class="field is-grouped">
              <p class="control">
        ';

            // Email
            if ($context['member']['show_email'] === 'yes' || $context['member']['show_email'] === 'no_through_forum' || $context['member']['show_email'] === 'yes_permission_override')
            echo '
            <a class="button is-outlined" href="', $scripturl, '?action=emailuser;sa=email;uid=', $context['member']['id'], '" title="', $context['member']['show_email'] == 'yes' || $context['member']['show_email'] == 'yes_permission_override' ? $context['member']['email'] : '', '" rel="nofollow"">
              <span class="icon">
                <span class="fa fa-envelope-o"></span>
                <span class="sr-only">Send email</span>
              </span>
            </a>
            ';

            // Website
            if ($context['member']['website']['url'] !== '' && !isset($context['disabled_fields']['website']))
            echo '
            <a class="button is-outlined" href="', $context['member']['website']['url'], '" title="' . $context['member']['website']['title'] . '" target="_blank">
              <span class="icon">
                <span class="fa fa-globe"></span>
                <span class="sr-only">', $txt['www'], '</span>
              </span>
            </a>
            ';
            
            echo' 
                </p>
              </div>
            </div>

        </div>
      </div>
    </div>

    <div class="column is-6">
      <div class="panel">

        <div class="panel-tabs">';
          // Send a PM
          if (!$context['user']['is_owner'] && $context['can_send_pm'])
          echo '
          <a href="', $scripturl, '?action=pm;sa=send;u=', $context['id_member'], '">
            <span class="icon"><span class="fa fa-envelope-o"></span></span>', $txt['profile_sendpm_short'], '</a>
          ';

          echo'
          <a href="', $scripturl, '?action=profile;area=showposts;u=', $context['id_member'], '"><span class="icon"><span class="fa fa-file-text-o"></span></span>', $txt['showPosts'], '</a>
          <a href="', $scripturl, '?action=profile;area=statistics;u=', $context['id_member'], '"><span class="icon"><span class="fa fa-bar-chart"></span></span>', $txt['statPanel'], '</a>';

          // Can they add this member as a buddy?
          if (!empty($context['can_have_buddy']) && !$context['user']['is_owner'])
          echo '
          <a href="', $scripturl, '?action=buddy;u=', $context['id_member'], ';', $context['session_var'], '=', $context['session_id'], '"><span class="icon"><span class="fa fa-heart-o"></span></span>', $txt['buddy_' . ($context['member']['is_buddy'] ? 'remove' : 'add')], '</a>';
          echo'
        </div>';

        if ($context['user']['is_owner'] || $context['user']['is_admin'])
        echo '
        <div class="panel-block is-block">
          <div class="is-flex justify-content-between">
            <div>', $txt['username'], '</div>
            <div>', $context['member']['username'], '</div>
          </div>
        </div>';

        // Only show the email address fully if it's not hidden - and we reveal the email.
        if ($context['member']['show_email'] == 'yes')
        echo '
        <div class="panel-block is-block">
          <div class="is-flex justify-content-between">
            <div>', $txt['email'], '</div>
            <div><a href="', $scripturl, '?action=emailuser;sa=email;uid=', $context['member']['id'], '">', $context['member']['email'], '</a></div>
          </div>
        </div>';

        // ... Or if the one looking at the profile is an admin they can see it anyway.
        elseif ($context['member']['show_email'] == 'yes_permission_override')
        echo '
        <div class="panel-block is-block">
          <div class="is-flex justify-content-between">
            <div>', $txt['email'], '</div>
            <div><a href="', $scripturl, '?action=emailuser;sa=email;uid=', $context['member']['id'], '">', $context['member']['email'], '</a></div>
          </div>
        </div>';

        if (!isset($context['disabled_fields']['posts']))
        {
        echo '
        <div class="panel-block is-block">
          <div class="is-flex justify-content-between">
            <div>', $txt['profile_posts'], '</div>
            <div>
              <div>', $context['member']['posts'], '<br>
                <span class="is-muted is-size-7 is-uppercase">(', $context['member']['posts_per_day'], ' ', $txt['posts_per_day'], ')</span>
              </div>
            </div>
          </div>
        </div>';

        if (isset($context['member']['subaccounts_posts']))
        echo '
        <div class="panel-block is-block">
          <div class="is-flex justify-content-between">
            <div>', $txt['subaccount_posts'], '</div>
            <div>
              <div>', $context['member']['subaccountsposts'], '<br>
              <span class="is-muted is-size-7 is-uppercase">(', $context['member']['subaccounts_posts_per_day'], ' ', $txt['posts_per_day'], ')</span>
              </div>
            </div>
          </div>
        </div>
        ';
        }

        if (!isset($context['disabled_fields']['gender']) && !empty($context['member']['gender']['name']))
        echo '
        <div class="panel-block is-block">
          <div class="is-flex justify-content-between">
            <div>', $txt['gender'], '</div>
            <div>', $context['member']['gender']['name'], '</div>
          </div>
        </div>';

        echo '
        <div class="panel-block is-block">
          <div class="is-flex justify-content-between">
            <div>', $txt['age'], '</div>
            <div>', $context['member']['age'] . ($context['member']['today_is_birthday'] ? ' &nbsp; <img src="' . $settings['images_url'] . '/cake.png" alt="" />' : ''), '</div>
          </div>
        </div>';

        if (!isset($context['disabled_fields']['location']) && !empty($context['member']['location']))
        echo '
        <div class="panel-block is-block">
          <div class="is-flex justify-content-between">
            <div>', $txt['location'], '</div>
            <div>', $context['member']['location'], '</div>
          </div>
        </div>';

        // Karma
        if ($modSettings['karmaMode'] == '1')
        echo'
        <div class="panel-block is-block">
          <div class="is-flex justify-content-between">
            <div>', $modSettings['karmaLabel'], '</div>
            <div>', ($context['member']['karma']['good'] - $context['member']['karma']['bad']), '</div>
          </div>
        </div>';

        elseif ($modSettings['karmaMode'] == '2')
        echo '
        <div class="panel-block is-block">
          <div class="is-flex justify-content-between">
            <div>', $modSettings['karmaLabel'], '</div>
            <div>+', $context['member']['karma']['good'], '/-', $context['member']['karma']['bad'], '</div>
          </div>
        </div>';

        // Registered
        echo'
        <div class="panel-block is-block">
          <div class="is-flex justify-content-between">
            <div>', $txt['date_registered'], '</div>
            <div>', $context['member']['registered'], '</div>
          </div>
        </div>
        ';

        // Last Active
        echo'
        <div class="panel-block is-block">
          <div class="is-flex justify-content-between">
            <div>', $txt['lastLoggedIn'], '</div>
            <div>', $context['member']['last_login'], '</div>
          </div>
        </div>
        ';

        // Local Time
        echo'
        <div class="panel-block is-block">
          <div class="is-flex justify-content-between">
            <div>', $txt['local_time'], '</div>
            <div>', $context['member']['local_time'], '</div>
          </div>
        </div>
        ';

        // Language
        if (!empty($modSettings['userLanguage']) && !empty($context['member']['language']))
        echo'
        <div class="panel-block is-block">
          <div class="is-flex justify-content-between">
            <div>', $txt['language'], '</div>
            <div>', $context['member']['language'], '</div>
          </div>
        </div>
        ';

        // If the person looking is allowed, they can check the members IP address and hostname.
        if ($context['can_see_ip'])
        {
          if (!empty($context['member']['ip']))
          echo'
          <div class="panel-block is-block">
            <div class="is-flex justify-content-between">
              <div>', $txt['ip'], '</div>
              <div><a href="', $scripturl, '?action=profile;area=tracking;sa=ip;searchip=', $context['member']['ip'], ';u=', $context['member']['id'], '">', $context['member']['ip'], '</a></div>
            </div>
          </div>
          ';

          if (empty($modSettings['disableHostnameLookup']) && !empty($context['member']['ip']))
            echo'
            <div class="panel-block is-block">
              <div class="is-flex justify-content-between">
                <div>', $txt['hostname'], '</div>
                <div>', $context['member']['hostname'], '</div>
              </div>
            </div>
            ';
        }

        // This is nasty, but hey, it works...
        if (!empty($context['member']['subaccounts']) && !empty($modSettings['subaccountsShowInProfile']))
        echo '
        <div class="panel-block is-block">
          <p>', $txt['subaccounts'], '</p>
          <p><a href="', $scripturl, '?action=profile;u=', implode('</a>, <a href="' . $scripturl . '?action=profile;u=', array_map(create_function('$id,$account', 'return $id . \'">\' . $account[\'name\'];'), array_keys($context['member']['subaccounts']), $context['member']['subaccounts'])), '</a></p>
        </div>';

        echo'

      </div><!-- end panel -->

    </div><!-- end column -->
  </div><!-- end columns -->
  ';

  echo'
  <div class="box">
  ';


    // Any custom fields for standard placement?
    if (!empty($context['custom_fields']))
    {
      $shown = false;
      foreach ($context['custom_fields'] as $field)
      {
        if ($field['placement'] != 0 || empty($field['output_html']))
          continue;

        if (empty($shown))
        {
          echo '
          <dl>';
          $shown = true;
        }

        echo '
            <dt>', $field['name'], ':</dt>
            <dd>', $field['output_html'], '</dd>';
      }

      if (!empty($shown))
        echo '
          </dl>';
    }


    // Are there any custom profile fields for the summary?
    if (!empty($context['custom_fields']))
    {
      $shown = false;
      foreach ($context['custom_fields'] as $field)
      {
        if ($field['placement'] != 2 || empty($field['output_html']))
          continue;
        if (empty($shown))
        {
          $shown = true;
          echo '
          <div class="custom_fields_above_signature">
            <ul class="reset nolist">';
        }
        echo '
              <li>', $field['output_html'], '</li>';
      }
      if ($shown)
          echo '
            </ul>
          </div>';
    }

  echo '
      </div><!-- end box -->
  ';
}

// Template for showing all the posts of the user, in chronological order.
function template_showPosts()
{
  global $context, $settings, $options, $scripturl, $modSettings, $txt;

  echo '
  <div class="pensieve-profile-showposts">
    <div class="cat_bar">
      <h3 class="title is-5 mb-4">
        ', (!isset($context['attachments']) && empty($context['is_topics']) ? $txt['showMessages'] : (!empty($context['is_topics']) ? $txt['showTopics'] : $txt['showAttachments'])), ' - ', $context['member']['name'], '
      </h3>
    </div>
    <nav class="pagination is-small mb-2">
      <span>', $txt['pages'], ': ', $context['page_index'], '</span>
    </nav>';

  // Button shortcuts
  $quote_button = create_button('quote.gif', 'reply_quote', 'quote', 'align="middle"');
  $reply_button = create_button('reply_sm.gif', 'reply', 'reply', 'align="middle"');
  $remove_button = create_button('delete.gif', 'remove_message', 'remove', 'align="middle"');
  $notify_button = create_button('notify_sm.gif', 'notify_replies', 'notify', 'align="middle"');

  // Are we displaying posts or attachments?
  if (!isset($context['attachments']))
  {
    // For every post to be displayed, give it its own div, and show the important details of the post.
    foreach ($context['posts'] as $post)
    {
      echo '
      <div class="box">
        <div class="is-flex">
          <div class="tag mr-2">', $post['counter'], '</div>
          <div>
            <h3>
              <a href="href="', $scripturl, '?board=', $post['board']['id'], '.0"">', $post['board']['name'], '</a> <span class="is-muted"> / </span> <a href="', $scripturl, '?topic=', $post['topic'], '.', $post['start'], '#msg', $post['id'], '">', $post['subject'], '</a>
            </h3>
            <p class="is-size-7">', $post['time'], '</p>
          </div>
        </div>
        <hr class="mt-2">
        ';

        if (!$post['approved'])
        echo'
        <div class="message is-warning">
          <div class="message-body">', $txt['post_awaiting_approval'], '</div>
        </div>
        ';

        echo'
        <div class="content">', $post['body'], '</div>
        
        <hr class="mt-2 mb-2">';

        if ($post['can_reply'] || $post['can_mark_notify'] || $post['can_delete'])
        echo '
        <div class="field is-grouped">
          <p class="control">
        ';

        // If they *can* reply?
        if ($post['can_reply'])
        echo '
        <a class="button is-primary is-small" href="', $scripturl, '?action=post;topic=', $post['topic'], '.', $post['start'], '">
          <span class="icon">
            <span class="fa fa-reply"></span>
          </span>
          <span>', $txt['reply'], '</span>
        </a>
        ';

        // If they *can* quote?
        if ($post['can_quote'])
        echo '
        <a class="button is-primary is-small" href="', $scripturl . '?action=post;topic=', $post['topic'], '.', $post['start'], ';quote=', $post['id'], '">
          <span class="icon">
            <span class="fa fa-quote-right"></span>
          </span>
          <span>', $txt['quote'], '</span>
        </a>
        ';

        // Can we request notification of topics?
        if ($post['can_mark_notify'])
        echo '
        <a class="button is-primary is-small" href="', $scripturl, '?action=notify;topic=', $post['topic'], '.', $post['start'], '">
          <span class="icon">
            <span class="fa fa-flag"></span>
          </span>
          <span>', $txt['notify'], '</span>
        </a>
        ';

        // How about... even... remove it entirely?!
        if ($post['can_delete'])
        echo '
        <a class="button is-primary is-small" href="', $scripturl, '?action=deletemsg;msg=', $post['id'], ';topic=', $post['topic'], ';profile;u=', $context['member']['id'], ';start=', $context['start'], ';', $context['session_var'], '=', $context['session_id'], '" onclick="return confirm(\'', $txt['remove_message'], '?\');">
          <span class="icon">
            <span class="fa fa-times"></span>
          </span>
          <span>', $txt['remove'], '</span>
        </a>
        ';


        if ($post['can_reply'] || $post['can_mark_notify'] || $post['can_delete'])
        echo '
          </p>
        </div>';

      echo'
      </div><!-- end box -->
      ';
    }
  }
  else
  {
    echo '
    <table class="table is-bordered is-striped" >
      <thead>
        <tr>
          <th scope="col" width="25%">
            <a href="', $scripturl, '?action=profile;u=', $context['current_member'], ';area=showposts;sa=attach;sort=filename', ($context['sort_direction'] == 'down' && $context['sort_order'] == 'filename' ? ';asc' : ''), '">
              ', $txt['show_attach_filename'], '
              ', ($context['sort_order'] == 'filename' ? '<img src="' . $settings['images_url'] . '/sort_' . ($context['sort_direction'] == 'down' ? 'down' : 'up') . '.gif" alt="" />' : ''), '
            </a>
          </th>
          <th scope="col" width="12%">
            <a href="', $scripturl, '?action=profile;u=', $context['current_member'], ';area=showposts;sa=attach;sort=downloads', ($context['sort_direction'] == 'down' && $context['sort_order'] == 'downloads' ? ';asc' : ''), '">
              ', $txt['show_attach_downloads'], '
              ', ($context['sort_order'] == 'downloads' ? '<img src="' . $settings['images_url'] . '/sort_' . ($context['sort_direction'] == 'down' ? 'down' : 'up') . '.gif" alt="" />' : ''), '
            </a>
          </th>
          <th scope="col" width="30%">
            <a href="', $scripturl, '?action=profile;u=', $context['current_member'], ';area=showposts;sa=attach;sort=subject', ($context['sort_direction'] == 'down' && $context['sort_order'] == 'subject' ? ';asc' : ''), '">
              ', $txt['message'], '
              ', ($context['sort_order'] == 'subject' ? '<img src="' . $settings['images_url'] . '/sort_' . ($context['sort_direction'] == 'down' ? 'down' : 'up') . '.gif" alt="" />' : ''), '
            </a>
          </th>
          <th scope="col">
            <a href="', $scripturl, '?action=profile;u=', $context['current_member'], ';area=showposts;sa=attach;sort=posted', ($context['sort_direction'] == 'down' && $context['sort_order'] == 'posted' ? ';asc' : ''), '">
            ', $txt['show_attach_posted'], '
            ', ($context['sort_order'] == 'posted' ? '<img src="' . $settings['images_url'] . '/sort_' . ($context['sort_direction'] == 'down' ? 'down' : 'up') . '.gif" alt="" />' : ''), '
            </a>
          </th>
        </tr>
      </thead>
      <tbody>';

    // Looks like we need to do all the attachments instead!
    $alternate = false;
    foreach ($context['attachments'] as $attachment)
    {
      echo '
        <tr class="', $attachment['approved'] ? ($alternate ? 'windowbg' : 'windowbg2') : 'approvebg', '">
          <td><a href="', $scripturl, '?action=dlattach;topic=', $attachment['topic'], '.0;attach=', $attachment['id'], '">', $attachment['filename'], '</a>', !$attachment['approved'] ? '&nbsp;<em>(' . $txt['awaiting_approval'] . ')</em>' : '', '</td>
          <td align="center">', $attachment['downloads'], '</td>
          <td><a href="', $scripturl, '?topic=', $attachment['topic'], '.msg', $attachment['msg'], '#msg', $attachment['msg'], '" rel="nofollow">', $attachment['subject'], '</a></td>
          <td>', $attachment['posted'], '</td>
        </tr>';
      $alternate = !$alternate;
    }

  // No posts? Just end the table with a informative message.
  if ((isset($context['attachments']) && empty($context['attachments'])) || (!isset($context['attachments']) && empty($context['posts'])))
    echo '
        <tr>
          <td colspan="4">
            ', isset($context['attachments']) ? $txt['show_attachments_none'] : ($context['is_topics'] ? $txt['show_topics_none'] : $txt['show_posts_none']), '
          </td>
        </tr>';

    echo '
      </tbody>
    </table>';
  }
  // Show more page numbers.
  echo '
    <nav class="pagination is-small mt-2">
      <span>', $txt['pages'], ': ', $context['page_index'], '</span>
    </nav>';

  echo '</div>';
}

// Template for showing all the buddies of the current user.
function template_editBuddies()
{
  global $context, $settings, $options, $scripturl, $modSettings, $txt;

  echo '
  <div class="pensieve-profile-buddies">
    <div class="cat_bar">
      <h3 class="title is-5 mb-4">', $txt['editBuddies'], '</h3>
    </div>

    <table class="table is-bordered is-striped" style="width: 100%">
      <tr>
        <th scope="col" width="20%">', $txt['name'], '</th>
        <th scope="col">', $txt['status'], '</th>
        <th scope="col">', $txt['email'], '</th>
        <th scope="col">', $txt['icq'], '</th>
        <th scope="col">', $txt['aim'], '</th>
        <th scope="col">', $txt['yim'], '</th>
        <th scope="col">', $txt['msn'], '</th>
        <th class="last_th" scope="col"></th>
      </tr>';

    // If they don't have any buddies don't list them!
    if (empty($context['buddies']))
      echo '
        <tr>
          <td colspan="8" align="center"><strong>', $txt['no_buddies'], '</strong></td>
        </tr>';

    // Now loop through each buddy showing info on each.
    $alternate = false;
    foreach ($context['buddies'] as $buddy)
    {
      echo '
        <tr>
          <td>', $buddy['link'], '</td>

          <td>';
            // Online/Offline

            if ($buddy['online']['text'] === 'Online') {
              echo'<div class="tag is-success">' . $buddy['online']['text'] . '</div>';
            } else {
              echo'<div class="tag is-light">' . $buddy['online']['text'] . '</div>';
            } 

          echo'
          </td>

          <td>', ($buddy['show_email'] == 'no' ? '' : '<a class="button" href="' . $scripturl . '?action=emailuser;sa=email;uid=' . $buddy['id'] . '" rel="nofollow"><span class="icon is-medium"><span class="fa fa-envelope-o"></span></span></a>'), '</td>

          <td align="center">', $buddy['icq']['link'], '</td>
          <td align="center">', $buddy['aim']['link'], '</td>
          <td align="center">', $buddy['yim']['link'], '</td>
          <td align="center">', $buddy['msn']['link'], '</td>

          <td>
            <a class="button is-danger is-small" href="', $scripturl, '?action=profile;area=lists;sa=buddies;u=', $context['id_member'], ';remove=', $buddy['id'], ';', $context['session_var'], '=', $context['session_id'], '">
              <span class="icon is-medium">
                <span class="fa fa-times"></span>
              </span>
              <span>Remove</span>
            </a>
          </td>
        </tr>';

      $alternate = !$alternate;
    }

  echo '
    </table>';

  // Add a new buddy?
  echo '
  <div class="box">
    <form action="', $scripturl, '?action=profile;u=', $context['id_member'], ';area=lists;sa=buddies" method="post" accept-charset="', $context['character_set'], '">

      <h3 class="title is-5 mb-4">', $txt['buddy_add'], '</h3>

      <label class="label" for="new_buddy">', $txt['who_member'], '</label>

      <div class="field has-addons">
        <div class="control">
          <input class="input" name="new_buddy" id="new_buddy" size="25">
        </div>
        <div class="control">
          <input type="submit" value="', $txt['buddy_add_button'], '" class="button is-primary">
        </div>
      </div>

    </form>
    </div>

    <script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/suggest.js?fin20"></script>
    <script type="text/javascript"><!-- // --><![CDATA[
      var oAddBuddySuggest = new smc_AutoSuggest({
        sSelf: \'oAddBuddySuggest\',
        sSessionId: \'', $context['session_id'], '\',
        sSessionVar: \'', $context['session_var'], '\',
        sSuggestId: \'new_buddy\',
        sControlId: \'new_buddy\',
        sSearchType: \'member\',
        sTextDeleteItem: \'', $txt['autosuggest_delete_item'], '\',
        bItemList: false
      });
    // ]]></script>
    ';

    echo '</div>';
}

// Template for showing the ignore list of the current user.
function template_editIgnoreList()
{
  global $context, $settings, $options, $scripturl, $modSettings, $txt;

  echo '
  <div class="pensieve-profile-ignore">
    <div class="cat_bar">
      <h3 class="title is-5 mb-4">', $txt['editIgnoreList'], '</h3>
    </div>

    <table class="table is-bordered is-striped" style="width: 100%">
      <tr>
        <th scope="col" width="20%">', $txt['name'], '</th>
        <th scope="col">', $txt['status'], '</th>
        <th scope="col">', $txt['email'], '</th>
        <th scope="col">', $txt['icq'], '</th>
        <th scope="col">', $txt['aim'], '</th>
        <th scope="col">', $txt['yim'], '</th>
        <th scope="col">', $txt['msn'], '</th>
        <th class="last_th" scope="col"></th>
      </tr>';

    // If they don't have anyone on their ignore list, don't list it!
    if (empty($context['ignore_list']))
      echo '
        <tr>
          <td colspan="8">', $txt['no_ignore'], '</td>
        </tr>';

    // Now loop through each buddy showing info on each.
    $alternate = false;
    foreach ($context['ignore_list'] as $member)
    {
      echo '
        <tr>
          <td>', $member['link'], '</td>

          <td>';
            // Online/Offline

            if ($member['online']['text'] === 'Online') {
              echo'<div class="tag is-success">' . $member['online']['text'] . '</div>';
            } else {
              echo'<div class="tag is-light">' . $member['online']['text'] . '</div>';
            } 

          echo'
          </td>

          <td>', ($member['show_email'] == 'no' ? '' : '<a class="button" href="' . $scripturl . '?action=emailuser;sa=email;uid=' . $member['id'] . '" rel="nofollow"><span class="icon is-medium"><span class="fa fa-envelope-o"></span></span></a>'), '</td>

          <td align="center">', $member['icq']['link'], '</td>
          <td align="center">', $member['aim']['link'], '</td>
          <td align="center">', $member['yim']['link'], '</td>
          <td align="center">', $member['msn']['link'], '</td>

          <td>
            <a class="button is-danger is-small" href="', $scripturl, '?action=profile;u=', $context['id_member'], ';area=lists;sa=ignore;remove=', $member['id'], ';', $context['session_var'], '=', $context['session_id'], '">
              <span class="icon is-medium">
                <span class="fa fa-times"></span>
              </span>
              <span>Remove</span>
            </a>
          </td>
        </tr>';

      $alternate = !$alternate;
    }

    echo '
      </table>';

    // Add a new enemy?
    echo '
    <div class="box">
      <form action="', $scripturl, '?action=profile;u=', $context['id_member'], ';area=lists;sa=ignore" method="post" accept-charset="', $context['character_set'], '">

        <h3 class="title is-5 mb-4">', $txt['ignore_add'], '</h3>

        <label class="label" for="new_buddy">', $txt['who_member'], '</label>

        <div class="field has-addons">
          <div class="control">
            <input class="input" name="new_buddy" id="new_ignore" size="25">
          </div>
          <div class="control">
            <input type="submit" value="', $txt['ignore_add_button'], '" class="button is-primary">
          </div>
        </div>
      </form>
    </div>
    <script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/suggest.js?fin20"></script>
    <script type="text/javascript"><!-- // --><![CDATA[
      var oAddIgnoreSuggest = new smc_AutoSuggest({
        sSelf: \'oAddIgnoreSuggest\',
        sSessionId: \'', $context['session_id'], '\',
        sSessionVar: \'', $context['session_var'], '\',
        sSuggestId: \'new_ignore\',
        sControlId: \'new_ignore\',
        sSearchType: \'member\',
        sTextDeleteItem: \'', $txt['autosuggest_delete_item'], '\',
        bItemList: false
      });
    // ]]></script>';

    echo '</div>';
}

// This template shows an admin information on a users IP addresses used and errors attributed to them.
function template_trackActivity()
{
  global $context, $settings, $options, $scripturl, $txt;

  // The first table shows IP information about the user.
  echo '
  <div class="pensieve-profile-trackactivity">
    <div class="title_bar">
      <h3 class="title is-5 mb-2">', $txt['view_ips_by'], ' ', $context['member']['name'], '</h3>
    </div>';

    // The last IP the user used.
    echo '
    <div>
      <dl class="mb-4">
        <dt><b>', $txt['most_recent_ip'], ':</b>
          ', (empty($context['last_ip2']) ? '' : '<br />
          <span class="smalltext">(<a href="' . $scripturl . '?action=helpadmin;help=whytwoip" onclick="return reqWin(this.href);">' . $txt['why_two_ip_address'] . '</a>)</span>'), '
        </dt>
        <dd class="mb-3">
          <span><a href="', $scripturl, '?action=profile;area=tracking;sa=ip;searchip=', $context['last_ip'], ';u=', $context['member']['id'], '">', $context['last_ip'], '</a></span>';

    // Second address detected?
    if (!empty($context['last_ip2']))
      echo ', <span><a href="', $scripturl, '?action=profile;area=tracking;sa=ip;searchip=', $context['last_ip2'], ';u=', $context['member']['id'], '">', $context['last_ip2'], '</a></span>';

    echo '</dd>';

    // Lists of IP addresses used in messages / error messages.
    echo '
        <dt><b>', $txt['ips_in_messages'], ':</b></dt>
        <dd class="mb-3">
          ', (count($context['ips']) > 0 ? implode(', ', $context['ips']) : '(' . $txt['none'] . ')'), '
        </dd>
        <dt>', $txt['ips_in_errors'], ':</dt>
        <dd class="mb-3">
          ', (count($context['ips']) > 0 ? implode(', ', $context['error_ips']) : '(' . $txt['none'] . ')'), '
        </dd>';

    // List any members that have used the same IP addresses as the current member.
    echo '
        <dt><b>', $txt['members_in_range'], ':</b></dt>
        <dd class="mb-3">
          ', (count($context['members_in_range']) > 0 ? implode(', ', $context['members_in_range']) : '(' . $txt['none'] . ')'), '
        </dd>
      </dl>
    </div>
    ';

  // Show the track user list.
  template_show_list('track_user_list');

  echo '</div>';
}

// The template for trackIP, allowing the admin to see where/who a certain IP has been used.
function template_trackIP()
{
  global $context, $settings, $options, $scripturl, $txt;

  // This function always defaults to the last IP used by a member but can be set to track any IP.
  // The first table in the template gives an input box to allow the admin to enter another IP to track.
  echo '
  <div class="pensieve-profile-trackip">
    <div class="title_bar">
      <h3 class="title is-5 mb-2">', $txt['trackIP'], '</h3>
    </div>

    <form action="', $context['base_url'], '" method="post" accept-charset="', $context['character_set'], '">
      <label class="label" for="search_ip">', $txt['enter_ip'], '</label>
      <div class="field has-addons">
        <div class="control">
          <input class="input" value="', $context['ip'], '" type="text" name="searchip" id="search_ip" size="25">
        </div>
        <div class="control">
          <input value="', $txt['trackIP'], '" type="submit" class="button is-primary">
        </div>
      </div>

    </form>
    ';

    // The table inbetween the first and second table shows links to the whois server for every region.
    if ($context['single_ip'])
    {
      echo '
      <hr>
      <div class="title_bar">
        <h3 class="title is-5 mb-2">', $txt['whois_title'], ' ', $context['ip'], '</h3>
      </div>

      <div class="box">';
        foreach ($context['whois_servers'] as $server)
          echo '
            <a href="', $server['url'], '" target="_blank" class="new_win"', isset($context['auto_whois_server']) && $context['auto_whois_server']['name'] == $server['name'] ? ' style="font-weight: bold;"' : '', '>', $server['name'], '</a><br />';
        echo '
      </div>
      ';
    }

    // The second table lists all the members who have been logged as using this IP address.
    echo '
    <hr>
    <div class="title_bar">
      <h3 class="title is-5 mb-2">', $txt['members_from_ip'], ' ', $context['ip'], '</h3>
    </div>
    ';

  if (empty($context['ips']))
    echo '
    <p>', $txt['no_members_from_ip'], '</p>';
  else
  {
    echo '
    <table class="table is-bordered is-striped" style="width: 100%;">
      <thead>
        <tr>
          <th scope="col">', $txt['ip_address'], '</th>
          <th scope="col">', $txt['display_name'], '</th>
        </tr>
      </thead>
      <tbody>';

    // Loop through each of the members and display them.
    foreach ($context['ips'] as $ip => $memberlist)
      echo '
        <tr>
          <td><a href="', $context['base_url'], ';searchip=', $ip, '">', $ip, '</a></td>
          <td>', implode(', ', $memberlist), '</td>
        </tr>';

    echo '
      </tbody>
    </table>
  ';
  }

  template_show_list('track_message_list');

  echo '<br />';

  template_show_list('track_user_list');

  echo '</div>';
}

function template_showPermissions()
{
  global $context, $settings, $options, $scripturl, $txt;

  echo '
  <div class="pensieve-profile-showpermissions">
    <div class="catbg">
      <h2 class="title is-4 mb-4">
        <span class="fa fa-user-circle"></span>
        <span>', $txt['showPermissions'], '</span>
      </h2>
    </div>';

  if ($context['member']['has_all_permissions'])
  {
    echo '
    <p>', $txt['showPermissions_all'], '</p>';
  }
  else
  {
    echo '
    <p class="notification is-size-7">',$txt['showPermissions_help'],'</p>
    
    <div id="permissions" class="flow_hidden">';

    if (!empty($context['no_access_boards']))
    {
      echo '
      <div class="title_bar">
        <h3 class="title is-5 mb-2">', $txt['showPermissions_restricted_boards'], '</h3>
      </div>
      
      <div class="content">', $txt['showPermissions_restricted_boards_desc'], ':<br />';
        foreach ($context['no_access_boards'] as $no_access_board)
          echo '
            <a href="', $scripturl, '?board=', $no_access_board['id'], '.0">', $no_access_board['name'], '</a>', $no_access_board['is_last'] ? '' : ', ';
        echo '
          </div>
        </div>';
    }

    // General Permissions section.
    echo '
    <div class="cat_bar">
      <h3 class="title is-5 mb-2">', $txt['showPermissions_general'], '</h3>
    </div>';
    if (!empty($context['member']['permissions']['general']))
    {
      echo '
          <table class="table is-bordered is-striped is-narrow" style="width: 100%;">
            <thead>
              <tr">
                <th scope="col" width="50%">', $txt['showPermissions_permission'], '</th>
                <th scope="col" width="50%">', $txt['showPermissions_status'], '</th>
              </tr>
            </thead>
            <tbody>';

      foreach ($context['member']['permissions']['general'] as $permission)
      {
        echo '
        <tr>
          <td title="', $permission['id'], '">
            ', $permission['is_denied'] ? '<del>' . $permission['name'] . '</del>' : $permission['name'], '
          </td>
          <td>';

        if ($permission['is_denied'])
          echo '
            <span class="has-text-danger">', $txt['showPermissions_denied'], ':&nbsp;', implode(', ', $permission['groups']['denied']),'</span>';
        else
          echo '
                  ', $txt['showPermissions_given'], ':&nbsp;', implode(', ', $permission['groups']['allowed']);

          echo '
                </td>
              </tr>';
      }
      echo '
            </tbody>
          </table>
    ';
    }
    else
      echo '
      <p class="notification is-size-7">', $txt['showPermissions_none_general'], '</p>';

    // Board permission section.
    echo '
      <form action="' . $scripturl . '?action=profile;u=', $context['id_member'], ';area=permissions#board_permissions" method="post" accept-charset="', $context['character_set'], '">
        
        <a id="board_permissions"></a>
        <div class="field is-grouped mb-2">
          <div class="control">
            <h3 class="title is-5">', $txt['showPermissions_select'], '</h3>
          </div>
          <div class="control">
            <div class="select">
              <select name="board" onchange="if (this.options[this.selectedIndex].value) this.form.submit();">
                <option value="0"', $context['board'] == 0 ? ' selected="selected"' : '', '>', $txt['showPermissions_global'], '&nbsp;</option>';
        
                if (!empty($context['boards']))
                echo '
                <option value="" disabled="disabled">----------------</option>';

                // Fill the box with any local permission boards.
                foreach ($context['boards'] as $board)
                echo '
                <option value="', $board['id'], '"', $board['selected'] ? ' selected="selected"' : '', '>', $board['name'], ' (', $board['profile_name'], ')</option>';
                echo '
              </select>
            </div>
          </div>
        </div>
      </form>';
    if (!empty($context['member']['permissions']['board']))
    {
      echo '
        <table class="table is-bordered is-striped" style="width: 100%;">
          <thead>
            <tr>
              <th scope="col" width="50%">', $txt['showPermissions_permission'], '</th>
              <th scope="col" width="50%">', $txt['showPermissions_status'], '</th>
            </tr>
          </thead>
          <tbody>';
      foreach ($context['member']['permissions']['board'] as $permission)
      {
        echo '
            <tr>
              <td title="', $permission['id'], '">
                ', $permission['is_denied'] ? '<del>' . $permission['name'] . '</del>' : $permission['name'], '
              </td>
              <td>';

        if ($permission['is_denied'])
        {
          echo '
                <span class="has-text-danger">', $txt['showPermissions_denied'], ':&nbsp;', implode(', ', $permission['groups']['denied']), '</span>';
        }
        else
        {
          echo '
                ', $txt['showPermissions_given'], ': &nbsp;', implode(', ', $permission['groups']['allowed']);
        }
        echo '
              </td>
            </tr>';
      }
      echo '
          </tbody>
        </table>';
    }
    else
      echo '
      <p class="notification is-size-7">', $txt['showPermissions_none_board'], '</p>';
  echo '
      </div>
    </div>
  </div>';
  }
}

// Template for user statistics, showing graphs and the like.
function template_statPanel()
{
  global $context, $settings, $options, $scripturl, $modSettings, $txt;

  // First, show a few text statistics such as post/topic count.
  echo '
  <div class="pensieve-profile-statpanel">
    <div class="box mt-4">
      <h2 class="title is-4 mb-4">', $txt['statPanel_generalStats'], ' - ', $context['member']['name'], '</h2>
      <div>
        <dl class="columns is-multiline is-gapless">
          <dt class="column is-4 no-float has-text-white">', $txt['statPanel_total_time_online'], ':</dt>
          <dd class="column is-8 no-float">', $context['time_logged_in'], '</dd>
          <dt class="column is-4 no-float has-text-white">', $txt['statPanel_total_posts'], ':</dt>
          <dd class="column is-8 no-float">', $context['num_posts'], ' ', $txt['statPanel_posts'], '</dd>
          <dt class="column is-4 no-float has-text-white">', $txt['statPanel_total_topics'], ':</dt>
          <dd class="column is-8 no-float">', $context['num_topics'], ' ', $txt['statPanel_topics'], '</dd>
          <dt class="column is-4 no-float has-text-white">', $txt['statPanel_users_polls'], ':</dt>
          <dd class="column is-8 no-float">', $context['num_polls'], ' ', $txt['statPanel_polls'], '</dd>
          <dt class="column is-4 no-float has-text-white">', $txt['statPanel_users_votes'], ':</dt>
          <dd class="column is-8 no-float">', $context['num_votes'], ' ', $txt['statPanel_votes'], '</dd>
        </dl>
      </div>
    </div>';

    // This next section draws a graph showing what times of day they post the most.
    echo '
    <div class="box">
      <h2 class="title is-4 mb-4">', $txt['statPanel_generalStats'], ' - ', $context['member']['name'], '</h2>
     ';

    // If they haven't post at all, don't draw the graph.
    if (empty($context['posts_by_time']))
      echo '
        <span>', $txt['statPanel_noPosts'], '</span>';
    
    // Otherwise do!
    else
    {
      echo '
        <ul class="stats-graph is-size-7">';

      // The labels.
      foreach ($context['posts_by_time'] as $time_of_day)
      {
        echo '
        <li>
          <div class="stats-graph_bar" style="height: ', ((int) ($time_of_day['relative_percent'])), '%;"></div>
          <span class="stats-graph_hour">', $time_of_day['hour_format'], '</span>
        </li>';

      /*
      <li', $time_of_day['is_last'] ? ' class="last"' : '', '>
        <div class="bar" style="padding-top: ', ((int) (100 - $time_of_day['relative_percent'])), 'px;" title="', sprintf($txt['statPanel_activityTime_posts'], $time_of_day['posts'], $time_of_day['posts_percent']), '">
          <div style="height: ', (int) $time_of_day['relative_percent'], 'px;">
            <span>', sprintf($txt['statPanel_activityTime_posts'], $time_of_day['posts'], $time_of_day['posts_percent']), '</span>
          </div>
        </div>
        <span class="stats_hour">', $time_of_day['hour_format'], '</span>
      </li>'; */
    }

    echo '
    </ul>';
  }

  echo '
  </div>';

  // Two columns with the most popular boards by posts and activity (activity = users posts / total posts).
  echo '
  <div class="columns">
    <div class="column">
      <div class="box h-100">
        <h2 class="title is-4 mb-4">', $txt['statPanel_topBoards'], '</h2>
        <div class="">';

        if (empty($context['popular_boards']))
        echo '
        <span>', $txt['statPanel_noPosts'], '</span>';

        else {
          echo '
          <dl class="columns is-mobile is-multiline is-gapless">';

          // Draw a bar for every board.
          foreach ($context['popular_boards'] as $board)
          {
          echo '
            <dt class="column is-9 mb-2 no-float">', $board['link'], '</dt>
            <dd class="column is-3 mb-2 no-float">
              <div class="tag is-small">', empty($context['hide_num_posts']) ? $board['posts'] : '', ' (', round($board['posts_percent'], 2) ,'%)</div>
            </dd>';
          }

          echo '
          </dl>';
        }
        echo '
        </div>
      </div>
    </div>';
    echo '
     <div class="column">
      <div class="box h-100">
        <h2 class="title is-4 mb-4">', $txt['statPanel_topBoardsActivity'], '</h2>
        <div class="">';

          if (empty($context['board_activity']))
          echo '
          <span>', $txt['statPanel_noPosts'], '</span>';
          
          else
          {
            echo '
            <dl class="columns is-multiline is-gapless">';

            // Draw a bar for every board.
            foreach ($context['board_activity'] as $activity)
            {
            echo '
              <dt class="column is-9 mb-2 no-float">', $activity['link'], '</dt>
              <dd class="column is-3 mb-2 no-float">
                <div class="tag is-small">', $activity['percent'], '%</div>
              </dd>';
            }

            echo '
            </dl>';
          }
          echo '
          </div>
        </div>
      </div>
    </div>
  </div>';
}

// Template for editing profile options.
function template_edit_options()
{
  global $context, $settings, $options, $scripturl, $modSettings, $txt;

  // The main header!
  echo '
    <form action="', (!empty($context['profile_custom_submit_url']) ? $context['profile_custom_submit_url'] : $scripturl . '?action=profile;area=' . $context['menu_item_selected'] . ';u=' . $context['id_member'] . ';save'), '" method="post" accept-charset="', $context['character_set'], '" name="creator" id="creator" enctype="multipart/form-data" onsubmit="return checkProfileSubmit();">

      <div class="cat_bar">
        <h3 class="title is-5 mb-4">';

          // Don't say "Profile" if this isn't the profile...
          if (!empty($context['profile_header_text']))
            echo '
                ', $context['profile_header_text'];
          else
            echo '
                ', $txt['profile'];

        echo'</h3>
      </div>
      ';

  // Have we some description?
  if ($context['page_desc'])
    echo '<p class="notification is-size-7">', $context['page_desc'], '</p>';

  // Any bits at the start?
  if (!empty($context['profile_prehtml']))
    echo '<div>', $context['profile_prehtml'], '</div>';

  if (!empty($context['profile_fields']))
    echo '
    <div>';

  // Start the big old loop 'of love.
  $lastItem = 'hr';
  foreach ($context['profile_fields'] as $key => $field)
  {
    // We add a little hack to be sure we never get more than one hr in a row!
    if ($lastItem == 'hr' && $field['type'] == 'hr')
      continue;

    $lastItem = $field['type'];
    if ($field['type'] == 'hr')
    {
      echo '
          </div>
          <hr>
          <div>';
    }
    elseif ($field['type'] == 'callback')
    {
      if (isset($field['callback_func']) && function_exists('template_profile_' . $field['callback_func']))
      {
        $callback_func = 'template_profile_' . $field['callback_func'];
        $callback_func();
      }
    }
    else
    {
      if ($field['type'] == 'check') {
        echo'
          <div class="field">
            <div class="control">
              <label', !empty($field['is_error']) ? ' class="label checkbox has-text-danger"' : ' class="label"', '>
                <input type="checkbox" name="', $key, '" id="', $key, '" ', !empty($field['value']) ? ' checked="checked"' : '', ' value="1" class="checkbox" ', $field['input_attr'], ' /> ', $field['label'], '
              </label>
            </div>';
            // Does it have any subtext to show?
            if (!empty($field['subtext']))
            echo '
              <p class="help">', $field['subtext'], '</p>';
          echo'
          </div>';
      } else {

        echo '
          <div class="field is-horizontal">
            <div class="field-label has-text-left">
              <label', !empty($field['is_error']) ? ' class="label has-text-danger"' : ' class="label"', '>', $field['label'], '</label>
            </div>';

            // Want to put something infront of the box?
            if (!empty($field['preinput']))
              echo '
                    ', $field['preinput'];

            // What type of data are we showing?
            if ($field['type'] == 'label')
              echo '<div class="field-body">
                    ', $field['value'],'</div>';

            // Maybe it's a text box - very likely!
            elseif (in_array($field['type'], array('int', 'float', 'text', 'password'))) {
              echo '
              <div class="field-body">
                <div class="field">
                  <div class="control">
                    <input type="', $field['type'] == 'password' ? 'password' : 'text', '" name="', $key, '" id="', $key, '" size="', empty($field['size']) ? 30 : $field['size'], '" value="', $field['value'], '" ', $field['input_attr'], ' class="input is-auto" />
                  </div>';
                  // Does it have any subtext to show?
                  if (!empty($field['subtext'])) echo '<p class="help">', $field['subtext'], '</p>';
                  echo'
                </div>
              </div>
              ';
            }

            // Always fun - select boxes!
            elseif ($field['type'] == 'select')
            {
              echo '
              <div class="field-body">
                <div class="field">
                  <div class="control">
                    <div class="select">
                      <select name="', $key, '" id="', $key, '">';
                        if (isset($field['options']))
                        {
                          // Is this some code to generate the options?
                          if (!is_array($field['options']))
                            $field['options'] = eval($field['options']);
                          // Assuming we now have some!
                          if (is_array($field['options']))
                            foreach ($field['options'] as $value => $name)
                              echo '
                                <option value="', $value, '" ', $value == $field['value'] ? 'selected="selected"' : '', '>', $name, '</option>';
                        }

                        echo '
                      </select>
                    </div>
                  </div>';
                  // Does it have any subtext to show?
                  if (!empty($field['subtext'])) echo '<p class="help">', $field['subtext'], '</p>';
                  echo'
                </div>
              </div>';
          }

      // Something to end with?
      if (!empty($field['postinput']))
        echo '', $field['postinput'];

      // Does it have any subtext to show?
      //if (!empty($field['subtext'])) echo '<p class="help">', $field['subtext'], '</p>';

        echo '
          </div>';
      }
    }
  }

  if (!empty($context['profile_fields']))
    echo '
          </div>';

  // Are there any custom profile fields - if so print them!
  if (!empty($context['custom_fields']))
  {
    if ($lastItem != 'hr')
      echo '
          <hr>';

    echo '
          <div>';

    foreach ($context['custom_fields'] as $field)
    {
      echo '
            <div class="field is-horizontal">
              <div class="field-label has-text-left">
                <label class="label">', $field['name'], '</label>
              </div>
              <div class="field-body">
                <div class="field">
                  <div class="control">
                    ', $field['input_html'], '
                  </div>
                  <p class="help">', $field['desc'], '</p>
                </div>
              </div>
            </div>';
    }
    echo '
          </div>';

  }

  // Any closing HTML?
  if (!empty($context['profile_posthtml']))
    echo '<div>', $context['profile_posthtml'], '</div>';
  elseif ($lastItem != 'hr')
    echo ' <hr>';

  // Only show the password box if it's actually needed.
  if ($context['require_password'])
    echo '
      <div class="field">
        <label ', isset($context['modify_error']['bad_password']) || isset($context['modify_error']['no_password']) ? ' class="is-danger label"' : ' class="label"', '>', $txt['current_password'], '</label>
        <div class="control">
          <input type="password" name="oldpasswrd" size="20" style="margin-right: 4ex;" class="input is-auto is-primary" />
        </div>
        <p class="help">', $txt['required_security_reasons'], '</p>
      </div>
      ';

  echo '<div>';

  // The button shouldn't say "Change profile" unless we're changing the profile...
  if (!empty($context['submit_button_text']))
    echo '<input type="submit" value="', $context['submit_button_text'], '" class="button is-primary" />';
  else
    echo '<input type="submit" value="', $txt['change_profile'], '" class="button is-primary" />';

  echo '
            <input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
            <input type="hidden" name="u" value="', $context['id_member'], '" />
            <input type="hidden" name="sa" value="', $context['menu_item_selected'], '" />
          </div>
        </div>
    </form>';

  // Some javascript!
  echo '
    <script type="text/javascript"><!-- // --><![CDATA[
      function checkProfileSubmit()
      {';

  // If this part requires a password, make sure to give a warning.
  if ($context['require_password'])
    echo '
        // Did you forget to type your password?
        if (document.forms.creator.oldpasswrd.value == "")
        {
          alert("', $txt['required_security_reasons'], '");
          return false;
        }';

  // Any onsubmit javascript?
  if (!empty($context['profile_onsubmit_javascript']))
    echo '
        ', $context['profile_javascript'];

  echo '
      }';

  // Any totally custom stuff?
  if (!empty($context['profile_javascript']))
    echo '
      ', $context['profile_javascript'];

  echo '
    // ]]></script>';

  // Any final spellchecking stuff?
  if (!empty($context['show_spellchecking']))
    echo '
    <form name="spell_form" id="spell_form" method="post" accept-charset="', $context['character_set'], '" target="spellWindow" action="', $scripturl, '?action=spellcheck"><input type="hidden" name="spellstring" value="" /></form>';
}

// Personal Message settings.
function template_profile_pm_settings()
{
  global $context, $settings, $options, $scripturl, $modSettings, $txt;

  echo '

  <div class="field is-horizontal">
    <div class="field-label has-text-left">
      <label class="label" for="pm_prefs">', $txt['pm_display_mode'], '</label>
    </div>
    <div class="field-body">
      <div class="field">
        <div class="control">
          <div class="select">
            <select name="pm_prefs" id="pm_prefs" onchange="if (this.value == 2 &amp;&amp; !document.getElementById(\'copy_to_outbox\').checked) alert(\'', $txt['pm_recommend_enable_outbox'], '\');">
              <option value="0"', $context['display_mode'] == 0 ? ' selected="selected"' : '', '>', $txt['pm_display_mode_all'], '</option>
              <option value="1"', $context['display_mode'] == 1 ? ' selected="selected"' : '', '>', $txt['pm_display_mode_one'], '</option>
              <option value="2"', $context['display_mode'] == 2 ? ' selected="selected"' : '', '>', $txt['pm_display_mode_linked'], '</option>
            </select>
          </div>
        </div>
        <div class="control mt-3">
          <label class="checkbox for="view_newest_pm_first">
            <input type="hidden" name="default_options[view_newest_pm_first]" value="0" />
            <input type="checkbox" name="default_options[view_newest_pm_first]" id="view_newest_pm_first" value="1"', !empty($context['member']['options']['view_newest_pm_first']) ? ' checked="checked"' : '', ' class="input_check" /> ', $txt['recent_pms_at_top'], '
          </label>
        </div>
      </div>
    </div>
  </div>
  
  <hr>

  <div class="field is-horizontal">
    <div class="field-label has-text-left">
      <label class="label" for="pm_receive_from">', $txt['pm_receive_from'], '</label>
    </div>
    <div class="field-body">
      <div class="field">
        <div class="control">
          <div class="select">
            <select name="pm_receive_from" id="pm_receive_from">
              <option value="0"', empty($context['receive_from']) || (empty($modSettings['enable_buddylist']) && $context['receive_from'] < 3) ? ' selected="selected"' : '', '>', $txt['pm_receive_from_everyone'], '</option>';

              if (!empty($modSettings['enable_buddylist']))
              echo '
              <option value="1"', !empty($context['receive_from']) && $context['receive_from'] == 1 ? ' selected="selected"' : '', '>', $txt['pm_receive_from_ignore'], '</option>
              <option value="2"', !empty($context['receive_from']) && $context['receive_from'] == 2 ? ' selected="selected"' : '', '>', $txt['pm_receive_from_buddies'], '</option>';

              echo '
              <option value="3"', !empty($context['receive_from']) && $context['receive_from'] > 2 ? ' selected="selected"' : '', '>', $txt['pm_receive_from_admins'], '</option>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label has-text-left">
      <label class="label" for="pm_email_notify">', $txt['email_notify'], '</label>
    </div>
    <div class="field-body">
      <div class="field">
        <div class="control">
          <div class="select">
            <select name="pm_email_notify" id="pm_email_notify">
              <option value="0"', empty($context['send_email']) ? ' selected="selected"' : '', '>', $txt['email_notify_never'], '</option>
              <option value="1"', !empty($context['send_email']) && ($context['send_email'] == 1 || (empty($modSettings['enable_buddylist']) && $context['send_email'] > 1)) ? ' selected="selected"' : '', '>', $txt['email_notify_always'], '</option>';

              if (!empty($modSettings['enable_buddylist']))
              echo '
              <option value="2"', !empty($context['send_email']) && $context['send_email'] > 1 ? ' selected="selected"' : '', '>', $txt['email_notify_buddies'], '</option>';

              echo '
            </select>
          </div>
        </div>
        <div class="control mt-3">
          <label class="checkbox" for="popup_messages">
          <input type="hidden" name="default_options[popup_messages]" value="0" />
          <input type="checkbox" name="default_options[popup_messages]" id="popup_messages" value="1"', !empty($context['member']['options']['popup_messages']) ? ' checked="checked"' : '', ' class="input_check" /> ', $txt['popup_messages'], '
          </label>
    </div>
      </div>
    </div>
  </div>

  <hr>

  <div class="field">
    <div class="control">
      <label class="checkbox" for="copy_to_outbox">
      <input type="hidden" name="default_options[copy_to_outbox]" value="0" />
      <input type="checkbox" name="default_options[copy_to_outbox]" id="copy_to_outbox" value="1"', !empty($context['member']['options']['copy_to_outbox']) ? ' checked="checked"' : '', ' class="input_check" /> ', $txt['copy_to_outbox'], '
      </label>
    </div>
  </div>

   <div class="field">
    <div class="control">
      <label class="checkbox" for="pm_remove_inbox_label">
        <input type="hidden" name="default_options[pm_remove_inbox_label]" value="0" />
        <input type="checkbox" name="default_options[pm_remove_inbox_label]" id="pm_remove_inbox_label" value="1"', !empty($context['member']['options']['pm_remove_inbox_label']) ? ' checked="checked"' : '', ' class="input_check" /> ', $txt['pm_remove_inbox_label'], '
        
      </label>
    </div>
  </div>
  ';
}

// Template for showing theme settings. Note: template_options() actually adds the theme specific options.
function template_profile_theme_settings()
{
  global $context, $settings, $options, $scripturl, $modSettings, $txt;

  echo '
  <div class="mt-3 mb-3">

    <div class="field">
      <div class="control">
        <input class="checkbox" type="hidden" name="default_options[show_board_desc]" value="0" />
        <label for="show_board_desc"><input type="checkbox" name="default_options[show_board_desc]" id="show_board_desc" value="1"', !empty($context['member']['options']['show_board_desc']) ? ' checked="checked"' : '', ' class="input_check" /> ', $txt['board_desc_inside'], '</label>
      </div>
    </div>
    
    <div class="field">
      <div class="control">
        <input type="hidden" name="default_options[show_children]" value="0" />
        <label class="checkbox" for="show_children"><input type="checkbox" name="default_options[show_children]" id="show_children" value="1"', !empty($context['member']['options']['show_children']) ? ' checked="checked"' : '', ' class="input_check" /> ', $txt['show_children'], '</label>
      </div>
    </div>
    
    <div class="field">
      <div class="control">
        <input type="hidden" name="default_options[use_sidebar_menu]" value="0" />
        <label class="checkbox" for="use_sidebar_menu"><input type="checkbox" name="default_options[use_sidebar_menu]" id="use_sidebar_menu" value="1"', !empty($context['member']['options']['use_sidebar_menu']) ? ' checked="checked"' : '', ' class="input_check" /> ', $txt['use_sidebar_menu'], '</label>
      </div>
    </div>
    
    <div class="field">
      <div class="control">
          <input type="hidden" name="default_options[show_no_avatars]" value="0" />
          <label class="checkbox" for="show_no_avatars"><input type="checkbox" name="default_options[show_no_avatars]" id="show_no_avatars" value="1"', !empty($context['member']['options']['show_no_avatars']) ? ' checked="checked"' : '', ' class="input_check" /> ', $txt['show_no_avatars'], '</label>
       </div>
    </div>
    
    <div class="field">
      <div class="control">
        <input type="hidden" name="default_options[show_no_signatures]" value="0" />
        <label class="checkbox" for="show_no_signatures"><input type="checkbox" name="default_options[show_no_signatures]" id="show_no_signatures" value="1"', !empty($context['member']['options']['show_no_signatures']) ? ' checked="checked"' : '', ' class="input_check" /> ', $txt['show_no_signatures'], '</label>
      </div>
    </div>
    ';

  if ($settings['allow_no_censored'])
    echo '
    <div class="field">
      <div class="control">
            <input type="hidden" name="default_options[show_no_censored]" value="0" />
            <label class="checkbox" for="show_no_censored"><input type="checkbox" name="default_options[show_no_censored]" id="show_no_censored" value="1"' . (!empty($context['member']['options']['show_no_censored']) ? ' checked="checked"' : '') . ' class="input_check" /> ' . $txt['show_no_censored'] . '</label>
       </div>
    </div>';

    echo '
    <div class="field">
      <div class="control">
          <input type="hidden" name="default_options[return_to_post]" value="0" />
          <label class="checkbox" for="return_to_post"><input type="checkbox" name="default_options[return_to_post]" id="return_to_post" value="1"', !empty($context['member']['options']['return_to_post']) ? ' checked="checked"' : '', ' class="input_check" /> ', $txt['return_to_post'], '</label>
       </div>
    </div>

     <div class="field">
      <div class="control">
          <input type="hidden" name="default_options[no_new_reply_warning]" value="0" />
          <label class="checkbox" for="no_new_reply_warning"><input type="checkbox" name="default_options[no_new_reply_warning]" id="no_new_reply_warning" value="1"', !empty($context['member']['options']['no_new_reply_warning']) ? ' checked="checked"' : '', ' class="input_check" /> ', $txt['no_new_reply_warning'], '</label>
        </div>
    </div>';

  if (!empty($modSettings['enable_buddylist']))
    echo '
    <div class="field">
      <div class="control">
          <input type="hidden" name="default_options[posts_apply_ignore_list]" value="0" />
          <label class="checkbox" for="posts_apply_ignore_list"><input type="checkbox" name="default_options[posts_apply_ignore_list]" id="posts_apply_ignore_list" value="1"', !empty($context['member']['options']['posts_apply_ignore_list']) ? ' checked="checked"' : '', ' class="input_check" /> ', $txt['posts_apply_ignore_list'], '</label>
        </div>
    </div>';

    echo '
    <div class="field">
      <div class="control">
          <input type="hidden" name="default_options[view_newest_first]" value="0" />
          <label class="checkbox" for="view_newest_first"><input type="checkbox" name="default_options[view_newest_first]" id="view_newest_first" value="1"', !empty($context['member']['options']['view_newest_first']) ? ' checked="checked"' : '', ' class="input_check" /> ', $txt['recent_posts_at_top'], '</label>
        </div>
    </div>';

  // Choose WYSIWYG settings?
  if (empty($modSettings['disable_wysiwyg']))
    echo '
     <div class="field">
      <div class="control">
          <input type="hidden" name="default_options[wysiwyg_default]" value="0" />
          <label class="checkbox" for="wysiwyg_default"><input type="checkbox" name="default_options[wysiwyg_default]" id="wysiwyg_default" value="1"', !empty($context['member']['options']['wysiwyg_default']) ? ' checked="checked"' : '', ' class="input_check" /> ', $txt['wysiwyg_default'], '</label>
        </div>
    </div>';

  if (empty($modSettings['disableCustomPerPage']))
  {
    echo '
    <div class="field is-horizontal">
      <div class="field-label has-text-left">
        <label class="label" for="topics_per_page">', $txt['topics_per_page'], '</label>
      </div>
      <div class="field-body">
        <div class="field">
          <div class="control">
            <div class="select">
              <select name="default_options[topics_per_page]" id="topics_per_page">
                <option value="0"', empty($context['member']['options']['topics_per_page']) ? ' selected="selected"' : '', '>', $txt['per_page_default'], ' (', $modSettings['defaultMaxTopics'], ')</option>
                <option value="5"', !empty($context['member']['options']['topics_per_page']) && $context['member']['options']['topics_per_page'] == 5 ? ' selected="selected"' : '', '>5</option>
                <option value="10"', !empty($context['member']['options']['topics_per_page']) && $context['member']['options']['topics_per_page'] == 10 ? ' selected="selected"' : '', '>10</option>
                <option value="25"', !empty($context['member']['options']['topics_per_page']) && $context['member']['options']['topics_per_page'] == 25 ? ' selected="selected"' : '', '>25</option>
                <option value="50"', !empty($context['member']['options']['topics_per_page']) && $context['member']['options']['topics_per_page'] == 50 ? ' selected="selected"' : '', '>50</option>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="field is-horizontal">
      <div class="field-label has-text-left">
        <label class="label" for="messages_per_page">', $txt['messages_per_page'], '</label>
      </div>
      <div class="field-body">
        <div class="field">
          <div class="control">
            <div class="select">
              <select name="default_options[messages_per_page]" id="messages_per_page">
                <option value="0"', empty($context['member']['options']['messages_per_page']) ? ' selected="selected"' : '', '>', $txt['per_page_default'], ' (', $modSettings['defaultMaxMessages'], ')</option>
                <option value="5"', !empty($context['member']['options']['messages_per_page']) && $context['member']['options']['messages_per_page'] == 5 ? ' selected="selected"' : '', '>5</option>
                <option value="10"', !empty($context['member']['options']['messages_per_page']) && $context['member']['options']['messages_per_page'] == 10 ? ' selected="selected"' : '', '>10</option>
                <option value="25"', !empty($context['member']['options']['messages_per_page']) && $context['member']['options']['messages_per_page'] == 25 ? ' selected="selected"' : '', '>25</option>
                <option value="50"', !empty($context['member']['options']['messages_per_page']) && $context['member']['options']['messages_per_page'] == 50 ? ' selected="selected"' : '', '>50</option>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>';
  }

  if (!empty($modSettings['cal_enabled']))
    echo '
    <div class="field is-horizontal">
      <div class="field-label has-text-left">
        <label class="label" for="calendar_start_day">', $txt['calendar_start_day'], ':</label>
      </div>
      <div class="field-body">
        <div class="field">
          <div class="control">
            <div class="select">
              <select name="default_options[calendar_start_day]" id="calendar_start_day">
                <option value="0"', empty($context['member']['options']['calendar_start_day']) ? ' selected="selected"' : '', '>', $txt['days'][0], '</option>
                <option value="1"', !empty($context['member']['options']['calendar_start_day']) && $context['member']['options']['calendar_start_day'] == 1 ? ' selected="selected"' : '', '>', $txt['days'][1], '</option>
                <option value="6"', !empty($context['member']['options']['calendar_start_day']) && $context['member']['options']['calendar_start_day'] == 6 ? ' selected="selected"' : '', '>', $txt['days'][6], '</option>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>';

  echo '
    <div class="field is-horizontal">
      <div class="field-label has-text-left">
        <label class="label" for="display_quick_reply">', $txt['display_quick_reply'], '</label>
      </div>
      <div class="field-body">
        <div class="field">
          <div class="control">
            <div class="select">
              <select name="default_options[display_quick_reply]" id="display_quick_reply">
                <option value="0"', empty($context['member']['options']['display_quick_reply']) ? ' selected="selected"' : '', '>', $txt['display_quick_reply1'], '</option>
                <option value="1"', !empty($context['member']['options']['display_quick_reply']) && $context['member']['options']['display_quick_reply'] == 1 ? ' selected="selected"' : '', '>', $txt['display_quick_reply2'], '</option>
                <option value="2"', !empty($context['member']['options']['display_quick_reply']) && $context['member']['options']['display_quick_reply'] == 2 ? ' selected="selected"' : '', '>', $txt['display_quick_reply3'], '</option>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="field is-horizontal">
      <div class="field-label has-text-left">
        <label class="label" for="display_quick_mod">', $txt['display_quick_mod'], '</label>
      </div>
      <div class="field-body">
        <div class="field">
          <div class="control">
            <div class="select">
              <select name="default_options[display_quick_mod]" id="display_quick_mod">
                <option value="0"', empty($context['member']['options']['display_quick_mod']) ? ' selected="selected"' : '', '>', $txt['display_quick_mod_none'], '</option>
                <option value="1"', !empty($context['member']['options']['display_quick_mod']) && $context['member']['options']['display_quick_mod'] == 1 ? ' selected="selected"' : '', '>', $txt['display_quick_mod_check'], '</option>
                <option value="2"', !empty($context['member']['options']['display_quick_mod']) && $context['member']['options']['display_quick_mod'] != 1 ? ' selected="selected"' : '', '>', $txt['display_quick_mod_image'], '</option>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>';
}

function template_notification()
{
  global $context, $settings, $options, $txt, $scripturl, $modSettings;

  // The main containing header.
  echo '
    <div class="cat_bar">
      <h3 class="title is-5 mb-4">', $txt['profile'], '</h3>
    </div>
 
    <p class="notification is-size-7">', $txt['notification_info'], '</p>

    <form action="', $scripturl, '?action=profile;area=notification;save" method="post" accept-charset="', $context['character_set'], '" id="notify_options" class="flow_hidden">';

      // Allow notification on announcements to be disabled?
      if (!empty($modSettings['allow_disableAnnounce']))
        echo '
        <div class="field">
          <label for="notify_announcements"><input type="checkbox" id="notify_announcements" name="notify_announcements"', !empty($context['member']['notify_announcements']) ? ' checked="checked"' : '', ' class="checkbox" />
            <input type="hidden" name="notify_announcements" value="0" class="checkbox"/>
               ', $txt['notify_important_email'], '
          </label>
        </div>';

      // More notification options.
      echo '
      <div class="field">
        <label for="auto_notify"><input type="checkbox" id="auto_notify" name="default_options[auto_notify]" value="1"', !empty($context['member']['options']['auto_notify']) ? ' checked="checked"' : '', ' class="checkbox" />
          <input type="hidden" name="default_options[auto_notify]" value="0" class="checkbox"/>
            ', $txt['auto_notify'], '
        </label>
      </div>';

      if (empty($modSettings['disallow_sendBody']))
        echo '
        <div class="field">
          <label for="notify_send_body">
            <input type="checkbox" id="notify_send_body" name="notify_send_body"', !empty($context['member']['notify_send_body']) ? ' checked="checked"' : '', ' class="checkbox" /> <input type="hidden" name="notify_send_body" value="0" class="checkbox"/>
               ', $txt['notify_send_body'], '
          </label>
        </div>';

      echo '
        <div class="field">
          <label for="notify_regularity">', $txt['notify_regularity'], ':</label>
          <div class="select">
            <select name="notify_regularity" id="notify_regularity">
              <option value="0"', $context['member']['notify_regularity'] == 0 ? ' selected="selected"' : '', '>', $txt['notify_regularity_instant'], '</option>
              <option value="1"', $context['member']['notify_regularity'] == 1 ? ' selected="selected"' : '', '>', $txt['notify_regularity_first_only'], '</option>
              <option value="2"', $context['member']['notify_regularity'] == 2 ? ' selected="selected"' : '', '>', $txt['notify_regularity_daily'], '</option>
              <option value="3"', $context['member']['notify_regularity'] == 3 ? ' selected="selected"' : '', '>', $txt['notify_regularity_weekly'], '</option>
            </select>
          </div>
        </div>

        <div class="field">
          <label for="notify_types">', $txt['notify_send_types'], ':</label>
          <div class="select">
            <select name="notify_types" id="notify_types">
              <option value="1"', $context['member']['notify_types'] == 1 ? ' selected="selected"' : '', '>', $txt['notify_send_type_everything'], '</option>
              <option value="2"', $context['member']['notify_types'] == 2 ? ' selected="selected"' : '', '>', $txt['notify_send_type_everything_own'], '</option>
              <option value="3"', $context['member']['notify_types'] == 3 ? ' selected="selected"' : '', '>', $txt['notify_send_type_only_replies'], '</option>
              <option value="4"', $context['member']['notify_types'] == 4 ? ' selected="selected"' : '', '>', $txt['notify_send_type_nothing'], '</option>
            </select>
          </div>
        </div>

        <div>
          <input id="notify_submit" type="submit" value="', $txt['notify_save'], '" class="button is-primary" />
          <input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
          <input type="hidden" name="u" value="', $context['id_member'], '" />
          <input type="hidden" name="sa" value="', $context['menu_item_selected'], '" />
        </div><br class="clear" />
    </form>
  ';

  template_show_list('topic_notification_list');

  template_show_list('board_notification_list');
}

// Template for choosing group membership.
function template_groupMembership()
{
  global $context, $settings, $options, $scripturl, $modSettings, $txt;

  // The main containing header.
  echo '
    <form action="', $scripturl, '?action=profile;area=groupmembership;save" method="post" accept-charset="', $context['character_set'], '" name="creator" id="creator">
      <div class="cat_bar">
        <h3 class="catbg">
          <span class="ie6_header floatleft"><img src="', $settings['images_url'], '/icons/profile_sm.gif" alt="" class="icon" />', $txt['profile'], '</span>
        </h3>
      </div>
      <p class="description">', $txt['groupMembership_info'], '</p>';

  // Do we have an update message?
  if (!empty($context['update_message']))
    echo '
      <div id="profile_success">
        ', $context['update_message'], '.
      </div>';

  // Requesting membership to a group?
  if (!empty($context['group_request']))
  {
    echo '
      <div class="groupmembership">
        <div class="cat_bar">
          <h3 class="catbg">', $txt['request_group_membership'], '</h3>
        </div>
        <span class="upperframe"><span></span></span>
        <div class="roundframe"><div class="innerframe">
          ', $txt['request_group_membership_desc'], ':
          <textarea name="reason" rows="4" style="' . ($context['browser']['is_ie8'] ? 'width: 635px; max-width: 99%; min-width: 99%' : 'width: 99%') . ';"></textarea>
          <div class="righttext" style="margin: 0.5em 0.5% 0 0.5%;">
            <input type="hidden" name="gid" value="', $context['group_request']['id'], '" />
            <input type="submit" name="req" value="', $txt['submit_request'], '" class="button is-primary" />
          </div>
        </div></div>
        <span class="lowerframe"><span></span></span>
      </div>';
  }
  else
  {
    echo '
      <table border="0" width="100%" cellspacing="0" cellpadding="4" class="table_grid">
        <thead>
          <tr class="catbg">
            <th class="first_th" scope="col" ', $context['can_edit_primary'] ? ' colspan="2"' : '', '>', $txt['current_membergroups'], '</th>
            <th class="last_th" scope="col"></th>
          </tr>
        </thead>
        <tbody>';

    $alternate = true;
    foreach ($context['groups']['member'] as $group)
    {
      echo '
          <tr class="', $alternate ? 'windowbg' : 'windowbg2', '" id="primdiv_', $group['id'], '">';

        if ($context['can_edit_primary'])
          echo '
            <td width="4%">
              <input type="radio" name="primary" id="primary_', $group['id'], '" value="', $group['id'], '" ', $group['is_primary'] ? 'checked="checked"' : '', ' onclick="highlightSelected(\'primdiv_' . $group['id'] . '\');" ', $group['can_be_primary'] ? '' : 'disabled="disabled"', ' class="input_radio" />
            </td>';

        echo '
            <td>
              <label for="primary_', $group['id'], '"><strong>', (empty($group['color']) ? $group['name'] : '<span style="color: ' . $group['color'] . '">' . $group['name'] . '</span>'), '</strong>', (!empty($group['desc']) ? '<br /><span class="smalltext">' . $group['desc'] . '</span>' : ''), '</label>
            </td>
            <td width="15%" class="righttext">';

        // Can they leave their group?
        if ($group['can_leave'])
          echo '
              <a href="' . $scripturl . '?action=profile;save;u=' . $context['id_member'] . ';area=groupmembership;' . $context['session_var'] . '=' . $context['session_id'] . ';gid=' . $group['id'] . '">' . $txt['leave_group'] . '</a>';
        echo '
            </td>
          </tr>';
      $alternate = !$alternate;
    }

    echo '
        </tbody>
      </table>';

    if ($context['can_edit_primary'])
      echo '
      <div class="padding righttext">
        <input type="submit" value="', $txt['make_primary'], '" class="button is-primary" />
      </div>';

    // Any groups they can join?
    if (!empty($context['groups']['available']))
    {
      echo '
      <br />
      <table border="0" width="100%" cellspacing="0" cellpadding="4" class="table_grid">
        <thead>
          <tr class="catbg">
            <th class="first_th" scope="col">
              ', $txt['available_groups'], '
            </th>
            <th class="last_th" scope="col"></th>
          </tr>
        </thead>
        <tbody>';

      $alternate = true;
      foreach ($context['groups']['available'] as $group)
      {
        echo '
          <tr class="', $alternate ? 'windowbg' : 'windowbg2', '">
            <td>
              <strong>', (empty($group['color']) ? $group['name'] : '<span style="color: ' . $group['color'] . '">' . $group['name'] . '</span>'), '</strong>', (!empty($group['desc']) ? '<br /><span class="smalltext">' . $group['desc'] . '</span>' : ''), '
            </td>
            <td width="15%" class="lefttext">';

        if ($group['type'] == 3)
          echo '
              <a href="', $scripturl, '?action=profile;save;u=', $context['id_member'], ';area=groupmembership;', $context['session_var'], '=', $context['session_id'], ';gid=', $group['id'], '">', $txt['join_group'], '</a>';
        elseif ($group['type'] == 2 && $group['pending'])
          echo '
              ', $txt['approval_pending'];
        elseif ($group['type'] == 2)
          echo '
              <a href="', $scripturl, '?action=profile;u=', $context['id_member'], ';area=groupmembership;request=', $group['id'], '">', $txt['request_group'], '</a>';

        echo '
            </td>
          </tr>';
        $alternate = !$alternate;
      }
      echo '
        </tbody>
      </table>';
    }

    // Javascript for the selector stuff.
    echo '
    <script type="text/javascript"><!-- // --><![CDATA[
    var prevClass = "";
    var prevDiv = "";
    function highlightSelected(box)
    {
      if (prevClass != "")
      {
        prevDiv.className = prevClass;
      }
      prevDiv = document.getElementById(box);
      prevClass = prevDiv.className;

      prevDiv.className = "highlight2";
    }';
    if (isset($context['groups']['member'][$context['primary_group']]))
      echo '
    highlightSelected("primdiv_' . $context['primary_group'] . '");';
    echo '
  // ]]></script>';
  }

  echo '
        <input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
        <input type="hidden" name="u" value="', $context['id_member'], '" />
      </form>';
}

function template_ignoreboards()
{
  global $context, $txt, $settings, $scripturl;
  // The main containing header.
  echo '
  <script type="text/javascript"><!-- // --><![CDATA[
    function selectBoards(ids)
    {
      var toggle = true;

      for (i = 0; i < ids.length; i++)
        toggle = toggle & document.forms.creator["ignore_brd" + ids[i]].checked;

      for (i = 0; i < ids.length; i++)
        document.forms.creator["ignore_brd" + ids[i]].checked = !toggle;
    }
  // ]]></script>

  <form action="', $scripturl, '?action=profile;area=ignoreboards;save" method="post" accept-charset="', $context['character_set'], '" name="creator" id="creator">
    <div class="cat_bar">
      <h3 class="catbg">
        <span class="ie6_header floatleft"><img src="', $settings['images_url'], '/icons/profile_sm.gif" alt="" class="icon" />', $txt['profile'], '</span>
      </h3>
    </div>
    <p class="description">', $txt['ignoreboards_info'], '</p>
    <div class="windowbg2">
      <span class="topslice"><span></span></span>
      <div class="content flow_hidden">
        <ul class="ignoreboards floatleft">';

  $i = 0;
  $limit = ceil($context['num_boards'] / 2);
  foreach ($context['categories'] as $category)
  {
    if ($i == $limit)
    {
      echo '
        </ul>
        <ul class="ignoreboards floatright">';

      $i++;
    }

    echo '
          <li class="category">
            <a href="javascript:void(0);" onclick="selectBoards([', implode(', ', $category['child_ids']), ']); return false;">', $category['name'], '</a>
            <ul>';

    foreach ($category['boards'] as $board)
    {
      if ($i == $limit)
        echo '
            </ul>
          </li>
        </ul>
        <ul class="ignoreboards floatright">
          <li class="category">
            <ul>';

      echo '
              <li class="board" style="margin-', $context['right_to_left'] ? 'right' : 'left', ': ', $board['child_level'], 'em;">
                <label for="ignore_brd', $board['id'], '"><input type="checkbox" id="ignore_brd', $board['id'], '" name="ignore_brd[', $board['id'], ']" value="', $board['id'], '"', $board['selected'] ? ' checked="checked"' : '', ' class="input_check" /> ', $board['name'], '</label>
              </li>';

      $i++;
    }

    echo '
            </ul>
          </li>';
  }

  echo '
        </ul>
        <br class="clear" />';

  // Show the standard "Save Settings" profile button.
  template_profile_save();

  echo '
      </div>
      <span class="botslice"><span></span></span>
    </div>
  </form>
  <br />';
}

// Simple load some theme variables common to several warning templates.
function template_load_warning_variables()
{
  global $modSettings, $context;

  $context['warningBarWidth'] = 200;
  // Setup the colors - this is a little messy for theming.
  $context['colors'] = array(
    0 => 'green',
    $modSettings['warning_watch'] => 'darkgreen',
    $modSettings['warning_moderate'] => 'orange',
    $modSettings['warning_mute'] => 'red',
  );

  // Work out the starting color.
  $context['current_color'] = $context['colors'][0];
  foreach ($context['colors'] as $limit => $color)
    if ($context['member']['warning'] >= $limit)
      $context['current_color'] = $color;
}

// Show all warnings of a user?
function template_viewWarning()
{
  global $context, $txt, $scripturl, $settings;

  template_load_warning_variables();

  echo '
    <div class="title_bar">
      <h3 class="titlebg">
        <span class="ie6_header floatleft"><img src="', $settings['images_url'], '/icons/profile_sm.gif" alt="" class="icon" />
        ', sprintf($txt['profile_viewwarning_for_user'], $context['member']['name']), '
        </span>
      </h3>
    </div>
    <div class="windowbg">
      <span class="topslice"><span></span></span>
      <div class="content">
        <dl class="settings">
          <dt>
            <strong>', $txt['profile_warning_name'], ':</strong>
          </dt>
          <dd>
            ', $context['member']['name'], '
          </dd>
          <dt>
            <strong>', $txt['profile_warning_level'], ':</strong>
          </dt>
          <dd>
            <div>
              <div>
                <div style="font-size: 8pt; height: 12pt; width: ', $context['warningBarWidth'], 'px; border: 1px solid black; background-color: white; padding: 1px; position: relative;">
                  <div id="warning_text" style="padding-top: 1pt; width: 100%; z-index: 2; color: black; position: absolute; text-align: center; font-weight: bold;">', $context['member']['warning'], '%</div>
                  <div id="warning_progress" style="width: ', $context['member']['warning'], '%; height: 12pt; z-index: 1; background-color: ', $context['current_color'], ';">&nbsp;</div>
                </div>
              </div>
            </div>
          </dd>';

    // There's some impact of this?
    if (!empty($context['level_effects'][$context['current_level']]))
      echo '
          <dt>
            <strong>', $txt['profile_viewwarning_impact'], ':</strong>
          </dt>
          <dd>
            ', $context['level_effects'][$context['current_level']], '
          </dd>';

    echo '
        </dl>
      </div>
      <span class="botslice"><span></span></span>
    </div>';

  template_show_list('view_warnings');
}

// Show a lovely interface for issuing warnings.
function template_issueWarning()
{
  global $context, $settings, $options, $scripturl, $modSettings, $txt;

  template_load_warning_variables();

  echo '
  <script type="text/javascript"><!-- // --><![CDATA[
    function setWarningBarPos(curEvent, isMove, changeAmount)
    {
      barWidth = ', $context['warningBarWidth'], ';

      // Are we passing the amount to change it by?
      if (changeAmount)
      {
        if (document.getElementById(\'warning_level\').value == \'SAME\')
          percent = ', $context['member']['warning'], ' + changeAmount;
        else
          percent = parseInt(document.getElementById(\'warning_level\').value) + changeAmount;
      }
      // If not then it\'s a mouse thing.
      else
      {
        if (!curEvent)
          var curEvent = window.event;

        // If it\'s a movement check the button state first!
        if (isMove)
        {
          if (!curEvent.button || curEvent.button != 1)
            return false
        }

        // Get the position of the container.
        contain = document.getElementById(\'warning_contain\');
        position = 0;
        while (contain != null)
        {
          position += contain.offsetLeft;
          contain = contain.offsetParent;
        }

        // Where is the mouse?
        if (curEvent.pageX)
        {
          mouse = curEvent.pageX;
        }
        else
        {
          mouse = curEvent.clientX;
          mouse += document.documentElement.scrollLeft != "undefined" ? document.documentElement.scrollLeft : document.body.scrollLeft;
        }

        // Is this within bounds?
        if (mouse < position || mouse > position + barWidth)
          return;

        percent = Math.round(((mouse - position) / barWidth) * 100);

        // Round percent to the nearest 5 - by kinda cheating!
        percent = Math.round(percent / 5) * 5;
      }

      // What are the limits?
      minLimit = ', $context['min_allowed'], ';
      maxLimit = ', $context['max_allowed'], ';

      percent = Math.max(percent, minLimit);
      percent = Math.min(percent, maxLimit);

      size = barWidth * (percent/100);

      setInnerHTML(document.getElementById(\'warning_text\'), percent + "%");
      document.getElementById(\'warning_level\').value = percent;
      document.getElementById(\'warning_progress\').style.width = size + "px";

      // Get the right color.
      color = "black"';

  foreach ($context['colors'] as $limit => $color)
    echo '
      if (percent >= ', $limit, ')
        color = "', $color, '";';

  echo '
      document.getElementById(\'warning_progress\').style.backgroundColor = color;

      // Also set the right effect.
      effectText = "";';

  foreach ($context['level_effects'] as $limit => $text)
    echo '
      if (percent >= ', $limit, ')
        effectText = "', $text, '";';

  echo '
      setInnerHTML(document.getElementById(\'cur_level_div\'), effectText);
    }

    // Disable notification boxes as required.
    function modifyWarnNotify()
    {
      disable = !document.getElementById(\'warn_notify\').checked;
      document.getElementById(\'warn_sub\').disabled = disable;
      document.getElementById(\'warn_body\').disabled = disable;
      document.getElementById(\'warn_temp\').disabled = disable;
      document.getElementById(\'new_template_link\').style.display = disable ? \'none\' : \'\';
    }

    function changeWarnLevel(amount)
    {
      setWarningBarPos(false, false, amount);
    }

    // Warn template.
    function populateNotifyTemplate()
    {
      index = document.getElementById(\'warn_temp\').value;
      if (index == -1)
        return false;

      // Otherwise see what we can do...';

  foreach ($context['notification_templates'] as $k => $type)
    echo '
      if (index == ', $k, ')
        document.getElementById(\'warn_body\').value = "', strtr($type['body'], array('"' => "'", "\n" => '\\n', "\r" => '')), '";';

  echo '
    }

  // ]]></script>';

  echo '
  <form action="', $scripturl, '?action=profile;u=', $context['id_member'], ';area=issuewarning" method="post" class="flow_hidden" accept-charset="', $context['character_set'], '">
    <div class="cat_bar">
      <h3 class="catbg">
        <span class="ie6_header floatleft"><img src="', $settings['images_url'], '/icons/profile_sm.gif" alt="" class="icon" />
        ', $context['user']['is_owner'] ? $txt['profile_warning_level'] : $txt['profile_issue_warning'], '
        </span>
      </h3>
    </div>';

  if (!$context['user']['is_owner'])
    echo '
    <p class="description">', $txt['profile_warning_desc'], '</p>';

  echo '
    <div class="windowbg">
      <span class="topslice"><span></span></span>
      <div class="content">
        <dl class="settings">';

  if (!$context['user']['is_owner'])
    echo '
          <dt>
            <strong>', $txt['profile_warning_name'], ':</strong>
          </dt>
          <dd>
            <strong>', $context['member']['name'], '</strong>
          </dd>';

  echo '
          <dt>
            <strong>', $txt['profile_warning_level'], ':</strong>';

  // Is there only so much they can apply?
  if ($context['warning_limit'])
    echo '
            <br /><span class="smalltext">', sprintf($txt['profile_warning_limit_attribute'], $context['warning_limit']), '</span>';

  echo '
          </dt>
          <dd>
            <div id="warndiv1" style="display: none;">
              <div>
                <span class="floatleft" style="padding: 0 0.5em"><a href="#" onclick="changeWarnLevel(-5); return false;">[-]</a></span>
                <div class="floatleft" id="warning_contain" style="font-size: 8pt; height: 12pt; width: ', $context['warningBarWidth'], 'px; border: 1px solid black; background-color: white; padding: 1px; position: relative;" onmousedown="setWarningBarPos(event, true);" onmousemove="setWarningBarPos(event, true);" onclick="setWarningBarPos(event);">
                  <div id="warning_text" style="padding-top: 1pt; width: 100%; z-index: 2; color: black; position: absolute; text-align: center; font-weight: bold;">', $context['member']['warning'], '%</div>
                  <div id="warning_progress" style="width: ', $context['member']['warning'], '%; height: 12pt; z-index: 1; background-color: ', $context['current_color'], ';">&nbsp;</div>
                </div>
                <span class="floatleft" style="padding: 0 0.5em"><a href="#" onclick="changeWarnLevel(5); return false;">[+]</a></span>
                <div class="clear_left smalltext">', $txt['profile_warning_impact'], ': <span id="cur_level_div">', $context['level_effects'][$context['current_level']], '</span></div>
              </div>
              <input type="hidden" name="warning_level" id="warning_level" value="SAME" />
            </div>
            <div id="warndiv2">
              <input type="text" name="warning_level_nojs" size="6" maxlength="4" value="', $context['member']['warning'], '" class="input" />&nbsp;', $txt['profile_warning_max'], '
              <div class="smalltext">', $txt['profile_warning_impact'], ':<br />';
  // For non-javascript give a better list.
  foreach ($context['level_effects'] as $limit => $effect)
    echo '
              ', sprintf($txt['profile_warning_effect_text'], $limit, $effect), '<br />';

  echo '
              </div>
            </div>
          </dd>';

  if (!$context['user']['is_owner'])
  {
    echo '
          <dt>
            <strong>', $txt['profile_warning_reason'], ':</strong><br />
            <span class="smalltext">', $txt['profile_warning_reason_desc'], '</span>
          </dt>
          <dd>
            <input type="text" name="warn_reason" id="warn_reason" value="', $context['warning_data']['reason'], '" size="50" style="width: 80%;" class="input" />
          </dd>
        </dl>
        <hr />
        <dl class="settings">
          <dt>
            <strong>', $txt['profile_warning_notify'], ':</strong>
          </dt>
          <dd>
            <input type="checkbox" name="warn_notify" id="warn_notify" onclick="modifyWarnNotify();" ', $context['warning_data']['notify'] ? 'checked="checked"' : '', ' class="input_check" />
          </dd>
          <dt>
            <strong>', $txt['profile_warning_notify_subject'], ':</strong>
          </dt>
          <dd>
            <input type="text" name="warn_sub" id="warn_sub" value="', empty($context['warning_data']['notify_subject']) ? $txt['profile_warning_notify_template_subject'] : $context['warning_data']['notify_subject'], '" size="50" style="width: 80%;" class="input" />
          </dd>
          <dt>
            <strong>', $txt['profile_warning_notify_body'], ':</strong>
          </dt>
          <dd>
            <select name="warn_temp" id="warn_temp" disabled="disabled" onchange="populateNotifyTemplate();" style="font-size: x-small;">
              <option value="-1">', $txt['profile_warning_notify_template'], '</option>
              <option value="-1">------------------------------</option>';

    foreach ($context['notification_templates'] as $id_template => $template)
      echo '
              <option value="', $id_template, '">', $template['title'], '</option>';

    echo '
            </select>
            <span class="smalltext" id="new_template_link" style="display: none;">[<a href="', $scripturl, '?action=moderate;area=warnings;sa=templateedit;tid=0" target="_blank" class="new_win">', $txt['profile_warning_new_template'], '</a>]</span><br />
            <textarea name="warn_body" id="warn_body" cols="40" rows="8">', $context['warning_data']['notify_body'], '</textarea>
          </dd>';
  }
  echo '
        </dl>
        <div class="righttext">
          <input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
          <input type="submit" name="save" value="', $context['user']['is_owner'] ? $txt['change_profile'] : $txt['profile_warning_issue'], '" class="button is-primary" />
        </div>
      </div>
      <span class="botslice"><span></span></span>
    </div>
  </form>';

  // Previous warnings?
  echo '<br />
    <div class="cat_bar">
      <h3 class="catbg">
        ', $txt['profile_warning_previous'], '
      </h3>
    </div>
    <table border="0" width="100%" cellspacing="0" cellpadding="5" class="table_grid">
      <thead>
        <tr class="titlebg lefttext">
          <th class="first_th" scope="col" width="20%">', $txt['profile_warning_previous_issued'], '</th>
          <th scope="col" width="30%">', $txt['profile_warning_previous_time'], '</th>
          <th scope="col">', $txt['profile_warning_previous_reason'], '</th>
          <th class="last_th" scope="col" width="6%">', $txt['profile_warning_previous_level'], '</th>
        </tr>
      </thead>
      <tbody>';

  // Print the warnings.
  $alternate = 0;
  foreach ($context['previous_warnings'] as $warning)
  {
    $alternate = !$alternate;
    echo '
        <tr class="', $alternate ? 'windowbg' : 'windowbg2', '">
          <td class="smalltext">', $warning['issuer']['link'], '</td>
          <td class="smalltext">', $warning['time'], '</td>
          <td class="smalltext">
            <div class="floatleft">
              ', $warning['reason'], '
            </div>';

    if (!empty($warning['id_notice']))
      echo '
            <div class="floatright">
              <a href="', $scripturl, '?action=moderate;area=notice;nid=', $warning['id_notice'], '" onclick="window.open(this.href, \'\', \'scrollbars=yes,resizable=yes,width=400,height=250\');return false;" target="_blank" class="new_win" title="', $txt['profile_warning_previous_notice'], '"><img src="', $settings['images_url'], '/filter.gif" alt="" /></a>
            </div>';
    echo '
          </td>
          <td class="smalltext">', $warning['counter'], '</td>
        </tr>';
  }

  if (empty($context['previous_warnings']))
    echo '
        <tr class="windowbg2">
          <td align="center" colspan="4">
            ', $txt['profile_warning_previous_none'], '
          </td>
        </tr>';

  echo '
      </tbody>
    </table>
    <div class="pagesection">', $txt['pages'], ': ', $context['page_index'], '</div>';

  // Do our best to get pretty javascript enabled.
  echo '
  <script type="text/javascript"><!-- // --><![CDATA[
    document.getElementById(\'warndiv1\').style.display = "";
    document.getElementById(\'warndiv2\').style.display = "none";';

  if (!$context['user']['is_owner'])
    echo '
    modifyWarnNotify();';

  echo '
  // ]]></script>';
}

// Template to show for deleting a users account - now with added delete post capability!
function template_deleteAccount()
{
  global $context, $settings, $options, $scripturl, $txt, $scripturl;

  // The main containing header.
  echo '
    <form action="', $scripturl, '?action=profile;area=deleteaccount;save" method="post" accept-charset="', $context['character_set'], '" name="creator" id="creator">
      <div class="title_bar">
        <h3 class="titlebg">
          <span class="ie6_header floatleft"><img src="', $settings['images_url'], '/icons/profile_sm.gif" alt="" class="icon" />', $txt['deleteAccount'], '</span>
        </h3>
      </div>';
  // If deleting another account give them a lovely info box.
  if (!$context['user']['is_owner'])
    echo '
      <p class="windowbg2 description">', $txt['deleteAccount_desc'], '</p>';
  echo '
      <div class="windowbg2">
        <span class="topslice"><span></span></span>
        <div class="content">';

  // If they are deleting their account AND the admin needs to approve it - give them another piece of info ;)
  if ($context['needs_approval'])
    echo '
          <div id ="profile_error" class="alert">', $txt['deleteAccount_approval'], '</div>';

  // If the user is deleting their own account warn them first - and require a password!
  if ($context['user']['is_owner'])
  {
    echo '
          <div class="alert">', $txt['own_profile_confirm'], '</div>
          <div>
            <strong', (isset($context['modify_error']['bad_password']) || isset($context['modify_error']['no_password']) ? ' class="error"' : ''), '>', $txt['current_password'], ': </strong>
            <input type="password" name="oldpasswrd" size="20" class="input_password" />&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="submit" value="', $txt['yes'], '" class="button is-primary" />
            <input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
            <input type="hidden" name="u" value="', $context['id_member'], '" />
            <input type="hidden" name="sa" value="', $context['menu_item_selected'], '" />
          </div>';
  }
  // Otherwise an admin doesn't need to enter a password - but they still get a warning - plus the option to delete lovely posts!
  else
  {
    echo '
          <div class="alert">', $txt['deleteAccount_warning'], '</div>';

    // Only actually give these options if they are kind of important.
    if ($context['can_delete_posts'])
      echo '
          <div>
            ', $txt['deleteAccount_posts'], ':
            <select name="remove_type">
              <option value="none">', $txt['deleteAccount_none'], '</option>
              <option value="posts">', $txt['deleteAccount_all_posts'], '</option>
              <option value="topics">', $txt['deleteAccount_topics'], '</option>
            </select>
          </div>';

    echo '
          <div>
            <label for="deleteAccount"><input type="checkbox" name="deleteAccount" id="deleteAccount" value="1" class="input_check" onclick="if (this.checked) return confirm(\'', $txt['deleteAccount_confirm'], '\');" /> ', $txt['deleteAccount_member'], '.</label>
          </div>
          <div>
            <input type="submit" value="', $txt['delete'], '" class="button is-primary" />
            <input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
            <input type="hidden" name="u" value="', $context['id_member'], '" />
            <input type="hidden" name="sa" value="', $context['menu_item_selected'], '" />
          </div>';
  }
  echo '
        </div>
        <span class="botslice"><span></span></span>
      </div>
      <br />
    </form>';
}

// Template for the password box/save button stuck at the bottom of every profile page.
function template_profile_save()
{
  global $context, $settings, $options, $txt;

  echo '

          <hr width="100%" size="1" class="hrcolor clear" />';

  // Only show the password box if it's actually needed.
  if ($context['require_password'])
    echo '
          <dl>
            <dt>
              <strong', isset($context['modify_error']['bad_password']) || isset($context['modify_error']['no_password']) ? ' class="error"' : '', '>', $txt['current_password'], ': </strong><br />
              <span class="smalltext">', $txt['required_security_reasons'], '</span>
            </dt>
            <dd>
              <input type="password" name="oldpasswrd" size="20" style="margin-right: 4ex;" class="input_password" />
            </dd>
          </dl>';

  echo '
          <div class="righttext">
            <input type="submit" value="', $txt['change_profile'], '" class="button is-primary" />
            <input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
            <input type="hidden" name="u" value="', $context['id_member'], '" />
            <input type="hidden" name="sa" value="', $context['menu_item_selected'], '" />
          </div>';
}

// Small template for showing an error message upon a save problem in the profile.
function template_error_message()
{
  global $context, $txt;

  echo '
    <div class="windowbg" id="profile_error">
      <span>', !empty($context['custom_error_title']) ? $context['custom_error_title'] : $txt['profile_errors_occurred'], ':</span>
      <ul class="reset">';

    // Cycle through each error and display an error message.
    foreach ($context['post_errors'] as $error)
      echo '
        <li>', isset($txt['profile_error_' . $error]) ? $txt['profile_error_' . $error] : $error, '.</li>';

    echo '
      </ul>
    </div>';
}

// Display a load of drop down selectors for allowing the user to change group.
function template_profile_group_manage()
{
  global $context, $txt, $scripturl;

  echo '
    <div class="field is-horizontal">
      <div class="field-label has-text-left">
        <label class="label">', $txt['primary_membergroup'], '</label>
        <p class="help">(<a href="', $scripturl, '?action=helpadmin;help=moderator_why_missing" onclick="return reqWin(this.href);">', $txt['moderator_why_missing'], '</a>)</p>
      </div>
      <div class="field-body">
        <div class="field">
          <div class="control">
            <div class="select">
            <select name="id_group" ', ($context['user']['is_owner'] && $context['member']['group_id'] == 1 ? 'onchange="if (this.value != 1 &amp;&amp; !confirm(\'' . $txt['deadmin_confirm'] . '\')) this.value = 1;"' : ''), '>';
                  // Fill the select box with all primary member groups that can be assigned to a member.
                  foreach ($context['member_groups'] as $member_group)
                    if (!empty($member_group['can_be_primary']))
                      echo '
                  <option value="', $member_group['id'], '"', $member_group['is_primary'] ? ' selected="selected"' : '', '>
                    ', $member_group['name'], '
                  </option>';
                echo '
                </select>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="field is-horizontal">
      <div class="field-label has-text-left">
        <label class="label">', $txt['additional_membergroups'], '</label>
      </div>
      <div class="field-body">
        <div class="field">
          <div class="control">
            <span id="additional_groupsList">
              <input type="hidden" name="additional_groups[]" value="0" />';
              // For each membergroup show a checkbox so members can be assigned to more than one group.
              foreach ($context['member_groups'] as $member_group)
                if ($member_group['can_be_additional'])
                  echo '
                  <label for="additional_groups-', $member_group['id'], '"><input type="checkbox" name="additional_groups[]" value="', $member_group['id'], '" id="additional_groups-', $member_group['id'], '"', $member_group['is_additional'] ? ' checked="checked"' : '', ' class="input_check" /> ', $member_group['name'], '</label><br />';
                echo '
                </span>
                <a href="javascript:void(0);" onclick="document.getElementById(\'additional_groupsList\').style.display = \'block\'; document.getElementById(\'additional_groupsLink\').style.display = \'none\'; return false;" id="additional_groupsLink" style="display: none;">', $txt['additional_membergroups_show'], '</a>
                <script type="text/javascript"><!-- // --><![CDATA[
                  document.getElementById("additional_groupsList").style.display = "none";
                  document.getElementById("additional_groupsLink").style.display = "";
                // ]]></script>
          </div>
        </div>
      </div>
    </div>';
}

// Callback function for entering a birthdate!
function template_profile_birthdate()
{
  global $txt, $context;

  // Just show the pretty box!
  echo '
  <div class="field is-horizontal">
    <div class="field-label has-text-left">
      <label class="label">', $txt['dob'], '</label>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="help mb-1">', $txt['dob_year'], ' - ', $txt['dob_month'], ' - ', $txt['dob_day'], '</p>
        <div class="control">
          <input type="text" name="bday3" size="4" maxlength="4" value="', $context['member']['birth_date']['year'], '" class="input is-auto" /> -
          <input type="text" name="bday1" size="2" maxlength="2" value="', $context['member']['birth_date']['month'], '" class="input is-auto" /> -
          <input type="text" name="bday2" size="2" maxlength="2" value="', $context['member']['birth_date']['day'], '" class="input is-auto" />
        </div>
      </div>
    </div>
  </div>';
}

// Show the signature editing box?
function template_profile_signature_modify()
{
  /* INCLUDES ADVANCED SIGNAUTRES */
  global $txt, $context, $settings, $modSettings;

  echo '

  <input type="hidden" name="signature" value="1">';

  for ($i = 0; $i < $modSettings['max_numberofSignatures']; $i++) {
    echo'
      <div class="field is-horizontal">
        <div class="field-label has-text-left">
          <label class="label">', sprintf($txt['signature_numb'], $i + 1), '</label>';
          // Spell check button
          if ($context['show_spellchecking'])
          echo '
            <input type="button" value="', $txt['spell_check'], '" onclick="spellCheck(\'creator\', \'signature\');" class="button" />';
        echo'
        </div>

        <div class="field-body">
          <div class="field">
            <p class="help mb-2">', $txt['sig_info'], '</p>
            <div class="control">';
            /* <textarea class="textarea" onkeyup="calcCharLeft();" name="signature" rows="5" cols="50">', $context['member']['signature'], '</textarea> */
              echo'
              <textarea class="textarea" onkeyup="calcCharLeft_', $i , '();" name="signature[', $i , ']" rows="5" cols="50">', isset($context['member']['signature'][$i]) ? $context['member']['signature'][$i] : '' , '</textarea>
            </div>';

            // If there is a limit at all!
            if (!empty($context['signature_limits']['max_length']))
            echo '
            <p class="help">', sprintf($txt['max_sig_characters'], $context['signature_limits']['max_length']), ' <span id="signatureLeft">', $context['signature_limits']['max_length'], '</span></p>';

            // Signature warning
            if ($context['signature_warning'])
            echo '
             <p class="help">', $context['signature_warning'], '</p>';

            echo'
          </div>
        </div>
      </div>';
      }

  // Load the spell checker?
  if ($context['show_spellchecking'])
    echo '<script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/spellcheck.js"></script>';

  // Some javascript used to count how many characters have been used so far in the signature.
  echo '
    <script type="text/javascript"><!-- // --><![CDATA[
      function tick', $i , '()
      {
        if (typeof(document.forms.creator) != "undefined")
        {
          calcCharLeft();
          setTimeout("tick()", 1000);
        }
        else
          setTimeout("tick()", 800);
      }

      function calcCharLeft_', $i , '()
      {
        var maxLength = ', $context['signature_limits']['max_length'], ';
        var oldSignature = "", currentSignature = document.forms.creator.signature.value;

        if (!document.getElementById("signatureLeft"))
          return;

        if (oldSignature != currentSignature)
        {
          oldSignature = currentSignature;

          if (currentSignature.replace(/\r/, "").length > maxLength)
            document.forms.creator.signature.value = currentSignature.replace(/\r/, "").substring(0, maxLength);
          currentSignature = document.forms.creator.signature.value.replace(/\r/, "");
        }

        setInnerHTML(document.getElementById("signatureLeft"), maxLength - currentSignature.length);
      }

      addLoadEvent(tick', $i , ');
    // ]]></script>';
}

function template_profile_avatar_select()
{
  global $context, $txt, $modSettings;

  // Start with the upper menu
  echo '
    <fieldset class="p-4">
      <legend class="is-uppercase is-muted is-size-6-5"><span id="personal_picture">', $txt['personal_picture'], '</span></legend>
      
      <div class="field is-horizontal">
        <div class="field-label has-text-left">
          <div class="control">

            <label for="avatar_choice_none"' . (isset($context['modify_error']['bad_avatar']) ? ' class="error is-block"' : 'class="is-block"') . '>
              <input type="radio" onclick="swap_avatar(this); return true;" name="avatar_choice" id="avatar_choice_none" value="none"' . ($context['member']['avatar']['choice'] == 'none' ? ' checked="checked"' : '') . ' class="input_radio" /> ' . $txt['no_avatar'] . '
            </label>',

            !empty($context['member']['avatar']['allow_server_stored']) ? '<label for="avatar_choice_server_stored"' . (isset($context['modify_error']['bad_avatar']) ? ' class="error is-block"' : 'class="is-block"') . '><input type="radio" onclick="swap_avatar(this); return true;" name="avatar_choice" id="avatar_choice_server_stored" value="server_stored"' . ($context['member']['avatar']['choice'] == 'server_stored' ? ' checked="checked"' : '') . ' class="input_radio" /> ' . $txt['choose_avatar_gallery'] . '</label>' : '',

            !empty($context['member']['avatar']['allow_external']) ? '<label for="avatar_choice_external"' . (isset($context['modify_error']['bad_avatar']) ? ' class="error is-block"' : 'class="is-block"') . '><input type="radio" onclick="swap_avatar(this); return true;" name="avatar_choice" id="avatar_choice_external" value="external"' . ($context['member']['avatar']['choice'] == 'external' ? ' checked="checked"' : '') . ' class="input_radio" /> ' . $txt['my_own_pic'] . '</label>' : '', '
              ',

            !empty($context['member']['avatar']['allow_upload']) ? '<label for="avatar_choice_upload"' . (isset($context['modify_error']['bad_avatar']) ? ' class="error is-block"' : 'class="is-block"') . '><input type="radio" onclick="swap_avatar(this); return true;" name="avatar_choice" id="avatar_choice_upload" value="upload"' . ($context['member']['avatar']['choice'] == 'upload' ? ' checked="checked"' : '') . ' class="input_radio" /> ' . $txt['avatar_will_upload'] . '</label>' : '', '
          </div>
        </div>
        <div class="field-body">';

        // If users are allowed to choose avatars stored on the server show selection boxes to choice them from.
        if (!empty($context['member']['avatar']['allow_server_stored']))
        {
          echo '
            <div id="avatar_server_stored">
              <div class="columns">
                <div class="column">
                  <div class="select is-multiple">
                  <select style="height: 5rem;" name="cat" id="cat" size="10" onchange="changeSel(\'\');" onfocus="selectRadioByName(document.forms.creator.avatar_choice, \'server_stored\');">';
                  // This lists all the file catergories.
                  foreach ($context['avatars'] as $avatar)
                    echo '
                      <option value="', $avatar['filename'] . ($avatar['is_dir'] ? '/' : ''), '"', ($avatar['checked'] ? ' selected="selected"' : ''), '>', $avatar['name'], '</option>';
                    echo '
                  </select>
                  </div>
                </div>
                <div class="column">
                  <div class="select is-multiple">
                    <select style="height: 5rem;" name="file" id="file" size="10" style="display: none;" onchange="showAvatar()" onfocus="selectRadioByName(document.forms.creator.avatar_choice, \'server_stored\');" disabled="disabled"><option></option></select>
                  </div>
                </div>
                <div class="column">
                  <img name="avatar" id="avatar" src="', !empty($context['member']['avatar']['allow_external']) && $context['member']['avatar']['choice'] == 'external' ? $context['member']['avatar']['external'] : $modSettings['avatar_url'] . '/blank.gif', '" alt="Do Nothing" /></div>
                  <script type="text/javascript"><!-- // --><![CDATA[
                    var files = ["' . implode('", "', $context['avatar_list']) . '"];
                    var avatar = document.getElementById("avatar");
                    var cat = document.getElementById("cat");
                    var selavatar = "' . $context['avatar_selected'] . '";
                    var avatardir = "' . $modSettings['avatar_url'] . '/";
                    var size = avatar.alt.substr(3, 2) + " " + avatar.alt.substr(0, 2) + String.fromCharCode(117, 98, 116);
                    var file = document.getElementById("file");

                    if (avatar.src.indexOf("blank.gif") > -1)
                      changeSel(selavatar);
                    else
                      previewExternalAvatar(avatar.src)

                    function changeSel(selected)
                    {
                      if (cat.selectedIndex == -1)
                        return;

                      if (cat.options[cat.selectedIndex].value.indexOf("/") > 0)
                      {
                        var i;
                        var count = 0;

                        file.style.display = "inline";
                        file.disabled = false;

                        for (i = file.length; i >= 0; i = i - 1)
                          file.options[i] = null;

                        for (i = 0; i < files.length; i++)
                          if (files[i].indexOf(cat.options[cat.selectedIndex].value) == 0)
                          {
                            var filename = files[i].substr(files[i].indexOf("/") + 1);
                            var showFilename = filename.substr(0, filename.lastIndexOf("."));
                            showFilename = showFilename.replace(/[_]/g, " ");

                            file.options[count] = new Option(showFilename, files[i]);

                            if (filename == selected)
                            {
                              if (file.options.defaultSelected)
                                file.options[count].defaultSelected = true;
                              else
                                file.options[count].selected = true;
                            }

                            count++;
                          }

                        if (file.selectedIndex == -1 && file.options[0])
                          file.options[0].selected = true;

                        showAvatar();
                      }
                      else
                      {
                        file.style.display = "none";
                        file.disabled = true;
                        document.getElementById("avatar").src = avatardir + cat.options[cat.selectedIndex].value;
                        document.getElementById("avatar").style.width = "";
                        document.getElementById("avatar").style.height = "";
                      }
                    }

                    function showAvatar()
                    {
                      if (file.selectedIndex == -1)
                        return;

                      document.getElementById("avatar").src = avatardir + file.options[file.selectedIndex].value;
                      document.getElementById("avatar").alt = file.options[file.selectedIndex].text;
                      document.getElementById("avatar").alt += file.options[file.selectedIndex].text == size ? "!" : "";
                      document.getElementById("avatar").style.width = "";
                      document.getElementById("avatar").style.height = "";
                    }

                    function previewExternalAvatar(src)
                    {
                      if (!document.getElementById("avatar"))
                        return;

                      var maxHeight = ', !empty($modSettings['avatar_max_height_external']) ? $modSettings['avatar_max_height_external'] : 0, ';
                      var maxWidth = ', !empty($modSettings['avatar_max_width_external']) ? $modSettings['avatar_max_width_external'] : 0, ';
                      var tempImage = new Image();

                      tempImage.src = src;
                      if (maxWidth != 0 && tempImage.width > maxWidth)
                      {
                        document.getElementById("avatar").style.height = parseInt((maxWidth * tempImage.height) / tempImage.width) + "px";
                        document.getElementById("avatar").style.width = maxWidth + "px";
                      }
                      else if (maxHeight != 0 && tempImage.height > maxHeight)
                      {
                        document.getElementById("avatar").style.width = parseInt((maxHeight * tempImage.width) / tempImage.height) + "px";
                        document.getElementById("avatar").style.height = maxHeight + "px";
                      }
                      document.getElementById("avatar").src = src;
                    }
                  // ]]></script>
                </div>
              </div>
            ';
        }

        // If the user can link to an off server avatar, show them a box to input the address.
        if (!empty($context['member']['avatar']['allow_external']))
        {
          echo '
            <div id="avatar_external">
              <div class="smalltext">', $txt['avatar_by_url'], '</div>
              <input type="text" name="userpicpersonal" size="45" value="', $context['member']['avatar']['external_original'], '" onfocus="selectRadioByName(document.forms.creator.avatar_choice, \'external\');" onchange="if (typeof(previewExternalAvatar) != \'undefined\') previewExternalAvatar(this.value);" class="input input" />
            </div>';
        }

        // If the user is able to upload avatars to the server show them an upload box.
        if (!empty($context['member']['avatar']['allow_upload']))
        {
          echo '
              <div id="avatar_upload" class="file">
                <div class="columns">
                  <div class="column">
                    <label class="file-label">
                      <input type="file" name="attachment" value="" onfocus="selectRadioByName(document.forms.creator.avatar_choice, \'upload\');" class="input_file file-input" />
                      <span class="file-cta">
                        <span class="file-icon">
                          <span class="fa fa-upload"></span>
                        </span>
                        <span class="filed-label">Choose an image</span>
                      </span>
                    </label>
                  </div>
                  <div class="column">
                    ', ($context['member']['avatar']['id_attach'] > 0 ? '<img src="' . $context['member']['avatar']['href'] . (strpos($context['member']['avatar']['href'], '?') === false ? '?' : '&amp;') . 'time=' . time() . '" alt="" /><input type="hidden" name="id_attach" value="' . $context['member']['avatar']['id_attach'] . '" />' : ''), '
                  </div>
                </div>
              </div>';
        }

        echo '
              <script type="text/javascript"><!-- // --><![CDATA[
                ', !empty($context['member']['avatar']['allow_server_stored']) ? 'document.getElementById("avatar_server_stored").style.display = "' . ($context['member']['avatar']['choice'] == 'server_stored' ? '' : 'none') . '";' : '', '
                ', !empty($context['member']['avatar']['allow_external']) ? 'document.getElementById("avatar_external").style.display = "' . ($context['member']['avatar']['choice'] == 'external' ? '' : 'none') . '";' : '', '
                ', !empty($context['member']['avatar']['allow_upload']) ? 'document.getElementById("avatar_upload").style.display = "' . ($context['member']['avatar']['choice'] == 'upload' ? '' : 'none') . '";' : '', '

                function swap_avatar(type)
                {
                  switch(type.id)
                  {
                    case "avatar_choice_server_stored":
                      ', !empty($context['member']['avatar']['allow_server_stored']) ? 'document.getElementById("avatar_server_stored").style.display = "";' : '', '
                      ', !empty($context['member']['avatar']['allow_external']) ? 'document.getElementById("avatar_external").style.display = "none";' : '', '
                      ', !empty($context['member']['avatar']['allow_upload']) ? 'document.getElementById("avatar_upload").style.display = "none";' : '', '
                      break;
                    case "avatar_choice_external":
                      ', !empty($context['member']['avatar']['allow_server_stored']) ? 'document.getElementById("avatar_server_stored").style.display = "none";' : '', '
                      ', !empty($context['member']['avatar']['allow_external']) ? 'document.getElementById("avatar_external").style.display = "";' : '', '
                      ', !empty($context['member']['avatar']['allow_upload']) ? 'document.getElementById("avatar_upload").style.display = "none";' : '', '
                      break;
                    case "avatar_choice_upload":
                      ', !empty($context['member']['avatar']['allow_server_stored']) ? 'document.getElementById("avatar_server_stored").style.display = "none";' : '', '
                      ', !empty($context['member']['avatar']['allow_external']) ? 'document.getElementById("avatar_external").style.display = "none";' : '', '
                      ', !empty($context['member']['avatar']['allow_upload']) ? 'document.getElementById("avatar_upload").style.display = "";' : '', '
                      break;
                    case "avatar_choice_none":
                      ', !empty($context['member']['avatar']['allow_server_stored']) ? 'document.getElementById("avatar_server_stored").style.display = "none";' : '', '
                      ', !empty($context['member']['avatar']['allow_external']) ? 'document.getElementById("avatar_external").style.display = "none";' : '', '
                      ', !empty($context['member']['avatar']['allow_upload']) ? 'document.getElementById("avatar_upload").style.display = "none";' : '', '
                      break;
                  }
                }
              // ]]></script>

        </div>
    </fieldset>
    ';
}

// Callback for modifying karam.
function template_profile_karma_modify()
{
  global $context, $modSettings, $txt;

    echo '
              <dt>
                <strong>', $modSettings['karmaLabel'], '</strong>
              </dt>
              <dd>
                ', $modSettings['karmaApplaudLabel'], ' <input type="text" name="karma_good" size="4" value="', $context['member']['karma']['good'], '" onchange="setInnerHTML(document.getElementById(\'karmaTotal\'), this.value - this.form.karma_bad.value);" style="margin-right: 2ex;" class="input" /> ', $modSettings['karmaSmiteLabel'], ' <input type="text" name="karma_bad" size="4" value="', $context['member']['karma']['bad'], '" onchange="this.form.karma_good.onchange();" class="input" /><br />
                (', $txt['total'], ': <span id="karmaTotal">', ($context['member']['karma']['good'] - $context['member']['karma']['bad']), '</span>)
              </dd>';
}

// Select the time format!
function template_profile_timeformat_modify()
{
  global $context, $modSettings, $txt, $scripturl, $settings;

  echo '
    <div class="field is-horizontal">
      <div class="field-label has-text-left">
        <a href="', $scripturl, '?action=helpadmin;help=time_format" onclick="return reqWin(this.href);">
          <span class="icon is-small">
            <span class="fa fa-question-circle"></span>
          </span>
        </a>
        <span>
          <label>', $txt['time_format'], '</label>
        </span>
        
      </div>
      <div class="field-body">
        <div class="field">
          <p class="help mb-1">' , $txt['date_format'], '</p>
          <div class="control">
            <div class="select">
              <select name="easyformat" onchange="document.forms.creator.time_format.value = this.options[this.selectedIndex].value;" style="margin-bottom: 4px;">';
                // Help the user by showing a list of common time formats.
                foreach ($context['easy_timeformats'] as $time_format)
                  echo '
                  <option value="', $time_format['format'], '"', $time_format['format'] == $context['member']['time_format'] ? ' selected="selected"' : '', '>', $time_format['title'], '</option>';
                echo '
              </select>
            </div>
          </div>
          <input type="text" name="time_format" value="', $context['member']['time_format'], '" size="30" class="input mt-2 is-auto" />
        </div>
      </div>
    </div>';
}

// Time offset?
function template_profile_timeoffset_modify()
{
  global $txt, $context;

  echo '
    <div class="field is-horizontal">
      <div class="field-label has-text-left">
        <label class="label">', $txt['time_offset'], '</label>
      </div>
      <div class="field-body">
        <div class="field">
          <p class="help mb-1">' , $txt['date_format'], '</p>
          <p class="help mb-1">', $txt['current_time'], ': <em>', $context['current_forum_time'], '</em></p>
          <div class="control">
            <input type="text" name="time_offset" id="time_offset" size="5" maxlength="5" value="', $context['member']['time_offset'], '" class="input" /> <a href="javascript:void(0);" onclick="currentDate = new Date(', $context['current_forum_time_js'], '); document.getElementById(\'time_offset\').value = autoDetectTimeOffset(currentDate); return false;">', $txt['timeoffset_autodetect'], '</a>
          </div>
          
        </div>
      </div>
    </div>';
}

// Theme?
function template_profile_theme_pick()
{
  global $txt, $context, $scripturl;

  echo '
    <div class="field is-horizontal">
      <div class="field-label has-text-left">
        <label class="label">', $txt['current_theme'], '</label>
      </div>
      <div class="field-body">
        <div class="field">
         ', $context['member']['theme']['name'], ' <a href="', $scripturl, '?action=theme;sa=pick;u=', $context['id_member'], ';', $context['session_var'], '=', $context['session_id'], '">', $txt['change'], '</a>
        </div>
      </div>
    </div>';
}

// Smiley set picker.
function template_profile_smiley_pick()
{
  global $txt, $context, $modSettings, $settings;

  echo '
              <dt>
                <strong>', $txt['smileys_current'], ':</strong>
              </dt>
              <dd>
                <select name="smiley_set" onchange="document.getElementById(\'smileypr\').src = this.selectedIndex == 0 ? \'', $settings['images_url'], '/blank.gif\' : \'', $modSettings['smileys_url'], '/\' + (this.selectedIndex != 1 ? this.options[this.selectedIndex].value : \'', !empty($settings['smiley_sets_default']) ? $settings['smiley_sets_default'] : $modSettings['smiley_sets_default'], '\') + \'/smiley.gif\';">';
  foreach ($context['smiley_sets'] as $set)
    echo '
                  <option value="', $set['id'], '"', $set['selected'] ? ' selected="selected"' : '', '>', $set['name'], '</option>';
  echo '
                </select> <img id="smileypr" src="', $context['member']['smiley_set']['id'] != 'none' ? $modSettings['smileys_url'] . '/' . ($context['member']['smiley_set']['id'] != '' ? $context['member']['smiley_set']['id'] : (!empty($settings['smiley_sets_default']) ? $settings['smiley_sets_default'] : $modSettings['smiley_sets_default'])) . '/smiley.gif' : $settings['images_url'] . '/blank.gif', '" alt=":)" align="top" style="padding-left: 20px;" />
              </dd>';
}

// Change the way you login to the forum.
function template_authentication_method()
{
  global $context, $settings, $options, $scripturl, $modSettings, $txt;

  // The main header!
  echo '
    <script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/register.js"></script>
    <form action="', $scripturl, '?action=profile;area=authentication;save" method="post" accept-charset="', $context['character_set'], '" name="creator" id="creator" enctype="multipart/form-data">
      <div class="cat_bar">
        <h3 class="catbg">
          <span class="ie6_header floatleft"><img src="', $settings['images_url'], '/icons/profile_sm.gif" alt="" class="icon" />', $txt['authentication'], '</span>
        </h3>
      </div>
      <p class="windowbg description">', $txt['change_authentication'], '</p>
      <div class="windowbg2">
        <span class="topslice"><span></span></span>
        <div class="content">
          <dl>
            <dt>
              <input type="radio" onclick="updateAuthMethod();" name="authenticate" value="openid" id="auth_openid"', $context['auth_method'] == 'openid' ? ' checked="checked"' : '', ' class="input_radio" /><label for="auth_openid"><strong>', $txt['authenticate_openid'], '</strong></label>&nbsp;<em><a href="', $scripturl, '?action=helpadmin;help=register_openid" onclick="return reqWin(this.href);" class="help">(?)</a></em><br />
              <input type="radio" onclick="updateAuthMethod();" name="authenticate" value="passwd" id="auth_pass"', $context['auth_method'] == 'password' ? ' checked="checked"' : '', ' class="input_radio" /><label for="auth_pass"><strong>', $txt['authenticate_password'], '</strong></label>
            </dt>
            <dd>
              <dl id="auth_openid_div">
                <dt>
                  <em>', $txt['authenticate_openid_url'], ':</em>
                </dt>
                <dd>
                  <input type="text" name="openid_identifier" id="openid_url" size="30" tabindex="', $context['tabindex']++, '" value="', $context['member']['openid_uri'], '" class="input openid_login" />
                </dd>
              </dl>
              <dl id="auth_pass_div">
                <dt>
                  <em>', $txt['choose_pass'], ':</em>
                </dt>
                <dd>
                  <input type="password" name="passwrd1" id="smf_autov_pwmain" size="30" tabindex="', $context['tabindex']++, '" class="input_password" />
                  <span id="smf_autov_pwmain_div" style="display: none;"><img id="smf_autov_pwmain_img" src="', $settings['images_url'], '/icons/field_invalid.gif" alt="*" /></span>
                </dd>
                <dt>
                  <em>', $txt['verify_pass'], ':</em>
                </dt>
                <dd>
                  <input type="password" name="passwrd2" id="smf_autov_pwverify" size="30" tabindex="', $context['tabindex']++, '" class="input_password" />
                  <span id="smf_autov_pwverify_div" style="display: none;"><img id="smf_autov_pwverify_img" src="', $settings['images_url'], '/icons/field_valid.gif" alt="*" /></span>
                </dd>
              </dl>
            </dd>
          </dl>';

  if ($context['require_password'])
    echo '
          <hr width="100%" size="1" class="hrcolor clear" />
          <dl>
            <dt>
              <strong', isset($context['modify_error']['bad_password']) || isset($context['modify_error']['no_password']) ? ' class="error"' : '', '>', $txt['current_password'], ': </strong><br />
              <span class="smalltext">', $txt['required_security_reasons'], '</span>
            </dt>
            <dd>
              <input type="password" name="oldpasswrd" size="20" style="margin-right: 4ex;" class="input_password" />
            </dd>
          </dl>';

echo '
          <div class="righttext">
            <input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
            <input type="hidden" name="u" value="', $context['id_member'], '" />
            <input type="hidden" name="sa" value="', $context['menu_item_selected'], '" />
            <input type="submit" value="', $txt['change_profile'], '" class="button is-primary" />
          </div>
        </div>
        <span class="botslice"><span></span></span>
      </div>
    </form>';

  // The password stuff.
  echo '
  <script type="text/javascript"><!-- // --><![CDATA[
  var regTextStrings = {
    "password_short": "', $txt['registration_password_short'], '",
    "password_reserved": "', $txt['registration_password_reserved'], '",
    "password_numbercase": "', $txt['registration_password_numbercase'], '",
    "password_no_match": "', $txt['registration_password_no_match'], '",
    "password_valid": "', $txt['registration_password_valid'], '"
  };
  var verificationHandle = new smfRegister("creator", ', empty($modSettings['password_strength']) ? 0 : $modSettings['password_strength'], ', regTextStrings);
  var currentAuthMethod = \'passwd\';
  function updateAuthMethod()
  {
    // What authentication method is being used?
    if (!document.getElementById(\'auth_openid\') || !document.getElementById(\'auth_openid\').checked)
      currentAuthMethod = \'passwd\';
    else
      currentAuthMethod = \'openid\';

    // No openID?
    if (!document.getElementById(\'auth_openid\'))
      return true;

    document.forms.creator.openid_url.disabled = currentAuthMethod == \'openid\' ? false : true;
    document.forms.creator.smf_autov_pwmain.disabled = currentAuthMethod == \'passwd\' ? false : true;
    document.forms.creator.smf_autov_pwverify.disabled = currentAuthMethod == \'passwd\' ? false : true;
    document.getElementById(\'smf_autov_pwmain_div\').style.display = currentAuthMethod == \'passwd\' ? \'\' : \'none\';
    document.getElementById(\'smf_autov_pwverify_div\').style.display = currentAuthMethod == \'passwd\' ? \'\' : \'none\';

    if (currentAuthMethod == \'passwd\')
    {
      verificationHandle.refreshMainPassword();
      verificationHandle.refreshVerifyPassword();
      document.forms.creator.openid_url.style.backgroundColor = \'\';
      document.getElementById("auth_openid_div").style.display = "none";
      document.getElementById("auth_pass_div").style.display = "";
    }
    else
    {
      document.forms.creator.smf_autov_pwmain.style.backgroundColor = \'\';
      document.forms.creator.smf_autov_pwverify.style.backgroundColor = \'\';
      document.forms.creator.openid_url.style.backgroundColor = \'#FCE184\';
      document.getElementById("auth_openid_div").style.display = "";
      document.getElementById("auth_pass_div").style.display = "none";
    }
  }
  updateAuthMethod();
  // ]]></script>';
}

?>