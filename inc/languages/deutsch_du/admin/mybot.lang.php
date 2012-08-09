<?php
$l['mybot'] = "MyBot";
$l['mybot_permission'] = "Kann MyBot konfigurieren?";


/* Admin Modul */
$l['mybot_overview'] = "Übersicht über alle Regeln";
$l['mybot_addrule'] = "Neue Regel hinzufügen";
$l['mybot_addrule_desc'] = "Hier kannst du eine neue Regel hinzufügen";
$l['mybot_post'] = "Als Bot posten";
$l['mybot_post_desc'] = "Hier kannst du als Bot posten";
$l['mybot_documentation'] = "Dokumentation";
$l['mybot_documentation_desc'] = "Hier siehst du alle Variablen die du in unterschiedlichen Texten einsetzen kannst";
$l['mybot_cache_reload'] = "Cache neuladen";
$l['mybot_no_id'] = "Keine ID angegeben";
$l['mybot_no_rules'] = "Bisher keine Regeln vorhanden";

/* Auflistung */
$l['mybot_title'] = "Titel";
$l['mybot_conditions'] = "Bedingungen";
$l['mybot_conditions_user'] = "Benutzer";
$l['mybot_conditions_group'] = "Benutzergruppe";
$l['mybot_conditions_forum'] = "Forum";
$l['mybot_conditions_string'] = "Zeichenkette";
$l['mybot_conditions_thread'] = "Nur Thread";
$l['mybot_actions'] = "Aktionen";
$l['mybot_actions_answer'] = "Antworten";
$l['mybot_actions_move'] = "Verschieben";
$l['mybot_actions_delete'] = "Löschen";
$l['mybot_actions_stick'] = "Als wichtig markieren";
$l['mybot_actions_close'] = "Öffnen/Schließen";
$l['mybot_actions_pm'] = "Nachricht schreiben";

/* Cache Update */
$l['mybot_cache_reloaded'] = "Cache neugeladen";

/* Documentation */
$l['mybot_variable'] = "Variable";
$l['mybot_description'] = "Beschreibung";
$l['mybot_global'] = "Globale Variablen";
$l['mybot_doc_boardname'] = "Der Name deines Forums";
$l['mybot_doc_botname'] = "Der Name deines Bots";
$l['mybot_register'] = "Variablen für Registrierung";
$l['mybot_doc_registered'] = "Der Name des Neuregistrierten";
$l['mybot_thread'] = "Variablen für Regeln";
$l['mybot_doc_user'] = "Der Name des Posters";
$l['mybot_doc_userlink'] = "Der Name des Posters mit MyCode Link zum Profil";
$l['mybot_doc_subject'] = "Der Titel des Beitrags";
$l['mybot_doc_id'] = "Die interne ID des Beitrags";
$l['mybot_doc_link'] = "Link zum Beitrag mit MyCode erstellt. Text ist gleich dem Titel des Beitrags";
$l['mybot_doc_date'] = "Datum an dem der Beitrag erstellt wurde";
$l['mybot_doc_time'] = "Uhrzeit an der der Beitrag erstellt wurde";
$l['mybot_doc_message'] = "Der komplette Text des Beitrags";
$l['mybot_doc_uid'] = "Die ID des Users";
$l['mybot_doc_timestamp'] = "Der Zeitstempel zur Beitragserstellung";
$l['mybot_doc_thread'] = "Alle Variablen für den letzten Beitrag sind ebenso für das Thema anwendbar<br />Beispiel:<br />{lastpost->user} wird durch den Namen des Users der den letzten Beitrag geschrieben hat ersetzt. Dann wird {thread->user} durch den Namen des Users ersetzt, welcher das Thema eröffnet hat";
$l['mybot_doc_forum'] = "Der Name des Forums in welchem das Thema erstellt wurde";
$l['mybot_doc_answers'] = "Anzahl der Antworten auf das Thema";
$l['mybot_doc_views'] = "Anzahl der Aufrufe des Themas";

