<?php

namespace App\Models;

use CodeIgniter\Model;

class LockerApiModel extends Model
{
    protected $table = 'lockrs';
    protected $primaryKey = 'LOCKR_CD';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = [
        'COMP_CD',
        'BCOFF_CD',
        'LOCKR_KND',
        'LOCKR_TYPE_CD',
        'X',
        'Y',
        'LOCKR_LABEL',
        'ROTATION',
        'DOOR_DIRECTION',
        'FRONT_VIEW_X',
        'FRONT_VIEW_Y',
        'GROUP_NUM',
        'LOCKR_GENDR_SET',
        'LOCKR_NO',
        'PARENT_LOCKR_CD',
        'TIER_LEVEL',
        'BUY_EVENT_SNO',
        'MEM_SNO',
        'MEM_NM',
        'LOCKR_USE_S_DATE',
        'LOCKR_USE_E_DATE',
        'LOCKR_STAT',
        'MEMO',
        'UPDATE_BY',
        'UPDATE_DT'
    ];
    
    protected $useTimestamps = false;
    
    protected $validationRules = [
        'COMP_CD' => 'required|max_length[10]',
        'BCOFF_CD' => 'required|max_length[10]',
        'LOCKR_KND' => 'max_length[10]',
        'LOCKR_TYPE_CD' => 'required|max_length[10]',
        'X' => 'required|integer',
        'Y' => 'required|integer',
        'LOCKR_LABEL' => 'required|max_length[50]',
        'ROTATION' => 'integer',
        'LOCKR_STAT' => 'required|max_length[2]'
    ];
    
    /**
     * Get locker zones
     */
    public function getZones($compCd = '001', $bcoffCd = '001')
    {
        // Check if lockr_area table exists
        $query = $this->db->query("SHOW TABLES LIKE 'lockr_area'");
        $result = $query->getResult();
        
        if (empty($result)) {
            // Return default zones if table doesn't exist
            return [
                [
                    'LOCKR_KND_CD' => 'zone-1',
                    'LOCKR_KND_NM' => '남자 탈의실',
                    'X' => 0,
                    'Y' => 0,
                    'WIDTH' => 800,
                    'HEIGHT' => 600,
                    'COLOR' => '#f0f9ff'
                ],
                [
                    'LOCKR_KND_CD' => 'zone-2',
                    'LOCKR_KND_NM' => '여자 탈의실',
                    'X' => 0,
                    'Y' => 0,
                    'WIDTH' => 800,
                    'HEIGHT' => 600,
                    'COLOR' => '#fef3c7'
                ],
                [
                    'LOCKR_KND_CD' => 'zone-3',
                    'LOCKR_KND_NM' => '혼용 탈의실',
                    'X' => 0,
                    'Y' => 0,
                    'WIDTH' => 800,
                    'HEIGHT' => 600,
                    'COLOR' => '#fee2e2'
                ]
            ];
        }
        
        $builder = $this->db->table('lockr_area');
        $builder->where('COMP_CD', $compCd);
        $builder->where('BCOFF_CD', $bcoffCd);
        
        $result = $builder->get()->getResultArray();
        
        // If no zones exist, return default zones
        if (empty($result)) {
            return [
                [
                    'ZONE_CD' => 'Z001',
                    'ZONE_NM' => '남자 락커존',
                    'ZONE_TYPE' => 'male',
                    'X' => 100,
                    'Y' => 100,
                    'WIDTH' => 800,
                    'HEIGHT' => 600,
                    'COLOR' => '#3B82F6'
                ],
                [
                    'ZONE_CD' => 'Z002',
                    'ZONE_NM' => '여자 락커존',
                    'ZONE_TYPE' => 'female',
                    'X' => 1000,
                    'Y' => 100,
                    'WIDTH' => 800,
                    'HEIGHT' => 600,
                    'COLOR' => '#EC4899'
                ]
            ];
        }
        
        return $result;
    }
    
