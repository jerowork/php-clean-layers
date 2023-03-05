<?php declare(strict_types = 1);

$ignoreErrors = [];
   $ignoreErrors[] = [
	'message' => '#^Call to an undefined method Jerowork\\\\PHPCleanLayers\\\\Guard\\\\Rule\\\\Rule\\:\\:getLayers\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Guard/Rule/AbstractLayeredRule.php',
];
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
   $ignoreErrors[] = [
	'message' => '#^Call to an undefined method Jerowork\\\\PHPCleanLayers\\\\Guard\\\\Rule\\\\Rule\\:\\:getLayers\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/tests/Guard/Rule/AbstractLayeredRuleTest.php',
];

return ['parameters' => ['ignoreErrors' => $ignoreErrors]];
