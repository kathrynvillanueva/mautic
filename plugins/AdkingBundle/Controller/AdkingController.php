<?php

namespace MauticPlugin\AdkingBundle\Controller;

use Mautic\CoreBundle\Controller\CommonController;
use MauticPlugin\AdkingBundle\Integration\AdkingIntegration;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AdkingController extends CommonController
{
    const LEAD_ADDED_EVENT  = 'added.person';
    const LEAD_UPDATE_EVENT = 'updated.person';
    const LEAD_DELETE_EVENT = 'deleted.person';

    const COMPANY_ADD_EVENT    = 'added.organization';
    const COMPANY_UPDATE_EVENT = 'updated.organization';
    const COMPANY_DELETE_EVENT = 'deleted.organization';

    const USER_ADD_EVENT    = 'added.user';
    const USER_UPDATE_EVENT = 'updated.user';

    /**
     * @return JsonResponse
     */
    public function webhookAction(Request $request)
    {
        $integrationHelper    = $this->get('mautic.helper.integration');
        $pipedriveIntegration = $integrationHelper->getIntegrationObject(AdkingIntegration::NAME);

        if (!$pipedriveIntegration || !$pipedriveIntegration->getIntegrationSettings()->getIsPublished()) {
            return new JsonResponse([
                'status' => 'Integration turned off',
            ], Response::HTTP_OK);
        }

        if (!$this->validCredential($request, $pipedriveIntegration)) {
            throw new UnauthorizedHttpException('Basic');
        }

        $params   = json_decode($request->getContent(), true);
        $data     = $params['current'];
        $response = [
            'status' => 'ok',
        ];

        try {
            switch ($params['event']) {
                case self::LEAD_UPDATE_EVENT:
                    $leadImport = $this->getLeadImport($pipedriveIntegration);
                    $leadImport->update($data);
                    break;
                case self::LEAD_DELETE_EVENT:
                    $leadImport = $this->getLeadImport($pipedriveIntegration);
                    $leadImport->delete($params['previous']);
                    break;
                case self::COMPANY_UPDATE_EVENT:
                    $companyImport = $this->getCompanyImport($pipedriveIntegration);
                    $companyImport->update($data);
                    break;
                case self::COMPANY_DELETE_EVENT:
                    $companyImport = $this->getCompanyImport($pipedriveIntegration);
                    $companyImport->delete($params['previous']);
                    break;
                case self::USER_UPDATE_EVENT:
                    $ownerImport = $this->getOwnerImport($pipedriveIntegration);
                    $ownerImport->create($data[0]);
                    break;
                default:
                    $response = [
                        'status' => 'unsupported event',
                    ];
            }
        } catch (\Exception $e) {
            return new JsonResponse([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], $this->getErrorCodeFromException($e));
        }

        return new JsonResponse($response, Response::HTTP_OK);
    }

    /**
     * @return bool
     */
    private function validCredential(Request $request, AdkingIntegration $pipedriveIntegration)
    {
        $headers = $request->headers->all();
        $keys    = $pipedriveIntegration->getKeys();

        if (!isset($headers['authorization']) || !isset($keys['user']) || !isset($keys['password'])) {
            return false;
        }

        $basicAuthBase64       = explode(' ', $headers['authorization'][0]);
        $decodedBasicAuth      = base64_decode($basicAuthBase64[1]);
        list($user, $password) = explode(':', $decodedBasicAuth);

        if ($keys['user'] == $user && $keys['password'] == $password) {
            return true;
        }

        return false;
    }
}
