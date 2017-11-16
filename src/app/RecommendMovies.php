<?php
namespace SwitchMedia;


use SwitchMedia\Sorter\Rating;
use SwitchMedia\Sorter\Sorter;
use SwitchMedia\Exception\ValidationException;
use SwitchMedia\Filter\Filter;
use SwitchMedia\Filter\Genre;
use SwitchMedia\Filter\Time;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RecommendMovies extends Command
{
	protected function configure()
	{
		$this->setName('movie:recommend')
			->setDescription('Recommends a movie')
			->setHelp('Help')
			->addArgument('genre', InputArgument::REQUIRED, 'A preffered genre')
			->addArgument('time', InputArgument::REQUIRED, 'A 24h formated time, HH:mm')
			->addArgument('feed', InputArgument::REQUIRED, 'A readable file|stream to reed a feed from');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$entries = $this->getEntryList($input);

		$filters = $this->getFilters($input);
		$sorters = $this->getSorter();

		/** @var Entry[] $results */
		$results = [];
		foreach ($entries as $entry)
		{
			$item = $entry;
			$highlights = [];

			foreach ($filters as $format => $filter)
			{
				if (!$filter->isAcceptable($entry))
				{
					$item = null;
					break;
				}
				elseif (null !== ($highlight = $filter->getHighlight())) {
					$highlights[] = sprintf($format, $highlight);
				}
			}

			if ($item)
			{
				$key = '';
				/** @var Sorter $sorter */
				foreach ($sorters as $sorter) {
					$key .= $sorter->getSortingKey($item) . '|';
				}
				$key .= spl_object_hash($item);
				$results[$key] = sprintf(
					'%s%s',
					$item->getName(),
					implode(' ', $highlights)
				);
			}
		}

		krsort($results);

		if (! $results) {
			$output->writeln("no movie recommendations");
		}
		else {
			foreach ($results as $line)
			{
				$output->writeln($line);
			}
		}
	}

	/**
	 * @param InputInterface $input
	 * @return Entry[]|mixed
	 * @throws ValidationException
	 */
	private function getEntryList(InputInterface $input)
	{
		$filename = $input->getArgument('feed');
		$fh = @fopen($filename, 'r');
		if (!$fh) {
			throw new ValidationException("File $filename is not exists");
		}
		$meta = stream_get_meta_data($fh);
		if ('r' !== $meta['mode']) {
			throw new ValidationException("File $filename is not readable");
		}
		$entries = json_decode(file_get_contents($filename), true);
		if (false === $entries) {
			throw new ValidationException(json_last_error_msg());
		}
		/** @var Entry[] $results */
		$entries = array_map(
			function (array $entry) {
				return new Entry($entry);
			},
			$entries
		);
		return $entries;
	}

	/**
	 * @param InputInterface $input
	 * @return Filter[]
	 */
	private function getFilters(InputInterface $input): array
	{
		return [
			0 => new Genre($input->getArgument('genre')),
			', showing at %s' => new Time($input->getArgument('time'), new \DateInterval('PT30M'), new \DateTimeZone('+1100')),
		];
	}

	/**
	 * @return Sorter[]
	 */
	private function getSorter(): array
	{
		return [
			new Rating(),
		];
	}
}