/* Post as Bot */
$l['mybot_post_forum'] = "Forum";
$l['mybot_post_forum_desc'] = "In welchem Forum soll gepostet werden?";
$l['mybot_post_forum_not'] = "Interner Fehler [1]";
$l['mybot_post_subject'] = "Titel";
$l['mybot_post_subject_desc'] = "Der Titel des Threads";
$l['mybot_post_subject_not'] = "Kein Titel angegeben";
$l['mybot_post_text'] = "Text";
$l['mybot_post_text_desc'] = "Der Beitrag der gepostet werden soll. MyCode und Smileys aktiviert.<br />Achtung: MyBot Variablen sind hier nicht verfügbar";
$l['mybot_post_text_not'] = "Kein Text angegeben";
$l['mybot_post_submit'] = "Post absenden";
$l['mybot_post_category'] = "Dein gewähltes Forum ist eine Kategorie in welcher nicht gepostet werden kann.";
$l['mybot_post_inserted'] = "Thema gespeichert";

/* Add/Edit/Delete Rule */
$l['mybot_editrule'] = "Regel bearbeiten";
$l['mybot_add_title'] = "Titel der Regel";
$l['mybot_add_title_desc'] = "Wird nur für die Auflistung benötigt und sollte ein eindeutiger Name sein";
$l['mybot_add_title_not'] = "Bitte gebe einen Titel an";
$l['mybot_add_conditions'] = "Bedingungen";
$l['mybot_add_conditions_desc'] = "Wann soll diese Regel Anwendung finden?";
$l['mybot_add_conditions_user'] = "Wenn ein bestimmter User postet";
$l['mybot_add_conditions_group'] = "Wenn User einer bestimmten Benutzergruppe postet";
$l['mybot_add_conditions_forum'] = "Nur in bestimmten Foren";
$l['mybot_add_conditions_string'] = "Wenn eine bestimmte Zeichenkette vorkommt";
$l['mybot_add_conditions_thread'] = "Nur bei Thread Start";
$l['mybot_add_conditions_not'] = "Bitte wähle mindestens eine Bedingung";
$l['mybot_add_action'] = "Aktionen";
$l['mybot_add_action_desc'] = "Was soll der Bot tuen?";
$l['mybot_add_action_answer'] = "Antworten";
$l['mybot_add_action_move'] = "Beitrag/Thema verschieben";
$l['mybot_add_action_delete'] = "Beitrag/Thema löschen";
$l['mybot_add_action_stick'] = "Thema als wichtig/unwichtig markieren";
$l['mybot_add_action_close'] = "Thema öffnen/schließen";
$l['mybot_add_action_pm'] = "Private Nachricht schicken";
$l['mybot_add_action_not'] = "Bitte wähle mindestens eine Aktion";
$l['mybot_add_user'] = "User";
$l['mybot_add_user_desc'] = "Auf welchen User soll der Bot reagieren?";
$l['mybot_add_user_not'] = "Bitte wähle einen User aus";
$l['mybot_add_group'] = "Benutzergruppe";
$l['mybot_add_group_desc'] = "Auf welche Benutzergruppe soll der Bot reagieren?";
$l['mybot_add_group_not'] = "Bitte wähle eine Benutzergruppe aus";
$l['mybot_add_forum'] = "Forum";
$l['mybot_add_forum_desc'] = "In welchem Forum soll der Bot reagieren?";
$l['mybot_add_forum_not'] = "Bitte wähle ein Forum aus";
$l['mybot_add_string'] = "Zeichenkette";
$l['mybot_add_string_desc'] = "Welche Zeichenkette muss in dem Beitrag vorkommen damit der Bot reagiert?";
$l['mybot_add_string_not'] = "Bitte gebe eine Zeichenkette an";
$l['mybot_add_answer'] = "Antwort";
$l['mybot_add_answer_desc'] = "Mit was soll der Bot antworten?<br />Siehe die <a href=\"index.php?module=user-mybot&amp;action=documentation\">Dokumentation</a> für mehr Informationen";
$l['mybot_add_answer_not'] = "Bitte gebe eine Antwort an";
$l['mybot_add_move'] = "Verschieben";
$l['mybot_add_move_desc'] = "Wohin soll das Thema verschoben werden?";
$l['mybot_add_move_not'] = "Bitte gebe an wohin das Thema verschoben werden soll";
$l['mybot_add_move_invalid'] = "In dieses Forum kann kein Thema verschoben werden. Bitte wähle ein anderes";
$l['mybot_add_delete'] = "Löschen";
$l['mybot_add_delete_desc'] = "Soll das ganze Thema gelöscht werden oder nur der letzte Beitrag?";
$l['mybot_add_delete_not'] = "Interner Fehler [1]";
$l['thread'] = "Thema";
$l['post'] = "Beitrag";
$l['mybot_add_pm'] = "Private Nachricht";
$l['mybot_add_pm_desc'] = "An wen soll die Nachricht verschickt werden?";
$l['mybot_add_pm_not'] = "Interner Fehler [2]";
$l['mybot_add_pm_last'] = "Letzten Poster";
$l['mybot_add_pm_start'] = "Threadstarter";
$l['mybot_add_pm_other'] = "Anderer User";
$l['mybot_add_pm_user'] = "User";
$l['mybot_add_pm_user_desc'] = "An welchen User soll die Nachricht verschickt werden?";
$l['mybot_add_pm_user_not'] = "Bitte wähle einen User aus";
$l['mybot_add_subject'] = "Betreff";
$l['mybot_add_subject_desc'] = "Welchen Betreff soll die Nachricht haben?<br />Siehe die <a href=\"index.php?module=user-mybot&amp;action=documentation\">Dokumentation</a> für mehr Informationen";
$l['mybot_add_subject_not'] = "Bitte gebe einen Betreff an";
$l['mybot_add_message'] = "Nachricht";
$l['mybot_add_message_desc'] = "Was soll der Bot schreiben?<br />Siehe die <a href=\"index.php?module=user-mybot&amp;action=documentation\">Dokumentation</a> für mehr Informationen";
$l['mybot_add_message_not'] = "Bitte gebe eine Nachricht an";
$l['mybot_add_added'] = "Regel hinzugefügt";
$l['mybot_add_edited'] = "Regel bearbeitet";
$l['mybot_delete_confirm'] = "Möchtest du diese Regel wirklich löschen?";
$l['mybot_delete_success'] = "Regel gelöscht";

