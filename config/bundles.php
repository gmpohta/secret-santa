<?php

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Bundle\WebProfilerBundle\WebProfilerBundle;

return [
    FrameworkBundle::class => ['all' => true],
    TwigBundle::class => ['dev' => true, 'test' => true],
    WebProfilerBundle::class => ['dev' => true, 'test' => true],
    MonologBundle::class => ['all' => true],
];
