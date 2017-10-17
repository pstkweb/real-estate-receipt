<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Housing;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Housing controller.
 *
 * @Route("housing")
 */
class HousingController extends Controller
{
    /**
     * Lists all housing entities.
     *
     * @Route("/", name="housing_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $housings = $em->getRepository('AppBundle:Housing')->findAll();

        return $this->render('housing/index.html.twig', array(
            'housings' => $housings,
        ));
    }

    /**
     * Creates a new housing entity.
     *
     * @Route("/new", name="housing_new")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $housing = new Housing();
        $form = $this->createForm('AppBundle\Form\HousingType', $housing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($housing);
            $em->flush();

            return $this->redirectToRoute('housing_show', array('id' => $housing->getId()));
        }

        return $this->render('housing/new.html.twig', array(
            'housing' => $housing,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a housing entity.
     *
     * @Route("/{id}", name="housing_show")
     * @Method("GET")
     *
     * @param Housing $housing
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Housing $housing)
    {
        $deleteForm = $this->createDeleteForm($housing);

        return $this->render('housing/show.html.twig', array(
            'housing' => $housing,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing housing entity.
     *
     * @Route("/{id}/edit", name="housing_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Housing $housing
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Housing $housing)
    {
        $deleteForm = $this->createDeleteForm($housing);
        $editForm = $this->createForm('AppBundle\Form\HousingType', $housing);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('housing_edit', array('id' => $housing->getId()));
        }

        return $this->render('housing/edit.html.twig', array(
            'housing' => $housing,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a housing entity.
     *
     * @Route("/{id}", name="housing_delete")
     * @Method("DELETE")
     *
     * @param Request $request
     * @param Housing $housing
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Housing $housing)
    {
        $form = $this->createDeleteForm($housing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($housing);
            $em->flush();
        }

        return $this->redirectToRoute('housing_index');
    }

    /**
     * Creates a form to delete a housing entity.
     *
     * @param Housing $housing The housing entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Housing $housing)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('housing_delete', array('id' => $housing->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
