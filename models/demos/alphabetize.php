<?php
	class demos_alphabetize_model extends model {
		private $words = array("1234", "5678", "9000", "!?#&", "abalienate", "acervate", "acervation", "achar", "aculeated", "allignment", "anthropophagus", "apiarian", "argent", "artics", "auspice", "avidity", "balneal", "banker", "beaked", "beatitide", "behemoth", "beldam", "bisquit", "bitterly", "blague", "bouilli", "brainless", "bullyrag", "bumbailiff", "caique", "caporal", "catafalque", "ceramics", "cereals", "checquers", "chink", "circling", "circumambient", "cloudtopt", "coiner", "commutual", "conditionally", "congenite", "contraindication", "copetitive", "dak", "deliberative", "demisemiquaver", "departed", "dexterousness", "dilettanti", "disavow", "dispensatory", "disproportionated", "dreaming", "dues", "duo", "earthlyminded", "edification", "educt", "episcopalianism", "epistyle", "eschar", "eutrophy", "evanescence", "excellency", "exculpation", "falsity", "feodal", "finality", "flagship", "flaunting", "fourwheeler", "frankhearted", "freethinker", "gite", "groceries", "gubernation", "gynephobia", "hellcat", "hyppish", "ideawake", "illassorted", "immolate", "imperfectly", "incoherence", "indifference", "inducement", "infraction", "ingle", "insignificance", "interline", "intruder", "invader", "involvement", "italics", "items", "jesuitism", "justification", "kite", "kos", "lentiform", "limpidity", "locular", "lucubration", "lupanar", "machiavel", "majordomo", "mauder", "mecaenas", "mesalliance", "middleclass", "mucronate", "nameless", "naphtha", "nautchgirl", "neatherd", "newsmonger", "nolleity", "norther", "novelist", "occiput", "opiniatry", "ovoid", "oxidation", "pacatolus", "palaver", "paronomasio", "penetralia", "pimento", "pinchbeck", "placit", "plaintful", "plowboy", "pornographic", "portreeve", "posthaste", "powerfor", "precipitant", "prevenient", "princely", "probable", "prognostication", "prolocutor", "puffiness", "quadrable", "radoteur", "rationality", "raucity", "reassemble", "reconstruct", "reduced", "reduplication", "regentship", "reticulation", "rhymer", "sacculated", "sacrarium", "sarculation", "seigniority", "selfsacrifice", "seraphic", "sestiad", "shive", "shorts", "simulating", "spanker", "straggling", "stylite", "suasion", "suffragan", "supernumernry", "suppletory", "suscitate", "tailpiece", "talionic", "tenancity", "tenebrious", "theopneustic", "torpedodestroyer", "troops", "turquois", "unbind", "uncertain", "undetermined", "uninvented", "unlabored", "unmarked", "unsurpassed", "unwarned", "vacuolar", "variation", "verruca", "vitrification", "vlei", "wanderer", "wellgrounded", "withinside", "words", "yaffle", "yieldance", "zemindar");

		public function get_words($char) {
			$result = array();

			foreach ($this->words as $word) {
				$first = strtolower(substr($word, 0, 1));
				if ($char == "0") {
					if ((ord($first) < ord("a")) || (ord($first) > ord("z"))) {
						array_push($result, $word);
					}
				} else {
					if ($first == $char) {
						array_push($result, $word);
					}
				}
			}

			return $result;
		}
	}
?>
