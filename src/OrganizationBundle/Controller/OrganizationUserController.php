<?php

namespace OrganizationBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use OrganizationBundle\Entity\User;

/**
 * User controller.
 *
 * @Route("/organization/{organizationId}/user")
 */
class OrganizationUserController extends Controller
{
    /**
     * Lists all User entities.
     *
     * @Route("/", name="organization_user_index")
     * @Method("GET")
     */
    public function indexAction($organizationId)
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('OrganizationBundle:User')->findBy([
            'organization' => $organizationId
        ]);

        return $this->render('user/index.html.twig', array(
            'users' => $users,
        ));
    }
}
