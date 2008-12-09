<?php
/**
 * validate and parse valid RDF/RSS newsfeeds
 *
 * @author Andreas Demmer <mail@andreas-demmer.de>
 * @version 1.1.0
 */

class rdfParser {
    /**
     * header information of the newsfeed
     *
     * @var array
     */
    protected $infos;

    /**
     * items of the newsfeed
     *
     * @var array
     */
    protected $items;

    /**
     * newsfeed unparsed
     +
     * @var string
     */
    protected $rdf;

    /**
     * constructur, reads and parses the newsfeed
     *
     * @return bool
     * @param string $url
     */
    public function rdfParser($url) {
        $validS = TRUE;
        $this->infos = array();
        $this->items = array();

        $this->rdf = implode('', file($url));

        $this->rdf = preg_match('|[?]|i', $this->rdf) ? $this->rdf : utf8_decode($this->rdf);

        $this->rdf = str_replace("\n", NULL, $this->rdf);
        $this->rdf = str_replace("\t", ' ', $this->rdf);
        $this->rdf = preg_replace('| +|', ' ', $this->rdf);

        if((bool)$this->rdf) {
            $this->parseInfos();
            $this->parseItems();
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * extracts the header informations of the newsfeed
     */
    private function parseInfos() {
        preg_match_all('|<channel[^>]*>.*</channel>|i', $this->rdf, $matches);
        @$infoString = $matches[0][0];
        $matches = array();

        preg_match_all('|<title>([^<]*)</title>|i', $infoString, $matches);
        @$title = $matches[1][0];
        $matches = array();

        preg_match_all('|<description>([^<]*)</description>|i', $infoString, $matches);
        @$description = $matches[1][0];
        $matches = array();

        preg_match_all('|<link>([^<]*)</link>|i', $infoString, $matches);
        @$link = $matches[1][0];

        preg_match_all('|<image[^<]*>.*</image>|i', $infoString, $matches);
        @$imageString = $matches[0][0];

        preg_match_all('|resource *= *"?([^" >]*)[" >]+|i', $imageString, $matches);
        @$image = array('resource' => $matches[1][0]);

        preg_match_all('|<width>([^<]*)</width>|i', $imageString, $matches);
        @$image['width'] = $matches[1][0];

        preg_match_all('|<height>([^<]*)</height>|i', $imageString, $matches);
        @$image['height'] = $matches[1][0];

        $this->infos = array(
            'title' => $title,
            'description' => $description,
            'link' => $link,
            'image' => $image,
            'timestamp' => time()
        );
    }

    /**
     * extracts the items of the newsfeed
     */
    private function parseItems() {
    	/* find items */
        $pattern = '$<item>(.*?)</item>$i';
        preg_match_all($pattern, $this->rdf, $items);

        /* parse item details */
        $pattern = '$<([a-zA-Z]+)>(.*?)</[a-zA-Z]+>$';
		$parsedItem = array();

        foreach ($items[0] as $item) {
        	preg_match_all($pattern, $item, $matches);

        	foreach ($matches[1] as $key => $detail) {
        		if ($detail === 'item') {
        			$parsedItem['title'] = strip_tags($matches[2][$key]);
        		} else {
        			$parsedItem[$detail] = $matches[2][$key];
        		}
        	}

        	$this->items[] = $parsedItem;
        }
    }

    /**
     * returns the header informations of the newsfeed
     *
     * @return array
     * @desc liefert die Header-Informationen des Newsfeeds
     */
    public function fetchInfos() {
        return $this->infos;
    }

    /**
     * returns one item of the newsfeed
     *
     * @return array
     */
    public function fetchItem() {
        return array_shift($this->items);
    }
    /**
     * returns all items of the newsfeed
     *
     * @return array
     */
    public function fetchItems() {
        return $this->items;
    }
}
?>