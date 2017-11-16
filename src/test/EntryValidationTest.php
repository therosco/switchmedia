<?php
namespace SwitchMedia\Test;


use PHPUnit\Framework\TestCase;
use SwitchMedia\Exception\ValidationException;
use SwitchMedia\Entry;

class EntryValidationTest extends TestCase
{
	public function getCases()
	{
		return [
			[
				[], false
			],
			[
				[
					'name' => '',
					'rating' => '',
					'genres' => '',
					'showings' => '',
				],
				false
			],
			[
				[
					'name' => 'The show',
					'rating' => 'good',
					'genres' => [],
					'showings' => [],
				],
				false
			],
			[
				[
					'name' => 'The show',
					'rating' => 0,
					'genres' => [''],
					'showings' => [''],
				],
				false
			],
			[
				[
					'name' => 'The show',
					'rating' => 11,
					'genres' => ['abc', 0],
					'showings' => ['def'],
				],
				false
			],
			[
				[
					'name' => 'The show',
					'rating' => 99,
					'genres' => ['abc', 'ghi'],
					'showings' => ['def'],
				],
				true
			],
		];
	}

	/**
	 * @param array $entryData
	 * @param bool $isValid
	 *
	 * @dataProvider getCases
	 */
	public function testValidation(array $entryData, bool $isValid)
	{
		if (! $isValid) {
			$this->expectException(ValidationException::class);
		}

		$this->assertInstanceOf(Entry::class, new Entry($entryData));
	}
}