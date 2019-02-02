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
		$host->add_hook($host::HOOK_RENDER_ARTICLE_CDM, $this);
	}

	function hook_render_article_cdm($article) {
		$guid = $article['guid'];
		$res = $this->pdo->query("select content_url
						from ttrss_enclosures
						where post_id=(select id
								from ttrss_entries
								where guid='".$guid."')
						order by width desc
						limit 1;");
		while ($line = $res->fetch())
			if (strpos($article["content"], $line["content_url"]) === false)
				$article['content'] = "<img src='".$line["content_url"]."'><br><hr><br>".$article["content"];
		return $article;
	}

	function api_version() {
		return 2;
	}

}
?>
