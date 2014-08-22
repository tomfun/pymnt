<?php

namespace Tommy\Pymnt\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Tommy\Pymnt\MainBundle\Entity\User;

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

    public function indexAction()
    {
        $token = $this->getSecurity()->getToken();
        if($token instanceof TokenInterface){
            $user = $token->getUser();
            if ($user instanceof User) {
                return $this->redirect($this->generateUrl('tommy_pymnt_main_cabinet'));
            }
        }
        return $this->render('TommyPymntMainBundle:Default:index.html.twig', array('name' => 'someone'));
    }

    /**
     * @param string $code
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function confirmationAction($code)
    {
        $userRepo = $this->get('user_repository');
        $user = $userRepo->getUserByCode($code);
        if($user){
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
            $factory = $this->getEncoderFactory();
            $encoder = $factory->getEncoder($user);
            $user->setEmail($request->get('email'));
            $user->setPlainPassword($encoder, $request->get('password'));

            $link = $this->generateUrl('register_confirmation', ['code' => $user->getCode()], UrlGeneratorInterface::ABSOLUTE_URL);
            $mailer = $this->getMailer();
            $html = $this->renderView('TommyPymntMainBundle:Emails:cabinet.html.twig', array('confirmation' => $link));

            $message = \Swift_Message::newInstance()
                ->setSubject('Registration on Pymnt')
                ->setTo($user->getEmail())
                ->setFrom("tomfun1990@gmail.com")
                ->setBody($html, 'text/html');
            $res = $mailer->send($message);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return new Response($res ? 'Check your mail.' : 'We have some truble with your email.');
        } else {
            return $this->render('TommyPymntMainBundle:Default:register.html.twig');
        }
    }

    public function cabinetAction()
    {
        $usr = $this->getSecurity()->getToken()->getUser();
        $name = '. - ! hz ! - .';
        if (is_object($usr) && $usr instanceof User) {
            $name = $usr->getEmail();
        }
        return $this->render('TommyPymntMainBundle:Default:cabinet.html.twig', array('name' => $name));
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
        /*
                $factory = $this->get('security.encoder_factory');
                $user = new User();

                $encoder = $factory->getEncoder($user);
                $password = $encoder->encodePassword('asdf', $user->getSalt());
                $user->setHash($password);

                var_dump($user);
                var_dump('pass:');
                var_dump($password);
        //        var_dump(mcrypt_create_iv(22, MCRYPT_DEV_URANDOM));
                var_dump('pass_hash:');
                var_dump(password_hash('asdf', PASSWORD_BCRYPT,
                    [
                        'cost' => 12,
                        'salt' => $user->getSalt()
                    ]));
        */
        return $this->render(
            'TommyPymntMainBundle:Default:login.html.twig',
            array(
                // last username entered by the user
                'name' => $lastUsername,
                'error' => $error,
            )
        );
    }
}
