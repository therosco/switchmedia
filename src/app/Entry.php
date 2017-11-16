<?php
namespace SwitchMedia;


use SwitchMedia\Exception\ValidationException;

class Entry
{
	private $name;
	private $rating;
	private $genres = [];
	private $showings = [];

	/**
	 * Entry constructor.
	 * @param array $entryData an assoc array expected, the result of json_decode(true):
	 * name: String
	 * rating: Integer
	 * genres[]:
	 * 	String
	 * showings[]:
	 * 	String
	 *
	 */
	public function __construct(array $entryData)
	{
		$entryData = $this->validate($entryData);

		$this->name     = $entryData['name'];
		$this->rating   = $entryData['rating'];
		$this->genres   = $entryData['genres'];
		$this->showings = $entryData['showings'];
	}

	/**
	 * @param array $entryData
	 * @return array
	 * @throws \Exception
	 */
	protected function validate(array $entryData): array
	{
		$nonEmptyString = function($data)
		{
			return is_string($data) && $data;
		};

		$isStringArray = function($data) use ($nonEmptyString)
		{
			return is_array($data) && array_filter($data, $nonEmptyString) === $data;
		};

		$predicates = [
			'name' => !isset($entryData['name']) || !$nonEmptyString($entryData['name']),
			'rating' => !isset($entryData['rating']) || !is_integer($entryData['rating']),
			'genres' => !isset($entryData['genres'])  || !$isStringArray($entryData['genres']),
			'showings' => !isset($entryData['showings'])  || !$isStringArray($entryData['showings']),
		];

		foreach ($predicates as $fieldName => $predicate)
		{
			if ($predicate) {
				throw new ValidationException("Invalid $fieldName data: " . serialize($entryData));
			}
		}

		return $entryData;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @return int
	 */
	public function getRating(): int
	{
		return $this->rating;
	}

	/**
	 * @return string[]
	 */
	public function getGenres(): array
	{
		return $this->genres;
	}

	/**
	 * @return string[]
	 */
	public function getShowings(): array
	{
		return $this->showings;
	}


}