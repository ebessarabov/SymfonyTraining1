<?php

namespace BatteryBundle\Controller;

use BatteryBundle\Entity\Battery;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use BatteryBundle\Form\BatteryType;

/**
 * Class DefaultController
 * @package BatteryBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @return Response
     */
    public function indexAction()
    {
        $repository = $this->getDoctrine()->getRepository('BatteryBundle:Battery');

        return $this->render(
            '@Battery/Default/index.html.twig', [
                'batteries' => $repository->getStatistic()
            ]
        );
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function addAction(Request $request)
    {
        $form = $this->createForm(BatteryType::class, new Battery());
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render(
            '@Battery/Default/add.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @return RedirectResponse
     */
    public function resetAction()
    {
        $repository = $this->getDoctrine()->getRepository('BatteryBundle:Battery');
        $repository->removeAll();

        return $this->redirectToRoute('homepage');
    }
}
