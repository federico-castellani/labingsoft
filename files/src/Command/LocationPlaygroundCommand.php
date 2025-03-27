<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Location;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:location:playground', description: 'location playground')]
class LocationPlaygroundCommand extends Command
{
    private EntityManagerInterface $entityManager;

    private LocationRepository $locationRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        LocationRepository $locationRepository,
    ) {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->locationRepository = $locationRepository;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $location = $this->locationRepository->findOneByName('perugia');
        $output->writeln($location->getName());
        return Command::SUCCESS;
    }
}
