<?php
define('WP_HOME', dirname(__FILE__)."/../");
require_once(WP_HOME.'wp-blog-header.php');
require_once(WP_HOME.'wp-admin/includes/upgrade.php' );

function log_msg($msg) {
	echo ($msg . "\n");
}
header ( 'HTTP/1.1 200 OK' );

global $wpdb;

$media_resource = $wpdb->prefix . 'media_resource';
createTable ( $media_resource, "CREATE TABLE " . $media_resource . " (
			`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			`uid` varchar(80) NOT NULL DEFAULT '',
			`channel_id` bigint(20) UNSIGNED NOT NULL,
			`title` varchar(255) NOT NULL DEFAULT '',
			`link` varchar(255) NOT NULL,
			`conver_url` varchar(255) NOT NULL DEFAULT '',
			`media_url` varchar(255) NOT NULL DEFAULT '',
			`media_type` int(1) NOT NULL DEFAULT 3,
			`pub_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
			`create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (`id`) 
			,UNIQUE KEY `link_uk` (`link`) 
			,UNIQUE KEY `title_uk` (`title`) 
		) " );



$media_channel = $wpdb->prefix . 'media_channel';
createTable ( $media_channel, "CREATE TABLE " . $media_channel . " (
			`channel_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			`channel_code` varchar(80) NOT NULL DEFAULT '',
			`channel_title` varchar(255) NOT NULL DEFAULT '',
			`channel_description` varchar(255) NOT NULL,
			`channel_conver` varchar(255) NOT NULL DEFAULT '',
			`channel_type` varchar(10) NOT NULL DEFAULT '',
			`channel_create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (`channel_id`)
			,UNIQUE KEY `channel_code_uk` (`channel_code`) 
			,UNIQUE KEY `channel_title_uk` (`channel_title`) 
		) " );
$wpdb->query("ALTER TABLE $media_resource CHANGE title title VARCHAR(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$wpdb->query("ALTER TABLE $media_channel add column `fetch_time` TIMESTAMP");

/*
$wpdb->query ( "alter table $db_table_user add valid_time datetime" );
$wpdb->query ( "alter table $db_table_user add last_login_time datetime" );
$wpdb->query ( "alter table $db_table_user add u_level int(3) not null default '0'" );
$wpdb->query ( "alter table $db_table_user add url varchar(255)" );
$wpdb->query ( "alter table $db_table_user add avatar_url varchar(255)" );
$wpdb->query ( "alter table $db_table_user add extra_content varchar(255)" );
$wpdb->query ( "ALTER TABLE $db_table_user DROP INDEX  email_uid" );

// ========================================================
$db_table = $wpdb->prefix . 'my_group';

createTable ( $db_table, "CREATE TABLE " . $db_table . " (
			`gid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			`create_uid` bigint(20) NOT NULL,
			`group_name` varchar(30) NOT NULL DEFAULT '',
			`group_tags` varchar(60) NOT NULL DEFAULT '',
			`group_category` varchar(60) NOT NULL DEFAULT '',
			`group_desc` longtext NOT NULL DEFAULT '',
			`join_question` varchar(60) NOT NULL,
			`join_answer` varchar(60) NOT NULL,
		  `group_type` char(1) NOT NULL default '0', 
			`create_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
      PRIMARY KEY (`gid`),
      UNIQUE KEY `create_uid_group_name` (`group_name`,`create_uid`) 
		) " );

$db_table = $wpdb->prefix . 'my_user_group';
createTable ( $db_table, "CREATE TABLE " . $db_table . " (
    `gid` bigint(20) UNSIGNED,
    `uid` bigint(20) UNSIGNED,
    `is_quit` char(1) not null default '0',
    `group_score` int(10) NOT NULL default '0', 
    `group_level` int(3) NOT NULL default '0', 
    `join_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    UNIQUE KEY `uni_uid_gid` (`gid`,`uid`) 
  ) " );
// ========================================================
$db_table = $wpdb->prefix . 'my_invite_code';
createTable ( $db_table, "CREATE TABLE " . $db_table . " (
    `invite_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `gid` bigint(20) UNSIGNED,
    `uid` bigint(20) UNSIGNED,
    `invite_name` varchar(255),
    `invite_code` varchar(255),
    `is_enable` char(1) not null default '1',
    `create_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    PRIMARY KEY (`invite_id`)
  ) " );


