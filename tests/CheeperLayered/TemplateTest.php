<?php

namespace CheeperLayered;

use PHPUnit\Framework\TestCase;

class TemplateTest extends TestCase
{
    private static $TEMPLATES_PATH = 'src/CheeperLayered/templates';

    private $twig;

    public function setUp(): void
    {
        $loader = new \Twig\Loader\FilesystemLoader(self::$TEMPLATES_PATH);
        $this->twig = new \Twig\Environment($loader);
    }

    /**
     * @test
     */
    public function itShouldRenderTemplate(): void
    {
        $c = Cheep::compose(1, 'A message');
        $c->setId(1);

        $html = $this->twig->render('timeline.html.twig', [
            'username' => 'johndoe',
            'cheeps' => [
                [
                    'author' => ['id' => 1, 'username' => 'johndoe'],
                    'cheep' => $c
                ]
            ]
        ]);

        $this->assertNotEmpty($html);
    }
}
