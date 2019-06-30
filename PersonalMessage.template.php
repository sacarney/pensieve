<?php
/**
 * Simple Machines Forum (SMF)
 *
 * @package SMF
 * @author Simple Machines
 * @copyright 2011 Simple Machines
 * @license http://www.simplemachines.org/about/smf/license.php BSD
 *
 * @version 2.0.2
 */

// This is the main sidebar for the personal messages section.
function template_pm_above()
{
  global $context, $settings, $options, $txt;

  echo '
  <div id="personal_messages">';

  // Show the capacity bar, if available.
  if (!empty($context['limit_bar']))
  {
    echo '
      <table class="table is-bordered is-striped">
        <tr class="titlebg2">
          <td width="200" align="right"><strong>', $txt['pm_capacity'], ':</strong></td>
          <td width="50%">
            <div class="capacity_bar">
              <div class="', $context['limit_bar']['percent'] > 85 ? 'full' : ($context['limit_bar']['percent'] > 40 ? 'filled' : 'empty'), '" style="width: ', $context['limit_bar']['bar'], '%;"></div>
            </div>
          </td>
          <td width="200"', $context['limit_bar']['percent'] > 90 ? ' class="alert"' : '', '>
            ', $context['limit_bar']['text'], '
          </td>
        </tr>
      </table>';
  }

  // Message sent? Show a small indication.
  if (isset($context['pm_sent']))
    echo '
      <div class="windowbg" id="profile_success">
        ', $txt['pm_sent'], '
      </div>';
}

// Just the end of the index bar, nothing special.
function template_pm_below()
{
  global $context, $settings, $options;

  echo '
  </div>';
}

function template_folder()
{
  global $context, $settings, $options, $scripturl, $modSettings, $txt;

  // The every helpful javascript!
  echo '
  <script type="text/javascript"><!-- // --><![CDATA[
    var allLabels = {};
    var currentLabels = {};
    function loadLabelChoices()
    {
      var listing = document.forms.pmFolder.elements;
      var theSelect = document.forms.pmFolder.pm_action;
      var add, remove, toAdd = {length: 0}, toRemove = {length: 0};

      if (theSelect.childNodes.length == 0)
        return;';

  // This is done this way for internationalization reasons.
  echo '
      if (!(\'-1\' in allLabels))
      {
        for (var o = 0; o < theSelect.options.length; o++)
          if (theSelect.options[o].value.substr(0, 4) == "rem_")
            allLabels[theSelect.options[o].value.substr(4)] = theSelect.options[o].text;
      }

      for (var i = 0; i < listing.length; i++)
      {
        if (listing[i].name != "pms[]" || !listing[i].checked)
          continue;

        var alreadyThere = [], x;
        for (x in currentLabels[listing[i].value])
        {
          if (!(x in toRemove))
          {
            toRemove[x] = allLabels[x];
            toRemove.length++;
          }
          alreadyThere[x] = allLabels[x];
        }

        for (x in allLabels)
        {
          if (!(x in alreadyThere))
          {
            toAdd[x] = allLabels[x];
            toAdd.length++;
          }
        }
      }

      while (theSelect.options.length > 2)
        theSelect.options[2] = null;

      if (toAdd.length != 0)
      {
        theSelect.options[theSelect.options.length] = new Option("', $txt['pm_msg_label_apply'], '", "");
        setInnerHTML(theSelect.options[theSelect.options.length - 1], "', $txt['pm_msg_label_apply'], '");
        theSelect.options[theSelect.options.length - 1].disabled = true;

        for (i in toAdd)
        {
          if (i != "length")
            theSelect.options[theSelect.options.length] = new Option(toAdd[i], "add_" + i);
        }
      }

      if (toRemove.length != 0)
      {
        theSelect.options[theSelect.options.length] = new Option("', $txt['pm_msg_label_remove'], '", "");
        setInnerHTML(theSelect.options[theSelect.options.length - 1], "', $txt['pm_msg_label_remove'], '");
        theSelect.options[theSelect.options.length - 1].disabled = true;

        for (i in toRemove)
        {
          if (i != "length")
            theSelect.options[theSelect.options.length] = new Option(toRemove[i], "rem_" + i);
        }
      }
    }
  // ]]></script>';

  echo '
<form action="', $scripturl, '?action=pm;sa=pmactions;', $context['display_mode'] == 2 ? 'conversation;' : '', 'f=', $context['folder'], ';start=', $context['start'], $context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : '', '" method="post" accept-charset="', $context['character_set'], '" name="pmFolder">';

  // If we are not in single display mode show the subjects on the top!
  if ($context['display_mode'] != 1)
  {
    template_subject_list();
    echo '<br />';
  }

  // Got some messages to display?
  if ($context['get_pmessage']('message', true))
  {
    // Show a few buttons if we are in conversation mode and outputting the first message.
    if ($context['display_mode'] == 2)
    {
      // Build the normal button array.
      $conversation_buttons = array(
        'reply' => array(
          'text' => 'reply_to_all', 
          'class' => 'is-primary',
          'image' => 'reply.gif', 
          'icon' => 'fa-reply',
          'lang' => true, 
          'url' => $scripturl . '?action=pm;sa=send;f=' . $context['folder'] . ($context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : '') . ';pmsg=' . $context['current_pm'] . ';u=all'
        ),
        'delete' => array(
          'text' => 'delete_conversation', 
          'image' => 'delete.gif', 
          'icon' => 'fa-times',
          'lang' => true, 
          'url' => $scripturl . '?action=pm;sa=pmactions;pm_actions[' . $context['current_pm'] . ']=delete;conversation;f=' . $context['folder'] . ';start=' . $context['start'] . ($context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : '') . ';' . $context['session_var'] . '=' . $context['session_id'], 'custom' => 'onclick="return confirm(\'' . addslashes($txt['remove_message']) . '?\');"'
        ),
      );

      // Show the conversation buttons.
      echo '

        <div class="mb-3">';

      template_button_strip($conversation_buttons, 'right');

      echo '
        </div>';
    }

  echo '
  <div class="tborder" style="padding: 1px">';

    // Show the helpful titlebar - generally.
    if ($context['display_mode'] != 1)
      echo '
      <div class="cat_bar">
        <h2 class="title is-5 mb-4">
          <div class="columns">
            <div class="column is-one-fifth">', $txt['author'], '</div>
            <div class="column is-four-fifths">', $txt[$context['display_mode'] == 0 ? 'messages' : 'conversation'], '</div>
          </div>
        </h3>
      </div>';

    // Cache some handy buttons.
      // Not actually using these...
    $quote_button = create_button('quote.gif', 'reply_quote', 'quote', 'align="middle"');
    $reply_button = create_button('im_reply.gif', 'reply', 'reply', 'align="middle"');
    $reply_all_button = create_button('im_reply_all.gif', 'reply_to_all', 'reply_to_all', 'align="middle"');
    $forward_button = create_button('quote.gif', 'reply_quote', 'reply_quote', 'align="middle"');
    $delete_button = create_button('delete.gif', 'remove_message', 'remove', 'align="middle"');

    while ($message = $context['get_pmessage']('message'))
    {
      $is_first_post = !isset($is_first_post) ? true : false;

      // Begin the message
      echo'
      <article class="mb-4 the-post-wrapper">
        <div class="columns m-0">';

        // Show information about the poster of this message.
          echo '
          <aside class="column is-one-fifth p-0">
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

                      echo'<div class="is-flex has-text-weight-normal pl-2 is-size-7-mobile is-hidden-tablet">';
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
                        
                            <ul>';
                    }
                    echo '
                              <li>', $custom['value'], '</li>';
                  }
                  if ($shown)
                    echo '
                            </ul>
                    ';
                }

                // Any custom fields for standard placement?
                if (!empty($message['member']['custom_fields']))
                {
                  foreach ($message['member']['custom_fields'] as $custom)
                    if (empty($custom['placement']) || empty($custom['value']))
                      echo '
                          <li class="custom">', $custom['title'], ': ', $custom['value'], '</li>';
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

        // The post
          echo'
          <div class="column is-four-fifths p-0 the-post">

            <header class="columns">
              <div class="column pb-0">
                <div>
                  <h1 class="title is-6 mb-2" id="subject_', $message['id'], '">', $message['subject'], '</h1>';

                  // Show who the message was sent to.
                  echo'
                  <p class="is-muted is-size-6-5">
                    <span class="is-uppercase">', $txt['sent_to'], '</span>

                    <span>';
                    // People it was sent directly to....
                    if (!empty($message['recipients']['to']))
                      echo implode(', ', $message['recipients']['to']);
                    // Otherwise, we're just going to say "some people"...
                    elseif ($context['folder'] != 'sent')
                      echo '(', $txt['pm_undisclosed_recipients'], ')';

                    echo'
                    </span>

                    ', $txt['on'], ': <span class="is-uppercase">', $message['time'], '</span>
                  </p>
                </div>
              </div>

              <div class="column is-narrow" role="toolbar" aria-label="Post Toolbar">';
                      // Show reply buttons if you have the permission to send PMs.
                if ($context['can_send_pm'])
                {
                  // You can't really reply if the member is gone.
                  if (!$message['member']['is_guest'])
                  {
                    // Were than more than one recipient you can reply to? (Only shown when not in conversation mode.)
                    if ($message['number_recipients'] > 1 && $context['display_mode'] != 2)
                      echo '
                        <a class="button is-small mr-1" href="', $scripturl, '?action=pm;sa=send;f=', $context['folder'], $context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : '', ';pmsg=', $message['id'], ';quote;u=all">
                          <span class="icon m-0">
                            <span class="fa fa-reply"></span>
                          </span>
                          <span class="is-hidden-touch ml-1">Reply All</span>
                        </a>';

                    echo '
                        <a class="button is-small mr-1" href="', $scripturl, '?action=pm;sa=send;f=', $context['folder'], $context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : '', ';pmsg=', $message['id'], ';u=', $message['member']['id'], '">
                          <span class="icon m-0">
                            <span class="fa fa-reply"></span>
                          </span>
                          <span class="is-hidden-touch ml-1">Reply</span>
                        </a>

                        <a class="button is-small mr-1" href="', $scripturl, '?action=pm;sa=send;f=', $context['folder'], $context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : '', ';pmsg=', $message['id'], ';quote', $context['folder'] == 'sent' ? '' : ';u=' . $message['member']['id'], '">
                          <span class="icon m-0">
                            <span class="fa fa-quote-left"></span>
                          </span>
                          <span class="is-hidden-touch ml-1">Quote</span>
                        </a>';
                  }
                  // This is for "forwarding" - even if the member is gone.
                  else
                    echo '
                        <a class="button is-small mr-1" href="', $scripturl, '?action=pm;sa=send;f=', $context['folder'], $context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : '', ';pmsg=', $message['id'], ';quote">
                          <span class="icon m-0">
                            <span class="fa fa-arrow-right"></span>
                          </span>
                          <span class="is-hidden-touch ml-1">Forward</span>
                        </a>';
                }
                echo '
                  <a class="button is-small mr-1" href="', $scripturl, '?action=pm;sa=pmactions;pm_actions[', $message['id'], ']=delete;f=', $context['folder'], ';start=', $context['start'], $context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : '', ';', $context['session_var'], '=', $context['session_id'], '" onclick="return confirm(\'', addslashes($txt['remove_message']), '?\');">
                          <span class="icon m-0">
                            <span class="fa fa-times"></span>
                          </span>
                          <span class="is-hidden-touch ml-1">Remove</span>
                        </a>';

                if (empty($context['display_mode']))
                  echo '
                    <input style="vertical-align: middle;" type="checkbox" name="pms[]" id="deletedisplay', $message['id'], '" value="', $message['id'], '" onclick="document.getElementById(\'deletelisting', $message['id'], '\').checked = this.checked;" class="input_check" />';

                  echo'

              </div>
            </header>

            <div class="content mt-4 the-post-content">
            ', $message['body'], '
            </div>';

            // Moderation Button
            if (!empty($modSettings['enableReportPM']) && $context['folder'] != 'sent')
            echo '
              <footer class="level">
                <div class="level-left"></div>
                <div class="level-right mr-2">
                  <a class="button is-small" href="', $scripturl, '?action=pm;sa=report;l=', $context['current_label_id'], ';pmsg=', $message['id'], '">', $txt['pm_report_to_admin'], '</a>
                </div>
              </footer>';

            echo'
          </div>
        </div>
      ';

      // Signature
      echo'
        <aside class="columns m-0 the-post-footer">
          <div class="column is-one-fifth p-0"></div>
          <div class="column is-four-fifths pl-0">';
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


      // Add an extra line at the bottom if we have labels enabled.
      if ($context['folder'] != 'sent' && !empty($context['currently_using_labels']) && $context['display_mode'] != 2)
      {
        echo '
            <div class="labels righttext">';

        // Add the label drop down box.
        if (!empty($context['currently_using_labels']))
        {
          echo '
            <div class="select">
              <select name="pm_actions[', $message['id'], ']" onchange="if (this.options[this.selectedIndex].value) form.submit();">
                <option value="">', $txt['pm_msg_label_title'], ':</option>
              <option value="" disabled="disabled">---------------</option>';

          // Are there any labels which can be added to this?
          if (!$message['fully_labeled'])
          {
            echo '
              <option value="" disabled="disabled">', $txt['pm_msg_label_apply'], ':</option>';

            foreach ($context['labels'] as $label)
            {
              if (!isset($message['labels'][$label['id']]))
                echo '
              <option value="', $label['id'], '">&nbsp;', $label['name'], '</option>';
            }
          }

          // ... and are there any that can be removed?
          if (!empty($message['labels']) && (count($message['labels']) > 1 || !isset($message['labels'][-1])))
          {
            echo '
                <option value="" disabled="disabled">', $txt['pm_msg_label_remove'], ':</option>';
            foreach ($message['labels'] as $label)
              echo '
                <option value="', $label['id'], '">&nbsp;', $label['name'], '</option>';
          }
          echo '
              </select>
              </div>
              <noscript>
                <input type="submit" value="', $txt['pm_apply'], '" class="button is-small" />
              </noscript>';
        }

        echo '
            </div>';
      }

      echo '
    </article>';
    }

    echo '
    </div>';

    if (empty($context['display_mode']))
      echo '
    <div class="catbg flow_hidden" style="padding: 1px; margin-top: 1ex;">
      <div class="floatleft pagesection mb-4">', $txt['pages'], ': ', $context['page_index'], '</div>
      <div class="floatright"><input type="submit" name="del_selected" value="', $txt['quickmod_delete_selected'], '" style="font-weight: normal;" onclick="if (!confirm(\'', $txt['delete_selected_confirm'], '\')) return false;" class="button is-small" /></div>
    </div>';

    // Show a few buttons if we are in conversation mode and outputting the first message.
    elseif ($context['display_mode'] == 2 && isset($conversation_buttons))
      template_button_strip($conversation_buttons);

    echo '
    <br />';
  }

  // Individual messages = buttom list!
  if ($context['display_mode'] == 1)
  {
    template_subject_list();
    echo '<br />';
  }

  echo '
  <input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
</form>';
}

