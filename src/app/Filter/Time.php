<?php
namespace SwitchMedia\Filter;

use function Sodium\add;
use SwitchMedia\Entry;
use SwitchMedia\Exception\ValidationException;

class Time implements Filter
{
	private $dateTime;
	/** @var null|\DateTimeImmutable */
	private $result = null;
	private $currentTimezone;

	public function __construct(string $time, \DateInterval $minDiff, \DateTimeZone $currentTimezone)
	{
		try {
			$this->dateTime = (new \DateTimeImmutable($time, $currentTimezone))->add($minDiff);
		}
		catch(\Exception $ex)
		{
			if (false !== strpos($ex->getMessage(), 'Unknown or bad format')) {
				throw new ValidationException('Invalid time', 0, $ex);
			}
			else {
				throw $ex;
			}
		}

		$this->currentTimezone = $currentTimezone;
	}

	public function isAcceptable(Entry $entry): bool
	{
		$oldZone = date_default_timezone_get();
		foreach ($entry->getShowings() as $showing)
		{
			$showingTime = new \DateTimeImmutable($showing, $this->currentTimezone);
			if($showingTime->getTimestamp() >= $this->dateTime->getTimestamp())
			{
				$this->result = $showingTime;
				break;
			}
		}

		date_default_timezone_set($oldZone);

		return (bool)$this->result;
	}

	public function getHighlight(): ?string
	{
		if (!$this->result) {
			return null;
		}

		return (int)$this->result->format("i") ? $this->result->format("g:ia") : $this->result->format("ga");
	}
}