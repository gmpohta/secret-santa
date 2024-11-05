<?php

declare(strict_types=1);

namespace App\Infrastructure\Framework;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Controller;

abstract class AbstractController extends Controller
{
    use HasJsonResponse;
}
