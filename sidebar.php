<?php
/**
 * A simple bookmark list.
 */


/**
 * @return string   hostname part of HTTP_HOST
 */
function get_http_hostname()
{
    return $http_host = explode(":",$_SERVER['HTTP_HOST'])[0];
}
/**
 * @return string  port part of HTTP_HOST
 */
function get_http_port()
{
    $http_host = explode(":",$_SERVER['HTTP_HOST']);
    if(count($http_host) < 2) return  80;
    else return $http_host[1];
}


/**
 * @param $jsonfile path to the data.json
 * @return array
 */
function load_data_from_json($jsonfile)
{
    $json = file_get_contents($jsonfile);
    $json_data = json_decode($json,true);

    $data['title'] = $json_data['title'];

    $data['bookmarks'] = array();
    foreach($json_data['bookmarks'] as $bookmark)
    {
        $data['bookmarks'][] = new Bookmark($bookmark);
    }
    return $data;
}

/**
 * Class Bookmark
 */
class Bookmark
{
    private $label;
    private $info;
    private $url;

    function __construct($args=[])
    {
        $this->label = $args['label'];
        $this->url = $args['url'];
        $this->info = $args['info'];

    }
    function renderURL()
    {
        $url = $this->url;
        $url = str_replace('${HTTP_HOSTNAME}', get_http_hostname(), $url);
        $url = str_replace('${HTTP_PORT}', get_http_port(), $url);
        return $url;
    }

    function renderLink()
    {
        return "<a href='".$this->renderURL()."'>$this->label</a>";
    }

    function renderItem()
    {
        $str = '';
        $str .= "<dt>".$this->renderLink()."</dt>";
        $str .= "<dd>$this->info</dd>";
        return $str;
    }

    /**
     * @param $bookmarks Bookmark[]
     * @return string rendered output
     */
    static function renderBookmarks($bookmarks)
    {
        $str = '<dl>';
        foreach($bookmarks as $bm)
        {
            $str .= $bm->renderItem();
        }
        $str .= '</dl>';
        return $str;
    }
}


?>
<?php
$data = load_data_from_json('./sidebar.json');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
  <head>
      <title><?=$data['title']?></title>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <link href='sidebar.css' rel='stylesheet' type='text/css' />
  </head>
  <body>
      <?=Bookmark::renderBookmarks($data['bookmarks'])?>
  </body>
</html>
