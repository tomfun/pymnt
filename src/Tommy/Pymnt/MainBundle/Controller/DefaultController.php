<?php

namespace Tommy\Pymnt\MainBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Tommy\Pymnt\MainBundle\Entity\Label;
use Tommy\Pymnt\MainBundle\Entity\Phone;
use Tommy\Pymnt\MainBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use Tommy\Pymnt\MainBundle\Repo\UserRepository;

class DefaultController
{
    /**
     * @DI\Inject("security.context")
     * @var SecurityContextInterface
     */
    protected $security;

    /**
     * @DI\Inject("security.encoder_factory")
     * @var EncoderFactoryInterface
     */
    protected $securityFactory;

    /**
     * @DI\Inject("mailer")
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @DI\Inject("router")
     * @var \Symfony\Bundle\FrameworkBundle\Routing\Router
     */
    protected $router;

    /**
     * @DI\Inject("user_repository")
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @DI\Inject("doctrine.orm.entity_manager")
     * @var EntityManager
     */
    protected $manager;

    /**
     * @DI\Inject("form.factory")
     * @var FormFactory
     */
    protected $formManager;

    /**
     * @Rest\Get("/", name="tommy_pymnt_main_homepage")
     * @Rest\View()
     */
    public function indexAction()
    {

        //var_dump($this->kernel);//usage !!
        $token = $this->security->getToken();
        if ($token instanceof TokenInterface) {
            $user = $token->getUser();
            if ($user instanceof User) {
                return new RedirectResponse($this->router->generate('tommy_pymnt_main_cabinet'));
            }
        }
        return ['name' => 'someone'];
    }

    /**
     * @Rest\Get("/confirmation/{code}", name="register_confirmation")
     */
    public function confirmationAction($code)
    {
        $user = $this->userRepository->getUserByCode($code);
        if ($user) {
            $user->setConfirmed(true);
            $user->setInformable(true);
            $this->manager->flush();
            $token = new UsernamePasswordToken($user, null, "human", $user->getRoles());
            $this->security->setToken($token);
            return new Response('Congratulations, now we trust you.');
        }
        return new Response('What are you doing man, maybe you forget some characters?!');
    }

    /**
     * @Rest\Get("/register", name="register", methods={"GET", "POST"})
     * @Rest\View()
     */
    public function registerAction(Request $request)
    {
        if ($request->isMethod('post')) {
            $user = new User();
            $form = $this->createForm('registration', $user);
            $form->handleRequest($request);
            if (!$form->isValid()) {
                return $this->render('TommyPymntMainBundle:Default:register.html.twig', ['form' => $form->createView()]);
            }
            $factory = $this->securityFactory();
            $encoder = $factory->getEncoder($user);
            //$user->setEmail($request->get('email'));
            $user->setPlainPassword(null, $encoder);

            $link = $this->generateUrl('register_confirmation', ['code' => $user->getCode()], UrlGeneratorInterface::ABSOLUTE_URL);
            $mailer = $this->getMailer();
            $html = $this->renderView('TommyPymntMainBundle:Emails:cabinet.html.twig', array('confirmation' => $link));

            $message = \Swift_Message::newInstance()
                ->setSubject('Registration on Pymnt')
                ->setTo($user->getEmail())
                ->setFrom("tomfun1990@gmail.com")
                ->setBody($html, 'text/html');
            $res = $mailer->send($message);
            if ($res) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);

                $phoneNumber = $user->getPhone('phone');
                $phone = new Phone();
                $phone->setInformable(false);
                $phone->setPhone($phoneNumber);
                $phone->setUser($user);
                $em->persist($phone);

                $label = new Label();
                $label->setPhone($phoneNumber);
                $label->setCaption('I');
                $label->setUser($user);
                $em->persist($label);

                $em->flush();
            }
            return new Response($res ? 'Check your mail.' : 'We have some trouble with your email.');
        } else {
            $form = $this->$formManager->create('registration', null, [
                'action' => $this->router->generate('register')
            ])->createView();
            return ['form' => $form];
        }
    }

    /**
     * @Rest\Get("/login", name="login")
     * @Rest\View()
     */
    public function loginAction(Request $request)
    {
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContextInterface::AUTHENTICATION_ERROR
            );
        } elseif (null !== $session && $session->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContextInterface::AUTHENTICATION_ERROR);
            $session->remove(SecurityContextInterface::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContextInterface::LAST_USERNAME);

        return [
                // last username entered by the user
                'name'  => $lastUsername,
                'error' => $error,
            ];
    }

}
