<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;

class PageController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(){
        return $this->render('homepage/index.html.twig');
    }

    /**
     * @Route("/about", name="about")
     */
    public function about() {
        return $this->render('about/about.html.twig');
    }

    /**
     * @Route("/contact", name="contact", methods={"GET","POST"})
     * @param Request $request
     * @param \Swift_Mailer $mailer
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function contact(Request $request, \Swift_Mailer $mailer)
    {
        $enquiry = new Contact();

        $form = $this->createForm(ContactType::class, $enquiry);
        //dump($request->getContent());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $enquiry = $form->getData();
              //  dump($enquiry);
            $message = (new \Swift_Message('Contact enquiry from Example'))
                ->setFrom('contact@example.com')
                ->setTo($this->container->getParameter('app.emails.contact_email'))
                ->setBody($this->renderView('contact/contactEmail.txt.twig', array('enquiry' => $enquiry)),
                    'text/html');
            $mailer->send($message);

            $this->get('session')->getFlashbag('blog-notice', 'Your contact enquiry was successfully sent. Thank you!');
            //dump($message); die;

            $this->addFlash('success', 'Message sent succcessfully!');
            // Redirect - This is important to prevent users re-posting
            // the form if they refresh the page
            return $this->redirectToRoute("contact");
        }

        return $this->render('contact/contact.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/search", name="search")
     */
    public function search() {
        return $this->render('search/search.html.twig');
    }

    /**
     * @Route("/sidebar", name="sidebar")
     */
    public function sidebar() {
        return $this->render('sidebar/sidebar.html.twig');
    }

}
