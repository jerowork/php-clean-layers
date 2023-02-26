<?php declare(strict_types = 1);

$ignoreErrors = [];
   $ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$class of method Jerowork\\\\PHPCleanLayers\\\\Guard\\\\Layer\\\\RegexLayer\\:\\:isPartOf\\(\\) expects class\\-string, string given\\.$#',
	'count' => 4,
	'path' => __DIR__ . '/tests/Guard/Layer/RegexLayerTest.php',
];
   $ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$class of method Jerowork\\\\PHPCleanLayers\\\\Guard\\\\Layer\\\\RootLevelClasses\\:\\:isPartOf\\(\\) expects class\\-string, string given\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/tests/Guard/Layer/RootLevelClassesTest.php',
];

return ['parameters' => ['ignoreErrors' => $ignoreErrors]];