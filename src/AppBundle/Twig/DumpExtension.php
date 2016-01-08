<?php
/**
 * Created by PhpStorm.
 * User: bjd
 * Date: 1/7/16
 * Time: 9:40 PM
 */

namespace AppBundle\Twig;


use Symfony\Component\VarDumper\Cloner\ClonerInterface;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;

class DumpExtension extends \Symfony\Bridge\Twig\Extension\DumpExtension
{
    private $cloner;

    public function __construct(ClonerInterface $cloner)
    {
        $this->cloner = $cloner;
        parent::__construct($cloner);
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('dump', array($this, 'dump'), array('is_safe' => array('html'), 'needs_context' => true, 'needs_environment' => true)),
        );
    }

    public function dump(\Twig_Environment $env, $context)
    {
        if (2 === func_num_args()) {
            $vars = array();
            foreach ($context as $key => $value) {
                if (!$value instanceof \Twig_Template) {
                    $vars[$key] = $value;
                }
            }

            $vars = array($vars);
        } else {
            $vars = func_get_args();
            unset($vars[0], $vars[1]);
        }

        $dump = fopen('php://memory', 'r+b');
        $dumper = new HtmlDumper($dump);

        foreach ($vars as $value) {
            $dumper->dump($this->cloner->cloneVar($value));
        }
        rewind($dump);

        return stream_get_contents($dump);
    }
}