<?php
	class search_model extends model {
		private $text = null;

		public function __call($name, $args) {
			return false;
		}

		/* Add selection to query
		 */
		private function add_selection($column, &$query, &$args) {
			$query .= "(".$column. " like %s)";
			array_push($args, "%".$this->text."%");
		}

		/* Search agenda
		 */
		private function search_agenda() {
			$query = "select concat(%s, id) as url, title as text, content from agenda where (";
			$args = array("/agenda/");
			$this->add_selection("title", $query, $args);
			$query .= " or ";
			$this->add_selection("content", $query, $args);
			$query .= ") order by begin desc, end";

			return $this->db->execute($query, $args);
		}

		/* Search dictionary
		 */
		private function search_dictionary() {
			$query = "select concat(%s, id) as url, word as text, short_description as content ".
			         "from dictionary where ";
			$args = array("/dictionary/");
			$this->add_selection("word", $query, $args);
			$query .= " or ";
			$this->add_selection("long_description", $query, $args);

			return $this->db->execute($query, $args);
		}

		/* Search forum
		 */
		private function search_forum() {
			$query = "select distinct concat(%s, t.id, %s, m.id) as url, concat(f.title, %s, t.subject) as text, m.content ".
			         "from forums f, forum_topics t, forum_messages m ".
			         "where f.id=t.forum_id and t.id=m.topic_id and (";
			$args = array("/forum/topic/", "/#", " :: ");
			$this->add_selection("t.subject", $query, $args);
			$query .= " or ";
			$this->add_selection("m.content", $query, $args);
			$query .= " or ";
			$this->add_selection("m.username", $query, $args);
			$query .= ") order by m.timestamp desc";

			return $this->db->execute($query, $args);
		}

		/* Search mailbox
		 */
		private function search_mailbox() {
			if ($this->user->logged_in == false) {
				return false;
			}

			$query = "select concat(%s, id) as url, subject as text, message as content from mailbox ".
			         "where to_user_id=%d and (deleted_by is null or deleted_by!=to_user_id) and (";
			$args = array("/mailbox/", $this->user->id);
			$this->add_selection("subject", $query, $args);
			$query .= " or ";
			$this->add_selection("message", $query, $args);
			$query .= ")";

			return $this->db->execute($query, $args);
		}

		/* Search news
		 */
		private function search_news() {
			$query = "select concat(%s, id) as url, title as text, content from news where (";
			$args = array("/news/");
			$this->add_selection("title", $query, $args);
			$query .= " or ";
			$this->add_selection("content", $query, $args);
			$query .= ") order by timestamp desc";

			return $this->db->execute($query, $args);
		}

		/* Search pages
		 */
		private function search_pages() {
			/* Public pages
			 */
			$args = array();
			$query = "select url, title as text, content from pages where ";
			if ($this->user->is_admin == false) {
				$query .= "private=%d and ";
				array_push($args, NO);
			}
			$query .= "(";
			$this->add_selection("title", $query, $args);
			$query .= " or ";
			$this->add_selection("content", $query, $args);
			$query .= ") and visible=%d";

			if (($public = $this->db->execute($query, $args, YES)) === false) {
				return false;
			}

			/* Search in private pages?
			 */
			if (($this->user->logged_in == false) || $this->user->is_admin) {
				return $public;
			}
			if (is_array($this->user->role_ids) == false) {
				return $public;
			}
			if (count($this->user->role_ids) == 0) {
				return $public;
			}

			$pages = implode(",", array_fill(0, count($this->user->role_ids), "%d"));

			/* Private pages
			 */
			$args = array();
			$query = "select url, title as text from pages p, page_access a ".
			         "where p.id=a.page_id and p.private=%d and a.role_id in (".$pages.") and (";
			$this->add_selection("p.title", $query, $args);
			$query .= " or ";
			$this->add_selection("p.content", $query, $args);
			$query .= ") and p.visible=%d";

			if (($private = $this->db->execute($query, YES, $this->user->role_ids, $args, YES)) === false) {
				return false;
			}

			return array_merge($public, $private);
		}

		/* Search weblog
		 */
		private function search_weblog() {
			$query = "select distinct concat(%s, w.id) as url, w.title as text, w.content ".
			         "from weblogs w, weblog_comments c where w.id=c.weblog_id and (";
			$args = array("/weblog/");
			$this->add_selection("w.title", $query, $args);
			$query .= " or ";
			$this->add_selection("w.content", $query, $args);
			$query .= " or ";
			$this->add_selection("c.content", $query, $args);
			$query .= " or ";
			$this->add_selection("c.author", $query, $args);
			$query .= ") order by w.timestamp desc";

			return $this->db->execute($query, $args);
		}

		/* Search the database
		 */
		public function search($post, $sections) {
			$this->text = $post["query"];
			$result = array();

			foreach ($sections as $section => $label) {
				if (is_true($post[$section])) {
					$hits = call_user_func(array($this, "search_".$section));
					if ($hits != false) {
						$result[$section] = $hits;
					}
				}
			}

			return $result;
		}
	}
?>
