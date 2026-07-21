<?php

namespace App\Controller\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;

class AppController extends AbstractController
{
    /**
     * Exhibition photos shown in the homepage carousel — excluded from the gallery grid.
     */
    private const CAROUSEL_IMAGES = [
        'visionary-exhibition-east.jpg',
        'mentalities-in-visionary.jpg',
        'visionary-west-wing.jpg',
    ];

    public function __construct(
        private readonly string $projectDir,
    ) {
    }

    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        return $this->render('app/index.html.twig', [
            'artworks' => $this->galleryImages(),
        ]);
    }

    #[Route('/about/', name: 'about')]
    public function about(): Response
    {
        return $this->render('app/about.html.twig');
    }

    #[Route('/purchase/', name: 'purchase')]
    public function purchase(): Response
    {
        return $this->render('app/purchase.html.twig');
    }

    /**
     * Build the gallery from the static images in public/images.
     *
     * @return list<array{image: string, title: string}>
     */
    private function galleryImages(): array
    {
        $dir = $this->projectDir . '/public/images';

        if (!is_dir($dir)) {
            return [];
        }

        $finder = (new Finder())
            ->files()
            ->in($dir)
            ->name('*.jpg')
            ->sortByName();

        $artworks = [];
        foreach ($finder as $file) {
            $filename = $file->getFilename();

            if (in_array($filename, self::CAROUSEL_IMAGES, true)) {
                continue;
            }

            $artworks[] = [
                'image' => $filename,
                'title' => $this->humanize($file->getFilenameWithoutExtension()),
            ];
        }

        return $artworks;
    }

    private function humanize(string $slug): string
    {
        $words = trim(preg_replace('/[^a-z]+/i', ' ', $slug));

        return ucwords($words) ?: (new AsciiSlugger())->slug($slug)->title()->toString();
    }
}