$db_table_url = $wpdb->prefix . 'website_url';
createTable ( $db_table_url, "CREATE TABLE " . $db_table_url . " (
			`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			`type` varchar(100) NOT NULL DEFAULT '',
			`website_url` varchar(200) NOT NULL DEFAULT '' ,
			`m_desc` longtext,
			`m_count` int(10) NOT NULL default '1',
			PRIMARY KEY (`id`),UNIQUE KEY `website_url_unid` (`website_url`) 
		) " );

$wpdb->query ( "alter table $db_table_url modify   website_url varchar(330)" );

$db_table_name = $wpdb->prefix . 'websites';
createTable ( $db_table_name, "CREATE TABLE " . $db_table_name . " (
			`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			`type` varchar(100) NOT NULL DEFAULT '',
			`title` varchar(200) NOT NULL,
			`title2` varchar(200) NOT NULL,
			`date_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			PRIMARY KEY (`id`)
		) " );

$wpdb->query ( "alter table $db_table_name add title2 varchar(200)" );
$wpdb->query ( "alter table $db_table_name add is_share char(1) NOT NULL default '0' " );
$wpdb->query ( "alter table $db_table_name add is_link char(1) NOT NULL default '0' " );
$wpdb->query ( "alter table $db_table_name add is_delete char(1) NOT NULL default '0' " );
$wpdb->query ( "alter table $db_table_name add is_wall char(1) NOT NULL default '0' " );
$wpdb->query ( "alter table $db_table_name add post_id bigint(20) " );
$wpdb->query ( "alter table $db_table_name add uid bigint(20) NOT NULL DEFAULT '1' " );
$wpdb->query ( "alter table $db_table_name change  extra title varchar(200)" );
$wpdb->query ( "alter table $db_table_name MODIFY  title varchar(200)" );
$wpdb->query ( "alter table $db_table_name add sdesc longtext" );
$wpdb->query ( "alter table $db_table_name MODIFY  sdesc longtext" );
$wpdb->query ( "alter table $db_table_name add seq_id varchar(20)" );
$wpdb->query ( "ALTER TABLE $db_table_name DROP INDEX  website_url_uid" );
$wpdb->query ( "alter table $db_table_name add gid bigint(20)" );
// $wpdb->query("ALTER TABLE $db_table_name DROP column website_url");
// $wpdb->query("ALTER TABLE $db_table_name ADD CONSTRAINT website_url_uid UNIQUE (uid,website_url)");
$wpdb->query ( "alter table $db_table_name add column url_id bigint(20)" );
$wpdb->query ( "alter table $db_table_name add column referer_url_id bigint(20)" );
// $wpdb->query("alter table $db_table_name add column note_id bigint(20)");
$wpdb->query ( "alter table $db_table_name add column note_type int(3) not null default '1'" );
$wpdb->query ( "alter table $db_table_name add constraint FK_website_url_id foreign key(url_id) references $db_table_url(id);" );
// $wpdb->query("ALTER TABLE $db_table_name ADD CONSTRAINT uid_url_uid UNIQUE (uid,url_id)");
$wpdb->query ( "ALTER TABLE $db_table_name DROP INDEX uid_url_uid;" );

/*
 * $sql=<<<EOF
 * insert into $db_table_url(id,website_url)
 * select * from(
 * select @i:=@i+1 as id, t.* from (select website_url from $db_table_name t where website_url is not null or website_url!='') t ,(SELECT @i :=(select ifnull(max(a.id),0) from $db_table_url a)) r ) tt where website_url is not null and website_url like 'http%'
 * and not exists (select 1 from $db_table_url a where a.website_url= tt.website_url)
 * EOF;
 * $wpdb->query($sql);
 * $wpdb->query("delete from $db_table_url where website_url =''");
 * log_msg( 'update count:'.$wpdb->query("update $db_table_name a set url_id = (select id from $db_table_url where website_url=a.website_url) where exists(select 1 from wp_website_url tu where tu.website_url=a.website_url)"));
 *
 * log_msg( "count:".$wpdb->get_var("select count(*) from $db_table_url"));
 *
 * log_msg( 'dbDelta');
 */

// ========================================================
/*
$db_table_name = $wpdb->prefix . "note";
createTable ( $db_table_name, "create table $db_table_name(
		`note_id` bigint(20),
	    `uid` bigint(20) NOT NULL DEFAULT '1',
	    `url_id` bigint(20),
	    `note_title` varchar(200),
		`note_content` longtext,
	    `note_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		`note_type` varchar(255)  ,
		 PRIMARY KEY (`note_id`),UNIQUE KEY `note_content_uid` (`note_title`,`uid`) 
		)" );
$wpdb->query ( "ALTER TABLE $db_table_name DROP INDEX note_content_uid;" );
$wpdb->query ( "alter table $db_table_name MODIFY  note_content longtext" );
$wpdb->query ( "alter table $db_table_name add gid bigint(20)" );


$db_table_name = $wpdb->prefix . "item";
createTable ( $db_table_name, "create table $db_table_name(
		`item_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
		`uid` bigint(20) NOT NULL DEFAULT '1',
		`item_size` bigint(20),
		`item_width` bigint(20),
		`item_height` bigint(20),
		`file_name` varchar(200),
		`title` varchar(200),
		`media_type` varchar(30),
		`event_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		`update_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
		`item_type` varchar(255)  ,
		PRIMARY KEY (`item_id`),UNIQUE KEY `item_uid` (`event_time`,`item_size`,`item_width`,`item_height`)
		)" );
*/

/**
 *
 * @param
 *        	db_table
 */
function is_exists_table($db_table) {
	global $wpdb;
	return $wpdb->get_var ( "SHOW TABLES LIKE '$db_table'" ) == $db_table;
}

/**
 *
 * @param
 *        	charset_collate
 * @param
 *        	sql
 */
function createTable($db_table_name, $createSql) {
	global $wpdb;
	//log_msg ( "init $db_table_name" );
	
	if (! is_exists_table ( $db_table_name )) {
		if (! empty ( $wpdb->charset ))
			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		if (! empty ( $wpdb->collate ))
			$charset_collate .= " COLLATE $wpdb->collate";
		
		$sql = "$createSql  $charset_collate";
		
		dbDelta ( $sql );
	}

}

function insertMediaResource($data){
		global $wpdb;
		$wpdb->insert(  $wpdb->prefix . 'media_resource', $data );

}
function insertMediaChannel($data){
		global $wpdb;
		$wpdb->insert(  $wpdb->prefix . 'media_channel', $data );
		return $wpdb->get_var($wpdb->prepare("select channel_id from {$wpdb->prefix}media_channel where channel_code=%s",$data['channel_code']));

}


