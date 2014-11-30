<?php

class JB_MyBot_Version_V1400 extends JB_Version_Base
{
	static function execute()
	{
		global $cache, $db, $PL;

		// Update setting types (use the 1.8 ones)
		$update = array(
			"mybot_user"				=> "numeric",
			"mybot_react_post_forum"	=> "numeric",
			"mybot_bday_post_forum"		=> "numeric"
		);
		foreach($update as $setting => $type)
		{
			$db->update_query("settings", array("optionscode" => $type), "name='{$setting}'");
		}

		// Move our cache to the mybb datacache
		if(file_exists(MYBB_ROOT."inc/plugins/pluginlibrary.php"))
		{
			$PL or require_once MYBB_ROOT."inc/plugins/pluginlibrary.php";
			$PL->cache_delete("mybot_version"); // Can be deleted without any problems, not used anyways
			$rules = $PL->cache_read("mybot_rules");
			$bday = $PL->cache_read("mybot_birthday");
			$cache->update("mybot_rules", $rules);
			$cache->update("mybot_birthday", $bday);
			$PL->cache_delete("mybot_rules");
			$PL->cache_delete("mybot_birthday");
		}
	}
}