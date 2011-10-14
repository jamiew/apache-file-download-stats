<?php
ini_set("memory_limit","64M");
 
$log_file = '/users/home/jamiew/logs/access_log';
//$pattern = '/GET (\S*news\/images\/[0-9]{4}\S*.php)/';
$pattern = '/GET (.*) HTTP/';
$max = 100;

if (is_readable($log_file)) {
 
	$fh = fopen($log_file,'r') or die($php_errormsg);
	$requests = array();
 
	header('Content-Type: text/html');
	while (!feof($fh)) {
		if ($s = trim(fgets($fh,16384))) {
			if (preg_match($pattern, $s, $matches)) {
				list($whole_match, $request) = $matches;
				$requests[$request]++;
			} 
		}
	}
	fclose($fh) or die($php_errormsg);
 
	// sort the array (in reverse) by number of requests
	arsort($requests);
 
	// print formatted results
	/*
	print "<?xml version='1.0' encoding='UTF-8'?>\n";
	print "<stats>\n";
	foreach ($requests as $request => $accesses) {
		printf("\t<url path='%s' count='%s' />\n", $request, $accesses); 
	}
	print "</stats>\n";
	*/

	print "<html><body>\n";
	print "<h1>jamiedubs.com top $max files today</h1>\n";
	print "<table>\n";
	print "<thead><th class='rank'></th><th class='count'>Hits</th><th class='url'>URL</th></thead>\n";
	print "<tbody>\n";
	$count = 0;
	foreach ($requests as $request => $accesses) {
		if($count >= $max){ break; };
		print("<tr>");
		printf("<td class='rank'>%s</td><td class='count'>%s</td><td class='url'><a target='_blank' rel='nofollow' href='%s'>%s</a></td>\n", $count+1, number_format($accesses), $request, $request);
		print("</tr>");
		$count++;
	}
	print "</tbody>\n";
	print "</table>\n";

} else { 
	echo "cannot access logfile!";
}
?>
<style type="text/css">
body { font-family: sans-serif; color: #555; font-size: 15pt; }
a, a:link { color: #777; text-decoration: underline; }
a:hover { color: #999; }
/* left offset is to accomodate the rank/position column */
h1 { text-align: center; text-transform: uppercase; font-size: 30pt; margin-top: 1em; margin-left: 40px; }
table { margin: 0 auto; } 
table td { border: 1px solid #eee; padding: 10px 6px 8px; }
table th { background: #555; color: #fff; }
table th.rank { background: white; }
table td.rank { color: #ccc; border: 0; text-align: right; }
table th.url { }
table td.url { }
table th.count { }
table td.count { font-weight: bold; padding-left: 10px; padding-right: 10px; text-align: right; }
</style>

</body>
</html>
