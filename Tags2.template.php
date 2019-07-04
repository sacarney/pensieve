<?php
/*
Tagging System
Version 2.0
by:vbgamer45
http://www.smfhacks.com
*/
function template_main()
{
  global $txt, $context, $scripturl;

  echo'
  <div class="container">
    <div class="cat_bar mb-4">
      <h2 class="title is-5 mb-1">',$txt['smftags_popular'], '</h2>
    </div>

    <div class="pensieve-tags-list">';
      if (isset($context['poptags']))
        echo $context['poptags'];
      echo '
    </div>

    <div class="cat_bar mb-4">
      <h2 class="title is-5 mb-1">',$txt['smftags_latest'], '</h2>
    </div>

    <table class="table is-bordered is-striped is-narrow">
      <thead>
        <tr>
          <th>',$txt['smftags_subject'],'</th>
          <th width="11%">',$txt['smftags_topictag'],'</th>
          <th width="11%">',$txt['smftags_startedby'],'</th>
          <th width="4%" align="center">',$txt['smftags_replies'],'</th>
          <th width="4%" align="center">', $txt['smftags_views'], '</th>
        </tr>
      </thead>
      <tbody>';
        foreach ($context['tags_topics'] as $i => $topic)
        {
        echo '<tr>';
          echo '<td><a href="' . $scripturl . '?topic=' . $topic['id_topic'] . '.0">' . $topic['subject'] . '</a></td>';
          echo '<td><a href="' . $scripturl . '?action=tags;tagid=' . $topic['ID_TAG'] . '">' . $topic['tag'] . '</a></td>';
          echo '<td><a href="' . $scripturl . '?action=profile;u=' . $topic['id_member'] . '">' . $topic['poster_name'] . '</a></td>';
          echo '<td>' . $topic['num_replies'] . '</td>';
          echo '<td>' . $topic['num_views'] . '</td>';
        echo '</tr>';
      }
      echo'
      </tbody>
    </table>

    </div>
  ';

  TagsCopyright();
}

function template_results()
{
  global $scripturl, $txt, $context;

  echo '
  <div class="container">
    <div class="cat_bar mb-4">
      <h2 class="title is-5 mb-1">' . $txt['smftags_resultsfor'] . $context['tag_search'] . '</h2>
    </div>

    <table class="table is-bordered is-striped is-narrow">
      <thead>
        <tr>
          <th>',$txt['smftags_subject'],'</th>
          <th width="11%">',$txt['smftags_startedby'],'</th>
          <th width="4%">',$txt['smftags_replies'],'</th>
          <th width="4%">', $txt['smftags_views'], '</th>
        </tr>
      </thead>
      <tbody>';

      foreach ($context['tags_topics'] as $i => $topic) {
        echo'<tr>';
        echo'
        <td><a href="' . $scripturl . '?topic=' . $topic['id_topic'] . '.0">' . $topic['subject'] . '</td>
        <td><a href="' . $scripturl . '?action=profile;u=' . $topic['id_member'] . '">' . $topic['poster_name'] . '</a></td>
        <td>', $topic['num_replies'], '</td>
        <td>', $topic['num_views'], '</td>
        ';
        echo'</tr>';
      }
      echo'
      </tbody>
    </table>

    <div class="mt-4">' . $txt['smftags_pages'] . $context['page_index'] . '</div>


  </div>
  ';

  TagsCopyright();

}

function template_addtag()
{
  global $scripturl, $txt, $context;

  echo '<div class="container">
  <form method="post" action="', $scripturl, '?action=tags;sa=addtag2">

  
    <div class="cat_bar mb-4">
      <h2 class="title is-5 mb-1">', $txt['smftags_addtag2'], '</h2>
    </div>

    <div class="field is-horizontal">
      <div class="field-label is-normal">
        <label class="label">', $txt['smftags_tagtoadd'], '</label>
      </div>
      <div class="field-body">
        <div class="field has-addons">
          <div class="control">
            <input class="input" type="text" name="tag" size="64" maxlength="100" />
          </div>
          <div class="control">
            <input type="hidden" name="topic" value="', $context['tags_topic'], '" />
      <input class="button is-primary" type="submit" value="', $txt['smftags_addtag2'], '" name="submit" />
          </div>
        </div>
      </div>
    </div>
  

  </form></div>
  ';
  
  TagsCopyright();

}

