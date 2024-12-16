<?php

namespace App\Controller;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProjectController extends AbstractController
{
    public function __construct(private ProjectRepository $projectRepository, private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/project', name: 'create_project', methods: ['POST'])]
    public function createProjct(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $project = new Project();
        $project->setTitle($data['title']);
        $project->setDescription($data['description']);
        $project->setNumberOfLots($data['numberOfLots']);
        $project->setPostalCode($data['postalCode']);
        $project->setDeliveryDate(new \DateTime($data['deliveryDate']));
        $project->setPhoto($data['photo']);
        $project->setActive(true); // Assume that a new project is active
        $project->setUser($this->getUser());
        $this->entityManager->persist($project);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Project created successfully', 'project' => $project->__toArray()], Response::HTTP_CREATED);

    }

    #[Route('/project/{id}', name: 'delete_project', methods: ['DELETE'])]
    public function deleteProject(int $id): Response
    {
        $project = $this->projectRepository->find($id);

        if (!$project) {
            return new JsonResponse(['message' => 'Project not found'], Response::HTTP_NOT_FOUND);
        }

        $project->setActive(false);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Project deleted successfully']);
    }

    #[Route('/project/{id}', name: 'update_project', methods: ['PUT'])]
    public function updateProject(int $id, Request $request): Response
    {
        $project = $this->projectRepository->find($id);

        if (!$project) {
            return new JsonResponse(['message' => 'Project not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['description'])) {
            $project->setDescription($data['description']);
        }
        if (isset($data['numberOfLots'])) {
            $project->setNumberOfLots($data['numberOfLots']);
        }
        if (isset($data['postalCode'])) {
            $project->setPostalCode($data['postalCode']);
        }
        if (isset($data['deliveryDate'])) {
            $project->setDeliveryDate(new \DateTime($data['deliveryDate']));
        }
        if (isset($data['photo'])) {
            $project->setPhoto($data['photo']);
        }

        $this->entityManager->flush();
        return new JsonResponse(['message' => 'Project updated successfully']);
    }

    #[Route('/project/{id}', name: 'get_project', methods: ['GET'])]
    public function getProject(int $id): Response
    {
        $project = $this->projectRepository->find($id);
        if (!$project) {
            return new JsonResponse(['message' => 'Project not found'], Response::HTTP_NOT_FOUND);
        }
        return new JsonResponse(['project' => $project]);

    }

    #[Route('/project', name: 'get_projects', methods: ['GET'])]
    public function getProjects(Request $request): Response
    {
        $projects = $this->projectRepository->findBy(['isActive' => true]);
        return new JsonResponse(['projects' => $projects]);


    }

    #[Route('/project/search', name: 'search_project', methods: ['GET'])]
    public function searchProjects(Request $request): Response
    {
        $title = $request->query->get('title');
        $deliveryDateMin = $request->query->get('deliveryDateMin');
        $deliveryDateMax = $request->query->get('deliveryDateMax');
        $criterea = ['active' => true];
        if ($title) {
            $criterea['title'] = $title;
        }
        if ($deliveryDateMin) {
            $criteria['deliveryDate'] = ['>=', new \DateTime($deliveryDateMin)];
        }
        if ($deliveryDateMax) {
            $criterea['deliveryDate'] = ['<=', new \DateTime($deliveryDateMax)];
        }
        $projects = $this->projectRepository->findBy($criterea);
        return new JsonResponse(['projects' => $projects]);

    }
}
