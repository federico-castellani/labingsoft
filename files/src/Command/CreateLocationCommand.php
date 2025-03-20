<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:location:create', description: 'Adds a new location')]
class CreateLocationCommand extends Command
{
    public const ARG_LOCATION_NAME = 'locationName';
    public const ARG_COUNTRY_CODE = 'countryCode';

    public function configure(): void
    {
        $this->addArgument(
            self::ARG_LOCATION_NAME,
            InputArgument::REQUIRED,
            'The location\'s name'
        )->addArgument(
            self::ARG_COUNTRY_CODE,
            InputArgument::REQUIRED,
            'The location\'s country code'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return Command::SUCCESS;
    }
}