// Just list all the personal message subjects - to make templates easier.
function template_subject_list()
{
  global $context, $options, $settings, $modSettings, $txt, $scripturl;

  echo '
    <table class="table is-bordered is-striped is-fullwidth responsive-table">
      <tr class="titlebg">
        <th align="center"><a href="', $scripturl, '?action=pm;view;f=', $context['folder'], ';start=', $context['start'], ';sort=', $context['sort_by'], ($context['sort_direction'] == 'up' ? '' : ';desc'), ($context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : ''), '"><span class="fa fa-refresh"></span></a></th>
        <th style="width: 32ex;"><a href="', $scripturl, '?action=pm;f=', $context['folder'], ';start=', $context['start'], ';sort=date', $context['sort_by'] == 'date' && $context['sort_direction'] == 'up' ? ';desc' : '', $context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : '', '">', $txt['date'], $context['sort_by'] == 'date' ? ' <img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" />' : '', '</a></td>
        <th width="46%"><a href="', $scripturl, '?action=pm;f=', $context['folder'], ';start=', $context['start'], ';sort=subject', $context['sort_by'] == 'subject' && $context['sort_direction'] == 'up' ? ';desc' : '', $context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : '', '">', $txt['subject'], $context['sort_by'] == 'subject' ? ' <img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" />' : '', '</a></th>
        <th><a href="', $scripturl, '?action=pm;f=', $context['folder'], ';start=', $context['start'], ';sort=name', $context['sort_by'] == 'name' && $context['sort_direction'] == 'up' ? ';desc' : '', $context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : '', '">', ($context['from_or_to'] == 'from' ? $txt['from'] : $txt['to']), $context['sort_by'] == 'name' ? ' <img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" />' : '', '</a></th>
        <th align="center" width="24"><input type="checkbox" onclick="invertAll(this, this.form);" class="input_check" /></th>
      </tr>';

  if (!$context['show_delete'])
    echo '
      <tr>
        <td class="windowbg" colspan="5">', $txt['msg_alert_none'], '</td>
      </tr>';

  $next_alternate = 0;
  while ($message = $context['get_pmessage']('subject'))
  {
    echo '
      <tr class="', $next_alternate ? 'windowbg' : 'windowbg2', '">
        <td align="center">
        <script type="text/javascript"><!-- // --><![CDATA[
          currentLabels[', $message['id'], '] = {';

    if (!empty($message['labels']))
    {
      $first = true;
      foreach ($message['labels'] as $label)
      {
        echo $first ? '' : ',', '
          "', $label['id'], '": "', $label['name'], '"';
        $first = false;
      }
    }

    echo '
          };
        // ]]></script>
          ', $message['is_replied_to'] ? '<img src="' . $settings['images_url'] . '/icons/pm_replied.gif" style="margin-right: 4px;" alt="' . $txt['pm_replied'] . '" />' : '<img src="' . $settings['images_url'] . '/icons/pm_read.gif" style="margin-right: 4px;" alt="' . $txt['pm_read'] . '" />', '</td>
        <td>', $message['time'], '</td>
        <td>', ($context['display_mode'] != 0 && $context['current_pm'] == $message['id'] ? '<img src="' . $settings['images_url'] . '/selected.gif" alt="*" />' : ''), '<a href="', ($context['display_mode'] == 0 || $context['current_pm'] == $message['id'] ? '' : ($scripturl . '?action=pm;pmid=' . $message['id'] . ';kstart;f=' . $context['folder'] . ';start=' . $context['start'] . ';sort=' . $context['sort_by'] . ($context['sort_direction'] == 'up' ? ';' : ';desc') . ($context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : ''))), '#msg', $message['id'], '">', $message['subject'], '</a>', $message['is_unread'] ? '&nbsp;<img src="' . $settings['lang_images_url'] . '/new.gif" alt="' . $txt['new'] . '" />' : '', '</td>
        <td>', ($context['from_or_to'] == 'from' ? $message['member']['link'] : (empty($message['recipients']['to']) ? '' : implode(', ', $message['recipients']['to']))), '</td>
        <td align="center"><input type="checkbox" name="pms[]" id="deletelisting', $message['id'], '" value="', $message['id'], '"', $message['is_selected'] ? ' checked="checked"' : '', ' onclick="if (document.getElementById(\'deletedisplay', $message['id'], '\')) document.getElementById(\'deletedisplay', $message['id'], '\').checked = this.checked;" class="input_check" /></td>
      </tr>';
      $next_alternate = !$next_alternate;
  }

  echo '
    </table>
    <div class="level mt-2 mb-2">
      <div class="level-left">
        <span clsas="is-muted is-size-6-5 is-uppercase">', $txt['pages'], ': ', $context['page_index'], '</span>
      </div>
      <div class="level-right">';

  if ($context['show_delete'])
  {
    if (!empty($context['currently_using_labels']) && $context['folder'] != 'sent')
    {
      echo '
      <div class="select mr-3">
        <select name="pm_action" onchange="if (this.options[this.selectedIndex].value) this.form.submit();" onfocus="loadLabelChoices();">
          <option value="">', $txt['pm_sel_label_title'], ':</option>
          <option value="" disabled="disabled">---------------</option>';

      echo '
          <option value="" disabled="disabled">', $txt['pm_msg_label_apply'], ':</option>';
      foreach ($context['labels'] as $label)
        if ($label['id'] != $context['current_label_id'])
          echo '
          <option value="add_', $label['id'], '">&nbsp;', $label['name'], '</option>';
      echo '
          <option value="" disabled="disabled">', $txt['pm_msg_label_remove'], ':</option>';
      foreach ($context['labels'] as $label)
        echo '
          <option value="rem_', $label['id'], '">&nbsp;', $label['name'], '</option>';
      echo '
        </select>
        </div>
        <noscript>
          <input type="submit" value="', $txt['pm_apply'], '" class="button is-primary" />
        </noscript>';
    }

    echo '
        <input type="submit" name="del_selected" value="', $txt['quickmod_delete_selected'], '" onclick="if (!confirm(\'', $txt['delete_selected_confirm'], '\')) return false;" class="button" />';
  }

  echo '
      </div>
    </div>';
}

