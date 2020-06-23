<?php

declare(strict_types=1);

namespace CheeperCommandBus\ComplexNoCommandBus;

use Cheeper\Application\Command\Cheep\PostCheep;
use Cheeper\Application\Command\Cheep\PostCheepHandler;
use Cheeper\DomainModel\Author\AuthorDoesNotExist;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use function Safe\sprintf;

//snippet complex-command-handler-execution
final class SignUpController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private LoggerInterface $logger;
    private PostCheepHandler $postCheepHandler;

    //ignore
    public function __construct(PostCheepHandler $postCheepHandler)
    {
        $this->postCheepHandler = $postCheepHandler;
    }
    //end-ignore

    public function __invoke(Request $request): Response
    {
        //ignore
        $authorId = $request->request->get('author_id');
        $cheepId = $request->request->get('cheep_id');
        $message = $request->request->get('message');

        if (null === $authorId || null === $cheepId || null === $message) {
            throw new BadRequestHttpException('Invalid parameters given');
        }

        $command = PostCheep::fromArray([
            'author_id' => $authorId,
            'cheep_id' => $cheepId,
            'message' => $message,
        ]);
        //end-ignore

        try {
            $this->logger->info(sprintf('Executing command: %s', get_class($command)));
            $this->entityManager->transactional(function() use($command): void {
                ($this->postCheepHandler)($command);
                $this->logger->info(sprintf('Command %s executed successfully', get_class($command)));
            });
        } catch (AuthorDoesNotExist | InvalidArgumentException $exception) {
            $this->logger->error(sprintf('Command %s failed', get_class($command)));
            throw new BadRequestHttpException($exception->getMessage(), $exception);
        }

        return new Response('', Response::HTTP_CREATED);
    }
}
//end-snippet
