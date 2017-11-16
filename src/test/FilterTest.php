<?php
namespace SwitchMedia\test;


use PHPUnit\Framework\TestCase;
use PHPUnit\Runner\Exception;
use SwitchMedia\Entry;

abstract class FilterTest extends TestCase
{
	protected function getEntry(int $indexNumber): Entry
	{
		$data = json_decode(
			file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'data.json'),
			true
		);

		if (!isset($data[$indexNumber])) {
			throw new \Exception('Index not found');
		}

		return new Entry($data[$indexNumber]);
	}
}