function template_search()
{
  global $context, $settings, $options, $scripturl, $modSettings, $txt;

  echo '
  <script type="text/javascript"><!-- // --><![CDATA[
    function expandCollapseLabels()
    {
      var current = document.getElementById("searchLabelsExpand").style.display != "none";

      document.getElementById("searchLabelsExpand").style.display = current ? "none" : "";
      document.getElementById("expandLabelsIcon").src = smf_images_url + (current ? "/expand.gif" : "/collapse.gif");
    }
  // ]]></script>
  <form action="', $scripturl, '?action=pm;sa=search2" method="post" accept-charset="', $context['character_set'], '" name="searchform" id="searchform">
    <div class="cat_bar">
      <h3 class="catbg">', $txt['pm_search_title'], '</h3>
    </div>';

  if (!empty($context['search_errors']))
  {
    echo '
    <div class="errorbox">
      ', implode('<br />', $context['search_errors']['messages']), '
    </div>';
  }

  if ($context['simple_search'])
  {
    echo '
    <fieldset id="simple_search">
      <span class="upperframe"><span></span></span>
      <div class="roundframe">
        <div id="search_term_input">
          <strong>', $txt['pm_search_text'], ':</strong>
          <input type="text" name="search"', !empty($context['search_params']['search']) ? ' value="' . $context['search_params']['search'] . '"' : '', ' size="40" class="input" />
          <input type="submit" name="submit" value="', $txt['pm_search_go'], '" class="button is-small" />
        </div>
        <a href="', $scripturl, '?action=pm;sa=search;advanced" onclick="this.href += \';search=\' + escape(document.forms.searchform.search.value);">', $txt['pm_search_advanced'], '</a>
        <input type="hidden" name="advanced" value="0" />
      </div>
      <span class="lowerframe"><span></span></span>
    </fieldset>';
  }

  // Advanced search!
  else
  {
    echo '
    <fieldset id="advanced_search">
      <span class="upperframe"><span></span></span>
      <div class="roundframe">
        <input type="hidden" name="advanced" value="1" />
        <span class="enhanced">
          <strong>', $txt['pm_search_text'], ':</strong>
          <input type="text" name="search"', !empty($context['search_params']['search']) ? ' value="' . $context['search_params']['search'] . '"' : '', ' size="40" class="input" />
          <script type="text/javascript"><!-- // --><![CDATA[
            function initSearch()
            {
              if (document.forms.searchform.search.value.indexOf("%u") != -1)
                document.forms.searchform.search.value = unescape(document.forms.searchform.search.value);
            }
            createEventListener(window);
            window.addEventListener("load", initSearch, false);
          // ]]></script>
          <select name="searchtype">
            <option value="1"', empty($context['search_params']['searchtype']) ? ' selected="selected"' : '', '>', $txt['pm_search_match_all'], '</option>
            <option value="2"', !empty($context['search_params']['searchtype']) ? ' selected="selected"' : '', '>', $txt['pm_search_match_any'], '</option>
          </select>
        </span>
        <dl id="search_options">
          <dt>', $txt['pm_search_user'], ':</dt>
          <dd><input type="text" name="userspec" value="', empty($context['search_params']['userspec']) ? '*' : $context['search_params']['userspec'], '" size="40" class="input" /></dd>
          <dt>', $txt['pm_search_order'], ':</dt>
          <dd>
            <select name="sort">
              <option value="relevance|desc">', $txt['pm_search_orderby_relevant_first'], '</option>
              <option value="id_pm|desc">', $txt['pm_search_orderby_recent_first'], '</option>
              <option value="id_pm|asc">', $txt['pm_search_orderby_old_first'], '</option>
            </select>
          </dd>
          <dt class="options">', $txt['pm_search_options'], ':</dt>
          <dd class="options">
            <label for="show_complete"><input type="checkbox" name="show_complete" id="show_complete" value="1"', !empty($context['search_params']['show_complete']) ? ' checked="checked"' : '', ' class="input_check" /> ', $txt['pm_search_show_complete'], '</label><br />
            <label for="subject_only"><input type="checkbox" name="subject_only" id="subject_only" value="1"', !empty($context['search_params']['subject_only']) ? ' checked="checked"' : '', ' class="input_check" /> ', $txt['pm_search_subject_only'], '</label>
          </dd>
          <dt class="between">', $txt['pm_search_post_age'], ':</dt>
          <dd>', $txt['pm_search_between'], ' <input type="text" name="minage" value="', empty($context['search_params']['minage']) ? '0' : $context['search_params']['minage'], '" size="5" maxlength="5" class="input" />&nbsp;', $txt['pm_search_between_and'], '&nbsp;<input type="text" name="maxage" value="', empty($context['search_params']['maxage']) ? '9999' : $context['search_params']['maxage'], '" size="5" maxlength="5" class="input" /> ', $txt['pm_search_between_days'], '</dd>
        </dl>
      </div>
      <span class="lowerframe"><span></span></span>
    </fieldset>';

    // Do we have some labels setup? If so offer to search by them!
    if ($context['currently_using_labels'])
    {
      echo '
    <fieldset class="labels">
      <span class="upperframe"><span></span></span>
      <div class="roundframe">
        <div class="title_bar">
          <h4 class="titlebg">
            <span class="ie6_header floatleft"><a href="javascript:void(0);" onclick="expandCollapseLabels(); return false;"><img src="', $settings['images_url'], '/expand.gif" id="expandLabelsIcon" alt="" /></a> <a href="javascript:void(0);" onclick="expandCollapseLabels(); return false;"><strong>', $txt['pm_search_choose_label'], '</strong></a></span>
          </h4>
        </div>
        <ul id="searchLabelsExpand" class="reset" ', $context['check_all'] ? 'style="display: none;"' : '', '>';

      foreach ($context['search_labels'] as $label)
        echo '
          <li>
            <label for="searchlabel_', $label['id'], '"><input type="checkbox" id="searchlabel_', $label['id'], '" name="searchlabel[', $label['id'], ']" value="', $label['id'], '" ', $label['checked'] ? 'checked="checked"' : '', ' class="input_check" />
            ', $label['name'], '</label>
          </li>';

      echo '
        </ul>
        <p>
          <input type="checkbox" name="all" id="check_all" value="" ', $context['check_all'] ? 'checked="checked"' : '', ' onclick="invertAll(this, this.form, \'searchlabel\');" class="input_check" /><em> <label for="check_all">', $txt['check_all'], '</label></em>
        </p>
      </div>
      <span class="lowerframe"><span></span></span>
    </fieldset>';
    }

    echo '
    <div class="righttext padding">
      <input type="submit" name="submit" value="', $txt['pm_search_go'], '" class="button is-small" />
    </div>';
  }

  echo '
    </table>
  </form>';
}

