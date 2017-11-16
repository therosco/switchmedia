<?php
namespace SwitchMedia\Filter;


use SwitchMedia\Entry;

class Genre implements Filter
{
	private $genre;

	public function __construct(string $genre)
	{
		$this->genre = strtolower($genre);
	}

	public function isAcceptable(Entry $entry): bool
	{
		foreach ($entry->getGenres() as $genre)
		{
			if (strtolower($genre) === $this->genre) {
				return true;
			}
		}

		return false;
	}

	public function getHighlight(): ?string
	{
		return null;
	}
}