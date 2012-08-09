<?php
if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}
if(!defined("PLUGINLIBRARY"))
{
    define("PLUGINLIBRARY", MYBB_ROOT."inc/plugins/pluginlibrary.php");
}

$plugins->add_hook("admin_config_action_handler", "mybot_admin_config_action_handler");
$plugins->add_hook("admin_config_plugins_activate_commit", "mybot_installed");
$plugins->add_hook("admin_user_menu", "mybot_admin_user_menu");
$plugins->add_hook("admin_user_action_handler", "mybot_admin_user_action_handler");
$plugins->add_hook("admin_user_permissions", "mybot_admin_user_permissions");
$plugins->add_hook("member_do_register_end", "mybot_register");
$plugins->add_hook("newthread_do_newthread_end", "mybot_thread");
$plugins->add_hook("newreply_do_newreply_end", "mybot_post");

function mybot_info()
{
	return array(
		"name"			=> "MyBot",
		"description"	=> "Adds a simple Bot to your MyBB",
		"website"		=> "",
		"author"		=> "Jones",
		"authorsite"	=> "http://mybbdemo.tk",
		"version"		=> "1.0 Beta 2",
		"guid" 			=> "",
		"compatibility" => "16*"
	);
}

function mybot_install()
{
	global $lang, $PL, $db;
	mybot_uninstall();
	$plugininfo = mybot_info();
	$lang->load("mybot");
    if(!file_exists(PLUGINLIBRARY))
    {
        flash_message($lang->mybot_pl_missing, "error");
        admin_redirect("index.php?module=config-plugins");
    }
    $PL or require_once PLUGINLIBRARY;

    if($PL->version < 8)
    {
        flash_message($lang->mybot_pl_old, "error");
//        flash_message($PL->version, "error");
        admin_redirect("index.php?module=config-plugins");
    }
	$db->query("CREATE TABLE `".TABLE_PREFIX."mybot` ( `id` int(11) NOT NULL AUTO_INCREMENT, `title` varchar(50) DEFAULT NULL, `conditions` text NOT NULL, `actions` text NOT NULL, PRIMARY KEY (`id`) ) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1");
	$PL->settings("mybot",
	  	"MyBot",
	  	"Settings for the \"MyBot\" Plugin",
	  	array(
	      	"user" => array(
	          	"title" => "Bot",
	          	"description" => "Please insert the UID of the user who should be the bot",
		        "optionscode" => "text",
		        "value" => "0",
	          ),
	      	"react" => array(
	          	"title" => "What should the bot do when a new user registers?",
		        "optionscode" => "select
none=Nothing
pm=Send a PM
post=Create a thread",
		        "value" => "none",
	          ),
	      	"react_pm_subject" => array(
	          	"title" => "Subject (PM)",
	          	"description" => "Just needed when the bot sends a PM to a new User<br />See the <a href=\"index.php?module=user-mybot&amp;action=documentation\">documentation</a> for more information",
		        "optionscode" => "text",
		        "value" => "Welcome {registered}",
	          ),
	      	"react_pm" => array(
	          	"title" => "Message (PM)",
	          	"description" => "Just needed when the bot sends a PM to a new User<br />See the <a href=\"index.php?module=user-mybot&amp;action=documentation\">documentation</a> for more information",
		        "optionscode" => "textarea",
		        "value" => "Hi {registered},

welcome on {boardname}

Best regards,
{botname}",
	          ),
	      	"react_post_forum" => array(
	          	"title" => "Welcom forum",
	          	"description" => "Which forum should be used by the bot to post in?",
		        "optionscode" => "text",
		        "value" => "0",
	          ),
	      	"react_post_subject" => array(
	          	"title" => "Subject (Thread)",
	          	"description" => "Just needed when the bot posts in a forum when a new User registers<br />See the <a href=\"index.php?module=user-mybot&amp;action=documentation\">documentation</a> for more information",
		        "optionscode" => "text",
		        "value" => "Welcome {registered}",
	          ),
	      	"react_post_text" => array(
	          	"title" => "Message (Thread)",
	          	"description" => "Just needed when the bot posts in a forum when a new User registers<br />See the <a href=\"index.php?module=user-mybot&amp;action=documentation\">documentation</a> for more information",
		        "optionscode" => "textarea",
		        "value" => "Hi {registered},

welcome on {boardname}

Best regards,
{botname}",
	          ),
		)
    );

	$PL->cache_update("mybot_version", $plugininfo['version']);
}

