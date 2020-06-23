<?php

namespace CheeperLayered;

use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TemplateTest extends TestCase
{
    private static string $TEMPLATES_PATH = __DIR__ . '/../../src/CheeperLayered/templates';

    private Environment $twig;

    public function setUp(): void
    {
        $loader = new FilesystemLoader(self::$TEMPLATES_PATH);
        $this->twig = new Environment($loader);
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
