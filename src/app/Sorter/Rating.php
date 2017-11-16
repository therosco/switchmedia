<?php
namespace SwitchMedia\Sorter;


use SwitchMedia\Entry;

class Rating implements Sorter
{

	/**
	 * @param Entry $entry
	 * @return string
	 */
	public function getSortingKey(Entry $entry): string
	{
		return str_pad($entry->getRating(), 10, '0', STR_PAD_LEFT);
	}
}