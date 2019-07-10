<?php
/*
  Drafts Modification for SMF 2.0/1.1

  Created by:   Charles Hill
  Website:    http://www.degreesofzero.com/

  Copyright 2008 - 2010.  All Rights Reserved.
*/

if (!defined('SMF'))
  die('Hacking attempt...');

function template_show_drafts()
{
  global $context, $settings, $txt, $scripturl;

  echo '
    <form action="', $scripturl, '?action=profile;area=show_drafts;u=', $context['user']['id'], '" method="post" accept-charset="', $context['character_set'], '">
    <div class="cat_bar">
      <h3 class="catbg">
        <span class="ie6_header floatleft"><img class="icon" src="', $settings['images_url'], '/icons/profile_sm.gif" alt="" align="top" />&nbsp;', $txt['drafts'][2], '</span>
      </h3>
    </div>

    <table class="table is-bordered is-narrow is-striped is-fullwidth">';

  // Only show drafts if they have made some!
  if (!empty($context['list_of_drafts']))
  {
    echo '
      <tr class="titlebg">
        <th align="left">', $txt['drafts'][5], '</th>
        <th align="center">', $txt['drafts'][6], '</th>
        <th align="center">', $txt['drafts'][7], '</th>
        <th align="right">', $txt['drafts'][8], '</th>
        <th></th>
        <th></th>
        <th>
          <input type="checkbox" onclick="invertAll(this, this.form, \'drafts-delete[]\');" class="check" />
        </th>
      </tr>';

    $i = 0;

    foreach ($context['list_of_drafts'] as $id => $draft)
    {
      $i++;

      echo '
      <tr class="windowbg', $i == 1 ? '' : ($i % 2 ? '' : '2'), '">
        <td align="left">', $draft['subject'], '</td>
        <td align="center">', $draft['board']['name'], '</td>
        <td align="center">', $draft['topic']['subject'], '</td>
        <td align="right">', $draft['last_saved'], '</td>
        <td align="center"><a href="', $draft['edit'], '" title="', $txt['drafts'][9], '"><img src="', $settings['images_url'], '/icons/edit.gif" alt="', $txt['drafts'][9], '" /></a></td>
        <td align="center"><a href="', $draft['post'], '" onclick="return confirm(\'', $txt['drafts'][13], '\');">', $txt['drafts'][10], '</a></td>
        <td align="center">
          <input type="hidden" name="sc" value="', $context['session_id'], '" />
          <input type="checkbox" name="drafts-delete[]" value="', $id, '" class="check" />
        </td>
      </tr>';
    }

    echo '
      <tr class="windowbg', $i % 2 ? '2' : '', '">
        <td colspan="7" align="right">
          <input type="submit" value="', $txt['drafts'][11], '" onclick="return confirm(\'', $txt['drafts'][12], '\');" class="button_submit" />
        </td>
      </tr>';
  }
  else
    echo '
    <div class="tborder windowbg2 padding">
      ', $txt['drafts'][4], '
    </div>';

  echo '
    </table>

      <div class="mt-3 mb-3">
        <input type="submit" value="', $txt['drafts'][11], '" onclick="return confirm(\'', $txt['drafts'][12], '\');" class="button" />
      </div>
    </form>';
}

function template_drafts_post_list_of_drafts()
{
  global $context;

  if (empty($context['list_of_drafts']))
    return;

  global $txt, $scripturl, $settings;

  echo '
    <br /><br />
    <div class="cat_bar">
      <h3 class="catbg">
        <span class="ie6_header floatleft">', $txt['drafts'][3], '</span>
      </h3>
    </div>
    <form action="', $scripturl, '?action=profile;area=show_drafts;u=', $context['user']['id'], '" method="post" accept-charset="', $context['character_set'], '">
      <table border="0" width="100%" cellspacing="1" cellpadding="5" class="bordercolor" align="center">
        <tr class="titlebg">
          <th align="left">', $txt['drafts'][5], '</th>
          <th align="center">', $txt['drafts'][6], '</th>
          <th align="center">', $txt['drafts'][7], '</th>
          <th align="right">', $txt['drafts'][8], '</th>
          <th></th>
          <th>
            <input type="checkbox" onclick="invertAll(this, this.form, \'drafts-delete[]\');" class="check" />
          </th>
        </tr>';

  $i = 0;

  foreach ($context['list_of_drafts'] as $id => $draft)
  {
    $i++;

    echo '
        <tr class="windowbg', $i == 1 ? '' : ($i % 2 ? '' : '2'), '">
          <td align="left">', $draft['subject'], '</td>
          <td align="center">', $draft['board']['name'], '</td>
          <td align="center">', $draft['topic']['subject'], '</td>
          <td align="right">', $draft['last_saved'], '</td>
          <td align="center"><a href="', $draft['edit'], '" title="', $txt['drafts'][9], '"><img src="', $settings['images_url'], '/icons/edit.gif" alt="', $txt['drafts'][9], '" /></a></td>
          <td align="center">
            <input type="hidden" name="sc" value="', $context['session_id'], '" />
            <input type="checkbox" name="drafts-delete[]" value="', $id, '" class="check" />
          </td>
        </tr>';
  }

  echo '
        <tr class="windowbg', $i % 2 ? '2' : '', '">
          <td colspan="6" align="right">
            <input type="submit" value="', $txt['drafts'][11], '" onclick="return confirm(\'', $txt['drafts'][12], '\');" class="button_submit" />
          </td>
        </tr>
      </table>
    </form>
    <br /><br />';
}

function template_drafts_post_extra_inputs()
{
  global $context, $txt;

  if (!empty($context['list_of_boards']))
  {
    echo '
            <dt>
              ', $txt['drafts'][21], ':
            </dt>
            <dd>
              <select name="board">';

    foreach ($context['list_of_boards'] as $category)
    {
      echo '
                <option disabled="disabled">[', $category['name'], ']</option>';

      foreach ($category['boards'] as $board)
        echo '
                <option value="', $board['id'], '"', $board['id'] == $context['current_board'] ? ' selected="selected"' : '', '>', $board['prefix'], $board['name'], '</option>';
    }

    echo '
              </select>
            </dt>';
  }
}

function template_drafts_post_save_as_draft_button()
{
  global $context;

  if (!$context['is_new_post'] || !allowedTo('save_drafts'))
    return;

  global $txt;

  echo '
    <input type="hidden" name="drafts-save_as_draft" id="drafts-save_as_draft" value="0" />';

  if (!empty($context['draft_id']))
    echo '
    <input type="hidden" name="drafts-draft_id" value="', $context['draft_id'], '" />';

  echo '
    <input type="submit" onclick="document.getElementById(\'drafts-save_as_draft\').value = \'1\';" value="', $txt['drafts'][14], '" class="button_submit" />';
}

?>