    /**
     * Get locker types
     */
    public function getLockerTypes()
    {
        // Check if lockr_types table exists
        $query = $this->db->query("SHOW TABLES LIKE 'lockr_types'");
        $result = $query->getResult();
        
        if (empty($result)) {
            // Return default types if table doesn't exist
            return [
                [
                    'LOCKR_TYPE_CD' => '1',
                    'LOCKR_TYPE_NM' => '소형',
                    'WIDTH' => 40,
                    'HEIGHT' => 40,
                    'DEPTH' => 40,
                    'COLOR' => '#3b82f6'
                ],
                [
                    'LOCKR_TYPE_CD' => '2',
                    'LOCKR_TYPE_NM' => '중형',
                    'WIDTH' => 50,
                    'HEIGHT' => 60,
                    'DEPTH' => 50,
                    'COLOR' => '#10b981'
                ],
                [
                    'LOCKR_TYPE_CD' => '3',
                    'LOCKR_TYPE_NM' => '대형',
                    'WIDTH' => 60,
                    'HEIGHT' => 80,
                    'DEPTH' => 60,
                    'COLOR' => '#f59e0b'
                ]
            ];
        }
        
        $builder = $this->db->table('lockr_types');
        
        $result = $builder->get()->getResultArray();
        
        // If no types exist, return default types
        if (empty($result)) {
            return [
                [
                    'TYPE_CD' => '1',
                    'TYPE_NM' => 'Small',
                    'WIDTH' => 40,
                    'HEIGHT' => 40,
                    'DEPTH' => 40,
                    'COLOR' => '#3b82f6',
                    'PRICE' => 30000
                ],
                [
                    'TYPE_CD' => '2',
                    'TYPE_NM' => 'Medium',
                    'WIDTH' => 50,
                    'HEIGHT' => 60,
                    'DEPTH' => 50,
                    'COLOR' => '#10b981',
                    'PRICE' => 40000
                ],
                [
                    'TYPE_CD' => '3',
                    'TYPE_NM' => 'Large',
                    'WIDTH' => 60,
                    'HEIGHT' => 80,
                    'DEPTH' => 60,
                    'COLOR' => '#f59e0b',
                    'PRICE' => 50000
                ]
            ];
        }
        
        return $result;
    }
    