function template_search_results()
{
  global $context, $settings, $options, $scripturl, $modSettings, $txt;

  // This splits broadly into two types of template... complete results first.
  if (!empty($context['search_params']['show_complete']))
  {
    echo '
    <table class="table is-bordered is-striped">
      <tr class="titlebg">
        <td colspan="3">', $txt['pm_search_results'], '</td>
      </tr>
      <tr class="catbg" height="30">
        <td colspan="3"><strong>', $txt['pages'], ':</strong> ', $context['page_index'], '</td>
      </tr>
    </table>';
  }
  else
  {
    echo '
    <table class="table is-bordered is-striped">
      <tr class="titlebg">
        <td colspan="3">', $txt['pm_search_results'], '</td>
      </tr>
      <tr class="catbg">
        <td colspan="3"><strong>', $txt['pages'], ':</strong> ', $context['page_index'], '</td>
      </tr>
      <tr class="titlebg">
        <td width="30%">', $txt['date'], '</td>
        <td width="50%">', $txt['subject'], '</td>
        <td width="20%">', $txt['from'], '</td>
      </tr>';
  }

  $alternate = true;
  // Print each message out...
  foreach ($context['personal_messages'] as $message)
  {
    // We showing it all?
    if (!empty($context['search_params']['show_complete']))
    {
      // !!! This still needs to be made pretty.
      echo '
    <br />
    <table class="table is-bordered is-striped">
      <tr class="titlebg">
        <td align="left">
          <div class="floatleft">
          ', $message['counter'], '&nbsp;&nbsp;<a href="', $message['href'], '">', $message['subject'], '</a>
          </div>
          <div class="floatright">
            ', $txt['search_on'], ': ', $message['time'], '
          </div>
        </td>
      </tr>
      <tr class="catbg">
        <td>', $txt['from'], ': ', $message['member']['link'], ', ', $txt['to'], ': ';

      // Show the recipients.
      // !!! This doesn't deal with the sent item searching quite right for bcc.
      if (!empty($message['recipients']['to']))
        echo implode(', ', $message['recipients']['to']);
      // Otherwise, we're just going to say "some people"...
      elseif ($context['folder'] != 'sent')
        echo '(', $txt['pm_undisclosed_recipients'], ')';

      echo '
        </td>
      </tr>
      <tr class="windowbg2" valign="top">
        <td>', $message['body'], '</td>
      </tr>
      <tr class="windowbg">
        <td align="right" class="middletext">';

      if ($context['can_send_pm'])
      {
        $quote_button = create_button('quote.gif', 'reply_quote', 'reply_quote', 'align="middle"');
        $reply_button = create_button('im_reply.gif', 'reply', 'reply', 'align="middle"');

        // You can only reply if they are not a guest...
        if (!$message['member']['is_guest'])
          echo '
              <a href="', $scripturl, '?action=pm;sa=send;f=', $context['folder'], $context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : '', ';pmsg=', $message['id'], ';quote;u=', $context['folder'] == 'sent' ? '' : $message['member']['id'], '">', $quote_button , '</a>', $context['menu_separator'], '
              <a href="', $scripturl, '?action=pm;sa=send;f=', $context['folder'], $context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : '', ';pmsg=', $message['id'], ';u=', $message['member']['id'], '">', $reply_button , '</a> ', $context['menu_separator'];
        // This is for "forwarding" - even if the member is gone.
        else
          echo '
              <a href="', $scripturl, '?action=pm;sa=send;f=', $context['folder'], $context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : '', ';pmsg=', $message['id'], ';quote">', $quote_button , '</a>', $context['menu_separator'];
      }

      echo '
        </td>
      </tr>
    </table>';
    }
    // Otherwise just a simple list!
    else
    {
      // !!! No context at all of the search?
      echo '
      <tr class="', $alternate ? 'windowbg' : 'windowbg2', '" valign="top">
        <td>', $message['time'], '</td>
        <td>', $message['link'], '</td>
        <td>', $message['member']['link'], '</td>
      </tr>';
    }

    $alternate = !$alternate;
  }

  // Finish off the page...
  if (!empty($context['search_params']['show_complete']))
  {
    // No results?
    if (empty($context['personal_messages']))
      echo '
    <table class="table is-bordered is-striped">
      <tr class="windowbg">
        <td align="center">', $txt['pm_search_none_found'], '</td>
      </tr>
    </table>';
    else
      echo '
    <br />';

    echo '
    <table class="table is-bordered is-striped">
      <tr class="catbg" height="30">
        <td colspan="3"><strong>', $txt['pages'], ':</strong> ', $context['page_index'], '</td>
      </tr>
    </table>';
  }
  else
  {
    if (empty($context['personal_messages']))
      echo '
      <tr class="windowbg2">
        <td colspan="3" align="center">', $txt['pm_search_none_found'], '</td>
      </tr>';

    echo '
      <tr class="catbg">
        <td colspan="3"><strong>', $txt['pages'], ':</strong> ', $context['page_index'], '</td>
      </tr>
    </table>';
  }
}

