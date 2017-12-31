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

// Form for editing current news on the site.
function template_edit_news()
{
  global $context, $settings, $options, $scripturl, $txt;

  echo '
  <form action="', $scripturl, '?action=admin;area=news;sa=editnews" method="post" accept-charset="', $context['character_set'], '" name="postmodify" id="postmodify">
    <table class="table is-narrow is-striped">
      <thead>
        <tr>
          <th width="50%">', $txt['admin_edit_news'], '</th>
          <th width="45%">', $txt['preview'], '</th>
          <th width="5%">
            <input type="checkbox" class="input_check" onclick="invertAll(this, this.form);" />
          </th>
        </tr>
      </thead>
    <tbody>';

  // Loop through all the current news items so you can edit/remove them.
  foreach ($context['admin_current_news'] as $admin_news)
    echo '
        <tr>
          <td>
            <textarea class="textarea is-small" rows="3" cols="65" name="news[]">', $admin_news['unparsed'], '</textarea>
          </td>
          <td>
            <div class="content">', $admin_news['parsed'], '</div>
          </td>
          <td>
            <input type="checkbox" name="remove[]" value="', $admin_news['id'], '" class="input_check" />
          </td>
        </tr>';

  // This provides an empty text box to add a news item to the site.
  echo '
        <tr id="moreNews" style="display: none;">
          <td>
            <div id="moreNewsItems"></div>
          </td>
          <td></td>
          <td></td>
        </tr>
      </tbody>
    </table>

    <div>
      <div id="moreNewsItems_link" style="display: none;">
        <a class="button is-small is-secondary" href="javascript:void(0);" onclick="addNewsItem(); return false;">', $txt['editnews_clickadd'], '</a>
      </div>

      <script type="text/javascript"><!-- // --><![CDATA[
        document.getElementById("moreNewsItems_link").style.display = "";
        function addNewsItem()
        {
          $("#moreNewsItems").append(\'<textarea class="textarea is-small" rows="3" cols="65" name="news[]"><\' + \'/textarea><\' + \'/div><div id="moreNewsItems"><\' + \'/div>\');

          document.getElementById("moreNews").style.display = "";
        }
      // ]]></script>

      <noscript>
        <textarea rows="3" cols="65" name="news[]"></textarea>
      </noscript>

      </div>
      <div class="field is-grouped mt-4">
        <div class="control">
          <input type="submit" name="save_items" value="', $txt['save'], '" class="button is-primary" />
        </div>
        <div class="control">
          <input type="submit" name="delete_selection" value="', $txt['editnews_remove_selected'], '" onclick="return confirm(\'', $txt['editnews_remove_confirm'], '\');" class="button is-secondary" />
        </div>
      </div>
      <input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
    </form>
';
}

function template_email_members()
{
  global $context, $settings, $options, $txt, $scripturl;

  // This is some javascript for the simple/advanced toggling stuff.
  echo '
  <script type="text/javascript"><!-- // --><![CDATA[
    function toggleAdvanced(mode)
    {
      // What styles are we doing?
      var divStyle = mode ? "" : "none";

      document.getElementById("advanced_settings_div").style.display = divStyle;
      document.getElementById("gosimple").style.display = divStyle;
      document.getElementById("goadvanced").style.display = mode ? "none" : "";
    }
  // ]]></script>';

  echo '
  <form action="', $scripturl, '?action=admin;area=news;sa=mailingcompose" method="post" accept-charset="', $context['character_set'], '">
    
    <h3 class="title is-5 mb-4">', $txt['admin_newsletters'], '</h3>

    <p class="mb-4">', $txt['admin_news_select_recipients'], '</p>

    <div class="field">
      <label class="label">', $txt['admin_news_select_group'], '</label>
      <p class="help mt-0 mb-2">', $txt['admin_news_select_group_desc'], '</p>
      
      ';

      foreach ($context['groups'] as $group)
      echo '
        <div class="control">
          <label class="checkbox" for="groups_', $group['id'], '">
            <input type="checkbox" name="groups[', $group['id'], ']" id="groups_', $group['id'], '" value="', $group['id'], '" checked="checked"/> ', $group['name'], ' <span class="tag is-secondary">', $group['member_count'], '</span>
          </label> 
        </div>';
      
      echo'
        <div class="control mt-4">
          <label class="control" for="checkAllGroups">
            <input type="checkbox" id="checkAllGroups" checked="checked" onclick="invertAll(this, this.form, \'groups\');" class="input_check" /> <em>', $txt['check_all'], '</em>
            </label>
        </div>

    </div>';

    echo '

    <hr>

    <h3 class="title is-5 mb-4" id="advanced_select_div" style="display: none;">
      <a href="#" onclick="toggleAdvanced(1); return false;" id="goadvanced">
        <span class="button is-secondary icon">
          <span class="fa fa-angle-right"></span>
        </span>
        <span>', $txt['advanced'], '</span>
      </a>
      <a href="#" onclick="toggleAdvanced(0); return false;" id="gosimple" style="display: none;">
        <span class="button is-secondary icon">
          <span class="fa fa-angle-down"></span>
        </span> 
        <span>', $txt['simple'], '</span>
      </a>
    </h3>

    <div id="advanced_settings_div" style="display: none;">

      <div class="field">
        <label class="label">', $txt['admin_news_select_email'], '</label>
        <div class="control">
          <textarea class="textarea is-small" name="emails" rows="5" cols="30"></textarea>
        </div>
        <p class="help">', $txt['admin_news_select_email_desc'], '</p>
      </div>

      <div class="field">
        <label class="label">', $txt['admin_news_select_members'], '</label>
        <p class="help mt-0 mb-2">', $txt['admin_news_select_members_desc'], '</p>
        <div class="control">
          <input type="text" name="members" id="members" value="" size="30" class="input is-auto" />
        </div>
        <div id="members_container"></div>
      </div>

      <hr>

      <div class="field">
        <label class="label">', $txt['admin_news_select_excluded_groups'], '</label>
        <p class="help mt-0 mb-2">', $txt['admin_news_select_excluded_groups_desc'], '</p>';

        foreach ($context['groups'] as $group)
        echo '
          <div class="control">
            <label class="checkbox" for="exclude_groups_', $group['id'], '">
              <input type="checkbox" name="exclude_groups[', $group['id'], ']" id="exclude_groups_', $group['id'], '" value="', $group['id'], '" class="input_check" /> ', $group['name'], ' <span class="tag is-secondary">', $group['member_count'], '</span>
            </label> 
          </div>';
        
        echo'
          <div class="control mt-4">
            <label class="control" for="checkAllGroupsExclude">
              <input type="checkbox" id="checkAllGroupsExclude" onclick="invertAll(this, this.form, \'exclude_groups\');" /> <em>', $txt['check_all'], '</em>
              </label>
          </div>
      </div>';

      echo'
      <div class="field">
        <label class="label">', $txt['admin_news_select_excluded_members'], '</label>
        <p class="help mt-0 mb-2">', $txt['admin_news_select_excluded_members_desc'], '</p>
        <div class="control">
          <input type="text" name="members" id="members" value="" size="30" class="input is-auto" />
        </div>
        <div id="exclude_members_container"></div>
      </div>

      <div class="field">
        <label class="label">', $txt['admin_news_select_override_notify'], '</label>
        <div class="control">
          <input type="checkbox" name="email_force" id="email_force" value="1" />
        </div>
        <p class="help">', $txt['email_force'], '</p>
      </div>

      <div>
        <input type="submit" value="', $txt['admin_next'], '" class="button is-primary" />
        <input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
      </div>

    </div>

  </form>
  ';

  // Make the javascript stuff visible.
  echo '
  <script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/suggest.js?fin20"></script>
  <script type="text/javascript"><!-- // --><![CDATA[
    document.getElementById("advanced_select_div").style.display = "";
    var oMemberSuggest = new smc_AutoSuggest({
      sSelf: \'oMemberSuggest\',
      sSessionId: \'', $context['session_id'], '\',
      sSessionVar: \'', $context['session_var'], '\',
      sSuggestId: \'members\',
      sControlId: \'members\',
      sSearchType: \'member\',
      bItemList: true,
      sPostName: \'member_list\',
      sURLMask: \'action=profile;u=%item_id%\',
      sTextDeleteItem: \'', $txt['autosuggest_delete_item'], '\',
      sItemListContainerId: \'members_container\',
      aListItems: []
    });
    var oExcludeMemberSuggest = new smc_AutoSuggest({
      sSelf: \'oExcludeMemberSuggest\',
      sSessionId: \'', $context['session_id'], '\',
      sSessionVar: \'', $context['session_var'], '\',
      sSuggestId: \'exclude_members\',
      sControlId: \'exclude_members\',
      sSearchType: \'member\',
      bItemList: true,
      sPostName: \'exclude_member_list\',
      sURLMask: \'action=profile;u=%item_id%\',
      sTextDeleteItem: \'', $txt['autosuggest_delete_item'], '\',
      sItemListContainerId: \'exclude_members_container\',
      aListItems: []
    });
  // ]]></script>';
}

function template_email_members_compose()
{
  global $context, $settings, $options, $txt, $scripturl;

  echo '
  <form action="', $scripturl, '?action=admin;area=news;sa=mailingsend" method="post" accept-charset="', $context['character_set'], '">
    <h3 class="title is-5 mb-4">
      <a href="', $scripturl, '?action=helpadmin;help=email_members" onclick="return reqWin(this.href);" >
        <span class="icon">
          <span class="fa fa-question-circle"></span>
        </span>
      </a> ', $txt['admin_newsletters'], '
    </h3>

    <p class="mb-4">', $txt['email_variables'], '</p>
    
    <div class="field">
      <label class="label">Subject</label>
      <div class="control">
        <input type="text" name="subject" size="60" value="', $context['default_subject'], '" class="input is-auto" />
      </div>
    </div>

    <div class="field">
      <label class="label">Message</label>
      <div class="control">
        <textarea cols="70" rows="9" name="message" class="textarea">', $context['default_message'], '</textarea>
      </div>
    </div>

    <div class="field">
      <div class="control">
        <label for="send_pm" class="checkbox"><input type="checkbox" name="send_pm" id="send_pm" class="input_check" onclick="if (this.checked && ', $context['total_emails'], ' != 0 && !confirm(\'', $txt['admin_news_cannot_pm_emails_js'], '\')) return false; this.form.parse_html.disabled = this.checked; this.form.send_html.disabled = this.checked; " /> ', $txt['email_as_pms'], '</label>
      </div>
    </div>

    <div class="field">
      <div class="control">
        <label for="send_html" class="checkbox"><input type="checkbox" name="send_html" id="send_html" class="input_check" onclick="this.form.parse_html.disabled = !this.checked;" /> ', $txt['email_as_html'], '</label>
      </div>
    </div>

    <div class="field">
      <div class="control">
        <label for="parse_html" class="checkbox"><input type="checkbox" name="parse_html" id="parse_html" checked="checked" disabled="disabled" class="input_check" /> ', $txt['email_parsed_html'], '</label>
      </div>
    </div>

    <p>
      <input type="submit" value="', $txt['sendtopic_send'], '" class="button is-primary" />
    </p>
    <input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
    <input type="hidden" name="email_force" value="', $context['email_force'], '" />
    <input type="hidden" name="total_emails" value="', $context['total_emails'], '" />
    <input type="hidden" name="max_id_member" value="', $context['max_id_member'], '" />';

  foreach ($context['recipients'] as $key => $values)
    echo '
      <input type="hidden" name="', $key, '" value="', implode(($key == 'emails' ? ';' : ','), $values), '" />';

  echo '
    </form>
  </div>
  <br class="clear" />';
}

function template_email_members_send()
{
  global $context, $settings, $options, $txt, $scripturl;

  echo '
  <form action="', $scripturl, '?action=admin;area=news;sa=mailingsend" method="post" accept-charset="', $context['character_set'], '" name="autoSubmit" id="autoSubmit">
    <h3 class="title is-5 mb-4">
      <a href="', $scripturl, '?action=helpadmin;help=email_members" onclick="return reqWin(this.href);" >
        <span class="icon">
          <span class="fa fa-question-circle"></span>
        </span>
      </a> ', $txt['admin_newsletters'], '
    </h3>

    <p>
      <strong>', $context['percentage_done'], '% ', $txt['email_done'], '</strong>
    </p>

    <input type="submit" name="b" value="', $txt['email_continue'], '" class="button_submit" />
    <input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
    <input type="hidden" name="subject" value="', $context['subject'], '" />
    <input type="hidden" name="message" value="', $context['message'], '" />
    <input type="hidden" name="start" value="', $context['start'], '" />
    <input type="hidden" name="total_emails" value="', $context['total_emails'], '" />
    <input type="hidden" name="max_id_member" value="', $context['max_id_member'], '" />
    <input type="hidden" name="send_pm" value="', $context['send_pm'], '" />
    <input type="hidden" name="send_html" value="', $context['send_html'], '" />
    <input type="hidden" name="parse_html" value="', $context['parse_html'], '" />';

  // All the things we must remember!
  foreach ($context['recipients'] as $key => $values)
    echo '
          <input type="hidden" name="', $key, '" value="', implode(($key == 'emails' ? ';' : ','), $values), '" />';

  echo '
  </form>
  <script type="text/javascript"><!-- // --><![CDATA[
    var countdown = 2;
    doAutoSubmit();

    function doAutoSubmit()
    {
      if (countdown == 0)
        document.forms.autoSubmit.submit();
      else if (countdown == -1)
        return;

      document.forms.autoSubmit.b.value = "', $txt['email_continue'], ' (" + countdown + ")";
      countdown--;

      setTimeout("doAutoSubmit();", 1000);
    }
  // ]]></script>';
}

?>