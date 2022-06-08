<?
	if(session_id()==''){session_start();}
	ob_start();

    $cc_array['id'] = $lng['ID'];
    $cc_array['anno_id'] = $lng['Announcement ID'];
    $cc_array['username'] = $lng['Username'];
    $cc_array['publish_on'] = $lng['Publish On'];
    $cc_array['date'] = $lng['Date'];
    $cc_array['anno_type'] = $lng['Type'];
    $cc_array['appr_required'] = $lng['Approval required'];
    $cc_array['fromss'] = $lng['From'];
    $cc_array['tooss'] = $lng['To'];
    $cc_array['anno_mode'] = $lng['Mode'];
    $cc_array['anno_category'] = $lng['Category'];
    $cc_array['description'] = $lng['Description'];