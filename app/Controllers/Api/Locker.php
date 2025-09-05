<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\LockerApiModel;

class Locker extends ResourceController
{
    use ResponseTrait;
    
    protected $modelName = 'App\Models\LockerApiModel';
    protected $format = 'json';
    
    public function __construct()
    {
        helper(['form', 'url']);
    }
    
    /**
     * Get all lockers
     * GET /api/locker
     */
    public function index()
    {
        try {
            $model = new LockerApiModel();
            
            // Get query parameters
            $compCd = $this->request->getGet('COMP_CD') ?? '001';
            $bcoffCd = $this->request->getGet('BCOFF_CD') ?? '001';
            $parentOnly = $this->request->getGet('parentOnly');
            
            // Build query
            $builder = $model->where('COMP_CD', $compCd)
                            ->where('BCOFF_CD', $bcoffCd);
            
            // Filter parent lockers only if requested
            if ($parentOnly === 'true') {
                $builder->where('PARENT_LOCKR_CD IS NULL');
            }
            
            $lockers = $builder->findAll();
            
            // Return in Node.js compatible format
            return $this->respond([
                'success' => true,
                'lockers' => $lockers ?: []
            ]);
            
        } catch (\Exception $e) {
            return $this->failServerError('Failed to fetch lockers: ' . $e->getMessage());
        }
    }
    
    /**
     * Get single locker
     * GET /api/locker/{id}
     */
    public function show($id = null)
    {
        try {
            $model = new LockerApiModel();
            $locker = $model->find($id);
            
            if (!$locker) {
                return $this->failNotFound('Locker not found');
            }
            
            // Get children if parent
            $children = [];
            if (empty($locker['PARENT_LOCKR_CD'])) {
                $children = $model->where('PARENT_LOCKR_CD', $id)->findAll();
            }
            
            return $this->respond([
                'status' => 'success',
                'data' => [
                    'locker' => $locker,
                    'children' => $children
                ]
            ]);
            
        } catch (\Exception $e) {
            return $this->failServerError('Failed to fetch locker: ' . $e->getMessage());
        }
    }
    
    /**
     * Create new locker
     * POST /api/locker
     */
    public function create()
    {
        try {
            $model = new LockerApiModel();
            
            // Get JSON data
            $data = $this->request->getJSON(true);
            
            // Add default values
            $data['COMP_CD'] = $data['COMP_CD'] ?? '001';
            $data['BCOFF_CD'] = $data['BCOFF_CD'] ?? '001';
            $data['TIER_LEVEL'] = $data['TIER_LEVEL'] ?? 0;
            $data['LOCKR_STAT'] = $data['LOCKR_STAT'] ?? '00'; // Available by default
            $data['UPDATE_BY'] = session()->get('user_id') ?? 'system';
            $data['UPDATE_DT'] = date('Y-m-d H:i:s');
            
            // Insert locker
            $lockrCd = $model->insert($data);
            
            if (!$lockrCd) {
                return $this->fail($model->errors() ?: 'Failed to create locker', 400);
            }
            
            // Return created locker
            $locker = $model->find($lockrCd);
            
            return $this->respondCreated([
                'status' => 'success',
                'data' => $locker,
                'message' => 'Locker created successfully'
            ]);
            
        } catch (\Exception $e) {
            return $this->failServerError('Failed to create locker: ' . $e->getMessage());
        }
    }
    
    /**
     * Update locker
     * PUT /api/locker/{id}
     */
    public function update($id = null)
    {
        try {
            $model = new LockerApiModel();
            
            // Check if locker exists
            $existing = $model->find($id);
            if (!$existing) {
                return $this->failNotFound('Locker not found');
            }
            
            // Get JSON data
            $data = $this->request->getJSON(true);
            
            // Convert lowercase keys to uppercase for database compatibility
            $upperData = [];
            foreach ($data as $key => $value) {
                $upperData[strtoupper($key)] = $value;
            }
            
            // Add update metadata
            $upperData['UPDATE_BY'] = session()->get('user_id') ?? 'system';
            $upperData['UPDATE_DT'] = date('Y-m-d H:i:s');
            
            // Skip validation for partial updates
            $model->skipValidation(true);
            
            // Update locker
            if (!$model->update($id, $upperData)) {
                return $this->fail($model->errors() ?: 'Failed to update locker', 400);
            }
            
            // Return updated locker
            $locker = $model->find($id);
            
            return $this->respond([
                'status' => 'success',
                'data' => $locker,
                'message' => 'Locker updated successfully'
            ]);
            
        } catch (\Exception $e) {
            return $this->failServerError('Failed to update locker: ' . $e->getMessage());
        }
    }
    
