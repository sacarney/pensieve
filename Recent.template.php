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
  /* ?action=recent */

  echo '
  <div id="recent" class="container">';

  // Page Title
    echo'
    <div class="cat_bar">
      <h2 class="title is-5 mb-4">
        <span class="icon">
          <span class="fa fa-clock-o"></span>
        </span> ',$txt['recent_posts'],'
      </h2>
    </div>';

    // Pagination
    echo '
    <div class="mb-3">
      <span class="is-muted is-size-6-5 is-uppercase">', $txt['pages'], ': &nbsp;</span>
      <span class="is-size-6-5 mr-3">', $context['page_index'], '</span>
    </div>';

    // Posts
  foreach ($context['posts'] as $post)
  {
    echo'
    <div class="box">
      <h3>', $post['board']['link'], ' / ', $post['link'], '
        <br>
        <div class="is-size-6-5">
       ', $txt['last_post'], ' ', $txt['by'], ' <strong>', $post['poster']['link'], ' </strong> ', $txt['on'], ' ', $post['time'], ' 
        </div>
      </h3>

      <hr>
      <div class="list_posts">', $post['message'], '</div>
      <hr>';

      // Buttons
      if ($post['can_reply'] || $post['can_mark_notify'] || $post['can_delete'])
        echo'
        <div role="toolbar" aria-label="Recent post toolbar.">';

        if ($post['can_reply'])
          echo'
          <a class="button is-small mr-1" href="', $scripturl, '?action=post;topic=', $post['topic'], '.', $post['start'], '">
            <span class="icon m-0">
              <span class="fa fa-reply"></span>
            </span>
            <span class="is-hidden-touch ml-1">', $txt['reply'], '</span>
          </a>
        ';

        if ($post['can_quote'])
          echo'
          <a class="button is-small mr-1" href="', $scripturl, '?action=post;topic=', $post['topic'], '.', $post['start'], ';quote=', $post['id'], '">
            <span class="icon m-0">
              <span class="fa fa-quote-left"></span>
            </span>
            <span class="is-hidden-touch ml-1">', $txt['quote'], '</span>
          </a>
        ';

        if ($post['can_mark_notify'])
          echo'
          <a class="button is-small mr-1" href="', $scripturl, '?action=notify;topic=', $post['topic'], '.', $post['start'], '">
            <span class="icon m-0">
              <span class="fa fa-bell"></span>
            </span>
            <span class="is-hidden-touch ml-1">', $txt['notify'], '</span>
          </a>
        ';

        if ($post['can_delete'])
          echo'
          <a class="button is-small mr-1" href="', $scripturl, '?action=deletemsg;msg=', $post['id'], ';topic=', $post['topic'], ';recent;', $context['session_var'], '=', $context['session_id'], '" onclick="return confirm(\'', $txt['remove_message'], '?\');">
            <span class="icon m-0">
              <span class="fa fa-times"></span>
            </span>
            <span class="is-hidden-touch ml-1">', $txt['remove'], '</span>
          </a>
        ';

      if ($post['can_reply'] || $post['can_mark_notify'] || $post['can_delete'])
        echo'
        </div>

      ';

      echo'
    </div>
    ';

  }

  // Pagination
  echo '
    <div class="mb-3">
      <span class="is-muted is-size-6-5 is-uppercase">', $txt['pages'], ': &nbsp;</span>
      <span class="is-size-6-5 mr-3">', $context['page_index'], '</span>
    </div>';
}

