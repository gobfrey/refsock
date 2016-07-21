<?php
$issn = $_GET['issn'];
$start_time = $_GET['date'];
if (!$start_time) { $start_time = 'Publication'; }

$json = file_get_contents("https://ref.sherpa.ac.uk/id/journal/$issn");
$journal = json_decode($json);

if (!$journal)
{
	echo('<h1>OH NO, No Journal!!!</h1>');
	exit;
}
?>


<html>
<head>
<title>Ref-o-Matic</title>
</head>
<body>

<h1>Results for <?php echo($journal->title)?></h1>

<?php

echo("<p>This journal is published by <strong>" . $journal->publisher->name . "</strong></p>");

echo("<p>Deposit within 3 months of <span style='font-size: bigger'><strong>Date of " . $start_time . "</strong></span></p>");

#ref complaint routes?
echo("<h2>Allowed Routes to Compliance:</h2>");
echo('<table><tr>');
foreach (array('AB', 'CD') as $panel)
{
	echo ('<td style="background-color: gray">');
	echo ("<h3 style='text-align: center; text-size: bigger;'>REF Panels $panel</h3>");
	echo("<ul>\n");
	$count = 0;
	foreach ($journal->advised_actions->$panel as $action)
	{
		$count++;
		$counts[$action->open_access_route]++;

		echo("<li>$count:<ul>");
		echo('<li>' . 'Route: ' . $action->open_access_route . '</li>');
		echo('<li>' . 'Required Embargo: ' . $action->embargo_period . '</li>');
		echo('<li>' . 'Allowed Version(s): ' . implode(' OR ',$action->article_versions) . '</li>');
		echo('<li>' . 'Place you can put it: ' . implode(' OR ',$action->repository_types) . '</li>');
		echo ('</ul></li>');
	}
	echo("</ul>\n");
	if ($counts['Archive'] > 0 || $counts['Publish'] > 0 || $counts['Hybrid'] > 0)
	{
		echo('<img src="450px-Checkmark.svg.png"/>');
	}
	else
	{
		echo("<p>It doesn't look like this is eligable</p>");
		echo('<img src="525px-X_mark.svg.png"/>');
	}
	echo('</td>');
}
echo('<table><tr>');


?>


</body>
</html>