function mybot_installed()
{
	global $install_uninstall, $codename;
	if($codename=="mybot" && $install_uninstall)
	    admin_redirect("index.php?module=config-installbot");
}

function mybot_is_installed()
{
	global $db;
	return $db->table_exists("mybot");
}

function mybot_uninstall()
{
	global $PL, $db;
    $PL or require_once PLUGINLIBRARY;
	$db->drop_table("mybot");
    $PL->settings_delete("mybot");
	$PL->cache_delete("mybot_version");
}

function mybot_admin_config_action_handler($actions)
{
	global $action;
	echo $action;
	$actions['installbot'] = array(
		"active" => "plugins",
		"file" => "installbot.php"
	);

	return $actions;
}

function mybot_admin_user_menu($sub_menu)
{
	global $lang;

	$lang->load("mybot");

	$sub_menu[] = array("id" => "mybot", "title" => $lang->mybot, "link" => "index.php?module=user-mybot");

	return $sub_menu;
}

function mybot_admin_user_action_handler($actions)
{
	$actions['mybot'] = array(
		"active" => "mybot",
		"file" => "mybot.php"
	);

	return $actions;
}

function mybot_admin_user_permissions($admin_permissions)
{
	global $lang;

	$lang->load("mybot");

	$admin_permissions['mybot'] = $lang->mybot_permission;

	return $admin_permissions;
}

