<?php

namespace Myindexd\CatalogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CatalogController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('MyindexdCatalogBundle:Category')->find(2);
        $product = $em->getRepository('MyindexdCatalogBundle:Product')->find(2);

        $product->addCategory($category);
//        $category->addProduct($product);
//        $em->persist($category);
        $em->persist($product);
        $em->flush();

        $data['category'] = $category;
        return $this->render('MyindexdCatalogBundle:Default:index.html.twig', $data);
    }
}
