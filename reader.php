<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// https://github.com/enfrte/salute-jonathan/blob/master/translations/finnish/sj_finnish_01.txt

if ( !empty($_GET['lang']) ) {
	$lang = $_GET['lang'];
} else {
	return 'Language not set';
}

$chapter = !empty($_GET['chapter']) ? $_GET['chapter'] : '01';

// if ($chapter < 10) {
// 	$chapter = '0' . $chapter;
// }

$inDevlopment = false;

if ($inDevlopment) {
	$baseUrl = __DIR__.'/translations/';
}
else {
	$baseUrl = 'https://raw.githubusercontent.com/enfrte/salute-jonathan/refs/heads/master/translations/';
}

$url = $baseUrl . $lang . "/sj_" . $lang . "_" . $chapter. ".txt";

$fileContents = file_get_contents($url);

if ( empty($fileContents) ) {
	echo "File not found: " . $url;
	return;
}

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

// Add a link to the next chapter

$chapter++;

if ($chapter < 10) {
	$chapter = '0' . $chapter;
}

$nextChapterUrl = $baseUrl . $lang . "/sj_" . $lang . "_" . $chapter. ".txt";
$headers = @get_headers($nextChapterUrl);

if ($headers && strpos($headers[0], '200') !== false) {
	$foo = '<tr><td colspan="2"><a href="#" hx-get="reader.php?lang='.$lang.'&chapter='.$chapter.'" hx-trigger="click" hx-target="#translation-table tbody" hx-swap-oob="true"><h3 class="mt-2">Next Chapter</h3></a></td></tr>';
	$result .= '<tr><td colspan="2"><a href="#" hx-get="reader.php?lang='.$lang.'&chapter='.$chapter.'" hx-trigger="click" hx-target="#translation-table tbody" hx-swap="show:top"><h3 class="mt-2">Next Chapter</h3></a></td></tr>';
}

echo $result; // Responce received by HTMX

?>
