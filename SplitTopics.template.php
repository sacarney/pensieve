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

function template_ask()
{
  global $context, $settings, $options, $txt, $scripturl;

  echo '
  <div id="split_topics" class="container">
    <form action="', $scripturl, '?action=splittopics;sa=execute;topic=', $context['current_topic'], '.0" method="post" accept-charset="', $context['character_set'], '">
      <input type="hidden" name="at" value="', $context['message']['id'], '" />
      
      <div class="cat_bar">
        <h3 class="catbg">', $txt['split'], '</h3>
      </div>

      <div class="field">
        <label class="label" for="subname">', $txt['subject_new_topic'], '</label>
        <input type="text" name="subname" id="subname" value="', $context['message']['subject'], '" size="25" class="input" />
      </div>

      <div class="is-block mb-2">
        <label class="radio" for="onlythis">
          <input type="radio" id="onlythis" name="step2" value="onlythis" checked="checked" class="input_radio" />
          ', $txt['split_this_post'], '
        </label>
      </div>
      <div class="is-block mb-2">
        <label class="radio" for="afterthis">
          <input type="radio" id="afterthis" name="step2" value="afterthis" class="input_radio" />
          ', $txt['split_after_and_this_post'], '
        </label>
      </div>
      <div class="is-block">
        <label class="radio" for="selective">
          <input type="radio" id="selective" name="step2" value="selective" class="input_radio" />
          ', $txt['select_split_posts'], '
        </label>
      </div>

      <div class="mt-3">
        <input type="submit" value="', $txt['split'], '" class="button_submit" />
        <input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
      </div>
    </form>
  </div>';
}

function template_main()
{
  global $context, $settings, $options, $txt, $scripturl;

  echo '
  <div id="split_topics" class="container">

    <div class="cat_bar">
      <h3 class="catbg">', $txt['split'], '</h3>
    </div>
      
    <div class="message is-success">
      <div class="message-body">', $txt['split_successful'], '</div>
    </div>

    <ul>
      <li>
        <a href="', $scripturl, '?board=', $context['current_board'], '.0">', $txt['message_index'], '</a>
      </li>
      <li>
        <a href="', $scripturl, '?topic=', $context['old_topic'], '.0">', $txt['origin_topic'], '</a>
      </li>
      <li>
        <a href="', $scripturl, '?topic=', $context['new_topic'], '.0">', $txt['new_topic'], '</a>
      </li>
    </ul>
  </div>';
}

