<?php
namespace SwitchMedia\Test;


use PHPUnit\Framework\TestCase;
use SwitchMedia\Exception\ValidationException;
use SwitchMedia\RecommendMovies;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class IntegrationTest extends TestCase
{
	public function dataProvider(): array
	{
		return [
			['123', '456', 'xvxvxc', true, null],
			[
				'animation',
				'12:00',
				dirname(__FILE__) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'data.json',
				false,
				"Zootopia, showing at 7pm\nShaun The Sheep, showing at 7pm\n"
			],
			[
				'fantasy',
				'12pm',
				dirname(__FILE__) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'data.json',
				false,
				"no movie recommendations\n"
			],
			[
				'animation',
				'12pm',
				dirname(__FILE__) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'data.json',
				false,
				"Zootopia, showing at 7pm\nShaun The Sheep, showing at 7pm\n"
			],
			[
				'animation',
				'11pm',
				dirname(__FILE__) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'data.json',
				false,
				"no movie recommendations\n"
			],
		];
	}

	/**
	 * @param array $data
	 *
	 * @dataProvider dataProvider
	 */
	public function testAll(
		string $genre,
		string $time,
		string $feedFileName,
		bool $validationExceptionExpected,
		string $output = null
	)
	{
		if ($validationExceptionExpected) {
			$this->expectException(ValidationException::class);
		}

		$application = new Application();
		$application->add(new RecommendMovies());
		$command = $application->find('movie:recommend');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'genre' => $genre,
			'time' => $time,
			'feed'=> $feedFileName
		]);
		if ($output) {
			$this->assertEquals($output, $commandTester->getDisplay());
		}
		$this->assertTrue(true);
	}
}