function mybot_parser($text, $type="", $additional=array()) {
	global $mybb, $db;
	if(!isset($additional['botname']))
   		$additional['botname'] = $db->fetch_field($db->simple_select("users", "username", "uid='{$mybb->settings['mybot_user']}'"), "username");
	$text = str_replace('{boardname}', $mybb->settings['bbname'], $text);
	$text = str_replace('{botname}', $additional['botname'], $text);
	if($type=="register") {
		if(isset($additional['registered']))
			$text = str_replace('{registered}', $additional['registered'], $text);
	}
	if($type=="thread") {
		//We can only replace something if we had a pid
		if(isset($additional['pid'])) {
			//If no tid is set get it
			if(!isset($additional['tid'])) {
				if(isset($style) && $style['pid'] == $additional['pid'] && $style['tid']) {
					$additional['tid'] = $style['tid'];
					unset($style['tid']);
				} else {
					$options = array(
						"limit" => 1
					);
					$query = $db->simple_select("posts", "tid", "pid=".$additional['pid'], $options);
					$post = $db->fetch_array($query);
					$additional['tid'] = $post['tid'];
				}
			}
			
			$thread = get_thread($additional['tid']);
			$post = get_post($additional['pid']);
			
			//Is the first post the same as the last?
			if(!isset($additional['type'])) {
				if($thread['firstpost'] == $additional['pid'])
				    $additional['type'] = "thread";
				else
					$additional['type'] = "post";
			}
			
			//Check all informations and add the missing ones
			if(!isset($additional['post']['subject']))
			    $additional['post']['subject'] = $post['subject'];
			    
			if(!isset($additional['post']['link'])) {
		        $link = $mybb->settings['bburl']."/".get_post_link($additional['pid'], $additional['tid'])."#pid{$additional['pid']}";
				$additional['post']['link'] = "[url={$link}]{$additional['post']['subject']}[/url]";
			}
			
			if(!isset($additional['post']['message']))
			    $additional['post']['message'] = $post['message'];
			    
			if(!isset($additional['post']['timestamp']))
			    $additional['post']['timestamp'] = $post['dateline'];
			
			if(!isset($additional['post']['date']))
			    $additional['post']['date'] = date($mybb->settings['dateformat'], $additional['post']['timestamp']);
			
			if(!isset($additional['post']['time']))
			    $additional['post']['time'] = date($mybb->settings['timeformat'], $additional['post']['timestamp']);
			    
			if(!isset($additional['post']['uid']))
			    $additional['post']['uid'] = $post['uid'];
			
			if(!isset($additional['post']['user']))
			    $additional['post']['user'] = $post['username'];
			
			if(!isset($additional['post']['userlink'])) {
			    $link = $mybb->settings['bburl']."/".get_profile_link($additional['post']['uid']);
				$additional['post']['userlink'] = "[url={$link}]{$additional['post']['user']}[/url]";
			}

			//Do the same for the firstpost
			if($additional['type'] == $thread)
			    $additional['thread'] = $additional['post'];
			else {
				$post = get_post($thread['firstpost']);
	
			    $additional['thread']['subject'] = $post['subject'];

		        $link = $mybb->settings['bburl']."/".get_post_link($post['pid'], $additional['tid'])."#pid{$post['pid']}";
				$additional['thread']['link'] = "[url={$link}]{$additional['thread']['subject']}[/url]";
	
			    $additional['thread']['message'] = $post['message'];
	
			    $additional['thread']['timestamp'] = $post['dateline'];
	
			    $additional['thread']['date'] = date($mybb->settings['dateformat'], $additional['thread']['timestamp']);

			    $additional['thread']['time'] = date($mybb->settings['timeformat'], $additional['thread']['timestamp']);

			    $additional['thread']['uid'] = $post['uid'];

			    $additional['thread']['user'] = $post['username'];

			    $link = $mybb->settings['bburl']."/".get_profile_link($additional['thread']['uid']);
				$additional['thread']['userlink'] = "[url={$link}]{$additional['thread']['user']}[/url]";
			}
			
			//Get the forum
			$forum = get_forum($thread['fid']);
			$additional['thread']['forum'] = $forum['name'];

			$additional['thread']['answers'] = my_number_format($thread['replies']) +1;
			$additional['thread']['views'] = my_number_format($thread['views']) +1;

			//Now we can replace everything ;)
			$text = str_replace('{lastpost->user}', $additional['post']['user'], $text);
			$text = str_replace('{lastpost->userlink}', $additional['post']['userlink'], $text);
			$text = str_replace('{lastpost->subject}', $additional['post']['subject'], $text);
			$text = str_replace('{lastpost->id}', $additional['pid'], $text);
			$text = str_replace('{lastpost->link}', $additional['post']['link'], $text);
			$text = str_replace('{lastpost->date}', $additional['post']['date'], $text);
			$text = str_replace('{lastpost->time}', $additional['post']['time'], $text);
			$text = str_replace('{lastpost->message}', $additional['post']['message'], $text);
			$text = str_replace('{lastpost->uid}', $additional['post']['uid'], $text);
			$text = str_replace('{lastpost->timestamp}', $additional['post']['timestamp'], $text);

			$text = str_replace('{thread->user}', $additional['thread']['user'], $text);
			$text = str_replace('{thread->userlink}', $additional['thread']['userlink'], $text);
			$text = str_replace('{thread->subject}', $additional['thread']['subject'], $text);
			$text = str_replace('{thread->id}', $additional['tid'], $text);
			$text = str_replace('{thread->link}', $additional['thread']['link'], $text);
			$text = str_replace('{thread->date}', $additional['thread']['date'], $text);
			$text = str_replace('{thread->time}', $additional['thread']['time'], $text);
			$text = str_replace('{thread->message}', $additional['thread']['message'], $text);
			$text = str_replace('{thread->uid}', $additional['thread']['uid'], $text);
			$text = str_replace('{thread->timestamp}', $additional['thread']['timestamp'], $text);
			$text = str_replace('{thread->forum}', $additional['thread']['forum'], $text);
			$text = str_replace('{thread->answers}', $additional['thread']['answers'], $text);
			$text = str_replace('{thread->views}', $additional['thread']['views'], $text);
		}
	}
	return $text;
}

