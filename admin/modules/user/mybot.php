<?php
if(!defined("IN_MYBB"))
{
	header("HTTP/1.0 404 Not Found");
	exit;
}
$PL or require_once PLUGINLIBRARY;
$lang->load("mybot");

if($mybb->input['action']!="delete") {
	$page->add_breadcrumb_item($lang->mybot, "index.php?module=user-mybot");
	$page->output_header($lang->mybot);
}

if($mybb->input['action']=="add") {
	generate_tabs("add");
	if($mybb->request_method == "post") {
		if(!strlen(trim($mybb->input['title'])))
			$errors[] = $lang->mybot_add_title_not;

		if(!$mybb->input['conditions'])
			$errors[] = $lang->mybot_add_conditions_not;

		if(is_array($mybb->input['conditions'])) {
			if(in_Array("user", $mybb->input['conditions']) && !$mybb->input['user'])
				$errors[] = $lang->mybot_add_user_not;

	    	if(in_Array("group", $mybb->input['conditions']) && !$mybb->input['group'])
				$errors[] = $lang->mybot_add_group_not;

	       	if(in_Array("forum", $mybb->input['conditions']) && !$mybb->input['forum'])
				$errors[] = $lang->mybot_add_forum_not;

	       	if(in_Array("string", $mybb->input['conditions']) && !strlen(trim($mybb->input['string'])))
				$errors[] = $lang->mybot_add_string_not;
		}

		if(!$mybb->input['actions'])
			$errors[] = $lang->mybot_add_action_not;

		if(is_array($mybb->input['actions'])) {
			if(in_Array("answer", $mybb->input['actions']) && !strlen(trim($mybb->input['answer'])))
				$errors[] = $lang->mybot_add_answer_not;

			if(in_Array("move", $mybb->input['actions']) && !strlen(trim($mybb->input['move'])))
				$errors[] = $lang->mybot_add_move_not;

			if(in_Array("delete", $mybb->input['actions']) && !strlen(trim($mybb->input['delete'])))
				$errors[] = $lang->mybot_add_delete_not;

			if(in_Array("pm", $mybb->input['actions'])) {
				if(!$mybb->input['pm'])
					$errors[] = $lang->mybot_add_pm_not;

    			if($mybb->input['pm'] == "other" && !strlen(trim($mybb->input['pm_user'])))
					$errors[] = $lang->mybot_add_pm_user_not;

				if(!strlen(trim($mybb->input['subject'])))
					$errors[] = $lang->mybot_add_subject_not;

				if(!strlen(trim($mybb->input['message'])))
					$errors[] = $lang->mybot_add_message_not;
			}
		}

		if(!$errors) {
			if(in_Array("user", $mybb->input['conditions']))
			    $conditions['user'] = $mybb->input['user'];

	    	if(in_Array("group", $mybb->input['conditions']))
			    $conditions['group'] = $mybb->input['group'];

	       	if(in_Array("forum", $mybb->input['conditions']))
			    $conditions['forum'] = $mybb->input['forum'];

	       	if(in_Array("string", $mybb->input['conditions']))
			    $conditions['string'] = $mybb->input['string'];

			if(in_Array("thread", $mybb->input['conditions']))
			    $conditions['thread'] = true;

   
  			if(in_Array("answer", $mybb->input['actions']))
			    $actions['answer'] = $mybb->input['answer'];

			if(in_Array("move", $mybb->input['actions']))
			    $actions['move'] = $mybb->input['move'];

			if(in_Array("delete", $mybb->input['actions']))
			    $actions['delete'] = $mybb->input['delete'];

			if(in_Array("stick", $mybb->input['actions']))
			    $actions['stick'] = true;

			if(in_Array("close", $mybb->input['actions']))
			    $actions['close'] = true;

			if(in_Array("pm", $mybb->input['actions'])) {
			    $actions['pm']['user'] = $mybb->input['pm'];
    			if($mybb->input['pm'] == "other")
			    	$actions['pm']['user'] = $mybb->input['pm_user'];
			    $actions['pm']['subject'] = $mybb->input['subject'];
			    $actions['pm']['message'] = $mybb->input['message'];
			}

			$rules = mybot_cache_load();

			$insert_array = array(
				'title' => $db->escape_string($mybb->input['title']),
				'conditions' => $db->escape_string(serialize($conditions)),
				'actions' => $db->escape_string(serialize($actions))
			);
			$id = $db->insert_query('mybot', $insert_array);

			$rules[] = array("id"=>$id, "title"=>$mybb->input['title'], "conditions"=>$conditions, "actions"=>$actions);
			mybot_cache_update(false, $rules);

			flash_message($lang->mybot_add_added, 'success');
			admin_redirect("index.php?module=user-mybot");
		}
	}
	if($mybb->request_method != "post" || $errors) {
		if($errors)
		{
			$page->output_inline_error($errors);
		}
		$query = $db->simple_select("users", "uid, username");
		while($user = $db->fetch_array($query))
		    $userarray[$user['uid']] = $user['username'];

		$form = new Form("index.php?module=user-mybot&amp;action=add", "post");
		$form_container = new FormContainer($lang->mybot_addrule);

		$add_title = $form->generate_text_box("title", $mybb->input['title']);
		$form_container->output_row($lang->mybot_add_title." <em>*</em>", $lang->mybot_add_title_desc, $add_title);

		$conditions_list = array(
				"user" => $lang->mybot_add_conditions_user,
				"group" => $lang->mybot_add_conditions_group,
				"forum" => $lang->mybot_add_conditions_forum,
				"string" => $lang->mybot_add_conditions_string,
				"thread" => $lang->mybot_add_conditions_thread);
		$add_conditions = $form->generate_select_box("conditions[]", $conditions_list, $mybb->input['conditions'], array("multiple"=>true, "id"=>"conditions"));
		$form_container->output_row($lang->mybot_add_conditions." <em>*</em>", $lang->mybot_add_conditions_desc, $add_conditions);

		$add_user = $form->generate_select_box("user[]", $userarray, $mybb->input['user'], array("multiple"=>true));
		$form_container->output_row($lang->mybot_add_user, $lang->mybot_add_user_desc, $add_user, '', array(), array('id' => 'user'));

		$add_group = $form->generate_group_select("group[]", $mybb->input['group'], array("multiple"=>true));
		$form_container->output_row($lang->mybot_add_group, $lang->mybot_add_group_desc, $add_group, '', array(), array('id' => 'group'));

		$add_forum = $form->generate_forum_select("forum[]", $mybb->input['forum'], array("multiple"=>true));
		$form_container->output_row($lang->mybot_add_forum, $lang->mybot_add_forum_desc, $add_forum, '', array(), array('id' => 'forum'));

		$add_string = $form->generate_text_area("string", $mybb->input['string']);
		$form_container->output_row($lang->mybot_add_string, $lang->mybot_add_string_desc, $add_string, '', array(), array('id' => 'string'));

		$action_list = array(
				"answer" => $lang->mybot_add_action_answer,
				"move" => $lang->mybot_add_action_move,
				"delete" => $lang->mybot_add_action_delete,
				"stick" => $lang->mybot_add_action_stick,
				"close" => $lang->mybot_add_action_close,
				"pm" => $lang->mybot_add_action_pm);
		$add_actions = $form->generate_select_box("actions[]", $action_list, $mybb->input['actions'], array("multiple"=>true, "id"=>"action"));
		$form_container->output_row($lang->mybot_add_action." <em>*</em>", $lang->mybot_add_action_desc, $add_actions);

		$add_answer = $form->generate_text_area("answer", $mybb->input['answer']);
		$form_container->output_row($lang->mybot_add_answer, $lang->mybot_add_answer_desc, $add_answer, '', array(), array('id' => 'answer'));

		$add_move = $form->generate_forum_select("move", $mybb->input['move']);
		$form_container->output_row($lang->mybot_add_move, $lang->mybot_add_move_desc, $add_move, '', array(), array('id' => 'move'));

		$add_delete = $form->generate_radio_button("delete", "thread", $lang->thread, array("checked"=>true));
		$add_delete .= " ".$form->generate_radio_button("delete", "post", $lang->post);
		$form_container->output_row($lang->mybot_add_delete, $lang->mybot_add_delete_desc, $add_delete, '', array(), array('id' => 'delete'));

		$pm_list = array(
				"last" => $lang->mybot_add_pm_last,
				"start" => $lang->mybot_add_pm_start,
				"other" => $lang->mybot_add_pm_other);
		$add_pm = $form->generate_select_box("pm", $pm_list, $mybb->input['pm'], array("id"=>"pm_select"));
		$form_container->output_row($lang->mybot_add_pm, $lang->mybot_add_pm_desc, $add_pm, '', array(), array('id' => 'pm'));

		$add_pm_user = $form->generate_select_box("pm_user", $userarray, $mybb->input['pm_user']);
		$form_container->output_row($lang->mybot_add_pm_user." <em>*</em>", $lang->mybot_add_pm_user_desc, $add_pm_user, '', array(), array('id' => 'pm_user'));

		$add_subject = $form->generate_text_box("subject", $mybb->input['subject']);
		$form_container->output_row($lang->mybot_add_subject, $lang->mybot_add_subject_desc, $add_subject, '', array(), array('id' => 'subject'));

		$add_message = $form->generate_text_area("message", $mybb->input['message']);
		$form_container->output_row($lang->mybot_add_message, $lang->mybot_add_message_desc, $add_message, '', array(), array('id' => 'message'));

		$form_container->end();

		$buttons[] = $form->generate_submit_button($lang->mybot_addrule);
		$buttons[] = $form->generate_reset_button($lang->reset);
		$form->output_submit_wrapper($buttons);
		$form->end();

		echo '<script type="text/javascript" src="./jscripts/peeker.js"></script>
		<script type="text/javascript">
			Event.observe(window, "load", function() {
				loadPeekers();
			});
			function loadPeekers()
			{
			/*
				new Peeker($("conditions"), $("user"), /user/, false);
				new Peeker($("conditions"), $("group"), /group/, false);
				new Peeker($("conditions"), $("forum"), /forum/, false);
				new Peeker($("conditions"), $("string"), /string/, false);
				new Peeker($("action"), $("answer"), /answer/, false);
				new Peeker($("action"), $("move"), /move/, false);
				new Peeker($("action"), $("delete"), /delete/, false);
				new Peeker($("action"), $("pm"), /pm/, false);
				new Peeker($("action"), $("subject"), /pm/, false);
				new Peeker($("action"), $("message"), /pm/, false);
			*/
				new Peeker($("pm_select"), $("pm_user"), /other/, false);
			}
		</script>';
	}
} elseif($mybb->input['action']=="edit") {
	generate_tabs("overview");
	$id = intval($mybb->input['id']);
	if(!$id) {
		flash_message($lang->mybot_no_id, 'error');
		admin_redirect("index.php?module=user-mybot");
	}
	if($mybb->request_method == "post") {
		if(!strlen(trim($mybb->input['title'])))
			$errors[] = $lang->mybot_add_title_not;

		if(!$mybb->input['conditions'])
			$errors[] = $lang->mybot_add_conditions_not;

		if(is_array($mybb->input['conditions'])) {
			if(in_Array("user", $mybb->input['conditions']) && !$mybb->input['user'])
				$errors[] = $lang->mybot_add_user_not;

	    	if(in_Array("group", $mybb->input['conditions']) && !$mybb->input['group'])
				$errors[] = $lang->mybot_add_group_not;

	       	if(in_Array("forum", $mybb->input['conditions']) && !$mybb->input['forum'])
				$errors[] = $lang->mybot_add_forum_not;

	       	if(in_Array("string", $mybb->input['conditions']) && !strlen(trim($mybb->input['string'])))
				$errors[] = $lang->mybot_add_string_not;
		}

		if(!$mybb->input['actions'])
			$errors[] = $lang->mybot_add_action_not;

		if(is_array($mybb->input['actions'])) {
			if(in_Array("answer", $mybb->input['actions']) && !strlen(trim($mybb->input['answer'])))
				$errors[] = $lang->mybot_add_answer_not;

			if(in_Array("move", $mybb->input['actions']) && !strlen(trim($mybb->input['move'])))
				$errors[] = $lang->mybot_add_move_not;
			elseif(in_Array("move", $mybb->input['actions'])) {
				$forum = get_forum($mybb->input['move']);
				if(!$forum || $forum['type'] != "f" || $forum['type'] == "f" && $forum['linkto'] != '')
				    $errors[] = $lang->mybot_add_move_invalid;
			}

			if(in_Array("delete", $mybb->input['actions']) && !strlen(trim($mybb->input['delete'])))
				$errors[] = $lang->mybot_add_delete_not;

			if(in_Array("pm", $mybb->input['actions'])) {
				if(!$mybb->input['pm'])
					$errors[] = $lang->mybot_add_pm_not;

    			if($mybb->input['pm'] == "other" && !strlen(trim($mybb->input['pm_user'])))
					$errors[] = $lang->mybot_add_pm_user_not;

				if(!strlen(trim($mybb->input['subject'])))
					$errors[] = $lang->mybot_add_subject_not;

				if(!strlen(trim($mybb->input['message'])))
					$errors[] = $lang->mybot_add_message_not;
			}
		}

		if(!$errors) {
			if(in_Array("user", $mybb->input['conditions']))
			    $conditions['user'] = $mybb->input['user'];

	    	if(in_Array("group", $mybb->input['conditions']))
			    $conditions['group'] = $mybb->input['group'];

	       	if(in_Array("forum", $mybb->input['conditions']))
			    $conditions['forum'] = $mybb->input['forum'];

	       	if(in_Array("string", $mybb->input['conditions']))
			    $conditions['string'] = $mybb->input['string'];

    		if(in_Array("thread", $mybb->input['conditions']))
			    $conditions['thread'] = true;


			if(in_Array("answer", $mybb->input['actions']))
			    $actions['answer'] = $mybb->input['answer'];

			if(in_Array("move", $mybb->input['actions']))
			    $actions['move'] = $mybb->input['move'];

			if(in_Array("delete", $mybb->input['actions']))
			    $actions['delete'] = $mybb->input['delete'];

			if(in_Array("stick", $mybb->input['actions']))
			    $actions['stick'] = true;

			if(in_Array("close", $mybb->input['actions']))
			    $actions['close'] = true;

			if(in_Array("pm", $mybb->input['actions'])) {
			    $actions['pm']['user'] = $mybb->input['pm'];
    			if($mybb->input['pm'] == "other")
			    	$actions['pm']['user'] = $mybb->input['pm_user'];
			    $actions['pm']['subject'] = $mybb->input['subject'];
			    $actions['pm']['message'] = $mybb->input['message'];
			}

//			$rules = mybot_cache_load();

			$update_array = array(
				'title' => $db->escape_string($mybb->input['title']),
				'conditions' => $db->escape_string(serialize($conditions)),
				'actions' => $db->escape_string(serialize($actions))
			);
			$db->update_query('mybot', $update_array, "id='{$id}'");

//			$rules[] = array("id"=>$id, "title"=>$mybb->input['title'], "conditions"=>$conditions, "actions"=>$actions);
//			mybot_cache_update(false, $rules);

			mybot_cache_update();

			flash_message($lang->mybot_add_edited, 'success');
			admin_redirect("index.php?module=user-mybot");
		}
	}
	if($mybb->request_method != "post" || $errors) {
		$rule = mybot_cache_load($id);
		if(array_key_exists("user", $rule['conditions']))
			$conditions[] = "user";

		if(array_key_exists("group", $rule['conditions']))
			$conditions[] = "group";

	   	if(array_key_exists("forum", $rule['conditions']))
			$conditions[] = "forum";

	   	if(array_key_exists("string", $rule['conditions']))
			$conditions[] = "string";

		if(array_key_exists("thread", $rule['conditions']))
		    $conditions[] = "thread";


		if(array_key_exists("answer", $rule['actions']))
			$actions[] = "answer";

		if(array_key_exists("move", $rule['actions']))
			$actions[] = "move";

		$thread_checked = true;
		$post_checked = false;
		if(array_key_exists("delete", $rule['actions'])) {
			$actions[] = "delete";
			if($rule['actions']['delete'] == "post") {
				$thread_checked = false;
				$post_checked = true;
			}
		}

    	if(array_key_exists("stick", $rule['actions']))
			$actions[] = "stick";

    	if(array_key_exists("close", $rule['actions']))
			$actions[] = "close";

		if(array_key_exists("pm", $rule['actions'])) {
			$actions[] = "pm";
			$pm = $rule['actions']['pm']['user'];
		    if($pm != "last" && $pm != "start")
		        $pm = "other";
		}


		if($errors)
		{
			$page->output_inline_error($errors);
		}
		$query = $db->simple_select("users", "uid, username");
		while($user = $db->fetch_array($query))
		    $userarray[$user['uid']] = $user['username'];

		$form = new Form("index.php?module=user-mybot&amp;action=edit", "post");
		$form_container = new FormContainer($lang->mybot_addrule);

		$add_title = $form->generate_text_box("title", $rule['title']);
		$form_container->output_row($lang->mybot_add_title." <em>*</em>", $lang->mybot_add_title_desc, $add_title);

		$conditions_list = array(
				"user" => $lang->mybot_add_conditions_user,
				"group" => $lang->mybot_add_conditions_group,
				"forum" => $lang->mybot_add_conditions_forum,
				"string" => $lang->mybot_add_conditions_string,
				"thread" => $lang->mybot_add_conditions_thread);
		$add_conditions = $form->generate_select_box("conditions[]", $conditions_list, $conditions, array("multiple"=>true, "id"=>"conditions"));
		$form_container->output_row($lang->mybot_add_conditions." <em>*</em>", $lang->mybot_add_conditions_desc, $add_conditions);

		$add_user = $form->generate_select_box("user[]", $userarray, $rule['conditions']['user'], array("multiple"=>true));
		$form_container->output_row($lang->mybot_add_user, $lang->mybot_add_user_desc, $add_user, '', array(), array('id' => 'user'));

		$add_group = $form->generate_group_select("group[]", $rule['conditions']['group'], array("multiple"=>true));
		$form_container->output_row($lang->mybot_add_group, $lang->mybot_add_group_desc, $add_group, '', array(), array('id' => 'group'));

		$add_forum = $form->generate_forum_select("forum[]", $rule['conditions']['forum'], array("multiple"=>true));
		$form_container->output_row($lang->mybot_add_forum, $lang->mybot_add_forum_desc, $add_forum, '', array(), array('id' => 'forum'));

		$add_string = $form->generate_text_area("string", $rule['conditions']['string']);
		$form_container->output_row($lang->mybot_add_string, $lang->mybot_add_string_desc, $add_string, '', array(), array('id' => 'string'));

		$action_list = array(
				"answer" => $lang->mybot_add_action_answer,
				"move" => $lang->mybot_add_action_move,
				"delete" => $lang->mybot_add_action_delete,
				"stick" => $lang->mybot_add_action_stick,
				"close" => $lang->mybot_add_action_close,
				"pm" => $lang->mybot_add_action_pm);
		$add_actions = $form->generate_select_box("actions[]", $action_list, $actions, array("multiple"=>true, "id"=>"action"));
		$form_container->output_row($lang->mybot_add_action." <em>*</em>", $lang->mybot_add_action_desc, $add_actions);

		$add_answer = $form->generate_text_area("answer", $rule['actions']['answer']);
		$form_container->output_row($lang->mybot_add_answer, $lang->mybot_add_answer_desc, $add_answer, '', array(), array('id' => 'answer'));

		$add_move = $form->generate_forum_select("move", $rule['actions']['move']);
		$form_container->output_row($lang->mybot_add_move, $lang->mybot_add_move_desc, $add_move, '', array(), array('id' => 'move'));

		$add_delete = $form->generate_radio_button("delete", "thread", $lang->thread, array("checked"=>$thread_checked));
		$add_delete .= " ".$form->generate_radio_button("delete", "post", $lang->post, array("checked"=>$post_checked));
		$form_container->output_row($lang->mybot_add_delete, $lang->mybot_add_delete_desc, $add_delete, '', array(), array('id' => 'delete'));

		$pm_list = array(
				"last" => $lang->mybot_add_pm_last,
				"start" => $lang->mybot_add_pm_start,
				"other" => $lang->mybot_add_pm_other);
		$add_pm = $form->generate_select_box("pm", $pm_list, $pm, array("id"=>"pm_select"));
		$form_container->output_row($lang->mybot_add_pm, $lang->mybot_add_pm_desc, $add_pm, '', array(), array('id' => 'pm'));

		$add_pm_user = $form->generate_select_box("pm_user", $userarray, $rule['actions']['pm']['user']);
		$form_container->output_row($lang->mybot_add_pm_user." <em>*</em>", $lang->mybot_add_pm_user_desc, $add_pm_user, '', array(), array('id' => 'pm_user'));

		$add_subject = $form->generate_text_box("subject", $rule['actions']['pm']['subject']);
		$form_container->output_row($lang->mybot_add_subject, $lang->mybot_add_subject_desc, $add_subject, '', array(), array('id' => 'subject'));

		$add_message = $form->generate_text_area("message", $rule['actions']['pm']['message']);
		$form_container->output_row($lang->mybot_add_message, $lang->mybot_add_message_desc, $add_message, '', array(), array('id' => 'message'));

		echo $form->generate_hidden_field("id", $id);
		$form_container->end();

		$buttons[] = $form->generate_submit_button($lang->mybot_editrule);
		$buttons[] = $form->generate_reset_button($lang->reset);
		$form->output_submit_wrapper($buttons);
		$form->end();

		echo '<script type="text/javascript" src="./jscripts/peeker.js"></script>
		<script type="text/javascript">
			Event.observe(window, "load", function() {
				loadPeekers();
			});
			function loadPeekers()
			{
			/*
				new Peeker($("conditions"), $("user"), /user/, false);
				new Peeker($("conditions"), $("group"), /group/, false);
				new Peeker($("conditions"), $("forum"), /forum/, false);
				new Peeker($("conditions"), $("string"), /string/, false);
				new Peeker($("action"), $("answer"), /answer/, false);
				new Peeker($("action"), $("move"), /move/, false);
				new Peeker($("action"), $("delete"), /delete/, false);
				new Peeker($("action"), $("pm"), /pm/, false);
				new Peeker($("action"), $("subject"), /pm/, false);
				new Peeker($("action"), $("message"), /pm/, false);
			*/
				new Peeker($("pm_select"), $("pm_user"), /other/, false);
			}
		</script>';
	}
} elseif($mybb->input['action']=="delete") {
	$id = intval($mybb->input['id']);
	if(!$id) {
		flash_message($lang->mybot_no_id, 'error');
		admin_redirect("index.php?module=user-mybot");
	}

	if($mybb->input['no'])
	{
		admin_redirect("index.php?module=user-mybot");
	}
	else
	{
		if($mybb->request_method == "post") {
			$db->delete_query("mybot", "id='{$id}'");
			mybot_cache_update();
			flash_message($lang->mybot_delete_success, 'success');
			admin_redirect("index.php?module=user-mybot");
		} else {
			$page->output_confirm_action("index.php?module=user-mybot&action=delete&id={$id}", $lang->mybot_delete_confirm);
			exit;
		}
	}
} elseif($mybb->input['action']=="post") {
	generate_tabs("post");
	if($mybb->request_method == "post") {
		if(!strlen(trim($mybb->input['forum'])))
		{
			flash_message($lang->mybot_post_forum_not, 'error');
			admin_redirect("index.php?module=user-mybot&amp;action=post");
		}
		if(!strlen(trim($mybb->input['subject'])))
		{
			flash_message($lang->mybot_post_subject_not, 'error');
			admin_redirect("index.php?module=user-mybot&amp;action=post");
		}
		if(!strlen(trim($mybb->input['text'])))
		{
			flash_message($lang->mybot_post_text_not, 'error');
			admin_redirect("index.php?module=user-mybot&amp;action=post");
		}
		if(!is_array($forum_cache))
		{
			$forum_cache = cache_forums();
		}
		if($forum_cache[$mybb->input['forum']]['type']=="c") {
			flash_message($lang->mybot_post_category, 'error');
			admin_redirect("index.php?module=user-mybot&amp;action=post");
		}
        $name = $db->fetch_field($db->simple_select("users", "username", "uid='{$mybb->settings['mybot_user']}'"), "username");
		// Set up posthandler.
        require_once  MYBB_ROOT."inc/datahandlers/post.php";
        $posthandler = new PostDataHandler("insert");
        $posthandler->action = "thread";

        // Set the thread data that came from the input to the $thread array.
        $new_thread = array(
        	"fid" => $mybb->input['forum'],
            "subject" => $mybb->input['subject'],
            "prefix" => "",
            "icon" => "",
            "uid" => $mybb->settings['mybot_user'],
            "username" => $name,
            "message" => $mybb->input['text'],
            "ipaddress" => get_ip()
        );
        $posthandler->set_data($new_thread);
        $valid_thread = $posthandler->validate_thread();
		// Fetch friendly error messages if this is an invalid thread
		if(!$valid_thread)
		{
	        $errors = $posthandler->get_friendly_errors();
		} else {
	        $posthandler->insert_thread();
			flash_message($lang->mybot_post_inserted, 'success');
			admin_redirect("index.php?module=user-mybot&amp;action=post");
		}
	}
	if($mybb->request_method != "post" || $errors) {
		if($errors)
		{
			$page->output_inline_error($errors);
		}
		$form = new Form("index.php?module=user-mybot&amp;action=post", "post");
		$form_container = new FormContainer($lang->mybot_post);

		$post_forum = $form->generate_forum_select("forum", "");
		$form_container->output_row($lang->mybot_post_forum." <em>*</em>", $lang->mybot_post_forum_desc, $post_forum);

		$post_subject = $form->generate_text_box("subject");
		$form_container->output_row($lang->mybot_post_subject." <em>*</em>", $lang->mybot_post_subject_desc, $post_subject);

		$post_text = $form->generate_text_area("text");
		$form_container->output_row($lang->mybot_post_text." <em>*</em>", $lang->mybot_post_text_desc, $post_text);

		$form_container->end();

		$buttons[] = $form->generate_submit_button($lang->mybot_post_submit);
		$buttons[] = $form->generate_reset_button($lang->reset);
		$form->output_submit_wrapper($buttons);
		$form->end();
	}
} elseif($mybb->input['action']=="documentation") {
	generate_tabs("documentation");
	$table = new Table;
	$table->construct_header($lang->mybot_variable, array("width"=>"15%"));
	$table->construct_header($lang->mybot_description);

	$table->construct_cell("{boardname}");
	$table->construct_cell($lang->mybot_doc_boardname);
	$table->construct_row();

	$table->construct_cell("{botname}");
	$table->construct_cell($lang->mybot_doc_botname);
	$table->construct_row();

	$table->output($lang->mybot_global);


	$table = new Table;
	$table->construct_header($lang->mybot_variable, array("width"=>"15%"));
	$table->construct_header($lang->mybot_description);

	$table->construct_cell("{registered}");
	$table->construct_cell($lang->mybot_doc_registered);
	$table->construct_row();

	$table->output($lang->mybot_register);


	$table = new Table;
	$table->construct_header($lang->mybot_variable, array("width"=>"15%"));
	$table->construct_header($lang->mybot_description);

	$table->construct_cell("{lastpost->user}");
	$table->construct_cell($lang->mybot_doc_user);
	$table->construct_row();

	$table->construct_cell("{lastpost->userlink}");
	$table->construct_cell($lang->mybot_doc_userlink);
	$table->construct_row();

	$table->construct_cell("{lastpost->subject}");
	$table->construct_cell($lang->mybot_doc_subject);
	$table->construct_row();

	$table->construct_cell("{lastpost->id}");
	$table->construct_cell($lang->mybot_doc_id);
	$table->construct_row();

	$table->construct_cell("{lastpost->link}");
	$table->construct_cell($lang->mybot_doc_link);
	$table->construct_row();

	$table->construct_cell("{lastpost->date}");
	$table->construct_cell($lang->mybot_doc_date);
	$table->construct_row();

	$table->construct_cell("{lastpost->time}");
	$table->construct_cell($lang->mybot_doc_time);
	$table->construct_row();

	$table->construct_cell("{lastpost->message}");
	$table->construct_cell($lang->mybot_doc_message);
	$table->construct_row();

	$table->construct_cell("{lastpost->uid}");
	$table->construct_cell($lang->mybot_doc_uid);
	$table->construct_row();

	$table->construct_cell("{lastpost->timestamp}");
	$table->construct_cell($lang->mybot_doc_timestamp);
	$table->construct_row();

	$table->construct_cell($lang->mybot_doc_thread, array("colspan"=>2));
	$table->construct_row();

	$table->construct_cell("{thread->forum}");
	$table->construct_cell($lang->mybot_doc_forum);
	$table->construct_row();

	$table->construct_cell("{thread->answers}");
	$table->construct_cell($lang->mybot_doc_answers);
	$table->construct_row();

	$table->construct_cell("{thread->views}");
	$table->construct_cell($lang->mybot_doc_views);
	$table->construct_row();

	$table->output($lang->mybot_thread);
} elseif($mybb->input['action']=="cache") {
	mybot_cache_update();
//	$PL->cache_delete("mybot_rules");

	flash_message($lang->mybot_cache_reloaded, 'success');
	admin_redirect("index.php?module=user-mybot");
} else {
	generate_tabs("overview");
	$rules = mybot_cache_load();

	$table = new Table;
	$table->construct_header($lang->mybot_title, array("width"=>"10%"));
	$table->construct_header($lang->mybot_conditions, array("width"=>"35%"));
	$table->construct_header($lang->mybot_actions, array("width"=>"35%"));
	$table->construct_header($lang->controls, array("colspan"=>2, "width"=>"20%"));

	if(is_Array($rules) && sizeof($rules) > 0) {
		foreach($rules as $rule) {
			unset($conditions); unset($actions);
			if(array_key_exists("user", $rule['conditions']))
				$conditions[] = $lang->mybot_conditions_user;
	
			if(array_key_exists("group", $rule['conditions']))
				$conditions[] = $lang->mybot_conditions_group;
	
		   	if(array_key_exists("forum", $rule['conditions']))
				$conditions[] = $lang->mybot_conditions_forum;
	
		   	if(array_key_exists("string", $rule['conditions']))
				$conditions[] = $lang->mybot_conditions_string;

   		   	if(array_key_exists("thread", $rule['conditions']))
				$conditions[] = $lang->mybot_conditions_thread;
	
	
			if(array_key_exists("answer", $rule['actions']))
				$actions[] = $lang->mybot_actions_answer;
	
			if(array_key_exists("move", $rule['actions']))
				$actions[] = $lang->mybot_actions_move;
	
			if(array_key_exists("delete", $rule['actions']))
				$actions[] = $lang->mybot_actions_delete;
	
	    	if(array_key_exists("stick", $rule['actions']))
				$actions[] = $lang->mybot_actions_stick;
	
	    	if(array_key_exists("close", $rule['actions']))
				$actions[] = $lang->mybot_actions_close;
	
			if(array_key_exists("pm", $rule['actions']))
				$actions[] = $lang->mybot_actions_pm;
	
	
			$table->construct_cell($rule['title']);
			$table->construct_cell(implode(", ", $conditions));
			$table->construct_cell(implode(", ", $actions));
			$table->construct_cell("<a href=\"index.php?module=user-mybot&amp;action=edit&amp;id={$rule['id']}\">{$lang->edit}</a>");
			$table->construct_cell("<a href=\"index.php?module=user-mybot&amp;action=delete&amp;id={$rule['id']}\">{$lang->delete}</a>");
			$table->construct_row();
		}
	} else {
		$table->construct_cell($lang->mybot_no_rules, array("colspan"=>5, "style"=>"text-align: center"));
		$table->construct_row();		
	}
	$table->output($lang->mybot_overview);
}

$page->output_footer();

function generate_tabs($selected)
{
	global $lang, $page;

	$sub_tabs = array();
	$sub_tabs['overview'] = array(
		'title' => $lang->mybot,
		'link' => "index.php?module=user-mybot",
		'description' => $lang->mybot_overview
	);
	$sub_tabs['add'] = array(
		'title' => $lang->mybot_addrule,
		'link' => "index.php?module=user-mybot&amp;action=add",
		'description' => $lang->mybot_addrule_desc
	);
	$sub_tabs['post'] = array(
		'title' => $lang->mybot_post,
		'link' => "index.php?module=user-mybot&amp;action=post",
		'description' => $lang->mybot_post_desc
	);
	$sub_tabs['documentation'] = array(
		'title' => $lang->mybot_documentation,
		'link' => "index.php?module=user-mybot&amp;action=documentation",
		'description' => $lang->mybot_documentation_desc
	);
	$sub_tabs['cache'] = array(
		'title' => $lang->mybot_cache_reload,
		'link' => "index.php?module=user-mybot&amp;action=cache",
		'description' => ""
	);

	$page->output_nav_tabs($sub_tabs, $selected);
}
?>