    /**
     * Delete locker
     * DELETE /api/locker/{id}
     */
    public function delete($id = null)
    {
        try {
            $model = new LockerApiModel();
            
            // Check if locker exists
            $locker = $model->find($id);
            if (!$locker) {
                return $this->failNotFound('Locker not found');
            }
            
            // Check for children
            $children = $model->where('PARENT_LOCKR_CD', $id)->countAllResults();
            if ($children > 0) {
                return $this->fail('Cannot delete locker with children', 400);
            }
            
            // Delete locker
            if (!$model->delete($id)) {
                return $this->failServerError('Failed to delete locker');
            }
            
            return $this->respondDeleted([
                'status' => 'success',
                'message' => 'Locker deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            return $this->failServerError('Failed to delete locker: ' . $e->getMessage());
        }
    }
    
    /**
     * Add tiers to a locker
     * POST /api/locker/{id}/tiers
     */
    public function addTiers($id = null)
    {
        try {
            $model = new LockerApiModel();
            
            // Check if parent locker exists
            $parent = $model->find($id);
            if (!$parent) {
                return $this->failNotFound('Parent locker not found');
            }
            
            // Get tier configuration
            $data = $this->request->getJSON(true);
            $tierCount = $data['tier_count'] ?? 4;
            $tierPrefix = $data['tier_prefix'] ?? 'T';
            
            $createdTiers = [];
            
            for ($i = 1; $i <= $tierCount; $i++) {
                $tierData = [
                    'COMP_CD' => $parent['COMP_CD'],
                    'BCOFF_CD' => $parent['BCOFF_CD'],
                    'LOCKR_KND' => $parent['LOCKR_KND'],
                    'LOCKR_TYPE_CD' => $parent['LOCKR_TYPE_CD'],
                    'LOCKR_LABEL' => $parent['LOCKR_LABEL'] . '-' . $tierPrefix . $i,
                    'X' => $parent['X'],
                    'Y' => $parent['Y'],
                    'ROTATION' => $parent['ROTATION'],
                    'PARENT_LOCKR_CD' => $id,
                    'TIER_LEVEL' => $i,
                    'LOCKR_STAT' => '00',
                    'UPDATE_BY' => session()->get('user_id') ?? 'system',
                    'UPDATE_DT' => date('Y-m-d H:i:s')
                ];
                
                $tierId = $model->insert($tierData);
                if ($tierId) {
                    $createdTiers[] = $model->find($tierId);
                }
            }
            
            return $this->respondCreated([
                'status' => 'success',
                'data' => $createdTiers,
                'message' => count($createdTiers) . ' tiers created successfully'
            ]);
            
        } catch (\Exception $e) {
            return $this->failServerError('Failed to add tiers: ' . $e->getMessage());
        }
    }
    
    /**
     * Batch update lockers
     * PUT /api/locker/batch
     */
    public function batchUpdate()
    {
        try {
            $model = new LockerApiModel();
            $lockers = $this->request->getJSON(true);
            
            if (!is_array($lockers)) {
                return $this->fail('Invalid data format', 400);
            }
            
            $updated = 0;
            $errors = [];
            
            foreach ($lockers as $lockerData) {
                if (!isset($lockerData['LOCKR_CD'])) {
                    $errors[] = 'Missing LOCKR_CD in one of the records';
                    continue;
                }
                
                $id = $lockerData['LOCKR_CD'];
                unset($lockerData['LOCKR_CD']);
                
                $lockerData['UPDATE_BY'] = session()->get('user_id') ?? 'system';
                $lockerData['UPDATE_DT'] = date('Y-m-d H:i:s');
                
                if ($model->update($id, $lockerData)) {
                    $updated++;
                } else {
                    $errors[] = "Failed to update locker ID: $id";
                }
            }
            
            return $this->respond([
                'status' => 'success',
                'updated' => $updated,
                'errors' => $errors,
                'message' => "$updated lockers updated successfully"
            ]);
            
        } catch (\Exception $e) {
            return $this->failServerError('Failed to batch update: ' . $e->getMessage());
        }
    }
    
    /**
     * Handle OPTIONS requests for CORS
     */
    public function options()
    {
        return $this->response
            ->setHeader('Access-Control-Allow-Origin', '*')
            ->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With')
            ->setHeader('Access-Control-Max-Age', '86400')
            ->setStatusCode(200);
    }
    
    /**
     * Get all zones
     * GET /api/zones
     * Node.js API compatible endpoint
     */
    public function zones()
    {
        try {
            $compCd = $this->request->getGet('COMP_CD') ?? '001';
            $bcoffCd = $this->request->getGet('BCOFF_CD') ?? '001';
            
            $model = new LockerApiModel();
            $zones = $model->getZones($compCd, $bcoffCd);
            
            // Return in Node.js compatible format
            return $this->respond([
                'success' => true,
                'zones' => $zones,
                'count' => count($zones),
                'source' => empty($zones) ? 'default' : 'database'
            ]);
            
        } catch (\Exception $e) {
            return $this->failServerError('Failed to load zones: ' . $e->getMessage());
        }
    }
    
    /**
     * Get all locker types
     * GET /api/types
     * Node.js API compatible endpoint
     */
    public function types()
    {
        try {
            $model = new LockerApiModel();
            $types = $model->getLockerTypes();
            
            // Return in Node.js compatible format
            return $this->respond([
                'success' => true,
                'types' => $types,
                'count' => count($types),
                'source' => empty($types) ? 'default' : 'database'
            ]);
            
        } catch (\Exception $e) {
            return $this->failServerError('Failed to load types: ' . $e->getMessage());
        }
    }
}