function mybot_register()
{
	global $mybb, $user_info, $db;
	$additional['registered'] = $user_info['username'];
	$additional['botname'] = $db->fetch_field($db->simple_select("users", "username", "uid='{$mybb->settings['mybot_user']}'"), "username");
	if($mybb->settings['mybot_react']=="pm") {
		$message = mybot_parser($mybb->settings['mybot_react_pm'], "register", $additional);
		$subject = mybot_parser($mybb->settings['mybot_react_pm_subject'], "register", $additional);
		//Write PM
		require_once MYBB_ROOT."inc/datahandlers/pm.php";
		$pmhandler = new PMDataHandler();

		$pm = array(
			"subject" => $subject,
			"message" => $message,
			"icon" => "",
			"fromid" => $mybb->settings['mybot_user'],
			"do" => "",
			"pmid" => "",
		);
		$pm['toid'][] = $user_info['uid'];
		$pmhandler->set_data($pm);

		// Now let the pm handler do all the hard work.
		if($pmhandler->validate_pm())
		{
			$pminfo = $pmhandler->insert_pm();
		}else {
			$pm_errors = $pmhandler->get_friendly_errors();
			$send_errors = inline_error($pm_errors);
			echo $send_errors;
		}
	} elseif($mybb->settings['mybot_react']=="post") {
		//Write Post
		$message = mybot_parser($mybb->settings['mybot_react_post_text'], "register", $additional);
		$subject = mybot_parser($mybb->settings['mybot_react_post_subject'], "register", $additional);
        require_once  MYBB_ROOT."inc/datahandlers/post.php";
        $posthandler = new PostDataHandler("insert");
        $posthandler->action = "thread";

        // Set the thread data that came from the input to the $thread array.
        $new_thread = array(
        	"fid" => $mybb->settings['mybot_react_post_forum'],
            "subject" => $subject,
            "prefix" => "",
            "icon" => "",
            "uid" => $mybb->settings['mybot_user'],
            "username" => $additional['botname'],
            "message" => $message,
            "ipaddress" => get_ip()
        );
        $posthandler->set_data($new_thread);
        $valid_thread = $posthandler->validate_thread();
		if($valid_thread) {
	        $posthandler->insert_thread();
		}
	} else
		return;
}

function mybot_cache_update($load = true, $rules = array())
{
	global $PL, $db;
    $PL or require_once PLUGINLIBRARY;
	if($load) {
	    $query = $db->simple_select("mybot");
		while($rule = $db->fetch_array($query))
		    $rules[] = $rule;
	}

	for($i=0; $i<sizeof($rules); $i++) {
		if(!is_Array($rules[$i]['conditions']))
		    $rules[$i]['conditions'] = @unserialize($rules[$i]['conditions']);
		if(!is_Array($rules[$i]['actions']))
		    $rules[$i]['actions'] = @unserialize($rules[$i]['actions']);
	}
	return $PL->cache_update("mybot_rules", $rules);
}

function mybot_cache_load($id = false)
{
	global $PL;
    $PL or require_once PLUGINLIBRARY;

	$content = $PL->cache_read("mybot_rules");
	if(!is_array($content))
	    $content = mybot_cache_update();
	if(!$id)
		return $content;
	foreach($content as $rid => $rule) {
		if($rule['id']==$id)
		    $rrid[] = $rid;
	}
	if(sizeOf($rrid)!=1)
	    return false;
	return $content[$rrid[0]];
}

function mybot_post()
{
	global $post, $postinfo;
	$post['pid'] = $postinfo['pid'];
	mybot_work($post, "post");
}

function mybot_thread()
{
	global $new_thread, $thread_info;
	$new_thread['tid'] = $thread_info['tid'];
	$new_thread['pid'] = $thread_info['pid'];
	mybot_work($new_thread, "thread");
}