function template_send()
{
  global $context, $settings, $options, $scripturl, $modSettings, $txt;

  // Show which messages were sent successfully and which failed.
  if (!empty($context['send_log']))
  {
    echo '
      <div class="cat_bar">
       <h3 class="title is-5 mb-4">', $txt['pm_send_report'], '</h3>
      </div>
      <div class="windowbg">
        <div class="content">
          <div class="message is-info">
            <div class="message-body">';
              if (!empty($context['send_log']['sent']))
                foreach ($context['send_log']['sent'] as $log_entry)
                  echo '<span class="error">', $log_entry, '</span><br />';
              if (!empty($context['send_log']['failed']))
                foreach ($context['send_log']['failed'] as $log_entry)
                  echo '<span class="error">', $log_entry, '</span><br />';
            echo '
            </div>
          </div>
        </div>
      </div>';
  }

  // Show the preview of the personal message. TODO
  if (isset($context['preview_message']))


    echo'
      <div class="cat_bar">
        <h2 class="title is-5 mb-1">', $context['preview_subject'], '</h2>
      </div>
    </div>
    <div>
      <div id="preview_body" class="post content p-3">
        ', $context['preview_message'], '
      </div>
    </div>';

  // Main message editing box.
  echo '
    <div class="cat_bar">
      <h3 class="title is-5 mb-4">', $txt['new_message'], '</h3>
    </div>';

  echo '
  <form action="', $scripturl, '?action=pm;sa=send2" method="post" accept-charset="', $context['character_set'], '" name="postmodify" id="postmodify" onsubmit="submitonce(this);smc_saveEntities(\'postmodify\', [\'subject\', \'message\']);">
    <div>';

  // If there were errors for sending the PM, show them.
  if (!empty($context['post_error']['messages']))
  {
    echo '
      <div class="notification is-danger">
        <strong>', $txt['error_while_submitting'], '</strong>
        <ul>';

    foreach ($context['post_error']['messages'] as $error)
      echo '
        <li class="error">', $error, '</li>';

    echo '
        </ul>
      </div>';
  }

  // To and bcc. Include a button to search for members.
  echo '
    <div class="field is-horizontal">
      <div class="field-label has-text-left is-narrow">
        <label class="label', (isset($context['post_error']['no_to']) || isset($context['post_error']['bad_to']) ? ' class="has-text-danger"' : '') ,'">', $txt['pm_to'], '</label>
      </div>
      <div class="field-body">
        <div class="field is-narrow" id="pm_to">
          <input type="text" name="to" id="to_control" value="', $context['to_value'], '" tabindex="', $context['tabindex']++, '" size="40" class="input" /><span class="smalltext" id="bcc_link_container" style="display: none;"></span>';
          // A div that'll contain the items found by the autosuggest.
          echo' <div id="to_item_list_container"></div>';
          echo'
        </div>
        <div class="field">';
         // A link to add BCC, only visible with JavaScript enabled.
        echo '<span class="smalltext" id="bcc_link_container" style="display: none;"></span>';
        echo'
        </div>
      </div>
    </div>';

  // This BCC row will be hidden by default if JavaScript is enabled.
  echo '
    <div class="field is-horizontal" id="bcc_div">
      <div class="field-label has-text-left is-narrow">
        <span', (isset($context['post_error']['no_to']) || isset($context['post_error']['bad_bcc']) ? ' class="error"' : ''), '>', $txt['pm_bcc'], ':</span>
      </div>
      <div class="field-body">
        <div class="field is-narrow" id="bcc_div2">
          <input type="text" name="bcc" id="bcc_control" value="', $context['bcc_value'], '" tabindex="', $context['tabindex']++, '" size="40" class="input" />
        </div>
        <div class="field">
          <div id="bcc_item_list_container"></div>
        </div>
      </div>
    </div>';

  // The subject of the PM.
  echo '
    <div class="field is-horizontal">
      <div class="field-label has-text-left is-narrow">
         <label class="label', (isset($context['post_error']['no_subject']) ? ' class="has-text-danger"' : '') ,'">', $txt['subject'], '</label>
      </div>
      <div class="field-body">
        <div class="field is-narrow">
          <input class="input" type="text" name="subject" value="', $context['subject'], '" tabindex="', $context['tabindex']++, '" size="40" maxlength="50" />
          </div>
      </div>
    </div>
    <hr>
';

  // Show the actual posting area...
  if ($context['show_bbc'] || !empty($context['smileys']['postform'])) {
    echo '<div class="notification">';
  
    if ($context['show_bbc'])
    {
      echo '
        <div id="bbcBox_message"></div>';
    }

    // @TODO What about smileys?
    if (!empty($context['smileys']['postform']) || !empty($context['smileys']['popup']))
      echo '
        <div id="smileyBox_message"></div>';
      echo '</div>';
    }

    echo '<div class="type-your-post">', template_control_richedit($context['post_box_name'], 'smileyBox_message', 'bbcBox_message') , '</div>';

  // Require an image to be typed to save spamming?
  if ($context['require_verification'])
  {
    echo '
    <div class="post_verification">
      <strong>', $txt['pm_visual_verification_label'], ':</strong>
      ', template_control_verification($context['visual_verification_id'], 'all'), '
    </div>';
  }

  // Send, Preview, spellcheck buttons.
  echo '
    <div class="field mt-4">
      <label class="checkbox" for="outbox">
        <input type="checkbox" name="outbox" id="outbox" value="1" tabindex="', $context['tabindex']++, '"', $context['copy_to_outbox'] ? ' checked="checked"' : '', ' class="input_check" />
        ', $txt['pm_save_outbox'], '
      </label>
    </div>

    <p id="shortcuts" class="is-muted mt-2 mb-2">', $context['browser']['is_firefox'] ? $txt['shortcuts_firefox'] : $txt['shortcuts'], ' </p>
    
    <p id="post_confirm_strip" class="righttext">', template_control_richedit_buttons($context['post_box_name']), ' </p>
    
    <input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
    <input type="hidden" name="seqnum" value="', $context['form_sequence_number'], '" />
    <input type="hidden" name="replied_to" value="', !empty($context['quoted_message']['id']) ? $context['quoted_message']['id'] : 0, '" />
    <input type="hidden" name="pm_head" value="', !empty($context['quoted_message']['pm_head']) ? $context['quoted_message']['pm_head'] : 0, '" />
    <input type="hidden" name="f" value="', isset($context['folder']) ? $context['folder'] : '', '" />
    <input type="hidden" name="l" value="', isset($context['current_label_id']) ? $context['current_label_id'] : -1, '" />

    </div>
  </form>';

  // Show the message you're replying to.
  if ($context['reply'])
    echo '

    <div class="box mt-4">
      <h3>
        <span class="sr-only">', $txt['subject'], ' </span>', $context['quoted_message']['subject'], '
        <br>
        <span class="is-size-7">', $txt['from'], ': ', $context['quoted_message']['member']['name'], ' | ', $txt['on'], ': ', $context['quoted_message']['time'], '</span>
      </h3>
      <hr>
      <div class="content">
      ', $context['quoted_message']['body'], '
      </div>
    </div>';

  echo '
    <script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/PersonalMessage.js?fin20"></script>
    <script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/suggest.js?fin20"></script>
    <script type="text/javascript"><!-- // --><![CDATA[
      var oPersonalMessageSend = new smf_PersonalMessageSend({
        sSelf: \'oPersonalMessageSend\',
        sSessionId: \'', $context['session_id'], '\',
        sSessionVar: \'', $context['session_var'], '\',
        sTextDeleteItem: \'', $txt['autosuggest_delete_item'], '\',
        sToControlId: \'to_control\',
        aToRecipients: [';
  foreach ($context['recipients']['to'] as $i => $member)
    echo '
          {
            sItemId: ', JavaScriptEscape($member['id']), ',
            sItemName: ', JavaScriptEscape($member['name']), '
          }', $i == count($context['recipients']['to']) - 1 ? '' : ',';

  echo '
        ],
        aBccRecipients: [';
  foreach ($context['recipients']['bcc'] as $i => $member)
    echo '
          {
            sItemId: ', JavaScriptEscape($member['id']), ',
            sItemName: ', JavaScriptEscape($member['name']), '
          }', $i == count($context['recipients']['bcc']) - 1 ? '' : ',';

  echo '
        ],
        sBccControlId: \'bcc_control\',
        sBccDivId: \'bcc_div\',
        sBccDivId2: \'bcc_div2\',
        sBccLinkId: \'bcc_link\',
        sBccLinkContainerId: \'bcc_link_container\',
        bBccShowByDefault: ', empty($context['recipients']['bcc']) && empty($context['bcc_value']) ? 'false' : 'true', ',
        sShowBccLinkTemplate: ', JavaScriptEscape('
          <a href="#" id="bcc_link">' . $txt['make_bcc'] . '</a> <a href="' . $scripturl . '?action=helpadmin;help=pm_bcc" onclick="return reqWin(this.href);">(?)</a>'
        ), '
      });
    ';

  echo '
    // ]]></script>';
}

