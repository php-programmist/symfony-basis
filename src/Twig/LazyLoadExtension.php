<?php

namespace App\Twig;

use App\Service\DummyImageService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class LazyLoadExtension extends AbstractExtension
{
    /**
     * @var DummyImageService
     */
    protected $dummy_image_service;
    
    public function __construct(DummyImageService $dummy_image_service)
    {
        $this->dummy_image_service = $dummy_image_service;
    }
    
    public function getFunctions(): array
    {
        return [
            new TwigFunction('lazy_load', [$this, 'lazyLoad'], ['is_safe' => ['html']]),
            new TwigFunction('lazy_load_bg', [$this, 'lazyLoadBg'], ['is_safe' => ['html']]),
            new TwigFunction('slick_lazy_load', [$this, 'slickLazyLoad'], ['is_safe' => ['html']]),
            new TwigFunction('lazy_youtube', [$this, 'lazyYoutube'], ['is_safe' => ['html']]),
        ];
    }
    
    /**
     * @param string $src
     * @param array  $classes
     *
     * @param bool   $lazy_off
     *
     * @return string
     */
    public function lazyLoad(string $src, array $classes = [], $lazy_off = false): string
    {
        $classes[] = 'lazy';
        if ($lazy_off) {
            return 'src="' . $src . '" class="' . implode(' ', $classes) . '"';
        }
        $base64data = $this->dummy_image_service->generateBase64FitToFile($src);
        
        return 'src="' . $base64data . '"
        data-src="' . $src . '"
        class="' . implode(' ', $classes) . '"';
    }
    
    /**
     * @param string $src
     * @param array  $classes
     *
     * @param bool   $lazy_off
     *
     * @return string
     */
    public function lazyLoadBg(string $src, array $classes = [], $lazy_off = false): string
    {
        $classes[] = 'lazy';
        if ($lazy_off) {
            return 'style="background:url(' . $src . ')"';
        }
        return 'data-bg="url(' . $src . '") class="' . implode(' ', $classes) . '"';
    }
    
    /**
     * @param string $src
     * @param bool   $lazy_off
     *
     * @return string
     */
    public function slickLazyLoad(string $src,$lazy_off = false):string
    {
        if ($lazy_off) {
            return 'src="'.$src.'"';
        }
        $base64data = $this->dummy_image_service->generateBase64FitToFile($src);
        return 'src="'.$base64data.'"
        data-lazy="'.$src.'"';
    }
    
    public function lazyYoutube($link,$lazy_off = false)
    {
        preg_match("/(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be\/)[^&\n]+|(?<=embed\/)[^&\n]+/", $link, $matches);
        $video_id = $matches[0];
        if ($lazy_off) {
            return '<iframe width="560" height="415" src="https://www.youtube.com/embed/'.$video_id.'" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>';
        }
        return '<div class="youtube" data-embed="'.$video_id.'">
                <div class="play-button"></div>
            </div>';
    }
}
