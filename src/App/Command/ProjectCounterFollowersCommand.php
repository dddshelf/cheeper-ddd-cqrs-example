<?php

namespace App\Command;

use Cheeper\Application\Command\Author\SignUp;
use Cheeper\Application\Command\Cheep\PostCheep;
use Cheeper\Chapter6\Application\Projector\Author\CountFollowerProjector;
use Cheeper\Chapter6\Application\Projector\Author\CountFollowers;
use Doctrine\DBAL\Driver\Connection;
use Predis\ClientInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

//snippet project-counter-followers-command
final class ProjectCounterFollowersCommand extends Command implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    protected static $defaultName = 'app:projector:counter-followers';

    public function __construct(
        string $name = null,
        public ClientInterface $redis,
        public Connection $database
    )
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Project counter followers')
            ->addArgument('authorId', InputArgument::REQUIRED, 'Author Id')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $authorId = $input->getArgument("authorId");

        (new CountFollowerProjector(
            $this->redis,
            $this->database,
        ))(
            CountFollowers::ofAuthor($authorId)
        );

        return 0;
    }
}
//end-snippet
