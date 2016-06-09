<?php

namespace OrganizationBundle\Controller;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use OrganizationBundle\Entity\Organization;
use Symfony\Component\HttpFoundation\Request;

/**
 * Organization controller.
 *
 * @Route("/organization")
 */
class OrganizationController extends Controller
{
    /**
     * Lists all Organization entities.
     *
     * @Route("/", name="organization_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $page = $request->query->getInt('page', 1);
        $em = $this->getDoctrine()->getManager();

        $limit = 10;
        $pagination = $em->getRepository('OrganizationBundle:Organization')->findPaginated([], [], $page, $limit);

        return $this->render('organization/index.html.twig', array(
            'pagination' => $pagination,
            'paginationData' => $this->getPaginationData($pagination, $page, $limit)
        ));
    }

    /**
     * @param Paginator $paginator
     */
    protected function getPaginationData(Paginator $paginator, $page = 1, $limit = 10)
    {
        $total = $paginator->count();
        $pages = floor($total / $limit) - 1;
        $currentOffset = ($page * $limit) - $limit;
        $nextOffset = (($page + 1) * $limit) - $limit;
        $previousOffset = (($page - 1) * $limit) - $limit;

        $nextPage = null;
        if ($nextOffset < $total) {
            $nextPage = $page + 1;
        }

        $previousPage = null;
        if ($previousOffset >= 0) {
            $previousPage = $page - 1;
        }

        $count = $total - $currentOffset;

        return [
            'total' => $total,
            'count' => $count,
            'pages' => $pages,
            'currentPage' => $page,
            'previousPage' => $previousPage,
            'nextPage' => $nextPage
        ];
    }
}
