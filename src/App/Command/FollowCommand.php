<?php

namespace App\Command;

use Cheeper\Chapter6\Application\Command\Author\Follow;
use Cheeper\Chapter6\Application\Command\Author\WithDomainEvents\FollowHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class FollowCommand extends Command
{
    protected static $defaultName = 'app:follow';

    public function __construct(
        private FollowHandler $followHandler,
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setDescription('Make an author follow another author')
            ->addArgument('from', InputArgument::REQUIRED, 'From Author Id')
            ->addArgument('to', InputArgument::REQUIRED, 'To Author Id')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $from = $input->getArgument('from');
        $to = $input->getArgument('to');

        $io->info(\Safe\sprintf('Making %s follow %s', $from, $to));

        $this->followHandler->__invoke(
            Follow::fromAuthorIdToAuthorId(
                from: $from,
                to: $to
            )
        );

        $io->success('Done!');

        return Command::SUCCESS;
    }
}
