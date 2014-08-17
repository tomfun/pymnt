<?php

namespace Tommy\Pymnt\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Tommy\Pymnt\MainBundle\Entity\User;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('TommyPymntMainBundle:Default:index.html.twig', array('name' => 'a!sdfasdf'));
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
                'error'         => $error,
            )
        );
    }
}
