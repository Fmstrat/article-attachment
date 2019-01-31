<?php
class Article_Attachment extends Plugin {

	private $host;

	function about() {
		return array(1.0,
			"If exists, put the largest media attachment at the top of feed content.",
			"fmstrat");
	}

	function init($host) {
		$this->host = $host;
		$host->add_hook($host::HOOK_ARTICLE_FILTER, $this);
	}

	function hook_article_filter($article) {
		$res = $this->pdo->query("select content_url
						from ttrss_enclosures
						where post_id=(select id
								from ttrss_entries
								where guid='".$article['guid_hashed']."')
						order by width desc
						limit 1;");
		while ($line = $res->fetch()) {
			$article['content'] = "<img src='".$line["content_url"]."'><br><br>".$article["content"];
		}
		return $article;
	}

	function api_version() {
		return 2;
	}

}
?>
