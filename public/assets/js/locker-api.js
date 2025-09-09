// 락커 API 서비스 (Locker4 호환)
(function() {
    'use strict';

    // API 설정 - PHP API만 사용
    const API_CONFIG = {
        phpApiUrl: window.LockerConfig ? window.LockerConfig.baseUrl + '/api' : '/api'
    };

    // API 서비스 객체
    window.LockerAPI = {
        // 기본 API URL 가져오기
        getApiUrl: function() {
            return API_CONFIG.phpApiUrl;
        },

        // CSRF 토큰 헤더 가져오기
        getCsrfHeaders: function() {
            const headers = {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            };
            
            if (window.LockerConfig && window.LockerConfig.csrfHeader) {
                headers[window.LockerConfig.csrfHeader] = window.LockerConfig.csrfHash;
            }
            
            return headers;
        },

        // 락커 타입 목록 조회
        getLockerTypes: async function() {
            try {
                const response = await fetch(`${this.getApiUrl()}/locker/types`, {
                    method: 'GET',
                    headers: this.getCsrfHeaders()
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                
                if (data.status === 'success') {
                    return data.types || [];
                } else {
                    console.error('[API] Error:', data.message);
                    return [];
                }
            } catch (error) {
                console.error('[API] Failed to fetch locker types:', error);
                
                // 폴백: 하드코딩된 타입 반환
                return [
                    { id: '1', name: '소형', width: 40, depth: 40, height: 40, color: '#3b82f6' },
                    { id: '2', name: '중형', width: 50, depth: 50, height: 60, color: '#10b981' },
                    { id: '3', name: '대형', width: 60, depth: 60, height: 80, color: '#f59e0b' }
                ];
            }
        },

        // 구역 목록 조회
        getZones: async function() {
            try {
                const response = await fetch(`${this.getApiUrl()}/locker/zones`, {
                    method: 'GET',
                    headers: this.getCsrfHeaders()
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                
                if (data.status === 'success') {
                    return data.zones || [];
                } else {
                    console.error('[API] Error:', data.message);
                    return [];
                }
            } catch (error) {
                console.error('[API] Failed to fetch zones:', error);
                
                // 폴백: 하드코딩된 구역 반환
                return [
                    { id: 1, name: 'A구역', lockerCount: 0 },
                    { id: 2, name: 'B구역', lockerCount: 0 },
                    { id: 3, name: 'C구역', lockerCount: 0 }
                ];
            }
        },

        // 락커 목록 조회 (PHP Controller 사용)
        getLockers: async function(compCd, bcoffCd, zoneId) {
            try {
                const params = new URLSearchParams();
                if (compCd) params.append('comp_cd', compCd);
                if (bcoffCd) params.append('bcoff_cd', bcoffCd);
                if (zoneId) params.append('zone_id', zoneId);

                const url = `${this.getApiUrl()}/locker/lockers?${params}`;

                const response = await fetch(url, {
                    method: 'GET',
                    headers: this.getCsrfHeaders()
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                
                if (data.status === 'success' || data.lockers) {
                    const lockers = data.lockers || [];
                    
                    // Locker4 형식에서 앱 형식으로 변환
                    return lockers.map(dbLocker => this.convertDbToApp(dbLocker));
                } else {
                    console.error('[API] Error:', data.message);
                    return [];
                }
            } catch (error) {
                console.error('[API] Failed to fetch lockers:', error);
                return [];
            }
        },

        // 락커 저장
        saveLocker: async function(locker) {
            try {
                const dbLocker = this.convertAppToDb(locker);
                const isNew = !locker.lockrCd;
                
                const url = isNew 
                    ? `${this.getApiUrl()}/locker/lockers`
                    : `${this.getApiUrl()}/locker/lockers/${locker.lockrCd}`;

                const response = await fetch(url, {
                    method: isNew ? 'POST' : 'PUT',
                    headers: this.getCsrfHeaders(),
                    body: JSON.stringify(dbLocker)
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                
                if (data.status === 'success' || data.locker) {
                    return this.convertDbToApp(data.locker);
                } else {
                    console.error('[API] Error:', data.message);
                    return null;
                }
            } catch (error) {
                console.error('[API] Failed to save locker:', error);
                return null;
            }
        },

        // 락커 삭제
        deleteLocker: async function(lockrCd) {
            try {
                const url = `${this.getApiUrl()}/locker/lockers/${lockrCd}`;

                const response = await fetch(url, {
                    method: 'DELETE',
                    headers: this.getCsrfHeaders()
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                return data.status === 'success';
            } catch (error) {
                console.error('[API] Failed to delete locker:', error);
                return false;
            }
        },

        // 구역 추가
        addZone: async function(zoneName) {
            try {
                const response = await fetch(`${this.getApiUrl()}/locker/zones`, {
                    method: 'POST',
                    headers: this.getCsrfHeaders(),
                    body: JSON.stringify({ zone_nm: zoneName })
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                return data.status === 'success' ? data.zone : null;
            } catch (error) {
                console.error('[API] Failed to add zone:', error);
                return null;
            }
        },

        // DB 형식을 앱 형식으로 변환
        convertDbToApp: function(dbLocker) {
            const typeCode = String(dbLocker.LOCKR_TYPE_CD || '1');
            const typeInfo = this.getTypeInfo(typeCode);
            
            return {
                id: dbLocker.LOCKR_CD ? `locker-${dbLocker.LOCKR_CD}` : null,
                lockrCd: dbLocker.LOCKR_CD,
                number: dbLocker.LOCKR_LABEL || '',
                x: dbLocker.X || 0,
                y: dbLocker.Y || 0,
                width: typeInfo.width,
                height: typeInfo.height,
                depth: typeInfo.depth,
                color: typeInfo.color,
                rotation: dbLocker.ROTATION || 0,
                zoneId: dbLocker.LOCKR_KND,
                typeId: dbLocker.LOCKR_TYPE_CD,
                status: this.mapDbStatusToApp(dbLocker.LOCKR_STAT),
                compCd: dbLocker.COMP_CD,
                bcoffCd: dbLocker.BCOFF_CD,
                frontViewX: dbLocker.FRONT_VIEW_X,
                frontViewY: dbLocker.FRONT_VIEW_Y,
                lockrNo: dbLocker.LOCKR_NO
            };
        },

        // 앱 형식을 DB 형식으로 변환
        convertAppToDb: function(appLocker) {
            return {
                LOCKR_CD: appLocker.lockrCd,
                COMP_CD: appLocker.compCd || window.LockerConfig?.companyCode || '001',
                BCOFF_CD: appLocker.bcoffCd || window.LockerConfig?.officeCode || '001',
                LOCKR_KND: appLocker.zoneId || '',
                LOCKR_TYPE_CD: appLocker.typeId || '1',
                X: Math.round(appLocker.x || 0),
                Y: Math.round(appLocker.y || 0),
                LOCKR_LABEL: appLocker.number || '',
                ROTATION: appLocker.rotation || 0,
                LOCKR_STAT: this.mapAppStatusToDb(appLocker.status),
                FRONT_VIEW_X: appLocker.frontViewX,
                FRONT_VIEW_Y: appLocker.frontViewY,
                LOCKR_NO: appLocker.lockrNo
            };
        },

        // 타입 정보 가져오기
        getTypeInfo: function(typeCode) {
            const typeMap = {
                '1': { width: 40, height: 40, depth: 40, color: '#3b82f6' },
                '2': { width: 50, height: 60, depth: 50, color: '#10b981' },
                '3': { width: 60, height: 80, depth: 60, color: '#f59e0b' },
                '4': { width: 70, height: 100, depth: 70, color: '#8b5cf6' }
            };
            return typeMap[typeCode] || typeMap['1'];
        },

        // DB 상태를 앱 상태로 매핑
        mapDbStatusToApp: function(dbStatus) {
            const statusMap = {
                '00': 'available',
                '01': 'occupied',
                '02': 'reserved',
                '03': 'maintenance',
                '04': 'disabled',
                '05': 'expired'
            };
            return statusMap[dbStatus] || 'available';
        },

        // 앱 상태를 DB 상태로 매핑
        mapAppStatusToDb: function(appStatus) {
            const statusMap = {
                'available': '00',
                'occupied': '01',
                'reserved': '02',
                'maintenance': '03',
                'disabled': '04',
                'expired': '05'
            };
            return statusMap[appStatus] || '00';
        },

        // 레이아웃 저장
        saveLayout: async function(zones, lockers) {
            try {
                const response = await fetch(`${this.getApiUrl()}/locker/layout`, {
                    method: 'POST',
                    headers: this.getCsrfHeaders(),
                    body: JSON.stringify({
                        zones: zones,
                        lockers: lockers.map(l => this.convertAppToDb(l))
                    })
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                return data.status === 'success';
            } catch (error) {
                console.error('[API] Failed to save layout:', error);
                return false;
            }
        }
    };

})();