// This template asks the user whether they wish to empty out their folder/messages.
function template_ask_delete()
{
  global $context, $settings, $options, $scripturl, $modSettings, $txt;

  echo '
    <div class="cat_bar">
      <h3 class="catbg">', ($context['delete_all'] ? $txt['delete_message'] : $txt['delete_all']), '</h3>
    </div>
    <div class="windowbg">
    <span class="topslice"><span></span></span>
      <div class="content">
        <p>', $txt['delete_all_confirm'], '</p><br />
        <strong><a href="', $scripturl, '?action=pm;sa=removeall2;f=', $context['folder'], ';', $context['current_label_id'] != -1 ? ';l=' . $context['current_label_id'] : '', ';', $context['session_var'], '=', $context['session_id'], '">', $txt['yes'], '</a> - <a href="javascript:history.go(-1);">', $txt['no'], '</a></strong>
      </div>
    <span class="botslice"><span></span></span>
    </div>';
}

// This template asks the user what messages they want to prune.
function template_prune()
{
  global $context, $settings, $options, $scripturl, $txt;

  echo '
  <form action="', $scripturl, '?action=pm;sa=prune" method="post" accept-charset="', $context['character_set'], '" onsubmit="return confirm(\'', $txt['pm_prune_warning'], '\');">
    <div class="cat_bar">
      <h3 class="catbg">', $txt['pm_prune'], '</h3>
    </div>
    <div class="windowbg">
      <span class="topslice"><span></span></span>
      <div class="content">
        <p>', $txt['pm_prune_desc1'], ' <input type="text" name="age" size="3" value="14" class="input" /> ', $txt['pm_prune_desc2'], '</p>
        <div class="righttext">
          <input type="submit" value="', $txt['delete'], '" class="button is-small" />
        </div>
      </div>
      <span class="botslice"><span></span></span>
    </div>
    <input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
  </form>';
}

// Here we allow the user to setup labels, remove labels and change rules for labels (i.e, do quite a bit)
function template_labels()
{
  global $context, $settings, $options, $scripturl, $txt;

  echo '
  <form action="', $scripturl, '?action=pm;sa=manlabels" method="post" accept-charset="', $context['character_set'], '">
    <div class="title_bar">
      <h3 class="titlebg">', $txt['pm_manage_labels'], '</h3>
    </div>
    <div class="description">
      ', $txt['pm_labels_desc'], '
    </div>
    <table class="table is-bordered is-striped">
    <thead>
      <tr class="catbg">
        <th class="smalltext" colspan="2">
          <div class="floatright centertext" style="width: 4%;"><input type="checkbox" class="input_check" onclick="invertAll(this, this.form);" /></div>
          ', $txt['pm_label_name'], '
        </th>
      </tr>
    </thead>
    <tbody>';
  if (count($context['labels']) < 2)
    echo '
      <tr class="windowbg2">
        <td colspan="2" align="center">', $txt['pm_labels_no_exist'], '</td>
      </tr>';
  else
  {
    $alternate = true;
    foreach ($context['labels'] as $label)
    {
      if ($label['id'] == -1)
        continue;

        echo '
      <tr class="', $alternate ? 'windowbg2' : 'windowbg', '">
        <td>
          <input type="text" name="label_name[', $label['id'], ']" value="', $label['name'], '" size="30" maxlength="30" class="input" />
        </td>
        <td align="center"><input type="checkbox" class="input_check" name="delete_label[', $label['id'], ']" /></td>
      </tr>';

      $alternate = !$alternate;
    }
  }
  echo '
    </tbody>
    </table>';

  if (!count($context['labels']) < 2)
    echo '
    <div class="padding righttext">
      <input type="submit" name="save" value="', $txt['save'], '" class="button is-small" />
      <input type="submit" name="delete" value="', $txt['quickmod_delete_selected'], '" onclick="return confirm(\'', $txt['pm_labels_delete'], '\');" class="button is-small" />
    </div>';

  echo '
    <input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
  </form>
  <form action="', $scripturl, '?action=pm;sa=manlabels" method="post" accept-charset="', $context['character_set'], '" style="margin-top: 1ex;">
    <div class="cat_bar">
      <h3 class="catbg">', $txt['pm_label_add_new'], '</h3>
    </div>
    <div class="windowbg">
      <span class="topslice"><span></span></span>
      <div class="content">
        <dl class="settings">
          <dt>
            <strong><label for="add_label">', $txt['pm_label_name'], '</label>:</strong>
          </dt>
          <dd>
            <input type="text" id="add_label" name="label" value="" size="30" maxlength="30" class="input" />
          </dd>
        </dl>
        <div class="righttext">
          <input type="submit" name="add" value="', $txt['pm_label_add_new'], '" class="button is-small" />
        </div>
      </div>
      <span class="botslice"><span></span></span>
    </div>
    <input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
  </form>';
}

// Template for reporting a personal message.
function template_report_message()
{
  global $context, $settings, $options, $txt, $scripturl;

  echo '
  <form action="', $scripturl, '?action=pm;sa=report;l=', $context['current_label_id'], '" method="post" accept-charset="', $context['character_set'], '">
    <input type="hidden" name="pmsg" value="', $context['pm_id'], '" />
    <div class="cat_bar">
      <h3 class="catbg">', $txt['pm_report_title'], '</h3>
    </div>
    <div class="description">
      ', $txt['pm_report_desc'], '
    </div>
    <div class="windowbg">
      <span class="topslice"><span></span></span>
      <div class="content">
        <dl class="settings">';

  // If there is more than one admin on the forum, allow the user to choose the one they want to direct to.
  // !!! Why?
  if ($context['admin_count'] > 1)
  {
    echo '
          <dt>
            <strong>', $txt['pm_report_admins'], ':</strong>
          </dt>
          <dd>
            <select name="ID_ADMIN">
              <option value="0">', $txt['pm_report_all_admins'], '</option>';
    foreach ($context['admins'] as $id => $name)
      echo '
              <option value="', $id, '">', $name, '</option>';
    echo '
            </select>
          </dd>';
  }

  echo '
          <dt>
            <strong>', $txt['pm_report_reason'], ':</strong>
          </dt>
          <dd>
            <textarea name="reason" rows="4" cols="70" style="' . ($context['browser']['is_ie8'] ? 'width: 635px; max-width: 80%; min-width: 80%' : 'width: 80%') . ';"></textarea>
          </dd>
        </dl>
        <input type="submit" name="report" value="', $txt['pm_report_message'], '" class="button is-small" />
      </div>
      <span class="botslice"><span></span></span>
    </div>
    <input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
  </form>';
}

// Little template just to say "Yep, it's been submitted"
function template_report_message_complete()
{
  global $context, $settings, $options, $txt, $scripturl;

  echo '
    <div class="cat_bar">
      <h3 class="catbg">', $txt['pm_report_title'], '</h3>
    </div>
    <div class="windowbg">
      <span class="topslice"><span></span></span>
      <div class="content">
        <p>', $txt['pm_report_done'], '</p>
        <a href="', $scripturl, '?action=pm;l=', $context['current_label_id'], '">', $txt['pm_report_return'], '</a>
      </div>
      <span class="botslice"><span></span></span>
    </div>';
}

// Manage rules.
function template_rules()
{
  global $context, $settings, $options, $txt, $scripturl;

  echo '
  <form action="', $scripturl, '?action=pm;sa=manrules" method="post" accept-charset="', $context['character_set'], '" name="manRules">
    <div class="title_bar">
      <h3 class="titlebg">', $txt['pm_manage_rules'], '</h3>
    </div>
    <div class="description">
      ', $txt['pm_manage_rules_desc'], '
    </div>
    <table class="table is-bordered is-striped">
    <thead>
      <tr class="catbg">
        <th class="smalltext">
          ', $txt['pm_rule_title'], '
        </th>
        <th align="center">';

  if (!empty($context['rules']))
    echo '
          <input type="checkbox" onclick="invertAll(this, this.form);" class="input_check" />';

  echo '
        </th>
      </tr>
    </thead>
    <tbody>';

  if (empty($context['rules']))
    echo '
      <tr class="windowbg2">
        <td colspan="2" align="center">
          ', $txt['pm_rules_none'], '
        </td>
      </tr>';

  $alternate = false;
  foreach ($context['rules'] as $rule)
  {
    echo '
      <tr class="', $alternate ? 'windowbg' : 'windowbg2', '">
        <td>
          <a href="', $scripturl, '?action=pm;sa=manrules;add;rid=', $rule['id'], '">', $rule['name'], '</a>
        </td>
        <td align="center">
          <input type="checkbox" name="delrule[', $rule['id'], ']" class="input_check" />
        </td>
      </tr>';
    $alternate = !$alternate;
  }

  echo '
    </tbody>
    </table>
    <div class="righttext">
      [<a href="', $scripturl, '?action=pm;sa=manrules;add;rid=0">', $txt['pm_add_rule'], '</a>]';

  if (!empty($context['rules']))
    echo '
      [<a href="', $scripturl, '?action=pm;sa=manrules;apply;', $context['session_var'], '=', $context['session_id'], '" onclick="return confirm(\'', $txt['pm_js_apply_rules_confirm'], '\');">', $txt['pm_apply_rules'], '</a>]';

  if (!empty($context['rules']))
    echo '
      <input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
      <input type="submit" name="delselected" value="', $txt['pm_delete_selected_rule'], '" onclick="return confirm(\'', $txt['pm_js_delete_rule_confirm'], '\');" class="button is-small" />';

  echo '
      </div>
  </form>';

}

