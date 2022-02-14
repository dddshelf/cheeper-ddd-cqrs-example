<?php
declare(strict_types=1);

namespace App\Command;

use Cheeper\Chapter6\Application\Author\Command\FollowCommand;
use Cheeper\Chapter6\Application\Author\Command\WithDomainEvents\FollowCommandHandler;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: "app:follow", description: "Make an author follow another author")]
final class FollowCliCommand extends Command
{
    public function __construct(
        private FollowCommandHandler $followHandler
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('from', InputArgument::REQUIRED, 'From Author Id')
            ->addArgument('to', InputArgument::REQUIRED, 'To Author Id')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $from = $input->getArgument('from');
        $to = $input->getArgument('to');

        $io->info(sprintf('Making %s follow %s', $from, $to));

        $this->followHandler->__invoke(
            FollowCommand::fromAuthorIdToAuthorId(
                from: $from,
                to: $to
            )
        );

        $io->success('Done!');

        return Command::SUCCESS;
    }
}
