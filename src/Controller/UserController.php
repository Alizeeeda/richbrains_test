<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/api', name: 'user_api')]
class UserController extends AbstractController
{
    public function __construct(private SerializerInterface $serializer, private Security $security)
    {
    }

    #[Route('/users', name: 'users', methods: ["GET"])]
    public function getPosts(ManagerRegistry $doctrine, UserRepository $userRepository): JsonResponse
    {
        if ($this->checkAdminAccess()) {
            $data = $doctrine->getRepository(User::class)->findAll();
            return $this->response($this->serializer->serialize($data, JsonEncoder::FORMAT));
        } else {
            $data = [
                'status' => 403,
                'success' => "Access denied",
            ];
            return $this->response($data);
        }
    }

    #[Route('/users', name: 'users_add', methods: ["POST"])]
    public function addPost(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface  $passwordHasher): JsonResponse
    {

        if ($this->checkAdminAccess()) {
            $entityManager = $doctrine->getManager();
            try {
                $request = $this->transformJsonBody($request);

                if (
                    !$request ||
                    !filter_var($request->get('email'), FILTER_VALIDATE_EMAIL) ||
                    !filter_var($request->get('phone'), FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[+]{1}[0-9]{7,12}$/"))) ||
                    !filter_var($request->get('firstName'), FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Zа-яА-Я]{2,}$/"))) ||
                    !filter_var($request->get('lastName'), FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Zа-яА-Я]{2,}$/"))) ||
                    !filter_var($request->get('password'), FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/"))) ||
                    !$request->get('roles')
                ) {
                    throw new \Exception();
                }
                $user = new User();
                $user->setEmail($request->get('email'));
                $user->setPhone($request->get('phone'));
                $user->setFirstName($request->get('firstName'));
                $user->setLastName($request->get('lastName'));
                $user->setRoles($request->get('roles'));
                $user->setPassword($passwordHasher->hashPassword($user, $request->get('password')));
                $entityManager->persist($user);
                $entityManager->flush();

                $data = [
                    'status' => 200,
                    'success' => "User added successfully",
                ];
                return $this->response($data);
            } catch (\Exception $e) {
                $data = [
                    'status' => 422,
                    'errors' => "Data no valid",
                ];
                return $this->response($data, 422);
            }
        } else {
            $data = [
                'status' => 403,
                'success' => "Access denied",
            ];
            return $this->response($data);
        }
    }


    #[Route('/users/{id}', name: 'users_get', methods: ["GET"])]
    public function getUsers(UserRepository $userRepository, $id): JsonResponse
    {
        if ($this->checkAdminAccess()) {
            $user = $userRepository->find($id);

            if (!$user) {
                $data = [
                    'status' => 404,
                    'errors' => "User not found",
                ];
                return $this->response($data, 404);
            }
            return $this->response($this->serializer->serialize($user, JsonEncoder::FORMAT));
        } else {
            $data = [
                'status' => 403,
                'success' => "Access denied",
            ];
            return $this->response($data);
        }
    }


    #[Route('/users/{id}', name: 'users_put', methods: ["PUT"])]
    public function updateUser(Request $request, ManagerRegistry $doctrine, UserRepository $userRepository, UserPasswordHasherInterface  $passwordHasher, $id): JsonResponse
    {
        if ($this->checkAccess($id)) {
            $entityManager = $doctrine->getManager();
            try {
                $user = $userRepository->find($id);

                if (!$user) {
                    $data = [
                        'status' => 404,
                        'errors' => "User not found",
                    ];
                    return $this->response($data, 404);
                }

                $request = $this->transformJsonBody($request);
                if (
                    !$request ||
                    (!filter_var($request->get('email'), FILTER_VALIDATE_EMAIL) && $request->get('email')) ||
                    (!filter_var($request->get('phone'), FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[+]{1}[0-9]{7,12}$/"))) && $request->get('phone')) ||
                    (!filter_var($request->get('firstName'), FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Zа-яА-Я]{2,}$/"))) && $request->get('firstName')) ||
                    (!filter_var($request->get('lastName'), FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Zа-яА-Я]{2,}$/"))) && $request->get('lastName')) ||
                    (!filter_var($request->get('password'), FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/"))) && $request->get('password'))

                ) {
                    throw new \Exception();
                }

                foreach ($request->toArray() as $key => $value) {
                    if ($key == "password") {
                        $user->setPassword($passwordHasher->hashPassword($user, $value));
                    } else {
                        $setter_name = "set" . $key;
                        $user->$setter_name($value);
                    }
                }

                $entityManager->persist($user);
                $entityManager->flush();
             
                $data = [
                    'status' => 200,
                    'success' => "User updated successfully",
                ];
                return $this->response($data);
            } catch (\Exception $e) {
                $data = [
                    'status' => 422,
                    'errors' => "Data no valid",
                ];
                return $this->response($data, 422);
            }
        } else {
            $data = [
                'status' => 403,
                'success' => "Access denied",
            ];
            return $this->response($data);
        }
    }



    #[Route('/users/{id}', name: 'users_delete', methods: ["DELETE"])]
    public function deletePost(ManagerRegistry $doctrine, UserRepository $userRepository, $id)
    {
        if ($this->checkAdminAccess()) {
            $entityManager = $doctrine->getManager();
            $user = $userRepository->find($id);

            if (!$user) {
                $data = [
                    'status' => 404,
                    'errors' => "User not found",
                ];
                return $this->response($data, 404);
            }

            $entityManager->remove($user);
            $entityManager->flush();
            $data = [
                'status' => 200,
                'errors' => "User deleted successfully",
            ];
            return $this->response($data);
        } else {
            $data = [
                'status' => 403,
                'success' => "Access denied",
            ];
            return $this->response($data);
        }
    }


    /**
     * Returns a JSON response
     */
    public function response($data, $status = 200, $headers = []): JsonResponse
    {
        return new JsonResponse($data, $status, $headers);
    }

    protected function transformJsonBody(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            return $request;
        }

        $request->request->replace($data);

        return $request;
    }


    protected function checkAdminAccess()
    {
        if ($this->security?->isGranted('ROLE_ADMIN')) {
            return true;
        } else {
            return false;
        }
    }

    protected function checkAccess(int $id)
    {
        //  dump($this->security?->getUser()?->getUserIdentifier()); dump($id); die;
        if ($this->security?->isGranted('ROLE_ADMIN') || (int) $this->security?->getUser()?->getUserIdentifier() === $id) {
            return true;
        } else {
            return false;
        }
    }
}
