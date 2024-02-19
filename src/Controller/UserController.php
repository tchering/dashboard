<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
            'title' => 'List Users'
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
            'title' => "New User"
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
            'title' => 'User Desc'
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager ,UserPasswordHasherInterface $hash): Response
    {
        //! Since we have added all the role using CRUD. Here we will do the following steps
        //todo 1st . We will get all list of roles here.
        $allRoles = $entityManager->getRepository(Role::class)->findAll();
        //todo 2nd . Here we will use for loop to create all list of role in array since role is in array in symfony .
        $roles = [];
        foreach ($allRoles as $role) {
            $libelle = $role->getLibelle(); //? in first loop $libelle = ['ROLE_ADMIN']
            $roles[$libelle] = $libelle; //? here ['ROLE_ADMIN'=>'ROLE_ADMIN'] IN first loop
            //? when loops complete ['ROLE_ADMIN'=>'ROLE_ADMIN','ROLE_USER'=>'ROLE_USER'] AND SO ON 
            //!NOTE:If you see in userType it is same sauf here its dynamic.
        }
        $form = $this->createForm(UserType::class, $user);
        //todo 3rd. Now we need to add this roles field in the form show user can select the role.
        $form->add('roles',ChoiceType::class,[ //? here 'roles' is the column in table user in database not $roles=[].
            'choices'=>$roles,
            'multiple'=>true,
            // 'expanded'=>true
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //!When user register then password will be hashed but here will are modifying,creating user as admin so password is not hased. Therefore we need to use method to hash password.
            //todo here $form is the instance of form class which is inside Vendor and getDate is method inside this class.
            //todo so when user enter password in field then this method below capture the password and stores it inside $plainPassword.
            $plainPassword = $form->get('plainPassword')->getData();
            if($plainPassword){
                //? hassPassword takes 2 parameter . 1/user entity and 2/plain password
                $password = $hash->hashPassword($user,$plainPassword);
                //setPassword is method in User entity to set the password inside database.
                $user->setPassword($password);
            }
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
            'title' => 'Modify User'
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
