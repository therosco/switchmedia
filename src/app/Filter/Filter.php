<?php
namespace SwitchMedia\Filter;


use SwitchMedia\Entry;

interface Filter
{
	public function isAcceptable(Entry $entry): bool;

	public function getHighlight(): ?string;
}