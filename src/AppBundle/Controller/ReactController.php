<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/react", name="react")
 */
class ReactController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $react_source = file_get_contents($this->get('kernel')->getRootDir().'/../web/js/react.js');
        $app_source = file_get_contents($this->get('kernel')->getRootDir().'/../web/js/components.js');

        $rjs = new \ReactJS($react_source, $app_source);

        $rjs->setComponent('Timer', array(
            'startTime' => time(),
        ));

        $output = '
            <html>
            <head>
                <title>Epoch at server</title>
                <script src="/js/react.js"></script>
                <script src="/js/components.js"></script>
            </head>
            <body>
            <h1>Epoch server time</h1>
            <h2>Client side only</h2>
            <div id="client"></div>
            <h2>Server side with a two second client detach</h2>
            <div id="server">'.$rjs->getMarkup().'</div>
            <script>

            '.$rjs->getJS('#client').'

            setTimeout(function(){
                '.$rjs->getJS('#server').'
            }, 2000);
            </script>
            </body>
            </html>
        ';

        $response = new Response($output);

        return $response;
    }
}