function template_unread()
{
  global $context, $settings, $options, $txt, $scripturl, $modSettings;
  /* ?action=unread */

  echo '
  <div id="recent" class="main_content">';

  $showCheckboxes = !empty($options['display_quick_mod']) && $options['display_quick_mod'] == 1 && $settings['show_mark_read'];

  if ($showCheckboxes)
    echo '
    <form action="', $scripturl, '?action=quickmod" method="post" accept-charset="', $context['character_set'], '" name="quickModForm" id="quickModForm" style="margin: 0;">
      <input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
      <input type="hidden" name="qaction" value="markread" />
      <input type="hidden" name="redirect_url" value="action=unread', (!empty($context['showing_all_topics']) ? ';all' : ''), $context['querystring_board_limits'], '" />';

  if ($settings['show_mark_read'])
  {
    // Generate the button strip.
    $mark_read = array(
      'markread' => array(
        'text' => !empty($context['no_board_limits']) ? 'mark_as_read' : 'mark_read_short', 
        'image' => 'markread.gif', 
        'lang' => true, 
        'icon' => 'fa-check',
        'url' => $scripturl . '?action=markasread;sa=' . (!empty($context['no_board_limits']) ? 'all' : 'board' . $context['querystring_board_limits']) . ';' . $context['session_var'] . '=' . $context['session_id']),
    );

    if ($showCheckboxes)
      $mark_read['markselectread'] = array(
        'text' => 'quick_mod_markread',
        'image' => 'markselectedread.gif',
        'icon' => 'fa-check-square-o',
        'lang' => true,
        'url' => 'javascript:document.quickModForm.submit();',
      );
  }

  if (!empty($context['topics']))
  {
    echo '
      <div class=" container">';

    if (!empty($mark_read) && !empty($settings['use_tabs']))
      template_button_strip($mark_read, 'right');

    // Pagination
    echo '
      <div class="mt-3">
        <span class="is-muted is-size-6-5 is-uppercase">', $txt['pages'], ': &nbsp;</span>
        <span class="is-size-6-5 mr-3">', $context['page_index'], '</span>
      </div>';

    // Topics Table
    echo '
      <div id="unread">
        <table class="table is-fullwidth mb-4 mt-4 responsive-table" cellspacing="0">
          <thead>
            <tr>
              <th scope="col" class="is-hidden-mobile"><span class="sr-only">Message icon</span></th>
              <th scope="col">
                <a href="', $scripturl, '?action=unread', $context['showing_all_topics'] ? ';all' : '', $context['querystring_board_limits'], ';sort=subject', $context['sort_by'] == 'subject' && $context['sort_direction'] == 'up' ? ';desc' : '', '">', $txt['subject'], $context['sort_by'] == 'subject' ? ' <img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" />' : '', '</a>
              </th>
              <th scope="col" width="14%" class="is-hidden-mobile">
                <a href="', $scripturl, '?action=unread', $context['showing_all_topics'] ? ';all' : '', $context['querystring_board_limits'], ';sort=replies', $context['sort_by'] == 'replies' && $context['sort_direction'] == 'up' ? ';desc' : '', '">', $txt['replies'], $context['sort_by'] == 'replies' ? ' <img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" />' : '', '</a>
              </th>';

            // Show a "select all" box for quick moderation?
            if ($showCheckboxes)
              echo '
              <th scope="col" width="22%">
                <a href="', $scripturl, '?action=unread', $context['showing_all_topics'] ? ';all' : '', $context['querystring_board_limits'], ';sort=last_post', $context['sort_by'] == 'last_post' && $context['sort_direction'] == 'up' ? ';desc' : '', '">', $txt['last_post'], $context['sort_by'] == 'last_post' ? ' <img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" />' : '', '</a>
              </th>
              <th>
                <input type="checkbox" onclick="invertAll(this, this.form, \'topics[]\');" class="input_check" />
              </th>';
            else
              echo '
              <th scope="col"width="22%">
                <a href="', $scripturl, '?action=unread', $context['showing_all_topics'] ? ';all' : '', $context['querystring_board_limits'], ';sort=last_post', $context['sort_by'] == 'last_post' && $context['sort_direction'] == 'up' ? ';desc' : '', '">', $txt['last_post'], $context['sort_by'] == 'last_post' ? ' <img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" />' : '', '</a>
              </th>';
            echo '
            </tr>
          </thead>
          <tbody>';

    foreach ($context['topics'] as $topic)
    {
      // Calculate the color class of the topic.
      $color_class = '';
      if (strpos($topic['class'], 'sticky') !== false)
        $color_class = 'stickybg';
      if (strpos($topic['class'], 'locked') !== false)
        $color_class .= 'lockedbg';

      $color_class2 = !empty($color_class) ? $color_class . '2' : '';

            echo '
            <tr>
              <td class="', $color_class, ' icon2 windowbg is-hidden-mobile">
                <img src="', $topic['first_post']['icon_url'], '" alt="" />
              </td>

              <td class="subject ', $color_class2, ' windowbg2">
                <div>
                  ', $topic['is_sticky'] ? '<strong>' : '', '<span id="msg_' . $topic['first_post']['id'] . '">', $topic['first_post']['link'], '</span>', $topic['is_sticky'] ? '</strong>' : '', '
                  <a href="', $topic['new_href'], '" id="newicon', $topic['first_post']['id'], '"><img src="', $settings['lang_images_url'], '/new.gif" alt="', $txt['new'], '" /></a>
                  <p>
                    <span class="is-muted is-uppercase is-size-7">', $txt['started_by'], '</span> ', $topic['first_post']['member']['link'], '
                    <span class="is-muted is-uppercase is-size-7">', $txt['in'], '</span> ', $topic['board']['link'], '
                  </p>
                </div>
              </td>

              <td class="', $color_class, ' stats windowbg is-hidden-mobile">
                <p class="is-uppercase is-size-7"><span>', $topic['replies'], ' ', $txt['replies'], '</span></p>
                <p class="is-uppercase is-size-7"><span>', $topic['views'], ' ', $txt['views'], '</span></p>
              </td>

              <td class="', $color_class2, ' lastpost windowbg2">
                <p class="is-size-6-5">
                  <span class="is-muted is-uppercase is-size-7">', $txt['by'], '</span> ', $topic['last_post']['member']['link'], '<br>
                  <span class="is-muted is-uppercase is-size-7">', $txt['on'], '</span> ', $topic['last_post']['time'], '
                  <a href="', $topic['last_post']['href'], '" class="view-last-post">
                    <span class="icon has-text-primary">
                      <i class="fa fa-angle-double-right"></i>
                    </span>
                    <span class="sr-only"> View last post</span>
                  </a>
                </p>
                
              </td>';

            if ($showCheckboxes)
              echo '
              <td class="windowbg2" valign="middle" align="center">
                <input type="checkbox" name="topics[]" value="', $topic['id'], '" class="input_check" />
              </td>';
            echo '
            </tr>';
    }

    if (!empty($context['topics']) && !$context['showing_all_topics'])
      $mark_read['readall'] = array(
        'text' => 'unread_topics_all', 
        'image' => 'markreadall.gif', 
        'icon' => 'fa-star-o',
        'lang' => true, 
        'url' => $scripturl . '?action=unread;all' . $context['querystring_board_limits'], 
        'active' => true
      );

          if (empty($settings['use_tabs']) && !empty($mark_read))
            echo '
            <tr class="catbg">
              <td colspan="', $showCheckboxes ? '6' : '5', '" align="right">
                ', template_button_strip($mark_read, 'top'), '
              </td>
            </tr>';

        if (empty($context['topics']))
          echo '
          <tr style="display: none;"><td></td></tr>';

          echo '
          </tbody>
        </table>
      </div>';

      // Bottom Buttons and pagination
      echo'
      <div class="pagesection container" id="readbuttons">';

      if (!empty($settings['use_tabs']) && !empty($mark_read))
        template_button_strip($mark_read, 'right');

      // Pagination
      echo '
        <div class="mb-3 mt-3">
          <span class="is-muted is-size-6-5 is-uppercase">', $txt['pages'], ': &nbsp;</span>
          <span class="is-size-6-5 mr-3">', $context['page_index'], '</span>
        </div>
      </div>';
  }

  else
    // If no topics
    echo '
      <div class="cat_bar">
        <h3 class="catbg centertext">
          ', $context['showing_all_topics'] ? $txt['msg_alert_none'] : $txt['unread_topics_visit_none'], '
        </h3>
      </div>';

  if ($showCheckboxes)
    echo '
    </form>';

  echo '
  </div>';
}

