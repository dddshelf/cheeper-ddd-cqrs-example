<?php
declare(strict_types=1);

namespace App\Command;

use Cheeper\Chapter6\Application\Projector\Author\CountFollowerProjectionHandler;
use Cheeper\Chapter6\Application\Projector\Author\CountFollowersProjection;
use Doctrine\ORM\EntityManagerInterface;
use Redis;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

//snippet project-counter-followers-command
#[AsCommand(name: "app:projector:counter-followers", description: "Project counter followers")]
final class ProjectCounterFollowersCommand extends Command
{
    public function __construct(
        public Redis $redis,
        public EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('authorId', InputArgument::REQUIRED, 'Author Id')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $authorId = $input->getArgument("authorId");

        (new CountFollowerProjectionHandler(
            $this->redis,
            $this->entityManager,
        ))(
            CountFollowersProjection::ofAuthor($authorId)
        );

        return Command::SUCCESS;
    }
}
//end-snippet
