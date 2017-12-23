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

// This is just the basic "login" form.
function template_login()
{
  global $context, $settings, $options, $scripturl, $modSettings, $txt;

  echo '
    <script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/sha1.js"></script>
    <div class="container">
      <div class="columns is-centered">
        <div class="column is-5">

          <form action="', $scripturl, '?action=login2" name="frmLogin" id="frmLogin" method="post" accept-charset="', $context['character_set'], '" ', empty($context['disable_login_hashing']) ? ' onsubmit="hashLoginPassword(this, \'' . $context['session_id'] . '\');"' : '', '>

              <div class="card login-card">
                <div class="card-header">
                  <p class="card-header-title">', $txt['login'],'</p>
                </div>
                <div class="card-content">
                ';

                // Did they make a mistake last time?
                if (!empty($context['login_errors']))
                  foreach ($context['login_errors'] as $error)
                    echo '
                      <p class="notification is-danger">', $error, '</p>';

                // Or perhaps there's some special description for this time?
                if (isset($context['description']))
                  echo '
                    <p class="notification">', $context['description'], '</p>
                  ';

                // Now just get the basic information - username, password, etc.
                echo '
                  <div class="field">
                    <label for="user" class="label">', $txt['username'], '</label>
                    <div class="control">
                      <input type="text" name="user" size="20" value="', $context['default_username'], '" class="input_text input" />
                    </div>
                  </div>
                  <div class="field">
                    <label for="passwrd" class="label">', $txt['password'], '</label>
                    <div class="control">
                      <input type="password" name="passwrd" value="', $context['default_password'], '" size="20" class="input_password input" />
                    </div>
                  </div>
                  ';

                  if (!empty($modSettings['enableOpenID']))
                  echo '
                    <p><strong>&mdash;', $txt['or'], '&mdash;</strong></p>

                    <div class="field">
                      <label for="openid_identifier" class="label">
                      ', $txt['openid'], '
                      <a href="', $scripturl, '?action=helpadmin;help=register_openid" onclick="return reqWin(this.href);" class="help">(?)</a>
                      </label>
                      <div class="control">
                        <input type="text" name="openid_identifier" class="input_text openid_login input" size="17" />
                      </div>
                    </div>
                  ';

                  echo '
                    <div class="field">
                      <label for="cookielength" class="label">', $txt['mins_logged_in'], '</label>
                      <div class="control">
                        <input type="text" name="cookielength" size="4" maxlength="4" value="', $modSettings['cookieTime'], '"', $context['never_expire'] ? ' disabled="disabled"' : '', ' class="input input_text" />
                      </div>
                    </div>
                    <div class="field">
                      <div class="control">
                        <label for="cookieneverexp" class="checkbox">
                          <input type="checkbox" name="cookieneverexp"', $context['never_expire'] ? ' checked="checked"' : '', ' class="checkbox input_check" onclick="this.form.cookielength.disabled = this.checked;" />
                            ', $txt['always_logged_in'], '
                        </label>
                      </div>
                    </div>
                  ';

                  // If they have deleted their account, give them a chance to change their mind.
                  if (isset($context['login_show_undelete']))
                    echo '
                      <div class="field">
                        <label for="undelete" class="checkbox">
                          <input type="checkbox" name="undelete" class="input_check" />
                          ', $txt['undelete_account'], '
                        </label>
                      </div>
                    ';

                  echo '
                    <div class="field is-grouped">
                      <p class="control">
                        <input type="submit" value="', $txt['login'], '" class="button is-primary button_submit" />
                      </p>
                      <p class="help"><a href="', $scripturl, '?action=reminder">', $txt['forgot_your_password'], '</a></p>
                      
                      <input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
                      <input type="hidden" name="hash_passwrd" value="" />
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        ';

          // Focus on the correct input - username or password.
          echo '
            <script type="text/javascript"><!-- // --><![CDATA[
              document.forms.frmLogin.', isset($context['default_username']) && $context['default_username'] != '' ? 'passwrd' : 'user', '.focus();
            // ]]></script>';
        }

