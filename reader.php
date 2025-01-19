<?php 

$lang = '';

if ( !empty($_GET['lang']) ) {
	displayTranslation($_GET['lang']);
}

function displayTranslation(string $lang, int $chapter = 1) {
	if ($chapter < 10) {
		$chapter = '0' . $chapter;
	}

	$url = __DIR__.'/translations/' . $lang . "/sj_" . $lang . "_" . $chapter. ".txt";
	$fileContents = file_get_contents($url);
	$result = '';
	$lines = array_values(array_filter(array_map('trim', explode("\n", $fileContents)), 'strlen'));
	
	// Process lines in pairs
	
	for ($i = 0; $i < count($lines); $i += 2) {
		if (isset($lines[$i]) && isset($lines[$i + 1])) {
			if (strpos($lines[$i], '##') === 0) {
				$lines[$i] = '<h3 class="mt-3">' . substr($lines[$i], 2) . '</h3>';
				$lines[$i + 1] = '<h3 class="mt-3">' . substr($lines[$i + 1], 2) . '</h3>';
			}
			$lines[$i] = str_replace('##', '', $lines[$i]);
			$lines[$i + 1] = str_replace('##', '', $lines[$i + 1]);

			// Generate the HTML
			$result .= '<tr><td>'.$lines[$i + 1].'</td><td>'. $lines[$i].'</td></tr>';
		}
	}

	echo $result;
}

?>