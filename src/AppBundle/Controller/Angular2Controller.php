<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/angular2")
 */
class Angular2Controller extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $script = array();
        $script[] = "var app = {}";
        $script[] = "var window = window || this";
        $script[] = "var document = document || this";
        $script[] = file_get_contents(__DIR__ .'/../Resources/public/js/angular2-polyfills.js');
        $script[] = file_get_contents(__DIR__ .'/../Resources/public/js/Rx.umd.js');
        $script[] = file_get_contents(__DIR__ .'/../Resources/public/js/angular2-all.umd.js');
        $script[] = file_get_contents(__DIR__ .'/../Resources/public/js/app/app.component.js');
        $script[] = file_get_contents(__DIR__ .'/../Resources/public/js/app/boot.js');
        $script = implode(";\n", $script);

        $v8 = $this->get('v8js');
        $output = $v8->executeString($script);

        return $this->render('AppBundle:Angular2:index.html.twig', array(
            // ...
        ));
    }

}
