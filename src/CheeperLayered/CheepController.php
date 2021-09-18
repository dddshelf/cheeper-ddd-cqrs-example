<?php declare(strict_types=1);

namespace CheeperLayered;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

//snippet cheep-controller
final class CheepController extends AbstractController
{
    public function postAction(Request $request): Response
    {
        if (
            $request->request->has('submit') &&
            Validator::validate($request->request->all())
        ) {
            $cheepService = new CheepService();

            try {
                $cheepService->postCheep(
                    $request->request->get('username'),
                    $request->request->get('message')
                );

                $this->addFlash(
                    'notice',
                    'Cheep has been published successfully!'
                );
            } catch (\Exception) {
                $this->addFlash(
                    'error',
                    'Unable to publish cheep!'
                );
            }
        }

        return $this->render('cheeps/timeline.html.twig');
    }
}
//end-snippet