function template_replies()
{
  global $context, $settings, $options, $txt, $scripturl, $modSettings;

  /* ?action=unreadreplies */
  echo '
  <div id="recent">';

  $showCheckboxes = !empty($options['display_quick_mod']) && $options['display_quick_mod'] == 1 && $settings['show_mark_read'];

  if ($showCheckboxes)
    echo '
    <form action="', $scripturl, '?action=quickmod" method="post" accept-charset="', $context['character_set'], '" name="quickModForm" id="quickModForm" style="margin: 0;">
      <input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
      <input type="hidden" name="qaction" value="markread" />
      <input type="hidden" name="redirect_url" value="action=unreadreplies', (!empty($context['showing_all_topics']) ? ';all' : ''), $context['querystring_board_limits'], '" />';

  if (isset($context['topics_to_mark']) && !empty($settings['show_mark_read']))
  {
    // Generate the button strip.
    $mark_read = array(
      'markread' => array(
        'text' => 'mark_as_read', 
        'image' => 'markread.gif', 
        'icon' => 'fa-check',
        'lang' => true, 
        'url' => $scripturl . '?action=markasread;sa=unreadreplies;topics=' . $context['topics_to_mark'] . ';' . $context['session_var'] . '=' . $context['session_id']),
    );

    if ($showCheckboxes)
      $mark_read['markselectread'] = array(
        'text' => 'quick_mod_markread',
        'image' => 'markselectedread.gif',
        'icon' => 'fa-check-square-o',
        'lang' => true,
        'url' => 'javascript:document.quickModForm.submit();',
      );
  }

  if (!empty($context['topics']))
  {
    echo '
      <div class="pagesection container">';

    if (!empty($mark_read) && !empty($settings['use_tabs']))
      template_button_strip($mark_read, 'right');

    // Pagination
    echo '
      <div class="mt-3">
        <span class="is-muted is-size-6-5 is-uppercase">', $txt['pages'], ': &nbsp;</span>
        <span class="is-size-6-5 mr-3">', $context['page_index'], '</span>
      </div>';

    echo '
      <div class="tborder topic_table container" id="unreadreplies">
        <table class="table is-fullwidth mb-4 mt-4 responsive-table" cellspacing="0">
          <thead>
            <tr class="catbg">
              <th scope="col" class="first_th"><span class="sr-only">Topic icon</span></th>
              <th scope="col">
                <a href="', $scripturl, '?action=unreadreplies', $context['querystring_board_limits'], ';sort=subject', $context['sort_by'] === 'subject' && $context['sort_direction'] === 'up' ? ';desc' : '', '">', $txt['subject'], $context['sort_by'] === 'subject' ? ' <img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" />' : '', '</a>
              </th>
              <th scope="col" width="14%" align="center">
                <a href="', $scripturl, '?action=unreadreplies', $context['querystring_board_limits'], ';sort=replies', $context['sort_by'] === 'replies' && $context['sort_direction'] === 'up' ? ';desc' : '', '">', $txt['replies'], $context['sort_by'] === 'replies' ? ' <img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" />' : '', '</a>
              </th>';

            // Show a "select all" box for quick moderation?
            if ($showCheckboxes)
              echo '
              <th scope="col" width="22%">
                <a href="', $scripturl, '?action=unreadreplies', $context['querystring_board_limits'], ';sort=last_post', $context['sort_by'] === 'last_post' && $context['sort_direction'] === 'up' ? ';desc' : '', '">', $txt['last_post'], $context['sort_by'] === 'last_post' ? ' <img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" />' : '', '</a>
              </th>
              <th class="last_th">
                <input type="checkbox" onclick="invertAll(this, this.form, \'topics[]\');" class="input_check" />
              </th>';

            else
              echo '
              <th scope="col" class="last_th" width="22%">
                <a href="', $scripturl, '?action=unreadreplies', $context['querystring_board_limits'], ';sort=last_post', $context['sort_by'] === 'last_post' && $context['sort_direction'] === 'up' ? ';desc' : '', '">', $txt['last_post'], $context['sort_by'] === 'last_post' ? ' <img src="' . $settings['images_url'] . '/sort_' . $context['sort_direction'] . '.gif" alt="" />' : '', '</a>
              </th>';
            echo '
            </tr>
          </thead>
          <tbody>';

    foreach ($context['topics'] as $topic)
    {
      // Calculate the color class of the topic.
      $color_class = '';
      if (strpos($topic['class'], 'sticky') !== false)
        $color_class = 'stickybg';
      if (strpos($topic['class'], 'locked') !== false)
        $color_class .= 'lockedbg';

      $color_class2 = !empty($color_class) ? $color_class . '2' : '';

            echo '
            <tr>
              <td class="', $color_class, ' icon2 windowbg">
                <img src="', $topic['first_post']['icon_url'], '" alt="" />
              </td>
              <td class="subject ', $color_class2, ' windowbg2">
                <div>
                  ', $topic['is_sticky'] ? '<strong>' : '', '<span id="msg_' . $topic['first_post']['id'] . '">', $topic['first_post']['link'], '</span>', $topic['is_sticky'] ? '</strong>' : '', '
                  <a href="', $topic['new_href'], '" id="newicon', $topic['first_post']['id'], '"><img src="', $settings['lang_images_url'], '/new.gif" alt="', $txt['new'], '" /></a>
                  <p>
                    <span class="is-muted is-uppercase is-size-7">', $txt['started_by'], '</span> ', $topic['first_post']['member']['link'], '
                    <span class="is-muted is-uppercase is-size-7">', $txt['in'], '</span> ', $topic['board']['link'], '
                  </p>
                </div>
              </td>
              <td class="', $color_class, ' stats windowbg">
                <p class="is-uppercase is-size-7"><span>', $topic['replies'], ' ', $txt['replies'], '</span></p>
                <p class="is-uppercase is-size-7"><span>', $topic['views'], ' ', $txt['views'], '</span></p>
              </td>
              <td class="', $color_class2, ' lastpost windowbg2">
                <p class="is-size-6-5">
                  <span class="is-muted is-uppercase is-size-7">', $txt['by'], '</span> ', $topic['last_post']['member']['link'], '<br>
                  <span class="is-muted is-uppercase is-size-7">', $txt['on'], '</span> ', $topic['last_post']['time'], '
                  <a href="', $topic['last_post']['href'], '" class="view-last-post">
                    <span class="icon has-text-primary tag">
                      <i class="fa fa-angle-double-right"></i>
                    </span>
                    <span class="sr-only"> View last post</span>
                  </a>
                </p>
                
              </td>';

            if ($showCheckboxes)
              echo '
              <td class="windowbg2" valign="middle" align="center">
                <input type="checkbox" name="topics[]" value="', $topic['id'], '" class="input_check" />
              </td>';
            echo '
            </tr>';
    }

    if (empty($settings['use_tabs']) && !empty($mark_read))
            echo '
            <tr class="catbg">
              <td colspan="', $showCheckboxes ? '6' : '5', '" align="right">
                ', template_button_strip($mark_read, 'top'), '
              </td>
            </tr>';

          echo '
          </tbody>
        </table>
      </div>';

      // Bottom Buttons and pagination
      echo'
      <div class="pagesection container">';

    if (!empty($settings['use_tabs']) && !empty($mark_read))
      template_button_strip($mark_read, 'right');

    // Pagination
    echo '
      <div class="mt-3">
        <span class="is-muted is-size-6-5 is-uppercase">', $txt['pages'], ': &nbsp;</span>
        <span class="is-size-6-5 mr-3">', $context['page_index'], '</span>
      </div>';
  }
  else
    // If no topics
    echo '
      <div class="cat_bar">
        <h3 class="catbg centertext">
          ', $context['showing_all_topics'] ? $txt['msg_alert_none'] : $txt['unread_topics_visit_none'], '
        </h3>
      </div>';

  if ($showCheckboxes)
    echo '
    </form>';

  echo '
  </div>';
}

?>