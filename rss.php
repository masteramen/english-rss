<?php
/*
Template Name: RSS
*/
?>
<?php 
define('DONOTCACHEPAGE',1);


/* ʵ�� -----------------------------------------------
    $feed = new RSS();
    $feed->title       = "RSS Feed ����";
    $feed->link        = "http://runoob.com";
    $feed->description = "RSS �����б�������";

    $db->query($query);  // ���ݿ��ѯ
    $result = $db->result;
    while($row = mysql_fetch_array($result, MYSQL_ASSOC))
    {
        $item = new RSSItem();
        $item->title = $title;
        $item->link  = $link;
        $item->setPubDate($create_date); 
        $item->description = "<![CDATA[ $html ]]>";
        $feed->addItem($item);
    }
    echo $feed->serve();
---------------------------------------------------------------- */

	
class RSS
{
    var $title;
    var $link;
    var $description;
    var $language = "en-us";
    var $pubDate;
    var $items;
    var $tags;

    function RSS()
    {
        $this->items = array();
        $this->tags  = array();
    }

    function addItem($item)
    {
        $this->items[] = $item;
    }

    function setPubDate($when)
    {
        if(strtotime($when) == false)
            $this->pubDate = date("D, d M Y H:i:s ", $when) . "GMT";
        else
            $this->pubDate = date("D, d M Y H:i:s ", strtotime($when)) . "GMT";
    }

    function getPubDate()
    {
            if(empty($this->pubDate))
            return date("D, d M Y H:i:s ") . "GMT";
        else
            return $this->pubDate;
    }

    function addTag($tag, $value)
    {
        $this->tags[$tag] = $value;
    }

    function out()
    {
        $out  = $this->header();
        $out .= "<channel>\n";
        $out .= "<title>" . htmlentities($this->title) . "</title>\n";
        $out .= "<link>" . htmlentities($this->link) . "</link>\n";
        $out .= "<description>" . htmlentities($this->description) . "</description>\n";
        $out .= "<language>" . $this->language . "</language>\n";
        $out .= "<pubDate>" . $this->getPubDate() . "</pubDate>\n";
        foreach($this->tags as $key => $val) $out .= "<$key>$val</$key>\n";
        foreach($this->items as $item) $out .= $item->out();

        $out .= "</channel>\n";

        $out .= $this->footer();

       // $out = str_replace("&", "&amp;", $out);

        return $out;
    }

    function serve($contentType = "application/xml")
    {
        $xml = $this->out();

        //header("Content-type: $contentType");
		//header("Content-length: ".strlen($xml));
		//header('Content-Type: application/rss+xml; charset=ISO-8859-1');
		header('Content-Type: application/rss+xml; charset=utf-8');  

        echo $xml;

    }

    function header()
    {
        $out  = '<?xml version="1.0" encoding="utf-8"?>' . "\n";
        $out .= '<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/">' . "\n";
        return $out;
    }

    function footer()
    {
        return '</rss>';
    }
}

class RSSItem
{
    var $title;
    var $link;
    var $description;
    var $pubDate;
    var $guid;
    var $tags;
    var $attachment;
    var $length;
    var $mimetype;

    function RSSItem()
    { 
        $this->tags = array();
    }

    function setPubDate($when)
    {
        if(strtotime($when) == false)
            $this->pubDate = date("D, d M Y H:i:s ", $when) . "GMT";
        else
            $this->pubDate = date("D, d M Y H:i:s ", strtotime($when)) . "GMT";
    }

    function getPubDate()
    {
        if(empty($this->pubDate))
            return date("D, d M Y H:i:s ") . "GMT";
        else
            return $this->pubDate;
    }

    function addTag($tag, $value)
    {
        $this->tags[$tag] = $value;
    }

    function out()
    {
        $out = "<item>\n";
        $out .= "<title>" . $this->title . "</title>\n";
        $out .= "<link>" . $this->link . "</link>\n";
        $out .= "<description>" . $this->description . "</description>\n";
        $out .= "<pubDate>" . $this->getPubDate() . "</pubDate>\n";

        if($this->attachment != "")
            $out .= "<enclosure url='{$this->attachment}' length='{$this->length}' type='{$this->mimetype}' />";

        if(empty($this->guid)) $this->guid = $this->link;
        $out .= "<guid>" . $this->guid . "</guid>\n";

        foreach($this->tags as $key => $val) $out .= "<$key>$val</$key\n>";
        $out .= "</item>\n";
        return $out;
    }

    function enclosure($url, $mimetype, $length)
    {
        $this->attachment = $url;
        $this->mimetype   = $mimetype;
        $this->length     = $length;
    }
}
/*
$feed = new RSS();

 $feed->title       = "RSS Feed 标题";
    $feed->link        = "http://www.jfox.info";
    $feed->description = "RSS 订阅列表描述。";

        $item = new RSSItem();
        $item->title = $_GET['title'];
        $item->link  = $_GET['link'];
       // $item->setPubDate(time()); 
        $item->description = "<![CDATA[]]>";
        $feed->addItem($item);
    
    echo $feed->serve();*/
	