function template_select()
{
  global $context, $settings, $options, $txt, $scripturl;

  echo '
  <div id="split_topics" class="container">
    <form action="', $scripturl, '?action=splittopics;sa=splitSelection;board=', $context['current_board'], '.0" method="post" accept-charset="', $context['character_set'], '">
      
      <div id="not_selected" class="columns">
        <div class="column">

          <div class="cat_bar">
            <h3 class="catbg">', $txt['split'], ' - ', $txt['select_split_posts'], '</h3>
          </div>

          <div class="notification is-size-6-5 p-2">
            ', $txt['please_select_split'], '
          </div>';

          // Pagination
          echo'
          <div class="mb-3">
            <span class="is-muted is-size-6-5 is-uppercase">', $txt['pages'],': &nbsp;</span>
            <span class="is-size-6-5 mr-3" id="pageindex_not_selected">', $context['not_selected']['page_index'], '</span>
          </div>';

          echo'

          <ul id="messages_not_selected" class="list-unstyled">';

          foreach ($context['not_selected']['messages'] as $message)
          echo '
            <li id="not_selected_', $message['id'], '">

              <div class="box mb-3">
                <div class="is-flex">
                  <div class="flex-grow-1">
                    <span>', $message['subject'], '</span>
                    <br>
                    <span class="is-muted is-uppercase is-size-7">', $txt['by'], '</span>
                    <span>', $message['poster'], '</span>
                    <span>', $txt['on'], '</span>
                    <span class="is-muted is-uppercase is-size-7">', $message['time'], '</span>
                  </div>
                  <div>
                    <a class="button is-small" href="', $scripturl, '?action=splittopics;sa=selectTopics;subname=', $context['topic']['subject'], ';topic=', $context['topic']['id'], '.', $context['not_selected']['start'], ';start2=', $context['selected']['start'], ';move=down;msg=', $message['id'], '" onclick="return select(\'down\', ', $message['id'], ');">
                      <span><span class="fa fa-arrow-right"></span></span>
                      <span class="sr-only">Select</span>
                    </a>

                  </div>
                </div>
                <hr class="mt-2">
              
                <div class="content">', $message['body'], '</div>
              </div>
            </li>';

            echo'
            <li class="dummy" />
          </ul>

        </div>

        <div id="selected" class="column">

          <div class="cat_bar">
            <h3 class="catbg">
              ', $txt['split_selected_posts'], ' (<a href="', $scripturl, '?action=splittopics;sa=selectTopics;subname=', $context['topic']['subject'], ';topic=', $context['topic']['id'], '.', $context['not_selected']['start'], ';start2=', $context['selected']['start'], ';move=reset;msg=0" onclick="return select(\'reset\', 0);">', $txt['split_reset_selection'], '</a>)
            </h3>
          </div>

          <div class="notification is-size-6-5 p-2">
            ', $txt['split_selected_posts_desc'], '
          </div>';

          // Pagination
          echo'
          <div class="mb-3">
            <span class="is-muted is-size-6-5 is-uppercase">', $txt['pages'],': &nbsp;</span>
            <span class="is-size-6-5 mr-3" id="pageindex_selected">', $context['selected']['page_index'], '</span>
          </div>';

          echo'

          <ul id="messages_selected" class="list-unstyled">';

          if (!empty($context['selected']['messages']))
          foreach ($context['selected']['messages'] as $message)
            echo '

            <li id="selected_', $message['id'], '">

              <div class="box mb-3">
                <div class="is-flex">
                  <div class="flex-grow-1">
                    <span>', $message['subject'], '</span>
                    <br>
                    <span class="is-muted is-uppercase is-size-7">', $txt['by'], '</span>
                    <span>', $message['poster'], '</span>
                    <span>', $txt['on'], '</span>
                    <span class="is-muted is-uppercase is-size-7">', $message['time'], '</span>
                  </div>
                  <div>
                    <a class="button is-small" href="', $scripturl, '?action=splittopics;sa=selectTopics;subname=', $context['topic']['subject'], ';topic=', $context['topic']['id'], '.', $context['not_selected']['start'], ';start2=', $context['selected']['start'], ';move=up;msg=', $message['id'], '" onclick="return select(\'up\', ', $message['id'], ');">
                      <span><span class="fa fa-arrow-left"></span></span>
                      <span class="sr-only">Un-select</span>
                    </a>

                  </div>
                </div>
                <hr class="mt-2">
              
                <div class="content">', $message['body'], '</div>
              </div>
            </li>';
          
            echo'
            <li class="dummy" />
          </ul>
        </div>
      </div>

      <div class="mt-3">
        <input type="hidden" name="topic" value="', $context['current_topic'], '" />
        <input type="hidden" name="subname" value="', $context['new_subject'], '" />
        <input type="submit" value="', $txt['split'], '" class="button_submit" />
        <input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
      </div>
    </form>
  </div>

  <br class="clear" />
    <script type="text/javascript"><!-- // --><![CDATA[
      var start = new Array();
      start[0] = ', $context['not_selected']['start'], ';
      start[1] = ', $context['selected']['start'], ';

      function select(direction, msg_id)
      {
        if (window.XMLHttpRequest)
        {
          getXMLDocument(smf_prepareScriptUrl(smf_scripturl) + "action=splittopics;sa=selectTopics;subname=', $context['topic']['subject'], ';topic=', $context['topic']['id'], '." + start[0] + ";start2=" + start[1] + ";move=" + direction + ";msg=" + msg_id + ";xml", onDocReceived);
          return false;
        }
        else
          return true;
      }
      function applyWindowClasses(oList)
      {
        var bAlternate = false;
        oListItems = oList.getElementsByTagName("LI");
        for (i = 0; i < oListItems.length; i++)
        {
          // Skip dummies.
          if (oListItems[i].id == "")
            continue;
          oListItems[i].className = "windowbg" + (bAlternate ? "2" : "");
          bAlternate = !bAlternate;
        }
      }
      function onDocReceived(XMLDoc)
      {
        var i, j, pageIndex;
        for (i = 0; i < 2; i++)
        {
          pageIndex = XMLDoc.getElementsByTagName("pageIndex")[i];
          setInnerHTML(document.getElementById("pageindex_" + pageIndex.getAttribute("section")), pageIndex.firstChild.nodeValue);
          start[i] = pageIndex.getAttribute("startFrom");
        }
        var numChanges = XMLDoc.getElementsByTagName("change").length;
        var curChange, curSection, curAction, curId, curList, curData, newItem, sInsertBeforeId;
        for (i = 0; i < numChanges; i++)
        {
          curChange = XMLDoc.getElementsByTagName("change")[i];
          curSection = curChange.getAttribute("section");
          curAction = curChange.getAttribute("curAction");
          curId = curChange.getAttribute("id");
          curList = document.getElementById("messages_" + curSection);
          if (curAction == "remove")
            curList.removeChild(document.getElementById(curSection + "_" + curId));
          // Insert a message.
          else
          {
            // By default, insert the element at the end of the list.
            sInsertBeforeId = null;
            // Loop through the list to try and find an item to insert after.
            oListItems = curList.getElementsByTagName("LI");
            for (j = 0; j < oListItems.length; j++)
            {
              if (parseInt(oListItems[j].id.substr(curSection.length + 1)) < curId)
              {
                // This would be a nice place to insert the row.
                sInsertBeforeId = oListItems[j].id;
                // We\'re done for now. Escape the loop.
                j = oListItems.length + 1;
              }
            }

            // Let\'s create a nice container for the message.
            newItem = document.createElement("LI");
            newItem.className = "windowbg2";
            newItem.id = curSection + "_" + curId;
            newItem.innerHTML = "<div class=\\"box mb-3\\"><div class=\\"is-flex\\"><div class=\\"flex-grow-1\\"><span>', $message['subject'], '</span><br><span class=\\"is-muted is-uppercase is-size-7\\">', $txt['by'], '</span><span>', $message['poster'], '</span><span>', $txt['on'], '</span><span class=\\"is-muted is-uppercase is-size-7\\">', $message['time'], '</span></div><div><a class=\\"button is-small\\" href=\\"', $scripturl, '?action=splittopics;sa=selectTopics;subname=', $context['topic']['subject'], ';topic=', $context['topic']['id'], '.', $context['not_selected']['start'], ';start2=', $context['selected']['start'], ';move=up;msg=', $message['id'], '\\" onclick=\\"return select(\'up\', ', $message['id'], ');\\"><span><span class=\\"fa fa-arrow-left\\"></span></span><span class=\\"sr-only\\">Un-select</span></a></div></div><hr class=\\"mt-2\\"><div class="content">', $message['body'], '</div></div>"

            // So, where do we insert it?
            if (typeof sInsertBeforeId == "string")
              curList.insertBefore(newItem, document.getElementById(sInsertBeforeId));
            else
              curList.appendChild(newItem);
          }
        }
        // After all changes, make sure the window backgrounds are still correct for both lists.
        applyWindowClasses(document.getElementById("messages_selected"));
        applyWindowClasses(document.getElementById("messages_not_selected"));
      }
    // ]]></script>';
}

function template_merge_done()
{
  global $context, $settings, $options, $txt, $scripturl;

  echo '
    <div id="merge_topics" class="container">
      <div class="cat_bar">
        <h3 class="catbg">', $txt['merge'], '</h3>
      </div>

      <div class="message is-success">
        <div class="message-body">', $txt['merge_successful'], '</div>
      </div>

      <ul>
        <li>
          <a href="', $scripturl, '?board=', $context['target_board'], '.0">', $txt['message_index'], '</a>
        </li>
        <li>
          <a href="', $scripturl, '?topic=', $context['target_topic'], '.0">', $txt['new_merged_topic'], '</a>
        </li>
      </ul>
    </div>';
}

// TODO
function template_merge()
{
  global $context, $settings, $options, $txt, $scripturl;

  echo '
    <div id="merge_topics" class="container">

      <div class="cat_bar">
        <h3 class="catbg">', $txt['merge'], '</h3>
      </div>

      <div class="notification is-size-6-5 p-2">
        ', $txt['merge_desc'], '
      </div>

      <div class="field is-horizontal">
        <div class="field-label has-text-left">
          <label class="label">
            ', $txt['topic_to_merge'], '</label>
        </div>
        <div class="field-body">
          <div class="field">
            ', $context['origin_subject'], '
          </div>
        </div>
      </div>';

      if (!empty($context['boards']) && count($context['boards']) > 1)
      {
        echo '
          <div class="field is-horizontal">
            <div class="field-label has-text-left">
              <label class="label">', $txt['target_board'], '</label>
            </div>
            <div class="field-body">
              <form action="' . $scripturl . '?action=mergetopics;from=' . $context['origin_topic'] . ';targetboard=' . $context['target_board'] . ';board=' . $context['current_board'] . '.0" method="post" accept-charset="', $context['character_set'], '">

                <input type="hidden" name="from" value="' . $context['origin_topic'] . '" />
                
                <div class="select">
                  <select name="targetboard" onchange="this.form.submit();">';
                    foreach ($context['boards'] as $board)
                      echo '
                    <option value="', $board['id'], '"', $board['id'] == $context['target_board'] ? ' selected="selected"' : '', '>', $board['category'], ' - ', $board['name'], '</option>';
                    echo '
                  </select>
                </div>
                <input type="submit" value="', $txt['go'], '" class="button_submit" />
              </form>
            </div>
          </div>';
      }

      echo '

      <div class="field is-horizontal">
        <div class="field-label">
          <label class="label has-text-left">', $txt['merge_to_topic_id'], '</label>
        </div>

        <div class="field-body">
          <form action="', $scripturl , '?action=mergetopics;sa=options" method="post" accept-charset="', $context['character_set'], '">
            <input type="hidden" name="topics[]" value="', $context['origin_topic'], '" />
            <input type="text" name="topics[]" class="input is-auto" />
            <input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
            <input type="submit" value="', $txt['merge'], '" class="button" />
          </form>
        </div>
      </div>';

    echo '

      <div class="cat_bar mt-4">
        <h3 class="catbg">', $txt['target_topic'], '</h3>
      </div>';

      // Pagination
      echo'
      <div class="mb-3">
        <span class="is-muted is-size-6-5 is-uppercase">', $txt['pages'],': &nbsp;</span>
        <span class="is-size-6-5 mr-3">', $context['page_index'], '</span>
      </div>';

      echo'
      
      <ul>';

      $merge_button = create_button('merge.gif', 'merge', '');

      foreach ($context['topics'] as $topic)
        echo '
          <li>
            <a href="', $scripturl, '?action=mergetopics;sa=options;board=', $context['current_board'], '.0;from=', $context['origin_topic'], ';to=', $topic['id'], ';', $context['session_var'], '=', $context['session_id'], '">', $merge_button, '</a>&nbsp;
            <a href="', $scripturl, '?topic=', $topic['id'], '.0" target="_blank" class="new_win">', $topic['subject'], '</a> <span class="is-muted is-size-6-5 is-uppercase">', $txt['started_by'], '</span> ', $topic['poster']['link'], '
          </li>';

        echo '
          </ul>';

      // Pagination
      echo'
      <div class="mb-3 mt-3">
        <span class="is-muted is-size-6-5 is-uppercase">', $txt['pages'],': &nbsp;</span>
        <span class="is-size-6-5 mr-3">', $context['page_index'], '</span>
      </div>';

      echo'
    </div>';
}

function template_merge_extra_options()
{
  global $context, $settings, $options, $txt, $scripturl;

  echo '
  <div id="merge_topics" class="container">
    <form action="', $scripturl, '?action=mergetopics;sa=execute;" method="post" accept-charset="', $context['character_set'], '">
    
      <div class="cat_bar">
        <h3 class="catbg">', $txt['merge_topic_list'], '</h3>
      </div>

      <table class="table is-fullwidth is-striped">
        <thead>
          <tr class="catbg">
            <th scope="col" class="first_th" align="center" width="10px">', $txt['merge_check'], '</th>
            <th scope="col">', $txt['subject'], '</th>
            <th scope="col">', $txt['started_by'], '</th>
            <th scope="col">', $txt['last_post'], '</th>
            <th scope="col">' . $txt['merge_include_notifications'] . '</th>
          </tr>
        </thead>
        <tbody>';
        foreach ($context['topics'] as $topic)
        echo '
          <tr class="windowbg2">
            <td class="has-text-centered">
              <input type="checkbox" name="topics[]" value="' . $topic['id'] . '" checked="checked" />
            </td>
            <td>
              <a href="' . $scripturl . '?topic=' . $topic['id'] . '.0" target="_blank" class="new_win">' . $topic['subject'] . '</a>
            </td>
            <td>
              ', $topic['started']['link'], '<br />
              <span class="is-size-6-5">', $topic['started']['time'], '</span>
            </td>
            <td>
              ' . $topic['updated']['link'] . '<br />
              <span class="is-size-6-5">', $topic['updated']['time'], '</span>
            </td>
            <td class="has-text-centered">
              <input type="checkbox" name="notifications[]" value="' . $topic['id'] . '" checked="checked" />
            </td>
          </tr>';
        echo '
        </tbody>
      </table>';

    echo '
      <fieldset id="merge_subject" class="mb-3">
        <legend>', $txt['merge_select_subject'], '</legend>

        <div class="field">
          <div class="select">
            <select name="subject" onchange="this.form.custom_subject.style.display = (this.options[this.selectedIndex].value != 0) ? \'none\': \'\' ;">';
              foreach ($context['topics'] as $topic)
                echo '
                <option value="', $topic['id'], '"' . ($topic['selected'] ? ' selected="selected"' : '') . '>', $topic['subject'], '</option>';
              echo '
                <option value="0">', $txt['merge_custom_subject'], ':</option>
            </select>
          </div>
        </div>
        
        <div class="field mt-3">
          <input type="text" name="custom_subject" size="60" id="custom_subject" class="input" style="display: none;" />
        </div>

        <div class="field">
          <label class="checkbox" for="enforce_subject">
            <input type="checkbox" class="input_check" name="enforce_subject" id="enforce_subject" value="1" /> ', $txt['merge_enforce_subject'], '
          </label>
        </div>
      </fieldset>';

  if (!empty($context['boards']) && count($context['boards']) > 1)
  {
    echo '
      <fieldset id="merge_board">
        <legend>', $txt['merge_select_target_board'], '</legend>
        <ul class="reset">';
        foreach ($context['boards'] as $board)
          echo '
          <li>
            <label class="radio">
              <input type="radio" name="board" value="' . $board['id'] . '"' . ($board['selected'] ? ' checked="checked"' : '') . ' class="input_radio" /> ' . $board['name'] . '
            </label>
          </li>';
        echo '
        </ul>
      </fieldset>';
  }
  if (!empty($context['polls']))
  {
    echo '
      <fieldset id="merge_poll">
        <legend>' . $txt['merge_select_poll'] . '</legend>
        <ul class="reset">';
        foreach ($context['polls'] as $poll)
        echo '
          <li>
            <input type="radio" name="poll" value="' . $poll['id'] . '"' . ($poll['selected'] ? ' checked="checked"' : '') . ' class="input_radio" /> ' . $poll['question'] . ' (' . $txt['topic'] . ': <a href="' . $scripturl . '?topic=' . $poll['topic']['id'] . '.0" target="_blank" class="new_win">' . $poll['topic']['subject'] . '</a>)
          </li>';
        echo '
          <li>
            <input type="radio" name="poll" value="-1" class="input_radio" /> (' . $txt['merge_no_poll'] . ')
          </li>
        </ul>
      </fieldset>';
  }
  echo '

      <div class="mt-3">
        <input type="submit" value="' . $txt['merge'] . '" class="button_submit floatright" />
        <input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
        <input type="hidden" name="sa" value="execute" /><br class="clear" />
      </div>
    </form>
  </div>';
}

?>