<?php

/**
 * Copyright (c) dotBlue (http://dotblue.net)
 */

namespace DotBlue\WebImages;

use Latte;
use Latte\MacroNode;
use Latte\PhpWriter;


class Macros extends Latte\Macros\MacroSet
{

	public static function install(Latte\Compiler $parser)
	{
		$me = new static($parser);
		$me->addMacro('src', function (MacroNode $node, PhpWriter $writer) use ($me) {
			return $me->macroSrc($node, $writer);
		}, NULL, function(MacroNode $node, PhpWriter $writer) use ($me) {
			return ' ?> src="<?php ' . $me->macroSrc($node, $writer) . ' ?>"<?php ';
		});
	}



	/********************* macros ****************v*d**/



	public function macroSrc(MacroNode $node, PhpWriter $writer)
	{
		$absolute = substr($node->args, 0, 2) === '//' ? '//' : '';
		$args = $absolute ? substr($node->args, 2) : $node->args;
		return $writer->write('echo %modify($_presenter->link("' . $absolute . ':Nette:Micro:", DotBlue\WebImages\Macros::prepareArguments([' . $args . '])))');
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
			} elseif($key === 3 && !isset($arguments['folder'])) {
				$arguments['folder'] = $value;
				unset($arguments[$key]);
			}
		}

		return $arguments;
	}

}
