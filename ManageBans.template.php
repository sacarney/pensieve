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

function template_ban_edit()
{
  global $context, $settings, $options, $scripturl, $txt, $modSettings;

  echo '
  <div id="manage_bans">

    <div class="cat_bar">
      <h3 class="catbg">
        ', $context['ban']['is_new'] ? $txt['ban_add_new'] : $txt['ban_edit'] . ' \'' . $context['ban']['name'] . '\'', '
      </h3>
    </div>';

  if ($context['ban']['is_new'])
    echo '
    <div class="notification is-size-6-5 p-2">
      ', $txt['ban_add_notes'], '
    </div>';

  echo '
    <form action="', $scripturl, '?action=admin;area=ban;sa=edit" method="post" accept-charset="', $context['character_set'], '" onsubmit="if (this.ban_name.value == \'\') {alert(\'', $txt['ban_name_empty'], '\'); return false;} if (this.partial_ban.checked &amp;&amp; !(this.cannot_post.checked || this.cannot_register.checked || this.cannot_login.checked)) {alert(\'', $txt['ban_restriction_empty'], '\'); return false;}">

      <div class="field is-horizontal">
        <div class="field-label has-text-left">
          <label class="label">', $txt['ban_name'], '</label>
        </div>
        <div class="field-body">
          <div class="field">
            <input type="text" name="ban_name" value="', $context['ban']['name'], '" size="45" maxlength="60" class="input is-auto" />
          </div>
        </div>
      </div>

      <div class="field is-horizontal">
        <div class="field-label has-text-left">
          <label class="label">', $txt['ban_reason'], '</label>
          <p class="help">', $txt['ban_reason_desc'], '</p>
        </div>
        <div class="field-body">
          <div class="field">
            <textarea class="textarea is-auto" name="reason" cols="44" rows="3">', $context['ban']['reason'], '</textarea>
          </div>
        </div>
      </div>

      <div class="field is-horizontal">
        <div class="field-label has-text-left">
          <label class="label">', $txt['ban_notes'], '</label>
          <p class="help">', $txt['ban_notes_desc'], '</p>
        </div>
        <div class="field-body">
          <div class="field">
            <textarea class="textarea is-auto" name="reason" cols="44" rows="3">', $context['ban']['notes'], '</textarea>
          </div>
        </div>
      </div>

      <div class="columns">
        <div class="column">
          <fieldset>
            <legend>', $txt['ban_expiration'], '</legend>
            
            <label class="radio">
              <input type="radio" name="expiration" value="never" id="never_expires" onclick="fUpdateStatus();"', $context['ban']['expiration']['status'] == 'never' ? ' checked="checked"' : '', ' class="input_radio" />
              ', $txt['never'], '
            </label><br>

            <input type="radio" name="expiration" value="one_day" id="expires_one_day" onclick="fUpdateStatus();"', $context['ban']['expiration']['status'] == 'still_active_but_we_re_counting_the_days' ? ' checked="checked"' : '', ' class="input_radio" /> <label for="expires_one_day">', $txt['ban_will_expire_within'], '</label>: <input type="text" name="expire_date" id="expire_date" size="3" value="', $context['ban']['expiration']['days'], '" class="input is-auto is-small" /> ', $txt['ban_days'], '<br>

            <label class="radio">
              <input type="radio" name="expiration" value="expired" id="already_expired" onclick="fUpdateStatus();"', $context['ban']['expiration']['status'] == 'expired' ? ' checked="checked"' : '', ' class="input_radio" /> 
              ', $txt['ban_expired'], '
            </label>
          </fieldset>
        </div>

        <div class="column">
          <fieldset class="ban_settings floatright">
            <legend>', $txt['ban_restriction'], '</legend>

            <input type="radio" name="full_ban" id="full_ban" value="1" onclick="fUpdateStatus();"', $context['ban']['cannot']['access'] ? ' checked="checked"' : '', ' class="input_radio" /> <label for="full_ban">', $txt['ban_full_ban'], '</label><br />
            <input type="radio" name="full_ban" id="partial_ban" value="0" onclick="fUpdateStatus();"', !$context['ban']['cannot']['access'] ? ' checked="checked"' : '', ' class="input_radio" /> <label for="partial_ban">', $txt['ban_partial_ban'], '</label><br />
            <input type="checkbox" name="cannot_post" id="cannot_post" value="1"', $context['ban']['cannot']['post'] ? ' checked="checked"' : '', ' class="ban_restriction input_radio" /> <label for="cannot_post">', $txt['ban_cannot_post'], '</label> (<a href="', $scripturl, '?action=helpadmin;help=ban_cannot_post" onclick="return reqWin(this.href);">?</a>)<br />
            <input type="checkbox" name="cannot_register" id="cannot_register" value="1"', $context['ban']['cannot']['register'] ? ' checked="checked"' : '', ' class="ban_restriction input_radio" /> <label for="cannot_register">', $txt['ban_cannot_register'], '</label><br />
            <input type="checkbox" name="cannot_login" id="cannot_login" value="1"', $context['ban']['cannot']['login'] ? ' checked="checked"' : '', ' class="ban_restriction input_radio" /> <label for="cannot_login">', $txt['ban_cannot_login'], '</label><br />
          </fieldset>
        </div>
      </div>';

      if (!empty($context['ban_suggestions']))
      {
        echo '
      <fieldset>
        <legend>', $txt['ban_triggers'], '</legend>

        <div class="field is-horizontal">
          <div class="field-label has-text-left">
            <input type="checkbox" name="ban_suggestion[]" id="main_ip_check" value="main_ip" />
            <label for="main_ip_check">', $txt['ban_on_ip'], '</label>
          </div>
          <div class="field-body">
            <input type="text" name="main_ip" value="', $context['ban_suggestions']['main_ip'], '" size="44" onfocus="document.getElementById(\'main_ip_check\').checked = true;" class="input is-auto" />
          </div>
        </div>';

        if (empty($modSettings['disableHostnameLookup']))
        echo '
          <div class="field is-horizontal">
            <div class="field-label has-text-left">
              <input type="checkbox" name="ban_suggestion[]" id="hostname_check" value="hostname" class="input_check" />
              <label for="hostname_check">', $txt['ban_on_hostname'], '</label>
            </div>
            <div class="field-body">
              <input type="text" name="hostname" value="', $context['ban_suggestions']['hostname'], '" size="44" onfocus="document.getElementById(\'hostname_check\').checked = true;" class="input is-auto" />
            </div>
          </div>';

        echo '
          <div class="field is-horizontal">
            <div class="field-label has-text-left">
              <input type="checkbox" name="ban_suggestion[]" id="email_check" value="email" class="input_check" checked="checked" />
              <label for="email_check">', $txt['ban_on_email'], '</label>
            </div>
            <div class="field-body">
              <input type="text" name="email" value="', $context['ban_suggestions']['email'], '" size="44" onfocus="document.getElementById(\'email_check\').checked = true;" class="input is-auto" />
            </div>
          </div>

          <div class="field is-horizontal">
            <div class="field-label has-text-left">
              <input type="checkbox" name="ban_suggestion[]" id="user_check" value="user" class="input_check" checked="checked" />
              <label for="user_check">', $txt['ban_on_username'], '</label>
            </div>
            <div class="field-body">';

            if (empty($context['ban_suggestions']['member']['id']))
              echo '
                <input type="text" name="user" id="user" value="" size="44" class="input is-auto" />';
              else
              echo '
                ', $context['ban_suggestions']['member']['link'], '
                <input type="hidden" name="bannedUser" value="', $context['ban_suggestions']['member']['id'], '" />';
            echo '
            </div>
          </div>';

        if (!empty($context['ban_suggestions']['message_ips']))
        {
          echo '
          <div class="field is-horizontal">
            <div class="field-label has-text-left">
              <label>', $txt['ips_in_messages'], '</label>
            </div>
            <div class="field-body">';

          foreach ($context['ban_suggestions']['message_ips'] as $ip)
            echo '
              <label class="checkbox">
                <input type="checkbox" name="ban_suggestion[ips][]" value="', $ip, '"/>
                ', $ip, '
              </label>';

        echo'
            </div>
          </div>';
        }

        if (!empty($context['ban_suggestions']['error_ips']))
        {
        echo '
          <div class="field is-horizontal">
            <div class="field-label has-text-left">
              <label>', $txt['ips_in_errors'], '</label>
            </div>
            <div class="field-body">';

          foreach ($context['ban_suggestions']['error_ips'] as $ip)
            echo '
              <label class="checkbox">
                 <input type="checkbox" name="ban_suggestion[ips][]" value="', $ip, '" class="input_check" />
                ', $ip, '
              </label>';
          echo'
            </div>
          </div>';
        }

    echo '
    </fieldset>';
  }

  echo '
      <div class="mt-4">
        <input type="submit" name="', $context['ban']['is_new'] ? 'add_ban' : 'modify_ban', '" value="', $context['ban']['is_new'] ? $txt['ban_add'] : $txt['ban_modify'], '" class="button_submit" />
        <input type="hidden" name="old_expire" value="', $context['ban']['expiration']['days'], '" />
        <input type="hidden" name="bg" value="', $context['ban']['id'], '" />
        <input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
      </div>
    </form>
  </div>';

  if (!$context['ban']['is_new'] && empty($context['ban_suggestions']))
  {
    echo '
      <br />
      <form action="', $scripturl, '?action=admin;area=ban;sa=edit" method="post" accept-charset="', $context['character_set'], '" style="padding: 0px;margin: 0px;" onsubmit="return confirm(\'', $txt['ban_remove_selected_triggers_confirm'], '\');">
        <table class="table is-striped is-bordered is-fullwidth">
          <thead>
            <tr class="catbg">
              <th>', $txt['ban_banned_entity'], '</td>
              <th>', $txt['ban_hits'], '</td>
              <th>', $txt['ban_actions'], '</td>
              <th><input type="checkbox" onclick="invertAll(this, this.form, \'ban_items\');" class="input_check" /></td>
            </tr>
          </thead>
          <tbody>';
    if (empty($context['ban_items']))
      echo '
            <tr class="windowbg2">
              <td colspan="4">(', $txt['ban_no_triggers'], ')</td>
            </tr>';
    else
    {
      foreach ($context['ban_items'] as $ban_item)
      {
        echo '
            <tr class="windowbg2" align="left">
              <td>';
        if ($ban_item['type'] == 'ip')
          echo '    <strong>', $txt['ip'], ':</strong>&nbsp;', $ban_item['ip'];
        elseif ($ban_item['type'] == 'hostname')
          echo '    <strong>', $txt['hostname'], ':</strong>&nbsp;', $ban_item['hostname'];
        elseif ($ban_item['type'] == 'email')
          echo '    <strong>', $txt['email'], ':</strong>&nbsp;', $ban_item['email'];
        elseif ($ban_item['type'] == 'user')
          echo '    <strong>', $txt['username'], ':</strong>&nbsp;', $ban_item['user']['link'];
        echo '
              </td>
              <td class="windowbg" align="center">', $ban_item['hits'], '</td>
              <td class="windowbg" align="center"><a href="', $scripturl, '?action=admin;area=ban;sa=edittrigger;bg=', $context['ban']['id'], ';bi=', $ban_item['id'], '">', $txt['ban_edit_trigger'], '</a></td>
              <td align="center" class="windowbg2"><input type="checkbox" name="ban_items[]" value="', $ban_item['id'], '" class="input_check" /></td>
            </tr>';
      }
    }

    echo '
          </tbody>
        </table>

        <div class="additional_rows">
          <div class="floatleft">
            [<a href="', $scripturl, '?action=admin;area=ban;sa=edittrigger;bg=', $context['ban']['id'], '">', $txt['ban_add_trigger'], '</a>]
          </div>

          <div class="mt-3">
            <input type="submit" name="remove_selection" value="', $txt['ban_remove_selected_triggers'], '" class="button" />
          </div>
        </div>

        <br class="clear" />
        <input type="hidden" name="bg" value="', $context['ban']['id'], '" />
        <input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
      </form>';

  }

  echo '
  </div>
  <br class="clear" />
  <script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/suggest.js?fin20"></script>
  <script type="text/javascript"><!-- // --><![CDATA[
    var fUpdateStatus = function ()
    {
      document.getElementById("expire_date").disabled = !document.getElementById("expires_one_day").checked;
      document.getElementById("cannot_post").disabled = document.getElementById("full_ban").checked;
      document.getElementById("cannot_register").disabled = document.getElementById("full_ban").checked;
      document.getElementById("cannot_login").disabled = document.getElementById("full_ban").checked;
    }
    addLoadEvent(fUpdateStatus);';

  // Auto suggest only needed for adding new bans, not editing
  if ($context['ban']['is_new'] && empty($_REQUEST['u']))
    echo '
      var oAddMemberSuggest = new smc_AutoSuggest({
      sSelf: \'oAddMemberSuggest\',
      sSessionId: \'', $context['session_id'], '\',
      sSessionVar: \'', $context['session_var'], '\',
      sSuggestId: \'user\',
      sControlId: \'user\',
      sSearchType: \'member\',
      sTextDeleteItem: \'', $txt['autosuggest_delete_item'], '\',
      bItemList: false
    });

    function onUpdateName(oAutoSuggest)
    {
      document.getElementById(\'user_check\').checked = true;
      return true;
    }
    oAddMemberSuggest.registerCallback(\'onBeforeUpdate\', \'onUpdateName\');';

  echo '// ]]></script>';
}

function template_ban_edit_trigger()
{
  global $context, $settings, $options, $scripturl, $txt, $modSettings;

  echo '
  <div id="manage_bans">
    <form action="', $scripturl, '?action=admin;area=ban;sa=edit" method="post" accept-charset="', $context['character_set'], '">
      <div class="cat_bar">
        <h3 class="catbg">
          ', $context['ban_trigger']['is_new'] ? $txt['ban_add_trigger'] : $txt['ban_edit_trigger_title'], '
        </h3>
      </div>

      <fieldset>
        <legend>', $txt['ban_triggers'], '</legend>

        <div class="field is-horizontal">
          <div class="field-label has-text-left">
            <input type="radio" name="bantype" value="ip_ban"', $context['ban_trigger']['ip']['selected'] ? ' checked="checked"' : '', ' class="input_radio" />
                ', $txt['ban_on_ip'], '
          </div>
          <div class="field-body">
            <input type="text" name="ip" value="', $context['ban_trigger']['ip']['value'], '" size="50" onfocus="selectRadioByName(this.form.bantype, \'ip_ban\');" class="input is-auto" />
          </div>
        </div>';

        if (empty($modSettings['disableHostnameLookup']))
        echo '

        <div class="field is-horizontal">
          <div class="field-label has-text-left">
            <input type="radio" name="bantype" value="hostname_ban"', $context['ban_trigger']['hostname']['selected'] ? ' checked="checked"' : '', ' class="input_radio" />
                ', $txt['ban_on_hostname'], '
          </div>
          <div class="field-body">
            <input type="text" name="hostname" value="', $context['ban_trigger']['hostname']['value'], '" size="50" onfocus="selectRadioByName(this.form.bantype, \'hostname_ban\');" class="input is-auto" />
          </div>
        </div>';

        echo '
        <div class="field is-horizontal">
          <div class="field-label has-text-left">
            <input type="radio" name="bantype" value="email_ban"', $context['ban_trigger']['email']['selected'] ? ' checked="checked"' : '', ' class="input_radio" />
                ', $txt['ban_on_email'], '
          </div>
          <div class="field-body">
            <input type="text" name="email" value="', $context['ban_trigger']['email']['value'], '" size="50" onfocus="selectRadioByName(this.form.bantype, \'email_ban\');" class="input is-auto" />
          </div>
        </div>

        <div class="field is-horizontal">
          <div class="field-label has-text-left">
            <input type="radio" name="bantype" value="user_ban"', $context['ban_trigger']['banneduser']['selected'] ? ' checked="checked"' : '', ' class="input_radio" />
                ', $txt['ban_on_username'], '
          </div>
          <div class="field-body">
            <input type="text" name="user" id="user" value="', $context['ban_trigger']['banneduser']['value'], '" size="50" onfocus="selectRadioByName(this.form.bantype, \'user_ban\');" class="input_text" />
          </div>
        </div>
      </fieldset>
          
      <div class="mt-4">
        <input type="submit" name="', $context['ban_trigger']['is_new'] ? 'add_new_trigger' : 'edit_trigger', '" value="', $context['ban_trigger']['is_new'] ? $txt['ban_add_trigger_submit'] : $txt['ban_edit_trigger_submit'], '" class="button_submit" />
      </div>

      <input type="hidden" name="bi" value="' . $context['ban_trigger']['id'] . '" />
      <input type="hidden" name="bg" value="' . $context['ban_trigger']['group'] . '" />
      <input type="hidden" name="' . $context['session_var'] . '" value="' . $context['session_id'] . '" />
    </form>
  </div>

  <br class="clear" />
  <script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/suggest.js?fin20"></script>
  <script type="text/javascript"><!-- // --><![CDATA[
    var oAddMemberSuggest = new smc_AutoSuggest({
      sSelf: \'oAddMemberSuggest\',
      sSessionId: \'', $context['session_id'], '\',
      sSessionVar: \'', $context['session_var'], '\',
      sSuggestId: \'username\',
      sControlId: \'user\',
      sSearchType: \'member\',
      sTextDeleteItem: \'', $txt['autosuggest_delete_item'], '\',
      bItemList: false
    });

    function onUpdateName(oAutoSuggest)
    {
      selectRadioByName(oAutoSuggest.oTextHandle.form.bantype, \'user_ban\');
      return true;
    }
    oAddMemberSuggest.registerCallback(\'onBeforeUpdate\', \'onUpdateName\');
  // ]]></script>';
}

?>