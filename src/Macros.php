<?php

/**
 * Copyright (c) dotBlue (http://dotblue.net)
 */

namespace DotBlue\WebImages;

use Nette\Latte;
use Nette\Latte\MacroNode;
use Nette\Latte\PhpWriter;


class Macros extends Latte\Macros\MacroSet
{

	public static function install(Latte\Compiler $parser)
	{
		$me = new static($parser);
		$me->addMacro('src', NULL, NULL, function(MacroNode $node, PhpWriter $writer) use ($me) {
			return ' ?> src="<?php ' . $me->macroSrc($node, $writer) . ' ?>"<?php ';
		});
	}



	/********************* macros ****************v*d**/



	public function macroSrc(MacroNode $node, PhpWriter $writer)
	{
		return $writer->write('echo %escape(%modify($_presenter->link(":Nette:Micro:", DotBlue\WebImages\Macros::prepareArguments([%node.args]))))');
	}



	public static function prepareArguments(array $arguments)
	{
		foreach ($arguments as $key => $value) {
			if ($key === 0 && !isset($arguments['id'])) {
				$arguments['id'] = $value;
				unset($arguments[$key]);
			} elseif ($key === 1 && !isset($arguments['width'])) {
				$arguments['width'] = $value;
				unset($arguments[$key]);
			} elseif ($key === 2 && !isset($arguments['height'])) {
				$arguments['height'] = $value;
				unset($arguments[$key]);
			} elseif ($key === 3 && !isset($arguments['algorithm'])) {
				$arguments['algorithm'] = $value;
				unset($arguments[$key]);
			}
		}

		return $arguments;
	}

}
