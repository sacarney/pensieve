<?php
/**
 * Simple Machines Forum (SMF)
 *
 * @package SMF
 * @author Simple Machines
 * @copyright 2011 Simple Machines
 * @license http://www.simplemachines.org/about/smf/license.php BSD
 *
 * @version 2.0.10
 */

function template_main()
{
  global $context, $settings, $options, $txt, $scripturl, $modSettings;

  echo '
  <div class="container">

  <form action="', $scripturl, '?action=search2" method="post" accept-charset="', $context['character_set'], '" name="searchform" id="searchform">
    <div class="cat_bar">
      <h3 class="catbg"><span class="fa fa-search mr-2"></span>', $txt['set_parameters'], '</h3>
    </div>';

  if (!empty($context['search_errors']))
    echo '
    <div id="search_error" class="message is-warning">
      <div class="message-body">', implode('<br />', $context['search_errors']['messages']), '</div>
    </div>';

  // Simple Search?
  if ($context['simple_search'])
  {
    echo '
    <fieldset id="simple_search">
      <div id="search_term_input">

        <div class="field is-horizontal">
          <div class="field-label has-text-left">
            <label class="label">', $txt['search_for'], '</label>
          </div>
          <div class="field-body">
            <div class="field has-addons">
              <div class="control">
                <input type="text" name="search"', !empty($context['search_params']['search']) ? ' value="' . $context['search_params']['search'] . '"' : '', ' maxlength="', $context['search_string_limit'], '" size="40" class="input" />
              </div>
              <div class="control">
                ', $context['require_verification'] ? '' : '<input type="submit" name="submit" value="' . $txt['search'] . '" class="button is-primary" />
              </div>
            </div>
          </div>
        </div>';

        if (empty($modSettings['search_simple_fulltext']))
          echo '
          <div class="field is-horizontal">
            <div class="field-label has-text-left"></div>
            <div class="field-body">
              <p class="help">', $txt['search_example'], '</p>
            </div>
          </div>';

      echo'
      </div>';

    if ($context['require_verification'])
      echo '
        <div class="verification>
          <div class="field is-horizontal">
            <div class="field-label has-text-left">
              <label class="label">', $txt['search_visual_verification_label'], '</label>
            </div>
            <div class="field-body">
              <div class="field">', template_control_verification($context['visual_verification_id'], 'all'), '<br />
                <input id="submit" type="submit" name="submit" value="' . $txt['search'] . '" class="button is-primary" />
              </div>
            </div>
          </div>
        </div>';

    echo '
      <a class="button is-secondary is-small" href="', $scripturl, '?action=search;advanced" onclick="this.href += \';search=\' + escape(document.forms.searchform.search.value);">', $txt['search_advanced'], '</a>
      <input type="hidden" name="advanced" value="0" />
    </fieldset>';
  }

  // Advanced search!
  else
  {
    echo '
    <fieldset id="advanced_search">

      <div class="field is-horizontal">
        <div class="field-label">
          <input type="hidden" name="advanced" value="1" />
          <label class="label">', $txt['search_for'], '</label>
        </div>
        <div class="field-body">
          <div class="field has-addons">
            <div class="control">
              <input type="text" name="search"', !empty($context['search_params']['search']) ? ' value="' . $context['search_params']['search'] . '"' : '', ' maxlength="', $context['search_string_limit'], '" size="40" class="input" />
            </div>
            <div class="control">

              <script type="text/javascript"><!-- // --><![CDATA[
                function initSearch()
                {
                  if (document.forms.searchform.search.value.indexOf("%u") != -1)
                    document.forms.searchform.search.value = unescape(document.forms.searchform.search.value);
                }
                createEventListener(window);
                window.addEventListener("load", initSearch, false);
              // ]]></script>

              <div class="select">
                <select name="searchtype">
                  <option value="1"', empty($context['search_params']['searchtype']) ? ' selected="selected"' : '', '>', $txt['all_words'], '</option>
                  <option value="2"', !empty($context['search_params']['searchtype']) ? ' selected="selected"' : '', '>', $txt['any_words'], '</option>
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>';

      echo '
      <div id="search_options">

        <div class="field is-horizontal">
          <div class="field-label">
            <label class="label">', $txt['by_user'], '</label>
          </div>
          <div class="field-body">
            <div class="field">
              <div class="control">
                <input id="userspec" type="text" name="userspec" value="', empty($context['search_params']['userspec']) ? '*' : $context['search_params']['userspec'], '" size="40" class="input is-auto" />
              </div>
            </div>
          </div>
        </div>

        <div class="field is-horizontal">
          <div class="field-label">
            <label class="label">', $txt['search_order'], '</label>
          </div>
          <div class="field-body">
            <div class="field">
              <div class="control">
                <div class="select">
                  <select id="sort" name="sort">
                    <option value="relevance|desc">', $txt['search_orderby_relevant_first'], '</option>
                    <option value="num_replies|desc">', $txt['search_orderby_large_first'], '</option>
                    <option value="num_replies|asc">', $txt['search_orderby_small_first'], '</option>
                    <option value="id_msg|desc">', $txt['search_orderby_recent_first'], '</option>
                    <option value="id_msg|asc">', $txt['search_orderby_old_first'], '</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="field is-horizontal">
          <div class="field-label">
            <label class="label">', $txt['search_options'], '</label>
          </div>
          <div class="field-body">
            <div class="field">
              <div class="control">
                <label class="checkbox is-block mb-2" for="show_complete">
                  <input type="checkbox" name="show_complete" id="show_complete" value="1"', !empty($context['search_params']['show_complete']) ? ' checked="checked"' : '', ' class="input_check" /> ', $txt['search_show_complete_messages'], '
                </label>
                <label class="checkbox is-block mb-2" for="subject_only">
                  <input type="checkbox" name="subject_only" id="subject_only" value="1"', !empty($context['search_params']['subject_only']) ? ' checked="checked"' : '', ' class="input_check" /> ', $txt['search_subject_only'], '
                </label>
              </div>
            </div>
          </div>
        </div>

        <div class="field is-horizontal">
          <div class="field-label">
            <label class="label">', $txt['search_post_age'], '</label>
          </div>
          <div class="field-body">
            <div class="field is-flex" style="align-items: center;">
              <div class="control mr-2">', $txt['search_between'], '</div>
              <div class="control mr-2">
                <input type="text" name="minage" value="', empty($context['search_params']['minage']) ? '0' : $context['search_params']['minage'], '" size="5" maxlength="4" class="input is-auto" />
              </div>
              <div class="control mr-2">', $txt['search_and'], '</div>
              <div class="control mr-2">
                <input type="text" name="maxage" value="', empty($context['search_params']['maxage']) ? '9999' : $context['search_params']['maxage'], '" size="5" maxlength="4" class="input is-auto" />
              </div>
              <div class="control">', $txt['days_word'], '</div>
            </div>
          </div>
        </div>
      </div>';

    // Require an image to be typed to save spamming?
    if ($context['require_verification'])
    {
      echo '
        <p>
          <strong>', $txt['verification'], ':</strong>
          ', template_control_verification($context['visual_verification_id'], 'all'), '
        </p>';
    }

    // If $context['search_params']['topic'] is set, that means we're searching just one topic.
    if (!empty($context['search_params']['topic']))
      echo '
        <p>', $txt['search_specific_topic'], ' &quot;', $context['search_topic']['link'], '&quot;.</p>
        <input type="hidden" name="topic" value="', $context['search_topic']['id'], '" />';

    echo '
    </fieldset>';

    if (empty($context['search_params']['topic']))
    {
      echo '
      <fieldset class="flow_hidden">
        <div class="cat_bar">
          <h3 class="title is-5 mb-4">
            <a href="javascript:void(0);" onclick="expandCollapseBoards(); return false;"><span class="icon"><span class="fa fa-plus-square-o"></span></span></a>
            <a href="javascript:void(0);" onclick="expandCollapseBoards(); return false;"><strong>', $txt['choose_board'], '</strong></a>
          </h3>
        </div>
        
        <div class="columns" id="searchBoardsExpand"', $context['boards_check_all'] ? ' style="display: none;"' : '', '>
            <ul class="column">';

        $i = 0;
        $limit = ceil($context['num_boards'] / 2);
        foreach ($context['categories'] as $category)
        {
          echo '
            <li class="category">
              <a class="mb-2 mt-2" href="javascript:void(0);" onclick="selectBoards([', implode(', ', $category['child_ids']), ']); return false;">
                <span class="icon">
                  <span class="fa fa-check-square-o"></span>
                </span>
                <span>', $category['name'], '</span>
              </a>
              <ul class="ml-2">';

        foreach ($category['boards'] as $board)
        {
          if ($i == $limit)
            echo '
              </ul>
            </li>
          </ul>
          <ul class="column">
            <li class="category">
              <ul class="ml-2">';
              echo '
                <li class="board">
                  <label for="brd', $board['id'], '" style="margin-', $context['right_to_left'] ? 'right' : 'left', ': ', $board['child_level'], 'em;"><input type="checkbox" id="brd', $board['id'], '" name="brd[', $board['id'], ']" value="', $board['id'], '"', $board['selected'] ? ' checked="checked"' : '', ' class="input_check" /> ', $board['name'], '</label>
                </li>';

          $i ++;
        }

        echo '
              </ul>
            </li>';
    }

    echo '
          </ul>
        </div>';

      echo '
        <div class="level">
          <div class="level-left">
            <label class="checkbox" for="check_all" >
              <input type="checkbox" name="all" id="check_all" value=""', $context['boards_check_all'] ? ' checked="checked"' : '', ' onclick="invertAll(this, this.form, \'brd\');"  />
              ', $txt['check_all'], '
            </label>
          </div>
          <div class="level-right">
            <input type="submit" name="submit" value="', $txt['search'], '" class="button is-primary" />
          </div>
        </div>
      </div>
    </fieldset>';
    }

  }

  echo '
  </form>
  </div>

  <script type="text/javascript"><!-- // --><![CDATA[
    function selectBoards(ids)
    {
      var toggle = true;

      for (i = 0; i < ids.length; i++)
        toggle = toggle & document.forms.searchform["brd" + ids[i]].checked;

      for (i = 0; i < ids.length; i++)
        document.forms.searchform["brd" + ids[i]].checked = !toggle;
    }

    function expandCollapseBoards()
    {
      var current = document.getElementById("searchBoardsExpand").style.display != "none";

      document.getElementById("searchBoardsExpand").style.display = current ? "none" : "";
      document.getElementById("expandBoardsIcon").src = smf_images_url + (current ? "/expand.gif" : "/collapse.gif");
    }';

  echo '
  // ]]></script>';
}

