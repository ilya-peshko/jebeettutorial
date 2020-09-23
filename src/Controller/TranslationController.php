<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Yaml;

/**
 * Class TranslationController
 * @package App\Controller
 */
class TranslationController extends AbstractController
{
    private $projectDir;

    /**
     * TranslationController constructor.
     * @param string $projectDir
     */
    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
    }

    /**
     * @Route("/{_locale<en|ru>}/translation.js", name="translation")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request): Response
    {
        $locale = $request->getLocale();
        $array  = include($this->projectDir . '/translations/messages.' . $locale . '.php');

        $translations = $this->renderView(
            'translation/translation.js.twig',
            [
                'json' => json_encode($array)
            ]
        );

        return new Response($translations, 200, ['Content-Type' => 'text/javascript']);
    }
}