// Template for adding/editing a rule.
function template_add_rule()
{
  global $context, $settings, $options, $txt, $scripturl;

  echo '
  <script type="text/javascript"><!-- // --><![CDATA[
      var criteriaNum = 0;
      var actionNum = 0;
      var groups = new Array()
      var labels = new Array()';

  foreach ($context['groups'] as $id => $title)
    echo '
      groups[', $id, '] = "', addslashes($title), '";';

  foreach ($context['labels'] as $label)
    if ($label['id'] != -1)
      echo '
      labels[', ($label['id'] + 1), '] = "', addslashes($label['name']), '";';

  echo '
      function addCriteriaOption()
      {
        if (criteriaNum == 0)
        {
          for (var i = 0; i < document.forms.addrule.elements.length; i++)
            if (document.forms.addrule.elements[i].id.substr(0, 8) == "ruletype")
              criteriaNum++;
        }
        criteriaNum++

        setOuterHTML(document.getElementById("criteriaAddHere"), \'<br /><select name="ruletype[\' + criteriaNum + \']" id="ruletype\' + criteriaNum + \'" onchange="updateRuleDef(\' + criteriaNum + \'); rebuildRuleDesc();"><option value="">', addslashes($txt['pm_rule_criteria_pick']), ':<\' + \'/option><option value="mid">', addslashes($txt['pm_rule_mid']), '<\' + \'/option><option value="gid">', addslashes($txt['pm_rule_gid']), '<\' + \'/option><option value="sub">', addslashes($txt['pm_rule_sub']), '<\' + \'/option><option value="msg">', addslashes($txt['pm_rule_msg']), '<\' + \'/option><option value="bud">', addslashes($txt['pm_rule_bud']), '<\' + \'/option><\' + \'/select>&nbsp;<span id="defdiv\' + criteriaNum + \'" style="display: none;"><input type="text" name="ruledef[\' + criteriaNum + \']" id="ruledef\' + criteriaNum + \'" onkeyup="rebuildRuleDesc();" value="" class="input" /><\' + \'/span><span id="defseldiv\' + criteriaNum + \'" style="display: none;"><select name="ruledefgroup[\' + criteriaNum + \']" id="ruledefgroup\' + criteriaNum + \'" onchange="rebuildRuleDesc();"><option value="">', addslashes($txt['pm_rule_sel_group']), '<\' + \'/option>';

  foreach ($context['groups'] as $id => $group)
    echo '<option value="', $id, '">', strtr($group, array("'" => "\'")), '<\' + \'/option>';

  echo '<\' + \'/select><\' + \'/span><span id="criteriaAddHere"><\' + \'/span>\');
      }

      function addActionOption()
      {
        if (actionNum == 0)
        {
          for (var i = 0; i < document.forms.addrule.elements.length; i++)
            if (document.forms.addrule.elements[i].id.substr(0, 7) == "acttype")
              actionNum++;
        }
        actionNum++

        setOuterHTML(document.getElementById("actionAddHere"), \'<br /><select name="acttype[\' + actionNum + \']" id="acttype\' + actionNum + \'" onchange="updateActionDef(\' + actionNum + \'); rebuildRuleDesc();"><option value="">', addslashes($txt['pm_rule_sel_action']), ':<\' + \'/option><option value="lab">', addslashes($txt['pm_rule_label']), '<\' + \'/option><option value="del">', addslashes($txt['pm_rule_delete']), '<\' + \'/option><\' + \'/select>&nbsp;<span id="labdiv\' + actionNum + \'" style="display: none;"><select name="labdef[\' + actionNum + \']" id="labdef\' + actionNum + \'" onchange="rebuildRuleDesc();"><option value="">', addslashes($txt['pm_rule_sel_label']), '<\' + \'/option>';

  foreach ($context['labels'] as $label)
    if ($label['id'] != -1)
      echo '<option value="', ($label['id'] + 1), '">', addslashes($label['name']), '<\' + \'/option>';

  echo '<\' + \'/select><\' + \'/span><span id="actionAddHere"><\' + \'/span>\');
      }

      function updateRuleDef(optNum)
      {
        if (document.getElementById("ruletype" + optNum).value == "gid")
        {
          document.getElementById("defdiv" + optNum).style.display = "none";
          document.getElementById("defseldiv" + optNum).style.display = "";
        }
        else if (document.getElementById("ruletype" + optNum).value == "bud" || document.getElementById("ruletype" + optNum).value == "")
        {
          document.getElementById("defdiv" + optNum).style.display = "none";
          document.getElementById("defseldiv" + optNum).style.display = "none";
        }
        else
        {
          document.getElementById("defdiv" + optNum).style.display = "";
          document.getElementById("defseldiv" + optNum).style.display = "none";
        }
      }

      function updateActionDef(optNum)
      {
        if (document.getElementById("acttype" + optNum).value == "lab")
        {
          document.getElementById("labdiv" + optNum).style.display = "";
        }
        else
        {
          document.getElementById("labdiv" + optNum).style.display = "none";
        }
      }

      // Rebuild the rule description!
      function rebuildRuleDesc()
      {
        // Start with nothing.
        text = "";
        joinText = "";
        actionText = "";
        hadBuddy = false;
        foundCriteria = false;
        foundAction = false;

        for (var i = 0; i < document.forms.addrule.elements.length; i++)
        {
          if (document.forms.addrule.elements[i].id.substr(0, 8) == "ruletype")
          {
            if (foundCriteria)
              joinText = document.getElementById("logic").value == \'and\' ? ', JavaScriptEscape(' ' . $txt['pm_readable_and'] . ' '), ' : ', JavaScriptEscape(' ' . $txt['pm_readable_or'] . ' '), ';
            else
              joinText = \'\';
            foundCriteria = true;

            curNum = document.forms.addrule.elements[i].id.match(/\d+/);
            curVal = document.forms.addrule.elements[i].value;
            if (curVal == "gid")
              curDef = document.getElementById("ruledefgroup" + curNum).value.php_htmlspecialchars();
            else if (curVal != "bud")
              curDef = document.getElementById("ruledef" + curNum).value.php_htmlspecialchars();
            else
              curDef = "";

            // What type of test is this?
            if (curVal == "mid" && curDef)
              text += joinText + ', JavaScriptEscape($txt['pm_readable_member']), '.replace("{MEMBER}", curDef);
            else if (curVal == "gid" && curDef && groups[curDef])
              text += joinText + ', JavaScriptEscape($txt['pm_readable_group']), '.replace("{GROUP}", groups[curDef]);
            else if (curVal == "sub" && curDef)
              text += joinText + ', JavaScriptEscape($txt['pm_readable_subject']), '.replace("{SUBJECT}", curDef);
            else if (curVal == "msg" && curDef)
              text += joinText + ', JavaScriptEscape($txt['pm_readable_body']), '.replace("{BODY}", curDef);
            else if (curVal == "bud" && !hadBuddy)
            {
              text += joinText + ', JavaScriptEscape($txt['pm_readable_buddy']), ';
              hadBuddy = true;
            }
          }
          if (document.forms.addrule.elements[i].id.substr(0, 7) == "acttype")
          {
            if (foundAction)
              joinText = ', JavaScriptEscape(' ' . $txt['pm_readable_and'] . ' '), ';
            else
              joinText = "";
            foundAction = true;

            curNum = document.forms.addrule.elements[i].id.match(/\d+/);
            curVal = document.forms.addrule.elements[i].value;
            if (curVal == "lab")
              curDef = document.getElementById("labdef" + curNum).value.php_htmlspecialchars();
            else
              curDef = "";

            // Now pick the actions.
            if (curVal == "lab" && curDef && labels[curDef])
              actionText += joinText + ', JavaScriptEscape($txt['pm_readable_label']), '.replace("{LABEL}", labels[curDef]);
            else if (curVal == "del")
              actionText += joinText + ', JavaScriptEscape($txt['pm_readable_delete']), ';
          }
        }

        // If still nothing make it default!
        if (text == "" || !foundCriteria)
          text = "', $txt['pm_rule_not_defined'], '";
        else
        {
          if (actionText != "")
            text += ', JavaScriptEscape(' ' . $txt['pm_readable_then'] . ' '), ' + actionText;
          text = ', JavaScriptEscape($txt['pm_readable_start']), ' + text + ', JavaScriptEscape($txt['pm_readable_end']), ';
        }

        // Set the actual HTML!
        setInnerHTML(document.getElementById("ruletext"), text);
      }
  // ]]></script>';

  echo '
  <form action="', $scripturl, '?action=pm;sa=manrules;save;rid=', $context['rid'], '" method="post" accept-charset="', $context['character_set'], '" name="addrule" id="addrule">
    <div class="cat_bar">
      <h3 class="catbg">', $context['rid'] == 0 ? $txt['pm_add_rule'] : $txt['pm_edit_rule'], '</h3>
    </div>
    <div class="windowbg">
      <span class="topslice"><span></span></span>
      <div class="content">
        <dl class="settings">
          <dt>
            <strong>', $txt['pm_rule_name'], ':</strong><br />
            <span class="smalltext">', $txt['pm_rule_name_desc'], '</span>
          </dt>
          <dd>
            <input type="text" name="rule_name" value="', empty($context['rule']['name']) ? $txt['pm_rule_name_default'] : $context['rule']['name'], '" class="input" style="width: 100%" />
          </dd>
        </dl>
        <fieldset>
          <legend>', $txt['pm_rule_criteria'], '</legend>';

  // Add a dummy criteria to allow expansion for none js users.
  $context['rule']['criteria'][] = array('t' => '', 'v' => '');

  // For each criteria print it out.
  $isFirst = true;
  foreach ($context['rule']['criteria'] as $k => $criteria)
  {
    if (!$isFirst && $criteria['t'] == '')
      echo '<div id="removeonjs1">';
    else
      echo '<br />';

    echo '
          <select name="ruletype[', $k, ']" id="ruletype', $k, '" onchange="updateRuleDef(', $k, '); rebuildRuleDesc();">
            <option value="">', $txt['pm_rule_criteria_pick'], ':</option>
            <option value="mid" ', $criteria['t'] == 'mid' ? 'selected="selected"' : '', '>', $txt['pm_rule_mid'], '</option>
            <option value="gid" ', $criteria['t'] == 'gid' ? 'selected="selected"' : '', '>', $txt['pm_rule_gid'], '</option>
            <option value="sub" ', $criteria['t'] == 'sub' ? 'selected="selected"' : '', '>', $txt['pm_rule_sub'], '</option>
            <option value="msg" ', $criteria['t'] == 'msg' ? 'selected="selected"' : '', '>', $txt['pm_rule_msg'], '</option>
            <option value="bud" ', $criteria['t'] == 'bud' ? 'selected="selected"' : '', '>', $txt['pm_rule_bud'], '</option>
          </select>
          <span id="defdiv', $k, '" ', !in_array($criteria['t'], array('gid', 'bud')) ? '' : 'style="display: none;"', '>
            <input type="text" name="ruledef[', $k, ']" id="ruledef', $k, '" onkeyup="rebuildRuleDesc();" value="', in_array($criteria['t'], array('mid', 'sub', 'msg')) ? $criteria['v'] : '', '" class="input" />
          </span>
          <span id="defseldiv', $k, '" ', $criteria['t'] == 'gid' ? '' : 'style="display: none;"', '>
            <select name="ruledefgroup[', $k, ']" id="ruledefgroup', $k, '" onchange="rebuildRuleDesc();">
              <option value="">', $txt['pm_rule_sel_group'], '</option>';

    foreach ($context['groups'] as $id => $group)
      echo '
              <option value="', $id, '" ', $criteria['t'] == 'gid' && $criteria['v'] == $id ? 'selected="selected"' : '', '>', $group, '</option>';
    echo '
            </select>
          </span>';

    // If this is the dummy we add a means to hide for non js users.
    if ($isFirst)
      $isFirst = false;
    elseif ($criteria['t'] == '')
      echo '</div>';
  }

  echo '
          <span id="criteriaAddHere"></span><br />
          <a href="#" onclick="addCriteriaOption(); return false;" id="addonjs1" style="display: none;">(', $txt['pm_rule_criteria_add'], ')</a>
          <br /><br />
          ', $txt['pm_rule_logic'], ':
          <select name="rule_logic" id="logic" onchange="rebuildRuleDesc();">
            <option value="and" ', $context['rule']['logic'] == 'and' ? 'selected="selected"' : '', '>', $txt['pm_rule_logic_and'], '</option>
            <option value="or" ', $context['rule']['logic'] == 'or' ? 'selected="selected"' : '', '>', $txt['pm_rule_logic_or'], '</option>
          </select>
        </fieldset>
        <fieldset>
          <legend>', $txt['pm_rule_actions'], '</legend>';

  // As with criteria - add a dummy action for "expansion".
  $context['rule']['actions'][] = array('t' => '', 'v' => '');

  // Print each action.
  $isFirst = true;
  foreach ($context['rule']['actions'] as $k => $action)
  {
    if (!$isFirst && $action['t'] == '')
      echo '<div id="removeonjs2">';
    else
      echo '<br />';

    echo '
          <select name="acttype[', $k, ']" id="acttype', $k, '" onchange="updateActionDef(', $k, '); rebuildRuleDesc();">
            <option value="">', $txt['pm_rule_sel_action'] , ':</option>
            <option value="lab" ', $action['t'] == 'lab' ? 'selected="selected"' : '', '>', $txt['pm_rule_label'] , '</option>
            <option value="del" ', $action['t'] == 'del' ? 'selected="selected"' : '', '>', $txt['pm_rule_delete'] , '</option>
          </select>
          <span id="labdiv', $k, '">
            <select name="labdef[', $k, ']" id="labdef', $k, '" onchange="rebuildRuleDesc();">
              <option value="">', $txt['pm_rule_sel_label'], '</option>';
    foreach ($context['labels'] as $label)
      if ($label['id'] != -1)
        echo '
              <option value="', ($label['id'] + 1), '" ', $action['t'] == 'lab' && $action['v'] == $label['id'] ? 'selected="selected"' : '', '>', $label['name'], '</option>';

    echo '
            </select>
          </span>';

    if ($isFirst)
      $isFirst = false;
    elseif ($action['t'] == '')
      echo '
        </div>';
  }

  echo '
          <span id="actionAddHere"></span><br />
          <a href="#" onclick="addActionOption(); return false;" id="addonjs2" style="display: none;">(', $txt['pm_rule_add_action'], ')</a>
        </fieldset>
      </div>
      <span class="botslice"><span></span></span>
    </div>
    <div class="cat_bar">
      <h3 class="catbg">', $txt['pm_rule_description'], '</h3>
    </div>
    <div class="information">
      <div id="ruletext" class="smalltext">', $txt['pm_rule_js_disabled'], '</div>
    </div>
    <div class="righttext">
      <input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
      <input type="submit" name="save" value="', $txt['pm_rule_save'], '" class="button is-small" />
    </div>
  </form>';

  // Now setup all the bits!
    echo '
  <script type="text/javascript"><!-- // --><![CDATA[';

  foreach ($context['rule']['criteria'] as $k => $c)
    echo '
      updateRuleDef(', $k, ');';

  foreach ($context['rule']['actions'] as $k => $c)
    echo '
      updateActionDef(', $k, ');';

  echo '
      rebuildRuleDesc();';

  // If this isn't a new rule and we have JS enabled remove the JS compatibility stuff.
  if ($context['rid'])
    echo '
      document.getElementById("removeonjs1").style.display = "none";
      document.getElementById("removeonjs2").style.display = "none";';

  echo '
      document.getElementById("addonjs1").style.display = "";
      document.getElementById("addonjs2").style.display = "";';

  echo '
    // ]]></script>';
}

?>