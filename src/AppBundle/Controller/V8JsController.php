<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/v8js")
 */
class V8JsController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction(Request $request)
    {
        $data = $request->request->get('form');
        $form = $this->createFormBuilder($data)
            ->add('code', TextareaType::class, [
                'attr' => ['rows' => 20],
            ])
            ->add('save', SubmitType::class, array('label' => 'Run'))
            ->getForm();

        $output = '';
        if ($data['code']) {
            $v8 = $this->get('v8js');
            $output = $v8->executeString($data['code']);
        }
        return $this->render('AppBundle:V8js:index.html.twig', array(
            'form' => $form->createView(),
            'output' => $output,
        ));
    }

}
