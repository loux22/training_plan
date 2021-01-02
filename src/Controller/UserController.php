<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\WeekYears;
use App\Entity\WentOut;
use App\Form\EnterWentOutType;
use App\Form\UserInfoType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/", name="null")
     */
    public function index()
    {
        if ($this -> getUser()) {
            return $this ->redirectToRoute('profil');
        }
        return $this->redirectToRoute('authentification');
    }

    /**
     * @Route("/authentification", name="authentification")
     */
    public function authentification(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        if ($this -> getUser()) {
            return $this ->redirectToRoute('profil');
        }
        $manager = $this->getDoctrine()->getManager();
        $input = $request->request->all();
        $user = new User;
        $form = $this->createForm(UserInfoType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $stringAge = strval($input['age']);
            $age = DateTime::createFromFormat('Y-m-d', $stringAge);
            $user->setAge($age);
            $user->setHeight(0);
            $user->setIsCoach(false);
            $user->setWeight($input['weight']);
            $user->setAvatar('default.png');

            $manager->persist($user); 
            $manager->flush($user); 

            $this->addFlash('success', 'La création de votre compte est une réussite ' . $user->getName());
            return $this ->redirectToRoute('authentification');
        }elseif($form->isSubmitted()){
            $this->addFlash('errors', 'erreur à la creation du compte');
        }

        return $this->render('user/index.html.twig', [
            "formSignup" => $form->createView(),
            'lastUsername' => ""
            ]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $user = new User;
        $form = $this->createForm(UserInfoType::class, $user);
        $lastUsername = $authenticationUtils -> getLastUsername();
        $error = $authenticationUtils->getLastAuthenticationError();

        if($error){
            $this -> addFlash('errors', 'erreur d\'authentification');
        }

        return $this->render('user/index.html.twig', [
            "formSignup" => $form->createView(),
            'lastUsername' => $lastUsername
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(){}

    /**
     * @Route("/profil", name="profil")
     */
    public function profil()
    {
        if (!$this -> getUser()) {
            return $this ->redirectToRoute('authentification');
        }
        return $this->render('user/profil.html.twig', [
       
            ]);
    }

    /**
     * @Route("/profil/weight", name="weight")
     */
    public function weight()
    {
        if (!$this -> getUser()) {
            return $this ->redirectToRoute('authentification');
        }
        return $this->render('user/weight.html.twig', [
       
            ]);
    }

    /**
     * @Route("/profil/km", name="km")
     */
    public function km()
    {
        if (!$this -> getUser()) {
            return $this ->redirectToRoute('authentification');
        }
        $repoWeek = $this->getDoctrine()->getRepository(WeekYears::class);
        $week = $repoWeek->findWeekOrderByDate($this -> getUser());
        return $this->render('user/km.html.twig', [
            "weekYears" => $week
            ]);
    }

    /**
     * @Route("/profil/duration", name="duration")
     */
    public function duration()
    {
        if (!$this -> getUser()) {
            return $this ->redirectToRoute('authentification');
        }
        $repoWeek = $this->getDoctrine()->getRepository(WeekYears::class);
        $week = $repoWeek->findWeekOrderByDate($this -> getUser());
        return $this->render('user/duration.html.twig', [
            "weekYears" => $week
            ]);
    }

    function heure_to_secondes($heure){
        $array_heure=explode(":",$heure);
        $secondes=3600*$array_heure[0]+60*$array_heure[1]+$array_heure[2];
        return $secondes;
    }

    function add_heures($heure1,$heure2){
        $secondes1= $this -> heure_to_secondes($heure1);
        $secondes2= $this -> heure_to_secondes($heure2);
        $somme=$secondes1+$secondes2;
        //transfo en h:i:s
        $s=$somme % 60; //reste de la division en minutes => secondes
        if($s < 10){
            $s = intval('0') . $s;
        }
        $m1=($somme-$s) / 60; //minutes totales
        $m=$m1 % 60;//reste de la division en heures => minutes
        if($m < 10){
            $m = intval('0') . $m;
        }
        $h=($m1-$m) / 60; //heures
        $resultat=$h.":".$m.":".$s;
        // dd(strtotime($resultat));
        return $resultat;
    }

    /**
     * @Route("/profil/enterWentOut", name="enterWentOut")
     */
    public function enterWentOut(Request $request)
    {
        if (!$this -> getUser()) {
            return $this ->redirectToRoute('authentification');
        }
        $manager = $this->getDoctrine()->getManager();
        $wentOut = new WentOut;

        
        $repoWentOut = $this->getDoctrine()->getRepository(WentOut::class);
        $wentOutAll = $repoWentOut->findWentoutOrderByDate($this -> getUser());

        $form = $this->createForm(EnterWentOutType::class, $wentOut);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($wentOutAll as $key => $value) {
                if ($value -> getDate() == $wentOut -> getDate()) {
                    $this->addFlash('success', 'Vous avez déjà enregistrer une sortie sur cette date');
                    return $this ->redirectToRoute('enterWentOut');
                }
            }
            $good_format=strtotime($wentOut->getDate()->format('Y-m-d'));
            $repository =  $this->getDoctrine()->getRepository(WeekYears::class);
            $week = $repository -> findOneBy([
                'week' => date('W',$good_format),
                'years' => date('Y',$good_format),
                'user' => $this ->getUser()
            ]);
        
            $wentOut->setUser($this -> getUser());

            foreach ($wentOutAll as $key => $value) {
                if ($week != null && $value -> getUser() == $wentOut -> getUser() && $week == $value->getWeek()) {
                    // $allWentout = $repoWentOut-> findWeekOrderByDate($week);
                    $week->setKm($wentOut -> getKm() + $week -> getKm());
                    $oldDuration = $week -> getDuration()->format('H:i:s');
                    $newDuration = $wentOut -> getDuration()->format('H:i:s');
                
                    $week->setDuration(date_create($this -> add_heures($oldDuration, $newDuration)));

                    $manager->persist($week); 
                    $manager->flush($week);
                    $wentOut->setWeek($week); 
                    $manager->persist($wentOut); 
                    $manager->flush($wentOut);
                    
                   
                    $this->addFlash('errors', 'Votre sortie a été enregistrer');
                    return $this ->redirectToRoute('enterWentOut');
                }
            }

            $week = new WeekYears;
            $week->setKm($wentOut->getKm());
            $week->setDuration($wentOut->getDuration());
            $week->setWeek(date('W',$good_format));
            $week->setYears(date("Y", strtotime($wentOut->getDate()->format('Y-m-d'))));
            $date = new DateTime();
            $week->setDate($date->setISOdate(date("Y", strtotime($wentOut->getDate()->format('Y-m-d'))), date('W',$good_format)));
            $week->setUser($this -> getUser());
            
         
            $manager->persist($week); 
            $manager->flush($week); 
            $wentOut->setWeek($week);
            $manager->persist($wentOut); 
            $manager->flush($wentOut);
            
            


            $this->addFlash('success', 'Votre sortie a été enregistrer');
            return $this ->redirectToRoute('enterWentOut');
        }

        return $this->render('user/enterWentOut.html.twig', [
            "formWentOut" => $form->createView()
            ]);
    }
}