    /**
     * Get all lockers with optional filters
     */
    public function getLockers($filters = [])
    {
        $builder = $this->builder();
        
        // Apply filters
        if (!empty($filters['COMP_CD'])) {
            $builder->where('COMP_CD', $filters['COMP_CD']);
        }
        if (!empty($filters['BCOFF_CD'])) {
            $builder->where('BCOFF_CD', $filters['BCOFF_CD']);
        }
        if (!empty($filters['LOCKR_KND'])) {
            $builder->where('LOCKR_KND', $filters['LOCKR_KND']);
        }
        if (!empty($filters['LOCKR_STAT'])) {
            $builder->where('LOCKR_STAT', $filters['LOCKR_STAT']);
        }
        if (isset($filters['parent_only']) && $filters['parent_only']) {
            $builder->where('PARENT_LOCKR_CD IS NULL');
        }
        
        // Order by position
        $builder->orderBy('Y', 'ASC');
        $builder->orderBy('X', 'ASC');
        $builder->orderBy('TIER_LEVEL', 'ASC');
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Get children lockers of a parent
     */
    public function getChildrenLockers($parentId)
    {
        return $this->where('PARENT_LOCKR_CD', $parentId)
                    ->orderBy('TIER_LEVEL', 'ASC')
                    ->findAll();
    }
    
    /**
     * Create locker with tiers
     */
    public function createLockerWithTiers($lockerData, $tierCount = 0)
    {
        $this->db->transStart();
        
        try {
            // Create parent locker
            $parentId = $this->insert($lockerData);
            
            if (!$parentId) {
                throw new \Exception('Failed to create parent locker');
            }
            
            // Create tiers if requested
            if ($tierCount > 0) {
                for ($i = 1; $i <= $tierCount; $i++) {
                    $tierData = $lockerData;
                    $tierData['PARENT_LOCKR_CD'] = $parentId;
                    $tierData['TIER_LEVEL'] = $i;
                    $tierData['LOCKR_LABEL'] = $lockerData['LOCKR_LABEL'] . '-T' . $i;
                    
                    if (!$this->insert($tierData)) {
                        throw new \Exception("Failed to create tier $i");
                    }
                }
            }
            
            $this->db->transComplete();
            
            if ($this->db->transStatus() === false) {
                throw new \Exception('Transaction failed');
            }
            
            return $parentId;
            
        } catch (\Exception $e) {
            $this->db->transRollback();
            throw $e;
        }
    }
    
    /**
     * Update locker position
     */
    public function updatePosition($lockerId, $x, $y, $rotation = null)
    {
        $data = [
            'X' => $x,
            'Y' => $y
        ];
        
        if ($rotation !== null) {
            $data['ROTATION'] = $rotation;
        }
        
        return $this->update($lockerId, $data);
    }
    
    /**
     * Assign locker to member
     */
    public function assignToMember($lockerId, $memberData)
    {
        $data = [
            'MEM_SNO' => $memberData['MEM_SNO'] ?? null,
            'MEM_NM' => $memberData['MEM_NM'] ?? null,
            'LOCKR_USE_S_DATE' => $memberData['LOCKR_USE_S_DATE'] ?? date('Y-m-d'),
            'LOCKR_USE_E_DATE' => $memberData['LOCKR_USE_E_DATE'] ?? null,
            'BUY_EVENT_SNO' => $memberData['BUY_EVENT_SNO'] ?? null,
            'LOCKR_STAT' => '01', // Occupied
            'UPDATE_BY' => session()->get('user_id') ?? 'system',
            'UPDATE_DT' => date('Y-m-d H:i:s')
        ];
        
        return $this->update($lockerId, $data);
    }
    
    /**
     * Release locker from member
     */
    public function releaseFromMember($lockerId)
    {
        $data = [
            'MEM_SNO' => null,
            'MEM_NM' => null,
            'LOCKR_USE_S_DATE' => null,
            'LOCKR_USE_E_DATE' => null,
            'BUY_EVENT_SNO' => null,
            'LOCKR_STAT' => '00', // Available
            'UPDATE_BY' => session()->get('user_id') ?? 'system',
            'UPDATE_DT' => date('Y-m-d H:i:s')
        ];
        
        return $this->update($lockerId, $data);
    }
    
    /**
     * Get locker statistics
     */
    public function getStatistics($compCd = '001', $bcoffCd = '001')
    {
        $builder = $this->builder();
        
        // Total lockers
        $total = $builder->where('COMP_CD', $compCd)
                        ->where('BCOFF_CD', $bcoffCd)
                        ->where('PARENT_LOCKR_CD IS NULL')
                        ->countAllResults(false);
        
        // Available lockers
        $available = $builder->where('LOCKR_STAT', '00')
                            ->countAllResults(false);
        
        // Occupied lockers
        $occupied = $builder->where('LOCKR_STAT', '01')
                           ->countAllResults(false);
        
        // Maintenance lockers
        $maintenance = $builder->where('LOCKR_STAT', '03')
                              ->countAllResults(false);
        
        // Expired lockers
        $expired = $builder->where('LOCKR_STAT', '05')
                          ->countAllResults(false);
        
        return [
            'total' => $total,
            'available' => $available,
            'occupied' => $occupied,
            'maintenance' => $maintenance,
            'expired' => $expired,
            'occupancy_rate' => $total > 0 ? round(($occupied / $total) * 100, 2) : 0
        ];
    }
}