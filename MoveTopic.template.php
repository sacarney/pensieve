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

// Show an interface for selecting which board to move a post to.
function template_main()
{
  global $context, $settings, $options, $txt, $scripturl;

  echo '
  <div id="move_topic" class="container">
    <form action="', $scripturl, '?action=movetopic2;topic=', $context['current_topic'], '.0" method="post" accept-charset="', $context['character_set'], '" onsubmit="submitonce(this);">
      <div class="cat_bar">
        <h3 class="catbg">', $txt['move_topic'], '</h3>
      </div>

      <div class="field is-horizontal">
        <div class="field-label has-text-left">
          <label class="label" for="toboard">', $txt['move_to'], '</label>
        </div>
        <div class="field-body">
          <div class="field">
            <div class="select">
              <select name="toboard">';
                foreach ($context['categories'] as $category)
                  {
                    echo '
                      <optgroup label="', $category['name'], '">';

                    foreach ($category['boards'] as $board)
                      echo '
                        <option value="', $board['id'], '"', $board['selected'] ? ' selected="selected"' : '', $board['id'] == $context['current_board'] ? ' disabled="disabled"' : '', '>', $board['child_level'] > 0 ? str_repeat('==', $board['child_level']-1) . '=&gt; ' : '', $board['name'], '</option>';
                    echo '
                                  </optgroup>';
                  }
              echo'
              </select>
            </div>
          </div>
        </div>
      </div>';

  // Disable the reason textarea when the postRedirect checkbox is unchecked...
  echo '
    <hr>
    <label for="reset_subject" class="checkbox">
      <input type="checkbox" name="reset_subject" id="reset_subject" onclick="document.getElementById(\'subjectArea\').style.display = this.checked ? \'block\' : \'none\';" class="input_check" /> ', $txt['moveTopic2'], '.
    </label>

    <fieldset id="subjectArea" style="display: none;" class="mt-3">
      
      <div class="field is-horizontal">
        <div class="field-label has-text-left">
          <label class="label" for="custom_subject">
            ', $txt['moveTopic3'], '</label>
        </div>
        <div class="field-body">
          <div class="field">
            <input type="text" name="custom_subject" size="30" value="', $context['subject'], '" class="input" />
          </div>
        </div>
      </div>

      <div class="field is-horizontal">
        <div class="field-label"></div>
        <div class="field-body">
          <div class="field">
            <label for="enforce_subject" class="checkbox">
            <input type="checkbox" name="enforce_subject" id="enforce_subject" class="input_check" /> ', $txt['moveTopic4'], '.
          </label>
        </div>
      </div>
    </fieldset>
    <hr>

    <label for="postRedirect" class="checkbox">
      <input type="checkbox" name="postRedirect" id="postRedirect" ', $context['is_approved'] ? 'checked="checked"' : '', ' onclick="', $context['is_approved'] ? '' : 'if (this.checked && !confirm(\'' . $txt['move_topic_unapproved_js'] . '\')) return false; ', 'document.getElementById(\'reasonArea\').style.display = this.checked ? \'block\' : \'none\';" class="" /> 
        ', $txt['moveTopic1'], '.
    </label>
    
    <fieldset id="reasonArea" style="margin-top: 1ex;', $context['is_approved'] ? '' : 'display: none;', '" class="mt-3">

      <div class="field">
        <label class="mb-3 is-block" for="reason">', $txt['moved_why'], '</label>
        <div class="control">
          <textarea class="textarea is-auto" name="reason" rows="3" cols="40">', $txt['movetopic_default'], '</textarea>
        </div>
      </div>
    </fieldset>

      <div class="mt-3">
        <input type="submit" value="', $txt['move_topic'], '" onclick="return submitThisOnce(this);" accesskey="s" class="button is-primary" />
      </div>

';

  if ($context['back_to_topic'])
    echo '
      <input type="hidden" name="goback" value="1" />';

  echo '
      <input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
      <input type="hidden" name="seqnum" value="', $context['form_sequence_number'], '" />
    </form>
  </div>';
}

?>