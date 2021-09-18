<?php declare(strict_types=1);

namespace App\Command;

use Cheeper\Application\Command\Author\SignUp;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

//snippet signup-command
#[AsCommand(name: "app:sign-up", description: "Signs up an author")]
final class SignUpCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addArgument('username', InputArgument::REQUIRED, 'Author\'s username')
            ->addArgument('email', InputArgument::REQUIRED, 'Author\'s email')
            ->addArgument('name', InputArgument::REQUIRED, 'Author\'s name')
            ->addArgument('biography', InputArgument::REQUIRED, 'Author\'s biography')
            ->addArgument('location', InputArgument::REQUIRED, 'Author\'s location')
            ->addArgument('website', InputArgument::REQUIRED, 'Author\'s website')
            ->addArgument('birthdate', InputArgument::REQUIRED, 'Author\'s birthdate')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $username = $input->getArgument('username');
        $email = $input->getArgument('email');
        $name = $input->getArgument('name');
        $biography = $input->getArgument('biography');
        $location = $input->getArgument('location');
        $website = $input->getArgument('website');
        $birthdate = $input->getArgument('birthdate');

        $command = new SignUp(
            Uuid::uuid4()->toString(),
            $username,
            $email,
            $name,
            $biography,
            $location,
            $website,
            $birthdate,
        );

        //ignore
        dump($command);
        //end-ignore

        return Command::SUCCESS;
    }
}
//end-snippet
