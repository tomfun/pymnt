<?php

namespace Tommy\Pymnt\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Tommy\Pymnt\MainBundle\Entity\Label;
use Tommy\Pymnt\MainBundle\Entity\Phone;
use Tommy\Pymnt\MainBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations as Rest;

class DefaultController extends Controller
{
    /**
     * @return \Symfony\Component\Security\Core\SecurityContext
     */
    protected function getSecurity()
    {
        return $this->get('security.context');
    }

    /**
     * @return \Symfony\Component\Security\Core\Encoder\EncoderFactory
     */
    protected function getEncoderFactory()
    {
        return $this->get('security.encoder_factory');
    }

    /**
     * @return \Swift_Mailer
     */
    protected function getMailer()
    {
        return $this->get('mailer');
    }

    /**
     * @Rest\Get("/", name="tommy_pymnt_main_homepage")
     * @Rest\View()
     */
    public function indexAction()
    {
        $token = $this->getSecurity()->getToken();
        if ($token instanceof TokenInterface) {
            $user = $token->getUser();
            if ($user instanceof User) {
                return $this->redirect($this->generateUrl('tommy_pymnt_main_cabinet'));
            }
        }
        //return $this->render('TommyPymntMainBundle:Default:index.html.twig', array('name' => 'someone'));
        return ['name' => 'someone'];
    }

    /**
     * @param string $code
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function confirmationAction($code)
    {
        $userRepo = $this->get('user_repository');
        $user = $userRepo->getUserByCode($code);
        if ($user) {
            $user->setConfirmed(true);
            $user->setInformable(true);
            $this->getDoctrine()->getManager()->flush();
            $token = new UsernamePasswordToken($user, null, "human", $user->getRoles());
            $this->getSecurity()->setToken($token);
            return new Response('Congratulations, now we trust you.');
        }
        return new Response('What are you doing man, maybe you forget some characters?!');
    }

    public function registerAction(Request $request)
    {
        if ($request->isMethod('post')) {
            $user = new User();
            $form = $this->createForm('registration', $user);
            $form->handleRequest($request);
            if (!$form->isValid()) {
                return $this->render('TommyPymntMainBundle:Default:register.html.twig', ['form' => $form->createView()]);
            }
            $factory = $this->getEncoderFactory();
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
            $form = $this->createForm('registration', null, [
                'action' => $this->generateUrl('register')
            ])->createView();
            return $this->render('TommyPymntMainBundle:Default:register.html.twig', ['form' => $form]);
        }
    }

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

        return $this->render(
            'TommyPymntMainBundle:Default:login.html.twig',
            array(
                // last username entered by the user
                'name'  => $lastUsername,
                'error' => $error,
            )
        );
    }

}