/* Install Process */
$l['mybot_installing'] = "Installiere MyBot";
//$l['mybot_already_installed'] = "MyBot ist bereits installiert";
$l['mybot_create_user'] = "Neuen Benutzer anlegen";
$l['mybot_create_group'] = "Neue Gruppe anlegen";
$l['mybot_user'] = "Benutzer";
$l['mybot_user_desc'] = "Welcher Benutzer soll als Bot fungieren?";
$l['mybot_user_not'] = "Interner Fehler [1]";
$l['mybot_username'] = "Benutzername";
$l['mybot_username_desc'] = "Wie soll der neue Benutzer heißen?";
$l['mybot_username_not'] = "Kein Benutzername angegeben";
$l['mybot_pw'] = "Passwort";
$l['mybot_pw_desc'] = "Bitte gebe ein Passwort für den neuen Benutzer an";
$l['mybot_pw_not'] = "Kein Passwort angegeben";
$l['mybot_email'] = "E-Mail";
$l['mybot_email_desc'] = "Bitte gebe die E-Mail für den neuen Benutzer an";
$l['mybot_email_not'] = "Keine E-Mail angegeben";
$l['mybot_group'] = "Gruppe";
$l['mybot_group_desc'] = "Welcher Gruppe soll der neue Benutzer angehören?";
$l['mybot_group_not'] = "Interner Fehler [2]";
$l['mybot_groupname'] = "Gruppenname";
$l['mybot_groupname_desc'] = "Wie soll die neue Gruppe heißen?";
$l['mybot_groupname_not'] = "Kein Gruppenname angegeben";
$l['mybot_save'] = "Speichern";
$l['mybot_installed_group'] = "{1}<br />User \"{2}\" und Gruppe \"{3}\" neu hinzugefügt<br />Bitte bearbeite diese noch einmal zur Sicherheit";
$l['mybot_installed_user'] = "{1}<br />User \"{2}\" neu hinzugefügt<br />Bitte bearbeite diesen noch einmal zur Sicherheit";
$l['mybot_installed'] = "MyBot wurde erfolgreich installiert.";

$l['mybot_pl_missing'] = "Die Installation konnte nicht gestartet werden, da die <b><a href=\"http://mods.mybb.com/view/pluginlibrary\">PluginLibrary</a></b> fehlt";
$l['mybot_pl_old'] = "Die Installation konnte nicht gestartet werden, da die <b><a href=\"http://mods.mybb.com/view/pluginlibrary\">PluginLibrary</a></b> zu alt ist (Version 8 oder höher erforderlich)";
?>