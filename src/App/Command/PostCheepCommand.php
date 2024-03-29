<?php
declare(strict_types=1);

namespace App\Command;

use Cheeper\Application\Command\Cheep\PostCheep;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

//snippet post-cheep-command
#[AsCommand(name: "app:post-chepp", description: "Post Cheep from command line")]
final class PostCheepCommand extends Command
{
    protected static $defaultName = 'app:post-cheep';

    protected function configure(): void
    {
        $this
            ->addArgument('authorId', InputArgument::REQUIRED, 'Author ID')
            ->addArgument('message', InputArgument::REQUIRED, 'Cheep message')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $authorId = $input->getArgument("authorId");
        $message = $input->getArgument("message");
        $cheepId = Uuid::uuid4()->toString();

        $command = PostCheep::fromArray([
            'author_id' => $authorId,
            'cheep_id' => $cheepId,
            'message' => $message,
        ]);

        //ignore
        dump($command);
        //end-ignore

        return Command::SUCCESS;
    }
}
//end-snippet
