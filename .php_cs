<?php

return Symfony\CS\Config\Config::create()
    ->setUsingCache(true)
    ->level(Symfony\CS\FixerInterface::SYMFONY_LEVEL)
    ->fixers([
        '-phpdoc_inline_tag',
        '-phpdoc_to_comment',
        '-psr0',
        '-unalign_double_arrow',
        '-unalign_equals',
        'ereg_to_preg',
        'ordered_use',
        'php4_constructor',
        'phpdoc_order',
        'short_array_syntax',
        'strict',
        'strict_param',
    ])
    ->finder(
        Symfony\CS\Finder\DefaultFinder::create()
            ->in(__DIR__)
            ->path('src/')
            ->path('tests/')
    )
;
