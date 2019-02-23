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
  global $context, $settings, $options, $txt, $scripturl;

  // Some javascript for adding more options.
  echo '
    <script type="text/javascript"><!-- // --><![CDATA[
      var pollOptionNum = 0;

      function addPollOption()
      {
        if (pollOptionNum == 0)
        {
          for (var i = 0; i < document.forms.postmodify.elements.length; i++)
            if (document.forms.postmodify.elements[i].id.substr(0, 8) == "options-")
              pollOptionNum++;
        }
        pollOptionNum++

        setOuterHTML(document.getElementById("pollMoreOptions"), \'<li><div class="field is-horizontal mb-2"><div class="field-label is-narrow"><label for="options-\' + pollOptionNum + \'" ', (isset($context['poll_error']['no_question']) ? ' class="error label"' : 'class="label"'), '>', $txt['option'], ' \' + pollOptionNum + \'</label> </div><div class="field-body"><div class="field is-narrow"><input type="text" name="options[\' + (pollOptionNum - 1) + \']" id="options-\' + (pollOptionNum - 1) + \'" value="" size="80" maxlength="255" class="input" /></div></div></div></li><li id="pollMoreOptions"></li\');
      }
    // ]]></script>';

  // Start the main poll form.
  echo '
  <div id="edit_poll">
    <form action="' . $scripturl . '?action=editpoll2', $context['is_edit'] ? '' : ';add', ';topic=' . $context['current_topic'] . '.' . $context['start'] . '" method="post" accept-charset="', $context['character_set'], '" onsubmit="submitonce(this); smc_saveEntities(\'postmodify\', [\'question\'], \'options-\');" name="postmodify" id="postmodify">
      <div class="cat_bar">
        <h3 class="title is-5 mt-4 mb-4">', $context['page_title'], '</h3>
      </div>';

    if (!empty($context['poll_error']['messages']))
    echo '
      <div class="notification is-danger">
        <dl class="poll_error">
          <dt>
            ', $context['is_edit'] ? $txt['error_while_editing_poll'] : $txt['error_while_adding_poll'], ':
          </dt>
          <dt>
            ', empty($context['poll_error']['messages']) ? '' : implode('<br />', $context['poll_error']['messages']), '
          </dt>
        </dl>
      </div>';

    echo '

    <input type="hidden" name="poll" value="', $context['poll']['id'], '" />
    
    <fieldset id="poll_main">
      <legend><span ', (isset($context['poll_error']['no_question']) ? ' class="is-danger"' : ''), '>', $txt['poll_question'], '</span></legend>

      <div class="field is-horizontal">
        <div class="field-label is-narrow">
          <label class="label">', $txt['poll_question'], '</label>
        </div>
        <div class="field-body">
          <div class="field is-narrow">
            <input type="text" name="question" size="80" value="', $context['poll']['question'], '" class="input" />
          </div>
        </div>
      </div>

      <ul class="poll_main">';
        // Loop through all the choices and print them out.
        foreach ($context['choices'] as $choice)
        {
        echo '
        <li>
          <div class="field is-horizontal mb-2">
            <div class="field-label is-narrow">
              <label class="label" for="options-', $choice['id'], '">', $txt['option'], ' ', $choice['number'], '</label>
            </div>
            <div class="field-body">
              <div class="field is-narrow">
                <input type="text" name="options[', $choice['id'], ']" id="options-', $choice['id'], '" value="', $choice['label'], '" size="80" maxlength="255" class="input" />
              </div>
              <div class="field">';
              // Does this option have a vote count yet, or is it new?
              if ($choice['votes'] != -1)
                echo ' (', $choice['votes'], ' ', $txt['votes'], ')';
              echo'
              </div>
            </div>
          </div>
        </li>';
        }

        echo '
        <li id="pollMoreOptions"></li>
      </ul>
      
      <strong><a href="javascript:addPollOption(); void(0);">(', $txt['poll_add_option'], ')</a></strong>
    </fieldset>
    
    </fieldset>

    <fieldset id="poll_options" class="mb-4">
      <legend>', $txt['poll_options'], '</legend>

      <div class="field is-horizontal">
        <div class="field-label is-narrow">
          <label class="label" for="poll_max_votes">', $txt['poll_max_votes'], '</label>
        </div>
        <div class="field-body">
          <div class="field is-narrow">
            <input type="text" name="poll_max_votes" id="poll_max_votes" size="2" value="', $context['poll']['max_votes'], '" class="input" />
          </div>
        </div>
      </div>

      <div class="field is-horizontal">
        <div class="field-label is-narrow">
          <label class="label" for="poll_expire">', $txt['poll_run'], '</label>
          <span class="is-muted">', $txt['poll_run_limit'], '</span>
        </div>
        <div class="field-body">
          <div class="field is-narrow">
            <input type="text" name="poll_max_votes" id="poll_max_votes" size="2" value="', $context['poll']['expiration'], '" class="input" />
          </div>
          <div class="field">
          ', $txt['days_word'], '
          </div>
        </div>
      </div>

      <div class="field is-horizontal">
        <div class="field-label is-narrow">
          <label class="label" for="poll_change_vote">', $txt['poll_do_change_vote'], '</label>
        </div>
        <div class="field-body">
          <div class="field is-narrow">
           <input type="checkbox" id="poll_change_vote" name="poll_change_vote"', !empty($context['poll']['change_vote']) ? ' checked="checked"' : '', ' class="input_check" />
          </div>
        </div>
      </div>';

      if ($context['poll_options']['guest_vote_enabled'])
      echo '
      <div class="field is-horizontal">
        <div class="field-label is-narrow">
          <label class="label" for="poll_guest_vote">', $txt['poll_guest_vote'], '</label>
        </div>
        <div class="field-body">
          <div class="field is-narrow">
           <input type="checkbox" id="poll_guest_vote" name="poll_guest_vote"', !empty($context['poll_options']['guest_vote']) ? ' checked="checked"' : '', ' class="input_check" />
          </div>
        </div>
      </div>';

      echo '
      <div class="field is-horizontal">
        <div class="field-label is-narrow">
          <div class="label">', $txt['poll_results_visibility'], '</div>
        </div>
        <div class="field-body">
          <div class="field">
            <label class=" is-block" for="poll_results_anyone">
              <input type="radio" name="poll_hide" id="poll_results_anyone" value="0"', $context['poll_options']['hide'] == 0 ? ' checked="checked"' : '', ' class="input_radio" />
              ', $txt['poll_results_anyone'], '
            </label>
            <label class=" is-block" for="poll_results_voted">
              <input type="radio" name="poll_hide" id="poll_results_voted" value="1"', $context['poll_options']['hide'] == 1 ? ' checked="checked"' : '', ' class="input_radio" />
              ', $txt['poll_results_voted'], '
            </label>
            <label class=" is-block" for="poll_results_expire">
              <input type="radio" name="poll_hide" id="poll_results_expire" value="2"', $context['poll_options']['hide'] == 2 ? ' checked="checked"' : '', empty($context['poll_options']['expire']) ? 'disabled="disabled"' : '', ' class="input_radio" />
              ', $txt['poll_results_after'], '
            </label>
          </div>
        </div>
      </div>
    </fieldset>';

  // If this is an edit, we can allow them to reset the vote counts.
  if ($context['is_edit'])
    echo '
          <fieldset id="poll_reset">
            <legend>', $txt['reset_votes'], '</legend>
            <input type="checkbox" name="resetVoteCount" value="on" class="input_check" /> ' . $txt['reset_votes_check'] . '
          </fieldset>';
  echo '
          <div class="mt-3">
            <input type="submit" name="post" value="', $txt['save'], '" onclick="return submitThisOnce(this);" accesskey="s" class="button is-primary" />
          </div>
        </div>
      </div>
      <input type="hidden" name="seqnum" value="', $context['form_sequence_number'], '" />
      <input type="hidden" name="' . $context['session_var'] . '" value="' . $context['session_id'] . '" />
    </form>
  </div>
  <br class="clear" />';
}

?>