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

function template_search_members()
{
  global $context, $settings, $options, $scripturl, $txt;

  echo '
  <div id="admincenter">
    <form action="', $scripturl, '?action=admin;area=viewmembers" method="post" accept-charset="', $context['character_set'], '">
      <div class="cat_bar">
        <h3 class="title is-5 mb-4">
          ', $txt['search_for'], ' | ', $txt['wild_cards_allowed'], '
        </h3>
      </div>

      <input type="hidden" name="sa" value="query" />
      <div class="windowbg mb-4">
        <div class="content">

          <div class="columns">
            <div class="column">

              <div class="field is-horizontal">
                <div class="field-label">
                  <label class="label">', $txt['member_id'], '</label>
                </div>
                <div class="field-body">
                  <div class="field is-narrow">
                    <div class="select">
                      <select name="types[mem_id]">
                        <option value="--">&lt;</option>
                        <option value="-">&lt;=</option>
                        <option value="=" selected="selected">=</option>
                        <option value="+">&gt;=</option>
                        <option value="++">&gt;</option>
                      </select>
                    </div>
                  </div>
                  <div class="field is-narrow">
                    <input type="text" name="mem_id" value="" size="6" class="input" />
                  </div>
                </div>
              </div>

              <div class="field is-horizontal">
                <div class="field-label">
                  <label class="label">', $txt['age'], '</label>
                </div>
                <div class="field-body">
                  <div class="field is-narrow">
                    <div class="select">
                      <select name="types[age]">
                        <option value="--">&lt;</option>
                        <option value="-">&lt;=</option>
                        <option value="=" selected="selected">=</option>
                        <option value="+">&gt;=</option>
                        <option value="++">&gt;</option>
                      </select>
                    </div>
                  </div>
                  <div class="field is-narrow">
                    <input type="text" name="age" value="" size="6" class="input" />
                  </div>
                </div>
              </div>

              <div class="field is-horizontal">
                <div class="field-label">
                  <label class="label">', $txt['member_postcount'], '</label>
                </div>
                <div class="field-body">
                  <div class="field is-narrow">
                    <div class="select">
                      <select name="types[posts]">
                        <option value="--">&lt;</option>
                        <option value="-">&lt;=</option>
                        <option value="=" selected="selected">=</option>
                        <option value="+">&gt;=</option>
                        <option value="++">&gt;</option>
                      </select>
                    </div>
                  </div>
                  <div class="field is-narrow">
                    <input type="text" name="posts" value="" size="6" class="input" />
                  </div>
                </div>
              </div>

              <div class="field is-horizontal">
                <div class="field-label">
                  <label class="label">', $txt['date_registered'], '</label>
                </div>
                <div class="field-body">
                  <div class="field is-narrow">
                    <div class="select">
                      <select name="types[reg_date]">
                        <option value="--">&lt;</option>
                        <option value="-">&lt;=</option>
                        <option value="=" selected="selected">=</option>
                        <option value="+">&gt;=</option>
                        <option value="++">&gt;</option>
                      </select>
                    </div>
                  </div>
                  <div class="field is-narrow">
                    <input type="text" name="reg_date" value="" size="6" class="input" />
                  </div>
                  <div class="field">
                    <span class="is-muted">', $txt['date_format'], '</span>
                  </div>
                </div>
              </div>

              <div class="field is-horizontal">
                <div class="field-label">
                  <label class="label">', $txt['viewmembers_online'], '</label>
                </div>
                <div class="field-body">
                  <div class="field is-narrow">
                    <div class="select">
                      <select name="types[last_online]">
                      <option value="--">&lt;</option>
                      <option value="-">&lt;=</option>
                      <option value="=" selected="selected">=</option>
                      <option value="+">&gt;=</option>
                      <option value="++">&gt;</option>
                    </select>
                    </div>
                  </div>
                  <div class="field is-narrow">
                    <input type="text" name="last_online" value="" size="10" class="input" />
                  </div>
                  <div class="field">
                    <span class="is-muted">', $txt['date_format'], '</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="column">

              <div class="field is-horizontal">
                <div class="field-label">
                  <label class="label">', $txt['username'], '</label>
                </div>
                <div class="field-body">
                  <div class="field is-narrow">
                    <input type="text" name="membername" value="" class="input" />
                  </div>
                </div>
              </div>

              <div class="field is-horizontal">
                <div class="field-label">
                  <label class="label">', $txt['email_address'], '</label>
                </div>
                <div class="field-body">
                  <div class="field is-narrow">
                    <input type="text" name="email" value="" class="input" />
                  </div>
                </div>
              </div>

              <div class="field is-horizontal">
                <div class="field-label">
                  <label class="label">', $txt['website'], '</label>
                </div>
                <div class="field-body">
                  <div class="field is-narrow">
                    <input type="text" name="website" value="" class="input" />
                  </div>
                </div>
              </div>

              <div class="field is-horizontal">
                <div class="field-label">
                  <label class="label">', $txt['location'], '</label>
                </div>
                <div class="field-body">
                  <div class="field is-narrow">
                    <input type="text" name="location" value="" class="input" />
                  </div>
                </div>
              </div>

              <div class="field is-horizontal">
                <div class="field-label">
                  <label class="label">', $txt['ip_address'], '</label>
                </div>
                <div class="field-body">
                  <div class="field is-narrow">
                    <input type="text" name="ip" value="" class="input" />
                  </div>
                </div>
              </div>

              <div class="field is-horizontal">
                <div class="field-label">
                  <label class="label">', $txt['messenger_address'], '</label>
                </div>
                <div class="field-body">
                  <div class="field is-narrow">
                    <input type="text" name="messenger" value="" class="input" />
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="columns">
            <div class="column">
              <fieldset>
                <legend>', $txt['gender'], '</legend>
                <label class="checkbox" for="gender-0"><input type="checkbox" name="gender[]" value="0" id="gender-0" checked="checked" class="checkbox" /> ', $txt['undefined_gender'], '</label>&nbsp;&nbsp;
                <label class="checkbox" for="gender-1"><input type="checkbox" name="gender[]" value="1" id="gender-1" checked="checked" class="checkbox" /> ', $txt['male'], '</label>&nbsp;&nbsp;
                <label class="checkbox" for="gender-2"><input type="checkbox" name="gender[]" value="2" id="gender-2" checked="checked" class="checkbox" /> ', $txt['female'], '</label>
              </fieldset>
            </div>
            <div class="column">
              <fieldset>
                <legend>', $txt['activation_status'], '</legend>
                <label class="checkbox" for="activated-0"><input type="checkbox" name="activated[]" value="1" id="activated-0" checked="checked" class="checkbox" /> ', $txt['activated'], '</label>&nbsp;&nbsp;
                <label class="checkbox" for="activated-1"><input type="checkbox" name="activated[]" value="0" id="activated-1" checked="checked" class="checkbox" /> ', $txt['not_activated'], '</label>
              </fieldset>
            </div>
          </div>
        </div>

      </div>
      
      <div class="cat_bar">
        <h3 class="title is-5 mb-4">', $txt['member_part_of_these_membergroups'], '</h3>
      </div>

      <div class="columns">
        <div class="column">

          <table class="table is-bordered is-striped is-narrow is-fullwidth">
            <thead>
              <tr class="catbg">
                <th scope="col" class="first_th">', $txt['membergroups'], '</th>
                <th scope="col">', $txt['primary'], '</th>
                <th scope="col" class="last_th">', $txt['additional'], '</th>
              </tr>
            </thead>
            <tbody>';

        foreach ($context['membergroups'] as $membergroup)
          echo '
              <tr class="windowbg2">
                <td>', $membergroup['name'], '</td>
                <td align="center">
                  <input type="checkbox" name="membergroups[1][]" value="', $membergroup['id'], '" checked="checked" class="input_check" />
                </td>
                <td align="center">
                  ', $membergroup['can_be_additional'] ? '<input type="checkbox" name="membergroups[2][]" value="' . $membergroup['id'] . '" checked="checked" class="input_check" />' : '', '
                </td>
              </tr>';

        echo '
              <tr class="windowbg2">
                <td>
                  <em>', $txt['check_all'], '</em>
                </td>
                <td align="center">
                  <input type="checkbox" onclick="invertAll(this, this.form, \'membergroups[1]\');" checked="checked" class="input_check" />
                </td>
                <td align="center">
                  <input type="checkbox" onclick="invertAll(this, this.form, \'membergroups[2]\');" checked="checked" class="input_check" />
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="column">

          <table class="table is-bordered is-striped is-narrow is-fullwidth">
            <thead>
              <tr class="catbg">
                <th scope="col" class="first_th">
                  ', $txt['membergroups_postgroups'], '
                </th>
                <th class="last_th">&nbsp;</th>
              </tr>
            </thead>
            </tbody>';

        foreach ($context['postgroups'] as $postgroup)
          echo '
              <tr class="windowbg2">
                <td>
                  ', $postgroup['name'], '
                </td>
                <td width="40" align="center">
                  <input type="checkbox" name="postgroups[]" value="', $postgroup['id'], '" checked="checked" class="input_check" />
                </td>
              </tr>';

        echo '
              <tr class="windowbg2">
                <td>
                  <em>', $txt['check_all'], '</em>
                </td>
                <td align="center">
                  <input type="checkbox" onclick="invertAll(this, this.form, \'postgroups[]\');" checked="checked" class="input_check" />
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <input type="submit" value="', $txt['search'], '" class="button is-primary" />
      </div>
    </form>
  </div>
';
}