function mybot_work($info, $type)
{
	global $PL, $db, $mybb, $groupscache;
    require_once MYBB_ROOT."inc/datahandlers/post.php";
 	$posthandler = new PostDataHandler("insert");
	require_once MYBB_ROOT."inc/class_moderation.php";
	$moderation = new Moderation;
	require_once MYBB_ROOT."inc/datahandlers/pm.php";
	$pmhandler = new PMDataHandler();

	$rules = mybot_cache_load();
	$user = $db->simple_select("users", "usergroup, additionalgroups, displaygroup", "uid='{$info['uid']}'");
	$user = $db->fetch_array($user);
	$additional['botname'] = $db->fetch_field($db->simple_select("users", "username", "uid='{$mybb->settings['mybot_user']}'"), "username");
	if(!$user['displaygroup'])
	{
		$user['displaygroup'] = $user['usergroup'];
	}
	$usergroup = $groupscache[$user['displaygroup']];
	$thread = get_thread($info['tid']);
	$active = array();
	foreach($rules as $rule) {
		if(!@in_array($info['uid'], $rule['conditions']['user']) && array_key_exists("user", $rule['conditions'])) {
			continue;
		}
		if(!@in_array($usergroup['gid'], $rule['conditions']['group']) && array_key_exists("group", $rule['conditions'])) {
		    continue;
		}
		if(!@in_array($info['fid'], $rule['conditions']['forum']) && array_key_exists("forum", $rule['conditions'])) {
		    continue;
		}
		if(strpos($info['message'], $rule['conditions']['string']) === false && array_key_exists("string", $rule['conditions'])) {
		    continue;
		}
		if($thread['firstpost'] != $info['pid'] && array_key_exists("thread", $rule['conditions'])) {
		    continue;
		}
		$active[] = $rule;
	}
	$rules = $active;


	$pid = $info ['pid'];
	$additional['post'] = $info;
	$additional['pid'] = $pid;
	$additional['post']['timestamp'] = $info['dateline'];
	$date = time();
	$date++;
	foreach($rules as $rule) {
		if(array_key_exists("answer", $rule['actions'])) {
            $subject = preg_replace('#RE:\s?#i', '', $info['subject']);
            $subject = "RE: ".$subject;
	        // Set the post data that came from the input to the $post array.
	        $post = array(
	        	"tid" => $info['tid'],
	            "replyto" => $pid,
	            "fid" => $info['fid'],
	            "subject" => $subject,
	            "icon" => "",
	            "uid" => $mybb->settings['mybot_user'],
	            "username" => $additional['botname'],
	            "message" => mybot_parser($rule['actions']['answer'], "thread", $additional),
	            "ipaddress" => get_ip(),
	            "dateline" => $date
	    	);
	        $posthandler->set_data($post);
	        $valid_thread = $posthandler->validate_post();
			if(!$valid_thread)
			{
		        echo inline_error($posthandler->get_friendly_errors());
			}
	        $ninfo = $posthandler->insert_post();
	        $pid = $ninfo['pid'];
	        $date++;
		}

		if(array_key_exists("move", $rule['actions'])) {
			$info['tid'] = $moderation->move_thread($info['tid'], $rule['actions']['move']);
			$thread = get_thread($info['tid']);
		}

		if(array_key_exists("delete", $rule['actions'])) {
			if($rule['actions']['delete'] == "thread" || $thread['firstpost'] == $info['pid'])
				$moderation->delete_thread($info['tid']);
			else
				$moderation->delete_post($info['pid']);
		}

    	if(array_key_exists("stick", $rule['actions'])) {
			if($thread['sticky'] == 1)
				$moderation->unstick_threads($info['tid']);
			else
				$moderation->stick_threads($info['tid']);
		}
		
    	if(array_key_exists("close", $rule['actions'])) {
			if($thread['closed'] == 1)
				$moderation->open_threads($info['tid']);
			else
				$moderation->close_threads($info['tid']);
		}

		if(array_key_exists("pm", $rule['actions'])) {
		    if($rule['actions']['pm']['user'] == "last")
				$rule['actions']['pm']['user'] = $info['uid'];
			elseif($rule['actions']['pm']['user'] == "start") {
				$post = get_post($thread['firstpost']);
				$rule['actions']['pm']['user'] = $post['uid'];
			}
		    
			$pm = array(
				"subject" => mybot_parser($rule['actions']['pm']['subject'], "thread", $additional),
				"message" => mybot_parser($rule['actions']['pm']['message'], "thread", $additional),
				"icon" => "",
				"fromid" => $mybb->settings['mybot_user'],
				"do" => "",
				"pmid" => "",
			);
			$pm['toid'][] = $rule['actions']['pm']['user'];
			$pmhandler->set_data($pm);
	
			// Now let the pm handler do all the hard work.
			if($pmhandler->validate_pm())
			{
				$pminfo = $pmhandler->insert_pm();
			}else {
				$pm_errors = $pmhandler->get_friendly_errors();
				$send_errors = inline_error($pm_errors);
				echo $send_errors;
			}
		}
	}
}

function mybot_activate()
{}

function mybot_deactivate()
{}
?>