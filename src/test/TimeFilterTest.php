<?php
namespace SwitchMedia\test;


use SwitchMedia\Entry;
use SwitchMedia\Filter\Time;

class TimeFilterTest extends FilterTest
{
	public function getCases()
	{
		return [
			[$this->getEntry(0), '12:00', true, '6:30pm'],
			[$this->getEntry(1), '12:00', true, '7pm'],
		];
	}

	/**
	 * @param Entry $entry
	 * @param string $time
	 * @param bool $accepted
	 * @param string $highlight
	 *
	 * @dataProvider getCases
	 */
	public function testFilter(Entry $entry, string $time, bool $accepted, string $highlight)
	{
		$filter = new Time($time, new \DateInterval('PT30M'), new \DateTimeZone('+1100'));

		if ($accepted) {
			$this->assertTrue($filter->isAcceptable($entry));
		}
		else {
			$this->assertFalse($filter->isAcceptable($entry));
		}

		$this->assertEquals($highlight, $filter->getHighlight());
	}
}