function template_results()
{
  global $context, $settings, $options, $txt, $scripturl, $message;
  
  echo' <div class="container">';
  
  if (isset($context['did_you_mean']) || empty($context['topics']))
  {
    echo '
      <div id="search_results">
        <div class="cat_bar">
          <h3 class="catbg"><span class="fa fa-search mr-2"></span>', $txt['search_adjust_query'], '</h3>
        </div>';

        // Did they make any typos or mistakes, perhaps?
        if (isset($context['did_you_mean']))
          echo '
          <p class="help">', $txt['search_did_you_mean'], ' <a href="', $scripturl, '?action=search2;params=', $context['did_you_mean_params'], '">', $context['did_you_mean'], '</a>.</p>';

        echo '
        <form action="', $scripturl, '?action=search2" method="post" accept-charset="', $context['character_set'], '">

        <div class="field is-horizontal">
          <div class="field-label has-text-left">
            <label class="label">', $txt['search_for'], '</label>
          </div>
          <div class="field-body">
            <div class="field has-addons">
              <div class="control">
                <input type="text" name="search"', !empty($context['search_params']['search']) ? ' value="' . $context['search_params']['search'] . '"' : '', ' maxlength="', $context['search_string_limit'], '" size="40" class="input" />
              </div>
              <div class="control">
                <input type="submit" name="submit" value="', $txt['search_adjust_submit'], '" class="button is-primary" />
              </div>
            </div>
          </div>
        </div>

        <input type="hidden" name="searchtype" value="', !empty($context['search_params']['searchtype']) ? $context['search_params']['searchtype'] : 0, '" />
        <input type="hidden" name="userspec" value="', !empty($context['search_params']['userspec']) ? $context['search_params']['userspec'] : '', '" />
        <input type="hidden" name="show_complete" value="', !empty($context['search_params']['show_complete']) ? 1 : 0, '" />
        <input type="hidden" name="subject_only" value="', !empty($context['search_params']['subject_only']) ? 1 : 0, '" />
        <input type="hidden" name="minage" value="', !empty($context['search_params']['minage']) ? $context['search_params']['minage'] : '0', '" />
        <input type="hidden" name="maxage" value="', !empty($context['search_params']['maxage']) ? $context['search_params']['maxage'] : '9999', '" />
        <input type="hidden" name="sort" value="', !empty($context['search_params']['sort']) ? $context['search_params']['sort'] : 'relevance', '" />';

          if (!empty($context['search_params']['brd']))
            foreach ($context['search_params']['brd'] as $board_id)
              echo '
              <input type="hidden" name="brd[', $board_id, ']" value="', $board_id, '" />';

          echo '
        </form> ';
  }

  if ($context['compact'])
  {
    // Quick moderation set to checkboxes? Oh, how fun :/.
    if (!empty($options['display_quick_mod']) && $options['display_quick_mod'] == 1)
      echo '
      <form action="', $scripturl, '?action=quickmod" method="post" accept-charset="', $context['character_set'], '" name="topicForm">';

    echo '
      <div class="cat_bar">
        <h3 class="catbg is-flex">
          <span class="fa fa-search mr-2"></span>', $txt['mlist_search_results'],':&nbsp;',$context['search_params']['search'],'</span>';

            if (!empty($options['display_quick_mod']) && $options['display_quick_mod'] == 1)
            echo '
          <span class="ml-auto">
            <input type="checkbox" onclick="invertAll(this, this.form, \'topics[]\');" class="input_check" />
          </span>';
          echo '
        </h3>
      </div>';

    // Pagination
    echo '
    <div class="mb-3">
      <span class="is-muted is-size-6-5 is-uppercase">', $txt['pages'], ': &nbsp;</span>
      <span class="is-size-6-5 mr-3">', $context['page_index'], '</span>
    </div>';

    while ($topic = $context['get_topics']())
    {
      // SEARCH RESULTS
      echo '

      <div class="box">';

      foreach ($topic['matches'] as $message)
      {
        echo '
        <div class="is-flex">
          <div class="tag mr-2">', $message['counter'], '</div>
          <div>
            <h3>', $topic['board']['link'], ' / <a href="', $scripturl, '?topic=', $topic['id'], '.msg', $message['id'], '#msg', $message['id'], '">', $message['subject_highlighted'], '</a>
            </h3>
            <p class="is-size-7">',$txt['by'],'&nbsp;', $message['member']['link'], '&nbsp;',$txt['on'],'&nbsp;', $message['time'], '</p>
          </div>';

          if (!empty($options['display_quick_mod']))
          {
            echo '
            <div class="ml-auto">';

            if ($options['display_quick_mod'] == 1)
            {
              echo '
              <input type="checkbox" name="topics[]" value="', $topic['id'], '" class="input_check" />';
            }
            else
            {
              if ($topic['quick_mod']['remove'])
                echo '
              <a href="', $scripturl, '?action=quickmod;actions[', $topic['id'], ']=remove;', $context['session_var'], '=', $context['session_id'], '" onclick="return confirm(\'', $txt['quickmod_confirm'], '\');"><img src="', $settings['images_url'], '/icons/quick_remove.gif" width="16" alt="', $txt['remove_topic'], '" title="', $txt['remove_topic'], '" /></a>';

              if ($topic['quick_mod']['lock'])
                echo '
              <a href="', $scripturl, '?action=quickmod;actions[', $topic['id'], ']=lock;', $context['session_var'], '=', $context['session_id'], '" onclick="return confirm(\'', $txt['quickmod_confirm'], '\');"><img src="', $settings['images_url'], '/icons/quick_lock.gif" width="16" alt="', $txt['set_lock'], '" title="', $txt['set_lock'], '" /></a>';

              if ($topic['quick_mod']['lock'] || $topic['quick_mod']['remove'])
                echo '
              <br />';

              if ($topic['quick_mod']['sticky'])
                echo '
              <a href="', $scripturl, '?action=quickmod;actions[', $topic['id'], ']=sticky;', $context['session_var'], '=', $context['session_id'], '" onclick="return confirm(\'', $txt['quickmod_confirm'], '\');"><img src="', $settings['images_url'], '/icons/quick_sticky.gif" width="16" alt="', $txt['set_sticky'], '" title="', $txt['set_sticky'], '" /></a>';

              if ($topic['quick_mod']['move'])
                echo '
              <a href="', $scripturl, '?action=movetopic;topic=', $topic['id'], '.0"><img src="', $settings['images_url'], '/icons/quick_move.gif" width="16" alt="', $txt['move_topic'], '" title="', $txt['move_topic'], '" /></a>';
            }

            echo '
            </div>';
          }

          echo'
        </div>';

        

        if ($message['body_highlighted'] != '')
          echo '
          <hr class="mt-2">

          <div class="content">', $message['body_highlighted'], '</div>';
      }

      echo '
    </div>';

    }
    if (!empty($context['topics']))
    // Pagination
    echo '
    <div class="mb-3">
      <span class="is-muted is-size-6-5 is-uppercase">', $txt['pages'], ': &nbsp;</span>
      <span class="is-size-6-5 mr-3">', $context['page_index'], '</span>
    </div>';

    if (!empty($options['display_quick_mod']) && $options['display_quick_mod'] == 1 && !empty($context['topics']))
    {
      echo '
      <div class="middletext titlebg2" style="padding: 4px;">
        <div class="floatright">
          <div class="select">
            <select name="qaction"', $context['can_move'] ? ' onchange="this.form.moveItTo.disabled = (this.options[this.selectedIndex].value != \'move\');"' : '', '>
              <option value="">--------</option>', $context['can_remove'] ? '
              <option value="remove">' . $txt['quick_mod_remove'] . '</option>' : '', $context['can_lock'] ? '
              <option value="lock">' . $txt['quick_mod_lock'] . '</option>' : '', $context['can_sticky'] ? '
              <option value="sticky">' . $txt['quick_mod_sticky'] . '</option>' : '', $context['can_move'] ? '
              <option value="move">' . $txt['quick_mod_move'] . ': </option>' : '', $context['can_merge'] ? '
              <option value="merge">' . $txt['quick_mod_merge'] . '</option>' : '', '
              <option value="markread">', $txt['quick_mod_markread'], '</option>
            </select>
          </div>';

      if ($context['can_move'])
      {
          echo '
          <div class="select">
            <select id="moveItTo" name="move_to" disabled="disabled">';

            foreach ($context['move_to_boards'] as $category)
            {
              echo '
              <optgroup label="', $category['name'], '">';
              foreach ($category['boards'] as $board)
                  echo '
              <option value="', $board['id'], '"', $board['selected'] ? ' selected="selected"' : '', '>', $board['child_level'] > 0 ? str_repeat('==', $board['child_level'] - 1) . '=&gt;' : '', ' ', $board['name'], '</option>';
              echo '
              </optgroup>';
            }
            echo '
            </select>
          </div>';
      }

      echo '
          <input type="hidden" name="redirect_url" value="', $scripturl . '?action=search2;params=' . $context['params'], '" />
          <input type="submit" style="font-size: 0.8em;" value="', $txt['quick_mod_go'], '" onclick="return this.form.qaction.value != \'\' &amp;&amp; confirm(\'', $txt['quickmod_confirm'], '\');" class="button is-primary" />
        </div>
        <br class="clear" />
      </div>';
    }


    if (!empty($options['display_quick_mod']) && $options['display_quick_mod'] == 1 && !empty($context['topics']))
      echo '
      <input type="hidden" name="' . $context['session_var'] . '" value="' . $context['session_id'] . '" />
    </form>';

  }

  else // If they're showing full content in posts...?
  {
    echo '
    <div class="cat_bar">
      <h3 class="catbg">
        <span class="fa fa-search mr-2"></span>', $txt['mlist_search_results'],':&nbsp;',$context['search_params']['search'],'
      </h3>
    </div>';

    // Pagination
    echo '
    <div class="mb-3">
      <span class="is-muted is-size-6-5 is-uppercase">', $txt['pages'], ': &nbsp;</span>
      <span class="is-size-6-5 mr-3">', $context['page_index'], '</span>
    </div>';

    if (empty($context['topics']))
      echo '
    <div class="information">(', $txt['search_no_results'], ')</div>';

    while ($topic = $context['get_topics']())
    {
      foreach ($topic['matches'] as $message)
      {
        // OTHER SEARCH RESULTS
        echo '
      <div class="box">
        <div class="is-flex">
          <div class="tag mr-2">', $message['counter'], '</div>
          <div>
            <h3>', $topic['board']['link'], ' / <a href="', $scripturl, '?topic=', $topic['id'], '.', $message['start'], ';topicseen#msg', $message['id'], '">', $message['subject_highlighted'], '</a>
            </h3>
            <p class="is-size-7">', $txt['message'], ' ', $txt['by'], ' ', $message['member']['link'], ' ', $txt['on'], ' ', $message['time'],'</p>
          </div>
        </div>
        <hr class="mt-2">

        <div class="content">', $message['body_highlighted'], '</div>

        <hr>';

        if ($topic['can_reply'] || $topic['can_mark_notify'])
          echo '
            <div class="quickbuttons_wrap">
              <ul class="is-flex">';
                // If they *can* reply?
                if ($topic['can_reply'])
                  echo '
                        <li class="reply_button"><a class="button is-small is-secondary mr-1" href="', $scripturl . '?action=post;topic=' . $topic['id'] . '.' . $message['start'], '">', $txt['reply'], '</a></li>';

                // If they *can* quote?
                if ($topic['can_quote'])
                  echo '
                        <li class="quote_button"><a class="button is-small is-secondary mr-1" href="', $scripturl . '?action=post;topic=' . $topic['id'] . '.' . $message['start'] . ';quote=' . $message['id'] . '">', $txt['quote'], '</a></li>';

                // Can we request notification of topics?
                if ($topic['can_mark_notify'])
                  echo '
                        <li class="notify_button"><a class="button is-small is-secondary" href="', $scripturl . '?action=notify;topic=' . $topic['id'] . '.' . $message['start'], '">', $txt['notify'], '</a></li>';

                if ($topic['can_reply'] || $topic['can_mark_notify'])
                  echo '
              </ul>
            </div>';

      }
      echo'
      </div>';


    }

    // Pagination
    echo '
    <div class="mb-3">
      <span class="is-muted is-size-6-5 is-uppercase">', $txt['pages'], ': &nbsp;</span>
      <span class="is-size-6-5 mr-3">', $context['page_index'], '</span>
    </div>';
  }

}

?>