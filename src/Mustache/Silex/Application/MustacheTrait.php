<?php

/*
 * This file is part of Mustache.php.
 *
 * (c) 2013 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mustache\Silex\Application;

use Symfony\Component\HttpFoundation\Response;

/**
 * Mustache trait.
 *
 * @author Justin Hileman <justin@justinhileman.info>
 */
trait MustacheTrait
{
    /**
     * Renders a template and returns a Response.
     *
     * @param string   $template The template name
     * @param mixed    $context  The Mustache rendering context
     * @param Response $response A Response instance
     *
     * @return Response A Response instance
     */
    public function render($template, $context = array(), Response $response = null)
    {
        if ($response === null) {
            $response = new Response();
        }

        $response->setContent($this['mustache']->render($template, $context));

        return $response;
    }

    /**
     * Renders a template.
     *
     * @param string $template The template name
     * @param mixed  $context  The Mustache rendering context
     *
     * @return Response A Response instance
     */
    public function renderTemplate($template, $context = array())
    {
        return $this['mustache']->render($template, $context);
    }
}
