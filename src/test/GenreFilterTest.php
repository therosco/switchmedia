<?php
namespace SwitchMedia\test;


use SwitchMedia\Entry;
use SwitchMedia\Filter\Genre;

class GenreFilterTest extends FilterTest
{
	public function getCases(): array
	{
		return [
			[$this->getEntry(0), 'drama', true],
			[$this->getEntry(1), 'fantasy', false],
			[$this->getEntry(1), 'animation', true],
		];
	}

	/**
	 * @param Entry $entry
	 * @param string $genre
	 * @param bool $accepted
	 *
	 * @dataProvider getCases
	 */
	public function testFilter(Entry $entry, string $genre, bool $accepted)
	{
		$filter = new Genre($genre);

		if ($accepted) {
			$this->assertTrue($filter->isAcceptable($entry));
		}
		else {
			$this->assertFalse($filter->isAcceptable($entry));
		}

		$this->assertNull($filter->getHighlight());
	}
}