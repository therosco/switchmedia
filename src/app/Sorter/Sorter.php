<?php
namespace SwitchMedia\Sorter;


use SwitchMedia\Entry;

interface Sorter
{
	/**
	 * @param Entry $entry
	 * @return string
	 */
	public function getSortingKey(Entry $entry): string;
}