function template_admin_browse()
{
  global $context, $settings, $options, $scripturl, $txt, $modSettings;

  echo '
  <div id="admincenter">';

  template_show_list('approve_list');

  // If we have lots of outstanding members try and make the admin's life easier.
  if ($context['approve_list']['total_num_items'] > 20)
  {
    echo '
    <br />
    <form action="', $scripturl, '?action=admin;area=viewmembers" method="post" accept-charset="', $context['character_set'], '" name="postFormOutstanding" id="postFormOutstanding" onsubmit="return onOutstandingSubmit();">
      <div class="cat_bar">
        <h3 class="catbg">', $txt['admin_browse_outstanding'], '</h3>
      </div>
      <script type="text/javascript"><!-- // --><![CDATA[
        function onOutstandingSubmit()
        {
          if (document.forms.postFormOutstanding.todo.value == "")
            return;

          var message = "";
          if (document.forms.postFormOutstanding.todo.value.indexOf("delete") != -1)
            message = "', $txt['admin_browse_w_delete'], '";
          else if (document.forms.postFormOutstanding.todo.value.indexOf("reject") != -1)
            message = "', $txt['admin_browse_w_reject'], '";
          else if (document.forms.postFormOutstanding.todo.value == "remind")
            message = "', $txt['admin_browse_w_remind'], '";
          else
            message = "', $context['browse_type'] == 'approve' ? $txt['admin_browse_w_approve'] : $txt['admin_browse_w_activate'], '";

          if (confirm(message + " ', $txt['admin_browse_outstanding_warn'], '"))
            return true;
          else
            return false;
        }
      // ]]></script>

      <div class="windowbg">
        <span class="topslice"><span></span></span>
        <div class="content">
          <dl class="settings">
            <dt>
              ', $txt['admin_browse_outstanding_days_1'], ':
            </dt>
            <dd>
              <input type="text" name="time_passed" value="14" maxlength="4" size="3" class="input" /> ', $txt['admin_browse_outstanding_days_2'], '.
            </dd>
            <dt>
              ', $txt['admin_browse_outstanding_perform'], ':
            </dt>
            <dd>
              <select name="todo">
                ', $context['browse_type'] == 'activate' ? '
                <option value="ok">' . $txt['admin_browse_w_activate'] . '</option>' : '', '
                <option value="okemail">', $context['browse_type'] == 'approve' ? $txt['admin_browse_w_approve'] : $txt['admin_browse_w_activate'], ' ', $txt['admin_browse_w_email'], '</option>', $context['browse_type'] == 'activate' ? '' : '
                <option value="require_activation">' . $txt['admin_browse_w_approve_require_activate'] . '</option>', '
                <option value="reject">', $txt['admin_browse_w_reject'], '</option>
                <option value="rejectemail">', $txt['admin_browse_w_reject'], ' ', $txt['admin_browse_w_email'], '</option>
                <option value="delete">', $txt['admin_browse_w_delete'], '</option>
                <option value="deleteemail">', $txt['admin_browse_w_delete'], ' ', $txt['admin_browse_w_email'], '</option>', $context['browse_type'] == 'activate' ? '
                <option value="remind">' . $txt['admin_browse_w_remind'] . '</option>' : '', '
              </select>
            </dd>
          </dl>
          <input type="submit" value="', $txt['admin_browse_outstanding_go'], '" class="button is-primary" />
          <input type="hidden" name="type" value="', $context['browse_type'], '" />
          <input type="hidden" name="sort" value="', $context['approve_list']['sort']['id'], '" />
          <input type="hidden" name="start" value="', $context['approve_list']['start'], '" />
          <input type="hidden" name="orig_filter" value="', $context['current_filter'], '" />
          <input type="hidden" name="sa" value="approve" />', !empty($context['approve_list']['sort']['desc']) ? '
          <input type="hidden" name="desc" value="1" />' : '', '
        </div>
        <span class="botslice"><span></span></span>
      </div>
      <input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
    </form>';
  }

  echo '
  </div>
  <br class="clear" />';
}

?>