// Tell a guest to get lost or login!
function template_kick_guest()
{
  global $context, $settings, $options, $scripturl, $modSettings, $txt;

  // This isn't that much... just like normal login but with a message at the top.
  echo '
  <script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/sha1.js"></script>

  <section class="login-page page-content">
    <div>

  <form action="', $scripturl, '?action=login2" method="post" accept-charset="', $context['character_set'], '" name="frmLogin" id="frmLogin"', empty($context['disable_login_hashing']) ? ' onsubmit="hashLoginPassword(this, \'' . $context['session_id'] . '\');"' : '', '>

    <div class="card login-card">
      <div class="card-header">
        <p class="card-header-title">', $txt['warning'], '</p>
      </div>
      <div class="card-content">
  ';

  // Show the message or default message.
  echo '
      <p class="notification">
        ', empty($context['kick_message']) ? $txt['only_members_can_access'] : $context['kick_message'], '<br />
        ', $txt['login_below'], ' <a href="', $scripturl, '?action=register">', $txt['register_an_account'], '</a> ', sprintf($txt['login_with_forum'], $context['forum_name_html_safe']), '
      </p>';

  // And now the login information.
  echo '
    <div class="field">
      <label for="user" class="label">', $txt['username'], '</label>
      <div class="control">
        <input type="text" name="user" size="20" class="input_text input" />
      </div>
    </div>
    <div class="field">
      <label for="passwrd" class="label">', $txt['password'], '</label>
      <div class="control">
        <input type="password" name="passwrd" size="20" class="input_password input" />
      </div>
    </div>
  ';

  if (!empty($modSettings['enableOpenID']))
    echo '
      <p><strong>&mdash;', $txt['or'], '&mdash;</strong></p>

      <div class="field">
        <label for="openid_identifier" class="label">
        ', $txt['openid'], '
        <a href="', $scripturl, '?action=helpadmin;help=register_openid" onclick="return reqWin(this.href);" class="help">(?)</a>
        </label>
        <div class="control">
          <input type="text" name="openid_identifier" class="input_text openid_login input" size="17" />
        </div>
      </div>
    ';

  echo '
    <div class="field">
      <label for="cookielength" class="label">', $txt['mins_logged_in'], '</label>
      <div class="control">
        <input type="text" name="cookielength" size="4" maxlength="4" value="', $modSettings['cookieTime'], '"', $context['never_expire'] ? ' disabled="disabled"' : '', ' class="input input_text" />
      </div>
    </div>
    <div class="field">
      <div class="control">
        <label for="cookieneverexp" class="checkbox">
          <input type="checkbox" name="cookieneverexp"', $context['never_expire'] ? ' checked="checked"' : '', ' class="checkbox input_check" onclick="this.form.cookielength.disabled = this.checked;" />
            ', $txt['always_logged_in'], '
        </label>
      </div>
    </div>

    <div class="field is-grouped">
      <p class="control">
        <input type="submit" value="', $txt['login'], '" class="button is-primary button_submit" />
      </p>
      <p class="help"><a href="', $scripturl, '?action=reminder">', $txt['forgot_your_password'], '</a></p>
      
      <input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
      <input type="hidden" name="hash_passwrd" value="" />
    </div>

  </div>
</div>
</form>
</div>
</section>
';

  // Do the focus thing...
  echo '
    <script type="text/javascript"><!-- // --><![CDATA[
      document.forms.frmLogin.user.focus();
    // ]]></script>';
}

// This is for maintenance mode.
function template_maintenance()
{
  global $context, $settings, $options, $scripturl, $txt, $modSettings;

  // Display the administrator's message at the top.
  echo '
<script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/sha1.js"></script>

<section class="login-page page-content">
    <div>

<form action="', $scripturl, '?action=login2" method="post" accept-charset="', $context['character_set'], '"', empty($context['disable_login_hashing']) ? ' onsubmit="hashLoginPassword(this, \'' . $context['session_id'] . '\');"' : '', '>

<div class="card login-card">
  <div class="card-header">
    <p class="card-header-title">', $txt['admin_login'], '</p>
  </div>
  <div class="card-content">

  <p class="notification is-warning">', $context['description'], '</p>

    <div class="field">
      <label for="user" class="label">', $txt['username'], '</label>
      <div class="control">
        <input type="text" name="user" size="20" class="input_text input" />
      </div>
    </div>
    <div class="field">
      <label for="passwrd" class="label">', $txt['password'], '</label>
      <div class="control">
        <input type="password" name="passwrd" size="20" class="input_password input" />
      </div>
    </div>

    <div class="field">
      <label for="cookielength" class="label">', $txt['mins_logged_in'], '</label>
      <div class="control">
        <input type="text" name="cookielength" size="4" maxlength="4" value="', $modSettings['cookieTime'], '"', $context['never_expire'] ? ' disabled="disabled"' : '', ' class="input input_text" />
      </div>
    </div>
    <div class="field">
      <div class="control">
        <label for="cookieneverexp" class="checkbox">
          <input type="checkbox" name="cookieneverexp"', $context['never_expire'] ? ' checked="checked"' : '', ' class="checkbox input_check" onclick="this.form.cookielength.disabled = this.checked;" />
            ', $txt['always_logged_in'], '
        </label>
      </div>
    </div>

    <div class="field is-grouped">
      <p class="control">
        <input type="submit" value="', $txt['login'], '" class="button is-primary button_submit" />
      </p>
      <p class="help"><a href="', $scripturl, '?action=reminder">', $txt['forgot_your_password'], '</a></p>
      
      <input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
      <input type="hidden" name="hash_passwrd" value="" />
    </div>

  </div>
</div>
</form>
</div>
</section>

';
}

