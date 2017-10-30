<?php

namespace Myindexd\CatalogBundle\Controller;

use Myindexd\CatalogBundle\Entity\Contacts;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\HttpFoundation\Request;

class CatalogController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('MyindexdCatalogBundle:Category')->findAll();

        $data['categories'] = $categories;
        return $this->render('MyindexdCatalogBundle:Default:index.html.twig', $data);
    }

    public function productAction(string $slug)
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('MyindexdCatalogBundle:Category')->findAll();
        $data['categories'] = $categories;

        $product = $em->getRepository('MyindexdCatalogBundle:Product')->findBy(['slug' => $slug]);

        if(count($product) == 1){
            $data['product'] = $product[0];
            return $this->render('MyindexdCatalogBundle:Default:product.html.twig', $data);
        } else {
            return $this->render('MyindexdCatalogBundle:Default:error404.html.twig');
        }
    }

    public function categoryAction(string $slug)
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('MyindexdCatalogBundle:Category')->findAll();
        $category = array_filter($categories, function($element) use ($slug){
            return $element->getSlug() == $slug;
        });
        $data['categories'] = $categories;

        if(count($category) == 1){
            $category = array_pop($category);
            $category->getProducts();
            $data['category'] = $category;
            return $this->render('MyindexdCatalogBundle:Default:category.html.twig', $data);
        } else {
            return $this->render('MyindexdCatalogBundle:Default:error404.html.twig');
        }
    }

    public function contactsShowAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('MyindexdCatalogBundle:Category')->findAll();
        $data['categories'] = $categories;

        $form = $this->createFormBuilder(new Contacts())
            ->add('title', Type\TextType::class)
            ->add('description', Type\TextareaType::class)
            ->add('submit', Type\SubmitType::class)
            ->getForm();

        $data['form'] = $form->createView();
        return $this->render('MyindexdCatalogBundle:Default:contacts.html.twig', $data);
    }

    public function contactsStoreAction(Request $request)
    {
        $form = $this->createFormBuilder(new Contacts())
            ->add('title', Type\TextType::class)
            ->add('description', Type\TextareaType::class)
            ->add('submit', Type\SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $contacts = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($contacts);
            $em->flush();
        }

        return $this->redirectToRoute('myindexd_catalog_contactsStorePage');
    }
}
