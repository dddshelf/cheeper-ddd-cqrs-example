<?php

namespace CheeperLayered;

//snippet cheep-controller
class CheepController
{
    public function postAction(Request $request): string
    {
        if (
            $request->request->has('submit') &&
            Validator::validate($request->request->post)
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
            } catch (\Exception $e) {
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