function template_admin_settings()
{
  global $scripturl, $txt, $modSettings;

  echo'
  <form method="post" action="' . $scripturl . '?action=tags;sa=admin2">

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">' . $txt['smftags_set_mintaglength'] . '</label>
    </div>
    <div class="field-body">
      <div class="field">
        <div class="control">
          <input class="input is-auto" type="text" name="smftags_set_mintaglength" value="' .  $modSettings['smftags_set_mintaglength'] . '" />
        </div>
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">' . $txt['smftags_set_maxtaglength'] . '</label>
    </div>
    <div class="field-body">
      <div class="field">
        <div class="control">
          <input class="input is-auto" type="text" name="smftags_set_maxtaglength" value="' .  $modSettings['smftags_set_maxtaglength'] . '" />        </div>
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">' . $txt['smftags_set_maxtags'] . '</label>
    </div>
    <div class="field-body">
      <div class="field">
        <div class="control">
          <input class="input is-auto" type="text" name="smftags_set_maxtags" value="' .  $modSettings['smftags_set_maxtags'] . '" />
        </div>
      </div>
    </div>
  </div>

  <hr>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">' . $txt['smftags_set_cloud_tags_to_show'] . '</label>
    </div>
    <div class="field-body">
      <div class="field">
        <div class="control">
          <input class="input is-auto" type="text" name="smftags_set_cloud_tags_to_show" value="' .  $modSettings['smftags_set_cloud_tags_to_show'] . '" />
        </div>
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">' . $txt['smftags_set_cloud_tags_per_row'] . '</label>
    </div>
    <div class="field-body">
      <div class="field">
        <div class="control">
          <input class="input is-auto" type="text" name="smftags_set_cloud_tags_per_row" value="' .  $modSettings['smftags_set_cloud_tags_per_row'] . '" />
        </div>
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">' . $txt['smftags_set_cloud_max_font_size_precent'] . '</label>
    </div>
    <div class="field-body">
      <div class="field">
        <div class="control">
          <input class="input is-auto" type="text" name="smftags_set_cloud_max_font_size_precent" value="' .  $modSettings['smftags_set_cloud_max_font_size_precent'] . '" />
        </div>
      </div>
    </div>
  </div>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">' . $txt['smftags_set_cloud_min_font_size_precent'] . '</label>
    </div>
    <div class="field-body">
      <div class="field">
        <div class="control">
          <input class="input is-auto" type="text" name="smftags_set_cloud_min_font_size_precent" value="' .  $modSettings['smftags_set_cloud_min_font_size_precent'] . '" />
        </div>
      </div>
    </div>
  </div>
  <div><input class="button is-primary" type="submit" name="savesettings" value="', $txt['smftags_savesettings'],  '" /></div>
  </form>
  <div class="mt-4">
    <b>Has SMF Tags helped you?</b> Then support the developers:<br />
    <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
    <input type="hidden" name="cmd" value="_xclick">
    <input type="hidden" name="business" value="sales@visualbasiczone.com">
    <input type="hidden" name="item_name" value="SMF Tags">
    <input type="hidden" name="no_shipping" value="1">
    <input type="hidden" name="no_note" value="1">
    <input type="hidden" name="currency_code" value="USD">
    <input type="hidden" name="tax" value="0">
    <input type="hidden" name="bn" value="PP-DonationsBF">
    <input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-butcc-donate.gif" border="0" name="submit" alt="Make payments with PayPal - it is fast, free and secure!">
    <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
    </div>
  </form>
  ';

  TagsCopyright();
}

function template_suggesttag()
{
  global $scripturl, $txt;

  echo '
  <form method="POST" action="', $scripturl, '?action=tags;sa=suggest2">

  <div class="container">
    <div class="cat_bar mb-4">
      <h2 class="title is-5 mb-1">', $txt['smftags_suggest'], '</h2>
    </div>

    <div class="field is-horizontal">
      <div class="field-label is-normal">
        <label class="label">', $txt['smftags_tagtosuggest'], '</label>
      </div>
      <div class="field-body">
        <div class="field has-addons">
          <div class="control">
            <input class="input" type="text" name="tag" size="64" maxlength="100" />
          </div>
          <div class="control">
            <input class="input" type="text" name="tag" size="64" maxlength="100" />
            <input class="button is-primary" type="submit" value="', $txt['smftags_suggest'], '" name="submit" />
          </div>
        </div>
      </div>
    </div>
  </div>

  </form>
  ';
  
  TagsCopyright();
  
}

function TagsCopyright()
{
  //The Copyright is required to remain or contact me to purchase link removal.
  //http://www.smfhacks.com/copyright_removal.php
  echo '<br /><div align="center"><span class="smalltext">Powered by: <a href="http://www.smfhacks.com" target="blank">SMF Tags</a></span></div>';

}
?>