// This is for the security stuff - makes administrators login every so often.
function template_admin_login()
{
  global $context, $settings, $options, $scripturl, $txt;

  // Since this should redirect to whatever they were doing, send all the get data.
  echo '
    <script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/sha1.js"></script>

    <section class="login-page page-content">
        <div>

    <form action="', $scripturl, $context['get_data'], '" method="post" accept-charset="', $context['character_set'], '" name="frmLogin" id="frmLogin" onsubmit="hashAdminPassword(this, \'', $context['user']['username'], '\', \'', $context['session_id'], '\');">

    <div class="card login-card">
      <div class="card-header">
        <p class="card-header-title">', $txt['login'], '</p>
      </div>
      <div class="card-content">
    ';

  if (!empty($context['incorrect_password']))
    echo '
      <p class="notification is-danger">', $txt['admin_incorrect_password'], '</p>';

  echo '
    <div class="field">
      <label for="admin_pass" class="label">
      ', $txt['password'], ' 
      <a href="', $scripturl, '?action=helpadmin;help=securityDisable_why" onclick="return reqWin(this.href);" class="help"><img src="', $settings['images_url'], '/helptopics.gif" alt="', $txt['help'], '" /></a>
      </label>
      <div class="control">
        <input type="password" name="admin_pass" size="20" class="input_password input" />
      </div>
    </div>
    <div class="field is-grouped">
      <p class="control">
        <input type="submit" value="', $txt['login'], '" class="button is-primary button_submit" />
      </p>
    </div>
';

  // Make sure to output all the old post data.
  echo $context['post_data'], '
  <input type="hidden" name="admin_hash_pass" value="" />
  </div>
</div>
</form>
</div>
</section>';

  // Focus on the password box.
  echo '
<script type="text/javascript"><!-- // --><![CDATA[
  document.forms.frmLogin.admin_pass.focus();
// ]]></script>';
}

// Activate your account manually?
function template_retry_activate()
{
  global $context, $settings, $options, $txt, $scripturl;

  // Just ask them for their code so they can try it again...
  echo '

  <section class="login-page page-content">
    <div>

    <form action="', $scripturl, '?action=activate;u=', $context['member_id'], '" method="post" accept-charset="', $context['character_set'], '">

    <div class="card login-card">
      <div class="card-header">
        <p class="card-header-title">', $context['page_title'], '</p>
      </div>
      <div class="card-content">

  ';

  // You didn't even have an ID?
  if (empty($context['member_id']))
    echo '
      <div class="field">
        <label for="user" class="label">', $txt['invalid_activation_username'], '</label>
        <div class="control">
          <input type="text" name="user" size="30" class="input_text input" />
        </div>
      </div>
    ';

  echo '
    <div class="field">
      <label for="code" class="label">', $txt['invalid_activation_retry'], '</label>
      <div class="control">
        <input type="text" name="code" size="30" class="input_text" />
      </div>
    </div>

    <div class="field is-grouped">
      <p class="control">
        <input type="submit" value="', $txt['invalid_activation_submit'], '" class="button is-primary button_submit" />
      </p>
    </div>

  </div>
</div>
</form>
</div>
</section>';
}

// Activate your account manually?
function template_resend()
{
  global $context, $settings, $options, $txt, $scripturl;

  // Just ask them for their code so they can try it again...
  echo '

  <section class="login-page page-content">
    <div>

    <form action="', $scripturl, '?action=activate;sa=resend" method="post" accept-charset="', $context['character_set'], '">

    <div class="card login-card">
      <div class="card-header">
        <p class="card-header-title">', $context['page_title'], '</p>
      </div>
      <div class="card-content">

      <div class="field">
        <label class="label" for="user">', $txt['invalid_activation_username'], '</label>
        <div class="control">
          <input type="text" name="user" size="40" value="', $context['default_username'], '" class="input_text" />
        </div>
      </div>

      <p class="notification">', $txt['invalid_activation_new'], '</p>

      <div class="field">
        <label class="label" for="new_email">', $txt['invalid_activation_new_email'], '</label>
        <div class="control">
          <input type="text" name="new_email" size="40" class="input input_text" />
        </div>
      </div>

      <div class="field">
        <label class="label" for="passwd">', $txt['invalid_activation_password'], '</label>
        <div class="control">
          <input type="password" name="passwd" size="30" class="input_password" />
        </div>
      </div>
  ';

  if ($context['can_activate'])
    echo '
        <p class="notification">', $txt['invalid_activation_known'], '</p>

        <div class="field">
          <label class="label" for="code">', $txt['invalid_activation_retry'], '</label>
          <div class="control">
            <input type="text" name="code" size="30" class="input_text input" />
          </div>
        </div>
      ';

  echo '
    <div class="field is-grouped">
      <p class="control">
        <input type="submit" value="', $txt['invalid_activation_submit'], '" class="button is-primary button_submit" />
      </p>
    </div>
    
  </div>
</div>
</form>
</div>
</section>';
}

?>